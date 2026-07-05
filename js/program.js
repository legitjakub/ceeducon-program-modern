const DATA_URL = window.CEEDUCON_DATA_URL || "data/program.json";
const LANGUAGE = "en";
const FAVORITES_KEY = "ceeducon-2025-favorites";
const COOKIE_KEY = "ceeducon-cookie-note-accepted";
const DEMO_TIME = "14:26";
const TIMEZONE = "Europe/Prague";
const CONFERENCE_START = "2026-12-01T09:00:00+01:00";
const PERIODS = [
  { id: "", label: "All day" },
  { id: "morning", label: "Morning" },
  { id: "afternoon", label: "Afternoon" },
];

const state = {
  data: null,
  theme: "",
  room: "",
  period: "",
  query: "",
  favoritesOnly: false,
  favorites: new Set(JSON.parse(localStorage.getItem(FAVORITES_KEY) || "[]")),
  liveMode: false,
  sessionMap: new Map(),
  modalSessionId: "",
  modalSession: null,
};

const elements = {
  schedule: document.querySelector("[data-schedule]"),
  themeFilters: document.querySelector("[data-theme-filters]"),
  roomFilters: document.querySelector("[data-room-filters]"),
  periodFilters: document.querySelector("[data-period-filters]"),
  search: document.getElementById("program-search"),
  resultCount: document.querySelector("[data-result-count]"),
  resetButtons: document.querySelectorAll("[data-reset-filters], [data-empty-reset]"),
  empty: document.querySelector("[data-empty]"),
  filterToggle: document.querySelector("[data-filter-toggle]"),
  filterDrawer: document.querySelector("[data-filter-drawer]"),
  favoritesToggle: document.querySelector("[data-favorites-toggle]"),
  favoriteCount: document.querySelector("[data-favorite-count]"),
  liveToggle: document.querySelector("[data-live-toggle]"),
  liveLabel: document.querySelector("[data-live-label]"),
  liveBanner: document.querySelector("[data-live-banner]"),
  modalBackdrop: document.querySelector("[data-modal-backdrop]"),
  modalTitle: document.querySelector("[data-modal-title]"),
  modalTheme: document.querySelector("[data-modal-theme]"),
  modalTrack: document.querySelector("[data-modal-track]"),
  modalTime: document.querySelector("[data-modal-time]"),
  modalRoom: document.querySelector("[data-modal-room]"),
  modalFavorite: document.querySelector("[data-modal-favorite]"),
  modalNote: document.querySelector("[data-modal-note]"),
  menuToggle: document.querySelector("[data-menu-toggle]"),
  mobileMenu: document.querySelector("[data-mobile-menu]"),
  navLinks: document.querySelectorAll(".header-nav a, .mobile-menu a"),
  toast: document.querySelector("[data-toast]"),
  cookieBanner: document.querySelector("[data-cookie-banner]"),
  cookieAccept: document.querySelector("[data-cookie-accept]"),
  countdownDays: document.querySelector("[data-countdown-days]"),
};

function getSessionTitle(session) {
  return session.title;
}

function getSlotTitle(slot) {
  return slot.title || slot.id;
}

function getThemeLabel(themeId) {
  return state.data?.themes?.find((theme) => theme.id === themeId)?.label || themeId;
}

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

function pragueDateTime() {
  const parts = new Intl.DateTimeFormat("en-CA", {
    timeZone: TIMEZONE,
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    hourCycle: "h23",
  }).formatToParts(new Date());
  const byType = Object.fromEntries(parts.map((part) => [part.type, part.value]));

  return {
    date: `${byType.year}-${byType.month}-${byType.day}`,
    time: `${byType.hour}:${byType.minute}`,
  };
}

function liveContext() {
  const now = pragueDateTime();
  const isEventDay = now.date === state.data?.event?.date;

  return {
    isEventDay,
    time: isEventDay ? now.time : DEMO_TIME,
  };
}

function sessionId(slot, session) {
  return `${slot.id}-${session.rooms.join("-")}`.replaceAll("+", "plus");
}

