<?php
/**
 * Plugin Name:       GutSlider Blocks
 * Description:       A collection of custom Gutenberg Slider Blocks to slide your post or custom content.
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Zakaria Binsaifullah
 * Author URI:        https://makegutenblock.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gutsliders
 *
 */

// Stop Direct Access 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// require autoload
require_once __DIR__ . '/vendor/autoload.php';

use GutSliders\Traits\Singleton;

/**
 * Blocks Final Class
 */

final class GutSliderBlocks {

	// Singleton Trait
	use Singleton;

	// Constructor
	public function __construct() {
		// loader 
		new GutSliders\Loader();
		// define constants
		$this->define_constants();
	}

	/**
	 * Define the plugin constants
	 */
	private function define_constants() {
		define( 'GUTSLIDERS_VERSION', '1.0.0' );
		define( 'GUTSLIDERS_URL', plugin_dir_url( __FILE__ ) );	
		define('GUTSLIDERS_DIR_PATH', plugin_dir_path(__FILE__));
		define( 'GUTSLIDERS_PATH', __DIR__ );
	}

}

/**
 * Kickoff
*/
function gut_slider_blocks() {
	return GutSliderBlocks::init();
}

// Let's start it
gut_slider_blocks();
