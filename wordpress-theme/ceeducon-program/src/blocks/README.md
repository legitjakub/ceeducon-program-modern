# CEEDUCON Native Gutenberg Blocks

The theme uses native WordPress blocks, not ACF Blocks or a page builder.

Each block lives in its own folder:

- `block.json` registers attributes, scripts, styles and the PHP render file.
- `edit.js` defines the React editor UI with `RichText`, `InspectorControls`, `URLInput` and `MediaUpload`.
- `render.php` renders the frontend with the CEEDUCON HTML/CSS classes and WordPress escaping.
- `style.css` is loaded on frontend and editor.
- `editor.css` contains editor-only polish.

The current section blocks are:

- `ceeducon/hero`
- `ceeducon/text-section`
- `ceeducon/image-text`
- `ceeducon/cards`
- `ceeducon/testimonials`
- `ceeducon/faq`
- `ceeducon/cta`
- `ceeducon/contact`
- `ceeducon/posts`

## Adding a New Block

1. Copy one of the existing block folders.
2. Change `name`, `title`, `description`, attributes and icon in `block.json`.
3. Build the editor UI in `edit.js`.
4. Render frontend markup in `render.php`.
5. Add the block name to `ceeducon_allowed_block_types()` in `functions.php`.
6. Keep frontend output escaped with `esc_html()`, `esc_url()`, `esc_attr()` or `wp_kses_post()`.

Blocks should use existing CEEDUCON classes (`section`, `shell`, `section-head`, `tile-grid`, `btn`, `contact-band`, etc.) so the design remains controlled by the theme.
