<?php

class ec_featuredproducts{
	protected $mysqli;									// ec_db structure
	
	public $product1;									// ec_product structure
	public $product2;									// ec_product structure
	public $product3;									// ec_product structure
	public $product4;									// ec_product structure
	
	function __construct( $product_id_1, $product_id_2, $product_id_3, $product_id_4 ){
		
		if($product_id_1 || $product_id_2 || $product_id_3 || $product_id_4 ){
			$products = wp_cache_get( 'wpeasycart-featured-products-'.$product_id_1."-".$product_id_2."-".$product_id_3."-".$product_id_4 );
			if( !$products ){
				$this->mysqli = new ec_db( );
															$where_statement  = " WHERE (";
															$where_statement .= " product.product_id = '"  . $product_id_1 . "'";
															$where_statement .= " OR product.product_id = '"  . $product_id_2 . "'";
															$where_statement .= " OR product.product_id = '"  . $product_id_3 . "'";
															$where_statement .= " OR product.product_id = '"  . $product_id_4 . "'";
															$where_statement .= " ) AND product.activate_in_store = 1 ";
												
															$order_statement  = " ORDER BY FIELD(product.product_id ";
				if( $product_id_1 )							$order_statement .= ", '" . $product_id_1 . "'";
				if( $product_id_2 )							$order_statement .= ", '" . $product_id_2 . "'";
				if( $product_id_3 )							$order_statement .= ", '" . $product_id_3 . "'";
				if( $product_id_4 )							$order_statement .= ", '" . $product_id_4 . "'";
															$order_statement .= ") ";
					
				
				$products = $this->mysqli->get_product_list( $where_statement, $order_statement, "", $GLOBALS['ec_cart_data']->ec_cart_id, 'wpeasycart-featured-products-'.$product_id_1."-".$product_id_2."-".$product_id_3."-".$product_id_4 );
			}
			
			if( count( $products ) > 0 )				$this->product1 = new ec_product( $products[0], 1 );
			if( count( $products ) > 1 )				$this->product2 = new ec_product( $products[1], 1 );
			if( count( $products ) > 2 )				$this->product3 = new ec_product( $products[2], 1 );
			if( count( $products ) > 3 )				$this->product4 = new ec_product( $products[3], 1 );
		}
		
	}
	
}

?>