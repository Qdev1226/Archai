<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2016, Freemius, Inc.
	 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
	 * @since       1.1.9
	 */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

	// Configuration should be loaded first.
	require_once dirname( __FILE__ ) . '/config.php';
	require_once WP_B__DIR . '/inc/Base/BSDK.php';
	require_once WP_B__DIR . '/inc/Base/License.php';
	require_once WP_B__DIR . '/inc/Base/Activate.php';
