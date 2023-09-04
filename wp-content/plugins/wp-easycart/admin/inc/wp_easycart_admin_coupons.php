<?php
class wp_easycart_admin_coupons {
	public function load_coupons_list() {
		if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['promocode_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_admin_coupon_details' );
		} else {
			do_action( 'wp_easycart_admin_coupon_list' );
		}
	}
}
