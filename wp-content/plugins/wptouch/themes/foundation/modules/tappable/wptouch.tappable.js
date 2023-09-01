jQuery( window ).load( function() {
	bindTappableEls( '.tappable, .show-hide-toggle, .slide-toggle, .menu-btn' );
});

jQuery( document ).ajaxComplete( function() {
	bindTappableEls( '.tappable, .show-hide-toggle, .slide-toggle, .menu-btn' );
});

function bindTappableEls( elements ){
	jQuery( elements ).each( function(){
		jQuery( this ).tappable({ touchDelay: 74 });
	});
}