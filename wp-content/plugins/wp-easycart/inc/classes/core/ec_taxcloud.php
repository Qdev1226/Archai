<?php
if( !class_exists( 'ec_taxcloud' ) ) :

final class ec_taxcloud{
	
	protected static $_instance = null;
	
	public $address_verified;
	
	public $tax_amount;
	
	public $subscription_product;
	public $subscription_product_quantity;
	public $subscription_product_discount = 0;
	public $subscription_product_option_price = 0;
	public $subscription_product_option_onetime = 0;
	
	public static function instance( ) {
		
		if( is_null( self::$_instance ) ) {
			self::$_instance = new self(  );
		}
		return self::$_instance;
	
	}
	
	public function __construct( ){
		
		add_action( 'init', array( $this, 'initiate_taxcloud' ), 10 );
		add_action( 'wpeasycart_cart_updated', array( $this, 'update_tax_amount' ), 10 );
		add_action( 'wpeasycart_order_inserted', array( $this, 'add_tax_cloud_order' ), 10, 5 );
		add_action( 'wpeasycart_order_paid', array( $this, 'approve_tax_cloud_order' ), 10, 1 );
		add_action( 'wpeasycart_full_order_refund', array( $this, 'refund_tax_cloud_order' ), 10, 1 );
		
	}
	
	public function setup_subscription_for_tax( $product, $quantity, $discount = 0, $option_total = 0, $option_total_onetime = 0 ){
		$this->subscription_product = $product;
		$this->subscription_product_option_price = $option_total;
		$this->subscription_product_quantity = $quantity;
		$this->subscription_product_discount = $discount;
		$this->subscription_product_option_onetime = $option_total_onetime;
		$this->update_tax_amount( );
	}
	
	public function initiate_taxcloud( ){
		
		if( $GLOBALS['ec_cart_data']->cart_data->taxcloud_tax_amount != "" ){
			$this->tax_amount = $GLOBALS['ec_cart_data']->cart_data->taxcloud_tax_amount;
			$this->address_verified = $GLOBALS['ec_cart_data']->cart_data->taxcloud_address_verified;
			
		}else{
			$this->tax_amount = 0;
			$this->address_verified = 0;
			
		}
		
	}
	
	public function update_tax_amount() {
		if ( '' != get_option( 'ec_option_tax_cloud_api_id' ) && '' != get_option( 'ec_option_tax_cloud_api_key' ) ) {
			$db = new ec_db();
			$cartpage = new ec_cartpage();

			$api_id = get_option( 'ec_option_tax_cloud_api_id' );
			$api_key = get_option( 'ec_option_tax_cloud_api_key' );
			$cart_id = $GLOBALS['ec_cart_data']->ec_cart_id;

			$customerID = $GLOBALS['ec_cart_data']->cart_data->user_id;
			if ( $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
				$customerID = $GLOBALS['ec_cart_data']->cart_data->guest_key;
			}

			$cartitems = $this->get_tax_cloud_cartitems( $cartpage->order_totals->shipping_total, $cartpage->order_totals->discount_total );
			$origin = $this->get_tax_cloud_origin();
			$destination = $this->get_tax_cloud_destination();

			$parameters = array(
				'apiLoginID' => $api_id,
				'apiKey' => $api_key,
				'customerID' => $customerID,
				'cartID' => $cart_id,
				'cartItems' => $cartitems,
				'origin' => $origin,
				'destination' => $destination,
				'deliveredBySeller' => false,
				'exemptCert' => NULL
			);

			if ( $destination ) {
				$is_verified = $this->tax_cloud_address_verification();
				if ( $is_verified ) {
					$parameters["destination"] = $this->get_tax_cloud_destination( );
					$request = new WP_Http;
					$response = $request->request(
						$this->get_tax_cloud_url( ) . 'Lookup',
						array(
							'method' => 'POST',
							'body' => json_encode( $parameters ),
							'headers' => array(
								'Content-Type' => 'application/json',
								'Content-Length' => strlen( json_encode( $parameters ) )
							),
							'timeout' => 30
						)
					);
					if ( is_wp_error( $response ) ) {
						$error_message = $response->get_error_message();
						$db->insert_response( 0, 1, 'TAX CLOUD LOOKUP ERROR', $error_message );
						return;
					}
					$db->insert_response( 0, 0, 'Tax Cloud Lookup', print_r( $response, true ) );
					$response = json_decode( $response['body'] );

					if ( $response->ResponseType == 0 ) {
						$this->tax_amount = 0;
					} else {
						$total = 0;
						foreach ( $response->CartItemsResponse as $cart_item ) {
							$total = $total + floatval( $cart_item->TaxAmount );
						}
						$this->tax_amount = $total;
					}
				} else {
					$this->tax_amount = 0;
				}
			}else{
				$this->tax_amount = 0;
			}

			$GLOBALS['ec_cart_data']->cart_data->taxcloud_tax_amount = $this->tax_amount;
			$GLOBALS['ec_cart_data']->cart_data->taxcloud_address_verified = $this->address_verified;
			$GLOBALS['ec_cart_data']->save_session_to_db( );
		}
	}
	
