function wptouchCloudRepair() {

	var brokenThemeDiv = jQuery( '.remodal-cloud' );

	if ( brokenThemeDiv.length ) {

		jQuery( '.remodal .message' ).hide();

		// Animation to start the progressbar
		function wptouchProgressBarStart( barElement ){
			jQuery( barElement ).animate({ width: '50%' }, 250 );
		}

		// Animation for a success response
		function wptouchProgressBarSuccess( barElement ){
			jQuery( barElement ).animate({ width: '100%' }, 250, function(){ jQuery( barElement ).addClass( 'bar-success' ); } );
		}

		// Animation for a failed response
		function wptouchProgressBarFail( barElement ){
			jQuery( barElement ).animate({ width: '100%' }, 250, function(){ jQuery( barElement ).addClass( 'bar-fail' ); } );
		}

		// Cache objects we'll be using
		var repairModal = jQuery('[data-remodal-id=modal-repair]').remodal();

		// Our progress bar element
		var progressBar = jQuery( '#progress-repair' ).find( '.bar' );

		wptouchProgressBarStart( progressBar );

		var ajaxParams = {};

		wptouchAdminAjax( 'repair-active-theme', ajaxParams, function( result ) {

			var thisResult = result;
			repairModal.open();
			if ( result == '0' ) {
				setTimeout( function(){
				wptouchProgressBarFail( progressBar );
					jQuery( '.remodal .failed-msg' ).slideDown();
				}, 500 );
			} else {
				setTimeout( function(){
					wptouchProgressBarSuccess( progressBar );
					jQuery( '.remodal .success-msg' ).slideDown();
				}, 500 );

				jQuery( document ).one( 'closed', '.remodal', function () {
					window.location.reload();
				});
			}
		});
	}

}

jQuery( document ).ready( function() {
	wptouchCloudRepair();
} );