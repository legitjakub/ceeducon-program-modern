# CEEDUCON Programme — WordPress theme

Multi-page conference theme for CEEDUCON 2026 (1–2 December 2026, O2 universum Prague).
Same design and front-end code as the static preview site — plain PHP templates, one CSS file and vanilla JS.

## Installation

1. Upload the ZIP in **Appearance → Themes → Add New → Upload Theme** and activate it.
2. Create five pages with these slugs (the titles can be anything):
   - `about`
   - `programme`
   - `practical`
   - `speakers`
   - `contact`

   Each page is matched automatically by its slug (`page-{slug}.php`). If you use different
   slugs, assign the matching template manually in the page editor
   (*CEEDUCON About*, *CEEDUCON Programme*, …).
3. Set a static front page in **Settings → Reading** (any page — the theme's `front-page.php` renders the home layout).

## Editing content

All visible texts live in **wp-admin → CEEDUCON Content**, grouped per page/section:

- Global header & footer (footer texts, contact links)
- Home hero, stats, media gallery and sections
- Thematic areas (shared between Home and About)
- Programme day cards and notices (shared between Home and Programme)
- About / Programme / Practical / Speakers / Contact page texts
- **Programme data** — the `Programme JSON` field

Fields left empty fall back to the built-in defaults.

## Programme data

The interactive grid on the Programme page reads, in order of priority:

1. The **Programme JSON** field in *CEEDUCON Content* (validated on save),
2. the bundled `data/program.json`.

The JSON structure (`event`, `rooms`, `themes`, `slots`) matches the static site.
The current data is the archived CEEDUCON 2025 programme, clearly labelled as an
archive sample on the page; replace it with the official 2026 sessions when published.
Theme track colours use the CEEDUCON brand palette (`#0d5e9d`, `#ec722f`, `#45c0ea`, `#06304f`).

For a later phase, the JSON can be migrated to a custom post type without changing
the front-end: `program.js` only needs `window.CEEDUCON_PROGRAM_DATA` in the same shape.

## Files

- `header.php` / `footer.php` — shared layout, navigation, footer, programme modal
- `front-page.php` — home
- `page-about|programme|practical|speakers|contact.php` — page templates
- `index.php` — fallback for any other content
- `css/styles.css` — full design system (identical to the static site)
- `js/site.js` — menu, header state, countdown, scroll reveals
- `js/program.js` + `js/program-data.js` + `data/program.json` — interactive programme
- `assets/` — logos, favicon, Tabac Sans web fonts
