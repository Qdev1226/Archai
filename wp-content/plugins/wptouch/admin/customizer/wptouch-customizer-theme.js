
// Add the preview code to load more URLs to ensure they load the WPtouch version of blog posts
function wptouchCustomizerThemeReady(){
	var loadMoreLnk = jQuery( 'a.load-more-link, a.infinite-link' );
	var currentRel = loadMoreLnk.attr( 'rel' );
	if ( loadMoreLnk.length ) {
		if( currentRel.indexOf( '?' ) > -1 ) {
			var toAppend = '&wptouch_preview_theme=enabled';
	    } else {
			var toAppend = '?wptouch_preview_theme=enabled';
	    }

		var newRel = currentRel + toAppend;

		jQuery( 'a.load-more-link, a.infinite-link' ).attr( 'rel', newRel );
	}
}

jQuery( document ).ready( function() {
	wptouchCustomizerThemeReady();
});