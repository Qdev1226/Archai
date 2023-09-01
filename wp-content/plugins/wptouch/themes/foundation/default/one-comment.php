<?php
	$settings = foundation_get_settings();
?>
<li class="comment clearfix" id="comment-<?php echo get_comment_ID()?>">
	<div class="comment-top">
		<div class="comment-avatar">
			<?php echo get_avatar( $comment ); ?>
		</div>
		<div class="meta">
			<div class="comment-author"><?php comment_author(); ?></div>
			<div class="comment-time"><?php comment_date(); ?></div>
		</div>
	</div>

	<div class="comment-body">
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation', 'wptouch-pro' ); ?>&hellip;</p>
		<?php endif; ?>
		<?php comment_text(); ?>
		<?php if ( comments_open() ) { ?>
			<p><a href="<?php echo esc_url( add_query_arg( 'replytocom', $comment->comment_ID ) ) . '#respond'; ?>"><?php _e( 'Reply' ); ?></a></p>
		<?php } ?>
	</div>