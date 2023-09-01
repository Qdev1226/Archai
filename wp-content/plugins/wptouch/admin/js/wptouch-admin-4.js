 /* WPtouch 4 Main Admin js */

// Admin tabs handler
function wptouchSetupAdminMenu(){
	var adminMenuArea = jQuery( '#wptouch-admin-menu' );
	if ( adminMenuArea.length ) {
		adminMenuArea.on( 'click', 'a', function( e ) {
			var targetSlug = jQuery( this ).attr( 'data-page-slug' );

			jQuery( '.wptouch-settings-sub-page:not(#' + targetSlug + ')' ).hide();
			jQuery( '#' + targetSlug ).show();

			adminMenuArea.find( 'a' ).removeClass( 'active' );
			jQuery( this ).addClass( 'active' );

			jQuery.cookie( 'wptouch-4-admin-menu', targetSlug );
			e.preventDefault();
		});

		// Update selected menu item if hash updates.
        // This is used when the 'Subscribe to Newsletter' admin notice is clicked
        // while already viewing the settings for the WPtouch plugin.
        jQuery(window).on('hashchange',  function() {
            var hash = window.location.hash.replace('#', '');
            if ( hash ) {
                adminMenuArea.find( 'a.' + hash ).click();
            }
        });

        var menuHandled = 0;

		// Check to see if the URL is requesting a specific tab.
        var hash = window.location.hash.replace('#', '');
        if ( hash ) {
            adminMenuArea.find( 'a.' + hash ).click();
            menuHandled = 1;
        }

		// If the URL hash isn't set, check to see if the menu cookie has been set previously.
		var previousCookie = jQuery.cookie( 'wptouch-4-admin-menu' );
		if ( !menuHandled && previousCookie ) {
			if ( jQuery( '#wptouch-admin-menu a.' + previousCookie ).length ) {
				menuHandled = 1;
			}
		}

		// If neither of the above, then click the first element.
		if ( !menuHandled ) {
			adminMenuArea.find( 'a:first' ).click();
		}
	}
}

function wptouchTooltipSetup() {
	jQuery( 'i.wptouch-tooltip' ).tooltip( { placement:'top' } );
}

function wptouchSetupSelects(){
	jQuery( '#wptouch-settings-area select' ).select2({
		minimumResultsForSearch: '20'
	});
}

function wptouchDownloadUploadSettings(){
		// Download settings button
	var backupButton = jQuery( '#backup-button' );
	if ( backupButton.length ) {
		backupButton.on( 'click', function( e ) {
			var ajaxParams = {};
			wptouchAdminAjax( 'prep-settings-download', ajaxParams, function( result ) {
				if ( result ) {
					var newUrl = WPtouchCustom.plugin_url + '&action=wptouch-download-settings&backup_file=' + result + '&nonce=' + WPtouchCustom.admin_nonce + '&redirect=' + WPtouchCustom.plugin_url;

					document.location.href = newUrl;
				}
			});

			e.preventDefault();
		});

		// Upload settings button
		var backupUploader = new AjaxUpload( '#restore-button', {
	    	action: ajaxurl,
	    	allowedExtensions: [ 'txt' ],
			debug: false,
			data: {
				action: 'upload_file',
				file_type: 'settings_backup',
				wp_nonce: WPtouchCustom.admin_nonce
			},
			name: 'myfile',
			onComplete: function( fileName, response ) {
				// Reload the page
				wptouchAdminTriggerReload();
			}
		});
	}
}

function WPtouchRefreshCustomIconArea() {
	jQuery( 'ul.custom-uploads-display', '#setting-custom_icon_management' ).load( document.location.href + ' #setting-custom_icon_management li' );
}

