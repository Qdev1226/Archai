<?php
function wptouch_settings_process( $wptouch_pro ) {
	if ( isset( $wptouch_pro->post['wptouch-reset-3'] ) ) {
		$wptouch_pro->verify_post_nonce();

		// Clear the cookie
		setcookie( 'wptouch-4-admin-menu', 0, time() - 3600 );

		WPTOUCH_DEBUG( WPTOUCH_INFO, "Settings are being reset" );
		$wptouch_pro->erase_all_settings();

		$wptouch_pro->reset_icon_states();
		$wptouch_pro->reload_settings();

		require_once( WPTOUCH_DIR . '/core/menu.php' );

		// Check for multisite reset
		if ( wptouch_is_multisite_enabled() && wptouch_is_multisite_primary() ) {
			delete_site_option( WPTOUCH_MULTISITE_LICENSED );
		}

		$wptouch_pro->redirect_to_page( admin_url( 'admin.php?page=wptouch-admin-general-settings' ) );

		wptouch_delete_all_transients();

	} else if ( isset( $wptouch_pro->post['wptouch-submit-3'] ) ) {
		$wptouch_pro->verify_post_nonce();

		if ( isset( $wptouch_pro->post['wptouch_restore_settings'] ) && strlen( $wptouch_pro->post['wptouch_restore_settings'] ) ) {
			require_once( 'admin-backup-restore.php' );

			wptouch_restore_settings( $wptouch_pro->post['wptouch_restore_settings'] );

			return;
		}

		$new_settings     = array();
		$modified_domains = array();

		// Search for all the settings to update
		foreach ( $wptouch_pro->post as $key => $content ) {
			if ( preg_match( '#^wptouch__(.*)__(.*)#', $key, $match ) ) {
				$setting_domain = sanitize_text_field( $match[1] );
				$setting_name   = sanitize_text_field( $match[2] );

				// Decode slashes on strings
				if ( is_string( $content ) ) {
					$content = htmlspecialchars_decode( $content );
				}

				$new_settings[ $setting_domain ][ $setting_name ] = apply_filters( 'wptouch_modify_setting__' . $setting_domain . '__' . $setting_name, $content );

				// Flag which domains have been modified
				$modified_domains[ $setting_domain ] = 1;

				if ( isset( $wptouch_pro->post[ 'hid-wptouch__' . $match[1] . '__' . $match[2] ] ) ) {
					// This is a checkbox
					$new_settings[ $setting_domain ][ $setting_name ] = 1;
				}
			}
		}

		// Do a loop and find all the checkboxes that should be disabled
		foreach ( $wptouch_pro->post as $key => $content ) {
			if ( preg_match( '#^hid-wptouch__(.*)__(.*)#', $key, $match ) ) {
				$setting_domain = sanitize_text_field( $match[1] );
				$setting_name   = sanitize_text_field( $match[2] );

				$new_settings[ $setting_domain ][ $setting_name ] = ( isset( $new_settings[ $setting_domain ][ $setting_name ] ) ? 1 : 0 );

				$modified_domains[ $setting_domain ] = 1;
			}
		}

		/**
		 * Settings fields that should allow script tags.
		 */
		$script_tags_allowed_fields = array(
			'custom_stats_code',
			'custom_advertising_mobile',
			'advertising_header_code_1',
			'advertising_header_code_2',
			'advertising_footer_code_1',
			'advertising_footer_code_2',
			'advertising_pre_content_code_1',
			'advertising_pre_content_code_2',
			'advertising_post_content_code_1',
			'advertising_post_content_code_2',
			'advertising_mid_content_code_1',
			'advertising_mid_content_code_2',
			'advertising_page_level_code_1',
			'advertising_page_level_code_2',
			'advertising_custom_head_code',
		);

		// Update all the domains that have been modified
		foreach ( $modified_domains as $domain => $ignored_value ) {
			$settings = $wptouch_pro->get_settings( $domain );

			// Sanitize and update settings with new values.
			foreach ( $new_settings[ $domain ] as $key => $value ) {
				if ( isset( $settings->$key ) ) {
					$key = sanitize_key( $key );
					if ( is_array( $value ) ) {
						$value = array_map( 'wptouch_sanitize_value', $value );
					} else {
						// Allow script tags to be output verbatim for whitelisted fields.
						if ( ! in_array( $key, $script_tags_allowed_fields, true ) ) {
							$value = wptouch_sanitize_value( $value );
						}
					}
					$settings->$key = is_array( $value ) ? $value : trim( $value );
				}
			}

			$settings->save();
		}

		// Handle automatic backup
		$settings = wptouch_get_settings();
		require_once( 'admin-backup-restore.php' );
		wptouch_backup_settings();
	}

	do_action( 'wptouch_admin_save_settings_completed' );
}

/**
 * Sanitize setting value.
 *
 * @param mixed $value The value to sanitize.
 *
 * @return bool|int|string
 */
function wptouch_sanitize_value( $value ) {
	switch ( true ) {
		case is_numeric( $value ):
			$value = intval( $value );
			break;

		case is_bool( $value ):
			$value = ( bool ) $value;
			break;

		case is_email( $value ):
			$value = sanitize_email( $value );
			break;

		default:
			$value = wp_strip_all_tags( $value );
	}

	return $value;
}