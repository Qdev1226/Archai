<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-feedback"></div>
		<span><?php esc_attr_e( 'Checkout Form', 'wp-easycart' ); ?></span>
		<?php wp_easycart_admin( )->preloader->print_saved_icon( "ec_admin_checkout_form_settings_saved" ); ?>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'checkout', 'form-settings' ) ); ?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart' ); ?>">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'checkout', 'form-settings');?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_country_dropdown', 'wpeasycart_admin_update_country_view( ); ec_admin_save_cart_settings_options', get_option( 'ec_option_use_country_dropdown' ), __( 'Country: Combo Box', 'wp-easycart' ), __( 'Display the country input as a select box and prevent user input errors (preferred method).', 'wp-easycart' ) ); ?>

		<?php $country_options = array( ); ?>
		<?php foreach( wp_easycart_admin( )->countries as $country ){
			$country_options[] = (object) array(
				'value'	=> $country->iso2_cnt,
				'label'	=> $country->name_cnt
			);
		} ?>
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_country', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_default_country' ), __( 'Country: Default Selection', 'wp-easycart' ), __( 'If no country has been selected, it will default to this selection.', 'wp-easycart' ), $country_options, 'ec_admin_default_country_row', ( ( get_option( 'ec_option_use_country_dropdown' ) == "1" ) ? true : false ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_display_country_top', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_display_country_top' ), __( 'Country: Start of Form', 'wp-easycart' ), __( 'Allows customers to select state/province/territory based on country selected (preferred method).', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_smart_states', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_use_smart_states' ), __( 'State: Change with Country', 'wp-easycart' ), __( 'The state box updates based on the country selected (preferred method).', 'wp-easycart' ), 'ec_option_use_smart_states_row', ( ( get_option( 'ec_option_payment_process_method' ) != 'square' && $GLOBALS['ec_setting']->get_shipping_method( ) != 'live' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_state_dropdown', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_use_state_dropdown' ), __( 'State: Combo Box', 'wp-easycart' ), __( 'Display the state input as a select box and prevent user input errors (preferred method).', 'wp-easycart' ), 'ec_option_use_state_dropdown_row', ( ( get_option( 'ec_option_payment_process_method' ) != 'square' && $GLOBALS['ec_setting']->get_shipping_method( ) != 'live' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_address2', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_use_address2' ), __( 'Address Line 2', 'wp-easycart' ), __( 'Enable to allow customers to enter a second line in the address on checkout.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_collect_user_phone', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_collect_user_phone' ), __( 'Phone Number', 'wp-easycart' ), __( 'Enable to collect the phone number on checkout.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_user_phone_required', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_user_phone_required' ), __( 'Require Phone Number', 'wp-easycart' ), __( 'When phone number is collected, you may require it or not require it using this option.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_company_name', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_enable_company_name' ), __( 'Company Name', 'wp-easycart' ), __( 'Enable this to allow customers to enter an optional company name.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_collect_vat_registration_number', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_collect_vat_registration_number' ), __( 'VAT Registration Number', 'wp-easycart' ), __( 'Enabling requires businesses to enter their VAT number on checkout.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_user_order_notes', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_user_order_notes' ), __( 'Customer Notes', 'wp-easycart' ), __( 'Allows customers to enter optional notes during checkout.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_require_terms_agreement', 'wpeasycart_admin_update_terms_link_view( ); ec_admin_save_cart_settings_options', get_option( 'ec_option_require_terms_agreement' ), __( 'Terms Agreement', 'wp-easycart' ), __( 'Enable a required terms agreement box, be sure to add your terms URL in the checkout options below.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_terms_link', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_terms_link' ), __( 'Terms URL', 'wp-easycart' ), __( 'Enter your full URL of your terms web page. this is something you will need to setup on your website.', 'wp-easycart' ), 'https://www.yoursite.com/terms', 'ec_admin_terms_link_row', ( ( get_option( 'ec_option_require_terms_agreement' ) == "1" ) ? true : false ), true ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_privacy_link', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_privacy_link' ), __( 'Privacy Policy URL', 'wp-easycart' ), __( 'Enter your full URL of your privacy policy web page. this is something you will need to setup on your website.', 'wp-easycart' ), 'https://www.yoursite.com/privacy', 'ec_admin_privacy_link_row', ( ( get_option( 'ec_option_require_terms_agreement' ) == "1" ) ? true : false ), true ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_contact_name', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_use_contact_name' ), __( 'Contact Name for Account Creation', 'wp-easycart' ), __( 'Enable to require a first and last name while creating an account on checkout.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_skip_shipping_page', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_skip_shipping_page' ), __( 'Bypass Shipping Selection Page', 'wp-easycart' ), __( 'Enable this option if you wish to bypass the shipping selection page during the checkout process.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_allow_guest', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_allow_guest' ), __( 'Guest Checkout', 'wp-easycart' ), __( 'Enabling guest checkout gives users the option to submit an order without a password or account (some products require accounts such as downloads, subscriptions, or gift cards)', 'wp-easycart' ) ); ?>

		<?php do_action( 'wpeasycart_admin_checkout_form_fields_end' ); ?>

	</div>
</div>