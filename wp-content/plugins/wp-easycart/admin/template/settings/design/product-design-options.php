<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-media-document"></div>
		<span><?php esc_attr_e( 'Product Design Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'design', 'product' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'design', 'product' ); ?>
	</div>
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">

		<?php
		$product_type_options = array(
			(object) array(
				'value'	=> '1',
				'label'	=> __( 'Grid Type 1', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '2',
				'label'	=> __( 'Grid Type 2', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '3',
				'label'	=> __( 'Grid Type 3', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '4',
				'label'	=> __( 'Grid Type 4', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '5',
				'label'	=> __( 'Grid Type 5', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '6',
				'label'	=> __( 'List Type 6', 'wp-easycart' )
			)
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_product_type', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_product_type' ), __( 'Product Display Type', 'wp-easycart' ), __( 'Select the display within your store pages for your products.', 'wp-easycart' ), $product_type_options, 'ec_admin_ec_option_default_product_type_row', true, false ); ?>

		<?php
		$product_align = array(
			(object) array(
				'value'	=> '',
				'label'	=> __( 'Default', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'left',
				'label'	=> __( 'Left', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'center',
				'label'	=> __( 'Center', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'right',
				'label'	=> __( 'Right', 'wp-easycart' )
			),
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_product_align', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_product_align' ), __( 'Product Alignment', 'wp-easycart' ), __( 'Select the content alignment for product items.', 'wp-easycart' ), $product_align, 'ec_admin_ec_option_default_product_align_row', true, false ); ?>

		<?php
		$product_visible_items = array(
			(object) array(
				'value'	=> 'title',
				'label'	=> __( 'Title', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'price',
				'label'	=> __( 'Price', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'rating',
				'label'	=> __( 'Star Rating', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'cart',
				'label'	=> __( 'Add to Cart Button', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'quickview',
				'label'	=> __( 'Quickview Button', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'desc',
				'label'	=> __( 'Short Description', 'wp-easycart' )
			),
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_product_visible_options', 'ec_admin_save_design_text_setting', ( ( get_option( 'ec_option_default_product_visible_options' ) ) ? explode( ',', get_option( 'ec_option_default_product_visible_options' ) ) : array() ), __( 'Product Visible Items', 'wp-easycart' ), __( 'Select the parts of the product to display.', 'wp-easycart' ), $product_visible_items, 'ec_admin_ec_option_default_product_visible_options_row', true, true, true ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_product_border', 'wpeasycart_admin_update_product_design_view(); ec_admin_save_design_options', get_option( 'ec_option_default_product_border' ), __( 'Enable Product Border', 'wp-easycart' ), __( 'This adds a border to the products in your store.', 'wp-easycart' ), 'ec_admin_ec_option_default_product_border_row' ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_product_rounded_corners', 'wpeasycart_admin_update_product_design_view(); ec_admin_save_design_options', get_option( 'ec_option_default_product_rounded_corners' ), __( 'Enable Rounded Product Corners', 'wp-easycart' ), __( 'This makes the corners of products rounded in your store.', 'wp-easycart' ), 'ec_admin_ec_option_default_product_rounded_corners_row', ( ( get_option( 'ec_option_default_product_border' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_product_rounded_corners_tl', 'ec_admin_save_design_options', get_option( 'ec_option_default_product_rounded_corners_tl' ), __( 'Rounded Corners: Top Left', 'wp-easycart' ), __( 'This makes the top left corner rounded in your store.', 'wp-easycart' ), 'ec_admin_ec_option_default_product_rounded_corners_tl_row', ( ( get_option( 'ec_option_default_product_border' ) == '1' && get_option( 'ec_option_default_product_rounded_corners' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_product_rounded_corners_tr', 'ec_admin_save_design_options', get_option( 'ec_option_default_product_rounded_corners_tr' ), __( 'Rounded Corners: Top Right', 'wp-easycart' ), __( 'This makes the top right corner rounded in your store.', 'wp-easycart' ), 'ec_admin_ec_option_default_product_rounded_corners_tr_row', ( ( get_option( 'ec_option_default_product_border' ) == '1' && get_option( 'ec_option_default_product_rounded_corners' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_product_rounded_corners_bl', 'ec_admin_save_design_options', get_option( 'ec_option_default_product_rounded_corners_bl' ), __( 'Rounded Corners: Bottom Left', 'wp-easycart' ), __( 'This makes the bottom left corner rounded in your store.', 'wp-easycart' ), 'ec_admin_ec_option_default_product_rounded_corners_bl_row', ( ( get_option( 'ec_option_default_product_border' ) == '1' && get_option( 'ec_option_default_product_rounded_corners' ) == '1' ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_product_rounded_corners_br', 'ec_admin_save_design_options', get_option( 'ec_option_default_product_rounded_corners_br' ), __( 'Rounded Corners: Bottom Right', 'wp-easycart' ), __( 'This makes the bottom right corner rounded in your store.', 'wp-easycart' ), 'ec_admin_ec_option_default_product_rounded_corners_br_row', ( ( get_option( 'ec_option_default_product_border' ) == '1' && get_option( 'ec_option_default_product_rounded_corners' ) == '1' ) ? true : false ) ); ?>

		<?php
		$hover_type_options = array(
			(object) array(
				'value'	=> '1',
				'label'	=> __( 'Image Flip', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '2',
				'label'	=> __( 'Image Crossfade', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '3',
				'label'	=> __( 'Lighten', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '5',
				'label'	=> __( 'Image Grow', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '6',
				'label'	=> __( 'Image Shrink', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '7',
				'label'	=> __( 'Grey-Color', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '8',
				'label'	=> __( 'Brighten', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '9',
				'label'	=> __( 'Image Slide', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '10',
				'label'	=> __( 'Flipbook', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '4',
				'label'	=> __( 'No Effect', 'wp-easycart' )
			)
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_product_image_hover_type', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_product_image_hover_type' ), __( 'Product Hover Type', 'wp-easycart' ), __( 'Select the mouse over effect within your store pages for your products.', 'wp-easycart' ), $hover_type_options, 'ec_admin_ec_option_default_product_image_hover_type_row', true, false ); ?>

		<?php
		$image_style_options = array(
			(object) array(
				'value'	=> 'none',
				'label'	=> __( 'None', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'border',
				'label'	=> __( 'Border', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'shadow',
				'label'	=> __( 'Shadow', 'wp-easycart' )
			)
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_product_image_effect_type', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_product_image_effect_type' ), __( 'Product Image Style', 'wp-easycart' ), __( 'Select the display within your store pages for your products images.', 'wp-easycart' ), $image_style_options, 'ec_admin_ec_option_default_product_image_effect_type_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_quick_view', 'ec_admin_save_design_options', get_option( 'ec_option_default_quick_view' ), __( 'Enable Quick View', 'wp-easycart' ), __( 'This allows a user to see a popup display of the product.', 'wp-easycart' ) ); ?>

		<?php
		$columns_options = array(
			(object) array(
				'value'	=> '1',
				'label'	=> __( '1 Column', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '2',
				'label'	=> __( '2 Columns', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '3',
				'label'	=> __( '3 Columns', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '4',
				'label'	=> __( '4 Columns', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '5',
				'label'	=> __( '5 Columns', 'wp-easycart' )
			)
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_desktop_columns', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_desktop_columns' ), __( 'Desktop Columns', 'wp-easycart' ), __( 'The number of columns will determine the image width.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_default_desktop_columns_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_laptop_columns', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_laptop_columns' ), __( 'Tablet Landscape Columns', 'wp-easycart' ), __( 'The number of columns will determine the image width.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_default_laptop_columns_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_tablet_wide_columns', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_tablet_wide_columns' ), __( 'Tablet Portrait Columns', 'wp-easycart' ), __( 'The number of columns will determine the image width.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_default_tablet_wide_columns_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_tablet_columns', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_tablet_columns' ), __( 'Smartphone Landscape Columns', 'wp-easycart' ), __( 'The number of columns will determine the image width.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_default_tablet_columns_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_smartphone_columns', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_smartphone_columns' ), __( 'Smartphone Portrait Columns', 'wp-easycart' ), __( 'The number of columns will determine the image width.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_default_smartphone_columns_row', true, false ); ?>


		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_default_dynamic_sizing', 'wpeasycart_admin_update_dynamic_sizing( ); ec_admin_save_design_options', get_option( 'ec_option_default_dynamic_sizing' ), __( 'Enable Dynamic Image Height', 'wp-easycart' ), __( 'This matches width of product images, but dynamically shows the image height as auto, disabling allows you to set an exact image height to crop to.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_default_desktop_image_height', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_desktop_image_height' ), __( 'Desktop Image Height', 'wp-easycart' ), __( 'This is the height point to crop your images within the store.', 'wp-easycart' ), '-', 'ec_admin_ec_option_default_desktop_image_height_row', !get_option( 'ec_option_default_dynamic_sizing' ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_default_laptop_image_height', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_laptop_image_height' ), __( 'Tablet Landscape Image Height', 'wp-easycart' ), __( 'This is the height point to crop your images within the store.', 'wp-easycart' ), '-', 'ec_admin_ec_option_default_laptop_image_height_row', !get_option( 'ec_option_default_dynamic_sizing' ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_default_tablet_wide_image_height', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_tablet_wide_image_height' ), __( 'Tablet Portrait Image Height', 'wp-easycart' ), __( 'This is the height point to crop your images within the store.', 'wp-easycart' ), '-', 'ec_admin_ec_option_default_tablet_wide_image_height_row', !get_option( 'ec_option_default_dynamic_sizing' ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_default_tablet_image_height', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_tablet_image_height' ), __( 'Smartphone Landscape Image Height', 'wp-easycart' ), __( 'This is the height point to crop your images within the store.', 'wp-easycart' ), '-', 'ec_admin_ec_option_default_tablet_image_height_row', !get_option( 'ec_option_default_dynamic_sizing' ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_default_smartphone_image_height', 'ec_admin_save_design_text_setting', get_option( 'ec_option_default_smartphone_image_height' ), __( 'Smartphone Portrait Image Height', 'wp-easycart' ), __( 'This is the height point to crop your images within the store.', 'wp-easycart' ), '-', 'ec_admin_ec_option_default_smartphone_image_height_row', !get_option( 'ec_option_default_dynamic_sizing' ), false ); ?>

	</div>

</div>