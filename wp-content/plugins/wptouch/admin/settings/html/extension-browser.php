<?php if ( !wptouch_can_cloud_install( true ) ) { ?>
	<div class="cloud-update-issue">
		<?php echo sprintf( __( 'Your server configuration is preventing WPtouch Pro from installing and updating from the Cloud. %sPlease visit %sthis article%s to follow the steps to enable Cloud install, or you can manually download and install into the wptouch-data/%s directory.', 'wptouch-pro' ), '<br /><br />', '<a href="https://support.wptouch.com/support/solutions/articles/5000525305-themes-or-extensions-cannot-be-downloaded">', '</a>', 'extensions' ); ?>
	</div>
<?php } ?>

<!-- available extension list -->
<div class="extension-panels">
	<div class="view" id="main-extension-panel">
		<?php
			$counter = 0;
			while ( wptouch_has_addons( true ) ) {
				wptouch_the_addon();
				if ( !is_multisite() || wptouch_is_network_available( 'extension' ) || is_network_admin() ) {
					if ( $counter == 0 ) { echo '<ul class="cloud-browser extensions">'; }
					$counter ++;
					include( 'extension-browser-item.php' );
				}
			}

			if ( $counter > 0 ) {
				echo '</ul>';
			} else {
		?>
			<div class="none-available">
				<center>
					<i class="icon-extension-browser"></i>
					<p><?php _e( 'No extensions available', 'wptouch-pro' ); ?></p>
				</center>
			</div>
		<?php } ?>
	</div>
	<!-- the inner-views for extensions -->
	<?php while ( wptouch_has_addons( true ) ) { ?>
		<?php wptouch_the_addon(); ?>
		<?php if ( !is_multisite() || wptouch_is_network_available( 'extension' ) || is_network_admin() ) { ?>
		<?php include( 'extension-browser-item-detail.php' ); ?>
		<?php } ?>
	<?php } ?>
</div>
