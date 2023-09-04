<?php
class ec_paypal extends ec_third_party{

	public $available_url;

	public function get_available_url( ){
		if( !isset( $this->available_url ) ){
			if( is_callable( 'socket_create' ) && is_callable( 'socket_connect' ) && is_callable( 'socket_close' ) ){
				$socket = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
				$connection = @socket_connect( $socket, "connect.wpeasycart.com", 443 );
				$this->available_url = ( $connection ) ? "https://connect.wpeasycart.com" : "https://support.wpeasycart.com";
				@socket_close( $socket );
			}else{
				$this->available_url = "https://connect.wpeasycart.com";
			}
		}
		return $this->available_url;
	}

	public function display_form_start( ){
		$paypal_use_sandbox = get_option( 'ec_option_paypal_use_sandbox' );
		$paypal_email = get_option( 'ec_option_paypal_email' );
		$paypal_currency_code = get_option( 'ec_option_paypal_currency_code' );
		$paypal_charset = get_option( 'ec_option_paypal_charset' );
		$paypal_lc = get_option( 'ec_option_paypal_lc' );
		$paypal_weight_unit = get_option( 'ec_option_paypal_weight_unit' );

		//this is actionscript version in flash
		if( $paypal_use_sandbox )			$paypal_request = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		else								$paypal_request = "https://www.paypal.com/cgi-bin/webscr";

		$tax = new ec_tax( 0.00, 0.00, 0.00, $this->order->billing_state, $this->order->billing_country );
		$tax_total = number_format( $this->order->tax_total + $this->order->duty_total + $this->order->gst_total + $this->order->pst_total + $this->order->hst_total, 2 );
		if( !$tax->vat_included )
			$tax_total = number_format( $tax_total + $this->order->vat_total, 2 );

		echo "<form action=\"" . esc_attr( $paypal_request ) . "\" method=\"post\">";
		echo "<input name=\"cmd\" id=\"cmd\" type=\"hidden\" value=\"_cart\" />";
		echo "<input name=\"upload\" id=\"upload\" type=\"hidden\" value=\"1\" />";
		echo "<input name=\"custom\" id=\"custom\" type=\"hidden\" value=\"" . esc_attr( $this->order_id ) . "\" />";
		echo "<input name=\"bn\" id=\"bn\" type=\"hidden\" value=\"LevelFourDevelopmentLLC_Cart\" />";
		echo "<input name=\"business\" id=\"business\" type=\"hidden\" value=\"" . esc_attr( $paypal_email ) . "\" />";
		if( get_option( 'ec_option_paypal_use_selected_currency' ) ){
			$selected_currency = $paypal_currency_code;
			if( isset( $_COOKIE['ec_convert_to'] ) ){
				$selected_currency = substr( preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_convert_to'] ) ) ), 0, 3 );
			}
			echo "<input name=\"currency_code\" id=\"currency_code\" type=\"hidden\" value=\"" . esc_attr( strtoupper( $selected_currency ) ) . "\" />";
			if( $this->order->discount_total < $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ){
				echo "<input name=\"handling_cart\" id=\"handling_cart\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->shipping_total ) ) . "\" />";
			}
			echo "<input name=\"discount_amount_cart\" id=\"discount_amount_cart\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->discount_total ) ) . "\" />";
			echo "<input name=\"tax_cart\" id=\"tax_cart\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $tax_total ) ) . "\" />";
			echo "<input name=\"amount\" id=\"amount\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ) ) . "\" />";
		}else{
			echo "<input name=\"currency_code\" id=\"currency_code\" type=\"hidden\" value=\"" . esc_attr( strtoupper( $paypal_currency_code ) ) . "\" />";
			if( $this->order->discount_total < $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ){
				echo "<input name=\"handling_cart\" id=\"handling_cart\" type=\"hidden\" value=\"" . esc_attr( number_format($this->order->shipping_total, 2, '.', '' ) ) . "\" />";
			}
			echo "<input name=\"discount_amount_cart\" id=\"discount_amount_cart\" type=\"hidden\" value=\"" . esc_attr( number_format($this->order->discount_total, 2, '.', '' ) ) . "\" />";
			echo "<input name=\"tax_cart\" id=\"tax_cart\" type=\"hidden\" value=\"" . esc_attr( $tax_total ) . "\" />";
			echo "<input name=\"amount\" id=\"amount\" type=\"hidden\" value=\"" . esc_attr( number_format($this->order->sub_total + $this->order->fee_total + $this->order->tip_total, 2, '.', '' ) ) . "\" />";
		}
		echo "<input name=\"weight_cart\" id=\"weight_cart\" type=\"hidden\" value=\"" . esc_attr( number_format( $this->order->order_weight, 2, '.', '' ) ) . "\" />";
		echo "<input name=\"weight_unit\" id=\"weight_unit\" type=\"hidden\" value=\"" . esc_attr( $paypal_weight_unit ) . "\" />";
		if( get_option( 'ec_option_paypal_collect_shipping' ) ){
			echo "<input name=\"no_shipping\" id=\"no_shipping\" type=\"hidden\" value=\"2\" />";
		}else{
			echo "<input name=\"no_shipping\" id=\"no_shipping\" type=\"hidden\" value=\"1\" />";
		}
		echo "<input name=\"lc\" id=\"lc\" type=\"hidden\" value=\"" . esc_attr( $paypal_lc ) . "\" />";
		echo "<input name=\"charset\" id=\"charset\" type=\"hidden\" value=\"" . esc_attr( $paypal_charset ) . "\" />";
		echo "<input name=\"rm\" id=\"rm\" type=\"hidden\" value=\"2\" />";
		echo "<input name=\"notify_url\" id=\"notify_url\" type=\"hidden\" value=\"". esc_url( get_site_url() . '?wpeasycarthook=paypal-webhook' ) ."\" />";
		echo "<input type=\"hidden\" name=\"return\" value=\"". esc_attr( $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order_id ) . "\" />";
		echo "<input type=\"hidden\" name=\"cancel_return\" value=\"". esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_payment\" />";

		//customer billing information and address info
		if( get_option( 'ec_option_paypal_send_shipping_address' ) ){
			echo "<input name=\"first_name\" id=\"first_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_first_name, ENT_QUOTES ) ) . "\" />";	
			echo "<input name=\"last_name\" id=\"last_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_last_name, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address1\" id=\"address1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_address_line_1, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address2\" id=\"address2\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_address_line_2, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"city\" id=\"city\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_city, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"state\" id=\"state\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( strtoupper($this->order->shipping_state ), ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"zip\" id=\"zip\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_zip, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"country\" id=\"country\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_country, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"email\" id=\"email\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->user_email, ENT_QUOTES ) ) . "\" />";
		}else{
			echo "<input name=\"first_name\" id=\"first_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_first_name, ENT_QUOTES ) ) . "\" />";	
			echo "<input name=\"last_name\" id=\"last_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_last_name, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address1\" id=\"address1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_address_line_1, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address2\" id=\"address12\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_address_line_2, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"city\" id=\"city\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_city, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"state\" id=\"state\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( strtoupper($this->order->billing_state ), ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"zip\" id=\"zip\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_zip, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"country\" id=\"country\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_country, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"email\" id=\"email\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->user_email, ENT_QUOTES ) ) . "\" />";
		}
		//add the cart contents to paypal
		for( $i = 0; $i<count( $this->order_details ); $i++ ){
			$paypal_counter = $i+1;
			echo "<input name=\"item_name_" . esc_attr( $paypal_counter ) . "\" id=\"item_name_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->order_details[$i]->title ) . "\" />";
			echo "<input name=\"item_number_" . esc_attr( $paypal_counter ) . "\" id=\"item_number_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( substr( $this->order_details[$i]->model_number, 0, 127 ) ) . "\" />";
			if( get_option( 'ec_option_paypal_use_selected_currency' ) ){

				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price(  ( $this->order_details[$i]->total_price/$this->order_details[$i]->quantity ) ) ) . "\" />";
			}else{
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( number_format( ( $this->order_details[$i]->total_price/$this->order_details[$i]->quantity ), 2, '.', '' ) ) . "\" />";
			}
			echo "<input name=\"quantity_". esc_attr( $paypal_counter ) . "\" id=\"quantity_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->order_details[$i]->quantity ) . "\" />";
			echo "<input name=\"shipping_" . esc_attr( $paypal_counter ) . "\" id=\"shipping_" . esc_attr( $paypal_counter ) ."\" type=\"hidden\" value=\"0.00\" />";
			echo "<input name=\"shipping2_" . esc_attr( $paypal_counter ) . "\" id=\"shipping2_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"0.00\" />";
		}
		if( $this->order->discount_total >= $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ){
			$paypal_counter = $i+1;
			echo "<input name=\"item_name_" . esc_attr( $paypal_counter ) . "\" id=\"item_name_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' ) ) . "\" />";
			echo "<input name=\"item_number_" . esc_attr( $paypal_counter ) . "\" id=\"item_number_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( substr( strtolower( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' ) ), 0, 127 ) ) . "\" />";
			if( get_option( 'ec_option_paypal_use_selected_currency' ) ){
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->shipping_total ) ) . "\" />";
			}else{
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( number_format( $this->order->shipping_total, 2, '.', '' ) ) . "\" />";
			}
			echo "<input name=\"quantity_". esc_attr( $paypal_counter ) . "\" id=\"quantity_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"1\" />";
			echo "<input name=\"shipping_" . esc_attr( $paypal_counter ) . "\" id=\"shipping_" . esc_attr( $paypal_counter ) ."\" type=\"hidden\" value=\"0.00\" />";
			echo "<input name=\"shipping2_" . esc_attr( $paypal_counter ) . "\" id=\"shipping2_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"0.00\" />";
		}

	}

	public function display_auto_forwarding_form( ){
		$paypal_use_sandbox = get_option( 'ec_option_paypal_use_sandbox' );
		$paypal_email = get_option( 'ec_option_paypal_email' );
		$paypal_currency_code = get_option( 'ec_option_paypal_currency_code' );
		$paypal_charset = get_option( 'ec_option_paypal_charset' );
		$paypal_lc = get_option( 'ec_option_paypal_lc' );
		$paypal_weight_unit = get_option( 'ec_option_paypal_weight_unit' );

		//this is actionscript version in flash
		if( $paypal_use_sandbox )			$paypal_request = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		else								$paypal_request = "https://www.paypal.com/cgi-bin/webscr";

		$tax = new ec_tax( 0.00, 0.00, 0.00, $this->order->billing_state, $this->order->billing_country );
		$tax_total = number_format( $this->order->tax_total + $this->order->duty_total + $this->order->gst_total + $this->order->pst_total + $this->order->hst_total, 2 );
		if( !$tax->vat_included )
			$tax_total = number_format( $tax_total + $this->order->vat_total, 2 );

		echo "<style>
		.ec_third_party_submit_button{ width:100%; text-align:center; }
		.ec_third_party_submit_button > input{ margin-top:150px; width:300px; height:45px; background-color:#38E; color:#FFF; font-weight:bold; text-transform:uppercase; border:1px solid #A2C0D8; cursor:pointer; }
		.ec_third_party_submit_button > input:hover{ background-color:#7A99BF; }
		.ec_third_party_loader{ display:block !important; position:absolute; top:50%; left:50%; }
		@-webkit-keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);

			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		@-moz-keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		@-o-keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		@keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		/* Styles for old versions of IE */
		.ec_third_party_loader {
		  font-family: sans-serif;
		  font-weight: 100;
		}

		/* :not(:required) hides this rule from IE9 and below */
		.ec_third_party_loader:not(:required) {
		  -webkit-animation: ec_third_party_loader 1250ms infinite linear;
		  -moz-animation: ec_third_party_loader 1250ms infinite linear;
		  -ms-animation: ec_third_party_loader 1250ms infinite linear;
		  -o-animation: ec_third_party_loader 1250ms infinite linear;
		  animation: ec_third_party_loader 1250ms infinite linear;
		  border: 8px solid #3388ee;
		  border-right-color: transparent;
		  border-radius: 16px;
		  box-sizing: border-box;
		  display: inline-block;
		  position: relative;
		  overflow: hidden;
		  text-indent: -9999px;
		  width: 32px;
		  height: 32px;
		}
		</style>";

		echo "<div style=\"display:none;\" class=\"ec_third_party_loader\">Loading...</div>";

		echo "<form name=\"ec_paypal_standard_auto_form\" action=\"" . esc_attr( $paypal_request ) . "\" method=\"post\">";
		echo "<input name=\"cmd\" id=\"cmd\" type=\"hidden\" value=\"_cart\" />";
		echo "<input name=\"upload\" id=\"upload\" type=\"hidden\" value=\"1\" />";
		echo "<input name=\"custom\" id=\"custom\" type=\"hidden\" value=\"" . esc_attr( $this->order_id ) . "\" />";
		echo "<input name=\"bn\" id=\"bn\" type=\"hidden\" value=\"LevelFourDevelopmentLLC_Cart\" />";
		echo "<input name=\"business\" id=\"business\" type=\"hidden\" value=\"" . esc_attr( $paypal_email ) . "\" />";
		if( get_option( 'ec_option_paypal_use_selected_currency' ) ){
			$selected_currency = $paypal_currency_code;
			if( isset( $_COOKIE['ec_convert_to'] ) ){
				$selected_currency = substr( preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_convert_to'] ) ) ), 0, 3 );
			}
			echo "<input name=\"currency_code\" id=\"currency_code\" type=\"hidden\" value=\"" . esc_attr( strtoupper( $selected_currency ) ) . "\" />";
			if( $this->order->discount_total < $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ){
				echo "<input name=\"handling_cart\" id=\"handling_cart\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->shipping_total ) ) . "\" />";
			}
			echo "<input name=\"discount_amount_cart\" id=\"discount_amount_cart\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->discount_total ) ) . "\" />";
			echo "<input name=\"tax_cart\" id=\"tax_cart\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $tax_total ) ) . "\" />";
			echo "<input name=\"amount\" id=\"amount\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ) ) . "\" />";
		}else{
			echo "<input name=\"currency_code\" id=\"currency_code\" type=\"hidden\" value=\"" . esc_attr( strtoupper( $paypal_currency_code ) ) . "\" />";
			if( $this->order->discount_total < $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ){
				echo "<input name=\"handling_cart\" id=\"handling_cart\" type=\"hidden\" value=\"" . esc_attr( number_format($this->order->shipping_total, 2, '.', '' ) ) . "\" />";
			}
			echo "<input name=\"discount_amount_cart\" id=\"discount_amount_cart\" type=\"hidden\" value=\"" . esc_attr( number_format($this->order->discount_total, 2, '.', '' ) ) . "\" />";
			echo "<input name=\"tax_cart\" id=\"tax_cart\" type=\"hidden\" value=\"" . esc_attr( $tax_total ) . "\" />";
			echo "<input name=\"amount\" id=\"amount\" type=\"hidden\" value=\"" . esc_attr( number_format($this->order->sub_total + $this->order->fee_total + $this->order->tip_total, 2, '.', '' ) ) . "\" />";
		}
		echo "<input name=\"weight_cart\" id=\"weight_cart\" type=\"hidden\" value=\"" . esc_attr( number_format( $this->order->order_weight, 2, '.', '' ) ) . "\" />";
		echo "<input name=\"weight_unit\" id=\"weight_unit\" type=\"hidden\" value=\"" . esc_attr( $paypal_weight_unit ) . "\" />";
		if( get_option( 'ec_option_paypal_collect_shipping' ) ){
			echo "<input name=\"no_shipping\" id=\"no_shipping\" type=\"hidden\" value=\"2\" />";
		}else{
			echo "<input name=\"no_shipping\" id=\"no_shipping\" type=\"hidden\" value=\"1\" />";
		}
		echo "<input name=\"lc\" id=\"lc\" type=\"hidden\" value=\"" . esc_attr( $paypal_lc ) . "\" />";
		echo "<input name=\"charset\" id=\"charset\" type=\"hidden\" value=\"" . esc_attr( $paypal_charset ) . "\" />";
		echo "<input name=\"rm\" id=\"rm\" type=\"hidden\" value=\"2\" />";
		echo "<input name=\"notify_url\" id=\"notify_url\" type=\"hidden\" value=\"". esc_url( get_site_url() . '?wpeasycarthook=paypal-webhook' ) ."\" />";
		echo "<input type=\"hidden\" name=\"return\" value=\"". esc_attr( $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order_id ) . "\" />";
		echo "<input type=\"hidden\" name=\"cancel_return\" value=\"". esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_payment\" />";

		//customer billing information and address info
		if( get_option( 'ec_option_paypal_send_shipping_address' ) ){
			echo "<input name=\"first_name\" id=\"first_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_first_name, ENT_QUOTES ) ) . "\" />";	
			echo "<input name=\"last_name\" id=\"last_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_last_name, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address1\" id=\"address1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_address_line_1, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address2\" id=\"address2\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_address_line_2, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"city\" id=\"city\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_city, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"state\" id=\"state\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( strtoupper($this->order->shipping_state ), ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"zip\" id=\"zip\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_zip, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"country\" id=\"country\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->shipping_country, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"email\" id=\"email\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->user_email, ENT_QUOTES ) ) . "\" />";
		}else{
			echo "<input name=\"first_name\" id=\"first_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_first_name, ENT_QUOTES ) ) . "\" />";	
			echo "<input name=\"last_name\" id=\"last_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_last_name, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address1\" id=\"address1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_address_line_1, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"address2\" id=\"address2\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_address_line_2, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"city\" id=\"city\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_city, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"state\" id=\"state\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( strtoupper($this->order->billing_state ), ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"zip\" id=\"zip\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_zip, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"country\" id=\"country\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->billing_country, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"email\" id=\"email\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $this->order->user_email, ENT_QUOTES ) ) . "\" />";
		}

		//add the cart contents to paypal
		for( $i = 0; $i<count( $this->order_details ); $i++ ){
			$paypal_counter = $i+1;
			echo "<input name=\"item_name_" . esc_attr( $paypal_counter ) . "\" id=\"item_name_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->order_details[$i]->title  ) . "\" />";
			echo "<input name=\"item_number_" . esc_attr( $paypal_counter ) . "\" id=\"item_number_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->order_details[$i]->model_number ) . "\" />";
			if( get_option( 'ec_option_paypal_use_selected_currency' ) ){
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price(  ( $this->order_details[$i]->total_price/$this->order_details[$i]->quantity ) ) ) . "\" />";
			}else{
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( number_format( ( $this->order_details[$i]->total_price/$this->order_details[$i]->quantity ), 2, '.', '' ) ) . "\" />";
			}
			echo "<input name=\"quantity_". esc_attr( $paypal_counter ) . "\" id=\"quantity_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->order_details[$i]->quantity ) . "\" />";
			echo "<input name=\"shipping_" . esc_attr( $paypal_counter ) . "\" id=\"shipping_" . esc_attr( $paypal_counter ) ."\" type=\"hidden\" value=\"0.00\" />";
			echo "<input name=\"shipping2_" . esc_attr( $paypal_counter ) . "\" id=\"shipping2_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"0.00\" />";
		}
		if( $this->order->discount_total >= $this->order->sub_total + $this->order->fee_total + $this->order->tip_total ){
			$paypal_counter = $i+1;
			echo "<input name=\"item_name_" . esc_attr( $paypal_counter ) . "\" id=\"item_name_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' ) ) . "\" />";
			echo "<input name=\"item_number_" . esc_attr( $paypal_counter ) . "\" id=\"item_number_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( strtolower( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' ) ) ) . "\" />";
			if( get_option( 'ec_option_paypal_use_selected_currency' ) ){
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['currency']->convert_price( $this->order->shipping_total ) ) . "\" />";
			}else{
				echo "<input name=\"amount_" . esc_attr( $paypal_counter ) . "\" id=\"amount_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"" . esc_attr( number_format( $this->order->shipping_total, 2, '.', '' ) ) . "\" />";
			}
			echo "<input name=\"quantity_". esc_attr( $paypal_counter ) . "\" id=\"quantity_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"1\" />";
			echo "<input name=\"shipping_" . esc_attr( $paypal_counter ) . "\" id=\"shipping_" . esc_attr( $paypal_counter ) ."\" type=\"hidden\" value=\"0.00\" />";
			echo "<input name=\"shipping2_" . esc_attr( $paypal_counter ) . "\" id=\"shipping2_" . esc_attr( $paypal_counter ) . "\" type=\"hidden\" value=\"0.00\" />";
		}
		echo "<div class=\"ec_third_party_submit_button\"><input type=\"submit\" value=\"" . esc_attr( wp_easycart_language( )->get_text( "cart_payment_information", "cart_payment_information_third_party" ) ) . " PayPal\" id=\"ec_third_party_submit_payment\" /></div>";
		echo "</form>";
		echo "<SCRIPT>document.getElementById( 'ec_third_party_submit_payment' ).style.display = 'none';</SCRIPT>";
		echo "<SCRIPT data-cfasync=\"false\" LANGUAGE=\"Javascript\">document.ec_paypal_standard_auto_form.submit();</SCRIPT>";
	}

	public function display_subscription_form( $order_id, $user, $product, $quantity = 1 ){
		$paypal_use_sandbox = get_option( 'ec_option_paypal_use_sandbox' );
		$paypal_email = get_option( 'ec_option_paypal_email' );
		$paypal_currency_code = get_option( 'ec_option_paypal_currency_code' );
		$paypal_lc = get_option( 'ec_option_paypal_lc' );
		$paypal_weight_unit = get_option( 'ec_option_paypal_weight_unit' );

		//this is actionscript version in flash
		if( $paypal_use_sandbox )			$paypal_request = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		else								$paypal_request = "https://www.paypal.com/cgi-bin/webscr";

		echo "<style>
		.ec_third_party_submit_button{ width:100%; text-align:center; }
		.ec_third_party_submit_button > input{ margin-top:150px; width:300px; height:45px; background-color:#38E; color:#FFF; font-weight:bold; text-transform:uppercase; border:1px solid #A2C0D8; cursor:pointer; }
		.ec_third_party_submit_button > input:hover{ background-color:#7A99BF; }
		.ec_third_party_loader{ display:block !important; position:absolute; top:50%; left:50%; }
		@-webkit-keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		@-moz-keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		@-o-keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		@keyframes ec_third_party_loader {
		  0% {
			-webkit-transform: rotate(0deg);
			-moz-transform: rotate(0deg);
			-ms-transform: rotate(0deg);
			-o-transform: rotate(0deg);
			transform: rotate(0deg);
		  }

		  100% {
			-webkit-transform: rotate(360deg);
			-moz-transform: rotate(360deg);
			-ms-transform: rotate(360deg);
			-o-transform: rotate(360deg);
			transform: rotate(360deg);
		  }
		}

		/* Styles for old versions of IE */
		.ec_third_party_loader {
		  font-family: sans-serif;
		  font-weight: 100;
		}

		/* :not(:required) hides this rule from IE9 and below */
		.ec_third_party_loader:not(:required) {
		  -webkit-animation: ec_third_party_loader 1250ms infinite linear;
		  -moz-animation: ec_third_party_loader 1250ms infinite linear;
		  -ms-animation: ec_third_party_loader 1250ms infinite linear;
		  -o-animation: ec_third_party_loader 1250ms infinite linear;
		  animation: ec_third_party_loader 1250ms infinite linear;
		  border: 8px solid #3388ee;
		  border-right-color: transparent;
		  border-radius: 16px;
		  box-sizing: border-box;
		  display: inline-block;
		  position: relative;
		  overflow: hidden;
		  text-indent: -9999px;
		  width: 32px;
		  height: 32px;
		}
		</style>";

		echo "<div style=\"display:none;\" class=\"ec_third_party_loader\">Loading...</div>";

		echo "<form name=\"ec_paypal_standard_auto_form\" action=\"" . esc_attr( $paypal_request ) . "\" method=\"post\">";
		echo "<input name=\"bn\" id=\"bn\" type=\"hidden\" value=\"LevelFourDevelopmentLLC_Cart\" />";
		echo "<input name=\"business\" id=\"business\" type=\"hidden\" value=\"" . esc_attr( $paypal_email ) . "\" />";
		echo "<input name=\"currency_code\" id=\"currency_code\" type=\"hidden\" value=\"" . esc_attr( strtoupper( $paypal_currency_code ) ) . "\" />";
		echo "<input name=\"lc\" id=\"lc\" type=\"hidden\" value=\"" . esc_attr( $paypal_lc ) . "\" />";
		echo "<input name=\"notify_url\" id=\"notify_url\" type=\"hidden\" value=\"". esc_url( get_site_url() . '?wpeasycarthook=paypal-webhook' ) ."\" />";
		echo "<input type=\"hidden\" name=\"return\" value=\"". esc_attr( $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order_id ) . "\" />";
		echo "<input type=\"hidden\" name=\"cancel_return\" value=\"". esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_payment\" />";
		echo "<input type=\"hidden\" name=\"cmd\" value=\"_xclick-subscriptions\" />";

		//customer billing information and address info
		echo "<input name=\"first_name\" id=\"first_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->first_name, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"last_name\" id=\"last_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->last_name, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"address1\" id=\"address1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->address_line_1, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"address2\" id=\"address2\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->address_line_2, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"city\" id=\"city\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->city, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"state\" id=\"state\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( strtoupper($user->billing->state ), ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"zip\" id=\"zip\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->zip, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"country\" id=\"country\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->billing->country, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"email\" id=\"email\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $user->email, ENT_QUOTES ) ) . "\" />";

		echo "<input name=\"item_name\" id=\"item_name\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $product->title, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"item_number\" id=\"item_number\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $product->model_number, ENT_QUOTES ) ) . "\" />";

		if( $product->subscription_signup_fee > 0 ){
			echo "<input name=\"a1\" id=\"a1\" type=\"hidden\" value=\"" . esc_attr( number_format( $product->subscription_signup_fee + $product->price * $quantity, 2 ) ) . "\" />";
			echo "<input name=\"p1\" id=\"p1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $product->subscription_bill_length, ENT_QUOTES ) ) . "\" />";
			echo "<input name=\"t1\" id=\"t1\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $product->subscription_bill_period, ENT_QUOTES ) ) . "\" />";
		}

		echo "<input name=\"a3\" id=\"a3\" type=\"hidden\" value=\"" . esc_attr( number_format( $product->price * $quantity, 2 ) ) . "\" />";
		echo "<input name=\"p3\" id=\"p3\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $product->subscription_bill_length, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"t3\" id=\"t3\" type=\"hidden\" value=\"" . esc_attr( htmlspecialchars( $product->subscription_bill_period, ENT_QUOTES ) ) . "\" />";
		echo "<input name=\"src\" id=\"src\" type=\"hidden\" value=\"1\" />";
		if( $product->subscription_bill_duration > 1 )
			echo "<input name=\"srt\" id=\"srt\" type=\"hidden\" value=\"" . esc_attr( $product->subscription_bill_duration ) . "\" />";

		echo "<input name=\"no_note\" id=\"no_note\" type=\"hidden\" value=\"1\" />";

		echo "<input name=\"custom\" id=\"custom\" type=\"hidden\" value=\"" . esc_attr( $order_id ) . "\" />";
		echo "<input name=\"invoice\" id=\"invoice\" type=\"hidden\" value=\"" . esc_attr( $order_id ) . "\" />";

		echo "<input name=\"modify\" id=\"modify\" type=\"hidden\" value=\"0\" />";
		echo "<input name=\"usr_manage\" id=\"usr_manage\" type=\"hidden\" value=\"1\" />";

		echo "<div class=\"ec_third_party_submit_button\"><input type=\"submit\" value=\"" . esc_attr( wp_easycart_language( )->get_text( "cart_payment_information", "cart_payment_information_third_party" ) ) . " PayPal\" id=\"ec_third_party_submit_payment\" /></div>";

		echo "</form>";
		echo "<SCRIPT>document.getElementById( 'ec_third_party_submit_payment' ).style.display = 'none';</SCRIPT>";
		echo "<SCRIPT data-cfasync=\"false\" LANGUAGE=\"Javascript\">document.ec_paypal_standard_auto_form.submit();</SCRIPT>";
	}

	public function create_webhook( ){

		// Include the DB
		$db = new ec_db( );

		// Personal APP Only
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? 'https://api.sandbox.paypal.com/v1/notifications/webhooks/' : 'https://api.paypal.com/v1/notifications/webhooks/';
			$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

			$headr = array( 
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
				'PayPal-Partner-Attribution-Id' => 'LevelFourDevelopment_SP_PPM'
			);

			$transaction_data = (object) array( 
				"url" 			=> get_site_url() . '?wpeasycarthook=paypal-webhook',
				"event_types"	=> array(
					(object) array(
						"name"	=> "PAYMENT.AUTHORIZATION.CREATED"
					),
					(object) array(
						"name"	=> "PAYMENT.AUTHORIZATION.VOIDED"
					),
					(object) array(
						"name"	=> "PAYMENT.CAPTURE.COMPLETED"
					),
					(object) array(
						"name"	=> "PAYMENT.CAPTURE.REFUNDED"
					),
					(object) array(
						"name"	=> "PAYMENT.SALE.COMPLETED"
					),
					(object) array(
						"name"	=> "PAYMENT.SALE.REFUNDED"
					),
					(object) array(
						"name"	=> "PAYMENT.SALE.PENDING"
					),
					(object) array(
						"name"	=> "CHECKOUT.ORDER.PROCESSED"
					),
					(object) array(
						"name"	=> "PAYMENT.ORDER.CANCELLED"
					),
					(object) array(
						"name"	=> "PAYMENT.ORDER.CREATED"
					)
				)
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal API Webhook CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal API Webhook Response", print_r( $response, true ) );
			}

			$json = json_decode( $response['body'] );
			if( isset( $json->id ) ){
				( get_option( 'ec_option_paypal_use_sandbox' ) ) ? update_option( 'ec_option_paypal_sandbox_webhook_id', $json->id ) : update_option( 'ec_option_paypal_production_webhook_id', $json->id );
			}

		// WP EasyCart APP		
		}else{ 

			$is_sandbox = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? 1 : 0;
			$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
			$webhook_url = get_site_url() . '?wpeasycarthook=paypal-webhook';

			$url = $this->get_available_url( ) . "/paypal-v2/webhook-create.php?is_sandbox=".$is_sandbox."&merchantID=".$merchant_id."&webhookURL=".$webhook_url;

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'GET',
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal API Webhook V2 CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal API Webhook V2 Response", print_r( $response, true ) );
			}

			$json = json_decode( $response['body'] );
			if( isset( $json->webhook_id ) )
				( get_option( 'ec_option_paypal_use_sandbox' ) ) ? update_option( 'ec_option_paypal_wpeasycart_sandbox_webhook_id', $json->webhook_id ) : update_option( 'ec_option_paypal_wpeasycart_production_webhook_id', $json->webhook_id );

		}

	}

	public function create_order( ){

		// Check Web Hook
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_webhook_id' ) == '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) &&  get_option( 'ec_option_paypal_production_webhook_id' ) == '' ) 
		){
			$this->create_webhook( );
		}

		// Do a Token Check First
		$this->handle_token( );

		// Include the DB
		global $wpdb;
		$db = new ec_db( );

		// Create the Cart Page
		$cartpage = new ec_cartpage( );

		// Setup Data
		$paypal_currency = ( get_option( 'ec_option_paypal_use_selected_currency' ) && isset( $_COOKIE['ec_convert_to'] ) ) ? substr( preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_convert_to'] ) ) ), 0, 3 ) : get_option( 'ec_option_paypal_currency_code' );

		$tax_total = number_format( $cartpage->order_totals->tax_total + $cartpage->order_totals->duty_total + $cartpage->order_totals->gst_total + $cartpage->order_totals->pst_total + $cartpage->order_totals->hst_total, 2 );
		if( !$cartpage->tax->vat_included )
			$tax_total = number_format( $tax_total + $cartpage->order_totals->vat_total, 2 );

		$fee_rate = apply_filters( 'wp_easycart_stripe_connect_fee_rate', 2 );

		// Create Items Array
		$items = array( );
		foreach( $cartpage->cart->cart as $cart_item ){

			$item = (object) array(
				"name"			=> substr( htmlspecialchars( preg_replace( "/[^A-Za-z0-9 \,\:]/", '', str_replace( "\r\n", ", ", $cart_item->title ) ), ENT_QUOTES ), 0, 127 ),
				"quantity"		=> esc_attr( $cart_item->quantity ),
				"price"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $cart_item->unit_price ) : $cart_item->unit_price, 2, '.', '' ),
				"sku"			=> substr( esc_attr( htmlspecialchars( $cart_item->model_number, ENT_QUOTES ) ), 0, 127 ),
				"description"	=> substr( esc_attr( htmlspecialchars( preg_replace( "/[^A-Za-z0-9 \,\:]/", '', str_replace( "\r\n", ", ", $this->build_item_description( $cart_item ) ) ), ENT_QUOTES ) ), 0, 127 ),
				"currency"		=> esc_attr( strtoupper( $paypal_currency ) )
			);

			$items[] = $item;

			$onetime_price_adjustments = $this->get_item_onetime_price_adjustments( $cart_item );
			if( count( $onetime_price_adjustments ) > 0 ){
				foreach( $onetime_price_adjustments as $adjustment ){	
					$item = (object) array(
						"name"			=> substr( htmlspecialchars( preg_replace( "/[^A-Za-z0-9 \,\:]/", '', str_replace( "\r\n", ", ", $adjustment['name'] ) ), ENT_QUOTES ), 0, 127 ),
						"quantity"		=> '1',
						"price"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $adjustment['price'] ) : $adjustment['price'], 2, '.', '' ),
						"currency"		=> esc_attr( strtoupper( $paypal_currency ) )
					);
					$items[] = $item;
				} // close price adjustment loop
			} // close price adjustment if
		}

		if( $cartpage->order_totals->discount_total > 0 ){ 
			$item = (object) array(
				"name"			=> substr( htmlspecialchars( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_discounts' ), ENT_QUOTES ), 0, 127),
				"quantity"		=> '1',
				"price"			=> (-1) * number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $cartpage->order_totals->discount_total ) : $cartpage->order_totals->discount_total, 2, '.', '' ),
				"currency"		=> esc_attr( strtoupper( $paypal_currency ) )
			);
			$items[] = $item;
		}

		if( $cartpage->order_totals->tip_total > 0 ){
			$item = (object) array(
				"name"			=> substr( htmlspecialchars( wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip' ), ENT_QUOTES ), 0, 127),
				"quantity"		=> '1',
				"price"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $cartpage->order_totals->tip_total ) : $cartpage->order_totals->tip_total, 2, '.', '' ),
				"currency"		=> esc_attr( strtoupper( $paypal_currency ) )
			);
			$items[] = $item;
		}

		if ( count( $cartpage->tax->fees ) > 0 ) {
			foreach ( $cartpage->tax->fees as $fee ) {
				$item = (object) array(
					"name"			=> esc_attr( substr( htmlspecialchars( $fee->label ), 0, 127 ) ),
					"quantity"		=> '1',
					"price"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $fee->amount ) : $fee->amount, 2, '.', '' ),
					"currency"		=> esc_attr( strtoupper( $paypal_currency ) )
				);
				$items[] = $item;
			}
		}

		// Build Transaction Data
		$transaction_data = (object) array( 
			"purchase_units" => array(
				(object) array(
					"reference_id"			=> 'WPEASYCART_ORDER_'.rand(1000000,9999999999),
					"payment_linked_group"	=> 1,
					"amount"				=> (object) array( 
						"total"				=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $cartpage->order_totals->get_converted_grand_total( ) : $cartpage->order_totals->grand_total, 2, '.', '' ), 
						"currency"			=> esc_attr( strtoupper( $paypal_currency ) ),
						"details"			=> (object) array(
							"subtotal"		=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $cartpage->order_totals->get_converted_sub_total( ) - $GLOBALS['currency']->convert_price( $cartpage->order_totals->discount_total ) + $GLOBALS['currency']->convert_price( $cartpage->order_totals->fee_total ) + $GLOBALS['currency']->convert_price( $cartpage->order_totals->tip_total ) : $cartpage->order_totals->sub_total - $cartpage->order_totals->discount_total + $cartpage->order_totals->fee_total + $cartpage->order_totals->tip_total, 2, '.', '' ),
							"tax"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $tax_total ) : $tax_total, 2, '.', '' ),
							"shipping"		=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $cartpage->order_totals->shipping_total ) : $cartpage->order_totals->shipping_total, 2, '.', '' ),
						)
					),
					"items"					=> $items
				)
			),
			"application_context"			=> (object) array(
				"shipping_preference"		=> "SET_PROVIDED_ADDRESS"
			),
			"redirect_urls"					=> (object) array(
				"return_url"				=> ( filter_var( $cartpage->cart_page . $cartpage->permalink_divider . 'ec_page=checkout_paypal_authorized', FILTER_VALIDATE_URL === false ) ) ? $cartpage->cart_page . $cartpage->permalink_divider . 'ec_page=checkout_paypal_authorized' : get_home_url( ),
				"cancel_url"				=> ( filter_var( $cartpage->cart_page, FILTER_VALIDATE_URL === false ) ) ? $cartpage->cart_page : get_home_url( )
			)
		);

		if( ( get_option( 'ec_option_paypal_use_sandbox' ) == '1' && get_option( 'ec_option_paypal_sandbox_merchant_id' ) != '' ) || ( get_option( 'ec_option_paypal_use_sandbox' ) == '0' && get_option( 'ec_option_paypal_production_merchant_id' ) != '' ) ){
			$transaction_data->purchase_units[0]->payee = (object) array( 
				"merchant_id" => esc_attr( ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' ) ),
			);
		} 

		if( ( $cartpage->order_totals->grand_total * $fee_rate ) >= 0.01 && !get_option( 'ec_option_paypal_use_sandbox' ) ){
			$transaction_data->purchase_units[0]->partner_fee_details = (object) array(
				"receiver"			=> (object) array(
					"email"			=> ( get_option( 'ec_option_paypal_use_sandbox' ) == '1' ) ? 'paypal-partner-facilitator@wpeasycart.com' : 'paypal-partner@wpeasycart.com',
					"payee_display_metadata"	=> (object) array(
						"brand_name"			=> 'WP EasyCart',
						"email"					=> ( get_option( 'ec_option_paypal_use_sandbox' ) == '1' ) ? 'paypal-partner-facilitator@wpeasycart.comm' : 'paypal-partner@wpeasycart.com'
					),
					"merchant_id"	=> ( get_option( 'ec_option_paypal_use_sandbox' ) == '1' ) ? '55LV5HAER3DNG' : 'U4HGH5W64EUBC'
				),
				"amount"			=> (object) array(
					"value"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $cartpage->order_totals->get_converted_grand_total( ) * $fee_rate / 100 : $cartpage->order_totals->grand_total * $fee_rate / 100, 2, '.', '' ),
					"currency"		=> esc_attr( strtoupper( $paypal_currency ) )
				)
			);
		}

		if( strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == 2 ){
			$shipping_address = (object) array(
				"line1"				=> esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ),
				"line2"				=> esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ),
				"city"				=> esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_city ),
				"country_code"		=> esc_attr( strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) ),
				"postal_code"		=> esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ),
				"recipient_name"	=> esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name . " " . $GLOBALS['ec_cart_data']->cart_data->shipping_last_name )
			);

			if( ( strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "AU" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "BR" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "CA" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "IN" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "IT" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "JP" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "MX" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "TH" || strtoupper( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) == "US" ) && strlen( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) == 2 )
				$shipping_address->state = esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_state );

			$transaction_data->purchase_units[0]->shipping_address = $shipping_address;
		}

		// Personal APP Only
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

			// Create URL and get Access Token
			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/checkout/orders/" : "https://api.paypal.com/v1/checkout/orders/";
			$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

			// Create Headers
			$headr = array( 
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
				'PayPal-Partner-Attribution-Id' => 'LevelFourDevelopment_SP_PPM'
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal API Create Order CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal API Create Order Response", print_r( $response, true ) );
			}

		// WP EasyCart APP		
		}else{ 

			$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $this->get_available_url( ) . "/paypal-v2/sandbox-create-order.php?merchantID=" . $merchant_id : $this->get_available_url( ) . "/paypal-v2/production-create-order.php?merchantID=" . $merchant_id;

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal API Create Order CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal API Create Order Response", print_r( $response, true ) );
			}

		}

		$json = json_decode( $response['body'] );

		if( !isset( $json->id ) )
			return "error";

		return $json->id;

		return $this->get_order_payment_id( $json->id );

	}

	private function get_item_onetime_price_adjustments( $cart_item ){

		$onetime_price_adjustments = array( );

		if( $cart_item->use_advanced_optionset ){

			$first = true;
			foreach( $cart_item->advanced_options as $advanced_option_set ){

				if( $advanced_option_set->option_type == "grid" ){ 

					if( $advanced_option_set->optionitem_price_onetime < 0 ){ 
						$onetime_price_adjustments[] = array(
							'name'		=> $advanced_option_set->optionitem_name . ': ' . $advanced_option_set->optionitem_value . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
							'price' 	=> ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $advanced_option_set->optionitem_price_onetime ) : $advanced_option_set->optionitem_price_onetime
						);
					}else if( $advanced_option_set->optionitem_price_onetime < 0 ){ 
						$onetime_price_adjustments[] = array(
							'name'		=> $advanced_option_set->optionitem_name . ': ' . $advanced_option_set->optionitem_value . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
							'price' 	=> ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $advanced_option_set->optionitem_price_onetime ) : $advanced_option_set->optionitem_price_onetime
						);
					}

				}else{
					if( $advanced_option_set->optionitem_price_onetime > 0 ){
						$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
						$onetime_price_adjustments[] = array(
							'name'		=> $advanced_option_set->option_label . ': ' . htmlspecialchars( $advanced_option_set->optionitem_value, ENT_QUOTES ) . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
							'price' 	=> ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $advanced_option_set->optionitem_price_onetime ) : $advanced_option_set->optionitem_price_onetime
						);
					}else if( $advanced_option_set->optionitem_price_onetime < 0 ){
						$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
						$onetime_price_adjustments[] = array(
							'name'		=> $advanced_option_set->option_label . ': ' . htmlspecialchars( $advanced_option_set->optionitem_value, ENT_QUOTES ) . ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')',
							'price' 	=> ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $advanced_option_set->optionitem_price_onetime ) : $advanced_option_set->optionitem_price_onetime
						);
					}
				}
				$first = false;
			}
		}

		return $onetime_price_adjustments;
	}

	private function build_item_description( $cart_item ){
		$description = '';
		if( $cart_item->optionitem1_name ){ 
			$description .= $cart_item->optionitem1_name;
			if( $cart_item->optionitem1_price > 0 ){ 
				$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem1_price ) . ' )';
			}else if( $cart_item->optionitem1_price < 0 ){
				$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem1_price ) . ' )';
			}
		}

		if( $cart_item->optionitem2_name ){ 
			$description .= ', ' . $cart_item->optionitem2_name;
			if( $cart_item->optionitem2_price > 0 ){ 
				$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem2_price ) . ' )';
			}else if( $cart_item->optionitem2_price < 0 ){
				$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem2_price ) . ' )';
			}
		}

		if( $cart_item->optionitem3_name ){ 
			$description .= ', ' . $cart_item->optionitem3_name;
			if( $cart_item->optionitem3_price > 0 ){ 
				$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem3_price ) . ' )';
			}else if( $cart_item->optionitem3_price < 0 ){
				$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem3_price ) . ' )';
			}
		}

		if( $cart_item->optionitem4_name ){ 
			$description .= ', ' . $cart_item->optionitem4_name;
			if( $cart_item->optionitem4_price > 0 ){ 
				$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem4_price ) . ' )';
			}else if( $cart_item->optionitem4_price < 0 ){
				$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem4_price ) . ' )';
			}
		}

		if( $cart_item->optionitem5_name ){ 
			$description .= ', ' . $cart_item->optionitem5_name;
			if( $cart_item->optionitem5_price > 0 ){ 
				$description .= '( +' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem5_price ) . ' )';
			}else if( $cart_item->optionitem5_price < 0 ){
				$description .= '( ' . $GLOBALS['currency']->get_currency_display( $cart_item->optionitem5_price ) . ' )';
			}
		}

		if( $cart_item->use_advanced_optionset ){

			$first = true;
			foreach( $cart_item->advanced_options as $advanced_option_set ){

				if( !$first )
					$description .= ', ';

				if( $advanced_option_set->option_type == "grid" ){ 

					$description .= $advanced_option_set->optionitem_name . ': ' . $advanced_option_set->optionitem_value;
					if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
					} else if( $advanced_option_set->optionitem_price > 0 ){ 
						$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';

					}else if( $advanced_option_set->optionitem_price < 0 ){ 
						$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';

					}else if( $advanced_option_set->optionitem_price_onetime > 0 ){ 
						$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';

					}else if( $advanced_option_set->optionitem_price_onetime < 0 ){ 
						$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')'; 

					}else if( $advanced_option_set->optionitem_price_override > -1 ){ 
						$description .= ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) . ')'; 

					}

				}else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){
					$description .= $advanced_option_set->option_label . ': ';
					$dimensions = json_decode( $advanced_option_set->optionitem_value );
					if( count( $dimensions ) == 2 ){ 
						$description .= $dimensions[0]; 
						if( !get_option( 'ec_option_enable_metric_unit_display' ) ){
							$description .= "\"";
						}
						$description .= " x " . $dimensions[1];
						if( !get_option( 'ec_option_enable_metric_unit_display' ) ){
							$description .= "\"";
						}
					}else if( count( $dimensions ) == 4 ){
						$description .= $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] . "\"";
					}

				}else{
					$description .= $advanced_option_set->option_label . ': ' . $advanced_option_set->optionitem_value;
					if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
						echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
					} else if( $advanced_option_set->optionitem_price > 0 ){
						$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
					}else if( $advanced_option_set->optionitem_price < 0 ){
						$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
					}else if( $advanced_option_set->optionitem_price_onetime > 0 ){
						$description .= ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
					}else if( $advanced_option_set->optionitem_price_onetime < 0 ){
						$description .= ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
					}else if( $advanced_option_set->optionitem_price_override > -1 ){
						$description .= ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) . ')';
					}
				}
				$first = false;
			}
		}
		return $description;
	}

	public function get_order_status( $paypal_order_id ){
		// Do a Token Check First
		$this->handle_token( );

		// Include the DB
		global $wpdb;
		$db = new ec_db( );

		// Make Sure Order ID is Uppercase
		$paypal_order_id = strtoupper( $paypal_order_id );

		// Personal APP Only
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/checkout/orders/" . $paypal_order_id : "https://api.paypal.com/v1/checkout/orders/" . $paypal_order_id;
			$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

			$headr = array( 
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'GET',
					'headers' => $headr,
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal Verify Order CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal Verify Order Response", print_r( $response, true ) );
			}

		// WP EasyCart APP
		}else{

			$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $this->get_available_url( ) . "/paypal-v2/sandbox-order-verify.php?paypalOrderID=" . $paypal_order_id . "&merchantID=" . $merchant_id : $this->get_available_url( ) . "/paypal-v2/production-order-verify.php?paypalOrderID=" . $paypal_order_id . "&merchantID=" . $merchant_id;

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'GET',
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal Verify Order CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal Verify Order Response", print_r( $response, true ) );
			}

		}

		return json_decode( $response['body'] );
	}

	public function order_pay( $paypal_order_id ){
		// Do a Token Check First
		$this->handle_token( );

		// Include the DB
		global $wpdb;
		$db = new ec_db( );

		// Make Sure Order ID is Uppercase
		$paypal_order_id = strtoupper( $paypal_order_id );

		// Personal APP Only
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/checkout/orders/" . $paypal_order_id . '/pay' : "https://api.paypal.com/v1/checkout/orders/" . $paypal_order_id  . '/pay';
			$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

			$headr = array( 
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
				'PayPal-Partner-Attribution-Id' => 'LevelFourDevelopment_SP_PPM'
			);

			$transaction_data = (object) array( 
				"disbursement_mode" => "INSTANT"
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal API Order Pay CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal API Order Pay Response", print_r( $response, true ) );
			}

		// WP EasyCart APP
		}else{

			$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $this->get_available_url( ) . "/paypal-v2/sandbox-order-submit.php?paypalOrderID=" . $paypal_order_id . "&merchantID=" . $merchant_id : $this->get_available_url( ) . "/paypal-v2/production-order-submit.php?paypalOrderID=" . $paypal_order_id . "&merchantID=" . $merchant_id;

			$transaction_data = (object) array( 
				"disbursement_mode" => "INSTANT"
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal API Order Pay CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal API Order Pay Response", print_r( $response, true ) );
			}
		}

		$json = json_decode( $response['body'] );

		if( isset( $json->order_id ) )
			return true;

		return false;
	}

	public function execute_payment( $order_id, $cart, $order_totals, $tax ){

		// Include the DB
		global $wpdb;
		$db = new ec_db( );

		$paypal_currency = get_option( 'ec_option_paypal_currency_code' );
		if( get_option( 'ec_option_paypal_use_selected_currency' ) && isset( $_COOKIE['ec_convert_to'] ) ){
			$paypal_currency = substr( preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_convert_to'] ) ) ), 0, 3 );
		}

		$tax = new ec_tax( 0.00, 0.00, 0.00, $this->order->billing_state, $this->order->billing_country );
		$tax_total = number_format( $order_totals->tax_total + $order_totals->duty_total + $order_totals->gst_total + $order_totals->pst_total + $order_totals->hst_total, 2 );
		if( !$tax->vat_included )
			$tax_total = number_format( $tax_total + $order_totals->vat_total, 2 );

		$transaction_data = (object) array( 
			"payer_id" 					=> preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payer_id'] ) ),
			"transactions"				=> array(
				(object) array(
					"amount"			=> (object) array(
						"total"			=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $order_totals->get_converted_grand_total( ) : $order_totals->grand_total, 2, '.', '' ),
						"currency"		=> strtoupper( $paypal_currency ),
						"details"		=> (object) array(
							"subtotal"	=> number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $order_totals->get_converted_sub_total( ) - $GLOBALS['currency']->convert_price( $order_totals->discount_total ) + $GLOBALS['currency']->convert_price( $order_totals->fee_total ) + $GLOBALS['currency']->convert_price( $order_totals->tip_total ) : $order_totals->sub_total - $order_totals->discount_total + $order_totals->fee_total + $order_totals->tip_total, 2, '.', '' ),
						)
					),
					"custom"			=> $order_id,
					"invoice_number"	=> $order_id
				)
			)
		);
		if( $tax_total > 0 ){
			$transaction_data->transactions[0]->amount->details->tax = number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $tax_total ) : $tax_total, 2, '.', '' );
		}
		if( $order_totals->shipping_total > 0 ){
			$transaction_data->transactions[0]->amount->details->shipping = number_format( ( get_option( 'ec_option_paypal_use_selected_currency' ) ) ? $GLOBALS['currency']->convert_price( $order_totals->shipping_total ) : $order_totals->shipping_total, 2, '.', '' );
		}

		// Personal APP Only
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/payments/payment/" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payment_id'] ) ) . '/execute' : "https://api.paypal.com/v1/payments/payment/" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payment_id'] ) ) . '/execute';
			$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

			$headr = array( 
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
				'PayPal-Partner-Attribution-Id' => 'LevelFourDevelopment_SP_PPM'
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal Express Execute CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal Express Execute Response", print_r( $response, true ) );
			}

		// WP EasyCart APP	
		}else{

			$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
			$webhook_url = get_site_url() . '?wpeasycarthook=paypal-webhook';
			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $this->get_available_url( ) . "/paypal-v2/sandbox-payment-submit.php?merchantID=" . $merchant_id . "&webhookURL=" . $webhook_url . "&paypalPaymentID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payment_id'] ) ) : $this->get_available_url( ) . "/paypal-v2/production-payment-submit.php?merchantID=" . $merchant_id . "&webhookURL=" . $webhook_url . "&paypalPaymentID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payment_id'] ) );

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal Express Execute CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal Express Execute Response", print_r( $response, true ) );
			}

		}

		$json = json_decode( $response['body'] );
		if( !isset( $json->state ) )
			return false;

		$state = $json->state;
		$transactions = $json->transactions;
		if( count( $transactions ) > 0 ){
			if( isset( $transactions[0]->related_resources ) ){
				if( count( $transactions[0]->related_resources ) > 0 ){
					if( isset( $transactions[0]->related_resources[0]->sale ) ){
						if( isset( $transactions[0]->related_resources[0]->sale->state ) ){
							if( $transactions[0]->related_resources[0]->sale->state == 'pending' ){
								$state = 'pending';
							}
						}
					}
				}
			}
		}

		// Redirect if Approved or Denied
		if( $state == 'approved' || $state == 'pending' ){
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET gateway_transaction_id = %s, order_gateway = 'paypal-express' WHERE order_id = %d", $json->transactions[0]->related_resources[0]->sale->id, $order_id ) );
			return $state;

		}else{
			return false;
		}
	}

	public function execute_order( $order_id, $cart, $order_totals, $tax ){
		// Do a Token Check First
		$this->handle_token( );

		// Include the DB
		global $wpdb;
		$db = new ec_db( );

		// Get PayPal Order ID
		$paypal_order_id = ( isset( $_POST['paypal_order_id'] ) ) ? preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_order_id'] ) ) : preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['orderID'] ) );

		// Make Sure Order ID is Uppercase
		$paypal_order_id = strtoupper( $paypal_order_id );

		// Call /pay and return true if no errors.
		$result = $this->order_pay( $paypal_order_id );

		if( $result ){

			// WP EasyCart APP Only
			if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) != '' ) || 
				( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) != '' ) ){

				// Send data to EasyCart to handle Webhooks
				$is_sandbox = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? 1 : 0;
				$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
				$webhook_url = get_site_url() . '?wpeasycarthook=paypal-webhook';

				$url = $this->get_available_url( ) . "/paypal-v2/webhook-add.php?orderID=".$paypal_order_id."&is_sandbox=".$is_sandbox."&merchantID=".$merchant_id."&webhookURL=".$webhook_url;

				$request = new WP_Http;
				$response = $request->request( 
					$url, 
					array( 
						'method' => 'GET',
						'timeout' => 30
					)
				);
				if( is_wp_error( $response ) ){
					$db->insert_response( $order_id, 1, "PayPal API Webhook V2 Add Order CURL ERROR", $response->get_error_message( ) );
					$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
				}else{
					$db->insert_response( $order_id, 0, "PayPal API Webhook V2 Add Order Response", print_r( $response, true ) );
				}
			}

			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET gateway_transaction_id = %s WHERE order_id = %d", $paypal_order_id, $order_id ) );

		}

		return $result;
	}

	public function refund_express_charge( $order_id, $key, $amount ){

		// Do a Token Check First
		$this->handle_token( );

		// Include the DB
		global $wpdb;
		$db = new ec_db( );

		// Make Sure Order ID is Uppercase
		$key = strtoupper( $key );

		// WP EasyCart APP Only
		if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) != '' ) || 
			( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) != '' ) ){

			$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
			$webhook_url = get_site_url() . '?wpeasycarthook=paypal-webhook';
			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $this->get_available_url( ) . "/paypal-v2/sandbox-order-refund.php?merchantID=".$merchant_id."&webhookURL=".$webhook_url."&orderID=" . $key : $this->get_available_url( ) . "/paypal-v2/production-order-refund.php?merchantID=".$merchant_id."&webhookURL=".$webhook_url."&orderID=" . $key;

			$paypal_currency = get_option( 'ec_option_paypal_currency_code' );
			$transaction_data = (object) array( 
				"amount" 					=> (object) array(
					"total"					=> $amount,
					"currency"				=> strtoupper( $paypal_currency )
				),
				"invoice_number"			=> $order_id
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal Express Refund CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal Express Refund Response", print_r( $response, true ) );
			}

		}else{

			$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/payments/sale/" . $key . '/refund' : "https://api.paypal.com/v1/payments/sale/" . $key . '/refund';
			$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );
			$paypal_currency = get_option( 'ec_option_paypal_currency_code' );

			$headr = array( 
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer ' . $access_token,
				'PayPal-Partner-Attribution-Id' => 'LevelFourDevelopment_SP_PPM'
			);

			$transaction_data = (object) array( 
				"amount" 					=> (object) array(
					"total"					=> $amount,
					"currency"				=> strtoupper( $paypal_currency )
				),
				"invoice_number"			=> $order_id
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => json_encode( $transaction_data ),
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( $order_id, 1, "PayPal Express Refund CURL ERROR", $response->get_error_message( ) );
				$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
			}else{
				$db->insert_response( $order_id, 0, "PayPal Express Refund Response", print_r( $response, true ) );
			}

		}

		$json = json_decode( $response['body'] );
		$state = $json->state;

		// Redirect if Approved or Denied
		if( $state == 'completed' ){
			return true;
		}else{
			return false;
		}

	}

	public function handle_token( ){

		// Authorize Sandbox Personal App
		if( get_option( 'ec_option_paypal_use_sandbox' ) == '1' && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' && 
				( !get_option( 'ec_option_paypal_sandbox_access_token_expires' ) || get_option( 'ec_option_paypal_sandbox_access_token_expires' ) < time( ) ) ){

			$db = new ec_db( );
			$url = "https://api.sandbox.paypal.com/v1/oauth2/token";
			$headr = array( );
			$headr[] = 'Accept: application/json';
			$headr[] = 'Accept-Language: en_US';

			$headr = array( 
				'Accept' => 'application/json',
				'Accept-Language' => 'en_US',
				'Authorization' => 'Basic ' . base64_encode( get_option( 'ec_option_paypal_sandbox_app_id' ) . ":" . get_option( 'ec_option_paypal_sandbox_secret' ) )
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => "grant_type=client_credentials",
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( 0, 1, "PayPal Express Token CURL ERROR", $response );
				$response = (object) array( "error" => $response );
			}else{
				$db->insert_response( 0, 0, "PayPal Express Token Response", print_r( $response, true ) );
			}

			$json = json_decode( $response['body'] );
			update_option( 'ec_option_paypal_sandbox_access_token', $json->access_token );
			update_option( 'ec_option_paypal_sandbox_access_token_expires', time( ) + $json->expires_in - 300 );

		// Authorize Production	Personal App
		}else if( get_option( 'ec_option_paypal_use_sandbox' ) != '1' && get_option( 'ec_option_paypal_production_merchant_id' ) == '' && 
				( !get_option( 'ec_option_paypal_production_access_token_expires' ) || get_option( 'ec_option_paypal_production_access_token_expires' ) < time( ) ) ){

			$db = new ec_db( );
			$url = "https://api.paypal.com/v1/oauth2/token";
			$headr = array( );
			$headr[] = 'Accept: application/json';
			$headr[] = 'Accept-Language: en_US';

			$headr = array( 
				'Accept' => 'application/json',
				'Accept-Language' => 'en_US',
				'Authorization' => 'Basic ' . base64_encode( get_option( 'ec_option_paypal_production_app_id' ) . ":" . get_option( 'ec_option_paypal_production_secret' ) )
			);

			$request = new WP_Http;
			$response = $request->request( 
				$url, 
				array( 
					'method' => 'POST',
					'headers' => $headr,
					'body' => "grant_type=client_credentials",
					'timeout' => 30
				)
			);
			if( is_wp_error( $response ) ){
				$db->insert_response( 0, 1, "PayPal Express Token CURL ERROR", $response );
				$response = (object) array( "error" => $response );
			}else{
				$db->insert_response( 0, 0, "PayPal Express Token Response", print_r( $response, true ) );
			}

			$json = json_decode( $response['body'] );
			update_option( 'ec_option_paypal_production_access_token', $json->access_token );
			update_option( 'ec_option_paypal_production_access_token_expires', time( ) + $json->expires_in - 300 );

		}
		// No token needed for EasyCart App

	}

}

