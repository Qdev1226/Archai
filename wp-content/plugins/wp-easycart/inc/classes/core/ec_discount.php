<?php

class ec_discount{
	protected $mysqli;											// ec_db structure
	
	public $discount_total;										// Float 7,2
	public $coupon_discount;									// Float 7,2
	public $coupon_matches;
	public $giftcard_discount;									// Float 7,2
	public $shipping_discount;									// Float 15,3
	public $coupon_code;										// VARCHAR 255
	public $giftcard_code;										// VARCHAR 255
	public $shipping_subtotal;									// Float 15,3
	
	private $cart;												// array of ec_cartitem structures
	private $cart_subtotal;										// Float 15,3
	private $cart_grandtotal;									// FLOAT 15,3
	private $cart_apply_quantity;								// INT
	
	function __construct( $cart, $cart_subtotal, $shipping_subtotal, $coupon_code, $giftcard_code, $cart_grandtotal ){
		$this->mysqli = new ec_db( );
		$this->cart = $cart;
		$this->shipping_subtotal = $shipping_subtotal;
		$this->cart_subtotal = $cart_subtotal;
		$this->cart_grandtotal = $cart_grandtotal;
		$this->coupon_code = $coupon_code;
		$this->giftcard_code = $giftcard_code;
		$this->cart_apply_quantity = 1;
		$this->coupon_matches = 0;
		$this->set_discounts( );
	}
	
	public function get_discount_subtotal( ){
		return $this->discount_total;
	}
	
	private function set_discounts( ){
		$this->coupon_discount = $this->get_coupon_discount( );
		$this->giftcard_discount = $this->get_giftcard_discount( );
        $this->discount_total = $this->coupon_discount + $this->giftcard_discount;
		if( isset( $this->cart->cart_promo_discount ) ){
			$this->discount_total += $this->cart->cart_promo_discount;
		}
	}
	
	private function get_coupon_discount( ){
		
		$promocode_row = $GLOBALS['ec_coupons']->redeem_coupon_code( $this->coupon_code );
		if( $promocode_row && !$promocode_row->coupon_expired && ( $promocode_row->max_redemptions == 999 || $promocode_row->times_redeemed < $promocode_row->max_redemptions ) ){
			if( 
				( $promocode_row->by_manufacturer_id	&& 	$this->has_manufacturer_match( $promocode_row->manufacturer_id, $promocode_row->minimum_required 	) 	) ||
				( $promocode_row->by_category_id		&& 	$this->has_category_match( $promocode_row->category_id, $promocode_row->minimum_required 			) 	) ||
				( $promocode_row->by_product_id			&& 	$this->has_product_match( $promocode_row->product_id, $promocode_row->minimum_required 				)	) || 
				( $promocode_row->by_all_products 		&&	$this->has_all_match( $promocode_row->minimum_required 												)	)
			)														return $this->get_coupon_amount( $promocode_row );
			else													return 0;
		}else														return 0;
		
	}
	
	private function has_manufacturer_match( $manufacturer_id, $min_required ){
		$this->cart_subtotal = 0;
		$total_found = 0;
		$return_val = false;
		for( $i=0; $i<count( $this->cart->cart ); $i++){
			if( !$this->cart->cart[$i]->is_giftcard && $this->cart->cart[$i]->manufacturer_id == $manufacturer_id ){
				$return_val = true;
				$total_found += $this->cart->cart[$i]->quantity;
				$this->cart_subtotal = $this->cart_subtotal + $this->cart->cart[$i]->total_price;
			}
		}
		if( $total_found >= $min_required )
			$this->coupon_matches++;
		return ( $total_found >= $min_required ) ? $return_val : false;
	}
	
	private function has_category_match( $category_id, $min_required ){
		$this->cart_subtotal = 0;
		$total_found = 0;
		$return_val = false;
		for( $i=0; $i<count( $this->cart->cart ); $i++){
			if( !$this->cart->cart[$i]->is_giftcard && $this->mysqli->has_category_match( $category_id, $this->cart->cart[$i]->product_id ) ){
				$return_val = true;
				$total_found += $this->cart->cart[$i]->quantity;
				$this->cart_subtotal = $this->cart_subtotal + $this->cart->cart[$i]->total_price;
			}
		}
		if( $total_found >= $min_required )
			$this->coupon_matches++;
		return ( $total_found >= $min_required ) ? $return_val : false;
	}
	
