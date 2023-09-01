function wptouchDoWizard(){

	var wizardContainer = jQuery( '#wptouch-wizard-container' );
	var locale_selected = jQuery( '#language-force_locale' ).val();

	wizardContainer.steps({
	    headerTag: 'details',
	    bodyTag: 'section',
	    transitionEffect: 'slideLeft',
	    startIndex: 0,
	    saveState: true, // save to a cookie
	    labels: {
		    next: '<i class="icon-right-open-big"></i>',
		    previous: '<i class="icon-left-open-big"></i>'
		},
		onInit: function( event, currentIndex ) {
			var wizardScanForAnalytics = jQuery( '#wizard-scan-analytics' );
			if ( wizardScanForAnalytics.length ) {
				wizardScanForAnalytics.click( function( e ) {
					jQuery( this ).text( jQuery( this ).attr( 'data-loading-text' ) );
					jQuery( this ).parent().find( 'i' ).addClass( 'pulse' );
					wptouchAdminAjax(
						'wizard-scan_for_analytics',
						{},
						function( result ) {
							var resultInfo = jQuery.parseJSON( result );
							if ( resultInfo.code == 'noresult' ) {
								// No Analytics code found
								wizardScanForAnalytics.hide().before( '<p>' + resultInfo.msg + '</p>' );
								wizardScanForAnalytics.parent().find( 'i' ).addClass( 'notfound' );
							} else {
								if ( resultInfo.site_id ) {
									wizardScanForAnalytics.hide().before( '<p>' + resultInfo.success + '</p>' );
									wizardScanForAnalytics.parent().find( 'i' ).addClass( 'success' );
									jQuery( '#analytics_google_id' ).val( resultInfo.site_id );
								}
							}
						}
					);
					e.preventDefault();
				});
			}
		},
		onStepChanging: function ( event, currentIndex, newIndex ) {
			bncHasLicense = bncHasLicense;
			// language
			if ( newIndex > currentIndex ) {
				currentStep = wizardContainer.steps( 'getStep', currentIndex );
				switch ( currentStep.title ) {
					case 'Language':
						current_locale = jQuery( '#language-force_locale' ).val();
						if ( current_locale != locale_selected ){
							if ( WPtouchCustom.is_network_admin ) {
								var args = { 'force_network_locale': current_locale };
							} else {
								var args = { 'force_locale': current_locale };
							}

							wptouchAdminAjax(
								'wizard-language',
								args,
								function(){
									// set the cookie to the next slide
									jQuery( '.language img' ).addClass( 'zoomOut' );
									jQuery( '.set-language' ).fadeIn();
									jQuery.cookie( 'jQu3ry_5teps_St@te_wptouch-wizard-container', '1' );
									setTimeout( function(){ wptouchReload() }, 1000 );

								}
							);
						return false;
						}
						break;
					case 'Activate License':
						if ( bncHasLicense == 1 ) {
							// Licensed in this wizard run
							jQuery.cookie( 'jQu3ry_5teps_St@te_wptouch-wizard-container', '3' );
							// Page will be reloaded
						} else if ( bncHasLicense == 0 ) {
							// Didn't get licensed
							return false;
						} else {
							// Previously licensed
							wptouchAdminAjax( 'wizard-update-extensions', {}, wptouchContinue );
						}
						break;
					case 'Download Upload Themes':
					case 'Download Upload Extensions':
						wptouchReload();
						break;
					case 'Choose a Theme':
						theme_selected = jQuery( 'input[name="activate_theme"]:checked').val();
						if ( theme_selected ) {
							jQuery( '.actions li' ).removeClass( 'disabled' );
							jQuery.cookie( 'jQu3ry_5teps_St@te_wptouch-wizard-container', '4' );
							wptouchAdminAjax( 'wizard-theme', { 'theme': theme_selected }, wptouchContinue );
						} else{
							jQuery( '.actions li' ).addClass( 'disabled' );
							return false;
						}
						break;
					case 'Activate Extensions':
						extensions_selected = [];
						jQuery( 'input[name="activate_extension[]"]:checked').each( function() {
							extensions_selected.push( jQuery( this ).val() );
						});
						wptouchAdminAjax( 'wizard-extensions', { 'extensions': extensions_selected }, wptouchContinue );
						break;
					case 'Home and Blog':
						params = {
							'latest_posts_page' : jQuery( '#latest_posts_page' ).val()
						}

						redirect_target_field = jQuery( '#homepage_redirect_wp_target' );
						if ( redirect_target_field.is( 'select' ) && redirect_target_field.val() != 'none' ) {
							params[ 'homepage_redirect_wp_target' ] = redirect_target_field.val();
						}

						wptouchAdminAjax( 'wizard-pages', params, wptouchContinue );
						break;
					case 'Analytics':
						analytics_google_id = jQuery( '#analytics_google_id' ).val();
						wptouchAdminAjax( 'wizard-analytics', { 'analytics_google_id': analytics_google_id }, wptouchContinue );
						break;
					case 'WPtouch Love':
						show_wptouch_in_footer = ( jQuery( '#wptouch_love' ).prop( 'checked' ) === true );
						wptouchAdminAjax( 'wizard-wptouch_message', { 'show_wptouch_in_footer': show_wptouch_in_footer }, wptouchContinue );
						break;
					case 'Multisite':
						multisite_control = ( jQuery( '#multisite_control' ).prop( 'checked' ) === true );
						wptouchAdminAjax( 'wizard-multisite', { 'multisite_control': multisite_control }, wptouchContinue );
						break;
					case 'WPtouch Support':
						if ( WPtouchCustom.is_network_admin ) {
							wptouchAdminAjax( 'network-wizard-complete', {}, wptouchContinue );
						} else {
							wptouchAdminAjax( 'wizard-complete', {}, wptouchContinue );
						}
						break;
				}
			}
			return true;
		}
	});

	// Now make it visible ;p
	wizardContainer.css( 'visibility', 'visible' );
}

