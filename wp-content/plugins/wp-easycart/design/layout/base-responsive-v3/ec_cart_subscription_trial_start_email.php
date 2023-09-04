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
				<a href="<?php echo esc_url_raw( $store_page ); ?>" target="_blank"><img src="<?php echo esc_attr( $email_logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( "name" ) ); ?>" style="max-height:250px; max-width:100%; height:auto;" /></a>
			</td>
		</tr>
		<tr>
			<td colspan='4' align='left' class='style22'>
				<strong><?php echo wp_easycart_language( )->get_text( 'subscription_trial', 'subscription_trial_email_title' ); ?></strong>

				<p><br><?php echo wp_easycart_language( )->get_text( 'subscription_trial', 'trial_message_1' ); ?> <?php echo esc_attr( $this->trial_period_days ); ?> <?php echo wp_easycart_language( )->get_text( 'subscription_trial', 'trial_message_2' ); ?> <?php echo esc_attr( $this->title ); ?> <?php echo wp_easycart_language( )->get_text( 'subscription_trial', 'trial_message_3' ); ?></p>

				<p><?php echo wp_easycart_language( )->get_text( 'subscription_trial', 'trial_message_4' ); ?></p>

			   <p><a href="<?php echo esc_attr( $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . $this->subscription_id ); ?>"><?php echo wp_easycart_language( )->get_text( 'subscription_trial', 'trial_message_link' ); ?></a></p>
			</td>
		</tr>
	</table>
</body>
</html>