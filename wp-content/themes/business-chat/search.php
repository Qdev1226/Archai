<?php

use SuperbThemesCustomizer\CustomizerControls;
use SuperbThemesCustomizer\CustomizerController;

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package business-chat
 */

get_header(); ?>



<div id="content" class="site-content clearfix"> <?php $business_chat_container_class = !is_page_template('elementor_header_footer') ? 'content-wrap' : 'content-none'; ?>
    <div class="<?php echo esc_html($business_chat_container_class); ?>">
        <div id="primary" class="featured-content content-area <?php if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::BLOGFEED_HIDE_SIDEBAR) == '1' || !is_active_sidebar('sidebar-1')) : ?>fullwidth-area-blog<?php endif; ?> add-blog-to-sidebar">
            <main id="main" class="site-main">
                <?php if (have_posts()) : ?>
                    <header class="page-header search-results-header-wrapper">
                        <h1 class="page-title">
                            <?php
                            /* translators: %s: search query. */
                            printf(esc_html__('Search Results for: %s', 'business-chat'), '<span>' . get_search_query() . '</span>');
                            ?>
                        </h1>
                    </header><!-- .page-header -->
                    <div class="all-blog-articles">
                        <?php CustomizerController::MaybeGetMasonryColumnOutput(); ?>
                    <?php
                    /* Start the Loop */
                    while (have_posts()) : the_post();

                        /**
                         * Run the loop for the search to output the results.
                         * If you want to overload this in a child theme then include a file
                         * called content-search.php and that will be used instead.
                         */
                        get_template_part('template-parts/content', get_post_format());

                    endwhile;

                    echo '<div class="text-center pag-wrapper">';
                    business_chat_numeric_posts_nav();
                    echo '</div>';

                else :

                    get_template_part('template-parts/content', 'none');

                endif; ?>
                    </div>
            </main><!-- #main -->
        </div><!-- #primary -->

        <?php if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::BLOGFEED_HIDE_SIDEBAR) == '1') : ?>
        <?php else : ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>


    </div>
</div>
<?php
get_footer();
