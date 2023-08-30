/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
  "use strict";
  //Color variants
  business_chat_customizer_variables.COLOR_VARIABLES_VARIANTS.forEach(
    createVariantSettingPreview
  );

  function createVariantSettingPreview(colorSettingVariants) {
    wp.customize(
      colorSettingVariants.REGULAR,
      colorSettingVariants.DARK,
      function (setting_regular, setting_dark) {
        setting_regular.bind(function (color) {
          var darkerColor = adjustColor(color, -30);
          setting_dark.set(darkerColor);
        });
      }
    );
  }

  function adjustColor(color, amount) {
    return (
      "#" +
      color
        .replace(/^#/, "")
        .replace(/../g, (color) =>
          (
            "0" +
            Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(
              16
            )
          ).substr(-2)
        )
    );
  }
})(jQuery);
