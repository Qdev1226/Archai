<?php
global $wpdb;
$vat_tax_rate = $wpdb->get_row( "SELECT ec_taxrate.* FROM ec_taxrate WHERE tax_by_vat = 1 OR tax_by_single_vat = 1" );
$vat_type = '0';
$vat_by_country = ( $vat_tax_rate && $vat_tax_rate->tax_by_vat ) ? 1 : 0;
if( $vat_tax_rate && $vat_tax_rate->tax_by_single_vat ){
	$vat_type = 'tax_by_single_vat';

}else if( $vat_tax_rate && $vat_tax_rate->tax_by_vat ){
	$vat_type = 'tax_by_vat';
}
$show_vat = ( $vat_type != '0' ) ? 1 : 0;
$vat_included = ( $vat_tax_rate && $vat_tax_rate->vat_included ) ? 1 : 0;
$default_rate = ( $vat_tax_rate && $vat_tax_rate->vat_rate ) ? $vat_tax_rate->vat_rate : '0.00';
?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-site"></div>
		<span><?php esc_attr_e( 'Setup VAT Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'vat-setup' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'taxes', 'vat-setup');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">
		<?php $vat_options = array(
			(object) array(
				'label'		=> __( 'Global VAT Rate', 'wp-easycart' ),
				'value'		=> 'tax_by_single_vat'
			),
			(object) array(
				'label'		=> __( 'VAT by Country', 'wp-easycart' ),
				'value'		=> 'tax_by_vat'
			)
		); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_vat_tax', 'ec_admin_save_vat_type( jQuery( this ) ); ec_admin_update_vat_tax_display', $show_vat, __( 'Enable VAT', 'wp-easycart' ), __( 'Enabling this will allow you to apply a VAT rate by country or globally.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_vat_by_country', 'ec_admin_save_vat_type( jQuery( this ) ); ec_admin_update_vat_tax_display', $vat_by_country, __( 'VAT by Country', 'wp-easycart' ), __( 'Enabling this allows you to set a custom rate for each country.', 'wp-easycart' ), 'ec_vat_row1', $show_vat ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_vat_pricing_method', 'ec_admin_save_vat_tax_options( jQuery( this ) ); ec_admin_update_vat_tax_display', $vat_included, __( 'Include in Price', 'wp-easycart' ), __( 'Enabling this will include the VAT in the price of the product.', 'wp-easycart' ), 'ec_vat_row2', $show_vat ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_no_vat_on_shipping', 'ec_admin_save_vat_tax_options( jQuery( this ) ); ec_admin_update_vat_tax_display', !get_option( 'ec_option_no_vat_on_shipping' ), __( 'VAT on Shipping', 'wp-easycart' ), __( 'Enabling this will tax the shipping total and add to the VAT total.', 'wp-easycart' ), 'ec_vat_row3', $show_vat ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_validate_vat_registration_number', 'ec_admin_save_vat_tax_options( jQuery( this ) ); ec_admin_update_vat_tax_display', get_option( 'ec_option_validate_vat_registration_number' ), __( 'Validate VAT Registration Number', 'wp-easycart' ), __( 'Enabling this will allow you to verify VAT numbers with Vatlayer.', 'wp-easycart' ), 'ec_vat_row4', $show_vat ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_vatlayer_api_key', 'ec_admin_save_vat_tax_text_setting( jQuery( this ) ); ec_admin_update_vat_tax_display', get_option( 'ec_option_vatlayer_api_key' ), __( 'Vatlayer API Key', 'wp-easycart' ), __( 'This is required to validate your VAT registration numbers and provide a custom rate for businesses.', 'wp-easycart' ), '', 'ec_vat_row8', get_option( 'ec_option_validate_vat_registration_number' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_percentage( 'ec_default_vat_rate', 'ec_admin_save_vat_tax_text_setting( jQuery( this ) ); ec_admin_update_vat_tax_display', $default_rate, __( 'Default Rate', 'wp-easycart' ), __( 'This is the percent to apply by default to orders, for global this is the rate that will be charged.', 'wp-easycart' ), '0.00', 'ec_vat_row5', $show_vat ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_percentage( 'ec_option_vat_custom_rate', 'ec_admin_save_vat_tax_text_setting( jQuery( this ) ); ec_admin_update_vat_tax_display', get_option( 'ec_option_vat_custom_rate' ), __( 'Business Rate', 'wp-easycart' ), __( 'This is the VAT rate charged to those that enter a VAT registration number.', 'wp-easycart' ), '0.00', 'ec_vat_row6', $show_vat ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_vat_rounding', 'ec_admin_save_vat_tax_text_setting( jQuery( this ) ); ec_admin_update_vat_tax_display', get_option( 'ec_option_vat_rounding' ), __( 'Default Rounding', 'wp-easycart' ), __( 'This is the rounding point for tax, most use .01, but some countries require rounding by .05 or other.', 'wp-easycart' ), '0.01', 'ec_vat_row7', $show_vat ); ?>

		<div id="ec_admin_vat_country_rates"<?php if( !$show_vat || !$vat_by_country ){ ?> style="display:none;"<?php }?>>
			<hr style="float:left; width:100%; margin:10px 0;" />
			<h3 style="float:left; width:100%; margin:0;"><?php esc_attr_e( 'VAT Country Rates', 'wp-easycart' ); ?></h3>
			<p><?php esc_attr_e( 'This feature pulls the VAT rate from the country once the user enters their country on checkout. All countries have rates, but zero rates do not show in this list. Default rate only applies before the customer provides their country in checkout.', 'wp-easycart' ); ?></p>

			<?php
			$countries = $wpdb->get_results( "SELECT ec_country.name_cnt AS label, ec_country.id_cnt AS value FROM ec_country WHERE ec_country.ship_to_active = 1 ORDER BY ec_country.sort_order ASC" );

			$columns = array(
				array(
					'label'		=> __( 'Country', 'wp-easycart' ),
					'id'		=> 'ec_country_code',
					'type'		=> 'combo',
					'options'	=> $countries,
					'default'	=> array(
						'value'	=> '',
						'label' => __( 'Select One', 'wp-easycart' )
					),
					'readonly'  => true
				),
				array(
					'label'		=> __( 'Rate', 'wp-easycart' ),
					'id'		=> 'country_rate',
					'type'		=> 'percentage',
					'default'	=> '0.000'
				),
				array(
					'label'		=> __( 'Apply to Business', 'wp-easycart' ),
					'id'		=> 'vat_b2b_enabled',
					'type'		=> 'checkbox',
					'default'	=> '0'
				)
			);

			$data = $wpdb->get_results( "SELECT 
					ec_country.id_cnt AS id, 
					ec_country.id_cnt AS ec_country_code,
					ec_country.vat_rate_cnt AS country_rate,
					ec_country.vat_b2b_enabled
				FROM 
					ec_country
				WHERE 
					ec_country.vat_rate_cnt > 0 
				ORDER BY 
					ec_country.iso2_cnt ASC"
			);

			$actions = array( 
				'delete'		=> array(
					'label'		=> __( 'Delete', 'wp-easycart' ),
					'icon'		=> 'dashicons-trash',
					'function'	=> 'ec_admin_ajax_delete_vat_country_tax_rate'
				)
			);

			wp_easycart_admin( )->load_editable_table( 'ec_admin_vat_country_rates_section', $columns, $data, $actions, 'ec_admin_ajax_insert_vat_country_tax_rate', 'ec_admin_ajax_save_vat_country_tax_rate', array( 'delete' => 'Delete Selected' ), 'wp_easycart_tax_settings_nonce' );
			?>
		</div>

	</div>

</div>