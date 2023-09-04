<?php
class ec_category{
	protected $mysqli;							// ec_db structure
	
	public $options;
	
	public $account_page;
	public $cart_page;
	public $store_page;
	public $permalink_divider;
	
	function __construct( $category ){
		
		$this->options = $category;
		
		$accountpageid = get_option( 'ec_option_accountpage' );
		$cartpageid = get_option( 'ec_option_cartpage' );
		$storepageid = get_option( 'ec_option_storepage' );
		
		if( function_exists( 'icl_object_id' ) ){
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
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
		
	}
	
	public function display_category( ){
	
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_category.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_category.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_category.php' );

	}
	
	public function get_image( ){
		
		$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/categories/" . $this->options->image;
		$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";
		
		if ( substr( $this->options->image, 0, 7 ) == 'http://' || substr( $this->options->image, 0, 8 ) == 'https://' ) {
			return $this->options->image;

		} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
			return plugins_url( "/wp-easycart-data/products/categories/" . $this->options->image, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( $test_src2 ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

		}
	
	}
	
	public function get_category_link( ){
		
		if( !get_option( 'ec_option_use_old_linking_style' ) && $this->options->post_id != "0" ){
			return get_permalink( $this->options->post_id );
		
		}else{
			return $this->store_page . $this->permalink_divider . "group_id=" . $this->options->category_id;
		}
		
	}
	
}
?>