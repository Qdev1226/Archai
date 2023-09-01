jQuery( document ).ready( function(){
	jQuery( '.wptouch-color' ).each( function() {
		var desktopPalette = jQuery.parseJSON( jQuery( this ).attr( 'data-desktop-palette' ) );
		jQuery( this ).wpColorPicker(
			{
				palettes: desktopPalette,
				change: wptouchAdminDebounce( function(){
						wptouchTriggerSave();
					}, 250 )
			}
		);
	});
} );