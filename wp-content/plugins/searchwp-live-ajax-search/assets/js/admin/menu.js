/**
 * SearchWP Live Search Admin Menu.
 *
 * @since 1.7.0
 */

'use strict';

var SearchWPLiveSearchAdminMenu = window.SearchWPLiveSearchAdminMenu || ( function( document, window, $ ) {

	/**
	 * Public functions and properties.
	 *
	 * @since 1.7.0
	 *
	 * @type {object}
	 */
	var app = {

		/**
		 * Start the engine.
		 *
		 * @since 1.7.0
		 */
		init: function() {

			$( app.ready );
		},

		/**
		 * Document ready.
		 *
		 * @since 1.7.0
		 */
		ready: function() {

			app.addParamsToUpgradeLink();
		},

		/**
		 * Add 'target="_blank"' and 'rel="noopener noreferrer"' to the "Upgrade to Pro" menu link.
		 *
		 * @since 1.7.0
		 */
		addParamsToUpgradeLink: function() {

			$( 'a.searchwp-sidebar-upgrade-pro' )
				.attr( 'target', '_blank' )
				.attr( 'rel', 'noopener noreferrer' );
		},
	};

	return app;

}( document, window, jQuery ) );

// Initialize.
SearchWPLiveSearchAdminMenu.init();
