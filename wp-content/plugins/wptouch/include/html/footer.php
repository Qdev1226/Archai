<p class="powered-by-msg">
	<?php
		global $footer_settings;
		$bnc_settings = wptouch_get_settings( 'bncid' );
	?>

	<?php 
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);
		echo wp_kses(sprintf( __( 'Powered by<br/>%s%s%s', 'wptouch-pro' ) , '<a href="http://www.wptouch.com/?utm_campaign=wptouch-powered-by&utm_medium=web" target="_blank">', 'WPtouch Mobile Suite for WordPress ', '</a>'),$allowed_html); ?>
</p>
