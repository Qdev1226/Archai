<?php
// This is a payment gateway basic structure,
// child classes will be based on this class.

class ec_gateway{
	
	protected $mysqli;													// ec_db structure
	
	protected $cart;													// ec_cart structure
	protected $user;													// ec_user structure
	protected $shipping;												// ec_shipping structure
	protected $tax;														// ec_tax structure
	protected $discount;												// ec_discount structure
	protected $credit_card;												// ec_credit_card structure
	protected $order_totals;											// ec_order_totals structure
	protected $order_id;											    // INT
	
	protected $error_message;											// TEXT
	protected $is_success;												// BOOL
	
	public $held_for_review = false;									// BOOL
	public $is_3d_auth = false;											// If 3D Auth
	
	//3d auth values
	public $post_url = "";												// Used for 3D Auth
	public $post_id_input_name = "";									// Used for 3D Auth
	public $post_id = "";												// Used for 3D Auth
	public $post_message_input_name = "";								// Used for 3D Auth
	public $post_message = "";											// Used for 3D Auth
	public $post_return_url_input_name = "";							// Used for 3D Auth
	
	public $cart_page;													// VARCHAR
	public $permalink_divider;											// CHAR
	
	function __construct( ){ 
		$this->mysqli = new ec_db( );
		
		$cart_page_id = get_option('ec_option_cartpage');
		
		if( function_exists( 'icl_object_id' ) ){
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		$this->cart_page = get_permalink( $cart_page_id );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
		}
		
		if( substr_count( $this->cart_page, '?' ) )					$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";
	}
	
	/****************************************
	* INITIALIZATION FUNCTIONS
	*****************************************/
	
	public function initialize( $cart, $user, $shipping, $tax, $discount, $credit_card, $order_totals, $order_id ){
		
		$this->cart = $cart;
		$this->user = $user;
		$this->shipping = $shipping;
		$this->tax = $tax;
		$this->discount = $discount;
		$this->credit_card = $credit_card;
		$this->order_totals = $order_totals;
		$this->order_id = $order_id;
		
		$this->is_success = false;
		
	}
	
	/****************************************
	* WORKER FUNCTIONS
	*****************************************/
	
	public function process_credit_card( ){
		
		$gateway_url = $this->get_gateway_url( );
		$gateway_data = $this->get_gateway_data( );
		$gateway_headers = $this->get_gateway_headers( );
		$gateway_response = $this->get_gateway_response( $gateway_url, $gateway_data, $gateway_headers );
		if( !$gateway_response ){
			$this->mysqli->insert_response( $this->order_id, 0, "ec_gateway", "error in process_credit_card, could not get a response from the server." );
			return false;
		}else{
			$this->handle_gateway_response( $gateway_response );
			if( $this->is_success )
				return true;
			else{
				$this->mysqli->insert_response( $this->order_id, 0, "ec_gateway", "error in process_credit_card from processor: " . $this->error_message );
				return false;
			}
		}
	}
	
	/****************************************
	* RETURNING FUNCTIONS
	*****************************************/
	public function get_response_message( ){
		return $this->error_message;
	}
	
	/****************************************
	* GATEWAY SPECIFIC HELPER FUNCTIONS
	*****************************************/
	
	protected function get_gateway_data( ){
		// 1. Setup gateway specific variables
		// 2. If it uses an xml format, build it, if an array, build that.
		// 3. return the xml/array data.
		return false;
	}
	
	protected function get_gateway_url( ){
		return false;
	}
	
	protected function get_gateway_headers( ){
		// This is optional, needed for some gateways
		return "";	
	}
	
	protected function handle_gateway_response( $response ){
		// 1. Break apart response
		// 2. Set is_success variable
		// 3. If ERROR, set the error message
		// 3. Store response to DB
		return false;
	}
	
	/********************************************************************************
	* CONSTANT HELPER FUNCTIONS ( SOME GATEWAYS MAY NEED TO CHANGE THIS FUNCTION )
	*********************************************************************************/
	
	protected function get_gateway_response( $gateway_url, $gateway_data, $gateway_headers ){
		
		$request = new WP_Http;
		$response = $request->request( $gateway_url, array( 'method' => 'POST', 'body' => $gateway_data, 'headers' => $gateway_headers, 'timeout' => 30 ) );
		if( is_wp_error( $response ) ){
			$this->error_message = $response->get_error_message();
			$this->mysqli->insert_response( $this->order_id, 1, "Gateway Error", $this->error_message );
			return false;
		}else
			return $response;
			
	}

}

?>