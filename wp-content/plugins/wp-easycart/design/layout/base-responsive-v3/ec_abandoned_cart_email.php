<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<!--[if gte mso 9]><xml>
	 <o:OfficeDocumentSettings>
	  <o:AllowPNG/>
	  <o:PixelsPerInch>96</o:PixelsPerInch>
	 </o:OfficeDocumentSettings>
	</xml><![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="initial-scale=1.0"/>
	<meta name="format-detection" content="telephone=no"/>
	<title><?php echo wp_easycart_language( )->get_text( 'ec_abandoned_cart_email', 'email_title' ); ?></title>
	<style type="text/css">
		html {
			width: 100%;
		}
		img {
			display: block !important;
		}
		.yshortcuts a {
			border-bottom: none !important;
		}
		html, body {
			background-color: #ffffff;
			margin: 0;
			padding: 0;
		}
		img {
			height: auto;
			line-height: 100%;
			outline: none;
			text-decoration: none;
			display: block;
		}
		br, b br, em br, i br {
			line-height: 100%;
		}
		h1, h2, h3, h4, h5, h6 {
			line-height: 100% !important;
			-webkit-font-smoothing: antialiased;
		}
		h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
			color: #19388a !important;
		}
		ul {
			font-size: 15px; line-height: normal; color:#333333; text-align:left; font-family:  Arial, Tahoma, Helvetica, sans-serif;
		}
		h1 a:active, h2 a:active, h3 a:active, h4 a:active, h5 a:active, h6 a:active {
			color: #19388a !important;
		}
		/* Preferably not the same color as the 300 header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
		h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
			color: #19388a !important;
		}
		/* Preferably not the same color as the 300 header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */  
		table td, table tr {
			border-collapse: collapse; line-height:100%;
		}
		table, tr, td, p{ line-height:100%; }
		.yshortcuts, .yshortcuts a, .yshortcuts a:link, .yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
			color: black;
			text-decoration: none !important;
			border-bottom: none !important;
			background: none !important;
		} /* Body text color for the New Yahoo.  This example sets the font of Yahoo's Shortcuts to black. */
		code {
			white-space: 300;
			word-break: break-all;
		}
		span a {
			text-decoration: none !important;
		}
		a {
			text-decoration: underline;
			color:#19388a;
		}

		a:hover {
			text-decoration: underline !important;
		}
		p, .basicp{ margin:0 0 0 0 !important; }
		img {
			height: auto !important;
		}
		.largetitle{ font-family: 'Montserrat', sans-serif; font-size:32px; font-weight:bold; }
		.templetcontainer{
			width:600px !important;
			min-width:600px !important;
			max-width:600px !important;
		}
		.mobile-hide {
			display:block !important;
		}
		.mobile-show {
			display:none !important;
		}
		.templatecontainer{
			width:600px !important;
			min-width:600px !important;
			max-width:600px !important;
		}
		.mobile-center {
			text-align: left !important;
		}
		@media only screen and (max-width: 599px) {
			.mobile-spacer{ 
				min-width:100% !important;
			}
			.mobile-show {
				display:block !important;
			}
			body {
				font-size: 10px !important;
			}
			table.mobile-width {
				width: 100% !important;
			}
			.templetcontainer, .templetcontainer, table.templetcontainer, table.templetcontainer{
				width: 100% !important;
				min-width: 100% !important;
				max-width: 100% !important;
				padding-left: 10px !important;
				padding-right: 10px !important;
			}
			table.container2 {
				width: 100% !important;
				float: none !important;
			}
			td.full_width {
				display: block !important;
				width: 100% !important;
			}
			td.full_width img {
				width: 100% !important;
				height: auto !important;
				max-width: 100% !important;
				min-width: 124px !important;
			}
			td.full_width_2 {
				display: block !important;
				width: 100% !important;
			}
			.full_width{
				width:100% !important;
				min-width:100% !important;
				max-width:100% !important;
			}
			table.full_width {
				width: 100% !important;
			}
			table.full_width_containt {
				width: 100% !important;
				background-color: #ffffff;
				padding-left: 20px !important;
				padding-right: 20px !important;
			}
			table.full_width_containt_sec2 {
				width: 100% !important;
				background-color: #ffffff;
				padding-left: 20px !important;
				padding-right: 20px !important;
			}
			table.grid2_footer {
				width: 100% !important;
				margin-right: 0px !important;
			}
			table.grid2_footer-last {
				width: 100% !important;
			}
			table.grid2 {
				width: 100% !important;
				max-width:100% !important;
				margin-right: 0px !important;
			}
			table.grid2-last {
				width: 100% !important;
				max-width:100% !important;
			}
			table.grid3 {
				width: 100% !important;
				margin-right: 0px !important;
			}
			table.grid3-last {
				width: 100% !important;
			}
			table.row-2 {
				width: 100% !important;
			}
			/*start text center*/
			td.text-center {
				text-align: center !important;
			}

			div.text-center {
				text-align: center !important;
			}
			/*end text center*/

			td.font-small {
				font-size: 12px !important;
				line-height: 13px !important;
			}
			img.codelogo {
				max-width:130px !important;
			}

			/* start  clear and remove */
			table.flot_clear {
				float: none !important;
			}

			.mobile-hide {
				display:none !important;
				height: 0 !important;
				width: 0 !important;
				padding-left:0px !important;
				padding-right:0px !important;
			}
			td.mobile-width-hide {

				width: 0 !important;

			}
			/* end  clear and remove */

			table.width-small {
				width: 100% !important;
			}
			table.spacer {
				padding-left: 0px !important;
				padding-right: 0px !important;
			}
			td.spacer {
				padding-left: 0px !important;
				padding-right: 0px !important;
			}
			td.font-resize {
				font-size: 14px !important;
			}
			td.height_increase {
				height: 10px !important;
			}
			td.height_increase-20 {
				height: 20px !important;
			}
			img.mobile-center {
				float: none;
				margin: 0 auto;
			}
			.mobile-center table {
				margin: 0 auto;
			}
			.mobile-padding {
				padding-right: 10px!important;
				padding-left: 10px!important;
			}
			.mobile-hide-table-column{
				width:0px;
				display:none !important;
			}
			.mobile-center{
				text-align: center !important;
			}
		}
	</style>
