<?php
class ec_googleanalytics {

	private $cart;
	private $shipping;
	private $tax;
	private $order_total;
	private $order_id;

	function __construct( $cart, $shipping, $tax, $order_totals, $order_id ) {
		$this->cart = $cart;
		$this->shipping = $shipping;	
		$this->tax = $tax;
		$this->order_total = $order_totals;
		$this->order_id = $order_id;
	}

	public function print_transaction_js() {
		echo "ga('ecommerce:addTransaction', {id: '" . esc_attr( $this->order_id ) . "', affiliation: '', revenue: '" . esc_attr( $this->order_total ) . "', shipping: '" . esc_attr( $this->shipping ) . "', tax: '" . esc_attr( $this->tax ) . "'});\n";
	}

	public function print_item_js() {
		$cart_count = count( $this->cart );
		for( $i = 0; $i < $cart_count; $i++ ){
			echo "ga('ecommerce:addItem', {id: '" . esc_attr( $this->cart[$i]->order_id ) . "', name: '".esc_js( $this->cart[$i]->title )."', sku: '".esc_js( $this->cart[$i]->model_number )."', price: '".esc_js( $this->cart[$i]->unit_price )."', quantity: '".esc_js( $this->cart[$i]->quantity )."'});\n";
		}
	}
}
