<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_display_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-analytics"></div>
		<span><?php esc_attr_e( 'Product Details Display', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'product-settings', 'product-details' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'product-settings', 'product-details');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_model_number_extension', 'ec_admin_save_product_text_setting', get_option( 'ec_option_model_number_extension' ), __( 'Model Number Extension', 'wp-easycart' ), __( 'This is the character injected between the main model number and option set extensions.  Default: "-"', 'wp-easycart' ), '-', 'ec_admin_model_number_extension_row', true, false, false ); ?>

		<?php 
		$split_options = array(
			(object) array(
				'value'	=> '',
				'label'	=> __( 'Default', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '5',
				'label'	=> __( '5% / 95%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '10',
				'label'	=> __( '10% / 90%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '15',
				'label'	=> __( '15% / 85%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '20',
				'label'	=> __( '20% / 80%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '25',
				'label'	=> __( '25% / 75%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '30',
				'label'	=> __( '30% / 70%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '35',
				'label'	=> __( '35% / 65%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '40',
				'label'	=> __( '40% / 60%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '45',
				'label'	=> __( '45% / 55%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '50',
				'label'	=> __( '50% / 50%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '55',
				'label'	=> __( '55% / 45%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '60',
				'label'	=> __( '60% / 40%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '65',
				'label'	=> __( '65% / 35%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '70',
				'label'	=> __( '70% / 30%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '75',
				'label'	=> __( '75% / 25%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '80',
				'label'	=> __( '80% / 20%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '85',
				'label'	=> __( '85% / 15%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '90',
				'label'	=> __( '90% / 10%', 'wp-easycart' )
			),
			(object) array(
				'value'	=> '95',
				'label'	=> __( '95% / 5%', 'wp-easycart' )
			),
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_product_details_sizing', 'ec_admin_save_product_text_setting', get_option( 'ec_option_product_details_sizing' ), __( 'Product Details: Image/Details Sizing', 'wp-easycart' ), __( 'This is the split of the image and details on a product.', 'wp-easycart' ), $split_options, 'ec_admin_ec_option_product_details_sizing_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_breadcrumbs', 'ec_admin_save_product_options', get_option( 'ec_option_show_breadcrumbs' ), __( 'Breadcrumbs', 'wp-easycart' ), __( 'Enabling this will show breadcrumbs with links within the product details.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_magnification', 'ec_admin_save_product_options', get_option( 'ec_option_show_magnification' ), __( 'Image Magnification', 'wp-easycart' ), __( 'Enabling this will show an image hover box when hovering the product image.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_large_popup', 'ec_admin_save_product_options', get_option( 'ec_option_show_large_popup' ), __( 'Image Lightbox', 'wp-easycart' ), __( 'Enabling this will show a larger image popup when the image is clicked.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_model_number', 'ec_admin_save_product_options', get_option( 'ec_option_show_model_number' ), __( 'Model Number', 'wp-easycart' ), __( 'Enabling this will show the model number on the product details page.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_short_description_below', 'ec_admin_save_product_options', get_option( 'ec_option_short_description_below' ), __( 'Short Description Below', 'wp-easycart' ), __( 'Enabling this will move the short description below the add to cart button, where applicable.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_categories', 'ec_admin_save_product_options', get_option( 'ec_option_show_categories' ), __( 'Categories', 'wp-easycart' ), __( 'Enabling this will show categories associated with the product with links.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_manufacturer', 'ec_admin_save_product_options', get_option( 'ec_option_show_manufacturer' ), __( 'Manufacturer', 'wp-easycart' ), __( 'Enabling this will show the manufacturer with a link to all products made by the manufacturer.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_stock_quantity', 'ec_admin_save_product_options', get_option( 'ec_option_show_stock_quantity' ), __( 'Stock Quantity (CAUTION ON IS RECOMMENDED)', 'wp-easycart' ), __( 'CAUTION, disabling this feature will disable quantity tracking site-wide and override all other options.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_facebook_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_facebook_icon' ), __( 'Social Icon: Facebook', 'wp-easycart' ), __( 'This adds a Facebook sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_twitter_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_twitter_icon' ), __( 'Social Icon: Twitter', 'wp-easycart' ), __( 'This adds a Twitter sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_delicious_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_delicious_icon' ), __( 'Social Icon: Delicious', 'wp-easycart' ), __( 'This adds a Delicious sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_myspace_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_myspace_icon' ), __( 'Social Icon: MySpace', 'wp-easycart' ), __( 'This adds a MySpace sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_linkedin_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_linkedin_icon' ), __( 'Social Icon: LinkedIn', 'wp-easycart' ), __( 'This adds a LinkedIn sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_email_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_email_icon' ), __( 'Social Icon: Email', 'wp-easycart' ), __( 'This adds a Email sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_digg_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_digg_icon' ), __( 'Social Icon: Digg', 'wp-easycart' ), __( 'This adds a Digg sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_googleplus_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_googleplus_icon' ), __( 'Social Icon: Google+', 'wp-easycart' ), __( 'This adds a Google+ sharing link for each product.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_pinterest_icon', 'ec_admin_save_product_options', get_option( 'ec_option_use_pinterest_icon' ), __( 'Social Icon: Pinterest', 'wp-easycart' ), __( 'This adds a Pinterest sharing link for each product.', 'wp-easycart' ) ); ?>

	</div>

</div>