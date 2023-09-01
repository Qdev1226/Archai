<?php if ( !wptouch_can_cloud_install( true ) ) { ?>
	<div class="cloud-update-issue">
		<?php echo sprintf( __( 'Your server configuration is preventing WPtouch Pro from installing and updating from the Cloud. %sPlease visit %sthis article%s to follow the steps to enable Cloud install, or you can manually download and install into the wptouch-data/%s directory.', 'wptouch-pro' ), '<br /><br />', '<a href="//support.wptouch.com/support/solutions/articles/5000525305-themes-or-extensions-cannot-be-downloaded">', '</a>', 'extensions' ); ?>
	</div>
<?php } ?>

<div class="theme-panels">
	<div class="view" id="main-theme-panel">
		<?php
			$counter = 0;
			while ( wptouch_has_themes( true ) ) {
				wptouch_the_theme();
				if ( ( !is_multisite() || wptouch_is_network_available( 'theme' ) || is_network_admin() ) && wptouch_get_theme_title() != 'Foundation' ) {
					if ( $counter == 0 ) { echo '<ul class="cloud-browser themes">'; }
					$counter ++;
					include( 'theme-browser-item.php' );
				}
			}

			if ( $counter > 0 ) {
				echo '</ul>';
			} else {
		?>
			<div class="none-available">
				<center>
					<i class="icon-theme-browser"></i>
					<p><?php _e( 'No themes available', 'wptouch-pro' ); ?></p>
				</center>
			</div>
		<?php } ?>
	</div>

	<?php
		while ( wptouch_has_themes( true ) ) {
			wptouch_the_theme();
			if ( wptouch_get_theme_title() != 'Foundation' && ( !is_multisite() || wptouch_is_network_available( 'theme' ) || is_network_admin() ) ) {
				include( 'theme-browser-item-detail.php' );
			}
		}
	?>
</div>