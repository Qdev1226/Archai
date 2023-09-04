<?php
$order_id = (int) $_GET['order_id'];
$email = $GLOBALS['ec_cart_data']->cart_data->email;

$mysqli = new ec_db( );

$order = false;
if ( '' != $GLOBALS['ec_cart_data']->cart_data->guest_key ) {
	$order = $mysqli->get_guest_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
} else if ( isset( $_GET['ec_guest_key'] ) && (bool) $_GET['ec_guest_key'] ) {
	$order = $mysqli->get_guest_order_row( $order_id, sanitize_text_field( $_GET['ec_guest_key'] ) );
}

if ( ! $order ) {
	$order = $mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
}

$bill_country = $mysqli->get_country_name( $order->billing_country );
if ( $bill_country ) {
	$order->billing_country = $bill_country;
}
$ship_country = $mysqli->get_country_name( $order->shipping_country );
if ( $ship_country ) {
	$order->shipping_country = $ship_country;
}

if ( $order ) {
	$order_details = false;
	if ( $GLOBALS['ec_cart_data']->cart_data->guest_key != "" ) {
		$order_details = $mysqli->get_guest_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
	} else if ( isset( $_GET['ec_guest_key'] ) && (bool) $_GET['ec_guest_key'] ) {
		$order_details = $mysqli->get_guest_order_details( $order_id, sanitize_text_field( $_GET['ec_guest_key'] ) );
	}
	if ( ! $order_details ) {
		$order_details = $mysqli->get_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
	}
	$country_list = $mysqli->get_countries( );
	$tax_struct = new ec_tax( 0,0,0, "", "");

	$total = $GLOBALS['currency']->get_currency_display( $order->grand_total );
	$subtotal = $GLOBALS['currency']->get_currency_display( $order->sub_total );
	$tip = $GLOBALS['currency']->get_currency_display( $order->tip_total );
	$tax = $GLOBALS['currency']->get_currency_display( $order->tax_total );
	if ( $order->duty_total > 0 ) {
		$has_duty = true;
	} else {
		$has_duty = false;
	}
	$duty = $GLOBALS['currency']->get_currency_display( $order->duty_total );
	$vat = $GLOBALS['currency']->get_currency_display( $order->vat_total );
	$order_fees = $mysqli->get_order_fees( $order->order_id );
	$shipping = $GLOBALS['currency']->get_currency_display( $order->shipping_total );
	$discount = $GLOBALS['currency']->get_currency_display( $order->discount_total );
	$refund = $GLOBALS['currency']->get_currency_display( $order->refund_total );

	if ( $order->vat_rate > 0 ) {
		$vat_rate_formatted = $vat_rate = $order->vat_rate;
	} else if ( ( $order->grand_total - $order->vat_total ) > 0 ) {
		$vat_rate_formatted = $vat_rate = ( $order->vat_total / ( $order->grand_total - $order->vat_total ) ) * 100;
	} else {
		$vat_rate_formatted = $vat_rate = 0;
	}

	if ( round( $vat_rate_formatted, 0 ) == $vat_rate ) {
		$vat_rate_formatted = number_format( round( $vat_rate_formatted, 0 ), 0, '', '' );
	} else if ( round( $vat_rate_formatted, 1 ) == $vat_rate ) {
		$vat_rate_formatted = number_format( $vat_rate_formatted, 1, '.', '' );
	} else if ( round( $vat_rate_formatted, 2 ) == $vat_rate ) {
		$vat_rate_formatted = number_format( $vat_rate_formatted, 2, '.', '' );
	} else if ( round( $vat_rate_formatted, 3 ) == $vat_rate ) {
		$vat_rate_formatted = number_format( $vat_rate_formatted, 3, '.', '' );
	}
	$vat_rate = $vat_rate_formatted;

	$gst = $order->gst_total;
	$pst = $order->pst_total;
	$hst = $order->hst_total;

	$gst_rate = $order->gst_rate;
	$pst_rate = $order->pst_rate;
	$hst_rate = $order->hst_rate;

	if ( floor( $gst_rate ) == $gst_rate ) {
		$gst_rate = number_format( $gst_rate, 0, '', '' );
	}
	if ( floor( $pst_rate ) == $pst_rate ) {
		$pst_rate = number_format( $pst_rate, 0, '', '' );
	}
	if ( floor( $hst_rate ) == $hst_rate ) {
		$hst_rate = number_format( $hst_rate, 0, '', '' );
	}
	$storepageid = get_option('ec_option_storepage');
	if ( function_exists( 'icl_object_id' ) ) {
		$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
	}
	$store_page = get_permalink( $storepageid );
	if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
		$https_class = new WordPressHTTPS();
		$store_page = $https_class->makeUrlHttps( $store_page );
	}

	if ( substr_count( $store_page, '?' ) ) {
		$permalink_divider = "&";
	} else {
		$permalink_divider = "?";
	}
	$email_logo_url = get_option( 'ec_option_email_logo' );

	// Get receipt
	if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_print_receipt.php' ) ) {
		include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_print_receipt.php';
	} else {
		include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_print_receipt.php';
	}
} else {
	echo "No Order Found";	
}
?>