<?php
/**
 * Load google fonts.
 */

namespace GutSliders\FontsLoader;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class FontsLoader {

	private static $all_fonts = [];
	
	/**
	 * The Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'fonts_loader' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'fonts_loader' ] );
		add_action('gutsliders_render_block', [ $this, 'font_generator' ]);
	}

	/**
	 * Font generator.
	 */
	public function font_generator($block) {
        if (isset($block['attrs']) && is_array($block['attrs'])) {
            $attributes = $block['attrs'];
            foreach ($attributes as $key => $value) {
                if (!empty($value) && strpos($key, 'gutsliders_') === 0 && strpos($key, 'FontFamily') !== false) {
                    self::$all_fonts[] = $value;
                }
            }
        }
    }

	/**
	 * Load fonts.
	 *
	 * @access public
	 */
	public function fonts_loader() {
		if (is_array(self::$all_fonts) && count(self::$all_fonts) > 0) {

			$fonts = array_filter(array_unique(self::$all_fonts));

			if (!empty($fonts)) {
				$system = array(
					'Arial',
					'Tahoma',
					'Verdana',
					'Helvetica',
					'Times New Roman',
					'Trebuchet MS',
					'Georgia',
				);
				$gfonts = '';
				$gfonts_attr = ':100,200,300,400,500,600,700,800,900';
				foreach ($fonts as $font) {
					if (!in_array($font, $system, true) && !empty($font)) {
						$gfonts .= str_replace(' ', '+', trim($font)) . $gfonts_attr . '|';
					}
				}
				if (!empty($gfonts)) {
					$query_args = array(
						'family' => $gfonts,
					);
					wp_register_style(
						'gutsliders-fonts',
						add_query_arg($query_args, '//fonts.googleapis.com/css'),
						array()
					);
					wp_enqueue_style('gutsliders-fonts');
				}
				// Reset.
				$gfonts = '';
			}
		}
	}
}