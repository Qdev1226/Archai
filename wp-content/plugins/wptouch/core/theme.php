<?php
// Main template functions for WPtouch Pro
function wptouch_head() {
	if ( is_single() ) {
		add_action( 'wp_head', 'wptouch_canonical_link' );
		remove_action( 'wp_head', 'rel_canonical' );
	}

	do_action( 'wptouch_pre_head' );
	wp_head();
	do_action( 'wptouch_post_head' );
}

function wptouch_footer() {
	do_action( 'wptouch_pre_footer' );
	wp_footer();
	do_action( 'wptouch_post_footer' );
}

function wptouch_title() {
	if ( is_home() ) {
		echo esc_html(wptouch_bloginfo( 'site_title' ));
	} else {
		echo esc_html(wptouch_bloginfo( 'site_title' ) . wp_title( ' &raquo; ', 0 ));
	}
}

function wptouch_site_title() {
	echo esc_html( wptouch_get_site_title() );
}

function wptouch_get_site_title() {
	return get_bloginfo('name');
}

function wptouch_have_posts() {
	return have_posts();
}

function wptouch_the_post() {
	the_post();
}

function wptouch_the_content() {
	/*
	Intentionally un-escaped to allow for 3rd party plugins with custom/obscure DOM 
	elements to work.  Necessary for full function.
	Per: https://developer.wordpress.org/apis/security/escaping/
	"most WordPress functions properly prepare the data for output, and additional escaping is not needed."
	*/
	echo apply_filters( 'the_content', wptouch_get_content() );
}

function wptouch_get_content() {
	return apply_filters( 'wptouch_the_content', get_the_content() );
}

function wptouch_the_excerpt() {
	echo esc_html(wptouch_get_excerpt());
}

function wptouch_get_excerpt() {
	return apply_filters( 'wptouch_excerpt', get_the_excerpt() );
}

function wptouch_footer_classes() {
	echo esc_html(wptouch_get_footer_classes());
}

function wptouch_get_footer_classes() {
	$footer_classes = array( 'footer' );

	return implode( ' ', apply_filters( 'wptouch_footer_classes', $footer_classes ) );
}

function wptouch_body_classes() {
	echo esc_attr( wptouch_get_body_classes() );
}

function wptouch_get_body_classes() {
	global $wptouch_pro;

	$body_classes = array();

	// Add a class to the body when we're in preview mode, or the preview window
	if ( wptouch_in_preview() ) {
		$body_classes[] = 'preview-mode';
	}

	$colors = foundation_get_theme_colors();
	foreach ( $colors as $color ) {
		$domain_settings = $wptouch_pro->get_settings( $color->domain );

		if ( isset( $color->luma_threshold ) && $color->luma_threshold != false ) {
			$current_luma = wptouch_hex_to_luma( $domain_settings->{ $color->setting } );
			if ( $current_luma < $color->luma_threshold ) {
				$body_classes[] = 'dark-' . $color->luma_class;
			} else {
				$body_classes[] = 'light-' . $color->luma_class;
			}
		}
	}

	return implode( ' ', apply_filters( 'wptouch_body_classes', $body_classes ) );
}

function wptouch_make_css_friendly( $name ) {
	return strtolower( str_replace( ' ', '-', $name ) );
}

function wptouch_canonical_link() {
	global $post;

	if ( is_object( $post ) ) {
		$settings = foundation_get_settings();
		$wordpress_posts_page = get_option( 'page_for_posts' );
		$wptouch_posts_page = $settings->latest_posts_page;
		$on_wptouch_posts_page = false;

		if ( $wptouch_posts_page != 'none' ) {
			$on_wptouch_posts_page = get_permalink( $post->ID ) == get_permalink( $wptouch_posts_page );
		}

		if ( is_home() && !$on_wptouch_posts_page ) {
			if ( $wordpress_posts_page != 0 ) {
				$permalink = get_permalink( $wordpress_posts_page );
			} else {
				$permalink = site_url() . '/';
			}
		} else {
			$permalink = get_permalink( $post->ID );
		}

		echo '<link rel="canonical" href="' . esc_url( $permalink ) . '" />';
	}
}

