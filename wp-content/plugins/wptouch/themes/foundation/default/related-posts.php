<?php if ( function_exists( 'wptouch_related_posts' ) ) { ?>
	<?php if ( wptouch_has_related_posts() ) { ?>
		<div class="related-posts">
			<h3><?php _e( 'Related', 'wptouch-pro' ); ?></h3>
			<ul class="related">
				<?php $related_posts = wptouch_related_posts(); ?>
				<?php foreach( $related_posts as $related_post ) { ?>
				<li class="<?php if ( $related_post->thumbnail != '' ) echo 'has-thumb'; ?><?php if ( $related_post->excerpt == '' ) { echo ' no-excerpt'; } ?>">
					<?php
						if ( $related_post->thumbnail != '' ) {
							echo $related_post->thumbnail;
						} else {
							echo "<div class='no-thumb'></div>";
						}
					?>
					<strong><a href="<?php echo $related_post->link; ?>"><?php echo $related_post->title; ?></a></strong>
					<p><?php echo $related_post->excerpt; ?></p>
				</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>
<?php } ?>