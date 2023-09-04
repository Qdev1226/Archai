<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
        <div class="dashicons-before dashicons-welcome-add-page"></div>
        <span><?php esc_attr_e( 'Design Template System', 'wp-easycart' ); ?></span>
        <a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'design', 'templates' ) ); ?>" target="_blank" class="ec_help_icon_link">
            <div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
        </a>
        <?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'design', 'templates');?>
    </div>
    
    <?php
    if( is_dir( EC_PLUGIN_DATA_DIRECTORY . '/' ) )
        $dir = EC_PLUGIN_DATA_DIRECTORY . '/design/theme/';
    else
        $dir = EC_PLUGIN_DIRECTORY . '/design/theme/';

    $scan = scandir( $dir );
    
    $theme_options = array(
        (object) array(
            'value'	=> '0',
            'label'	=> __( 'No Child Theme Used', 'wp-easycart' )
        )
    );
    foreach( $scan as $key => $val ){
        if( $val != '.' && $val != '..' ){
            $theme_options[] = (object) array(
                'value'	=> $val,
                'label'	=> $val
            );
        }
    }
    ?>
    
    <?php
    if( is_dir( EC_PLUGIN_DATA_DIRECTORY . '/' ) )
        $dir = EC_PLUGIN_DATA_DIRECTORY . '/design/layout/';
    else
        $dir = EC_PLUGIN_DIRECTORY . '/design/layout/';

    $scan = scandir( $dir );
    
    $layout_options = array(
        (object) array(
            'value'	=> '0',
            'label'	=> __( 'No Child Layout Used', 'wp-easycart' )
        )
    );
    foreach( $scan as $key => $val ){
        if( $val != '.' && $val != '..' ){
            $layout_options[] = (object) array(
                'value'	=> $val,
                'label'	=> $val
            );
        }
    }
    ?>
    
    <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
	    
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_base_theme', 'ec_admin_save_design_text_setting', get_option( 'ec_option_base_theme' ), __( 'EasyCart Custom Theme', 'wp-easycart' ), __( 'This is an advanced design feature allowing you to design and implement custom CSS and design files for your site.', 'wp-easycart' ), $theme_options, 'ec_admin_ec_option_base_theme_row', true, false ); ?>
	    
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_base_layout', 'ec_admin_save_design_text_setting', get_option( 'ec_option_base_layout' ), __( 'EasyCart Custom Layout', 'wp-easycart' ), __( 'This is an advanced design feature allowing you to design and implement custom layouts in PHP and html for your site.', 'wp-easycart' ), $layout_options, 'ec_admin_ec_option_base_layout_row', true, false ); ?>
		
    </div>
</div>