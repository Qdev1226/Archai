<?php

class ec_menu{
	
	public $menu;													// Menu Array
	private $store_page;											// VARCHAR
	private $permalinkdivider;										// CHAR
	
	function __construct( ){
		
		$db = new ec_db( );
		$menu_items = $db->get_menu_items( );
		
		$last_menu1 = 0;
		$last_menu2 = 0;
		
		$i=-1;
		$j=-1;
		
		$this->menu = array( ); 
		foreach( $menu_items as $menu_item ){
			
			if( $last_menu1 != $menu_item->menulevel1_id ){
				$i++;
				$last_menu1 = $menu_item->menulevel1_id;
				$last_menu2 = 0;
				$j=-1;
				$this->menu[] = (object) array( 
					"submenu" 			=> array( ), 
					"menu_id" 			=> $menu_item->menulevel1_id, 
					"menulevel1_id" 	=> $menu_item->menulevel1_id,
					"name"				=> $menu_item->menu1_name, 
					"post_id"			=> $menu_item->menulevel1_post_id,
					"guid"				=> $menu_item->menulevel1_guid,
					"order"				=> $menu_item->menulevel1_order,
					"clicks"			=> $menu_item->menulevel1_clicks,
					"seo_keywords"		=> $menu_item->menulevel1_seo_keywords,
					"seo_description"	=> $menu_item->menulevel1_seo_description,
					"banner_image" 		=> $menu_item->menulevel1_banner_image );
			}
			
			if( $menu_item->menulevel2_id && $last_menu2 != $menu_item->menulevel2_id ){
				$j++;
				$last_menu2 = $menu_item->menulevel2_id;
				$this->menu[$i]->submenu[] = (object) array(
					"subsubmenu"		=> array( ), 
					"menu_id"			=> $menu_item->menulevel2_id,
					"menulevel1_id"		=> $menu_item->menulevel1_id, 
					"menulevel2_id"		=> $menu_item->menulevel2_id, 
					"name"				=> $menu_item->menu2_name, 
					"post_id"			=> $menu_item->menulevel2_post_id,
					"guid"				=> $menu_item->menulevel2_guid,
					"order"				=> $menu_item->menulevel2_order,
					"clicks"			=> $menu_item->menulevel2_clicks,
					"seo_keywords"		=> $menu_item->menulevel2_seo_keywords,
					"seo_description"	=> $menu_item->menulevel2_seo_description,
					"banner_image" 		=> $menu_item->menulevel2_banner_image );
			}
			
			if( $menu_item->menulevel3_id ){
				$this->menu[$i]->submenu[$j]->subsubmenu[] = (object) array(
					"menu_id"			=> $menu_item->menulevel3_id,
					"menulevel2_id"		=> $menu_item->menulevel2_id,
					"menulevel3_id"		=> $menu_item->menulevel3_id,
					"name" 				=> $menu_item->menu3_name, 
					"post_id" 			=> $menu_item->menulevel3_post_id,
					"guid"				=> $menu_item->menulevel3_guid,
					"order"				=> $menu_item->menulevel3_order,
					"clicks"			=> $menu_item->menulevel3_clicks,
					"seo_keywords"		=> $menu_item->menulevel3_seo_keywords,
					"seo_description"	=> $menu_item->menulevel3_seo_description,
					"banner_image" 		=> $menu_item->menulevel3_banner_image );
			}
			
		}
	
	}
	
