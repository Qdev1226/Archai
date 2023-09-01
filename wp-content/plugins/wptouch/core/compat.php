<?php
/**
 * This file contains compatibility related functions.
 */

add_filter( 'wptouch_should_init_pro', 'wptouch_check_for_initialization' );

// ManageWP
add_filter( 'mwp_premium_update_notification', 'wptouch_mwp_update_notification' );
add_filter( 'mwp_premium_perform_update', 'wptouch_mwp_perform_update' );

add_filter( 'plugins_loaded', 'wptouch_compat_remove_hooks' );

function wptouch_compat_remove_hooks() {

	// Paginated Comments plugin
	remove_action( 'init', 'Paginated_Comments_init' );
	remove_action( 'admin_menu', 'Paginated_Comments_menu_add' );
	remove_action( 'template_redirect', 'Paginated_Comments_alter_source', 15 );
	remove_action( 'wp_head', 'Paginated_Comments_heads' );
	remove_filter( 'comment_post_redirect', 'Paginated_Comments_redirect_location', 1, 2 );

	// qTranslate
	if ( function_exists( 'qtrans_useCurrentLanguageIfNotFoundShowAvailable' ) ) {
		add_filter( 'wptouch_menu_item_title', 'qtrans_useCurrentLanguageIfNotFoundShowAvailable', 0 );
	}

	// Facebook Like button
	remove_filter( 'the_content', 'Add_Like_Button');

	// Sharebar Plugin
	remove_filter( 'the_content', 'sharebar_auto' );
	remove_action( 'wp_head', 'sharebar_header' );

	// Disqus
	remove_filter( 'comments_number', 'dsq_comments_number' );

	// Classipress
	remove_action( 'admin_enqueue_scripts', 'cp_load_admin_scripts' );
}

function wptouch_check_for_initialization( $should_init ) {
	// Check for Piggy Pro
	if ( function_exists( 'piggy_should_be_shown' ) && piggy_should_be_shown() ) {
		$should_init = false;
	}

	// Check for AJAX requests
	if ( defined( 'XMLRPC_REQUEST' ) || defined( 'APP_REQUEST'  ) ) {
		$should_init = false;
	}

	return $should_init;
}

function wptouch_mwp_update_notification( $premium_updates ) {
	global $wptouch_pro;

	if( !function_exists( 'get_plugin_data' ) ) {
		include_once( ABSPATH.'wp-admin/includes/plugin.php');
	}

	if ( !function_exists( 'mwp_wptouch_pro_get_latest_info' ) ) {
		return;
	}

	$myplugin = get_plugin_data( WPTOUCH_DIR . '/wptouch-pro.php' );
	$myplugin['type'] = 'plugin';

	$latest_info = mwp_wptouch_pro_get_latest_info();
	if ( $latest_info ) {
		// Check to see if a new version is available
		if ( $latest_info['version'] != WPTOUCH_VERSION ) {
			$myplugin['new_version'] = $latest_info['version'];

			array_push( $premium_updates, $myplugin ) ;

			$wptouch_pro->remove_transient_info();
		}
	}

	return $premium_updates;
}

function wptouch_mwp_perform_update( $update ){
	global $wptouch_pro;

	if( !function_exists( 'get_plugin_data' ) ) {
		include_once( ABSPATH.'wp-admin/includes/plugin.php');
	}

	if ( !function_exists( 'mwp_wptouch_pro_get_latest_info' ) ) {
		return;
	}

	$my_addon = get_plugin_data(  WPTOUCH_DIR . '/wptouch-pro.php' );
	$my_addon[ 'type' ] = 'plugin';

	$latest_info = mwp_wptouch_pro_get_latest_info();
	if ( $latest_info ) {
		// Check for a new version
		if ( $latest_info['version'] != WPTOUCH_VERSION ) {
			$my_addon['url'] = $latest_info['upgrade_url'];

			array_push( $update, $my_addon );
		}
	}

	return $update;
}

function wptouch_maybe_show_notice_about_incompatible_themes() {
	if ( wptouch_is_active_theme_compatible() || wptouch_has_theme_incompatibility_notice_been_dismissed() ) {
		return;
	}
	?>
	<div class="notice notice-error is-dismissible js-theme-incompatibility-notice">
	   <p><?php echo wp_kses_post( sprintf( __( 'The current theme is not compatible with WPtouch. Some features may not function properly. Check out %1$sthis support article%2$s for more information.', 'wptouch-pro' ), '<a href="https://support.wptouch.com/support/solutions/articles/5000523490">', '</a>' ) ); ?></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'wptouch-pro' ); ?></span>
		</button>
	</div>
	<?php
}
add_action( 'admin_notices', 'wptouch_maybe_show_notice_about_incompatible_themes' );

function wptouch_has_theme_incompatibility_notice_been_dismissed() {
	$current_theme = get_option( 'stylesheet' );
	return wptouch_is_value_in_options_array( $current_theme, '_wptouch-disable-theme-incompat-notice' );
}

function wptouch_is_value_in_options_array( $value, $option_name ) {
	$options_array = get_option( $option_name, array() );

	if ( ! is_array( $options_array ) ) {
		return false;
	}

	if ( in_array( $value, $options_array ) ) {
		return true;
	}

	return false;
}

