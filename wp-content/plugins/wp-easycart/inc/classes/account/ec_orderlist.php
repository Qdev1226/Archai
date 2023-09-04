<?php

class ec_orderlist{
	protected $mysqli;								// ec_db structure

	public $orders = array();						// array of ec_orderitem structures
	public $num_orders;								// INT
	
	private $user_id;								// INT
	
	function __construct( $user_id ){
		$this->mysqli = new ec_db();
		
		$this->user_id = $user_id;
		
		$this->get_orders( );
	}
	
	private function get_orders( ){
		
		$result = $this->mysqli->get_order_list( $this->user_id );
		$this->num_orders = count($result);
	
		foreach( $result as $orderdata ){
			
			$order = new ec_orderdisplay( $orderdata );
			array_push( $this->orders, $order );
			
		}
		
	}
	
	public function display_order_list( $days_limit = 0, $order_limit = 0 ){
		
		for( $i=0; $i < count( $this->orders ) && ($order_limit == 0 || $i < $order_limit); $i++ ){
			if( $days_limit == 0 || $this->date_within_range( $this->orders[$i]->order_date, $days_limit ) ){
				$order = $this->orders[$i];
				if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_order_line.php' ) )	
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_account_order_line.php' );
				else
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_account_order_line.php' );
			}
		}
		
	}
	
	private function date_within_range( $order_date, $limit ){
		$order_strtotime = strtotime( $order_date );
		$limit_date = strtotime( "-" . $limit . " day" );
		
		if( $order_strtotime > $limit_date )		return true;
		else										return false;
	}
	
}

?>