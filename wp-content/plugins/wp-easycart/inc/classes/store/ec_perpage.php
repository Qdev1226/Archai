<?php

class ec_perpage{
	private $mysqli;											// ec_db structure
	
	public $values;												// array of INTs
	public $selected;											// INT
	
	function __construct(){
		$this->mysqli = new ec_db();
		
		$this->values = $GLOBALS['ec_setting']->perpage_options;
		$this->selected = $this->get_selected( );
	}
	
	private function get_selected(){
		if( isset( $_GET['perpage'] ) )							return $this->mysqli->get_perpage( (int) $_GET['perpage'] );
		else if( isset( $GLOBALS['ec_cart_data']->cart_data->perpage ) && $GLOBALS['ec_cart_data']->cart_data->perpage != "" )				
																return $this->mysqli->get_perpage( $GLOBALS['ec_cart_data']->cart_data->perpage );
		else													return $this->get_default( );
		
	}
	
	private function get_default(){
		$sel_item = ( ceil( count( $this->values ) / 2 )  );
		if( $sel_item > 0 )										return $this->values[$sel_item-1];
		else													return 0;
	}
	
	public function get_items_per_page( $divider, $link_string ){
		
		$ret_string = "";
		
		for( $i=0; $i<count($this->values); $i++ ){
		
			if( $this->values[$i] == $this->selected )			$ret_string .= $this->get_per_page_link_selected($i);
			else												$ret_string .= $this->get_per_page_link($i, $link_string);
			
			if( $i+1 < count($this->values) )					$ret_string .= $divider;
			
		}
		
		return $ret_string;
		
	}
	
	private function get_per_page_link($i, $link_string){
		return "<a href=\"" . $this->get_current_url( ) . "perpage=" . $this->values[$i] . "\" class=\"ec_per_page_link\">" . $this->values[$i] . "</a>"; 
	}
	
	private function get_per_page_link_selected($i){
		return "<span class=\"ec_per_page_selected\">" . $this->values[$i] . "</span>";
	}
	
	public function get_per_page_url( $i ){
		$url = $this->get_current_url( ) . "perpage=" . $i;
		if( isset( $_GET['manufacturer'] ) ){
			$url .= "&manufacturer=" . htmlentities( sanitize_text_field( $_GET['manufacturer'] ), ENT_QUOTES );
		}
		
		if( isset( $_GET['pricepoint'] ) ){
			$url .= "&pricepoint=" . htmlentities( (int) $_GET['pricepoint'], ENT_QUOTES );
		}
		
		if( isset( $_GET['ec_search'] ) ){
			$url .= "&ec_search=" . htmlentities( sanitize_text_field( $_GET['ec_search'] ), ENT_QUOTES );
		}
		
		if( isset( $_GET['group_id'] ) ){
			$url .= "&group_id=" . htmlentities( (int) $_GET['group_id'], ENT_QUOTES );
		}
		
		if( isset( $_GET['menuid'] ) ){
			$url .= "&menuid=" . htmlentities( (int) $_GET['menuid'], ENT_QUOTES );
		}
		
		if( isset( $_GET['submenuid'] ) ){
			$url .= "&submenuid=" . htmlentities( (int) $_GET['submenuid'], ENT_QUOTES );
		}
		
		if( isset( $_GET['subsubmenuid'] ) ){
			$url .= "&subsubmenuid=" . htmlentities( (int) $_GET['subsubmenuid'], ENT_QUOTES );
		}
		
		if( isset( $_GET['filternum'] ) ){
			$url .= "&filternum=" . htmlentities( (int) $_GET['filternum'], ENT_QUOTES );
		}
		
		if( isset( $_GET['pagenum'] ) ){
			$url .= "&pagenum=" . htmlentities( (int) $_GET['pagenum'], ENT_QUOTES );
		}
		
		if( isset( $_GET['filter_option'] ) ){
            $filter_data_raw = explode( ',', sanitize_text_field( $_GET['filter_option'] ) );
            $filters = array( );
            for( $filter_i=0; $filter_i<count( $filter_data_raw ); $filter_i++ ){
                $filters[] = (int) $filter_data_raw[$filter_i];
            }
			$url .= "&filter_option=" . htmlentities( implode( ',', $filters ), ENT_QUOTES );
		}
		
		return $url;
	}
	
	private function get_current_url( ){
		$page_url = 'http';
		if( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ){
			$page_url .= "s";
		}
		
		$page_url .= "://";
		
		if( (int) $_SERVER["SERVER_PORT"] != 80 && (int) $_SERVER["SERVER_PORT"] != 443 ) {
			$page_url .= sanitize_text_field( $_SERVER["SERVER_NAME"] ).":".(int) $_SERVER["SERVER_PORT"];
		
		}else{
			$page_url .= sanitize_text_field( $_SERVER["SERVER_NAME"] );
		
		}
		
		if( substr_count( $page_url, '?' ) )						
			$page_url .= "&";
		else																
			$page_url = "?";
		
		return $page_url;
	}
}

?>