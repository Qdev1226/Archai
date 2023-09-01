<?php
/**
 * Add theme page
 */
function jetblack_fse_menu() {
	add_theme_page( esc_html__( 'JetBlack FSE Theme', 'jetblack-fse' ), esc_html__( 'JetBlack FSE Theme', 'jetblack-fse' ), 'edit_theme_options', 'jetblack-fse-info', 'jetblack_fse_theme_page_display' );
}
add_action( 'admin_menu', 'jetblack_fse_menu' );

/**
 * Display About page
 */
function jetblack_fse_theme_page_display() {
    require get_parent_theme_file_path( '/inc/about-admin/templates/theme-info.php' );
}

function jetblack_fse_admin_plugin_notice() {
    require get_parent_theme_file_path( '/inc/about-admin/templates/admin-plugin-notice.php' );
}
add_action( 'admin_notices', 'jetblack_fse_admin_plugin_notice' );

/**
 * Enqueue admin scripts and styles.
 */
function jetblack_fse_admin_scripts() {
	
    wp_enqueue_style(
        'jetblack-fse-admin-style',
        get_template_directory_uri() . '/assets/css/admin-style.css',
        array(),
        jetblack_file_version( '/assets/css/admin-style.css' )
    );
    
}
add_action( 'admin_enqueue_scripts', 'jetblack_fse_admin_scripts' );
