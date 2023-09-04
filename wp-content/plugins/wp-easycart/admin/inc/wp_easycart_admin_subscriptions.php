<?php
class wp_easycart_admin_subscriptions {
	public function load_subscriptions_list() {
		if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['subscription_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_admin_subscriptions_details' );
		} else {
			do_action( 'wp_easycart_admin_subscriptions_list' );
		}
	}
}