function wptouchHandleCustomIconUpload() {
	if ( jQuery( '.custom-icon-uploader' ).length ) {
		var thisUploader = jQuery( '#custom_icon_uploader' );
		var thisSpinner = thisUploader.find( '.spinner' );

		var iconUploader = new AjaxUpload( '#custom_icon_upload_button', {
	    	action: ajaxurl,
	    	allowedExtensions: [ 'png', 'jpg' ],
			debug: false,
			data: {
				action: 'upload_file',
				file_type: 'custom_image',
				wp_nonce: WPtouchCustom.admin_nonce
			},
			name: 'myfile',
			onSubmit: function( fileName, extension ) {
				thisSpinner.show();
				jQuery( '.wpt-spinner', thisUploader ).animate({
					opacity: 1
				}, 100 );
			},
			onComplete: function( fileName, response ) {
				jQuery( '.wpt-spinner', thisUploader ).animate({
					opacity: 0
				}, 500 );
				WPtouchRefreshCustomIconArea();
			},
			onCancel: function( id, fileName ) {
				jQuery( '.wpt-spinner', thisUploader ).animate({
					opacity: 0
				}, 500 );
			},
			showMessage: function( message ) {
				// Nothin'
			}
		});
	}

	// Handle delete custom Icon
	if ( jQuery( 'ul.custom-uploads-display' ).length ) {
		jQuery( '#setting-custom_icon_management' ).on( 'click', 'ul.custom-uploads-display li', function( e ) {
			var iconName = jQuery( this ).find( 'p.name' ).attr( 'data-name' );
			var ajaxParams = {
				icon_name: iconName
			};
			jQuery( this ).fadeOut( 'fast' );
			wptouchAdminAjax( 'delete-custom-icon', ajaxParams, function( result ) {
				if ( result == 0 ) {
					WPtouchRefreshCustomIconArea();
				}
			});

			e.preventDefault();
		});
	}

	// Handle delete fade-in/fade-out
	jQuery( 'ul.custom-uploads-display' ).on( 'mouseenter', 'li', function() {
		jQuery( this ).find( 'a' ).fadeIn( 'fast' );
	}).on( 'mouseleave', 'li', function() {
		jQuery( this ).find( 'a' ).fadeOut( 'fast' );
	});

	// Setup the show/hide for the whole menu icons area based on whether they're used or not
	wptouchCheckToggle( '#enable_menu_icons', '#section-admin_menu_icon_sets, #section-admin_menu_icon_upload' );

}

function wptouchHandlePluginCompat() {
	var pluginCompatDiv = jQuery( '#plugin-compat-setting .content' );
	if ( pluginCompatDiv.length ) {
		var ajaxParams = {};

		wptouchAdminAjax( 'load-plugin-compat-list', ajaxParams, function( result ) {
			pluginCompatDiv.html( result );
		});
	}
}

// Function that holds toggle settings
function wptouchSetupSettingsToggles() {

	wptouchCheckToggle('#enable_custom_post_types', '#section-foundation-web-custom-post-types' );

	// General Settings: Analytics
	jQuery( '#analytics_embed_method' ).on( 'change', function() {

	var google = jQuery( '#setting-analytics_google_id' );
	var stats = jQuery( '#setting-custom_stats_code' );

		switch( jQuery( this ).val() ) {
			case 'simple':
				google.slideDown();
				stats.hide();
				break;
			case 'custom':
				stats.slideDown();
				google.hide();
				break;
			default:
				jQuery( '#setting-analytics_google_id, #setting-custom_stats_code' ).hide();
				break;
		}
	} ).trigger( 'change' );

	// General Settings: Filter URLs
	jQuery( '#setting-url_filter_behaviour' ).find( 'select' ).on( 'change', function() {
		var filteredEls = jQuery( '#setting-filtered_urls, #setting-filtered_urls_exact' );
		switch( jQuery( this ).val() ) {
			case 'disabled':
				filteredEls.hide();
				break;
			default:
				filteredEls.slideDown( 'fast' );
				break;
		}
	} ).trigger( 'change' );

	// General Settings: WPtouch Homepage
	jQuery( '#setting-homepage_landing' ).find( 'select' ).on( 'change', function() {

		var redirectTargetDiv = jQuery( '#setting-homepage_redirect_wp_target' );
		var customTargetDiv = jQuery( '#setting-homepage_redirect_custom_target' );

		switch( jQuery( this ).val() ) {
			case 'none':
				redirectTargetDiv.hide();
				customTargetDiv.hide();
				break;
			case 'select':
				redirectTargetDiv.show();
				customTargetDiv.hide();
				break;
			case 'custom':
				customTargetDiv.show();
				redirectTargetDiv.hide();
				break;
		}
	} ).trigger( 'change' );
}

function wptouchLoadUpgradeArea() {
	jQuery( 'button#upgrade-to-pro' ).on( 'click', function(){
		window.location = jQuery( this ).attr( 'data-target' );
	});

	var upgrade = jQuery( '#upgrade-area' );
	if ( upgrade.length ) {
		var ajaxParams = {};
		wptouchAdminAjax( 'load-upgrade-area', ajaxParams, function( result ) {
			upgrade.html( result );
			if ( jQuery( '#license-settings-area' ).length ) {
				var goProHref = jQuery( '#upgrade-area .button' ).attr( 'href' );
				goProHref = goProHref + '&callback=' + encodeURIComponent( window.location );
				jQuery( '#upgrade-area .button' ).attr( 'href', goProHref );
			}
		});
	}
}