function themeById(id) {
  return state.data.themes.find((theme) => theme.id === id);
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

function titleForSession(session) {
  return getSessionTitle(session, LANGUAGE);
}

function titleForSlot(slot) {
  return getSlotTitle(slot, LANGUAGE);
}

function activeFilters() {
  return Boolean(state.theme || state.room || state.period || state.query || state.favoritesOnly);
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
  if (state.room && !session.rooms.includes(state.room)) return false;
  if (state.favoritesOnly && !state.favorites.has(id)) return false;
  if (state.query) {
    const themeLabel = getThemeLabel(session.theme, LANGUAGE);
    const haystack = `${titleForSession(session)} ${session.title} ${session.rooms.join(" ")} ${themeLabel}`.toLocaleLowerCase("en");
    if (!haystack.includes(state.query)) return false;
  }
  return true;
}

function matchesStandaloneSlot(slot) {
  if (state.theme || state.favoritesOnly) return false;
  if (state.room && slot.rooms && !slot.rooms.includes(state.room)) return false;
  if (state.query) {
    return `${titleForSlot(slot)} ${slot.title || ""}`.toLocaleLowerCase("en").includes(state.query);
  }
  return true;
}

function renderFilters() {
  elements.themeFilters.innerHTML = state.data.themes.map((theme) => `
    <button
      class="filter-chip${state.theme === theme.id ? " is-active" : ""}"
      type="button"
      data-theme-filter="${escapeHtml(theme.id)}"
      style="--chip-color:${theme.color}"
      aria-pressed="${state.theme === theme.id}"
    ><i></i><span>${escapeHtml(getThemeLabel(theme.id, LANGUAGE))}</span></button>
  `).join("");

  elements.roomFilters.innerHTML = state.data.rooms.map((room) => `
    <button
      class="filter-chip filter-chip--room${state.room === room ? " is-active" : ""}"
      type="button"
      data-room-filter="${escapeHtml(room)}"
      aria-pressed="${state.room === room}"
    >${escapeHtml(room)}</button>
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

function buildSessionCard(slot, session, wide = false) {
  const id = sessionId(slot, session);
  const theme = themeById(session.theme) || { color: "#4c84bc", id: "" };
  const favorite = state.favorites.has(id);
  const title = titleForSession(session);
  const themeLabel = getThemeLabel(session.theme, LANGUAGE);
  const placement = roomPlacement(session);

  state.sessionMap.set(id, {
    id,
    title,
    originalTitle: session.title,
    rooms: session.rooms,
    start: slot.start,
    end: slot.end,
    theme: themeLabel,
    color: theme.color,
    date: state.data.event.date,
    format: session.format || (wide ? "Plenary session" : "Thematic session"),
    speakers: session.speakers || [],
    description: session.description || `Archived CEEDUCON programme item in the ${themeLabel} thematic track. The final 2026 abstract and speaker details can be added when the official programme is confirmed.`,
  });

  return `
    <article class="session-card${wide ? " session-card--wide" : ""}" style="--track:${theme.color};--room-start:${placement.start};--room-span:${placement.span}" data-room-list="${escapeHtml(session.rooms.join(","))}">
      <div class="session-card-head">
        <span class="room-tag">${escapeHtml(session.rooms.join(" + "))}</span>
        <button class="favorite-star${favorite ? " is-active" : ""}" type="button" data-favorite="${escapeHtml(id)}" aria-label="${favorite ? "Remove from my programme" : "Add to my programme"}" aria-pressed="${favorite}">${favorite ? "★" : "☆"}</button>
      </div>
      <button class="session-card-open" type="button" data-session-open="${escapeHtml(id)}" title="${escapeHtml(title)}">
        <h3>${escapeHtml(title)}</h3>
        <p class="session-theme">${escapeHtml(themeLabel)}</p>
        <span class="session-arrow" aria-hidden="true">↗</span>
      </button>
    </article>`;
}

function buildProgramBand(slot) {
  const title = titleForSlot(slot);
  const variant = slot.id === "registration" ? "registration" : slot.id === "lunch" ? "lunch" : "coffee";
  const notes = {
    registration: "Participant registration and arrival",
    coffee: "Coffee break and networking space",
    lunch: "Lunch break and networking",
  };
  const icons = {
    registration: `<svg viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="1"/><path d="M8 8h8M8 12h5"/><circle cx="9" cy="16" r="2"/><path d="M13 18c.8-1.4 2-2 3.5-2"/></svg>`,
    coffee: `<svg viewBox="0 0 24 24"><path d="M5 9h11v3a5 5 0 0 1-5 5H10a5 5 0 0 1-5-5V9Z"/><path d="M16 10h2a2 2 0 0 1 0 4h-2M7 4v2M11 3v3M15 4v2M4 21h15"/></svg>`,
    lunch: `<svg viewBox="0 0 24 24"><path d="M5 3v7M2.5 3v5A2 2 0 0 0 5 10a2 2 0 0 0 2.5-2V3M5 10v11M16 3v18M16 3c3 2 3.5 6 0 9"/></svg>`,
  };
  return `
    <div class="program-band program-band--${variant}">
      <span class="program-band-icon" aria-hidden="true">${icons[variant]}</span>
      <p><strong>${escapeHtml(title)}</strong><span>${escapeHtml(notes[variant])}</span></p>
    </div>`;
}

function isLiveSlot(slot) {
  if (!state.liveMode) return false;
  const current = toMinutes(liveContext().time);
  return current >= toMinutes(slot.start) && current < toMinutes(slot.end);
}

function renderSchedule() {
  state.sessionMap.clear();
  let visibleSessions = 0;
  const slots = [];
  const roomHeader = `
    <div class="room-grid-header" style="--room-count:${state.data.rooms.length}" aria-hidden="true">
      <span class="room-grid-spacer">Time</span>
      <div class="room-grid-labels">
        ${state.data.rooms.map((room) => `<span>${escapeHtml(room)}</span>`).join("")}
      </div>
    </div>`;

  for (const slot of state.data.slots) {
    if (!matchesSlotPeriod(slot)) continue;

    let content = "";

    if (slot.sessions) {
      const sessions = slot.sessions.filter((session) => matchesSession(session, sessionId(slot, session)));
      visibleSessions += sessions.length;
      if (sessions.length) {
        content = `<div class="slot-heading"><span>${sessions.length === 1 ? "1 session" : `${sessions.length} sessions`}</span></div><div class="sessions-grid" style="--room-count:${state.data.rooms.length}">${sessions.map((session) => buildSessionCard(slot, session)).join("")}</div>`;
      }
    } else if (matchesStandaloneSlot(slot)) {
      if (slot.type === "plenary") {
        const session = { title: titleForSlot(slot), rooms: slot.rooms, theme: "smart" };
        visibleSessions += 1;
        content = `<div class="sessions-grid" style="--room-count:${state.data.rooms.length}">${buildSessionCard(slot, session, true)}</div>`;
      } else {
        content = buildProgramBand(slot);
      }
    }

    if (!content) continue;
    const live = isLiveSlot(slot);
    slots.push(`
      <section class="time-slot${live ? " is-live" : ""}" id="slot-${escapeHtml(slot.id)}" data-slot-id="${escapeHtml(slot.id)}">
        <div class="slot-time"><strong>${escapeHtml(slot.start)}</strong><span>${escapeHtml(slot.end)}</span></div>
        <div class="slot-content">${content}</div>
      </section>`);
  }

  elements.schedule.innerHTML = slots.length ? roomHeader + slots.join("") : "";
  elements.empty.hidden = slots.length > 0;
  elements.schedule.hidden = slots.length === 0;
  elements.resultCount.textContent = activeFilters()
    ? `${visibleSessions} matching sessions shown`
    : `Full programme · ${visibleSessions} sessions`;
}

function render() {
  renderFilters();
  renderSchedule();
  updateFavoritesUI();
  updateLiveUI();
}

function updateFavoritesUI() {
  elements.favoriteCount.textContent = state.favorites.size;
  elements.favoritesToggle.setAttribute("aria-pressed", String(state.favoritesOnly));
  elements.favoritesToggle.classList.toggle("is-active", state.favoritesOnly);
}

function updateLiveUI() {
  elements.liveToggle.setAttribute("aria-pressed", String(state.liveMode));
  elements.liveBanner.hidden = !state.liveMode;

  const context = liveContext();
  elements.liveLabel.textContent = state.liveMode
    ? (context.isEventDay ? "Live mode on" : "Demo preview on")
    : "Live preview";

  if (!state.liveMode) return;

  const heading = context.isEventDay ? `Happening now · ${context.time}` : `Demo live preview · ${context.time}`;
  const copy = context.isEventDay
    ? "The current programme block is highlighted."
    : "The archive date is not today, so the highlighted block uses a sample time and does not claim a live 2026 schedule.";

  elements.liveBanner.querySelector("strong").textContent = heading;
  elements.liveBanner.querySelector("span:last-child").textContent = copy;
}

function resetFilters() {
  state.theme = "";
  state.room = "";
  state.period = "";
  state.query = "";
  state.favoritesOnly = false;
  elements.search.value = "";
  render();
}

function applyThemeFromStory(themeId) {
  state.theme = themeId;
  state.room = "";
  state.period = "";
  state.query = "";
  state.favoritesOnly = false;
  elements.search.value = "";
  render();
  document.querySelector("#schedule")?.scrollIntoView({ behavior: "smooth", block: "start" });
  showToast("Programme filtered by the selected thematic track.");
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
  state.modalSessionId = id;
  state.modalSession = session;
  elements.modalTitle.textContent = session.title;
  elements.modalTheme.textContent = session.theme;
  elements.modalTrack.style.background = session.color;
  elements.modalTime.textContent = `${session.start} – ${session.end}`;
  elements.modalRoom.textContent = session.rooms.join(" + ");
  const speakers = session.speakers.length
    ? `Speakers: ${session.speakers.join(", ")}.`
    : "Speaker details will be published with the official programme.";
  elements.modalNote.textContent = `${session.description} Format: ${session.format}. ${speakers}`;
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
}

function compactDate(date) {
  return date.replaceAll("-", "");
}

function compactTime(time) {
  return time.replace(":", "") + "00";
}

function downloadIcs() {
  const session = state.modalSession;
  if (!session) return;
  const description = `${session.description} CEEDUCON 2025 archive programme – ${session.theme}.`;
  const content = [
    "BEGIN:VCALENDAR",
    "VERSION:2.0",
    "PRODID:-//DZS//CEEDUCON 2025//EN",
    "BEGIN:VEVENT",
    `UID:${session.id}@ceeducon.cz`,
    `DTSTART;TZID=Europe/Prague:${compactDate(session.date)}T${compactTime(session.start)}`,
    `DTEND;TZID=Europe/Prague:${compactDate(session.date)}T${compactTime(session.end)}`,
    `SUMMARY:${session.title.replaceAll(",", "\\,")}`,
    `LOCATION:${state.data.event.location} – ${session.rooms.join(" + ")}`,
    `DESCRIPTION:${description}`,
    "END:VEVENT",
    "END:VCALENDAR",
  ].join("\r\n");
  const url = URL.createObjectURL(new Blob([content], { type: "text/calendar;charset=utf-8" }));
  const link = document.createElement("a");
  link.href = url;
  link.download = `${session.id}.ics`;
  link.click();
  URL.revokeObjectURL(url);
  showToast("Calendar file is ready to download.");
}

let toastTimer;
function showToast(message) {
  clearTimeout(toastTimer);
  elements.toast.querySelector("p").textContent = message;
  elements.toast.classList.add("is-visible");
  toastTimer = setTimeout(() => elements.toast.classList.remove("is-visible"), 2800);
}

function closeMobileMenu() {
  if (!elements.mobileMenu || !elements.menuToggle) return;
  elements.mobileMenu.hidden = true;
  elements.menuToggle.setAttribute("aria-expanded", "false");
}

function setActiveNav(id) {
  elements.navLinks.forEach((link) => {
    const active = link.getAttribute("href") === `#${id}`;
    link.classList.toggle("is-active", active);
  });
}

