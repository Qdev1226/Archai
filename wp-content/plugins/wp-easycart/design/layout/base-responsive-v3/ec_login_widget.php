<?php if( $GLOBALS['ec_user']->user_id == "" || $GLOBALS['ec_user']->user_id == 0 ){ ?>

<form action="<?php echo esc_attr( $account_page ); ?>" method="POST">    

	<div class="ec_cart_input_row">
		<label for="ec_account_login_email"><?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_email_label' )?>*</label>
		<input type="text" name="ec_account_login_email" id="ec_account_login_widget_email" class="ec_account_login_input_field" autocomplete="off" autocapitalize="off" />
	</div>

	<div class="ec_cart_error_row" id="ec_account_login_widget_email_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_email_label' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<label for="ec_account_login_password_widget"><?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_password_label' )?>*</label>
		<input type="password" name="ec_account_login_password" id="ec_account_login_widget_password" class="ec_account_login_input_field" />
	</div>

	<div class="ec_cart_error_row" id="ec_account_login_widget_password_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_password_label' ); ?>
	</div>

	<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
		<input type="hidden" id="ec_grecaptcha_response_login_widget" name="ec_grecaptcha_response_login" value="" />
		<input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
		<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_login_widget_recaptcha"></div>
	<?php }?>

	 <div class="ec_cart_button_row">
		<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_button' ); ?>" class="ec_login_widget_button" onclick="return ec_account_login_widget_button_click()" />
	</div>

	<input type="hidden" name="ec_account_form_action" value="login">
	<input type="hidden" name="ec_account_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-account-login' ) ); ?>" />

</form>

<?php }else{ ?>

<strong><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'hello_text' ); ?>, <?php echo esc_attr( $GLOBALS['ec_user']->first_name ); ?></strong><br />
<a href="<?php echo esc_attr( $account_page ); ?>"><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'dashboard_text' ); ?></a><br />
<a href="<?php echo esc_attr( $account_page ); ?>?ec_page=orders"><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'order_history_text' ); ?></a><br />
<a href="<?php echo esc_attr( $account_page ); ?>?ec_page=billing_information"><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'billing_info_text' ); ?></a><br />
<a href="<?php echo esc_attr( $account_page ); ?>?ec_page=shipping_information"><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'shipping_info_text' ); ?></a><br />
<a href="<?php echo esc_attr( $account_page ); ?>?ec_page=password"><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'change_password_text' ); ?></a><br />
<a href="<?php echo esc_attr( $account_page ); ?>?ec_page=logout"><?php echo wp_easycart_language( )->get_text( 'ec_login_widget', 'sign_out_text' ); ?></a>

<?php } ?>