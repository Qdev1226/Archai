<h3><?php esc_attr_e( 'Welcome to WP EasyCart!', 'wp-easycart' ); ?></h3>
<p><?php esc_attr_e( 'Thank you for choosing WP EasyCart for your online store! This setup wizard will help you configure your store\'s basic settings.', 'wp-easycart' ); ?> <strong><?php esc_attr_e( 'This setup is completely optional and should take no longer than five minutes!', 'wp-easycart' ); ?></strong></p>
<p><?php esc_attr_e( 'No time to complete this right now? If you don\'t want to go through the wizard you can skip and return to the WordPress dashboard. Come back when you are ready to continue.', 'wp-easycart' ); ?></p>
<div class="ec_admin_wizard_button_bar">
	<a href="admin.php?page=wp-easycart-settings&ec_admin_form_action=skip-wizard&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-skip-wizard' ) ); ?>" class="ec_admin_wizard_quit_button"><?php esc_attr_e( 'Skip Setup Wizard', 'wp-easycart' ); ?></a>
	<a href="admin.php?page=wp-easycart-products&subpage=products"><?php esc_attr_e( 'Setup Later', 'wp-easycart' ); ?></a>
	<a href="admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=1" class="ec_admin_wizard_next_button"><?php esc_attr_e( 'Get Started!', 'wp-easycart' ); ?></a>
</div>