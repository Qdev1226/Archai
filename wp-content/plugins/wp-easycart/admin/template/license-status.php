<div class="ec_admin_settings_panel ec_admin_details_panel">

	<div class="ec_admin_important_numbered_list">

		<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

			<div class="ec_admin_settings_label">
				<div class="dashicons-before dashicons-admin-network"></div>
				<span><?php esc_attr_e( 'WP EasyCart Status, License, &amp; Features', 'wp-easycart' ); ?></span>
				<a href="https://www.wpeasycart.com/professional-edition-ecommerce/" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Learn About PRO', 'wp-easycart' ); ?>
				</a>
				<a href="https://www.wpeasycart.com/wordpress-ecommerce-premium-edition/" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Learn About Premium', 'wp-easycart' ); ?>
				</a>
			</div>

				<?php
				$status = new wp_easycart_admin_store_status( );
				$license_data = false; $days_left = 0; $is_pro = false; $is_premium =false; $is_trial = false; $renew_url = 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/'; $upgrade_url = 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/';
				if( function_exists( 'wp_easycart_admin_license' ) ){
					$license_data = wp_easycart_admin_license( )->license_data;
					$license_info = get_option( 'wp_easycart_license_info' );
					$transaction_key = $license_info['transaction_key'];
					$test_now = time( );
					$test_expiration = strtotime( $license_data->support_end_date );
					$test_diff = $test_expiration - $test_now;
					$days_left = round( $test_diff / ( 60 * 60 * 24 ) );
					$days_left = ( $days_left < 0 ) ? 0 : $days_left; // No Negative
					$is_premium = ( $license_data->model_number == 'ec410' ) ? true : false;
					$is_pro = ( $license_data->model_number == 'ec400' ) ? true : false;
					$is_trial = $license_data->is_trial;
					if( $is_trial ){
						$renew_url = 'https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=' . $transaction_key;
						$upgrade_url = 'https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=' . $transaction_key . '&license_type=Premium';
					}else{
						$renew_url = ( $license_data->model_number == 'ec400' ) ? 'https://www.wpeasycart.com/products/wp-easycart-professional-support-upgrades/?transaction_key=' . $transaction_key : 'https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=' . $transaction_key;
						$upgrade_url = 'https://www.wpeasycart.com/products/wp-easycart-premium-support-extensions/?transaction_key=' . $transaction_key;
					}
				}
				?>

			<div class="ec_admin_settings_input wp_easycart_admin_no_padding">

				<?php if( !$license_data ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( __( 'FREE', 'wp-easycart' ), -1, __( 'FREE Version', 'wp-easycart' ), __( 'You are running the free version of WP EasyCart. 2&#37; fees apply for Stripe, Square, and PayPal. Upgrade today to remove all fees.', 'wp-easycart' ), 'admin.php?page=wp-easycart-registration&ec_trial=start', __( 'Try PRO Free', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left > 0 && $is_trial ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( ( ( $days_left > 14 ) ? 1 : round( $days_left / 14 * 100 ) ) . '%', ( $days_left / 14 ), __( 'Trial Status', 'wp-easycart' ), __( sprintf( 'You have %d days before your trial expires.', $days_left ), 'wp-easycart' ), 'https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/?transaction_key=' . $transaction_key . '&license_type=Premium', __( 'Upgrade Now', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left <= 0 && $is_trial ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( 'TRIAL', -1, __( 'TRIAL EXPIRED', 'wp-easycart' ), __( 'Your trial has expired, please upgrade to continue to use WP EasyCart Pro or Premium', 'wp-easycart' ), 'https://www.wpeasycart.com/wordpress-shopping-cart-pricing/', __( 'UPGRADE NOW', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left >= 100 && $is_pro ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( 'PRO', 1, __( 'Professional License Status', 'wp-easycart' ), __( sprintf( 'You have %d days before your professional license expires.', $days_left ), 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left >= 100 && $is_premium ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', 1, __( 'Premium License Status', 'wp-easycart' ), __( sprintf( 'You have %d days before your premium license expires.', $days_left ), 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left > 0 && $is_pro ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( 'PRO', ( $days_left / 100 ), __( 'Professional License Status', 'wp-easycart' ), __( sprintf( 'You have %d days before your professional license expires.', $days_left ), 'wp-easycart' ), $renew_url, __( 'Renew Now', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left > 0 && $is_premium ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', ( $days_left / 100 ), __( 'Premium License Status', 'wp-easycart' ), __( sprintf( 'You have %d days before your premium license expires.', $days_left ), 'wp-easycart' ), $renew_url, __( 'Renew Now', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left <= 0 && $is_pro ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( __( 'PRO', 'wp-easycart' ), -1, __( 'PRO LICENSE EXPIRED!', 'wp-easycart' ), __( 'Your professional license is expired and you are paying 2&#37; fees, renew today!', 'wp-easycart' ), $renew_url, __( 'RENEW NOW', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $days_left <= 0 && $is_premium ){ ?>
					<div class="ec_admin_status_circle_container large">
						<?php wp_easycart_admin( )->display_stat_circle( __( 'PREMIUM', 'wp-easycart' ), -1, __( 'PREMIUM LICENSE EXPIRED!', 'wp-easycart' ), __( 'Your premium license is expired and you are paying 2&#37; fees, renew today!', 'wp-easycart' ), $renew_url, __( 'RENEW NOW', 'wp-easycart' ) ); ?>
					</div>

				<?php } ?>

			</div>
			<div class="ec_admin_settings_input wp_easycart_admin_no_padding">

				<?php do_action( 'wpeasycart_store_status_bubble_list_start' ); ?>

				<?php if( $is_premium && $days_left > 0 ){ ?>
					<div class="ec_admin_status_circle_container">
						<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Premium Perks', 'wp-easycart' ), __( 'You are maxed out with all the features!', 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( $is_premium && $days_left <= 0 ){ ?>
					<div class="ec_admin_status_circle_container">
						<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Premium Perks', 'wp-easycart' ), __( 'You are maxed out with all the features!', 'wp-easycart' ), $renew_url, __( 'RENEW NOW', 'wp-easycart' ) ); ?>
					</div>

				<?php }else if( !$is_trial ){ ?>
					<div class="ec_admin_status_circle_container">
						<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Premium Perks', 'wp-easycart' ), __( 'You are not running Premium, upgrade for more perks!', 'wp-easycart' ), $upgrade_url, __( 'Upgrade License', 'wp-easycart' ) ); ?>
					</div>

				<?php }?>

				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Products', 'wp-easycart' ), __( 'You have unlimited products!', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'View Products', 'wp-easycart' ) ); ?>
				</div>

				<?php if( $status->ec_using_no_tax( ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '0%', 0, __( 'Taxes', 'wp-easycart' ), __( 'Taxes or VAT are not setup.', 'wp-easycart' ), 'admin.php?page=wp-easycart-settings&subpage=tax', __( 'View Setup', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Taxes', 'wp-easycart' ), __( 'You have vat or taxes setup and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-settings&subpage=tax', __( 'View Setup', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $status->ec_using_method_shipping( ) == false && $status->ec_using_live_shipping( ) == false && $status->ec_using_price_shipping( ) == false && $status->ec_using_weight_shipping( ) == false && $status->ec_using_quantity_shipping( ) == false && $status->ec_using_percentage_shipping( ) == false && $status->ec_using_fraktjakt_shipping( ) == false ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '0%', 0, __( 'Shipping', 'wp-easycart' ), __( 'Shipping is not setup or used.', 'wp-easycart' ), 'admin.php?page=wp-easycart-settings&subpage=shipping-rates', __( 'View Rates', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Shipping', 'wp-easycart' ), __( 'Shipping rates are setup for your store.', 'wp-easycart' ), 'admin.php?page=wp-easycart-settings&subpage=shipping-rates', __( 'View Rates', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $status->ec_no_payment_selected( ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '0%', 0, __( 'Payment', 'wp-easycart' ), __( 'Payments are not setup or used.', 'wp-easycart' ), 'admin.php?page=wp-easycart-settings&subpage=payment', __( 'View Setup', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Payment', 'wp-easycart' ), __( 'Payment is setup and running for your store!', 'wp-easycart' ), 'admin.php?page=wp-easycart-settings&subpage=payment', __( 'View Setup', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php /* COUPONS */ ?>
				<?php if( $days_left <= 0 && ( $is_pro || $is_premium ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Coupons', 'wp-easycart' ), __( 'Renew your license to regain access to coupons.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Coupons', 'wp-easycart' ), __( 'Get access to unlimited coupons with PRO.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $is_trial ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( ( ( $days_left > 14 ) ? 1 : round( $days_left / 14 * 100 ) ) . '%', ( $days_left / 14 ), __( 'Coupons', 'wp-easycart' ), __( 'Coupons are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-rates&subpage=coupons', __( 'View Coupons', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Coupons', 'wp-easycart' ), __( 'Coupons are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-rates&subpage=coupons', __( 'View Coupons', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php /* Promotions */ ?>
				<?php if( $days_left <= 0 && ( $is_pro || $is_premium ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Promotions', 'wp-easycart' ), __( 'Renew your license to access unlimited promotions!', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Promotions', 'wp-easycart' ), __( 'Get access to unlimited promotions with PRO.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $is_trial ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( ( ( $days_left > 14 ) ? 1 : round( $days_left / 14 * 100 ) ) . '%', ( $days_left / 14 ), __( 'Promotions', 'wp-easycart' ), __( 'Promotions are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-rates&subpage=promotions', __( 'View Promotions', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Promotions', 'wp-easycart' ), __( 'Promotions are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-rates&subpage=promotions', __( 'View Promotions', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php /* Subscriptions */ ?>
				<?php if( $days_left <= 0 && ( $is_pro || $is_premium ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Subscriptions', 'wp-easycart' ), __( 'Renew your license to create subscription products.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Subscriptions', 'wp-easycart' ), __( 'Upgrade now to create unlimited subscription products.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $is_trial ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( ( ( $days_left > 14 ) ? 1 : round( $days_left / 14 * 100 ) ) . '%', ( $days_left / 14 ), __( 'Subscriptions', 'wp-easycart' ), __( 'Subscription products are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'New Subscription', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Subscriptions', 'wp-easycart' ), __( 'Subscription products are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'New Subscription', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php /* Downloads */ ?>
				<?php if( $days_left <= 0 && ( $is_pro || $is_premium ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Downloads', 'wp-easycart' ), __( 'Renew your license to create download products.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Downloads', 'wp-easycart' ), __( 'Upgrade to create unlimited download products.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $is_trial ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( ( ( $days_left > 14 ) ? 1 : round( $days_left / 14 * 100 ) ) . '%', ( $days_left / 14 ), __( 'Downloads', 'wp-easycart' ), __( 'Download products are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'New Download', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Downloads', 'wp-easycart' ), __( 'Download products are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'New Download', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php /* Gift Cards */ ?>
				<?php if( $days_left <= 0 && ( $is_pro || $is_premium ) ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Gift Cards', 'wp-easycart' ), __( 'Renew your license to access gift cards.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PRO', -1, __( 'Gift Cards', 'wp-easycart' ), __( 'A Pro or Premium license is required to access gift cards.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $is_trial ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( ( ( $days_left > 14 ) ? 1 : round( $days_left / 14 * 100 ) ) . '%', ( $days_left / 14 ), __( 'Gift Cards', 'wp-easycart' ), __( 'Gift card products are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'New Gift Card', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Gift Cards', 'wp-easycart' ), __( 'Gift card products are available and running.', 'wp-easycart' ), 'admin.php?page=wp-easycart-products&subpage=products', __( 'New Gift Card', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $days_left <= 0 && $is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Quickbooks', 'wp-easycart' ), __( 'Your license is expired, renew to access.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 || !$is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Quickbooks', 'wp-easycart' ), __( 'A valid premium license is required for this extension.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Quickbooks', 'wp-easycart' ), __( 'Congrats! You have it, download from your account.', 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $days_left <= 0 && $is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'MailChimp', 'wp-easycart' ), __( 'Your license is expired, renew to access.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 || !$is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'MailChimp', 'wp-easycart' ), __( 'A valid premium license is required for this extension.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'MailChimp', 'wp-easycart' ), __( 'Congrats! You have it, download from your account.', 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $days_left <= 0 && $is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'ShipStation', 'wp-easycart' ), __( 'Your license is expired, renew to access.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 || !$is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'ShipStation', 'wp-easycart' ), __( 'A valid premium license is required for this extension.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'ShipStation', 'wp-easycart' ), __( 'Congrats! You have it, download from your account.', 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $days_left <= 0 && $is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Facebook & Instagram', 'wp-easycart' ), __( 'Your license is expired, renew to access.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 || !$is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Facebook & Instagram', 'wp-easycart' ), __( 'A valid premium license is required for this extension.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Facebook & Instagram', 'wp-easycart' ), __( 'Congrats! You have it, download from your account.', 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php if( $days_left <= 0 && $is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Mobile Apps', 'wp-easycart' ), __( 'Your license is expired, renew to access.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else if( $days_left <= 0 || !$is_premium ){ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( 'PREMIUM', -1, __( 'Mobile Apps', 'wp-easycart' ), __( 'A valid premium license is required to use our apps.', 'wp-easycart' ) ); ?>
				</div>

				<?php }else{ ?>
				<div class="ec_admin_status_circle_container">
					<?php wp_easycart_admin( )->display_stat_circle( '100%', 1, __( 'Mobile Apps', 'wp-easycart' ), __( 'Congrats! You have it, download from Apple or Google app stores.', 'wp-easycart' ), 'https://www.wpeasycart.com/my-account/', __( 'View Account', 'wp-easycart' ) ); ?>
				</div>

				<?php }?>

				<?php do_action( 'wpeasycart_store_status_bubble_list_end' ); ?>

			</div>

		</div>

	</div>

</div>