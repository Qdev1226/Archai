<div id="ec_account_register">
    <div class="ec_cart_left">

        <?php $this->display_account_register_form_start( ); ?>

        <div class="ec_cart_header ec_top">
            <?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_title' )?>
        </div>

        <div class="ec_cart_input_row">
            <label for="ec_account_register_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>*</label>
            <?php $this->display_account_register_first_name_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_register_first_name_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>
            </div>
        </div>

        <div class="ec_cart_input_row">
            <label for="ec_account_register_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>*</label>
            <?php $this->display_account_register_last_name_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_register_last_name_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>
            </div>
        </div>

        <div class="ec_cart_input_row">
            <label for="ec_account_register_email"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>*</label>
            <?php $this->display_account_register_email_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_register_email_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
            </div>
        </div>

        <div class="ec_cart_input_row">
            <label for="ec_account_register_email_retype"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_retype_email' ); ?>*</label>
            <?php $this->display_account_register_retype_email_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_register_email_retype_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_emails_do_not_match' ); ?>
            </div>
        </div>

        <div class="ec_cart_input_row">
            <?php do_action( 'wpeasycart_pre_password_display' ); ?>
            <label for="ec_account_register_password"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_password' ); ?>*</label>
            <?php $this->display_account_register_password_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_register_password_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_length_error' ); ?>
            </div>
        </div>

        <div class="ec_cart_input_row">
            <label for="ec_account_register_password_retype"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_retype_password' ); ?>*</label>
            <?php $this->display_account_register_retype_password_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_register_password_retype_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_passwords_do_not_match' ); ?>
            </div>
        </div>

        <?php // OPTIONAL BILLING ADDRESS FIELDS

        if( get_option( 'ec_option_require_account_address' ) ){ ?>

        <div class="ec_cart_header">
            <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
        </div>
        <?php if( get_option( 'ec_option_display_country_top' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_country"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
            <?php $this->display_account_billing_information_country_input( "country" ); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_country_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
            </div>
        </div>
        <?php }?>
        <div class="ec_cart_input_row">
            <div class="ec_cart_input_left_half">
                <label for="ec_account_billing_information_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>*</label>
                <?php $this->display_account_billing_information_first_name_input( ); ?>
                <div class="ec_cart_error_row" id="ec_account_billing_information_first_name_error">
                    <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>
                </div>
            </div>
            <div class="ec_cart_input_right_half">
                <label for="ec_account_billing_information_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>*</label>
                <?php $this->display_account_billing_information_last_name_input( ); ?>
                <div class="ec_cart_error_row" id="ec_account_billing_information_last_name_error">
                    <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>
                </div>
            </div>
        </div>
        <?php if( get_option( 'ec_option_enable_company_name' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_company_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ); ?></label>
            <?php $this->display_account_billing_information_company_name_input( ); ?>
        </div>
        <?php }?>
        <?php if( get_option( 'ec_option_collect_vat_registration_number' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_vat_registration_number"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?></label>
            <?php $this->display_vat_registration_number_input( ); ?>
        </div>
        <?php }?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_address"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>*</label>
            <?php $this->display_account_billing_information_address_input( ); ?>
        </div>
        <div class="ec_cart_error_row" id="ec_account_billing_information_address_error">
            <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>
        </div>
        <?php if( get_option( 'ec_option_use_address2' ) ){ ?>
        <div class="ec_cart_input_row">
            <?php $this->display_account_billing_information_address2_input( ); ?>
        </div>
        <?php }?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_city"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>*</label>
            <?php $this->display_account_billing_information_city_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_city_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>
            </div>
        </div>
        <div class="ec_cart_input_row">
            <div class="ec_cart_input_left_half">
                <label for="ec_account_billing_information_state"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?><span id="ec_billing_state_required">*</span></label>
                <?php $this->display_account_billing_information_state_input( ); ?>
                <div class="ec_cart_error_row" id="ec_account_billing_information_state_error">
                    <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?>
                </div>
            </div>
            <div class="ec_cart_input_right_half">
                <label for="ec_account_billing_information_zip"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>*</label>
                <?php $this->display_account_billing_information_zip_input( ); ?>
                <div class="ec_cart_error_row" id="ec_account_billing_information_zip_error">
                    <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>
                </div>
            </div>
        </div>
        <?php if( !get_option( 'ec_option_display_country_top' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_country"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
            <?php $this->display_account_billing_information_country_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_country_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
            </div>
        </div>
        <?php }?>
        <?php if( get_option( 'ec_option_collect_user_phone' ) ){ ?>
        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_phone"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?>*</label>
            <?php $this->display_account_billing_information_phone_input( ); ?>
            <div class="ec_cart_error_row" id="ec_account_billing_information_phone_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?>
            </div>
        </div>

        <?php }?>

        <?php }?>

        <?php if( get_option( 'ec_option_enable_user_notes' ) ){ ?>

        <div class="ec_cart_input_row">
            <label for="ec_account_billing_information_user_notes"><?php echo wp_easycart_language( )->get_text( 'account_register', 'account_billing_information_user_notes' ); ?>*</label>
            <textarea name="ec_account_register_user_notes" id="ec_account_register_user_notes"></textarea>
            <div class="ec_cart_error_row" id="ec_account_register_user_notes_error">
                <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_register', 'account_billing_information_user_notes' ); ?>
            </div>
        </div>

        <?php }?>

        <?php if( get_option( 'ec_option_require_account_terms' ) ){ ?>
        <div class="ec_cart_button_row">
            <input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" class="ec_account_register_input_field" />
            <?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_agree_terms' )?>
        </div>
        <div class="ec_cart_error_row" id="ec_terms_error">
            <?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
        </div>
        <?php }?>

        <?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
        <input type="hidden" id="ec_grecaptcha_response_register" name="ec_grecaptcha_response_register" value="" />
        <input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
        <div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_register_recaptcha"></div>
        <?php }?>

        <div class="ec_cart_button_row">
            <?php if( get_option( 'ec_option_show_subscriber_feature' ) ){ ?>
            <?php $this->display_account_register_is_subscriber_input( ); ?>
            <?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_subscribe' )?>
            <?php }?>
            <input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_button' ); ?>" class="ec_account_button" onclick="<?php $ec_register_js_function = apply_filters( 'wpeasycart_register_js_function', 'return ec_account_register_button_click2( );' ); echo esc_attr( $ec_register_js_function ); ?>" />
        </div>

        <?php $this->display_account_register_form_end( ); ?>

    </div>

    <div class="ec_cart_right">

        <div class="ec_cart_header ec_top">
            <?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_title' )?>
        </div>

        <?php $this->display_account_login_form_start(); ?>

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
        <div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_login_recaptcha"></div>
        <?php }?>

         <div class="ec_cart_button_row">
            <?php $this->display_account_login_forgot_password_link( wp_easycart_language( )->get_text( 'account_login', 'account_login_forgot_password_link' ) ); ?>
            <input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_button' ); ?>" class="ec_account_button" onclick="return ec_account_login_button_click( );" />
        </div>

        <?php $this->display_account_login_form_end(); ?>

    </div>

</div>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>

<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
<script type="text/javascript">
	wpeasycart_account_billing_country_update( );
	wpeasycart_account_shipping_country_update( );
	jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).change( function( ){ wpeasycart_account_billing_country_update( ); } );
	jQuery( document.getElementById( 'ec_account_shipping_information_country' ) ).change( function( ){ wpeasycart_account_shipping_country_update( ); } );
    if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
		var wpeasycart_login_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_login_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_login_recaptcha_callback
		});
	}
    if( jQuery( document.getElementById( 'ec_account_register_recaptcha' ) ).length ){
        var wpeasycart_register_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_register_recaptcha' ), {
            'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
            'callback' : wpeasycart_register_recaptcha_callback
        });
    }
</script>
<?php }?>