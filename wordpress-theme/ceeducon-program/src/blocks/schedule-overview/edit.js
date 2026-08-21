(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl, ToggleControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/schedule-overview", {
    edit({ attributes, setAttributes }) {
      const items = Array.isArray(attributes.items) ? attributes.items : [];
      const setItem = (index, patch) =>
        setAttributes({ items: items.map((item, i) => (i === index ? { ...item, ...patch } : item)) });

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
          ),
          el(
            PanelBody,
            { title: __("Karty dnů", "ceeducon-program"), initialOpen: false },
            ...items.map((item, index) =>
              el(
                "div",
                { key: index, style: { marginBottom: "18px" } },
                el(TextControl, { label: __("Popisek", "ceeducon-program"), value: item.label || "", onChange: (label) => setItem(index, { label }) }),
                el(TextControl, { label: __("Nadpis", "ceeducon-program"), value: item.title || "", onChange: (title) => setItem(index, { title }) }),
                el(TextControl, { label: __("Text", "ceeducon-program"), value: item.text || "", onChange: (text) => setItem(index, { text }) }),
                el(
                  Button,
                  { isDestructive: true, variant: "secondary", onClick: () => setAttributes({ items: items.filter((_, i) => i !== index) }) },
                  __("Odebrat kartu", "ceeducon-program")
                )
              )
            ),
            el(
              Button,
              { variant: "primary", onClick: () => setAttributes({ items: [...items, { label: "", title: "", text: "" }] }) },
              __("Přidat kartu", "ceeducon-program")
            )
          )
        ),
        el(
          "div",
          { className: "shell" },
          el(
            "div",
            { className: "section-head" },
            el(
              "div",
              {},
              el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }),
              el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) })
            ),
            el(RichText, { tagName: "p", value: attributes.intro, allowedFormats: ["core/bold", "core/italic"], onChange: (intro) => setAttributes({ intro }) })
          ),
          el(
            "div",
            { className: "day-cards" },
            ...items.map((item, index) =>
              el("article", { key: index }, el("span", {}, item.label || ""), el("h3", {}, item.title || ""), el("p", {}, item.text || ""))
            )
          ),
          attributes.buttonText ? el("div", { className: "mt-lg" }, el("span", { className: "btn btn--dark" }, attributes.buttonText)) : null
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
