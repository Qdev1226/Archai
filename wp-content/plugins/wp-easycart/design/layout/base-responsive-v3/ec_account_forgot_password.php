<section class="ec_account_page" id="ec_account_forgot_password">

	<div class="ec_cart_left">

		<?php $this->display_account_forgot_password_form_start( ); ?>

		<div class="ec_cart_header ec_top"><?php echo wp_easycart_language( )->get_text( 'account_forgot_password', 'account_forgot_password_title' )?></div>

		<div class="ec_cart_input_row">
        
        	<label for="ec_account_forgot_password_email"><?php echo wp_easycart_language( )->get_text( 'account_forgot_password', 'account_forgot_password_email_label' )?></label>
            
			<?php $this->display_account_forgot_password_email_input( ); ?>
            
            <div class="ec_cart_error_row" id="ec_account_forgot_password_email_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'account_forgot_password', 'account_forgot_password_email_label' ); ?>
            </div>
            
		</div>
        
        <div class="ec_cart_button_row">
			
            <input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_forgot_password', 'account_forgot_password_button' ); ?>" class="ec_account_button" onclick="return ec_account_forgot_password_button_click( );" />

		</div>

		<?php $this->display_account_forgot_password_form_end( ); ?>

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
        
         <div class="ec_cart_button_row">
         	<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_button' ); ?>" class="ec_account_button" onclick="return ec_account_login_button_click( );" />
        </div>
        
        <?php $this->display_account_login_form_end(); ?>
        
    </div>

    <div style="clear:both;"></div>
    <div id="ec_current_media_size"></div>

</section>