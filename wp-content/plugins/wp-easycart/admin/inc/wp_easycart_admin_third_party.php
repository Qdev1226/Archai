<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_third_party' ) ) :

	final class wp_easycart_admin_third_party {

		protected static $_instance = null;

		public $google_analytics_design_file;
		public $settings_file;
		public $upgrade_file;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->google_analytics_design_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/third-party/google-analytics.php';
			$this->settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/third-party/settings.php';
			$this->upgrade_file = EC_PLUGIN_DIRECTORY . '/admin/template/upgrade/upgrade-simple.php';

			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_analytics_design' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_ga4_design' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_adwords_design' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_facebook_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_shareasale_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_mailerlite_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_convertkit_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_activecampaign_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_google_merchant' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_amazon_settings' ) );
			add_action( 'wpeasycart_admin_third_party', array( $this, 'load_deconetwork_settings' ) );

			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'google-import-complete' == $_GET['success'] ) {
				$messages[] = __( 'Google Merchant CSV Successfully Uploaded', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_third_party() {
			include( $this->settings_file );
		}

		public function load_google_analytics_design() {
			include( $this->google_analytics_design_file );
		}

		public function load_google_ga4_design() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Google Analytics GA4 Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Google Analytics GA4 for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_google_adwords_design() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Google Adwords Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Google Adwords for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_google_merchant() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Google Merchant', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Google Merchant for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_amazon_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Amazon S3 Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Amazon S3 for Download Products', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_deconetwork_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Deconetwork Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Deconetwork for Customizable Products', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_facebook_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Facebook Pixel Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Facebook Pixel for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_shareasale_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'ShareASale Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable ShareASale for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_mailerlite_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Mailer Lite Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Mailer Lite for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_convertkit_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'ConvertKit Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable ConvertKit for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function load_activecampaign_settings() {
			$upgrade_icon = 'dashicons-admin-generic';
			$upgrade_title = __( 'Active Campaign Setup', 'wp-easycart' );
			$upgrade_subtitle = '';
			$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( 'Enable Active Campaign for Your Cart', 'wp-easycart' );
			$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
			include( $this->upgrade_file );
		}

		public function save_google_analytics() {
			$ec_option_googleanalyticsid = 'UA-XXXXXXX-X';
			if ( isset( $_POST['ec_option_googleanalyticsid'] ) ) {
				$ec_option_googleanalyticsid = sanitize_text_field( wp_unslash( $_POST['ec_option_googleanalyticsid'] ) );
			}
			update_option( 'ec_option_googleanalyticsid', $ec_option_googleanalyticsid );
		}
	}
endif;

function wp_easycart_admin_third_party() {
	return wp_easycart_admin_third_party::instance();
}
wp_easycart_admin_third_party();

add_action( 'wp_ajax_ec_admin_ajax_save_google_analytics', 'ec_admin_ajax_save_google_analytics' );
function ec_admin_ajax_save_google_analytics() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-third-party-settings' ) ) {
		return false;
	}

	wp_easycart_admin_third_party()->save_google_analytics();
	die();
}
