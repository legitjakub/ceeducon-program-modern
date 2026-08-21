(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, MediaUpload, MediaUploadCheck } = blockEditor;
  const { PanelBody, TextControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/partners", {
    edit({ attributes, setAttributes }) {
      return el(
        "section",
        { className: "section ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Kontakt", "ceeducon-program"), initialOpen: true },
            el(TextControl, { label: __("E-mail", "ceeducon-program"), value: attributes.email, onChange: (email) => setAttributes({ email }) })
          ),
          el(
            PanelBody,
            { title: __("Pruh log", "ceeducon-program"), initialOpen: false },
            el(MediaUploadCheck, {}, el(MediaUpload, {
              allowedTypes: ["image"],
              value: attributes.logoId,
              onSelect: (media) => setAttributes({ logoId: media.id, logoUrl: media.url, logoAlt: media.alt || attributes.logoAlt }),
              render: ({ open }) => el(Button, { variant: "secondary", onClick: open }, __("Vybrat obrázek", "ceeducon-program")),
            })),
            el(TextControl, { label: __("Alternativní text", "ceeducon-program"), value: attributes.logoAlt, onChange: (logoAlt) => setAttributes({ logoAlt }) }),
            attributes.logoId
              ? el(Button, { isDestructive: true, variant: "tertiary", onClick: () => setAttributes({ logoId: 0 }) }, __("Odebrat obrázek", "ceeducon-program"))
              : null
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
            el(RichText, { tagName: "p", value: attributes.text, allowedFormats: ["core/bold", "core/italic"], onChange: (text) => setAttributes({ text }) })
          ),
          el(
            "div",
            { className: "partners-card" },
            el(RichText, { tagName: "span", value: attributes.cardLabel, allowedFormats: [], onChange: (cardLabel) => setAttributes({ cardLabel }) }),
            el(RichText, { tagName: "strong", value: attributes.cardTitle, allowedFormats: [], onChange: (cardTitle) => setAttributes({ cardTitle }) }),
            el(RichText, { tagName: "span", value: attributes.partnersLabel, allowedFormats: [], onChange: (partnersLabel) => setAttributes({ partnersLabel }) }),
            el(RichText, { tagName: "p", value: attributes.partnersText, allowedFormats: ["core/bold", "core/italic"], onChange: (partnersText) => setAttributes({ partnersText }) })
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
