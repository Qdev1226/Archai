<?php
/**
 * Enqueue Assets 
 * @package GutSliderBlocks
 */
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

 if( ! class_exists( 'GutSlider_Assets' ) ) {

    class GutSlider_Assets {
        
        /**
         * Constructor
         * return void 
         */
        public function __construct() {
            add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
            add_action( 'enqueue_block_assets', [ $this, 'enqueue_assets' ] );
        }

        /**
         * Enqueue Block Assets [ Editor Only ]
         * return void
         */
        public function enqueue_editor_assets(){
            if( file_exists( GUTSLIDER_DIR_PATH . './build/global/global.asset.php' ) ){
                $dependency_file = include_once GUTSLIDER_DIR_PATH . './build/global/global.asset.php';
            }
    
            if( is_array( $dependency_file ) && ! empty( $dependency_file ) ) {
                wp_enqueue_script(
                    'gutslider-blocks-global-script',
                    GUTSLIDER_URL . './build/global/global.js',
                    isset( $dependency_file['dependencies'] ) ? $dependency_file['dependencies'] : [],
                    isset( $dependency_file['version'] ) ? $dependency_file['version'] : GUTSLIDER_VERSION,
                    true
                );
            }
    
            wp_enqueue_style(
                'gutslider-blocks-global-style',
                GUTSLIDER_URL . './build/global/global.css',
                [],
                GUTSLIDER_VERSION
            );
        }

        /**
         * Enqueue Block Assets [ Editor + Frontend ]
         * return void 
         */
        public function enqueue_assets() {
            // swiper style
            wp_enqueue_style(
                'swiper-style',
                GUTSLIDER_URL . 'assets/css/swiper-bundle.min.css',
                [],
                GUTSLIDER_VERSION
            );

            // swiper script
            wp_enqueue_script(
                'swiper-script',
                GUTSLIDER_URL . 'assets/js/swiper-bundle.min.js',
                [],
                GUTSLIDER_VERSION,
                true
            );
        }

    }

 }

    new GutSlider_Assets();    // Initialize the class