<?php
/**
 * WP EasyCart Product Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Product_Widget
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
 * WP EasyCart Product Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Product_Widget
 * @author   WP EasyCart
 */
class Wp_Easycart_Elementor_Product_Widget extends \Elementor\Widget_Base {
	/**
	 * Get product widget name.
	 */
	public function get_name() {
		return 'wp_easycart_product';
	}

	/**
	 * Get product widget title.
	 */
	public function get_title() {
		return esc_attr__( 'WP EasyCart Products', 'wp-easycart' );
	}

	/**
	 * Get product widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Get product widget categories.
	 */
	public function get_categories() {
		return array( 'wp-easycart-elements' );
	}

	/**
	 * Get product widget keywords.
	 */
	public function get_keywords() {
		return array( 'products', 'shop', 'wp-easycart' );
	}

	/**
	 * Enqueue product widget scripts and styles.
	 */
	public function get_script_depends() {
		$scripts = array( 'owl-carousel', 'isotope-pkgd', 'jquery-hoverIntent' );
		if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$scripts[] = 'wpeasycart_js';
			$scripts[] = 'wpeasycart_owl_carousel_js';
		}
		return $scripts;
	}

	/**
	 * Setup product widget controls.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_products',
			array(
				'label' => esc_attr__( 'Products Selector', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_attr__( 'Title', 'wp-easycart' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => '',
				'placeholder' => esc_attr__( 'Title', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'title_link',
			array(
				'label' => esc_attr__( 'Title Link', 'wp-easycart' ),
				'type'  => Controls_Manager::URL,
			)
		);

		$this->add_control(
			'desc',
			array(
				'label'       => esc_attr__( 'Description', 'wp-easycart' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'placeholder' => esc_attr__( 'Description', 'wp-easycart' ),
			)
		);

		$this->add_responsive_control(
			'title_align',
			array(
				'label'     => esc_attr__( 'Alignment', 'wp-easycart' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_attr__( 'Left', 'wp-easycart' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_attr__( 'Center', 'wp-easycart' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_attr__( 'Right', 'wp-easycart' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .title-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'status',
			array(
				'label'   => esc_attr__( 'Product Status', 'wp-easycart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => esc_attr__( 'All', 'wp-easycart' ),
					'featured'  => esc_attr__( 'Featured', 'wp-easycart' ),
					'on_sale'   => esc_attr__( 'On Sale', 'wp-easycart' ),
					'in_stock'  => esc_attr__( 'In Stock', 'wp-easycart' ),
				),
			)
		);

		$this->add_control(
			'ids',
			array(
				'label'       => esc_attr__( 'Select products', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product',
				'label_block' => true,
				'multiple'    => 'true',
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => esc_attr__( 'Select categories', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product_cat',
				'label_block' => true,
				'multiple'    => 'true',
			)
		);

		$this->add_control(
			'brands',
			array(
				'label'       => esc_attr__( 'Select Brands', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product_brand',
				'label_block' => true,
				'multiple'    => 'true',
			)
		);

		$this->add_control(
			'count',
			array(
				'type'    => Controls_Manager::SLIDER,
				'label'   => esc_attr__( 'Products Count Per Page', 'wp-easycart' ),
				'default' => array(
					'size' => 4,
				),
				'range'   => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 100,
					),
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_attr__( 'Order by', 'wp-easycart' ),
				'options' => array(
					'',
					'title'            => esc_attr__( 'Title', 'wp-easycart' ),
					'price'            => esc_attr__( 'Price', 'wp-easycart' ),
					'product_id'       => esc_attr__( 'Product ID', 'wp-easycart' ),
					'added_to_db_date' => esc_attr__( 'Date', 'wp-easycart' ),
					'rand'             => esc_attr__( 'Random', 'wp-easycart' ),
					'views'            => esc_attr__( 'Most Views', 'wp-easycart' ),
					'rating'           => esc_attr__( 'Rating', 'wp-easycart' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'type'    => Controls_Manager::SELECT,
				'label'   => esc_attr__( 'Order dir', 'wp-easycart' ),
				'options' => array(
					'',
					'DESC' => esc_attr__( 'Descending', 'wp-easycart' ),
					'ASC'  => esc_attr__( 'Ascending', 'wp-easycart' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products_layout',
			array(
				'label' => esc_attr__( 'Products Layout', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'layout_mode',
			array(
				'label'   => esc_attr__( 'Layout Mode', 'wp-easycart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'     => esc_attr__( 'Grid', 'wp-easycart' ),
					'slider'   => esc_attr__( 'Slider', 'wp-easycart' ),
				),
			)
		);

		$this->add_control(
			'spacing',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_attr__( 'Spacing (px)', 'wp-easycart' ),
				'description' => esc_attr__( 'Leave blank if you use theme default value.', 'wp-easycart' ),
				'default'     => array(
					'size' => 20,
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 40,
					),
				),
			)
		);

		$this->add_control(
			'cols_upper_desktop',
			array(
				'label'     => esc_attr__( 'Columns Upper Desktop ( >= 1200px )', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''  => esc_attr__( 'Default', 'wp-easycart' ),
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
					'6' => 6,
					'7' => 7,
					'8' => 8,
				),
				'condition' => array(
					'layout_mode!'   => array( 'creative' ),
					'product_style!' => array( 'list' ),
				),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_attr__( 'Columns', 'wp-easycart' ),
				'default'   => '4',
				'options'   => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
					'4' => 4,
					'5' => 5,
					'6' => 6,
					'7' => 7,
					'8' => 8,
				),
				'condition' => array(
					'product_style!' => array( 'list' ),
				),
			)
		);

		$this->add_control(
			'cols_under_mobile',
			array(
				'label'     => esc_attr__( 'Columns Under Mobile ( <= 575px )', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 2,
				'options'   => array(
					'1' => 1,
					'2' => 2,
					'3' => 3,
				),
				'condition' => array(
					'product_style!' => array( 'list' ),
				),
			)
		);
		$this->add_control(
			'product_slider_heading',
			array(
				'label'     => esc_attr__( 'Slider Options', 'wp-easycart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout_mode' => array( 'slider' ),
				),
			)
		);

		$this->add_control(
			'product_slider_nav_pos',
			array(
				'label'     => esc_attr__( 'Nav & Dot Position', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					'owl-nav-inside' => esc_attr__( 'Inner', 'wp-easycart' ),
					''               => esc_attr__( 'Outer', 'wp-easycart' ),
					'owl-nav-top'    => esc_attr__( 'Top', 'wp-easycart' ),
				),
				'condition' => array(
					'layout_mode' => array( 'slider' ),
				),
			)
		);

		$this->add_control(
			'product_slider_nav_type',
			array(
				'label'     => esc_attr__( 'Nav Type', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''                => esc_attr__( 'Type 1', 'wp-easycart' ),
					'owl-full'        => esc_attr__( 'Type 2', 'wp-easycart' ),
					'owl-nav-rounded' => esc_attr__( 'Type 3', 'wp-easycart' ),
				),
				'condition' => array(
					'layout_mode' => array( 'slider' ),
				),
			)
		);

		$this->add_responsive_control(
			'slider_nav',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_attr__( 'Show navigation?', 'wp-easycart' ),
				'condition' => array(
					'layout_mode' => array( 'slider' ),
				),
			)
		);

		$this->add_control(
			'slider_nav_show',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_attr__( 'Enable Navigation Auto Hide', 'wp-easycart' ),
				'default'   => 'yes',
				'condition' => array(
					'layout_mode' => array( 'slider' ),
				),
			)
		);

		$this->add_responsive_control(
			'slider_dot',
			array(
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_attr__( 'Show slider dots?', 'wp-easycart' ),
				'condition' => array(
					'layout_mode' => array( 'slider' ),
				),
			)
		);

		$this->add_control(
			'slider_loop',
			array(
				'label'     => esc_attr__( 'Enable Loop', 'wp-easycart' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'layout_mode' => 'slider',
				),
			)
		);

		$this->add_control(
			'slider_auto_play',
			array(
				'label'     => esc_attr__( 'Enable Auto-Play', 'wp-easycart' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'layout_mode' => 'slider',
				),
			)
		);

		$this->add_control(
			'slider_auto_play_time',
			array(
				'label'     => esc_attr__( 'Autoplay Speed', 'wp-easycart' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 10000,
				'condition' => array(
					'layout_mode'      => 'slider',
					'slider_auto_play' => 'yes',
				),
			)
		);

		$this->add_control(
			'slider_center',
			array(
				'label'     => esc_attr__( 'Enable Center Mode', 'wp-easycart' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'condition' => array(
					'layout_mode' => 'slider',
				),
			)
		);

		$this->add_control(
			'product_extra_heading',
			array(
				'label'     => esc_attr__( 'Extra Options', 'wp-easycart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'product_border',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Product Border', 'wp-easycart' ),
				'description' => esc_attr__( 'Border shows where applicable (depends on product display type).', 'wp-easycart' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products_type',
			array(
				'label' => esc_attr__( 'Products Type', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'type',
			array(
				'label'   => esc_attr__( 'Type', 'wp-easycart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''       => esc_attr__( 'Theme Options', 'wp-easycart' ),
					'custom' => esc_attr__( 'Custom', 'wp-easycart' ),
				),
			)
		);

		$this->add_control(
			'product_style',
			array(
				'label'     => esc_attr__( 'Product Type', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'1'     => esc_attr__( 'Grid Type 1', 'wp-easycart' ),
					'2'     => esc_attr__( 'Grid Type 2', 'wp-easycart' ),
					'3'     => esc_attr__( 'Grid Type 3', 'wp-easycart' ),
					'4'     => esc_attr__( 'Grid Type 4', 'wp-easycart' ),
					'5'     => esc_attr__( 'Grid Type 5', 'wp-easycart' ),
					'6'     => esc_attr__( 'List Type 6', 'wp-easycart' ),
				),
				'condition' => array(
					'type' => 'custom',
				),
			)
		);

		$this->add_responsive_control(
			'product_align',
			array(
				'label'     => esc_attr__( 'Product Align', 'wp-easycart' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'options'   => array(
					'left'   => array(
						'title' => esc_attr__( 'Left', 'wp-easycart' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_attr__( 'Center', 'wp-easycart' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_attr__( 'Right', 'wp-easycart' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'condition' => array(
					'type'           => 'custom',
					'product_style!' => 'card',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_position' );

		$this->start_controls_tab(
			'tab_pos_top',
			array(
				'label'     => esc_attr__( 'Top', 'wp-easycart' ),
				'condition' => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_responsive_control(
			'body_pos_top',
			array(
				'label'      => esc_attr__( 'Top', 'wp-easycart' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 50,
					'unit' => '%',
				),
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-body' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pos_right',
			array(
				'label'     => esc_attr__( 'Right', 'wp-easycart' ),
				'condition' => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_responsive_control(
			'body_pos_right',
			array(
				'label'      => esc_attr__( 'Right', 'wp-easycart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-body' => 'right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pos_bottom',
			array(
				'label'     => esc_attr__( 'Bottom', 'wp-easycart' ),
				'condition' => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_responsive_control(
			'body_pos_bottom',
			array(
				'label'      => esc_attr__( 'Bottom', 'wp-easycart' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array(
					'px',
					'%',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 500,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-body' => 'bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pos_left',
			array(
				'label' => esc_attr__( 'Left', 'wp-easycart' ),
				'condition' => array(
					'type' => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_responsive_control(
			'body_pos_left',
			array(
				'label' => esc_attr__( 'Left', 'wp-easycart' ),
				'type' => Controls_Manager::SLIDER,
				'default' => array(
					'size' => 50,
					'unit' => '%',
				),
				'size_units' => array(
					'px',
					'%',
				),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min' => 0,
						'max' => 500,
					),
					'%' => array(
						'step' => 1,
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-body' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'type' => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'min_height',
			array(
				'type' => Controls_Manager::SLIDER,
				'label' => esc_attr__( 'Image Min Height', 'wp-easycart' ),
				'separator' => 'after',
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min' => 20,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .product-media img' => 'min-height: {{SIZE}}px; object-fit: cover',
				),
				'condition' => array(
					'type' => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_control(
			'visible_options',
			array(
				'label' => esc_attr__( 'Visible Items', 'wp-easycart' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => array(
					'title',
					'category',
					'price',
					'rating',
					'cart',
					'quickview',
					'desc',
				),
				'description' => esc_attr__( 'Short description only where available.', 'wp-easycart' ),
				'options' => array(
					'title' => esc_attr__( 'Title', 'wp-easycart' ),
					'category' => esc_attr__( 'Categories', 'wp-easycart' ),
					'price' => esc_attr__( 'Price', 'wp-easycart' ),
					'rating' => esc_attr__( 'Rating', 'wp-easycart' ),
					'cart' => esc_attr__( 'Add To Cart', 'wp-easycart' ),
					'quickview' => esc_attr__( 'Quick View', 'wp-easycart' ),
					'desc' => esc_attr__( 'Short Description', 'wp-easycart' ),
				),
				'condition' => array(
					'type' => 'custom',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners',
			array(
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Customize Product Image Corners', 'wp-easycart' ),
				'default' => 'no',
				'condition' => array(
					'type' => 'custom',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_tl',
			array(
				'type' => Controls_Manager::SLIDER,
				'label' => esc_attr__( 'Border Radius - Top-Left (px)', 'wp-easycart' ),
				'default' => array(
					'size' => 10,
				),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min' => 0,
						'max' => 50,
					),
				),
				'condition' => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_tr',
			array(
				'type' => Controls_Manager::SLIDER,
				'label' => esc_attr__( 'Border Radius - Top-Right (px)', 'wp-easycart' ),
				'default' => array(
					'size' => 10,
				),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min' => 0,
						'max' => 50,
					),
				),
				'condition' => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_bl',
			array(
				'type' => Controls_Manager::SLIDER,
				'label' => esc_attr__( 'Border Radius - Bottom-Left (px)', 'wp-easycart' ),
				'default' => array(
					'size' => 10,
				),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min' => 0,
						'max' => 50,
					),
				),
				'condition' => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_br',
			array(
				'type' => Controls_Manager::SLIDER,
				'label' => esc_attr__( 'Border Radius - Bottom-Right (px)', 'wp-easycart' ),
				'default' => array(
					'size' => 10,
				),
				'range' => array(
					'px' => array(
						'step' => 1,
						'min' => 0,
						'max' => 50,
					),
				),
				'condition' => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render product widget control output in the editor.
	 */
	protected function render() {
		$atts = $this->get_settings_for_display();
		include( EC_PLUGIN_DIRECTORY . '/admin/elementor/wp-easycart-elementor-product-widget.php' );
	}
}
