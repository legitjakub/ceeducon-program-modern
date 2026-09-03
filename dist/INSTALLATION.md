# CEEDUCON WordPress installation

## Required packages

- `ceeducon-program-wordpress-theme.zip` — the theme
- `ceeducon-control-center.zip` — the one plugin that edits everything

Superseded, kept only so an existing install can be rolled back:
`ceeducon-conference-settings.zip` and `ceeducon-programme-editor.zip`. Control
Center replaces both and switches them off when it is activated; all three write
to the same options, so nothing is migrated and nothing is lost either way.

## Installation

1. In **Appearance → Themes → Add New → Upload Theme**, upload and activate the theme ZIP.
2. In **Plugins → Add New → Upload Plugin**, upload and activate `ceeducon-control-center.zip`.
3. Open **CEEDUCON → Ročník konference** and check the year, dates, venue, registration, statistics and annual images.
4. Create the pages `about`, `programme`, `practical`, `speakers`, `media` and `contact`, then set a static homepage under **Settings → Reading**.
5. Assign the primary menu under **Appearance → Menus**.
6. Clear the WordPress and hosting cache after replacing the theme.

## Automatic updates from GitHub (WP Pusher)

The theme and both plugins live in subfolders of this repository, so each one
is installed separately and WP Pusher needs the subdirectory told to it:

| Package | Repository | Subdirectory | Branch |
| --- | --- | --- | --- |
| Theme | `legitjakub/ceeducon-program-modern` | `wordpress-theme/ceeducon-program` | `main` |
| Control Center | same | `wordpress-plugin/ceeducon-control-center` | `main` |
| Conference Edition *(superseded)* | same | `wordpress-plugin/ceeducon-conference-settings` | `main` |
| Programme Editor *(superseded)* | same | `wordpress-plugin/ceeducon-programme-editor` | `main` |

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

Values that change every year are edited once under **CEEDUCON → Ročník konference** and reused throughout the site.

## Editing the site with Control Center

The plugin adds one **CEEDUCON** menu with five screens:

- **Přehled** — what the site currently says, plus the checks worth running
  before an edition goes live and the list of `{{tokens}}` with their values.
- **Program** — the visual programme editor (below).
- **Texty webu** — every visible text, grouped by page, with search, a preview
  of how each `{{token}}` resolves, and a per-field way back to the theme's
  wording. An emptied field falls back to what the theme ships.
- **Ročník konference** — the values that change every year. One save here
  reaches the hero, the calendar links, the SEO descriptions and the Event
  structured data.
- **Zálohy a nástroje** — download the whole configuration as one JSON file,
  restore it, or read the programme exactly as the front end sees it.

## Editing the programme

**CEEDUCON → Program** edits the interactive programme: day tabs, time slots,
and the sessions inside them. A slot is either a set of sessions or a break.
Clicking a session opens a side panel with its title, rooms, theme, type,
format, speakers and abstract; sessions can be dragged from one slot to another
or moved with the *Přesunout do bloku* selector. The second tab of the screen
holds the rooms, themes, session types and formats.

The whole programme is posted back as a single JSON document. That matters: a
form with one input per field would need well over a thousand inputs for two
days and nine rooms, and PHP accepts a thousand by default (`max_input_vars`) —
everything past the limit is dropped without an error. Each save also writes a
one-step backup, restorable from the same screen.
