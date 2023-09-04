<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-feedback"></div>
		<span><?php esc_attr_e( 'Cart Settings', 'wp-easycart' ); ?></span>
		<?php wp_easycart_admin( )->preloader->print_saved_icon( "ec_admin_cart_settings_saved" ); ?>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'checkout', 'form-settings' ) ); ?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart' ); ?>">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'checkout', 'form-settings'); ?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">

		<?php do_action( 'wpeasycart_admin_settings_cart_top' ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_load_ssl', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_load_ssl' ), __( 'Force Website HTTPS Secured', 'wp-easycart' ), __( 'This will secure your entire site, CAUTION: SSL Certificate Required!', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_cache_prevent', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_cache_prevent' ), __( 'Prevent Cart Caching Problems', 'wp-easycart' ), __( 'This loads your cart dynamically using javascript and typically solves caching problems.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_addtocart_return_to_product', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_addtocart_return_to_product' ), __( 'Add to Cart: Stay on Page', 'wp-easycart' ), __( 'Enable to keep user on product details page after adding to cart (disable takes user to cart immediately)', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_estimate_shipping', 'wpeasycart_admin_update_estimate_shipping_view( ); ec_admin_save_cart_settings_options', get_option( 'ec_option_use_estimate_shipping' ), __( 'Estimate Shipping', 'wp-easycart' ), __( 'Enabling this adds an estimate shipping box to the first page of the cart.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_estimate_shipping_zip', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_estimate_shipping_zip' ), __( 'Estimate Shipping: Enable Postal Code', 'wp-easycart' ), __( 'This adds the zip/postal code to your estimate shipping calculation.', 'wp-easycart' ), 'ec_admin_estimate_shipping_zip_row', ( ( get_option( 'ec_option_use_estimate_shipping' ) == "1" ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_estimate_shipping_country', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_estimate_shipping_country' ), __( 'Estimate Shipping: Enable Country', 'wp-easycart' ), __( 'This adds a country box to your estimate shipping calculation.', 'wp-easycart' ), 'ec_admin_estimate_shipping_country_row', ( ( get_option( 'ec_option_use_estimate_shipping' ) == "1" ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_giftcards', 'wpeasycart_admin_update_gift_card_view( ); ec_admin_save_cart_settings_options', get_option( 'ec_option_show_giftcards' ), __( 'Gift Cards', 'wp-easycart' ), __( 'Enabling this adds a gift card entry box to the first page of the cart.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_gift_card_shipping_allowed', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_gift_card_shipping_allowed' ), __( 'Gift Cards: Apply to Grand Total', 'wp-easycart' ), __( 'Enabling this allows the gift card to apply to grand total, instead of sub total.', 'wp-easycart' ), 'ec_admin_gift_card_shipping_allowed_row', ( ( get_option( 'ec_option_show_giftcards' ) == "1" ) ? true : false ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_coupons', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_show_coupons' ), __( 'Coupons', 'wp-easycart' ), __( 'Enabling this adds a coupon entry box to the first page of the cart.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_tips', 'wpeasycart_admin_update_tips_view( ); ec_admin_save_cart_settings_options', get_option( 'ec_option_enable_tips' ), __( 'Enable Tips / Gratuity', 'wp-easycart' ), __( 'This allows customers to add a tip or gratuity during checkout, edit text in the language editor.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_default_tips', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_default_tips' ), __( 'Default Tip Values', 'wp-easycart' ), __( 'Enter a comma separated list, e.g. &quot;15,20,25&quot;.', 'wp-easycart' ), '10,15,20,25', 'ec_admin_tips_row', ( ( get_option( 'ec_option_enable_tips' ) == "1" ) ? true : false ), false ); ?>  

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_return_to_store_page_url', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_return_to_store_page_url' ), __( 'Custom Continue Shopping URL', 'wp-easycart' ), __( 'Customize where the \'Continue Shopping\' buttons go with a custom URL (default: Store Page)', 'wp-easycart' ), 'https://www.example.com', 'ec_admin_return_to_store_page_url_row', true, false ); ?>  

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_enable_metric_unit_display', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_enable_metric_unit_display' ), __( 'Unit of Measurement', 'wp-easycart' ), __( 'Used to calculate box sizes and dimension based pricing in option sets.', 'wp-easycart' ), array(
			(object) array( 
				'value'	=> '0',
				'label'	=> __( 'Standard', 'wp-easycart' )
			),
			(object) array( 
				'value'	=> '1',
				'label'	=> __( 'Metric', 'wp-easycart' )
			)
		), 'ec_admin_enable_metric_unit_display_row', true, false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_minimum_order_total', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_minimum_order_total' ), __( 'Minimum Order Total', 'wp-easycart' ), __( 'This is the miniumum allowed subtotal of a purchase.', 'wp-easycart' ), '100.00', 'ec_admin_minimum_order_total_row', true, false ); ?>  

		<?php global $wpdb; $min_order_id = $wpdb->get_var( $wpdb->prepare( "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = 'ec_order'", $wpdb->dbname ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_current_order_id', 'ec_admin_save_checkout_text_setting', $min_order_id, __( 'Next Order Number', 'wp-easycart' ), __( 'Set the value for your next order number (you can only increase this value, lower values are not permitted)', 'wp-easycart' ), 'https://www.example.com', 'ec_admin_return_to_store_page_url_row', true, false ); ?>  

	</div>
</div>