(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl, ToggleControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/cards", {
    edit({ attributes, setAttributes }) {
      const items = attributes.items || [];
      const updateItem = (index, key, value) => setAttributes({ items: items.map((item, itemIndex) => (itemIndex === index ? { ...item, [key]: value } : item)) });
      const addItem = () => setAttributes({ items: [...items, { label: "New", title: "New card", text: "Card text.", url: "" }] });
      const removeItem = (index) => setAttributes({ items: items.filter((_, itemIndex) => itemIndex !== index) });

      return el(
        "section",
        { className: `section ceeducon-editor-block ${attributes.paper ? "section--paper" : ""}` },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení", "ceeducon-program"), initialOpen: true },
            el(ToggleControl, { label: __("Světlé papírové pozadí", "ceeducon-program"), checked: attributes.paper, onChange: (paper) => setAttributes({ paper }) })
          ),
          el(
            PanelBody,
            { title: __("Karty", "ceeducon-program"), initialOpen: false },
            items.map((item, index) =>
              el(
                "div",
                { className: "ceeducon-editor-row", key: index },
                el(TextControl, { label: __("Popisek", "ceeducon-program"), value: item.label, onChange: (value) => updateItem(index, "label", value) }),
                el(TextControl, { label: __("Nadpis", "ceeducon-program"), value: item.title, onChange: (value) => updateItem(index, "title", value) }),
                el(TextControl, { label: __("Text", "ceeducon-program"), value: item.text, onChange: (value) => updateItem(index, "text", value) }),
                el(URLInput, { label: __("URL", "ceeducon-program"), value: item.url, onChange: (value) => updateItem(index, "url", value) }),
                el(Button, { isDestructive: true, variant: "link", onClick: () => removeItem(index) }, __("Odebrat kartu", "ceeducon-program"))
              )
            ),
            el(Button, { variant: "secondary", onClick: addItem }, __("Přidat kartu", "ceeducon-program"))
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
            el(RichText, { tagName: "p", value: attributes.intro, allowedFormats: ["core/bold", "core/italic"], onChange: (intro) => setAttributes({ intro }), placeholder: __("Volitelný úvod", "ceeducon-program") })
          ),
          el(
            "div",
            { className: "tile-grid" },
            items.map((item, index) =>
              el("article", { className: "link-tile", key: index }, el("span", {}, item.label), el("h3", {}, item.title), el("p", {}, item.text))
            )
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
