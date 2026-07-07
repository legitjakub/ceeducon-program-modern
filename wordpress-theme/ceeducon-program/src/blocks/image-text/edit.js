(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, MediaUpload, MediaUploadCheck, URLInput } = blockEditor;
  const { PanelBody, TextControl, ToggleControl, Button } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/image-text", {
    edit({ attributes, setAttributes }) {
      const onSelectImage = (media) => {
        setAttributes({
          imageId: media.id,
          imageUrl: media.url,
          imageAlt: media.alt || media.title || "",
          secondaryUrl: attributes.secondaryUrl || media.url,
        });
      };

      return el(
        "section",
        { className: `section section--media ceeducon-editor-block ${attributes.paper ? "section--paper" : ""}` },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Rozvržení", "ceeducon-program"), initialOpen: true },
            el(ToggleControl, {
              label: __("Prohodit obrázek a text", "ceeducon-program"),
              checked: attributes.reverse,
              onChange: (reverse) => setAttributes({ reverse }),
            }),
            el(ToggleControl, {
              label: __("Světlé papírové pozadí", "ceeducon-program"),
              checked: attributes.paper,
              onChange: (paper) => setAttributes({ paper }),
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
            { title: __("Obrázek", "ceeducon-program"), initialOpen: false },
            el(TextControl, { label: __("Alt text", "ceeducon-program"), value: attributes.imageAlt, onChange: (imageAlt) => setAttributes({ imageAlt }) }),
            el(TextControl, { label: __("Štítek na obrázku", "ceeducon-program"), value: attributes.imageLabel, onChange: (imageLabel) => setAttributes({ imageLabel }) })
          )
        ),
        el(
          "div",
          { className: `shell media-showcase ${attributes.reverse ? "is-reversed" : ""}` },
          el(
            "div",
            { className: "media-copy" },
            el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }),
            el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) }),
            el(RichText, { tagName: "p", value: attributes.text, allowedFormats: ["core/bold", "core/italic"], onChange: (text) => setAttributes({ text }) }),
            el("div", { className: "media-actions" }, el("span", { className: "btn btn--primary" }, attributes.primaryText), attributes.secondaryText ? el("span", { className: "btn btn--outline" }, attributes.secondaryText) : null)
          ),
          el(
            "div",
            { className: "media-mosaic media-mosaic--single" },
            el(
              MediaUploadCheck,
              {},
              el(MediaUpload, {
                onSelect: onSelectImage,
                allowedTypes: ["image"],
                value: attributes.imageId,
                render: ({ open }) =>
                  el(
                    Button,
                    { className: "media-tile media-tile--large", onClick: open },
                    attributes.imageUrl ? el("img", { src: attributes.imageUrl, alt: attributes.imageAlt || "" }) : __("Vybrat obrázek", "ceeducon-program"),
                    attributes.imageLabel ? el("span", {}, attributes.imageLabel) : null
                  ),
              })
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
