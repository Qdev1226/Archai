<?php

class ec_promotions{
	
	public $promotions;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		$db = new ec_db( );
		$this->promotions = $db->get_promotions( );
	}
	
	public function apply_promotions_to_shipping( $cart_subtotal, $rate ){
		$max_promotion = 0;
		for( $i=0; $i<count( $this->promotions ); $i++ ){
			if( $this->promotions[$i]->promotion_type == 4 ){
				if( $cart_subtotal >= $this->promotions[$i]->price2 ){
					
					// Discount cart by price 1
					if( $this->promotions[$i]->price1 != 0 ){
						if( $this->promotions[$i]->price1 > $max_promotion ){
							$max_promotion = $this->promotions[$i]->price1;
						}
					
					// Discount cart by percentage 1
					}else if( $this->promotions[$i]->percentage1 != 0 ){
						if( ( $rate * $this->promotions[$i]->percentage1 / 100 ) > $max_promotion ){
							$max_promotion = ( $rate * $this->promotions[$i]->percentage1 / 100 );
						}
					
					// Free Shipping
					}else{
						$max_promotion = $rate;
					}
				}
			}
		}
		return $rate - $max_promotion;
	}
		
}

?>