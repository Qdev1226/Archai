<?php 
class ec_categorywidget extends WP_Widget{
	
	function __construct( ){
		$widget_ops = array('classname' => 'ec_categorywidget', 'description' => 'Displays the Top Level Menu For Your WP EasyCart' );
		parent::__construct('ec_categorywidget', 'WP EasyCart Top Level Menu Filter', $widget_ops);
	}
	
	function form($instance){
		if( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = __( 'Shop by Menu', 'wp-easycart' );
		}
		
		if( isset( $instance[ 'up_level_text' ] ) ) {
			$up_level_text = $instance[ 'up_level_text' ];
		}else {
			$up_level_text = __( 'Up a Level', 'wp-easycart' );
		}
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\">" . esc_attr__( 'Title', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'title' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\" type=\"text\" value=\"" . esc_attr( $title ) . "\" /></p>";
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'up_level_text' ) ) . "\">" . esc_attr__( 'Up a Level Text', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'up_level_text' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'up_level_text' ) ) . "\" type=\"text\" value=\"" . esc_attr( $up_level_text ) . "\" /></p>";
		
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults);
	}
	
	function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['up_level_text'] = ( !empty( $new_instance['up_level_text'] ) ) ? strip_tags( $new_instance['up_level_text'] ) : '';

		return $instance;
	}
	
	
	function widget($args, $instance){
	
		extract( $args );
		if( isset( $instance['title'] ) )
			$title = apply_filters( 'widget_title', $instance['title'] );
		else
			$title = "";
		if( isset( $instance['up_level_text'] ) )
			$up_level_text = apply_filters( 'widget_up_level_text', $instance['up_level_text'] );
		else
			$up_level_text = "";
		
		// Translate if Needed
		$title = wp_easycart_language( )->convert_text( $title );
		$up_level_text = wp_easycart_language( )->convert_text( $up_level_text );
	
		echo wp_easycart_escape_html( $before_widget );
		if ( ! empty( $title ) )
			echo wp_easycart_escape_html( $before_title . $title . $after_title );
		
		// WIDGET CODE GOES HERE
		$mysqli = new ec_db();
		
		//Required for old linking layouts /////DO NOT DELETE////
		$store_page_id = get_option('ec_option_storepage');
		if( function_exists( 'icl_object_id' ) ){
			$store_page_id = icl_object_id( $store_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$store_page = get_permalink( $store_page_id );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$store_page = $https_class->makeUrlHttps( $store_page );
		}
		
		if( substr_count( $store_page, '?' ) )					$permalink_divider = "&";
		else													$permalink_divider = "?";
		//Required for old linking layouts //////DO NOT DELETE////
		
		
		if( isset( $_GET['menuid'] ) || isset( $_GET['submenuid'] ) || isset( $_GET['subsubmenuid'] ) ){
			//Old Linking Format Code
			if( isset( $_GET['menuid'] ) ){
				$level = 1;
				$menu_id = (int) $_GET['menuid'];
				$submenu_id = 0;
				$subsubmenu_id = 0;
			}else if( isset( $_GET['submenuid'] ) ){
				$level = 2;
				$menu_id = 0;
				$submenu_id = (int) $_GET['submenuid'];
				$subsubmenu_id = 0;
			}else if( isset( $_GET['subsubmenuid'] ) ){
				$level = 3;
				$menu_id = 0;
				$submenu_id = 0;
				$subsubmenu_id = (int) $_GET['subsubmenuid'];
			}else{
				$level = 0;
				$menu_id = 0;
				$submenu_id = 0;
				$subsubmenu_id = 0;
			}
		}else{
			//New Linking Format Code
			global $wp_query;
			$post_obj = $wp_query->get_queried_object();
			if( isset( $post_obj ) && isset( $post_obj->ID ) ){
				$post_id = $post_obj->ID;
				$menulevel1 = $GLOBALS['ec_menu']->get_menu_row_from_post_id( $post_id, 1 );
				$menulevel2 = $GLOBALS['ec_menu']->get_menu_row_from_post_id( $post_id, 2 );
				$menulevel3 = $GLOBALS['ec_menu']->get_menu_row_from_post_id( $post_id, 3 );
				
				if( $menulevel1 ){
					$level = 1;
					$menu_id = $menulevel1->menulevel1_id;
					$submenu_id = 0;
					$subsubmenu_id = 0;
				}else if( $menulevel2 ){
					$level = 2;
					$menu_id = 0;
					$submenu_id = $menulevel2->menulevel2_id;
					$subsubmenu_id = 0;
				}else if( $menulevel3 ){
					$level = 3;
					$menu_id = 0;
					$submenu_id = 0;
					$subsubmenu_id = $menulevel3->menulevel3_id;
				}else{
					$level = 0;
					$menu_id = 0;
					$submenu_id = 0;
					$subsubmenu_id = 0;
				}
			}else{
				$level = 0;
				$menu_id = 0;
				$submenu_id = 0;
				$subsubmenu_id = 0;
			}
		}
		
		$category_items = $mysqli->get_category_items( $level, $menu_id, $submenu_id, $subsubmenu_id );
		
		$categories = array(); 
		for( $i=0; $i<count( $category_items ); $i++ ){
			$categories[] = array( $category_items[$i]->menu_id, wp_easycart_language( )->convert_text( $category_items[$i]->menu_name ), $category_items[$i]->product_count, $this->ec_get_permalink( $category_items[$i] , $level, $store_page, $permalink_divider ) );
		}
		
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_category_widget.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" . get_option( 'ec_option_base_layout' ) . "/ec_category_widget.php");
		else
			include( EC_PLUGIN_DIRECTORY . "/design/layout/" . get_option( 'ec_option_latest_layout' ) . "/ec_category_widget.php");
		
		echo wp_easycart_escape_html( $after_widget );
	}
	
	private function ec_get_permalink( $category_item, $level, $store_page, $permalink_divider ){
		
		if( !get_option( 'ec_option_use_old_linking_style' ) && $category_item->post_id != "0" ){
			return $category_item->guid;
		}else{
			if( $level == 0 )
				return $store_page . $permalink_divider . "menuid=" . $category_item->menu_id . "&menuname=" . $category_item->menu_name;
			else if( $level == 1 )
				return $store_page . $permalink_divider . "submenuid=" . $category_item->menu_id . "&submenuname=" . $category_item->menu_name;
			else if( $level == 2 )
				return $store_page . $permalink_divider . "subsubmenuid=" . $category_item->menu_id . "&subsubmenuname=" . $category_item->menu_name;
		}
		
	}
 
}
?>