<?php

define( 'BNC_API_VERSION', '5.2' );
define( 'BNC_API_URL', 'http://api.wptouch.com/v/' . BNC_API_VERSION );
define( 'BNC_API_TIMEOUT', 30 );

require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

class BNCAPI {
	var $bncid;
	var $license_key;
	var $time_variance;
	var $credentials_invalid;
	var $server_down;
	var $response_code;
	var $attempts;
	var $might_have_license;
	var $product_name;

	function __construct( $bncid, $license_key ) {
		$this->bncid = $bncid;
		$this->license_key = $license_key;
		$this->credentials_invalid = false;
		$this->server_down = false;
		$this->response_code = 0;
		$this->attempts = 0;
		$this->product_name = 'wptouch-pro-4';

		if ( $this->bncid && $this->license_key ) {
			$this->might_have_license = true;
		} else {
			$this->might_have_license = false;
		}
	}

	function might_have_license() {
		return $this->might_have_license;
	}

	function do_api_request( $method, $command, $params = array(), $do_auth = false ) {
		$url = BNC_API_URL . "/{$method}/{$command}/";

		// Always use the PHP serialization method for data
		$params[ 'format' ] = 'php';

		if ( is_multisite() ) {
			$params[ 'multisite' ] = 1;
			$params[ 'site_id' ] = get_current_blog_id();
			$params[ 'network_activated' ] = ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ? '1' : '0' );
			$params[ 'network_controlled' ] = ( wptouch_is_controlled_network() ? '1' : '0' );
		} else {
			$params[ 'multisite' ] = 0;
		}

		if ( $do_auth && $this->might_have_license ) {
			// Sort the parameters
			ksort( $params );

			// Generate a string to use for authorization
			$auth_string = '';
			foreach( $params as $key => $value ) {
				$auth_string = $auth_string . $key . $value;
			}

			WPTOUCH_DEBUG( WPTOUCH_INFO, 'Auth string [' . $auth_string . '], Key [' . $this->license_key . ']' );

			// Create the authorization hash using the server nonce and the license key
			$params[ 'auth' ] = md5( $auth_string . $this->license_key );
		}

        $body_params = array();
        foreach( $params as $key => $value ) {
        	$body_params[] = $key . '=' . urlencode( $value );
        }
        $body = implode( '&', $body_params );

