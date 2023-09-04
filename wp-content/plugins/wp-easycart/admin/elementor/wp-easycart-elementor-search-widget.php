<?php
/**
 * WP EasyCart Search Widget Display for Elementor
 *
 * @package  Wp_Easycart_Elementor_Search_Widget
 * @author   WP EasyCart
 */

$args = shortcode_atts(
	array(
		'shortcode'                => 'search',
		'label'                    => 'Search Now',
		'postid'                   => 0,
	),
	$atts
);

$more_atts['label'] = $args['label'];
$more_atts['postid'] = (int) $args['postid'];

$extra_atts = ' ';
foreach ( $more_atts as $key => $value ) {
	$extra_atts .= $key . '=' . json_encode( $value ) . ' ';
}

echo '<div class="wp-easycart-search-shortcode-wrapper d-flex">';
echo do_shortcode( '[ec_search ' . $extra_atts . ']' );
echo '</div>';
