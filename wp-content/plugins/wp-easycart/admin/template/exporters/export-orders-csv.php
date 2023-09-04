<?php
global $wpdb;

$order_id_array = array( );
if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'export-orders-csv' && isset( $_GET['bulk'] ) ){
	if( is_array( $_GET['bulk'] ) ){
		foreach( (array) $_GET['bulk'] as $id ){ // XSS OK. Forced array and each item sanitized.
			$order_id_array[] = (int) $id ;
		}
	}else{
		$order_id_array[] = (int) $_GET['bulk'];
	}
}

$header = "";
$data = "";

$sql = "SELECT 
			ec_order.order_date,
			ec_orderstatus.order_status,
			ec_orderdetail.orderdetail_id,
			ec_orderdetail.order_id,
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
			ec_orderdetail.product_id,
			ec_orderdetail.title,
			ec_orderdetail.model_number,
			ec_orderdetail.unit_price,
			ec_orderdetail.total_price,
			ec_orderdetail.quantity,
			ec_orderdetail.optionitem_name_1,
			ec_orderdetail.optionitem_name_2,
			ec_orderdetail.optionitem_name_3,
			ec_orderdetail.optionitem_name_4,
			ec_orderdetail.optionitem_name_5,
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
			ec_orderdetail.use_advanced_optionset,
			ec_orderdetail.giftcard_id,
			ec_orderdetail.shipper_id,
			ec_orderdetail.shipper_first_name,
			ec_orderdetail.shipper_last_name,
			ec_orderdetail.gift_card_message,
			ec_orderdetail.gift_card_from_name,
			ec_orderdetail.gift_card_to_name,
			ec_orderdetail.gift_card_email,
			ec_orderdetail.download_file_name,
			ec_orderdetail.download_key,
			ec_orderdetail.deconetwork_id,
			ec_orderdetail.deconetwork_name,
			ec_orderdetail.deconetwork_product_code,
			ec_orderdetail.deconetwork_options,
			ec_orderdetail.deconetwork_color_code,
			ec_orderdetail.deconetwork_product_id,
			ec_orderdetail.deconetwork_image_link,
			ec_orderdetail.subscription_signup_fee,
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
			ec_order.fraktjakt_shipment_id,
			ec_response.response_text as gateway_response
		FROM 
			ec_order 
			LEFT OUTER JOIN ec_orderdetail ON ec_order.order_id = ec_orderdetail.order_id
			LEFT JOIN ec_country as billing_country ON billing_country.iso2_cnt = ec_order.billing_country 
			LEFT JOIN ec_country as shipping_country ON shipping_country.iso2_cnt = ec_order.shipping_country 
			LEFT JOIN ec_orderstatus ON ec_orderstatus.status_id = ec_order.orderstatus_id
			LEFT JOIN ec_response ON ec_response.order_id = ec_order.order_id
		WHERE 
			( ec_orderstatus.is_approved = 1 OR 
			ec_orderstatus.is_approved = 0 )";

if( count( $order_id_array ) > 0 ){
	$order_id_sql = implode( ',', $order_id_array );
	$sql .=	" AND
			ec_order.order_id IN (" . $order_id_sql . ")";
}
$sql .=	"
		ORDER BY 
			ec_order.order_id ASC";
$results = $wpdb->get_results( $sql, ARRAY_A );

$keys = array_keys( $results[0] );
$dataset = array( );
$single_use_key_names = apply_filters( 'wp_easycart_order_export_single_keys', 
						array( 	"sub_total", "tip_total", "tax_total", "tax_total", "refund_total", "shipping_total", "discount_total", "vat_total", 
								"vat_rate", "hst_total", "hst_rate", "pst_total", "pst_rate", "gst_total", "gst_rate", "grand_total",
								"order_date", "order_status", "payment_method", "shipping_method", "tracking_number", "promo_code_used",
								"order_customer_notes", "agreed_to_terms", "order_ip_address", "order_weight", 
								"order_gateway", "card_holder_name", "creditcard_digits", "cc_exp_month", "cc_exp_year", "stripe_charge_id", "order_notes", 
								"gateway_response" ) );


$keys[] = "advanced_product_options";
$keys = apply_filters( 'wp_easycart_order_export_keys', $keys );

$prev_order = 0;
$is_new_order = false;

foreach( $results as $result ){

	if( $result['order_id'] != $prev_order ){
		$prev_order = $result['order_id'];
		$is_new_order = true;
	}

	if( $result['order_gateway'] == "authorize" ){
		$response_exploded = explode( ",", $result['gateway_response'] );
		if( count( $response_exploded ) > 3 ){
			 $result['gateway_response'] = $response_exploded[3];
		}
	}else if( $result['order_gateway'] == "paypal" ){
		preg_match_all( "/\[payment_status\] \=\> (.*)\n/", $result['gateway_response'], $output_array );
		if( count( $output_array ) > 1 ){
			 $result['gateway_response'] = $output_array[1][0];
		}
	}else if( $result['order_gateway'] == "stripe" ){
		preg_match_all( "/\[status\] \=\> (.*)\n/", $result['gateway_response'], $output_array );
		if( count( $output_array ) > 1 ){
			 $result['gateway_response'] = $output_array[1][0];
		}
	}

	$new_line = array( );

	foreach( $keys as $key ){

		if( $key == "advanced_product_options" ){
			$option_sql = "SELECT 
					ec_order_option.option_value 
				   FROM 
					ec_order_option 
				   WHERE 
					ec_order_option.orderdetail_id = %s 
				   ORDER BY 
					ec_order_option.order_option_id ASC";
			$option_results = $wpdb->get_results( $wpdb->prepare( $option_sql, $result['orderdetail_id'] ) );

			$optionlist = '';
			$first = true;
			foreach( $option_results as $option_row ){
				if( !$first )
					$optionlist .= ', ';
				$optionlist .= htmlspecialchars_decode( $option_row->option_value );
				$first = false;
			}
			$new_line[] = $optionlist;

		}else{

			$value = $result[$key];

			if( in_array( $key, $single_use_key_names ) && !$is_new_order ){
				$new_line[] = "0.00";

			}else if( !isset( $value ) || $value == "" ){
				$new_line[] = "";

			}else if( $key == 'billing_zip' || $key == 'shipping_zip' ){
				$new_line[] = "=\"" . $value . "\"";

			}else{
				$new_line[] = htmlspecialchars_decode( $value );

			}

		}

	}


	$dataset[] = $new_line;

	$is_new_order = false;

}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=order-export-' . date( 'Y-m-d' ). '.csv' );
$output = fopen('php://output', 'w');
fputcsv($output, $keys);
foreach( $dataset as $result ){
	fputcsv($output, $result);
}
die( );
?>