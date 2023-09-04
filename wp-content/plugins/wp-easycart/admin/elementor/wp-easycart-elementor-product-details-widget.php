<?php
/**
 * WP EasyCart Product Details Widget Display for Elementor
 *
 * @package Wp_Easycart_Elementor_Product_Details_Widget
 * @author WP EasyCart
 */

$args = shortcode_atts(
	array(
		'shortcode' => 'product_details',
		'product_id' => '',
		'cols_upper_desktop' => 2,
		'columns' => 2,
		'columns_tablet' => 2,
		'columns_mobile' => 1,
		'cols_mobile_small' => 1,
		'cols_under_mobile' => 1,
		'details_sizing' => false,
		'breadcrumbs' => true,
		'image_hover' => true,
		'lightbox' => true,
		'thumbnails' => true,
		'title' => true,
		'title_font' => '',
		'title_color' => '',
		'title_divider_color' => '',
		'price_font' => '',
		'price_color' => '',
		'list_price_font' => '',
		'list_price_color' => '',
		'add_to_cart_color' => '',
		'customer_reviews' => true,
		'price' => true,
		'price_font' => '',
		'short_description' => true,
		'model_number' => true,
		'categories' => true,
		'manufacturer' => true,
		'stock' => true,
		'social' => true,
		'description' => true,
		'specifications' => true,
		'related_products' => true,
		'background_add' => false
	),
	$atts
);

$shortcode = $args['shortcode'];
$product_id = $args['product_id'];
$cols_upper_desktop = $args['cols_upper_desktop'];
$columns = $args['columns'];
$columns_tablet = $args['columns_tablet'];
$columns_mobile = $args['columns_mobile'];
$cols_under_mobile = $args['cols_under_mobile'];
$details_sizing = ( isset( $args['details_sizing'] ) && isset( $args['details_sizing']['size'] ) ) ? (int) $args['details_sizing']['size'] : (int) get_option( 'ec_option_product_details_sizing' );
$breadcrumbs = $args['breadcrumbs'];
$image_hover = $args['image_hover'];
$lightbox = $args['lightbox'];
$thumbnails = $args['thumbnails'];
$show_title = $args['title'];
$title_font = $args['title_font'];
$title_color = $args['title_color'];
$title_divider_color = $args['title_divider_color'];
$price_font = $args['price_font'];
$price_color = $args['price_color'];
$list_price_font = $args['list_price_font'];
$list_price_color = $args['list_price_color'];
$add_to_cart_color = $args['add_to_cart_color'];
$customer_reviews = $args['customer_reviews'];
$price = $args['price'];
$price_font = $args['price_font'];
$short_description = $args['short_description'];
$show_model_number = $args['model_number'];
$categories = $args['categories'];
$manufacturer = $args['manufacturer'];
$stock = $args['stock'];
$social = $args['social'];
$description = $args['description'];
$specifications = $args['specifications'];
$related_products = $args['related_products'];
$background_add = $args['background_add'];

$more_atts = array();

global $wpdb;
$model_number = $wpdb->get_var( $wpdb->prepare( 'SELECT model_number FROM ec_product WHERE product_id = %d', (int) $product_id ) );
if ( ! $model_number ) {
	$model_number = $wpdb->get_var( 'SELECT model_number FROM ec_product' );
}

$fonts = array();

