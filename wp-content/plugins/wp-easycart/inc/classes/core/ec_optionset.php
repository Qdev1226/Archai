<?php

class ec_optionset{
	private $mysqli;									// ec_db structure
	
	public $option_id;									// INT
	public $option_name;								// VARCHAR 255
	public $option_label;								// VARCHAR 255
	public $option_meta;								// Array of meta
	public $option_type;								// VARCHAR 255
	public $optionset = array();						// Array of ec_optionitem
	
	function __construct( $option_id ){
		if( $option_id == 0 )
			return;
		global $wpdb;
		$optionset = wp_cache_get( "wpeasycart-optionset-".$option_id, 'wpeasycart-options' );
		if( !$optionset ){
			$optionset = $wpdb->get_row( $wpdb->prepare( "SELECT ec_option.* FROM ec_option WHERE ec_option.option_id = %d", $option_id ) );
			wp_cache_set( "wpeasycart-optionset-".$option_id, $optionset, 'wpeasycart-options' );
		}
		if( $optionset ){
			$this->option_id = $optionset->option_id;
			$this->option_name = wp_easycart_language( )->convert_text( $optionset->option_name );
			$this->option_label = wp_easycart_language( )->convert_text( $optionset->option_label );
			$this->option_type = $optionset->option_type;
			if( $optionset->option_meta ){
				$this->option_meta = maybe_unserialize( $optionset->option_meta );
			}else{
				$this->option_meta = array(
					"min" => "",
					"max" => "",
					"step" => "",
					"url_var" => "",
					"swatch_size" => "30"
				);
			}
			$optionitems = wp_cache_get( "wpeasycart-optionitems-".$option_id, 'wpeasycart-options' );
			if( !$optionitems ){
				$optionitems = $wpdb->get_results( $wpdb->prepare( "SELECT ec_optionitem.* FROM ec_optionitem WHERE ec_optionitem.option_id = %d ORDER BY optionitem_order ASC", $option_id ) );
				$optionitems = apply_filters( 'wpeasycart_basic_optionitems', $optionitems );
				wp_cache_set( "wpeasycart-optionitems-".$option_id, $optionitems, 'wpeasycart-options' );
			}
			for( $i=0; $i<count( $optionitems ); $i++ ){
				array_push( $this->optionset, new ec_optionitem( $this->option_id, $optionitems[$i] ) );
			}
		}
	}
	
	public function is_combo( ){
		if( count( $this->optionset ) > 0 && $this->optionset[0]->optionitem_name && $this->optionset[0]->optionitem_name != "" && $this->optionset[0]->optionitem_icon == "" )
			return true;
		else
			return false;
	}
	
	public function is_swatch( ){
		if( count($this->optionset ) > 0 && $this->optionset[0]->optionitem_icon != "" )
			return true;
		else
			return false;
	}
	
}

?>