<?php
global $wptouch_pro;
$current_theme = $wptouch_pro->get_current_theme_info();

global $wptouch_queued_items;
$wptouch_queued_items = 0;

if ( $current_theme && wptouch_admin_use_customizer() ) {

	add_action( 'admin_init', 'wptouch_initialize_customizer' );

	// Executed during the Customizer parent window load.
	add_action( 'customize_register', 'wptouch_customizer_setup', 5 );

	// Executed during the Customizer child window load (preview).
	add_action( 'customize_controls_enqueue_scripts', 'wptouch_customizer_scripts' );

	// Executed when a new setting is detected and its default value is merged into the settings object
	add_action( 'wptouch_settings_after_merge_default', 'wptouch_customizer_merge_setting', 10, 3 );

	// Executed after settings backup is loaded, replacing WPtouch settings.
	add_action( 'wptouch_after_restore_settings', 'wptouch_customizer_restore_settings', 10, 2 );

	// Executed after settings have been erased.
	add_action( 'wptouch_after_reset_settings', 'wptouch_customizer_reset_settings' );
	add_action( 'wptouch_after_self_destruct', 'wptouch_customizer_reset_settings' );

	add_filter( 'wptouch_force_mobile_device', 'wptouch_is_customizing_mobile' );

	// If we're in the customizer and we're editing the mobile theme...
	if ( wptouch_is_customizing_mobile() ) {

		wptouch_customizer_begin_theme_override();

		add_filter( 'customize_previewable_devices', '__return_empty_array' );

		add_filter( 'validate_current_theme', 'wptouch_return_false' );

		add_filter( 'wptouch_show_mobile_switch_link', 'wptouch_return_false' );

		add_action( 'customize_register', 'wptouch_customizer_remove_undesired_sections', 5 );
		add_action( 'customize_register', 'wptouch_customizer_remove_desktop_sections', 999 );

		// Force WPtouch to handle this as a mobile request
		add_filter( 'wptouch_user_agent', 'customizer_user_override' );

		// Make WordPress aware we're using the mobile theme not the desktop theme (not overriden by WPtouch)
		add_filter( 'pre_option_stylesheet', 'wptouch_get_current_theme_name', 50 );
		add_filter( 'pre_option_template', 'wptouch_get_current_theme_friendly_name', 50 );

		// Prevent the 'custom landing page' setting from being applied.
		add_filter( 'wptouch_redirect_target', 'wptouch_return_false' );

		// Load the custom landing page instead of the homepage (if set).
		$settings = $wptouch_pro->get_settings();
		if ( $settings->homepage_landing == 'select' ) {
			add_filter( 'request', 'wptouch_customizer_modify_homepage_query' );
		}

		// Allow settings modified in the customizer to be applied to the preview without save.
		add_filter( 'wptouch_settings_domain', 'wptouch_customizer_override_settings', 10, 2 );

		// Apply changes made in the customizer to the WPtouch settings objects.
		add_action( 'customize_save_after', 'wptouch_customizer_save');

		// Load JS required to enable theme events.
		add_action( 'wp_enqueue_scripts', 'wptouch_customizer_load_theme_js' );
	}
}

// Initialize the device switcher cookie. Values are toggled between 'desktop' and 'mobile' and used to control theme overrides.
if ( is_admin() && current_user_can( 'manage_options' ) && !isset( $_COOKIE[ 'wptouch_customizer_mode' ] ) ) {
	setcookie( 'wptouch_customizer_mode', 'desktop', 0, '/' );
}

// Domains whose settings should be stored by the Customizer in site options instead of theme mods.
global $options_domains;
$options_domains = array(
	'foundation',
	'addons'
);

// Once instantiated, tracks which settings are being handled by the Customizer. Allows us to accelerate setting save & avoid manipulating non-customizer settings.
global $customizable_settings;
$customizable_settings = array();

function wptouch_customizer_class() {
	$classes = '';
	if ( WPTOUCH_IS_FREE ) {
		$classes .= 'wptouch-free';
	}

	return $classes;
}

