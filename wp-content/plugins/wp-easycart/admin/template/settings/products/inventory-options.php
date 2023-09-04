<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_inventory_options_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-chart-bar"></div>
		<span><?php esc_attr_e( 'Product Inventory Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'product-settings', 'inventory' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'product-settings', 'inventory');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_stock_removed_in_cart', 'wp_easycart_admin_update_cart_stock_view( ); ec_admin_save_product_options', get_option( 'ec_option_stock_removed_in_cart' ), __( 'Remove Stock: Add to Cart', 'wp-easycart' ), __( 'Enabling this to remove items from stock temporarily while the item is in a customers cart, rather than on purchase only.', 'wp-easycart' ) ); ?>

		<div style="float:left; width:50%; position:relative;">
			<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_tempcart_stock_hours', 'ec_admin_save_product_text_setting', get_option( 'ec_option_tempcart_stock_hours' ), __( 'Cart Stock: Length', 'wp-easycart' ), __( 'This is the number of seconds/minutes/hours to keep items out of stock when in the cart.', 'wp-easycart' ), '1', 'ec_admin_tempcart_stock_hours_row', ( ( get_option( 'ec_option_stock_removed_in_cart' ) == '1' ) ? true : false ), false ); ?>
		</div>

		<div style="float:left; width:50%; position:relative;">
			<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_tempcart_stock_timeframe', 'ec_admin_save_product_text_setting', get_option( 'ec_option_tempcart_stock_timeframe' ), __( 'Cart Stock: Unit', 'wp-easycart' ), __( 'This is the unit to keep items out of stock when in the cart.', 'wp-easycart' ), array(
				(object) array(
					'value'	=> 'SECOND',
					'label' => __( 'Second(s)', 'wp-easycart' )
				),
				(object) array(
					'value'	=> 'MINUTE',
					'label' => __( 'Minute(s)', 'wp-easycart' )
				),
				(object) array(
					'value'	=> 'HOUR',
					'label' => __( 'Hour(s)', 'wp-easycart' )
				)
			), 'ec_admin_tempcart_stock_timeframe_row', ( ( get_option( 'ec_option_stock_removed_in_cart' ) == '1' ) ? true : false ), false ); ?>
		</div>

		<?php do_action( 'wp_easycart_admin_settings_product_inventory_end' ); ?>

	</div>

</div>