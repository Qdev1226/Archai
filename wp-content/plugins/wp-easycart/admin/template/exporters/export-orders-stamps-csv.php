<?php
global $wpdb;

function wpeasycart_stamps_export_calculate_parcel( $products ) {
	$package_dimensions = array( 0, 0, 0 );
	$package_weight = 0;
	$package_volume = 0;
	$package_volume_empty = 0;
	$package_volume_used = 0;

	foreach( $products as $product ){
		$width = ( isset( $product->width ) ) ? $product->width : 1;
		$height = ( isset( $product->height ) ) ? $product->height : 1;
		$length = ( isset( $product->length ) ) ? $product->length : 1;
		$product_dimensions = array( $width, $height, $length );
		rsort( $product_dimensions, SORT_NUMERIC );
		if ( $product_dimensions[0] <= $package_dimensions[0] && $product_dimensions[1] <= $package_dimensions[1] && $product_dimensions[2] <= $package_dimensions[2] && ( $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2] ) <= $package_volume_empty ) {
			$package_volume_empty -= $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
			$package_volume_used += $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2];
		} else {
			$package_dimensions[2] += $product_dimensions[2];
			if ( $product_dimensions[1] > $package_dimensions[1] ) {
				$package_dimensions[1] = $product_dimensions[1];
			}
			if ( $product_dimensions[0] > $package_dimensions[0] ) {
				$package_dimensions[0] = $product_dimensions[0];
			}
			rsort( $package_dimensions, SORT_NUMERIC );
			$package_volume = $package_dimensions[0] * $package_dimensions[1] * $package_dimensions[2];
			$package_volume_used += ( $product_dimensions[0] * $product_dimensions[1] * $product_dimensions[2] );
			$package_volume_empty = ( $package_volume - $package_volume_used );
		}
	}
	$parcel = array(
		'width' => $package_dimensions[0],
		'height' => $package_dimensions[1],
		'length' => $package_dimensions[2],
	);
	return $parcel;
}

$order_id_array = array();
if ( isset( $_GET['ec_admin_form_action'] ) && 'export-orders-stamps-csv' == $_GET['ec_admin_form_action'] && isset( $_GET['bulk'] ) ){
	if ( is_array( $_GET['bulk'] ) ) {
		foreach ( (array) $_GET['bulk'] as $id ) { // XSS OK. Forced array and each item sanitized.
			$order_id_array[] = (int) $id;
		}
	} else {
		$order_id_array[] = (int) $_GET['bulk'];
	}
}

$header = "";
$data = "";

