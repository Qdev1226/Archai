<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_oscommerce_importer" ); ?>
    
    <div class="ec_admin_settings_label">
        <div class="dashicons-before dashicons-migrate"></div>
        <span><?php esc_attr_e( 'osCommerce Importer', 'wp-easycart' ); ?></span>
        <a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'cart-importer', 'oscommerce' ) ); ?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
        </a>
        <?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'cart-importer', 'oscommerce');?>
    </div>
    
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        
		<?php

			if( isset( $_GET['ec_success'] ) && $_GET['ec_success'] == "oscommerce-imported" ){ ?>
				<div class="ec_save_success">
					<p><?php esc_attr_e( 'Your osCommerce store has been imported to the EasyCart. There are no guarantees that all options have been imported, becuase osCommerce offers so many extensions. Please check over the data and manually add anything that may be missing.', 'wp-easycart' ); ?></p>
				</div>
			<?php } else if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "import-oscommerce-products" ){	?>
			<div  class="ec_save_success">
				<p><?php esc_attr_e( 'Importing... Please Wait...', 'wp-easycart' ); ?></p>
			</div>
        <?php } ?>
			
			<form action="admin.php?page=wp-easycart-settings&subpage=cart-importer&ec_action=import-oscommerce-products" method="POST" enctype="multipart/form-data" name="wpeasycart_admin_form" id="wpeasycart_admin_form_import2" novalidate="novalidate">
			<div  class="settings_list_items"><p><?php esc_attr_e( 'Importing your data from your osCommerce store is as simple as a click of a button! Although we do our best to import your data, not everything is transferrable or is known about all extensions available to the osCommerce system. The following information is imported by our system:', 'wp-easycart' ); ?></p>
			<ul>
				<li><?php esc_attr_e( 'Product Categories', 'wp-easycart' ); ?></li>
				<li><?php esc_attr_e( 'Option Sets', 'wp-easycart' ); ?></li>
				<li><?php esc_attr_e( 'Option Item Price Changes', 'wp-easycart' ); ?></li>
				<li><?php esc_attr_e( 'Manufacturers', 'wp-easycart' ); ?></li>
				<li><?php esc_attr_e( 'Products are imported by the following rules:', 'wp-easycart' ); ?><ul>
					<li><?php esc_attr_e( 'Stock quantity, model number, weight, image name, and manufacturer.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Titles and descriptions are added to the products.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Connects products to option sets.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Connects products to categories.', 'wp-easycart' ); ?></li>
				</ul></li>
			</ul>
			
			</div>
			<p>***<strong><?php esc_attr_e( 'Please note!', 'wp-easycart' ); ?></strong> <?php esc_attr_e( 'If you do not have osCommerce installed, clicking import will cause a server error and you will need to press the back button to return to your WordPress admin. Please only use this feature if you are really importing from osCommerce.', 'wp-easycart' ); ?></p>
			
			<div class="ec_admin_settings_input"><input type="submit" value="<?php esc_attr_e( 'IMPORT osCommerce DATA NOW', 'wp-easycart' ); ?>" class="ec_admin_settings_simple_button" /></div>
			
			</form>
		
    </div>
</div>