add_filter( 'wp_easycart_cart_update_response', 'wp_easycart_paypal_express_filter', 10, 1 );
function wp_easycart_paypal_express_filter( $cart_response ){
	if( get_option( 'ec_option_paypal_enable_pay_now' ) || get_option( 'ec_option_paypal_enable_credit' ) ){
		$cartpage = new ec_cartpage( );
		ob_start( );
		$cartpage->print_paypal_express_button_code();
		$content = ob_get_clean();
		$cart_response['paypal_express_button'] = $content;
	}
	return $cart_response;
}

add_action( 'init', 'wpeasycart_complete_paypal' );
function wpeasycart_complete_paypal( ){
	if( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == 'checkout_paypal_complete' && isset( $_GET['orderID'] ) && isset( $_GET['payerID'] ) && isset( $_GET['paymentID'] ) && isset( $_GET['paymentToken'] ) ){

		// Handle Token First
		$paypal = new ec_paypal( );
		$paypal->handle_token( );

		// Include the DB
		$db = new ec_db( );

		// Setup Linking Info
		$cart_page_id = get_option( 'ec_option_cartpage' );
		if( function_exists( 'icl_object_id' ) ){
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cart_page = get_permalink( $cart_page_id );
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$cart_page = $https_class->makeUrlHttps( $cart_page );
		}

		if( substr_count( $cart_page, '?' ) )						$permalink_divider = "&";
		else														$permalink_divider = "?";

		// Verify the Order is Legit
		if( get_option( 'ec_option_paypal_use_sandbox' ) == '1' ){
			$url = "https://api.sandbox.paypal.com/v1/payments/payment/" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) );
		}else{
			$url = "https://api.paypal.com/v1/payments/payment/" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) );
		}

		if( get_option( 'ec_option_paypal_use_sandbox' ) ){
			$access_token = get_option( 'ec_option_paypal_sandbox_access_token' );
		}else{
			$access_token = get_option( 'ec_option_paypal_production_access_token' );
		}

		$headr = array( 
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $access_token
		);

		$request = new WP_Http;
		$response = $request->request( 
			$url, 
			array( 
				'method' => 'GET',
				'headers' => $headr,
				'timeout' => 30
			)
		);
		if( is_wp_error( $response ) ){
			$db->insert_response( $order_id, 1, "PayPal Express CURL ERROR", $response->get_error_message( ) );
			$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
		}else{
			$db->insert_response( $order_id, 0, "PayPal Express Response", print_r( $response, true ) );
		}

		$json = json_decode( $response['body'] );
		$state = $json->state;

		// Redirect if Approved or Denied
		if( $state == 'approved' ){
			$cartpage = new ec_cartpage( );
			$order_id = $cartpage->submit_paypal_order( );
		}

		// Redirect either way. Third party pending when not able to verify payment.
		wp_redirect( $cart_page . $permalink_divider . "ec_page=checkout_success&order_id=" . $order_id );
		die( );
	}
}

