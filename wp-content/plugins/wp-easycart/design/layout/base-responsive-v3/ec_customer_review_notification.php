<html>
	<head>
		<title><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'email_title' ); ?></title>
		<style type='text/css'>
		<!--
		.style20 {
			font-family: Arial, Helvetica, sans-serif;
			font-weight: bold;
			font-size: 12px;
		}
		.style22 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
		</style>
	</head>
	<body>
		<table width='539' border='0' align='center'>
			<tr>
				<td colspan='4' align='left' class='style22'>
					<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
				</td>
			</tr>
			<tr>
				<td colspan='4' align='left' class='style22'>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'reviewed_product' ); ?>: <?php echo esc_attr( htmlspecialchars( stripslashes( $review->product_title ) ) ); ?></p>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'submitted_by' ); ?>: <?php echo esc_attr( ( $review->user_id != 0 ) ? $review->email : wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'anonymous' ) ); ?></p>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'rating_label' ); ?>: <?php echo esc_attr( htmlspecialchars( $review->rating ) ); ?>/5</p>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'review_title' ); ?>: <?php echo esc_attr( htmlspecialchars( stripslashes( $review->title ) ) ); ?></p>
					<p><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'review_comments' ); ?>: <?php echo esc_attr( nl2br( htmlspecialchars( stripslashes( $review->description ) ) ) ); ?></p>
					<p><a href="<?php echo esc_attr( $review_admin_url ); ?>" target="_blank"><?php echo wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'link_text' ); ?></a></p>
				</td>
			</tr>
		</table>
	</body>
</html>