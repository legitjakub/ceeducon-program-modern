(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls } = blockEditor;
  const { PanelBody, TextControl, TextareaControl, ToggleControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/faq", {
    edit({ attributes, setAttributes }) {
      const items = attributes.items || [];
      const updateItem = (index, key, value) => setAttributes({ items: items.map((item, itemIndex) => (itemIndex === index ? { ...item, [key]: value } : item)) });
      const addItem = () => setAttributes({ items: [...items, { question: "New question", answer: "Answer text." }] });
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
            { title: __("Otázky", "ceeducon-program"), initialOpen: false },
            items.map((item, index) =>
              el(
                "div",
                { className: "ceeducon-editor-row", key: index },
                el(TextControl, { label: __("Otázka", "ceeducon-program"), value: item.question, onChange: (value) => updateItem(index, "question", value) }),
                el(TextareaControl, { label: __("Odpověď", "ceeducon-program"), value: item.answer, onChange: (value) => updateItem(index, "answer", value) }),
                el(Button, { isDestructive: true, variant: "link", onClick: () => removeItem(index) }, __("Odebrat otázku", "ceeducon-program"))
              )
            ),
            el(Button, { variant: "secondary", onClick: addItem }, __("Přidat otázku", "ceeducon-program"))
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
          el("div", { className: "faq-list" }, items.map((item, index) => el("details", { key: index, open: index === 0 }, el("summary", {}, item.question), el("p", {}, item.answer))))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
