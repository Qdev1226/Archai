<?php

class ec_notifications {

	private $wpdb;

	function __construct( ){
		global $wpdb;
		$this->wpdb =& $wpdb;
		if( get_option( 'ec_option_send_low_stock_emails' ) || get_option( 'ec_option_send_out_of_stock_emails' ) )
			add_action( 'wpeasycart_order_paid', array( $this, 'check_stock_levels' ), 10, 1 );
	}

	function check_stock_levels( $order_id ){
		$products = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT ec_product.product_id, ec_product.title, ec_product.show_stock_quantity, ec_product.use_optionitem_quantity_tracking, ec_product.stock_quantity, ec_orderdetail.optionitem_name_1, ec_orderdetail.optionitem_name_2, ec_orderdetail.optionitem_name_3, ec_orderdetail.optionitem_name_4, ec_orderdetail.optionitem_name_5 FROM ec_product, ec_orderdetail WHERE ec_orderdetail.order_id = %d AND ec_product.product_id = ec_orderdetail.product_id", $order_id ) );

		foreach( $products as $product ){
			if( ( get_option( 'ec_option_send_out_of_stock_emails' ) || get_option( 'ec_option_send_low_stock_emails' ) ) && $product->use_optionitem_quantity_tracking ){

				// Check product option item combo instead of total stock quantity
				$sql = $this->wpdb->prepare( "SELECT ec_optionitemquantity.quantity FROM ec_optionitemquantity, ec_optionitem WHERE ec_optionitemquantity.product_id = %d", $product->product_id );
				if( $product->optionitem_name_1 != "" )
					$sql .= $this->wpdb->prepare( " AND ec_optionitem.optionitem_name = %s AND ec_optionitemquantity.optionitem_id_1 = ec_optionitem.optionitem_id", $product->optionitem_name_1 );
				else
					$sql .= " AND ec_optionitemquantity.optionitem_id_1 = 0";

				if( $product->optionitem_name_2 != "" )
					$sql .= $this->wpdb->prepare( " AND ec_optionitem.optionitem_name = %s AND ec_optionitemquantity.optionitem_id_2 = ec_optionitem.optionitem_id", $product->optionitem_name_2 );
				else
					$sql .= " AND ec_optionitemquantity.optionitem_id_2 = 0";

				if( $product->optionitem_name_3 != "" )
					$sql .= $this->wpdb->prepare( " AND ec_optionitem.optionitem_name = %s AND ec_optionitemquantity.optionitem_id_3 = ec_optionitem.optionitem_id", $product->optionitem_name_3 );
				else
					$sql .= " AND ec_optionitemquantity.optionitem_id_3 = 0";

				if( $product->optionitem_name_4 != "" )
					$sql .= $this->wpdb->prepare( " AND ec_optionitem.optionitem_name = %s AND ec_optionitemquantity.optionitem_id_4 = ec_optionitem.optionitem_id", $product->optionitem_name_4 );
				else
					$sql .= " AND ec_optionitemquantity.optionitem_id_4 = 0";

				if( $product->optionitem_name_5 != "" )
					$sql .= $this->wpdb->prepare( " AND ec_optionitem.optionitem_name = %s AND ec_optionitemquantity.optionitem_id_5 = ec_optionitem.optionitem_id", $product->optionitem_name_5 );
				else
					$sql .= " AND ec_optionitemquantity.optionitem_id_5 = 0";

				$option_item_stock_quantity = $this->wpdb->get_var( $sql );
				if( $option_item_stock_quantity != NULL ){
					if( get_option( 'ec_option_send_out_of_stock_emails' ) && $option_item_stock_quantity == 0 ){
						$this->send_optionitem_out_of_stock_email_admin( $product, $option_item_stock_quantity );

					}else if( get_option( 'ec_option_send_low_stock_emails' ) && $option_item_stock_quantity > 0 && $option_item_stock_quantity <= get_option( 'ec_option_low_stock_trigger_total' ) ){
						$this->send_optionitem_low_stock_email_admin( $product, $option_item_stock_quantity );
					}
				}

			// Low Overall Stock method
			}else if( get_option( 'ec_option_send_out_of_stock_emails' ) && $product->show_stock_quantity && $product->stock_quantity == 0 ){
				$this->send_out_of_stock_email_admin( $product );

			// Overall Out of Stock Method
			}else if( get_option( 'ec_option_send_low_stock_emails' ) && $product->show_stock_quantity && $product->stock_quantity > 0 && $product->stock_quantity <= get_option( 'ec_option_low_stock_trigger_total' ) ){
				$this->send_low_stock_email_admin( $product );

			}

		}
	}

	public function send_low_stock_email_admin( $product ){
		$email_logo_url = get_option( 'ec_option_email_logo' );

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

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "X-Mailer: PHP/".phpversion();

		ob_start();
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_low_stock_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_low_stock_email.php';	
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_low_stock_email.php';
		$message = ob_get_clean();

		if( get_option( 'ec_option_use_wp_mail' ) ){
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), "Low Stock Notification", $message, implode("\r\n", $headers) );

		}else{
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$subject = "Low Stock Notification";
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $admin_email, $subject, $message );
		}
	}

	public function send_out_of_stock_email_admin( $product ){
		$email_logo_url = get_option( 'ec_option_email_logo' );

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

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "X-Mailer: PHP/".phpversion();

		ob_start();
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_out_of_stock_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_out_of_stock_email.php';	
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_out_of_stock_email.php';
		$message = ob_get_clean();

		if( get_option( 'ec_option_use_wp_mail' ) ){
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), "Out of Stock Notification", $message, implode("\r\n", $headers) );

		}else{
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$subject = "Out of Stock Notification";
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $admin_email, $subject, $message );
		}
	}

	public function send_optionitem_low_stock_email_admin( $product, $option_item_stock_quantity ){
		$email_logo_url = get_option( 'ec_option_email_logo' );

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

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "X-Mailer: PHP/".phpversion();

		ob_start();
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_optionitem_low_stock_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_optionitem_low_stock_email.php';	
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_optionitem_low_stock_email.php';
		$message = ob_get_clean();

		if( get_option( 'ec_option_use_wp_mail' ) ){
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), "Low Stock Notification", $message, implode("\r\n", $headers) );

		}else{
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$subject = "Low Stock Notification";
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $admin_email, $subject, $message );
		}
	}

	public function send_optionitem_out_of_stock_email_admin( $product, $option_item_stock_quantity ){
		$email_logo_url = get_option( 'ec_option_email_logo' );

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

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
		$headers[] = "X-Mailer: PHP/".phpversion();

		ob_start();
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_optionitem_out_of_stock_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_optionitem_out_of_stock_email.php';	
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_optionitem_out_of_stock_email.php';
		$message = ob_get_clean();

		if( get_option( 'ec_option_use_wp_mail' ) ){
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), "Out of Stock Notification", $message, implode("\r\n", $headers) );

		}else{
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$subject = "Out of Stock Notification";
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $admin_email, $subject, $message );
		}
	}
}
