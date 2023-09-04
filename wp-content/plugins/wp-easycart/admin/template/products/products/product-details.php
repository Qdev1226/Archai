<?php wp_easycart_admin( )->load_new_slideout( 'product' ); ?>
<?php wp_easycart_admin( )->load_new_slideout( 'manufacturer' ); ?>
<?php wp_easycart_admin( )->load_new_slideout( 'optionset' ); ?>
<?php wp_easycart_admin( )->load_new_slideout( 'advanced-optionset' ); ?>

<div class="ec_admin_message_error" id="ec_admin_product_activate_error"<?php if( !$this->id || $this->product->activate_in_store ){ ?> style="display:none;"<?php }?>><?php esc_attr_e( 'Your product is NOT ACTIVE and is currently not showing on your online store', 'wp-easycart' ); ?> <a href="#" style="float:right; margin:0 15px 0;" onclick="jQuery( document.getElementById( 'activate_in_store' ) ).prop( 'checked', true ); ec_admin_save_product_details_basic( ); jQuery( this ).parent( ).fadeOut( ); return false;"><?php esc_attr_e( 'Activate', 'wp-easycart' ); ?></a></div>
<div class="ec_admin_message_error" id="ec_admin_product_store_startup_error"<?php if( !$this->id || $this->product->show_on_startup ){ ?> style="display:none;"<?php }?>><?php esc_attr_e( 'Your product is NOT showing on your main store page.', 'wp-easycart' ); ?> <a href="#" style="float:right; margin:0 15px 0;" onclick="jQuery( document.getElementById( 'show_on_startup' ) ).prop( 'checked', true ); ec_admin_save_product_details_general_options( ); jQuery( this ).parent( ).fadeOut( ); return false;"><?php esc_attr_e( 'Add to Store', 'wp-easycart' ); ?></a></div>
<?php if( get_option( 'ec_option_display_as_catalog' ) ){ ?>
<div class="ec_admin_message_error" id="ec_admin_product_store_startup_error"><?php echo sprintf( esc_attr__( 'Your store is in catalog mode and all cart features are disabled. %1$sClick here%2$s to visit your product settings page and disabled catalog mode to add back your shopping cart.', 'wp-easycart' ), '<a href="admin.php?page=wp-easycart-settings&subpage=products">', '</a>' ); ?></div>
<?php }?>

<input type="hidden" name="ec_admin_form_action" value="<?php echo esc_attr( $this->form_action ); ?>" />
<input type="hidden" name="product_id" id="product_id"value="<?php echo esc_attr( $this->product->product_id ); ?>" />
<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_product_details_nonce', 'wp-easycart-product-details' ); ?>

