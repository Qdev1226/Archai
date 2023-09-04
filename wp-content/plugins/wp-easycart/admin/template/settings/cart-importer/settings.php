<input type="hidden" id="wpec_cart_importer_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-cart-importer' ) ); ?>" />

<div class="ec_admin_settings_panel">
	
	<div class="ec_admin_important_numbered_list">

		<?php do_action( 'wpeasycart_admin_cart_importer' ); ?>

	</div>

</div>