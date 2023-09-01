<?php
/**
 * Right now (v4.3.7) all themes use the WPtouch Icons. For that reason,
 * we include the font files in the primary `foundation/default/style.css` file.
 */

add_action( 'foundation_module_init_mobile', 'foundation_wptouch_icons_init' );

function foundation_inline_wptouch_icons_css() {
	echo '<style data-context="foundation-wptouch-icons-css">';
	echo wp_kses_post( file_get_contents( dirname( __FILE__ ) . '/css/wptouch-icons.min.css' ) );
	echo '</style>';
}

function foundation_wptouch_icons_init() {
	add_action( 'wp_head', 'foundation_inline_wptouch_icons_css' );
}
