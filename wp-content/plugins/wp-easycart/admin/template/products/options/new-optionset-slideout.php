<div class="ec_admin_slideout_container" id="new_option_box">
    <div class="ec_admin_slideout_container_content">
        <?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_new_optionset_display_loader" ); ?>
        <input type="hidden" id="wp_easycart_optionset_quick_edit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-optionset-quick-edit' ) ); ?>" />
        <header class="ec_admin_slideout_container_content_header">
            <div class="ec_admin_slideout_container_content_header_inner">
                <h3><?php esc_attr_e( 'Create a Basic Option Set', 'wp-easycart' ); ?></h3>
                <div class="ec_admin_slideout_close" onclick="wp_easycart_admin_close_slideout( 'new_option_box' );">
                    <div class="dashicons-before dashicons-no-alt"></div>
                </div>
            </div>
        </header>
        <div class="ec_admin_slideout_container_content_inner">
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_option_type"><?php esc_attr_e( 'Option Type', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_option_type" name="ec_new_option_type" class="select2-basic">
                        <option value="0"><?php esc_attr_e( 'Select One', 'wp-easycart' ); ?></option>
                        <option value="basic-combo"><?php esc_attr_e( 'Combo / Select Box', 'wp-easycart' ); ?></option>
                        <option value="basic-swatch"><?php esc_attr_e( 'Swatches', 'wp-easycart' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_option_name"><?php esc_attr_e( 'Option Name (Internal Use)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_option_name" name="ec_new_option_name" placeholder="<?php esc_attr_e( 'Product Shirt Sizes', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_option_label"><?php esc_attr_e( 'Option Label', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_option_label" name="ec_new_option_label" placeholder="<?php esc_attr_e( 'Select a Shirt Size', 'wp-easycart' ); ?>" />
                </div>
            </div>
        </div>
        <footer class="ec_admin_slideout_container_content_footer">
            <div class="ec_admin_slideout_container_content_footer_inner">
                <div class="ec_admin_slideout_container_content_footer_inner_body">
                    <ul>
                        <li>
                            <button onclick="ec_admin_save_new_optionset( );">
                                <span><?php esc_attr_e( 'Create Optionset', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                        <li>
                            <button class="ec_footer_slideout_button_alt" onclick="wp_easycart_admin_close_slideout( 'new_option_box' );">
                                <span><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>
<script>jQuery( document.getElementById( 'new_option_box' ) ).appendTo( document.body );</script>