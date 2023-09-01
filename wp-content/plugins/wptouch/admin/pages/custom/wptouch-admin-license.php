<?php $settings = wptouch_get_settings( 'bncid' ); ?>

<?php
	if ( !defined( 'WPTOUCH_IS_FREE' ) )  {
		wptouch_check_api( true );
		$bncid_info = wptouch_get_settings( 'bncid' );
	}
?>

<?php function wptouch_has_valid_gravatar( $email ) {
	// Check to see if an e-mail address has a Gravatar associated with it
	// Craft a potential url and test its headers
	$hash = md5( strtolower( trim( $email ) ) );
	$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
	$headers = @get_headers( $uri );
	if ( !preg_match( "|200|", $headers[0] ) ) {
		$has_valid_avatar = false;
	} else {
		$has_valid_avatar = true;
	}
		return $has_valid_avatar;
	}
?>

<script type="text/javascript">
	var bncHasLicense = 0;
</script>

<?php include_once( WPTOUCH_ADMIN_DIR . '/html/license-modals.php' ); ?>

<div class="wptouch-account">
	<h2><?php esc_html_e( 'Account & License', 'wptouch-pro' ); ?></h2>
	<table>
		<tr>
			<td>
				<?php if ( defined( 'WPTOUCH_IS_FREE' ) || !wptouch_has_license() ) { ?>
						<img src="<?php echo esc_url(WPTOUCH_ADMIN_URL); ?>/images/wptouch-logo.png" alt="WPtouch icon" />
				<?php } else { ?>
					<?php if ( wptouch_has_valid_gravatar( $settings->bncid ) ) { ?>
						<?php echo wp_kses_post('<img src="//www.gravatar.com/avatar/' . md5( $settings->bncid ) . '?s=80" alt="gravtar" />'); ?>
					<?php } else { ?>
						<img src="<?php echo esc_url(WPTOUCH_ADMIN_URL); ?>/images/wptouch-logo.png" alt="WPtouch icon" />
					<?php } ?>
				<?php } ?>
			</td>
			<td>
				<h3>
					<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
						WPtouch Free User
					<?php } elseif ( !defined( 'WPTOUCH_IS_FREE' ) && !wptouch_has_license() ) { ?>
						WPtouch Pro User
					<?php } else { ?>
						<?php echo esc_html($settings->bncid); ?>
					<?php } ?>
				</h3>
				<h5>
					<?php if ( defined( 'WPTOUCH_IS_FREE' ) ) { ?>
						WPtouch Free Version
					<?php } elseif ( !defined( 'WPTOUCH_IS_FREE' ) && !wptouch_has_license() ) { ?>
						WPtouch Pro Unlicensed
					<?php } else { ?>
						<?php echo esc_html($bncid_info->license_friendly_name); ?>
					<?php } ?>
				</h5>
			</td>
		</tr>

	<?php if ( defined( 'WPTOUCH_IS_FREE' ) || !wptouch_has_license() ) { ?>
		<tr>
			<td colspan="2">
				<div id="license-settings-area">
					<input type="text" placeholder="<?php esc_html_e( 'Account E-Mail Address', 'wptouch-pro'  ); ?>" id="license_email" name="<?php echo esc_html(wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' )); ?>" value="<?php if ( $settings->bncid ) echo esc_html($settings->bncid); else ''; ?>" />

					<input type="text" placeholder="<?php esc_html_e( 'Product License Key', 'wptouch-pro' ); ?>" id="license_key" name="<?php echo esc_html(wptouch_admin_get_manual_encoded_setting_name( 'bncid', 'bncid' )); ?>" value="<?php if ( $settings->wptouch_license_key ) echo esc_html($settings->wptouch_license_key); else ''; ?>" />

					<div id="activate-license">
						<?php if ( wptouch_show_renewal_notice() ) { ?>
						<a href="http://www.wptouch.com/renew/?utm_campaign=renew-license-page&utm_source=wptouch&utm_medium=web" class="renew button" target="_blank"><?php esc_html_e( 'Renew License', 'wptouch-pro' ); ?></a>
						<?php } else { ?>
						<a href="#" class="activate button"><?php esc_html_e( 'Activate', 'wptouch-pro' ); ?></a>
						<?php } ?>
						<?php if ( $settings->bncid || $settings->wptouch_license_key ) { ?>
							<a href="#" class="clear-license button"><?php esc_html_e( 'Clear License', 'wptouch-pro' ); ?></a>
						<?php } ?>
					</div>
				</div>
			</td>
		</tr>
	<?php } else { ?>
		<tr>
			<td><?php esc_html_e( 'Site License', 'wptouch-pro' ); ?>:</td>
			<td><?php 
				if (isset($_SERVER[ 'REQUEST_URI' ])) {
					$request_uri = sanitize_text_field(wp_unslash($_SERVER[ 'REQUEST_URI' ]));
				}
				else {
					$request_uri = '';
				}
				echo esc_html($bncid_info->licensed_site); ?> | <a href="<?php echo esc_url( add_query_arg( array( 'wptouch_license_action' => 'remove_license', 'wptouch_license_nonce' => wp_create_nonce( 'tsarbomba' ) ), $request_uri ) ) ; ?>" class="remove-license"><?php esc_html_e( 'Deactivate', 'wptouch-pro' ); ?></a></td>
		</tr>
		<tr>
			<td><?php esc_html_e( 'Licenses used', 'wptouch-pro' ); ?>:</td>
			<td><?php echo esc_html($bncid_info->license_used_sites); ?> of <?php echo ( esc_html($bncid_info->license_total_sites) == 9999 ? 'unlimited' : esc_html($bncid_info->license_total_sites) ); ?> sites | <a href="//www.wptouch.com/account" target="_blank"><?php esc_html_e( 'Manage', 'wptouch-pro' ); ?></a></td>
		</tr>
		<?php if ( $bncid_info->license_expiry_date ) { ?>
		<tr>
			<td><?php esc_html_e( 'License expiry', 'wptouch-pro' ); ?>:</td>
			<td><?php echo esc_html(date( 'F jS, Y', $bncid_info->license_expiry_date )); ?></td>
		</tr>
		<?php } ?>
	<?php } ?>
	</table>
