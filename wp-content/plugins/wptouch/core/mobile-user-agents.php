<?php
/* Smartphones */
global $wptouch_smartphone_list;
$wptouch_smartphone_list = array();

$settings = wptouch_get_settings();

if ( $settings->enable_ios_phone ) {
	$wptouch_smartphone_list[] = array( 'iPhone' ); // iPhone
	$wptouch_smartphone_list[] = array( 'iPod', 'Mobile' ); // iPod touch
}

if ( $settings->enable_android_phone ) {
	$wptouch_smartphone_list[] = array( 'Android', 'Mobile' ); // Android devices
}

if ( $settings->enable_blackberry_phone ) {
	$wptouch_smartphone_list[] = array( 'BB', 'Mobile Safari' ); // BB10 devices
	$wptouch_smartphone_list[] = array( 'BlackBerry', 'Mobile Safari' ); // BB 6, 7 devices
}

if ( $settings->enable_firefox_phone ) {
	$wptouch_smartphone_list[] = array( 'Firefox', 'Mobile' ); // Firefox OS devices
}

if ( $settings->enable_windows_phone ) {
	$wptouch_smartphone_list[] = array( 'IEMobile/11', 'Touch' ); // Windows IE 11 touch devices
	$wptouch_smartphone_list[] = array( 'IEMobile/10', 'Touch' ); // Windows IE 10 touch devices
	$wptouch_smartphone_list[] = 'IEMobile/9.0'; // Windows Phone OS 9
	$wptouch_smartphone_list[] = 'IEMobile/8.0'; // Windows Phone OS 8
	$wptouch_smartphone_list[] = 'IEMobile/7.0'; // Windows Phone OS 7
}

if ( $settings->enable_opera_phone ) {
	$wptouch_smartphone_list[] = array( 'OPiOS', 'Mobile' ); // Opera Mini iOS
	$wptouch_smartphone_list[] = array( 'Coast', 'Mobile' ); // Opera Coast iOS
}

/* Tablets */
// Handled by the tablets module.

/* Matching any of these user-agents will cause WPtouch Pro to be shown for the 'default' theme */
global $wptouch_device_classes;

$wptouch_device_classes[ 'default' ] = $wptouch_smartphone_list;

global $wptouch_exclusion_list;
$wptouch_exclusion_list = array();
