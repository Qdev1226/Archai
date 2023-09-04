<div class="ec_admin_slideout_container" id="new_optionitem_box">
    <div class="ec_admin_slideout_container_content">
        <?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_new_optionitem_display_loader" ); ?>
        <input type="hidden" id="wp_easycart_optionitem_quick_edit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-optionitem-quick-edit' ) ); ?>" />
        <header class="ec_admin_slideout_container_content_header">
            <div class="ec_admin_slideout_container_content_header_inner">
                <h3><?php esc_attr_e( 'Create an Option Item', 'wp-easycart' ); ?></h3>
                <div class="ec_admin_slideout_close" onclick="wp_easycart_admin_close_slideout( 'new_optionitem_box' );">
                    <div class="dashicons-before dashicons-no-alt"></div>
                </div>
            </div>
        </header>
        <input type="hidden" id="ec_new_optionitem_option_id" value="0" />
        <input type="hidden" id="ec_new_optionitem_option_type" value="" />
        <input type="hidden" id="ec_new_optionitem_sort_order" value="0" />
        <div class="ec_admin_slideout_container_content_inner">
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_optionitem_name"><?php esc_attr_e( 'Option Item Name', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_optionitem_name" name="ec_new_optionitem_name" placeholder="<?php esc_attr_e( 'Small', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_optionitem_model_number_extension"><?php esc_attr_e( 'Model Number Extension (Optional - Extends Model Number in Cart)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_optionitem_model_number_extension" name="ec_new_optionitem_model_number_extension" placeholder="<?php esc_attr_e( 'XL', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_optionitem_price_adjustment"><?php esc_attr_e( 'Price Adjustment (+/-)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_optionitem_price_adjustment" name="ec_new_optionitem_price_adjustment" placeholder="0.00" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_optionitem_weight_adjustment"><?php esc_attr_e( 'Weight Adjustment (+/-)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_optionitem_weight_adjustment" name="ec_new_optionitem_weight_adjustment" placeholder="0.00" />
                </div>
            </div>
        </div>
        <footer class="ec_admin_slideout_container_content_footer">
            <div class="ec_admin_slideout_container_content_footer_inner">
                <div class="ec_admin_slideout_container_content_footer_inner_body">
                    <ul>
                        <li>
                            <button onclick="ec_admin_save_new_optionitem( true );">
                                <span><?php esc_attr_e( 'Create and add another', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                        <li>
                            <button onclick="ec_admin_save_new_optionitem( false );">
                                <span><?php esc_attr_e( 'Create and Close', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>
<script>jQuery( document.getElementById( 'new_optionitem_box' ) ).appendTo( document.body );</script>