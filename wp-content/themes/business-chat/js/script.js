"use strict";
jQuery.fn.exists = function (callback) {
  var args = [].slice.call(arguments, 1);
  if (this.length) {
    callback.call(this, args);
  }
  return this;
};

(function ($) {
  "use strict";
  var khmerScript = {
    initAll: function () {
      this.menuShowHide();
      this.searchOpen();
    },

    menuShowHide: function () {
      var $primary_menu = $("#primary-site-navigation");
      var $secondary_menu = $("#secondary-site-navigation");

      var $first_menu = "";
      var $second_menu = "";

      if ($primary_menu.length == 0 && $secondary_menu.length == 0) {
        return;
      } else {
        if ($primary_menu.length) {
          $first_menu = $primary_menu;
        }
      }

      var menu_wrapper = $first_menu.clone().appendTo("#smobile-menu");

      if ($secondary_menu.length) {
        if ($("ul.smenu").length) {
          var $second_menu = $secondary_menu
            .find("ul.smenu")
            .clone()
            .insertAfter("#smobile-menu .primary-menu .pmenu");
        } else {
          var $second_menu1 = $secondary_menu
            .find(".smenu > ul")
            .clone()
            .insertAfter("#smobile-menu .primary-menu .pmenu");
        }
      }

      $(
        "#smobile-menu .header-content-container.navigation-layout-large"
      ).remove();

      $(".toggle-mobile-menu").click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        if (!$("body").hasClass("mobile-menu-active")) {
          $("#smobile-menu").show().addClass("show");
          $("body").toggleClass("mobile-menu-active");
        } else {
          khmerScript.callFunctionHideMenu();
        }
      });

      $(
        '<span class="sub-arrow"><i class="fa fa-angle-down"></i></span>'
      ).insertAfter(
        $(".menu-item-has-children > a, .page_item_has_children > a")
      );

      $(
        ".menu-item-has-children .sub-arrow, .page_item_has_children .sub-arrow"
      ).click(function (e) {
        e.preventDefault();
        e.stopPropagation();

        var subMenuOpen = $(this).hasClass("sub-menu-open");

        if (subMenuOpen) {
          $(this).removeClass("sub-menu-open");
          $(this)
            .find("i")
            .removeClass("fa-angle-up")
            .addClass("fa-angle-down");
          $(this)
            .next("ul.children, ul.sub-menu")
            .removeClass("active")
            .slideUp();
        } else {
          $(this).addClass("sub-menu-open");
          $(this)
            .find("i")
            .removeClass("fa-angle-down")
            .addClass("fa-angle-up");
          $(this)
            .next("ul.children, ul.sub-menu")
            .addClass("active")
            .slideDown();
        }
      });

      if ($("#wpadminbar").length) {
        $("#smobile-menu").addClass("wpadminbar-active");
      }
    },

    searchOpen: function () {
      $(".btn-search").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(".search-style-one").addClass("open");
        $(".overlay").find("input").focus();
      });

      $(".overlay-close").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(".search-style-one").removeClass("open");
      });

      $(document).on("click", function (e) {
        $(".search-style-one").removeClass("open");
      });

      $(".search-style-one").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
      });
    },
    callFunctionHideMenu: function () {
      $("#smobile-menu").removeClass("show");
      jQuery("body").removeClass("mobile-menu-active");
      jQuery("html").removeClass("noscroll");
      jQuery("#mobile-menu-overlay").fadeOut();
    },
  };

  $(document)
    .ready(function (e) {
      khmerScript.initAll();
    })
    .on("click", function (event) {
      khmerScript.callFunctionHideMenu();
    });

  // Initialize gototop for carousel
  if ($("#goTop").length > 0) {
    // Hide the toTop button when the page loads.
    $("#goTop").css("display", "none");

    // This function runs every time the user scrolls the page.
    $(window).scroll(function () {
      // Check weather the user has scrolled down (if "scrollTop()"" is more than 0)
      if ($(window).scrollTop() > 0) {
        // If it's more than or equal to 0, show the toTop button.
        $("#goTop").fadeIn("slow");
      } else {
        // If it's less than 0 (at the top), hide the toTop button.
        $("#goTop").fadeOut("slow");
      }
    });

    // When the user clicks the toTop button, we want the page to scroll to the top.
    jQuery("#goTop").click(function (event) {
      // Disable the default behaviour when a user clicks an empty anchor link.
      // (The page jumps to the top instead of // animating)
      event.preventDefault();
      // Animate the scrolling motion.
      jQuery("html, body").animate(
        {
          scrollTop: 0,
        },
        "slow"
      );
    });
  }
})(jQuery);
