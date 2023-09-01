<?php if ( !empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) { ?>
		die ( 'Please do not load this page directly. Thanks!' );
<?php } ?>

<?php if ( post_password_required() ) { return; } ?>

<?php if ( have_comments() ) { ?>

	<h3 id="responses" class="heading-font">
		<?php comments_number( __( 'no responses', 'wptouch-pro' ), __( '1 response', 'wptouch-pro' ), __( '% responses', 'wptouch-pro' ) ); ?>
	</h3>

	<ol class="commentlist">
		<?php wp_list_comments( 'type=comment&avatar_size=80&max_depth=3&callback=wptouch_fdn_display_comment' ); ?>

		<?php if ( wptouch_fdn_comments_pagination() ) { ?>
			<?php if ( get_option( 'default_comments_page' ) == 'newest' ) { ?>
				<?php if ( get_previous_comments_link() ) { ?>
					<li class="load-more-comments-wrap">
						<?php previous_comments_link( __( 'Load More Comments&hellip;', 'wptouch-pro' ) ); ?>
					</li>
				<?php } ?>
			<?php } else { ?>
				<?php if ( get_next_comments_link() ) { ?>
					<li class="load-more-comments-wrap">
						<?php next_comments_link( __( 'Load More Comments&hellip;', 'wptouch-pro' ) ); ?>
					</li>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</ol>

<?php } else { ?>

	<?php if ( comments_open() ) { ?>
		<!-- If comments are open, but there are no comments -->
 	<?php } else { ?>
		<p class="nocomments"><?php _e( 'Comments are closed', 'wptouch-pro' ); ?></p>
 	<?php }?>

<?php } ?>

<!--  End of dealing with the comments, now the comment form -->

<?php if ( comments_open() ) { ?>

	<div id="respond">

		<?php
			comment_form( array(
				'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
				'title_reply_after'  => '</h2>',
			) );
		?>
		
	</div><!-- #respond // end dealing with the comment form -->

<?php }
