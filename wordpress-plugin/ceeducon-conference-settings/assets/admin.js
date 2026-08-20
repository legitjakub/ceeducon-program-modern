(function ($) {
  "use strict";

  $(document).on("click", "[data-media-select]", function () {
    const field = $(this).closest("[data-media-field]");
    const frame = wp.media({ title: "Choose conference image", multiple: false, library: { type: "image" } });

    frame.on("select", function () {
      const image = frame.state().get("selection").first().toJSON();
      const preview = image.sizes && image.sizes.medium ? image.sizes.medium.url : image.url;
      field.find("[data-media-id]").val(image.id);
      field.find("[data-media-preview]").html($("<img>", { src: preview, alt: "" }));
      field.find("[data-media-remove]").prop("hidden", false);
    });

    frame.open();
  });

  $(document).on("click", "[data-media-remove]", function () {
    const field = $(this).closest("[data-media-field]");
    field.find("[data-media-id]").val("0");
    field.find("[data-media-preview]").empty();
    $(this).prop("hidden", true);
  });
})(jQuery);
