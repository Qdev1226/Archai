<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-clipboard"></div>
		<span><?php esc_attr_e( 'Packing Slip Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-rates', 'packing-slip' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'shipping-rates', 'packing-slip' ); ?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section wp_easycart_admin_no_padding">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_logo', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_logo' ), __( 'Logo on Packing Slip', 'wp-easycart' ), __( 'Enable to show your logo on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_order_id', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_order_id' ), __( 'Order Number on Packing Slip', 'wp-easycart' ), __( 'Enable to show the order number on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_order_date', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_order_date' ), __( 'Order Date on Packing Slip', 'wp-easycart' ), __( 'Enable to show the order date on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_billing', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_billing' ), __( 'Billing Info on Packing Slip', 'wp-easycart' ), __( 'Enable to show the billing information on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_shipping', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_shipping' ), __( 'Shipping Info on Packing Slip', 'wp-easycart' ), __( 'Enable to show the shipping information on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_phone', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_phone' ), __( 'Phone on Packing Slip', 'wp-easycart' ), __( 'Enable to show the phone number on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_phone_row', ( get_option( 'ec_option_packing_slip_show_billing' ) || get_option( 'ec_option_packing_slip_show_shipping' ) ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_email', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_email' ), __( 'Email on Packing Slip', 'wp-easycart' ), __( 'Enable to show the email address on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_product_image', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_product_image' ), __( 'Product Images on Packing Slip', 'wp-easycart' ), __( 'Enable to show the product images on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_product_title', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_product_title' ), __( 'Product Title on Packing Slip', 'wp-easycart' ), __( 'Enable to show the product title on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_model_number', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_model_number' ), __( 'Model Number on Packing Slip', 'wp-easycart' ), __( 'Enable to show the product model number on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_options', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_options' ), __( 'Product Options on Packing Slip', 'wp-easycart' ), __( 'Enable to show the product options on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_pricing', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_pricing' ), __( 'Pricing on Packing Slip', 'wp-easycart' ), __( 'Enable to show pricing on the packing slip.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_subtotal', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_subtotal' ), __( 'Sub Total on Packing Slip', 'wp-easycart' ), __( 'Enable to show the subtotal on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_subtotal_row', get_option( 'ec_option_packing_slip_show_pricing' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_tiptotal', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_tiptotal' ), __( 'Tip Total on Packing Slip', 'wp-easycart' ), __( 'Enable to show the total tips on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_tiptotal_row', get_option( 'ec_option_packing_slip_show_pricing' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_shippingtotal', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_shippingtotal' ), __( 'Shipping Total on Packing Slip', 'wp-easycart' ), __( 'Enable to show the shipping total on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_shippingtotal_row', get_option( 'ec_option_packing_slip_show_pricing' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_discounttotal', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_discounttotal' ), __( 'Discount Total on Packing Slip', 'wp-easycart' ), __( 'Enable to show the total discounts on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_discounttotal_row', get_option( 'ec_option_packing_slip_show_pricing' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_taxtotal', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_taxtotal' ), __( 'Tax/VAT on Packing Slip', 'wp-easycart' ), __( 'Enable to show the tax or vat totals on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_taxtotal_row', get_option( 'ec_option_packing_slip_show_pricing' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_grandtotal', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_grandtotal' ), __( 'Grand Total on Packing Slip', 'wp-easycart' ), __( 'Enable to show the grand total on the packing slip.', 'wp-easycart' ), 'ec_option_packing_slip_show_grandtotal_row', get_option( 'ec_option_packing_slip_show_pricing' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_packing_slip_show_order_notes', 'ec_admin_save_shipping_options', get_option( 'ec_option_packing_slip_show_order_notes' ), __( 'Order Notes on Packing Slip', 'wp-easycart' ), __( 'Enable to show order notes on the packing slip.', 'wp-easycart' ) ); ?>

	</div>
</div>