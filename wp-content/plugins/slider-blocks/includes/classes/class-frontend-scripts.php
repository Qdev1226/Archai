<?php
/**
 * Enqueue Blocks Frontend Scripts
 * @package GutSliderBlocks
 */

 if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

 if( ! class_exists( 'GutSlider_Blocks_Frontend_Scripts' ) ) {
    
    class GutSlider_Blocks_Frontend_Scripts {

        /**
         * Constructor
         * @return void
         */
        public function __construct() {
            // enqueue scripts using wp_enqueue_scripts hook
            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 9999 );
        }

        /**
         * Register blocks
         * @return void
         */ 
        public function enqueue_assets() {

            $blocksFolder = GUTSLIDER_DIR . '/build/blocks';

            if ( is_dir( $blocksFolder ) ) {

                $contents = scandir( $blocksFolder );

                $blocks = array_filter( $contents, function( $item ) use ( $blocksFolder ) {
                    
                    $frontendScripts = $blocksFolder . DIRECTORY_SEPARATOR . $item . DIRECTORY_SEPARATOR . 'frontend.js';
                    $frontendDependencies = $blocksFolder . DIRECTORY_SEPARATOR . $item . DIRECTORY_SEPARATOR . 'frontend.asset.php';

                    if( file_exists( $frontendScripts ) && file_exists( $frontendDependencies ) ) {
                        $frontendDependencies = include_once $frontendDependencies;

                        $block_name = 'gutsliders/' . $item;

                        if( has_block( $block_name ) ) {
                            wp_enqueue_script( 'gutsliders-' . $item . '-frontend-script', GUTSLIDER_URL . 'build/blocks/' . $item . '/frontend.js', $frontendDependencies['dependencies'], $frontendDependencies['version'], true );
                        }
                    }
                });
            } 

        }

    }

 } 

    new GutSlider_Blocks_Frontend_Scripts();   // Initialize the class