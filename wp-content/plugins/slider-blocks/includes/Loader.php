<?php

namespace GutSliders;
use GutSliders\Admin\Admin;
use GutSliders\RegisterBlocks\RegisterBlocks;
use GutSliders\EnqueueAssets\EnqueueAssets;
use GutSliders\FontsLoader\FontsLoader;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Loader {

    // Constructor
    public function __construct() {
        // register blocks category
        if( version_compare( $GLOBALS['wp_version'], '5.7', '<' ) ) {
            add_filter( 'block_categories', [ $this, 'register_block_category' ], 10, 2, 9999 );
        } else {
            add_filter( 'block_categories_all', [ $this, 'register_block_category' ], 10, 2, 9999 );
        }

        // init
        $this->init();
    } 

    /**
	 * Register Block Category
	 */
	public function register_block_category( $categories, $post ) {
		return array_merge(
			array(
				array(
					'slug'  => 'gutsliders',
					'title' => __( 'GutSlider Blocks', 'slider-blocks' ),
				),
			),
			$categories,
		);
	}

    /**
     * Init
     */
    public function init() {
        // admin
        new Admin();
        // register blocks
        new RegisterBlocks();
        // enqueue assets
        new EnqueueAssets();
        // fonts loader
        new FontsLoader();
    }
}