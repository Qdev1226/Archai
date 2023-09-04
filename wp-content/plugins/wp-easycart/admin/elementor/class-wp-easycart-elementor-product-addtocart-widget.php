<?php
/**
 * WP EasyCart Product Add To Cart Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Product_Addtocart_Widget
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
use Elementor\Wp_Easycart_Controls_Manager;

/**
 * WP EasyCart Product Add To Cart Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Product_Addtocart_Widget
 * @author   WP EasyCart
 */
class Wp_Easycart_Elementor_Product_Addtocart_Widget extends \Elementor\Widget_Base {

	/**
	 * Get product add to cart widget name.
	 */
	public function get_name() {
		return 'wp_easycart_product_addtocart';
	}

	/**
	 * Get product add to cart widget title.
	 */
	public function get_title() {
		return esc_attr__( 'WP EasyCart Add to Cart', 'wp-easycart' );
	}

	/**
	 * Get product add to cart widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Get product add to cart widget categories.
	 */
	public function get_categories() {
		return array( 'wp-easycart-elements' );
	}

	/**
	 * Get product add to cart widget keywords.
	 */
	public function get_keywords() {
		return array( 'products', 'shop', 'wp-easycart' );
	}

	/**
	 * Enqueue product add to cart widget scripts and styles.
	 */
	public function get_script_depends() {
		$scripts = array( 'isotope-pkgd', 'jquery-hoverIntent' );
		if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$scripts[] = 'wpeasycart_js';
		}
		return $scripts;
	}

	/**
	 * Setup product add to cart widget controls.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_products',
			array(
				'label' => esc_attr__( 'Add to Cart Options', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => esc_attr__( 'Select Product', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product',
				'label_block' => true,
				'multiple'    => false,
			)
		);

		$this->add_control(
			'background_add',
			array(
				'label'       => esc_attr__( 'Background Add', 'wp-easycart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'featured',
				'options'     => array(
					'0' => esc_attr__( 'No, Redirect to Cart', 'wp-easycart' ),
					'1'  => esc_attr__( 'Yes, Add in Background', 'wp-easycart' ),
				)
			)
		);

		$this->add_control(
			'enable_quantity',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Quantity', 'wp-easycart' ),
				'default'   => 0,
			)
		);

		$this->add_control(
			'button_width',
			array(
				'type'    => Controls_Manager::SLIDER,
				'label'   => esc_attr__( 'Button Width (px)', 'wp-easycart' ),
				'default' => array(
					'unit' => 'px',
					'size' => 150,
				),
				'size_units' => array( 'px' ),
				'range'   => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 1000,
					),
				),
			)
		);

		$this->add_control(
			'button_font',
			array(
				'type'  => Controls_Manager::FONT,
				'label' => esc_attr__( 'Button Font', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_font_main' ) != '' ) ? get_option( 'ec_option_font_main' ) : 'Lato',
			)
		);

		$this->add_control(
			'button_bg_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'Button Background Color', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_details_main_color' ) != '' ) ? get_option( 'ec_option_details_main_color' ) : '#333333',
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'Button Text Color', 'wp-easycart' ),
				'default' => '#ffffff',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render product add to cart widget control output in the editor.
	 */
	protected function render() {
		$atts = $this->get_settings_for_display();
		include( EC_PLUGIN_DIRECTORY . '/admin/elementor/wp-easycart-elementor-product-addtocart-widget.php' );
	}
}
