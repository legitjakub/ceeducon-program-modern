(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl, ToggleControl } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/venue", {
    edit({ attributes, setAttributes }) {
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
              label: __("Světlé pozadí", "ceeducon-program"),
              checked: attributes.paper,
              onChange: (paper) => setAttributes({ paper }),
            })
          ),
          el(
            PanelBody,
            { title: __("Tlačítko", "ceeducon-program"), initialOpen: false },
            el(TextControl, { label: __("Text", "ceeducon-program"), value: attributes.buttonText, onChange: (buttonText) => setAttributes({ buttonText }) }),
            el(URLInput, { label: __("URL", "ceeducon-program"), value: attributes.buttonUrl, onChange: (buttonUrl) => setAttributes({ buttonUrl }) })
          )
        ),
        el(
          "div",
          { className: "shell feature-split" },
          el(
            "div",
            {},
            el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }),
            el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) }),
            el(RichText, { tagName: "p", value: attributes.text, allowedFormats: ["core/bold", "core/italic"], onChange: (text) => setAttributes({ text }) }),
            el("span", { className: "btn btn--outline" }, attributes.buttonText)
          ),
          el(
            "div",
            { className: "feature-panel" },
            el(RichText, { tagName: "span", value: attributes.panelLabel, allowedFormats: [], onChange: (panelLabel) => setAttributes({ panelLabel }) }),
            el(RichText, { tagName: "strong", value: attributes.panelTitle, allowedFormats: [], onChange: (panelTitle) => setAttributes({ panelTitle }) }),
            el(RichText, { tagName: "p", value: attributes.panelText, allowedFormats: ["core/bold", "core/italic"], onChange: (panelText) => setAttributes({ panelText }) })
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
