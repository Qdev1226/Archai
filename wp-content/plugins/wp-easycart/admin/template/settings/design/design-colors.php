<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
        <div class="dashicons-before dashicons-admin-appearance"></div>
        <span><?php esc_attr_e( 'Store Colors', 'wp-easycart' ); ?></span>
        <a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'design', 'colors' ) ); ?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
        </a>
        <?php wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'design', 'colors' ); ?>
    </div>
    <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
	
		<?php wp_easycart_admin( )->load_toggle_group_color( 'ec_option_details_main_color', 'ec_admin_save_design_color_setting', get_option( 'ec_option_details_main_color' ), __( 'Main Color', 'wp-easycart' ), __( 'Your main color for your shopping system.', 'wp-easycart' ), '', 'ec_admin_ec_option_details_main_color_row', true, false ); ?>
	
		<?php wp_easycart_admin( )->load_toggle_group_color( 'ec_option_details_second_color', 'ec_admin_save_design_color_setting', get_option( 'ec_option_details_second_color' ), __( 'Second Color', 'wp-easycart' ), __( 'Your secondary color for your shopping system, mostly used in hover effects.', 'wp-easycart' ), '', 'ec_admin_ec_option_details_second_color_row', true, false ); ?>
	
		<?php wp_easycart_admin( )->load_toggle_group_color( 'ec_option_admin_color', 'ec_admin_save_design_color_setting', get_option( 'ec_option_admin_color' ), __( 'Admin Color', 'wp-easycart' ), __( 'The color scheme of the EasyCart admin display.', 'wp-easycart' ), '', 'ec_admin_ec_option_admin_color_row', true, false ); ?>
	    
        <?php
        $text_options = array(
            (object) array(
                'value'	=> '0',
                'label'	=> __( 'Dark Text (MOST COMMON, Used on white or light backgrounds)', 'wp-easycart' )
            ),
            (object) array(
                'value'	=> '1',
                'label'	=> __( 'White Text (CAUTION: Only use on dark background websites)', 'wp-easycart' )
            )
        );
        ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_use_dark_bg', 'ec_admin_save_design_text_setting', get_option( 'ec_option_use_dark_bg' ), __( 'Invert Colors', 'wp-easycart' ), __( 'Select number of columns to show on the desktop screen size', 'wp-easycart' ), $text_options, 'ec_admin_ec_option_use_dark_bg_row', true, false ); ?>
		
    </div>
</div>