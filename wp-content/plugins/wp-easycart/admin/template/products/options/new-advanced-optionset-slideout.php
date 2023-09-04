<div class="ec_admin_slideout_container" id="new_adv_option_box">
    <div class="ec_admin_slideout_container_content">
        <?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_new_adv_optionset_display_loader" ); ?>
        <input type="hidden" id="wp_easycart_adv_optionset_quick_edit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-adv-optionset-quick-edit' ) ); ?>" />
        <header class="ec_admin_slideout_container_content_header">
            <div class="ec_admin_slideout_container_content_header_inner">
                <h3><?php esc_attr_e( 'Create an Advanced Product Modifier', 'wp-easycart' ); ?></h3>
                <div class="ec_admin_slideout_close" onclick="wp_easycart_admin_close_slideout( 'new_adv_option_box' );">
                    <div class="dashicons-before dashicons-no-alt"></div>
                </div>
            </div>
        </header>
        <div class="ec_admin_slideout_container_content_inner">
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_adv_option_type"><?php esc_attr_e( 'Modifier Type', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_adv_option_type" name="ec_new_adv_option_type" class="select2-basic" onchange="ec_admin_update_advanced_option_fields( );">
                        <option value="0"><?php esc_attr_e( 'Select One', 'wp-easycart' ); ?></option>
                        <option value="combo"><?php esc_attr_e( 'Combo / Select Box', 'wp-easycart' ); ?></option>
                        <option value="swatch"><?php esc_attr_e( 'Swatches', 'wp-easycart' ); ?></option>
                        <option value="text"><?php esc_attr_e( 'Text Input', 'wp-easycart' ); ?></option>
                        <option value="textarea"><?php esc_attr_e( 'Text Area (Multi-line)', 'wp-easycart' ); ?></option>
                        <option value="number"><?php esc_attr_e( 'Number Input', 'wp-easycart' ); ?></option>
                        <option value="file"><?php esc_attr_e( 'File Upload', 'wp-easycart' ); ?></option>
                        <option value="radio"><?php esc_attr_e( 'Radio Group', 'wp-easycart' ); ?></option>
                        <option value="checkbox"><?php esc_attr_e( 'Checkbox Group', 'wp-easycart' ); ?></option>
                        <option value="grid"><?php esc_attr_e( 'Quantity Grid', 'wp-easycart' ); ?></option>
                        <option value="date"><?php esc_attr_e( 'Date Input', 'wp-easycart' ); ?></option>
                        <option value="dimensions1"><?php esc_attr_e( 'Dimensions (Whole Inch)', 'wp-easycart' ); ?></option>
                        <option value="dimensions2"><?php esc_attr_e( 'Dimensions (Sub-Inch)', 'wp-easycart' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_adv_option_name"><?php esc_attr_e( 'Modifier Name (Internal Use)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_adv_option_name" name="ec_new_adv_option_name" placeholder="<?php esc_attr_e( 'Product Shirt Sizes', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_adv_option_label"><?php esc_attr_e( 'Modifier Label', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_adv_option_label" name="ec_new_adv_option_label" placeholder="<?php esc_attr_e( 'Select a Shirt Size', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row" style="display:none;" id="ec_new_adv_option_meta_min_row">
                <label for="ec_new_adv_option_meta_min"><?php esc_attr_e( 'Minimum Value (leave blank for no minimum)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_adv_option_meta_min" name="ec_new_adv_option_meta_min" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row" style="display:none;" id="ec_new_adv_option_meta_max_row">
                <label for="ec_new_adv_option_meta_max"><?php esc_attr_e( 'Maximum Value (leave blank for no maximum)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_adv_option_meta_max" name="ec_new_adv_option_meta_max" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row" style="display:none;" id="ec_new_adv_option_meta_step_row">
                <label for="ec_new_adv_option_meta_step"><?php esc_attr_e( 'Step (e.g. .01 | .1 | 1 | 5...)', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_adv_option_meta_step" name="ec_new_adv_option_meta_step" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <input type="checkbox" id="ec_new_adv_option_required" name="ec_new_adv_option_required" value="1" onchange="ec_admin_update_advanced_option_required_field( );" />
                <label for="ec_new_adv_option_required"><?php esc_attr_e( 'Is Modifier Required?', 'wp-easycart' ); ?></label>
            </div>
            <div class="ec_admin_slideout_container_input_row" style="display:none;" id="ec_new_adv_option_error_text_row">
                <label for="ec_new_adv_option_error_text"><?php esc_attr_e( 'Error Message', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_adv_option_error_text" name="ec_new_adv_option_error_text" placeholder="<?php esc_attr_e( 'Please select a shirt size', 'wp-easycart' ); ?>" />
                </div>
            </div>
        </div>
        <footer class="ec_admin_slideout_container_content_footer">
            <div class="ec_admin_slideout_container_content_footer_inner">
                <div class="ec_admin_slideout_container_content_footer_inner_body">
                    <ul>
                        <li>
                            <button onclick="ec_admin_save_new_adv_optionset( );">
                                <span><?php esc_attr_e( 'Create Modifier', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                        <li>
                            <button class="ec_footer_slideout_button_alt" onclick="wp_easycart_admin_close_slideout( 'new_adv_option_box' );">
                                <span><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>
<script>jQuery( document.getElementById( 'new_adv_option_box' ) ).appendTo( document.body );</script>