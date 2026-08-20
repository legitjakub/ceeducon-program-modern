#!/bin/sh
set -eu

ROOT=$(CDPATH= cd -- "$(dirname -- "$0")/.." && pwd)
DIST="$ROOT/dist"
STAGE=$(mktemp -d "${TMPDIR:-/tmp}/ceeducon-packages.XXXXXX")
trap 'rm -rf "$STAGE"' EXIT INT TERM

mkdir -p "$DIST/elementor-templates"
cp -R "$ROOT/wordpress-theme/ceeducon-program" "$STAGE/ceeducon-program"
cp -R "$ROOT/wordpress-plugin/ceeducon-elementor-widgets" "$STAGE/ceeducon-elementor-widgets"
cp -R "$ROOT/wordpress-plugin/ceeducon-conference-settings" "$STAGE/ceeducon-conference-settings"
cp -R "$ROOT/wordpress-plugin/ceeducon-elementor-widgets/." "$DIST/ceeducon-elementor-widgets/"
rm -rf "$DIST/ceeducon-conference-settings"
cp -R "$ROOT/wordpress-plugin/ceeducon-conference-settings" "$DIST/ceeducon-conference-settings"
cp "$ROOT"/wordpress-plugin/ceeducon-elementor-widgets/templates/*.json "$DIST/elementor-templates/"
cp "$ROOT/WORDPRESS-ELEMENTOR-INSTALLATION.md" "$DIST/INSTALLATION.md"

find "$STAGE" "$DIST" -name '.DS_Store' -delete
find "$STAGE" -name '__MACOSX' -type d -prune -exec rm -rf {} +

rm -f \
  "$DIST/ceeducon-program-wordpress-theme.zip" \
  "$DIST/ceeducon-elementor-widgets.zip" \
  "$DIST/ceeducon-conference-settings.zip" \
  "$DIST/ceeducon-elementor-page-templates.zip"

(cd "$STAGE" && zip -X -q -r "$DIST/ceeducon-program-wordpress-theme.zip" ceeducon-program)
(cd "$STAGE" && zip -X -q -r "$DIST/ceeducon-elementor-widgets.zip" ceeducon-elementor-widgets)
(cd "$STAGE" && zip -X -q -r "$DIST/ceeducon-conference-settings.zip" ceeducon-conference-settings)

mkdir -p "$STAGE/ceeducon-elementor-page-templates"
cp "$ROOT"/wordpress-plugin/ceeducon-elementor-widgets/templates/*.json "$STAGE/ceeducon-elementor-page-templates/"
cp "$ROOT/WORDPRESS-ELEMENTOR-INSTALLATION.md" "$STAGE/ceeducon-elementor-page-templates/INSTALLATION.md"
(cd "$STAGE" && zip -X -q -r "$DIST/ceeducon-elementor-page-templates.zip" ceeducon-elementor-page-templates)

(
  cd "$DIST"
  shasum -a 256 \
    ceeducon-program-wordpress-theme.zip \
    ceeducon-elementor-widgets.zip \
    ceeducon-conference-settings.zip \
    ceeducon-elementor-page-templates.zip \
    elementor-templates/*.json > SHA256SUMS.txt
)