add_action( 'wp_ajax_wp_easycart_ajax_init_paypal_express', 'wp_easycart_ajax_init_paypal_express' );
add_action( 'wp_ajax_nopriv_wp_easycart_ajax_init_paypal_express', 'wp_easycart_ajax_init_paypal_express' );
function wp_easycart_ajax_init_paypal_express( ){
	$paypal = new ec_paypal( );
	echo esc_attr( $paypal->create_order( ) );
	die( );
}

add_action( 'init', 'wpeasycart_paypal_express_authorized' );
function wpeasycart_paypal_express_authorized( ){
	if( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == 'checkout_paypal_authorized' && isset( $_GET['orderID'] ) && isset( $_GET['payerID'] ) && isset( $_GET['paymentID'] ) && isset( $_GET['paymentToken'] ) ){

		// Handle Token First
		$paypal = new ec_paypal( );
		$paypal->handle_token( );

		// Include the DB
		$db = new ec_db( );

		// Setup Linking Info
		$cart_page_id = get_option( 'ec_option_cartpage' );
		if( function_exists( 'icl_object_id' ) ){
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cart_page = get_permalink( $cart_page_id );
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$cart_page = $https_class->makeUrlHttps( $cart_page );
		}

		if( substr_count( $cart_page, '?' ) )						$permalink_divider = "&";
		else														$permalink_divider = "?";

		// Verify the payment is Legit
		if( apply_filters( 'wp_easycart_allow_paypal_express', false ) && isset( $_GET['paymentID'] ) && $_GET['paymentID'] != 'undefined' ){

			// PERSONAL APP ONLY
			if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
				( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

				$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/payments/payment/" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) ) : "https://api.paypal.com/v1/payments/payment/" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) );
				$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

				$headr = array( 
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $access_token
				);

				$request = new WP_Http;
				$response = $request->request( 
					$url, 
					array( 
						'method' => 'GET',
						'headers' => $headr,
						'timeout' => 30
					)
				);
				if( is_wp_error( $response ) ){
					$db->insert_response( $order_id, 1, "PayPal Express Authorized CURL ERROR", $response->get_error_message( ) );
					$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
				}else{
					$db->insert_response( $order_id, 0, "PayPal Express Authorized Response", print_r( $response, true ) );
				}

			// WP EasyCart APP	
			}else{

				$merchant_id = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_merchant_id' ) : get_option( 'ec_option_paypal_production_merchant_id' );
				$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $paypal->get_available_url( ) . "/paypal-v2/sandbox-payment-verify.php?paypalPaymentID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) ) . "&merchantID=" . $merchant_id : $paypal->get_available_url( ) . "/paypal-v2/production-payment-verify.php?paypalPaymentID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) ) . "&merchantID=" . $merchant_id;

				$request = new WP_Http;
				$response = $request->request( 
					$url, 
					array( 
						'method' => 'GET',
						'timeout' => 30
					)
				);
				if( is_wp_error( $response ) ){
					$db->insert_response( $order_id, 1, "PayPal Express Payment Verify CURL ERROR", $response->get_error_message( ) );
					$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
				}else{
					$db->insert_response( $order_id, 0, "PayPal Express Payment Verify Response", print_r( $response, true ) );
				}
			}

			$json = json_decode( $response['body'] );
			$state = $json->state;

			// Redirect if Approved or Denied
			if( $state == 'created' ){
				$cartpage = new ec_cartpage( );
				$cartpage->update_authorized_paypal_order( $json );
			}

			// Redirect either way. Third party pending when not able to verify payment.
			wp_redirect( $cart_page . $permalink_divider . "ec_page=checkout_payment&PID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['paymentID'] ) ) . '&PYID=' . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['payerID'] ) ) . '&PMETH=' . preg_replace( "/[^A-Za-z0-9\_]/", '', $json->payer->payment_method ) );

		}else{
			// Get PayPal Order ID
			$paypal_order_id = preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['orderID'] ) );

			// Verify the Order is Legit
			$json = $paypal->get_order_status( $paypal_order_id );

			// If error getting payment id (PayPal Bug) from PRO users, we can try to auto-correct the issue.
			if( isset( $json->name ) && $json->name == "PERMISSION_DENIED" ){

				// Verify the payment/order is Legit

				// PERSONAL APP ONLY
				if( ( get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_sandbox_merchant_id' ) == '' ) || 
					( !get_option( 'ec_option_paypal_use_sandbox' ) && get_option( 'ec_option_paypal_production_merchant_id' ) == '' ) ){

					$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? "https://api.sandbox.paypal.com/v1/checkout/orders/" . $paypal_order_id : "https://api.paypal.com/v1/checkout/orders/" . $paypal_order_id;
					$access_token = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? get_option( 'ec_option_paypal_sandbox_access_token' ) : get_option( 'ec_option_paypal_production_access_token' );

					$headr = array( 
						'Content-Type' => 'application/json',
						'Authorization' => 'Bearer ' . $access_token
					);

					$request = new WP_Http;
					$response = $request->request( 
						$url, 
						array( 
							'method' => 'GET',
							'headers' => $headr,
							'timeout' => 30
						)
					);
					if( is_wp_error( $response ) ){
						$db->insert_response( $order_id, 1, "PayPal Express Order Payment Verify CURL ERROR", $response->get_error_message( ) );
						$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
					}else{
						$db->insert_response( $order_id, 0, "PayPal Express Order Payment Verify Response", print_r( $response, true ) );
					}

				// WP EasyCart APP
				}else{
					$url = ( get_option( 'ec_option_paypal_use_sandbox' ) ) ? $paypal->get_available_url( ) . "/paypal-v2/sandbox-payment-order-verify.php?paypalOrderID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['orderID'] ) ) : $paypal->get_available_url( ) . "/paypal-v2/production-payment-order-verify.php?paypalOrderID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['orderID'] ) );

					$request = new WP_Http;
					$response = $request->request( 
						$url, 
						array( 
							'method' => 'GET',
							'timeout' => 30
						)
					);
					if( is_wp_error( $response ) ){
						$db->insert_response( $order_id, 1, "PayPal Express Payment/Order Verify CURL ERROR", $response->get_error_message( ) );
						$response = json_encode( (object) array( "error" => $response->get_error_message( ) ) );
					}else{
						$db->insert_response( $order_id, 0, "PayPal Express Payment/Order Verify Response", print_r( $response, true ) );
					}
				}

				$json = json_decode( $response['body'] );
				$state = $json->state;

			}

			// Redirect if Approved or Denied
			if( $json->status == 'APPROVED' ){
				$cartpage = new ec_cartpage( );
				$cartpage->update_authorized_paypal_order( $json );
			}

			// Redirect either way. Third party pending when not able to verify payment.
			wp_redirect( $cart_page . $permalink_divider . "ec_page=checkout_payment&PID=" . preg_replace( "/[^A-Za-z0-9\-]/", '', $json->payment_details->payment_id ) . '&PYID=' . preg_replace( "/[^A-Za-z0-9\-]/", '', $json->payer_info->payer_id ) . '&PMETH=' . preg_replace( "/[^A-Za-z0-9\_]/", '', $json->payer->payment_method ) . '&OID=' . $paypal_order_id );

		}
		die( );
	}
}

