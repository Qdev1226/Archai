<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_order_receipt_language_loader" ); 
		if( isset( $_GET['subpage'] ) && $_GET['subpage'] == "email-setup" && isset( $_GET['ec_action'] ) && $_GET['ec_action'] == "save_language" ){
			wp_easycart_language( )->update_language_data( );
			$isupdate = "5";
		}
	
	?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-testimonial"></div>
		<span><?php esc_attr_e( 'Order Receipt Phrases', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'email-setup', 'order-receipt-language' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'email-setup', 'order-receipt-language');?>
	</div>
    
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
    
    	<?php if( isset($isupdate) && $isupdate == "5" ) { ?>
            <div class="ec_save_success"><span><?php esc_attr_e( 'Updated Successfully!', 'wp-easycart' ); ?></span></div>
        <?php } ?>
		
   		<?php
			$file_name = get_option( 'ec_option_language' );
			$key_section = 'cart_success';
			$language_section = wp_easycart_language( )->get_language_section( $file_name, $key_section );
			$section_label = $language_section->label;
		?>
        
        <form action="admin.php?page=wp-easycart-settings&subpage=email-setup&ec_action=save_language" method="POST">
        
			<input type="hidden" name="file_name" id="wp_easycart_admin_file_name" value="<?php echo esc_attr( $file_name ); ?>" />
			<input type="hidden" name="key_section" id="wp_easycart_admin_key_section" value="<?php echo esc_attr( $key_section ); ?>" />
			<input type="hidden" name="isupdate" value="1" />
        	
			<?php
            foreach( $language_section->options as $key => $language_item ){
				$title = $language_item->title;
				$value = $language_item->value;
				?>
				
				<?php wp_easycart_admin( )->load_toggle_group_text( $key, 'ec_admin_save_email_language_setting', $value, $title, '', '', 'ec_admin_ec_language_field_' . $key . '_row', true, false ); ?>
			
            <?php }?>
			
         </form>
		
    </div>
</div>