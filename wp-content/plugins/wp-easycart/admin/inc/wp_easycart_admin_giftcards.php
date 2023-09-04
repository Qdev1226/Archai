<?php
class wp_easycart_admin_giftcards {
	public function load_giftcards_list() {
		if( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['giftcard_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_admin_giftcard_details' );
		} else {
			do_action( 'wp_easycart_admin_giftcard_list' );
		}
	}
}
