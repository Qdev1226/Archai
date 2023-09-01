<div class="checkbox-wrap">
	<?php
		preg_match('/\[(.*?)\]/', wptouch_admin_get_setting_name(), $matches );
		$post_type_name = str_replace( array('[',']') , ''  , $matches[0] );
		$type_object = get_post_type_object( $post_type_name );
		$type_object_encoded = urlencode( serialize( $type_object ) );
	?>
	<?php echo $type_object->label; ?>
	<input type="hidden" name="object_<?php wptouch_admin_the_encoded_setting_name(); ?>" id="<?php wptouch_admin_the_setting_name(); ?>"<?php if ( wptouch_admin_is_post_type_checked() ) echo " checked"; ?> value="<?php echo $post_type_name . '||||' . $type_object_encoded; ?>" />
	<input type="checkbox" class="checkbox" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" id="<?php echo str_replace( '[', '-', str_replace( ']', '-', wptouch_admin_get_setting_name() ) ); ?>" <?php if ( wptouch_admin_is_post_type_checked() ) { echo ' checked'; } ?> value="<?php echo $post_type_name . '||||' . $type_object_encoded; ?>" />
	<label for="<?php echo str_replace( '[', '-', str_replace( ']', '-', wptouch_admin_get_setting_name() ) ); ?>"></label>
</div>