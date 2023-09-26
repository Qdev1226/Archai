<?php
/**
 * Generate Dynamic Style
 * @package GutSliderBlocks
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

 if( ! class_exists( 'GutSlider_Dynamic_Style' ) ) {

    class GutSlider_Dynamic_Style {

        /**
         * Constructor
         * return void 
         */
        public function __construct() {
            add_filter( 'render_block', [ $this, 'generate_dynamic_style' ], 10, 2 );
        }

        function generate_dynamic_style($block_content, $block) {
            if (isset($block['blockName']) && str_contains($block['blockName'], 'gutsliders/')) {

                do_action( 'gutsliders_render_block', $block );

                if (isset($block['attrs']['blockStyle'])) {

                    $style = $block['attrs']['blockStyle'];

                    $handle = isset( $block['attrs']['uniqueId'] ) ? $block['attrs']['uniqueId'] : 'slider-blocks';

                    if ( is_array( $style ) && !empty( $style ) ) {
                        $style = implode(' ', $style);
                    }
                    // minify style to remove extra space
                    $style = preg_replace( '/\s+/', ' ', $style );

                    // register style
                    wp_register_style( $handle,false, [], GUTSLIDER_VERSION, 'all' ); // wp_register_style( $handle, $src, $deps, $ver, $media );
                    wp_enqueue_style( $handle );
                    wp_add_inline_style( $handle, $style );

                }

            }
            return $block_content;
        }

    }

 }

    new GutSlider_Dynamic_Style();    // Initialize the class