import { getSessionTitle, getSlotTitle, getThemeLabel } from "./i18n.js";

const DATA_URL = "data/program.json";
const LANGUAGE = "cs";
const FAVORITES_KEY = "ceeducon-2025-favorites";
const DEMO_TIME = "14:26";

const state = {
  data: null,
  theme: "",
  room: "",
  query: "",
  favoritesOnly: false,
  favorites: new Set(JSON.parse(localStorage.getItem(FAVORITES_KEY) || "[]")),
  liveDemo: false,
  sessionMap: new Map(),
  modalSessionId: "",
  modalSession: null,
};

const elements = {
  schedule: document.querySelector("[data-schedule]"),
  themeFilters: document.querySelector("[data-theme-filters]"),
  roomFilters: document.querySelector("[data-room-filters]"),
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

function sessionId(slot, session) {
  return `${slot.id}-${session.rooms.join("-")}`.replaceAll("+", "plus");
}

function themeById(id) {
  return state.data.themes.find((theme) => theme.id === id);
}

function titleForSession(session) {
  return getSessionTitle(session, LANGUAGE);
}

function titleForSlot(slot) {
  return getSlotTitle(slot, LANGUAGE);
}

function activeFilters() {
  return Boolean(state.theme || state.room || state.query || state.favoritesOnly);
}

function matchesSession(session, id) {
  if (state.theme && session.theme !== state.theme) return false;
  if (state.room && !session.rooms.includes(state.room)) return false;
  if (state.favoritesOnly && !state.favorites.has(id)) return false;
  if (state.query) {
    const themeLabel = getThemeLabel(session.theme, LANGUAGE);
    const haystack = `${titleForSession(session)} ${session.title} ${session.rooms.join(" ")} ${themeLabel}`.toLocaleLowerCase("cs");
    if (!haystack.includes(state.query)) return false;
  }
  return true;
}

function matchesStandaloneSlot(slot) {
  if (state.theme || state.favoritesOnly) return false;
  if (state.room && slot.rooms && !slot.rooms.includes(state.room)) return false;
  if (state.query) {
    return `${titleForSlot(slot)} ${slot.title || ""}`.toLocaleLowerCase("cs").includes(state.query);
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
}

function buildSessionCard(slot, session, wide = false) {
  const id = sessionId(slot, session);
  const theme = themeById(session.theme) || { color: "#4c84bc", id: "" };
  const favorite = state.favorites.has(id);
  const title = titleForSession(session);
  const themeLabel = getThemeLabel(session.theme, LANGUAGE);

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
  });

  return `
    <article class="session-card${wide ? " session-card--wide" : ""}" style="--track:${theme.color}">
      <div class="session-card-head">
        <span class="room-tag">${escapeHtml(session.rooms.join(" + "))}</span>
        <button class="favorite-star${favorite ? " is-active" : ""}" type="button" data-favorite="${escapeHtml(id)}" aria-label="${favorite ? "Odebrat z mého programu" : "Přidat do mého programu"}" aria-pressed="${favorite}">${favorite ? "★" : "☆"}</button>
      </div>
      <button class="session-card-open" type="button" data-session-open="${escapeHtml(id)}">
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
    registration: "Registrace a vstup účastníků",
    coffee: "Přestávka a prostor pro networking",
    lunch: "Obědová přestávka a networking",
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
  if (!state.liveDemo) return false;
  const current = toMinutes(DEMO_TIME);
  return current >= toMinutes(slot.start) && current < toMinutes(slot.end);
}

function renderSchedule() {
  state.sessionMap.clear();
  let visibleSessions = 0;
  const slots = [];

  for (const slot of state.data.slots) {
    let content = "";

    if (slot.sessions) {
      const sessions = slot.sessions.filter((session) => matchesSession(session, sessionId(slot, session)));
      visibleSessions += sessions.length;
      if (sessions.length) {
        content = `<div class="slot-heading"><span>${sessions.length === 1 ? "1 příspěvek" : `${sessions.length} příspěvků`}</span></div><div class="sessions-grid">${sessions.map((session) => buildSessionCard(slot, session)).join("")}</div>`;
      }
    } else if (matchesStandaloneSlot(slot)) {
      if (slot.type === "plenary") {
        const session = { title: titleForSlot(slot), rooms: slot.rooms, theme: "smart" };
        visibleSessions += 1;
        content = `<div class="sessions-grid">${buildSessionCard(slot, session, true)}</div>`;
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

  elements.schedule.innerHTML = slots.join("");
  elements.empty.hidden = slots.length > 0;
  elements.schedule.hidden = slots.length === 0;
  elements.resultCount.textContent = activeFilters()
    ? `Zobrazeno ${visibleSessions} odpovídajících příspěvků`
    : `Kompletní program · ${visibleSessions} příspěvků`;
}

function render() {
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
  state.room = "";
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
    showToast("Příspěvek byl odebrán z vašeho programu.");
  } else {
    state.favorites.add(id);
    showToast("Příspěvek byl přidán do vašeho programu.");
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
  updateModalFavorite();
  elements.modalBackdrop.hidden = false;
  document.body.classList.add("modal-open");
  requestAnimationFrame(() => document.querySelector("[data-modal-close]").focus());
}

function updateModalFavorite() {
  const active = state.favorites.has(state.modalSessionId);
  elements.modalFavorite.classList.toggle("is-active", active);
  elements.modalFavorite.innerHTML = `<span>${active ? "★" : "☆"}</span> ${active ? "Odebrat z mého programu" : "Přidat do mého programu"}`;
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
  const description = `CEEDUCON 2025 – ${session.theme}`;
  const content = [
    "BEGIN:VCALENDAR",
    "VERSION:2.0",
    "PRODID:-//DZS//CEEDUCON 2025//CS",
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
  showToast("Událost byla připravena ke stažení.");
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
    state.query = elements.search.value.trim().toLocaleLowerCase("cs");
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

  elements.liveToggle.addEventListener("click", () => {
    state.liveDemo = !state.liveDemo;
    elements.liveToggle.setAttribute("aria-pressed", String(state.liveDemo));
    elements.liveLabel.textContent = state.liveDemo ? "Live režim zapnutý" : "Live režim";
    elements.liveBanner.hidden = !state.liveDemo;
    renderSchedule();
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

  document.addEventListener("keydown", (event) => {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === "k") {
      event.preventDefault();
      elements.search.focus();
    }
    if (event.key === "Escape" && !elements.modalBackdrop.hidden) closeModal();
  });
}

function updateStats() {
  const count = state.data.slots.reduce((total, slot) => total + (slot.sessions?.length || (slot.type === "plenary" ? 1 : 0)), 0);
  document.querySelector("[data-session-count]").textContent = count;
  document.querySelector("[data-room-count]").textContent = state.data.rooms.length;
  document.querySelector("[data-theme-count]").textContent = state.data.themes.length;
}

async function init() {
  try {
    const response = await fetch(DATA_URL);
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    state.data = await response.json();
    updateStats();
    render();
    bindEvents();
  } catch (error) {
    console.error("Program se nepodařilo načíst:", error);
    elements.schedule.innerHTML = `<div class="empty-state"><span>!</span><h3>Program se nepodařilo načíst</h3><p>Spusťte stránku přes lokální webový server.</p></div>`;
  }
}

init();
