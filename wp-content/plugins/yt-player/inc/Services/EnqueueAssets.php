<?php
namespace YTP\Services;

class EnqueueAssets {
    
    public function register(){
        add_action('admin_enqueue_scripts', [$this, 'adminAssets']);
        add_action('wp_enqueue_scripts', [$this, 'publicAssets']);
    }

    public function adminAssets(){
        $page = get_current_screen();

        if($page->post_type === 'ytplayer' || $page->base === 'plugins'){
            wp_enqueue_style('ytp-admin', YTP_PLUGIN_DIR.'admin/assets/css/style.css', [], YTP_PLUGIN_VERSION);
            wp_enqueue_script('ytp-admin', YTP_PLUGIN_DIR.'admin/assets/js/script.js', [], YTP_PLUGIN_VERSION);

            wp_localize_script( 'ytp-admin', 'ytpAdmin', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce( 'ytp-nonce' ),
            ]);
        }

    }

    public function publicAssets(){
        wp_enqueue_style( 'ytp-style', YTP_PLUGIN_DIR . 'public/css/player-style.css', array(), YTP_PLUGIN_VERSION, 'all' );
        wp_enqueue_script( 'ytp-js', YTP_PLUGIN_DIR  . 'public/js/yt-plyr.js',YTP_PLUGIN_VERSION, false );
    }

}