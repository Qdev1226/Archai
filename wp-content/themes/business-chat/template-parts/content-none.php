<?php

/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package business-chat
 */

?>

<section class="fbox no-results not-found">


	<div class="page-content">
		<?php
		if (is_home() && current_user_can('publish_posts')) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e('Nothing Found', 'business-chat'); ?></h1>
			</header>
			<p><?php
				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'business-chat'),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url(admin_url('post-new.php'))
				);
				?></p>

		<?php elseif (is_search()) : ?>
			<div class="search-form-wrapper">
				<h1 class="page-title"><?php esc_html_e('Nothing Found', 'business-chat'); ?></h1>
				<p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'business-chat'); ?></p>
			<?php
			get_search_form();
			echo "</div>";
		else : ?>
				<div class="search-form-wrapper">
					<h1 class="page-title"><?php esc_html_e('Nothing Found', 'business-chat'); ?></h1>
					<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'business-chat'); ?></p>
				<?php
				get_search_form();
				echo "</div>";
			endif; ?>
				</div><!-- .page-content -->
</section><!-- .no-results -->