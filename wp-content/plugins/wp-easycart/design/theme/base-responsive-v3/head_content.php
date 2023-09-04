<?php
$db = new ec_db( );
$page_options = $GLOBALS['ec_page_options']->page_options;

if( isset( $_GET['previewholder'] ) )
	$is_preview_holder = true;
else
	$is_preview_holder = false;

//----------------------//
// DISPLAY OPTIONS
//----------------------//

// DISPLAY TYPE SETUP
if( isset( $page_options->product_type ) )
	$product_type = $page_options->product_type;
else
	$product_type = get_option( 'ec_option_default_product_type' );
	
// DISPLAY QUICK VIEW SETUP
if( isset( $page_options->use_quickview ) )
	$quick_view = $page_options->use_quickview;
else
	$quick_view = get_option( 'ec_option_default_quick_view' );

// DISPLAY WIDTH SETUP
if( isset( $page_options->dynamic_image_sizing ) )  
	$dynamic_sizing = $page_options->dynamic_image_sizing;
else
	$dynamic_sizing = get_option( 'ec_option_default_dynamic_sizing' );

if( isset( $page_options->columns_smartphone ) )  
	$display_width_smartphone = (100/$page_options->columns_smartphone) . "%";
else if( get_option( 'ec_option_default_smartphone_columns' ) )
	$display_width_smartphone = (100/get_option( 'ec_option_default_smartphone_columns' ) ) . "%";
else
	$display_width_smartphone = (100/1) . "%";
	
if( isset( $page_options->columns_tablet ) )  
	$display_width_tablet = (100/$page_options->columns_tablet) . "%";
else if( get_option( 'ec_option_default_tablet_columns' ) )
	$display_width_tablet = (100/get_option( 'ec_option_default_tablet_columns' ) ) . "%";
else
	$display_width_tablet = (100/2) . "%";
	
if( isset( $page_options->columns_tablet_wide ) )  
	$display_width_tablet_wide = (100/$page_options->columns_tablet_wide) . "%";
else if( get_option( 'ec_option_default_tablet_wide_columns' ) )
	$display_width_tablet_wide = (100/get_option( 'ec_option_default_tablet_wide_columns' ) ) . "%";
else
	$display_width_tablet_wide = (100/2) . "%";
	
if( isset( $page_options->columns_laptop ) )  
	$display_width_laptop = (100/$page_options->columns_laptop) . "%";
else if( get_option( 'ec_option_default_laptop_columns' ) )
	$display_width_laptop = (100/get_option( 'ec_option_default_laptop_columns' ) ) . "%";
else
	$display_width_laptop = (100/3) . "%";
	
if( isset( $page_options->columns_desktop ) )  
	$display_width_desktop = (100/$page_options->columns_desktop ) . "%";
else if( get_option( 'ec_option_default_desktop_columns' ) )
	$display_width_desktop = (100/get_option( 'ec_option_default_desktop_columns' ) ) . "%";
else
	$display_width_desktop = (100/3) . "%";
	
// COLUMNS SETUP
if( isset( $page_options->columns_smartphone ) )  
	$columns_smartphone = $page_options->columns_smartphone;
else if( get_option( 'ec_option_default_smartphone_columns' ) )
	$columns_smartphone = get_option( 'ec_option_default_smartphone_columns' );
else
	$columns_smartphone = 1;
	
if( isset( $page_options->columns_tablet ) )  
	$columns_tablet = $page_options->columns_tablet;
else if( get_option( 'ec_option_default_tablet_columns' ) )
	$columns_tablet = get_option( 'ec_option_default_tablet_columns' );
else
	$columns_tablet = 2;
	
if( isset( $page_options->columns_tablet_wide ) )  
	$columns_tablet_wide = $page_options->columns_tablet_wide;
else if( get_option( 'ec_option_default_tablet_wide_columns' ) )
	$columns_tablet_wide = get_option( 'ec_option_default_tablet_wide_columns' );
else
	$columns_tablet_wide = 2;
	
if( isset( $page_options->columns_laptop ) )  
	$columns_laptop = $page_options->columns_laptop;
else if( get_option( 'ec_option_default_laptop_columns' ) )
	$columns_laptop = get_option( 'ec_option_default_laptop_columns' );
else
	$columns_laptop = 3;
	
if( isset( $page_options->columns_desktop ) )  
	$columns_desktop = $page_options->columns_desktop;
else if( get_option( 'ec_option_default_desktop_columns' ) )
	$columns_desktop = get_option( 'ec_option_default_desktop_columns' );
else
	$columns_desktop = 3;

// Image Height Setup
if( isset( $page_options->image_height_smartphone ) )
	$image_height_smartphone = str_replace( "px", "", $page_options->image_height_smartphone ) . "px";
else if( get_option( 'ec_option_default_smartphone_image_height' ) )
	$image_height_smartphone = str_replace( "px", "", get_option( 'ec_option_default_smartphone_image_height' ) ) . "px";
else
	$image_height_smartphone = '370px';
	
if( isset( $page_options->image_height_tablet ) )
	$image_height_tablet = str_replace( "px", "", $page_options->image_height_tablet ) . "px";
else if( get_option( 'ec_option_default_tablet_image_height' ) )
	$image_height_tablet = str_replace( "px", "", get_option( 'ec_option_default_tablet_image_height' ) ) . "px";
else
	$image_height_tablet = '380px';
	
if( isset( $page_options->image_height_tablet_wide ) )
	$image_height_tablet_wide = str_replace( "px", "", $page_options->image_height_tablet_wide ) . "px";
else if( get_option( 'ec_option_default_tablet_wide_image_height' ) )
	$image_height_tablet_wide = str_replace( "px", "", get_option( 'ec_option_default_tablet_wide_image_height' ) ) . "px";
else
	$image_height_tablet_wide = '310px';
	
if( isset( $page_options->image_height_laptop ) )
	$image_height_laptop = str_replace( "px", "", $page_options->image_height_laptop ) . "px";
else if( get_option( 'ec_option_default_laptop_image_height' ) )
	$image_height_laptop = str_replace( "px", "", get_option( 'ec_option_default_laptop_image_height' ) ) . "px";
else
	$image_height_laptop = '310px';
	
if( isset( $page_options->image_height_desktop ) )
	$image_height_desktop = str_replace( "px", "", $page_options->image_height_desktop ) . "px";
else if( get_option( 'ec_option_default_desktop_image_height' ) )
	$image_height_desktop = str_replace( "px", "", get_option( 'ec_option_default_desktop_image_height' ) ) . "px";
else
	$image_height_desktop = '310px';

// COLOR SETUP
if( get_option( 'ec_option_details_main_color' ) != '' )
	$color1 = get_option( 'ec_option_details_main_color' );
else
	$color1 = '#222222';
	
if( get_option( 'ec_option_details_second_color' ) != '' )
	$color2 = get_option( 'ec_option_details_second_color' );
else
	$color2 = '#666666';

// Product Details Light/Dark
if( get_option( 'ec_option_use_dark_bg' ) != '' ){
	$bg_theme_dark = get_option( 'ec_option_use_dark_bg' );
}else{
	$bg_theme_dark = 0;
}

