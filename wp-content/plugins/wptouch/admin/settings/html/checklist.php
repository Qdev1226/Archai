	<?php while ( wptouch_admin_has_list_options() ) { ?>
		<div class="checkbox-wrap">
			<?php wptouch_admin_the_list_option(); ?>
			<?php wptouch_admin_the_list_option_desc(); ?>
			<?php if ( wptouch_admin_is_setting_new() ) { ?>
				<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
			<?php } ?>
			<input type="hidden" name="checklist-<?php wptouch_admin_the_setting_name(); ?>" value="1">
			<input type="checkbox" class="checkbox" id="<?php wptouch_admin_the_setting_name(); ?>-<?php wptouch_admin_the_list_option_key(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>[]" value="<?php wptouch_admin_the_list_option_key(); ?>"<?php if ( wptouch_admin_is_checklist_option_selected() ) echo " checked"; ?>>
			<label for="<?php wptouch_admin_the_setting_name(); ?>-<?php wptouch_admin_the_list_option_key(); ?>"></label>
		</div>
	<?php } ?>