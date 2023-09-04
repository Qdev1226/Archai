<?php
$curr_page = "";
if( isset( $_GET['subpage'] ) )
	$curr_page = sanitize_key( $_GET['subpage'] );
else
	$curr_page = sanitize_key( $_GET['page'] );
?>
<div class="wpeasycart_upsell_panel_wrap">
	<div class="wpeasycart_upsell_panel">
		<div class="wpeasycart_upsell_panel_section wpeasycart_upsell_panel_green">
			<h1><?php esc_attr_e( 'YOUR STORE, SIMPLE AND EASY.', 'wp-easycart' ); ?></h1>
			<h4><?php esc_attr_e( 'Upgrade to PRO and unlock every feature. Upgrade to Premium and unlock your full potential with ALL EasyCart extensions included.', 'wp-easycart' ); ?></h4>
		</div>
		<div class="wpeasycart_upsell_panel_section">
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_paypal_express">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Paypal Express Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'When you upgrade you are getting PayPal Express + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=paypal-express" target="_blank"><?php esc_attr_e( 'Learn more about PayPal Express', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=paypal-express&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<?php /* SHIPPING UPSALES */ ?>
			<?php if( isset( $_GET['subpage'] ) && ( $_GET['subpage'] == 'shipping-settings' || $_GET['subpage'] == 'shipping-rates' ) ){ ?>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_australia_post">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Australia Post Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with Australia Post + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about Australia Post', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=australia-post&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_canada_post">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Canada Post Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with Canada Post + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about Canada Post', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=canada-post&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_dhl">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'DHL Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with DHL + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about DHL', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_fedex">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'FedEx Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with FedEx + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about FedEx', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>

			</div>

			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_ups">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'UPS Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with UPS + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about UPS', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_usps">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'USPS Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with USPS + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about USPS', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<?php }?>
			<?php if ( '' != apply_filters( 'wp_easycart_trial_start_content', 'true' ) ) { ?>
				<?php $pro_plugin_base = 'wp-easycart-pro/wp-easycart-admin-pro.php'; ?>
				<?php $pro_plugin_file = EC_PLUGIN_DIRECTORY . '-pro/wp-easycart-admin-pro.php'; ?>
				<?php if ( file_exists( $pro_plugin_file ) && ! is_plugin_active( $pro_plugin_base ) ) { ?>
					<div class="ec_admin_message_error">
						<p><?php esc_attr_e( 'WP EasyCart PRO is installed but NOT ACTIVATED. Please', 'wp-easycart' ); ?> 
							<a href="<?php echo esc_url_raw( wp_easycart_admin( )->get_pro_activation_link( ) ); ?>"><?php esc_attr_e( 'click here to activate your WP EasyCart PRO plugin', 'wp-easycart' ); ?></a>.
						</p>
					</div>
				<?php } ?>
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Start Your FREE 14 Day PRO Trial', 'wp-easycart' ) ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'To start your free trial, simply click the install button below.', 'wp-easycart' ) ?></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="admin.php?page=wp-easycart-registration&ec_trial=start"><?php esc_attr_e( 'INSTALL YOUR PRO TRIAL NOW!', 'wp-easycart' ) ?></a></div>
				<div class="ec_admin_upgrade_subheader" style="font-size:14px;">*<?php esc_attr_e( 'WP EasyCart PRO plugin will install immediately on click and your trial will start.', 'wp-easycart' ) ?></div>
				<div class="ec_admin_upgrade_subheader" style="font-size:14px;">*<?php esc_attr_e( 'No credit card required to start trial, remove WP EasyCart PRO at any time.', 'wp-easycart' ) ?></div>
			<?php } ?>
		</div>
		<div class="wpeasycart_upsell_panel_section wpeasycart_upsell_panel_grey">
			<h2><?php esc_attr_e( 'Unlock a World of Powerful Features - WP EasyCart PRO', 'wp-easycart' ); ?></h2>
			<h5><?php esc_attr_e( 'Discover a wealth of feature-rich tools, plugins, and extensions with WP EasyCart PRO. From automated marketing to Quickbooks integration, tax management, and shipping options, our comprehensive platform has it all. Get ready to explore a world of possibilities and take your WordPress eCommerce store to new heights. Upgrade to WP EasyCart PRO today and unleash the full potential of your online business!', 'wp-easycart' ); ?></h5>
			<div class="wpeasycart_upsell_panel_section_button_row_center">
				<a href="https://www.wpeasycart.com/wordpress-shopping-cart-features/" target="_blank" class="wpeasycart_upsell_panel_button_outline"><?php esc_attr_e( 'See Our Full Feature List', 'wp-easycart' ); ?></a>
			</div>
			<img src="<?php echo esc_url_raw( plugins_url( "wp-easycart/admin/images/partners.png", EC_PLUGIN_DIRECTORY ) ); ?>" alt="<?php esc_attr_e( 'MailChimp, Square, ShipStation, QB, USPS, UPS', 'wp-easycart' ); ?>" title="<?php esc_attr_e( 'Partner Logos', 'wp-easycart' ); ?>" class="wpeasycart_partner_logos" />
		</div>
		<div class="wpeasycart_upsell_panel_section">
			<h2><?php esc_attr_e( 'Customer support from a human, not a dead-end forum', 'wp-easycart' ); ?></h2>
			<p><?php esc_attr_e( 'WP EasyCart provides quick, personalized support for your shopping cart plugin.  We know every minute of up-time counts for your small business, let us get you back to selling and making a profit. Get the peace of mind knowing that we’re always here to help.', 'wp-easycart' ); ?></p>
			<p><strong><?php esc_attr_e( 'See what happy business owners are saying about EasyCart.', 'wp-easycart' ); ?></strong></p>
			<div class="wpeasycart_upsell_testimonials">
				<div class="wpeasycart_upsell_testimonial">
					<h4>Excellent Support</h4>
					<p>“The support team at WP Easycart are very helpful and prompt. I think the longest I have waited for a reply has been 5 hours. It’s very clear they know what they are doing.”</p>
					<div class="wpeasycart_upsell_testimonial_info">
						<span>jimlaabsmusicstore</span>
						<img src="<?php echo esc_url_raw( plugins_url( "wp-easycart/admin/images/5-stars-1.png", EC_PLUGIN_DIRECTORY ) ); ?>" alt="5/5 Stars" title="5 Stars" class="wpeasycart_testimonial_stars" />
					</div>
				</div>
				<div class="wpeasycart_upsell_testimonial">
					<h4>Best Ecommerce Solution</h4>
					<p>“I have been using WP Easy Cart for over one year and now deployed on 5 websites with absolutely zero issues. When I do have an issue, always very minor tweaks, and I submit a ticket, it is responded too usually with several hours at most personally and answered. I love this company and their product.”</p>
					<div class="wpeasycart_upsell_testimonial_info">
						<span>Cofffeeman</span>
						<img src="<?php echo esc_url_raw( plugins_url( "wp-easycart/admin/images/5-stars-1.png", EC_PLUGIN_DIRECTORY ) ); ?>" alt="5/5 Stars" title="5 Stars" class="wpeasycart_testimonial_stars" />
					</div>
				</div>
			</div>
			<div class="wpeasycart_upsell_panel_section_button_row_center">
				<a href="https://wordpress.org/plugins/wp-easycart/#reviews" target="_blank" class="wpeasycart_upsell_panel_button_outline"><?php esc_attr_e( 'View More Reviews on WordPress', 'wp-easycart' ); ?></a>
			</div>
		</div>
		<div class="wpeasycart_upsell_panel_section wpeasycart_upsell_panel_grey">
			<h2><?php esc_attr_e( 'Start selling today!', 'wp-easycart' ); ?></h2>
			<div class="wpeasycart_upsell_pricing_row">
				<div class="wpeasycart_upsell_pricing_column">
					<div class="wp_easycart_upsell_pricing_column_title"><?php esc_attr_e( 'PROFESSIONAL', 'wp-easycart' ); ?></div>
					<div class="wp_easycart_upsell_pricing_column_item"><?php esc_attr_e( 'Unlocks all the features of the WP EasyCart platform.', 'wp-easycart' ); ?></div>
					<div class="wp_easycart_upsell_pricing_column_item"><?php esc_attr_e( '1 Year Premium Support', 'wp-easycart' ); ?></div>
					<div class="wp_easycart_upsell_pricing_column_item"><a href="https://www.wpeasycart.com/professional-edition-ecommerce/?upsell=5&upsellpage=<?php echo esc_attr( $curr_page ); ?>" target="_blank" class="wpeasycart_upsell_panel_button_outline"><?php esc_attr_e( 'LEARN MORE', 'wp-easycart' ); ?></a></div>
					<div class="wp_easycart_upsell_pricing_column_item"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=5&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'GET PROFESSIONAL', 'wp-easycart' ); ?></a></div>
				</div>
				<div class="wpeasycart_upsell_pricing_column">
					<div class="wp_easycart_upsell_pricing_column_title"><?php esc_attr_e( 'PREMIUM', 'wp-easycart' ); ?></div>
					<div class="wp_easycart_upsell_pricing_column_item"><?php esc_attr_e( 'Unlocks all the features + Includes Premium Extensions.', 'wp-easycart' ); ?></div>
					<div class="wp_easycart_upsell_pricing_column_item"><?php esc_attr_e( '1 Year Premium Support', 'wp-easycart' ); ?></div>
					<div class="wp_easycart_upsell_pricing_column_item"><a href="https://www.wpeasycart.com/wordpress-ecommerce-premium-edition/?upsell=6&upsellpage=<?php echo esc_attr( $curr_page ); ?>" target="_blank" class="wpeasycart_upsell_panel_button_outline"><?php esc_attr_e( 'LEARN MORE', 'wp-easycart' ); ?></a></div>
					<div class="wp_easycart_upsell_pricing_column_item"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_premium_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=6&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'GET PREMIUM', 'wp-easycart' ); ?></a></div>
				</div>
			</div>
		</div>
		<div class="wpeasycart_upsell_panel_section">
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_paypal_express">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Paypal Express Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'When you upgrade you are getting PayPal Express + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=paypal-express" target="_blank"><?php esc_attr_e( 'Learn more about PayPal Express', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=paypal-express&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<?php /* SHIPPING UPSALES */ ?>
			<?php if( isset( $_GET['subpage'] ) && ( $_GET['subpage'] == 'shipping-settings' || $_GET['subpage'] == 'shipping-rates' ) ){ ?>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_australia_post">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Australia Post Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with Australia Post + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about Australia Post', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=australia-post&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_canada_post">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Canada Post Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with Canada Post + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about Canada Post', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=canada-post&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_dhl">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'DHL Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with DHL + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about DHL', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_fedex">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'FedEx Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with FedEx + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about FedEx', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_ups">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'UPS Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with UPS + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about UPS', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<div class="ec_admin_upsell_popup_extras" style="display:none;" id="ec_admin_upsell_popup_usps">
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'USPS Requires an Upgrade!', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'Upgrade to get live shipping rates with USPS + hundreds of other great selling features.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-settings" target="_blank"><?php esc_attr_e( 'Learn more about USPS', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="<?php echo esc_url_raw( apply_filters( 'wp_easycart_upgrade_pro_url', 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/?upsell=dhl&upsellpage=' . esc_attr( $curr_page ) ) ); ?>" target="_blank"><?php esc_attr_e( 'UPGRADE NOW', 'wp-easycart' ); ?></a></div>
			</div>
			<?php }?>
			<?php if ( '' != apply_filters( 'wp_easycart_trial_start_content', 'true' ) ) { ?>
				<?php $pro_plugin_base = 'wp-easycart-pro/wp-easycart-admin-pro.php'; ?>
				<?php $pro_plugin_file = EC_PLUGIN_DIRECTORY . '-pro/wp-easycart-admin-pro.php'; ?>
				<?php if( file_exists( $pro_plugin_file ) && !is_plugin_active( $pro_plugin_base ) ) { ?>
					<div class="ec_admin_message_error">
						<p><?php esc_attr_e( 'WP EasyCart PRO is installed but NOT ACTIVATED. Please', 'wp-easycart' ); ?> <a href="<?php echo esc_url_raw( wp_easycart_admin( )->get_pro_activation_link( ) ); ?>"><?php esc_attr_e( 'click here to activate your WP EasyCart PRO plugin', 'wp-easycart' ); ?></a>.</p>
					</div>
				<?php } ?>
				<div class="ec_admin_upgrade_header"><?php esc_attr_e( 'Start Your FREE 14 Day PRO Trial', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader"><?php esc_attr_e( 'To start your free trial, simply click the install button below.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row"><a href="admin.php?page=wp-easycart-registration&ec_trial=start"><?php esc_attr_e( 'INSTALL YOUR PRO TRIAL NOW!', 'wp-easycart' ); ?></a></div>
				<div class="ec_admin_upgrade_subheader" style="font-size:14px;">*<?php esc_attr_e( 'WP EasyCart PRO plugin will install immediately on click and your trial will start.', 'wp-easycart' ); ?></div>
				<div class="ec_admin_upgrade_subheader" style="font-size:14px;">*<?php esc_attr_e( 'No credit card required to start trial, remove WP EasyCart PRO at any time.', 'wp-easycart' ); ?></div>
			<?php } ?>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
