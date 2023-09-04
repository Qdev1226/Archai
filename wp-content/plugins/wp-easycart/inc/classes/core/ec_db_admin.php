<?php
class ec_db_admin extends ec_db{

	public static function add_taxrate( $tax_by_state, $tax_by_country, $tax_by_duty, $tax_by_vat, $tax_by_all, $state_rate, $country_rate, $duty_rate, $vat_rate, $all_rate, $state_code, $country_code, $vat_country_code, $duty_exempt_country_code ){
		$sql = "INSERT INTO ec_taxrate(tax_by_state, tax_by_country, tax_by_duty, tax_by_vat, tax_by_all, state_rate, country_rate, duty_rate, vat_rate, all_rate, state_code, country_code,vat_country_code, duty_exempt_country_code) VALUES( %d, %d, %d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s)";

		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $tax_by_state, $tax_by_country, $tax_by_duty, $tax_by_vat, $tax_by_all, $state_rate, $country_rate, $duty_rate, $vat_rate, $all_rate, $state_code, $country_code, $vat_country_code, $duty_exempt_country_code ) );
	}

	public static function update_taxrate( $taxrate_id, $tax_by_state, $tax_by_country, $tax_by_duty, $tax_by_vat, $tax_by_all, $state_rate, $country_rate, $duty_rate, $vat_rate, $all_rate, $state_code, $country_code, $vat_country_code, $duty_exempt_country_code ){
		$sql = "UPDATE ec_taxrate SET tax_by_state=%d, tax_by_country=%d, tax_by_duty=%d, tax_by_vat=%d, tax_by_all=%d, state_rate=%s, country_rate=%s, duty_rate=%s, vat_rate=%s, all_rate=%s, state_code=%s, country_code=%s, vat_country_code=%s, duty_exempt_country_code=%s WHERE taxrate_id=%d";

		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $tax_by_state, $tax_by_country, $tax_by_duty, $tax_by_vat, $tax_by_all, $state_rate, $country_rate, $duty_rate, $vat_rate, $all_rate, $state_code, $country_code, $vat_country_code, $duty_exempt_country_code, $taxrate_id ) );
	}

	public static function delete_taxrate( $taxrate_id ){
		$sql = "DELETE FROM ec_taxrate WHERE taxrate_id = %d";

		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $taxrate_id ) );
	}

	public static function update_country( $iso2_cnt, $rate ){
		$sql = "UPDATE ec_country SET vat_rate_cnt = %s WHERE iso2_cnt = %s";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $rate, $iso2_cnt ) );
	}

	public static function add_shippingrate( $is_price_based, $is_weight_based, $is_method_based, $is_ups_based, $is_usps_based, $is_fedex_based, $is_auspost_based, $is_dhl_based, $trigger_rate, $shipping_rate, $shipping_label, $shipping_order, $shipping_code, $shipping_override_rate ){
		$sql = "INSERT INTO ec_shippingrate( is_price_based, is_weight_based, is_method_based, is_ups_based, is_usps_based, is_fedex_based, is_auspost_based, is_dhl_based, trigger_rate, shipping_rate, shipping_label, shipping_order, shipping_code, shipping_override_rate) VALUES( %d, %d, %d, %d, %d, %d, %d, %d, %s, %s, %s, %d, %s, %s)";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $is_price_based, $is_weight_based, $is_method_based, $is_ups_based, $is_usps_based, $is_fedex_based, $is_auspost_based, $is_dhl_based, $trigger_rate, $shipping_rate, $shipping_label, $shipping_order, $shipping_code, $shipping_override_rate ) );
	}

	public static function update_shippingrate( $shippingrate_id, $is_price_based, $is_weight_based, $is_method_based, $is_ups_based, $is_usps_based, $is_fedex_based, $is_auspost_based, $is_dhl_based, $trigger_rate, $shipping_rate, $shipping_label, $shipping_order, $shipping_code, $shipping_override_rate ){
		$sql = "UPDATE ec_shippingrate SET is_price_based=%d, is_weight_based=%d, is_method_based=%d, is_ups_based=%d, is_usps_based=%d, is_fedex_based=%d, is_auspost_based=%d, is_dhl_based=%d, trigger_rate=%s, shipping_rate=%s, shipping_label=%s, shipping_order=%d, shipping_code=%s, shipping_override_rate=%s WHERE shippingrate_id=%d";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $is_price_based, $is_weight_based, $is_method_based, $is_ups_based, $is_usps_based, $is_fedex_based, $is_auspost_based, $is_dhl_based, $trigger_rate, $shipping_rate, $shipping_label, $shipping_order, $shipping_code, $shipping_override_rate, $shippingrate_id ) );
	}

	public static function delete_shippingrate( $shippingrate_id ){
		$sql = "DELETE FROM ec_shippingrate WHERE shippingrate_id=%d";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $shippingrate_id ) );
	}

	public static function update_setting( $option_name, $option_value ){
		$sql = "UPDATE ec_setting SET " . parent::$mysqli->prepare( $option_name ) . "=%s WHERE setting_id = 1";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $option_value ) );
	}

	public static function get_user( $user_id, $email = "" ){ // email not used here.
		$sql = "SELECT ec_user.user_id, ec_user.password, ec_user.list_id, ec_user.edit_sequence, ec_user.email, ec_user.first_name, ec_user.last_name, ec_user.user_level, billing.company_name AS billing_company_name, billing.first_name AS billing_first_name, billing.last_name AS billing_last_name, billing.address_line_1 AS billing_address_line_1, billing.city AS billing_city, billing.state AS billing_state, billing.zip AS billing_zip, billing.country AS billing_country, billing.phone AS billing_phone, shipping.company_name AS shipping_company_name, shipping.first_name AS shipping_first_name, shipping.last_name AS shipping_last_name, shipping.address_line_1 AS shipping_address_line_1, shipping.city AS shipping_city, shipping.state AS shipping_state, shipping.zip AS shipping_zip, shipping.country AS shipping_country, shipping.phone AS shipping_phone FROM ec_user LEFT JOIN ec_address as billing ON (ec_user.default_billing_address_id = billing.address_id) LEFT JOIN ec_address as shipping ON (ec_user.default_shipping_address_id = shipping.address_id) WHERE ec_user.user_id = %d";
		return parent::$mysqli->get_row( parent::$mysqli->prepare( $sql, $user_id ) );
	}

	public static function get_users( ){
		$sql = "SELECT ec_user.user_id, ec_user.password, ec_user.list_id, ec_user.edit_sequence, ec_user.email, ec_user.first_name, ec_user.last_name, ec_user.user_level, billing.company_name AS billing_company_name, billing.first_name AS billing_first_name, billing.last_name AS billing_last_name, billing.address_line_1 AS billing_address_line_1, billing.city AS billing_city, billing.state AS billing_state, billing.zip AS billing_zip, billing.country AS billing_country, billing.phone AS billing_phone, shipping.company_name AS shipping_company_name, shipping.first_name AS shipping_first_name, shipping.last_name AS shipping_last_name, shipping.address_line_1 AS shipping_address_line_1, shipping.city AS shipping_city, shipping.state AS shipping_state, shipping.zip AS shipping_zip, shipping.country AS shipping_country, shipping.phone AS shipping_phone FROM ec_user LEFT JOIN ec_address as billing ON (ec_user.default_billing_address_id = billing.address_id) LEFT JOIN ec_address as shipping ON (ec_user.default_shipping_address_id = shipping.address_id)";
		return parent::$mysqli->get_results( $sql );
	}

	public static function update_product_quickbooks( $model_number, $list_id, $edit_sequence, $income_account_ref = "Online Sales", $cogs_account_ref = "Cost of Goods Sold", $asset_account_ref = "Inventory Asset" ){
		$sql = "UPDATE ec_product SET list_id = %s, edit_sequence = %s, income_account_ref = %s, cogs_account_ref = %s, asset_account_ref = %s WHERE model_number = %s";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $list_id, $edit_sequence, $income_account_ref, $cogs_account_ref, $asset_account_ref, $model_number ) );
	}

	public static function get_product( $model_number, $product_id = 0 ){
		$sql = "SELECT ec_product.* FROM ec_product WHERE ec_product.model_number = %s";
		return parent::$mysqli->get_row( parent::$mysqli->prepare( $sql, $model_number ) );
	}

	public static function get_products( ){
		$sql = "SELECT ec_product.* FROM ec_product";
		return parent::$mysqli->get_results( $sql );
	}

	public static function get_orders( ){
		$sql = "SELECT ec_order.order_id, ec_order.user_id FROM ec_order";
		return parent::$mysqli->get_results( $sql );
	}

	public static function update_order_quickbooks( $order_id, $txn_id, $edit_sequence ){
		$sql = "UPDATE ec_order SET txn_id = %s, edit_sequence = %s WHERE order_id = %d";
		parent::$mysqli->query( parent::$mysqli->prepare( $sql, $txn_id, $edit_sequence, $order_id ) );
	}

	public static function get_quickbooks_user( ){
		$sql_quickbooks_user = "SELECT qb_username, qb_password FROM quickbooks_user";
		return parent::$mysqli->get_row( $sql_quickbooks_user );
	}

	public static function update_quickbooks_user( $username, $password ){
		$sql_quickbooks_user = "UPDATE quickbooks_user SET qb_username = %s, qb_password = %s";
		$func = QUICKBOOKS_HASH;
		parent::$mysqli->query( parent::$mysqli->prepare( $sql_quickbooks_user, $username, $func( $password . QUICKBOOKS_SALT ) ) );
	}

	public static function get_order_totals_by_days( $days_limit ){
		$sql = "SELECT SUM( ec_order.grand_total ) AS total, DAY( ec_order.order_date ) AS the_day FROM ec_order LEFT JOIN ec_orderstatus ON ( ec_order.orderstatus_id = ec_orderstatus.status_id ) WHERE ec_order.order_date > ( curdate( ) - INTERVAL %d DAY ) AND ec_orderstatus.is_approved = 1 GROUP BY DAY( ec_order.order_date ) ORDER BY ec_order.order_date ASC";
		return parent::$mysqli->get_results( parent::$mysqli->prepare( $sql, $days_limit ) );
	}

	public static function get_order_totals_by_weeks( $weeks_limit ){
		$sql = "SELECT SUM( ec_order.grand_total ) as total, WEEK( ec_order.order_date ) AS the_week FROM ec_order LEFT JOIN ec_orderstatus ON ( ec_order.orderstatus_id = ec_orderstatus.status_id ) WHERE ec_order.order_date > ( curdate( ) - INTERVAL %d WEEK ) AND ec_orderstatus.is_approved = 1 GROUP BY WEEK( ec_order.order_date ) ORDER BY ec_order.order_date ASC";
		return parent::$mysqli->get_results( parent::$mysqli->prepare( $sql, $weeks_limit ) );
	}

	public static function get_order_totals_by_months( $months_limit ){
		$sql = "SELECT SUM( ec_order.grand_total ) as total, MONTH( ec_order.order_date ) AS the_month FROM ec_order LEFT JOIN ec_orderstatus ON ( ec_order.orderstatus_id = ec_orderstatus.status_id ) WHERE ec_order.order_date > ( curdate( ) - INTERVAL %d MONTH ) AND ec_orderstatus.is_approved = 1 GROUP BY MONTH( ec_order.order_date ) ORDER BY ec_order.order_date ASC";
		return parent::$mysqli->get_results( parent::$mysqli->prepare( $sql, $months_limit ) );
	}

	public static function get_order_totals_by_years( $years_limit ){
		$sql = "SELECT SUM( ec_order.grand_total ) as total, YEAR( ec_order.order_date ) AS the_year FROM ec_order LEFT JOIN ec_orderstatus ON ( ec_order.orderstatus_id = ec_orderstatus.status_id ) WHERE ec_order.order_date > ( curdate( ) - INTERVAL %d YEAR ) AND ec_orderstatus.is_approved = 1 GROUP BY YEAR( ec_order.order_date ) ORDER BY ec_order.order_date ASC";
		return parent::$mysqli->get_results( parent::$mysqli->prepare( $sql, $years_limit ) );
	}

	public static function get_top_ten_products( ){
		$sql = "SELECT ec_product.title, ec_product.views FROM ec_product ORDER BY ec_product.views DESC LIMIT 10";
		return parent::$mysqli->get_results( $sql );
	}

	public static function get_last_ten_orders( ){
		$sql = "SELECT ec_order.grand_total, ec_order.billing_first_name, ec_order.billing_last_name FROM ec_order ORDER BY ec_order.order_date DESC LIMIT 10";
		return parent::$mysqli->get_results( $sql );
	}

	public static function get_top_ten_customers( ){
		$sql = "SELECT SUM( ec_order.grand_total ) as total, ec_order.billing_first_name, ec_order.billing_last_name FROM ec_order, ec_orderstatus WHERE ec_orderstatus.status_id = ec_order.orderstatus_id AND ec_orderstatus.is_approved = 1 GROUP BY ec_order.user_email ORDER BY total DESC LIMIT 10";
		return parent::$mysqli->get_results( $sql );
	}

	public static function get_order_row_admin( $order_id ){

		$sql = "SELECT 
			ec_order.order_id, 
			ec_order.txn_id,
			ec_order.edit_sequence,
			ec_order.credit_memo_txn_id,
			ec_order.order_date,  
			ec_order.orderstatus_id,
			ec_order.gateway_transaction_id,
			ec_orderstatus.order_status, 
			ec_order.order_weight, 
			ec_orderstatus.is_approved,

			ec_order.user_id,
			ec_user.list_id,

			ec_order.sub_total,
			ec_order.shipping_total,
			ec_order.tax_total,
			ec_order.vat_total,
			ec_order.vat_rate,
			ec_order.duty_total,
			ec_order.discount_total,
			ec_order.grand_total,
			ec_order.refund_total,

			ec_order.gst_total,
			ec_order.gst_rate,
			ec_order.pst_total,
			ec_order.pst_rate,
			ec_order.hst_total,
			ec_order.hst_rate,
			ec_order.tip_total,

			ec_order.promo_code,
			ec_order.giftcard_id,

			ec_order.use_expedited_shipping,
			ec_order.shipping_method,
			ec_order.shipping_carrier,
			ec_order.tracking_number,

			ec_order.user_email,
			ec_order.email_other,
			ec_order.user_level,
			ec_order.guest_key,

			ec_order.billing_first_name, 
			ec_order.billing_last_name, 
			ec_order.billing_company_name, 
			ec_order.billing_address_line_1, 
			ec_order.billing_address_line_2, 
			ec_order.billing_city, 
			ec_order.billing_state, 
			ec_order.billing_zip, 
			ec_order.billing_country, 
			bill_country.name_cnt as billing_country_name, 
			ec_order.billing_phone, 

			ec_order.vat_registration_number,

			ec_order.shipping_first_name, 
			ec_order.shipping_last_name, 
			ec_order.shipping_company_name, 
			ec_order.shipping_address_line_1, 
			ec_order.shipping_address_line_2, 
			ec_order.shipping_city, 
			ec_order.shipping_state, 
			ec_order.shipping_zip, 
			ec_order.shipping_country,
			ship_country.name_cnt as shipping_country_name, 
			ec_order.shipping_phone, 

			ec_order.payment_method, 

			ec_order.paypal_email_id, 
			ec_order.paypal_payer_id,

			ec_order.order_customer_notes,
			ec_order.card_holder_name,
			ec_order.creditcard_digits,
			ec_order.cc_exp_month,
			ec_order.cc_exp_year,

			ec_order.fraktjakt_order_id,
			ec_order.fraktjakt_shipment_id,
			ec_order.subscription_id,

			ec_order.success_page_shown,

			GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data 

			FROM 
			ec_order

			LEFT JOIN ec_country as bill_country ON
			bill_country.iso2_cnt = ec_order.billing_country

			LEFT JOIN ec_country as ship_country ON
			ship_country.iso2_cnt = ec_order.shipping_country

			LEFT JOIN ec_orderstatus ON
			ec_order.orderstatus_id = ec_orderstatus.status_id

			LEFT JOIN ec_customfield
			ON ec_customfield.table_name = 'ec_order'

			LEFT JOIN ec_customfielddata
			ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_order.order_id

			LEFT JOIN ec_user
			ON ec_user.user_id = ec_order.user_id

			WHERE ec_order.order_id = %d

			GROUP BY ec_order.order_id";

		return parent::$mysqli->get_row( parent::$mysqli->prepare( $sql, $order_id ) );

	}

	public static function get_order_details_admin( $order_id ){
		$orderdetail_sql = "SELECT 
				ec_orderdetail.orderdetail_id, 
				ec_orderdetail.order_id, 
				ec_orderdetail.product_id, 
				ec_product.list_id, 
				ec_manufacturer.`name` as manufacturer_name,
				ec_orderdetail.title, 
				ec_orderdetail.model_number, 
				ec_orderdetail.order_date, 
				ec_orderdetail.unit_price, 
				ec_orderdetail.total_price, 
				ec_orderdetail.quantity, 
				ec_orderdetail.image1, 
				ec_orderdetail.optionitem_id_1, 
				ec_orderdetail.optionitem_id_2, 
				ec_orderdetail.optionitem_id_3, 
				ec_orderdetail.optionitem_id_4, 
				ec_orderdetail.optionitem_id_5,
				ec_orderdetail.optionitem_name_1, 
				ec_orderdetail.optionitem_name_2, 
				ec_orderdetail.optionitem_name_3, 
				ec_orderdetail.optionitem_name_4, 
				ec_orderdetail.optionitem_name_5,
				ec_orderdetail.optionitem_label_1, 
				ec_orderdetail.optionitem_label_2, 
				ec_orderdetail.optionitem_label_3, 
				ec_orderdetail.optionitem_label_4, 
				ec_orderdetail.optionitem_label_5,
				ec_orderdetail.optionitem_price_1, 
				ec_orderdetail.optionitem_price_2, 
				ec_orderdetail.optionitem_price_3, 
				ec_orderdetail.optionitem_price_4, 
				ec_orderdetail.optionitem_price_5,
				ec_orderdetail.use_advanced_optionset,
				ec_orderdetail.use_both_option_types,
				ec_orderdetail.giftcard_id, 
				ec_orderdetail.gift_card_message, 
				ec_orderdetail.gift_card_from_name, 
				ec_orderdetail.gift_card_to_name,
				ec_orderdetail.gift_card_email,
				ec_orderdetail.is_download, 
				ec_orderdetail.is_giftcard, 
				ec_orderdetail.is_taxable, 
				ec_orderdetail.is_shippable, 
				ec_download.download_file_name, 
				ec_orderdetail.download_key,
				ec_orderdetail.maximum_downloads_allowed,
				ec_orderdetail.download_timelimit_seconds,
				ec_download.is_amazon_download,
				ec_download.amazon_key,

				ec_orderdetail.is_deconetwork,
				ec_orderdetail.deconetwork_id,
				ec_orderdetail.deconetwork_name,
				ec_orderdetail.deconetwork_product_code,
				ec_orderdetail.deconetwork_options,
				ec_orderdetail.deconetwork_color_code,
				ec_orderdetail.deconetwork_product_id,
				ec_orderdetail.deconetwork_image_link,

				ec_orderdetail.include_code,
				ec_orderdetail.subscription_signup_fee,

				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data

				FROM ec_orderdetail

				LEFT JOIN ec_product
				ON ec_product.product_id = ec_orderdetail.product_id
				
				LEFT JOIN ec_manufacturer
				ON ec_product.manufacturer_id = ec_manufacturer.manufacturer_id

				LEFT JOIN ec_download
				ON ec_download.download_id = ec_orderdetail.download_key

				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_orderdetail'

				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_orderdetail.orderdetail_id 



				WHERE 
				ec_orderdetail.order_id = %d

				GROUP BY
				ec_orderdetail.orderdetail_id";

		return parent::$mysqli->get_results( parent::$mysqli->prepare( $orderdetail_sql, $order_id ) );

	}

}
