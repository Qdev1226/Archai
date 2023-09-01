function wptouchDoPreview() {
	jQuery( '#searchform' ).prepend( '<input type="hidden" name="wptouch_preview_theme" value="enabled">' );

	jQuery( 'a:not(.load-more-link)' ).each( function() {
		var linkLocation = jQuery( this ).attr( 'href' );
		if ( linkLocation && linkLocation.charAt( 0 ) != '#' ) {
			if ( linkLocation.search( "\\?" ) == -1 ) {
				linkLocation = linkLocation + '?wptouch_preview_theme=enabled';
			} else {
				linkLocation = linkLocation + '&wptouch_preview_theme=enabled';
			}
			jQuery( this ).attr( 'href', linkLocation ).attr( 'rel', 'nofollow' );
		}
	});
}

jQuery( document ).ready( function() { wptouchDoPreview(); });