function wptouch_the_title() {
	echo esc_html( wptouch_get_title() );
}

function wptouch_get_title() {
	return apply_filters( 'wptouch_the_title', get_the_title() );
}

function wptouch_the_permalink() {
	echo esc_url( wptouch_get_the_permalink() );
}

function wptouch_get_the_permalink() {
	return apply_filters( 'wptouch_the_permalink', get_permalink() );
}

function wptouch_post_classes() {
	echo esc_attr( implode( ' ', wptouch_get_post_classes() ) );
}

function wptouch_get_post_classes() {
	global $post;
	$post_classes = array( 'post', 'section' );

	// Add the post ID as a class
	if ( isset( $post->ID ) ) {
		$post_classes[] = 'post-' . $post->ID;
	}

	// Add the post title
	if ( isset( $post->post_name ) ) {
		$post_classes[] = 'post-name-' . $post->post_name;
	}

	// Add the post parent
	if ( isset( $post->post_parent ) && $post->post_parent ) {
		$post_classes[] = 'post-parent-' . $post->post_parent;
	}

	// Add the post parent
	if ( isset( $post->post_author ) && $post->post_author ) {
		$post_classes[] = 'post-author-' . $post->post_author;
	}

	if ( is_single() ) {
		$post_classes[] = 'single';
	} else {
		$post_classes[] = 'not-single';
	}

	if ( is_page() ) {
		$post_classes[] = 'page';
	} else {
		$post_classes[] = 'not-page';
	}

	if ( wptouch_has_post_thumbnail() ) {
		$post_classes[] = 'has-thumbnail';
	} else {
		$post_classes[] = 'no-thumbnail';
	}

	return apply_filters( 'wptouch_post_classes', $post_classes );
}

function wptouch_has_post_thumbnail() {
	if ( function_exists( 'has_post_thumbnail' ) ) {
		$has_thumbnail = has_post_thumbnail();

		return apply_filters( 'wptouch_has_post_thumbnail', $has_thumbnail );
	} else {
		return apply_filters( 'wptouch_has_post_thumbnail', false );
	}
}

function wptouch_the_post_thumbnail( $param = false ) {
	echo wp_kses_post( wptouch_get_the_post_thumbnail( $param ) );
}

function wptouch_get_the_post_thumbnail( $param = false ) {
	global $post;

	$thumbnail = false;
	if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) {
		$thumbnail = get_the_post_thumbnail( $post->ID, 'wptouch-new-thumbnail' );
		if ( preg_match( '#src=\"(.*)\"#iU', $thumbnail, $matches ) ) {
			$thumbnail = $matches[1];

			$our_size = sprintf( "%dx%d", WPTOUCH_THUMBNAIL_SIZE, WPTOUCH_THUMBNAIL_SIZE );
			if ( strpos( $thumbnail, $our_size ) === false ) {
				// It's not our image, so just use the WP thumbnail size
				$thumbnail = get_the_post_thumbnail( $post->ID, 'thumbnail' );
				if ( preg_match( '#src=\"(.*)\"#iU', $thumbnail, $matches ) ) {
					$thumbnail = $matches[1];
				}
			}
		}
	}

	return apply_filters( 'wptouch_the_post_thumbnail', $thumbnail, $param );
}

function wptouch_content_classes() {
	echo esc_attr( implode( ' ', wptouch_get_content_classes() ) );
}

function wptouch_get_content_classes() {
	$content_classes = array( 'content' );

	return apply_filters( 'wptouch_content_classes', $content_classes );
}

function wptouch_the_time( $format = false, $time = false ) {
	echo esc_html( wptouch_get_the_time( $format, $time ) );
}

function wptouch_get_the_time( $format = false, $time = false ) {
	if ( !$format ) {
		$date_format = get_option( 'date_format' );
		$format = $date_format;
	}

	if ( !$time ) {
		$time = get_the_time( 'U' );
	}

	return apply_filters( 'wptouch_get_the_time', date_i18n( $format, $time ) );
}

