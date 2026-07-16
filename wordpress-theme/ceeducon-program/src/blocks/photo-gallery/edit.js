(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, MediaUpload, MediaUploadCheck, URLInput } = blockEditor;
  const { PanelBody, TextControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/photo-gallery", {
    edit({ attributes, setAttributes }) {
      const items = attributes.items || [];
      const updateItem = (index, patch) => setAttributes({ items: items.map((item, itemIndex) => itemIndex === index ? { ...item, ...patch } : item) });

      return el(
        "section",
        { className: "section section--media section--navy on-dark photo-section ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Nastavení", "ceeducon-program"), initialOpen: true },
            el(TextControl, { label: __("Text tlačítka", "ceeducon-program"), value: attributes.buttonText, onChange: (buttonText) => setAttributes({ buttonText }) }),
            el(URLInput, { label: __("URL tlačítka", "ceeducon-program"), value: attributes.buttonUrl, onChange: (buttonUrl) => setAttributes({ buttonUrl }) })
          ),
          el(
            PanelBody,
            { title: __("Popisky fotografií", "ceeducon-program"), initialOpen: false },
            items.map((item, index) => el("div", { key: `photo-fields-${index}` }, el(TextControl, { label: `${__("Fotografie", "ceeducon-program")} ${index + 1} – ${__("štítek", "ceeducon-program")}`, value: item.label || "", onChange: (label) => updateItem(index, { label }) }), el(TextControl, { label: __("Alt text", "ceeducon-program"), value: item.imageAlt || "", onChange: (imageAlt) => updateItem(index, { imageAlt }) })))
          )
        ),
        el(
          "div",
          { className: "shell photo-showcase" },
          el("div", { className: "photo-showcase-head" },
            el("div", { className: "media-copy" },
              el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }),
              el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, onChange: (title) => setAttributes({ title }) })
            ),
            el("div", { className: "photo-showcase-copy" },
              el(RichText, { tagName: "p", value: attributes.text, onChange: (text) => setAttributes({ text }) }),
              el("span", { className: "btn btn--ghost" }, attributes.buttonText)
            )
          ),
          el(
            "div",
            { className: "photo-gallery-grid" },
            items.map((item, index) => el(MediaUploadCheck, { key: `photo-${index}` }, el(MediaUpload, {
              value: item.imageId || 0,
              onSelect: (media) => updateItem(index, { imageId: media.id || 0, imageUrl: media.url, imageAlt: media.alt || media.title || "" }),
              allowedTypes: ["image"],
              render: ({ open }) => el(Button, { className: "photo-gallery-item", onClick: open }, item.imageUrl ? el("img", { src: item.imageUrl, alt: item.imageAlt || "", width: 900, height: 900 }) : __("Vybrat fotografii", "ceeducon-program"), item.label ? el("span", {}, item.label) : null),
            })))
          )
        )
      );
    },
    save() { return null; },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
