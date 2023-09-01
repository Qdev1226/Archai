<div class="<?php wptouch_admin_the_setting_name(); ?>_wrap uploader" id="<?php wptouch_admin_the_setting_name(); ?>">
	<div class="image-placeholder">
		<?php $image = wptouch_admin_get_setting_value(); ?>
		<?php if ( $image ) { ?>
			<img src="<?php echo WPTOUCH_BASE_CONTENT_URL . $image; ?>" />
		<?php } ?>
		<div class="progress" style="display:none" title="<?php _e( 'Upload Complete!', 'wptouch-pro' ); ?>" data-placement="right">
		  <div class="bar" style="width: 0%;"></div>
		</div>
	</div>

	<button id="<?php wptouch_admin_the_setting_name(); ?>_upload" data-esn="<?php wptouch_admin_the_encoded_setting_name(); ?>" class="upload button">
		<?php _e( 'Upload', 'wptouch-pro' ); ?>
	</button>

	<button class="delete button" <?php  if ( !$image ) { echo 'style="display: none;"'; } ?>>
		<?php _e( 'Delete', 'wptouch-pro' ); ?>
	</button>


	<br class="wptouch-clearfix" />

	<span class="upload-desc">
		<?php wptouch_admin_the_setting_desc(); ?> <?php include( WPTOUCH_DIR . '/include/html/pro.php' ); ?>
	</span>

	<div id="<?php wptouch_admin_the_setting_name(); ?>_spot" class="<?php wptouch_admin_the_setting_name(); ?>_upload" style="display: none;"></div>
</div>