function initNavObserver() {
  const sections = ["about", "themes", "programme-2026", "schedule", "practical", "speakers", "venue", "contact"]
    .map((id) => document.getElementById(id))
    .filter(Boolean);

  if (!("IntersectionObserver" in window) || !sections.length) return;

  const observer = new IntersectionObserver((entries) => {
    const visible = entries
      .filter((entry) => entry.isIntersecting)
      .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
    if (visible?.target?.id) setActiveNav(visible.target.id);
  }, {
    rootMargin: "-28% 0px -58% 0px",
    threshold: [0.12, 0.35, 0.6],
  });

  sections.forEach((section) => observer.observe(section));
}

function bindEvents() {
  elements.search.addEventListener("input", () => {
    state.query = elements.search.value.trim().toLocaleLowerCase("en");
    renderSchedule();
  });

  elements.themeFilters.addEventListener("click", (event) => {
    const button = event.target.closest("[data-theme-filter]");
    if (!button) return;
    state.theme = state.theme === button.dataset.themeFilter ? "" : button.dataset.themeFilter;
    render();
  });

  elements.roomFilters.addEventListener("click", (event) => {
    const button = event.target.closest("[data-room-filter]");
    if (!button) return;
    state.room = state.room === button.dataset.roomFilter ? "" : button.dataset.roomFilter;
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

  document.querySelectorAll("[data-theme-jump]").forEach((button) => {
    button.addEventListener("click", () => applyThemeFromStory(button.dataset.themeJump));
  });

  elements.menuToggle?.addEventListener("click", () => {
    const open = elements.mobileMenu.hidden;
    elements.mobileMenu.hidden = !open;
    elements.menuToggle.setAttribute("aria-expanded", String(open));
  });

  elements.navLinks.forEach((link) => {
    link.addEventListener("click", () => closeMobileMenu());
  });

  elements.favoritesToggle.addEventListener("click", () => {
    state.favoritesOnly = !state.favoritesOnly;
    render();
  });

  elements.liveToggle.addEventListener("click", () => {
    state.liveMode = !state.liveMode;
    if (state.liveMode) state.period = "";
    render();
  });

  document.querySelector("[data-jump-live]").addEventListener("click", () => {
    document.querySelector(".time-slot.is-live")?.scrollIntoView({ behavior: "smooth", block: "center" });
  });

  elements.filterToggle.addEventListener("click", () => {
    const open = elements.filterDrawer.classList.toggle("is-open");
    elements.filterToggle.setAttribute("aria-expanded", String(open));
  });

  document.querySelectorAll("[data-print]").forEach((button) => button.addEventListener("click", () => window.print()));
  document.querySelectorAll("[data-modal-close]").forEach((button) => button.addEventListener("click", closeModal));
  elements.modalBackdrop.addEventListener("click", (event) => {
    if (event.target === elements.modalBackdrop) closeModal();
  });
  elements.modalFavorite.addEventListener("click", () => toggleFavorite(state.modalSessionId));
  document.querySelector("[data-download-ics]").addEventListener("click", downloadIcs);

  if (localStorage.getItem(COOKIE_KEY) === "1") {
    elements.cookieBanner?.classList.add("is-hidden");
  }

  elements.cookieAccept?.addEventListener("click", () => {
    localStorage.setItem(COOKIE_KEY, "1");
    elements.cookieBanner?.classList.add("is-hidden");
  });

  document.addEventListener("keydown", (event) => {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === "k") {
      event.preventDefault();
      elements.search.focus();
    }
    if (event.key === "Escape" && !elements.modalBackdrop.hidden) closeModal();
    if (event.key === "Escape") closeMobileMenu();
  });

  initNavObserver();
}

function updateCountdown() {
  if (!elements.countdownDays) return;
  const now = new Date();
  const start = new Date(CONFERENCE_START);
  const days = Math.max(0, Math.ceil((start - now) / 86400000));
  elements.countdownDays.textContent = days;
}

function updateStats() {
  const count = state.data.slots.reduce((total, slot) => total + (slot.sessions?.length || (slot.type === "plenary" ? 1 : 0)), 0);
  const sessionCount = document.querySelector("[data-session-count]");
  const roomCount = document.querySelector("[data-room-count]");
  const themeCount = document.querySelector("[data-theme-count]");

  if (sessionCount) sessionCount.textContent = count;
  if (roomCount) roomCount.textContent = state.data.rooms.length;
  if (themeCount) themeCount.textContent = state.data.themes.length;
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
      elements.schedule.innerHTML = `<div class="empty-state"><span>!</span><h3>Programme could not be loaded</h3><p>Please reload the preview or open the GitHub Pages link.</p></div>`;
      return;
    }
  }

  updateCountdown();
  updateStats();
  render();
  bindEvents();
}

init();
