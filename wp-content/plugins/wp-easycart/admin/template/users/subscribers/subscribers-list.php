<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_subscriber', 'subscriber_id' );
$table->set_default_sort( 'email', 'ASC' );
$table->set_icon( 'id-alt' );
$table->set_docs_link( 'users', 'subscribers' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'email',
			'label'	=> __( 'Email', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-subscriber'
                )
            )
		),
		array( 
			'name' 	=> 'first_name',
			'label'	=> __( 'First Name', 'wp-easycart' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'last_name',
			'label'	=> __( 'Last Name', 'wp-easycart' ),
			'format'=> 'string'
		)
	)
);
$table->set_search_columns(
	array( 'ec_subscriber.email', 'ec_subscriber.first_name', 'ec_subscriber.last_name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-subscriber',
			'label'	=> __( 'Delete', 'wp-easycart' )
		),
		array(
			'name'	=> 'export-subscribers-csv',
			'label'	=> __( 'Export Selected CSV', 'wp-easycart' )
		),
		array(
			'name'	=> 'export-subscribers-csv-all',
			'label'	=> __( 'Export All CSV', 'wp-easycart' )
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
			'name'	=> 'delete-subscriber',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
$table->set_filters(
	array( )
);
$table->set_label( __( 'Subscriber', 'wp-easycart' ), __( 'Subscribers', 'wp-easycart' ) );
$table->print_table( );
?>