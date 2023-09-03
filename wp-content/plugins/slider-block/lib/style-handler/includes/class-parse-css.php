<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('EbStyleHandlerParseCss')) {
    class EbStyleHandlerParseCss
    {

        private static $instance;

        public static function init()
        {
            if (null === self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct()
        {

        }

        /**
         * Recursive function for parsing blocks
         */
        public static function eb_block_style_recursive($block, &$eb_blocks)
        {
            if (count($block) > 0) {
                foreach ($block as $item) {
                    $attributes = $item['attrs'];

                    $blockId = "";
                    if (isset($attributes['blockId']) && !empty($attributes['blockId'])) {
                        $blockId = $attributes['blockId'];
                    }
                    $blockMeta = "";
                    if (isset($attributes['blockMeta']) && !empty($attributes['blockMeta'])) {
                        $blockMeta = $attributes['blockMeta'];
                    }
                    $commonStyles = "";
                    if (isset($attributes['commonStyles']) && !empty($attributes['commonStyles'])) {
                        $commonStyles = $attributes['commonStyles'];
                    }
                    $customCss = "";
                    if (isset($attributes['customCss']) && !empty($attributes['customCss'])) {
                        $customCss = $attributes['customCss'];
                    }

                    if (isset($attributes['ref']) && !empty($attributes['ref']) && $item["blockName"] === "core/block") {
                        $reusable_block = get_post($attributes['ref']);
                        $reusable_content = parse_blocks($reusable_block->post_content);
                        $reusable_blocks = [];
                        $eb_blocks["reusableBlocks"][$attributes['ref']] = self::eb_block_style_recursive($reusable_content, $reusable_blocks);
                    } else {
                        if (isset($item["innerBlocks"]) && count($item["innerBlocks"]) > 0) {
                            self::eb_block_style_recursive($item['innerBlocks'], $eb_blocks);
                            if (isset($attributes['blockMeta']) && !empty($attributes['blockMeta'])) {
                                $eb_blocks[$blockId] = array(
                                    'blockMeta' => $blockMeta,
                                    'commonStyles' => $commonStyles,
                                    'customCss' => $customCss,
                                );
                            }
                        } else if (isset($attributes['blockMeta']) && !empty($attributes['blockMeta'])) {
                            $eb_blocks[$blockId] = array(
                                'blockMeta' => $blockMeta,
                                'commonStyles' => $commonStyles,
                                'customCss' => $customCss,
                            );
                        }
                    }
                }
            }

            return $eb_blocks;
        }

        /**
         * Blockarray to Style Array Function
         */
        public static function blocks_to_style_array($blocks) {
            $style_array = [];
            if (is_array($blocks) && count($blocks) > 0) {
                foreach ($blocks as $blockId => $block) {

                    $style_array[$blockId] = array(
                        'desktop' => "",
                        'tab' => "",
                        'mobile' => "",
                        'customCss' => "",
                    );

                    if (is_array($block) && count($block) > 0) {
                        foreach ($block as $value) {
                            if (is_array($value) && count($value) > 0) {
                                if (isset($value["desktop"])) {
                                    $style_array[$blockId]["desktop"] .= $value["desktop"];
                                }
                                if (isset($value["tab"])) {
                                    $style_array[$blockId]["tab"] .= $value["tab"];
                                }
                                if (isset($value["mobile"])) {
                                    $style_array[$blockId]["mobile"] .= $value["mobile"];
                                }
                            }
                            else if ( isset($block['customCss']) && is_string($block['customCss']) && strlen($block['customCss']) > 0 ) {
                                $style_array[$blockId]["customCss"] .= $block['customCss'];
                            }
                        }
                    }
                }
            }
            return $style_array;
        }

		/**
		 * Enqueue frontend css for post if have one
		 * @param string
		 * @return string
		 * @since 1.0.2
		 */
		public static function build_css($style_object)
		{
			$block_styles = $style_object;

			$css = '';
			foreach ($block_styles as $block_style_key => $block_style) {
				if (!empty($block_css = (array) $block_style)) {
					$css .= sprintf(
						'/* %1$s Starts */',
						$block_style_key
					);
					foreach ($block_css as $media => $style) {
						switch ($media) {
							case "desktop":
								$css .= preg_replace('/\s+/', ' ', $style);
								break;
							case "tab":
								$css .= ' @media(max-width: 1024px){';
								$css .= preg_replace('/\s+/', ' ', $style);
								$css .= '}';
								break;
							case "mobile":
								$css .= ' @media(max-width: 767px){';
								$css .= preg_replace('/\s+/', ' ', $style);
								$css .= '}';
								break;
							case "customCss":
								$css .= preg_replace('/\s+/', ' ', $style);
								break;
						}
					}
					$css .= sprintf(
						'/* =%1$s= Ends */',
						$block_style_key
					);
				}
			}
			return trim($css);
		}

		/**
		 * Helper function to get string between 2 string
		 * @since 3.3.0
		 */
		public static function get_between_data($string, $start, $end)
		{
			$pos_string = stripos($string, $start);
			$substr_data = substr($string, $pos_string);
			$string_two = substr($substr_data, strlen($start));
			$second_pos = stripos($string_two, $end);
			$string_three = substr($string_two, 0, $second_pos);

			// remove whitespaces from result
			$result_unit = trim($string_three);

			// return result_unit
			return $result_unit;
		}

        /**
         * Is Gutenberg Editor
         */
        public static function eb_stylehandler_is_gutenberg_editor($pagenow, $param)
        {
            if ($pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'site-editor.php') {
                return true;
            }

            if ($pagenow == 'themes.php' && !empty($param) && str_contains($param, 'gutenberg-edit-site')) {
                return true;
            }

            return false;
        }
    }

    EbStyleHandlerParseCss::init();
}