        $options = array( 'method' => 'POST', 'timeout' => BNC_API_TIMEOUT, 'body' => $body );
        $options['headers'] = array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
            'Content-Length' => strlen( $body ),
            'User-Agent' => 'WordPress/' . get_bloginfo("version") . '/WPtouch-Pro/' . WPTOUCH_VERSION,
            'Referer' => get_bloginfo("url")
        );

        $this->attempts++;

        WPTOUCH_DEBUG( WPTOUCH_INFO, 'Attempting to connect to URL: ' . $url );
        $raw_response = wp_remote_request( $url, $options );
        if ( !is_wp_error( $raw_response ) ) {
        	if ( $raw_response['response']['code'] == 200 ) {

        	//	echo "\n\n" . $raw_response[ 'body' ] . "\n\n"; die;
        		$result = unserialize( $raw_response['body'] );

        		$this->response_code = $result['code'];
        		return $result;
        	} else {
        		WPTOUCH_DEBUG( WPTOUCH_WARNING, "Unable to connect to server. Response code is " . $raw_response[ 'response' ][ 'code' ] );
        		return false;
        	}
        } else {
       		$this->server_down = true;
       		return false;
        }
	}

	function get_response_code() {
		return $this->response_code;
	}

	function get_proper_server_name() {
		$server_name = false;

		if ( is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) ) {
			$main_site_url = str_replace( array( 'http://', 'https://' ), array( '', '' ), get_site_option( 'siteurl' ) );
			$site_info = explode( '/', $main_site_url );

			$server_name = $site_info[0];
		} else {
			$server_name = getenv('HTTP_HOST');
		}

		if ( strpos( $server_name, ':' ) !== false ) {
			$server_params = explode( ':', $server_name );

			return $server_params[0];
		} else {
			return $server_name;
		}
	}

	function verify_site_license() {
		// only check for a real license if they have a  license key
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'product_name' => $this->product_name
		);

		$result = $this->do_api_request( 'user', 'verify_license', $params, true );

		if ( $result and $result['status'] == 'ok' ) {
			return true;
		}

		return false;
	}

	function check_api() {
		$params = array(
			'site' => $this->get_proper_server_name(),
			'product_name' => $this->product_name,
			'product_version' => WPTOUCH_VERSION,
			'bncid_temp' => $this->bncid
		);

		$result = $this->do_api_request( 'check', 'api', $params, true );

		if ( $result and $result['status'] == 'ok' ) {
			if ( isset( $result[ 'result' ] ) ) {
				return $result[ 'result' ];
			}
		}

		return false;
	}

	function get_all_available_themes() {
		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'product_name' => $this->product_name,
			'current_version' => WPTOUCH_VERSION
		);

		$result = false;
		if ( $this->might_have_license ) {
			$result = $this->do_api_request( 'themes', 'get_available', $params, true );
		} else {
			$result = $this->do_api_request( 'themes', 'get_available', $params, false );
		}

		if ( $result and $result['status'] == 'ok' ) {
			return $result[ 'result' ][ 'themes' ];
		}

		return false;
	}

	function get_all_available_addons() {
		$params = array(
			'bncid' => $this->bncid,
			'site' => $this->get_proper_server_name(),
			'product_name' => $this->product_name,
			'current_version' => WPTOUCH_VERSION
		);

		$result = false;
		if ( $this->might_have_license ) {
			$result = $this->do_api_request( 'addons', 'get_available', $params, true );
		} else {
			$result = $this->do_api_request( 'addons', 'get_available', $params, false );
		}

		if ( $result and $result['status'] == 'ok' ) {
			return $result[ 'result' ][ 'addons' ];
		}

		return false;
	}

	function get_total_licenses() {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $this->product_name
		);

		$result = $this->do_api_request( 'user', 'get_license_count', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return $result['result']['count'];
		}

		return false;
	}

	function user_update_info( $product_version, $theme_name, $active_addons, $settings_diff ) {
		if ( !$this->might_have_license ) {
			return false;
		}

		$info = array(
			'theme' => $theme_name,
			'product_version' => $product_version,
			'extensions' => $active_addons,
			'settings_diff' => $settings_diff
		);

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $this->product_name,
			'info' => serialize( $info ),
			'site' => $this->get_proper_server_name()
		);

		$result = $this->do_api_request( 'user', 'update_info', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return true;
		}

		return false;
	}

	function get_product_version() {
		$params = array(
			'site' => $this->get_proper_server_name(),
			'product_name' => $this->product_name
		);

		$result = false;

		if ( $this->might_have_license ) {
			$params[ 'bncid' ] = $this->bncid;
			$result = $this->do_api_request( 'products', 'get_version', $params, true );
		} else {
			$result = $this->do_api_request( 'products', 'get_version', $params, false );
		}

		if ( $result and $result['status'] == 'ok' ) {
			return $result['result']['product'];
		}

		return false;
	}

	function user_list_licenses() {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $this->product_name
		);

		$result = $this->do_api_request( 'user', 'list_licenses', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return $result['result'];
		}

		return false;
	}

	function user_add_license() {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $this->product_name,
			'site' => $this->get_proper_server_name()
		);

		$result = $this->do_api_request( 'user', 'add_license', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return true;
		}

		return false;
	}

	function user_remove_license() {
		if ( !$this->might_have_license ) {
			return false;
		}

		$params = array(
			'bncid' => $this->bncid,
			'product_name' => $this->product_name,
			'site' => $this->get_proper_server_name()
		);

		$result = $this->do_api_request( 'user', 'remove_license', $params, true );
		if ( $result and $result['status'] == 'ok' ) {
			return true;
		}

		return false;
	}

	function translations_get_list() {
		$params = array(
			'product_name' => $this->product_name
		);

		$result = $this->do_api_request( 'translations', 'get_list', $params, false );
		if ( $result and $result['status'] == 'ok' ) {
			return $result [ 'result' ][ 'language_info' ];
		}

		return false;
	}
}
