<?php
/**
 * WP EasyCart Product Widget Display for Elementor
 *
 * @package  Wp_Easycart_Elementor_Product_Widget
 * @authorWP EasyCart
 */

$args = shortcode_atts(
	array(
		'shortcode' => 'products',
		'title' => '',
		'title_link' => '',
		'desc' => '',
		'status' => '',
		'count' => 4,
		'orderby' => 'date',
		'order' => 'desc',
		'ids' => '',
		'category' => '',
		'brands' => '',
		'product_border' => false,

		'layout_mode' => '',
		'spacing' => '',
		'cols_upper_desktop' => 4,
		'columns' => 4,
		'columns_tablet' => 4,
		'columns_mobile' => 3,
		'cols_under_mobile' => 2,
		'product_slider_nav_pos' => '',
		'product_slider_nav_type' => '',
		'slider_nav' => 'no',
		'slider_nav_show' => 'yes',
		'slider_nav_tablet' => 'no',
		'slider_nav_mobile' => 'no',
		'slider_dot' => 'no',
		'slider_dot_tablet' => 'no',
		'slider_dot_mobile' => 'no',
		'slider_loop' => 'no',
		'slider_auto_play' => 'no',
		'slider_auto_play_time' => 10000,
		'slider_center' => 'no',

		'type' => '',
		'product_style' => 'default',
		'product_align' => 'center',
		'visible_options' => array(
			'title',
			'price',
			'rating',
			'cart',
			'quickview',
			'desc',
		),
		'product_rounded_corners' => 'no',
		'product_rounded_corners_tl' => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
		'product_rounded_corners_tr' => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
		'product_rounded_corners_bl' => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
		'product_rounded_corners_br' => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
	),
	$atts
);

$wpec_elem_title = $args['title'];
$wpec_elem_title_link = $args['title_link'];
$desc = $args['desc'];
$wpec_elem_status = $args['status'];
$count = $args['count'];
$wpec_elem_orderby = $args['orderby'];
$wpec_elem_order = $args['order'];
$ids = $args['ids'];
$category = $args['category'];
$brands = $args['brands'];
$product_border = $args['product_border'];
$layout_mode = $args['layout_mode'];
$spacing = $args['spacing'];
$cols_upper_desktop = $args['cols_upper_desktop'];
$columns = $args['columns'];
$columns_tablet = $args['columns_tablet'];
$columns_mobile = $args['columns_mobile'];
$cols_under_mobile = $args['cols_under_mobile'];
$product_slider_nav_pos = $args['product_slider_nav_pos'];
$product_slider_nav_type = $args['product_slider_nav_type'];
$slider_nav = $args['slider_nav'];
$slider_nav_show = $args['slider_nav_show'];
$slider_nav_tablet = $args['slider_nav_tablet'];
$slider_nav_mobile = $args['slider_nav_mobile'];
$slider_dot = $args['slider_dot'];
$slider_dot_tablet = $args['slider_dot_tablet'];
$slider_dot_mobile = $args['slider_dot_mobile'];
$slider_loop = $args['slider_loop'];
$slider_auto_play = $args['slider_auto_play'];
$slider_auto_play_time = $args['slider_auto_play_time'];
$slider_center = $args['slider_center'];
$wpec_elem_type = $args['type'];
$product_style = $args['product_style'];
$product_align = $args['product_align'];
$visible_options = $args['visible_options'];
$product_rounded_corners = $args['product_rounded_corners'];
$product_rounded_corners_tl = $args['product_rounded_corners_tl'];
$product_rounded_corners_tr = $args['product_rounded_corners_tr'];
$product_rounded_corners_bl = $args['product_rounded_corners_bl'];
$product_rounded_corners_br = $args['product_rounded_corners_br'];

if ( is_string( $wpec_elem_title_link ) ) {
	$wpec_elem_title_link = json_decode( $wpec_elem_title_link, true );
}

if ( '' == $wpec_elem_type ) {
	$product_style = '1';
	$product_align = 'center';
	$visible_options = array( 'title', 'price', 'rating', 'cart', 'quickview', 'desc' );
	$product_rounded_corners  = 'no';
	$product_rounded_corners_tl  = array(
		'unit' => 'px',
		'size' => '10',
		'sizes' => array(),
	);
	$product_rounded_corners_tr  = array(
		'unit' => 'px',
		'size' => '10',
		'sizes' => array(),
	);
	$product_rounded_corners_bl  = array(
		'unit' => 'px',
		'size' => '10',
		'sizes' => array(),
	);
	$product_rounded_corners_br  = array(
		'unit' => 'px',
		'size' => '10',
		'sizes' => array(),
	);

} else if ( is_string( $visible_options ) ) {
	$visible_options = explode( ',', $visible_options );

}

$heading_html = '';

$more_atts = array();

if ( $wpec_elem_title ) {
	$heading_html = $wpec_elem_title;

	if ( $wpec_elem_title_link && isset( $wpec_elem_title_link['url'] ) && $wpec_elem_title_link['url'] ) {
		$heading_html = sprintf( '<a href="%1$s"' . ( $wpec_elem_title_link['is_external'] ? ' target="nofollow"' : '' ) . ( $wpec_elem_title_link['nofollow'] ? ' rel="_blank"' : '' ) . '>%2$s</a>', esc_url( $wpec_elem_title_link['url'] ), $heading_html );
	}

	$heading_html = '<h2 class="heading-title">' . $heading_html . '</h2>';
}

if ( $desc ) {
	$heading_html .= '<p class="heading-desc">' . $desc . '</p>';
}

