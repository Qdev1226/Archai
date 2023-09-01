<?php get_header(); ?>

	<div id="content">
		<?php while ( wptouch_have_posts() ) { ?>

			<?php wptouch_the_post(); ?>

			<div class="<?php wptouch_post_classes(); ?>">
				<div class="post-page-head-area bauhaus">
					<span class="post-date-comments">
						<?php if ( bauhaus_should_show_date() ) { ?>
							<?php wptouch_the_time(); ?>
						<?php } ?>
						<?php if ( bauhaus_should_show_comments() ) { ?>
							<?php if ( bauhaus_should_show_date() && ( comments_open() || wptouch_have_comments() ) ) echo '&bull;'; ?>
							<?php if ( comments_open() || wptouch_have_comments() ) comments_number( __( 'no comments', 'wptouch-pro' ), __( '1 comment', 'wptouch-pro' ), __( '% comments', 'wptouch-pro' ) ); ?>
						<?php } ?>
					</span>
					<h1 class="post-title heading-font"><?php wptouch_the_title(); ?></h1>
					<?php if ( bauhaus_should_show_author() ) { ?>
						<span class="post-author"><?php the_author(); ?></span>
					<?php } ?>
					<?php if ( bauhaus_should_show_thumbnail() && wptouch_has_post_thumbnail() ) { ?>
						<div class="post-page-thumbnail">
							<?php the_post_thumbnail('large', array( 'class' => 'post-thumbnail wp-post-image' ) ); ?>
						</div>
					<?php } ?>
				</div>

				<div class="post-page-content">
					<?php wptouch_the_content(); ?>
					<?php if ( bauhaus_should_show_taxonomy() ) { ?>
						<?php if ( wptouch_has_categories() || wptouch_has_tags() ) { ?>
							<div class="cat-tags">
								<?php if ( wptouch_has_categories() ) { ?>
									<?php _e( 'Categories', 'wptouch-pro' ); ?>: <?php wptouch_the_categories(); ?><br />
								<?php } ?>
								<?php if ( wptouch_has_tags() ) { ?>
									<?php _e( 'Tags', 'wptouch-pro' ); ?>: <?php wptouch_the_tags(); ?>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>

		<?php } ?>
	</div> <!-- content -->

	<?php do_action( 'wptouch_after_post_content' ); ?>

	<?php get_template_part( 'nav-bar' ); ?>
	<?php if ( comments_open() || wptouch_have_comments() ) { ?>
		<div id="comments">
			<?php comments_template(); ?>
		</div>
	<?php } ?>

<?php get_footer(); ?>
