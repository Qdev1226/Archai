<form action="admin.php?page=wp-easycart-settings&subpage=shipping-settings" method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form_settings" novalidate="novalidate">
	<input type="hidden" name="ec_admin_form_action" value="save-shipping-setup" />
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_shipping_settings_nonce', 'wp-easycart-shipping-settings' ); ?>

	<?php do_action( 'wpeasycart_admin_products_success' ); ?>

	<div class="ec_admin_settings_panel">


		<div class="ec_admin_important_numbered_list">

			<?php do_action( 'wpeasycart_admin_shipping_setup' ); ?>

		</div>

	</div>
</form>