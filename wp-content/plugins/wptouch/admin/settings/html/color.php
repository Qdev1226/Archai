<?php wptouch_admin_the_setting_desc(); ?>

<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
	<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
<?php } ?>

<?php if ( wptouch_admin_is_setting_new() ) { ?>
	<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
<?php } ?>

<?php $colors = json_encode( wptouch_get_desktop_theme_colors() ); ?>

<input type="text" autocomplete="off" class="wptouch-color" data-desktop-palette='<?php echo $colors; ?>' id="<?php wptouch_admin_the_setting_name(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" value="<?php wptouch_admin_the_setting_value(); ?>" placeholder="" />