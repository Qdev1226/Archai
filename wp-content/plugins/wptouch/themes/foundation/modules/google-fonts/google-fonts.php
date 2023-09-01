<?php
add_action( 'init', 'foundation_google_fonts_enable' );
add_action( 'foundation_module_init_mobile', 'foundation_google_fonts_enable', 5 );
add_action( 'foundation_module_init_mobile', 'foundation_google_fonts_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_admin_panel' );
add_filter( 'wptouch_amp_get_fonts', 'foundation_build_google_fonts' );

global $google_fonts_enabled;

function foundation_google_fonts_enable() {
	global $google_fonts_enabled;
	$google_fonts_enabled = apply_filters( 'wptouch_filter_google_fonts', true );
}

function foundation_admin_panel( $page_options ) {
	global $google_fonts_enabled;
	if ( $google_fonts_enabled ) {
		$fonts = foundation_get_google_font_pairings();

		if ( count( (array) $fonts ) ) {
			$font_defaults = array( 'default' => __( 'Browser Default Fonts', 'wptouch-pro' ) );

			foreach( $fonts as $setting_value => $font_info ) {
				$font_defaults[ $setting_value ] = sprintf( '%s & %s', $font_info[0]->name, $font_info[1]->name );
			}

			wptouch_add_page_section(
				FOUNDATION_PAGE_BRANDING,
				__( 'Typography', 'wptouch-pro' ),
				'foundation-typography',
				array(
					wptouch_add_setting(
						'select',
						'typography_sets',
						__( 'Font Pairing', 'wptouch-pro' ),
						__( 'Choose a Google font pairing designed for this theme, or default browser fonts.', 'wptouch-pro' ),
						WPTOUCH_SETTING_BASIC,
						'1.0',
						$font_defaults
					)
				),
				$page_options,
				FOUNDATION_SETTING_DOMAIN,
				true,
				false,
				20
			);
		}
	}

	return $page_options;
}

function foundation_google_fonts_get_selected_info() {
	$settings = wptouch_get_settings( 'foundation' );
	$fonts = foundation_get_google_font_pairings();

	$selected_font_info = false;
	foreach( $fonts as $setting_name => $font_info ) {
		if ( $settings->typography_sets == $setting_name ) {
			$selected_font_info = $font_info;
			break;
		}
	}

	return $selected_font_info;
}

function foundation_google_fonts_init() {
	global $google_fonts_enabled;

	if ( $google_fonts_enabled ) {

		$settings = wptouch_get_settings( 'foundation' );

		if ( $settings->typography_sets != 'default' ) {
			wp_enqueue_script(
				'foundation_google_fonts',
				foundation_get_base_module_url() . '/google-fonts/google-fonts.min.js',
				false,
				md5( FOUNDATION_VERSION ),
				true
			);

			add_filter( 'wptouch_body_classes', 'foundation_add_google_font_classes' );
		}

		$selected_font_info = foundation_google_fonts_get_selected_info();

		if ( $selected_font_info ) {
			$family_string = '';
			$inline_style_data = '';

			if ( is_array( $selected_font_info ) && count( (array) $selected_font_info ) ) {
				$new_families = array();

				// Maintain the module's max of 2 fonts when rendering; we'll let ourselves register more font placements for selection in the advanced type module.
				if ( count( (array) $selected_font_info ) > 2 ) {
					$selected_font_info = array_slice( $selected_font_info, 0, 2 );
				}

				$new_families = foundation_build_google_fonts();

				foreach( $selected_font_info as $font_info ) {
					$inline_style_data .= "." . $font_info->selector . "-font" . "{\n";
					$inline_style_data .= "\t font-family: '" . $font_info->name . "', " . $font_info->fallback . ";\n";
					$inline_style_data .= "}\n";
				}

				$family_string = implode( '|', $new_families );
			}

			if ( $family_string ) {
				wp_enqueue_style(
					'foundation_google_fonts',
					'//fonts.googleapis.com/css?family=' . $family_string,
					false,
					md5( FOUNDATION_VERSION ),
					false
				);

				if ( $inline_style_data ) {
					wp_add_inline_style( 'foundation_google_fonts', $inline_style_data );
				}
			}
		}
	}
}

function foundation_build_google_fonts( $fonts = array() ) {
	$selected_font_info = foundation_google_fonts_get_selected_info();
	if ( is_array( $selected_font_info ) ) {
		foreach( $selected_font_info as $font_info ) {
			$font_string = htmlentities( $font_info->name );
			if ( isset( $font_info->variants ) && is_array( $font_info->variants ) ) {
				$font_string .=  ':' . implode( ',', $font_info->variants );
			}

			$fonts[ $font_info->selector ] = $font_string;
		}
	}

	return $fonts;
}

global $wptouch_google_fonts;
$wptouch_google_fonts = array();

function foundation_create_google_font( $selector, $name, $fallback = 'sans-serif', $variants = false ) {
	$font = new stdClass;

	$font->selector = $selector;
	$font->name = $name;
	$font->fallback = $fallback;
	$font->variants = $variants;

	return $font;
}

function foundation_register_google_font_pairing() {
	global $wptouch_google_fonts;
	$args = func_get_args();

	$setting_value = array_shift( $args );
	$wptouch_google_fonts[ $setting_value ] = $args;
}

function foundation_get_google_font_pairings() {
	global $wptouch_google_fonts;
	return $wptouch_google_fonts;
}

function foundation_add_google_font_classes( $classes ) {
	$settings = wptouch_get_settings( 'foundation' );

	$classes[] = 'fonts-' . $settings->typography_sets;

	return $classes;
}
