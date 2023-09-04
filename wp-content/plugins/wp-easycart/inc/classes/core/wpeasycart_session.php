<?php
if( !class_exists( 'wpeasycart_session' ) ) :

class wpeasycart_session{
	
	protected static $_instance = null;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
	
	public function __construct( ){
		if( isset( $_COOKIE['ec_cart_id'] ) ){
			$GLOBALS['ec_cart_id'] = preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_cart_id'] ) ) );
			setcookie( "ec_cart_id", "", time( ) - 3600 );
			setcookie( "ec_cart_id", "", time( ) - 3600, "/" ); 
			setcookie( 'ec_cart_id', preg_replace( '/[^A-Z]/', '', strtoupper( $GLOBALS['ec_cart_id'] ) ), time( ) + ( 3600 * 24 * 1 ), "/" );
		}else{
			$GLOBALS['ec_cart_id'] = "not-set";
		}
	}
	
	public function handle_session( $session_id = false ){
		if( $session_id ){
			if( $session_id == 'not-set' ){
				$this->new_session( );
			}else{
				$GLOBALS['ec_cart_id'] = preg_replace( '/[^A-Z]/', '', strtoupper( $session_id ) );
				setcookie( "ec_cart_id", "", time( ) - 3600 );
				setcookie( "ec_cart_id", "", time( ) - 3600, "/" ); 
				setcookie( 'ec_cart_id', preg_replace( '/[^A-Z]/', '', strtoupper( $GLOBALS['ec_cart_id'] ) ), time( ) + ( 3600 * 24 * 1 ), "/" );
				$GLOBALS['ec_cart_data'] = new ec_cart_data( preg_replace( '/[^A-Z]/', '', strtoupper( $GLOBALS['ec_cart_id'] ) ) );
			}
			
		// Check Cookie for New Session Needed
		}else if( isset( $_COOKIE['ec_cart_id'] ) &&  $_COOKIE['ec_cart_id'] == 'not-set' ){
			$this->new_session( );
		
		// Check Cookie for header error
		}else if( isset( $_COOKIE['ec_cart_id'] ) && 'WARNINGCANNOT' == substr( $_COOKIE['ec_cart_id'], 0, 13 ) ) {
			$this->new_session( );
		
		// Check that is just isn't set!
		}else if( !isset( $_COOKIE['ec_cart_id'] ) || ( isset( $_COOKIE['ec_cart_id'] ) && $_COOKIE['ec_cart_id'] == "deleted" ) ){ // No Cookie, Set One
			$this->new_session( );
		}
	}
	
	private function new_session( ){
		global $wpdb;
		$vals = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
		$session_cart_id = $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)];
		$check_cart_id = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart_data.* FROM ec_tempcart_data WHERE ec_tempcart_data.session_id = %s", $session_cart_id ) );
		while( $check_cart_id ){ // If we get a result, create new and go until we get a unique tempcart id...
			$session_cart_id = $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)];
			$check_cart_id = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart_data.* FROM ec_tempcart_data WHERE ec_tempcart_data.session_id = %s", $session_cart_id ) );
		}
		$GLOBALS['ec_cart_id'] = preg_replace( '/[^A-Z]/', '', strtoupper( $session_cart_id ) );
		setcookie( "ec_cart_id", "", time( ) - 3600 );
		setcookie( "ec_cart_id", "", time( ) - 3600, "/" );
		setcookie( 'ec_cart_id', preg_replace( '/[^A-Z]/', '', strtoupper( $GLOBALS['ec_cart_id'] ) ), time( ) + ( 3600 * 24 * 1 ), "/" );
		$GLOBALS['ec_cart_data'] = new ec_cart_data( preg_replace( '/[^A-Z]/', '', strtoupper( $GLOBALS['ec_cart_id'] ) ) );
	}
	
}
endif; // End if class_exists check

function wpeasycart_session( ){
	return wpeasycart_session::instance( );
}
wpeasycart_session( );
?>