<?php
/**
 * Register All Blocks 
 */

namespace GutSliders\RegisterBlocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class RegisterBlocks {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_blocks' ] );
    }

    /**
     * Register Blocks
     */
    public function register_blocks() {
        $blocksList = [
            [
                'name' => 'content-slider',
            ]
        ];
        
        // register blocks
        if( ! empty( $blocksList ) ) {
            foreach( $blocksList as $block ) {
                register_block_type(
                    GUTSLIDERS_PATH . '/build/blocks/' . $block['name'],
                    isset( $block['render_callback'] ) ?
                    [
                        'render_callback' => $block['render_callback']
                    ] : []
                );
            }
        }
    }

}