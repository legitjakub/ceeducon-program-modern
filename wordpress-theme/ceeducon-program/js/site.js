const SITE_COOKIE_KEY = "ceeducon-cookie-note-accepted";
const SITE_CONFERENCE_START = "2026-12-01T09:00:00+01:00";

document.documentElement.classList.add("js");

const siteElements = {
  header: document.querySelector(".site-header"),
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
    siteElements.header?.classList.toggle("is-open", !expanded);
  });

  siteElements.mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      siteElements.menuToggle.setAttribute("aria-expanded", "false");
      siteElements.mobileMenu.hidden = true;
      siteElements.header?.classList.remove("is-open");
    });
  });
}

function bindHeaderScroll() {
  if (!siteElements.header) return;
  const update = () => {
    siteElements.header.classList.toggle("is-scrolled", window.scrollY > 24);
  };
  window.addEventListener("scroll", update, { passive: true });
  update();
}

function bindReveals() {
  const targets = document.querySelectorAll("[data-reveal]");
  if (!targets.length) return;

  if (!("IntersectionObserver" in window)) {
    targets.forEach((el) => el.classList.add("is-visible"));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-visible");
          observer.unobserve(entry.target);
        }
      });
    },
    { rootMargin: "0px 0px -8% 0px", threshold: 0.08 }
  );

  targets.forEach((el) => observer.observe(el));
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
bindHeaderScroll();
bindReveals();
bindSiteCookie();
updateSiteCountdown();
