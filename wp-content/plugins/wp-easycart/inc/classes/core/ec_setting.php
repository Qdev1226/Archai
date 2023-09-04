<?php

class ec_setting{
	protected $mysqli;						// ec_db structure
	
	public $setting_row;					// ec_setting DB table row
	public $perpage_options;
	
	function __construct( $setting_row = false ){
		$this->mysqli = new ec_db( );
		if( $setting_row ){
			$this->setting_row = $setting_row;
		}else{
			$this->setting_row = $this->mysqli->get_settings( );
			$this->perpage_options = $this->mysqli->get_perpage_values( );
		}
	}
	
	public function get_setting( $setting ){
		if( isset( $this->setting_row->{$setting} ) )
			return $this->setting_row->{$setting};	
		else
			return "";
	}
	
	public function get_shipping_method( ){
		if( isset( $this->setting_row->shipping_method ) )
			return $this->setting_row->shipping_method;
		else
			return "";
	}
	
	public function get_ups_access_license_number( ){
		if( isset( $this->setting_row->ups_access_license_number ) )
			return $this->setting_row->ups_access_license_number;
		else
			return "";
	}
	
	public function get_ups_user_id( ){
		if( isset( $this->setting_row->ups_user_id ) )
			return $this->setting_row->ups_user_id;
		else
			return "";
	}
	
	public function get_ups_password( ){
		if( isset( $this->setting_row->ups_password ) )
			return $this->setting_row->ups_password;
		else
			return "";
	}
	
	public function get_ups_ship_from_zip( ){
		if( isset( $this->setting_row->ups_ship_from_zip ) )
			return $this->setting_row->ups_ship_from_zip;
		else
			return "";
	}
	
	public function get_ups_shipper_number( ){
		if( isset( $this->setting_row->ups_shipper_number ) )
			return $this->setting_row->ups_shipper_number;
		else
			return "";
	}
	
	public function get_ups_country_code( ){
		if( isset( $this->setting_row->ups_country_code ) )
			return $this->setting_row->ups_country_code;
		else
			return "";
	}
	
	public function get_ups_weight_type( ){
		if( isset( $this->setting_row->ups_weight_type ) )
			return $this->setting_row->ups_weight_type;
		else
			return "";
	}
	
	public function get_ups_conversion_rate( ){
		if( isset( $this->setting_row->ups_conversion_rate ) )
			return $this->setting_row->ups_conversion_rate;
		else
			return "";
	}

	public function get_ups_ship_from_state( ){
		if( isset( $this->setting_row->ups_ship_from_state ) )
			return $this->setting_row->ups_ship_from_state;
		else
			return "";
	}

	public function get_ups_negotiated_rates( ){
		if( isset( $this->setting_row->ups_negotiated_rates ) )
			return $this->setting_row->ups_negotiated_rates;
		else
			return 0;
	}
	
	public function get_usps_user_name( ){
		if( isset( $this->setting_row->usps_user_name ) )
			return $this->setting_row->usps_user_name;
		else
			return "";
	}

	public function get_usps_ship_from_zip( ){
		if( isset( $this->setting_row->usps_ship_from_zip ) )
			return $this->setting_row->usps_ship_from_zip;
		else
			return "";
	}

	public function get_fedex_key( ){
		if( isset( $this->setting_row->fedex_key ) )
			return $this->setting_row->fedex_key;
		else
			return "";
	}

	public function get_fedex_account_number( ){
		if( isset( $this->setting_row->fedex_account_number ) )
			return $this->setting_row->fedex_account_number;
		else
			return "";
	}

	public function get_fedex_meter_number( ){
		if( isset( $this->setting_row->fedex_meter_number ) )
			return $this->setting_row->fedex_meter_number;
		else
			return "";
	}

	public function get_fedex_password( ){
		if( isset( $this->setting_row->fedex_password ) )
			return $this->setting_row->fedex_password;
		else
			return "";
	}
	
	public function get_fedex_ship_from_zip( ){
		if( isset( $this->setting_row->fedex_ship_from_zip ) )
			return $this->setting_row->fedex_ship_from_zip;
		else
			return "";
	}

	public function get_fedex_weight_units( ){
		if( isset( $this->setting_row->fedex_weight_units ) )
			return $this->setting_row->fedex_weight_units;
		else
			return "";
	}

	public function get_fedex_country_code( ){
		if( isset( $this->setting_row->fedex_country_code ) )
			return $this->setting_row->fedex_country_code;
		else
			return "";
	}

	public function get_fedex_conversion_rate( ){
		if( isset( $this->setting_row->fedex_conversion_rate ) )
			return $this->setting_row->fedex_conversion_rate;
		else
			return "";
	}

