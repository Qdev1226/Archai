<?php

class ec_optionitem{
	
	public $option_id;									// INT
	public $optionitem_id;								// INT
	public $optionitem_name;							// VARCHAR 33
	public $optionitem_enable_custom_price_label;		// BOOL
	public $optionitem_custom_price_label;				// TEXT
	public $optionitem_price;							// FLOAT 7,2
	public $optionitem_price_multiplier;				// FLOAT 7,2
	public $optionitem_price_onetime;					// FLOAT 7,2
	public $optionitem_icon;							// VARCHAR 512
	public $optionitem_initially_selected;				// BOOL
	
	function __construct( $option_id, $optionitem_data ){
		$this->option_id = $option_id;
		$this->optionitem_id = $optionitem_data->optionitem_id;
		$this->optionitem_name = wp_easycart_language( )->convert_text( $optionitem_data->optionitem_name );
		$this->optionitem_enable_custom_price_label = (int) $optionitem_data->optionitem_enable_custom_price_label;
		$this->optionitem_custom_price_label = wp_easycart_language( )->convert_text( $optionitem_data->optionitem_custom_price_label );
		$this->optionitem_price = $optionitem_data->optionitem_price;
		$this->optionitem_price_onetime  = $optionitem_data->optionitem_price_onetime ;
		$this->optionitem_price_multiplier = $optionitem_data->optionitem_price_multiplier;
		$this->optionitem_icon = $optionitem_data->optionitem_icon;
		$this->optionitem_initially_selected = $optionitem_data->optionitem_initially_selected;
	}
	
	public function get_optionitem_label( ){
		if($this->optionitem_price != 0.00){
			if( $this->optionitem_price > 0.00 ){
				return $this->optionitem_name . " (+" . $GLOBALS['currency']->get_currency_display( $this->optionitem_price ) . ")";
			}else{
				return $this->optionitem_name . " (" . $GLOBALS['currency']->get_currency_display( $this->optionitem_price ) . ")";
			}
		}else{
			return $this->optionitem_name;
		}
	}

}

?>