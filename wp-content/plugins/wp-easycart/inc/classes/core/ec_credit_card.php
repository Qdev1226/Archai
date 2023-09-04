<?php

class ec_credit_card{
	
	public $payment_method;
	public $card_holder_name;
	public $card_number;
	public $expiration_month;
	public $expiration_year;
	public $security_code;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( $payment_method, $card_holder_name, $card_number, $expiration_month, $expiration_year, $security_code ){
		$this->payment_method = $payment_method;
		$this->card_holder_name = $card_holder_name;
		$this->card_number = $card_number;
		$this->expiration_month = $expiration_month;
		$this->expiration_year = $expiration_year;
		$this->security_code = $security_code;
	}
	
	/****************************************
	* HELPER FUNCTIONS
	*****************************************/
	public function get_expiration_year( $year_length ){
		if( strlen( $this->expiration_year ) == $year_length )
			return $this->expiration_year;
		else if( $year_length == 2 )
			return substr( $this->expiration_year, 2, 2 );
		else
			return "20" . $this->expiration_year;
	}
	
	public function get_last_four( ){
		return substr( $this->card_number, -4 );
	}
		
		
}

?>