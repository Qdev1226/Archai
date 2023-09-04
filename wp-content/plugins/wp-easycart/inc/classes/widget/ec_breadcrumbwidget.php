<?php 
class ec_breadcrumbwidget extends WP_Widget{
	
	function __construct( ){
		$widget_ops = array('classname' => 'ec_breadcrumbwidget', 'description' => 'Custom Breadcrumbs for Your WP EasyCart' );
		parent::__construct('ec_breadcrumbwidget', 'WP EasyCart Breadcrumbs', $widget_ops);
	}
	
	function form($instance){
		if( isset( $instance[ 'divider' ] ) ) {
			$divider = $instance[ 'divider' ];
		}else {
			$divider = '&quot;';
		}
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'divider' ) ) . "\">" . esc_attr__( 'divider', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'divider' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'divider' ) ) . "\" type=\"text\" value=\"" . esc_attr( $divider ) . "\" /></p>";
		
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults);
	}
	
	function update($new_instance, $old_instance){
		$instance = array();
		$instance['divider'] = ( !empty( $new_instance['divider'] ) ) ? strip_tags( $new_instance['divider'] ) : '';

		return $instance;
	}
	
	
	function widget($args, $instance){
	
		extract( $args );
		$divider = empty($instance['divider']) ? ' ' : apply_filters('widget_divider', $instance['divider']);
	
		echo wp_easycart_escape_html( $before_widget );
		
		// WIDGET CODE GOES HERE
		if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_breadcrumb_widget.php' ) )	
			include( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/" . get_option( 'ec_option_base_layout' ) . "/ec_breadcrumb_widget.php");
		else
			include( EC_PLUGIN_DIRECTORY . "/design/layout/" . get_option( 'ec_option_latest_layout' ) . "/ec_breadcrumb_widget.php");
		
		echo wp_easycart_escape_html( $after_widget );
	}
 
}
?>