# CEEDUCON Programme — WordPress theme

Multi-page conference theme for CEEDUCON 2026 (1–2 December 2026, O2 universum Prague).
The theme now uses native Gutenberg section blocks for page content. There is no ACF Blocks,
Elementor, Divi or page builder dependency.

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

Client-editable page sections are edited directly in the WordPress block editor.
Use the custom block category **Sekce webu**.

Available CEEDUCON blocks:

- Hero sekce
- Textová sekce
- Obrázek s textem
- Karty / výhody
- Reference
- FAQ
- CTA sekce
- Kontakt
- Výpis článků

The blocks store content in normal Gutenberg block attributes in the database. The frontend HTML,
CSS classes, responsive behaviour and escaping stay controlled by the theme code.

Navigation uses **Appearance → Menus → Primary navigation** when assigned, with a built-in fallback menu.
Footer defaults and the interactive programme JSON still use the lightweight **CEEDUCON Content** admin
screen as a compatibility fallback for global values and programme data.

## Gutenberg block workflow

The theme is a classic PHP theme with native custom Gutenberg blocks:

- `src/blocks/{block}/block.json` registers the block.
- `src/blocks/{block}/edit.js` defines the React editor UI.
- `src/blocks/{block}/render.php` renders dynamic frontend HTML.
- `src/blocks/{block}/style.css` and `editor.css` keep frontend/editor visuals aligned.
- `functions.php` registers all blocks and restricts page editors to the approved CEEDUCON blocks plus a small set of safe core blocks.

Important design choices:

- No ACF Blocks and no page builder.
- No client-editable content hardcoded in PHP when using the Gutenberg block version of a page.
- Blocks reuse existing CEEDUCON classes such as `section`, `shell`, `section-head`, `hero`, `tile-grid`, `contact-band`, `btn`.
- `theme.json` disables broad design controls so users can edit content without changing global colours, typography or spacing.

To create a homepage quickly, insert the pattern **CEEDUCON homepage** from the block inserter.

## Client editing guide

1. Open the page in WordPress admin.
2. Add or select a block from **Sekce webu**.
3. Click directly into headings and text to edit copy.
4. Use the right sidebar for links, repeated cards, contact details and images.
5. Drag blocks up/down or use the block toolbar arrows to change the section order.
6. Update the page. The frontend keeps the CEEDUCON design automatically.

Do not edit CSS, global colours or spacing in the editor. Those are intentionally controlled by the theme.

## Adding a new block

1. Copy an existing folder in `src/blocks/`.
2. Rename the block in `block.json` and define attributes.
3. Build the editor experience in `edit.js` with `RichText`, `InspectorControls`, `URLInput` and `MediaUpload` as needed.
4. Render frontend markup in `render.php` and escape output with `esc_html()`, `esc_url()`, `esc_attr()` or `wp_kses_post()`.
5. Add the new block name to `ceeducon_allowed_block_types()` in `functions.php`.
6. Reuse existing CSS classes wherever possible.

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
- `src/blocks/` — native CEEDUCON Gutenberg blocks
- `css/styles.css` — full design system (identical to the static site)
- `css/editor-style.css` + `theme.json` — WordPress editor palette, fonts and block defaults
- `js/site.js` — menu, header state, countdown, scroll reveals
- `js/program.js` + `js/program-data.js` + `data/program.json` — interactive programme
- `assets/` — logos, favicon, Tabac Sans web fonts
