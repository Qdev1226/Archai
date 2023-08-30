/**
 * File customizer-preview.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
  "use strict";
  // Site title and description.
  wp.customize("blogname", function (setting) {
    setting.bind(function (text) {
      $(".site-title").text(text);
    });
  });
  wp.customize("blogdescription", function (setting) {
    setting.bind(function (text) {
      $(".site-description").text(text);
    });
  });

  // Range preview
  business_chat_customizer_preview_variables.LAYOUT_VARIABLES.RANGE.forEach(
    createRangeSettingPreview
  );

  function createRangeSettingPreview(rangeSetting) {
    wp.customize(rangeSetting, function (setting) {
      setting.bind(function (value) {
        $(":root").css(rangeSetting, value + "px");
      });
    });
  }

  // Color preview
  business_chat_customizer_preview_variables.COLOR_VARIABLES.forEach(
    createSettingPreview
  );
  function createSettingPreview(colorSetting) {
    wp.customize(colorSetting, function (setting) {
      setting.bind(function (color) {
        $(":root").css(colorSetting, color);
      });
    });
  }

  //Color preview variants
  business_chat_customizer_preview_variables.COLOR_VARIABLES_VARIANTS.forEach(
    createVariantSettingPreview
  );

  function createVariantSettingPreview(colorSettingVariants) {
    wp.customize(
      colorSettingVariants.REGULAR,
      colorSettingVariants.DARK,
      function (setting_regular, setting_dark) {
        setting_regular.bind(function (color) {
          $(":root").css(colorSettingVariants.REGULAR, color);
          var darkerColor = adjustColor(color, -30);
          setting_dark.set(darkerColor);
          $(":root").css(colorSettingVariants.DARK, darkerColor);
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

  // Header text color / logo text color.
  wp.customize("header_textcolor", function (setting) {
    setting.bind(function (color) {
      $(".logofont.site-title").css({
        color: color,
      });
    });
  });
})(jQuery);
