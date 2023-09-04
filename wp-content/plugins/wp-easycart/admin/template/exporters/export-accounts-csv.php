<?php
global $wpdb;

$user_id_array = array( );
if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'export-accounts-csv' && isset( $_GET['bulk'] ) ){
	if( is_array( $_GET['bulk'] ) ){
		foreach( (array) $_GET['bulk'] as $user_id ){ // XSS OK. Forced array and each item sanitized.
			$user_id_array[] = (int) $user_id;
		}
	}else{
		$user_id_array[] = (int) $_GET['bulk'];
	}
}

$header = "";
$data = "";

$sql = "SELECT 
		ec_user.user_id,
		ec_user.email,
		ec_user.list_id,
		ec_user.edit_sequence,
		ec_user.quickbooks_status,
		ec_user.first_name,
		ec_user.last_name,
		ec_user.default_billing_address_id,
		ec_user.default_shipping_address_id,
		ec_user.user_level,
		ec_user.is_subscriber,
		ec_user.realauth_registered,
		ec_user.stripe_customer_id,
		ec_user.default_card_type,
		ec_user.default_card_last4,
		ec_user.exclude_tax,
		ec_user.exclude_shipping,
		ec_user.user_notes,
		ec_user.vat_registration_number,
		ec_address.address_id as billing_address_id,
		ec_address.user_id as billing_user_id,
		ec_address.first_name as billing_first_name,
		ec_address.last_name as billing_last_name,
		ec_address.address_line_1 as billing_address_line_1,
		ec_address.address_line_2 as billing_address_line_2,
		ec_address.city as billing_city,
		ec_address.state as billing_state,
		ec_address.zip as billing_zip,
		ec_address.country as billing_country,
		ec_address.phone as billing_phone,
		ec_address.company_name as billing_company_name,
		ec_address1.address_id as shipping_address_id,
		ec_address1.user_id as shipping_user_id,
		ec_address1.first_name as shipping_first_name,
		ec_address1.last_name as shipping_last_name,
		ec_address1.address_line_1 as shipping_address_line_1,
		ec_address1.address_line_2 as shipping_address_line_2,
		ec_address1.city as shipping_city,
		ec_address1.state as shipping_state,
		ec_address1.zip as shipping_zip,
		ec_address1.country as shipping_country,
		ec_address1.phone as shipping_phone,
		ec_address1.company_name as shipping_company_name,
		( SUM( ec_order.sub_total ) - SUM( ec_order.refund_total ) ) AS customer_value
	FROM
		ec_user
		LEFT JOIN ec_address ON (ec_user.default_billing_address_id = ec_address.address_id)
		LEFT JOIN ec_address ec_address1 ON (ec_user.default_shipping_address_id = ec_address1.address_id)
		LEFT JOIN ec_order ON (ec_order.user_id = ec_user.user_id)";

if( count( $user_id_array ) > 0 ){
	$user_id_sql = implode( ',', $user_id_array );
	$sql .= "
	WHERE ec_user.user_id  IN (" . $user_id_sql . ")";
}

$sql .= "
	GROUP BY ec_user.user_id";

$results = $wpdb->get_results( $sql, ARRAY_A );
if( $results ){
	$keys = array_keys( $results[0] );
}else{
	$keys = array( );
}
header( 'Content-Type: text/csv; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=users-export-' . date( 'Y-m-d' ). '.csv' );
$output = fopen( 'php://output', 'w' );
fputcsv($output, $keys);
foreach( $results as $result ){
	fputcsv( $output, $result );
}
die( );
?>