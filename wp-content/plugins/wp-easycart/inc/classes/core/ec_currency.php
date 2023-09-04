<?php 

class ec_currency{
	
	/* Keep for backward compatibility */
	public $symbol;
	public $symbol_location;
	public $negative_location;
	public $decimal_symbol;
	public $decimal_length;
	public $grouping_symbol;
	public $currency_code;
	public $show_currency_code;
	public $conversion_rate;
	/* end */
	
	public static $static_symbol;
	public static $static_symbol_location;
	public static $static_negative_location;
	public static $static_decimal_symbol;
	public static $static_decimal_length;
	public static $static_grouping_symbol;
	public static $static_currency_code;
	public static $static_show_currency_code;
	public static $static_conversion_rate;
	
	function __construct( ){
		self::$static_symbol = $symbol = get_option( 'ec_option_currency' );
		self::$static_symbol_location = $symbol_location = get_option( 'ec_option_currency_symbol_location' );
		self::$static_negative_location = $negative_location = get_option( 'ec_option_currency_negative_location' );
		self::$static_decimal_symbol = $decimal_symbol = get_option( 'ec_option_currency_decimal_symbol' );
		if(  get_option( 'ec_option_currency_decimal_places' ) == '' || is_nan(  get_option( 'ec_option_currency_decimal_places' ) ) ||  get_option( 'ec_option_currency_decimal_places' ) < 0 ){
			self::$static_decimal_length = $decimal_length = 2;
		}else{
			self::$static_decimal_length = $decimal_length = get_option( 'ec_option_currency_decimal_places' );
		}
		self::$static_grouping_symbol = $grouping_symbol = get_option( 'ec_option_currency_thousands_seperator' );
		self::$static_currency_code = $currency_code = get_option( 'ec_option_base_currency' );
		self::$static_show_currency_code = $show_currency_code = get_option( 'ec_option_show_currency_code' );
		self::$static_conversion_rate = $conversion_rate = 1.000;
		
		if( ( !is_admin( ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) && isset( $_COOKIE['ec_convert_to'] ) ){
			$from = strtoupper( get_option( 'ec_option_base_currency' ) );
			$to = substr( preg_replace( '/[^A-Z]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_convert_to'] ) ) ), 0, 3 );
			self::$static_currency_code = $to;
			if( $to != $from ){
				if( get_option( 'ec_option_payment_third_party' ) == 'payfort' && get_option( 'ec_option_payfort_use_currency_service' ) ){
					$payfort = new ec_payfort( );
					$exchange_rate = $payfort->convert_price( 1 );
					$rates = array( $to . "=" . $exchange_rate );
				}else{
					$rates = explode( ",", get_option( 'ec_option_exchange_rates' ) );
				}
				//Set Symbol
				self::$static_symbol = self::get_currency_symbol( $to );
				//Get rate from string
				for( $i=0; $i<count( $rates ); $i++ ){
					$rate = explode( "=", $rates[$i] );
					if( $rate[0] == $to ){
						self::$static_conversion_rate = $rate[1];
						break;
					}
				}
			}else{ self::$static_symbol = self::get_currency_symbol( $to ); }
		}
	}
	
	public static function get_stats_currency_display( $amount ){
		$display_amount = '';
		
		//no currency code display for stats
		
		if( $amount < 0 && self::$static_negative_location )
			$display_amount .= '-';
		
		if( self::$static_symbol_location )
			$display_amount .= self::$static_symbol;
			
		if( $amount < 0 && !self::$static_negative_location )
			$display_amount .= '-';
		
		if( $amount < 0 )
		$amount = $amount * -1;
		
		$amount = floatval( $amount );
		$amount = $amount * self::$static_conversion_rate;
		$display_amount .= number_format( $amount, self::$static_decimal_length, self::$static_decimal_symbol, self::$static_grouping_symbol );
		
		if( !self::$static_symbol_location )
			$display_amount .= self::$static_symbol;
			
		return $display_amount;
	}
	
	
	public static function get_currency_display( $amount, $convert = true ){
		$display_amount = '';
		
		if( self::$static_show_currency_code )
			$display_amount .= self::$static_currency_code . ' ';
		
		if( $amount < 0 && self::$static_negative_location )
			$display_amount .= '-';
		
		if( self::$static_symbol_location )
			$display_amount .= self::$static_symbol;
			
		if( $amount < 0 && !self::$static_negative_location )
			$display_amount .= '-';
		
		if( $amount < 0 )
		$amount = $amount * -1;
		
		$amount = floatval( $amount );
		if( $convert )
			$amount = $amount * self::$static_conversion_rate;
		$display_amount .= number_format( $amount, self::$static_decimal_length, self::$static_decimal_symbol, self::$static_grouping_symbol );
		
		if( !self::$static_symbol_location )
			$display_amount .= self::$static_symbol;
			
		return $display_amount;
	}
	
	public static function get_number_only( $amount ){
		$amount = floatval( $amount );
		//convert if needed
		$amount = $amount * self::$static_conversion_rate;
		return number_format( $amount, self::$static_decimal_length, self::$static_decimal_symbol, '' );
	}
	
	public static function get_number_safe( $amount ){
		$amount = floatval( $amount );
		//convert if needed
		$amount = $amount * self::$static_conversion_rate;
		return number_format( $amount, self::$static_decimal_length, '.', '' );
	}
	
	public static function format_cents( $cents ){
		return substr( $cents, 0, self::$static_decimal_length );
	}
	
	public static function convert_price( $amount ){
		$amount = number_format( $amount * self::$static_conversion_rate, self::$static_decimal_length, '.', '' );
		return $amount;
	}
	
	public static function get_currency_symbol( $static_currency_code ){
		$currencies = array( 'AED' => 'د.إ',
							 'ALL' => 'Lek',
							 'AFN' => '؋', 
							 'ARS' => '$',
							 'AWG' => 'ƒ',
							 'AUD' => '$', 
							 'AZN' => 'ман', 
							 'BSD' => '$', 
							 'BBD' => '$',
							 'BYR' => 'p.', 
							 'BZD' => 'BZ$', 
							 'BMD' => '$',
							 'BOB' => '$b',	 
							 'BAM' => 'KM', 
							 'BWP' => 'P', 
							 'BGN' => 'лв', 
							 'BRL' => 'R$',
							 'BND' => '$',
							 'KHR' => '៛	៛',
							 'CAD' => '$',
							 'KYD' => '$',
							 'CLP' => '$',
							 'CNY' => '¥',
							 'COP' => '$',
							 'CRC' => '₡', 
							 'HRK' => 'kn',
							 'CUP' => '₱',	 
							 'CZK' => 'Kč', 
							 'DKK' => 'kr',
							 'DOP' => 'RD$',
							 'XCD' => '$', 
							 'EGP' => '£', 
							 'SVC' => '$', 
							 'EEK' => 'kr',
							 'EUR' => '€', 
							 'FKP' => '£',
							 'FJD' => '$',
							 'GHC' => '¢', 
							 'GIP' => '£',	 
							 'GTQ' => 'Q', 
							 'GGP' => '£',	 
							 'GYD' => '$', 
							 'HNL' => 'L',
							 'HKD' => '$',
							 'HUF' => 'Ft',
							 'ISK' => 'kr',
							 'INR' => 'INR',
							 'IDR' => 'Rp',
							 'IRR' => '﷼',
							 'IMP' => '£',
							 'ILS' => '₪', 
							 'JMD' => 'J$',
							 'JPY' => '¥',
							 'JEP' => '£',
							 'KZT' => 'лв',
							 'KPW' => '₩', 
							 'KRW' => '₩',
							 'KGS' => 'лв', 
							 'LAK' => '₭',
							 'LVL' => 'Ls',
							 'LBP' => '£',
							 'LRD' => '$',
							 'LTL' => 'Lt',
							 'MKD' => 'ден',
							 'MYR' => 'RM',
							 'MUR' => '₨',
							 'MXN' => '$',
							 'MNT' => '₮',
							 'MZN' => 'MT',
							 'NAD' => '$',
							 'NPR' => '₨',
							 'ANG' => 'ƒ',
							 'NZD' => '$',
							 'NIO' => 'C$',
							 'NGN' => '₦',
							 'KPW' => '₩',
							 'NOK' => 'kr',
							 'OMR' => '﷼',
							 'PKR' => '₨',
							 'PAB' => 'B/.',
							 'PYG' => 'Gs',
							 'PEN' => 'S/.',
							 'PHP' => '₱',
							 'PLN' => 'zł',
							 'QAR' => '﷼',
							 'RON' => 'lei',
							 'RUB' => 'руб',
							 'SHP' => '£',
							 'SAR' => '﷼',
							 'RSD' => 'Дин.',
							 'SCR' => '₨',
							 'SGD' => '$',
							 'SBD' => '$',
							 'SOS' => 'S',
							 'ZAR' => 'R',
							 'KRW' => '₩',
							 'LKR' => '₨',
							 'SEK' => 'kr',
							 'CHF' => 'CHF',
							 'SRD' => '$',
							 'SYP' => '£',
							 'TWD' => 'NT$',
							 'THB' => '฿',
							 'TTD' => 'TT$',
							 'TRY' => 'TRY',
							 'TRL' => '₤',
							 'TVD' => '$',
							 'UAH' => '₴',
							 'GBP' => '£',
							 'USD' => '$',
							 'UYU' => '$U',
							 'UZS' => 'лв',
							 'VEF' => 'Bs',
							 'VND' => '₫',
							 'YER' => '﷼',
							 'ZWD' => 'Z$'
							);
							
		return $currencies[$static_currency_code];
	}
	
	public static function get_symbol( ){
		return self::$static_symbol;
	}
	public static function get_symbol_location( ){
		return self::$static_symbol_location;
	}
	public static function get_negative_location( ){
		return self::$static_negative_location;
	}
	public static function get_decimal_symbol( ){
		return self::$static_decimal_symbol;
	}
	public static function get_decimal_length( ){
		return self::$static_decimal_length;
	}
	public static function get_grouping_symbol( ){
		return self::$static_grouping_symbol;
	}
	public static function get_currency_code( ){
		return self::$static_currency_code;
	}
	public static function get_show_currency_code( ){
		return self::$static_show_currency_code;
	}
	public static function get_conversion_rate( ){
		return self::$static_conversion_rate;
	}
}

?>