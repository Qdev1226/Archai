<?php

class ec_prodoptions {
	private $mysqli;

	public $product_id;
	public $optionset1;
	public $optionset2;
	public $optionset3;
	public $optionset4;
	public $optionset5;

	public $quantity_array = array();
	public $variation_array = array();
	
	function __construct( $product_id, $option_id_1, $option_id_2, $option_id_3, $option_id_4, $option_id_5, $use_quantity_tracking ) {
		$this->mysqli = new ec_db();

		$this->product_id = $product_id;
		$this->optionset1 = new ec_optionset( $option_id_1 );
		$this->optionset2 = new ec_optionset( $option_id_2 );
		$this->optionset3 = new ec_optionset( $option_id_3 );
		$this->optionset4 = new ec_optionset( $option_id_4 );
		$this->optionset5 = new ec_optionset( $option_id_5 );

		if ( $use_quantity_tracking ) {
			$this->quantity_array = $this->mysqli->get_quantity_data( $this->product_id, $this->optionset1, $this->optionset2, $this->optionset3, $this->optionset4, $this->optionset5 );
		} else {
			$this->quantity_array = array();
		}
		$this->variation_array = $this->mysqli->get_variation_data( $this->product_id, $this->optionset1, $this->optionset2, $this->optionset3, $this->optionset4, $this->optionset5 );
	}

	public function verify_optionitem( $id_num, $optionitem_id ) {
		$is_available = false;
		$prefix = '';
		if ( 2 == $id_num ) {
			$prefix = 'x';
		} else if ( 3 == $id_num ) {
			$prefix = 'xx';
		} else if ( 4 == $id_num ) {
			$prefix = 'xxx';
		} else if ( 5 == $id_num ) {
			$prefix = 'xxxx';
		}
		if ( isset( $this->variation_array[ $prefix . $optionitem_id ] ) && isset( $this->variation_array[ $prefix . $optionitem_id ]->items ) && count( $this->variation_array[ $prefix . $optionitem_id ]->items ) > 0 ) {
			foreach ( $this->variation_array[ $prefix . $optionitem_id ]->items as $item ) {
				if ( $item->is_enabled ) {
					$is_available = true;
				}
			}
		} else {
			$is_available = true;
		}
		return $is_available;
	}

	public function get_quantity_string( $level, $num ) {
		if ( 1 == $level ) {
			return $this->quantity_array[ $num ][1];

		} else if( 2 == $level ) {
			$ret_string = '';
			for ( $a = 0; $a < count( $this->quantity_array ); $a++ ) {
				if ( $a > 0 ) {
					$ret_string .= ',';
				}
				$ret_string .= $this->quantity_array[ $a ][0][ $num ][1];
			}
			return $ret_string;

		} else if ( 3 == $level ) {
			$ret_string = '';
			for ( $a = 0; $a < count( $this->quantity_array ); $a++ ) {
				for ( $b = 0; $b < count( $this->quantity_array[ $a ][0] ); $b++ ) {
					if( $a > 0 || $b > 0) {
						$ret_string .= ',';
					}
					$ret_string .= $this->quantity_array[ $a ][0][ $b ][0][ $num ][1];
				}
			}
			return $ret_string;

		} else if ( 4 == $level ) {
			$ret_string = '';
			for ( $a = 0; $a < count( $this->quantity_array ); $a++ ) {
				for ( $b = 0; $b < count( $this->quantity_array[ $a ][0] ); $b++ ) {
					for ( $c = 0; $c < count( $this->quantity_array[ $a ][0][ $b ][0] ); $c++ ) {
						if ( $a > 0 || $b > 0 || $c > 0 ) {
							$ret_string .= ',';
						}
						$ret_string .= $this->quantity_array[ $a ][0][ $b ][0][ $c ][0][ $num ][1];
					}
				}
			}
			return $ret_string;

		} else if ( 5 == $level ) {
			$ret_string = '';
			for ( $a = 0; $a < count( $this->quantity_array ); $a++ ) {
				for ( $b = 0; $b < count( $this->quantity_array[ $a ][0] ); $b++ ) {
					for ( $c = 0; $c < count( $this->quantity_array[ $a ][0][ $b ][0] ); $c++ ) {
						for ( $d = 0; $d < count( $this->quantity_array[ $a ][0][ $b ][0][ $c ][0]); $d++ ) {
							if ( $a > 0 || $b > 0 || $c > 0 || $d > 0 ) {
								$ret_string .= ',';
							}
							$ret_string .= $this->quantity_array[ $a ][0][ $b ][0][ $c ][0][ $d ][0][ $num ][1];
						}
					}
				}
			}
			return $ret_string;
		}
	}
}
