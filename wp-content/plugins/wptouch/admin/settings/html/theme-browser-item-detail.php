<?php
	if ( is_network_admin() ) {
		$activate_label = __( 'Install', 'wptouch-pro' );
	} else {
		$activate_label = __( 'Activate', 'wptouch-pro' );
	}
?>
<div id="<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>-container" class="view inner-view <?php wptouch_the_theme_classes(); ?>">
	<div class="back-link">
		<a href="#main-theme-panel" data-popview>
			<i class="icon-left-open-big"></i> <?php _e( 'Back to themes', 'wptouch-pro' ); ?>
		</a>
	</div>

	<div class="item-information inner-section">
		<?php if ( wptouch_get_theme_icon() ) { ?>
			<div class="image-wrapper">
				<img src="<?php wptouch_the_theme_icon(); ?>" alt="<?php wptouch_the_theme_title(); ?>" class="<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>" />
			</div><!-- image-wrapper -->
		<?php } ?>
		<h2>

			<?php wptouch_the_theme_title(); ?>
			<?php if ( wptouch_is_theme_active() && current_user_can( 'manage_options' ) ) { ?><i class="icon-ok-circle"></i><?php } ?>
			<span class="version"><?php wptouch_the_theme_version(); ?></span>
		</h2>
		<p class="desc">
			<?php wptouch_the_theme_description(); ?>
		</p>

		<?php if ( !is_network_admin() || wptouch_is_controlled_network() ) { ?>
			<div class="action-buttons<?php if ( !wptouch_can_cloud_install() && wptouch_is_theme_in_cloud() ) { ?> no-install<?php } ?>">

				<?php if ( !defined( 'WPTOUCH_IS_FREE' ) && wptouch_is_theme_active() && current_user_can( 'manage_options' ) && !is_network_admin() ) { ?>
					<a class="button" href="<?php echo admin_url( 'customize.php' ); ?>"><?php _e( 'Customize', 'wptouch-pro' ); ?></a>
				<?php } ?>

				<?php if ( ( ( wptouch_is_theme_in_cloud() && current_user_can( 'install_plugins' ) ) || ( !wptouch_is_theme_in_cloud() && current_user_can( 'manage_options' ) ) ) && !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
					<?php if ( wptouch_get_theme_buy_url() ) { ?>
						<?php if ( !is_multisite() || is_network_admin() ) { ?>
							<a class="button buynow" href="<?php wptouch_the_theme_buy_url(); ?>">
								<?php _e( 'Add to License', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>
					<?php } elseif ( ( !wptouch_is_theme_active() && !is_network_admin() ) || wptouch_is_theme_in_cloud() ) { ?>
						<a class="button activate" href="<?php echo wptouch_check_url_ssl( wptouch_get_theme_activate_link_url() ); ?>">
							<?php echo $activate_label; ?>
						</a>

						<?php if ( !wptouch_can_cloud_install() && wptouch_is_theme_in_cloud() && wptouch_get_theme_download_url() && !wptouch_get_theme_buy_url() && ( !wptouch_is_controlled_network() || is_network_admin() ) ) { ?>
							<a class="button download theme" href="<?php wptouch_the_theme_download_url(); ?>">
								<?php _e( 'Download', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>

					<?php } ?>
				<?php } else { ?>
					<a class="button" href="<?php echo wptouch_theme_info_url(); ?>"><?php _e( 'Learn More', 'wptouch-pro' ); ?></a>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<?php if ( wptouch_get_theme_screenshots() ) { ?>
		<div class="inner-section screenshots">
			<h3><?php _e( 'Screenshots', 'wptouch-pro' ); ?></h3>
			<div class="screenshot-carousel">
				<div class="carousel-inner">
					<?php wptouch_reset_theme_screenshot(); ?>
					<?php while ( wptouch_has_theme_screenshots() ) { ?>
						<?php wptouch_the_theme_screenshot(); ?>
						<img width="100" src="<?php wptouch_the_theme_screenshot_url(); ?>" alt="preview-image" />
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<?php if ( wptouch_get_theme_long_desc() != '' ) { ?>
		<div class="inner-section long-desc">
			<h3><?php _e( 'Description', 'wptouch-pro' ); ?></h3>
			<?php wptouch_the_theme_long_desc(); ?>
		</div>
	<?php } ?>

	<?php if ( wptouch_get_theme_changelog() != '' ) { ?>
		<div class="inner-section changelog">
			<h3><?php _e( 'Changelog', 'wptouch-pro' ); ?></h3>
			<p class="changelog">
				<?php wptouch_the_theme_changelog(); ?>
			</p>
		</div>
	<?php } ?>
</div>