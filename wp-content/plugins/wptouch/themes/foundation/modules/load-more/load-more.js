function doFoundationLoadMoreReady() {
	var loadMoreLink = 'a.load-more-link';
	jQuery( '#content' ).on( 'click', loadMoreLink, function( e ) {
		jQuery( loadMoreLink ).addClass( 'ajaxing' ).text( wptouchFdn.ajaxLoading ).prepend( '<span class="spinner"></span>' );
		jQuery( '.spinner' ).spin( 'tiny' );
		var loadMoreURL = jQuery( loadMoreLink ).attr( 'rel' );

		jQuery( loadMoreLink ).after( "<span class='ajax-target'></span>" );
		jQuery( '.ajax-target' ).load( loadMoreURL + ' #content > div, #content .load-more-link', function() {
			jQuery( '.ajax-target' ).replaceWith( jQuery( this ).html() );
			jQuery( '.ajaxing' ).animate( { height: 'toggle' }, 200, 'linear', function(){ jQuery( this ).remove(); } );
		});

		e.preventDefault();
	});

	// Load More Comments
	var loadMoreComsLink = '.load-more-comments-wrap a';
	jQuery( '.commentlist' ).on( 'click', loadMoreComsLink, function() {
		jQuery( loadMoreComsLink ).addClass( 'ajaxing' ).text( wptouchFdn.ajaxLoading ).prepend( '<span class="spinner"></span>' );
		jQuery( '.spinner' ).spin( 'tiny' );
		var loadMoreURL = jQuery( loadMoreComsLink ).prop( 'href' );

		jQuery( loadMoreComsLink ).parent().after( "<span class='ajax-target'></span>" );
		jQuery( '.ajax-target' ).load( loadMoreURL + ' ol.commentlist > li', function() {
			jQuery( '.ajax-target' ).replaceWith( jQuery( this ).html() );
			jQuery( '.ajaxing' ).animate( { height: 'toggle' }, 200, 'linear', function(){ jQuery( this ).parent().remove(); } );
		});

		return false;
	});

	// Load More Post Search Results
	var loadMorePostSearchLink = 'a.load-more-post-link';
	var postTypeName = jQuery( loadMorePostSearchLink ).data( 'lang-type' ) || 'post';
	jQuery( '#content' ).on( 'click', loadMorePostSearchLink, function( e ) {
		jQuery( loadMorePostSearchLink ).addClass( 'ajaxing' ).text( wptouchFdn.ajaxLoading ).prepend( '<span class="spinner"></span>' );
		jQuery( '.spinner' ).spin( 'tiny' );
		var loadMoreURL = jQuery( loadMorePostSearchLink ).attr( 'rel' );

		jQuery( loadMorePostSearchLink ).after( "<span class='ajax-target'></span>" );
		jQuery( '.ajax-target' ).load( loadMoreURL + ' #content #' + postTypeName + '-results, #content .load-more-post-link', function() {
			jQuery( '.ajax-target' ).replaceWith( jQuery( this ).html() );
			jQuery( '.ajaxing' ).animate( { height: 'toggle' }, 200, 'linear', function(){ jQuery( this ).remove(); } );
		});

		e.preventDefault();
	});

	// Load More Page Search Results
	var loadMorePageSearchLink = 'a.load-more-page-link';
	var pageTypeName = jQuery( loadMorePageSearchLink ).data( 'lang-type' ) || 'page';
	jQuery( '#content' ).on( 'click', loadMorePageSearchLink, function( e ) {
		jQuery( loadMorePageSearchLink ).addClass( 'ajaxing' ).text( wptouchFdn.ajaxLoading ).prepend( '<span class="spinner"></span>' );
		jQuery( '.spinner' ).spin( 'tiny' );
		var loadMoreURL = jQuery( loadMorePageSearchLink ).attr( 'rel' );

		jQuery( loadMorePageSearchLink ).after( "<span class='ajax-target'></span>" );
		jQuery( '.ajax-target' ).load( loadMoreURL + ' #content #' + pageTypeName + '-results, #content .load-more-page-link', function() {
			jQuery( '.ajax-target' ).replaceWith( jQuery( this ).html() );
			jQuery( '.ajaxing' ).animate( { height: 'toggle' }, 200, 'linear', function(){ jQuery( this ).remove(); } );
		});

		e.preventDefault();
	});

	// Load More Custom Post Search Results
	var loadMoreCustomSearchLink = 'a.load-more-custom-link';
	jQuery( '#content' ).on( 'click', loadMoreCustomSearchLink, function( e ) {
		var customTypeName = jQuery( this ).data( 'lang-type' );
		jQuery( loadMoreCustomSearchLink ).filter( '[data-lang-type="' + customTypeName + '"]' ).addClass( 'ajaxing' ).text( wptouchFdn.ajaxLoading ).prepend( '<span class="spinner"></span>' );
		jQuery( '.spinner' ).spin( 'tiny' );
		var loadMoreURL = jQuery( loadMoreCustomSearchLink ).filter( '[data-lang-type="' + customTypeName + '"]' ).attr( 'rel' );

		jQuery( loadMoreCustomSearchLink ).filter( '[data-lang-type="' + customTypeName + '"]' ).after( "<span class='ajax-target'></span>" );
		jQuery( '.ajax-target' ).load( loadMoreURL + ' #content #' + customTypeName + '-results, #content .load-more-custom-link[data-lang-type="' + customTypeName + '"]', function() {
			jQuery( '.ajax-target' ).replaceWith( jQuery( this ).html() );
			jQuery( '.ajaxing' ).animate( { height: 'toggle' }, 200, 'linear', function(){ jQuery( this ).remove(); } );
		});

		e.preventDefault();
	});

}
jQuery( document ).ready( function() { doFoundationLoadMoreReady(); });
