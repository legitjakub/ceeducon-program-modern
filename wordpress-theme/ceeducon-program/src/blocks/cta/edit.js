(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl, ToggleControl } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/cta", {
    edit({ attributes, setAttributes }) {
      return el(
        "section",
        { className: `section ceeducon-editor-block ${attributes.dark ? "section--navy on-dark" : "section--paper"}` },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení", "ceeducon-program"), initialOpen: true },
            el(ToggleControl, {
              label: __("Tmavá brand sekce", "ceeducon-program"),
              checked: attributes.dark,
              onChange: (dark) => setAttributes({ dark }),
            })
          ),
          el(
            PanelBody,
            { title: __("Tlačítka", "ceeducon-program"), initialOpen: false },
            el(TextControl, { label: __("Primární text", "ceeducon-program"), value: attributes.primaryText, onChange: (primaryText) => setAttributes({ primaryText }) }),
            el(URLInput, { label: __("Primární URL", "ceeducon-program"), value: attributes.primaryUrl, onChange: (primaryUrl) => setAttributes({ primaryUrl }) }),
            el(TextControl, { label: __("Sekundární text", "ceeducon-program"), value: attributes.secondaryText, onChange: (secondaryText) => setAttributes({ secondaryText }) }),
            el(URLInput, { label: __("Sekundární URL", "ceeducon-program"), value: attributes.secondaryUrl, onChange: (secondaryUrl) => setAttributes({ secondaryUrl }) })
          ),
          el(
            PanelBody,
            { title: __("Informační karta", "ceeducon-program"), initialOpen: false },
            el(TextControl, { label: __("Popisek", "ceeducon-program"), value: attributes.noteLabel, onChange: (noteLabel) => setAttributes({ noteLabel }) }),
            el(TextControl, { label: __("Nadpis", "ceeducon-program"), value: attributes.noteTitle, onChange: (noteTitle) => setAttributes({ noteTitle }) }),
            el(TextControl, { label: __("Text", "ceeducon-program"), value: attributes.noteText, onChange: (noteText) => setAttributes({ noteText }) })
          )
        ),
        el(
          "div",
          { className: "shell contact-band" },
          el(
            "div",
            {},
            el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }),
            el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) }),
            el(RichText, { tagName: "p", className: "lead", value: attributes.text, allowedFormats: ["core/bold", "core/italic"], onChange: (text) => setAttributes({ text }) }),
            el("div", { className: "contact-actions" }, el("span", { className: "btn btn--primary" }, attributes.primaryText), el("span", { className: "btn btn--outline" }, attributes.secondaryText))
          ),
          el("div", { className: "notice-card notice-card--sky" }, el("span", {}, attributes.noteLabel), el("h3", {}, attributes.noteTitle), el("p", {}, attributes.noteText))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
