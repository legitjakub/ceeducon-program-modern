const DATA_URL = window.CEEDUCON_DATA_URL || "data/program.json";
const FAVORITES_KEY = "ceeducon-2026-favorites";
const PERIODS = [
  { id: "", label: "All day" },
  { id: "morning", label: "Morning" },
  { id: "afternoon", label: "Afternoon" },
];

// Favourites live in localStorage, which anything on the origin can overwrite.
// This runs while `state` is being built, so an unguarded parse of a corrupted
// value would throw before the programme ever renders — and keep throwing on
// every reload. Fall back to an empty list instead.
function readFavorites() {
  try {
    const stored = JSON.parse(localStorage.getItem(FAVORITES_KEY) || "[]");
    return Array.isArray(stored) ? stored.filter((id) => typeof id === "string") : [];
  } catch {
    return [];
  }
}

const state = {
  data: null,
  dayIndex: 0,
  theme: "",
  period: "",
  query: "",
  favoritesOnly: false,
  favorites: new Set(readFavorites()),
  sessionMap: new Map(),
  modalSessionId: "",
  modalSession: null,
  modalReturnFocus: null,
};

const elements = {
  schedule: document.querySelector("[data-schedule]"),
  dayBar: document.querySelector("[data-day-bar]"),
  themeFilters: document.querySelector("[data-theme-filters]"),
  periodFilters: document.querySelector("[data-period-filters]"),
  search: document.getElementById("program-search"),
  resultCount: document.querySelector("[data-result-count]"),
  resetButtons: document.querySelectorAll("[data-reset-filters], [data-empty-reset]"),
  empty: document.querySelector("[data-empty]"),
  filterToggle: document.querySelector("[data-filter-toggle]"),
  filterDrawer: document.querySelector("[data-filter-drawer]"),
  favoritesToggle: document.querySelector("[data-favorites-toggle]"),
  favoriteCount: document.querySelector("[data-favorite-count]"),
  modalBackdrop: document.querySelector("[data-modal-backdrop]"),
  modalTitle: document.querySelector("[data-modal-title]"),
  modalTheme: document.querySelector("[data-modal-theme]"),
  modalTrack: document.querySelector("[data-modal-track]"),
  modalTime: document.querySelector("[data-modal-time]"),
  modalRoom: document.querySelector("[data-modal-room]"),
  modalFavorite: document.querySelector("[data-modal-favorite]"),
  modalNote: document.querySelector("[data-modal-note]"),
  toast: document.querySelector("[data-toast]"),
};

function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

function toMinutes(time) {
  const [hours, minutes] = time.split(":").map(Number);
  return hours * 60 + minutes;
}

function currentDay() {
  return state.data.days[state.dayIndex];
}

function themeById(id) {
  return state.data.themes.find((theme) => theme.id === id);
}

function getThemeLabel(themeId) {
  return themeById(themeId)?.label || "";
}

function formatById(id) {
  return (state.data.formats || []).find((format) => format.id === id);
}

function getFormatLabel(formatId) {
  return formatById(formatId)?.label || formatId || "Conference session";
}

function getSessionCategoryLabel(session) {
  return getThemeLabel(session.theme) || getFormatLabel(session.format);
}

function sessionId(day, slot, session) {
  return `${slot.id}-${session.rooms.join("-")}`.replaceAll("+", "plus");
}

function roomPlacement(session) {
  const indexes = session.rooms
    .map((room) => state.data.rooms.indexOf(room))
    .filter((index) => index >= 0)
    .sort((a, b) => a - b);
  const first = indexes[0] ?? 0;
  const last = indexes[indexes.length - 1] ?? first;
  return {
    start: first + 1,
    span: Math.max(1, last - first + 1),
  };
}

function speakersText(speakers) {
  if (!speakers || !speakers.length) return "";
  if (speakers.length === 1 && speakers[0] === "tbc") return "Speakers: to be confirmed.";
  return `Speakers: ${speakers.map((s) => (s === "tbc" ? "tbc" : s)).join("; ")}.`;
}

function speakersPreview(speakers) {
  if (!speakers || !speakers.length) return "";
  if (speakers.length === 1 && speakers[0] === "tbc") return "Speakers to be confirmed";
  const names = speakers
    .filter((speaker) => speaker && speaker !== "tbc")
    .slice(0, 2)
    .map((speaker) => speaker.replace(/\s*\(.+\)\s*$/, ""));
  if (!names.length) return "Speakers to be confirmed";
  return speakers.length > 2 ? `${names.join("; ")} + ${speakers.length - 2} more` : names.join("; ");
}

