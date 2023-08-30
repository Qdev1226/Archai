<?php

/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package business-chat
 */

if (
    !is_active_sidebar('sidebar-1') ||
    (class_exists("WooCommerce") && function_exists("is_woocommerce") && (is_woocommerce() || is_checkout() || is_cart() || is_account_page()))
) {
    return;
}
?>

<aside id="secondary" class="featured-sidebar blog-sidebar-wrapper widget-area">
    <?php dynamic_sidebar('sidebar-1'); ?>
</aside>