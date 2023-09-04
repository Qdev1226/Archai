<div class="ec_admin_list_line_item_fullwidth ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_language_editor_loader" ); 
	
		$validate = new ec_validation; 
		wp_easycart_language( )->update_language_data( ); //Do this to update the database if a new language is added
		
		if( !get_option( 'ec_option_use_seperate_language_forms' ) )
			update_option( 'ec_option_use_seperate_language_forms', 1 );
			
		if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "language-editor" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "update-selected-language" && isset( $_POST['ec_option_language'] ) ){
			update_option( 'ec_option_language', sanitize_key( $_POST['ec_option_language'] ) );
		
		}else if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "language-editor" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "delete-language" && isset( $_GET['ec_language'] ) ){
			wp_easycart_language( )->remove_language( sanitize_key( $_GET['ec_language'] ) );
		
		}else if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "language-editor" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "add-new-language" && isset( $_POST['ec_option_add_language'] ) ){
			wp_easycart_language( )->add_new_language( sanitize_key( $_POST['ec_option_add_language'] ) );
		}
	
	
	?>
    
    <div class="ec_admin_settings_label"><div class="dashicons-before dashicons-admin-users"></div><span><?php esc_attr_e( 'Language Settings', 'wp-easycart' ); ?></span></div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
   		
        <span><?php esc_attr_e( 'Language Section', 'wp-easycart' ); ?></span>
        
        <form method="post" action="admin.php?page=wp-easycart-settings&subpage=language-editor&ec_action=add-new-language" name="wpeasycart_admin_form" id="wpeasycart_admin_form_lang2" novalidate="novalidate">
            <div><?php esc_attr_e( 'Add Language', 'wp-easycart' ); ?>:<select name="ec_option_add_language" id="ec_option_add_language">
                <?php 
                $add_count = 0;
                $language_file_list = wp_easycart_language( )->get_language_file_list( );
                $languages = wp_easycart_language( )->get_languages_array( );
                $language_data = wp_easycart_language( )->get_language_data( );
                for( $i=0; $i<count( $language_file_list ); $i++ ){ 
                    $file_name = $language_file_list[$i];
                    if( !in_array( $file_name, $languages ) ){
                ?>
                    <option value="<?php echo esc_attr( $file_name ); ?>" <?php if( get_option( 'ec_option_language' ) == $file_name ) echo ' selected'; ?>><?php echo esc_attr( $language_file_list[$i] ); ?></option>
                <?php
                    $add_count++;
                    }
                }
                if( $add_count == 0 ){ ?>
                <option value=""><?php esc_attr_e( 'No New Languages', 'wp-easycart' ); ?></option>
                <?php } ?>
                </select> 
                <?php if( $add_count > 0 ){ ?>
                <div class="ec_admin_settings_input"><input type="submit" value="<?php esc_attr_e( 'Add', 'wp-easycart' ); ?>" /></div>
                <?php }?>
            </div>
        </form>
        
        
       	
        <div class="ec_admin_settings_input">
            <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_language_editor( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart' ); ?>" />
        </div>
    </div>
</div>