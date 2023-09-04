<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_goal_setup" ); ?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-cart"></div>
		<span><?php esc_attr_e( 'eCommerce Goals', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'initial-setup', 'goals' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
     	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'initial-setup', 'goals');?>
	</div>
    
    <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
		
        <?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_goals_settings_nonce', 'wp-easycart-initial-setup-goals-setup' ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_admin_display_sales_goal', 'wpeasycart_admin_update_goals_view( ); ec_admin_save_initial_setup_options', get_option( 'ec_option_admin_display_sales_goal' ), __( 'eCommerce Goals', 'wp-easycart' ), __( 'Enabling this shows the goals information on the left of the admin.', 'wp-easycart' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_admin_sales_goal', 'ec_admin_save_initial_setup_text_setting', ( ( get_option( 'ec_option_admin_sales_goal' ) ) ? get_option( 'ec_option_admin_sales_goal' ) : '1000' ), __( 'Monthly Goal', 'wp-easycart' ), __( 'Enter your monthly sales goal value. For example: 2,500', 'wp-easycart' ), '5,000', 'ec_admin_sales_goal_row', ( ( get_option( 'ec_option_admin_display_sales_goal' ) == "1" ) ? true : false ), false, false ); ?>  
	</div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_goals_setup( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart' ); ?>" />
    </div>
</div>