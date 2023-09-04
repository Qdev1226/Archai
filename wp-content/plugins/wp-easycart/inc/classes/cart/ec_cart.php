<?php

class ec_cart{
	
	protected $mysqli;										// ec_db structure
	
	private $session_id;									// VARCHAR 255
	
	public $cart = array( ); 								// Array of ec_cartitem structures
	public $user;											// ec_user
	public $subtotal;										// Float 15,3
	public $taxable_subtotal;								// FLOAT 15,3
	public $discountable_subtotal;							// FLOAT 15,3
	public $discount_subtotal;								// Float 15,3
	public $shipping_subtotal;								// FLOAT 15,3
	public $vat_subtotal;									// FLOAT 15,3
	
	public $weight;											// INT
	public $length;											// FLOAT 15,3
	public $width;											// FLOAT 15,3
	public $height;											// FLOAT 15,3
	public $total_items;									// INT
	public $shippable_total_items;							// INT
	public $excluded_shippable_total_items;					// INT
	
	public $cart_promo_discount;							// FLOAT
	public $cart_total_promotion;							// TEXT
	
	//Get sessionid and create the cart
	function __construct( $session_id ){
		$this->mysqli = new ec_db( );
		$this->session_id = $session_id;
		
		$this->cart = $this->mysqli->get_temp_cart( $session_id );
		
		$this->user =& $GLOBALS['ec_user'];
		$this->make_vat_adjustments( );
		$this->update_cart_values();
		add_filter( 'wp_easycart_shipping_price_display', array( $this, 'add_handling_to_display' ), 1, 2 );
	}
	
