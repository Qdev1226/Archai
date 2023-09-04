<?php

class ec_product_filter{
	
	public $menus;
	public $submenus;
	public $subsubmenus;
	public $manufacturers;
	public $categories;
	public $optionitems;
	public $products;
	
	public $min_price;
	public $max_price;
	public $search_term;
	
	public $orderby;
	
	public $num_per_page;
	public $current_page;
	public $limit_start;
	
	public $is_search;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		$this->menus = array( );
		$this->submenus = array( );
		$this->subsubmenus = array( );
		$this->categories = array( );
		$this->optionitems = array( );
		$this->products = array( );
		
		$this->min_price = -1;
		$this->max_price = -1;
		
		if( isset( $_GET['filternum'] ) )
			$this->set_orderby( (int) $_GET['filternum'] );
		else
			$this->set_orderby( get_option( 'ec_option_default_store_filter' ) );
		
		$this->num_per_page = $GLOBALS['ec_perpages']->selected;
		$this->current_page = $this->get_current_page( );
		$this->limit_start = ( $this->current_page - 1 ) * $this->num_per_page ;
		
		$this->is_search = false;
	}
	
	public function initialize( $menuid = "NOMENU", $submenuid = "NOSUBMENU", $subsubmenuid = "NOSUBSUBMENU", $manufacturerid = "NOMANUFACTURER", $groupid = "NOGROUP", $modelnumber = "NOMODELNUMBER" ){
		
		$this->initialize_menu( $menuid );
		$this->initialize_submenu( $submenuid );
		$this->initialize_subsubmenu( $subsubmenuid );
		$this->initialize_manufacturer( $manufacturerid );
		$this->initialize_category( $groupid );
		$this->initialize_product( $modelnumber );
		$this->initialize_optionitem( );
		$this->initialize_price_point( );
		$this->initialize_search( );
		
	}
	
	public function initialize_menu( $menuid ){
		
		if( $menuid != "NOMENU" ){
			$this->add_menu( $menuid );
		}else if( isset( $_GET['menuid'] ) ){
			$this->add_menu( (int) $_GET['menuid'] );
		}
		
	}
	
	public function initialize_submenu( $submenuid ){
		
		if( $submenuid != "NOSUBMENU" ){
			$this->add_submenu( $submenuid );
		}else if( isset( $_GET['submenuid'] ) ){
			$this->add_submenu( (int) $_GET['submenuid'] );
		}
		
	}
	
	public function initialize_subsubmenu( $subsubmenuid ){
		
		if( $subsubmenuid != "NOSUBSUBMENU" ){
			$this->add_subsubmenu( $subsubmenuid );
		}else if( isset( $_GET['subsubmenuid'] ) ){
			$this->add_subsubmenu( (int) $_GET['subsubmenuid'] );
		}
		
	}
	
	public function initialize_manufacturer( $manufacturerid ){
		
		if( $manufacturerid != "NOMANUFACTURER" ){
			$this->add_manufacturer( $manufacturerid );
		}else if( isset( $_GET['manufacturer'] ) ){
			$this->add_manufacturer( (int) $_GET['manufacturer'] );
		}
		
	}
	
	public function initialize_category( $groupid ){
		
		if( $groupid != "NOGROUP" ){
			$this->add_category( $groupid );
		}else if( isset( $_GET['group_id'] ) ){
			$this->add_category( (int) $_GET['group_id'] );
		}
		
	}
	
	public function initialize_product( $modelnumber ){
		
		if( $modelnumber != "NOMODELNUMBER" ){
			$this->add_product( $modelnumber );
		}else if( isset( $_GET['model_number'] ) ){
			$this->add_product( preg_replace( '/[^a-zA-Z0-9\-\_]/', '', sanitize_text_field( $_GET['model_number'] ) ) );
		}
		
	}
	
	public function initialize_optionitem( ){
		
		if( isset( $_GET['ec_optionitem_id'] ) ){
			$this->add_optionitem( (int) $_GET['ec_optionitem_id'] );
		}
		
	}
	
	public function initialize_price_point( ){
		
		if( isset( $_GET['pricepoint'] ) ){
			$pricepoint = $GLOBALS['ec_pricepoints']->get_pricepoint( (int) $_GET['pricepoint'] );
			$this->set_min_price( $pricepoint->low_point );
			if( $GLOBALS['ec_pricepoints']->high_point > 0 )
				$this->set_max_price( $GLOBALS['ec_pricepoints']->high_point );
			
		}
		
	}
	
	public function initialize_search( ){
		
		if( isset( $_GET['ec_search'] ) )
			$this->set_search( sanitize_text_field( $_GET['ec_search'] ) );
	
	}
	
	public function add_menu( $menu_id ){
		
		$this->menus[] = $menu_id;
		
	}
	
	public function add_submenu( $submenu_id ){
		
		$this->submenus[] = $submenu_id;
		
	}
	
	public function add_subsubmenu( $subsubmenu_id ){
		
		$this->subsubmenus[] = $subsubmenu_id;
		
	}
	
	public function add_category( $category_id ){
		
		$this->categories[] = $category_id;
		
	}
	
	public function add_product( $model_number ){
		
		$this->products[] = $model_number;
		
	}
	
	public function add_manufacturer( $manufacturer_id ){
		
		$this->manufacturers[] = $manufacturer_id;
		
	}
	
	public function add_optionitem( $optionitem_id ){
		
		$this->optionitems[] = $optionitem_id;
		
	}
	
	public function set_min_price( $min_price ){
		
		$this->min_price = $min_price;
		
	}
	
	public function set_max_price( $max_price ){
		
		$this->max_price = $max_price;
		
	}
	
	public function set_search( $search_term ){
		
		$this->search_term = $search_term;
		$this->is_search = true;
		
	}
	
	public function set_orderby( $method ){
		
		$this->orderby = $method;
		
	}
	
	public function get_current_page( ){
		if( isset( $_GET['pagenum'] ) )
			return intval( $_GET['pagenum'] );
		else
			return 1;
	}
	
	public function get_select( ){
		
		global $wpdb;
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS ec_product.*";
		if( $this->is_search ){
			$sql .= $wpdb->prepare( ",
				CASE WHEN product.title = %s THEN 20 ELSE 0 END + 
				CASE WHEN product.title LIKE %s THEN 3 ELSE 0 END + 
				CASE WHEN product.title LIKE %s THEN 2 ELSE 0 END + 
				CASE WHEN product.title LIKE %s THEN 1 ELSE 0 END AS search_match_score", 
				$this->search_term, 
				$this->search_term . "%", 
				"%" . $this->search_term . "%", 
				"%" . $this->search_term );
				
		}
		$sql .= " FROM ec_product";
		
		return $sql;
		
	}
	
	public function get_joins( ){
		
		$left_join = "";
		
		if( $this->is_search ){
			
			$left_join = " LEFT JOIN ec_manufacturer as manufacturer ON manufacturer.manufacturer_id = product.manufacturer_id

			LEFT JOIN ec_menulevel1 ON ( ec_menulevel1.menulevel1_id = product.menulevel1_id_1 OR ec_menulevel1.menulevel1_id = product.menulevel2_id_1 OR ec_menulevel1.menulevel1_id = product.menulevel3_id_1 )
			
			LEFT JOIN ec_menulevel2 ON ( ec_menulevel2.menulevel2_id = product.menulevel1_id_2 OR ec_menulevel2.menulevel2_id = product.menulevel2_id_2 OR ec_menulevel2.menulevel2_id = product.menulevel3_id_2 )
			
			LEFT JOIN ec_menulevel3 ON ( ec_menulevel3.menulevel3_id = product.menulevel1_id_3 OR ec_menulevel3.menulevel3_id = product.menulevel2_id_3 OR ec_menulevel3.menulevel3_id = product.menulevel3_id_3 )";
			
		}
		
		if( count( $this->categories ) > 0 ){
			$left_join .= "
			LEFT JOIN ec_categoryitem ON ec_categoryitem.product_id = product.product_id";
		}
		
		if( count( $this->optionitems ) > 0 ){
			$left_join .= "
			JOIN ec_optionitemquantity ON ec_optionitemquantity.product_id = product.product_id";
		}
		
		return $left_join;
		
	}
	
	public function get_where( ){
		
		$where = " WHERE ec_product.activate_in_store = 1";
		$where .= $this->get_menu_where( );
		$where .= $this->get_submenu_where( );
		$where .= $this->get_subsubmenu_where( );
		$where .= $this->get_category_where( );
		$where .= $this->get_manufacturer_where( );
		$where .= $this->get_product_where( );
		$where .= $this->get_price_where( );
		$where .= $this->get_optionitem_where( );
		$where .= $this->get_search_where( );
		
		return $where;
		
		
	}
	
	public function get_order( ){
		if( $this->is_search )
			return " ORDER BY search_match_score DESC, product.title ASC";
		else if($this->orderby == 0){
			
			$page_options = $GLOBALS['ec_page_options']->page_options;
			$order = json_decode( stripslashes( $page_options->product_order ) );
			$ret_string = " ORDER BY FIELD( model_number";
			foreach( $order as $model_number ){
				$ret_string .= ", '" . $model_number . "'";
			}
			$ret_string .= " )";
			return $ret_string;
		
		}else if( $this->orderby == 1)							return " ORDER BY product.price ASC";
		else if($this->orderby == 2)							return " ORDER BY product.price DESC";
		else if($this->orderby == 3)							return " ORDER BY product.title ASC";
		else if($this->orderby == 4)							return " ORDER BY product.title DESC";
		else if($this->orderby == 5)							return " ORDER BY product.added_to_db_date DESC";
		else if($this->orderby == 6)							return " ORDER BY review_average DESC";
		else if($this->orderby == 7)							return " ORDER BY product.views DESC";
		else													return " ORDER BY product.sort_position ASC, product.price ASC";
	}
	
	public function get_limit( ){
		
		global $wpdb;
		if( get_option( 'ec_option_enable_product_paging' ) && is_numeric( $this->limit_start ) && is_numeric( $this->num_per_page ) && $this->num_per_page > 0 )
			return $wpdb->prepare( " LIMIT %d, %d", $this->limit_start, $this->num_per_page );
		else
			return "";
		
	}
	
	private function get_menu_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->menus ) > 0 )
			$where .= " AND (";
		for( $i=0; $i<count( $this->menus ); $i++ ){
			
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_product.menulevel1_id_1 = %d OR ec_product.menulevel1_id_2 = %d OR ec_product.menulevel1_id_3 = %d", $this->menus[$i], $this->menus[$i], $this->menus[$i] );
			
		}
		if( count( $this->menus ) > 0 )
			$where .= ")";
			
		return $where;
		
	}
	
	private function get_submenu_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->submenus ) > 0 )
			$where .= " AND (";
		for( $i=0; $i<count( $this->submenus ); $i++ ){
			
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_product.menulevel2_id_1 = %d OR ec_product.menulevel2_id_2 = %d OR ec_product.menulevel2_id_3 = %d", $this->submenus[$i], $this->submenus[$i], $this->submenus[$i] );
			
		}
		if( count( $this->submenus ) > 0 )
			$where .= ")";
			
		return $where;
		
	}
	
	private function get_subsubmenu_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->subsubmenus ) > 0 )
			$where .= " AND (";
		for( $i=0; $i<count( $this->subsubmenus ); $i++ ){
			
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_product.menulevel3_id_1 = %d OR ec_product.menulevel3_id_2 = %d OR ec_product.menulevel3_id_3 = %d", $this->subsubmenus[$i], $this->subsubmenus[$i], $this->subsubmenus[$i] );
			
		}
		if( count( $this->subsubmenus ) > 0 )
			$where .= ")";
			
		return $where;
		
	}
	
	private function get_category_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->categories ) )
			$where .= " AND (";
		for( $i=0; $i<count( $this->categories ); $i++ ){
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_category.category_id = %d", $this->categories[$i] );
		}
		if( count( $this->categories ) )
			$where .= ")";
			
		return $where;
		
	}
	
	private function get_manufacturer_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->manufacturers ) )
			$where .= " AND (";
		for( $i=0; $i<count( $this->manufacturers ); $i++ ){
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_product.manufacturer_id = %d", $this->manufacturers[$i] );
		}
		if( count( $this->manufacturers ) )
			$where .= ")";
		
		return $where;
		
	}
	
	private function get_product_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->products ) )
			$where .= " AND (";
		for( $i=0; $i<count( $this->products ); $i++ ){
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_product.model_number = %s", $this->products[$i] );
		}
		if( count( $this->products ) )
			$where .= ")";
		
		return $where;
		
	}
	
	private function get_price_where( ){
		
		global $wpdb;
		$where = "";
		if( $this->min_price > -1 )
			$where .= $wpdb->prepare( " AND ec_product.price > %s", $this->min_price );
		if( $this->min_price > -1 && $this->max_price > -1 )
			$where .= " AND";
		if( $this->max_price > -1 )
			$where .= $wpdb->prepare( " AND ec_product.price < %s", $this->max_price );
		return $where;
		
	}
	
	private function get_optionitem_where( ){
		
		global $wpdb;
		$where = "";
		if( count( $this->optionitems ) )
			$where .= " AND ec_optionitemquantity.quantity > 0 AND (";
		for( $i=0; $i<count( $this->optionitems ); $i++ ){
			if( $i > 0 )
				$where .= " OR";
			$where .= $wpdb->prepare( " ec_optionitemquantity.optionitem_id_1 = %d OR ec_optionitemquantity.optionitem_id_2 = %d OR ec_optionitemquantity.optionitem_id_3 = %d OR ec_optionitemquantity.optionitem_id_4 = %d OR ec_optionitemquantity.optionitem_id_5 = %d ) )", 
				$this->optionitems[$i], $this->optionitems[$i], $this->optionitems[$i], $this->optionitems[$i], $this->optionitems[$i] );
		}
		if( count( $this->optionitems ) )
			$where .= ")";
		return $where;
		
	}
	
	private function get_search_where( ){
		
		global $wpdb;
		$where = "";
		if( $this->is_search ){
			$exploded_search = explode( ' ', $this->search );
			$where .= " AND (";
			$item_num = 0;
			foreach( $exploded_search as $search_item ){
				$search_clean = $wpdb->prepare( '%s', '%' . $search_item . '%' );
				
				$search_terms = array( );
				if( get_option( 'ec_option_search_title' ) )
					$search_terms[] = "product.title";
					
				if( get_option( 'ec_option_search_model_number' ) )
					$search_terms[] = "product.model_number";
				
				if( get_option( 'ec_option_search_manufacturer' ) )
					$search_terms[] = "manufacturer.name";
					
				if( get_option( 'ec_option_search_description' ) )
					$search_terms[] = "product.description";
				
				if( get_option( 'ec_option_search_short_description' ) )
					$search_terms[] = "product.short_description";
				
				if( get_option( 'ec_option_search_menu' ) ){
					$search_terms[] = "ec_menulevel1.name";
					$search_terms[] = "ec_menulevel2.name";
					$search_terms[] = "ec_menulevel3.name";
				}
				
				$search_terms = apply_filters( 'wpeasycart_search_terms', $search_terms );
				
				if( $item_num > 0 && get_option( 'ec_option_search_by_or' ) )
					$where .= " OR";
				else if( $item_num > 0 )
					$where .= " AND";
				
				$where .= " ( ";
				for( $j=0; $j<count( $search_terms ); $j++ ){
					if( $j > 0 )
						$where .= " OR ";
					$where .= $search_terms[$j] . " LIKE " . $search_clean;
				}
				$where .= " ) ";
				$item_num++;
			}
			$where .= " )";
		}
		return $where;
		
	}
	
}

?>