# CEEDUCON Programme Editor

Edits the conference programme through a form instead of a raw JSON textarea.

## What it does

Adds **CEEDUCON Content → Program** in wp-admin, where an editor can:

- add, edit and remove conference days (date, label, heading),
- add and remove time slots, and switch a slot between *sessions* and *break*,
- edit each session: title, theme, format, rooms (checkboxes) and speakers,
- rename themes and change their colours,
- maintain the room list that drives the grid columns.

## Why it is safe to install

It reads and writes the **same** `ceeducon_content['programme_json']` option the
theme already uses, so the front end needs no change. If the option is empty it
falls back to the `data/program.json` bundled with the theme, exactly like
`ceeducon_programme_data()` does.

The old JSON field on *CEEDUCON Content* keeps working; this screen is simply a
friendlier way to produce the same value. A read-only copy of the current JSON
sits at the bottom of the screen for backups.

## Requirements

WordPress 6.0+, PHP 8.0+, the CEEDUCON Programme theme active.