</div>
<div class="wptouch-support">
	<h2><?php esc_html_e( 'Support', 'wptouch-pro' ); ?></h2>
	<table>
		<tr>
			<td><a href="//support.wptouch.com"><img src="<?php echo esc_url(WPTOUCH_ADMIN_URL); ?>/images/wptouch_support.png" alt="support screenshot" /></a></td>
			<td>
				<h4><a href="//support.wptouch.com"><?php echo 'support.wptouch.com'; ?></a></h4>
				<p><?php esc_html_e( 'Find support guides, file tickets and access our knowledgebase here.', 'wptouch-pro' ); ?></p>
			</td>
		</tr>
		<tr>
			<td><a href="//www.wptouch.com/account"><img src="<?php echo esc_url(WPTOUCH_ADMIN_URL); ?>/images/wptouch_account.png" alt="<?php esc_html_e( 'Ornamental Image' ); ?>" /></a></td>
			<td>
				<h4><a href="//www.wptouch.com/account"><?php echo esc_html(wptouchize_it( __( 'WPtouch Pro Account', 'wptouch-pro' )) ); ?></a></h4>
				<p><?php echo esc_html(wptouchize_it( __( 'Access your WPtouch Pro account to mange your license, access support resources, or upgrade your license.', 'wptouch-pro' ) )); ?></p>
			</td>
		</tr>
	</table>
</div>
<?php if ( !wptouch_is_controlled_network() || ( wptouch_is_controlled_network() && current_user_can( 'manage_network' ) ) ) { ?>
	<div class="wptouch-installation">
		<h2><?php esc_html_e( 'Installation', 'wptouch-pro' ); ?></h2>
		<input type="hidden" name="wptouch-self-destruct" value="1" />
		<input type="hidden" name="wptouch-self-destruct-nonce" value="<?php echo esc_html(wp_create_nonce( 'tsarbomba' )); ?>" />
		<table>
			<tr>
				<td><button class="button" id="backup-button"><?php esc_html_e( 'Download Settings', 'wptouch-pro' ); ?></button></td>
				<td><p><?php wptouchize_it( esc_html_e( 'Download a copy of WPtouch Pro settings', 'wptouch-pro' ) ); ?></p></td>
			</tr>
			<?php if ( current_user_can( 'manage_network' ) || current_user_can( 'activate_plugins' ) ) { ?>
				<tr>
					<td>
						<button class="button upload" name="restore-settings-button" id="restore-button"><?php esc_html_e( 'Restore Settings', 'wptouch-pro' ); ?></button>
						<div id="restore_uploader" style="display: none;"></div>
					</td>
					<td>
						<p><?php wptouchize_it( esc_html_e( 'Restore a WPtouch Pro settings file', 'wptouch-pro' ) ); ?></p>
					</td>
				</tr>
				<tr class="spacer"><!-- spacer --></tr>
				<tr>
					<td><input id="erase-settings" type="submit" name="wptouch-self-destruct-1" href="#" class="button erase grapefruit" value="<?php esc_html_e( 'Erase Settings', 'wptouch-pro' ); ?>" /></td>
					<td><p><?php wptouchize_it( esc_html_e( 'Erases WPtouch Pro settings without deleting files', 'wptouch-pro' ) ); ?></p></td>
				</tr>
				</tr>
				<tr>
					<td><input id="erase-and-delete" type="submit" name="wptouch-self-destruct-2"  class="button erase grapefruit" value="<?php esc_html_e( 'Delete & Erase', 'wptouch-pro' ); ?>" /></td>
					<td><p><?php echo esc_html(wptouchize_it( sprintf( __( 'Deletes the %s folder in your %s folder on disk, and erases WPtouch Pro settings', 'wptouch-pro' ), 'wptouch-data', 'wp-content' ) )); ?></p></td>
				</tr>
				<tr>
					<td><input id="erase-delete-deactivate" type="submit" name="wptouch-self-destruct-3"  class="button erase grapefruit" value="<?php esc_html_e( 'Delete, Erase & Deactivate', 'wptouch-pro' ); ?>" /></td>
					<td><p><?php echo esc_html(wptouchize_it( sprintf( __( 'Deletes the %s folder, erases WPtouch settings and deactivates WPtouch Pro', 'wptouch-pro' ), 'wptouch-data' ) )); ?></p></td>
				</tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>