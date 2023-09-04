<?php

class ec_selectedoptions{
	public $OptionItem1;			//ec_optionitem structure
	public $OptionItem2;			//ec_optionitem structure
	public $OptionItem3;			//ec_optionitem structure
	public $OptionItem4;			//ec_optionitem structure
	public $OptionItem5;			//ec_optionitem structure
	
	function __construct($optid1, $optid2, $optid3, $optid4, $optid5){
		$this->OptionItem1 = new ec_optionitem($optid1);
		$this->OptionItem2 = new ec_optionitem($optid2);
		$this->OptionItem3 = new ec_optionitem($optid3);
		$this->OptionItem4 = new ec_optionitem($optid4);
		$this->OptionItem5 = new ec_optionitem($optid5);
	}
}

?>