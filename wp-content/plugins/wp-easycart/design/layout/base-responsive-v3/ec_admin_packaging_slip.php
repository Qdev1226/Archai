<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo sprintf( esc_attr__( 'Packing Slip for Order %d', 'wp-easycart' ), esc_attr( $order_id ) ); ?></title>
		<style type='text/css'>
			<!--
			.style20 {
				font-family: Arial, Helvetica, sans-serif;
				font-weight: bold;
				font-size: 12px;
				line-height:18px;
			}
			.style22 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
				line-height:18px;
			}
			.style24 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
				line-height:18px;
				color:#F00;
				font-weight: bold;
			}
			.ec_option_label {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;
				line-height:16px;
				font-weight: bold;
			}
			.ec_option_name {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 11px;
				line-height:16px;
			}
			.ec_admin_page_break{ page-break-before:always; }
			-->
		</style>
	</head>
	<body>
		<table width='539' border='0' align='center'>
			<?php if ( get_option( 'ec_option_packing_slip_show_logo' ) ) { ?>
			<tr>
				<td colspan='4' align='left' class='style22'>
					<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
				</td>
			</tr>
			<?php }?>
			<?php if ( get_option( 'ec_option_packing_slip_show_order_id' ) || get_option( 'ec_option_packing_slip_show_order_date' ) ) { ?>
			<tr>
				<td align='left' class='style22'><?php if ( get_option( 'ec_option_packing_slip_show_order_id' ) ) { ?><strong><br><?php echo wp_easycart_language()->get_text( "cart_success", "cart_success_order_number_is" ) . ' ' . esc_attr( $order->order_id ); ?></strong><br><br><?php }?></td>
				<td colspan="3" align='right' class='style22'><?php if ( get_option( 'ec_option_packing_slip_show_order_date' ) ) { ?><strong><?php $date = date_create($order->order_date); echo esc_attr( date_format($date , 'l - F jS, Y') ); ?></strong><?php }?></td>
			</tr>
			<?php }?>
			<tr>
				<td colspan='4' align='left' class='style20'>
					<?php if ( get_option( 'ec_option_packing_slip_show_billing' ) && get_option( 'ec_option_packing_slip_show_shipping' ) ) { ?>
					<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='47%' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_billing_label" ); ?></td>
							<td width='3%'>&nbsp;</td>
							<td width='50%' bgcolor='#DFDFDF' class='style20'><?php if ( get_option( 'ec_option_use_shipping' ) ) {?>
							<?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_shipping_label" ); ?>
							<?php }?></td>
						</tr>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $order->billing_last_name, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td>
								<span class='style22'><?php if ( get_option( 'ec_option_use_shipping' ) ) {
									echo esc_attr( htmlspecialchars( $order->shipping_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $order->shipping_last_name, ENT_QUOTES ) );
								} ?></span>
							</td>
						</tr>
						<?php if ( $order->billing_company_name !=  '' || ( get_option( 'ec_option_use_shipping' ) && $order->shipping_company_name !=  '' ) ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_company_name, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td><span class='style22'>
							<?php if ( get_option( 'ec_option_use_shipping' ) ) {
								echo esc_attr( htmlspecialchars( $order->shipping_company_name, ENT_QUOTES ) );
							} ?>
							</span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_address_line_1, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td><span class='style22'>
							<?php if ( get_option( 'ec_option_use_shipping' ) ) {
								echo esc_attr( htmlspecialchars( $order->shipping_address_line_1, ENT_QUOTES ) );
							} ?>
							</span></td>
						</tr>
						<?php if ( $order->billing_address_line_2 !=  '' || $order->shipping_address_line_2 !=  '' ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_address_line_2, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td><span class='style22'>
							<?php if ( get_option( 'ec_option_use_shipping' ) ) {?>
							<?php echo esc_attr( htmlspecialchars( $order->shipping_address_line_2, ENT_QUOTES ) ); ?>
							<?php }?>
							</span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $order->billing_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order->billing_zip, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td><span class='style22'>
							<?php if ( get_option( 'ec_option_use_shipping' ) ) {?>
							<?php echo esc_attr( htmlspecialchars( $order->shipping_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $order->shipping_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order->shipping_zip, ENT_QUOTES ) ); ?>
							<?php }?>
							</span></td>
						</tr>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_country, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td><span class='style22'>
							<?php if ( get_option( 'ec_option_use_shipping' ) ) {?>
							<?php echo esc_attr( htmlspecialchars( $order->shipping_country, ENT_QUOTES ) ); ?>
							<?php }?>
							</span></td>
						</tr>
						<?php if ( get_option( 'ec_option_packing_slip_show_phone' ) ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_phone, ENT_QUOTES ) ); ?></span></td>
							<td>&nbsp;</td>
							<td><span class='style22'>
							<?php if ( get_option( 'ec_option_use_shipping' ) ) {?>
							<?php echo esc_attr( htmlspecialchars( $order->shipping_phone, ENT_QUOTES ) ); ?>
							<?php }?>
							</span></td>
						</tr>
						<?php }?>
					</table>
					<?php } else if ( get_option( 'ec_option_packing_slip_show_billing' ) ) { ?>
					<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='100%' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_billing_label" ); ?></td>
						</tr>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $order->billing_last_name, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php if ( '' != $order->billing_company_name ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_company_name, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_address_line_1, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php if ( $order->billing_address_line_2 !=  '' || $order->shipping_address_line_2 !=  '' ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_address_line_2, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $order->billing_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order->billing_zip, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_country, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php if ( get_option( 'ec_option_packing_slip_show_phone' ) ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->billing_phone, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php }?>
					</table>
					<?php } else if ( get_option( 'ec_option_packing_slip_show_shipping' ) ) { ?>
					<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
						<tr>
							<td width='100%' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_shipping_label" ); ?></td>
						</tr>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $order->shipping_last_name, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php if ( '' != $order->shipping_company_name ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_company_name, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_address_line_1, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php if ( $order->shipping_address_line_2 !=  '' || $order->shipping_address_line_2 !=  '' ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_address_line_2, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php } ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $order->shipping_state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $order->shipping_zip, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_country, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php if ( get_option( 'ec_option_packing_slip_show_phone' ) ) { ?>
						<tr>
							<td><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_phone, ENT_QUOTES ) ); ?></span></td>
						</tr>
						<?php }?>
					</table>
					<?php }?>
				</td>
			</tr>
			<?php if ( get_option( 'ec_option_packing_slip_show_email' ) ) { ?>
			<tr>
				<td colspan='4' align='left' class='style20'><span class='style22'><?php echo esc_attr( htmlspecialchars( $order->user_email, ENT_QUOTES ) ); ?></span></td>
			</tr>
			<?php }?>
			<tr>
				<td width='269' align='left'>&nbsp;</td>
				<td width='80' align='center'>&nbsp;</td>
				<td width='91' align='center'>&nbsp;</td>
				<td align='center'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" align='left' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?></td>
			</tr>
			<tr>
				<td colspan="4" align='left' class='style24'><?php echo esc_attr( htmlspecialchars( $order->shipping_method, ENT_QUOTES ) ); ?></td>
			</tr>
			<tr>
				<td colspan="4" align='left' class='style22'><?php echo esc_attr( htmlspecialchars( $order->shipping_carrier, ENT_QUOTES ) ); ?></td>
			</tr>
			<tr>
				<td colspan="4" align='left' class='style22'><?php echo esc_attr( htmlspecialchars( $order->tracking_number, ENT_QUOTES ) ); ?></td>
			</tr>
			<tr>
				<td colspan="4" align='left' class='style22'>&nbsp;</td>
			</tr>
			<tr>
				<td width='<?php if ( get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?>269<?php } else { ?>459<?php }?>'<?php if ( !get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?> colspan="3"<?php }?> align='left' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_details_header_1" ); ?></td>
				<td width='80' align='center' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_details_header_2" ); ?></td>
				<?php if ( get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?>
				<td width='91' align='center' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_details_header_3" ); ?></td>
				<td align='center' bgcolor='#DFDFDF' class='style20'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_details_header_4" ); ?></td>
				<?php }?>
			</tr>
			<?php for( $i=0; $i < count( $order_details); $i++) { 
				$unit_price = $GLOBALS['currency']->get_currency_display( $order_details[$i]->unit_price );
				$total_price = $GLOBALS['currency']->get_currency_display( $order_details[$i]->total_price );
			?>
			<tr>
				<td width='<?php if ( get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?>269<?php } else { ?>459<?php }?>' class='style22'<?php if ( !get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?> colspan="3"<?php }?>>
					<?php if ( get_option( 'ec_option_packing_slip_show_product_image' ) ) { ?>
					<?php
					if ( $order_details[$i]->is_deconetwork ) {
						$img_url = "https://" . get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_image_link;

					} else if ( substr( $order_details[$i]->image1, 0, 7 ) == 'http://' || substr( $order_details[$i]->image1, 0, 8 ) == 'https://' ) {
						$img_url = $order_details[$i]->image1;

					} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $order_details[$i]->image1 ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $order_details[$i]->image1 ) ) {
						$img_url = plugins_url( "wp-easycart-data/products/pics1/" . $order_details[$i]->image1, EC_PLUGIN_DATA_DIRECTORY );

					} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
						$img_url = get_option( 'ec_option_product_image_default' );

					} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg" ) ) {
						$img_url = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

					} else {
						$img_url = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

					}
					?>
					<div style="float:left; width:70px; margin-right:5px;"><img src="<?php echo esc_attr( $img_url ); ?>" style="width:70px; height:auto;" alt="<?php echo wp_easycart_language()->convert_text( $order_details[$i]->title ); ?>" /></div>
					<?php }?>
					<table>
						<?php if ( get_option( 'ec_option_packing_slip_show_product_title' ) ) { ?>
						<tr>
							<td><?php echo wp_easycart_language()->convert_text( $order_details[$i]->title ); ?></td>
						</tr>
						<?php } ?>
						<?php if ( get_option( 'ec_option_packing_slip_show_model_number' ) ) { ?>
						<tr>
							<td class="ec_option_name"><?php echo esc_attr( $order_details[$i]->model_number ); ?></td>
						</tr>
						<?php } ?>
						<?php if ( get_option( 'ec_option_packing_slip_show_options' ) ) {
							if ( ! $order_details[$i]->use_advanced_optionset || $order_details[$i]->use_both_option_types ) {
								if ( $order_details[$i]->optionitem_name_1 ) {
									echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $order_details[$i]->optionitem_name_1 );
									if ( $order_details[$i]->optionitem_price_1 < 0 ) {
										echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_1 ) ) . ")";
									} else if ( $order_details[$i]->optionitem_price_1 > 0 ) {
										echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_1 ) ) . ")";
									}
									echo "</span></td></tr>";
								}
								if ( $order_details[$i]->optionitem_name_2 ) {
									echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $order_details[$i]->optionitem_name_2 );
									if ( $order_details[$i]->optionitem_price_2 < 0 )
										echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_2 ) ) . ")";
									else if ( $order_details[$i]->optionitem_price_2 > 0 )
										echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_2 ) ) . ")";
									echo "</span></td></tr>";
								}
								if ( $order_details[$i]->optionitem_name_3 ) {
									echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $order_details[$i]->optionitem_name_3 );
									if ( $order_details[$i]->optionitem_price_3 < 0 )
										echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_3 ) ) . ")";
									else if ( $order_details[$i]->optionitem_price_3 > 0 )
										echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_3 ) ) . ")";
									echo "</span></td></tr>";
								}
								if ( $order_details[$i]->optionitem_name_4 ) {
									echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $order_details[$i]->optionitem_name_4 );
									if ( $order_details[$i]->optionitem_price_4 < 0 )
										echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_4 ) ) . ")";
									else if ( $order_details[$i]->optionitem_price_4 > 0 )
										echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_4 ) ) . ")";
									echo "</span></td></tr>";
								}
								if ( $order_details[$i]->optionitem_name_5 ) {
									echo "<tr><td><span class=\"ec_option_name\">" . wp_easycart_escape_html( $order_details[$i]->optionitem_name_5 );
									if ( $order_details[$i]->optionitem_price_5 < 0 )
										echo " (" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_5 ) ) . ")";
									else if ( $order_details[$i]->optionitem_price_5 > 0 )
										echo " (+" . esc_attr( $GLOBALS['currency']->get_currency_display( $order_details[$i]->optionitem_price_5 ) ) . ")";
									echo "</span></td></tr>";
								}
							}
							if ( $order_details[$i]->use_advanced_optionset || $order_details[$i]->use_both_option_types ) {
								$advanced_options = $mysqli->get_order_options( $order_details[$i]->orderdetail_id );
								foreach ( $advanced_options as $advanced_option ) {
									if ( $advanced_option->option_type == "file" ) {
										$file_split = explode( "/", $advanced_option->option_value );
										echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $file_split[1] ) . "</span></td></tr>";
									} else if ( $advanced_option->option_type == "grid" ) {
										echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . wp_easycart_escape_html( $advanced_option->optionitem_name . " (" . $advanced_option->option_value . ")" ) . "</span></td></tr>";
									} else {
										echo "<tr><td><span class=\"ec_option_label\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $advanced_option->option_value ) . "</span></td></tr>";
									}
								}
							}
						} ?>
					</table>
				</td>
				<td width='80' align='center' class='style22'><?php echo esc_attr( $order_details[$i]->quantity ); ?></td>
				<?php if ( get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?>
				<td width='91' align='center' class='style22'><?php echo esc_attr( $unit_price ); ?></td>
				<td align='center' class='style22'><?php echo esc_attr( $total_price ); ?></td>
				<?php }?>
			</tr>
			<?php }//end for loop ?>
			<?php if ( get_option( 'ec_option_packing_slip_show_pricing' ) ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center'>&nbsp;</td>
					<td width='91' align='center'>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<?php if ( get_option( 'ec_option_packing_slip_show_subtotal' ) ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_subtotal" ); ?></td>
					<td align='center' class='style22'><?php echo esc_attr( $subtotal ); ?></td>
				</tr>
				<?php }?>
				<?php if( get_option( 'ec_option_packing_slip_show_tiptotal' ) && $order->tip_total > 0 ){?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip' ); ?></td>
					<td  align='center'  class='style22'><?php echo esc_attr( $tip ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_taxtotal' ) && $order->tax_total > 0 ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_tax" ); ?></td>
					<td align='center' class='style22'><?php echo esc_attr( $tax ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_shippingtotal' ) && $order->shipping_total > 0 ) {?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_shipping" ); ?></td>
					<td align='center' class='style22'><?php echo esc_attr( $shipping ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_discounttotal' ) && $order->discount_total > 0 ) {?>
				<tr>
					<td>&nbsp;</td>
					<td align='center' class='style22'>&nbsp;</td>
					<td align='center' class='style22'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_discount" ); ?></td>
					<td align='center' class='style22'>-<?php echo esc_attr( $discount ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_taxtotal' ) && $has_duty ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_duty" ); ?></td>
					<td align='center' class='style22'><?php echo esc_attr( $duty ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_taxtotal' ) && $vat_rate != 0 ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_vat" ); ?><?php echo esc_attr( $vat_rate ); ?>%</td>
					<td align='center' class='style22'><?php echo esc_attr( $vat ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_taxtotal' ) && $gst_rate != 0 ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'>GST (<?php echo esc_attr( $gst_rate ); ?>%)</td>
					<td align='center' class='style22'><?php echo esc_attr( $gst_total ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_taxtotal' ) && $pst_rate != 0 ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'>PST (<?php echo esc_attr( $pst_rate ); ?>%)</td>
					<td align='center' class='style22'><?php echo esc_attr( $pst_total ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_taxtotal' ) && $hst_rate != 0 ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' class='style22'>HST (<?php echo esc_attr( $hst_rate ); ?>%)</td>
					<td align='center' class='style22'><?php echo esc_attr( $hst_total ); ?></td>
				</tr>
				<?php }?>
				<?php if ( get_option( 'ec_option_packing_slip_show_grandtotal' ) ) { ?>
				<tr>
					<td width='269'>&nbsp;</td>
					<td width='80' align='center' class='style22'>&nbsp;</td>
					<td width='91' align='center' bgcolor="#DFDFDF" class='style22'><strong><?php echo wp_easycart_language()->get_text( "cart_success", "cart_payment_complete_order_totals_grand_total" ); ?></strong></td>
					<td align='center' bgcolor="#DFDFDF" class='style22'><strong><?php echo esc_attr( $total ); ?></strong></td>
				</tr>
				<?php }?>
			<?php } // Close show pricing option ?>

			<?php if ( get_option( 'ec_option_packing_slip_show_order_notes' ) && '' != $order->order_customer_notes ) { ?>
			<tr>
				<td colspan='4' class='style22'><p><br>
					<hr />
					<h4><?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?></h4>
					<p><?php echo esc_attr( wp_unslash( $order->order_customer_notes ) ); ?></p>
					<br>
					<hr />
				</td>
			</tr>
			<?php }?>
		</table>
	</body>
</html>
