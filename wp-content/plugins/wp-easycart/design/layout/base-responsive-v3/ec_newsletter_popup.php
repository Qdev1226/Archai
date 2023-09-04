<div class="ec_newsletter_container">
	<div class="ec_newsletter_content">
		<div class="ec_newsletter_content_padding">
        	<div class="ec_newsletter_content_holder">
            	<div class="ec_newsletter_content ec_newsletter_pre_submit">
                <form action="" method="post">
                	<div class="ec_newsletter_close" onclick="ec_close_popup_newsletter( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-close-newsletter' ) ); ?>' );"><a>X</a></div>
                	<h1><?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_title' ); ?></h1>
                    <h3><?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_subtitle' ); ?></h3>
                    <input type="text" name="ec_newsletter_name" id="ec_newsletter_name" placeholder="<?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_name_placeholder' ); ?>" />
                    <input type="email" name="ec_newsletter_email" id="ec_newsletter_email" placeholder="<?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_email_placeholder' ); ?>" />
                    <input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_button_text' ); ?>" onclick="ec_submit_newsletter_signup( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-submit-newsletter' ) ); ?>' ); return false;" />
                    <input type="hidden" name="ec_action" value="ec_newsletter_signup" />
                </form>
                </div>
                
                <div class="ec_newsletter_content ec_newsletter_post_submit">
                <form>
                	<div class="ec_newsletter_close" onclick="ec_close_popup_newsletter( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-close-newsletter' ) ); ?>' );"><a>X</a></div>
                	<h1><?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_success_title' ); ?></h1>
                    <h3><?php echo wp_easycart_language( )->get_text( 'ec_newsletter_popup', 'signup_form_success_subtitle' ); ?></h3>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	jQuery( '.ec_newsletter_container' ).appendTo( document.body );
</script>