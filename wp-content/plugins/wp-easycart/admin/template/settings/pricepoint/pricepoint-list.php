<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_pricepoint', 'pricepoint_id' );
$table->set_default_sort( 'pricepoint_order', 'ASC' );
$table->set_header( __( 'Manage Price Points', 'wp-easycart' ) );
$table->set_icon( 'admin-site' );
$table->set_docs_link( 'settings', 'price-points' );
$table->set_list_columns( 
	array(

		array( 
			'name' 	=> 'low_point',
			'label'	=> __( 'Low Price Point', 'wp-easycart' ),
			'format'=> 'currency',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-pricepoint'
                )
            )
		),
		array( 
			'name' 	=> 'High_point',
			'label'	=> __( 'High Price Point', 'wp-easycart' ),
			'format'=> 'currency'
		),
		array( 
			'name' 	=> 'is_less_than',
			'label'	=> __( 'Less Than', 'wp-easycart' ),
			'format'=> 'checkbox'
		),
		array( 
			'name' 	=> 'is_greater_than',
			'label'	=> __( 'Greater Than', 'wp-easycart' ),
			'format'=> 'checkbox'
		)
	)
);
$table->set_search_columns(
	array( 'ec_pricepoint.pricepoint_id' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-pricepoint',
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
			'name'	=> 'delete-pricepoint',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);

$table->set_filters(
	array( )
);
$table->set_label( __( 'Price Point', 'wp-easycart' ), __( 'Price Points', 'wp-easycart' ) );
$table->print_table( );
?>