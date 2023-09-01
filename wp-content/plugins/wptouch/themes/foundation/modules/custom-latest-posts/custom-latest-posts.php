<?php

add_filter( 'foundation_settings_pages', 'wptouch_custom_latest_post_settings' );
add_filter( 'request', 'wptouch_custom_latest_post_filter' );

function wptouch_custom_latest_post_filter( $query_vars ) {
	if ( wptouch_is_showing_mobile_theme_on_mobile_device() ) {
	   	$settings = foundation_get_settings();

		if ( foundation_is_theme_using_module( 'custom-latest-posts' ) && $settings->latest_posts_page != 'none' ) {
			$dummy_query = new WP_Query();  // the query isn't run if we don't pass any query vars
		    $dummy_query->parse_query( $query_vars );

			if ( $dummy_query->is_page && ( count( (array) $query_vars ) == 0 || ( count( (array) $query_vars ) == 1 && isset( $query_vars[ 'paged' ] ) ) ) ) { // Front page
				$front_option = get_option( 'show_on_front', false );
				if ( $front_option == 'page' ) {
					$front_page = get_option( 'page_on_front' );
					$dummy_query->queried_object_id = $front_page;
				}
			}

		    if ( isset( $dummy_query->queried_object_id ) && apply_filters( 'foundation_is_custom_latest_posts_page', ( $settings->latest_posts_page == $dummy_query->queried_object_id ), $settings->latest_posts_page, $dummy_query->queried_object_id ) ) {
				if ( isset( $query_vars[ 'paged' ] ) ) {
					$paged = $query_vars[ 'paged' ];
				} elseif ( isset( $query_vars[ 'page' ] ) ) {
					$paged = $query_vars[ 'page' ];
				} else {
					$paged = 1;
				}

				$query_vars = array(
					'paged' => $paged,
					'posts_per_page' => $settings->posts_per_page
				);

				// Since we're modifying the query here, we need to prevent the custom landing page redirect from firing for this request (it'll think it's loading the homepage)
				add_filter( 'wptouch_redirect_target', 'wptouch_return_false' );

				// filter for Yoast SEO to force site title to be name of blog
				add_filter( 'wpseo_title', 'wptouch_custom_latest_post_title' );
		    }
		}
	}

   	return $query_vars;
}

function wptouch_custom_latest_post_settings( $settings ) {
	$settings[] =
		wptouch_add_setting(
			'custom-latest-posts',
			false,
			false,
			false,
			WPTOUCH_SETTING_BASIC,
			'2.3.3'
		);

	return $settings;
}

function wptouch_fdn_is_custom_latest_posts_page() {
	global $post;

	$settings = foundation_get_settings();

	if ( $settings->latest_posts_page == 'none' ) {
		return false;
	} else {
		rewind_posts();
		wptouch_the_post();
		rewind_posts();

		return apply_filters( 'foundation_is_custom_latest_posts_page', ( $settings->latest_posts_page == $post->ID ), $settings->latest_posts_page, $post->ID );
	}
}

function wptouch_fdn_custom_latest_posts_query() {
	return false;
}

function wptouch_custom_latest_post_title( $title ) {

	if ( get_bloginfo( 'description' ) == '' ) {
		return get_bloginfo( 'name' );
	} else {
		return get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
	}
}