function wptouch_customizer_remove_desktop_sections( $wp_customize ) {
	// These are either not supported by WPtouch or should not appear to be separately managed.
	// Widget & Menu sections are removed separately.
	global $wp_version;
	if ( $wp_version < 4.3 ) {
		// We can show this section, but title and description are hidden with CSS, and only site icon is shown.
		$wp_customize->remove_section( 'title_tagline' );
	}
	$wp_customize->remove_section( 'themes' );
	$wp_customize->remove_section( 'header_image' );
	$wp_customize->remove_section( 'static_front_page' );
}

function wptouch_customizer_reset_settings() {
	// Called when the user resets WPtouch settings. WPtouch handles the settings object side, but we also need to clear out the data structures used by the Customizer. Settings defaults will be applied as normal.
	global $wptouch_pro;

	// Delete site option structures; no need to restrict these to the known options domains (n.b.: others should not exist at this point).
	$domains = $wptouch_pro->get_active_setting_domains();
	foreach ( $domains as $domain ) {
		delete_option( 'wptouch_customizer_options_' . $domain );
	}

	// Rebuild settings and objects from the WPtouch objects (e.g., defaults)
	$current_theme = wptouch_get_current_theme_name();
	delete_option( 'wptouch_customizer_initialized_' . $current_theme );

	// Delete theme mods – because we're not actively customizing here, WP believes the desktop theme is active, so override the value for the duration of the action. Disable the override once we're done so later requests execute normally.
	wptouch_customizer_begin_theme_override();
	remove_theme_mods();
	wptouch_customizer_end_theme_override();
}

function wptouch_customizer_restore_settings( $domain, $settings ) {
	// Called when the user restores WPtouch settings from a backup file. With the settings objects reconstructed, replace any existing Customizer values with those so that we complete the process.
	// As in the rest we must override the active theme name so that theme mods are targeted appropriately.
	// Only store values for settings that should appear in the customizer.

	global $wptouch_pro;
	global $options_domains;

	$customizable_settings = wptouch_get_customizable_settings();
	$current_theme = $wptouch_pro->get_current_theme_info();

	wptouch_customizer_begin_theme_override();
	if ( isset( $customizable_settings[ $domain ] ) || $domain == $current_theme->base ) {
		if ( in_array( $domain, $options_domains ) ) {
			$option_array = array();

			foreach ( $settings as $setting => $value ) {
				if ( in_array( $setting, $customizable_settings[ $domain ] ) || strstr( $setting, 'color' )  ) {
					$setting_use_name = str_replace( '[', '-----', str_replace( ']', '_____', $setting ) );
					$option_array[ $setting_use_name ] = $value;
				}
			}

			update_option( 'wptouch_customizer_options_' . $domain, $option_array );
		} else {
			foreach ( $settings as $setting => $value ) {
				if ( in_array( $setting, (array) $customizable_settings[ $domain ] ) || strstr( $setting, 'color' ) || $domain == $current_theme->base ) {
					set_theme_mod( 'wptouch_' . $setting, $value );
				}
			}
		}
	}

	wptouch_customizer_end_theme_override();
}

global $merging_setting;
$merging_setting = false;
function wptouch_customizer_merge_setting( $domain, $setting, $value ) {
	require_once( WPTOUCH_DIR . '/core/admin-load.php' );

	global $merging_setting;
	global $wptouch_pro;
	global $options_domains;

	wptouch_customizer_begin_theme_override();

	if ( !$merging_setting ) {
		$merging_setting = true;
		$customizable_settings = wptouch_get_customizable_settings();
		$current_theme = $wptouch_pro->get_current_theme_info();

		if ( isset( $customizable_settings[ $domain ] ) || $domain == $current_theme->base ) {
			if ( in_array( $domain, $options_domains ) ) {
				$option_array = get_option( 'wptouch_customizer_options_' . $domain );
				if ( in_array( $setting, $customizable_settings[ $domain ] ) || strstr( $setting, 'color' )  ) {
					$setting_use_name = str_replace( '[', '-----', str_replace( ']', '_____', $setting ) );
					$option_array[ $setting_use_name ] = $value;
				}
				update_option( 'wptouch_customizer_options_' . $domain, $option_array );
			} else {
				if ( in_array( $setting, $customizable_settings[ $domain ] ) || strstr( $setting, 'color' ) || $domain == $current_theme->base ) {
					set_theme_mod( 'wptouch_' . $setting, $value );
				}
			}
		}
		$merging_setting = false;
	}

	wptouch_customizer_end_theme_override();
}

