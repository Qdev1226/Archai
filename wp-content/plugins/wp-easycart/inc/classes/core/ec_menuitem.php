<?php

class ec_menuitem{
	private $mysqli;					// ec_db structure
	public $menu_id;					// INT
	public $menu_level;					// INT
	public $menu_name;					// VARCHAR
	public $post_id;					// INT
	
	function __construct( $menu_id, $menu_level ){
		$this->mysqli = new ec_db( );
		$this->menu_id = $menu_id;
		$this->menu_level = $menu_level;
		$menurow = $GLOBALS['ec_menu']->get_menu_row( $menu_id, $menu_level );
		if( isset( $menurow ) ){
			$this->menu_name = $menurow->name;
			$this->post_id = $menurow->post_id;
		}
	}
}

?>