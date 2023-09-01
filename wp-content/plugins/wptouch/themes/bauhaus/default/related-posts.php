<?php if ( function_exists( 'wptouch_related_posts' ) ) { ?>
	<?php if ( wptouch_has_related_posts() ) { ?>
		<div class="related-posts">
			<h3><?php _e( 'Related', 'wptouch-pro' ); ?></h3>
			<ul class="related">
				<?php $related_posts = wptouch_related_posts(); ?>
				<?php foreach( $related_posts as $related_post ) { ?>
					<li class="<?php if ( $related_post->thumbnail != '' ) echo 'has-thumb'; ?><?php if ( $related_post->excerpt == '' ) { echo ' no-excerpt'; } ?>">
						<?php if ( $related_post->thumbnail != '' ) { ?>
							<?php echo $related_post->thumbnail; ?>
						<?php  }
						else { ?>
							<div class="date-circle">
								<span class="month"><?php echo $related_post->month; ?></span>
								<span class="day"><?php echo $related_post->day; ?></span>
							</div>
						<?php } ?>
						<strong>
							<a href="<?php echo $related_post->link; ?>"><?php echo $related_post->title; ?></a>
						</strong>
						<p><?php echo $related_post->excerpt; ?></p>
					</li>
				<?php } // each related post ?>
			</ul>
		</div>
	<?php } ?>
<?php } ?>