function wptouchContinue() {
	return true;
}

function wptouchReload( response ) {
	if ( response == 0 ) {
		return true;
	} else {
		// go to the same url, without reload (prevent FOUC)
		window.location.href = window.location.href;
		return false;
	}
}

function wptouchSetupSelects(){
	jQuery( '#wptouch-settings-area select' ).select2({
		minimumResultsForSearch: '20'
	});
}

function wptouchLove(){
	jQuery( '#wptouch_love' ).on( 'change', function(){
		if ( !jQuery( this ).prop( 'checked' ) ) {
			jQuery( 'i.heartbeat' ).removeClass( 'icon-heart' ).addClass( 'icon-heart-broken' );
		} else {
			jQuery( 'i.heartbeat' ).addClass( 'icon-heart' ).removeClass( 'icon-heart-broken' );
		}
	});
}

function wptouchWizardLicense(){

	if ( jQuery( '#activate-license a' ).length ){
		jQuery( '#activate-license a' ).on( 'click', function( e ) {

	//
			e.preventDefault();

			// Setup AJAX params
			var licenseEmail = jQuery( '#license_email' ).val();
			var licenseKey = jQuery( '#license_key' ).val().trim();
			var licenseTextDiv = jQuery( 'p.license-issue' );

			var ajaxParams = {
				email: licenseEmail,
				key: licenseKey
			};

			wptouchAdminAjax( 'activate-license-key', ajaxParams, function( result ) {
				if ( result == '1' ) {
					// license success
					bncHasLicense = 1;
					wptouchReload();

				} else {
					// rejected license
					jQuery( '#license_email, #license_key' ).addClass( 'error' );
					if ( result == '2' ) {
						licenseTextDiv.html( 'Our server rejected your e-mail address and/or license key.<br />Please check that they are correct and try again.' );
					} else if( result == '3' ){
						licenseTextDiv.html( 'You have no licenses remaining to activate this site.<br />You can add <a href="http://www.wptouch-com/account">additional licenses</a> to your account and try again.' );
					} else if( result == '4' ){
						licenseTextDiv.html( 'The server is having an issue activating your license.<br />Please contact us at <a href="mailto:support@wtouch.com?subject=Cannot%20Reach%20WPtouch%20Server">support@wptouch.com</a> and let us know.' );
					} else if( result == '5' ){
						licenseTextDiv.html( 'Your license has expired.<br />Please <a href="//www.wptouch.com/renew">renew</a> to activate this site.' );
					}

					licenseTextDiv.fadeIn( function(){
						setTimeout(function(){
							jQuery( 'p.license-issue' ).fadeOut();
							jQuery( '#license_email, #license_key' ).removeClass( 'error' );
						}, 3500 );
					});
				}
			});
		});
	} else {
		bncHasLicense = null;
	}
}

