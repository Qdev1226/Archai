<?php
do_action( 'wpeasycart_admin_load_init' );
// Load Helper Classes 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_verification.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_account.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_actions.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_category.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_checkout.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_design.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_downloads.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_third_party.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_logging.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_initial_setup.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_live_shipping_rates.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_setup_wizard.php' );

include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_inventory.php' );
	
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_manufacturers.php' );

include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_pricepoint.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_perpage.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_country.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_states.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_giftcards.php' );

if( isset( $_GET['page'] ) && isset( $_GET['subpage'] ) && $_GET['page'] == 'wp-easycart-products' && ( $_GET['subpage'] == 'menus' || $_GET['subpage'] == 'submenus' || $_GET['subpage'] == 'subsubmenus' ) )
	include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_menus.php' );

include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_miscellaneous.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_option.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_orders.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_payments.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_preloader.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_products.php' );

if( isset( $_GET['page'] ) && isset( $_GET['subpage'] ) && $_GET['page'] == 'wp-easycart-products' && $_GET['subpage'] == 'reviews' )
	include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_reviews.php' );

include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_shipping.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_subscriptions.php' );

if( isset( $_GET['page'] ) && isset( $_GET['subpage'] ) && $_GET['page'] == 'wp-easycart-products' && $_GET['subpage'] == 'subscriptionplans' )
	include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_subscription_plans.php' );

include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_table.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_taxes.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_users.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_user_role.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_subscribers.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_email_settings.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_language_editor.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_cart_importer.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_online_docs.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_abandon_cart.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_store_status.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_registration.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_coupons.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_promotions.php' ); 
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_extensions.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_shortcodes.php' );
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_fee.php' );

include( EC_PLUGIN_DIRECTORY . '/admin/gutenberg/class-wp-easycart-gutenberg.php' );

if( get_option( 'ec_option_allow_tracking' ) && get_option( 'ec_option_allow_tracking' ) == '1' ){
	include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_tracking.php' );
}

// Load Main Files Last
include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin.php' );
//include( EC_PLUGIN_DIRECTORY . '/inc/admin/admin_ajax_functions.php' );
do_action( 'wpeasycart_admin_load_complete' );
?>