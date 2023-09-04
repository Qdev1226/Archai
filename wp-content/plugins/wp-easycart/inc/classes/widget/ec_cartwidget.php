<?php 
class ec_cartwidget extends WP_Widget{
	
	function __construct( ){
		$widget_ops = array('classname' => 'ec_cartwidget', 'description' => 'Displays a Minicart For Your WP EasyCart' );
		parent::__construct('ec_cartwidget', 'WP EasyCart Minicart', $widget_ops);
	}
	
	function form( $instance ){ 
		if( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = __( 'Shopping Cart', 'wp-easycart' );
		}
		
		if( isset( $instance[ 'link_text' ] ) ) {
			$link_text = $instance[ 'link_text' ];
		}else {
			$link_text = __( 'Shopping Cart', 'wp-easycart' );
		}
		
		if( isset( $instance[ 'use_popup_minicart' ] ) ) {
			$use_popup_minicart = $instance[ 'use_popup_minicart' ];
		}else {
			$use_popup_minicart = '1';
		}
		
		if( isset( $instance[ 'open_on_click' ] ) ) {
			$open_on_click = $instance[ 'open_on_click' ];
		}else {
			$open_on_click = '0';
		}
		
		if( isset( $instance[ 'open_on_mouseover' ] ) ) {
			$open_on_mouseover = $instance[ 'open_on_mouseover' ];
		}else {
			$open_on_mouseover = '1';
		}
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\">" . esc_attr__( 'Title', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'title' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\" type=\"text\" value=\"" . esc_attr( $title ) . "\" /></p>";
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'link_text' ) ) . "\">" . esc_attr__( 'Link Text', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'link_text' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'link_text' ) ) . "\" type=\"text\" value=\"" . esc_attr( $link_text ) . "\" /></p>";
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'use_popup_minicart' ) ) . "\">" . esc_attr__( 'Minicart Popup', 'wp-easycart' ) . ":</label><select class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'use_popup_minicart' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'use_popup_minicart' ) ) . "\"><option value=\"1\"";
		if( $use_popup_minicart == 1 )
		echo " selected=\"selected\"";
		echo ">On</option><option value=\"0\"";
		if( $use_popup_minicart == 0 )
		echo "selected=\"selected\"";
		echo ">Off</option></select></p>";
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'open_on_click' ) ) . "\">" . esc_attr__( 'Open on Click', 'wp-easycart' ) . ":</label><select class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'open_on_click' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'open_on_click' ) ) . "\"><option value=\"1\"";
		if( $open_on_click == 1 )
		echo " selected=\"selected\"";
		echo ">Yes</option><option value=\"0\"";
		if( $open_on_click == 0 )
		echo "selected=\"selected\"";
		echo ">No</option></select></p>";
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'open_on_mouseover' ) ) . "\">" . esc_attr__( 'Open on Hover', 'wp-easycart' ) . ":</label><select class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'open_on_mouseover' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'open_on_mouseover' ) ) . "\"><option value=\"1\"";
		if( $open_on_mouseover == 1 )
		echo " selected=\"selected\"";
		echo ">Yes</option><option value=\"0\"";
		if( $open_on_mouseover == 0 )
		echo "selected=\"selected\"";
		echo ">No</option></select></p>";
		
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults);
	}
	
	function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['link_text'] = ( !empty( $new_instance['link_text'] ) ) ? strip_tags( $new_instance['link_text'] ) : '';
		$instance['use_popup_minicart'] = ( !empty( $new_instance['use_popup_minicart'] ) ) ? strip_tags( $new_instance['use_popup_minicart'] ) : '';
		$instance['open_on_click'] = ( !empty( $new_instance['open_on_click'] ) ) ? strip_tags( $new_instance['open_on_click'] ) : '';
		$instance['open_on_mouseover'] = ( !empty( $new_instance['open_on_mouseover'] ) ) ? strip_tags( $new_instance['open_on_mouseover'] ) : '';

		return $instance;
	}
	
	
	function widget($args, $instance){
	
		extract( $args );
		if( isset( $instance['title'] ) )
			$title = apply_filters( 'widget_title', $instance['title'] );
		else
			$title = "";
		if( isset( $instance['link_text'] ) )
			$link_text = apply_filters( 'widget_link_text', $instance['link_text'] );
		else
			$link_text = "";
		if( isset( $instance['use_popup_minicart'] ) )
			$use_popup_minicart = apply_filters( 'widget_use_popup_minicart', $instance['use_popup_minicart'] );
		else
			$use_popup_minicart = "";
		if( isset( $instance['open_on_click'] ) )
			$open_on_click = apply_filters( 'widget_open_on_click', $instance['open_on_click'] );
		else
			$open_on_click = "";
		if( isset( $instance['open_on_mouseover'] ) )
			$open_on_mouseover = apply_filters( 'widget_open_on_mouseover', $instance['open_on_mouseover'] );
		else
			$open_on_mouseover = "";
		
		// Translate if Needed
		$title = wp_easycart_language( )->convert_text( $title );
		$link_text = wp_easycart_language( )->convert_text( $link_text );
	
		echo wp_easycart_escape_html( $before_widget );
		if ( ! empty( $title ) )
			echo wp_easycart_escape_html( $before_title . $title . $after_title );
		
		// WIDGET CODE GOES HERE
		$mysqli = new ec_db();
		$filter = new ec_filter(0);
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$subtotal = $cart->subtotal;
		
		$cartpageid = get_option('ec_option_cartpage');
		if( function_exists( 'icl_object_id' ) ){
			$cartpageid = icl_object_id( $cartpageid, 'page', true, ICL_LANGUAGE_CODE );
		}
		$cart_page = get_permalink( $cartpageid );
		
		if( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ){
			$https_class = new WordPressHTTPS( );
			$cart_page = $https_class->makeUrlHttps( $cart_page );
		}
		
		if( substr_count( $cart_page, '?' ) )						$permalink_divider = "&";
		else														$permalink_divider = "?";
		
		$cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$page_id = get_the_ID();
		
        if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_widget.php' ) )	
            include( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" . get_option( 'ec_option_base_layout' ) . "/ec_cart_widget.php");
        else
            include( EC_PLUGIN_DIRECTORY . "/design/layout/" . get_option( 'ec_option_latest_layout' ) . "/ec_cart_widget.php");
		
		echo wp_easycart_escape_html( $after_widget );
	}
 
}
?>