$sql = "SELECT 
			ec_order.order_id,
			DATE_FORMAT( ec_order.order_date, '%m/%d/%Y %H:%i' ) AS order_date,
			ec_orderstatus.order_status,
			ec_order.payment_method,
			ec_order.sub_total,
			ec_order.tip_total,
			ec_order.tax_total,
			ec_order.refund_total,
			ec_order.shipping_total,
			ec_order.discount_total,
			ec_order.vat_total,
			ec_order.vat_rate,
			ec_order.duty_total,
			ec_order.gst_total,
			ec_order.gst_rate,
			ec_order.pst_total,
			ec_order.pst_rate,
			ec_order.hst_total,
			ec_order.hst_rate,
			ec_order.grand_total,
			ec_order.user_id,
			ec_order.use_expedited_shipping,
			ec_order.shipping_method,
			ec_order.shipping_carrier,
			ec_order.shipping_service_code,
			ec_order.tracking_number,
			ec_order.giftcard_id as gift_card_used,
			ec_order.promo_code as promo_code_used,
			ec_order.order_notes,
			ec_order.order_customer_notes,
			ec_order.user_email,
			ec_order.user_level,
			ec_order.billing_first_name,
			ec_order.billing_last_name,
			ec_order.billing_company_name,
			ec_order.billing_address_line_1,
			ec_order.billing_address_line_2,
			ec_order.billing_city,
			ec_order.billing_state,
			ec_order.billing_zip,
			ec_order.billing_country,
			billing_country.name_cnt as billing_country_name, 
			ec_order.billing_phone,
			ec_order.shipping_first_name,
			ec_order.shipping_last_name,
			ec_order.shipping_company_name,
			ec_order.shipping_address_line_1,
			ec_order.shipping_address_line_2,
			ec_order.shipping_city,
			ec_order.shipping_state,
			ec_order.shipping_zip,
			ec_order.shipping_country,
			shipping_country.name_cnt as shipping_country_name,
			ec_order.shipping_phone,
			ec_order.vat_registration_number,
			ec_order.agreed_to_terms,
			ec_order.order_ip_address,
			ec_order.order_weight,
			ec_order.order_gateway,
			ec_order.card_holder_name,
			ec_order.creditcard_digits,
			ec_order.cc_exp_month,
			ec_order.cc_exp_year,
			ec_order.subscription_id,
			ec_order.stripe_charge_id,
			ec_order.nets_transaction_id,
			ec_order.gateway_transaction_id,
			ec_order.paypal_email_id,
			ec_order.paypal_transaction_id,
			ec_order.paypal_payer_id,
			ec_order.fraktjakt_order_id,
			ec_order.fraktjakt_shipment_id
		FROM 
			ec_order 
			LEFT JOIN ec_country as billing_country ON billing_country.iso2_cnt = ec_order.billing_country 
			LEFT JOIN ec_country as shipping_country ON shipping_country.iso2_cnt = ec_order.shipping_country 
			LEFT JOIN ec_orderstatus ON ec_orderstatus.status_id = ec_order.orderstatus_id
		WHERE 
			( ec_orderstatus.is_approved = 1 OR ec_orderstatus.is_approved = 0 )";

if ( count( $order_id_array ) > 0 ) {
	$order_id_sql = implode( ',', $order_id_array );
	$sql .= " AND ec_order.order_id IN (" . $order_id_sql . ")";
}
$sql .= " ORDER BY ec_order.order_id ASC";
$results = $wpdb->get_results( $sql, ARRAY_A );

$keys = array(
	'Order ID (required)',
	'Order Date',
	'Order Value',
	'Requested Service',
	'Ship To - Name',
	'Ship To - Company',
	'Ship To - Address 1',
	'Ship To - Address 2',
	'Ship To - Address 3',
	'Ship To - State/Province',
	'Ship To - City',
	'Ship To - Postal Code',
	'Ship To - Country',
	'Ship To - Phone',
	'Ship To - Email',
	'Total Weight in Oz',
	'Dimensions - Length',
	'Dimensions - Width',
	'Dimensions - Height',
	'Notes - From Customer',
	'Notes - Internal',
	'Gift Wrap?',
	'Gift Message',
);

$dataset = array();
foreach ( $results as $result ) {
	$products = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_product.length, ec_product.width, ec_product.height FROM ec_orderdetail LEFT JOIN ec_product ON ec_product.product_id = ec_orderdetail.product_id WHERE ec_orderdetail.order_id = %d', $result['order_id'] ) );
	$parcel = wpeasycart_stamps_export_calculate_parcel( $products );
	$new_line = array(
		$result['order_id'],
		$result['order_date'],
		number_format( $result['grand_total'], 2, '.', '' ),
		'',
		$result['shipping_first_name'] . ' ' . $result['shipping_last_name'],
		$result['shipping_company_name'],
		$result['shipping_address_line_1'],
		$result['shipping_address_line_2'],
		'',
		$result['shipping_state'],
		$result['shipping_city'],
		$result['shipping_zip'],
		$result['shipping_country'],
		$result['shipping_phone'],
		$result['user_email'],
		$result['order_weight'] * 16,
		$parcel['length'],
		$parcel['width'],
		$parcel['height'],
		'',
		'',
		'',
		'',
	);
	$dataset[] = $new_line;
}

header( 'Content-Type: text/csv; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=stamps-export-' . date( 'Y-m-d' ). '.csv' );
$output = fopen( 'php://output', 'w' );
fputcsv( $output, $keys );
foreach ( $dataset as $result ) {
	fputcsv($output, $result);
}
die();
