<form action="<?php echo esc_attr( $this->action ); ?>"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form" novalidate="novalidate">
<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-menulevel1-details' ); ?>
<input type="hidden" name="ec_admin_form_action" value="<?php echo esc_attr( $this->form_action ); ?>" />
<input type="hidden" name="menulevel1_id" value="<?php echo esc_attr( $this->menulevel1->menulevel1_id ); ?>" />

<div class="ec_admin_settings_panel ec_admin_details_panel">
    <div class="ec_admin_important_numbered_list">
        <div class="ec_admin_flex_row">
            <div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">
            
                <div class="ec_admin_settings_label">
                    <div class="dashicons-before dashicons-products"></div>
                    <span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-menulevel1' ){ esc_attr_e( 'ADD NEW MENU', 'wp-easycart' ); }else{ esc_attr_e( 'EDIT MENU', 'wp-easycart' ); } ?></span>
                    <div class="ec_page_title_button_wrap">
                        <a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
                            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
                        </a>
                        <?php wp_easycart_admin( )->helpsystem->print_vids_url('products', 'menus', 'details');?>
                        <a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                        <?php if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ){ ?>
                        <a href="post.php?post=<?php echo esc_attr( $this->menulevel1->post_id ); ?>&action=edit" target="_blank" class="ec_page_title_button"><?php esc_attr_e( 'Edit Yoast SEO', 'wp-easycart' ); ?></a>
                        <?php } ?>
                        <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
                    </div>
                </div>
            
                <div class="ec_admin_settings_input ec_admin_settings_currency_section">
                	<?php do_action( 'wp_easycart_admin_menulevel1_details_basic_fields' ); ?>
                </div>
            </div>
        </div>
      	<div class="ec_admin_details_footer">
            <div class="ec_page_title_button_wrap">
                <a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
                <?php if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) || is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' ) ){ ?>
                <a href="post.php?post=<?php echo esc_attr( $this->menulevel1->post_id ); ?>&action=edit" target="_blank" class="ec_page_title_button"><?php esc_attr_e( 'Edit Yoast SEO', 'wp-easycart' ); ?></a>
                <?php } ?>
                <input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
            </div>
        </div>  
    </div>
</div>
</form>