function wptouchHandleDownloadIconSets() {
	var iconSetArea = jQuery( '#manage-icon-sets' );
	if ( iconSetArea.length ) {
		var ajaxParams = {};
		wptouchAdminAjax( 'get-icon-set-info', ajaxParams, function( result ) {
			iconSetArea.html( result );

			jQuery( 'ul.manage-sets' ).on( 'click', 'button', function( e ) {
				var pressedButton = jQuery( this );
				var installURL = jQuery( this ).attr( 'data-install-url' );
				var basePath = jQuery( this ).attr( 'data-base-path' );
				var loadingText = jQuery( this ).attr( 'data-loading-text' );

				var ajaxParams = {
					url: installURL,
					base: basePath
				};

				pressedButton.html( loadingText ).addClass( 'disabled' );

				wptouchAdminAjax( 'download-icon-set', ajaxParams, function( result ) {
					if ( result == '1' ) {
						// Succeeded
						pressedButton.parent().find( '.installed' ).show();
						pressedButton.hide();
					} else {
						// Failed
						pressedButton.parent().find( '.error' ).show();
						pressedButton.hide();
					}
				});

				e.preventDefault();
			});
		});
	}
}

function wptouchHandleResetSettings() {
	jQuery( '#reset, #erase-settings' ).click( function( e ) {
		if ( !confirm( WPtouchCustom.reset_settings ) ) {
			e.preventDefault();
		}
	});

	jQuery( '#erase-and-delete' ).click( function( e ) {
		if ( !confirm( WPtouchCustom.reset_delete ) ) {
			e.preventDefault();
		}
	});

	jQuery( '#erase-delete-deactivate' ).click( function( e ) {
		if ( !confirm( WPtouchCustom.reset_delete_deactivate ) ) {
			e.preventDefault();
		}
	});
}

function wptouchThemesAddonsAjaxInstall() {
	var canAjaxInstall = typeof new XMLHttpRequest().responseType === 'string';

	if ( canAjaxInstall ) {
		jQuery( '.cloud-update-issue' ).remove();
		jQuery( '#section-updates-available' ).addClass( 'ajax-install' );
	}

	jQuery( '.action-buttons.no-install, #section-updates-available' ).each( function() {
		if ( jQuery( 'a', this ).length == 2 ) {
			jQuery( '.activate', this ).hide();
		}

		if ( canAjaxInstall ) {
			jQuery( '.download', this ).text( WPtouchCustom.install ).addClass( 'ajax-install' );
		}
	});

	jQuery( '.action-buttons.no-install' ).on( 'click', 'a.ajax-install', function(e){
		if ( jQuery( this ).hasClass( 'theme' ) ) { fileType = 'theme'; } else { fileType = 'extension'; }
		downloadLink = jQuery( this ).attr( 'href' );
		wptouchAdminAjaxInstall( this, fileType, downloadLink, wptouchEnableDownload, wptouchRemoveAjaxInstall );
		e.preventDefault();
	});

	jQuery( '#section-updates-available' ).on( 'click', 'a.ajax-install', function(e){
		if ( jQuery( this ).hasClass( 'theme' ) ) { fileType = 'theme'; } else { fileType = 'extension'; }
		downloadLink = jQuery( this ).attr( 'href' );
		wptouchAdminAjaxInstall( this, fileType, downloadLink, false, wptouchRemoveAjaxInstall );
		e.preventDefault();
	});
}

function wptouchEnableDownload( targetButton ) {
	jQuery( targetButton ).prev().show().end().remove();
}

function wptouchRemoveAjaxInstall( targetButton ) {
	jQuery( targetButton ).removeClass( 'ajax-install' ).text( WPtouchCustom.download );
}

function wptouchLoadThemes() {
	jQuery( '#setup-themes-browser' ).on( 'click', 'a.button.install, a.button.upgrade', function( e ) {
		var pressedButton = jQuery( this );
		var installURL = jQuery( this ).attr( 'data-url' );
		var basePath = jQuery( this ).attr( 'data-name' );

		var loadingText = jQuery( this ).attr( 'data-loading-text' );

		var ajaxParams = {
			url: installURL,
			base: basePath
		};

		pressedButton.html( loadingText ).addClass( 'disabled' );

		wptouchAdminAjax( 'download-theme', ajaxParams, function( result ) {
			ourResult = jQuery.parseJSON( result );
			if ( ourResult.status == '1' ) {
				// Succeeded
				wptouchAdminTriggerReload();
			} else {
				var str = WPtouchCustom.cloud_download_fail;
				alert( str.replace( '%reason%', ourResult.error ) );
			}
		});

		e.preventDefault();
	});
}