	public function get_menu_row( $menu_id, $menu_level ){
		
		for( $i=0; $i<count( $this->menu ); $i++ ){
			
			if( $menu_level == 1 ){
				
				if( $this->menu[$i]->menu_id == $menu_id ){
					
					return $this->menu[$i];
					
				}
				
			}else if( $menu_level > 1 ){
				
				for( $j=0; $j<count( $this->menu[$i]->submenu ); $j++ ){
					
					if( $menu_level == 2 ){
						
						if( $this->menu[$i]->submenu[$j]->menu_id == $menu_id ){
					
							return $this->menu[$i]->submenu[$j];
							
						}
						
					}else if( $menu_level > 2 ){
						
						for( $k=0; $k<count( $this->menu[$i]->submenu[$j]->subsubmenu ); $k++ ){
							
							if( $menu_level == 3 ){
						
								if( $this->menu[$i]->submenu[$j]->subsubmenu[$k]->menu_id == $menu_id ){
					
									return $this->menu[$i]->submenu[$j]->subsubmenu[$k];
								
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
		}
		
	}
	
	public function get_menu_row_from_post_id( $post_id, $menu_level ){
		
		for( $i=0; $i<count( $this->menu ); $i++ ){
			
			if( $menu_level == 1 ){
				
				if( $this->menu[$i]->post_id == $post_id ){
					
					return $this->menu[$i];
					
				}
				
			}else if( $menu_level > 1 ){
				
				for( $j=0; $j<count( $this->menu[$i]->submenu ); $j++ ){
					
					if( $menu_level == 2 ){
						
						if( $this->menu[$i]->submenu[$j]->post_id == $post_id ){
					
							return $this->menu[$i]->submenu[$j];
							
						}
						
					}else if( $menu_level > 2 ){
						
						for( $k=0; $k<count( $this->menu[$i]->submenu[$j]->subsubmenu ); $k++ ){
							
							if( $menu_level == 3 ){
						
								if( $this->menu[$i]->submenu[$j]->subsubmenu[$k]->post_id == $post_id ){
						
									return $this->menu[$i]->submenu[$j]->subsubmenu[$k];
									
								}
						
							}
						
						}
						
					}
					
				}
				
			}
			
		}
		
		return false;
		
	}
	
	public function level1_count( ){
		return count( $this->menu );
	}
	
	public function display_menulevel1_link( $level1 ){
		
		$permalink = $this->ec_get_permalink( 1, $level1, 0, 0, $this->menu[$level1]->post_id );
		echo esc_attr( $permalink );
		
	}
	
	public function display_menulevel1_name( $level1 ){
		
		echo wp_easycart_language( )->convert_text( $this->menu[$level1]->name );
	
	}
	
	public function get_menulevel1_name( $level1 ){
		
		return wp_easycart_language( )->convert_text( $this->menu[$level1]->name );
	
	}
	
	public function display_menulevel1_id( $level1 ){
		
		echo esc_attr( $this->menu[$level1]->menu_id );
	
	}
	
	public function get_menulevel1_id( $level1 ){
		
		return $this->menu[$level1]->menu_id;	
	
	}
	
	public function level2_count( $level1 ){
		return count( $this->menu[$level1]->submenu );
	}
	
	public function display_menulevel2_link( $level1, $level2 ){
		
		$permalink = $this->ec_get_permalink( 2, $level1, $level2, 0, $this->menu[$level1]->submenu[$level2]->post_id );
		echo esc_attr( $permalink );
		
	}
	
	public function display_menulevel2_name( $level1, $level2 ){
		
		echo wp_easycart_language( )->convert_text( $this->menu[$level1]->submenu[$level2]->name );	
	
	}
	
	public function get_menulevel2_name( $level1, $level2 ){
		
		return wp_easycart_language( )->convert_text( $this->menu[$level1]->submenu[$level2]->name );	
	
	}
	
	public function display_menulevel2_id( $level1, $level2 ){
		
		echo esc_attr( $this->menu[$level1]->submenu[$level2]->menu_id );	
	
	}
	
	public function get_menulevel2_id( $level1, $level2 ){
		
		return $this->menu[$level1]->submenu[$level2]->menu_id;	
	
	}
	
	public function level3_count( $level1, $level2 ){
		return count( $this->menu[$level1]->submenu[$level2]->subsubmenu );
	}
	
	public function display_menulevel3_link( $level1, $level2, $level3 ){
		
		$permalink = $this->ec_get_permalink( 3, $level1, $level2, $level3, $this->menu[$level1]->submenu[$level2]->subsubmenu[$level3]->post_id );
		echo esc_attr( $permalink );
	
	}
	
	public function display_menulevel3_name( $level1, $level2, $level3 ){
		
		echo wp_easycart_language( )->convert_text( $this->menu[$level1]->submenu[$level2]->subsubmenu[$level3]->name );	
	
	}
	
	public function get_menulevel3_name( $level1, $level2, $level3 ){
		
		return wp_easycart_language( )->convert_text( $this->menu[$level1]->submenu[$level2]->subsubmenu[$level3]->name );	
	
	}
	
	public function display_menulevel3_id( $level1, $level2, $level3 ){
		
		echo esc_attr( $this->menu[$level1]->submenu[$level2]->subsubmenu[$level3]->menu_id );	
	
	}
	
	public function get_menulevel3_id( $level1, $level2, $level3 ){
		
		return $this->menu[$level1]->submenu[$level2]->subsubmenu[$level3]->menu_id;	
	
	}
	
	private function ec_get_permalink( $menu_level, $level1, $level2, $level3, $postid ){
		
		$storepageid = get_option( 'ec_option_storepage' );
		
		if( function_exists( 'icl_object_id' ) ){
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		$this->store_page = get_permalink( $storepageid );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}
		
		if( substr_count( $this->store_page, '?' ) )					$this->permalinkdivider = "&";
		else															$this->permalinkdivider = "?";
		
		if( !get_option( 'ec_option_use_old_linking_style' ) && $postid != "0" ){
			if( $menu_level == 1 )
				return $this->menu[$level1]->guid;
			else if( $menu_level == 2 )
				return $this->menu[$level1]->submenu[$level2]->guid;
			else if( $menu_level == 3 )
				return $this->menu[$level1]->submenu[$level2]->subsubmenu[$level3]->guid;
		}else{
			if( $menu_level == 1 )
				return $this->store_page . $this->permalinkdivider . "menuid=" . $this->get_menulevel1_id( $level1 ) . "&menuname=" . $this->get_menulevel1_name( $level1 );
			else if( $menu_level == 2 )
				return $this->store_page . $this->permalinkdivider . "submenuid=" . $this->get_menulevel2_id( $level1, $level2 ) . "&submenuname=" . $this->get_menulevel2_name( $level1, $level2 );
			else if( $menu_level == 3 )
				return $this->store_page . $this->permalinkdivider . "subsubmenuid=" . $this->get_menulevel3_id( $level1, $level2, $level3 ) . "&subsubmenuname=" . $this->get_menulevel3_name( $level1, $level2, $level3 );
		}
		
	}
}

?>