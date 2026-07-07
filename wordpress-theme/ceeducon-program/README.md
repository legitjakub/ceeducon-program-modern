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

All visible texts live in **wp-admin → CEEDUCON Content**, grouped per page/section.
When ACF Pro is active, this screen is registered as an ACF Options Page and the theme reads
fields with matching names from ACF first. When ACF is not active, the theme falls back to its
built-in lightweight content editor, so the theme remains uploadable on a clean WordPress install.

- Global header & footer (footer texts, contact links)
- Navigation uses **Appearance → Menus → Primary navigation** when assigned, with a built-in fallback menu.
- Brand colours, wide alignment and the Tabac Sans editor preview are registered through `theme.json` and `css/editor-style.css`, so normal WordPress blocks stay visually close to the CEEDUCON identity.
- The normal WordPress page editor is also supported. If a page contains Gutenberg content, it is rendered below the hero section in a styled CEEDUCON content band; empty editor content outputs nothing.
- Home hero, stats, media gallery and sections
- Thematic areas (shared between Home and About)
- Programme day cards and notices (shared between Home and Programme)
- About / Programme / Practical / Speakers / Contact page texts
- **Programme data** — the `Programme JSON` field

Fields left empty fall back to the built-in defaults.

## ACF workflow

The theme is ACF-compatible:

- `get_field($key, 'option')` is used first when ACF is active.
- ACF Options Page slug: `ceeducon-content`.
- ACF JSON save/load path: `acf-json/`.
- Field names intentionally match the fallback field keys, for example `home_hero_title`,
  `media_hero_alt`, `spk_cta_url`, `programme_json`.

The current fallback editor exists only so the theme can still run without ACF. In a production
WordPress setup, install ACF Pro, open **CEEDUCON Content**, adjust/sync fields if needed, and let
ACF write field-group JSON into `acf-json/` for version control. Concrete content values remain in
the database, not in Git.

The theme is intentionally a classic PHP theme, not a full Site Editor block theme. This keeps the
conference layouts stable, while day-to-day copy, menu labels, contact links and programme data remain
editable from the WordPress admin. If full layout editing is needed later, the next step would be a
block theme or custom CEEDUCON blocks/patterns.

## SEO and social previews

The theme leaves document titles, meta descriptions, canonical URLs and Open Graph/Twitter card tags to
a WordPress SEO plugin such as Yoast SEO or Rank Math. Configure the CEEDUCON 2026 banner as the default
social image there. The static preview includes hardcoded social metadata only because it does not run
inside WordPress.

## Programme data

The interactive grid on the Programme page reads, in order of priority:

1. The **Programme JSON** field in *CEEDUCON Content* (validated on save),
2. the bundled `data/program.json`.

The JSON structure (`event`, `rooms`, `themes`, `slots`) matches the static site.
Invalid JSON is not saved over the last valid value, so a formatting mistake in the admin cannot break
the public programme.
The Programme page also renders a server-side text version of sessions below the interactive grid,
so important session titles, times and rooms are present in normal HTML for SEO and non-JavaScript users.
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
- `css/editor-style.css` + `theme.json` — WordPress editor palette, fonts and block defaults
- `js/site.js` — menu, header state, countdown, scroll reveals
- `js/program.js` + `js/program-data.js` + `data/program.json` — interactive programme
- `assets/` — logos, favicon, Tabac Sans web fonts
