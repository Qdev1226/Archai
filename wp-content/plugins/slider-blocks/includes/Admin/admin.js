/* eslint-disable no-undef */
(function ($) {
    // tabs title
    const tabTitles = $('.tab__title');
    // tabs content
    const tabContents = $('.tab__panel');

    // open each tab content on click tab title
    tabTitles.on('click', function () {
        const tabId = $(this).attr('data-tab');
        const tab = $(`#${tabId}`);
        tabTitles.removeClass('active');
        $(this).addClass('active');
        tabContents.removeClass('active');
        tab.addClass('active');
    });
})(jQuery);
