<?php
namespace WPMapBlock;

class Assets
{
    public static function init()
    {
        $self = new self();
        add_action('init', [$self, 'register_block_assets']);
    }

    public function register_block_assets()
    {
		// Register block script for frontend.
        $frontend_dependencies = include_once WPMAPBLOCK_ASSETS_DIR_PATH . 'dist/wpmapblock-frontend.core.min.asset.php';
        wp_register_style(
            'wp-map-block-stylesheets',
            WPMAPBLOCK_ASSETS_URI . 'dist/wpmapblock-frontend.core.min.css',
            is_admin() ? array('wp-editor') : null,
            $frontend_dependencies['version']
        );
        wp_register_script(
            'wp-map-block-frontend-js', // Handle.
            WPMAPBLOCK_ASSETS_URI . 'dist/wpmapblock-frontend.core.min.js',
            $frontend_dependencies['dependencies'],
            $frontend_dependencies['version'],
            true
        );

		// Register block editor styles for backend.
        $backend_dependencies = include_once WPMAPBLOCK_ASSETS_DIR_PATH . 'dist/wpmapblock.core.min.asset.php';
        wp_register_style(
            'wp-map-block-editor-css',
            WPMAPBLOCK_ASSETS_URI . 'dist/wpmapblock.core.min.css',
            array('wp-edit-blocks'),
            $backend_dependencies['version']
        );

        // Register block editor script for backend.
        wp_register_script(
            'wp-map-block-js', // Handle.
            WPMAPBLOCK_ASSETS_URI . 'dist/wpmapblock.core.min.js',
            $backend_dependencies['dependencies'],
            $backend_dependencies['version'],
            true
        );

        // WP Localized globals. Use dynamic PHP stuff in JavaScript via `wpmapblockGlobal` object.
        wp_localize_script(
            'wp-map-block-js',
            'wpmapblockGlobal', // Array containing dynamic data for a JS Global.
            [
                'pluginDirPath' => plugin_dir_path(__DIR__),
                'pluginDirUrl'  => plugin_dir_url(__DIR__),
                // Add more data here that you want to access from `wpmapblockGlobal` object.
            ]
        );
        wp_set_script_translations( 'wp-map-block-js', 'wp-map-block', WPMAPBLOCK_ROOT_DIR_PATH . 'languages/' );
    }
}
