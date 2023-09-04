<?php
class wp_easycart_admin_promotions {
	public function load_promotions_list() {
		if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['promotion_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_admin_promotion_details' );
		} else {
			do_action( 'wp_easycart_admin_promotion_list' );
		}
	}
}
