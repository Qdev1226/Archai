<?php

define( 'BAUHAUS_THEME_VERSION', '2.2.5' );
define( 'BAUHAUS_SETTING_DOMAIN', 'bauhaus' );
define( 'BAUHAUS_DIR', wptouch_get_bloginfo( 'theme_root_directory' ) );
define( 'BAUHAUS_URL', wptouch_get_bloginfo( 'theme_parent_url' ) );

// Bauhaus actions
add_action( 'foundation_init', 'bauhaus_theme_init' );
add_action( 'foundation_modules_loaded', 'bauhaus_register_fonts' );
add_action( 'customize_controls_enqueue_scripts', 'bauhaus_enqueue_customizer_script' );
// Add custom css to Web App Mode to allow for a frosted header appearance on iOS 7 or higher
add_action( 'wp_head', 'bauhaus_add_frosted_header_wam', 100 );

// Bauhaus filters
add_filter( 'wptouch_registered_setting_domains', 'bauhaus_setting_domain' );
add_filter( 'wptouch_setting_defaults_bauhaus', 'bauhaus_setting_defaults' );
add_filter( 'foundation_settings_blog', 'bauhaus_blog_settings' );
add_filter( 'wptouch_setting_defaults_foundation', 'bauhaus_foundation_setting_defaults' );

add_filter( 'wptouch_body_classes', 'bauhaus_body_classes' );
add_filter( 'wptouch_post_classes', 'bauhaus_post_classes' );

add_filter( 'wptouch_theme_color', 'bauhaus_theme_color' );

// Bauhaus GUI Settings
add_filter( 'foundation_settings_header', 'bauhaus_header_settings' );
add_filter( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'bauhaus_render_theme_settings' );
add_filter( 'wptouch_post_footer', 'bauhaus_footer_version' );

add_filter( 'wptouch_has_post_thumbnail', 'bauhaus_handle_has_thumbnail' );
add_filter( 'wptouch_the_post_thumbnail', 'bauhaus_handle_the_thumbnail' );
add_filter( 'wptouch_get_post_thumbnail', 'bauhaus_handle_get_thumbnail' );
add_filter( 'post_thumbnail_html', 'bauhaus_handle_thumbnail_html', 10, 5 );
add_filter( 'wptouch_setting_version_compare', 'bauhaus_setting_version_compare', 10, 2 );

function bauhaus_setting_domain( $domain ) {
	$domain[] = BAUHAUS_SETTING_DOMAIN;
	return $domain;
}

function bauhaus_get_settings() {
	return wptouch_get_settings( BAUHAUS_SETTING_DOMAIN );
}

function bauhaus_setting_version_compare( $version, $domain ) {
	if ( $domain == BAUHAUS_SETTING_DOMAIN ) {
		return BAUHAUS_THEME_VERSION;
	}

	return $version;
}

function bauhaus_footer_version(){
	echo '<!--Bauhaus v' . BAUHAUS_THEME_VERSION . '-->';
}

function bauhaus_setting_defaults( $settings ) {

	// Bauhaus menu default
	$settings->bauhaus_menu_style = 'off-canvas';
	$settings->bauhaus_menu_position = 'left-side';

	// Theme colors
	$settings->bauhaus_background_color = '#edede7';
	$settings->bauhaus_header_color = '#037add';
	$settings->bauhaus_link_color = '#037add';
	$settings->bauhaus_post_page_header_color = '#e8a21c';

	// Blog
	$settings->bauhaus_show_taxonomy = false;
	$settings->bauhaus_show_date = true;
	$settings->bauhaus_show_author = false;
	$settings->bauhaus_show_search = true;
	$settings->bauhaus_show_comment_bubbles = true;
	$settings->bauhaus_use_infinite_scroll = false;

	$settings->bauhaus_use_thumbnails = 'index_single_page';
	$settings->bauhaus_thumbnail_type = 'featured';
	$settings->bauhaus_thumbnail_custom_field = '';

	// Global Featured Posts Settings
	$settings->bauhaus_featured_comments = false;
	$settings->bauhaus_featured_type = 'latest';
	$settings->bauhaus_featured_tag = '';
	$settings->bauhaus_featured_category = '';
	$settings->bauhaus_featured_post_type = '';
	$settings->bauhaus_featured_post_ids = '';
	$settings->bauhaus_featured_max_number_of_posts = '5';
	$settings->bauhaus_featured_filter_posts = true;
	$settings->featured_slider_page = false;

	$settings->bauhaus_post_listing_view = 'default';

	// Post Listings -----
	$settings->bauhaus_featured_enabled = true;
	$settings->bauhaus_post_listing_autoplay = false;
	$settings->bauhaus_post_listing_dots = true;

	// Carousel View -----
	// Featured Posts
	$settings->bauhaus_featured_carousel_enabled = true;
	// Popular Posts
	$settings->bauhaus_popular_enabled = true;
	$settings->bauhaus_popular_max_number_of_posts = '5';

	return $settings;
}

