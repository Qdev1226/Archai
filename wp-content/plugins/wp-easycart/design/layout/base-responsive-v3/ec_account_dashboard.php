<?php if ( isset( $_GET['account_success'] ) && 'login_success' == $_GET['account_success'] ) {
	do_action( 'wp_easycart_login_success_account' );
} ?>
<section class="ec_account_page" id="ec_account_dashboard">
	<div class="ec_account_mobile">
		<div class="ec_cart_header ec_top"><?php echo wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_title' )?></div>
		<?php do_action( 'wpeasycart_account_links' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_billing_information_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_billing_information' ) ); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_after_billing' ); ?>
		<?php if( get_option( 'ec_option_use_shipping' ) ){ ?>
		<div class="ec_cart_input_row">
			<?php $this->display_shipping_information_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_shipping_information' ) ); ?>
		</div>
		<?php }?>
		<?php do_action( 'wpeasycart_account_links_after_shipping' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_personal_information_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_basic_inforamtion' ) ); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_after_personal' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_password_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_password' ) ); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_after_password' ); ?>
		<?php if( $this->using_subscriptions( ) ){ ?>
		<div class="ec_cart_input_row">
			<?php $this->display_subscriptions_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_subscriptions' )); ?>
		</div>
		<?php }?>
		<?php do_action( 'wpeasycart_account_links_after_subscriptions' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_logout_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_sign_out' )); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_end' ); ?>
	</div>

	<div class="ec_account_left">
		<?php do_action( 'wpeasycart_dashboard_top' ); ?>

		<div class="ec_cart_header ec_top ec_cart_header_no_border"><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_recent_orders_title' )?></div>

		<?php if ( $this->orders->num_orders > 0 ) {
			$is_first_order = true;
			$max_orders = 5;
			$current_order = 0;

			for ( $current_order; $current_order < count( $this->orders->orders ) && $current_order < $max_orders; $current_order++ ) {
				$order = $this->orders->orders[$current_order]; 
				$order_details = $this->mysqli->get_order_details( $order->order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
		?>

		<div class="ec_account_order_header_row<?php if( !$is_first_order ){?> ec_account_order_header_row_not_first <?php } $is_first_order = false; ?>">
			<div class="ec_account_order_header_column_left ec_account_order_header_column_left_div1">
				<span><?php $order->display_order_status( ); //wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_order_placed' )?></span>
				<span><?php $order->display_order_date( ); ?></span>
			</div>
			<div class="ec_account_order_header_column_left ec_account_order_header_column_left_div2">
				<span><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_order_total' )?></span>
				<span><?php $order->display_grand_total( ); ?></span>
			</div>
			<div class="ec_account_order_header_column_left ec_account_order_header_column_left_div3">
				<span><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_order_ship_to' )?></span>
				<div>
					<a href="#" class="ec_account_dashboard_order_info_link"><?php $order->display_order_shipping_first_name( ); ?> <?php $order->display_order_shipping_last_name( ); ?>
						<span><strong><?php $order->display_order_shipping_first_name( ); ?> <?php $order->display_order_shipping_last_name( ); ?></strong><br />
						<?php if( $order->shipping_company_name != "" ){ ?>
						<?php echo esc_attr( htmlspecialchars( $order->shipping_company_name, ENT_QUOTES ) ); ?><br />
						<?php }?>
						<?php $order->display_order_shipping_address_line_1( ); ?><br />
						<?php if( $order->shipping_address_line_2 != "" ){ ?>
						<?php echo esc_attr( htmlspecialchars( $order->shipping_address_line_2, ENT_QUOTES ) ); ?><br />
						<?php }?>
						<?php $order->display_order_shipping_city( ); ?>, <?php $order->display_order_shipping_state( ); ?> <?php $order->display_order_shipping_zip( ); ?><br />
						<?php $order->display_order_shipping_country( ); ?><?php if( $order->shipping_phone != "" ){ ?><br />
						<?php echo wp_easycart_language( )->get_text( 'account_billing_information', 'account_billing_information_phone' )?>: <?php $order->display_order_shipping_phone( ); ?><?php }?></span>
					</a>
				</div>
			</div>
			<div class="ec_account_order_header_column_left ec_account_order_header_column_left_div4">
				<span><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_order_order_label' )?> <?php echo esc_attr( $order->order_id ); ?></span>
				<div><a href="<?php echo esc_attr( $this->account_page . $this->permalink_divider ); ?>ec_page=order_details&order_id=<?php echo esc_attr( $order->order_id ); ?>"><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_order_view_details' )?></a> | <a href="<?php echo esc_attr( $this->account_page . $this->permalink_divider ); ?>ec_page=print_receipt&order_id=<?php echo esc_attr( $order->order_id ); ?>" target="_blank"><?php echo wp_easycart_language( )->get_text( 'cart_success', 'cart_success_print_receipt_text' )?></a></div>
			</div>
		</div>

		<?php foreach ( $order_details as $detail ) {
			$order_item = new ec_orderdetail( $detail ); ?>
		<div class="ec_account_order_item_row">
			<div class="ec_account_order_item_content">
				<div class="ec_account_order_item_image">
					<?php $order_item->display_image( "small" ); ?>
				</div>
				<div class="ec_account_order_item_details">
					<span class="ec_account_order_item_title"><?php $order_item->display_title(); ?><?php if( $detail->quantity > 1 ){ ?> (<?php $order_item->display_quantity(); ?>)<?php }?></span>
					<?php do_action( 'wpeasycart_dashboard_recent_order_item', $order_item ); ?>
					<?php $advanced_optionitem_download_allowed = true;
					if ( $order_item->use_advanced_optionset ) {
						$advanced_options = $this->mysqli->get_order_options( $order_item->orderdetail_id );
						foreach ( $advanced_options as $advanced_option ) {
							if ( ! $advanced_option->optionitem_allow_download ) {
								$advanced_optionitem_download_allowed = false;
							}
							if ( $advanced_option->option_type == "file" ) {
								$file_split = explode( "/", $advanced_option->option_value );
								echo "<span>" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $file_split[1] ) . "</span>";
							} else if( $advanced_option->option_type == "grid" ) {
								echo "<span>" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . wp_easycart_escape_html( $advanced_option->optionitem_name . " (" . $advanced_option->option_value . ")" ) . "</span>";
							} else {
								echo "<span>" . wp_easycart_escape_html( $advanced_option->option_label ) . ": " . esc_attr( $advanced_option->option_value ) . "</span>";
							}
						}
					} else {
						if ( $order_item->has_option1() ) {
							echo "<span>"; $order_item->display_option1( ); 
							if( $order_item->has_option1_price( ) ){ 
								echo "("; $order_item->display_option1_price( ); echo ")";
							}
							echo "</span>";
						}
						if ( $order_item->has_option2() ) {
							echo "<span>"; $order_item->display_option2( ); 
							if( $order_item->has_option2_price( ) ){ 
								echo "("; $order_item->display_option2_price( ); echo ")";
							}
							echo "</span>";
						}
						if ( $order_item->has_option3() ) {
							echo "<span>"; $order_item->display_option3( ); 
							if( $order_item->has_option3_price( ) ){ 
								echo "("; $order_item->display_option3_price( ); echo ")";
							}
							echo "</span>";
						}
						if ( $order_item->has_option4() ) {
							echo "<span>"; $order_item->display_option4( ); 
							if( $order_item->has_option4_price( ) ){ 
								echo "("; $order_item->display_option4_price( ); echo ")";
							}
							echo "</span>";
						}
						if ( $order_item->has_option5() ) {
							echo "<span>"; $order_item->display_option5( ); 
							if( $order_item->has_option5_price( ) ){ 
								echo "("; $order_item->display_option5_price( ); echo ")";
							}
							echo "</span>";
						}
					}
					if ( $order_item->has_gift_card_message() ) {
						echo "<span>";
						$order_item->display_gift_card_message( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_message' ) ); 
						echo "</span>";
					}
					if( $order_item->has_gift_card_from_name() ) {
						echo "<span>";
						$order_item->display_gift_card_from_name( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_from' ) );
						echo "</span>";
					}
					if ( $order_item->has_gift_card_to_name() ) {
						echo "<span>";
						$order_item->display_gift_card_to_name( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_to' ) );
						echo "</span>";
					}
					if ( $order_item->has_print_gift_card_link() && $order->is_approved ) {
						echo "<span>";
						$order_item->display_print_online_link( wp_easycart_language( )->get_text( "account_order_details", "account_orders_details_print_online" ) ); 
						echo "</span>";
					}
					if ( $order_item->include_code && $order->is_approved ) {
						global $wpdb;
						$codes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_code WHERE ec_code.orderdetail_id = %d", $this->cart->cart[$i]->orderdetail_id ) );
						$code_list = "";
						for ( $code_index = 0; $code_index < count( $codes ); $code_index++ ) {
							if( $code_index > 0 )
								$code_list .= ", ";
							$code_list .= $codes[$code_index]->code_val;
						}
						echo "<span>";
						echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_your_codes' ); 
						echo esc_attr( $code_list );
						echo "</span>";
					 }

					if ( $order->has_membership_page() ) {
						echo "<span><a href=\"" . esc_attr( $order->get_membership_page_link( ) ) . "\">" . wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_line_5" ) . "</a></span>";
					 }
					 ?>
					 <span class="ec_account_order_item_price"><?php $order_item->display_unit_price(); ?></span>
				</div>
			</div>
			<?php $product_link = $order_item->get_product_link(); ?>
			<div class="ec_account_order_item_buttons<?php if( $product_link && $order_item->has_download_link( ) && $order->is_approved && $advanced_optionitem_download_allowed ){ echo " ec_account_order_item_two_buttons"; } ?>">

				<?php if ( $order_item->has_download_link( ) && $order->is_approved && $advanced_optionitem_download_allowed ) {
					 echo "<span>";
					 $order_item->display_download_link( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_download' ) );
					 echo "</span>";
				}
				if ( $product_link ) { ?>
				<span class="ec_account_order_item_buy_button"><a href="<?php echo esc_attr( $product_link ); ?>"><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_order_buy_item_again' )?></a></span>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<?php } ?>

		<div class="ec_account_dashboard_row_divider">
			<?php $this->display_orders_link( wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_all_orders_linke' ) ); ?>
		</div>

		<?php }else{ echo wp_easycart_language( )->get_text( "account_dashboard", "account_dashboard_recent_orders_none" ); }?>
		<?php if( count( $this->downloads ) > 0 ){ ?>
		<div class="ec_cart_header">Your Downloads</div>
		<?php 
		global $wpdb;
		foreach ( $this->downloads as $download ) {
		?>
		<div class="ec_account_download_line">
			<div class="ec_account_download_line_title">
				<a href="<?php echo esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=order_details&amp;order_id=" . esc_attr( $download->order_id ) . "&amp;orderdetail_id=" . esc_attr( $download->orderdetail_id ) . "&amp;download_id=" . esc_attr( $download->download_id ); ?>" target="_blank" onclick="update_download_count( '<?php echo esc_attr( $order_item->orderdetail_id ); ?>' );"><?php echo esc_attr( $download->title ); ?></a>
			</div>
			<?php if( isset( $download->maximum_downloads_allowed ) && $download->maximum_downloads_allowed > 0 ){ ?>
			<div class="ec_account_download_line_limit">
				<?php echo "<span id=\"ec_download_count_" . esc_attr( $download->orderdetail_id ) . "\">" . esc_attr( $download->download_count ) . "</span>" . "/" . "<span id=\"ec_download_count_max_" . esc_attr( $download->orderdetail_id ) . "\">" . esc_attr( $download->maximum_downloads_allowed ) . "</span> " . wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_downloads_used' ); ?>
			</div>
			<?php }?>
			<?php if( isset( $download->download_timelimit_seconds ) && $download->download_timelimit_seconds > 0 ){
				$date = new DateTime();
				$seconds_remaining = $this->download_timelimit_seconds - $this->timecheck;
				if ( $seconds_remaining < 0 ) {
					$seconds_remaining = ( $seconds_remaining * -1 );
					$date->sub( new DateInterval('PT' . $seconds_remaining . 'S' ) );
				} else {
					$date->add( new DateInterval('PT' . $seconds_remaining . 'S' ) );
				}
				$date_format = $date->format( "d M Y" );
			?>
			<div class="ec_account_download_line_time_limit">
				<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_downloads_expire_time' ) . " " . esc_attr( $date_format ); ?>
			</div>
			<?php } ?>

		</div>
		<?php } ?>
		<?php } ?>

		<div class="ec_cart_header"><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_email_title' )?></div>

		<div class="ec_cart_input_row"><?php $GLOBALS['ec_user']->display_email(); ?></div>

		<div class="ec_cart_input_row">
			<?php $this->display_personal_information_link( wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_email_edit_link' ) ); ?>
			<?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_email_note' )?></div>
		<div class="ec_cart_header"><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_billing_title' )?></div>

		<?php if( $GLOBALS['ec_user']->billing->first_name || $GLOBALS['ec_user']->billing->last_name ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->billing->display_first_name(); ?>
			<?php $GLOBALS['ec_user']->billing->display_last_name(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->billing->company_name ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->billing->display_company_name(); ?>
		</div>
		<?php } ?>

		<?php if( get_option( 'ec_option_collect_vat_registration_number' ) && $GLOBALS['ec_user']->vat_registration_number ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( $GLOBALS['ec_user']->vat_registration_number ); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->billing->address_line_1 ){ ?>
		<div class="ec_cart_input_row">
		  <?php $GLOBALS['ec_user']->billing->display_address_line_1(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->billing->address_line_2 != "" ){ ?>
		<div class="ec_cart_input_row">
		  <?php $GLOBALS['ec_user']->billing->display_address_line_2( ); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->billing->city || $GLOBALS['ec_user']->billing->state || $GLOBALS['ec_user']->billing->zip ){ ?>
		<div class="ec_cart_input_row">
		  <?php $GLOBALS['ec_user']->billing->display_city(); ?>, <?php $GLOBALS['ec_user']->billing->display_state(); ?> <?php $GLOBALS['ec_user']->billing->display_zip(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->billing->country ){ ?>
		<div class="ec_cart_input_row">
		  <?php $GLOBALS['ec_user']->billing->display_country(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->billing->phone ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->billing->display_phone(); ?>
		</div>
		<?php } ?>

		<div class="ec_cart_input_row">
			<?php $this->display_billing_information_link( wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_billing_link' ) ); ?>
		</div>

		<?php if( get_option( 'ec_option_use_shipping' ) ){ ?>
		<div class="ec_cart_header"><?php echo wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_shipping_title' )?></div>

		<?php if( $GLOBALS['ec_user']->shipping->first_name || $GLOBALS['ec_user']->shipping->last_name ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_first_name(); ?>
			<?php $GLOBALS['ec_user']->shipping->display_last_name(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->shipping->company_name ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_company_name(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->shipping->address_line_1 ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_address_line_1(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->shipping->address_line_2 != "" ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_address_line_2( ); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->shipping->city || $GLOBALS['ec_user']->shipping->state || $GLOBALS['ec_user']->shipping->zip ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_city(); ?>, <?php $GLOBALS['ec_user']->shipping->display_state(); ?> <?php $GLOBALS['ec_user']->shipping->display_zip(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->shipping->country ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_country(); ?>
		</div>
		<?php } ?>

		<?php if( $GLOBALS['ec_user']->shipping->phone ){ ?>
		<div class="ec_cart_input_row">
			<?php $GLOBALS['ec_user']->shipping->display_phone(); ?>
		</div>
		<?php } ?>

		<div class="ec_cart_input_row">
			<?php $this->display_shipping_information_link( wp_easycart_language( )->get_text( 'account_dashboard', 'account_dashboard_shipping_link' )); ?>
		</div>
		<?php }?>
	</div>

	<div class="ec_account_right">
		<div class="ec_cart_header ec_top"><?php echo wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_title' )?></div>
		<?php do_action( 'wpeasycart_account_links' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_billing_information_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_billing_information' ) ); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_after_billing' ); ?>
		<?php if( get_option( 'ec_option_use_shipping' ) ){ ?>
		<div class="ec_cart_input_row">
			<?php $this->display_shipping_information_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_shipping_information' ) ); ?>
		</div>
		<?php }?>
		<?php do_action( 'wpeasycart_account_links_after_shipping' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_personal_information_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_basic_inforamtion' ) ); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_after_personal' ); ?>
		<div class="ec_cart_input_row">
			<?php $this->display_password_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_password' ) ); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_after_password' ); ?>
		<?php if( $this->using_subscriptions( ) ){ ?>
		<div class="ec_cart_input_row">
		<?php $this->display_subscriptions_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_subscriptions' )); ?>
		</div>
		<?php }?>
		<?php do_action( 'wpeasycart_account_links_after_subscriptions' ); ?>
		<div class="ec_cart_input_row">
		<?php $this->display_logout_link( wp_easycart_language( )->get_text( 'account_navigation', 'account_navigation_sign_out' )); ?>
		</div>
		<?php do_action( 'wpeasycart_account_links_end' ); ?>
	</div>
	<div style="clear:both;"></div>
	<div id="ec_current_media_size"></div>
</section>
