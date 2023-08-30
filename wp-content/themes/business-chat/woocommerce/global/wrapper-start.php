<?php

use SuperbThemesCustomizer\CustomizerControls;

/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

echo '<div id="content" class="site-content clearfix">';
echo '<div class="content-wrap">';
echo '<div id="primary" class="content-area">';
echo '<main id="main" class="site-main featured-content' . (CustomizerControls::GetSelectedOrDefault(CustomizerControls::SIDEBAR_WOOCOMMERCE_HIDE) == '1' || !is_active_sidebar('sidebar-wc') ? " fullwidth-area-blog" : "") . '" role="main">';
