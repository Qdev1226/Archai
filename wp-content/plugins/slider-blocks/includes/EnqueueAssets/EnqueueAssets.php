<?php

namespace GutSliders\EnqueueAssets;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EnqueueAssets {
    // constructor 
    public function __construct() {
        // generate dynaamic style
		add_filter( 'render_block', [ $this, 'generate_dynamic_style' ], 10, 2 );
        // enqueue editor assets
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
        // block frontend assets
        add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
    }

    /**
     * Enqueue Block Assets
     */
    public function enqueue_block_assets(){
        if( file_exists( GUTSLIDERS_DIR_PATH . './build/frontend/frontend.asset.php' ) ){
            $gutsldiers_dependency_file = include_once GUTSLIDERS_DIR_PATH . './build/frontend/frontend.asset.php';
        }

        wp_enqueue_script(
            'gutsliders-blocks-frontend-js',
            GUTSLIDERS_URL . './build/frontend/frontend.js',
            isset( $gutsldiers_dependency_file['dependencies'] ) ? $gutsldiers_dependency_file['dependencies'] : [],
            isset( $gutsldiers_dependency_file['version'] ) ? $gutsldiers_dependency_file['version'] : GUTSLIDERS_VERSION,
            true
        );

        wp_enqueue_style(
            'gutsliders-blocks-frontend-css',
            GUTSLIDERS_URL . './build/frontend/frontend.css',
            [],
            GUTSLIDERS_VERSION
        );
    }

    /**
     * Enqueue Editor Assets
     */
    public function enqueue_editor_assets(){
        $gutsldiers_dependency_file = include_once GUTSLIDERS_DIR_PATH . './build/global/global.asset.php';
        wp_enqueue_script(
            'gutsliders-blocks-global-js',
            GUTSLIDERS_URL . './build/global/global.js',
            $gutsldiers_dependency_file['dependencies'],
            $gutsldiers_dependency_file['version'],
            true
        );

        wp_enqueue_style(
            'gutsliders-blocks-controls-css',
            GUTSLIDERS_URL . './build/global/global.css',
            [],
            GUTSLIDERS_VERSION
        );
    }

    /**
     * Register Dynamic Style
     */
    function generate_dynamic_style($block_content, $block) {
        if (isset($block['blockName']) && str_contains($block['blockName'], 'gutsliders/')) {
            do_action( 'gutsliders_render_block', $block );
            if (isset($block['attrs']['blockStyle'])) {
                $style = $block['attrs']['blockStyle'];
                $handle = isset( $block['attrs']['uniqueId'] ) ? $block['attrs']['uniqueId'] : 'gutsliders';
                // convert style array to string
                if ( is_array($style) ) {
                    $style = implode(' ', $style);
                }
                // minify style to remove extra space
                $style = preg_replace( '/\s+/', ' ', $style );
                wp_register_style(
                    $handle,
                    false
                );
                wp_enqueue_style( $handle );
                wp_add_inline_style( $handle, $style );
            }
        }
        return $block_content;
    }

}