<?php

add_action( 'foundation_enqueue_scripts', 'bauhaus_enqueue_scripts' );
add_filter( 'amp_should_show_featured_image_in_header', 'bauhaus_should_show_thumbnail' );
add_action( 'wp_loaded', 'bauhaus_featured_setup' );
add_filter( 'bauhaus_featured_show', 'bauhaus_show_featured_slider_in_page', 10, 2 );
global $bauhaus_featured_args;
global $bauhaus_featured_posts;

function bauhaus_enqueue_scripts() {
	wp_enqueue_script(
		'bauhaus-js',
		BAUHAUS_URL . '/default/bauhaus.min.js',
		array( 'jquery' ),
		BAUHAUS_THEME_VERSION,
		true
	);
}

function bauhaus_should_show_thumbnail() {
	$settings = bauhaus_get_settings();

	switch( $settings->bauhaus_use_thumbnails ) {
		case 'none':
			return false;
		case 'index':
			return is_home();
		case 'index_single':
			return is_home() || is_single();
		case 'index_single_page':
			return is_home() || is_single() || is_page();
		case 'all':
			return is_home() || is_single() || is_page() || is_archive() || is_search();
		default:
			// in case we add one at some point
			return false;
	}
}

function bauhaus_should_show_taxonomy() {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_show_taxonomy ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_should_show_date(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_show_date ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_should_show_author(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_show_author ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_should_show_search(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_show_search ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_should_show_comments(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_show_comment_bubbles ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_is_menu_position_default(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_menu_position == 'left-side' ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_is_latest_only(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_featured_carousel_enabled == false && $settings->bauhaus_popular_enabled == false ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_get_featured_image( $post = false ) {
	if ( !$post ) {
		global $post;
	}

	$image = get_the_post_thumbnail( $post->ID, 'foundation-featured-image' );

    if ( preg_match( '#src=\"(.*)\"#iU', $image, $matches ) ) {
      $image = $matches[1];

      $our_size = sprintf( "%d", WPTOUCH_FEATURED_SIZE );
      if ( strpos( $image, $our_size ) === false ) {
        // It's not our image, so just use the WP medium size
        $image = get_the_post_thumbnail( $post->ID, 'large' );
        if ( preg_match( '#src=\"(.*)\"#iU', $image, $matches ) ) {
          $image = $matches[1];
        }
      }
    }

    return apply_filters( 'wptouch_get_post_thumbnail', $image );
}

function bauhaus_featured_setup() {
	$settings = bauhaus_get_settings();
	if ( wptouch_is_showing_mobile_theme_on_mobile_device() && !is_admin() ) {
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
		}

		global $bauhaus_featured_posts;
		$settings = bauhaus_get_settings();
		$args = bauhaus_featured_get_args();

		$slides = bauhaus_featured_get_posts();

		$slide_count = 0;
		if ( $slides->post_count > 0 ) {
			while ( $slides->have_posts() ) {//} && $slide_count < $args[ 'num' ] ) {
				$slides->the_post();
				$image = bauhaus_featured_has_image();
				$slide_count++;
				$bauhaus_featured_posts[] = get_the_ID();
			}
		}

		add_filter( 'parse_query', 'bauhaus_featured_modify_query' );
	}
}

function bauhaus_featured_modify_query( $query ) {
	if ( $query->is_main_query() ) {
		$settings = bauhaus_get_settings();

		if ( bauhaus_is_latest_only() || $settings->bauhaus_featured_filter_posts ) {
			return;
		}

		if ( !$settings->bauhaus_featured_filter_posts && !$settings->bauhaus_featured_enabled ) {
			return;
		}

		$should_modify_query = apply_filters(
			'bauhaus_featured_should_modify_query',
			( $query->is_single || $query->is_page || $query->is_feed || $query->is_search || $query->is_archive || $query->is_category ) == false,
			$query
		);

		if ( $should_modify_query === false ) {
			return;
		}

		global $bauhaus_featured_posts;

		$post_array = array();

		$post_count = 1;

		if ( is_array( $bauhaus_featured_posts ) && count( $bauhaus_featured_posts ) > 0 ) {
			foreach( $bauhaus_featured_posts as $post_id ) {
				$post_array[] = $post_id;

				// ensure that we're not excluding more posts than in the slider
				$post_count++;
				if ( $post_count > $settings->bauhaus_featured_max_number_of_posts ) {
					break;
				}
			}
		}

		$query->query_vars[ 'post__not_in']  = $post_array;

		return $query;
	}
}

function bauhaus_featured_get_args() {
	$settings = bauhaus_get_settings();
	$max_posts = $settings->bauhaus_featured_max_number_of_posts;

	global $bauhaus_featured_args;

	$defaults = array(
		'type' => 'post',
		'num' => $max_posts,
		'before' => '',
		'after' => '',
		'max_search' => $max_posts * 1.5,
		'ignore_sticky_posts' => 1
	);
	// Parse defaults into arguments
	return wp_parse_args( $bauhaus_featured_args, $defaults );
}

function bauhaus_featured_get_posts() {
	global $post;

	$settings = bauhaus_get_settings();

	$bauhaus_featured_posts = array();

	$args = bauhaus_featured_get_args();

	$new_posts = false;
	switch( $settings->bauhaus_featured_type ) {
		case 'tag':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'tag' => $settings->bauhaus_featured_tag, 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			break;
		case 'category':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'category_name' => $settings->bauhaus_featured_category, 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			break;
		case 'posts':
			if ( function_exists( 'wptouch_custom_posts_add_to_search' ) ) {
				$post_types = wptouch_custom_posts_add_to_search( array( 'post', 'page' ) );
			} else {
				$post_types = array( 'post', 'page' );
			}
			$post_ids = explode( ',', str_replace( ' ', '', $settings->bauhaus_featured_post_ids ) );
			if ( is_array( $post_ids ) && count( $post_ids ) ) {
				$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'post__in'  => $post_ids, 'posts_per_page' => $args[ 'max_search' ], 'post_type' => $post_types, 'orderby' => 'post__in' , 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			}
			break;
		case 'post_type':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'post_type' => $settings->bauhaus_featured_post_type, 'posts_per_page' => $args[ 'max_search' ], 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			break;
		case 'latest':
		default:
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'meta_query' => array( array( 'key' => '_thumbnail_id' ) ) ) );
			break;
	}

	return $new_posts;
}

function bauhaus_featured_has_image( $post = false ) {
	if ( !$post ) {
		global $post;
	}

	$image = get_the_post_thumbnail( $post->ID, 'large' );

	if ( $image ) {
		return true;
	} else {
		return false;
	}
}

function bauhaus_should_show_featured() {
	$settings = bauhaus_get_settings();
	$should_show = ( ( is_home() || is_front_page() ) && $settings->bauhaus_featured_enabled );

	return apply_filters( 'bauhaus_featured_show', $should_show, $settings->bauhaus_featured_enabled );
}

function bauhaus_should_show_carousel_featured() {
	$settings = bauhaus_get_settings();
	$should_show = ( ( is_home() || is_front_page() ) && $settings->bauhaus_featured_carousel_enabled );

	return apply_filters( 'bauhaus_featured_carousel_show', $should_show, $settings->bauhaus_featured_carousel_enabled );
}

function bauhaus_featured_slider() {
	global $bauhaus_featured_posts;

	// ensure at least one featured post has been found
	if ( $bauhaus_featured_posts === null || count($bauhaus_featured_posts) == 0 ) {
		return;
	}

	$settings = bauhaus_get_settings();
	$args = bauhaus_featured_get_args();
	$classes = array();
	if ( $settings->bauhaus_post_listing_autoplay ) {
		$classes[] = 'autoplay';
	}

	if ( $settings->bauhaus_post_listing_dots ) {
		$classes[] = 'dots';
	}

	if ( function_exists( 'wptouch_custom_posts_add_to_search' ) ) {
		$post_types = wptouch_custom_posts_add_to_search( array( 'post', 'page' ) );
	} else {
		$post_types = array( 'post', 'page' );
	}

	$slides = new WP_Query( array( 'ignore_sticky_posts' => 1, 'post__in' => $bauhaus_featured_posts, 'post_type' => $post_types, 'posts_per_page' => $settings->bauhaus_featured_max_number_of_posts ) );

	if ( $slides->post_count > 0 ) {

		echo '<div class="carousel list-carousel ' . esc_attr( implode( ' ', $classes ) ) .'">';

		while ( $slides->have_posts() ) {
			$slides->the_post();
			echo '<div class="carousel-cell">';
			get_template_part( 'layouts/carousel-post-loop' );
			echo '</div>';
		}

		echo '</div>';

	}
}

function bauhaus_show_featured_slider_in_page( $show_featured_slider, $featured_slider_enabled ) {
	if ( bauhaus_allow_featured_slider_override() && $featured_slider_enabled == true ) {
		$settings = bauhaus_get_settings();

		global $post;
		if ( $settings->featured_slider_page !== false && $post->ID == $settings->featured_slider_page ) {
			$show_featured_slider = true;
		} elseif ( $settings->featured_slider_page == true ) {
			$show_featured_slider = false;
		}
	}

	return $show_featured_slider;
}

function bauhaus_allow_featured_slider_override() {
	$settings = wptouch_get_settings();
	$foundation_settings = foundation_get_settings();
	return $settings->homepage_landing != 'none' && $settings->homepage_landing != $foundation_settings->latest_posts_page;
}
