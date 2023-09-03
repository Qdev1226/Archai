/* global searchwp_live_search_admin_notices */

/**
 * SearchWP Live Search Dismissible Notices.
 *
 * @since 1.7.0
 */

'use strict';

var SearchWPLiveSearchAdminNotices = window.SearchWPLiveSearchAdminNotices || ( function( document, window, $ ) {

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

			app.events();
		},

		/**
		 * Dismissible notices events.
		 *
		 * @since 1.7.0
		 */
		events: function() {

			$( document ).on(
				'click',
				'.searchwp-live-search-notice .notice-dismiss, .searchwp-live-search-notice .searchwp-live-search-notice-dismiss',
				app.dismissNotice
			);
		},

		/**
		 * Dismiss notice event handler.
		 *
		 * @since 1.7.0
		 *
		 * @param {object} e Event object.
		 * */
		dismissNotice: function( e ) {

			$.post( searchwp_live_search_admin_notices.ajax_url, {
				action: 'searchwp_live_search_notice_dismiss',
				nonce:   searchwp_live_search_admin_notices.nonce,
				id: 	 ( $( this ).closest( '.searchwp-live-search-notice' ).attr( 'id' ) || '' ).replace( 'searchwp-live-search-notice-', '' ),
			} );
		},
	};

	return app;

}( document, window, jQuery ) );

// Initialize.
SearchWPLiveSearchAdminNotices.init();
