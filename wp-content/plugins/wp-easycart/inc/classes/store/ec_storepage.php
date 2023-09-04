<?php

class ec_storepage{

	private $mysqli;										// ec_db structure

	private $is_details;									// BOOL
	private $product_list;									// ec_productlist structure
	private $category_list;									// ec_categorylist structure
	private $product;										// ec_product structure
	private $model_number;									// VARCHAR 255
	private $optionitem_id;									// INT
	private $category_view;									// BOOL

	public $previous_model_number;							// VARCHAR 255
	public $number_in_product_list;							// INT
	public $count_of_product_list;							// INT
	public $next_model_number;								// VARCHAR 255
	public $cart_page;
	public $account_page;
	public $store_page;
	public $permalink_divider;

	// Short Code Info
	public $menu_id;
	public $submenu_id;
	public $subsubmenu_id;
	public $manufacturer_id;
	public $group_id;
	public $atts;


	// Page Options
	public $page_options;									// Array

	////////////////////////////////////////////////////////
	// CONSTUCTOR FUNCTION
	////////////////////////////////////////////////////////
	function __construct( $menuid = "NOMENU", $submenuid = "NOSUBMENU", $subsubmenuid = "NOSUBSUBMENU", $manufacturerid = "NOMANUFACTURER", $groupid = "NOGROUP", $modelnumber = "NOMODELNUMBER", $atts = false ){

		$this->mysqli = new ec_db( );
		$GLOBALS['ec_page_options'] = new ec_page_options();
		if ( isset( $GLOBALS['ec_page_options'] ) ) {
			$this->page_options = $GLOBALS['ec_page_options']->page_options;
		} else {
			$this->page_options = array( );
		}
		$this->atts = $atts;

		$this->update_statistics( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, $modelnumber );

		if ( 'NOMODELNUMBER' != $modelnumber ) {
			$this->is_details = true;
		} else {
			$this->is_details = $this->get_is_details();
		}

		if( ! $this->is_details ) {
			$this->setup_products(  $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid );
		} else {
			if ( 'NOMODELNUMBER' != $modelnumber ) {
				$this->model_number = $modelnumber;
			} else {
				$this->model_number = sanitize_text_field( $_GET['model_number'] );
			}
			if ( isset( $_GET['optionitem_id'] ) ) {
				$this->optionitem_id = (int) $_GET['optionitem_id'];
			} else {
				$this->optionitem_id = 0;
			}
			$this->setup_details();
		}

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
			$https_class = new WordPressHTTPS( );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}

		if ( substr_count( $this->store_page, '?' ) ) {
			$this->permalink_divider = '&';
		} else {
			$this->permalink_divider = '?';
		}

