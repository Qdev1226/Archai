<?php

class ec_roleprices{

	public $roleprices;

	function __construct() {
		global $wpdb;
		$roleprices = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_roleprice.* FROM ec_roleprice, ec_user WHERE ec_user.user_id = %d AND ec_user.user_level = ec_roleprice.role_label ORDER BY ec_roleprice.product_id ASC', ( ( isset( $GLOBALS['ec_cart_data']->cart_data->user_id ) ) ? $GLOBALS['ec_cart_data']->cart_data->user_id : 0 ) ) );
		if( count( $roleprices ) == 0 ) {
			$roleprices = 'EMPTY';
		}
		if( 'EMPTY' == $roleprices ) {
			$roleprices = array();
		}
		$this->roleprices = $roleprices;
	}

	public function get_roleprice( $product_id ) {
		for ( $i = 0; $i < count( $this->roleprices ); $i++ ) {
			if ( $this->roleprices[ $i ]->product_id == $product_id ) {
				return $this->roleprices[$i]->role_price;
			}
		}
		return false;
	}
}
