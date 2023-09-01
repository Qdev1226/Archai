<span><?php wptouch_admin_the_setting_desc(); ?></span>

<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
	<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
<?php } ?>

<?php if ( wptouch_admin_is_setting_new() ) { ?>
	<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
<?php } ?>

<?php if ( wptouch_admin_is_setting_pro() && defined( 'WPTOUCH_IS_FREE' ) ) { ?>
	<span class="pro"><a href="<?php echo esc_url( admin_url( 'admin.php?page=wptouch-admin-go-pro' ) ); ?>"><?php _e( 'Pro', 'wptouch-pro' ); ?></a></span>
<?php } ?>

<input type="text" autocomplete="off" class="text" id="<?php wptouch_admin_the_setting_name(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" value="<?php wptouch_admin_the_setting_value(); ?>" placeholder="" <?php if ( wptouch_admin_is_setting_pro() && defined( 'WPTOUCH_IS_FREE' ) ) echo ' disabled '; ?> />
