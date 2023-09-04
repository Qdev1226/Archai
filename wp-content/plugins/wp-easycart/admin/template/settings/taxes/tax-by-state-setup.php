<?php global $wpdb; ?>
<div class="ec_admin_list_line_item ec_admin_demo_data_line" style="float:left;">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_tax_by_state_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-welcome-add-page"></div>
		<span><?php esc_attr_e( 'Tax By State', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'tax-by-state-setup' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'taxes', 'tax-by-state-setup');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_tax_section">

		<?php
		$states = $wpdb->get_results( "SELECT ec_state.name_sta AS label, ec_state.id_sta AS value, ec_country.iso2_cnt as group_id FROM ec_state LEFT JOIN ec_country ON ec_country.id_cnt = ec_state.idcnt_sta WHERE ec_state.ship_to_active = 1 ORDER BY ec_country.sort_order ASC, ec_state.name_sta ASC" );

		$columns = array(
			array(
				'label'		=> __( 'State', 'wp-easycart' ),
				'id'		=> 'ec_state_code',
				'type'		=> 'combo',
				'options'	=> $states,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'Select One', 'wp-easycart' )
				)
			),
			array(
				'label'		=> __( 'Country', 'wp-easycart' ),
				'id'		=> 'group_id',
				'type'		=> 'read-only',
				'default'	=> ''
			),
			array(
				'label'		=> __( 'Rate', 'wp-easycart' ),
				'id'		=> 'state_rate',
				'type'		=> 'percentage',
				'default'	=> '0.000'
			)
		);

		$data = $wpdb->get_results( "SELECT 
				ec_taxrate.taxrate_id AS id, 
				ec_state.id_sta AS ec_state_code, 
				ec_taxrate.country_code AS group_id, 
				ec_taxrate.state_rate 
			FROM 
				ec_taxrate 
				LEFT JOIN ec_country ON (
					ec_country.iso2_cnt = ec_taxrate.country_code
				)
				LEFT JOIN ec_state ON ( 
					ec_state.code_sta = ec_taxrate.state_code AND 
					ec_state.idcnt_sta = ec_country.id_cnt
				) 
			WHERE 
				ec_taxrate.tax_by_state = 1 
			ORDER BY 
				ec_taxrate.state_code ASC"
		);

		$actions = array( 
			'delete'		=> array(
				'label'		=> __( 'Delete', 'wp-easycart' ),
				'icon'		=> 'dashicons-trash',
				'function'	=> 'ec_admin_ajax_delete_state_tax_rate'
			)
		);

		wp_easycart_admin( )->load_editable_table( 'wp_easycart_state_tax_table', $columns, $data, $actions, 'ec_admin_ajax_insert_state_tax_rate', 'ec_admin_ajax_save_state_tax_rate', array( 'delete' => 'Delete Selected' ), 'wp_easycart_tax_settings_nonce' ); 
		?>

	</div>

</div>