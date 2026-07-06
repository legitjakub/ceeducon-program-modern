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

function bindMediaLightbox() {
  const triggers = document.querySelectorAll("[data-lightbox]");
  if (!triggers.length) return;

  const lightbox = document.createElement("div");
  lightbox.className = "media-lightbox";
  lightbox.hidden = true;
  lightbox.innerHTML = `
    <div class="media-lightbox-inner" role="dialog" aria-modal="true" aria-label="Media preview">
      <button class="media-lightbox-close" type="button" aria-label="Close media preview">×</button>
      <img alt="" />
      <div class="media-lightbox-caption"></div>
    </div>
  `;
  document.body.append(lightbox);

  const image = lightbox.querySelector("img");
  const caption = lightbox.querySelector(".media-lightbox-caption");
  const close = lightbox.querySelector(".media-lightbox-close");

  const closeLightbox = () => {
    lightbox.hidden = true;
    document.body.classList.remove("modal-open");
    image.removeAttribute("src");
  };

  triggers.forEach((trigger) => {
    trigger.addEventListener("click", () => {
      const src = trigger.dataset.lightbox;
      if (!src) return;
      image.src = src;
      image.alt = trigger.querySelector("img")?.alt || trigger.dataset.lightboxCaption || "CEEDUCON media";
      caption.textContent = trigger.dataset.lightboxCaption || "";
      lightbox.hidden = false;
      document.body.classList.add("modal-open");
      close.focus();
    });
  });

  close.addEventListener("click", closeLightbox);
  lightbox.addEventListener("click", (event) => {
    if (event.target === lightbox) closeLightbox();
  });
  window.addEventListener("keydown", (event) => {
    if (!lightbox.hidden && event.key === "Escape") closeLightbox();
  });
}

function bindMobileCarousels() {
  const tracks = document.querySelectorAll(
    ".media-mosaic, .theme-grid, .day-cards, .tile-grid, .info-grid, .timeline, .step-list"
  );
  if (!tracks.length) return;

  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

  tracks.forEach((track, index) => {
    const slides = Array.from(track.children).filter((child) => child.nodeType === 1);
    if (slides.length < 2 || track.dataset.carouselReady === "true") return;

    track.dataset.carouselReady = "true";
    track.classList.add("mobile-carousel");
    track.tabIndex = 0;
    if (!track.id) track.id = `mobile-carousel-${index + 1}`;

    const nav = document.createElement("div");
    nav.className = "carousel-nav";
    nav.setAttribute("aria-label", `${track.getAttribute("aria-label") || "Section"} controls`);
    nav.innerHTML = `
      <div class="carousel-counter" aria-live="polite">1 / ${slides.length}</div>
      <div class="carousel-buttons">
        <button class="carousel-button carousel-button--prev" type="button" data-carousel-prev aria-label="Previous slide" aria-controls="${track.id}">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5l-7 7 7 7" /></svg>
        </button>
        <button class="carousel-button carousel-button--next" type="button" data-carousel-next aria-label="Next slide" aria-controls="${track.id}">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7" /></svg>
        </button>
      </div>
    `;
    track.after(nav);

    const counter = nav.querySelector(".carousel-counter");
    const prev = nav.querySelector("[data-carousel-prev]");
    const next = nav.querySelector("[data-carousel-next]");

    const getCurrentIndex = () => {
      const trackLeft = track.getBoundingClientRect().left;
      return slides.reduce((best, slide, slideIndex) => {
        const distance = Math.abs(slide.getBoundingClientRect().left - trackLeft);
        return distance < best.distance ? { index: slideIndex, distance } : best;
      }, { index: 0, distance: Number.POSITIVE_INFINITY }).index;
    };

    const updateCounter = () => {
      const current = getCurrentIndex();
      counter.textContent = `${current + 1} / ${slides.length}`;
      prev.disabled = current === 0;
      next.disabled = current === slides.length - 1;
    };

    const scrollToSlide = (direction) => {
      const current = getCurrentIndex();
      const target = slides[Math.max(0, Math.min(slides.length - 1, current + direction))];
      if (!target) return;
      target.scrollIntoView({
        behavior: prefersReducedMotion.matches ? "auto" : "smooth",
        block: "nearest",
        inline: "start",
      });
    };

    prev.addEventListener("click", () => scrollToSlide(-1));
    next.addEventListener("click", () => scrollToSlide(1));
    track.addEventListener("scroll", () => window.requestAnimationFrame(updateCounter), { passive: true });
    window.addEventListener("resize", updateCounter);
    updateCounter();
  });
}

bindSiteNavigation();
bindHeaderScroll();
bindReveals();
bindSiteCookie();
bindMediaLightbox();
bindMobileCarousels();
updateSiteCountdown();
