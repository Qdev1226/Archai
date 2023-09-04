<?php 
class ec_languagewidget extends WP_Widget{
	
	function __construct( ){
		$widget_ops = array('classname' => 'ec_languagewidget', 'description' => 'Displays a Language Convertor for WP EasyCart' );
		parent::__construct('ec_languagewidget', 'WP EasyCart Language Selector', $widget_ops);
	}
	
	function form( $instance ){ 
		if( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}else {
			$title = '[EN]Shop Language[/EN][FR]Boutique Langue[/FR][NL]Shop Taal[/NL]';
		}
		
		if( isset( $instance[ 'available_languages' ] ) ) {
			$available_languages = $instance[ 'available_languages' ];
		}else {
			$available_languages = 'EN:English,FR:Français,NL:Nederlands';
		}
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\">" . esc_attr__( 'Title', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'title' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'title' ) ) . "\" type=\"text\" value=\"" . esc_attr( $title ) . "\" /></p>";
		
		echo "<p><label for=\"" . esc_attr( $this->get_field_name( 'available_languages' ) ) . "\">" . esc_attr__( 'Available Languages (format: EN:English,FR:French)', 'wp-easycart' ) . ":</label><input class=\"widefat\" id=\"" . esc_attr( $this->get_field_id( 'available_languages' ) ) . "\" name=\"" . esc_attr( $this->get_field_name( 'available_languages' ) ) . "\" type=\"text\" value=\"" . esc_attr( $available_languages ) . "\" /></p>";
		
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults);
	}
	
	function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['available_languages'] = ( !empty( $new_instance['available_languages'] ) ) ? strip_tags( $new_instance['available_languages'] ) : '';

		return $instance;
	}
	
	
	function widget($args, $instance){
	
		// Get the Widget Vars
		extract( $args );
		if( isset( $instance['title'] ) )
			$title = apply_filters( 'widget_title', $instance['title'] );
		else
			$title = "";
		if( isset( $instance['available_languages'] ) )
			$available_languages = apply_filters( 'widget_available_languages', $instance['available_languages'] );
		else
			$available_languages = "";
			
		// Process the language string
		$language_arrs = explode( ",", $available_languages );
		$languages = array( );
		
		for( $i=0; $i<count( $language_arrs ); $i++ ){
			$language = explode( ":", $language_arrs[$i] );
			$languages[] = $language;
		}
		
		// Get the selected language
		if( count( $languages ) > 0 ){
			$selected_language = wp_easycart_language( )->get_language_code( );
			
		}else{
			$languages[] = array( "EN", "English" );
			$selected_language = "EN";
		}
		
		// Get the correct title
		$title = wp_easycart_language( )->convert_text( $title );
		
		// Display the widget
		echo wp_easycart_escape_html( $before_widget );
		if ( ! empty( $title ) )
			echo wp_easycart_escape_html( $before_title . $title . $after_title );
		
		
		// WIDGET CODE GOES HERE
		echo "<form action=\"\" method=\"POST\" id=\"language\">";
		echo "<select name=\"ec_language_conversion\" onchange=\"document.getElementById('language').submit();\">";
		foreach( $languages as $language ){
			echo "<option value=\"" . esc_attr( $language[0] ) . "\"";
			if( $selected_language == $language[0] )
				echo " selected=\"selected\"";
			echo ">" . esc_attr( $language[1] ) . "</option>";
		}
		echo "</select>";
		echo "</form>";
		
		echo wp_easycart_escape_html( $after_widget );
	}
 
}
?>