	private function has_product_match( $product_id, $min_required ){
		$this->cart_subtotal = 0;
		$this->cart_apply_quantity = 0;
		$total_found = 0;
		$return_val = false;
		for( $i=0; $i<count( $this->cart->cart ); $i++){
			if( !$this->cart->cart[$i]->is_giftcard && $this->cart->cart[$i]->product_id == $product_id ){
				$return_val = true;	
				$total_found += $this->cart->cart[$i]->quantity;
				$this->cart_subtotal = $this->cart_subtotal + $this->cart->cart[$i]->total_price;
				$this->cart_apply_quantity = $this->cart_apply_quantity + $this->cart->cart[$i]->quantity;
			}
		}
		if( $total_found >= $min_required )
			$this->coupon_matches++;
		return ( $total_found >= $min_required ) ? $return_val : false;
	}
	
	private function has_all_match( $min_required ){
		if( $this->cart->total_items >= $min_required )
			$this->coupon_matches++;
		return ( $this->cart->total_items >= $min_required ) ? true : false;
	}
	
	private function get_coupon_amount( $promocode_row ){
		
		if( $promocode_row->is_dollar_based ){
			
			if( $this->cart_subtotal > ( $promocode_row->promo_dollar * $this->cart_apply_quantity ) )
																return ( $promocode_row->promo_dollar * $this->cart_apply_quantity );
			else												return $this->cart_subtotal;
			
		}else if( $promocode_row->is_percentage_based ){		return ( $this->cart_subtotal * $promocode_row->promo_percentage / 100 );
		 
		}else if( $promocode_row->is_shipping_based ){				
			
			if($promocode_row->promo_shipping == "0.00" || $promocode_row->promo_shipping > $this->shipping_subtotal)			
																$this->shipping_discount = $this->shipping_subtotal;
			else												$this->shipping_discount = $promocode_row->promo_shipping;
			
																return 0;
		 
		}else if( $promocode_row->is_bogo_based ){
            $matches = array( );
            $discount_amount = 0;
            
            if( $promocode_row->by_category_id ){
                global $wpdb;
                $category = $GLOBALS['ec_categories']->get_category( $promocode_row->category_id );
                $promo_cat_products = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_categoryitem WHERE category_id = %d", $promocode_row->category_id ) );
                $products_in_cat = array( );
                foreach( $promo_cat_products as $promo_cat_product ){
                    $products_in_cat[] = $promo_cat_product->product_id;
                }
            }
            
            for( $k=0; $k<count( $this->cart->cart ); $k++ ){
                for( $l=0; $l<$this->cart->cart[$k]->quantity; $l++ ){
                    if( !$promocode_row->by_product_id && !$promocode_row->by_manufacturer_id && !$promocode_row->by_category_id ){
                        $matches[] = $this->cart->cart[$k];
                    }else if( $promocode_row->by_product_id && $this->cart->cart[$k]->product_id == $promocode_row->product_id ){
                        $matches[] = $this->cart->cart[$k];
                    }else if( $promocode_row->by_manufacturer_id && $this->cart->cart[$k]->manufacturer_id == $promocode_row->manufacturer_id ){
                        $matches[] = $this->cart->cart[$k];
                    }else if( $promocode_row->by_category_id && in_array( $this->cart->cart[$k]->product_id, $products_in_cat ) ){
                        $matches[] = $this->cart->cart[$k];
                    }
                }
            }

            if( count( $matches ) > 1 ){
                usort( $matches, array( $this, 'sort_bogo_matches' ) );
                if( $promocode_row->promo_bogo_dollar > 0 ){
                    $discount_amount = $promocode_row->promo_bogo_dollar;
                }else{
                    $discount_amount = $matches[1]->unit_price * $promocode_row->promo_bogo_percentage / 100;
                }
            }
            
            return $discount_amount;
            
        }else													return 0;
	
	}
    
    private function sort_bogo_matches( $a, $b ){
        return ( $a->unit_price < $b->unit_price ) ? 1 : -1;
    }
	
	private function get_giftcard_discount( ){
		
		$giftcard_row = $this->mysqli->redeem_gift_card( $this->giftcard_code );
		
        // Subtract any gift cards in your cart from the discountable total.
        $cart_giftcards_total = 0;
        for( $i=0; $i<count( $this->cart->cart ); $i++){
			if( $this->cart->cart[$i]->is_giftcard ){
				$cart_giftcards_total += $this->cart->cart[$i]->total_price;
			}
		}
        
		if( $giftcard_row ){
			if( get_option( 'ec_option_gift_card_shipping_allowed' ) ){
				$giftcard_discountable_total = $this->cart_grandtotal - $this->coupon_discount - $cart_giftcards_total;
			}else{
				$giftcard_discountable_total = $this->cart_subtotal - $this->coupon_discount - $cart_giftcards_total;
			}
			
			if( $giftcard_discountable_total > $giftcard_row->amount )		
																return $giftcard_row->amount;
			else												return $giftcard_discountable_total;
		}else													return 0;
	
	}
	
}

?>