// Product Details 1/2 Columns
if( get_option( 'ec_option_details_columns_desktop' ) != '' ){
	$details_columns_desktop = get_option( 'ec_option_details_columns_desktop' );
}else{
	$details_columns_desktop = 2;
}

if( get_option( 'ec_option_details_columns_laptop' ) != '' ){
	$details_columns_laptop = get_option( 'ec_option_details_columns_laptop' );
}else{
	$details_columns_laptop = 2;
}

if( get_option( 'ec_option_details_columns_tablet_wide' ) != '' ){
	$details_columns_tablet_wide = get_option( 'ec_option_details_columns_tablet_wide' );
}else{
	$details_columns_tablet_wide = 1;
}

if( get_option( 'ec_option_details_columns_tablet' ) != '' ){
	$details_columns_tablet = get_option( 'ec_option_details_columns_tablet' );
}else{
	$details_columns_tablet = 1;
}

if( get_option( 'ec_option_details_columns_smartphone' ) != '' ){
	$details_columns_smartphone = get_option( 'ec_option_details_columns_smartphone' );
}else{
	$details_columns_smartphone = 1;
}

// Cart 1/2 Columns
if( get_option( 'ec_option_cart_columns_desktop' ) != '' ){
	$cart_columns_desktop = get_option( 'ec_option_cart_columns_desktop' );
}else{
	$cart_columns_desktop = 2;
}

if( get_option( 'ec_option_cart_columns_laptop' ) != '' ){
	$cart_columns_laptop = get_option( 'ec_option_cart_columns_laptop' );
}else{
	$cart_columns_laptop = 2;
}

if( get_option( 'ec_option_cart_columns_tablet_wide' ) != '' ){
	$cart_columns_tablet_wide = get_option( 'ec_option_cart_columns_tablet_wide' );
}else{
	$cart_columns_tablet_wide = 1;
}

if( get_option( 'ec_option_cart_columns_tablet' ) != '' ){
	$cart_columns_tablet = get_option( 'ec_option_cart_columns_tablet' );
}else{
	$cart_columns_tablet = 1;
}

if( get_option( 'ec_option_cart_columns_smartphone' ) != '' ){
	$cart_columns_smartphone = get_option( 'ec_option_cart_columns_smartphone' );
}else{
	$cart_columns_smartphone = 1;
}

// DISPLAY OPTIONS //

// Check for Safari/Admin //
$ua = sanitize_text_field( $_SERVER["HTTP_USER_AGENT"] );
$safariorchrome = strpos($ua, 'Safari') ? true : false;
$chrome = strpos($ua, 'Chrome') ? true : false;
if( $safariorchrome && !$chrome )
	$safari = true;
else
	$safari = false;

$ipad = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPad' );

if( $is_preview_holder ){ ?>
<script>
jQuery( document ).ready( function( ){
	jQuery( '#ec_admin_preview_container' ).appendTo( document.body );
} );
</script>
<?php } ?>