</head>
<body style="padding:0; margin:0; background-color:#ffffff; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; font-size: 15px; font-family:  Arial, Tahoma, Helvetica, sans-serif; color:#333333;">
<table style="width: 100%; background-color: #ffffff; mso-cellspacing: 0px; mso-padding-alt: 0px 0px 0px 0px;" border="0" cellpadding="0" cellspacing="0"><!--START HEADER TOP -->
	<tbody>
		<tr>
			<td class="spacer" bgcolor="#ffffff" align="center" valign="top"><!-- start  templetcontainer width 600px -->
				<table class="templetcontainer" style="width: 600px; background-color: #fffcf5; mso-cellspacing: 0px; mso-padding-alt: 0px 0px 0px 0px; margin:0 auto;" align="center" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td valign="top" width="600" bgcolor="#fffcf5"><!-- start templetcontainer width 580px -->
							<table class="full_width" width="560" style="width:560px; mso-cellspacing: 0px; mso-padding-alt: 0px 0px 0px 0px; margin:0 auto;" align="center" border="0" cellpadding="0" cellspacing="0"><!-- start text content -->
								<tr>    
									<td colspan='4' align='left'>
										<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;"></a>
									</td>
								</tr>
								<tr>
								  <td height="5"></td>
								</tr>
								<tr>
									<td style="font-size:62px; line-height:62px; color:#827b6f; font-weight:bold; font-family:'Montserrat', sans-serif; padding:0px; text-align:left;">
										<?php echo wp_easycart_language( )->get_text( 'ec_abandoned_cart_email', 'something_in_cart' ); ?>
									</td>
								</tr>
								<tr>
									<td height="12"></td>
								</tr>
								<tr>
									<td style="font-size:26px; line-height:28px; color:#40392c; font-weight:bold; font-family:'Montserrat', sans-serif; padding:0px; text-align:left;">
										<?php echo wp_easycart_language( )->get_text( 'ec_abandoned_cart_email', 'complete_question' ); ?>
									</td>
								</tr>
								<tr>
								  <td height="25"></td>
								</tr>
								<tr>
								  <td height="1" style="border-bottom:1px solid #999;"></td>
								</tr>
								<tr>
								  <td height="10"></td>
								</tr>
								<tr>
									<td width='269' class='style22'>
										<table class="full_width" width="560" style="width:560px; mso-cellspacing: 0px; mso-padding-alt: 0px 0px 0px 0px; margin:0 auto;" align="center" border="0" cellpadding="0" cellspacing="0"><!-- start text content -->
											<?php foreach( $tempcart_rows as $tempcart_row ){ ?>
											<tr>
												<td width="100">
													<?php if( substr( $tempcart_row->image1, 0, 7 ) == 'http://' || substr( $tempcart_row->image1, 0, 8 ) == 'https://' ){ ?>
													<div style="ec_lineitem_image"><img src="<?php echo esc_attr( str_replace( "https://", "http://", $tempcart_row->image1 ) ); ?>" width="70" alt="<?php echo wp_easycart_language( )->convert_text( $tempcart_row->title ); ?>" /></div>

													<?php }else if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $tempcart_row->image1 ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $tempcart_row->image1 ) ){ ?>
													<div style="ec_lineitem_image"><img src="<?php echo esc_attr( str_replace( "https://", "http://", plugins_url( "wp-easycart-data/products/pics1/" . $tempcart_row->image1, EC_PLUGIN_DATA_DIRECTORY ) ) ); ?>" width="70" alt="<?php echo wp_easycart_language( )->convert_text( $tempcart_row->title ); ?>" /></div>

													<?php }?>
												</td>
												<td class='style20' width="350">
												<?php 
													echo wp_easycart_language( )->convert_text( $tempcart_row->title );
													if( $tempcart_row->optionitem_id_1 ){
														$optionitem = $wpdb->get_row( $wpdb->prepare( "SELECT ec_optionitem.optionitem_name, ec_option.option_label FROM ec_optionitem, ec_option WHERE ec_optionitem.option_id = ec_option.option_id AND ec_optionitem.optionitem_id = %d", $tempcart_row->optionitem_id_1 ) );
														echo "<br />" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name );
													}
													if( $tempcart_row->optionitem_id_2 ){
														$optionitem = $wpdb->get_row( $wpdb->prepare( "SELECT ec_optionitem.optionitem_name, ec_option.option_label FROM ec_optionitem, ec_option WHERE ec_optionitem.option_id = ec_option.option_id AND ec_optionitem.optionitem_id = %d", $tempcart_row->optionitem_id_2 ) );
														echo "<br />" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name );
													}
													if( $tempcart_row->optionitem_id_3 ){
														$optionitem = $wpdb->get_row( $wpdb->prepare( "SELECT ec_optionitem.optionitem_name, ec_option.option_label FROM ec_optionitem, ec_option WHERE ec_optionitem.option_id = ec_option.option_id AND ec_optionitem.optionitem_id = %d", $tempcart_row->optionitem_id_3 ) );
														echo "<br />" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name );
													}
													if( $tempcart_row->optionitem_id_4 ){
														$optionitem = $wpdb->get_row( $wpdb->prepare( "SELECT ec_optionitem.optionitem_name, ec_option.option_label FROM ec_optionitem, ec_option WHERE ec_optionitem.option_id = ec_option.option_id AND ec_optionitem.optionitem_id = %d", $tempcart_row->optionitem_id_4 ) );
														echo "<br />" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name );
													}
													if( $tempcart_row->optionitem_id_5 ){
														$optionitem = $wpdb->get_row( $wpdb->prepare( "SELECT ec_optionitem.optionitem_name, ec_option.option_label FROM ec_optionitem, ec_option WHERE ec_optionitem.option_id = ec_option.option_id AND ec_optionitem.optionitem_id = %d", $tempcart_row->optionitem_id_5 ) );
														echo "<br />" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name );
													}
												?>
												</td>
												<td width='65' align='center' class='style22'>x<?php echo esc_attr( $tempcart_row->tempcart_quantity ); ?></td>
											</tr>
											<tr>
											  <td height="10" colspan="3"></td>
											</tr>
											<tr>
											  <td height="1" style="border-bottom:1px solid #999;" colspan="3"></td>
											</tr>
											<?php }?>
										</table>
									</td>
								</tr>
								<tr>
								  <td height="25"></td>
								</tr>
								<tr>
									<td>
										<table border="0" align="left" cellpadding="0" cellspacing="0" bgcolor="#2a2a2a" style="background:#2a2a2a; min-width:150px; mso-cellspacing: 0px; mso-padding-alt: 0px 0px 0px 0px; margin:0 auto;">
											<tr>
												<td height="10" width="100%" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
											</tr>
											<tr>
												<td height="22" style="font-size: 14px; mso-line-height-rule:exactly; line-height: 22px;">
													<table height="22" border="0" align="center" cellpadding="0" cellspacing="0" style="min-width:150px; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;  mso-cellspacing: 0px; mso-padding-alt: 0px 0px 0px 0px; margin:0 auto;">
														<tr>
															<td height="22" align="center" style="min-width:150px; text-align:center !important; color: #ffffff; font-size: 14px; font-family: Arial, Helvetica, sans-serif; font-weight: 600; letter-spacing: 1px; mso-line-height-rule:exactly; line-height: 22px !important; padding:0 20px;" mc:edit="cta-button2" data-color="button-link" data-size="button-link"><!-- ============ tm-widget button ============ -->
																<a class="facebook_link" href="<?php echo esc_attr( $cart_page . $permalink_divider ); ?>ec_load_tempcart=<?php echo esc_attr( $tempcart_item->session_id ); ?>&ec_load_email=<?php echo esc_attr( $tempcart_item->email ); ?>" style="margin:0 auto; text-align:center !important; text-decoration: none; color: #ffffff; font-weight: bold; font-size:14px; mso-line-height-rule:exactly; line-height:22px !important;"><?php echo wp_easycart_language( )->get_text( 'ec_abandoned_cart_email', 'complete_checkout' ); ?> ►</a>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td height="10" width="100%" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
								  <td height="45"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>
</body>
</html>