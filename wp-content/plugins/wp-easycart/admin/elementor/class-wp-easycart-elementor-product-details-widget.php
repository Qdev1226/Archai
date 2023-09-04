<?php
/**
 * WP EasyCart Product Details Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Product_Details_Widget
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
 * WP EasyCart Product Details Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Product_Details_Widget
 * @author   WP EasyCart
 */
class Wp_Easycart_Elementor_Product_Details_Widget extends \Elementor\Widget_Base {

	/**
	 * Get product details widget name.
	 */
	public function get_name() {
		return 'wp_easycart_product_details';
	}

	/**
	 * Get product details widget title.
	 */
	public function get_title() {
		return esc_attr__( 'WP EasyCart Product Details', 'wp-easycart' );
	}

	/**
	 * Get product details widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-images';
	}

	/**
	 * Get product details widget categories.
	 */
	public function get_categories() {
		return array( 'wp-easycart-elements' );
	}

	/**
	 * Get product details widget keywords.
	 */
	public function get_keywords() {
		return array( 'products', 'shop', 'wp-easycart' );
	}

	/**
	 * Enqueue product details widget scripts and styles.
	 */
	public function get_script_depends() {
		$scripts = array( 'isotope-pkgd', 'jquery-hoverIntent' );
		if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$scripts[] = 'wpeasycart_js';
		}
		return $scripts;
	}

	/**
	 * Setup product details widget controls.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_products',
			array(
				'label' => esc_attr__( 'Product Details Options', 'wp-easycart' ),
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
			'cols_upper_desktop',
			array(
				'label'     => esc_attr__( 'Columns Upper Desktop ( >= 1200px )', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 2,
				'options'   => array(
					'1' => 1,
					'2' => 2,
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_attr__( 'Columns', 'wp-easycart' ),
				'default'   => 2,
				'options'   => array(
					'1' => 1,
					'2' => 2,
				),
			)
		);

		$this->add_control(
			'cols_mobile_small',
			array(
				'label'     => esc_attr__( 'Columns Under Mobile ( <= 575px )', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 1,
				'options'   => array(
					'1' => 1,
					'2' => 2,
				),
			)
		);

		$this->add_control(
			'details_sizing',
			array(
				'type'    => Controls_Manager::SLIDER,
				'label'   => esc_attr__( 'Image Width (%)', 'wp-easycart' ),
				'default' => array(
					'unit' => 'px',
					'size' => (int) get_option( 'ec_option_product_details_sizing' ),
				),
				'size_units' => array( 'px' ),
				'range'   => array(
					'px' => array(
						'step' => 5,
						'min'  => 5,
						'max'  => 95,
					),
				),
			)
		);

		$this->add_control(
			'breadcrumbs',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Breadcrumbs', 'wp-easycart' ),
				'default'   => get_option( 'ec_option_show_breadcrumbs' ),
			)
		);

		$this->add_control(
			'image_hover',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Image Magnification', 'wp-easycart' ),
				'default'   => get_option( 'ec_option_show_magnification' ),
			)
		);

		$this->add_control(
			'lightbox',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Image Lightbox', 'wp-easycart' ),
				'default'   => get_option( 'ec_option_show_large_popup' ),
			)
		);

		$this->add_control(
			'thumbnails',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Image Thumbnails', 'wp-easycart' ),
				'default' => true,
			)
		);

		$this->add_control(
			'title',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Product Title', 'wp-easycart' ),
				'default'   => 1,
			)
		);

		$this->add_control(
			'customer_reviews',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Customer Reviews', 'wp-easycart' ),
				'default'   => 1,
			)
		);

		$this->add_control(
			'price',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Product Price', 'wp-easycart' ),
				'default'   => 1,
			)
		);

		$this->add_control(
			'short_description',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Short Description', 'wp-easycart' ),
				'default'   => 1,
			)
		);

		$this->add_control(
			'model_number',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Product Model Number', 'wp-easycart' ),
				'default'   => get_option( 'ec_option_show_model_number' ),
			)
		);

		$this->add_control(
			'categories',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Product Categories', 'wp-easycart' ),
				'default'   => get_option( 'ec_option_show_categories' ),
			)
		);

		$this->add_control(
			'manufacturer',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Product Manufacturer', 'wp-easycart' ),
				'default' => get_option( 'ec_option_show_manufacturer' ),
			)
		);

		$this->add_control(
			'stock',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Product Stock', 'wp-easycart' ),
				'default' => 1,
			)
		);

		$this->add_control(
			'social',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Social Sharing', 'wp-easycart' ),
				'default' => 1,
			)
		);

		$this->add_control(
			'description',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Description', 'wp-easycart' ),
				'default' => 1,
			)
		);

		$this->add_control(
			'specifications',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Specifications', 'wp-easycart' ),
				'default' => 1,
			)
		);

		$this->add_control(
			'related_products',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Display Related Products', 'wp-easycart' ),
				'default' => 1,
			)
		);

		$this->add_control(
			'background_add',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Background Add to Cart', 'wp-easycart' ),
				'default' => 0,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_design',
			array(
				'label' => esc_attr__( 'Design Options', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'title_font',
			array(
				'type'  => Controls_Manager::FONT,
				'label' => esc_attr__( 'Title Font', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_font_main' ) != '' ) ? get_option( 'ec_option_font_main' ) : 'Lato',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'Title Color', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_details_main_color' ) != '' ) ? get_option( 'ec_option_details_main_color' ) : '#333333',
			)
		);

		$this->add_control(
			'title_divider_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'Title Divider Color', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_details_main_color' ) != '' ) ? get_option( 'ec_option_details_main_color' ) : '#CCCCCC',
			)
		);

		$this->add_control(
			'price_font',
			array(
				'type'  => Controls_Manager::FONT,
				'label' => esc_attr__( 'Price Font', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_font_main' ) != '' ) ? get_option( 'ec_option_font_main' ) : 'Lato',
			)
		);

		$this->add_control(
			'price_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'Price Color', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_details_main_color' ) != '' ) ? get_option( 'ec_option_details_main_color' ) : '#333333',
			)
		);

		$this->add_control(
			'list_price_font',
			array(
				'type'  => Controls_Manager::FONT,
				'label' => esc_attr__( 'List Price Font', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_font_main' ) != '' ) ? get_option( 'ec_option_font_main' ) : 'Lato',
			)
		);

		$this->add_control(
			'list_price_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'List Price Color', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_details_main_color' ) != '' ) ? get_option( 'ec_option_details_main_color' ) : '#333333',
			)
		);

		$this->add_control(
			'add_to_cart_color',
			array(
				'type'  => Controls_Manager::COLOR,
				'label' => esc_attr__( 'Add to Cart Color', 'wp-easycart' ),
				'default' => ( get_option( 'ec_option_details_main_color' ) != '' ) ? get_option( 'ec_option_details_main_color' ) : '#333333',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render product detailstore widget control output in the editor.
	 */
	protected function render() {
		$atts = $this->get_settings_for_display();
		include( EC_PLUGIN_DIRECTORY . '/admin/elementor/wp-easycart-elementor-product-details-widget.php' );
	}
}
