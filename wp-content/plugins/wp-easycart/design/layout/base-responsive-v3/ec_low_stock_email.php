<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type='text/css'>
		<!--
			.style20 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
			.style22 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
			.ec_option_label{font-family: Arial, Helvetica, sans-serif; font-size:11px; font-weight:bold; }
			.ec_option_name{font-family: Arial, Helvetica, sans-serif; font-size:11px; }
		-->
		</style>
	</head>
	<body>
		<table width='539' border='0' align='center'>
			<tr>
				<td colspan='4' align='left' class='style22'>
					<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src='<?php echo esc_attr( $email_logo_url ); ?>' alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
				</td>
			</tr>
			<tr>
				<td colspan='4' align='left' class='style22'>	
					<h1>Stock for <?php echo wp_easycart_language( )->convert_text( $product->title ); ?> is Low</h1>
					<p><?php echo wp_easycart_language( )->convert_text( $product->title ); ?> stock level is currently at <?php echo esc_attr( $product->stock_quantity ); ?>.</p>
					<p><i>To turn off these notifications, go to WP EasyCart -> Settings -> Checkout.</i></p>
				</td>
			</tr>
			<tr>
				<td colspan='4'></td>
			</tr>
		</table>
	</body>
</html>