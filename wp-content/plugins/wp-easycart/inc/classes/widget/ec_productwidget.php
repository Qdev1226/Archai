<?php 
class ec_productwidget extends WP_Widget{
	
	function __construct( ){
		$widget_ops = array('classname' => 'ec_productwidget', 'description' => 'Displays a single product from WP EasyCart' );
		parent::__construct('ec_productwidget', 'WP EasyCart Single Product Display', $widget_ops);
	}
	
	function form($instance){
		if( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = __( 'Hot Item', 'wp-easycart' );
		}
		
		if( isset( $instance[ 'model_number' ] ) ) {
			$model_number = $instance[ 'model_number' ];
		}else {
			$model_number = '';
		}
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\">" . esc_attr__( 'Title', 'wp-easycart' ) . ":</label>";
        echo "<input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'title' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\" type=\"text\" value=\"" . esc_attr( $title ) . "\" /></p>";
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'model_number' ) ) . "\">" . esc_attr__( 'Product (enter SKU)', 'wp-easycart' ) . ":</label>";
        echo "<input type=\"text\" class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'model_number' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'model_number' ) ) . "\" value=\"" . esc_attr( $model_number ) . "\" />";
		
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults);
	}
	
	function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['model_number'] = ( !empty( $new_instance['model_number'] ) ) ? strip_tags( $new_instance['model_number'] ) : '';

		return $instance;
	}
	
	
	function widget($args, $instance){
	
		extract( $args );
		if( isset( $instance['title'] ) )
			$title = apply_filters( 'widget_title', $instance['title'] );
		else
			$title = "";
		if( isset( $instance['model_number'] ) )
			$model_number = apply_filters( 'widget_model_number', $instance['model_number'] );
		else
			$model_number = "";
			
		// Translate if Needed
		$title = wp_easycart_language( )->convert_text( $title );
	
		echo wp_easycart_escape_html( $before_widget );
		if ( ! empty( $title ) )
			echo wp_easycart_escape_html( $before_title . $title . $after_title );
			
		// WIDGET CODE GOES HERE
		$mysqli = new ec_db( );
		$products = $mysqli->get_product_list( " WHERE product.model_number = '" . $model_number . "'", "", "", "" );
		if( count( $products ) > 0 ){
			$product = new ec_product( $products[0], 0, 0, 1 );
			
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product_widget.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" . get_option( 'ec_option_base_layout' ) . "/ec_product_widget.php");
			else
				include( EC_PLUGIN_DIRECTORY . "/design/layout/" . get_option( 'ec_option_latest_layout' ) . "/ec_product_widget.php");
		}
		
		echo wp_easycart_escape_html( $after_widget );
	}
 
}
?>