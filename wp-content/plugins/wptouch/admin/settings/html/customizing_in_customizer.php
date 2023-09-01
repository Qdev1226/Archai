<?php
	global $wptouch_pro;
	$current_theme = $wptouch_pro->get_current_theme_info();
?>
<div class="new-in-four">
	<img src="<?php echo WPTOUCH_ADMIN_URL; ?>/images/customizer-setup-icon.jpg" alt="customizer setup icon with phone and graphic" />

	<?php
		echo '<p>' . sprintf( __( 'These settings are handled in the WordPress %s %sAppearance -> Customize%s settings.', 'wptouch-pro' ), '</br>', '<em>', '</em>' ) . '</p>';
		echo sprintf( __( '%sGo to the Customizer%s', 'wptouch-pro' ), '<a href="' . admin_url( 'customize.php' ) . '" class="button">', '</a>' );
	?>
</div>