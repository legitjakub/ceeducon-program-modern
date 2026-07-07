(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl, ToggleControl } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/text-section", {
    edit({ attributes, setAttributes }) {
      const chips = attributes.chips || [];
      const updateChip = (index, value) => setAttributes({ chips: chips.map((chip, chipIndex) => (chipIndex === index ? value : chip)) });

      return el(
        "section",
        { className: `section ceeducon-editor-block ${attributes.paper ? "section--paper" : ""}` },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení", "ceeducon-program"), initialOpen: true },
            el(ToggleControl, {
              label: __("Světlé papírové pozadí", "ceeducon-program"),
              checked: attributes.paper,
              onChange: (paper) => setAttributes({ paper }),
            }),
            el(TextControl, {
              label: __("Text tlačítka", "ceeducon-program"),
              value: attributes.buttonText,
              onChange: (buttonText) => setAttributes({ buttonText }),
            }),
            el(URLInput, {
              label: __("URL tlačítka", "ceeducon-program"),
              value: attributes.buttonUrl,
              onChange: (buttonUrl) => setAttributes({ buttonUrl }),
            })
          ),
          el(
            PanelBody,
            { title: __("Štítky", "ceeducon-program"), initialOpen: false },
            chips.map((chip, index) =>
              el(TextControl, {
                key: index,
                label: `${__("Štítek", "ceeducon-program")} ${index + 1}`,
                value: chip,
                onChange: (value) => updateChip(index, value),
              })
            )
          )
        ),
        el(
          "div",
          { className: "shell statement-grid" },
          el(
            "div",
            {},
            el(RichText, {
              tagName: "p",
              className: "kicker",
              value: attributes.kicker,
              allowedFormats: [],
              onChange: (kicker) => setAttributes({ kicker }),
            }),
            el(RichText, {
              tagName: "h2",
              className: "display-2",
              value: attributes.title,
              allowedFormats: ["core/bold", "core/italic"],
              onChange: (title) => setAttributes({ title }),
            })
          ),
          el(
            "div",
            { className: "statement-copy" },
            el(RichText, {
              tagName: "p",
              value: attributes.text,
              allowedFormats: ["core/bold", "core/italic"],
              onChange: (text) => setAttributes({ text }),
            }),
            el(RichText, {
              tagName: "p",
              value: attributes.secondText,
              allowedFormats: ["core/bold", "core/italic"],
              onChange: (secondText) => setAttributes({ secondText }),
            }),
            el("div", { className: "fact-chips" }, chips.map((chip, index) => el("span", { key: index }, chip))),
            attributes.buttonText ? el("span", { className: "btn btn--outline mt-lg" }, attributes.buttonText) : null
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
