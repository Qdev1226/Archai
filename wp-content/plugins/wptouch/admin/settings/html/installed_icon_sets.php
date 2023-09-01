<?php if ( is_writable( WPTOUCH_CUSTOM_SET_DIRECTORY ) ) { ?>
	<div id="manage-icon-sets">
		<p class="small"><?php _e( 'Loading icon sets', 'wptouch-pro' ); ?>&hellip;</p>
	</div>
<?php } else { ?>
	<div id="manage-icon-set-error">
		<p class="small"><?php echo sprintf( __( 'The %s%s%s directory is not writable. %sPlease fix this issue to install additional icon sets.', 'wptouch-pro' ), '<strong>', '/wptouch-data/icons', '</strong>', '<br/>' ); ?></p>
	</div>
<?php } ?>