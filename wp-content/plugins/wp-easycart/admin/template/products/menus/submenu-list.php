<?php
global $wpdb;
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_menulevel2', 'menulevel2_id' );
$table->set_table_id( 'ec_admin_menu_list' );
$table->set_default_sort( 'menu_order', 'ASC' );
$menu_name = $wpdb->get_var( $wpdb->prepare("SELECT ec_menulevel1.name FROM ec_menulevel1 WHERE ec_menulevel1.menulevel1_id = %d", (int) $_GET['menulevel1_id'] ) );
$table->set_header( sprintf( __( 'Manage Sub-Menus for %s', 'wp-easycart' ), $menu_name ) );
$table->set_icon( 'menu' );
$table->set_add_new( true, 'add-new-menulevel2&menulevel1_id=' . (int) $_GET['menulevel1_id'], __( 'Add New', 'wp-easycart' ) );
$table->set_cancel( true, 'admin.php?page=wp-easycart-products&subpage=menus', __( 'Back to Main Menus', 'wp-easycart' ) );
$table->set_custom_where( $wpdb->prepare( ' AND menulevel1_id = %d', (int) $_GET['menulevel1_id'] ) );
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
                    'url'           => 'admin.php?page=wp-easycart-products&subpage=subsubmenus&menulevel2_id={key}',
                    'name'          => __( 'View Submenu', 'wp-easycart' ),
                    'action_type'   => 'subpage',
                    'action'        => 'subsubmenus'
                ),
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-menulevel2'
                )
            )
		),
		array( 
			'name' 	=> 'menulevel2_id', 
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
	array( 'ec_menulevel2.name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-menulevel2',
			'label'	=> __( 'Delete', 'wp-easycart' )
		)
	)
);
$table->set_actions(
	array(
		array(
			'custom'=> 'subpage',
			'name'	=> 'subsubmenus',
			'label'	=> __( 'View Submenu', 'wp-easycart' ),
			'icon'	=> 'external'
		),
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'delete-menulevel2',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
$table->set_filters(
	array( )
);
$table->set_label( __( 'Sub-Menu', 'wp-easycart' ), __( 'Sub-Menus', 'wp-easycart' ) );
$table->set_get_vars( array( 'menulevel1_id' ) );
$table->print_table( );
?>