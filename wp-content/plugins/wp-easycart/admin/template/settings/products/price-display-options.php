<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_price_display_options_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-vault"></div>
		<span><?php esc_attr_e( 'Price Display Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'product-settings', 'price-display' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'product-settings', 'price-display');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_hide_price_seasonal', 'ec_admin_save_product_options', get_option( 'ec_option_hide_price_seasonal' ), __( 'Seasonal Products: Hide Price', 'wp-easycart' ), __( 'Enabling this hides the price for any seasonal products.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_hide_price_inquiry', 'ec_admin_save_product_options', get_option( 'ec_option_hide_price_inquiry' ), __( 'Inquiry Products: Hide Price', 'wp-easycart' ), __( 'Enabling this hides the price for any inquiry products.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_multiple_vat_pricing', 'ec_admin_save_product_options', get_option( 'ec_option_show_multiple_vat_pricing' ), __( 'VAT Pricing: Show Included and Excluded Pricing', 'wp-easycart' ), __( 'Enabling this shows pricing with VAT included and excluded. You must have VAT enabled AND selected that VAT is included in price. Failing to do so will show the price with or without VAT as the same price.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_tiered_price_format', 'ec_admin_save_product_options', get_option( 'ec_option_tiered_price_format' ), __( 'Volume Pricing: As low as Formatting', 'wp-easycart' ), __( 'Enabling this shows volume pricing in an "as low as" pricing.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_tiered_price_by_option', 'ec_admin_save_product_options', get_option( 'ec_option_tiered_price_by_option' ), __( 'Volume Pricing: Applies Individually', 'wp-easycart' ), __( 'Enabling this gives volume pricing by the options selected (Small Shirt is different from Large Shirt), rather than just the product.', 'wp-easycart' ) ); ?>

	</div>

</div>