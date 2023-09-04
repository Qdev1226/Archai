<?php
class wp_easycart_admin_registration {

	public $registration_none_file;
	public $registration_file;
	public $registration_expired_file;
	public $trial_file;

	public function __construct() { 
		$this->registration_none_file = EC_PLUGIN_DIRECTORY . '/admin/template/registration/registration_none.php';
		$this->registration_file = EC_PLUGIN_DIRECTORY . '/admin/template/registration/registration_status.php';
		$this->registration_expired_file = EC_PLUGIN_DIRECTORY . '/admin/template/registration/registration-expired.php';
		$this->trial_file = EC_PLUGIN_DIRECTORY . '/admin/template/registration/trial.php';
	}

	public function load_registration_status() {
		$license_status = 'none';
		if ( function_exists( 'wp_easycart_admin_license' ) ) {
			$license_status = wp_easycart_admin_license()->license_check();
		}
		if ( $license_status == 'trial' ) {
			include( $this->trial_file );
			wp_easycart_admin()->show_upgrade();
		} else if ( $license_status == 'activated' || $license_status == 'deactivated' || $license_status == 'communications_error' ) {
			include( $this->registration_file );
		} else if ( $license_status == 'expired' ) {
			include( $this->registration_expired_file );
		} else {
			include( $this->registration_none_file );
			wp_easycart_admin()->show_upgrade();
		}
	}

	public function ec_activate_trial( $customername, $customeremail ) {
		delete_transient( 'ec_license_data');
		$action_url = 'http://connect.wpeasycart.com/licensing/activatetrial.php';
		$api_params = array(
			'ec_action' => 'activate_trial',
			'site_url' => esc_url_raw( wp_unslash( $_SERVER['SERVER_NAME'] ) ),
			'customername' => $customername,
			'customeremail' => $customeremail,
		);
		add_filter( 'https_ssl_verify', '__return_false' );
		$response = wp_remote_get( $action_url, array( 'body' => $api_params, 'timeout' => 15, 'sslverify' => false ) );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$response_code = wp_remote_retrieve_response_code( $response ) ;
		$body = wp_remote_retrieve_body( $response );
		$activation_status = $body;
		if ($activation_status != 'error' && $response_code == 200) {
			$trial_data = json_decode($body);
			$reg_code = $trial_data->reg_code;
			$this->wpdb->query( $this->wpdb->prepare( "UPDATE ec_setting SET reg_code = %s", $reg_code ) );
			return 'success';
		} else {
			return 'error';
		}
	}

	public function ec_check_trial() {
		$results = $this->wpdb->get_row("SELECT reg_code FROM ec_setting" );
		if ($results->reg_code != '') {	
			return 'success';
		} else {
			return 'error';
		}
	}
}
