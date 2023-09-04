<?php
/**
 * WP EasyCart Product Add To Cart Widget Display for Elementor
 *
 * @package Wp_Easycart_Elementor_Product_Addtocart_Widget
 * @author WP EasyCart
 */

$args = shortcode_atts(
	array(
		'shortcode' => 'product_addtocart',
		'product_id' => '',
		'enable_quantity' => false,
		'button_width' => false,
		'button_font' => '',
		'button_bg_color' => '',
		'button_text_color' => '',
		'background_add' => '0',
	),
	$atts
);

global $wpdb;

$shortcode = $args['shortcode'];
$product_id = $args['product_id'];
$product_exists = $wpdb->get_row( $wpdb->prepare( 'SELECT product_id FROM ec_product WHERE product_id = %d', $product_id ) );
if ( ! $product_exists ) {
	$product_id = 0;
}
$enable_quantity = $args['enable_quantity'];
$button_width = ( isset( $args['button_width'] ) && isset( $args['button_width']['size'] ) ) ? (int) $args['button_width']['size'] : 150;
$button_font = $args['button_font'];
$button_bg_color = $args['button_bg_color'];
$button_text_color = $args['button_text_color'];
$background_add = $args['background_add'];

$more_atts = array();

$fonts = array();

$more_atts['is_elementor'] = 1;
$more_atts['productid'] = $product_id;
$more_atts['enable_quantity'] = ( 'yes' == $enable_quantity ) ? 1 : 0;
$more_atts['button_width'] = $button_width;
if ( '' != $button_font && '0' != $button_font ) {
	if ( ! in_array( $button_font, $fonts ) ) {
		$fonts[] = $button_font;
	}
	$more_atts['button_font'] = $button_font;
}
$more_atts['button_bg_color'] = $button_bg_color;
$more_atts['button_text_color'] = $button_text_color;
$more_atts['background_add'] = $background_add;

echo '<div class="wp-easycart-product-details-shortcode-wrapper d-flex">';

$extra_atts = ' ';
foreach ( $more_atts as $key => $value ) {
	$extra_atts .= $key . '=' . json_encode( $value ) . ' ';
}

$extra_atts . "'";

if ( count( $fonts ) > 0 ) {
	$gfont_string = 'https://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', implode( '|', $fonts ) );
	echo '<link rel="stylesheet" href="' . esc_url( $gfont_string ) . '" />';
}
echo do_shortcode( '[ec_addtocart ' . $extra_atts . ']' );
echo '</div>';
