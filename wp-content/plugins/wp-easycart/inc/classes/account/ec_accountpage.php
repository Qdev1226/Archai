<?php

class ec_accountpage {
	protected $mysqli;

	public $user;
	public $downloads;
	public $orders;
	public $order;
	public $subscriptions;
	public $subscription;

	private $user_email;
	private $user_password;

	public $store_page;
	public $account_page;
	public $cart_page;
	public $permalink_divider;

	public $redirect_login;

	function __construct( $redirect_login = false ) {

		$this->user =& $GLOBALS['ec_user'];
		$this->mysqli = new ec_db();
		$this->orders = new ec_orderlist( $GLOBALS['ec_user']->user_id );
		$this->subscriptions = new ec_subscription_list( $GLOBALS['ec_user'] );
		$this->downloads = $this->mysqli->get_download_list( $GLOBALS['ec_user']->user_id );

		if ( isset( $_GET['order_id'] ) ) {
			if ( isset( $_GET['ec_guest_key'] ) && (bool) $_GET['ec_guest_key'] ) {
				$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
				$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_key( $_GET['ec_guest_key'] );
				$order_row = $this->mysqli->get_guest_order_row( (int) $_GET['order_id'], sanitize_key( $_GET['ec_guest_key'] ) );

			} else if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != '' ) {
				$order_row = $this->mysqli->get_guest_order_row( (int) $_GET['order_id'], $GLOBALS['ec_cart_data']->cart_data->guest_key );

			} else {
				$order_row = $this->mysqli->get_order_row( (int) $_GET['order_id'], $GLOBALS['ec_cart_data']->cart_data->user_id );
			}

			if ( $order_row ) {
				$this->order = new ec_orderdisplay( $order_row, true );
			}
		}

		if ( isset( $_GET['subscription_id'] ) ) {
			$subscription_row = $this->mysqli->get_subscription_row( (int) $_GET['subscription_id'] );
			if ( $subscription_row && $subscription_row->user_id == $GLOBALS['ec_cart_data']->cart_data->user_id ) {
				$this->subscription = new ec_subscription( $subscription_row, true );
			} else {
				$this->subscription = false;
			}
		}

		$storepageid = get_option('ec_option_storepage');
		$accountpageid = get_option('ec_option_accountpage');
		$cartpageid = get_option('ec_option_cartpage');

