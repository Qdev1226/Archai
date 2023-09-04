<?php
/**
 * WP EasyCart Store Widget Display for Elementor
 *
 * @package  Wp_Easycart_Elementor_Store_Widget
 * @author WP EasyCart
 */

$args = shortcode_atts(
	array(
		'shortcode' => 'store',
		'title' => '',
		'title_link' => '',
		'desc' => '',
		'status' => '',
		'ids'  => '',
		'category'  => '',
		'brands' => '',
		'product_border' => false,
		'paging' => true,
		'sorting' => false,
		'sorting_default' => 0,

		'sidebar' => false,
		'sidebar_position' => 'left',
		'sidebar_filter_clear' => true,
		'sidebar_include_search' => true,
		'sidebar_include_categories' => true,
		'sidebar_include_categories_first' => true,
		'sidebar_categories'  => '',
		'sidebar_include_category_filters' => false,
		'sidebar_category_filter_id' => 0,
		'sidebar_category_filter_method' => 'AND',
		'sidebar_category_filter_open' => 1,
		'sidebar_include_option_filters' => true,
		'sidebar_option_filters' => '',

		'layout_mode' => '',
		'spacing' => '',
		'cols_upper_desktop'  => 4,
		'columns' => 4,
		'columns_tablet' => 4,
		'columns_mobile' => 3,
		'cols_under_mobile' => 2,

		'type' => '',
		'product_style'  => 'default',
		'product_align'  => 'center',
		'visible_options' => array(
			'title',
			'price',
			'rating',
			'cart',
			'quickview',
			'desc',
		),
		'product_rounded_corners'  => 'no',
		'product_rounded_corners_tl'  => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
		'product_rounded_corners_tr'  => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
		'product_rounded_corners_bl'  => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
		'product_rounded_corners_br'  => array(
			'unit' => 'px',
			'size' => '10',
			'sizes' => array(),
		),
	),
	$atts
);

$shortcode = $args['shortcode'];
$wpec_elem_title = $args['title'];
$wpec_elem_title_link = $args['title_link'];
$desc = $args['desc'];
$wpec_elem_status = $args['status'];
$ids = $args['ids'];
$category = $args['category'];
$brands = $args['brands'];
$product_border = $args['product_border'];
$paging = $args['paging'];
$sorting = $args['sorting'];
$sorting_default = $args['sorting_default'];
$sidebar = $args['sidebar'];
$sidebar_position = $args['sidebar_position'];
$sidebar_filter_clear = $args['sidebar_filter_clear'];
$sidebar_include_search = $args['sidebar_include_search'];
$sidebar_include_categories = $args['sidebar_include_categories'];
$sidebar_include_categories_first = $args['sidebar_include_categories_first'];
$sidebar_categories = $args['sidebar_categories'];
$sidebar_include_category_filters = $args['sidebar_include_category_filters'];
$sidebar_category_filter_id = $args['sidebar_category_filter_id'];
$sidebar_category_filter_method = $args['sidebar_category_filter_method'];
$sidebar_category_filter_open = $args['sidebar_category_filter_open'];
$sidebar_include_option_filters = $args['sidebar_include_option_filters'];
$sidebar_option_filters = $args['sidebar_option_filters'];
$layout_mode = $args['layout_mode'];
$spacing = $args['spacing'];
$cols_upper_desktop = $args['cols_upper_desktop'];
$columns = $args['columns'];
$columns_tablet = $args['columns_tablet'];
$columns_mobile = $args['columns_mobile'];
$cols_under_mobile = $args['cols_under_mobile'];
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
	$product_rounded_corners = 'no';
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

