<form action="admin.php?page=wp-easycart-settings&subpage=shipping-rates" method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form_rates" novalidate="novalidate">

	<input type="hidden" name="ec_admin_form_action" value="save-shipping-rates" />
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_shipping_rates_nonce', 'wp-easycart-shipping-rates' ); ?>

	<?php do_action( 'wpeasycart_admin_shipping_rates_success' ); ?>	

	<div class="ec_admin_shipping_rates_panel">

		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_shipping_method_loader" ); ?>

		<div class="ec_admin_settings_label">
			<div class="dashicons-before dashicons-location-alt"></div>
			<span><?php esc_attr_e( 'Shipping Method', 'wp-easycart' ); ?></span>
			<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'shipping-rates', 'shipping-method' ) );?>" target="_blank" class="ec_help_icon_link">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
			</a>
			<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'shipping-rates', 'shipping-method');?>
		</div>
		
		<?php if( get_option( 'ec_option_use_shipping' ) ) { ?>
		
		<div class="ec_admin_settings_input">
			<span><?php esc_attr_e( 'Select and Manage Your Shipping Method', 'wp-easycart' ); ?></span>
			<div>
				<select name="ec_option_shipping_method" id="ec_option_shipping_method" onchange="toggle_shipping_method( true );" style="float:left;">
					<?php do_action( 'wpeasycart_admin_shipping_rates_methods' ); ?>
				</select>
			</div>
		</div>

		<div class="ec_admin_settings_shipping_divider"></div>

		<?php do_action( 'wpeasycart_admin_shipping_rates' ); ?>

		<?php } else { ?>
		
		<div class="ec_admin_settings_input ec_admin_settings_products_section wp_easycart_admin_no_padding wpeasycart_shipping_settings_section_disabled_<?php echo ( ! get_option( 'ec_option_use_shipping' ) ) ? 'enabled' : 'disabled'; ?>">
			<?php echo sprintf( esc_attr__( 'Shipping is Disabled. To use this setting you need to re-enable shipping in your shipping settings. %1$sClick here%2$s to manage your shipping settings', 'wp-easycart' ), '<a href="admin.php?page=wp-easycart-settings&subpage=shipping-settings">', '</a>' ); ?>
		</div>
		
		<?php }?>

		<div class="ec_admin_tax_spacer"></div>

	</div>

</form>
<script>
toggle_shipping_method( false );
</script>