
// Bauhaus & CMS & Classic
function doShimCustomizerReady() {

	// Post thumbnails select
	jQuery( '[id$=_use_thumbnails]' ).on( 'change wptouch.customizerReady', 'select', function() {
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

	thumbnailSelect.on( 'change wptouch.customizerReady', 'select', function(){
		if ( jQuery( this ).val() == 'featured' ) {
			thumbnailSelect.next().find( '*' ).hide();
		} else {
			thumbnailSelect.next().find( '*' ).show();
		}
	});

	jQuery( '#accordion-section-foundation-web-theme-settings' ).find( 'select' ).change();

}


jQuery( window ).load( function() {
	doShimCustomizerReady();
});