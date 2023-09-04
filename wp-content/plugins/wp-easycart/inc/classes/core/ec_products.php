<?php

class ec_products{
	
	private $db;
	public $products;
	public $valid_model_numbers;
	public $invalid_model_numbers;
	public $no_post_id_matches;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		$this->db = new ec_db( );
		$this->products = array( );
		$this->valid_model_numbers = array( );
		$this->no_post_id_matches = array( );
		$this->invalid_model_numbers = array( );
	}
	
	public function get_product_from_post_id( $post_id ){
		
		for( $i=0; $i<count( $this->no_post_id_matches ); $i++ ){
			
			if( $this->no_post_id_matches[$i] == $post_id )
				return false;
			
		}
		
		for( $i=0; $i<count( $this->products ); $i++ ){
			
			if( $this->products[$i]->post_id == $post_id )
				return $this->products[$i];
			
		}
		
		$product = $this->db->get_product_from_post_id( $post_id );
		if( $product )
			$this->products[] = $product;
		else
			$this->no_post_id_matches[] = $post_id;
		return $product;
		
	}
	
	public function get_model_number( $model_number ){
		
		for( $i=0; $i<count( $this->invalid_model_numbers ); $i++ ){
			
			if( $this->invalid_model_numbers[$i] == $model_number )
				return "";
			
		}
		
		for( $i=0; $i<count( $this->valid_model_numbers ); $i++ ){
			
			if( $this->valid_model_numbers[$i] == $model_number )
				return $this->valid_model_numbers[$i];
			
		}
		
		$found_model_number = $this->db->get_model_number( $model_number );
		if( $found_model_number )
			$this->valid_model_numbers[] = $found_model_number;
		else
			$this->invalid_model_numbers[] = $model_number;
		
		return $found_model_number;
		
	}
		
		
}

?>