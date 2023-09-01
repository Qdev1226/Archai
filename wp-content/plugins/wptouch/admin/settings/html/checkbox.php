<div class="checkbox-wrap">
	<?php wptouch_admin_the_setting_desc(); ?>
	<?php if ( wptouch_admin_is_setting_new() ) { ?>
		<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
	<?php } ?>

	<?php if ( wptouch_admin_is_setting_pro() && defined( 'WPTOUCH_IS_FREE' ) ) { ?>
		<span class="pro"><a href="<?php echo admin_url( 'admin.php?page=wptouch-admin-go-pro' ); ?>"><?php _e( 'Pro', 'wptouch-pro' ); ?></a></span>
	<?php } ?>

	<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
		<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
	<?php } ?>

	<input type="hidden" name="hid-<?php wptouch_admin_the_encoded_setting_name(); ?>" value="hide" />
	<input type="checkbox" class="checkbox" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" id="<?php wptouch_admin_the_setting_name(); ?>"<?php if ( wptouch_admin_is_setting_pro() && defined( 'WPTOUCH_IS_FREE' ) ) echo ' disabled '; ?><?php if ( wptouch_admin_is_setting_checked() ) echo " checked"; ?> />
	<label for="<?php wptouch_admin_the_setting_name(); ?>"></label>
</div>