<?php

class ec_subscription{

	private $mysqli;									// ec_db object

	public $subscription_id;							// DB ID for Subscription
	public $user_id;									// USER ID connecting subscription to user
	public $title;										// title of the subscription
	public $created;									// date created in UNIX timestamp format
	public $amount;										// 12.00 format
	public $quantity;									// INT
	public $product_id;									// id of the product
	public $trial_period_days;							// days of the trial
	public $bill_length;								// length of bill cycle, e.g. 4
	public $bill_period;								// period type (D, W, M, Y)
	public $status;										// Active, Suspended, or Canceled
	public $last_billed;								// date in UNIX timestamp format
	public $next_payment;								// date in UNIX timestamp format
	public $card_type;									// credit card type used
	public $last4;										// last 4 digits of credit card

	public $is_details;									// Get more details if this is details
	public $stripe_subscription_id;						// ID of a Stripe Subscription

	public $payment_duration;

	public $upgrades;									// Array of possible upgrades
	public $past_payments;								// Array of past payments

	public $membership_page;							// VARCHAR link

	public $account_page;								// VARCHAR
	public $cart_page;									// VARCHAR
	public $permalink_divider;							// CHAR

	function __construct( $subscription_row, $is_details = false ){

		$this->mysqli = new ec_db();
		$this->is_details = $is_details;

		$accountpageid = get_option('ec_option_accountpage');
		$cartpageid = get_option('ec_option_cartpage');

		if( function_exists( 'icl_object_id' ) ){
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->account_page = get_permalink( $accountpageid );
		$this->cart_page = get_permalink( $cartpageid );

		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
		}

		if( substr_count( $this->account_page, '?' ) )				$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";


		// Initialize data
		$this->set_db_vars( $subscription_row );
		$this->upgrades = $this->mysqli->get_subscription_upgrades( $this->subscription_id );
		$this->past_payments = $this->mysqli->get_subscription_payments( $this->subscription_id, $subscription_row->user_id );

	}

	//////////////////////////////////////////////////////////
	//Display Functions
	//////////////////////////////////////////////////////////

	public function display_title( ){
		echo esc_attr( $this->title );
	}

	public function display_next_bill_date( $date_format = "" ){
		if( $date_format == "" ){
			$date_format = get_option('date_format');
			echo esc_attr( gmdate( $date_format, $this->next_payment ) );
		}else{
			echo esc_attr( gmdate( $date_format, $this->next_payment ) );
		}
	}

	public function display_last_bill_date( $date_format = "" ){
		if( $date_format == "" ){
			$date_format = get_option('date_format');
			echo esc_attr( gmdate( $date_format, $this->last_billed ) );
		}else{
			echo esc_attr( gmdate( $date_format, $this->last_billed ) );
		}
	}

