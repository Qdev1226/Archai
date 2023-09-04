<?php

class ec_cartitem {

	public $mysqli;

	public $orderdetail_id = 0;
	public $cartitem_id;
	public $product_id;
	public $model_number;
	public $orderdetails_model_number;
	public $post_id;
	public $guid;
	public $manufacturer_id;
	public $manufacturer_name;

	public $quantity;
	public $show_stock_quantity;
	public $min_purchase_quantity;
	public $max_purchase_quantity;
	public $grid_quantity;
	public $weight;
	public $total_weight;
	public $width;
	public $height;
	public $length;
	public $shipping_class_id;
	public $shipping_restriction;

	public $title;
	public $description;

	public $unit_price;
	public $gift_card_value;
	public $total_price;
	public $converted_total_price;
	public $prev_price;
	public $handling_price;
	public $handling_price_each;
	public $discount_price;
	public $pricetiers = array();
	public $enable_price_label;
	public $replace_price_label;
	public $custom_price_label;

	public $options_price_onetime;
	public $grid_price_change;

	public $vat_enabled;

	public $is_giftcard;
	public $is_download;
	public $is_donation;
	public $is_taxable;
	public $is_shippable;
	public $exclude_shippable_calculation;
	public $ship_to_billing;
	public $is_amazon_download;
	public $include_code;
	public $TIC;

	public $allow_backorders;
	public $backorder_fill_date;
	public $stock_quantity;

	public $image1;
	public $image2;
	public $image3;
	public $image4;
	public $image5;
	public $product_images;
	public $image1_optionitem;

	public $optionitem1_name;
	public $optionitem2_name;
	public $optionitem3_name;
	public $optionitem4_name;
	public $optionitem5_name;

	public $optionitem1_label;
	public $optionitem2_label;
	public $optionitem3_label;
	public $optionitem4_label;
	public $optionitem5_label;

	public $optionitem1_price;
	public $optionitem2_price;
	public $optionitem3_price;
	public $optionitem4_price;
	public $optionitem5_price;

	public $optionitem1_weight;
	public $optionitem2_weight;
	public $optionitem3_weight;
	public $optionitem4_weight;
	public $optionitem5_weight;

	public $optionitem1_id;
	public $optionitem2_id;
	public $optionitem3_id;
	public $optionitem4_id;
	public $optionitem5_id;

	public $advanced_options;
	public $use_advanced_optionset;
	public $use_both_option_types;

	public $custom_vars = array();

	public $giftcard_id = 0;
	public $gift_card_message;
	public $gift_card_from_name;
	public $gift_card_to_name;
	public $gift_card_email;

	public $donation_price;

	public $is_deconetwork;
	public $deconetwork_id;
	public $deconetwork_name;
	public $deconetwork_product_code;
	public $deconetwork_options;
	public $deconetwork_edit_link;
	public $deconetwork_color_code;
	public $deconetwork_product_id;
	public $deconetwork_image_link;
	public $deconetwork_discount;
	public $deconetwork_tax;
	public $deconetwork_total;
	public $deconetwork_version;

	public $has_affiliate_rule;
	public $affiliate_rule;

	public $download_id = 0;
	public $download_file_name;
	public $amazon_key;
	public $maximum_downloads_allowed;
	public $download_timelimit_seconds;

	public $use_optionitem_quantity_tracking;
	public $optionitem_stock_quantity;
	public $track_quantity;
	public $max_quantity;
	public $min_quantity;

	public $is_subscription_item;
	public $subscription_bill_length;
	public $subscription_bill_period;
	public $subscription_bill_duration;
	public $trial_period_days;
	public $subscription_signup_fee;
	public $subscription_prorate;
	public $stripe_plan_added;
	public $subscription_unique_id;

	public $promotions;

	public $store_page;
	public $cart_page;
	public $permalink_divider;

