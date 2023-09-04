<div class="ec_admin_settings_panel">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_third_party_settings_nonce', 'wp-easycart-third-party-settings' ); ?>
	<div class="ec_admin_important_numbered_list">
		<?php do_action( 'wpeasycart_admin_third_party' ); ?>
	</div>
</div>