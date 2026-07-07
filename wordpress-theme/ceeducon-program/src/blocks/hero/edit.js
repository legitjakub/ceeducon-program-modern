(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl, Button } = components;
  const { __ } = i18n;

  function updateArrayItem(items, index, value) {
    return items.map((item, itemIndex) => (itemIndex === index ? value : item));
  }

  function updateObjectItem(items, index, key, value) {
    return items.map((item, itemIndex) => (itemIndex === index ? { ...item, [key]: value } : item));
  }

  blocks.registerBlockType("ceeducon/hero", {
    edit({ attributes, setAttributes }) {
      const meta = attributes.meta || [];
      const rows = attributes.eventRows || [];
      const stats = attributes.stats || [];

      return el(
        "section",
        { className: "hero ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Tlačítka", "ceeducon-program"), initialOpen: true },
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
            { title: __("Event karta", "ceeducon-program"), initialOpen: false },
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
              label: __("CTA text", "ceeducon-program"),
              value: attributes.eventCtaText,
              onChange: (eventCtaText) => setAttributes({ eventCtaText }),
            }),
            el(URLInput, {
              label: __("CTA URL", "ceeducon-program"),
              value: attributes.eventCtaUrl,
              onChange: (eventCtaUrl) => setAttributes({ eventCtaUrl }),
            }),
            el(TextControl, {
              label: __("Kalendář text", "ceeducon-program"),
              value: attributes.calendarText,
              onChange: (calendarText) => setAttributes({ calendarText }),
            }),
            el(URLInput, {
              label: __("Kalendář URL", "ceeducon-program"),
              value: attributes.calendarUrl,
              onChange: (calendarUrl) => setAttributes({ calendarUrl }),
            })
          ),
          el(
            PanelBody,
            { title: __("Statistiky", "ceeducon-program"), initialOpen: false },
            stats.map((stat, index) =>
              el(
                "div",
                { className: "ceeducon-editor-row", key: index },
                el(TextControl, {
                  label: __("Hodnota", "ceeducon-program"),
                  value: stat.value,
                  onChange: (value) => setAttributes({ stats: updateObjectItem(stats, index, "value", value) }),
                }),
                el(TextControl, {
                  label: __("Popisek", "ceeducon-program"),
                  value: stat.label,
                  onChange: (label) => setAttributes({ stats: updateObjectItem(stats, index, "label", label) }),
                })
              )
            )
          )
        ),
        el("span", { className: "hero-ghost", "aria-hidden": true }, "2026"),
        el("div", { className: "hero-ring", "aria-hidden": true }),
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
              { className: "hero-meta" },
              meta.map((item, index) =>
                el(RichText, {
                  tagName: "span",
                  key: index,
                  value: item,
                  allowedFormats: ["core/bold"],
                  onChange: (value) => setAttributes({ meta: updateArrayItem(meta, index, value) }),
                })
              )
            ),
            el(
              "div",
              { className: "hero-actions" },
              el("span", { className: "btn btn--primary" }, attributes.primaryText),
              el("span", { className: "btn btn--ghost" }, attributes.secondaryText)
            ),
            el(
              "p",
              { className: "countdown-strip" },
              el("strong", {}, "149"),
              el(RichText, {
                tagName: "span",
                value: attributes.countdownText,
                allowedFormats: [],
                onChange: (countdownText) => setAttributes({ countdownText }),
              })
            )
          ),
          el(
            "aside",
            { className: "event-card" },
            el("div", { className: "event-date" }, el("strong", {}, attributes.eventDay), el("span", { dangerouslySetInnerHTML: { __html: attributes.eventMonth || "" } })),
            rows.map((row, index) =>
              el("div", { className: "event-card-row", key: index }, el("span", {}, row.label), el("strong", {}, row.value))
            ),
            el("span", { className: "btn btn--primary" }, attributes.eventCtaText),
            el("span", { className: "btn btn--ghost" }, attributes.calendarText)
          )
        ),
        el(
          "div",
          { className: "hero-stats shell" },
          stats.map((stat, index) => el("div", { key: index }, el("strong", {}, stat.value), el("span", {}, stat.label)))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
