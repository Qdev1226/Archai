<?php

class ec_giftcard{
	public $to_name;					// VARCHAR 255
	public $from_name;					// VARCHAR 255
	public $message;					// TEXT
	public $to_email;					// VARCHAR 255
	
	function __construct( $to_name, $from_name, $message, $to_email ){
		$this->to_name = $to_name;
		$this->from_name = $from_name;
		$this->message = $message;
		$this->to_email = $to_email;
	}
}

?>