<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_user', 'user_id' );
$table->set_table_id( 'ec_admin_user_list' );
$table->set_default_sort( 'email', 'ASC' );
$table->set_header( __( 'Manage User Accounts', 'wp-easycart' ) );
$table->set_icon( 'admin-users' );
$table->set_importer( true, __( 'Import Users', 'wp-easycart' ) );
$table->set_docs_link( 'users', 'user-accounts' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'email',
			'label'	=> __( 'Email Address', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-account'
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
		),
		array( 
			'name' 	=> 'user_level',
			'label'	=> __( 'Security Level', 'wp-easycart' ),
			'format'=> 'string'
		)
	)
);
$table->set_search_columns(
	array( 'ec_user.email', 'ec_user.first_name', 'ec_user.last_name', 'ec_user.user_level' )
);
$table->set_bulk_actions(
	apply_filters( 
		'wp_easycart_admin_bulk_user_options',
		array(
			array(
				'name'	=> 'delete-account',
				'label'	=> __( 'Delete', 'wp-easycart' )
			),
			array(
				'name'	=> 'export-accounts-csv',
				'label'	=> __( 'Export Selected CSV', 'wp-easycart' )
			),
			array(
				'name'	=> 'export-accounts-csv-all',
				'label'	=> __( 'Export All CSV', 'wp-easycart' )
			),
			array(
				'name'	=> 'accounts-force-password-reset',
				'label'	=> __( 'Force Selected to Reset Password', 'wp-easycart' )
			)
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
			'name'	=> 'delete-account',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
global $wpdb;
$user_roles = $wpdb->get_results( "SELECT ec_role.role_label AS value, ec_role.role_label AS label FROM ec_role ORDER BY role_id ASC" );

$table->set_filters(
	array(
		array(
			'data'		=> $user_roles,
			'label'		=> __( 'User Role', 'wp-easycart' ),
			'where'		=> 'ec_user.user_level = %s'
		),
		
	)
);
$table->set_label( __( 'User', 'wp-easycart' ), __( 'Users', 'wp-easycart' ) );
$table->print_table( );
?>
