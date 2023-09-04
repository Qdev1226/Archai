<?php
class ec_third_party{
	
	protected $mysqli;													// ec_db structure
	
	protected $order_id;												// INT
	protected $order;													// ec_orderdisplay structure
	protected $order_details = array();											// array of ec_orderdetail structure
	
	protected $store_page;												// VARCHAR
	protected $cart_page;												// VARCHAR
	protected $account_page;											// VARCHAR
	protected $permalink_divider;										// CHAR
	
	function __construct( ){
		$this->mysqli = new ec_db( );
	}
	
	/****************************************
	* INITIALIZATION FUNCTIONS
	*****************************************/
	
	public function initialize( $order_id  ){
		
		$this->order_id = $order_id;
		
		if( $GLOBALS['ec_cart_data']->cart_data->is_guest )
			$order_row = $this->mysqli->get_guest_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
		else
			$order_row = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );

		$store_page_id = get_option('ec_option_storepage');
		$this->store_page = get_permalink( $store_page_id );
		
		$cart_page_id = get_option('ec_option_cartpage');
		$this->cart_page = get_permalink( $cart_page_id );
		
		$account_page_id = get_option('ec_option_accountpage');
		$this->account_page = get_permalink( $account_page_id );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
		}
		
		if( substr_count( $this->cart_page, '?' ) )					$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";
		
		if( $order_row ){
			$this->order = new ec_orderdisplay( $order_row );
		
			if( $GLOBALS['ec_cart_data']->cart_data->guest_key != "" )
				$order_details = $this->mysqli->get_guest_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
			else
				$order_details = $this->mysqli->get_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
			
			if( $order_details ){
				foreach( $order_details as $order_detail )
					$this->order_details[] = new ec_orderdetail( $order_detail );
				
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/****************************************
	* CHANGING FUNCTIONS
	*****************************************/
	
	public function display_form_start( ){
		return false;
	}
	
}
?>