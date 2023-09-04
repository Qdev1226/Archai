<?php global $wpdb; ?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-site"></div>
		<span><?php esc_attr_e( 'Country Shipping List', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-settings', 'country-list' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'shipping-rates', 'country-list' ); ?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_tax_section">

		<?php
		$data = $wpdb->get_results( "SELECT ec_country.id_cnt as id, ec_country.name_cnt, ec_country.ship_to_active FROM ec_country ORDER BY ec_country.sort_order ASC" );
		$columns = array(
			array(
				'label' => __( 'Country', 'wp-easycart' ),
				'id' => 'name_cnt',
				'type' => 'read-only',
				'readonly' => true,
				'default' => '',
				'labelpos' => 'left',
				'width' => '75%',
			),
			array(
				'label' => __( 'Enabled', 'wp-easycart' ),
				'id' => 'ship_to_active',
				'type' => 'checkbox',
				'default' => '0',
				'readonly' => false,
				'labelpos' => 'center',
				'width' => '25%',
				'sort' => false,
			),
		);
		$actions = array( );
		$bulk_actions = array( );
		wp_easycart_admin( )->load_editable_table( 'wp_easycart_country_ship_table', $columns, $data, $actions, 'ec_admin_ajax_save_country_list', 'ec_admin_ajax_save_country_list', $bulk_actions, 'wp_easycart_shipping_settings_nonce' ); 
		?>

	</div>

</div>