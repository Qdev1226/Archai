<?php

class ec_wpstyle{
	
	private $css_name;
	private $css_file;
	
	function __construct($css_name, $css_file){
		$this->css_name = $css_name;
		$this->css_file = $css_file;
		$this->register_css_file();
	}
	
	private function register_css_file(){
		wp_register_style( $this->css_name, $this->css_file );
	}
	
}

?>