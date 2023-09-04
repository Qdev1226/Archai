<form action="<?php echo esc_attr( $this->action ); ?>"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form" novalidate="novalidate">
    <?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-optionitem-details' ); ?>
    <input type="hidden" name="ec_admin_form_action" value="<?php echo esc_attr( $this->form_action ); ?>" />
    <?php
        global $wpdb;
        if( isset( $_GET['option_id'] ) ){
            $option_id = (int) $_GET['option_id'];
        } else {
            $option_id = $wpdb->get_var( $wpdb->prepare( "SELECT ec_optionitem.option_id FROM ec_optionitem WHERE ec_optionitem.optionitem_id = %d", (int) $_GET['optionitem_id'] ) );
        }	
        $option_type = $wpdb->get_var( $wpdb->prepare( "SELECT ec_option.option_type FROM ec_option WHERE ec_option.option_id = %d", $option_id ) );
    ?>
    <input type="hidden" name="option_id" id="option_id" value="<?php echo esc_attr( $option_id ); ?>" />	
    <input type="hidden" name="optionitem_id" id="optionitem_id" value="<?php echo esc_attr( $this->optionitem->optionitem_id ); ?>" />
    <input type="hidden" name="option_type" id="option_type" value="<?php echo esc_attr( $option_type ); ?>" />

    <div class="ec_admin_settings_panel ec_admin_details_panel">
        <div class="ec_admin_important_numbered_list">


            <div class="ec_admin_flex_row">
                <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

                    <div class="ec_admin_settings_label">
                        <div class="dashicons-before dashicons-image-filter"></div>
                        <span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-optionitem' ){ esc_attr_e( 'ADD NEW OPTION ITEM', 'wp-easycart' ); }else{ esc_attr_e( 'EDIT OPTION ITEM', 'wp-easycart' ); } ?></span>
                        <div class="ec_page_title_button_wrap">
                            <a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url('products', 'option-sets', 'option-sets') ); ?>" target="_blank" class="ec_help_icon_link">
                                <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                            </a>
                            <?php wp_easycart_admin( )->helpsystem->print_vids_url('products', 'option-sets', 'details');?>
                            <a href="<?php echo esc_attr( $this->action ); ?>&option_id=<?php echo esc_attr( $option_id ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                            <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
                        </div>
                    </div>

                    <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                        <?php do_action( 'wp_easycart_admin_optionitem_details_basic_fields' ); ?>
                    </div>
               </div>
            </div>     


             <?php 
                //only show advanced form fields if advanced option set
                if ($option_type == 'combo' || $option_type == 'swatch' ||$option_type == 'text' ||$option_type == 'textarea' ||$option_type == 'file' ||$option_type == 'radio' ||$option_type == 'checkbox' ||$option_type == 'grid' ||$option_type == 'date' ||$option_type == 'dimensions1' ||$option_type == 'dimensions2' ||$option_type == 'number') {
            ?>
              <div class="ec_admin_flex_row ec_admin_advanced_fields">
                <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">       


                    <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                        <div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'Advanced Option Settings', 'wp-easycart' ); ?><br></div>
                        <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'The following advanced option properties can be adjusted.  Swatch type images can upload an image to represent that option, or you can choose which option is initially selected and/or whether options allow shipping of products or product downloads.', 'wp-easycart' ); ?></p></div>
                        <?php do_action( 'wp_easycart_admin_optionitem_details_advanced_fields' ); ?>
                    </div>

                </div>
            </div>


             <div class="ec_admin_flex_row ec_admin_price_fields">
                <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">       


                    <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                        <div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'Advanced Price Options', 'wp-easycart' ); ?><br></div>
                        <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'You may select to have a price adjustment made based on the option selected by the user.  Simply select the price adjustment type and then enter the value for that type.', 'wp-easycart' ); ?></p>

                        </div>
                        <div class="ec_admin_row_weight_adjustment"><?php esc_attr_e( 'Price Adjustment Type:', 'wp-easycart' ); ?>  <select id="ec_optionitem_price" onchange="ec_admin_optionitem_price_adjustment();">
                          <option value="0"><?php esc_attr_e( 'No Price Adjustments', 'wp-easycart' ); ?></option>
                          <option value="basic_price"><?php esc_attr_e( 'Basic Price Adjustment', 'wp-easycart' ); ?></option>
                          <option value="one_time_price"><?php esc_attr_e( 'One-Time (per cart) Price Adjustment', 'wp-easycart' ); ?></option>
                          <option value="override_price"><?php esc_attr_e( 'Product Price Over-Ride', 'wp-easycart' ); ?></option>
                          <option value="multiplier_price"><?php esc_attr_e( 'Product Price Multiplier (not compatible with subscriptions)', 'wp-easycart' ); ?></option>
                          <?php
                            if ($option_type == 'text') {
                          ?>
                          <option value="per_character_price"><?php esc_attr_e( 'Per-Character Price Adjustment', 'wp-easycart' ); ?></option>
                          <?php
                            }
                          ?>
                        </select>
                        </p>
                        </div>
                        <?php do_action( 'wp_easycart_admin_optionitem_details_price_fields' ); ?>
                    </div>

                </div>
            </div>


            <div class="ec_admin_flex_row ec_admin_weight_fields">
                <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">       


                    <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                        <div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'Advanced Weight Options', 'wp-easycart' ); ?><br></div>
                        <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'You may select to have a weight adjustment made based on the option selected by the user.  Simply select the weight adjustment type and then enter the value for that type.', 'wp-easycart' ); ?></p>

                        </div>
                        <div class="ec_admin_row_weight_adjustment"><?php esc_attr_e( 'Weight Adjustment Type:', 'wp-easycart' ); ?>  <select id="ec_optionitem_weight" onchange="ec_admin_optionitem_weight_adjustment();">
                          <option value="0"><?php esc_attr_e( 'No Weight Adjustments', 'wp-easycart' ); ?></option>
                          <option value="basic_weight"><?php esc_attr_e( 'Basic Weight Adjustment', 'wp-easycart' ); ?></option>
                          <option value="one_time_weight"><?php esc_attr_e( 'One-Time (per cart) Weight Adjustment', 'wp-easycart' ); ?></option>
                          <option value="override_weight"><?php esc_attr_e( 'Product Weight Over-Ride', 'wp-easycart' ); ?></option>
                          <option value="multiplier_weight"><?php esc_attr_e( 'Product Weight Multiplier (not compatible with subscriptions)', 'wp-easycart' ); ?></option>
                        </select>
                        </p>
                        </div>
                        <?php do_action( 'wp_easycart_admin_optionitem_details_weight_fields' ); ?>
                    </div>

                </div>
            </div>
            <?php
                }
            ?>
            <div class="ec_admin_details_footer">
                <div class="ec_page_title_button_wrap">
                    <a href="<?php echo esc_attr( $this->action ); ?>&option_id=<?php echo esc_attr( $option_id ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                    <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
                </div>
            </div>  
        </div>
    </div>
</form>