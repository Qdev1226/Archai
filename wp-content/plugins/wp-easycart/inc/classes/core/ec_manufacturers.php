<?php

class ec_manufacturers{
	
	public $manufacturers;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		global $wpdb;
		$this->manufacturers = wp_cache_get( 'wpeasycart-manufacturers', 'wpeasycart-manufacturer' );
		if( !$this->manufacturers ){
			$this->manufacturers = $wpdb->get_results( "SELECT * FROM ec_manufacturer ORDER BY ec_manufacturer.name ASC" );
			wp_cache_set( 'wpeasycart-manufacturers', $this->manufacturers, 'wpeasycart-manufacturer' );
		}
	}
	
	public function get_manufacturer( $manufacturer_id ){
		
		for( $i=0; $i<count( $this->manufacturers ); $i++ ){
			
			if( $this->manufacturers[$i]->manufacturer_id == $manufacturer_id )
				return $this->manufacturers[$i];
			
		}
		
	}
	
	public function get_manufacturer_id_from_post_id( $post_id ){
		
		for( $i=0; $i<count( $this->manufacturers ); $i++ ){
			
			if( $this->manufacturers[$i]->post_id == $post_id )
				return $this->manufacturers[$i];
			
		}
		
	}
		
		
}

?>