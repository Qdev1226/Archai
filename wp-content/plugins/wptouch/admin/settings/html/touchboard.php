<?php $settings = wptouch_get_settings(); ?>

<!-- what's new right side -->
<div class="overview-right-wrap wptouch-clearfix">
	<div id="touchboard-links" class="overview-box-appearance">
		<h3><?php _e( 'Quick Links', 'wptouch-pro' ); ?></h3>
		<ul>
			<li><a href="#" data-toggle="modal" data-target="#modal-updates"><?php _e( 'What\'s New Changelog', 'wptouch-pro' ); ?></a></li>

			<?php if ( !defined( 'WPTOUCH_IS_FREE' ) ) { ?>
			<li><a href="//support.wptouch.com" target="_blank"><?php _e( 'Knowledgebase & Support', 'wptouch-pro' ); ?></a></li>
			<li><a href="//www.wptouch.com/account/" target="_blank"><?php _e( 'Manage Account & License', 'wptouch-pro' ); ?></a></li>
			<?php } else { ?>
			<li><a href="//wptouch.s3.amazonaws.com/docs/WPtouch%20User%20Guide.pdf"><?php _e( 'WPtouch User Guide', 'wptouch-pro' ); ?></a></li>
			<li><a href="//wptouch.s3.amazonaws.com/WPtouch%20-%20Make%20Your%20Website%20Mobile-Friendly.pdf"><?php _e( 'Mobile-Friendly Guide', 'wptouch-pro' ); ?></a></li>
			<?php } ?>
			<li><a href="//www.twitter.com/wptouch" target="_blank"><i class="wptouch-icon-twitter"></i> <?php _e( 'WPtouch on Twitter', 'wptouch-pro' ); ?> </a></li>

			<?php if ( false && defined( 'WPTOUCH_IS_FREE' ) ) { ?>
				<li><a href="//www.wptouch.com/themes/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web"><?php _e( 'Look at Pro Themes', 'wptouch-pro' ); ?></a></li>
				<li><a href="//www.wptouch.com/extensions/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web"><?php _e( 'Look at Pro Extensions', 'wptouch-pro' ); ?></a></li>
				<li><a href="//www.wptouch.com/features/?utm_campaign=touchboard&utm_source=<?php echo WPTOUCH_UTM_SOURCE; ?>&utm_medium=web"><?php _e( 'Look at Pro Features', 'wptouch-pro' ); ?></a></li>
			<?php } ?>
		</ul>
	</div>

	<div id="touchboard-news" class="overview-box-appearance">
		<h3>
			<?php _e( 'WPtouch News', 'wptouch-pro' ); ?>
			<a href="//www.wptouch.com/blog/" target="_blank"><!-- <?php _e( 'Read More', 'wptouch-pro' ); ?>--> <i class="wptouch-icon-external-link"></i></a>
		</h3>
		<span id="ajax-news">
			<!-- ajaxed news here -->
		</span>
	</div><!-- touchboard-news -->
</div><!-- over-right-side -->

<div id="touchboard-left" class="overview-box-appearance"></div><!-- touchboard-left-side -->

	<div class="modal hide" id="modal-updates" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-header">
			<h3><?php echo sprintf( __( '%s Change Log', 'wptouch-pro' ), WPTOUCH_PRODUCT_NAME ); ?></h3>
		</div>
		<div class="modal-body" id="change-log">
		</div>
		<div class="modal-footer">
			<button class="button" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>