<?php


add_filter( 'wptouch_allow_tablets', 'foundation_allow_tablets' );
function foundation_allow_tablets() {
	return true;
}

add_filter( 'wptouch_supported_device_classes', 'foundation_get_tablet_devices' );
function foundation_get_tablet_devices( $device_classes = false ) {
	$settings = wptouch_get_settings();

	$tablet_devices[] = array();

	if ( $settings->enable_ios_tablet ) {
		$tablet_devices[] = 'iPad'; // Apple iPads
	}

	if ( $settings->enable_android_tablet ) {
		$tablet_devices[] = array( 'Android', 'Tablet' ); // Catches ALL Android devices/browsers that explicitly state they're tablets
		$tablet_devices[] = array( 'Nexus', '7' ); // Nexus 7
		$tablet_devices[] = 'Xoom'; // Motorola Xoom
		$tablet_devices[] = 'SCH-I800'; // Galaxy Tab
	}

	if ( $settings->enable_windows_tablet ) {
		$tablet_devices[] = 'IEMobile/10.0'; // Windows IE 10 touch tablet devices
	}

	if ( $settings->enable_kindle_tablet ) {
		$tablet_devices[] = 'Kindle'; // Kindles
		$tablet_devices[] = 'Silk'; // Kindles in Silk mode
	}

	if ( $settings->enable_blackberry_tablet ) {
		$tablet_devices[] = 'PlayBook'; // BB PlayBook
	}

	if ( $settings->enable_webos_tablet ) {
		$tablet_devices[] = array( 'tablet', 'hpwOS' );
		$tablet_devices[] = array( 'tablet', 'WebOS' );
		$tablet_devices[] = 'hp-tablet';
		$tablet_devices[] = 'P160U'; // HP TouchPad
	}

	if ( is_array( $device_classes ) && isset( $device_classes[ 'default' ] ) ) {
		$device_classes[ 'default' ] = array_merge( $device_classes[ 'default' ], $tablet_devices );
		return $device_classes;
	} else {
		return $tablet_devices;
	}
}


function foundation_tablet_settings( $page_options ){
	wptouch_add_page_section(
		FOUNDATION_PAGE_HOMESCREEN_ICONS,
		__( 'iPad', 'wptouch-pro' ),
		'admin_menu_homescreen_ipad_retina',
		array(
			wptouch_add_setting(
				'image-upload',
				'ipad_icon_retina',
				sprintf( __( '%d by %d pixels (PNG)', 'wptouch-pro' ), 152, 152 ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0'
			),
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN
	);

	return $page_options;
}

function foundation_is_tablet() {
	global $wptouch_pro;

	if ( in_array( $wptouch_pro->active_device, foundation_get_tablet_devices() ) ) {
		return true;
	}

	return false;
}