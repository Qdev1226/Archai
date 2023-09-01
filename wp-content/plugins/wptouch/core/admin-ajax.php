<?php

function wptouch_admin_handle_ajax( &$wptouch_pro, $ajax_action ) {
	switch( $ajax_action ) {
		case 'dismiss-warning':
			$wptouch_pro->check_plugins_for_warnings();
			$settings = $wptouch_pro->get_settings();
			if ( $wptouch_pro->post['plugin'] ) {
				if ( !in_array( $wptouch_pro->post['plugin'], $settings->dismissed_warnings ) ) {
					$settings->dismissed_warnings[] = $wptouch_pro->post['plugin'];

					$settings->save();
				}
			}

			echo wptouch_get_plugin_warning_count();
			break;
		case 'delete-image-upload':
			if ( isset( $wptouch_pro->post[ 'setting_name' ] ) ) {
				$wptouch_pro->update_encoded_setting( $wptouch_pro->post[ 'setting_name'], false );
				echo '0';
			}
			break;
		case 'delete-custom-icon':
			if ( current_user_can( 'upload_files' ) ) {
				if ( isset( $wptouch_pro->post[ 'icon_name' ] ) ) {
					$icon_location = WPTOUCH_CUSTOM_ICON_DIRECTORY . '/' . $wptouch_pro->post[ 'icon_name' ];

					unlink( $icon_location );
					echo '0';
				}
			}

			break;
		case 'load-plugin-compat-list':
			$wptouch_pro->generate_plugin_hook_list( true );

			$compat_settings = wptouch_get_settings( 'compat' );
			if ( is_array( $compat_settings->plugin_hooks ) && count( (array) $compat_settings->plugin_hooks ) ) {
				$changed = false;
				foreach( $compat_settings->plugin_hooks as $name => $value ) {
					if ( !isset( $compat_settings->enabled_plugins[ $name ] ) ) {
						$compat_settings->enabled_plugins[ $name ] = 1;
						$changed = true;
					}
				}

				if ( $changed ) {
					$compat_settings->save();
				}
			}

			echo wptouch_capture_include_file( WPTOUCH_DIR . '/admin/settings/html/plugin-compat-ajax.php' );
			break;
		case 'prep-settings-download':
			require_once( WPTOUCH_DIR . '/core/admin-backup-restore.php' );
			$backup_file = wptouch_backup_settings();

			echo $backup_file;
			break;
		case 'load-upgrade-area':
			$content = wp_remote_get( 'http://wptouch-pro-4.s3.amazonaws.com/free-upgrade-area/4.2/page.xhtml' );

			if ( !is_wp_error( $content ) ) {
				echo $content['body'];
			}

			break;
		case 'download-icon-set':
			global $wptouch_pro;

			require_once( WPTOUCH_DIR . '/core/icon-set-installer.php' );

			$icon_set_installer = new WPtouchIconSetInstaller;
			$icon_set_installer->install( $wptouch_pro->post[ 'base' ] , $wptouch_pro->post[ 'url' ] );

			if ( file_exists( WPTOUCH_BASE_CONTENT_DIR . '/icons/' . $wptouch_pro->post[ 'base' ] ) ) {
				echo '1';
			} else {
				echo '0';
			}

			break;
		case 'get-icon-set-info':
			require_once( WPTOUCH_DIR . '/core/admin-icons.php' );

			echo wptouch_capture_include_file( WPTOUCH_DIR . '/admin/settings/html/installed_icon_sets_ajax.php' );
			break;
		case 'admin-change-log':
			if ( !defined( 'WPTOUCH_IS_FREE' ) ) {
				$change_log = wp_remote_get( WPTOUCH_PRO_README_FILE );
			} else {
				$change_log = wp_remote_get( 'http://plugins.svn.wordpress.org/wptouch/trunk/readme.txt' );
			}

			if ( !is_wp_error( $change_log ) ) {

				$content = $change_log[ 'body' ];

				$result = preg_match_all( "#= Version (.*) =(.*)\n=#iUs", $content, $matches );

				if ( $result ) {
					$entries = count( (array) $matches[0] );

					for ( $i = 0; $i < $entries; $i++) {
						echo '<h4 style="font-family: Helvetica, sans-serif">' . sprintf( __( 'Version %s', 'wptouch-pro' ), $matches[1][$i] ) . '</h4><ul  style="font-family: Helvetica, sans-serif; font-size: 13px">';
						echo str_replace( '* ', '<li style="padding-top:3px;padding-bottom:3px;">', str_replace( "\n", "</li>\n", $matches[2][$i] ) );
						echo '</ul>';
					}
				}
			} else {
				echo __( 'There is a temporary issue retrieving the change-log.  Please try again later.', 'wptouch-pro' );
			}
			break;
		case 'load-addon-browser':
			require_once( WPTOUCH_DIR . '/admin/settings/html/extension-browser-ajax.php' );
			break;
		case 'repair-active-theme':
			$result = wptouch_repair_active_theme_from_cloud( $errors );

			if ( wptouch_migration_is_theme_broken() ) {
				echo '0';
			} else {
				echo '1';
			}
			break;
		case 'wizard-language':
			$settings = $wptouch_pro->get_settings();
			if ( $wptouch_pro->post[ 'force_locale' ] && $settings->force_locale != $wptouch_pro->post[ 'force_locale' ] ) {
				$settings->force_locale = $wptouch_pro->post[ 'force_locale' ];
				$settings->save();
				echo '1';
			} elseif ( $wptouch_pro->post[ 'force_network_locale' ] && $settings->force_network_locale != $wptouch_pro->post[ 'force_network_locale' ] ) {
				$settings->force_network_locale = $wptouch_pro->post[ 'force_network_locale' ];
				$settings->save();
				echo '1';
			} else {
				echo '0';
			}
			break;
		case 'wizard-update-extensions':
			$result = json_decode( wptouch_update_all_addons() );
			echo $result[ 'status' ];
			break;
		case 'wizard-theme':
			if ( isset( $wptouch_pro->post[ 'theme' ] ) ) {
				wptouch_activate_theme( $wptouch_pro->post[ 'theme' ] );
				echo '1';
			} else {
				echo '0';
			}
			break;
		case 'wizard-extensions':
			if ( isset( $wptouch_pro->post[ 'extensions' ] ) ) {
				foreach( $wptouch_pro->post[ 'extensions' ] as $extension ) {
					wptouch_activate_addon( $extension );
				}
				echo '1';
			} else {
				echo '0';
			}
			break;
		case 'wizard-pages':
			$settings = $wptouch_pro->get_settings();
			if ( get_option( 'show_on_front' ) == 'page' && isset( $wptouch_pro->post[ 'homepage_redirect_wp_target' ] ) ) {
				$settings->homepage_landing = 'select';
				$settings->homepage_redirect_wp_target = $wptouch_pro->post[ 'homepage_redirect_wp_target' ];
			}
			$settings->save();
			$settings = $wptouch_pro->get_settings( 'foundation' );
			$settings->latest_posts_page = $wptouch_pro->post[ 'latest_posts_page' ];
			$settings->save();
			echo '1';
			break;
		case 'wizard-wptouch_message':
			$settings = $wptouch_pro->get_settings();
			if ( $wptouch_pro->post[ 'show_wptouch_in_footer' ] == 'true' ) { $wptouch_pro->post[ 'show_wptouch_in_footer' ] = 1; } else { $wptouch_pro->post[ 'show_wptouch_in_footer' ] = 0; }
			if ( isset( $wptouch_pro->post[ 'show_wptouch_in_footer' ] ) && $settings->show_wptouch_in_footer != $wptouch_pro->post[ 'show_wptouch_in_footer' ] ) {
				$settings->show_wptouch_in_footer = $wptouch_pro->post[ 'show_wptouch_in_footer' ];
				$settings->save();
				echo '1';
			} else {
				echo '0';
			}
			break;
		case 'wizard-scan_for_analytics':
		 	$result = wp_remote_get( home_url() );

		 	$result_info = array();

		 	if ( $result && is_array( $result ) && isset( $result[ 'body' ] ) ) {
		 		if ( preg_match_all( '#(<script>.*</script>)#iUs', $result[ 'body' ], $match ) ) {
		 			foreach( $match[0] as $possible_analytics ) {
		 				$search_for = array( 'GoogleAnalyticsObject' );

		 				foreach( $search_for as $search_phrase ) {
			 				if ( preg_match( '#' . $search_phrase . '#iU', $possible_analytics, $ga_match ) ) {
		 						// Found Google Analytics code
		 						$result_info[ 'code' ] = 'found';
		 						$result_info[ 'success' ] = sprintf( __( 'Code found! %s Analytics was automatically configured for you.', 'wptouch-pro' ), '<br />' );
		 						$result_info[ 'fragment' ] = htmlentities( $possible_analytics );

		 						// If Google, retrieve the site ID
		 						if ( $search_phrase == 'GoogleAnalyticsObject' ) {
		 							preg_match( '/\'(UA-.*?)\'/s', $possible_analytics, $id_match );
		 							if ( count( (array) $id_match ) == 2 ) {
		 								$result_info[ 'site_id' ] = $id_match[ 1 ];
		 							}
		 						}
		 						break;
		 					}
		 				}
		 			}
		 		}
		 	}

		 	if (  is_array( $result ) && !isset( $result_info[ 'code' ] ) ) {
		 		$result_info[ 'code' ] = 'noresult';
		 		$result_info[ 'msg' ] = __( 'Unable to find your Google Analytics code. You can enter it manually in the settings later.', 'wptouch-pro' );
		 	}

		 	echo json_encode( $result_info );
			break;
		case 'wizard-analytics':
			$settings = $wptouch_pro->get_settings();
			if ( $wptouch_pro->post[ 'analytics_google_id' ] ) {
				$settings->analytics_google_id = $wptouch_pro->post[ 'analytics_google_id' ];
				$settings->analytics_embed_method = 'simple';
				$settings->save();
			}
			break;
		case 'wizard-multisite':
			$settings = $wptouch_pro->get_settings( 'bncid' );
			if ( $wptouch_pro->post[ 'multisite_control' ] == 'true' ) { $wptouch_pro->post[ 'multisite_control' ] = 1; } else { $wptouch_pro->post[ 'multisite_control' ] = 0; }
			$settings->multisite_control = $wptouch_pro->post[ 'multisite_control' ];
			$settings->save();
			break;
		case 'network-wizard-complete':
			$settings = $wptouch_pro->get_settings();
			$settings->show_network_wizard = false;
			$settings->save();
			break;
		case 'wizard-complete':
			$settings = $wptouch_pro->get_settings();
			if ( defined( 'WPTOUCH_IS_FREE' ) ) {
				$settings->show_free_wizard = false;
			} else {
				$settings->show_wizard = false;
			}
			$settings->save();
			break;
		case 'activate-license-key':
			$email = $wptouch_pro->post['email'];
			$key = $wptouch_pro->post['key'];

			$settings = wptouch_get_settings( 'bncid' );
			$old_settings = $settings;

			$settings->bncid = $email;
			$settings->wptouch_license_key = $key;

			WPTOUCH_DEBUG( WPTOUCH_INFO, "Attempting site activation with email [" . $email . "] and key [" . $key . "]" );

			$settings->save();

			$wptouch_pro->bnc_api = false;
			$wptouch_pro->setup_bncapi( $email, $key, true );

			// let's try to activate the license
			$wptouch_pro->activate_license();

			// Check to see if the credentials were valid
			if ( $wptouch_pro->bnc_api->response_code >= 406 && $wptouch_pro->bnc_api->response_code <= 408 ) {
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation response code was [" . $wptouch_pro->bnc_api->response_code . "]" );
				echo '2';
			} else if ( $wptouch_pro->bnc_api->server_down ) {
				// Server is unreachable for some reason
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation response code was [SERVER UNREACHABLE]" );
				echo '4';
			} else if ( $wptouch_pro->bnc_api->verify_site_license() ) {
				// Activation successful
				WPTOUCH_DEBUG( WPTOUCH_WARNING, "Activation successful, response code was [" . $wptouch_pro->bnc_api->response_code . "]" );

				$settings = wptouch_get_settings( 'bncid' );

				$settings->license_accepted = 1;
				$settings->license_accepted_time = time();

				$settings->save();

				echo '1';
			} else {
				$bnc_info = $wptouch_pro->bnc_api->check_api();

				if ( isset( $bnc_info[ 'license_expired' ] ) && $bnc_info[ 'license_expired' ] ) {
					WPTOUCH_DEBUG( WPTOUCH_WARNING, "Failure: license is expired [" . $wptouch_pro->bnc_api->response_code . "]" );
					echo '5';
				} else {
					// No more licenses - might be triggered another way
					WPTOUCH_DEBUG( WPTOUCH_WARNING, "Failure: activation response code was [" . $wptouch_pro->bnc_api->response_code . "]" );
					echo '3';
				}
			}
			break;
		case 'go-pro':
			$result = wptouch_free_go_pro();
			echo $result;
			break;
		case 'multisite_deploy':
			$source_site = $wptouch_pro->post[ 'source_site' ];

			$current_blog_id = get_current_blog_id();

			// Switch to the source site
			if ( $current_blog_id != $source_site ) {
				switch_to_blog( $source_site );

			 	$wptouch_pro->settings_object = array();
			}

			$main_settings = $wptouch_pro->get_raw_settings( 'wptouch_pro' );
			$foundation_settings = $wptouch_pro->get_raw_settings( 'foundation' );
			$compat_settings = $wptouch_pro->get_raw_settings( 'compat' );

			$colors = foundation_get_theme_colors();
			$color_settings = array();

			// Deploy color settings
			foreach( $colors as $color ) {
				if ( !isset( $color_settings[ $color->domain ] ) ) {
					$new_settings = wptouch_get_settings( $color->domain );
					$color_settings[ $color->domain ] = $new_settings;
				}
			}

			$destination_sites = $wptouch_pro->post[ 'deploy_sites' ];
			foreach( $destination_sites as $site ) {
				$update_customizer = false;

				$real_site = str_replace( 'site-', '', $site );

				restore_current_blog();

				switch_to_blog( $real_site );

				$destination_main_settings = $wptouch_pro->get_raw_settings( 'wptouch_pro' );
				if ( !$destination_main_settings ) {
					$destination_main_settings = $wptouch_pro->get_setting_defaults( 'wptouch_pro' );
					$destination_main_settings = 'wptouch_pro';
				}

				$destination_foundation_settings = $wptouch_pro->get_raw_settings( 'foundation' );
				if ( !$destination_foundation_settings ) {
					$destination_foundation_settings = $wptouch_pro->get_setting_defaults( 'foundation' );
					$destination_foundation_settings->domain = 'foundation';
				}

				$destination_compat_settings = $wptouch_pro->get_raw_settings( 'compat' );
				if ( !$destination_compat_settings ) {
					$destination_compat_settings = $wptouch_pro->get_setting_defaults( 'compat' );
					$destination_compat_settings->domain = 'compat';
				}

				if ( $wptouch_pro->post[ 'deploy_general' ] ) {
					// Deploy general settings
					$destination_main_settings->new_display_mode = $main_settings->new_display_mode;
					$destination_main_settings->show_switch_link = $main_settings->show_switch_link;
					$destination_foundation_settings->allow_zoom = $foundation_settings->allow_zoom;
					$destination_foundation_settings->smart_app_banner = $foundation_settings->smart_app_banner;

					$destination_main_settings->analytics_embed_method = $main_settings->analytics_embed_method;
					$destination_main_settings->analytics_google_id = $main_settings->analytics_google_id;
					$destination_main_settings->custom_stats_code= $main_settings->custom_stats_code;
					$destination_main_settings->show_wptouch_in_footer = $main_settings->show_wptouch_in_footer;
				}

				if ( $wptouch_pro->post[ 'deploy_compat' ] ) {
					// Deploy compatiblitiy settings
					$destination_main_settings->process_desktop_shortcodes = $main_settings->process_desktop_shortcodes;
					$destination_main_settings->remove_shortcodes = $main_settings->remove_shortcodes;

					$destination_compat_settings->plugin_hooks = $compat_settings->plugin_hooks;
					$destination_compat_settings->enabled_plugins = $compat_settings->enabled_plugins;
				}

				if ( $wptouch_pro->post[ 'deploy_devices' ] ) {
					// Deploy device settings
					$destination_main_settings->enable_ios_phone = $main_settings->enable_ios_phone;
					$destination_main_settings->enable_android_phone = $main_settings->enable_android_phone;
					$destination_main_settings->enable_blackberry_phone = $main_settings->enable_blackberry_phone;
					$destination_main_settings->enable_firefox_phone = $main_settings->enable_firefox_phone;
					$destination_main_settings->enable_opera_phone = $main_settings->enable_opera_phone;
					$destination_main_settings->enable_windows_phone = $main_settings->enable_windows_phone;

					$destination_main_settings->enable_ios_tablet = $main_settings->enable_ios_tablet ;
					$destination_main_settings->enable_android_tablet = $main_settings->enable_android_tablet;
					$destination_main_settings->enable_windows_tablet = $main_settings->enable_windows_tablet;
					$destination_main_settings->enable_kindle_tablet = $main_settings->enable_kindle_tablet;
					$destination_main_settings->enable_blackberry_tablet = $main_settings->enable_blackberry_tablet;
					$destination_main_settings->enable_webos_tablet = $main_settings->enable_webos_tablet;

					$destination_main_settings->custom_user_agents = $main_settings->custom_user_agents;
				}

				if ( $wptouch_pro->post[ 'deploy_menus' ] ) {
					// Deploy menu settings
					$destination_main_settings->enable_parent_items = $main_settings->enable_parent_items;
					$destination_main_settings->enable_menu_icons = $main_settings->enable_menu_icons;
				}

				if ( $wptouch_pro->post[ 'deploy_themes'] ) {
					// Deploy theme settings
					$destination_main_settings->current_theme_friendly_name = $main_settings->current_theme_friendly_name;
					$destination_main_settings->current_theme_location = $main_settings->current_theme_location;
					$destination_main_settings->current_theme_name = $main_settings->current_theme_name;

					$update_customizer = true;
				}

				if ( $wptouch_pro->post[ 'deploy_extensions'] ) {
					// Deploy extension settings
					$destination_main_settings->active_addons = $main_settings->active_addons;
				}

				if ( $wptouch_pro->post[ 'deploy_colors' ] ) {
					//echo $wptouch_pro->post[ 'deploy_colors' ];
					// Deploy color settings
					$destination_color_settings = array();

					foreach( $colors as $color ) {
						if ( !isset( $destination_color_settings[ $color->domain ] ) ) {
							$new_settings = wptouch_get_settings( $color->domain );
							$destination_color_settings[ $color->domain ] = $new_settings;
						}
					}

					foreach( $colors as $color ) {
						$setting_name = $color->setting;
						$destination_color_settings[ $color->domain ]->$setting_name = $color_settings[ $color->domain ]->$setting_name;
					}

					foreach( $destination_color_settings as $settings_object ) {
						$settings_object->save();
					}

					$update_customizer = true;
				}

				if ( $wptouch_pro->post[ 'deploy_social_media' ] ) {
					// Deploy social media settings
					$destination_foundation_settings->social_facebook_url = $foundation_settings->social_facebook_url;
					$destination_foundation_settings->social_twitter_url = $foundation_settings->social_twitter_url;
					$destination_foundation_settings->social_google_url = $foundation_settings->social_google_url;
					$destination_foundation_settings->social_instagram_url = $foundation_settings->social_instagram_url;
					$destination_foundation_settings->social_tumblr_url = $foundation_settings->social_tumblr_url;
					$destination_foundation_settings->social_pinterest_url = $foundation_settings->social_pinterest_url;
					$destination_foundation_settings->social_vimeo_url = $foundation_settings->social_vimeo_url;
					$destination_foundation_settings->social_youtube_url = $foundation_settings->social_youtube_url;
					$destination_foundation_settings->social_linkedin_url = $foundation_settings->social_linkedin_url;
					$destination_foundation_settings->social_yelp_url = $foundation_settings->social_yelp_url;
					$destination_foundation_settings->social_email_url = $foundation_settings->social_email_url;
					$destination_foundation_settings->social_rss_url = $foundation_settings->social_rss_url;

					$update_customizer = true;
				}

				if ( $wptouch_pro->post[ 'deploy_social_sharing'] ) {
					// Deploy social sharing icons
					$destination_foundation_settings->show_share = $foundation_settings->show_share;
					$destination_foundation_settings->share_on_pages = $foundation_settings->share_on_pages;
					$destination_foundation_settings->share_location = $foundation_settings->share_location;
					$destination_foundation_settings->share_colour_scheme = $foundation_settings->share_colour_scheme;

					$update_customizer = true;
				}

				$destination_foundation_settings->save();
				$destination_main_settings->save();
				$destination_compat_settings->save();

				if ( $update_customizer ) {
					wptouch_initialize_customizer( true );
				}
			}

			// Switch to the original site
			if ( $current_blog_id != $source_site ) {
				restore_current_blog();
			}

			break;
		default:
			do_action( 'wptouch_admin_ajax_' . $ajax_action, $wptouch_pro );
			do_action( 'wptouch_admin_ajax_intercept', $ajax_action );
			break;
	}
}
