<?php get_header(); ?>

	<?php if ( !bauhaus_if_carousel_view_enabled() && bauhaus_should_show_featured() ) { ?>
		<?php bauhaus_featured_slider(); ?>
	<?php } ?>

<div id="content">

	<?php if ( bauhaus_if_carousel_view_enabled() ) {
		get_template_part( '/layouts/carousel-view' );
	} else {
		get_template_part( '/layouts/post-listing-view' );
	} ?>

</div><!-- #content -->

<?php get_footer(); ?>