<?php
/**
 * WP EasyCart Store Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Store_Widget
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
 * WP EasyCart Store Widget for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Elementor_Store_Widget
 * @author   WP EasyCart
 */
class Wp_Easycart_Elementor_Store_Widget extends \Elementor\Widget_Base {

	/**
	 * Get store widget name.
	 */
	public function get_name() {
		return 'wp_easycart_store';
	}

	/**
	 * Get store widget title.
	 */
	public function get_title() {
		return esc_attr__( 'WP EasyCart Store', 'wp-easycart' );
	}

	/**
	 * Get store widget icon.
	 */
	public function get_icon() {
		return 'eicon-product-pages';
	}

	/**
	 * Get store widget categories.
	 */
	public function get_categories() {
		return array( 'wp-easycart-elements' );
	}

	/**
	 * Get store widget keywords.
	 */
	public function get_keywords() {
		return array( 'products', 'shop', 'wp-easycart' );
	}

	/**
	 * Enqueue store widget scripts and styles.
	 */
	public function get_script_depends() {
		$scripts = array( 'isotope-pkgd', 'jquery-hoverIntent' );
		if ( ( isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) || isset( $_REQUEST['elementor-preview'] ) ) {
			$scripts[] = 'wpeasycart_js';
		}
		return $scripts;
	}