function wptouch_customizer_load_theme_js() {
	// Provides a mechanism for loading JavaScript when customizing the mobile theme only.
	wp_enqueue_script(
		'wptouch-customizer-in-theme',
		WPTOUCH_ADMIN_URL . '/customizer/wptouch-customizer-theme.js',
		false,
		md5( WPTOUCH_VERSION ),
		true
	);
}

function wptouch_customizer_save( $object ) {
	wptouch_customizer_begin_theme_override();

	// Apply changes made in the customizer to the WPtouch settings objects.
	// Settings are prefixed with wptouch_ to avoid collision with other sources of theme mods & for clarity if viewing the database directly.
	global $wptouch_pro, $options_domains;

	// Colours are a special case in WPtouch, so they're handled separately
	$colors = foundation_get_theme_colors();
	foreach( $colors as $color ) {
		$settings = wptouch_get_settings( $color->domain );
		// Colours are stored by the customizer as theme mods
		$new_color = get_theme_mod( 'wptouch_' . $color->setting );
		if ( !$new_color ) {
			// The customizer allows users to save an null value for colours. Rather than allowing it to stay, we'll treat this as a 'reset to default' action.
			remove_theme_mod( 'wptouch_' . $color->setting );

			// Now replace the saved setting with the appropriate default
			$defaults = $wptouch_pro->get_setting_defaults( $color->domain );
			$settings->{ $color->setting } = $defaults->{ $color->setting };
		} else {
			$settings->{ $color->setting } = $new_color;
		}

		// Because colours could conceivably be in more than one domain we do a precautionary persist after each one.
		$settings->save();
	}

	$customizable_settings = wptouch_get_customizable_settings();

	// Now iterate over each of the domains with settings managed in the customizer and transfer their settings
	foreach ( $customizable_settings as $domain => $domain_customizable_settings ) {
		// Determine whether we should retrieve data from theme mods or a site option.
		if ( in_array( $domain, $options_domains ) ) {
			$domain_options = get_option( 'wptouch_customizer_options_' . $domain );
			$use_options = true;
		} else {
			$domain_options = false;
			$use_options = false;
		}

		// Load the current settings object for this domain
		$settings = wptouch_get_settings( $domain );

		// Next, iterate over the settings that can be set for this domain using the customizer
		foreach ( $domain_customizable_settings as $setting_name ) {
			if ( $use_options ) {
				// Persist from site option
				$options_setting_name = str_replace( '[', '-----', str_replace( ']', '_____', $setting_name ) );
				if ( isset( $domain_options[ $options_setting_name ] ) ) {
					$new_setting = $domain_options[ $options_setting_name ];
					$settings = apply_filters( 'wptouch_customizer_save__' . $domain . '__' . $setting_name, $settings, $new_setting );
					$settings->$setting_name = $new_setting;
				}
			} else {
				// Persist from theme mod
				if ( $new_setting = get_theme_mod( 'wptouch_' . $setting_name ) ) {
					$settings = apply_filters( 'wptouch_customizer_save__' . $domain . '__' . $setting_name, $settings, $new_setting );
					$settings->$setting_name = $new_setting;
				}
			}
		}

		$settings->save();
	}

	wptouch_customizer_end_theme_override();
}

function wptouch_get_customizable_settings() {
	global $customizable_settings;
	if ( count( (array) $customizable_settings ) > 0 ) {
		return $customizable_settings;
	} else {
		$panel_options = apply_filters( 'wptouch_admin_page_render_wptouch-admin-theme-settings', false );
		if ( isset( $panel_options[ 'Customizer' ] ) ) {
			$customize_settings = $panel_options[ 'Customizer' ];
		}

		if ( isset( $customize_settings ) && count( (array) $customize_settings->sections ) > 0 ) {
			foreach ( $customize_settings->sections as $section ) {
				if ( $section->settings ) {
					foreach ( $section->settings as $setting ) {
						$customizable_settings[ $setting->domain ][] = $setting->name;
					}
				}
			}
		}

		return $customizable_settings;
	}
}

