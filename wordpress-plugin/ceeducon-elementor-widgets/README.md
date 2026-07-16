# CEEDUCON Elementor Widgets

Companion plugin for the **CEEDUCON Programme** theme and Elementor Free. It adds the category **CEEDUCON Sections** with Hero, Page Hero, Text Section, Image + Text, Video, Photo Gallery, Cards, Testimonials, FAQ, CTA, Contact, Posts and Programme widgets.

## Installation

1. Activate the CEEDUCON Programme theme and Elementor Free.
2. Upload and activate `ceeducon-elementor-widgets.zip` in Plugins.
3. Set the page template to **CEEDUCON Elementor Full Width**.
4. Click **Edit with Elementor** and use widgets from **CEEDUCON Sections**.

Controls are derived from the theme block metadata. Text, images, links, repeaters and switches are editable; markup, brand colours and responsive behaviour remain controlled by the theme. Controls are grouped by purpose, and the widget uses Elementor's server-rendered preview so the editor and published page share exactly the same PHP markup.

CEEDUCON widgets are page-level sections. They automatically extend across the available viewport while their content stays aligned to the theme's responsive `.shell`, even if Elementor inserts the widget into its default boxed container.

The plugin also bundles fallback copies of the control schemas. This keeps the Elementor control panel editable when a theme switch or cache refresh temporarily makes the active theme metadata unavailable.

## Starter pages

Use **Templates > Saved Templates > Import Templates** to import a JSON file from `templates/`, then insert it into the matching page. Templates are included for Homepage, About, Programme, Practical, Speakers, Media Kit and Contact. Existing Gutenberg content is not deleted automatically.

The Programme widget reads the same `data/program.json` and uses the same scripts and server-rendered fallback as the Gutenberg block. The same component is available as `[ceeducon_programme]` for legacy content. Use only one Programme block, widget or shortcode per page.

After updating the theme or this plugin, run **Elementor > Tools > Regenerate CSS & Data** and clear the WordPress page cache before reopening the editor.

To roll back, deactivate the plugin and restore the page's original CEEDUCON template/content. Gutenberg and PHP fallback rendering remain available.
