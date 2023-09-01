<!-- post loop -->
<div rel="<?php wptouch_the_permalink(); ?>">

	<a href="<?php wptouch_the_permalink(); ?>" class="needsclick">
		<img data-flickity-lazyload="<?php echo bauhaus_get_featured_image(); ?>" alt="<?php the_title(); ?>" />
	</a>

	<?php if ( bauhaus_should_show_date() || bauhaus_should_show_author() || bauhaus_should_show_comments() ) { ?>
		<span class="post-meta body-font">
			
			<?php if ( bauhaus_should_show_date() ) { ?>
				<?php wptouch_the_time(); ?>
			<?php } ?>
		 	
	 		<?php if ( bauhaus_should_show_author() ) { ?>
	 			<?php if ( bauhaus_should_show_date() ) echo '&bull;'; ?> <?php _e( 'by', 'wptouch-pro' ); ?> <?php the_author(); ?>
	 		<?php } ?>
	 	
	 		<?php if ( wptouch_get_comment_count() > 0 && ( comments_open() || bauhaus_should_show_comments() ) ) { ?>
				<?php if ( bauhaus_should_show_date() || bauhaus_should_show_author() ) echo '<br />'; ?> <?php comments_number( 'no comments', '1 comment', '% comments' ); ?>
			<?php } ?>
		 </span>
	 <?php } ?>

	<a href="<?php wptouch_the_permalink(); ?>" class="needsclick">
		<h2 class="post-title heading-font"><?php the_title(); ?></h2>
	</a>

</div>