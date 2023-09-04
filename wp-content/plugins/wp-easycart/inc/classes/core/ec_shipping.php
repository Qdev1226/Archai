<?php

class ec_shipping {
	protected $mysqli;
	public $shipper;
	public $has_live_rates;
	public $change_shipping_js_func = 'ec_cart_shipping_method_change';

	private $fraktjakt;

	private $display_type;

	private $price_based = array();
	private $weight_based = array();
	private $method_based = array();
	private $quantity_based = array();
	private $percentage_based = array();
	private $live_based = array();
	private $fraktjakt_shipping_options;

	private $handling;

	public $subtotal;
	private $weight;
	private $width;
	private $height;
	private $length;
	private $quantity;
	private $express_price;
	private $ship_express;
	private $destination_zip;
	private $destination_state;
	private $destination_country;

	private $cart;

	public $shipping_method;

	public $shipping_promotion_text;

	private $freeshipping;

	function __construct( $subtotal, $weight, $quantity = 1, $display_type = 'RADIO', $freeshipping = false, $length = 1, $width = 1, $height = 1, $cart = array() ) {
		$this->mysqli = new ec_db();
		$this->shipping_method = apply_filters( 'wp_easycart_shipping_method', $GLOBALS['ec_setting']->get_shipping_method() );

		$this->cart = $cart;

		$email_user = '';

		if ( $this->shipping_method == 'live' ) {
			if ( class_exists( 'ec_shipper' ) ) {
				$this->shipper = new ec_shipper();
			} else {
				$this->shipping_method = 'price';
			}
		}

		$this->freeshipping = $freeshipping;

		if ( get_option( 'ec_option_use_shipping' ) ) {
			$setting_row = $GLOBALS['ec_setting']->setting_row;
			$this->handling = $setting_row->shipping_handling_rate;
			$shipping_rows = $this->mysqli->get_shipping_data();

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) && $GLOBALS['ec_cart_data']->cart_data->shipping_zip != '' ) {
				$this->destination_zip = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;

			} else if ( isset( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip ) && $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip != '' ) {
				$this->destination_zip = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip;

			} else if ( $GLOBALS['ec_user'] && $GLOBALS['ec_user']->shipping && $GLOBALS['ec_user']->shipping->zip ) {
				$this->destination_zip = $GLOBALS['ec_user']->shipping->zip;
			}

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) && $GLOBALS['ec_cart_data']->cart_data->shipping_state != '' ) {
				$this->destination_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;

			} else if ( isset( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_state ) && $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_state != '' ) {
				$this->destination_state = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_state;

			} else if ( $GLOBALS['ec_user'] && $GLOBALS['ec_user']->shipping && $GLOBALS['ec_user']->shipping->state ) {
				$this->destination_state = $GLOBALS['ec_user']->shipping->state;
			}

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ) {
				$this->destination_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;

			} else if ( isset( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country != '' ) {
				$this->destination_country = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country;

			} else if ( $GLOBALS['ec_user'] && $GLOBALS['ec_user']->shipping && $GLOBALS['ec_user']->shipping->country ) {
				$this->destination_country = $GLOBALS['ec_user']->shipping->country;
			}

			if ( $this->shipping_method == 'fraktjakt' ) {
				$this->fraktjakt = new ec_fraktjakt();
				$this->fraktjakt_shipping_options = $this->fraktjakt->get_shipping_options();
			}

			$zone_obj = $this->mysqli->get_zone_ids( $this->destination_country, $this->destination_state );
			$zones = array();
			foreach ( $zone_obj as $zone ) {
				$zones[] = $zone->zone_id;
			}

			foreach ( $shipping_rows as $shipping_row ) {

				if ( $shipping_row->is_price_based && $shipping_row->zone_id > 0 ) {
					if ( in_array( $shipping_row->zone_id, $zones ) ) {
						array_push( $this->price_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
					}
				} else if ( $shipping_row->is_price_based ) {
					array_push( $this->price_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
				} else if ( $shipping_row->is_weight_based && $shipping_row->zone_id > 0 ) {
					if ( in_array( $shipping_row->zone_id, $zones ) ) {
						array_push( $this->weight_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
					}
				} else if ( $shipping_row->is_weight_based ) {
					array_push( $this->weight_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
				} else if ( $shipping_row->is_method_based && $shipping_row->zone_id > 0 ) {
					if ( in_array( $shipping_row->zone_id, $zones ) ) {
						array_push( $this->method_based, array( $shipping_row->shipping_rate, wp_easycart_language()->convert_text( $shipping_row->shipping_label ), $shipping_row->shippingrate_id, $shipping_row->free_shipping_at ) );
					}
				} else if ( $shipping_row->is_method_based ) {
					array_push( $this->method_based, array( $shipping_row->shipping_rate, wp_easycart_language()->convert_text( $shipping_row->shipping_label ), $shipping_row->shippingrate_id, $shipping_row->free_shipping_at ) );
				} else if ( $shipping_row->is_quantity_based && $shipping_row->zone_id > 0 ) {
					if ( in_array( $shipping_row->zone_id, $zones ) ) {
						array_push( $this->quantity_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
					}
				} else if ( $shipping_row->is_quantity_based ) {
					array_push( $this->quantity_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
				} else if ( $shipping_row->is_percentage_based && $shipping_row->zone_id > 0 ) {
					if ( in_array( $shipping_row->zone_id, $zones ) ) {
						array_push( $this->percentage_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
					}
				} else if ( $shipping_row->is_percentage_based ) {
					array_push( $this->percentage_based, array( $shipping_row->trigger_rate, $shipping_row->shipping_rate ) );
				} else if ( $this->is_live_based( $shipping_row ) && $shipping_row->zone_id > 0 ) {
					if ( in_array( $shipping_row->zone_id, $zones ) ) {
						array_push( $this->live_based, array( $shipping_row->shipping_code, wp_easycart_language()->convert_text( $shipping_row->shipping_label ), $shipping_row->shippingrate_id, $this->get_live_type( $shipping_row ), $shipping_row->shipping_override_rate, $shipping_row->free_shipping_at ) );
					}
				} else if ( $this->is_live_based( $shipping_row ) ) {
					array_push( $this->live_based, array( $shipping_row->shipping_code, wp_easycart_language()->convert_text( $shipping_row->shipping_label ), $shipping_row->shippingrate_id, $this->get_live_type( $shipping_row ), $shipping_row->shipping_override_rate, $shipping_row->free_shipping_at ) );
				}
			}

			$this->live_based = apply_filters( 'wpeasycart_live_based_codes', $this->live_based );
			$this->method_based = apply_filters( 'wpeasycart_method_based_shipping', $this->method_based );

			$this->subtotal = $subtotal - $GLOBALS['wpeasycart_current_coupon_discount'];
			$this->weight = $weight;
			$this->width = $width;
			$this->height = $height;
			$this->length = $length;
			$this->quantity = $quantity;
			$this->express_price = (float) number_format( $GLOBALS['ec_setting']->get_setting( 'shipping_expedite_rate' ), 2, '.', '' );
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->expedited_shipping ) && $GLOBALS['ec_cart_data']->cart_data->expedited_shipping != '' ) {
				$this->ship_express = $GLOBALS['ec_cart_data']->cart_data->expedited_shipping;
			} else {
				$this->ship_express = false;
			}

			$this->display_type = $display_type;
		}
	}

	private function is_live_based( $shipping_row ) {
		$is_live_based = false;
		if ( $shipping_row->is_ups_based || $shipping_row->is_usps_based || $shipping_row->is_fedex_based || $shipping_row->is_auspost_based || $shipping_row->is_dhl_based || $shipping_row->is_canadapost_based ) {
			$is_live_based = true;
		}
		return apply_filters( 'wp_easycart_shipping_is_live_based', $is_live_based );
	}

	private function get_live_type( $shipping_row ) {
		$live_type = 'none';
		if ( $shipping_row->is_ups_based ) {
			$live_type = 'ups';
		} else if ( $shipping_row->is_usps_based ) {
			$live_type = 'usps';
		} else if ( $shipping_row->is_fedex_based ) {
			$live_type = 'fedex';
		} else if ( $shipping_row->is_auspost_based ) {
			$live_type = 'auspost';
		} else if ( $shipping_row->is_dhl_based ) {
			$live_type = 'dhl';
		} else if ( $shipping_row->is_canadapost_based ) {
			$live_type = 'canadapost';
		}
		return apply_filters( 'wp_easycart_shipping_live_type', $live_type );
	}

	public function get_shipping_options( $standard_text, $express_text ) {
		ob_start();
		$this->print_shipping_options( $standard_text, $express_text );
		return ob_get_clean();
	}

	public function print_shipping_options( $standard_text, $express_text ) {
		if ( apply_filters( 'wp_easycart_shipping_should_print_custom_options', false ) ) {
			do_action( 'wp_easycart_shipping_print_custom_shipping_options' );

		} else if ( $this->shipping_method == 'price' ) {
			$this->get_price_based_shipping_options( $standard_text, $express_text );

		} else if ( $this->shipping_method == 'weight' ) {
			$this->get_weight_based_shipping_options( $standard_text, $express_text );

		} else if ( $this->shipping_method == 'method' ) {
			$this->get_method_based_shipping_options( $standard_text, $express_text );

		} else if ( $this->shipping_method == 'quantity' ) {
			$this->get_quantity_based_shipping_options( $standard_text, $express_text );

		} else if ( $this->shipping_method == 'percentage' ) {
			$this->get_percentage_based_shipping_options( $standard_text, $express_text );

		} else if ( $this->shipping_method == 'live' ) {
			$this->get_live_based_shipping_options( $standard_text, $express_text );
			if ( ! $this->has_live_rates ) {
				echo '<div class="ec_cart_no_shipping_methods">' . wp_easycart_language()->get_text( 'cart_shipping_method', 'cart_shipping_no_rates_available' ) . '</div>';
			}
		} else if ( $this->shipping_method == 'fraktjakt' ) {
			$this->get_fraktjakt_based_shipping_options();
		}
	}

	private function get_price_based_rate() {
		for ( $i = 0; $i < count( $this->price_based ); $i++ ) {
			if ( $this->subtotal >= $this->price_based[ $i ][0] ) {
				return $this->price_based[ $i ][1];
			}
		}
	}

	private function get_weight_based_rate() {
		 for ( $i = 0; $i < count( $this->weight_based ); $i++ ) {
			if ( $this->weight >= $this->weight_based[ $i ][0] ) {
				return $this->weight_based[ $i ][1];
			}
		}
	}

	private function get_quantity_based_rate() {
		 for ( $i = 0; $i < count( $this->quantity_based ); $i++ ) {
			if ( $this->quantity >= $this->quantity_based[ $i ][0] ) {
				return $this->quantity_based[ $i ][1];
			}
		}
	}

	private function get_percentage_based_rate() {
		 for ( $i = 0; $i < count( $this->percentage_based ); $i++ ) {
			if ( $this->subtotal >= $this->percentage_based[ $i ][0] ) {
				return $this->subtotal * $this->percentage_based[ $i ][1] / 100;
			}
		}
	}

	public function get_shipping_rate_data( $standard_text, $express_text, $multiplier = 100 ) {
		$rates = array();
		$this->handling = 0;
		for( $i=0; $i<count( $this->cart ); $i++ ) {
			$this->handling += $this->cart[$i]->handling_price;
			$this->handling += ( $this->cart[$i]->handling_price_each * $this->cart[$i]->quantity );
		}
		if ( $this->shipping_method == 'price' || $this->shipping_method == 'weight' || $this->shipping_method == 'quantity' || $this->shipping_method == 'percentage' ) {
			if ( $this->shipping_method == 'price' ) {
				$standard_price = $this->get_price_based_rate();
			} else if ( $this->shipping_method == 'weight' ) {
				$standard_price = $this->get_weight_based_rate();
			} else if ( $this->shipping_method == 'quantity' ) {
				$standard_price = $this->get_quantity_based_rate();
			} else if ( $this->shipping_method == 'percentage' ) {
				$standard_price = $this->get_percentage_based_rate();
			}
			$coupon_code = '';
			if ( $GLOBALS['ec_cart_data']->cart_data->coupon_code != '' ) {
				$coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
			}
			$discount = new ec_discount( (object) array( 'cart' => array() ), 0.00, $standard_price, $coupon_code, '', 0 );
			$shipping_discount = $discount->shipping_discount;
			$standard_price += $this->handling - $shipping_discount;

			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'free' ) {
				if ( get_option( 'ec_option_add_local_pickup' ) ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ),
						'amount' => (int) 0,
						'id' => 'free',
					);
				}
			}

			if ( $GLOBALS['ec_cart_data']->cart_data->expedited_shipping == 'shipexpress' ) {
				if ( $this->express_price > 0 ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ),
						'amount' => ( $multiplier == 100 ) ? (int) number_format( ( $standard_price + $this->express_price ) * $multiplier, 0, '', '' ) : number_format( $standard_price + $this->express_price, 2, '.', '' ),
						'id' => 'shipexpress',
					);
				}
			}

			$rates[] = (object) array(
				'label' => $standard_text,
				'amount' => ( $multiplier == 100 ) ? (int) number_format( $standard_price * $multiplier, 0, '', '' ) : number_format( $standard_price, 2, '.', '' ),
				'id' => 'standard',
			);

			if ( $GLOBALS['ec_cart_data']->cart_data->expedited_shipping != 'shipexpress' ) {
				if ( $this->express_price > 0 ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ),
						'amount' => ( $multiplier == 100 ) ? (int) number_format( ( $standard_price + $this->express_price ) * $multiplier, 0, '', '' ) : number_format( $standard_price + $this->express_price, 2, '.', '' ),
						'id' => 'shipexpress',
					);
				}
			}

			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != 'free' ) {
				if ( get_option( 'ec_option_add_local_pickup' ) ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ),
						'amount' => (int) 0,
						'id' => 'free',
					);
				}
			}
		} else if ( $this->shipping_method == 'method' ) {
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'free' ) {
				if ( get_option( 'ec_option_add_local_pickup' ) ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ),
						'amount' => (int) 0,
						'id' => 'free',
					);
				}
			}

			for ( $i = 0; $i < count( $this->method_based ); $i++ ) {
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->method_based[ $i ][2] ) {
					if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
						$rate = 0;
					} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
						$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
					} else {
						$rate = $this->method_based[ $i ][0] + $this->handling;
					}
					$rates[] = (object) array(
						'label' => $this->method_based[ $i ][1],
						'amount' => ( $multiplier == 100 ) ? (int) number_format( $rate * $multiplier, 0, '', '' ) : number_format( $rate, 2, '.', '' ),
						'id' => $this->method_based[ $i ][2],
					);
				}
			}

			for ( $i = 0; $i < count( $this->method_based ); $i++ ) {
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != $this->method_based[ $i ][2] ) {
					if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
						$rate = 0;
					} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
						$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
					} else {
						$rate = $this->method_based[ $i ][0] + $this->handling;
					}
					$rates[] = (object) array(
						'label' => $this->method_based[ $i ][1],
						'amount' => ( $multiplier == 100 ) ? (int) number_format( $rate * $multiplier, 0, '', '' ) : number_format( $rate, 2, '.', '' ),
						'id' => $this->method_based[ $i ][2],
					);
				}
			}

			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != 'free' ) {
				if ( get_option( 'ec_option_add_local_pickup' ) ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ),
						'amount' => (int) 0,
						'id' => 'free',
					);
				}
			}

		} else if ( $this->shipping_method == 'live' ) {
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'free' ) {
				if ( get_option( 'ec_option_add_local_pickup' ) ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ),
						'amount' => (int) 0,
						'id' => 'free',
					);
				}
			}

			$ret_string = '';
			$count = 0;
			for ( $i = 0; $i < count( $this->live_based ); $i++ ) {
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->live_based[ $i ][2] ) {
					$service_days = 0;
					if ( $this->live_based[ $i ][4] != null && get_option( 'ec_option_live_override_always' ) ) {
						if ( $this->live_based[ $i ][4] == 0 ) {
							$rate = 'FREE';
						} else {
							$rate = $this->live_based[ $i ][4];
						}
					} else if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) {
						$rate = 'FREE';
					} else {
						$rate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
						$service_days = $this->shipper->get_service_days( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
					}

					if ( $rate != 'ERROR' ) {
						ob_start();
						$this->get_live_based_radio( $count, $i, $rate, $service_days );
						$ret_string .= ob_get_clean();

						if ( $rate == 'FREE' || $rate == 'free' ) {
							$rate = 0;
						} else {
							$rate = floatval( $rate ) + floatval( $this->handling );
						}
						$id = $this->live_based[ $i ][2];
						$label = $this->live_based[ $i ][1];
						if ( $service_days > 0 && get_option( 'ec_option_show_delivery_days_live_shipping' ) ) {
							$label .= ' (' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'delivery_in' ) . ' ' . $service_days . '-' . ($service_days+1) . ' ' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'delivery_days' ) . ')';
						}
						if ( $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
							if ( $this->live_based[ $i ][4] == 0 ) {
								$rate = 'FREE';
							} else {
								$rate = $this->live_based[ $i ][4];
							}
						}

						$rates[] = (object) array(
							'label' => $label,
							'amount' => ( $multiplier == 100 ) ? (int) number_format( $rate * $multiplier, 0, '', '' ) : number_format( $rate, 2, '.', '' ),
							'id' => $id,
						);
					}
				}
			}

			 for ( $i = 0; $i < count( $this->live_based ); $i++ ) {
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != $this->live_based[ $i ][2] ) {
					$service_days = 0;
					if ( $this->live_based[ $i ][4] != null && get_option( 'ec_option_live_override_always' ) ) {
						if ( $this->live_based[ $i ][4] == 0 ) {
							$rate = 'FREE';
						} else {
							$rate = $this->live_based[ $i ][4];
						}
					} else if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) {
						$rate = 'FREE';

					} else {
						$rate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
						$service_days = $this->shipper->get_service_days( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
					}

					if ( $rate != 'ERROR' ) {
						ob_start();
						$this->get_live_based_radio( $count, $i, $rate, $service_days );
						$ret_string .= ob_get_clean();

						if ( $rate == 'FREE' || $rate == 'free' ) {
							$rate = 0;
						} else {
							$rate = floatval( $rate ) + floatval( $this->handling );
						}
						$id = $this->live_based[ $i ][2];
						$label = $this->live_based[ $i ][1];
						if ( $service_days > 0 && get_option( 'ec_option_show_delivery_days_live_shipping' ) ) {
							$label .= ' (' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'delivery_in' ) . ' ' . $service_days . '-' . ($service_days+1) . ' ' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'delivery_days' ) . ')';
						}
						if ( $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
							if ( $this->live_based[ $i ][4] == 0 ) {
								$rate = 'FREE';
							} else {
								$rate = $this->live_based[ $i ][4];
							}
						}

						$rates[] = (object) array(
							'label' => $label,
							'amount' => ( $multiplier == 100 ) ? (int) number_format( $rate * $multiplier, 0, '', '' ) : number_format( $rate, 2, '.', '' ),
							'id' => $id
						);
					}
				}
			}

			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != 'free' ) {
				if ( get_option( 'ec_option_add_local_pickup' ) ) {
					$rates[] = (object) array(
						'label' => wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ),
						'amount' => (int) 0,
						'id' => 'free'
					);
				}
			}

		} else if ( $this->shipping_method == 'fraktjakt' ) {

			foreach ( $this->fraktjakt_shipping_options as $shipping_option ) {
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == $shipping_option['id'] ) {
					$id = $shipping_option['id'];
					$label = $shipping_option['description'];
					$rate = $shipping_option['price'] + $this->handling;
					$rates[] = (object) array(
						'label' => $label,
						'amount' => ( $multiplier == 100 ) ? (int) number_format( $rate * $multiplier, 0, '', '' ) : number_format( $rate, 2, '.', '' ),
						'id' => $id
					);
				}
			}

			foreach ( $this->fraktjakt_shipping_options as $shipping_option ) {
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != $shipping_option['id'] ) {
					$id = $shipping_option['id'];
					$label = $shipping_option['description'];
					$rate = $shipping_option['price'] + $this->handling;
					$rates[] = (object) array(
						'label' => $label,
						'amount' => ( $multiplier == 100 ) ? (int) number_format( $rate * $multiplier, 0, '', '' ) : number_format( $rate, 2, '.', '' ),
						'id' => $id
					);
				}
			}
		}

		$rates = apply_filters( 'wp_easycart_shipping_get_rate_data', $rates );
		return $rates;

	}

	private function get_price_based_shipping_options( $standard_text, $express_text ) {
		if ( count( $this->price_based ) > 0 ) {
			$found = false;
			$price_based_count = count( $this->price_based );
			for ( $i = 0; $i < $price_based_count && ! $found; $i++) {
				if ( $this->subtotal >= $this->price_based[ $i ][0] ) {
					$this->get_single_shipping_price_content( $standard_text, $express_text, apply_filters( 'wp_easycart_trigger_rate', $this->price_based[ $i ][1], 'price' ) );
					$found = true;
				}
			}
			if ( ! $found ) {
				echo '<div class="ec_cart_shipping_method_row">' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_error' ) . '</div>';
			}
		} else {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row">' . esc_attr__( 'Shipping Rate Setup ERROR: Please visit the EasyCart Admin -> Store Admin -> Rates and add at least one price trigger. If you have done this, check to ensure no gaps in triggers.', 'wp-easycart' ) . '</div>';
		}
	}

	private function get_weight_based_shipping_options( $standard_text, $express_text ) {
		if ( count( $this->weight_based ) > 0 ) {
			$found = false;
			$weight_based_count = count( $this->weight_based );
			for ( $i = 0; $i < $weight_based_count && ! $found; $i++ ) {
				if ( $this->weight >= $this->weight_based[ $i ][0] ) {
					$this->get_single_shipping_price_content( $standard_text, $express_text, apply_filters( 'wp_easycart_trigger_rate', $this->weight_based[ $i ][1], 'weight' ) );
					$found = true;
				}
			}
			if ( ! $found ) {
				echo '<div class="ec_cart_shipping_method_row">' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_error' ) . '</div>';
			}
		} else {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row">' . esc_attr__( 'Shipping Rate Setup ERROR: Please visit the WP EasyCart -> Settings -> Shipping Rates and add at least one weight trigger. If you have done this, check to ensure no gaps in triggers.', 'wp-easycart' ) . '</div>';
		}
	}

	private function get_method_based_shipping_options( $standard_text, $express_text ) {
		if ( count( $this->method_based ) > 0 ) { 
			if ( get_option( 'ec_option_add_local_pickup' ) ) {
				$this->method_based[] = array(
					'0',
					wp_easycart_language()->convert_text( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ) ),
					'free',
					0
				);
				$this->get_method_based_radio( count( $this->method_based ) - 1 );
				array_pop( $this->method_based );
			}

			echo wp_easycart_escape_html( apply_filters( 'wp_easycart_method_rate_pre', '' ) );
			do_action( 'wp_easycart_method_rate_pre_rates' );

			if ( $this->display_type == 'SELECT' ) {
				echo '<select name="ec_cart_shipping_method" onchange="' . $this->change_shipping_js_func . '();">';
			}

			 for ( $i=0; $i<count($this->method_based); $i++) {
				if ( $this->display_type == 'RADIO' ) {
					$this->get_method_based_radio( $i );
				} else if ( $this->display_type == 'SELECT' ) {
					$this->get_method_based_select( $i );
				} else {
					$this->print_method_based_div( $i );
				}
			}

			if ( $this->display_type == 'SELECT' ) {
				echo '</select>';
			}

		} else {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row">' . esc_attr__( 'Shipping Rate Setup ERROR: Please visit the WP EasyCart -> Settings -> Shipping Rates and add at least one shipping method.', 'wp-easycart' ) . '</div>';
		}
	}

	private function get_quantity_based_shipping_options( $standard_text, $express_text ) {
		if ( count( $this->quantity_based ) > 0 ) {
			$found = false;
			$quantity_based_count = count( $this->quantity_based );
			for ( $i = 0; $i < $quantity_based_count && ! $found; $i++ ) {
				if ( $this->quantity >= $this->quantity_based[ $i ][0] ) {
					$this->get_single_shipping_price_content( $standard_text, $express_text, apply_filters( 'wp_easycart_trigger_rate', $this->quantity_based[ $i ][1], 'quantity' ) );
					$found = true;
				}
			}
			if ( ! $found ) {
				echo '<div class="ec_cart_shipping_method_row">' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_error' ) . '</div>';
			}
		} else {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row">' . esc_attr__( 'Shipping Rate Setup ERROR: Please visit the WP EasyCart -> Settings -> Shipping Rates and add at least one quantity trigger. If you have done this, check to ensure no gaps in triggers.', 'wp-easycart' ) . '</div>';
		}
	}

	private function get_percentage_based_shipping_options( $standard_text, $express_text ) {
		if ( count( $this->percentage_based ) > 0 ) {
			$found = false;
			$percentage_based_count = count( $this->percentage_based );
			for ( $i = 0; $i < $percentage_based_count && ! $found; $i++ ) {
				if ( $this->subtotal >= $this->percentage_based[ $i ][0] ) {
					$this->get_single_shipping_price_content( $standard_text, $express_text, apply_filters( 'wp_easycart_trigger_rate', $this->subtotal * ( $this->percentage_based[ $i ][1] / 100 ), 'percentage' ) );
					$found = true;
				}
			}
			if ( ! $found ) {
				echo '<div class="ec_cart_shipping_method_row">' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_error' ) . '</div>';
			}
		} else {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row">' . esc_attr__( 'Shipping Rate Setup ERROR: Please visit the WP EasyCart -> Settings -> Shipping Rates and add at least one quantity trigger. If you have done this, check to ensure no gaps in triggers.', 'wp-easycart' ) . '</div>';
		}
	}

	private function filter_by_class() {

		$found_count = 0;
		$last_rate_id = 0;
		$allowed_live_rates = array();
		$applicable_rate_ids = $this->mysqli->get_rates_by_class( $this->cart );
		if ( count( $applicable_rate_ids ) ) {
			 for ( $i=0; $i<count( $applicable_rate_ids ); $i++ ) {
				if ( $last_rate_id != $applicable_rate_ids[ $i ]->shipping_rate_id ) {
					$found_count = 0;
					$last_rate_id = $applicable_rate_ids[ $i ]->shipping_rate_id;
				}
				 for ( $j=0; $j<count( $this->cart ); $j++ ) {
					if ( $applicable_rate_ids[ $i ]->shipping_class_id == $this->cart[$j]->shipping_class_id )
						$found_count++;
				}
				if ( $found_count == count( $this->cart ) ) {
					$allowed_live_rates[] = $last_rate_id;
				}
			}
			$new_live_based = array();
			foreach( $this->live_based as $live_based ) {
				if ( in_array( $live_based[2], $allowed_live_rates ) )
					$new_live_based[] = $live_based;
			}
			if ( count( $new_live_based ) == 0 ) { // Need to offer multiple options
				return false;
			} else {
				$this->live_based = $new_live_based;
				return true;
			}
		}

	}

	private function get_live_based_shipping_options_by_class( $standard_text, $express_text ) {

		$last_rate_id = 0;
		$allowed_live_rates = array();
		$new_rate = 0;
		$applicable_rate_ids = $this->mysqli->get_rates_by_class( $this->cart );
		 for ( $i=0; $i<count( $applicable_rate_ids ); $i++ ) {
			if ( $last_rate_id != $applicable_rate_ids[ $i ]->shipping_rate_id ) {
				$found_count = 0;
				$last_rate_id = $applicable_rate_ids[ $i ]->shipping_rate_id;
				$allowed_live_rates[] = $last_rate_id;
			}
		}
		$new_live_based = array();
		foreach( $this->live_based as $live_based ) {
			if ( in_array( $live_based[2], $allowed_live_rates ) )
				$new_live_based[] = $live_based;
		}
		$this->live_based = $new_live_based;

	}

	private function get_live_based_shipping_options_no_rates( $standard_text, $express_text ) {

		print_r( $this->method_based );

	}

	private function get_live_based_shipping_options( $standard_text, $express_text ) {

		$this->has_live_rates = false;

		if ( count( $this->live_based ) > 0 ) { 

			$filter_success = $this->filter_by_class();
			$count = 0;

			if ( $this->display_type == 'SELECT' ) {
				echo '<select name="ec_cart_shipping_method" onchange="' . $this->change_shipping_js_func . '();">';
			}
			if ( get_option( 'ec_option_add_local_pickup' ) ) {
				$this->live_based[] = array(
					'FREE',
					wp_easycart_language()->convert_text( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ) ),
					'free',
					'',
					0,
					0
				);
				$this->get_live_based_radio( 0, count( $this->live_based ) - 1, 'free', 0 );
				array_pop( $this->live_based );
				$count++;
			}

			echo wp_easycart_escape_html( apply_filters( 'wp_easycart_live_rate_pre', '' ) );
			do_action( 'wp_easycart_live_rate_pre_rates' );

			for ( $i=0; $i<count( $this->live_based ); $i++) {
				$service_days = 0;
				if ( $this->live_based[ $i ][4] != null && get_option( 'ec_option_live_override_always' ) ) {
					if ( $this->live_based[ $i ][4] == 0 ) {
						$rate = 'FREE';
					} else {
						$rate = $this->live_based[ $i ][4];
					}
				} else if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) {
					$rate = 'FREE';

				} else {
					$rate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
					$service_days = $this->shipper->get_service_days( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
				}

				if ( $rate != 'ERROR' ) {
					if ( $this->display_type == 'RADIO' ) {
						$this->get_live_based_radio( $count, $i, $rate, $service_days );

					} else if ( $this->display_type == 'SELECT' ) {
						$this->get_live_based_select( $count, $i, $rate );

					} else {
						$this->print_live_based_div( $count, $i, $rate );
					}
					$count++;
				}
			}

			if ( $this->display_type == 'SELECT' ) {
				echo '</select>';
			}
			
			if ( $count > 0 ){
				$this->has_live_rates = true;
			}

		} else {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row">' . esc_attr__( 'Shipping Rate Setup ERROR: Please visit the WP EasyCart -> Settings -> Shipping Rates and add at least one shipping method for your selected live based shipping company. If you have done this and are still seeing this error, then likely there is a setup error in the live based company settings. Feel free to contact us at www.wpeasycart.com to get help troubleshooting.', 'wp-easycart' ) . '</div>';
		}
	}

	public function has_shipping_option() {
		if ( apply_filters( 'wp_easycart_shipping_should_print_custom_options', false ) ) {
			return apply_filters( 'wp_easycart_shipping_has_custom_shipping_options', true );

		} else if ( $this->shipping_method == 'price' ) {
			return ( count( $this->price_based ) > 0 );

		} else if ( $this->shipping_method == 'weight' ) {
			return ( count( $this->weight_based ) > 0 );

		} else if ( $this->shipping_method == 'method' ) {
			return ( count( $this->method_based ) > 0 );

		} else if ( $this->shipping_method == 'quantity' ) {
			return ( count( $this->quantity_based ) > 0 );

		} else if ( $this->shipping_method == 'percentage' ) {
			return ( count( $this->percentage_based ) > 0 );

		} else if ( $this->shipping_method == 'live' ) {
			return ( count( $this->live_based ) > 0 );

		} else if ( $this->shipping_method == 'fraktjakt' ) {
			return true;
		}
	}

	private function get_fraktjakt_based_shipping_options() {
		$i = 0;
		$selected_method = 0;
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' ) {
			$selected_method = $GLOBALS['ec_cart_data']->cart_data->shipping_method;
		}
		foreach ( $this->fraktjakt_shipping_options as $shipping_option ) {
			echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row"><input type="radio" class="no_wrap" name="ec_cart_shipping_method" id="ec_cart_shipping_method" value="' . esc_attr( $shipping_option['id'] ) . '"';
			if ( ( !$selected_method && $i == 0 ) || ( $selected_method == $shipping_option['id'] ) ) {
				echo ' checked="checked"';
			}
			echo '>' . esc_attr( $shipping_option['description'] ) . ' (' . esc_attr( $GLOBALS['currency']->get_symbol() ) . '<span id="ec_cart_standard_shipping_price">' . esc_attr( $GLOBALS['currency']->get_number_only( apply_filters( 'wp_easycart_shipping_price_display', $shipping_option['price'] + $this->handling, $shipping_option['id'] ) ) ) . '</span>)</div>';
			$i++;
		}
	}

	private function get_method_based_radio( $i ) {
		if ( $this->method_based[ $i ][2] == 'free' ) {
			$rate = 0;
		} else if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
			$rate = 0;
		} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
			$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
		} else {
			$rate = $this->method_based[ $i ][0] + $this->handling;
		}
		$is_selected = ( ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == '' && $i==0 ) || ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' && $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->method_based[ $i ][2] ) );
		if ( get_option( 'ec_option_onepage_checkout' ) ) {
			echo '<label class="ec_cart_full_radio">';
		}
		echo '<div class="ec_cart_shipping_method_row';
		if ( $is_selected ) {
			echo ' ec_method_selected';
		}
		echo '">';
		echo '<input type="radio" class="no_wrap" name="ec_cart_shipping_method" value="' . esc_attr( $this->method_based[ $i ][2] ) . '" onchange="' . $this->change_shipping_js_func . '(\'' . esc_attr( $this->method_based[ $i ][2] ) . '\', \'' . esc_attr( $rate ) . '\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\');"';
		if ( $is_selected ) {
			echo ' checked="checked"';
		}
		echo ' /> ' . esc_attr( $this->method_based[ $i ][1] ) . ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->method_based[ $i ][2] ) ) ) . ')</div>';
		if ( get_option( 'ec_option_onepage_checkout' ) ) {
			echo '</label>';
		}
	}

	private function get_method_based_select( $i ) {
		echo '<option value="' . esc_attr( $this->method_based[ $i ][2] ) . '"';
		if ( ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == "" && $i==0 ) || ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->method_based[ $i ][2] ) ) {
			echo ' selected="selected"';
		}
		if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
			$rate = 0;
		} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
			$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
		} else {
			$rate = $this->method_based[ $i ][0] + $this->handling;
		}
		echo '> ' . esc_attr( $this->method_based[ $i ][1] ) . ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->method_based[ $i ][2] ) ) ) . ')</option>';
	}

	private function print_method_based_div( $i ) {
		if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
			$rate = 0;
		} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
			$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
		} else {
			$rate = $this->method_based[ $i ][0] + $this->handling;
		}
		echo '<div class="ec_cart_shipping_method_row" id="' . esc_attr( $this->method_based[ $i ][2] ) . '"> ' . esc_attr( $this->method_based[ $i ][1] ) . ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->method_based[ $i ][2] ) ) ) . ')</div>';
	}

	private function get_method_based_div( $i ) {
		if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
			$rate = 0;
		} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
			$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
		} else {
			$rate = $this->method_based[ $i ][0] + $this->handling;
		}
		return '<div class="ec_cart_shipping_method_row" id="' . esc_attr( $this->method_based[ $i ][2] ) . '"> ' . esc_attr( $this->method_based[ $i ][1] ) . ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->method_based[ $i ][2] ) ) ) . ')</div>';
	}

	private function get_live_based_radio( $count, $i, $rate, $service_days = 0 ) {

		if ( $rate != 'ERROR' ) {
			if ( $rate == 'FREE' || $rate == 'free' ) {
				$rate = 0;
			} else if ( $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
				$rate = $this->live_based[ $i ][4];
			} else {
				$rate = floatval( $rate ) + floatval( $this->handling );
			}
			$is_selected = false;
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' && $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->live_based[ $i ][2] ) {
				$is_selected = true;
			} else if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == "" && $this->get_lowest_live_based_rate() == $this->live_based[ $i ][2] ) {
				$is_selected = true;
			}
			if ( get_option( 'ec_option_onepage_checkout' ) ) {
				echo '<label class="ec_cart_full_radio">';
			}
			echo '<div class="ec_cart_shipping_method_row';
			if ( $is_selected ) {
				echo ' ec_method_selected';
			}
			echo '">';
			echo '<input type="radio" class="no_wrap" name="ec_cart_shipping_method" value="' . esc_attr( $this->live_based[ $i ][2] ) . '" onchange="' . $this->change_shipping_js_func . '(\'' . esc_attr( $this->live_based[ $i ][2] ) . '\', ' . esc_attr( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->live_based[ $i ][2] ) ) . ', \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' ); "';
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == '' && $this->get_lowest_live_based_rate() == $this->live_based[ $i ][2] ) {
				$GLOBALS['ec_cart_data']->cart_data->shipping_method = $this->live_based[ $i ][2];
				$GLOBALS['ec_cart_data']->save_session_to_db();
			}
			if ( $is_selected ) {
				echo ' checked="checked"';
			}
			echo ' /><span class="label">' . esc_attr( $this->live_based[ $i ][1] );
			if ( $service_days > 0 && get_option( 'ec_option_show_delivery_days_live_shipping' ) ) {
				echo ' (' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'delivery_in' ) . ' ' . esc_attr( $service_days ) . '-' . esc_attr( $service_days + 1 ) . ' ' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'delivery_days' ) . ')';
			}
			echo '</span> <span class="price">' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->live_based[ $i ][2] ) ) ) . '</span></div>';
			if ( get_option( 'ec_option_onepage_checkout' ) ) {
				echo '</label>';
			}
		}
	}

	private function get_live_based_select( $count, $i, $rate ) {

		if ( $rate != 'ERROR' ) {
			if ( $rate == 'FREE' ) {
				$rate = 0;
			} else if ( $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
				$rate = $this->live_based[ $i ][4];
			} else {
				$rate = floatval( $rate ) + floatval( $this->handling );
			}
			echo '<option value="' . esc_attr( $this->live_based[ $i ][0] ) . '"';
			if ( ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == '' && $count == 0 ) || ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' && $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->live_based[ $i ][0] ) ) {
				echo ' selected="selected"';
			}
			echo '> ' . esc_attr( $this->live_based[ $i ][1] ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->live_based[ $i ][0] ) ) ) . '</option>';
		}
	}

	private function get_live_based_div( $count, $i, $rate ) {
		if ( $rate != 'ERROR' ) {
			if ( $rate == 'FREE' ) {
				$rate = 0;
			} else if ( $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
				$rate = $this->live_based[ $i ][4];
			} else {
				$rate = floatval( $rate ) + floatval( $this->handling );
			}
			return '<div id="' . esc_attr( $this->live_based[ $i ][0] ) . '"> ' . esc_attr( $this->live_based[ $i ][1] ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->live_based[ $i ][0] ) ) ) . '</div>';
		}
	}

	private function print_live_based_div( $count, $i, $rate ) {
		if ( $rate != 'ERROR' ) {
			if ( $rate == 'FREE' ) {
				$rate = 0;
			} else if ( $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
				$rate = $this->live_based[ $i ][4];
			} else {
				$rate = floatval( $rate ) + floatval( $this->handling );
			}
			echo '<div id="' . esc_attr( $this->live_based[ $i ][0] ) . '"> ' . esc_attr( $this->live_based[ $i ][1] ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->live_based[ $i ][0] ) ) ) . '</div>';
		}
	}

	public function get_lowest_live_based_rate() {
		$lowest_i = 0;
		$lowest = 100000.00;
		$lowest_ship_method = 'ERROR';

		 for ( $i=0; $i<count( $this->live_based ); $i++ ) {

			// Find lowest
			if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
				$lowest_i = $i;
				$lowest = floatval( 0 );
				$lowest_ship_method = $this->live_based[ $i ][2];

			} else if ( $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && get_option( 'ec_option_live_override_always' ) )
				$subrate = $this->live_based[ $i ][4];
			else 
				$subrate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );

			if ( $subrate != 'ERROR' && floatval( $subrate ) < $lowest ) {
				if ( $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && !get_option( 'ec_option_live_override_always' ) ) {
					$subrate = $this->live_based[ $i ][4];
				}
				$lowest_i = $i;
				$lowest = floatval( $subrate );
				$lowest_ship_method = $this->live_based[ $i ][2];
			}

		}

		return $lowest_ship_method;
	}

	public function get_selected_shipping_method() {

		$selected_shipping_method_id = 0;
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' )
			$selected_shipping_method_id = $GLOBALS['ec_cart_data']->cart_data->shipping_method;

		if ( $this->shipping_method == 'price' || $this->shipping_method == 'weight' || $this->shipping_method == 'percentage' || $this->shipping_method == 'quantity' ) {
			if ( $this->ship_express ) {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' );
			} else if ( 'free' == (string) $selected_shipping_method_id ) {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' );
			} else {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' );
			}

		} else if ( $this->shipping_method == 'method' ) {

			 for ( $i=0; $i<count($this->method_based); $i++) {
				if ( $this->method_based[ $i ][2] == $selected_shipping_method_id ) {
					return $this->get_method_based_div( $i );
				}
			}

		} else if ( $this->shipping_method == 'live' ) {
			if ( 'free' == (string) $selected_shipping_method_id ) {
				return '<div id="free"> ' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ) . ' ' . $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', 0, 'free' ) ) . '</div>';
			}

			$shippable_total = 0;
			for ( $i=0; $i<count( $this->cart ); $i++ ) {
				if ( $this->cart[$i]->is_shippable && ! $this->cart[$i]->exclude_shippable_calculation ) {
					$shippable_total++;
				}
			}

			for ( $i=0; $i<count($this->live_based); $i++) {
				if ( $this->live_based[ $i ][2] == $selected_shipping_method_id ) {
					if ( $this->live_based[ $i ][4] && get_option( 'ec_option_live_override_always' ) ) {
						if ( $this->live_based[ $i ][4] == 0 ) {
							$rate = 'FREE';
						} else {
							$rate = $this->live_based[ $i ][4];
						}
						return '<div id="' . $this->live_based[ $i ][0] . '"> ' . $this->live_based[ $i ][1] . ' ' . $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', $rate, $this->live_based[ $i ][2] ) ) . '</div>';
					} else if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
						$rate = 'FREE';
					} else {
						$rate = floatval( $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] ) ) + floatval( $this->handling );
					}
					return $this->get_live_based_div( $i, $i, $rate );
				}
			}

			// Nothing currently selected, lets find lowest value!
			$lowest_i = 0;
			$lowest = 100000.00;
			$lowest_ship_method = 'ERROR';

			 for ( $i=0; $i<count( $this->live_based ); $i++ ) {

				// Find lowest
				if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
					$lowest_i = $i;
					$lowest = floatval( 0 );
					$lowest_ship_method = $this->live_based[ $i ][2];

				} else if ( $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && get_option( 'ec_option_live_override_always' ) )
					$subrate = $this->live_based[ $i ][4];
				else 
					$subrate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );

				if ( $subrate != 'ERROR' && $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && !get_option( 'ec_option_live_override_always' ) ) {
					$subrate = $this->live_based[ $i ][4];
				}

				if ( $subrate != 'ERROR' && floatval( $subrate ) < $lowest ) {
					$lowest_i = $i;
					$lowest = floatval( $subrate );
					$lowest_ship_method = $this->live_based[ $i ][2];
				}

			}

			if ( $shippable_total <= 0 ) {
				$lowest = .0001;
				$lowest_i = 0;
			}

			return $this->get_live_based_div( $lowest_i, $lowest_i, $lowest );

		} else if ( $this->shipping_method == 'fraktjakt' ) {

			$i = 0;
			$selected_method = 0;
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' )
				$selected_method = $GLOBALS['ec_cart_data']->cart_data->shipping_method;

			$ret_string = '';
			foreach( $this->fraktjakt_shipping_options as $shipping_option ) {
				if ( ( !$selected_method && $i == 0 ) || ( $selected_method == $shipping_option['id'] ) )
					return $shipping_option['description'];

				$i++;
			}
		}

	}

	public function get_selected_shipping_method_label() {
		$selected_shipping_method_id = 0;
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' ) {
			$selected_shipping_method_id = $GLOBALS['ec_cart_data']->cart_data->shipping_method;
		}

		if ( $this->shipping_method == 'price' || $this->shipping_method == 'weight' || $this->shipping_method == 'percentage' || $this->shipping_method == 'quantity' ) {
			if ( $this->ship_express ) {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' );
			} else if ( 'free' == (string) $selected_shipping_method_id ) {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' );
			} else {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' );
			}

		} else if ( $this->shipping_method == 'method' ) {
			 for ( $i = 0; $i < count( $this->method_based ); $i++ ) {
				if ( $this->method_based[ $i ][2] == $selected_shipping_method_id ) {
					return esc_attr( $this->method_based[ $i ][1] );
				}
			}

		} else if ( $this->shipping_method == 'live' ) {
			if ( 'free' == (string) $selected_shipping_method_id ) {
				return wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' );
			}

			$shippable_total = 0;
			for ( $i=0; $i<count( $this->cart ); $i++ ) {
				if ( $this->cart[$i]->is_shippable && ! $this->cart[$i]->exclude_shippable_calculation ) {
					$shippable_total++;
				}
			}

			for ( $i=0; $i<count($this->live_based); $i++) {
				if ( $this->live_based[ $i ][2] == $selected_shipping_method_id ) {
					if ( $this->live_based[ $i ][4] && get_option( 'ec_option_live_override_always' ) ) {
						if ( $this->live_based[ $i ][4] == 0 ) {
							$rate = 'FREE';
						} else {
							$rate = $this->live_based[ $i ][4];
						}
						return $this->live_based[ $i ][1];

					} else if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
						$rate = 'FREE';
					} else {
						$rate = floatval( $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] ) ) + floatval( $this->handling );
					}
					return $this->live_based[ $i ][1];
				}
			}

			// Nothing currently selected, lets find lowest value!
			$lowest_i = 0;
			$lowest = 100000.00;
			$lowest_ship_method = 'ERROR';

			 for ( $i=0; $i<count( $this->live_based ); $i++ ) {

				// Find lowest
				if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
					$lowest_i = $i;
					$lowest = floatval( 0 );
					$lowest_ship_method = $this->live_based[ $i ][2];

				} else if ( $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && get_option( 'ec_option_live_override_always' ) )
					$subrate = $this->live_based[ $i ][4];
				else 
					$subrate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );

				if ( $subrate != 'ERROR' && $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && !get_option( 'ec_option_live_override_always' ) ) {
					$subrate = $this->live_based[ $i ][4];
				}

				if ( $subrate != 'ERROR' && floatval( $subrate ) < $lowest ) {
					$lowest_i = $i;
					$lowest = floatval( $subrate );
					$lowest_ship_method = $this->live_based[ $i ][2];
				}

			}

			if ( $shippable_total <= 0 ) {
				$lowest = .0001;
				$lowest_i = 0;
			}

			return $this->live_based[ $lowest_i ][1];

		} else if ( $this->shipping_method == 'fraktjakt' ) {
			$i = 0;
			$selected_method = 0;
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' ) {
				$selected_method = $GLOBALS['ec_cart_data']->cart_data->shipping_method;
			}

			$ret_string = '';
			foreach( $this->fraktjakt_shipping_options as $shipping_option ) {
				if ( ( ! $selected_method && $i == 0 ) || ( $selected_method == $shipping_option['id'] ) ) {
					return $shipping_option['description'];
				}
				$i++;
			}
		}
	}

	public function get_single_shipping_price_content( $standard_text, $express_text, $standard_price ) {

		$coupon_code = '';
		if ( $GLOBALS['ec_cart_data']->cart_data->coupon_code != '' ) {
			$coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
		}

		$discount = new ec_discount( (object) array( 'cart' => array() ), 0.00, $standard_price, $coupon_code, '', 0 );
		$shipping_discount = $discount->shipping_discount;

		if ( get_option( 'ec_option_add_local_pickup' ) ) {
			echo '<div id="ec_cart_standard_shipping_row_free" class="ec_cart_shipping_method_row"><input type="radio" class="no_wrap" name="ec_cart_shipping_method" id="ec_cart_shipping_method_free" onchange="' . $this->change_shipping_js_func . '( \'free\', 0, \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\');" value="free"';
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == "free" ) {
				echo ' checked="checked"';
			}
			echo ' /><span class="ec_cart_standard_shipping_price_label">' . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_free' ) . ' (' . esc_attr( $GLOBALS['currency']->get_symbol() ) . '</span> <span id="ec_cart_standard_shipping_price_free">' . esc_attr( $GLOBALS['currency']->get_number_only( apply_filters( 'wp_easycart_shipping_price_display', 0, 'free' ) ) ) . '</span>)</div>';
		}
		echo '<div id="ec_cart_standard_shipping_row" class="ec_cart_shipping_method_row"><input type="radio" class="no_wrap" name="ec_cart_shipping_method" id="ec_cart_shipping_method" onchange="' . $this->change_shipping_js_func . '(\'standard\',0, \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\');" value="standard"';
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == "" || $GLOBALS['ec_cart_data']->cart_data->shipping_method == "standard" ) {
			echo ' checked="checked"';
		}
		echo ' /><span class="ec_cart_standard_shipping_price_label">' . esc_attr( $standard_text ) . ' (' . esc_attr( $GLOBALS['currency']->get_symbol() ) . '</span><span id="ec_cart_standard_shipping_price">' . esc_attr( $GLOBALS['currency']->get_number_only( apply_filters( 'wp_easycart_shipping_price_display', $standard_price + $this->handling - $shipping_discount, 'standard' ) ) ) . '</span>)</div>';
		if ( $this->express_price > 0 ) {
			echo '<div id="ec_cart_express_shipping_row" class="ec_cart_shipping_method_row"><input type="checkbox" name="ec_cart_ship_express" id="ec_cart_ship_express" onchange="' . $this->change_shipping_js_func . '(\'shipexpress\',0, \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\');" value="shipexpress"';
			if ( $this->ship_express ) {
				echo ' checked="checked"';
			}
			echo ' /><span class="ec_cart_standard_shipping_price_label">' . esc_attr( $express_text ) . ' (+' . esc_attr( $GLOBALS['currency']->get_symbol() ) . '</span><span id="ec_cart_express_shipping_price">' . esc_attr( $GLOBALS['currency']->get_number_only( apply_filters( 'wp_easycart_express_shipping_price_display', $this->express_price ) ) ) . '</span>)</div>';
		}
	}

	public function get_shipping_price( $cart_handling = 0 ) {
		if ( $this->freeshipping || $this->quantity == 0 ) {
			return '0.00';
		}

		$rate = 'ERROR';
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'free' || $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'promo_free' ) {
				$rate = 0;

		} else if ( $this->shipping_method == 'price' ) {
			 for ( $i=0; $i<count( $this->price_based ); $i++ ) {
				if ( $this->subtotal >= $this->price_based[ $i ][0] ) {
					$rate = apply_filters( 'wp_easycart_trigger_rate', $this->price_based[ $i ][1], 'price' );
					break;
				}

			}
			if ( $this->ship_express )
				$rate = $rate + $this->express_price;

		} else if ( $this->shipping_method == 'weight' ) {
			 for ( $i=0; $i<count( $this->weight_based ); $i++ ) {
				if ( $this->weight >= $this->weight_based[ $i ][0] ) {
					$rate = apply_filters( 'wp_easycart_trigger_rate', $this->weight_based[ $i ][1], 'weight' );
					break;
				}
			}
			if ( $this->ship_express )
				$rate = $rate + $this->express_price;

		} else if ( $this->shipping_method == 'method' ) {
			if ( $this->subtotal <= 0 )
				$rate = '0.00';

			else if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == '' ) {
				if ( !is_array( $this->method_based ) || count( $this->method_based ) == 0 ) {
					$rate = 0;
				} else if ( $this->method_based[0][3] > 0 && $this->subtotal >= $this->method_based[0][3] ) {
					$rate = 0;
				} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
					$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
				} else {
					$rate = $this->method_based[0][0];
				}

			} else {
				$rate_found = false;
				 for ( $i=0; $i<count( $this->method_based ); $i++ ) {
					if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->method_based[ $i ][2] ) {
						if ( $this->method_based[ $i ][3] > 0 && $this->subtotal >= $this->method_based[ $i ][3] ) {
							$rate = 0;
						} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
							$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
						} else {
							$rate = $this->method_based[ $i ][0];
						}
						$rate_found = true;
					}
				}

				if ( !$rate_found ) {
					if ( $this->method_based[0][3] > 0 && $this->subtotal >= $this->method_based[0][3] ) {
						$rate = 0;
					} else if ( get_option( 'ec_option_static_ship_items_seperately' ) ) {
						$rate = ( $this->method_based[ $i ][0] * $this->quantity ) + $this->handling;
					} else {
						$rate = $this->method_based[0][0];
					}
				}
			}
			$rate = apply_filters( 'wp_easycart_trigger_rate', $rate, 'method' );

		} else if ( $this->shipping_method == 'quantity' ) {
			 for ( $i=0; $i<count( $this->quantity_based ); $i++ ) {
				if ( $this->quantity >= $this->quantity_based[ $i ][0] ) {
					$rate = apply_filters( 'wp_easycart_trigger_rate', $this->quantity_based[ $i ][1], 'quantity' );
					break;
				}

			}
			if ( $this->ship_express )
				$rate = $rate + $this->express_price;

		} else if ( $this->shipping_method == 'percentage' ) {
			 for ( $i=0; $i<count( $this->percentage_based ); $i++ ) {
				if ( $this->subtotal >= $this->percentage_based[ $i ][0] ) {
					$rate = apply_filters( 'wp_easycart_trigger_rate', ( $this->subtotal * ( $this->percentage_based[ $i ][1] / 100 ) ), 'percentage' );
					break;
				}

			}
			if ( $this->ship_express )
				$rate = $rate + $this->express_price;

		} else if ( $this->shipping_method == 'live' ) {
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip ) && $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip == '' && isset( $GLOBALS['ec_cart_data']->cart_data->shipping_method ) && $GLOBALS['ec_cart_data']->cart_data->shipping_method == '' && $GLOBALS['ec_cart_data']->cart_data->email == '' )
				return floatval( '0.00' );

			$lowest = 100000.00;
			$lowest_ship_method = 'ERROR';

			 for ( $i=0; $i<count( $this->live_based ); $i++ ) {

				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' && $GLOBALS['ec_cart_data']->cart_data->shipping_method == $this->live_based[ $i ][2] ) {
					if ( $this->live_based[ $i ][4] != null && get_option( 'ec_option_live_override_always' ) ) {
						if ( $this->live_based[ $i ][4] == 0 )
							$rate = 'FREE';
						else
							$rate = $this->live_based[ $i ][4];
					} else if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
						$rate = 'FREE';
					} else {
						$rate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );

						if ( $rate != 'ERROR' && $this->live_based[ $i ][4] != null && !get_option( 'ec_option_live_override_always' ) ) {
							$rate = $this->live_based[ $i ][4];
						}
					}

				} else {

					if ( $this->live_based[ $i ][5] > 0 && $this->subtotal >= $this->live_based[ $i ][5] ) { // Shipping free at rate
						$subrate = 0;
						$lowest = floatval( 0 );
						$lowest_ship_method = $this->live_based[ $i ][2];
					} else if ( $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] > 0 && get_option( 'ec_option_live_override_always' ) ) {
						$subrate = $this->live_based[ $i ][4];
					} else if ( $this->live_based[ $i ][4] != null && $this->live_based[ $i ][4] == 0 && get_option( 'ec_option_live_override_always' ) ) {
						$subrate = 99999999;
					} else { 
						$subrate = $this->shipper->get_rate( $this->live_based[ $i ][3], $this->live_based[ $i ][0] );
					}
					if ( $subrate != 'ERROR' && $this->live_based[ $i ][4] != null && ! get_option( 'ec_option_live_override_always' ) ) {
						$subrate = $this->live_based[ $i ][4];
					}
					if ( $subrate != 'ERROR' && floatval( $subrate ) < $lowest ) {
						$lowest = floatval( $subrate );
						$lowest_ship_method = $this->live_based[ $i ][2];
					}
				}
			}

			if ( $rate == 'ERROR' && $lowest_ship_method != 'ERROR' ) {
				$rate = $lowest;
			}

		} else if ( $this->shipping_method == 'fraktjakt' ) {
			$i = 0;
			$selected_method = 0;
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' ) {
				$selected_method = $GLOBALS['ec_cart_data']->cart_data->shipping_method;
			}
			if ( $this->fraktjakt_shipping_options ) {
				$backup = 0.00;
				$frak_is_found = false;
				foreach ( $this->fraktjakt_shipping_options as $shipping_option ) {
					if ( ( ! $selected_method && $i == 0 ) || ( $selected_method == $shipping_option['id'] ) ) {
						$rate = $shipping_option['price'];
						$frak_is_found = true;
					} else if ( $i == 0 )
						$backup = $shipping_option['price'];

					$i++;
				}

				if ( ! $frak_is_found ) {
					$rate = $backup;
				}
			}
		}

		if ( $rate == 'ERROR' ) {
			return floatval( '0.00' );
		} else if ( $rate == 'FREE' ) {
			return 0;
		} else {
			$rate = floatval( $rate ) + floatval( $this->handling ) + floatval( $cart_handling );

			$promotion = new ec_promotion();
			$discount = $promotion->get_shipping_discounts( $this->subtotal, $rate, $this->shipping_promotion_text );

			return floatval( $rate ) - floatval( $discount );
		}
	}

	public function get_shipping_promotion_text() {
		$promotion = new ec_promotion();
		$rate = 0;
		$promotion->get_shipping_discounts( $this->subtotal, $rate, $this->shipping_promotion_text );
		return $this->shipping_promotion_text;
	}

	public function submit_fraktjakt_shipping_order() {
		$shipment_id = 0;
		$i = 0;
		$selected_method = 0;
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != '' )
			$selected_method = $GLOBALS['ec_cart_data']->cart_data->shipping_method;

		foreach( $this->fraktjakt_shipping_options as $shipping_option ) {
			if ( $selected_method == $shipping_option['id'] ) {
				$shipment_id = $shipping_option['shipment_id'];
			}
		}

		return $this->fraktjakt->insert_shipping_order( $shipment_id, $GLOBALS['ec_cart_data']->cart_data->shipping_method );
	}

	public function validate_address( $destination_address, $destination_city, $destination_state, $destination_zip, $destination_country ) {
		if ( $this->shipping_method == 'live' ) {
			return $this->shipper->validate_address( $destination_address, $destination_city, $destination_state, $destination_zip, $destination_country );
		} else if ( $this->shipping_method == 'fraktjakt' ) {
			return $this->fraktjakt->validate_address( $destination_address, $destination_city, $destination_state, $destination_zip, $destination_country );
		} else {
			return true;
		}
	}

	public function skip_shipping_selection_page() {
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == '' && $this->shipping_method == 'live' ) {
			$lowest_method = $this->get_lowest_live_based_rate();
			$GLOBALS['ec_cart_data']->cart_data->shipping_method = $lowest_method;
		}
	}

	public function has_shipping_rates() {
		if ( $this->shipping_method == 'price' ) {
			return count( $this->price_based );
		} else if ( $this->shipping_method == 'weight' ) {
			return count( $this->weight_based );
		} else if ( $this->shipping_method == 'method' ) {
			return count( $this->method_based );
		} else if ( $this->shipping_method == 'quantity' ) {
			return count( $this->quantity_based );
		} else if ( $this->shipping_method == 'percentage' ) {
			return count( $this->percentage_based );
		} else if ( $this->shipping_method == 'live' ) {
			return count( $this->live_based );
		} else if ( $this->shipping_method == 'fraktjakt' ) {
			return true;
		}
	}

}