<div class="ec_admin_settings_panel ec_admin_details_panel">
	<div class="ec_admin_important_numbered_list">

	<div class="ec_admin_flex_row">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
			<?php do_action( 'wp_easycart_admin_product_details_basic_start', $this->product ); ?>
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_basic_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header" style="z-index:4; background:#ffffff;">
			<div class="dashicons-before dashicons-tag"></div>
			<span id="product_title"><?php if( !$this->id ){ ?><?php esc_attr_e( 'CREATE NEW PRODUCT', 'wp-easycart' ); ?><?php }else{ ?><?php esc_attr_e( 'EDIT PRODUCT', 'wp-easycart' ); ?><?php }?></span>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
				<?php wp_easycart_admin( )->helpsystem->print_vids_url('products', 'products', 'details');?>
				<?php do_action( 'wp_easycart_admin_product_details_buttons_pre', $this->product ); ?>
				<a href="admin.php?page=wp-easycart-products&subpage=products&ec_admin_form_action=add-new" class="ec_page_title_button<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>" id="ec_admin_product_details_add_new_button" onclick="wp_easycart_admin_open_slideout( 'new_product_box' ); return false;"><?php esc_attr_e( 'Add New Product', 'wp-easycart' ); ?></a>
				<a href="<?php echo esc_attr( wp_easycart_admin_products( )->get_product_link( $this->product->product_id ) ); ?>" target="_blank" class="ec_page_title_button<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>" id="ec_admin_product_details_view_product_link"><?php esc_attr_e( 'View Product', 'wp-easycart' ); ?></a>
				<a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Back to Products', 'wp-easycart' ); ?></a>
				<a href="http://chart.apis.google.com/chart?cht=qr&chs=500x500&chl=<?php echo urlencode( esc_url_raw( wp_easycart_admin_products( )->get_product_link( $this->product->product_id ) ) ); ?>" target="_blank"><img src="http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=<?php echo urlencode( esc_url_raw( wp_easycart_admin_products( )->get_product_link( $this->product->product_id ) ) ); ?>" style="float:right; max-width:50px; height:auto;" /></a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_settings_currency_section">
			<?php do_action( 'wp_easycart_admin_product_details_basic_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" id="product_create_button" onclick="return ec_admin_save_product_details_basic( );" value="<?php if( !$this->id ){ ?><?php esc_attr_e( 'Create New Product', 'wp-easycart' ); ?><?php }else{ ?><?php esc_attr_e( 'Update Product', 'wp-easycart' ); ?><?php }?>" />
			</div>
		</div>
		</div>
	</div>
	<?php do_action( 'wp_easycart_admin_product_details_sections_pre' ); ?>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>" id="wp_easycart_product_details_images_basic">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_images_loader" ); ?>
			<div class="ec_admin_settings_label ec_admin_expand_section_header">
				<div class="dashicons-before dashicons-format-gallery"></div>
				<span><?php esc_attr_e( 'PRODUCT IMAGES', 'wp-easycart' ); ?></span>
				<a href="#images" class="ec_admin_expand_section" data-section="ec_admin_product_details_images_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
				<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
					<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
						<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
					</a>
				</div>
			</div>
			<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_images_section">
				<?php do_action( 'wp_easycart_admin_product_details_images_fields' ); ?>
				<div class="ec_admin_products_submit">
					<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_images( );" value="<?php esc_attr_e( 'Update Images', 'wp-easycart' ); ?>" />
				</div>
				<?php do_action( 'wp_easycart_admin_product_details_after_images_save_button' ); ?>
			</div>
		</div>
	</div>

	<?php do_action( 'wp_easycart_admin_product_details_after_images' ); ?>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
			<?php do_action( 'wp_easycart_admin_product_details_quantity_start', $this->product ); ?>
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_quantities_loader" ); ?>
			<div class="ec_admin_settings_label ec_admin_expand_section_header">
				<div class="dashicons-before dashicons-chart-area"></div>
				<span><?php esc_attr_e( 'QUANTITY OPTIONS', 'wp-easycart' ); ?></span>
				<a href="#quantities" class="ec_admin_expand_section" data-section="ec_admin_product_details_quantities_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
				<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
					<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
						<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
					</a>
				</div>
			</div>
			<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_quantities_section">
				<?php do_action( 'wp_easycart_admin_product_details_quantity_fields', $this->product ); ?>
				<div class="ec_admin_products_submit">
					<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_quantities( );" value="<?php esc_attr_e( 'Update Quantities', 'wp-easycart' ); ?>" />
				</div>
				<?php do_action( 'wp_easycart_admin_product_details_optionitem_quantity_fields' ); ?>
			</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
			<?php do_action( 'wp_easycart_admin_product_details_pricing_start', $this->product ); ?>
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_pricing_loader" ); ?>
			<div class="ec_admin_settings_label ec_admin_expand_section_header">
				<div class="dashicons-before dashicons-chart-pie"></div>
				<span><?php esc_attr_e( 'PRICING OPTIONS', 'wp-easycart' ); ?></span>
				<a href="#pricing" class="ec_admin_expand_section" data-section="ec_admin_product_details_pricing_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
				<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
					<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
						<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
					</a>
				</div>
			</div>
			<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_pricing_section">
				<?php do_action( 'wp_easycart_admin_product_details_pricing_fields', $this->product ); ?>
				<div class="ec_admin_products_submit">
					<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_pricing( );" value="<?php esc_attr_e( 'Update Pricing', 'wp-easycart' ); ?>" />
				</div>
				<?php do_action( 'wp_easycart_admin_product_details_advanced_pricing_fields' ); ?>
			</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>" id="wp_easycart_product_details_options_basic">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_options_loader" ); ?>
			<div class="ec_admin_settings_label ec_admin_expand_section_header">
				<div class="dashicons-before dashicons-admin-settings"></div>
				<span><?php esc_attr_e( 'OPTION SETS', 'wp-easycart' ); ?></span>
				<a href="#options" class="ec_admin_expand_section" data-section="ec_admin_product_details_options_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
				<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
					<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
						<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
					</a>
				</div>
			</div>
			<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_options_section">

			<div class="ec_admin_option_add_new_row">
					<input type="button" value="<?php esc_attr_e( 'QUICK OPTION CREATOR', 'wp-easycart' ); ?>" onclick="ec_admin_open_new_option( );" />
				</div>
				<div class="ec_admin_option_add_new_row"><a href="admin.php?page=wp-easycart-products&subpage=option" target="_blank"><?php esc_attr_e( 'FULL OPTION MANAGER', 'wp-easycart' ); ?></a></div>

				<?php do_action( 'wp_easycart_admin_product_details_options_fields' ); ?>
				<div class="ec_admin_products_submit">
					<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_options( );" value="<?php esc_attr_e( 'Update Options', 'wp-easycart' ); ?>" />
				</div>
				<?php do_action( 'wp_easycart_admin_product_details_after_options_save_button' ); ?>
			</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_general_options_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-admin-tools"></div>
			<span><?php esc_attr_e( 'BASIC SETTINGS', 'wp-easycart' ); ?></span>
			<a href="#general-options" class="ec_admin_expand_section" data-section="ec_admin_product_details_general_options_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_general_options_section" style="padding-top:10px;">
			<?php do_action( 'wp_easycart_admin_product_details_general_options_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_general_options( );" value="<?php esc_attr_e( 'Update General Options', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_featured_products_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-exerpt-view"></div>
			<span><?php esc_attr_e( 'FEATURED PRODUCTS', 'wp-easycart' ); ?></span>
			<a href="#featured-products" class="ec_admin_expand_section" data-section="ec_admin_product_details_featured_products_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_featured_products_section">
			<?php do_action( 'wp_easycart_admin_product_details_featured_products_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_featured_products( );" value="<?php esc_attr_e( 'Update Featured Products', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_seo_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-chart-area"></div>
			<span><?php esc_attr_e( 'SEO OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#seo" class="ec_admin_expand_section" data-section="ec_admin_product_details_seo_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_seo_section">
			<?php 
			$has_yoast = false;
			$yoast_setup = false;
			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ){
				$has_yoast = true;
				$post_meta = get_post_meta( $this->product->post_id );
				if( $post_meta && isset( $post_meta['_yoast_wpseo_metadesc'] ) ){
					$yoast_setup = true;
				}
				echo '<div style="float:left; width:100%; margin-top:10px;' . ( ( $yoast_setup ) ? '' : ' margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #CCC;' ) . '">' . sprintf( esc_attr__( 'It looks like Yoast is enabled, great! %sClick here to edit the custom post and update your Yoast SEO settings%s for best results. Be careful when editing the page, you will see a shortcode that is required to correctly show the product on your custom post page, without it your product will not work!', 'wp-easycart' ), '<a target="_blank" href="post.php?post=' . esc_attr( $this->product->post_id ) . '&action=edit">', '</a>' ) . '</div>';
			}

			if( !$has_yoast || !$yoast_setup ){
				if( $has_yoast && !$yoast_setup ){
					echo '<div style="float:left; width:100%; margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #CCC;">' . esc_attr__( 'Yoast SEO info has not yet been setup for this product. You may use our basic settings below, but once you setup your Yoast content, the below information will no longer be available.', 'wp-easycart' ) . '</div>';
				}
			do_action( 'wp_easycart_admin_product_details_seo_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_seo( );" value="<?php esc_attr_e( 'Update SEO', 'wp-easycart' ); ?>" />
			</div>
			<?php }?>
		</div>
		</div>
	</div>


	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_menus_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-forms"></div>
			<span><?php esc_attr_e( 'MENU LOCATIONS', 'wp-easycart' ); ?></span>
			<a href="#menus" class="ec_admin_expand_section" data-section="ec_admin_product_details_menus_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_menus_section">
			<?php do_action( 'wp_easycart_admin_product_details_menus_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_menus( );" value="<?php esc_attr_e( 'Update Menus', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>
	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_categories_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-list-view"></div>
			<span><?php esc_attr_e( 'CATEGORY LOCATIONS', 'wp-easycart' ); ?></span>
			<a href="#categories" class="ec_admin_expand_section" data-section="ec_admin_product_details_categories_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_categories_section">
			<?php do_action( 'wp_easycart_admin_product_details_categories_fields' ); ?>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_packaging_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-move"></div>
			<span><?php esc_attr_e( 'PACKAGING OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#packaging" class="ec_admin_expand_section" data-section="ec_admin_product_details_packaging_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_packaging_section">
			<?php if( get_option( 'ec_option_use_shipping' ) ) { ?>
				<?php do_action( 'wp_easycart_admin_product_details_packaging_fields' ); ?>
				<div class="ec_admin_products_submit">
					<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_packaging( );" value="<?php esc_attr_e( 'Update Packaging', 'wp-easycart' ); ?>" />
				</div>
			<?php } else { ?>
				<div class="wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>" style="padding:20px 0 10px;">
					<?php echo sprintf( esc_attr__( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings. %1$sClick here%2$s to manage your shipping settings', 'wp-easycart' ), '<a href="admin.php?page=wp-easycart-settings&subpage=shipping-settings">', '</a>' ); ?>
				</div>
			<?php }?>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_shipping_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-store"></div>
			<span><?php esc_attr_e( 'SHIPPING OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#shipping" class="ec_admin_expand_section" data-section="ec_admin_product_details_shipping_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_shipping_section">
			<?php if( get_option( 'ec_option_use_shipping' ) ) { ?>
				<?php do_action( 'wp_easycart_admin_product_details_shipping_fields' ); ?>
				<div class="ec_admin_products_submit">
					<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_shipping( );" value="<?php esc_attr_e( 'Update Shipping', 'wp-easycart' ); ?>" />
				</div>
			<?php } else { ?>
				<div class="wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>" style="padding:20px 0 10px;">
					<?php echo sprintf( esc_attr__( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings. %1$sClick here%2$s to manage your shipping settings', 'wp-easycart' ), '<a href="admin.php?page=wp-easycart-settings&subpage=shipping-settings">', '</a>' ); ?>
				</div>
			<?php }?>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_tax_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-cart"></div>
			<span><?php esc_attr_e( 'TAX OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#tax" class="ec_admin_expand_section" data-section="ec_admin_product_details_tax_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_tax_section">
			<?php do_action( 'wp_easycart_admin_product_details_tax_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_tax( );" value="<?php esc_attr_e( 'Update Tax', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_short_description_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-menu"></div>
			<span><?php esc_attr_e( 'SHORT DESCRIPTION', 'wp-easycart' ); ?></span>
			<a href="#short-description" class="ec_admin_expand_section" data-section="ec_admin_product_details_short_description_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_short_description_section">
			<?php do_action( 'wp_easycart_admin_product_details_short_description_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_short_description( );" value="<?php esc_attr_e( 'Update Short Description', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_specifications_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-analytics"></div>
			<span><?php esc_attr_e( 'SPECIFICATIONS', 'wp-easycart' ); ?></span>
			<a href="#specifications" class="ec_admin_expand_section" data-section="ec_admin_product_details_specifications_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_specifications_section">
			<?php do_action( 'wp_easycart_admin_product_details_specifications_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_specifications( );" value="<?php esc_attr_e( 'Update Specifications', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_ordercompleted_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-welcome-write-blog"></div>
			<span><?php esc_attr_e( 'ORDER COMPLETED NOTE', 'wp-easycart' ); ?></span>
			<a href="#ordercompleted" class="ec_admin_expand_section" data-section="ec_admin_product_details_ordercompleted_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_ordercompleted_section">
			<?php do_action( 'wp_easycart_admin_product_details_order_completed_note_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_order_completed_note( );" value="<?php esc_attr_e( 'Update Note', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_ordercompleted_email_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-welcome-write-blog"></div>
			<span><?php esc_attr_e( 'ORDER EMAIL NOTE', 'wp-easycart' ); ?></span>
			<a href="#ordercompletedemail" class="ec_admin_expand_section" data-section="ec_admin_product_details_ordercompletedemail_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_ordercompletedemail_section">
			<?php do_action( 'wp_easycart_admin_product_details_order_completed_email_note_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_order_completed_email_note( );" value="<?php esc_attr_e( 'Update Note', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_ordercompleted_details_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-welcome-write-blog"></div>
			<span><?php esc_attr_e( 'ORDER DETAILS NOTE', 'wp-easycart' ); ?></span>
			<a href="#ordercompleteddetails" class="ec_admin_expand_section" data-section="ec_admin_product_details_ordercompleteddetails_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_ordercompleteddetails_section">
			<?php do_action( 'wp_easycart_admin_product_details_order_completed_details_note_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_order_completed_details_note( );" value="<?php esc_attr_e( 'Update Note', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_address_line_item ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_tags_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-tablet"></div>
			<span><?php esc_attr_e( 'IMAGE TAGS', 'wp-easycart' ); ?></span>
			<a href="#tags" class="ec_admin_expand_section" data-section="ec_admin_product_details_tags_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_tags_section">
			<?php do_action( 'wp_easycart_admin_product_details_tags_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_tags( );" value="<?php esc_attr_e( 'Update Tags', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_downloads_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-arrow-down-alt"></div>
			<span><?php esc_attr_e( 'DOWNLOAD OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#downloads" class="ec_admin_expand_section" data-section="ec_admin_product_details_downloads_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_downloads_section">
			<?php do_action( 'wp_easycart_admin_product_details_downloads_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_downloads( );" value="<?php esc_attr_e( 'Update Downloads', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_subscription_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-image-rotate"></div>
			<span><?php esc_attr_e( 'SUBSCRIPTION OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#subscription" class="ec_admin_expand_section" data-section="ec_admin_product_details_subscription_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_subscription_section">
			<?php do_action( 'wp_easycart_admin_product_details_subscription_fields' ); ?>
			<div style="font-size:12px;">*<?php esc_attr_e( 'NOTE: This product type is only compatible with Stripe', 'wp-easycart' ); ?></div>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_subscription( );" value="<?php esc_attr_e( 'Update Subscription', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_deconetwork_loader" ); ?>
		<div class="ec_admin_settings_label ec_admin_expand_section_header">
			<div class="dashicons-before dashicons-admin-appearance"></div>
			<span><?php esc_attr_e( 'DECONETWORK OPTIONS', 'wp-easycart' ); ?></span>
			<a href="#deconetwork" class="ec_admin_expand_section" data-section="ec_admin_product_details_deconetwork_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
			<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
				<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
			</div>
		</div>
		<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_deconetwork_section">
			<?php do_action( 'wp_easycart_admin_product_details_deconetwork_fields' ); ?>
			<div class="ec_admin_products_submit">
				<input type="submit" class="ec_admin_products_simple_button" onclick="return ec_admin_save_product_details_deconetwork( );" value="<?php esc_attr_e( 'Update Deconetwork', 'wp-easycart' ); ?>" />
			</div>
		</div>
		</div>
	</div>

	<div class="ec_admin_flex_row<?php if( !$this->id ){ ?> ec_admin_hidden<?php }?>">
		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first ec_admin_collapsable">
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_google_merchant_loader" ); ?>
			<div class="ec_admin_settings_label ec_admin_expand_section_header">
				<div class="dashicons-before dashicons-rest-api"></div>
				<span><?php esc_attr_e( 'GOOGLE MERCHANT OPTIONS', 'wp-easycart' ); ?></span>
				<a href="#googlemerchant" class="ec_admin_expand_section" data-section="ec_admin_product_details_googlemerchant_section"><div class="dashicons-before dashicons-arrow-down-alt2"></div></a>
				<div class="ec_page_product_title_button_wrap ec_page_title_button_wrap">
					<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
						<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
					</a>
				</div>
			</div>
			<div class="ec_admin_settings_input ec_admin_collapsed_section" id="ec_admin_product_details_googlemerchant_section">
				<?php do_action( 'wp_easycart_admin_product_details_googlemerchant_fields' ); ?>
			</div>
		</div>
	</div>

	<?php do_action( 'wp_easycart_admin_product_details_sections_post' ); ?>

	<div class="ec_admin_details_footer">
		<div class="ec_page_title_button_wrap">
			<a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Back to Products', 'wp-easycart' ); ?></a>
		</div>
	</div>
	</div>
</div>