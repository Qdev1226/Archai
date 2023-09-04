<?php

class ec_subscription_list{
	
	private $mysqli;
	private $user;
	
	public $subscription_list;
	
	private $account_page;						// VARCHAR
	private $permalink_divider;					// CHAR
	
	function __construct( $user ){
		$this->mysqli = new ec_db( );
		$this->user = $user;
		
		$this->set_subscription_list( );
		
		$accountpageid = get_option('ec_option_accountpage');
		
		if( function_exists( 'icl_object_id' ) ){
			$accountpageid = icl_object_id( $accountpageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		
		$this->account_page = get_permalink( $accountpageid );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
		}
		
		if( substr_count( $this->account_page, '?' ) )				$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";
	}
	
	private function set_subscription_list( ){
		// Initial VARS
		$this->subscription_list = array( );
		$data = $this->mysqli->get_subscriptions( $this->user->user_id );
		
		foreach( $data as $subscription_row ){
			$this->subscription_list[] = new ec_subscription( $subscription_row );
		}
	}
	
	///////////////////////////////////////////////////
	// Display Functions
	///////////////////////////////////////////////////
	
	public function display_subscription_list( ){
		
		$i=0;
		if( count( $this->subscription_list ) > 0 ){
			
			foreach( $this->subscription_list as $subscription ){
				
				if( !$subscription->is_canceled( ) ){
				
					if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_subscription_line.php' ) )	
						include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_account_subscription_line.php' );
					else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_account_subscription_line.php' ) )
						include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_account_subscription_line.php' );
				
					$i++;
					
				}
			}
			
		}else{
			
			echo "<div class=\"ec_subscription_none_found\">" . wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_none_found' ) . "</div>";
			
		}
		
	}
	
	public function display_canceled_subscription_list( ){
		
		$i=0;
		if( count( $this->subscription_list ) > 0 ){
			
			foreach( $this->subscription_list as $subscription ){
				
				if( $subscription->is_canceled( ) ){
				
					if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_subscription_line.php' ) )	
						include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_account_subscription_line.php' );
					else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_account_subscription_line.php' ) )
						include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_account_subscription_line.php' );
				
					$i++;
					
				}
			}
			
		}
        
        if( $i <= 0 ){
			
			echo "<div class=\"ec_subscription_none_found\">" . wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_none_found' ) . "</div>";
			
		}
		
	}
	
}

?>