<!-- depreciated in 4.0 -->
<div class="radio-group">

	<label class="radiolist" for="<?php wptouch_admin_the_setting_name(); ?>">
		<?php if ( wptouch_get_locale() == 'fr_FR' ) { ?>
		<?php wptouch_admin_the_setting_desc(); ?> :
		<?php } else { ?>
		<?php wptouch_admin_the_setting_desc(); ?>:
		<?php } ?>
	</label>

	<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
		<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
	<?php } ?>

	<?php if ( wptouch_admin_is_setting_new() ) { ?>
		<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
	<?php } ?>

	<?php while ( wptouch_admin_has_list_options() ) { ?>
		<?php wptouch_admin_the_list_option(); ?>
		<p><input type="radio" name="<?php wptouch_admin_the_encoded_setting_name(); ?>" value="<?php wptouch_admin_the_list_option_key(); ?>"<?php if ( wptouch_admin_is_list_option_selected() ) echo " checked"; ?>><?php wptouch_admin_the_list_option_desc(); ?></p>
	<?php } ?>

</div>
