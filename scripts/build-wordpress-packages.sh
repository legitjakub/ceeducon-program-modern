#!/bin/sh
set -eu

ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
DIST="$ROOT/dist"
STAGE=$(mktemp -d "${TMPDIR:-/tmp}/ceeducon-packages.XXXXXX")
trap 'rm -rf "$STAGE"' EXIT INT TERM

cp -R "$ROOT/wordpress-theme/ceeducon-program" "$STAGE/ceeducon-program"
cp -R "$ROOT/wordpress-plugin/ceeducon-conference-settings" "$STAGE/ceeducon-conference-settings"
cp "$ROOT/WORDPRESS-INSTALLATION.md" "$DIST/INSTALLATION.md"

find "$STAGE" "$DIST" -name '.DS_Store' -delete
find "$STAGE" -name '__MACOSX' -type d -prune -exec rm -rf {} +

rm -f \
  "$DIST/ceeducon-program-wordpress-theme.zip" \
  "$DIST/ceeducon-conference-settings.zip"

(cd "$STAGE" && zip -X -q -r "$DIST/ceeducon-program-wordpress-theme.zip" ceeducon-program)
(cd "$STAGE" && zip -X -q -r "$DIST/ceeducon-conference-settings.zip" ceeducon-conference-settings)

(
  cd "$DIST"
  shasum -a 256 \
    ceeducon-program-wordpress-theme.zip \
    ceeducon-conference-settings.zip > SHA256SUMS.txt
)
