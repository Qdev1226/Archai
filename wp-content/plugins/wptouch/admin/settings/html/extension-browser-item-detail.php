
<div id="<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>-container" class="view inner-view <?php wptouch_the_addon_classes(); ?>">
	<div class="back-link">
		<a href="#main-extension-panel" data-popview>
			<i class="icon-left-open-big"></i> <?php _e( 'Back to extensions', 'wptouch-pro' ); ?>
		</a>
	</div>
	<div class="item-information inner-section">
		<?php if ( wptouch_get_addon_icon() ) { ?>
			<div class="image-wrapper">
				<img src="<?php wptouch_the_addon_icon(); ?>" alt="<?php wptouch_the_addon_title(); ?>" />
			</div>
		<?php } ?>
		<h2>
			<?php wptouch_the_addon_title(); ?>
			<?php if ( wptouch_is_addon_active() && current_user_can( 'manage_options' ) ) { ?><i class="icon-ok-circle"></i><?php } ?>
			<span class="version"><?php wptouch_the_addon_version(); ?></span>
		</h2>

		<p class="desc"><?php wptouch_the_addon_description(); ?></p>

		<?php if ( current_user_can( 'install_plugins' ) && !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<div class="action-buttons<?php if ( !wptouch_can_cloud_install() ) { ?> no-install<?php } ?>">
			<?php if ( is_network_admin() ) { ?>
				<?php if ( wptouch_is_controlled_network() || wptouch_is_multisite_extension() ) { ?>
					<?php if ( !wptouch_is_addon_active() ) { ?>
						<?php if ( wptouch_get_addon_buy_url() ) { ?>
							<a class="button buynow" href="<?php wptouch_the_addon_buy_url(); ?>">
								<?php _e( 'Add to License', 'wptouch-pro' ); ?>
							</a>
						<?php } else { ?>
							<!-- Extension has been purchased -->
							<?php if ( wptouch_can_cloud_install() || wptouch_is_multisite_extension() ) { ?>
								<a class="button activate" href="<?php echo wptouch_check_url_ssl( wptouch_get_addon_activate_link_url() ); ?>">
									<?php echo wptouch_get_activation_label(); ?>
								</a>
							<?php } ?>

							<?php if ( !wptouch_can_cloud_install() ) { ?>
								<a class="button activate" href="<?php echo wptouch_check_url_ssl( wptouch_get_addon_activate_link_url() ); ?>">
							<?php } ?>
						<?php } ?>
					<?php } else { ?>
						<!-- Plugin is active -->
						<?php if ( wptouch_is_multisite_extension() ) { ?>
							<a class="button deactivate" href="<?php wptouch_the_addon_deactivate_link_url(); ?>"><?php _e( 'Deactivate', 'wptouch-pro' ); ?></a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
			<?php } else { ?>
				<?php if ( !wptouch_is_addon_active() ) { ?>
					<?php if ( wptouch_get_addon_buy_url() ) { ?>
						<a class="button buynow" href="<?php wptouch_the_addon_buy_url(); ?>">
							<?php _e( 'Add to License', 'wptouch-pro' ); ?>
						</a>
					<?php } else { ?>
						<!-- Extension has been purchased -->
						<?php if ( wptouch_can_cloud_install() ) { ?>
							<?php if ( wptouch_is_multisite_extension() ) { ?>
								<div><strong><?php _e( 'Must be activated from Network Admin', 'wptouch-pro' ); ?><strong></div>
							<?php } else { ?>
								<a class="button activate" href="<?php echo wptouch_check_url_ssl( wptouch_get_addon_activate_link_url() ); ?>">
									<?php echo wptouch_get_activation_label(); ?>
								</a>
							<?php } ?>
						<?php } ?>

						<?php if ( !wptouch_can_cloud_install() ) { ?>
							<a class="button activate" href="<?php echo wptouch_check_url_ssl( wptouch_get_addon_activate_link_url() ); ?>">
						<?php } ?>
					<?php } ?>
				<?php } else { ?>
					<!-- Plugin is active -->
					<?php if ( !wptouch_is_multisite_extension() ) { ?>
						<a class="button deactivate" href="<?php wptouch_the_addon_deactivate_link_url(); ?>"><?php _e( 'Deactivate', 'wptouch-pro' ); ?></a>
					<?php } ?>
				<?php } ?>
			<?php } ?>
			</div>
		<?php } else { ?>
			<div class="action-buttons<?php if ( !wptouch_can_cloud_install() ) { ?> no-install<?php } ?>">
				<a class="button" href="<?php echo wptouch_addon_info_url(); ?>"><?php _e( 'Learn More', 'wptouch-pro' ); ?></a>
			</div>
		<?php } ?>

		<?php /*
		<?php if ( !is_network_admin() || wptouch_is_controlled_network() ) { ?>
			<div class="action-buttons<?php if ( !wptouch_can_cloud_install() ) { ?> no-install<?php } ?>">
				<?php if ( current_user_can( 'install_plugins' ) && !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
					<?php if ( wptouch_get_addon_buy_url() ) { ?>
						<?php if ( !is_multisite() || is_network_admin() ) { ?>
							<a class="button buynow" href="<?php wptouch_the_addon_buy_url(); ?>">
								<?php _e( 'Add to License', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>
					<?php } elseif ( ( !wptouch_is_addon_active() && ( is_network_admin() && wptouch_is_multisite_extension() ) )  || wptouch_is_addon_in_cloud()  ) { ?>
						<a class="button activate" href="<?php echo wptouch_check_url_ssl( wptouch_get_addon_activate_link_url() ); ?>">
							<?php echo wptouch_get_activation_label(); ?>
						</a>

						<?php if ( !wptouch_can_cloud_install() && wptouch_get_addon_download_url() && !wptouch_get_addon_buy_url() && ( !wptouch_is_controlled_network() || is_network_admin() ) ) { ?>
							<a class="button download extension" href="<?php wptouch_the_addon_download_url(); ?>">
								<?php _e( 'Download', 'wptouch-pro' ); ?>
							</a>
						<?php } ?>

					<?php } elseif ( wptouch_is_multisite_extension() || ( !wptouch_is_multisite_extension() && !is_network_admin() ) ) { ?>
						<a class="button deactivate" href="<?php wptouch_the_addon_deactivate_link_url(); ?>"><?php _e( 'Deactivate', 'wptouch-pro' ); ?></a>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } ?>
		<?php */ ?>

	</div>

	<div class="inner-section long-desc">
		<h3><?php _e( 'Description', 'wptouch-pro' ); ?></h3>
		<?php wptouch_the_addon_long_desc(); ?>
	</div>

	<div class="inner-section changelog">
		<h3><?php _e( 'Changelog', 'wptouch-pro' ); ?></h3>
		<p class="changelog">
			<?php wptouch_the_addon_changelog(); ?>
		</p>
	</div>
</div><!-- inner-view -->