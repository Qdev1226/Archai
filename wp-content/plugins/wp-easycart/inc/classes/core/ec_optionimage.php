<?php

class ec_optionimage{
	
	public $OptionItemID;			//INT
	public $OptionName;				//VARCHAR 255
	public $Image;					//VARCHAR 255
	
	function __construct( $optid, $image ){
		$this->OptionItemID = $optid;
		$this->Image = $image;
		$this->set_option_name();
	}
	
	private function set_option_name(){
		global $wpdb;
		$sql = "SELECT optionitemname FROM optionitems WHERE optionitemID = %s";
		$result = $wpdb->get_row( $wpdb->prepare( $sql, $this->OptinItemID ) );
		
		if( isset( $result ) && isset( $result->optionitemname ) ){
			$this->OptionName = $row->optionitemname;
		}
	}
	
}

?>