function activeFilters() {
  return Boolean(state.theme || state.period || state.query || state.favoritesOnly);
}

function matchesSlotPeriod(slot) {
  if (!state.period) return true;
  const start = toMinutes(slot.start);
  const end = toMinutes(slot.end);
  if (state.period === "morning") return start < 12 * 60;
  if (state.period === "afternoon") return start >= 12 * 60 && end > 12 * 60;
  return true;
}

function matchesSession(session, id) {
  if (state.theme && session.theme !== state.theme) return false;
  if (state.favoritesOnly && !state.favorites.has(id)) return false;
  if (state.query) {
    const haystack = `${session.title} ${session.rooms.join(" ")} ${getSessionCategoryLabel(session)} ${getFormatLabel(session.format)} ${(session.speakers || []).join(" ")}`.toLocaleLowerCase("en");
    if (!haystack.includes(state.query)) return false;
  }
  return true;
}

function matchesBreakSlot(slot) {
  if (state.theme || state.favoritesOnly) return false;
  if (state.query) {
    return `${slot.title || ""}`.toLocaleLowerCase("en").includes(state.query);
  }
  return true;
}

function renderDayBar() {
  if (!elements.dayBar) return;
  elements.dayBar.innerHTML = state.data.days
    .map(
      (day, index) => `
    <button class="day-tab${index === state.dayIndex ? " is-active" : ""}" type="button" data-day-index="${index}" aria-pressed="${index === state.dayIndex}">
      <span>${escapeHtml(day.label)}</span><strong>${escapeHtml(day.title)}</strong>
    </button>`
    )
    .join("");
}

function renderFilters() {
  elements.themeFilters.innerHTML = state.data.themes.map((theme) => `
    <button
      class="filter-chip${state.theme === theme.id ? " is-active" : ""}"
      type="button"
      data-theme-filter="${escapeHtml(theme.id)}"
      style="--chip-color:${escapeHtml(theme.color)}"
      aria-pressed="${state.theme === theme.id}"
    ><i></i><span>${escapeHtml(theme.label)}</span></button>
  `).join("");

  elements.periodFilters.innerHTML = PERIODS.map((period) => `
    <button
      class="filter-chip filter-chip--period${state.period === period.id ? " is-active" : ""}"
      type="button"
      data-period-filter="${escapeHtml(period.id)}"
      aria-pressed="${state.period === period.id}"
    >${escapeHtml(period.label)}</button>
  `).join("");
}

function buildSessionCard(day, slot, session) {
  const id = sessionId(day, slot, session);
  const theme = themeById(session.theme) || { color: "#0d5e9d", softColor: "#ffffff", id: "" };
  const favorite = state.favorites.has(id);
  const themeLabel = getThemeLabel(session.theme);
  const formatLabel = getFormatLabel(session.format);
  const categoryLabel = themeLabel || formatLabel;
  const placement = roomPlacement(session);
  const wide = session.rooms.length > 1;
  const preview = speakersPreview(session.speakers || []);

  state.sessionMap.set(id, {
    id,
    title: session.title,
    rooms: session.rooms,
    start: slot.start,
    end: slot.end,
    theme: themeLabel,
    category: categoryLabel,
    format: formatLabel,
    color: theme.color,
    date: day.date,
    speakers: session.speakers || [],
    description: session.description || "",
  });

  return `
    <article class="session-card${wide ? " session-card--wide" : ""}" style="--track:${escapeHtml(theme.color)};--theme-soft:${escapeHtml(theme.softColor || "#ffffff")};--room-start:${placement.start};--room-span:${placement.span}" data-room-list="${escapeHtml(session.rooms.join(","))}">
      <div class="session-card-head">
        <span class="room-tag">${escapeHtml(session.rooms.join(" + "))}</span>
        <button class="favorite-star${favorite ? " is-active" : ""}" type="button" data-favorite="${escapeHtml(id)}" aria-label="${favorite ? "Remove from my programme" : "Add to my programme"}" aria-pressed="${favorite}">${favorite ? "★" : "☆"}</button>
      </div>
      <button class="session-card-open" type="button" data-session-open="${escapeHtml(id)}" title="${escapeHtml(session.title)}">
        <h3>${escapeHtml(session.title)}</h3>
        ${preview ? `<p class="session-speakers">${escapeHtml(preview)}</p>` : ""}
        <p class="session-theme">${escapeHtml(categoryLabel)}</p>
        <span class="session-arrow ui-icon" aria-hidden="true"><svg viewBox="0 0 16 16"><path d="M6 4h6v6M12 4 5 11"></path></svg></span>
      </button>
    </article>`;
}

