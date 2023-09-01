<?php if ( wptouch_admin_get_setting_desc() != '' ) { ?>
	<label class="textarea" for="<?php wptouch_admin_the_setting_name(); ?>">
		<?php wptouch_admin_the_setting_desc(); ?>
	</label>

	<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
		<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
	<?php } ?>

	<?php if ( wptouch_admin_is_setting_new() ) { ?>
		<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
	<?php } ?>

	<br />
<?php } ?>

<textarea rows="5" class="textarea"  id="<?php wptouch_admin_the_setting_name(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>"><?php echo htmlspecialchars( wptouch_admin_the_setting_value() ); ?></textarea>