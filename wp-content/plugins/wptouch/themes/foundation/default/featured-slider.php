<?php
$settings = foundation_get_settings();
?>
<div class='one-swipe-image' style='visibility: hidden;'>
	<a href='<?php echo the_permalink(); ?>' class='needsclick'>
		<div class='comments-number'><span><?php echo wptouch_get_comment_count(); ?></span></div>
		<img src='<?php echo foundation_featured_get_image(); ?>' alt='<?php the_title(); ?>' / >
		<p class='featured-date'><?php wptouch_the_time(); ?></p>
		<p class='featured-title'><span><?php the_title(); ?></span></p>
	</a>
</div>