function bauhaus_foundation_setting_defaults( $settings ) {
	$settings->typography_sets = 'oswald_opensans';
	return $settings;
}

function bauhaus_theme_init() {
	// Foundation modules this theme should load
	foundation_add_theme_support(
		array(
			// Modules w/ settings
			'wptouch-icons',
			'custom-posts',
			'custom-latest-posts',
			'google-fonts',
			'load-more',
			'media',
			'sharing',
			'social-links',
			'login',
//			'featured-posts',
			// Modules w/o settings
			'menu',
			'flickity',
			'spinjs',
			'tappable',
			'fastclick',
			'concat'
		)
	);

	// If enabled in Bauhaus settings, load up infinite scrolling
	bauhaus_if_infinite_scroll_enabled();

	// If enabled in Bauhaus settings, load up PushIt off-canvas menu (default)
	bauhaus_if_off_canvas_enabled();

	// Example of how to register a theme menu
	wptouch_register_theme_menu(
		array(
			'name' => 'primary_menu',									// this is the name of the setting
			'friendly_name' => __( 'Header Menu', 'wptouch-pro' ),		// the friendly name, shows as a section heading
			'settings_domain' => BAUHAUS_SETTING_DOMAIN,				// the setting domain (should be the same for the whole theme)
			'description' => __( 'Choose a menu', 'wptouch-pro' ),		// the description
			'tooltip' => __( 'Main menu selection', 'wptouch-pro' ),	// Extra help info about this menu, perhaps?
			'can_be_disabled' => false									// Typically this is always false
		)
	);

	// Example of how to register theme colors
	// (Name, element to add color to, element to add background-color to, settings domain, luma threshold, luma class root – light-*, dark-* )
	foundation_register_theme_color( 'bauhaus_background_color', __( 'Theme background', 'wptouch-pro' ), '', '.page-wrapper', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING, 150, 'body' );
	foundation_register_theme_color( 'bauhaus_header_color', __( 'Header & Menu', 'wptouch-pro' ),'', 'body, header, .wptouch-menu, .pushit, #search-dropper, .date-circle, .list-view .list-carousel', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING, 150, 'header' );
	foundation_register_theme_color( 'bauhaus_link_color', __( 'Links', 'wptouch-pro' ), '.content-wrap a, #slider a p:after', '.dots li.active, #switch .active', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING );
	foundation_register_theme_color( 'bauhaus_post_page_header_color', __( 'Post/Page Headers', 'wptouch-pro' ), '', '.bauhaus, form#commentform button#submit, form#commentform input#submit', BAUHAUS_SETTING_DOMAIN, WPTOUCH_PRO_LIVE_PREVIEW_SETTING, 150, 'post-head' );

}

