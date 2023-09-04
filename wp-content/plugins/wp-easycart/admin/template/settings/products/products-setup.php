<?php do_action( 'wpeasycart_admin_products_success' ); ?>

<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_product_settings_nonce', 'wp-easycart-settings-products' ); ?>

<div class="ec_admin_settings_panel">

	<div class="ec_admin_important_numbered_list">

		<?php do_action( 'wpeasycart_admin_products_setup' ); ?>

	</div>

</div>