function wptouchLoadAddons() {
	jQuery( '#setup-addons-browser' ).on( 'click', 'a.install, a.upgrade', function( e ) {
		var pressedButton = jQuery( this );
		var installURL = jQuery( this ).attr( 'data-url' );
		var basePath = jQuery( this ).attr( 'data-name' );

		var loadingText = jQuery( this ).attr( 'data-loading-text' );

		var ajaxParams = {
			url: installURL,
			base: basePath
		};

		var oldText = pressedButton.html();
		pressedButton.html( loadingText ).addClass( 'disabled' );

		wptouchAdminAjax( 'download-addon', ajaxParams, function( result ) {
			ourResult = jQuery.parseJSON( result );
			if ( ourResult.status == '1' ) {
				// Succeeded
				location.reload( true );
			} else {
				var str = WPtouchCustom.cloud_download_fail;
				alert( str.replace( '%reason%', ourResult.error ) );

				pressedButton.html( loadingText ).removeClass( 'disabled' ).html( oldText );
			}
		});

		e.preventDefault();
	});
}

function wptouchHandleMultilineFields() {

	var multiLine = jQuery( '.multiline' );

	multiLine.on( 'wptouch-rebuild-value', function() {
		var values = [];

		jQuery( 'li', this ).each( function() {
			values.push( jQuery( this ).data( 'text-value' ) );
		});

		if ( jQuery( this ).hasClass( 'comma' ) ) {
			var glue = ',';
		} else {
			var glue = '\n';
		}

		jQuery( 'textarea', this ).val( values.join( glue ) );
	});

	multiLine.find( 'li' ).each( function() {
		jQuery( this ).data( 'text-value', jQuery( this ).text() ).append( '<a href="#" class="remove icon-cancel-circled"></a>');
	});

	multiLine.on( 'click', 'a.remove', function( e ) {
		e.preventDefault();
		multiline_group = jQuery( this ).parents( '.multiline' );
		jQuery( this ).parent().remove();
		multiline_group.trigger( 'wptouch-rebuild-value' );
		if ( jQuery( 'li', multiline_group ).length == 0 ) {
			jQuery( 'ul', multiline_group ).remove();
		}
	});

	multiLine.on( 'click', 'a.add', function( e ) {
		e.preventDefault();
		new_value = jQuery( this ).siblings( 'input' ).val();
		if ( new_value != '' ) {

			// create UL if it doesn't exist
			parent = jQuery( this ).parents( '.multiline' );
			if ( parent.has( 'ul' ).length == 0 ) {
				parent.append( '<ul></ul>' );
			}

			new_element = jQuery( '<li>' + new_value + '<a href="#" class="remove icon-cancel-circled"></a></li>').data( 'text-value', new_value );
			jQuery( this ).parents( '.multiline' ).find( 'ul' ).append( new_element );

			multiline_group = jQuery( this ).parents( '.multiline' );
			multiline_group.trigger( 'wptouch-rebuild-value' );

			jQuery( this ).siblings( 'input' ).val( '' );
		}
	});

	// trap enter keys and make them add the item instead of returning on the whole form
	jQuery( 'input.add-entry' ).keydown( function( e ) {
		if( e.which && e.which == 13 || e.keyCode && e.keyCode == 13 ) {
			e.preventDefault();
			jQuery( this ).next( 'a.add' ).click();
			return false;
		}
	});
}

function wptouchHandleCustomizerLink() {
	jQuery( '#foundation-page-theme-customizer' ).click( 'a', function( e ) {
		jQuery.cookie( 'wptouch_customizer_mode', 'mobile', { expires: 0, path: '/' } );
	});
}

function wptouchThemesExtensionsPanels(){

	// Themes Panels
	var themeSlideView = jQuery( '.theme-panels' ).css( 'visibility', 'visible' ).simpleSlideView({
		duration: 400,
		scrollToContainerTop: false,
		easing: 'easeInOutCubic'
	});

	// Extension Panels
	var extensionlideView = jQuery( '.extension-panels' ).css( 'visibility', 'visible' ).simpleSlideView({
		duration: 400,
		scrollToContainerTop: false,
		easing: 'easeInOutQuint'
	});

	jQuery( 'a.setup-themes-browser' ).on( 'click', function(){
		themeSlideView.popView( '#main-theme-panel' );
		jQuery( 'html, body' ).animate({ scrollTop: '0' }, 400 );
	});

	jQuery( 'a.setup-addons-browser' ).on( 'click', function(){
		extensionlideView.popView( '#main-extension-panel' );
		jQuery( 'html, body' ).animate({ scrollTop: '0' }, 400 );
	});
}