function buildProgramBand(slot) {
  const variant = slot.break === "lunch" ? "lunch" : slot.break === "registration" ? "registration" : "coffee";
  const notes = {
    registration: "Participant registration and arrival",
    coffee: "Coffee, networking and room changes",
    lunch: "Lunch break and networking",
  };
  const icons = {
    registration: `<svg viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="1"/><path d="M8 8h8M8 12h5"/><circle cx="9" cy="16" r="2"/><path d="M13 18c.8-1.4 2-2 3.5-2"/></svg>`,
    coffee: `<svg viewBox="0 0 24 24"><path d="M5 9h11v3a5 5 0 0 1-5 5H10a5 5 0 0 1-5-5V9Z"/><path d="M16 10h2a2 2 0 0 1 0 4h-2M7 4v2M11 3v3M15 4v2M4 21h15"/></svg>`,
    lunch: `<svg viewBox="0 0 24 24"><path d="M3 3v5a3 3 0 0 0 6 0V3M6 3v18M16 3v18M16 3c3 2 4 7 0 10"/></svg>`,
  };
  return `
    <div class="program-band program-band--${variant}">
      <span class="program-band-icon" aria-hidden="true">${icons[variant]}</span>
      <p><strong>${escapeHtml(slot.title)}</strong><span>${escapeHtml(notes[variant])}</span></p>
    </div>`;
}

function renderSchedule() {
  state.sessionMap.clear();
  const day = currentDay();
  let visibleSessions = 0;
  const slots = [];
  const roomHeader = `
    <div class="room-grid-header" style="--room-count:${state.data.rooms.length}" aria-hidden="true">
      <span class="room-grid-spacer">Time</span>
      <div class="room-grid-labels">
        ${state.data.rooms.map((room) => `<span>${escapeHtml(room)}</span>`).join("")}
      </div>
    </div>`;

  for (const slot of day.slots) {
    if (!matchesSlotPeriod(slot)) continue;

    let content = "";

    if (slot.sessions) {
      const sessions = slot.sessions
        .filter((session) => matchesSession(session, sessionId(day, slot, session)))
        .sort((a, b) => roomPlacement(a).start - roomPlacement(b).start);
      visibleSessions += sessions.length;
      if (sessions.length) {
        content = `<div class="slot-heading"><span>${sessions.length === 1 ? "1 session" : `${sessions.length} sessions`}</span></div><div class="sessions-grid" style="--room-count:${state.data.rooms.length}">${sessions.map((session) => buildSessionCard(day, slot, session)).join("")}</div>`;
      }
    } else if (slot.type === "break" && matchesBreakSlot(slot)) {
      content = buildProgramBand(slot);
    }

    if (!content) continue;
    slots.push(`
      <section class="time-slot" id="slot-${escapeHtml(slot.id)}" data-slot-id="${escapeHtml(slot.id)}">
        <div class="slot-time"><strong>${escapeHtml(slot.start)}</strong><span>${escapeHtml(slot.end)}</span></div>
        <div class="slot-content">${content}</div>
      </section>`);
  }

  elements.schedule.innerHTML = slots.length ? roomHeader + slots.join("") : "";
  elements.empty.hidden = slots.length > 0;
  elements.schedule.hidden = slots.length === 0;
  elements.resultCount.textContent = activeFilters()
    ? `${visibleSessions} matching sessions shown`
    : `${day.label}: full programme · ${visibleSessions} sessions`;
}

function render() {
  renderDayBar();
  renderFilters();
  renderSchedule();
  updateFavoritesUI();
}

function updateFavoritesUI() {
  elements.favoriteCount.textContent = state.favorites.size;
  elements.favoritesToggle.setAttribute("aria-pressed", String(state.favoritesOnly));
  elements.favoritesToggle.classList.toggle("is-active", state.favoritesOnly);
}

function resetFilters() {
  state.theme = "";
  state.period = "";
  state.query = "";
  state.favoritesOnly = false;
  elements.search.value = "";
  render();
}

function saveFavorites() {
  localStorage.setItem(FAVORITES_KEY, JSON.stringify([...state.favorites]));
}

function toggleFavorite(id) {
  if (state.favorites.has(id)) {
    state.favorites.delete(id);
    showToast("Session removed from your programme.");
  } else {
    state.favorites.add(id);
    showToast("Session added to your programme.");
  }
  saveFavorites();
  render();
  if (state.modalSessionId === id) updateModalFavorite();
}