add_action( 'init', 'wpeasycart_paypal_express_partner_authorized' );
function wpeasycart_paypal_express_partner_authorized( ){
	if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && is_admin() && isset( $_GET['wpeasycart_paypal_onboard'] ) && $_GET['wpeasycart_paypal_onboard'] == 'sandbox' && isset( $_GET['merchantIdInPayPal'] ) ) {
		if ( false === wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['wp_easycart_nonce'] ) ), 'wp-easycart-paypal' ) ) {
			return false;
		}
		update_option( 'ec_option_payment_third_party', 'paypal' );
		update_option( 'ec_option_paypal_use_sandbox', 1 );
		update_option( 'ec_option_paypal_enable_pay_now', 1 );
		update_option( 'ec_option_paypal_sandbox_app_id', '' );
		update_option( 'ec_option_paypal_sandbox_secret', '' );
		update_option( 'ec_option_paypal_sandbox_merchant_id', preg_replace( "/[^A-Za-z0-9]/", '', sanitize_text_field( $_GET['merchantIdInPayPal'] ) ) );
		$paypal = new ec_paypal( );
		$paypal->create_webhook( );
		do_action( 'wpeasycart_third_party_payment_updated', get_option( 'ec_option_payment_third_party' ) );
		wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment' );
		die( );

	} else if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_settings' ) ) && is_admin() && isset( $_GET['wpeasycart_paypal_onboard'] ) && $_GET['wpeasycart_paypal_onboard'] == 'production' && isset( $_GET['merchantIdInPayPal'] ) ) {
		if ( false === wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['wp_easycart_nonce'] ) ), 'wp-easycart-paypal' ) ) {
			return false;
		}
		update_option( 'ec_option_payment_third_party', 'paypal' );
		update_option( 'ec_option_paypal_use_sandbox', 0 );
		update_option( 'ec_option_paypal_enable_pay_now', 1 );
		update_option( 'ec_option_paypal_production_app_id', '' );
		update_option( 'ec_option_paypal_production_secret', '' );
		update_option( 'ec_option_paypal_production_merchant_id', preg_replace( "/[^A-Za-z0-9]/", '', sanitize_text_field( $_GET['merchantIdInPayPal'] ) ) );
		$paypal = new ec_paypal( );
		$paypal->create_webhook( );
		do_action( 'wpeasycart_third_party_payment_updated', get_option( 'ec_option_payment_third_party' ) );
		if( isset( $_GET['is_wizard'] ) && $_GET['is_wizard'] == 'true' ){
			wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3' );
		}else{
			wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment' );
		}
		die( );

	}
}

