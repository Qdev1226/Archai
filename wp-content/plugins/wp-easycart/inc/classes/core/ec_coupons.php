<?php

class ec_coupons{
	
	public $coupons;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		global $wpdb;
		$coupons = wp_cache_get( 'wpeasycart-coupons' );
		if( !$coupons ){
			$coupons = $wpdb->get_results( "SELECT ec_promocode.*, IF( ec_promocode.expiration_date < NOW( ), 1, 0 ) as coupon_expired FROM ec_promocode" );
			if( count( $coupons ) == 0 )
				$coupons = "EMPTY";
			wp_cache_set( 'wpeasycart-coupons', $coupons );
		}
		if( $coupons == "EMPTY" )
			$coupons = array( );
		$this->coupons = $coupons;
	}
	
	public function redeem_coupon_code( $promocode_id ){
		
		for( $i=0; $i<count( $this->coupons ); $i++ ){
			
			if( strtolower( $this->coupons[$i]->promocode_id ) == strtolower( $promocode_id ) ){
				// Validate Subscription Coupon
				if( isset( $_GET['subscription'] ) ){
					global $wpdb;
					$product = $wpdb->get_row( $wpdb->prepare( "SELECT product_id, manufacturer_id FROM ec_product WHERE model_number=  %s", sanitize_text_field( $_GET['subscription'] ) ) );
					if( $this->coupons[$i]->by_product_id ){ // validate product id match
						if( $this->coupons[$i]->product_id == $product->product_id ){
							return $this->coupons[$i];
						}else{
							return false;
						}
					}else if( $this->coupons[$i]->by_manufacturer_id ){ // validate manufacturer id match
						if( $this->coupons[$i]->manufacturer_id == $product->manufacturer_id ){
							return $this->coupons[$i];
						}else{
							return false;
						}
					}else if( $this->coupons[$i]->by_category_id ){ // validate category id match
						if( $has_categories = $wpdb->get_results( $wpdb->prepare( "SELECT categoryitem_id FROM ec_categoryitem WHERE category_id = %d AND product_id = %d", $this->coupons[$i]->category_id, $product->product_id ) ) ){
							return $this->coupons[$i];
						}else{
							return false;
						}
					}else{ // match works
						return $this->coupons[$i];
					}
				}else{
					return $this->coupons[$i];
				}
			}
			
		}
		
		return false;
		
	}
		
		
}

add_action( 'wp', 'wp_easycart_apply_query_coupon', 9 );
function wp_easycart_apply_query_coupon( ){
	if( isset( $_GET['ec_coupon'] ) ){
		wpeasycart_session( )->handle_session();
		$coupons = new ec_coupons( );
		if( $coupons->redeem_coupon_code( sanitize_text_field( $_GET['ec_coupon'] ) ) ){
			$GLOBALS['ec_cart_data']->cart_data->coupon_code = htmlspecialchars( sanitize_text_field( $_GET['ec_coupon'] ), ENT_QUOTES );
			$GLOBALS['ec_cart_data']->save_session_to_db( );
			wp_cache_flush( );
			do_action( 'wpeasycart_cart_updated' );
		}
	}
}
?>