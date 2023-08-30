<?php

namespace SuperbThemesCustomizer\Modules\Posts;

class LatestPosts
{
    private $PostsQuery;
    public function __construct($args)
    {
        $this->PostsQuery = new \WP_Query($args);
        if ($this->PostsQuery->have_posts()) {
            $this->Render();
            \wp_reset_postdata();
        }
    }

    public function Render()
    {
        echo '<div class="blog spbcustomizer-latest-posts-sc">';
        echo '<div class="add-blog-to-sidebar">';
        echo '<div class="all-blog-articles">';
        while ($this->PostsQuery->have_posts()) {
            $this->PostsQuery->the_post();
            get_template_part('template-parts/content', '', array('is_posts_shortcode' => true));
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
