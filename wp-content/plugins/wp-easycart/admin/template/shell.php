<style>
    .ec_admin_wizard_input_row_toggle > input:checked + .ec_admin_wizard_slider, .ec_admin_wizard_complete > span.bubble > span.dot, .wpeasycart-add-text-notification-button, .wpeasycart-add-text-notification-account-button{ background-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_wizard_current > span.bubble:before, .ec_admin_wizard_current > span.bubble:after, .ec_admin_wizard_complete > span.bubble:before, .ec_admin_wizard_complete > span.bubble:after{ background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_wizard_container > h1{ background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-top-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), .40 ) ); ?>; border-bottom-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.40 ) ); ?>; }
    .ec_admin_wizard_current > span.bubble, .ec_admin_wizard_complete > span.bubble{ background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_default_color1-color, .ec_admin_wizard_current, .ec_admin_wizard_complete, .ec_admin_order_details_currency_grand_total, .ec_admin_order_details_currency_grand_total_label{ color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> !important; }
    .ec_admin_default_color1-background, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_item:hover, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_selected, .ec_admin_top_nav_link:hover, .ec_admin_bottom_stats_bar_positive, .wp_easycart_wizard_success_box_button > a, a.ec_admin_wizard_next_button, input[type="submit"].ec_admin_wizard_next_button, .ec_admin_content_area .tablenav .tablenav-pages a:hover, .ec_admin_content_area .tablenav .tablenav-pages a:focus, .ec_admin_content_area .tablenav .tablenav-pages a:active, .ec_admin_content_area input[type="submit"].ec_admin_list_submit:hover, .ec_admin_content_area input[type="submit"].ec_admin_list_submit:active, .ec_admin_content_area input[type="submit"].ec_admin_list_submit:focus, .ec_admin_slideout_container_content_header, .ec_admin_slideout_container_input_row .wpec-admin-upload-button, .ec_admin_slideout_container_content_footer_inner_body button, .ec_admin_help_video_box > h1 > a, #ec_admin_add_new_optionitem_quantity_row > input[type="button"], .ec_admin_stock_notification_view h4 > a, .ec_admin_stock_notification_view > .ec_admin_stock_notification_add_new > input[type="button"]{ background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; background-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> !important; border-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> !important; }
    .ec_admin_default_color1-background-gradient{ 
        background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>;
        background: -moz-linear-gradient(top, <?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> 0%, <?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?> 100%);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>), color-stop(100%,<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?>));
        background: -webkit-linear-gradient(top, <?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> 0%,<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?> 100%);
        background: -o-linear-gradient(top, <?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> 0%,<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?> 100%);
        background: -ms-linear-gradient(top, <?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> 0%,<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?> 100%);
        background: linear-gradient(to bottom, <?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> 0%,<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?> 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>', endColorstr='<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.20 ) ); ?>',GradientType=0 );
    }
    #ec_admin_add_new_optionitem_quantity_row > h3 > a, .ec_admin_content_area input[type="submit"].ec_admin_list_submit, .wpeasycart-timeline-item:nth-child(odd) a, .wpeasycart-timeline-item:nth-child(odd) span.dashicons{ color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    #ec_admin_add_new_optionitem_quantity_row > input[type="button"]:hover, .wpeasycart-add-text-notification-button:hover, .wpeasycart-add-text-notification-account-button:hover{ background-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?> !important; }
    .ec_admin_slideout_container_simple_row, .ec_admin_upgrade_box_signup_row > a, .ec_admin_order_details_item_actions > div.dashicons-edit:before, .ec_admin_order_details_item_actions > div.dashicons-yes:before{ background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_order_details_item_actions > div.dashicons-edit:hover:before, .ec_admin_order_details_item_actions > div.dashicons-yes:hover:before{ background:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.40 ) ); ?> }
    a.ec_admin_wizard_quit_button, .ec_admin_help_video_box > h1, .ec_admin_help_video_box > h1 > .dashicons-before:before{ color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    a.ec_admin_wizard_quit_button:hover{ color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?>; }
    .ec_admin_default_color1-border-right{ border-right-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_default_color1-border-left{ border-left-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_default_color2-background, .ec_admin_default_color2-background > input, a.ec_admin_wizard_next_button:hover, .ec_admin_slideout_container_input_row .wpec-admin-upload-button:hover, input[type="submit"].ec_admin_wizard_next_button:hover, .ec_admin_slideout_container_content_footer_inner_body button:hover, .ec_admin_upgrade_box_signup_row > a:hover, #ec_admin_add_new_advanced_option_row > input, #ec_admin_add_new_category_row > input, #ec_admin_add_new_price_tier_row > input[type="button"], #ec_admin_add_new_role_price_row > input[type="button"], .ec_admin_stock_notification_view h4 > a:hover, .ec_admin_stock_notification_view > .ec_admin_stock_notification_add_new > input[type="button"]:hover{ background:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), .4 ) ); ?>; background-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?> !important; }
    .ec_admin_review_star_on{ color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-bottom:5px solid <?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?> !important; }
    .ec_admin_review_star_on:before{ border-bottom-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_review_star_on:after{ color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-bottom-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_default_color2-border, .ec_admin_customer_info_top{ border-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?>; }
    .ec_admin_default_color3-background, .ec_admin_left_nav_subitem.ec_admin_left_nav_selected, .ec_admin_bottom_stats_bar{ background:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?>; background-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?>; }
    .ec_admin_top_nav_link{ border-left-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-right-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.4 ) ); ?>; }
    .ec_admin_content_area, .ec_admin_right_stats{ border-bottom-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-left-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_wrap{ border-right-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_left_stats{ border-right-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), .4 ) ); ?>; }
    .ec_admin_left_nav_subitem.ec_admin_left_nav_selected{ background-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.6 ) ); ?>; }
    .ec_admin_content_area h1.wp-heading-inline, .ec_admin_content_area h1.easycart-wp-heading-inline, .ec_admin_content_area h1.wp-heading-inline a, .ec_admin_content_area h1.easycart-wp-heading-inline a, .ec_admin_settings_label{ color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.6 ) ); ?>; }
    .ec_admin_content_area h1.wp-heading-inline a:hover, .ec_admin_content_area h1.easycart-wp-heading-inline a:hover, #ec_admin_add_new_optionitem_quantity_row > h3 > a:hover{ color:#FFF; background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.6 ) ); ?>; }
    .ec_admin_content_area .wp-list-table a{ color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), -.6 ) ); ?>; }
    .ec_admin_content_area .wp-list-table a:hover{ color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_stats_container, #ec_admin_row_tier_pricing, #ec_admin_row_b2b_pricing, .ec_admin_details_panel textarea, .ec_admin_settings_input input[type="checkbox"]{ border-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_products_submit > input.ec_admin_products_simple_button, .ec_admin_product_details_manufacturer_column2 > input[type="button"], .ec_admin_expand_section > div.dashicons-arrow-down-alt2:before, .ec_admin_expand_section > div.dashicons-arrow-up-alt2:before, #ec_admin_add_new_advanced_option_row > input, #ec_admin_add_new_category_row > input, #ec_admin_add_new_price_tier_row > input[type="button"], #ec_admin_add_new_role_price_row > input[type="button"], .ec_admin_option_add_new_row input[type="button"], .ec_page_title_button:hover, .ec_admin_order_edit_button:hover, .ec_admin_settings_input > span.ec_admin_settings_simple_button, .ec_admin_settings_input > input.ec_admin_settings_simple_button, .ec_admin_settings_label > span.ec_admin_label_button > a, .ec_admin_settings_shipping_input > input, input.ec_admin_settings_simple_delete_button, .ec_admin_settings_simple_delete_button, .ec_admin_settings_live_rate_toggle div.dashicons-before, .ec_admin_settings_shipping_divider, .ec_admin_language_add > input.ec_admin_settings_simple_button, .ec_admin_language_input > input.ec_admin_settings_simple_button, .ec_admin_order_details_totals_edit, .ec_admin_order_details_line_item_save, .ec_admin_details_panel .wpec-admin-upload-button:hover{ background-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; border-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
    .ec_admin_products_submit > input.ec_admin_products_simple_button:hover, .ec_admin_settings_input > span.ec_admin_settings_simple_button:hover, .ec_admin_settings_input > input.ec_admin_settings_simple_button:hover, .ec_admin_settings_label > span.ec_admin_label_button > a:hover, .ec_admin_settings_shipping_input > input:hover, input.ec_admin_settings_simple_delete_button:hover, .ec_admin_settings_simple_delete_button:hover, .ec_admin_language_add > input.ec_admin_settings_simple_button:hover, .ec_admin_language_input > input.ec_admin_settings_simple_button:hover, .ec_admin_order_details_totals_edit:hover, .ec_admin_order_details_line_item_save:hover{ background-color:<?php echo esc_attr( wp_easycart_admin( )->adjust_hex_brightness( get_option( 'ec_option_admin_color' ), .4 ) ); ?>; }
    .ec_admin_settings_currency_section input[type="number"], .ec_admin_settings_currency_section input[type="text"], .ec_admin_settings_currency_section input[type="password"], .ec_admin_settings_currency_section input[type="date"], .ec_admin_settings_currency_section select, .ec_admin_settings_currency_section .select2, .ec_admin_settings_input select, .select2-container > .selection > .select2-selection, .ec_admin_settings_products_section input, input.ec_admin_taxcloud_field, select.ec_admin_taxcloud_field, .ec_admin_settings_tax_section input, .ec_admin_settings_tax_section select, .ec_admin_settings_live_payment_section input[type="text"], .ec_admin_settings_live_payment_section input[type="email"], .ec_admin_settings_live_payment_section input[type="password"], .ec_admin_settings_live_payment_section select, .ec_admin_live_rate_display, .ec_admin_settings_shipping_section input[type="text"], .ec_admin_settings_shipping_section input[type="number"], .ec_admin_settings_shipping_section select{ border-color:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; color:#333; }
    .wp-easycart-admin-onoffswitch .wp-easycart-admin-onoffswitch-inner:before, .wp-easycart-admin-toggle-group input[type=checkbox]:checked ~ .wp-easycart-admin-onoffswitch .wp-easycart-admin-onoffswitch-label{ background:<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>; }
</style>

<?php do_action( 'wp_easycart_admin_mobile_navigation' ); ?>

<?php do_action( 'wp_easycart_admin_upsell_popup' ); ?>

<div class="ec_admin_help_video_container">
    <div class="ec_admin_upsell_popup_close">
    	<a href="#" onclick="wp_easycart_admin_close_video_help( ); return false;"><div class="dashicons-before dashicons-dismiss"></div></a>
    </div>
    <div class="ec_admin_help_video_container_inner"><div id="wp_easycart_admin_help_video_player"></div></div>
</div>
<script>jQuery( '.ec_admin_help_video_container' ).prependTo( document.body );</script>

<div class="ec_admin_wrap">

	<div class="ec_admin_left ec_admin_default_color1-background-gradient ec_admin_default_color1-border-right">
    
    	<a href="http://www.wpeasycart.com" target="_blank" style="background:rgba(130,130,130,.4); display:block;"><div class="ec_admin_logo"></div></a>
        
        <div class="ec_admin_styled_divider" style="margin-top:0px;"></div>
        
        <div class="wp_easycart_view_store_main_link"><a href="<?php $storepageid = get_option( 'ec_option_storepage' ); $store_page = get_permalink( $storepageid ); echo esc_attr( $store_page ); ?>" target="_blank" class="ec_admin_default_color1-color"><?php esc_attr_e( 'View My Store', 'wp-easycart' ); ?></a></div>
        
        <div class="ec_admin_styled_divider"></div>
        
        <div class="ec_admin_search ec_admin_default_color2-background">
            <form method="GET" action="https://docs.wpeasycart.com/" target="_blank" class="ec_admin_default_color2-background" id="wpeasycart_search_form">
                <input class="ec_admin_default_color2-background" type="text" name="s" placeholder="<?php esc_attr_e( 'search docs...', 'wp-easycart' ); ?>" />
                <div class="ec_admin_search_icon dashicons-before dashicons-search"></div>
            </form>
        </div>
    
        <div class="ec_admin_styled_divider"></div>
        
        <?php if (get_option( 'ec_option_admin_display_sales_goal') == 1) { ?>
    
    	<div class="ec_admin_main_stats ec_admin_default_color1-background-gradient ec_admin_default_color2-border">
        
        	<div class="ec_admin_left_stats">
            
            	<div class="ec_admin_left_stats_first"><?php 
				if( class_exists( 'DateTime' ) ){
					$date = new DateTime;
					echo esc_attr( $date->format('M') );
				}else{
					echo esc_attr( date( 'M' ) );
				}?> <?php esc_attr_e( 'Sales', 'wp-easycart' ); ?></div>
            
            	<div class="ec_admin_left_stats_second"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->month_sales_total ) ); ?></div>
            
            </div>
            
            <div class="ec_admin_right_stats">
            
            	<div class="<?php if( $this->month_percentage_change >= 0 ){ echo "ec_admin_right_stats_arrow_positive"; }else{ echo "ec_admin_right_stats_arrow_negative"; } ?>"></div>
            
            	<div class="<?php if( $this->month_percentage_change >= 0 ){ echo "ec_admin_right_stats_percent_positive"; }else{ echo "ec_admin_right_stats_percent_negative"; } ?>"><?php if( $this->month_percentage_change > 0 ){ echo "+"; } echo esc_attr( number_format( (float) $this->month_percentage_change, 2, '.', '' ) ); ?>%</div>
            
            </div>
            
        </div>
            
        <div class="ec_admin_bottom_stats">
        
            <div class="ec_admin_bottom_stats_label_row">
            
                <div class="ec_admin_bottom_stats_label_row_right"><?php 
				if( class_exists( 'DateTime' ) ){
					$date = new DateTime;
					echo esc_attr( $date->format('M') );
				}else{
					echo esc_attr( date( 'M' ) );
				}?> <?php esc_attr_e( 'Sales', 'wp-easycart' ); ?></div>
            
                <div class="ec_admin_bottom_stats_label_row_left"><?php echo esc_attr( number_format( (float) $this->month_percentage_goal, 0, '', '' ) ); ?>% <?php esc_attr_e( 'of goal', 'wp-easycart' ); ?></div>
            
            </div>
            
            <div class="ec_admin_bottom_stats_bar">
            
                <div class="ec_admin_bottom_stats_bar_positive" style="width:<?php echo esc_attr( number_format( (float) $this->month_percentage_goal, 0, '', '' ) ); ?>%;"></div>
            
            </div>
        
        </div>
        
        
        <div class="ec_admin_styled_divider"></div>
        
         <?php } ?>
         
        <div class="ec_admin_left_navigation">
        
        	<?php do_action( 'wp_easycart_admin_left_navigation' ); ?>
        
        </div>
    
    </div>
    
    <div class="ec_admin_right ec_admin_default_color1-border-left ec_admin_default_color1-border-right">
    
    	<div class="ec_admin_head_navigation ec_admin_default_color1-background-gradient">
        
        	<?php do_action( 'wp_easycart_admin_head_navigation' ); ?>
            
            <div class="ec_admin_color_selector"><input value="<?php echo esc_attr( get_option( 'ec_option_admin_color' ) ); ?>" id="ec_admin_shell_color" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-admin-color' ) ); ?>" /></div>
        
        </div>
        
        <div class="ec_admin_content_area ec_admin_default_color1-border-left ec_admin_default_color1-border-right">
        
        	<div class="ec_admin_mobile_menu_button">
    
                <a href="#" onclick="ec_admin_open_mobile_menu( ); return false;"><div class="dashicons-before dashicons-menu"></div></a>
            
            </div>
            
            <?php do_action( 'wp_easycart_admin_messages' ); ?>
        
        	<?php do_action( 'wp_easycart_admin_shell_content' ); ?>
        
        </div>
    
    </div>
    
</div>