<?php

/* This is the main settings object for WPtouch Pro 3.x /*
/* It defines the default settings for the majority of features within WPtouch Pro */
/* To augment these settings, please use one of the appropriate WPtouch hooks */

class WPtouchSettings extends stdClass {
	function save() {
		if ( isset( $this->domain ) ) {
			global $wptouch_pro;
			$wptouch_pro->save_settings( $this, $this->domain );
		} else {
			die( 'Setting domain not set' );
		}
	}
};

// These settings should never be adjusted, but rather should be augmented at a later time */
class WPtouchDefaultSettings30 extends WPtouchSettings {
	function __construct() {

		// Wizard
		$this->show_wizard = true;
		$this->show_free_wizard = false;
		$this->show_network_wizard = true;

		// Setup - General
		$this->site_title = get_bloginfo( 'name' );
		if ( defined( 'WPTOUCH_IS_FREE' ) ) {
			$this->show_wptouch_in_footer = false;
		} else {
			$this->show_wptouch_in_footer = true;
		}

		// Setup - Desktop / Mobile Switching
		$this->show_switch_link = true;
		$this->switch_link_method = 'automatic';

		// Setup - Multisite Network Admin Regionalization
		$this->force_network_locale = 'auto';

		// Setup - Regionalization
		$this->force_locale = 'auto';
		$this->translate_admin = true;

		// Setup - Statistics
		$this->analytics_embed_method = 'disabled';
		$this->analytics_google_id = '';
		$this->custom_stats_code = '';

		// Setup - Google Maps
		$this->maps_google_api_key = '';

		// Setup - Home Page Redirect
		$this->homepage_landing = 'none';
		$this->homepage_redirect_wp_target = 0;
		$this->homepage_redirect_custom_target = '';

		// Changed from preview_mode in 3.x for 4.0
		$this->new_display_mode = true;

		// Setup - Compatibility
		$this->process_desktop_shortcodes = false;
		$this->remove_shortcodes = '';
		$this->disable_no_cache_request_headers = false;

		$this->url_filter_behaviour = 'disabled';
		$this->filtered_urls = '';
		$this->filtered_urls_exact = false;

		// Device Support
		$this->enable_ios_phone = true;
		$this->enable_android_phone = true;
		$this->enable_blackberry_phone = true;
		$this->enable_firefox_phone = true;
		$this->enable_opera_phone = true;
		$this->enable_windows_phone = true;

		$this->enable_ios_tablet = true;
		$this->enable_android_tablet = true;
		$this->enable_windows_tablet = true;
		$this->enable_kindle_tablet = true;
		$this->enable_blackberry_tablet = true;
		$this->enable_webos_tablet = true;

		$this->custom_user_agents = '';

		// Default Theme
		if ( defined( 'WPTOUCH_IS_FREE' ) ) {
			$this->current_theme_friendly_name = 'Bauhaus';
			$this->current_theme_location = '/plugins/' . WPTOUCH_ROOT_NAME . '/themes';
			$this->current_theme_name = 'bauhaus';
		} else {
			$this->current_theme_friendly_name = false;
			$this->current_theme_location = false;
			$this->current_theme_name = false;
		}

		// Menu
		$this->custom_menu_name = 'wp';
		$this->appended_menu_name = 'none';
		$this->prepended_menu_name = 'none';

		$this->enable_parent_items = true;
		$this->enable_menu_icons = false;
		$this->show_login_box = false;

		$this->default_menu_icon = WPTOUCH_DEFAULT_MENU_ICON;
		$this->disabled_menu_items = array();
		$this->temp_disabled_menu_items = array();

		// Debug Log
		$this->debug_log = false;
		$this->debug_log_level = WPTOUCH_ALL;
		$this->debug_log_salt = substr( md5( mt_rand() ), 0, 10 );

		// Add-Ons
		$this->active_addons = array();
		$this->show_wpml_lang_switcher = true;
	}
};

class WPtouchDefaultSettingsBNCID30 extends WPtouchSettings {
	function __construct() {
		// License Information
		$this->bncid = '';
		$this->wptouch_license_key = '';

		$this->license_accepted = false;
		$this->license_accepted_time = 0;

		$this->next_update_check_time = 0;

		$this->license_expired = false;
		$this->license_expiry_date = 0;

		$this->referral_user_id = false;
		$this->allow_multisite = true;
		$this->multisite_control = true;

		$this->license_support_packages = array();
	}
};

class WPtouchDefaultSettingsCompat extends WPtouchSettings {
	function __construct() {
		$this->plugin_hooks = '';
		$this->enabled_plugins = array();
	}
};

class WPtouchDefaultSettingsMultisite extends WPtouchSettings {
	function __construct() {
		$this->multisite_use_master_settings = false;
		$this->multisite_master_site = 1;
		$this->multisite_show_general_settings = true;
		$this->multisite_show_site_compat = true;
		$this->multisite_show_devices = true;
		$this->multisite_show_menus = true;
		$this->multisite_show_themes = true;
		$this->multisite_show_extensions = true;
		$this->multisite_show_colors = true;
		$this->multisite_show_social_media = true;
		$this->multisite_show_social_sharing = true;
	}
};
