jQuery(document).ready(function ($) {
  "use strict";
  var openMenuButton = $(".menu-offconvas-mobile-only .toggle-mobile-menu");
  var closeMenuButton = $("#smobile-menu .smenu-hide");
  openMenuButton.click(function (e) {
    e.preventDefault(); // don't grab focus
    openMenuButton.toggleClass("menu-is-active");
    if (openMenuButton.hasClass("menu-is-active")) {
      // open the menu
      closeMenuButton.first().focus();
      $(document).off("keydown");
      $(document).on("keydown", function (e) {
        if (e.keyCode === 27) {
          // escape to close popout menu
          e.preventDefault();
          openMenuButton.trigger("click");
        }
      });

      closeMenuButton.off("keydown");
      closeMenuButton.on("keydown", function (e) {
        if (e.keyCode === 13) {
          e.preventDefault();
          // enter on closemenubtn to close menu
          openMenuButton.trigger("click");
        }
        if (e.keyCode === 9 && e.shiftKey) {
          e.preventDefault();
          // cycle to last element if shift-tabbing first element
          $("#smobile-menu #primary-menu li a").last().focus();
        }
      });

      $("#smobile-menu #primary-menu li a").last().off("keydown");
      $("#smobile-menu #primary-menu li a")
        .last()
        .on("keydown", function (e) {
          if (e.keyCode === 9 && !e.shiftKey) {
            e.preventDefault();
            //cycle back to start if tabbing last element
            closeMenuButton.focus();
          }
        });
    } else {
      // close the menu
      $(document).off("keydown");
      closeMenuButton.off("keydown");
      $("#smobile-menu #primary-menu li a").last().off("keydown");
      openMenuButton.first().focus();
    }
  });
});
