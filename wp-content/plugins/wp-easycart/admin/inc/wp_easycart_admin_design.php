<?php
class wp_easycart_admin_design {

	public $design_file;
	public $settings_file;
	public $colorize_file;
	public $custom_css;
	public $product_details_options;
	public $cart_design_options;
	public $product_design_options;
	public $template_settings;

	public function __construct() {

		// Setup File Names 
		$this->design_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/design.php';
		$this->settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/settings.php';
		$this->colorize_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/design-colors.php';
		$this->custom_css = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/custom-css.php';
		$this->product_details_options = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/product-details-design-options.php';
		$this->cart_design_options = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/cart-design-options.php';
		$this->product_design_options = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/product-design-options.php';
		$this->template_settings = EC_PLUGIN_DIRECTORY . '/admin/template/settings/design/design-templates.php';

		// Actions
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_design_settings' ) );
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_color_settings' ) );
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_custom_css' ) );
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_cart_design_options' ) );
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_product_details_design_options' ) );
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_product_design_options' ) );
		add_action( 'wpeasycart_admin_design_settings', array( $this, 'load_design_template_settings' ) );
	}

	public function load_design() {
		include( $this->design_file );
	}

	public function load_design_settings() {
		include( $this->settings_file );
	}

	public function load_color_settings() {
		include( $this->colorize_file );
	}

	public function load_custom_css() {
		include( $this->custom_css );
	}

	public function load_cart_design_options() {
		include( $this->cart_design_options );
	}

	public function load_product_details_design_options() {
		include( $this->product_details_options );
	}

	public function load_product_design_options() {
		include( $this->product_design_options );
	}

	public function load_design_template_settings() {
		include( $this->template_settings );
	}

	public function save_design_settings() {
		$options = array(
			'ec_option_use_custom_post_theme_template', 'ec_option_match_store_meta', 'ec_option_default_quick_view', 'ec_option_default_dynamic_sizing', 'ec_option_enabled_minified_scripts',
			'ec_option_default_product_border', 'ec_option_default_product_rounded_corners', 'ec_option_default_product_rounded_corners_tl',
			'ec_option_default_product_rounded_corners_tr', 'ec_option_default_product_rounded_corners_bl', 'ec_option_default_product_rounded_corners_br',
		);
		$options_text = array( 
			'ec_option_base_theme', 'ec_option_base_layout', 'ec_option_font_main', 'ec_option_font_custom', 'ec_option_details_main_color', 
			'ec_option_details_second_color', 'ec_option_admin_color', 'ec_option_use_dark_bg', 'ec_option_cart_columns_desktop', 
			'ec_option_cart_columns_laptop', 'ec_option_cart_columns_tablet_wide', 'ec_option_cart_columns_tablet', 'ec_option_cart_columns_smartphone', 
			'ec_option_details_columns_desktop', 'ec_option_details_columns_laptop', 'ec_option_details_columns_tablet_wide', 'ec_option_details_columns_tablet', 
			'ec_option_details_columns_smartphone', 'ec_option_default_product_type', 'ec_option_default_product_image_hover_type', 'ec_option_default_product_image_effect_type',
			'ec_option_default_desktop_columns', 'ec_option_default_desktop_image_height',
			'ec_option_default_laptop_columns', 'ec_option_default_laptop_image_height', 'ec_option_default_tablet_wide_columns', 'ec_option_default_tablet_wide_image_height',
			'ec_option_default_tablet_columns', 'ec_option_default_tablet_image_height', 'ec_option_default_smartphone_columns', 'ec_option_default_smartphone_image_height',
			'ec_option_default_product_align',
		);

		if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
			$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
		} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
		} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_custom_css' ) {
			update_option( 'ec_option_custom_css', wp_easycart_escape_html( wp_unslash( $_POST['val'] ) ) );
		} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_no_rounded_corners' ) {
			$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 0 : 1;
			update_option( 'ec_option_no_rounded_corners', $val );
		} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_hide_live_editor' ) {
			$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 0 : 1;
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
		} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_default_product_visible_options' ) {
			$val = ( isset( $_POST['val'] ) && is_array( $_POST['val'] ) ) ? implode( ',', $_POST['val'] ) : '';
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
		}
	}
}

add_action( 'wp_ajax_ec_admin_ajax_save_design_settings', 'ec_admin_ajax_save_design_settings' );
function ec_admin_ajax_save_design_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-design-settings' ) ) {
		return false;
	}

	$design_settings = new wp_easycart_admin_design();
	$design_settings->save_design_settings();
	die();
}
