<?php

use SuperbThemesCustomizer\CustomizerControls;
use SuperbThemesCustomizer\CustomizerController;

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package business-chat
 */

get_header(); ?>


<div id="content" class="site-content clearfix"> <?php $business_chat_container_class = !is_page_template('elementor_header_footer') ? 'content-wrap' : 'content-none'; ?>
    <div class="<?php echo esc_html($business_chat_container_class); ?>">
        <div id="primary" class="featured-content content-area <?php if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::BLOGFEED_HIDE_SIDEBAR) == '1' || !is_active_sidebar('sidebar-1')) : ?>fullwidth-area-blog<?php endif; ?> add-blog-to-sidebar">
            <main id="main" class="site-main all-blog-articles">
                <?php CustomizerController::MaybeGetMasonryColumnOutput(); ?>
                <?php
                if (have_posts()) :
                    if (is_home() && !is_front_page()) :
                    //
                    endif;
                    //
                    /* Start the Loop */
                    while (have_posts()) : the_post();

                        /*
                     * Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                        get_template_part('template-parts/content', get_post_format());

                    endwhile;

                    echo '<div class="text-center pag-wrapper">';
                    business_chat_numeric_posts_nav();
                    echo '</div>';
                else :

                    get_template_part('template-parts/content', 'none');

                endif; ?>

            </main><!-- #main -->
        </div><!-- #primary -->

        <?php if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::BLOGFEED_HIDE_SIDEBAR) == '1') : ?>
        <?php else : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>



    </div>
</div>

<?php get_footer(); ?>