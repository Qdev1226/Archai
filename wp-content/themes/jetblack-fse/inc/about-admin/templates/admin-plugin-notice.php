<?php
$screen = get_current_screen();
if ( ! empty( $screen->base ) && 'appearance_page_jetblack-fse-info' === $screen->base ) {
	return false;
}

?>
<div class="notice notice-info is-dismissible jetblack-fse-admin-notice">
	<div class="jetblack-fse-admin-notice-wrapper">
		<h2><?php esc_html_e( 'Howdy, Welcome to', 'jetblack-fse' ); ?> <?php esc_html_e( 'JetBlack FSE!', 'jetblack-fse' ); ?></h2>
		<p><?php esc_html_e( 'Do you know you can get more features in JetBlack FSE? Upgrade to Jetblack FSE Pro!!!', 'jetblack-fse' ); ?></p>
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=jetblack-fse-info' ) ); ?>" class="jetblack-fse-admin-notice-primary-button"><?php esc_html_e( 'JetBlack FSE Pro Details', 'jetblack-fse' ); ?></a>
		<a class="button" href="<?php echo esc_url( 'https://fireflythemes.com/themes/jetblack-fse-pro'); ?>" target="_blank"><?php esc_html_e( 'Learn more about JetBlack FSE Pro Theme', 'jetblack-fse' ); ?></a>
	</div>
</div>
<?php
