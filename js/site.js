document.documentElement.classList.add("js");

const siteElements = {
  header: document.querySelector(".site-header"),
  menuToggle: document.querySelector("[data-menu-toggle]"),
  mobileMenu: document.querySelector("[data-mobile-menu]"),
};

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

function bindStatCounters() {
  const rows = document.querySelectorAll(".stat-row");
  if (!rows.length) return;

  const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

  const animateRow = (row) => {
    if (row.dataset.counted === "true") return;
    row.dataset.counted = "true";

    row.querySelectorAll("strong").forEach((number, index) => {
      const original = number.textContent.trim();
      const match = original.match(/^(\D*)(\d[\d\s,.]*)(.*)$/);
      if (!match || prefersReducedMotion.matches) return;

      const target = Number(match[2].replace(/[\s,]/g, ""));
      if (!Number.isFinite(target)) return;

      const [, prefix, , suffix] = match;
      const duration = 1100;
      const delay = index * 90;
      let startedAt;

      number.setAttribute("aria-label", original);
      number.textContent = `${prefix}0${suffix}`;

      const frame = (now) => {
        if (startedAt === undefined) startedAt = now + delay;
        if (now < startedAt) {
          window.requestAnimationFrame(frame);
          return;
        }

        const progress = Math.min((now - startedAt) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        number.textContent = `${prefix}${Math.round(target * eased)}${suffix}`;

        if (progress < 1) window.requestAnimationFrame(frame);
        else number.textContent = original;
      };

      window.requestAnimationFrame(frame);
    });
  };

  if (!("IntersectionObserver" in window) || prefersReducedMotion.matches) {
    rows.forEach(animateRow);
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animateRow(entry.target);
        observer.unobserve(entry.target);
      });
    },
    { rootMargin: "0px 0px -10% 0px", threshold: 0.2 }
  );

  rows.forEach((row) => observer.observe(row));
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
    ".theme-grid, .day-cards, .tile-grid, .info-grid, .timeline, .step-list"
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

/** Keep the theme cards compact: opening a card closes the other card in the
 * same group, while preserving the native keyboard and no-JavaScript behavior. */
function bindThemeDetails() {
  const hoverCapable = window.matchMedia("(hover: hover) and (pointer: fine)");

  document.querySelectorAll(".theme-grid").forEach((grid) => {
    const cards = [...grid.querySelectorAll("details.theme-card")];
    cards.forEach((card) => {
      let openedByHover = false;

      card.addEventListener("mouseenter", () => {
        if (!hoverCapable.matches || card.open) return;
        openedByHover = true;
        card.open = true;
      });

      card.addEventListener("mouseleave", () => {
        if (!hoverCapable.matches || !openedByHover) return;
        openedByHover = false;
        card.open = false;
      });

      card.addEventListener("toggle", () => {
        if (!card.open) return;
        cards.forEach((other) => {
          if (other !== card) other.open = false;
        });
      });
    });
  });
}

/** Level switching for the venue plan, plus hover/focus sync between the
 *  schematic and the hall list beside it. */
