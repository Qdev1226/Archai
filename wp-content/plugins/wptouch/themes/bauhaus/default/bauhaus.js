/* WPtouch Bauhaus Theme JS File */
/* Public functions called here reside in base.js, found in the Foundation theme */

function doBauhausReady() {
	bauhausSliderMods();
	bauhausMoveFooterDiv();
	bauhausBindTappableLinks();
	bauhausSearchToggle();
	bauhausWebAppMenu();
	bauhausHandleSearch();
	bauhausHandlePostImgs();
	bauhausFlickity();
}

// Spice up the appearance of Foundation's Featured Slider
function bauhausSliderMods(){
	jQuery( '#slider a' ).each( function(){
		imgCloned = jQuery( this ).find( 'img' ).attr( 'src' );
		jQuery( this ).append( '<img class="clone" src="'+imgCloned+'" alt="" />');
		jQuery( this ).find( 'p' ).not( 'p.featured-date' ).addClass( 'heading-font' );
	});
}

// CSS animated slideout
function bauhausSearchToggle(){
	jQuery( '#search-toggle' ).on( 'click', function(){
		jQuery( '#search-dropper' ).toggleClass( 'toggled' );
	});
}

// Move the footer below the switch
function bauhausMoveFooterDiv(){
	if ( jQuery( '#switch' ).length ) {
		var footerDiv = jQuery( '.footer' ).detach();
		jQuery( '#switch' ).after( footerDiv );
	}
}

// Add 'touched' class to these elements when they're actually touched (100ms delay) for a better UI experience (tappable module)
function bauhausBindTappableLinks(){
	// Drop down menu items
	jQuery( '.wptouch-menu li.menu-item' ).each( function(){
		jQuery( this ).addClass( 'tappable' );
	});
}

// In Web-App Mode, dynamically ensure that the Menu height is correct and scrollable
function bauhausWebAppMenu(){
	if ( navigator.standalone ) {
		var bodyCheck = jQuery( 'body.web-app-mode.ios7.smartphone' );
		var menuEl = jQuery( '#menu' );
		jQuery( window ).resize( function() {
			var windowHeight = jQuery( window ).height() - 74;
			if ( bodyCheck.hasClass( 'portrait' ) ) {
				menuEl.css( 'max-height', windowHeight );
			}
			if ( bodyCheck.hasClass( 'landscape' ) ) {
				menuEl.css( 'max-height', windowHeight );
			}
		}).resize();
	}
}

function bauhausHandlePostImgs(){
	jQuery( '.post-page-content p img' ).each( function(){
		if ( !jQuery( this ).is( '.aligncenter, .alignleft, .alignright' ) ) {
			jQuery( this ).addClass( 'aligncenter' );
		}
	});
}

function bauhausHandleSearch() {
	if ( jQuery( '.search' ).length ) {
		jQuery( '.search-select' ).change( function( e ) {
			var sectionName = ( '#' + jQuery( this ).find( ':selected' ).attr( 'data-section' ) + '-results' );
			jQuery( '#content > div:not(.post-page-head-area)' ).hide();
			jQuery( sectionName ).show();
			e.preventDefault();
		}).trigger( 'change' );
	}
}

function bauhausFlickity(){

	jQuery( '.slider-latest-only .carousel' ).css({ 'height': jQuery( window ).height() });

		var bauhausFriction = 0.32;
		var bauhausAttraction = 0.06;
		var bauhausThreshold = 15;
		

	var recentCarousel = jQuery( '.recent-carousel' ).flickity({
		friction: bauhausFriction,
		selectedAttraction: bauhausAttraction,
		dragThreshold: bauhausThreshold,
		setGallerySize: false,
		cellAlign: 'left',
		wrapAround: false,
		prevNextButtons: false,
		pageDots: false,
		imagesLoaded: true,
		lazyLoad: 4
	});

	var carouselData = recentCarousel.data( 'flickity' );
	recentCarousel.on( 'settle.flickity', function(){
		// as we go along, load more
		if ( carouselData.selectedIndex > carouselData.cells.length - 4 ) {
			var loadMoreRecent = recentCarousel.attr( 'rel' );
			jQuery.get( loadMoreRecent ).done( function( result ) {
				if ( true ) {
					items = jQuery( result ).find( '.recent-carousel .carousel-cell' );
					recentCarousel.flickity( 'append', items );
					newPageUrl = jQuery( result ).find( '.recent-carousel' ).attr( 'rel' );
					recentCarousel.attr( 'rel', newPageUrl );
				}
			});
		}
	});

	jQuery( '.featured-carousel' ).flickity({
		friction: bauhausFriction,
		selectedAttraction: bauhausAttraction,
		dragThreshold: bauhausThreshold,
		setGallerySize: false,
		cellAlign: 'left',
		freeScroll: false,
		imagesLoaded: true,
		wrapAround: false,
		prevNextButtons: false,
		pageDots: false,
		lazyLoad: 1
	});

	jQuery( '.popular-carousel' ).flickity({
		friction: bauhausFriction,
		selectedAttraction: bauhausAttraction,
		dragThreshold: bauhausThreshold,
		setGallerySize: false,
		cellAlign: 'left',
		freeScroll: false,
		imagesLoaded: true,
		wrapAround: false,
		prevNextButtons: false,
		pageDots: false,
		lazyLoad: 1
	});
	
	listCarousel = jQuery( '.list-view .list-carousel' );
	showDots = false;
	autoPlay = false;
	if ( listCarousel.hasClass( 'dots' ) ) {
		showDots = true;
	}
	if ( listCarousel.hasClass( 'autoplay' ) ) {
		autoPlay = true;
	}
	
	jQuery( '.list-carousel' ).flickity({
		friction: bauhausFriction,
		selectedAttraction: bauhausAttraction,
		dragThreshold: bauhausThreshold,
		setGallerySize: false,
		cellAlign: 'left',
		freeScroll: false,
		imagesLoaded: true,
		wrapAround: false,
		prevNextButtons: false,
		autoPlay: autoPlay,
		pageDots: showDots,
		lazyLoad: 1
	});

}


jQuery( document ).ready( function() { doBauhausReady(); } );