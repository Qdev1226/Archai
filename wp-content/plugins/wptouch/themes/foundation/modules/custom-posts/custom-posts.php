<?php
add_action( 'wptouch_update_settings_domain_foundation', 'wptouch_preserve_theme_custom_post_types' );
add_action( 'init', 'wptouch_register_theme_custom_post_types', 999 );

add_filter( 'wptouch_modify_setting__foundation__enabled_custom_post_types', 'wptouch_serialize_custom_post_types', 1 );

add_filter( 'wptouch_setting_defaults_foundation', 'wptouch_custom_posts_default_settings' );
add_filter( 'foundation_search_post_types', 'wptouch_custom_posts_add_to_search' );
add_filter( 'wptouch_mobile_content_post_types', 'wptouch_custom_posts_add_to_search' );
add_filter( 'pre_get_posts', 'wptouch_custom_posts_pre_get_posts' );
add_filter( 'wptouch_foundation_search_post_type_text', 'wptouch_custom_posts_get_post_type_name' );

if ( !defined('WPTOUCH_IS_FREE') ) {
	add_filter( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'wptouch_custom_posts_render_theme_settings' );
}

function wptouch_serialize_custom_post_types( $setting ) {
	// Function is fed the custom post type object as loaded by WordPress at render time; serialize it for pass-through as a field value.
	return addslashes( serialize( $setting ) );
}

function wptouch_preserve_theme_custom_post_types() {
	$settings = foundation_get_settings();
	$post_types_to_preserve = array();

	global $wptouch_pro;
	if ( $settings->enabled_custom_post_types != false && isset( $wptouch_pro->post[ 'object_wptouch__foundation__enabled_custom_post_types' ] ) && !isset( $wptouch_pro->post[ 'wptouch__foundation__enabled_custom_post_types' ] ) ) {
		$settings->enabled_custom_post_types = false;
		$settings->save();
		update_option( 'wptouch_custom_post_types', false );
		update_option( 'wptouch_custom_taxonomies', false );
	}

	if ( $settings->enable_custom_post_types ) {
		$post_types_from_form = maybe_unserialize( stripslashes( $settings->enabled_custom_post_types ) );

		if ( is_array( $post_types_from_form ) ) {
			foreach ( $post_types_from_form as $object ) {
				$type = explode( '||||', $object );
				$post_types_to_preserve[ $type[ 0 ] ] = urldecode( $type[ 1 ] );
			}
			update_option( 'wptouch_custom_post_types', $post_types_to_preserve );
		}

		$args = array(
		  'public'   => true
		);
		$output = 'objects';
		$taxonomies = get_taxonomies( $args, $output );
		update_option( 'wptouch_custom_taxonomies', $taxonomies );
	}
}

function wptouch_register_theme_custom_post_types() {
	if ( wptouch_is_mobile_theme_showing() ) {
		$settings = foundation_get_settings();
		$post_types_to_preserve = get_option( 'wptouch_custom_post_types' );

		$post_types_to_register = array();
		$registered_types = wptouch_custom_posts_get_list( true );

		if ( is_array( $post_types_to_preserve ) && count( (array) $post_types_to_preserve ) > 0 ) {

			foreach ( $post_types_to_preserve as $type => $object ) {
				if ( !is_object( $object ) ) {
					$object = maybe_unserialize( urldecode( $object ) );
				}

				if ( !in_array( $type, $registered_types ) ) {
					$args = array(
						'labels'			  => array(
													'name' => $object->labels->name,
													'singular_name' => $object->labels->singular_name
												),
						'taxonomies'          => $object->taxonomies,
						'hierarchical'        => $object->hierarchical,
						'public'              => $object->public,
						'has_archive'         => $object->has_archive,
						'exclude_from_search' => $object->exclude_from_search,
						'publicly_queryable'  => $object->publicly_queryable,
						'rewrite'			  => $object->rewrite,
						'query_var'			  => $object->query_var,
					);


					register_post_type( $type, $args );
				}
			}
		}

		$taxonomies_to_preserve = get_option( 'wptouch_custom_taxonomies' );
		$taxonomies_to_register = array();
		$args = array(
		  'public'   => true
		);
		$output = 'names';
		$registered_taxonomies = get_taxonomies( $args, $output );

		if ( is_array( $taxonomies_to_preserve ) ) {
			foreach ( $taxonomies_to_preserve as $taxonomy => $object ) {
				if ( !in_array( $taxonomy, $registered_taxonomies ) ) {
					$args = array(
						'hierarchical'      => $object->hierarchical,
						'query_var'         => $object->query_var,
						'rewrite'           => $object->rewrite,
					);

					register_taxonomy( $taxonomy, $object->object_type, $args );
				}
			}
		}
	}
}

function wptouch_custom_posts_add_to_search( $post_types ) {
	$settings = foundation_get_settings();
	$custom_post_types = get_option( 'wptouch_custom_post_types' );
	if ( $custom_post_types ) {
		if ( is_array( $custom_post_types )  ) {
			foreach( $custom_post_types as $type => $object ) {
				$post_types[] = $type;
			}
		}
	}

	return $post_types;
}

function wptouch_custom_posts_get_list( $remove_defaults = true ) {
	$default_post_types = array( 'post', 'page', 'attachment', 'revision', 'nav_menu_item', 'tweet' );

	// Get the internal list
	$post_types = get_post_types();

	if ( $remove_defaults ) {
		return apply_filters( 'wptouch_custom_posts_list', array_diff( $post_types, $default_post_types ) );
	} else {
		return apply_filters( 'wptouch_custom_posts_list', $post_types );
	}
}

