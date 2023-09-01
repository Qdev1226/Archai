<span><?php wptouch_admin_the_setting_desc(); ?></span>

<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
	<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
<?php } ?>

<?php if ( wptouch_admin_is_setting_new() ) { ?>
	<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
<?php } ?>

<div id="setting_content">
	<?php wptouch_admin_do_callback(); ?>
</div>