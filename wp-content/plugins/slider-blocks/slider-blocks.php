<?php
/**
 * Plugin Name:       GutSlider - Slider Blocks for Gutenberg
 * Description:       A collection of custom Gutenberg Slider Blocks to slide your post or custom content.
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           2.1.0
 * Author:            Zakaria Binsaifullah
 * Author URI:        https://makegutenblock.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       slider-blocks
 *
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Blocks Final Class
 * return GutSliderBlocks
 */
 if( ! class_exists('GutSliderBlocks') ) {

	final class GutSliderBlocks {

		private static $instance = null;

		/**
		 * Constructor
		 * return void
		 */
		public function __construct() {
			$this->define_constants();
			$this->includes();
		}
	
		/**
		 * Define the plugin constants
		 * return void
		 */
		private function define_constants() {
			define( 'GUTSLIDER_VERSION', '2.1.0' );
			define( 'GUTSLIDER_URL', plugin_dir_url( __FILE__ ) );	
			define('GUTSLIDER_DIR_PATH', plugin_dir_path(__FILE__));
			define( 'GUTSLIDER_DIR', __DIR__ );
		}

		/**
		 * Initialize the plugin
		 * return GutSliderBlocks
		 */
		public static function init() {
			if( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Include the files
		 * return void
		 */
		public function includes() {
			require_once GUTSLIDER_DIR . '/includes/init.php';
		}
	}

 }

 /**
  * Initialize the GutSliderBlocks
  * return GutSliderBlocks
  */
  function gutsliderblocks() {
	  return GutSliderBlocks::init();
  }

  // kick-off the plugin
  gutsliderblocks();