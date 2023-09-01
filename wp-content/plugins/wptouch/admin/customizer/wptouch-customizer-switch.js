//TODO: localize!
// Setup Customizer Switch between mobile and desktop themes
(function( exports, $ ){
	var current = jQuery.cookie( 'wptouch_customizer_mode' ) || 'desktop';

	jQuery( '#customize-info .accordion-section-title' ).append( '<div><button style="margin-top: 10px;" id="switch_to" class="button">' + WPtouchCustomizerSwitch.mobile_switch + '</button></div>' );

	if ( current == 'mobile' ) { var next = 'desktop'; } else { var next = 'mobile'; }

	if ( next == 'mobile' ) {
		jQuery( '#switch_to' ).text( WPtouchCustomizerSwitch.mobile_switch );
		jQuery( '#customize-theme-controls' ).css( 'padding-top', '0px' );
	} else {
		jQuery( '#switch_to' ).text( WPtouchCustomizerSwitch.desktop_switch );
		jQuery( '#customize-theme-controls' ).css( 'padding-top', '15px' );
	}

	jQuery( '#switch_to' ).click( function( e ) {
		e.preventDefault();
		e.stopImmediatePropagation();
		jQuery.cookie( 'wptouch_customizer_mode', next, { expires: false, path: '/' } );
		window.location.reload();
	});

})( wp, jQuery )