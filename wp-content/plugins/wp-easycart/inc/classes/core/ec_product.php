<?php

class ec_product{
	protected $mysqli;							// ec_db structure

	public $product_id;							// INT
	public $model_number;						// VARCHAR 255
	public $post_id;							// INT
	public $guid;								// Permalink to Product
	public $activate_in_store;					// BOOL
	public $title;								// VARCHAR 255
	public $description;						// Text
	public $short_description;					// VARCHAR 2048
	public $specifications;						// Text

	public $price;								// Float 15,3
	public $list_price;							// Float 15,3
	public $price_options;
	public $login_for_pricing;
	public $login_for_pricing_user_level;
	public $login_for_pricing_label;
	public $show_custom_price_range;			// BOOL
	public $price_range_low;					// Float 15,3
	public $price_range_high;					// Float 15,3
	public $enable_price_label;
	public $replace_price_label;
	public $custom_price_label;

	public $vat_rate;							// FLOAT 15,3
	public $handling_price;						// FLOAT 15,3
	public $handling_price_each;				// FLOAT 15,3
	public $stock_quantity;						// INT
	public $min_purchase_quantity;				// INT
	public $max_purchase_quantity;				// INT
	public $weight;								// Float 9,2
	public $width;								// DOUBLE 15,3
	public $height;								// DOUBLE 15,3
	public $length;								// DOUBLE 15,3
	public $show_stock_quantity;				// BOOL
	public $TIC;								// VARCHAR 128

	public $seo_description;					// Text
	public $seo_keywords;						// VARCHAR 255

	public $use_specifications;					// Bool
	public $use_customer_reviews;				// Bool

	public $manufacturer_id;					// INT
	public $manufacturer_name;					// VARCHAR 255

	public $download_file_name;					// VARCHAR 255
	public $is_amazon_download;
	public $amazon_key;

	public $has_options;						// BOOL
	public $options;							// ec_prodoptions structure
	public $pricing_per_sq_foot;				// BOOL

	public $images;								// ec_prodimages structure

	public $featured_products;					// ec_featuredproducts structure

	public $is_giftcard;						// Bool
	public $is_special;							// Bool
	public $is_taxable;							// Bool
	public $is_shippable;						// Bool
	public $exclude_shippable_calculation;
	public $is_download;						// Bool
	public $is_donation;						// Bool
	public $is_subscription_item;				// Bool
	public $is_catalog_mode;					// Bool
	public $is_inquiry_mode;					// Bool
	public $is_deconetwork;						// Bool
	public $include_code;						// Bool
	public $allow_backorders;					// Bool
	public $backorder_fill_date;				// DATETIME

	public $catalog_mode_phrase;				// VARCHAR 1024
	public $inquiry_url;						// VARCHAR 1024

	public $deconetwork_mode;					// VARCHAR 64
	public $deconetwork_product_id;				// VARCHAR 64
	public $deconetwork_size_id;				// VARCHAR 64
	public $deconetwork_color_id;				// VARCHAR 64
	public $deconetwork_design_id;				// VARCHAR 64

	public $subscription_bill_length;			// INT
	public $subscription_bill_period;			// VARCHAR(20)
	public $subscription_bill_duration;			// INT
	public $subscription_shipping_recurring;	// INT
	public $trial_period_days;					// INT
	public $stripe_plan_added;					// VARCHAR(128)
	public $subscription_signup_fee;			// FLOAT 15,3
	public $subscription_unique_id;				// INT
	public $subscription_prorate;				// BOOL
	public $allow_multiple_subscription_purchases;

	public $rating;								// ec_rating structure
	public $reviews = array();		 			// Array of ec_review structures

	public $use_advanced_optionset;				// Bool
	public $use_both_option_types;				// Bool
	public $has_grid_optionset = false;			// Bool

	public $use_optionitem_images;				// Bool
	public $first_selection;					// INT
	public $total_products;						// INT

	public $show_on_startup;					// Bool
	public $use_optionitem_quantity_tracking;	// Bool
	public $views;								// INT

	public $pricetiers;							// Array of Array(Price, Quantity)
	public $google_attributes;					// JSON of google attributes

	private $is_featured_product;				// BOOL
	private $is_product_details;				// BOOL
	private $is_widget;

	public $social_icons;						// ec_social_media structure

	public $account_page;
	public $cart_page;
	public $store_page;
	public $permalink_divider;

	public $promotion;							// ec_promotion structure
	public $promotion_text;						// TEXT

	private $customfields = array();			// array of ec_customfield objects

	public $menuitems;							// Menu Options
	public $categoryitems;						// Category Options
	public $option1quantity;					// Array of Option Quantity Values
	public $advanced_optionsets;				// Array of advanced option sets

	public $using_role_price;					// BOOL

	// DISPLAY VARS
	public $display_type;						// INT
	public $image_hover_type;					// INT
	public $image_effect_type;					// VARCHAR(20)
	public $tag_type;							// INT
	public $tag_bg_color;						// VARCHAR(20)
	public $tag_text_color;						// VARCHAR(20)
	public $tag_text;							// VARCHAR(20)

	public $i;									// INT
	public $page_options;						// Array

	function __construct($product_data, $is_featured_product=0, $is_product_details=0, $is_widget=0, $i=0, $page_options = NULL ){
		$this->i = $i;
		$this->page_options = $page_options;
		$this->mysqli = new ec_db();
		$this->is_featured_product = $is_featured_product;
		$this->is_product_details = $is_product_details;
		$this->is_widget = $is_widget;
		$this->setup_product($product_data);

		$accountpageid = get_option( 'ec_option_accountpage' );
		$storepageid = get_option( 'ec_option_storepage' );
		$cartpageid = get_option( 'ec_option_cartpage' );

		if( function_exists( 'icl_object_id' ) ){
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->account_page = get_permalink( $accountpageid );
		$this->store_page = get_permalink( $storepageid );
		$this->cart_page = get_permalink( $cartpageid );

		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
		}

		if( substr_count( $this->store_page, '?' ) )						$this->permalink_divider = "&";
		else																$this->permalink_divider = "?";
	}

	private function setup_product( $product_data ){

		$this->product_id = $product_data['product_id'];
		$this->model_number = $product_data['model_number'];
		$this->post_id = $product_data['post_id'];
		$this->guid = $product_data['guid'];
		$this->activate_in_store = $product_data['activate_in_store'];
		$this->title = wp_easycart_language( )->convert_text( $product_data['title'] );
		if( substr( $product_data['description'], 0, 3 ) == "[ec" ){
			$this->description = trim( $product_data['description'] );
		}else{
			$this->description = wp_easycart_language( )->convert_text( trim( $product_data['description'] ) );
		}
		$this->short_description = wp_easycart_language( )->convert_text( $product_data['short_description'] );
		if ( '' != $product_data['specifications'] && substr( $product_data['specifications'], 0, 3 ) == '[ec' ) {
			$this->specifications = trim( $product_data['specifications'] );
		} else if ( '' != $product_data['specifications'] ) {
			$this->specifications = wp_easycart_language()->convert_text( trim( $product_data['specifications'] ) );
		}

		$this->price = $product_data['price']; 
		$this->list_price = $product_data['list_price'];
		$this->login_for_pricing = $product_data['login_for_pricing'];
		$this->login_for_pricing_user_level = json_decode( $product_data['login_for_pricing_user_level'] );
		$this->login_for_pricing_label = $product_data['login_for_pricing_label'];
		$this->show_custom_price_range = $product_data['show_custom_price_range'];
		$this->price_range_low = $product_data['price_range_low'];
		$this->price_range_high = $product_data['price_range_high'];
		$this->enable_price_label = $product_data['enable_price_label'];
		$this->replace_price_label = $product_data['replace_price_label'];
		$this->custom_price_label = $product_data['custom_price_label'];

		$this->vat_rate = $product_data['vat_rate'];
		$this->handling_price = $product_data['handling_price'];
		$this->handling_price_each = $product_data['handling_price_each'];
		$this->stock_quantity = $product_data['stock_quantity'];
		$this->min_purchase_quantity = $product_data['min_purchase_quantity'];
		$this->max_purchase_quantity = $product_data['max_purchase_quantity'];
		$this->weight = $product_data['weight'];
		$this->width = $product_data['width'];
		$this->height = $product_data['height'];
		$this->length = $product_data['length'];
		$this->show_stock_quantity = $product_data['show_stock_quantity'];
		$this->pricing_per_sq_foot = false; // check later to see if true
		$this->TIC = $product_data['TIC'];

		$this->seo_description = wp_easycart_language( )->convert_text( $product_data['seo_description'] );
		$this->seo_keywords = wp_easycart_language( )->convert_text( $product_data['seo_keywords'] );

		$this->use_specifications = $product_data['use_specifications'];
		$this->use_customer_reviews = $product_data['use_customer_reviews'];

		$this->manufacturer_id = $product_data['manufacturer_id'];
		$this->manufacturer_name = $product_data['manufacturer_name'];

		$this->download_file_name = $product_data['download_file_name'];
		$this->is_amazon_download = $product_data['is_amazon_download'];
		$this->amazon_key = $product_data['amazon_key'];

		$this->has_options = false;
		$this->use_advanced_optionset = $product_data['use_advanced_optionset'];
		$this->use_both_option_types = $product_data['use_both_option_types'];
		$this->use_optionitem_images = $product_data['use_optionitem_images'];
		$this->use_optionitem_quantity_tracking = $product_data['use_optionitem_quantity_tracking'];

		if ( $product_data['option_id_1'] != 0 || $product_data['option_id_2'] != 0 || $product_data['option_id_3'] != 0 || $product_data['option_id_4'] != 0 || $product_data['option_id_5'] != 0 ) {
			$this->has_options = true;
		}

		if ( $this->use_advanced_optionset || $this->use_both_option_types ) {
			$this->advanced_optionsets = $GLOBALS['ec_advanced_optionsets']->get_advanced_optionsets( $this->product_id );
		} else {
			$this->advanced_optionsets = array( );
		}

		if( $this->has_options ){
			$this->options = new ec_prodoptions($this->product_id, $product_data['option_id_1'], $product_data['option_id_2'], $product_data['option_id_3'], $product_data['option_id_4'], $product_data['option_id_5'], $product_data['use_optionitem_quantity_tracking'] );
			if ( count( $this->options->optionset1->optionset ) < 1 && count( $this->options->optionset1->optionset ) < 1 && count( $this->options->optionset1->optionset ) < 1 && count( $this->options->optionset1->optionset ) < 1 && count( $this->options->optionset1->optionset ) < 1 ) {
				$this->has_options = false;
			}
		}else{
			$this->options = new ec_prodoptions($this->product_id, 0, 0, 0, 0, 0, 0 );
		}

		$has_unlimited_item = false;
		if ( $product_data['use_optionitem_quantity_tracking'] && $this->has_options ) {
			global $wpdb;
			$this->stock_quantity = 0;
			$option_quantity_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d', $this->product_id ) );
			if ( $option_quantity_data ) {
				foreach ( $option_quantity_data as $quantity_item ) {
					if ( $quantity_item->is_enabled && $quantity_item->is_stock_tracking_enabled ) {
						$this->stock_quantity += ( $quantity_item->quantity > 0 ) ? $quantity_item->quantity : 0;
					} else if ( $quantity_item->is_enabled && ! $quantity_item->is_stock_tracking_enabled ) {
						$this->stock_quantity += 1;
						$has_unlimited_item = true;
					}
				}
			}
		}

		if( $this->use_optionitem_images )
			$optionitem_images = $GLOBALS['ec_options']->get_optionitem_images( $this->product_id );
		else
			$optionitem_images = array( );

		if( $this->is_featured_product )
			$this->images = new ec_prodimages($this->product_id, $this->options, $this->model_number, $product_data['use_optionitem_images'], $product_data['image1'], $product_data['image2'], $product_data['image3'], $product_data['image4'], $product_data['image5'], $optionitem_images, "", $this->post_id, $this->guid, false, '', $product_data['product_images'] );
		else
			$this->images = new ec_prodimages($this->product_id, $this->options, $this->model_number, $product_data['use_optionitem_images'], $product_data['image1'], $product_data['image2'], $product_data['image3'], $product_data['image4'], $product_data['image5'], $optionitem_images, $this->get_additional_link_options(), $this->post_id, $this->guid, false, '', $product_data['product_images'] );

		if(!$this->is_featured_product && $this->is_product_details)
			$this->featured_products = new ec_featuredproducts($product_data['featured_product_id_1'], $product_data['featured_product_id_2'], $product_data['featured_product_id_3'], $product_data['featured_product_id_4']);

		$this->is_giftcard = $product_data['is_giftcard'];
		$this->is_special = $product_data['is_special'];
		$this->is_taxable = $product_data['is_taxable'];
		$this->is_shippable = $product_data['is_shippable'];
		$this->exclude_shippable_calculation =  $product_data['exclude_shippable_calculation'];
		$this->is_download = $product_data['is_download'];
		$this->is_donation = $product_data['is_donation'];
		$this->is_subscription_item = $product_data['is_subscription_item'];
		$this->is_catalog_mode = $product_data['catalog_mode'];
		$this->is_inquiry_mode = $product_data['inquiry_mode'];
		$this->is_deconetwork = $product_data['is_deconetwork'];
		$this->include_code = $product_data['include_code'];
		$this->allow_backorders = $product_data['allow_backorders'];
		$this->backorder_fill_date = $product_data['backorder_fill_date'];

		$this->catalog_mode_phrase = $product_data['catalog_mode_phrase'];
		$this->inquiry_url = $product_data['inquiry_url'];

		$this->deconetwork_mode = $product_data['deconetwork_mode'];
		$this->deconetwork_product_id = $product_data['deconetwork_product_id'];
		$this->deconetwork_size_id = $product_data['deconetwork_size_id'];
		$this->deconetwork_color_id = $product_data['deconetwork_color_id'];
		$this->deconetwork_design_id = $product_data['deconetwork_design_id'];

		$this->subscription_bill_length = $product_data['subscription_bill_length'];
		$this->subscription_bill_period = $product_data['subscription_bill_period'];
		$this->subscription_bill_duration = $product_data['subscription_bill_duration'];
		$this->subscription_shipping_recurring = isset( $product_data['subscription_shipping_recurring'] ) ? $product_data['subscription_shipping_recurring'] : 0;
		$this->trial_period_days = $product_data['trial_period_days'];
		$this->stripe_plan_added = $product_data['stripe_plan_added'];
		$this->subscription_signup_fee = $product_data['subscription_signup_fee'];
		$this->subscription_unique_id = $product_data['subscription_unique_id'];
		$this->subscription_prorate = $product_data['subscription_prorate'];
		$this->allow_multiple_subscription_purchases = $product_data['allow_multiple_subscription_purchases'];

		$this->rating = new ec_rating( $product_data['review_data'] );

		if( isset( $GLOBALS['ec_customer_reviews'] ) ){
			$this->reviews = $GLOBALS['ec_customer_reviews']->get_customer_reviews( $this->product_id );
		}

		$this->total_products = $product_data['product_count'];

		$this->show_on_startup = $product_data['show_on_startup'];
		$this->use_optionitem_quantity_tracking = $product_data['use_optionitem_quantity_tracking'];
		$this->views = $product_data['views'];
		$this->google_attributes = $product_data['google_attributes'];

		$this->price = $this->get_updated_price( $product_data['price'] );
		$this->list_price = $this->get_updated_price( $product_data['list_price'] );

		if( isset( $product_data['pricetier_data'] ) )
			$this->pricetiers = $product_data['pricetier_data'];
		else
			$this->pricetiers = array( );

		if( isset( $product_data['customfield_data'] ) )
		$this->customfields = $product_data['customfield_data'];

		$this->update_stock_quantity( $GLOBALS['ec_cart_data']->ec_cart_id, $has_unlimited_item );

		$this->social_icons = new ec_social_media( $this->model_number, $this->title, $this->description, $this->get_social_image() );

		$this->first_selection = $this->get_first_selection();

		// Check for Tiered Pricing that may initially apply
		// Quantity could be total of initial grid, minimum quantity, or 1.
		$init_tier_quantity = 1;
		if( $this->min_purchase_quantity > 1 )
			$init_tier_quantity = $this->min_purchase_quantity;
		else if( $this->get_starting_grid_quantity( ) > 0 )
			$init_tier_quantity = $this->get_starting_grid_quantity( );

		for( $i=0; $i<count( $this->pricetiers ); $i++ ){
			if( $this->pricetiers[$i][1] <= $init_tier_quantity )
				$this->price = $this->pricetiers[$i][0];
		}

		// First we should check if there is a special price for this user
		$this->using_role_price = false;
		$roleprice = $GLOBALS['ec_roleprices']->get_roleprice( $this->product_id );
		if( $roleprice ){
			if( $this->list_price <= 0 )
				$this->list_price = $this->price;
			$this->price = $roleprice;
			$this->using_role_price = true;
		}

		// Now check promotions, even if special price based on user role, use the promo price!
		$this->promotion = new ec_promotion( );
		$promotion_price = $this->promotion->single_product_promotion( $this->product_id, $this->manufacturer_id, $this->price, $this->promotion_text );
		if( !$this->is_subscription_item ){
			if( $promotion_price < $this->price ){
				if( $this->list_price == "0.00" )
					$this->list_price = $this->price;

				$this->price = $promotion_price;
			}
		}

		// Update Price with Default Selected Option Items
		$this->price_options = $this->price;
		$price_add = 0;
		$price_mult = 1;
		$price_onetime = 0;
		if ( $this->has_options ) {
			foreach ( $this->options->optionset1->optionset as $optionitem ) {
				if ( $optionitem->optionitem_initially_selected ) {
					if ( $optionitem->optionitem_price != 0 ) {
						$price_add += $optionitem->optionitem_price;
					} else if ( $optionitem->optionitem_price_onetime != 0 ) {
						$price_onetime += $optionitem->optionitem_price_onetime;
					} else if ( $optionitem->optionitem_price_override != -1 ) {
						$this->price_options = $optionitem->optionitem_price_override;
					} else if ( $optionitem->optionitem_price_multiplier != 0 ) {
						$price_mult = $price_mult * $optionitem->optionitem_price_multiplier;
					}
				}
			}
		}
		if ( $this->use_advanced_optionset || $this->use_both_option_types ) {
			foreach ( $this->advanced_optionsets as $optionset ) {
				if ( $this->is_option_initially_visible( $optionset ) ) {
					$optionitems = $this->get_advanced_optionitems( $optionset->option_id );
					foreach ( $optionitems as $optionitem ) {
						if ( $optionitem->optionitem_initially_selected ) {
							if ( $optionitem->optionitem_price != 0 ) {
								$price_add += $optionitem->optionitem_price;
							} else if ( $optionitem->optionitem_price_onetime != 0 ) {
								$price_onetime += $optionitem->optionitem_price_onetime;
							} else if ( $optionitem->optionitem_price_override != -1 ) {
								$this->price_options = $optionitem->optionitem_price_override;
							} else if ( $optionitem->optionitem_price_multiplier != 0 ) {
								$price_mult = $price_mult * $optionitem->optionitem_price_multiplier;
							}
						}
					}
				}
			}
		}
		$this->price_options += $price_add;
		$this->price_options = $this->price_options * $price_mult;

		// Update Tiered Pricing
		for( $k=0; $k<count( $this->pricetiers ); $k++ ){
			$promotion_price = $this->promotion->single_product_promotion( $this->product_id, $this->manufacturer_id, $this->pricetiers[$k][0], $this->promotion_text );
			if( $promotion_price < $this->pricetiers[$k] ){
				$this->pricetiers[$k][0] = $promotion_price;
			}
		}

		if( $this->is_product_details ){
			// Get menu and category connections
			$this->menuitems = $this->mysqli->get_menu_values( $this->product_id );
			$this->categoryitems = $this->mysqli->get_category_values( $this->product_id );
			if( $this->use_optionitem_quantity_tracking ){
				$this->option1quantity = array( );
				foreach( $this->options->optionset1->optionset as $result_item ){
					if( isset( $this->options->quantity_array[$result_item->optionitem_id.'....'] ) )
						$this->option1quantity[$result_item->optionitem_id] = $this->options->quantity_array[$result_item->optionitem_id.'....'];
					else
						$this->option1quantity[$result_item->optionitem_id] = 0;
				}
			}

			// Loop through options, select correct text if transalation used
			for( $adv_index = 0; $adv_index < count( $this->advanced_optionsets ); $adv_index++ ){
				$this->advanced_optionsets[$adv_index]->option_label = wp_easycart_language( )->convert_text( $this->advanced_optionsets[$adv_index]->option_label );
				$this->advanced_optionsets[$adv_index]->option_error_text = wp_easycart_language( )->convert_text( $this->advanced_optionsets[$adv_index]->option_error_text );
			}
		}

		// Loop through options, see if sq. footage used
		for( $adv_index = 0; $adv_index < count( $this->advanced_optionsets ); $adv_index++ ){
			if( $this->advanced_optionsets[$adv_index]->option_type == "dimensions1" || $this->advanced_optionsets[$adv_index]->option_type == "dimensions2" )
				$this->pricing_per_sq_foot = true;
		}

		// DISPLAY VARS
		$this->display_type = $product_data['display_type'];
		$this->image_hover_type = $product_data['image_hover_type'];
		$this->image_effect_type = $product_data['image_effect_type'];
		$this->tag_type = $product_data['tag_type'];
		$this->tag_bg_color = $product_data['tag_bg_color'];
		$this->tag_text_color = $product_data['tag_text_color'];
		$this->tag_text = $product_data['tag_text'];

	}

