<?php global $wpdb; ?>
<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-networking"></div>
		<span><?php esc_attr_e( 'Manage Shipping Zones', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-settings', 'shipping-zones' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-rates', 'shipping-zones');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_tax_section">

		<?php
		$zones = $wpdb->get_results( "SELECT ec_zone.zone_id AS value, ec_zone.zone_name AS label FROM ec_zone ORDER BY ec_zone.zone_name ASC" );
		$countries = $wpdb->get_results( "SELECT ec_country.name_cnt AS label, ec_country.iso2_cnt AS value FROM ec_country ORDER BY ec_country.sort_order ASC" );
		$states = $wpdb->get_results( "SELECT ec_state.name_sta AS label, ec_state.id_sta AS value, ec_country.iso2_cnt as group_id FROM ec_state LEFT JOIN ec_country ON ec_country.id_cnt = ec_state.idcnt_sta  ORDER BY ec_country.sort_order ASC, ec_state.name_sta ASC" );

		?>

		<?php
		$columns = array(
			array(
				'label'		=> 'Zone Name',
				'id'		=> 'zone_name',
				'type'		=> 'text',
			)
		);

		$data = $wpdb->get_results( "SELECT 
				ec_zone.zone_id AS id, 
				ec_zone.zone_name 
			FROM 
				ec_zone
			ORDER BY ec_zone.zone_name ASC"
		);

		$actions = array( 
			'delete'		=> array(
				'label'		=> 'Delete',
				'icon'		=> 'dashicons-trash',
				'function'	=> 'ec_admin_ajax_delete_shipping_zone',
				'callback'  => 'ec_admin_delete_shipping_zone'
			)
		);

		wp_easycart_admin( )->load_editable_table( 'wp_easycart_zone_table', $columns, $data, $actions, array( 'add_func' => 'ec_admin_ajax_add_shipping_zone', 'callback_func' => 'ec_admin_add_shipping_zone' ), 'ec_admin_ajax_edit_shipping_zone', array( 'delete' => 'Delete Selected' ), 'wp_easycart_shipping_settings_nonce' ); 
		?>
	</div>
</div>

<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-networking"></div>
		<span><?php esc_attr_e( 'Manage Shipping Zone Items', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-settings', 'shipping-zones' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-rates', 'shipping-zones');?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_tax_section">
		<?php
		$columns = array(
			array(
				'label'		=> __( 'Zone', 'wp-easycart' ),
				'id'		=> 'zone_id',
				'type'		=> 'combo',
				'options'	=> $zones,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'Select One', 'wp-easycart' )
				),
				'cssclass'  => 'wp-easycart-zone-list'
			),
			array(
				'label'		=> __( 'Country', 'wp-easycart' ),
				'id'		=> 'iso2_cnt',
				'type'		=> 'combo',
				'options'	=> $countries,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'All Countries', 'wp-easycart' )
				)
			),
			array(
				'label'		=> __( 'State', 'wp-easycart' ),
				'id'		=> 'id_sta',
				'type'		=> 'combo',
				'options'	=> $states,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'All States', 'wp-easycart' )
				),
				'required'  => false
			)
		);

		$data = $wpdb->get_results( "SELECT 
				ec_zone_to_location.zone_to_location_id AS id, 
				ec_zone_to_location.zone_id, 
				ec_zone_to_location.iso2_cnt, 
				ec_state.id_sta 
			FROM 
				ec_zone_to_location
				LEFT JOIN ec_country ON ( ec_country.iso2_cnt = ec_zone_to_location.iso2_cnt ) 
				LEFT JOIN ec_state ON ( ec_state.code_sta = ec_zone_to_location.code_sta AND ec_state.idcnt_sta = ec_country.id_cnt )
			ORDER BY 
				ec_zone_to_location.zone_id ASC,
				ec_zone_to_location.iso2_cnt ASC,
				ec_zone_to_location.code_sta ASC"
		);

		$actions = array( 
			'delete'		=> array(
				'label'		=> __( 'Delete', 'wp-easycart' ),
				'icon'		=> 'dashicons-trash',
				'function'	=> 'ec_admin_ajax_delete_shipping_zone_item'
			)
		);

		wp_easycart_admin( )->load_editable_table( 'wp_easycart_zone_items_table', $columns, $data, $actions, 'ec_admin_ajax_add_shipping_zone_item', 'ec_admin_ajax_update_shipping_zone_item', array( 'delete' => 'Delete Selected' ), 'wp_easycart_shipping_settings_nonce' ); 
		?>

	</div>

</div>