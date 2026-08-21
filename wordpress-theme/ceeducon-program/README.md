# CEEDUCON Programme — WordPress theme

Multi-page conference theme for CEEDUCON 2026 (1–2 December 2026, O2 universum Prague).
The theme uses native Gutenberg section blocks for page content and has no third-party page builder dependency.

The interactive programme is available as a Gutenberg block and the `[ceeducon_programme]` shortcode. Place only one programme component on a page; its modal and supporting UI are rendered once in the theme footer.

## Installation

1. Upload the ZIP in **Appearance → Themes → Add New → Upload Theme** and activate it.
2. Create six pages with these slugs (the titles can be anything):
   - `about`
   - `programme`
   - `practical`
   - `speakers`
   - `media`
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
- Čtvercová fotogalerie
- Mapa účastníků / regionálního dosahu
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

The homepage Video block accepts a normal YouTube URL, creates a privacy-enhanced responsive embed and keeps the title, description, accessible video title, link label and caption editable in the block sidebar.

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

WordPress core owns document titles and canonical URLs. When no supported SEO plugin is active, the
theme adds an editable meta-description and Open Graph/Twitter fallback from **CEEDUCON Content**, plus
Event structured data on the homepage. The fallback disables itself when Yoast SEO, Rank Math, SEOPress,
AIOSEO or The SEO Framework is active, so those plugins remain the preferred source of advanced metadata.
Configure the CEEDUCON 2026 banner as the default social image in the selected SEO plugin.

Important page content remains server-rendered HTML in PHP and Gutenberg rendering paths.
Media selected from the WordPress library uses attachment IDs and responsive image markup where the
component supports it. Keep descriptive alt text with the image in the Media Library.

### Production SEO checklist

1. In **Settings → Reading**, make sure “Discourage search engines from indexing this site” is disabled.
2. Use **Settings → Permalinks → Post name** and keep the page slugs used by the primary navigation.
3. Activate only one SEO plugin. Its metadata takes precedence over the theme fallback automatically.
4. Set a unique SEO title and description for every public page and use the approved CEEDUCON social image.
5. Confirm the site language, timezone (`Europe/Prague`), HTTPS canonical domain and XML sitemap.
6. Submit the sitemap in Google Search Console after the production domain is live.
7. Replace preliminary programme data and any `TBC` speaker information before launch.

## Programme data

The interactive grid on the Programme page reads, in order of priority:

1. The **Programme JSON** field in *CEEDUCON Content* (validated on save),
2. the bundled `data/program.json`.

The JSON structure (`event`, `rooms`, `themes`, `slots`) matches the static site.
Invalid JSON is not saved over the last valid value, so a formatting mistake in the admin cannot break
the public programme.
The Programme page also renders a server-side text version of sessions below the interactive grid,
so important session titles, times and rooms are present in normal HTML for SEO and non-JavaScript users.
The bundled data contains the current two-day CEEDUCON 2026 programme. Update the
central Programme JSON field when approved session details change.
Theme track colours use only the CEEDUCON brand palette (`#0d5e9d`, `#ec722f`, `#45c0ea`, `#ffffff`).

For a later phase, the JSON can be migrated to a custom post type without changing
the front-end: `program.js` only needs `window.CEEDUCON_PROGRAM_DATA` in the same shape.

## Files

- `header.php` / `footer.php` — shared layout, navigation, footer, programme modal
- `front-page.php` — home
- `page-about|programme|practical|speakers|media|contact.php` — page templates
- `index.php` — fallback for any other content
- `src/blocks/` — native CEEDUCON Gutenberg blocks
- `css/styles.css` — full design system (identical to the static site)
- `css/editor-style.css` + `theme.json` — WordPress editor palette, fonts and block defaults
- `js/site.js` — menu, header state, scroll reveals and mobile carousels
- `js/program.js` + `js/program-data.js` + `data/program.json` — interactive programme
- `assets/` — logos, favicon, Tabac Sans web fonts
