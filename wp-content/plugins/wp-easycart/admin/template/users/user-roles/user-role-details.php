<form action="<?php echo esc_attr( $this->action ); ?>"  method="POST" id="wpeasycart_admin_form" name="wpeasycart_admin_form" novalidate="novalidate">
<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-user-role-details' ); ?>
<input type="hidden" name="ec_admin_form_action" value="<?php echo esc_attr( $this->form_action ); ?>" />
<input type="hidden" name="role_id" value="<?php echo esc_attr( $this->user_role->role_id ); ?>" />

<div class="ec_admin_settings_panel ec_admin_details_panel">
    <div class="ec_admin_important_numbered_list">
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-admin-users"></div>
                    <span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ){ esc_attr_e( 'ADD NEW USER ROLE', 'wp-easycart' ); }else{ esc_attr_e( 'EDIT USER ROLE', 'wp-easycart' ); } ?></span>
                    <div class="ec_page_title_button_wrap">
                        <a href="<?php echo esc_url( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                        <a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                        <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
                    </div>
                </div>
            
                <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                	<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title"><?php esc_attr_e( 'User Role Setup', 'wp-easycart' ); ?><br></div>
                    <div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p><?php esc_attr_e( 'You may setup user roles so that pricing of products and store access can be limited to a select group of accounts or individuals.  Establish a user role, edit accounts to be a part of this user role, and lock the store down to only these users or adjust product price using user roles.', 'wp-easycart' ); ?></p></div>
					<?php do_action( 'wp_easycart_admin_user_role_details_basic_fields' ); ?>
                </div>
            </div>
        </div>
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-admin-users"></div>
                    <span><?php esc_attr_e( 'Remote User Access', 'wp-easycart' ); ?></span>
                    <div class="ec_page_title_button_wrap">
                        <a href="<?php echo esc_url( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                    </div>
                </div>
            
                <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                	<?php do_action( 'wp_easycart_admin_user_role_details_remote_access_fields' ); ?>
                </div>
            </div>
        </div>
      	<div class="ec_admin_details_footer">
            <div class="ec_page_title_button_wrap">
                <a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
            </div>
        </div>  
    </div>
</div>
</form>