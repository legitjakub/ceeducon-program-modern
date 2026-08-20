(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls } = blockEditor;
  const { PanelBody, TextControl, TextareaControl, ToggleControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/themes", {
    edit({ attributes, setAttributes }) {
      const items = attributes.items || [];
      const updateItem = (index, key, value) => setAttributes({
        items: items.map((item, itemIndex) => (itemIndex === index ? { ...item, [key]: value } : item)),
      });
      const addItem = () => setAttributes({
        items: [...items, { number: String(items.length + 1).padStart(2, "0"), title: "New theme", text: "", question: "", details: "" }],
      });
      const removeItem = (index) => setAttributes({ items: items.filter((_, itemIndex) => itemIndex !== index) });

      return el(
        "section",
        { className: `section ceeducon-editor-block ${attributes.dark ? "section--navy on-dark" : "section--tint theme-section-light"}` },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení", "ceeducon-program"), initialOpen: true },
            el(ToggleControl, {
              label: __("Tmavé pozadí", "ceeducon-program"),
              checked: Boolean(attributes.dark),
              onChange: (dark) => setAttributes({ dark }),
            })
          ),
          el(
            PanelBody,
            { title: __("Témata", "ceeducon-program"), initialOpen: false },
            items.map((item, index) => el(
              "div",
              { className: "ceeducon-editor-row", key: index },
              el(TextControl, { label: __("Číslo", "ceeducon-program"), value: item.number || "", onChange: (value) => updateItem(index, "number", value) }),
              el(TextControl, { label: __("Název", "ceeducon-program"), value: item.title || "", onChange: (value) => updateItem(index, "title", value) }),
              el(TextareaControl, { label: __("Krátký popis", "ceeducon-program"), value: item.text || "", onChange: (value) => updateItem(index, "text", value) }),
              el(TextareaControl, { label: __("Hlavní otázka", "ceeducon-program"), value: item.question || "", onChange: (value) => updateItem(index, "question", value) }),
              el(TextareaControl, { label: __("Rozšířený popis", "ceeducon-program"), value: item.details || "", onChange: (value) => updateItem(index, "details", value) }),
              el(Button, { isDestructive: true, variant: "link", onClick: () => removeItem(index) }, __("Odebrat téma", "ceeducon-program"))
            )),
            el(Button, { variant: "secondary", onClick: addItem }, __("Přidat téma", "ceeducon-program"))
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
            { className: "theme-grid" },
            items.map((item, index) => el(
              "article",
              { className: `theme-card theme-card--${["sky", "orange", "white", "navy"][index % 4]}`, key: index },
              el("span", {}, item.number),
              el("h3", {}, item.title),
              el("p", {}, item.text),
              item.question || item.details
                ? el("div", { className: "theme-card-more" }, item.question ? el("p", { className: "theme-card-question" }, item.question) : null, item.details ? el("p", {}, item.details) : null)
                : null
            ))
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
