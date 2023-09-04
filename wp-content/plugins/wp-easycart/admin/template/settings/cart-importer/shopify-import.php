<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<?php wp_easycart_admin( )->preloader->print_preloader( 'ec_admin_shopify_importer' ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-migrate"></div>
		<span><?php esc_attr_e( 'Shopify Importer', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'cart-importer', 'shopify' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'cart-importer', 'shopify');?>
	</div>

	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
		<?php esc_attr_e( 'Shopify has discontinued their private app system, meaning we no longer can offer an easy way to export your data from Shopify to WP EasyCart.', 'wp-easycart' ); ?>
		<?php do_action( 'wp_easycart_admin_shopify_import_end' ); ?>
	</div>
</div>