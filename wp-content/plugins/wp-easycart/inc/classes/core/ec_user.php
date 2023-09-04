<?php

class ec_user{
	protected $mysqli;									// ec_db structure

	public $user_id;									// INT
	public $email;										// VARCHAR 255
	public $email_other;								// VARCHAR 255
	public $user_level;									// VARCHAR 255
	public $role_id;									// INT
	public $is_subscriber;								// BOOLEAN

	public $first_name;									// VARCHAR 255
	public $last_name;									// VARCHAR 255
	public $vat_registration_number;					// VARCHAR 255

	public $billing_id;									// INT
	public $shipping_id;								// INT

	public $billing;									// ec_address structure
	public $shipping;									// ec_address structure

	public $realauth_registered;						// BOOL
	public $stripe_customer_id;							// VARCHAR 128

	public $card_type;
	public $last4;
	private $password;									// VARCHAR 255

	public $customfields = array();						// array of customfield objects

	public $taxfree;									// Boolean
	public $freeshipping;								// Boolean

	function __construct( $email = "" ){ 

		$this->mysqli = new ec_db();

		$this->user_id = ( ( isset( $GLOBALS['ec_cart_data']->cart_data->user_id ) ) ? (int) $GLOBALS['ec_cart_data']->cart_data->user_id : 0 );
		$this->email = ( ( isset( $GLOBALS['ec_cart_data']->cart_data->email ) ) ? $GLOBALS['ec_cart_data']->cart_data->email : '' );

		$user = $this->mysqli->get_user( $this->user_id, $this->email );

		if( $user && $user->user_level ){
			$this->first_name = $user->first_name;
			$this->last_name = $user->last_name;
			$this->email_other = $user->email_other;
			$this->vat_registration_number = $user->vat_registration_number;
			$this->user_level = $user->user_level;
			$this->role_id = $user->role_id;
			$this->is_subscriber = $user->is_subscriber;
			$this->billing_id = $user->default_billing_address_id;
			$this->shipping_id = $user->default_shipping_address_id;
			$this->stripe_customer_id = $user->stripe_customer_id;
			$this->card_type = $user->default_card_type;
			$this->last4 = $user->default_card_last4;
			$this->taxfree = $user->exclude_tax;
			$this->freeshipping = $user->exclude_shipping;
		}else{
			$this->first_name = "";
			$this->last_name = "";
			$this->vat_registration_number = "";
			$this->user_level = "";
			$this->role_id = 0;
			$this->is_subscriber = "";
			$this->billing_id = "";
			$this->shipping_id = "";
			$this->stripe_customer_id = "";
			$this->taxfree = false;
			$this->freeshipping = false;
		}

		if( $user && $user->billing_first_name ){
			$this->billing = new ec_address( $user->billing_first_name, $user->billing_last_name, $user->billing_address_line_1, $user->billing_address_line_2, $user->billing_city, $user->billing_state, $user->billing_zip, $user->billing_country, $user->billing_phone, $user->billing_company_name );
		}else{
			$this->billing = new ec_address( "", "", "", "", "", "", "", "", "", "" );
		}

		// User has gone through the checkout info page
		if( $user && $user->shipping_first_name ){
			$this->shipping = new ec_address( $user->shipping_first_name, $user->shipping_last_name, $user->shipping_address_line_1, $user->shipping_address_line_2, $user->shipping_city, $user->shipping_state, $user->shipping_zip, $user->shipping_country, $user->shipping_phone, $user->shipping_company_name );

		// Fall back option
		}else{
			$this->shipping = new ec_address( "", "", "", "", "", "", "", "", "", "" );
		}

		if( isset( $user ) && $user )
			$this->realauth_registered = $user->realauth_registered;

		if( $user && $user->customfield_data ){
			$customfield_data_array = explode( "---", $user->customfield_data );
			for( $i=0; $i<count( $customfield_data_array ); $i++ ){
				$temp_arr = explode("***", $customfield_data_array[$i]);
				array_push($this->customfields, $temp_arr);
			}
		}

	}

	private function setup_billing_info(){

		if(	isset($_POST['EmailNew']))		setup_billing_info_from_post();
		else								setup_billing_info_from_db();

	}

	private function setup_shipping_info(){

		if(	isset($_POST['EmailNew']))		setup_shipping_info_from_post();
		else								setup_shipping_info_from_db();

	}

	public function setup_billing_info_data( $bname, $blastname, $baddress, $baddress2, $bcity, $bstate, $bcountry, $bzip, $bphone, $bcompany ){

		$this->billing = new ec_address( $bname, $blastname, $baddress, $baddress2, $bcity, $bstate, $bzip, $bcountry, $bphone, $bcompany );

	}

	public function setup_shipping_info_data( $sname, $slastname, $saddress, $saddress2, $scity, $sstate, $scountry, $szip, $sphone, $scompany ){

		$this->shipping = new ec_address( $sname, $slastname, $saddress, $saddress2, $scity, $sstate, $szip, $scountry, $sphone, $scompany );

	}

