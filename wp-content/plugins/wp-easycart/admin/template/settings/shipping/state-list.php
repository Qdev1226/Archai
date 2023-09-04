<?php global $wpdb; ?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-networking"></div>
		<span><?php esc_attr_e( 'States Shipping List', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-settings', 'state-list' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-rates', 'state-list');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_tax_section">

		<?php 
		$data = $wpdb->get_results( "SELECT ec_state.id_sta AS id, ec_state.name_sta, ec_country.name_cnt, ec_state.ship_to_active FROM ec_state LEFT JOIN ec_country ON ec_state.idcnt_sta = ec_country.id_cnt ORDER BY ec_state.sort_order ASC" );

		$columns = array(
			array(
				'label'		=> __( 'State', 'wp-easycart' ),
				'id'		=> 'name_sta',
				'type'		=> 'read-only',
				'readonly'  => true,
				'default'	=> '',
				'labelpos'  => 'left',
				'width'     => '40%'
			),
			array(
				'label'		=> __( 'Country', 'wp-easycart' ),
				'id'		=> 'name_cnt',
				'type'		=> 'read-only',
				'readonly'  => true,
				'default'	=> '',
				'labelpos'  => 'left',
				'width'     => '40%'
			),
			array(
				'label'		=> __( 'Enabled', 'wp-easycart' ),
				'id'		=> 'ship_to_active',
				'type'		=> 'checkbox',
				'default'	=> '0',
				'readonly'  => false,
				'labelpos'  => 'center',
				'width'     => '20%',
				'sort'      => false
			)
		);

		$actions = array( );

		$bulk_actions = array( );

		wp_easycart_admin( )->load_editable_table( 'wp_easycart_state_ship_table', $columns, $data, $actions, 'ec_admin_ajax_save_state_list', 'ec_admin_ajax_save_state_list', $bulk_actions, 'wp_easycart_shipping_settings_nonce' ); 
		?>

	</div>

</div>