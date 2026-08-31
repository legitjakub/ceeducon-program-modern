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

## Automatic updates from GitHub (WP Pusher)

The theme and both plugins live in subfolders of this repository, so each one
is installed separately and WP Pusher needs the subdirectory told to it:

| Package | Repository | Subdirectory | Branch |
| --- | --- | --- | --- |
| Theme | `legitjakub/ceeducon-program-modern` | `wordpress-theme/ceeducon-program` | `main` |
| Conference Edition | same | `wordpress-plugin/ceeducon-conference-settings` | `main` |
| Programme Editor | same | `wordpress-plugin/ceeducon-programme-editor` | `main` |

Three things have to be true for a push to reach the site:

1. **The package was installed *by* WP Pusher.** WP Pusher only updates what it
   installed itself — a theme uploaded as a ZIP under *Appearance → Themes* is
   invisible to it. Install it once from *WP Pusher → Install theme* (or
   *Install plugin*) with the subdirectory above, replacing the uploaded copy.
2. **The work is on `main`.** WP Pusher tracks one branch; commits on a feature
   branch never reach it.
3. **Push-to-Deploy is switched on** for the package, and the repository has the
   matching webhook under *Settings → Webhooks* on GitHub. Without it WP Pusher
   only notices a new version when the Themes or Plugins screen is loaded, and
   the update still has to be clicked.

Updates are recognised by the version header, so every change that should reach
the site needs its version bumped: `Version:` in
`wordpress-theme/ceeducon-program/style.css` for the theme and the plugin header
comment for each plugin.

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
