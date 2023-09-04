<?php

class ec_db{
	
	protected static $mysqli;  // holds your mysqli connection
	protected static $orderdetail_sql;
	protected static $orderdetail_guest_sql;
	
	function __construct(){
		global $wpdb;
		self::$mysqli =& $wpdb;
		
		self::$orderdetail_sql = "SELECT 
				ec_orderdetail.orderdetail_id, 
				ec_orderdetail.order_id, 
				ec_orderdetail.product_id, 
				ec_product.list_id, 
				ec_manufacturer.`name` as manufacturer_name,
				ec_orderdetail.title, 
				ec_orderdetail.model_number, 
				ec_orderdetail.order_date, 
				ec_orderdetail.unit_price, 
				ec_orderdetail.total_price, 
				ec_orderdetail.quantity, 
				ec_orderdetail.image1, 
				ec_orderdetail.optionitem_name_1, 
				ec_orderdetail.optionitem_name_2, 
				ec_orderdetail.optionitem_name_3, 
				ec_orderdetail.optionitem_name_4, 
				ec_orderdetail.optionitem_name_5,
				ec_orderdetail.optionitem_label_1, 
				ec_orderdetail.optionitem_label_2, 
				ec_orderdetail.optionitem_label_3, 
				ec_orderdetail.optionitem_label_4, 
				ec_orderdetail.optionitem_label_5,
				ec_orderdetail.optionitem_price_1, 
				ec_orderdetail.optionitem_price_2, 
				ec_orderdetail.optionitem_price_3, 
				ec_orderdetail.optionitem_price_4, 
				ec_orderdetail.optionitem_price_5,
				ec_orderdetail.use_advanced_optionset,
				ec_orderdetail.use_both_option_types,
				ec_orderdetail.giftcard_id, 
				ec_orderdetail.gift_card_message, 
				ec_orderdetail.gift_card_from_name, 
				ec_orderdetail.gift_card_to_name,
				ec_orderdetail.gift_card_email,
				ec_orderdetail.is_download, 
				ec_orderdetail.is_giftcard, 
				ec_orderdetail.is_taxable,
				ec_orderdetail.is_shippable, 
				ec_download.download_file_name, 
				ec_orderdetail.download_key,
				ec_orderdetail.maximum_downloads_allowed,
				ec_orderdetail.download_timelimit_seconds,
				ec_download.is_amazon_download,
				ec_download.amazon_key,
				
				ec_orderdetail.include_code,
				ec_orderdetail.subscription_signup_fee,
				
				ec_orderdetail.is_deconetwork,
				ec_orderdetail.deconetwork_id,
				ec_orderdetail.deconetwork_name,
				ec_orderdetail.deconetwork_product_code,
				ec_orderdetail.deconetwork_options,
				ec_orderdetail.deconetwork_color_code,
				ec_orderdetail.deconetwork_product_id,
				ec_orderdetail.deconetwork_image_link,
				
				";
		
		if( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ){
			for( $i=0; $i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ); $i++ ){
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array( ), array( ) );
				for( $j=0; $j<count( $arr ); $j++ ){
					self::$orderdetail_sql .= "ec_orderdetail." . $arr[$j] . ", ";
				}
			}
		}
			
		self::$orderdetail_sql .=	"
				
				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
				
				FROM ec_orderdetail
				
				LEFT JOIN ec_product
				ON ec_product.product_id = ec_orderdetail.product_id
				
				LEFT JOIN ec_manufacturer
				ON ec_product.manufacturer_id = ec_manufacturer.manufacturer_id
				
				LEFT JOIN ec_download
				ON ec_download.download_id = ec_orderdetail.download_key
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_orderdetail'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_orderdetail.orderdetail_id, 
				
				ec_order, ec_user
				
				WHERE 
				ec_user.user_id = %s AND
				ec_order.order_id = ec_orderdetail.order_id AND 
				ec_user.user_id = ec_order.user_id AND 
				ec_orderdetail.order_id = %d
				
				GROUP BY
				ec_orderdetail.orderdetail_id";
				
		self::$orderdetail_guest_sql = "SELECT 
				ec_orderdetail.orderdetail_id, 
				ec_orderdetail.order_id, 
				ec_orderdetail.product_id,
				ec_product.list_id,
				ec_manufacturer.`name` as manufacturer_name,
				ec_orderdetail.title, 
				ec_orderdetail.model_number, 
				ec_orderdetail.order_date, 
				ec_orderdetail.unit_price, 
				ec_orderdetail.total_price, 
				ec_orderdetail.quantity, 
				ec_orderdetail.image1, 
				ec_orderdetail.optionitem_name_1, 
				ec_orderdetail.optionitem_name_2, 
				ec_orderdetail.optionitem_name_3, 
				ec_orderdetail.optionitem_name_4, 
				ec_orderdetail.optionitem_name_5,
				ec_orderdetail.optionitem_label_1, 
				ec_orderdetail.optionitem_label_2, 
				ec_orderdetail.optionitem_label_3, 
				ec_orderdetail.optionitem_label_4, 
				ec_orderdetail.optionitem_label_5,
				ec_orderdetail.optionitem_price_1, 
				ec_orderdetail.optionitem_price_2, 
				ec_orderdetail.optionitem_price_3, 
				ec_orderdetail.optionitem_price_4, 
				ec_orderdetail.optionitem_price_5,
				ec_orderdetail.use_advanced_optionset,
				ec_orderdetail.use_both_option_types,
				ec_orderdetail.giftcard_id, 
				ec_orderdetail.gift_card_message, 
				ec_orderdetail.gift_card_from_name, 
				ec_orderdetail.gift_card_to_name,
				ec_orderdetail.gift_card_email,
				ec_orderdetail.is_download, 
				ec_orderdetail.is_giftcard, 
				ec_orderdetail.is_taxable, 
				ec_orderdetail.is_shippable, 
				ec_download.download_file_name, 
				ec_orderdetail.download_key,
				ec_orderdetail.maximum_downloads_allowed,
				ec_orderdetail.download_timelimit_seconds,
				ec_download.is_amazon_download,
				ec_download.amazon_key,
				
				ec_orderdetail.is_deconetwork,
				ec_orderdetail.deconetwork_id,
				ec_orderdetail.deconetwork_name,
				ec_orderdetail.deconetwork_product_code,
				ec_orderdetail.deconetwork_options,
				ec_orderdetail.deconetwork_color_code,
				ec_orderdetail.deconetwork_product_id,
				ec_orderdetail.deconetwork_image_link,
				
				ec_orderdetail.include_code,
				ec_orderdetail.subscription_signup_fee,
				
				";
		
		if( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ){
			for( $i=0; $i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ); $i++ ){
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array( ), array( ) );
				for( $j=0; $j<count( $arr ); $j++ ){
					self::$orderdetail_guest_sql .= "ec_orderdetail." . $arr[$j] . ", ";
				}
			}
		}
				
		self::$orderdetail_guest_sql .=	"
				
				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
				
				FROM ec_orderdetail
				
				LEFT JOIN ec_product
				ON ec_product.product_id = ec_orderdetail.product_id
				
				LEFT JOIN ec_manufacturer
				ON ec_product.manufacturer_id = ec_manufacturer.manufacturer_id
				
				LEFT JOIN ec_download
				ON ec_download.download_id = ec_orderdetail.download_key
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_orderdetail'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_orderdetail.orderdetail_id, 
				
				ec_order 
				
				WHERE 
				ec_order.order_id = ec_orderdetail.order_id AND 
				ec_order.guest_key = %s AND
				ec_order.guest_key != '' AND
				ec_orderdetail.order_id = %d
				
				GROUP BY
				ec_orderdetail.orderdetail_id";
	}
	
	public static function get_breadcrumb_data( $model_number, $menuid, $submenuid, $subsubmenuid ){
		$sql_menu0 = self::$mysqli->prepare( "SELECT ec_product.title, ec_product.model_number FROM ec_product WHERE ec_product.model_number = '%s'", $model_number );
		$sql_menu1 = self::$mysqli->prepare( "SELECT ec_product.title, ec_product.model_number, ec_menulevel1.menulevel1_id, ec_menulevel1.name as menulevel1_name FROM ec_product, ec_menulevel1 WHERE ec_product.model_number = '%s' AND ec_menulevel1.menulevel1_id = %d", $model_number, $menuid );
		$sql_menu2 = self::$mysqli->prepare( "SELECT ec_product.title, ec_product.model_number, ec_menulevel1.menulevel1_id, ec_menulevel2.menulevel2_id, ec_menulevel1.name as menulevel1_name, ec_menulevel2.name as menulevel2_name FROM ec_product, ec_menulevel2 LEFT JOIN ec_menulevel1 ON ec_menulevel1.menulevel1_id = ec_menulevel2.menulevel1_id WHERE ec_product.model_number = '%s' AND ec_menulevel2.menulevel2_id = %d", $model_number, $submenuid );
		$sql_menu3 = self::$mysqli->prepare( "SELECT ec_product.title, ec_product.model_number, ec_menulevel1.menulevel1_id, ec_menulevel2.menulevel2_id, ec_menulevel3.menulevel3_id, ec_menulevel1.name as menulevel1_name, ec_menulevel2.name as menulevel2_name, ec_menulevel3.name as menulevel3_name FROM ec_product, ec_menulevel3 LEFT JOIN ec_menulevel2 ON ec_menulevel2.menulevel2_id = ec_menulevel3.menulevel2_id LEFT JOIN ec_menulevel1 ON ec_menulevel1.menulevel1_id = ec_menulevel2.menulevel1_id WHERE ec_product.model_number = '%s' AND ec_menulevel3.menulevel3_id = %d", $model_number, $subsubmenuid );
		
		if( $subsubmenuid != 0 )
			$sql = $sql_menu3;
		else if( $submenuid != 0 )
			$sql = $sql_menu2;
		else if( $menuid != 0 )
			$sql = $sql_menu1;
		else
			$sql = $sql_menu0;
		
		return self::$mysqli->get_row( $sql );	
	}
	
	public static function get_review_list( ){
		$sql = "SELECT
				ec_review.product_id,
				ec_review.rating, 
				ec_review.title,
				ec_review.description,
				ec_review.date_submitted,
                ec_review.reviewer_name,
				ec_user.first_name,
				ec_user.last_name
				
				FROM ec_review
				
				LEFT JOIN ec_user ON ec_review.user_id = ec_user.user_id
				
				WHERE ec_review.approved = 1
				
				ORDER BY ec_review.date_submitted DESC";
				
		return self::$mysqli->get_results( $sql );
	}
	
	public static function get_pricetier_list( ){
		$pricetiers = wp_cache_get( 'wpeasycart-pricetiers' );
		if( !$pricetiers ){
			$sql = "SELECT
					ec_pricetier.product_id,
					ec_pricetier.price,
					ec_pricetier.quantity
					
					FROM ec_pricetier
					
					ORDER BY ec_pricetier.quantity ASC";
			
			$pricetiers = self::$mysqli->get_results( $sql );
			if( count( $pricetiers ) == 0 )
				$pricetiers = "EMPTY";
			wp_cache_set( 'wpeasycart-pricetiers', $pricetiers );
		}
		if( $pricetiers == "EMPTY" )
			return array( );
		return $pricetiers;
	}
	
	public static function get_option_list( ){
		$sql = "SELECT
				ec_option.option_id,
				ec_option.option_name,
				ec_option.option_label
				
				FROM ec_option
				
				ORDER BY 
				ec_option.option_id";
				
		return self::$mysqli->get_results( $sql );
	}
	
	public static function get_optionitem_list( ){
		$sql = "SELECT 
				optionitem.option_id,
				optionitem.optionitem_id, 
				optionitem.optionitem_name, 
				optionitem.optionitem_price, 
				optionitem.optionitem_price_onetime,
				optionitem.optionitem_price_override,
				optionitem.optionitem_price_multiplier,
				optionitem.optionitem_price_per_character,
				optionitem.optionitem_weight, 
				optionitem.optionitem_weight_onetime,
				optionitem.optionitem_weight_override,
				optionitem.optionitem_weight_multiplier,
				optionitem.optionitem_icon,
				optionitem.optionitem_initially_selected,
				
				ec_option.option_label,
				ec_option.option_name
				
				FROM ec_optionitem as optionitem
				LEFT JOIN ec_option ON ( ec_option.option_id = optionitem.option_id )
				
				ORDER BY
				optionitem.option_id, 
				optionitem.optionitem_order";
				
		return self::$mysqli->get_results( $sql );
	}
	
	public static function get_optionitem_image_list( ){
		$sql = "SELECT 
				optionitemimage.optionitemimage_id,
				optionitemimage.optionitem_id, 
				optionitemimage.product_id, 
				optionitemimage.image1, 
				optionitemimage.image2, 
				optionitemimage.image3, 
				optionitemimage.image4, 
				optionitemimage.image5,
				optionitem.optionitem_order
				
				FROM ec_optionitemimage as optionitemimage, ec_optionitem as optionitem

				WHERE optionitem.optionitem_id = optionitemimage.optionitem_id
				
                GROUP BY optionitemimage.optionitemimage_id
				
				ORDER BY
				optionitemimage.product_id,
				optionitem.optionitem_order";
				
		return self::$mysqli->get_results( $sql );
	}
	
	// COMING IN FUTURE VERSION, WILL SPEED UP PRODUCT QUERY //
	public static function get_products_by_filter( $filter ){
		
		$sql = $filter->get_select( );
		$sql .= $filter->get_joins( );
		$sql .= $filter->get_where( );
		$sql .= $filter->get_order( );
		$sql .= $filter->get_limit( );
		
		$product_list = self::$mysqli->get_results( $sql );
		$product_count = self::$mysqli->get_var( "SELECT FOUND_ROWS()" );
		
		return (object) array( "productlist" => $product_list, "product_count" => $product_count );
		
	}
	
	public static function get_product_list( $where_query, $order_query, $limit_query, $session_id, $cache_key = "", $optionitem_filter = '', $extra_joins = '' ){
		
		if( $cache_key != "" ){ // Return a cached list if possible
		
			$cached_list = wp_cache_get( $cache_key, 'wpeasycart-product-list' );
			if( $cached_list )
				return $cached_list;
			
		}
		
		$has_search = false;
		if( isset( $_GET['ec_search'] ) ){
			$has_search = true;
			$search_term = sanitize_text_field( $_GET['ec_search'] );
		}
		
		self::$mysqli->query( "SET SQL_BIG_SELECTS=1" );
		
		$sql1 = "SELECT product.product_id, product.post_id, ec_roleprice.role_price
				
				FROM ec_product as product 
				
				LEFT JOIN ec_roleprice ON ( ec_roleprice.product_id = product.product_id AND ec_roleprice.role_label = '" . $GLOBALS['ec_user']->user_level . "' )
				
				LEFT JOIN ec_manufacturer as manufacturer ON manufacturer.manufacturer_id = product.manufacturer_id
				
				LEFT JOIN ec_categoryitem ON ec_categoryitem.product_id = product.product_id ";
		$sql1 .= $extra_joins;		
				if( get_option( 'ec_option_search_menu' ) ){
		$sql1 .= "

				LEFT JOIN ec_menulevel1 ON ( ec_menulevel1.menulevel1_id = product.menulevel1_id_1 OR ec_menulevel1.menulevel1_id = product.menulevel2_id_1 OR ec_menulevel1.menulevel1_id = product.menulevel3_id_1 )
				
				LEFT JOIN ec_menulevel2 ON ( ec_menulevel2.menulevel2_id = product.menulevel1_id_2 OR ec_menulevel2.menulevel2_id = product.menulevel2_id_2 OR ec_menulevel2.menulevel2_id = product.menulevel3_id_2 )
				
				LEFT JOIN ec_menulevel3 ON ( ec_menulevel3.menulevel3_id = product.menulevel1_id_3 OR ec_menulevel3.menulevel3_id = product.menulevel2_id_3 OR ec_menulevel3.menulevel3_id = product.menulevel3_id_3 )
				
				";
				}
		
        if( $optionitem_filter != '' ){
            $sql1 .= 'LEFT JOIN ec_option_to_product ON (
                ec_option_to_product.product_id = product.product_id
            )
            LEFT JOIN ec_option ON (
                (
                    product.use_advanced_optionset = false AND 
                    (
                      product.option_id_1 = ec_option.option_id OR
                      product.option_id_2 = ec_option.option_id OR
                      product.option_id_3 = ec_option.option_id OR
                      product.option_id_4 = ec_option.option_id OR
                      product.option_id_5 = ec_option.option_id
                    )
                )
                OR
                (
                    product.use_advanced_optionset = true AND
                    ec_option_to_product.option_id = ec_option.option_id
                )
            ),
            ec_optionitem ';
        }
		
		if( isset( $_GET['ec_optionitem_id'] ) ){
			
			$sql1 .= self::$mysqli->prepare( "JOIN ec_optionitemquantity ON ( ec_optionitemquantity.product_id = product.product_id AND ( ec_optionitemquantity.optionitem_id_1 = %d OR ec_optionitemquantity.optionitem_id_2 = %d OR ec_optionitemquantity.optionitem_id_3 = %d OR ec_optionitemquantity.optionitem_id_4 = %d OR ec_optionitemquantity.optionitem_id_5 = %d ) AND ec_optionitemquantity.quantity > 0 )
			
			", (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'] );
			
		}
		
		$sql2 = "SELECT
		
					product.*, ec_roleprice.role_price,";
		
		if( $has_search ){
			
		$sql2 .= self::$mysqli->prepare( "
				CASE WHEN product.title = %s THEN 20 ELSE 0 END + 
				CASE WHEN product.title LIKE %s THEN 3 ELSE 0 END + 
				CASE WHEN product.title LIKE %s THEN 2 ELSE 0 END + 
				CASE WHEN product.title LIKE %s THEN 1 ELSE 0 END AS search_match_score, ", 
				$search_term, 
				$search_term . "%", 
				"%" . $search_term . "%", 
				"%" . $search_term );
				
		}
		
		$sql2 .= "  manufacturer.name as manufacturer_name,
					
					ec_product_google_attributes.attribute_value as google_attributes,
					
					AVG( ec_review.rating ) as review_average,
					
					" . self::$mysqli->prefix . "posts.guid
					
					FROM ec_product AS product
					
					LEFT JOIN " . self::$mysqli->prefix . "posts ON " . self::$mysqli->prefix . "posts.ID = product.post_id 
				
					LEFT JOIN ec_roleprice ON ( ec_roleprice.product_id = product.product_id AND ec_roleprice.role_label = '" . $GLOBALS['ec_user']->user_level . "' )
					
					LEFT JOIN ec_manufacturer AS manufacturer ON manufacturer.manufacturer_id = product.manufacturer_id
				
					LEFT JOIN ec_categoryitem ON ec_categoryitem.product_id = product.product_id
					
					LEFT JOIN ec_product_google_attributes ON ec_product_google_attributes.product_id = product.product_id";
		$sql2 .= $extra_joins;
					if( get_option( 'ec_option_search_menu' ) ){
						
		$sql2 .= "
					
					LEFT JOIN ec_menulevel1 ON ( ec_menulevel1.menulevel1_id = product.menulevel1_id_1 OR ec_menulevel1.menulevel1_id = product.menulevel2_id_1 OR ec_menulevel1.menulevel1_id = product.menulevel3_id_1 )
					
					LEFT JOIN ec_menulevel2 ON ( ec_menulevel2.menulevel2_id = product.menulevel1_id_2 OR ec_menulevel2.menulevel2_id = product.menulevel2_id_2 OR ec_menulevel2.menulevel2_id = product.menulevel3_id_2 )
					
					LEFT JOIN ec_menulevel3 ON ( ec_menulevel3.menulevel3_id = product.menulevel1_id_3 OR ec_menulevel3.menulevel3_id = product.menulevel2_id_3 OR ec_menulevel3.menulevel3_id = product.menulevel3_id_3 )";
					
					}
		$sql2 .= "
					
					LEFT JOIN ec_review ON ( ec_review.product_id = product.product_id AND ec_review.approved = 1 )
				
				";
		
        if( $optionitem_filter != '' ){
            $sql2 .= 'LEFT JOIN ec_option_to_product ON (
                ec_option_to_product.product_id = product.product_id
            )
            LEFT JOIN ec_option ON (
                (
                    product.use_advanced_optionset = false AND 
                    (
                      product.option_id_1 = ec_option.option_id OR
                      product.option_id_2 = ec_option.option_id OR
                      product.option_id_3 = ec_option.option_id OR
                      product.option_id_4 = ec_option.option_id OR
                      product.option_id_5 = ec_option.option_id
                    )
                )
                OR
                (
                    product.use_advanced_optionset = true AND
                    ec_option_to_product.option_id = ec_option.option_id
                )
            ),
            ec_optionitem ';
        }
				
		if( isset( $_GET['ec_optionitem_id'] ) ){
			
			$sql2 .= self::$mysqli->prepare( "JOIN ec_optionitemquantity ON ( ec_optionitemquantity.product_id = product.product_id AND ( ec_optionitemquantity.optionitem_id_1 = %d OR ec_optionitemquantity.optionitem_id_2 = %d OR ec_optionitemquantity.optionitem_id_3 = %d OR ec_optionitemquantity.optionitem_id_4 = %d OR ec_optionitemquantity.optionitem_id_5 = %d ) AND ec_optionitemquantity.quantity > 0 )
			
			", (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'], (int) $_GET['ec_optionitem_id'] );
			
		}
				
		$group_query = " GROUP BY product.product_id ";
		
		// User Role Lock Down if Used
		if( strtoupper( substr( trim( $where_query ), 0, 5 ) ) != 'WHERE' ){
			$where_query .= " WHERE ";
		}else{
			$where_query .= " AND ";
		}
		
		if( $GLOBALS['ec_user']->role_id )
			$where_query .= self::$mysqli->prepare( " ( product.role_id = 0 OR product.role_id = %d ) ", $GLOBALS['ec_user']->role_id );
		else
			$where_query .= " ( product.role_id = 0 OR product.role_id = -1 ) ";
		// End User Role Lock Down Code
				
		$result = self::$mysqli->get_results( $sql1 . $where_query . $group_query );
		$result_count = count($result);
		
		$result2 = self::$mysqli->get_results( $sql2 . $where_query . $group_query . $order_query . $limit_query );
		
		//Process the Result
		$option_list = $GLOBALS['ec_options']->options;
		$review_list = $GLOBALS['ec_customer_reviews']->customer_reviews;
		$pricetier_list = $GLOBALS['ec_pricetiers']->pricetiers;
		$optionitem_list = $GLOBALS['ec_options']->optionitems;
		$optionitem_image_list = $GLOBALS['ec_options']->optionitemimages;
		$product_list = array();
		
		foreach($result2 as $row){
			
			// Get the review data for the product
			$review_data = array( );
			foreach( $review_list as $review ){
				if( $review->product_id == $row->product_id ){
					$review_data[] = $review->rating;
				}
			}
			
			// Get the review average
			if( count( $review_data ) > 0 ){
				$review_average = array_sum( $review_data ) / count( $review_data );
			}else{
				$review_average = 0;
			}
			
			// Get the price tier data
			$pricetier_data = array( );
			foreach( $pricetier_list as $pricetier ){
				if( $pricetier->product_id == $row->product_id ){
					$pricetier_data[] = array( $pricetier->price, $pricetier->quantity );
				}
			}
			
			//Setup Return Array
			$temp_product = array(
						"product_count" => $result_count,
						"product_id" => $row->product_id,
						"model_number" => $row->model_number,
						"post_id" => $row->post_id,
						"guid" => $row->guid,
						"activate_in_store" => $row->activate_in_store,
						"manufacturer_id" => $row->manufacturer_id, 
						"manufacturer_name" => $row->manufacturer_name, 
						"title" => $row->title, 
						"description" => $row->description, 
						"short_description" => $row->short_description, 
						"seo_description" => $row->seo_description, 
						"seo_keywords" => $row->seo_keywords, 
						
						"price" => $row->price, 
						"list_price" => $row->list_price,
						"login_for_pricing" => $row->login_for_pricing,
						"login_for_pricing_user_level" => $row->login_for_pricing_user_level,
						"login_for_pricing_label" => $row->login_for_pricing_label,
						"show_custom_price_range" => $row->show_custom_price_range,
						"price_range_low" => $row->price_range_low,
						"price_range_high" => $row->price_range_high,
						"enable_price_label" => $row->enable_price_label,
						"replace_price_label" => $row->replace_price_label,
						"custom_price_label" => $row->custom_price_label,
						
						"vat_rate" => $row->vat_rate,
						"handling_price" => $row->handling_price,
						"handling_price_each" => $row->handling_price_each,
						"stock_quantity" => $row->stock_quantity,
						"min_purchase_quantity" => $row->min_purchase_quantity,
						"max_purchase_quantity" => $row->max_purchase_quantity,
						"weight" => $row->weight,  
						"width" => $row->width,  
						"height" => $row->height,  
						"length" => $row->length, 
						"use_optionitem_quantity_tracking" => $row->use_optionitem_quantity_tracking, 
						"use_specifications" => $row->use_specifications, 
						"specifications" => $row->specifications, 
						"use_customer_reviews" => $row->use_customer_reviews, 
						"show_on_startup" => $row->show_on_startup,
						"show_stock_quantity" => $row->show_stock_quantity, 
						"is_special" => $row->is_special, 
						"is_taxable" => $row->is_taxable, 
						"is_shippable" => $row->is_shippable, 
                        "exclude_shippable_calculation" => $row->exclude_shippable_calculation,
						"is_giftcard" => $row->is_giftcard, 
						"is_download" => $row->is_download,
						"is_donation" => $row->is_donation,
						"is_subscription_item" => $row->is_subscription_item,
						"is_deconetwork" => $row->is_deconetwork,
						"allow_backorders" => $row->allow_backorders,
						"backorder_fill_date" => $row->backorder_fill_date,
						"TIC" => $row->TIC,
						
						"include_code" => $row->include_code,
						
						"download_file_name" => $row->download_file_name,
						"is_amazon_download" => $row->is_amazon_download,
						"amazon_key" => $row->amazon_key,
						
						"subscription_bill_length" => $row->subscription_bill_length,
						"subscription_bill_period" => $row->subscription_bill_period,
						"subscription_bill_duration" => $row->subscription_bill_duration,
						"subscription_shipping_recurring" => ( isset( $row->subscription_shipping_recurring ) ) ? $row->subscription_shipping_recurring : 0,
						"trial_period_days" => $row->trial_period_days,
						"stripe_plan_added" => $row->stripe_plan_added,
						"subscription_signup_fee" => $row->subscription_signup_fee,
						"subscription_unique_id" => $row->subscription_unique_id,
						"subscription_prorate" => $row->subscription_prorate,
						"allow_multiple_subscription_purchases" => $row->allow_multiple_subscription_purchases,
						
						"option_id_1" => $row->option_id_1, 
						"option_id_2" => $row->option_id_2, 
						"option_id_3" => $row->option_id_3,
						"option_id_4" => $row->option_id_4,
						"option_id_5" => $row->option_id_5,
						
						"use_both_option_types" => $row->use_both_option_types,
						"use_advanced_optionset" => $row->use_advanced_optionset,
						"use_optionitem_images" => $row->use_optionitem_images,
						
						"image1" => $row->image1,
						"image2" => $row->image2,
						"image3" => $row->image3,
						"image4" => $row->image4,
						"image5" => $row->image5,
						"product_images" => $row->product_images,
						
						"featured_product_id_1" => $row->featured_product_id_1,
						"featured_product_id_2" => $row->featured_product_id_2,
						"featured_product_id_3" => $row->featured_product_id_3,
						"featured_product_id_4" => $row->featured_product_id_4,
						
						"catalog_mode" => $row->catalog_mode,
						"catalog_mode_phrase" => $row->catalog_mode_phrase,
						"inquiry_mode" => $row->inquiry_mode,
						"inquiry_url" => $row->inquiry_url,
				
						"deconetwork_mode" => $row->deconetwork_mode,
						"deconetwork_product_id" => $row->deconetwork_product_id,
						"deconetwork_size_id" => $row->deconetwork_size_id,
						"deconetwork_color_id" => $row->deconetwork_color_id,
						"deconetwork_design_id" => $row->deconetwork_design_id,
						
						"review_data" => $review_data,
						"review_average" => $review_average,
						"views" => $row->views,
						"pricetier_data" => $pricetier_data,
						"google_attributes" => $row->google_attributes,
						
						"display_type" => $row->display_type,
						"image_hover_type" => $row->image_hover_type,
						"image_effect_type" => $row->image_effect_type,
						"tag_type" => $row->tag_type,
						"tag_bg_color" => $row->tag_bg_color,
						"tag_text_color" => $row->tag_text_color,
						"tag_text" => $row->tag_text
			);
			
			$product_list[] = $temp_product;
			
		}
		
		if( $cache_key != "" )
			wp_cache_set( $cache_key, $product_list, 'wpeasycart-product-list' );
		
		//Return Array
		return $product_list;
		
	}
	
	public static function clean_search( $string ){
		return self::$mysqli->prepare( '%s', $string );		
	}
	
	public static function get_tempcart_product_quantity( $session_id, $product_id ){
		
		$sql = "SELECT SUM(tempcart.quantity) as quantity FROM ec_tempcart as tempcart WHERE tempcart.session_id = '%s' AND tempcart.product_id = %d";
		$result = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $session_id, $product_id ) );
		return $result->quantity;
			
	}
	
	public static function get_tempcart_product_quantities( $session_id ){
		//$results = wp_cache_get( 'wpeasycart-tempcart-product-quantities-'.$session_id, 'wpeasycart-tempcart' );
		//if( !$results ){
			$sql = "SELECT SUM(tempcart.quantity) as quantity, tempcart.product_id FROM ec_tempcart as tempcart WHERE tempcart.session_id = '%s' AND tempcart.session_id != '' AND tempcart.session_id != 'deleted' AND tempcart.session_id != 'not-set' GROUP BY tempcart.product_id";
			$results = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $session_id ) );
		//	wp_cache_set( 'wpeasycart-tempcart-product-quantities-'.$session_id, $results, 'wpeasycart-tempcart', 3600 );
		//}
		return $results;
			
	}
	
	public static function get_quantity_data( $product_id, $optionset1, $optionset2, $optionset3, $optionset4, $optionset5 ){
		
		$return_array = array();
		
		$quantity_items = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT * FROM ec_optionitemquantity WHERE product_id = %d", $product_id ) );
		foreach( $quantity_items as $item ){
			$item->quantity = ( $item->is_stock_tracking_enabled ) ? $item->quantity : 1;
			// Level 1 Calculation
			if( isset( $return_array[$item->optionitem_id_1.'....'] ) )
				$return_array[$item->optionitem_id_1.'....'] = $return_array[$item->optionitem_id_1.'....'] + $item->quantity;
			else
				$return_array[$item->optionitem_id_1.'....'] = $item->quantity;
				
			// Level 2 Calculation
			if( isset( $return_array[$item->optionitem_id_1.$item->optionitem_id_2.'...'] ) )
				$return_array[$item->optionitem_id_1.$item->optionitem_id_2.'...'] = $return_array[$item->optionitem_id_1.$item->optionitem_id_2.'...'] + $item->quantity;
			else
				$return_array[$item->optionitem_id_1.$item->optionitem_id_2.'...'] = $item->quantity;
				
			// Level 3 Calculation
			if( isset( $return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.'..'] ) )
				$return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.'..'] = $return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.'..'] + $item->quantity;
			else
				$return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.'..'] = $item->quantity;
			
			// Level 4 Calculation
			if( isset( $return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.$item->optionitem_id_4.'.'] ) )
				$return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.$item->optionitem_id_4.'.'] = $return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.$item->optionitem_id_4.'.'] + $item->quantity;
			else
				$return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.$item->optionitem_id_4.'.'] = $item->quantity;
			
			// Level 5 Calculation
			$return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.$item->optionitem_id_4.$item->optionitem_id_5] = $item->quantity;
		}
		
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			$tempcart_data = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_tempcart.* FROM ec_tempcart WHERE ec_tempcart.product_id = %d AND ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ), $product_id, $hours ) );
			foreach( $tempcart_data as $tempcart_item ){
				if( $tempcart_item->optionitem_id_1 ){
					$return_array[$tempcart_item->optionitem_id_1.'....'] -= $tempcart_item->quantity;
				}
				if( $tempcart_item->optionitem_id_2 ){
					$return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.'...'] -= $tempcart_item->quantity;
				}
				if( $tempcart_item->optionitem_id_3 ){
					$return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.$tempcart_item->optionitem_id_3.'..'] -= $tempcart_item->quantity;
				}
				if( $tempcart_item->optionitem_id_4 ){
					$return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.$tempcart_item->optionitem_id_3.$tempcart_item->optionitem_id_4.'.'] -= $tempcart_item->quantity;
				}
				if( $tempcart_item->optionitem_id_5 ){
					$return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.$tempcart_item->optionitem_id_3.$tempcart_item->optionitem_id_4.$tempcart_item->optionitem_id_5] -= $tempcart_item->quantity;
				}
			}
		}
		
		return $return_array;
		
	}
	
	public static function get_variation_data( $product_id, $optionset1, $optionset2, $optionset3, $optionset4, $optionset5 ){
		$return_array = array();
		$quantity_items = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT * FROM ec_optionitemquantity WHERE product_id = %d", $product_id ) );
		foreach( $quantity_items as $item ){
			$return_array[$item->optionitem_id_1.$item->optionitem_id_2.$item->optionitem_id_3.$item->optionitem_id_4.$item->optionitem_id_5] = $item;
			if ( $item->optionitem_id_1 ) {
				if ( ! isset( $return_array[$item->optionitem_id_1] ) ) {
					$return_array[$item->optionitem_id_1] = (object) array(
						'items' => array(),
					);
				}
				$return_array[$item->optionitem_id_1]->items[] = $item;
			}
			if ( $item->optionitem_id_2 ) {
				if ( ! isset( $return_array['x'.$item->optionitem_id_2] ) ) {
					$return_array['x'.$item->optionitem_id_2] = (object) array(
						'items' => array(),
					);
				}
				$return_array['x'.$item->optionitem_id_2]->items[] = $item;
			}
			if ( $item->optionitem_id_3 ) {
				if ( ! isset( $return_array['xx'.$item->optionitem_id_3] ) ) {
					$return_array['xx'.$item->optionitem_id_3] = (object) array(
						'items' => array(),
					);
				}
				$return_array['xx'.$item->optionitem_id_3]->items[] = $item;
			}
			if ( $item->optionitem_id_4 ) {
				if ( ! isset( $return_array['xxx'.$item->optionitem_id_4] ) ) {
					$return_array['xxx'.$item->optionitem_id_4] = (object) array(
						'items' => array(),
					);
				}
				$return_array['xxx'.$item->optionitem_id_4]->items[] = $item;
			}
			if ( $item->optionitem_id_5 ) {
				if ( ! isset( $return_array['xxxx'.$item->optionitem_id_5] ) ) {
					$return_array['xxxx'.$item->optionitem_id_5] = (object) array(
						'items' => array(),
					);
				}
				$return_array['xxxx'.$item->optionitem_id_5]->items[] = $item;
			}
		}
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			$tempcart_data = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_tempcart.* FROM ec_tempcart WHERE ec_tempcart.product_id = %d AND ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ), $product_id, $hours ) );
			foreach( $tempcart_data as $tempcart_item ){
				if( isset( $return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.$tempcart_item->optionitem_id_3.$tempcart_item->optionitem_id_4.$tempcart_item->optionitem_id_5] ) ) {
					$return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.$tempcart_item->optionitem_id_3.$tempcart_item->optionitem_id_4.$tempcart_item->optionitem_id_5]->quantity -= $tempcart_item->quantity;
				} else {
					$return_array[$tempcart_item->optionitem_id_1.$tempcart_item->optionitem_id_2.$tempcart_item->optionitem_id_3.$tempcart_item->optionitem_id_4.$tempcart_item->optionitem_id_5] = (object) array(
						'quantity' => $tempcart_item->quantity
					);
				}
			}
		}
		return $return_array;
	}
	
	private static function get_level_1_quantity(&$values, $opt1){
		$quant = 0;
		for($i=0; $i<count($values); $i++){
			if($values[$i][0] == $opt1){
				$quant = $quant + $values[$i][5];
			}
		}
		return $quant;
	}
	
	private static function get_level_2_quantity(&$values, $opt1, $opt2){
		$quant = 0;
		for($i=0; $i<count($values); $i++){
			if($values[$i][0] == $opt1 && $values[$i][1] == $opt2){
				$quant = $quant + $values[$i][5];
			}
		}
		return $quant;
	}
	
	private static function get_level_3_quantity(&$values, $opt1, $opt2, $opt3){
		$quant = 0;
		for($i=0; $i<count($values); $i++){
			if($values[$i][0] == $opt1 && $values[$i][1] == $opt2 && $values[$i][2] == $opt3){
				$quant = $quant + $values[$i][5];
			}
		}
		return $quant;
	}
	
	private static function get_level_4_quantity(&$values, $opt1, $opt2, $opt3, $opt4){
		$quant = 0;
		for($i=0; $i<count($values); $i++){
			if($values[$i][0] == $opt1 && $values[$i][1] == $opt2 && $values[$i][2] == $opt3 && $values[$i][3] == $opt4){
				$quant = $quant + $values[$i][5];
			}
		}
		return $quant;
	}
	
	private static function get_level_5_quantity(&$values, $opt1, $opt2, $opt3, $opt4, $opt5){
		$quant = 0;
		for($i=0; $i<count($values); $i++){
			if($values[$i][0] == $opt1 && $values[$i][1] == $opt2 && $values[$i][2] == $opt3 && $values[$i][3] == $opt4 && $values[$i][4] == $opt5){
				$quant = $quant + $values[$i][5];
			}
		}
		return $quant;
	}
	
	public static function get_perpage( $perpage ){
		$sql = "SELECT ec_perpage.perpage FROM ec_perpage WHERE ec_perpage.perpage = %d";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $perpage ) );
	}
	
	public static function get_perpage_values(){
		$array = wp_cache_get( 'wpeasycart-perpages' );
		if( !$array ){
			$vals = self::$mysqli->get_results( "SELECT perpage.perpage FROM ec_perpage as perpage ORDER BY perpage.perpage ASC" );
			$array = array( );
			foreach( $vals as $val ){
				$array[] = $val->perpage;	
			}
			wp_cache_set( 'wpeasycart-perpages', $array );
		}
		return $array;
	}
	
	public static function get_manufacturers( $level, $menuid, $manufacturer_id, $category_id ){
		
		$manufacturers = wp_cache_get( 'wpeasycart-manufacturers-'.$level.'-'.$menuid.'-'.$manufacturer_id.'-'.$category_id );
		if( !$manufacturers ){
			$manufacturers = self::$mysqli->get_results( "SELECT manufacturer_id, name FROM ec_manufacturer ORDER BY name ASC" );
			if( $category_id ){
				$products = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_product.manufacturer_id, COUNT( ec_product.manufacturer_id ) as product_count FROM ec_product, ec_categoryitem WHERE ec_product.activate_in_store = 1 AND ec_product.product_id = ec_categoryitem.product_id AND ec_categoryitem.category_id = %d GROUP BY ec_product.manufacturer_id", $category_id ) );
			}else if( $level > 0 ){
				$products = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_product.manufacturer_id, COUNT( ec_product.manufacturer_id ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 AND ( ec_product.menulevel1_id_%d = %d OR ec_product.menulevel2_id_%d = %d OR ec_product.menulevel3_id_%d = %d ) GROUP BY ec_product.manufacturer_id", $level, $menuid, $level, $menuid, $level, $menuid ) );
			}else{
				$products = self::$mysqli->get_results( "SELECT ec_product.manufacturer_id, COUNT( ec_product.manufacturer_id ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 AND ec_product.show_on_startup = 1 GROUP BY ec_product.manufacturer_id" );
			}
			
			$counts = array( );
			for( $i=0; $i<count( $products ); $i++ ){
				$counts[$products[$i]->manufacturer_id] = $products[$i]->product_count;			
			}
			
			for( $i=0; $i<count( $manufacturers ); $i++ ){
				if( isset( $counts[$manufacturers[$i]->manufacturer_id] ) )
					$manufacturers[$i]->product_count = $counts[$manufacturers[$i]->manufacturer_id];
				else
					$manufacturers[$i]->product_count = 0;
			}
			
			wp_cache_set( 'wpeasycart-manufacturers-'.$level.'-'.$menuid.'-'.$manufacturer_id.'-'.$category_id, $manufacturers );
		}
		return $manufacturers;
	
	}
	
	public static function get_groups( ){
		
		return self::$mysqli->get_results( "SELECT ec_category.category_id, ec_category.category_name, ec_category.post_id FROM ec_category WHERE ec_category.is_active = 1 ORDER BY ec_category.priority DESC, ec_category.category_name ASC" );	
	}
	
	public static function get_pricepoint_row( $pricepoint_id ){
		$sql = "SELECT 
					ec_pricepoint.pricepoint_id, 
					ec_pricepoint.is_less_than, 
					ec_pricepoint.is_greater_than, 
					ec_pricepoint.low_point, 
					ec_pricepoint.high_point, 
					(
						SELECT COUNT( ec_product.product_id ) 
						FROM ec_product 
						WHERE 
						ec_product.activate_in_store = 1 AND 
						ec_product.show_on_startup = 1 AND 
						ec_product.price < ec_pricepoint.high_point
					) as product_count_below,
					(
						SELECT COUNT( ec_product.product_id )
						FROM ec_product 
						WHERE 
						ec_product.activate_in_store = 1 AND 
						ec_product.show_on_startup = 1 AND 
						ec_product.price > ec_pricepoint.low_point
					) as product_count_above,
					(
						SELECT COUNT( ec_product.product_id )
						FROM ec_product 
						WHERE 
						ec_product.activate_in_store = 1 AND 
						ec_product.show_on_startup = 1 AND 
						ec_product.price <= ec_pricepoint.high_point AND 
						ec_product.price >= ec_pricepoint.low_point
					) as product_count_between
					 FROM ec_pricepoint 
					 WHERE ec_pricepoint.pricepoint_id = %d
					 ORDER BY ec_pricepoint.pricepoint_order ASC";
					 
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $pricepoint_id ) );
	}
	
	public static function get_pricepoints( $level, $menuid, $manufacturerid, $category_id ){
		if( $manufacturerid )
			$man_sql = self::$mysqli->prepare( " AND ec_product.manufacturer_id = %d", $manufacturerid );
		else
			$man_sql = "";
			
		if( $category_id ){
			$cat_from_sql = ", ec_categoryitem ";
			$cat_sql = self::$mysqli->prepare( " AND ec_product.product_id = ec_categoryitem.product_id AND ec_categoryitem.category_id = %d", $category_id );
		}else{
			$cat_from_sql = " ";
			$cat_sql = "";
		}
		
		$show_on_startup = "";
		if( $menuid == 0 && $category_id == 0 )
			$show_on_startup = "ec_product.show_on_startup = 1 AND ";
		
		if( $level == 0 )
			$sql = "SELECT 
					ec_pricepoint.pricepoint_id, 
					ec_pricepoint.is_less_than, 
					ec_pricepoint.is_greater_than, 
					ec_pricepoint.low_point, 
					ec_pricepoint.high_point, 
					(
						SELECT COUNT( ec_product.product_id ) 
						FROM ec_product" . $cat_from_sql . "
						WHERE 
						ec_product.activate_in_store = 1 AND 
						".$show_on_startup."
						ec_product.price < ec_pricepoint.high_point
						" . $man_sql . "
						" . $cat_sql . "
					) as product_count_below,
					(
						SELECT COUNT( ec_product.product_id )
						FROM ec_product" . $cat_from_sql . "
						WHERE 
						ec_product.activate_in_store = 1 AND 
						".$show_on_startup."
						ec_product.price > ec_pricepoint.low_point
						" . $man_sql . "
						" . $cat_sql . "
					) as product_count_above,
					(
						SELECT COUNT( ec_product.product_id )
						FROM ec_product" . $cat_from_sql . "
						WHERE 
						ec_product.activate_in_store = 1 AND 
						".$show_on_startup."
						ec_product.price <= ec_pricepoint.high_point AND 
						ec_product.price >= ec_pricepoint.low_point
						" . $man_sql . "
						" . $cat_sql . "
					) as product_count_between
					 FROM ec_pricepoint 
					 ORDER BY ec_pricepoint.pricepoint_order ASC";
		else
			$sql = self::$mysqli->prepare( "SELECT 
					ec_pricepoint.pricepoint_id, 
					ec_pricepoint.is_less_than, 
					ec_pricepoint.is_greater_than, 
					ec_pricepoint.low_point, 
					ec_pricepoint.high_point, 
					(
						SELECT COUNT( ec_product.product_id ) 
						FROM ec_product" . $cat_from_sql . "
						WHERE 
						ec_product.activate_in_store = 1 AND 
						ec_product.price < ec_pricepoint.high_point AND
						( ec_product.menulevel1_id_%d = %d OR ec_product.menulevel2_id_%d = %d OR ec_product.menulevel3_id_%d = %d )
						" . $man_sql . "
						" . $cat_sql . "
					) as product_count_below,
					(
						SELECT COUNT( ec_product.product_id )
						FROM ec_product" . $cat_from_sql . "
						WHERE 
						ec_product.activate_in_store = 1 AND 
						ec_product.price > ec_pricepoint.low_point AND
						( ec_product.menulevel1_id_%d = %d OR ec_product.menulevel2_id_%d = %d OR ec_product.menulevel3_id_%d = %d )
						" . $man_sql . "
						" . $cat_sql . "
					) as product_count_above,
					(
						SELECT COUNT( ec_product.product_id )
						FROM ec_product" . $cat_from_sql . "
						WHERE 
						ec_product.activate_in_store = 1 AND 
						ec_product.price <= ec_pricepoint.high_point AND 
						ec_product.price >= ec_pricepoint.low_point AND
						( ec_product.menulevel1_id_%d = %d OR ec_product.menulevel2_id_%d = %d OR ec_product.menulevel3_id_%d = %d )
						" . $man_sql . "
						" . $cat_sql . "
					) as product_count_between
					 FROM ec_pricepoint 
					 ORDER BY ec_pricepoint.pricepoint_order ASC", $level, $menuid, $level, $menuid, $level, $menuid, $level, $menuid, $level, $menuid, $level, $menuid, $level, $menuid, $level, $menuid, $level, $menuid );
					 
		
		return self::$mysqli->get_results( $sql );	
	}
	
	public static function get_menuname( $menu_id, $menu_level ){
		if( $menu_level == 1 )
			return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT name FROM ec_menulevel1 WHERE menulevel1_id = %d", $menu_id ) );
		else if( $menu_level == 2 )
			return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT name FROM ec_menulevel2 WHERE menulevel2_id = %d", $menu_id ) );
		else if( $menu_level == 3 )
			return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT name FROM ec_menulevel3 WHERE menulevel3_id = %d", $menu_id ) );
	}
	
	public static function get_menulevel1_id( $menulevel1_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_menulevel1.menulevel1_id FROM ec_menulevel1 WHERE ec_menulevel1.menulevel1_id = %d", $menulevel1_id ) );
	}
	
	public static function get_menulevel2_id( $menulevel2_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_menulevel2.menulevel2_id FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d", $menulevel2_id ) );
	}
	
	public static function get_menulevel3_id( $menulevel3_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_menulevel3.menulevel3_id FROM ec_menulevel3 WHERE ec_menulevel3.menulevel3_id = %d", $menulevel3_id ) );
	}
	
	public static function get_menulevel1_id_from_menulevel2( $menulevel2_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_menulevel2.menulevel1_id FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d", $menulevel2_id ) );
	}
	
	
	public static function get_menulevel2_id_from_menulevel3( $menulevel3_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_menulevel3.menulevel2_id FROM ec_menulevel3 WHERE ec_menulevel3.menulevel3_id = %d", $menulevel3_id ) );
	}
	
	public static function get_customer_reviews( $product_id ){
		return self::$mysqli->get_results( "SELECT ec_review.review_id, ec_review.rating, ec_review.title, ec_review.description, ec_review.approved, DATE_FORMAT(ec_review.date_submitted, '%W, %M %e, %Y') as review_date, ec_review.reviewer_name, ec_user.first_name, ec_user.last_name " . self::$mysqli->prepare( "FROM ec_review LEFT JOIN ec_user ON ec_user.user_id = ec_review.user_id WHERE product_id = %d AND approved = 1 ORDER BY date_submitted DESC", $product_id ) );
	}
	
	public static function get_all_customer_reviews( ){
		$reviews = wp_cache_get( 'wpeasycart-reviews' );
		if( !$reviews ){
			$reviews = self::$mysqli->get_results( "SELECT ec_review.review_id, ec_review.product_id, ec_review.rating, ec_review.title, ec_review.description, ec_review.approved, DATE_FORMAT(ec_review.date_submitted, '%W, %M %e, %Y') as review_date, ec_review.reviewer_name, ec_user.first_name, ec_user.last_name FROM ec_review LEFT JOIN ec_user ON ec_user.user_id = ec_review.user_id WHERE approved = 1 ORDER BY ec_review.product_id ASC, ec_review.date_submitted DESC" );
			wp_cache_set( 'wpeasycart-reviews', $reviews );
		}
		return $reviews;
	}
	
	public static function update_temp_cart_inventory( $session_id ){
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			$cart = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_tempcart.tempcart_id, ec_tempcart.quantity, ec_tempcart.optionitem_id_1, ec_tempcart.optionitem_id_2, ec_tempcart.optionitem_id_3, ec_tempcart.optionitem_id_4, ec_tempcart.optionitem_id_5, ec_product.stock_quantity, ec_product.show_stock_quantity, ec_product.product_id, ec_product.use_optionitem_quantity_tracking FROM ec_tempcart LEFT JOIN ec_product ON ( ec_product.product_id = ec_tempcart.product_id ) WHERE session_id = %s AND last_changed_date < NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ), $session_id, $hours ) );
			foreach( $cart as $cart_item ){
				if( $cart_item->show_stock_quantity || $cart_item->use_optionitem_quantity_tracking ){
					$stock_remaining = $cart_item->stock_quantity;
					if( $cart_item->use_optionitem_quantity_tracking ){
						$stock_quantity_row = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT
								( SUM( ec_optionitemquantity.quantity ) - COALESCE( SUM( ec_tempcart.quantity ), 0 ) ) as quantity,
								ec_optionitemquantity.is_stock_tracking_enabled,
								ec_tempcart.tempcart_id
							FROM
								ec_tempcart
								LEFT JOIN ec_optionitemquantity ON (
									ec_tempcart.product_id = ec_optionitemquantity.product_id AND
									ec_tempcart.optionitem_id_1 = ec_optionitemquantity.optionitem_id_1 AND
									ec_tempcart.optionitem_id_2 = ec_optionitemquantity.optionitem_id_2 AND
									ec_tempcart.optionitem_id_3 = ec_optionitemquantity.optionitem_id_3 AND
									ec_tempcart.optionitem_id_4 = ec_optionitemquantity.optionitem_id_4 AND
									ec_tempcart.optionitem_id_5 = ec_optionitemquantity.optionitem_id_5
								)
							WHERE
								ec_tempcart.product_id = %d AND
								ec_tempcart.optionitem_id_1 = %d AND
								ec_tempcart.optionitem_id_2 = %d AND
								ec_tempcart.optionitem_id_3 = %d AND
								ec_tempcart.optionitem_id_4 = %d AND
								ec_tempcart.optionitem_id_5 = %d AND
								ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
							GROUP BY
								ec_tempcart.product_id", 
							$cart_item->product_id, 
							$cart_item->optionitem_id_1, $cart_item->optionitem_id_2, $cart_item->optionitem_id_3,
							$cart_item->optionitem_id_4, $cart_item->optionitem_id_5, $hours
						) );
						if( $stock_quantity_row ){
							$stock_remaining = ( $stock_quantity_row->is_stock_tracking_enabled ) ? $stock_quantity_row->quantity : 100000000;
						}else{
							$stock_quantity_row = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT
									ec_optionitemquantity.quantity,
									ec_optionitemquantity.is_stock_tracking_enabled
								FROM
									ec_optionitemquantity
								WHERE
									ec_optionitemquantity.product_id = %d AND
									ec_optionitemquantity.optionitem_id_1 = %d AND
									ec_optionitemquantity.optionitem_id_2 = %d AND
									ec_optionitemquantity.optionitem_id_3 = %d AND
									ec_optionitemquantity.optionitem_id_4 = %d AND
									ec_optionitemquantity.optionitem_id_5 = %d", 
								$cart_item->product_id, 
								$cart_item->optionitem_id_1, $cart_item->optionitem_id_2, $cart_item->optionitem_id_3,
								$cart_item->optionitem_id_4, $cart_item->optionitem_id_5
							) );
							if ( $stock_quantity_row ) {
								$stock_remaining = ( $stock_quantity_row->is_stock_tracking_enabled ) ? $stock_quantity_row->quantity : 100000000;
							}
						}
					}else{ // basic quantity tracking
						$stock_quantity_row = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT
								COALESCE( SUM( ec_tempcart.quantity ), 0 ) as quantity
							FROM
								ec_tempcart
							WHERE
								ec_tempcart.product_id = %d AND
								ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
							GROUP BY
								ec_tempcart.product_id", 
							$cart_item->product_id, $hours
						) );
						if ( $stock_quantity_row ) {
							$stock_remaining -= $stock_quantity_row->quantity;
						}
					}
					if( $stock_remaining <= 0 ){
						self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart WHERE tempcart_id = %d", $cart_item->tempcart_id ) );
						do_action( 'wpeasycart_cart_updated' );
					
					}else if( $stock_remaining < $cart_item->quantity ){
						self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_tempcart SET quantity = %d WHERE tempcart_id = %d", $stock_remaining, $cart_item->tempcart_id ) );
						do_action( 'wpeasycart_cart_updated' );
					}
				}
			}
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_tempcart SET last_changed_date = NOW( ) WHERE session_id = %s", $session_id ) );
		}
	}
    
    public static function get_cart_count( $session_id ){
        $cart_array = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
                ec_product.login_for_pricing,
                ec_product.login_for_pricing_user_level,
                ec_tempcart.tempcart_id as cartitem_id 
            FROM 
                ec_tempcart
				LEFT JOIN ec_product ON ec_product.product_id = ec_tempcart.product_id 
            WHERE 
                ec_tempcart.session_id = '%s' AND 
                ec_tempcart.session_id != '' AND 
                ec_tempcart.session_id != 'deleted' AND 
                ec_tempcart.session_id != 'not-set'", $session_id
        ) );
        $valid_items = 0;
        foreach( $cart_array as $row ){
            if( $row->login_for_pricing && $row->login_for_pricing_user_level != '' && $row->login_for_pricing_user_level != '[]' ){
                $levels = json_decode( $row->login_for_pricing_user_level );
                if( $levels && is_array( $levels ) && in_array( $GLOBALS['ec_user']->user_level, $levels ) ){
                    $valid_items++;
                }
            }else{
                $valid_items++;
            }
        }
        return $valid_items;
    }
	
	public static function get_temp_cart( $session_id ){
		
		//$cart = wp_cache_get( 'wpeasycart-tempcart-'.$session_id, 'wpeasycart-tempcart' );
		//if( !$cart ){
			self::update_temp_cart_inventory( $session_id );
			$sql = "SELECT 
					product.*,
					" . self::$mysqli->prefix . "posts.guid,

					tempcart.tempcart_id as cartitem_id,
					tempcart.quantity,
					tempcart.grid_quantity,

					tempcart.gift_card_message, 
					tempcart.gift_card_to_name, 
					tempcart.gift_card_email, 
					tempcart.gift_card_from_name,

					tempcart.optionitem_id_1,
					tempcart.optionitem_id_2,
					tempcart.optionitem_id_3,
					tempcart.optionitem_id_4,
					tempcart.optionitem_id_5,

					tempcart.is_deconetwork,
					tempcart.deconetwork_id,
					tempcart.deconetwork_name,
					tempcart.deconetwork_product_code,
					tempcart.deconetwork_options,
					tempcart.deconetwork_edit_link,
					tempcart.deconetwork_color_code,
					tempcart.deconetwork_product_id,
					tempcart.deconetwork_image_link,
					tempcart.deconetwork_discount,
					tempcart.deconetwork_tax,
					tempcart.deconetwork_total,
					tempcart.deconetwork_version,
					";
			
			if( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ){
				for( $i=0; $i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ); $i++ ){
					$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array( ), array( ) );
					for( $j=0; $j<count( $arr ); $j++ ){
						$sql .= "tempcart." . $arr[$j] . ", ";
					}
				}
			}
					
			$sql .=	"
					tempcart.donation_price,
					
					ec_manufacturer.`name` as manufacturer_name
					
					FROM ec_tempcart as tempcart
					
					LEFT JOIN ec_product as product ON product.product_id = tempcart.product_id
					
					LEFT JOIN ec_manufacturer ON ec_manufacturer.manufacturer_id = product.manufacturer_id
					
					LEFT JOIN " . self::$mysqli->prefix . "posts ON " . self::$mysqli->prefix . "posts.ID = product.post_id
					
					WHERE tempcart.session_id = '%s' AND tempcart.session_id != '' AND tempcart.session_id != 'deleted' AND tempcart.session_id != 'not-set'
					
					ORDER BY product.title ASC
					";
					
			$cart_array = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $session_id ) );
			$cart = array();
			foreach ($cart_array as $row ) {
				if ( $row->login_for_pricing && $row->login_for_pricing_user_level != '' && $row->login_for_pricing_user_level != '[]' ) {
					$levels = json_decode( $row->login_for_pricing_user_level );
					if ( $levels && is_array( $levels ) && in_array( $GLOBALS['ec_user']->user_level, $levels ) ) {
						array_push( $cart, new ec_cartitem( $row ) );
					}
				}else{
					array_push( $cart, new ec_cartitem( $row ) );
				}
			}
			//wp_cache_set( 'wpeasycart-tempcart-'.$session_id, $cart, 'wpeasycart-tempcart', 3600 );
		//}
		return $cart;
	}
	
	public static function get_cart_data( $session_id ){
		$cart_data = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT ec_tempcart_data.*, billing_country.name_cnt AS billing_country_name, shipping_country.name_cnt AS shipping_country_name FROM ec_tempcart_data LEFT JOIN ec_country AS billing_country ON ( billing_country.iso2_cnt = ec_tempcart_data.billing_country ) LEFT JOIN ec_country AS shipping_country ON( shipping_country.iso2_cnt = ec_tempcart_data.shipping_country ) WHERE ec_tempcart_data.session_id = %s AND ec_tempcart_data.session_id != ''", $session_id ) );
		if( !$cart_data && $session_id != '' && $session_id != 'not-set' ){
			self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_tempcart_data( ec_tempcart_data.session_id ) VALUES( %s )", $session_id ) );
			$cart_data = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT ec_tempcart_data.*, billing_country.name_cnt AS billing_country_name, shipping_country.name_cnt AS shipping_country_name FROM ec_tempcart_data LEFT JOIN ec_country AS billing_country ON ( billing_country.iso2_cnt = ec_tempcart_data.billing_country ) LEFT JOIN ec_country AS shipping_country ON( shipping_country.iso2_cnt = ec_tempcart_data.shipping_country ) WHERE ec_tempcart_data.session_id = %s AND ec_tempcart_data.session_id != ''", $session_id ) );
			self::remove_cart_data( '' );
		}
		return $cart_data;
	}
	
	public static function save_cart_data( $ec_cart_id, $cart_data ){
		
		self::remove_cart_data( $ec_cart_id );
			
		$sql = "INSERT INTO ec_tempcart_data( session_id";
		$sql_values = " VALUES(%s";
		$insert_vals = array( $ec_cart_id );
		
		foreach( $cart_data as $key => $value ){
			
			if( $key != "session_id" && $key != "tempcart_data_id" && $key != "shipping_country_name" && $key != "billing_country_name" ){
			
				$sql .= ", " . $key;
				$sql_values .= ", %s";
				$insert_vals[] = $value;
				
			}
			
		}
		
		$sql .= ")";
		$sql_values .= ")";
		
		self::$mysqli->query( self::$mysqli->prepare( $sql . $sql_values, $insert_vals ) );
		
	}
	
	public static function remove_cart_data( $session_id ){
		
		self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart_data WHERE ec_tempcart_data.session_id = %s OR ec_tempcart_data.session_id = 'deleted' OR ( ec_tempcart_data.tempcart_time < DATE_SUB( NOW( ), INTERVAL 7 DAY ) AND ec_tempcart_data.email = '' ) OR ec_tempcart_data.tempcart_time < DATE_SUB( NOW( ), INTERVAL 18 DAY ) OR ec_tempcart_data.tempcart_time IS NULL", $session_id ) );
	
	}
	
	public static function remove_user_data( $user_id ){
		
		self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart_data WHERE ec_tempcart_data.user_id = %s", $user_id ) );
	
	}
	
	// IN: the Product ID
	// OUT: Product array (
	public static function get_cart_product( $productid ){
			
	}
	
	public static function get_menu_items(){
		$result = wp_cache_get( 'wpeasycart-get-menu-items', 'wpeasycart-menu' );
		if( !$result ){
			$sql = "SELECT 
					menulevel1.name as menu1_name, menulevel1.menulevel1_id, menulevel1.post_id as menulevel1_post_id, menulevel1.menu_order as menulevel1_order, menulevel1.clicks as menulevel1_clicks, menulevel1.seo_keywords as menulevel1_seo_keywords, menulevel1.seo_description as menulevel1_seo_description, menulevel1.banner_image as menulevel1_banner_image, posts1.guid as menulevel1_guid, 
					menulevel2.name as menu2_name, menulevel2.menulevel2_id, menulevel2.post_id as menulevel2_post_id, menulevel2.menu_order as menulevel2_order, menulevel2.clicks as menulevel2_clicks, menulevel2.seo_keywords as menulevel2_seo_keywords, menulevel2.seo_description as menulevel2_seo_description, menulevel2.banner_image as menulevel2_banner_image, posts2.guid as menulevel2_guid,
					menulevel3.name as menu3_name, menulevel3.menulevel3_id, menulevel3.post_id as menulevel3_post_id, menulevel3.menu_order as menulevel3_order, menulevel3.clicks as menulevel3_clicks, menulevel3.seo_keywords as menulevel3_seo_keywords, menulevel3.seo_description as menulevel3_seo_description, menulevel3.banner_image as menulevel3_banner_image, posts3.guid as menulevel3_guid
					
					FROM ec_menulevel1 as menulevel1
					LEFT JOIN " . self::$mysqli->prefix . "posts as posts1 ON posts1.ID = menulevel1.post_id
					
					LEFT JOIN ec_menulevel2 as menulevel2 ON menulevel2.menulevel1_id = menulevel1.menulevel1_id 
					LEFT JOIN " . self::$mysqli->prefix . "posts as posts2 ON posts2.ID = menulevel2.post_id
					
					LEFT JOIN ec_menulevel3 as menulevel3 ON menulevel3.menulevel2_id = menulevel2.menulevel2_id 
					LEFT JOIN " . self::$mysqli->prefix . "posts as posts3 ON posts3.ID = menulevel3.post_id
					
					ORDER BY menulevel1.menu_order, menulevel1.menulevel1_id, menulevel2.menu_order, menulevel2.menulevel2_id, menulevel3.menu_order, menulevel3.menulevel3_id";
			$result = self::$mysqli->get_results($sql);
			wp_cache_set( 'wpeasycart-get-menu-items', $result, 'wpeasycart-menu' );
		}
		return $result;
	}
	
	public static function get_menulevel1_items( ){
		$sql = "SELECT menulevel1.name as menu1_name, menulevel1.menulevel1_id, menulevel1.post_id as menulevel1_post_id FROM ec_menulevel1 as menulevel1 ORDER BY menulevel1.menu_order";
		return self::$mysqli->get_results($sql);
	}
	
	public static function get_menulevel2_items( ){
		$sql = "SELECT menulevel2.menulevel1_id, menulevel2.name as menu2_name, menulevel2.menulevel2_id, menulevel2.post_id as menulevel2_post_id FROM ec_menulevel2 as menulevel2 ORDER BY menulevel2.menu_order";
		return self::$mysqli->get_results($sql);
	}
	
	public static function get_menulevel3_items( ){
		$sql = "SELECT menulevel3.menulevel2_id, menulevel3.name as menu3_name, menulevel3.menulevel3_id, menulevel3.post_id as menulevel3_post_id FROM ec_menulevel3 as menulevel3 ORDER BY menulevel3.menu_order";
		return self::$mysqli->get_results($sql);
	}
	
	public static function submit_customer_review( $product_id, $rating, $title, $description, $user_id = 0 ){
		$reviewer_name = '';
        if( $user_id != 0 ){
            $reviewer_name = $GLOBALS['ec_user']->first_name . ' ' . $GLOBALS['ec_user']->last_name;
        }
		self::$mysqli->insert( 	
			'ec_review', 
			array( 	'product_id' => $product_id, 
					'user_id' => $user_id, 
					'rating' => $rating, 
					'title' => $title, 
					'description' => $description,
                    'reviewer_name' => $reviewer_name
			), 
			array( '%d', '%d', '%d', '%s', '%s', '%s' )
		);
		$review_id = self::$mysqli->insert_id;
		do_action( 'wpeasycart_review_added', $review_id ); 
		
		if( get_option( 'ec_option_customer_review_notification' ) ){
			$headers   = array();
			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-Type: text/html; charset=utf-8";
			$headers[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
			$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
			$headers[] = "X-Mailer: PHP/" . phpversion( );

			$email_logo_url = get_option( 'ec_option_email_logo' );

			$storepageid = get_option('ec_option_storepage');
			if ( function_exists( 'icl_object_id' ) ) {
				$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			}
			$store_page = get_permalink( $storepageid );
			if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$store_page = $https_class->makeUrlHttps( $store_page );
			}

			if ( substr_count( $store_page, '?' ) ) {
				$permalink_divider = "&";
			} else {
				$permalink_divider = "?";
			}

			$review = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT ec_review.*, ec_product.title AS product_title, ec_user.email FROM ec_review LEFT JOIN ec_product ON ec_product.product_id = ec_review.product_id LEFT JOIN ec_user ON ec_user.user_id = ec_review.user_id WHERE ec_review.review_id = %d", $review_id ) );
			$review_admin_url = get_admin_url( ) . '/admin.php?page=wp-easycart-products&subpage=reviews&review_id=' . $review_id . '&ec_admin_form_action=edit';
			
			// Get receipt
			ob_start();
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_customer_review_notification.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_customer_review_notification.php' );	
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_customer_review_notification.php' );
			$message = ob_get_contents();
			ob_end_clean();
			
			if( get_option( 'ec_option_use_wp_mail' ) ){
				wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'email_title' ), $message, implode( "\r\n", $headers ) );
			}else{
				$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
				$subject = wp_easycart_language( )->get_text( 'ec_customer_review_notify_email', 'email_title' );
				$mailer = new wpeasycart_mailer( );
				$mailer->send_customer_email( $admin_email, $subject, $message );
			}
		}
		return true;
	}
	
	public static function get_category_items( &$level, $menu_id, $submenu_id, $subsubmenu_id ){
		$sql_level0 = "SELECT 
						ec_menulevel1.name as menu_name, 
						ec_menulevel1.menulevel1_id as menu_id,
						ec_menulevel1.post_id as post_id,
						" . self::$mysqli->prefix . "posts.guid
						
						FROM 
						ec_menulevel1
						
						LEFT JOIN " . self::$mysqli->prefix . "posts ON " . self::$mysqli->prefix . "posts.ID = ec_menulevel1.post_id
						 
						ORDER BY 
						ec_menulevel1.menu_order"; 
						
		$sql_level1 = "SELECT 
						ec_menulevel2.name as menu_name, 
						ec_menulevel2.menulevel2_id as menu_id, 
						ec_menulevel2.post_id as post_id,
						" . self::$mysqli->prefix . "posts.guid
						
						FROM ec_menulevel2 
						
						LEFT JOIN " . self::$mysqli->prefix . "posts ON " . self::$mysqli->prefix . "posts.ID = ec_menulevel2.post_id
						
						WHERE ec_menulevel2.menulevel1_id = %d 
						
						ORDER BY ec_menulevel2.menu_order";
						
		$sql_level2 = "SELECT 
						ec_menulevel3.name as menu_name, 
						ec_menulevel3.menulevel3_id as menu_id, 
						ec_menulevel3.post_id as post_id,
						" . self::$mysqli->prefix . "posts.guid
						
						FROM ec_menulevel3 
						
						LEFT JOIN " . self::$mysqli->prefix . "posts ON " . self::$mysqli->prefix . "posts.ID = ec_menulevel3.post_id
						
						WHERE ec_menulevel3.menulevel2_id = %d 
						
						ORDER BY ec_menulevel3.menu_order";
		
		$sql_get_menuid = "SELECT ec_menulevel2.menulevel1_id FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d";
		$sql_get_submenuid = "SELECT ec_menulevel3.menulevel2_id FROM ec_menulevel3 WHERE ec_menulevel3.menulevel3_id = %d";
		
		$role_where_add = ' AND ( ec_product.role_id = 0 OR ';
		if( $GLOBALS['ec_user']->role_id )
			$role_where_add .= self::$mysqli->prepare( 'ec_product.role_id = %d )', $GLOBALS['ec_user']->role_id );
		else
			$role_where_add .= 'ec_product.role_id = -1 )';
		
		if( $level == 0 ){
			$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_1 as menu_id, COUNT( ec_product.menulevel1_id_1 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 " . $role_where_add . " GROUP BY ec_product.menulevel1_id_1 ORDER BY ec_product.menulevel1_id_1" );
			$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_1 as menu_id, COUNT( ec_product.menulevel2_id_1 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 " . $role_where_add . " GROUP BY ec_product.menulevel2_id_1 ORDER BY ec_product.menulevel2_id_1" );
			$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_1 as menu_id, COUNT( ec_product.menulevel3_id_1 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 " . $role_where_add . " GROUP BY ec_product.menulevel3_id_1 ORDER BY ec_product.menulevel3_id_1" );
			$menus = self::$mysqli->get_results( $sql_level0 );
			
			$counts = array( );
			for( $i=0; $i<count( $products1 ); $i++ ){
				if( isset( $counts[$products1[$i]->menu_id] ) )
					$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
				else
					$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
			}
			for( $i=0; $i<count( $products2 ); $i++ ){
				if( isset( $counts[$products2[$i]->menu_id] ) )
					$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
				else
					$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
			}
			for( $i=0; $i<count( $products3 ); $i++ ){
				if( isset( $counts[$products3[$i]->menu_id] ) )
					$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
				else
					$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
			}
			
			for( $i=0; $i<count( $menus ); $i++ ){
				if( isset( $counts[$menus[$i]->menu_id] ) )
					$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
				else
					$menus[$i]->product_count = 0;
			}
			return $menus;
		
		}else if( $level == 1 ){
			$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_2 as menu_id, COUNT( ec_product.menulevel1_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel1_id_2 ORDER BY ec_product.menulevel1_id_2" );
			$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_2 as menu_id, COUNT( ec_product.menulevel2_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel2_id_2 ORDER BY ec_product.menulevel2_id_2" );
			$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_2 as menu_id, COUNT( ec_product.menulevel3_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel3_id_2 ORDER BY ec_product.menulevel3_id_2" );
			$menus = self::$mysqli->get_results( self::$mysqli->prepare( $sql_level1, $menu_id ) );
			
			$counts = array( );
			for( $i=0; $i<count( $products1 ); $i++ ){
				if( isset( $counts[$products1[$i]->menu_id] ) )
					$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
				else
					$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
			}
			for( $i=0; $i<count( $products2 ); $i++ ){
				if( isset( $counts[$products2[$i]->menu_id] ) )
					$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
				else
					$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
			}
			for( $i=0; $i<count( $products3 ); $i++ ){
				if( isset( $counts[$products3[$i]->menu_id] ) )
					$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
				else
					$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
			}
			for( $i=0; $i<count( $menus ); $i++ ){
				if( isset( $counts[$menus[$i]->menu_id] ) )
					$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
				else
					$menus[$i]->product_count = 0;
			}
			
			if( count( $menus ) > 0 )
				return $menus;
			else{
				$level = 0;
				$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_1 as menu_id, COUNT( ec_product.menulevel1_id_1 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel1_id_1 ORDER BY ec_product.menulevel1_id_1" );
				$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_1 as menu_id, COUNT( ec_product.menulevel2_id_1 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel2_id_1 ORDER BY ec_product.menulevel2_id_1" );
				$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_1 as menu_id, COUNT( ec_product.menulevel3_id_1 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel3_id_1 ORDER BY ec_product.menulevel3_id_1" );
				$menus = self::$mysqli->get_results( $sql_level0 );
				
				$counts = array( );
				for( $i=0; $i<count( $products1 ); $i++ ){
					if( isset( $counts[$products1[$i]->menu_id] ) )
						$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
					else
						$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
				}
				for( $i=0; $i<count( $products2 ); $i++ ){
					if( isset( $counts[$products2[$i]->menu_id] ) )
						$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
					else
						$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
				}
				for( $i=0; $i<count( $products3 ); $i++ ){
					if( isset( $counts[$products3[$i]->menu_id] ) )
						$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
					else
						$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
				}
				for( $i=0; $i<count( $menus ); $i++ ){
					if( isset( $counts[$menus[$i]->menu_id] ) )
						$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
					else
						$menus[$i]->product_count = 0;
				}
				return $menus;
			}
				
		}else if( $level == 2 ){ 
			$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_3 as menu_id, COUNT( ec_product.menulevel1_id_3 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel1_id_3 ORDER BY ec_product.menulevel1_id_3" );
			$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_3 as menu_id, COUNT( ec_product.menulevel2_id_3 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel2_id_3 ORDER BY ec_product.menulevel2_id_3" );
			$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_3 as menu_id, COUNT( ec_product.menulevel3_id_3 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel3_id_3 ORDER BY ec_product.menulevel3_id_3" );
			$menus = self::$mysqli->get_results( self::$mysqli->prepare( $sql_level2, $submenu_id ) );
			
			$counts = array( );
			for( $i=0; $i<count( $products1 ); $i++ ){
				if( isset( $counts[$products1[$i]->menu_id] ) )
					$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
				else
					$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
			}
			for( $i=0; $i<count( $products2 ); $i++ ){
				if( isset( $counts[$products2[$i]->menu_id] ) )
					$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
				else
					$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
			}
			for( $i=0; $i<count( $products3 ); $i++ ){
				if( isset( $counts[$products3[$i]->menu_id] ) )
					$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
				else
					$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
			}
			for( $i=0; $i<count( $menus ); $i++ ){
				if( isset( $counts[$menus[$i]->menu_id] ) )
					$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
				else
					$menus[$i]->product_count = 0;
			}
			
			if( count( $menus ) > 0 )
				return $menus;
			else{
				$level = 1;
				$menu_id = self::$mysqli->get_var( self::$mysqli->prepare( $sql_get_menuid, $submenu_id ) );
				$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_2 as menu_id, COUNT( ec_product.menulevel1_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel1_id_2 ORDER BY ec_product.menulevel1_id_2" );
				$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_2 as menu_id, COUNT( ec_product.menulevel2_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel2_id_2 ORDER BY ec_product.menulevel2_id_2" );
				$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_2 as menu_id, COUNT( ec_product.menulevel3_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel3_id_2 ORDER BY ec_product.menulevel3_id_2" );
				$menus = self::$mysqli->get_results( self::$mysqli->prepare( $sql_level1, $menu_id ) );
				
				$counts = array( );
				for( $i=0; $i<count( $products1 ); $i++ ){
					if( isset( $counts[$products1[$i]->menu_id] ) )
						$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
					else
						$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
				}
				for( $i=0; $i<count( $products2 ); $i++ ){
					if( isset( $counts[$products2[$i]->menu_id] ) )
						$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
					else
						$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
				}
				for( $i=0; $i<count( $products3 ); $i++ ){
					if( isset( $counts[$products3[$i]->menu_id] ) )
						$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
					else
						$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
				}
				for( $i=0; $i<count( $menus ); $i++ ){
					if( isset( $counts[$menus[$i]->menu_id] ) )
						$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
					else
						$menus[$i]->product_count = 0;
				}
				return $menus;
			}
			
		}else if( $level == 3 ){
			$level = 2;
			$submenu_id = self::$mysqli->get_var( self::$mysqli->prepare( $sql_get_submenuid, $subsubmenu_id ) );
			$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_3 as menu_id, COUNT( ec_product.menulevel1_id_3 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel1_id_3 ORDER BY ec_product.menulevel1_id_3" );
			$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_3 as menu_id, COUNT( ec_product.menulevel2_id_3 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel2_id_3 ORDER BY ec_product.menulevel2_id_3" );
			$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_3 as menu_id, COUNT( ec_product.menulevel3_id_3 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel3_id_3 ORDER BY ec_product.menulevel3_id_3" );
			$menus = self::$mysqli->get_results( self::$mysqli->prepare( $sql_level2, $submenu_id ) );
			
			$counts = array( );
			for( $i=0; $i<count( $products1 ); $i++ ){
				if( isset( $counts[$products1[$i]->menu_id] ) )
					$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
				else
					$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
			}
			for( $i=0; $i<count( $products2 ); $i++ ){
				if( isset( $counts[$products2[$i]->menu_id] ) )
					$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
				else
					$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
			}
			for( $i=0; $i<count( $products3 ); $i++ ){
				if( isset( $counts[$products3[$i]->menu_id] ) )
					$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
				else
					$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
			}
			for( $i=0; $i<count( $menus ); $i++ ){
				if( isset( $counts[$menus[$i]->menu_id] ) )
					$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
				else
					$menus[$i]->product_count = 0;
			}
			
			if( count( $menus ) > 0 )
				return $menus;
			else{
				$level = 1;
				$menuid = self::$mysqli->get_var( self::$mysqli->prepare( $sql_get_menuid, $submenu_id ) );
				$products1 = self::$mysqli->get_results( "SELECT ec_product.menulevel1_id_2 as menu_id, COUNT( ec_product.menulevel1_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel1_id_2 ORDER BY ec_product.menulevel1_id_2" );
				$products2 = self::$mysqli->get_results( "SELECT ec_product.menulevel2_id_2 as menu_id, COUNT( ec_product.menulevel2_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel2_id_2 ORDER BY ec_product.menulevel2_id_2" );
				$products3 = self::$mysqli->get_results( "SELECT ec_product.menulevel3_id_2 as menu_id, COUNT( ec_product.menulevel3_id_2 ) as product_count FROM ec_product WHERE ec_product.activate_in_store = 1 GROUP BY ec_product.menulevel3_id_2 ORDER BY ec_product.menulevel3_id_2" );
				$menus = self::$mysqli->get_results( self::$mysqli->prepare( $sql_level1, $menu_id ) );
				
				$counts = array( );
				for( $i=0; $i<count( $products1 ); $i++ ){
					if( isset( $counts[$products1[$i]->menu_id] ) )
						$counts[$products1[$i]->menu_id] = $counts[$products1[$i]->menu_id] + $products1[$i]->product_count;
					else
						$counts[$products1[$i]->menu_id] = $products1[$i]->product_count;
				}
				for( $i=0; $i<count( $products2 ); $i++ ){
					if( isset( $counts[$products2[$i]->menu_id] ) )
						$counts[$products2[$i]->menu_id] = $counts[$products2[$i]->menu_id] + $products2[$i]->product_count;
					else
						$counts[$products2[$i]->menu_id] = $products2[$i]->product_count;
				}
				for( $i=0; $i<count( $products3 ); $i++ ){
					if( isset( $counts[$products3[$i]->menu_id] ) )
						$counts[$products3[$i]->menu_id] = $counts[$products3[$i]->menu_id] + $products3[$i]->product_count;
					else
						$counts[$products3[$i]->menu_id] = $products3[$i]->product_count;
				}
				for( $i=0; $i<count( $menus ); $i++ ){
					if( isset( $counts[$menus[$i]->menu_id] ) )
						$menus[$i]->product_count = $counts[$menus[$i]->menu_id];
					else
						$menus[$i]->product_count = 0;
				}
				return $menus;
			}
		}
	}
	
	public static function get_promotions( ){
		$promotions = wp_cache_get( 'wpeasycart-promotions' );
		if( !$promotions ){
			$promotions = self::$mysqli->get_results( "SELECT 
											ec_promotion.promotion_id, ec_promotion.type as promotion_type, ec_promotion.name as promotion_name,
											ec_promotion.start_date, ec_promotion.end_date, 
											ec_promotion.product_id_1, ec_promotion.product_id_2, ec_promotion.product_id_3, 
											ec_promotion.manufacturer_id_1, ec_promotion.manufacturer_id_2, ec_promotion.manufacturer_id_3, 
											ec_promotion.category_id_1, ec_promotion.category_id_2, ec_promotion.category_id_3, 
											ec_promotion.price1, ec_promotion.price2, ec_promotion.price3, 
											ec_promotion.percentage1, ec_promotion.percentage2, ec_promotion.percentage3, 
											ec_promotion.number1, ec_promotion.number2, ec_promotion.number3, 
											ec_promotion.promo_limit as product_limit,
											NOW() as currdate
											
											FROM ec_promotion 
											HAVING ec_promotion.start_date <= currdate AND ec_promotion.end_date >= currdate" );
			if( count( $promotions ) == 0 )
				$promotions = "EMPTY";
			wp_cache_set( 'wpeasycart-promotions', $promotions );
		}
		if( $promotions == "EMPTY" )
			return array( );
		return $promotions;
	}
	
	public static function has_category_match( $category_id, $product_id ){
		$count = self::$mysqli->get_var( self::$mysqli->prepare( "SELECT COUNT( categoryitem_id ) FROM ec_categoryitem WHERE category_id = %d AND product_id = %d", $category_id, $product_id ) );
		if( $count > 0 )
			return true;
		else
			return false;
	}
	
	public static function add_to_cart( $product_id, $session_id, $quantity, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5, $gift_card_message="", $gift_card_to_name="", $gift_card_from_name="", $donation_price=0.00, $use_advanced_optionset=false, $return_tempcart=1, $gift_card_email="" ){
		
		// Get the limit on this product
		$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
		$product_sql = "SELECT stock_quantity, use_optionitem_quantity_tracking, show_stock_quantity, allow_backorders, max_purchase_quantity, min_purchase_quantity, is_donation FROM ec_product WHERE product_id = %d";
		$optionitem_sql = "SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d";
		$tempcart_optionitem_sql = "SELECT quantity FROM ec_tempcart WHERE session_id = '%s' AND product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d";
		$tempcart_optionitem_other_sql = "SELECT quantity FROM ec_tempcart WHERE session_id != '%s' AND product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d AND last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' );
		
		$tempcart_sql = "SELECT SUM(quantity) as quantity FROM ec_tempcart WHERE session_id = '%s' AND product_id = %d";
		$tempcart_other_sql = "SELECT SUM(quantity) as quantity FROM ec_tempcart WHERE session_id != '%s' AND product_id = %d AND last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' );
		
		$stock_quantity = 99999999999; // very large limit... nearly infinite really
		
		//Get this product stock quantity and use_optionitem_quantity tracking
		$product = self::$mysqli->get_row( self::$mysqli->prepare( $product_sql, $product_id ) );
		//Get this tempcart item quantity
		$tempcart_optionitem = self::$mysqli->get_row( self::$mysqli->prepare( $tempcart_optionitem_sql, $session_id, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 ) );
		//Get this tempcart total quantity
		$tempcart = self::$mysqli->get_row( self::$mysqli->prepare( $tempcart_sql, $session_id, $product_id ) );
		
		$optionitem_quantity = self::$mysqli->get_row( self::$mysqli->prepare( $optionitem_sql, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 ) );
		
		if( $product->use_optionitem_quantity_tracking == 1 && $optionitem_quantity ){
			if ( $optionitem_quantity->is_stock_tracking_enabled ) {
				$stock_quantity = $optionitem_quantity->quantity;
			}
			if( get_option( 'ec_option_stock_removed_in_cart' ) ){
				$tempcart_opt_item_quantity = self::$mysqli->get_var( self::$mysqli->prepare( $tempcart_optionitem_other_sql, $session_id, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5, $hours ) );
				$stock_quantity -= $tempcart_opt_item_quantity;
			}
			
		}else if( isset( $product->show_stock_quantity ) && $product->show_stock_quantity == 1 ){
			$stock_quantity = $product->stock_quantity;
			if( get_option( 'ec_option_stock_removed_in_cart' ) ){
				$tempcart_other_quantity = self::$mysqli->get_var( self::$mysqli->prepare( $tempcart_other_sql, $session_id, $product_id, $hours ) );
				$stock_quantity -= $tempcart_other_quantity;
			}
			
		}else{
			$stock_quantity = 1000000;
		}
		
		if( $product->allow_backorders )
			$stock_quantity = 1000000;
		
		if( $gift_card_message != "" || $gift_card_from_name != "" || $gift_card_from_name != "" || $gift_card_email != "" ){
			// Do nothing, use quantity entered.
			
		// OPTION ITEM QUANTITY TRACKING AND ENTERED QUANITTY GOES OVER ITEM LIMIT
		// IF    1. Using advanced option items
		//		 2. using basic quantity tracking
		//       2. quantity in cart + new quantity is greater than the amount in stock
		// THEN     use max for that option item set
		}else if( $use_advanced_optionset && isset( $product->show_stock_quantity ) && $quantity + $tempcart->quantity > $stock_quantity ){
			$quantity = $stock_quantity - $tempcart->quantity;
			if( $quantity == 0 ){
				return 0;
			}
			
		// OPTION ITEM QUANTITY TRACKING AND ENTERED QUANITTY GOES OVER ITEM LIMIT
		// IF    1. using advanced option items
		//       2. quantity must not be exceding stock quantity OR we are not tracking it for this product
		// THEN     use the actual quantity value
		}else if( $use_advanced_optionset || $product->is_donation ){
			// Do nothing to the quantity value
			
		//Get the quantity for the new tempcart item (insert or update)
		// OPTION ITEM QUANTITY TRACKING AND ENTERED QUANITTY GOES OVER ITEM LIMIT
		// IF    1. using option item quantity tracking
		//       2. quantity + item in cart with same options quantity > amount available for this option
		// THEN     use max for that option item set
		}else if( $product->use_optionitem_quantity_tracking == 1 && isset( $tempcart_optionitem ) && $quantity + $tempcart_optionitem->quantity > $stock_quantity ){
			$quantity = $stock_quantity;
		
		// OPTION ITEM QUANTITY TRACKING AND AMOUNT ENTERED IS TOO MUCH
		// IF    1. using option item quanitty tracking
		//       2. item with theme options is not in the cart yet
		// THEN     use the quantity entered by the user
		}else if( $product->use_optionitem_quantity_tracking == 1 && $quantity > $stock_quantity ){
			$quantity = $stock_quantity;
		
		
		// OIQT + Valid Quantity -- Add the quantities up for updating the item
		}else if( $product->use_optionitem_quantity_tracking == 1 && isset( $tempcart_optionitem )  ){
			$quantity = $quantity + $tempcart_optionitem->quantity;
		
		// OIQT + Valid quantity and none in cart, use value entered
		}else if( $product->use_optionitem_quantity_tracking == 1 ){
			
		// BASIC QUANTITY TRACKING and THIS OPTION CHOICE IS IN CART and ENTERED QUANTITY + ALL QUANITY OF SAME PROD IDS MORE THAN QUANTITY IN STOCK
		// IF    1. using general quantity tracking
		//       2. quantity + the quantity of all items with same product id > amount available
		// THEN     use the total in stock - the total in cart so far
		}else if( isset( $product->show_stock_quantity ) && isset( $tempcart_optionitem ) && $quantity + $tempcart->quantity > $stock_quantity ){			
			$quantity = $stock_quantity - $tempcart->quantity + $tempcart_optionitem->quantity;
		
		// BASIC QUANTITY TRACKING and THIS OPTION CHOICE IS NOT IN CART and ENTERED QUANTITY + ALL QUANITY OF SAME PROD IDS MORE THAN QUANTITY IN STOCK
		// IF    1. using general quantity tracking
		//       2. quantity + the quantity of all items with same product id > amount available
		// THEN     use the total in stock - the total in cart so far
		}else if( isset( $product->show_stock_quantity ) && $quantity + $tempcart->quantity > $stock_quantity ){			
			$quantity = $stock_quantity - $tempcart->quantity;
		
		// BASIC QUANTITY TRACKING and THIS OPTION CHOICE IS IN CART
		// IF    1. using general quantity tracking
		//       2. quantity + the quantity of all items with same product id > amount available
		// THEN     use the total in stock - the total in cart so far
		}else if( isset( $product->show_stock_quantity ) && isset( $tempcart_optionitem ) ){			
			$quantity = $quantity + $tempcart_optionitem->quantity;	
		
		// BASIC QUANTITY TRACKING and THIS OPTION CHOICE IS NOT IN CART
		// IF    1. using general quantity tracking
		//       2. quantity + the quantity of all items with same product id > amount available
		// THEN     use the total in stock - the total in cart so far
		}else if( isset( $product->show_stock_quantity ) ){			
			// USE QUANTITY ENTERED
		
		// NO QUANITTY TRACKING
		// THEN     use the quantity entered + the quantity for this option item in cart
		}else{																		
			if( isset( $tempcart_optionitem ) )
				$quantity = $quantity + $tempcart_optionitem->quantity;
		}
		
		// First check for an item with the same option items IF NOT a gift card
		$sql = "SELECT COUNT(tempcart_id) AS total_rows FROM ec_tempcart WHERE session_id = '%s' AND product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d";
		$insert = self::$mysqli->get_var( self::$mysqli->prepare( $sql, $session_id, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 ) );
		
		if( $use_advanced_optionset || $product->is_donation || $gift_card_message != "" || $gift_card_from_name != "" || $gift_card_to_name != "" || ( $insert == 0 && $quantity > 0 ) || apply_filters( 'wp_easycart_add_to_cart_optional_always_add', false, $product_id ) ){
			
			if( $quantity < $product->min_purchase_quantity && $product->min_purchase_quantity != 0 )
				$quantity = $product->min_purchase_quantity;
				
			if( $quantity > $product->max_purchase_quantity && $product->max_purchase_quantity != 0 )
				$quantity = $product->max_purchase_quantity;
				
			self::$mysqli->insert( 'ec_tempcart', 
										array( 	'product_id' 					=> $product_id,
												'session_id' 					=> $session_id, 
												'quantity'						=> apply_filters( 'wp_easycart_add_to_cart_quantity', $quantity, $product_id ),
												'optionitem_id_1' 				=> $optionitem_id_1, 
												'optionitem_id_2' 				=> $optionitem_id_2, 
												'optionitem_id_3'				=> $optionitem_id_3, 
												'optionitem_id_4' 				=> $optionitem_id_4, 
												'optionitem_id_5' 				=> $optionitem_id_5, 
												'gift_card_message' 			=> $gift_card_message, 
												'gift_card_from_name' 			=> $gift_card_from_name, 
												'gift_card_to_name' 			=> $gift_card_to_name,
												'gift_card_email' 				=> $gift_card_email,
												'donation_price'				=> $donation_price
										), 
										array( '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
									);							
		}else if($insert != 0){
			
			if( $quantity < $product->min_purchase_quantity && $product->min_purchase_quantity != 0 )
				$quantity = $product->min_purchase_quantity;
				
			if( $quantity > $product->max_purchase_quantity && $product->max_purchase_quantity != 0 )
				$quantity = $product->max_purchase_quantity;
				
			self::$mysqli->update(	'ec_tempcart', 
										array(	'quantity'						=> $quantity ),
										array( 	'product_id' 					=> $product_id,
												'session_id' 					=> $session_id, 
												'optionitem_id_1' 				=> $optionitem_id_1, 
												'optionitem_id_2' 				=> $optionitem_id_2, 
												'optionitem_id_3'				=> $optionitem_id_3, 
												'optionitem_id_4' 				=> $optionitem_id_4, 
												'optionitem_id_5' 				=> $optionitem_id_5
										), 
										array( '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%d' )
								  );	
		}
		
		if( $return_tempcart )
			return self::get_temp_cart( $session_id );
		else
			return self::$mysqli->insert_id;
	}
	
	public static function update_cartitem( $tempcart_id, $session_id, $quantity ){
		
		if( $quantity <= 0 ){ // If someone tries to update to 0 or less, just remove it from the cart
			
			self::delete_cartitem( $tempcart_id, $session_id );
			
		}else{
		
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			$tempcart_item_sql = "SELECT product_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, optionitem_id_5 FROM ec_tempcart WHERE tempcart_id = %d";
			$tempcart_item = self::$mysqli->get_row( self::$mysqli->prepare( $tempcart_item_sql, $tempcart_id ) );
			$product_id = $tempcart_item->product_id;
			$optionitem_id_1 = $tempcart_item->optionitem_id_1;
			$optionitem_id_2 = $tempcart_item->optionitem_id_2;
			$optionitem_id_3 = $tempcart_item->optionitem_id_3;
			$optionitem_id_4 = $tempcart_item->optionitem_id_4;
			$optionitem_id_5 = $tempcart_item->optionitem_id_5;
			
			// Get the limit on this product
			$product_sql = "SELECT show_stock_quantity, stock_quantity, use_optionitem_quantity_tracking, min_purchase_quantity, max_purchase_quantity, allow_backorders FROM ec_product WHERE product_id = %d";
			$optionitem_sql = "SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d";
			
			$tempcart_optionitem_sql = "SELECT quantity FROM ec_tempcart WHERE tempcart_id = %d";
			$tempcart_optionitem_other_sql = "SELECT quantity FROM ec_tempcart WHERE session_id != '%s' AND product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d AND last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' );
			
			$tempcart_sql = "SELECT SUM(quantity) as quantity FROM ec_tempcart WHERE session_id = '%s' AND product_id = %d";
			$tempcart_other_sql = "SELECT SUM(quantity) as quantity FROM ec_tempcart WHERE session_id != '%s' AND product_id = %d AND last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' );
			
			$stock_quantity = 99999999999; // very large limit... nearly infinite really
			
			//Get this product stock quantity and use_optionitem_quantity tracking
			$product = self::$mysqli->get_row( self::$mysqli->prepare( $product_sql, $product_id ) );
			
			//Get this tempcart item quantity
			$tempcart_optionitem = self::$mysqli->get_row( self::$mysqli->prepare( $tempcart_optionitem_sql, $tempcart_id ) );
			
			//Get this tempcart total quantity
			$tempcart = self::$mysqli->get_row( self::$mysqli->prepare( $tempcart_sql, $session_id, $product_id ) );
			
			//Get the maximum for this option item
			$optionitem_quantity = self::$mysqli->get_row( self::$mysqli->prepare( $optionitem_sql, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 ) );
			
			if ( $product->use_optionitem_quantity_tracking == 1 ) {
				if ( $optionitem_quantity->is_stock_tracking_enabled ) {
					$stock_quantity = $optionitem_quantity->quantity;
				}
				if( get_option( 'ec_option_stock_removed_in_cart' ) ){
					$tempcart_opt_item_quantity = self::$mysqli->get_var( self::$mysqli->prepare( $tempcart_optionitem_other_sql, $session_id, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5, $hours ) );
					$stock_quantity -= $tempcart_opt_item_quantity;
				}
				
			} else if( $product->show_stock_quantity == 1 ) {
				$stock_quantity = $product->stock_quantity;
				if( get_option( 'ec_option_stock_removed_in_cart' ) ){
					$tempcart_other_quantity = self::$mysqli->get_var( self::$mysqli->prepare( $tempcart_other_sql, $session_id, $product_id, $hours ) );
					$stock_quantity -= $tempcart_other_quantity;
				}
				
			} else {
				$stock_quantity = 1000000;
			}
			
			if( $product->allow_backorders )
				$stock_quantity = 1000000;
			
			if( $product->use_optionitem_quantity_tracking == 1 ){
				if( $quantity > $stock_quantity ){			
					$quantity = $stock_quantity;
				}
				
			}else if( $product->show_stock_quantity && $product->stock_quantity <= 0 && $product->allow_backorders ){
				// Do not worry about amount in cart as it will be backordered
			
			}else if( $product->show_stock_quantity == 1 && $tempcart->quantity + $quantity - $tempcart_optionitem->quantity > $stock_quantity ){			
				$quantity = $stock_quantity - ( $tempcart->quantity - $tempcart_optionitem->quantity );
			
			}else if( $product->show_stock_quantity == 1 && $quantity > $stock_quantity ){			
				$quantity = $stock_quantity;
			
			}
			
			// Don't allow less than the minimum
			if( $product->min_purchase_quantity > 0 && $quantity < $product->min_purchase_quantity ){
				$quantity = $product->min_purchase_quantity;
			}
			
			// Don't allow more than the maximum
			if( $product->max_purchase_quantity > 0 && $quantity > $product->max_purchase_quantity ){
				$quantity = $product->max_purchase_quantity;
			}
			
			// Don't allow negative quantities!
			if( $quantity < 0 )
				$quantity = 0;
			
			$quantity = apply_filters( 'wp_easycart_update_item_quantity', $quantity, $tempcart_id, $session_id, $product_id ); 
			
			$sql = "UPDATE ec_tempcart SET quantity = %d WHERE tempcart_id = %d AND session_id = %s";
			self::$mysqli->query( self::$mysqli->prepare( $sql, $quantity, $tempcart_id, $session_id ) );
		
		}
							
	}
	
	public static function delete_cartitem( $tempcart_id, $session_id ){
		
		// Maybe Delete Customer File
		$upload_path =  EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/' . $tempcart_id . '/';
		if( is_dir( $upload_path ) ){
			$files = scandir( $upload_path );
			foreach( $files as $file ){
				if( !is_dir( $upload_path . $file ) && file_exists( $upload_path . $file ) ){
					unlink( $upload_path . $file );
				}
			}
			rmdir( $upload_path );
		}
		
		$sql = "DELETE FROM ec_tempcart WHERE ec_tempcart.tempcart_id = '%s' AND ec_tempcart.session_id = '%s'";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $tempcart_id, $session_id ) );
		self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart_optionitem WHERE ec_tempcart_optionitem.tempcart_id = %d", $tempcart_id ) );
		
        do_action( 'wp_easycart_cartitem_deleted', $tempcart_id, $session_id );
        
		$sql = "SELECT 

				SUM( ec_tempcart.quantity ) as quantity,
				
				SUM( 
					( ec_product.price + 
					  IFNULL(optionitem1.optionitem_price, 0) + 
					  IFNULL(optionitem2.optionitem_price, 0) + 
					  IFNULL(optionitem3.optionitem_price, 0) + 
					  IFNULL(optionitem4.optionitem_price, 0) + 
					  IFNULL(optionitem5.optionitem_price, 0) 
					) * ec_tempcart.quantity
				) as total_price 
				
				FROM 
				ec_tempcart 
				
				LEFT JOIN ec_product 
				ON ec_product.product_id = ec_tempcart.product_id 
				
				LEFT JOIN ec_optionitem as `optionitem1`
				ON optionitem1.`optionitem_id` = ec_tempcart.`optionitem_id_1`
				
				LEFT JOIN ec_optionitem as `optionitem2`
				ON optionitem2.`optionitem_id` = ec_tempcart.`optionitem_id_2`
				
				LEFT JOIN ec_optionitem as `optionitem3`
				ON optionitem3.`optionitem_id` = ec_tempcart.`optionitem_id_3`
				
				LEFT JOIN ec_optionitem as `optionitem4`
				ON optionitem4.`optionitem_id` = ec_tempcart.`optionitem_id_4`
				
				LEFT JOIN ec_optionitem as `optionitem5`
				ON optionitem5.`optionitem_id` = ec_tempcart.`optionitem_id_5`
				
				WHERE session_id = '%s'";
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $session_id ) );
	}
	
	public static function get_shipping_data( ){
		$shipping_rows = wp_cache_get( 'wpeasycart-shipping-data', 'wpeasycart-shipping' );
		if( !$shipping_rows ){
			$sql = "SELECT ec_shippingrate.shippingrate_id, ec_shippingrate.zone_id, ec_shippingrate.is_price_based, ec_shippingrate.is_weight_based, ec_shippingrate.is_method_based, ec_shippingrate.is_quantity_based, ec_shippingrate.is_percentage_based, ec_shippingrate.is_ups_based, ec_shippingrate.is_usps_based, ec_shippingrate.is_fedex_based, ec_shippingrate.is_auspost_based, ec_shippingrate.is_dhl_based, ec_shippingrate.is_canadapost_based, ec_shippingrate.trigger_rate, ec_shippingrate.shipping_rate, ec_shippingrate.shipping_label, ec_shippingrate.shipping_order, ec_shippingrate.shipping_code, ec_shippingrate.shipping_override_rate, ec_shippingrate.free_shipping_at FROM ec_shippingrate ORDER BY ec_shippingrate.is_price_based DESC, ec_shippingrate.is_weight_based DESC, ec_shippingrate.is_method_based DESC, ec_shippingrate.is_quantity_based DESC, ec_shippingrate.trigger_rate DESC, ec_shippingrate.trigger_rate DESC, ec_shippingrate.zone_id DESC, ec_shippingrate.shipping_order";
			$shipping_rows = self::$mysqli->get_results( $sql );
			wp_cache_set( 'wpeasycart-shipping-data', $shipping_rows, 'wpeasycart-shipping' );
		}
		return $shipping_rows;
	}
	
	public static function redeem_coupon_code( $couponcode ){
		$sql = "SELECT 
				ec_promocode.promocode_id,
				ec_promocode.is_dollar_based, 
				ec_promocode.is_percentage_based, 
				ec_promocode.is_shipping_based, 
				ec_promocode.is_free_item_based, 
				ec_promocode.is_for_me_based, 
				ec_promocode.by_manufacturer_id,
				ec_promocode.by_category_id,
				ec_promocode.by_product_id, 
				ec_promocode.by_all_products, 
				ec_promocode.promo_dollar, 
				ec_promocode.promo_percentage, 
				ec_promocode.promo_shipping, 
				ec_promocode.promo_free_item, 
				ec_promocode.promo_for_me, 
				ec_promocode.manufacturer_id,
				ec_promocode.category_id,
				ec_promocode.product_id,
				ec_promocode.message,
				ec_promocode.max_redemptions,
				ec_promocode.times_redeemed,
				IF( ec_promocode.expiration_date < NOW( ), 1, 0 ) as coupon_expired
				
				FROM 
				ec_promocode 
				
				WHERE 
				ec_promocode.promocode_id = %s";
				
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $couponcode ) );	
	}
	
	public static function redeem_gift_card( $giftcardcode ){
		if( $giftcardcode == "" )
			return false;
		
		$sql = "SELECT 
				ec_giftcard.amount, 
				ec_giftcard.message 
				
				FROM 
				ec_giftcard
				
				WHERE 
				ec_giftcard.giftcard_id = '%s'";
				
		$giftcard = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $giftcardcode ) );	
		return apply_filters( 'wpeasycart_get_gift_card_balance', $giftcard, $giftcardcode );
	}
	
	public static function update_giftcard_total( $giftcard_code, $giftcard_discount ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_giftcard SET amount = amount - '%s' WHERE giftcard_id = '%s'", $giftcard_discount, $giftcard_code ) );
	}
	
	public static function get_taxrates() {
		$taxrates = wp_cache_get( 'wpeasycart-tax-rates' );
		if ( ! $taxrates ) {
			$sql = "SELECT taxrate_id, tax_by_state, tax_by_country, tax_by_duty, tax_by_vat, tax_by_single_vat, tax_by_all, state_rate, country_rate, duty_rate, vat_rate, vat_added, vat_included, all_rate, state_code, country_code,vat_country_code, duty_exempt_country_code, stripe_taxrate_id FROM ec_taxrate ORDER BY tax_by_vat, tax_by_single_vat, tax_by_duty, tax_by_all, tax_by_country, tax_by_state";
			$taxrates = self::$mysqli->get_results( $sql );
			if( count( $taxrates ) == 0 )
				$taxrates = "EMPTY";
			wp_cache_set( 'wpeasycart-tax-rates', $taxrates );
		}
		if ( $taxrates == "EMPTY" ) {
			return array();
		}
		return $taxrates;
	}

	public static function get_fees() {
		$fees = wp_cache_get( 'wpeasycart-fees' );
		if ( ! $fees ) {
			$fees = self::$mysqli->get_results( 'SELECT * FROM ec_fee ORDER BY fee_id ASC' );
			if ( 0 == count( $fees ) ) {
				$fees = 'EMPTY';
			}
			wp_cache_set( 'wpeasycart-fees', $fees );
		}
		if ( 'EMPTY' == $fees ) {
			return array();
		}
		return $fees;
	}

	public static function get_registration_code( ){
		$sql = "SELECT ec_setting.reg_code FROM ec_setting WHERE ec_setting.setting_id = 1";
		return self::$mysqli->get_var( $sql );
	}
	
	public static function install( $install_sql_array ){
		if( !self::$mysqli->get_var( "show tables like 'ec_product'" ) ){
		
			foreach( $install_sql_array as $stmt ){
				if( strlen( $stmt ) > 3 ){
					self::$mysqli->query( $stmt );
				}
			}
		
		}
		return true;
	} 
	
	public static function upgrade( $upgrade_sql_array ){
		foreach( $upgrade_sql_array as $stmt ){
			if( strlen( $stmt ) > 3 ){
				self::$mysqli->query( $stmt );
			}
		}
		return true;
	} 
	
	public static function uninstall( $install_sql_array ){
		foreach( $install_sql_array as $stmt ){
			if( strlen( $stmt ) > 3 ){
				self::$mysqli->query( $stmt );
			}
		}
		return true;
	} 
	
	public static function update_url( $site_url ){
		//First check if tables exists
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_setting SET site_url = '%s'", $site_url ) );
	}
	
	public static function get_shipping_method_name( $ship_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT shipping_label FROM ec_shippingrate WHERE shippingrate_id = '%s'", $ship_id ) );	
	}
	
	public static function do_quantity_check( $cart ){
		$stock_valid = true;
		foreach ( $cart->cart as $cart_item ) {
			if ( ! $cart_item->allow_backorders ) {
				$product_sql = 'SELECT stock_quantity, use_optionitem_quantity_tracking, show_stock_quantity, allow_backorders, max_purchase_quantity, min_purchase_quantity FROM ec_product WHERE product_id = %d';
				$optionitem_sql = 'SELECT * FROM ec_optionitemquantity WHERE product_id = %d AND optionitem_id_1 = %d AND optionitem_id_2 = %d AND optionitem_id_3 = %d AND optionitem_id_4 = %d AND optionitem_id_5 = %d';
				$product = self::$mysqli->get_row( self::$mysqli->prepare( $product_sql, $cart_item->product_id ) );
				$optionitem_quantity = self::$mysqli->get_row( self::$mysqli->prepare( $optionitem_sql, $cart_item->product_id, $cart_item->optionitem1_id, $cart_item->optionitem2_id, $cart_item->optionitem3_id, $cart_item->optionitem4_id, $cart_item->optionitem5_id ) );
				if ( 1 == $product->use_optionitem_quantity_tracking && $optionitem_quantity && $optionitem_quantity->is_stock_tracking_enabled && $optionitem_quantity->quantity < $cart_item->quantity ) {
					if ( $optionitem_quantity->quantity > 0 ) {
						self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_tempcart SET quantity = %d WHERE tempcart_id = %d", $optionitem_quantity->quantity, $cart_item->cartitem_id ) );
					} else {
						self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart WHERE tempcart_id = %d", $cart_item->cartitem_id ) );
					}
					$stock_valid = false;
				
				} else if ( isset( $product->show_stock_quantity ) && isset( $optionitem_quantity->is_stock_tracking_enabled ) && ! $optionitem_quantity->is_stock_tracking_enabled && $product->show_stock_quantity == 1 && $product->stock_quantity < $cart_item->quantity ) {
					if ( $product->stock_quantity > 0 ) {
						self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_tempcart SET quantity = %d WHERE tempcart_id = %d", $product->stock_quantity, $cart_item->cartitem_id ) );
					} else {
						self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart WHERE tempcart_id = %d", $cart_item->cartitem_id ) );
					}
					$stock_valid = false;
				}
			}
		}
		
		return $stock_valid;
	}
	
	public static function insert_order( $cart, $user, $shipping, $tax, $discount, $order_totals, $payment, $payment_type, $orderstatus_id, $order_notes, $order_gateway ){
		
		if ( ! self::do_quantity_check( $cart ) ) {
			return -1;
		}
		
		// Get Payment Method to Save
		if ( $payment_type == "manual_bill" ) {
			$payment_type = "manual_bill";
		} else if( $payment_type == "third_party" ) {
			$payment_type = get_option( 'ec_option_payment_third_party' );
		} else if( $payment_type == "amazonpay" ) {
			$payment_type = esc_attr( $GLOBALS['ec_cart_data']->cart_data->amazon_payment_selection );
		}

		if( $payment_type != "manual_bill" && $payment_type != "third_party" && $payment_type != "amazonpay" ){
			$card_holder_name = $payment->credit_card->card_holder_name;
			$credit_card_last_four = substr( $payment->credit_card->card_number, -4 );
			$cc_exp_month = $payment->credit_card->expiration_month;
			$cc_exp_year = $payment->credit_card->get_expiration_year( 4 );

		}else{
			$card_holder_name = "";
			$credit_card_last_four = "";
			$cc_exp_month = "";
			$cc_exp_year = "";
			
		}

		// Get Shipping Method to Save
		$shipping_method = "";
		if ( !get_option( 'ec_option_use_shipping' ) ) {
			$shipping_method = "";

		} else if( $GLOBALS['ec_cart_data']->cart_data->shipping_method == "free" ) {
			$shipping_method = wp_easycart_language( )->get_text( "cart_estimate_shipping", "cart_estimate_shipping_free" );

		} else if( $GLOBALS['ec_cart_data']->cart_data->shipping_method == "promo_free" ) {
			$promotion = new ec_promotion( );
			$shipping_method = $promotion->get_free_shipping_promo_label( $cart );

		} else if( $cart->shipping_subtotal <= 0 ) {
			$shipping_method = "";

		} else if( $shipping->shipping_method == "fraktjakt" ) {
			$shipping_method = $shipping->get_selected_shipping_method( );

		} else if( $GLOBALS['ec_cart_data']->cart_data->shipping_method != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_method != "standard" ) {
			$shipping_method = self::get_shipping_method_name( $GLOBALS['ec_cart_data']->cart_data->shipping_method );

		} else if( ( $shipping->shipping_method == "price" || $shipping->shipping_method == "weight" ) && $GLOBALS['ec_cart_data']->cart_data->expedited_shipping == "shipexpress" ) {
			$shipping_method = wp_easycart_language( )->get_text( "cart_estimate_shipping", "cart_estimate_shipping_express" );

		} else {
			$shipping_method = wp_easycart_language( )->get_text( "cart_estimate_shipping", "cart_estimate_shipping_standard" );
		}

		// Check for NULL rate in poor setups
		if( !$shipping_method )
			$shipping_method = "";
		
		// Gift Card and Coupon Code
		$coupon = $GLOBALS['ec_coupons']->redeem_coupon_code( $GLOBALS['ec_cart_data']->cart_data->coupon_code );
		if( $coupon && !$coupon->coupon_expired && ( $coupon->max_redemptions == 999 || $coupon->times_redeemed < $coupon->max_redemptions ) ){
			$coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
		}else{
			$coupon_code = '';
		}
		$gift_card = $GLOBALS['ec_cart_data']->cart_data->giftcard;
			
		$expedited_shipping = 0;
		if ( '' != $GLOBALS['ec_cart_data']->cart_data->expedited_shipping ) {
			$expedited_shipping = $GLOBALS['ec_cart_data']->cart_data->expedited_shipping;
		}
		$vals = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
		$guest_key = ( isset( $GLOBALS['ec_cart_data']->cart_data->guest_key ) && '' != $GLOBALS['ec_cart_data']->cart_data->guest_key ) ? $GLOBALS['ec_cart_data']->cart_data->guest_key : $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)];
		
		$agreed_to_terms = 0;
		if ( isset( $_POST['ec_terms_agree'] ) && $_POST['ec_terms_agree'] == '1' ) {
			$agreed_to_terms = 1;
		}
		if ( $tax->vat_rate ) {
			$vat_rate = $tax->vat_rate;
		} else {
			$vat_rate = 0;
		}
		self::$mysqli->insert(  'ec_order', 
								array( 	'user_id' 						=> $user->user_id, 
										'last_updated' 					=> date( 'Y-m-d H:i:s' ),
										'orderstatus_id'				=> $orderstatus_id,
										'order_weight' 					=> $cart->weight,
										'sub_total'						=> $order_totals->sub_total,
										
										'tax_total'						=> $order_totals->tax_total,
										'shipping_total'				=> $order_totals->shipping_total,
										'duty_total'					=> $order_totals->duty_total,
										'discount_total'				=> $order_totals->discount_total,
										'vat_total'						=> $order_totals->vat_total,
										'vat_rate'						=> $vat_rate,
										'gst_total'						=> $order_totals->gst_total,
										'pst_total'						=> $order_totals->pst_total,
										'hst_total'						=> $order_totals->hst_total,
										'tip_total'						=> $order_totals->tip_total,
										
										'gst_rate'						=> $tax->gst_rate,
										'pst_rate'						=> $tax->pst_rate,
										'hst_rate'						=> $tax->hst_rate,
										
										'grand_total' 					=> $order_totals->grand_total,
										'promo_code'					=> $coupon_code,
										'giftcard_id'					=> $gift_card,
										'use_expedited_shipping'		=> $expedited_shipping,
										'shipping_method'				=> $shipping_method,
										
										'user_email'					=> $user->email,
										'email_other'					=> $GLOBALS['ec_cart_data']->cart_data->email_other,
										'user_level'					=> $user->user_level,
										'billing_first_name'			=> $GLOBALS['ec_cart_data']->cart_data->billing_first_name,
										'billing_last_name'				=> $GLOBALS['ec_cart_data']->cart_data->billing_last_name,
										'billing_company_name'			=> $GLOBALS['ec_cart_data']->cart_data->billing_company_name,
										'billing_address_line_1'		=> $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1,
										
										'billing_address_line_2'		=> $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2,
										'billing_city'					=> $GLOBALS['ec_cart_data']->cart_data->billing_city,
										'billing_state'					=> $GLOBALS['ec_cart_data']->cart_data->billing_state,
										'billing_country'				=> $GLOBALS['ec_cart_data']->cart_data->billing_country,
										'billing_zip'					=> $GLOBALS['ec_cart_data']->cart_data->billing_zip,
										
										'billing_phone'					=> $GLOBALS['ec_cart_data']->cart_data->billing_phone,
										'shipping_first_name'			=> $GLOBALS['ec_cart_data']->cart_data->shipping_first_name,
										'shipping_last_name'			=> $GLOBALS['ec_cart_data']->cart_data->shipping_last_name,
										'shipping_company_name'			=> $GLOBALS['ec_cart_data']->cart_data->shipping_company_name,
										'shipping_address_line_1'		=> $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1,
										'shipping_address_line_2'		=> $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2,
										
										'shipping_city'					=> $GLOBALS['ec_cart_data']->cart_data->shipping_city,
										'shipping_state'				=> $GLOBALS['ec_cart_data']->cart_data->shipping_state,
										'shipping_country'				=> $GLOBALS['ec_cart_data']->cart_data->shipping_country,
										'shipping_zip'					=> $GLOBALS['ec_cart_data']->cart_data->shipping_zip,
										'shipping_phone'				=> $GLOBALS['ec_cart_data']->cart_data->shipping_phone,
										'vat_registration_number'		=> $GLOBALS['ec_cart_data']->cart_data->vat_registration_number,
										
										'payment_method'				=> $payment_type,
										'order_customer_notes'			=> $order_notes,
										'card_holder_name'				=> $card_holder_name,
										'creditcard_digits'				=> $credit_card_last_four,
										'cc_exp_month'					=> $cc_exp_month,
										'cc_exp_year'					=> $cc_exp_year,
										'order_gateway'					=> $order_gateway,
										
										'guest_key'						=> $guest_key,
										'agreed_to_terms'				=> $agreed_to_terms,
										'order_ip_address'				=> sanitize_text_field( $_SERVER['REMOTE_ADDR'] )
								), 
								array( 	'%d', '%s', '%d', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
										'%s', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s', '%s',
										'%s', '%s', '%s', '%s', '%s', '%s', '%s',
										'%s', '%d', '%s'
								)
							);	
									
		$order_id = self::$mysqli->insert_id;
		
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-new" )', $order_id ) );
		$order_log_id = self::$mysqli->insert_id;
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, $orderstatus_id ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "first_name", %s )', $order_log_id, $order_id, $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "last_name", %s )', $order_log_id, $order_id, $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) );

		if ( count( $tax->fees ) > 0 ) {
			foreach ( $tax->fees as $tax_fee ) {
				self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_fee( order_id, fee_label, fee_rate, fee_total ) VALUES( %d, %s, %s, %s )', $order_id, $tax_fee->label, $tax_fee->rate, $tax_fee->amount ) );
			}
		}

		// If coupon used, update usage numbers
		if( $coupon_code != "" ){
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_promocode SET times_redeemed = times_redeemed + 1 WHERE ec_promocode.promocode_id = %s", $coupon_code ) );
		}
		
		return $order_id;
		
	}
	
	public static function update_order_status( $order_id, $orderstatus_id ){
		self::$mysqli->update(
			'ec_order',
			array( 'orderstatus_id' => $orderstatus_id ),
			array( 'order_id' => $order_id ),
			array( '%d', '%d' )
		);
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-status-update" )', $order_id ) );
		$order_log_id = self::$mysqli->insert_id;
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, $orderstatus_id ) );
	}
	
	public static function get_order_id_from_temp_id( $temp_order_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT order_id FROM ec_order WHERE temp_order_id = '%s'", $temp_order_id ) );	
	}
	
	public static function get_response_from_order_id( $order_id ){
		return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT * FROM ec_response WHERE order_id = %d AND is_error = 0", $order_id ) );
	}
	
	public static function update_reponse_order_id( $order_id, $temp_order_id, $processor ){
		if( get_option( 'ec_option_enable_gateway_log' ) ){
			if( $processor == "Skrill" ){
				self::$mysqli->update( 'ec_response',
									array( 'order_id'		=> $order_id ),
									array( 'skrill_transaction_id'	=> $temp_order_id ),
									array( '%s', '%s' )
								  );
			}
		}
	}
	
	public static function remove_order( $order_id ){
		self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_order WHERE order_id = %d", $order_id ) );
		
		$coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
		
		// If coupon used, update usage numbers
		if( $coupon_code != "" ){
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_promocode SET times_redeemed = times_redeemed - 1 WHERE ec_promocode.promocode_id = %s", $coupon_code ) );
		}
	}
	
	public static function insert_address( $first_name, $last_name, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $company_name = "" ){
		if( !$phone )
			$phone = "";
		self::$mysqli->insert(	'ec_address',
								array(	"first_name"		=> $first_name,
										"last_name"			=> $last_name,
										"address_line_1"	=> $address_line_1,
										"address_line_2"	=> $address_line_2,
										"city"				=> $city,
										"state"				=> $state,
										"zip"				=> $zip,
										"country"			=> $country,
										"phone"				=> $phone,
										"company_name"		=> $company_name
									  ),
								array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
							  );
		
		return self::$mysqli->insert_id;	
	}
	
	public static function update_address( $address_id, $first_name, $last_name, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $company_name ){
		
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_address SET first_name = %s, last_name = %s, address_line_1 = %s, address_line_2 = %s, city = %s, state = %s, zip = %s, country = %s, phone = %s, company_name = %s WHERE address_id = %d", $first_name, $last_name, $address_line_1, $address_line_2, $city, $state, $zip, $country, $phone, $company_name, $address_id ) );	
	
	}
	
	public static function insert_user( $email, $password, $first_name, $last_name, $billing_id, $shipping_id, $user_level, $is_subscriber, $user_notes = "", $vat_registration_number = "" ){
		if( $is_subscriber )
			self::insert_subscriber( $email, $first_name, $last_name );
		else
			self::remove_subscriber( $email );
		
		$inserted = self::$mysqli->insert(	
			'ec_user',
			array(	"email"							=> $email,
					"password"						=> $password,
					"first_name"					=> $first_name,
					"last_name"						=> $last_name,
					"default_billing_address_id"	=> $billing_id,
					"default_shipping_address_id"	=> $shipping_id,
					"user_level"					=> $user_level,
					"is_subscriber"					=> $is_subscriber,
					"user_notes"					=> $user_notes,
					"vat_registration_number"		=> $vat_registration_number
				  ),
			array( '%s', '%s', '%s', '%s', '%d', '%d', '%s', '%d', '%s', '%s' )
		);
		
		return ( $inserted ) ? self::$mysqli->insert_id : 0;
	}
	
	public static function update_user( $user_id, $vat_registration_number ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_user SET vat_registration_number = %s WHERE user_id = %d", $vat_registration_number, $user_id ) );
	}
	
	public static function insert_subscriber( $email, $first_name, $last_name ){
		do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
		self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_subscriber( email, first_name, last_name ) VALUES( %s, %s, %s ) ON DUPLICATE KEY UPDATE first_name = %s, last_name = %s", $email, $first_name, $last_name, $first_name, $last_name ) );
	}
	
	public static function remove_subscriber( $email ){
		do_action( 'wpeasycart_remove_subscriber', $email );
		self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_subscriber WHERE email = '%s'", $email ) );
	}
	
	public static function update_address_user_id( $address_id, $user_id ){
		self::$mysqli->update(	'ec_address',
								array(	"user_id"	=> $user_id ),
								array(	"address_id"	=> $address_id),
								array(	'%s', '%s' )
							  );	
	}
	
	public static function insert_order_detail( $order_id, $giftcard_id, $download_key, $cart_item ){
		
		if( $cart_item->image1_optionitem )	$image1 = $cart_item->image1_optionitem;
		else								$image1 = $cart_item->image1;
		
		$insert_array = array(	'order_id'						=> $order_id,
								'product_id'					=> $cart_item->product_id,
								'title'							=> $cart_item->title,
								'model_number'					=> $cart_item->orderdetails_model_number,
								'unit_price'					=> $cart_item->unit_price,
								
								'total_price'					=> $cart_item->total_price,
								'quantity'						=> $cart_item->quantity,
								'image1'						=> $image1,
								
								'optionitem_id_1'				=> $cart_item->optionitem1_id,
								'optionitem_id_2'				=> $cart_item->optionitem2_id,
								'optionitem_id_3'				=> $cart_item->optionitem3_id,
								'optionitem_id_4'				=> $cart_item->optionitem4_id,
								'optionitem_id_5'				=> $cart_item->optionitem5_id,
								
								'optionitem_name_1'				=> $cart_item->optionitem1_name,
								'optionitem_name_2'				=> $cart_item->optionitem2_name,
								'optionitem_name_3'				=> $cart_item->optionitem3_name,
								'optionitem_name_4'				=> $cart_item->optionitem4_name,
								'optionitem_name_5'				=> $cart_item->optionitem5_name,
								
								'optionitem_label_1'			=> $cart_item->optionitem1_label,
								'optionitem_label_2'			=> $cart_item->optionitem2_label,
								'optionitem_label_3'			=> $cart_item->optionitem3_label,
								'optionitem_label_4'			=> $cart_item->optionitem4_label,
								'optionitem_label_5'			=> $cart_item->optionitem5_label,
								
								'optionitem_price_1'			=> $cart_item->optionitem1_price,
								'optionitem_price_2'			=> $cart_item->optionitem2_price,
								'optionitem_price_3'			=> $cart_item->optionitem3_price,
								'optionitem_price_4'			=> $cart_item->optionitem4_price,
								'optionitem_price_5'			=> $cart_item->optionitem5_price,
								
								'use_advanced_optionset'		=> $cart_item->use_advanced_optionset,
								'use_both_option_types'			=> $cart_item->use_both_option_types,
								'giftcard_id'					=> $giftcard_id,
								'gift_card_message'				=> $cart_item->gift_card_message,
								
								'gift_card_from_name'			=> ( ( is_null( $cart_item->gift_card_from_name ) ) ? '' : $cart_item->gift_card_from_name ),
								'gift_card_to_name'				=> ( ( is_null( $cart_item->gift_card_to_name ) ) ? '' : $cart_item->gift_card_to_name ),
								'gift_card_email'				=> ( ( is_null( $cart_item->gift_card_email ) ) ? '' : $cart_item->gift_card_email ),
								'is_download'					=> $cart_item->is_download,
								'is_giftcard'					=> $cart_item->is_giftcard,
								
								'is_taxable'					=> $cart_item->is_taxable,
								'is_shippable'					=> $cart_item->is_shippable,
                                'exclude_shippable_calculation' => $cart_item->exclude_shippable_calculation,
								'download_file_name'			=> $cart_item->download_file_name,
								'download_key'					=> $download_key,
								'maximum_downloads_allowed'		=> $cart_item->maximum_downloads_allowed,
								'download_timelimit_seconds'	=> $cart_item->download_timelimit_seconds,
								
								'is_amazon_download'			=> $cart_item->is_amazon_download,
								'amazon_key'					=> $cart_item->amazon_key,
								
								'is_deconetwork'				=> $cart_item->is_deconetwork,
								'deconetwork_id'				=> $cart_item->deconetwork_id,
								'deconetwork_name'				=> $cart_item->deconetwork_name,
								'deconetwork_product_code'		=> $cart_item->deconetwork_product_code,
								'deconetwork_options'			=> $cart_item->deconetwork_options,
								'deconetwork_color_code'		=> $cart_item->deconetwork_color_code,
								'deconetwork_product_id'		=> $cart_item->deconetwork_product_id,
								'deconetwork_image_link'		=> $cart_item->deconetwork_image_link,
								
								'include_code'					=> $cart_item->include_code,
								'subscription_signup_fee'		=> $cart_item->subscription_signup_fee,
                                'order_date'                    => date( 'Y-m-d H:i:s' ) );
								
										
		$percent_array = array( '%d', '%d', '%s', '%s', '%s',
								'%s', '%d', '%s', 
								'%d', '%d', '%d', '%d', '%d',
								'%s', '%s', '%s', '%s', '%s', 
								'%s', '%s', '%s', '%s', '%s', 
								'%s', '%s', '%s', '%s', '%s',
								'%d', '%d', '%s', '%s',
								'%s', '%s', '%s', '%d', '%d', 
								'%d', '%d', '%d', '%s', '%s', '%d', '%d', 
								'%d', '%s',
								'%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s',
								'%d', '%s', '%s' );
								
		if( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ){
			for( $i=0; $i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ); $i++ ){
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array( ), array( ) );
				for( $j=0; $j<count( $arr ); $j++ ){
					$insert_array[ $arr[$j] ] = $cart_item->custom_vars[$arr[$j]];
					array_push( $percent_array, '%s' );
				}
			}
		}
		
		self::$mysqli->insert(	'ec_orderdetail',
								$insert_array,
								$percent_array
							  );
							  
		$orderdetail_id = self::$mysqli->insert_id;
		
		// If using advanced option sets, insert the order values
		if ( $cart_item->use_advanced_optionset || $cart_item->use_both_option_types ) {
			foreach ( $cart_item->advanced_options as $advanced_option ) {
				self::insert_order_option( $orderdetail_id, $GLOBALS['ec_cart_data']->ec_cart_id, $advanced_option );
			}
		}
		
		// If including a code, apply one here
		if( $cart_item->include_code ){
			$codes = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_code.code_id FROM ec_code WHERE ec_code.product_id = %d AND orderdetail_id = 0", $cart_item->product_id ) );
			for( $i=0; $i<$cart_item->quantity; $i++ ){
				self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_code SET ec_code.orderdetail_id = %d WHERE ec_code.code_id = %d AND ec_code.product_id = %d", $orderdetail_id, $codes[$i]->code_id, $cart_item->product_id ) );
			}
		}
		
		do_action( 'wp_easycart_order_detail_inserted', $orderdetail_id, $cart_item );
		
		return $orderdetail_id;
	}
	
	public static function insert_order_option( $orderdetail_id, $tempcart_id, $advanced_option ){
		$sql = "INSERT INTO ec_order_option( orderdetail_id, option_name, optionitem_name, option_type, option_value, option_price_change, optionitem_allow_download, option_label, option_to_product_id, option_order, download_override_file, download_addition_file, optionitem_price, optionitem_price_onetime, optionitem_price_override, optionitem_price_multiplier, optionitem_price_per_character, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override, optionitem_weight_multiplier, optionitem_disallow_shipping, optionitem_enable_custom_price_label, optionitem_custom_price_label ) VALUES( %d, %s, %s, %s, %s, %s, %d, %s, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %s )";
		$advanced_option_details = $GLOBALS['ec_options']->get_optionitem( $advanced_option->optionitem_id );
		$advanced_option_data = $GLOBALS['ec_options']->get_option( $advanced_option->option_id );
		$optionitem_price = "";
		if ( $advanced_option_details->optionitem_enable_custom_price_label && ( $advanced_option_details->optionitem_price != 0 || ( isset( $advanced_option_details->optionitem_price ) && $advanced_option_details->optionitem_price != 0 ) || ( isset( $advanced_option_details->optionitem_price_onetime ) && $advanced_option_details->optionitem_price_onetime != 0 ) ) ) {
			$optionitem_price = ' ' . wp_easycart_language()->convert_text( $advanced_option_details->optionitem_custom_price_label );
		} else if( $advanced_option_details->optionitem_price > 0 ){ 
			$optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $advanced_option_details->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
		}else if( $advanced_option_details->optionitem_price < 0 ){ 
			$optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $advanced_option_details->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
		}else if( $advanced_option_details->optionitem_price_onetime > 0 ){ 
			$optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $advanced_option_details->optionitem_price_onetime ) . ")"; 
		}else if( $advanced_option_details->optionitem_price_onetime < 0 ){ 
			$optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $advanced_option_details->optionitem_price_onetime ) . ")"; 
		}else if( $advanced_option_details->optionitem_price_override >= 0 ){ 
			$optionitem_price = " (" . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . " " . $GLOBALS['currency']->get_currency_display( $advanced_option_details->optionitem_price_override ) . ")"; 
		}
		
		$option_value = $advanced_option->optionitem_value;
		if( $advanced_option_data->option_type == "file" )
			$option_value = $tempcart_id . "/" . $option_value;
		else if( $advanced_option_data->option_type == "dimensions1" || $advanced_option_data->option_type == "dimensions2" ){
			$dimensions = json_decode( $advanced_option->optionitem_value ); 
			if( count( $dimensions ) == 2 ){ 
				$option_value = $dimensions[0]; if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ $option_value .= "\""; } $option_value .= " x " . $dimensions[1]; if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ $option_value .= "\""; }; 
			}else if( count( $dimensions ) == 4 ){ 
				$option_value = $dimensions[0] . " " . $dimensions[1]; if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ $option_value .= "\""; } $option_value .= $dimensions[2] . " " . $dimensions[3]; if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ $option_value .= "\""; }
			}
		}

		$download_id = ( isset( $advanced_option->download_id ) ) ? $advanced_option->download_id : 0;
		
		self::$mysqli->query( self::$mysqli->prepare( $sql, $orderdetail_id, $advanced_option->option_name, $advanced_option->optionitem_name, $advanced_option_data->option_type, $option_value, $optionitem_price, $advanced_option_details->optionitem_allow_download, $advanced_option_data->option_label, $advanced_option->option_to_product_id, $advanced_option->option_order, $advanced_option_details->optionitem_download_override_file, $download_id, $advanced_option_details->optionitem_price, $advanced_option_details->optionitem_price_onetime, $advanced_option_details->optionitem_price_override, $advanced_option_details->optionitem_price_multiplier, $advanced_option_details->optionitem_price_per_character, $advanced_option_details->optionitem_weight, $advanced_option_details->optionitem_weight_onetime, $advanced_option_details->optionitem_weight_override, $advanced_option_details->optionitem_weight_multiplier, $advanced_option_details->optionitem_disallow_shipping, $advanced_option_details->optionitem_enable_custom_price_label, $advanced_option_details->optionitem_custom_price_label ) );
	}
	
	public static function insert_response( $order_id, $is_error, $processor, $response_text ){
		if( get_option( 'ec_option_enable_gateway_log' ) ){
			self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_response( order_id, is_error, processor, response_text ) VALUES( %d, %d, %s, %s )", $order_id, $is_error, $processor, $response_text ) );
			$response_id = self::$mysqli->insert_id;
			if ( $order_id != 0 ) {
				self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, %s )', $order_id, 'order-log-entry' ) );
				$order_log_id = self::$mysqli->insert_id;
				self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "response_id", %s )', $order_log_id, $order_id, $response_id ) );
				self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "log_type", %s )', $order_log_id, $order_id, $processor ) );
			}
		}
	}
	
	public static function insert_new_giftcard( $amount, $message ){
		$chars = array( "A", "B", "C", "D", "E", "F" );
		$giftcard_id = $chars[rand( 0, 5 )] . $chars[rand( 0, 5 )] . $chars[rand( 0, 5 )] . $chars[rand( 0, 5 )] . $chars[rand( 0, 5 )] . $chars[rand( 0, 5 )] . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 );
		
		self::$mysqli->insert( 'ec_giftcard', array( 'giftcard_id' => $giftcard_id, 'amount' => $amount, 'message' => $message ), array( '%s', '%s', '%s' ) );
		do_action( 'wpeasycart_gift_card_purchased', $giftcard_id, $amount );
		return $giftcard_id;
	}
	
	public static function insert_new_download( $order_id, $download_file_name, $product_id, $is_amazon_download, $amazon_key ){
		$download_id = uniqid( md5( rand( ) ) );
		
		self::$mysqli->insert( 	'ec_download', 
								array( 	'download_id'			=> $download_id, 
										'order_id' 				=> $order_id, 
										'download_file_name' 	=> $download_file_name,
										'product_id'			=> $product_id,
										'is_amazon_download'	=> $is_amazon_download,
										'amazon_key'			=> $amazon_key
									), 
								array( 	'%s', '%d', '%s', '%d', '%d', '%s' ) );
		return $download_id;
	}
	
	public static function get_download( $download_id ){
		return self::$mysqli->get_row( self::$mysqli->prepare( "SELECT download_id, date_created, UNIX_TIMESTAMP(date_created) AS date_created_timestamp, download_count, order_id, product_id, download_file_name FROM ec_download WHERE download_id = '%s'", $download_id ) );	
	}
	
	public static function update_quantity_value( $quantity, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 ){
		$sql = "UPDATE ec_optionitemquantity SET ec_optionitemquantity.quantity = ec_optionitemquantity.quantity - %d WHERE ec_optionitemquantity.product_id = %d AND ec_optionitemquantity.optionitem_id_1 = %d AND ec_optionitemquantity.optionitem_id_2 = %d AND ec_optionitemquantity.optionitem_id_3 = %d AND ec_optionitemquantity.optionitem_id_4 = %d AND ec_optionitemquantity.optionitem_id_5 = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $quantity, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 ) );
		do_action( 'wp_easycart_updated_optionitem_quantity_value', $quantity, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4, $optionitem_id_5 );
	}
	
	public static function update_download_count( $download_id, $download_count ){
		self::$mysqli->update(	'ec_download',
								array(	'download_count'	=> $download_count ),
								array(	'download_id'		=> $download_id ),
								array(	'%d', '$s' )
							);
	}
	
	public static function update_product_stock( $product_id, $quantity ){
		$quantity = apply_filters( 'wp_easycart_update_stock_quantity', $quantity, $product_id );
		$stock_quantity = self::$mysqli->get_var( self::$mysqli->prepare( "SELECT stock_quantity FROM ec_product WHERE product_id = %d", $product_id ) );
		self::$mysqli->update(
			'ec_product',
			array(
				'stock_quantity' => $stock_quantity - $quantity
			),
			array(
				'product_id' => $product_id
			),
			array( '%d', '%d' )
		);
		do_action( 'wp_easycart_updated_product_stock', $product_id, $quantity );
	}
	
	public static function clear_tempcart( $session_id ){
		$tempcart_ids = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_tempcart.tempcart_id FROM ec_tempcart WHERE ec_tempcart.session_id = %s", $session_id ) );
		foreach( $tempcart_ids as $tempcart_id ){
			self::$mysqli->query( self::$mysqli->prepare( "DELETE FROM ec_tempcart_optionitem WHERE ec_tempcart_optionitem.tempcart_id = %d", $tempcart_id->tempcart_id ) );
		}
		
		$sql = "DELETE FROM ec_tempcart WHERE session_id = %s";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $session_id ) );
	}
	
	public static function get_order_list( $user_id ){
		$orders = wp_cache_get( 'wpeasycart-order-list-'.$user_id, 'wpeasycart-orders' );
		if( !$orders ){
			$sql = "SELECT 
					ec_order.order_id, 
					CONVERT_TZ( ec_order.order_date, @@session.time_zone, '+00:00' ) as order_date,
					ec_order.orderstatus_id,
					ec_orderstatus.order_status, 
					ec_order.order_weight, 
					ec_orderstatus.is_approved,
					
					ec_order.sub_total,
					ec_order.shipping_total,
					ec_order.tax_total, 
					ec_order.duty_total, 
					ec_order.vat_total, 
					ec_order.vat_rate,
					ec_order.discount_total,
					ec_order.grand_total,  
					ec_order.refund_total,
					
					ec_order.gst_total,
					ec_order.gst_rate,
					ec_order.pst_total,
					ec_order.pst_rate,
					ec_order.hst_total,
					ec_order.hst_rate,
					
					ec_order.promo_code, 
					ec_order.giftcard_id, 
					
					ec_order.use_expedited_shipping,
					ec_order.shipping_method,
					ec_order.shipping_carrier,
					ec_order.tracking_number,
					
					ec_order.user_email,
					ec_order.email_other,
					ec_order.user_level,
					ec_order.guest_key,
					
					ec_order.billing_first_name, 
					ec_order.billing_last_name, 
					ec_order.billing_company_name, 
					ec_order.billing_address_line_1, 
					ec_order.billing_address_line_2, 
					ec_order.billing_city, 
					ec_order.billing_state, 
					ec_order.billing_zip, 
					ec_order.billing_country,
					billing_country.name_cnt as billing_country_name, 
					ec_order.billing_phone, 
				 
					ec_order.vat_registration_number,
					
					ec_order.shipping_first_name, 
					ec_order.shipping_last_name, 
					ec_order.shipping_company_name, 
					ec_order.shipping_address_line_1, 
					ec_order.shipping_address_line_2, 
					ec_order.shipping_city, 
					ec_order.shipping_state, 
					ec_order.shipping_zip, 
					ec_order.shipping_country,
					shipping_country.name_cnt as shipping_country_name,
					ec_order.shipping_phone, 
					
					ec_order.payment_method, 
					
					ec_order.paypal_email_id, 
					ec_order.paypal_payer_id,
					
					ec_order.order_customer_notes,
					ec_order.card_holder_name,
					ec_order.creditcard_digits,
					
					ec_order.fraktjakt_order_id,
					ec_order.fraktjakt_shipment_id,
					ec_order.subscription_id,

					ec_order.success_page_shown

					FROM 
					ec_order
					LEFT JOIN ec_country as billing_country ON ( ec_order.billing_country = billing_country.iso2_cnt )
					LEFT JOIN ec_country as shipping_country ON ( ec_order.shipping_country = shipping_country.iso2_cnt )
					LEFT JOIN ec_orderstatus ON ec_order.orderstatus_id = ec_orderstatus.status_id, 
					ec_user
					
					WHERE 
					ec_order.user_id = %d AND 
					ec_user.user_id = ec_order.user_id
					
					ORDER BY 
					ec_order.order_date DESC";
					
			$orders = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $user_id ) );
			wp_cache_set( 'wpeasycart-order-list-'.$user_id, $orders, 'wpeasycart-orders', 3600 );
		}
		return $orders;
	}
	
	public static function get_guest_order_row( $order_id, $guest_key ){
		$sql = "SELECT 
				ec_order.order_id, 
				ec_order.txn_id,
				ec_order.edit_sequence,
				CONVERT_TZ( ec_order.order_date, @@session.time_zone, '+00:00' ) as order_date,
			 	ec_order.orderstatus_id,
				ec_orderstatus.order_status, 
				ec_order.order_weight, 
				ec_orderstatus.is_approved,
				
				ec_order.user_id,
				ec_user.list_id,
				
				ec_order.sub_total,
				ec_order.shipping_total,
				ec_order.tax_total,
				ec_order.vat_total,
				ec_order.vat_rate,
				ec_order.duty_total,
				ec_order.discount_total,
				ec_order.grand_total, 
				ec_order.refund_total,
				
				ec_order.gst_total,
				ec_order.gst_rate,
				ec_order.pst_total,
				ec_order.pst_rate,
				ec_order.hst_total,
				ec_order.hst_rate,
				ec_order.tip_total,
				
				ec_order.promo_code, 
				ec_order.giftcard_id, 
				
				ec_order.use_expedited_shipping, 
				ec_order.shipping_method, 
				ec_order.shipping_carrier, 
				ec_order.tracking_number, 
				
				ec_order.user_email,
				ec_order.email_other,
				ec_order.user_level,
				ec_order.guest_key,
				
				ec_order.billing_first_name, 
				ec_order.billing_last_name, 
				ec_order.billing_company_name, 
				ec_order.billing_address_line_1, 
				ec_order.billing_address_line_2, 
				ec_order.billing_city, 
				ec_order.billing_state, 
				ec_order.billing_zip, 
				ec_order.billing_country,
				bill_country.name_cnt as billing_country_name, 
				ec_order.billing_phone, 
			 
				ec_order.vat_registration_number,
				
				ec_order.shipping_first_name, 
				ec_order.shipping_last_name, 
				ec_order.shipping_company_name, 
				ec_order.shipping_address_line_1, 
				ec_order.shipping_address_line_2, 
				ec_order.shipping_city, 
				ec_order.shipping_state, 
				ec_order.shipping_zip, 
				ec_order.shipping_country, 
				ship_country.name_cnt as shipping_country_name,
				ec_order.shipping_phone, 
				
				ec_order.payment_method, 
				
				ec_order.paypal_email_id, 
				ec_order.paypal_payer_id,
				
				ec_order.order_customer_notes,
				ec_order.card_holder_name,
				ec_order.creditcard_digits,
				
				ec_order.fraktjakt_order_id,
				ec_order.fraktjakt_shipment_id,
				ec_order.subscription_id,

				ec_order.success_page_shown,
				
				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
				
				FROM 
				ec_order
				
				LEFT JOIN ec_country as bill_country ON
				bill_country.iso2_cnt = ec_order.billing_country
				
				LEFT JOIN ec_country as ship_country ON
				ship_country.iso2_cnt = ec_order.shipping_country
				
				LEFT JOIN ec_orderstatus ON
				ec_order.orderstatus_id = ec_orderstatus.status_id
				
				LEFT JOIN ec_user ON
				ec_user.user_id = ec_order.user_id
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_order'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_order.order_id
				
				WHERE 
				
				ec_order.order_id = %d AND
				ec_order.guest_key = %s AND 
				ec_order.guest_key != ''
				
				GROUP BY
				ec_order.order_id";
				
				return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $order_id, $guest_key ) );
	}
	
	public static function get_order_row( $order_id, $user_id ){
		
		$order = wp_cache_get( 'wpeasycart-order-'.$user_id.'-'.$order_id, 'wpeasycart-orders' );
		if( !$order ){
			$sql = "SELECT 
				ec_order.order_id, 
				ec_order.txn_id,
				ec_order.edit_sequence,
				CONVERT_TZ( ec_order.order_date, @@session.time_zone, '+00:00' ) as order_date,
				ec_order.orderstatus_id,
				ec_orderstatus.order_status, 
				ec_order.order_weight, 
				ec_orderstatus.is_approved,
				
				ec_order.user_id,
				ec_user.list_id,
				
				ec_order.sub_total,
				ec_order.shipping_total,
				ec_order.tax_total,
				ec_order.vat_total,
				ec_order.vat_rate,
				ec_order.duty_total,
				ec_order.discount_total,
				ec_order.grand_total, 
				ec_order.refund_total,
					
				ec_order.gst_total,
				ec_order.gst_rate,
				ec_order.pst_total,
				ec_order.pst_rate,
				ec_order.hst_total,
				ec_order.hst_rate,
				ec_order.tip_total,
				
				ec_order.promo_code, 
				ec_order.giftcard_id, 
				
				ec_order.use_expedited_shipping, 
				ec_order.shipping_method, 
				ec_order.shipping_carrier, 
				ec_order.tracking_number, 
				
				ec_order.user_email,
				ec_order.email_other,
				ec_order.user_level,
				ec_order.guest_key,
				
				ec_order.billing_first_name, 
				ec_order.billing_last_name, 
				ec_order.billing_company_name, 
				ec_order.billing_address_line_1, 
				ec_order.billing_address_line_2, 
				ec_order.billing_city, 
				ec_order.billing_state, 
				ec_order.billing_zip, 
				ec_order.billing_country, 
				bill_country.name_cnt as billing_country_name, 
				ec_order.billing_phone,
				 
				ec_order.vat_registration_number,
				
				ec_order.shipping_first_name, 
				ec_order.shipping_last_name, 
				ec_order.shipping_company_name, 
				ec_order.shipping_address_line_1, 
				ec_order.shipping_address_line_2, 
				ec_order.shipping_city, 
				ec_order.shipping_state, 
				ec_order.shipping_zip, 
				ec_order.shipping_country,
				ship_country.name_cnt as shipping_country_name, 
				ec_order.shipping_phone, 
				
				ec_order.payment_method, 
				
				ec_order.paypal_email_id, 
				ec_order.paypal_payer_id,
				
				ec_order.order_customer_notes,
				ec_order.card_holder_name,
				ec_order.creditcard_digits,
				
				ec_order.fraktjakt_order_id,
				ec_order.fraktjakt_shipment_id,
				ec_order.subscription_id,

				ec_order.success_page_shown,

				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data 
				
				FROM 
				ec_order
				
				LEFT JOIN ec_country as bill_country ON
				bill_country.iso2_cnt = ec_order.billing_country
				
				LEFT JOIN ec_country as ship_country ON
				ship_country.iso2_cnt = ec_order.shipping_country
				
				LEFT JOIN ec_orderstatus ON
				ec_order.orderstatus_id = ec_orderstatus.status_id
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_order'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_order.order_id, 
				
				ec_user
				
				WHERE ec_user.user_id = '%s' AND ec_user.user_id = ec_order.user_id AND ec_order.order_id = %d
				
				GROUP BY ec_order.order_id";
				
			$order = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $user_id, $order_id ) );
			wp_cache_set( 'wpeasycart-order-'.$user_id.'-'.$order_id, $order, 'wpeasycart-orders', 3600 );
		}
		return $order;
	
	}
	
	public static function get_guest_order_details( $order_id, $guest_key ){
		return self::$mysqli->get_results( self::$mysqli->prepare( self::$orderdetail_guest_sql, $guest_key, $order_id ) );
	}
	
	public static function get_order_details( $order_id, $user_id ){
		$order_details = wp_cache_get( 'wpeasycart-order-details-'.$user_id.'-'.$order_id, 'wpeasycart-orders' );
		if( !$order_details ){
			$order_details = self::$mysqli->get_results( self::$mysqli->prepare( self::$orderdetail_sql, $user_id, $order_id ) );
			wp_cache_set( 'wpeasycart-order-details-'.$user_id.'-'.$order_id, $order_details, 'wpeasycart-orders', 3600 );
		}
		return $order_details;
	}
	
	public static function get_orderdetail_row( $order_id, $orderdetail_id, $user_id ){
		$row_sql = "SELECT 
				ec_orderdetail.orderdetail_id, 
				ec_orderdetail.order_id, 
				ec_orderdetail.product_id, 
				ec_orderdetail.title, 
				ec_orderdetail.model_number, 
				ec_orderdetail.order_date, 
				ec_orderdetail.unit_price, 
				ec_orderdetail.total_price, 
				ec_orderdetail.quantity, 
				ec_orderdetail.image1, 
				ec_orderdetail.optionitem_name_1, 
				ec_orderdetail.optionitem_name_2, 
				ec_orderdetail.optionitem_name_3, 
				ec_orderdetail.optionitem_name_4, 
				ec_orderdetail.optionitem_name_5,
				ec_orderdetail.optionitem_label_1, 
				ec_orderdetail.optionitem_label_2, 
				ec_orderdetail.optionitem_label_3, 
				ec_orderdetail.optionitem_label_4, 
				ec_orderdetail.optionitem_label_5,
				ec_orderdetail.optionitem_price_1, 
				ec_orderdetail.optionitem_price_2, 
				ec_orderdetail.optionitem_price_3, 
				ec_orderdetail.optionitem_price_4, 
				ec_orderdetail.optionitem_price_5,
				ec_orderdetail.giftcard_id, 
				ec_orderdetail.gift_card_message, 
				ec_orderdetail.gift_card_from_name, 
				ec_orderdetail.gift_card_to_name,
				ec_orderdetail.is_download, 
				ec_orderdetail.is_giftcard, 
				ec_orderdetail.is_taxable, 
				ec_orderdetail.is_shippable, 
				ec_orderdetail.include_code,
				ec_download.download_file_name, 
				ec_orderdetail.download_key,
				ec_orderdetail.maximum_downloads_allowed,
				ec_orderdetail.download_timelimit_seconds,
				ec_download.is_amazon_download,
				ec_download.amazon_key,
				
				";
		
		if( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ){
			for( $i=0; $i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ); $i++ ){
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array( ), array( ) );
				for( $j=0; $j<count( $arr ); $j++ ){
					$row_sql .= "ec_orderdetail." . $arr[$j] . ", ";
				}
			}
		}
			
		$row_sql .=	"
				
				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
				
				FROM ec_orderdetail
				
				LEFT JOIN ec_download
				ON ec_download.download_id = ec_orderdetail.download_key
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_orderdetail'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_orderdetail.orderdetail_id, 
				
				ec_order, ec_user
				
				WHERE 
				ec_user.user_id = %d AND 
				ec_order.order_id = ec_orderdetail.order_id AND 
				ec_user.user_id = ec_order.user_id AND 
				ec_orderdetail.order_id = %d AND 
				ec_orderdetail.orderdetail_id = %d
				
				GROUP BY
				ec_orderdetail.orderdetail_id";
		
		return self::$mysqli->get_row( self::$mysqli->prepare( $row_sql, $user_id, $order_id, $orderdetail_id ) );
	}
	
	public static function get_orderdetail_row_guest( $order_id, $orderdetail_id ){
		$row_sql = "SELECT 
				ec_orderdetail.orderdetail_id, 
				ec_orderdetail.order_id, 
				ec_orderdetail.product_id, 
				ec_orderdetail.title, 
				ec_orderdetail.model_number, 
				ec_orderdetail.order_date, 
				ec_orderdetail.unit_price, 
				ec_orderdetail.total_price, 
				ec_orderdetail.quantity, 
				ec_orderdetail.image1, 
				ec_orderdetail.optionitem_name_1, 
				ec_orderdetail.optionitem_name_2, 
				ec_orderdetail.optionitem_name_3, 
				ec_orderdetail.optionitem_name_4, 
				ec_orderdetail.optionitem_name_5,
				ec_orderdetail.optionitem_label_1, 
				ec_orderdetail.optionitem_label_2, 
				ec_orderdetail.optionitem_label_3, 
				ec_orderdetail.optionitem_label_4, 
				ec_orderdetail.optionitem_label_5,
				ec_orderdetail.optionitem_price_1, 
				ec_orderdetail.optionitem_price_2, 
				ec_orderdetail.optionitem_price_3, 
				ec_orderdetail.optionitem_price_4, 
				ec_orderdetail.optionitem_price_5,
				ec_orderdetail.giftcard_id, 
				ec_orderdetail.gift_card_message, 
				ec_orderdetail.gift_card_from_name, 
				ec_orderdetail.gift_card_to_name,
				ec_orderdetail.is_download, 
				ec_orderdetail.is_giftcard, 
				ec_orderdetail.is_taxable, 
				ec_orderdetail.is_shippable, 
				ec_orderdetail.download_file_name, 
				ec_orderdetail.download_key,
				ec_orderdetail.maximum_downloads_allowed,
				ec_orderdetail.download_timelimit_seconds,
				
				";
		
		if( isset( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ) ){
			for( $i=0; $i<count( $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'] ); $i++ ){
				$arr = $GLOBALS['ec_hooks']['ec_extra_cartitem_vars'][$i][0]( array( ), array( ) );
				for( $j=0; $j<count( $arr ); $j++ ){
					$row_sql .= "ec_orderdetail." . $arr[$j] . ", ";
				}
			}
		}
			
		$row_sql .=	"
				
				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
				
				FROM ec_orderdetail
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_orderdetail'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_orderdetail.orderdetail_id, 
				
				ec_order
				
				WHERE 
				ec_order.order_id = ec_orderdetail.order_id AND
				ec_orderdetail.order_id = %d AND 
				ec_orderdetail.orderdetail_id = %d
				
				GROUP BY
				ec_orderdetail.orderdetail_id";
		
		return self::$mysqli->get_row( self::$mysqli->prepare( $row_sql, $order_id, $orderdetail_id ) );
	}
	
	public static function get_user( $user_id, $email = "" ){
		if( $user_id == '' )
			return false; 
		$user = wp_cache_get( 'wpeasycart-user-'.$user_id, 'wpeasycart-user' );
		if( !$user ){
			$sql = "SELECT 
					ec_user.user_id,
					ec_user.first_name, 
					ec_user.last_name,
					ec_user.vat_registration_number,
					ec_user.user_level, 
					ec_user.default_billing_address_id,
					ec_user.default_shipping_address_id,
					ec_user.is_subscriber,
					ec_user.realauth_registered,
					ec_user.stripe_customer_id,
					ec_user.default_card_type, 
					ec_user.default_card_last4,
					ec_user.exclude_tax,
					ec_user.exclude_shipping,
					ec_user.email_other,
					
					ec_role.role_id,
					
					billing.first_name as billing_first_name, 
					billing.last_name as billing_last_name, 
					billing.address_line_1 as billing_address_line_1, 
					billing.address_line_2 as billing_address_line_2, 
					billing.city as billing_city, 
					billing.state as billing_state, 
					billing.zip as billing_zip, 
					billing.country as billing_country, 
					billing.phone as billing_phone, 
					billing.company_name as billing_company_name, 
					
					shipping.first_name as shipping_first_name, 
					shipping.last_name as shipping_last_name, 
					shipping.address_line_1 as shipping_address_line_1, 
					shipping.address_line_2 as shipping_address_line_2, 
					shipping.city as shipping_city, 
					shipping.state as shipping_state, 
					shipping.zip as shipping_zip, 
					shipping.country as shipping_country, 
					shipping.phone as shipping_phone,
					shipping.company_name as shipping_company_name,
					
					GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
					
					FROM 
					ec_user 
					
					LEFT JOIN ec_address as billing 
					ON ( ec_user.default_billing_address_id = billing.address_id AND billing.user_id = ec_user.user_id )
					
					LEFT JOIN ec_address as shipping 
					ON ( ec_user.default_shipping_address_id = shipping.address_id AND shipping.user_id = ec_user.user_id )
					
					LEFT JOIN ec_role
					ON ec_role.role_label = ec_user.user_level
					
					LEFT JOIN ec_customfield
					ON ec_customfield.table_name = 'ec_user'
					
					LEFT JOIN ec_customfielddata
					ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_user.user_id
					
					WHERE 
					ec_user.user_id = %s AND
					ec_user.email = %s
					
					GROUP BY
					ec_user.user_id";
			
			$user = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $user_id, $email ) );
			wp_cache_set( 'wpeasycart-user-'.$user_id, $user, 'wpeasycart-user', 3600 );
		}
		return $user;
	}
	
	public static function get_user_login( $email, $password, $password_hash ){
		$sql = "SELECT 
				ec_user.user_id,
				ec_user.email,
				ec_user.password,
				ec_user.first_name, 
				ec_user.last_name,
				ec_user.user_level, 
				ec_user.default_billing_address_id,
				ec_user.default_shipping_address_id,
				ec_user.is_subscriber,
				ec_user.realauth_registered,
				ec_user.stripe_customer_id,
				ec_user.default_card_type, 
				ec_user.default_card_last4,
				ec_user.exclude_tax,
				ec_user.exclude_shipping,
				
				billing.first_name as billing_first_name, 
				billing.last_name as billing_last_name, 
				billing.address_line_1 as billing_address_line_1, 
				billing.address_line_2 as billing_address_line_2, 
				billing.city as billing_city, 
				billing.state as billing_state, 
				billing.zip as billing_zip, 
				billing.country as billing_country, 
				billing.phone as billing_phone, 
				billing.company_name as billing_company_name, 
				
				shipping.first_name as shipping_first_name, 
				shipping.last_name as shipping_last_name, 
				shipping.address_line_1 as shipping_address_line_1, 
				shipping.address_line_2 as shipping_address_line_2, 
				shipping.city as shipping_city, 
				shipping.state as shipping_state, 
				shipping.zip as shipping_zip, 
				shipping.country as shipping_country, 
				shipping.phone as shipping_phone,
				shipping.company_name as shipping_company_name,
				
				GROUP_CONCAT(DISTINCT CONCAT_WS('***', ec_customfield.field_name, ec_customfield.field_label, ec_customfielddata.data) ORDER BY ec_customfield.field_name ASC SEPARATOR '---') as customfield_data
				
				FROM 
				ec_user 
				
				LEFT JOIN ec_address as billing 
				ON ( ec_user.default_billing_address_id = billing.address_id AND billing.user_id = ec_user.user_id )
				
				LEFT JOIN ec_address as shipping 
				ON ( ec_user.default_shipping_address_id = shipping.address_id AND shipping.user_id = ec_user.user_id )
				
				LEFT JOIN ec_customfield
				ON ec_customfield.table_name = 'ec_user'
				
				LEFT JOIN ec_customfielddata
				ON ec_customfielddata.customfield_id = ec_customfield.customfield_id AND ec_customfielddata.table_id = ec_user.user_id
				
				WHERE 
				ec_user.email = %s
				
				GROUP BY
				ec_user.user_id";
		
		$user = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $email ) );
		$password_verified = ( $password_hash == $user->password ? true : false );
		$password_verified = apply_filters( 'wpeasycart_password_verify', $password_verified, $password, $user->password, $user );
		if( $password_verified )
			return $user;
		else
			return false;
		
	}
	
	public static function update_user_quickbooks( $user_id, $list_id, $edit_sequence ){
		$sql = "UPDATE ec_user SET list_id = %s, edit_sequence = %s WHERE user_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $list_id, $edit_sequence, $user_id ) );
	}
	
	public static function reset_password( $email, $new_password ){
		return self::$mysqli->update( 	'ec_user',
								array( 'password'	=> $new_password ),
								array( 'email'		=> $email ),
								array( '%s', '%s' ) );	
	}
	
	public static function update_personal_information( $old_email, $user_id, $first_name, $last_name, $email, $is_subscriber, $vat_registration_number = '', $email_other = '' ) {
		$email_error = false;
		if ( $old_email != $email ) {
			$email_exists = self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_user.email FROM ec_user WHERE ec_user.email = %s", $email ) );
			if ( $email_exists ) {
				$email_error = true;
			}
		}
		
		if ( $email_error ) {
			return false; //return email exists error
		} else {
			if ( $is_subscriber ) {
				self::insert_subscriber( $email, $first_name, $last_name );
			} else {
				self::remove_subscriber( $email );
			}
			return self::$mysqli->update(
				'ec_user',
				array(
					'first_name' => $first_name,
					'last_name' => $last_name,
					'email' => $email,
					'email_other' => $email_other,
					'is_subscriber' => $is_subscriber,
					'vat_registration_number' => $vat_registration_number
				),
				array(
					'email' => $old_email,
					'user_id' => $user_id
				),
				array( '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s' )
			);
		}
		
	}
	
	public static function update_password( $user_id, $current_password, $new_password ){
		$user = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT ec_user.user_id, ec_user.email, ec_user.password FROM ec_user WHERE ec_user.user_id = %d", $user_id ) );
		
		$password_hash = md5( $current_password );
		
		$new_password_hash = md5( $new_password );
		$new_password_hash = apply_filters( 'wpeasycart_password_hash', $new_password_hash, $new_password );
		
		
		$password_verified = ( $password_hash == $user->password ? true : false );
		$password_verified = apply_filters( 'wpeasycart_password_verify', $password_verified, $current_password, $user->password, $user );
		if( $password_verified ){
			self::$mysqli->update(	'ec_user',
											array(	'password'		=> $new_password_hash ),
											array(	'user_id'		=> $user_id ),
											array(	'%s', '%d' ) );
			do_action( 'wpeasycart_password_changed', $user_id, $new_password_hash );
			return true;
		}else{
			return false;
		}
	}
	
	public static function update_user_address( $address_id, $first_name, $last_name, $address, $address2, $city, $state, $zip, $country, $phone, $company_name, $user_id ){
		return self::$mysqli->update(	'ec_address', 
										array(	'first_name'						=> $first_name,
												'last_name'							=> $last_name,
												'address_line_1'					=> $address,
												'address_line_2'					=> $address2,
												'city'								=> $city,
												'state'								=> $state,
												'zip'								=> $zip,
												'country'							=> $country,
												'phone'								=> $phone ,
												'company_name'						=> $company_name 
											 ),
										array( 	'address_id' 						=> $address_id,
												'user_id'							=> $user_id ), 
										array( 	'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d' )
								  );
	}
	
	public static function insert_user_address( $first_name, $last_name, $company_name, $address, $address2, $city, $state, $zip, $country, $phone, $user_id, $address_type ){
		if( !$phone || $phone == NULL )
			$phone = "";
		self::$mysqli->insert(	'ec_address',
												array(	'user_id'							=> $user_id,
														'first_name'						=> $first_name,
														'last_name'							=> $last_name,
														'address_line_1'					=> $address,
														'address_line_2'					=> $address2,
														'city'								=> $city,
														'state'								=> $state,
														'zip'								=> $zip,
														'country'							=> $country,
														'phone'								=> $phone,
														'company_name'						=> $company_name
												),
												array( 	'%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
											);
											
		$address_id = self::$mysqli->insert_id;
		
		if( $address_type == "billing" ){
			return self::$mysqli->update(	"ec_user",
											array( 	"default_billing_address_id"	=> $address_id ),
											array( 	"user_id"						=> $user_id
											),
											array( "%d", "%d" )
										);
			
		}else if( $address_type == "shipping"){
			return self::$mysqli->update(	"ec_user",
											array( 	"default_shipping_address_id"	=> $address_id ),
											array( 	"user_id"						=> $user_id
											),
											array( "%d", "%d" )
										);
										
		}
		
	}
	
	public static function update_product_views( $model_number ){
		if( get_option( 'ec_option_track_user_clicks' ) )
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_product SET ec_product.views=ec_product.views+1 WHERE ec_product.model_number = '%s'", $model_number ) );
	}
	
	public static function update_menu_views( $menuid ){
		if( get_option( 'ec_option_track_user_clicks' ) )
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_menulevel1 SET ec_menulevel1.clicks=ec_menulevel1.clicks+1 WHERE ec_menulevel1.menulevel1_id = '%s'", $menuid ) );
	}
	
	public static function update_menu_post_id( $menuid, $post_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_menulevel1 SET ec_menulevel1.post_id=%d WHERE ec_menulevel1.menulevel1_id = %d", $post_id, $menuid ) );
	}
	
	public static function update_submenu_views( $submenuid ){
		if( get_option( 'ec_option_track_user_clicks' ) )
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_menulevel2 SET ec_menulevel2.clicks=ec_menulevel2.clicks+1 WHERE ec_menulevel2.menulevel2_id = '%s'", $submenuid ) );
	}
	
	public static function update_submenu_post_id( $submenuid, $post_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_menulevel2 SET ec_menulevel2.post_id=%d WHERE ec_menulevel2.menulevel2_id = %d", $post_id, $submenuid ) );
	}
	
	public static function update_subsubmenu_views( $subsubmenuid ){
		if( get_option( 'ec_option_track_user_clicks' ) )
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_menulevel3 SET ec_menulevel3.clicks=ec_menulevel3.clicks+1 WHERE ec_menulevel3.menulevel3_id = '%s'", $subsubmenuid ) );
	}
	
	public static function update_subsubmenu_post_id( $subsubmenuid, $post_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_menulevel3 SET ec_menulevel3.post_id=%d WHERE ec_menulevel3.menulevel3_id = %d", $post_id, $subsubmenuid ) );
	}
	
	public static function update_product_post_id( $product_id, $post_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_product SET ec_product.post_id=%d WHERE ec_product.product_id = %d", $post_id, $product_id ) );
	}
	
	public static function update_category_post_id( $category_id, $post_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_category SET ec_category.post_id=%d WHERE ec_category.category_id = %d", $post_id, $category_id ) );
	}
	
	public static function update_manufacturer_post_id( $manufacturer_id, $post_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_manufacturer SET ec_manufacturer.post_id=%d WHERE ec_manufacturer.manufacturer_id = %d", $post_id, $manufacturer_id ) );
	}
	
	public static function get_countries( ){
		$sql = "SELECT name_cnt, iso2_cnt, vat_rate_cnt, stripe_taxrate_id FROM ec_country WHERE ship_to_active = 1 ORDER BY sort_order ASC";
		return self::$mysqli->get_results( $sql );
	}
	
	public static function get_country_name( $iso2 ){
		$sql = "SELECT name_cnt FROM ec_country WHERE iso2_cnt = '%s'";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $iso2 ) );
	}
	
	public static function get_country_code( $country_name ){
		$sql = "SELECT iso2_cnt FROM ec_country WHERE name_cnt = '%s'";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $country_name ) );
	}
	
	public static function get_states( ){
		$states = wp_cache_get( 'wpeasycart-states' );
		if( !$states ){
			$sql = "SELECT ec_state.name_sta, ec_state.code_sta, ec_country.iso2_cnt, ec_state.group_sta FROM ec_state LEFT JOIN ec_country ON ( ec_country.id_cnt = ec_state.idcnt_sta ) WHERE ec_state.ship_to_active = 1 ORDER BY ec_country.iso2_cnt, ec_state.group_sta, ec_state.sort_order ASC";
			$states = self::$mysqli->get_results( $sql );
			wp_cache_set( 'wpeasycart-states', $states );
		}
		return $states;
	}
	
	public static function get_settings( ){
		$settings = wp_cache_get( 'wpeasycart-settings', 'wpeasycart-settings' );
		if( !$settings ){
			$sql = "SELECT shipping_method, shipping_expedite_rate, shipping_handling_rate, ups_access_license_number, ups_user_id, ups_password, ups_ship_from_zip, ups_shipper_number, ups_country_code, ups_weight_type, ups_conversion_rate, ups_ship_from_state, ups_negotiated_rates, usps_user_name, usps_ship_from_zip, fedex_key, fedex_account_number, fedex_meter_number, fedex_password, fedex_ship_from_zip, fedex_weight_units, fedex_country_code, fedex_conversion_rate, fedex_test_account, auspost_api_key, auspost_ship_from_zip, dhl_site_id, dhl_password, dhl_ship_from_country, dhl_ship_from_zip, dhl_weight_unit, dhl_test_mode, fraktjakt_customer_id, fraktjakt_login_key, fraktjakt_conversion_rate, fraktjakt_test_mode, fraktjakt_address, fraktjakt_city, fraktjakt_state, fraktjakt_zip, fraktjakt_country, canadapost_username, canadapost_password, canadapost_customer_number, canadapost_contract_id, canadapost_test_mode, canadapost_ship_from_zip FROM ec_setting WHERE setting_id = 1";
			$settings = self::$mysqli->get_row( $sql );
			wp_cache_set( 'wpeasycart-settings', $settings, 'wpeasycart-settings' );
		}
		return $settings;
	}
	
	public static function get_ios3_country_code( $iso2 ){
		$sql = "SELECT iso3_cnt FROM ec_country WHERE iso2_cnt = '%s'";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $iso2 ) );
	}
	
	public static function get_manufacturer_row( $manufacturer_id ){
		$manufacturer = wp_cache_get( 'wpeasycart-manufacturer-'.$manufacturer_id, 'wpeasycart-manufacturer' );
		if( !$manufacturer ){
			$sql = "SELECT ec_manufacturer.manufacturer_id, ec_manufacturer.name, ec_manufacturer.clicks, ec_manufacturer.post_id FROM ec_manufacturer WHERE ec_manufacturer.manufacturer_id = %d";
			$manufacturer = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $manufacturer_id ) );	
			wp_cache_set( 'wpeasycart-manufacturer-'.$manufacturer_id, $manufacturer, 'wpeasycart-manufacturer' );
		}
		return $manufacturer;
	}
	
	public static function get_manufacturer_list( ){
		$sql = "SELECT ec_manufacturer.manufacturer_id, ec_manufacturer.name, ec_manufacturer.clicks, ec_manufacturer.post_id FROM ec_manufacturer";
		return self::$mysqli->get_results( $sql );	
	}
	
	public static function get_category_list( ){
		$sql = "SELECT ec_category.* FROM ec_category ORDER BY ec_category.priority DESC, ec_category.category_name ASC";
		return self::$mysqli->get_results( $sql );	
	}
	
	public static function get_category_list_items( $parent_id ){
		if( !$parent_id || $parent_id == -1 )
			$category_list = wp_cache_get( 'wpeasycart-category-list', 'wpeasycart-categories' );
		else
			$category_list = wp_cache_get( 'wpeasycart-category-list-'.$parent_id, 'wpeasycart-categories' );
		
		if( !$category_list ){
			if( $parent_id == -1 ){
				$category_list = self::$mysqli->get_results( "SELECT ec_category.* FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.parent_id = 0 ORDER BY ec_category.priority DESC, ec_category.category_name ASC" );
			}else if( $parent_id )
				$category_list = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT ec_category.* FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.parent_id = %d ORDER BY ec_category.priority DESC, ec_category.category_name ASC", $parent_id ) );
			else
				$category_list = self::$mysqli->get_results( "SELECT ec_category.* FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.featured_category = 1 ORDER BY ec_category.priority DESC, ec_category.category_name ASC" );
			
			if( count( $category_list ) == 0 ){
				$category_list = "EMPTY";
			}
			if( !$parent_id || $parent_id == -1 )
				wp_cache_set( 'wpeasycart-category-list', $category_list, 'wpeasycart-categories' );
			else
				wp_cache_set( 'wpeasycart-category-list-'.$parent_id, $category_list, 'wpeasycart-categories' );
			
		}
		if( $category_list == "EMPTY" )
			return array( );
		return $category_list;
	}
	
	public static function get_menu_row( $menu_id, $level ){
		if( $level == 1 ){
			$sql = "SELECT ec_menulevel1.menulevel1_id, ec_menulevel1.post_id, ec_menulevel1.name, ec_menulevel1.menu_order, ec_menulevel1.clicks, ec_menulevel1.seo_keywords, ec_menulevel1.seo_description, ec_menulevel1.banner_image FROM ec_menulevel1 WHERE ec_menulevel1.menulevel1_id = %d";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $menu_id ) );
		}else if( $level == 2 ){
			$sql = "SELECT ec_menulevel2.menulevel2_id, ec_menulevel2.post_id, ec_menulevel2.menulevel1_id, ec_menulevel2.name, ec_menulevel2.menu_order, ec_menulevel2.clicks, ec_menulevel2.seo_keywords, ec_menulevel2.seo_description, ec_menulevel2.banner_image FROM ec_menulevel2 WHERE ec_menulevel2.menulevel2_id = %d";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $menu_id ) );
		}else if( $level == 3 ){
			$sql = "SELECT ec_menulevel3.menulevel3_id, ec_menulevel3.post_id, ec_menulevel3.menulevel2_id, ec_menulevel3.name, ec_menulevel3.menu_order, ec_menulevel3.clicks, ec_menulevel3.seo_keywords, ec_menulevel3.seo_description, ec_menulevel3.banner_image FROM ec_menulevel3 WHERE ec_menulevel3.menulevel3_id = %d";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $menu_id ) );
		}
	}
	
	public static function get_menu_row_from_post_id( $post_id, $level ){
		if( $level == 1 ){
			$sql = "SELECT ec_menulevel1.menulevel1_id, ec_menulevel1.post_id, ec_menulevel1.name, ec_menulevel1.menu_order, ec_menulevel1.clicks, ec_menulevel1.seo_keywords, ec_menulevel1.seo_description, ec_menulevel1.banner_image FROM ec_menulevel1 WHERE ec_menulevel1.post_id = %d";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $post_id ) );
		}else if( $level == 2 ){
			$sql = "SELECT ec_menulevel2.menulevel2_id, ec_menulevel2.post_id, ec_menulevel2.menulevel1_id, ec_menulevel2.name, ec_menulevel2.menu_order, ec_menulevel2.clicks, ec_menulevel2.seo_keywords, ec_menulevel2.seo_description, ec_menulevel2.banner_image FROM ec_menulevel2 WHERE ec_menulevel2.post_id = %d";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $post_id ) );
		}else if( $level == 3 ){
			$sql = "SELECT ec_menulevel3.menulevel3_id, ec_menulevel3.post_id, ec_menulevel3.menulevel2_id, ec_menulevel3.name, ec_menulevel3.menu_order, ec_menulevel3.clicks, ec_menulevel3.seo_keywords, ec_menulevel3.seo_description, ec_menulevel3.banner_image FROM ec_menulevel3 WHERE ec_menulevel3.post_id = %d";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $post_id ) );
		}
	}
	
	public static function get_product_from_post_id( $post_id ){
		$product = wp_cache_get( 'wpeasycart-product-from-post-' . $post_id, 'wpeasycart-product-list' );
		if( !$product ){
			$sql = "SELECT ec_product.* FROM ec_product WHERE ec_product.post_id = %d";
			$product = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $post_id ) );
			if( !$product )
				$product = "EMPTY";
			wp_cache_set( 'wpeasycart-product-from-post-' . $post_id, $product, 'wpeasycart-product-list' );
		}
		if( $product == "EMPTY" )
			return false;
		return $product;
	}
	
	public static function get_category_id( $category_id ){
		if( strstr( $category_id, ',' ) === false ){
            $sql = "SELECT ec_category.category_id FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.category_id = %d";
            return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $category_id ) );
        }else{
            $cat_id_list = explode( ',', $category_id );
            $cat_id_list_ints = array( );
            foreach( $cat_id_list as $cat_id ){
                $cat_id_list_ints[] = (int) $cat_id;
            }
            $verified_list = self::$mysqli->get_results( "SELECT ec_category.category_id FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.category_id IN (" . implode( ',', $cat_id_list_ints )  . ")" );
            $return_str = '';
            foreach( $verified_list as $list_item ){
                if( $return_str != '' )
                    $return_str .= ',';
                $return_str .= $list_item->category_id;
            }
            return $return_str;
        }
	}
	
	public static function get_category_id_from_post_id( $post_id ){
		$sql = "SELECT ec_category.category_id FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.post_id = %d";
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $post_id ) );
	}
	
	public static function get_manufacturer_id( $manufacturer_id ){
		$sql = "SELECT ec_manufacturer.manufacturer_id FROM ec_manufacturer WHERE ec_manufacturer.manufacturer_id = %d";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $manufacturer_id ) );
	}
	
	public static function get_manufacturer_id_from_post_id( $post_id ){
		$sql = "SELECT ec_manufacturer.manufacturer_id FROM ec_manufacturer WHERE ec_manufacturer.post_id = %d";
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $post_id ) );
	}
	
	public static function get_pricepoint_id( $pricepoint_id ){
		$sql = "SELECT ec_pricepoint.pricepoint_id FROM ec_pricepoint WHERE ec_pricepoint.pricepoint_id = %d";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $pricepoint_id ) );
	}
	
	public static function get_model_number( $model_number ){
		$sql = "SELECT ec_product.model_number FROM ec_product WHERE ec_product.model_number = %s";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $model_number ) );
	}
	
	public static function get_roleprice( $user_id, $product_id ){
		$sql = "SELECT ec_roleprice.role_price FROM ec_roleprice, ec_user WHERE ec_user.user_id = %d AND ec_user.user_level = ec_roleprice.role_label AND ec_roleprice.product_id = %d";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $user_id, $product_id ) );
	}
	
	public static function update_user_realvault_registered( $user_id ){
		$sql = "UPDATE ec_user SET realauth_registered = 1 WHERE user_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $user_id ) );
	}
	
	public static function get_donation_order_total( $model_number ){
		$sql = "SELECT SUM( ec_orderdetail.total_price ) as order_sum FROM ec_order, ec_orderstatus, ec_orderdetail WHERE ec_orderstatus.status_id = ec_order.orderstatus_id AND ec_orderstatus.is_approved = 1 AND ec_order.order_id = ec_orderdetail.order_id AND ec_orderdetail.model_number = %s";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $model_number ) );
	}
	
	public static function get_advanced_optionsets( $product_id ){
		$optionsets = wp_cache_get( 'wpeasycart-get-advanced-option-sets-' . $product_id, 'wpeasycart-advanced-options-list' );
		if( !$optionsets ){
			$sql = "SELECT ec_option_to_product.option_to_product_id, ec_option_to_product.conditional_logic, ec_option.option_id, ec_option.option_name, ec_option.option_label, ec_option.option_type, ec_option.option_required, ec_option.option_error_text, ec_option.option_meta FROM ec_option_to_product LEFT JOIN ec_option ON ec_option.option_id = ec_option_to_product.option_id WHERE ec_option_to_product.product_id = %d AND ec_option.option_id != '' ORDER BY ec_option_to_product.option_order ASC, ec_option_to_product.option_to_product_id ASC";
			$optionsets = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $product_id ) );
			wp_cache_set( 'wpeasycart-get-advanced-option-sets-' . $product_id, $optionsets, 'wpeasycart-advanced-options-list' );
		}
		return $optionsets;
	}
	
	public static function get_all_advanced_optionsets( ){
		$sql = "SELECT ec_option_to_product.option_to_product_id, ec_option_to_product.product_id, ec_option.option_id, ec_option.option_name, ec_option.option_label, ec_option.option_type, ec_option.option_required, ec_option.option_error_text FROM ec_option_to_product LEFT JOIN ec_option ON ec_option.option_id = ec_option_to_product.option_id WHERE ec_option.option_id != '' ORDER BY ec_option_to_product.option_to_product_id ASC";
		return self::$mysqli->get_results( $sql );
	}
	
	public static function get_all_advanced_optionitems( $optionsets ){
		$cache_string = '';
		$sql = "SELECT ec_optionitem.* FROM ec_optionitem";
		for( $i=0; $i<count( $optionsets ); $i++ ){
			if( $i == 0 ){
				$sql .= " WHERE ";
			}else{
				$sql .= " OR ";
				$cache_string .= '-';
			}
			$cache_string .= $optionsets[$i]->option_id;
			$sql .= self::$mysqli->prepare( "ec_optionitem.option_id = %d", $optionsets[$i]->option_id );
		}
		$sql .= " ORDER BY option_id ASC, optionitem_order ASC";
		
		$optionitems = wp_cache_get( 'wpeasycart-get-all-advanced-optionitems-' . $cache_string, 'wpeasycart-advanced-optionitems-list' );
		if( !$optionitems ){
			$optionitems = self::$mysqli->get_results( $sql );
			$optionitems = apply_filters( 'wpeasycart_db_get_all_advanced_optionitems_results', $optionitems );
			wp_cache_set( 'wpeasycart-get-all-advanced-optionitems-' . $cache_string, $optionitems, 'wpeasycart-advanced-optionitems-list' );
		}
		return $optionitems;
	}
	
	public static function get_advanced_optionitems( $option_id ){
		$sql = "SELECT 
					ec_optionitem.optionitem_id, 
					ec_optionitem.option_id, 
					ec_optionitem.optionitem_name, 
					ec_optionitem.optionitem_price, 
					ec_optionitem.optionitem_price_onetime, 
					ec_optionitem.optionitem_price_override, 
					ec_optionitem.optionitem_price_multiplier, 
					ec_optionitem.optionitem_price_per_character,
					ec_optionitem.optionitem_weight, 
					ec_optionitem.optionitem_weight_onetime, 
					ec_optionitem.optionitem_weight_override, 
					ec_optionitem.optionitem_weight_multiplier, 
					ec_optionitem.optionitem_order, 
					ec_optionitem.optionitem_icon, 
					ec_optionitem.optionitem_initial_value, 
					ec_optionitem.optionitem_model_number,
					ec_optionitem.optionitem_initially_selected,
					
					ec_option.option_name,
					ec_option.option_type
					
					FROM ec_optionitem 
					LEFT JOIN ec_option ON ( ec_option.option_id = ec_optionitem.option_id )
					
					WHERE ec_optionitem.option_id = %d 
					
					ORDER BY ec_optionitem.optionitem_order";
					
		$results = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $option_id ) );
		$results = apply_filters( 'wpeasycart_db_get_all_advanced_optionitems_results', $results );
		return $results;
	}
	
	public static function add_option_to_cart( $tempcart_id, $session_id, $option_val ){
		if( $option_val["optionitem_value"] != "" ){
			$sql = "INSERT INTO ec_tempcart_optionitem(tempcart_id, session_id, option_id, optionitem_id, optionitem_value, optionitem_model_number) VALUES(%d, %s, %d, %d, %s, %s)";
			self::$mysqli->query( self::$mysqli->prepare( $sql, $tempcart_id, $session_id, $option_val["option_id"], $option_val["optionitem_id"], $option_val["optionitem_value"], $option_val["optionitem_model_number"] ) ); 
		}
	}
	
	public static function get_advanced_cart_options( $tempcart_id ){
		//$tempcart = wp_cache_get( 'wpeasycart-advanced-cart-options-' . $tempcart_id, 'wpeasycart-tempcart' );
		//if( !$tempcart ){
			$sql = "SELECT ec_tempcart_optionitem.*, ec_option_to_product.option_to_product_id, ec_option_to_product.option_order FROM ec_tempcart_optionitem LEFT JOIN ec_option_to_product ON ( ec_option_to_product.option_id = ec_tempcart_optionitem.option_id ) WHERE ec_tempcart_optionitem.session_id = %s GROUP BY ec_tempcart_optionitem.tempcart_optionitem_id ORDER BY ec_tempcart_optionitem.tempcart_optionitem_id ASC";
			$tempcart = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $tempcart_id ) );
			if( count( $tempcart ) == 0 )
				$tempcart = "EMPTY";
			//wp_cache_set( 'wpeasycart-advanced-cart-options-' . $tempcart_id, $tempcart, 'wpeasycart-tempcart', 3600 );
		//}
		if( $tempcart == "EMPTY" )
			return array( );
		return $tempcart;
	}
	
	public static function get_order_options( $orderdetail_id ){
		return self::$mysqli->get_results( self::$mysqli->prepare( 'SELECT * FROM ec_order_option WHERE orderdetail_id = %d ORDER BY order_option_id ASC', $orderdetail_id ) );
	}
	
	public static function get_order_fees( $order_id ) {
		return self::$mysqli->get_results( self::$mysqli->prepare( 'SELECT * FROM ec_order_fee WHERE order_id = %d ORDER BY order_fee_id ASC', $order_id ) );
	}
	
	public static function update_tempcart_grid_quantity( $tempcart_id, $quantity ){
		$sql = "UPDATE ec_tempcart SET ec_tempcart.grid_quantity = %d WHERE ec_tempcart.tempcart_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $quantity, $tempcart_id ) );
	}
	
	public static function get_zone_ids( $iso2_cnt, $code_sta ){
		$zone_ids = wp_cache_get( 'wpeasycart-zone-ids-' . $iso2_cnt . '-' . $code_sta );
		if( !$zone_ids ){
			$sql = "SELECT zone_id FROM ec_zone_to_location WHERE ( iso2_cnt = %s AND ( code_sta = '-1' OR code_sta = '' ) ) OR ( iso2_cnt = %s AND code_sta = %s )";
			$zone_ids = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $iso2_cnt, $iso2_cnt, $code_sta ) );
			wp_cache_set( 'wpeasycart-zone-ids-' . $iso2_cnt . '-' . $code_sta, $zone_ids );
		}
		return $zone_ids;
	}
	
	//////////////// SUBSCRIPTION FUNCTIONS //////////////////////////
	public static function has_subscription_inserted( $subscr_id ){
		$sql = "SELECT subscription_id FROM ec_subscription WHERE paypal_subscr_id = %s";
		$results = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $subscr_id ) );
		if( count( $results ) > 0 ){
			return true;
		}else{
			return false;
		}
	}
	
	public static function insert_paypal_subscription( $item_name, $payer_email, $first_name, $last_name, $residence_country, $mc_gross, $payment_date, $txn_id, $txn_type, $subscr_id, $username, $password ){
		
		$sql = "SELECT ec_product.model_number, ec_product.subscription_bill_length, ec_product.subscription_bill_period, ec_product.subscription_bill_duration FROM ec_product WHERE ec_product.title LIKE %s";
		$result = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $item_name ) );
		
		$model_number = "";
		$bill_length = 0;
		$bill_period = "";
		if( count( $result ) > 0 ){
			$model_number = $result[0]->model_number;
			$bill_length = $result[0]->subscription_bill_length;
			$bill_period = $result[0]->subscription_bill_period;
			$bill_duration = $result[0]->subscription_bill_duration;
		}
		
		$sql = "INSERT INTO ec_subscription( subscription_type, title, email, first_name, last_name, user_country, model_number, price, payment_length, payment_period, payment_duration, last_payment_date, paypal_txn_id, paypal_txn_type, paypal_subscr_id, paypal_username, paypal_password) VALUES( 'paypal', %s, %s, %s, %s, %s, %s, %s, %d, %s, %d, %s, %s, %s, %s, %s, %s )";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $item_name, $payer_email, $first_name, $last_name, $residence_country, $model_number, $mc_gross, $bill_length, $bill_period, $bill_duration, $payment_date, $txn_id, $txn_type, $subscr_id, $username, $password ) );
	
	}
	
	public static function update_paypal_subscription( $next_payment_date, $subscr_id ){
		
		$sql = "UPDATE ec_subscription SET next_payment_date = %s, number_payments_completed = number_payments_completed + 1, last_payment_date = NOW( ) WHERE paypal_subscr_id = %s";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $next_payment_date, $subscr_id ) );
		
	}
	
	public static function cancel_paypal_subscription( $subscr_id ){
		$sql = "UPDATE ec_subscription SET subscription_status = 'Canceled' WHERE paypal_subscr_id = %s";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $subscr_id ) );
	}
	
	public static function update_order_fraktjakt_info( $order_id, $ship_order_info ){
		$sql = "UPDATE ec_order SET ec_order.fraktjakt_order_id = %s, ec_order.fraktjakt_shipment_id = %s WHERE ec_order.order_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $ship_order_info['order_id'], $ship_order_info['shipment_id'], $order_id ) );
	}
	
	public static function update_order_stripe_charge_id( $order_id, $charge_id ){
		$sql = "UPDATE ec_order SET ec_order.stripe_charge_id = %s WHERE ec_order.order_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $charge_id, $order_id ) );
	}
	
	public static function update_order_transaction_id( $order_id, $transaction_id ){
		$sql = "UPDATE ec_order SET ec_order.gateway_transaction_id = %s WHERE ec_order.order_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $transaction_id, $order_id ) );
	}
	
	public static function update_user_stripe_id( $user_id, $customer_id ){
		$sql = "UPDATE ec_user SET ec_user.stripe_customer_id = %s WHERE ec_user.user_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $customer_id, $user_id ) );
	}
	
	public static function update_product_stripe_added( $product_id ){
		$sql = "UPDATE ec_product SET ec_product.stripe_plan_added = 1 WHERE ec_product.product_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $product_id ) );
	}
	
	public static function insert_stripe_subscription( $subscription, $product, $user, $card, $quantity ){
		$sql = "INSERT INTO ec_subscription( subscription_type, title, user_id, email, first_name, last_name, user_country, product_id, price, payment_length, payment_period, payment_duration, stripe_subscription_id, last_payment_date, next_payment_date, quantity ) VALUES( 'stripe', %s, %s, %s, %s, %s, %s, %s, %s, %d, %s, %d, %s, %s, %s, %d )";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $product->title, $user->user_id, $user->email, $user->billing->first_name, $user->billing->last_name, $user->billing->country, $product->product_id, round( $subscription->latest_invoice->amount_due / 100, 2 ), $product->subscription_bill_length, $product->subscription_bill_period, $product->subscription_bill_duration, $subscription->id, $subscription->current_period_start, $subscription->current_period_end, $quantity ) );
		
		return self::$mysqli->insert_id;
	}
	
	public static function insert_subscription_order( $product, $user, $card, $subscription_id, $coupon_code, $order_notes, $option1_name, $option2_name, $option3_name, $option4_name, $option5_name, $option1_label, $option2_label, $option3_label, $option4_label, $option5_label, $quantity, $order_totals, $shipping_method, $tax, $discount_total, $stripe_charge_id = '', $paymentintent_id = '', $option_onetime_adjustment = 0 ){
		$order_gateway = get_option( 'ec_option_payment_process_method' );
		
		$agreed_to_terms = 0;
		if( isset( $_POST['ec_terms_agree'] ) && $_POST['ec_terms_agree'] == '1' )
			$agreed_to_terms = 1;
		
		$sql = "INSERT INTO ec_order( 
				user_id, user_email, email_other, user_level, orderstatus_id, sub_total, discount_total, 
				tax_total, shipping_total, vat_total, vat_rate, gst_total, 
				
				gst_rate, pst_total, pst_rate, hst_total, hst_rate, 
				grand_total, shipping_method, promo_code, billing_first_name, billing_last_name, 
				
				billing_address_line_1, billing_city, billing_state, billing_country, billing_zip, 
				billing_phone, shipping_first_name, shipping_last_name, shipping_address_line_1, shipping_city, 
				
				shipping_state, shipping_country, shipping_zip, shipping_phone, vat_registration_number, payment_method, 
				creditcard_digits, cc_exp_month, cc_exp_year, subscription_id, order_customer_notes, billing_company_name, shipping_company_name, 
				
				billing_address_line_2, shipping_address_line_2, order_gateway, agreed_to_terms, stripe_charge_id, gateway_transaction_id, order_ip_address
			) VALUES( 
				%d, %s, %s, %s, 6, %s,  %s,
				%s, %s, %s, %s, %s, 
				
				%s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, 
				
				%s, %s, %s, %s, %s, 
				%s, %s, %s, %s, %s, 
				
				%s, %s, %s, %s, %s, %s, 
				%s, %s, %s, %d, %s, %s, %s,
				
				%s, %s, %s, %d, %s, %s, %s
			)";
		self::$mysqli->query( self::$mysqli->prepare( $sql, 
				$user->user_id, $user->email, ( ( isset( $user->email_other ) ) ? $user->email_other : '' ), $user->user_level, $order_totals->sub_total, $discount_total,
				$order_totals->tax_total, $order_totals->shipping_total, $order_totals->vat_total, $tax->vat_rate, $order_totals->gst_total,
				
				$tax->gst_rate, $order_totals->pst_total, $tax->pst_rate, $order_totals->hst_total, $tax->hst_rate, 
				round( $order_totals->grand_total, 2 ), $shipping_method, $coupon_code, $user->billing->first_name, $user->billing->last_name, 
				
				$user->billing->address_line_1, $user->billing->city, $user->billing->state, $user->billing->country, $user->billing->zip, 
				$user->billing->phone, $user->shipping->first_name, $user->shipping->last_name, $user->shipping->address_line_1, $user->shipping->city, 
				
				$user->shipping->state, $user->shipping->country, $user->shipping->zip, $user->shipping->phone, $user->vat_registration_number, $card->payment_method, 
				$card->get_last_four( ), $card->expiration_month, $card->expiration_year, $subscription_id, $order_notes, $user->billing->company_name, $user->shipping->company_name, 
				
				$user->billing->address_line_2, $user->shipping->address_line_2, $order_gateway, $agreed_to_terms, $stripe_charge_id, $paymentintent_id, sanitize_text_field($_SERVER['REMOTE_ADDR'] )
		) );
		
		$order_id = self::$mysqli->insert_id;
		$image1 = $product->images->get_single_image( );
		
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-new" )', $order_id ) );
		$order_log_id = self::$mysqli->insert_id;
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, 6 ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "first_name", %s )', $order_log_id, $order_id, $user->billing->first_name ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "last_name", %s )', $order_log_id, $order_id, $user->billing->last_name ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "user_email", %s )', $order_log_id, $order_id, $user->email ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "email_other", %s )', $order_log_id, $order_id, $user->email_other ) );

		$download_key = '';
		if ( $product->is_download ) {
			$download_key = self::insert_new_download( $order_id, $product->download_file_name, $product->product_id, $product->is_amazon_download, $product->amazon_key );
		}

		$sql = "INSERT INTO ec_orderdetail(
			order_id, product_id, title, model_number, order_date,
			unit_price, total_price, quantity, image1,
			optionitem_name_1, optionitem_name_2, optionitem_name_3, optionitem_name_4, optionitem_name_5,
			optionitem_label_1, optionitem_label_2, optionitem_label_3, optionitem_label_4, optionitem_label_5,
			use_advanced_optionset, use_both_option_types, subscription_signup_fee,
			is_download, is_giftcard, is_taxable, is_shippable, exclude_shippable_calculation,
			download_file_name, download_key, maximum_downloads_allowed, download_timelimit_seconds, is_amazon_download, amazon_key
		) VALUES(
			%d, %d, %s, %s, NOW( ),
			%s, %s, %d, %s,
			%s, %s, %s, %s, %s,
			%s, %s, %s, %s, %s,
			%d, %d, %s,
			%d, %d, %d, %d, %d,
			%s, %s, %d, %d, %d, %s
		)";
		self::$mysqli->query( self::$mysqli->prepare( $sql,
			$order_id, $product->product_id, $product->title, $product->model_number,
			$product->price + $product->subscription_signup_fee, ( ( $product->price + $product->subscription_signup_fee ) * $quantity ) + $option_onetime_adjustment, $quantity, $image1,
			$option1_name, $option2_name, $option3_name, $option4_name, $option5_name,
			$option1_label, $option2_label, $option3_label, $option4_label, $option5_label,
			$product->use_advanced_optionset, $product->use_both_option_types, $product->subscription_signup_fee * $quantity,
			$product->is_download, $product->is_giftcard, $product->is_taxable, $product->is_shippable, $product->exclude_shippable_calculation,
			$product->download_file_name, $download_key, $product->maximum_downloads_allowed, $product->download_timelimit_Seconds, $product->is_amazon_download, $product->amazon_key
		) );
		
		$orderdetail_id = self::$mysqli->insert_id;
		
		// If coupon used, update usage numbers
		if( $coupon_code != "" ){
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_promocode SET times_redeemed = times_redeemed + 1 WHERE ec_promocode.promocode_id = %s", $coupon_code ) );
		}
		
		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option ) && '' != $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option ) {
			$advanced_options = apply_filters( 'wp_easycart_subscription_modifiers_pre_db_insert', maybe_unserialize( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option ), $product );
		
			foreach ( $advanced_options as $advanced_option ) {
				$optionitem = $GLOBALS['ec_options']->get_optionitem( $advanced_option['optionitem_id'] );
				$optionset = $GLOBALS['ec_options']->get_option( $optionitem->option_id );
				$option_item_value = $advanced_option['optionitem_value'];

				if ( $advanced_option['option_type'] == "file" ) {
					$option_item_value = $GLOBALS['ec_cart_data']->ec_cart_id . "/" .  $advanced_option['optionitem_value'];

				} else if( $advanced_option['option_type'] == 'dimensions1' ) {
					$option_item_value = $advanced_option['optionitem_value'][0]; 
					if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ 
						$option_item_value .= "\"";
					}
					$option_item_value .= " x " . $advanced_option['optionitem_value'][1]; 
					if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ 
						$option_item_value .= "\"";
					}

				} else if( $advanced_option['option_type'] == 'dimensions2' ) {
					$option_item_value = $advanced_option['optionitem_value'][0] . " " . $advanced_option['optionitem_value'][1] . "\" x " . $advanced_option['optionitem_value'][2] . " " . $advanced_option['optionitem_value'][3] . "\"";
				}

				$optionitem_price = ""; 
				if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
					$optionitem_price = ' ' . wp_easycart_language()->convert_text( $optionitem->optionitem_custom_price_label );
				} else if( $optionitem->optionitem_price > 0 ){ 
					$optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
				}else if( $optionitem->optionitem_price < 0 ){ 
					$optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ")"; 
				}else if( $optionitem->optionitem_price_onetime > 0 ){ 
					$optionitem_price = " (+" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
				}else if( $optionitem->optionitem_price_onetime < 0 ){ 
					$optionitem_price = " (" . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) . ")"; 
				}else if( $optionitem->optionitem_price_override >= 0 ){ 
					$optionitem_price = " (" . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . " " . $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) . ")"; 
				}

				$option_additional_download_id = null;
				if ( isset( $optionitem->optionitem_download_addition_file ) && '' != $optionitem->optionitem_download_addition_file ) {
					$option_additional_download_id = self::insert_new_download( $order_id, $optionitem->optionitem_download_addition_file, $product->product_id, 0, '' );
				}

				self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_option( 
					orderdetail_id, option_name, option_label, optionitem_name, option_type,
					option_value, option_price_change, option_to_product_id, option_order, optionitem_allow_download,
					download_override_file, download_addition_file, optionitem_price, optionitem_price_onetime, optionitem_price_override,
					optionitem_price_multiplier, optionitem_price_per_character, optionitem_weight, optionitem_weight_onetime, optionitem_weight_override,
					optionitem_weight_multiplier, optionitem_disallow_shipping, optionitem_enable_custom_price_label, optionitem_custom_price_label
				) VALUES(
					%d, %s, %s, %s, %s,
					%s, %s, %d, %d, %d,
					%s, %s, %s, %s, %s,
					%s, %s, %s, %s, %s,
					%d, %s, %s, %s )',
					$orderdetail_id, $advanced_option['option_name'], $advanced_option['option_label'], $advanced_option['optionitem_name'], $advanced_option['option_type'],
					$option_item_value, $optionitem_price, 0, $optionitem->optionitem_order, $optionitem->optionitem_allow_download,
					$optionitem->optionitem_download_override_file, $option_additional_download_id, $optionitem->optionitem_price, $optionitem->optionitem_price_onetime, $optionitem->optionitem_price_override,
					$optionitem->optionitem_price_multiplier, $optionitem->optionitem_price_per_character, $optionitem->optionitem_weight, $optionitem->optionitem_weight_onetime, $optionitem->optionitem_weight_override,
					$optionitem->optionitem_weight_multiplier, $optionitem->optionitem_disallow_shipping, $optionitem->optionitem_enable_custom_price_label, $optionitem->optionitem_custom_price_label
				) );
				$order_option_id = self::$mysqli->insert_id;
				do_action( 'wp_easycart_subscription_modifier_post_db_insert', $order_option_id, $advanced_option );
				$i++;
			}
		}
		return $order_id;
	}
	
	public static function insert_paypal_subscription_order( $product, $user, $coupon_code, $order_notes, $option1_name, $option2_name, $option3_name, $option4_name, $option5_name, $option1_label, $option2_label, $option3_label, $option4_label, $option5_label, $quantity ){
		$sql = "INSERT INTO ec_order( user_id, user_email, email_other, user_level, orderstatus_id, sub_total, grand_total, promo_code, billing_first_name, billing_last_name, billing_address_line_1, billing_city, billing_state, billing_country, billing_zip, billing_phone, shipping_first_name, shipping_last_name, shipping_address_line_1, shipping_city, shipping_state, shipping_country, shipping_zip, shipping_phone, payment_method, order_customer_notes, billing_company_name, shipping_company_name, billing_address_line_2, shipping_address_line_2) VALUES( %d, %s, %s, %s, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $user->user_id, $user->email, ( ( isset( $user->email_other ) ) ? $user->email_other : '' ), $user->user_level, 8, ( $product->price * $quantity ) + $product->subscription_signup_fee, ( $product->price * $quantity ) + $product->subscription_signup_fee, $coupon_code, $user->billing->first_name, $user->billing->last_name, $user->billing->address_line_1, $user->billing->city, $user->billing->state, $user->billing->country, $user->billing->zip, $user->billing->phone, $user->shipping->first_name, $user->shipping->last_name, $user->shipping->address_line_1, $user->shipping->city, $user->shipping->state, $user->shipping->country, $user->shipping->zip, $user->shipping->phone, 'PayPal', $order_notes, $user->billing->company_name, $user->shipping->company_name, $user->billing->address_line_2, $user->shipping->address_line_2 ) );
		
		$order_id = self::$mysqli->insert_id;
		$image1 = $product->images->get_single_image( );
		
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-new" )', $order_id ) );
		$order_log_id = self::$mysqli->insert_id;
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, 8 ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "first_name", %s )', $order_log_id, $order_id, $user->billing->first_name ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "last_name", %s )', $order_log_id, $order_id, $user->billing->last_name ) );
		
		$sql = "INSERT INTO ec_orderdetail( order_id, product_id, title, model_number, order_date, unit_price, total_price, quantity, image1, optionitem_name_1, optionitem_name_2, optionitem_name_3, optionitem_name_4, optionitem_name_5, optionitem_label_1, optionitem_label_2, optionitem_label_3, optionitem_label_4, optionitem_label_5, use_advanced_optionset, subscription_signup_fee ) VALUES( %d, %d, %s, %s, NOW( ), %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %s )";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $order_id, $product->product_id, $product->title, $product->model_number, $product->price, ( $product->price * $quantity ), $quantity, $image1, $option1_name, $option2_name, $option3_name, $option4_name, $option5_name, $option1_label, $option2_label, $option3_label, $option4_label, $option5_label, $product->use_advanced_optionset, $product->subscription_signup_fee ) );
		
		$orderdetail_id = self::$mysqli->insert_id;
		
		if( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option ) && $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option != "" ){
			$advanced_options = json_decode( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option, true );
			foreach( $advanced_options as $advanced_option ){
				$sql = "INSERT INTO ec_order_option( orderdetail_id, option_name, optionitem_name, option_type, option_value ) VALUES( %d, %s, %s, %s, %s )";
				self::$mysqli->query( self::$mysqli->prepare( $sql, $orderdetail_id, $advanced_option['option_name'], $advanced_option['optionitem_name'], $advanced_option['option_type'], $advanced_option['optionitem_value'] ) );
				$i++;
			}
		}
		
		return $order_id;
	}
	
	public static function get_subscriptions( $user_id ){
		$subscriptions = wp_cache_get( 'wpeasycart-subscriptions-'.$user_id, 'wpeasycart-subscriptions' );
		if( !$subscriptions ){
			$sql = "SELECT ec_subscription.subscription_id, ec_subscription.num_failed_payment, ec_subscription.subscription_type, ec_subscription.subscription_status, ec_subscription.title, ec_subscription.user_id, ec_subscription.email, ec_subscription.first_name, ec_subscription.last_name, ec_subscription.user_country, ec_subscription.product_id, ec_subscription.model_number, ec_subscription.price, ec_subscription.payment_length, ec_subscription.payment_period, ec_subscription.start_date, ec_subscription.last_payment_date, ec_subscription.next_payment_date, ec_subscription.number_payments_completed, ec_subscription.paypal_txn_id, ec_subscription.paypal_txn_type, ec_subscription.paypal_subscr_id, ec_subscription.paypal_username, ec_subscription.paypal_password, ec_subscription.stripe_subscription_id, ec_subscription.payment_duration, ec_subscription.quantity, ec_product.trial_period_days, ec_product.membership_page, ec_product.min_purchase_quantity FROM ec_subscription LEFT JOIN ec_product ON ec_subscription.product_id = ec_product.product_id WHERE ec_subscription.user_id = %d";
			$subscriptions = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $user_id ) );
			if( count( $subscriptions ) == 0 )
				$subscriptions = "EMPTY";
			wp_cache_set( 'wpeasycart-subscriptions-'.$user_id, $subscriptions, 'wpeasycart-subscriptions', 3600 );
		}
		if( $subscriptions == "EMPTY" )
			return array( );
		return $subscriptions;
	}
	
	public static function get_subscription_row( $subscription_id ){
		$sql = "SELECT 
				ec_subscription.subscription_id, 
				ec_subscription.num_failed_payment, 
				ec_subscription.subscription_type, 
				ec_subscription.subscription_status, 
				ec_subscription.title, 
				ec_subscription.user_id, 
				ec_subscription.email, 
				ec_subscription.first_name, 
				ec_subscription.last_name, 
				ec_subscription.user_country, 
				ec_subscription.product_id, 
				ec_subscription.model_number, 
				ec_subscription.price, 
				ec_subscription.payment_length, 
				ec_subscription.payment_period, 
				ec_subscription.start_date, 
				ec_subscription.last_payment_date, 
				ec_subscription.next_payment_date, 
				ec_subscription.number_payments_completed, 
				ec_subscription.paypal_txn_id, 
				ec_subscription.paypal_txn_type, 
				ec_subscription.paypal_subscr_id, 
				ec_subscription.paypal_username, 
				ec_subscription.paypal_password, 
				ec_subscription.stripe_subscription_id, 
				ec_subscription.payment_duration, 
				ec_subscription.quantity, 
				ec_product.subscription_unique_id,
				ec_product.trial_period_days, 
				ec_product.membership_page, 
				ec_product.min_purchase_quantity 
			FROM 
				ec_subscription LEFT JOIN ec_product ON ec_subscription.product_id = ec_product.product_id 
			WHERE ec_subscription.subscription_id = %d";
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $subscription_id ) );
	}
	
	public static function find_subscription_match( $email, $product_id ){
		$sql = "SELECT ec_subscription.subscription_id FROM ec_subscription, ec_product WHERE ec_subscription.email = %s AND ec_subscription.subscription_status = 'Active' AND ec_subscription.product_id = %d AND ec_product.product_id = ec_subscription.product_id AND ec_product.allow_multiple_subscription_purchases = 0";
		return self::$mysqli->get_results( self::$mysqli->prepare( $sql, $email, $product_id ) );
	}
	
	public static function activate_user( $email, $key ){
	
		$sql = "SELECT ec_user.email, ec_user.user_level FROM ec_user WHERE ec_user.email = %s";
		$user = self::$mysqli->get_row( self::$mysqli->prepare( $sql, $email ) );
		
		if( $user->user_level != "pending" ){
			return true;
		}else{
		
			if( $user && isset( $user->email ) ){
				$match_key = md5( $user->email . "ecsalt" );
				
				if( $match_key == $key ){
					$sql = "UPDATE ec_user SET ec_user.user_level = 'shopper' WHERE ec_user.email = %s";
					self::$mysqli->query( self::$mysqli->prepare( $sql, $email ) );
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
	}
	
	public static function get_stripe_customer_id( $user_id ){
		$sql = "SELECT ec_user.stripe_customer_id FROM ec_user WHERE ec_user.user_id = %d";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $user_id ) );
	}
	
	public static function get_subscription_upgrades( $subscription_id ){
		$sql = "SELECT ec_product.subscription_plan_id FROM ec_subscription, ec_product WHERE ec_subscription.subscription_id = %d AND ec_product.product_id = ec_subscription.product_id";
		$plan_id = self::$mysqli->get_var( self::$mysqli->prepare( $sql, $subscription_id ) );
		if( $plan_id != 0 ){
			$sql = "SELECT ec_product.title, ec_product.product_id, ec_product.price, ec_product.subscription_bill_length, ec_product.subscription_bill_period, ec_product.subscription_bill_duration,  ec_subscription_plan.can_downgrade FROM ec_product, ec_subscription_plan WHERE ec_product.subscription_plan_id = %d AND ec_subscription_plan.subscription_plan_id = ec_product.subscription_plan_id ORDER BY ec_product.price ASC";
			return self::$mysqli->get_results( self::$mysqli->prepare( $sql, $plan_id ) );
		}else{
			return array( );
		}
	}
	
	public static function upgrade_subscription( $subscription_id, $product, $quantity ){
		$sql = "UPDATE ec_subscription SET title = %s, product_id = %d, price = %s, payment_length = %d, payment_period = %s, payment_duration = %s, quantity = %s WHERE subscription_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $product->title, $product->product_id, $product->price, $product->subscription_bill_length, $product->subscription_bill_period, $product->subscription_bill_duration, $quantity, $subscription_id ) );
	}
	
	public static function update_subscription( $subscription_id, $user, $product, $card, $quantity ){
		$sql = "UPDATE ec_subscription SET title = %s, email = %s, first_name = %s, last_name = %s, product_id = %d, price = %s, payment_length = %d, payment_period = %s, payment_duration = %s, quantity = %s WHERE subscription_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $product->title, $user->email, $user->billing->first_name, $user->billing->last_name, $product->product_id, $product->price, $product->subscription_bill_length, $product->subscription_bill_period, $product->subscription_bill_duration, $quantity, $subscription_id ) );
	}
	
	public static function get_webhook( $webhook_id ){
		$sql = "SELECT ec_webhook.webhook_type FROM ec_webhook WHERE ec_webhook.webhook_id = %s";
		return self::$mysqli->get_results( self::$mysqli->prepare( $sql, $webhook_id ) );
	}
	
	public static function insert_webhook( $webhook_id, $webhook_type, $webhook_data ){
		if( $webhook_id != "evt_00000000000000" ){
			$sql = "INSERT INTO ec_webhook( webhook_id, webhook_type, webhook_data ) VALUES( %s, %s, %s )";
			self::$mysqli->query( self::$mysqli->prepare( $sql, $webhook_id, $webhook_type, print_r( $webhook_data, true ) ) );
		}
	}
	
	public static function get_stripe_subscription( $stripe_subscription_id ){
		if( $stripe_subscription_id == "sub_00000000000000" ){ // Test Webhook
			return (object) array( 
				"subscription_id"			=> 0,
				"subscription_type"			=> "stripe",
				"subscription_status"		=> "Active",
				"title"						=> "Webhook Test Subscription",
				"user_id"					=> 0,
				"email"						=> "demouser@demo.com",
				"first_name"				=> "Demo",
				"last_name"					=> "User",
				"user_country"				=> "US",
				"product_id"				=> 0,
				"model_number"				=> "testproduct-model-number",
				"price"						=> 9.99,
				"payment_length"			=> 1,
				"payment_period"			=> 'M',
				"payment_duration"			=> 0,
				"start_date"				=> 0,
				"last_payment"				=> 0,
				"next_payment_date"			=> 0,
				"number_payments_completed"	=> 0,
				"paypal_txn_id"				=> '',
				"paypal_txn_type"			=> '',
				"paypal_subscr_id"			=> '',
				"paypal_username"			=> '',
				"paypal_password"			=> '',
				"stripe_subscription_id"	=> 'sub_00000000000000',
				"image1"					=> '',
				"trial_period_days"			=> 15,
				"num_failed_payment"		=> 2,
				"is_shippable"				=> 1
			);
		}else{
			$sql = "SELECT ec_subscription.subscription_id, ec_subscription.subscription_type, ec_subscription.subscription_status, ec_subscription.title, ec_subscription.user_id, ec_subscription.email, ec_subscription.first_name, ec_subscription.last_name, ec_subscription.user_country, ec_subscription.product_id, ec_subscription.model_number, ec_subscription.price, 
			ec_subscription.payment_length, ec_subscription.payment_period, ec_subscription.payment_duration, ec_subscription.start_date, 
			ec_subscription.last_payment_date, ec_subscription.next_payment_date, ec_subscription.number_payments_completed, 
			ec_subscription.paypal_txn_id, ec_subscription.paypal_txn_type, ec_subscription.paypal_subscr_id, ec_subscription.paypal_username, 
			ec_subscription.paypal_password, ec_subscription.stripe_subscription_id, ec_product.image1, ec_product.trial_period_days, ec_product.is_shippable
			
			FROM ec_subscription LEFT JOIN ec_product ON ec_product.product_id = ec_subscription.product_id WHERE ec_subscription.stripe_subscription_id = %s";
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $stripe_subscription_id ) );
		}
	}
			
	public static function update_stripe_order( $subscription_id, $stripe_charge_id ){
		$sql = "UPDATE ec_order SET ec_order.stripe_charge_id = %s WHERE ec_order.subscription_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $stripe_charge_id, $subscription_id ) );
	}
	
	public static function insert_stripe_order( $subscription, $webhook_data, $user ){
		if ( $subscription->subscription_id != 0 ) {
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_subscription SET num_failed_payment = 0 WHERE subscription_id = %d", $subscription->subscription_id ) );
		}
		$discount = 0;
		if ( isset( $webhook_data->discount ) && isset( $webhook_data->discount->coupon ) && isset( $webhook_data->discount->coupon->amount_off ) ) {
			$discount = round( $webhook_data->discount->coupon->amount_off / 100, 2 );
		} else if ( isset( $webhook_data->discount ) && isset( $webhook_data->discount->coupon ) && isset( $webhook_data->discount->coupon->percent_off ) ) {
			$discount = round( $webhook_data->discount->coupon->percent_off / 100 * $webhook_data->subtotal / 100, 2 );
		}

		$first_order = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT * FROM ec_order WHERE subscription_id = %d ORDER BY order_id ASC", $subscription->subscription_id ) );

		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
			$order_gateway = 'stripe';
		} else {
			$order_gateway = 'stripe_connect';
		}

		if( get_option( 'ec_subscriptions_use_first_order_details' ) ){
			
			self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_order( 
						
						user_id, user_email, email_other, user_level, orderstatus_id, sub_total, tax_total, discount_total,
						grand_total, billing_first_name, billing_last_name, billing_company_name, billing_address_line_1,
						billing_address_line_2, billing_city, billing_state, billing_country, billing_zip,
						billing_phone, shipping_first_name, shipping_last_name, shipping_company_name, shipping_address_line_1,
						shipping_address_line_2, shipping_city, shipping_state, shipping_country, shipping_zip,
						shipping_phone, payment_method, creditcard_digits, stripe_charge_id, subscription_id, order_gateway
					
					) VALUES( 
						%d, %s, %s, %s, %d, %s, %s, %s,
						%s, %s, %s, %s, %s, 
						%s, %s, %s, %s, %s, 
						%s, %s, %s, %s, %s, 
						%s, %s, %s, %s, %s,
						%s, %s, %s, %s, %d, %s )",
						
						$user->user_id, $user->email, ( ( isset( $first_order->email_other ) ) ? $first_order->email_other : '' ), $user->user_level, 6, number_format( ( $webhook_data->subtotal / 100 ), 3, '.', '' ), number_format( ( $webhook_data->tax / 100 ), 3, '.', '' ), number_format( $discount, 3, '.', '' ),
						number_format( ( $webhook_data->total / 100 ), 3, '.', '' ), $first_order->billing_first_name, $first_order->billing_last_name, $first_order->billing_company_name, $first_order->billing_address_line_1,
						$first_order->billing_address_line_2, $first_order->billing_city, $first_order->billing_state, $first_order->billing_country, $first_order->billing_zip,
						$first_order->billing_phone, $first_order->shipping_first_name, $first_order->shipping_last_name, $first_order->shipping_company_name, $first_order->shipping_address_line_1,
						$first_order->shipping_address_line_2, $first_order->shipping_city, $first_order->shipping_state, $first_order->shipping_country, $first_order->shipping_zip,
						$first_order->shipping_phone, $user->default_card_type, $user->default_card_last4, $webhook_data->charge, $subscription->subscription_id, $order_gateway ) );
					
			$order_id = self::$mysqli->insert_id;
		
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-new" )', $order_id ) );
			$order_log_id = self::$mysqli->insert_id;
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, 6 ) );
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "first_name", %s )', $order_log_id, $order_id, $first_order->billing_first_name ) );
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "last_name", %s )', $order_log_id, $order_id, $first_order->billing_last_name ) );
			
		}else{
		
			$first_name = $user->first_name;
			$last_name = $user->last_name;
			
			if( $user->billing_first_name != "" )
				$first_name = $user->billing_first_name;
			
			if( $user->billing_last_name != "" )
				$last_name = $user->billing_last_name;
			
			self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_order( 
						
						user_id, user_email, email_other, user_level, orderstatus_id, sub_total, tax_total, discount_total,
						grand_total, billing_first_name, billing_last_name, billing_company_name, billing_address_line_1,
						billing_address_line_2, billing_city, billing_state, billing_country, billing_zip,
						billing_phone, shipping_first_name, shipping_last_name, shipping_company_name, shipping_address_line_1,
						shipping_address_line_2, shipping_city, shipping_state, shipping_country, shipping_zip,
						shipping_phone, payment_method, creditcard_digits, stripe_charge_id, subscription_id, order_gateway
					
					) VALUES( 
						%d, %s, %s, %s, %d, %s, %s, %s, 
						%s, %s, %s, %s, %s, 
						%s, %s, %s, %s, %s, 
						%s, %s, %s, %s, %s, 
						%s, %s, %s, %s, %s,
						%s, %s, %s, %s, %d, %s )",
						
						$user->user_id, $user->email, ( ( isset( $first_order->email_other ) ) ? $first_order->email_other : '' ), $user->user_level, 6, number_format( ( $webhook_data->subtotal / 100 ), 3, '.', '' ), number_format( ( $webhook_data->tax / 100 ), 3, '.', '' ), number_format( $discount, 3, '.', '' ),
						number_format( ( $webhook_data->total / 100 ), 3, '.', '' ), $first_name, $last_name, $user->billing_company_name, $user->billing_address_line_1,
						$user->billing_address_line_2, $user->billing_city, $user->billing_state, $user->billing_country, $user->billing_zip,
						$user->billing_phone, $user->shipping_first_name, $user->shipping_last_name, $user->shipping_company_name, $user->shipping_address_line_1,
						$user->shipping_address_line_2, $user->shipping_city, $user->shipping_state, $user->shipping_country, $user->shipping_zip,
						$user->shipping_phone, $user->default_card_type, $user->default_card_last4, $webhook_data->charge, $subscription->subscription_id, $order_gateway ) );
					
			$order_id = self::$mysqli->insert_id;
		
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-new" )', $order_id ) );
			$order_log_id = self::$mysqli->insert_id;
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, 6 ) );
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "first_name", %s )', $order_log_id, $order_id, $first_name ) );
			self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "last_name", %s )', $order_log_id, $order_id, $last_name ) );
			
		}
		
		foreach ( $webhook_data->lines->data as $sub_row ) {
			$title = $sub_row->plan->nickname;
			if ( ! $title ) {
				$title = self::$mysqli->get_var( self::$mysqli->prepare( 'SELECT title FROM ec_product WHERE product_id = %d', $subscription->product_id ) );
			}
			if ( ! $title || $sub_row->type == 'invoiceitem' ) {
				$title = $sub_row->description;
			}
			$first_orderdetail_row = ( $first_order ) ? self::$mysqli->get_row( self::$mysqli->prepare( 'SELECT * FROM ec_orderdetail WHERE order_id = %d AND product_id = %d', $first_order->order_id, $subscription->product_id ) ) : false;

			self::$mysqli->insert(
				'ec_orderdetail',
				array(
					'order_id'		=> $order_id,
					'product_id'	=> $subscription->product_id,
					'title'			=> $title,
					'model_number'	=> $subscription->model_number,
					'order_date' => date( 'Y-m-d H:i:s' ),
					
					'unit_price'	=> ( $sub_row->amount / 100 ),
					'total_price'	=> ( $sub_row->amount / 100 ),
					'quantity'		=> 1,
					'image1'		=> ( $subscription->image1 ) ? $subscription->image1 : '',
					'is_shippable'	=> ( $subscription->is_shippable ) ? $subscription->is_shippable : 0,
					
					'optionitem_id_1' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_id_1 : 0,
					'optionitem_id_2' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_id_2 : 0,
					'optionitem_id_3' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_id_3 : 0,
					'optionitem_id_4' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_id_4 : 0,
					'optionitem_id_5' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_id_5 : 0,
					
					'optionitem_name_1' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_name_1 : '',
					'optionitem_name_2' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_name_2 : '',
					'optionitem_name_3' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_name_3 : '',
					'optionitem_name_4' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_name_4 : '',
					'optionitem_name_5' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_name_5 : '',
					
					'optionitem_label_1' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_label_1 : '',
					'optionitem_label_2' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_label_2 : '',
					'optionitem_label_3' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_label_3 : '',
					'optionitem_label_4' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_label_4 : '',
					'optionitem_label_5' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_label_5 : '',
					
					'optionitem_price_1' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_price_1 : '0.000',
					'optionitem_price_2' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_price_2 : '0.000',
					'optionitem_price_3' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_price_3 : '0.000',
					'optionitem_price_4' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_price_4 : '0.000',
					'optionitem_price_5' => ( $first_orderdetail_row && $sub_row->type != 'invoiceitem' ) ? $first_orderdetail_row->optionitem_price_5 : '0.000',
					
					'use_advanced_optionset' => ( $first_orderdetail_row ) ? $first_orderdetail_row->use_advanced_optionset : 0,
				),
				array( 
					'%d', '%d', '%s', '%s', '%s',
					'%s', '%s', '%d', '%s', '%d',
					'%d', '%d', '%d', '%d', '%d',
					'%s', '%s', '%s', '%s', '%s',
					'%s', '%s', '%s', '%s', '%s',
					'%s', '%s', '%s', '%s', '%s',
					'%d'
				)
			);
			$orderdetail_id = self::$mysqli->insert_id;
			if( $first_orderdetail_row && $sub_row->type != 'invoiceitem' && $first_orderdetail_row->use_advanced_optionset ) {
				$first_orderdetail_advanced_options = self::$mysqli->get_results( self::$mysqli->prepare( 'SELECT * FROM ec_order_option WHERE orderdetail_id = %d', $first_orderdetail_row->orderdetail_id ) );
				foreach( $first_orderdetail_advanced_options as $first_orderdetail_advanced_option ) {
					self::$mysqli->insert(
						'ec_order_option',
						array(
							'orderdetail_id' => $orderdetail_id,
							'option_name' => $first_orderdetail_advanced_option->option_name,
							'optionitem_name' => $first_orderdetail_advanced_option->optionitem_name,
							'option_type' => $first_orderdetail_advanced_option->option_type,
							'option_value' => $first_orderdetail_advanced_option->option_value,
							
							'option_price_change' => $first_orderdetail_advanced_option->option_price_change,
							'optionitem_allow_download' => $first_orderdetail_advanced_option->optionitem_allow_download,
							'option_label' => $first_orderdetail_advanced_option->option_label,
							'option_to_product_id' => $first_orderdetail_advanced_option->option_to_product_id,
							'option_order' => $first_orderdetail_advanced_option->option_order,
							
							'download_override_file' => $first_orderdetail_advanced_option->download_override_file,
							'download_addition_file' => $first_orderdetail_advanced_option->download_addition_file,

							'optionitem_price' => $first_orderdetail_advanced_option->optionitem_price,
							'optionitem_price_onetime' => $first_orderdetail_advanced_option->optionitem_price_onetime,
							'optionitem_price_override' => $first_orderdetail_advanced_option->optionitem_price_override,
							'optionitem_price_multiplier' => $first_orderdetail_advanced_option->optionitem_price_multiplier,
							'optionitem_price_per_character' => $first_orderdetail_advanced_option->optionitem_price_per_character,

							'optionitem_weight' => $first_orderdetail_advanced_option->optionitem_weight,
							'optionitem_weight_onetime' => $first_orderdetail_advanced_option->optionitem_weight_onetime,
							'optionitem_weight_override' => $first_orderdetail_advanced_option->optionitem_weight_override,
							'optionitem_weight_multiplier' => $first_orderdetail_advanced_option->optionitem_weight_multiplier,

							'optionitem_disallow_shipping' => $first_orderdetail_advanced_option->optionitem_disallow_shipping,
							'optionitem_enable_custom_price_label' => $first_orderdetail_advanced_option->optionitem_enable_custom_price_label,
							'optionitem_custom_price_label' => $first_orderdetail_advanced_option->optionitem_custom_price_label,
						),
						array( 
							'%d', '%s', '%s', '%s', '%s',
							'%s', '%d', '%s','%d','%d',
							'%s', '%s',
							'%s', '%s', '%s', '%s', '%s',
							'%s', '%s', '%s', '%s', 
							'%d', '%d', '%s',
						)
					);
				}
			}
		}
		
		return $order_id;
		
	}
	
	public static function insert_stripe_failed_order( $subscription, $webhook_data ){
		
		$user = self::get_stripe_user( $webhook_data->customer );
		
		if( $subscription->subscription_id != 0 )
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_subscription SET num_failed_payment = (num_failed_payment + 1) WHERE subscription_id = %d", $subscription->subscription_id ) );
		
		$first_name = $user->first_name;
		if( $user->billing_first_name != "" )
			$first_name = $user->billing_first_name;
			
		$last_name = $user->last_name;
		if( $user->billing_last_name != "" )
			$last_name = $user->billing_last_name;
		
		self::$mysqli->insert( 	'ec_order',
						array( 	'user_id'					=> $user->user_id,
								'user_email'				=> $user->email,
								'email_other'				=> ( ( isset( $user->email_other ) ) ? $user->email_other : '' ),
								'user_level'				=> $user->user_level,
								'orderstatus_id'			=> 7,
								'sub_total'					=> number_format( ( $webhook_data->subtotal / 100 ), 3, '.', '' ),
								'grand_total'				=> number_format( ( $webhook_data->total / 100 ), 3, '.', '' ),
								'billing_first_name'		=> $first_name,
								'billing_last_name'			=> $last_name,
								'billing_address_line_1'	=> $user->billing_address_line_1,
								'billing_city'				=> $user->billing_city,
								'billing_state'				=> $user->billing_state,
								'billing_country'			=> $user->billing_country,
								'billing_zip'				=> $user->billing_zip,
								'billing_phone'				=> $user->billing_phone,
								'payment_method'			=> $user->default_card_type,
								'creditcard_digits'			=> $user->default_card_last4,
								'stripe_charge_id'			=> ( ( $webhook_data->charge ) ? $webhook_data->charge : '' ),
								'subscription_id'			=> $subscription->subscription_id ),
						array( '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' ) );
		
		$order_id = self::$mysqli->insert_id;
		
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-new" )', $order_id ) );
		$order_log_id = self::$mysqli->insert_id;
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, 7 ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "first_name", %s )', $order_log_id, $order_id, $first_name ) );
		self::$mysqli->query( self::$mysqli->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "last_name", %s )', $order_log_id, $order_id, $last_name ) );
		
		self::$mysqli->insert( 	'ec_orderdetail',
						array(	'order_id'		=> $order_id,
								'product_id'	=> $subscription->product_id,
								'title'			=> $subscription->title,
								'model_number'	=> $subscription->model_number,
								'order_date'	=> 'NOW( )',
								'unit_price'	=> $subscription->price,
								'total_price'	=> $subscription->price,
								'quantity'		=> 1,
								'image1'		=> ( $subscription->image1 ) ? $subscription->image1 : '' ),
						array( '%d', '%d', '%s', '%s', '%s', '%s', '%s', '%d', '%s' ) );
		
		return $order_id;
		
	}
	
	public static function update_stripe_subscription( $subscription_id, $webhook_data ){
		$sql = "UPDATE ec_subscription SET title = %s, price = %s, payment_length = %d, payment_period = %s, last_payment_date = %s, next_payment_date = %s, number_payments_completed = number_payments_completed + 1 WHERE stripe_subscription_id = %s";
		
		$subscription = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT * FROM ec_subscription WHERE stripe_subscription_id = %s", $subscription_id ) );
		if( $subscription ){
		
			$title = ( isset( $webhook_data->lines->data[0]->plan->name ) && $webhook_data->lines->data[0]->plan->name ) ? $webhook_data->lines->data[0]->plan->name : $subscription->title;

			self::$mysqli->query( self::$mysqli->prepare( $sql, $title, ( $webhook_data->lines->data[0]->plan->amount /100 ), $webhook_data->lines->data[0]->plan->interval_count, self::get_stripe_subscription_period( $webhook_data->lines->data[0]->plan->interval ), $webhook_data->lines->data[0]->period->start, $webhook_data->lines->data[0]->period->end, $subscription_id ) );
			
		}
	}
	
	public static function update_stripe_subscription_failed( $subscription_id, $webhook_data ){
		$sql = "UPDATE ec_subscription SET subscription_status = 'Failed' WHERE subscription_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $subscription_id ) );
	}
	
	public static function get_stripe_user( $stripe_customer_id ){
		
		if( $stripe_customer_id == "cus_00000000000000" ){
			
			return (object) array(
				"user_id"						=> 0,
				"first_name"					=> "Demo",
				"last_name"						=> "User",
				"user_level"					=> "shopper",
				"default_billing_address_id"	=> 0,
				"default_shipping_address_id"	=> 0,
				"is_subscriber"					=> 0,
				"realauth_registered"			=> 0,
				"stripe_customer_id"			=> "cus_00000000000000",
				"default_card_type"				=> "visa",
				"default_card_last4"			=> "4242",
				"email"							=> "demouser@demo.com",
				"billing_first_name"			=> "Demo",
				"billing_last_name"				=> "User",
				"billing_address_line_1"		=> "Address 1",
				"billing_address_line_2"		=> "STE B",
				"billing_city"					=> "Pendleton",
				"billing_state"					=> "OR",
				"billing_zip"					=> "97801",
				"billing_country"				=> "US",
				"billing_phone"					=> "555-555-5555",
				"billing_company_name"			=> "Company Here",
				"shipping_first_name"			=> "Demo",
				"shipping_last_name"			=> "User",
				"shipping_address_line_1"		=> "Address 1",
				"shipping_address_line_2"		=> "STE B",
				"shipping_city"					=> "Pendleton",
				"shipping_state"				=> "OR",
				"shipping_zip"					=> "97801",
				"shipping_country"				=> "US",
				"shipping_phone"				=> "555-555-5555",
				"shipping_company_name"			=> "Company Here"
			);
			
		}else{
			
			$sql = "SELECT ec_user.user_id, ec_user.first_name, ec_user.last_name, ec_user.user_level, ec_user.default_billing_address_id, 
					ec_user.default_shipping_address_id, ec_user.is_subscriber, ec_user.realauth_registered, ec_user.stripe_customer_id, 
					ec_user.default_card_type, ec_user.default_card_last4, ec_user.email,
					
					IFNULL( billing.first_name, '' ) as billing_first_name, IFNULL( billing.last_name, '' ) as billing_last_name, 
					IFNULL( billing.address_line_1, '' ) as billing_address_line_1, IFNULL( billing.address_line_2, '' ) as billing_address_line_2, 
					IFNULL( billing.city, '' ) as billing_city, IFNULL( billing.state, '' ) as billing_state, IFNULL( billing.zip, '' ) as billing_zip, 
					IFNULL( billing.country, '' ) as billing_country, IFNULL( billing.phone, '' ) as billing_phone, IFNULL( billing.company_name, '' ) as billing_company_name,
					
					IFNULL( shipping.first_name, '' ) as shipping_first_name, IFNULL( shipping.last_name, '' ) as shipping_last_name, 
					IFNULL( shipping.address_line_1, '' ) as shipping_address_line_1, IFNULL( shipping.address_line_2, '' ) as shipping_address_line_2, 
					IFNULL( shipping.city, '' ) as shipping_city, IFNULL( shipping.state, '' ) as shipping_state, IFNULL( shipping.zip, '' ) as shipping_zip, 
					IFNULL( shipping.country, '' ) as shipping_country, IFNULL( shipping.phone, '' ) as shipping_phone, IFNULL( shipping.company_name, '' ) as shipping_company_name
					
					FROM 
					ec_user 
					
					LEFT JOIN ec_address as billing 
					ON ( ec_user.default_billing_address_id = billing.address_id AND billing.user_id = ec_user.user_id )
					
					LEFT JOIN ec_address as shipping 
					ON ( ec_user.default_shipping_address_id = shipping.address_id AND shipping.user_id = ec_user.user_id )
					
					WHERE 
					ec_user.stripe_customer_id = %s";
		
			return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $stripe_customer_id ) );
			
		}
		
	}
	
	public static function update_user_default_card( $user, $card ){
		$sql = "UPDATE ec_user SET default_card_type = %s, default_card_last4 = %s WHERE user_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $card->payment_method, $card->get_last_four( ), $user->user_id ) );
	}
	
	public static function get_subscription_payments( $subscription_id, $user_id ){
		$sql = "SELECT ec_order.order_id, CONVERT_TZ( ec_order.order_date, @@session.time_zone, '+00:00' ) as order_date, ec_order.grand_total FROM ec_order WHERE ec_order.subscription_id = %d AND ec_order.user_id = %d";
		return self::$mysqli->get_results( self::$mysqli->prepare( $sql, $subscription_id, $user_id ) );
	}
	
	public static function update_stripe_order_status( $stripe_charge_id, $orderstatus_id, $refund_total ){
		$sql = "UPDATE ec_order SET orderstatus_id = %d, refund_total = %s WHERE stripe_charge_id = %s";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $orderstatus_id, $refund_total, $stripe_charge_id ) );
	}
	
	public static function cancel_subscription( $subscription_id ){
		$sql = "UPDATE ec_subscription SET ec_subscription.subscription_status = 'Canceled' WHERE ec_subscription.subscription_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $subscription_id ) );
	}
	
	public static function has_membership_product_ids( $product_id_list ){
		
		if( $GLOBALS['ec_cart_data']->cart_data->user_id != "" && $GLOBALS['ec_cart_data']->cart_data->user_id != 0 ){
			$products = explode( ',', $product_id_list );
			$subscription_where = self::$mysqli->prepare( " WHERE ec_subscription.user_id = %d AND ec_subscription.subscription_status = 'Active' AND ( ", $GLOBALS['ec_user']->user_id );
			$i = 0;
			foreach( $products as $product_id ){
				if( $i > 0 ){
					$subscription_where .= " OR ";
				}
				$subscription_where .= self::$mysqli->prepare( "ec_subscription.product_id = %d", $product_id );
				$i++;
			}
			
			$subscription_sql = "SELECT ec_subscription.product_id FROM ec_subscription " . $subscription_where . " )";
			
			$subscription_product_ids = self::$mysqli->get_results( $subscription_sql );
			
			if( count( $subscription_product_ids ) > 0 ){
				return true;
			}
			
			// Check for regular products as well
			$product_where = self::$mysqli->prepare( " WHERE ec_order.user_id = %d AND ec_orderdetail.order_id = ec_order.order_id AND ec_order.orderstatus_id = ec_orderstatus.status_id AND ec_orderstatus.is_approved = 1 AND ( ", $GLOBALS['ec_user']->user_id );
			
			$i = 0;
			foreach( $products as $product_id ){
				if( $i > 0 ){
					$product_where .= " OR ";
				}
				$product_where .= self::$mysqli->prepare( "ec_orderdetail.product_id = %d", $product_id );
				$i++;
			}
			
			$orderdetails_sql = "SELECT ec_orderdetail.product_id FROM ec_order, ec_orderdetail, ec_orderstatus " . $product_where . " )";
			
			$orderdetail_product_ids = self::$mysqli->get_results( $orderdetails_sql );
			
			if( count( $orderdetail_product_ids ) > 0 ){
				return true;
			}
		}
		
		return false;
		
	}
	
	public static function get_membership_link( $subscription_id ){
		$sql = "SELECT ec_product.membership_page FROM ec_subscription, ec_product WHERE ec_subscription.subscription_id = %d AND ec_product.product_id = ec_subscription.product_id";
		return self::$mysqli->get_var( self::$mysqli->prepare( $sql, $subscription_id ) );
	}
	
	public static function does_user_exist( $email ){
		$sql = "SELECT ec_user.user_id FROM ec_user WHERE ec_user.email = %s";
		$results = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $email ) );
		if( count( $results ) > 0 ){
			return true;
		}else{
			return false;
		}
	}
	
	public static function get_stripe_subscription_period( $interval ){
	
		if( $interval == "day" )
			return "D";
		
		else if( $interval == "week" )
			return "W";
			
		else if( $interval == "month" )
			return "M";
		
		else if( $interval == "year" )
			return "Y";
	
	}
	
	public static function cancel_stripe_subscription( $stripe_subscription_id ){
		
		$sql = "UPDATE ec_subscription SET subscription_status = 'Canceled' WHERE stripe_subscription_id = %s";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $stripe_subscription_id ) );
		
	}
	
	public static function update_order_user( $user_id, $order_id ){
		
		$sql = "UPDATE ec_order SET user_id = %d WHERE order_id = %d";
		self::$mysqli->query( self::$mysqli->prepare( $sql, $user_id, $order_id ) );
	
	}
	
	public static function get_page_options( $post_id ){
		
		$pageoption_obj = wp_cache_get( 'wpeasycart-get-page-options-' . $post_id, 'wpeasycart-page-options' );
		
		if( !$pageoption_obj ){
			
			$sql = "SELECT option_type, option_value FROM ec_pageoption WHERE post_id = %d";
			$pageoption_arr = self::$mysqli->get_results( self::$mysqli->prepare( $sql, $post_id ) );
			$pageoption_obj = new stdClass( );
			foreach( $pageoption_arr as $option ){
				
				$pageoption_obj->{$option->option_type} = $option->option_value;
				
			}
			wp_cache_set( 'wpeasycart-get-page-options-' . $post_id, $pageoption_obj, 'wpeasycart-page-options' );
			
		}
		
		return $pageoption_obj;
		
	}
	
	public static function update_page_option( $post_id, $key, $value ){
		
		$results = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT option_value FROM ec_pageoption WHERE post_id = %d AND option_type = %s", $post_id, $key ) );
		if( count( $results ) > 0 ){
			self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_pageoption SET option_value = %s WHERE post_id = %d AND option_type = %s", $value, $post_id, $key ) );
		}else{
			self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_pageoption( post_id, option_type, option_value ) VALUES( %d, %s, %s )", $post_id, $key, $value ) );
		}
		
	}
	
	public static function update_product_options( $model_number, $product_options ){
		
		$sql = "UPDATE ec_product SET ";
		
		$add_comma = false;
		
		if( isset( $product_options->image_hover_type ) ){
			if( $add_comma )
				$sql .= ", ";
			$sql .= self::$mysqli->prepare( "ec_product.image_hover_type = %s", $product_options->image_hover_type );
			$add_comma = true;
		}
		
		if( isset( $product_options->image_effect_type ) ){
			if( $add_comma )
				$sql .= ", ";
			$sql .= self::$mysqli->prepare( "ec_product.image_effect_type = %s", $product_options->image_effect_type );
			$add_comma = true;
		}
		
		if( isset( $product_options->tag_type ) ){
			if( $add_comma )
				$sql .= ", ";
			$sql .= self::$mysqli->prepare( "ec_product.tag_type = %s", $product_options->tag_type );
			$add_comma = true;
		}
		
		if( isset( $product_options->tag_bg_color ) ){
			if( $add_comma )
				$sql .= ", ";
			$sql .= self::$mysqli->prepare( "ec_product.tag_bg_color = %s", $product_options->tag_bg_color );
			$add_comma = true;
		}
		
		if( isset( $product_options->tag_text_color ) ){
			if( $add_comma )
				$sql .= ", ";
			$sql .= self::$mysqli->prepare( "ec_product.tag_text_color = %s", $product_options->tag_text_color );
			$add_comma = true;
		}
		
		if( isset( $product_options->tag_text ) ){
			if( $add_comma )
				$sql .= ", ";
			$sql .= self::$mysqli->prepare( "ec_product.tag_text = %s", $product_options->tag_text );
			$add_comma = true;
		}
		
		$sql .= self::$mysqli->prepare( " WHERE ec_product.model_number = %s", $model_number );
		
		self::$mysqli->query( $sql ) ;
		
	}
	
	public static function get_menu_values( $product_id ){
		
		$menus = wp_cache_get( 'wpeasycart-menu-by-product-' . $product_id, 'wpeasycart-menu' );
		if( !$menus ){
			$menus = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
			
			ec_menulevel1_1.name as menulevel1_1_name, 
			ec_menulevel2_1.name as menulevel2_1_name, 
			ec_menulevel3_1.name as menulevel3_1_name,
			ec_menulevel1_1.post_id as menulevel1_1_post_id, 
			ec_menulevel2_1.post_id as menulevel2_1_post_id, 
			ec_menulevel3_1.post_id as menulevel3_1_post_id,
			ec_menulevel1_1.menulevel1_id as menulevel1_1_menu_id, 
			ec_menulevel2_1.menulevel2_id as menulevel2_1_menu_id, 
			ec_menulevel3_1.menulevel3_id as menulevel3_1_menu_id, 
			
			ec_menulevel1_2.name as menulevel1_2_name, 
			ec_menulevel2_2.name as menulevel2_2_name, 
			ec_menulevel3_2.name as menulevel3_2_name,
			ec_menulevel1_2.post_id as menulevel1_2_post_id, 
			ec_menulevel2_2.post_id as menulevel2_2_post_id, 
			ec_menulevel3_2.post_id as menulevel3_2_post_id,
			ec_menulevel1_2.menulevel1_id as menulevel1_2_menu_id, 
			ec_menulevel2_2.menulevel2_id as menulevel2_2_menu_id, 
			ec_menulevel3_2.menulevel3_id as menulevel3_2_menu_id,  
			
			ec_menulevel1_3.name as menulevel1_3_name, 
			ec_menulevel2_3.name as menulevel2_3_name, 
			ec_menulevel3_3.name as menulevel3_3_name,
			ec_menulevel1_3.post_id as menulevel1_3_post_id, 
			ec_menulevel2_3.post_id as menulevel2_3_post_id, 
			ec_menulevel3_3.post_id as menulevel3_3_post_id,
			ec_menulevel1_3.menulevel1_id as menulevel1_3_menu_id, 
			ec_menulevel2_3.menulevel2_id as menulevel2_3_menu_id, 
			ec_menulevel3_3.menulevel3_id as menulevel3_3_menu_id
			 
			FROM ec_product
			
			LEFT JOIN ec_menulevel1 as ec_menulevel1_1 ON ( ec_product.menulevel1_id_1 = ec_menulevel1_1.menulevel1_id )
			LEFT JOIN ec_menulevel1 as ec_menulevel1_2 ON ( ec_product.menulevel2_id_1 = ec_menulevel1_2.menulevel1_id )
			LEFT JOIN ec_menulevel1 as ec_menulevel1_3 ON ( ec_product.menulevel3_id_1 = ec_menulevel1_3.menulevel1_id )
			
			LEFT JOIN ec_menulevel2 as ec_menulevel2_1 ON ( ec_product.menulevel1_id_2 = ec_menulevel2_1.menulevel2_id )
			LEFT JOIN ec_menulevel2 as ec_menulevel2_2 ON ( ec_product.menulevel2_id_2 = ec_menulevel2_2.menulevel2_id )
			LEFT JOIN ec_menulevel2 as ec_menulevel2_3 ON ( ec_product.menulevel3_id_2 = ec_menulevel2_3.menulevel2_id )
			
			LEFT JOIN ec_menulevel3 as ec_menulevel3_1 ON ( ec_product.menulevel1_id_3 = ec_menulevel3_1.menulevel3_id )
			LEFT JOIN ec_menulevel3 as ec_menulevel3_2 ON ( ec_product.menulevel2_id_3 = ec_menulevel3_2.menulevel3_id )
			LEFT JOIN ec_menulevel3 as ec_menulevel3_3 ON ( ec_product.menulevel3_id_3 = ec_menulevel3_3.menulevel3_id )
			
			WHERE ec_product.product_id = %d", $product_id ) );
			
			wp_cache_set( 'wpeasycart-menu-by-product-' . $product_id, $menus, 'wpeasycart-menu' );
		}
		return $menus;
		
	}
	
	public static function get_category_values( $product_id ){
		
		$categories = wp_cache_get( 'wpeasycart-product-categories-'.$product_id, 'wpeasycart-categories' );
		if( !$categories ){
			$categories = self::$mysqli->get_results( self::$mysqli->prepare( "SELECT DISTINCT ec_category.category_id, ec_category.category_name, ec_category.post_id FROM ec_categoryitem LEFT JOIN ec_category ON ( ec_category.is_active = 1 AND ec_category.category_id = ec_categoryitem.category_id ) WHERE ec_categoryitem.product_id = %d", $product_id ) );
			wp_cache_set( 'wpeasycart-product-categories-'.$product_id, $categories, 'wpeasycart-categories' );
		}
		return $categories;
	}
	
	public static function get_option_quantity_values( $product_id ){
		
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) - 
					(
						SELECT 
							COALESCE( SUM( ec_tempcart.quantity ), 0 ) 
						FROM 
							ec_tempcart 
						WHERE
							ec_tempcart.product_id = ec_optionitemquantity.product_id AND
							ec_tempcart.optionitem_id_1 = ec_optionitemquantity.optionitem_id_1 AND
							ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
					) AS quantity
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_1 = ec_optionitem.optionitem_id 
					)
				WHERE 
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_1 
				GROUP BY 
					ec_optionitemquantity.optionitem_id_1 
				ORDER BY 
					ec_optionitem.optionitem_order", $hours, $product_id ) );
		}else{
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) as quantity 
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_1 = ec_optionitem.optionitem_id 
					) 
				WHERE 
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_1 
				GROUP BY 
					ec_optionitemquantity.optionitem_id_1 
				ORDER BY 
					ec_optionitem.optionitem_order", $product_id ) );
		}
		
	}
	
	public static function get_option2_quantity_values( $product_id, $optionitem_id_1 ){
	
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) -
					(
						SELECT 
							COALESCE( SUM( ec_tempcart.quantity ), 0 ) 
						FROM 
							ec_tempcart 
						WHERE
							ec_tempcart.product_id = ec_optionitemquantity.product_id AND
							ec_tempcart.optionitem_id_1 = ec_optionitemquantity.optionitem_id_1 AND
							ec_tempcart.optionitem_id_2 = ec_optionitemquantity.optionitem_id_2 AND
							ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
					) AS quantity
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_2 = ec_optionitem.optionitem_id
					)
				WHERE
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitemquantity.optionitem_id_1 = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_2 
				GROUP BY 
					ec_optionitemquantity.optionitem_id_1, 
					ec_optionitemquantity.optionitem_id_2 
				ORDER BY
					ec_optionitem.optionitem_order", $hours, $product_id, $optionitem_id_1 ) );
		}else{
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) as quantity 
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_2 = ec_optionitem.optionitem_id
					)
				WHERE
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitemquantity.optionitem_id_1 = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_2 
				GROUP BY 
					ec_optionitemquantity.optionitem_id_1, 
					ec_optionitemquantity.optionitem_id_2 
				ORDER BY
					ec_optionitem.optionitem_order", $product_id, $optionitem_id_1 ) );
		}
		
	}
	
	public static function get_option3_quantity_values( $product_id, $optionitem_id_1, $optionitem_id_2 ){
	
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) -
					(
						SELECT 
							COALESCE( SUM( ec_tempcart.quantity ), 0 ) 
						FROM 
							ec_tempcart 
						WHERE
							ec_tempcart.product_id = ec_optionitemquantity.product_id AND
							ec_tempcart.optionitem_id_1 = ec_optionitemquantity.optionitem_id_1 AND
							ec_tempcart.optionitem_id_2 = ec_optionitemquantity.optionitem_id_2 AND
							ec_tempcart.optionitem_id_3 = ec_optionitemquantity.optionitem_id_3 AND
							ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
					) AS quantity
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_3 = ec_optionitem.optionitem_id
					)
				WHERE
					ec_optionitemquantity.product_id = %d AND
					ec_optionitemquantity.optionitem_id_1 = %d AND
					ec_optionitemquantity.optionitem_id_2 = %d AND
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_3
				GROUP BY
					ec_optionitemquantity.optionitem_id_1,
					ec_optionitemquantity.optionitem_id_2,
					ec_optionitemquantity.optionitem_id_3
				ORDER BY
					ec_optionitem.optionitem_order", $hours, $product_id, $optionitem_id_1, $optionitem_id_2 ) );
		}else{
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) as quantity 
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_3 = ec_optionitem.optionitem_id
					)
				WHERE
					ec_optionitemquantity.product_id = %d AND
					ec_optionitemquantity.optionitem_id_1 = %d AND
					ec_optionitemquantity.optionitem_id_2 = %d AND
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_3
				GROUP BY
					ec_optionitemquantity.optionitem_id_1,
					ec_optionitemquantity.optionitem_id_2,
					ec_optionitemquantity.optionitem_id_3
				ORDER BY
					ec_optionitem.optionitem_order", $product_id, $optionitem_id_1, $optionitem_id_2 ) );
		}
		
	}
	
	public static function get_option4_quantity_values( $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3 ){
	
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) - 
					(
						SELECT 
							COALESCE( SUM( ec_tempcart.quantity ), 0 ) 
						FROM 
							ec_tempcart 
						WHERE
							ec_tempcart.product_id = ec_optionitemquantity.product_id AND
							ec_tempcart.optionitem_id_1 = ec_optionitemquantity.optionitem_id_1 AND
							ec_tempcart.optionitem_id_2 = ec_optionitemquantity.optionitem_id_2 AND
							ec_tempcart.optionitem_id_3 = ec_optionitemquantity.optionitem_id_3 AND
							ec_tempcart.optionitem_id_4 = ec_optionitemquantity.optionitem_id_4 AND
							ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
					) AS quantity 
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_4 = ec_optionitem.optionitem_id
					)
				WHERE 
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitemquantity.optionitem_id_1 = %d AND 
					ec_optionitemquantity.optionitem_id_2 = %d AND 
					ec_optionitemquantity.optionitem_id_3 = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_4
				GROUP BY
					ec_optionitemquantity.optionitem_id_1,
					ec_optionitemquantity.optionitem_id_2,
					ec_optionitemquantity.optionitem_id_3,
					ec_optionitemquantity.optionitem_id_4
				ORDER BY
					ec_optionitem.optionitem_order", $hours, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3 ) );
		}else{
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) as quantity 
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_4 = ec_optionitem.optionitem_id
					)
				WHERE 
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitemquantity.optionitem_id_1 = %d AND 
					ec_optionitemquantity.optionitem_id_2 = %d AND 
					ec_optionitemquantity.optionitem_id_3 = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_4
				GROUP BY
					ec_optionitemquantity.optionitem_id_1,
					ec_optionitemquantity.optionitem_id_2,
					ec_optionitemquantity.optionitem_id_3,
					ec_optionitemquantity.optionitem_id_4
				ORDER BY
					ec_optionitem.optionitem_order", $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3 ) );
		}
		
	}
	
	public static function get_option5_quantity_values( $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4 ){
	
		if( get_option( 'ec_option_stock_removed_in_cart' ) ){
			$hours = ( get_option( 'ec_option_tempcart_stock_hours' ) ) ? get_option( 'ec_option_tempcart_stock_hours' ) : 1;
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) - 
					(
						SELECT 
							COALESCE( SUM( ec_tempcart.quantity ), 0 ) 
						FROM 
							ec_tempcart 
						WHERE
							ec_tempcart.product_id = ec_optionitemquantity.product_id AND
							ec_tempcart.optionitem_id_1 = ec_optionitemquantity.optionitem_id_1 AND
							ec_tempcart.optionitem_id_2 = ec_optionitemquantity.optionitem_id_2 AND
							ec_tempcart.optionitem_id_3 = ec_optionitemquantity.optionitem_id_3 AND
							ec_tempcart.optionitem_id_4 = ec_optionitemquantity.optionitem_id_4 AND
							ec_tempcart.optionitem_id_5 = ec_optionitemquantity.optionitem_id_5 AND
							ec_tempcart.last_changed_date >= NOW( ) - INTERVAL %d " . get_option( 'ec_option_tempcart_stock_timeframe' ) . "
					) AS quantity
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_5 = ec_optionitem.optionitem_id 
					)
				WHERE 
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitemquantity.optionitem_id_1 = %d AND 
					ec_optionitemquantity.optionitem_id_2 = %d AND 
					ec_optionitemquantity.optionitem_id_3 = %d AND 
					ec_optionitemquantity.optionitem_id_4 = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_5 
				GROUP BY 
					ec_optionitemquantity.optionitem_id_1, 
					ec_optionitemquantity.optionitem_id_2, 
					ec_optionitemquantity.optionitem_id_3, 
					ec_optionitemquantity.optionitem_id_4, 
					ec_optionitemquantity.optionitem_id_5 
				ORDER BY 
					ec_optionitem.optionitem_order", $hours, $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4 ) );
		}else{
			return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
					ec_optionitem.optionitem_id,
					ec_optionitemquantity.is_stock_tracking_enabled as tracking,
					SUM( ec_optionitemquantity.quantity ) as quantity
				FROM 
					ec_optionitemquantity 
					LEFT JOIN ec_optionitem ON ( 
						ec_optionitemquantity.optionitem_id_5 = ec_optionitem.optionitem_id 
					)
				WHERE 
					ec_optionitemquantity.product_id = %d AND 
					ec_optionitemquantity.optionitem_id_1 = %d AND 
					ec_optionitemquantity.optionitem_id_2 = %d AND 
					ec_optionitemquantity.optionitem_id_3 = %d AND 
					ec_optionitemquantity.optionitem_id_4 = %d AND 
					ec_optionitem.optionitem_id = ec_optionitemquantity.optionitem_id_5 
				GROUP BY 
					ec_optionitemquantity.optionitem_id_1, 
					ec_optionitemquantity.optionitem_id_2, 
					ec_optionitemquantity.optionitem_id_3, 
					ec_optionitemquantity.optionitem_id_4, 
					ec_optionitemquantity.optionitem_id_5 
				ORDER BY 
					ec_optionitem.optionitem_order", $product_id, $optionitem_id_1, $optionitem_id_2, $optionitem_id_3, $optionitem_id_4 ) );
		}
		
	}
	
	public static function get_product( $model_number, $product_id = 0 ){
		
		$sql = "SELECT
				product.product_id,
				product.model_number,
				product.post_id,
				product.activate_in_store,
				product.title,
				product.description,
				product.short_description,
				product.seo_description,
				product.seo_keywords,
				product.price,
				product.list_price,
				product.show_custom_price_range,
				product.price_range_low,
				product.price_range_high,
				product.vat_rate,
				product.handling_price,
				product.handling_price_each,
				product.stock_quantity,
				product.min_purchase_quantity,
				product.max_purchase_quantity,
				product.weight,
				product.width,
				product.height,
				product.length,
				product.use_optionitem_quantity_tracking,
				product.use_specifications,
				product.specifications,
				product.use_customer_reviews,
				product.show_on_startup,
				product.show_stock_quantity,
				product.is_special,
				product.is_taxable,
				product.is_shippable,
                product.exclude_shippable_calculation,
				product.is_giftcard,
				product.is_download,
				product.is_donation,
				product.is_subscription_item,
				product.is_deconetwork,
				product.allow_backorders,
				product.backorder_fill_date,
				
				product.include_code,
				
				product.download_file_name,
				
				product.subscription_bill_length,
				product.subscription_bill_period,
				product.subscription_bill_duration,
				product.trial_period_days,
				product.stripe_plan_added,
				product.subscription_signup_fee,
				product.subscription_unique_id,
				product.subscription_prorate,
				
				product.use_advanced_optionset,
				product.use_both_option_types,
				product.use_optionitem_images,
				
				product.image1,
				product.image2,
				product.image3,
				product.image4,
				product.image5,
				product.product_images,
				
				product.featured_product_id_1,
				product.featured_product_id_2,
				product.featured_product_id_3,
				product.featured_product_id_4,
				
				product.catalog_mode,
				product.catalog_mode_phrase,
				
				product.inquiry_mode,
				product.inquiry_url,
				
				product.deconetwork_mode,
				product.deconetwork_product_id,
				product.deconetwork_size_id,
				product.deconetwork_color_id,
				product.deconetwork_design_id,

				product.display_type,
				product.image_hover_type,
				product.image_effect_type,
				product.tag_type,
				product.tag_bg_color,
				product.tag_text_color,
				product.tag_text,
				
				product.views
				
				FROM ec_product as product 
				
				WHERE product.model_number = %s OR product.product_id = %d";
				
		return self::$mysqli->get_row( self::$mysqli->prepare( $sql, $model_number, $product_id ) );
		
	}
	
	public static function deconetwork_add_to_cart( ){
		
		// First check if this is already in the cart
		$sql = "SELECT tempcart_id FROM ec_tempcart WHERE ec_tempcart.deconetwork_id = %s AND ec_tempcart.session_id = %s";
		$cartrow = self::$mysqli->get_row( self::$mysqli->prepare( $sql, (int) $_GET['id'], sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id ) ) );
		
		if( $cartrow->tempcart_id ){
			
			$sql = "UPDATE ec_tempcart SET ec_tempcart.quantity = %d, ec_tempcart.deconetwork_name = %s, ec_tempcart.deconetwork_product_code = %s, ec_tempcart.deconetwork_options = %s, ec_tempcart.deconetwork_edit_link = %s, ec_tempcart.deconetwork_color_code = %s, ec_tempcart.deconetwork_product_id = %s, ec_tempcart.deconetwork_image_link = %s, ec_tempcart.deconetwork_discount = %s, ec_tempcart.deconetwork_tax = %s, ec_tempcart.deconetwork_total = %s, ec_tempcart.deconetwork_version = ec_tempcart.deconetwork_version+1 WHERE ec_tempcart.tempcart_id = %d AND ec_tempcart.session_id = %s";
			self::$mysqli->query( self::$mysqli->prepare( $sql, (int) $_GET['qty'], sanitize_title( $_GET['name'] ), sanitize_title( $_GET['product_code'] ), sanitize_text_field( $_GET['options'] ), sanitize_text_field( $_GET['edit_link'] ), sanitize_text_field( $_GET['color'] ), sanitize_text_field( $_GET['product_id'] ), sanitize_text_field( $_GET['tn'] ), sanitize_text_field( $_GET['discount'] ), sanitize_text_field( $_GET['tax'] ), sanitize_text_field( $_GET['line_total'] ), $cartrow->tempcart_id, $GLOBALS['ec_cart_data']->ec_cart_id ) );
			
		}else{
			
			$sql = "INSERT INTO ec_tempcart( session_id, product_id, quantity, is_deconetwork, deconetwork_id, deconetwork_name, deconetwork_product_code, deconetwork_options, deconetwork_edit_link, deconetwork_color_code, deconetwork_product_id, deconetwork_image_link, deconetwork_discount, deconetwork_tax, deconetwork_total ) VALUES( %s, %d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )";
			self::$mysqli->query( self::$mysqli->prepare( $sql, $GLOBALS['ec_cart_data']->ec_cart_id, sanitize_text_field( $_GET['ec_product_id'] ), sanitize_text_field( $_GET['qty'] ), 1, sanitize_text_field( $_GET['id'] ), sanitize_text_field( $_GET['name'] ), sanitize_text_field( $_GET['product_code'] ), sanitize_text_field( $_GET['options'] ), sanitize_text_field( $_GET['edit_link'] ), sanitize_text_field( $_GET['color'] ), sanitize_text_field( $_GET['product_id'] ), sanitize_text_field( $_GET['tn'] ), sanitize_text_field( $_GET['discount'] ), sanitize_text_field( $_GET['tax'] ), sanitize_text_field( $_GET['line_total'] ) ) );
			
		}
		
	}
	
	public static function update_affirm_id( $order_id, $charge_id ){
		
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_order SET affirm_charge_id = %s WHERE ec_order.order_id = %d", $charge_id, $order_id ) );
		
	}
	
	public static function get_live_search_options( $search_val ){
		
		$results_array = array( );
		
		if( get_option( 'ec_option_search_title' ) || get_option( 'ec_option_search_model_number' ) ){
			if( get_option( 'ec_option_search_title' ) && get_option( 'ec_option_search_model_number' ) ){
				$sql = "SELECT ec_product.title FROM ec_product WHERE ( ec_product.title LIKE %s OR ec_product.model_number LIKE %s ) AND ec_product.activate_in_store = 1 LIMIT 10";
				$products = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%', '%' . $search_val . '%' ) );
			}else if( get_option( 'ec_option_search_title' ) ){
				$sql = "SELECT ec_product.title FROM ec_product WHERE ( ec_product.title LIKE %s ) AND ec_product.activate_in_store = 1 LIMIT 10";
				$products = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
			}else{
				$sql = "SELECT ec_product.title FROM ec_product WHERE ( ec_product.model_number LIKE %s ) AND ec_product.activate_in_store = 1 LIMIT 10";
				$products = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
			}
			$results_array = array_merge( $results_array, $products );
		}
		
		if( get_option( 'ec_option_search_manufacturer' ) ){
			$sql = "SELECT ec_manufacturer.name as title FROM ec_manufacturer WHERE ec_manufacturer.name LIKE %s LIMIT 10";
			$manufacturers = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
			$results_array = array_merge( $results_array, $manufacturers );
		}
		
		$sql = "SELECT ec_category.category_name as title FROM ec_category WHERE ec_category.is_active = 1 AND ec_category.category_name LIKE %s LIMIT 10";
		$categories = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
		$results_array = array_merge( $results_array, $categories );
		
		if( get_option( 'ec_option_search_menu' ) ){
			$sql = "SELECT ec_menulevel1.name as title FROM ec_menulevel1 WHERE ec_menulevel1.name LIKE %s LIMIT 10";
			$menu1 = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
			
			$sql = "SELECT ec_menulevel2.name as title FROM ec_menulevel2 WHERE ec_menulevel2.name LIKE %s LIMIT 10";
			$menu2 = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
			
			$sql = "SELECT ec_menulevel3.name as title FROM ec_menulevel3 WHERE ec_menulevel3.name LIKE %s LIMIT 10";
			$menu3 = self::$mysqli->get_results( self::$mysqli->prepare( $sql, '%' . $search_val . '%' ) );
			
			$results_array = array_merge( $results_array, $menu1, $menu2, $menu3 );
		}
		
		return $results_array;
	}
	
	public static function get_affiliate_rule( $affiliate_id, $product_id ){
		return self::$mysqli->get_row( self::$mysqli->prepare( "SELECT ec_affiliate_rule.* FROM ec_affiliate_rule, ec_affiliate_rule_to_product, ec_affiliate_rule_to_affiliate WHERE ec_affiliate_rule_to_affiliate.affiliate_id = %s AND ec_affiliate_rule_to_product.product_id = %d AND ec_affiliate_rule.affiliate_rule_id = ec_affiliate_rule_to_affiliate.affiliate_rule_id AND ec_affiliate_rule.affiliate_rule_id = ec_affiliate_rule_to_product.affiliate_rule_id AND ec_affiliate_rule.rule_active = 1", $affiliate_id, $product_id ) );
	}
	
	public static function get_total_cart_items_by_product_id( $product_id, $session_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT SUM( ec_tempcart.quantity ) FROM ec_tempcart WHERE ec_tempcart.product_id = %d AND ec_tempcart.session_id = %s AND ec_tempcart.session_id != '' AND ec_tempcart.session_id != 'deleted' AND ec_tempcart.session_id != 'not-set'", $product_id, $session_id ) );
	}
	
	public static function get_total_cart_items_with_grid_by_product_id( $product_id, $option_id, $session_id ){
		return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT SUM( ec_tempcart_optionitem.optionitem_value ) 
				FROM ec_tempcart_optionitem 
				LEFT JOIN ec_tempcart 
				ON ec_tempcart.tempcart_id = ec_tempcart_optionitem.tempcart_id 
				WHERE ec_tempcart.session_id = %s AND ec_tempcart.session_id != '' AND ec_tempcart.session_id != 'deleted' AND ec_tempcart.session_id != 'not-set'
				AND ec_tempcart.product_id = %d 
				AND ec_tempcart_optionitem.tempcart_id = ec_tempcart.tempcart_id
				AND ec_tempcart_optionitem.option_id = %d", $session_id, $product_id, $option_id ) );
	}
	
	public static function quick_add_to_cart( $model_number ){
		$product = self::$mysqli->get_row( self::$mysqli->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.model_number = %s", $model_number ) );
		if( $product ){
			$current_in_cart = self::$mysqli->get_var( self::$mysqli->prepare( "SELECT SUM( ec_tempcart.quantity ) FROM ec_tempcart WHERE ec_tempcart.product_id = %d AND ec_tempcart.session_id = %s", $product->product_id, $GLOBALS['ec_cart_data']->ec_cart_id ) );
			if( !$product->show_stock_quantity || ( $product->show_stock_quantity && $product->stock_quantity >= $current_in_cart + 1 ) ){
				self::$mysqli->query( self::$mysqli->prepare( "INSERT INTO ec_tempcart( `session_id`, `product_id`, `quantity`, `gift_card_message`, `gift_card_from_name`, `gift_card_to_name` ) VALUES( %s, %d, %s, '', '', '' )", $GLOBALS['ec_cart_data']->ec_cart_id, $product->product_id, 1 ) );
                $tempcart_id = self::$mysqli->insert_id;
				self::update_temp_cart_inventory( $session_id );
				return $tempcart_id;
			}else if( $current_in_cart > 0 ){
                return self::$mysqli->get_var( self::$mysqli->prepare( "SELECT ec_tempcart.tempcart_id FROM ec_tempcart WHERE ec_tempcart.product_id = %d AND ec_tempcart.session_id = %s", $product->product_id, $GLOBALS['ec_cart_data']->ec_cart_id ) );
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public static function get_download_list( $user_id ){
		return self::$mysqli->get_results( self::$mysqli->prepare( "SELECT 
				ec_download.*, ec_orderdetail.title, ec_orderdetail.orderdetail_id, ec_orderdetail.is_download, ec_orderstatus.is_approved
			FROM 
				ec_download, ec_order 
				LEFT JOIN ec_orderdetail ON ec_orderdetail.order_id = ec_order.order_id 
                LEFT JOIN ec_orderstatus ON ec_order.orderstatus_id = ec_orderstatus.status_id
			WHERE 
				ec_order.user_id = %d AND 
				ec_download.order_id = ec_order.order_id
			GROUP BY ec_download.download_id
            HAVING is_approved = 1", $user_id ) );
	}
	
	public static function get_rates_by_class( $cart ){
		$applicable_count = 0;
		$sql = "SELECT shipping_rate_id, shipping_class_id FROM ec_shipping_class_to_rate WHERE ";
		
		for( $i=0; $i<count( $cart ); $i++ ){
			if ( isset( $cart[ $i ]->shipping_class_id ) && 0 != $cart[ $i ]->shipping_class_id ) {
				if( $applicable_count > 0 )
					$sql .= " OR ";
				$sql .= "shipping_class_id = " . $cart[$i]->shipping_class_id;
				$applicable_count++;
			}
		}
		if( $applicable_count == 0 )
			$sql .= "1=1";
		
		$sql .= " ORDER BY shipping_rate_id ASC, shipping_class_id ASC";
		return self::$mysqli->get_results( $sql );
		$return_array = array( );
		foreach( $results as $result ){
			$return_array[] = $result->shipping_rate_id;
		}
		return $return_array;
	}
	
	public function update_stripe_taxrate_id( $taxrate_id, $stripe_taxrate_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_taxrate SET stripe_taxrate_id = %s WHERE ec_taxrate.taxrate_id = %d", $stripe_taxrate_id, $taxrate_id ) );
	}
	
	public function update_stripe_country_taxrate_id( $country_id, $stripe_taxrate_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_country SET stripe_taxrate_id = %s WHERE ec_country.id_cnt = %d", $stripe_taxrate_id, $country_id ) );
	}
	
	public static function update_details_stock_adjusted( $orderdetail_id ){
		self::$mysqli->query( self::$mysqli->prepare( "UPDATE ec_orderdetail SET ec_orderdetail.stock_adjusted = 1 WHERE ec_orderdetail.orderdetail_id = %d", $orderdetail_id ) );
	}
	
}

?>