add_action( 'wp_head', 'wp_easycart_init_paypal_marketing' );
function wp_easycart_init_paypal_marketing( ){
	if( get_option( 'ec_option_paypal_use_sandbox' ) == '1' && get_option( 'ec_option_paypal_marketing_solution_cid_sandbox' ) != '' ){
		echo '<!-- PayPal BEGIN -->';
		echo '<script>';
		echo ";(function(a,t,o,m,s){a[m]=a[m]||[];a[m].push({t:new Date().getTime(),event:'snippetRun'});var f=t.getElementsByTagName(o)[0],e=t.createElement(o),d=m!=='paypalDDL'?'&m='+m:'';e.async=!0;e.src='https://www.sandbox.paypal.com/tagmanager/pptm.js?id='+s+d;f.parentNode.insertBefore(e,f);})(window,document,'script','paypalDDL','" . esc_attr( get_option( 'ec_option_paypal_marketing_solution_cid_sandbox' ) ) . "');";
		echo '</script>';
		echo '<!-- PayPal END -->';
	}else if( get_option( 'ec_option_paypal_use_sandbox' ) == '0' && get_option( 'ec_option_paypal_marketing_solution_cid_production' ) != '' ){
		echo '<!-- PayPal BEGIN -->';
		echo '<script>';
		echo ";(function(a,t,o,m,s){a[m]=a[m]||[];a[m].push({t:new Date().getTime(),event:'snippetRun'});var f=t.getElementsByTagName(o)[0],e=t.createElement(o),d=m!=='paypalDDL'?'&m='+m:'';e.async=!0;e.src='https://www.paypal.com/tagmanager/pptm.js?id='+s+d;f.parentNode.insertBefore(e,f);})(window,document,'script','paypalDDL','" . esc_attr( get_option( 'ec_option_paypal_marketing_solution_cid_production' ) ) . "');";
		echo '</script>';
		echo '<!-- PayPal END -->';
	}
}
