<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package business-chat
 */

?>


<footer id="colophon" class="site-footer clearfix">


	<?php if (is_active_sidebar('footerwidget-1')) : ?>
		<div class="content-wrap">
			<div class="site-footer-widget-area">
				<?php dynamic_sidebar('footerwidget-1'); ?>
			</div>
		</div>

	<?php endif; ?>


	<div class="site-info">
		&copy;<?php echo date('Y'); ?> <?php bloginfo('name'); ?>
		<span class="footer-info-right">
			<?php echo __(' | WordPress Theme by', 'business-chat') ?> <a href="<?php echo esc_url('https://superbthemes.com/', 'business-chat'); ?>" rel="nofollow noopener"><?php echo __(' SuperbThemes', 'business-chat') ?></a>
		</span>
	</div><!-- .site-info -->

</footer><!-- #colophon -->


<div id="smobile-menu" class="mobile-only"></div>
<div id="mobile-menu-overlay"></div>

<?php wp_footer(); ?>
</body>

</html>