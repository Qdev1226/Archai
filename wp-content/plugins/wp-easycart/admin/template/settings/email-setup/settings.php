<div class="ec_admin_settings_panel">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_email_settings_nonce', 'wp-easycart-email-settings' ); ?>
	<div class="ec_admin_important_numbered_list">

		<?php do_action( 'wpeasycart_admin_email_settings' ); ?>

	</div>

</div>