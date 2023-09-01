function doOwlFeatured() {
	if ( jQuery().owlCarousel ) {
		var use_rtl = jQuery( 'body' ).hasClass( 'rtl' );

		var autoplay = jQuery( '#slider' ).hasClass( 'slide' );
		var loop = jQuery( '#slider' ).hasClass( 'continuous' );

		if ( autoplay ) {
			if ( jQuery( '#slider' ).hasClass( 'slow' ) ) {
				var autoplayTimeout = 7000;
			} else if ( jQuery( '#slider').hasClass( 'fast' ) ) {
				var autoplayTimeout = 3000;
			} else {
				autoplayTimeout = 5000;
			}
		} else {
			autoplayTimeout = false;
		}

		var carouselOptions = {
			rtl: use_rtl,
			nav: false,
			items: 1,
			loop: loop,
//			lazyLoad : true,
			autoplay: autoplay,
			autoplayTimeout: autoplayTimeout
		}

		jQuery( '.owl-carousel' ).owlCarousel( carouselOptions );
	}
}

jQuery( document ).ready( function() {
	if ( jQuery( '#slider' ).length ) {
		doOwlFeatured();
	}
});