	public function get_updated_price( $price ){
		if( $this->vat_rate != 0 ){
			$shipping_state = '';
			$shipping_country = '';
			if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
				$shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
			}else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
				$shipping_state = $GLOBALS['ec_user']->shipping->state;
			}
			if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
				$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
			}else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
				$shipping_country = $GLOBALS['ec_user']->shipping->country;
			}

			$tax = new ec_tax( $price, $price, $price, $shipping_state, $shipping_country, false, 0, (object) array( 
				'cart' => array( 
					(object) array( 
						'product_id' => $this->product_id, 
						'total_price' => $price, 
						'manufacturer_id' => $this->manufacturer_id, 
						'is_taxable' => $this->is_taxable,
						'vat_enabled' => $this->vat_rate
					)
				)
			) );
			if( $tax->vat_included && $tax->vat_rate_default != $tax->vat_rate ){
				$base_price = number_format( $price / ( 1 + $tax->vat_rate_default / 100 ), 2, '.', '' );
				$price = $base_price * ( 1 + $tax->vat_rate / 100 );
			}
		}
		return $price;
	}

	public function get_first_selection( ){

		// Use the following to determine the selected image and swatch.
		// If a optionitem_id is avaialable, then we want to match that up. Otherwise randomize it.
		$tot_items = 0;
		if ( $this->has_options && ( ! $this->use_advanced_optionset || $this->use_both_option_types ) ) {
			$tot_items = count( $this->options->optionset1->optionset );
		}
		if ( isset( $_GET['optionitem_id'] ) && $_GET['optionitem_id'] != '' ) {
			for ( $i=0; $i<$tot_items; $i++ ) {
				if ( $_GET['optionitem_id'] == $this->options->optionset1->optionset[$i]->optionitem_id ) {
					if( ! $this->use_optionitem_quantity_tracking || ( $this->use_optionitem_quantity_tracking && $this->options->quantity_array[$i][1] > 0 ) ) {
						return $i;
					}
				}
			}
		} else if( $this->use_optionitem_quantity_tracking ) {
			for ( $a=0; $a<count( $this->options->optionset1->optionset ); $a++ ) {
				if ( isset( $this->options->quantity_array[$this->options->optionset1->optionset[$a]->optionitem_id.'....'] ) ) {
					return $a;
				}
			}
			return -1;
		} else {
			return 0;
		}

	}

	private function get_starting_grid_quantity( ){
		$quantity = 0;
		$quantity_grid_i = -1;
		for( $i=0; $i<count( $this->advanced_optionsets ); $i++ ){
			if( $this->advanced_optionsets[$i]->option_type == "grid" )
				$quantity_grid_i = $i;
		}
		if( $quantity_grid_i >= 0 ){
			$optionitems = $this->mysqli->get_advanced_optionitems( $this->advanced_optionsets[$quantity_grid_i]->option_id );
			for( $i=0; $i<count( $optionitems ); $i++ ){
				$quantity = $quantity + (int) $optionitems[$i]->optionitem_initial_value;
			}
		}
		return $quantity;
	}

	public function update_stock_quantity( $session_id, $has_unlimited_item = false ){
		global $wpdb;
		if ( ! $has_unlimited_item && get_option( 'ec_option_stock_removed_in_cart' ) ) {
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			$tempcart_data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_tempcart WHERE product_id = %d AND last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ), $this->product_id, $hours ) );
			foreach( $tempcart_data as $tempcart_item ){
				$this->stock_quantity -= $tempcart_item->quantity;
				if( $this->use_optionitem_quantity_tracking ){
					if( isset( $this->option1quantity[$tempcart_item->optionitem_id_1] ) ){
						$this->option1quantity[$tempcart_item->optionitem_id_1] -= $tempcart_item->quantity;
					}
				}
			}

		} else if ( ! $has_unlimited_item ) {
			$quantity = $GLOBALS['ec_cart_data']->get_tempcart_product_quantity( $this->product_id );
			$this->stock_quantity = $this->stock_quantity - $quantity;
		}
	}

	public function display_product_quick_view( $link_text ){

		if( $this->is_deconetwork ){
			echo "<div class=\"ec_product_quick_view\" id=\"ec_product_quick_view_" . esc_attr( $this->model_number ) . "\"><a href=\"" . esc_attr( $this->get_deconetwork_link( ) ) . "\">" . wp_easycart_language( )->get_text( 'product_page', 'product_design_now' ) . "</a></div>";
		}else{
			echo "<div class=\"ec_product_quick_view\" id=\"ec_product_quick_view_" . esc_attr( $this->model_number ) . "\"><a href=\"#\" onclick=\"ec_product_show_quick_view('" . esc_attr( $this->model_number ) . "'); return false;\">" . esc_attr( $link_text ) . "</a></div>";
		}

	}

	/* Display the form start */
	public function display_product_details_form_start( ){

		// Go to the login page, at the same time save this subscription to session
		if( get_option( 'ec_option_payment_process_method' ) == 'stripe' && $this->is_subscription_item ){
			echo "<form action=\"" . esc_attr( $this->cart_page ) . "\" method=\"post\" enctype=\"multipart/form-data\">";

		// Go to the subscription page for PayPal
		}else if( get_option( 'ec_option_payment_third_party' ) == 'paypal' && $this->is_subscription_item ){

			if( get_option( 'ec_option_paypal_use_sandbox' ) )			
				$paypal_request = "https://www.sandbox.paypal.com/cgi-bin/webscr";
			else
				$paypal_request = "https://www.paypal.com/cgi-bin/webscr";

			echo "<form action=\"" . esc_attr( $paypal_request ) . "\" method=\"post\">";
			echo "<input type=\"hidden\" name=\"cmd\" value=\"_xclick-subscriptions\">";
			echo "<input name=\"bn\" id=\"bn\" type=\"hidden\" value=\"LevelFourDevelopmentLLC_Cart\">";
			echo "<input type=\"hidden\" name=\"business\" value=\"" . esc_attr( get_option( 'ec_option_paypal_email' ) ) . "\">";
			echo "<input type=\"hidden\" name=\"currency_code\" value=\"" . esc_attr( get_option( 'ec_option_paypal_currency_code' ) ) . "\">";
			echo "<input type=\"hidden\" name=\"no_shipping\" value=\"1\">";

			echo "<input type=\"hidden\" name=\"item_name\" value=\"" . esc_attr( $this->title ) . "\">";
			echo "<input type=\"hidden\" name=\"a3\" value=\"" . esc_attr( number_format( $this->price, 2 ) ) . "\">";
			echo "<input type=\"hidden\" name=\"p3\" value=\"" . esc_attr( $this->subscription_bill_length ) . "\">";
			echo "<input type=\"hidden\" name=\"t3\" value=\"" . esc_attr( $this->subscription_bill_period ) . "\">";

			echo "<input type=\"hidden\" name=\"src\" value=\"1\">";
			echo "<input type=\"hidden\" name=\"sra\" value=\"1\">";
			echo "<input type=\"hidden\" name=\"usr_manage\" value=\"1\">";
			echo "<input type=\"hidden\" name=\"modify\" value=\"0\">";


		// Go to the cart	
		}else{
			echo "<form action=\"" . esc_attr( $this->cart_page ) . "\" method=\"post\" enctype=\"multipart/form-data\">";
		}
	}

	/* Display the form end */
	public function display_product_details_form_end( ){
		global $language_data;

		if( get_option( 'ec_option_payment_process_method' ) == 'stripe' && $this->is_subscription_item ){
			echo "<input name=\"model_number\" id=\"model_number\" type=\"hidden\" value=\"" . esc_attr( $this->model_number ) . "\" />";
			echo "<input name=\"ec_cart_form_action\" id=\"ec_cart_form_action_" . esc_attr( $this->model_number ) . "\" value=\"purchase_subscription\" type=\"hidden\" />";
			echo "<script>jQuery( '#ec_product_details_quantity_" . esc_attr( $this->model_number ) . "' ).hide( ); </script>";
			echo "</form>";

		}else if( get_option( 'ec_option_payment_third_party' ) == 'paypal' && $this->is_subscription_item ){
			echo "<script>jQuery( '#ec_product_details_quantity_" . esc_attr( $this->model_number ) . "' ).hide( ); </script>";
			echo "</form>";
		}else{
			echo "<input name=\"is_donation\" id=\"is_donation_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->is_donation ) . "\" />";
			echo "<input name=\"product_id\" id=\"product_id\" type=\"hidden\" value=\"" . esc_attr( $this->product_id ) . "\" />";
			echo "<input name=\"session_id\" id=\"session_id\" type=\"hidden\" value=\"" . esc_attr( $GLOBALS['ec_cart_data']->ec_cart_id ) . "\" />";
			echo "<input name=\"model_number\" id=\"model_number\" type=\"hidden\" value=\"" . esc_attr( $this->model_number ) . "\" />";
			echo "<input name=\"quantity\" id=\"quantity_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->stock_quantity ) . "\" />";
			echo "<input name=\"show_stock_quantity\" id=\"show_stock_quantity_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->show_stock_quantity ) . "\" />";
			echo "<input name=\"ec_use_advanced_optionset\" id=\"ec_use_advanced_optionset_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->use_advanced_optionset ) . "\" />";
			echo "<input name=\"ec_use_both_option_types\" id=\"ec_use_both_option_types_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->use_both_option_types ) . "\" />";
			echo "<input name=\"pricetier_quantity\" id=\"pricetier_quantity_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->get_price_tier_quantity_string( ) ) . "\" />";
			echo "<input name=\"pricetier_price\" id=\"pricetier_price_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->get_price_tier_price_string( ) ) . "\" />";
			echo "<input name=\"use_optionitem_quantity_tracking\" id=\"use_optionitem_quantity_tracking_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->use_optionitem_quantity_tracking ) . "\" />";
			echo "<input name=\"use_optionitem_images\" id=\"use_optionitem_images_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->use_optionitem_images ) . "\" />";
			echo "<input name=\"initial_swatch_selected\" id=\"initial_swatch_selected_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( $this->first_selection ) . "\" />";
			echo "<input type=\"hidden\" name=\"ec_product_details_base_path\" id=\"ec_product_details_base_path_" . esc_attr( $this->model_number ) . "\" value=\"" . esc_attr( plugins_url( ) ) . "\" />";
			echo "<input type=\"hidden\" name=\"ec_product_details_form_action\" id=\"ec_product_details_form_action_" . esc_attr( $this->model_number ) . "\" value=\"add_to_cart\" />";
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) ."/ec_product_details_page/ec_product_details_page_get_stock_quantity.php" ) )	
				echo "<input name=\"ec_jquery_get_stock_quantity_file\" id=\"ec_jquery_get_stock_quantity_file_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) ."/ec_product_details_page/ec_product_details_page_get_stock_quantity.php", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" />";
			else
				echo "<input name=\"ec_jquery_get_stock_quantity_file\" id=\"ec_jquery_get_stock_quantity_file_" . esc_attr( $this->model_number ) . "\" type=\"hidden\" value=\"" . esc_attr( plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_base_theme' ) ."/ec_product_details_page/ec_product_details_page_get_stock_quantity.php", EC_PLUGIN_DIRECTORY ) ) . "\" />";
			echo "<input name=\"ec_cart_form_action\" id=\"ec_cart_form_action_" . esc_attr( $this->model_number ) . "\" value=\"add_to_cart\" type=\"hidden\" />";
			echo "</form>";
		}
	}

	public function display_product_category_links( $divider, $featured_product_text ){

		// If has menu level 1, show link
		if( isset( $_GET['menuid'] ) && isset( $_GET['menu'] ) )
		echo "<a href=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "menuid=" . esc_attr( (int) $_GET['menuid'] ) . "&menu=" . esc_attr( (int) $_GET['menu'] ) . "\" class=\"ec_product_title_link\">" . esc_attr( (int) $_GET['menu'] ) . "</a>" . esc_attr( $divider );

		// If has menu level 2, show link
		if( isset( $_GET['submenuid'] ) && isset( $_GET['submenu'] ) )
		echo "<a href=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "submenuid=" . esc_attr( (int) $_GET['submenuid'] ) . "&submenu=" . esc_attr( (int) $_GET['submenu'] ) . "\" class=\"ec_product_title_link\">" . esc_attr( (int) $_GET['submenu'] ) . "</a>" . esc_attr( $divider );

		// If has menu level 3, show link
		if( isset( $_GET['subsubmenuid'] ) && isset( $_GET['subsubmenu'] ) )
		echo "<a href=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "subsubmenuid=" . esc_attr( (int) $_GET['subsubmenuid'] ) . "&subsubmenu=" . esc_attr( (int) $_GET['subsubmenu'] ) . "\" class=\"ec_product_title_link\">" . esc_attr( (int) $_GET['subsubmenu'] ) . "</a>" . esc_attr( $divider );

		// If no menu, but is a store startup product, show link
		if( $this->show_on_startup && !isset( $_GET['menuid'] ) && !isset( $_GET['submenuid'] ) && !isset( $_GET['subsubmenuid'] ) )
		echo "<a href=\"" . esc_attr( $this->store_page ) . "\" class=\"ec_product_title_link\">" . esc_attr( $featured_product_text ) . "</a>" . esc_attr( $divider );

		// show product link
		echo "<a href=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "model_number=" . esc_attr( $this->model_number . $this->get_additional_link_options( ) ) . "\" class=\"ec_product_title_link\">" . esc_attr( $this->title ) . "</a>";

	}

	/* Display the product title with a link to the product details page */
	public function display_product_title_link( ){

		$permalink =  $this->ec_get_permalink( $this->post_id );
		$add_options = $this->get_additional_link_options();
		if( $add_options != "" ){
			if( substr( $add_options, 0, 5 ) == "&amp;" )
				$add_options = substr( $add_options, 5, strlen( $add_options ) - 5 );

			if( get_option( 'ec_option_use_old_linking_style' ) ){
				$add_options = "&" . $add_options;
			}else{
				if( substr_count( $permalink, '?' ) ){
					$add_options = "&" . $add_options;
				}else{
					$add_options = $this->permalink_divider . $add_options;
				}
			}
		}
		if( $this->is_deconetwork )
			echo "<a href=\"" . esc_attr( $this->get_deconetwork_link( ) ) . "\" class=\"ec_product_title_link\">" . esc_attr( $this->title ) . "</a>";
		else if( $this->is_featured_product ) 
			echo "<a href=\"" . esc_attr( $permalink ) . "\" class=\"ec_product_title_link\">" . esc_attr( $this->title ) . "</a>";
		else
			echo "<a href=\"" . esc_attr( $permalink . $add_options ) . "\" class=\"ec_product_title_link\">" . esc_attr( $this->title ) . "</a>";

	}

	/* Display the link to the product details page */
	public function display_product_link( $link_text ){

		$permalink =  $this->ec_get_permalink( $this->post_id );
		$add_options = $this->get_additional_link_options();
		if( $add_options != "" ){
			if( substr( $add_options, 0, 5 ) == "&amp;" )
				$add_options = substr( $add_options, 5, strlen( $add_options ) - 5 );

			if( get_option( 'ec_option_use_old_linking_style' ) ){
				$add_options = "&" . $add_options;
			}else{
				$add_options = $this->permalink_divider . $add_options;
			}
		}

		if( $this->is_deconetwork )
			echo "<a href=\"" . esc_attr( $this->get_deconetwork_link( ) ) . "\" class=\"ec_product_title_link\">" . esc_attr( $link_text ) . "</a>";
		else
			echo "<a href=\"" . esc_attr( $permalink . $add_options ) . "\" class=\"ec_product_title_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function has_promotion_text( ){
		if( $this->promotion_text )
			return true;
		else
			return false;	
	}

	public function display_promotion_text( ){
		echo esc_attr( $this->promotion_text );
	}

	/* Display the product title text */
	public function display_product_title( ){
		echo esc_attr( $this->title );
	}

	public function get_rating( ){
		$total = 0;
		for( $i=0; $i<count( $this->reviews ); $i++ ){
			$total = $total + $this->reviews[$i]->rating;	
		}
		if( $i > 0 )
			$average = ceil( $total/$i );
		else
			$average = 0;

		return $average;
	}

	/* Display the star icons for the product */
	public function display_product_stars( ){
		$total = 0;
		for( $i=0; $i<count( $this->reviews ); $i++ ){
			$total = $total + $this->reviews[$i]->rating;	
		}
		if( $i > 0 )
			$average = ceil( $total/$i );
		else
			$average = 0;
		$this->rating->display_stars( $average );	
	}

	/* Does this product have reviews yet?*/
	public function has_reviews( ){
		if( count( $this->reviews ) > 0 )
			return true;
		else
			return false;
	}

	/* Display the number of reviews for the product */
	public function display_product_number_reviews( ){
		echo count( $this->reviews );
	}

	/* Return the number of reviews for the product */
	public function get_product_number_reviews( ){
		return $this->rating->display_number_reviews( );
	}

	/* Display the input price for product */
	public function display_price_input( ){
		echo "<input type=\"text\" name=\"ec_product_input_price\" id=\"ec_product_input_price\" value=\"" . esc_attr( $GLOBALS['currency']->get_number_only( $this->price ) ) . "\" />";
		echo "<input type=\"hidden\" name=\"ec_product_min_donation_amount\" id=\"ec_product_min_donation_amount\" value=\"" . esc_attr( $GLOBALS['currency']->get_number_only( $this->price ) ) . "\" />";
	}

	/* Display the product price */
	public function display_price( $font = false, $color = false, $rand_id = 0 ){

		$output = "";

		if( $this->show_custom_price_range && $this->list_price != "0.00" )
			$output .= "<span class=\"ec_product_sale_price\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\">";

		else if( $this->show_custom_price_range )
			$output .= "<span class=\"ec_product_price\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\">";

		else if( $this->list_price != "0.00" )
			$output .= "<span class=\"ec_product_sale_price ec_product_sale_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\">";

		else
			$output .= "<span class=\"ec_product_price ec_product_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\">";

		$output = apply_filters( 'wp_easycart_product_details_price_pre_num', $output, $this->product_id, $rand_id );

		if( $this->show_custom_price_range ){
			if ( $this->price_range_high > 0 ) {
				$output .= esc_attr( $GLOBALS['currency']->get_currency_display( $this->price_range_low ) . ' - ' . $GLOBALS['currency']->get_currency_display( $this->price_range_high ) );
			} else {
				$output .= esc_attr( wp_easycart_language( )->get_text( 'product_details', 'product_details_starting_at' ) . ' ' . $GLOBALS['currency']->get_currency_display( $this->price_range_low ) );
			}
		}else{
			$output .= esc_attr( $GLOBALS['currency']->get_currency_display( $this->price_options ) );
		}

		$output = apply_filters( 'wp_easycart_product_details_price_post_num', $output, $this->product_id, $rand_id );

		if( $this->is_subscription_item ){

			$ret_string = "";

			$ret_string .= "/";

			if( $this->subscription_bill_length > 1 ){

				$ret_string .= esc_attr( $this->subscription_bill_length . " " . $this->get_subscription_period_name( ) . "s" );

			}else{

				$ret_string .= esc_attr( $this->get_subscription_period_name( ) );

			}

			if( $this->subscription_bill_duration > 0 ){

				$ret_string .= ' ' . wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_duration_divider' ) . ' ' . esc_attr( $this->subscription_bill_duration . ' ' . $this->get_subscription_period_name_full( ) );

			}

			$ret_string = apply_filters( 'wp_easycart_subscription_price_formatting', $ret_string );

			$output .= "</span><span class=\"ec_product_price\">" . $ret_string;

		}

		$output .= "</span>";

		if( $this->pricing_per_sq_foot && !get_option( 'ec_option_enable_metric_unit_display' ) ){ $output .= "/sq ft"; }
		else if( $this->pricing_per_sq_foot && get_option( 'ec_option_enable_metric_unit_display' ) ){ $output .= "/sq m"; }

		echo wp_easycart_escape_html( apply_filters( 'wp_easycart_product_details_price_display', $output, $this->price ) );

	}

	public function get_price_formatted( $subscription_quantity = 1, $price = false ) {
		if ( ! $price ) {
			$price = $this->price;
		}
		$ret_string = "/"; 
		if( $this->subscription_bill_length > 1 ){
			$ret_string .= $this->subscription_bill_length . " " . $this->get_subscription_period_name( ) . "s";
		}else{
			$ret_string .= $this->get_subscription_period_name( );
		}

		if( $this->subscription_bill_duration > 0 ){
			$ret_string .= ' ' . wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_duration_divider' ) . ' ' . $this->subscription_bill_duration . ' ' . $this->get_subscription_period_name_full( );
		}

		$ret_string = $GLOBALS['currency']->get_currency_display( $price * $subscription_quantity  ) . apply_filters( 'wp_easycart_subscription_price_formatting', $ret_string );

		return $ret_string;

	}

	public function get_option_price_formatted( $price, $subscription_quantity = 1 ){

		$ret_string = "/"; 
		if( $this->subscription_bill_length > 1 ){
			$ret_string .= $this->subscription_bill_length . " " . $this->get_subscription_period_name( ) . "s";
		}else{
			$ret_string .= $this->get_subscription_period_name( );
		}

		if( $this->subscription_bill_duration > 0 ){
			$ret_string .= ' ' . wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_duration_divider' ) . ' ' . $this->subscription_bill_duration . ' ' . $this->get_subscription_period_name_full( );
		}

		$ret_string = $GLOBALS['currency']->get_currency_display( $price * $subscription_quantity  ) . apply_filters( 'wp_easycart_subscription_price_formatting', $ret_string );

		return $ret_string;

	}

	public function get_subscription_period_name( ){
		if( $this->subscription_bill_period == 'D' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_day' );
		else if( $this->subscription_bill_period == 'W' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_week' );
		else if( $this->subscription_bill_period == 'M' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_month' );
		else if( $this->subscription_bill_period == 'Y' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_year' );
	}

	public function get_subscription_period_name_full( ){
		if( $this->subscription_bill_period == 'D' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_day' );
		else if( $this->subscription_bill_period == 'W' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_week' );
		else if( $this->subscription_bill_period == 'M' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_month_full' );
		else if( $this->subscription_bill_period == 'Y' )
			return wp_easycart_language( )->get_text( 'product_details', 'product_details_subscription_year' );
	}

	public function display_list_price( $font = false, $color = false ){
		if( $this->list_price != "0.00" )
		echo "<span class=\"ec_product_old_price\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->list_price ) ) . "</span>";
	}

	public function display_product_price( $font = false, $color = false, $rand_id = 0 ){

		$price = $GLOBALS['currency']->convert_price( $this->price_options );
		$p_arr = explode( ".", $price );
		$p_cents = "";
		$p_dollar = "";
		if( count( $p_arr ) > 0 ){
			$p_dollar = $p_arr[0];
		}

		if( count( $p_arr ) > 1 ){
			$p_cents = $p_arr[1];
		}

		$p_cent = $GLOBALS['currency']->format_cents( $p_cents );

		if( $this->list_price != "0.000" )
			echo "<span class=\"ec_product_sale_price ec_product_sale_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\"><span class=\"currency\">" . esc_attr( $GLOBALS['currency']->get_symbol( ) ) . "</span><span class=\"dollar\">" . esc_attr( $p_dollar ) . "</span><span class=\"cent\">" . esc_attr( $p_cent ) . "</span></span>";

		else
			echo "<span class=\"ec_product_price ec_product_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\"><span class=\"currency\">" . esc_attr( $GLOBALS['currency']->get_symbol( ) ) . "</span><span class=\"dollar\">" . esc_attr( $p_dollar ) . "</span><span class=\"cent\">" . esc_attr( $p_cent ) . "</span></span>";
	}

	/* Display the product list price (if available it is the "old price" */
	public function display_product_list_price( $font = false, $color = false ){

		$list_price = $GLOBALS['currency']->convert_price( $this->list_price );
		$p_arr = explode( ".", $list_price );
		$p_cents = "";
		$p_dollar = "";
		if( count( $p_arr ) > 0 ){
			$p_dollar = $p_arr[0];
		}

		if( count( $p_arr ) > 1 ){
			$p_cents = $p_arr[1];
		}
		$p_cent = $GLOBALS['currency']->format_cents( $p_cents );

		$output = "";

		if( $this->list_price != "0.000" ){
			$output .= "<span class=\"ec_product_old_price\" style=\"" . ( ( $font ) ? 'font-family:' . esc_attr( $font ) . ' !important;' : '' ) . ( ( $color ) ? 'color:' . esc_attr( $color ) . ' !important;' : '' ) . "\">";
			if( $GLOBALS['currency']->get_symbol_location( ) ){ 
				$output .= "<span class=\"currency\">" . esc_attr( $GLOBALS['currency']->get_symbol( ) ) . "</span>";
			}
			$output .= "<span class=\"dollar\">" . esc_attr( $p_dollar ) . "</span><span class=\"cent\">" . esc_attr( $GLOBALS['currency']->get_decimal_symbol( ) . $p_cent ) . "</span>";
			if( !$GLOBALS['currency']->get_symbol_location( ) ){ 
				$output .= "<span class=\"currency\">" . esc_attr( $GLOBALS['currency']->get_symbol( ) ) . "</span>";
			}
			$output .= "</span>";
		}

		echo wp_easycart_escape_html( apply_filters( 'wp_easycart_product_details_list_price_display', $output, $this->list_price ) );

	}

	/* Display the product pricing with and without vat */
	public function display_product_pricing_no_vat( $price_font = false, $price_color = false, $list_price_font = false, $list_price_color = false, $rand_id = 0 ){

		$price = $GLOBALS['currency']->convert_price( $this->price_options );
		$list_price = $GLOBALS['currency']->convert_price( $this->list_price );

		$shipping_state = '';
		$shipping_country = '';
		if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
			$shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
		}else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
			$shipping_state = $GLOBALS['ec_user']->shipping->state;
		}
		if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
			$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
		}else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
			$shipping_country = $GLOBALS['ec_user']->shipping->country;
		}

		$tax_price = new ec_tax( $price, $price, $price, $shipping_state, $shipping_country, false, 0, (object) array( 
			'cart' => array( 
				(object) array( 
					'product_id' => $this->product_id, 
					'total_price' => $price, 
					'manufacturer_id' => $this->manufacturer_id, 
					'is_taxable' => $this->is_taxable, 
					'vat_enabled' => $this->vat_rate 
				)
			)
		) );
		$tax_list_price = new ec_tax( $list_price, $list_price, $list_price, $shipping_state, $shipping_country, false, 0, (object) array( 
			'cart' => array( 
				(object) array( 
					'product_id' => $this->product_id, 
					'total_price' => $list_price, 
					'manufacturer_id' => $this->manufacturer_id, 
					'is_taxable' => $this->is_taxable, 
					'vat_enabled' => $this->vat_rate 
				)
			)
		) );

		if( $tax_price->vat_included ){ // remove vat from price
			$price = $price / ( 1 + $tax_price->vat_rate / 100 );
			$list_price = $list_price / ( 1 + $tax_price->vat_rate / 100 );
		}

		$p_arr = explode( ".", $list_price );
		$p_cents = "";
		$p_dollar = "";
		if( count( $p_arr ) > 0 ){
			$p_dollar = $p_arr[0];
		}

		if( count( $p_arr ) > 1 ){
			$p_cents = $p_arr[1];
		}
		$p_cent = $GLOBALS['currency']->format_cents( $p_cents );

		$output = "";
		if( $list_price != "0.000" )
			$output .= "<span class=\"ec_product_old_price\" style=\"" . ( ( $list_price_font ) ? 'font-family:' . esc_attr( $list_price_font ) . ' !important;' : '' ) . ( ( $list_price_color ) ? 'color:' . esc_attr( $list_price_color ) . ' !important;' : '' ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $list_price ) ) . "</span></span><span class=\"ec_product_sale_price ec_product_sale_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $price ) ) . "</span> " . wp_easycart_language( )->get_text( 'product_details', 'product_details_vat_excluded' ) . " " . wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_vat' );
		else
			$output .= "<span class=\"ec_product_price ec_product_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\" style=\"" . ( ( $price_font ) ? 'font-family:' . esc_attr( $price_font ) . ' !important;' : '' ) . ( ( $price_color ) ? 'color:' . esc_attr( $price_color ) . ' !important;' : '' ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $price ) ) . "</span> " . wp_easycart_language( )->get_text( 'product_details', 'product_details_vat_excluded' ) . " " . wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_vat' );

		echo wp_easycart_escape_html( apply_filters( 'wp_easycart_product_details_price_no_vat_display', $output, $price, $list_price, $tax_price, $tax_list_price ) );

	}

	public function display_product_pricing_vat( $price_font = false, $price_color = false, $list_price_font = false, $list_price_color = false, $rand_id = 0 ){

		$price = $GLOBALS['currency']->convert_price( $this->price_options );
		$list_price = $GLOBALS['currency']->convert_price( $this->list_price );

		$shipping_state = '';
		$shipping_country = '';
		if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
			$shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
		}else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
			$shipping_state = $GLOBALS['ec_user']->shipping->state;
		}
		if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
			$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
		}else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
			$shipping_country = $GLOBALS['ec_user']->shipping->country;
		}

		$tax_price = new ec_tax( $price, $price, $price, $shipping_state, $shipping_country, false, 0, (object) array( 
			'cart' => array( 
				(object) array( 
					'product_id' => $this->product_id, 
					'total_price' => $this->price, 
					'manufacturer_id' => $this->manufacturer_id, 
					'is_taxable' => $this->is_taxable, 
					'vat_enabled' => $this->vat_rate 
				)
			)
		) );
		$tax_list_price = new ec_tax( $list_price, $list_price, $list_price, $shipping_state, $shipping_country, false, 0, (object) array( 
			'cart' => array( 
				(object) array( 
					'product_id' => $this->product_id, 
					'total_price' => $this->price, 
					'manufacturer_id' => $this->manufacturer_id, 
					'is_taxable' => $this->is_taxable, 
					'vat_enabled' => $this->vat_rate 
				)
			)
		) );

		if( $tax_price->vat_added ){ // remove vat from price
			$price = $price + $tax_price->vat_total;
			$list_price = $list_price + $tax_list_price->vat_total;
		}

		$p_arr = explode( ".", $list_price );
		$p_cents = "";
		$p_dollar = "";
		if( count( $p_arr ) > 0 ){
			$p_dollar = $p_arr[0];
		}

		if( count( $p_arr ) > 1 ){
			$p_cents = $p_arr[1];
		}
		$p_cent = $GLOBALS['currency']->format_cents( $p_cents );

		$output = "";
		if( $list_price != "0.000" )
			$output .= "<span class=\"ec_product_old_price\" style=\"" . ( ( $list_price_font ) ? 'font-family:' . esc_attr( $list_price_font ) . ' !important;' : '' ) . ( ( $list_price_color ) ? 'color:' . esc_attr( $list_price_color ) . ' !important;' : '' ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $list_price ) ) . "</span><span class=\"ec_product_sale_price ec_product_sale_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $price ) ) . "</span> " . wp_easycart_language( )->get_text( 'product_details', 'product_details_vat_included' ) . " " . number_format( esc_attr( $tax_price->vat_rate ), 0, '', '' ) . "% " . wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_vat' );
		else
			$output .= "<span class=\"ec_product_price ec_product_price_" . esc_attr( $this->product_id ) . '_' . esc_attr( $rand_id ) . "\" style=\"" . ( ( $price_font ) ? 'font-family:' . esc_attr( $price_font ) . ' !important;' : '' ) . ( ( $price_color ) ? 'color:' . esc_attr( $price_color ) . ' !important;' : '' ) . "\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $price ) ) . "</span> " . wp_easycart_language( )->get_text( 'product_details', 'product_details_vat_included' ) . " " . number_format( esc_attr( $tax_price->vat_rate ), 0, '', '' ) . "% " . wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_vat' );

		echo wp_easycart_escape_html( apply_filters( 'wp_easycart_product_details_price_vat_display', $output, $price, $list_price, $tax_price, $tax_list_price ) );

	}

	public function get_product_price_without_vat( ){

		$price = $GLOBALS['currency']->convert_price( $this->price_options );

		$shipping_state = '';
		$shipping_country = '';
		if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
			$shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
		}else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
			$shipping_state = $GLOBALS['ec_user']->shipping->state;
		}
		if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
			$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
		}else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
			$shipping_country = $GLOBALS['ec_user']->shipping->country;
		}

		$tax_price = new ec_tax( $price, $price, $price, $shipping_state, $shipping_country, false, 0, (object) array( 
			'cart' => array( 
				(object) array( 
					'product_id' => $this->product_id, 
					'total_price' => $price, 
					'manufacturer_id' => $this->manufacturer_id, 
					'is_taxable' => $this->is_taxable, 
					'vat_enabled' => $this->vat_rate 
				)
			)
		) );

		if( $tax_price->vat_included ){ // remove vat from price
			$price = number_format( $price / ( 1 + $tax_price->vat_rate / 100 ), 2, '.', '' );
		}

		return $price;

	}

	public function get_product_price_with_vat( ){

		$price = $GLOBALS['currency']->convert_price( $this->price_options );

		$shipping_state = '';
		$shipping_country = '';
		if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
			$shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
		}else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
			$shipping_state = $GLOBALS['ec_user']->shipping->state;
		}
		if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
			$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
		}else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
			$shipping_country = $GLOBALS['ec_user']->shipping->country;
		}

		$tax_price = new ec_tax( $price, $price, $price, $shipping_state, $shipping_country, false, 0, (object) array( 
			'cart' => array( 
				(object) array( 
					'product_id' => $this->product_id, 
					'total_price' => $this->price, 
					'manufacturer_id' => $this->manufacturer_id, 
					'is_taxable' => $this->is_taxable, 
					'vat_enabled' => $this->vat_rate 
				)
			)
		) );

		if( $tax_price->vat_added ){ // remove vat from price
			$price = $price + $tax_price->vat_total;
		}

		return $price;

	}

	/* Display the percentage number for the discount percentage */
	public function display_product_discount_percentage( ){
		if( $this->list_price != "0.00" )				echo esc_attr( round( 100 - ( ( $this->price / $this->list_price ) * 100 ) ) );
	}

	/* Display the product in stock quantity */
	public function display_product_stock_quantity( ){
		if( $this->use_optionitem_quantity_tracking )
			echo esc_attr( $this->options->quantity_array[$this->first_selection][1] );
		else
			echo esc_attr( $this->stock_quantity );
	}

	/* Display the product model number */
	public function display_product_model_number( ){
		echo esc_attr( $this->model_number );
	}

	/* Display the minimum purchase amount if needed */
	public function display_minimum_purchase_amount( ){
		if( $this->min_purchase_quantity > 0 ){
			echo "<div class=\"ec_min_quantity_amount_text\">" . sprintf( wp_easycart_language( )->get_text( 'product_details', 'product_details_minimum_quantity_text1' ) . " %d " . wp_easycart_language( )->get_text( 'product_details', 'product_details_minimum_quantity_text2' ), esc_attr( $this->min_purchase_quantity ) ) . "</div>";
		}
	}

	/* Display the quantity input box */
	public function display_product_quantity_input( $default ){
		if( $this->min_purchase_quantity > 0 ){ $default = $this->min_purchase_quantity; }

		echo "<input type=\"number\" value=\"" . esc_attr( $default ) . "\" name=\"product_quantity\" id=\"product_quantity_" . esc_attr( $this->model_number ) . "\" class=\"product_quantity_input\" />";
	}

	/* Display the add to cart button */
	public function display_product_add_to_cart_button( $title, $id ){
		if( $this->stock_quantity > 0 ){
			// Subscription Button
			if( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_third_party' ) == 'paypal' ) && $this->is_subscription_item ){
				echo "<input type=\"submit\" value=\"" . wp_easycart_language( )->get_text( 'product_details', 'product_details_sign_up_now' ) . "\" name=\"ec_product_details_add_to_cart_button\" id=\"ec_product_details_add_to_cart_button\" class=\"ec_product_details_add_to_cart_button\">";

			// Catalog Mode Button
			}else if( $this->is_catalog_mode ){

				echo '<div class="ec_product_details_catalog_mode_phrase">' . esc_attr( $this->catalog_mode_phrase ) . '</div>';

			// Inquiry Mode
			}else if( $this->is_inquiry_mode ){

				if( $this->inquiry_url != "" ){

					if( substr( $this->inquiry_url, 0, 4 ) != 'http' )					$this->inquiry_url = "http://" . $this->inquiry_url;
					if( substr_count( $this->inquiry_url, '?' ) )						$divider = "&";
					else																$divider = "?";

					echo '<a href="' . esc_url_raw( $this->inquiry_url . $divider ) . 'model_number=' . esc_attr( $this->model_number ) . '">' . wp_easycart_language( )->get_text( 'product_details', 'product_details_inquire' ) . '</a>';

				}

			// Add to Cart Button
			}else{
				echo "<input type=\"submit\" value=\"" . esc_attr( $title ) . "\" name=\"ec_product_details_add_to_cart_button\" id=\"ec_product_details_add_to_cart_button\" class=\"ec_product_details_add_to_cart_button\" ";
				if ( $this->use_advanced_optionset || $this->use_both_option_types ) {
					echo "onclick=\"ec_google_addToCart( ); return ec_product_details_add_to_cart_advanced( '" . esc_attr( $this->model_number ) . "' );\" />";
				} else {
					echo "onclick=\"ec_google_addToCart( ); return ec_product_details_add_to_cart( '" . esc_attr( $this->model_number ) . "' );\" />";
				}
				echo "<div class=\"ec_error_message_box\" id=\"" . esc_attr( $id ) . "_" . esc_attr( $this->model_number ) . "\">error text here</div>";
			}
		}else{
			echo "<div class=\"ec_product_details_quantity\">" . wp_easycart_language( )->get_text( 'product_details', 'product_details_out_of_stock' ) . "</div>";
		}
	}

	/* Display the add to cart button */
	public function display_product_add_to_cart_button_no_validation( $title, $id ){
		echo "<input type=\"submit\" value=\"" . esc_attr( $title ) . "\" name=\"ec_product_details_add_to_cart_button\" id=\"ec_product_details_add_to_cart_button\" class=\"ec_product_details_add_to_cart_button\" />";
	}

	/* Display the product image set */
	public function display_product_image_set( $size, $id_prefix, $js_function_name ){
		if( $this->first_selection == -1 ){
			echo wp_easycart_escape_html( $this->images->get_product_images( $size, 0, $id_prefix, $js_function_name ) );
		}else{
			echo wp_easycart_escape_html( $this->images->get_product_images( $size, $this->first_selection, $id_prefix, $js_function_name ) );
		}
	}

	/* Display the product details image set */
	public function display_product_details_image_set( $size, $id_prefix, $js_function_name ){
		if( $this->first_selection == -1 ){
			echo wp_easycart_escape_html( $this->images->get_product_details_images( $size, 0, $id_prefix, $js_function_name ) );
		}else{
			echo wp_easycart_escape_html( $this->images->get_product_details_images( $size, $this->first_selection, $id_prefix, $js_function_name ) );
		}
	}

	/* Display the product image thumbnails */
	public function display_product_image_thumbnails( $size, $id_prefix, $js_function_name ){
		if( $this->first_selection == -1 ){
			echo wp_easycart_escape_html( $this->images->get_product_thumbnails( $size, 0, $id_prefix, $js_function_name ) );
		}else{
			echo wp_easycart_escape_html( $this->images->get_product_thumbnails( $size, $this->first_selection, $id_prefix, $js_function_name ) );
		}

		// need some javascript added to guarantee the correct image is hidden
		echo "<script>jQuery( document ).ready( function( ){ ec_thumb_quick_view_click('" . esc_attr( $this->model_number ) . "', 0, 1); } );</script>";
	}

	public function has_thumbnails( ){
		if( $this->images->has_thumbnails( ) )		return true;
		else										return false;	
	}

	/* Get random selection */
	private function get_random_selection( $tot_items ){
		return rand( 0, $tot_items-1 );	
	}

	/* */
	public function product_has_swatches( &$optionset ){
		if( $optionset->is_swatch() )
			return true;
		else
			return false;
	}

	public function product_has_combo( $optionset ){
		if( $optionset->is_combo() )						
			return true;
		else
			return false;
	}

	/* Display the product option drop down or swatches*/
	public function display_product_option( &$optionset, $size, $level, $id_prefix, $js_function_name ){

		if( $optionset->is_combo() )						$this->display_product_option_combo( $optionset, $level, $id_prefix, $js_function_name );
		else if( $optionset->is_swatch() )					$this->display_product_option_swatches( $optionset, $size, $level, $id_prefix, $js_function_name );

	}

	/* Display all option sets */
	public function display_all_advanced_optionsets( ){
		$optionsets = $GLOBALS['ec_advanced_optionsets']->get_advanced_optionsets( $this->product_id );
		$i=0;
		foreach( $optionsets as $optionset ){
			if( $optionset->option_type == "combo" )
				$this->display_advanced_option_combo( $optionset, $i );
			else if( $optionset->option_type == "swatch" )
				$this->display_advanced_option_swatch( $optionset, $i );
			else if( $optionset->option_type == "checkbox" )
				$this->display_advanced_option_checkbox( $optionset, $i );
			else if( $optionset->option_type == "text" )
				$this->display_advanced_option_text( $optionset, $i );
			else if( $optionset->option_type == "textarea" )
				$this->display_advanced_option_textarea( $optionset, $i );
			else if( $optionset->option_type == "file" )
				$this->display_advanced_option_file( $optionset, $i );
			else if( $optionset->option_type == "radio" )
				$this->display_advanced_option_radio( $optionset, $i );
			else if( $optionset->option_type == "grid" )
				$this->display_advanced_option_grid( $optionset, $i );
			else if( $optionset->option_type == "date" )
				$this->display_advanced_option_date( $optionset, $i );
			else if( $optionset->option_type == "number" )
				$this->display_advanced_option_number( $optionset, $i );

			$i++;
		}
	}

	public function display_advanced_option_combo( $optionset, $i ){
		$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_combo_row\"><select name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" class=\"ec_product_details_option_combo\" onchange=\"ec_product_details_combo_change(" . esc_attr( $optionset->option_id ) . ", '" . esc_attr( $this->model_number ) . "');\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\">";
		echo "<option value=\"0\" data-quantitystring=\"" . esc_attr( $this->stock_quantity ) . "\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . "</option>";
		foreach( $optionitems as $optionitem ){
			$optionitem_price = ""; 
			if( $optionitem->optionitem_price > 0 ){ 
			  $optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
			}else if( $optionitem->optionitem_price < 0 ){ 
			  $optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
			}else if( $optionitem->optionitem_price_onetime > 0 ){ 
			  $optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
			}else if( $optionitem->optionitem_price_onetime < 0 ){ 
			  $optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
			}else if( $optionitem->optionitem_price_override >= 0 ){ 
			  $optionitem_price = " (" . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) . ")"; 
			}

			echo "<option data-quantitystring=\"" . esc_attr( $this->stock_quantity ) . "\" value=\"" . esc_attr( $optionitem->optionitem_id ) . "\">" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name ) . esc_attr( $optionitem_price ) . "</option>";
		}
		echo "</select></div>";
	}
	public function display_advanced_option_swatch( $optionset, $i ){
		$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
		$j=0;
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_swatch_row\">";
		foreach( $optionitems as $optionitem ){

			$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/" . $optionitem->optionitem_icon;
			$test_src2 = EC_PLUGIN_DIRECTORY . "/products/swatches/" . $optionitem->optionitem_icon;
			$test_src3 = EC_PLUGIN_DATA_DIRECTORY . "/design/themes/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

			if ( substr( $optionitem->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionitem->optionitem_icon, 0, 8 ) == 'https://' ) {
				$thumb_src = $optionitem->optionitem_icon;

			} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
				$thumb_src = plugins_url( "wp-easycart-data/products/swatches/" . $optionitem->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY );

			} else if ( file_exists( $test_src2 ) && !is_dir( $test_src2 ) ) {
				$thumb_src = plugins_url( "wp-easycart/products/swatches/" . $optionitem->optionitem_icon, EC_PLUGIN_DIRECTORY );

			} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
				$thumb_src = get_option( 'ec_option_product_image_default' );

			} else if ( file_exists( $test_src3 ) && !is_dir( $test_src3 ) ) {
				$thumb_src = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

			} else {
				$thumb_src = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

			}
			echo "<img src=\"" . esc_attr( $thumb_src ) . "\" alt=\"" . esc_js( $optionitem->optionitem_name ) . "\" class=\"";

			echo "ec_product_swatch";

			echo "\" onclick=\"ec_swatch_click('" . esc_attr( $this->model_number ) . "', " . esc_attr( $i ) . ", " . esc_attr( $j ) . ");\" id=\"ec_swatch_" . esc_attr( $this->model_number ) . "_" . esc_attr( $i ) . "_" . esc_attr( $j ) . "\" data-optionitemid=\"" . esc_attr( $optionitem->optionitem_id ) . "\" data-quantitystring=\"9999\" width=\"" . esc_attr( get_option( 'ec_option_swatch_large_width' ) ) . "\" height=\"" . esc_attr( get_option( 'ec_option_swatch_large_height' ) ) . "\" \>";
			$j++;	
		}
		echo "</div>";

		echo "<input type=\"hidden\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" value=\"0\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\" />";

	}
	public function display_advanced_option_checkbox( $optionset, $i ){
		$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_checkbox_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_checkbox_box\">";
		$j=0;
		foreach( $optionitems as $optionitem ){
			$optionitem_price = ""; 
			if( $optionitem->optionitem_price > 0 ){ 
			  $optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
			}else if( $optionitem->optionitem_price < 0 ){ 
			  $optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
			}else if( $optionitem->optionitem_price_onetime > 0 ){ 
			  $optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
			}else if( $optionitem->optionitem_price_onetime < 0 ){ 
			  $optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
			}else if( $optionitem->optionitem_price_override >= 0 ){ 
			  $optionitem_price = " (" . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) . ")"; 
			}

			echo "<div class=\"ec_option_checkbox_row\"><input type=\"checkbox\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "_" . esc_attr( $optionitem->optionitem_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_" . esc_attr( $j ) . "\" value=\"" . esc_attr( $optionitem->optionitem_name ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\">" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name ) . esc_attr( $optionitem_price ) . "</div>";
			$j++;
		}
		echo "</div>";
	}
	public function display_advanced_option_text( $optionset, $i ){
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_text_label_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_text_row\"><input class=\"ec_option_text\" type=\"text\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\" /></div>";
	}
	public function display_advanced_option_number( $optionset, $i ){
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_text_label_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_text_row\"><input class=\"ec_option_text\" type=\"number\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\" /></div>";
	}
	public function display_advanced_option_textarea( $optionset, $i ){
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_textarea_label_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_textarea_row\"><textarea class=\"ec_option_textarea\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\"></textarea></div>";
	}
	public function display_advanced_option_file( $optionset, $i ){
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_file_label_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_file_row\"><input class=\"ec_option_text\" type=\"file\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\" /></div>";
	}
	public function display_advanced_option_radio( $optionset, $i ){
		$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_radio_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_radio_box\">";
		$j=0;
		foreach( $optionitems as $optionitem ){
			$optionitem_price = ""; 
			if( $optionitem->optionitem_price > 0 ){ 
			  $optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
			}else if( $optionitem->optionitem_price < 0 ){ 
			  $optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
			}else if( $optionitem->optionitem_price_onetime > 0 ){ 
			  $optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
			}else if( $optionitem->optionitem_price_onetime < 0 ){ 
			  $optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
			}else if( $optionitem->optionitem_price_override >= 0 ){ 
			  $optionitem_price = " (" . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) . ")"; 
			}

			echo "<div class=\"ec_option_radio_row\"><input type=\"radio\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number  ). "_" . esc_attr( $j ) . "\" value=\"" . esc_attr( $optionitem->optionitem_id ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\">" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name ) . esc_attr( $optionitem_price ) . "</div>";
			$j++;
		}
		echo "</div>";
	}
	public function display_advanced_option_grid( $optionset, $i ){
		$this->has_grid_optionset = true;
		$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_grid_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_grid_box\">";
		$j=0;
		foreach( $optionitems as $optionitem ){
			echo "<div class=\"ec_option_grid_row\"><span class=\"ec_option_grid_label\">" . wp_easycart_language( )->convert_text( $optionitem->optionitem_name ) . ":</span><span class=\"ec_option_grid_input\"><input type=\"number\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "_" . esc_attr( $optionitem->optionitem_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_" . esc_attr( $j ) ."\" value=\"" . esc_attr( $optionitem->optionitem_initial_value ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\"></span></div>";
			$j++;
		}
		echo "</div>";
	}
	public function display_advanced_option_date( $optionset, $i ){
		echo "<div class=\"ec_option_error_row\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "_error\"><div class=\"ec_option_error_row_inner\">" . wp_easycart_language( )->convert_text( $optionset->option_error_text ) . "</div></div>";
		echo "<div class=\"ec_option_text_label_row\">" . wp_easycart_language( )->convert_text( $optionset->option_label ) . ":</div><div class=\"ec_option_text_row\"><input class=\"ec_option_text\" type=\"date\" name=\"ec_option_" . esc_attr( $optionset->option_id ) . "\" id=\"ec_option" . esc_attr( $i ) . "_" . esc_attr( $this->model_number ) . "\" data-ec-required=\"" . esc_attr( $optionset->option_required ) . "\" /></div>";
	}

	/* Display product option swatches */
	public function display_product_option_swatches( &$optionset, $size, $level, $id_prefix, $js_function_name, $show_input=true ){
		global $language_data;
		$selected_accepted = 0;
		if( count( $optionset->optionset ) > 0 && $optionset->optionset[0]->optionitem_icon ){

			for( $i=0; $i<count( $optionset->optionset ); $i++ ){

				$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/swatches/" . $optionset->optionset[$i]->optionitem_icon;
				$test_src2 = EC_PLUGIN_DIRECTORY . "/products/swatches/" . $optionset->optionset[$i]->optionitem_icon;
				$test_src3 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

				if ( substr( $optionset->optionset[$i]->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionset->optionset[$i]->optionitem_icon, 0, 8 ) == 'https://' ) {
					$thumb_src = $optionset->optionset[$i]->optionitem_icon;

				} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
					$thumb_src = plugins_url( "wp-easycart-data/products/swatches/" . $optionset->optionset[$i]->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY );

				} else if ( file_exists( $test_src2 ) && !is_dir( $test_src2 ) ) {
					$thumb_src = plugins_url( "wp-easycart/products/swatches/" . $optionset->optionset[$i]->optionitem_icon, EC_PLUGIN_DIRECTORY );

				} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
					$thumb_src = get_option( 'ec_option_product_image_default' );

				} else if ( file_exists( $test_src3 ) && !is_dir( $test_src3 ) ) {
					$thumb_src = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

				} else {
					$thumb_src = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

				}
				echo "<img src=\"" . esc_url( $thumb_src ) . "\" alt=\"" . esc_js( $optionset->optionset[$i]->optionitem_name ) . "\" class=\"";

				if( $this->use_optionitem_quantity_tracking && $this->options->quantity_array[$i][1] < 1 )
					echo "ec_product_swatch_out_of_stock";
				else if( $i == $this->first_selection ){
					$selected_accepted++; echo "ec_product_swatch_selected";
				}else
					echo "ec_product_swatch";

				if( $this->use_optionitem_quantity_tracking )
					echo "\" onclick=\"" . esc_attr( $js_function_name ) . "('" . esc_attr( $this->model_number ) . "', " . esc_attr( $level ) . ", " . esc_attr( $i ) . ");\" id=\"" . esc_attr( $id_prefix ) . esc_attr( $this->model_number ) . "_" . esc_attr( $level ) . "_" . esc_attr( $i ) . "\" data-optionitemid=\"" . esc_attr( $optionset->optionset[$i]->optionitem_id ) . "\" data-quantitystring=\"" . esc_attr( $this->options->get_quantity_string( $level, $i ) ) . "\" width=\"" . esc_attr( get_option( 'ec_option_swatch_' . $size . '_width' ) ) . "\" height=\"" . esc_attr( get_option( 'ec_option_swatch_' . $size . '_height' ) ) . "\" \>";
				else
					echo "\" onclick=\"" . esc_attr( $js_function_name ) . "('" . esc_attr( $this->model_number ) . "', " . esc_attr( $level ) . ", " . esc_attr( $i ) . ");\" id=\"" . esc_attr( $id_prefix ) . esc_attr( $this->model_number ) . "_" . esc_attr( $level ) . "_" . esc_attr( $i ) . "\" data-optionitemid=\"" . esc_attr( $optionset->optionset[$i]->optionitem_id ) . "\" data-quantitystring=\"9999\" width=\"" . esc_attr( get_option( 'ec_option_swatch_' . $size . '_width' ) ) . "\" height=\"" . esc_attr( get_option( 'ec_option_swatch_' . $size . '_height' ) ) . "\" \>";

			}

			echo "<div id=\"ec_option_" . esc_attr( $level ) . "_error\" class=\"ec_product_details_option_error_text\">" . esc_attr( $language_data[10] ) . "</div>";

			$optionitem_id = 0;
			if( isset( $_GET['optionitem_id'] ) )
				$optionitem_id = (int) $_GET['optionitem_id'];
			else if( $level == 1 || !$this->use_optionitem_quantity_tracking )
				$optionitem_id = $optionset->optionset[$this->first_selection]->optionitem_id;

			if( $show_input )
				echo "<input type=\"hidden\" name=\"ec_option" . esc_attr( $level ) . "\" id=\"ec_option" . esc_attr( $level ) . "_" . esc_attr( $this->model_number ) . "\" value=\"" . esc_attr( (int) $optionitem_id ) . "\" />";

			// need some javascript added to guarantee the correct image is hidden
			echo "<script>jQuery( document ).ready( function( ){ ec_swatch_click('" . esc_attr( $this->model_number ) . "', 1, 0); } );</script>";

		}
	}

	/* Display product option combo box */
	public function display_product_option_combo( &$optionset, $level, $id_prefix, $js_function_name ){
		if( count( $optionset->optionset ) > 0 && $optionset->option_name != "" ){
			echo "<select name=\"ec_option" . esc_attr( $level ) . "\" id=\"ec_option" . esc_attr( $level ) . "_" . esc_attr( $this->model_number ) . "\" class=\"ec_product_details_option_combo\" onchange=\"ec_product_details_combo_change(" . esc_attr( $level ) . ", '" . esc_attr( $this->model_number ) . "');\">";
			echo "<option value=\"0\" data-quantitystring=\"" . esc_attr( $this->stock_quantity ) . "\">" . wp_easycart_escape_html( $optionset->option_label ) . "</option>";
			for( $i=0; $i<count( $optionset->optionset ); $i++ ){
				echo "<option data-quantitystring=\"" . esc_attr( $this->options->get_quantity_string( $level, $i ) ) . "\" value=\"" . esc_attr( $optionset->optionset[$i]->optionitem_id ) . "\">" . esc_attr( $optionset->optionset[$i]->get_optionitem_label( ) ) . "</option>";
			}
			echo "</select>";
		}
	}

	/* Display Description */
	public function display_product_description( ){

		if( substr( $this->description, 0, 3 ) == "[ec" ){
			$content = $this->process_special_content( stripslashes_deep( $this->description ) );
		}else{
			$content = $this->process_normal_content( $this->description );
		}
		echo wp_easycart_escape_html( $content ); // XSS OK.

	}

	/* Does this product have a description */
	public function product_has_description( ){
		if( $this->description )								return true;
		else													return false;	
	}

	/* Display Specifications */
	public function display_product_specifications( ){


		if( substr( $this->specifications, 0, 3 ) == "[ec" ){
			$content = $this->process_special_content( stripslashes_deep( $this->specifications ) );
		}else{
			$content = $this->process_normal_content( $this->specifications );
		}
		echo wp_easycart_escape_html( $content ); // XSS OK.


	}

	/* Does this product have specifications */
	public function product_has_specifications( ){
		if( $this->use_specifications )							return true;
		else													return false;	
	}

	public function process_normal_content( $content ){

		preg_match_all( '/(<table.+?\/table>)/s', $content, $table_array, PREG_PATTERN_ORDER );
		$desc2 = preg_replace( '/(<table.+?\/table>)/s', '[TABLE]', $content );
		$content = nl2br( $desc2 );

		for( $i=0; $i<count( $table_array[0] ); $i++ ){
			$content = preg_replace( '/\[TABLE\]/s', $table_array[0][$i], $content, 1 );
		}

		return $content;

	}

	public function process_special_content( $content ){
		preg_match_all( '/(<table.+?\/table>)/s', $content, $table_array, PREG_PATTERN_ORDER );
		$desc2 = preg_replace( '/(<table.+?\/table>)/s', '[TABLE]', $content );
		$content = nl2br( $desc2 );

		for( $i=0; $i<count( $table_array[0] ); $i++ ){
			$content = preg_replace( '/\[TABLE\]/s', $table_array[0][$i], $content, 1 );
		}

		// NORMAL ROWS //////
		// Replace [ecrow1_1] shortcode
		$content = preg_replace( "/\[ecrow_11(.*?)\](.*?)\[\/ecrow_11\]/", "<div class='ecrow_11$1'>$2</div>", $content );

		// Replace [ecrow1_2] shortcode
		$content = preg_replace( "/\[ecrow_12(.*?)\](.*?)\[\/ecrow_12\]/", "<div class='ecrow_12$1'>$2</div>", $content );

		// Replace [ecrow1_3] shortcode
		$content = preg_replace( "/\[ecrow_13(.*?)\](.*?)\[\/ecrow_13\]/", "<div class='ecrow_13$1'>$2</div>", $content );

		// Replace [ecrow2_3] shortcode
		$content = preg_replace( "/\[ecrow_23(.*?)\](.*?)\[\/ecrow_23\]/", "<div class='ecrow_23$1'>$2</div>", $content );

		// Replace [ecrow1_4] shortcode
		$content = preg_replace( "/\[ecrow_14(.*?)\](.*?)\[\/ecrow_14\]/", "<div class='ecrow_14$1'>$2</div>", $content );

		// Replace [ecrow3_4] shortcode
		$content = preg_replace( "/\[ecrow_34(.*?)\](.*?)\[\/ecrow_34\]/", "<div class='ecrow_34$1'>$2</div>", $content );

		// Replace [ecrow1_5] shortcode
		$content = preg_replace( "/\[ecrow_15(.*?)\](.*?)\[\/ecrow_15\]/", "<div class='ecrow_15$1'>$2</div>", $content );

		// Replace [ecrow2_5] shortcode
		$content = preg_replace( "/\[ecrow_25(.*?)\](.*?)\[\/ecrow_25\]/", "<div class='ecrow_25$1'>$2</div>", $content );

		// Replace [ecrow3_5] shortcode
		$content = preg_replace( "/\[ecrow_35(.*?)\](.*?)\[\/ecrow_35\]/", "<div class='ecrow_35$1'>$2</div>", $content );

		// Replace [ecrow4_5] shortcode
		$content = preg_replace( "/\[ecrow_45(.*?)\](.*?)\[\/ecrow_45\]/", "<div class='ecrow_45$1'>$2</div>", $content );

		// SPECIAL ELEMENTS //////
		// Replace [echeading]HEADER[/echeading] shortcode
		$content = preg_replace_callback( "/\[echeading size='(.*?)' color='(.*?)' position='(.*?)' padding='(.*?)'\](.*?)\[\/echeading\]/", array( $this, "header_shortcode_callback" ), $content );

		// Replace [ecdivider] shortcode
		$content = preg_replace( "/\[ecdivider\]\[\/ecdivider\]/", "<hr class='ec_special_divider' />", $content );

		// Replace [ecicon]ICONNAME[/ecicon] shortcode
		$content = preg_replace( "/\[ecicon\](.*?)\[\/ecicon\]/", "<div class='ec_special_icon dashicons dashicons-$1'>$1</div>", $content );

		// Replace [eciconbox icon='' title='']ICON CONTENT[/eciconbox] shortcode
		$content = preg_replace_callback( "/\[eciconbox title='(.*?)' icon='(.*?)' position='(.*?)' link='(.*?)'](.*?)\[\/eciconbox\]/", array( $this, "iconbox_shortcode_callback" ), $content );

		// Replace [eciconlist][eciconlistitem icon='' title='']ICON CONTENT[/iconlistitem]...[/eciconlist] shortcode
		$content = preg_replace( "/\[eciconlist\](.*?)\[\/eciconlist\]/", "<div class='ec_special_iconlist'>$1</div>", $content );

		$content = preg_replace_callback( "/\[eciconlistitem title='(.*?)' icon='(.*?)' position='(.*?)' link='(.*?)'\](.*?)\[\/eciconlistitem\]/", array( $this, "iconlistitem_shortcode_callback" ), $content );

		// Replace [ecvideo]URL[/ecvideo] shortcode
		$content = preg_replace( "/\[ecvideo\](.*?)\[\/ecvideo\]/", "<div class='ec_special_video' itemprop='video' itemtype='https://schema.org/VideoObject'><div class='ec_special_videowrap'><iframe width='1500' height='844' src='$1?feature=oembed&amp;wmode=opaque' frameborder='0' allowfullscreen=''></iframe></div></div>", preg_replace( "/\[ecvideo\]http[s]*:\/\/[www.]*youtube.com[\/]*\?watch=(.*?)\[\/ecvideo\]/", "[ecvideo]https://www.youtube.com/embed/$1[/ecvideo]", $content ) );

		// Replace [ecimage]URL[/ecimage] shortcode
		$content = preg_replace( "/\[ecimage alignment='(.*?)' link='(.*?)'\](.*?)\[\/ecimage\]/", "<div class='ec_special_image' style='text-align:$1'><a href='$2'><img src='$3' /></a></div>", $content );

		// Replace [ectext]URL[/ectext] shortcode
		$content = preg_replace_callback( "/\[ectext\](.*?)\[\/ectext\]/", array( $this, "text_shortcode_callback" ), $content );

		// Replace [ecshortcode]URL[/ecshortcode] shortcode
		$content = preg_replace_callback( "/\[ecshortcode\](.*?)\[\/ecshortcode\]/", array($this, "replace_shortcode" ), $content );

		// Before returning, alter format all text from the flash editor
		$content = preg_replace( "/\<[P,p][\s][A,a][L,l][I,i][G,g][N,n]=\"(.+?)\"\>/", "<p style=\"text-align:$1;\">", $content );
		$content = preg_replace( "/\<[F,f][O,o][N,n][T,t]/", "<span", $content );
		$content = preg_replace( "/\<\/[F,f][O,o][N,n][T,t]/", "</span", $content );
		$content = preg_replace( "/\<\/[P,p]/", "</p", $content );
		$content = preg_replace( "/\<[B,b] /", "<B style=\"color:inherit !important;\"", $content );
		$content = preg_replace( "/FACE=\"(.*?)\" SIZE=\"(.*?)\" COLOR=\"(.*?)\"(.*?)\>/", "style=\"font-family:$1; font-size:$2px; color:$3;\"\>", $content );
		$content = preg_replace( "/\<[D,d][I,i][V,v][\s][C,c][O,o][L,l][O,o][R,r]\=[\"\'](.*?)[\"\']\>.*?\<[A,a][\s][H,h][R,r][E,e][F,f]\=[\"\'](.*?)[\"\'][\s][T,t][A,a][R,r][G,g][E,e][T,t]\=[\"\'](.*?)[\"\']\>(.*?)\<\/[A,a]\>.*?\<\/[D,d][I,i][V,v]\>/", "<a href=\"$2\" target=\"$3\" style=\"color:$1 !important;\">$4</a>", $content );

		return $content;
	}

	public function header_shortcode_callback( $matches ){
		return "<" . $matches[1] . " class='ec_special_heading' style='color:" . $matches[2] . "; text-align:" . $matches[3] . "; padding-bottom:" . $matches[4] . "px;'>" . wp_easycart_language( )->convert_text( $matches[5] ) . "</h1>";
	}

	public function text_shortcode_callback( $matches ){
		return "<div class='ec_text'>" . wp_easycart_language( )->convert_text( $matches[1] ) . "</div>";
	}

	public function iconbox_shortcode_callback( $matches ){
		return "<div class='ec_special_iconbox_" . $matches[3] . "'><div class='ec_special_icon dashicons dashicons-" . $matches[2] . "'><a href='" . $matches[4] . "'>" . $matches[2] . "</a></div><div class='ec_special_iconlist_content'><h3>" . wp_easycart_language( )->convert_text( $matches[1] ) . "</h3><span>" . wp_easycart_language( )->convert_text( $matches[5] ) . "</span></div></div>";
	}

	public function iconlistitem_shortcode_callback( $matches ){
		return "<div class='ec_special_iconlist_item'><div class='ec_special_icon_list dashicons dashicons-" . $matches[2] . "'><a href='" . $matches[4] . "'>" . $matches[2] . "</a></div><div class='ec_special_iconlist_content'><h3>" . wp_easycart_language( )->convert_text( $matches[1] ) . "</h3><span>" . wp_easycart_language( )->convert_text( $matches[5] ) . "</span></div></div>";
	}

	public function replace_shortcode( $matches ){
		if( $matches[1] != "[ec_store]" ){
			$shortcode_result = do_shortcode( $matches[1] );
			return "<div class='ec_shortcode'>" . $shortcode_result . "</div>";
		}else{
			return "<div class='ec_shortcode'>cannot use [ec_store] shortcode here</div>";
		}
	}

	/* Display Ratings */
	public function display_product_reviews( ){
		foreach( $this->reviews as $review_row ){
			$review = new ec_review( $review_row );
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_customer_review.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_customer_review.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_customer_review.php' );
		}
	}

	/* Does this product have a customer reviews */
	public function product_has_customer_reviews( ){
		if( $this->use_customer_reviews )						return true;
		else													return false;	
	}

	/* Display Customer Review Open Button */
	public function display_product_customer_review_open_button( $review_text, $complete_text ){
		echo "<a href=\"#\" onclick=\"product_details_customer_review_open( ); return false;\" id=\"ec_open_review_button\" />" . esc_attr( $review_text ) . "</a><div id=\"ec_open_review_button_submitted\">" . esc_attr( $complete_text ) . "</div>";
	}

	/* Display Customer Review Close Button */
	public function display_product_customer_review_close_button( $review_text ){
		echo "<a href=\"#\" onclick=\"product_details_customer_review_close( ); return false;\" />" . esc_attr( $review_text ) . "</a>";
	}

	/* Print Out Customer Review Form Tag */
	public function display_product_customer_review_form_start( ){
		global $wp_query;
		$post_obj = $wp_query->get_queried_object();
		if( isset( $post_obj ) && isset( $post_obj->ID ) ){
			$post_id = $post_obj->ID;
		}else{
			$post_id = 0;
		}
		$product = $GLOBALS['ec_products']->get_product_from_post_id( $post_id );

		if( isset( $_GET['optionitem_id'] ) ){
			echo "<form action=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "model_number=" . esc_attr( $this->model_number ) . "&optionitem_id=" . esc_attr( (int) $_GET['optionitem_id'] ) . "\" method=\"post\" id=\"customer_review_form\">";
		}else{
			echo "<form action=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "model_number=" . esc_attr( $this->model_number ) . "\" method=\"post\" id=\"customer_review_form\">";
		}
	}

	/* Print Out Customer Review Closing Form Tag */
	public function display_product_customer_review_form_end( ){
		echo "<input type=\"hidden\" name=\"ec_customer_review_base_path\" id=\"ec_customer_review_base_path\" value=\"" . esc_attr( plugins_url( ) ) . "\" />";
		echo "<input type=\"hidden\" name=\"ec_customer_review_form_action\" id=\"ec_customer_review_form_action\" value=\"submit_review\" />";
		echo "<input type=\"hidden\" name=\"ec_customer_review_product_id\" value=\"".esc_attr( $this->product_id )."\" />";
		echo "</form>";
		echo "<div id=\"ec_customer_review_loader\" class=\"ec_product_details_loader_div\">LOADING</div>";
	}

	/* Display the selection stars to rate the product*/
	public function display_product_customer_review_rating_stars( ){
		global $language_data;
		for( $i=0; $i<5; $i++ )
			echo "<div class=\"ec_customer_review_star_off\" onmouseover=\"ec_customer_review_star_hover(" . esc_attr( $i ) . ");\" onmouseout=\"ec_customer_review_star_rollout(" . esc_attr( $i ) . ");\" onclick=\"ec_customer_review_star_click(" . esc_attr( $i ) . ");\" id=\"ec_customer_review_star_" . esc_attr( $i ) . "\"></div>";

		echo "<div class=\"ec_product_details_customer_review_error_text\" id=\"ec_customer_review_rating_error\">".esc_attr( $language_data[7] )."</div>";
		echo "<input type=\"hidden\" id=\"ec_customer_review_rating\" name=\"ec_customer_review_rating\" value=\"0\" />";
	}


	/* Display the input box for the customer review title */
	public function display_product_customer_review_title_input( ){
		echo "<input type=\"text\" name=\"ec_customer_review_title\" id=\"ec_customer_review_title\" class=\"ec_customer_review_title\" />";
	}

	/* Display the input box for the customer review description */
	public function display_product_customer_review_description_input( ){
		echo "<textarea name=\"ec_customer_review_description\" id=\"ec_customer_review_description\" class=\"ec_customer_review_description\"></textarea>";
	}

	/* Display the submit button for the customer review*/
	public function display_product_customer_review_submit_button( $text_label ){
		echo "<input type=\"submit\" name=\"ec_customer_review_submit_button\" id=\"ec_customer_review_submit_button\" value=\"" . esc_attr( $text_label ) . "\" onclick=\"return submit_customer_review();\" />";
	}

	/* Does this product have a discount */
	public function product_has_discount( ){
		if( $this->list_price == "0.000" )						return false;
		else													return true;	
	}

	/* Display the Featured Products */
	public function product_has_featured_products( ){
		if( $this->featured_products->product1 || $this->featured_products->product2 || $this->featured_products->product3 || $this->featured_products->product4 )
			return true;
		else
			return false;
	}

	public function display_featured_products( ){
		if( isset( $this->featured_products->product1 ) && $this->featured_products->product1->product_id != 0 ){
			$i=1;
			$featured_product = $this->featured_products->product1;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_featured_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
		}

		if( isset( $this->featured_products->product2 ) && $this->featured_products->product2->product_id != 0 ){
			$i=2;
			$featured_product = $this->featured_products->product2;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_featured_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
		}

		if( isset( $this->featured_products->product3 ) && $this->featured_products->product3->product_id != 0 ){
			$i=3;
			$featured_product = $this->featured_products->product3;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_featured_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
		}

		if( isset( $this->featured_products->product4 ) && $this->featured_products->product4->product_id != 0 ){
			$i=4;
			$featured_product = $this->featured_products->product4;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_featured_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout') . '/ec_featured_product.php' );
		}
	}

	/* Display the Gift Card Input Fields */
	public function display_gift_card_input( ){
		if( $this->is_giftcard ){
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_gift_card_input.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_gift_card_input.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_gift_card_input.php' );	
		}
	}

	public function display_gift_card_message_input_field( ){
		echo "<textarea name=\"ec_gift_card_message\" id=\"ec_gift_card_message_" . esc_attr( $this->model_number ) . "\" class=\"ec_gift_card_message\"></textarea>";
	}

	public function display_gift_card_to_name_input_field( ){
		echo "<input type=\"text\" name=\"ec_gift_card_to_name\" id=\"ec_gift_card_to_name_" . esc_attr( $this->model_number ) . "\" class=\"ec_gift_card_to_name\" />";
	}

	public function display_gift_card_from_name_input_field( ){
		echo "<input type=\"text\" name=\"ec_gift_card_from_name\" id=\"ec_gift_card_from_name_" . esc_attr( $this->model_number ) . "\" class=\"ec_gift_card_from_name\" />";
	}

	/* Price Tier Functions */
	public function get_price_tier_quantity_string( ){
								$ret_string = "";
		for( $i=0; $i<count( $this->pricetiers ); $i++ ){
			if( $i > 0 )		$ret_string .= ",";
			if( count( $this->pricetiers[$i] ) > 1 )
								$ret_string .= $this->pricetiers[$i][1];
		}
								return $ret_string;
	}

	public function get_price_tier_price_string( ){
								$ret_string = "";
		for( $i=0; $i<count( $this->pricetiers ); $i++ ){
			if( $i>0 )			$ret_string .= ",";
								$ret_string .= $this->pricetiers[$i][0];
		}
								return $ret_string;
	}

	public function display_product_price_tiers( ){
		for( $i=0; $i<count( $this->pricetiers ) && count( $this->pricetiers[$i] ) == 2; $i++ ){
			$Price = $GLOBALS['currency']->get_currency_display( $this->pricetiers[$i][0] );
			$Quantity = $this->pricetiers[$i][1];
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_price_tier.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_price_tier.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product_price_tier.php' );	
		}
	}

	public function display_product_custom_fields( $divider, $spacer ){
		for( $i=0; $i<count( $this->customfields ) && count( $this->customfields[$i] ) == 3; $i++ ){
			$field_name = $this->customfields[$i][0];
			$field_label = $this->customfields[$i][1];
			$field_data = $this->customfields[$i][2];

			echo esc_attr( $field_label . $divider . " " . $field_data . $spacer );
		}
	}

	public function display_product_custom_field_label( $field_name_input ){
		for( $i=0; $i<count( $this->customfields ) && count( $this->customfields[$i] ) == 3; $i++ ){
			$field_name = $this->customfields[$i][0];
			if( $field_name_input == $field_name ){
				$field_label = $this->customfields[$i][1];
				echo esc_attr( $field_label );
			}
		}
	}

	public function display_product_custom_field_data( $field_name_input ){
		for( $i=0; $i<count( $this->customfields ) && count( $this->customfields[$i] ) == 3; $i++ ){
			$field_name = $this->customfields[$i][0];
			if( $field_name_input == $field_name ){
				$field_data = $this->customfields[$i][2];
				echo esc_attr( $field_data );
			}
		}
	}

	public function display_model_number( ){
		echo esc_attr( $this->model_number );	
	}

	/* Return the product product_id */
	public function display_product_product_id( ){
		return $this->product_id;
	}

	public function get_additional_link_options( ){

		global $wp_query;
		$post_obj = $wp_query->get_queried_object();
		if( isset( $post_obj ) && isset( $post_obj->ID ) )
			$post_id = $post_obj->ID;
		else
			$post_id = 0;
		$menulevel1 = $GLOBALS['ec_menu']->get_menu_row_from_post_id( $post_id, 1 );
		$menulevel2 = $GLOBALS['ec_menu']->get_menu_row_from_post_id( $post_id, 2 );
		$menulevel3 = $GLOBALS['ec_menu']->get_menu_row_from_post_id( $post_id, 3 );
		$product = $GLOBALS['ec_products']->get_product_from_post_id( $post_id );

		$link_text = "";

		if( !$this->is_widget ){
			if( isset( $_GET['subsubmenuid'] ) ){
				$link_text .= "&amp;subsubmenuid=" . (int) $_GET['subsubmenuid'];

				if( isset( $_GET['subsubmenu'] ) )
					$link_text .= "&amp;subsubmenu=" . (int) $_GET['subsubmenu'];

				if( isset( $_GET['pagenum'] ) )
					$link_text .= "&amp;pagenum=" . (int) $_GET['pagenum'];

			}else if( $menulevel3 ){
				$link_text .= "&amp;subsubmenuid=" . $menulevel3->menulevel3_id;
				if( isset( $_GET['pagenum'] ) )
					$link_text .= "&amp;pagenum=" . (int) $_GET['pagenum'];
			}else if( isset( $_GET['submenuid'] ) ){
				$link_text .= "&amp;submenuid=" . (int) $_GET['submenuid'];

				if( isset( $_GET['submenu'] ) )
					$link_text .= "&amp;submenu=" . (int) $_GET['submenu'];

				if( isset( $_GET['pagenum'] ) )
					$link_text .= "&amp;pagenum=" . (int) $_GET['pagenum'];

			}else if( $menulevel2 ){
				$link_text .= "&amp;submenuid=" . $menulevel2->menulevel2_id;
				if( isset( $_GET['pagenum'] ) )
					$link_text .= "&amp;pagenum=" . (int) $_GET['pagenum'];
			}else if( $menulevel1 ){
				$link_text .= "&amp;menuid=" . $menulevel1->menulevel1_id;
				if( isset( $_GET['pagenum'] ) )
					$link_text .= "&amp;pagenum=" . (int) $_GET['pagenum'];
			}else if( !isset( $_GET['manufacturer'] ) && !isset( $_GET['group_id'] ) && $this->show_on_startup ){
				if( isset( $_GET['pagenum'] ) )
					$link_text .= "&amp;pagenum=" . (int) $_GET['pagenum'];
			}

			if( isset( $_GET['manufacturer'] ) ){
				$link_text .= "&amp;manufacturer=" . (int) $_GET['manufacturer'];	
			}

			if( isset( $_GET['group_id'] ) ){
				$link_text .= "&amp;group_id=" . (int) $_GET['group_id'];	
			}

			if( isset( $_GET['pricepoint'] ) ){
				$link_text .= "&amp;pricepoint=" . (int) $_GET['pricepoint'];	
			}

		}

		return $link_text;

	}

	public function get_product_unsubscribe_link( $email, $product_subscriber_id ){
		$url = $this->ec_get_permalink( $this->post_id );
		if( strstr( $url, '?' ) ){
			$url .= '&ec_action=product-notify-unsubscribe&unsubscribe_email=' . $email . '&unsubscribe_id=' . $product_subscriber_id;
		}else{
			$url .= '?ec_action=product-notify-unsubscribe&unsubscribe_email=' . $email . '&unsubscribe_id=' . $product_subscriber_id;
		}
		return $url;
	}

	public function get_product_link( ){
		return $this->ec_get_permalink( $this->post_id );
	}

	public function get_product_single_image( ){
		$thumb = "";
		if( $this->use_optionitem_images ){
			if( substr( $this->images->imageset[0]->image1, 0, 7 ) == 'http://' || substr( $this->images->imageset[0]->image1, 0, 8 ) == 'https://' ){
				$thumb = $this->images->imageset[0]->image1;
			}else if( file_exists( plugins_url( "wp-easycart-data/products/pics1/" . $this->images->imageset[0]->image1, EC_PLUGIN_DATA_DIRECTORY ) ) ){
				$thumb = plugins_url( "wp-easycart-data/products/pics1/" . $this->images->imageset[0]->image1, EC_PLUGIN_DATA_DIRECTORY );
			}else{
				$thumb = plugins_url( "wp-easycart/products/pics1/" . $this->images->imageset[0]->image1, EC_PLUGIN_DIRECTORY );
			}
		}else{
			if( substr( $this->images->image1, 0, 7 ) == 'http://' || substr( $this->images->image1, 0, 8 ) == 'https://' ){
				$thumb = $this->images->image1;
			}else if( file_exists( plugins_url( "wp-easycart-data/products/pics1/" . $this->images->image1, EC_PLUGIN_DATA_DIRECTORY ) ) ){
				$thumb = plugins_url( "wp-easycart-data/products/pics1/" . $this->images->image1, EC_PLUGIN_DATA_DIRECTORY );
			}else if( !file_exists( $thumb ) ){
				$thumb = plugins_url( "wp-easycart/products/pics1/" . $this->images->image1, EC_PLUGIN_DIRECTORY );
			}
		}
		return $thumb;
	}

	public function has_sale_price( ){
		if( $this->list_price == "0.000" ){
			return false;
		}else{
			return true;
		}
	}

	public function get_formatted_before_price( ){
		return $GLOBALS['currency']->get_currency_display( $this->list_price );
	}

	public function get_formatted_price( ){
		return $GLOBALS['currency']->get_currency_display( $this->price );
	}

	private function ec_get_permalink( $postid ){

		if( !get_option( 'ec_option_use_old_linking_style' ) && $this->guid != "" ){
			return get_permalink( $postid );//return $this->guid;
		}else{
			return $this->store_page . $this->permalink_divider . "model_number=" . $this->model_number;
		}

	}

	public function in_stock( ){

		if( ( !$this->show_stock_quantity && !$this->use_optionitem_quantity_tracking ) || $this->stock_quantity > 0 ){
			return true;
		}else{
			return false;
		}

	}

	public function has_options( ){

		if ( $this->has_options || ( ( $this->use_advanced_optionset || $this->use_both_option_types ) && count( $this->advanced_optionsets ) > 0 ) ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_add_to_cart_link( ){
		return $this->cart_page . $this->permalink_divider . "ec_action=addtocart&model_number=" . $this->model_number;
	}

	public function get_subscription_link( ){
		return $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $this->model_number;
	}

	public function get_advanced_optionitems( $option_id ){
		$optionitems = $GLOBALS['ec_options']->get_optionitems( $option_id );
		for( $opt_index = 0; $opt_index < count( $optionitems ); $opt_index++ ){
			$optionitems[$opt_index]->optionitem_name = wp_easycart_language( )->convert_text( $optionitems[$opt_index]->optionitem_name );
		}
		return $optionitems;
	}

	public function get_deconetwork_link( ){

		 $link = "https://" . get_option( 'ec_option_deconetwork_url' ) . "/external/load_resource?mode=" . $this->deconetwork_mode . "&product=" . $this->deconetwork_product_id . "&";
		if( $this->deconetwork_size_id != "" ){
			$link .= "size=" . $this->deconetwork_size_id . "&";
		}
		if( $this->deconetwork_color_id != "" ){
			$link .= "color=" . $this->deconetwork_color_id . "&";
		}
		if( $this->deconetwork_design_id != "" ){
			$link .= "design=" . $this->deconetwork_design_id . "&";
		}
		$link .= "callback_add_url=" . $this->cart_page . "&callback_cancel_url=" . $this->cart_page . "&callback_param_ec_action=deconetwork_add_to_cart&oid=" . $GLOBALS['ec_cart_data']->ec_cart_id . "&callback_param_ec_product_id=" . $this->product_id;
		return $link;

	}

	public function get_manufacturer_link( ){

		$manufacturer_row = $this->mysqli->get_manufacturer_row( $this->manufacturer_id );
		if( !get_option( 'ec_option_use_old_linking_style' ) && $manufacturer_row && $manufacturer_row->post_id ){
			return get_permalink( $manufacturer_row->post_id );
		}else{
			return $this->store_page . $this->permalink_divider . "manufacturer=" . $this->manufacturer_id;
		}

	}

	public function get_category_link( $post_id, $category_id ){

		if( !get_option( 'ec_option_use_old_linking_style' ) && $post_id ){
			return get_permalink( $post_id );
		}else{
			return $this->store_page . $this->permalink_divider . "group_id=" . $category_id;
		}

	}

	public function get_social_image() {
		$return_image = '';
		if ( $this->use_optionitem_images ) {
			$first_image_found = false;
			$first_optionitem_id = false;
			if ( $this->use_advanced_optionset ) {
				if( count( $this->advanced_optionsets ) > 0 ) {
					$valid_optionset = false;
					foreach ( $this->advanced_optionsets as $adv_optionset ) {
						if ( ! $valid_optionset && ( 'combo' == $adv_optionset->option_type || 'swatch' == $adv_optionset->option_type || 'radio' == $adv_optionset->option_type ) ) {
							$valid_optionset = $adv_optionset;
						}
					}
					if ( $valid_optionset ) {
						$optionitems = $this->get_advanced_optionitems( $valid_optionset->option_id );
						if ( count( $optionitems ) > 0 ) {
							$first_optionitem_id = $optionitems[0]->optionitem_id;
						}
					}
				}
			} else {
				if ( count( $this->options->optionset1->optionset ) > 0 ) {
					$first_optionitem_id = $this->options->optionset1->optionset[0]->optionitem_id;
				}
			}
			if ( $first_optionitem_id ) {
				for ( $i = 0; $i < count( $this->images->imageset ); $i++ ) {
					if ( ! $first_image_found && (int) $this->images->imageset[$i]->optionitem_id == (int) $first_optionitem_id ) {
						if ( count( $this->images->imageset[$i]->product_images ) > 0 ) {
							if ( 'video:' == substr( $this->images->imageset[$i]->product_images[0], 0, 6 ) ) {
								$video_str = substr( $this->images->imageset[$i]->product_images[0], 6, strlen( $this->images->imageset[$i]->product_images[0] ) - 6 );
								$video_arr = explode( ':::', $video_str );
								if ( count( $video_arr ) >= 2 ) {
									$return_image = esc_attr( $video_arr[1] );
									$first_image_found = true;
								}
							} else if( 'youtube:' == substr( $this->images->imageset[$i]->product_images[0], 0, 8 ) ) {
								$youtube_video_str = substr( $this->images->imageset[$i]->product_images[0], 8, strlen( $this->images->imageset[$i]->product_images[0] ) - 8 );
								$youtube_video_arr = explode( ':::', $youtube_video_str );
								if ( count( $youtube_video_arr ) >= 2 ) {
									$return_image = esc_attr( $youtube_video_arr[1] );
									$first_image_found = true;
								}
							} else if( 'vimeo:' == substr( $this->images->imageset[$i]->product_images[0], 0, 6 ) ) {
								$vimeo_video_str = substr( $this->images->imageset[$i]->product_images[0], 6, strlen( $this->images->imageset[$i]->product_images[0] ) - 6 );
								$vimeo_video_arr = explode( ':::', $vimeo_video_str );
								if ( count( $vimeo_video_arr ) >= 2 ) {
									$return_image = esc_attr( $vimeo_video_arr[1] );
									$first_image_found = true;
								}
							} else { 
								if ( 'image1' == $this->images->imageset[$i]->product_images[0] ) {
									$return_image = esc_attr( $this->get_first_image_url( ) );
								} else if( 'image2' == $this->images->imageset[$i]->product_images[0] ) {
									$return_image = esc_attr( $this->get_second_image_url( ) );
								} else if( 'image3' == $this->images->imageset[$i]->product_images[0] ) {
									$return_image = esc_attr( $this->get_third_image_url( ) );
								} else if( 'image4' == $this->images->imageset[$i]->product_images[0] ) {
									$return_image = esc_attr( $this->get_fourth_image_url( ) );
								} else if( 'image5' == $this->images->imageset[$i]->product_images[0] ) {
									$return_image = esc_attr( $this->get_fifth_image_url( ) );
								} else if( 'image:' == substr( $this->images->imageset[$i]->product_images[0], 0, 6 ) ) {
									$return_image = esc_attr( substr( $this->images->imageset[$i]->product_images[0], 6, strlen( $this->images->imageset[$i]->product_images[0] ) - 6 ) );
								} else {
									$product_image_media = wp_get_attachment_image_src( $this->images->imageset[$i]->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
									if ( $product_image_media && isset( $product_image_media[0] ) ) {
										$return_image = esc_attr( $product_image_media[0] );
									}
								}
								$first_image_found = true;
							}
						} else {
							$return_image = esc_attr( $this->get_first_image_url() );
						}
					}
				}
			}
		} else { // Close check for option item images
			if ( count( $this->images->product_images ) > 0  && 'video:' == substr( $this->images->product_images[0], 0, 6 ) ) {
				$video_str = substr( $this->images->product_images[0], 6, strlen( $this->images->product_images[0] ) - 6 );
				$video_arr = explode( ':::', $video_str );
				if ( count( $video_arr ) >= 2 ) {
					$return_image = esc_attr( $video_arr[1] );
				}
			} else if ( count( $this->images->product_images ) > 0  && 'youtube:' == substr( $this->images->product_images[0], 0, 8 ) ) {
				$youtube_video_str = substr( $this->images->product_images[0], 8, strlen( $this->images->product_images[0] ) - 8 );
				$youtube_video_arr = explode( ':::', $youtube_video_str );
				if ( count( $youtube_video_arr ) >= 2 ) {
					$return_image = esc_attr( $youtube_video_arr[1] );
				}
			} else if( count( $this->images->product_images ) > 0  && 'vimeo:' == substr( $this->images->product_images[0], 0, 6 ) ) {
				$vimeo_video_str = substr( $this->images->product_images[0], 6, strlen( $this->images->product_images[0] ) - 6 );
				$vimeo_video_arr = explode( ':::', $vimeo_video_str );
				if ( count( $vimeo_video_arr ) >= 2 ) {
					$return_image = esc_attr( $vimeo_video_arr[1] );
				}
			} else {
				if ( count( $this->images->product_images ) > 0 ) { 
					if ( 'image1' == $this->images->product_images[0] ) {
						$return_image = esc_attr( $this->get_first_image_url( ) );
					} else if( 'image2' == $this->images->product_images[0] ) {
						$return_image = esc_attr( $this->get_second_image_url( ) );
					} else if( 'image3' == $this->images->product_images[0] ) {
						$return_image = esc_attr( $this->get_third_image_url( ) );
					} else if( 'image4' == $this->images->product_images[0] ) {
						$return_image = esc_attr( $this->get_fourth_image_url( ) );
					} else if( 'image5' == $this->images->product_images[0] ) {
						$return_image = esc_attr( $this->get_fifth_image_url( ) );
					} else if( 'image:' == substr( $this->images->product_images[0], 0, 6 ) ) {
						$return_image = esc_attr( substr( $this->images->product_images[0], 6, strlen( $this->images->product_images[0] ) - 6 ) );
					} else {
						$product_image_media = wp_get_attachment_image_src( $this->images->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
						if( $product_image_media && isset( $product_image_media[0] ) ) {
							$return_image = esc_attr( $product_image_media[0] );
						}
					}
				} else { 
					$return_image = esc_attr( $this->get_first_image_url( ) );
				}
			} // close check for video
		}
		return $return_image;
	}

	public function get_first_image_url( ){

		$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->images->get_single_image( );
		$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

		if ( substr( $this->images->image1, 0, 7 ) == 'http://' || substr( $this->images->image1, 0, 8 ) == 'https://' ) {
			return $this->images->image1;

		} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
			return plugins_url( "/wp-easycart-data/products/pics1/" . $this->images->get_single_image( ), EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( $test_src2 ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

		}
	}

	public function get_second_image_url( ){

		$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/pics2/" . $this->images->image2;
		$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

		if ( substr( $this->images->image2, 0, 7 ) == 'http://' || substr( $this->images->image2, 0, 8 ) == 'https://' ) {
			return $this->images->image2;

		} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
			return plugins_url( "/wp-easycart-data/products/pics2/" . $this->images->image2, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( $test_src2 ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

		}

	}

	public function get_third_image_url( ){

		$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/pics3/" . $this->images->image3;
		$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

		if ( substr( $this->images->image3, 0, 7 ) == 'http://' || substr( $this->images->image3, 0, 8 ) == 'https://' ) {
			return $this->images->image3;

		} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
			return plugins_url( "/wp-easycart-data/products/pics3/" . $this->images->image3, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( $test_src2 ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

		}

	}

	public function get_fourth_image_url( ){

		$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/pics4/" . $this->images->image4;
		$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

		if ( substr( $this->images->image4, 0, 7 ) == 'http://' || substr( $this->images->image4, 0, 8 ) == 'https://' ) {
			return $this->images->image4;

		} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
			return plugins_url( "/wp-easycart-data/products/pics4/" . $this->images->image4, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( $test_src2 ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );

		}
	}

	public function get_fifth_image_url() {
		$test_src = EC_PLUGIN_DATA_DIRECTORY . "/products/pics5/" . $this->images->image5;
		$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

		if ( substr( $this->images->image5, 0, 7 ) == 'http://' || substr( $this->images->image5, 0, 8 ) == 'https://' ) {
			return $this->images->image5;

		} else if ( file_exists( $test_src ) && !is_dir( $test_src ) ) {
			return plugins_url( "/wp-easycart-data/products/pics5/" . $this->images->image5, EC_PLUGIN_DATA_DIRECTORY );

		} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
			return get_option( 'ec_option_product_image_default' );

		} else if ( file_exists( $test_src2 ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );
		}
	}

	public function is_login_for_pricing_valid() {
		if ( $GLOBALS['ec_user']->user_id == 0 ){
			return false;
		}

		if ( ! isset( $this->login_for_pricing_user_level ) ) {
			return true;
		}

		if ( ! is_array( $this->login_for_pricing_user_level ) ) {
			return true;
		}

		if ( count( $this->login_for_pricing_user_level ) == 0 ) {
			return true;
		}

		if ( in_array( '0', $this->login_for_pricing_user_level ) ) {
			return true;
		}

		if ( in_array( $GLOBALS['ec_user']->user_level, $this->login_for_pricing_user_level ) ) {
			return true;
		}

		return false;

	}

	public function is_option_initially_visible( $optionset ) {
		$is_visible = true;
		$rules = array();
		foreach ( $this->advanced_optionsets as $advanced_option ) {
			if ( isset( $advanced_option->conditional_logic ) ) {
				$rules[ $advanced_option->option_to_product_id ] = json_decode( $advanced_option->conditional_logic );
			}
		}
		if ( count( $rules ) > 0 ) {
			foreach ( $rules as $key => $option_rules ) {
				if ( is_object( $option_rules ) && isset( $option_rules->enabled ) && $option_rules->enabled && isset( $option_rules->rules ) && is_array( $option_rules->rules ) && count( $option_rules->rules ) > 0 ) {
					if ( $key == $optionset->option_to_product_id ) {
						$is_visible = ( 'AND' == $option_rules->and_rules ) ? true : false;
						$has_valid_rules = false;
						foreach ( $option_rules->rules as $rule ) {
							if ( $rule->option_id != 0 ) {
								foreach ( $this->advanced_optionsets as $advanced_option ) {
									if ( $advanced_option->option_to_product_id == $rule->option_id ) {
										$optionitems = $this->get_advanced_optionitems( $advanced_option->option_id );
										foreach ( $optionitems as $optionitem ) {
											if ( 'checkbox' == $advanced_option->option_type || 'radio' == $advanced_option->option_type || 'swatch' == $advanced_option->option_type || 'combo' == $advanced_option->option_type ) {
												// LOGIC NOT QUITE RIGHT!
												if ( $optionitem->optionitem_id == $rule->optionitem_id ) {
													$has_valid_rules = true;
													if ( $rule->operator == '=' && ! $optionitem->optionitem_initially_selected && 'AND' == $option_rules->and_rules ) {
														$is_visible = false;
													} else if ( $rule->operator == '!=' && $optionitem->optionitem_initially_selected && 'AND' == $option_rules->and_rules ) {
														$is_visible = false;
													} else if ( $rule->operator == '=' && $optionitem->optionitem_initially_selected && 'OR' == $option_rules->and_rules ) {
														$is_visible = true;
													} else if ( $rule->operator == '!=' && ! $optionitem->optionitem_initially_selected && 'OR' == $option_rules->and_rules ) {
														$is_visible = true;
													}
												}
											} else {
												$has_valid_rules = true;
												if ( $rule->operator == '=' && $rule->optionitem_value != $optionitem->optionitem_initial_value && 'AND' == $option_rules->and_rules ) {
													$is_visible = false;
												} else if ( $rule->operator == '!='&& $rule->optionitem_value == $optionitem->optionitem_initial_value && 'AND' == $option_rules->and_rules ) {
													$is_visible = false;
												} else if ( $rule->operator == '=' && $rule->optionitem_value == $optionitem->optionitem_initial_value && 'OR' == $option_rules->and_rules ) {
													$is_visible = true;
												} else if ( $rule->operator == '!='&& $rule->optionitem_value != $optionitem->optionitem_initial_value && 'OR' == $option_rules->and_rules ) {
													$is_visible = true;
												}
											}
										}
									}
								}
							}
						}
						if ( ! $has_valid_rules ) {
							$is_visible = true;
						} else if ( ! $option_rules->show_field ) {
							$is_visible = ! $is_visible;
						}
					}
				}
			}
		}
		return $is_visible;
	}
}