function wptouch_initialize_customizer( $override = false ) {
	$current_theme = wptouch_get_current_theme_name();
	if ( !get_option( 'wptouch_customizer_initialized_' . $current_theme ) || $override ) {
		wptouch_customizer_initialize();
		add_option( 'wptouch_customizer_initialized_' . $current_theme, true );
	}
}

function wptouch_customizer_setup( $wp_customize ) {
	// Registers settings and controls for the WordPress Customizer

	global $wptouch_pro;
	global $options_domains;
	global $customizable_settings;

	if ( wptouch_is_customizing_mobile() ) {
		// We're in the customizer and editing the mobile theme.
		require_once( WPTOUCH_DIR . '/admin/customizer/wptouch-customize-control-multiple-checkbox.php' );

		// Prepare to set defaults; we'll need Foundation defaults for sure, but we'll load others as needed.
		$defaults = array(
			'foundation' => $wptouch_pro->get_setting_defaults( 'foundation' )
		);

		require_once( WPTOUCH_DIR . '/core/admin-load.php' );

		// Allow other areas of the plugin to perform actions before we get set up. These might be 'first load'-type calls
		do_action( 'wptouch_customizer_start_setup' );

		if ( wptouch_can_show_page( 'colors' ) ) {
			// Again, colours are a special case and are handled separately
			if ( function_exists( 'foundation_has_theme_colors' ) ) {
				$colors = foundation_get_theme_colors();

				foreach( $colors as $color ) {
					if ( !array_key_exists( $color->domain, $defaults ) ) {
						$defaults[ $color->domain ] = $wptouch_pro->get_setting_defaults( $color->domain );
					}

					$setting_name = $color->setting;

					$args = array(
						'default' => $defaults[ $color->domain ]->$setting_name
					);

					if ( $color->live_preview ) {
						$args[ 'transport' ] = 'postMessage';
					}

					// The Customizer constructs things in two parts
					$wp_customize->add_setting( 'wptouch_' . $setting_name, $args );

					$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wptouch_' . $setting_name, array(
						'label'    => __( $color->desc, 'wptouch-pro' ),
						'section'  => 'colors'
					) ) );
				}
			}
		}

		// TODO: Determine whether caching the settings structure is useful.
		// $customize_settings = get_transient( 'wptouch_customizer_settings' );
		$customize_settings = false;
		if ( !$customize_settings ) {
			global $panel_options;
			// Trigger WPtouch's settings control generation, then extract settings being sent to the Customizer.
			$panel_options = apply_filters( 'wptouch_admin_page_render_wptouch-admin-theme-settings', $panel_options );

			if ( isset( $panel_options[ 'Customizer' ] ) ) {
				$customize_settings = $panel_options[ 'Customizer' ];
			}

			// set_transient( 'wptouch_customizer_settings', $customize_settings, 60 * 60 );
		}

		if ( isset( $customize_settings ) && count( (array) $customize_settings->sections ) > 0 ) {

			asort( $customize_settings->sections );

			foreach ( $customize_settings->sections as $section ) {
				if ( $section->settings ) {
					$wp_customize->add_section( $section->slug, array(
						'title' => $section->name
					) );

					foreach ( $section->settings as $setting ) {
						if ( !isset( $defaults[ $setting->domain ] ) ) {
							$defaults[ $setting->domain ] = $wptouch_pro->get_setting_defaults( $setting->domain );
						}

						$args = array(
							'label' => $setting->desc,
							'section' => $section->slug,
							'type' => $setting->type,
						);

						if ( isset( $setting->tooltip ) ) {
							$args[ 'description' ] = $setting->tooltip;
						}

						$setting_args = array();

						if ( isset( $defaults[ $setting->domain ]->{ $setting->name } ) ) {
							$args[ 'default' ] = $defaults[ $setting->domain ]->{ $setting->name };
						}

						if ( $setting->type == 'checklist' ) {
							$setting_args[ 'sanitize_callback' ] = 'wptouch_sanitize_string_to_array';
						}

						if ( in_array( $setting->domain, $options_domains ) ) {
							$setting_use_name = 'wptouch_customizer_options_' . $setting->domain . '[' . str_replace( '[', '-----', str_replace( ']', '_____', $setting->name ) ) . ']';
							$wp_customize->add_setting( $setting_use_name, array_merge( array( 'type' => 'option' ), $setting_args ) );
						} else {

							$setting_use_name = 'wptouch_' . $setting->name;


							$value = get_theme_mod( $setting_use_name );
							$wp_customize->add_setting( $setting_use_name, array_merge( array( 'type' => 'theme_mod' ), $setting_args ) );
						}

						if ( $setting->type == 'image-upload' ) {
							unset( $args[ 'type' ] );

							$wp_customize->add_control(
								new WP_Customize_Image_Control(
									$wp_customize,
									$setting_use_name,
									$args
								)
							);

						} elseif ( $setting->type == 'checklist' ) {
							$args[ 'choices' ] = $setting->extra;

							$wp_customize->add_control(
								new WPtouch_Customize_Control_Multiple_Checkbox(
									$wp_customize,
									$setting_use_name,
									$args
								)
							);
						} else {
							if ( $setting->type == 'list' || $setting->type == 'select' || $setting->type == 'radiolist' || $setting->type == 'radio' ) {
								$args[ 'type' ] = 'select';
								$args[ 'choices' ] = $setting->extra;
							} elseif ( $setting->type == 'range' ) {
								$args[ 'input_attrs' ] = $setting->extra;
							}
							$wp_customize->add_control(
								$setting_use_name,
								$args
							);
						}
					}
				}
			}

			if ( $wp_customize->is_preview() && !is_admin() ) {
				add_action( 'wp_footer', 'wptouch_customizer_ajax_callbacks', 21 );
			}
		}
	} else {
		// In the customizer and editing the desktop theme. To keep things tidy, hide mobile menu locations in the menu picker
		$menus = get_registered_nav_menus();
		foreach( $menus as $identifier => $menu ) {
			if ( strstr( $identifier, 'wptouch_' ) ) {
				unregister_nav_menu( $identifier );
			}
		}
	}
}