function wptouchSetupOldUploaders() {
	if ( jQuery( '.uploader' ).length ) {

		jQuery( '.uploader' ).each( function() {
			var thisUploader = jQuery( this );
			var baseId = jQuery( this ).find( 'button.upload' ).parent().attr( 'id' );
			var settingName = jQuery( '#' + baseId + '_upload' ).attr( 'data-esn' );
			var deleteButton = jQuery( '#' + baseId ).find( 'button.delete' );
			var uploader = new AjaxUpload( baseId + '_upload', {
		    	action: ajaxurl,
		    	allowedExtensions: [ 'png' ],
				debug: false,
				data: {
					action: 'upload_file',
					file_type: 'homescreen_image',
					setting_name: settingName,
					wp_nonce: WPtouchCustom.admin_nonce
				},
				name: 'myfile',
				onSubmit: function( fileName, extension ) {
					thisUploader.find( '.progress .bar' ).css( 'width', '20%' );
					thisUploader.find( '.progress' ).show();
				},
				onComplete: function( fileName, response ) {
					// success
					if ( response != 'invalid image' ) {
						thisUploader.find( '.progress .bar' ).css( 'width', '100%' );
						thisUploader.find( '.progress' ).removeClass( 'bar-fail' ).addClass( 'bar-success' );
						setTimeout( function() {
							thisUploader.find( '.image-placeholder' ).append( '<img src="' + response + '" />');
							deleteButton.fadeIn( 'fast' );
							thisUploader.find( '.progress' ).hide();
						},
						1500 );
					} else {
						// failed
						thisUploader.find( '.progress .bar' ).css( 'width', '50%' );
						thisUploader.find( '.progress' ).attr( 'title', WPtouchCustom.upload_invalid );
						thisUploader.find( '.progress' ).attr( 'data-original-title', WPtouchCustom.upload_invalid );
						thisUploader.find( '.progress' ).removeClass( 'bar-success' ).addClass( 'bar-fail' );
					}
					//cleanup & reset
					setTimeout( function() {
						thisUploader.find( '.progress .bar' ).removeClass( 'bar-fail bar-success' );
						thisUploader.find( '.progress' )
							.attr( 'title', WPtouchCustom.upload_complete )
							.attr( 'data-original-title', WPtouchCustom.upload_complete );
					},
					3500 );
				},
				onCancel: function( id, fileName ) {},
				showMessage: function( message ) {
				}
			});

			jQuery( '#' + baseId + '_upload' ).on( 'click', function( e ) {
				jQuery( '#' + baseId + '_spot' ).trigger( 'click' );
				e.preventDefault();
			});

			deleteButton.on( 'click', function( e ) {
				var deleteButton = jQuery( this );
				var placeHolder = jQuery( this ).parent().find( '.image-placeholder' );
				placeHolder.find( 'img' ).remove();

				var baseId = jQuery( this ).parent().attr( 'id' );
				var settingName = jQuery( '#' + baseId + '_upload' ).attr( 'data-esn' );

				var ajaxParams = {
					setting_name: settingName
				};

				wptouchAdminAjax( 'delete-image-upload', ajaxParams, function( result ) {
					if ( result == 0 ) {

						deleteButton.fadeOut( 'fast' );
					}
				});

				e.preventDefault();
			});
		});
	}
}

function wptouchSetupFreeSettings(){

	if ( jQuery( '.wptouch-free' ).length ) {
		// Sharing Links on/off
		var sharingCheckbox = jQuery( '[id$=show_share]' );
		sharingCheckbox.on( 'change', 'input', function(){
			if ( jQuery( this ).is( ':checked' ) ) {
				sharingCheckbox.nextAll( 'li' ).show();
			} else {
				sharingCheckbox.nextAll( 'li' ).hide();
			}
		}).change();

		// Featured Slider on/off
		var featuredCheckbox = jQuery( '[id$=featured_enabled]' );
		var ignoreItems = '#setting-featured_tag, #setting-featured_category, #setting-featured_post_ids';
		featuredCheckbox.on( 'change', 'input', function(){
			if ( jQuery( this ).is( ':checked' ) ) {
				featuredCheckbox.nextAll( 'li' ).not( ignoreItems ).show();
			} else {
				featuredCheckbox.nextAll( 'li' ).not( ignoreItems ).hide();
			}
		}).change();

		// Featured slider source select
		jQuery( '[id$="featured_type"]' ).on( 'change', 'select', function() {
			var tagSetting = jQuery( '[id$=featured_tag]' );
			var catSetting = jQuery( '[id$=featured_category]' );
			var posttySetting = jQuery( '[id$=featured_post_type]' );
			var postSetting = jQuery( '[id$=featured_post_ids]' );

			switch( jQuery( this ).val() ) {
				case 'tag':
					tagSetting.show();
					catSetting.hide();
					posttySetting.hide();
					postSetting.hide();
				break;
				case 'category':
					tagSetting.hide();
					catSetting.show();
					posttySetting.hide();
					postSetting.hide();
				break;
				case 'post_type':
					tagSetting.hide();
					catSetting.hide();
					posttySetting.show();
					postSetting.hide();
				break;
				case 'posts':
					tagSetting.hide();
					catSetting.hide();
					posttySetting.hide();
					postSetting.show();
				break;
				case 'latest':
				default:
					tagSetting.hide();
					catSetting.hide();
					posttySetting.hide();
					postSetting.hide();
				break;
			}
		}).change();

		// Featured Thumbs on/off
		var featuredThumb = jQuery( '[id$=use_thumbnails]' );
		featuredThumb.on( 'change', 'select', function(){
			var thumbType = jQuery( '[id$=thumbnail_type]' );

			switch( jQuery( this ).val() ) {
				case 'none':
					thumbType.hide();
				break;
				default:
					thumbType.show();
				break;

			}
		}).change();

		// Featured Thumb Type on/off
		var featuredThumbType = jQuery( '[id$=thumbnail_type]' );
		featuredThumbType.on( 'change', 'select', function(){
			var thumbCustomType = jQuery( '[id$=thumbnail_custom_field]' );

			switch( jQuery( this ).val() ) {
				case 'custom_field':
					thumbCustomType.show();
				break;
				default:
					thumbCustomType.hide();
				break;

			}
		}).change();
	}
}

