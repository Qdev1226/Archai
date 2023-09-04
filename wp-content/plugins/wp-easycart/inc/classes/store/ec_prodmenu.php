<?php

class ec_prodmenu{
	
	public $menu1;				// ec_menu structure
	public $menu2;				// ec_menu structure
	public $menu3;				// ec_menu structure
	
	function __construct($one_a, $one_b, $one_c, $two_a, $two_b, $two_c, $three_a, $three_b, $three_c){
		$this->menu1 = new ec_menu($one_a, $one_b, $one_c);
		$this->menu2 = new ec_menu($two_a, $two_b, $two_c);
		$this->menu3 = new ec_menu($three_a, $three_b, $three_c);
	}
	
}

?>