function wptouch_customizer_initialize() {
	// When we first access the customizer, construct its settings data from the existing WPtouch settings object (if any). This will ensure any inherited values are shown correctly.
	global $customizable_settings;

	global $wptouch_pro;
	$domains = $wptouch_pro->get_active_setting_domains();

	foreach ( $domains as $domain ) {
		$settings = wptouch_get_settings( $domain );
		wptouch_customizer_restore_settings( $domain, $settings );
	}
}

function wptouch_customizer_remove_undesired_sections( $wp_customize ) {
	if ( isset( $wp_customize->nav_menus ) ) {
		/** @var WP_Customize_Manager $wp_customize */
		remove_action( 'customize_controls_enqueue_scripts', array( $wp_customize->nav_menus, 'enqueue_scripts' ) );
		remove_action( 'customize_register', array( $wp_customize->nav_menus, 'customize_register' ), 11 );
		remove_filter( 'customize_dynamic_setting_args', array( $wp_customize->nav_menus, 'filter_dynamic_setting_args' ) );
		remove_filter( 'customize_dynamic_setting_class', array( $wp_customize->nav_menus, 'filter_dynamic_setting_class' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'print_templates' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->nav_menus, 'available_items_template' ) );
		remove_action( 'customize_preview_init', array( $wp_customize->nav_menus, 'customize_preview_init' ) );
	}

	if ( isset( $wp_customize->widgets ) ) {
		remove_filter( 'customize_dynamic_setting_args', array( $wp_customize->widgets, 'filter_customize_dynamic_setting_args' ), 10, 2 );
		remove_action( 'customize_controls_init', array( $wp_customize->widgets, 'customize_controls_init' ) );
		remove_action( 'customize_register', array( $wp_customize->widgets, 'schedule_customize_register' ), 1 );
		remove_action( 'customize_controls_enqueue_scripts', array( $wp_customize->widgets, 'enqueue_scripts' ) );
		remove_action( 'customize_controls_print_styles', array( $wp_customize->widgets, 'print_styles' ) );
		remove_action( 'customize_controls_print_scripts', array( $wp_customize->widgets, 'print_scripts' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->widgets, 'print_footer_scripts' ) );
		remove_action( 'customize_controls_print_footer_scripts', array( $wp_customize->widgets, 'output_widget_control_templates' ) );
		remove_action( 'customize_preview_init', array( $wp_customize->widgets, 'customize_preview_init' ) );
		remove_filter( 'customize_refresh_nonces', array( $wp_customize->widgets, 'refresh_nonces' ) );

	}
}

