<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_state', 'id_sta' );
$table->set_default_sort( 'sort_order', 'ASC' );
$table->set_header( __( 'Manage States & Provinces', 'wp-easycart' ) );
$table->set_icon( 'admin-site' );
$table->set_docs_link( 'settings', 'states-territories' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'name_sta', 
			'label'	=> __( 'State/Province', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-state'
                )
            )
		),
		array( 
			'name' 	=> 'code_sta',
			'label'	=> __( 'Abbreviated Name', 'wp-easycart' ),
			'format'=> 'string'
		),

		array( 
			'name' 	=> 'ship_to_active',
			'label'	=> __( 'Enabled?', 'wp-easycart' ),
			'format'=> 'checkbox'
		),
		array( 
			'name' 	=> 'sort_order',
			'label'	=> __( 'Sort Order', 'wp-easycart' ),
			'format'=> 'string'
		)
	)
);
$table->set_search_columns(
	array( 'ec_state.name_sta' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-state',
			'label'	=> __( 'Delete', 'wp-easycart' )
		),
		array(
			'name'	=> 'bulk-enable-state',
			'label'	=> __( 'Enable', 'wp-easycart' )
		),
		array(
			'name'	=> 'bulk-disable-state',
			'label'	=> __( 'Disable', 'wp-easycart' )
		)
	)
);
$table->set_actions(
	array(
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'delete-state',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);

$table->set_filters(
	array( )
);
$table->set_label( __( 'State', 'wp-easycart' ), __( 'States', 'wp-easycart' ) );
$table->print_table( );
?>