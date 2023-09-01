<?php $settings = bauhaus_get_settings(); ?>

<div class="flickity-view">

	<?php if ( !bauhaus_is_latest_only() ) { ?>
		<h2><?php _e( 'Latest', 'wptouch-pro' ); ?></h2>
	<?php } ?>
	
	<div rel="<?php echo get_next_posts_page_link(); ?>" class="carousel recent-carousel">
		<?php if ( wptouch_have_posts() ) while ( wptouch_have_posts() ) { wptouch_the_post(); ?>
		<?php if ( wptouch_has_post_thumbnail() ) { ?>
			<div class="carousel-cell">
				<?php get_template_part( 'layouts/carousel-post-loop' ); ?>
			</div>
		<?php } ?>
		<?php } ?>
	</div>

	<?php if ( bauhaus_should_show_carousel_featured() ) { ?>
		<h2><?php _e( 'Featured', 'wptouch-pro' ); ?></h2>
			<?php bauhaus_featured_slider(); ?>
	<?php } ?>

	<?php if ( bauhaus_if_popular_enabled() ) {  $max_pop_posts = $settings->bauhaus_popular_max_number_of_posts; ?>
		<h2><?php _e( 'Popular', 'wptouch-pro' ); ?></h2>

		<div class="carousel popular-carousel">
			<?php $popular = new WP_Query( array( 'ignore_sticky_posts' => 1, 'orderby' => 'comment_count', 'posts_per_page' => $max_pop_posts, 'meta_query' => array( array('key' => '_thumbnail_id') ) ) ); ?>
			<?php if ( $popular->have_posts() ) while ( $popular->have_posts() ) : $popular->the_post(); ?>
				<div class="carousel-cell">
					<?php get_template_part( 'layouts/carousel-post-loop' ); ?>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	<?php } ?>

</div>