function wptouch_customizer_scripts() {
	if ( wptouch_is_customizing_mobile() ) {
		wp_enqueue_style(
			'wptouch-theme-customizer-css',
			WPTOUCH_URL . '/admin/customizer/wptouch-customizer.css',
			'',
			md5( WPTOUCH_VERSION )
		);

		wp_enqueue_style(
			'wptouch-admin-fontello',
			WPTOUCH_URL . '/admin/fontello/css/fontello-embedded.css',
			'',
			md5( WPTOUCH_VERSION )
		);

		wp_enqueue_script(
			'wptouch-theme-customizer-js',
			WPTOUCH_URL . '/admin/customizer/wptouch-customizer.js',
			array( 'jquery' ),
			md5( WPTOUCH_VERSION ),
			true
		);

		/**
		 * WPtouch Load color extraction js library.
		 *
		 * Whether to load the necessary js to use in the customizer
		 * for getting colors from an image, allowing this to be set
		 * on a per theme basis.
		 *
		 * @since 4.3.25
		 *
		 * @param boolean
		 */
		if ( apply_filters( 'wptouch_load_color_extraction_lib', false ) ) {
			wp_enqueue_script(
				'color-extraction-customizer-js',
				WPTOUCH_URL . '/admin/customizer/color-thief/color-thief.min.js',
				array(),
				md5( WPTOUCH_VERSION ),
				true
			);
		}

		global $wptouch_pro;
		$theme = $wptouch_pro->get_current_theme_info();
		$pro_or_free = ( !defined( 'WPTOUCH_IS_FREE' ) ) ? 'yes' : 'no';
		$customizer_params = array(
			'device_orientation' => __( 'Device Orientation', 'wptouch-pro' ),
			'device_tags' => $theme->tags,
			'settings_url' => admin_url( 'admin.php?page=wptouch-admin-general-settings' ),
			'wptouch_is_pro' => $pro_or_free,
			'mobile_preview' => wptouch_is_customizing_mobile(),
		);

		wp_localize_script( 'wptouch-theme-customizer-js', 'WPtouchCustomizer', $customizer_params );
	}

	wp_enqueue_script(
		'jquery-plugins',
		WPTOUCH_URL . '/admin/js/wptouch-admin-plugins.js',
		array( 'jquery' ),
		md5( WPTOUCH_VERSION ),
		true
	);

	wp_enqueue_script(
		'wptouch-theme-customizer-switch',
		WPTOUCH_URL . '/admin/customizer/wptouch-customizer-switch.js',
		array( 'jquery' ),
		md5( WPTOUCH_VERSION ),
		true
	);

	$customizer_switch_params = array(
		'mobile_switch' => __( 'Switch to Mobile Theme', 'wptouch-pro' ),
		'desktop_switch' => __( 'Switch to Desktop Theme', 'wptouch-pro' )
	);
	wp_localize_script( 'wptouch-theme-customizer-switch', 'WPtouchCustomizerSwitch', $customizer_switch_params );


	// It's a 3.x stock theme, or older copied theme, load the shim js file to help out displaced settings in the customizer
	if ( wptouch_theme_version_compare( '4', '<' ) ) {
		wp_enqueue_script(
			'wptouch-theme-customizer-shim',
			WPTOUCH_URL . '/admin/customizer/wptouch-customizer-shim.js',
			array( 'jquery' ),
			md5( WPTOUCH_VERSION ),
			true
		);
	}
}