function wptouch_has_tags() {
	if ( is_page() ) {
		return false;
	}

	return apply_filters( 'wptouch_has_tags', get_the_tags() );
}

function wptouch_the_tags() {
	the_tags( '',', ','' );
}

function wptouch_has_categories() {
	if ( is_page() ) {
		return false;
	}

	$cats = get_the_category();
	return $cats;
}

function wptouch_the_categories() {
	the_category( ', ' );
}

function wptouch_page_has_icon() {
	$settings = wptouch_get_settings();
	return ( $settings->enable_menu_icons );
}

function wptouch_is_custom_page_template() {
	global $wptouch_pro;
	return $wptouch_pro->is_custom_page_template;
}

function wptouch_the_custom_page_template_id() {
	echo esc_html( wptouch_get_custom_page_template_id() );
}

function wptouch_get_custom_page_template_id() {
	global $wptouch_pro;
	return $wptouch_pro->custom_page_template_id;
}

function wptouch_the_mobile_switch_link() {
	echo esc_url( wptouch_get_mobile_switch_link() );
}

function wptouch_get_mobile_switch_link() {
	$settings = wptouch_get_settings();
	return apply_filters( 'wptouch_mobile_switch_link', '?wptouch_switch=desktop' );
}

function wptouch_use_mobile_switch_link() {
	$settings = wptouch_get_settings();
	if  ( apply_filters( 'wptouch_show_mobile_switch_link', $settings->show_switch_link == true ) ) {
		return true;
	} else {
		return false;
	}
}

function wptouch_the_footer_message() {
	echo wp_kses_post( wptouch_get_the_footer_message() );
}

function wptouch_get_the_footer_message() {
	$settings = wptouch_get_settings();
	return apply_filters( 'wptouch_footer_message', $settings->footer_message );
}

function wptouch_have_comments() {
	$comment_count = wptouch_get_comment_count();

	if ( $comment_count > 0 ) {
		return true;
	}

	return false;
}

function wptouch_the_comment_count() {
	echo absint( wptouch_get_comment_count() );
}

function wptouch_get_comment_count() {
	global $id;

	$comments = get_approved_comments( $id );
	$comment_count = 0;

	foreach( $comments as $comment ){
		if( $comment->comment_type == "" ){
			$comment_count++;
		}
	}
	return $comment_count;
}

function wptouch_the_current_page_url() {
	echo esc_url( wptouch_get_current_page_url() );
}

function wptouch_get_current_page_url() {
	if(isset($_SERVER['REQUEST_URI'])) {
		return apply_filters( 'wptouch_current_page_url', sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) );
	}
}

function wptouch_hex_to_luma( $hex ) {
	// assumes 6 character long hex strings
	$hex = strtolower( ltrim( $hex, '#' ) );

	if ( strlen( $hex ) == 6 ) {
		$red_hex = substr( $hex, 0, 2 );
		$green_hex = substr( $hex, 2, 2 );
		$blue_hex = substr( $hex, 4, 2);
	} else {
		$red_hex = substr( $hex, 0, 1 ) . substr( $hex, 0, 1 );
		$green_hex = substr( $hex, 1, 1 ) . substr( $hex, 1, 1 );
		$blue_hex = substr( $hex, 2, 1) . substr( $hex, 2, 1);
	}

	$red = hexdec( $red_hex );
	$green = hexdec( $green_hex );
	$blue = hexdec( $blue_hex );

	$luma = round( 0.30 * $red + 0.59 * $green + 0.11 * $blue );

	return $luma;
}

function wptouch_ordered_tag_list( $num ) {
	global $wpdb;

	echo '<ul>';
	$sql = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE taxonomy = 'post_tag' AND {$wpdb->prefix}term_taxonomy.term_id AND count >= 1 ORDER BY count DESC LIMIT 0, $num");

	if ( $sql ) {
		foreach ( $sql as $result ) {
			if ( $result ) {
				echo wp_kses_post( "<li><a href=\"" . get_tag_link( $result->term_id ) . "\">" . $result->name . "</a></li>" );
			}
		}
	}
	echo '</ul>';
}
