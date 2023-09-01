<?php

add_action( 'wp_loaded', 'foundation_featured_setup' );
add_action( 'foundation_module_init_mobile', 'foundation_featured_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_featured_settings' );

global $foundation_featured_args;

function foundation_featured_use_swipe() {
	return apply_filters( 'foundation_featured_use_swipe', true ) === true;
}

function foundation_featured_init() {
	if ( foundation_featured_use_swipe() ) {
		$settings = foundation_get_settings();
		if ( $settings->featured_enabled ) {
			wp_enqueue_script(
				'foundation_featured',
				foundation_get_base_module_url() . '/featured/swipe.min.js',
				false,
				md5( FOUNDATION_VERSION ),
				true
			);

			wp_enqueue_script(
				'foundation_featured_init',
				foundation_get_base_module_url() . '/featured/wptouch-swipe.min.js',
				'foundation_featured',
				md5( FOUNDATION_VERSION ),
				true
			);
		}
	} else {
		wp_enqueue_script(
			'foundation_featured',
			foundation_get_base_module_url() . '/featured/wptouch-owl.min.js',
			false,
			md5( FOUNDATION_VERSION ),
			true
		);
	}
}

function foundation_featured_setup() {
	$settings = foundation_get_settings();
	if ( wptouch_is_showing_mobile_theme_on_mobile_device() && !is_admin() && $settings->featured_enabled ) {
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
			add_image_size( 'foundation-featured-image', 900, 9999, false );
		}

		global $foundation_featured_posts;
		$settings = foundation_get_settings();
		$args = foundation_featured_get_args();

		$slides = foundation_featured_get_slides();

		$slide_count = 0;
		if ( $slides->post_count > 0 ) {
			while ( $slides->have_posts() ) {//} && $slide_count < $args[ 'num' ] ) {
				$slides->the_post();
				$image = foundation_featured_has_image();
				if ( apply_filters( 'wptouch_has_post_thumbnail', $image ) ) {
					$slide_count++;
					$foundation_featured_posts[] = get_the_ID();
				}
			}
		}

		add_filter( 'parse_query', 'foundation_featured_modify_query' );
	}
}

function foundation_featured_config( $args ) {
	global $foundation_featured_args;

	$foundation_featured_args = $args;
}

function foundation_featured_modify_query( $query ) {
	if ( $query->is_main_query() ) {
		$settings = foundation_get_settings();

		if ( $settings->featured_filter_posts ) {
			return;
		}

		$should_modify_query = apply_filters(
			'foundation_featured_should_modify_query',
			( $query->is_single || $query->is_page || $query->is_feed || $query->is_search || $query->is_archive || $query->is_category ) == false,
			$query
		);

		if ( $should_modify_query === false ) {
			return;
		}

		global $foundation_featured_posts;

		$post_array = array();

		if ( is_array( $foundation_featured_posts ) && count( (array) $foundation_featured_posts ) > 0 ) {
			foreach( $foundation_featured_posts as $post_id ) {
				$post_array[] = '-' . $post_id;
			}
		}

		$query->query_vars[ 'post__not_in']  = $post_array;

		return $query;
	}
}

function foundation_featured_get_args() {
	$settings = foundation_get_settings();
	$max_posts = $settings->featured_max_number_of_posts;

	global $foundation_featured_args;

	$defaults = array(
		'type' => 'post',
		'num' => $max_posts,
		'show_dots' => true,		// might not be needed
		'before' => '',
		'after' => '',
		'max_search' => $max_posts * 1.5,
		'ignore_sticky_posts' => 1
	);
	// Parse defaults into arguments
	return wp_parse_args( $foundation_featured_args, $defaults );
}

