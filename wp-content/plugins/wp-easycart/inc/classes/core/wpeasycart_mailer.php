<?php
class wpeasycart_mailer{
	
	public function send_order_email( $to, $subject, $message ){
		
		// Init
		if( !class_exists( 'PHPMailer' ) ){
            require_once( EC_PLUGIN_DIRECTORY . '/inc/classes/core/class-phpmailer.php' );
        }
        $mail = new PHPMailer();
		$errors = false;
		
		// Setup To
		$to_addresses = explode( ",", $to );
		foreach( $to_addresses as $to_address ){
			$mail->AddAddress( trim( $to_address ) );
		}
		
		// Setup From
		$from_val = stripslashes( get_option( 'ec_option_order_from_email' ) );
		$matches = array( );
		preg_match( "/(.*)\<(.*)\>/", $from_val, $matches );
		if( count( $matches ) == 3 ){
			$from_name = $matches[1];
			$from_email = $matches[2];
		}else{
			$from_name = get_bloginfo( "name" );
			$from_email = $from_val;
		}
		$mail->SetFrom( $from_email, $from_name );
		
		// Setup Sending Vars
		$charset = get_bloginfo( 'charset' );
		$mail->CharSet = $charset;
		$mail->SMTPDebug = 0;
		
		// Setup SMTP Data
		if( get_option( 'ec_option_order_use_smtp' ) ){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			if( get_option( 'ec_option_order_from_smtp_username' ) != "" )
				$mail->Username = get_option( 'ec_option_order_from_smtp_username' );
			if( get_option( 'ec_option_order_from_smtp_password' ) != "" )
				$mail->Password = get_option( 'ec_option_order_from_smtp_password' );
			$mail->Host = get_option( 'ec_option_order_from_smtp_host' );
			$mail->Port = get_option( 'ec_option_order_from_smtp_port' ); 
		}
			
		if ( get_option( 'ec_option_order_from_smtp_encryption_type' ) !== 'none' ) {
			$mail->SMTPSecure = get_option( 'ec_option_order_from_smtp_encryption_type' );
		}
				
		$mail->SMTPAutoTLS = false;
		
		// Set Emailer Vars
		$mail->isHTML( true );
		$mail->Subject = $subject;
		$mail->MsgHTML( $message );
		
		
		/* Send mail and return result */
		if( !$mail->Send( ) ){
			$errors = $mail->ErrorInfo;
		}
		$mail->ClearAddresses();
		$mail->ClearAllRecipients();
		
		return $errors;
		
	}
	
	public function send_customer_email( $to, $subject, $message ){
		
		// Init
		if( !class_exists( 'PHPMailer' ) ){
            require_once( EC_PLUGIN_DIRECTORY . '/inc/classes/core/class-phpmailer.php' );
        }
        $mail = new PHPMailer();
		$errors = false;
		
		// Setup To
		$to_addresses = explode( ",", $to );
		foreach( $to_addresses as $to_address ){
			$mail->AddAddress( trim( $to_address ) );
		}
		
		// Setup From
		$from_val = stripslashes( get_option( 'ec_option_password_from_email' ) );
		$matches = array( );
		preg_match( "/(.*)\<(.*)\>/", $from_val, $matches );
		if( count( $matches ) == 3 ){
			$from_name = $matches[1];
			$from_email = $matches[2];
		}else{
			$from_name = get_bloginfo( "name" );
			$from_email = $from_val;
		}
		$mail->SetFrom( $from_email, $from_name );
		
		// Setup Sending Vars
		$charset = get_bloginfo( 'charset' );
		$mail->CharSet = $charset;
		$mail->SMTPDebug = 0;
		
		// Setup SMTP Data
		if( get_option( 'ec_option_password_use_smtp' ) ){
			$mail->IsSMTP();
			$mail->SMTPAuth = true;
			if( get_option( 'ec_option_password_from_smtp_username' ) != "" )
				$mail->Username = get_option( 'ec_option_password_from_smtp_username' );
			if( get_option( 'ec_option_password_from_smtp_password' ) != "" )
				$mail->Password = get_option( 'ec_option_password_from_smtp_password' );
			$mail->Host = get_option( 'ec_option_password_from_smtp_host' );
			$mail->Port = get_option( 'ec_option_password_from_smtp_port' ); 
		}
			
		if ( get_option( 'ec_option_password_from_smtp_encryption_type' ) !== 'none' ) {
			$mail->SMTPSecure = get_option( 'ec_option_password_from_smtp_encryption_type' );
		}
				
		$mail->SMTPAutoTLS = false;
		
		// Set Emailer Vars
		$mail->isHTML( true );
		$mail->Subject = $subject;
		$mail->MsgHTML( $message );
		
		
		/* Send mail and return result */
		if( !$mail->Send( ) ){
			$errors = $mail->ErrorInfo;
		}
		$mail->ClearAddresses();
		$mail->ClearAllRecipients();
		
		return $errors;
		
	}
	
}
?>