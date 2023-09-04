<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			.style20 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
			.style22 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight:normal; }
			.ec_option_label{font-family: Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; }
			.ec_option_name{font-family: Arial, Helvetica, sans-serif; font-size:11px; }
		</style>
	</head>
	<body>
		<table width="539" border="0" align="center">
			<tr>
				<td align="left" class="style22">
					<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
				</td>
			</tr>
			<tr>
				<td align="left" class="style22">
					<p><br><?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_dear' )?> <?php echo esc_attr( $order[0]->billing_first_name . " " . $order[0]->billing_last_name ); ?>,</p>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_subtitle1' )?> <b><?php echo esc_attr( $order[0]->order_id ); ?></b> <?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_subtitle2' )?><br>
					<?php if( $trackingnumber != '0' && $trackingnumber != 'Null' && $trackingnumber != 'NULL' && $trackingnumber != 'null' && $trackingnumber != NULL && $trackingnumber != '' ){ ?>
					<br><?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_description' )?></p>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_carrier' )?> <?php echo esc_attr( $shipcarrier ); ?><br><?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_tracking' )?> <?php 
					if ( 'fedex' == strtolower( $shipcarrier ) ) {
						echo '<a href="https://www.fedex.com/fedextrack/summary?trknbr=' . esc_attr( $trackingnumber ) . '" target="_blank">' . esc_attr( $trackingnumber ) . '</a>';
					} else if ( 'usps' == strtolower( $shipcarrier ) ) {
						echo '<a href="https://tools.usps.com/go/TrackConfirmAction?tRef=fullpage&tLc=3&text28777=&tLabels=' . esc_attr( $trackingnumber ) . '" target="_blank">' . esc_attr( $trackingnumber ) . '</a>';
					} else if ( 'ups' == strtolower( $shipcarrier ) ) {
						echo '<a href="https://www.ups.com/track?loc=en_US&tracknum=' . esc_attr( $trackingnumber ) . '" target="_blank">' . esc_attr( $trackingnumber ) . '</a>';
					} else {
						echo esc_attr( $trackingnumber );
					}
					?></p>
					<?php } ?>
					<?php do_action( 'wp_easycart_shipping_email_after_tracking', $order[0] ); ?>
					<?php if( get_option( 'ec_option_show_email_on_receipt' ) ){ ?><p><b><?php echo esc_attr( htmlspecialchars( $this->user_email, ENT_QUOTES ) ); ?></b></p><?php }?>
				</td>
			</tr>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td align="left" class="style20">
								<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
									<tbody>
										<tr>
											<td width="47%" bgcolor="#F3F1ED" class="style20"><?php echo wp_easycart_language( )->get_text( "ec_shipping_email", "shipping_billing_label" ); ?></td>
											<td width="3%">&nbsp;</td><td width="50%" bgcolor="#F3F1ED" class="style20"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo wp_easycart_language( )->get_text( "ec_shipping_email", "shipping_shipping_label" ); ?><?php }?></td>
										</tr>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order[0]->billing_last_name, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order[0]->shipping_last_name, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
										<?php if( $order[0]->billing_company_name != "" || ( get_option( 'ec_option_use_shipping' ) && $order[0]->shipping_company_name != "" ) ){ ?>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_company_name, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_company_name, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
										<?php }?>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_address_line_1, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_address_line_1, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
										<?php if( $order[0]->billing_address_line_2 != "" || ( $order[0]->shipping_address_line_2 != "" && get_option( 'ec_option_use_shipping' ) ) ){ ?>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_address_line_2, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){ ?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_address_line_2, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
										<?php }?>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $order[0]->billing_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order[0]->billing_zip, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $order[0]->shipping_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order[0]->shipping_zip, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_country_name, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_country_name, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
										<tr>
											<td align="left" class="style22"><?php echo esc_attr( htmlspecialchars( $order[0]->billing_phone, ENT_QUOTES ) ); ?></td>
											<td>&nbsp;</td>
											<td align="left" class="style22"><?php if( get_option( 'ec_option_use_shipping' ) ){?><?php echo esc_attr( htmlspecialchars( $order[0]->shipping_phone, ENT_QUOTES ) ); ?><?php }?></td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<?php if ( $order[0]->vat_registration_number != "" ) { ?>
						<tr>
							<td align="left" class="style22"><b><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?>:</b> <?php echo esc_attr( htmlspecialchars( $order[0]->vat_registration_number, ENT_QUOTES ) ); ?></td>
						</tr>
						<?php }?>
					</table>
				</td>
			</tr>

			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269" align="left">&nbsp;</td>
								<td width="80" align="center">&nbsp;</td>
								<td width="91" align="center">&nbsp;</td>
								<td align="center">&nbsp;</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269" align="left" bgcolor="#F3F1ED" class="style20"><?php echo wp_easycart_language( )->get_text( "ec_shipping_email", "shipping_product" ); ?></td>
								<td width="80" align="center" bgcolor="#F3F1ED" class="style20"><?php echo wp_easycart_language( )->get_text( "ec_shipping_email", "shipping_quantity" ); ?></td>
								<td width="91" align="right" bgcolor="#F3F1ED" class="style20"><?php echo wp_easycart_language( )->get_text( "ec_shipping_email", "shipping_unit_price" ); ?></td>
								<td align="right" bgcolor="#F3F1ED" class="style20"><?php echo wp_easycart_language( )->get_text( "ec_shipping_email", "shipping_total_price" ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

			<?php for( $i=0; $i < count( $orderdetails); $i++){ 
				$unit_price = $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->unit_price );
				$total_price = $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->total_price );
			?>

			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269" class="style22">
									<table>
										<tr>
											<?php if( get_option( 'ec_option_show_image_on_receipt' ) ){ ?>
											<td>
												<?php
												if ( $orderdetails[$i]->is_deconetwork ) {
													$img_url = "https://" . get_option( 'ec_option_deconetwork_url' ) . $orderdetails[$i]->deconetwork_image_link;

												} else if ( substr( $orderdetails[$i]->image1, 0, 7 ) == 'http://' || substr( $orderdetails[$i]->image1, 0, 8 ) == 'https://' ) {
													$img_url = $orderdetails[$i]->image1;

												} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $orderdetails[$i]->image1 ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $orderdetails[$i]->image1 ) ) {
													$img_url = plugins_url( "wp-easycart-data/products/pics1/" . $orderdetails[$i]->image1, EC_PLUGIN_DATA_DIRECTORY );

												} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
													$img_url = get_option( 'ec_option_product_image_default' );

												} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg" ) ) {
													$img_url = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

												} else {
													$img_url = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );
												}
												?>
												<img src="<?php echo esc_attr( str_replace( "https://", "http://", $img_url ) ); ?>" width="70" alt="<?php echo esc_attr( $orderdetails[$i]->title ); ?>" />
											</td>
											<?php }?>
											<td>
												<table>
													<tr>
														<td class="style20">
															<?php echo esc_attr( $orderdetails[$i]->title ); ?>
														</td>
													</tr>

													<tr>
														<td class="ec_option_name">
															<?php echo esc_attr( $orderdetails[$i]->model_number ); ?>
														</td>
													</tr>

													<?php if( $orderdetails[$i]->gift_card_message ){ ?>
													<tr>
														<td class="style22">
															<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_message' ) . esc_attr( htmlspecialchars( $orderdetails[$i]->gift_card_message, ENT_QUOTES ) ); ?>
														</td>
													</tr>
													<?php }?>

													<?php if( $orderdetails[$i]->gift_card_from_name ){ ?>
													<tr>
														<td class="style22">
															<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_from' ) . esc_attr( htmlspecialchars( $orderdetails[$i]->gift_card_from_name, ENT_QUOTES ) ); ?>
														</td>
													</tr>
													<?php }?>

													<?php if( $orderdetails[$i]->gift_card_to_name ){ ?>
													<tr>
														<td class="style22">
															<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_to' ) . esc_attr( htmlspecialchars( $orderdetails[$i]->gift_card_to_name, ENT_QUOTES ) ); ?>
														</td>
													</tr>
													<?php }?>

													<?php
													do_action( 'wpeasycart_email_receipt_line_item', $orderdetails[$i]->model_number, $orderdetails[$i]->orderdetail_id );

													$advanced_option_allow_download = true;
													$db = new ec_db( );
													if ( $orderdetails[$i]->use_advanced_optionset ) {
														$advanced_options = $db->get_order_options( $orderdetails[$i]->orderdetail_id );
														foreach ( $advanced_options as $advanced_option ) {
															if ( ! $advanced_option->optionitem_allow_download ) {
																$advanced_option_allow_download = false;
															}
															if ( $advanced_option->option_type == "file" ) {
																$file_split = explode( "/", $advanced_option->option_value );
																echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $file_split[1] ) . "</span></td></tr>";
															} else if ( $advanced_option->option_type == "grid" ) {
																echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $advanced_option->optionitem_name . " (" . $advanced_option->option_value . ")" ) . "</span></td></tr>";
															} else {
																echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $advanced_option->option_value ) . "</span></td></tr>";
															}
														}

													}else{
														if ( $orderdetails[$i]->optionitem_name_1 ) {
															echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $orderdetails[$i]->optionitem_name_1 );
															if ( $orderdetails[$i]->optionitem_price_1 < 0 ) {
																echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_1 ) ) . ")";
															} else if ( $orderdetails[$i]->optionitem_price_1 > 0 ) {
																echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_1 ) ) . ")";
															}
															echo "</span></td></tr>";
														}

														if ( $orderdetails[$i]->optionitem_name_2 ) {
															echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $orderdetails[$i]->optionitem_name_2 );
															if ( $orderdetails[$i]->optionitem_price_2 < 0 ) {
																echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_2 ) ) . ")";
															} else if ( $orderdetails[$i]->optionitem_price_2 > 0 ) {
																echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_2 ) ) . ")";
															}
															echo "</span></td></tr>";
														}

														if ( $orderdetails[$i]->optionitem_name_3 ) {
															echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $orderdetails[$i]->optionitem_name_3 );
															if ( $orderdetails[$i]->optionitem_price_3 < 0 ) {
																echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_3 ) ) . ")";
															} else if ( $orderdetails[$i]->optionitem_price_3 > 0 ) {
																echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_3 ) ) . ")";
															}
															echo "</span></td></tr>";
														}

														if ( $orderdetails[$i]->optionitem_name_4 ) {
															echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $orderdetails[$i]->optionitem_name_4 );
															if ( $orderdetails[$i]->optionitem_price_4 < 0 ) {
																echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_4 ) ) . ")";
															} else if( $orderdetails[$i]->optionitem_price_4 > 0 ) {
																echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_4 ) ) . ")";
															}
															echo "</span></td></tr>";
														}

														if ( $orderdetails[$i]->optionitem_name_5 ) {
															echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $orderdetails[$i]->optionitem_name_5 );
															if ( $orderdetails[$i]->optionitem_price_5 < 0 ) {
																echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_5 ) ) . ")";
															} else if( $orderdetails[$i]->optionitem_price_5 > 0 ) {
																echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $orderdetails[$i]->optionitem_price_5 ) ) . ")";
															}
															echo "</span></td></tr>";
														}
													}// Close basic options
													?>

													<?php if( $orderdetails[$i]->is_giftcard || ( $orderdetails[$i]->is_download && $advanced_option_allow_download ) ){ ?>
													<tr>
														<td class="style22">
														<?php 
															$account_page_id = get_option('ec_option_accountpage');
															$account_page = get_permalink( $account_page_id );
															if( substr_count( $account_page, '?' ) )
																$permalink_divider = "&";
															else
																$permalink_divider = "?";

															if( $orderdetails[$i]->is_giftcard ){
																echo "<a href=\"" . esc_attr( $account_page . $permalink_divider . "ec_page=order_details&order_id=" . $this->order_id ) . "\" target=\"_blank\">" . wp_easycart_language( )->get_text( "account_order_details", "account_orders_details_print_online" ) . "</a>";

															}else if( $orderdetails[$i]->is_download ){
																echo "<a href=\"" . esc_attr( $account_page . $permalink_divider . "ec_page=order_details&order_id=" . $this->order_id ) . "\" target=\"_blank\">" . wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_download' ) . "</a>";

															}
														?>
														</td>
													</tr>
													<?php } ?>

													<?php if( $orderdetails[$i]->include_code && $this->is_approved ){ 
													global $wpdb;
													$codes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_code WHERE ec_code.orderdetail_id = %d", $orderdetails[$i]->orderdetail_id ) );
													$code_list = "";
													for( $code_index = 0; $code_index < count( $codes ); $code_index++ ){
														if( $code_index > 0 )
															$code_list .= ", ";
														$code_list .= $codes[$code_index]->code_val;
													}
													?>
													<tr>
														<td class="style22">
															<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_your_codes' ); ?> <?php echo esc_attr( $code_list ); ?>
														</td>
													</tr>
													<?php }?>
												</table>
											</td>
										</tr>
									</table>
								</td>
								<td width="65" align="center" class="style22"><?php echo esc_attr( $orderdetails[$i]->quantity ); ?></td>
								<td width="90" align="right" class="style22"><?php echo esc_attr( $unit_price ); ?></td>
								<td width="90" align="right" class="style22"><?php echo esc_attr( $total_price ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }//end for loop ?>

			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center">&nbsp;</td>
								<td width="91" align="center">&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_subtotal" ); ?></td>
								<td  align="right"  class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->sub_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

			<?php if( $order[0]->tip_total > 0 ){?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip' ); ?></td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->tip_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->tax_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_tax" ); ?></td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->tax_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( get_option( 'ec_option_use_shipping' ) ){?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_shipping" ); ?></td>
								<td  align="right"  class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->shipping_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->discount_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td>&nbsp;</td>
								<td align="center" class="style22">&nbsp;</td>
								<td align="right" class="style22"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_discount" ); ?></td>
								<td  align="right"  class="style22">-<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->discount_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->duty_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_duty" ); ?></td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->duty_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->vat_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_vat" ); ?><?php echo esc_attr( number_format( $order[0]->vat_rate, 0 ) ); ?>%</td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->vat_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->gst_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22">GST (<?php echo esc_attr( $order[0]->gst_rate ); ?>%)</td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->gst_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->pst_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22">PST (<?php echo esc_attr( $order[0]->pst_rate ); ?>%)</td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->pst_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<?php if( $order[0]->hst_total > 0 ){ ?>
			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22">HST (<?php echo esc_attr( $order[0]->hst_rate ); ?>%)</td>
								<td align="right" class="style22"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->hst_total ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<?php }?>

			<tr>
				<td align="left" class="style20">
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<td width="269">&nbsp;</td>
								<td width="80" align="center" class="style22">&nbsp;</td>
								<td width="91" align="right" class="style22"><b><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_payment_complete_order_totals_grand_total" ); ?></b></td>
								<td align="right" class="style22"><b><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order[0]->grand_total ) ); ?></b></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<td class="style22">
					<p><br>
					<?php if( get_option( 'ec_option_user_order_notes' ) ){ ?>
						<hr />
						<h4><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?></h4>
						<p><?php echo esc_attr( nl2br( htmlspecialchars( $order[0]->order_customer_notes, ENT_QUOTES ) ) ); ?></p>
						<br>
						<hr />
					<?php }?>
					<?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_final_note1' )?><br><br><?php echo wp_easycart_language( )->get_text( 'ec_shipping_email', 'shipping_final_note2' )?></p>
					<p>&nbsp;</p>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table>
	</body>
</html>