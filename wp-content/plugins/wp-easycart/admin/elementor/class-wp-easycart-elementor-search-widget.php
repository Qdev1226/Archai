<?php
/**
 * WP EasyCart Search Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Search_Widget
 * @author   WP EasyCart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use ELementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

/**
 * WP EasyCart Search Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Search_Widget
 * @author   WP EasyCart
 */
class Wp_Easycart_Elementor_Search_Widget extends \Elementor\Widget_Base {

	/**
	 * Get search widget name.
	 */
	public function get_name() {
		return 'wp_easycart_search';
	}

	/**
	 * Get search widget title.
	 */
	public function get_title() {
		return esc_attr__( 'WP EasyCart Search', 'wp-easycart' );
	}

	/**
	 * Get search widget icon.
	 */
	public function get_icon() {
		return 'eicon-search';
	}

	/**
	 * Get search widget categories.
	 */
	public function get_categories() {
		return array( 'wp-easycart-elements' );
	}

	/**
	 * Get search widget keywords.
	 */
	public function get_keywords() {
		return array( 'search', 'wp-easycart' );
	}

	/**
	 * Enqueue search widget scripts and styles.
	 */
	public function get_script_depends() {
		$scripts = array( 'isotope-pkgd', 'jquery-hoverIntent' );
		if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$scripts[] = 'wpeasycart_js';
		}
		return $scripts;
	}

	/**
	 * Setup search widget controls.
	 */
	protected function _register_controls() {
		$pages = get_pages();
		$pages_select = array();
		foreach ( $pages as $page ) {
			$pages_select[ $page->ID ] = $page->post_title;
		}
		$this->start_controls_section(
			'section_products',
			array(
				'label' => esc_attr__( 'Search Options', 'wp-easycart' ),
			)
		);
		$this->add_control(
			'label',
			array(
				'label'       => esc_attr__( 'Button Label', 'wp-easycart' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => 'Search',
				'placeholder' => esc_attr__( 'Button Label', 'wp-easycart' ),
			)
		);
		$this->add_control(
			'postid',
			array(
				'label'   => esc_attr__( 'Search Page', 'wp-easycart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => (int) get_option( 'ec_option_storepage' ),
				'options' => $pages_select,
			)
		);
		$this->end_controls_section();
	}

	/**
	 * Render search widget control output in the editor.
	 */
	protected function render() {
		$atts = $this->get_settings_for_display();
		include( EC_PLUGIN_DIRECTORY . '/admin/elementor/wp-easycart-elementor-search-widget.php' );
	}
}
