<?php

class ec_manufacturer{
	
	public $manufacturer_id;						// INT
	public $name;						// String
	
	function __construct($id, $name){
		$this->manufacturer_id = $id;
		$this->name = $name;
	}
	
}

?>