function bindFloorplan() {
  const root = document.querySelector("[data-floorplan]");
  if (!root) return;

  const tabs = [...root.querySelectorAll("[data-level]")];
  const plans = [...root.querySelectorAll("[data-level-plan]")];
  const lists = [...root.querySelectorAll("[data-level-list]")];

  // `hidden` is an HTMLElement property — assigning it on an <svg> does nothing,
  // so toggle the attribute instead, which works for both.
  function toggleHidden(el, hide) {
    if (hide) el.setAttribute("hidden", "");
    else el.removeAttribute("hidden");
  }

  function showLevel(level) {
    tabs.forEach((tab) => {
      const active = tab.dataset.level === level;
      tab.classList.toggle("is-active", active);
      tab.setAttribute("aria-pressed", String(active));
    });
    plans.forEach((plan) => toggleHidden(plan, plan.dataset.levelPlan !== level));
    lists.forEach((list) => toggleHidden(list, list.dataset.levelList !== level));
  }

  tabs.forEach((tab) => tab.addEventListener("click", () => showLevel(tab.dataset.level)));

  // A hall appears twice — on the plan and in the list. Highlight both together.
  function setHighlight(room, on) {
    root.querySelectorAll("[data-room]").forEach((el) => {
      if (el.dataset.room === room) el.classList.toggle("is-active", on);
    });
  }

  root.querySelectorAll("[data-room]").forEach((el) => {
    const room = el.dataset.room;
    el.addEventListener("mouseenter", () => setHighlight(room, true));
    el.addEventListener("mouseleave", () => setHighlight(room, false));
    el.addEventListener("focus", () => setHighlight(room, true));
    el.addEventListener("blur", () => setHighlight(room, false));
  });

  /* ---- Hall detail ----------------------------------------------------
     Selecting a hall shows what is scheduled there without leaving the
     page. The programme data is only fetched on the first open, so the
     page costs nothing extra until someone actually asks for it. */

  const detail = root.querySelector("[data-hall-detail]");
  if (!detail) return;

  const levelOfRoom = {};
  root.querySelectorAll("[data-level-list]").forEach((list) => {
    list.querySelectorAll("[data-room]").forEach((a) => { levelOfRoom[a.dataset.room] = list.dataset.levelList; });
  });

  let programme = null;
  let openRoom = "";

  function esc(v) {
    return String(v).replace(/[&<>"']/g, (c) => (
      { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;" }[c]
    ));
  }

  function speakerLine(speakers) {
    const names = (speakers || []).filter((s) => s && s.toLowerCase() !== "tbc");
    if (!names.length) return (speakers || []).length ? "Speakers to be confirmed" : "";
    const shown = names.slice(0, 2).map((s) => s.replace(/\s*\(.+\)\s*$/, ""));
    return names.length > 2 ? `${shown.join("; ")} + ${names.length - 2} more` : shown.join("; ");
  }

  function sessionsFor(room) {
    return programme.days.map((day) => ({
      label: day.label,
      title: day.title,
      items: day.slots.flatMap((slot) =>
        (slot.sessions || [])
          .filter((s) => s.rooms.includes(room))
          .map((s) => ({ start: slot.start, end: slot.end, title: s.title, speakers: s.speakers }))
      ),
    })).filter((d) => d.items.length);
  }

  function renderDetail(room) {
    const link = root.querySelector(`.floorplan-room-link[data-room="${CSS.escape(room)}"]`);
    const count = link ? link.querySelector("span").textContent : "";
    const href = link ? link.getAttribute("href") : "#";
    const days = sessionsFor(room);

    detail.innerHTML = `
      <div class="floorplan-detail-head">
        <div>
          <p class="floorplan-detail-label">Hall</p>
          <strong>${esc(room)}</strong>
          <span>${esc(count)} · Level ${esc(levelOfRoom[room] || "")}</span>
        </div>
        <button class="floorplan-detail-close" type="button" data-hall-close aria-label="Close hall detail">×</button>
      </div>
      ${days.map((day) => `
        <div class="floorplan-detail-day">
          <p>${esc(day.title)}</p>
          <ul>
            ${day.items.map((s) => `
              <li>
                <time>${esc(s.start)}</time>
                <div><strong>${esc(s.title)}</strong>${
                  speakerLine(s.speakers) ? `<span>${esc(speakerLine(s.speakers))}</span>` : ""
                }</div>
              </li>`).join("")}
          </ul>
        </div>`).join("")}
      <a class="floorplan-detail-link" href="${esc(href)}">Open in programme <span aria-hidden="true">→</span></a>`;

    detail.querySelector("[data-hall-close]").addEventListener("click", closeHall);
  }

  function closeHall() {
    openRoom = "";
    toggleHidden(detail, true);
    lists.forEach((list) => toggleHidden(list, list.dataset.levelList !== currentLevel()));
    root.querySelectorAll("[data-room]").forEach((el) => el.classList.remove("is-selected"));
    if (location.hash.startsWith("#hall-")) history.replaceState(null, "", location.pathname + location.search);
  }

  function currentLevel() {
    const active = tabs.find((t) => t.classList.contains("is-active"));
    return active ? active.dataset.level : "0";
  }

  async function openHall(room, { scrollIntoView = false } = {}) {
    const level = levelOfRoom[room];
    if (level && level !== currentLevel()) showLevel(level);

    if (!programme) {
      detail.innerHTML = '<p class="floorplan-detail-loading">Loading sessions…</p>';
      lists.forEach((l) => toggleHidden(l, true));
      toggleHidden(detail, false);
      try {
        const res = await fetch(window.CEEDUCON_DATA_URL || "data/program.json");
        if (!res.ok) throw new Error(res.status);
        programme = await res.json();
      } catch (err) {
        console.error("Hall detail: programme could not be loaded.", err);
        const link = root.querySelector(`.floorplan-room-link[data-room="${CSS.escape(room)}"]`);
        detail.innerHTML = `<p class="floorplan-detail-loading">Sessions could not be loaded. <a href="${
          link ? link.getAttribute("href") : "programme.html"}">Open the programme</a>.</p>`;
        return;
      }
    }

    openRoom = room;
    lists.forEach((l) => toggleHidden(l, true));
    toggleHidden(detail, false);
    renderDetail(room);
    root.querySelectorAll("[data-room]").forEach((el) => el.classList.toggle("is-selected", el.dataset.room === room));
    history.replaceState(null, "", `#hall-${room}`);

    // #hall-B1 is not a real element id, so the browser cannot scroll to it.
    // Deferred twice so it lands after the browser's own scroll restoration.
    if (scrollIntoView) {
      const section = root.closest("section");
      // "auto" rather than the page's smooth scrolling: landing on a shared
      // link should place you at the section, not animate a long way to it.
      // Repeated on load so the browser's own scroll restoration cannot undo
      // it; rAF is avoided because it never fires in a background tab.
      // The page sets scroll-behavior: smooth, which some engines apply even
      // when the option says otherwise — so suspend it around the jump.
      const go = () => {
        const root_ = document.documentElement;
        const previous = root_.style.scrollBehavior;
        root_.style.scrollBehavior = "auto";
        section.scrollIntoView({ block: "start" });
        root_.style.scrollBehavior = previous;
      };
      go();
      if (document.readyState !== "complete") window.addEventListener("load", go, { once: true });
    }
  }

  // Plain left-click opens the panel; modifier clicks keep normal link behaviour.
  root.querySelectorAll("a[data-room]").forEach((a) => {
    a.addEventListener("click", (event) => {
      if (event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
      event.preventDefault();
      openHall(a.dataset.room);
    });
  });

  // Arrow keys walk through the halls drawn on the visible level.
  root.addEventListener("keydown", (event) => {
    if (!["ArrowRight", "ArrowDown", "ArrowLeft", "ArrowUp", "Escape"].includes(event.key)) return;
    if (event.key === "Escape") { if (openRoom) closeHall(); return; }

    const plan = plans.find((p) => !p.hasAttribute("hidden"));
    if (!plan || !plan.contains(document.activeElement)) return;
    const halls = [...plan.querySelectorAll("a[data-room]")];
    const i = halls.indexOf(document.activeElement.closest("a[data-room]"));
    if (i < 0) return;
    event.preventDefault();
    const step = event.key === "ArrowRight" || event.key === "ArrowDown" ? 1 : -1;
    halls[(i + step + halls.length) % halls.length].focus();
  });

  // #hall-D2 opens that hall directly, so a link can point at one. Handled on
  // load and on hashchange, so back/forward and pasted links both work.
  function openFromHash() {
    const match = decodeURIComponent(location.hash).match(/^#hall-(.+)$/);
    if (match && levelOfRoom[match[1]]) {
      if (match[1] !== openRoom) openHall(match[1], { scrollIntoView: true });
    } else if (openRoom) {
      closeHall();
    }
  }

  window.addEventListener("hashchange", openFromHash);
  openFromHash();
}

bindSiteNavigation();
bindHeaderScroll();
bindReveals();
bindStatCounters();
bindMediaLightbox();
bindThemeDetails();
bindMobileCarousels();
bindFloorplan();