function foundation_featured_get_slides() {
	global $post;

	$settings = foundation_get_settings();

	$foundation_featured_posts = array();
	$foundation_featured_data = array();

	$args = foundation_featured_get_args();

	$new_posts = false;
	switch( $settings->featured_type ) {
		case 'tag':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'tag' => $settings->featured_tag, 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			break;
		case 'category':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'category_name' => $settings->featured_category, 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			break;
		case 'posts':
			if ( function_exists( 'wptouch_custom_posts_add_to_search' ) ) {
				$post_types = wptouch_custom_posts_add_to_search( array( 'post', 'page' ) );
			} else {
				$post_types = array( 'post', 'page' );
			}
			$post_ids = explode( ',', str_replace( ' ', '', $settings->featured_post_ids ) );
			if ( is_array( $post_ids ) && count( (array) $post_ids ) ) {
				$new_posts = new WP_Query( array( 'post__in'  => $post_ids, 'posts_per_page' => $args[ 'max_search' ], 'post_type' => $post_types, 'orderby' => 'post__in' , 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			}
			break;
		case 'post_type':
			$new_posts = new WP_Query( array( 'post_type' => $settings->featured_post_type, 'posts_per_page' => $args[ 'max_search' ], 'meta_query' => array( array('key' => '_thumbnail_id') ) ) );
			break;
		case 'latest':
		default:
			break;
	}

	if ( !$new_posts ) {
		$new_posts = new WP_Query( array( 'posts_per_page' => $args[ 'max_search' ], 'meta_query' => array( array( 'key' => '_thumbnail_id' ) ) ) );
	}

	return $new_posts;
}

function foundation_featured_has_image( $post = false ) {
	if ( !$post ) {
		global $post;
	}

	$settings = foundation_get_settings();

	$image = get_the_post_thumbnail( $post->ID, 'foundation-featured-image' );

	if ( $image ) {
		return true;
	} else {
		return false;
	}
}

function foundation_featured_get_image( $post = false ) {
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

function featured_should_show_slider() {
	$settings = foundation_get_settings();
	$should_show = ( ( is_home() && $settings->featured_blog || is_front_page() && $settings->featured_homepage ) && $settings->featured_enabled );

	return apply_filters( 'foundation_featured_show', $should_show, $settings->featured_enabled );
}

function foundation_featured_get_slider_classes() {
	$settings = foundation_get_settings();

	if ( foundation_featured_use_swipe() ) {
		$featured_classes = array( 'swipe' );
	} else {
		$featured_classes = array( 'owl-carousel' );
	}

	if ( $settings->featured_grayscale ) {
		$featured_classes[] = 'grayscale';
	}

	if ( $settings->featured_style == 'enhanced' ) {
		$featured_classes[] = 'enhanced';
	} else {
		$featured_classes[] = 'normal';
	}

	if ( $settings->featured_autoslide ) {
		$featured_classes[] = 'slide';
	}

	if ( $settings->featured_comments ) {
		$featured_classes[] = 'comments';
	}

	if ( $settings->featured_continuous ) {
		$featured_classes[] = 'continuous';
	}

	switch( $settings->featured_speed ) {
		case 'slow':
			$featured_classes[] = 'slow';
			break;
		case 'fast':
			$featured_classes[] = 'fast';
			break;
	}

	return $featured_classes;
}

function foundation_featured_prefix( $args ) {
	$allowed_html = wptouch_allowed_html();

	echo wp_kses($args['before'],$allowed_html);

	if ( foundation_featured_use_swipe() ) {
		echo wp_kses("<div id='slider' class='" . implode( ' ', foundation_featured_get_slider_classes() ) . "'>\n",$allowed_html);
		echo "<div class='swipe-wrap'>\n";
	} else {
		echo wp_kses('<div id="slider" class="' . implode( ' ', foundation_featured_get_slider_classes() ) . '">',$allowed_html);
	}
}

function foundation_featured_postfix( $args ) {
	if ( foundation_featured_use_swipe() ) {
		echo "</div>\n";
		echo "</div>\n";
	} else {
		echo '</div>';
	}
	$allowed_html = wptouch_allowed_html();
	echo wp_kses($args['after'],$allowed_html);
}

function foundation_featured_slider() {
	global $foundation_featured_posts;
	$settings = foundation_get_settings();
	$args = foundation_featured_get_args();

	if ( featured_should_show_slider() ) {
		if ( function_exists( 'wptouch_custom_posts_add_to_search' ) ) {
			$post_types = wptouch_custom_posts_add_to_search( array( 'post', 'page' ) );
		} else {
			$post_types = array( 'post', 'page' );
		}

		$slides = new WP_Query( array( 'ignore_sticky_posts' => 1, 'post__in' => $foundation_featured_posts, 'post_type' => $post_types, 'posts_per_page' => $settings->featured_max_number_of_posts ) );

		if ( $slides->post_count > 0 ) {

			foundation_featured_prefix( $args );

			while ( $slides->have_posts() ) {
				$slides->the_post();
				$image = foundation_featured_has_image();
				if ( apply_filters( 'wptouch_has_post_thumbnail', $image ) ) {
					get_template_part( 'featured-slider' );
				}
			}

			foundation_featured_postfix( $args );
		}
	}
}

function foundation_featured_settings( $page_options ) {
	$settings = foundation_get_settings();
	global $wptouch_pro;

	if ( $wptouch_pro->get_current_theme() == 'bauhaus' || ( $wptouch_pro->is_child_theme() && $wptouch_pro->get_parent_theme_info()->base == 'bauhaus' ) ) {
		$featured_enhanced_setting = array(
			wptouch_add_setting(
				'checkbox',
				'featured_blog',
				__( 'Show on blog', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'2.0'
			),
			wptouch_add_setting(
				'checkbox',
				'featured_homepage',
				__( 'Show on homepage', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'2.0'
			),
			wptouch_add_setting(
				'list',
				'featured_style',
				__( 'Featured slider style', 'wptouch-pro' ),
				false,
				WPTOUCH_SETTING_BASIC,
				'2.0',
				array(
					'enhanced' => __( 'Enhanced', 'wptouch-pro' ),
					'streamlined' => __( 'Streamlined', 'wptouch-pro' )
				)
			),
		);
	} else {
		$featured_enhanced_setting = array();
	}

	$featured_settings = array(
		wptouch_add_pro_setting(
			'list',
			'featured_speed',
			__( 'Slide transition Delay', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'slow' => __( 'Long', 'wptouch-pro' ),
				'normal' => __( 'Normal', 'wptouch-pro' ),
				'fast' => __( 'Short', 'wptouch-pro' )
			)
		),
		wptouch_add_pro_setting(
			'range',
			'featured_max_number_of_posts',
			__( 'Number of posts in slider', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'min' => 1,
				'max' => 15,
				'step' => 1
			)
		),
		wptouch_add_setting(
			'checkbox',
			'featured_comments',
			__( 'Show # of comments', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_setting(
			'checkbox',
			'featured_autoslide',
			__( 'Slide automatically', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_setting(
			'checkbox',
			'featured_continuous',
			__( 'Slides repeat', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_pro_setting(
			'checkbox',
			'featured_grayscale',
			__( 'Make images grayscale', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_setting(
			'checkbox',
			'featured_filter_posts',
			__( 'Slider posts also show in listings', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_setting(
			'list',
			'featured_type',
			__( 'Posts to display', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'latest' => __( 'Show latest posts', 'wptouch-pro' ),
				'tag' => __( 'Show posts from a specific tag', 'wptouch-pro' ),
				'category' => __( 'Show posts from a specific category', 'wptouch-pro' ),
				'post_type' => __( 'Show posts from a specific post type', 'wptouch-pro' ),
				'posts' => __( 'Show only specific posts or pages', 'wptouch-pro' )
			)
		),
		wptouch_add_setting(
			'text',
			'featured_tag',
			__( 'Only this tag', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false //foundation_get_tag_list()
		),
		wptouch_add_setting(
			'text',
			'featured_category',
			__( 'Only this category', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false //foundation_get_category_list()
		),
		wptouch_add_setting(
			'text',
			'featured_post_ids',
			__( 'Comma-separated list of post/page IDs', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		)
	);

	if ( function_exists( 'wptouch_custom_posts_get_list' ) ) {
		$featured_settings[] = wptouch_add_pro_setting(
			'list',
			'featured_post_type',
			__( 'Only this post type', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array_merge( array( 'Select Post Type' ), wptouch_custom_posts_get_list() )
		);
	}

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Featured Slider', 'wptouch-pro' ),
		'foundation-featured-settings',
		array_merge(
			array(
				wptouch_add_setting(
					'checkbox',
					'featured_enabled',
					__( 'Enable featured slider', 'wptouch-pro' ),
					false,
					WPTOUCH_SETTING_BASIC,
					'2.0'
				)
			),
			$featured_enhanced_setting,
			apply_filters( 'wptouch_featured_slider_settings', $featured_settings )
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true,
		false,
		30
	);

	return $page_options;
}
