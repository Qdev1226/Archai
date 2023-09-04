<form action="<?php echo esc_attr( $this->action ); ?>"  method="POST" id="wpeasycart_admin_form" name="wpeasycart_admin_form" novalidate="novalidate">
<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-user-details' ); ?>
<input type="hidden" name="ec_admin_form_action" value="<?php echo esc_attr( $this->form_action ); ?>" />
<input type="hidden" name="user_id" value="<?php echo esc_attr( $this->user->user_id ); ?>" />

<div class="ec_admin_settings_panel ec_admin_details_panel">
    <div class="ec_admin_important_numbered_list">
        <?php do_action( 'wpeasycart_admin_user_details_top', $this->user ); ?>
            
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-admin-users"></div>
                    <span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){ esc_attr_e( 'ADD NEW USER ACCOUNT', 'wp-easycart' ); }else{ esc_attr_e( 'EDIT USER ACCOUNT', 'wp-easycart' ); } ?></span>
                    <div class="ec_page_title_button_wrap">
                        <a href="<?php echo esc_url( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                        <?php if( !isset( $_GET['ec_admin_form_action'] ) || $_GET['ec_admin_form_action'] != "add-new" ){ ?>
                            <a href="admin.php?page=wp-easycart-orders&subpage=orders&filter_2=<?php echo esc_attr( $this->user->user_id ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'View Orders', 'wp-easycart' ); ?></a>
                            <a href="admin.php?page=wp-easycart-users&subpage=accounts&ec_admin_form_action=user-login-override&user_id=<?php echo esc_attr( $this->user->user_id ); ?>&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-login-as-user' ) ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Login as User', 'wp-easycart' ); ?></a>
                        <?php }?>
                        <a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                        <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
                    </div>
                </div>
            
                <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                	<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'User Account Setup', 'wp-easycart' ); ?><br></div>
                    <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'EasyCart user accounts can store their billing and shipping address information as well as important shipping, tax, and user role preferences.', 'wp-easycart' ); ?></p></div>
					<?php do_action( 'wp_easycart_admin_user_details_basic_fields' ); ?>
                </div>
            </div>
        </div>
        
        <?php do_action( 'wpeasycart_admin_user_details_top_settings', $this->user ); ?>
        
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-admin-users"></div>
                    <span><?php esc_attr_e( 'EDIT OPTIONAL ACCOUNT SETTINGS', 'wp-easycart' ); ?></span>
                    <div class="ec_page_title_button_wrap">
                        <a href="<?php echo esc_url( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                    </div>
                </div>
            
                <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                	<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'Optional User Account Settings', 'wp-easycart' ); ?><br></div>
                    <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'You may add various optional components to this account such as exclude them from shipping and taxes. You may also add administrative notes or VAT registration numbers depending on your needs.', 'wp-easycart' ); ?></p></div>
					<?php do_action( 'wp_easycart_admin_user_details_optional_fields' ); ?>
                </div>
            </div>
        </div>
        
        <?php do_action( 'wpeasycart_admin_user_details_top_addresses', $this->user ); ?>
        
        <div class="ec_admin_flex_row">
            
            <?php do_action( 'wpeasycart_admin_user_details_top_billing', $this->user ); ?>
            
            <div class="ec_admin_list_line_item ec_admin_col_6 ec_admin_col_first">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-admin-users"></div>
                    <span><?php esc_attr_e( 'EDIT BILLING ADDRESS', 'wp-easycart' ); ?></span>
                    <div class="ec_page_title_button_wrap">
                    	<a  class="ec_page_title_button" onclick="copy_to_shipping();"><?php esc_attr_e( 'Copy to Shipping', 'wp-easycart' ); ?></a>
                        <a href="<?php echo esc_url( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                    </div>
                </div>
            
                <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
                	<?php do_action( 'wp_easycart_admin_user_details_billing_fields' ); ?>
                </div>
            </div>
            
            <?php do_action( 'wpeasycart_admin_user_details_top_shipping', $this->user ); ?>
            
            <div class="ec_admin_list_line_item ec_admin_col_6 ec_admin_col_last">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-admin-users"></div>
                    <span><?php esc_attr_e( 'EDIT SHIPPING ADDRESS', 'wp-easycart' ); ?></span>
                    <div class="ec_page_title_button_wrap">
                        <a href="<?php echo esc_url( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                    </div>
                </div>
            
                <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
                	<?php do_action( 'wp_easycart_admin_user_details_shipping_fields' ); ?>
                </div>
            </div>
        </div>
		
        <?php do_action( 'wpeasycart_admin_user_details_bottom', $this->user ); ?>
        
		<div class="ec_admin_details_footer">
            <div class="ec_page_title_button_wrap">
                <a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
            </div>
        </div>  
    </div>
</div>
</form>