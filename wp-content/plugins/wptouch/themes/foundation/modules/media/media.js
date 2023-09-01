/* WPtouch Foundation Media Handling Code */

function handleVids(){
	// Add dynamic automatic video resizing via fitVids (if enabled)
	if ( jQuery.isFunction( jQuery.fn.fitVids ) ) {
		jQuery( '#content' ).fitVids();
	}

	// If we have html5 videos, add controls for them if they're not specified, CSS will style them appropriately
	if ( jQuery( '#content video' ).length ) {
		jQuery( '#content video' ).attr( 'controls', 'controls' );
	}
}

// Fixes all HTML5 videos from trigging when menus are overtop
function listenForMenuOpenHideVideos(){
	jQuery( '.show-hide-toggle' ).on( 'click', function(){
		setTimeout( function(){
			var selectors = jQuery( '.css-videos video, .css-videos embed, .css-videos object, .css-videos .mejs-container' );
			var menuDisplay = jQuery( '#menu, #alt-menu' ).css( 'display' );
			if ( menuDisplay == 'block' ) {
				selectors.css( 'visibility', 'hidden' );
			} else {
				selectors.css( 'visibility', 'visible' );
			}
		}, 500 );

	});
}

jQuery( document ).ready( function() {
	handleVids();
	listenForMenuOpenHideVideos();
});