function wptouch_is_active_theme_compatible() {
	$current_theme = get_option( 'stylesheet' );
	$is_theme_compatible = true;
	$incompatiable_themes = array( 'divi', 'architectos', 'classipress', 'geotheme', 'headway', 'swagger', 'clear', 'avada' );

	/**
	 * List of incomptaible themes.
	 *
	 * @since 4.3.13
	 * @param array $incompatiable_themes Incompatible theme slugs.
	 */
	$incompatiable_themes = apply_filters( 'wptouch_incompatible_themes', $incompatiable_themes );

	if ( in_array( strtolower( $current_theme ), $incompatiable_themes ) ) {
		$is_theme_compatible = false;
	}

	return $is_theme_compatible;
}

function wptouch_disable_theme_incompatibility_notice() {
	$current_theme = get_option( 'stylesheet' );
	$current_theme_incompat_notices = get_option( '_wptouch-disable-theme-incompat-notice', array() );

	$current_theme_incompat_notices[] = $current_theme;
	update_option( '_wptouch-disable-theme-incompat-notice', $current_theme_incompat_notices, false );
}
add_action( 'wp_ajax_disable_theme_incompatibility_notice', 'wptouch_disable_theme_incompatibility_notice' );

function wptouch_maybe_show_notice_about_incompatible_plugins() {
	if ( false === $incompatible_plugin = wptouch_get_active_incompatible_plugins() ) {
		return;
	}

	if ( wptouch_incompatible_plugin_notice_has_been_dismissed( $incompatible_plugin ) ) {
		return;
	}
	?>
	<div class="notice notice-error is-dismissible js-plugin-incompatibility-notice">
		<p><?php echo wp_kses_post( sprintf( __( 'The %1$s plugin is not compatible with WPtouch. Some features may not function properly. Check out %2$sthis support article%3$s for more information.', 'wptouch-pro' ), $incompatible_plugin, '<a href="https://support.wptouch.com/support/solutions/articles/5000523490">', '</a>' ) ); ?></p>
		<input type="hidden" class="js-wptouch-incompatible-plugin-name" value="<?php echo esc_html( $incompatible_plugin ); ?>">
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'wptouch-pro' ); ?></span>
		</button>
	</div>
	<?php
}
add_action( 'admin_notices', 'wptouch_maybe_show_notice_about_incompatible_plugins' );

function wptouch_incompatible_plugin_notice_has_been_dismissed( $plugin_name ) {
	return wptouch_is_value_in_options_array( $plugin_name, '_wptouch-disable-plugin-incompat-notice' );
}

/**
 * For now (4.3.13) the only incompatible plugin we want to display an alert for is Visual Composer.
 *
 * @return bool|string False if no incompatible plugin is active. Name of plugin if incompatible plugin is active.
 */
function wptouch_get_active_incompatible_plugins() {
	$active_plugins = wp_get_active_and_valid_plugins();
	$is_incompatible_plugin_active = false;
	$incompatiable_plugins = array();

	/**
	 * List of incomptaible plugins.
	 *
	 * @since 4.3.13
	 *
	 * @param array $incompatiable_themes The incomptaible plugins to search for.
	 *              array(
	 *                  $plugin_name => $plugin_file_path
	 *              );
	*/
	$incompatiable_plugins = apply_filters( 'wptouch_incompatible_plugins', $incompatiable_plugins );

	foreach ( $incompatiable_plugins as $name => $file ) {
		if ( is_plugin_active( $file ) ) {
			$is_incompatible_plugin_active = $name;
			break;
		}
	}

	// Manually check for Visual Composer since a lot of themes bundle it and it might not show in standard
	// plugin location.
	if ( defined( 'WPB_VC_VERSION' ) ) {
		$is_incompatible_plugin_active = 'Visual Composer';
	}

	return $is_incompatible_plugin_active;
}

function wptouch_disable_plugin_incompatibility_notice() {
	if ( empty( $_GET['plugin'] ) ) {
		return;
	}

	$plugin_name = sanitize_text_field( wp_unslash( $_GET['plugin'] ) );
	if ( empty( $plugin_name ) ) {
		return;
	}

	$dismissed_plugin_incompat_notices = get_option( '_wptouch-disable-plugin-incompat-notice', array() );
	$dismissed_plugin_incompat_notices[] = $plugin_name;

	update_option( '_wptouch-disable-plugin-incompat-notice', $dismissed_plugin_incompat_notices, false );
}
add_action( 'wp_ajax_disable_plugin_incompatibility_notice', 'wptouch_disable_plugin_incompatibility_notice' );

/**
 * Allows posts in the selected language to display when using a custom Posts page.
 *
 * @see https://github.com/sureswiftcapital/wptouch-pro/pull/42
 *
 * @param $is_latest_posts_page
 * @param $latest_posts_page
 * @param $page_id
 *
 * @return bool
 */
function wptouch_wpml_latest_posts( $is_latest_posts_page, $latest_posts_page, $page_id ) {
	if ( function_exists( 'icl_object_id' ) && $page_id != $latest_posts_page ) {
		$translated_id = icl_object_id( $latest_posts_page, 'page', true );
		if ( $translated_id == $page_id ) {
			return true;
		}
	}

	return $is_latest_posts_page;
}
add_filter( 'foundation_is_custom_latest_posts_page', 'wptouch_wpml_latest_posts', 10, 3 );
