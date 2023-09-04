<?php

class ec_pricepoints{
	
	public $pricepoints;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		global $wpdb;
		$this->pricepoints = wp_cache_get( 'wpeasycart-pricepoints' );
		if( !$this->pricepoints ){
			$this->pricepoints = $wpdb->get_results( "SELECT ec_pricepoint.* FROM ec_pricepoint ORDER BY ec_pricepoint.pricepoint_order ASC" );
			wp_cache_set( 'wpeasycart-pricepoints', $this->pricepoints );
		}
	}
	
	public function get_pricepoint( $pricepoint_id ){
		
		for( $i=0; $i<count( $this->pricepoints ); $i++ ){
			
			if( $this->pricepoints[$i]->pricepoint_id == $pricepoint_id )
				return $this->pricepoints[$i];
				
		}
		
	}
		
		
}

?>