<!-- Modals -->
<div class="remodal" data-remodal-id="modal-license" data-remodal-options="hashTracking: false">
	<?php if ( isset($_SERVER[ 'REQUEST_URI' ]) && !strstr( sanitize_text_field(wp_unslash($_SERVER[ 'REQUEST_URI' ])), '?page=wptouch-admin-general-settings' ) ) { ?>
		<button data-remodal-action="close" class="remodal-close"></button>
	<?php } ?>
	<div class="license-activation">
		<h1><?php esc_html_e( 'Activating License', 'wptouch-pro' ); ?>&hellip;</h1>
		<div id="progress-license" class="license-status">
			<div class="progress">
			  <div class="bar"></div>
			</div>
		</div>
	</div>

	<!-- Cannot Connect To Server -->
	<div class="license-no-connection status">
		<h1><?php esc_html_e( 'Server Unavailable.', 'wptouch-pro' ); ?></h1>
		<p><?php esc_html_e( 'Our server cannot authorize your license. This could be caused by your webhost blocking the connection, or a temporary issue with our server.', 'wptouch-pro' ); ?></p>
		<button class="email-support"><?php esc_html_e( 'E-Mail Support', 'wptouch-pro' ); ?></button>
	</div>

	<!-- Failed Activation -->
	<div class="license-failed status">
		<h1><?php esc_html_e( 'E-mail Address/License Key Rejected', 'wptouch-pro' ); ?></h1>
		<p><?php esc_html_e( 'Our server rejected your E-Mail Address/License Key.', 'wptouch-pro' ); ?></p>
		<p><?php esc_html_e( 'Please check that they are correct and try again.', 'wptouch-pro' ); ?></p>
		<button data-remodal-action="cancel"><?php esc_html_e( 'Try Again', 'wptouch-pro' ); ?></button>
	</div>

	<!-- Expired License -->
	<div class="license-expired status">
		<h1><?php esc_html_e( 'Your license has expired', 'wptouch-pro' ); ?></h1>
		<p><?php echo wp_kses_post(sprintf( __( '%sRenew your license%s to continue to receive product updates and support.', 'wptouch-pro' ), '<a href="http://www.wptouch.com/renew/?utm_campaign=renew-from-license-area&utm_source=wptouch&utm_medium=web">', '</a>' )); ?></p>
		<button class="renew-license"><?php esc_html_e( 'Renew License', 'wptouch-pro' ); ?></button>
	</div>

	<!-- No Activations Left -->
	<div class="license-no-activations status">
		<h1><?php esc_html_e( 'No licenses remaining', 'wptouch-pro' ); ?></h1>
		<p><?php esc_html_e( 'You have no licenses remaining. You can remove a license from another domain, or purchase an additional site license.', 'wptouch-pro' ); ?></p>
		<button class="manage-licenses"><?php esc_html_e( 'Manage Licenses', 'wptouch-pro' ); ?></button>
		<button class="visit-pricing"><?php esc_html_e( 'Purchase Additional License', 'wptouch-pro' ); ?></button>
	</div>
	<!-- Success! -->
	<div class="license-success status">
		<h1><?php esc_html_e( 'License Activation Complete!', 'wptouch-pro' ); ?></h1>
		<p><?php esc_html_e( 'Thanks for purchasing WPtouch Pro! Your installation is activated, you can receive support and product updates for this website.', 'wptouch-pro' ); ?></p>
		<button data-remodal-action="confirm" class="remodal-confirm"><?php echo 'OK'; ?></button>
	</div>

	<!-- Free Upgrade -->
	
	<div class="license-free-upgrade-text status">
		<p><?php esc_html_e( 'Upgrading installation...', 'wptouch-pro' ); ?></p>
	</div>
	<div class="license-free-upgrade status">
		<h1><?php esc_html_e( 'Upgrade to Pro Complete!', 'wptouch-pro' ); ?></h1>
		<p><?php esc_html_e( 'Your installation has been activated and replaced by WPtouch Pro. Next you\'ll be taken to the WPtouch Pro wizard to get setup.', 'wptouch-pro' ); ?></p>
		<button data-remodal-action="confirm" class="remodal-confirm"><?php echo 'Finish Upgrade'; ?></button>
	</div>

	<!-- Free Upgrade Failed -->
	<div class="license-free-upgrade-failed status">
		<h1><?php esc_html_e( 'Upgrade to Pro Failed', 'wptouch-pro' ); ?></h1>
		<p><?php esc_html_e( 'Your license was activated, however your installation could not be automatically upgraded to WPtouch Pro.', 'wptouch-pro' ); ?></p>
		<p><?php echo wp_kses_post(sprintf( __( 'Please %sfollow the steps in this article%s to fix the issue.', 'wptouch-pro' ), '<a href="https://support.wptouch.com/support/solutions/articles/5000694582">', '</a>' )); ?></p>
		<button data-remodal-action="confirm" class="remodal-confirm"><?php echo 'Ok'; ?></button>
	</div>

</div>