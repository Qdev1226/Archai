/* eslint-disable no-undef */
const gutSliderNotice = jQuery('.gutslider-notice');

jQuery(document).on('click', '#gutslider_notice', function () {
    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'dismiss_gutslider_admin_notice'
        }
    });

    // Hide the notice
    gutSliderNotice.hide();
});
