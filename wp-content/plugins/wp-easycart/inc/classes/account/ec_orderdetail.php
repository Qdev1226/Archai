<?php
class ec_orderdetail{
	protected $mysqli;// ec_db structure

	public $orderdetail_id;// INT
	public $order_id;// INT
	public $product_id;// INT
	public $title;// VARCHAR 255
	public $model_number;// VARCHAR 255
	public $order_date;// DATETIME
	public $unit_price;// FLOAT 7,2 
	public $total_price;// FLOAT 7,2
	public $quantity;// INT
	public $image1;// VARCHAR 255
	public $optionitem_name_1;// VARCHAR 128
	public $optionitem_name_2;// VARCHAR 128
	public $optionitem_name_3;// VARCHAR 128
	public $optionitem_name_4;// VARCHAR 128
	public $optionitem_name_5;// VARCHAR 128
	public $optionitem_label_1;// VARCHAR 128
	public $optionitem_label_2;// VARCHAR 128
	public $optionitem_label_3;// VARCHAR 128
	public $optionitem_label_4;// VARCHAR 128
	public $optionitem_label_5;// VARCHAR 128
	public $optionitem_price_1;// FLOAT 13,3
	public $optionitem_price_2;// FLOAT 13,3
	public $optionitem_price_3;// FLOAT 13,3
	public $optionitem_price_4;// FLOAT 13,3
	public $optionitem_price_5;// FLOAT 13,3
	public $use_advanced_optionset;// BOOL
	public $use_both_option_types;// BOOL
	public $giftcard_id;// VARCHAR 20
	public $gift_card_message;// TEXT
	public $gift_card_from_name;// VARCHAR 255
	public $gift_card_to_name;// VARCHAR 255
	public $is_download;// BOOL
	public $is_giftcard;// BOOL
	public $is_taxable;// BOOL
	public $is_shippable;// BOOL
	public $download_file_name;// VARCHAR 255
	public $download_id;// VARCHAR 255
	public $maximum_downloads_allowed;// INT
	public $download_timelimit_seconds;// INT
	public $is_amazon_download;// BOOL
	public $amazon_key;// VARCHAR 255
	public $subscription_signup_fee;// FLOAT 15,3
	public $manufacturer_name;

	public $include_code;// BOOL

	public $is_deconetwork;// BOOL
	public $deconetwork_id;// VARCHAR 64
	public $deconetwork_name;// VARCHAR 512
	public $deconetwork_product_code;// VARCHAR 64
	public $deconetwork_options;// VARCHAR 512
	public $deconetwork_color_code;// VARCHAR 64
	public $deconetwork_product_id;// VARCHAR 64
	public $deconetwork_image_link;// VARCHAR 512

	public $custom_vars = array();// Array

	private $download;// ec_download object
	public $timecheck;
	public $download_count;

	public $customfields = array();// array of ec_customfield objects

	public $store_page;// VARCHAR
	public $account_page;// VARCHAR
	public $cart_page;// VARCHAR
	public $permalink_divider;// CHAR

