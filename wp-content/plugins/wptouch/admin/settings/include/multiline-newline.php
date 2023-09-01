<?php

require_once( 'text.php' );

function wptouch_admin_split_setting_newline() {
	global $_primed_setting;
	$settings = wptouch_get_settings( $_primed_setting->domain, false );
	$setting_name = $_primed_setting->name;
	if ( $settings->$setting_name != '' ) {
		$setting_data = explode( "\r\n", $settings->$setting_name );
	} else {
		$setting_data = array();
	}
	return $setting_data;
}