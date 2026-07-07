(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput, MediaUpload, MediaUploadCheck } = blockEditor;
  const { PanelBody, TextControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/contact", {
    edit({ attributes, setAttributes }) {
      const mailUrl = attributes.email ? `mailto:${attributes.email}` : "#";

      return el(
        "section",
        { className: "section ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Kontakt", "ceeducon-program"), initialOpen: true },
            el(TextControl, { label: __("E-mail", "ceeducon-program"), value: attributes.email, onChange: (email) => setAttributes({ email }) }),
            el(TextControl, { label: __("Telefon", "ceeducon-program"), value: attributes.phone, onChange: (phone) => setAttributes({ phone }) }),
            el(TextControl, { label: __("Text tlačítka", "ceeducon-program"), value: attributes.buttonText, onChange: (buttonText) => setAttributes({ buttonText }) }),
            el(TextControl, { label: __("Sekundární tlačítko", "ceeducon-program"), value: attributes.secondaryText, onChange: (secondaryText) => setAttributes({ secondaryText }) }),
            el(URLInput, { label: __("Sekundární URL", "ceeducon-program"), value: attributes.secondaryUrl, onChange: (secondaryUrl) => setAttributes({ secondaryUrl }) })
          ),
          el(
            PanelBody,
            { title: __("Karta organizátora", "ceeducon-program"), initialOpen: false },
            el(TextControl, { label: __("Popisek", "ceeducon-program"), value: attributes.cardLabel, onChange: (cardLabel) => setAttributes({ cardLabel }) }),
            el(TextControl, { label: __("Název", "ceeducon-program"), value: attributes.cardTitle, onChange: (cardTitle) => setAttributes({ cardTitle }) }),
            el(TextControl, { label: __("Popisek partnerů", "ceeducon-program"), value: attributes.partnersLabel, onChange: (partnersLabel) => setAttributes({ partnersLabel }) }),
            el(TextControl, { label: __("Partneři", "ceeducon-program"), value: attributes.partnersText, onChange: (partnersText) => setAttributes({ partnersText }) }),
            el(MediaUploadCheck, {}, el(MediaUpload, {
              onSelect: (media) => setAttributes({ logoId: media.id, logoUrl: media.url, logoAlt: media.alt || media.title || "" }),
              allowedTypes: ["image"],
              value: attributes.logoId,
              render: ({ open }) => el(Button, { variant: "secondary", onClick: open }, attributes.logoUrl ? __("Změnit logo", "ceeducon-program") : __("Vybrat logo", "ceeducon-program")),
            })),
            el(TextControl, { label: __("Alt text loga", "ceeducon-program"), value: attributes.logoAlt, onChange: (logoAlt) => setAttributes({ logoAlt }) })
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
            el("div", { className: "contact-actions" }, el("span", { className: "btn btn--primary" }, attributes.buttonText), el("span", { className: "btn btn--outline" }, attributes.secondaryText)),
            el("p", { className: "ceeducon-editor-contact-preview" }, mailUrl, attributes.phone ? ` · ${attributes.phone}` : "")
          ),
          el(
            "div",
            { className: "partners-card" },
            attributes.logoUrl ? el("img", { src: attributes.logoUrl, alt: attributes.logoAlt || "" }) : null,
            el("span", {}, attributes.cardLabel),
            el("strong", {}, attributes.cardTitle),
            el("span", {}, attributes.partnersLabel),
            el("p", {}, attributes.partnersText)
          )
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
