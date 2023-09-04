<?php

class ec_customer_reviews{
	
	public $customer_reviews;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		$db = new ec_db( );
		$this->customer_reviews = $db->get_all_customer_reviews( );
	}
	
	public function get_customer_reviews( $product_id ){
		
		$reviews = array( );
		
		for( $i=0; $i<count( $this->customer_reviews ); $i++ ){
			
			if( $this->customer_reviews[$i]->product_id == $product_id )
				$reviews[] = $this->customer_reviews[$i];
			
		}
		
		return $reviews;
		
	}
		
		
}

?>