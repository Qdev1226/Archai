<li class="<?php wptouch_the_theme_classes(); ?> <?php if ( wptouch_get_theme_buy_url() && !defined( 'WPTOUCH_IS_FREE') ) { echo 'no-license'; } ?>" data-pushview="#<?php echo wptouch_convert_to_class_name( wptouch_get_theme_title() ); ?>-container">

		<div class="item-information">
			<?php if ( wptouch_get_theme_icon() ) { ?>
			<div class="image-wrapper">
				<img src="<?php wptouch_the_theme_icon(); ?>" alt="<?php wptouch_the_theme_title(); ?>" id="<?php echo esc_attr( wptouch_convert_to_class_name( wptouch_get_theme_title() ) ); ?>" />
			</div><!-- image-wrapper -->
			<?php } ?>
			<h2>
				<?php wptouch_the_theme_title(); ?>
				<?php if ( !wptouch_is_theme_in_cloud() && is_network_admin() && wptouch_is_controlled_network()  ) { ?>
					<span class="installed"><?php _e( 'Installed', 'wptouch-pro' ); ?></span>
				<?php } ?>
			</h2>

			<p class="desc">
				<?php wptouch_the_theme_description(); ?>
			</p>
		</div>
</li>
