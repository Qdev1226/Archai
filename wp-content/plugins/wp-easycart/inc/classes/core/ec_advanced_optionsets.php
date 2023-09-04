<?php

class ec_advanced_optionsets{
	
	public $db;
	public $option_sets;
	public $option_items;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		$this->db = new ec_db( );
		$this->option_sets = array( );
		$this->option_items = array( );
		$this->init_option_sets( );
	}
	
	private function init_option_sets( ){
		for( $i=0; $i<count( $this->option_sets ); $i++ ){
			if( $this->option_sets[$i]->option_meta ){
				$this->option_sets[$i]->option_meta = maybe_unserialize( $this->option_sets[$i]->option_meta );
			}else{
				$this->option_sets[$i]->option_meta = array(
					"min" => "",
					"max" => "",
					"step" => "",
					"url_var" => "",
					"swatch_size" => "30"
				);
			}
			$this->option_sets[$i]->option_items = array( );
			for( $j=0; $j<count( $this->option_items ); $j++ ){
				if( $this->option_items[$j]->option_id == $this->option_sets[$i]->option_id ){
					$this->option_sets[$i]->option_items[] = $this->option_items[$j];
				}
			}
		}
	}
	
	public function get_advanced_optionsets( $product_id ){
		
		$option_sets = array( );
		$this->option_sets = $this->db->get_advanced_optionsets( $product_id );
		$this->option_items = $this->db->get_all_advanced_optionitems( $this->option_sets );
		$this->init_option_sets( );
		
		return $this->option_sets;
		
	}
	
	public function get_advanced_option( $option_id ){
		
		global $wpdb;
		$this->option_sets = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_option WHERE ec_option.option_id = %d", $option_id ) );
		$this->option_items = $this->db->get_all_advanced_optionitems( $this->option_sets );
		$this->init_option_sets( );
		for( $i=0; $i<count( $this->option_sets ); $i++ ){
			if( $this->option_sets[$i]->option_id == $option_id ){
				return $this->option_sets[$i];
			}
		}
		
	}
		
		
}

?>