<?php global $wpdb; ?>
<div class="ec_admin_list_line_item ec_admin_demo_data_line" style="float:left;">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-welcome-add-page"></div>
		<span><?php _e( 'Flex-Fee', 'wp-easycart' ); ?></span>
		<a href="<?php echo wp_easycart_admin( )->helpsystem->print_docs_url('settings', 'taxes', 'tax-by-country-setup');?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php _e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php echo wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'taxes', 'tax-by-country-setup');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_tax_section">

		<?php
		$countries = $wpdb->get_results( "SELECT ec_country.name_cnt AS label, ec_country.iso2_cnt AS value FROM ec_country WHERE ec_country.ship_to_active = 1 ORDER BY ec_country.sort_order ASC" );
		$states = $wpdb->get_results( "SELECT ec_state.name_sta AS label, ec_state.id_sta AS value, ec_country.iso2_cnt as group_id FROM ec_state LEFT JOIN ec_country ON ec_country.id_cnt = ec_state.idcnt_sta WHERE ec_state.ship_to_active = 1 ORDER BY ec_country.sort_order ASC, ec_state.name_sta ASC" );
		$categories = $wpdb->get_results( "SELECT ec_category.category_name AS label, ec_category.category_id AS value FROM ec_category ORDER BY ec_category.priority ASC, ec_category.category_name ASC" );
		$columns = array(
			array(
				'label'		=> __( 'Label', 'wp-easycart' ),
				'id'		=> 'fee_label',
				'type'		=> 'text',
				'default'	=> ''
			),
			array(
				'label'		=> __( 'Country', 'wp-easycart' ),
				'id'		=> 'fee_country',
				'type'		=> 'combo',
				'options'	=> $countries,
				'multiple'	=> true,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'All Countries', 'wp-easycart' )
				),
				'required'	=> false,
			),
			array(
				'label'		=> __( 'State', 'wp-easycart' ),
				'id'		=> 'fee_state',
				'type'		=> 'combo',
				'options'	=> $states,
				'multiple'	=> true,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'All States', 'wp-easycart' )
				),
				'required'	=> false,
			),
			array(
				'label'		=> __( 'Zip', 'wp-easycart' ),
				'id'		=> 'fee_zip',
				'type'		=> 'text',
				'default'	=> '',
				'required'	=> false,
			),
			array(
				'label'		=> __( 'City', 'wp-easycart' ),
				'id'		=> 'fee_city',
				'type'		=> 'text',
				'default'	=> '',
				'required'	=> false,
			),
			array(
				'label'		=> __( 'Category', 'wp-easycart' ),
				'id'		=> 'fee_category',
				'type'		=> 'combo',
				'options'	=> $categories,
				'multiple'	=> true,
				'default'	=> array(
					'value'	=> '',
					'label' => __( 'All Categories', 'wp-easycart' )
				),
				'required'	=> false,
			),
			array(
				'label'		=> __( 'Rate', 'wp-easycart' ),
				'id'		=> 'fee_rate',
				'type'		=> 'percentage',
				'default'	=> '0.000',
				'required'	=> false,
			),
			array(
				'label'		=> __( 'Price', 'wp-easycart' ),
				'id'		=> 'fee_price',
				'type'		=> 'number',
				'default'	=> '',
				'required'	=> false,
			),
			array(
				'label'		=> __( 'Min Fee', 'wp-easycart' ),
				'id'		=> 'fee_min',
				'type'		=> 'number',
				'default'	=> '',
				'required'	=> false,
			),
			array(
				'label'		=> __( 'Max Fee', 'wp-easycart' ),
				'id'		=> 'fee_max',
				'type'		=> 'number',
				'default'	=> '',
				'required'	=> false,
			),
		);

		$data = $wpdb->get_results( "SELECT 
				ec_fee.fee_id AS id,
				ec_fee.fee_label,
				ec_fee.fee_country,
				ec_fee.fee_state,
				ec_fee.fee_zip,
				ec_fee.fee_city,
				ec_fee.fee_category,
				ec_fee.fee_rate,
				ec_fee.fee_price,
				ec_fee.fee_min,
				ec_fee.fee_max
			FROM 
				ec_fee
			ORDER BY 
				ec_fee.fee_label ASC"
		);

		$actions = array( 
			'delete'		=> array(
				'label'		=> __( 'Delete', 'wp-easycart' ),
				'icon'		=> 'dashicons-trash',
				'function'	=> 'show_pro_required'
			)
		);

		wp_easycart_admin( )->load_editable_table( 'wp_easycart_fee_table', $columns, $data, $actions, 'show_pro_required', 'show_pro_required', array( 'delete' => 'Delete Selected' ), 'wp_easycart_tax_settings_nonce'  ); 
		?>

	</div>

</div>