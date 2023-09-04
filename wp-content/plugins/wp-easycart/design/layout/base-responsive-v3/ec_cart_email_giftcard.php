<html>
<head>
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

	<table>
		<thead>
			<tr>
				<td class="ec_title" colspan="2"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_header" ); ?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<?php if( get_option( 'ec_option_show_image_on_receipt' ) ){ ?>
				<td class="ec_image">
					<?php
					if ( $cart_item->is_deconetwork ) {
						$img_url = "https://" . get_option( 'ec_option_deconetwork_url' ) . $cart_item->deconetwork_image_link;

					} else if ( substr( $cart_item->image1_optionitem, 0, 7 ) == 'http://' || substr( $cart_item->image1_optionitem, 0, 8 ) == 'https://' ) {
						$img_url = $cart_item->image1_optionitem;

					} else if ( $cart_item->image1_optionitem != "" && file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $cart_item->image1_optionitem ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $cart_item->image1_optionitem ) ) {
						$img_url = plugins_url( "wp-easycart-data/products/pics1/" . $cart_item->image1_optionitem, EC_PLUGIN_DATA_DIRECTORY );

					} else if ( substr( $cart_item->image1, 0, 7 ) == 'http://' || substr( $cart_item->image1, 0, 8 ) == 'https://' ) {
						$img_url = $cart_item->image1;

					} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $cart_item->image1 ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $cart_item->image1 ) ) {
						$img_url = plugins_url( "wp-easycart-data/products/pics1/" . $cart_item->image1, EC_PLUGIN_DATA_DIRECTORY );

					} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
						$img_url = get_option( 'ec_option_product_image_default' );

					} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg" ) ) {
						$img_url = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

					} else {
						$img_url = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

					}
					?>
					<div style="ec_lineitem_image"><img src="<?php echo esc_attr( str_replace( "https://", "http://", $img_url ) ); ?>" alt="<?php echo esc_attr( wp_easycart_language( )->convert_text( $cart_item->title ) ); ?>" style="max-width:300px;" /></div>
				</td>
				<?php }?>
				<td class="ec_content">
					<div class="ec_content_row"><strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_to" ); ?>:</strong> <?php echo esc_attr( $cart_item->gift_card_to_name ); ?></div>
					<div class="ec_content_row"><strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_from" ); ?>:</strong> <?php echo esc_attr( $cart_item->gift_card_from_name ); ?></div>
					<div class="ec_content_row"><?php echo esc_attr( $cart_item->gift_card_message ); ?></div>
					<div class="ec_content_row ec_extra_margin"><strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_id" ); ?>: <?php echo esc_attr( $giftcard_id ); ?></strong></div>
					<div class="ec_content_row"><strong><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_amount" ); ?>: <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart_item->gift_card_value ) ); ?></strong></div>
					<div class="ec_content_row ec_extra_margin"><?php echo wp_easycart_language( )->get_text( "cart_success", "cart_giftcard_receipt_message" ); ?> <a href="<?php echo esc_attr( $store_page ); ?>" target="_blank"><?php echo esc_attr( $store_page ); ?></a>.</div>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>