<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_customer_review_display_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-edit"></div>
		<span><?php esc_attr_e( 'Customer Review Display', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'product-settings', 'customer-review' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'product-settings', 'customer-review');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_customer_review_require_login', 'ec_admin_save_product_options', get_option( 'ec_option_customer_review_require_login' ), __( 'Customer Reviews: Require Login', 'wp-easycart' ), __( 'Enabling this requires a user to login before they can submit a review.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_customer_review_show_user_name', 'ec_admin_save_product_options', get_option( 'ec_option_customer_review_show_user_name' ), __( 'Customer Reviews: Show Name', 'wp-easycart' ), __( 'Enabling this will show a name with the customer review.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_customer_review_notification', 'ec_admin_save_product_options', get_option( 'ec_option_customer_review_notification' ), __( 'Customer Reviews: Notify Admin', 'wp-easycart' ), __( 'Enabling this will email the admin of a new review.', 'wp-easycart' ) ); ?>
	</div>

</div>