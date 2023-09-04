<?php
/**
 * WP EasyCart Controls Manager for Elementor
 *
 * @category Class
 * @package  Wp_Easycart_Controls_Manager
 * @author   WP EasyCart
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'Elementor\Controls_Manager' ) ) {
	/**
	 * WP EasyCart Controls Manager for Elementor
	 *
	 * @category Class
	 * @package  Wp_Easycart_Controls_Manager
	 * @author   WP EasyCart
	 */
	abstract class Wp_Easycart_Controls_Manager extends Controls_Manager {
		const WPECAJAXSELECT2 = 'wpecajaxselect2';
	}
}
