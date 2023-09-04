<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type='text/css'>
			<!--
				.style20 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
				.style22 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
				.ec_option_label{font-family: Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; }
				.ec_option_name{font-family: Arial, Helvetica, sans-serif; font-size:11px; }
				.ec_admin_page_break{ page-break-before:always; }
			-->
		</style>
	</head>
	<body>
		<table width='539' border='0' align='center' cellpadding='0' cellspacing='0'>  
			<?php do_action( 'wp_easycart_email_receipt_top', $this->order_id, $is_admin ); ?>
			<tr>
				<td colspan='1' align='left' class='style22'>
					<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
				</td>
				<td colspan='3' align='right' class='style22'>
					<?php echo esc_attr( get_option( 'ec_option_invoice_address_info' ) ); ?>
				</td>
			</tr>
			<tr>
				<td colspan='4' align='left' class='style22'>    
					<p><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_invoice_line_1" ); ?></p>
					<p>
						<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=invoice&order_id=<?php echo esc_attr( $this->order_id ); ?><?php if( $this->guest_key != "" ){ ?>&ec_guest_key=<?php echo esc_attr( $this->guest_key ); } ?>" target="_blank"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_click_here" ); ?> <?php echo wp_easycart_language( )->get_text( "cart_success", "cart_invoice_pay_online" ); ?></a>
					</p>
				</td>
			</tr>
			<tr>
				<td width='269' align='left' bgcolor='#F3F1ED' class='style20'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_details_header_1" ); ?></td>
				<td width='80' align='center' bgcolor='#F3F1ED' class='style20'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_details_header_2" ); ?></td>
				<td width='91' align='center' bgcolor='#F3F1ED' class='style20'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_details_header_3" ); ?></td>
				<td align='center' bgcolor='#F3F1ED' class='style20'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_details_header_4" ); ?></td>
			</tr>
			<?php for( $i=0; $i < count( $this->cart->cart); $i++){ 
				$unit_price = $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->unit_price );
				$total_price = $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->total_price );
			?>
			<tr>
				<td width='269' class='style22'>	
					<table>
						<tr>
							<?php if( get_option( 'ec_option_show_image_on_receipt' ) ){ ?>
							<td>
								<?php
								if ( $this->cart->cart[$i]->is_deconetwork ) {
									$img_url = "https://" . get_option( 'ec_option_deconetwork_url' ) . $this->cart->cart[$i]->deconetwork_image_link;

								} else if ( substr( $this->cart->cart[$i]->image1_optionitem, 0, 7 ) == 'http://' || substr( $this->cart->cart[$i]->image1_optionitem, 0, 8 ) == 'https://' ) {
									$img_url = $this->cart->cart[$i]->image1_optionitem;

								} else if ( $this->cart->cart[$i]->image1_optionitem != "" && file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->cart->cart[$i]->image1_optionitem ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->cart->cart[$i]->image1_optionitem ) ) {
									$img_url = plugins_url( "wp-easycart-data/products/pics1/" . $this->cart->cart[$i]->image1_optionitem, EC_PLUGIN_DATA_DIRECTORY );

								} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->cart->cart[$i]->image1 ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->cart->cart[$i]->image1 ) ) {
									$img_url = plugins_url( "wp-easycart-data/products/pics1/" . $this->cart->cart[$i]->image1, EC_PLUGIN_DATA_DIRECTORY );

								} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
									$img_url = get_option( 'ec_option_product_image_default' );

								} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg" ) ) {
									$img_url = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

								} else {
									$img_url = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );
								}
								?>
								<div style="ec_lineitem_image"><img src="<?php echo esc_attr( str_replace( "https://", "http://", $img_url ) ); ?>" width="70" alt="<?php echo esc_attr( wp_easycart_language( )->convert_text( $this->cart->cart[$i]->title ) ); ?>" /></div>
							</td>
							<?php }?>
							<td>
								<table>
									<tr>
										<td class='style20'>
											<?php echo wp_easycart_language( )->convert_text( $this->cart->cart[$i]->title ); ?>
										</td>
									</tr>
									<tr>
										<td class="ec_option_name">
											<?php echo esc_attr( $this->cart->cart[$i]->orderdetails_model_number ); ?>
										</td>
									</tr>
									<?php if( $this->cart->cart[$i]->gift_card_message ){ ?>
									<tr>
										<td class='style22'>
											<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_message' ) . esc_attr( $this->cart->cart[$i]->gift_card_message ); ?>
										</td>
									</tr>
									<?php }?>
									<?php if( $this->cart->cart[$i]->gift_card_from_name ){ ?>
									<tr>
										<td class='style22'>
											<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_from' ) . esc_attr( $this->cart->cart[$i]->gift_card_from_name ); ?>
										</td>
									</tr>
									<?php }?>
									<?php if( $this->cart->cart[$i]->gift_card_to_name ){ ?>
									<tr>
										<td class='style22'>
											<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_to' ) . esc_attr( $this->cart->cart[$i]->gift_card_to_name ); ?>
										</td>
									</tr>
									<?php }?>
									<?php 
									do_action( 'wpeasycart_email_receipt_line_item', $this->cart->cart[$i]->model_number, $this->cart->cart[$i]->orderdetail_id );
									$advanced_option_allow_download = true;
									if ( ! $this->cart->cart[$i]->use_advanced_optionset || $this->cart->cart[$i]->use_both_option_types ) {
										if ( $this->cart->cart[$i]->optionitem1_name ) {
											echo "<tr><td><span class=\"ec_option_name\">" . esc_attr( $this->cart->cart[$i]->optionitem1_name );
											if ( $this->cart->cart[$i]->optionitem1_price < 0 ) {
												echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem1_price ) ) . ")";
											} else if( $this->cart->cart[$i]->optionitem1_price > 0 ) {
												echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem1_price ) ) . ")";
											}
											echo "</span></td></tr>";
										}
										if ( $this->cart->cart[$i]->optionitem2_name ) {
											echo "<tr><td><span class=\"ec_option_name\">" . esc_attr( $this->cart->cart[$i]->optionitem2_name );
											if ( $this->cart->cart[$i]->optionitem2_price < 0 ) {
												echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem2_price ) ) . ")";
											} else if( $this->cart->cart[$i]->optionitem2_price > 0 ) {
												echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem2_price ) ) . ")";
											}
											echo "</span></td></tr>";
										}
										if ( $this->cart->cart[$i]->optionitem3_name ) {
											echo "<tr><td><span class=\"ec_option_name\">" . esc_attr( $this->cart->cart[$i]->optionitem3_name );
											if ( $this->cart->cart[$i]->optionitem3_price < 0 ) {
												echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem3_price ) ) . ")";
											} else if( $this->cart->cart[$i]->optionitem3_price > 0 ) {
												echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem3_price ) ) . ")";
											}
											echo "</span></td></tr>";
										}
										if ( $this->cart->cart[$i]->optionitem4_name ) {
											echo "<tr><td><span class=\"ec_option_name\">" . esc_attr( $this->cart->cart[$i]->optionitem4_name );
											if ( $this->cart->cart[$i]->optionitem4_price < 0 ) {
												echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem4_price ) ) . ")";
											} else if( $this->cart->cart[$i]->optionitem4_price > 0 ) {
												echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem4_price ) ) . ")";
											}
											echo "</span></td></tr>";
										}
										if ( $this->cart->cart[$i]->optionitem5_name ) {
											echo "<tr><td><span class=\"ec_option_name\">" . esc_attr( $this->cart->cart[$i]->optionitem5_name );
											if ( $this->cart->cart[$i]->optionitem5_price < 0 ) {
												echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem5_price ) ) . ")";
											} else if( $this->cart->cart[$i]->optionitem5_price > 0 ) {
												echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$i]->optionitem5_price ) ) . ")";
											}
											echo "</span></td></tr>";
										}
									}// Close basic options
	
									if ( $this->cart->cart[$i]->use_advanced_optionset || $this->cart->cart[$i]->use_both_option_types ) {
										$advanced_options = $this->mysqli->get_order_options( $this->cart->cart[$i]->orderdetail_id );
										if ( $advanced_options && count( $advanced_options ) > 0 ) {
											foreach ( $advanced_options as $advanced_option ) {
												if ( !$advanced_option->optionitem_allow_download ) {
													$advanced_option_allow_download = false;
												}
												if ( $advanced_option->option_type == "file" ) {
													$file_split = explode( "/", $advanced_option->option_value );
													echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $file_split[1] ) . "</span></td></tr>";
												} else if( $advanced_option->option_type == "grid" ) {
													echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . wp_easycart_escape_html( $advanced_option->optionitem_name . " (" . $advanced_option->option_value . ")" ) . "</span></td></tr>";
												} else {
													echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $advanced_option->option_value ) . "</span></td></tr>";
												}
											}
										}
									}

									if( $this->cart->cart[$i]->is_giftcard || ( $this->cart->cart[$i]->is_download && $advanced_option_allow_download ) ){ ?>
									<tr>
										<td class='style22'>
										<?php 
											$account_page_id = get_option('ec_option_accountpage');
											$account_page = get_permalink( $account_page_id );
											if( substr_count( $account_page, '?' ) )
												$permalink_divider = "&";
											else
												$permalink_divider = "?";

											if( $this->cart->cart[$i]->is_giftcard ){
												echo '<a href="' . esc_url( get_site_url() . '?wpeasycarthook=print-giftcard&order_id=' . $this->order_id . '&orderdetail_id=' . $this->cart->cart[$i]->orderdetail_id . '&giftcard_id=' . $this->giftcard_id . ( ( $this->guest_key != '' ) ? '&ec_guest_key=' . $this->guest_key : '' ) ) . '" target="_blank">' . wp_easycart_language( )->get_text( "account_order_details", "account_orders_details_print_online" ) . '</a>';

											}else if( $this->cart->cart[$i]->is_download ){
												echo "<a href=\"" . esc_url( $account_page . $permalink_divider . "ec_page=order_details&order_id=" . $this->order_id ) . "\" target=\"_blank\">" . wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_download' ) . "</a>";

											}
										?>
										</td>
									</tr>
									<?php } ?>

									<?php if( $this->cart->cart[$i]->include_code && $this->is_approved ){ 
									global $wpdb;
									$codes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_code WHERE ec_code.orderdetail_id = %d", $this->cart->cart[$i]->orderdetail_id ) );
									$code_list = "";
									for( $code_index = 0; $code_index < count( $codes ); $code_index++ ){
										if( $code_index > 0 )
											$code_list .= ", ";
										$code_list .= $codes[$code_index]->code_val;
									}
									?>

									<tr>
										<td class='style22'>
											<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_your_codes' ); ?> <?php echo esc_attr( $code_list ); ?>
										</td>
									</tr>
									<?php }?>
									<?php do_action( 'wp_easycart_email_receipt_optionitems', $this->cart->cart[$i] ); ?>
								</table>
							</td>
						</tr>
					</table>
				</td>
				<td width='65' align='center' class='style22'><?php echo esc_attr( $this->cart->cart[$i]->quantity ); ?></td>
				<td width='90' align='center' class='style22'><?php echo esc_attr( apply_filters( 'wp_easycart_cart_item_unit_price_display', $unit_price, $this->cart->cart[$i]->product_id ) ); ?></td>
				<td width='90' align='center' class='style22'><?php echo esc_attr( $total_price ); ?></td>
			</tr>
			<?php }//end for loop ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center'>&nbsp;</td>
				<td width='91' align='center'>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_subtotal" ); ?></td>
				<td  align='center'  class='style22'><?php echo esc_attr( $subtotal ); ?></td>
			</tr>
			<?php if( $this->tip_total > 0 ){?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip' ); ?></td>
				<td  align='center'  class='style22'><?php echo esc_attr( $tip ); ?></td>
			</tr>
			<?php }?>
			<?php if( ( $tax_struct->is_tax_enabled( ) && !get_option( 'ec_option_enable_easy_canada_tax' ) ) || ( get_option( 'ec_option_enable_easy_canada_tax' ) && $tax > 0 ) ){ ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_tax" ); ?></td>
				<td align='center' class='style22'><?php echo esc_attr( $tax ); ?></td>
			</tr>
			<?php }?>
			<?php if( get_option( 'ec_option_use_shipping' ) && $this->shipping_total > 0 ){?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_shipping" ); ?></td>
				<td  align='center'  class='style22'><?php echo esc_attr( $shipping ); ?></td>
			</tr>
			<?php }?>
			<?php if( $this->discount_total != 0 ){ ?>
			<tr>
			  <td>&nbsp;</td>
			  <td align='center' class='style22'>&nbsp;</td>
			  <td align='center' class='style22'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_discount" ); ?></td>
			  <td  align='center'  class='style22'>-<?php echo esc_attr( $discount ); ?></td>
			</tr>
			<?php }?>
			<?php if( $has_duty ){ ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_duty" ); ?></td>
				<td align='center' class='style22'><?php echo esc_attr( $duty ); ?></td>
			</tr>
			<?php }?>
			<?php if( $tax_struct->is_vat_enabled( ) ){ ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_vat" ); ?><?php echo esc_attr( $vat_rate ); ?>%</td>
				<td align='center' class='style22'><?php echo esc_attr( $vat ); ?></td>
			</tr>
			<?php }?>
			<?php if( $gst > 0 ){ ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'>GST (<?php echo esc_attr( $gst_rate ); ?>%)</td>
				<td align='center' class='style22'><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $gst ) ); ?></td>
			</tr>
			<?php }?>
			<?php if( $pst > 0 ){ ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'>PST (<?php echo esc_attr( $pst_rate ); ?>%)</td>
				<td align='center' class='style22'><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $pst ) ); ?></td>
			</tr>
			<?php }?>
			<?php if( $hst > 0 ){ ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'>HST (<?php echo esc_attr( $hst_rate ); ?>%)</td>
				<td align='center' class='style22'><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $hst ) ); ?></td>
			</tr>
			<?php }?>
			<?php if ( count( $this->order_fees ) > 0 ) { ?>
			<?php foreach ( $this->order_fees as $order_fee ) { ?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><?php echo esc_attr( $order_fee->fee_label ); ?></td>
				<td align='center' class='style22'><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order_fee->fee_total ) ); ?></td>
			</tr>
			<?php }?>
			<?php }?>
			<tr>
				<td width='269'>&nbsp;</td>
				<td width='80' align='center' class='style22'>&nbsp;</td>
				<td width='91' align='center' class='style22'><strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_grand_total" ); ?></strong></td>
				<td align='center' class='style22'><strong><?php echo esc_attr( $total ); ?></strong></td>
			</tr>
		</table>
	</body>
</html>