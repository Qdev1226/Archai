<?php if ( foundation_is_theme_using_module( 'custom-latest-posts' ) && wptouch_fdn_is_custom_latest_posts_page() ) { ?>

	<?php get_template_part( 'index' ); ?>

<?php } else { ?>

	<?php get_header(); ?>

	<div id="content">
		<?php if ( wptouch_have_posts() ) { ?>
			<?php wptouch_the_post(); ?>
			<?php get_template_part( 'page-content' ); ?>
		<?php } ?>
	</div> <!-- content -->

	<?php if ( comments_open() || have_comments() ) { ?>
		<div id="comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>

	<?php get_footer(); ?>

<?php } ?>