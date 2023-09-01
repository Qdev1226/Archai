<?php _e( 'Redirect target', 'wptouch-pro' ); ?>

<?php
	$settings = wptouch_get_settings();

	ob_start();
	wp_dropdown_pages();
	$contents = ob_get_contents();
	ob_end_clean();

	$contents = str_replace( "id='page_id'", 'id="' . wptouch_admin_get_setting_name() . '"', $contents );
	$contents = str_replace( "name='page_id'", 'name="' . wptouch_admin_get_encoded_setting_name() . '"', $contents );
	$value_string = 'value="' . $settings->homepage_redirect_wp_target . '"';
	$contents = str_replace( $value_string, $value_string . ' selected', $contents );

	echo $contents;