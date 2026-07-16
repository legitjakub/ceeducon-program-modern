(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput, MediaUpload, MediaUploadCheck } = blockEditor;
  const { PanelBody, TextControl, Button } = components;
  const { __ } = i18n;

  function updateObjectItem(items, index, key, value) {
    return items.map((item, itemIndex) => (itemIndex === index ? { ...item, [key]: value } : item));
  }

  blocks.registerBlockType("ceeducon/hero", {
    edit({ attributes, setAttributes }) {
      const rows = attributes.eventRows || [];

      return el(
        "section",
        { className: "hero ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Fotografie", "ceeducon-program"), initialOpen: true },
            el(
              MediaUploadCheck,
              {},
              el(MediaUpload, {
                allowedTypes: ["image"],
                value: attributes.imageId,
                onSelect: (media) =>
                  setAttributes({
                    imageId: media.id,
                    imageUrl: media.url,
                    imageAlt: media.alt || attributes.imageAlt,
                  }),
                render: ({ open }) =>
                  el(
                    Button,
                    { variant: "secondary", onClick: open },
                    attributes.imageUrl ? __("Změnit fotografii", "ceeducon-program") : __("Vybrat fotografii", "ceeducon-program")
                  ),
              })
            ),
            attributes.imageUrl
              ? el(
                  Button,
                  {
                    variant: "link",
                    isDestructive: true,
                    onClick: () => setAttributes({ imageId: 0, imageUrl: "" }),
                  },
                  __("Odebrat fotografii", "ceeducon-program")
                )
              : null,
            el(TextControl, {
              label: __("Alternativní text", "ceeducon-program"),
              value: attributes.imageAlt,
              onChange: (imageAlt) => setAttributes({ imageAlt }),
              help: __("Stručně popište, co je na fotografii.", "ceeducon-program"),
            })
          ),
          el(
            PanelBody,
            { title: __("Tlačítka", "ceeducon-program"), initialOpen: false },
            el(TextControl, {
              label: __("Primární tlačítko", "ceeducon-program"),
              value: attributes.primaryText,
              onChange: (primaryText) => setAttributes({ primaryText }),
            }),
            el(URLInput, {
              label: __("Primární URL", "ceeducon-program"),
              value: attributes.primaryUrl,
              onChange: (primaryUrl) => setAttributes({ primaryUrl }),
            }),
            el(TextControl, {
              label: __("Sekundární tlačítko", "ceeducon-program"),
              value: attributes.secondaryText,
              onChange: (secondaryText) => setAttributes({ secondaryText }),
            }),
            el(URLInput, {
              label: __("Sekundární URL", "ceeducon-program"),
              value: attributes.secondaryUrl,
              onChange: (secondaryUrl) => setAttributes({ secondaryUrl }),
            })
          ),
          el(
            PanelBody,
            { title: __("Údaje o konferenci", "ceeducon-program"), initialOpen: false },
            el(TextControl, {
              label: __("Den", "ceeducon-program"),
              value: attributes.eventDay,
              onChange: (eventDay) => setAttributes({ eventDay }),
            }),
            el(TextControl, {
              label: __("Měsíc / rok", "ceeducon-program"),
              value: attributes.eventMonth,
              onChange: (eventMonth) => setAttributes({ eventMonth }),
            }),
            rows.map((row, index) =>
              el(
                "div",
                { className: "ceeducon-editor-row", key: index },
                el(TextControl, {
                  label: __("Popisek", "ceeducon-program"),
                  value: row.label,
                  onChange: (label) => setAttributes({ eventRows: updateObjectItem(rows, index, "label", label) }),
                }),
                el(TextControl, {
                  label: __("Hodnota", "ceeducon-program"),
                  value: row.value,
                  onChange: (value) => setAttributes({ eventRows: updateObjectItem(rows, index, "value", value) }),
                })
              )
            ),
            el(TextControl, {
              label: __("Google Calendar text", "ceeducon-program"),
              value: attributes.googleCalendarText,
              onChange: (googleCalendarText) => setAttributes({ googleCalendarText }),
            }),
            el(URLInput, {
              label: __("Google Calendar URL", "ceeducon-program"),
              value: attributes.googleCalendarUrl,
              onChange: (googleCalendarUrl) => setAttributes({ googleCalendarUrl }),
            }),
            el(TextControl, {
              label: __("Ostatní kalendáře text", "ceeducon-program"),
              value: attributes.calendarText,
              onChange: (calendarText) => setAttributes({ calendarText }),
            }),
            el(URLInput, {
              label: __("ICS soubor URL", "ceeducon-program"),
              value: attributes.calendarUrl,
              onChange: (calendarUrl) => setAttributes({ calendarUrl }),
            })
          )
        ),
        attributes.imageUrl
          ? el("div", { className: "hero-media" }, el("img", { src: attributes.imageUrl, alt: attributes.imageAlt || "" }))
          : null,
        el(
          "div",
          { className: "hero-inner shell" },
          el(
            "div",
            { className: "hero-copy" },
            el(RichText, {
              tagName: "p",
              className: "hero-kicker",
              value: attributes.kicker,
              allowedFormats: [],
              onChange: (kicker) => setAttributes({ kicker }),
              placeholder: __("Kicker", "ceeducon-program"),
            }),
            el(RichText, {
              tagName: "h1",
              value: attributes.title,
              allowedFormats: ["core/italic"],
              onChange: (title) => setAttributes({ title }),
              placeholder: __("Nadpis", "ceeducon-program"),
            }),
            el(RichText, {
              tagName: "p",
              className: "hero-lead",
              value: attributes.lead,
              allowedFormats: [],
              onChange: (lead) => setAttributes({ lead }),
              placeholder: __("Perex", "ceeducon-program"),
            }),
            el(
              "div",
              { className: "hero-actions" },
              el("span", { className: "btn btn--primary" }, attributes.primaryText),
              el("span", { className: "btn btn--ghost" }, attributes.secondaryText)
            )
          )
        ),
        el(
          "div",
          { className: "hero-facts-wrap" },
          el(
            "div",
            { className: "hero-facts shell" },
            el("div", { className: "hero-date" }, el("strong", {}, attributes.eventDay), el("span", { dangerouslySetInnerHTML: { __html: attributes.eventMonth || "" } })),
            rows.map((row, index) =>
              el("div", { className: `hero-fact hero-fact--${index + 1}`, key: index }, el("span", {}, row.label), el("strong", {}, row.value))
            ),
            el(
              "div",
              { className: "hero-calendar" },
              el("span", { className: "hero-calendar__primary" }, attributes.googleCalendarText),
              el("span", { className: "hero-calendar__download" }, attributes.calendarText)
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
