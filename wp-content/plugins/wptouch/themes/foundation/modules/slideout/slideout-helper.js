jQuery(function(){
    // only init if Slideout module is loaded
    if ( typeof( Slideout ) !== 'undefined' ) {

        // setup slideout for each .menu-btn
        jQuery( '.pushit' ).each( function( index, trigger ) {

            // init slideout
            var slideout = new Slideout({
                'panel': jQuery( '.page-wrapper' )[0],
                'menu': jQuery( trigger )[0],
                'touch': false,
                'side': jQuery( trigger ).hasClass( 'pushit-left' ) ? 'left' : 'right',
                'duration': 330
            });

            // toggle slideout open/close on menu button click
            var btnTarget = jQuery ( '.menu-btn[data-menu-target="' + jQuery( trigger ).find( "> div" ).attr( "id" ) + '"]' );
            btnTarget.on( 'click', function( e ) {
                e.preventDefault();
                slideout.toggle();
            });

            // when slideout is open, close it if the .page-wrapper is clicked
            slideout.on( 'open', function() {
                jQuery( '.page-wrapper' ).one(deviceHasTouchSupport() ? 'touchstart' : 'click', function( e ) {
            		e.preventDefault();
            		e.stopImmediatePropagation();
            		if ( !jQuery(this).hasClass('menu-btn') ) {
            			if ( slideout.isOpen() ) {
            				slideout.close();
            			}
            		}
				});
            });

            // special handling of left and right menus
            if ( jQuery( '.pushit' ).length > 1 && jQuery( this ).hasClass('pushit-right') ) {
                jQuery( '.pushit-right' ).hide();

                slideout.on( 'close', function() {
                    jQuery( '.pushit-right' ).hide();
                });

                slideout.on( 'beforeopen', function() {
                    jQuery( '.pushit-right' ).show();
                });
            }
        });
    }

    // tests browser for touch support
    function deviceHasTouchSupport() {
        return 'ontouchstart' in document.documentElement;
    }
});
