(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls } = blockEditor;
  const { PanelBody, TextControl } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/page-hero", {
    edit({ attributes, setAttributes }) {
      return el(
        "section",
        { className: "page-hero ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení hero sekce", "ceeducon-program"), initialOpen: true },
            el(TextControl, { label: __("Drobečková navigace", "ceeducon-program"), value: attributes.crumb, onChange: (crumb) => setAttributes({ crumb }) }),
            el(TextControl, { label: __("Popisek karty", "ceeducon-program"), value: attributes.cardLabel, onChange: (cardLabel) => setAttributes({ cardLabel }) }),
            el(TextControl, { label: __("Nadpis karty", "ceeducon-program"), value: attributes.cardTitle, onChange: (cardTitle) => setAttributes({ cardTitle }) }),
            el(TextControl, { label: __("Text karty", "ceeducon-program"), value: attributes.cardText, onChange: (cardText) => setAttributes({ cardText }) })
          )
        ),
        el(
          "div",
          { className: "shell page-hero-grid" },
          el(
            "div",
            {},
            el("p", { className: "page-crumbs" }, el("span", {}, __("Home", "ceeducon-program")), el("span", {}, "/"), el("em", {}, attributes.crumb)),
            el(RichText, { tagName: "h1", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) }),
            el(RichText, { tagName: "p", className: "page-hero-note", value: attributes.note, allowedFormats: ["core/bold", "core/italic"], onChange: (note) => setAttributes({ note }) })
          ),
          el("div", { className: "page-hero-card" }, el("span", {}, attributes.cardLabel), el("strong", {}, attributes.cardTitle), el("p", {}, attributes.cardText))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
