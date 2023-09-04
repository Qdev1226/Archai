<?php

class ec_order_totals {
	public $sub_total;
	public $converted_sub_total;
	public $tax_total;
	public $handling_total;
	public $shipping_total;
	public $duty_total;
	public $vat_total;
	public $gst_total;
	public $pst_total;
	public $hst_total;
	public $fee_total;
	public $tip_total;
	public $discount_total;
	public $grand_total;
	public $converted_grand_total;
	public $tax;

	function __construct( $cart, $user, $shipping, $tax, $discount ) {
		$this->tax = $tax;
		$this->sub_total = number_format( $cart->subtotal, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$this->set_converted_sub_total( $cart );
		$this->handling_total = number_format( $cart->get_handling_total(), $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$shipping_price = floatval( $shipping->get_shipping_price( $this->handling_total ) );
		$this->shipping_total = number_format( $shipping_price, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		if ( $cart->shippable_total_items <= 0 ) {
			$this->shipping_total = 0 + $this->handling_total;
		}
		if ( 'square' == get_option( 'ec_option_payment_process_method' ) ) {
			$this->tax_total = number_format( round( $tax->tax_total, 2, PHP_ROUND_HALF_EVEN ), $GLOBALS['currency']->get_decimal_length(), '.', '' );
		} else {
			$this->tax_total = number_format( $tax->tax_total, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		}
		$this->fee_total = 0;
		for ( $i = 0; $i < count( $tax->fees ); $i++ ) {
			$this->fee_total += $tax->fees[ $i ]->amount;
		}
		$this->fee_total = number_format( $this->fee_total, $GLOBALS['currency']->get_decimal_length( ), '.', '' );
		$this->duty_total = number_format( $tax->duty_total, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$this->vat_total = number_format( $tax->vat_total, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$this->gst_total = number_format( $tax->gst, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$this->pst_total = number_format( $tax->pst, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$this->hst_total = number_format( $tax->hst, $GLOBALS['currency']->get_decimal_length(), '.', '' );
		$this->tip_total = 0;
		if ( get_option( 'ec_option_enable_tips' ) ) {
			$this->tip_total = ( $GLOBALS['ec_cart_data']->cart_data->tip_rate == 'custom' ) ? number_format( $GLOBALS['ec_cart_data']->cart_data->tip_amount, $GLOBALS['currency']->get_decimal_length( ), '.', '' ) : (float) number_format( $GLOBALS['ec_cart_data']->cart_data->tip_rate / 100 * $this->converted_sub_total, $GLOBALS['currency']->get_decimal_length(), '.', '' );
			$this->tip_total = ( $this->tip_total < 0 ) ? 0 : $this->tip_total;
		}
		if ( 'vat' == strtolower(substr( $discount->coupon_code, 0, 3 ) ) ) {
			$mysqli = new ec_db();
			$promocode_row = $GLOBALS['ec_coupons']->redeem_coupon_code( $discount->coupon_code );
			if ( $promocode_row && $promocode_row->is_free_item_based ) {
				$this->vat_total = number_format( 0, $GLOBALS['currency']->get_decimal_length(), '.', '' );
			}
		}
		$this->discount_total = number_format( $discount->discount_total, $GLOBALS['currency']->get_decimal_length( ), '.', '' );
		$this->shipping_total = $this->shipping_total - $discount->shipping_discount;
		$this->grand_total = number_format( $this->get_grand_total( $tax ), $GLOBALS['currency']->get_decimal_length( ), '.', '' );
		$this->set_converted_grand_total( $tax );
	}

	private function get_grand_total( $tax ) {
		if ( $tax->vat_included ) {
			return $this->sub_total + $this->shipping_total + $this->tax_total + $this->gst_total + $this->pst_total + $this->hst_total + $this->fee_total + $this->duty_total - $this->discount_total + $this->tip_total;
		} else {
			return $this->sub_total + $this->shipping_total + $this->tax_total + $this->gst_total + $this->pst_total + $this->hst_total + $this->fee_total + $this->duty_total + $this->vat_total - $this->discount_total + $this->tip_total;
		}
	}

	public function get_grand_total_in_cents() {
		return number_format( $this->grand_total * 100, 0, '', '' );
	}

	private function set_converted_sub_total( $cart ) {
		$this->converted_sub_total = 0;
		foreach ( $cart->cart as $cartitem ) {
			$this->converted_sub_total += $cartitem->converted_total_price;
		}
	}

	public function get_converted_sub_total() {
		return $this->converted_sub_total;
	}

	private function set_converted_grand_total( $tax ) {
		if ( $tax->vat_included ) {
			$this->converted_grand_total = $this->get_converted_sub_total( ) + $GLOBALS['currency']->convert_price( $this->shipping_total + $this->tax_total + $this->gst_total + $this->pst_total + $this->hst_total + $this->fee_total + $this->duty_total - $this->discount_total + $this->tip_total );
		} else {
			$this->converted_grand_total = $this->get_converted_sub_total( ) + $GLOBALS['currency']->convert_price( $this->shipping_total + $this->tax_total + $this->gst_total + $this->pst_total + $this->hst_total + $this->fee_total + $this->duty_total + $this->vat_total - $this->discount_total + $this->tip_total );
		}
	}

	public function get_converted_grand_total() {
		return $this->converted_grand_total;
	}
}