<?php
	if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "import-oscommerce-products" ){	
		global $wpdb;
		$wpdb->show_errors();
		
		/* IMPORT CATEGORIES */
		$categories = $wpdb->get_results( "SELECT * FROM categories_description" );
		for( $i=0; $i<count( $categories ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_category( category_name ) VALUES( %s )", $categories[$i]->categories_name ) );
			$categories[$i]->category_id = $wpdb->insert_id;
		}
		
		/* IMPORT MANUFACTURERS */
		$manufacturers = array( );
		$os_manufacturers = $wpdb->get_results( "SELECT * FROM manufacturers" );
		for( $i=0; $i<count( $os_manufacturers ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_manufacturer( `name` ) VALUES( %s )", $os_manufacturers[$i]->manufacturers_name ) );
			$manufacturers[$os_manufacturers[$i]->manufacturers_id]->manufacturer_id = $wpdb->insert_id;
		}
		
		/* IMPORT OPTIONS */
		$options = array( );
		$os_options = $wpdb->get_results( "SELECT * FROM products_options" );
		for( $i=0; $i<count( $os_options ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_option( `option_name`, `option_label`, `option_required` ) VALUES( %s, %s, 0 )", $os_options[$i]->products_options_name, $os_options[$i]->products_options_name ) );
			$options[$os_options[$i]->products_options_id]->name = $os_options[$i]->products_options_name;
			$options[$os_options[$i]->products_options_id]->option_id = $wpdb->insert_id;
		}
		
		/* IMPORT OPTION ITEMS */
		$optionitems = array( );
		$os_optionitems = $wpdb->get_results( "SELECT * FROM products_options_values" );
		for( $i=0; $i<count( $os_optionitems ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_optionitem( `optionitem_name` ) VALUES( %s )", $os_optionitems[$i]->products_options_values_name ) );
			$optionitems[$os_optionitems[$i]->products_options_values_id]->optionitem_id = $wpdb->insert_id;
		}
		
		/* IMPORT PRODUCTS */
		$products = array( );
		$os_products = $wpdb->get_results( "SELECT * FROM products" );
		for( $i=0; $i<count( $os_products ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_product( `stock_quantity`, `model_number`, `image1`, `price`, `weight`, `manufacturer_id` ) VALUES( %s, %s, %s, %s, %s, %s )", $os_products[$i]->products_quantity, $os_products[$i]->products_model, $os_products[$i]->products_image, $os_products[$i]->products_price, $os_products[$i]->products_weight, $manufacturers[$os_products[$i]->manufacturers_id]->manufacturer_id ) );
			$products[$os_products[$i]->products_id]->product_id = $wpdb->insert_id;
		}
		
		// Add Descriptions
		$os_product_descriptions = $wpdb->get_results( "SELECT * FROM products_description" );
		for( $i=0; $i<count( $os_product_descriptions ); $i++ ){
            $wpdb->prepare( "UPDATE ec_product SET activate_in_store = 1, title = %s, description = %s WHERE product_id = %d", $os_product_descriptions[$i]->products_name, $os_product_descriptions[$i]->products_description, $products[$os_product_descriptions[$i]->products_id]->product_id );
			$wpdb->query( $wpdb->prepare( "UPDATE ec_product SET activate_in_store = 1, show_on_startup = 1, title = %s, description = %s WHERE product_id = %d", $os_product_descriptions[$i]->products_name, $os_product_descriptions[$i]->products_description, $products[$os_product_descriptions[$i]->products_id]->product_id ) );
		}
		
		// Connect Option items to Options
		$os_option_to_optionitems = $wpdb->get_results( "SELECT * FROM products_options_values_to_products_options" );
		for( $i=0; $i<count( $os_option_to_optionitems ); $i++ ){
			$wpdb->query( $wpdb->prepare( "UPDATE ec_optionitem SET option_id = %d WHERE optionitem_id = %d", $options[$os_option_to_optionitems[$i]->products_options_id]->option_id, $optionitems[$os_option_to_optionitems[$i]->products_options_values_id]->optionitem_id ) );
		}
		
		// Connect Options to Products
		$os_option_to_product = $wpdb->get_results( "SELECT * FROM products_attributes GROUP BY products_id, options_id" );
		for( $i=0; $i<count( $os_option_to_product ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_option_to_product( `option_id`, `product_id` ) VALUES( %s, %s )", $options[$os_option_to_product[$i]->options_id]->option_id, $products[$os_option_to_product[$i]->products_id]->product_id ) );
		}
		
		// Add Option Item Pricing to Option Items
		$os_optionitem_pricing = $wpdb->get_results( "SELECT * FROM products_attributes" );
		for( $i=0; $i<count( $os_optionitem_pricing ); $i++ ){
			if( $os_optionitem_pricing[$i]->price_prefix = "+" )
				$price_change = $os_optionitem_pricing[$i]->options_values_price;
			else
				$price_change = (-1) * $os_optionitem_pricing[$i]->options_values_price;
			
			$wpdb->query( $wpdb->prepare( "UPDATE ec_optionitem SET optionitem_price = %s WHERE optionitem_id = %d", $price_change, $optionitems[$os_optionitem_pricing[$i]->options_values_id]->optionitem_id ) );
		}
		
		// Connect Products to Categories
		$os_product_to_category = $wpdb->get_results( "SELECT * FROM products_to_categories" );
		for( $i=0; $i<count( $os_product_to_category ); $i++ ){
			$wpdb->query( $wpdb->prepare( "INSERT INTO ec_categoryitem( `category_id`, `product_id` ) VALUES( %d, %d )", $categories[$os_product_to_category[$i]->categories_id]->category_id, $products[$os_product_to_category[$i]->products_id]->product_id ) );
		}
		
		header( "location:admin.php?page=wp-easycart-settings&subpage=cart-importer&ec_success=oscommerce-imported" );
	}	
?>