function openModal(id) {
  const session = state.sessionMap.get(id);
  if (!session) return;
  state.modalReturnFocus = document.activeElement instanceof HTMLElement ? document.activeElement : null;
  state.modalSessionId = id;
  state.modalSession = session;
  elements.modalTitle.textContent = session.title;
  elements.modalTheme.textContent = session.category;
  elements.modalTrack.style.background = session.color;
  elements.modalTime.textContent = `${session.start} – ${session.end}`;
  elements.modalRoom.textContent = session.rooms.join(" + ");
  const parts = [];
  if (session.description) parts.push(session.description);
  if (session.theme) parts.push(`Theme: ${session.theme}.`);
  parts.push(`Format: ${session.format}.`);
  const speakers = speakersText(session.speakers);
  if (speakers) parts.push(speakers);
  elements.modalNote.textContent = parts.join(" ");
  updateModalFavorite();
  elements.modalBackdrop.hidden = false;
  document.body.classList.add("modal-open");
  requestAnimationFrame(() => document.querySelector("[data-modal-close]").focus());
}

function updateModalFavorite() {
  const active = state.favorites.has(state.modalSessionId);
  elements.modalFavorite.classList.toggle("is-active", active);
  elements.modalFavorite.innerHTML = `<span>${active ? "★" : "☆"}</span> ${active ? "Remove from my programme" : "Add to my programme"}`;
}

function closeModal() {
  elements.modalBackdrop.hidden = true;
  document.body.classList.remove("modal-open");
  state.modalSessionId = "";
  state.modalSession = null;
  state.modalReturnFocus?.focus?.();
  state.modalReturnFocus = null;
}

function modalFocusableElements() {
  return Array.from(
    elements.modalBackdrop.querySelectorAll(
      'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
    )
  ).filter((element) => element.offsetParent !== null);
}

function trapModalFocus(event) {
  if (elements.modalBackdrop.hidden || event.key !== "Tab") return;
  const focusable = modalFocusableElements();
  if (!focusable.length) return;
  const first = focusable[0];
  const last = focusable[focusable.length - 1];

  if (event.shiftKey && document.activeElement === first) {
    event.preventDefault();
    last.focus();
  } else if (!event.shiftKey && document.activeElement === last) {
    event.preventDefault();
    first.focus();
  }
}

function compactDate(date) {
  return date.replaceAll("-", "");
}

function compactTime(time) {
  return time.replace(":", "") + "00";
}

function calendarDetails(session) {
  return [`CEEDUCON 2026 · ${session.category}`, `Format: ${session.format}`, speakersText(session.speakers)].filter(Boolean).join("\n");
}

function calendarLocation(session) {
  return `O2 universum Prague — ${session.rooms.join(" + ")}`;
}

function addToCalendar() {
  const session = state.modalSession;
  if (!session) return;
  const params = new URLSearchParams({
    action: "TEMPLATE",
    text: session.title,
    dates: `${compactDate(session.date)}T${compactTime(session.start)}/${compactDate(session.date)}T${compactTime(session.end)}`,
    ctz: "Europe/Prague",
    location: calendarLocation(session),
    details: calendarDetails(session),
  });
  window.open(`https://calendar.google.com/calendar/render?${params.toString()}`, "_blank", "noopener");
}

function addToOutlookCalendar() {
  const session = state.modalSession;
  if (!session) return;
  // Outlook's deeplink takes ISO datetimes with an explicit offset. The conference
  // runs in December, when Prague is on CET (UTC+01:00).
  const params = new URLSearchParams({
    path: "/calendar/action/compose",
    rru: "addevent",
    subject: session.title,
    startdt: `${session.date}T${session.start}:00+01:00`,
    enddt: `${session.date}T${session.end}:00+01:00`,
    allday: "false",
    location: calendarLocation(session),
    body: calendarDetails(session),
  });
  window.open(`https://outlook.office.com/calendar/0/deeplink/compose?${params.toString()}`, "_blank", "noopener");
}

let toastTimer;
function showToast(message) {
  clearTimeout(toastTimer);
  elements.toast.querySelector("p").textContent = message;
  elements.toast.classList.add("is-visible");
  toastTimer = setTimeout(() => elements.toast.classList.remove("is-visible"), 2800);
}

