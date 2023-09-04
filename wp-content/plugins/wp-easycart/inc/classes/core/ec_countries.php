<?php

class ec_countries {

	public $countries;
	public $address_format;

	function __construct() {
		global $wpdb;
		// $this->init_address_info();
		$this->countries = wp_cache_get( 'wpeasycart-countries' );
		if ( ! $this->countries ) {
			$this->countries = $wpdb->get_results( "SELECT * FROM ec_country WHERE ship_to_active = 1 ORDER BY ec_country.sort_order ASC" );
			wp_cache_set( 'wpeasycart-countries', $this->countries );
		}
	}

	public function init_address_info() {
		$this->address_format = array(
			'US' => (object) array(
				'fields' => (object) array(
					'name' => array(
						(object) array(
							'field_key' => 'first_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ),
						),
						(object) array(
							'field_key' => 'last_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ),
						),
					),
					'company' => array(
						(object) array(
							'field_key' => 'company_name',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ),
						),
					),
					'address' => array(
						(object) array(
							'field_key' => 'address',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ),
						),
					),
					'address2' => array(
						(object) array(
							'field_key' => 'address2',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address2' ),
						),
					),
					'address_info' => array( 
						(object) array(
							'field_key' => 'city',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ),
						),
						(object) array(
							'field_key' => 'state',
							'required' => true,
							'format' => '/^([A-Z][A-Z])$/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_state' ),
						),
						(object) array(
							'field_key' => 'zip',
							'required' => true,
							'format' => '/^([0-9]{5})([-][0-9]{4})*$/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_zip' ),
						),
					),
				),
			),
			'CA' => (object) array(
				'fields' => (object) array(
					'name' => array(
						(object) array(
							'field_key' => 'first_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ),
						),
						(object) array(
							'field_key' => 'last_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ),
						),
					),
					'company' => array(
						(object) array(
							'field_key' => 'company_name',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ),
						),
					),
					'address' => array(
						(object) array(
							'field_key' => 'address',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ),
						),
					),
					'address2' => array(
						(object) array(
							'field_key' => 'address2',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address2' ),
						),
					),
					'address_info' => array( 
						(object) array(
							'field_key' => 'city',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ),
						),
						(object) array(
							'field_key' => 'state',
							'required' => true,
							'format' => '/^([A-Z][A-Z])$/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_state_ca' ),
						),
						(object) array(
							'field_key' => 'zip',
							'required' => true,
							'format' => '/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_zip_ca' ),
						),
					),
				),
			),
			'GB' => (object) array(
				'fields' => (object) array(
					'name' => array(
						(object) array(
							'field_key' => 'first_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ),
						),
						(object) array(
							'field_key' => 'last_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ),
						),
					),
					'company' => array(
						(object) array(
							'field_key' => 'company_name',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ),
						),
					),
					'address' => array(
						(object) array(
							'field_key' => 'address',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ),
						),
					),
					'address2' => array(
						(object) array(
							'field_key' => 'address2',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address2' ),
						),
					),
					'address_info' => array( 
						(object) array(
							'field_key' => 'city',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ),
						),
						(object) array(
							'field_key' => 'zip',
							'required' => true,
							'format' => '/([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_zip_gb' ),
						),
					),
					'hidden' => array(
						(object) array(
							'field_key' => 'state',
							'required' => false,
							'label' => '',
						),
					),
				),
			),
			'NL' => (object) array(
				'fields' => (object) array(
					'name' => array(
						(object) array(
							'field_key' => 'first_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ),
						),
						(object) array(
							'field_key' => 'last_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ),
						),
					),
					'company' => array(
						(object) array(
							'field_key' => 'company_name',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ),
						),
					),
					'address' => array(
						(object) array(
							'field_key' => 'address',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_address_nl' ),
						),
					),
					'address2' => array(
						(object) array(
							'field_key' => 'address2',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address2' ),
						),
					),
					'address_info' => array( 
						(object) array(
							'field_key' => 'city',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ),
						),
						(object) array(
							'field_key' => 'zip',
							'required' => true,
							'format' => '/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_zip_nl' ),
						),
					),
					'hidden' => array(
						(object) array(
							'field_key' => 'state',
							'required' => false,
							'label' => '',
						),
					),
				),
			),
			'AR' => (object) array(
				'fields' => (object) array(
					'name' => array(
						(object) array(
							'field_key' => 'first_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ),
						),
						(object) array(
							'field_key' => 'last_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ),
						),
					),
					'company' => array(
						(object) array(
							'field_key' => 'company_name',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ),
						),
					),
					'address' => array(
						(object) array(
							'field_key' => 'address',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ),
						),
					),
					'address2' => array(
						(object) array(
							'field_key' => 'address2',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address2' ),
						),
					),
					'address_info' => array(
						(object) array(
							'field_key' => 'zip',
							'required' => true,
							'format' => '/^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_zip_ar' ),
						),
						(object) array(
							'field_key' => 'city',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ),
						),
						(object) array(
							'field_key' => 'state',
							'required' => true,
							'format' => '/^([A-Z][A-Z])$/',
							'label' => wp_easycart_language()->get_text( 'cart_address_information', 'cart_state_ar' ),
						),
					),
				),
			),
		);
	}

	public function get_country_name( $iso2_cnt ) {
		for ( $i = 0; $i < count( $this->countries ); $i++ ) {
			if( $this->countries[ $i ]->iso2_cnt == $iso2_cnt ) {
				return $this->countries[ $i ]->name_cnt;
			}
		}
	}

	public function get_state_from_zip( $iso2_cnt, $zip ) {
		if ( 'US' != strtoupper( $iso2_cnt ) ) {
			return false;
		}
		$zip = (int) substr( $zip, 0, 5 );
		$state_code = false;

		if ( $zip >= 35000 && $zip <= 36999 ) {
			$state_code =  'AL';
		} else if ( $zip >= 99500 && $zip <= 99999 ) {
			$state_code =  'AK';
		} else if ( $zip >= 85000 && $zip <= 86999 ) {
			$state_code = 'AZ';
		} else if ( $zip >= 71600 && $zip <= 72999 ) {
			$state_code = 'AR';
		} else if ( $zip >= 90000 && $zip <= 96699 ) {
			$state_code = 'CA';
		} else if ( $zip >= 80000 && $zip <= 81999 ) {
			$state_code = 'CO';
		} else if ( ( $zip >= 6000 && $zip <= 6389 ) || ( $zip >= 6391 && $zip <= 6999 ) ) {
			$state_code = 'CT';
		} else if ( $zip >= 19700 && $zip <= 19999 ) {
			$state_code = 'DE';
		} else if ( $zip >= 32000 && $zip <= 34999 ) {
			$state_code = 'FL';
		} else if ( ( $zip >= 30000 && $zip <= 31999 ) || ( $zip >= 39800 && $zip <= 39999 ) ) {
			$state_code = 'GA';
		} else if ( $zip >= 96700 && $zip <= 96999 ) {
			$state_code = 'HI';
		} else if ( $zip >= 83200 && $zip <= 83999 && $zip != 83414 ) {
			$state_code = 'ID';
		} else if ( $zip >= 60000 && $zip <= 62999 ) {
			$state_code = 'IL';
		} else if ( $zip >= 46000 && $zip <= 47999 ) {
			$state_code = 'IN';
		} else if ( $zip >= 50000 && $zip <= 52999 ) {
			$state_code = 'IA';
		} else if ( $zip >= 66000 && $zip <= 67999 ) {
			$state_code = 'KS';
		} else if ( $zip >= 40000 && $zip <= 42999 ) {
			$state_code = 'KY';
		} else if ( $zip >= 70000 && $zip <= 71599 ) {
			$state_code = 'LA';
		} else if ( $zip >= 3900 && $zip <= 4999 ) {
			$state_code = 'ME';
		} else if ( $zip >= 20600 && $zip <= 21999 ) {
			$state_code = 'MD';
		} else if ( ( $zip >= 1000 && $zip <= 2799 ) || $zip == 5501 || $zip == 5544 ) {
			$state_code = 'MA';
		} else if ( $zip >= 48000 && $zip <= 49999 ) {
			$state_code = 'MI';
		} else if ( $zip >= 55000 && $zip <= 56899 ) {
			$state_code = 'MN';
		} else if ( $zip >= 38600 && $zip <= 39999 ) {
			$state_code = 'MS';
		} else if ( $zip >= 63000 && $zip <= 65999 ) {
			$state_code = 'MO';
		} else if ( $zip >= 59000 && $zip <= 59999 ) {
			$state_code = 'MT';
		} else if ( $zip >= 27000 && $zip <= 28999 ) {
			$state_code = 'NC';
		} else if ( $zip >= 58000 && $zip <= 58999 ) {
			$state_code = 'ND';
		} else if ( $zip >= 68000 && $zip <= 69999 ) {
			$state_code = 'NE';
		} else if ( $zip >= 88900 && $zip <= 89999 ) {
			$state_code = 'NV';
		} else if ( $zip >= 3000 && $zip <= 3899 ) {
			$state_code = 'NH';
		} else if ( $zip >= 7000 && $zip <= 8999 ) {
			$state_code = 'NJ';
		} else if ( $zip >= 87000 && $zip <= 88499 ) {
			$state_code = 'NM';
		} else if ( ( $zip >= 10000 && $zip <= 14999 ) ||$zip == 6390 || $zip == 501 || $zip == 544 ) {
			$state_code = 'NY';
		} else if ( $zip >= 43000 && $zip <= 45999 ) {
			$state_code = 'OH';
		} else if ( ( $zip >= 73000 && $zip <= 73199 ) || ( $zip >= 73400 && $zip <= 74999 ) ) {
			$state_code = 'OK';
		} else if ( $zip >= 97000 && $zip <= 97999 ) {
			$state_code = 'OR';
		} else if ( $zip >= 15000 && $zip <= 19699 ) {
			$state_code = 'PA';
		} else if ( $zip >= 300 && $zip <= 999 ) {
			$state_code = 'PR';
		} else if ( $zip >= 2800 && $zip <= 2999 ) {
			$state_code = 'RI';
		} else if ( $zip >= 29000 && $zip <= 29999 ) {
			$state_code = 'SC';
		} else if ( $zip >= 57000 && $zip <= 57999 ) {
			$state_code = 'SD';
		} else if ( $zip >= 37000 && $zip <= 38599 ) {
			$state_code = 'TN';
		} else if ( ( $zip >= 75000 && $zip <= 79999 ) || ( $zip >= 73301 && $zip <= 73399 ) ||  ( $zip >= 88500 && $zip <= 88599 ) ) {
			$state_code = 'TX';
		} else if ( $zip >= 84000 && $zip <= 84999 ) {
			$state_code = 'UT';
		} else if ( $zip >= 5000 && $zip <= 5999 ) {
			$state_code = 'VT';
		} else if ( ( $zip >= 20100 && $zip <= 20199 ) || ( $zip >= 22000 && $zip <= 24699 ) || ( $zip == 20598 ) ) {
			$state_code = 'VA';
		} else if ( ( $zip >= 20000 && $zip <= 20099 ) || ( $zip >= 20200 && $zip <= 20599 ) || ( $zip >= 56900 && $zip <= 56999 ) ) {
			$state_code = 'DC';
		} else if ( $zip >= 98000 && $zip <= 99499 ) {
			$state_code = 'WA';
		} else if ( $zip >= 24700 && $zip <= 26999 ) {
			$state_code = 'WV';
		} else if ( $zip >= 53000 && $zip <= 54999 ) {
			$state_code = 'WI';
		} else if ( ( $zip >= 82000 && $zip <= 83199 ) || $zip == 83414 ) {
			$state_code = 'WY';
		}
		return $state_code;
	}

}
