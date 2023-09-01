<?php

require_once( 'list.php' );

function wptouch_admin_is_checklist_option_selected() {
	global $_primed_setting;

	$settings = wptouch_get_settings( $_primed_setting->domain, false );
	$setting_name = $_primed_setting->name;

	if ( strstr( $setting_name, '[' ) ) {
		// It's an Array!
		preg_match( '/(.*)\[(.*)\]/', $setting_name, $setting_info );
		$setting_name = $setting_info[ 1 ];
		$setting_key = $setting_info[ 2 ];
		$setting = $settings->$setting_name;

		if ( isset( $setting[ $setting_key ] ) ) {
			return ( isset( $settings->$setting_name ) && in_array( wptouch_admin_get_list_option_key() , $setting[ $setting_key ] ) );
		} else {
			return false;
		}
	} else {
		return ( isset( $settings->$setting_name ) && is_array( $settings->$setting_name ) && in_array( wptouch_admin_get_list_option_key() , $settings->$setting_name ) );
	}
}