<?php

class ec_paging{
	
	public $current_page;								// INT
	public $total_pages;								// INT
	
	private $total_products;							// INT
	private $num_per_page;								// INT
	private $start_item;								// INT						// INT
	
	private $store_page;
	private $permalink_divider;
	
	const MAX_PAGES_SHOWN = 5;							// INT
	
	function __construct( $num_per_page ){
		$this->total_products = 0;
		$this->num_per_page = $num_per_page;
		$this->current_page = $this->get_current_page( );
		$this->total_pages = 0;
		$this->start_item = ( ( $this->current_page - 1 ) * $this->num_per_page );
		
		$storepageid = get_option( 'ec_option_storepage' );
		$this->store_page = get_permalink( $storepageid );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}
		
		if( substr_count( $this->store_page, '?' ) )						$this->permalink_divider = "&";
		else																$this->permalink_divider = "?";
	}
	
	public function update_product_count( $total_products ){
		$this->total_products = $total_products;
		$this->total_pages = $this->get_total_pages();
		
	}
	
	private function get_current_page( ){
		if( isset( $_GET['pagenum'] ) )										return intval( $_GET['pagenum'] );
		else																return 1;
	}
	
	private function get_total_pages( ){
		if( $this->num_per_page > 0 )
			return ceil($this->total_products / $this->num_per_page);
		else
			return 0;
	}
	
	private function start_page(){
		if($this->current_page <= ceil( self::MAX_PAGES_SHOWN / 2 ) )		return 1;
		else if( ($this->current_page + ceil( self::MAX_PAGES_SHOWN / 2 ) ) > $this->total_pages )
																			return ($this->total_pages - self::MAX_PAGES_SHOWN + 1);
		else																return ($this->current_page - ceil(self::MAX_PAGES_SHOWN/2) + 1);
	}
	
	private function end_page(){
		if($this->total_pages < self::MAX_PAGES_SHOWN)						return $this->total_pages;
		else if($this->current_page <= ceil( self::MAX_PAGES_SHOWN / 2 ) )		return self::MAX_PAGES_SHOWN;
		else if( ($this->current_page + ceil( self::MAX_PAGES_SHOWN / 2 ) ) > $this->total_pages )
																			return $this->total_pages;
		else																return ($this->current_page + ceil(self::MAX_PAGES_SHOWN/2) - 1);
	}
	
	public function display_paging_links( $divider, $link_string ){
		
																	$ret_string = "";
														
		if( $this->current_page != 1 )								$ret_string .= "<a href=\"".$link_string."&amp;pagenum=".($this->current_page-1)."\" class=\"ec_prev_link\">< Prev</a>" . $divider;
		
		for($i = $this->start_page(); $i<=$this->end_page(); $i++){
			
			if($i == $this->current_page)							$ret_string .= $this->get_selected_link( $i );
			else													$ret_string .= $this->get_link( $i, $link_string );
			
			if($i != $this->total_pages)							$ret_string .= $divider;
			
		}
		
		if( $this->current_page != $this->total_pages)				$ret_string .= $divider . "<a href=\"".$link_string."&amp;pagenum=".($this->current_page+1)."\" class=\"ec_next_link\">Next ></a>";
		
																	return $ret_string;
		
	}
	
	private function get_selected_link( $i ){
		return "<span class=\"ec_selected_page\">" . ($i) . "</span>";
	}
	
	private function get_link( $i, $link_string ){
		return "<a href=\"" . $link_string . "&amp;pagenum=" .  $i . "\" class=\"ec_page_link\">" . $i . "</a>";
	}
	
	public function get_limit_query( ){
		if( get_option( 'ec_option_enable_product_paging' ) && is_numeric( $this->start_item ) && is_numeric( $this->num_per_page ) && $this->num_per_page > 0 )
			return sprintf( " LIMIT %d, %d", $this->start_item, $this->num_per_page );
		else
			return "";
	}
	
	public function get_prev_page_link( ){
		$return_link = $this->get_current_url( ) . "pagenum=" . ( $this->current_page - 1 );
		return $return_link;	
	}
	
	public function get_page_link( $i ){
		$return_link = $this->get_current_url( ) . "pagenum=" . ( $i );
		return $return_link;
	}
	
	public function get_next_page_link( ){
		$return_link = $this->get_current_url( ) . "pagenum=" . ( $this->current_page + 1 );
		return $return_link;
	}
	
	private function get_current_url( ){
		$page_url = 'http';
		if( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ){
			$page_url .= "s";
		}
		
		$page_url .= "://";
		
		if( (int) $_SERVER["SERVER_PORT"] != 80 && (int) $_SERVER["SERVER_PORT"] != 443 ) {
			$page_url .= sanitize_text_field( $_SERVER["HTTP_HOST"] ) . ":" . (int) $_SERVER["SERVER_PORT"] . sanitize_text_field( $_SERVER["REQUEST_URI"] );
		
		}else{
			$page_url .= sanitize_text_field( $_SERVER["HTTP_HOST"] ) . sanitize_text_field( $_SERVER["REQUEST_URI"] );
		
		}
		
		$page_url = preg_replace( '/([&]*[?]*pagenum\=[\d]*)/', '', $page_url );
		
		if( substr_count( $page_url, '?' ) )						
			$page_url .= "&";
		else																
			$page_url = "?";
		
		return $page_url;
	}
	
}

?>