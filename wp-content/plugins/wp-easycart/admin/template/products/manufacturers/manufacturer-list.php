<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_manufacturer', 'manufacturer_id' );
$table->set_default_sort( 'name', 'ASC' );
$table->set_header( __( 'Manage Manufacturers', 'wp-easycart' ) );
$table->set_icon( 'products' );
$table->set_docs_link ('products','manufacturers');
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'name', 
			'label'	=> __( 'Manufacturer Name', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-manufacturer'
                )
            )
		),
        array( 
			'name' 	=> 'manufacturer_id', 
			'label'	=> __( 'ID', 'wp-easycart' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'clicks',
			'label'	=> __( 'Clicks', 'wp-easycart' ),
			'format'=> 'int'
		)
	)
);
$table->set_search_columns(
	array( 'ec_manufacturer.name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-manufacturer',
			'label'	=> __( 'Delete', 'wp-easycart' )
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
			'name'	=> 'delete-manufacturer',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);

$table->set_filters(
	array( )
);
$table->set_label( __( 'Manufacturer', 'wp-easycart' ), __( 'Manufacturers', 'wp-easycart' ) );
$table->print_table( );
?>