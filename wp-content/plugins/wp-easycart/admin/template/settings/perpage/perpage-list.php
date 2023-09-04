<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_perpage', 'perpage_id' );
$table->set_default_sort( 'perpage', 'ASC' );
$table->set_header( __( 'Manage Per-Page', 'wp-easycart' ) );
$table->set_icon( 'admin-site' );
$table->set_docs_link( 'settings', 'per-page-options' );
$table->set_list_columns( 
	array(

		array( 
			'name' 	=> 'perpage',
			'label'	=> __( 'Per-Page Value', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-perpage'
                )
            )
		)
	)
);
$table->set_search_columns(
	array( 'ec_perpage.perpage' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-perpage',
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
			'name'	=> 'delete-perpage',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);

$table->set_filters(
	array( )
);
$table->set_label( __( 'Per-Page', 'wp-easycart' ), __( 'Per-Page', 'wp-easycart' ) );
$table->print_table( );
?>