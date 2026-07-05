const SITE_COOKIE_KEY = "ceeducon-cookie-note-accepted";
const SITE_CONFERENCE_START = "2026-12-01T09:00:00+01:00";

const siteElements = {
  menuToggle: document.querySelector("[data-menu-toggle]"),
  mobileMenu: document.querySelector("[data-mobile-menu]"),
  countdownDays: document.querySelector("[data-countdown-days]"),
  cookieBanner: document.querySelector("[data-cookie-banner]"),
  cookieAccept: document.querySelector("[data-cookie-accept]"),
};

function updateSiteCountdown() {
  if (!siteElements.countdownDays) return;
  const start = new Date(SITE_CONFERENCE_START).getTime();
  const now = Date.now();
  const days = Math.max(0, Math.ceil((start - now) / 86400000));
  siteElements.countdownDays.textContent = String(days);
}

function bindSiteNavigation() {
  if (!siteElements.menuToggle || !siteElements.mobileMenu) return;

  siteElements.menuToggle.addEventListener("click", () => {
    const expanded = siteElements.menuToggle.getAttribute("aria-expanded") === "true";
    siteElements.menuToggle.setAttribute("aria-expanded", String(!expanded));
    siteElements.mobileMenu.hidden = expanded;
  });

  siteElements.mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      siteElements.menuToggle.setAttribute("aria-expanded", "false");
      siteElements.mobileMenu.hidden = true;
    });
  });
}

function bindSiteCookie() {
  if (!siteElements.cookieBanner || !siteElements.cookieAccept) return;
  if (localStorage.getItem(SITE_COOKIE_KEY) === "1") {
    siteElements.cookieBanner.classList.add("is-hidden");
  }

  siteElements.cookieAccept.addEventListener("click", () => {
    localStorage.setItem(SITE_COOKIE_KEY, "1");
    siteElements.cookieBanner.classList.add("is-hidden");
  });
}

bindSiteNavigation();
bindSiteCookie();
updateSiteCountdown();
