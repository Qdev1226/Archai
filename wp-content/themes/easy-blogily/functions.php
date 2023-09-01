<?php 
add_action( 'wp_enqueue_scripts', 'easy_blogily_enqueue_styles' );
function easy_blogily_enqueue_styles() {
	wp_enqueue_style( 'easy-blogily-parent-style', get_template_directory_uri() . '/style.css' ); 
} 

// Load new fonts
function easy_blogily_google_fonts(){
	wp_enqueue_style('easy-blogily-google-fonts', '//fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap', false);
}
add_action('wp_enqueue_scripts', 'easy_blogily_google_fonts');



// Load new header
require get_stylesheet_directory() . '/inc/custom-header.php';


function easy_blogily_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_section('header_image')->title = __( 'Header Settings', 'easy-blogily' );
	$wp_customize->get_section('colors')->title = __( 'Other Colors', 'easy-blogily' );


	$wp_customize->selective_refresh->add_partial(
		'custom_logo',
		array(
			'selector'        => '.header-titles [class*=site-]:not(.logo-container .logofont)',
			'render_callback' => 'easy_blogily_customize_partial_site_logo',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'retina_logo',
		array(
			'selector'        => '.header-titles [class*=site-]:not(.logo-container .logofont)',
			'render_callback' => 'easy_blogily_customize_partial_site_logo',
		)
	);

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.logo-container .logofont',
			'render_callback' => 'easy_blogily_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.logo-container .logofont',
			'render_callback' => 'easy_blogily_customize_partial_blogdescription',
		) );
	}


$wp_customize->add_section( 'footer_settings', array(
		'title'      => __('Footer Settings','easy-blogily'),
		'priority'   => 1,
		'capability' => 'edit_theme_options',
		) );

	$wp_customize->add_setting( 'footer_headline_color', array(
		'default'           => '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
		) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_headline_color', array(
		'label'       => __( 'Headline Color', 'easy-blogily' ),
		'section'     => 'footer_settings',
		'priority'   => 1,
		'settings'    => 'footer_headline_color',
		) ) );
	$wp_customize->add_setting( 'footer_text_color', array(
		'default'           => '#656565',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
		) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
		'label'       => __( 'Text Color', 'easy-blogily' ),
		'section'     => 'footer_settings',
		'priority'   => 1,
		'settings'    => 'footer_text_color',
		) ) );
	$wp_customize->add_setting( 'footer_link_color', array(
		'default'           => '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
		) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_link_color', array(
		'label'       => __( 'Link Color', 'easy-blogily' ),
		'section'     => 'footer_settings',
		'priority'   => 1,
		'settings'    => 'footer_link_color',
		) ) );
	$wp_customize->add_setting( 'footer_border_color', array(
		'default'           => '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
		) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_border_color', array(
		'label'       => __( 'Border Color', 'easy-blogily' ),
		'section'     => 'footer_settings',
		'priority'   => 1,
		'settings'    => 'footer_border_color',
		) ) );

	$wp_customize->add_setting( 'footer_background_color', array(
		'default'           => '#000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
		) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_background_color', array(
		'label'       => __( 'Background Color', 'easy-blogily' ),
		'section'     => 'footer_settings',
		'priority'   => 1,
		'settings'    => 'footer_background_color',
		) ) );

	$wp_customize->add_setting( 'only_show_header_frontpage', array(
		'default' => 0,
		'sanitize_callback' => 'sanitize_text_field',
		) );

	$wp_customize->add_control( 'only_show_header_frontpage', array(
		'label'    => __( 'Only show header image on front page', 'easy-blogily' ),
		'section'  => 'header_image',
		'priority' => 1,
		'settings' => 'only_show_header_frontpage',
		'type'     => 'checkbox',
		) );

	$wp_customize->add_setting( 'header_img_text', array(
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
		'capability'        => 'edit_theme_options',
		) );

	$wp_customize->add_control( 'header_img_text', array(
		'label'    => __( "Title", 'easy-blogily' ),
		'section'  => 'header_image',
		'type'     => 'text',
		'priority' => 1,
		) );
	$wp_customize->add_setting( 'header_img_text_tagline', array(
		'type'              => 'theme_mod',
		'sanitize_callback' => 'sanitize_text_field',
		'capability'        => 'edit_theme_options',
		) );

	$wp_customize->add_control( 'header_img_text_tagline', array(
		'label'    => __( "Tagline", 'easy-blogily' ),
		'section'  => 'header_image',
		'type'     => 'text',
		'priority' => 1,
		) );

	$wp_customize->add_setting( 'header_img_textcolor', array(
		'default'           => '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
		) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_img_textcolor', array(
		'label'       => __( 'Text Color', 'easy-blogily' ),
		'section'     => 'header_image',
		'priority'   => 1,
		'settings'    => 'header_img_textcolor',
		) ) );


}
add_action( 'customize_register', 'easy_blogily_customize_register', 99999 );




if(! function_exists('easy_blogily_customizer_css_final_output' ) ):
	function easy_blogily_customizer_css_final_output(){
		?>

		<style type="text/css">

			.page-numbers li a, .page-numbers.current, span.page-numbers.dots, .main-navigation ul li a:hover { color: <?php echo esc_attr(get_theme_mod( 'easy_blogily_main_color')); ?>; }
			.comments-area p.form-submit input, a.continuereading, .blogpost-button, .blogposts-list .entry-header h2:after { background: <?php echo esc_attr(get_theme_mod( 'easy_blogily_main_color')); ?>; }


		</style>
	<?php }
	add_action( 'wp_head', 'easy_blogily_customizer_css_final_output' );
endif;



if ( ! function_exists( 'easy_blogily_customize_partial_site_logo' ) ) {
	/**
	 * Render the site logo for the selective refresh partial.
	 *
	 * Doing it this way so we don't have issues with `render_callback`'s arguments.
	 */
	function easy_blogily_customize_partial_site_logo() {
		the_custom_logo();
	}
}



/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function easy_blogily_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function easy_blogily_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */

function easy_blogily_customize_preview_js() {
	wp_enqueue_script( 'easy-blogily-customizer', get_stylesheet_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '1', true );
	wp_dequeue_style( 'marketingly-customizer' );
}
add_action( 'customize_preview_init', 'easy_blogily_customize_preview_js', 99999 );



/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for child theme Lightweightly Blog for publication on WordPress.org
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */

require_once get_stylesheet_directory() . '/inc/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'easy_blogily_register_required_plugins' );

function easy_blogily_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => 'Social Share and Follow Buttons',
			'slug'      => 'superb-social-share-and-follow-buttons',
			'required'  => false,
		),
		array(
			'name'      => 'Tables',
			'slug'      => 'superb-tables',
			'required'  => false,
		),
		array(
			'name'      => 'Recent Posts with Thumbnails',
			'slug'      => 'superb-recent-posts-with-thumbnail-images',
			'required'  => false,
		),
	);

	$config = array(
		'id'           => 'easy-blogily',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
