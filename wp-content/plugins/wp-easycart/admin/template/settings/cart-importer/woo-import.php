<div class="ec_admin_list_line_item ec_admin_demo_data_line">
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_woo_importer" ); ?>
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-migrate"></div>
		<span><?php esc_attr_e( 'WooCommerce Importer', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'cart-importer', 'woo' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'cart-importer', 'woo');?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		<?php if( isset( $_GET['ec_success'] ) && $_GET['ec_success'] == "woo-imported" ){ ?>
			<div  class="ec_save_success">
				<p><?php esc_attr_e( 'Your WooCommerce store has been imported to the EasyCart. There are no guarantees that all options have been imported, becuase Woo offers so many extensions. Please check over the data and manually add anything that may be missing.', 'wp-easycart' ); ?></p>
			</div>
		<?php } else if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "import-woo-products" ){	?>
			<div  class="ec_save_success">
				<p><?php esc_attr_e( 'Importing... Please Wait...', 'wp-easycart' ); ?></p>
			</div>
		<?php } ?>
		<form action="admin.php?page=wp-easycart-settings&subpage=cart-importer" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="ec_admin_form_action" value="import-woo-products" />
			<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-woo-importer-settings' ); ?>
			<div  class="settings_list_items"><p><?php esc_attr_e( 'Importing your data from your WooCommerce store is as simple as a click of a button! Although we do our best to import your data, not everything is transferrable or is known about all extensions available to the Woo system. The following information is imported by our system:', 'wp-easycart' ); ?></p>
			<ul>
				<li><?php esc_attr_e( 'Woo Product Categories', 'wp-easycart' ); ?></li>
				<li><?php esc_attr_e( 'Woo Attributes are imported as option sets to our system', 'wp-easycart' ); ?></li>
				<li><?php esc_attr_e( 'Woo Products are imported by the following rules:', 'wp-easycart' ); ?><ul>
					<li><?php esc_attr_e( 'Title, Description, Short Description, Price (Sale/Regular), Allow Comments, Taxable, Download, Service Item (Virtual), SKU, Download File, Download Limit, Download Expiry, Manage Stock, Stock Status, Stock Quantity', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Connects Imported Attributes (now option sets) to products the same as Woo has connected.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Connects Product Categories to Products.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'If no SKU available, random model number is created.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Product images are copied into our system from WordPress upload system', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Limited to 5 images and first 5 of image gallery used', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'If no image gallery, uses featured image', 'wp-easycart' ); ?></li>
				</ul></li>
			</ul>

			</div>

			<?php if( class_exists( "WooCommerce" ) ){ ?>
				<div class="ec_admin_settings_input">
					<input type="submit" value="<?php esc_attr_e( 'IMPORT WooCommerce DATA NOW', 'wp-easycart' ); ?>" class="ec_admin_settings_simple_button" />
				</div>
			<?php }else{ ?>
				<div>
					<div class="error">
						<p><?php esc_attr_e( 'We cannot detect a version of WooCommerce installed, which may mean that this section does not apply to your site or WooCommerce has been deactivated. In order for us to complete a successful import from WooCommerce, we need a copy of WooCommerce Installed and activated on your site.', 'wp-easycart' ); ?></p>
					</div>
				</div>
			<?php }?>
		</form>
	</div>
</div>