	public function should_insert_user($userlevel, $createaccount){
		if($userlevel == "guest" && $createaccount)					return true;
		else 														return false;
	}

	public function is_guest( ){
		if( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest )	
																	return true;
		else														return false;	
	}

	public function insert_user( ){
		$this->billing_id = $this->insert_billing_info( );
		$this->shipping_id = $this->insert_shipping_info( );
		$this->user_id = $this->mysqli->insert_user( $this->email, $this->password, $this->first_name, $this->last_name, $this->billing_id, $this->shipping_id, "shopper", $this->is_subscriber );
		$this->mysqli->update_address_user_id( $this->billing_id, $this->user_id );
		$this->mysqli->update_address_user_id( $this->shipping_id, $this->user_id );

		// MyMail Hook
		if( function_exists( 'mailster' ) ){
			$subscriber_id = mailster('subscribers')->add(array(
				'fistname' => $this->first_name,
				'lastname' => $this->last_name,
				'email' => $this->email,
				'status' => 1,
			), false );
		}
	}

	public function insert_billing_info( ){
		$this->mysqli->insert_address( $this->billing->first_name, $this->billing->last_name, $this->billing->address_line_1, $this->billing->city, $this->billing->state, $this->billing->zip, $this->billing->country, $this->billing->phone, $this->billing->company_name );
	}

	public function insert_shipping_info( ){
		$this->mysqli->insert_address( $this->shipping->first_name, $this->shipping->last_name, $this->shipping->address_line_1, $this->shipping->city, $this->shipping->state, $this->shipping->zip, $this->shipping->country, $this->shipping->phone, $this->shipping->company_name );
	}

	public function display_email( ){
		echo esc_attr( $this->email );	
	}

	public function display_email_other( ){
		echo esc_attr( $this->email_other );	
	}

	public function display_custom_input_fields( $divider, $seperator ){
		for( $i=0; $i<count( $this->customfields ) && $this->customfields[$i][0] != ""; $i++ ){
			echo esc_attr( $this->customfields[$i][1] . $divider ) . " <input type=\"text\" name=\"ec_user_custom_field_" . esc_attr( $this->customfields[$i][0] ) . "\" id=\"ec_user_custom_field_" . esc_attr( $this->customfields[$i][0] ) . "\" value=\"" . esc_attr( $this->customfields[$i][2] ) . "\" />" . esc_attr( $seperator );
		}
	}

	public function display_custom_fields( $divider, $seperator ){
		for( $i=0; $i<count( $this->customfields ) && $this->customfields[$i][0] != ""; $i++ ){
			echo esc_attr( $this->customfields[$i][1] . $divider . " " . $this->customfields[$i][2] . $seperator );
		}
	}

	public function display_custom_input_label_single( $i ){
		echo esc_attr( $this->customfields[$i][1] );
	}

	public function display_custom_input_field_single( $i ){
		echo "<input type=\"text\" name=\"ec_user_custom_field_" . esc_attr( $this->customfields[$i][0] ) . "\" id=\"ec_user_custom_field_" . esc_attr( $this->customfields[$i][0] ) . "\" value=\"" . esc_attr( $this->customfields[$i][2] ) . "\" />" . esc_attr( $seperator );
	}

	public function get_payment_list( ){
		$ret_cards = array( );
		if( get_option( 'ec_option_payment_process_method' ) == "stripe" ){
			$stripe = new ec_stripe( );
			$card_list = $stripe->get_card_list( $this->stripe_customer_id );

			foreach( $card_list->data as $card ){
				$ret_cards[] = array( 'id' => $card->id, 'type' => $card->type, 'last4' => $card->last4, 'exp_month' => $card->exp_month, 'exp_year' => $card->exp_year );
			}
		}else if( get_option( 'ec_option_payment_process_method' ) == "stripe_connect" ){
			$stripe = new ec_stripe_connect( );
			$card_list = $stripe->get_card_list( $this->stripe_customer_id );

			foreach( $card_list->data as $card ){
				$ret_cards[] = array( 'id' => $card->id, 'type' => $card->type, 'last4' => $card->last4, 'exp_month' => $card->exp_month, 'exp_year' => $card->exp_year );
			}
		}else{
			return false;
		}

		return $ret_cards;
	}

	public function display_card_type( ){

		echo esc_attr( strtoupper( $this->card_type ) );

	}

	public function display_last4( ){

		echo esc_attr( $this->last4 );

	}
	
	public function has_active_subscription( $product_id ) {
		if ( 0 == $this->user_id ) {
			return false;
		}
		global $wpdb;
		return $wpdb->get_results( $wpdb->prepare ( 'SELECT * FROM ec_subscription WHERE subscription_status = "Active" AND product_id = %d AND user_id = %d', $product_id, $this->user_id ) );
	}
}
