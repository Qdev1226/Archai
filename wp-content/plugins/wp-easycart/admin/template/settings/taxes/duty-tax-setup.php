<?php
global $wpdb;
$duty_tax_rate = $wpdb->get_row( "SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_duty = 1" );
$duty_tax_enabled = ( $duty_tax_rate ) ? 1 : 0;
$duty_exempt_country = ( $duty_tax_rate ) ? $duty_tax_rate->duty_exempt_country_code : '';
$duty_rate = ( $duty_tax_rate ) ? $duty_tax_rate->duty_rate : '0.00';
$countries = $wpdb->get_results( "SELECT ec_country.iso2_cnt AS value, name_cnt AS label FROM ec_country" );
?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-site"></div>
		<span><?php esc_attr_e( 'Setup Duty Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'taxes', 'duty-tax-setup') ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'taxes', 'duty-tax-setup'); ?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_duty_tax', 'ec_admin_update_duty_tax_display( ); ec_admin_update_duty_tax_rate_1', $duty_tax_enabled, __( 'Enable Duty', 'wp-easycart' ), __( 'Enabling this will allow you to apply a duty rate to any order outside of your own country.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_duty_exempt_country_code', 'ec_admin_update_duty_tax_rate_2', $duty_exempt_country, __( 'Duty Exempt Country', 'wp-easycart' ), __( 'Duty will not apply to this country.', 'wp-easycart' ), $countries, 'ec_duty_tax_country_row', $duty_tax_enabled, false, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_percentage( 'ec_duty_tax_rate', 'ec_admin_update_duty_tax_rate_3', $duty_rate, __( 'Duty Rate', 'wp-easycart' ), __( 'This is the percent to apply on duty applicable orders.', 'wp-easycart' ), '0.00', 'ec_duty_tax_row', $duty_tax_enabled, true ); ?>
	</div>

</div>