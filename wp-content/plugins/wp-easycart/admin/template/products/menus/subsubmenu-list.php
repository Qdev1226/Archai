<?php
global $wpdb;
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_menulevel3', 'menulevel3_id' );
$table->set_table_id( 'ec_admin_menu_list' );
$table->set_default_sort( 'menu_order', 'ASC' );
$menu_name = $wpdb->get_var( $wpdb->prepare("SELECT ec_menulevel2.name FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d", (int) $_GET['menulevel2_id'] ) );
$menu1_id = $wpdb->get_var( $wpdb->prepare("SELECT ec_menulevel2.menulevel1_id FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d", (int) $_GET['menulevel2_id'] ) );
$table->set_header( sprintf( __( 'Manage Sub-Menus for %s', 'wp-easycart' ), $menu_name ) );
$table->set_icon( 'menu' );
$table->set_add_new( true, 'add-new-menulevel3&menulevel2_id=' . (int) $_GET['menulevel2_id'], __( 'Add New', 'wp-easycart' ) );
$table->set_cancel( true, 'admin.php?page=wp-easycart-products&subpage=submenus&menulevel1_id='. $menu1_id, __( 'Back to Sub-Menu', 'wp-easycart' ) );
$table->set_custom_where( $wpdb->prepare( ' AND menulevel2_id = %d', (int) $_GET['menulevel2_id'] ) );
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
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-menulevel3'
                )
            )
		),
		array( 
			'name' 	=> 'menulevel3_id', 
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
	array( 'ec_menulevel3.name' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-menulevel3',
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
			'name'	=> 'delete-menulevel3',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
$table->set_filters(
	array( )
);
$table->set_label( __( 'Sub-Menu', 'wp-easycart' ), __( 'Sub-Menus', 'wp-easycart' ) );
$table->set_get_vars( array( 'menulevel2_id' ) );
$table->print_table( );
?>