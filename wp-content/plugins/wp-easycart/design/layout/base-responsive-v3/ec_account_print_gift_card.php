<html>
	<head>
		<title>Gift Card <?php echo esc_attr( $giftcard_id ); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type='text/css'>
		<!--
			.ec_title {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 18px; float:left; width:100%; border-bottom:3px solid #CCC; margin-bottom:15px; }
			.ec_image { float:left; width:35%;}
			.ec_image > img{ max-width:100%; }
			.ec_content{ width:65%; padding-left:15px; }
			.ec_content_row{ font-family: Arial, Helvetica, sans-serif; font-size:12px; float:left; width:100%; margin:0 0 10px; }
			.ec_content_row strong{ font-weight:bold; }
			.ec_content_row.ec_extra_margin{ margin-top:25px; }
		-->
		</style>
	</head>
	<body>
		<table width='725' border='0' align='center'>
			<tr>
				<td colspan='4' align='left' class='style22'>
					<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
				</td>
			</tr>
			<tr>
				<?php if( get_option( 'ec_option_show_image_on_receipt' ) ){ ?>
				<td width='400' class='style24'><?php $ec_orderdetail->display_image( "large" ); ?></td>
				<td width="25"></td>
				<?php }?>
				<td width='300' align='left' class='style22' colspan="2">
					<div class="ec_content_row">
						<strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_to" ); ?>:</strong> 
						<?php $ec_orderdetail->display_gift_card_to_name( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_to' ) ); ?>
					</div>
					<div class="ec_content_row">
						<strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_from" ); ?>:</strong> 
						<?php $ec_orderdetail->display_gift_card_from_name( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_from' ) ); ?>
					</div>
					<div class="ec_content_row">
						<?php $ec_orderdetail->display_gift_card_message( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_message' ) ); ?>
					</div>
					<div class="ec_content_row ec_extra_margin">
						<strong>
							<?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_id" ); ?>: 
							<?php echo esc_attr( $giftcard_id ); ?>
						</strong>
					</div>
					<div class="ec_content_row">
						<strong>
							<?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_amount" ); ?>: 
							<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $giftcard_total ) ); ?>
						</strong>
					</div>
					<div class="ec_content_row ec_extra_margin">
						<?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_message" ); ?> 
						<a href="<?php echo esc_attr( $store_page ); ?>" target="_blank"><?php echo esc_attr( $store_page ); ?></a>.
					</div>
				</td>
			</tr>
		</table>
	</body>
</html>