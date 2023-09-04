<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_menulevel1', 'menulevel1_id' );
$table->set_table_id( 'ec_admin_menu_list' );
$table->set_default_sort( 'menu_order', 'ASC' );
$table->set_default_sort( 'name', 'ASC' );
$table->set_header( __( 'Manage Menus', 'wp-easycart' ) );
$table->set_add_new( true, 'add-new-menulevel1', __( 'Add New', 'wp-easycart' ) );
$table->set_icon( 'menu' );
$table->set_docs_link( 'products', 'menus' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'name', 
			'label'	=> __( 'Menu Name', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
            'subactions'=> array(
                array(
                    'url'           => 'admin.php?page=wp-easycart-products&subpage=submenus&menulevel1_id={key}',
                    'name'          => __( 'View Submenu', 'wp-easycart' ),
                    'action_type'   => 'subpage',
                    'action'        => 'submenus'
                ),
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-menulevel1'
                )
            )
		),
		array( 
			'name' 	=> 'menulevel1_id', 
			'label'	=> __( 'Menu ID', 'wp-easycart' ),
			'format'=> 'number'
		),
		array( 
			'name' 	=> 'clicks',
			'label'	=> __( 'Menu Clicks', 'wp-easycart' ),
			'format'=> 'int'
		)
	)
);
$table->set_search_columns(
	array( 'ec_menulevel1.name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-menulevel1',
			'label'	=> __( 'Delete', 'wp-easycart' )
		)
	)
);
$table->set_actions(
	array(
		array(
			'custom'=> 'subpage',
			'name'	=> 'submenus',
			'label'	=> __( 'View Submenu', 'wp-easycart' ),
			'icon'	=> 'external'
		),
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'delete-menulevel1',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
$table->set_filters(
	array( )
);
$table->set_label( __( 'Menu', 'wp-easycart' ), __( 'Menus', 'wp-easycart' ) );
$table->print_table( );
?>