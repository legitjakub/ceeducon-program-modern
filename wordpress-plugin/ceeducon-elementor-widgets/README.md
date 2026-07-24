# CEEDUCON Elementor Widgets

Companion plugin for the **CEEDUCON Programme** theme and Elementor Free. It adds the category **CEEDUCON Sections** with Hero, Page Hero, Text Section, Image + Text, Video, Photo Gallery, Themes, Two-day Overview, Venue, Organisers and Partners, Cards, Testimonials, FAQ, CTA, Contact, Posts and Programme widgets.

## Installation

1. Activate the CEEDUCON Programme theme and Elementor Free.
2. Upload and activate `ceeducon-elementor-widgets.zip` in Plugins.
3. Set the page template to **CEEDUCON Elementor Full Width**.
4. Click **Edit with Elementor** and use widgets from **CEEDUCON Sections**.

Controls are derived from the theme block metadata. Text, images, links, repeaters and switches are editable in **Content**. The **Style** tab offers only the approved CEEDUCON palette and image focal point. The **Responsive** section controls spacing, alignment, columns and supported image order per device. Font, semantic markup and technical structure remain controlled by the theme.

CEEDUCON widgets are page-level sections. Use the **CEEDUCON Elementor Full Width** page template. Imported root containers use Elementor's native full-width setting and zero outer padding; content remains aligned to the theme's responsive `.shell`. No viewport-width breakout or negative margins are used.

The plugin also bundles fallback copies of the control schemas. This keeps the Elementor control panel editable when a theme switch or cache refresh temporarily makes the active theme metadata unavailable.

## Starter pages

Use **Templates > Saved Templates > Import Templates** to import a JSON file from `templates/`, then insert it into a new draft page. Templates are included for Homepage, About, Programme, Practical, Speakers, Media Kit and Contact. Existing Gutenberg or Divi content is not deleted automatically and imported templates never overwrite a live page by themselves.

The Programme widget reads the same `data/program.json` and uses the same scripts and server-rendered fallback as the Gutenberg block. The same component is available as `[ceeducon_programme]` for legacy content. Use only one Programme block, widget or shortcode per page.

After updating the theme or this plugin, run **Elementor > Tools > Regenerate CSS & Data** and clear the WordPress page cache before reopening the editor.

To roll back, deactivate the plugin and restore the page's original CEEDUCON template/content. Gutenberg and PHP fallback rendering remain available.