function wptouch_custom_posts_default_settings( $defaults ) {
	$defaults->enable_custom_post_types = false;
	$defaults->custom_post_types_in_post_index = true;
	$defaults->enabled_custom_post_types = '';

	return $defaults;
}

function wptouch_custom_posts_get_post_type_name( $post_type ) {
	$post_type_object = get_post_type_object( $post_type );
	return $post_type_object->labels->singular_name;
}

function wptouch_custom_posts_render_theme_settings( $page_options ) {
	$custom_post_settings = array();

	$post_types = wptouch_custom_posts_get_list( true );
	if ( count( (array) $post_types ) ) {

		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_COMPAT,
			__( 'Custom Post Support', 'wptouch-pro' ),
			'foundation-web-custom-post-type-support',
			array(
				wptouch_add_setting(
					'checkbox',
					'enable_custom_post_types',
					__( 'Enable custom post-type support', 'wptouch-pro' ),
					'',
					WPTOUCH_SETTING_BASIC,
					'1.0'
				),
			),
			$page_options,
			FOUNDATION_SETTING_DOMAIN
		);

		foreach( $post_types as $post_type ) {
			$setting = wptouch_add_setting(
				'post_type',
				'enabled_custom_post_types[' . $post_type . ']',
				sprintf( __( '%s', 'wptouch-pro' ), $post_type ),
				'',
				WPTOUCH_SETTING_BASIC,
				'1.0',
				FOUNDATION_SETTING_DOMAIN
			);

			$custom_post_settings[] = $setting;
		}

		$custom_post_settings[]	= wptouch_add_setting(
			'checkbox',
			'custom_post_types_in_post_index',
			__( 'Include custom post-type posts in blog index', 'wptouch-pro' ),
			'',
			WPTOUCH_SETTING_BASIC,
			'1.0'
		);

		wptouch_add_page_section(
			WPTOUCH_ADMIN_SETUP_COMPAT,
			__( 'Custom Post Types', 'wptouch-pro' ),
			'foundation-web-custom-post-types',
			$custom_post_settings,
			$page_options,
			FOUNDATION_SETTING_DOMAIN,
			false,
			wptouchize_it( __( 'Select which custom post types WPtouch Pro should load when displaying your site.', 'wptouch-pro' ) )
		);
	}

	return $page_options;
}

function wptouch_custom_posts_pre_get_posts( $query ) {
	// Only modify the custom post type information when a mobile theme is showing
	$settings = foundation_get_settings();
	if ( !$settings->enable_custom_post_types ) {
		return $query;
	}

	if ( is_attachment() ) {
		return $query;
	}

	// Right now only support custom post types on the home page and single post pages
	if ( ( is_single() && !is_page() ) || is_category() || is_tax() || is_tag() || is_home() ) {
		// Only employ this logic for when the mobile theme is showing
		if ( wptouch_is_mobile_theme_showing() ) {
			$settings = foundation_get_settings();

			if ( $settings->custom_post_types_in_post_index || ( !is_home() && !is_page() ) ) {
				$post_types = wptouch_custom_posts_get_list( true );
				if ( $post_types && count( (array) $post_types )  ) {
					$post_type_array = get_option( 'wptouch_custom_post_types' );

					if ( is_array( $post_type_array ) ) {
						$post_type_array = array_flip( $post_type_array );
					} else {
						$post_type_array = array();
					}
				}

				if ( count( (array) $post_type_array ) ) {
					// Determine the original post type in the query
					$original_post_type = false;
					if ( isset( $query->queried_object ) ) {
						$original_post_type = $query->queried_object->post_type;
					} else if ( isset( $query->query_vars['post_type'] ) ) {
						$original_post_type = $query->query_vars['post_type'];
					}

					if ( $original_post_type ) {
						$page_for_posts = get_option( 'page_for_posts' );
						if ( isset( $query->queried_object_id ) && ( $query->queried_object_id == $page_for_posts ) ) {
							// we're on the posts page
							$custom_post_types = apply_filters( 'wptouch_custom_posts_pre_get', array_merge( array( 'post' ), $post_type_array ) );
						} else {
							if ( !is_array( $original_post_type ) ) {
								$original_post_type = array( $original_post_type );
							}

							$custom_post_types = apply_filters( 'wptouch_custom_posts_pre_get', array_merge( $original_post_type, $post_type_array ) );
						}

						$query->set( 'post_type', $custom_post_types );
					} else {
						// We're on the home page or possibly another page for a normal site
						$custom_post_types = apply_filters( 'wptouch_custom_posts_pre_get', array_merge( array( 'post' ), $post_type_array ) );
						$query->set( 'post_type', $custom_post_types );
					}
				}
			}
		}
	}

	return $query;
}

function wptouch_custom_post_type_get_taxonomies( $post_type ) {
	$post_info = get_object_taxonomies( $post_type );
	return $post_info;
}

function wptouch_custom_posts_get_taxonomy( $tax_info = false ) {
	global $post;

	$taxonomies = wptouch_custom_post_type_get_taxonomies( $post->post_type );
	if ( $taxonomies && count( (array) $taxonomies ) ) {
		foreach( $taxonomies as $taxonomy ) {
			$product_terms = wp_get_object_terms( $post->ID, $taxonomy );
			if ( $product_terms ) {
				$tax_object = get_taxonomy( $taxonomy );
				$tax_info[ $tax_object->labels->name ] = array();

				foreach( $product_terms as $term ) {
					$term_info = new stdClass;
					$term_info->name = $term->name;
					$term_info->link = get_term_link( $term->slug, $taxonomy );

					$tax_info[ $tax_object->labels->name ][] = $term_info;
				}
			}
		}
	}

	return $tax_info;
}