<style>
<?php
if( get_option( 'ec_option_no_rounded_corners' ) ){
////////////////////////////////////////////////////////////////////////
// Remove corners
////////////////////////////////////////////////////////////////////////
?>
.ec_product_type1, .ec_product_type2, .ec_product_type3, .ec_product_type4, .ec_product_type5, .ec_product_type6, 
.ec_product_type1 > .ec_image_container_none, .ec_product_type1 > .ec_image_container_border, .ec_product_type1 > .ec_image_container_shadow, 
.ec_product_type1 .ec_product_addtocart a, .ec_product_type1 .ec_product_addtocart a:hover, .ec_product_type1 .ec_product_addtocart a:focus,
.ec_product_type1 .ec_product_addtocart, .ec_product_type4 .ec_product_addtocart, .ec_product_type6 .ec_product_meta_type6 .ec_product_addtocart, .ec_product_meta_type6 .ec_product_addtocart a.ec_added_to_cart_button,
.ec_price_container_type5, .ec_product_type6 .ec_product_meta_type6 .ec_price_container,

.ec_single_fade_container .ec_product_image_container, #ec_store_product_list img, 

.ec_details_main_image, .ec_details_magbox, .ec_details_thumbnail, .ec_details_swatches > li > img, .ec_details_swatches > li > a > img, 
.ec_details_add_to_cart_area > .ec_details_add_to_cart > input, .ec_details_add_to_cart_area > .ec_details_add_to_cart > a, .ec_details_quantity, 
.ec_details_add_to_cart, .ec_details_add_to_cart_area > .ec_details_quantity > .ec_minus, .ec_details_add_to_cart_area > .ec_details_quantity > .ec_plus, 
.ec_cartitem_quantity_table > tbody > tr > td > .ec_cartitem_update_button, .ec_cart_button_row > .ec_cart_button, 
.ec_cartitem_quantity_table > tbody > tr > td > .ec_minus, .ec_cartitem_quantity_table > tbody > tr > td > .ec_plus, 
.ec_cartitem_quantity_table > tbody > tr > td > .ec_minus:focus, .ec_cartitem_quantity_table > tbody > tr > td > .ec_plus:focus, 
.ec_cartitem_quantity_table > tbody > tr > td > .ec_minus:hover, .ec_cartitem_quantity_table > tbody > tr > td > .ec_plus:hover, 
.ec_cartitem_quantity_table > tbody > tr > td > .ec_quantity, .ec_cart_button_row > .ec_cart_button_working,

.ec_account_order_header_row, .ec_account_order_item_buy_button, .ec_account_order_item_download_button, .ec_account_dashboard_row_divider a, 
.ec_account_billing_information_button, .ec_account_shipping_information_button, .ec_account_personal_information_button, .ec_account_password_button,
.ec_cart_button_row > .ec_account_button, .ec_account_order_line_column5 a, .ec_out_of_stock_notify, .ec_out_of_stock_notify_title{ 
	border-top-right-radius:0px !important; border-top-left-radius:0px !important; border-bottom-left-radius:0px !important; border-bottom-right-radius:0px !important; border-radius:0px !important;
}
<?php
}
////////////////////////////////////////////////////////////////////////
// Font Change
////////////////////////////////////////////////////////////////////////
if( get_option( 'ec_option_font_main' ) ){ ?>
.ec_category_view_list *, .ec_product_page, #bc-status-container, .ec_tag4 > span, .ec_category_title_type, .ec_product_type1 .ec_product_addtocart, .ec_product_type1 .ec_out_of_stock, .ec_product_type1 .ec_seasonal_mode, .ec_product_type1 .ec_product_quickview > input, .ec_product_categories, .ec_product_title_type1, .ec_list_price_type1, .ec_price_type1, .ec_product_type2 .ec_product_addtocart, .ec_product_type2 .ec_out_of_stock, .ec_product_type2 .ec_seasonal_mode, .ec_product_type2 .ec_product_quickview > input, .ec_product_title_type2, .ec_list_price_type2, .ec_price_type2, .ec_product_type3 .ec_product_addtocart, .ec_product_type3 .ec_out_of_stock, .ec_product_type3 .ec_seasonal_mode, .ec_product_type3 .ec_product_quickview > input, .ec_product_title_type3, .ec_list_price_type3, .ec_price_type3, .ec_product_type4 .ec_product_addtocart, .ec_product_type4 .ec_out_of_stock, .ec_product_type4 .ec_seasonal_mode, .ec_product_type4 .ec_product_quickview > input, .ec_product_title_type4, .ec_list_price_type4, .ec_price_type4, .ec_product_type5 .ec_product_addtocart, .ec_product_type5 .ec_out_of_stock, .ec_product_type5 .ec_seasonal_mode, .ec_product_type5 .ec_product_quickview > input, .ec_product_title_type5, .ec_price_type5, .ec_product_type6 .ec_product_meta_type6 .ec_product_title, .ec_product_type6 .ec_product_meta_type6 .ec_price, .ec_product_type1 .ec_product_loader_container, .ec_product_type2 .ec_product_loader_container, .ec_product_type3 .ec_product_loader_container, .ec_product_type4 .ec_product_loader_container, .ec_product_type5 .ec_product_loader_container, .ec_product_type6 .ec_product_loader_container, .ec_product_type1 .ec_product_successfully_added_container, .ec_product_type2 .ec_product_successfully_added_container, .ec_product_type3 .ec_product_successfully_added_container, .ec_product_type4 .ec_product_successfully_added_container, .ec_product_type5 .ec_product_successfully_added_container, .ec_product_type6 .ec_product_successfully_added_container, .ec_product_quickview_trial_notice, .ec_product_quickview_content_title, .ec_product_quickview_content_price, .ec_product_quickview_content_description, .ec_product_quickview_price_tier, .ec_product_quickview_shipping_notice, .ec_product_quickview_content_add_to_cart_container > .ec_out_of_stock, .ec_quickview_view_details a, .ec_product_quickview_content_quantity input[type="submit"], .ec_product_quickview_content_quantity input[type="button"], .ec_product_quickview_content_quantity a, .ec_product_quickview_content_add_to_cart input[type="submit"], .ec_product_quickview_content_add_to_cart input[type="button"], .ec_product_quickview_content_add_to_cart a, .ec_product_quickview_close > input, .ec_product_page_sort .ec_product_page_perpage, .ec_product_page_sort .ec_product_page_perpage > a, .ec_product_page_sort .ec_product_page_showing, .ec_num_page, .ec_num_page_selected, .ec_product_added_to_cart, .ec_cart_checkout_link, .ec_details_breadcrumbs, .ec_details_breadcrumbs > a, .ec_details_title, .ec_details_reviews, .ec_details_price > .ec_product_price, .ec_details_price > .ec_product_old_price, .ec_details_price > .ec_product_sale_price, .ec_details_large_popup_close > input, .ec_details_model_number, .ec_details_description, .ec_details_stock_total, .ec_details_tiers, .ec_details_option_label, .ec_option_loading, .ec_details_swatches, .ec_details_html_swatches, .ec_details_option_row_error, .ec_details_option_data, .ec_details_final_price, .ec_details_add_to_cart_area > .ec_details_seasonal_mode, .ec_details_backorder_info, .ec_details_add_to_cart_area > .ec_out_of_stock, .ec_details_add_to_cart_area > .ec_details_add_to_cart > input, .ec_details_add_to_cart_area > .ec_details_add_to_cart > a, .ec_details_add_to_cart_area > .ec_details_quantity > .ec_quantity, .ec_details_min_purchase_quantity, .ec_details_handling_fee, .ec_details_categories, .ec_details_manufacturer, .ec_details_tab, .ec_details_edit_button > input, .ec_details_customer_reviews_left > h3, .ec_details_customer_review_list, .ec_details_customer_reviews_form > .ec_details_customer_reviews_form_holder > h3, .ec_details_customer_reviews_row, .ec_details_customer_reviews_row > input[type="button"], .ec_details_related_products_area > h3, .ec_details_related_products, .ec_restricted, .ec_details_inquiry_popup_main .ec_details_add_to_cart > .ec_out_of_stock, .ec_details_inquiry_popup_main .ec_details_add_to_cart > input, .ec_details_inquiry_popup_main .ec_details_add_to_cart > a, .ec_details_inquiry_popup_close > input, .ec_special_heading, .ec_special_iconbox_left > .ec_special_iconlist_content > h3, .ec_special_iconbox_left > .ec_special_iconlist_content > span, .ec_special_iconbox_top > .ec_special_iconlist_content > h3, .ec_special_iconbox_top > .ec_special_iconlist_content > span, .ec_special_iconlist_item > .ec_special_iconlist_content > h3, .ec_special_iconlist_item > .ec_special_iconlist_content > span, .ec_cart_empty, a.ec_cart_empty_button, .ec_cart_breadcrumb, .ec_cart_backorders_present, .ec_cart_backorder_date, .ec_cart > thead > tr > th, .ec_minimum_purchase_box p, tr.ec_cartitem_error_row > td, .ec_cartitem_title, td.ec_cartitem_details > dl > dt, td.ec_cartitem_price, td.ec_cartitem_quantity, .ec_cartitem_quantity_table > tbody > tr > td > .ec_quantity, .ec_cartitem_quantity_table > tbody > tr > td > .ec_cartitem_update_button, td.ec_cartitem_total, .ec_cart_header, .ec_cart_price_row_label, .ec_cart_price_row_total, .ec_cart_button_row > .ec_account_button, .ec_cart_button_row > .ec_cart_button, .ec_cart_button_row > .ec_cart_button_working, .ec_cart_input_row, .ec_cart_input_row label, .ec_cart_error_row, .ec_cart_error_message, .ec_cart_success, .ec_cart_success_message, .ec_cart_box_section strong, .ec_cart_option_row, .ec_cart_input_row > a, .ec_cart_input_row > b, .ec_cart_input_row > strong, .ec_account_order_details_item_display_title > a, .ec_cart_input_row > a:hover, .ec_account_order_details_item_display_title > a:hover, .ec_cart_error > div, .ec_cart_success_print_button > a, .ec_account_error, .ec_account_success, .ec_account_subheader, .ec_account_login_title, .ec_account_login_subtitle, .ec_account_login_row, .ec_account_login_row_label, .ec_account_login_row_input a, .ec_account_login_button, .ec_account_login_complete_subtitle, .ec_account_login_complete_logout_link, .ec_account_login_create_account_button, .ec_account_forgot_password_title, .ec_account_forgot_password_row, .ec_account_forgot_password_button, .ec_account_forgot_password_row_error, .ec_account_register_title, .ec_account_register_label, .ec_account_register_input, .ec_account_register_button, .ec_account_dashboard_title, .ec_account_order_header_column_left > span, .ec_account_order_header_column_left > div > a, .ec_account_order_item_details > span.ec_account_order_item_title, .ec_account_order_item_details > span.ec_account_order_item_price, .ec_account_order_item_buy_button > a, .ec_account_order_item_download_button, .ec_account_dashboard_holder, .ec_account_dashboard_right .ec_account_dashboard_holder a, .ec_account_dashboard_holder a, .ec_account_dashboard_row, .ec_account_dashboard_row_content, .ec_account_dashboard_row_bold, .ec_account_dashboard_row_bold b, .ec_account_dashboard_row_divider a, .ec_account_order_line_column1_header, .ec_account_order_line_column2_header, .ec_account_order_line_column3_header, .ec_account_order_line_column4_header, .ec_account_subscription_line_column1_header, .ec_account_subscription_line_column2_header, .ec_account_subscription_line_column3_header, .ec_account_subscription_line_column4_header, .ec_account_billing_information_title, .ec_account_shipping_information_title, .ec_account_billing_information_label, .ec_account_shipping_information_label, .ec_account_billing_information_input, .ec_account_shipping_information_input, .ec_account_billing_information_button, .ec_account_shipping_information_button, .ec_account_personal_information_button, .ec_account_password_button, .ec_account_personal_information_main_title, .ec_account_personal_information_main_sub_title, .ec_account_personal_information_label, .ec_account_personal_information_input, .ec_account_password_main_title, .ec_account_password_main_sub_title, .ec_account_password_label, .ec_account_password_input, .ec_account_password_error_text, .ec_account_orders_title, .ec_account_orders_holder a, .ec_account_order_line_column1, .ec_account_order_line_column2, .ec_account_order_line_column3, .ec_account_order_line_column4, .ec_account_order_line_column5.ec_account_order_line_column5 a, .ec_account_no_order_found, .ec_account_subscriptions_title, .ec_account_subscriptions_row > a, .ec_account_complete_order_link, .ec_account_order_details_title, .ec_account_order_details_table > thead > tr > th, tr.ec_account_orderitem_error_row > td, .ec_account_orderitem_title, td.ec_account_orderitem_details > dl > dt, td.ec_account_orderitem_price, td.ec_account_orderitem_quantity, td.ec_account_orderitem_total, .ec_account_subscription_title, .ec_account_subscription_row, .ec_account_subscription_link, .ec_account_subscriptions_past_payments a, .ec_account_subscription_row b, .ec_account_subscription_button input[type="submit"], .ec_account_subscription_line_column1, .ec_account_subscription_line_column2, .ec_account_subscription_line_column3, .ec_account_subscription_line_column4, .ec_account_subscription_line_column5 a, .widget.ec_cartwidget h4.widgettitle, .widget.ec_cartwidget widgettitle, .widget.ec_menuwidget h4.widgettitle, .ec_cartwidget, .ec_cart_widget_button, .ec_cart_widget_minicart_title, .ec_cart_widget_minicart_subtotal, .ec_cart_widget_minicart_checkout_button, .ec_cart_widget_minicart_product, .ec_cart_widget_minicart_product_title, .widget.ec_categorywidget h4.widgettitle, .widget.ec_categorywidget widgettitle, .ec_categorywidget, .ec_category_widget a, .widget.ec_donationwidget h4.widgettitle, .widget.ec_donationwidget widgettitle, .ec_donationwidget, .widget.ec_groupwidget h4.widgettitle, .widget.ec_groupwidget widgettitle, .ec_groupwidget, .ec_group_widget a, input[type="submit"].ec_login_widget_button, .widget.ec_manufacturerwidget h4.widgettitle, .widget.ec_manufacturerwidget widgettitle, .ec_manufacturerwidget, .ec_manufacturer_widget a, .ec_menu_horizontal, .ec_menu_vertical, ul.ec_menu_vertical, .widget.ec_newsletterwidget h4.widgettitle, .widget.ec_newsletterwidget widgettitle, .ec_newsletterwidget, .ec_newsletter_widget input[type="submit"], .widget.ec_pricepointwidget h4.widgettitle, .widget.ec_pricepointwidget widgettitle, .ec_pricepointwidget, .ec_pricepoint_widget a, .widget.ec_productwidget h4.widgettitle, .widget.ec_productwidget widgettitle, .ec_product_widget, .ec_product_widget_title, .ec_product_widget_title a, .ec_product_widget_pricing > .ec_product_old_price, .ec_product_widget_pricing > .ec_product_sale_price, .ec_product_widget_pricing > .ec_product_price, .widget.ec_searchwidget h4.widgettitle, .widget.ec_searchwidget widgettitle, .ec_searchwidget, .ec_search_widget input[type="submit"], .widget.ec_specialswidget h4.widgettitle, .widget.ec_specialswidget widgettitle, .ec_specialswidget, .ec_specialswidget .ec_product_title, .ec_specialswidget .ec_product_title a, .ec_specialswidget .ec_product_old_price, .ec_specialswidget .ec_product_sale_price, .ec_specialswidget .ec_product_price, .ec_newsletter_content input[type='submit'], .ec_newsletter_content h1, .ec_newsletter_content h3, .ec_newsletter_close > a, .ec_product_page_sort select, .ec_details_description_content *, .ec_cart_input_row input, .ec_cart_input_row select, .ec_cart_input_row textarea, .ec_cart_success_title, .ec_cart_success_subtitle, .ec_cart_success_order_number, .ec_cart_success_continue_shopping_button > a, .ec_cart_page a, .ec_restricted a, .ec_account_order_details_item_display_option, .ec_account_order_item_details > span, .ec_account_download_line > .ec_account_download_line_title, .ec_account_download_line > .ec_account_download_line_limit, .ec_account_download_line > .ec_account_download_line_time_limit, .ec_cart_button_row a.ec_account_login_link, .ec_cart_button_row, .ec_out_of_stock_notify, .ec_out_of_stock_notify_title, .ec_out_of_stock_notify_input > input, .ec_out_of_stock_notify_button > input, .ec_cart_login_header_link, .ec_cart_address_display, .ec_cart_address_display_line, .ec_cart_address_display_header, #ec_card_errors, .ec_cart_notice_row, #ec_user_login_link, #ec_user_login_link a, .ec_account_line_optionitem_pricing, .ec_details_price_label, .ec_cartitem_delete > span, .ec_cart_breadcrumb_item_v2, .ec_cart_bottom_nav_back, .ec_cart_create_account_row_v2 > a, .ec_cart_button_column div.ec_cart_button, .ec_cart_table_headers > div, .ec_cart_table_row > div, .ec_cart_table_column > div, .ec_cart_table_column > input, .ec_cart_table_subtotal_label, .ec_cart_table_subtotal_amount, .ec_cart_table_message, .ec_cart_table_checkout_button, .ec_cart_table_continue_shopping, .ec_cart_table .ec_cartitem_delete, .ec_cart_price_row_total_v2, .ec_cart_price_row_label_v2, .ec_cart_show_cart a, .ec_cart_locked_panel, .ec_cart_payment_table_column, .ec_cart_billing_table_column{ <?php echo ( get_option( 'ec_option_font_main' ) == 'custom' ) ? 'font-family:"' . esc_attr( get_option( 'ec_option_font_custom' ) ) . '", sans-serif !important;' : 'font-family:"' . esc_attr( get_option( 'ec_option_font_main' ) ) . '", sans-serif !important;'; ?> }
.<?php 
}
////////////////////////////////////////////////////////////////////////
?>
.ec_out_of_stock_notify{ border-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_out_of_stock_notify_title, .ec_out_of_stock_notify_button > input, #ec_user_login_link a{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_type1 .ec_product_addtocart{ background-color:<?php echo esc_attr( $color1 ); ?>; border-bottom-color:<?php echo esc_attr( $color2 ); ?>; }
.ec_product_type3 .ec_product_addtocart{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_price_container_type5{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_price_container_type5:after{ border-color: <?php echo esc_attr( $color2 ); ?> transparent transparent <?php echo esc_attr( $color2 ); ?>; }
.ec_product_type6 .ec_product_meta_type6 .ec_price_container{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_type6 .ec_product_meta_type6 .ec_price_container:after{ border-color:<?php echo esc_attr( $color2 ); ?> transparent transparent <?php echo esc_attr( $color2 ); ?>; }
.ec_product_type6 .ec_product_meta_type6 .ec_product_addtocart{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_product_type6 .ec_product_meta_type6 .ec_product_addtocart, .ec_product_meta_type6 .ec_product_addtocart a.ec_added_to_cart_button{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
@media(hover: hover) {
	.ec_out_of_stock_notify_button > input:hover, #ec_user_login_link a:hover{ background-color:<?php echo esc_attr( $color2 ); ?>; }
	.ec_product_type1 .ec_product_quickview > input:hover{ background:<?php echo esc_attr( $color1 ); ?>; background-color:<?php echo esc_attr( $color1 ); ?>; }
	.ec_product_type1 .ec_product_addtocart:hover{ background-color:<?php echo esc_attr( $color2 ); ?>; border-bottom-color:<?php echo esc_attr( $color1 ); ?>; }
	.ec_product_type3 .ec_product_addtocart:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
	.ec_product_type3 .ec_product_addtocart:hover{ background-color:<?php echo esc_attr( $color1 ); ?>; }
	.ec_product_type3 .ec_product_quickview > input:hover{ background:<?php echo esc_attr( $color1 ); ?>; background-color:<?php echo esc_attr( $color1 ); ?>; }
	.ec_product_type5 .ec_product_addtocart:hover{ background-color:<?php echo esc_attr( $color1 ); ?>; }
	.ec_product_type5 .ec_product_quickview > input:hover{ background:<?php echo esc_attr( $color1 ); ?>; background-color:<?php echo esc_attr( $color1 ); ?>; }
	.ec_product_type6 .ec_product_meta_type6 .ec_product_addtocart:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
	.ec_product_type6 .ec_product_meta_type6 .ec_product_addtocart:hover, .ec_product_meta_type6 .ec_product_addtocart a.ec_added_to_cart_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
	.ec_product_type6 .ec_product_meta_type6 .ec_product_quickview > input:hover{ background-color:<?php echo esc_attr( $color1 ); ?>; }
}
.ec_product_quickview_content_title, .ec_product_quickview_content_title > a{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_product_quickview_content_title:hover, .ec_product_quickview_content_title > a:hover{ color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_product_quickview_content_quantity input[type="submit"], .ec_product_quickview_content_quantity input[type="button"], .ec_product_quickview_content_add_to_cart a{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_product_quickview_content_quantity input[type="submit"]:hover, .ec_product_quickview_content_quantity input[type="button"]:hover, .ec_product_quickview_content_add_to_cart a:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_product_quickview_content_quantity .ec_minus, .ec_product_quickview_content_quantity .ec_plus{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_quickview_content_quantity .ec_minus:hover, .ec_product_quickview_content_quantity .ec_plus:hover{ background-color:<?php echo esc_attr( $color2 ); ?>; }
.ec_quickview_view_details a{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_quickview_view_details a:hover{ color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_product_page_sort > .ec_product_page_showing{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_star_on{ border-bottom-color:<?php echo esc_attr( $color1 ); ?> !important; color:<?php echo esc_attr( $color1 ); ?>; border-bottom-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_star_on:before{ border-bottom-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_star_on:after{ color:<?php echo esc_attr( $color1 ); ?>; border-bottom-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_star_off{ border-bottom-color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?> !important; color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; }
.ec_product_star_off:before{ border-bottom-color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; }
.ec_product_star_off:after{ color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; border-bottom-color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; }
.ec_product_added_to_cart a, .ec_cart_checkout_link{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_product_added_to_cart a:hover, .ec_cart_checkout_link:hover{ color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_product_details_page a{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_details_page a:hover{ color:<?php echo esc_attr( $color2 ); ?>; }
.ec_details_title{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#222222<?php }?> !important; }
.ec_details_price > .ec_product_price{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#000000<?php }?>; }
.ec_details_price > .ec_product_sale_price{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#000000<?php }?>; }
.ec_details_images{ width:<?php if( $details_columns_desktop == 1 ){ ?>100%<?php } ?>; }
.ec_details_magbox{ display:none<?php if( $details_columns_desktop == 1 ){?> !important<?php }?>; }
.ec_details_right{ <?php if( $details_columns_desktop == 1 ){ ?>width:100%; margin-left:0;<?php } ?> }
.ec_details_model_number{ color:<?php if( $bg_theme_dark ){ ?>#CCCCCC<?php }else{?>#666666<?php }?> !important; }
.ec_details_description{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#222222<?php }?> !important; }
.ec_details_stock_total{ color:<?php if( $bg_theme_dark ){ ?>#CCCCCC<?php }else{?>#666666<?php }?> !important; }
.ec_details_add_to_cart_area > .ec_details_quantity > .ec_minus, .ec_details_add_to_cart_area > .ec_details_quantity > .ec_plus, .ec_details_add_to_cart_area > .ec_details_add_to_cart > input, .ec_details_add_to_cart_area > .ec_details_add_to_cart > a, .ec_details_customer_reviews_row > input[type="button"], .ec_details_inquiry_popup_main > form > .ec_details_add_to_cart input, .ec_details_inquiry_popup_main > form > .ec_details_add_to_cart > a, .wpeasycart-html-swatch{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_details_add_to_cart_area > .ec_details_quantity > .ec_minus:hover, .ec_details_add_to_cart_area > .ec_details_quantity > .ec_plus:hover, .ec_details_add_to_cart_area > .ec_details_add_to_cart > input:hover, .ec_details_add_to_cart_area > .ec_details_add_to_cart > a:hover, .ec_details_customer_reviews_row > input[type="button"]:hover, .ec_details_inquiry_popup_main > form > .ec_details_add_to_cart > input:hover, .ec_details_inquiry_popup_main > form > .ec_details_add_to_cart > a:hover, .wpeasycart-html-swatch.ec_selected, .wpeasycart-html-swatch:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_details_categories{ color:<?php if( $bg_theme_dark ){ ?>#CCCCCC<?php }else{?>#666666<?php }?> !important; }
.ec_details_manufacturer{ color:<?php if( $bg_theme_dark ){ ?>#CCCCCC<?php }else{?>#666666<?php }?> !important; }
.ec_details_tabs{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#222222<?php }?>; }
.ec_details_tab.ec_active{ border-top-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_details_customer_reviews_left > h3{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_details_customer_review_date{ color:<?php if( $bg_theme_dark ){ ?>#CCCCCC<?php }else{?>#666666<?php }?>; }
.ec_details_customer_review_date > strong{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#222222<?php }?>; }
.ec_details_customer_review_data{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#222222<?php }?>; }
.ec_details_customer_reviews_form > .ec_details_customer_reviews_form_holder > h3{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_details_customer_reviews_row{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{?>#222222<?php }?>; }
.ec_details_customer_reviews_row > input[type="button"]{ background-color:<?php if( $bg_theme_dark ){ ?>#AAAAAA<?php }else{?>#333<?php }?>; color:<?php if( $bg_theme_dark ){ ?>#000000<?php }else{?>#FFFFFF<?php }?>; }
.ec_details_customer_reviews_row > input[type="button"]:hover{ background-color:<?php if( $bg_theme_dark ){ ?>#CCCCCC<?php }else{?>#333333<?php }?>; }
.ec_details_related_products_area > h3{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_product_details_star_on{ border-bottom-color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{ echo esc_attr( $color1 ); }?> !important; color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{ echo esc_attr( $color1 ); }?>; border-bottom-color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{ echo esc_attr( $color1 ); }?>; }
.ec_product_details_star_on:before{ border-bottom-color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{ echo esc_attr( $color1 ); }?>; }
.ec_product_details_star_on:after{ color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{ echo esc_attr( $color1 ); }?>; border-bottom-color:<?php if( $bg_theme_dark ){ ?>#FFFFFF<?php }else{ echo esc_attr( $color1 ); }?>; }
.ec_product_details_star_off{ border-bottom-color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?> !important; color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; }
.ec_product_details_star_off:before{ border-bottom-color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; }
.ec_product_details_star_off:after{ color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; border-bottom-color:<?php if( $bg_theme_dark ){ ?>#666666<?php }else{?>#CCCCCC<?php }?>; }
<?php if( $bg_theme_dark ){ ?>
.ec_details_option_label{ color:#FFFFFF; }
<?php }?>
.ec_details_swatches > li.ec_selected > img{ border:2px solid <?php echo esc_attr( $color1 ); ?>; }
.ec_special_heading{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_special_icon, .ec_special_icon_list{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cart_page a, .ec_restricted a{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cart_page a:hover, .ec_restricted a:hover{ color:<?php echo esc_attr( $color2 ); ?>; }
a.ec_cart_empty_button{ background-color:<?php echo esc_attr( $color1 ); ?> }
a.ec_cart_empty_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> }
.ec_cart_breadcrumb{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cart_table_continue_shopping{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cart_table_continue_shopping:hover{ color:<?php echo esc_attr( $color2 ); ?>; }
.ec_cart_button_column > .ec_cart_button, .ec_cart_table_checkout_button{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cart_button_column > .ec_cart_button:hover, .ec_cart_table_checkout_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_cart_breadcrumb_item_v2, .ec_cart_bottom_nav_back, .ec_cart_create_account_row_v2 > a, .ec_account_login_cancel_link{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cart_breadcrumb_item_v2:hover, .ec_cart_bottom_nav_back:hover, .ec_cart_create_account_row_v2 > a:hover, .ec_account_login_cancel_link:hover{ color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_cart > thead > tr{ border-bottom-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cartitem_title{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cartitem_quantity_table > tbody > tr > td > .ec_minus, .ec_cartitem_quantity_table > tbody > tr > td > .ec_plus, .ec_cartitem_quantity_table > tbody > tr > td > .ec_cartitem_update_button{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cartitem_quantity_table > tbody > tr > td > .ec_minus:hover, .ec_cartitem_quantity_table > tbody > tr > td > .ec_plus:hover, .ec_cartitem_quantity_table > tbody > tr > td > .ec_cartitem_update_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_cart_button_row > .ec_account_button{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cart_button_row > .ec_account_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_cart_button_row > .ec_cart_button, .ec_cart_button_row input[type="button"], .ec_cart_button_row a.ec_cart_button{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cart_button_row > .ec_cart_button:hover, .ec_cart_button_row input[type="button"]:hover, .ec_cart_button_row a.ec_cart_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> !important; }
.ec_cart_button_row a.ec_account_login_link{ background:none !important; background-color:transparent !important; color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cart_button_row a.ec_account_login_link:hover{ background:none !important; background-color:transparent !important; color:<?php echo esc_attr( $color2 ); ?>; }
.ec_cart_input_row > a, .ec_cart_input_row > b, .ec_cart_input_row > strong, .ec_account_order_details_item_display_title > a{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_cart_input_row > a:hover, .ec_account_order_details_item_display_title > a:hover{ color:<?php echo esc_attr( $color2 ); ?>; }
<?php if( $bg_theme_dark ){ ?>
.ec_cart_header, .ec_cart_price_row, .ec_cart_price_row_label, .ec_cart_price_row_total, .ec_cart_input_row label, .ec_cart_input_row{ color:#FFF; }
.ec_details_breadcrumbs.ec_small, .ec_details_breadcrumbs.ec_small > a{ color:#FFFFFF !important; }
.ec_details_breadcrumbs, .ec_details_breadcrumbs > a{ color:#FFFFFF !important; }
.ec_cart_shipping_method_row > span{ color:#FFFFFF !important; }
.ec_cart_shipping_method_row > span.price{ color:#FFFFFF !important; }
<?php }?>
ul.ec_menu_vertical li a:hover{ background-color:<?php echo esc_attr( $color1 ); ?>; }
ul.ec_menu_vertical ul li a:hover, ul.ec_menu_vertical ul ul li a:hover, .ec_categorywidget a:hover, .ec_manufacturerwidget a:hover, .ec_pricepointwidget a:hover, .ec_groupwidget a:hover, .ec_product_widget_title a:hover{ color:<?php echo esc_attr( $color1 ); ?> !important; }

.ec_search_widget input[type="submit"], .ec_newsletter_widget input[type="submit"], input[type="submit"].ec_login_widget_button{ background-color:<?php echo esc_attr( $color1 ); ?>; border-bottom:4px solid <?php echo esc_attr( $color2 ); ?>; }
.ec_search_widget input[type="submit"]:hover, .ec_newsletter_widget input[type="submit"]:hover, input[type="submit"].ec_login_widget_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?>; border-bottom:4px solid <?php echo esc_attr( $color1 ); ?>; }

.ec_cart_widget_minicart_wrap{ background:<?php echo esc_attr( $color1 ); ?>; }

.ec_categorywidget a, .ec_manufacturer_widget a, .ec_pricepoint_widget a, .ec_group_widget a, .ec_cartwidget a{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_categorywidget a:hover, .ec_manufacturer_widget a:hover, .ec_pricepoint_widget a:hover, .ec_group_widget a:hover, .ec_cartwidget a:hover{ color:<?php echo esc_attr( $color2 ); ?> !important; }

.ec_newsletter_content h1, .ec_newsletter_close{ color:<?php echo esc_attr( $color1 ); ?>; }
.ec_newsletter_content input[type='submit']{ background-color:<?php echo esc_attr( $color1 ); ?>;}
.ec_newsletter_content input[type='submit']:hover{ background-color:<?php echo esc_attr( $color2 ); ?>; }

.ec_account_order_item_buy_button, .ec_account_order_item_download_button{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_account_order_item_buy_button:hover, .ec_account_order_item_download_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?>; }
.ec_account_dashboard_row_divider a, .ec_account_order_line_column5 a, .ec_account_complete_payment_button{ background-color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_account_dashboard_row_divider a:hover, .ec_account_order_line_column5 a:hover, .ec_account_complete_payment_button:hover{ background:<?php echo esc_attr( $color2 ); ?> !important; background-color:<?php echo esc_attr( $color2 ); ?> !important; }

.ec_store_table a{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_store_table a:hover{ color:<?php echo esc_attr( $color2 ); ?> !important; }

.ec_cart_success_title{ color:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cart_success_continue_shopping_button > a{ background:<?php echo esc_attr( $color1 ); ?> !important; }
.ec_cart_success_continue_shopping_button > a:hover{ background:<?php echo esc_attr( $color2 ); ?> !important; }

@media only screen and ( min-width:1140px ){
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_desktop ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_desktop ); ?>; height:<?php echo esc_attr( $image_height_desktop ); ?>; }
	#ec_current_media_size{ max-width:1300px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_desktop ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_desktop ); ?>; height:<?php echo esc_attr( $image_height_desktop ); ?>; }
	#ec_current_media_size{ max-width:1300px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ) .ec_product_editor{ left:227px; }
	<?php if( $details_columns_desktop == 1 ){ ?>
	.ec_details_mobile_title_area{ display:block; }
	.ec_details_images, .ec_details_right, .ec_details_customer_reviews_left, .ec_details_customer_reviews_form, .ec_details_customer_review_date{ float:left; margin-left:0px; width:100%; }
	.ec_details_right{ padding-left:0px; }
	.ec_details_right > form > .ec_details_breadcrumbs.ec_small, .ec_details_right > form > .ec_details_title, .ec_details_right > form > .ec_title_divider, .ec_details_right > form > .ec_details_price, .ec_details_right > form > .ec_details_rating{ display:none; }
	.ec_details_customer_review_list{ width:100%; }
	<?php }?>
	
	.ec_category_li{ width:<?php echo esc_attr( $display_width_desktop ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ){ clear:both; }
	.ec_category_li{ width:<?php echo esc_attr( $display_width_desktop ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ){ clear:both; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	<?php if( $cart_columns_desktop == 1 ){ ?>
	.ec_cart_breadcrumb.ec_inactive, .ec_cart_breadcrumb_divider{ display:none; }
	.ec_cart_breadcrumb{ width:100%; text-align:center; font-size:22px; }
	.ec_cart_left{ width:100%; padding-right:0px; border-right:0px; }
	.ec_cart_right{ width:100%; padding-left:0px; }
	.ec_cart_right > .ec_cart_header.ec_top{ margin-top:15px; }
	.ec_show_two_column_only{ display:none !important; }
	#ec_cart_payment_one_column{ display:block; }
	#ec_cart_payment_hide_column{ display:none; }
	<?php }?>
}

@media only screen and ( min-width:990px ) and ( max-width:1139px ){
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_laptop ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_laptop ); ?>; height:<?php echo esc_attr( $image_height_laptop ); ?>; }
	#ec_current_media_size{ max-width:1139px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_laptop ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_laptop ); ?>; height:<?php echo esc_attr( $image_height_laptop ); ?>; }
	#ec_current_media_size{ max-width:1139px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ) .ec_product_editor{ left:227px; }
	<?php if( $details_columns_laptop == 1 ){ ?>
	.ec_details_magbox{ display:none !important }
	.ec_details_mobile_title_area{ display:block; }
	.ec_details_images, .ec_details_right, .ec_details_customer_reviews_left, .ec_details_customer_reviews_form, .ec_details_customer_review_date{ float:left; margin-left:0px; width:100%; }
	.ec_details_right{ padding-left:0px; }
	.ec_details_right > form > .ec_details_breadcrumbs.ec_small, .ec_details_right > form > .ec_details_title, .ec_details_right > form > .ec_title_divider, .ec_details_right > form > .ec_details_price, .ec_details_right > form > .ec_details_rating{ display:none; }
	.ec_details_customer_review_list{ width:100%; }
	<?php }?>
	
	.ec_category_li{ width:<?php echo esc_attr( $display_width_laptop ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ){ clear:both; }
	.ec_category_li{ width:<?php echo esc_attr( $display_width_laptop ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ){ clear:both; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_laptop ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	<?php if( $cart_columns_laptop == 1 ){ ?>
	.ec_cart_breadcrumb.ec_inactive, .ec_cart_breadcrumb_divider{ display:none; }
	.ec_cart_breadcrumb{ width:100%; text-align:center; font-size:22px; }
	.ec_cart_left{ width:100%; padding-right:0px; border-right:0px; }
	.ec_cart_right{ width:100%; padding-left:0px; }
	.ec_cart_right > .ec_cart_header.ec_top{ margin-top:15px; }
	.ec_show_two_column_only{ display:none !important; }
	#ec_cart_payment_one_column{ display:block; }
	#ec_cart_payment_hide_column{ display:none; }
	<?php }?>
}

@media only screen and ( min-width:768px ) and ( max-width:989px ) {
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_tablet_wide ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_tablet_wide ); ?>; height:<?php echo esc_attr( $image_height_tablet_wide ); ?>; }
	#ec_current_media_size{ max-width:989px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_tablet_wide ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_tablet_wide ); ?>; height:<?php echo esc_attr( $image_height_tablet_wide ); ?>; }
	#ec_current_media_size{ max-width:989px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ) .ec_product_editor{ left:227px; }
	<?php if( $details_columns_tablet_wide == 1 ){ ?>
	.ec_details_magbox{ display:none !important }
	.ec_details_mobile_title_area{ display:block; }
	.ec_details_images, .ec_details_right, .ec_details_customer_reviews_left, .ec_details_customer_reviews_form, .ec_details_customer_review_date{ float:left; margin-left:0px; width:100%; }
	.ec_details_right{ padding-left:0px; }
	.ec_details_right > form > .ec_details_breadcrumbs.ec_small, .ec_details_right > form > .ec_details_title, .ec_details_right > form > .ec_title_divider, .ec_details_right > form > .ec_details_price, .ec_details_right > form > .ec_details_rating{ display:none; }
	.ec_details_customer_review_list{ width:100%; }
	<?php }?>
	
	.ec_category_li{ width:<?php echo esc_attr( $display_width_tablet_wide ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ){ clear:both; }
	.ec_category_li{ width:<?php echo esc_attr( $display_width_tablet_wide ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ){ clear:both; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_tablet_wide ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	<?php if( $cart_columns_tablet_wide == 1 ){ ?>
	.ec_cart_breadcrumb.ec_inactive, .ec_cart_breadcrumb_divider{ display:none; }
	.ec_cart_breadcrumb{ width:100%; text-align:center; font-size:22px; }
	.ec_cart_left{ width:100%; padding-right:0px; border-right:0px; }
	.ec_cart_right{ width:100%; padding-left:0px; }
	.ec_cart_right > .ec_cart_header.ec_top{ margin-top:15px; }
	.ec_show_two_column_only{ display:none !important; }
	#ec_cart_payment_one_column{ display:block; }
	#ec_cart_payment_hide_column{ display:none; }
	<?php }?>
}

@media only screen and ( min-width:481px ) and ( max-width:767px ){
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_tablet ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_tablet ); ?>; height:<?php echo esc_attr( $image_height_tablet ); ?>; }
	#ec_current_media_size{ max-width:767px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_tablet ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_tablet ); ?>; height:<?php echo esc_attr( $image_height_tablet ); ?>; }
	#ec_current_media_size{ max-width:767px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ) .ec_product_editor{ left:227px; }
	<?php if( $details_columns_tablet == 1 ){ ?>
	.ec_details_magbox{ display:none !important }
	.ec_details_mobile_title_area{ display:block; }
	.ec_details_images, .ec_details_right, .ec_details_customer_reviews_left, .ec_details_customer_reviews_form, .ec_details_customer_review_date{ float:left; margin-left:0px; width:100%; }
	.ec_details_right{ padding-left:0px; }
	.ec_details_right > form > .ec_details_breadcrumbs.ec_small, .ec_details_right > form > .ec_details_title, .ec_details_right > form > .ec_title_divider, .ec_details_right > form > .ec_details_price, .ec_details_right > form > .ec_details_rating{ display:none; }
	.ec_details_customer_review_list{ width:100%; }
	<?php }?>
	
	.ec_category_li{ width:<?php echo esc_attr( $display_width_tablet ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ){ clear:both; }
	.ec_category_li{ width:<?php echo esc_attr( $display_width_tablet ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ){ clear:both; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_tablet ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	<?php if( $cart_columns_tablet == 1 ){ ?>
	.ec_cart_left{ width:100%; padding-right:0px; border-right:0px; }
	.ec_cart_right{ width:100%; padding-left:0px; }
	.ec_cart_right > .ec_cart_header.ec_top{ margin-top:15px; }
	.ec_show_two_column_only{ display:none !important; }
	#ec_cart_payment_one_column{ display:block; }
	#ec_cart_payment_hide_column{ display:none; }
	<?php }?>
}

@media only screen and ( max-width:480px ){
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_smartphone ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_smartphone ); ?>; height:<?php echo esc_attr( $image_height_smartphone ); ?>; }
	#ec_current_media_size{ max-width:480px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	.ec_product_page .ec_product_li, .ec_product_page li.ec_product_li{ width:<?php echo esc_attr( $display_width_smartphone ); ?>; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ){ clear:both; }
	.ec_image_container_none, .ec_image_container_none > div, .ec_image_container_border, .ec_image_container_border > div, .ec_image_container_shadow, .ec_image_container_shadow > div{ min-height:<?php echo esc_attr( $image_height_smartphone ); ?>; height:<?php echo esc_attr( $image_height_smartphone ); ?>; }
	#ec_current_media_size{ max-width:480px; }
	.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ) .ec_product_editor{ left:227px; }
	.ec_product_quickview_content_data{ padding:15px; }
	.ec_product_page_sort > .ec_product_page_showing{ margin:0; }
	.ec_product_page_sort > select{ float:left; }
	.ec_product_type6 .ec_image_container_none, .ec_product_type6 .ec_image_container_border, .ec_product_type6 .ec_image_container_shadow{ width:100%; max-width:100%; }
	.ec_product_type6 .ec_product_meta_type6{ position:relative; width:100%; max-width:100%; margin-left:0; float:none; padding:15px; }
	.ec_product_type6 .ec_product_meta_type6 .ec_product_addtocart_container{ float:none; }
	
	.ec_details_swatches{ float:left; width:100%; }
	.ec_details_option_label{ border-right:none; }
	
	.ec_category_li{ width:<?php echo esc_attr( $display_width_smartphone ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ){ clear:both; }
	.ec_category_li{ width:<?php echo esc_attr( $display_width_smartphone ); ?>; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ){ clear:both; }
	.ec_category_li:nth-child( <?php echo esc_attr( $columns_smartphone ); ?>n+1 ) .ec_product_editor{ left:227px; }
	
	<?php if( $details_columns_smartphone == 1 ){ ?>
	.ec_details_magbox{ display:none !important }
	.ec_details_mobile_title_area{ display:block; }
	.ec_details_images, .ec_details_right, .ec_details_customer_reviews_left, .ec_details_customer_reviews_form, .ec_details_customer_review_date{ float:left; margin-left:0px; width:100%; }
	.ec_details_right{ padding-left:0px; }
	.ec_details_right > form > .ec_details_breadcrumbs.ec_small, .ec_details_right > form > .ec_details_title, .ec_details_right > form > .ec_title_divider, .ec_details_right > form > .ec_details_price, .ec_details_right > form > .ec_details_rating{ display:none; }
	.ec_details_customer_review_list{ width:100%; }
	<?php }?>
	
	<?php if( $cart_columns_smartphone == 1 ){ ?>
	.ec_cart_left{ width:100%; padding-right:0px; border-right:0px; }
	.ec_cart_right{ width:100%; padding-left:0px; }
	.ec_cart_right > .ec_cart_header.ec_top{ margin-top:15px; }
	.ec_show_two_column_only{ display:none !important; }
	#ec_cart_payment_one_column{ display:block; }
	#ec_cart_payment_hide_column{ display:none; }
	<?php }?>
}

<?php if( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ){ ?>

<?php if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ipad_landscape.png" ) ){ ?>
.ec_admin_preview_ipad_landscape > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ipad_landscape.png", EC_PLUGIN_DATA_DIRECTORY ) ); ?>' ) no-repeat; }
<?php }else{ ?>
.ec_admin_preview_ipad_landscape > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ipad_landscape.png", EC_PLUGIN_DIRECTORY ) ); ?>' ) no-repeat; }
<?php }?>

<?php if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ipad_portrait.png" ) ){ ?>
.ec_admin_preview_ipad_portrait > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ipad_portrait.png", EC_PLUGIN_DATA_DIRECTORY ) ); ?>') no-repeat; }
<?php }else{ ?>
.ec_admin_preview_ipad_portrait > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ipad_portrait.png", EC_PLUGIN_DIRECTORY ) ); ?>' ) no-repeat; }
<?php }?>

<?php if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/iphone_landscape.png" ) ){ ?>
.ec_admin_preview_iphone_landscape > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/iphone_landscape.png", EC_PLUGIN_DATA_DIRECTORY ) ); ?>') no-repeat; }
<?php }else{ ?>
.ec_admin_preview_iphone_landscape > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/iphone_landscape.png", EC_PLUGIN_DIRECTORY ) ); ?>' ) no-repeat; }
<?php }?>

<?php if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/iphone_portrait.png" ) ){ ?>
.ec_admin_preview_iphone_portrait > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/iphone_portrait.png", EC_PLUGIN_DATA_DIRECTORY ) ); ?>') no-repeat; }
<?php }else{ ?>
.ec_admin_preview_iphone_portrait > input{ background:url( '<?php echo esc_url( plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/iphone_portrait.png", EC_PLUGIN_DIRECTORY ) ); ?>' ) no-repeat; }
<?php }?>

.ec_admin_preview_ipad_landscape > input:hover, .ec_admin_preview_ipad_portrait > input:hover, .ec_admin_preview_iphone_landscape > input:hover, .ec_admin_preview_iphone_portrait > input:hover{ background-color:#FFF; }

.ec_product_page .ec_product_li:nth-child( <?php echo esc_attr( $columns_desktop ); ?>n+1 ) .ec_product_editor{ left:227px; }
.ec_product_admin_reorder_button{ background-color:<?php echo esc_attr( $color1 ); ?>; }
.ec_product_admin_reorder_button:hover{ background-color:<?php echo esc_attr( $color2 ); ?> }
.ec_products_sortable_padding > ul{ width:<?php echo esc_attr( $columns_desktop * 220 ); ?>px; }
<?php }?>

<?php  echo htmlspecialchars_decode( wp_easycart_escape_html( get_option( 'ec_option_custom_css' ) ) ); ?>
</style>