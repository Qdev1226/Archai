<?php
function foundation_inline_flickity_css() {
	echo '<style data-context="foundation-flickity-css">';
	echo esc_html( file_get_contents( dirname( __FILE__ ) . '/flickity.min.css' ) );
	echo '</style>';
}
add_action( 'wp_head', 'foundation_inline_flickity_css' );

function foundation_flickity_init() {
	wp_enqueue_script(
		'foundation_flickity',
		foundation_get_base_module_url() . '/flickity/flickity.pkgd.min.js',
		array( 'jquery' ),
		md5( FOUNDATION_VERSION ),
		true
	);

	wp_enqueue_script(
		'foundation_flickity_wptouch',
		foundation_get_base_module_url() . '/flickity/flickity-wptouch.min.js',
		array( 'foundation_flickity' ),
		md5( FOUNDATION_VERSION ),
		true
	);
}
add_action( 'foundation_module_init_mobile', 'foundation_flickity_init' );