function bindEvents() {
  elements.search.addEventListener("input", () => {
    state.query = elements.search.value.trim().toLocaleLowerCase("en");
    renderSchedule();
  });

  elements.dayBar?.addEventListener("click", (event) => {
    const button = event.target.closest("[data-day-index]");
    if (!button) return;
    state.dayIndex = Number(button.dataset.dayIndex);
    render();
  });

  elements.themeFilters.addEventListener("click", (event) => {
    const button = event.target.closest("[data-theme-filter]");
    if (!button) return;
    state.theme = state.theme === button.dataset.themeFilter ? "" : button.dataset.themeFilter;
    render();
  });

  elements.periodFilters.addEventListener("click", (event) => {
    const button = event.target.closest("[data-period-filter]");
    if (!button) return;
    state.period = state.period === button.dataset.periodFilter ? "" : button.dataset.periodFilter;
    render();
  });

  elements.schedule.addEventListener("click", (event) => {
    const favorite = event.target.closest("[data-favorite]");
    if (favorite) {
      toggleFavorite(favorite.dataset.favorite);
      return;
    }
    const session = event.target.closest("[data-session-open]");
    if (session) openModal(session.dataset.sessionOpen);
  });

  elements.resetButtons.forEach((button) => button.addEventListener("click", resetFilters));

  elements.favoritesToggle.addEventListener("click", () => {
    state.favoritesOnly = !state.favoritesOnly;
    render();
  });

  elements.filterToggle.addEventListener("click", () => {
    const open = elements.filterDrawer.classList.toggle("is-open");
    elements.filterToggle.setAttribute("aria-expanded", String(open));
  });

  document.querySelectorAll("[data-modal-close]").forEach((button) => button.addEventListener("click", closeModal));
  elements.modalBackdrop.addEventListener("click", (event) => {
    if (event.target === elements.modalBackdrop) closeModal();
  });
  elements.modalFavorite.addEventListener("click", () => toggleFavorite(state.modalSessionId));
  document.querySelector("[data-add-calendar]")?.addEventListener("click", addToCalendar);
  document.querySelector("[data-add-outlook]")?.addEventListener("click", addToOutlookCalendar);

  document.addEventListener("keydown", (event) => {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === "k") {
      event.preventDefault();
      elements.search.focus();
    }
    trapModalFocus(event);
    if (event.key === "Escape" && !elements.modalBackdrop.hidden) closeModal();
  });
}

function updateStats() {
  const count = state.data.days.reduce(
    (total, day) => total + day.slots.reduce((sum, slot) => sum + (slot.sessions?.length || 0), 0),
    0
  );
  const sessionCount = document.querySelector("[data-session-count]");
  const roomCount = document.querySelector("[data-room-count]");
  const themeCount = document.querySelector("[data-theme-count]");

  if (sessionCount) sessionCount.textContent = count;
  if (roomCount) roomCount.textContent = state.data.rooms.length;
  if (themeCount) themeCount.textContent = state.data.themes.length;
}

/** The venue plan links here as programme.html?room=B1. Room codes are part of
 *  the search haystack, so pre-filling the search box filters to that hall. */
function applyRoomFromUrl() {
  let room = "";
  try {
    room = (new URLSearchParams(window.location.search).get("room") || "").trim();
  } catch {
    return;
  }
  if (!room) return;

  state.query = room.toLocaleLowerCase("en");
  if (elements.search) elements.search.value = room;
  renderSchedule();

  const schedule = document.getElementById("schedule");
  if (schedule) {
    requestAnimationFrame(() => schedule.scrollIntoView({ block: "start" }));
  }
}

function restoreHashScroll() {
  if (!window.location.hash) return;

  let targetId = "";
  try {
    targetId = decodeURIComponent(window.location.hash.slice(1));
  } catch {
    return;
  }
  const target = document.getElementById(targetId);
  if (!target) return;

  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      target.scrollIntoView({ block: "start" });
    });
  });
}

async function init() {
  try {
    if (window.CEEDUCON_PREFER_EMBEDDED_DATA && window.CEEDUCON_PROGRAM_DATA) {
      state.data = window.CEEDUCON_PROGRAM_DATA;
    } else {
      const response = await fetch(DATA_URL);
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      state.data = await response.json();
    }
  } catch (error) {
    if (window.CEEDUCON_PROGRAM_DATA) {
      console.info("Using embedded programme data fallback.", error);
      state.data = window.CEEDUCON_PROGRAM_DATA;
    } else {
      console.error("Programme could not be loaded:", error);
      elements.schedule.innerHTML = `<div class="empty-state"><span>!</span><h3>Programme could not be loaded</h3><p>Please reload the page and try again.</p></div>`;
      return;
    }
  }

  updateStats();
  render();
  bindEvents();
  applyRoomFromUrl();
  restoreHashScroll();
}

init();
