// WPtouch Basic Client-side Ajax Routines
function WPtouchAjax( actionName, actionParams, callback ) {
	var ajaxData = {
		action: "wptouch_client_ajax",
		wptouch_action: actionName,
		wptouch_nonce: wptouchMain.security_nonce
	};

	for ( name in actionParams ) { ajaxData[name] = actionParams[name]; }

	jQuery.post( wptouchMain.ajaxurl, ajaxData, function( result ) {
		callback( result );
	});
}

jQuery( '#content table' ).each( function() {
	parentElement = jQuery( this ).parent( 'p,div' );
	if ( parentElement.hasClass( 'post' ) === false ) {
		parentElement.addClass( 'table-parent' );
	} else {
		jQuery( this ).wrap( '<div class="table-parent"></div>' );
	}
});

jQuery( '#footer .back-to-top' ).click( function( e ) {
	e.preventDefault();
	jQuery( window ).scrollTop( 0 );
});

function doWPtouchReady() {
	// Parse query vars json
	wptouchMain.query_vars = jQuery.parseJSON( wptouchMain.query_vars );

	// Shortcode
	var shortcodeDiv = jQuery( '.wptouch-sc-content' );

	if ( shortcodeDiv.length ) {
		// We have a shortcode
		var params = {
			post_id: shortcodeDiv.attr( 'data-post-id' ),
			page: shortcodeDiv.attr( 'data-page' ),
			post_content: jQuery( '.wptouch-orig-content' ).html(),
			post_nonce: wptouchMain.security_nonce
		};

		jQuery.post( wptouchMain.current_shortcode_url + '&current_time=' + jQuery.now(), params, function( result ) {
				shortcodeDiv.html( result );
				jQuery( document ).trigger( 'wptouch_ajax_content_loaded' );
			}
		);
	}
}

jQuery( document ).ready( function() { doWPtouchReady(); });
