<?php

class ec_productlist{
	private $mysqli;

	public $products = array();
	public $is_product_details;
	public $filter;
	public $paging;
	public $num_products;
	public $page_options;
	public $atts;

	public $cart_page;
	public $account_page;
	public $store_page;
	public $permalink_divider;

	function __construct ( $is_product_details = false, $menuid = "NOMENU", $submenuid = "NOSUBMENU", $subsubmenuid = "NOSUBSUBMENU", $manufacturerid = "NOMANUFACTURER", $groupid = "NOGROUP", $modelnumber = "NOMODELNUMBER", $page_options = NULL, $atts = false ) {
		$this->mysqli = new ec_db();

		$this->page_options = $page_options;
		$this->atts = $atts;

		$accountpageid = get_option( 'ec_option_accountpage' );
		$cartpageid = get_option( 'ec_option_cartpage' );
		$storepageid = get_option( 'ec_option_storepage' );

		if ( function_exists( 'icl_object_id' ) ) {
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		if ( function_exists( 'icl_object_id' ) ) {
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		if ( function_exists( 'icl_object_id' ) ) {
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->account_page = get_permalink( $accountpageid );
		$this->cart_page = get_permalink( $cartpageid );
		$this->store_page = get_permalink( $storepageid );

		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}

		if ( substr_count( $this->store_page, '?' ) ) {
			$this->permalink_divider = "&";
		} else {
			$this->permalink_divider = "?";
		}

		$this->filter = new ec_filter();
		if ( isset( $this->atts['elementor'] ) && $this->atts['elementor'] ) {
			$this->filter->add_filter_atts( $this->atts['productid'], $this->atts['category'], $this->atts['manufacturer'], $this->atts['status'], $this->atts['sorting_default'], $this->atts['sidebar_category_filter_method'] );
		}
		$this->set_shortcode_vals( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, $modelnumber );
		$this->paging = new ec_paging( $this->filter->perpage->selected );
		$this->is_product_details = $is_product_details;
		$this->get_products( );	
		if ( count( $this->products ) > 0 ) {
			$this->paging->update_product_count( $this->products[0]->total_products );
		} else {
			$this->paging->update_product_count( 0 );
		}
	}

	private function get_is_details() {
		return ( isset( $_GET['model_number'] ) ) ? true : false;
	}

	private function get_products() {
		if ( !$this->is_product_details ) {
			$result = $this->mysqli->get_product_list( $this->filter->get_where_query(), $this->filter->get_order_by_query( $this->page_options ), $this->paging->get_limit_query(), $GLOBALS['ec_cart_data']->ec_cart_id, $this->filter->get_cache_key( ), $this->filter->get_optionitems_filters( ), $this->filter->get_extra_left_joins() );
		} else {
			$result = $this->mysqli->get_product_list( $this->filter->get_where_query(), $this->filter->get_order_by_query( $this->page_options ), "", $GLOBALS['ec_cart_data']->ec_cart_id, $this->filter->get_cache_key( ) );
		}

		if ( count( $result ) > 0 ) {
			$this->num_products = $result[0]["product_count"];
		} else {
			$this->num_products = 0;
		}

		for ( $i=0; $i < count( $result ); $i++ ) {
			$product = new ec_product( $result[$i], 0, $this->get_is_details( ), 0, $i, $this->page_options );
			array_push( $this->products, $product );
		}
	}

	public function get_products_no_limit() {
		return $this->mysqli->get_product_list( $this->filter->get_where_query(), $this->filter->get_order_by_query( $this->page_options ), "", $GLOBALS['ec_cart_data']->ec_cart_id, "wpeasycart-all-products", $this->filter->get_optionitems_filters( ), $this->filter->get_extra_left_joins() );
	}

	public function display_product_list(){
		extract( shortcode_atts( array(
			'orderby' => '',
			'order' => 'ASC',
			'status' => '',
			'columns' => false,
			'elementor' => false,
			'cols_desktop' => 4,
			'cols_tablet' => 3,
			'cols_mobile' => 2,
			'cols_mobile_small' => 1,
			'margin' => '45px',
			'width' => '175px',
			'minheight' => '375px',
			'imagew' => '140px',
			'imageh' => '140px',
			'style' => '1',
			'layout_mode' => 'grid',
			'product_border' => true,
			'per_page' => true,
			'spacing' => 20,
			'product_style' => 'default',
			'product_align' => 'default',
			'product_visible_options' => 'title,price,rating,cart,quickview,desc',
			'product_rounded_corners' => false,
			'product_rounded_corners_tl' => 10,
			'product_rounded_corners_tr' => 10,
			'product_rounded_corners_bl' => 10,
			'product_rounded_corners_br' => 10
		), $this->atts ) );

		for ( $prod_index = 0; $prod_index < count( $this->products ); $prod_index++ ) {
			$product = $this->products[ $prod_index ];
			$list_view = false;
			if ( get_option( "ec_option_product_layout_type" ) == "list_only" ) {
				$list_view = true;
			}

			if ( $list_view ) {
				if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_list.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_list.php' );
				} else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product_list.php' ) ) {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_list.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product.php' );
				}
			}else{
				if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product.php' );
				}
			}
		}
	}

	public function display_filter_menu( $divider ) {
		if ( 1 == $this->filter->get_menu_level() ) {
			echo esc_attr( $this->filter->get_menu_link() );
		} else if ( 2 == $this->filter->get_menu_level() ) {
			echo esc_attr( $this->filter->get_menu_link() . $divider . $this->filter->get_submenu_link() );
		} else if ( 3 == $this->filter->get_menu_level() ) {
			echo esc_attr( $this->filter->get_menu_link() . $divider . $this->filter->get_submenu_link() . $divider . $this->filter->get_subsubmenu_link() );
		}
	}

	private function get_current_page_url() {
		$uri_parts = explode( '?', esc_url_raw( $_SERVER['REQUEST_URI'] ), 2 );
		$pageURL = 'http';
		if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ) {
			$pageURL .= 's';
		}
		$pageURL .= '://';
		$pageURL .= sanitize_text_field( $_SERVER['HTTP_HOST'] ) . $uri_parts[0];

		if ( isset( $_GET['page_id'] ) ) {
			$pageURL .= '?page_id=' . get_the_ID();
		}

		return $pageURL;
	}

	public function display_filter_combo( $default = false ) {
		global $language_data;
		$current_page = $this->get_current_page_url();

		if ( $default ) {
			$this->filter->default_first_filter = $default;
			$this->filter->current_filter = $this->filter->get_current_filter();
		}

		if ( substr_count( $current_page, '?' ) ) {
			$permalink_divider = '&';
		} else {
			$permalink_divider = '?';
		}

		echo "<select name=\"sortfield\" id=\"sortfield\" onchange=\"change_product_sort('" . esc_js( $this->filter->get_menu_id( ) ) . "', '" . esc_js( $this->filter->get_menu_name( ) ) . "', '" . esc_js( $this->filter->get_submenu_id( ) ) . "', '" . esc_js( $this->filter->get_submenu_name( ) ) . "', '" . esc_js( $this->filter->get_subsubmenu_id( ) ) . "', '" . esc_js( $this->filter->get_subsubmenu_name( ) ) . "', '" . esc_js( $this->filter->manufacturer->manufacturer_id ) . "', '" . esc_js( $this->filter->pricepoint_id ) . "', '" . esc_js( $this->paging->current_page ) . "', '" . esc_js( $this->filter->perpage->selected ) . "', '" . esc_js( $current_page ) . "', '" . esc_js( $permalink_divider ) . "', '" . esc_js( $this->filter->get_optionitems_filters( ) ) . "');\" class=\"ec_sort_menu no_wrap\">\n\n";

		if ( get_option( 'ec_option_product_filter_0' ) || $this->filter->is_sort_selected( 0 ) ) {
			echo "<option value=\"0\""; if( $this->filter->is_sort_selected( 0 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_default' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_1' ) || $this->filter->is_sort_selected( 1 ) ) {
			echo "<option value=\"1\""; if( $this->filter->is_sort_selected( 1 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_price_low' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_2' ) || $this->filter->is_sort_selected( 2 ) ) {
			echo "<option value=\"2\""; if( $this->filter->is_sort_selected( 2 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_price_high' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_3' ) || $this->filter->is_sort_selected( 3 ) ) {
			echo "<option value=\"3\""; if( $this->filter->is_sort_selected( 3 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_title_a' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_4' ) || $this->filter->is_sort_selected( 4 ) ) {
			echo "<option value=\"4\""; if( $this->filter->is_sort_selected( 4 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_title_z' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_5' ) || $this->filter->is_sort_selected( 5 ) ) {
			echo "<option value=\"5\""; if( $this->filter->is_sort_selected( 5 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_newest' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_8' ) || $this->filter->is_sort_selected( 8 ) ) {
			echo "<option value=\"8\""; if( $this->filter->is_sort_selected( 8 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_oldest' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_6' ) || $this->filter->is_sort_selected( 6 ) ) {
			echo "<option value=\"6\""; if( $this->filter->is_sort_selected( 6 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_rating' ) . "</option>\n\n";
		}

		if ( get_option( 'ec_option_product_filter_7' ) || $this->filter->is_sort_selected( 7 ) ) {
			echo "<option value=\"7\""; if( $this->filter->is_sort_selected( 7 ) ){ echo " selected=\"selected\""; } echo ">" . wp_easycart_language( )->get_text( 'sort_bar', 'sort_by_most_viewed' ) . "</option>\n\n";
		}

		echo "</select>\n\n";

	}

	public function display_items_per_page( $divider ) {
		echo esc_attr( $this->filter->get_items_per_page( $divider ) );
	}

	public function display_current_page() {
		echo esc_attr( $this->paging->current_page );
	}

	public function display_total_pages() {
		echo esc_attr( $this->paging->total_pages );
	}

	public function display_product_paging( $divider ) {
		echo esc_attr( $this->paging->display_paging_links( $divider, $this->filter->get_link_string( 0 ) ) );
	}

	public function set_shortcode_vals( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, $modelnumber ) {
		if ( $menuid != "NOMENU" ) {
			$this->filter->menulevel1 = new ec_menuitem( $menuid, 1 );
			$this->filter->forced_menu_level = 1;
		} else if( $submenuid != "NOSUBMENU" ) {
			$this->filter->menulevel2 = new ec_menuitem( $submenuid, 2 );
			$this->filter->forced_menu_level = 2;
		} else if( $subsubmenuid != "NOSUBSUBMENU" ) {
			$this->filter->menulevel3 = new ec_menuitem( $subsubmenuid, 3 );
			$this->filter->forced_menu_level = 3;
		}

		if ( $manufacturerid != "NOMANUFACTURER" ) {
			$this->filter->manufacturer = new ec_manufacturer( $manufacturerid, "" );
		}

		if ( $groupid != "NOGROUP" ) {
			$this->filter->group_id = $groupid;
		}

		if ( $modelnumber != "NOMODELNUMBER" ) {
			$this->filter->model_number = $modelnumber;
		}
	}
}
