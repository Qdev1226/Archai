<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package business-chat
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('posts-entry fbox blogposts-list'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="featured-img-box">
			<a href="<?php the_permalink() ?>" class="featured-thumbnail" rel="bookmark">
				<?php esc_html(the_post_thumbnail('business-chat-noresize')); ?>
			</a>
		<?php else : ?>
			<div class="no-featured-img-box">
			<?php endif; ?>
			<div class="content-wrapper">
				<header class="entry-header">
					<?php
					if (is_singular()) :
						the_title('<h1 class="entry-title">', '</h1>');
					else :
						the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
					endif;

					if ('post' === get_post_type()) : ?>
						<div class="entry-meta">
							<div class="blog-data-wrapper">
								<span class="post-author-wrapper"><?php esc_html_e('By', 'business-chat'); ?> <?php the_author(); ?> <?php esc_html_e('on', 'business-chat'); ?></span>
								<?php business_chat_posted_on(); ?>
							</div>
						</div><!-- .entry-meta -->
					<?php
					endif; ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php the_excerpt(); ?>
					<?php
					wp_link_pages(array(
						'before' => '<div class="page-links">' . esc_html__('Pages:', 'business-chat'),
						'after'  => '</div>',
					));
					?>

				</div>

				<a class="read-story" href="<?php the_permalink() ?>">
					<?php esc_html_e('Continue reading', 'business-chat'); ?>
				</a>

			</div>
			</div>

</article><!-- #post-<?php the_ID(); ?> -->