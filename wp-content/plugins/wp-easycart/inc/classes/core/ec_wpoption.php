<?php

class ec_wpoption{
	
	public $wp_option_name;								// String
	public $wp_option_default;							// String
	
	function __construct($name, $default){
		$this->wp_option_name = $name;
		$this->wp_option_default = $default;
	}
}

?>