	public function display_price( ){
		echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->amount * $this->quantity ) . $this->get_bill_period_formatted( ) );
	}

	public function display_subscription_link( $text ){

		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=subscription_details&subscription_id=" . esc_attr( $this->subscription_id ) . "\">" . esc_attr( $text ) . "</a>";

	}

	public function has_membership_page( ){
		if( $this->membership_page != "" ){
			return true;
		}else{
			return false;
		}
	}

	public function display_membership_page_link( $link_text ){
		echo "<a href=\"" . esc_attr( $this->membership_page ) . "\" class=\"ec_account_membership_page_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_order_customer_email_notes( $order_id ){
		global $wpdb;
		$order_notes = $wpdb->get_results( $wpdb->prepare( "SELECT ec_product.order_completed_email_note FROM ec_order, ec_orderdetail, ec_product WHERE ec_order.order_id = %d AND ec_orderdetail.order_id = ec_order.order_id AND ec_product.product_id = ec_orderdetail.product_id GROUP BY ec_orderdetail.product_id", $order_id ) );
		foreach( $order_notes as $order_note ){
			if( $order_note->order_completed_email_note != '' ){
				$content = do_shortcode( stripslashes( $order_note->order_completed_email_note ) );
				$content = str_replace( ']]>', ']]&gt;', $content );
				echo wp_easycart_escape_html( $content ); //XSS OK.
			}
		}
	}

	/////////////////////////////////////////////////////////
	// Funtionality Functions
	/////////////////////////////////////////////////////////
	public function send_email_receipt( $user, $order, $order_details ){

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
		$headers[] = "X-Mailer: PHP/" . phpversion( );

		ob_start();
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_email_receipt.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_email_receipt.php';
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription_email_receipt.php';

		$message = ob_get_clean();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if( $email_send_method == "1" ){
			wp_mail( $user->email, wp_easycart_language( )->get_text( "cart_success", "cart_payment_receipt_title" ) . " " . $order->order_id, $message, implode("\r\n", $headers) );
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "cart_success", "cart_payment_receipt_title" ) . " " . $order->order_id, $message, implode("\r\n", $headers) );

		}else if( $email_send_method == "0" ){
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$to = $user->email;
			$subject = wp_easycart_language( )->get_text( "cart_success", "cart_payment_receipt_title" ) . " " . $order->order_id;
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $to, $subject, $message );
			$mailer->send_order_email( $admin_email, $subject, $message );

		}else{
			do_action( 'wpeasycart_custom_subscription_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $user->email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "cart_success", "cart_payment_receipt_title" ) . " " . $order->order_id, $message );

		}

	}

	public function send_trial_start_email( $user ){

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
		$headers[] = "X-Mailer: PHP/" . phpversion( );

		ob_start( );
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_trial_start_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_trial_start_email.php';
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription_trial_start_email.php';

		$message = ob_get_clean();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if( $email_send_method == "1" ){
			wp_mail( $user->email, wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_email_title" ), $message, implode("\r\n", $headers) );
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_email_title" ), $message, implode("\r\n", $headers) );

		}else if( $email_send_method == "0" ){
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$to = $user->email;
			$subject = wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_email_title" );
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $to, $subject, $message );
			$mailer->send_order_email( $admin_email, $subject, $message );

		}else{
			do_action( 'wpeasycart_custom_subscription_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $user->email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_email_title" ), $message );

		}

	}

	public function send_subscription_trial_ending_email( $user ){

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
		$headers[] = "X-Mailer: PHP/" . phpversion( );

		ob_start( );
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_trial_ending_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_trial_ending_email.php';
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription_trial_ending_email.php';

		$message = ob_get_clean();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if( $email_send_method == "1" ){
			wp_mail( $user->email, wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_ending_email_title" ), $message, implode("\r\n", $headers) );
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_ending_email_title" ), $message, implode("\r\n", $headers) );

		}else if( $email_send_method == "0" ){
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$to = $user->email;
			$subject = wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_ending_email_title" );
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $to, $subject, $message );
			$mailer->send_order_email( $admin_email, $subject, $message );

		}else{
			do_action( 'wpeasycart_custom_subscription_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $user->email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "subscription_trial", "subscription_trial_ending_email_title" ), $message );

		}

	}

	public function send_subscription_ended_email( $user ){

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
		$headers[] = "X-Mailer: PHP/" . phpversion( );

		ob_start( );
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_ended_email.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_ended_email.php';
		else
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription_ended_email.php';

		$message = ob_get_clean();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if( $email_send_method == "1" ){
			wp_mail( $user->email, wp_easycart_language( )->get_text( "subscription_ended", "subscription_ended_email_title" ), $message, implode("\r\n", $headers) );
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "subscription_ended", "subscription_ended_email_title" ), $message, implode("\r\n", $headers) );

		}else if( $email_send_method == "0" ){
			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
			$to = $user->email;
			$subject = wp_easycart_language( )->get_text( "subscription_ended", "subscription_ended_email_title" );
			$mailer = new wpeasycart_mailer( );
			$mailer->send_order_email( $to, $subject, $message );
			$mailer->send_order_email( $admin_email, $subject, $message );

		}else{
			do_action( 'wpeasycart_custom_subscription_order_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $user->email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( "subscription_ended", "subscription_ended_email_title" ), $message );

		}

	}

	public function get_subscription_purchase_link( ){
		global $wpdb;
		$model_number = $wpdb->get_var( $wpdb->prepare( "SELECT ec_product.model_number FROM ec_product WHERE ec_product.product_id = %d", $this->product_id ) );
		return $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number;
	}

	public function print_receipt( $order, $order_details ){

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

		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_email_receipt.php' ) )	
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription_email_receipt.php';
		else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription_email_receipt.php' ) )
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription_email_receipt.php';
		else{

		}

	}

	public function has_upgrades( ){

		if( count( $this->upgrades ) > 0 ){
			foreach( $this->upgrades as $upgrade ){
				if( $this->product_id == $upgrade->product_id ){
					return true;
				}
			}
		}

		echo "<input type=\"hidden\" name=\"ec_selected_plan\" value=\"" . esc_attr( $this->product_id ) . "\" />";

		return false;

	}

	public function display_upgrade_dropdown( ){

		$found_this = false;
		echo "<select name=\"ec_selected_plan\" id=\"ec_selected_plan\">";
		foreach( $this->upgrades as $upgrade ){
			if( $this->product_id == $upgrade->product_id ){
				$found_this = true;
			}

			if( $upgrade->can_downgrade || $found_this ){
				echo "<option value=\"" . esc_attr( $upgrade->product_id ) . "\"";
				if( $this->product_id == $upgrade->product_id ){
					echo " selected=\"selected\"";
				}
				echo ">" . wp_easycart_language( )->convert_text( $upgrade->title ) . " " . esc_attr( $GLOBALS['currency']->get_currency_display( $upgrade->price ) . $this->get_new_bill_period_formatted( $upgrade->subscription_bill_length, $upgrade->subscription_bill_period ) ) . "</option>";
			}
		}
		echo "</select>";

	}

	public function get_stripe_id( ){
		return $this->stripe_subscription_id;
	}

	public function display_past_payments( $date_format = "" ){

		if( $date_format == "" ){
			$date_format = get_option( 'date_format' ) . " " . get_option( 'time_format' );
		}

		if( $this->past_payments ){
			foreach( $this->past_payments as $payment ){
				echo '<div class="ec_account_subscriptions_past_payment_item">' . esc_attr( date( $date_format, strtotime( $payment->order_date ) ) ) . " | " . esc_attr( $GLOBALS['currency']->get_currency_display( $payment->grand_total ) ) . " | <a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=order_details&order_id=" . esc_attr( $payment->order_id ) . "\">" . wp_easycart_language( )->get_text( 'account_orders', 'account_orders_view_order_button' ) . "</a></div>";
			}
		}
	}

	public function display_cancel_form( $button_text, $confirm_text ){
		echo "<form method=\"POST\" action=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=subscriptions\">";
		echo "<input type=\"hidden\" name=\"ec_account_subscription_id\" value=\"" . esc_attr( $this->subscription_id ) . "\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" value=\"cancel_subscription\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-cancel-subscription-' . (int) $this->subscription_id ) ) . "\" />";
		echo "<input type=\"submit\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_cancel_subscription_check( '" . esc_attr( $confirm_text ) . "' );\">";
		echo "</form>";
	}

	public function is_canceled( ){
		if( $this->status == "Canceled" ){
			return true;
		}else{
			return false;
		}
	}

	/////////////////////////////////////////////////////////
	//Help Functions
	/////////////////////////////////////////////////////////

	private function get_new_bill_period_formatted( $length, $period ){

		$ret_string = "/";

		if( $length > 1 ){
			$ret_string .= $length . " ";
		}

		if( $period == "D" ){
			$ret_string .= "day";
		}else if( $period == "W" ){
			$ret_string .= "week";
		}else if( $period == "M" ){
			$ret_string .= "month";
		}else if( $period == "Y" ){
			$ret_string .= "year";
		}

		if( $length > 1 ){
			$ret_string .= "s";
		}

		return $ret_string;

	}

	private function get_bill_period_formatted( ){

		$ret_string = "/";

		if( $this->bill_length > 1 ){
			$ret_string .= $this->bill_length . " ";
		}

		if( $this->bill_period == "D" ){
			$ret_string .= "day";
		}else if( $this->bill_period == "W" ){
			$ret_string .= "week";
		}else if( $this->bill_period == "M" ){
			$ret_string .= "month";
		}else if( $this->bill_period == "Y" ){
			$ret_string .= "year";
		}

		if( $this->bill_length > 1 ){
			$ret_string .= "s";
		}

		$ret_string = apply_filters( 'wp_easycart_subscription_price_formatting', $ret_string );

		return $ret_string;

	}

	////////////////////////////////////////////////////////////
	//
	// MAIN DB FUNCTIONS
	//
	////////////////////////////////////////////////////////////

	private function set_db_vars( $db_row ){

		$this->subscription_id = $db_row->subscription_id;
		$this->user_id = $db_row->user_id;
		$this->title = $db_row->title;
		$this->amount = $db_row->price;
		$this->quantity = $db_row->quantity;
		$this->product_id = $db_row->product_id;
		$this->trial_period_days = $db_row->trial_period_days;
		$this->bill_length = $db_row->payment_length;
		$this->bill_period = $db_row->payment_period;
		$this->payment_duration = $db_row->payment_duration;
		$this->status = $db_row->subscription_status;
		$this->last_billed = $db_row->last_payment_date;
		$this->next_payment = $db_row->next_payment_date;
		if( isset( $db_row->credit_card_type ) )
			$this->card_type = $db_row->credit_card_type;
		else
			$this->card_type = "";
		if( isset( $db_row->credit_card_last4 ) )
			$this->last4 = $db_row->credit_card_last4;
		else
			$this->last4 = "";
		$this->stripe_subscription_id = $db_row->stripe_subscription_id;
		$this->membership_page = $db_row->membership_page;

	}

	////////////////////////////////////////////////////////////
	//
	// STRIPE FUNCTIONS
	//
	////////////////////////////////////////////////////////////

	private function set_stripe_vars( $subscription_row ){

		$this->stripe_subscription_id = $subscription_row->id;
		$this->title = $subscription_row->plan->name;
		$this->created = $subscription_row->plan->created;
		$this->amount = $this->convert_from_cents( $subscription_row->plan->amount );
		$this->product_id = $subscription_row->plan->id;
		$this->bill_length = $subscription_row->plan->interval_count;
		$this->bill_period = $this->stripe_convert_bill_period( $subscription_row->plan->interval );
		$this->status = $this->stripe_convert_status( $subscription_row->status );
		$this->last_billed = $subscription_row->current_period_start;
		$this->next_payment = $subscription_row->current_period_end;

	}

	private function convert_from_cents( $price ){
		return ( $price / 100 );
	}

	private function stripe_convert_bill_period( $period ){

		if( $period == "day" )
			return "D";
		else if( $period == "week" )
			return "W";
		else if( $period == "month" )
			return "M";
		else if( $period == "year" )
			return "Y";

	}

	private function stripe_convert_status( $status ){

		if( $status == "active" )
			return "Active";

	}

}

?>