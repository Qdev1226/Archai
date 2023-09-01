<?php

$login_args = array(
	'form_id' => 'wptouch-login',
	'label_username' => '',
	'label_password' => '',
	'label_remember' => __( 'Remember Me', 'wptouch-pro' ),
	'value_remember' => true,
	'label_log_in' => __( 'Log In', 'wptouch-pro' ),
);

?>

<div class="wptouch-login-wrap">
	<i class="login-close wptouch-icon-remove-sign"></i>
	<div class='wptouch-login-inner'>

		<h3>
			<i class='wptouch-icon-key'></i>
			<?php esc_html_e( 'Login', 'wptouch-pro' ); ?>
		</h3>

		<?php wp_login_form( $login_args ); ?>

	</div>

	<?php if ( wptouch_fdn_show_login_links() ) { ?>
		<div class="login-links">
			<?php 
				if(isset($_SERVER[ 'REQUEST_URI'])) {
					$link_destination = esc_url_raw( sanitize_text_field(wp_unslash($_SERVER[ 'REQUEST_URI'])), array( 'http', 'https' ) ); 
				}
			?>
			<a class="sign-up tappable" href="<?php echo esc_url(site_url( '/wp-login.php?action=register&redirect_to=' . $link_destination )); ?>"><?php esc_html_e( 'Sign-up', 'wptouch-pro' ); ?></a>
			<a class="forgot tappable" href="<?php echo esc_url(site_url( '/wp-login.php?action=lostpassword&redirect_to=' . $link_destination )); ?>"><?php esc_html_e( 'Lost password?', 'wptouch-pro' ); ?></a>
		</div>
	<?php } ?>
</div>
