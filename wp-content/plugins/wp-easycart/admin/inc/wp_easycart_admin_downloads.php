<?php
class wp_easycart_admin_downloads {
	public function load_downloads_list() {
		if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['download_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
			do_action( 'wp_easycart_admin_downloads_details' );
		} else {
			do_action( 'wp_easycart_admin_downloads_list' );
		}
	}
}
