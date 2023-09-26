<?php

// class to add admin submenu page

class GutSlider_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'gutslider_admin_menu' ] );
        // enqueue admin assets
        add_action( 'admin_enqueue_scripts', [ $this, 'gutslider_admin_assets' ] );
    }

    /**
     * Enqueue admin scripts
     */
    public function gutslider_admin_assets($screen) {
        if( $screen === 'settings_page_gutslider-block' ){
            wp_enqueue_style( 'gutslider-admin-style', GUTSLIDER_URL . 'includes/Admin/admin.css', [], GUTSLIDER_VERSION );
            // JS
            wp_enqueue_script( 'gutslider-admin-script', GUTSLIDER_URL . 'includes/Admin/admin.js', [ 'jquery' ], GUTSLIDER_VERSION, true );
        }
    }

    /**
     * Add admin menu
     */
    public function gutslider_admin_menu() {
        add_submenu_page(
            'options-general.php',
            __( 'Gutslider Block', 'slider-blocks' ),
            __( 'Gutslider Block', 'slider-blocks' ),
            'manage_options',
            'gutslider-block',
            [ $this, 'gutslider_admin_page' ]
        );
    }

    /**
     * Admin page
     */
    public function gutslider_admin_page() {
        ?>
        <div class="gutslider__wrap">
            <div class="plugin_max_container">
                <div class="plugin__head_container">
                    <div class="plugin_head">
                        <h1 class="plugin_title">
                            <?php _e( 'Gutslider Block', 'slider-blocks' ); ?>
                        </h1>
                        <p class="plugin_description">
                            <?php _e( 'Gutslider Block is a Gutenberg block plugin that allows you to create amazing slider in Gutenberg Editor without any coding knowledge', 'slider-blocks' ); ?>
                        </p>
                    </div>
                </div>
                <div class="plugin__body_container">
                    <div class="plugin_body">
                        <div class="tabs__panel_container">
                            <div class="tabs__titles">
                                <p class="tab__title active" data-tab="tab1">
                                    <?php _e( 'Help and Support', 'slider-blocks' ); ?>
                                </p>
                                <p class="tab__title" data-tab="tab2">
                                    <?php _e( 'Changelog', 'slider-blocks' ); ?>
                                </p>
                            </div>
                            <div class="tabs__container">
                                <div class="tabs__panels">
                                    <div class="tab__panel active" id="tab1">
                                        <div class="tab__panel_flex">
                                            <div class="tab__panel_left">
                                                <h3 class="video__title">
                                                    <?php _e( 'Video Tutorial', 'slider-blocks' ); ?>
                                                </h3>
                                                <p class="video__description">
                                                    <?php _e( 'Watch the video tutorial to learn how to use the plugin. It will help you start your own design quickly.', 'slider-blocks' ); ?>
                                                </p>
                                                <div class="video__container">
                                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/B3EuKpf3NXM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                            <div class="tab__panel_right">
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e( 'Get Support', 'slider-blocks' ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e( 'If you find any issue or have any suggestion, please let me know.', 'slider-blocks' ); ?>
                                                    </p>
                                                    <a href="https://wordpress.org/support/plugin/slider-blocks/" class="support__link" target="_blank">
                                                        <?php _e( 'Support', 'slider-blocks' ); ?>
                                                    </a>
                                                </div>
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e( 'Spread Your Love', 'slider-blocks' ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e( 'If you like this plugin, please share your opinion', 'slider-blocks' ); ?>
                                                    </p>
                                                    <a href="https://wordpress.org/support/plugin/slider-blocks/reviews/" class="support__link" target="_blank">
                                                        <?php _e( 'Rate the Plugin', 'slider-blocks' ); ?>
                                                    </a>
                                                </div>
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e( 'Similar Blocks', 'slider-blocks' ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e( 'Want to get more similar blocks, please visit my website', 'slider-blocks' ); ?>
                                                    </p>
                                                    <a href="https://makegutenblock.com" class="support__link" target="_blank">
                                                        <?php _e( 'Visit my Website', 'slider-blocks' ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom__block_request">
                                            <h3 class="custom__block_request_title">
                                                <?php _e( 'Need to Hire Me?', 'slider-blocks' ); ?>
                                            </h3>
                                            <p class="custom__block_request_description">
                                                <?php _e( 'I am available for any freelance projects. Please feel free to share your project detail with me.', 'slider-blocks' ); ?>
                                            </p>
                                            <div class="available__links">
                                                <a href="mailto:zbinsaifullah@gmail.com" class="available__link mail" target="_blank">
                                                    <?php _e( 'Send Email', 'slider-blocks' ); ?>
                                                </a>
                                                <a href="https://makegutenblock.com/contact" class="available__link web" target="_blank">
                                                    <?php _e( 'Send Message', 'slider-blocks' ); ?>
                                                </a>
                                                <a href="https://www.fiverr.com/devs_zak" class="available__link fiverr" target="_blank">
                                                    <?php _e( 'Fiverr', 'slider-blocks' ); ?>
                                                </a>
                                                <a href="https://www.upwork.com/freelancers/~010af183b3205dc627" class="available__link upwork" target="_blank">
                                                    <?php _e( 'UpWork', 'slider-blocks' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab__panel" id="tab2">
                                        <div class="change__log_head">
                                            <h3 class="change__log_title">
                                                <?php _e( 'Changelog', 'slider-blocks' ); ?>
                                            </h3>
                                            <p class="change__log_description">
                                                <?php _e( 'This is the changelog of the plugin. You can see the changes in each version.', 'slider-blocks' ); ?>
                                            </p>
                                            <div class="change__notes">
                                                <div class="single__note">
                                                    <span class="info change__note"><?php _e( 'i', 'slider-blocks' ); ?></span>
                                                    <span class="note__description"><?php _e( 'Info', 'slider-blocks' ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="feature change__note"><?php _e( 'N', 'slider-blocks' ); ?></span>
                                                    <span class="note__description"><?php _e( 'New Feature', 'slider-blocks' ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="update change__note"><?php _e( 'U', 'slider-blocks' ); ?></span>
                                                    <span class="note__description"><?php _e( 'Update', 'slider-blocks' ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="fixing change__note"><?php _e( 'F', 'slider-blocks' ); ?></span>
                                                    <span class="note__description"><?php _e( 'Issue Fixing', 'slider-blocks' ); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="change__log_body">
                                            <div class="single__log">
                                                <div class="plugin__info">
                                                <span class="log__version">1.0.0</span>
                                                    <span class="log__date">2023-08-11</span>
                                                </div>
                                                <div class="log__description">
                                                    <span class="change__note info">i</span>
                                                    <span class="description__text"><?php _e( 'Initial Release', 'slider-blocks' ); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

new GutSlider_Admin(); // initialize the class