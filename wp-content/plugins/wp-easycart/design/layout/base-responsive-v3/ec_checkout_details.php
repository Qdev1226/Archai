<?php $this->display_page_one_form_start(); ?>

<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
<input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
<?php }?>

<div class="ec_cart_left">
	<?php if( $GLOBALS['ec_cart_data']->cart_data->user_id == "" ){ ?>
	<div class="ec_cart_header ec_top wpeasycart_returning_customer">
		<input type="checkbox" name="ec_login_selector" id="ec_login_selector" value="login" onchange="ec_cart_toggle_login( );" /> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_title' ); ?>
	</div>
	<div id="ec_user_login_form">

		<div class="ec_cart_input_row">
			<label for="ec_cart_login_email"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_email_label' ); ?>*</label>
			<input type="text" id="ec_cart_login_email" name="ec_cart_login_email" novalidate />
		</div>
		<div class="ec_cart_error_row" id="ec_cart_login_email_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_email_label' ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php do_action( 'wpeasycart_pre_login_password_display' ); ?>
			<label for="ec_cart_login_password"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_password_label' ); ?>*</label>
			<input type="password" id="ec_cart_login_password" name="ec_cart_login_password" />
		</div>
		<div class="ec_cart_error_row" id="ec_cart_login_password_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_password_label' ); ?>
		</div>

		<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
		<input type="hidden" id="ec_grecaptcha_response_login" name="ec_grecaptcha_response_login" value="" />
		<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_login_recaptcha"></div>
		<?php }?>

		<div class="ec_cart_button_row">
			<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_button' ); ?>" class="ec_cart_button" onclick="return ec_validate_cart_login( );" />
		</div>

		<div class="ec_cart_input_row">
			<a href="<?php echo esc_attr( $this->account_page ); ?>?ec_page=forgot_password" class="ec_account_login_link"><?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_forgot_password_link' ); ?></a>
		</div>

		<?php if( get_option( 'ec_option_cache_prevent' ) && get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
		<script type="text/javascript">
			if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
				var wpeasycart_login_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_login_recaptcha' ), {
					'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
					'callback' : wpeasycart_login_recaptcha_callback
				});
			}
		</script>
		<?php }?>

	</div>

	<?php }else{ // close section for NON logged in user ?>
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_account_information_text' ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->last_name, ENT_QUOTES ) ); ?>, <a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider . "ec_cart_action=logout" ); ?>"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_account_information_logout_link' ); ?></a> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_account_information_text2' ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
	</div>

	<?php // ec_address_form()->print_form( $this, 'ec_cart_billing' ); ?>

	<?php if( get_option( 'ec_option_display_country_top' ) ){ ?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_country"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
		<?php $this->display_billing_input( "country" ); ?>
		<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
		</div>
	</div>
	<?php }?>
	<div class="ec_cart_input_row">
		<div class="ec_cart_input_left_half">
			<label for="ec_cart_billing_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>*</label>
			<?php $this->display_billing_input( "first_name" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_first_name_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_right_half">
			<label for="ec_cart_billing_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>*</label>
			<?php $this->display_billing_input( "last_name" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_last_name_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>
			</div>
		</div>
	</div>
	<?php if( get_option( 'ec_option_enable_company_name' ) ){ ?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_company_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ); ?></label>
		<?php $this->display_billing_input( "company_name" ); ?>
	</div>
	<?php }?>
	<?php if( get_option( 'ec_option_collect_vat_registration_number' ) ){ ?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_vat_registration_number"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?></label>
		<?php $this->display_vat_registration_number_input( ); ?>
	</div>
	<?php }?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_address"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>*</label>
		<?php $this->display_billing_input( "address" ); ?>
	</div>
	<div class="ec_cart_error_row" id="ec_cart_billing_address_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>
	</div>
	<?php if( get_option( 'ec_option_use_address2' ) ){ ?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_address2"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address2' ); ?></label>
		<?php $this->display_billing_input( "address2" ); ?>
	</div>
	<?php }?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_city"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>*</label>
		<?php $this->display_billing_input( "city" ); ?>
		<div class="ec_cart_error_row" id="ec_cart_billing_city_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>
		</div>
	</div>
	<div class="ec_cart_input_row">
		<div class="ec_cart_input_left_half">
			<label for="ec_cart_billing_state"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?><span id="ec_billing_state_required">*</span></label>
			<?php $this->display_billing_input( "state" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_state_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_right_half">
			<label for="ec_cart_billing_zip"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>*</label>
			<?php $this->display_billing_input( "zip" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_zip_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>
			</div>
		</div>
	</div>
	<?php if( !get_option( 'ec_option_display_country_top' ) ){ ?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_country"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
		<?php $this->display_billing_input( "country" ); ?>
		<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
		</div>
	</div>
	<?php }?>
	<?php if( get_option( 'ec_option_collect_user_phone' ) ){ ?>
	<div class="ec_cart_input_row">
		<label for="ec_cart_billing_phone"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?><?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>*<?php }?></label>
		<?php $this->display_billing_input( "phone" ); ?>
		<?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>
		<div class="ec_cart_error_row" id="ec_cart_billing_phone_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?>
		</div>
		<?php }?>
	</div>
	<?php }?>

	<?php do_action( 'wpeasycart_billing_after' ); ?>

	<?php if ( ! $this->shipping_address_allowed ) { ?>
		<div class="ec_cart_header">
			<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_ship_to_different' ); ?>
		</div>
		<div class="ec_cart_no_shipping_address">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_shipping_not_alllowed' ); ?>
		</div>
	<?php } ?>

	<?php if( get_option( 'ec_option_use_shipping' ) && $this->shipping_address_allowed && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ){ ?>
	<div class="ec_cart_header">
		<input type="checkbox" name="ec_shipping_selector" id="ec_shipping_selector" value="true" onchange="ec_update_shipping_view( );"<?php if( 
		( 
			$GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && 
			$GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" 
		) || 
		( 
			$GLOBALS['ec_cart_data']->cart_data->shipping_selector == "" && 
			( 
				$GLOBALS['ec_cart_data']->cart_data->billing_first_name != $GLOBALS['ec_cart_data']->cart_data->shipping_first_name || 
				$GLOBALS['ec_cart_data']->cart_data->billing_last_name != $GLOBALS['ec_cart_data']->cart_data->shipping_last_name || 
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 || 
				$GLOBALS['ec_cart_data']->cart_data->billing_city != $GLOBALS['ec_cart_data']->cart_data->shipping_city || 
				$GLOBALS['ec_cart_data']->cart_data->billing_state != $GLOBALS['ec_cart_data']->cart_data->shipping_state
			)
		 ) ){?> checked="checked"<?php }?> /> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_ship_to_different' ); ?>
	</div>
	<div id="ec_shipping_form"<?php if( 
		( 
			$GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && 
			$GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" 
		) || 
		( 
			$GLOBALS['ec_cart_data']->cart_data->shipping_selector == "" && 
			( 
				$GLOBALS['ec_cart_data']->cart_data->billing_first_name != $GLOBALS['ec_cart_data']->cart_data->shipping_first_name || 
				$GLOBALS['ec_cart_data']->cart_data->billing_last_name != $GLOBALS['ec_cart_data']->cart_data->shipping_last_name || 
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 || 
				$GLOBALS['ec_cart_data']->cart_data->billing_city != $GLOBALS['ec_cart_data']->cart_data->shipping_city || 
				$GLOBALS['ec_cart_data']->cart_data->billing_state != $GLOBALS['ec_cart_data']->cart_data->shipping_state 
			)
		 ) ){?> style="display:block;"<?php }?>>
		<?php if( get_option( 'ec_option_display_country_top' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_country"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>*</label>
			<?php $this->display_shipping_input( "country" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_country_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>
			</div>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<div class="ec_cart_input_left_half">
				<label for="ec_cart_shipping_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_first_name' ); ?>*</label>
				<?php $this->display_shipping_input( "first_name" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_first_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_first_name' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_right_half">
				<label for="ec_cart_shipping_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_last_name' ); ?>*</label>
				<?php $this->display_shipping_input( "last_name" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_last_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_last_name' ); ?>
				</div>
			</div>
		</div>
		<?php if( get_option( 'ec_option_enable_company_name' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_company_name"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_company_name' ); ?></label>
			<?php $this->display_shipping_input( "company_name" ); ?>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_address"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address' ); ?>*</label>
			<?php $this->display_shipping_input( "address" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_address_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address' ); ?>
			</div>
		</div>
		<?php if( get_option( 'ec_option_use_address2' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_address2"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address2' ); ?></label>
			<?php $this->display_shipping_input( "address2" ); ?>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_city"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_city' ); ?>*</label>
			<?php $this->display_shipping_input( "city" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_city_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_city' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_row">
			<div class="ec_cart_input_left_half">
				<label for="ec_cart_shipping_state"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_state' ); ?><span id="ec_shipping_state_required">*</span></label>
				<?php $this->display_shipping_input( "state" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_state_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_state' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_right_half">
				<label for="ec_cart_shipping_zip"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_zip' ); ?>*</label>
				<?php $this->display_shipping_input( "zip" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_zip_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_zip' ); ?>
				</div>
			</div>
		</div>
		<?php if( !get_option( 'ec_option_display_country_top' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_country"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>*</label>
			<?php $this->display_shipping_input( "country" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_country_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>
			</div>
		</div>
		<?php }?>
		<?php if( get_option( 'ec_option_collect_user_phone' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_phone"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_phone' ); ?><?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>*<?php }?></label>
			<?php $this->display_shipping_input( "phone" ); ?>
			<?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_phone_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_phone' ); ?>
			</div>
			<?php }?>
		</div>
		<?php }?>
	</div>

	<?php }?>

	<?php do_action( 'wpeasycart_shipping_after' ); ?>

	<?php if( $GLOBALS['ec_cart_data']->cart_data->user_id == "" ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<label for="ec_contact_email"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>*</label>
		<?php $this->ec_cart_display_contact_email_input(); ?>
		<div class="ec_cart_error_row" id="ec_contact_email_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
		</div>
	</div>

	<div class="ec_cart_input_row">
		<label for="ec_contact_email_retype"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_retype_email' ); ?>*</label>
		<?php $this->ec_cart_display_contact_email_retype_input(); ?>
		<div class="ec_cart_error_row" id="ec_contact_email_retype_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_emails_do_not_match' ); ?>
		</div>
	</div>
	<?php }?>

	<?php if( $GLOBALS['ec_cart_data']->cart_data->email == "" || 
			  ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest ) || 
			  ( !$GLOBALS['ec_user']->user_id )
			){ ?>

	<div class="ec_cart_header wpeasycart_create_account">
		<?php if( get_option( 'ec_option_allow_guest' ) && !$this->has_downloads ){ ?><input type="checkbox" name="ec_create_account_selector" id="ec_create_account_selector" value="create_account" onchange="ec_toggle_create_account( );" /> <?php }else{ ?><input type="hidden" name="ec_create_account_selector" id="ec_create_account_selector" value="create_account" /><?php }?><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_create_account' ); ?>
	</div>
	<?php if( get_option( 'ec_option_allow_guest' ) && !$this->has_downloads ){ ?><div id="ec_user_create_form"><?php }?>
		<?php if( get_option( 'ec_option_use_contact_name' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_contact_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>*</label>
			<?php $this->ec_cart_display_contact_first_name_input(); ?>
			<div class="ec_cart_error_row" id="ec_contact_first_name_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_row">
			<label for="ec_contact_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>*</label>
			<?php $this->ec_cart_display_contact_last_name_input(); ?>
			<div class="ec_cart_error_row" id="ec_contact_last_name_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>
			</div>
		</div>
		<?php }?>

		<div class="ec_cart_input_row">
			<?php do_action( 'wpeasycart_pre_password_display' ); ?>
			<label for="ec_contact_password"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_password' ); ?>*</label>
			<?php $this->ec_cart_display_contact_password_input(); ?>
			<div class="ec_cart_error_row" id="ec_contact_password_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_length_error' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_row">
			<label for="ec_contact_password_retype"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_retype_password' ); ?>*</label>
			<?php $this->ec_cart_display_contact_password_retype_input(); ?>
			<div class="ec_cart_error_row" id="ec_contact_password_retype_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_passwords_do_not_match' ); ?>
			</div>
		</div>

		<?php if( get_option( 'ec_option_show_subscriber_feature' ) ){ ?>
		<div class="ec_cart_input_row">
			<input type="checkbox" name="ec_cart_is_subscriber" id="ec_cart_is_subscriber" class="ec_account_register_input_field" value="1" />
			<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_subscribe' )?>
		</div>
		<?php }?>

		<?php if( get_option( 'ec_option_require_account_terms' ) ){ ?>
		<div class="ec_cart_error_row" id="ec_terms_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
		</div>
		<div class="ec_cart_input_row">
			<input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" class="ec_account_register_input_field" />
			<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_agree_terms' )?>
		</div>
		<?php }?>

	<?php if( get_option( 'ec_option_allow_guest' ) && !$this->has_downloads ){ ?></div><?php }?>
	<?php } ?>
	<?php if( get_option( 'ec_option_user_order_notes' ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_message' ); ?>
		<textarea name="ec_order_notes" id="ec_order_notes"><?php if( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" ){ echo esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes ); } ?></textarea>
	</div>
	<?php }?>

	<?php do_action( 'wpeasycart_order_notes_after' ); ?>

	<?php if( get_option( 'ec_option_enable_extra_email' ) ) { ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email_other' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<label for="ec_contact_email_other"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email_other_label' ); ?></label>
		<?php $this->ec_cart_display_contact_email_other_input(); ?>
		<div class="ec_cart_error_row" id="ec_contact_email_other_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
		</div>
	</div>
	<?php }?>

	<div class="ec_cart_error_row ec_show_two_column_only" id="ec_checkout2_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_checkout_details_errors' )?>
	</div>

	<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
	<input type="hidden" id="ec_grecaptcha_response_register" name="ec_grecaptcha_response_register" value="" />
	<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_register_recaptcha"></div>
	<?php }?>

	<div class="ec_cart_button_row ec_show_two_column_only">
		<input type="submit" value="<?php if( get_option( 'ec_option_skip_shipping_page' ) || ( $this->cart->shippable_total_items <= 0 && $this->order_totals->handling_total <= 0 ) ){ ?><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_payment' ); ?><?php }else{ ?><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_shipping' ); ?><?php }?>" class="ec_cart_button ec_checkout_details_submit" onclick="<?php $ec_checkout_js_function = apply_filters( 'wpeasycart_checkout_password_js_function', 'return ec_validate_cart_details( );' ); echo esc_attr( $ec_checkout_js_function ); ?>" />
		<div class="wp-easycart-ld-ring wp-easycart-ld-spin" style="color:#fff"></div>
	</div>

	<?php if( get_option( 'ec_option_cache_prevent' ) && get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
	<script type="text/javascript">
		if( jQuery( document.getElementById( 'ec_account_register_recaptcha' ) ).length ){
			var wpeasycart_register_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_register_recaptcha' ), {
				'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
				'callback' : wpeasycart_register_recaptcha_callback
			});
		}
	</script>
	<?php }?>
		
	<?php do_action( 'wp_easycart_checkout_details_left_end', $this ); ?>

</div>

<div class="ec_cart_right">

	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart', 'your_cart_title' ); ?>
	</div>

	<?php for( $cartitem_index = 0; $cartitem_index<count( $this->cart->cart ); $cartitem_index++ ){ ?>

	<div class="ec_cart_price_row ec_cart_price_row_cartitem_<?php echo esc_attr( $cartitem_index ); ?>">
		<div class="ec_cart_price_row_label"><?php $this->cart->cart[$cartitem_index]->display_title( ); ?><?php if( $this->cart->cart[$cartitem_index]->grid_quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->grid_quantity ); ?><?php }else if( $this->cart->cart[$cartitem_index]->quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->quantity ); ?><?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->stock_quantity <= 0 && $this->cart->cart[$cartitem_index]->allow_backorders ){ ?>
		<div class="ec_cart_backorder_date"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backordered' ); ?><?php if( $this->cart->cart[$cartitem_index]->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $this->cart->cart[$cartitem_index]->backorder_fill_date ); ?><?php }?></div>
		<?php }?>
		<?php if( $this->cart->cart[$cartitem_index]->optionitem1_name ){ ?>
		<dl>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem1_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem1_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem1_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php } ?></dt>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem2_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem2_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem2_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem2_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem3_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem3_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem3_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem3_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem4_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem4_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem4_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem4_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem5_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem5_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem5_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem5_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php } ?></dt>
		<?php }?>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->use_advanced_optionset || $this->cart->cart[$cartitem_index]->use_both_option_types ){ ?>
		<dl>
		<?php foreach( $this->cart->cart[$cartitem_index]->advanced_options as $advanced_option_set ){ ?>
			<?php if( $advanced_option_set->option_type == "grid" ){ ?>
			<dt><?php echo wp_easycart_escape_html( $advanced_option_set->optionitem_name ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if ( $advanced_option_set->optionitem_price > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
					echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
				} ?></dt>
			<?php }else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){ ?>
			<strong><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

			<?php }else{ ?>
			<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if( $advanced_option_set->optionitem_price > 0 ){
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
					echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
				} ?></dt>
			<?php } ?>
		<?php }?>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_giftcard ){ ?>
		<dl>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_to_name ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_email ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_from_name ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( $this->cart->cart[$cartitem_index]->gift_card_message ); ?></dt>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_deconetwork ){ ?>
		<dl>
		<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->deconetwork_options ); ?></dt>
		<dt><?php echo "<a href=\"https://" . esc_attr( get_option( 'ec_option_deconetwork_url' ) ) . esc_attr( $this->cart->cart[$cartitem_index]->deconetwork_edit_link ) . "\">" . wp_easycart_language( )->get_text( 'cart', 'deconetwork_edit' ) . "</a>"; ?></dt>
		</dl>
		<?php }?>

		<?php do_action( 'wp_easycart_cartitem_post_optionitems', $this->cart->cart[$cartitem_index] ); ?>

		</div>
		<div class="ec_cart_price_row_total" id="ec_cart_subtotal"><?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
	</div>

	<?php }?>

	<?php if( get_option( 'ec_option_show_coupons' ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_coupon_title' )?>
	</div>
	<div class="ec_cart_error_message" id="ec_coupon_error"<?php if( $this->is_coupon_expired( ) ){ ?> style="display:block;"<?php }?>><?php echo esc_attr( $this->get_coupon_expiration_note( ) ); ?></div>
	<div class="ec_cart_success_message" id="ec_coupon_success"<?php if( isset( $this->coupon ) && !$this->is_coupon_expired( ) ){?> style="display:block;"<?php }?>><?php if( isset( $this->coupon ) ){ if( $this->discount->coupon_matches <= 0 ){ echo wp_easycart_language( )->get_text( 'cart_coupons', 'coupon_not_applicable' ); }else{ echo wp_easycart_language( )->convert_text( $this->coupon->message ); } } ?></div>
	<div class="ec_cart_input_row">
		<input type="text" name="ec_coupon_code" id="ec_coupon_code" value="<?php if( isset( $this->coupon ) ){ echo esc_attr( $this->coupon_code ); } ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_coupon' )?>" />
	</div>
	<div class="ec_cart_button_row">
		<div class="ec_cart_button" id="ec_apply_coupon" onclick="ec_apply_coupon( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-coupon-code-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_apply_coupon' ); ?></div>
		<div class="ec_cart_button_working" id="ec_applying_coupon"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
	</div>
	<?php }?>
	<?php if( get_option( 'ec_option_show_giftcards' ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_gift_card_title' )?>
	</div>
	<div class="ec_cart_error_message" id="ec_gift_card_error"></div>
	<div class="ec_cart_success_message" id="ec_gift_card_success"<?php if( $this->gift_card != "" ){?> style="display:block;"<?php }?>><?php if( $this->gift_card != "" ){ echo esc_attr( $this->giftcard->message ); } ?></div>
	<div class="ec_cart_input_row">
		<input type="text" name="ec_gift_card" id="ec_gift_card" value="<?php echo esc_attr( $this->gift_card ); ?>" placeholder="<?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_enter_gift_code' ); ?>" />
	</div>
	<div class="ec_cart_button_row">
		<div class="ec_cart_button" id="ec_apply_gift_card" onclick="ec_apply_gift_card( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-gift-card-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart_coupons', 'cart_redeem_gift_card' ); ?></div>
		<div class="ec_cart_button_working" id="ec_applying_gift_card"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
	</div>
	<?php }?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_title' ); ?>
	</div>
	<?php $this->load_cart_total_lines(); ?>

	<div class="ec_cart_error_row" id="ec_checkout_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_checkout_details_errors' )?>
	</div>

	<div class="ec_cart_button_row">
		<input type="submit" value="<?php if( get_option( 'ec_option_skip_shipping_page' ) || ( $this->cart->shippable_total_items <= 0 && $this->order_totals->handling_total <= 0 ) ){ ?><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_payment' ); ?><?php }else{ ?><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_shipping' ); ?><?php }?>" class="ec_cart_button ec_checkout_details_submit" onclick="<?php $ec_checkout_js_function = apply_filters( 'wpeasycart_checkout_password_js_function', 'return ec_validate_cart_details( );' ); echo esc_attr( $ec_checkout_js_function ); ?>" />
		<div class="wp-easycart-ld-ring wp-easycart-ld-spin" style="color:#fff"></div>
	</div>

	<?php do_action( 'wp_easycart_checkout_details_right_end', $this ); ?>

</div>

<?php do_action( 'wpeasycart_checkout_details_after' ); ?>

<?php $this->display_page_one_form_end(); ?>

<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
<script type="text/javascript">
	wpeasycart_cart_billing_country_update( );
	wpeasycart_cart_shipping_country_update( );
	jQuery( document.getElementById( 'ec_cart_billing_country' ) ).change( wpeasycart_cart_billing_country_update );
	jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).change( wpeasycart_cart_shipping_country_update );
</script>
<?php }?>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>