<?php
$table = new wp_easycart_admin_table( );
$table->set_table( 'ec_option', 'option_id' );
$table->set_table_id( 'ec_admin_option_list' );
$table->set_default_sort( 'option_name', 'ASC' );
$table->set_header( __( 'Manage Option Sets', 'wp-easycart' ) );
$table->set_add_new( true, 'add-new-option', __( 'Add New', 'wp-easycart' ) );
$table->set_icon( 'image-filter' );
$table->set_docs_link( 'products', 'option-sets' );
$table->set_list_columns( 
	array(
		array( 
			'name' 	=> 'option_name', 
			'label'	=> __( 'Option Set Name', 'wp-easycart' ),
			'format'=> 'string',
            'linked'=> true,
			'square_check' => true,
            'subactions'=> array(
                array(
                    'url'           => 'admin.php?page=wp-easycart-products&subpage=optionitems&option_id={key}',
                    'name'          => __( 'Edit Option Items', 'wp-easycart' ),
                    'action_type'   => 'subpage',
                    'action'        => 'optionitems'
                ),
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Duplicate', 'wp-easycart' ),
                    'action_type'   => 'duplicate',
                    'action'        => 'duplicate-option'
                ),
                array(
                    'click'         => 'return false',
                    'name'          => __( 'Delete', 'wp-easycart' ),
                    'action_type'   => 'delete',
                    'action'        => 'delete-option'
                )
            )
		),
		array( 
			'name' 	=> 'square_id', 
			'label'	=> __( 'Square ID', 'wp-easycart' ),
			'format'=> 'hidden'
		),
		array( 
			'name' 	=> 'option_type', 
			'label'	=> __( 'Option Type', 'wp-easycart' ),
			'format'=> 'optiontype'
		),
		array( 
			'name' 	=> 'option_id', 
			'label'	=> __( 'Option ID', 'wp-easycart' ),
			'format'=> 'string'
		),
		array( 
			'name' 	=> 'option_required',
			'label'	=> __( 'Option Required?', 'wp-easycart' ),
			'format'=> 'checkbox'
		)
	)
);
$table->set_search_columns(
	array( 'ec_option.option_name', 'ec_option.option_label', 'ec_option.option_id', 'ec_option.option_type' )
);
$table->set_bulk_actions(
	array(
		array(
			'name'	=> 'delete-option',
			'label'	=> __( 'Delete', 'wp-easycart' )
		)
	)
);
$table->set_actions(
	array(

		array(
			'custom'=> 'subpage',
			'name'	=> 'optionitems',
			'label'	=> __( 'Edit Option Items', 'wp-easycart' ),
			'icon'	=> 'external'
		),
		array(
			'name'	=> 'edit',
			'label'	=> __( 'Edit Option', 'wp-easycart' ),
			'icon'	=> 'edit'
		),
		array(
			'name'	=> 'duplicate-option',
			'label'	=> __( 'Duplicate', 'wp-easycart' ),
			'icon'	=> 'admin-page'
		),
		array(
			'name'	=> 'delete-option',
			'label'	=> __( 'Delete', 'wp-easycart' ),
			'icon'	=> 'trash'
		)
	)
);
$option_types = array(
	(object) array(
			'value'	=> 'basic-combo',
			'label'	=> __( 'Basic Combo', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'basic-swatch',
			'label'	=> __( 'Basic Swatch', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'combo',
			'label'	=> __( 'Advanced Combo Box', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'swatch',
			'label'	=> __( 'Advanced Image Swatches', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'text',
			'label'	=> __( 'Advanced Text Input', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'textarea',
			'label'	=> __( 'Advanced Text Area', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'number',
			'label'	=> __( 'Advanced Number Field', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'file',
			'label'	=> __( 'Advanced File Upload', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'radio',
			'label'	=> __( 'Advanced Radio Group', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'checkbox',
			'label'	=> __( 'Advanced Checkbox Group', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'grid',
			'label'	=> __( 'Advanced Quantity Grid', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'date',
			'label'	=> __( 'Advanced Date', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'dimensions1',
			'label'	=> __( 'Advanced Dimensions (Whole Inch)', 'wp-easycart' )
	),
	(object) array(
			'value'	=> 'dimensions2',
			'label'	=> __( 'Advanced Dimensions (Sub-Inch)', 'wp-easycart' )
	)
);
$option_requirements = array(
	(object) array(
			'value'	=> '1',
			'label'	=> __( 'Option Required', 'wp-easycart' )
	),
	(object) array(
			'value'	=> '0',
			'label'	=> __( 'Option Not Required', 'wp-easycart' )
	)
);
$table->set_filters(
	array(
		array(
			'data'		=> $option_types,
			'label'		=> __( 'All Option Types', 'wp-easycart' ),
			'where'		=> 'ec_option.option_type = %s'
		),
		array(
			'data'		=> $option_requirements,
			'label'		=> __( 'All Options', 'wp-easycart' ),
			'where'		=> 'ec_option.option_required = %d'
		)
	)
);
$table->set_label( __( 'Option', 'wp-easycart' ), __( 'Options', 'wp-easycart' ) );
$table->print_table( );
?>