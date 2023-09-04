<div class="ec_admin_settings_panel ec_admin_settings_shipping_section ec_admin_settings_<?php if( wp_easycart_admin( )->settings->shipping_method == "fraktjakt" ){ ?>show<?php }else{?>hide<?php }?>" id="fraktjakt">
	
	<div class="ec_admin_list_line_item">

		<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding">

			<div class="ec_admin_settings_label"><div class="dashicons-before dashicons-admin-site"></div><span><?php esc_attr_e( 'Fraktjakt Setup', 'wp-easycart' ); ?></span></div>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_customer_id', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_customer_id, __( 'Customer ID', 'wp-easycart' ), __( 'The customer ID from your account.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_customer_id_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_login_key', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_login_key, __( 'Login Key', 'wp-easycart' ), __( 'The login key from your account.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_login_key_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_conversion_rate', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_conversion_rate, __( 'Conversion Rate', 'wp-easycart' ), __( 'The login key from your account.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_conversion_rate_row', true, false ); ?>

			<div class="ec_admin_settings_input ec_admin_settings_fraktjakt_section">
				<span><input type="checkbox" name="fraktjakt_test_mode" id="fraktjakt_test_mode" value="1"<?php if( wp_easycart_admin( )->settings->fraktjakt_test_mode ){ ?> checked="checked"<?php }?> /><?php esc_attr_e( 'Test Mode', 'wp-easycart' ); ?></span>
			</div>

			<div class="ec_admin_settings_label"><div class="dashicons-before dashicons-location-alt"></div><span><?php esc_attr_e( 'Initial Shipping Address', 'wp-easycart' ); ?></span></div>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_address', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_address, __( 'Ship From: Address', 'wp-easycart' ), __( 'The address you are shipping from.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_address_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_city', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_city, __( 'Ship From: City', 'wp-easycart' ), __( 'The city you are shipping from.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_city_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_state', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_state, __( 'Ship From: State', 'wp-easycart' ), __( 'This must be a 2 digit state code.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_state_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_zip', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_zip, __( 'Ship From: Postal Code', 'wp-easycart' ), __( 'The postal code you are shipping from.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_zip_row', true, false ); ?>

			<?php wp_easycart_admin( )->load_toggle_group_text( 'fraktjakt_country', 'ec_admin_save_fraktjakt_options', wp_easycart_admin( )->settings->fraktjakt_country, __( 'Ship From: Country Code', 'wp-easycart' ), __( 'This must be a 2 digit country code.', 'wp-easycart' ), '', 'ec_admin_fraktjakt_country_row', true, false ); ?>

		</div>

	</div>
	
</div>