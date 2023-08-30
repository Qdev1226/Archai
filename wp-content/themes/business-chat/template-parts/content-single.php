<?php

/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package business-chat
 */

use SuperbThemesCustomizer\CustomizerControls;

$business_chat_is_related_posts = isset($args['is_related_posts']) && !!$args['is_related_posts'];
$business_chat_hide_author_name = !!$business_chat_is_related_posts;
$business_chat_hide_author_image = CustomizerControls::GetSelectedOrDefault(CustomizerControls::SINGLE_HIDE_BYLINE_IMAGE) == "1" || !!$business_chat_is_related_posts;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('posts-entry fbox'); ?>>
	<header class="entry-header">
		<?php
		if (is_singular()) :
			the_title('<h1 class="entry-title">', '</h1>');
		else :
			the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
		endif;
		?>
		<?php
		if ('post' === get_post_type()) : ?>
			<div class="entry-meta">
				<div class="blog-data-wrapper">
					<div class='post-meta-inner-wrapper'>
						<?php if (!$business_chat_hide_author_image) : ?>
							<span class="post-author-img">
								<?php echo get_avatar(get_the_author_meta('ID'), 24); ?>
							</span>
						<?php endif; ?>
						<?php if (!$business_chat_hide_author_name) : ?>
							<span class="post-author-data">
								<?php the_author(); ?><?php esc_html_e(', ', 'business-chat'); ?>
							<?php endif; ?>
							<?php business_chat_posted_on(); ?>
							<?php if (!$business_chat_hide_author_name) : ?>
							</span>
						<?php endif; ?>
					</div>
				</div>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(array(
			'before' => '<div class="page-links">' . esc_html__('Pages:', 'business-chat'),
			'after'  => '</div>',
		));

		if (is_single()) : ?>
			<div class="category-and-tags">
				<?php the_category(' '); ?>
				<?php if (has_tag()) : ?>
					<?php the_tags('', ''); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>


	</div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->