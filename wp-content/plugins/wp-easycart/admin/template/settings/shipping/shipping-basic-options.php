<div class="ec_admin_list_line_item">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-screenoptions"></div>
		<span><?php esc_attr_e( 'ADDITIONAL SHIPPING OPTIONS', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-settings', 'shipping-basic-options' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-rates', 'shipping-basic-options');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_shipping', 'ec_admin_save_shipping_options', get_option( 'ec_option_use_shipping' ), __( 'Enable Shipping', 'wp-easycart' ), __( 'This option allows you to override sitewide shipping settings.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_hide_shipping_rate_page1', 'ec_admin_save_shipping_options', get_option( 'ec_option_hide_shipping_rate_page1' ), __( 'Hide Cart Shipping Rate', 'wp-easycart' ), __( 'This will hide shipping rates on your initial cart page.  Rates can be viewed after user enters billing/shipping addresses.', 'wp-easycart' ), 'ec_option_hide_shipping_rate_page1_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'shipping_handling_rate', 'ec_admin_save_shipping_text_setting', wp_easycart_admin( )->settings->shipping_handling_rate, __( 'Global Handling Rate', 'wp-easycart' ), __( 'This adds a global amount to every shipping rate in the form of a handling cost.', 'wp-easycart' ), '0.00', 'ec_admin_shipping_handling_rate_row', get_option( 'ec_option_use_shipping' ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'shipping_expedite_rate', 'ec_admin_save_shipping_text_setting', wp_easycart_admin( )->settings->shipping_expedite_rate, __( 'Expedited Shipping Cost', 'wp-easycart' ), __( 'This is the expedited rate shown to your customers and only applies to price, weight, quantity, and percentage shipping systems.', 'wp-easycart' ), '0.00', 'ec_admin_shipping_expedite_rate_row', get_option( 'ec_option_use_shipping' ), false ); ?>

		<?php 
		$dimensions_options = array(
			(object) array(
				'value' => '0',
				'label' => __( 'Standard', 'wp-easycart' )
			),
			(object) array(
				'value' => '1',
				'label' => __( 'Metric', 'wp-easycart' )
			)
		);
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_enable_metric_unit_display', 'ec_admin_save_shipping_text_setting', get_option( 'ec_option_enable_metric_unit_display' ), __( 'Dimension Unit', 'wp-easycart' ), __( 'This is used for dimension option set pricing and live shipping calculations, where applicable.', 'wp-easycart' ), $dimensions_options, 'ec_admin_metric_unit_display_row', get_option( 'ec_option_use_shipping' ), false ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_add_local_pickup', 'ec_admin_save_shipping_options', get_option( 'ec_option_add_local_pickup' ), __( 'Add Free Local Pickup', 'wp-easycart' ), __( 'This adds a free local pickup option to your shipping options.', 'wp-easycart' ), 'ec_option_add_local_pickup_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_collect_tax_on_shipping', 'ec_admin_save_shipping_options', get_option( 'ec_option_collect_tax_on_shipping' ), __( 'Tax Shipping', 'wp-easycart' ), __( 'Enabling will add shipping to your tax calculation (Does not apply to TaxCloud)', 'wp-easycart' ), 'ec_option_collect_tax_on_shipping_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_delivery_days_live_shipping', 'ec_admin_save_shipping_options', get_option( 'ec_option_show_delivery_days_live_shipping' ), __( 'Show Delivery Days', 'wp-easycart' ), __( 'This only applies to some live shipping systems.', 'wp-easycart' ), 'ec_option_show_delivery_days_live_shipping_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_collect_shipping_for_subscriptions', 'ec_admin_save_shipping_options', get_option( 'ec_option_collect_shipping_for_subscriptions' ), __( 'Shipping Allowed on Subscriptions', 'wp-easycart' ), __( 'You may collect a shipping address and charge a shipping rate on a subscription product.', 'wp-easycart' ), 'ec_option_collect_shipping_for_subscriptions_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_ship_items_seperately', 'ec_admin_save_shipping_options', get_option( 'ec_option_ship_items_seperately' ), __( 'Items Ship Separately (Live)', 'wp-easycart' ), __( 'This calculates live rates as products in individual shipments.', 'wp-easycart' ), 'ec_option_ship_items_seperately_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_static_ship_items_seperately', 'ec_admin_save_shipping_options', get_option( 'ec_option_static_ship_items_seperately' ), __( 'Item Ships Separately (Static Method)', 'wp-easycart' ), __( 'This calculates static method based rates as products in individual shipments.', 'wp-easycart' ), 'ec_option_static_ship_items_seperately_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_fedex_use_net_charge', 'ec_admin_save_shipping_options', get_option( 'ec_option_fedex_use_net_charge' ), __( 'FedEx Account Discounts Apply', 'wp-easycart' ), __( 'This allows for your rates to display account discounts, rather than base rates for FedEx live shipping.', 'wp-easycart' ), 'ec_admin_save_shipping_options_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_live_override_always', 'ec_admin_save_shipping_options', get_option( 'ec_option_live_override_always' ), __( 'Live Override Rates Always Show', 'wp-easycart' ), __( 'If you have live shipping rate overrides, they will always show to users.  Disable if you want shipping rate overrides to not display IF the shipping rate is not available.', 'wp-easycart' ), 'ec_option_live_override_always_row', get_option( 'ec_option_use_shipping' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_ship_to_billing_global', 'ec_admin_save_shipping_options', get_option( 'ec_option_ship_to_billing_global' ), __( 'Disable shipping address (ship to billing address only)', 'wp-easycart' ), __( 'By enabling this option, the customer may only enter a billing address and will be given a notice that the store only ships to the billing address.', 'wp-easycart' ), 'ec_option_ship_to_billing_global_row', get_option( 'ec_option_use_shipping' ) ); ?>
	</div>

</div>