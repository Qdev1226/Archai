<?php
/**
 * Plugin Name: Gosign - Contact Person Box Block
 * Plugin URI: Plugin URI: https://www.gosign.de/plugins/contact-person-box-block
 * Description: Gosign contact-person-box-block — is a Gutenberg created by Gosign. It helps in creating contact person and profile block.
 * Author: Gosign.de
 * Author URI: https://www.gosign.de/wordpress-agentur/
 * Version: 1.3.1
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
