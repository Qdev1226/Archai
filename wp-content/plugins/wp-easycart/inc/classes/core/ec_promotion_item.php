<?php

class ec_promotion_item{
	
	public $promotion_id;								// INT
	public $promotion_type;								// INT
	public $promotion_name;								// TEXT
	
	public $start_date;									// DATETIME
	public $end_date;									// DATETIME
	
	public $product_id_1;								// INT
	public $product_id_2;								// INT
	public $product_id_3;								// INT
	
	public $manufacturer_id_1;							// INT
	public $manufacturer_id_2;							// INT
	public $manufacturer_id_3;							// INT
	
	public $category_id_1;								// INT
	public $category_id_2;								// INT
	public $category_id_3;								// INT
	
	public $price1;										// FLOAT 15,3
	public $price2;										// FLOAT 15,3
	public $price3;										// FLOAT 15,3
	
	public $percentage1;								// DOUBLE 9,2
	public $percentage2;								// DOUBLE 9,2
	public $percentage3;								// DOUBLE 9,2
	
	public $number1;									// INT
	public $number2;									// INT
	public $number3;									// INT
	
	public $limit;										// INT
	
	function __construct( $promotion_row ){
		$this->promotion_id = $promotion_row->promotion_id;
		$this->promotion_type = $promotion_row->promotion_type;
		$this->promotion_name = $promotion_row->promotion_name;
		
		$this->start_date = $promotion_row->start_date;
		$this->end_date = $promotion_row->end_date;
		
		$this->product_id_1 = $promotion_row->product_id_1;
		$this->product_id_2 = $promotion_row->product_id_2;
		$this->product_id_3 = $promotion_row->product_id_3;
		
		$this->manufacturer_id_1 = $promotion_row->manufacturer_id_1;
		$this->manufacturer_id_2 = $promotion_row->manufacturer_id_2;
		$this->manufacturer_id_3 = $promotion_row->manufacturer_id_3;
		
		$this->category_id_1 = $promotion_row->category_id_1;
		$this->category_id_2 = $promotion_row->category_id_2;
		$this->category_id_3 = $promotion_row->category_id_3;
		
		$this->price1 = $promotion_row->price1;
		$this->price2 = $promotion_row->price2;
		$this->price3 = $promotion_row->price3;
		
		$this->percentage1 = $promotion_row->percentage1;
		$this->percentage2 = $promotion_row->percentage2;
		$this->percentage3 = $promotion_row->percentage3;
		
		$this->number1 = $promotion_row->number1;
		$this->number2 = $promotion_row->number2;
		$this->number3 = $promotion_row->number3;
		
		$this->limit = $promotion_row->product_limit;
	}
	
}

?>