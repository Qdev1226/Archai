<!-- post loop -->
<a href="<?php wptouch_the_permalink(); ?>" class="loop-link tappable clearfix <?php if ( !bauhaus_should_show_thumbnail() ) { echo 'no-thumbs'; } ?>">

	<?php if ( bauhaus_should_show_thumbnail() && wptouch_has_post_thumbnail() ) { ?>
		<img src="<?php wptouch_the_post_thumbnail( 'thumbnail' ); ?>" alt="thumbnail" class="post-thumbnail wp-post-image" />
	<?php } else if ( bauhaus_should_show_thumbnail() && !wptouch_has_post_thumbnail() ) { ?>
		<div class="date-circle">
			<span class="month"><?php wptouch_the_time( 'M' ); ?></span>
			<span class="day"><?php wptouch_the_time( 'j' ); ?></span>
		</div>
	<?php } ?>

	<?php if ( bauhaus_should_show_date() || bauhaus_should_show_author() ) { ?>
		<span class="post-meta body-font">
			<?php if ( bauhaus_should_show_date() ) { wptouch_the_time(); } ?>
		 	<?php if ( bauhaus_should_show_author() ) { ?>
		 		<?php if ( bauhaus_should_show_date() ) echo '&bull;'; ?> <?php _e( 'by', 'wptouch-pro' ); ?> <?php the_author(); ?>
		 	<?php } ?>
		 </span>
	 <?php } ?>

	<h2 class="post-title heading-font"><?php the_title(); ?></h2>

	<?php if ( comments_open() || wptouch_have_comments() ) { ?>
		<div class="post-meta comments">
			<?php comments_number( __('no responses', 'wptouch-pro'), __('1 response', 'wptouch-pro'), __('% responses', 'wptouch-pro') ); ?>
		</div>
	<?php } ?>

	<?php if ( wptouch_should_load_rtl() ) { ?>
		<i class="arrow wptouch-icon-angle-left"></i>
	<?php } else { ?>
		<i class="arrow wptouch-icon-angle-right"></i>
	<?php } ?>
	<span class="bottom-border"><!--css border--></span>
</a>
