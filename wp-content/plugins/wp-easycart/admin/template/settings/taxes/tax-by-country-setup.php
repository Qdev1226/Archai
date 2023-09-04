<?php global $wpdb; ?>
<div class="ec_admin_list_line_item ec_admin_demo_data_line" style="float:left;">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_tax_by_country_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-welcome-add-page"></div>
		<span><?php esc_attr_e( 'Tax By Country', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'tax-by-country-setup' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'taxes', 'tax-by-country-setup');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_tax_section">

		<?php
		$countries = $wpdb->get_results( "SELECT ec_country.name_cnt AS label, ec_country.iso2_cnt AS value FROM ec_country WHERE ec_country.ship_to_active = 1 ORDER BY ec_country.sort_order ASC" );

		$columns = array(
			array(
				'label'		=> __( 'Country', 'wp-easycart' ),
				'id'		=> 'ec_country_code',
				'type'		=> 'combo',
				'options'	=> $countries,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'Select One', 'wp-easycart' )
				)
			),
			array(
				'label'		=> __( 'Rate', 'wp-easycart' ),
				'id'		=> 'country_rate',
				'type'		=> 'percentage',
				'default'	=> '0.000'
			)
		);

		$data = $wpdb->get_results( "SELECT 
				ec_taxrate.taxrate_id AS id, 
				ec_taxrate.country_code AS ec_country_code, 
				ec_taxrate.country_rate 
			FROM 
				ec_taxrate
			WHERE 
				ec_taxrate.tax_by_country = 1 
			ORDER BY 
				ec_taxrate.country_code ASC"
		);

		$actions = array( 
			'delete'		=> array(
				'label'		=> __( 'Delete', 'wp-easycart' ),
				'icon'		=> 'dashicons-trash',
				'function'	=> 'ec_admin_ajax_delete_country_tax_rate'
			)
		);

		wp_easycart_admin( )->load_editable_table( 'wp_easycart_country_tax_table', $columns, $data, $actions, 'ec_admin_ajax_insert_country_tax_rate', 'ec_admin_ajax_save_country_tax_rate', array( 'delete' => 'Delete Selected' ), 'wp_easycart_tax_settings_nonce' ); 
		?>

	</div>

</div>