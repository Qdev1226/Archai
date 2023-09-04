<?php

class ec_pricetiers{
	
	public $pricetiers;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		$db = new ec_db( );
		$this->pricetiers = $db->get_pricetier_list( );
	}
	
	public function get_pricetiers( $product_id ){
		
		$pricetiers = array( );
		
		for( $i=0; $i<count( $this->pricetiers ); $i++ ){
			
			if( $this->pricetiers[$i]->product_id == $product_id )
				$pricetiers[] = $this->pricetiers[$i];
			
		}
		
		return $pricetiers;
		
	}
		
		
}

?>