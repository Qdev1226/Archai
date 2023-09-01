<?php
/**
 * Add theme page
 */
function flexiverse_menu() {
	add_theme_page( esc_html__( 'FlexiVerse Theme', 'flexiverse' ), esc_html__( 'FlexiVerse Theme', 'flexiverse' ), 'edit_theme_options', 'flexiverse-info', 'flexiverse_theme_page_display' );
}
add_action( 'admin_menu', 'flexiverse_menu' );

/**
 * Display About page
 */
function flexiverse_theme_page_display() {
    require get_parent_theme_file_path( '/inc/about-admin/templates/theme-info.php' );
}

function flexiverse_admin_plugin_notice() {
    if ( ! get_option( 'flexiverse_notice_dismissed' ) || ( time() - get_option( 'flexiverse_notice_dismissed' ) > 30 * DAY_IN_SECONDS ) ) {
        require get_parent_theme_file_path( '/inc/about-admin/templates/admin-plugin-notice.php' );
    }
}
add_action( 'admin_notices', 'flexiverse_admin_plugin_notice' );

/**
 * Enqueue admin scripts and styles.
 */
function flexiverse_admin_scripts() {
	
    wp_enqueue_style(
        'flexiverse-admin-message-style',
        get_template_directory_uri() . '/assets/css/admin-style.css',
        array(),
        flexiverse_file_version( '/assets/css/admin-style.css' )
    );
    
}
add_action( 'admin_enqueue_scripts', 'flexiverse_admin_scripts' );

function flexiverse_admin_notice_script() {
    ?>
    <script>
        jQuery(document).ready(function($){
            $(document).on('click', '#flexiverse-admin-notice .notice-dismiss', function(){
                $.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'flexverse_dismiss_custom_notice'
                    }
                });
            });
        });
    </script>
    <?php
}
add_action( 'admin_footer', 'flexiverse_admin_notice_script' );

function flexverse_dismiss_custom_notice() {
    update_option( 'flexiverse_notice_dismissed', time() );
    wp_die();
}
add_action( 'wp_ajax_flexverse_dismiss_custom_notice', 'flexverse_dismiss_custom_notice' );

