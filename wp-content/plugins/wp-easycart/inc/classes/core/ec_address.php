<?php

class ec_address{

	public $first_name;							// VARCHAR 255
	public $last_name;							// VARCHAR 255
	public $address_line_1;						// VARCHAR 255
	public $address_line_2;						// VARCHAR 128
	public $city;								// VARCHAR 128
	public $state;								// VARCHAR 255
	public $zip;								// VARCHAR 255
	public $country;							// VARCHAR 255
	public $country_name;						// VARCHAR 255
	public $phone;								// VARCHAR 255
	public $company_name;						// VARCHAR 255
	
	function __construct( $fn, $ln, $ad1, $ad2, $cty, $st, $zp, $cnt, $ph, $cn ){
	
		$this->first_name = $fn;
		$this->last_name = $ln;
		$this->address_line_1 = $ad1;
		$this->address_line_2 = $ad2;
		$this->city = $cty;
		$this->state = $st;
		$this->zip = $zp;
		$this->country = $cnt;
		$this->phone = $ph;
		$this->company_name = $cn;
		
		$mysqli = new ec_db( );
		$this->country_name = $GLOBALS['ec_countries']->get_country_name( $cnt );
		
	}
	
	public function get_value( $name ){
		if( $name == "first_name" ){
			return $this->first_name;	
		}else if( $name == "last_name" ){
			return $this->last_name;
		}else if( $name == "address" ){
			return $this->address_line_1;
		}else if( $name == "address2" ){
			return $this->address_line_2;
		}else if( $name == "address_line_1" ){
			return $this->address_line_1;
		}else if( $name == "address_line_2" ){
			return $this->address_line_2;
		}else if( $name == "city" ){
			return $this->city;
		}else if( $name == "state" ){
			return $this->state;
		}else if( $name == "zip" ){
			return $this->zip;
		}else if( $name == "country" ){
			return $this->country_name;
		}else if( $name == "country2" ){
			return $this->country;
		}else if( $name == "phone" ){
			return $this->phone;
		}else if( $name == "company_name" ){
			return $this->company_name;
		}
	}
	
	public function display_first_name( ){
		echo esc_attr( htmlspecialchars( $this->first_name, ENT_QUOTES ) );
	}
	
	public function display_last_name( ){
		echo esc_attr( htmlspecialchars( $this->last_name, ENT_QUOTES ) );
	}
	
	public function display_address_line_1( ){
		echo esc_attr( htmlspecialchars( $this->address_line_1, ENT_QUOTES ) );
	}
	
	public function display_address_line_2( ){
		echo esc_attr( htmlspecialchars( $this->address_line_2, ENT_QUOTES ) );
	}
	
	public function display_city( ){
		echo esc_attr( htmlspecialchars( $this->city, ENT_QUOTES ) );
	}
	
	public function display_state( ){
		echo esc_attr( htmlspecialchars( $this->state, ENT_QUOTES ) );
	}
	
	public function display_zip( ){
		echo esc_attr( htmlspecialchars( $this->zip, ENT_QUOTES ) );
	}
	
	public function display_country( ){
		echo esc_attr( htmlspecialchars( $this->country_name, ENT_QUOTES ) );
	}
	
	public function display_phone( ){
		echo esc_attr( htmlspecialchars( $this->phone, ENT_QUOTES ) );
	}
	
	public function display_company_name( ){
		echo esc_attr( htmlspecialchars( $this->company_name, ENT_QUOTES ) );
	}
	
}

?>