if ( $heading_html ) {
	$heading_html = '<div class="title-wrapper">' . $heading_html . '</div>';
}

$product_ids = array();
if ( $ids ) {
	if ( ! is_array( $ids ) ) {
		$ids = str_replace( ' ', '', $ids );
		$ids = explode( ',', $ids );
	}
	$ids_count = count( $ids );
	for ( $i = 0; $i < $ids_count; $i++ ) {
		$product_ids[] = $ids[ $i ];
	}
}

$cat_ids = array();
if ( $category ) {
	if ( ! is_array( $category ) ) {
		$category = explode( ',', $category );
	}
	$category_count = count( $category );
	for ( $i = 0; $i < $category_count; $i++ ) {
		$cat_ids[] = $category[ $i ];
	}
}

$man_ids = array();
if ( $brands ) {
	if ( ! is_array( $brands ) ) {
		$brands = explode( ',', $brands );
	}
	$brands_count = count( $brands );
	for ( $i = 0; $i < $brands_count; $i++ ) {
		$man_ids[] = $brands[ $i ];
	}
}

if ( $heading_html ) {
	echo wp_easycart_escape_html( $heading_html ); // XSS OK.
}
echo '<div class="wp-easycart-product-shortcode-wrapper d-flex">';

$more_atts['columns'] = ( ! $columns || '' == $columns ) ? 4 : intval( $columns );
$more_atts['cols_tablet'] = ( ! $columns_tablet || '' == $columns_tablet ) ? $more_atts['columns'] : intval( $columns_tablet );
$more_atts['cols_desktop'] = ( ! $cols_upper_desktop || '' == $cols_upper_desktop ) ? $more_atts['columns'] : intval( $cols_upper_desktop );
$more_atts['cols_mobile_small'] = ( ! $cols_under_mobile || '' == $cols_under_mobile ) ? 3 : intval( $cols_under_mobile );
$more_atts['cols_mobile'] = ( ! $columns_mobile || '' == $columns_mobile ) ? $more_atts['cols_mobile_small'] : intval( $columns_mobile );

if ( $product_ids ) {
	$more_atts['productid'] = esc_attr( implode( ',', $product_ids ) );
}
if ( count( $cat_ids ) ) {
	$more_atts['category'] = esc_attr( implode( ',', $cat_ids ) );
}
if ( count( $man_ids ) ) {
	$more_atts['manufacturer'] = esc_attr( implode( ',', $man_ids ) );
}
if ( $wpec_elem_orderby ) {
	$more_atts['orderby'] = esc_attr( $wpec_elem_orderby );
}
if ( $wpec_elem_order ) {
	$more_atts['order'] = esc_attr( $wpec_elem_order );
}

if ( is_array( $count ) && 0 === $count['size'] ) {
	echo '</div>';
	return;
}

if ( $count ) {
	if ( is_array( $count ) ) {
		$more_atts['per_page'] = intval( $count['size'] );
	} else {
		$more_atts['per_page'] = intval( $count );
	}
}

$more_atts['status'] = $wpec_elem_status;
$more_atts['layout_mode'] = $layout_mode;
$more_atts['product_style'] = $product_style;
$more_atts['product_align'] = $product_align;
$more_atts['product_visible_options'] = implode( ',', $visible_options );
$more_atts['product_rounded_corners'] = ( 'yes' == $product_rounded_corners ) ? 1 : 0;
$more_atts['product_rounded_corners_tl'] = (int) $product_rounded_corners_tl['size'];
$more_atts['product_rounded_corners_tr'] = (int) $product_rounded_corners_tr['size'];
$more_atts['product_rounded_corners_bl'] = (int) $product_rounded_corners_bl['size'];
$more_atts['product_rounded_corners_br'] = (int) $product_rounded_corners_br['size'];
$more_atts['product_border'] = $product_border;
$more_atts['product_slider_nav_pos'] = $product_slider_nav_pos;
$more_atts['product_slider_nav_type'] = $product_slider_nav_type;
$more_atts['slider_nav'] = ( ( true === $slider_nav || 'yes' == $slider_nav ) ? 1 : 0 );
$more_atts['slider_nav_show'] = ( 'yes' == $slider_nav_show ) ? 1 : 0;
$more_atts['slider_nav_tablet'] = ( 'yes' == $slider_nav_tablet ) ? 1 : 0;
$more_atts['slider_nav_mobile'] = ( 'yes' == $slider_nav_mobile ) ? 1 : 0;
$more_atts['slider_dot'] = ( 'yes' == $slider_dot ) ? 1 : 0;
$more_atts['slider_dot_tablet'] = ( 'yes' == $slider_dot_tablet ) ? 1 : 0;
$more_atts['slider_dot_mobile'] = ( 'yes' == $slider_dot_mobile ) ? 1 : 0;
$more_atts['slider_loop'] = ( 'yes' == $slider_loop ) ? 1 : 0;
$more_atts['slider_auto_play'] = ( 'yes' == $slider_auto_play ) ? 1 : 0;
$more_atts['slider_auto_play_time'] = $slider_auto_play_time;
$more_atts['slider_center'] = ( 'yes' == $slider_center ) ? 1 : 0;
if ( $spacing ) {
	$more_atts['spacing']  = ( is_array( $spacing ) ) ? esc_attr( $spacing['size'] ) : esc_attr( $spacing );
}

$extra_atts = ' ';
foreach ( $more_atts as $key => $value ) {
	$extra_atts .= $key . '=' . json_encode( $value ) . ' ';
}

$extra_atts . "'";
echo do_shortcode( '[ec_product ' . $extra_atts . ']' );
echo '</div>';
