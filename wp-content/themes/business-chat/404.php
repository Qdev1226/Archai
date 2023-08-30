<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package business-chat
 */

get_header(); ?>

<div id="content" class="site-content clearfix">
	<?php $business_chat_container_class = !is_page_template('elementor_header_footer') ? 'content-wrap' : 'content-none'; ?>
	<div class="<?php echo esc_html($business_chat_container_class); ?>">
		<div id="primary" class="featured-content content-area full-width-template add-blog-to-sidebar">
			<main id="main" class="fbox site-main">
				<section class="error-404 not-found bg-image-404">
					<header class="page-header">
						<h1 class="page-title error-404-headline"><?php esc_html_e('Ooops!', 'business-chat'); ?></h1>
						<p class="error-404-description"><?php esc_html_e('It seems like this page is no longer here', 'business-chat'); ?></p>
					</header><!-- .page-header -->

					<div class="page-content">
						<div class="fourofour-home">
							<a href="<?php echo  esc_url(home_url()); ?>"><?php esc_html_e('Go to homepage', 'business-chat'); ?></a>
						</div>
					</div><!-- .page-content -->

				</section><!-- .error-404 -->

			</main><!-- #main -->
		</div><!-- #primary -->
	</div>
</div><!-- #content -->

<?php
get_footer();
