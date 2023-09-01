<?php
/*
	Mobile Template: Home Template
*/
?>
<?php get_header(); ?>

<?php if ( have_posts() ) { wptouch_the_post(); ?>
<div id="content">
	<div class="post-content">
		<?php wptouch_the_content(); ?>
	</div>
</div>
<?php } ?>

<?php get_footer(); ?>