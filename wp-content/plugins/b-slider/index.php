<?php
/**
 * Plugin Name: B Slider
 * Description: Simple slider with bootstrap.
 * Version: 1.1.2
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: slider
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'BSB_PLUGIN_VERSION', 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.1.2 ' );
define( 'BSB_DIR', plugin_dir_url( __FILE__ ) );
define( 'BSB_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

if(!function_exists('bsb_init')){
    function bsb_init(){
        global $bsb_bs;
        require_once(plugin_dir_path(__FILE__).'bplugins_sdk/init.php');
        $bsb_bs = new BPlugins_SDK(__FILE__);
    }
    bsb_init();
}else {
	$bsb_bs->uninstall_plugin(__FILE__);
}


// Block Directory
class BSBSlider{
	function __construct(){ 
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'adminEnqueueScripts'] );
		add_action( 'init', [$this, 'onInit'] );
	} 

	function enqueueBlockAssets(){
		wp_register_style( 'bsbfontawesome', BSB_ASSETS_DIR . 'css/fontAwesome.min.css', [], BSB_PLUGIN_VERSION );
		wp_register_style( 'bsb-style', BSB_ASSETS_DIR . 'css/bootstrap.min.css', [], BSB_PLUGIN_VERSION );
		wp_register_script( 'bootstrap', BSB_ASSETS_DIR . 'js/bootstrap.min.js', [], BSB_PLUGIN_VERSION );
		
		wp_register_script( 'bsb-slider-script', plugins_url( 'dist/script.js', __FILE__ ), [ 'react', 'react-dom', 'jquery', 'bootstrap' ], BSB_PLUGIN_VERSION );
		
		wp_register_style( 'bsb-slider-style', plugins_url( 'dist/style.css', __FILE__ ), [ 'bsb-style', 'bsbfontawesome' ], BSB_PLUGIN_VERSION ); // Style

		wp_localize_script('bsb-slider-editor-script', 'bsbInfo', [
			'patternsImagePath' => BSB_DIR.'assets/images/patterns/'
		]);
	}

	// Short code style  
	function adminEnqueueScripts( $hook ){
		if( 'edit.php' === $hook || 'post.php' === $hook ){
			wp_enqueue_style( 'bsbAdmin', BSB_ASSETS_DIR . 'css/admin.css', [], BSB_PLUGIN_VERSION );
			wp_enqueue_script( 'bsbAdmin', BSB_ASSETS_DIR . 'js/admin.js', [ 'wp-i18n' ], BSB_PLUGIN_VERSION, true );
		}
	}
  
	function onInit() {
		wp_register_style( 'bsb-slider-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'bsb-slider-style' ], BSB_PLUGIN_VERSION ); // Backend Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'bsb-slider-editor-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block
		
		wp_set_script_translations( 'bsb-slider-editor-script', 'slider', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		$className = $className ?? '';
		$bsbBlockClassName = 'wp-block-bsb-slider ' . $className . ' align' . $align;

		wp_enqueue_style( 'bsb-slider-style' );
		wp_enqueue_script( 'bsb-slider-script' );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $bsbBlockClassName ); ?>' id='bsbCarousel-<?php echo esc_attr( $cId ) ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new BSBSlider();

require_once plugin_dir_path(__FILE__).'/custom-post.php';
 