	// Action from ec_order
	public function add_tax_cloud_order( $order_id, $cart, $order_totals, $user, $payment_type ){
		
		if( get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ){
			
			$db = new ec_db( );
		
			$dateTimeauthorizedDate = gmdate(DATE_ATOM);
			$dateTimecapturedDate 	= gmdate(DATE_ATOM);
			
			$customerID = $GLOBALS['ec_cart_data']->cart_data->user_id;
			if( $GLOBALS['ec_cart_data']->cart_data->is_guest )
				$customerID = $GLOBALS['ec_cart_data']->cart_data->guest_key;
			
			$parameters = array( 	'apiLoginID' 		=> get_option( 'ec_option_tax_cloud_api_id' ),
									'apiKey' 			=> get_option( 'ec_option_tax_cloud_api_key' ),
									'customerID' 		=> $customerID,
									'cartID' 			=> $GLOBALS['ec_cart_data']->ec_cart_id,
									'orderID' 			=> $order_id,
									'dateAuthorized' 	=> $dateTimeauthorizedDate,
									'dateCaptured' 		=> $dateTimecapturedDate );
			
            $request = new WP_Http;
            $response = $request->request( 
                $this->get_tax_cloud_url( ) . "Authorized", 
                array( 
                    'method' => 'POST', 
                    'body' => json_encode( $parameters ),
                    'headers' => array( 
                        'Content-Type' => 'application/json',
                        'Content-Length' => strlen( json_encode( $parameters ) ) 
                    ),
                    'timeout' => 30
                )
            );
            if( is_wp_error( $response ) ){
                $error_message = $response->get_error_message( );
                $db->insert_response( 0, 1, "TAX CLOUD Insert Order ERROR", $error_message );
                return;
            }
            $db->insert_response( 0, 0, "Tax Cloud Insert Order", print_r( $response, true ) );
			
			$GLOBALS['ec_cart_data']->cart_data->taxcloud_tax_amount = "";
			$GLOBALS['ec_cart_data']->cart_data->taxcloud_address_verified = 0;
			$GLOBALS['ec_cart_data']->cart_data->taxcloud_address_last_verified = '';
			$GLOBALS['ec_cart_data']->save_session_to_db( );
			
		}
		
	}
	
	public function approve_tax_cloud_order( $order_id ){
		
		if( get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ){
			
			$db = new ec_db( );
			
			$parameters = array( 	'apiLoginID' 		=> get_option( 'ec_option_tax_cloud_api_id' ),
									'apiKey' 			=> get_option( 'ec_option_tax_cloud_api_key' ),
									'orderID' 			=> $order_id );
			
            $request = new WP_Http;
            $response = $request->request( 
                $this->get_tax_cloud_url( ) . "Captured", 
                array( 
                    'method' => 'POST', 
                    'body' => json_encode( $parameters ),
                    'headers' => array( 
                        'Content-Type' => 'application/json',
                        'Content-Length' => strlen( json_encode( $parameters ) ) 
                    ),
                    'timeout' => 30
                )
            );
            if( is_wp_error( $response ) ){
                $error_message = $response->get_error_message( );
                $db->insert_response( 0, 1, "TAX CLOUD Approve Order ERROR", $error_message );
                return;
            }
            $db->insert_response( 0, 0, "Tax Cloud Approve Order", print_r( $response, true ) );
		}
		
	}
	
