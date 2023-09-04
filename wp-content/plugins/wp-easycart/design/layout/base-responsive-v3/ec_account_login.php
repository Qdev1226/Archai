<section class="ec_account_page">
    <div class="ec_account_left ec_account_login">

        <?php $this->display_account_login_form_start(); ?>

        <div class="ec_cart_header ec_top">
            <?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_title' )?>
        </div>
        <div class="ec_account_subheader">
            <?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_sub_title' )?>
        </div>

        <div class="ec_cart_input_row">
            <label for="ec_account_login_email"><?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_email_label' )?>*</label>
            <?php $this->display_account_login_email_input(); ?>
        </div>

        <div class="ec_cart_error_row" id="ec_account_login_email_error">
            <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_email_label' ); ?>
        </div>

        <div class="ec_cart_input_row">
            <?php do_action( 'wpeasycart_pre_login_password_display' ); ?>
            <label for="ec_account_login_password"><?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_password_label' )?>*</label>
            <?php $this->display_account_login_password_input(); ?>
        </div>

        <div class="ec_cart_error_row" id="ec_account_login_password_error">
            <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_password_label' ); ?>
        </div>

        <?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
        <input type="hidden" id="ec_grecaptcha_response_login" name="ec_grecaptcha_response_login" value="" />
        <input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
        <div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_login_recaptcha"></div>
        <?php }?>

         <div class="ec_cart_button_row">
            <?php $this->display_account_login_forgot_password_link( wp_easycart_language( )->get_text( 'account_login', 'account_login_forgot_password_link' ) ); ?>
            <input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_button' ); ?>" class="ec_account_button" onclick="return ec_account_login_button_click( );" />
        </div>

        <input type="hidden" name="ec_account_page_id" id="ec_account_page_id" value="<?php echo esc_attr( ( $page_id ) ? (int) $page_id : get_queried_object_id( ) ); ?>" />

        <?php $this->display_account_login_form_end(); ?>

    </div>

    <div class="ec_account_right ec_account_login">

        <div class="ec_cart_header ec_top">
            <?php echo wp_easycart_language( )->get_text( 'account_login', 'account_new_user_title' )?>
        </div>

        <div class="ec_account_subheader">
            <?php echo wp_easycart_language( )->get_text( 'account_login', 'account_new_user_sub_title' )?>
        </div>

        <div class="ec_cart_input_row">
            <?php echo wp_easycart_language( )->get_text( 'account_login', 'account_new_user_message' )?>
        </div>

        <div class="ec_cart_button_row">
            <?php $this->display_account_login_create_account_button( wp_easycart_language( )->get_text( 'account_login', 'account_new_user_button' ) ); ?>
        </div>

    </div>

    <div style="clear:both;"></div>
    <div id="ec_current_media_size"></div>

</section>
<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
<script type="text/javascript">
	if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
		var wpeasycart_login_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_login_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_login_recaptcha_callback
		});
	}
</script>
<?php }?>