$more_atts['is_elementor'] = 1;
$more_atts['cols_desktop'] = ( ! $cols_upper_desktop || '' == $cols_upper_desktop ) ? $more_atts['columns'] : intval( $cols_upper_desktop );
$more_atts['columns'] = ( ! $columns || '' == $columns ) ? 4 : intval( $columns );
$more_atts['cols_tablet'] = ( ! $columns_tablet || '' == $columns_tablet ) ? $more_atts['columns'] : intval( $columns_tablet );
$more_atts['cols_mobile_small'] = ( ! $cols_under_mobile || '' == $cols_under_mobile ) ? 3 : intval( $cols_under_mobile );
$more_atts['cols_mobile'] = ( ! $columns_mobile || '' == $columns_mobile ) ? $more_atts['cols_mobile_small'] : intval( $columns_mobile );
$more_atts['cols_under_mobile'] = ( ! $cols_under_mobile || '' == $cols_under_mobile ) ? $more_atts['cols_mobile'] : intval( $cols_under_mobile );
$more_atts['details_sizing'] = $details_sizing;
$more_atts['show_breadcrumbs'] = ( 'yes' == $breadcrumbs ) ? 1 : 0;
$more_atts['show_image_hover'] = ( 'yes' == $image_hover ) ? 1 : 0;
$more_atts['show_lightbox'] = ( 'yes' == $lightbox ) ? 1 : 0;
$more_atts['show_thumbnails'] = ( 'yes' == $thumbnails ) ? 1 : 0;
$more_atts['show_title'] = ( 'yes' == $show_title ) ? 1 : 0;
if ( '' != $title_font ) {
	if ( ! in_array( $title_font, $fonts ) ) {
		$fonts[] = $title_font;
	}
	$more_atts['title_font'] = $title_font;
}
$more_atts['title_color'] = $title_color;
$more_atts['title_divider_color'] = $title_divider_color;
if ( '' != $price_font ) {
	if ( ! in_array( $price_font, $fonts ) ) {
		$fonts[] = $price_font;
	}
	$more_atts['price_font'] = $price_font;
}
$more_atts['price_color'] = $price_color;
if ( '' != $list_price_font ) {
	if ( ! in_array( $list_price_font, $fonts ) ) {
		$fonts[] = $list_price_font;
	}
	$more_atts['list_price_font'] = $list_price_font;
}
$more_atts['list_price_color'] = $list_price_color;
$more_atts['add_to_cart_color'] = $add_to_cart_color;
$more_atts['show_customer_reviews'] = ( 'yes' == $customer_reviews ) ? 1 : 0;
$more_atts['show_price'] = ( 'yes' == $price ) ? 1 : 0;
if ( '' != $price_font ) {
	if ( ! in_array( $price_font, $fonts ) ) {
		$fonts[] = $price_font;
	}
	$more_atts['price_font'] = $price_font;
}
$more_atts['show_short_description'] = ( 'yes' == $short_description ) ? 1 : 0;
$more_atts['show_model_number'] = ( 'yes' == $show_model_number ) ? 1 : 0;
$more_atts['show_categories'] = ( 'yes' == $categories ) ? 1 : 0;
$more_atts['show_manufacturer'] = ( 'yes' == $manufacturer ) ? 1 : 0;
$more_atts['show_stock'] = ( 'yes' == $stock ) ? 1 : 0;
$more_atts['show_social'] = ( 'yes' == $social ) ? 1 : 0;
$more_atts['show_description'] = ( 'yes' == $description ) ? 1 : 0;
$more_atts['show_specifications'] = ( 'yes' == $specifications ) ? 1 : 0;
$more_atts['show_related_products'] = ( 'yes' == $related_products ) ? 1 : 0;
$more_atts['background_add'] = ( 'yes' == $background_add ) ? 1 : 0;

echo '<div class="wp-easycart-product-details-shortcode-wrapper d-flex">';

$more_atts['elementor'] = true;
if ( $model_number ) {
	$more_atts['modelnumber'] = $model_number;
}

$extra_atts = ' ';
foreach ( $more_atts as $key => $value ) {
	$extra_atts .= $key . '=' . json_encode( $value ) . ' ';
}

$extra_atts . "'";

if ( count( $fonts ) > 0 ) {
	$gfont_string = 'https://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', implode( '|', $fonts ) );
	echo '<link rel="stylesheet" href="' . esc_url( $gfont_string ) . '" />';
}
echo do_shortcode( '[ec_store ' . $extra_atts . ']' );
echo '</div>';
