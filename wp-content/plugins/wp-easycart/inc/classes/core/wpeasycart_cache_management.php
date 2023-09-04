<?php

class wpeasycart_cache_management{
	
	public function __construct( ){
		add_action( 'wpeasycart_cart_updated', 			array( $this, 'clear_cache' ), 10, 0 );
		add_action( 'wpeasycart_amf_complete',			array( $this, 'clear_cache' ), 10, 0 );
		add_action( 'wpeasycart_page_options_updated',	array( $this, 'clear_cache' ), 10, 0 );
		add_action( 'wpeasycart_user_updated',			array( $this, 'clear_user_cache' ), 10, 0 );
		add_action( 'wpeasycart_order_success',			array( $this, 'clear_user_cache' ), 10, 0 );
		add_action( 'wpeasycart_review_added',			array( $this, 'clear_cache' ), 10, 0 );
	}
	
	public function clear_tempcart_cache( ){
		wp_cache_delete( 'wpeasycart-tempcart-product-quantities-'.$GLOBALS['ec_cart_data']->ec_cart_id, 'wpeasycart-tempcart' );
		wp_cache_delete( 'wpeasycart-tempcart-'.$GLOBALS['ec_cart_data']->ec_cart_id, 'wpeasycart-tempcart' );
		wp_cache_delete( 'wpeasycart-advanced-cart-options-'.$GLOBALS['ec_cart_data']->ec_cart_id, 'wpeasycart-tempcart' );
		wp_cache_delete( 'wpeasycart-cart-data-'.$GLOBALS['ec_cart_data']->ec_cart_id, 'wpeasycart-tempcart' );
		wp_cache_delete( 'wpeasycart-user-'.$GLOBALS['ec_cart_data']->cart_data->user_id, 'wpeasycart-user' );
	}
	
	public function clear_user_cache( ){
		wp_cache_delete( 'wpeasycart-user-'.$GLOBALS['ec_cart_data']->cart_data->user_id, 'wpeasycart-user' );
		wp_cache_delete( 'wpeasycart-order-list-'.$GLOBALS['ec_cart_data']->cart_data->user_id, 'wpeasycart-orders' );
		wp_cache_delete( 'wpeasycart-subscriptions-'.$GLOBALS['ec_cart_data']->cart_data->user_id, 'wpeasycart-subscriptions' );
	}
	
	public function clear_cache( ){
		wp_cache_flush( );
	}
	
}

new wpeasycart_cache_management( );
?>