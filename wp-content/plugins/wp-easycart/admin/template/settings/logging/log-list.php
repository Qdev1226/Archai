<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_response', 'response_id' );
$table->set_default_sort( 'response_id', 'DESC' );
$table->set_header( __( 'EasyCart Logging System', 'wp-easycart' ) );
$table->set_icon( 'sos' );
$table->set_add_new( false );
$table->set_docs_link( 'settings', 'log-entries' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'response_id',
			'label'	=> __( 'Log ID', 'wp-easycart' ),
			'format'=> 'int',
			'linked'=> true
		),
		array( 
			'name' 	=> 'order_id',
			'label'	=> __( 'Order ID', 'wp-easycart' ),
			'format'=> 'int'
		),
		array( 
			'name' 	=> 'processor',
			'label'	=> __( 'Source', 'wp-easycart' ),
			'format'=> 'text'
		),
		array( 
			'name' 	=> 'is_error', 
			'label'	=> __( 'Is Error?', 'wp-easycart' ),
			'format'=> 'checkbox'
		),array( 
			'name' 	=> 'response_time',
			'label'	=> __( 'Date', 'wp-easycart' ),
			'format'=> 'date'
		)
	)
);
$table->set_search_columns(
	array( 'ec_response.response_id', 'ec_response.order_id' )
);
$table->set_bulk_actions(
	array(

	)
);
$table->set_actions(
	array(
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart' ),
			'icon'	=> 'edit'
		)
	)
);

$table->set_filters(
	array( )
);
$table->set_label( __( 'Log Entry', 'wp-easycart' ), __( 'Log Entries', 'wp-easycart' ) );
$table->print_table( );
?>