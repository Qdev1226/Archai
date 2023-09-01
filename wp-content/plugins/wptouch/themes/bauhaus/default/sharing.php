<div class="<?php foundation_sharing_classes(); ?>">
	<a class="facebook-btn no-ajax" href="//www.facebook.com/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank"><?php _e( 'Share', 'wptouch-pro' ); ?></a>
	<a class="twitter-btn no-ajax" href="//twitter.com/intent/tweet?source=wptouchpro3&text=<?php echo htmlspecialchars( urlencode( html_entity_decode( get_the_title() . ' - ' ) ) ); ?>&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank"><?php _e( 'Tweet', 'wptouch-pro' ); ?></a>
	<a class="pinterest-btn no-ajax" href="//pinterest.com/pin/create/button/?url=<?php echo urlencode( get_permalink() ); ?>&media=<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" target="_blank"><?php _e( 'Pin', 'wptouch-pro' ); ?></a>
	<a class="email-btn no-ajax" href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&body=<?php echo rawurlencode( get_permalink() ); ?>"><?php  _e( 'Mail', 'wptouch-pro' ); ?></a>
	<a class="sms-btn no-ajax" href="sms:&body=<?php echo esc_html( get_the_title() ); ?>: <?php echo rawurlencode( get_permalink() ); ?>"><span><?php _e( 'SMS', 'wptouch-pro' ); ?></span></a>
</div>