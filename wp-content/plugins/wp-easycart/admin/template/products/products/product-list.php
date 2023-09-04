<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_product', 'product_id' );
$table->set_table_id( 'ec_admin_product_list' );
$table->set_default_sort( 'title', 'ASC' );
$table->set_header( __( 'Manage Products', 'wp-easycart' ) );
$table->set_icon( 'products' );
$table->set_importer( true, __( 'Import Products', 'wp-easycart' ) );
$table->set_docs_link( 'products', 'products' );
$table->set_add_new_js( 'wp_easycart_admin_open_slideout( \'new_product_box\' ); return false;' );
$table->set_add_new_css( 'ec_page_title_button' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'title', 
			'label'	=> __( 'Product Title', 'wp-easycart' ),
			'format'=> 'string',
			'width'	=> 225,
            'linked'=> true,
			'square_check' => true,
            'subactions'=> array(
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Quick Edit', 'wp-easycart' ),
                    'action_type'   => 'quick-edit',
                    'action'        => 'quick-edit-product',
                    'type'          => 'product'
                ),
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Duplicate', 'wp-easycart' ),
                    'action_type'   => 'duplicate',
                    'action'        => 'duplicate-product'
                ),
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-product'
                )
            )
		),
		array( 
			'name' 	=> 'square_id', 
			'label'	=> __( 'Square ID', 'wp-easycart' ),
			'format'=> 'hidden'
		),
		array( 
			'name' 	=> 'stock_quantity', 
			'label'	=> __( 'Quantity', 'wp-easycart' ),
			'format'=> 'stock'
		),
		array( 
			'name' 	=> 'price',
			'label'	=> __( 'Price', 'wp-easycart' ),
			'format'=> 'currency'
		), 
		array( 
			'name' 	=> 'model_number',
			'label'	=> __( 'SKU', 'wp-easycart' ),
			'format'=> 'string'
		),
		array( 
			'select'=> 'ec_product.activate_in_store as is_visible',
			'name' 	=> 'is_visible',
			'label'	=> __( 'Active', 'wp-easycart' ),
			'format'=> 'yes_no'
		),
		array( 
			'name' 	=> 'product_id',
			'label'	=> __( 'ID', 'wp-easycart' ),
			'format'=> 'int',
		),
		array( 
			'name' 	=> 'views',
			'label'	=> __( 'ID', 'wp-easycart' ),
			'format'=> 'hidden'
		),
		array( 
			'name' 	=> 'show_stock_quantity',
			'label'	=> __( 'Show Stock Quantity', 'wp-easycart' ),
			'format'=> 'hidden'
		),
		array( 
			'name' 	=> 'use_optionitem_quantity_tracking',
			'label'	=> __( 'Use Option Stock', 'wp-easycart' ),
			'format'=> 'hidden'
		)
	)
);
$table->set_search_columns(
	array( 'ec_product.title', 'ec_product.short_description', 'ec_product.description', 'ec_product.model_number' )
);
$table->set_bulk_actions(
	apply_filters( 'wp_easycart_admin_bulk_product_options', array(
		array(
			'name'	=> 'delete-product',
			'label'	=> __( 'Delete', 'wp-easycart' )
		),
		array(
			'name'	=> 'activate-product',
			'label'	=> __( 'Activate Selected', 'wp-easycart' )
		),
		array(
			'name'	=> 'deactivate-product',
			'label'	=> __( 'Deactivate Selected', 'wp-easycart' )
		),

		array(
			'name'	=> 'export-products-csv',
			'label'	=> __( 'Export Selected CSV', 'wp-easycart' )
		),
		array(
			'name'	=> 'export-all-products-csv',
			'label'	=> __( 'Export All CSV', 'wp-easycart' )
		)
	) )
);
$table->set_actions(
	array(
		array(
			'name'	=> 'stats',
			'label'	=> __( 'Stats', 'wp-easycart' ),
			'icon'	=> 'chart-bar',
			'custom'=> '#',
			'customhtml'=>' class="ec_admin_stats_link" onmouseout="wp_easycart_hide_product_stats( jQuery( this ) );" onmouseover="wp_easycart_show_product_stats( jQuery( this ) );" onclick="return false;"'
		),
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit', 'wp-easycart' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'deactivate-product',
			'label'	=> __( 'Deactivate', 'wp-easycart' ),
			'icon'	=> 'hidden'
		),
		array(
			'name'	=> 'duplicate-product',
			'label'	=> __( 'Duplicate', 'wp-easycart' ),
			'icon'	=> 'admin-page'
		),
		array(
			'name'	=> 'delete-product',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
global $wpdb;
$manufacturer_list = $wpdb->get_results( "SELECT ec_manufacturer.manufacturer_id AS value, ec_manufacturer.name AS label FROM ec_manufacturer ORDER BY ec_manufacturer.name ASC" );
$category_list = $wpdb->get_results( "SELECT ec_category.category_id AS value, ec_category.category_name AS label FROM ec_category ORDER BY ec_category.category_name ASC" );
$table->set_filters(
	array(
		array(
			'data'		=> $manufacturer_list,
			'label'		=> __( 'All Manufacturers', 'wp-easycart' ),
			'where'		=> 'ec_product.manufacturer_id = %d'
		),
		array(
			'data'		=> $category_list,
			'label'		=> __( 'All Categories', 'wp-easycart' ),
			'select'	=> 'ec_categoryitem.category_id',
			'join'		=> 'LEFT JOIN ec_categoryitem ON (ec_categoryitem.product_id = ec_product.product_id)',
			'having'	=> 'ec_categoryitem.category_id = %d'
		),
        array(
			'data'		=> array(
                (object) array(
                    'value' => '1',
                    'label' => __( 'Activated Only', 'wp-easycart' )
                ),
                (object) array(
                    'value' => '0',
                    'label' => __( 'Deactivated Only', 'wp-easycart' )
                )
            ),
			'label'		=> __( 'All Products', 'wp-easycart' ),
			'where'		=> 'ec_product.activate_in_store = %d'
		)
	)
);
$table->set_label( __( 'Product', 'wp-easycart' ), __( 'Products', 'wp-easycart' ) );
if( !get_option( 'ec_option_review_complete' ) ){
?>
<div class="wp-easycart-admin-review-us-box">
	<?php esc_attr_e( 'Do you like WP EasyCart? If you do, please take a moment to', 'wp-easycart' ); ?> <a href="https://wordpress.org/support/plugin/wp-easycart/reviews/" target="_blank"><?php esc_attr_e( 'submit a review', 'wp-easycart' ); ?></a>, <?php esc_attr_e( 'it really helps us!', 'wp-easycart' ); ?>
    <div class="wp-easycart-admin-review-us-close" onclick="wp_easycart_admin_close_review( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-review-us' ) ); ?>' );"><div class="dashicons dashicons-no"></div></div>
</div>
<?php
}
$table->print_table( );

wp_easycart_admin( )->load_new_slideout( 'product' );
wp_easycart_admin( )->load_new_slideout( 'manufacturer' );
wp_easycart_admin( )->load_new_slideout( 'optionset' );
?>