<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'EbStyleHandler' ) ) {
    final class EbStyleHandler {
        private static $instance;
        /**
         * Holds block styles array
         *
         * @var array
         */
        public static $_block_styles = [];

        public static function init() {
            if ( null === self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct() {
            $this->load_style_handler_dependencies();

            add_action( 'wp_enqueue_scripts', [$this, 'enqueue_frontend_css'] );
            add_action( 'save_post', [$this, 'stylehandler_get_post_content'], 10, 3 );
            add_action( 'wp', [$this, 'stylehandler_generate_post_content'] );
            add_action( 'rest_after_save_widget', [$this, 'eb_save_widget_action'], 10, 4 );
            // FSE assets generation
            add_action( 'init', function () {
                if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
                    add_filter( "404_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "archive_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "category_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "frontpage_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "home_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "index_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "page_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "search_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "single_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "singular_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "tag_template", [$this, 'fse_assets_generation'], 99, 3 );
                    add_filter( "taxonomy_template", [$this, 'fse_assets_generation'], 99, 3 );
                }
            }, 999 );
        }
        /**
         * Generate FSE Assets
         */
        public function fse_assets_generation( $template, $type, $templates ) {
            $block_template = resolve_block_template( $type, $templates, $template );
            if ( ! empty( $block_template ) ) {
                $parsed_content = parse_blocks( $block_template->content );
                if ( is_array( $parsed_content ) && ! empty( $parsed_content ) ) {
                    foreach ( $parsed_content as $content ) {
                        if (  ( 'core/template-part' == $content['blockName'] ) || ( 'core/template' == $content['blockName'] ) ) {
                            $post_ids = isset( $content['attrs']['slug'] ) ? self::eb_get_post_content_by_post_name( $content['attrs']['slug'] ) : [];

                            if ( ! empty( $post_ids ) ) {
                                foreach ( $post_ids as $id ) {
                                    $post_id        = (int) $id['ID'];
                                    $post           = get_post( $post_id );
                                    $parsed_content = parse_blocks( $post->post_content );
                                    $this->eb_write_css_from_content( $post, $post_id, $parsed_content );
                                }
                            }
                        } else {
                            $post_ids = self::eb_get_post_content_by_post_name( $block_template->slug );
                            if ( ! empty( $post_ids ) ) {
                                foreach ( $post_ids as $id ) {
                                    $post_id        = (int) $id['ID'];
                                    $post           = get_post( $post_id );
                                    $parsed_content = parse_blocks( $post->post_content );
                                    $this->eb_write_css_from_content( $post, $post_id, $parsed_content );
                                }
                            }
                        }
                    }
                }
            }
            return $template;
        }

        /**
         * Write CSS
         */
        public function eb_write_css_from_content( $post, $post_id, $parsed_content ) {
            $eb_blocks          = [];
            $recursive_response = EbStyleHandlerParseCss::eb_block_style_recursive( $parsed_content, $eb_blocks );
            $reusable_Blocks    = ! empty( $recursive_response['reusableBlocks'] ) ? $recursive_response['reusableBlocks'] : [];
            // remove empty reusable blocks
            $reusable_Blocks = array_filter( $reusable_Blocks, function ( $v ) {
                return ! empty( $v );
            } );
            unset( $recursive_response["reusableBlocks"] );
            $style       = EbStyleHandlerParseCss::blocks_to_style_array( $recursive_response );
            $reusableIds = $reusable_Blocks && is_array( $reusable_Blocks ) ? array_keys( $reusable_Blocks ) : [];
            if ( ! empty( $reusableIds ) ) {
                update_option( '_eb_reusable_block_ids', $reusableIds );
            }
            update_post_meta( $post_id, '_eb_reusable_block_ids', $reusableIds );
            $this->write_block_css( $style, $post ); //Write CSS file for this page

            if ( ! empty( $reusable_Blocks ) ) {
                foreach ( $reusable_Blocks as $blockId => $block ) {
                    $style = EbStyleHandlerParseCss::blocks_to_style_array( $block );
                    $this->write_reusable_block_css( $style, $blockId );
                }
            }
        }

        /**
         * Save Widget CSS when Widget is saved
         * @return void
         * @since 3.5.3
         */
        function eb_save_widget_action( $id, $sidebar_id, $request, $creating ) {
            $parsed_content = isset( $request['instance']['raw']['content'] ) ? parse_blocks( $request['instance']['raw']['content'] ) : [];
            if ( is_array( $parsed_content ) && ! empty( $parsed_content ) ) {
                $eb_blocks          = [];
                $recursive_response = EbStyleHandlerParseCss::eb_block_style_recursive( $parsed_content, $eb_blocks );
                unset( $recursive_response["reusableBlocks"] );
                $style = EbStyleHandlerParseCss::blocks_to_style_array( $recursive_response );
                //Write CSS file for Widget
                $upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
                $this->single_file_css_generator( $style, $upload_dir, 'eb-style-widget.min.css' );
            }
        }

        /**
         * Load Dependencies
         */
        private function load_style_handler_dependencies() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/class-parse-css.php';
        }

        /**
         * Enqueue frontend css for post if have one
         * @return void
         * @since 1.0.2
         */
        public function enqueue_frontend_css() {
            global $post;
            $upload_dir = wp_upload_dir();

            // generatepress elements
            if ( in_array( 'gp-premium/gp-premium.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                $gp_elements = get_posts( ['post_type' => 'gp_elements'] );
                if ( is_array( $gp_elements ) && ! empty( $gp_elements ) ) {
                    foreach ( $gp_elements as $element ) {
                        if ( file_exists( $upload_dir['basedir'] . '/eb-style/eb-style-' . $element->ID . '.min.css' ) ) {
                            wp_enqueue_style( 'eb-block-style-' . $element->ID, $upload_dir['baseurl'] . '/eb-style/eb-style-' . $element->ID . '.min.css', [], substr( md5( microtime( true ) ), 0, 10 ) );
                        }
                    }
                }
            }

            if ( ! empty( $post ) && ! empty( $post->ID ) ) {
                //Page/Post Style Enqueue
                if ( file_exists( $upload_dir['basedir'] . '/eb-style/eb-style-' . $post->ID . '.min.css' ) ) {
                    wp_enqueue_style( 'eb-block-style-' . $post->ID, $upload_dir['baseurl'] . '/eb-style/eb-style-' . $post->ID . '.min.css', [], substr( md5( microtime( true ) ), 0, 10 ) );
                }

                // Reusable block Style Enqueues
                $reusableIds         = get_post_meta( $post->ID, '_eb_reusable_block_ids', true );
                $reusableIds         = ! empty( $reusableIds ) ? $reusableIds : [];
                $templateReusableIds = get_option( '_eb_reusable_block_ids', [] );
                $reusableIds         = array_unique( array_merge( $reusableIds, $templateReusableIds ) );
                if ( ! empty( $reusableIds ) && is_array( $reusableIds ) ) {
                    foreach ( $reusableIds as $reusableId ) {
                        if ( file_exists( $upload_dir['basedir'] . '/eb-style/reusable-blocks/eb-reusable-' . $reusableId . '.min.css' ) ) {
                            wp_enqueue_style( 'eb-reusable-block-style-' . $reusableId, $upload_dir['baseurl'] . '/eb-style/reusable-blocks/eb-reusable-' . $reusableId . '.min.css', [], substr( md5( microtime( true ) ), 0, 10 ) );
                        }
                    }
                }
            }

            //Widget Style Enqueue
            if ( file_exists( $upload_dir['basedir'] . '/eb-style/eb-style-widget.min.css' ) ) {
                wp_enqueue_style( 'eb-widget-style', $upload_dir['baseurl'] . '/eb-style/eb-style-widget.min.css', [], substr( md5( microtime( true ) ), 0, 10 ) );
            }

            //FSE Style Enqueue
            if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() && file_exists( $upload_dir['basedir'] . '/eb-style/eb-style-edit-site.min.css' ) ) {
                wp_enqueue_style( 'eb-fullsite-style', $upload_dir['baseurl'] . '/eb-style/eb-style-edit-site.min.css', [], substr( md5( microtime( true ) ), 0, 10 ) );
            }
        }

        /**
         * Get post content when page is saved
         */
        public function stylehandler_get_post_content( $post_id, $post, $update ) {
            $post_type = get_post_type( $post_id );

            //If This page is draft, return
            if ( isset( $post->post_status ) && 'auto-draft' == $post->post_status ) {
                return;
            }

            // Autosave, do nothing
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            // Return if it's a post revision
            if ( false !== wp_is_post_revision( $post_id ) ) {
                return;
            }

            if ( $post_type === 'wp_template_part' || $post_type === 'wp_template' ) {
                $post           = get_post( $post_id );
                $parsed_content = parse_blocks( $post->post_content );
            } else {
                $parsed_content = parse_blocks( $post->post_content );
            }

            if ( empty( $parsed_content ) ) {
                delete_post_meta( $post_id, '_eb_reusable_block_ids' );
            }

            if ( is_array( $parsed_content ) && ! empty( $parsed_content ) ) {
                $this->eb_write_css_from_content( $post, $post_id, $parsed_content );
            }
        }

        /**
         * Get post content when page is load in frontend
         */
        public function stylehandler_generate_post_content() {
            $post_id = get_the_ID();
            if ( $post_id ) {
                $upload_dir = wp_upload_dir();
                $base_path  = $upload_dir['basedir'];
                $style_file = $base_path . '/eb-style/eb-style-' . $post_id . '.min.css';
                if ( ! file_exists( $style_file ) ) {
                    $post_type = get_post_type( $post_id );
                    $post      = get_post( $post_id );
                    //If This page is draft, return
                    if ( isset( $post->post_status ) && 'auto-draft' == $post->post_status ) {
                        return;
                    }
                    // Return if it's a post revision
                    if ( false !== wp_is_post_revision( $post_id ) ) {
                        return;
                    }

                    if ( $post_type === 'wp_template_part' || $post_type === 'wp_template' ) {
                        // Todo: have to do something for FSE
                        $post           = get_post( $post_id );
                        $parsed_content = parse_blocks( $post->post_content );
                    } else {
                        $parsed_content = parse_blocks( $post->post_content );
                    }

                    if ( empty( $parsed_content ) ) {
                        delete_post_meta( $post_id, '_eb_reusable_block_ids' );
                    }

                    if ( is_array( $parsed_content ) && ! empty( $parsed_content ) ) {
                        $eb_blocks          = [];
                        $recursive_response = EbStyleHandlerParseCss::eb_block_style_recursive( $parsed_content, $eb_blocks );
                        $reusable_Blocks    = ! empty( $recursive_response['reusableBlocks'] ) ? $recursive_response['reusableBlocks'] : [];

                        // remove empty reusable blocks
                        $reusable_Blocks = array_filter( $reusable_Blocks, function ( $v ) {
                            return ! empty( $v );
                        } );
                        unset( $recursive_response["reusableBlocks"] );

                        $style       = EbStyleHandlerParseCss::blocks_to_style_array( $recursive_response );
                        $reusableIds = $reusable_Blocks && is_array( $reusable_Blocks ) ? array_keys( $reusable_Blocks ) : [];
                        if ( ! empty( $reusableIds ) ) {
                            update_option( '_eb_reusable_block_ids', $reusableIds );
                        }
                        update_post_meta( $post_id, '_eb_reusable_block_ids', $reusableIds );
                        $this->write_block_css( $style, $post ); //Write CSS file for this page

                        if ( ! empty( $reusable_Blocks ) ) {
                            foreach ( $reusable_Blocks as $blockId => $block ) {
                                $style = EbStyleHandlerParseCss::blocks_to_style_array( $block );
                                $this->write_reusable_block_css( $style, $blockId );
                            }
                        }
                    }
                }
            }
        }

        /**
         * Ajax callback to write css in upload directory
         * @retun void
         * @since 1.0.2
         */
        private function write_block_css( $block_styles, $post ) {
            //Write CSS for FSE
            if ( isset( $post->post_type ) && ( $post->post_type === "wp_template_part" || $post->post_type === "wp_template" && ! empty( $block_styles ) ) ) {
                $upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
                $this->single_file_css_generator( $block_styles, $upload_dir, 'eb-style-edit-site.min.css' );
            }
            // Write CSS for Page/Posts
            else {
                if ( ! empty( $css = EbStyleHandlerParseCss::build_css( $block_styles ) ) ) {
                    $upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
                    if ( ! file_exists( $upload_dir ) ) {
                        mkdir( $upload_dir );
                    }
                    file_put_contents( $upload_dir . 'eb-style-' . abs( $post->ID ) . '.min.css', $css );
                }
            }
        }

        /**
         * Write css for Reusable block
         * @retun void
         * @since 3.4.0
         */
        private function write_reusable_block_css( $block_styles, $id ) {
            if ( isset( $block_styles ) && is_array( $block_styles ) ) {
                if ( ! empty( $css = EbStyleHandlerParseCss::build_css( $block_styles ) ) ) {
                    $upload_dir = wp_upload_dir()['basedir'] . '/eb-style/reusable-blocks/';
                    if ( ! file_exists( $upload_dir ) ) {
                        mkdir( $upload_dir );
                    }
                    file_put_contents( $upload_dir . 'eb-reusable-' . abs( $id ) . '.min.css', $css );
                }
            }
        }

        /**
         * Single file css generator
         * @retun void
         * @since 3.5.3
         */
        private function single_file_css_generator( $block_styles, $upload_dir, $filename ) {
            $editSiteCssPath = $upload_dir . $filename;
            if ( file_exists( $editSiteCssPath ) ) {
                $existingCss = file_get_contents( $editSiteCssPath );
                $pattern     = "~\/\*(.*?)\*\/~";
                preg_match_all( $pattern, $existingCss, $result, PREG_PATTERN_ORDER );
                $allComments  = $result[0];
                $seperatedIds = [];
                foreach ( $allComments as $comment ) {
                    $id = preg_replace( '/[^A-Za-z0-9\-]|Ends|Starts/', '', $comment );

                    if ( strpos( $comment, "Starts" ) ) {
                        $seperatedIds[$id]['start'] = $comment;
                    } else if ( strpos( $comment, "Ends" ) ) {
                        $seperatedIds[$id]['end'] = $comment;
                    }
                }

                $seperateStyles = [];
                foreach ( $seperatedIds as $key => $ids ) {
                    $seperateStyles[][$key] = isset( $block_styles[$key] ) ? $block_styles[$key] : [];
                }

                self::$_block_styles = array_merge( self::$_block_styles, $block_styles );

                if ( ! empty( $css = EbStyleHandlerParseCss::build_css( self::$_block_styles ) ) ) {
                    if ( ! file_exists( $upload_dir ) ) {
                        mkdir( $upload_dir );
                    }

                    file_put_contents( $editSiteCssPath, $css );
                }
            } else {
                if ( ! empty( $css = EbStyleHandlerParseCss::build_css( $block_styles ) ) ) {
                    $upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
                    if ( ! file_exists( $upload_dir ) ) {
                        mkdir( $upload_dir );
                    }

                    file_put_contents( $editSiteCssPath, $css );
                }
            }
        }

        /**
         * Get post id by post_name for template
         */
        public static function eb_get_post_content_by_post_name( $post_name ) {
            global $wpdb;
            $sql = $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE post_name = %s", $post_name );
            return $wpdb->get_results( $sql, ARRAY_A );
        }
    }

    EbStyleHandler::init();
}