	public function get_fedex_test_account( ){
		if( isset( $this->setting_row->fedex_test_account ) )
			return $this->setting_row->fedex_test_account;
		else
			return "";
	}

	public function get_auspost_api_key( ){
		if( isset( $this->setting_row->auspost_api_key ) )
			return $this->setting_row->auspost_api_key;
		else
			return "";
	}

	public function get_auspost_ship_from_zip( ){
		if( isset( $this->setting_row->auspost_ship_from_zip ) )
			return $this->setting_row->auspost_ship_from_zip;
		else
			return "";
	}
	
	public function get_dhl_site_id( ){
		if( isset( $this->setting_row->dhl_site_id ) )
			return $this->setting_row->dhl_site_id;
		else
			return "";
	}
	
	public function get_dhl_password( ){
		if( isset( $this->setting_row->dhl_password ) )
			return $this->setting_row->dhl_password;
		else
			return "";
	}
	
	public function get_dhl_ship_from_country( ){
		if( isset( $this->setting_row->dhl_ship_from_country ) )
			return $this->setting_row->dhl_ship_from_country;
		else
			return "";
	}
	
	public function get_dhl_ship_from_zip( ){
		if( isset( $this->setting_row->dhl_ship_from_zip ) )
			return $this->setting_row->dhl_ship_from_zip;
		else
			return "";
	}
	
	public function get_dhl_weight_unit( ){
		if( isset( $this->setting_row->dhl_weight_unit ) )
			return $this->setting_row->dhl_weight_unit;
		else
			return "";
	}
	
	public function get_dhl_test_mode( ){
		if( isset( $this->setting_row->dhl_test_mode ) )
			return $this->setting_row->dhl_test_mode;
		else
			return "";
	}
	
	public function get_fraktjakt_customer_id( ){
		if( isset( $this->setting_row->fraktjakt_customer_id ) )
			return $this->setting_row->fraktjakt_customer_id;
		else
			return "";
	}
	
	public function get_fraktjakt_login_key( ){
		if( isset( $this->setting_row->fraktjakt_login_key ) )
			return $this->setting_row->fraktjakt_login_key;
		else
			return "";
	}
	
	public function get_fraktjakt_conversion_rate( ){
		if( isset( $this->setting_row->fraktjakt_conversion_rate ) )
			return $this->setting_row->fraktjakt_conversion_rate;
		else
			return "";
	}
	
	public function get_fraktjakt_test_mode( ){
		if( isset( $this->setting_row->fraktjakt_test_mode ) )
			return $this->setting_row->fraktjakt_test_mode;
		else
			return "";
	}
	
	public function get_fraktjakt_address( ){
		if( isset( $this->setting_row->fraktjakt_address ) )
			return $this->setting_row->fraktjakt_address;
		else
			return "";
	}
	
	public function get_fraktjakt_city( ){
		if( isset( $this->setting_row->fraktjakt_city ) )
			return $this->setting_row->fraktjakt_city;
		else
			return "";
	}
	
	public function get_fraktjakt_state( ){
		if( isset( $this->setting_row->fraktjakt_state ) )
			return $this->setting_row->fraktjakt_state;
		else
			return "";
	}
	
	public function get_fraktjakt_zip( ){
		if( isset( $this->setting_row->fraktjakt_zip ) )
			return $this->setting_row->fraktjakt_zip;
		else
			return "";
	}
	
	public function get_fraktjakt_country( ){
		if( isset( $this->setting_row->fraktjakt_country ) )
			return $this->setting_row->fraktjakt_country;
		else
			return "";
	}
	
	public function get_canadapost_username( ){
		if( isset( $this->setting_row->canadapost_username ) )
			return $this->setting_row->canadapost_username;
		else
			return "";
	}
	
	public function get_canadapost_password( ){
		if( isset( $this->setting_row->canadapost_password ) )
			return $this->setting_row->canadapost_password;
		else
			return "";
	}
	
	public function get_canadapost_customer_number( ){
		if( isset( $this->setting_row->canadapost_customer_number ) )
			return $this->setting_row->canadapost_customer_number;
		else
			return "";
	}
	
	public function get_canadapost_contract_id( ){
		if( isset( $this->setting_row->canadapost_contract_id ) )
			return $this->setting_row->canadapost_contract_id;
		else
			return "";
	}
	
	public function get_canadapost_test_mode( ){
		if( isset( $this->setting_row->canadapost_test_mode ) )
			return $this->setting_row->canadapost_test_mode;
		else
			return "";
	}
	
	public function get_canadapost_ship_from_zip( ){
		if( isset( $this->setting_row->canadapost_ship_from_zip ) )
			return $this->setting_row->canadapost_ship_from_zip;
		else
			return "";
	}
	
}

?>