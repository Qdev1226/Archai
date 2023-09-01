<?php global $wptouch_pro; ?>
<?php if ( !wptouch_can_cloud_install( true ) ) { ?>
	<div class="cloud-update-issue">
		<?php echo sprintf( __( 'Your server configuration is preventing WPtouch Pro from installing and updating from the Cloud. %sPlease visit %sthis article%s to follow the steps to enable Cloud install, or you can manually download and install into the wptouch-data/%s directory.', 'wptouch-pro' ), '<br /><br />', '<a href="https://support.wptouch.com/support/solutions/articles/5000525305-themes-or-extensions-cannot-be-downloaded">', '</a>', 'extensions' ); ?>
	</div>
<?php } elseif ( !wptouch_should_show_license_nag() ) { ?>
	<button class="update-all no-save" data-loading-text="<?php _e( 'Updating', 'wptouch-pro' ); ?>&hellip;"><?php _e( 'Update All', 'wptouch-pro' ); ?></button>
<?php } ?>

<?php if ( function_exists( 'wptouch_is_update_available' ) && wptouch_is_update_available() != WPTOUCH_VERSION ) { ?>
	<div class="updates-plugin">
		<?php if ( $wptouch_pro->theme_upgrades_available() ) { ?><h3><?php _e( 'Core Plugin Updates', 'wptouch-pro' ); ?></h3><?php } ?>
		<ul>
			<li>
				<h4>WPtouch Pro Plugin</h4>
				<span class="update-version">
					<?php echo sprintf( __( 'Upgrade to %s', 'wptouch-pro' ), wptouch_is_update_available() ); ?>
				</span>
			</li>
		</ul>
	</div>
<?php } ?>


<?php if ( $wptouch_pro->theme_upgrades_available() ) { ?>
	<div class="updates-themes">
		<h3><?php _e( 'Theme Updates', 'wptouch-pro' ); ?></h3>
		<ul>
			<?php while ( wptouch_has_themes( true ) ) { ?>
				<?php wptouch_the_theme(); ?>
				<?php if ( wptouch_cloud_theme_update_available() ) { ?>
					<li>
						<?php if ( !wptouch_should_show_license_nag() && !wptouch_can_cloud_install( true ) ) { ?>
							<a class="button download theme no-save" href="<?php wptouch_the_theme_download_url(); ?>">
								<?php _e( 'Download', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>
						<?php if ( wptouch_get_theme_icon() ) { ?>
							<img src="<?php wptouch_the_theme_icon(); ?>" alt="<?php wptouch_the_theme_title(); ?>" />
						<?php } ?>
						<h4><?php wptouch_the_theme_title(); ?></h4>
						<span class="version"><?php wptouch_the_theme_version(); ?></span>
						<span class="update-version">
							<?php echo sprintf( __( 'Upgrade to %s', 'wptouch-pro' ), wptouch_cloud_theme_get_update_version() ); ?>
						</span>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
<?php } ?>

<?php if ( $wptouch_pro->extension_upgrades_available() ) { ?>
	<div class="updates-extensions">
		<h3><?php _e( 'Extension Updates', 'wptouch-pro' ); ?></h3>
		<ul>
			<?php while ( wptouch_has_addons( true ) ) { ?>
				<?php  wptouch_the_addon(); ?>
				<?php if ( wptouch_cloud_addon_update_available() ) { ?>
					<li>
						<?php if ( wptouch_get_addon_icon() ) { ?>
							<img src="<?php wptouch_the_addon_icon(); ?>" alt="<?php wptouch_the_addon_title(); ?>" />
						<?php } ?>
						<h4><?php wptouch_the_addon_title(); ?></h4>
						<span class="version"><?php wptouch_the_addon_version(); ?></span>
						<span class="update-version">
							<?php echo sprintf( __( 'Upgrade to %s', 'wptouch-pro' ), wptouch_cloud_addon_get_update_version() ); ?>
						</span>
						<?php if ( !wptouch_should_show_license_nag() && !wptouch_can_cloud_install( true ) ) { ?>
							<a class="button download extension" href="<?php wptouch_the_addon_download_url(); ?>">
								<?php _e( 'Download', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>
<?php } ?>
