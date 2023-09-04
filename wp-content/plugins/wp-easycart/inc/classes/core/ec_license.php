<?php

class ec_license{
	
	protected $mysqli;				// ec_db structure
	private $regcode;				// VARCHAR 255
	
	function __construct( ){
		
		$this->mysqli = new ec_db();
		$this->regcode = $this->mysqli->get_registration_code( );
		
	}
	
	public function is_registered( ){
		return $this->check_license( );
	}
	
	public function is_lite_version( ){
		$tcode =  substr($this->regcode, 9, 1);
		if( $tcode == 'L' ) 						return true;  
		else										return false; 
	}
	
	public function show_lite_message( ){
		return get_option( 'ec_option_show_lite_message' );
	}

	private function check_license( ){
		$tcode =  substr($this->regcode, 9, 1);
		if( $tcode == 'V' || $tcode == 'L' ) 		return true;  
		else										return false; 
	}
}

?>