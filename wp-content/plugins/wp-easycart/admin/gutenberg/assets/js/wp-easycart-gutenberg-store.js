jQuery( document ).ready( 
	function() {
		( 
			function( blocks, blockEditor, i18n, element, components ) {
				var el = element.createElement;
				var BlockControls = blockEditor.BlockControls;
				var ToolbarGroup = components.ToolbarGroup;
				var CheckboxControl = components.CheckboxControl;
				var Component = element.Component;

				const { sprintf } = i18n;

				class WPEasyCartStorePreview extends Component {

					constructor( props ) {
						super( props );
					}

					render(){
						var props = this.props;
						var el = element.createElement;

						function WPEasyCartCategoryRenderItems( props ){
							var el_list = [];
							for( var i = 0; i < props.products.length; i++ ){
								el_list.push( 
									el(
										'li',
										{
											class: 'wp-easycart-guten-item'
										},
										el( 'div', { class: 'wp-easycart-guten-item-image' }, 
										   el( 'img', {src: props.products[i].first_image} )
										),
										el( 'h3', { class: 'wp-easycart-guten-item-title' }, props.products[i].title ),
										el( 'div', { class: 'wp-easycart-guten-item-price' }, props.products[i].price ),
										el( 'div', { class: 'wp-easycart-guten-item-addtocart' }, wp_easycart_admin_block_language['add-to-cart'] )
									)
								);
							}
							element.render( 
								el(
									'ul',
									{
										class: 'wp-easycart-guten-item-list'
									},
									el_list
								),
								document.getElementById( 'block-wpeasycart-store-display-' + props.clientId )
							);
						}

						function WPEasyCartCategoryRenderEmpty( props ){
							element.render( el( 'div', { class: 'wp-easycart-guten-list-empty' },
								el( 'div', { class: 'wp-easycart-guten-list-empty-text' }, wp_easycart_admin_block_language['store-categories-empty'] ),
								el( 'button', {
									class: 'components-button is-primary wp-easycart-guten-search-button-done wp-easycart-guten-list-empty-button',
									onClick: ( ) => { props.setAttributes( { isEditing : !props.attributes.isEditing } ); }
								}, wp_easycart_admin_block_language['edit-block'] )
							), document.getElementById( 'block-wpeasycart-store-display-' + props.clientId ) );
						}

						function WPEasyCartCategoryGetPreview( props ){
							jQuery.ajax( 
								{
									url: wpeasycart_guten.jsonurl + 'wp-easycart/v1/products/categories/',
									type: 'get',
									data: {
										ids:props.attributes.groupid
									},
									success: function( response ){
										props.products = response.results;
										if( response.results.length > 0 ){
											WPEasyCartCategoryRenderItems( props );
										}else{
											WPEasyCartCategoryRenderEmpty( props );
										}
									}
								}
							);
						}

						WPEasyCartCategoryGetPreview( props );

						return(
							el( 'div', { class: 'wp-easycart-guten-store-display-wrap', id: 'block-wpeasycart-store-display-' + props.clientId },
								el( 'div', { class: 'wp-easycart-guten-search-list-loading' },
									el( 'div', { class: 'wp-easycart-guten-loader' },
										el( 'div', null ),
										el( 'div', null ),
										el( 'div', null )
									)
								)
							)
						);
					}

				}

				class WPEasyCartCategorySearchControl extends Component {

					constructor( props ) {
						super( props );
					}

					render() {
						var props = this.props;
						var el = element.createElement;
						WPEasyCartCategorySearchGetItems( props, '' );

						function WPEasyCartCategorySearchSelectedItem( data ) {
							var el = element.createElement;
							return el( 'div', { class: 'wp-easycart-guten-search-item-selected', 'data-id': data.category.category_id },
								el( 'span', { class: 'wp-easycart-guten-search-item-selected-text' }, data.category.category_name ),
								el( 'button', { class: 'wp-easycart-guten-search-item-selected-remove', onClick: () => { WPEasyCartCategorySearchItemUpdate( data, data.category.category_id ) } },
									el( 'span', { class: 'dashicon dashicons dashicons-dismiss' } ) 
								)
							);
						}

						function WPEasyCartCategorySearchItemUpdate( data, category_id ) {
							data.props.updateGroupID( category_id );
							WPEasyCartCategorySearchSetSelected( data.props );
							WPEasyCartCategorySearchRenderItems( data.props );
						}

						function WPEasyCartCategorySearchItemClear( props ) {
							props.attributes.groupid = '';
							WPEasyCartCategorySearchSetSelected( props );
							WPEasyCartCategorySearchRenderItems( props );
						}

						function WPEasyCartCategorySearchItem( data ) {
							var el = element.createElement;
							return el( 'div', { class: 'wp-easycart-guten-search-item-wrap' },
								el(
									CheckboxControl,
									{
										label: data.category.category_name,
										className: 'wp-easycart-guten-search-item-check',
										value: data.category.category_id,
										checked: data.isChecked,
										onChange: ( ) => {
											WPEasyCartCategorySearchItemUpdate( data, data.category.category_id );
										},
									},
								),
								el( 'div', { class: 'wp-easycart-guten-search-item-count' }, data.category.total_products + ' ' + wp_easycart_admin_block_language['products'] )
							);
						}

						function WPEasyCartCategorySearchGetItems( props, search = '' ) {
							jQuery.ajax(
								{
									url: wpeasycart_guten.jsonurl + 'wp-easycart/v1/categories/',
									type: 'get',
									data: {s:search},
									success: function( response ){
										props.categories = response.results;
										props.categories_search = response.results;
										WPEasyCartCategorySearchSetSelected( props );
										WPEasyCartCategorySearchRenderItems( props );
									}
								}
							);
						}

						function WPEasyCartCategorySearchUpdate( props, search ) {
							WPEasyCartCategorySearchGetItems( props, search );
							props.categories_search = props.categories.filter( function( category ){
								return category.category_name.toLowerCase( ).includes( search.toLowerCase( ) );
							} );
						}

						function WPEasyCartCategorySearchProcessSelected( selected ) {
							var selected_items = [];
							if( selected ){
								selected_items = selected.split( ',' );
							}
							return selected_items;
						}

						function WPEasyCartCategorySearchRenderItems( props ){
							var el = element.createElement;
							var categories = props.categories_search;
							var selected_items = WPEasyCartCategorySearchProcessSelected( props.attributes.groupid );
							var category_item_els = [];
							if( categories && categories.length > 0 ){
								for( var i=0; i<categories.length; i++ ){
									category_item_els.push(
										el(
											WPEasyCartCategorySearchItem,
											{
												props: props,
												categories: categories,
												category: categories[i],
												isChecked: selected_items.includes( categories[i].category_id )
											}
										)
									);
								}
							}else{
								category_item_els.push( el( 'div', { class: 'wp-easycart-guten-search-none' }, wp_easycart_admin_block_language['no-categories'] ) );
							}
							element.render( category_item_els, document.getElementById( 'block-wpeasycart-searchlist-' + props.clientId ) );
						}

						function WPEasyCartCategorySearchSetSelected( props ) {
							var el = element.createElement;
							var categories = props.categories;
							var selected_items = WPEasyCartCategorySearchProcessSelected( props.attributes.groupid );
							var render_els = [];
							var selected_count = 0;
							if( selected_items ){
								selected_count = selected_items.length;
							}
							render_els.push( el( 'div', { class: 'wp-easycart-guten-search-count-wrap' },
								el( 'span', { class: 'wp-eaycart-guten-search-selected-count' }, sprintf( wp_easycart_admin_block_language['categories-selected'], selected_count ) ),
								el( 'a', { class: 'wp-eaycart-guten-search-selected-cancel', onClick: () => { WPEasyCartCategorySearchItemClear( props ); } }, wp_easycart_admin_block_language['clear-all'] )
							) );
							if ( wp_easycart_categories && wp_easycart_categories.length > 0 ) {
								for( var i = 0; i < wp_easycart_categories.length; i++ ) {
									if( selected_items.includes( wp_easycart_categories[i].value ) ){
										render_els.push( el( WPEasyCartCategorySearchSelectedItem, { props: props, categories: categories, category: {category_id:wp_easycart_categories[i].value,category_name:wp_easycart_categories[i].label} } ) );
									}
								}
							}
							element.render( el( 'div', { class: 'wp-eaycart-guten-search-selected-wrap' }, render_els ), document.getElementById( 'block-wpeasycart-searchlist-selected-' + props.clientId ) );
						}

						return(
							el( 'div', { class: 'wp-easycart-guten-search-wrap' },
								el( 'div', { class: 'wp-easycart-guten-search-selected-list-wrap', id: 'block-wpeasycart-searchlist-selected-' + props.clientId } ),
								el( 'div', { class: 'wp-easycart-guten-search-bar-wrap' },
									el( 'input', {
										type: 'text',
										placeholder: wp_easycart_admin_block_language['search-placeholder'],
										class: 'wp-easycart-guten-search-ele',
										onChange: e => {
											WPEasyCartCategorySearchUpdate( props, e.target.value );
										}
									} )
								),
								el( 'div', { class: 'wp-easycart-guten-search-item-list-wrap', id: 'block-wpeasycart-searchlist-' + props.clientId },
									el( 'div', { class: 'wp-easycart-guten-search-list-loading' },
										el( 'div', { class: 'wp-easycart-guten-loader' },
											el( 'div', null ),
											el( 'div', null ),
											el( 'div', null )
										)
									)
								),
								el( 'div', { class: 'wp-easycart-guten-search-button-wrap' },
									el( 'input', { type: 'button', value: wp_easycart_admin_block_language['done'], class: 'components-button is-primary wp-easycart-guten-search-button-done', onClick: () => { props.saveBlock( ); } } ),
									el( 'a', { href: '#', class: 'wp-easycart-guten-search-button-cancel', onClick: () => { props.cancelBlock( ); } }, wp_easycart_admin_block_language['cancel'] )
								)
							)
						);
					}
				}

				blocks.registerBlockType('wp-easycart/storecat', {
					title: wp_easycart_admin_block_language['store-category'],
					icon: el( 'svg', { viewBox: '0 0 25 25' },
						el( 'rect', { width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { y:9, width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { y:18, width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { x:9, width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { x:9, y:9, width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { x:9, y:18,width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { x:18, width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { x:18, y:9, width:7, height:7, fill:'#7eb044' } ),
						el( 'rect', { x:18, y:18, width:7, height:7, fill:'#7eb044' } )
					),
					category: 'wp-easycart',
					description: 'Create the store shortcode, which drives your store.',
					example: {},
					attributes: {
						isPreview:      { type: 'bool', default: true },
						isEditing:      { type: 'bool', default: true },
						oldgroupid:     { type: 'string', default: 'NOGROUP' },
						groupid:        { type: 'string', default: 'NOGROUP' }
					},
					edit: function( props ) {
						var attributes = props.attributes;

						if ( !props.isSelected && attributes.isPreview ) {
							return el( 'img', { src: wp_easycart_admin_preview_images['store'] } );
						}

						if ( !attributes.isEditing ) {
							return el( 'div', null, el( BlockControls, null, el( ToolbarGroup, {
								controls: [{
								  icon: 'edit',
									title: wp_easycart_admin_block_language['edit'],
									onClick: ( ) => { props.setAttributes( { isEditing : !attributes.isEditing } ); },
									isActive: attributes.isEditing,
								} ]
							} ) ),
							el( WPEasyCartStorePreview, props ) );
						}

						props.attributes.isPreview = false;

						if( attributes.groupid == 'NOGROUP' ){
							attributes.groupid = '';
						}

						props.updateGroupID = ( _groupid ) => {
							if( !_groupid || _groupid == '' ){
								return;
							}
							var grouparr = [];
							if( props.attributes.groupid.length > 0 ){
								grouparr = props.attributes.groupid.split( ',' );
							}
							const index = grouparr.indexOf( _groupid );
							if( index > -1 ){
								grouparr.splice( index, 1 );
							}else{
								grouparr.push( _groupid );
							}
							props.attributes.groupid = grouparr.join( ',' );
						}

						props.saveBlock = ( ) => {
							props.attributes.oldgroupid = props.attributes.groupid;
							props.setAttributes( { isEditing : false } );
						}

						props.cancelBlock = ( ) => {
							props.attributes.groupid = props.attributes.oldgroupid;
							props.setAttributes( { isEditing : false } );
						}

						return el( 'div', null,
							el( BlockControls, null, 
								el( ToolbarGroup, {
									controls: [{
										icon: 'edit',
										title: wp_easycart_admin_block_language['edit'],
										onClick: ( ) => { props.setAttributes( { isEditing : !attributes.isEditing } ); },
										isActive: attributes.isEditing,
									}]
								} )
							),
							el( 'div', { class: 'wp-easycart-guten-edit-wrap' },
								el( 'div', { class: 'wp-easycart-guten-edit-row-wrap' },
									el( 'div', { class: 'wp-easycart-guten-edit-header' }, 
										el( 'img', { src: wp_easycart_admin_preview_images['store-icon'] } ),
										el( 'span', null, wp_easycart_admin_block_language['store-category'] )
									),
								   el( 'div', { class: 'wp-easycart-guten-edit-block-desc' }, wp_easycart_admin_block_language['store-category-desc'] )
								),
								el( 'div', { class: 'wp-easycart-guten-edit-row-wrap' },
									el( WPEasyCartCategorySearchControl, props ),
									el( 'input', { type: 'hidden', class: 'wp-easycart-guten-group-id', value: props.attributes.groupid } )
								)
							)
						);
					},
					save: function( props ){
						props.attributes.isPreview = false;

						return el( 'div', { }, '[ec_store groupid="' + props.attributes.groupid + '"]' );
					}
				} )
			}
		)( window.wp.blocks, window.wp.blockEditor, window.wp.i18n, window.wp.element, window.wp.components );
	}
);