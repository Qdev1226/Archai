<ul class="manage-sets">
	<?php while ( wptouch_have_icon_packs() ) { ?>
		<?php wptouch_the_icon_pack(); ?>
		<?php if ( wptouch_get_icon_pack_name() == __( 'Custom Icons', 'wptouch-pro' ) ) continue; ?>
		<li>
			<img src="<?php wptouch_the_icon_pack_thumbnail(); ?>" alt="placeholder">
			<p class="set-title"><?php wptouch_the_icon_pack_name(); ?></p>
			<p class="set-author">
				<a href="<?php  echo wptouch_get_icon_pack_author_url(); ?>" target="_blank">
					<?php echo wptouch_get_icon_pack_author(); ?>
				</a>
			</p>
			<span class="installed"><?php _e( 'Installed', 'wptouch-pro' ); ?></span>
		</li>
	<?php } ?>

	<?php $remote_icon_sets = wptouch_get_remote_icon_packs(); ?>
	<?php if ( $remote_icon_sets ) { ?>
		<?php foreach( $remote_icon_sets as $icon_set ) { ?>
			<?php if ( !wptouch_already_has_icon_pack( $icon_set->name ) ) { ?>
			<li>
				<img src="<?php echo $icon_set->thumbnail; ?>" alt="placeholder">
				<p class="set-title"><?php echo $icon_set->name; ?></span>
				<p class="set-author"><a href="<?php echo $icon_set->author_url; ?>"><?php echo $icon_set->author; ?></a></p>
				<button class="button" data-loading-text="<?php _e( 'Installing', 'wptouch-pro' ); ?>&hellip;" data-base-path="<?php echo $icon_set->dir_base; ?>" data-install-url="<?php echo $icon_set->download_url; ?>"><?php _e( 'Install', 'wptouch-pro' ); ?></button>
				<span class="installed" style="display: none;"><?php _e( 'Installed', 'wptouch-pro' ); ?></span>
				<span class="error" style="display: none;"><?php _e( 'Unable to Install', 'wptouch-pro' ); ?></span>
			</li>
			<?php } ?>
		<?php } ?>
	<?php } ?>
<ul>
