<?php
class ec_admin_settings{
	
	private $wpdb;
	
	public function __construct( ){ 
		// Keep reference to wpdb
		global $wpdb;
		$this->wpdb =& $wpdb;
	}
	
	// Public Functions
	public function process_form_action( $action ){
		
		if( $action == "initial_setup" ){
			$this->process_initial_setup( );
		}
		
	}
	
	public function has_admin_plugin( ){
		
		$has_admin = false;
		$plugins = get_plugins( );
		foreach( $plugins as $plugin ){
			if( $plugin['Name'] == "WP EasyCart Administration" )
				$has_admin = true;
		}
		return $has_admin;
	
	}
	
	public function is_admin_plugin_activated( ){
		
		return is_plugin_active( "wp-easycart-admin/wpeasycart-admin.php" );
	
	}
	
	// Actions Functions
	private function process_initial_setup( ){
		
		// Process Page Setups
		$ec_option_storepage = $this->setup_page( (int) $_POST['ec_option_storepage'], __( "Store", 'wp-easycart' ), "[ec_store]", "[ec_store" );
		$ec_option_cartpage = $this->setup_page( (int) $_POST['ec_option_cartpage'], __( "Cart", 'wp-easycart' ), "[ec_cart]", "[ec_cart" );
		$ec_option_accountpage = $this->setup_page( (int) $_POST['ec_option_accountpage'], __( "Account", 'wp-easycart' ), "[ec_account]", "[ec_account" );
		
		// Save Ids
		update_option( 'ec_option_storepage', $ec_option_storepage );
		update_option( 'ec_option_cartpage', $ec_option_cartpage );
		update_option( 'ec_option_accountpage', $ec_option_accountpage );
		
		// Process Demo Data Install
		if( isset( $_POST['ec_install_demo_data'] ) ){
			$this->install_demo_data( );
		}
		
	}
	
	// Initial Setup Helper Functions
	private function setup_page( $posted_id, $page_title, $shortcode, $shortcode_test ){
		
		if( $posted_id == "" ){ // Setup new page and save id
			$new_id = $this->create_new_page( $page_title, $shortcode );
		
		}else{ // Add shortcode if necessary
			$new_id = $posted_id;
			if( !$this->has_shortcode( $new_id, $shortcode_test ) )
				$this->add_shortcode( $new_id, $shortcode );
			
		}
		
		return $new_id;
		
	}
	
	// General Helper Functions
	private function create_new_page( $title, $content ){
		
		$post = array( 'post_content' 	=> $content,
					   'post_title' 	=> $title,
					   'post_type'		=> "page",
					   'post_status'	=> "publish"
				 );
		$post_id = wp_insert_post( $post );
		return $post_id;
		
	}
	
	public function has_shortcode( $post_id, $content ){
		
		$page = get_page( $post_id );
		if( strstr( $page->post_content, $content ) )
			return true;
		
		else
			return false;
	
	}
	
	private function add_shortcode( $post_id, $content ){
		
		$page = get_page( $post_id );
		$page->post_content = $content . $page->post_content;
		wp_update_post( $page );
		
	}
	
}
?>