<?php

/*
 * Plugin Name: Video Player for YouTube
 * Plugin URI:  http://bplugins.com
 * Description: A simple, accessable, fully customizable & user friendly YouTube Video Player for wordrpess.
 * Version: 1.5.5
 * Author: bPlugins LLC
 * Author URI: http://abuhayatpolash.com
 * License: GPLv3
 * Text Domain: ytp
 * Domain Path:  /languages
 */
//--------------Fremius Integration------------------

if ( function_exists( 'ytp_fs' ) ) {
    ytp_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'ytp_fs' ) ) {
        function ytp_fs()
        {
            global  $ytp_fs;
            
            if ( !isset( $ytp_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_5836_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_5836_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $ytp_fs = fs_dynamic_init( array(
                    'id'             => '5836',
                    'slug'           => 'yt-player',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_829fc74e7bb67d3d555c68048933d',
                    'is_premium'     => false,
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug' => 'edit.php?post_type=ytplayer',
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $ytp_fs;
        }
        
        // Init Freemius.
        ytp_fs();
        // Signal that SDK was initiated.
        do_action( 'ytp_fs_loaded' );
    }
    
    /*Some Set-up*/
    define( 'YTP_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
    define( 'YTP_PLUGIN_VERSION', '1.5.5' );
    if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
        require_once dirname( __FILE__ ) . '/vendor/autoload.php';
    }
    add_action( 'plugins_loaded', function () {
        load_plugin_textdomain( 'ytp', false, YTP_PLUGIN_DIR . "languages" );
        if ( !class_exists( 'CSF' ) ) {
            require_once __DIR__ . '/admin/framework/codestar-framework.php';
        }
        if ( class_exists( 'YTP\\Init' ) ) {
            YTP\Init::register_services();
        }
    } );
    // Footer Review Request
    add_filter( 'admin_footer_text', 'ytp_admin_footer' );
    function ytp_admin_footer( $text )
    {
        
        if ( 'ytplayer' == get_post_type() ) {
            $url = 'https://wordpress.org/support/plugin/yt-player/reviews/?filter=5#new-post';
            $text = sprintf( __( 'If you like <strong>YT Player</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'post-carousel' ), $url );
        }
        
        return $text;
    }
    
    function ytp_import_btn( $links )
    {
        array_unshift( $links, '<a id="ytp_import_btn" href="#">Import</a>' );
        return $links;
    }
    
    $plugin = plugin_basename( __FILE__ );
    add_filter( "plugin_action_links_{$plugin}", 'ytp_import_btn' );
}
