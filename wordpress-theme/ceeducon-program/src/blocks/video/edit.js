(function (blocks, element, blockEditor, components, i18n) {
  const el = element.createElement;
  const { RichText, InspectorControls, URLInput } = blockEditor;
  const { PanelBody, TextControl } = components;
  const { __ } = i18n;

  blocks.registerBlockType("ceeducon/video", {
    edit({ attributes, setAttributes }) {
      return el(
        "section",
        { className: "section video-section ceeducon-editor-block" },
        el(
          InspectorControls,
          {},
          el(
            PanelBody,
            { title: __("Video", "ceeducon-program"), initialOpen: true },
            el(URLInput, {
              label: __("YouTube URL", "ceeducon-program"),
              value: attributes.videoUrl,
              onChange: (videoUrl) => setAttributes({ videoUrl }),
            }),
            el(TextControl, {
              label: __("Přístupný název videa", "ceeducon-program"),
              value: attributes.videoTitle,
              onChange: (videoTitle) => setAttributes({ videoTitle }),
            }),
            el(TextControl, {
              label: __("Text odkazu", "ceeducon-program"),
              value: attributes.buttonText,
              onChange: (buttonText) => setAttributes({ buttonText }),
            }),
            el(TextControl, {
              label: __("Popisek pod videem", "ceeducon-program"),
              value: attributes.caption,
              onChange: (caption) => setAttributes({ caption }),
            })
          )
        ),
        el(
          "div",
          { className: "shell video-feature" },
          el(
            "div",
            { className: "video-feature-copy" },
            el(RichText, { tagName: "p", className: "kicker", value: attributes.kicker, allowedFormats: [], onChange: (kicker) => setAttributes({ kicker }) }),
            el(RichText, { tagName: "h2", className: "display-2", value: attributes.title, allowedFormats: ["core/bold", "core/italic"], onChange: (title) => setAttributes({ title }) }),
            el(RichText, { tagName: "p", value: attributes.text, allowedFormats: ["core/bold", "core/italic"], onChange: (text) => setAttributes({ text }) }),
            el("span", { className: "video-feature-link" }, attributes.buttonText)
          ),
          el(
            "div",
            { className: "video-feature-stage video-feature-stage--editor" },
            el("div", { className: "video-editor-play", "aria-hidden": "true" }),
            el("strong", {}, attributes.videoTitle || __("YouTube video", "ceeducon-program")),
            el("span", { className: "video-feature-caption" }, attributes.caption)
          )
        )
      );
    },
    save() { return null; },
  });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components, window.wp.i18n);
