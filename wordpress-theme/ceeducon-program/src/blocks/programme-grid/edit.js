(function (blocks, element, blockEditor, i18n) {
  const el = element.createElement;
  const { RichText } = blockEditor;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/programme-grid", {
    edit({ attributes, setAttributes }) {
      return el(
        "section",
        { className: "schedule-section ceeducon-editor-block" },
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
          el("div", { className: "notice-card notice-card--sky" }, el("span", {}, __("Dynamic block", "ceeducon-program")), el("h3", {}, __("Interactive programme renders on the frontend.", "ceeducon-program")), el("p", {}, __("Programme data is loaded from data/program.json or the CEEDUCON Content JSON field.", "ceeducon-program")))
        )
      );
    },
    save() {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.i18n);
