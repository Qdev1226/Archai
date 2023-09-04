<?php
global $wpdb;
$global_tax_rate = $wpdb->get_row( "SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_all = 1" );
$global_tax_enabled = ( $global_tax_rate ) ? 1 : 0;
$global_tax_rate = ( $global_tax_rate ) ? $global_tax_rate->all_rate : '0.00';
?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-site"></div>
		<span><?php esc_attr_e( 'Setup Global Tax Rate', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'global-tax-setup' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'taxes', 'global-tax-setup'); ?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_global_tax', 'ec_admin_update_global_tax_display( ); ec_admin_update_global_tax_rate', $global_tax_enabled, __( 'Enable Global Tax', 'wp-easycart' ), __( 'Enabling this will allow you to apply a global tax rate to ALL orders.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_percentage( 'ec_global_tax_rate', 'ec_admin_update_global_tax_rate', $global_tax_rate, __( 'Tax Rate', 'wp-easycart' ), __( 'This is the percent to tax all orders.', 'wp-easycart' ), '0.00', 'ec_global_tax_row', $global_tax_enabled, true ); ?>
	</div>

</div>