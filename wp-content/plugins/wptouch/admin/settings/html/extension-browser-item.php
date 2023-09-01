<li class="<?php wptouch_the_addon_classes(); ?> <?php if ( wptouch_get_addon_buy_url() && !defined( 'WPTOUCH_IS_FREE') ) { echo 'no-license'; } ?>" data-pushview="#<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>-container">
	<div class="item-information">
		<?php if ( wptouch_get_addon_icon() ) { ?>
			<div class="image-wrapper">
				<img src="<?php wptouch_the_addon_icon(); ?>" alt="<?php wptouch_the_addon_title(); ?>" class="<?php echo wptouch_convert_to_class_name( wptouch_get_addon_title() ); ?>" />
			</div>
		<?php } ?>
		<h2><?php wptouch_the_addon_title(); ?>&nbsp;
			<?php if ( !wptouch_is_addon_in_cloud() && is_network_admin() && wptouch_is_controlled_network()  ) { ?>
				<span class="installed"><?php _e( 'Installed', 'wptouch-pro' ); ?></span>
			<?php } ?>
		</h2>
		<p class="desc"><?php wptouch_the_addon_description(); ?></p>
	</div>
</li><!-- indidivudal extension -->