# CEEDUCON Programme WordPress Theme

Upload this folder as a WordPress theme or upload the prepared ZIP package.

## How to install

1. In WordPress admin go to **Appearance -> Themes -> Add New -> Upload Theme**.
2. Upload `ceeducon-program-wordpress-theme.zip`.
3. Activate **CEEDUCON Programme**.
4. Open **CEEDUCON Content** in the left WordPress admin menu.
5. Edit the homepage texts there and save. Longer copy fields use the normal WordPress editor.

The older **Appearance -> Customize -> CEEDUCON content** fields are still available as a fallback, but the dedicated admin page is easier to use.

## What is editable in WordPress

- Hero title, lead text, event date/card and CTA labels.
- About section text and highlight chips.
- Thematic area titles and descriptions.
- CEEDUCON 2026 programme overview and registration CTA.
- Programme intro, archive notice and FAQ text.
- Practical information and speaker guidance.
- Venue title, text, button and URL.
- Contact and partner text.
- Footer title, subtitle and main footer links.
- Programme JSON for rooms, themes and interactive schedule sessions.

## Programme data

The interactive programme uses structured JSON data and can be edited in **CEEDUCON Content -> Programme data**:

- `data/program.json`
- `js/program-data.js`

When the Programme JSON field contains valid JSON, the front end uses that saved WordPress data instead of the bundled file. A future production build could still move the same structure to ACF repeaters or a custom post type, but this ZIP already lets editors update the schedule without opening code.
