jQuery( document ).ready( 
	function( ) {
		(
			function( blocks, blockEditor, i18n, element, components, data ) {
				var el = element.createElement;
				var BlockControls = blockEditor.BlockControls;
				var ToolbarGroup = components.ToolbarGroup;
				var Component = element.Component;
				var TextControl = components.TextControl;

				class WPEasyCartSearchPreview extends Component {
					constructor( props ) {
						super( props );
					}
					render() {
						var props = this.props;
						var el = window.wp.element.createElement;
						return(
							el(
								'div',
								{
									class: 'wp-easycart-guten-search-display-wrap'
								},
								el(
									'input',
									{
										type: 'text', 
										class: 'wp-easycart-guten-search-preview-ele',
									},
								),
								el(
									'button',
									{
										type: 'button',
										class: 'wp-easycart-guten-search-preview-button',
									},
									props.attributes.label
								),
							)
						);
					}
				}

				class WPEasyCartSearchControl extends Component {
					constructor( props ) {
						super( props );
					}

					render() {
						var props = this.props;
						var el = window.wp.element.createElement;
						function WPEasyCartSearchUpdateLabel( props, search_label ) {
							props.attributes.label = search_label;
						}

						return(
							el(
								'div',
								{
									class: 'wp-easycart-guten-search-wrap'
								},
								el(
									'div',
									{
										class: 'wp-easycart-guten-search-selected-list-wrap',
										id: 'block-wpeasycart-searchlist-selected-' + props.clientId,
									},
								),
								el(
									'div',
									{
										class: 'wp-easycart-guten-search-bar-wrap'
									},
									el(
										TextControl,
										{
											label: 'Enter Search Button Label:',
											className: 'wp-easycart-guten-search-ele',
											value: props.attributes.label,
											onChange: ( value ) => {
												props.setAttributes(
													{
														label: value
													}
												);
											},
											placeholder: 'Search Now',
										}
									),
								),
								el(
									'div',
									{
										class: 'wp-easycart-guten-search-bar-wrap'
									},
									el(
										TextControl,
										{
											label: 'Search Landing Page ID:',
											className: 'wp-easycart-guten-search-ele',
											value: props.attributes.searchPageID,
											onChange: ( value ) => {
												props.setAttributes(
													{
														searchPageID: value
													}
												);
											},
											placeholder: 'This should be an ID',
										},
									),
								),
								el(
									'div',
									{
										class: 'wp-easycart-guten-search-button-wrap'
									},
									el(
										'input',
										{
											type: 'button',
											value: wp_easycart_admin_block_language['done'],
											class: 'components-button is-primary wp-easycart-guten-search-button-done',
											onClick: () => {
												props.saveBlock();
											},
										},
									),
									el(
										'a',
										{
											href: '#',
											class: 'wp-easycart-guten-search-button-cancel',
											onClick: () => {
												props.cancelBlock();
											},
										},
										wp_easycart_admin_block_language['cancel'],
									),
								),
							)
						);
					}
				}

				blocks.registerBlockType(
					'wp-easycart/search',
					{
						title: wp_easycart_admin_block_language['search-title'],
						icon: el( 
							'svg',
							{
								viewBox: '0 0 22.82 23.28'
							},
							el(
								'circle',
								{
									cx:"10.07",
									cy:"10.07",
									r:"10.07",
									fill:'#7eb044'
								},
							),
							el(
								'rect',
								{
									x:"13",
									y:"17",
									width:"11",
									height:"4",
									transform:"translate(17.87 -8.54) rotate(45)",
									fill:'#7eb044',
								},
							),
							el(
								'circle',
								{
									cx:"10.09",
									cy:"10.18",
									r:"6.85",
									fill:'#ffffff'
								},
							),
						),
						category: 'wp-easycart',
						description: wp_easycart_admin_block_language['search-desc'],
						example: { },
						attributes: {
							isPreview: {
								type: 'bool',
								default: true
							},
							isEditing: {
								type: 'bool',
								default: true
							},
							label: {
								type: 'string',
								default: 'Search'
							},
							searchPageID: {
								type: 'string',
								default: wpeasycart_guten.postid
							},
						},
						edit: function( props ) {
							var attributes = props.attributes;

							if ( ! props.isSelected && attributes.isPreview ) {
								return el(
									'img',
									{
										src: wp_easycart_admin_preview_images['search']
									},
								);
							}

							if ( ! attributes.isEditing ) {
								return el(
									'div',
									null,
									el(
										BlockControls,
										null,
										el(
											ToolbarGroup,
											{
												controls: [
													{
														icon: 'edit',
														title: wp_easycart_admin_block_language['edit'],
														onClick: ( ) => { props.setAttributes( { isEditing : !attributes.isEditing } ); },
														isActive: attributes.isEditing,
													}
												]
											}
										),
									),
									el( 
										WPEasyCartSearchPreview,
										props
									),
								);
							}

							props.attributes.isPreview = false;

							props.saveBlock = ( ) => {
								props.attributes.oldlabel = props.attributes.label;
								props.setAttributes(
									{
										isEditing: false
									}
								);
							}

							props.cancelBlock = ( ) => {
								props.attributes.label = props.attributes.oldlabel;
								props.setAttributes(
									{
										isEditing: false
									}
								);
							}

							return el(
								'div',
								null,
								el(
									BlockControls,
									null,
									el(
										ToolbarGroup,
										{
											controls: [
												{
													icon: 'edit',
													title: wp_easycart_admin_block_language['edit'],
													onClick: () => {
														props.setAttributes(
															{
																isEditing: ! attributes.isEditing
															}
														);
													},
													isActive: attributes.isEditing,
												}
											]
										},
									),
								),
								el(
									'div',
									{
										class: 'wp-easycart-guten-edit-wrap'
									},
									el(
										'div',
										{
											class: 'wp-easycart-guten-edit-row-wrap'
										},
										el(
											'div',
											{
												class: 'wp-easycart-guten-edit-header'
											},
											el(
												'img',
												{
													src: wp_easycart_admin_preview_images['search-icon']
												}
											),
											el(
												'span',
												null,
												wp_easycart_admin_block_language['search-title']
											)
										),
									   el(
										   'div',
										   {
											   class: 'wp-easycart-guten-edit-block-desc'
										   },
										   wp_easycart_admin_block_language['search-desc']
									   ),
									),
									el(
										'div',
										{
											class: 'wp-easycart-guten-edit-row-wrap'
										},
										el(
											WPEasyCartSearchControl,
											props,
										),
										el(
											'input',
											{
												type: 'hidden',
												class: 'wp-easycart-guten-search-label',
												value: props.attributes.search
											}
										),
									),
								),
							);
						},
						save: function( props ){
							props.attributes.isPreview = false;
							return el( 'div', { }, '[ec_search label="' + props.attributes.label + '" postid="' + props.attributes.searchPageID + '"]' );
						}
					}
				)
			}
		)( 
			window.wp.blocks,
			window.wp.blockEditor,
			window.wp.i18n,
			window.wp.element,
			window.wp.components,
			window.wp.data
		);
	}
);