	// Function to adjust VAT prices in the cart 
	// Used when vat is included and a customer should be applied to a different vat rate.
	private function make_vat_adjustments( ){
		$shipping_state = '';
        $shipping_country = '';
        if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
            $shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
        }else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
            $shipping_state = $GLOBALS['ec_user']->shipping->state;
        }
        if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
            $shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
        }else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
            $shipping_country = $GLOBALS['ec_user']->shipping->country;
        }
        $tax = new ec_tax( 0, 0, 0, $shipping_state, $shipping_country );

        for($i=0; $i<count( $this->cart ); $i++){

            if( $this->cart[$i]->vat_enabled ){

                if( $tax->vat_included && $tax->vat_rate_default != $tax->vat_rate ){ 

                    $default_vat = $tax->vat_rate_default / 100;
                    $new_vat = $tax->vat_rate / 100;
                    
                    $old_unit_price = $this->cart[$i]->unit_price;
                    $product_actual_price = number_format( $old_unit_price / ( $default_vat + 1 ), 2, '.', '' );
                    $unit_price = $product_actual_price * ( 1 + $new_vat );

                    $total_original_price = $this->cart[$i]->total_price;
                    $total_actual_price = number_format( $total_original_price / ( $default_vat + 1 ), 2, '.', '' );
                    $total_price = $total_actual_price * ( 1 + $new_vat );

                    $converted_original_price = $this->cart[$i]->total_price;
                    $converted_actual_price = number_format( $converted_original_price / ( $default_vat + 1 ), 2, '.', '' );
                    $converted_total_price = $converted_actual_price * ( 1 + $new_vat );

                    $this->cart[$i]->unit_price = $unit_price;
                    $this->cart[$i]->total_price = $total_price;
                    $this->cart[$i]->converted_total_price = $converted_total_price;

                }

            }

        }
	}
	
	//set the subtotal
	private function update_cart_values(){
		
		$this->update_cart_totals( );
		
		if( isset( $GLOBALS['ec_cart_data']->cart_data->coupon_code ) && $GLOBALS['ec_cart_data']->cart_data->coupon_code != "" )
			$coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
		else
			$coupon_code = 0;
		
		if( isset( $GLOBALS['ec_cart_data']->cart_data->giftcard ) && $GLOBALS['ec_cart_data']->cart_data->giftcard != "" )
			$gift_card = $GLOBALS['ec_cart_data']->cart_data->giftcard;
		else
			$gift_card = 0;
		
		$promotion = new ec_promotion( );
		$this->cart_promo_discount = $promotion->apply_promotions_to_cart( $this->cart, $this->discountable_subtotal, $this->cart_total_promotion );
		
		//If a promotion happened, need to recalculate subtotal!
		$this->update_cart_totals( );
	}
	
	public function update_cart_totals( ){
		$this->subtotal = 0;
		$this->shipping_subtotal = 0;
		$this->taxable_subtotal = 0;
		$this->discountable_subtotal = 0;
		$this->vat_subtotal = 0;
		$this->weight = 0;
		$this->total_items = 0;
		$this->shippable_total_items = 0;
        $this->excluded_shippable_total_items = 0;
		
		for($i=0; $i<count( $this->cart ); $i++){
			$this->subtotal = $this->subtotal + $this->cart[$i]->total_price;
            
            if( $this->cart[$i]->exclude_shippable_calculation )
                $this->excluded_shippable_total_items = $this->excluded_shippable_total_items + $this->cart[$i]->quantity;
			
			if( $this->cart[$i]->is_taxable ) 				
				$this->taxable_subtotal = $this->taxable_subtotal + $this->cart[$i]->total_price;
			
			if( $this->cart[$i]->is_shippable && !$this->cart[$i]->exclude_shippable_calculation )
				$this->shipping_subtotal = $this->shipping_subtotal + $this->cart[$i]->total_price;
			
			if( $this->cart[$i]->vat_enabled )
				$this->vat_subtotal = $this->vat_subtotal + $this->cart[$i]->total_price;
			
			if( $this->cart[$i]->is_shippable && !$this->cart[$i]->exclude_shippable_calculation )
				$this->weight = $this->weight + $this->cart[$i]->get_weight();
			
			$this->total_items = $this->total_items + $this->cart[$i]->quantity;
			
			if( $this->cart[$i]->is_shippable && !$this->cart[$i]->exclude_shippable_calculation )
				$this->shippable_total_items = $this->shippable_total_items + $this->cart[$i]->quantity;
				
			if( !$this->cart[$i]->is_giftcard )
				$this->discountable_subtotal = $this->discountable_subtotal + $this->cart[$i]->total_price;
		}
		
		$this->taxable_subtotal = $this->taxable_subtotal - $this->cart_promo_discount;
		
		if( $this->taxable_subtotal < 0 )
			$this->taxable_subtotal = 0;
		
		$this->calculate_parcel( );
	}
	
	// Check for a backordered item
	public function has_backordered_item( ){
		
		$products = array( );
		for( $i=0; $i<count( $this->cart ); $i++ ){
			
			if( $this->cart[$i]->use_optionitem_quantity_tracking ){
				if( $this->cart[$i]->optionitem_stock_quantity < $this->cart[$i]->quantity && $this->cart[$i]->allow_backorders )
					return true;
			
			}else if( $this->cart[$i]->show_stock_quantity ){
				if( isset( $products[$this->cart[$i]->product_id] ) ){
					$products[$this->cart[$i]->product_id] += $this->cart[$i]->quantity;
				}else{
					$products[$this->cart[$i]->product_id] = $this->cart[$i]->quantity;
				}
				if( $this->cart[$i]->stock_quantity < $products[$this->cart[$i]->product_id] && $this->cart[$i]->allow_backorders )
					return true;
			}
			
		}
		
		return false;
	}
	
	// Process Adding Item to cart
	public function process_add_to_cart($sessionid, $productid, $quantity, $option1, $option2, $option3, $option4, $option5, $message, $to_name, $from_name ){
		if( $this->is_duplicate($productid, $option1, $option2, $option3, $option4, $option5) )
			return $this->update_cart_item($sessionid, $productid, $quantity, $option1, $option2, $option3, $option4, $option5);
		else
			return $this->add_to_cart_item($sessionid, $productid, $quantity, $option1, $option2, $option3, $option4, $option5, $message, $to_name, $from_name );
	}
	
	// Check if product is already in the cart
	private function is_duplicate($productid, $option1, $option2, $option3, $option4, $option5){
		for($i=0; $i<count($this->cart); $i++){
			if(	$this->cart[$i]->Product->ProductID == $productid &&
				$this->cart[$i]->Options->OptionItem1->OptionItemID == $option1 &&
				$this->cart[$i]->Options->OptionItem2->OptionItemID == $option2 &&
				$this->cart[$i]->Options->OptionItem3->OptionItemID == $option3 &&
				$this->cart[$i]->Options->OptionItem4->OptionItemID == $option4 &&
				$this->cart[$i]->Options->OptionItem5->OptionItemID == $option5){
					return true;
					
			}
		}
		
		return false;	
	}
	
	// Add an item to the cart
	private function add_to_cart_item($sessionid, $productid, $quantity, $option1, $option2, $option3, $option4, $option5, $message, $to_name, $from_name ){
		// Get the Product Information
		$Product = $this->mysqli->get_cart_product( $productid );
		if( $Product['is_gift_card'] )				$this->add_gift_card( $Product, $message, $to_name, $from_name );
		else if( $Product['is_download'] )			$this->add_download( $Product );
		else if( $Product['is_donation'] )			$this->add_donation( $Product );
		else										$this->add_product( $Product );
		
		
		//If successfully added item to cart, return true
		return true;
	}
	
	private function add_gift_card( &$Product, $message, $to_name, $from_name ){
		return $this->mysqli->add_gift_card_to_cart( $Product, $message, $to_name, $from_name );
	}
	
	private function add_download( &$Product ){
		return $this->mysqli->add_download_to_cart( $Product );
	}
	
	private function add_donation( &$Product ){
		return $this->mysqli->add_donation_to_cart( $Product );
	}
	
	private function add_product( &$Product ){
		return $this->mysqli->add_product_to_cart( $Product );
	}
	
	// Update a cart item
	private function update_cart_item($sessionid, $productid, $quantity, $option1, $option2, $option3, $option4, $option5){
		
		//If successfully updated the cart item, return true
		return true;
	}
	
	public function get_total_items( ){
		return $this->total_items;
	}
	
	public function display_cart_items( $vat_enabled, $vat_country_match ){
		for($i=0; $i<count( $this->cart ); $i++){
			$cart_item = $this->cart[$i];
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_page.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_item.php' );	
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_item.php' );	
		}
	}
	
	public function get_subtotal( $vat_enabled, $vat_country_match ){
		if( $vat_enabled && !$vat_country_match)
			return $this->subtotal - $this->vat_subtotal;
		else
			return $this->subtotal;
	}
	
	public function get_handling_total( ){
		$handling_total = 0;
		$handling_added_ids = array( );
		for( $i=0; $i<count( $this->cart ); $i++ ){
			if( !in_array( $this->cart[$i]->product_id, $handling_added_ids ) ){
				$handling_total = $handling_total + $this->cart[$i]->handling_price;
				$handling_added_ids[] = $this->cart[$i]->product_id;
			}
			$handling_total = $handling_total + ( $this->cart[$i]->handling_price_each * $this->cart[$i]->quantity );
		}
		return $handling_total;
	}
	
	public function add_handling_to_display( $rate, $method ){
		if( $rate == 'FREE' || $method == 'free' ) {
			return $rate;
		}
		return $rate + $this->get_handling_total( );
	}
	
	public function get_discount_total( ){
		return number_format( $this->discount_subtotal, 2 );
	}
	
	public function get_grand_total( ){
		return number_format( $this->grand_total, 2 );
	}
	
	private function calculate_parcel( ){ // Thank you Fraktjakt for this function.
 
		// Create an empty package
		$package_dimensions = array( 0, 0, 0 );
		
		// Step through each product
		foreach( $this->cart as $cart_item ){
		
			// Create an array of product dimensions
			$product_dimensions = array( $cart_item->width, $cart_item->height, $cart_item->length );
			
			// Twist and turn the item, longest side first ([0]=length, [1]=width, [2]=height)
			rsort( $product_dimensions, SORT_NUMERIC); // Sort $product_dimensions by highest to lowest
			
			// Package height + item height
			$package_dimensions[2] += $product_dimensions[2];
			
			// If this is the widest item so far, set item width as package width
			if($product_dimensions[1] > $package_dimensions[1]) 
				
				$package_dimensions[1] = $product_dimensions[1];
			
			// If this is the longest item so far, set item length as package length
			if($product_dimensions[0] > $package_dimensions[0]) 
				$package_dimensions[0] = $product_dimensions[0];
			
			// Twist and turn the package, longest side first ([0]=length, [1]=width, [2]=height)
			rsort( $package_dimensions, SORT_NUMERIC );
			
		}
		
		$this->width = round( $package_dimensions[0], 0 );
		$this->height = round( $package_dimensions[1], 0 );
		$this->length = round( $package_dimensions[2], 0 );
		
		return $package_dimensions;
	}

}


?>