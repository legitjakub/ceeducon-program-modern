(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls } = blockEditor;
  const { PanelBody, TextControl, RangeControl, ToggleControl } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/posts", {
    edit({ attributes, setAttributes }) {
      const placeholders = Array.from({ length: attributes.count || 3 });

      return el(
        "section",
        { className: `section ceeducon-editor-block ${attributes.paper ? "section--paper" : ""}` },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení výpisu", "ceeducon-program"), initialOpen: true },
            el(TextControl, { label: __("Post type", "ceeducon-program"), help: __("Např. post nebo vlastní CPT slug.", "ceeducon-program"), value: attributes.postType, onChange: (postType) => setAttributes({ postType }) }),
            el(RangeControl, { label: __("Počet položek", "ceeducon-program"), value: attributes.count, min: 1, max: 6, onChange: (count) => setAttributes({ count }) }),
            el(ToggleControl, { label: __("Světlé papírové pozadí", "ceeducon-program"), checked: attributes.paper, onChange: (paper) => setAttributes({ paper }) })
          )
        ),
        el(
          "div",
          { className: "shell" },
          el(
            "div",
            { className: "section-head" },
            el("div", {}, el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }), el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) })),
            el(RichText, { tagName: "p", value: attributes.intro, allowedFormats: ["core/bold", "core/italic"], onChange: (intro) => setAttributes({ intro }) })
          ),
          el(
            "div",
            { className: "tile-grid" },
            placeholders.map((_, index) => el("article", { className: "link-tile", key: index }, el("span", {}, __("Dynamický výpis", "ceeducon-program")), el("h3", {}, `${__("Článek", "ceeducon-program")} ${index + 1}`), el("p", {}, __("Na frontendu se načtou reálné publikované položky.", "ceeducon-program"))))
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
