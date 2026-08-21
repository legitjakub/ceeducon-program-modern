# CEEDUCON WordPress installation

## Required packages

- `ceeducon-program-wordpress-theme.zip`
- `ceeducon-conference-settings.zip`
- `ceeducon-programme-editor.zip`

## Installation

1. In **Appearance → Themes → Add New → Upload Theme**, upload and activate the theme ZIP.
2. In **Plugins → Add New → Upload Plugin**, upload and activate the conference settings ZIP, then the programme editor ZIP.
3. Open **CEEDUCON Content → Conference edition** and check the year, dates, venue, registration, statistics and annual images.
4. Create the pages `about`, `programme`, `practical`, `speakers`, `media` and `contact`, then set a static homepage under **Settings → Reading**.
5. Assign the primary menu under **Appearance → Menus**.
6. Clear the WordPress and hosting cache after replacing the theme.

## Editing pages

Open a page in the standard WordPress block editor. Important headings, paragraphs, buttons, cards and images are edited in the CEEDUCON blocks under **Sekce webu**. Layout, colours, spacing and responsive behaviour stay controlled by the theme.

Values that change every year are edited once under **CEEDUCON Content → Conference edition** and reused throughout the site.

## Editing the programme

**CEEDUCON Content → Program** edits the interactive programme as a form: days,
time slots, and the sessions inside them. A slot is either a set of sessions or
a break; switching the type shows the matching fields. Rooms are ticked from the
room list, so a typo cannot put a session in a hall that does not exist — the
screen warns about that after saving.

It writes to the same place the theme reads the programme from, so a save is
live immediately. The old *Programme JSON* field on **CEEDUCON Content** still
works and now serves as the manual escape hatch; a read-only copy of the current
JSON sits at the bottom of the programme screen for backups.
