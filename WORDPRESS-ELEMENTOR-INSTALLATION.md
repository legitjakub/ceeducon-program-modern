# CEEDUCON WordPress + Elementor installation

## Required components

- WordPress 6.5 or newer
- PHP 8.0 or newer
- Elementor Free 3.20 or newer
- `ceeducon-program-wordpress-theme.zip`
- `ceeducon-elementor-widgets.zip`

The theme owns the design, Tabac Sans font, header, footer, navigation, responsive layout and semantic HTML. Elementor owns page content assembled from CEEDUCON section widgets. Programme sessions remain in **CEEDUCON Content** in WordPress so a large schedule is not stored in one Elementor repeater.

## Safe installation

1. Make a database and files backup or create a staging copy of the website.
2. In **Appearance → Themes → Add New → Upload Theme**, upload and activate `ceeducon-program-wordpress-theme.zip`.
3. Install and activate Elementor Free. Update it to at least version 3.20.
4. In **Plugins → Add New → Upload Plugin**, upload and activate `ceeducon-elementor-widgets.zip`.
5. If WordPress shows a compatibility notice, update the named component before continuing. The plugin does not load its widget classes until Elementor is available and compatible.
6. Go to **Elementor → Tools** and run **Regenerate CSS & Data**. Clear the WordPress and hosting cache.

## Import without overwriting the live website

1. Open **Templates → Saved Templates → Import Templates**.
2. Import the individual JSON file for the page you want to test. The combined ZIP is only a convenient download bundle; Elementor imports the JSON files inside it.
3. Create a new WordPress page and keep it as **Draft**.
4. Set **Page template** to **CEEDUCON Elementor Full Width**.
5. Click **Edit with Elementor**, insert the imported CEEDUCON template and save the page as a draft.
6. Check desktop, tablet and mobile previews before replacing any existing page.
7. Only after approval, update the WordPress menu or page URL. Do not delete the old page until redirects and SEO metadata have been checked.

## Editing sections

- **Content:** headings, text, buttons, links, images, alt text, cards, FAQ items, testimonials and gallery items.
- **Style:** approved CEEDUCON background, heading, text and accent colours; image focal point; content width.
- **Responsive:** top and bottom spacing, text alignment, supported column counts and image order for desktop, tablet and mobile.
- Clear a changed style control to restore the original GitHub design.

Header, footer, logo and navigation are global. Edit the menu under **Appearance → Menus** and the logo/site identity under **Appearance → Customise** rather than changing them on every Elementor page.

## SEO and migration checks

- Keep exactly one Hero or Page Hero widget per page so the page has one `h1`.
- Add meaningful alt text to every informative image. Decorative images may use an empty alt value.
- Keep normal descriptive link text instead of “click here”.
- Configure title, meta description, canonical URL and social preview in Yoast SEO or Rank Math.
- Do not publish duplicate Elementor and legacy pages under separate indexable URLs.
- The Programme widget has a server-rendered HTML fallback and remains readable without JavaScript.

## Recovery

If the plugin is deactivated, the CEEDUCON theme, Gutenberg blocks and PHP fallback templates remain available. If a widget shows a theme compatibility message, activate the supplied CEEDUCON Programme theme, regenerate Elementor CSS and reload the editor.
