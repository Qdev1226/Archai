<?php
global $wpdb;

$subscriber_id_array = array( );
if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'export-subscribers-csv' && isset( $_GET['bulk'] ) ){
	if( is_array( $_GET['bulk'] ) ){
		foreach( (array) $_GET['bulk'] as $user_id ){ // XSS OK. Forced array and each item sanitized.
			$subscriber_id_array[] = (int) $user_id;
		}
	}else{
		$subscriber_id_array[] = (int) $_GET['bulk'];
	}
}

$header = "";
$data = "";

$sql = "SELECT 
		*
	FROM
	  ec_subscriber";

if( count( $subscriber_id_array ) > 0 ){
	$subscriber_id_sql = implode( ',', $subscriber_id_array );
	$sql .= "
	WHERE ec_subscriber.subscriber_id IN (" . $subscriber_id_sql . ")";
}

$results = $wpdb->get_results( $sql, ARRAY_A );
if( $results ){
	$keys = array_keys( $results[0] );
}else{
	$keys = array( );
}
header( 'Content-Type: text/csv; charset=utf-8' );
header( 'Content-Disposition: attachment; filename=subscribers-export-' . date( 'Y-m-d' ). '.csv' );
$output = fopen( 'php://output', 'w' );
fputcsv($output, $keys);
foreach( $results as $result ){
	fputcsv( $output, $result );
}
die( );
?>