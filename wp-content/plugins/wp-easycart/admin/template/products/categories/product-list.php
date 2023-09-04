<?php
global $wpdb;
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_categoryitem', 'categoryitem_id' );
$table->set_join( 'LEFT JOIN ec_product ON ec_product.product_id = ec_categoryitem.product_id' );
$table->set_default_sort( 'ec_product.title', 'ASC' );
$category_name = $wpdb->get_var( $wpdb->prepare( "SELECT ec_category.category_name FROM ec_category WHERE ec_category.category_id = %d", (int) $_GET['category_id'] ) );
$table->set_header( sprintf( __( "Manage Products for %s Category", 'wp-easycart' ), $category_name ) );
$table->set_icon( 'menu' );
$table->set_add_new( true, 'add-new-category-product&category_id=' . (int) $_GET['category_id'], 'Add New', true, 'subpage', 'category-products-manage' );
$table->set_cancel( true, 'admin.php?page=wp-easycart-products&subpage=category', 'Back to Categories' );
$table->set_custom_where( $wpdb->prepare( ' AND ec_categoryitem.category_id = %d', (int) $_GET['category_id'] ) );
$table->set_docs_link( 'products', 'categories' );
$table->set_list_columns( 
	array(

		array( 
			'select'=> 'ec_product.title AS product_title',
			'name' 	=> 'product_title', 
			'label'	=> __( 'Product Title', 'wp-easycart' ),
			'format'=> 'string'
		),
		array( 
			'select'=> 'ec_product.model_number AS model_number',
			'name' 	=> 'model_number', 
			'label'	=> __( 'Model Number', 'wp-easycart' ),
			'format'=> 'string'
		),
		array( 
			'select'=> 'ec_product.price AS price',
			'name'	=> 'price', 
			'label'	=> __( 'Price', 'wp-easycart' ),
			'format'=> 'currency'
		)
	)
);
$table->set_search_columns(
	array( 'ec_product.title', 'ec_product.model_number' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-category-product',
			'label'	=> __( 'Delete', 'wp-easycart' )
		)
	)
);
$table->set_actions(
	array(
		array(
			'name'	=> 'delete-category-product',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'  => 'trash'
		)
		
	)
);
$table->set_filters(
	array( )
);
$table->set_label( __( 'Product', 'wp-easycart' ), __( 'Products', 'wp-easycart' ) );
$table->set_get_vars( array( 'category_id' ) );
$table->print_table( );
?>