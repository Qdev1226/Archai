<?php

use SuperbThemesCustomizer\CustomizerControls;

/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-end.php.
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
echo '</main>';
echo '</div>';
if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::SIDEBAR_WOOCOMMERCE_HIDE) != '1' && is_active_sidebar('sidebar-wc') && !is_account_page() && !is_cart() && !is_checkout()) :
    echo '<aside id="secondary" class="featured-sidebar wc-sidebar-wrapper widget-area">';
    dynamic_sidebar('sidebar-wc');
    echo '</aside>';
endif;
echo '</div>';
echo '</div>';
