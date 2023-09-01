<?php if ( function_exists( 'wptouch_pro_check_for_update' ) ) { ?>
<?php $new_version = wptouch_pro_check_for_update(); ?>
<?php $current_version = WPTOUCH_VERSION; ?>

	<?php if ( $new_version != $current_version && !wptouch_has_license() ) { ?>
		<tr class="plugin-update-tr" id="wptouch-plugin-message">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message notice inline notice-warning notice-alt" style="padding: 10px;margin:0">
					<?php if ( !wptouch_has_license() ) { ?>
						<?php if ( wptouch_show_renewal_notice() ) { ?>
							<?php echo wp_kses_post(sprintf( __( 'A new product version (%s) is available. %sRenew your license%s to download this update and receive additional product support.', 'wptouch-pro' ), $new_version, '<a href="//www.wptouch.com/renew/?utm_campaign=renew-plugins-page&utm_source=wptouch&utm_medium=web">', '</a>' )); ?>
						<?php } else if ( wptouch_should_show_license_nag() ) { ?>
							<?php echo wp_kses_post(sprintf( __( 'A new product version (%s) is available. Please %sactivate your license%s, or %spurchase a new license%s to enable updates and full product support.', 'wptouch-pro' ), $new_version, '<a href="' . wptouch_get_license_activation_url() . '">', '</a>', '<a href="//www.wptouch.com/?utm_source=license_nag&utm_medium=web&utm_campaign=wptouch3_upgrades">', '</a>' )); ?>
						<?php } ?>
					<?php } ?>
				</div>
			</td>
		</tr>
		<script type="text/javascript">
			( function(){
				jQuery( '#wptouch-pro' ).addClass( 'update' );
			}());
		</script>
	<?php } elseif ( $new_version == $current_version && !wptouch_has_license() ) { ?>
		<tr class="plugin-update-tr" id="wptouch-plugin-message">
			<td colspan="3" class="plugin-update colspanchange">
				<div class="update-message update">
					<?php if ( !wptouch_has_license() ) { ?>
						<?php if ( wptouch_show_renewal_notice() ) { ?>
							<?php echo wp_kses_post(sprintf( __( '%sRenew your license%s to receive future product updates and product support.', 'wptouch-pro' ), '<a href="//www.wptouch.com/renew/?utm_campaign=renew-plugins-page&utm_source=wptouch&utm_medium=web">', '</a>' )); ?>
						<?php } else if ( wptouch_should_show_license_nag() ) {
								if ( !is_plugin_active_for_network( WPTOUCH_PLUGIN_SLUG ) || is_network_admin() ) {
									$nag_links = array(
										'<a href="' . wptouch_get_license_activation_url() . '">',
										'</a>',
										'<a href="//www.wptouch.com/?utm_source=license_nag&utm_medium=web&utm_campaign=wptouch3_upgrades">',
										'</a>'
									);
								} else {
									$nag_links = array( '', '', '', '' );
								}
							}
						?>
							<?php echo wp_kses_post(sprintf( __( 'Please %sactivate your license%s, or %spurchase a license%s to fully enable product updates and product support.', 'wptouch-pro' ), $nag_links[ 0 ], $nag_links[ 1 ], $nag_links[ 2 ], $nag_links[ 3 ] )); ?>
						<?php } ?>
				</div>
			</td>
		</tr>
		<script type="text/javascript">
			( function(){
				jQuery( '#wptouch-pro' ).addClass( 'update' );
			}());
		</script>
	<?php } ?>

<?php } //wptouch_pro_check_for_update ?>