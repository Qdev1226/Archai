<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-cart"></div>
		<span><?php esc_attr_e( 'Cart Design Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'design', 'cart' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'design', 'cart');?>
    </div>
	
	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">
        
		<?php
			global $wpdb;
			$column_options = array(
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
		
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cart_columns_desktop', 'ec_admin_save_design_text_setting', get_option( 'ec_option_cart_columns_desktop' ), __( 'Cart Page Columns: Desktop', 'wp-easycart' ), __( 'Select number of columns to show on a desktop screen size', 'wp-easycart' ), $column_options, 'ec_admin_ec_option_cart_columns_desktop_row', true, false ); ?>
		
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cart_columns_laptop', 'ec_admin_save_design_text_setting', get_option( 'ec_option_cart_columns_laptop' ), __( 'Cart Page Columns: Tablet Horizontal', 'wp-easycart' ), __( 'Select number of columns to show on a tablet screen size', 'wp-easycart' ), $column_options, 'ec_admin_ec_option_cart_columns_laptop_row', true, false ); ?>
		
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cart_columns_tablet_wide', 'ec_admin_save_design_text_setting', get_option( 'ec_option_cart_columns_tablet_wide' ), __( 'Cart Page Columns: Tablet Vertical', 'wp-easycart' ), __( 'Select number of columns to show on a landscape tablet screen size', 'wp-easycart' ), $column_options, 'ec_admin_ec_option_cart_columns_tablet_wide_row', true, false ); ?>
		
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cart_columns_tablet', 'ec_admin_save_design_text_setting', get_option( 'ec_option_cart_columns_tablet' ), __( 'Cart Page Columns: Phone Horizontal', 'wp-easycart' ), __( 'Select number of columns to show on a landscape phone screen size', 'wp-easycart' ), $column_options, 'ec_admin_ec_option_cart_columns_tablet_row', true, false ); ?>
		
        <?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cart_columns_smartphone', 'ec_admin_save_design_text_setting', get_option( 'ec_option_cart_columns_smartphone' ), __( 'Cart Page Columns: Phone Vertical', 'wp-easycart' ), __( 'Select number of columns to show on a vertical phone screen size', 'wp-easycart' ), $column_options, 'ec_admin_ec_option_cart_columns_smartphone_row', true, false ); ?>
		
	</div>
	
</div>