function wptouchAdminMultisite(){
	if ( jQuery( '#multisite_deployment_source' ).is( 'select' ) ) {
		jQuery( '#multisite-select-all' ).on( 'click', function( e ){
			jQuery( '#multisite-deploy-sites input:not(input:disabled)' ).prop( 'checked', 'checked' );
			e.preventDefault();
		});

		jQuery( '#multisite-select-none' ).on( 'click', function( e ){
			jQuery( '#multisite-deploy-sites input' ).prop( 'checked', '' );
			e.preventDefault();
		});

		var deployment_modal = jQuery('[data-remodal-id=modal-deployment]').remodal();

		jQuery( '#multisite-deploy' ).on( 'click', function( e ){
			e.preventDefault();

			if ( jQuery( '#multisite-deploy-sites input' ).is( ':checked' ) ) {
				jQuery( '.deploy-to-sites' ).show();
				jQuery( '.icon-ok-circle, .button-close' ).remove();
				jQuery( 'p.deploy-text' ).text( jQuery( 'p.deploy-text' ).attr( 'data-text' ) );
				deployment_modal.open();

			}
		});

		jQuery( '#multisite_deployment_source' ).change( function(){
			// what's the current selected site?
			var currentSite = jQuery( this ).val();
			// reset all checkboxes to enabled
			jQuery( '#multisite-deploy-sites input' ).attr( 'disabled', false );
			// disabled the selected site's checkbox and uncheck it
			var destinationSite = jQuery( '#multisite-deploy-sites' ).find( 'input#site-' + currentSite );
			destinationSite.prop( 'checked', false ).attr( 'disabled', true );
		}).change();

		jQuery( '.deploy-to-sites' ).on( 'click', function( e ){
			jQuery( this ).hide();
			e.preventDefault();

			var sourceSite = jQuery( '#multisite_deployment_source' ).val();
			var deployGeneral = jQuery( "#multisite_deploy_general_settings" ).is( ":checked" );
			var deployCompat = jQuery( "#multisite_deploy_site_compat" ).is( ":checked" );
			var deployDevices = jQuery( "#multisite_deploy_devices" ).is( ":checked" );
			var deployMenus = jQuery( "#multisite_deploy_menus" ).is( ":checked" );
			var deployThemes = jQuery( "#multisite_deploy_themes" ).is( ":checked" );
			var deployExtensions = jQuery( "#multisite_deploy_extensions" ).is( ":checked" );
			var deployColors = jQuery( "#multisite_deploy_colors" ).is( ":checked" );
			var deploySocialMedia = jQuery( "#multisite_deploy_social_media" ).is( ":checked" );
			var deploySocialSharing = jQuery( "#multisite_deploy_social_sharing" ).is( ":checked" );

			var sites = [];

			jQuery( '#multisite-deploy-sites input' ).each( function( index, value ) {
				if ( jQuery( this ).is( ":checked" ) ) {
					sites.push( jQuery( this ).attr( 'id' ) );
				}
			});

			var ajaxParams = {
				source_site: 			sourceSite,
				deploy_general: 		deployGeneral ? '1' : '0',
				deploy_compat: 			deployCompat ? '1' : '0',
				deploy_devices: 		deployDevices ? '1' : '0',
				deploy_menus: 			deployMenus ? '1' : '0',
				deploy_themes: 			deployThemes ? '1' : '0',
				deploy_extensions: 		deployExtensions ? '1' : '0',
				deploy_colors: 			deployColors ? '1' : '0',
				deploy_social_media: 	deploySocialMedia ? '1' : '0',
				deploy_social_sharing: 	deploySocialSharing ? '1' : '0',
				deploy_sites: 			sites
			};

			jQuery( 'p.deploy-text' ).text( jQuery( 'p.deploy-text' ).attr( 'data-deploying-text' ) );

			wptouchAdminAjax( 'multisite_deploy', ajaxParams, function( result ) {
				setTimeout( function(){
					jQuery( 'p.deploy-text' )
					.text( jQuery( 'p.deploy-text' ).attr( 'data-completed-text' ) )
					.prepend( '<i class="icon-ok-circle"></i>' )
					.append( '<br /><br /><button class="button button-close" data-remodal-action="cancel">Close</button>' );
				}, 1000 );
			});
		});
	}
}