function wptouchWizardThemeSelect(){
	jQuery()
	jQuery( '.choose-a-theme' ).on( 'click', 'li:not(.unlicensed)', function(){
		jQuery( '.choose-a-theme li' ).removeClass( 'active' );
		jQuery( this ).addClass( 'active' );
		jQuery( '.actions li' ).removeClass( 'disabled' );
		jQuery( 'li.error' ).removeClass( 'error' );
		jQuery( this ).find( 'input[type="radio"]' ).prop( 'checked', 'checked' );
	});
}

function wptouchWizardSetupComplete(){
	jQuery( '#exit_wizard_customizer, #exit_wizard_settings' ).on( 'click', function(){
		jQuery.cookie( 'jQu3ry_5teps_St@te_wptouch-wizard-container', '1', { expires: -1 } );
		jQuery.cookie( 'wptouch_customizer_mode', 'mobile', { expires: 0, path: '/' } );
	});
}

function wptouchWizardDownloadUpload(){
	var canAjaxInstall = typeof new XMLHttpRequest().responseType === 'string';
	if ( canAjaxInstall ) {
		jQuery( '.down-up-theme h2' ).text( WPtouchCustom.install_themes );
		jQuery( '.down-up-extension h2' ).text( WPtouchCustom.install_extensions );
		jQuery( '.download-upload button' ).text( WPtouchCustom.install );
		jQuery( 'p.upload' ).hide();
	}

	jQuery( '.download-upload' ).on( 'click', 'button.download', function(e){
		download_link = jQuery( this ).attr( 'data-download-link' );
		upload_type = jQuery( this ).parent().attr( 'data-type' );
		if ( canAjaxInstall ) {
			wptouchAdminAjaxInstall( this, upload_type, download_link );
		} else {
			window.location.href = download_link;
		}
		e.preventDefault();
	});

	// Upload Themes
	if ( jQuery( '#upload_theme' ).length ){
		var themeUploader = new AjaxUpload( '#upload_theme', {
    	action: ajaxurl,
    	allowedExtensions: [ 'zip' ],
		debug: false,
		data: {
			action: 'upload_file',
			file_type: 'theme',
			wp_nonce: WPtouchCustom.admin_nonce
		},
		name: 'theme-upload',
			onSubmit: function( fileName, extension ) {
				jQuery( '#upload_theme' ).text( jQuery( '#upload_theme' ).attr( 'data-loading-text' ) );
			},
			onComplete: function( fileName, response ) {
				var currentText = jQuery( 'p.upload span', '.down-up-theme' ).attr( 'data-text' );
				var newLoadingtext = jQuery( 'p.upload span', '.down-up-theme' ).attr( 'data-loaded-text' );
				jQuery( '#upload_theme', '.down-up-theme' ).text( jQuery( '#upload_theme' ).attr( 'data-text' ) );
				jQuery( '.upload span', '.down-up-theme' ).text( newLoadingtext );
				setTimeout( function(){
					jQuery( '.upload span', '.down-up-theme' ).text( currentText );
				}, 2500 );
			}
		});
	}

	// Upload Extensions
	if ( jQuery( '#upload_extension' ).length ){
		var themeUploader = new AjaxUpload( '#upload_extension', {
	    	action: ajaxurl,
	    	allowedExtensions: [ 'zip' ],
			debug: false,
			data: {
				action: 'upload_file',
				file_type: 'extension',
				wp_nonce: WPtouchCustom.admin_nonce
			},
			name: 'extension-upload',
				onSubmit: function( fileName, extension ) {
					jQuery( '#upload_extension' ).text( jQuery( '#upload_extension' ).attr( 'data-loading-text' ) );
				},
				onComplete: function( fileName, response ) {
					var currentText = jQuery( 'p.upload span', '.down-up-extension' ).attr( 'data-text' );
					var newLoadingtext = jQuery( 'p.upload span', '.down-up-extension' ).attr( 'data-loaded-text' );
					jQuery( '#upload_extension' ).text( jQuery( '#upload_extension' ).attr( 'data-text' ) );
					jQuery( '.upload span', '.down-up-extension' ).text( newLoadingtext );
					setTimeout( function(){
						jQuery( '.upload span', '.down-up-extension' ).text( currentText );
					}, 2500 );
				}
		});
	}
}

jQuery( document ).ready( function(){
	wptouchDoWizard();
	wptouchLove();
	wptouchSetupSelects();
	wptouchWizardLicense();
	wptouchWizardThemeSelect();
	wptouchWizardSetupComplete();
	wptouchWizardDownloadUpload();
});