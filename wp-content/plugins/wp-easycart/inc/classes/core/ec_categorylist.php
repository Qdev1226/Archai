<?php

class ec_categorylist{
	private $mysqli;								// ec_db structure

	public $parent_id;
	public $categories = array();					// array of ec_product structures
	public $num_categories;							// INT
	
	public $cart_page;
	public $account_page;
	public $store_page;
	public $permalink_divider;
	
	function __construct( $group_id = "NOGROUP" ){
		
		$this->mysqli = new ec_db( );
		
		$this->parent_id = 0;
		if( $group_id != "NOGROUP" )
			$this->parent_id = $group_id;
		else if( isset( $_GET['group_id'] ) )
			$this->parent_id = (int) $_GET['group_id'];
		
		$accountpageid = get_option( 'ec_option_accountpage' );
		$cartpageid = get_option( 'ec_option_cartpage' );
		$storepageid = get_option( 'ec_option_storepage' );
		
		if( function_exists( 'icl_object_id' ) ){
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		if( function_exists( 'icl_object_id' ) ){
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		if( function_exists( 'icl_object_id' ) ){
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		$this->account_page = get_permalink( $accountpageid );
		$this->cart_page = get_permalink( $cartpageid );
		$this->store_page = get_permalink( $storepageid );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}
		
		if( substr_count( $this->store_page, '?' ) )						$this->permalink_divider = "&";
		else																$this->permalink_divider = "?";
		
		$this->categories = $this->mysqli->get_category_list_items( $this->parent_id );
		$this->num_categories = count( $this->categories );
		
	}
	
	public function display_category_list( ){
		
		for( $cat_index = 0; $cat_index < count( $this->categories ); $cat_index++ ){
			
			$category = new ec_category( $this->categories[$cat_index] );
			$category->display_category( );
			
		}
		
	}
	
	private function get_current_page_url( ){
		$uri_parts = explode( '?', sanitize_text_field( $_SERVER['REQUEST_URI'] ), 2 );
		$pageURL = 'http';
		if( isset( $_SERVER["HTTPS"] ) && sanitize_text_field( $_SERVER["HTTPS"] ) == "on" ){
			$pageURL .= "s";
		}
		$pageURL .= "://";
		$pageURL .= sanitize_text_field( $_SERVER['HTTP_HOST'] ) . $uri_parts[0];
		
		if( isset( $_GET['page_id'] ) )
			$pageURL .= "?page_id=" . get_the_ID( );
		
		return $pageURL;
	}
	
	public function display_items_per_page( $divider ){
		
		echo esc_attr( $this->filter->get_items_per_page( $divider ) );
	
	}
	
	public function display_current_page( ){
		
		echo esc_attr( $this->paging->current_page );
		
	}
	
	public function display_total_pages( ){
		
		echo esc_attr( $this->paging->total_pages );
		
	}
	
	public function display_product_paging( $divider ){
		
		echo esc_attr( $this->paging->display_paging_links( $divider, $this->filter->get_link_string( 0 ) ) );
	}
	
}

?>