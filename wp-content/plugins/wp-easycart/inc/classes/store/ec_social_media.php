<?php

class ec_social_media{
	
	public $use_facebook;									// BOOL
	public $use_twitter;									// BOOL
	public $use_delicious;									// BOOL
	public $use_myspace;									// BOOL
	public $use_linkedin;									// BOOL
	public $use_email;										// BOOL
	public $use_digg;										// BOOL
	public $use_googleplus;									// BOOL
	public $use_pinterest;									// BOOL
	
	private $model_number;									// VARCHAR 255
	private $title;											// TEXT
	private $description;									// TEXT
	private $image;											// ec_prodimages structure
	
	private $store_page;
	private $permalink_divider;
	
	function __construct( $model_number, $title, $description, $image ){
		
		$this->use_facebook = get_option( 'ec_option_use_facebook_icon' );
		$this->use_twitter = get_option( 'ec_option_use_twitter_icon' );
		$this->use_delicious = get_option( 'ec_option_use_delicious_icon' );
		$this->use_myspace = get_option( 'ec_option_use_myspace_icon' );
		$this->use_linkedin = get_option( 'ec_option_use_linkedin_icon' );
		$this->use_email = get_option( 'ec_option_use_email_icon' );
		$this->use_digg = get_option( 'ec_option_use_digg_icon' );
		$this->use_googleplus = get_option( 'ec_option_use_googleplus_icon' );
		$this->use_pinterest = get_option( 'ec_option_use_pinterest_icon' );
		
		$this->model_number = $model_number;
		$this->title = $title;
		$this->description = $description;
		$this->image = $image;
		
		$store_page_id = get_option('ec_option_storepage');
		
		if( function_exists( 'icl_object_id' ) ){
			$store_page_id = icl_object_id( $store_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		$this->store_page = get_permalink( $store_page_id );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}
		
		if( substr_count( $this->store_page, '?' ) )						$this->permalink_divider = "&";
		else																$this->permalink_divider = "?";
		
	}
		
	public function display_facebook_icon( ){
		if( $this->use_facebook ){
			echo "<a href=\"";
			echo "https://www.facebook.com/sharer/sharer.php?p[url]=";
			echo esc_attr( $this->get_product_url( ) );
			echo "&p[images][o]=" . esc_attr( $this->get_image_url( ) );
			echo "&p[title]=" . esc_attr( $this->get_product_clean_title( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "facebook_20x20.png" ) );
			echo "\" alt=\"Facebook\" title=\"Facebook\" />";
			echo "</a>";
		}
	}
	
	public function get_facebook_link( ){
		return "https://www.facebook.com/sharer/sharer.php?p[url]=" . $this->get_product_url( ) . "&p[images][o]=" . $this->get_image_url( ) . "&p[title]=" . $this->get_product_clean_title( );
	}

	public function display_twitter_icon( ){
		if( $this->use_twitter ){
    		echo "<a href=\"";
			echo "https://twitter.com/intent/tweet?original_referer=";
			echo esc_attr( $this->get_product_url( ) );
			echo "&source=tweetbutton&text=";
			echo esc_attr( $this->get_product_clean_title( ) );
			echo "&url=";
			echo esc_attr( $this->get_product_url( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "twitter_20x20.png" ) );
			echo "\" alt=\"Twitter\" title=\"Twitter\" />";
			echo "</a>";
		}
	}
	
	public function get_twitter_link( ){
		return "https://twitter.com/intent/tweet?original_referer=" . $this->get_product_url( ) . "&source=tweetbutton&text=" . $this->get_product_clean_title( ) . "&url=" . $this->get_product_url( );
	}
	
	public function display_delicious_icon( ){
		if( $this->use_delicious ){
    		echo "<a href=\"";
			echo "https://delicious.com/login?lo_action=save&amp;next=http%3A%2F%2Fdelicious.com%2Fpost%3Furl%3D";
			echo esc_attr( $this->get_product_url( ) );
			echo "%3Flog%3Dout%26url%3D";
			echo esc_attr( $this->get_product_url( ) );
			echo "&url=";
			echo esc_attr( $this->get_product_url( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "delicious_20x20.png" ) );
			echo "\" alt=\"Delicious\" title=\"Delicious\" />";
			echo "</a>";
		}
	}
	
	public function get_delicious_link( ){
		return "https://delicious.com/login?lo_action=save&amp;next=http%3A%2F%2Fdelicious.com%2Fpost%3Furl%3D" . $this->get_product_url( ) . "%3Flog%3Dout%26url%3D" . $this->get_product_url( ) . "&url=" . $this->get_product_url( );
	}
	
	public function display_myspace_icon( ){
		if( $this->use_myspace ){
    		echo "<a href=\"";
			echo esc_attr( "https://www.myspace.com/index.cfm?fuseaction=postto&t=".$this->get_product_subject( )."&u=".urlencode( $this->get_product_url( ) ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "myspace_20x20.png" ) );
			echo "\" alt=\"MySpace\" title=\"MySpace\" />";
			echo "</a>";
		}
	}
	
	public function get_myspace_link( ){
		return "https://www.myspace.com/index.cfm?fuseaction=postto&t=" . $this->get_product_subject( ) . "&u=" . urlencode( $this->get_product_url( ) );
	}
	
	public function display_linkedin_icon( ){
		if( $this->use_linkedin ){
    		echo "<a href=\"";
			echo "https://www.linkedin.com/shareArticle?mini=true&amp;url=";
			echo esc_attr( $this->get_product_url( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "linkedin_20x20.png" ) );
			echo "\" alt=\"Linked In\" title=\"Linked In\" />";
			echo "</a>";
		}
	}
	
	public function get_linkedin_link( ){
		return "https://www.linkedin.com/shareArticle?mini=true&amp;url=" . $this->get_product_url( );
	}
	
	public function display_email_icon( ){
		if( $this->use_email ){
    		echo "<a href=\"";
			echo "mailto:?subject=";
			echo esc_attr( $this->get_product_subject( ) );
			echo "&body=";
			echo "Link for Product: " . esc_attr( $this->get_product_url( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "email_20x20.png" ) );
			echo "\" alt=\"Email\" title=\"Email\" />";
			echo "</a>";
		}
	}
	
	public function get_email_link( ){
		return "mailto:?subject=" . $this->get_product_subject( ) . "&body=" . "Link for Product: " . $this->get_product_url( );
	}
	
	public function display_digg_icon( ){
		if( $this->use_digg ){
    		echo "<a href=\"";
			echo "https://digg.com/submit?phase=2&amp;url=";
			echo esc_attr( $this->get_product_url( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "digg_20x20.png" ) );
			echo "\" alt=\"Digg\" title=\"Digg\" />";
			echo "</a>";
		}
	}
	
	public function get_digg_link( ){
		return "https://digg.com/submit?phase=2&amp;url=" . $this->get_product_url( );
	}
	
	public function display_googleplus_icon( ){
		if( $this->use_googleplus ){
    		echo "<a href=\"";
			echo "https://plus.google.com/share?url=";
			echo esc_attr( $this->get_product_url( ) );
			echo "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "google_plus_20x20.png" ) );
			echo "\" alt=\"Google Plus\" title=\"Google Plus\" />";
			echo "</a>";
		}
	}
	
	public function get_googleplus_link( ){
		return "https://plus.google.com/share?url=" . $this->get_product_url( );
	}
	
	public function display_pinterest_icon( ){
		if( $this->use_pinterest ){
    		echo "<a href=\"";
			echo "https://pinterest.com/pin/create/button/?media=";
			echo esc_attr( urlencode( $this->get_image_url( ) ) );
			echo "&description=" . urlencode($this->title);
			echo "&url=" . esc_attr( urlencode($this->get_product_url( ) ) ) . "\" target=\"_blank\">";
			echo "<img src=\"";
			echo esc_attr( $this->get_icon_image( "pinterest_20x20.png" ) );
			echo "\" alt=\"Pinterest\" title=\"Pinterest\" />";
			echo "</a>"; 
		}
	}
	
	public function get_pinterest_link( ){
		return "https://pinterest.com/pin/create/button/?media=" . urlencode( $this->get_image_url( ) ) . "&description=" . urlencode( $this->title ) . "&url=" . urlencode($this->get_product_url( ) );
	}
	
	private function get_image_url( ) {
		if( substr( $this->image, 0, 7 ) == 'http://' || substr( $this->image, 0, 8 ) == 'https://' ){
			return $this->image;
		}else if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics1/" . $this->image ) )
			return plugins_url( "wp-easycart-data/products/pics1/" . $this->image, EC_PLUGIN_DATA_DIRECTORY );
		else
			return plugins_url( "wp-easycart/products/pics1/" . $this->image, EC_PLUGIN_DIRECTORY );
	}
	
	private function get_product_url( ){
		global $wp_query;
		$post_obj = $wp_query->get_queried_object();
		if( isset( $post_obj ) && isset( $post_obj->ID ) ){
			$post_id = $post_obj->ID;
		}else{
			$post_id = 0;
		}
		return $this->ec_get_permalink( $post_id );
	}
	
	private function ec_get_permalink( $postid ){
		
		if( !get_option( 'ec_option_use_old_linking_style' ) && $postid != "0" ){
			return get_permalink( $postid );
		}else{
			return $this->store_page . $this->permalink_divider . "model_number=" . $this->model_number;
		}
		
	}
	
	public function get_icon_image( $image ){
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ). "/ec_product_details_page/" . $image ) )
			return plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ). "/ec_product_details_page/" . $image, EC_PLUGIN_DATA_DIRECTORY );
		else if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ). "/images/" . $image ) )
			return plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ). "/images/" . $image, EC_PLUGIN_DATA_DIRECTORY );
		else
			return plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ). "/images/" . $image, EC_PLUGIN_DIRECTORY );
	}
	
	private function get_product_clean_title( ){
		return urlencode( $this->title );
	}
	
	private function get_product_subject( ){
		return $this->title;
	}
	
	private function get_product_body( ){ 
		return "<p><h1>" . $this->title . "</h1></p><p><h4>" . $this->description . "</h4></p>";
	}
	
	private function get_product_image( ){
		return $this->image->get_single_image();
	}
	
}

?>