function wptouchControlReturn(){
	// Intercept enter key, which strangely causes the first button in the DOM to be pressed
	// in our case this might result in a backup file download!
    jQuery( 'input' ).keypress( function ( e ) {
        if ( ( e.which && e.which == 13 ) || ( e.keyCode && e.keyCode == 13 ) ) {
            return false;
        }
    });
}

// Handy helper function to add Checkbox + target element(s) toggles
function wptouchCheckToggle( checkBox, toggleElements ) {
	if ( jQuery( checkBox ).prop( 'checked' ) ) {
		jQuery( toggleElements ).show();
	} else {
		jQuery( toggleElements ).hide();
	}
	jQuery( checkBox ).on( 'change', function() {
		if ( jQuery( checkBox ).prop( 'checked' ) ) {
			jQuery( toggleElements ).animate( {
				height: 'toggle',
				opacity: 'toggle'
			}, 230 );
		} else {
			jQuery( toggleElements ).hide();
		}
	});
}

function wptouchUpdateAll() {
	jQuery( '#setting-theme-extension-updates-available' ).on( 'click', 'button', function( e ) {
		e.preventDefault();
		jQuery( this ).text( jQuery( this ).attr( 'data-loading-text' ) ).attr( 'disabled', true ).addClass( 'disabled' );
		wptouchAdminAjax( 'update-themes-addons', {}, function( result ) {
			ourResult = jQuery.parseJSON( result );
			if ( ourResult.status == '1' ) {
				// Succeeded
				location.reload( true );
			} else {
				var str = WPtouchCustom.cloud_download_fail;
				alert( str.replace( '%reason%', ourResult.error ) );
				jQuery( this ).attr( 'disabled', false ).removeClass( 'disabled' );
			}
		});
	});
}

function wptouchAddPlaceholders(){
	jQuery( '#wptouch-settings-content input[type="text"]' ).not( '#license-settings-area input[type="text"]' ).each( function(){
		var placeholder = jQuery( this ).parents( 'li' ).find( 'span' ).not( '.pro' ).text();
		jQuery( this ).attr( 'placeholder', placeholder );
	});
}

function wptouchTriggerSave( callback ) {
	// Will stop reloads during saving
	var saving = true;
	window.onbeforeunload = function(){
		if ( saving ) {
			return WPtouchCustom.saving_settings;
		}
	};

	// Animate the admin spinner in
	jQuery( '#admin-spinner' ).animate({
			opacity: 1
	}, 330 );

	jQuery.ajax( {
		method: 'POST',
		data: 'wptouch-submit-3=1&' + jQuery( '#wptouch-settings-form' ).serialize(),
		success: function() {
			// Done saving, allow reload
			saving = false;
			// Animate the admin spinner out
			jQuery( '#admin-spinner' ).animate({
				opacity: 0
			}, 330 );

			if ( typeof( callback ) !== 'undefined' ) {
				callback();
				return false;
			}
		},
		cache: false
	});
}

