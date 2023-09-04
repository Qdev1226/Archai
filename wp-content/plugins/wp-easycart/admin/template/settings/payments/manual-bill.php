<div class="ec_admin_manual_payment_row">
    <div class="ec_admin_slider_row">
        <?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_direct_deposit_display_loader" ); ?>
        <h3><?php esc_attr_e( 'Bill Later', 'wp-easycart' ); ?></h3>
        <div class="ec_admin_slider_row_description">
            <div><?php esc_attr_e( 'This method can be considered a pay later option. Customers cannot actually pay you during the checkout process with this method, but can create an order that you can collect payment later.', 'wp-easycart' ); ?></div>
            <a href="#" onclick="return direct_deposit_show_advanced( );" id="direct_deposit_advanced_link"><?php esc_attr_e( 'Advanced Options', 'wp-easycart' ); ?> &#9660;</a>
        </div>
        <div class="ec_admin_toggles_wrap">
            <div class="ec_admin_toggle">
                <span><?php esc_attr_e( 'Enable', 'wp-easycart' ); ?>:</span>
                <label class="ec_admin_switch">
                    <input type="checkbox" onclick="return toggle_direct_deposit( );" class="ec_admin_slider_checkbox" value="<?php echo esc_attr( get_option( 'ec_option_use_direct_deposit' ) ); ?>" id="ec_option_use_direct_deposit"<?php if( get_option( 'ec_option_use_direct_deposit' ) ){ ?> checked="checked"<?php }?>>
                    <span class="ec_admin_slider round"></span>
                </label>
            </div>
        </div>
        <div id="ec_direct_deposit_options" class="ec_admin_initial_hide">
            <div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show"><?php esc_attr_e( 'Payment Title', 'wp-easycart' ); ?>
                <input type="text" class="ec_admin_text_full_field" name="ec_language_field[cart_payment_information_manual_payment]" id="ec_option_manual_payment_title" value="<?php echo esc_attr( wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_manual_payment' ) ); ?>" />
            </div>
            <input type="hidden" name="file_name" id="manual_bill_file_name" value="<?php echo esc_attr( get_option( 'ec_option_language' ) ); ?>" />
            <input type="hidden" name="key_section" id="manual_bill_key_section" value="cart_payment_information" />
            <input type="hidden" name="isupdate" value="1" />
            <div class="ec_admin_settings_input ec_admin_settings_advanced_payment_section ec_admin_settings_show"><?php esc_attr_e( 'Payment Message', 'wp-easycart' ); ?>
                <textarea class="ec_admin_settings_payment_full_textarea" name="ec_option_direct_deposit_message" id="ec_option_direct_deposit_message"><?php echo esc_attr( get_option( 'ec_option_direct_deposit_message' ) ); ?></textarea>
            </div>

            <div class="ec_admin_settings_input">
                <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_direct_deposit_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart' ); ?>" />
            </div>
        </div>
    </div>
</div>