# CEEDUCON Programme WordPress Theme

Upload this folder as a WordPress theme or upload the prepared ZIP package.

## How to install

1. In WordPress admin go to **Appearance -> Themes -> Add New -> Upload Theme**.
2. Upload `ceeducon-program-wordpress-theme.zip`.
3. Activate **CEEDUCON Programme**.
4. Open **Appearance -> Customize -> CEEDUCON content**.
5. Edit the homepage texts there.

## What is editable in WordPress

- Hero title, lead text, event date/card and CTA labels.
- About section text and highlight chips.
- Thematic area titles and descriptions.
- Programme intro text.
- Venue title, text, button and URL.
- Footer title and subtitle.

## Programme data

The interactive programme still uses structured JSON data:

- `data/program.json`
- `js/program-data.js`

For a production WordPress build, this could later be moved to ACF repeaters or a custom post type. For this assignment version, the theme is intentionally lightweight and keeps the programme data file-based.

