<ul class="custom-uploads-display wptouch-clearfix">
	<?php if ( !wptouch_have_custom_icons() ) { ?>
		<li class="no-icons"><p><?php _e( 'No icons have been uploaded yet', 'wptouch-pro' ); ?>&hellip;</p></li>
	<?php } else { ?>
			<?php while ( wptouch_have_custom_icons() ) { ?>
				<?php wptouch_the_custom_icon(); ?>

				<li class="custom-icon">
					<img src="<?php wptouch_the_custom_icon_image(); ?>" alt="<?php wptouch_the_custom_icon_name(); ?>" />
					<p class="name" data-name="<?php wptouch_the_custom_icon_name(); ?>">
						<?php wptouch_the_custom_icon_name(); ?>
						<a href="#" class="delete-custom-icon icon-cancel-circled" style="display:none"></a>
					</p>
					<p class="size"><?php wptouch_the_custom_icon_width(); ?>x<?php wptouch_the_custom_icon_height(); ?></p>
				</li>
			<?php } ?>
	<?php } ?>
</ul>
