(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls } = blockEditor;
  const { PanelBody, TextControl, TextareaControl, ToggleControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/testimonials", {
    edit({ attributes, setAttributes }) {
      const items = attributes.items || [];
      const updateItem = (index, key, value) => setAttributes({ items: items.map((item, itemIndex) => (itemIndex === index ? { ...item, [key]: value } : item)) });
      const addItem = () => setAttributes({ items: [...items, { quote: "New quote.", name: "Name", role: "Role" }] });
      const removeItem = (index) => setAttributes({ items: items.filter((_, itemIndex) => itemIndex !== index) });

      return el(
        "section",
        { className: `section ceeducon-editor-block ${attributes.dark ? "section--navy on-dark" : "section--paper"}` },
        el(
          InspectorControls,
          {},
          el(PanelBody, { title: __("Nastavení", "ceeducon-program"), initialOpen: true }, el(ToggleControl, { label: __("Tmavá brand sekce", "ceeducon-program"), checked: attributes.dark, onChange: (dark) => setAttributes({ dark }) })),
          el(
            PanelBody,
            { title: __("Reference", "ceeducon-program"), initialOpen: false },
            items.map((item, index) =>
              el(
                "div",
                { className: "ceeducon-editor-row", key: index },
                el(TextareaControl, { label: __("Citace", "ceeducon-program"), value: item.quote, onChange: (value) => updateItem(index, "quote", value) }),
                el(TextControl, { label: __("Jméno", "ceeducon-program"), value: item.name, onChange: (value) => updateItem(index, "name", value) }),
                el(TextControl, { label: __("Role", "ceeducon-program"), value: item.role, onChange: (value) => updateItem(index, "role", value) }),
                el(Button, { isDestructive: true, variant: "link", onClick: () => removeItem(index) }, __("Odebrat referenci", "ceeducon-program"))
              )
            ),
            el(Button, { variant: "secondary", onClick: addItem }, __("Přidat referenci", "ceeducon-program"))
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
            { className: "testimonial-grid" },
            items.map((item, index) =>
              el("figure", { className: "testimonial-card", key: index }, el("blockquote", {}, item.quote), el("figcaption", {}, el("strong", {}, item.name), el("span", {}, item.role)))
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