	function __construct( $cartitem_data ) {
		$this->mysqli = new ec_db();
		global $wpdb;

		$this->cartitem_id = $cartitem_data->cartitem_id;
		$this->product_id = $cartitem_data->product_id;
		$this->model_number = $cartitem_data->model_number;
		$this->orderdetails_model_number = $cartitem_data->model_number;
		$this->post_id = $cartitem_data->post_id;
		$this->guid = $cartitem_data->guid;
		$this->manufacturer_id = $cartitem_data->manufacturer_id;
		$this->manufacturer_name = $cartitem_data->manufacturer_name;

		$this->advanced_options = $GLOBALS['ec_cart_data']->get_advanced_cart_options( $this->cartitem_id );

		$this->quantity = $cartitem_data->quantity;
		$this->show_stock_quantity = $cartitem_data->show_stock_quantity;
		$this->min_purchase_quantity = $cartitem_data->min_purchase_quantity;
		$this->max_purchase_quantity = $cartitem_data->max_purchase_quantity;
		$this->grid_quantity = $cartitem_data->grid_quantity;
		if ( $this->grid_quantity > 0 ) {
			$this->quantity = $this->grid_quantity;
		}
		$this->weight = $cartitem_data->weight;
		$this->width = $cartitem_data->width;
		$this->height = $cartitem_data->height;
		$this->length = $cartitem_data->length;
		$this->shipping_class_id = $cartitem_data->shipping_class_id;
		$this->shipping_restriction = $cartitem_data->shipping_restriction;

		$this->title = wp_easycart_language()->convert_text( $cartitem_data->title );
		$this->description = wp_easycart_language()->convert_text( $cartitem_data->description );

		$this->is_giftcard = $cartitem_data->is_giftcard;
		$this->is_download = $cartitem_data->is_download;
		$this->is_donation = $cartitem_data->is_donation;
		$this->is_taxable = $cartitem_data->is_taxable;
		$this->is_shippable = $cartitem_data->is_shippable;
		$this->exclude_shippable_calculation = $cartitem_data->exclude_shippable_calculation;
		$this->ship_to_billing = $cartitem_data->ship_to_billing;
		$this->is_amazon_download = $cartitem_data->is_amazon_download;
		$this->include_code = $cartitem_data->include_code;
		$this->TIC = $cartitem_data->TIC;

		$this->allow_backorders = $cartitem_data->allow_backorders;
		$this->backorder_fill_date = $cartitem_data->backorder_fill_date;
		$this->stock_quantity = $cartitem_data->stock_quantity;

		$this->product_images = ( isset( $cartitem_data->product_images ) && '' != $cartitem_data->product_images ) ? explode( ',', $cartitem_data->product_images ) : array();
		if ( count( $this->product_images ) > 0 ) {
			if( 'image1' == $this->product_images[0] ) {
				if ( substr( $cartitem_data->image1, 0, 7 ) == 'http://' || substr( $cartitem_data->image1, 0, 8 ) == 'https://' ){
					$this->image1 = $cartitem_data->image1;
				} else {
					$this->image1 = plugins_url( "/wp-easycart-data/products/pics1/" . $cartitem_data->image1, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image2' == $this->product_images[0] ) {
				if ( substr( $cartitem_data->image2, 0, 7 ) == 'http://' || substr( $cartitem_data->image2, 0, 8 ) == 'https://' ){
					$this->image1 = $cartitem_data->image2;
				} else {
					$this->image1 = plugins_url( "/wp-easycart-data/products/pics2/" . $cartitem_data->image2, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image3' == $this->product_images[0] ) {
				if ( substr( $cartitem_data->image3, 0, 7 ) == 'http://' || substr( $cartitem_data->image3, 0, 8 ) == 'https://' ){
					$this->image1 = $cartitem_data->image3;
				} else {
					$this->image1 = plugins_url( "/wp-easycart-data/products/pics3/" . $cartitem_data->image3, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image4' == $this->product_images[0] ) {
				if ( substr( $cartitem_data->image4, 0, 7 ) == 'http://' || substr( $cartitem_data->image4, 0, 8 ) == 'https://' ){
					$this->image1 = $cartitem_data->image4;
				} else {
					$this->image1 = plugins_url( "/wp-easycart-data/products/pics4/" . $cartitem_data->image4, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image5' == $this->product_images[0] ) {
				if ( substr( $cartitem_data->image5, 0, 7 ) == 'http://' || substr( $cartitem_data->image5, 0, 8 ) == 'https://' ){
					$this->image1 = $cartitem_data->image5;
				} else {
					$this->image1 = plugins_url( "/wp-easycart-data/products/pics5/" . $cartitem_data->image5, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image:' == substr( $this->product_images[0], 0, 6 ) ) {
				$this->image1 = substr( $this->product_images[0], 6, strlen( $this->product_images[0] ) - 6 );
			} else if( 'video:' == substr( $this->product_images[0], 0, 6 ) ) {
				$video_str = substr( $this->product_images[0], 6, strlen( $this->product_images[0] ) - 6 );
				$video_arr = explode( ':::', $video_str );
				if ( count( $video_arr ) >= 2 ) {
					$this->image1 = $video_arr[1];
				}
			} else if( 'youtube:' == substr( $this->product_images[0], 0, 8 ) ) {
				$youtube_video_str = substr( $this->product_images[0], 8, strlen( $this->product_images[0] ) - 8 );
				$youtube_video_arr = explode( ':::', $youtube_video_str );
				if ( count( $youtube_video_arr ) >= 2 ) {
					$this->image1 = $youtube_video_arr[1];
				}
			} else if( 'vimeo:' == substr( $this->product_images[0], 0, 6 ) ) {
				$vimeo_video_str = substr( $this->product_images[0], 6, strlen( $this->product_images[0] ) - 6 );
				$vimeo_video_arr = explode( ':::', $vimeo_video_str );
				if ( count( $vimeo_video_arr ) >= 2 ) {
					$this->image1 = $vimeo_video_arr[1];
				}
			} else {
				$product_image_media = wp_get_attachment_image_src( $this->product_images[0], 'large' );
				if( $product_image_media && isset( $product_image_media[0] ) ) {
					$this->image1 = $product_image_media[0];
				}
			}
			$this->image1 = apply_filters( 'wpeasycart_cartitem_image1', $this->image1, $this->product_id, $this->cartitem_id );
		} else {
			$this->image1 = apply_filters( 'wpeasycart_cartitem_image1', $cartitem_data->image1, $this->product_id, $this->cartitem_id );
		}
		$this->image1_optionitem = $GLOBALS['ec_options']->get_optionitem_image1( $this->product_id, $cartitem_data->optionitem_id_1 );
		if( $cartitem_data->use_advanced_optionset || $cartitem_data->use_both_option_types ) {
			$advanced_found = false;
			foreach ( $this->advanced_options as $advanced_optionset ) {
				$advanced_option_details = $GLOBALS['ec_options']->get_optionitem( $advanced_optionset->optionitem_id );
				$advanced_option_data = $GLOBALS['ec_options']->get_option( $advanced_optionset->option_id );
				if( ! $advanced_found && ( 'combo' == $advanced_option_data->option_type || 'swatch' == $advanced_option_data->option_type || 'radio' == $advanced_option_data->option_type ) ) {
					$optionitem_id = $advanced_optionset->optionitem_id;
					$this->image1_optionitem = $GLOBALS['ec_options']->get_optionitem_image1( $this->product_id, $optionitem_id );
					$advanced_found = true;
				}
			}
		}
		$this->image1_optionitem = apply_filters( 'wpeasycart_cartitem_image1_optionitem', $this->image1_optionitem, $this->product_id, $this->cartitem_id );

		if ( $cartitem_data->optionitem_id_1 ) {
			$optionitem = $GLOBALS['ec_options']->get_optionitem( $cartitem_data->optionitem_id_1 );
			$this->optionitem1_name = wp_easycart_language()->convert_text( $optionitem->optionitem_name );
			$this->optionitem1_price = $optionitem->optionitem_price;
			$this->optionitem1_weight = $optionitem->optionitem_weight;
			if ( 0 != $cartitem_data->option_id_1 ) {
				$option = $GLOBALS['ec_options']->get_option( $cartitem_data->option_id_1 );
				$this->optionitem1_label = wp_easycart_language()->convert_text( $option->option_label );
			}
			$this->optionitem1_id = $optionitem->optionitem_id;
			if ( '' != $optionitem->optionitem_model_number ) {
				$this->orderdetails_model_number = $this->orderdetails_model_number . get_option( 'ec_option_model_number_extension' ) . $optionitem->optionitem_model_number;
			}
		} else {
			$this->optionitem1_name = '';
			$this->optionitem1_label = '';
			$this->optionitem1_price = 0.00;
			$this->optionitem1_weight = 0.00;
			$this->optionitem1_id = 0;
		}

		if ( $cartitem_data->optionitem_id_2 ) {
			$optionitem = $GLOBALS['ec_options']->get_optionitem( $cartitem_data->optionitem_id_2 );
			$this->optionitem2_name = wp_easycart_language()->convert_text( $optionitem->optionitem_name );
			$this->optionitem2_price = $optionitem->optionitem_price;
			$this->optionitem2_weight = $optionitem->optionitem_weight;
			if ( 0 != $cartitem_data->option_id_2 ) {
				$option = $GLOBALS['ec_options']->get_option( $cartitem_data->option_id_2 );
				$this->optionitem2_label = wp_easycart_language()->convert_text( $option->option_label );
			}
			$this->optionitem2_id = $optionitem->optionitem_id;
			if ( '' != $optionitem->optionitem_model_number ) {
				$this->orderdetails_model_number = $this->orderdetails_model_number . get_option( 'ec_option_model_number_extension' ) . $optionitem->optionitem_model_number;
			}
		} else {
			$this->optionitem2_name = '';
			$this->optionitem2_label = '';
			$this->optionitem2_price = 0.00;
			$this->optionitem2_weight = 0.00;
			$this->optionitem2_id = 0;
		}

		if ( $cartitem_data->optionitem_id_3 ) {
			$optionitem = $GLOBALS['ec_options']->get_optionitem( $cartitem_data->optionitem_id_3 );
			$this->optionitem3_name = wp_easycart_language()->convert_text( $optionitem->optionitem_name );
			$this->optionitem3_price = $optionitem->optionitem_price;
			$this->optionitem3_weight = $optionitem->optionitem_weight;
			if ( 0 != $cartitem_data->option_id_3 ) {
				$option = $GLOBALS['ec_options']->get_option( $cartitem_data->option_id_3 );
				$this->optionitem3_label = wp_easycart_language()->convert_text( $option->option_label );
			}
			$this->optionitem3_id = $optionitem->optionitem_id;
			if ( '' != $optionitem->optionitem_model_number ) {
				$this->orderdetails_model_number = $this->orderdetails_model_number . get_option( 'ec_option_model_number_extension' ) . $optionitem->optionitem_model_number;
			}
		} else {
			$this->optionitem3_name = '';
			$this->optionitem3_label = '';
			$this->optionitem3_price = 0.00;
			$this->optionitem3_weight = 0.00;
			$this->optionitem3_id = 0;
		}

		if ( $cartitem_data->optionitem_id_4 ) {
			$optionitem = $GLOBALS['ec_options']->get_optionitem( $cartitem_data->optionitem_id_4 );
			$this->optionitem4_name = wp_easycart_language()->convert_text( $optionitem->optionitem_name );
			$this->optionitem4_price = $optionitem->optionitem_price;
			$this->optionitem4_weight = $optionitem->optionitem_weight;
			if ( 0 != $cartitem_data->option_id_4 ) {
				$option = $GLOBALS['ec_options']->get_option( $cartitem_data->option_id_4 );
				$this->optionitem4_label = wp_easycart_language()->convert_text( $option->option_label );
			}
			$this->optionitem4_id = $optionitem->optionitem_id;
			if ( '' != $optionitem->optionitem_model_number ) {
				$this->orderdetails_model_number = $this->orderdetails_model_number . get_option( 'ec_option_model_number_extension' ) . $optionitem->optionitem_model_number;
			}
		} else {
			$this->optionitem4_name = '';
			$this->optionitem4_label = '';
			$this->optionitem4_price = 0.00;
			$this->optionitem4_weight = 0.00;
			$this->optionitem4_id = 0;
		}

		if ( $cartitem_data->optionitem_id_5 ) {
			$optionitem = $GLOBALS['ec_options']->get_optionitem( $cartitem_data->optionitem_id_5 );
			$this->optionitem5_name = wp_easycart_language()->convert_text( $optionitem->optionitem_name );
			$this->optionitem5_price = $optionitem->optionitem_price;
			$this->optionitem5_weight = $optionitem->optionitem_weight;
			if ( 0 != $cartitem_data->option_id_5 ) {
				$option = $GLOBALS['ec_options']->get_option( $cartitem_data->option_id_5 );
				$this->optionitem5_label = wp_easycart_language()->convert_text( $option->option_label );
			}
			$this->optionitem5_id = $optionitem->optionitem_id;
			if ( '' != $optionitem->optionitem_model_number ) {
				$this->orderdetails_model_number = $this->orderdetails_model_number . get_option( 'ec_option_model_number_extension' ) . $optionitem->optionitem_model_number;
			}
		} else {
			$this->optionitem5_name = '';
			$this->optionitem5_label = '';
			$this->optionitem5_price = 0.00;
			$this->optionitem5_weight = 0.00;
			$this->optionitem5_id = 0;
		}

		$this->pricetiers = $GLOBALS['ec_pricetiers']->get_pricetiers( $this->product_id );

		$this->use_advanced_optionset = $cartitem_data->use_advanced_optionset;
		$this->use_both_option_types = $cartitem_data->use_both_option_types;
		$this->optionitem_stock_quantity = 0;
		if ( $this->use_optionitem_quantity_tracking ) {
			$this->optionitem_stock_quantity = $wpdb->get_row( $wpdb->prepare( 'SELECT quantity FROM ec_optionitemquantity WHERE product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d', $this->product_id, $this->optionitem1_id, $this->optionitem2_id, $this->optionitem3_id, $this->optionitem4_id, $this->optionitem5_id ) );
		}

		$this->gift_card_message = $cartitem_data->gift_card_message;
		$this->gift_card_from_name = $cartitem_data->gift_card_from_name;
		$this->gift_card_to_name = $cartitem_data->gift_card_to_name;
		$this->gift_card_email = $cartitem_data->gift_card_email;

		$this->download_file_name = $cartitem_data->download_file_name;
		$this->amazon_key = $cartitem_data->amazon_key;
		$this->maximum_downloads_allowed = $cartitem_data->maximum_downloads_allowed;
		$this->download_timelimit_seconds = $cartitem_data->download_timelimit_seconds;

		$this->is_subscription_item = $cartitem_data->is_subscription_item;
		$this->subscription_bill_length = $cartitem_data->subscription_bill_length;
		$this->subscription_bill_period = $cartitem_data->subscription_bill_period;
		$this->subscription_bill_duration = $cartitem_data->subscription_bill_duration;
		$this->trial_period_days = $cartitem_data->trial_period_days;
		$this->subscription_signup_fee = $cartitem_data->subscription_signup_fee;
		$this->subscription_prorate = $cartitem_data->subscription_prorate;
		$this->stripe_plan_added = $cartitem_data->stripe_plan_added;
		$this->subscription_unique_id = $cartitem_data->subscription_unique_id;

		$this->use_optionitem_quantity_tracking = $cartitem_data->use_optionitem_quantity_tracking;
		$this->optionitem_stock_quantity = 0;
		if ( $this->use_optionitem_quantity_tracking ) {
			$optionitem_stock_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d', $this->product_id, $this->optionitem1_id, $this->optionitem2_id, $this->optionitem3_id, $this->optionitem4_id, $this->optionitem5_id ) );
			if ( $optionitem_stock_row && $optionitem_stock_row->is_stock_tracking_enabled ) {
				$this->optionitem_stock_quantity = $optionitem_stock_row->quantity;
			} else {
				$this->optionitem_stock_quantity = 10000000;
			}
		}

		$this->track_quantity = false;
		$this->max_quantity = 10000000;
		$this->min_quantity = 1;

		if ( $this->min_purchase_quantity > 0 || $this->max_purchase_quantity > 0 || $this->show_stock_quantity || $this->use_optionitem_quantity_tracking ) {
			$this->track_quantity = true;
			if ( $this->max_purchase_quantity > 0 ) {
				$this->max_quantity = $this->max_purchase_quantity;
			}
			if ( ! $this->allow_backorders && $this->show_stock_quantity && $this->stock_quantity < $this->max_quantity ) {
				$this->max_quantity = $this->stock_quantity;
			}
			if ( ! $this->allow_backorders && $this->use_optionitem_quantity_tracking && $this->optionitem_stock_quantity < $this->max_quantity ) {
				$this->max_quantity = $this->optionitem_stock_quantity;
			}
			if ( $this->min_purchase_quantity > 0 ) {
				$this->min_quantity = $this->min_purchase_quantity;
			}
		}

		$this->donation_price = $cartitem_data->donation_price;

		$this->is_deconetwork = $cartitem_data->is_deconetwork;
		$this->deconetwork_id = $cartitem_data->deconetwork_id;
		$this->deconetwork_name = str_replace( '%2F', '/', str_replace( '%3F', '?', str_replace( '%3D', '=', str_replace( '%26', '&', $cartitem_data->deconetwork_name ) ) ) );
		$this->deconetwork_product_code = $cartitem_data->deconetwork_product_code;
		$this->deconetwork_options = str_replace( '<br/><br/>', '<br/>', str_replace( '%2F', '/', str_replace( '%3F', '?', str_replace( '%3D', '=', str_replace( '%26', '&', str_replace( '%3A', ':', str_replace( '%2C', ',', str_replace( '%3C', '<', str_replace( '%3E', '>', $cartitem_data->deconetwork_options ) ) ) ) ) ) ) ) );
		$this->deconetwork_edit_link = str_replace( '%2F', '/', str_replace( '%3F', '?', str_replace( '%3D', '=', str_replace( '%26', '&', $cartitem_data->deconetwork_edit_link ) ) ) );
		$this->deconetwork_color_code = $cartitem_data->deconetwork_color_code;
		$this->deconetwork_product_id = $cartitem_data->deconetwork_product_id;
		$this->deconetwork_image_link = str_replace( '%2F', '/', str_replace( '%3F', '?', str_replace( '%3D', '=', str_replace( '%26', '&', $cartitem_data->deconetwork_image_link ) ) ) );
		$this->deconetwork_discount = $cartitem_data->deconetwork_discount;
		$this->deconetwork_tax = $cartitem_data->deconetwork_tax;
		$this->deconetwork_total = $cartitem_data->deconetwork_total;
		$this->deconetwork_version = $cartitem_data->deconetwork_version;

		if ( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ) {
			$ec_extra_cartitem_vars_count = count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] );
			for ( $i = 0; $i < $ec_extra_cartitem_vars_count; $i++ ) {
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][ $i ][0]( array(), array() );
				$arr_count = count( $arr );
				for ( $j = 0; $j < $arr_count; $j++ ) {
					$this->custom_vars[ $arr[ $j ] ] = $cartitem_data->{ $arr[ $j ] };
				}
			}
		}
		$options_price = 0;
		$this->options_price_onetime = 0;
		$options_weight = 0;
		$options_weight_onetime = 0;
		$grid_weight_change = 0;
		$this->grid_price_change = 0;
		$price_multiplier = 0;
		$weight_multiplier = 0;

		for ( $adv_index = 0; $adv_index < count( $this->advanced_options ); $adv_index++ ) {
			if ( isset( $this->advanced_options[ $adv_index ]->option_label ) ) {
				$this->advanced_options[ $adv_index ]->option_label = wp_easycart_language()->convert_text( $this->advanced_options[ $adv_index ]->option_label );
			}
			$this->advanced_options[ $adv_index ]->optionitem_value = wp_easycart_language()->convert_text( $this->advanced_options[ $adv_index ]->optionitem_value );
			if ( isset( $this->advanced_options[ $adv_index ]->optionitem_download_override_file ) && '' != trim( $this->advanced_options[ $adv_index ]->optionitem_download_override_file ) ) {
				$this->download_file_name = $this->advanced_options[ $adv_index ]->optionitem_download_override_file;
			}
		}

		$grid_id = 0;

		$options_price = $this->optionitem1_price + $this->optionitem2_price + $this->optionitem3_price + $this->optionitem4_price + $this->optionitem5_price;
		$options_weight = $this->optionitem1_weight + $this->optionitem2_weight + $this->optionitem3_weight + $this->optionitem4_weight + $this->optionitem5_weight;
		$variant_row = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d', $this->product_id, $this->optionitem1_id, $this->optionitem2_id, $this->optionitem3_id, $this->optionitem4_id, $this->optionitem5_id ) );
		if ( $variant_row ) {
			if ( -1 != $variant_row->price ) {
				$options_price = 0;
				$cartitem_data->price = $variant_row->price;
			}
			if ( '' != $variant_row->sku ) {
				$this->model_number = $cartitem_data->model_number = $variant_row->sku;
			}
		}

		if ( $this->use_advanced_optionset || $this->use_both_option_types ) {
			foreach ( $this->advanced_options as $advanced_option ) {
				$advanced_option_details = $GLOBALS['ec_options']->get_optionitem( $advanced_option->optionitem_id );
				$advanced_option_data = $GLOBALS['ec_options']->get_option( $advanced_option->option_id );
				if ( $advanced_option_details->optionitem_disallow_shipping ) {
					$this->is_shippable = false;
				}
				if ( '' != $advanced_option->optionitem_model_number ) {
					$this->orderdetails_model_number = $this->orderdetails_model_number . get_option( 'ec_option_model_number_extension' ) . $advanced_option->optionitem_model_number;
				}
				if ( 'grid' == $advanced_option_data->option_type ) {
					$grid_id = $advanced_option->option_id;
					if ( 0 != $advanced_option_details->optionitem_price ) {
						$this->grid_price_change = $this->grid_price_change + ( $advanced_option_details->optionitem_price * $advanced_option->optionitem_value );
					} else if ( 0 != $advanced_option_details->optionitem_price_onetime ) {
						$this->grid_price_change = $this->grid_price_change + $advanced_option_details->optionitem_price_onetime;
					} else if ( $advanced_option_details->optionitem_price_override >= 0 ) {
						$this->grid_price_change = $this->grid_price_change + ( ( $advanced_option_details->optionitem_price_override - $cartitem_data->price ) * $advanced_option->optionitem_value );
					} else if ( $advanced_option_details->optionitem_price_multiplier > 1 ) {
						$this->grid_price_change = $cartitem_data->price * ( $advanced_option_details->optionitem_price_multiplier - 1 );
					}
					if ( 0 != $advanced_option_details->optionitem_weight ) {
						$grid_weight_change = $grid_weight_change + ( $advanced_option_details->optionitem_weight * $advanced_option->optionitem_value );
					} else if ( 0 != $advanced_option_details->optionitem_weight_onetime ) {
						$grid_weight_change = $grid_weight_change + $advanced_option_details->optionitem_weight_onetime;
					} else if ( $advanced_option_details->optionitem_weight_override >= 0 ) {
						$grid_weight_change = $grid_weight_change + ( ( $advanced_option_details->optionitem_weight_override - $cartitem_data->weight ) * $advanced_option->optionitem_value );
					} else if ( $advanced_option_details->optionitem_weight_multiplier > 1 ) {
						$grid_weight_change = $cartitem_data->weight * ( $advanced_option_details->optionitem_weight_multiplier - 1 );
					}
				} else if ( 'number' == $advanced_option_data->option_type ) {
					if ( 0 != $advanced_option_details->optionitem_price ) {
						$options_price = $options_price + ( $advanced_option_details->optionitem_price * $advanced_option->optionitem_value );
					} else if ( 0 != $advanced_option_details->optionitem_price_onetime ) {
						$this->options_price_onetime = $this->options_price_onetime + $advanced_option_details->optionitem_price_onetime;
					} else if ( $advanced_option_details->optionitem_price_override >= 0 ) {
						$cartitem_data->price = $advanced_option_details->optionitem_price_override;
					}
					if ( 0 != $advanced_option_details->optionitem_price_multiplier ) {
						if ( 0 == $price_multiplier ) {
							$price_multiplier = 1;
						}
						$price_multiplier = $price_multiplier * $advanced_option_details->optionitem_price_multiplier * $advanced_option->optionitem_value;
					}
					if ( 0 != $advanced_option_details->optionitem_weight ) {
						$options_weight = $options_weight + ( $advanced_option_details->optionitem_weight * $advanced_option->optionitem_value );
					} else if ( 0 != $advanced_option_details->optionitem_weight_onetime ) {
						$options_weight_onetime = $options_weight_onetime + $advanced_option_details->optionitem_weight_onetime;
					} else if ( $advanced_option_details->optionitem_weight_override >= 0 ) {
						$this->weight = $advanced_option_details->optionitem_weight_override;
					}
					if ( $advanced_option_details->optionitem_weight_multiplier > 1 ) {
						$weight_multiplier = $advanced_option_details->optionitem_weight_multiplier * $advanced_option->optionitem_value;
					}
				} else if ( 'dimensions1' == $advanced_option_data->option_type || 'dimensions2' == $advanced_option_data->option_type ) {
					$dimensions = json_decode( $advanced_option->optionitem_value );
					if ( 2 == count( $dimensions ) ) { 
						if ( ! get_option( 'ec_option_enable_metric_unit_display' ) ) {
							$cartitem_data->price = $cartitem_data->price * ( ( $dimensions[0] / 12 ) * ( $dimensions[1] / 12 ) );
							$cartitem_data->weight = $cartitem_data->weight * ( ( $dimensions[0] / 12 ) * ( $dimensions[1] / 12 ) );
						} else {
							$cartitem_data->price = $cartitem_data->price * ( ( $dimensions[0] / 1000 ) * ( $dimensions[1] / 1000 ) );
							$cartitem_data->weight = $cartitem_data->weight * ( ( $dimensions[0] / 1000 ) * ( $dimensions[1] / 1000 ) );
						}
					} else if ( 4 == count( $dimensions ) ) { 
						if ( ! get_option( 'ec_option_enable_metric_unit_display' ) ) {
							$cartitem_data->price = $cartitem_data->price * ( ( ( intval( $dimensions[0] ) + $this->get_dimension_decimal( $dimensions[1] ) ) / 12 ) * ( ( intval( $dimensions[2] ) + $this->get_dimension_decimal( $dimensions[3] ) ) / 12 ) );
							$cartitem_data->weight = $cartitem_data->weight * ( ( ( intval( $dimensions[0] ) + $this->get_dimension_decimal( $dimensions[1] ) ) / 12 ) * ( ( intval( $dimensions[2] ) + $this->get_dimension_decimal( $dimensions[3] ) ) / 12 ) );
						} else {
							$cartitem_data->price = $cartitem_data->price * ( ( ( intval( $dimensions[0] ) + $this->get_dimension_decimal( $dimensions[1] ) ) / 1000 ) * ( ( intval( $dimensions[2] ) + $this->get_dimension_decimal( $dimensions[3] ) ) / 1000 ) );
							$cartitem_data->weight = $cartitem_data->weight * ( ( ( intval( $dimensions[0] ) + $this->get_dimension_decimal( $dimensions[1] ) ) / 1000 ) * ( ( intval( $dimensions[2] ) + $this->get_dimension_decimal( $dimensions[3] ) ) / 1000 ) );
						}
					}
				} else {
					if ( 0 != $advanced_option_details->optionitem_price ) {
						$options_price = $options_price + $advanced_option_details->optionitem_price;
					} else if ( 0 != $advanced_option_details->optionitem_price_onetime ) {
						$this->options_price_onetime = $this->options_price_onetime + $advanced_option_details->optionitem_price_onetime;
					} else if ( $advanced_option_details->optionitem_price_override >= 0 ) {
						$cartitem_data->price = $advanced_option_details->optionitem_price_override;
					}
					if ( 0 != $advanced_option_details->optionitem_price_multiplier ) {
						if ( 0 == $price_multiplier ) {
							$price_multiplier = 1;
						}
						$price_multiplier = $price_multiplier * $advanced_option_details->optionitem_price_multiplier;
					}
					if ( $advanced_option_details->optionitem_price_per_character > 0 ) {
						$num_chars = strlen( preg_replace('/\s+/', '', $advanced_option->optionitem_value ) );
						$options_price = $options_price + ( $num_chars * $advanced_option_details->optionitem_price_per_character );
					}
					if ( 0 != $advanced_option_details->optionitem_weight ) {
						$options_weight = $options_weight + $advanced_option_details->optionitem_weight;
					} else if ( 0 != $advanced_option_details->optionitem_weight_onetime ) {
						$options_weight_onetime = $options_weight_onetime + $advanced_option_details->optionitem_weight_onetime;
					} else if ( $advanced_option_details->optionitem_weight_override >= 0 ) {
						$this->weight = $advanced_option_details->optionitem_weight_override;
					}
					if ( $advanced_option_details->optionitem_weight_multiplier > 1 ) {
						$weight_multiplier = $advanced_option_details->optionitem_weight_multiplier;
					}
				}
			}
			for ( $i = 0; $i < count( $this->advanced_options ); $i++ ) {
				$advanced_option_details = $GLOBALS['ec_options']->get_optionitem( $this->advanced_options[ $i ]->optionitem_id );
				$advanced_option_data = $GLOBALS['ec_options']->get_option( $this->advanced_options[ $i ]->option_id );
				$this->advanced_options[ $i ]->option_name = wp_easycart_language()->convert_text( $advanced_option_data->option_name );
				$this->advanced_options[ $i ]->optionitem_name = wp_easycart_language()->convert_text( $advanced_option_details->optionitem_name );
				$this->advanced_options[ $i ]->optionitem_value = wp_easycart_language()->convert_text( $this->advanced_options[ $i ]->optionitem_value );
			}
		}

		$this->weight = $this->weight + $options_weight + $grid_weight_change;
		$roleprice = $GLOBALS['ec_roleprices']->get_roleprice( $this->product_id );

		if ( $this->is_donation ) {
			$this->unit_price = $cartitem_data->donation_price + $options_price;
		} else if ( $roleprice ) {
			$this->unit_price = $roleprice + $options_price;
		} else if ( count( $this->pricetiers ) > 0 ) {

			if ( 0 == $grid_id && get_option( 'ec_option_tiered_price_by_option' ) ) {
				$total_items = $this->quantity;
			} else if ( 0 == $grid_id ) {
				$total_items = $this->mysqli->get_total_cart_items_by_product_id( $this->product_id, $GLOBALS['ec_cart_data']->ec_cart_id );
			} else {
				$total_items = $this->mysqli->get_total_cart_items_with_grid_by_product_id( $this->product_id, $grid_id, $GLOBALS['ec_cart_data']->ec_cart_id );
			}

			$this->unit_price = $cartitem_data->price + $options_price;
			$pricetiers_count = count( $this->pricetiers );
			for ( $i = 0; $i < $pricetiers_count; $i++ ) {
				if ( $total_items >= $this->pricetiers[ $i ]->quantity ) {
					$this->unit_price = $this->pricetiers[ $i ]->price + $options_price;
				}
			}
		} else {
			$this->unit_price = $cartitem_data->price + $options_price;
		}

		if ( $price_multiplier > 0 ) {
			$this->unit_price = $this->unit_price * $price_multiplier;
		}

		if ( $weight_multiplier > 1 ) {
			$this->weight = $this->weight * $weight_multiplier;
		}

		$this->gift_card_value = $this->unit_price;

		$this->total_price = ( $this->unit_price * $this->quantity ) + $this->options_price_onetime + $this->grid_price_change;
		$this->total_price = apply_filters( 'wp_easycart_cart_item_total_price', $this->total_price, $this->cartitem_id, $this->product_id );
		$this->converted_total_price = ( $GLOBALS['currency']->convert_price( $this->unit_price ) * $this->quantity ) + $GLOBALS['currency']->convert_price( $this->options_price_onetime ) + $GLOBALS['currency']->convert_price( $this->grid_price_change );
		$this->converted_total_price = apply_filters( 'wp_easycart_cart_item_total_price', $this->converted_total_price, $this->cartitem_id, $this->product_id );
		$this->total_weight = ( $this->weight * $this->quantity ) + $options_weight_onetime;
		$this->handling_price = $cartitem_data->handling_price;
		$this->handling_price_each = $cartitem_data->handling_price_each;

		if ( $cartitem_data->vat_rate > 0 ) {
			$this->vat_enabled = true;
		} else {
			$this->vat_enabled = false;
		}
		if ( $this->is_deconetwork ) {
			$this->unit_price = $this->deconetwork_total / $this->quantity;
			$this->total_price = $this->deconetwork_total;
			$this->discount_price = $this->deconetwork_discount;
		}

		$this->enable_price_label = $cartitem_data->enable_price_label;
		$this->replace_price_label = $cartitem_data->replace_price_label;
		$this->custom_price_label = $cartitem_data->custom_price_label;

		$this->has_affiliate_rule = false;

		$store_page_id = get_option( 'ec_option_storepage' );
		$cart_page_id = get_option( 'ec_option_cartpage' );

		if ( function_exists( 'icl_object_id' ) ) {
			$store_page_id = icl_object_id( $store_page_id, 'page', true, ICL_LANGUAGE_CODE );
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->store_page = get_permalink( $store_page_id );
		$this->cart_page = get_permalink( $cart_page_id );

		if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
		}

		if ( substr_count( $this->cart_page, '?' ) ) {
			$this->permalink_divider = '&';
		} else {
			$this->permalink_divider = '?';
		}
	}

	private function get_dimension_decimal( $value ) {

		if ( $value == '1/16' ) {
			return .0625;
		} else if ( $value == '1/8' ) {
			return .1250;
		} else if ( $value == '3/16' ) {
			return .1875;
		} else if ( $value == '1/4' ) {
			return .2500;
		} else if ( $value == '5/16' ) {
			return .3125;
		} else if ( $value == '3/8' ) {
			return .3750;
		} else if ( $value == '7/16' ) {
			return .4375;
		} else if ( $value == '1/2' ) {
			return .5000;
		} else if ( $value == '9/16' ) {
			return .5625;
		} else if ( $value == '5/8' ) {
			return .6250;
		} else if ( $value == '11/16' ) {
			return .6875;
		} else if ( $value == '3/4' ) {
			return .7500;
		} else if ( $value == '13/16' ) {
			return .8125;
		} else if ( $value == '7/8' ) {
			return .8750;
		} else if ( $value == '15/16' ) {
			return .9375;
		} else {
			return 0;
		}

	}

	public function display_cartitem_id() {
		echo esc_attr( $this->cartitem_id );
	}

	public function get_quantity() {
		return $this->quantity;
	}

	public function display_quantity() {
		echo esc_attr( $this->quantity );
	}

	public function get_item_unit_price() {
		return $this->unit_price;
	}

	public function get_discount_unit_price() {
		return $this->discount_price;
	}

	public function get_item_total() {
		return $this->total_price;
	}

	public function get_weight() {
		return $this->total_weight;
	}

	public function get_shippable_total() {
		if ( $this->is_shippable ) {
			return $this->total_price;
		} else {
			return 0;
		}
	}

	public function display_image( $size ) {

		if ( $this->is_deconetwork ) {
			echo '<a href="https://' . esc_attr( get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_edit_link ) . '"><img src="https://' . esc_attr( get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_image_link . '?version=' . $this->deconetwork_version ) . '" alt="' . esc_attr( $this->model_number ) . '" /></a>';
		} else {
			echo '<a href="' . esc_attr( $this->ec_get_permalink( $this->post_id ) );
			if ( substr_count( $this->ec_get_permalink( $this->post_id ), '?' ) ) {
				$second_permalink_divider = '&';
			} else {
				$second_permalink_divider = '?';
			}

			if ( $this->image1_optionitem ) {
				if ( substr( $this->image1_optionitem, 0, 7 ) == 'http://' || substr( $this->image1_optionitem, 0, 8 ) == 'https://' ) {
					echo esc_attr( $second_permalink_divider . 'optionitem_id=' . $this->optionitem1_id ) . '"><img src="' . esc_attr( $this->image1_optionitem ) . '" alt="' . esc_attr( $this->model_number ) . '" />';
				} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $this->image1_optionitem ) ) {
					echo esc_attr( $second_permalink_divider . 'optionitem_id=' . $this->optionitem1_id ) . '"><img src="' . esc_attr( plugins_url( 'wp-easycart-data/products/pics1/' . $this->image1_optionitem, EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';
				} else {
					echo esc_attr( $second_permalink_divider . 'optionitem_id=' . $this->optionitem1_id ) . '"><img src="' . esc_attr( plugins_url( 'wp-easycart/products/pics1/' . $this->image1_optionitem, EC_PLUGIN_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';
				}
			} else {
				if ( substr( $this->image1 , 0, 7 ) == 'http://' || substr( $this->image1 , 0, 8 ) == 'https://' ) {
					echo '"><img src="' . esc_url( $this->image1 ) . '" alt="' . esc_attr( $this->model_number ) . '" />';

				} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $this->image1 ) ) {
					echo '"><img src="' . esc_url( plugins_url( 'wp-easycart-data/products/pics1/' . $this->image1, EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';

				} else if ( file_exists( EC_PLUGIN_DIRECTORY . '/products/pics1/' . $this->image1 ) ) {
					echo '"><img src="' . esc_url( plugins_url( 'wp-easycart/products/pics1/' . $this->image1, EC_PLUGIN_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';

				} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
					echo '"><img src="' . esc_url( get_option( 'ec_option_product_image_default' ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';

				} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) ) {
					echo '"><img src="' . esc_url( plugins_url( '/wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';

				} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec_image_not_found.jpg' ) ) {
					echo '"><img src="' . esc_url( plugins_url( '/wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec_image_not_found.jpg', EC_PLUGIN_DATA_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';

				} else {
					echo '"><img src="' . esc_url( plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec_image_not_found.jpg', EC_PLUGIN_DIRECTORY ) ) . '" alt="' . esc_attr( $this->model_number ) . '" />';
				}
			}
			echo '</a>';
		}
	}

	public function get_image_url() {

		if ( $this->is_deconetwork ) {
			return 'https://' . get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_image_link . '?version=' . $this->deconetwork_version;

		} else if ( substr( $this->image1_optionitem, 0, 7 ) == 'http://' || substr( $this->image1_optionitem, 0, 8 ) == 'https://' ) {
			return $this->image1_optionitem;

		} else if ( $this->image1_optionitem && file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $this->image1_optionitem ) && ! is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $this->image1_optionitem ) ) {
			return plugins_url( 'wp-easycart-data/products/pics1/' . $this->image1_optionitem, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( substr( $this->image1, 0, 7 ) == 'http://' || substr( $this->image1, 0, 8 ) == 'https://' ) {
			return $this->image1;

		} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $this->image1 ) && ! is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $this->image1 ) ) {
			return plugins_url( 'wp-easycart-data/products/pics1/' . $this->image1, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) ) {
			return plugins_url( '/wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( '/wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DIRECTORY );
		}

	}

	public function get_product_url() {
		if ( $this->is_deconetwork ) {
			return 'https://' . get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_edit_link;
		} else {
			return $this->ec_get_permalink( $this->post_id );
		}
	}

	public function display_title() {
		if ( $this->is_deconetwork ) {
			echo wp_easycart_escape_html( $this->deconetwork_name );
		} else {
			echo wp_easycart_escape_html( $this->title );
		}
	}

	public function display_title_link() {
		if ( $this->is_deconetwork ) {
			echo '<a href="https://' . esc_attr( get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_edit_link ) . '">' . wp_easycart_escape_html( $this->deconetwork_name ) . '</a>';
		} else {
			echo '<a href="' . esc_attr( $this->ec_get_permalink( $this->post_id ) );
			if ( substr_count( $this->ec_get_permalink( $this->post_id ), '?' ) ) {
				$second_permalink_divider = '&';
			} else {
				$second_permalink_divider = '?';
			}
			if ( $this->image1_optionitem ) {
				echo esc_attr( $second_permalink_divider . 'optionitem_id=' . $this->optionitem1_id );
			}
			echo '">' . wp_easycart_escape_html( $this->title ) . '</a>';
		}
	}

	public function get_title_link() {
		if ( $this->is_deconetwork ) {
			return 'https://' . get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_edit_link;
		} else {
			$ret_string = $this->ec_get_permalink( $this->post_id );
			if ( substr_count( $ret_string, '?' ) ) {
				$second_permalink_divider = '&';
			} else {
				$second_permalink_divider = '?';
			}
			if ( $this->image1_optionitem ) {
				$ret_string .= $second_permalink_divider . 'optionitem_id=' . $this->optionitem1_id;
			}
			return $ret_string;
		}
	}

	public function has_option1() {
		if ( ( $this->is_deconetwork && $this->deconetwork_options ) || $this->optionitem1_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_option1() {
		if ( $this->is_deconetwork ) {
			echo wp_easycart_escape_html( str_replace( '<br/><br/>', '<br/>', $this->deconetwork_options ) );
			echo '<br/><a href="https://' . esc_attr( get_option( 'ec_option_deconetwork_url' ) . $this->deconetwork_edit_link ) . '">Edit Design</a>';
		} else {
			if ( $this->optionitem1_price == '0.00' && $this->optionitem1_name ) {
				echo esc_attr( $this->optionitem1_label . ': ' . $this->optionitem1_name );
			} else if ( $this->optionitem1_name ) {
				if ( $this->optionitem1_price > 0.00 ) {
					echo esc_attr( $this->optionitem1_label . ': ' . $this->optionitem1_name . ' ( +' . $GLOBALS['currency']->get_currency_display( $this->optionitem1_price ) . ' )' );
				} else {
					echo esc_attr( $this->optionitem1_label . ': ' . $this->optionitem1_name . ' ( ' . $GLOBALS['currency']->get_currency_display( $this->optionitem1_price ) . ' )' );
				}
			}
		}
	}

	public function has_option2() {
		if ( $this->optionitem2_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_option2() {
		if ( $this->optionitem2_price == '0.00' && $this->optionitem2_name ) {
			echo esc_attr( $this->optionitem2_label . ': ' . $this->optionitem2_name );
		} else if ( $this->optionitem2_name ) {
			if ( $this->optionitem2_price > 0.00 ) {
				echo esc_attr( $this->optionitem2_label . ': ' . $this->optionitem2_name . ' ( +' . $GLOBALS['currency']->get_currency_display( $this->optionitem2_price ) . ' )' );
			} else {
				echo esc_attr( $this->optionitem2_label . ': ' . $this->optionitem2_name . ' ( ' . $GLOBALS['currency']->get_currency_display( $this->optionitem2_price ) . ' )' );
			}
		}
	}

	public function has_option3() {
		if ( $this->optionitem3_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_option3() {
		if ( $this->optionitem3_price == '0.00' && $this->optionitem3_name ) {
			echo esc_attr( $this->optionitem3_label . ': ' . $this->optionitem3_name );
		} else if ( $this->optionitem3_name ) {
			if ( $this->optionitem3_price > 0.00 ) {
				echo esc_attr( $this->optionitem3_label . ': ' . $this->optionitem3_name . ' ( +' . $GLOBALS['currency']->get_currency_display( $this->optionitem3_price ) . ' )' );
			} else {
				echo esc_attr( $this->optionitem3_label . ': ' . $this->optionitem3_name . ' ( ' . $GLOBALS['currency']->get_currency_display( $this->optionitem3_price ) . ' )' );
			}
		}
	}

	public function has_option4() {
		if ( $this->optionitem4_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_option4() {
		if ( $this->optionitem4_price == '0.00' && $this->optionitem4_name ) {
			echo esc_attr( $this->optionitem4_label . ': ' . $this->optionitem4_name );
		} else if ( $this->optionitem4_name ) {
			if ( $this->optionitem4_price > 0.00 ) {
				echo esc_attr( $this->optionitem4_label . ': ' . $this->optionitem4_name . ' ( +' . $GLOBALS['currency']->get_currency_display( $this->optionitem4_price ) . ' )' );
			} else {
				echo esc_attr( $this->optionitem4_label . ': ' . $this->optionitem4_name . ' ( ' . $GLOBALS['currency']->get_currency_display( $this->optionitem4_price ) . ' )' );
			}
		}
	}

	public function has_option5() {
		if ( $this->optionitem5_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_option5() {
		if ( $this->optionitem5_price == '0.00' && $this->optionitem5_name ) {
			echo esc_attr( $this->optionitem5_label . ': ' . $this->optionitem5_name );
		} else if ( $this->optionitem5_name ) {
			if ( $this->optionitem5_price > 0.00 ) {
				echo esc_attr( $this->optionitem5_label . ': ' . $this->optionitem5_name . ' ( +' . $GLOBALS['currency']->get_currency_display( $this->optionitem5_price ) . ' )' );
			} else {
				echo esc_attr( $this->optionitem5_label . ': ' . $this->optionitem5_name . ' ( ' . $GLOBALS['currency']->get_currency_display( $this->optionitem5_price ) . ' )' );
			}
		}
	}

	public function has_gift_card_message() {
		if ( $this->gift_card_message ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_gift_card_message( $message_text ) {
		if ( $this->gift_card_message ) {
			echo esc_attr( $message_text . $this->gift_card_message );
		}
	}

	public function has_gift_card_from_name() {
		if ( $this->gift_card_from_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_gift_card_from_name( $from_text ) {
		if ( $this->gift_card_from_name ) {
			echo esc_attr( $from_text . $this->gift_card_from_name );
		}
	}

	public function has_gift_card_to_name() {
		if ( $this->gift_card_to_name ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_gift_card_to_name( $to_text ) {
		if ( $this->gift_card_to_name ) {
			echo esc_attr( $to_text . $this->gift_card_to_name );
		}
	}

	public function has_print_gift_card_link() {
		if ( $this->is_giftcard ) {
			return true;
		} else {
			return false;
		}
	}

	public function has_download_link() {
		if ( $this->is_download ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_update_form_start() {
		if ( !$this->is_deconetwork ) {
			if ( isset( $_GET['ec_page'] ) ) {
				echo '<form action="' . esc_attr( $this->cart_page . $this->permalink_divider ) . 'ec_page=' . esc_attr( htmlspecialchars( sanitize_key( $_GET['ec_page'] ), ENT_QUOTES ) ) . '" method="post">';
			} else {
				echo '<form action="' . esc_attr( $this->cart_page ) . '" method="post">';
			}
		}
	}

	public function display_update_form_end() {
		if ( !$this->is_deconetwork ) {
			echo '<input type="hidden" name="ec_update_cartitem_id" id="ec_update_cartitem_id_' . esc_attr( $this->cartitem_id ) . '" value="' . esc_attr( $this->cartitem_id ) . '" />';
			echo '<input type="hidden" name="ec_cart_form_action" id="ec_cart_form_action" value="ec_update_action" />';
			echo '<input type="hidden" name="ec_cart_form_nonce" value="' . esc_attr( wp_create_nonce( 'wp-easycart-cart-update-item-' . $this->cartitem_id ) ) . '\" />';
			echo '</form>';
		}
	}

	public function display_quantity_box() {
		if ( $this->is_deconetwork ) {
			echo esc_attr( $this->quantity );
		} else {
			if ( $this->grid_quantity > 0 ) {
				echo '<input type="hidden" id="ec_cartitem_quantity_' . esc_attr( $this->cartitem_id ) . '" name="ec_cartitem_quantity_' . esc_attr( $this->cartitem_id ) . '" value="' . esc_attr( $this->quantity ) . '" min="1" />' . esc_attr( $this->grid_quantity );
			} else {
				echo '<input type="number" id="ec_cartitem_quantity_' . esc_attr( $this->cartitem_id ) . '" name="ec_cartitem_quantity_' . esc_attr( $this->cartitem_id ) . '" value="' . esc_attr( $this->quantity ) . '" min="1" />';
			}
		}
	}

	public function display_update_button( $update_text ) {
		if ( !$this->is_deconetwork ) {
			echo '<input type="submit" id="update_' . esc_attr( $this->cartitem_id ) . '" name="update_' . esc_attr( $this->cartitem_id ) . '" value="' . esc_attr( $update_text ) . '" onclick="ec_cart_item_update( \'' . esc_attr( $this->cartitem_id ) . '\' ); return false;" />';
		}
	}

	public function display_delete_button( $remove_text ) {
		if ( isset( $_GET['ec_page'] ) ) {
			echo '<form action="' . esc_attr( $this->cart_page . $this->permalink_divider ) . 'ec_page=' . esc_attr( htmlspecialchars( sanitize_key( $_GET['ec_page'] ), ENT_QUOTES ) ) . '" method="post">';
		} else {
			echo '<form action="' . esc_attr( $this->cart_page ) . '" method="post">';
		}
		echo '<input type="submit" id="remove_' . esc_attr( $this->cartitem_id ) . '" name="remove_' . esc_attr( $this->cartitem_id ) . '" value="' . esc_attr( $remove_text ) . '" onclick="';
		if ( get_option( 'ec_option_googleanalyticsid' ) != "UA-XXXXXXX-X" && get_option( 'ec_option_googleanalyticsid' ) != "" ) {
			echo 'ec_google_removeFromCart( \'' . esc_attr( $this->model_number ) . '\', \'' . esc_attr( str_replace( "'", "\'", $this->title ) ) . '\', document.getElementById( \'ec_cartitem_quantity_' . esc_attr( $this->cartitem_id ) . '\' ), \'' . esc_attr( number_format( $this->unit_price, 2, '.', '' ) ) . '\' );';
		}
		if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
			echo 'ec_ga4_remove_from_cart( \'' . esc_attr( $this->model_number ) . '\', \'' . esc_attr( str_replace( "'", "\'", $this->title ) ) . '\', jQuery( document.getElementById( \'ec_quantity_' . esc_attr( $this->cartitem_id ) . '\' ) ).val(), \'' . esc_attr( number_format( $this->unit_price, 2, '.', '' ) ) . '\', \'' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '\', \'' . esc_attr( $this->manufacturer_name ) . '\', ' . esc_attr( ( get_option( 'ec_option_google_ga4_tag_manager' ) ) ? '1' : '0' ) . ' );';
		}
		echo ' ec_cart_item_delete( \'' . esc_attr( $this->cartitem_id ) . '\' ); return false;" />';
		echo '<input type="hidden" name="ec_cart_form_action" id="ec_cart_form_action" value="ec_delete_action" />';
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-delete-item-' . $this->cartitem_id ) ) . "\" />";
		echo '<input type="hidden" name="ec_delete_cartitem_id" id="ec_delete_cartitem_id_' . esc_attr( $this->cartitem_id ) . '" value="' . esc_attr( $this->cartitem_id ) . '" />';
		echo '</form>';
	}

	public function display_unit_price() {
		if ( $this->is_deconetwork ) {
			echo '<span id="ec_cartitem_unit_price_' . esc_attr( $this->cartitem_id ) . '">' . esc_attr( $GLOBALS['currency']->get_currency_display( $this->deconetwork_total / $this->quantity ) ) . '</span>';
		} else {
			echo '<span id="ec_cartitem_unit_price_' . esc_attr( $this->cartitem_id ) . '">' . esc_attr( $GLOBALS['currency']->get_currency_display( $this->unit_price ) ) . '</span>';
			if ( $this->prev_price ) {
				echo '<span id="ec_cartitem_prev_price_' . esc_attr( $this->cartitem_id ) . '" class="ec_product_old_price">' . esc_attr( $GLOBALS['currency']->get_currency_display( $this->prev_price ) ) . '</span>';
			}
		}
	}

	public function get_unit_price() {
		if ( $this->is_deconetwork ) {
			return $GLOBALS['currency']->get_currency_display( $this->deconetwork_total / $this->quantity );
		} else if ( $this->replace_price_label && in_array( $this->enable_price_label, array( 3, 5, 6, 7 ) ) ) {
			return wp_easycart_escape_html( $this->custom_price_label );
		} else {
			$extra_label = '';
			if ( ! $this->replace_price_label && in_array( $this->enable_price_label, array( 3, 5, 6, 7 ) ) ) {
				$extra_label .= wp_easycart_escape_html( $this->custom_price_label );
			}
			return apply_filters( 'wp_easycart_cart_item_unit_price_display', $GLOBALS['currency']->get_currency_display( $this->unit_price ) . $extra_label, $this->product_id );
		}
	}

	public function display_item_total() {
		if ( $this->is_deconetwork ) {
			echo '<span id="ec_cartitem_unit_price_' . esc_attr( $this->cartitem_id ) . '">' . esc_attr( $GLOBALS['currency']->get_currency_display( $this->deconetwork_total ) ) . '</span>';
		} else {
			echo '<span id="ec_cartitem_total_' . esc_attr( $this->cartitem_id ) . '">' . esc_attr( $GLOBALS['currency']->get_currency_display( $this->total_price ) ) . '</span>';
		}
	}

	public function get_total() {
		if ( $this->is_deconetwork ) {
			return $GLOBALS['currency']->get_currency_display( $this->deconetwork_total );
		} else {
			return ( 1 == $GLOBALS['currency']->get_conversion_rate() ) ? $GLOBALS['currency']->get_currency_display( $this->total_price, false ) : $GLOBALS['currency']->get_currency_display( $this->converted_total_price, false );
		}
	}

	public function display_vat_rate() {
		if ( $this->vat_enabled ) {
			if ( isset( $GLOBALS['ec_vat_rate'] ) ) {
				echo esc_attr( number_format( $GLOBALS['ec_vat_rate'], 0 ) );
			} else {
				$tax_struct = new ec_tax( 0, 0, 0, '', '' );
				echo esc_attr( number_format( $tax_struct->vat_rate , 0 ) );
			}
		} else {
			echo esc_attr( number_format( 0, 0 ) );
		}
	}

	public function display_item_loader() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec_cart_page/loader.gif' ) ) {
			echo '<div class="ec_cart_item_loader" id="ec_cart_item_loader_' . esc_attr( $this->cartitem_id ) . '"><img src="' . esc_attr( plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme', EC_PLUGIN_DATA_DIRECTORY ) . '/ec_cart_page/loader.gif' ) ) . '" /></div>';
		} else {
			echo '<div class="ec_cart_item_loader" id="ec_cart_item_loader_' . esc_attr( $this->cartitem_id ) . '"><img src="' . esc_attr( plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_base_theme' ) . '/ec_cart_page/loader.gif', EC_PLUGIN_DIRECTORY ) ) . '" /></div>';
		}
	}

	public function get_advanced_options() {
		return $this->advanced_options;
	}

	private function ec_get_permalink( $postid ) {
		if ( !get_option( 'ec_option_use_old_linking_style' ) && $postid != '0' ) {
			return $this->guid;
		} else {
			return $this->store_page . $this->permalink_divider . 'model_number=' . $this->model_number;
		}
	}

	static function ec_sort_price_tier( $a, $b ) {
		if ( $a[1] > $b[1] ) {
			return 1;
		} else {
			return -1;
		}
	}
}
