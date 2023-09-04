/* GUTENBERG EDITOR */
jQuery( document ).ready( function( ){
    ( function( blocks, i18n, element ) {
		var el = element.createElement;
		//var children = wp.blocks.source.children;
		var BlockControls = wp.blocks.BlockControls;
		var AlignmentToolbar = wp.blocks.AlignmentToolbar;
		var MediaUpload = wp.blocks.MediaUpload;
		var InspectorControls = wp.blocks.InspectorControls;
		var TextControl = wp.components.TextControl;
		var SelectControl = wp.components.SelectControl;
		var CheckboxControl = wp.components.CheckboxControl;
		var BaseControl = wp.components.BaseControl;
		var WPEasyCartShortCodes = [
		  { value: 'ec_store', label: wp_easycart_admin_block_language['store'] },
		  { value: 'ec_cart', label: wp_easycart_admin_block_language['cart'] },
		  { value: 'ec_account', label: wp_easycart_admin_block_language['account'] },
		  { value: 'ec_categories', label: wp_easycart_admin_block_language['category-standard'] },
		  { value: 'ec_category_view', label: wp_easycart_admin_block_language['category-grid'] },
		  { value: 'ec_store_table', label: wp_easycart_admin_block_language['store-table'] },
		  { value: 'ec_product', label: wp_easycart_admin_block_language['product-display'] },
		  { value: 'ec_addtocart', label: wp_easycart_admin_block_language['add-to-cart-button'] },
		  { value: 'ec_cartdisplay', label: wp_easycart_admin_block_language['cart-display'] },
		  { value: 'ec_membership', label: wp_easycart_admin_block_language['membership-content'] }
		];
		var WPEasyCartTableColumns = [
			{ value: 'product_id', label: wp_easycart_admin_block_language['product-id'] },
			{ value: 'model_number', label: wp_easycart_admin_block_language['model-number'] },
			{ value: 'title', label: wp_easycart_admin_block_language['title'] },
			{ value: 'price', label: wp_easycart_admin_block_language['price'] },
			{ value: 'details_link', label: wp_easycart_admin_block_language['details-link'] },
			{ value: 'description', label: wp_easycart_admin_block_language['description'] },
			{ value: 'specifications', label: wp_easycart_admin_block_language['specifications'] },
			{ value: 'stock_quantity', label: wp_easycart_admin_block_language['stock-quantity'] },
			{ value: 'weight', label: wp_easycart_admin_block_language['weight'] },
			{ value: 'width', label: wp_easycart_admin_block_language['width'] },
			{ value: 'height', label: wp_easycart_admin_block_language['height'] },
			{ value: 'length', label: wp_easycart_admin_block_language['length'] },
		];
		
		blocks.registerBlockType('wp-easycart/shortcode', {
			title: 'WP EasyCart', // The title of our block.
			icon: 'cart', // Dashicon icon for our block
			category: 'wp-easycart', // The category of the block.
			attributes: { // Necessary for saving block content.
				shortcode_type: {
					type: 'select',
					default: 'ec_store'
				},
				store_filter_type: {
					typle: 'select',
					default: ''
				},
				store_category: {
					type: 'select',
					default: '',
				},
				store_manufacturer: {
					type: 'select',
					default: '',
				},
				store_menulevel1: {
					type: 'select',
					default: '',
				},
				store_menulevel2: {
					type: 'select',
					default: '',
				},
				store_menulevel3: {
					type: 'select',
					default: '',
				},
				store_product: {
					type: 'select',
					default: '',
				},
				account_redirect: {
					type: 'url',
					default: '',
				},
				categories_category: {
					type: 'select',
					default: '0',
				},
				category_view_category: {
					type: 'select',
					default: '0',
				},
				category_view_columns: {
					type: 'select',
					default: '3',
				},
				store_table_products: {
					type: 'select',
					default: '',
				},
				store_table_menulevel1: {
					type: 'select',
					default: '',
				},
				store_table_menulevel2: {
					type: 'select',
					default: '',
				},
				store_table_menulevel3: {
					type: 'select',
					default: '',
				},
				store_table_category: {
					type: 'select',
					default: '',
				},
				store_table_label1: {
					type: 'string',
					default: '',
				},
				store_table_column1: {
					type: 'select',
					default: '',
				},
				store_table_label2: {
					type: 'string',
					default: '',
				},
				store_table_column2: {
					type: 'select',
					default: '',
				},
				store_table_label3: {
					type: 'string',
					default: '',
				},
				store_table_column3: {
					type: 'select',
					default: '',
				},
				store_table_label4: {
					type: 'string',
					default: '',
				},
				store_table_column4: {
					type: 'select',
					default: '',
				},
				store_table_label5: {
					type: 'string',
					default: '',
				},
				store_table_column5: {
					type: 'select',
					default: '',
				},
				store_table_link_label: {
					type: 'string',
					default: '',
				},
				product_product: {
					type: 'select',
					default: '',
				},
				product_display_type: {
					type: 'select',
					default: '',
				},
				addtocart_product: {
					type: 'select',
					default: '',
				},
				addtocart_background_add: {
					type: 'select',
					default: '',
				},
				membership_products: {
					type: 'select',
					default: '',
				}
			},
			edit: function( props ) {
                var focus = props.isSelected;
				var attributes = props.attributes;
                
				function onChangeShortcodeType( update_shortcode_type ) {
					props.setAttributes( { shortcode_type: update_shortcode_type } );
					
                    var ele = jQuery( document.getElementById( 'block-' + props.clientId ) );
                    
					ele.find( '.wp-easycart-block-item' ).each( function( ){
                        jQuery( this ).removeClass( 'hidden' ).addClass( 'hidden' );
					} );
					
					// Show Correct Fields
					if( attributes.shortcode_type == 'ec_store' ){
						ele.find( 'div.wp-easycart-store-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
						
						
					}else if( attributes.shortcode_type == 'ec_cart' ){
						// No Options
					
					}else if( attributes.shortcode_type == 'ec_account' ){
						ele.find( 'div.wp-easycart-account-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( attributes.shortcode_type == 'ec_categories' ){
						ele.find( 'div.wp-easycart-categories-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
						
					}else if(  attributes.shortcode_type == 'ec_category_view' ){
						ele.find( 'div.wp-easycart-category-view-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
						
					}else if(  attributes.shortcode_type == 'ec_store_table' ){
						ele.find( 'div.wp-easycart-store-table-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( attributes.shortcode_type == 'ec_product' ){
						ele.find( 'div.wp-easycart-product-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
						
					}else if( attributes.shortcode_type == 'ec_addtocart' ){
						ele.find( 'div.wp-easycart-addtocart-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
						
					}else if( attributes.shortcode_type == 'ec_cartdisplay' ){
						// No Options
					
					}else if( attributes.shortcode_type == 'ec_membership' ){
						ele.find( 'div.wp-easycart-membership-shortcode' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					}
				}
				
				function onChangeStoreFilters( changedVal ){
					props.setAttributes( { store_filter_type: changedVal } );
					
					var ele = jQuery( document.getElementById( 'block-' + props.clientId ) );
                    
                    jQuery( this ).find( 'div.wp-easycart-filter-category' ).each( function( ){
						if( jQuery( this ).parent( ).parent( ).hasClass( 'components-base-control' ) ){
                            jQuery( this ).parent( ).parent( ).hide( );
                        }
					} );
					
					jQuery( this ).find( 'div.wp-easycart-filter-menu' ).each( function( ){
						if( jQuery( this ).parent( ).parent( ).hasClass( 'components-base-control' ) ){
                            jQuery( this ).parent( ).parent( ).hide( );
                        }
					} );
					
					jQuery( this ).find( 'div.wp-easycart-filter-manufacturer' ).each( function( ){
						if( jQuery( this ).parent( ).parent( ).hasClass( 'components-base-control' ) ){
                            jQuery( this ).parent( ).parent( ).hide( );
                        }
					} );
					
					jQuery( this ).find( 'div.wp-easycart-filter-product' ).each( function( ){
						if( jQuery( this ).parent( ).parent( ).hasClass( 'components-base-control' ) ){
                            jQuery( this ).parent( ).parent( ).hide( );
                        }
					} );
					
					if( changedVal == 'category' ){
						jQuery( this ).find( 'div.wp-easycart-filter-category' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( changedVal == 'menu1' ){
						jQuery( this ).find( 'div.wp-easycart-filter-menu1' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( changedVal == 'menu2' ){
						jQuery( this ).find( 'div.wp-easycart-filter-menu2' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( changedVal == 'menu3' ){
						jQuery( this ).find( 'div.wp-easycart-filter-menu3' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( changedVal == 'manufacturer' ){
						jQuery( this ).find( 'div.wp-easycart-filter-manufacturer' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					
					}else if( changedVal == 'product' ){
						jQuery( this ).find( 'div.wp-easycart-filter-product' ).each( function( ){
							jQuery( this ).removeClass( 'hidden' )
						} );
					}
				}
				
				function onChangeStoreCategory( changedVal ){
					props.setAttributes( { store_category: changedVal } );
					props.setAttributes( { store_manufacturer: '' } );
					props.setAttributes( { store_menulevel1: '' } );
					props.setAttributes( { store_menulevel2: '' } );
					props.setAttributes( { store_menulevel3: '' } );
					props.setAttributes( { store_product: '' } );
				}
				
				function onChangeStoreManufacturer( changedVal ){
					props.setAttributes( { store_category: '' } );
					props.setAttributes( { store_manufacturer: changedVal } );
					props.setAttributes( { store_menulevel1: '' } );
					props.setAttributes( { store_menulevel2: '' } );
					props.setAttributes( { store_menulevel3: '' } );
					props.setAttributes( { store_product: '' } );
				}
				
				function onChangeStoreMenuLevel1( changedVal ){
					props.setAttributes( { store_category: '' } );
					props.setAttributes( { store_manufacturer: '' } );
					props.setAttributes( { store_menulevel1: changedVal } );
					props.setAttributes( { store_menulevel2: '' } );
					props.setAttributes( { store_menulevel3: '' } );
					props.setAttributes( { store_product: '' } );
				}
				
				function onChangeStoreMenuLevel2( changedVal ){
					props.setAttributes( { store_category: '' } );
					props.setAttributes( { store_manufacturer: '' } );
					props.setAttributes( { store_menulevel1: '' } );
					props.setAttributes( { store_menulevel2: changedVal } );
					props.setAttributes( { store_menulevel3: '' } );
					props.setAttributes( { store_product: '' } );
				}
				
				function onChangeStoreMenuLevel3( changedVal ){
					props.setAttributes( { store_category: '' } );
					props.setAttributes( { store_manufacturer: '' } );
					props.setAttributes( { store_menulevel1: '' } );
					props.setAttributes( { store_menulevel2: '' } );
					props.setAttributes( { store_menulevel3: changedVal } );
					props.setAttributes( { store_product: '' } );
				}
				
				function onChangeStoreProduct( changedVal ){
					props.setAttributes( { store_category: '' } );
					props.setAttributes( { store_manufacturer: '' } );
					props.setAttributes( { store_menulevel1: '' } );
					props.setAttributes( { store_menulevel2: '' } );
					props.setAttributes( { store_menulevel3: '' } );
					props.setAttributes( { store_product: changedVal } );
				}
				
				function onChangeAccountRedirect( changedVal ){
					props.setAttributes( { account_redirect: changedVal } );
				}
				
				function onChangeCategoriesCategory( changedVal ){
					props.setAttributes( { categories_category: changedVal } );
				}
				
				function onChangeCategoryViewCategory( changedVal ){
					props.setAttributes( { category_view_category: changedVal } );
				}
				
				function onChangeCategoryViewColumns( changedVal ){
					props.setAttributes( { category_view_columns: changedVal } );
				}
				
				function onChangeStoreTableProducts( e ){
					var val = e.target.value;
					
					var start_arr = props.attributes.store_table_products;
					console.log( start_arr );
					if( !start_arr ){
						start_arr = [];
					}
					if( start_arr.includes( val ) ){
						var f_index = start_arr.indexOf( val );
						if( f_index > -1 ){
							start_arr.splice( f_index, 1 );
						}
						e.target.checked = false;
					}else{
						e.target.checked = true;
						start_arr.push( val );
					}
					props.setAttributes({ store_table_products: start_arr });
				}
				
				function onChangeStoreTableMenulevel1( changedVal ){
					props.setAttributes( { store_table_menulevel1: changedVal } );
				}
				
				function onChangeStoreTableMenulevel2( changedVal ){
					props.setAttributes( { store_table_menulevel2: changedVal } );
				}
				
				function onChangeStoreTableMenulevel3( changedVal ){
					props.setAttributes( { store_table_menulevel3: changedVal } );
				}
				
				function onChangeStoreTableCategory( changedVal ){
					props.setAttributes( { store_table_categories: changedVal } );
				}
				
				function onChangeStoreTableLabel1( changedVal ){
					props.setAttributes( { store_table_label1: changedVal } );
				}
				
				function onChangeStoreTableColumn1( changedVal ){
					props.setAttributes( { store_table_column1: changedVal } );
				}
				
				function onChangeStoreTableLabel2( changedVal ){
					props.setAttributes( { store_table_label2: changedVal } );
				}
				
				function onChangeStoreTableColumn2( changedVal ){
					props.setAttributes( { store_table_column2: changedVal } );
				}
				
				function onChangeStoreTableLabel3( changedVal ){
					props.setAttributes( { store_table_label3: changedVal } );
				}
				
				function onChangeStoreTableColumn3( changedVal ){
					props.setAttributes( { store_table_column3: changedVal } );
				}
				
				function onChangeStoreTableLabel4( changedVal ){
					props.setAttributes( { store_table_label4: changedVal } );
				}
				
				function onChangeStoreTableColumn4( changedVal ){
					props.setAttributes( { store_table_column4: changedVal } );
				}
				
				function onChangeStoreTableLabel5( changedVal ){
					props.setAttributes( { store_table_label5: changedVal } );
				}
				
				function onChangeStoreTableColumn5( changedVal ){
					props.setAttributes( { store_table_column5: changedVal } );
				}
				
				function onChangeStoreTableLinkLabel( changedVal ){
					props.setAttributes( { store_table_link_label: changedVal } );
				}
				
				function onChangeProductProduct( changedVal ){
					props.setAttributes( { product_product: changedVal } );
				}
				
				function onChangeProductDisplayType( changedVal ){
					props.setAttributes( { product_display_type: changedVal } );
				}
				
				function onChangeAddToCartProduct( changedVal ){
					props.setAttributes( { addtocart_product: changedVal } );
				}
				
				function onChangeAddToCartBackground( changedVal ) {
					props.setAttributes( { addtocart_background_add: changedVal } );
				}
				
				function onChangeMembershipProducts( changedVal ){
					props.setAttributes( { membership_products: changedVal } );
				}
				
				function selected_shortcode_type( ){
					for( var i=0; i<WPEasyCartShortCodes.length; i++ ){
						if( WPEasyCartShortCodes[i].value == attributes.shortcode_type ){
							return WPEasyCartShortCodes[i].label;
						}
					}
				}
                
                function WPEasyCartTestChange( changedVal ){
                    //props.setAttributes( { testChangeVal: changedVal } );
                }
				
				function wpeasycart_gutenberg_checkbox_group( label, options, values, classes, onchange ){
					console.log( 'selected values: ' );
					console.log( values );
					return el(
                        'div',
                        {
                            className: 'wpeasycart-gt-group ' + classes
                        },
                        [ 
                            
                            el( 
                                'h3',
                                {inline:true},
                                label
                            ),
                            el(
                                'div',
                                { className: 'wpeasycart-gt-multiselect' },
                                [
                                    el(
                                        'div',
                                        {
                                            className: 'wpeasycart-gt-multiselect-container'
                                        },
                                        [    
                                            options.map( item => {
                                                return el(
                                                    CheckboxControl,
                                                    {
                                                        label: item.label,
                                                        value: item.value,
														checked: ( values.includes( item.value ) ) ? true : false,
														onClick: onchange,
														onChange: WPEasyCartTestChange,
														useState: true
                                                    }
                                                );
                                            } )
                                        ]
                                    )
                                ]
                            )
                        ]
                    );
				}
				
				return [
					!focus && el(
						'h3',
						{},
						wp_easycart_admin_block_language['shortcode'] + ' - ' + selected_shortcode_type( )
					),
					focus && el(
						SelectControl,
						{
							type: 'string',
							label: wp_easycart_admin_block_language['shortcode'],
							value: attributes.shortcode_type,
							onChange: onChangeShortcodeType,
							options: WPEasyCartShortCodes
						}
					),
					focus && el (
						'h3',
						{className: ( ( attributes.shortcode_type != 'ec_store' ) ? 'hidden ' : '' ) + 'wp-easycart-store-shortcode wp-easycart-block-item'},
						wp_easycart_admin_block_language['add-product-filters']
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' ) ? 'hidden ' : '' ) + 'wp-easycart-store-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['add-filters'],
							value: attributes.store_filter_type,
							onChange: onChangeStoreFilters,
							options: [
								{ value: '', label: wp_easycart_admin_block_language['show-featured-only'] },
								{ value: 'category', label: wp_easycart_admin_block_language['filter-category'] },
								{ value: 'menu1', label: wp_easycart_admin_block_language['filter-menu'] },
								{ value: 'menu2', label: wp_easycart_admin_block_language['filter-submenu'] },
								{ value: 'menu3', label: wp_easycart_admin_block_language['filter-subsubmenu'] },
								{ value: 'manufacturer', label: wp_easycart_admin_block_language['filter-manufacturer'] },
								{ value: 'product', label: wp_easycart_admin_block_language['fitler-product'] },
							]
						}
					),
					focus && wp_easycart_categories.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'category' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-category wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['category-filter'],
							value: attributes.store_category,
							onChange: onChangeStoreCategory,
							options: [
							  { value: '', label: wp_easycart_admin_block_language['show-all-categories'] },
							].concat( wp_easycart_categories )
						}
					),
					focus && wp_easycart_categories.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'category' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-category wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-category-id'],
							value: attributes.store_category,
							onChange: onChangeStoreCategory
						}
					),
					focus && wp_easycart_manufacturers.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'manufacturer' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-manufacturer wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['manufacturer-filter'],
							value: attributes.store_manufacturer,
							onChange: onChangeStoreManufacturer,
							options: [
							  { value: '', label: wp_easycart_admin_block_language['show-all-manufacturers'] },
							].concat( wp_easycart_manufacturers )
						}
					),
					focus && wp_easycart_manufacturers.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'manufacturer' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-manufacturer wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-manufacturer-id'],
							value: attributes.store_manufacturer,
							onChange: onChangeStoreManufacturer
						}
					),
					focus && wp_easycart_menulevel1.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'menu1' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-menu1 wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['menu-filter'],
							value: attributes.store_menulevel1,
							onChange: onChangeStoreMenuLevel1,
							options: [
							  { value: '', label: wp_easycart_admin_block_language['show-all-menus'] },
							].concat( wp_easycart_menulevel1 )
						}
					),
					focus && wp_easycart_menulevel1.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'menu1' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-menu1 wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-menu-level1-id'],
							value: attributes.store_menulevel1,
							onChange: onChangeStoreMenuLevel1
						}
					),
					focus && wp_easycart_menulevel2.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'menu2' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-menu2 wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['sub-menu-filter'],
							value: attributes.store_menulevel2,
							onChange: onChangeStoreMenuLevel2,
							options: [
							  { value: '', label: wp_easycart_admin_block_language['show-all-sub-menus'] },
							].concat( wp_easycart_menulevel2 )
						}
					),
					focus && wp_easycart_menulevel2.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'menu2' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-menu2 wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-menu-level2-id'],
							value: attributes.store_menulevel2,
							onChange: onChangeStoreMenuLevel2
						}
					),
					focus && wp_easycart_menulevel3.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'menu3' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-menu3 wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['sub-sub-menu-filter'],
							value: attributes.store_menulevel3,
							onChange: onChangeStoreMenuLevel3,
							options: [
							  { value: '', label: wp_easycart_admin_block_language['show-all-sub-sub-menus'] },
							].concat( wp_easycart_menulevel3 )
						}
					),
					focus && wp_easycart_menulevel3.length >= 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'menu3' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-menu3 wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-menu-level3-id'],
							value: attributes.store_menulevel3,
							onChange: onChangeStoreMenuLevel3
						}
					),
					focus && wp_easycart_products_model.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'product' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-product wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['product-to-display'],
							value: attributes.store_product,
							onChange: onChangeStoreProduct,
							options: [
							  { value: '', label: wp_easycart_admin_block_language['no-product-filter'] },
							].concat( wp_easycart_products_model )
						}
					),
					focus && wp_easycart_products_model.length >= 2000 && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store' || attributes.store_filter_type != 'product' ) ? 'hidden ' : '' ) + 'wp-easycart-filter-product wp-easycart-block-item',
							label: wp_easycart_admin_block_language['enter-product-sku'],
							value: attributes.store_product,
							onChange: onChangeStoreProduct,
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_account' ) ? 'hidden ' : '' ) + 'wp-easycart-account-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['success-redirect-url'],
							value: attributes.account_redirect,
							onChange: onChangeAccountRedirect,
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_categories' ) ? 'hidden ' : '' ) + 'wp-easycart-categories-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['categories-to-display'],
							value: attributes.categories_category,
							onChange: onChangeCategoriesCategory,
							options: [
							  { value: '0', label: wp_easycart_admin_block_language['show-featured-categories'] },
							  { value: '-1', label: wp_easycart_admin_block_language['show-top-level-categories'] },
							].concat( wp_easycart_categories )
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_category_view' ) ? 'hidden ' : '' ) + 'wp-easycart-category-view-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['categories-to-display'],
							value: attributes.category_view_category,
							onChange: onChangeCategoryViewCategory,
							options: [
							  { value: '0', label: wp_easycart_admin_block_language['show-featured-categories'] },
							  { value: '-1', label: wp_easycart_admin_block_language['show-top-level-categories'] },
							].concat( wp_easycart_categories )
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_category_view' ) ? 'hidden ' : '' ) + 'wp-easycart-category-view-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['columns'],
							value: attributes.category_view_columns,
							onChange: onChangeCategoryViewColumns,
							options: [
							  { value: '1', label: wp_easycart_admin_block_language['1column'] },
							  { value: '2', label: wp_easycart_admin_block_language['2columns'] },
							  { value: '3', label: wp_easycart_admin_block_language['3columns'] },
							  { value: '4', label: wp_easycart_admin_block_language['4columns'] },
							  { value: '5', label: wp_easycart_admin_block_language['5columns'] },
							  { value: '6', label: wp_easycart_admin_block_language['6columns'] }
							]
						}
					),
					focus && wp_easycart_products.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['products-to-display'],
							value: attributes.store_table_products,
							onChange: onChangeStoreTableProducts,
							multiple: 'multiple',
							options: wp_easycart_products
						}
					),
					focus && wp_easycart_products.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-product-ids'],
							value: attributes.store_table_products,
							onChange: onChangeStoreTableProducts
						}
					),
					focus && wp_easycart_menulevel1.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['menus-to-display'],
							value: attributes.store_table_menulevel1,
							onChange: onChangeStoreTableMenulevel1,
							multiple: 'multiple',
							options: wp_easycart_menulevel1
						}
					),
					focus && wp_easycart_menulevel1.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-menu-level1-ids'],
							value: attributes.store_table_menulevel1,
							onChange: onChangeStoreTableMenulevel1
						}
					),
					focus && wp_easycart_menulevel2.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['submenus-to-display'],
							value: attributes.store_table_menulevel2,
							onChange: onChangeStoreTableMenulevel2,
							multiple: 'multiple',
							options: wp_easycart_menulevel2
						}
					),
					focus && wp_easycart_menulevel2.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-menu-level2-ids'],
							value: attributes.store_table_menulevel2,
							onChange: onChangeStoreTableMenulevel2
						}
					),
					focus && wp_easycart_menulevel3.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['subsubmenus-to-display'],
							value: attributes.store_table_menulevel3,
							onChange: onChangeStoreTableMenulevel3,
							multiple: 'multiple',
							options: wp_easycart_menulevel3
						}
					),
					focus && wp_easycart_menulevel3.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-menu-level3-ids'],
							value: attributes.store_table_menulevel3,
							onChange: onChangeStoreTableMenulevel3
						}
					),
					focus && wp_easycart_categories.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['categories-to-display'],
							value: attributes.store_table_categories,
							onChange: onChangeStoreTableCategory,
							multiple: 'multiple',
							options: wp_easycart_categories
						}
					),
					focus && wp_easycart_categories.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-category-ids'],
							value: attributes.store_table_categories,
							onChange: onChangeStoreTableCategory
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['column1-label'],
							value: attributes.store_table_label1,
							onChange: onChangeStoreTableLabel1,
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['column1-data'],
							value: attributes.store_table_column1,
							onChange: onChangeStoreTableColumn1,
							options: WPEasyCartTableColumns
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['column2-label'],
							value: attributes.store_table_label2,
							onChange: onChangeStoreTableLabel2,
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['column2-data'],
							value: attributes.store_table_column2,
							onChange: onChangeStoreTableColumn2,
							options: WPEasyCartTableColumns
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['column3-label'],
							value: attributes.store_table_label3,
							onChange: onChangeStoreTableLabel3,
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['column3-data'],
							value: attributes.store_table_column3,
							onChange: onChangeStoreTableColumn3,
							options: WPEasyCartTableColumns
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['column4-label'],
							value: attributes.store_table_label4,
							onChange: onChangeStoreTableLabel4,
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['column4-data'],
							value: attributes.store_table_column4,
							onChange: onChangeStoreTableColumn4,
							options: WPEasyCartTableColumns
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['column5-label'],
							value: attributes.store_table_label5,
							onChange: onChangeStoreTableLabel5,
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['column5-data'],
							value: attributes.store_table_column5,
							onChange: onChangeStoreTableColumn5,
							options: WPEasyCartTableColumns
						}
					),
					focus && el(
						TextControl,
						{
							tagName: 'input',
							className: ( ( attributes.shortcode_type != 'ec_store_table' ) ? 'hidden ' : '' ) + 'wp-easycart-store-table-shortcode wp-easycart-block-item',
							label: wp_easycart_admin_block_language['link-label'],
							value: attributes.store_table_link_label,
							onChange: onChangeStoreTableLinkLabel,
						}
					),
					focus && wp_easycart_products.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_product' ) ? 'hidden ' : '' ) + 'wp-easycart-product-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['product-to-display'],
							value: attributes.product_product,
							onChange: onChangeProductProduct,
							options: wp_easycart_products
						}
					),
					focus && wp_easycart_products.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_product' ) ? 'hidden ' : '' ) + 'wp-easycart-product-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-product-id'],
							value: attributes.product_product,
							onChange: onChangeProductProduct
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_product' ) ? 'hidden ' : '' ) + 'wp-easycart-product-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['display-type'],
							value: attributes.product_display_type,
							onChange: onChangeProductDisplayType,
							options: [
								{ value: '1', label: wp_easycart_admin_block_language['display-type1'] },
								{ value: '2', label: wp_easycart_admin_block_language['display-type2'] },
								{ value: '3', label: wp_easycart_admin_block_language['display-type3'] }
							]
						}
					),
					focus && wp_easycart_products.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_addtocart' ) ? 'hidden ' : '' ) + 'wp-easycart-addtocart-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['product-to-display'],
							value: attributes.addtocart_product,
							onChange: onChangeAddToCartProduct,
							options: wp_easycart_products
						}
					),
					focus && wp_easycart_products.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_addtocart' ) ? 'hidden ' : '' ) + 'wp-easycart-addtocart-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['enter-product-id'],
							value: attributes.addtocart_product,
							onChange: onChangeAddToCartProduct
						}
					),
					focus && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_addtocart' ) ? 'hidden ' : '' ) + 'wp-easycart-addtocart-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['background-type'],
							value: attributes.addtocart_background_add,
							onChange: onChangeAddToCartBackground,
							options: [
								{ value: '0', label: wp_easycart_admin_block_language['background-type0'] },
								{ value: '1', label: wp_easycart_admin_block_language['background-type1'] }
							]
						}
					),
					focus && wp_easycart_products.length < 2000 && el(
						SelectControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_membership' ) ? 'hidden ' : '' ) + 'wp-easycart-membership-shortcode wp-easycart-block-item',
							type: 'string',
							label: wp_easycart_admin_block_language['purchase-required-1'],
							value: attributes.membership_products,
							onChange: onChangeMembershipProducts,
							multiple:'multiple',
							options: wp_easycart_products
						}
					),
					focus && wp_easycart_products.length >= 2000 && el(
						TextControl,
						{
							className: ( ( attributes.shortcode_type != 'ec_membership' ) ? 'hidden ' : '' ) + 'wp-easycart-membership-shortcode wp-easycart-block-item',
							tagName: 'input',
							label: wp_easycart_admin_block_language['purchase-required-2'],
							value: attributes.membership_products,
							onChange: onChangeMembershipProducts
						}
					)
				];
			},
			save: function( props ) {
				var attributes = props.attributes;
				if( attributes.shortcode_type == "ec_store" ){
					var storeShortcode = '[ec_store';
					
					if( attributes.store_category != '' )
						storeShortcode += ' groupid="' + attributes.store_category + '"';
					else if( attributes.store_manufacturer != '' )
						storeShortcode += ' manufacturerid="' + attributes.store_manufacturer + '"';
					else if( attributes.store_product != '' )
						storeShortcode += ' modelnumber="' + attributes.store_product + '"';
					else if( attributes.store_menulevel3 != '' )
						storeShortcode += ' subsubmenuid="' + attributes.store_menulevel3 + '"';
					else if( attributes.store_menulevel2 != '' )
						storeShortcode += ' submenuid="' + attributes.store_menulevel2 + '"';
					else if( attributes.store_menulevel1 != '' )
						storeShortcode += ' menuid="' + attributes.store_menulevel1 + '"';
					
					return storeShortcode + ']';
					
				}else if( attributes.shortcode_type == "ec_account" ){
					var accountShortcode = '[ec_account';
					
					if( attributes.account_redirect != '' )
						accountShortcode += ' redirect="' + attributes.account_redirect + '"';
						
					return accountShortcode + ']';
					
				}else if( attributes.shortcode_type == "ec_categories" ){
					return '[ec_categories groupid="' + attributes.categories_category + '"]';
					
				}else if( attributes.shortcode_type == "ec_category_view" ){
					return '[ec_category_view groupid="' + attributes.category_view_category + '" columns="' + attributes.category_view_columns + '"]';
					
				}else if( attributes.shortcode_type == "ec_store_table" ){
					return '[ec_store_table productid="' + attributes.store_table_products + '" menuid="' + attributes.store_table_menulevel1 + '" submenuid="' + attributes.store_table_menulevel2 + '" subsubmenuid="' + attributes.store_table_menulevel3 + '" categoryid="' + attributes.store_table_categories + '" labels="' + attributes.store_table_label1 + ',' + attributes.store_table_label2 + ',' + attributes.store_table_label3 + ',' + attributes.store_table_label4 + ',' + attributes.store_table_label5 + '" columns="' + attributes.store_table_column1 + ',' + attributes.store_table_column2 + ',' + attributes.store_table_column3 + ',' + attributes.store_table_column4 + ',' + attributes.store_table_column5 + '"]';
					
				}else if( attributes.shortcode_type == "ec_product" ){
					return '[ec_product productid="' + attributes.product_product + '" style="' + attributes.product_display_type + '"]';
					
				}else if( attributes.shortcode_type == "ec_addtocart" ){
					return '[ec_addtocart productid="' + attributes.addtocart_product + '" background_add="' + attributes.addtocart_background_add + '"]';
					
				}else if( attributes.shortcode_type == "ec_cartdisplay" ){
					return '[ec_cartdisplay]';
					
				}else if( attributes.shortcode_type == "ec_membership" ){
					return '[ec_membership productid="' + attributes.membership_product + '"]MEMBER CONTENT HERE[/ec_membership][ec_membership_alt productid="' + attributes.membership_product + '"]NON-MEMBER NOTICE HERE[/ec_membership_alt]';
				
				}else{
					return "["+attributes.shortcode_type+"]";
				}
			}
		} );
	} )(
	   window.wp.blocks,
	   window.wp.i18n,
	   window.wp.element
	);
});