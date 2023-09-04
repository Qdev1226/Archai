<?php

class ec_categories{
	
	public $categories;
	public $all_categories;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		global $wpdb;
		$this->categories = wp_cache_get( 'wpeasycart-all-categories', 'wpeasycart-categories' );
		if( !$this->categories ){
			$this->categories = array( );
			$this->all_categories = $wpdb->get_results( "SELECT ec_category.*, " . $wpdb->prefix . "posts.guid
				FROM ec_category
				LEFT JOIN " . $wpdb->prefix . "posts ON " . $wpdb->prefix . "posts.ID = ec_category.post_id
				WHERE ec_category.is_active = 1
				ORDER BY 
				ec_category.parent_id ASC,
				ec_category.priority DESC,
				ec_category.category_name ASC" );
			
			for( $i=0; $i<count($this->all_categories); $i++ ){
				if( $this->all_categories[$i]->parent_id == 0 && $this->all_categories[$i]->category_id != 0 ){
					$this->all_categories[$i] = $this->add_children( $this->all_categories[$i] );
					$this->categories[] = $this->all_categories[$i];
				}
			}
			wp_cache_set( 'wpeasycart-all-categories', $this->categories, 'wpeasycart-categories' );
		}
	}
	
	public function get_categories( $parent_id ){
		if( $parent_id == 0 ){
			$featured_categories = array( );
			for( $i=0; $i<count( $this->categories ); $i++ ){
				if( $this->categories[$i]->featured_category )
					$featured_categories[] = $this->categories[$i];
			}
			return $featured_categories;
		}else if( $parent_id == -1 ){
			$top_level_categories = array( );
			for( $i=0; $i<count( $this->categories ); $i++ ){
				if( !$this->categories[$i]->parent_id )
					$top_level_categories[] = $this->categories[$i];
			}
			return $top_level_categories;
		}else{
			return $this->get_recursive_categories( $this->categories, $parent_id );
		}
	}
	
	private function get_recursive_categories( $categories, $parent_id ){
		for( $i=0; $i<count( $categories ); $i++ ){
			if( $categories[$i]->category_id == $parent_id ){
				return $categories[$i]->children;
			}else if( isset( $categories[$i]->children ) ){
				$subtest = $this->get_recursive_categories( $categories[$i]->children, $categories[$i]->parent_id );
				if( $subtest ){
					return $subtest;
				}
			}
		}
		return false;
	}
	
	private function add_children( $category ){
		
		global $wpdb;
		$children = $this->get_children( $category->category_id );
		
		if( count( $children ) > 0 ){
			$category->children = $children;
			for( $i=0; $i<count( $category->children ); $i++ ){
				$category->children[$i] = $this->add_children( $category->children[$i] );
			}
		}
		
		return $category;
		
	}
	
	private function get_children( $parent_id ){
		
		$children = array( );
		for( $i=0; $i<count( $this->all_categories ); $i++ ){
			if( $this->all_categories[$i]->parent_id == $parent_id ){
				$children[] = $this->all_categories[$i];
			}
		}
		return $children;
		
	}
	
	public function print_widget_list( $categories, $depth = 0, $selected_id = 0, $store_page = '', $permalink_divider = '' ){
		
		for( $i=0; $i<count( $categories ); $i++ ){
			
			$this->print_widget_item( $categories[$i], $depth, $selected_id, $store_page, $permalink_divider );
			if( isset( $categories[$i]->children ) ){
				$this->print_widget_list( $categories[$i]->children, $depth + 1, $selected_id, $store_page, $permalink_divider );
			}
			
		}
		
	}
    
    public function print_widget_item( $category, $depth, $selected_id, $store_page, $permalink_divider ){
		
    	$padding = 20;
		echo '<div style="padding-left:' . esc_attr( ($padding * $depth) ) . 'px;"><a href="';
		if( !get_option( 'ec_option_use_old_linking_style' ) ){ 
			echo esc_attr( $category->guid );
		}else{
			echo esc_attr( $store_page . $permalink_divider ) . 'group_id=' . esc_attr( $category->category_id );
		}
		echo '" class="menu_link">';
		if( $selected_id == $category->category_id ){ 
			echo "<b>";
		}
		echo wp_easycart_language( )->convert_text( $category->category_name );
		if( $selected_id == $category->category_id ){ 
			echo "</b>"; 
		}
		echo '</a></div>';
	
    }
	
	public function get_category( $category_id ){
		
		for( $i=0; $i<count( $this->all_categories ); $i++ ){
			
			if( $this->all_categories[$i]->category_id == $category_id )
				return $this->all_categories[$i];
			
		}
		
	}
	
	public function get_category_id_from_post_id( $post_id ){
		
		for( $i=0; $i<count( $this->categories ); $i++ ){
			
			if( $this->categories[$i]->post_id == $post_id )
				return $this->categories[$i];
			
		}
		
	}
		
		
}

?>