function wptouch_customizer_ajax_callbacks() {
	?>
	<script type="text/javascript">
		function wptouchCustomizerGetLuma( hexvalue ) {
			var c = hexvalue.substring(1);      // strip #
			var rgb = parseInt(c, 16);   // convert rrggbb to decimal
			var r = (rgb >> 16) & 0xff;  // extract red
			var g = (rgb >>  8) & 0xff;  // extract green
			var b = (rgb >>  0) & 0xff;  // extract blue

			return 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709'
		}

		( function( jQuery ){
			<?php
			if ( foundation_has_theme_colors() ) {
			$colors = foundation_get_theme_colors();
			foreach( $colors as $color ) {
			?>
			wp.customize('wptouch_<?php echo esc_html($color->setting); ?>',function( value ) {
				value.bind( function( to ) {
					<?php if ( $color->fg_selectors ) { ?>
					jQuery( '<?php echo esc_attr( $color->fg_selectors ); ?>' ).css('color', to ? to : '' );
					<?php } ?>

					<?php if ( $color->bg_selectors ) { ?>
					jQuery( '<?php echo esc_attr( $color->bg_selectors ); ?>' ).css('background-color', to ? to : '' );
					<?php } ?>

					<?php if ( $color->luma_threshold ) { ?>
					if ( wptouchCustomizerGetLuma( to ) < <?php echo esc_attr( $color->luma_threshold ); ?> ) {
						jQuery( 'body' ).removeClass( 'light-<?php echo esc_attr( $color->luma_class ); ?>' );
						jQuery( 'body' ).addClass( 'dark-<?php echo esc_attr( $color->luma_class ); ?>' );
					} else {
						jQuery( 'body' ).addClass( 'light-<?php echo esc_attr( $color->luma_class ); ?>' );
						jQuery( 'body' ).removeClass( 'dark-<?php echo esc_attr( $color->luma_class ); ?>' );
					}
					<?php } ?>
				});
			});
			<?php
			}
			}
			?>
		} )( jQuery )
	</script>
	<?php
}

function wptouch_customizer_override_settings( $settings, $domain ) {
	global $wptouch_pro;

	if ( isset( $wptouch_pro->post[ 'customized' ] ) ) {
		$customized = json_decode( $wptouch_pro->post[ 'customized' ], true );
		if ( is_array( $customized ) ) {
			foreach ( $customized as $setting_name => $setting_value ) {
				$setting_value = apply_filters( 'wptouch_customizer_override_' . $setting_name, $setting_value );

				if ( strstr( $setting_name, 'color' ) && !$setting_value) {
					$true_setting_name = substr( $setting_name, 8 );
					$defaults = $wptouch_pro->get_setting_defaults( $domain );
					if ( isset( $defaults->$true_setting_name ) ) {
						$setting_value = $defaults->$true_setting_name;
					}
				}

				if ( substr( $setting_name, 0, 8 ) == 'wptouch_' ) {
					if ( strstr( $setting_name, 'wptouch_customizer_options_' ) ) {
						// Site option!
						preg_match( '/(.*?)\[(.*?)\]/', $setting_name, $setting_name_parts );
						$domain = substr( $setting_name_parts[ 1 ], 27 );
						$setting_name = str_replace( '-----', '[', str_replace( '_____', ']', $setting_name_parts[ 2 ] ) );
					}

					if ( strpos( $setting_name, '[' ) ) {
						// We have an array setting that we need to massage.
						preg_match( '/(.*?)\[(.*?)\]/', $setting_name, $setting_name_parts );

						if ( substr( $setting_name_parts[ 1 ], 0, 8 ) == 'wptouch_' ) {
							$setting_name_parts[ 1 ] = substr( $setting_name_parts[ 1 ], 8 );
						}

						if ( isset( $settings->{ $setting_name_parts[ 1 ] } ) ) {
							$settings->{ $setting_name_parts[ 1 ] }[ $setting_name_parts[ 2 ] ] = $setting_value;
						}
					} else {
						if ( substr( $setting_name, 0, 8 ) == 'wptouch_' ) {
							$setting_name = substr( $setting_name, 8 );
							if ( isset( $settings->$setting_name ) ) {
								$settings->$setting_name = $setting_value;
							}
						} else {
							if ( isset( $settings->$setting_name ) ) {
								$settings->$setting_name = $setting_value;
							}
						}
					}
				}
			}
		}
		// $settings = apply_filters( 'wptouch_process_previewed_settings', $settings, $domain );
		return $settings;
	} else {
		return $settings;
	}
}

function wptouch_is_customizing() {
	if ( !isset( $_COOKIE[ 'wptouch_customizer_mode' ] ) ) {
		return false;
	} else {
		if ( $_COOKIE[ 'wptouch_customizer_mode' ] == 'desktop' ) {
			return false;
		} else {
			global $wp_customize;
			return is_a( $wp_customize, 'WP_Customize_Manager' );
		}
	}
}