if ( ! isset( $product_rounded_corners_tl ) ) {
	$product_rounded_corners_tl = array(
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

$sidebar_cat_ids = array();
if ( $sidebar_categories ) {
	if ( ! is_array( $sidebar_categories ) ) {
		$sidebar_categories = explode( ',', $sidebar_categories );
	}
	$sidebar_categories_count = count( $sidebar_categories );
	for ( $i = 0; $i < $sidebar_categories_count; $i++ ) {
		$sidebar_cat_ids[] = (int) $sidebar_categories[ $i ];
	}
}

$sidebar_option_ids = array();
if ( $sidebar_option_filters ) {
	if ( ! is_array( $sidebar_option_filters ) ) {
		$sidebar_option_filters = explode( ',', $sidebar_option_filters );
	}
	$sidebar_option_filters_count = count( $sidebar_option_filters );
	for ( $i = 0; $i < $sidebar_option_filters_count; $i++ ) {
		$sidebar_option_ids[] = (int) $sidebar_option_filters[ $i ];
	}
}

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
if ( count( $sidebar_cat_ids ) ) {
	$more_atts['sidebar_categories'] = esc_attr( implode( ',', $sidebar_cat_ids ) );
}
if ( count( $sidebar_option_ids ) ) {
	$more_atts['sidebar_option_filters'] = esc_attr( implode( ',', $sidebar_option_ids ) );
}

if ( isset( $count ) && is_array( $count ) && 0 === $count['size'] ) {
	echo '<div class="wp-easycart-store-shortcode-wrapper d-flex">' . wp_easycart_escape_html( $heading_html ) . '</div>';
	return;
}

if ( $heading_html ) {
	echo wp_easycart_escape_html( $heading_html );
}
echo '<div class="wp-easycart-store-shortcode-wrapper d-flex">';

if ( isset( $count ) && $count ) {
	if ( is_array( $count ) ) {
		$more_atts['per_page'] = intval( $count['size'] );
	} else {
		$more_atts['per_page'] = intval( $count );
	}
}

$more_atts['elementor'] = true;
$more_atts['status'] = $wpec_elem_status;
$more_atts['layout_mode'] = $layout_mode;
$more_atts['product_style']  = $product_style;
$more_atts['product_align']  = $product_align;
$more_atts['product_visible_options']  = implode( ',', $visible_options );
$more_atts['product_rounded_corners']  = ( 'yes' == $product_rounded_corners ) ? 1 : 0;
$more_atts['product_rounded_corners_tl']  = (int) $product_rounded_corners_tl['size'];
$more_atts['product_rounded_corners_tr']  = (int) $product_rounded_corners_tr['size'];
$more_atts['product_rounded_corners_bl']  = (int) $product_rounded_corners_bl['size'];
$more_atts['product_rounded_corners_br']  = (int) $product_rounded_corners_br['size'];
$more_atts['product_border'] = $product_border;
$more_atts['paging'] = ( 'yes' == $paging ) ? 1 : 0;
$more_atts['sorting'] = ( 'yes' == $sorting ) ? 1 : 0;
$more_atts['sorting_default'] = $sorting_default;
$more_atts['sidebar'] = ( 'yes' == $sidebar ) ? 1 : 0;
$more_atts['sidebar_position'] = $sidebar_position;
$more_atts['sidebar_filter_clear'] = ( 'yes' == $sidebar_filter_clear ) ? 1 : 0;
$more_atts['sidebar_include_search']  = ( 'yes' == $sidebar_include_search ) ? 1 : 0;
$more_atts['sidebar_include_categories']  = ( 'yes' == $sidebar_include_categories ) ? 1 : 0;
$more_atts['sidebar_include_categories_first'] = ( 'yes' == $sidebar_include_categories_first ) ? 1 : 0;
$more_atts['sidebar_include_category_filters'] = ( 'yes' == $sidebar_include_category_filters ) ? 1 : 0;
$more_atts['sidebar_category_filter_id'] = $sidebar_category_filter_id;
$more_atts['sidebar_category_filter_method'] = ( 'AND' == $sidebar_category_filter_method ) ? 'AND' : 'OR';
$more_atts['sidebar_category_filter_open'] = (int) $sidebar_category_filter_open;
$more_atts['sidebar_include_option_filters'] = ( 'yes' == $sidebar_include_option_filters ) ? 1 : 0;

if ( $spacing ) {
	$more_atts['spacing'] = ( is_array( $spacing ) ) ? esc_attr( $spacing['size'] ) : esc_attr( $spacing );
}

$extra_atts = ' ';
foreach ( $more_atts as $key => $value ) {
	$extra_atts .= $key . '=' . json_encode( $value ) . ' ';
}

$extra_atts . "'";
echo do_shortcode( '[ec_store ' . $extra_atts . ']' );
echo '</div>';
