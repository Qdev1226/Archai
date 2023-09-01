<?php
$screen = get_current_screen();
if ( ! empty( $screen->base ) && 'appearance_page_flexiverse-info' === $screen->base ) {
	return false;
}

?>
<div class="notice notice-info is-dismissible flexiverse-admin-notice" id="flexiverse-admin-notice">
	<div class="flexiverse-admin-notice-wrapper">
		<h2><?php esc_html_e( 'Howdy, Welcome to', 'flexiverse' ); ?> <?php esc_html_e( 'FlexiVerse!', 'flexiverse' ); ?></h2>
		<p><?php esc_html_e( 'Do you know you can get more features in FlexiVerse? Upgrade to FlexiVerse Pro!!!', 'flexiverse' ); ?></p>
		<a href="<?php echo esc_url( admin_url( 'themes.php?page=flexiverse-info' ) ); ?>" class="flexiverse-admin-notice-primary-button"><?php esc_html_e( 'FlexiVerse Pro Details', 'flexiverse' ); ?></a>
		<a class="button" href="<?php echo esc_url( 'https://fireflythemes.com/themes/flexiverse-pro'); ?>" target="_blank"><?php esc_html_e( 'Learn more about FlexiVerse Pro Theme', 'flexiverse' ); ?></a>
	</div>
</div>
<?php