function wptouch_is_customizing_mobile( $skip_override = false ) {
	if ( $skip_override ) { // generally set by the 'is_mobile_device' filter. If true, don't worry about overrides.
		return true;
	}

	if ( isset($_COOKIE[ 'wptouch_customizer_mode' ]) && wptouch_is_customizing() && sanitize_text_field(wp_unslash($_COOKIE[ 'wptouch_customizer_mode' ])) == 'mobile' ) {
		return true;
	} else {
		return false;
	}
}

function customizer_user_override( $user_agent ) {
	return 'iPhone';
}

function wptouch_get_current_theme_friendly_name( $value=false ) {
	// Override the value of 'stylesheet' on customizer.
	global $wptouch_pro;
	$settings = $wptouch_pro->get_settings();
	return $settings->current_theme_friendly_name;
}

function wptouch_get_current_theme_name( $value=false ) {
	// Override the value of 'stylesheet' on customizer.
	global $wptouch_pro;
	$settings = $wptouch_pro->get_settings();
	return $settings->current_theme_name;
}

function wptouch_customizer_begin_theme_override() {
	global $wptouch_queued_items;

	if ( $wptouch_queued_items == 0 ) {
		add_filter( 'pre_option_stylesheet', 'wptouch_get_current_theme_name', 50 );
	}

	$wptouch_queued_items++;
}

function wptouch_customizer_end_theme_override() {
	global $wptouch_queued_items;

	$wptouch_queued_items--;

	if ( $wptouch_queued_items == 0 ) {
		remove_filter( 'pre_option_stylesheet', 'wptouch_get_current_theme_name', 50 );
	}
}

function wptouch_customizer_port_image( $customizer_setting, $source_setting, $settings_domain = 'foundation' ) {
	global $options_domains, $wp_version;
	$sideload_image = false;
	$settings = wptouch_get_settings( $settings_domain );
	$upload_dir = wp_upload_dir();

	if ( $source_setting != false && isset( $settings->$source_setting ) && $settings->$source_setting != false ) {

		if ( in_array( $settings_domain, $options_domains ) ) {
			$domain_options = get_option( 'wptouch_customizer_options_' . $settings_domain );
			if ( strstr( $customizer_setting, 'wptouch_' ) ) {
				$customizer_setting = substr( $customizer_setting, 8 );
			}
			$customizer_setting = str_replace( '[', '-----', str_replace( ']', '_____', $customizer_setting ) );
			$use_options = true;

			if ( !isset( $domain_options[ $customizer_setting ] ) || !strstr( $domain_options[ $customizer_setting ], $upload_dir[ 'baseurl' ] ) ) {
				$sideload_image = true;
			}
		} else {
			$use_options = false;
			if ( ( !$customizer_image = get_theme_mod( $customizer_setting ) ) || !strstr( $customizer_image, $upload_dir[ 'baseurl' ] ) ) {
				$sideload_image = true;
			}
		}

		if ( $sideload_image ) {
			require_once(ABSPATH . 'wp-admin/includes/media.php');
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			if ( isset( $settings->$source_setting ) && $settings->$source_setting != '' && $settings->$source_setting != false ) {
				$image = media_sideload_image( foundation_prepare_uploaded_file_url( $settings->$source_setting ), 0 );
				if ( !is_object( $image ) ) {
					preg_match( '/\'(.*?)\'/', $image, $image_url);
					if ( $use_options ) {
						$domain_options[ $customizer_setting ] = $image_url[ 1 ];
						update_option( 'wptouch_customizer_options_' . $settings_domain, $domain_options );
					} else {
						set_theme_mod( $customizer_setting, $image_url[ 1 ] );
					}
				}
			}
		}
	}
}

function wptouch_sanitize_string_to_array( $values ) {
	if ( $values == '' ) { return array(); }
	$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;
	return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

function wptouch_customizer_modify_homepage_query( $query_vars ) {
	$settings = wptouch_get_settings();

	$dummy_query = new WP_Query();  // the query isn't run if we don't pass any query vars
	$dummy_query->parse_query( $query_vars );

	if ( count( (array) $query_vars ) == 0 ) { // Front page
		$query_vars[ 'page_id' ] = $settings->homepage_redirect_wp_target;
	}

	return $query_vars;
}
