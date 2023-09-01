<div class="multiline comma">
	<span><?php wptouch_admin_the_setting_desc(); ?></span>

	<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
		<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
	<?php } ?>

	<?php if ( wptouch_admin_is_setting_new() ) { ?>
		<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
	<?php } ?>

	<?php if ( wptouch_admin_is_setting_pro() && defined( 'WPTOUCH_IS_FREE' ) ) { ?>
		<span class="pro"><a href="<?php echo admin_url( 'admin.php?page=wptouch-admin-go-pro' ); ?>"><?php _e( 'Pro', 'wptouch-pro' ); ?></a></span>
	<?php } ?>

	<div class="input-wrap">
		<input type="text" class="add-entry" placeholder="" <?php if ( wptouch_admin_is_setting_pro() && defined( 'WPTOUCH_IS_FREE' ) ) echo ' disabled '; ?> />
		<a href="#" class="add icon-plus-circled"></a>
		<textarea style="width:400px; height: 100px" id="<?php wptouch_admin_the_setting_name(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>"><?php wptouch_admin_the_setting_value(); ?></textarea>
	</div>

	<?php $entries = wptouch_admin_split_setting(); ?>

	<?php if ( count( (array) $entries ) > 0 ) { ?>
		<ul class="<?php wptouch_admin_the_encoded_setting_name(); ?>-list">
		<?php foreach ( $entries as $entry ) { ?>
			<?php if ( $entry != '' ) { ?>
				<li><?php echo $entry; ?></li>
			<?php } ?>
		<?php } ?>
		</ul>
	<?php } ?>
</div>