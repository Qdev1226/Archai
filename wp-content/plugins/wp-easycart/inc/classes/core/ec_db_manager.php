<?php

class ec_db_manager {

	public function install_db() {
		global $wpdb;
		$this->run_initial_update();
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( self::get_schema( ) );
		$user_test = $wpdb->get_row( "SELECT * FROM ec_user" );
		if ( ! $user_test ) {
			$this->install_base_data();
		}
		update_option( 'ec_option_db_version', EC_CURRENT_DB );
		update_option( 'ec_option_db_new_version', EC_UPGRADE_DB );
	}

	public function uninstall_db() {
		global $wpdb;
		$tables = $this->get_uninstall_tables();
		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS " . $table . ";" );
		}
	}

	public function check_db() {
		global $wpdb;
		$tables = $this->get_uninstall_tables( );
		foreach ( $tables as $table ) {
			$result = $wpdb->get_results( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) );
			if ( 0 == count( $result ) ) {
				return false;
			}
		}
		return true;
	}

	public function verify_db() {
		global $wpdb;
		$errors = array( );
		$schema = $this->get_schema( );
		$create_statements = explode( ';', $schema );
		foreach( $create_statements as $create_statement ){
			preg_match_all( '/CREATE\sTABLE\s([a-z0-9\_]*)\s\((.*)\)/s', $create_statement, $table_data );
			if( count( $table_data ) == 3 && is_array( $table_data[1] ) && count( $table_data[1] ) == 1 && $table_data[1][0] != '' ){
				$table_name = trim( $table_data[1][0] );
				$table_rows_string = $table_data[2][0];
				$table_rows_strings = explode( PHP_EOL, $table_rows_string );
				$table_rows = array( );
				if( 1 || $table_name == 'ec_orderdetail' ){
					foreach( $table_rows_strings as $table_row_string ){
						preg_match_all( '/([a-z0-9\_]*)\s(text|[a-zA-Z\(\)\,0-9]*)(\sDEFAULT NULL|\sNOT\sNULL\s|\sNOT\sNULL|\sNULL\s|\sNULL|\s|.*)(DEFAULT.*|.*)\,/', trim( $table_row_string ), $table_row_item );
						if( count( $table_row_item ) == 5 && count( $table_row_item[1] ) && count( $table_row_item[2] ) && count( $table_row_item[3] ) && count( $table_row_item[4] ) && $table_row_item[1][0] != '' ){
							if( substr( $table_row_item[4][0], strlen( $table_row_item[4][0] ) - 1, 1 ) == "'" ){
								$temp_default_string = substr( $table_row_item[4][0], 0, strlen( $table_row_item[4][0] ) - 1 );
							}else{
								$temp_default_string = $table_row_item[4][0];
							}

							$table_rows[(string)$table_row_item[1][0]] = (object) array(
								'Field'     => $table_row_item[1][0],
								'Type'      => $table_row_item[2][0],
								'Null'      => ( trim( $table_row_item[3][0] ) == 'NOT NULL' ) ? 'NO' : 'YES',
								'Default'   => str_replace( "CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP", "CURRENT_TIMESTAMP", str_replace( "ON UPDATE CURRENT_TIMESTAMP CURRENT_TIMESTAMP", "CURRENT_TIMESTAMP", str_replace( "DEFAULT ", "", str_replace( "DEFAULT '", "", $temp_default_string ) ) ) ),
								'SQL'       => substr( $table_row_string, 0, strlen( $table_row_string ) - 1 )
							);
						}
					}

					$table_schema_rows = $wpdb->get_results( "SHOW COLUMNS FROM " . $table_name );

					// Check the table exists at all
					if( !$table_schema_rows ){
						$errors[] = array( 'type' => 'missing_table', 'sql_fix' => $create_statement, 'error' => 'The table ' . $table_name . ' is missing from your database.' );

					}else{
						$table_schema = array( );
						foreach( $table_schema_rows as $table_schema_row ){
							$table_schema[$table_schema_row->Field] = $table_schema_row;
						}

						foreach( $table_rows as $table_row_item ){

							// Check that the table column exists
							if( !isset( $table_schema[ $table_row_item->Field ] ) ){
								$errors[] = array( 'type' => 'missing_field', 'sql_fix' => 'ALTER TABLE ' . $table_name . ' ADD COLUMN ' . $table_row_item->SQL, 'error' => $table_name . ' is missing "' . $table_row_item->Field . '"' );

							// Exists, lets check its valid
							}else{

								// Check the type is correct (removed to prevent unecessary error notices)
								if( strtolower( trim( $table_schema[ $table_row_item->Field ]->Type ) ) != strtolower( trim( $table_row_item->Type ) ) ){
									//$errors[] = array( 'type' => 'incorrect_type', 'sql_fix' => 'ALTER TABLE ' . $table_name . ' MODIFY ' . $table_row_item->SQL, 'error' => $table_name . '.' . $table_row_item->Field . ' is the wrong type, listed as "' . trim( $table_schema[ $table_row_item->Field]->Type ) . '" should be "' . trim( $table_row_item->Type ) . '"' );
								}

								// Check the Null / Not Null is correct (removed to prevent unecessary error notices)
								if( trim( $table_schema[ $table_row_item->Field ]->Null ) != trim( $table_row_item->Null ) ){
									//$errors[] = array( 'type' => 'incorrect_null', 'sql_fix' => 'ALTER TABLE ' . $table_name . ' MODIFY ' . $table_row_item->SQL, 'error' => $table_name . '.' . $table_row_item->Field . ' is the wrong "IS NULL" value, listed as "' . trim( $table_schema[ $table_row_item->Field]->Null ) . '" should be "' . trim( $table_row_item->Null ) . '"' );
								}

								// Check the Default is correct (removed to prevent unecessary error notices)
								if( strtoupper( trim( $table_row_item->Default ) ) == 'AUTO_INCREMENT' ){
									// Ignore

								}else if( strtoupper( trim( $table_row_item->Default ) ) == 'CURRENT_TIMESTAMP' ){
									// Ignore

								}

							}
						}
					}
				}
			}
		}
		if ( ! count( $errors ) ) {
			update_option( 'ec_option_db_version_verified', str_replace( '_', '.', EC_CURRENT_VERSION ) );
		}
		return $errors;
	}

	public function try_repair() {
		global $wpdb;
		$errors = $this->verify_db();
		foreach ( $errors as $error ) {
			$wpdb->query( $error['sql_fix'] );
		}
	}

	public function get_db_errors() {
		global $wpdb;
		$error = "You are missing the following DB tables: ";
		$tables = $this->get_uninstall_tables( );
		$first = true;
		foreach ( $tables as $table ) {
			$result = $wpdb->get_results( $wpdb->prepare( "SHOW TABLES LIKE %s", $table ) );
			if ( 0 == count( $result ) ) {
				if ( ! $first ) {
					$error .= ", ";
				}
				$error .= $table;
				$first = false;
			}
		}
		return $error;
	}

	public function try_db_update() {
		$functions = $this->get_new_update_functions( );
		if ( count( $functions ) ) {
			foreach ( $functions as $function ) {
				if ( method_exists( $this, $function ) ) {
					$this->$function();
				}
			}
		}
		update_option( 'ec_option_db_version_updated', str_replace( '_', '.', EC_CURRENT_VERSION ) );
		$errors = $this->verify_db( );
		if ( ! count( $errors ) ) {
			update_option( 'ec_option_db_version_verified', str_replace( '_', '.', EC_CURRENT_VERSION ) );
		}
	}

	public function get_new_update_functions() {
		$function_list = array(
			'4.3.3' => array(
				'wpeasycart_sql_4_3_3'
			),
			'5.0.0' => array(
				'wpeasycart_sql_5_0_0'
			),
			'5.0.2' => array(
				'wpeasycart_sql_5_0_2'
			),
			'5.1.14' => array(
				'wpeasycart_sql_5_1_14'
			),
			'5.1.16' => array(
				'wpeasycart_sql_5_1_16'
			),
			'5.2.1' => array(
				'wpeasycart_sql_5_2_1'
			),
			'5.2.2' => array(
				'wpeasycart_sql_5_2_2'
			),
			'5.3.4' => array(
				'wpeasycart_sql_5_3_4'
			),
			'5.3.5' => array(
				'wpeasycart_sql_5_3_5'
			),
			'5.3.11' => array(
				'wpeasycart_sql_5_3_11'
			),
			'5.3.14' => array(
				'wpeasycart_sql_5_3_14'
			),
			'5.4.1' => array(
				'wpeasycart_sql_5_4_1'
			),
			'5.4.3' => array(
				'wpeasycart_sql_5_4_3'
			),
			'5.4.6' => array(
				'wpeasycart_sql_5_4_6'
			),
			'5.4.9' => array(
				'wpeasycart_sql_5_4_9'
			),
			'5.5.6' => array(
				'wpeasycart_sql_5_5_6'
			),
		);

		$return_functions = array();
		foreach ( $function_list as $version => $functions ) {
			if ( version_compare( $version, get_option( 'ec_option_db_version_updated' ), '>' ) ) {
				for ( $i = 0; $i < count( $functions ); $i++ ) {
					$return_functions[] = $functions[ $i ];
				}
			}
		}
		update_option( 'ec_option_db_version_updated', str_replace( '_', '.', EC_CURRENT_VERSION ) );
		return $return_functions;
	}

	private function run_initial_update( ){
		if( !get_option( 'ec_option_db_insert_v4' ) || get_option( 'ec_option_db_insert_v4' ) == '0' ){
			global $wpdb;
			$wpdb->query( "ALTER TABLE `ec_menulevel1` CHANGE `order` `menu_order` int(11)" );
			$wpdb->query( "ALTER TABLE `ec_menulevel2` CHANGE `order` `menu_order` int(11)" );
			$wpdb->query( "ALTER TABLE `ec_menulevel3` CHANGE `order` `menu_order` int(11)" );
			$wpdb->query( "ALTER TABLE `ec_pricepoint` CHANGE `order` `pricepoint_order` int(11)" );
			$wpdb->query( "ALTER TABLE `ec_promotion` CHANGE `limit` `promo_limit` int(11)" );
			update_option( 'ec_option_db_insert_v4', 1 );
		}
	}

	/* Database Upgrade Scripts */
	private function wpeasycart_sql_4_3_3() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_country ADD COLUMN vat_b2b_enabled tinyint(1) NOT NULL DEFAULT '1'" );
		$wpdb->query( "ALTER TABLE ec_order ADD COLUMN tip_total float(15,3) NOT NULL DEFAULT '0.000'" );
		$wpdb->query( "ALTER TABLE ec_orderstatus ADD COLUMN is_archieved tinyint(1) DEFAULT '0'" );
		$wpdb->query( "ALTER TABLE ec_taxrate MODIFY stripe_taxrate_id varchar(255) NOT NULL" );
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN tip_amount float(15,3) NOT NULL DEFAULT '0.000'" );
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN tip_rate varchar(10) NOT NULL DEFAULT '0.000'" );
		$wpdb->query( "ALTER TABLE ec_review ADD COLUMN reviewer_name varchar(255) NOT NULL DEFAULT ''" );
	}
	
	private function wpeasycart_sql_5_0_0() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_optionitemimage ADD COLUMN product_images text NULL" );
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN product_images text NULL" );
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN sort_position int(11) NOT NULL DEFAULT '0'" );
	}
	
	private function wpeasycart_sql_5_0_2() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN shopify_id varchar(255) NOT NULL DEFAULT ''" );
	}
	
	private function wpeasycart_sql_5_1_14() {
		global $wpdb;
		$collate = "";
		$max_index_length = 191;
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$wpdb->query( "CREATE TABLE ec_order_log (
		  order_log_id int(11) NOT NULL AUTO_INCREMENT,
		  order_id int(11) NOT NULL DEFAULT '0',
		  order_log_key varchar(100) NOT NULL DEFAULT '',
		  order_log_timestamp timestamp NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY  (order_log_id),
		  UNIQUE KEY order_log_id (order_log_id),
		  KEY order_id (order_id)
		) $collate;
		CREATE TABLE ec_order_log_meta (
		  order_log_meta_id int(11) NOT NULL AUTO_INCREMENT,
		  order_log_id int(11) NOT NULL DEFAULT '0',
		  order_id int(11) NOT NULL DEFAULT '0',
		  order_log_meta_key varchar(100) NOT NULL DEFAULT '',
		  order_log_meta_value text,
		  PRIMARY KEY  (order_log_meta_id),
		  UNIQUE KEY order_log_meta_id (order_log_meta_id),
		  KEY order_log_id (order_log_id),
		  KEY order_id (order_id)
		) $collate;" );
	}
	
	private function wpeasycart_sql_5_1_16() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN amazon_session_id varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN amazon_buyer_id varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN amazon_payment_selection varchar(255) NOT NULL DEFAULT ''" );
	}
	
	private function wpeasycart_sql_5_2_1() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_order ADD COLUMN success_page_shown tinyint(1) NOT NULL DEFAULT 0" );
	}
	
	private function wpeasycart_sql_5_2_2() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_order ADD COLUMN email_other varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN email_other varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_user ADD COLUMN email_other varchar(255) NOT NULL DEFAULT ''" );
	}
	
	private function wpeasycart_sql_5_3_4() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN square_variation_id varchar(255) NOT NULL DEFAULT ''" );
	}
	
	private function wpeasycart_sql_5_3_5() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_category ADD COLUMN is_active tinyint(1) NOT NULL DEFAULT '1'" );
		$wpdb->query( "ALTER TABLE ec_optionitem ADD COLUMN optionitem_enable_custom_price_label tinyint(1) NOT NULL DEFAULT '0'" );
		$wpdb->query( "ALTER TABLE ec_optionitem ADD COLUMN optionitem_custom_price_label text" );
		$wpdb->query( "ALTER TABLE ec_optionitemquantity ADD COLUMN sku varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_optionitemquantity ADD COLUMN price float(15,3) NOT NULL DEFAULT '-1.000'" );
		$wpdb->query( "ALTER TABLE ec_optionitemquantity ADD COLUMN is_enabled tinyint(1) NOT NULL DEFAULT '1'" );
		$wpdb->query( "ALTER TABLE ec_optionitemquantity ADD COLUMN is_stock_tracking_enabled tinyint(1) NOT NULL DEFAULT '1'" );
		$wpdb->query( "ALTER TABLE ec_optionitemquantity ADD COLUMN square_id varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_orderdetail ADD COLUMN use_both_option_types tinyint(1) NOT NULL DEFAULT '0'" );
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN use_both_option_types tinyint(1) NOT NULL DEFAULT '0'" );
	}

	private function wpeasycart_sql_5_3_11() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN ship_to_billing tinyint(1) NOT NULL DEFAULT '0'" );
	}

	private function wpeasycart_sql_5_3_14() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN taxjar_tax_amount varchar(255) NOT NULL DEFAULT ''" );
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN taxjar_address_verified tinyint(1) NOT NULL DEFAULT '0'" );
	}

	private function wpeasycart_sql_5_4_1() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN subscription_shipping_recurring tinyint(1) NOT NULL DEFAULT '0'" );
	}

	private function wpeasycart_sql_5_4_3() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN taxcloud_address_last_verified text" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_price float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_price_onetime float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_price_override float(15,3) NOT NULL DEFAULT '-1.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_price_multiplier float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_price_per_character float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_weight float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_weight_onetime float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_weight_override float(15,3) NOT NULL DEFAULT '-1.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_weight_multiplier float(15,3) NOT NULL DEFAULT '0.00'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_disallow_shipping tinyint(1) NOT NULL DEFAULT '0'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_enable_custom_price_label tinyint(1) NOT NULL DEFAULT '0'" );
		$wpdb->query( "ALTER TABLE ec_order_option ADD COLUMN optionitem_custom_price_label text" );
	}

	private function wpeasycart_sql_5_4_6() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_tempcart_data ADD COLUMN stripe_last_pi_data text" );
	}

	private function wpeasycart_sql_5_4_9() {
		global $wpdb;
		$collate = "";
		$max_index_length = 191;
		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
		$wpdb->query( "CREATE TABLE ec_fee (
		  fee_id int(11) NOT NULL AUTO_INCREMENT,
		  fee_label varchar(512)  NOT NULL DEFAULT '',
		  fee_admin_description text,
		  fee_country text,
		  fee_state text,
		  fee_zip text,
		  fee_city text,
		  fee_category text,
		  fee_role text,
		  fee_zone text,
		  fee_type int(11) NOT NULL DEFAULT '1',
		  fee_rate float(15,3) NOT NULL DEFAULT '0.000',
		  fee_price float(15,3) NOT NULL DEFAULT '0.000',
		  fee_min float(15,3) NOT NULL DEFAULT '0.000',
		  fee_max float(15,3) NOT NULL DEFAULT '-1.000',
		  PRIMARY KEY (fee_id)
		) $collate;" );
		$wpdb->query( "CREATE TABLE ec_order_fee (
		  order_fee_id int(11) NOT NULL AUTO_INCREMENT,
		  order_id int(11) NOT NULL DEFAULT '0',
		  fee_label varchar(512)  NOT NULL DEFAULT '',
		  fee_rate float(15,3) NOT NULL DEFAULT '0.000',
		  fee_total float(15,3) NOT NULL DEFAULT '0.000',
		  PRIMARY KEY (order_fee_id),
		  KEY order_id (order_id)
		) $collate;" );
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN shipping_restriction tinyint(1) NOT NULL DEFAULT '0'" );
	}

	private function wpeasycart_sql_5_5_6() {
		global $wpdb;
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN enable_price_label int(11) NOT NULL DEFAULT 0" );
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN replace_price_label int(11) NOT NULL DEFAULT 0" );
		$wpdb->query( "ALTER TABLE ec_product ADD COLUMN custom_price_label varchar(512) NOT NULL DEFAULT ''" );
	}
	/* END DATABASE UPGRADE SCRIPTS */

	private function get_uninstall_tables( ){

		$tables = array( 
			"ec_address",
			"ec_affiliate_rule",
			"ec_affiliate_rule_to_affiliate",
			"ec_affiliate_rule_to_product",
			"ec_bundle",
			"ec_category",
			"ec_categoryitem",
			"ec_code",
			"ec_country",
			"ec_customfield",
			"ec_customfielddata",
			"ec_download",
			"ec_fee",
			"ec_giftcard",
			"ec_live_rate_cache",
			"ec_manufacturer",
			"ec_menulevel1",
			"ec_menulevel2",
			"ec_menulevel3",
			"ec_option",
			"ec_option_to_product",
			"ec_optionitem",
			"ec_optionitemimage",
			"ec_optionitemquantity",
			"ec_order",
			"ec_order_fee",
			"ec_order_log",
			"ec_order_log_meta",
			"ec_order_option",
			"ec_orderdetail",
			"ec_orderstatus",
			"ec_pageoption",
			"ec_perpage",
			"ec_pricepoint",
			"ec_pricetier",
			"ec_product",
			"ec_product_google_attributes",
			"ec_product_subscriber",
			"ec_promocode",
			"ec_promotion",
			"ec_response",
			"ec_review",
			"ec_role",
			"ec_roleaccess",
			"ec_roleprice",
			"ec_setting",
			"ec_shipping_class",
			"ec_shipping_class_to_rate",
			"ec_shippingrate",
			"ec_state",
			"ec_subscriber",
			"ec_subscription",
			"ec_subscription_plan",
			"ec_taxrate",
			"ec_tempcart",
			"ec_tempcart_data",
			"ec_tempcart_optionitem",
			"ec_timezone",
			"ec_user",
			"ec_webhook",
			"ec_zone",
			"ec_zone_to_location"
		);

		return $tables;

	}

	private function get_schema( ){
		global $wpdb;
		$collate = "";
		$max_index_length = 191;
		if( $wpdb->has_cap( 'collation' ) ){
			$collate = $wpdb->get_charset_collate( );
		}
		$schema = "
CREATE TABLE ec_address (
  address_id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL DEFAULT '0',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  address_line_1 varchar(255) NOT NULL DEFAULT '',
  address_line_2 varchar(255) DEFAULT '',
  city varchar(255) NOT NULL DEFAULT '',
  state varchar(128) NOT NULL DEFAULT '',
  zip varchar(128) NOT NULL DEFAULT '',
  country varchar(255) NOT NULL DEFAULT '',
  phone varchar(255) NOT NULL DEFAULT '',
  company_name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (address_id),
  UNIQUE KEY address_id (address_id),
  KEY ec_address_idx1 (address_id),
  KEY ec_address_idx2 (user_id)
) $collate;
CREATE TABLE ec_affiliate_rule (
  affiliate_rule_id int(11) NOT NULL AUTO_INCREMENT,
  rule_name varchar(255) NOT NULL DEFAULT '',
  rule_type varchar(20) NOT NULL DEFAULT '',
  rule_amount float(15,3) NOT NULL DEFAULT '0.000',
  rule_limit int(11) NOT NULL DEFAULT '0',
  rule_active tinyint(1) NOT NULL DEFAULT '1',
  rule_recurring tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (affiliate_rule_id),
  UNIQUE KEY affiliate_rule_id (affiliate_rule_id)
) $collate;
CREATE TABLE ec_affiliate_rule_to_affiliate (
  rule_to_account_id int(11) NOT NULL AUTO_INCREMENT,
  affiliate_rule_id int(11) NOT NULL DEFAULT '0',
  affiliate_id varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY  (rule_to_account_id),
  UNIQUE KEY rule_to_account_id (rule_to_account_id),
  KEY affiliate_rule_id (affiliate_rule_id) ,
  KEY affiliate_id (affiliate_id)
) $collate;
CREATE TABLE ec_affiliate_rule_to_product (
  rule_to_product_id int(11) NOT NULL AUTO_INCREMENT,
  affiliate_rule_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (rule_to_product_id),
  UNIQUE KEY rule_to_product_id (rule_to_product_id),
  KEY affiliate_rule_id (affiliate_rule_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_bundle (
  bundle_id int(11) NOT NULL AUTO_INCREMENT,
  key_product_id int(11) NOT NULL DEFAULT '0',
  bundled_product_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (bundle_id),
  UNIQUE KEY bundle_id (bundle_id)
) $collate;
CREATE TABLE ec_category (
  category_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  is_active tinyint(1) NOT NULL DEFAULT '1',
  category_name varchar(255) NOT NULL DEFAULT '',
  post_id int(11) NOT NULL DEFAULT '0',
  parent_id int(11) NOT NULL DEFAULT '0',
  short_description text,
  image text,
  featured_category tinyint(1) NOT NULL DEFAULT '0',
  priority int(11) NOT NULL DEFAULT '0',
  square_id varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (category_id),
  UNIQUE KEY category_id (category_id)
) $collate;
CREATE TABLE ec_categoryitem (
  categoryitem_id int(11) NOT NULL AUTO_INCREMENT,
  category_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (categoryitem_id),
  UNIQUE KEY categoryitem_id (categoryitem_id),
  KEY product_id (product_id),
  KEY category_id (category_id)
) $collate;
CREATE TABLE ec_code (
  code_id int(11) NOT NULL AUTO_INCREMENT,
  code_val varchar(255) DEFAULT '',
  product_id int(11) NOT NULL DEFAULT '0',
  orderdetail_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (code_id),
  UNIQUE KEY code_id (code_id),
  KEY product_id (product_id),
  KEY orderdetail_id (orderdetail_id)
) $collate;
CREATE TABLE ec_country (
  id_cnt int(11) NOT NULL AUTO_INCREMENT,
  name_cnt varchar(255) NOT NULL DEFAULT '',
  iso2_cnt varchar(10) NOT NULL DEFAULT '',
  iso3_cnt varchar(10) NOT NULL DEFAULT '',
  sort_order int(11) NOT NULL,
  vat_rate_cnt float(9,3) NOT NULL DEFAULT '0.000',
  ship_to_active tinyint(1) NOT NULL DEFAULT '1',
  stripe_taxrate_id varchar(100) NOT NULL DEFAULT '',
  vat_b2b_enabled tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (id_cnt),
  KEY iso2_cnt (iso2_cnt),
  KEY iso3_cnt (iso3_cnt)
) $collate;
CREATE TABLE ec_customfield (
  customfield_id int(11) NOT NULL AUTO_INCREMENT,
  table_name varchar(30) NOT NULL DEFAULT '',
  field_name varchar(255) NOT NULL DEFAULT '',
  field_label varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (customfield_id),
  UNIQUE KEY customfield_id (customfield_id)
) $collate;
CREATE TABLE ec_customfielddata (
  customfielddata_id int(11) NOT NULL AUTO_INCREMENT,
  customfield_id int(11) DEFAULT NULL,
  table_id int(11) NOT NULL,
  data blob NOT NULL,
  PRIMARY KEY  (customfielddata_id)
) $collate;
CREATE TABLE ec_download (
  download_id varchar($max_index_length) NOT NULL DEFAULT '',
  date_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  download_count int(11) NOT NULL DEFAULT '0',
  order_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  download_file_name text NOT NULL DEFAULT '',
  is_amazon_download tinyint(1) NOT NULL DEFAULT '0',
  amazon_key varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY  (download_id),
  KEY download_order_id (order_id),
  KEY download_product_id (product_id)
) $collate;
CREATE TABLE ec_fee (
  fee_id int(11) NOT NULL AUTO_INCREMENT,
  fee_label varchar(512)  NOT NULL DEFAULT '',
  fee_admin_description text,
  fee_country text,
  fee_state text,
  fee_zip text,
  fee_city text,
  fee_category text,
  fee_role text,
  fee_zone text,
  fee_type int(11) NOT NULL DEFAULT '1',
  fee_rate float(15,3) NOT NULL DEFAULT '0.000',
  fee_price float(15,3) NOT NULL DEFAULT '0.000',
  fee_min float(15,3) NOT NULL DEFAULT '0.000',
  fee_max float(15,3) NOT NULL DEFAULT '-1.000',
  PRIMARY KEY (fee_id)
) $collate;
CREATE TABLE ec_giftcard (
  giftcard_id varchar(20) NOT NULL DEFAULT '',
  amount float(15,3) NOT NULL DEFAULT '0.000',
  message text,
  PRIMARY KEY  (giftcard_id),
  UNIQUE KEY giftcard_id (giftcard_id)
) $collate;
CREATE TABLE ec_live_rate_cache (
  live_rate_cache_id int(11) NOT NULL AUTO_INCREMENT,
  ec_cart_id varchar(255) NOT NULL DEFAULT '',
  rate_data text,
  PRIMARY KEY  (live_rate_cache_id)
) $collate;
CREATE TABLE ec_manufacturer (
  manufacturer_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  clicks int(11) NOT NULL DEFAULT '0',
  post_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (manufacturer_id)
) $collate;
CREATE TABLE ec_menulevel1 (
  menulevel1_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  menu_order int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  seo_keywords varchar(255) NOT NULL DEFAULT '',
  seo_description blob NULL,
  banner_image varchar(255) NOT NULL DEFAULT '',
  post_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (menulevel1_id),
  UNIQUE KEY menu1_menulevel1_id (menulevel1_id)
) $collate;
CREATE TABLE ec_menulevel2 (
  menulevel2_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  menulevel1_id int(11) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  menu_order int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  seo_keywords varchar(255) NOT NULL DEFAULT '',
  seo_description blob NULL,
  banner_image varchar(255) NOT NULL DEFAULT '',
  post_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (menulevel2_id),
  UNIQUE KEY menu2_menulevel2_id (menulevel2_id),
  KEY menu2_menulevel1_id (menulevel1_id)
) $collate;
CREATE TABLE ec_menulevel3 (
  menulevel3_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  menulevel2_id int(11) NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL DEFAULT '',
  menu_order int(11) NOT NULL DEFAULT '0',
  clicks int(11) NOT NULL DEFAULT '0',
  seo_keywords varchar(255) NOT NULL DEFAULT '',
  seo_description blob NULL,
  banner_image varchar(255) NOT NULL DEFAULT '',
  post_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (menulevel3_id),
  UNIQUE KEY menu3_menulevel3_id (menulevel3_id),
  KEY menu3_menulevel2_id (menulevel2_id)
) $collate;
CREATE TABLE ec_option (
  option_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  option_name varchar(128) NOT NULL DEFAULT '',
  option_label text,
  option_type varchar(20) NOT NULL DEFAULT 'combo',
  option_required tinyint(1) NOT NULL DEFAULT '1',
  option_error_text text,
  option_meta text,
  square_id varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (option_id),
  UNIQUE KEY option_option_id (option_id) 
) $collate;
CREATE TABLE ec_option_to_product (
  option_to_product_id int(11) NOT NULL AUTO_INCREMENT,
  option_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  role_label varchar(20) NOT NULL DEFAULT 'all',
  option_order int(11) NOT NULL DEFAULT '0',
  conditional_logic text,
  PRIMARY KEY  (option_to_product_id),
  UNIQUE KEY option_to_product_id (option_to_product_id),
  KEY option_id (option_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_optionitem (
  optionitem_id int(11) NOT NULL AUTO_INCREMENT,
  option_id int(11) NOT NULL DEFAULT '0',
  optionitem_name text,
  optionitem_enable_custom_price_label tinyint(1) NOT NULL DEFAULT '0',
  optionitem_custom_price_label text,
  optionitem_price float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_price_onetime float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_price_override float(15,3) NOT NULL DEFAULT '-1.000',
  optionitem_price_multiplier float(15,3) NOT NULL DEFAULT '0',
  optionitem_price_per_character float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_weight float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_weight_onetime float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_weight_override float(15,3) NOT NULL DEFAULT '-1.000',
  optionitem_weight_multiplier float(15,3) NOT NULL DEFAULT '0',
  optionitem_order int(11) NOT NULL DEFAULT '1',
  optionitem_icon varchar(255) NOT NULL DEFAULT '',
  optionitem_initial_value varchar(255) NOT NULL DEFAULT '',
  optionitem_model_number varchar(255) NOT NULL DEFAULT '',
  optionitem_allow_download tinyint(1) NOT NULL DEFAULT '1',
  optionitem_disallow_shipping tinyint(1) NOT NULL DEFAULT '0',
  optionitem_initially_selected tinyint(1) NOT NULL DEFAULT '0',
  optionitem_download_override_file text,
  optionitem_download_addition_file text,
  square_id varchar(255) NOT NULL DEFAULT '',
  stripe_plan_id varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (optionitem_id),
  KEY option_id (option_id)
) $collate;
CREATE TABLE ec_optionitemimage (
  optionitemimage_id int(11) NOT NULL AUTO_INCREMENT,
  optionitem_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  product_images text NULL,
  image1 text NOT NULL DEFAULT '',
  image2 text NOT NULL DEFAULT '',
  image3 text NOT NULL DEFAULT '',
  image4 text NOT NULL DEFAULT '',
  image5 text NOT NULL DEFAULT '',
  PRIMARY KEY  (optionitemimage_id),
  KEY optionitem_id (optionitem_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_optionitemquantity (
  optionitemquantity_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(17) NOT NULL DEFAULT '0',
  optionitem_id_1 int(11) NOT NULL DEFAULT '0',
  optionitem_id_2 int(11) NOT NULL DEFAULT '0',
  optionitem_id_3 int(11) NOT NULL DEFAULT '0',
  optionitem_id_4 int(11) NOT NULL DEFAULT '0',
  optionitem_id_5 int(11) NOT NULL DEFAULT '0',
  quantity int(11) NOT NULL DEFAULT '0',
  sku varchar(255) NOT NULL DEFAULT '',
  price float(15,3) NOT NULL DEFAULT '-1.000',
  is_enabled tinyint(1) NOT NULL DEFAULT '1',
  is_stock_tracking_enabled tinyint(1) NOT NULL DEFAULT '1',
  square_id varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (optionitemquantity_id),
  UNIQUE KEY optionitemquantity_id (optionitemquantity_id),
  KEY product_id (product_id),
  KEY optionitem_id_1 (optionitem_id_1),
  KEY optionitem_id_2 (optionitem_id_2),
  KEY optionitem_id_3 (optionitem_id_3),
  KEY optionitem_id_4 (optionitem_id_4),
  KEY optionitem_id_5 (optionitem_id_5)
) $collate;
CREATE TABLE ec_order (
  order_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  user_email varchar(255) NOT NULL DEFAULT '',
  user_level varchar(255) NOT NULL DEFAULT 'shopper',
  last_updated datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  order_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  orderstatus_id int(11) NOT NULL DEFAULT '5',
  order_weight float(15,3) NOT NULL DEFAULT '0.000',
  sub_total float(15,3) NOT NULL DEFAULT '0.000',
  tax_total float(15,3) NOT NULL DEFAULT '0.000',
  shipping_total float(15,3) NOT NULL DEFAULT '0.000',
  discount_total float(15,3) NOT NULL DEFAULT '0.000',
  vat_total float(15,3) NOT NULL DEFAULT '0.000',
  vat_rate float(15,3) NOT NULL DEFAULT '0.000',
  duty_total float(15,3) NOT NULL DEFAULT '0.000',
  gst_total float(15,3) NOT NULL DEFAULT '0.000',
  gst_rate float(15,3) NOT NULL DEFAULT '0.000',
  pst_total float(15,3) NOT NULL DEFAULT '0.000',
  pst_rate float(15,3) NOT NULL DEFAULT '0.000',
  hst_total float(15,3) NOT NULL DEFAULT '0.000',
  hst_rate float(15,3) NOT NULL DEFAULT '0.000',
  tip_total float(15,3) NOT NULL DEFAULT '0.000',
  grand_total float(15,3) NOT NULL DEFAULT '0.000',
  refund_total float(15,3) NOT NULL DEFAULT '0.000',
  promo_code varchar(255) NOT NULL DEFAULT '',
  giftcard_id varchar(20) NOT NULL DEFAULT '',
  use_expedited_shipping tinyint(1) NOT NULL DEFAULT '0',
  shipping_method varchar(255) NOT NULL DEFAULT '',
  shipping_carrier varchar(255) NOT NULL DEFAULT '',
  shipping_service_code varchar(255) NOT NULL DEFAULT '',
  tracking_number varchar(255) NOT NULL DEFAULT '',
  billing_first_name varchar(255) NOT NULL DEFAULT '',
  billing_last_name varchar(255) NOT NULL DEFAULT '',
  billing_address_line_1 varchar(255) NOT NULL DEFAULT '',
  billing_address_line_2 varchar(255) NOT NULL DEFAULT '',
  billing_city varchar(255) NOT NULL DEFAULT '',
  billing_state varchar(255) NOT NULL DEFAULT '',
  billing_country varchar(255) NOT NULL DEFAULT '',
  billing_zip varchar(255) NOT NULL DEFAULT '',
  billing_phone varchar(255) NOT NULL DEFAULT '',
  shipping_first_name varchar(255) NOT NULL DEFAULT '',
  shipping_last_name varchar(255) NOT NULL DEFAULT '',
  shipping_address_line_1 varchar(255) NOT NULL DEFAULT '',
  shipping_address_line_2 varchar(255) NOT NULL DEFAULT '',
  shipping_city varchar(255) NOT NULL DEFAULT '',
  shipping_state varchar(255) NOT NULL DEFAULT '',
  shipping_country varchar(255) NOT NULL DEFAULT '',
  shipping_zip varchar(255) NOT NULL DEFAULT '',
  shipping_phone varchar(255) NOT NULL DEFAULT '',
  vat_registration_number varchar(255) NOT NULL DEFAULT '',
  payment_method varchar(255) NOT NULL DEFAULT '',
  paypal_email_id varchar(255) NOT NULL DEFAULT '',
  paypal_transaction_id varchar(255) NOT NULL DEFAULT '',
  paypal_payer_id varchar(255) NOT NULL DEFAULT '',
  order_viewed tinyint(1) NOT NULL DEFAULT '0',
  order_notes text,
  order_customer_notes blob,
  txn_id varchar(50) NOT NULL DEFAULT '',
  payment_txn_id varchar(50) NOT NULL DEFAULT '',
  edit_sequence varchar(50) NOT NULL DEFAULT '',
  quickbooks_status varchar(255) NOT NULL DEFAULT 'Not Queued',
  credit_memo_txn_id varchar(255) NOT NULL DEFAULT '',
  card_holder_name varchar(255) NOT NULL DEFAULT '',
  creditcard_digits varchar(4) NOT NULL DEFAULT '',
  cc_exp_month varchar(2) NOT NULL DEFAULT '',
  cc_exp_year varchar(4) NOT NULL DEFAULT '',
  fraktjakt_order_id varchar(20) NOT NULL DEFAULT '',
  fraktjakt_shipment_id varchar(20) DEFAULT '',
  stripe_charge_id varchar(255) NOT NULL DEFAULT '',
  nets_transaction_id varchar(255) NOT NULL DEFAULT '',
  subscription_id int(11) NOT NULL DEFAULT '0',
  order_gateway varchar(64) NOT NULL DEFAULT '',
  affirm_charge_id varchar(100) NOT NULL DEFAULT '',
  billing_company_name varchar(255) NOT NULL DEFAULT '',
  shipping_company_name varchar(255) NOT NULL DEFAULT '',
  guest_key varchar(255) NOT NULL DEFAULT '',
  agreed_to_terms tinyint(1) NOT NULL DEFAULT '0',
  order_ip_address varchar(255) NOT NULL DEFAULT '',
  gateway_transaction_id varchar(255) NOT NULL DEFAULT '',
  success_page_shown tinyint(1) NOT NULL DEFAULT 0,
  email_other varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (order_id),
  UNIQUE KEY order_id (order_id),
  KEY user_id (user_id),
  KEY giftcard_id (giftcard_id)
) $collate;
CREATE TABLE ec_order_fee (
  order_fee_id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL DEFAULT '0',
  fee_label varchar(512)  NOT NULL DEFAULT '',
  fee_rate float(15,3) NOT NULL DEFAULT '0.000',
  fee_total float(15,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY (order_fee_id),
  KEY order_id (order_id)
) $collate;
CREATE TABLE ec_order_log (
  order_log_id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL DEFAULT '0',
  order_log_key varchar(100) NOT NULL DEFAULT '',
  order_log_timestamp timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (order_log_id),
  UNIQUE KEY order_log_id (order_log_id),
  KEY order_id (order_id)
) $collate;
CREATE TABLE ec_order_log_meta (
  order_log_meta_id int(11) NOT NULL AUTO_INCREMENT,
  order_log_id int(11) NOT NULL DEFAULT '0',
  order_id int(11) NOT NULL DEFAULT '0',
  order_log_meta_key varchar(100) NOT NULL DEFAULT '',
  order_log_meta_value text,
  PRIMARY KEY  (order_log_meta_id),
  UNIQUE KEY order_log_meta_id (order_log_meta_id),
  KEY order_log_id (order_log_id),
  KEY order_id (order_id)
) $collate;
CREATE TABLE ec_order_option (
  order_option_id int(11) NOT NULL AUTO_INCREMENT,
  orderdetail_id int(11) NOT NULL DEFAULT '0',
  option_name varchar(255) NOT NULL DEFAULT '',
  optionitem_name text,
  option_type varchar(20) NOT NULL DEFAULT 'combo',
  option_value text NOT NULL,
  option_price_change varchar(255) NOT NULL DEFAULT '',
  optionitem_price float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_price_onetime float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_price_override float(15,3) NOT NULL DEFAULT '-1.00',
  optionitem_price_multiplier float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_price_per_character float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_weight float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_weight_onetime float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_weight_override float(15,3) NOT NULL DEFAULT '-1.00',
  optionitem_weight_multiplier float(15,3) NOT NULL DEFAULT '0.00',
  optionitem_disallow_shipping tinyint(1) NOT NULL DEFAULT '0',
  optionitem_allow_download tinyint(1) NOT NULL DEFAULT '1',
  optionitem_enable_custom_price_label tinyint(1) NOT NULL DEFAULT '0',
  optionitem_custom_price_label text,
  option_label text,
  option_to_product_id int(11) NOT NULL DEFAULT '0',
  option_order int(11) NOT NULL DEFAULT '0',
  download_override_file text,
  download_addition_file text,
  PRIMARY KEY  (order_option_id),
  UNIQUE KEY order_option_id (order_option_id),
  KEY orderdetail_id (orderdetail_id) 
) $collate;
CREATE TABLE ec_orderdetail (
  orderdetail_id int(11) NOT NULL AUTO_INCREMENT,
  order_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  title varchar(255) DEFAULT NULL,
  model_number varchar(255) NOT NULL,
  order_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  unit_price float(15,3) NOT NULL DEFAULT '0.000',
  total_price float(15,3) NOT NULL DEFAULT '0.000',
  quantity int(11) NOT NULL DEFAULT '0',
  image1 text NOT NULL,
  optionitem_id_1 int(11) NOT NULL DEFAULT '0',
  optionitem_id_2 int(11) NOT NULL DEFAULT '0',
  optionitem_id_3 int(11) NOT NULL DEFAULT '0',
  optionitem_id_4 int(11) NOT NULL DEFAULT '0',
  optionitem_id_5 int(11) NOT NULL DEFAULT '0',
  optionitem_name_1 varchar(128) NOT NULL DEFAULT '',
  optionitem_name_2 varchar(128) NOT NULL DEFAULT '',
  optionitem_name_3 varchar(128) NOT NULL DEFAULT '',
  optionitem_name_4 varchar(128) NOT NULL DEFAULT '',
  optionitem_name_5 varchar(128) NOT NULL DEFAULT '',
  optionitem_label_1 varchar(255) NOT NULL DEFAULT '',
  optionitem_label_2 varchar(255) NOT NULL DEFAULT '',
  optionitem_label_3 varchar(255) NOT NULL DEFAULT '',
  optionitem_label_4 varchar(255) NOT NULL DEFAULT '',
  optionitem_label_5 varchar(255) NOT NULL DEFAULT '',
  optionitem_price_1 float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_price_2 float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_price_3 float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_price_4 float(15,3) NOT NULL DEFAULT '0.000',
  optionitem_price_5 float(15,3) NOT NULL DEFAULT '0.000',
  use_advanced_optionset tinyint(1) NOT NULL DEFAULT '0',
  use_both_option_types tinyint(1) NOT NULL DEFAULT '0',
  giftcard_id varchar(20) NOT NULL DEFAULT '',
  shipper_id int(11) DEFAULT '0',
  shipper_first_name varchar(255) NOT NULL DEFAULT '',
  shipper_last_name varchar(255) NOT NULL DEFAULT '',
  gift_card_message text NULL,
  gift_card_from_name varchar(255) NULL,
  gift_card_to_name varchar(255) NULL,
  is_download tinyint(1) NOT NULL DEFAULT '0',
  is_giftcard tinyint(1) NOT NULL DEFAULT '0',
  is_taxable tinyint(1) NOT NULL DEFAULT '1',
  is_shippable tinyint(1) NOT NULL DEFAULT '1',
  exclude_shippable_calculation tinyint(1) NOT NULL DEFAULT '0',
  download_file_name text NOT NULL DEFAULT '',
  download_key text DEFAULT '',
  maximum_downloads_allowed int(11) NOT NULL DEFAULT '0',
  download_timelimit_seconds int(11) DEFAULT '0',
  is_amazon_download tinyint(1) NOT NULL DEFAULT '0',
  amazon_key varchar(1024) NOT NULL DEFAULT '',
  is_deconetwork tinyint(1) NOT NULL DEFAULT '0',
  deconetwork_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_name varchar(255) NOT NULL DEFAULT '',
  deconetwork_product_code varchar(64) NOT NULL DEFAULT '',
  deconetwork_options varchar(255) NOT NULL DEFAULT '',
  deconetwork_color_code varchar(64) NOT NULL DEFAULT '',
  deconetwork_product_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_image_link varchar(255) NOT NULL DEFAULT '',
  gift_card_email varchar(255) NOT NULL DEFAULT '',
  include_code tinyint(1) NOT NULL DEFAULT '0',
  subscription_signup_fee float(15,3) NOT NULL DEFAULT '0.000',
  stock_adjusted tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (orderdetail_id),
  UNIQUE KEY orderdetail_id (orderdetail_id),
  KEY orderdetail_order_id (order_id),
  KEY orderdetail_product_id (product_id),
  KEY orderdetail_giftcard_id (giftcard_id)
) $collate;
CREATE TABLE ec_orderstatus (
  status_id int(11) NOT NULL AUTO_INCREMENT,
  order_status varchar(255) NOT NULL DEFAULT '',
  is_approved tinyint(1) DEFAULT '0',
  is_archieved tinyint(1) DEFAULT '0',
  PRIMARY KEY  (status_id),
  UNIQUE KEY orderstatus_status_id (status_id)
) $collate;
CREATE TABLE ec_pageoption (
  pageoption_id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) NOT NULL DEFAULT '0',
  option_type varchar(155) NOT NULL DEFAULT '',
  option_value text NOT NULL,
  PRIMARY KEY  (pageoption_id),
  UNIQUE KEY pageoption_id (pageoption_id)
) $collate;
CREATE TABLE ec_perpage (
  perpage_id int(11) NOT NULL AUTO_INCREMENT,
  perpage int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (perpage_id),
  UNIQUE KEY perpageid (perpage_id)
) $collate;
CREATE TABLE ec_pricepoint (
  pricepoint_id int(11) NOT NULL AUTO_INCREMENT,
  is_less_than tinyint(1) NOT NULL DEFAULT 0,
  is_greater_than tinyint(1) NOT NULL DEFAULT 0,
  low_point float(15,3) NOT NULL DEFAULT '0.000',
  high_point float(15,3) DEFAULT '0.000',
  pricepoint_order int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (pricepoint_id),
  UNIQUE KEY pricepoint_pricepoint_id (pricepoint_id)
) $collate;
CREATE TABLE ec_pricetier (
  pricetier_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL DEFAULT '0',
  price float(15,3) NOT NULL DEFAULT '0.000',
  quantity int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY  (pricetier_id),
  UNIQUE KEY pricetier_id (pricetier_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_product (
  product_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  model_number varchar(255) NOT NULL DEFAULT '',
  post_id int(11) NOT NULL DEFAULT '0',
  activate_in_store tinyint(1) NOT NULL DEFAULT 0,
  title varchar(255) NOT NULL DEFAULT '',
  description text NULL,
  specifications text NULL,
  order_completed_note text NULL,
  order_completed_email_note text NULL,
  order_completed_details_note text NULL,
  price float(15,3) NOT NULL DEFAULT '0.000',
  list_price float(15,3) NOT NULL DEFAULT '0.000',
  product_cost float(15,3) NOT NULL DEFAULT '0.000',
  vat_rate float(15,3) NOT NULL DEFAULT '0.000',
  handling_price float(15,3) NOT NULL DEFAULT '0.000',
  handling_price_each float(15,3) NOT NULL DEFAULT '0.000',
  stock_quantity int(7) NOT NULL DEFAULT '0',
  min_purchase_quantity int(11) NOT NULL DEFAULT '0',
  max_purchase_quantity int(11) NOT NULL DEFAULT '0',
  weight float(15,3) NOT NULL DEFAULT '0.000',
  width DOUBLE(15,3) NOT NULL DEFAULT '1.000',
  height DOUBLE(15,3) NOT NULL DEFAULT '1.000',
  length DOUBLE(15,3) NOT NULL DEFAULT '1.000',
  seo_description text NULL,
  seo_keywords varchar(255) NOT NULL DEFAULT '',
  use_specifications tinyint(1) NOT NULL DEFAULT 0,
  use_customer_reviews tinyint(1) NOT NULL DEFAULT 0,
  manufacturer_id int(11) NOT NULL DEFAULT '0',
  download_file_name text NOT NULL DEFAULT '',
  product_images text NULL,
  image1 text NOT NULL DEFAULT '',
  image2 text NOT NULL DEFAULT '',
  image3 text NOT NULL DEFAULT '',
  image4 text NOT NULL DEFAULT '',
  image5 text NOT NULL DEFAULT '',
  option_id_1 int(11) NOT NULL DEFAULT '0',
  option_id_2 int(11) NOT NULL DEFAULT '0',
  option_id_3 int(11) NOT NULL DEFAULT '0',
  option_id_4 int(11) NOT NULL DEFAULT '0',
  option_id_5 int(11) NOT NULL DEFAULT '0',
  use_advanced_optionset tinyint(1) NOT NULL DEFAULT 0,
  use_both_option_types tinyint(1) NOT NULL DEFAULT '0',
  menulevel1_id_1 int(11) NOT NULL DEFAULT '0',
  menulevel1_id_2 int(11) NOT NULL DEFAULT '0',
  menulevel1_id_3 int(11) NOT NULL DEFAULT '0',
  menulevel2_id_1 int(11) NOT NULL DEFAULT '0',
  menulevel2_id_2 int(11) NOT NULL DEFAULT '0',
  menulevel2_id_3 int(11) NOT NULL DEFAULT '0',
  menulevel3_id_1 int(11) NOT NULL DEFAULT '0',
  menulevel3_id_2 int(11) NOT NULL DEFAULT '0',
  menulevel3_id_3 int(11) NOT NULL DEFAULT '0',
  featured_product_id_1 int(11) NOT NULL DEFAULT '0',
  featured_product_id_2 int(11) NOT NULL DEFAULT '0',
  featured_product_id_3 int(11) NOT NULL DEFAULT '0',
  featured_product_id_4 int(11) NOT NULL DEFAULT '0',
  is_giftcard tinyint(1) NOT NULL DEFAULT 0,
  is_download tinyint(1) NOT NULL DEFAULT 0,
  is_donation tinyint(1) NOT NULL DEFAULT 0,
  is_special tinyint(1) NOT NULL DEFAULT 0,
  is_taxable tinyint(1) NOT NULL DEFAULT 1,
  is_shippable tinyint(1) NOT NULL DEFAULT 1,
  exclude_shippable_calculation tinyint(1) NOT NULL DEFAULT 0,
  is_subscription_item tinyint(1) NOT NULL DEFAULT 0,
  is_preorder tinyint(1) NOT NULL DEFAULT 0,
  role_id tinyint(1) NOT NULL DEFAULT 0,
  added_to_db_date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  show_on_startup tinyint(1) NOT NULL DEFAULT 0,
  use_optionitem_images tinyint(1) NOT NULL DEFAULT 0,
  use_optionitem_quantity_tracking tinyint(1) NOT NULL DEFAULT 0,
  views int(11) NOT NULL DEFAULT '0',
  last_viewed datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  show_stock_quantity tinyint(1) NOT NULL DEFAULT 1,
  maximum_downloads_allowed int(11) NOT NULL DEFAULT '0',
  download_timelimit_seconds int(11) NOT NULL DEFAULT '0',
  list_id varchar(50) NOT NULL DEFAULT '',
  edit_sequence varchar(55) NOT NULL DEFAULT '',
  quickbooks_status varchar(255) NOT NULL DEFAULT 'Not Queued',
  income_account_ref varchar(255) NOT NULL DEFAULT 'Online Sales',
  cogs_account_ref varchar(255) NOT NULL DEFAULT 'Cost of Goods Sold',
  asset_account_ref varchar(255) NOT NULL DEFAULT 'Inventory Asset',
  quickbooks_parent_name varchar(255) NOT NULL DEFAULT '',
  quickbooks_parent_list_id varchar(255) NOT NULL DEFAULT '',
  subscription_bill_length int(11) NOT NULL DEFAULT '1',
  subscription_bill_period varchar(20) NOT NULL DEFAULT 'M',
  subscription_bill_duration int(11) NOT NULL DEFAULT '0',
  subscription_shipping_recurring tinyint(1) NOT NULL DEFAULT '0',
  trial_period_days int(11) NOT NULL DEFAULT '0',
  stripe_plan_added tinyint(1) NOT NULL DEFAULT 0,
  subscription_plan_id int(11) NOT NULL DEFAULT '0',
  allow_multiple_subscription_purchases tinyint(1) NOT NULL DEFAULT 1,
  membership_page varchar(255) NOT NULL DEFAULT '',
  is_amazon_download tinyint(1) NOT NULL DEFAULT 0,
  amazon_key varchar(1024) NOT NULL DEFAULT '',
  catalog_mode tinyint(1) NOT NULL DEFAULT 0,
  catalog_mode_phrase varchar(1024) DEFAULT NULL,
  inquiry_mode tinyint(1) NOT NULL DEFAULT 0,
  inquiry_url varchar(1024) DEFAULT NULL,
  is_deconetwork tinyint(1) NOT NULL DEFAULT 0,
  deconetwork_mode varchar(64) NOT NULL DEFAULT 'designer',
  deconetwork_product_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_size_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_color_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_design_id varchar(64) NOT NULL DEFAULT '',
  short_description text NULL,
  display_type int(11) NOT NULL DEFAULT '1',
  image_hover_type int(11) NOT NULL DEFAULT '3',
  tag_type int(11) NOT NULL DEFAULT '0',
  tag_bg_color varchar(20) NOT NULL DEFAULT '',
  tag_text_color varchar(20) NOT NULL DEFAULT '',
  tag_text varchar(255) NOT NULL DEFAULT '',
  image_effect_type varchar(20) NOT NULL DEFAULT 'none',
  include_code tinyint(1) NOT NULL DEFAULT '0',
  TIC varchar(128) NOT NULL DEFAULT '00000',
  subscription_signup_fee float(15,3) NOT NULL DEFAULT '0.000',
  subscription_unique_id int(11) NOT NULL DEFAULT '0',
  subscription_prorate tinyint(1) NOT NULL DEFAULT '1',
  allow_backorders tinyint(1) NOT NULL DEFAULT '0',
  backorder_fill_date varchar(255) NOT NULL DEFAULT '',
  shipping_class_id int(11) NOT NULL DEFAULT '0',
  show_custom_price_range tinyint(1) NOT NULL DEFAULT '0',
  price_range_low float(15,3) NOT NULL DEFAULT '0.000',
  price_range_high float(15,3) NOT NULL DEFAULT '0.000',
  square_id varchar(255) NOT NULL DEFAULT '',
  square_variation_id varchar(255) NOT NULL DEFAULT '',
  login_for_pricing tinyint(1) NOT NULL DEFAULT '0',
  login_for_pricing_user_level varchar(255) NOT NULL DEFAULT '',
  login_for_pricing_label varchar(255) NOT NULL DEFAULT '',
  sort_position int(11) NOT NULL DEFAULT '0',
  shopify_id varchar(255) NOT NULL DEFAULT '',
  ship_to_billing tinyint(1) NOT NULL DEFAULT '0',
  shipping_restriction tinyint(1) NOT NULL DEFAULT '0',
  enable_price_label int(11) NOT NULL DEFAULT 0,
  replace_price_label int(11) NOT NULL DEFAULT 0,
  custom_price_label varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY  (product_id),
  UNIQUE KEY product_product_id (product_id),
  UNIQUE KEY product_model_number (model_number($max_index_length)),
  KEY product_menulevel1_id_1 (menulevel1_id_1,menulevel2_id_1,menulevel3_id_1),
  KEY product_menulevel1_id_2 (menulevel1_id_2,menulevel2_id_2,menulevel3_id_2),
  KEY product_menulevel1_id_3 (menulevel1_id_3,menulevel2_id_3,menulevel3_id_3),
  KEY product_manufacturer_id (manufacturer_id),
  KEY product_option_id_1 (option_id_1),
  KEY product_option_id_2 (option_id_2),
  KEY product_option_id_3 (option_id_3),
  KEY product_option_id_4 (option_id_4),
  KEY product_option_id_5 (option_id_5)
) $collate;
CREATE TABLE ec_product_google_attributes (
  product_google_attribute_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL DEFAULT '0',
  attribute_value text,
  PRIMARY KEY  (product_google_attribute_id),
  UNIQUE KEY product_google_attribute_id (product_google_attribute_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_product_subscriber (
  product_subscriber_id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(1024) NOT NULL DEFAULT '',
  product_id int(11) NOT NULL DEFAULT '0',
  status varchar(100) NOT NULL DEFAULT 'subscribed',
  last_notified datetime DEFAULT NULL,
  PRIMARY KEY  (product_subscriber_id),
  UNIQUE KEY product_subscriber_id (product_subscriber_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_promocode (
  promocode_id varchar($max_index_length) NOT NULL DEFAULT '',
  is_dollar_based tinyint(1) NOT NULL DEFAULT '0',
  is_percentage_based tinyint(1) NOT NULL DEFAULT '0',
  is_shipping_based tinyint(1) NOT NULL DEFAULT '0',
  is_free_item_based tinyint(1) NOT NULL DEFAULT '0',
  is_for_me_based tinyint(1) NOT NULL DEFAULT '0',
  is_bogo_based tinyint(1) NOT NULL DEFAULT '0',
  by_manufacturer_id tinyint(1) NOT NULL DEFAULT '0',
  by_category_id tinyint(1) NOT NULL DEFAULT '0',
  by_product_id tinyint(1) NOT NULL DEFAULT '0',
  by_all_products int(11) NOT NULL DEFAULT '0',
  promo_dollar float(15,3) NOT NULL DEFAULT '0.000',
  promo_percentage float(15,3) NOT NULL DEFAULT '0.000',
  promo_shipping float(15,3) NOT NULL DEFAULT '0.000',
  promo_free_item float(15,3) NOT NULL DEFAULT '0.000',
  promo_for_me float(15,3) NOT NULL DEFAULT '0.000',
  promo_bogo_dollar float(15,3) NOT NULL DEFAULT '0.000',
  promo_bogo_percentage float(15,3) NOT NULL DEFAULT '0.000',
  manufacturer_id int(11) NOT NULL DEFAULT '0',
  category_id int(11) NOT NULL DEFAULT '0',
  product_id int(11) NOT NULL DEFAULT '0',
  message blob NOT NULL,
  max_redemptions int(11) NOT NULL DEFAULT '999',
  times_redeemed int(11) NOT NULL DEFAULT '0',
  expiration_date datetime DEFAULT NULL,
  duration varchar(20) NOT NULL DEFAULT 'forever',
  duration_in_months int(11) NOT NULL DEFAULT '1',
  minimum_required int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (promocode_id),
  KEY promo_manufacturer_id (manufacturer_id),
  KEY promo_product_id (product_id)
) $collate;
CREATE TABLE ec_promotion (
  promotion_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  type int(11) NOT NULL DEFAULT '0',
  start_date datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  end_date datetime DEFAULT '0000-00-00 00:00:00',
  product_id_1 int(11) NOT NULL DEFAULT '0',
  product_id_2 int(11) NOT NULL DEFAULT '0',
  product_id_3 int(11) NOT NULL DEFAULT '0',
  manufacturer_id_1 int(11) NOT NULL DEFAULT '0',
  manufacturer_id_2 int(11) NOT NULL DEFAULT '0',
  manufacturer_id_3 int(11) NOT NULL DEFAULT '0',
  category_id_1 int(11) NOT NULL DEFAULT '0',
  category_id_2 int(11) NOT NULL DEFAULT '0',
  category_id_3 int(11) NOT NULL DEFAULT '0',
  price1 float(15,3) NOT NULL DEFAULT '0.000',
  price2 float(15,3) NOT NULL DEFAULT '0.000',
  price3 float(15,3) NOT NULL DEFAULT '0.000',
  percentage1 float(15,3) NOT NULL DEFAULT '0.000',
  percentage2 float(15,3) NOT NULL DEFAULT '0.000',
  percentage3 float(15,3) NOT NULL DEFAULT '0.000',
  number1 int(11) NOT NULL DEFAULT '0',
  number2 int(11) NOT NULL DEFAULT '0',
  number3 int(11) NOT NULL DEFAULT '0',
  promo_limit int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY  (promotion_id),
  UNIQUE KEY promotion_promotion_id (promotion_id),
  KEY promotion_product_id_1 (product_id_1),
  KEY promotion_product_id_2 (product_id_2),
  KEY promotion_product_id_3 (product_id_3),
  KEY promotion_manufacturer_id_1 (manufacturer_id_1),
  KEY promotion_manufacturer_id_2 (manufacturer_id_2),
  KEY promotion_manufacturer_id_3 (manufacturer_id_3),
  KEY promotion_category_id_1 (category_id_1),
  KEY promotion_category_id_2 (category_id_2),
  KEY promotion_category_id_3 (category_id_3)
) $collate;
CREATE TABLE ec_response (
  response_id int(11) NOT NULL AUTO_INCREMENT,
  is_error tinyint(1) NOT NULL DEFAULT '0',
  processor varchar(255) DEFAULT NULL,
  order_id int(11) DEFAULT NULL,
  response_time timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  response_text text,
  PRIMARY KEY  (response_id),
  KEY order_id (order_id) 
) $collate;
CREATE TABLE ec_review (
  review_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  approved tinyint(1) NOT NULL DEFAULT '0',
  rating int(2) NOT NULL DEFAULT '0',
  title varchar(255) NOT NULL DEFAULT '',
  description mediumblob NOT NULL,
  date_submitted timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  reviewer_name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (review_id),
  UNIQUE KEY review_id (review_id),
  KEY product_id (product_id),
  KEY user_id (user_id)
) $collate;
CREATE TABLE ec_role (
  role_id int(11) NOT NULL AUTO_INCREMENT,
  role_label varchar($max_index_length) NOT NULL DEFAULT '',
  admin_access tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (role_id),
  UNIQUE KEY role_id (role_id),
  KEY role_label (role_label)
) $collate;
CREATE TABLE ec_roleaccess (
  roleaccess_id int(11) NOT NULL AUTO_INCREMENT,
  role_label varchar($max_index_length) NOT NULL DEFAULT '',
  admin_panel varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (roleaccess_id),
  UNIQUE KEY roleaccess_id (roleaccess_id),
  KEY role_label (role_label)
) $collate;
CREATE TABLE ec_roleprice (
  roleprice_id int(11) NOT NULL AUTO_INCREMENT,
  product_id int(11) NOT NULL DEFAULT '0',
  role_label varchar($max_index_length) NOT NULL DEFAULT '',
  role_price float(15,3) NOT NULL DEFAULT '0.000',
  PRIMARY KEY  (roleprice_id),
  UNIQUE KEY roleprice_id (roleprice_id),
  KEY product_id (product_id) ,
  KEY role_label (role_label) 
) $collate;
CREATE TABLE ec_setting (
  setting_id int(11) NOT NULL AUTO_INCREMENT,
  site_url varchar(255) NOT NULL DEFAULT '',
  reg_code varchar(255) NOT NULL DEFAULT '',
  storeversion varchar(20) NOT NULL DEFAULT '',
  storetype varchar(20) NOT NULL DEFAULT 'wordpress',
  storepage varchar(255) NOT NULL DEFAULT 'store',
  cartpage varchar(255) NOT NULL DEFAULT 'cart',
  accountpage varchar(255) NOT NULL DEFAULT 'account',
  timezone varchar(255) NOT NULL DEFAULT 'Europe/London',
  shipping_method varchar(255) NOT NULL DEFAULT 'method',
  shipping_expedite_rate float(11,2) NOT NULL DEFAULT '0.00',
  shipping_handling_rate float(11,2) NOT NULL DEFAULT '0.00',
  ups_access_license_number varchar(255) NOT NULL DEFAULT '',
  ups_user_id varchar(255) NOT NULL DEFAULT '',
  ups_password varchar(255) NOT NULL DEFAULT '',
  ups_ship_from_zip varchar(20) NOT NULL DEFAULT '',
  ups_shipper_number varchar(20) NOT NULL DEFAULT '',
  ups_country_code varchar(9) NOT NULL DEFAULT 'US',
  ups_weight_type varchar(19) NOT NULL DEFAULT 'LBS',
  ups_conversion_rate float(9,3) NOT NULL DEFAULT '1.000',
  usps_user_name varchar(255) NOT NULL DEFAULT '',
  usps_ship_from_zip varchar(20) NOT NULL DEFAULT '',
  fedex_key varchar(255) NOT NULL DEFAULT '',
  fedex_account_number varchar(255) NOT NULL DEFAULT '',
  fedex_meter_number varchar(255) NOT NULL DEFAULT '',
  fedex_password varchar(255) NOT NULL DEFAULT '',
  fedex_ship_from_zip varchar(255) NOT NULL DEFAULT '',
  fedex_weight_units varchar(20) NOT NULL DEFAULT 'LB',
  fedex_country_code varchar(20) NOT NULL DEFAULT 'US',
  fedex_conversion_rate float(9,3) NOT NULL DEFAULT '1.000',
  fedex_test_account tinyint(1) NOT NULL DEFAULT '0',
  auspost_api_key varchar(255) NOT NULL DEFAULT '',
  auspost_ship_from_zip varchar(55) NOT NULL DEFAULT '',
  dhl_site_id varchar(155) NOT NULL DEFAULT '',
  dhl_password varchar(155) NOT NULL DEFAULT '',
  dhl_ship_from_country varchar(25) NOT NULL DEFAULT 'US',
  dhl_ship_from_zip varchar(64) NOT NULL DEFAULT '',
  dhl_weight_unit varchar(20) NOT NULL DEFAULT 'LB',
  dhl_test_mode tinyint(1) NOT NULL DEFAULT '0',
  fraktjakt_customer_id varchar(64) NOT NULL DEFAULT '',
  fraktjakt_login_key varchar(64) NOT NULL DEFAULT '',
  fraktjakt_conversion_rate DOUBLE(15,3) NOT NULL DEFAULT '1.000',
  fraktjakt_test_mode tinyint(1) NOT NULL DEFAULT '0',
  fraktjakt_address varchar(120) NOT NULL DEFAULT '',
  fraktjakt_city varchar(55) NOT NULL DEFAULT '',
  fraktjakt_state varchar(2) NOT NULL DEFAULT '',
  fraktjakt_zip varchar(20) NOT NULL DEFAULT '',
  fraktjakt_country varchar(2) NOT NULL DEFAULT '',
  ups_ship_from_state varchar(2) NOT NULL DEFAULT '',
  ups_negotiated_rates tinyint(1) NOT NULL DEFAULT '0',
  canadapost_username varchar(255) NOT NULL DEFAULT '',
  canadapost_password varchar(255) NOT NULL DEFAULT '',
  canadapost_customer_number varchar(255) NOT NULL DEFAULT '',
  canadapost_contract_id varchar(255) NOT NULL DEFAULT '',
  canadapost_test_mode tinyint(1) NOT NULL DEFAULT '0',
  canadapost_ship_from_zip varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY  (setting_id)
) $collate;
CREATE TABLE ec_shipping_class (
  shipping_class_id int(11) NOT NULL AUTO_INCREMENT,
  class_name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (shipping_class_id)
) $collate;
CREATE TABLE ec_shipping_class_to_rate (
  shipping_class_to_rate_id int(11) NOT NULL AUTO_INCREMENT,
  shipping_class_id int(11) NOT NULL DEFAULT '0',
  shipping_rate_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (shipping_class_to_rate_id)
) $collate;
CREATE TABLE ec_shippingrate (
  shippingrate_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  zone_id int(11) NOT NULL DEFAULT '0',
  is_price_based tinyint(1) NOT NULL DEFAULT '0',
  is_weight_based tinyint(1) NOT NULL DEFAULT '0',
  is_method_based tinyint(1) NOT NULL DEFAULT '0',
  is_quantity_based tinyint(1) NOT NULL DEFAULT '0',
  is_percentage_based tinyint(1) NOT NULL DEFAULT '0',
  is_ups_based tinyint(1) NOT NULL DEFAULT '0',
  is_usps_based tinyint(1) NOT NULL DEFAULT '0',
  is_fedex_based tinyint(1) NOT NULL DEFAULT '0',
  is_auspost_based tinyint(1) NOT NULL DEFAULT '0',
  is_dhl_based tinyint(1) NOT NULL DEFAULT '0',
  is_canadapost_based tinyint(1) NOT NULL DEFAULT '0',
  trigger_rate float(15,3) NOT NULL DEFAULT '0.000',
  shipping_rate float(15,3) NOT NULL DEFAULT '0.000',
  shipping_label varchar(255) NOT NULL DEFAULT '',
  shipping_order int(11) NOT NULL DEFAULT '0',
  shipping_code varchar(255) NOT NULL DEFAULT '',
  shipping_override_rate float(11,3) NULL,
  free_shipping_at float(15,3) NOT NULL DEFAULT '-1.000',
  PRIMARY KEY  (shippingrate_id),
  UNIQUE KEY shippingrate_id (shippingrate_id),
  KEY zone_id (zone_id)
) $collate;
CREATE TABLE ec_state (
  id_sta int(11) NOT NULL AUTO_INCREMENT,
  idcnt_sta int(11) NOT NULL DEFAULT '0',
  code_sta varchar($max_index_length) NOT NULL DEFAULT '',
  name_sta varchar(255) NOT NULL DEFAULT '',
  sort_order int(11) NOT NULL DEFAULT '0',
  group_sta varchar(255) NOT NULL DEFAULT '',
  ship_to_active tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (id_sta),
  KEY idcnt_sta (idcnt_sta),
  KEY code_sta (code_sta)
) $collate;
CREATE TABLE ec_subscriber (
  subscriber_id int(11) NOT NULL AUTO_INCREMENT,
  email text NOT NULL DEFAULT '',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (subscriber_id),
  UNIQUE KEY subscriber_email (email($max_index_length))
) $collate;
CREATE TABLE ec_subscription (
  subscription_id int(11) NOT NULL AUTO_INCREMENT,
  subscription_type varchar(255) NOT NULL DEFAULT 'paypal',
  subscription_status varchar(255) NOT NULL DEFAULT 'Active',
  title text NOT NULL DEFAULT '',
  user_id int(11) NOT NULL DEFAULT '0',
  email text NOT NULL DEFAULT '',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  user_country varchar(255) NOT NULL DEFAULT 'US',
  product_id int(11) NOT NULL DEFAULT '0',
  model_number varchar(510) NOT NULL DEFAULT '',
  price double(21,3) NOT NULL DEFAULT '0.000',
  payment_length int(11) NOT NULL DEFAULT '1',
  payment_period varchar(255) NOT NULL DEFAULT '',
  payment_duration int(11) NOT NULL DEFAULT '0',
  start_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_payment_date varchar(255) NOT NULL DEFAULT '',
  next_payment_date varchar(255) NOT NULL DEFAULT '',
  number_payments_completed int(11) NOT NULL DEFAULT '1',
  paypal_txn_id varchar(255) NOT NULL DEFAULT '',
  paypal_txn_type varchar(255) NOT NULL DEFAULT '',
  paypal_subscr_id varchar(255) NOT NULL DEFAULT '',
  paypal_username varchar(255) NOT NULL DEFAULT '',
  paypal_password varchar(255) NOT NULL DEFAULT '',
  stripe_subscription_id varchar(255) NOT NULL DEFAULT '',
  quantity int(11) NOT NULL DEFAULT '1',
  num_failed_payment int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (subscription_id),
  UNIQUE KEY subscription_id (subscription_id),
  KEY user_id (user_id),
  KEY product_id (product_id)
) $collate;
CREATE TABLE ec_subscription_plan (
  subscription_plan_id int(11) NOT NULL AUTO_INCREMENT,
  plan_title varchar(255) NOT NULL DEFAULT '',
  can_downgrade tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (subscription_plan_id)
) $collate;
CREATE TABLE ec_taxrate (
  taxrate_id int(11) NOT NULL AUTO_INCREMENT,
  tax_by_state tinyint(1) NOT NULL DEFAULT '0',
  tax_by_country tinyint(1) NOT NULL DEFAULT '0',
  tax_by_duty tinyint(1) NOT NULL DEFAULT '0',
  tax_by_vat tinyint(1) NOT NULL DEFAULT '0',
  tax_by_single_vat tinyint(1) NOT NULL DEFAULT '0',
  tax_by_all tinyint(1) NOT NULL DEFAULT '0',
  state_rate float(15,3) NOT NULL DEFAULT '0.000',
  country_rate float(15,3) NOT NULL DEFAULT '0.000',
  duty_rate float(15,3) NOT NULL DEFAULT '0.000',
  vat_rate float(15,3) NOT NULL DEFAULT '0.000',
  vat_added tinyint(1) NOT NULL DEFAULT '0',
  vat_included tinyint(1) NOT NULL DEFAULT '0',
  all_rate float(15,3) NOT NULL DEFAULT '0.000',
  state_code varchar(50) NOT NULL DEFAULT '',
  country_code varchar(50) NOT NULL DEFAULT '',
  vat_country_code varchar(50) NOT NULL DEFAULT '',
  duty_exempt_country_code varchar(50) NOT NULL DEFAULT '',
  stripe_taxrate_id varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (taxrate_id),
  UNIQUE KEY taxrate_id (taxrate_id),
  KEY state_code (state_code),
  KEY country_code (country_code),
  KEY vat_country_code (vat_country_code),
  KEY duty_exempt_country_code (duty_exempt_country_code)
) $collate;
CREATE TABLE ec_tempcart (
  tempcart_id int(11) NOT NULL AUTO_INCREMENT,
  session_id varchar(100) DEFAULT NULL,
  product_id int(11) NOT NULL DEFAULT '0',
  quantity int(11) DEFAULT '0',
  grid_quantity int(11) DEFAULT '0',
  gift_card_message blob,
  gift_card_from_name varchar(255) DEFAULT NULL,
  gift_card_to_name varchar(255) DEFAULT NULL,
  optionitem_id_1 int(11) NOT NULL DEFAULT '0',
  optionitem_id_2 int(11) NOT NULL DEFAULT '0',
  optionitem_id_3 int(11) NOT NULL DEFAULT '0',
  optionitem_id_4 int(11) NOT NULL DEFAULT '0',
  optionitem_id_5 int(11) NOT NULL DEFAULT '0',
  donation_price float(15,3) NOT NULL DEFAULT '0.000',
  last_changed_date timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_deconetwork tinyint(1) NOT NULL DEFAULT '0',
  deconetwork_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_name varchar(255) NOT NULL DEFAULT '',
  deconetwork_product_code varchar(64) NOT NULL DEFAULT '',
  deconetwork_options varchar(255) NOT NULL DEFAULT '',
  deconetwork_edit_link varchar(255) NOT NULL DEFAULT '',
  deconetwork_color_code varchar(64) NOT NULL DEFAULT '',
  deconetwork_product_id varchar(64) NOT NULL DEFAULT '',
  deconetwork_image_link varchar(255) NOT NULL DEFAULT '',
  deconetwork_discount float(15,3) NOT NULL DEFAULT '0.000',
  deconetwork_tax float(15,3) NOT NULL DEFAULT '0.000',
  deconetwork_total float(15,3) NOT NULL DEFAULT '0.000',
  deconetwork_version int(11) NOT NULL DEFAULT '1',
  gift_card_email varchar(255) NOT NULL DEFAULT '',
  abandoned_cart_email_sent int(11) NOT NULL DEFAULT '0',
  hide_from_admin tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (tempcart_id),
  UNIQUE KEY tempcart_tempcart_id (tempcart_id),
  KEY tempcart_session_id (session_id),
  KEY tempcart_product_id (product_id),
  KEY tempcart_optionitem_id_1 (optionitem_id_1),
  KEY tempcart_optionitem_id_2 (optionitem_id_2),
  KEY tempcart_optionitem_id_3 (optionitem_id_3),
  KEY tempcart_optionitem_id_4 (optionitem_id_4),
  KEY tempcart_optionitem_id_5 (optionitem_id_5)
) $collate;
CREATE TABLE ec_tempcart_data (
  tempcart_data_id int(11) NOT NULL AUTO_INCREMENT,
  tempcart_time timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  session_id varchar(100) NOT NULL DEFAULT '',
  user_id varchar(255) NOT NULL DEFAULT '',
  email varchar(255) NOT NULL DEFAULT '',
  username varchar(255) NOT NULL DEFAULT '',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  tip_amount float(15,3) NOT NULL DEFAULT '0.000',
  tip_rate varchar(10) NOT NULL DEFAULT '0.000',
  coupon_code varchar(255) NOT NULL DEFAULT '',
  giftcard varchar(255) NOT NULL DEFAULT '',
  billing_first_name varchar(255) NOT NULL DEFAULT '',
  billing_last_name varchar(255) NOT NULL DEFAULT '',
  billing_company_name varchar(255) NOT NULL DEFAULT '',
  billing_address_line_1 varchar(255) NOT NULL DEFAULT '',
  billing_address_line_2 varchar(255) NOT NULL DEFAULT '',
  billing_city varchar(255) NOT NULL DEFAULT '',
  billing_state varchar(255) NOT NULL DEFAULT '',
  billing_zip varchar(255) NOT NULL DEFAULT '',
  billing_country varchar(255) NOT NULL DEFAULT '',
  billing_phone varchar(255) NOT NULL DEFAULT '',
  shipping_selector varchar(255) NOT NULL DEFAULT '',
  shipping_first_name varchar(255) NOT NULL DEFAULT '',
  shipping_last_name varchar(255) NOT NULL DEFAULT '',
  shipping_company_name varchar(255) NOT NULL DEFAULT '',
  shipping_address_line_2 varchar(255) NOT NULL DEFAULT '',
  shipping_address_line_1 varchar(255) NOT NULL DEFAULT '',
  shipping_city varchar(255) NOT NULL DEFAULT '',
  shipping_state varchar(255) NOT NULL DEFAULT '',
  shipping_zip varchar(255) NOT NULL DEFAULT '',
  shipping_country varchar(255) NOT NULL DEFAULT '',
  shipping_phone varchar(255) NOT NULL DEFAULT '',
  create_account varchar(255) NOT NULL DEFAULT '',
  order_notes text,
  shipping_method varchar(255) NOT NULL DEFAULT '',
  estimate_shipping_zip varchar(255) NOT NULL DEFAULT '',
  expedited_shipping varchar(255) NOT NULL DEFAULT '',
  estimate_shipping_country varchar(255) NOT NULL DEFAULT '',
  is_guest varchar(255) NOT NULL DEFAULT '',
  guest_key varchar(255) NOT NULL DEFAULT '',
  subscription_option1 text,
  subscription_option2 text,
  subscription_option3 text,
  subscription_option4 text,
  subscription_option5 text,
  subscription_advanced_option text,
  subscription_quantity varchar(255) NOT NULL DEFAULT '',
  convert_to varchar(255) NOT NULL DEFAULT '',
  translate_to varchar(255) NOT NULL DEFAULT '',
  taxcloud_tax_amount varchar(255) NOT NULL DEFAULT '',
  taxcloud_address_last_verified text,
  taxcloud_address_verified tinyint(1) NOT NULL DEFAULT '0',
  taxjar_tax_amount varchar(255) NOT NULL DEFAULT '',
  taxjar_address_verified tinyint(1) NOT NULL DEFAULT '0',
  perpage varchar(255) NOT NULL DEFAULT '',
  vat_registration_number varchar(255) NOT NULL DEFAULT '',
  card_error varchar(255) NOT NULL DEFAULT '',
  payment_type varchar(255) NOT NULL DEFAULT '',
  payment_method varchar(255) NOT NULL DEFAULT '',
  stripe_paymentintent_id varchar(255) NOT NULL DEFAULT '',
  stripe_pi_client_secret varchar(255) NOT NULL DEFAULT '',
  amazon_session_id varchar(255) NOT NULL DEFAULT '',
  amazon_buyer_id varchar(255) NOT NULL DEFAULT '',
  amazon_payment_selection varchar(255) NOT NULL DEFAULT '',
  email_other varchar(255) NOT NULL DEFAULT '',
  stripe_last_pi_data text,
  PRIMARY KEY  (tempcart_data_id)
) $collate;
CREATE TABLE ec_tempcart_optionitem (
  tempcart_optionitem_id int(11) NOT NULL AUTO_INCREMENT,
  tempcart_id int(11) NOT NULL DEFAULT '0',
  option_id int(11) NOT NULL DEFAULT '0',
  optionitem_id int(11) NOT NULL DEFAULT '0',
  optionitem_value text NOT NULL,
  session_id varchar(100) NOT NULL DEFAULT '',
  optionitem_model_number text NOT NULL DEFAULT '',
  PRIMARY KEY  (tempcart_optionitem_id),
  UNIQUE KEY tempcart_optionitem_id (tempcart_optionitem_id),
  KEY tempcart_id (tempcart_id),
  KEY option_id (option_id),
  KEY optionitem_id (optionitem_id),
  KEY session_id (session_id)
) $collate;
CREATE TABLE ec_timezone (
  timezone_id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL DEFAULT '',
  identifier varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (timezone_id),
  UNIQUE KEY timezone_id (timezone_id)
) $collate;
CREATE TABLE ec_user (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  is_demo_item tinyint(1) NOT NULL DEFAULT '0',
  email varchar(255) NOT NULL DEFAULT '',
  password varchar(255) NOT NULL DEFAULT '',
  list_id varchar(255) NOT NULL DEFAULT '',
  edit_sequence varchar(255) NOT NULL DEFAULT '',
  quickbooks_status varchar(255) NOT NULL DEFAULT 'Not Queued',
  first_name varchar(255) NOT NULL DEFAULT '',
  last_name varchar(255) NOT NULL DEFAULT '',
  default_billing_address_id int(11) NOT NULL DEFAULT '0',
  default_shipping_address_id int(11) NOT NULL DEFAULT '0',
  user_level varchar(255) NOT NULL DEFAULT 'shopper',
  is_subscriber tinyint(1) NOT NULL DEFAULT '0',
  realauth_registered tinyint(1) NOT NULL DEFAULT '0',
  stripe_customer_id varchar(255) NOT NULL DEFAULT '',
  default_card_type varchar(255) NOT NULL DEFAULT '',
  default_card_last4 varchar(255) NOT NULL DEFAULT '',
  exclude_tax tinyint(1) NOT NULL DEFAULT '0',
  exclude_shipping tinyint(1) NOT NULL DEFAULT '0',
  user_notes text,
  vat_registration_number varchar(255) NOT NULL DEFAULT '',
  email_other varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (user_id),
  UNIQUE KEY user_user_id (user_id),
  UNIQUE KEY user_email (email($max_index_length)),
  KEY user_password (password($max_index_length)),
  KEY user_default_billing_address_id (default_billing_address_id),
  KEY user_default_shipping_address_id (default_shipping_address_id),
  KEY user_user_level (user_level($max_index_length))
) $collate;
CREATE TABLE ec_webhook (
  webhook_id varchar($max_index_length) NOT NULL,
  webhook_type varchar(128) NOT NULL DEFAULT '',
  webhook_data blob,
  PRIMARY KEY  (webhook_id),
  UNIQUE KEY webhook_id (webhook_id)
) $collate;
CREATE TABLE ec_zone (
  zone_id int(11) NOT NULL AUTO_INCREMENT,
  zone_name varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY  (zone_id)
) $collate;
CREATE TABLE ec_zone_to_location (
  zone_to_location_id int(11) NOT NULL AUTO_INCREMENT,
  zone_id int(11) NOT NULL DEFAULT '0',
  iso2_cnt varchar(20) NOT NULL DEFAULT '',
  code_sta varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY  (zone_to_location_id)
) $collate;";
		return $schema;
	}

	public function install_base_data( ){
		global $wpdb;
		$wpdb->insert( 
			"ec_setting",
			array( 
				"setting_id" => "1",
				"site_url" => "",
				"reg_code" => "",
				"storeversion" => "1.0.0",
				"storetype" => "wordpress",
				"storepage" => "6",
				"cartpage" => "7",
				"accountpage" => "8",
				"timezone" => "America/Los_Angeles",
				"shipping_method" => "price",
				"shipping_expedite_rate" => "0",
				"shipping_handling_rate" => "0",
				"ups_access_license_number" => "",
				"ups_user_id" => "",
				"ups_password" => "",
				"ups_ship_from_zip" => "",
				"ups_shipper_number" => "",
				"ups_country_code" => "",
				"ups_weight_type" => "",
				"ups_conversion_rate" => "1.000",
				"usps_user_name" => "",
				"usps_ship_from_zip" => "",
				"fedex_key" => "",
				"fedex_account_number" => "",
				"fedex_meter_number" => "",
				"fedex_password" => "",
				"fedex_ship_from_zip" => "",
				"fedex_weight_units" => "LB",
				"fedex_country_code" => "US",
				"fedex_conversion_rate" => "1.000",
				"fedex_test_account" => "0",
				"auspost_api_key" => "",
				"auspost_ship_from_zip" => "",
				"dhl_site_id" => "",
				"dhl_password" => "",
				"dhl_ship_from_country" => "",
				"dhl_ship_from_zip" => "",
				"dhl_weight_unit" => "",
				"dhl_test_mode" => "0",
				"fraktjakt_customer_id" => "",
				"fraktjakt_login_key" => "",
				"fraktjakt_conversion_rate" => "1.000",
				"fraktjakt_test_mode" => "0"
			)
		);

		$wpdb->insert( 
			"ec_shippingrate",
			array( 
				"shippingrate_id" => "51",
				"is_price_based" => "1",
				"is_weight_based" => "0",
				"is_method_based" => "0",
				"is_ups_based" => "0",
				"is_usps_based" => "0",
				"is_fedex_based" => "0",
				"trigger_rate" => "0",
				"shipping_rate" => "5",
				"shipping_label" => "",
				"shipping_order" => "0",
				"shipping_code" => "",
				"shipping_override_rate" => "0"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "1",
				"name_cnt" => "Afghanistan",
				"iso2_cnt" => "AF",
				"iso3_cnt" => "AFG",
				"sort_order" => "10"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "2",
				"name_cnt" => "Albania",
				"iso2_cnt" => "AL",
				"iso3_cnt" => "ALB",
				"sort_order" => "11"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "3",
				"name_cnt" => "Algeria",
				"iso2_cnt" => "DZ",
				"iso3_cnt" => "DZA",
				"sort_order" => "12"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "4",
				"name_cnt" => "American Samoa",
				"iso2_cnt" => "AS",
				"iso3_cnt" => "ASM",
				"sort_order" => "13"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "5",
				"name_cnt" => "Andorra",
				"iso2_cnt" => "AD",
				"iso3_cnt" => "AND",
				"sort_order" => "14"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "6",
				"name_cnt" => "Angola",
				"iso2_cnt" => "AO",
				"iso3_cnt" => "AGO",
				"sort_order" => "15"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "7",
				"name_cnt" => "Anguilla",
				"iso2_cnt" => "AI",
				"iso3_cnt" => "AIA",
				"sort_order" => "16"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "8",
				"name_cnt" => "Antarctica",
				"iso2_cnt" => "AQ",
				"iso3_cnt" => "ATA",
				"sort_order" => "17"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "9",
				"name_cnt" => "Antigua and Barbuda",
				"iso2_cnt" => "AG",
				"iso3_cnt" => "ATG",
				"sort_order" => "18"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "10",
				"name_cnt" => "Argentina",
				"iso2_cnt" => "AR",
				"iso3_cnt" => "ARG",
				"sort_order" => "19"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "11",
				"name_cnt" => "Armenia",
				"iso2_cnt" => "AM",
				"iso3_cnt" => "ARM",
				"sort_order" => "20"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "12",
				"name_cnt" => "Aruba",
				"iso2_cnt" => "AW",
				"iso3_cnt" => "ABW",
				"sort_order" => "21"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "13",
				"name_cnt" => "Australia",
				"iso2_cnt" => "AU",
				"iso3_cnt" => "AUS",
				"sort_order" => "3"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "14",
				"name_cnt" => "Austria",
				"iso2_cnt" => "AT",
				"iso3_cnt" => "AUT",
				"sort_order" => "23"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "15",
				"name_cnt" => "Azerbaijan",
				"iso2_cnt" => "AZ",
				"iso3_cnt" => "AZE",
				"sort_order" => "24"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "16",
				"name_cnt" => "Bahamas",
				"iso2_cnt" => "BS",
				"iso3_cnt" => "BHS",
				"sort_order" => "25"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "17",
				"name_cnt" => "Bahrain",
				"iso2_cnt" => "BH",
				"iso3_cnt" => "BHR",
				"sort_order" => "26"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "18",
				"name_cnt" => "Bangladesh",
				"iso2_cnt" => "BD",
				"iso3_cnt" => "BGD",
				"sort_order" => "27"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "19",
				"name_cnt" => "Barbados",
				"iso2_cnt" => "BB",
				"iso3_cnt" => "BRB",
				"sort_order" => "28"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "20",
				"name_cnt" => "Belarus",
				"iso2_cnt" => "BY",
				"iso3_cnt" => "BLR",
				"sort_order" => "29"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "21",
				"name_cnt" => "Belgium",
				"iso2_cnt" => "BE",
				"iso3_cnt" => "BEL",
				"sort_order" => "30"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "22",
				"name_cnt" => "Belize",
				"iso2_cnt" => "BZ",
				"iso3_cnt" => "BLZ",
				"sort_order" => "31"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "23",
				"name_cnt" => "Benin",
				"iso2_cnt" => "BJ",
				"iso3_cnt" => "BEN",
				"sort_order" => "32"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "24",
				"name_cnt" => "Bermuda",
				"iso2_cnt" => "BM",
				"iso3_cnt" => "BMU",
				"sort_order" => "33"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "25",
				"name_cnt" => "Bhutan",
				"iso2_cnt" => "BT",
				"iso3_cnt" => "BTN",
				"sort_order" => "34"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "26",
				"name_cnt" => "Bolivia",
				"iso2_cnt" => "BO",
				"iso3_cnt" => "BOL",
				"sort_order" => "35"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "28",
				"name_cnt" => "Botswana",
				"iso2_cnt" => "BW",
				"iso3_cnt" => "BWA",
				"sort_order" => "36"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "29",
				"name_cnt" => "Bouvet Island",
				"iso2_cnt" => "BV",
				"iso3_cnt" => "BVT",
				"sort_order" => "37"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "30",
				"name_cnt" => "Brazil",
				"iso2_cnt" => "BR",
				"iso3_cnt" => "BRA",
				"sort_order" => "38"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "32",
				"name_cnt" => "Brunei Darussalam",
				"iso2_cnt" => "BN",
				"iso3_cnt" => "BRN",
				"sort_order" => "39"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "33",
				"name_cnt" => "Bulgaria",
				"iso2_cnt" => "BG",
				"iso3_cnt" => "BGR",
				"sort_order" => "40"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "34",
				"name_cnt" => "Burkina Faso",
				"iso2_cnt" => "BF",
				"iso3_cnt" => "BFA",
				"sort_order" => "41"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "35",
				"name_cnt" => "Burundi",
				"iso2_cnt" => "BI",
				"iso3_cnt" => "BDI",
				"sort_order" => "42"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "36",
				"name_cnt" => "Cambodia",
				"iso2_cnt" => "KH",
				"iso3_cnt" => "KHM",
				"sort_order" => "43"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "37",
				"name_cnt" => "Cameroon",
				"iso2_cnt" => "CM",
				"iso3_cnt" => "CMR",
				"sort_order" => "44"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "38",
				"name_cnt" => "Canada",
				"iso2_cnt" => "CA",
				"iso3_cnt" => "CAN",
				"sort_order" => "2"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "39",
				"name_cnt" => "Cape Verde",
				"iso2_cnt" => "CV",
				"iso3_cnt" => "CPV",
				"sort_order" => "46"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "40",
				"name_cnt" => "Cayman Islands",
				"iso2_cnt" => "KY",
				"iso3_cnt" => "CYM",
				"sort_order" => "47"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "42",
				"name_cnt" => "Chad",
				"iso2_cnt" => "TD",
				"iso3_cnt" => "TCD",
				"sort_order" => "48"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "43",
				"name_cnt" => "Chile",
				"iso2_cnt" => "CL",
				"iso3_cnt" => "CHL",
				"sort_order" => "49"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "44",
				"name_cnt" => "China",
				"iso2_cnt" => "CN",
				"iso3_cnt" => "CHN",
				"sort_order" => "50"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "45",
				"name_cnt" => "Christmas Island",
				"iso2_cnt" => "CX",
				"iso3_cnt" => "CXR",
				"sort_order" => "51"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "47",
				"name_cnt" => "Colombia",
				"iso2_cnt" => "CO",
				"iso3_cnt" => "COL",
				"sort_order" => "52"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "48",
				"name_cnt" => "Comoros",
				"iso2_cnt" => "KM",
				"iso3_cnt" => "COM",
				"sort_order" => "53"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "49",
				"name_cnt" => "Congo",
				"iso2_cnt" => "CG",
				"iso3_cnt" => "COG",
				"sort_order" => "54"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "50",
				"name_cnt" => "Cook Islands",
				"iso2_cnt" => "CK",
				"iso3_cnt" => "COK",
				"sort_order" => "55"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "51",
				"name_cnt" => "Costa Rica",
				"iso2_cnt" => "CR",
				"iso3_cnt" => "CRI",
				"sort_order" => "56"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "52",
				"name_cnt" => "Cote D''Ivoire",
				"iso2_cnt" => "CI",
				"iso3_cnt" => "CIV",
				"sort_order" => "57"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "53",
				"name_cnt" => "Croatia",
				"iso2_cnt" => "HR",
				"iso3_cnt" => "HRV",
				"sort_order" => "58"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "54",
				"name_cnt" => "Cuba",
				"iso2_cnt" => "CU",
				"iso3_cnt" => "CUB",
				"sort_order" => "59"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "55",
				"name_cnt" => "Cyprus",
				"iso2_cnt" => "CY",
				"iso3_cnt" => "CYP",
				"sort_order" => "60"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "56",
				"name_cnt" => "Czech Republic",
				"iso2_cnt" => "CZ",
				"iso3_cnt" => "CZE",
				"sort_order" => "61"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "57",
				"name_cnt" => "Denmark",
				"iso2_cnt" => "DK",
				"iso3_cnt" => "DNK",
				"sort_order" => "62"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "58",
				"name_cnt" => "Djibouti",
				"iso2_cnt" => "DJ",
				"iso3_cnt" => "DJI",
				"sort_order" => "63"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "59",
				"name_cnt" => "Dominica",
				"iso2_cnt" => "DM",
				"iso3_cnt" => "DMA",
				"sort_order" => "64"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "60",
				"name_cnt" => "Dominican Republic",
				"iso2_cnt" => "DO",
				"iso3_cnt" => "DOM",
				"sort_order" => "65"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "61",
				"name_cnt" => "East Timor",
				"iso2_cnt" => "TP",
				"iso3_cnt" => "TMP",
				"sort_order" => "66"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "62",
				"name_cnt" => "Ecuador",
				"iso2_cnt" => "EC",
				"iso3_cnt" => "ECU",
				"sort_order" => "67"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "63",
				"name_cnt" => "Egypt",
				"iso2_cnt" => "EG",
				"iso3_cnt" => "EGY",
				"sort_order" => "68"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "64",
				"name_cnt" => "El Salvador",
				"iso2_cnt" => "SV",
				"iso3_cnt" => "SLV",
				"sort_order" => "69"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "65",
				"name_cnt" => "Equatorial Guinea",
				"iso2_cnt" => "GQ",
				"iso3_cnt" => "GNQ",
				"sort_order" => "70"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "66",
				"name_cnt" => "Eritrea",
				"iso2_cnt" => "ER",
				"iso3_cnt" => "ERI",
				"sort_order" => "71"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "67",
				"name_cnt" => "Estonia",
				"iso2_cnt" => "EE",
				"iso3_cnt" => "EST",
				"sort_order" => "72"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "68",
				"name_cnt" => "Ethiopia",
				"iso2_cnt" => "ET",
				"iso3_cnt" => "ETH",
				"sort_order" => "73"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "70",
				"name_cnt" => "Faroe Islands",
				"iso2_cnt" => "FO",
				"iso3_cnt" => "FRO",
				"sort_order" => "74"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "71",
				"name_cnt" => "Fiji",
				"iso2_cnt" => "FJ",
				"iso3_cnt" => "FJI",
				"sort_order" => "75"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "72",
				"name_cnt" => "Finland",
				"iso2_cnt" => "FI",
				"iso3_cnt" => "FIN",
				"sort_order" => "76"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "73",
				"name_cnt" => "France",
				"iso2_cnt" => "FR",
				"iso3_cnt" => "FRA",
				"sort_order" => "77"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "74",
				"name_cnt" => "France, Metropolitan",
				"iso2_cnt" => "FX",
				"iso3_cnt" => "FXX",
				"sort_order" => "78"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "75",
				"name_cnt" => "French Guiana",
				"iso2_cnt" => "GF",
				"iso3_cnt" => "GUF",
				"sort_order" => "79"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "76",
				"name_cnt" => "French Polynesia",
				"iso2_cnt" => "PF",
				"iso3_cnt" => "PYF",
				"sort_order" => "80"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "78",
				"name_cnt" => "Gabon",
				"iso2_cnt" => "GA",
				"iso3_cnt" => "GAB",
				"sort_order" => "81"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "79",
				"name_cnt" => "Gambia",
				"iso2_cnt" => "GM",
				"iso3_cnt" => "GMB",
				"sort_order" => "82"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "80",
				"name_cnt" => "Georgia",
				"iso2_cnt" => "GE",
				"iso3_cnt" => "GEO",
				"sort_order" => "83"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "81",
				"name_cnt" => "Germany",
				"iso2_cnt" => "DE",
				"iso3_cnt" => "DEU",
				"sort_order" => "84"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "82",
				"name_cnt" => "Ghana",
				"iso2_cnt" => "GH",
				"iso3_cnt" => "GHA",
				"sort_order" => "85"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "83",
				"name_cnt" => "Gibraltar",
				"iso2_cnt" => "GI",
				"iso3_cnt" => "GIB",
				"sort_order" => "86"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "84",
				"name_cnt" => "Greece",
				"iso2_cnt" => "GR",
				"iso3_cnt" => "GRC",
				"sort_order" => "87"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "85",
				"name_cnt" => "Greenland",
				"iso2_cnt" => "GL",
				"iso3_cnt" => "GRL",
				"sort_order" => "88"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "86",
				"name_cnt" => "Grenada",
				"iso2_cnt" => "GD",
				"iso3_cnt" => "GRD",
				"sort_order" => "89"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "87",
				"name_cnt" => "Guadeloupe",
				"iso2_cnt" => "GP",
				"iso3_cnt" => "GLP",
				"sort_order" => "90"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "88",
				"name_cnt" => "Guam",
				"iso2_cnt" => "GU",
				"iso3_cnt" => "GUM",
				"sort_order" => "91"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "89",
				"name_cnt" => "Guatemala",
				"iso2_cnt" => "GT",
				"iso3_cnt" => "GTM",
				"sort_order" => "92"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "90",
				"name_cnt" => "Guinea",
				"iso2_cnt" => "GN",
				"iso3_cnt" => "GIN",
				"sort_order" => "93"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "91",
				"name_cnt" => "Guinea-bissau",
				"iso2_cnt" => "GW",
				"iso3_cnt" => "GNB",
				"sort_order" => "94"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "92",
				"name_cnt" => "Guyana",
				"iso2_cnt" => "GY",
				"iso3_cnt" => "GUY",
				"sort_order" => "95"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "93",
				"name_cnt" => "Haiti",
				"iso2_cnt" => "HT",
				"iso3_cnt" => "HTI",
				"sort_order" => "96"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "95",
				"name_cnt" => "Honduras",
				"iso2_cnt" => "HN",
				"iso3_cnt" => "HND",
				"sort_order" => "97"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "96",
				"name_cnt" => "Hong Kong",
				"iso2_cnt" => "HK",
				"iso3_cnt" => "HKG",
				"sort_order" => "98"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "97",
				"name_cnt" => "Hungary",
				"iso2_cnt" => "HU",
				"iso3_cnt" => "HUN",
				"sort_order" => "99"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "98",
				"name_cnt" => "Iceland",
				"iso2_cnt" => "IS",
				"iso3_cnt" => "ISL",
				"sort_order" => "100"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "99",
				"name_cnt" => "India",
				"iso2_cnt" => "IN",
				"iso3_cnt" => "IND",
				"sort_order" => "101"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "100",
				"name_cnt" => "Indonesia",
				"iso2_cnt" => "ID",
				"iso3_cnt" => "IDN",
				"sort_order" => "102"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "102",
				"name_cnt" => "Iraq",
				"iso2_cnt" => "IQ",
				"iso3_cnt" => "IRQ",
				"sort_order" => "103"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "103",
				"name_cnt" => "Ireland",
				"iso2_cnt" => "IE",
				"iso3_cnt" => "IRL",
				"sort_order" => "104"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "104",
				"name_cnt" => "Israel",
				"iso2_cnt" => "IL",
				"iso3_cnt" => "ISR",
				"sort_order" => "105"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "105",
				"name_cnt" => "Italy",
				"iso2_cnt" => "IT",
				"iso3_cnt" => "ITA",
				"sort_order" => "106"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "106",
				"name_cnt" => "Jamaica",
				"iso2_cnt" => "JM",
				"iso3_cnt" => "JAM",
				"sort_order" => "107"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "107",
				"name_cnt" => "Japan",
				"iso2_cnt" => "JP",
				"iso3_cnt" => "JPN",
				"sort_order" => "108"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "108",
				"name_cnt" => "Jordan",
				"iso2_cnt" => "JO",
				"iso3_cnt" => "JOR",
				"sort_order" => "109"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "109",
				"name_cnt" => "Kazakhstan",
				"iso2_cnt" => "KZ",
				"iso3_cnt" => "KAZ",
				"sort_order" => "110"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "110",
				"name_cnt" => "Kenya",
				"iso2_cnt" => "KE",
				"iso3_cnt" => "KEN",
				"sort_order" => "111"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "111",
				"name_cnt" => "Kiribati",
				"iso2_cnt" => "KI",
				"iso3_cnt" => "KIR",
				"sort_order" => "112"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "113",
				"name_cnt" => "Korea, Republic of",
				"iso2_cnt" => "KR",
				"iso3_cnt" => "KOR",
				"sort_order" => "113"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "114",
				"name_cnt" => "Kuwait",
				"iso2_cnt" => "KW",
				"iso3_cnt" => "KWT",
				"sort_order" => "114"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "115",
				"name_cnt" => "Kyrgyzstan",
				"iso2_cnt" => "KG",
				"iso3_cnt" => "KGZ",
				"sort_order" => "115"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "117",
				"name_cnt" => "Latvia",
				"iso2_cnt" => "LV",
				"iso3_cnt" => "LVA",
				"sort_order" => "116"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "118",
				"name_cnt" => "Lebanon",
				"iso2_cnt" => "LB",
				"iso3_cnt" => "LBN",
				"sort_order" => "117"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "119",
				"name_cnt" => "Lesotho",
				"iso2_cnt" => "LS",
				"iso3_cnt" => "LSO",
				"sort_order" => "118"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "120",
				"name_cnt" => "Liberia",
				"iso2_cnt" => "LR",
				"iso3_cnt" => "LBR",
				"sort_order" => "119"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "122",
				"name_cnt" => "Liechtenstein",
				"iso2_cnt" => "LI",
				"iso3_cnt" => "LIE",
				"sort_order" => "120"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "123",
				"name_cnt" => "Lithuania",
				"iso2_cnt" => "LT",
				"iso3_cnt" => "LTU",
				"sort_order" => "121"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "124",
				"name_cnt" => "Luxembourg",
				"iso2_cnt" => "LU",
				"iso3_cnt" => "LUX",
				"sort_order" => "122"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "125",
				"name_cnt" => "Macau",
				"iso2_cnt" => "MO",
				"iso3_cnt" => "MAC",
				"sort_order" => "123"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "127",
				"name_cnt" => "Madagascar",
				"iso2_cnt" => "MG",
				"iso3_cnt" => "MDG",
				"sort_order" => "124"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "128",
				"name_cnt" => "Malawi",
				"iso2_cnt" => "MW",
				"iso3_cnt" => "MWI",
				"sort_order" => "125"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "129",
				"name_cnt" => "Malaysia",
				"iso2_cnt" => "MY",
				"iso3_cnt" => "MYS",
				"sort_order" => "126"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "130",
				"name_cnt" => "Maldives",
				"iso2_cnt" => "MV",
				"iso3_cnt" => "MDV",
				"sort_order" => "127"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "131",
				"name_cnt" => "Mali",
				"iso2_cnt" => "ML",
				"iso3_cnt" => "MLI",
				"sort_order" => "128"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "132",
				"name_cnt" => "Malta",
				"iso2_cnt" => "MT",
				"iso3_cnt" => "MLT",
				"sort_order" => "129"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "133",
				"name_cnt" => "Marshall Islands",
				"iso2_cnt" => "MH",
				"iso3_cnt" => "MHL",
				"sort_order" => "130"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "134",
				"name_cnt" => "Martinique",
				"iso2_cnt" => "MQ",
				"iso3_cnt" => "MTQ",
				"sort_order" => "131"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "135",
				"name_cnt" => "Mauritania",
				"iso2_cnt" => "MR",
				"iso3_cnt" => "MRT",
				"sort_order" => "132"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "136",
				"name_cnt" => "Mauritius",
				"iso2_cnt" => "MU",
				"iso3_cnt" => "MUS",
				"sort_order" => "133"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "137",
				"name_cnt" => "Mayotte",
				"iso2_cnt" => "YT",
				"iso3_cnt" => "MYT",
				"sort_order" => "134"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "138",
				"name_cnt" => "Mexico",
				"iso2_cnt" => "MX",
				"iso3_cnt" => "MEX",
				"sort_order" => "135"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "141",
				"name_cnt" => "Monaco",
				"iso2_cnt" => "MC",
				"iso3_cnt" => "MCO",
				"sort_order" => "136"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "142",
				"name_cnt" => "Mongolia",
				"iso2_cnt" => "MN",
				"iso3_cnt" => "MNG",
				"sort_order" => "137"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "143",
				"name_cnt" => "Montserrat",
				"iso2_cnt" => "MS",
				"iso3_cnt" => "MSR",
				"sort_order" => "138"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "144",
				"name_cnt" => "Morocco",
				"iso2_cnt" => "MA",
				"iso3_cnt" => "MAR",
				"sort_order" => "139"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "145",
				"name_cnt" => "Mozambique",
				"iso2_cnt" => "MZ",
				"iso3_cnt" => "MOZ",
				"sort_order" => "140"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "146",
				"name_cnt" => "Myanmar",
				"iso2_cnt" => "MM",
				"iso3_cnt" => "MMR",
				"sort_order" => "141"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "147",
				"name_cnt" => "Namibia",
				"iso2_cnt" => "NA",
				"iso3_cnt" => "NAM",
				"sort_order" => "142"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "148",
				"name_cnt" => "Nauru",
				"iso2_cnt" => "NR",
				"iso3_cnt" => "NRU",
				"sort_order" => "143"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "149",
				"name_cnt" => "Nepal",
				"iso2_cnt" => "NP",
				"iso3_cnt" => "NPL",
				"sort_order" => "144"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "150",
				"name_cnt" => "Netherlands",
				"iso2_cnt" => "NL",
				"iso3_cnt" => "NLD",
				"sort_order" => "145"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "151",
				"name_cnt" => "Netherlands Antilles",
				"iso2_cnt" => "AN",
				"iso3_cnt" => "ANT",
				"sort_order" => "146"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "152",
				"name_cnt" => "New Caledonia",
				"iso2_cnt" => "NC",
				"iso3_cnt" => "NCL",
				"sort_order" => "147"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "153",
				"name_cnt" => "New Zealand",
				"iso2_cnt" => "NZ",
				"iso3_cnt" => "NZL",
				"sort_order" => "148"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "154",
				"name_cnt" => "Nicaragua",
				"iso2_cnt" => "NI",
				"iso3_cnt" => "NIC",
				"sort_order" => "149"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "155",
				"name_cnt" => "Niger",
				"iso2_cnt" => "NE",
				"iso3_cnt" => "NER",
				"sort_order" => "150"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "156",
				"name_cnt" => "Nigeria",
				"iso2_cnt" => "NG",
				"iso3_cnt" => "NGA",
				"sort_order" => "151"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "157",
				"name_cnt" => "Niue",
				"iso2_cnt" => "NU",
				"iso3_cnt" => "NIU",
				"sort_order" => "152"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "158",
				"name_cnt" => "Norfolk Island",
				"iso2_cnt" => "NF",
				"iso3_cnt" => "NFK",
				"sort_order" => "153"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "160",
				"name_cnt" => "Norway",
				"iso2_cnt" => "NO",
				"iso3_cnt" => "NOR",
				"sort_order" => "154"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "161",
				"name_cnt" => "Oman",
				"iso2_cnt" => "OM",
				"iso3_cnt" => "OMN",
				"sort_order" => "155"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "162",
				"name_cnt" => "Pakistan",
				"iso2_cnt" => "PK",
				"iso3_cnt" => "PAK",
				"sort_order" => "156"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "163",
				"name_cnt" => "Palau",
				"iso2_cnt" => "PW",
				"iso3_cnt" => "PLW",
				"sort_order" => "157"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "164",
				"name_cnt" => "Panama",
				"iso2_cnt" => "PA",
				"iso3_cnt" => "PAN",
				"sort_order" => "158"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "165",
				"name_cnt" => "Papua New Guinea",
				"iso2_cnt" => "PG",
				"iso3_cnt" => "PNG",
				"sort_order" => "159"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "166",
				"name_cnt" => "Paraguay",
				"iso2_cnt" => "PY",
				"iso3_cnt" => "PRY",
				"sort_order" => "160"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "167",
				"name_cnt" => "Peru",
				"iso2_cnt" => "PE",
				"iso3_cnt" => "PER",
				"sort_order" => "161"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "168",
				"name_cnt" => "Philippines",
				"iso2_cnt" => "PH",
				"iso3_cnt" => "PHL",
				"sort_order" => "162"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "169",
				"name_cnt" => "Pitcairn",
				"iso2_cnt" => "PN",
				"iso3_cnt" => "PCN",
				"sort_order" => "163"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "170",
				"name_cnt" => "Poland",
				"iso2_cnt" => "PL",
				"iso3_cnt" => "POL",
				"sort_order" => "164"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "171",
				"name_cnt" => "Portugal",
				"iso2_cnt" => "PT",
				"iso3_cnt" => "PRT",
				"sort_order" => "165"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "172",
				"name_cnt" => "Puerto Rico",
				"iso2_cnt" => "PR",
				"iso3_cnt" => "PRI",
				"sort_order" => "166"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "173",
				"name_cnt" => "Qatar",
				"iso2_cnt" => "QA",
				"iso3_cnt" => "QAT",
				"sort_order" => "167"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "174",
				"name_cnt" => "Reunion",
				"iso2_cnt" => "RE",
				"iso3_cnt" => "REU",
				"sort_order" => "168"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "175",
				"name_cnt" => "Romania",
				"iso2_cnt" => "RO",
				"iso3_cnt" => "ROM",
				"sort_order" => "169"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "176",
				"name_cnt" => "Russian Federation",
				"iso2_cnt" => "RU",
				"iso3_cnt" => "RUS",
				"sort_order" => "170"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "177",
				"name_cnt" => "Rwanda",
				"iso2_cnt" => "RW",
				"iso3_cnt" => "RWA",
				"sort_order" => "171"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "178",
				"name_cnt" => "Saint Kitts and Nevis",
				"iso2_cnt" => "KN",
				"iso3_cnt" => "KNA",
				"sort_order" => "172"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "179",
				"name_cnt" => "Saint Lucia",
				"iso2_cnt" => "LC",
				"iso3_cnt" => "LCA",
				"sort_order" => "173"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "181",
				"name_cnt" => "Samoa",
				"iso2_cnt" => "WS",
				"iso3_cnt" => "WSM",
				"sort_order" => "174"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "182",
				"name_cnt" => "San Marino",
				"iso2_cnt" => "SM",
				"iso3_cnt" => "SMR",
				"sort_order" => "175"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "183",
				"name_cnt" => "Sao Tome and Principe",
				"iso2_cnt" => "ST",
				"iso3_cnt" => "STP",
				"sort_order" => "176"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "184",
				"name_cnt" => "Saudi Arabia",
				"iso2_cnt" => "SA",
				"iso3_cnt" => "SAU",
				"sort_order" => "177"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "185",
				"name_cnt" => "Senegal",
				"iso2_cnt" => "SN",
				"iso3_cnt" => "SEN",
				"sort_order" => "178"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "186",
				"name_cnt" => "Seychelles",
				"iso2_cnt" => "SC",
				"iso3_cnt" => "SYC",
				"sort_order" => "179"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "187",
				"name_cnt" => "Sierra Leone",
				"iso2_cnt" => "SL",
				"iso3_cnt" => "SLE",
				"sort_order" => "180"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "188",
				"name_cnt" => "Singapore",
				"iso2_cnt" => "SG",
				"iso3_cnt" => "SGP",
				"sort_order" => "181"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "240",
				"name_cnt" => "Slovakia",
				"iso2_cnt" => "SK",
				"iso3_cnt" => "SVK",
				"sort_order" => "182"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "190",
				"name_cnt" => "Slovenia",
				"iso2_cnt" => "SI",
				"iso3_cnt" => "SVN",
				"sort_order" => "182"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "191",
				"name_cnt" => "Solomon Islands",
				"iso2_cnt" => "SB",
				"iso3_cnt" => "SLB",
				"sort_order" => "183"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "192",
				"name_cnt" => "Somalia",
				"iso2_cnt" => "SO",
				"iso3_cnt" => "SOM",
				"sort_order" => "184"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "193",
				"name_cnt" => "South Africa",
				"iso2_cnt" => "ZA",
				"iso3_cnt" => "ZAF",
				"sort_order" => "185"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "195",
				"name_cnt" => "Spain",
				"iso2_cnt" => "ES",
				"iso3_cnt" => "ESP",
				"sort_order" => "186"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "196",
				"name_cnt" => "Sri Lanka",
				"iso2_cnt" => "LK",
				"iso3_cnt" => "LKA",
				"sort_order" => "187"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "197",
				"name_cnt" => "St. Helena",
				"iso2_cnt" => "SH",
				"iso3_cnt" => "SHN",
				"sort_order" => "188"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "198",
				"name_cnt" => "St. Pierre and Miquelon",
				"iso2_cnt" => "PM",
				"iso3_cnt" => "SPM",
				"sort_order" => "189"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "199",
				"name_cnt" => "Sudan",
				"iso2_cnt" => "SD",
				"iso3_cnt" => "SDN",
				"sort_order" => "190"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "200",
				"name_cnt" => "Suriname",
				"iso2_cnt" => "SR",
				"iso3_cnt" => "SUR",
				"sort_order" => "191"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "202",
				"name_cnt" => "Swaziland",
				"iso2_cnt" => "SZ",
				"iso3_cnt" => "SWZ",
				"sort_order" => "192"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "203",
				"name_cnt" => "Sweden",
				"iso2_cnt" => "SE",
				"iso3_cnt" => "SWE",
				"sort_order" => "193"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "204",
				"name_cnt" => "Switzerland",
				"iso2_cnt" => "CH",
				"iso3_cnt" => "CHE",
				"sort_order" => "194"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "205",
				"name_cnt" => "Syrian Arab Republic",
				"iso2_cnt" => "SY",
				"iso3_cnt" => "SYR",
				"sort_order" => "195"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "206",
				"name_cnt" => "Taiwan",
				"iso2_cnt" => "TW",
				"iso3_cnt" => "TWN",
				"sort_order" => "196"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "207",
				"name_cnt" => "Tajikistan",
				"iso2_cnt" => "TJ",
				"iso3_cnt" => "TJK",
				"sort_order" => "197"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "209",
				"name_cnt" => "Thailand",
				"iso2_cnt" => "TH",
				"iso3_cnt" => "THA",
				"sort_order" => "198"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "210",
				"name_cnt" => "Togo",
				"iso2_cnt" => "TG",
				"iso3_cnt" => "TGO",
				"sort_order" => "199"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "211",
				"name_cnt" => "Tokelau",
				"iso2_cnt" => "TK",
				"iso3_cnt" => "TKL",
				"sort_order" => "200"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "212",
				"name_cnt" => "Tonga",
				"iso2_cnt" => "TO",
				"iso3_cnt" => "TON",
				"sort_order" => "201"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "213",
				"name_cnt" => "Trinidad and Tobago",
				"iso2_cnt" => "TT",
				"iso3_cnt" => "TTO",
				"sort_order" => "202"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "214",
				"name_cnt" => "Tunisia",
				"iso2_cnt" => "TN",
				"iso3_cnt" => "TUN",
				"sort_order" => "203"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "215",
				"name_cnt" => "Turkey",
				"iso2_cnt" => "TR",
				"iso3_cnt" => "TUR",
				"sort_order" => "204"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "216",
				"name_cnt" => "Turkmenistan",
				"iso2_cnt" => "TM",
				"iso3_cnt" => "TKM",
				"sort_order" => "205"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "217",
				"name_cnt" => "Turks and Caicos Islands",
				"iso2_cnt" => "TC",
				"iso3_cnt" => "TCA",
				"sort_order" => "206"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "218",
				"name_cnt" => "Tuvalu",
				"iso2_cnt" => "TV",
				"iso3_cnt" => "TUV",
				"sort_order" => "207"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "219",
				"name_cnt" => "Uganda",
				"iso2_cnt" => "UG",
				"iso3_cnt" => "UGA",
				"sort_order" => "208"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "220",
				"name_cnt" => "Ukraine",
				"iso2_cnt" => "UA",
				"iso3_cnt" => "UKR",
				"sort_order" => "209"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "221",
				"name_cnt" => "United Arab Emirates",
				"iso2_cnt" => "AE",
				"iso3_cnt" => "ARE",
				"sort_order" => "210"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "222",
				"name_cnt" => "United Kingdom",
				"iso2_cnt" => "GB",
				"iso3_cnt" => "GBR",
				"sort_order" => "211"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "223",
				"name_cnt" => "United States",
				"iso2_cnt" => "US",
				"iso3_cnt" => "USA",
				"sort_order" => "1"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "224",
				"name_cnt" => "US Minor Outlying Islands",
				"iso2_cnt" => "UM",
				"iso3_cnt" => "UMI",
				"sort_order" => "213"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "225",
				"name_cnt" => "Uruguay",
				"iso2_cnt" => "UY",
				"iso3_cnt" => "URY",
				"sort_order" => "214"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "226",
				"name_cnt" => "Uzbekistan",
				"iso2_cnt" => "UZ",
				"iso3_cnt" => "UZB",
				"sort_order" => "215"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "227",
				"name_cnt" => "Vanuatu",
				"iso2_cnt" => "VU",
				"iso3_cnt" => "VUT",
				"sort_order" => "216"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "229",
				"name_cnt" => "Venezuela",
				"iso2_cnt" => "VE",
				"iso3_cnt" => "VEN",
				"sort_order" => "217"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "230",
				"name_cnt" => "Viet Nam",
				"iso2_cnt" => "VN",
				"iso3_cnt" => "VNM",
				"sort_order" => "218"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "231",
				"name_cnt" => "Virgin Islands (British)",
				"iso2_cnt" => "VG",
				"iso3_cnt" => "VGB",
				"sort_order" => "219"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "232",
				"name_cnt" => "Virgin Islands (U.S.)",
				"iso2_cnt" => "VI",
				"iso3_cnt" => "VIR",
				"sort_order" => "220"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "233",
				"name_cnt" => "Wallis and Futuna Islands",
				"iso2_cnt" => "WF",
				"iso3_cnt" => "WLF",
				"sort_order" => "221"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "234",
				"name_cnt" => "Western Sahara",
				"iso2_cnt" => "EH",
				"iso3_cnt" => "ESH",
				"sort_order" => "222"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "235",
				"name_cnt" => "Yemen",
				"iso2_cnt" => "YE",
				"iso3_cnt" => "YEM",
				"sort_order" => "223"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "236",
				"name_cnt" => "Yugoslavia",
				"iso2_cnt" => "YU",
				"iso3_cnt" => "YUG",
				"sort_order" => "224"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "237",
				"name_cnt" => "Zaire",
				"iso2_cnt" => "ZR",
				"iso3_cnt" => "ZAR",
				"sort_order" => "225"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "238",
				"name_cnt" => "Zambia",
				"iso2_cnt" => "ZM",
				"iso3_cnt" => "ZMB",
				"sort_order" => "226"
			)
		);

		$wpdb->insert( 
			"ec_country",
			array( 
				"id_cnt" => "239",
				"name_cnt" => "Zimbabwe",
				"iso2_cnt" => "ZW",
				"iso3_cnt" => "ZWE",
				"sort_order" => "227"
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "1",
				"order_status" => "Status Not Found",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "2",
				"order_status" => "Order Shipped",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "3",
				"order_status" => "Order Confirmed",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "4",
				"order_status" => "Order on Hold",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "5",
				"order_status" => "Order Started",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "6",
				"order_status" => "Card Approved",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "7",
				"order_status" => "Card Denied",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "8",
				"order_status" => "Third Party Pending",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "9",
				"order_status" => "Third Party Error",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "10",
				"order_status" => "Third Party Approved",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "11",
				"order_status" => "Ready for Pickup",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "12",
				"order_status" => "Pending Approval",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "14",
				"order_status" => "Direct Deposit Pending",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "15",
				"order_status" => "Direct Deposit Received",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "16",
				"order_status" => "Refunded Order",
				"is_approved" => "0" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "17",
				"order_status" => "Partial Refund",
				"is_approved" => "1" 
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "18",
				"order_status" => "Order Picked Up",
				"is_approved" => "1"
			)
		);

		$wpdb->insert( 
			"ec_orderstatus",
			array(
				"status_id" => "19",
				"order_status" => "Order Cancelled",
				"is_approved" => "0"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "1",
				"name" => "(GMT-12:00) International Date Line West",
				"identifier" => "Pacific/Wake"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "2",
				"name" => "(GMT-11:00) Midway Island",
				"identifier" => "Pacific/Apia"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "3",
				"name" => "(GMT-11:00) Samoa",
				"identifier" => "Pacific/Apia"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "4",
				"name" => "(GMT-10:00) Hawaii",
				"identifier" => "Pacific/Honolulu"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "5",
				"name" => "(GMT-09:00) Alaska",
				"identifier" => "America/Anchorage"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "6",
				"name" => "(GMT-08:00) Pacific Time (US & Canada) Tijuana",
				"identifier" => "America/Los_Angeles"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "7",
				"name" => "(GMT-07:00) Arizona",
				"identifier" => "America/Phoenix"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "8",
				"name" => "(GMT-07:00) Chihuahua",
				"identifier" => "America/Chihuahua"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "9",
				"name" => "(GMT-07:00) La Paz",
				"identifier" => "America/Chihuahua"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "10",
				"name" => "(GMT-07:00) Mazatlan",
				"identifier" => "America/Chihuahua"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "11",
				"name" => "(GMT-07:00) Mountain Time (US & Canada)",
				"identifier" => "America/Denver"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "12",
				"name" => "(GMT-06:00) Central America",
				"identifier" => "America/Managua"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "13",
				"name" => "(GMT-06:00) Central Time (US & Canada)",
				"identifier" => "America/Chicago"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "14",
				"name" => "(GMT-06:00) Guadalajara",
				"identifier" => "America/Mexico_City"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "15",
				"name" => "(GMT-06:00) Mexico City",
				"identifier" => "America/Mexico_City"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "16",
				"name" => "(GMT-06:00) Monterrey",
				"identifier" => "America/Mexico_City"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "17",
				"name" => "(GMT-06:00) Saskatchewan",
				"identifier" => "America/Regina"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "18",
				"name" => "(GMT-05:00) Bogota",
				"identifier" => "America/Bogota"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "19",
				"name" => "(GMT-05:00) Eastern Time (US & Canada)",
				"identifier" => "America/New_York"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "20",
				"name" => "(GMT-05:00) Indiana (East)",
				"identifier" => "America/Indiana/Indianapolis"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "21",
				"name" => "(GMT-05:00) Lima",
				"identifier" => "America/Bogota"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "22",
				"name" => "(GMT-05:00) Quito",
				"identifier" => "America/Bogota"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "23",
				"name" => "(GMT-04:00) Atlantic Time (Canada)",
				"identifier" => "America/Halifax"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "24",
				"name" => "(GMT-04:00) Caracas",
				"identifier" => "America/Caracas"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "25",
				"name" => "(GMT-04:00) La Paz",
				"identifier" => "America/Caracas"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "26",
				"name" => "(GMT-04:00) Santiago",
				"identifier" => "America/Santiago"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "27",
				"name" => "(GMT-03:30) Newfoundland",
				"identifier" => "America/St_Johns"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "28",
				"name" => "(GMT-03:00) Brasilia",
				"identifier" => "America/Sao_Paulo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "29",
				"name" => "(GMT-03:00) Buenos Aires",
				"identifier" => "America/Argentina/Buenos_Aires"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "30",
				"name" => "(GMT-03:00) Georgetown",
				"identifier" => "America/Argentina/Buenos_Aires"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "31",
				"name" => "(GMT-03:00) Greenland",
				"identifier" => "America/Godthab"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "32",
				"name" => "(GMT-02:00) Mid-Atlantic",
				"identifier" => "America/Noronha"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "33",
				"name" => "(GMT-01:00) Azores",
				"identifier" => "Atlantic/Azores"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "34",
				"name" => "(GMT-01:00) Cape Verde Is.",
				"identifier" => "Atlantic/Cape_Verde"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "35",
				"name" => "(GMT) Casablanca",
				"identifier" => "Africa/Casablanca"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "36",
				"name" => "(GMT) Edinburgh",
				"identifier" => "Europe/London"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "37",
				"name" => "(GMT) Greenwich Mean Time : Dublin",
				"identifier" => "Europe/London"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "38",
				"name" => "(GMT) Lisbon",
				"identifier" => "Europe/London"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "39",
				"name" => "(GMT) London",
				"identifier" => "Europe/London"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "40",
				"name" => "(GMT) Monrovia",
				"identifier" => "Africa/Casablanca"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "41",
				"name" => "(GMT+01:00) Amsterdam",
				"identifier" => "Europe/Berlin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "42",
				"name" => "(GMT+01:00) Belgrade",
				"identifier" => "Europe/Belgrade"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "43",
				"name" => "(GMT+01:00) Berlin",
				"identifier" => "Europe/Berlin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "44",
				"name" => "(GMT+01:00) Bern",
				"identifier" => "Europe/Berlin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "45",
				"name" => "(GMT+01:00) Bratislava",
				"identifier" => "Europe/Belgrade"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "46",
				"name" => "(GMT+01:00) Brussels",
				"identifier" => "Europe/Paris"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "47",
				"name" => "(GMT+01:00) Budapest",
				"identifier" => "Europe/Belgrade"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "48",
				"name" => "(GMT+01:00) Copenhagen",
				"identifier" => "Europe/Paris"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "49",
				"name" => "(GMT+01:00) Ljubljana",
				"identifier" => "Europe/Belgrade"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "50",
				"name" => "(GMT+01:00) Madrid",
				"identifier" => "Europe/Paris"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "51",
				"name" => "(GMT+01:00) Paris",
				"identifier" => "Europe/Paris"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "52",
				"name" => "(GMT+01:00) Prague",
				"identifier" => "Europe/Belgrade"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "53",
				"name" => "(GMT+01:00) Rome",
				"identifier" => "Europe/Berlin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "54",
				"name" => "(GMT+01:00) Sarajevo",
				"identifier" => "Europe/Sarajevo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "55",
				"name" => "(GMT+01:00) Skopje",
				"identifier" => "Europe/Sarajevo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "56",
				"name" => "(GMT+01:00) Stockholm",
				"identifier" => "Europe/Berlin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "57",
				"name" => "(GMT+01:00) Vienna",
				"identifier" => "Europe/Berlin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "58",
				"name" => "(GMT+01:00) Warsaw",
				"identifier" => "Europe/Sarajevo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "59",
				"name" => "(GMT+01:00) West Central Africa",
				"identifier" => "Africa/Lagos"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "60",
				"name" => "(GMT+01:00) Zagreb",
				"identifier" => "Europe/Sarajevo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "61",
				"name" => "(GMT+02:00) Athens",
				"identifier" => "Europe/Istanbul"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "62",
				"name" => "(GMT+02:00) Bucharest",
				"identifier" => "Europe/Bucharest"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "63",
				"name" => "(GMT+02:00) Cairo",
				"identifier" => "Africa/Cairo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "64",
				"name" => "(GMT+02:00) Harare",
				"identifier" => "Africa/Johannesburg"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "65",
				"name" => "(GMT+02:00) Helsinki",
				"identifier" => "Europe/Helsinki"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "66",
				"name" => "(GMT+02:00) Istanbul",
				"identifier" => "Europe/Istanbul"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "67",
				"name" => "(GMT+02:00) Jerusalem",
				"identifier" => "Asia/Jerusalem"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "68",
				"name" => "(GMT+02:00) Kyiv",
				"identifier" => "Europe/Helsinki"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "69",
				"name" => "(GMT+02:00) Minsk",
				"identifier" => "Europe/Istanbul"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "70",
				"name" => "(GMT+02:00) Pretoria",
				"identifier" => "Africa/Johannesburg"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "71",
				"name" => "(GMT+02:00) Riga",
				"identifier" => "Europe/Helsinki"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "72",
				"name" => "(GMT+02:00) Sofia",
				"identifier" => "Europe/Helsinki"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "73",
				"name" => "(GMT+02:00) Tallinn",
				"identifier" => "Europe/Helsinki"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "74",
				"name" => "(GMT+02:00) Vilnius",
				"identifier" => "Europe/Helsinki"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "75",
				"name" => "(GMT+03:00) Baghdad",
				"identifier" => "Asia/Baghdad"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "76",
				"name" => "(GMT+03:00) Kuwait",
				"identifier" => "Asia/Riyadh"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "77",
				"name" => "(GMT+03:00) Moscow",
				"identifier" => "Europe/Moscow"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "78",
				"name" => "(GMT+03:00) Nairobi",
				"identifier" => "Africa/Nairobi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "79",
				"name" => "(GMT+03:00) Riyadh",
				"identifier" => "Asia/Riyadh"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "80",
				"name" => "(GMT+03:00) St. Petersburg",
				"identifier" => "Europe/Moscow"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "81",
				"name" => "(GMT+03:00) Volgograd",
				"identifier" => "Europe/Moscow"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "82",
				"name" => "(GMT+03:30) Tehran",
				"identifier" => "Asia/Tehran"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "83",
				"name" => "(GMT+04:00) Abu Dhabi",
				"identifier" => "Asia/Muscat"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "84",
				"name" => "(GMT+04:00) Baku",
				"identifier" => "Asia/Tbilisi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "85",
				"name" => "(GMT+04:00) Muscat",
				"identifier" => "Asia/Muscat"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "86",
				"name" => "(GMT+04:00) Tbilisi",
				"identifier" => "Asia/Tbilisi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "87",
				"name" => "(GMT+04:00) Yerevan",
				"identifier" => "Asia/Tbilisi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "88",
				"name" => "(GMT+04:30) Kabul",
				"identifier" => "Asia/Kabul"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "89",
				"name" => "(GMT+05:00) Ekaterinburg",
				"identifier" => "Asia/Yekaterinburg"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "90",
				"name" => "(GMT+05:00) Islamabad",
				"identifier" => "Asia/Karachi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "91",
				"name" => "(GMT+05:00) Karachi",
				"identifier" => "Asia/Karachi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "92",
				"name" => "(GMT+05:00) Tashkent",
				"identifier" => "Asia/Karachi"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "93",
				"name" => "(GMT+05:30) Chennai",
				"identifier" => "Asia/Calcutta"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "94",
				"name" => "(GMT+05:30) Kolkata",
				"identifier" => "Asia/Calcutta"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "95",
				"name" => "(GMT+05:30) Mumbai",
				"identifier" => "Asia/Calcutta"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "96",
				"name" => "(GMT+05:30) New Delhi",
				"identifier" => "Asia/Calcutta"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "97",
				"name" => "(GMT+05:45) Kathmandu",
				"identifier" => "Asia/Katmandu"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "98",
				"name" => "(GMT+06:00) Almaty",
				"identifier" => "Asia/Novosibirsk"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "99",
				"name" => "(GMT+06:00) Astana",
				"identifier" => "Asia/Dhaka"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "100",
				"name" => "(GMT+06:00) Dhaka",
				"identifier" => "Asia/Dhaka"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "101",
				"name" => "(GMT+06:00) Novosibirsk",
				"identifier" => "Asia/Novosibirsk"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "102",
				"name" => "(GMT+06:00) Sri Jayawardenepura",
				"identifier" => "Asia/Colombo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "103",
				"name" => "(GMT+06:30) Rangoon",
				"identifier" => "Asia/Rangoon"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "104",
				"name" => "(GMT+07:00) Bangkok",
				"identifier" => "Asia/Bangkok"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "105",
				"name" => "(GMT+07:00) Hanoi",
				"identifier" => "Asia/Bangkok"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "106",
				"name" => "(GMT+07:00) Jakarta",
				"identifier" => "Asia/Bangkok"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "107",
				"name" => "(GMT+07:00) Krasnoyarsk",
				"identifier" => "Asia/Krasnoyarsk"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "108",
				"name" => "(GMT+08:00) Beijing",
				"identifier" => "Asia/Hong_Kong"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "109",
				"name" => "(GMT+08:00) Chongqing",
				"identifier" => "Asia/Hong_Kong"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "110",
				"name" => "(GMT+08:00) Hong Kong",
				"identifier" => "Asia/Hong_Kong"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "111",
				"name" => "(GMT+08:00) Irkutsk",
				"identifier" => "Asia/Irkutsk"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "112",
				"name" => "(GMT+08:00) Kuala Lumpur",
				"identifier" => "Asia/Singapore"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "113",
				"name" => "(GMT+08:00) Perth",
				"identifier" => "Australia/Perth"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "114",
				"name" => "(GMT+08:00) Singapore",
				"identifier" => "Asia/Singapore"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "115",
				"name" => "(GMT+08:00) Taipei",
				"identifier" => "Asia/Taipei"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "116",
				"name" => "(GMT+08:00) Ulaan Bataar",
				"identifier" => "Asia/Irkutsk"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "117",
				"name" => "(GMT+08:00) Urumqi",
				"identifier" => "Asia/Hong_Kong"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "118",
				"name" => "(GMT+09:00) Osaka",
				"identifier" => "Asia/Tokyo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "119",
				"name" => "(GMT+09:00) Sapporo",
				"identifier" => "Asia/Tokyo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "120",
				"name" => "(GMT+09:00) Seoul",
				"identifier" => "Asia/Seoul"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "121",
				"name" => "(GMT+09:00) Tokyo",
				"identifier" => "Asia/Tokyo"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "122",
				"name" => "(GMT+09:00) Yakutsk",
				"identifier" => "Asia/Yakutsk"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "123",
				"name" => "(GMT+09:30) Adelaide",
				"identifier" => "Australia/Adelaide"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "124",
				"name" => "(GMT+09:30) Darwin",
				"identifier" => "Australia/Darwin"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "125",
				"name" => "(GMT+10:00) Brisbane",
				"identifier" => "Australia/Brisbane"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "126",
				"name" => "(GMT+10:00) Canberra",
				"identifier" => "Australia/Sydney"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "127",
				"name" => "(GMT+10:00) Guam",
				"identifier" => "Pacific/Guam"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "128",
				"name" => "(GMT+10:00) Hobart",
				"identifier" => "Australia/Hobart"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "129",
				"name" => "(GMT+10:00) Melbourne",
				"identifier" => "Australia/Sydney"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "130",
				"name" => "(GMT+10:00) Port Moresby",
				"identifier" => "Pacific/Guam"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "131",
				"name" => "(GMT+10:00) Sydney",
				"identifier" => "Australia/Sydney"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "132",
				"name" => "(GMT+10:00) Vladivostok",
				"identifier" => "Asia/Vladivostok"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "133",
				"name" => "(GMT+11:00) Magadan",
				"identifier" => "Asia/Magadan"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "134",
				"name" => "(GMT+11:00) New Caledonia",
				"identifier" => "Asia/Magadan"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "135",
				"name" => "(GMT+11:00) Solomon Is.",
				"identifier" => "Asia/Magadan"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "136",
				"name" => "(GMT+12:00) Auckland",
				"identifier" => "Pacific/Auckland"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "137",
				"name" => "(GMT+12:00) Fiji",
				"identifier" => "Pacific/Fiji"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "138",
				"name" => "(GMT+12:00) Kamchatka",
				"identifier" => "Pacific/Fiji"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "139",
				"name" => "(GMT+12:00) Marshall Is.",
				"identifier" => "Pacific/Fiji"
			)
		);

		$wpdb->insert( 
			"ec_timezone",
			array(
				"timezone_id" => "140",
				"name" => "(GMT+12:00) Wellington",
				"identifier" => "Pacific/Auckland"
			)
		);

		$wpdb->insert( 
			"ec_perpage",
			array(
				"perpage_id" => "1",
				"perpage" => "50"
			)
		);

		$wpdb->insert( 
			"ec_perpage",
			array(
				"perpage_id" => "2",
				"perpage" => "25"
			)
		);

		$wpdb->insert( 
			"ec_perpage",
			array(
				"perpage_id" => "3",
				"perpage" => "10"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "1",
				"is_less_than" => "1",
				"is_greater_than" => "0",
				"low_point" => "0",
				"high_point" => "10",
				"pricepoint_order" => "0"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "2",
				"is_less_than" => "0",
				"is_greater_than" => "0",
				"low_point" => "25",
				"high_point" => "49.99",
				"pricepoint_order" => "4"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "3",
				"is_less_than" => "0",
				"is_greater_than" => "0",
				"low_point" => "50",
				"high_point" => "99.99",
				"pricepoint_order" => "5"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "4",
				"is_less_than" => "0",
				"is_greater_than" => "0",
				"low_point" => "100",
				"high_point" => "299.99",
				"pricepoint_order" => "6"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "5",
				"is_less_than" => "0",
				"is_greater_than" => "2",
				"low_point" => "299.99",
				"high_point" => "0",
				"pricepoint_order" => "7"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "6",
				"is_less_than" => "0",
				"is_greater_than" => "0",
				"low_point" => "10",
				"high_point" => "14.99",
				"pricepoint_order" => "1"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "7",
				"is_less_than" => "0",
				"is_greater_than" => "0",
				"low_point" => "15",
				"high_point" => "19.99",
				"pricepoint_order" => "2"
			)
		);

		$wpdb->insert( 
			"ec_pricepoint",
			array(
				"pricepoint_id" => "8",
				"is_less_than" => "0",
				"is_greater_than" => "0",
				"low_point" => "20",
				"high_point" => "24.99",
				"pricepoint_order" => "3"
			)
		);

		$wpdb->insert( 
			"ec_role",
			array(
				"role_id" => "1",
				"role_label" => "admin",
				"admin_access" => "1"
			)
		);

		$wpdb->insert( 
			"ec_role",
			array(
				"role_id" => "2",
				"role_label" => "shopper",
				"admin_access" => "0"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "1",
				"idcnt_sta" => "223",
				"code_sta" => "AL",
				"name_sta" => "Alabama",
				"sort_order" => "9",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "2",
				"idcnt_sta" => "223",
				"code_sta" => "AK",
				"name_sta" => "Alaska",
				"sort_order" => "10",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "4",
				"idcnt_sta" => "223",
				"code_sta" => "AZ",
				"name_sta" => "Arizona",
				"sort_order" => "11",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "5",
				"idcnt_sta" => "223",
				"code_sta" => "AR",
				"name_sta" => "Arkansas",
				"sort_order" => "12",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "12",
				"idcnt_sta" => "223",
				"code_sta" => "CA",
				"name_sta" => "California",
				"sort_order" => "13",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "13",
				"idcnt_sta" => "223",
				"code_sta" => "CO",
				"name_sta" => "Colorado",
				"sort_order" => "14",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "14",
				"idcnt_sta" => "223",
				"code_sta" => "CT",
				"name_sta" => "Connecticut",
				"sort_order" => "15",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "15",
				"idcnt_sta" => "223",
				"code_sta" => "DE",
				"name_sta" => "Delaware",
				"sort_order" => "16",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "16",
				"idcnt_sta" => "223",
				"code_sta" => "DC",
				"name_sta" => "District of Columbia",
				"sort_order" => "17",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "18",
				"idcnt_sta" => "223",
				"code_sta" => "FL",
				"name_sta" => "Florida",
				"sort_order" => "18",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "19",
				"idcnt_sta" => "223",
				"code_sta" => "GA",
				"name_sta" => "Georgia",
				"sort_order" => "19",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "21",
				"idcnt_sta" => "223",
				"code_sta" => "HI",
				"name_sta" => "Hawaii",
				"sort_order" => "21",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "22",
				"idcnt_sta" => "223",
				"code_sta" => "ID",
				"name_sta" => "Idaho",
				"sort_order" => "22",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "23",
				"idcnt_sta" => "223",
				"code_sta" => "IL",
				"name_sta" => "Illinois",
				"sort_order" => "23",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "24",
				"idcnt_sta" => "223",
				"code_sta" => "IN",
				"name_sta" => "Indiana",
				"sort_order" => "24",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "25",
				"idcnt_sta" => "223",
				"code_sta" => "IA",
				"name_sta" => "Iowa",
				"sort_order" => "25",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "26",
				"idcnt_sta" => "223",
				"code_sta" => "KS",
				"name_sta" => "Kansas",
				"sort_order" => "26",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "27",
				"idcnt_sta" => "223",
				"code_sta" => "KY",
				"name_sta" => "Kentucky",
				"sort_order" => "27",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "28",
				"idcnt_sta" => "223",
				"code_sta" => "LA",
				"name_sta" => "Louisiana",
				"sort_order" => "28",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "29",
				"idcnt_sta" => "223",
				"code_sta" => "ME",
				"name_sta" => "Maine",
				"sort_order" => "29",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "31",
				"idcnt_sta" => "223",
				"code_sta" => "MD",
				"name_sta" => "Maryland",
				"sort_order" => "30",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "32",
				"idcnt_sta" => "223",
				"code_sta" => "MA",
				"name_sta" => "Massachusetts",
				"sort_order" => "31",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "33",
				"idcnt_sta" => "223",
				"code_sta" => "MI",
				"name_sta" => "Michigan",
				"sort_order" => "32",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "34",
				"idcnt_sta" => "223",
				"code_sta" => "MN",
				"name_sta" => "Minnesota",
				"sort_order" => "33",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "35",
				"idcnt_sta" => "223",
				"code_sta" => "MS",
				"name_sta" => "Mississippi",
				"sort_order" => "34",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "36",
				"idcnt_sta" => "223",
				"code_sta" => "MO",
				"name_sta" => "Missouri",
				"sort_order" => "35",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "37",
				"idcnt_sta" => "223",
				"code_sta" => "MT",
				"name_sta" => "Montana",
				"sort_order" => "36",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "38",
				"idcnt_sta" => "223",
				"code_sta" => "NE",
				"name_sta" => "Nebraska",
				"sort_order" => "37",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "39",
				"idcnt_sta" => "223",
				"code_sta" => "NV",
				"name_sta" => "Nevada",
				"sort_order" => "38",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "40",
				"idcnt_sta" => "223",
				"code_sta" => "NH",
				"name_sta" => "New Hampshire",
				"sort_order" => "39",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "41",
				"idcnt_sta" => "223",
				"code_sta" => "NJ",
				"name_sta" => "New Jersey",
				"sort_order" => "40",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "42",
				"idcnt_sta" => "223",
				"code_sta" => "NM",
				"name_sta" => "New Mexico",
				"sort_order" => "41",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "43",
				"idcnt_sta" => "223",
				"code_sta" => "NY",
				"name_sta" => "New York",
				"sort_order" => "42",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "44",
				"idcnt_sta" => "223",
				"code_sta" => "NC",
				"name_sta" => "North Carolina",
				"sort_order" => "43",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "45",
				"idcnt_sta" => "223",
				"code_sta" => "ND",
				"name_sta" => "North Dakota",
				"sort_order" => "44",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "47",
				"idcnt_sta" => "223",
				"code_sta" => "OH",
				"name_sta" => "Ohio",
				"sort_order" => "45",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "48",
				"idcnt_sta" => "223",
				"code_sta" => "OK",
				"name_sta" => "Oklahoma",
				"sort_order" => "46",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "49",
				"idcnt_sta" => "223",
				"code_sta" => "OR",
				"name_sta" => "Oregon",
				"sort_order" => "47",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "51",
				"idcnt_sta" => "223",
				"code_sta" => "PA",
				"name_sta" => "Pennsylvania",
				"sort_order" => "48",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "52",
				"idcnt_sta" => "223",
				"code_sta" => "PR",
				"name_sta" => "Puerto Rico",
				"sort_order" => "49",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "53",
				"idcnt_sta" => "223",
				"code_sta" => "RI",
				"name_sta" => "Rhode Island",
				"sort_order" => "50",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "54",
				"idcnt_sta" => "223",
				"code_sta" => "SC",
				"name_sta" => "South Carolina",
				"sort_order" => "51",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "55",
				"idcnt_sta" => "223",
				"code_sta" => "SD",
				"name_sta" => "South Dakota",
				"sort_order" => "52",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "56",
				"idcnt_sta" => "223",
				"code_sta" => "TN",
				"name_sta" => "Tennessee",
				"sort_order" => "53",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "57",
				"idcnt_sta" => "223",
				"code_sta" => "TX",
				"name_sta" => "Texas",
				"sort_order" => "54",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "58",
				"idcnt_sta" => "223",
				"code_sta" => "UT",
				"name_sta" => "Utah",
				"sort_order" => "55",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "59",
				"idcnt_sta" => "223",
				"code_sta" => "VT",
				"name_sta" => "Vermont",
				"sort_order" => "56",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "60",
				"idcnt_sta" => "223",
				"code_sta" => "VI",
				"name_sta" => "Virgin Islands",
				"sort_order" => "57",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "61",
				"idcnt_sta" => "223",
				"code_sta" => "VA",
				"name_sta" => "Virginia",
				"sort_order" => "58",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "62",
				"idcnt_sta" => "223",
				"code_sta" => "WA",
				"name_sta" => "Washington",
				"sort_order" => "59",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "63",
				"idcnt_sta" => "223",
				"code_sta" => "WV",
				"name_sta" => "West Virginia",
				"sort_order" => "60",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "64",
				"idcnt_sta" => "223",
				"code_sta" => "WI",
				"name_sta" => "Wisconsin",
				"sort_order" => "61",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "65",
				"idcnt_sta" => "223",
				"code_sta" => "WY",
				"name_sta" => "Wyoming",
				"sort_order" => "62",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "66",
				"idcnt_sta" => "38",
				"code_sta" => "AB",
				"name_sta" => "Alberta",
				"sort_order" => "100",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "67",
				"idcnt_sta" => "38",
				"code_sta" => "BC",
				"name_sta" => "British Columbia",
				"sort_order" => "101",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "68",
				"idcnt_sta" => "38",
				"code_sta" => "MB",
				"name_sta" => "Manitoba",
				"sort_order" => "102",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "69",
				"idcnt_sta" => "38",
				"code_sta" => "NF",
				"name_sta" => "Newfoundland",
				"sort_order" => "103",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "70",
				"idcnt_sta" => "38",
				"code_sta" => "NB",
				"name_sta" => "New Brunswick",
				"sort_order" => "104",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "71",
				"idcnt_sta" => "38",
				"code_sta" => "NS",
				"name_sta" => "Nova Scotia",
				"sort_order" => "105",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "72",
				"idcnt_sta" => "38",
				"code_sta" => "NT",
				"name_sta" => "Northwest Territories",
				"sort_order" => "106",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "73",
				"idcnt_sta" => "38",
				"code_sta" => "NU",
				"name_sta" => "Nunavut",
				"sort_order" => "107",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "74",
				"idcnt_sta" => "38",
				"code_sta" => "ON",
				"name_sta" => "Ontario",
				"sort_order" => "108",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "75",
				"idcnt_sta" => "38",
				"code_sta" => "PE",
				"name_sta" => "Prince Edward Island",
				"sort_order" => "109",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "76",
				"idcnt_sta" => "38",
				"code_sta" => "QC",
				"name_sta" => "Quebec",
				"sort_order" => "110",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "77",
				"idcnt_sta" => "38",
				"code_sta" => "SK",
				"name_sta" => "Saskatchewan",
				"sort_order" => "111",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "78",
				"idcnt_sta" => "38",
				"code_sta" => "YT",
				"name_sta" => "Yukon Territory",
				"sort_order" => "112",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "79",
				"idcnt_sta" => "13",
				"code_sta" => "ACT",
				"name_sta" => "Australian Capital Territory",
				"sort_order" => "113",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "80",
				"idcnt_sta" => "13",
				"code_sta" => "CX",
				"name_sta" => "Christmas Island",
				"sort_order" => "114",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "81",
				"idcnt_sta" => "13",
				"code_sta" => "CC",
				"name_sta" => "Cocos Islands",
				"sort_order" => "115",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "82",
				"idcnt_sta" => "13",
				"code_sta" => "HM",
				"name_sta" => "Heard Island and McDonald Islands",
				"sort_order" => "116",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "83",
				"idcnt_sta" => "13",
				"code_sta" => "NSW",
				"name_sta" => "New South Wales",
				"sort_order" => "117",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "84",
				"idcnt_sta" => "13",
				"code_sta" => "NF",
				"name_sta" => "Norfolk Island",
				"sort_order" => "118",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "85",
				"idcnt_sta" => "13",
				"code_sta" => "NT",
				"name_sta" => "Northern Territory",
				"sort_order" => "119",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "86",
				"idcnt_sta" => "13",
				"code_sta" => "QLD",
				"name_sta" => "Queensland",
				"sort_order" => "120",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "87",
				"idcnt_sta" => "13",
				"code_sta" => "SA",
				"name_sta" => "South Australia",
				"sort_order" => "121",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "88",
				"idcnt_sta" => "13",
				"code_sta" => "TAS",
				"name_sta" => "Tasmania",
				"sort_order" => "122",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "89",
				"idcnt_sta" => "13",
				"code_sta" => "VIC",
				"name_sta" => "Victoria",
				"sort_order" => "123",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "90",
				"idcnt_sta" => "13",
				"code_sta" => "WA",
				"name_sta" => "Western Australia",
				"sort_order" => "124",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "91",
				"idcnt_sta" => "222",
				"code_sta" => "Avon",
				"name_sta" => "Avon",
				"sort_order" => "125",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "92",
				"idcnt_sta" => "222",
				"code_sta" => "Bedfordshire",
				"name_sta" => "Bedfordshire",
				"sort_order" => "126",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "93",
				"idcnt_sta" => "222",
				"code_sta" => "Berkshire",
				"name_sta" => "Berkshire",
				"sort_order" => "127",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "94",
				"idcnt_sta" => "222",
				"code_sta" => "Buckinghamshire",
				"name_sta" => "Buckinghamshire",
				"sort_order" => "128",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "95",
				"idcnt_sta" => "222",
				"code_sta" => "Cambridgeshire",
				"name_sta" => "Cambridgeshire",
				"sort_order" => "129",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "96",
				"idcnt_sta" => "222",
				"code_sta" => "Cheshire",
				"name_sta" => "Cheshire",
				"sort_order" => "130",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "97",
				"idcnt_sta" => "222",
				"code_sta" => "Cleveland",
				"name_sta" => "Cleveland",
				"sort_order" => "131",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "98",
				"idcnt_sta" => "222",
				"code_sta" => "Cornwall",
				"name_sta" => "Cornwall",
				"sort_order" => "132",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "99",
				"idcnt_sta" => "222",
				"code_sta" => "Cumbria",
				"name_sta" => "Cumbria",
				"sort_order" => "133",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "100",
				"idcnt_sta" => "222",
				"code_sta" => "Derbyshire",
				"name_sta" => "Derbyshire",
				"sort_order" => "134",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "101",
				"idcnt_sta" => "222",
				"code_sta" => "Devon",
				"name_sta" => "Devon",
				"sort_order" => "135",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "102",
				"idcnt_sta" => "222",
				"code_sta" => "Dorset",
				"name_sta" => "Dorset",
				"sort_order" => "136",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "103",
				"idcnt_sta" => "222",
				"code_sta" => "Durham",
				"name_sta" => "Durham",
				"sort_order" => "137",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "104",
				"idcnt_sta" => "222",
				"code_sta" => "East Sussex",
				"name_sta" => "East Sussex",
				"sort_order" => "138",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "105",
				"idcnt_sta" => "222",
				"code_sta" => "Essex",
				"name_sta" => "Essex",
				"sort_order" => "139",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "106",
				"idcnt_sta" => "222",
				"code_sta" => "Gloucestershire",
				"name_sta" => "Gloucestershire",
				"sort_order" => "140",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "107",
				"idcnt_sta" => "222",
				"code_sta" => "Hampshire",
				"name_sta" => "Hampshire",
				"sort_order" => "141",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "108",
				"idcnt_sta" => "222",
				"code_sta" => "Herefordshire",
				"name_sta" => "Herefordshire",
				"sort_order" => "142",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "109",
				"idcnt_sta" => "222",
				"code_sta" => "Hertfordshire",
				"name_sta" => "Hertfordshire",
				"sort_order" => "143",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "110",
				"idcnt_sta" => "222",
				"code_sta" => "Isle of Wight",
				"name_sta" => "Isle of Wight",
				"sort_order" => "144",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "111",
				"idcnt_sta" => "222",
				"code_sta" => "Kent",
				"name_sta" => "Kent",
				"sort_order" => "145",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "112",
				"idcnt_sta" => "222",
				"code_sta" => "Lancashire",
				"name_sta" => "Lancashire",
				"sort_order" => "146",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "113",
				"idcnt_sta" => "222",
				"code_sta" => "Leicestershire",
				"name_sta" => "Leicestershire",
				"sort_order" => "147",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "114",
				"idcnt_sta" => "222",
				"code_sta" => "Lincolnshire",
				"name_sta" => "Lincolnshire",
				"sort_order" => "148",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "115",
				"idcnt_sta" => "222",
				"code_sta" => "London",
				"name_sta" => "London",
				"sort_order" => "149",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "116",
				"idcnt_sta" => "222",
				"code_sta" => "Merseyside",
				"name_sta" => "Merseyside",
				"sort_order" => "150",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "117",
				"idcnt_sta" => "222",
				"code_sta" => "Middlesex",
				"name_sta" => "Middlesex",
				"sort_order" => "151",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "118",
				"idcnt_sta" => "222",
				"code_sta" => "Norfolk",
				"name_sta" => "Norfolk",
				"sort_order" => "152",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "119",
				"idcnt_sta" => "222",
				"code_sta" => "Northamptonshire",
				"name_sta" => "Northamptonshire",
				"sort_order" => "153",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "120",
				"idcnt_sta" => "222",
				"code_sta" => "Northumberland",
				"name_sta" => "Northumberland",
				"sort_order" => "154",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "121",
				"idcnt_sta" => "222",
				"code_sta" => "North Humberside",
				"name_sta" => "North Humberside",
				"sort_order" => "155",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "122",
				"idcnt_sta" => "222",
				"code_sta" => "North Yorkshire",
				"name_sta" => "North Yorkshire",
				"sort_order" => "156",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "123",
				"idcnt_sta" => "222",
				"code_sta" => "Nottinghamshire",
				"name_sta" => "Nottinghamshire",
				"sort_order" => "157",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "124",
				"idcnt_sta" => "222",
				"code_sta" => "Oxfordshire",
				"name_sta" => "Oxfordshire",
				"sort_order" => "158",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "125",
				"idcnt_sta" => "222",
				"code_sta" => "Rutland",
				"name_sta" => "Rutland",
				"sort_order" => "159",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "126",
				"idcnt_sta" => "222",
				"code_sta" => "Shropshire",
				"name_sta" => "Shropshire",
				"sort_order" => "160",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "127",
				"idcnt_sta" => "222",
				"code_sta" => "Somerset",
				"name_sta" => "Somerset",
				"sort_order" => "161",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "128",
				"idcnt_sta" => "222",
				"code_sta" => "South Humberside",
				"name_sta" => "South Humberside",
				"sort_order" => "162",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "129",
				"idcnt_sta" => "222",
				"code_sta" => "South Yorkshire",
				"name_sta" => "South Yorkshire",
				"sort_order" => "163",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "130",
				"idcnt_sta" => "222",
				"code_sta" => "Staffordshire",
				"name_sta" => "Staffordshire",
				"sort_order" => "164",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "131",
				"idcnt_sta" => "222",
				"code_sta" => "Suffolk",
				"name_sta" => "Suffolk",
				"sort_order" => "165",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "132",
				"idcnt_sta" => "222",
				"code_sta" => "Surrey",
				"name_sta" => "Surrey",
				"sort_order" => "166",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "133",
				"idcnt_sta" => "222",
				"code_sta" => "Tyne and Wear",
				"name_sta" => "Tyne and Wear",
				"sort_order" => "167",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "134",
				"idcnt_sta" => "222",
				"code_sta" => "Warwickshire",
				"name_sta" => "Warwickshire",
				"sort_order" => "168",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "135",
				"idcnt_sta" => "222",
				"code_sta" => "West Midlands",
				"name_sta" => "West Midlands",
				"sort_order" => "169",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "136",
				"idcnt_sta" => "222",
				"code_sta" => "West Sussex",
				"name_sta" => "West Sussex",
				"sort_order" => "170",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "137",
				"idcnt_sta" => "222",
				"code_sta" => "West Yorkshire",
				"name_sta" => "West Yorkshire",
				"sort_order" => "171",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "138",
				"idcnt_sta" => "222",
				"code_sta" => "Wiltshire",
				"name_sta" => "Wiltshire",
				"sort_order" => "172",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "139",
				"idcnt_sta" => "222",
				"code_sta" => "Worcestershire",
				"name_sta" => "Worcestershire",
				"sort_order" => "173",
				"group_sta" => "England"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "140",
				"idcnt_sta" => "222",
				"code_sta" => "Clwyd",
				"name_sta" => "Clwyd",
				"sort_order" => "174",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "141",
				"idcnt_sta" => "222",
				"code_sta" => "Dyfed",
				"name_sta" => "Dyfed",
				"sort_order" => "175",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "142",
				"idcnt_sta" => "222",
				"code_sta" => "Gwent",
				"name_sta" => "Gwent",
				"sort_order" => "176",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "143",
				"idcnt_sta" => "222",
				"code_sta" => "Gwynedd",
				"name_sta" => "Gwynedd",
				"sort_order" => "177",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "144",
				"idcnt_sta" => "222",
				"code_sta" => "Mid Glamorgan",
				"name_sta" => "Mid Glamorgan",
				"sort_order" => "178",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "145",
				"idcnt_sta" => "222",
				"code_sta" => "Powys",
				"name_sta" => "Powys",
				"sort_order" => "179",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "146",
				"idcnt_sta" => "222",
				"code_sta" => "South Glamorgan",
				"name_sta" => "South Glamorgan",
				"sort_order" => "180",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "147",
				"idcnt_sta" => "222",
				"code_sta" => "West Glamorgan",
				"name_sta" => "West Glamorgan",
				"sort_order" => "181",
				"group_sta" => "Wales"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "148",
				"idcnt_sta" => "222",
				"code_sta" => "Aberdeenshire",
				"name_sta" => "Aberdeenshire",
				"sort_order" => "182",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "149",
				"idcnt_sta" => "222",
				"code_sta" => "Angus",
				"name_sta" => "Angus",
				"sort_order" => "183",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "150",
				"idcnt_sta" => "222",
				"code_sta" => "Argyll",
				"name_sta" => "Argyll",
				"sort_order" => "184",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "151",
				"idcnt_sta" => "222",
				"code_sta" => "Ayrshire",
				"name_sta" => "Ayrshire",
				"sort_order" => "185",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "152",
				"idcnt_sta" => "222",
				"code_sta" => "Banffshire",
				"name_sta" => "Banffshire",
				"sort_order" => "186",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "153",
				"idcnt_sta" => "222",
				"code_sta" => "Berwickshire",
				"name_sta" => "Berwickshire",
				"sort_order" => "187",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "154",
				"idcnt_sta" => "222",
				"code_sta" => "Bute",
				"name_sta" => "Bute",
				"sort_order" => "188",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "155",
				"idcnt_sta" => "222",
				"code_sta" => "Caithness",
				"name_sta" => "Caithness",
				"sort_order" => "189",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "156",
				"idcnt_sta" => "222",
				"code_sta" => "Clackmannanshire",
				"name_sta" => "Clackmannanshire",
				"sort_order" => "190",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "157",
				"idcnt_sta" => "222",
				"code_sta" => "Dumfriesshire",
				"name_sta" => "Dumfriesshire",
				"sort_order" => "191",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "158",
				"idcnt_sta" => "222",
				"code_sta" => "Dunbartonshire",
				"name_sta" => "Dunbartonshire",
				"sort_order" => "192",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "159",
				"idcnt_sta" => "222",
				"code_sta" => "East Lothian",
				"name_sta" => "East Lothian",
				"sort_order" => "193",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "160",
				"idcnt_sta" => "222",
				"code_sta" => "Fife",
				"name_sta" => "Fife",
				"sort_order" => "194",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "161",
				"idcnt_sta" => "222",
				"code_sta" => "Inverness-shire",
				"name_sta" => "Inverness-shire",
				"sort_order" => "195",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "162",
				"idcnt_sta" => "222",
				"code_sta" => "Kincardineshire",
				"name_sta" => "Kincardineshire",
				"sort_order" => "196",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "163",
				"idcnt_sta" => "222",
				"code_sta" => "Kinross-shire",
				"name_sta" => "Kinross-shire",
				"sort_order" => "197",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "164",
				"idcnt_sta" => "222",
				"code_sta" => "Kirkcudbrightshire",
				"name_sta" => "Kirkcudbrightshire",
				"sort_order" => "198",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "165",
				"idcnt_sta" => "222",
				"code_sta" => "Lanarkshire",
				"name_sta" => "Lanarkshire",
				"sort_order" => "199",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "166",
				"idcnt_sta" => "222",
				"code_sta" => "Midlothian",
				"name_sta" => "Midlothian",
				"sort_order" => "200",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "167",
				"idcnt_sta" => "222",
				"code_sta" => "Moray",
				"name_sta" => "Moray",
				"sort_order" => "201",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "168",
				"idcnt_sta" => "222",
				"code_sta" => "Nairnshire",
				"name_sta" => "Nairnshire",
				"sort_order" => "202",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "169",
				"idcnt_sta" => "222",
				"code_sta" => "Orkney",
				"name_sta" => "Orkney",
				"sort_order" => "203",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "170",
				"idcnt_sta" => "222",
				"code_sta" => "Peeblesshire",
				"name_sta" => "Peeblesshire",
				"sort_order" => "204",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "171",
				"idcnt_sta" => "222",
				"code_sta" => "Perthshire",
				"name_sta" => "Perthshire",
				"sort_order" => "205",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "172",
				"idcnt_sta" => "222",
				"code_sta" => "Renfrewshire",
				"name_sta" => "Renfrewshire",
				"sort_order" => "206",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "173",
				"idcnt_sta" => "222",
				"code_sta" => "Ross-shire",
				"name_sta" => "Ross-shire",
				"sort_order" => "207",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "174",
				"idcnt_sta" => "222",
				"code_sta" => "Roxburghshire",
				"name_sta" => "Roxburghshire",
				"sort_order" => "208",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "175",
				"idcnt_sta" => "222",
				"code_sta" => "Selkirkshire",
				"name_sta" => "Selkirkshire",
				"sort_order" => "209",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "176",
				"idcnt_sta" => "222",
				"code_sta" => "Shetland",
				"name_sta" => "Shetland",
				"sort_order" => "210",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "177",
				"idcnt_sta" => "222",
				"code_sta" => "Stirlingshire",
				"name_sta" => "Stirlingshire",
				"sort_order" => "211",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "178",
				"idcnt_sta" => "222",
				"code_sta" => "Sutherland",
				"name_sta" => "Sutherland",
				"sort_order" => "212",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "179",
				"idcnt_sta" => "222",
				"code_sta" => "West Lothian",
				"name_sta" => "West Lothian",
				"sort_order" => "213",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "180",
				"idcnt_sta" => "222",
				"code_sta" => "Wigtownshire",
				"name_sta" => "Wigtownshire",
				"sort_order" => "214",
				"group_sta" => "Scotland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "181",
				"idcnt_sta" => "222",
				"code_sta" => "Antrim",
				"name_sta" => "Antrim",
				"sort_order" => "215",
				"group_sta" => "Northern Ireland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "182",
				"idcnt_sta" => "222",
				"code_sta" => "Down",
				"name_sta" => "Down",
				"sort_order" => "217",
				"group_sta" => "Northern Ireland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "183",
				"idcnt_sta" => "222",
				"code_sta" => "Armagh",
				"name_sta" => "Armagh",
				"sort_order" => "216",
				"group_sta" => "Northern Ireland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "184",
				"idcnt_sta" => "222",
				"code_sta" => "Fermanagh",
				"name_sta" => "Fermanagh",
				"sort_order" => "218",
				"group_sta" => "Northern Ireland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "185",
				"idcnt_sta" => "222",
				"code_sta" => "Londonderry",
				"name_sta" => "Londonderry",
				"sort_order" => "219",
				"group_sta" => "Northern Ireland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "186",
				"idcnt_sta" => "222",
				"code_sta" => "Tyrone",
				"name_sta" => "Tyrone",
				"sort_order" => "220",
				"group_sta" => "Northern Ireland"
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "187",
				"idcnt_sta" => "30",
				"code_sta" => "AL",
				"name_sta" => "Alagoas",
				"sort_order" => "221",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "188",
				"idcnt_sta" => "30",
				"code_sta" => "AM",
				"name_sta" => "Amazonas",
				"sort_order" => "222",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "189",
				"idcnt_sta" => "30",
				"code_sta" => "BA",
				"name_sta" => "Bahia",
				"sort_order" => "223",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "190",
				"idcnt_sta" => "30",
				"code_sta" => "CE",
				"name_sta" => "Cearà",
				"sort_order" => "224",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "191",
				"idcnt_sta" => "30",
				"code_sta" => "DF",
				"name_sta" => "Distrito Federal",
				"sort_order" => "225",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "192",
				"idcnt_sta" => "30",
				"code_sta" => "ES",
				"name_sta" => "Espìrito Santo",
				"sort_order" => "226",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "193",
				"idcnt_sta" => "30",
				"code_sta" => "GO",
				"name_sta" => "Goias",
				"sort_order" => "227",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "194",
				"idcnt_sta" => "30",
				"code_sta" => "MA",
				"name_sta" => "Maranhao",
				"sort_order" => "228",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "195",
				"idcnt_sta" => "30",
				"code_sta" => "MT",
				"name_sta" => "Mato Grosso",
				"sort_order" => "229",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "196",
				"idcnt_sta" => "30",
				"code_sta" => "MS",
				"name_sta" => "Mato Grosso Do Sul",
				"sort_order" => "230",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "197",
				"idcnt_sta" => "30",
				"code_sta" => "MG",
				"name_sta" => "Minas Gerais",
				"sort_order" => "231",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "198",
				"idcnt_sta" => "30",
				"code_sta" => "PA",
				"name_sta" => "Parà",
				"sort_order" => "232",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "199",
				"idcnt_sta" => "30",
				"code_sta" => "PB",
				"name_sta" => "Paraìba",
				"sort_order" => "233",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "200",
				"idcnt_sta" => "30",
				"code_sta" => "PR",
				"name_sta" => "Paranà",
				"sort_order" => "234",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "201",
				"idcnt_sta" => "30",
				"code_sta" => "PE",
				"name_sta" => "Pernambuco",
				"sort_order" => "235",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "202",
				"idcnt_sta" => "30",
				"code_sta" => "PI",
				"name_sta" => "Piauì",
				"sort_order" => "236",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "203",
				"idcnt_sta" => "30",
				"code_sta" => "RJ",
				"name_sta" => "Rio de Janeiro",
				"sort_order" => "237",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "204",
				"idcnt_sta" => "30",
				"code_sta" => "RN",
				"name_sta" => "Rio Grande do Norte",
				"sort_order" => "238",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "205",
				"idcnt_sta" => "30",
				"code_sta" => "RS",
				"name_sta" => "Dio Grande do Sul",
				"sort_order" => "239",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "206",
				"idcnt_sta" => "30",
				"code_sta" => "RO",
				"name_sta" => "Rondônia",
				"sort_order" => "240",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "207",
				"idcnt_sta" => "30",
				"code_sta" => "SC",
				"name_sta" => "Santa Catarina",
				"sort_order" => "241",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "208",
				"idcnt_sta" => "30",
				"code_sta" => "SP",
				"name_sta" => "Sao Paulo",
				"sort_order" => "242",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "209",
				"idcnt_sta" => "30",
				"code_sta" => "SE",
				"name_sta" => "Sergipe",
				"sort_order" => "243",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "210",
				"idcnt_sta" => "44",
				"code_sta" => "ANH",
				"name_sta" => "Anhui",
				"sort_order" => "244",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "211",
				"idcnt_sta" => "44",
				"code_sta" => "BEI",
				"name_sta" => "Beijing",
				"sort_order" => "245",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "212",
				"idcnt_sta" => "44",
				"code_sta" => "CHO",
				"name_sta" => "Chongqing",
				"sort_order" => "246",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "213",
				"idcnt_sta" => "44",
				"code_sta" => "FUJ",
				"name_sta" => "Fujian",
				"sort_order" => "247",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "214",
				"idcnt_sta" => "44",
				"code_sta" => "GAN",
				"name_sta" => "Gansu",
				"sort_order" => "248",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "215",
				"idcnt_sta" => "44",
				"code_sta" => "GDG",
				"name_sta" => "Guangdong",
				"sort_order" => "249",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "216",
				"idcnt_sta" => "44",
				"code_sta" => "GXI",
				"name_sta" => "Guangxi",
				"sort_order" => "250",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "217",
				"idcnt_sta" => "44",
				"code_sta" => "GUI",
				"name_sta" => "Guizhou",
				"sort_order" => "251",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "218",
				"idcnt_sta" => "44",
				"code_sta" => "HAI",
				"name_sta" => "Hainan",
				"sort_order" => "252",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "219",
				"idcnt_sta" => "44",
				"code_sta" => "HEB",
				"name_sta" => "Hebei",
				"sort_order" => "253",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "220",
				"idcnt_sta" => "44",
				"code_sta" => "HEI",
				"name_sta" => "Heilongjiang",
				"sort_order" => "254",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "221",
				"idcnt_sta" => "44",
				"code_sta" => "HEN",
				"name_sta" => "Henan",
				"sort_order" => "255",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "222",
				"idcnt_sta" => "44",
				"code_sta" => "HUB",
				"name_sta" => "Hubei",
				"sort_order" => "256",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "223",
				"idcnt_sta" => "44",
				"code_sta" => "HUN",
				"name_sta" => "Hunan",
				"sort_order" => "257",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "224",
				"idcnt_sta" => "44",
				"code_sta" => "JSU",
				"name_sta" => "Jiangsu",
				"sort_order" => "258",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "225",
				"idcnt_sta" => "44",
				"code_sta" => "JXI",
				"name_sta" => "Jiangxi",
				"sort_order" => "259",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "226",
				"idcnt_sta" => "44",
				"code_sta" => "JIL",
				"name_sta" => "Jilin",
				"sort_order" => "260",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "227",
				"idcnt_sta" => "44",
				"code_sta" => "LIA",
				"name_sta" => "Liaoning",
				"sort_order" => "261",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "228",
				"idcnt_sta" => "44",
				"code_sta" => "MON",
				"name_sta" => "Nei Mongol",
				"sort_order" => "262",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "229",
				"idcnt_sta" => "44",
				"code_sta" => "NIN",
				"name_sta" => "Ningxia",
				"sort_order" => "263",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "230",
				"idcnt_sta" => "44",
				"code_sta" => "QIN",
				"name_sta" => "Qinghai",
				"sort_order" => "264",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "231",
				"idcnt_sta" => "44",
				"code_sta" => "SHA",
				"name_sta" => "Shaanxi",
				"sort_order" => "265",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "232",
				"idcnt_sta" => "44",
				"code_sta" => "SHD",
				"name_sta" => "Shandong",
				"sort_order" => "266",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "233",
				"idcnt_sta" => "44",
				"code_sta" => "SHH",
				"name_sta" => "Shanghai",
				"sort_order" => "267",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "234",
				"idcnt_sta" => "44",
				"code_sta" => "SHX",
				"name_sta" => "Shanxi",
				"sort_order" => "268",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "235",
				"idcnt_sta" => "44",
				"code_sta" => "SIC",
				"name_sta" => "Sichuan",
				"sort_order" => "269",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "236",
				"idcnt_sta" => "44",
				"code_sta" => "TIA",
				"name_sta" => "TIanjin",
				"sort_order" => "270",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "237",
				"idcnt_sta" => "44",
				"code_sta" => "XIN",
				"name_sta" => "Xinjiang",
				"sort_order" => "271",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "238",
				"idcnt_sta" => "44",
				"code_sta" => "XIZ",
				"name_sta" => "Xizang",
				"sort_order" => "272",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "239",
				"idcnt_sta" => "44",
				"code_sta" => "YUN",
				"name_sta" => "Yunnan",
				"sort_order" => "273",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "240",
				"idcnt_sta" => "44",
				"code_sta" => "ZHE",
				"name_sta" => "Zhejiang",
				"sort_order" => "274",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "241",
				"idcnt_sta" => "99",
				"code_sta" => "AND",
				"name_sta" => "Andhra Pradesh",
				"sort_order" => "275",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "242",
				"idcnt_sta" => "99",
				"code_sta" => "ASS",
				"name_sta" => "Assam",
				"sort_order" => "276",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "243",
				"idcnt_sta" => "99",
				"code_sta" => "BIH",
				"name_sta" => "Bihar",
				"sort_order" => "277",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "244",
				"idcnt_sta" => "99",
				"code_sta" => "CHH",
				"name_sta" => "Chhattisgarh",
				"sort_order" => "278",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "245",
				"idcnt_sta" => "99",
				"code_sta" => "DEL",
				"name_sta" => "Delhi",
				"sort_order" => "279",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "246",
				"idcnt_sta" => "99",
				"code_sta" => "GOA",
				"name_sta" => "Goa",
				"sort_order" => "280",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "247",
				"idcnt_sta" => "99",
				"code_sta" => "GUJ",
				"name_sta" => "Gujarat",
				"sort_order" => "281",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "248",
				"idcnt_sta" => "99",
				"code_sta" => "HAR",
				"name_sta" => "Haryana",
				"sort_order" => "282",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "249",
				"idcnt_sta" => "99",
				"code_sta" => "HIM",
				"name_sta" => "Himachal Pradesh",
				"sort_order" => "283",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "250",
				"idcnt_sta" => "99",
				"code_sta" => "JAM",
				"name_sta" => "Jammu & Kashmir",
				"sort_order" => "284",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "251",
				"idcnt_sta" => "99",
				"code_sta" => "JHA",
				"name_sta" => "Jharkhand",
				"sort_order" => "285",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "252",
				"idcnt_sta" => "99",
				"code_sta" => "KAR",
				"name_sta" => "Karnataka",
				"sort_order" => "286",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "253",
				"idcnt_sta" => "99",
				"code_sta" => "KER",
				"name_sta" => "Kerala",
				"sort_order" => "287",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "254",
				"idcnt_sta" => "99",
				"code_sta" => "MAD",
				"name_sta" => "Madhya Pradesh",
				"sort_order" => "288",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "255",
				"idcnt_sta" => "99",
				"code_sta" => "MAH",
				"name_sta" => "Maharashtra",
				"sort_order" => "289",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "256",
				"idcnt_sta" => "99",
				"code_sta" => "MEG",
				"name_sta" => "Meghalaya",
				"sort_order" => "290",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "257",
				"idcnt_sta" => "99",
				"code_sta" => "ORI",
				"name_sta" => "Orissa",
				"sort_order" => "291",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "258",
				"idcnt_sta" => "99",
				"code_sta" => "PON",
				"name_sta" => "Pondicherry",
				"sort_order" => "292",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "259",
				"idcnt_sta" => "99",
				"code_sta" => "PUN",
				"name_sta" => "Punjab",
				"sort_order" => "293",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "260",
				"idcnt_sta" => "99",
				"code_sta" => "RAJ",
				"name_sta" => "Rajasthan",
				"sort_order" => "294",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "261",
				"idcnt_sta" => "99",
				"code_sta" => "TAM",
				"name_sta" => "Tamil Nadu",
				"sort_order" => "295",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "262",
				"idcnt_sta" => "99",
				"code_sta" => "UTT",
				"name_sta" => "Uttar Pradesh",
				"sort_order" => "296",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "263",
				"idcnt_sta" => "99",
				"code_sta" => "UTR",
				"name_sta" => "Uttaranchal",
				"sort_order" => "297",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "264",
				"idcnt_sta" => "99",
				"code_sta" => "WES",
				"name_sta" => "West Bengal",
				"sort_order" => "298",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "265",
				"idcnt_sta" => "107",
				"code_sta" => "AIC",
				"name_sta" => "Aichi",
				"sort_order" => "299",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "266",
				"idcnt_sta" => "107",
				"code_sta" => "AKT",
				"name_sta" => "Akita",
				"sort_order" => "300",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "267",
				"idcnt_sta" => "107",
				"code_sta" => "AMR",
				"name_sta" => "Aomori",
				"sort_order" => "301",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "268",
				"idcnt_sta" => "107",
				"code_sta" => "CHB",
				"name_sta" => "Chiba",
				"sort_order" => "302",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "269",
				"idcnt_sta" => "107",
				"code_sta" => "EHM",
				"name_sta" => "Ehime",
				"sort_order" => "303",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "270",
				"idcnt_sta" => "107",
				"code_sta" => "FKI",
				"name_sta" => "Fukui",
				"sort_order" => "304",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "271",
				"idcnt_sta" => "107",
				"code_sta" => "FKO",
				"name_sta" => "Fukuoka",
				"sort_order" => "305",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "272",
				"idcnt_sta" => "107",
				"code_sta" => "FSM",
				"name_sta" => "Fukushima",
				"sort_order" => "306",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "273",
				"idcnt_sta" => "107",
				"code_sta" => "GFU",
				"name_sta" => "Gifu",
				"sort_order" => "307",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "274",
				"idcnt_sta" => "107",
				"code_sta" => "GUM",
				"name_sta" => "Gunma",
				"sort_order" => "308",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "275",
				"idcnt_sta" => "107",
				"code_sta" => "HRS",
				"name_sta" => "Hiroshima",
				"sort_order" => "309",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "276",
				"idcnt_sta" => "107",
				"code_sta" => "HKD",
				"name_sta" => "Hokkaido",
				"sort_order" => "310",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "277",
				"idcnt_sta" => "107",
				"code_sta" => "HYG",
				"name_sta" => "Hyogo",
				"sort_order" => "311",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "278",
				"idcnt_sta" => "107",
				"code_sta" => "IBR",
				"name_sta" => "Ibaraki",
				"sort_order" => "312",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "279",
				"idcnt_sta" => "107",
				"code_sta" => "IKW",
				"name_sta" => "Ishikawa",
				"sort_order" => "313",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "280",
				"idcnt_sta" => "107",
				"code_sta" => "IWT",
				"name_sta" => "Iwate",
				"sort_order" => "314",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "281",
				"idcnt_sta" => "107",
				"code_sta" => "KGW",
				"name_sta" => "Kagawa",
				"sort_order" => "315",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "282",
				"idcnt_sta" => "107",
				"code_sta" => "KGS",
				"name_sta" => "Kagoshima",
				"sort_order" => "316",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "283",
				"idcnt_sta" => "107",
				"code_sta" => "KNG",
				"name_sta" => "Kanagawa",
				"sort_order" => "317",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "284",
				"idcnt_sta" => "107",
				"code_sta" => "KCH",
				"name_sta" => "Kochi",
				"sort_order" => "318",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "285",
				"idcnt_sta" => "107",
				"code_sta" => "KMM",
				"name_sta" => "Kumamoto",
				"sort_order" => "319",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "286",
				"idcnt_sta" => "107",
				"code_sta" => "KYT",
				"name_sta" => "Kyoto",
				"sort_order" => "320",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "287",
				"idcnt_sta" => "107",
				"code_sta" => "MIE",
				"name_sta" => "Mie",
				"sort_order" => "321",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "288",
				"idcnt_sta" => "107",
				"code_sta" => "MYG",
				"name_sta" => "Miyagi",
				"sort_order" => "322",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "289",
				"idcnt_sta" => "107",
				"code_sta" => "MYZ",
				"name_sta" => "Miyazaki",
				"sort_order" => "323",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "290",
				"idcnt_sta" => "107",
				"code_sta" => "NGN",
				"name_sta" => "Nagano",
				"sort_order" => "324",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "291",
				"idcnt_sta" => "107",
				"code_sta" => "NGS",
				"name_sta" => "Nagasaki",
				"sort_order" => "325",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "292",
				"idcnt_sta" => "107",
				"code_sta" => "NRA",
				"name_sta" => "Nara",
				"sort_order" => "326",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "293",
				"idcnt_sta" => "107",
				"code_sta" => "NGT",
				"name_sta" => "Niigata",
				"sort_order" => "327",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "294",
				"idcnt_sta" => "107",
				"code_sta" => "OTA",
				"name_sta" => "Oita",
				"sort_order" => "328",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "295",
				"idcnt_sta" => "107",
				"code_sta" => "OKY",
				"name_sta" => "Okayama",
				"sort_order" => "329",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "296",
				"idcnt_sta" => "107",
				"code_sta" => "OKN",
				"name_sta" => "Okinawa",
				"sort_order" => "330",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "297",
				"idcnt_sta" => "107",
				"code_sta" => "OSK",
				"name_sta" => "Osaka",
				"sort_order" => "331",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "298",
				"idcnt_sta" => "107",
				"code_sta" => "SAG",
				"name_sta" => "Saga",
				"sort_order" => "332",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "299",
				"idcnt_sta" => "107",
				"code_sta" => "STM",
				"name_sta" => "Saitama",
				"sort_order" => "333",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "300",
				"idcnt_sta" => "107",
				"code_sta" => "SHG",
				"name_sta" => "Shiga",
				"sort_order" => "334",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "301",
				"idcnt_sta" => "107",
				"code_sta" => "SMN",
				"name_sta" => "Shimane",
				"sort_order" => "335",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "302",
				"idcnt_sta" => "107",
				"code_sta" => "SZK",
				"name_sta" => "Shizuoka",
				"sort_order" => "336",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "303",
				"idcnt_sta" => "107",
				"code_sta" => "TOC",
				"name_sta" => "Tochigi",
				"sort_order" => "337",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "304",
				"idcnt_sta" => "107",
				"code_sta" => "TKS",
				"name_sta" => "Tokushima",
				"sort_order" => "338",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "305",
				"idcnt_sta" => "107",
				"code_sta" => "TKY",
				"name_sta" => "Tokyo",
				"sort_order" => "335",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "306",
				"idcnt_sta" => "107",
				"code_sta" => "TTR",
				"name_sta" => "Tottori",
				"sort_order" => "336",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "307",
				"idcnt_sta" => "107",
				"code_sta" => "TYM",
				"name_sta" => "Toyama",
				"sort_order" => "337",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "308",
				"idcnt_sta" => "107",
				"code_sta" => "WKY",
				"name_sta" => "Wakayama",
				"sort_order" => "338",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "309",
				"idcnt_sta" => "107",
				"code_sta" => "YGT",
				"name_sta" => "Yamagata",
				"sort_order" => "339",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "310",
				"idcnt_sta" => "107",
				"code_sta" => "YGC",
				"name_sta" => "Yamaguchi",
				"sort_order" => "340",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "311",
				"idcnt_sta" => "107",
				"code_sta" => "YNS",
				"name_sta" => "Yamanashi",
				"sort_order" => "341",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "312",
				"idcnt_sta" => "10",
				"code_sta" => "C",
				"name_sta" => "Buenos Aires (Autonomous City)",
				"sort_order" => "342",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "313",
				"idcnt_sta" => "10",
				"code_sta" => "B",
				"name_sta" => "Buenos Aires Province",
				"sort_order" => "343",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "314",
				"idcnt_sta" => "10",
				"code_sta" => "K",
				"name_sta" => "Catamarca",
				"sort_order" => "344",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "315",
				"idcnt_sta" => "10",
				"code_sta" => "H",
				"name_sta" => "Chaco",
				"sort_order" => "345",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "316",
				"idcnt_sta" => "10",
				"code_sta" => "U",
				"name_sta" => "Chubut",
				"sort_order" => "346",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "317",
				"idcnt_sta" => "10",
				"code_sta" => "X",
				"name_sta" => "Córdoba",
				"sort_order" => "347",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "318",
				"idcnt_sta" => "10",
				"code_sta" => "W",
				"name_sta" => "Corrientes",
				"sort_order" => "348",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "319",
				"idcnt_sta" => "10",
				"code_sta" => "E",
				"name_sta" => "Entre Ríos",
				"sort_order" => "349",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "320",
				"idcnt_sta" => "10",
				"code_sta" => "P",
				"name_sta" => "Formosa",
				"sort_order" => "350",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "321",
				"idcnt_sta" => "10",
				"code_sta" => "Y",
				"name_sta" => "Jujuy",
				"sort_order" => "351",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "322",
				"idcnt_sta" => "10",
				"code_sta" => "L",
				"name_sta" => "La Pampa",
				"sort_order" => "352",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "323",
				"idcnt_sta" => "10",
				"code_sta" => "",
				"name_sta" => "",
				"sort_order" => "353",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "324",
				"idcnt_sta" => "10",
				"code_sta" => "F",
				"name_sta" => "La Rioja",
				"sort_order" => "354",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "325",
				"idcnt_sta" => "10",
				"code_sta" => "M",
				"name_sta" => "Mendoza",
				"sort_order" => "355",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "326",
				"idcnt_sta" => "10",
				"code_sta" => "N",
				"name_sta" => "Misiones",
				"sort_order" => "356",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "327",
				"idcnt_sta" => "10",
				"code_sta" => "Q",
				"name_sta" => "Neuquén",
				"sort_order" => "357",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "328",
				"idcnt_sta" => "10",
				"code_sta" => "R",
				"name_sta" => "Río Negro",
				"sort_order" => "358",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "329",
				"idcnt_sta" => "10",
				"code_sta" => "A",
				"name_sta" => "Salta",
				"sort_order" => "359",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "330",
				"idcnt_sta" => "10",
				"code_sta" => "J",
				"name_sta" => "San Juan",
				"sort_order" => "360",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "331",
				"idcnt_sta" => "10",
				"code_sta" => "D",
				"name_sta" => "San Luis",
				"sort_order" => "361",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "332",
				"idcnt_sta" => "10",
				"code_sta" => "Z",
				"name_sta" => "Santa Cruz",
				"sort_order" => "362",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "333",
				"idcnt_sta" => "10",
				"code_sta" => "S",
				"name_sta" => "Santa Fe",
				"sort_order" => "363",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "334",
				"idcnt_sta" => "10",
				"code_sta" => "G",
				"name_sta" => "Santiago del Estero",
				"sort_order" => "364",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "335",
				"idcnt_sta" => "10",
				"code_sta" => "V",
				"name_sta" => "Tierra del Fuego",
				"sort_order" => "365",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_state",
			array(
				"id_sta" => "336",
				"idcnt_sta" => "10",
				"code_sta" => "T",
				"name_sta" => "Tucumán",
				"sort_order" => "366",
				"group_sta" => ""
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "1",
				"zone_name" => "North America"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "2",
				"zone_name" => "South America"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "3",
				"zone_name" => "Europe"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "4",
				"zone_name" => "Africa"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "5",
				"zone_name" => "Asia"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "6",
				"zone_name" => "Australia"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "7",
				"zone_name" => "Oceania"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "8",
				"zone_name" => "Lower 48 States"
			)
		);

		$wpdb->insert( 
			"ec_zone",
			array(
				"zone_id" => "9",
				"zone_name" => "Alaska and Hawaii"
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "1",
				"zone_id" => "1",
				"iso2_cnt" => "AI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "2",
				"zone_id" => "1",
				"iso2_cnt" => "AQ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "3",
				"zone_id" => "1",
				"iso2_cnt" => "AW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "4",
				"zone_id" => "1",
				"iso2_cnt" => "BS",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "5",
				"zone_id" => "1",
				"iso2_cnt" => "BB",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "6",
				"zone_id" => "1",
				"iso2_cnt" => "BM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "7",
				"zone_id" => "1",
				"iso2_cnt" => "BZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "8",
				"zone_id" => "1",
				"iso2_cnt" => "CA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "9",
				"zone_id" => "1",
				"iso2_cnt" => "KY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "10",
				"zone_id" => "1",
				"iso2_cnt" => "CR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "11",
				"zone_id" => "1",
				"iso2_cnt" => "CU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "12",
				"zone_id" => "1",
				"iso2_cnt" => "DM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "13",
				"zone_id" => "1",
				"iso2_cnt" => "DO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "14",
				"zone_id" => "1",
				"iso2_cnt" => "SV",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "15",
				"zone_id" => "1",
				"iso2_cnt" => "GL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "16",
				"zone_id" => "1",
				"iso2_cnt" => "GD",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "17",
				"zone_id" => "1",
				"iso2_cnt" => "GP",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "18",
				"zone_id" => "1",
				"iso2_cnt" => "GT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "19",
				"zone_id" => "1",
				"iso2_cnt" => "HT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "20",
				"zone_id" => "1",
				"iso2_cnt" => "HN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "21",
				"zone_id" => "1",
				"iso2_cnt" => "JM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "22",
				"zone_id" => "1",
				"iso2_cnt" => "MQ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "23",
				"zone_id" => "1",
				"iso2_cnt" => "MX",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "24",
				"zone_id" => "1",
				"iso2_cnt" => "MS",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "25",
				"zone_id" => "1",
				"iso2_cnt" => "NI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "26",
				"zone_id" => "1",
				"iso2_cnt" => "PA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "27",
				"zone_id" => "1",
				"iso2_cnt" => "PR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "28",
				"zone_id" => "1",
				"iso2_cnt" => "KN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "29",
				"zone_id" => "1",
				"iso2_cnt" => "LC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "30",
				"zone_id" => "1",
				"iso2_cnt" => "TT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "31",
				"zone_id" => "1",
				"iso2_cnt" => "TC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "32",
				"zone_id" => "1",
				"iso2_cnt" => "US",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "33",
				"zone_id" => "1",
				"iso2_cnt" => "VI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "34",
				"zone_id" => "2",
				"iso2_cnt" => "AR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "35",
				"zone_id" => "2",
				"iso2_cnt" => "BO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "36",
				"zone_id" => "2",
				"iso2_cnt" => "BR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "37",
				"zone_id" => "2",
				"iso2_cnt" => "CL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "38",
				"zone_id" => "2",
				"iso2_cnt" => "CO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "39",
				"zone_id" => "2",
				"iso2_cnt" => "EC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "40",
				"zone_id" => "2",
				"iso2_cnt" => "GF",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "41",
				"zone_id" => "2",
				"iso2_cnt" => "GY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "42",
				"zone_id" => "2",
				"iso2_cnt" => "PY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "43",
				"zone_id" => "2",
				"iso2_cnt" => "PE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "44",
				"zone_id" => "2",
				"iso2_cnt" => "SR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "45",
				"zone_id" => "2",
				"iso2_cnt" => "UY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "46",
				"zone_id" => "2",
				"iso2_cnt" => "VE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "47",
				"zone_id" => "6",
				"iso2_cnt" => "AU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "48",
				"zone_id" => "7",
				"iso2_cnt" => "AS",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "49",
				"zone_id" => "7",
				"iso2_cnt" => "AU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "50",
				"zone_id" => "7",
				"iso2_cnt" => "CK",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "51",
				"zone_id" => "7",
				"iso2_cnt" => "FJ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "52",
				"zone_id" => "7",
				"iso2_cnt" => "PF",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "53",
				"zone_id" => "7",
				"iso2_cnt" => "GU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "54",
				"zone_id" => "7",
				"iso2_cnt" => "KI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "55",
				"zone_id" => "7",
				"iso2_cnt" => "MH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "56",
				"zone_id" => "7",
				"iso2_cnt" => "NR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "57",
				"zone_id" => "7",
				"iso2_cnt" => "NC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "58",
				"zone_id" => "7",
				"iso2_cnt" => "NZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "59",
				"zone_id" => "7",
				"iso2_cnt" => "NU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "60",
				"zone_id" => "7",
				"iso2_cnt" => "NF",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "61",
				"zone_id" => "7",
				"iso2_cnt" => "PW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "62",
				"zone_id" => "7",
				"iso2_cnt" => "PG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "63",
				"zone_id" => "7",
				"iso2_cnt" => "PN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "64",
				"zone_id" => "7",
				"iso2_cnt" => "WS",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "65",
				"zone_id" => "7",
				"iso2_cnt" => "SB",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "66",
				"zone_id" => "7",
				"iso2_cnt" => "TK",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "67",
				"zone_id" => "7",
				"iso2_cnt" => "TO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "68",
				"zone_id" => "7",
				"iso2_cnt" => "TV",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "69",
				"zone_id" => "7",
				"iso2_cnt" => "VU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "70",
				"zone_id" => "7",
				"iso2_cnt" => "WF",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "71",
				"zone_id" => "3",
				"iso2_cnt" => "AL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "72",
				"zone_id" => "3",
				"iso2_cnt" => "AD",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "73",
				"zone_id" => "3",
				"iso2_cnt" => "AT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "74",
				"zone_id" => "3",
				"iso2_cnt" => "BY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "75",
				"zone_id" => "3",
				"iso2_cnt" => "BE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "76",
				"zone_id" => "3",
				"iso2_cnt" => "BG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "77",
				"zone_id" => "3",
				"iso2_cnt" => "HR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "78",
				"zone_id" => "3",
				"iso2_cnt" => "CZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "79",
				"zone_id" => "3",
				"iso2_cnt" => "DK",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "80",
				"zone_id" => "3",
				"iso2_cnt" => "EE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "81",
				"zone_id" => "3",
				"iso2_cnt" => "FO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "82",
				"zone_id" => "3",
				"iso2_cnt" => "FI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "83",
				"zone_id" => "3",
				"iso2_cnt" => "FR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "84",
				"zone_id" => "3",
				"iso2_cnt" => "DE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "257",
				"zone_id" => "3",
				"iso2_cnt" => "DC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "85",
				"zone_id" => "3",
				"iso2_cnt" => "GI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "86",
				"zone_id" => "3",
				"iso2_cnt" => "GR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "87",
				"zone_id" => "3",
				"iso2_cnt" => "HU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "88",
				"zone_id" => "3",
				"iso2_cnt" => "IS",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "89",
				"zone_id" => "3",
				"iso2_cnt" => "IE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "90",
				"zone_id" => "3",
				"iso2_cnt" => "IT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "91",
				"zone_id" => "3",
				"iso2_cnt" => "LV",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "92",
				"zone_id" => "3",
				"iso2_cnt" => "LI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "93",
				"zone_id" => "3",
				"iso2_cnt" => "LT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "94",
				"zone_id" => "3",
				"iso2_cnt" => "LU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "95",
				"zone_id" => "3",
				"iso2_cnt" => "MT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "96",
				"zone_id" => "3",
				"iso2_cnt" => "MC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "97",
				"zone_id" => "3",
				"iso2_cnt" => "NL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "98",
				"zone_id" => "3",
				"iso2_cnt" => "NO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "99",
				"zone_id" => "3",
				"iso2_cnt" => "PL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "100",
				"zone_id" => "3",
				"iso2_cnt" => "PT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "101",
				"zone_id" => "3",
				"iso2_cnt" => "RO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "102",
				"zone_id" => "3",
				"iso2_cnt" => "RU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "103",
				"zone_id" => "3",
				"iso2_cnt" => "SM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "104",
				"zone_id" => "3",
				"iso2_cnt" => "SI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "105",
				"zone_id" => "3",
				"iso2_cnt" => "ES",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "106",
				"zone_id" => "3",
				"iso2_cnt" => "SE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "107",
				"zone_id" => "3",
				"iso2_cnt" => "CH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "108",
				"zone_id" => "3",
				"iso2_cnt" => "UA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "109",
				"zone_id" => "3",
				"iso2_cnt" => "GB",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "110",
				"zone_id" => "4",
				"iso2_cnt" => "DZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "111",
				"zone_id" => "4",
				"iso2_cnt" => "AO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "112",
				"zone_id" => "4",
				"iso2_cnt" => "BJ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "113",
				"zone_id" => "4",
				"iso2_cnt" => "BW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "114",
				"zone_id" => "4",
				"iso2_cnt" => "BF",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "115",
				"zone_id" => "4",
				"iso2_cnt" => "BI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "116",
				"zone_id" => "4",
				"iso2_cnt" => "CM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "117",
				"zone_id" => "4",
				"iso2_cnt" => "CV",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "118",
				"zone_id" => "4",
				"iso2_cnt" => "TD",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "119",
				"zone_id" => "4",
				"iso2_cnt" => "KM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "120",
				"zone_id" => "4",
				"iso2_cnt" => "CG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "121",
				"zone_id" => "4",
				"iso2_cnt" => "CI",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "122",
				"zone_id" => "4",
				"iso2_cnt" => "DJ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "123",
				"zone_id" => "4",
				"iso2_cnt" => "EG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "124",
				"zone_id" => "4",
				"iso2_cnt" => "GQ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "125",
				"zone_id" => "4",
				"iso2_cnt" => "ER",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "126",
				"zone_id" => "4",
				"iso2_cnt" => "ET",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "127",
				"zone_id" => "4",
				"iso2_cnt" => "GA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "128",
				"zone_id" => "4",
				"iso2_cnt" => "GM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "129",
				"zone_id" => "4",
				"iso2_cnt" => "GH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "130",
				"zone_id" => "4",
				"iso2_cnt" => "GN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "131",
				"zone_id" => "4",
				"iso2_cnt" => "GW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "132",
				"zone_id" => "4",
				"iso2_cnt" => "KE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "133",
				"zone_id" => "4",
				"iso2_cnt" => "LS",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "134",
				"zone_id" => "4",
				"iso2_cnt" => "LR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "135",
				"zone_id" => "4",
				"iso2_cnt" => "MG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "136",
				"zone_id" => "4",
				"iso2_cnt" => "MW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "137",
				"zone_id" => "4",
				"iso2_cnt" => "ML",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "138",
				"zone_id" => "4",
				"iso2_cnt" => "MR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "139",
				"zone_id" => "4",
				"iso2_cnt" => "MU",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "140",
				"zone_id" => "4",
				"iso2_cnt" => "YT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "141",
				"zone_id" => "4",
				"iso2_cnt" => "MA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "142",
				"zone_id" => "4",
				"iso2_cnt" => "MZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "143",
				"zone_id" => "4",
				"iso2_cnt" => "NA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "144",
				"zone_id" => "4",
				"iso2_cnt" => "NE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "145",
				"zone_id" => "4",
				"iso2_cnt" => "NG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "146",
				"zone_id" => "4",
				"iso2_cnt" => "RE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "147",
				"zone_id" => "4",
				"iso2_cnt" => "RW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "148",
				"zone_id" => "4",
				"iso2_cnt" => "ST",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "149",
				"zone_id" => "4",
				"iso2_cnt" => "SN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "150",
				"zone_id" => "4",
				"iso2_cnt" => "SC",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "151",
				"zone_id" => "4",
				"iso2_cnt" => "SL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "152",
				"zone_id" => "4",
				"iso2_cnt" => "SO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "153",
				"zone_id" => "4",
				"iso2_cnt" => "ZA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "154",
				"zone_id" => "4",
				"iso2_cnt" => "SD",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "155",
				"zone_id" => "4",
				"iso2_cnt" => "SZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "156",
				"zone_id" => "4",
				"iso2_cnt" => "TG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "157",
				"zone_id" => "4",
				"iso2_cnt" => "TN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "158",
				"zone_id" => "4",
				"iso2_cnt" => "UG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "159",
				"zone_id" => "4",
				"iso2_cnt" => "ZM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "160",
				"zone_id" => "4",
				"iso2_cnt" => "ZW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "161",
				"zone_id" => "5",
				"iso2_cnt" => "AF",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "162",
				"zone_id" => "5",
				"iso2_cnt" => "AM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "163",
				"zone_id" => "5",
				"iso2_cnt" => "AZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "164",
				"zone_id" => "5",
				"iso2_cnt" => "BH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "165",
				"zone_id" => "5",
				"iso2_cnt" => "BD",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "166",
				"zone_id" => "5",
				"iso2_cnt" => "BT",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "167",
				"zone_id" => "5",
				"iso2_cnt" => "BN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "168",
				"zone_id" => "5",
				"iso2_cnt" => "KH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "169",
				"zone_id" => "5",
				"iso2_cnt" => "CN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "170",
				"zone_id" => "5",
				"iso2_cnt" => "CX",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "171",
				"zone_id" => "5",
				"iso2_cnt" => "CY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "172",
				"zone_id" => "5",
				"iso2_cnt" => "TP",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "173",
				"zone_id" => "5",
				"iso2_cnt" => "GE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "174",
				"zone_id" => "5",
				"iso2_cnt" => "HK",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "175",
				"zone_id" => "5",
				"iso2_cnt" => "IN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "176",
				"zone_id" => "5",
				"iso2_cnt" => "ID",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "177",
				"zone_id" => "5",
				"iso2_cnt" => "IQ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "178",
				"zone_id" => "5",
				"iso2_cnt" => "IL",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "179",
				"zone_id" => "5",
				"iso2_cnt" => "JP",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "180",
				"zone_id" => "5",
				"iso2_cnt" => "JO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "181",
				"zone_id" => "5",
				"iso2_cnt" => "KZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "182",
				"zone_id" => "5",
				"iso2_cnt" => "KW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "183",
				"zone_id" => "5",
				"iso2_cnt" => "KG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "184",
				"zone_id" => "5",
				"iso2_cnt" => "LB",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "185",
				"zone_id" => "5",
				"iso2_cnt" => "MO",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "186",
				"zone_id" => "5",
				"iso2_cnt" => "MY",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "187",
				"zone_id" => "5",
				"iso2_cnt" => "MV",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "188",
				"zone_id" => "5",
				"iso2_cnt" => "MN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "189",
				"zone_id" => "5",
				"iso2_cnt" => "MM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "190",
				"zone_id" => "5",
				"iso2_cnt" => "NP",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "191",
				"zone_id" => "5",
				"iso2_cnt" => "OM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "192",
				"zone_id" => "5",
				"iso2_cnt" => "PK",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "193",
				"zone_id" => "5",
				"iso2_cnt" => "PH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "194",
				"zone_id" => "5",
				"iso2_cnt" => "QA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "195",
				"zone_id" => "5",
				"iso2_cnt" => "SA",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "196",
				"zone_id" => "5",
				"iso2_cnt" => "SG",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "197",
				"zone_id" => "5",
				"iso2_cnt" => "LK",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "198",
				"zone_id" => "5",
				"iso2_cnt" => "TW",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "199",
				"zone_id" => "5",
				"iso2_cnt" => "TJ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "200",
				"zone_id" => "5",
				"iso2_cnt" => "TH",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "201",
				"zone_id" => "5",
				"iso2_cnt" => "TR",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "202",
				"zone_id" => "5",
				"iso2_cnt" => "TM",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "203",
				"zone_id" => "5",
				"iso2_cnt" => "AE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "204",
				"zone_id" => "5",
				"iso2_cnt" => "UZ",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "205",
				"zone_id" => "5",
				"iso2_cnt" => "VN",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "206",
				"zone_id" => "5",
				"iso2_cnt" => "YE",
				"code_sta" => "",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "207",
				"zone_id" => "9",
				"iso2_cnt" => "US",
				"code_sta" => "HI",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "208",
				"zone_id" => "9",
				"iso2_cnt" => "US",
				"code_sta" => "AK",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "209",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "AL",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "210",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "AZ",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "211",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "AR",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "212",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "CA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "213",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "CO",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "214",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "CT",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "215",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "DE",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "258",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "DC",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "216",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "FL",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "217",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "GA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "218",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "ID",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "219",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "IL",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "220",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "IN",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "221",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "IA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "222",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "KS",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "223",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "KY",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "224",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "LA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "225",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "ME",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "226",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MD",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "227",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "228",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MI",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "229",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MN",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "230",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MS",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "231",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MO",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "232",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "MT",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "233",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NE",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "234",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NV",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "235",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NH",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "236",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NJ",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "237",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NM",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "238",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NY",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "239",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "NC",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "240",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "ND",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "241",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "OH",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "242",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "OK",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "243",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "OR",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "244",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "PA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "245",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "RI",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "246",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "SC",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "247",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "SD",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "248",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "TN",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "249",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "TX",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "250",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "UT",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "251",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "VT",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "252",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "VA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "253",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "WA",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "254",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "WV",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "255",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "WI",
			)
		);

		$wpdb->insert( 
			"ec_zone_to_location",
			array(
				"zone_to_location_id" => "256",
				"zone_id" => "8",
				"iso2_cnt" => "US",
				"code_sta" => "WY"
			)
		);

	}

}
?>