	function __construct( $orderdetail_row, $download_now = 0 ) {

		$this->mysqli = new ec_db();

		$this->orderdetail_id = $orderdetail_row->orderdetail_id;
		$this->order_id = $orderdetail_row->order_id;
		$this->product_id = $orderdetail_row->product_id;
		$this->title = $orderdetail_row->title;
		$this->model_number = $orderdetail_row->model_number;
		$this->order_date = $orderdetail_row->order_date;
		$this->unit_price = $orderdetail_row->unit_price;
		$this->total_price = $orderdetail_row->total_price;
		$this->quantity = $orderdetail_row->quantity;
		$this->image1 = $orderdetail_row->image1;
		$this->optionitem_name_1 = $orderdetail_row->optionitem_name_1;
		$this->optionitem_name_2 = $orderdetail_row->optionitem_name_2;
		$this->optionitem_name_3 = $orderdetail_row->optionitem_name_3;
		$this->optionitem_name_4 = $orderdetail_row->optionitem_name_4;
		$this->optionitem_name_5 = $orderdetail_row->optionitem_name_5;
		$this->optionitem_label_1 = $orderdetail_row->optionitem_label_1;
		$this->optionitem_label_2 = $orderdetail_row->optionitem_label_2;
		$this->optionitem_label_3 = $orderdetail_row->optionitem_label_3;
		$this->optionitem_label_4 = $orderdetail_row->optionitem_label_4;
		$this->optionitem_label_5 = $orderdetail_row->optionitem_label_5;
		$this->optionitem_price_1 = $orderdetail_row->optionitem_price_1;
		$this->optionitem_price_2 = $orderdetail_row->optionitem_price_2;
		$this->optionitem_price_3 = $orderdetail_row->optionitem_price_3;
		$this->optionitem_price_4 = $orderdetail_row->optionitem_price_4;
		$this->optionitem_price_5 = $orderdetail_row->optionitem_price_5;
		$this->use_advanced_optionset = ( isset( $orderdetail_row->use_advanced_optionset ) ) ? $orderdetail_row->use_advanced_optionset : false;
		$this->use_both_option_types = ( isset( $orderdetail_row->use_both_option_types ) ) ? $orderdetail_row->use_both_option_types : false;
		$this->giftcard_id = $orderdetail_row->giftcard_id;
		$this->gift_card_message = $orderdetail_row->gift_card_message;
		$this->gift_card_from_name = $orderdetail_row->gift_card_from_name;
		$this->gift_card_to_name = $orderdetail_row->gift_card_to_name;
		$this->is_download = $orderdetail_row->is_download;
		$this->is_giftcard = $orderdetail_row->is_giftcard;
		$this->is_taxable = $orderdetail_row->is_taxable;
		$this->is_shippable = $orderdetail_row->is_shippable;
		$this->manufacturer_name = $orderdetail_row->manufacturer_name;
		$this->download_file_name = $orderdetail_row->download_file_name;
		$this->download_id = $orderdetail_row->download_key;
		$this->maximum_downloads_allowed = $orderdetail_row->maximum_downloads_allowed;
		$this->download_timelimit_seconds = $orderdetail_row->download_timelimit_seconds;
		if ( isset( $orderdetail_row->is_amazon_download ) ) {
			$this->is_amazon_download = $orderdetail_row->is_amazon_download;
			$this->amazon_key = $orderdetail_row->amazon_key;
		}else{
			$this->is_amazon_download = false;
			$this->amazon_key = "";
		}

		if ( isset( $orderdetail_row->subscription_signup_fee ) )
			$this->subscription_signup_fee = $orderdetail_row->subscription_signup_fee;
		else
			$this->subscription_signup_fee = false;

		if ( isset( $orderdetail_row->is_deconetwork ) ) {
			$this->is_deconetwork = $orderdetail_row->is_deconetwork;
			$this->deconetwork_id = $orderdetail_row->deconetwork_id;
			$this->deconetwork_name = $orderdetail_row->deconetwork_name;
			$this->deconetwork_product_code = $orderdetail_row->deconetwork_product_code;
			$this->deconetwork_options = $orderdetail_row->deconetwork_options;
			$this->deconetwork_color_code = $orderdetail_row->deconetwork_color_code;
			$this->deconetwork_product_id = $orderdetail_row->deconetwork_product_id;
			$this->deconetwork_image_link = $orderdetail_row->deconetwork_image_link;
		}else{
			$this->is_deconetwork = false;
			$this->deconetwork_id = "";
			$this->deconetwork_name = "";
			$this->deconetwork_product_code = "";
			$this->deconetwork_options = "";
			$this->deconetwork_color_code = "";
			$this->deconetwork_product_id = "";
			$this->deconetwork_image_link = "";
		}

		if ( isset( $orderdetail_row->include_code ) )
			$this->include_code = $orderdetail_row->include_code;
		else
			$this->include_code = false;

		if ( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ) {
			for( $i=0;$i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] );$i++ ) {
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array(), array() );
				for( $j=0;$j<count( $arr );$j++ ) {
					$this->custom_vars[ $arr[$j] ] =  $orderdetail_row->{$arr[$j]};
				}
			}
		}

		if ( $this->is_download ) {
			$this->download = $this->mysqli->get_download( $this->download_id );
			$this->timecheck = date('U') - $this->download->date_created_timestamp;
			$this->download_count = $this->download->download_count;
		}

		$storepageid = get_option('ec_option_storepage');
		$accountpageid = get_option('ec_option_accountpage');
		$cartpageid = get_option('ec_option_cartpage');

		if ( function_exists( 'icl_object_id' ) ) {
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->store_page = get_permalink( $storepageid );
		$this->account_page = get_permalink( $accountpageid );
		$this->cart_page = get_permalink( $cartpageid );

		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
		}

		if ( substr_count( $this->account_page, '?' ) )				$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";

		if ( isset( $_GET['orderdetail_id'] ) && $_GET['orderdetail_id'] == $this->orderdetail_id && 
			isset( $_GET['download_id'] ) && $download_now ) {

			if ( $_GET['download_id'] == $this->download_id ) { // Basic Download Found
				$this->start_download();

			}else{ // Try to find in advanced options
				$found = false;
				$advanced_options = $this->mysqli->get_order_options( $this->orderdetail_id );
				for( $i=0;$i<count( $advanced_options );$i++ ) {
					if ( $advanced_options[$i]->download_addition_file == $_GET['download_id'] ) {
						$found = true;
						$this->download = $this->mysqli->get_download( sanitize_text_field( $_GET['download_id'] ) );
						$this->timecheck = date('U') - $this->download->date_created_timestamp;
						$this->download_count = $this->download->download_count;
						break;
					}
				}
				if ( $found ) {
					$this->start_download();
				}
			}
		}

		$customfield_data_array = explode( "---", $orderdetail_row->customfield_data );
		for( $i=0;$i<count( $customfield_data_array );$i++ ) {
			$temp_arr = explode("***", $customfield_data_array[$i]);
			array_push($this->customfields, $temp_arr);
		}
	}

	public function get_product_link() {
		global $wpdb;
		$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.model_number, ec_product.post_id FROM ec_product WHERE ec_product.product_id = %d AND ec_product.activate_in_store", $this->product_id ) );
		if ( $product ) {
			$permalink =  $this->ec_get_permalink( $product->post_id, $product->model_number );
			return $permalink;
		}else
			return false;
	}

	private function ec_get_permalink( $post_id, $model_number ) {

		if ( !get_option( 'ec_option_use_old_linking_style' ) && $post_id != "0" ) {
			return get_permalink( $post_id );
		}else{
			return $this->store_page . $this->permalink_divider . "model_number=" . $model_number;
		}

	}

	public function display_order_item_id() {
		echo esc_attr( $this->orderdetail_id );
	}

	public function display_image( $size ) {
		if ( $this->is_deconetwork ) {
			echo '<img src="https://' . esc_url( get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_image_link ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( substr( $this->image1, 0, 7 ) == 'http://' || substr( $this->image1, 0, 8 ) == 'https://' ) {
			echo '<img src="' . esc_url( $this->image1 ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->image1 ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->image1 ) ) {
			echo '<img src="' . esc_url( plugins_url( "wp-easycart-data/products/pics1/" . $this->image1, EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( file_exists( EC_PLUGIN_DIRECTORY . "/products/pics1/" . $this->image1 ) && !is_dir( EC_PLUGIN_DIRECTORY . "/products/pics1/" . $this->image1 ) ) {
			echo '<img src="' . esc_url( plugins_url( "wp-easycart/products/pics1/" . $this->image1, EC_PLUGIN_DIRECTORY ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			echo '<img src="' . esc_url( get_option( 'ec_option_product_image_default' ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( file_exists( EC_PLUGIN_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_image_not_found.jpg" ) ) {
			echo '<img src="' . esc_url( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_image_not_found.jpg" ) ) {
			echo '<img src="' . esc_url( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg"  ) ) {
			echo '<img src="' . esc_url( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';

		} else {
			echo '<img src="' . esc_url( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY ) ) . '" alt"' . esc_attr( $this->model_number ) . '" />';
		}
	}

	public function display_title() {
		if ( $this->is_deconetwork )
			echo esc_attr( $this->deconetwork_name );
		else
			echo wp_easycart_language()->convert_text( $this->title );
	}

	public function has_option1() {
		if ( $this->optionitem_name_1 != "" )
			return true;
		else
			return false;
	}

	public function display_option1() {
		if ( $this->optionitem_name_1 != "" )
			echo esc_attr( $this->optionitem_name_1 );
	}

	public function display_option1_label() {
		if ( $this->optionitem_label_1 != "" )
			echo esc_attr( $this->optionitem_label_1 );
	}

	public function has_option1_price() {
		if ( $this->optionitem_price_1 != "0.00" )
			return true;
		else
			return false;
	}

	public function display_option1_price() {
		if ( $this->optionitem_price_1 < 0 )
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_1 ) );
		else if ( $this->optionitem_price_1 > 0 )
			echo "+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_1 ) );
	}

	public function has_option2() {
		if ( $this->optionitem_name_2 != "" )
			return true;
		else
			return false;
	}

	public function display_option2() {
		if ( $this->optionitem_name_2 != "" )
			echo esc_attr( $this->optionitem_name_2 );
	}

	public function display_option2_label() {
		if ( $this->optionitem_label_2 != "" )
			echo esc_attr( $this->optionitem_label_2 );
	}

	public function has_option2_price() {
		if ( $this->optionitem_price_2 != "0.00" )
			return true;
		else
			return false;
	}

	public function display_option2_price() {
		if ( $this->optionitem_price_2 < 0 )
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_2 ) );
		else if ( $this->optionitem_price_2 > 0 )
			echo "+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_2 ) );
	}

	public function has_option3() {
		if ( $this->optionitem_name_3 != "" )
			return true;
		else
			return false;
	}

	public function display_option3() {
		if ( $this->optionitem_name_3 != "" )
			echo esc_attr( $this->optionitem_name_3 );
	}

	public function display_option3_label() {
		if ( $this->optionitem_label_3 != "" )
			echo esc_attr( $this->optionitem_label_3 );
	}

	public function has_option3_price() {
		if ( $this->optionitem_price_3 != "0.00" )
			return true;
		else
			return false;
	}

	public function display_option3_price() {
		if ( $this->optionitem_price_3 < 0 )
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_3 ) );
		else if ( $this->optionitem_price_3 > 0 )
			echo "+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_3 ) );
	}

	public function has_option4() {
		if ( $this->optionitem_name_4 != "" )
			return true;
		else
			return false;
	}

	public function display_option4() {
		if ( $this->optionitem_name_4 != "" )
			echo esc_attr( $this->optionitem_name_4 );
	}

	public function display_option4_label() {
		if ( $this->optionitem_label_4 != "" )
			echo esc_attr( $this->optionitem_label_4 );
	}

	public function has_option4_price() {
		if ( $this->optionitem_price_4 != "0.00" )
			return true;
		else
			return false;
	}

	public function display_option4_price() {
		if ( $this->optionitem_price_4 < 0 )
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_4 ) );
		else if ( $this->optionitem_price_4 > 0 )
			echo "+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_4 ) );
	}

	public function has_option5() {
		if ( $this->optionitem_name_5 != "" )
			return true;
		else
			return false;
	}

	public function display_option5() {
		if ( $this->optionitem_name_5 != "" )
			echo esc_attr( $this->optionitem_name_5 );
	}

	public function display_option5_label() {
		if ( $this->optionitem_label_5 != "" )
			echo esc_attr( $this->optionitem_label_5 );
	}

	public function has_option5_price() {
		if ( $this->optionitem_price_5 != "0.00" )
			return true;
		else
			return false;
	}

	public function display_option5_price() {
		if ( $this->optionitem_price_5 < 0 )
			echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_5 ) );
		else if ( $this->optionitem_price_5 > 0 )
			echo "+" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->optionitem_price_5 ) );
	}

	public function has_gift_card_message() {
		if ( $this->is_giftcard )
			return true;
		else
			return false;
	}

	public function display_gift_card_message( $label_text ) {
		if ( $this->is_giftcard )
			echo esc_attr( $label_text . htmlspecialchars( $this->gift_card_message, ENT_QUOTES ) );
	}

	public function has_gift_card_from_name() {
		if ( $this->is_giftcard )
			return true;
		else
			return false;
	}

	public function display_gift_card_from_name( $label_text ) {
		if ( $this->is_giftcard )
			echo esc_attr( $label_text . htmlspecialchars( $this->gift_card_from_name, ENT_QUOTES ) );
	}

	public function has_gift_card_to_name() {
		if ( $this->is_giftcard )
			return true;
		else
			return false;
	}

	public function display_gift_card_to_name( $label_text ) {
		if ( $this->is_giftcard )
			echo esc_attr( $label_text . htmlspecialchars( $this->gift_card_to_name, ENT_QUOTES ) );
	}

	public function display_gift_card_id( $label_text ) {
		if ( $this->is_giftcard )
			echo esc_attr( $label_text . $this->giftcard_id );
	}

	public function has_print_gift_card_link() {
		if ( $this->is_giftcard )
			return true;
		else
			return false;
	}

	public function display_print_online_link( $link_text ) {
		if ( $this->is_giftcard )
			echo '<a href="' . esc_url( get_site_url() . '?wpeasycarthook=print-giftcard&order_id=' . $this->order_id . '&orderdetail_id=' . $this->orderdetail_id . '&giftcard_id=' . $this->giftcard_id ) . '" target="_blank">' . esc_attr( $link_text ) . '</a>';
	}

	public function display_unit_price() {
		echo esc_attr( apply_filters( 'wp_easycart_cart_item_unit_price_display', $GLOBALS['currency']->get_currency_display( $this->unit_price ), $this->product_id ) );
	}

	public function display_quantity() {
		echo esc_attr( $this->quantity );
	}

	public function display_item_total() {
		echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->total_price ) );
	}

	public function display_custom_fields( $divider, $seperator ) {
		for( $i=0;$i<count( $this->customfields ) && count( $this->customfields[$i] ) == 3;$i++ ) {
			echo esc_attr( $this->customfields[$i][1] . $divider . " " . $this->customfields[$i][2] . $seperator );
		}
	}

	public function has_download_link() {
		if ( $this->is_download )
			return true;
		else
			return false;
	}

	public function display_download_link( $link_text, $additional_downloads = array() ) {
		if ( $this->is_download ) {
			echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider . "ec_page=order_details&amp;order_id=" . $this->order_id . "&amp;orderdetail_id=" . $this->orderdetail_id . "&amp;download_id=" . $this->download_id ) . "\" class=\"ec_account_order_item_download_button\" onclick=\"update_download_count( '" . esc_attr( $this->orderdetail_id ) . "' );\">" . esc_attr( $link_text . ( ( count( $additional_downloads ) > 0 ) ? ' 1' : '' ) ) . "</a>";
		}
		if ( count( $additional_downloads ) > 0 ) {
			for( $i=0;$i<count( $additional_downloads );$i++ ) {
				echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider . "ec_page=order_details&amp;order_id=" . $this->order_id . "&amp;orderdetail_id=" . $this->orderdetail_id . "&amp;download_id=" . $additional_downloads[$i] ) . "\" class=\"ec_account_order_item_download_button\">" . esc_attr( $link_text . " " . ( $i+2 ) ) . "</a>";
			}
		}
	}

	public function get_download_link(  ) {
		if ( $this->is_download )
			return $this->account_page . $this->permalink_divider . "ec_page=order_details&amp;order_id=" . $this->order_id . "&amp;orderdetail_id=" . $this->orderdetail_id . "&amp;download_id=" . $this->download_id;
	}

	public function display_download_error() {
		if ( $this->is_download ) {
			if ( $this->download_timelimit_seconds > 0 && $this->timecheck >= $this->download_timelimit_seconds )
				echo "<div class=\"ec_account_error\"><div>The download has expired because you have exceeded the length of time that you have to access and download this product.</div></div>";

			else if ($this->maximum_downloads_allowed > 0 && $this->download_count >= $this->maximum_downloads_allowed)
				echo "<div class=\"ec_account_error\"><div>The download key has expired because you have exceeded your download limit set for this product.</div></div>";
		}
	}

	public function get_download_expire_date( $date_format ) {
		$date = new DateTime();
		$seconds_remaining = $this->download_timelimit_seconds - $this->timecheck;
		if ( $seconds_remaining < 0 ) {
			$seconds_remaining = ( $seconds_remaining * -1 );
			$date->sub( new DateInterval('PT' . $seconds_remaining . 'S' ) );
		}else
			$date->add( new DateInterval('PT' . $seconds_remaining . 'S' ) );

		return $date->format( $date_format );
	}

	private function start_download() {
		if ( $this->is_download ) {

			$this->download_count++;

			if ( ( $this->download_timelimit_seconds == 0 || $this->timecheck <= $this->download_timelimit_seconds ) && 
				( $this->maximum_downloads_allowed  == 0 || $this->download_count <= $this->maximum_downloads_allowed  ) ) {


				if ( $this->is_amazon_download ) {

					if ( phpversion() >= 5.3 ) {

						require_once( EC_PLUGIN_DIRECTORY . "/inc/classes/account/ec_amazons3.php" );
						$amazons3 = new ec_amazons3();
						$this->mysqli->update_download_count( $this->download_id, $this->download_count );
						$amazons3->download_file( $this->amazon_key );

					}

				}else{

					if ( strstr( $this->download->download_file_name, EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" ) && file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" . basename( $this->download->download_file_name ) ) ) {
						$this->download->download_file_name = basename( $this->download->download_file_name );

					}else if ( strstr( $this->download->download_file_name, "uploads/wp-easycart" ) && file_exists( WP_CONTENT_DIR . "/uploads/wp-easycart/" . basename( $this->download->download_file_name ) ) ) {
						$this->download->download_file_name = basename( $this->download->download_file_name );
					}

					if ( substr( $this->download->download_file_name, 0, 7 ) == "http://" || substr( $this->download->download_file_name, 0, 8 ) == "https://" ) {
						$this->mysqli->update_download_count( $this->download_id, $this->download_count );
						$file = $this->download->download_file_name;
						header( "location:" . $file );
						die();

					}else{

						if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" . $this->download->download_file_name ) )	
							$file = EC_PLUGIN_DATA_DIRECTORY . "/products/downloads/" . $this->download->download_file_name;

						else if ( file_exists( WP_CONTENT_DIR . "/uploads/wp-easycart/" . $this->download->download_file_name ) )	
							$file = WP_CONTENT_DIR . "/uploads/wp-easycart/" . $this->download->download_file_name;

						else
							$file = EC_PLUGIN_DIRECTORY . "/products/downloads/" . $this->download->download_file_name;

						if ( file_exists( $file ) ) {

							$ext = substr( $this->download->download_file_name, strrpos( $this->download->download_file_name, '.' ) + 1);

							switch( $ext ) {
								case "pdf":
									$mm_type="application/pdf";
									break;
								case "zip":
									$mm_type="application/zip";
									break;
								case "xlsx":
									$mm_type="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";
									break;
								case "xls":
									$mm_type="application/vnd.ms-excel";
									break;
								default:
									$mm_type="application/octet-stream";
									break;
							}

							$date = new DateTime();
							if ( method_exists( $date, 'getTimestamp' ) ) {
								$time_stamp = $date->getTimestamp();
							}else{
								$time_stamp = $date->format('U');
							}

							if ( strstr( $this->download->download_file_name, '_' ) ) {
								$filename_arr = explode( "_", $this->download->download_file_name );
								$file_start_name = "";
								for( $i=0;$i<count($filename_arr)-1;$i++ ) {
									if ( $i>0 )
										$file_start_name .= "_";
									$file_start_name .= $filename_arr[$i];
								}
							}else{
								$filename_arr = explode( ".", $this->download->download_file_name );
								$file_start_name = "";
								for( $i=0;$i<count($filename_arr)-1;$i++ ) {
									if ( $i>0 )
										$file_start_name .= "_";
									$file_start_name .= $filename_arr[$i];
								}
							}

							$filename = $file_start_name . "_" . $time_stamp . "." . $ext;

							header( "Cache-Control: public, must-revalidate" );
							header( "Pragma: no-cache" );
							header( "Content-Type: " . $mm_type );
							header( "Content-Length: " . ( string )( filesize( $file ) ) );
							header( 'Content-Disposition: attachment;filename="' . $file_start_name . "." . $ext . '"' );
							header( "Content-Transfer-Encoding: binary\n" );

							if ( $mm_type == "application/zip" ) {

								readfile( $file );

							}else{

								$fh = fopen( $file, "rb" );

								while( !feof( $fh ) ) {
									$buffer = fread( $fh, 8192 );
									echo $buffer;// XSS OK. Outputting Customer File to Download.
									flush();
									ob_flush();
								}

								fclose( $fh );

							}

							$this->mysqli->update_download_count( $this->download_id, $this->download_count );
							die();

						}else{

							echo "This download is not available on the sever.";
							die();

						}
					}
				}
			}
		}
	}
}