<?php if( get_option( 'ec_option_display_as_catalog' ) ){ ?>
	<div class="ec_admin_message_error" id="ec_admin_product_store_startup_error"><?php echo sprintf( esc_attr__( 'Your store is in catalog mode and all cart features are disabled. %1$sClick here%2$s to visit your product settings page and disabled catalog mode to add back your shopping cart.', 'wp-easycart' ), '<a href="admin.php?page=wp-easycart-settings&subpage=products">', '</a>' ); ?></div>
<?php }?>

<div class="ec_admin_settings_panel">

	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_checkout_settings_nonce', 'wp-easycart-settings-checkout' ); ?>

	<div class="ec_admin_important_numbered_list">

		<?php do_action( 'wpeasycart_admin_checkout_settings' ); ?>

	</div>

</div>