function wptouchAdminSetupSave(){

	var wptouchAdminForm = jQuery( '#wptouch-settings-form' );

	if ( wptouchAdminForm.length ) {

		// text inputs, debounced to save after 250 millisecond delay
			var debounceSave = wptouchAdminDebounce( function(){
				if ( !jQuery( this ).hasClass( 'no-save' ) ){
					wptouchTriggerSave();
				}
			}, 1000 );

		jQuery( window ).load( function(){
			// Toggles, textareas & selects

			wptouchAdminForm.on( 'change.autosave keyup.autosave', 'input[type="checkbox"]:not(.no-save):not(#translate_admin):not(#multisite_control):not(#wptouch-addon-deployment input), textarea, select:not(#force_locale):not(#force_network_locale):not(#wptouch-addon-deployment select), input[type="text"]:not(.add-entry,.license-inputs)', debounceSave );

			// Multiline / Newline
			jQuery( '.multiline' ).on( 'click', 'a.add, a.remove', debounceSave );

		});

	}

	// Handle special case of saving setting relatedto the admin language
	var languageSelect = jQuery( '#force_locale, #force_network_locale' );
	var languageInAdmin = jQuery( '#translate_admin' );
	var networkControl = jQuery( '#multisite_control' );

	languageSelect.on( 'change', function(){
		if ( languageInAdmin.is( ':checked' ) ) {
			wptouchTriggerSave( wptouchAdminTriggerReload );
		} else {
			wptouchTriggerSave();
		}
	});

	languageInAdmin.on( 'change', function(){
		wptouchTriggerSave( wptouchAdminTriggerReload );
	});

	networkControl.on( 'change', function(){
		wptouchTriggerSave( wptouchAdminTriggerReload );
	});
}

function wptouchAdminTriggerReload(){
	// Reload and refresh the cache
	window.location.reload();
}

function wptouchAdminDebounce( func, wait, immediate ) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if ( !immediate ) func.apply( context, args );
		};
		var callNow = immediate && !timeout;
		clearTimeout( timeout );
		timeout = setTimeout( later, wait );
		if ( callNow ) func.apply( context, args );
	};
};

function showPreviewWindow(){
	var width = '375', height = '667';
	topPosition = ( screen.height ) ? ( screen.height - height ) / 2:0;
	leftPosition = ( screen.width ) ? ( screen.width - width ) / 2:0;
	options = 'scrollbars=no, titlebar=no, status=no, menubar=no';
	previewUrl = jQuery( 'input#wptouch-preview-theme' ).attr( 'data-url' );
	wptouchPreviewWindow = window.open( previewUrl, 'preview', 'width=' + width + ', height=' + height + ',' + options + ', top=' + topPosition + ',left=' + leftPosition + '' );
}

function showCustomizerWindow(){
	var width = '800', height = '500';
	topPosition = ( screen.height ) ? ( screen.height - height ) / 2:0;
	leftPosition = ( screen.width ) ? ( screen.width - width ) / 2:0;
	options = 'scrollbars=no, titlebar=no, status=no, menubar=no';
	windowUrl = '//wptouch-pro-4.s3.amazonaws.com/free/free-customizer-promo.html';
	wptouchCustomizerWindow = window.open( windowUrl, 'customizermsg', 'width=' + width + ', height=' + height + ',' + options + ', top=' + topPosition + ',left=' + leftPosition + '' );
}


// The Preview Pop-Up Window
function wptouchPreviewWindow(){

	jQuery( 'input#wptouch-preview-theme' ).on( 'click', function( e ) {		
		showPreviewWindow();
		e.preventDefault();
	});
}

function wptouchCreateForm() {
    var $ = jQuery,
        $formSubmit = $( '.js-form-submit' );

    $formSubmit.click( function(event) {
        event.preventDefault();

        var $createForm = $( this ).parents( '.js-create-form' ),
            $createdForm = $( '<form></form>' ).hide(),
            valid = true;

        $createForm.find( '.js-required' ).each( function( index, item ) {
            var $this = $( item );
            if ( '' === $this.val() ) {
                $this.css( { 'border' : '1px solid red' } );
                valid = false;
            }
        } );

        if (! valid) {
            return;
        }

        $createdForm.attr( 'action', $createForm.data( 'form-action' ) );
        $createdForm.attr( 'method', $createForm.data( 'form-method' ) );
        $createdForm.html( $createForm.clone() );

        $('body').append( $createdForm );
        $createdForm.submit();
    } );
}

function wptouchAdminReady() {

	wptouchDownloadUploadSettings();

	wptouchSetupAdminMenu();

	wptouchTooltipSetup();

	wptouchSetupSelects();

	wptouchAddPlaceholders();

	wptouchHandleCustomIconUpload();

	wptouchHandlePluginCompat();

	wptouchLoadUpgradeArea();

	wptouchHandleDownloadIconSets();

	wptouchLoadThemes();

	wptouchUpdateAll();

	wptouchLoadAddons();

	wptouchHandleMultilineFields();

	wptouchHandleCustomizerLink();

	wptouchThemesExtensionsPanels();

	wptouchHandleResetSettings();

	wptouchAdminMultisite();

	wptouchControlReturn();

	wptouchSetupOldUploaders();

	wptouchSetupFreeSettings();

	wptouchSetupSettingsToggles();

	wptouchAdminSetupSave();

	wptouchThemesAddonsAjaxInstall();

	wptouchPreviewWindow();

    wptouchCreateForm();
}

jQuery( document ).ready( function() {
	wptouchAdminReady();
});
