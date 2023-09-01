function doBauhausCustomizerReady() {

	// Post Listings Select
	var postListingSelect = jQuery( 'select', '[id$=_post_listing_view]' );
	// Popular Posts Checkbox
	popularSetting = jQuery( 'input', '[id$=bauhaus_popular_enabled]' );

	checkPostListingShowHide();

	// check whether to show or hide all the various settings
	function checkPostListingShowHide(){
		postListingSelect.on( 'change wptouch.customizerReady', function(){
			if ( jQuery( this ).val() != 'default' ) {
				// Carousel view
				jQuery( '[id$=bauhaus_featured_enabled]' ).hide();
				jQuery( '[id$=post_listing_dots]' ).hide();
				jQuery( '[id$=post_listing_autoplay]' ).hide();
				jQuery( '[id$=bauhaus_featured_carousel_enabled]' ).show();
				jQuery( '[id$=bauhaus_popular_enabled]' ).show();	
			} else {
				jQuery( '[id$=bauhaus_featured_enabled]' ).show();
				jQuery( '[id$=post_listing_dots]' ).show();
				jQuery( '[id$=post_listing_autoplay]' ).show();
				jQuery( '[id$=bauhaus_featured_carousel_enabled]' ).hide();
				jQuery( '[id$=bauhaus_popular_enabled]' ).hide();
			}
			checkPopularShowHide();
		});

		popularSetting.on( 'change wptouch.customizerReady', function(){
			checkPopularShowHide();
		});
	}

	function checkPopularShowHide(){
		if ( popularSetting.is( ':checked' ) && postListingSelect.val() == 'carousel' ) {
			popularSetting.closest( 'li' ).next( 'li' ).show();
		} else {
			popularSetting.closest( 'li' ).next( 'li' ).hide();
		}
	}


	// Post thumbnails select
	jQuery( '[id$=_use_thumbnails]' ).on( 'change wptouch.BauhausCustomizerReady', 'select', function() {
		var thumbSetting = jQuery( '[id$=_thumbnail_type] *' );

		switch( jQuery( this ).val() ) {
			default:
				thumbSetting.show();
			break;
			case 'none':
				thumbSetting.hide();
			break;
		}
	});

	// Thumbnail Image
	var thumbnailSelect = jQuery( '[id$=_thumbnail_type]' );
	thumbnailSelect.on( 'change wptouch.BauhausCustomizerReady', 'select', function(){
		if ( jQuery( this ).val() == 'featured' ) {
			thumbnailSelect.next().find( '*' ).hide();
		} else {
			thumbnailSelect.next().find( '*' ).show();
		}
	});
	
	
	if ( WPtouchCustomizer.wptouch_is_pro == 'no' ) {
		  postListingSelect.find( 'option[value=carousel]').attr( 'disabled','disabled' ).text( 'Carousel View (Pro Only)' );
	}

	// Fire a change to deal with Customizer controlof .change()
	jQuery( '#customize-theme-controls' ).find( 'select' ).trigger( 'wptouch.customizerReady' );
	jQuery( '#customize-theme-controls' ).find( 'input[type="checkbox"]' ).trigger( 'wptouch.BauhausCustomizerReady' );
}

jQuery( window ).load( function() {
	doBauhausCustomizerReady();
});