// Example of how to register Google font pairings
// (Apply to (Headings or Body), Google font Pretty Name, kerning, weights)
function bauhaus_register_fonts() {
	if ( foundation_is_theme_using_module( 'google-fonts' ) ) {
		foundation_register_google_font_pairing(
			'oswald_opensans',
			foundation_create_google_font( 'heading', 'Oswald', 'sans-serif', array( '300', '700' ) ),
			foundation_create_google_font( 'body', 'Open Sans', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'lato_roboto',
			foundation_create_google_font( 'heading', 'Lato', 'sans-serif', array( '300', '600' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'droidserif_roboto',
			foundation_create_google_font( 'heading', 'Droid Serif', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'baumans_ubuntu',
			foundation_create_google_font( 'heading', 'Baumans', 'sans-serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Ubuntu', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'alegreya_roboto',
			foundation_create_google_font( 'heading', 'Alegreya', 'serif', array( '400', '700' ) ),
			foundation_create_google_font( 'body', 'Roboto', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'fjalla_cantarell',
			foundation_create_google_font( 'heading', 'Fjalla One', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Open Sans', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'grandhotel_crimson',
			foundation_create_google_font( 'heading', 'Domine', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'News Cycle', 'sans-serif', array( '400', '700', '400italic', '700italic' ) )
		);
		foundation_register_google_font_pairing(
			'muli_montserrat',
			foundation_create_google_font( 'heading', 'Montserrat', 'sans-serif', array( '400' ) ),
			foundation_create_google_font( 'body', 'Muli', 'sans-serif', array( '400', '400italic' ) )
		);
	}
}

function bauhaus_add_frosted_header_wam(){
	$settings = bauhaus_get_settings();
	$color = 'rgba(' . wptouch_hex_to_rgb( $settings->bauhaus_header_color, true ) . ',.88)';
	echo "<style>.ios7.web-app-mode.has-fixed header{ background-color: " . $color . ";}</style>";
}

function bauhaus_body_classes( $classes ) {
	$settings = bauhaus_get_settings();

	$classes[] = 'circles';

	if ( !$settings->bauhaus_show_comment_bubbles ) {
		$classes[] = 'no-com-bubbles';
	}

	if ( $settings->bauhaus_menu_style == 'drop-down' ) {
		$classes[] = 'drop-down';
	} else {
		$classes[] = 'off-canvas';
	}

	if ( $settings->bauhaus_post_listing_view == 'default' ) {
		$classes[] = 'list-view';
	}

	if ( $settings->bauhaus_post_listing_view == 'carousel' && $settings->bauhaus_featured_carousel_enabled == false && $settings->bauhaus_popular_enabled == false ) {
		$classes[] = 'slider-latest-only';
	}

	return $classes;
}

function bauhaus_post_classes( $classes ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_use_thumbnails != 'none' ) {
	  $classes[] = 'show-thumbs';
	} else {
	  $classes[] = 'no-thumbs';
	}

	return $classes;
}


function bauhaus_enqueue_customizer_script() {
	wp_enqueue_script(
		'bauhaus-customizer-js',
		BAUHAUS_URL . '/bauhaus-customizer.js',
		array( 'jquery' ),
		BAUHAUS_THEME_VERSION,
		false
	);
}

// Admin Settings
function bauhaus_header_settings( $header_settings ) {

	$header_settings[] = wptouch_add_pro_setting(
		'list',
		'bauhaus_menu_style',
		__( 'Menu animation style', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.3',
		array(
			'off-canvas' => __( 'Off-canvas', 'wptouch-pro' ),
			'drop-down' => __( 'Drop-down', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$header_settings[] = wptouch_add_setting(
		'list',
		'bauhaus_menu_position',
		__( 'Menu position', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.6.4',
		array(
			'left-side' => __( 'Left side', 'wptouch-pro' ),
			'right-side' => __( 'Right side', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$header_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_search',
		__( 'Show search in header', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	return $header_settings;
}

// Hook into Foundation page section for Blog and add settings
function bauhaus_blog_settings( $blog_settings ) {

	$blog_settings[] = wptouch_add_setting(
		'list',
		'bauhaus_use_thumbnails',
		__( 'Post thumbnails', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		array(
			'none' => __( 'No thumbnails', 'wptouch-pro' ),
			'index' => __( 'Blog listing only', 'wptouch-pro' ),
			'index_single' => __( 'Blog listing, single posts', 'wptouch-pro' ),
			'index_single_page' => __( 'Blog listing, single posts & pages', 'wptouch-pro' ),
			'all' => __( 'All (blog, single, pages, search & archive)', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'radiolist',
		'bauhaus_thumbnail_type',
		__( 'Thumbnail Type', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_ADVANCED,
		'1.0',
		array(
			'featured' => __( 'Post featured images', 'wptouch-pro' ),
			'custom_field' => __( 'Post custom field', 'wptouch-pro' )
		),
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'text',
		'bauhaus_thumbnail_custom_field',
		__( 'Thumbnail custom field name', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_ADVANCED,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_taxonomy',
		__( 'Show post categories and tags', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_date',
		__( 'Show post date', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_author',
		__( 'Show post author', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_setting(
		'checkbox',
		'bauhaus_show_comment_bubbles',
		__( 'Show comment bubbles on posts', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0.5',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	$blog_settings[] = wptouch_add_pro_setting(
		'checkbox',
		'bauhaus_use_infinite_scroll',
		__( 'Use infinite scrolling for blog', 'wptouch-pro' ),
		false,
		WPTOUCH_SETTING_BASIC,
		'1.0',
		false,
		BAUHAUS_SETTING_DOMAIN
	);

	return $blog_settings;
}

function bauhaus_render_theme_settings( $page_options ) {

	global $wptouch_pro;

	$bauhaus_settings = array(
		wptouch_add_setting(
			'list',
			'bauhaus_post_listing_view',
			__( 'Blog Appearance', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'default' => __( 'List View', 'wptouch-pro' ),
				'carousel' => __( 'Carousel View', 'wptouch-pro' )
			)
		),
		wptouch_add_setting(
			'checkbox',
			'bauhaus_featured_enabled',
			__( 'Show featured posts', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'checkbox',
			'bauhaus_post_listing_dots',
			__( 'Show slider nav bar', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'checkbox',
			'bauhaus_post_listing_autoplay',
			__( 'Autoplay', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'checkbox',
			'bauhaus_featured_carousel_enabled',
			__( 'Show featured posts slider', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'checkbox',
			'bauhaus_popular_enabled',
			__( 'Show popular posts slider', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'range',
			'bauhaus_popular_max_number_of_posts',
			__( 'Max number of posts in popular slider', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'min' => 5,
				'max' => 12,
				'step' => 1
			),
			BAUHAUS_SETTING_DOMAIN
		)
	);

	$bauhaus_featured_settings = array(
		wptouch_add_setting(
			'range',
			'bauhaus_featured_max_number_of_posts',
			__( 'Max number of featured posts', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'min' => 5,
				'max' => 12,
				'step' => 1
			),
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'checkbox',
			'bauhaus_featured_filter_posts',
			__( 'Featured posts also show in latest posts', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'list',
			'bauhaus_featured_type',
			__( 'Featured posts to display', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'latest' => __( 'Show latest posts', 'wptouch-pro' ),
				'tag' => __( 'Show posts from a specific tag', 'wptouch-pro' ),
				'category' => __( 'Show posts from a specific category', 'wptouch-pro' ),
				'post_type' => __( 'Show posts from a specific post type', 'wptouch-pro' ),
				'posts' => __( 'Show only specific posts or pages', 'wptouch-pro' )
			),
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'text',
			'bauhaus_featured_tag',
			__( 'Only this tag', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'text',
			'bauhaus_featured_category',
			__( 'Only this category', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'text',
			'bauhaus_featured_post_ids',
			__( 'Comma-separated list of post/page IDs', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false,
			BAUHAUS_SETTING_DOMAIN
		),
		wptouch_add_setting(
			'list',
			'featured_slider_page',
			__( 'Featured Slider Page', 'wptouch-pro' ),
			__( 'Choose which page should display the featured slider', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			bauhaus_get_page_list(),
			BAUHAUS_SETTING_DOMAIN
		)
	);

	if ( function_exists( 'wptouch_custom_posts_get_list' ) ) {
		$bauhaus_featured_post_type = wptouch_add_pro_setting(
			'list',
			'bauhaus_featured_post_type',
			__( 'Only this post type', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array_merge( array( 'Select Post Type' ), wptouch_custom_posts_get_list() ),
			BAUHAUS_SETTING_DOMAIN
		);

		array_push( $bauhaus_featured_settings, $bauhaus_featured_post_type );
	}

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Blog Featured Slider', 'wptouch-pro' ),
		'featured-slider',
		$bauhaus_featured_settings,
		$page_options,
		BAUHAUS_SETTING_DOMAIN,
		true
	);

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Blog Layout', 'wptouch-pro' ),
		'post-view',
		$bauhaus_settings,
		$page_options,
		BAUHAUS_SETTING_DOMAIN,
		true
	);


	return $page_options;
}

function bauhaus_handle_has_thumbnail( $does_have_it ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		if ( $settings->bauhaus_thumbnail_custom_field ) {
			global $post;

			$possible_image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
			return strlen( $possible_image );
 		}
	}

	return $does_have_it;
}

function bauhaus_handle_the_thumbnail( $current_thumbnail ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		global $post;

		$image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
		echo wp_kses_post( $image );
	}

	return $current_thumbnail;
}

function bauhaus_handle_get_thumbnail( $current_thumbnail ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		global $post;

		$image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );
		return $image;
	}

	return $current_thumbnail;
}

function bauhaus_handle_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr  ) {
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_thumbnail_type == 'custom_field' ) {
		if ( $settings->bauhaus_thumbnail_custom_field ) {
			global $post;

			$possible_image = get_post_meta( $post->ID, $settings->bauhaus_thumbnail_custom_field, true );

			$classes = '';
			if ( strlen( $possible_image ) > 0 ) {
				if ( isset( $attr[ 'class' ] ) ) { $classes = 'class="' . $attr[ 'class' ] . '"'; }

				return '<img src="' . $possible_image . '" ' . $classes . '>';
			}
 		}
	}

	return $html;
}

function bauhaus_if_infinite_scroll_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_use_infinite_scroll ) {
		foundation_add_theme_support( 'infinite-scroll' );
	}
}

function bauhaus_if_off_canvas_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_menu_style == 'off-canvas' ) {
		foundation_add_theme_support( 'slideout' );
	}
}

function bauhaus_if_carousel_view_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_post_listing_view == 'carousel' ) {
		return true;
	}

	return false;
}

function bauhaus_if_popular_enabled(){
	$settings = bauhaus_get_settings();

	if ( $settings->bauhaus_post_listing_view == 'carousel' && $settings->bauhaus_popular_enabled ) {
		return true;
	}

	return false;
}

add_filter( 'wptouch_amp_header_color', 'bauhaus_amp_header_color' );

function bauhaus_amp_header_color( $color ) {
	$settings = bauhaus_get_settings();
	return $settings->bauhaus_header_color;
}

add_filter( 'wptouch_amp_link_color', 'bauhaus_amp_link_color' );

function bauhaus_amp_link_color( $color ) {
	$settings = bauhaus_get_settings();
	return $settings->bauhaus_link_color;
}

add_filter( 'wptouch_amp_show_author', 'bauhaus_amp_show_author' );

function bauhaus_amp_show_author() {
	$settings = bauhaus_get_settings();
	return $settings->bauhaus_show_author;
}

add_filter( 'wptouch_amp_show_date', 'bauhaus_amp_show_date' );

function bauhaus_amp_show_date() {
	$settings = bauhaus_get_settings();
	return $settings->bauhaus_show_date;
}

add_filter( 'wptouch_amp_show_taxonomy', 'bauhaus_amp_show_taxonomy' );

function bauhaus_amp_show_taxonomy() {
	$settings = bauhaus_get_settings();
	return $settings->bauhaus_show_taxonomy;
}

function bauhaus_theme_color() {
	$settings = bauhaus_get_settings();
	return $settings->bauhaus_header_color;
}

function bauhaus_get_page_list() {
	$contents = get_pages();
	$pages = array( 'Select&hellip;' );
	foreach ( $contents as $page ) {
		$pages[ $page->ID ] = $page->post_title;
	}
	return $pages;
}