	public function refund_tax_cloud_order( $order_id ){
		
		if( get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ){
			
			$db = new ec_db( );
			$dateTimereturnedDate = gmdate(DATE_ATOM);
			
			$parameters = array( 	'apiLoginID' 		=> get_option( 'ec_option_tax_cloud_api_id' ),
									'apiKey' 			=> get_option( 'ec_option_tax_cloud_api_key' ),
									'orderID' 			=> $order_id,
									'returnedDate' 		=> $dateTimereturnedDate );
			
            $request = new WP_Http;
            $response = $request->request( 
                $this->get_tax_cloud_url( ) . "Returned", 
                array( 
                    'method' => 'POST', 
                    'body' => json_encode( $parameters ),
                    'headers' => array( 
                        'Content-Type' => 'application/json',
                        'Content-Length' => strlen( json_encode( $parameters ) ) 
                    ),
                    'timeout' => 30
                )
            );
            if( is_wp_error( $response ) ){
                $error_message = $response->get_error_message( );
                $db->insert_response( 0, 1, "TAX CLOUD Refund Order ERROR", $error_message );
                return;
            }
            $db->insert_response( 0, 0, "Tax Cloud Refund Order", print_r( $response, true ) );
		
		}
		
	}
	
	private function get_tax_cloud_url( ){
		return "https://api.taxcloud.net/1.0/Taxcloud/";
	}
	