	/**
	 * Setup store widget controls.
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
				'default' => 'featured',
				'options' => array(
					'all'       => esc_attr__( 'All', 'wp-easycart' ),
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

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products_layout',
			array(
				'label' => esc_attr__( 'Products Layout', 'wp-easycart' ),
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
			'section_store_layout',
			array(
				'label' => esc_attr__( 'Store Layout', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'paging',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Paging', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'sorting',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sorting', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'sorting_default',
			array(
				'label'     => esc_attr__( 'Sorting Selection', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => get_option( 'ec_option_default_store_filter' ),
				'options'   => array(
					'0' => __( 'Default Sorting (admin determined sort order)', 'wp-easycart' ),
					'1' => __( 'Price Low-High', 'wp-easycart' ),
					'2' => __( 'Price High-Low', 'wp-easycart' ),
					'3' => __( 'Title A-Z', 'wp-easycart' ),
					'4' => __( 'Title Z-A', 'wp-easycart' ),
					'5' => __( 'Newest First', 'wp-easycart' ),
					'6' => __( 'Best Rating First', 'wp-easycart' ),
					'7' => __( 'Most Viewed', 'wp-easycart' ),
				),
				'condition' => array(
					'sorting' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sidebar', 'wp-easycart' ),
			)
		);

		$this->add_control(
			'sidebar_position',
			array(
				'label'     => esc_attr__( 'Sorting Selection', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left' => __( 'Sidebar Left', 'wp-easycart' ),
					'right' => __( 'Sidebar Right', 'wp-easycart' ),
					'slide-left' => __( 'Slideout Left (overlay)', 'wp-easycart' ),
					'slide-right' => __( 'Slideout Right (overlay)', 'wp-easycart' ),
				),
				'condition' => array(
					'sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_filter_clear',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sidebar Filter Clear', 'wp-easycart' ),
				'default'   => 'yes',
				'condition' => array(
					'sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_include_search',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sidebar Search', 'wp-easycart' ),
				'default'   => 'yes',
				'condition' => array(
					'sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_include_categories',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sidebar Category Links', 'wp-easycart' ),
				'default'   => 'yes',
				'condition' => array(
					'sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_include_categories_first',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Sidebar Category Links First?', 'wp-easycart' ),
				'default'   => 'yes',
				'condition' => array(
					'sidebar' => 'yes',
					'sidebar_include_categories' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_categories',
			array(
				'label'       => esc_attr__( 'Select Category Links', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product_cat',
				'label_block' => true,
				'multiple'    => 'true',
				'condition' => array(
					'sidebar' => 'yes',
					'sidebar_include_categories' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_include_category_filters',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sidebar Complex Category Filters', 'wp-easycart' ),
				'default'   => 'no',
				'condition' => array(
					'sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_category_filter_id',
			array(
				'label'       => esc_attr__( 'Select top level category', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product_cat',
				'label_block' => true,
				'multiple'    => false,
				'condition' => array(
					'sidebar' => 'yes',
					'sidebar_include_category_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_category_filter_method',
			array(
				'label'     => esc_attr__( 'Filter Method', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'AND',
				'options'   => array(
					'AND' => __( 'Filter Method: AND', 'wp-easycart' ),
					'OR' => __( 'Filter Method: OR', 'wp-easycart' ),
				),
				'condition' => array(
					'sidebar' => 'yes',
					'sidebar_include_category_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_category_filter_open',
			array(
				'label'     => esc_attr__( 'Filter Method', 'wp-easycart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '1',
				'options'   => array(
					'0' => __( 'All Closed', 'wp-easycart' ),
					'1' => __( 'All Open', 'wp-easycart' ),
					'2' => __( 'First Open', 'wp-easycart' ),
				),
				'condition' => array(
					'sidebar' => 'yes',
					'sidebar_include_category_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_include_option_filters',
			array(
				'type'  => Controls_Manager::SWITCHER,
				'label' => esc_attr__( 'Enable Sidebar Option Filters', 'wp-easycart' ),
				'default'   => 'yes',
				'condition' => array(
					'sidebar' => 'yes',
				),
			)
		);

		$this->add_control(
			'sidebar_option_filters',
			array(
				'label'       => esc_attr__( 'Select options', 'wp-easycart' ),
				'type'        => Wp_Easycart_Controls_Manager::WPECAJAXSELECT2,
				'options'     => 'easycart_product_optionsets',
				'label_block' => true,
				'multiple'    => 'true',
				'condition' => array(
					'sidebar' => 'yes',
					'sidebar_include_option_filters' => 'yes',
				),
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
				'label'     => esc_attr__( 'Left', 'wp-easycart' ),
				'condition' => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_responsive_control(
			'body_pos_left',
			array(
				'label'      => esc_attr__( 'Left', 'wp-easycart' ),
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
					'{{WRAPPER}} .product-body' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'min_height',
			array(
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_attr__( 'Image Min Height', 'wp-easycart' ),
				'separator'  => 'after',
				'size_units' => array(
					'px',
					'%',
					'rem',
				),
				'range'      => array(
					'px' => array(
						'step' => 1,
						'min'  => 20,
						'max'  => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .product-media img' => 'min-height: {{SIZE}}px; object-fit: cover',
				),
				'condition'  => array(
					'type'          => 'custom',
					'product_style' => 'full',
				),
			)
		);

		$this->add_control(
			'visible_options',
			array(
				'label'       => esc_attr__( 'Visible Items', 'wp-easycart' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => array(
					'title',
					'category',
					'price',
					'rating',
					'cart',
					'quickview',
					'desc',
				),
				'description' => esc_attr__( 'Short description only where available.', 'wp-easycart' ),
				'options'     => array(
					'title'     => esc_attr__( 'Title', 'wp-easycart' ),
					'category'  => esc_attr__( 'Categories', 'wp-easycart' ),
					'price'     => esc_attr__( 'Price', 'wp-easycart' ),
					'rating'    => esc_attr__( 'Rating', 'wp-easycart' ),
					'cart'      => esc_attr__( 'Add To Cart', 'wp-easycart' ),
					'quickview' => esc_attr__( 'Quick View', 'wp-easycart' ),
					'desc'      => esc_attr__( 'Short Description', 'wp-easycart' ),
				),
				'condition'   => array(
					'type' => 'custom',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners',
			array(
				'type'        => Controls_Manager::SWITCHER,
				'label'       => esc_attr__( 'Customize Product Image Corners', 'wp-easycart' ),
				'default'     => 'no',
				'condition'   => array(
					'type' => 'custom',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_tl',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_attr__( 'Border Radius - Top-Left (px)', 'wp-easycart' ),
				'default'     => array(
					'size' => 10,
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
				),
				'condition'   => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_tr',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_attr__( 'Border Radius - Top-Right (px)', 'wp-easycart' ),
				'default'     => array(
					'size' => 10,
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
				),
				'condition'   => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_bl',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_attr__( 'Border Radius - Bottom-Left (px)', 'wp-easycart' ),
				'default'     => array(
					'size' => 10,
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
				),
				'condition'   => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->add_control(
			'product_rounded_corners_br',
			array(
				'type'        => Controls_Manager::SLIDER,
				'label'       => esc_attr__( 'Border Radius - Bottom-Right (px)', 'wp-easycart' ),
				'default'     => array(
					'size' => 10,
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 50,
					),
				),
				'condition'   => array(
					'type' => 'custom',
					'product_rounded_corners' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render store widget control output in the editor.
	 */
	protected function render() {
		$atts = $this->get_settings_for_display();
		include( EC_PLUGIN_DIRECTORY . '/admin/elementor/wp-easycart-elementor-store-widget.php' );
	}
}
