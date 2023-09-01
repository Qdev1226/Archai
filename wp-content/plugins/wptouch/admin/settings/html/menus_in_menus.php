<?php
	global $wptouch_pro;
	$current_theme = $wptouch_pro->get_current_theme_info();
?>
<div class="new-in-four">

	<img src="<?php echo WPTOUCH_ADMIN_URL; ?>/images/menu-setup-icon.jpg" alt="menu setup icon with phone and graphic" />
	<?php
		echo '<p>' . sprintf( __( 'These settings are handled in the WordPress %s %sAppearance -> Menus%s Menu Location settings', 'wptouch-pro' ), '<br />', '<em>',  '</em>' ) . '</p>';
		echo sprintf( __( '%sGo to Menu Settings%s', 'wptouch-pro' ), '<a href="' . admin_url( 'nav-menus.php' ) . '" class="button">', '</a>' );
	?>
</div>