	public function tax_cloud_address_verification() {
		$db = new ec_db( );
		
		$zip_split = explode( '-', $GLOBALS['ec_cart_data']->cart_data->shipping_zip );
		$zip5 = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;
		if( count( $zip_split ) > 0 ) {
			$zip5 = $zip_split[0];
		}
		$zip4 = "";
		if ( count( $zip_split ) > 1 ) {
			$zip4 = $zip_split[1];
		}

		$parameters = array( 
			'apiLoginID' => get_option( 'ec_option_tax_cloud_api_id' ),
			'apiKey' => get_option( 'ec_option_tax_cloud_api_key' ),
			'Address1' => $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1,
			'Address2' => $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2,
			'City' => $GLOBALS['ec_cart_data']->cart_data->shipping_city,
			'State' => $GLOBALS['ec_cart_data']->cart_data->shipping_state,
			'Zip5' => $zip5,
			'Zip4' => $zip4
		);

		$taxcloud_new_verify = json_encode( $parameters );
		if ( $GLOBALS['ec_cart_data']->cart_data->taxcloud_address_last_verified == $taxcloud_new_verify ) {
			$this->address_verified = true;
			return true;
		} else {
			$GLOBALS['ec_cart_data']->cart_data->taxcloud_address_last_verified = $taxcloud_new_verify;
		}

		$request = new WP_Http;
		$response = $request->request( 
			$this->get_tax_cloud_url( ) . "VerifyAddress", 
			array( 
				'method' => 'POST', 
				'body' => json_encode( $parameters ),
				'headers' => array( 
					'Content-Type' => 'application/json',
					'Content-Length' => strlen( json_encode( $parameters ) ) 
				),
				'timeout' => 30
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			$db->insert_response( 0, 1, "TAX CLOUD VerifyAddress ERROR", $error_message );
			return;
		}
		$db->insert_response( 0, 0, "Tax Cloud VerifyAddress", print_r( $response, true ) );
		$response = json_decode( $response['body'] );

		if ( $response->ErrNumber == 0 ) {
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $response->Zip5 . '-' . $response->Zip4;
			if( $GLOBALS['ec_cart_data']->cart_data->shipping_selector == 'false' ){
				$GLOBALS['ec_cart_data']->cart_data->billing_zip = $response->Zip5 . '-' . $response->Zip4;
			}
			$parameters['Zip5'] = $response->Zip5;
			$parameters['Zip4'] = $response->Zip4;
			$taxcloud_new_verify = json_encode( $parameters );
			$GLOBALS['ec_cart_data']->cart_data->taxcloud_address_last_verified = $taxcloud_new_verify;
			$this->address_verified = true;	
			
		} else {
			$this->address_verified = true;	
		}
		return $this->address_verified;
	}

	private function get_tax_cloud_cartitems( $shipping_total, $discount_total ) {
		global $wpdb;
		$cartitems = array();

		$i = 0;
		$onetime_added = 0;
		if ( isset( $this->subscription_product ) ) {
			$unit_price = $this->subscription_product->price + $this->subscription_product_option_price - ( $this->subscription_product_discount / $this->subscription_product_quantity ) + ( $this->subscription_product_option_onetime / $this->subscription_product_quantity );
			$cartitems[] = array(
				'Index' => $i,
				'TIC' => $this->subscription_product->TIC,
				'ItemID' => $this->subscription_product->model_number,
				'Price' => $unit_price,
				'Qty' => $this->subscription_product_quantity
			);
			$i++;

		} else {
			$cart = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_tempcart.quantity, ec_product.price, ec_product.model_number, ec_product.TIC FROM ec_tempcart LEFT JOIN ec_product ON ec_product.product_id = ec_tempcart.product_id WHERE ec_tempcart.session_id = %s AND ec_product.is_taxable', $GLOBALS['ec_cart_data']->ec_cart_id ) );
			$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
			if ( count( $cart->cart ) == 0 ) {
				return $cartitems;
			}
			$discount_remaining = $discount_total;
			for ( $i = 0; $i < count( $cart->cart ); $i++ ) {
				if ( $discount_remaining <= 0 ) {
					$unit_price = $cart->cart[ $i ]->unit_price;

				} else if ( $discount_remaining > ( $cart->cart[ $i ]->unit_price * $cart->cart[ $i ]->quantity ) ) {
					$discount_remaining = $discount_remaining - ( $cart->cart[ $i ]->quantity * ( $cart->cart[ $i ]->unit_price - .01 ) );
					$unit_price = .01;

				} else {
					$unit_price = ( $cart->cart[ $i ]->unit_price - ( $discount_remaining / $cart->cart[ $i ]->quantity ) );
					$discount_remaining = 0;
				}
				
				$cartitems[] = array(
					'Index' => $i + $onetime_added,
					'TIC' => $cart->cart[ $i ]->TIC,
					'ItemID' => $cart->cart[ $i ]->model_number,
					'Price' => $unit_price,
					'Qty' => $cart->cart[ $i ]->quantity
				 );

				if ( $cart->cart[ $i ]->options_price_onetime > 0 ) {
					$onetime_added++;
					$cartitems[] = array(
						'Index' => $i + $onetime_added,
						'TIC' => $cart->cart[$i]->TIC,
						'ItemID' => $cart->cart[$i]->model_number,
						'Price' => $cart->cart[$i]->options_price_onetime,
						'Qty' => 1
					);
				}
			}
		}

		if ( $shipping_total > 0 ) {
			$cartitems[] = array(
				'Index' => $i + $onetime_added,
				'TIC' => '11010',
				'ItemID' => 'Shipping',
				'Price' => $shipping_total,
				'Qty' => 1
			);
		}

		$ec_db = new ec_db();
		$ec_db->insert_response( 0, 0, 'TaxCloud Cart Items', print_r( $cartitems, true ) );
		return $cartitems;
	}

	private function get_tax_cloud_origin( ){
		
		$zip_split = explode( '-', get_option( 'ec_option_tax_cloud_zip' ) );
		$zip5 = get_option( 'ec_option_tax_cloud_zip' );
		if( count( $zip_split ) > 0 )
			$zip5 = $zip_split[0];
		
		$zip4 = "";
		if( count( $zip_split ) > 1 )
			$zip4 = $zip_split[1];
		
		$origin = array(	"Address1"	=> get_option( 'ec_option_tax_cloud_address' ),
							"City"		=> get_option( 'ec_option_tax_cloud_city' ),
							"State"		=> get_option( 'ec_option_tax_cloud_state' ),
							"Zip5"		=> $zip5,
							"Zip4"		=> $zip4
						 );
		return $origin;
							 
	}
	
	private function get_tax_cloud_destination( ){
		
		$zip_split = explode( '-', $GLOBALS['ec_cart_data']->cart_data->shipping_zip );
		$zip5 = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;
		if( count( $zip_split ) > 0 )
			$zip5 = $zip_split[0];
		
		$zip4 = "";
		if( count( $zip_split ) > 1 )
			$zip4 = $zip_split[1];
		
		$parameters = array( 	"Address1"		=> $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1,
								"Address2"		=> $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2,
								"City"			=> $GLOBALS['ec_cart_data']->cart_data->shipping_city,
								"State"			=> $GLOBALS['ec_cart_data']->cart_data->shipping_state,
								"Zip5"			=> $zip5,
								"Zip4"			=> $zip4
							);
		return $parameters; 
		
		
	}
	
}
endif; // End if class_exists check


function wpeasycart_taxcloud( ){

	return ec_taxcloud::instance( );

}

$GLOBALS['wpeasycart_taxcloud'] = wpeasycart_taxcloud( );

?>