		if ( function_exists( 'icl_object_id' ) ) {
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->store_page = get_permalink( $storepageid );
		$this->account_page = get_permalink( $accountpageid );
		$this->cart_page = get_permalink( $cartpageid );

		if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );

		} else if ( get_option( 'ec_option_load_ssl' ) ) {
			$this->store_page = str_replace( 'http://', 'https://', $this->store_page );
			$this->cart_page = str_replace( 'http://', 'https://', $this->cart_page );
			$this->account_page = str_replace( 'http://', 'https://', $this->account_page );

		}

		if ( substr_count( $this->account_page, '?' ) ) {
			$this->permalink_divider = '&';
		} else {
			$this->permalink_divider = '?';
		}

		$this->redirect_login = $redirect_login;

		$this->cart_page = apply_filters( 'wp_easycart_cart_page_url', $this->cart_page );
		$this->account_page = apply_filters( 'wp_easycart_account_page_url', $this->account_page );
	}

	public function display_account_dynamic( $account_page, $page_id, $success_code, $error_code ) {

		$this->display_account_error( $error_code );
		$this->display_account_success( $success_code );

		if ( $GLOBALS['ec_cart_data']->cart_data->user_id != "" ) {

			if ( $account_page == 'billing_information' ) {
				$this->display_billing_information_page();

			} else if ( $account_page == 'shipping_information' ) {
				$this->display_shipping_information_page();

			} else if ( $account_page == 'personal_information' ) {
				$this->display_personal_information_page();

			} else if ( $account_page == 'orders' ) {
				$this->display_orders_page();

			} else if ( $account_page == 'subscriptions' ) {
				$this->display_subscriptions_page();

			} else if ( $account_page == 'password' ) {
				$this->display_password_page();

			} else if ( substr( $account_page, 0, 13 ) == 'order_details' ) {
				$order_info = explode( '-', $account_page );
				$order_row = false;
				if ( count( $order_info ) > 1 ) {
					$order_id = (int) $order_info[1];
					if ( count( $order_info ) > 2 ) {
						$guest_key = substr( preg_replace( '/[^A-Z]/', '', $order_info[2] ), 0, 30 );
						$order_row = $this->mysqli->get_guest_order_row( $order_id, $guest_key );
						if ( $order_row ) {
							$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
							$GLOBALS['ec_cart_data']->cart_data->guest_key = $guest_key;
						}
					} else {
						$order_row = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
					}
				}

				if ( $order_row ) {
					$this->order = new ec_orderdisplay( $order_row, true );
				}

				$this->display_order_details_page();

			} else if ( substr( $account_page, 0, 20 ) == 'subscription_details' ) {
				$this->subscription = false;
				$subscription_id = (int) substr( $account_page, 21 );
				$subscription_row = $this->mysqli->get_subscription_row( $subscription_id );

				if ( $subscription_row && $subscription_row->user_id == $GLOBALS['ec_cart_data']->cart_data->user_id ) {
					$this->subscription = new ec_subscription( $subscription_row, true );
				}

				$this->display_subscription_details_page();

			} else {
				if ( 'login_success' == $success_code ) {
					if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
						if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) {
							echo '<script>
								jQuery( document ).ready( function() {
									dataLayer.push({
										event: "login",
										"userId": "' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->user_id ) . '",
									} );
								} );
							</script>';
						} else {
							echo '<script>
								jQuery( document ).ready( function() {
									gtag( "event", "login", {
										"userId": "' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->user_id ) . '",
									} );
								} );
							</script>';
						}
					}
				}
				$this->display_dashboard_page();

			}

		} else {

			if ( substr( $account_page, 0, 13 ) == 'order_details' ) {
				$order_info = explode( '-', $account_page );
				$order_row = false;
				if ( count( $order_info ) > 1 ) {
					$order_id = (int) $order_info[1];
					if ( count( $order_info ) > 2 ) {
						$guest_key = substr( preg_replace( '/[^A-Z]/', '', $order_info[2] ), 0, 30 );
						$order_row = $this->mysqli->get_guest_order_row( $order_id, $guest_key );
						if ( $order_row ) {
							$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
							$GLOBALS['ec_cart_data']->cart_data->guest_key = $guest_key;
						}
					}
				}

				if ( $order_row ) {
					$this->order = new ec_orderdisplay( $order_row, true );
					$this->display_order_details_page();
				} else {
					$this->display_account_login_page( $page_id );

				}

			} else if ( $account_page == 'register' ) {
				$this->display_register_page();

			} else if ( $account_page == 'forgot_password' ) {
				$this->display_forgot_password_page();

			} else {
				$this->display_account_login_page( $page_id );

			}

		}

	}

	public function display_account_page() {
		do_action( 'wpeasycart_account_page_pre' );
		if ( apply_filters( 'wpeasycart_show_account_page', true ) ) {
			echo "<div class=\"ec_account_page\">";
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_page.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_page.php' );
			else	
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_page.php' );
			echo "<input type=\"hidden\" name=\"ec_account_base_path\" id=\"ec_account_base_path\" value=\"" . esc_url( plugins_url() ) . "\" />";
			echo "<input type=\"hidden\" name=\"ec_account_session_id\" id=\"ec_account_session_id\" value=\"" . esc_attr( $GLOBALS['ec_cart_data']->ec_cart_id ) . "\" />";
			echo "<input type=\"hidden\" name=\"ec_account_email\" id=\"ec_account_email\" value=\"" . esc_attr( htmlspecialchars( $this->user_email, ENT_QUOTES ) ) . "\" />";

			$page_name = "";
			if ( isset( $_GET['ec_page'] ) )
				$page_name = htmlspecialchars( sanitize_key( $_GET['ec_page'] ), ENT_QUOTES );

			echo "<input type=\"hidden\" name=\"ec_account_start_page\" id=\"ec_account_start_page\" value=\"" . esc_attr( $page_name ) . "\" />";
			echo "</div>";
		}
	}

	public function display_account_error( $error_code = '' ) {
		$valid_error_codes = array( 'not_activated', 'login_failed', 'register_email_error', 'register_invalid', 'no_reset_email_found', 'personal_information_update_error', 'password_no_match', 'password_wrong_current', 'billing_information_error', 'shipping_information_error', 'subscription_update_failed', 'subscription_cancel_failed' );
		if ( isset( $_GET['account_error'] ) && in_array( $_GET['account_error'], $valid_error_codes ) ) {
			$error_text = wp_easycart_language()->get_text( "ec_errors", sanitize_key( $_GET['account_error'] ) );
			$error_text = apply_filters( 'wpeasycart_account_error', $error_text, sanitize_key( $_GET['account_error'] ) );
			if ( $error_text ) {
				echo "<div class=\"ec_account_error\"><div>" . esc_attr( $error_text ) . " ";
				if ( $_GET['account_error'] == 'login_failed' ) {
					$this->display_account_login_forgot_password_link( wp_easycart_language()->get_text( 'account_login', 'account_login_forgot_password_link' ) );
				}
				echo "</div></div>";
			}

		} else if ( $error_code != '' && in_array( $error_code, $valid_error_codes ) ) {
			$error_text = wp_easycart_language()->get_text( "ec_errors", $error_code );
			$error_text = apply_filters( 'wpeasycart_account_error', $error_text, $error_code );
			if ( $error_text ) {
				echo "<div class=\"ec_account_error\"><div>" . esc_attr( $error_text ) . " ";
				if ( $error_code == 'login_failed' ) {
					$this->display_account_login_forgot_password_link( wp_easycart_language()->get_text( 'account_login', 'account_login_forgot_password_link' ) );
				}
				echo "</div></div>";
			}
		}
	}

	public function display_account_success( $success_code = '' ) {
		$valid_success_codes = array( 'validation_required', 'reset_email_sent', 'personal_information_updated', 'billing_information_updated', 'billing_information_updated', 'shipping_information_updated', 'shipping_information_updated', 'subscription_updated', 'subscription_updated', 'subscription_canceled', 'cart_account_created', 'activation_success' );
		if ( isset( $_GET['account_success'] ) && in_array( $_GET['account_success'], $valid_success_codes ) ) {
			$success_text = wp_easycart_language()->get_text( "ec_success", sanitize_key( $_GET['account_success'] ) );
			$success_text = apply_filters( 'wpeasycart_account_success', $success_text, sanitize_key( $_GET['account_success'] ) );
			if ( $success_text )
				echo "<div class=\"ec_account_success\"><div>" . esc_attr( $success_text ) . "</div></div>";

		} else if ( $success_code != '' && in_array( $success_code, $valid_success_codes ) ) {
			$success_text = wp_easycart_language()->get_text( "ec_success", $success_code );
			$success_text = apply_filters( 'wpeasycart_account_success', $success_text, $success_code );
			if ( $success_text )
				echo "<div class=\"ec_account_success\"><div>" . esc_attr( $success_text ) . "</div></div>";
		}
		if ( 'login_success' == $success_code ) {
			do_action( 'wp_easycart_login_success_account' );
		}
	}

	public function is_page_visible( $page_name ) {
		if ( isset( $_GET['ec_page'] ) ) { //Check for a ec_page variable, act differently if set.
			if ( $GLOBALS['ec_cart_data']->cart_data->user_id != "" ) { //If logged in we can show any page accept login
				if ( $page_name == 'login' )															return false;
				else if ( $page_name == $_GET['ec_page'] )												return true;
				else if ( $_GET['ec_page'] == 'login' && $page_name == 'dashboard')						return true;
				else																					return false;

			} else if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" ) { // checked out guests can see order details
				if ( $page_name == 'forgot_password' && $_GET['ec_page'] == 'forgot_password' )			return true;
				else if ( $page_name == 'register' && $_GET['ec_page'] == 'register' )					return true;
				else if ( $page_name == 'login' && $_GET['ec_page'] != 'register' && $_GET['ec_page'] != 'forgot_password' && $_GET['ec_page'] != 'order_details' )	
																										return true;
				else if ( $page_name == 'order_details' && $_GET['ec_page'] == 'order_details' && $this->order )			
																										return true;
				else if ( $page_name == 'login' && $_GET['ec_page'] == 'order_details' && !$this->order )
																										return true;
				else																					return false; 

			} else if ( isset( $_GET['ec_guest_key'] ) && (bool) $_GET['ec_guest_key'] ) { // guests can see their order with a key
				if ( $page_name == 'forgot_password' && $_GET['ec_page'] == 'forgot_password' )			return true;
				else if ( $page_name == 'register' && $_GET['ec_page'] == 'register' )					return true;
				else if ( $page_name == 'login' && $_GET['ec_page'] != 'register' && $_GET['ec_page'] != 'forgot_password' && $_GET['ec_page'] != 'order_details' )	
																										return true;
				else if ( $page_name == 'order_details' && $_GET['ec_page'] == 'order_details' )			return true;
				else																					return false; 

			} else { //If not logged in we can only show login or register
				if ( $page_name == 'forgot_password' && $_GET['ec_page'] == 'forgot_password' )			return true;
				else if ( $page_name == 'register' && $_GET['ec_page'] == 'register' )					return true;
				else if ( $page_name == 'login' && $_GET['ec_page'] != 'register' && $_GET['ec_page'] != 'forgot_password' )	
																										return true;
				else																					return false;
			}
		} else { //ec_page variable is not set
			if ( $GLOBALS['ec_cart_data']->cart_data->user_id != "" ) { //If logged in we should only show dashboard here
				if ( $page_name == 'dashboard' )										return true;
				else																return false;
			} else { //If not logged in we should only show login here
				if ( $page_name == 'login' )											return true;
				else																return false;
			}
		}
	}

	/* START ACCOUNT LOGIN FUNCTIONS */
	public function display_account_login() {
		if ( $this->is_page_visible( "login" ) ) {
			$this->display_account_login_page();
		}
	}

	public function display_account_login_page( $page_id = false ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_login.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_login.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_login.php' );
		do_action( 'wpeasycart_account_login_post' );
	}

	public function display_account_login_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";
	}

	public function display_account_login_form_end() {

		if ( $this->redirect_login ) {
			echo "<input type=\"hidden\" name=\"ec_custom_login_redirect\" value=\"" . esc_url_raw( wp_unslash( $this->redirect_login ) ) . "\" />";
		}
		if ( isset( $_GET['ec_page'] ) ) {
			echo "<input type=\"hidden\" name=\"ec_goto_page\" value=\"" . esc_attr( sanitize_key( $_GET['ec_page'] ) ) . "\" />";
		}
		if ( isset( $_GET['order_id'] ) ) {
			echo "<input type=\"hidden\" name=\"ec_order_id\" value=\"" . esc_attr( (int) $_GET['order_id'] ) . "\" />";
		}
		if ( isset( $_GET['subscription_id'] ) ) {
			echo "<input type=\"hidden\" name=\"ec_subscription_id\" value=\"" . esc_attr( (int) $_GET['subscription_id'] ) . "\" />";
		}
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" value=\"login\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-login' ) ) . "\" />";
		echo "</form>";	

	}

	public function display_account_login_email_input() {
		echo "<input type=\"email\" name=\"ec_account_login_email\" id=\"ec_account_login_email\" class=\"ec_account_login_input_field\" autocomplete=\"off\" autocapitalize=\"off\">";
	}

	public function display_account_login_password_input() {
		echo "<input type=\"password\" name=\"ec_account_login_password\" id=\"ec_account_login_password\" class=\"ec_account_login_input_field\">";
	}

	public function display_account_login_button( $button_text ) {
		echo "<input type=\"submit\" name=\"ec_account_login_button\" id=\"ec_account_login_button\" class=\"ec_account_login_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_login_button_click();\">";
	}

	public function display_account_login_forgot_password_link( $link_text ) {
		echo wp_easycart_escape_html( apply_filters( 'wpeasycart_forgot_password_link', "<a href=\"" . $this->account_page . $this->permalink_divider . "ec_page=forgot_password\" class=\"ec_account_login_link\">" . esc_attr( $link_text ) . "</a>" ) );
	}

	public function display_account_login_create_account_button( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=register\" class=\"ec_account_login_create_account_button\">" . esc_attr( $link_text ) . "</a>";
	}

	/* END ACCOUNT LOGIN FUNCTIONS */

	/* START FORGOT PASSWORD FUNCTIONS */
	public function display_account_forgot_password() {
		if ( $this->is_page_visible( "forgot_password" ) ) {
			$this->display_forgot_password_page();
		}
	}

	public function display_forgot_password_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_forgot_password.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_forgot_password.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_forgot_password.php' );
	}

	public function display_account_forgot_password_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";	
	}

	public function display_account_forgot_password_form_end() {
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" value=\"retrieve_password\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-retrieve-password' ) ) . "\" />";
		echo "</form>";
	}

	public function display_account_forgot_password_email_input() {
		echo "<input type=\"email\" name=\"ec_account_forgot_password_email\" id=\"ec_account_forgot_password_email\" class=\"ec_account_forgot_password_input_field\">";	
	}

	public function display_account_forgot_password_submit_button( $button_text ) {
		echo "<input type=\"submit\" name=\"ec_account_forgot_password_button\" id=\"ec_account_forgot_password_button\" class=\"ec_account_forgot_password_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_forgot_password_button_click();\">";
	}
	/* END FORGOT PASSWORD FUNCTIONS*/

	/* START REGISTER FUNCTIONS */
	public function display_account_register() {
		if ( $this->is_page_visible( "register" ) ) {
			$this->display_register_page();
		}
	}

	public function display_register_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_register.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_register.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_register.php' );
	}

	public function display_account_register_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-register' ) ) . "\" />";
	}

	public function display_account_register_form_end() {
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" value=\"register\"/>";
		echo "</form>";
	}

	public function display_account_register_first_name_input() {
		echo "<input type=\"text\" name=\"ec_account_register_first_name\" id=\"ec_account_register_first_name\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_last_name_input() {
		echo "<input type=\"text\" name=\"ec_account_register_last_name\" id=\"ec_account_register_last_name\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_zip_input() {
		echo "<input type=\"text\" name=\"ec_account_register_zip\" id=\"ec_account_register_zip\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_email_input() {
		echo "<input type=\"email\" name=\"ec_account_register_email\" id=\"ec_account_register_email\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_retype_email_input() {
		echo "<input type=\"email\" name=\"ec_account_register_retype_email\" id=\"ec_account_register_retype_email\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_password_input() {
		echo "<input type=\"password\" name=\"ec_account_register_password\" id=\"ec_account_register_password\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_retype_password_input() {
		echo "<input type=\"password\" name=\"ec_account_register_password_retype\" id=\"ec_account_register_password_retype\" class=\"ec_account_register_input_field\">";
	}

	public function display_account_register_is_subscriber_input() {
		echo "<input type=\"checkbox\" name=\"ec_account_register_is_subscriber\" id=\"ec_account_register_is_subscriber\" class=\"ec_account_register_input_field\" />";	
	}

	public function display_account_register_button( $button_text ) {
		if ( get_option( 'ec_option_require_account_address' ) )
			echo "<input type=\"submit\" name=\"ec_account_register_button\" id=\"ec_account_register_button\" class=\"ec_account_register_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_register_button_click2();\">";
		else
			echo "<input type=\"submit\" name=\"ec_account_register_button\" id=\"ec_account_register_button\" class=\"ec_account_register_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_register_button_click();\">";
	}
	/* END REGISTER FUNCTIONS */

	/* START DASHBOARD FUNCTIONS */
	public function display_account_dashboard() {
		if ( $this->is_page_visible( "dashboard" ) ) {
			$this->display_dashboard_page();
		}
	}

	public function display_dashboard_page() {
		if ( isset( $_GET['account_success'] ) && 'login_success' == $_GET['account_success'] ) {
			if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
				if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) {
					echo '<script>
						jQuery( document ).ready( function() {
							dataLayer.push({
								event: "login",
								"userId": "' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->user_id ) . '",
							} );
						} );
					</script>';
				} else {
					echo '<script>
						jQuery( document ).ready( function() {
							gtag( "event", "login", {
								"userId": "' . esc_attr( $GLOBALS['ec_cart_data']->cart_data->user_id ) . '",
							} );
						} );
					</script>';
				}
			}
		}
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_dashboard.php' ) ) {
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_dashboard.php' );
		} else {
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_dashboard.php' );
		}
	}

	public function display_dashboard_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";	
	}

	public function display_orders_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=orders\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";	
	}

	public function display_personal_information_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=personal_information\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_billing_information_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=billing_information\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_shipping_information_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=shipping_information\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_password_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=password\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_subscriptions_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=subscriptions\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_payment_methods_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=payment_methods\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_logout_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=logout\" class=\"ec_account_dashboard_link\">" . esc_attr( $link_text ) . "</a>";
	}
	/* END DASHBOARD FUNCTIONS */

	/* START ORDERS FUNCTIONS */
	public function display_account_orders() {
		if ( $this->is_page_visible( "orders" ) ) {
			$this->display_orders_page();
		}
	}

	public function display_orders_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_orders.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_orders.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_orders.php' );
	}
	/* END ORDERS FUNCTIONS*/

	/* START ORDER DETAILS FUNCTIONS */
	public function display_account_order_details() {
		if ( $this->is_page_visible( "order_details" ) ) {
			$this->display_order_details_page();
		}
	}

	public function display_order_details_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_order_details.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_order_details.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_order_details.php' );
	}

	public function display_order_detail_product_list() {
		if ( $this->order ) {
			$this->order->display_order_detail_product_list();
		}
	}

	public function display_print_order_icon() {
		if ( $this->order ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_account_order_details/print_icon.png" ) )	
				echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=print_receipt&order_id=" . esc_attr( $this->order->order_id ) . "\" target=\"_blank\"><img src=\"" . esc_url( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_account_order_details/print_icon.png", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" alt=\"print\" /></a>";
			else
				echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=print_receipt&order_id=" . esc_attr( $this->order->order_id ) . "\" target=\"_blank\"><img src=\"" . esc_url( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_account_order_details/print_icon.png", EC_PLUGIN_DIRECTORY ) ) . "\" alt=\"print\" /></a>";
		}
	}

	public function get_print_order_icon_url() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/print_icon.png" ) )
			return plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/print_icon.png", EC_PLUGIN_DATA_DIRECTORY  );
		else
			return plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/print_icon.png", EC_PLUGIN_DIRECTORY  );
	}

	public function display_complete_payment_link() {
		if ( $this->order && $this->order->orderstatus_id == 8 ) {
			echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=third_party&order_id=" . esc_attr( $this->order->order_id ) . "\" class=\"ec_account_complete_order_link\">" . wp_easycart_language()->get_text( 'account_order_details', 'complete_payment' ) . "</a> ";
		}
	}
	/* END ORDER DETAILS FUNCTIONS*/

	/* START PERSONAL INFORMATION FUNCTIONS */
	public function display_account_personal_information() {
		if ( $this->is_page_visible( "personal_information" ) ) {
			$this->display_personal_information_page();
		}
	}

	public function display_personal_information_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_personal_information.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_personal_information.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_personal_information.php' );
	}

	public function display_account_personal_information_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" id=\"ec_account_personal_information_form_action\" value=\"update_personal_information\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-update-personal-info-' . (int) $GLOBALS['ec_user']->user_id ) ) . "\" />";
	}

	public function display_account_personal_information_form_end() {
		echo "</form>";
	}

	public function display_account_personal_information_first_name_input() {
		echo "<input type=\"text\" name=\"ec_account_personal_information_first_name\" id=\"ec_account_personal_information_first_name\" class=\"ec_account_personal_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->first_name, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_personal_information_last_name_input() {
		echo "<input type=\"text\" name=\"ec_account_personal_information_last_name\" id=\"ec_account_personal_information_last_name\" class=\"ec_account_personal_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->last_name, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_personal_information_vat_registration_number_input() {
		echo "<input type=\"text\" name=\"ec_account_personal_information_vat_registration_number\" id=\"ec_account_personal_information_vat_registration_number\" class=\"ec_account_personal_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->vat_registration_number, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_personal_information_zip_input() {
		echo "<input type=\"text\" name=\"ec_account_personal_information_zip\" id=\"ec_account_personal_information_zip\" class=\"ec_account_personal_information_input_field\" value=\"" . esc_attr(  htmlspecialchars( $GLOBALS['ec_user']->billing->zip, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_personal_information_email_input() {
		echo "<input type=\"email\" name=\"ec_account_personal_information_email\" id=\"ec_account_personal_information_email\" class=\"ec_account_personal_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->email, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_personal_information_email_other_input() {
		echo "<input type=\"email\" name=\"ec_account_personal_information_email_other\" id=\"ec_account_personal_information_email_other\" class=\"ec_account_personal_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->email_other, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_personal_information_is_subscriber_input() {
		echo "<input type=\"checkbox\" name=\"ec_account_personal_information_is_subscriber\" id=\"ec_account_personal_information_is_subscriber\" class=\"ec_account_personal_information_input_field\"";
		if ( $GLOBALS['ec_user']->is_subscriber )
		echo " checked=\"checked\"";
		echo "/>";
	}

	public function display_account_personal_information_update_button( $button_text ) {
		echo "<input type=\"submit\" name=\"ec_account_personal_information_button\" id=\"ec_account_personal_information_button\" class=\"ec_account_personal_information_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_personal_information_update_click();\" />";
	}
	public function display_account_personal_information_cancel_link( $button_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard\" class=\"ec_account_personal_information_link\"><input type=\"button\" name=\"ec_account_personal_information_button\" id=\"ec_account_personal_information_button\" class=\"ec_account_personal_information_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"window.location='" . esc_url_raw( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard'\" /></a>";
	}


	/* END PERSONAL INFORMATION FUNCTIONS */

	/* START PASSWORD FUNCTIONS */
	public function display_account_password() {
		if ( $this->is_page_visible( "password" ) ) {
			$this->display_password_page();
		}
	}

	public function display_password_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_password.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_password.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_password.php' );
	}

	public function display_account_password_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" id=\"ec_account_password_form_action\" value=\"update_password\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-update-password-' . (int) $GLOBALS['ec_user']->user_id ) ) . "\" />";
	}

	public function display_account_password_form_end() {
		echo "</form>";
	}

	public function display_account_password_current_password() {
		echo "<input type=\"password\" name=\"ec_account_password_current_password\" id=\"ec_account_password_current_password\" class=\"ec_account_password_input_field\">";
	}

	public function display_account_password_new_password() {
		echo "<input type=\"password\" name=\"ec_account_password_new_password\" id=\"ec_account_password_new_password\" class=\"ec_account_password_input_field\">";
	}

	public function display_account_password_retype_new_password() {
		echo "<input type=\"password\" name=\"ec_account_password_retype_new_password\" id=\"ec_account_password_retype_new_password\" class=\"ec_account_password_input_field\">";
	}

	public function display_account_password_update_button( $button_text ) {
		echo "<input type=\"submit\" name=\"ec_account_password_button\" id=\"ec_account_password_button\" class=\"ec_account_password_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_password_button_click();\" />";
	}
	public function display_account_password_cancel_link( $button_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard\" class=\"ec_account_password_link\"><input type=\"button\" name=\"ec_account_password_button\" id=\"ec_account_password_button\" class=\"ec_account_password_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"window.location='" . esc_url_raw( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard'\" /></a>";
	}

	/* END PASSWORD FUNCTIONS */

	/* START BILLING INFORMATION FUNCTIONS */
	public function display_account_billing_information() {
		if ( $this->is_page_visible( "billing_information" ) ) {
			$this->display_billing_information_page();
		}
	}

	public function display_billing_information_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_billing_information.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_billing_information.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_billing_information.php' );
	}

	public function display_account_billing_information_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" id=\"ec_account_billing_information_form_action\" value=\"update_billing_information\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-update-billing-info-' . (int) $GLOBALS['ec_user']->user_id ) ) . "\" />";
	}

	public function display_account_billing_information_form_end() {
		echo "</form>";
	}

	public function display_account_billing_information_first_name_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_first_name\" id=\"ec_account_billing_information_first_name\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->first_name, ENT_QUOTES )  ). "\" />";
	}

	public function display_account_billing_information_last_name_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_last_name\" id=\"ec_account_billing_information_last_name\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->last_name, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_company_name_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_company_name\" id=\"ec_account_billing_information_company_name\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->company_name, ENT_QUOTES ) ) . "\" />";
	}

	public function display_vat_registration_number_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_vat_registration_number\" id=\"ec_account_billing_vat_registration_number\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->vat_registration_number, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_address_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_address\" id=\"ec_account_billing_information_address\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->address_line_1, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_address2_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_address2\" id=\"ec_account_billing_information_address2\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->address_line_2, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_city_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_city\" id=\"ec_account_billing_information_city\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->city, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_state_input() {

		if ( get_option( 'ec_option_use_smart_states' ) ) {

			// DISPLAY STATE DROP DOWN MENU
			$states = $this->mysqli->get_states();
			$selected_state = $GLOBALS['ec_user']->billing->get_value( "state" );
			$selected_country = $GLOBALS['ec_user']->billing->get_value( "country2" );

			$current_country = "";
			$close_last_state = false;
			$state_found = false;
			$current_state_group = "";
			$close_last_state_group = false;

			foreach ($states as $state) {
				if ( $current_country != $state->iso2_cnt ) {
					if ( $close_last_state ) {
						echo "</select>";
					}
					echo "<select name=\"ec_account_billing_information_state_" . esc_attr( $state->iso2_cnt ) . "\" id=\"ec_account_billing_information_state_" . esc_attr( $state->iso2_cnt ) . "\" class=\"ec_account_billing_information_input_field ec_billing_state_dropdown\"";
					if ( $state->iso2_cnt != $selected_country ) {
						echo " style=\"display:none;\"";
					} else {
						$state_found = true;
					}
					echo ">";

					if ( $state->iso2_cnt == "CA" ) { // Canada
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_billing_information", "cart_billing_information_select_province" ) . "</option>";
					} else if ( $state->iso2_cnt == "GB" ) { // United Kingdom
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_billing_information", "cart_billing_information_select_county" ) . "</option>";
					} else if ( $state->iso2_cnt == "US" ) { //USA 
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_billing_information", "cart_billing_information_select_state" ) . "</option>";
					} else {
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_billing_information", "cart_billing_information_select_other" ) . "</option>";
					}

					$current_country = $state->iso2_cnt;
					$close_last_state = true;
				}

				if ( $current_state_group != $state->group_sta && $state->group_sta != "" ) {
					if ( $close_last_state_group ) {
						echo "</optgroup>";
					}
					echo "<optgroup label=\"" . esc_attr( $state->group_sta ) . "\">";
					$current_state_group = $state->group_sta;
					$close_last_state_group = true;
				}

				echo "<option value=\"" . esc_attr( $state->code_sta ) . "\"";
				if ( $state->code_sta == $selected_state )
					echo " selected=\"selected\"";
				echo ">" . esc_attr( $state->name_sta ) . "</option>";
			}

			if ( $close_last_state_group ) {
				echo "</optgroup>";
			}

			echo "</select>";

			// DISPLAY STATE TEXT INPUT	
			echo "<input type=\"text\" name=\"ec_account_billing_information_state\" id=\"ec_account_billing_information_state\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $selected_state, ENT_QUOTES ) ) . "\"";
			if ( $state_found ) {
				echo " style=\"display:none;\"";
			}
			echo " />";

		} else {
			// Use the basic method of old
			if ( get_option( 'ec_option_use_state_dropdown' ) ) {
				$states = $this->mysqli->get_states();
				$selected_state = $GLOBALS['ec_user']->billing->state;

				echo "<select name=\"ec_account_billing_information_state\" id=\"ec_account_billing_information_state\" class=\"ec_account_billing_information_input_field\">";
				echo "<option value=\"0\">" . wp_easycart_language()->get_text( "account_billing_information", "account_billing_information_default_no_state" ) . "</option>";
				foreach ($states as $state) {
					echo "<option value=\"" . esc_attr( $state->code_sta ) . "\"";
					if ( $state->code_sta == $selected_state )
					echo " selected=\"selected\"";
					echo ">" . esc_attr( $state->name_sta ) . "</option>";
				}
				echo "</select>";
			} else {
				echo "<input type=\"text\" name=\"ec_account_billing_information_state\" id=\"ec_account_billing_information_state\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->state, ENT_QUOTES ) ) . "\" />";
			}
		}// Close if/else for state display type

	}

	public function display_account_billing_information_zip_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_zip\" id=\"ec_account_billing_information_zip\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->zip, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_country_input() {
		if ( get_option( 'ec_option_use_country_dropdown' ) ) {
			$countries = $GLOBALS['ec_countries']->countries;
			if ( $GLOBALS['ec_user']->billing->country )
				$selected_country = $GLOBALS['ec_user']->billing->country;
			else if ( count( $countries ) == 1 )
				$selected_country = $countries[0]->iso2_cnt;
			else if ( get_option( 'ec_option_default_country' ) )
				$selected_country = get_option( 'ec_option_default_country' );
			else
				$selected_country = $GLOBALS['ec_user']->billing->country;

			echo "<select name=\"ec_account_billing_information_country\" id=\"ec_account_billing_information_country\" class=\"ec_account_billing_information_input_field\">";
			echo "<option value=\"0\">" . wp_easycart_language()->get_text( "account_billing_information", "account_billing_information_default_no_country" ) . "</option>";
			foreach ($countries as $country) {
				echo "<option value=\"" . esc_attr( $country->iso2_cnt ) . "\"";
				if ( $country->iso2_cnt == $selected_country )
				echo " selected=\"selected\"";
				echo ">" . esc_attr( $country->name_cnt ) . "</option>";
			}
			echo "</select>";
		} else {
			echo "<input type=\"text\" name=\"ec_account_billing_information_country\" id=\"ec_account_billing_information_country\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->country, ENT_QUOTES ) ) . "\" />";
		}
	}

	public function display_account_billing_information_phone_input() {
		echo "<input type=\"text\" name=\"ec_account_billing_information_phone\" id=\"ec_account_billing_information_phone\" class=\"ec_account_billing_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->phone, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_billing_information_update_button( $button_text ) {
		echo "<input type=\"submit\" name=\"ec_account_billing_information_button\" id=\"ec_account_billing_information_button\" class=\"ec_account_billing_information_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_billing_information_update_click();\" />";
	}
	public function display_account_billing_information_cancel_link( $button_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard\" class=\"ec_account_billing_information_link\">" . "<input type=\"button\" name=\"ec_account_billing_information_button\" id=\"ec_account_billing_information_button\" class=\"ec_account_billing_information_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"window.location='" . esc_url_raw( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard'\" /></a>";
	}


	/* END BILLING INFORMATION FUNCTIONS */

	/* START SHIPPING INFORMATION FUNCTIONS */
	public function display_account_shipping_information() {
		if ( $this->is_page_visible( "shipping_information" ) ) {
			$this->display_shipping_information_page();
		}
	}

	public function display_shipping_information_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_shipping_information.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_shipping_information.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_shipping_information.php' );
	}

	public function display_account_shipping_information_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\">";
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" id=\"ec_account_shipping_information_form_action\" value=\"update_shipping_information\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-update-shipping-info-' . (int) $GLOBALS['ec_user']->user_id ) ) . "\" />";
	}

	public function display_account_shipping_information_form_end() {
		echo "</form>";
	}

	public function display_account_shipping_information_first_name_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_first_name\" id=\"ec_account_shipping_information_first_name\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->first_name, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_last_name_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_last_name\" id=\"ec_account_shipping_information_last_name\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->last_name, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_address_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_address\" id=\"ec_account_shipping_information_address\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_1, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_address2_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_address2\" id=\"ec_account_shipping_information_address2\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_2, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_city_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_city\" id=\"ec_account_shipping_information_city\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->city, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_state_input() {

		if ( get_option( 'ec_option_use_smart_states' ) ) {

			// DISPLAY STATE DROP DOWN MENU
			$states = $this->mysqli->get_states();
			$selected_state = $GLOBALS['ec_user']->shipping->get_value( "state" );
			$selected_country = $GLOBALS['ec_user']->shipping->get_value( "country2" );

			$current_country = "";
			$close_last_state = false;
			$state_found = false;
			$current_state_group = "";
			$close_last_state_group = false;

			foreach ($states as $state) {
				if ( $current_country != $state->iso2_cnt ) {
					if ( $close_last_state ) {
						echo "</select>";
					}
					echo "<select name=\"ec_account_shipping_information_state_" . esc_attr( $state->iso2_cnt ) . "\" id=\"ec_account_shipping_information_state_" . esc_attr( $state->iso2_cnt ) . "\" class=\"ec_account_shipping_information_input_field ec_shipping_state_dropdown\"";
					if ( $state->iso2_cnt != $selected_country ) {
						echo " style=\"display:none;\"";
					} else {
						$state_found = true;
					}
					echo ">";

					if ( $state->iso2_cnt == "CA" ) { // Canada
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_shipping_information", "cart_shipping_information_select_province" ) . "</option>";
					} else if ( $state->iso2_cnt == "GB" ) { // United Kingdom
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_shipping_information", "cart_shipping_information_select_county" ) . "</option>";
					} else if ( $state->iso2_cnt == "US" ) { //USA 
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_shipping_information", "cart_shipping_information_select_state" ) . "</option>";
					} else {
						echo "<option value=\"0\">" . wp_easycart_language()->get_text( "cart_shipping_information", "cart_shipping_information_select_other" ) . "</option>";
					}

					$current_country = $state->iso2_cnt;
					$close_last_state = true;
				}

				if ( $current_state_group != $state->group_sta && $state->group_sta != "" ) {
					if ( $close_last_state_group ) {
						echo "</optgroup>";
					}
					echo "<optgroup label=\"" . esc_attr( $state->group_sta ) . "\">";
					$current_state_group = $state->group_sta;
					$close_last_state_group = true;
				}

				echo "<option value=\"" . esc_attr( $state->code_sta ) . "\"";
				if ( $state->code_sta == $selected_state )
					echo " selected=\"selected\"";
				echo ">" . esc_attr( $state->name_sta ) . "</option>";
			}

			if ( $close_last_state_group ) {
				echo "</optgroup>";
			}

			echo "</select>";

			// DISPLAY STATE TEXT INPUT	
			echo "<input type=\"text\" name=\"ec_account_shipping_information_state\" id=\"ec_account_shipping_information_state\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $selected_state, ENT_QUOTES ) ) . "\"";
			if ( $state_found ) {
				echo " style=\"display:none;\"";
			}
			echo " />";

		} else {
			// Use the basic method of old
			if ( get_option( 'ec_option_use_state_dropdown' ) ) {
				$states = $this->mysqli->get_states();
				$selected_state = $GLOBALS['ec_user']->shipping->state;

				echo "<select name=\"ec_account_shipping_information_state\" id=\"ec_account_shipping_information_state\" class=\"ec_account_shipping_information_input_field\">";
				echo "<option value=\"0\">" . wp_easycart_language()->get_text( "account_shipping_information", "account_shipping_information_default_no_state" ) . "</option>";
				foreach ($states as $state) {
					echo "<option value=\"" . esc_attr( $state->code_sta ) . "\"";
					if ( $state->code_sta == $selected_state )
					echo " selected=\"selected\"";
					echo ">" . esc_attr( $state->name_sta ) . "</option>";
				}
				echo "</select>";
			} else {
				echo "<input type=\"text\" name=\"ec_account_shipping_information_state\" id=\"ec_account_shipping_information_state\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->state, ENT_QUOTES ) ) . "\" />";
			}
		}// Close if/else for state display type

	}

	public function display_account_shipping_information_zip_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_zip\" id=\"ec_account_shipping_information_zip\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->zip, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_country_input() {
		if ( get_option( 'ec_option_use_country_dropdown' ) ) {
			$countries = $GLOBALS['ec_countries']->countries;
			if ( $GLOBALS['ec_user']->shipping->country )
				$selected_country = $GLOBALS['ec_user']->shipping->country;
			else if ( count( $countries ) == 1 )
				$selected_country = $countries[0]->iso2_cnt;
			else if ( get_option( 'ec_option_default_country' ) )
				$selected_country = get_option( 'ec_option_default_country' );
			else
				$selected_country = $GLOBALS['ec_user']->shipping->country;

			echo "<select name=\"ec_account_shipping_information_country\" id=\"ec_account_shipping_information_country\" class=\"ec_account_shipping_information_input_field\">";
			echo "<option value=\"0\">" . wp_easycart_language()->get_text( "account_shipping_information", "account_shipping_information_default_no_country" ) . "</option>";
			foreach ($countries as $country) {
				echo "<option value=\"" . esc_attr( $country->iso2_cnt ) . "\"";
				if ( $country->iso2_cnt == $selected_country )
				echo " selected=\"selected\"";
				echo ">" . esc_attr( $country->name_cnt ) . "</option>";
			}
			echo "</select>";
		} else {
			echo "<input type=\"text\" name=\"ec_account_shipping_information_country\" id=\"ec_account_shipping_information_country\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->country, ENT_QUOTES ) ) . "\" />";
		}
	}

	public function display_account_shipping_information_phone_input() {
		echo "<input type=\"text\" name=\"ec_account_shipping_information_phone\" id=\"ec_account_shipping_information_phone\" class=\"ec_account_shipping_information_input_field\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->phone, ENT_QUOTES ) ) . "\" />";
	}

	public function display_account_shipping_information_update_button( $button_text ) {
		echo "<input type=\"submit\" name=\"ec_account_shipping_information_button\" id=\"ec_account_shipping_information_button\" class=\"ec_account_shipping_information_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_account_shipping_information_update_click();\" />";
	}

	public function display_account_shipping_information_cancel_link( $button_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard\" class=\"ec_account_shipping_information_link\">" ."<input type=\"button\" name=\"ec_account_shipping_information_button\" id=\"ec_account_shipping_information_button\" class=\"ec_account_shipping_information_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"window.location='" . esc_url_raw( $this->account_page . $this->permalink_divider ) . "ec_page=dashboard'\" /></a>";
	}


	/* END SHIPPING INFORMATION FUNCTIONS */


	/* START SUBSCRIPTIONS FUNCTIONS */
	public function display_account_subscriptions() {
		if ( $this->is_page_visible( "subscriptions" ) ) {
			$this->display_subscriptions_page();
		}
	}

	public function display_subscriptions_page() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_subscriptions.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_subscriptions.php' );
		else if ( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_subscriptions.php' ) )
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_subscriptions.php' );
	}

	public function using_subscriptions() {
		if ( ( get_option( 'ec_option_payment_process_method' ) == "stripe" || get_option( 'ec_option_payment_process_method' ) == "stripe_connect" ) && get_option( 'ec_option_show_account_subscriptions_link' ) ) {
			return true;
		} else {
			return false;
		}
	}
	/* END SUBSCRIPTIONS FUNCTIONS*/

	/* START SUBSCRIPTION DETAILS FUNCTIONS */
	public function display_account_subscription_details() {

		if ( $this->is_page_visible( "subscription_details" ) ) {
			$this->display_subscription_details_page();

		}

	}

	public function display_subscription_details_page() {
		if ( $this->subscription ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_subscription_details.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_subscription_details.php' );
			else if ( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_subscription_details.php' ) )
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_subscription_details.php' );

		} else {
			echo '<div style="float:left; width:100%; margin:50px 0; text-align:center;">' . wp_easycart_language()->get_text( 'account_subscriptions', 'account_subscriptions_none_found' ) . '</div>';

		}
	}

	/* END SUBSCRIPTION DETAILS FUNCTIONS */

	/* START PAYMENT METHODS FUNCTIONS */
	public function display_account_payment_methods() {
		if ( $this->is_page_visible( "payment_methods" ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_payment_methods.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_payment_methods.php' );
			else if ( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_payment_methods.php' ) )
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_payment_methods.php' );
		}
	}
	/* END PAYMENT METHODS FUNCTIONS*/

	/* START PAYMENT METHOD DETAILS FUNCTIONS */
	public function display_account_payment_method_details() {
		if ( $this->is_page_visible( "payment_method_details" ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_payment_method_details.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_payment_method_details.php' );
			else if ( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_payment_method_details.php' ) )
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_payment_method_details.php' );
		}
	}

	/* END PAYMENT METHOD DETAILS FUNCTIONS */

	/* START FORM ACTION FUNCTIONS */
	public function process_form_action( $action ) {
		wpeasycart_session()->handle_session();
		if ( $action == "login" )
			$this->process_login();
		else if ( $action == "register" )
			$this->process_register();
		else if ( $action == "retrieve_password" )
			$this->process_retrieve_password();
		else if ( $action == "update_personal_information" )
			$this->process_update_personal_information();
		else if ( $action == "update_password" )
			$this->process_update_password();
		else if ( $action == "update_billing_information" )
			$this->process_update_billing_information();
		else if ( $action == "update_shipping_information" )
			$this->process_update_shipping_information();
		else if ( $action == "logout" )
			$this->process_logout();
		else if ( $action == "update_subscription" )
			$this->process_update_subscription();
		else if ( $action == "cancel_subscription" )
			$this->process_cancel_subscription();
		else if ( $action == "order_create_account" )
			$this->process_order_create_account();

		do_action( 'wpeasycart_user_updated' );
	}

	private function process_login() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-login' ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$recaptcha_valid = true;
		if ( get_option( 'ec_option_enable_recaptcha' ) ) {

			if ( !isset( $_POST['ec_grecaptcha_response_login'] ) || $_POST['ec_grecaptcha_response_login'] == '' ) {
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=login&account_error=login_failed" );
				die();

			}

			$db = new ec_db_admin();
			$recaptcha_response = sanitize_text_field( $_POST['ec_grecaptcha_response_login'] );

			$data = array(
				"secret"	=> get_option( 'ec_option_recaptcha_secret_key' ),
				"response"	=> $recaptcha_response
			);

			$request = new WP_Http;
			$response = $request->request( 
				"https://www.google.com/recaptcha/api/siteverify", 
				array( 
					'method' => 'POST', 
					'body' => http_build_query( $data ),
					'timeout' => 30
				)
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$db->insert_response( 0, 1, "GOOGLE RECAPTCHA CURL ERROR", $error_message );
				$response = (object) array( "error" => $error_message );
			} else {
				$response = json_decode( $response['body'] );
				$db->insert_response( 0, 0, "Google Recaptcha Response", print_r( $response, true ) );
			}

			$recaptcha_valid = ( isset( $response->success ) && $response->success ) ? true : false;
		}

		if ( $recaptcha_valid ) {

			if ( isset( $_POST['ec_account_login_email_widget'] ) ) {
				$email = sanitize_email( $_POST['ec_account_login_email_widget'] );
			} else {
				$email = sanitize_email( $_POST['ec_account_login_email'] );
			}

			if ( isset( $_POST['ec_account_login_password_widget'] ) )
				$password = sanitize_text_field( $_POST['ec_account_login_password_widget'] );
			else
				$password = sanitize_text_field( $_POST['ec_account_login_password'] );

			$password_hash = md5( $password );
			$password_hash = apply_filters( 'wpeasycart_password_hash', $password_hash, $password );

			do_action( 'wpeasycart_pre_login_attempt', $email );
			$user = $this->mysqli->get_user_login( $email, $password, $password_hash );

			if ( $user && $user->user_level == "pending" ) {

				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=login&account_error=not_activated" );

			} else if ( $user ) {

				$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $user->billing_first_name;
				$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $user->billing_last_name;
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $user->billing_address_line_1;
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $user->billing_address_line_2;
				$GLOBALS['ec_cart_data']->cart_data->billing_city = $user->billing_city;
				$GLOBALS['ec_cart_data']->cart_data->billing_state = $user->billing_state;
				$GLOBALS['ec_cart_data']->cart_data->billing_zip = $user->billing_zip;
				$GLOBALS['ec_cart_data']->cart_data->billing_country = $user->billing_country;
				$GLOBALS['ec_cart_data']->cart_data->billing_phone = $user->billing_phone;

				$GLOBALS['ec_cart_data']->cart_data->shipping_selector = "";
				if ( $user->shipping_first_name != "" ) {
					$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = $user->shipping_first_name;
					$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = $user->shipping_last_name;
					$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $user->shipping_address_line_1;
					$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $user->shipping_address_line_2;
					$GLOBALS['ec_cart_data']->cart_data->shipping_city = $user->shipping_city;
					$GLOBALS['ec_cart_data']->cart_data->shipping_state = $user->shipping_state;
					$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $user->shipping_zip;
					$GLOBALS['ec_cart_data']->cart_data->shipping_country = $user->shipping_country;
					$GLOBALS['ec_cart_data']->cart_data->shipping_phone = $user->shipping_phone;

				} else {
					$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = $user->billing_first_name;
					$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = $user->billing_last_name;
					$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $user->billing_address_line_1;
					$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $user->billing_address_line_2;
					$GLOBALS['ec_cart_data']->cart_data->shipping_city = $user->billing_city;
					$GLOBALS['ec_cart_data']->cart_data->shipping_state = $user->billing_state;
					$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $user->billing_zip;
					$GLOBALS['ec_cart_data']->cart_data->shipping_country = $user->billing_country;
					$GLOBALS['ec_cart_data']->cart_data->shipping_phone = $user->billing_phone;
				}

				$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
				$GLOBALS['ec_cart_data']->cart_data->guest_key = "";

				$GLOBALS['ec_cart_data']->cart_data->user_id = $user->user_id;
				$GLOBALS['ec_cart_data']->cart_data->email = $email;
				$GLOBALS['ec_cart_data']->cart_data->username = $user->first_name . " " . $user->last_name;
				$GLOBALS['ec_cart_data']->cart_data->first_name = $user->first_name;
				$GLOBALS['ec_cart_data']->cart_data->last_name = $user->last_name;

				$GLOBALS['ec_cart_data']->save_session_to_db();

				if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
					$wp_user = wp_signon( array( 'user_login' => $email, 'user_password' => sanitize_text_field( $_POST['ec_account_login_password'] ) ), false );
				}

				wp_cache_flush();
				do_action( 'wpeasycart_login_success', $email );

				if ( isset( $_POST['ec_goto_page'] ) && $_POST['ec_goto_page'] == "store" ) {
					header( "location: " . $this->store_page );

				} else if ( isset( $_POST['ec_custom_login_redirect'] ) ) {

					if ( substr( esc_url_raw( $_POST['ec_custom_login_redirect'] ), 0, 7 ) == "http://" || substr( esc_url_raw( $_POST['ec_custom_login_redirect'] ), 0, 8 ) == "https://" )
						$redirect_url = htmlspecialchars( esc_url_raw( $_POST['ec_custom_login_redirect'] ), ENT_QUOTES );
					else
						$redirect_url = get_page_link( esc_url_raw( $_POST['ec_custom_login_redirect'] ) );

					header( "location: " . $redirect_url );


				} else if ( isset( $_POST['ec_goto_page'] ) && $_POST['ec_goto_page'] != "forgot_password" && $_POST['ec_goto_page'] != "register" && $_POST['ec_goto_page'] != "login" ) {
					$goto = $this->account_page . $this->permalink_divider . "ec_page=" . htmlspecialchars( sanitize_key( $_POST['ec_goto_page'] ), ENT_QUOTES );
					if ( isset( $_POST['ec_order_id'] ) )
						$goto .= "&order_id=" . (int) $_POST['ec_order_id'];
					if ( isset( $_POST['ec_subscription_id'] ) )
						$goto .= "&subscription_id=" . (int) $_POST['ec_subscription_id'];
					header( "location: " . $goto );

				} else {
					$page_id = (int) $_POST['ec_account_page_id'];
					$page_content = get_post( $page_id );
					if ( preg_match( "/\[ec_account redirect\=[\'\\\"](.*)[\'\\\"]\]/", $page_content->post_content, $matches ) ) {
						header( "location: " . $matches[1] );
					} else {
						header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=login_success" );
					}
				}

			} else {

				do_action( 'wpeasycart_login_failed', $email );
				if ( isset( $_POST['ec_goto_page'] ) && $_POST['ec_goto_page'] == "store" ) {
					header( "location: " . $this->store_page . $this->permalink_divider . "ec_page=login&account_error=login_failed" );

				} else {
					$page_id = (int) $_POST['ec_account_page_id'];
					do_action( 'wpeasycart_account_pre_login_failed_redirect', $email, $password );
					header( "location: " . get_permalink( $page_id ) . $this->permalink_divider . "ec_page=login&account_error=login_failed" );

				}

			}

		} else { // close recaptcha check
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=login&account_error=recaptcha_failed" );
			die();

		}

	}

	private function process_register() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-register' ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		
		if ( isset( $_POST['ec_account_register_email'] ) && isset( $_POST['ec_account_register_password'] ) && $_POST['ec_account_register_email'] != "" && $_POST['ec_account_register_password'] != "" ) {

			$recaptcha_valid = true;
			if ( get_option( 'ec_option_enable_recaptcha' ) ) {

				if ( !isset( $_POST['ec_grecaptcha_response_register'] ) || $_POST['ec_grecaptcha_response_register'] == '' ) {
					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=register&account_error=register_invalid" );
					die();
				}

				$db = new ec_db_admin();
				$recaptcha_response = sanitize_text_field( $_POST['ec_grecaptcha_response_register'] );
				$data = array(
					"secret"	=> get_option( 'ec_option_recaptcha_secret_key' ),
					"response"	=> $recaptcha_response
				);

				$request = new WP_Http;
				$response = $request->request( 
					"https://www.google.com/recaptcha/api/siteverify", 
					array( 
						'method' => 'POST', 
						'body' => http_build_query( $data ),
						'timeout' => 30
					)
				);
				if ( is_wp_error( $response ) ) {
					$error_message = $response->get_error_message();
					$db->insert_response( 0, 1, "GOOGLE RECAPTCHA CURL ERROR", $error_message );
					$response = (object) array( "error" => $error_message );
				} else {
					$response = json_decode( $response['body'] );
					$db->insert_response( 0, 0, "Google Recaptcha Response", print_r( $response, true ) );
				}

				$recaptcha_valid = ( isset( $response->success ) && $response->success ) ? true : false;
			}

			if ( $recaptcha_valid ) {

				$first_name = "";
				if ( isset( $_POST['ec_account_register_first_name'] ) )
					$first_name = sanitize_text_field( $_POST['ec_account_register_first_name'] );

				$last_name = "";
				if ( isset( $_POST['ec_account_register_last_name'] ) )
					$last_name = sanitize_text_field( $_POST['ec_account_register_last_name'] );

				$email = sanitize_email( $_POST['ec_account_register_email'] );
				$password = md5( $_POST['ec_account_register_password'] ); // XSS OK, Password Hashed Immediately
				$password = apply_filters( 'wpeasycart_password_hash', $password, sanitize_text_field( $_POST['ec_account_register_password'] ) );

				// Check if account already exists
				if ( $this->mysqli->does_user_exist( sanitize_email( $_POST['ec_account_register_email'] ) ) ) {
					header("location: " . $this->account_page . $this->permalink_divider . "ec_page=register&account_error=register_email_error");
					die();   
				}

				$is_subscriber = false;
				if ( isset( $_POST['ec_account_register_is_subscriber'] ) )
					$is_subscriber = true;

				$billing_id = 0;
				$vat_registration_number = "";

				// Insert billing address if enabled
				if ( get_option( 'ec_option_require_account_address' ) ) {
					$billing = array( "first_name" 	=> sanitize_text_field( $_POST['ec_account_billing_information_first_name'] ),
									  "last_name"	=> sanitize_text_field( $_POST['ec_account_billing_information_last_name'] ),
									  "address"		=> sanitize_text_field( $_POST['ec_account_billing_information_address'] ),
									  "city"		=> sanitize_text_field( $_POST['ec_account_billing_information_city'] ),
									  "zip_code"	=> sanitize_text_field( $_POST['ec_account_billing_information_zip'] ),
									  "country"		=> sanitize_text_field( $_POST['ec_account_billing_information_country'] ),
									);

					if ( isset( $_POST['ec_account_billing_information_state_' . $billing['country']] ) ) {
						$billing['state'] = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_state_' . sanitize_text_field( $billing['country'] )] ) );
					} else {
						$billing['state'] = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_state'] ) );
					}

					if ( isset( $_POST['ec_account_billing_information_company_name'] ) ) {
						$billing['company_name'] = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_company_name'] ) );
					} else {
						$billing['company_name'] = "";
					}

					if ( isset( $_POST['ec_account_billing_vat_registration_number'] ) ) {
						$vat_registration_number = stripslashes( sanitize_text_field( $_POST['ec_account_billing_vat_registration_number'] ) );
					}

					if ( isset( $_POST['ec_account_billing_information_address2'] ) ) {
						$billing['address2'] = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_address2'] ) );
					} else {
						$billing['address2'] = "";
					}

					if ( isset( $_POST['ec_account_billing_information_phone'] ) ) {
						$billing['phone'] = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_phone'] ) );
					} else {
						$billing['phone'] = "";
					}

					$billing_id = $this->mysqli->insert_address( $billing["first_name"], $billing["last_name"], $billing["address"], $billing["address2"], $billing["city"], $billing["state"], $billing["zip_code"], $billing["country"], $billing["phone"], $billing["company_name"] );

				}

				if ( isset( $_POST['ec_account_register_user_notes'] ) ) {
					$user_notes = stripslashes( sanitize_textarea_field( $_POST['ec_account_register_user_notes'] ) );
				} else {
					$user_notes = "";
				}

				// Insert the user
				if ( get_option( 'ec_option_require_email_validation' ) ) {
					// Send a validation email here.
					$this->send_validation_email( $email );
					$user_id = $this->mysqli->insert_user( $email, $password, $first_name, $last_name, $billing_id, 0, "pending", $is_subscriber, $user_notes, $vat_registration_number );
				} else {
					$user_id = $this->mysqli->insert_user( $email, $password, $first_name, $last_name, $billing_id, 0, "shopper", $is_subscriber, $user_notes, $vat_registration_number );
				}

				// Update the address user_id
				if ( get_option( 'ec_option_require_account_address' ) ) {
					$this->mysqli->update_address_user_id( $billing_id, $user_id );
				}

				// MyMail Hook
				if ( function_exists( 'mailster' ) ) {
					$subscriber_id = mailster('subscribers')->add(array(
						'firstname' => $first_name,
						'lastname' => $last_name,
						'email' => $email,
						'status' => 1,
					), false );
				}

				// Maybe insert WP user
				if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
					$user_name = 'wpeasycart_' . $user_id;
					$wp_user_id = wp_create_user( $user_name, sanitize_text_field( $_POST['ec_account_register_password'] ), $email );
					add_user_meta( $wp_user_id, 'wpeasycart_user_id', $user_id, true );
				}

				do_action( 'wpeasycart_account_added', $user_id, $email, sanitize_text_field( $_POST['ec_account_register_password'] ) );

				// Send registration email if needed
				if ( get_option( 'ec_option_send_signup_email' ) ) {

					$headers   = array();
					$headers[] = "MIME-Version: 1.0";
					$headers[] = "Content-Type: text/html; charset=utf-8";
					$headers[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
					$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
					$headers[] = "X-Mailer: PHP/" . phpversion();

					$message = wp_easycart_language()->get_text( "account_register", "account_register_email_message" ) . " " . $email;

					if ( get_option( 'ec_option_use_wp_mail' ) ) {
						wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( "account_register", "account_register_email_title" ), $message, implode("\r\n", $headers) );
					} else {
						$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
						$subject = wp_easycart_language()->get_text( "account_register", "account_register_email_title" );
						$mailer = new wpeasycart_mailer();
						$mailer->send_customer_email( $admin_email, $subject, $message );
					}

				}

				if ( $user_id ) {

					if ( get_option( 'ec_option_require_email_validation' ) ) {

						header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=login&account_success=validation_required" );

					} else {

						$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;
						$GLOBALS['ec_cart_data']->cart_data->email = $email;
						$GLOBALS['ec_cart_data']->cart_data->username = $first_name . " " . $last_name;
						$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
						$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;

						$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
						$GLOBALS['ec_cart_data']->cart_data->guest_key = "";

						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard" );

					}

				} else {

					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=register&account_error=register_email_error" );

				}

			} else { // close recaptcha check
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=register&account_error=recaptcha_failed" );
				die();

			}

		} else {

			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=register&account_error=register_invalid" );

		}

	}

	private function process_retrieve_password() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-retrieve-password' ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$email = sanitize_email( $_POST['ec_account_forgot_password_email'] );
		$new_password = $this->get_random_password();
		$password = md5( $new_password );
		$password = apply_filters( 'wpeasycart_password_hash', $password, $new_password );

		$success = $this->mysqli->reset_password( $email, $password );

		if ( $success ) {
			$this->send_new_password_email( $email, $new_password );
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=login&account_success=reset_email_sent" );
		} else {
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=register&account_error=no_reset_email_found" );
		}

	}

	private function process_update_personal_information() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-update-personal-info-' . (int) $GLOBALS['ec_user']->user_id ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$old_email = $GLOBALS['ec_cart_data']->cart_data->email;
		$user_id = $GLOBALS['ec_cart_data']->cart_data->user_id;
		$first_name = sanitize_text_field( $_POST['ec_account_personal_information_first_name'] );
		$last_name = sanitize_text_field( $_POST['ec_account_personal_information_last_name'] );
		$email = sanitize_email( $_POST['ec_account_personal_information_email'] );
		$email_other = sanitize_email( ( ( isset( $_POST['ec_account_personal_information_email_other'] ) ) ? $_POST['ec_account_personal_information_email_other'] : '' ) );
		if ( isset( $_POST['ec_account_personal_information_vat_registration_number'] ) ) {
			$vat_registration_number = sanitize_text_field( $_POST['ec_account_personal_information_vat_registration_number'] );
		} else {
			$vat_registration_number = "";
		}
		$is_subscriber = ( isset( $_POST['ec_account_personal_information_is_subscriber'] ) && (bool) $_POST['ec_account_personal_information_is_subscriber'] ) ? 1 : 0;

		$success = $this->mysqli->update_personal_information( $old_email, $user_id, $first_name, $last_name, $email, $is_subscriber, $vat_registration_number, $email_other );

		//Update Custom Fields if They Exist
		if ( count( $GLOBALS['ec_user']->customfields ) > 0 ) {
			for ( $i=0; $i<count( $GLOBALS['ec_user']->customfields ); $i++ ) {
				$this->mysqli->update_customfield_data( $GLOBALS['ec_user']->customfields[$i][0], sanitize_text_field( $_POST['ec_user_custom_field_' . $GLOBALS['ec_user']->customfields[$i][0]] ) );
			}
		}

		if ( $is_subscriber ) {
			do_action( 'wpeasycart_insert_subscriber', sanitize_email( $email ), $first_name, $last_name );
		} else { 
			do_action( 'wpeasycart_remove_subscriber', $email );
		}

		if ( $success !== false ) {
			$GLOBALS['ec_cart_data']->cart_data->email = $email;
			$GLOBALS['ec_cart_data']->cart_data->username = $first_name . " " . $last_name;
			$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
			$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;

			$GLOBALS['ec_cart_data']->save_session_to_db();

			if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
				$wp_user = get_user_by( 'email', $GLOBALS['ec_user']->email );
				if ( $wp_user ) {
					wp_update_user( array( 'ID' => $wp_user->ID, 'user_email' => $email ) );
				}
			}

			do_action( 'wpeasycart_account_updated', $user_id );

			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=personal_information_updated" );
		} else
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=personal_information&account_error=personal_information_update_error" );

	}

	private function process_update_password() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-update-password-' . (int) $GLOBALS['ec_user']->user_id ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$user_id = $GLOBALS['ec_user']->user_id;

		if ( apply_filters( 'wpeasycart_custom_verify_new_password', false, $_POST['ec_account_password_new_password'] ) ) { // XSS OK, Password Should not be sanitized
			do_action( 'wpeasycart_custom_verify_new_password_failed', $_POST['ec_account_password_new_password'] ); // XSS OK, Password Should not be sanitized

		} else if ( $_POST['ec_account_password_new_password'] != $_POST['ec_account_password_retype_new_password'] ) { // XSS OK, Password Should not be sanitized
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=password&account_error=password_no_match" );

		} else {
			$success = $this->mysqli->update_password( 
				$user_id, $_POST['ec_account_password_current_password'],  // XSS OK, Password Should not be sanitized
				$_POST['ec_account_password_retype_new_password'] // XSS OK, Password Should not be sanitized
			);

			if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
				$wp_user = get_user_by( 'email', $GLOBALS['ec_user']->email );
				if ( $wp_user ) {
					wp_set_password( $_POST['ec_account_password_retype_new_password'], $wp_user->ID ); // XSS OK, Password Should not be sanitized
				}
			}

			if ( $success ) {
				$GLOBALS['ec_cart_data']->save_session_to_db();
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=password_updated" );
			} else
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=password&account_error=password_wrong_current" );
		}
	}

	private function process_update_billing_information() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-update-billing-info-' . (int) $GLOBALS['ec_user']->user_id ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$country = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_country'] ) );

		$first_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_first_name'] ) );
		$last_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_last_name'] ) );
		if ( isset( $_POST['ec_account_billing_information_company_name'] ) ) {
			$company_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_company_name'] ) );
		} else {
			$company_name = "";
		}
		if ( isset( $_POST['ec_account_billing_information_vat_registration_number'] ) ) {
			$vat_registration_number = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_vat_registration_number'] ) );
		} else {
			$vat_registration_number = "";
		}
		$address = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_address'] ) );
		if ( isset( $_POST['ec_account_billing_information_address2'] ) ) {
			$address2 = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_address2'] ) );
		} else {
			$address2 = "";
		}

		$city = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_city'] ) );
		if ( isset( $_POST['ec_account_billing_information_state_' . $country] ) ) {
			$state = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_state_' . $country] ) );
		} else {
			$state = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_state'] ) );
		}

		$zip = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_zip'] ) );
		$phone = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_phone'] ) );

		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $first_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $last_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = $company_name;
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $address;
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $address2;
		$GLOBALS['ec_cart_data']->cart_data->billing_city = $city;
		$GLOBALS['ec_cart_data']->cart_data->billing_state = $state;
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = $zip;
		$GLOBALS['ec_cart_data']->cart_data->billing_country = $country;
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = $phone;

		if ( $first_name == $GLOBALS['ec_user']->billing->first_name && 
			$last_name == $GLOBALS['ec_user']->billing->last_name && 
			$company_name == $GLOBALS['ec_user']->billing->company_name && 
			$vat_registration_number == $GLOBALS['ec_user']->vat_registration_number && 
			$address == $GLOBALS['ec_user']->billing->address_line_1 && 
			$address2 == $GLOBALS['ec_user']->billing->address_line_2 && 
			$city == $GLOBALS['ec_user']->billing->city && 
			$state == $GLOBALS['ec_user']->billing->state && 
			$zip == $GLOBALS['ec_user']->billing->zip && 
			$country == $GLOBALS['ec_user']->billing->country &&
			$phone == $GLOBALS['ec_user']->billing->phone ) {

			$GLOBALS['ec_cart_data']->save_session_to_db();
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=billing_information_updated" );

		} else {
			$this->mysqli->update_user( $GLOBALS['ec_user']->user_id, $vat_registration_number );
			$address_id = $GLOBALS['ec_user']->billing_id;
			if ( $address_id )
				$success = $this->mysqli->update_user_address( $address_id, $first_name, $last_name, $address, $address2, $city, $state, $zip, $country, $phone, $company_name, $GLOBALS['ec_user']->user_id );
			else {
				$success = $this->mysqli->insert_user_address( $first_name, $last_name, $company_name, $address, $address2, $city, $state, $zip, $country, $phone, $GLOBALS['ec_user']->user_id, "billing" );
			}

			$GLOBALS['ec_cart_data']->save_session_to_db();

			do_action( 'wpeasycart_account_updated', $GLOBALS['ec_user']->user_id );

			if ( $success >= 0 )
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=billing_information_updated" );
			else
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=billing_information&account_error=billing_information_error" );

		}
	}

	private function process_update_shipping_information() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-update-shipping-info-' . (int) $GLOBALS['ec_user']->user_id ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$country = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_country'] ) );

		$first_name = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_first_name'] ) );
		$last_name = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_last_name'] ) );
		if ( isset( $_POST['ec_account_shipping_information_company_name'] ) ) {
			$company_name = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_company_name'] ) );
		} else {
			$company_name = "";
		}
		$address = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_address'] ) );
		if ( isset( $_POST['ec_account_shipping_information_address2'] ) ) {
			$address2 = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_address2'] ) );
		} else {
			$address2 = "";
		}

		$city = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_city'] ) );
		if ( isset( $_POST['ec_account_shipping_information_state_' . $country] ) ) {
			$state = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_state_' . $country] ) );
		} else {
			$state = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_state'] ) );
		}

		$zip = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_zip'] ) );
		$phone = stripslashes( sanitize_text_field( $_POST['ec_account_shipping_information_phone'] ) );

		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = $first_name;
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = $last_name;
		$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = $company_name;
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $address;
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $address2;
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = $city;
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = $state;
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $zip;
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = $country;
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = $phone;

		if ( $first_name == $GLOBALS['ec_user']->shipping->first_name && 
			$last_name == $GLOBALS['ec_user']->shipping->last_name && 
			$company_name == $GLOBALS['ec_user']->shipping->company_name && 
			$address == $GLOBALS['ec_user']->shipping->address_line_1 && 
			$address2 == $GLOBALS['ec_user']->shipping->address_line_2 && 
			$city == $GLOBALS['ec_user']->shipping->city && 
			$state == $GLOBALS['ec_user']->shipping->state && 
			$zip == $GLOBALS['ec_user']->shipping->zip && 
			$country == $GLOBALS['ec_user']->shipping->country &&
			$phone == $GLOBALS['ec_user']->shipping->phone ) {

			$GLOBALS['ec_cart_data']->save_session_to_db();
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=shipping_information_updated" );

		} else {

			$address_id = $GLOBALS['ec_user']->shipping_id;
			if ( $address_id )
				$success = $this->mysqli->update_user_address( $address_id, $first_name, $last_name, $address, $address2, $city, $state, $zip, $country, $phone, $company_name, $GLOBALS['ec_user']->user_id );
			else {
				$success = $this->mysqli->insert_user_address( $first_name, $last_name, $company_name, $address, $address2, $city, $state, $zip, $country, $phone, $GLOBALS['ec_user']->user_id, "shipping" );
			}

			$GLOBALS['ec_cart_data']->save_session_to_db();

			do_action( 'wpeasycart_account_updated', $GLOBALS['ec_user']->user_id );

			if ( $success >= 0 )
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=dashboard&account_success=shipping_information_updated" );
			else
				header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=shipping_information&account_error=shipping_information_error" );

		}
	}

	private function process_logout() {
		$account_logout_url = apply_filters( 'wp_easycart_account_logout_redirect_url', $this->account_page . $this->permalink_divider . "ec_page=login" );

		$GLOBALS['ec_cart_data']->cart_data->user_id = "";
		$GLOBALS['ec_cart_data']->cart_data->email = "";
		$GLOBALS['ec_cart_data']->cart_data->username = "";
		$GLOBALS['ec_cart_data']->cart_data->first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->last_name = "";

		$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
		$GLOBALS['ec_cart_data']->cart_data->guest_key = "";

		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_city = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_state = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_country = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = "";

		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = "";

		$GLOBALS['ec_cart_data']->cart_data->shipping_selector = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_method = "";
		$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = ""; 

		$GLOBALS['ec_cart_data']->cart_data->create_account = "";
		$GLOBALS['ec_cart_data']->cart_data->coupon_code = "";
		$GLOBALS['ec_cart_data']->cart_data->giftcard = "";

		$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = "";
		$GLOBALS['ec_cart_data']->cart_data->stripe_pi_client_secret = "";

		$GLOBALS['ec_cart_data']->save_session_to_db();
		wp_cache_flush();

		if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
			wp_logout();
		}

		header( "location: " . $account_logout_url );
	}

	private function process_update_subscription() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-update-subscription-' . (int) $_POST['subscription_id'] ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		global $wpdb;
		$products = $this->mysqli->get_product_list( $wpdb->prepare( " WHERE product.product_id = %d", (int) $_POST['ec_selected_plan'] ), "", "", "" );

		// Check that a product was found
		if ( count( $products ) > 0 ) {

			// Setup Re-usable vars
			$product = new ec_product( $products[0] );
			$payment_method = get_option( 'ec_option_payment_process_method' );
			$success = false;
			$plan_added = $product->stripe_plan_added;
			$quantity = 1;
			if ( isset( $_POST['ec_quantity'] ) )
				$quantity = (int) $_POST['ec_quantity'];

			// Check if we need to add the plan to Stripe
			if ( $payment_method == "stripe" ||$payment_method == "stripe_connect" ) {
				if ( $payment_method == "stripe" ) {
					$stripe = new ec_stripe();
				} else {
					$stripe = new ec_stripe_connect();
				}
		
				$plan_check = $stripe->get_plan( $product );
				
				if ( !$product->stripe_plan_added ) {
					$plan_added = $stripe->insert_plan( $product );
					$this->mysqli->update_product_stripe_added( $product->product_id );
				} else if ( !$plan_check || $plan_check->amount != (int) ( $product->price * 100 ) ) {
					$plan_added = $stripe->insert_plan( $product );
				}

				if ( !$plan_added ) {
					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id'] . "&account_error=subscription_update_failed&errcode=01" );
				}
			}


			//Upgrade and billing adjustment
			if ( isset( $_POST['ec_card_number'] ) && $_POST['ec_card_number'] != "" ) {
				$country = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_country'] ) );
				$first_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_first_name'] ) );
				$last_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_last_name'] ) );
				$company_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_company_name'] ) );
				$address = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_address'] ) );
				$address2 = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_address2'] ) );
				$city = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_city'] ) );
				if ( isset( $_POST['ec_account_billing_information_state_' . $country] ) )
					$state = sanitize_text_field( $_POST['ec_account_billing_information_state_' . $country] );
				else
					$state = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_state'] ) );
				$zip = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_zip'] ) );
				$phone = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_phone'] ) );

				$card_type = $this->get_payment_type( $this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ) );
				$card_holder_name = stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_first_name'] ) ) . " " . stripslashes( sanitize_text_field( $_POST['ec_account_billing_information_last_name'] ) );
				$card_number = sanitize_text_field( $_POST['ec_card_number'] );
				if ( isset( $_POST['ec_expiration_month'] ) && isset( $_POST['ec_expiration_year'] ) ) {
					$exp_month = sanitize_text_field( $_POST['ec_expiration_month'] );
					$exp_year = sanitize_text_field( $_POST['ec_expiration_year'] );
				} else {
					$exp_date = sanitize_text_field( $_POST['ec_cc_expiration'] );
					$exp_month = substr( $exp_date, 0, 2 );
					$exp_year = substr( $exp_date, 5 );
					if ( strlen( $exp_year ) == 2 ) {
						$exp_year = "20" . $exp_year;
					}
				}
				$security_code = sanitize_text_field( $_POST['ec_security_code'] );

				$address_id = $GLOBALS['ec_user']->billing_id;
				$this->mysqli->update_user_address( $address_id, $first_name, $last_name, $address, $address2, $city, $state, $zip, $country, $phone, $company_name, $GLOBALS['ec_user']->user_id );
				$GLOBALS['ec_user']->setup_billing_info_data( $first_name, $last_name, $address, $address2, $city, $state, $country, $zip, $phone, $company_name );
				$card = new ec_credit_card( $card_type, $card_holder_name, $card_number, $exp_month, $exp_year, $security_code );

				if ( $payment_method == "stripe" || $payment_method == "stripe_connect" ) {
					if ( $payment_method == "stripe" )
						$stripe = new ec_stripe();
					else
						$stripe = new ec_stripe_connect();

					$success = $stripe->update_subscription( $product, $this->user, $card, sanitize_text_field( $_POST['stripe_subscription_id'] ), NULL, $product->subscription_prorate, NULL, $quantity );
				}

				// Update our DB if the subscription was successfully updated
				if ( $success ) {
					$this->mysqli->update_subscription( (int) $_POST['subscription_id'], $this->user, $product, $card, $quantity );
					$this->mysqli->update_user_default_card( $this->user, $card );
				}

				$GLOBALS['ec_cart_data']->save_session_to_db();
				if ( $success ) {
					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id'] . "&account_success=subscription_updated" );
				} else {
					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id'] . "&account_error=subscription_update_failed&errcode=02" );
				}

			// Only an upgrade, no change to billing	
			} else {

				if ( $payment_method == "stripe" ) {
					$stripe = new ec_stripe();
					$success = $stripe->update_subscription( $product, $this->user, NULL, sanitize_text_field( $_POST['stripe_subscription_id'] ), NULL, $product->subscription_prorate, NULL, $quantity );
				} else if ( $payment_method == "stripe_connect" ) {
					$stripe = new ec_stripe_connect();
					$success = $stripe->update_subscription( $product, $this->user, NULL, sanitize_text_field( $_POST['stripe_subscription_id'] ), NULL, $product->subscription_prorate, NULL, $quantity );
				}

				if ( $success ) {
					$this->mysqli->upgrade_subscription( (int) $_POST['subscription_id'], $product, $quantity );
				}

				$GLOBALS['ec_cart_data']->save_session_to_db();
				if ( $success ) {
					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id'] . "&account_success=subscription_updated" );
				} else {
					header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id'] . "&account_error=subscription_update_failed&errcode=03" );
				}

			}// End Update of subscription

		} else { // No product has been found error

			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . (int) $_POST['subscription_id'] . "&account_error=subscription_update_failed&errcode=04" );

		}

	}// End process update subscription

	private function process_cancel_subscription() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_account_form_nonce'] ), 'wp-easycart-account-cancel-subscription-' . (int) $_POST['ec_account_subscription_id'] ) ) {
			header( "location: " . $this->account_page . $this->permalink_divider . 'account_error=invalid_nonce' );
			die();
		}

		$subscription_id = (int) $_POST['ec_account_subscription_id'];
		$subscription_row = $this->mysqli->get_subscription_row( $subscription_id );
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
			$stripe = new ec_stripe();
		else
			$stripe = new ec_stripe_connect();
		$cancel_success = $stripe->cancel_subscription( $this->user, $subscription_row->stripe_subscription_id );
		do_action( 'wpeasycart_subscription_cancelled', $this->user->user_id, $subscription_id );
		$GLOBALS['ec_cart_data']->save_session_to_db();
		if ( $cancel_success ) {
			$this->mysqli->cancel_subscription( $subscription_id );
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscriptions&account_success=subscription_canceled" );
		} else {
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . $subscription_id . "&account_error=subscription_cancel_failed" );
		}
	}

	private function process_order_create_account() {
		$order_id = (int) $_POST['order_id'];
		$email = sanitize_email( $_POST['email_address'] );
		$password = $_POST['ec_password']; // XSS OK. Password Hashed, not sanitized.

		$ec_db_admin = new ec_db_admin();
		$order_row = $ec_db_admin->get_order_row( $order_id );

		if ( $this->mysqli->does_user_exist( $email ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $order_id . "&ec_cart_error=email_exists" );
		} else if ( $order_row->user_id == 0 ) {
			$billing_id = $this->mysqli->insert_address( $order_row->billing_first_name, $order_row->billing_last_name, $order_row->billing_address_line_1, $order_row->billing_address_line_2, $order_row->billing_city, $order_row->billing_state, $order_row->billing_zip, $order_row->billing_country, $order_row->billing_phone );
			$shipping_id = $this->mysqli->insert_address( $order_row->shipping_first_name, $order_row->shipping_last_name, $order_row->shipping_address_line_1, $order_row->shipping_address_line_2, $order_row->shipping_city, $order_row->shipping_state, $order_row->shipping_zip, $order_row->shipping_country, $order_row->shipping_phone );

			$user_id = $this->mysqli->insert_user( $email, $password, $order_row->billing_first_name, $order_row->billing_last_name, $billing_id, $shipping_id, "shopper", 0 );
			$this->mysqli->update_order_user( $user_id, $order_id );

			// MyMail Hook
			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add(array(
					'firstname' => $order_row->billing_first_name,
					'lastname' => $order_row->billing_last_name,
					'email' => $email,
					'status' => 1,
				), false );
			}

			do_action( 'wpeasycart_account_added', $user_id, $email, $password );

			$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;
			$GLOBALS['ec_cart_data']->cart_data->email = $email;
			$GLOBALS['ec_cart_data']->cart_data->username = $order_row->billing_first_name . " " . $order_row->billing_last_name;
			$GLOBALS['ec_cart_data']->cart_data->first_name = $order_row->billing_first_name;
			$GLOBALS['ec_cart_data']->cart_data->last_name = $order_row->billing_last_name;

			$GLOBALS['ec_cart_data']->save_session_to_db();
			header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=order_details&order_id=" . $order_id . "&account_success=cart_account_created" );
		}
	}

	/* END FORM ACTION FUNCTIONS */

	private function send_new_password_email( $email, $new_password ) {

		$password_hash = md5( $new_password );
		$password_hash = apply_filters( 'wpeasycart_password_hash', $password_hash, $new_password );
		$user = $this->mysqli->get_user_login( $email, $new_password, $password_hash );

		$email_logo_url = get_option( 'ec_option_email_logo' );

		$storepageid = get_option('ec_option_storepage');
		if ( function_exists( 'icl_object_id' ) ) {
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		$store_page = get_permalink( $storepageid );
		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$store_page = $https_class->makeUrlHttps( $store_page );
		}

		if ( substr_count( $store_page, '?' ) ) {
			$permalink_divider = "&";
		} else {
			$permalink_divider = "?";
		}

		// Get receipt
		ob_start();
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_retrieve_password_email.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_retrieve_password_email.php' );	
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_retrieve_password_email.php' );
		$message = ob_get_contents();
		ob_end_clean();

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
		$headers[] = "X-Mailer: PHP/" . phpversion();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if ( $email_send_method == "1" ) {
			wp_mail( $email, wp_easycart_language()->get_text( "account_forgot_password_email", "account_forgot_password_email_title" ), $message, implode("\r\n", $headers));

		} else if ( $email_send_method == "0" ) {
			$to = $email;
			$subject = wp_easycart_language()->get_text( "account_forgot_password_email", "account_forgot_password_email_title" );
			$mailer = new wpeasycart_mailer();
			$mailer->send_customer_email( $to, $subject, $message );

		} else {
			do_action( 'wpeasycart_custom_forgot_password_email', stripslashes( get_option( 'ec_option_password_from_email' ) ), $email, "", wp_easycart_language()->get_text( "account_forgot_password_email", "account_forgot_password_email_title" ), $message );

		}

	}

	private function get_random_password() {
		$rand_chars = array( "A", "B", "C", "D", "E", "F", "G", "H", "I", "J" );
		$rand_password = $rand_chars[ rand( 0, 9 ) ] . $rand_chars[ rand( 0, 9 ) ] . $rand_chars[ rand( 0, 9 ) ] . $rand_chars[ rand( 0, 9 ) ] . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 );
		return $rand_password;
	}

	public function send_validation_email( $email ) {
		$key = md5( $email . "ecsalt" );

		// Get receipt
		$message = wp_easycart_language()->get_text( "account_validation_email", "account_validation_email_message" ) . "\r\n";
		$message .= "<a href=\"" . $this->account_page . $this->permalink_divider . "ec_page=activate_account&email=" . $email . "&key=" . $key . "\" target=\"_blank\">" . wp_easycart_language()->get_text( "account_validation_email", "account_validation_email_link" ) . "</a>";

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
		$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
		$headers[] = "X-Mailer: PHP/" . phpversion();

		$email_send_method = get_option( 'ec_option_use_wp_mail' );
		$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

		if ( $email_send_method == "1" ) {
			wp_mail( $email, wp_easycart_language()->get_text( "account_validation_email", "account_validation_email_title" ), $message, implode("\r\n", $headers));

		} else if ( $email_send_method == "0" ) {
			$to = $email;
			$subject = wp_easycart_language()->get_text( "account_validation_email", "account_validation_email_title" );
			$mailer = new wpeasycart_mailer();
			$mailer->send_customer_email( $to, $subject, $message );

		} else {
			do_action( 'wpeasycart_custom_register_verification_email', stripslashes( get_option( 'ec_option_password_from_email' ) ), $email, "", wp_easycart_language()->get_text( "account_validation_email", "account_validation_email_title" ), $message );

		}	

	}

	public function ec_display_payment_method_input( $select_one_text ) {
		echo "<select name=\"ec_cart_payment_type\" id=\"ec_cart_payment_type\" class=\"ec_cart_payment_information_input_select\">";

		echo "<option value=\"0\">" . esc_attr( $select_one_text ) . "</option>";

		if ( get_option('ec_option_use_visa') )
		echo "<option value=\"visa\">Visa</option>";

		if ( get_option('ec_option_use_delta') )
		echo "<option value=\"delta\">Visa Debit/Delta</option>";

		if ( get_option('ec_option_use_uke') )
		echo "<option value=\"uke\">Visa Electron</option>";

		if ( get_option('ec_option_use_discover') )
		echo "<option value=\"discover\">Discover</option>";

		if ( get_option('ec_option_use_mastercard') )
		echo "<option value=\"mastercard\">Mastercard</option>";

		if ( get_option('ec_option_use_mcdebit') )
		echo "<option value=\"mcdebit\">Debit Mastercard</option>";

		if ( get_option('ec_option_use_amex') )
		echo "<option value=\"amex\">American Express</option>";

		if ( get_option('ec_option_use_jcb') )
		echo "<option value=\"jcb\">JCB</option>";

		if ( get_option('ec_option_use_diners') )
		echo "<option value=\"diners\">Diners</option>";

		if ( get_option('ec_option_use_laser') )
		echo "<option value=\"laser\">Laser</option>";

		if ( get_option('ec_option_use_maestro') )
		echo "<option value=\"maestro\">Maestro</option>";

		echo "</select>";
	}

	public function ec_display_card_holder_name_input() {
		echo "<input type=\"text\" name=\"ec_card_holder_name\" id=\"ec_card_holder_name\" class=\"ec_cart_payment_information_input_text\" value=\"\" />";
	}

	public function ec_display_card_number_input() {
		echo "<input type=\"text\" name=\"ec_card_number\" id=\"ec_card_number\" class=\"ec_cart_payment_information_input_text\" value=\"\" />";
	}

	public function ec_display_card_expiration_month_input( $select_text ) {
		echo "<select name=\"ec_expiration_month\" id=\"ec_expiration_month\" class=\"ec_cart_payment_information_input_select\">";
		echo "<option value=\"0\">" . esc_attr( $select_text ) . "</option>";
		for ( $i=1; $i<=12; $i++ ) {
			echo "<option value=\"";
			if ( $i<10 )										$month = "0" . $i;
			else											$month = $i;
			echo esc_attr( $month ) . "\">" . esc_attr( $month ) . "</option>";
		}
		echo "</select>";
	}

	public function ec_display_card_expiration_year_input( $select_text ) {
		echo "<select name=\"ec_expiration_year\" id=\"ec_expiration_year\" class=\"ec_cart_payment_information_input_select\">";
		echo "<option value=\"0\">" . esc_attr( $select_text ) . "</option>";
		for ( $i=date( 'Y' ); $i < date( 'Y' ) + 15; $i++ ) {
			echo "<option value=\"" . esc_attr( $i ) . "\">" . esc_attr( $i ) . "</option>";	
		}
		echo "</select>";
	}

	public function ec_display_card_security_code_input() {
		echo "<input type=\"text\" name=\"ec_security_code\" id=\"ec_security_code\" class=\"ec_cart_payment_information_input_select\" value=\"\" />";
	}

	public function display_subscription_update_form_start() {
		echo "<form action=\"" . esc_attr( $this->account_page ) . "\" method=\"POST\" id=\"ec_submit_update_form\">";
	}

	public function display_subscription_update_form_end() {
		echo "<input type=\"hidden\" name=\"stripe_subscription_id\" id=\"stripe_subscription_id\" value=\"" . esc_attr( $this->subscription->get_stripe_id() ) . "\" />";
		echo "<input type=\"hidden\" name=\"subscription_id\" id=\"subscription_id\" value=\"" . esc_attr( $this->subscription->subscription_id ) . "\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_action\" value=\"update_subscription\" />";
		echo "<input type=\"hidden\" name=\"ec_account_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-account-update-subscription-' . (int) $this->subscription->subscription_id ) ) . "\" />";
		echo "</form>";
	}

	public function ec_account_display_credit_card_images() {

		/* Fallback only */

	}

	public function ec_account_display_card_holder_name_hidden_input() {
		echo "<input type=\"hidden\" name=\"ec_card_holder_name\" id=\"ec_card_holder_name\" class=\"ec_cart_payment_information_input_text\" value=\"" . esc_attr( $GLOBALS['ec_user']->billing->first_name . " " . $GLOBALS['ec_user']->billing->last_name ) . "\" />";
	}

	private function sanatize_card_number( $card_number ) {

		return preg_replace( "/[^0-9]/", "", $card_number );

	}

	private function get_payment_type( $card_number ) {

		if ( preg_match( "^5[1-5][0-9]{14}$", $card_number ) )
			return "mastercard";
		else if ( preg_match( "^4[0-9]{12}([0-9]{3})?$", $card_number ) )
			return "visa";
		else if ( preg_match( "^3[47][0-9]{13}$", $card_number ) )
			return "amex";
		else if ( preg_match( "^3(0[0-5]|[68][0-9])[0-9]{11}$", $card_number ) )
			return "diners";
		else if ( preg_match( "^6011[0-9]{12}$", $card_number ) )
			return "discover";
		else if ( preg_match( "^(3[0-9]{4}|2131|1800)[0-9]{11}$", $card_number ) )
			return "jcb";	
		else
			return "Credit Card";

	}

	public function get_payment_image_source( $image ) {

		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/" . $image ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/" . $image, EC_PLUGIN_DATA_DIRECTORY );
		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/" . $image, EC_PLUGIN_DIRECTORY );
		}

	}

	public function ec_cart_display_card_holder_name_hidden_input() {
		echo "<input type=\"hidden\" name=\"ec_card_holder_name\" id=\"ec_card_holder_name\" class=\"ec_cart_payment_information_input_text\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->first_name, ENT_QUOTES ) . " " . htmlspecialchars( $GLOBALS['ec_user']->billing->last_name, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_card_number_input() {
		echo "<input type=\"text\" name=\"ec_card_number\" id=\"ec_card_number\" class=\"ec_cart_payment_information_input_text\" value=\"\" autocomplete=\"off\" />";
	}

	public function ec_cart_display_card_expiration_month_input( $select_text ) {
		echo "<select name=\"ec_expiration_month\" id=\"ec_expiration_month\" class=\"ec_cart_payment_information_input_select\" autocomplete=\"off\">";
		echo "<option value=\"0\">" . esc_attr( $select_text ) . "</option>";
		for ( $i=1; $i<=12; $i++ ) {
			echo "<option value=\"";
			if ( $i<10 )										$month = "0" . $i;
			else											$month = $i;
			echo esc_attr( $month ) . "\">" . esc_attr( $month ) . "</option>";
		}
		echo "</select>";
	}

	public function ec_cart_display_card_expiration_year_input( $select_text ) {
		echo "<select name=\"ec_expiration_year\" id=\"ec_expiration_year\" class=\"ec_cart_payment_information_input_select\" autocomplete=\"off\">";
		echo "<option value=\"0\">" . esc_attr( $select_text ) . "</option>";
		for ( $i=date( 'Y' ); $i < date( 'Y' ) + 15; $i++ ) {
			echo "<option value=\"" . esc_attr( $i ) . "\">" . esc_attr( $i ) . "</option>";	
		}
		echo "</select>";
	}

	public function ec_cart_display_card_security_code_input() {
		echo "<input type=\"text\" name=\"ec_security_code\" id=\"ec_security_code\" class=\"ec_cart_payment_information_input_text\" value=\"\" autocomplete=\"off\" />";
	}

	public function get_stripe_intent_client_secret() {
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
			$stripe = new ec_stripe();
		else
			$stripe = new ec_stripe_connect();

		$response = $stripe->create_setup_intent( $GLOBALS['ec_user']->stripe_customer_id );
		return $response->client_secret;
	}

}

?>