		$this->menu_id = $menuid;
		$this->submenu_id = $submenuid;
		$this->subsubmenu_id = $subsubmenuid;
		$this->manufacturer_id = $manufacturerid;
		$this->group_id = $groupid;
	}

	private function setup_products( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid ) {
		$this->product_list = new ec_productlist( false, $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, "", $this->page_options, $this->atts );
		$this->category_list = new ec_categorylist( $groupid );
	}

	private function setup_details() {
		$db = new ec_db();
		global $wpdb;
		$products = $db->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s AND product.activate_in_store = 1", $this->model_number ), "", "", "", "wpeasycart-product-only-".$this->model_number );
		if ( count( $products ) > 0 ) {
			$this->product = new ec_product( $products[0], 0, 1, 0 );
		} else {
			global $wp_query;
			$wp_query->is_404 = true;
			$wp_query->is_single = false;
			$wp_query->is_page = false;
			include( get_query_template( '404' ) );
			exit();
		}
	}

	public function display_store_success() {
		$model_number = "";
		$title = "";
		if( isset( $_GET['model'] ) ){
			$model_number = sanitize_text_field( $_GET['model'] );
			$db = new ec_db( );
			global $wpdb;
			$products = $db->get_product_list( $wpdb->prepare( "WHERE product.model_number = %s", $model_number ), "", "", "", "wpeasycart-product-only-".$model_number );
			if( count( $products ) > 0 ){
				$title = $products[0]['title'];
			}
		}

		$success_notes = array(
			"addtocart" => wp_easycart_language( )->get_text( "ec_success", "store_added_to_cart" ),
			"inquiry_sent" => wp_easycart_language( )->get_text( "ec_success", "inquiry_sent" )
		);

		if ( isset( $_GET['ec_store_success'] ) && $_GET['ec_store_success'] != "addtocart" ) {
			echo "<div class=\"ec_cart_success\"><div>" . esc_attr( $success_notes[ sanitize_key( $_GET['ec_store_success'] ) ] ) . "</div></div>";
		} else if ( isset( $_GET['ec_store_success'] ) ) {
			echo "<div class=\"ec_cart_success\"><div>" . esc_attr( str_replace( "[prod_title]", wp_easycart_language( )->convert_text( $title ), esc_attr( $success_notes[ sanitize_key( $_GET['ec_store_success'] ) ] ) ) ) . " ";
			$cartpage = new ec_cartpage( );
			$cartpage->display_checkout_button( wp_easycart_language( )->get_text( 'cart', 'cart_checkout' ) );
			echo "</div></div>";
		}
	}

	public function display_store_error(){
		$error_notes = array(
			"minquantity" => wp_easycart_language( )->get_text( "ec_errors", "minquantity" ) 
		);
		echo "<div class=\"ec_cart_error\"><div>" . esc_attr( $error_notes[ sanitize_key( $_GET['ec_store_error'] ) ] ) . "</div></div>";
	}

	public function display_store_page( ){

		if( get_option( 'ec_option_restrict_store' ) ){
			$restricted = explode( "***", get_option( 'ec_option_restrict_store' ) );
		}
		//current_user_can('wpec_manager') || 
		if( !get_option( 'ec_option_restrict_store' ) || $GLOBALS['ec_user']->user_level == "admin" || in_array( $GLOBALS['ec_user']->user_level, $restricted ) ){
			$paging = 1;
			if( isset( $_GET['pagenum'] ) )
				$paging = (int) $_GET['pagenum'];
			if( isset( $_GET['ec_store_success'] ) )			$this->display_store_success( );
			if( isset( $_GET['ec_store_error'] ) )				$this->display_store_error( );
			if( !$this->is_details && $this->category_list->num_categories > 0 && get_option( 'ec_option_show_featured_categories' ) && $paging == 1 )
																$this->display_category_view( );
			if(	!$this->is_details )							$this->display_products_page( );
			else												$this->display_product_details_page( );
		}else{

			$this->show_restricted_store( );

		}

	}

	public function display_category_page( ){
		$this->display_category_view( );
	}

	private function display_category_view( ){
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_category_view.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_category_view.php' );	
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_category_view.php' );
	}

	private function display_products_page( ){
		extract( shortcode_atts( array(
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
			'product_rounded_corners_br' => 10,
			'paging' => true,
			'sorting' => true,
			'sorting_default' => 0,
			'sidebar' => false,
			'sidebar_position' => 'left',
			'sidebar_filter_clear' => true,
			'sidebar_include_search' => true,
			'sidebar_include_categories' => true,
			'sidebar_include_categories_first' => true,
			'sidebar_categories' => '',
			'sidebar_include_category_filters' => false,
			'sidebar_category_filter_id' => 0,
			'sidebar_category_filter_method' => 'AND',
			'sidebar_category_filter_open' => true,
			'sidebar_include_option_filters' => true,
			'sidebar_option_filters' => ''
		), $this->atts ) );

		if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
			if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) {
				echo '<script>
				jQuery( document ).ready( function() {
					dataLayer.push({ ecommerce: null });
					dataLayer.push({
						event: "view_item_list",
						ecommerce: {
							item_list_id: "products",
							item_list_name: "' . esc_attr__( 'Products', 'wp-easycart' ) . '",
							items: [';
							for( $i=0; $i < count( $this->product_list->products ); $i++ ) {
								echo '{
									item_id: "' . esc_attr( $this->product_list->products[$i]->model_number ) . '",
									item_name: "' . esc_attr( $this->product_list->products[$i]->title ) . '",
									index: ' . $i . ',
									price: ' . esc_attr( number_format( $this->product_list->products[$i]->price, 2, '.', '' ) ) . ',
									item_brand: "' . esc_attr( $this->product_list->products[$i]->manufacturer_name ) . '",
									quantity: 1
								},';
							}
							echo ' ]
						}
					} );
				} );
				</script>';
			} else {
				echo '<script>
				jQuery( document ).ready( function() {
					gtag( "event", "view_item_list", {
							item_list_id: "products",
							item_list_name: "' . esc_attr__( 'Products', 'wp-easycart' ) . '",
							items: [';
						for( $i=0; $i < count( $this->product_list->products ); $i++ ) {
							echo '{
								item_id: "' . esc_attr( $this->product_list->products[$i]->model_number ) . '",
								item_name: "' . esc_attr( $this->product_list->products[$i]->title ) . '",
								index: ' . $i . ',
								price: ' . esc_attr( number_format( $this->product_list->products[$i]->price, 2, '.', '' ) ) . ',
								item_brand: "' . esc_attr( $this->product_list->products[$i]->manufacturer_name ) . '",
								quantity: 1
							},';
						}
						echo ' ]
					} );
				} );
				</script>';
			}
		}

		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_page.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_page.php' );	
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_page.php' );
	}

	public function has_products( ){
		if( count( $this->product_list->products ) > 0 )
			return true;
		else
			return false;
	}

	private function display_product_details_page( ){
		$storepageid = get_option('ec_option_storepage');
		$cartpageid = get_option('ec_option_cartpage');
		$accountpageid = get_option('ec_option_accountpage');

		$storepage = get_permalink( $storepageid );
		$cartpage = get_permalink( $cartpageid );
		$accountpage = get_permalink( $accountpageid );

		if(substr_count($storepage, '?'))							$permalinkdivider = "&";
		else														$permalinkdivider = "?";

		if ( get_option( 'ec_option_googleanalyticsid' ) != "UA-XXXXXXX-X" && get_option( 'ec_option_googleanalyticsid' ) != "" ) {
			echo "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', '" . esc_js( get_option( 'ec_option_googleanalyticsid' ) ) . "', 'auto');ga('send', 'pageview');ga('require', 'ec');ga('ec:addImpression',{'id': '" . esc_js( $this->product->model_number ) . "','name': '" . esc_js( $this->product->title ) . "','price': '" . esc_js( number_format( $this->product->price, 2, '.', '' ) ) . "',});ga('send', 'pageview');function  ec_google_addToCart( ){ga('create', '" . esc_js( get_option( 'ec_option_googleanalyticsid' ) ) . "', 'auto');ga('require', 'ec');ga('ec:addProduct', {'id': '" . esc_js( $this->product->model_number ) . "','name': '" . esc_js( $this->product->title ) . "','price': '" . esc_js( number_format( $this->product->price, 2, '.', '' ) ) . "','quantity': document.getElementById( 'product_quantity_" . esc_js( $this->product->model_number ) . "' )});ga('ec:setAction', 'add');ga('send', 'event', 'UX', 'click', 'add to cart');}</script>";
		}

		if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
			if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) {
				echo '<script>
				jQuery( document ).ready( function() {
					dataLayer.push( { ecommerce: null } );
					dataLayer.push( {
						event: "view_item",
						ecommerce: {
							currency: "' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '",
							value: ' . esc_attr( number_format( $this->product->price, 2, '.', '' ) ) . ',
							items: [ {
								item_id: "' . esc_attr( $this->product->model_number ) . '",
								item_name: "' . esc_attr( $this->product->title ) . '",
								index: 0,
								price: ' . esc_attr( number_format( $this->product->price, 2, '.', '' ) ) . ',
								item_brand: "' . esc_attr( $this->product->manufacturer_name ) . '",
								quantity: 1
							} ]
						}
					} );
				} );
				</script>';
			} else {
				echo '<script>
				jQuery( document ).ready( function() {
					gtag( "event", "view_item", {
						currency: "' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '",
						value: ' . esc_attr( number_format( $this->product->price, 2, '.', '' ) ) . ',
						items: [ {
							item_id: "' . esc_attr( $this->product->model_number ) . '",
							item_name: "' . esc_attr( $this->product->title ) . '",
							index: 0,
							price: ' . esc_attr( number_format( $this->product->price, 2, '.', '' ) ) . ',
							item_brand: "' . esc_attr( $this->product->manufacturer_name ) . '",
							quantity: 1
						} ]
					} );
				} );
				</script>';
			}
		}

		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_details_page.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_details_page.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_details_page.php' );

		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/admin_panel.php" ) )
			echo "<script>ec_initialize_options();</script>";
	}

	////////////////////////////////////////////////////////
	// PRODUCT MENU FILTER BAR DISPLAY FUNCTIONS
	////////////////////////////////////////////////////////
	private function product_menu_filter( ){
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_menu_filter.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_menu_filter.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_menu_filter.php' );
	}

	private function product_filter_menu_items( $divider ){
		$this->product_list->display_filter_menu( $divider );
	}

	////////////////////////////////////////////////////////
	// PRODUCT FILTER AND PAGESET BAR DISPLAY FUNCTIONS
	////////////////////////////////////////////////////////
	private function product_filter_bar( ){
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_filter_bar.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_filter_bar.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_filter_bar.php' );
	}

	private function product_filter_combo( $default = false ){
		$this->product_list->display_filter_combo( $default );
	}

	private function product_items_per_page( $divider ){
		$this->product_list->display_items_per_page( $divider );
	}

	private function product_current_page( ){
		$this->product_list->display_current_page( );
	}

	private function product_total_pages( ){
		$this->product_list->display_total_pages( );
	}

	private function product_paging( $divider ){
		$this->product_list->display_product_paging( $divider );
	}

	////////////////////////////////////////////////////////
	// PRODUCT DISPLAY FUNCTIONS
	////////////////////////////////////////////////////////
	private function product_list( ){
		$this->product_list->display_product_list( );
	}

	private function category_list( ){
		$this->category_list->display_category_list( );
	}

	////////////////////////////////////////////////////////
	// PRODUCT DETAILS DISPLAY FUNCTIONS
	////////////////////////////////////////////////////////	
	public function display_product_previous_category_link( $link_text ){
		if( $this->previous_model_number != "" && $this->product->product_id )
			echo "<a href=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "model_number=" . esc_attr( $this->previous_model_number . $this->product->get_additional_link_options( ) ) . "\" class=\"ec_product_title_link\">" . esc_attr( $link_text ) . "</a>";
		else
			echo esc_attr( $link_text );
	}

	public function display_product_number_in_category_list( ){
		echo esc_attr( $this->number_in_product_list );
	}

	public function display_product_count_in_category_list( ){
		echo esc_attr( $this->count_of_product_list );
	}

	public function display_product_next_category_link( $link_text ){
		if( $this->next_model_number )
		  echo "<a href=\"" . esc_attr( $this->store_page . $this->permalink_divider ) . "model_number=" . esc_attr( $this->next_model_number . $this->product->get_additional_link_options( ) ) . "\" class=\"ec_product_title_link\">" . esc_attr( $link_text ) . "</a>";
		else
		  echo esc_attr( $link_text );
	}

	public function display_optional_banner( ){
		$menu_level = $this->product_list->filter->get_menu_level( );
		if( $menu_level == 1 && isset( $this->product_list->filter->menulevel1->menu_id ) ){
			$menu_row = $GLOBALS['ec_menu']->get_menu_row( $this->product_list->filter->menulevel1->menu_id, 1 );
		}else if( $menu_level == 2 && isset( $this->product_list->filter->menulevel2->menu_id ) ){
			$menu_row = $GLOBALS['ec_menu']->get_menu_row( $this->product_list->filter->menulevel2->menu_id, 2 );
		}else if( $menu_level == 3 && isset( $this->product_list->filter->menulevel3->menu_id ) ){
			$menu_row = $GLOBALS['ec_menu']->get_menu_row( $this->product_list->filter->menulevel3->menu_id, 3 );
		}

		if( isset( $menu_row ) ){
			if( $menu_row->banner_image != "" ){
				if( substr( $menu_row->banner_image, 0, 7 ) == 'http://' || substr( $menu_row->banner_image, 0, 8 ) == 'https://' )
					echo "<img src=\"" . esc_attr( $menu_row->banner_image ) . "\" alt=\"" . esc_attr( $menu_row->name ) . "\" />";	
				else if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/banners/" . $menu_row->banner_image ) )	
					echo "<img src=\"" . esc_attr( plugins_url( "wp-easycart-data/products/banners/" . $menu_row->banner_image, EC_PLUGIN_DATA_DIRECTORY ) ) . "\" alt=\"" . esc_attr( $menu_row->name ) . "\" />";	
				else
					echo "<img src=\"" . esc_attr( plugins_url( "wp-easycart/products/banners/" . $menu_row->banner_image, EC_PLUGIN_DIRECTORY ) ) . "\" alt=\"" . esc_attr( $menu_row->name ) . "\" />";	
			}
		}
	}

	public function has_banner( ){
		$menu_level = $this->product_list->filter->get_menu_level( );
		if( $menu_level == 1 && isset( $this->product_list->filter->menulevel1->menu_id ) ){
			$menu_row = $GLOBALS['ec_menu']->get_menu_row( $this->product_list->filter->menulevel1->menu_id, 1 );
		}else if( $menu_level == 2 && isset( $this->product_list->filter->menulevel2->menu_id ) ){
			$menu_row = $GLOBALS['ec_menu']->get_menu_row( $this->product_list->filter->menulevel2->menu_id, 2 );
		}else if( $menu_level == 3 && isset( $this->product_list->filter->menulevel3->menu_id ) ){
			$menu_row = $GLOBALS['ec_menu']->get_menu_row( $this->product_list->filter->menulevel3->menu_id, 3 );
		}

		if( isset( $menu_row ) && isset( $menu_row->banner_image ) && $menu_row->banner_image != "" ){
			return true;
		}else{
			return false;
		}
	}

	public function get_products_no_limit( ){

		return $this->product_list->get_products_no_limit( );

	}

	private function show_restricted_store( ){

		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_restricted.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product_restricted.php' );
		else
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product_restricted.php' );

	}

	public function get_option_filter_url( $optionitem_id, $cat_i = 0, $category_id = false ){
		$url = $this->get_current_url( );

		$amp_status = '';

		if( isset( $_GET['perpage'] ) ){
			$url .= $amp_status . "perpage=" . (int) $_GET['perpage'];
			$amp_status = '&';
		}

		if( isset( $_GET['manufacturer'] ) ){
			$url .= $amp_status . "manufacturer=" . htmlentities( sanitize_text_field( $_GET['manufacturer'] ), ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['pricepoint'] ) ){
			$url .= $amp_status . "pricepoint=" . htmlentities( (int) $_GET['pricepoint'], ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['ec_search'] ) ){
			$url .= $amp_status . "ec_search=" . htmlentities( sanitize_text_field( $_GET['ec_search'] ), ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['group_id'] ) ){
			$url .= $amp_status . "group_id=" . htmlentities( sanitize_text_field( $_GET['group_id'] ), ENT_QUOTES );
			$amp_status = '&';
		}

		if ( $optionitem_id != 'clear' ) {
			for ( $i = 0; $i < 20; $i++ ) {
				if ( isset( $_GET[ 'group_id_' . $i ] ) ) {
					if ( $i == $cat_i && $category_id ) {
						$filter_group_data_raw = ( strlen( trim( sanitize_text_field( $_GET[ 'group_id_' . $i ] ) ) ) > 0 ) ? explode( ',', sanitize_text_field( $_GET[ 'group_id_' . $i ] ) ) : array();
						$filters_group = array();
						$found = false;
						for ( $filter_i = 0; $filter_i < count( $filter_group_data_raw ); $filter_i++ ) {
							if ( $category_id == (int) $filter_group_data_raw[$filter_i] ) {
								$found = true;
							} else if ( 0 != (int) $category_id ) {
								$filters_group[] = (int) $filter_group_data_raw[$filter_i];
							}
						}
						if ( ! $found && 0 != (int) $category_id ) {
							 $filters_group[] = (int) $category_id;
						}
						if ( count ( $filters_group ) > 0 ) {
							$url .= $amp_status . 'group_id_' . $i . '=' . htmlentities( implode( ',', $filters_group ), ENT_QUOTES );
							$amp_status = '&';
						}
					} else {
						$url .= $amp_status . 'group_id_' . $i . '=' . htmlentities( sanitize_text_field( $_GET[ 'group_id_' . $i ] ), ENT_QUOTES );
						$amp_status = '&';
					}
				} else if ( $i == $cat_i ) {
					$url .= $amp_status . 'group_id_' . $i . '=' . htmlentities( sanitize_text_field( $category_id ), ENT_QUOTES );
					$amp_status = '&';
				}
			}
		}

		if( isset( $_GET['menuid'] ) ){
			$url .= $amp_status . "menuid=" . htmlentities( (int) $_GET['menuid'], ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['submenuid'] ) ){
			$url .= $amp_status . "submenuid=" . htmlentities( (int) $_GET['submenuid'], ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['subsubmenuid'] ) ){
			$url .= $amp_status . "subsubmenuid=" . htmlentities( (int) $_GET['subsubmenuid'], ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['filternum'] ) ){
			$url .= $amp_status . "filternum=" . htmlentities( (int) $_GET['filternum'], ENT_QUOTES );
			$amp_status = '&';
		}

		if( isset( $_GET['pagenum'] ) ){
			$url .= $amp_status . "pagenum=" . htmlentities( (int) $_GET['pagenum'], ENT_QUOTES );
			$amp_status = '&';
		}

		if ( $optionitem_id != 'clear' && isset( $_GET['filter_option'] ) ) {
			$filter_data_raw = ( strlen( trim( sanitize_text_field( $_GET['filter_option'] ) ) ) > 0 ) ? explode( ',', sanitize_text_field( $_GET['filter_option'] ) ) : array();
			$filters = array();
			$found = false;
			for ( $filter_i = 0; $filter_i < count( $filter_data_raw ); $filter_i++ ) {
				if ( $optionitem_id == (int) $filter_data_raw[$filter_i] ) {
					$found = true;
				} else if ( 0 != (int) $optionitem_id ) {
					$filters[] = (int) $filter_data_raw[$filter_i];
				}
			}
			if ( ! $found && 0 != (int) $optionitem_id ) {
				 $filters[] = (int) $optionitem_id;
			}
			if ( count ( $filters ) > 0 ) {
				$url .= $amp_status . "filter_option=" . htmlentities( implode( ',', $filters ), ENT_QUOTES );
			}
		}else if( $optionitem_id != 'clear' && 0 != (int) $optionitem_id ){
			$url .= $amp_status . "filter_option=" . (int) $optionitem_id;
		}

		return $url;
	}

	private function get_current_url( ){
		$page_url = 'http';
		if( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ){
			$page_url .= "s";
		}

		$page_url .= "://";

		if( (int) $_SERVER["SERVER_PORT"] != 80 && (int) $_SERVER["SERVER_PORT"] != 443 ) {
			$page_url .= sanitize_text_field( $_SERVER["SERVER_NAME"] ) . ":" . (int) $_SERVER["SERVER_PORT"];

		}else{
			$page_url .= sanitize_text_field( $_SERVER["SERVER_NAME"] );

		}

		if( substr_count( $page_url, '?' ) )						
			$page_url .= "&";
		else																
			$page_url = "?";

		return $page_url;
	}

	////////////////////////////////////////////////////////
	// MAIN HELPER FUNCTIONS
	////////////////////////////////////////////////////////
	private function get_is_details( ){
		if( isset( $_GET['model_number'] ) )					return true;
		else													return false;
	}

	private function update_statistics( $menuid, $submenuid, $subsubmenuid, $manufacturerid, $groupid, $modelnumber ){

		$db = new ec_db( );
		if( $modelnumber != "NOMODELNUMBER" ){
			$db->update_product_views( $modelnumber );
		}else if( $menuid != "NOMENU" ){
			$db->update_menu_views( $menuid );
		}else if( $submenuid != "NOSUBMENU" ){
			$db->update_submenu_views( $submenuid );
		}else if( $subsubmenuid != "NOSUBSUBMENU" ){
			$db->update_subsubmenu_views( $subsubmenuid );
		}
	}
}
