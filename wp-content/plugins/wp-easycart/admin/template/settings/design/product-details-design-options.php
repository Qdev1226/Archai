<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_details_design_options" ); ?>
    
    <div class="ec_admin_settings_label">
        <div class="dashicons-before dashicons-media-document"></div>
        <span><?php esc_attr_e( 'Product Details Design Options', 'wp-easycart' ); ?></span>
        <a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'design', 'product-details' ) ); ?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
        </a>
        <?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'design', 'product-details');?>
    </div>
    
    <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
        
        <?php
        $columns_options = array(
            (object) array(
                'value'	=> '1',
                'label'	=> __( '1 Column', 'wp-easycart' )
            ),
            (object) array(
                'value'	=> '2',
                'label'	=> __( '2 Columns', 'wp-easycart' )
            )
        );
        ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_details_columns_desktop', 'ec_admin_save_design_text_setting', get_option( 'ec_option_details_columns_desktop' ), __( 'Product Details: Desktop Columns', 'wp-easycart' ), __( 'The number of columns (for desktop) should be adjusted to best fit your theme and design situation.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_details_columns_desktop_row', true, false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_details_columns_laptop', 'ec_admin_save_design_text_setting', get_option( 'ec_option_details_columns_laptop' ), __( 'Product Details: Tablet Landscape Columns', 'wp-easycart' ), __( 'The number of columns (for a horizontal tablet) should be adjusted to best fit your theme and design situation.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_details_columns_laptop_row', true, false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_details_columns_tablet_wide', 'ec_admin_save_design_text_setting', get_option( 'ec_option_details_columns_tablet_wide' ), __( 'Product Details: Tablet Portrait Columns', 'wp-easycart' ), __( 'The number of columns (for a vertical tablet) should be adjusted to best fit your theme and design situation.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_details_columns_tablet_wide_row', true, false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_details_columns_tablet', 'ec_admin_save_design_text_setting', get_option( 'ec_option_details_columns_tablet' ), __( 'Product Details: Smartphone Landscape Columns', 'wp-easycart' ), __( 'The number of columns (for horizontal phone) should be adjusted to best fit your theme and design situation.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_details_columns_tablet_row', true, false ); ?>
        
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_details_columns_smartphone', 'ec_admin_save_design_text_setting', get_option( 'ec_option_details_columns_smartphone' ), __( 'Product Details: Smartphone Portrait Columns', 'wp-easycart' ), __( 'The number of columns (for a vertical phone) should be adjusted to best fit your theme and design situation.', 'wp-easycart' ), $columns_options, 'ec_admin_ec_option_details_columns_smartphone_row', true, false ); ?>
        
    </div>
    
</div>