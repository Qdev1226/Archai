<?php
class wp_easycart_admin_actions {
	public function process_action() {
		if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_POST['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_process_post_form_action' );
		}

		if ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && isset( $_GET['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_process_get_form_action' );
		}
	}
}
