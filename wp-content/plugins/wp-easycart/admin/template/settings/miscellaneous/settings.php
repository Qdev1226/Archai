<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_miscellaneous_additional_options_loader" ); ?>
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_miscellaneous_settings_nonce', 'wp-easycart-miscellaneous-settings' ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Additional Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'additional-settings', 'additional-options' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'additional-settings', 'additional-options'); ?>
	</div>
	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section wp_easycart_admin_no_padding">

		<?php 
		$ids = explode( '***', get_option( 'ec_option_cart_menu_id' ) );
		$menus = get_registered_nav_menus();
		$menu_list = get_terms( 'nav_menu' );
		$keys = array_keys( $menus );
		$menu_options = array();
		foreach ( $keys as $key ) {
			$menu_options[] = (object) array(
				'value' => $key,
				'label' => $menus[$key],
			);
		}
		foreach ( $menu_list as $menu_item ) {
			$menu_options[] = (object) array(
				'value' => 'term_' . $menu_item->term_id,
				'label' => $menu_item->name,
			);
		}
		?>

		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_cart_menu_id', 'ec_admin_save_additional_text_options', $ids, __( 'Cart Icon Display', 'wp-easycart' ), __( 'Select one or many menus that you wish to display the cart icon', 'wp-easycart' ), $menu_options, 'ec_admin_ec_option_cart_menu_id_row', true, false, true ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_hide_cart_icon_on_empty', 'ec_admin_save_additional_options', get_option( 'ec_option_hide_cart_icon_on_empty' ), __( 'Hide Cart Icon for Empty Cart', 'wp-easycart' ), __( 'Enable to only show the icon when a user adds something to the cart.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_newsletter_popup', 'ec_admin_save_additional_options', get_option( 'ec_option_enable_newsletter_popup' ), __( 'Newsletter Popup', 'wp-easycart' ), __( 'Enable to have the newsletter signup show automatically.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_gateway_log', 'ec_admin_save_additional_options', get_option( 'ec_option_enable_gateway_log' ), __( 'Enable Gateway Log', 'wp-easycart' ), __( 'This is good to leave on all the time to handle errors if they do occur.', 'wp-easycart' ) ); ?>

		<div style="margin:-25px 65px 20px">
			<a href="admin.php?page=wp-easycart-settings&subpage=logs"><?php esc_attr_e( 'View Log', 'wp-easycart' ); ?></a>
			|  
			<a href="admin.php?page=wp-easycart-settings&subpage=miscellaneous&ec_admin_form_action=ec_delete_gateway_log"><?php esc_attr_e( 'Delete Log File', 'wp-easycart' ); ?></a>
		</div>


		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_inquiry_form', 'ec_admin_save_additional_options', get_option( 'ec_option_use_inquiry_form' ), __( 'Inquiry Submit POST Variables', 'wp-easycart' ), __( 'Enable this to submit the inquiry form instead of dynamically submitting.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_use_old_linking_style', 'ec_admin_save_additional_options', !get_option( 'ec_option_use_old_linking_style' ), __( 'Use SEO Friendly Links', 'wp-easycart' ), __( 'Enable to use custom WordPress posts and SEO friendly linking within your shopping system.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_deconetwork_allow_blank_products', 'ec_admin_save_additional_options', get_option( 'ec_option_deconetwork_allow_blank_products' ), __( 'DecoNetwork - Allow Blank Item Purchase', 'wp-easycart' ), __( 'This only applies to the DecoNetwork implementation.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_allow_tracking', 'ec_admin_save_additional_options', get_option( 'ec_option_allow_tracking' ), __( 'Send Anonymous Data', 'wp-easycart' ), __( 'Enable to allow EasyCart to track basic usage data from the shopping system.', 'wp-easycart' ) ); ?>

		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_abandoned_cart_days', 'ec_admin_save_additional_text_options', get_option( 'ec_option_abandoned_cart_days' ), __( 'Abandoned Cart Email', 'wp-easycart' ), __( 'This is the number of days to wait after a cart is abandoned to send the emailer.', 'wp-easycart' ), '1', 'ec_admin_ec_option_abandoned_cart_days_row', true, false ); ?>

		<?php do_action( 'wp_easycart_additional_settings_more_options' ); ?>

		<div>
			<?php esc_attr_e( 'Clear statistics for product views and menu clicks?', 'wp-easycart' ); ?> 
			<a href="#" onclick="return ec_admin_ajax_clear_stats( );"><?php esc_attr_e( 'Clear Stats', 'wp-easycart' ); ?></a>
		</div>

	</div>
</div>