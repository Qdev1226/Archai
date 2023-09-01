<?php

namespace GutSliders\Admin;

// class to add admin submenu page

class Admin {
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
            wp_enqueue_style( 'gutslider-admin-style', GUTSLIDERS_URL . 'includes/Admin/admin.css', [], GUTSLIDERS_VERSION );
            // JS
            wp_enqueue_script( 'gutslider-admin-script', GUTSLIDERS_URL . 'includes/Admin/admin.js', [ 'jquery' ], GUTSLIDERS_VERSION, true );
        }
    }

    /**
     * Add admin menu
     */
    public function gutslider_admin_menu() {
        add_submenu_page(
            'options-general.php',
            __( 'Gutslider Block', 'gutsliders' ),
            __( 'Gutslider Block', 'gutsliders' ),
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
                            <?php _e( 'Gutslider Block', 'gutsliders' ); ?>
                        </h1>
                        <p class="plugin_description">
                            <?php _e( 'Gutslider Block is a Gutenberg block plugin that allows you to create amazing slider in Gutenberg Editor without any coding knowledge', 'gutsliders' ); ?>
                        </p>
                    </div>
                </div>
                <div class="plugin__body_container">
                    <div class="plugin_body">
                        <div class="tabs__panel_container">
                            <div class="tabs__titles">
                                <p class="tab__title active" data-tab="tab1">
                                    <?php _e( 'Help and Support', 'gutsliders' ); ?>
                                </p>
                                <p class="tab__title" data-tab="tab2">
                                    <?php _e( 'Changelog', 'gutsliders' ); ?>
                                </p>
                            </div>
                            <div class="tabs__container">
                                <div class="tabs__panels">
                                    <div class="tab__panel active" id="tab1">
                                        <div class="tab__panel_flex">
                                            <div class="tab__panel_left">
                                                <h3 class="video__title">
                                                    <?php _e( 'Video Tutorial', 'gutsliders' ); ?>
                                                </h3>
                                                <p class="video__description">
                                                    <?php _e( 'Watch the video tutorial to learn how to use the plugin. It will help you start your own design quickly.', 'gutsliders' ); ?>
                                                </p>
                                                <div class="video__container">
                                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/B3EuKpf3NXM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                            <div class="tab__panel_right">
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e( 'Get Support', 'gutsliders' ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e( 'If you find any issue or have any suggestion, please let me know.', 'gutsliders' ); ?>
                                                    </p>
                                                    <a href="https://wordpress.org/support/plugin/gutsliders/" class="support__link" target="_blank">
                                                        <?php _e( 'Support', 'gutsliders' ); ?>
                                                    </a>
                                                </div>
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e( 'Spread Your Love', 'gutsliders' ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e( 'If you like this plugin, please share your opinion', 'gutsliders' ); ?>
                                                    </p>
                                                    <a href="https://wordpress.org/support/plugin/gutsliders/reviews/" class="support__link" target="_blank">
                                                        <?php _e( 'Rate the Plugin', 'gutsliders' ); ?>
                                                    </a>
                                                </div>
                                                <div class="single__support_panel">
                                                    <h3 class="support__title">
                                                        <?php _e( 'Similar Blocks', 'gutsliders' ); ?>
                                                    </h3>
                                                    <p class="support__description">
                                                        <?php _e( 'Want to get more similar blocks, please visit my website', 'gutsliders' ); ?>
                                                    </p>
                                                    <a href="https://makegutenblock.com" class="support__link" target="_blank">
                                                        <?php _e( 'Visit my Website', 'gutsliders' ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="custom__block_request">
                                            <h3 class="custom__block_request_title">
                                                <?php _e( 'Need to Hire Me?', 'gutsliders' ); ?>
                                            </h3>
                                            <p class="custom__block_request_description">
                                                <?php _e( 'I am available for any freelance projects. Please feel free to share your project detail with me.', 'gutsliders' ); ?>
                                            </p>
                                            <div class="available__links">
                                                <a href="mailto:zbinsaifullah@gmail.com" class="available__link mail" target="_blank">
                                                    <?php _e( 'Send Email', 'gutsliders' ); ?>
                                                </a>
                                                <a href="https://makegutenblock.com/contact" class="available__link web" target="_blank">
                                                    <?php _e( 'Send Message', 'gutsliders' ); ?>
                                                </a>
                                                <a href="https://www.fiverr.com/devs_zak" class="available__link fiverr" target="_blank">
                                                    <?php _e( 'Fiverr', 'gutsliders' ); ?>
                                                </a>
                                                <a href="https://www.upwork.com/freelancers/~010af183b3205dc627" class="available__link upwork" target="_blank">
                                                    <?php _e( 'UpWork', 'gutsliders' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab__panel" id="tab2">
                                        <div class="change__log_head">
                                            <h3 class="change__log_title">
                                                <?php _e( 'Changelog', 'gutsliders' ); ?>
                                            </h3>
                                            <p class="change__log_description">
                                                <?php _e( 'This is the changelog of the plugin. You can see the changes in each version.', 'gutsliders' ); ?>
                                            </p>
                                            <div class="change__notes">
                                                <div class="single__note">
                                                    <span class="info change__note"><?php _e( 'i', 'gutsliders' ); ?></span>
                                                    <span class="note__description"><?php _e( 'Info', 'gutsliders' ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="feature change__note"><?php _e( 'N', 'gutsliders' ); ?></span>
                                                    <span class="note__description"><?php _e( 'New Feature', 'gutsliders' ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="update change__note"><?php _e( 'U', 'gutsliders' ); ?></span>
                                                    <span class="note__description"><?php _e( 'Update', 'gutsliders' ); ?></span>
                                                </div>
                                                <div class="single__note">
                                                    <span class="fixing change__note"><?php _e( 'F', 'gutsliders' ); ?></span>
                                                    <span class="note__description"><?php _e( 'Issue Fixing', 'gutsliders' ); ?></span>
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
                                                    <span class="description__text"><?php _e( 'Initial Release', 'gutsliders' ); ?></span>
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