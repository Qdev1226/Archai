<?php

class wpeasycart_options{
	
	private $is_new_version;
	private $options;
	
	function __construct( ){
		$this->options = get_option( 'wpeasycart_options' );
	}
	
	public function get_option( $var ){
		if( isset( $this->options[$var] ) )
			return $this->options[$var];
		else
			return "";
	}
	
	public function update_option( $var, $value ){
		$this->options[$var] = $value;
		update_option( 'wpeasycart_options', $this->options );
	}
	
}

?>