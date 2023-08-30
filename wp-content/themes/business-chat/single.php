<?php

use SuperbThemesCustomizer\CustomizerControls;

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package business-chat
 */
$business_chat_featured_image_style = CustomizerControls::GetSelectedOrDefault(CustomizerControls::SINGLE_FEATURED_IMAGE_STYLE);
$business_chat_thumbnail_url = \get_the_post_thumbnail_url(get_the_id(), 'business-chat-noresize');
get_header(); ?>

<div id="content" class="site-content clearfix"> <?php $business_chat_container_class = !is_page_template('elementor_header_footer') ? 'content-wrap' : 'content-none'; ?>
    <div class="<?php echo esc_html($business_chat_container_class); ?>">
        <div id="primary" class="featured-content content-area <?php if (!is_active_sidebar('sidebar-1')) : ?>fullwidth-area-blog<?php endif; ?>">
            <main id="main" class="site-main">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="featured-thumbnail">
                        <div class="featured-thumbnail-cropped" <?php if ($business_chat_featured_image_style === CustomizerControls::SINGLE_FEATURED_IMAGE_CHOICE_COVER_IMAGE) {
                                                                    echo 'style="background-image: url(' . esc_url($business_chat_thumbnail_url) . ')"';
                                                                } ?>>
                            <?php
                            if ($business_chat_featured_image_style === CustomizerControls::SINGLE_FEATURED_IMAGE_CHOICE_FULL_IMAGE_COVER_BLUR) {
                            ?>
                                <span class="featured-img-bg-blur" <?php echo 'style="background-image: url(' . esc_url($business_chat_thumbnail_url) . ')"'; ?>></span>
                            <?php
                            }
                            if ($business_chat_featured_image_style !== CustomizerControls::SINGLE_FEATURED_IMAGE_CHOICE_COVER_IMAGE) {
                                the_post_thumbnail('business-chat-noresize');
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php
                while (have_posts()) : the_post();

                    get_template_part('template-parts/content', 'single');

                    // Next and previs oust
                    the_post_navigation(array(
                        'prev_text'                  => __('Previous post', 'business-chat'),
                        'next_text'                  => __('Next post', 'business-chat'),
                    ));

                    // Related posts start
                    if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::SINGLE_HIDE_RELATED_POSTS) != '1') :
                        $categories = get_the_category($post->ID);
                        if ($categories) {
                            $category_ids = array();
                            foreach ($categories as $individual_category) {
                                $category_ids[] = $individual_category->term_id;
                            }
                            $args = array(
                                'category__in' => $category_ids,
                                'post__not_in' => array($post->ID),
                                'ignore_sticky_posts' => 1,
                                'posts_per_page' => 3,
                                'orderby' => 'rand'
                            );
                            $my_query = new wp_query($args);
                            if ($my_query->have_posts()) {
                                echo '<div class="related-posts"><div class="related-posts-headline"><h3>' . esc_html__('Related Posts', 'business-chat') . '</h3></div><div class="related-posts-posts">';
                                $pexcerpt = 1;
                                $j = 0;
                                $counter = 0;
                                while ($my_query->have_posts()) {
                                    $my_query->the_post();
                                    get_template_part('template-parts/content', get_post_format(), array('is_related_posts' => true));
                                    $pexcerpt++; ?>
                <?php }
                                echo '</div></div>';
                            }
                        }
                        wp_reset_postdata();

                    endif;
                    // Related posts end

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->

        <?php get_sidebar(); ?>

    </div>
</div><!-- #content -->

<?php get_footer();
