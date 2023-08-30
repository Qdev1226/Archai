(function ($) {
  "use strict";
  var spbnl_search_bar_is_active = false;

  toggleSearch();

  function toggleSearch() {
    $(".navigation-layout-search-button").attr(
      "type",
      spbnl_search_bar_is_active ? "submit" : "button"
    );

    if (spbnl_search_bar_is_active) {
      $(
        ".navigation-layout-search-bar-wrapper .navigation-layout-search-bar-input"
      ).addClass("spbnl-search-bar-active");
      $(".navigation-layout-search-button").off();
      setTimeout(() => {
        $(document).on("click.disable-spbnl-search-bar", function (e) {
          if (
            $(e.target).parents(".navigation-layout-search-bar-wrapper").length
          ) {
            return;
          }
          spbnl_search_bar_is_active = false;
          toggleSearch();
        });
      }, 0);
    } else {
      $(
        ".navigation-layout-search-bar-wrapper .navigation-layout-search-bar-input"
      ).removeClass("spbnl-search-bar-active");
      $(document).off("click.disable-spbnl-search-bar");
      $(".navigation-layout-search-button").click(function (e) {
        e.preventDefault();
        spbnl_search_bar_is_active = true;
        toggleSearch();
      });
    }
  }
})(jQuery);
