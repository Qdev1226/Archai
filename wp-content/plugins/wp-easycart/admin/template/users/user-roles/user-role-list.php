<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_role', 'role_id' );
$table->set_default_sort( 'role_label', 'ASC' );
$table->set_icon( 'groups' );
$table->set_docs_link( 'users', 'user-roles' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'role_label', 
			'label'	=> __( 'User Role', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-user-role'
                )
            )
		),
		array( 
			'name' 	=> 'admin_access', 
			'label'	=> __( 'Remote Admin Access', 'wp-easycart' ),
			'format'=> 'checkbox'
		)

	)
);
$table->set_search_columns(
	array( 'ec_role.role_label' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-user-role',
			'label'	=> __( 'Delete', 'wp-easycart' )
		),
		
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
			'name'	=> 'delete-user-role',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
$table->set_filters(
	array( )
);
$table->set_label( __( 'User Role', 'wp-easycart' ), __( 'User Roles', 'wp-easycart' ) );
$table->print_table( );
?>