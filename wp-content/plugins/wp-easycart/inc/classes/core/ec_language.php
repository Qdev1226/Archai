<?php
if ( ! class_exists( 'wp_easycart_language' ) ) :

	final class wp_easycart_language {

		protected static $_instance = null;

		private static $selected_language;
		public static $language_code;

		public static $language_data;
		public static $languages;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cloning is forbidden.', 'wp-easycart' ), '5.8' );
		}

		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Unserializing instances of this class is forbidden.', 'wp-easycart' ), '5.8' );
		}

		public function get_selected_language() {
			return self::$selected_language;
		}

		public function get_language_code() {
			return self::$language_code;
		}

		public function update_selected_language( $selected_language = 'NONE' ) {
			if ( $selected_language == 'NONE' ) {
				self::$selected_language = strtolower( get_option( 'ec_option_language' ) );
				if ( isset( $_GET['lang'] ) ) {
					self::$language_code = strtoupper( htmlspecialchars( sanitize_text_field( $_GET['lang'] ), ENT_QUOTES ) );
					if ( isset( $GLOBALS['ec_cart_data'] ) && isset( $GLOBALS['ec_cart_data']->cart_data ) && isset( $GLOBALS['ec_cart_data']->cart_data->translate_to ) ) {
						$GLOBALS['ec_cart_data']->cart_data->translate_to = self::$language_code;
						$GLOBALS['ec_cart_data']->save_session_to_db( );
					}
				} else if ( isset( $_COOKIE['ec_translate_to'] ) ) {
					self::$language_code = preg_replace( '/[^A-Z\-\_]/', '', strtoupper( sanitize_text_field( $_COOKIE['ec_translate_to'] ) ) );
					if ( (string) self::$language_code != '' && isset( $GLOBALS['ec_cart_data'] ) && isset( $GLOBALS['ec_cart_data']->cart_data ) && isset( $GLOBALS['ec_cart_data']->cart_data->translate_to ) ){
						$GLOBALS['ec_cart_data']->cart_data->translate_to = self::$language_code;
						$GLOBALS['ec_cart_data']->save_session_to_db( );
					} else {
						self::$language_code = "NEEDTOSET";
					}
				} else if( ! isset( $GLOBALS['ec_cart_data'] ) || ! isset( $GLOBALS['ec_cart_data']->cart_data ) && ! isset( $GLOBALS['ec_cart_data']->cart_data->translate_to ) || '' == $GLOBALS['ec_cart_data']->cart_data->translate_to ) {
					self::$language_code = "NEEDTOSET";
				}
			} else {
				self::$selected_language = strtolower( $selected_language );
				self::$language_code = strtoupper( $selected_language );
			}

			self::$language_data = self::get_decoded_language_data();
			if ( !self::$language_data ) {
				self::$language_data = self::get_new_language_data();
				self::save_language_data();
			}
			self::$languages = self::get_languages();

			if ( self::$language_code == "NEEDTOSET" ) {
				if ( isset( self::$language_data ) && isset( self::$language_data->{self::$selected_language} ) && isset( self::$language_data->{self::$selected_language}->options ) && isset( self::$language_data->{self::$selected_language}->options->language_code->options->code ) && isset( self::$language_data->{self::$selected_language}->options->language_code->options->code->value ) ) {
					self::$language_code = strtoupper( self::$language_data->{self::$selected_language}->options->language_code->options->code->value );
				} else {
					self::$language_code = "NONE";
				}
			} else {
				self::set_selected_language();
			}
		}

		public function set_language( $code ) {
			self::$language_code = strtoupper( htmlspecialchars( $code, ENT_QUOTES ) );
			$GLOBALS['ec_cart_data']->cart_data->translate_to = self::$language_code;
			self::set_selected_language();
		}

		public function set_language_data( $language_data ) {
			self::$language_data = $language_data;
		}

		public function get_language_section( $file_name, $key_section ) {
			return self::$language_data->{$file_name}->options->{$key_section};
		}

		public function add_new_language( $file_name ) {
			self::$language_data->{$file_name} = self::get_language_file_decoded( $file_name . ".txt" );
			self::save_language_data();
			self::$languages = self::get_languages();
		}

		public function update_language_data() {
			if ( isset( $_POST['isupdate'] ) ) {
				$language_file = sanitize_text_field( $_POST['file_name'] );
				$language_section = sanitize_key( $_POST['key_section'] );
				foreach ( (array) $_POST['ec_language_field'] as $key => $value ) { // XSS OK. Forced array and each item sanitized.
					self::$language_data->{$language_file}->options->{$language_section}->options->{sanitize_key( $key )}->value = htmlspecialchars( wp_easycart_escape_html( stripslashes( $value ) ), ENT_NOQUOTES, "UTF-8" );
				}
			}
			$file_names = self::get_language_file_list();
			foreach ( $file_names as $file_name ) {
				if ( in_array( $file_name, self::$languages ) )
					self::update_language_entry( $file_name );
			}
			self::save_language_data();
			self::$languages = self::get_languages();
		}

		public function update_language_item( $language_file, $language_section, $key, $value ) {
			self::$language_data->{$language_file}->options->{$language_section}->options->{$key}->value = htmlspecialchars( wp_easycart_escape_html( stripslashes( $value ) ), ENT_NOQUOTES, "UTF-8" );
			self::save_language_data();
			self::$languages = self::get_languages();
		}

		public function remove_language( $file_name ) {
			$language_data = (object) array();
			foreach ( self::$language_data as $key => $data ) {
				if ( $key != $file_name ) {
					$language_data->{$key} = $data;
				}
			}
			self::$language_data = $language_data;
			self::save_language_data();
			self::$languages = self::get_languages();
		}

		public function get_languages_array() {
			return self::$languages;
		}

		public function get_language_data() {
			return self::$language_data;
		}

		private function set_selected_language() {
			if ( isset( self::$language_data ) ) {
				$languages = self::get_languages();
				for( $i=0; $i<count( $languages ); $i++ ) {
					$item_language_code = self::$language_data->{$languages[$i]}->options->language_code->options->code->value;
					if ( strtoupper( self::$language_code ) == strtoupper( $item_language_code ) ) {
						self::$selected_language = strtolower( $languages[$i] );
						break;
					}
				}
			}
		}

		private function get_languages() {
			$language_arrays = get_object_vars( self::$language_data );
			return array_keys( $language_arrays );
		}

		private function get_new_language_data() {
			$language_data = (object) array();
			$file_names = self::get_language_file_list();
			for( $i=0; $i<count( $file_names ); $i++ ) {
				if ( $file_names[$i] == "en-us" ) {
					$language_data->{$file_names[$i]} = self::get_language_file_decoded( $file_names[$i] . ".txt" );
				}
			}
			return $language_data;
		}

		public function get_language_file_list() {
			$file_names = array();
			$dir = EC_PLUGIN_DIRECTORY . "/inc/language/";
			$handle = opendir( $dir );
			while( false !== ( $file = readdir( $handle ) ) ) {
				$extension = pathinfo( $file, PATHINFO_EXTENSION );
				$name = pathinfo( $file, PATHINFO_FILENAME );
				if ( $extension == "txt" )
				$file_names[] = $name;
			}
			return $file_names;
		}

		private function get_language_file_decoded( $file_name ) {
			return json_decode( self::get_language_file_contents( $file_name ) );
		}

		private function get_language_file_contents( $file_name ) {
			if ( !file_exists( EC_PLUGIN_DIRECTORY . "/inc/language/" . $file_name ) ) {
				return '';
			}
			ob_start();
			include EC_PLUGIN_DIRECTORY . "/inc/language/" . $file_name;
			$contents = ob_get_clean();
			return $contents;
		}

		public function get_text( $lang_section, $lang_var ) {
			if ( isset( self::$language_data->{self::$selected_language} ) && 
				isset( self::$language_data->{self::$selected_language}->options->{$lang_section} ) && 
				isset( self::$language_data->{self::$selected_language}->options->{$lang_section}->options->{$lang_var} ) )
				return str_replace( "[terms]", "<a href=\"" . esc_url_raw( stripslashes( get_option( 'ec_option_terms_link' ) ) ) . "\" target=\"_blank\">", str_replace( "[/terms]", "</a>", str_replace( "[privacy]", "<a href=\"" . esc_url_raw( stripslashes( get_option( 'ec_option_privacy_link' ) ) ) . "\" target=\"_blank\">", str_replace( "[/privacy]", "</a>", wp_easycart_escape_html( self::$language_data->{self::$selected_language}->options->{$lang_section}->options->{$lang_var}->value ) ) ) ) );
		}

		public function convert_text( $text ) {
			if ( self::$language_code != "NONE" && $text != null && preg_match_all( '/[\[][a-zA-Z][a-zA-Z][\]]|[\[][\/][a-zA-Z][a-zA-Z][\]]/', $text, $matches ) > 0 ) {
				$text_arr = preg_split( '/[\[][\/]|[\[]|[\]]/', $text );
				$texts = array();
				for( $i=1; $i<count( $text_arr ); $i++ ) {
					$key = strtoupper( $text_arr[$i] );
					$val = $text_arr[($i+1)];
					$texts[$key] = $val;
					$i = $i + 3;
				}
				if ( isset( $texts[ strtoupper( self::$language_code ) ] ) ) {
					return wp_easycart_escape_html( $texts[ strtoupper( self::$language_code ) ] );
				}
			}
			return wp_easycart_escape_html( $text );
		}

		public function export_language( $language_key ) {
			$download_content = json_encode( self::$language_data->{$language_key} );
			header( "Cache-Control: public, must-revalidate" );
			header( "Pragma: no-cache" );
			header( "Content-Type: text/plain" );
			header( "Content-Length: " . strlen( $download_content ) );
			header( 'Content-Disposition: attachment; filename="' . $language_key . '.txt"' );
			header( "Content-Transfer-Encoding: binary\n" );
			echo json_encode( self::$language_data->{$language_key} );
		}

		private function update_language_entry( $file_name ) {
			$new_language_object = self::get_language_file_decoded( $file_name . ".txt" );
			$new_array = ( $new_language_object && isset( $new_language_object->options ) ) ? get_object_vars( $new_language_object->options ) : array();
			$new_keys = array_keys( $new_array );

			$current_language_object = self::$language_data->{$file_name};
			$current_array = get_object_vars( $current_language_object->options );
			$current_keys = array_keys( $current_array );

			foreach ( $new_keys as $new_key ) {
				if ( !in_array( $new_key, $current_keys ) ) {
					self::$language_data->{$file_name}->options->{$new_key} = $new_language_object->options->{$new_key};
				} else {
					$new_sub_array = get_object_vars( $new_language_object->options->{$new_key}->options );
					$new_sub_keys = array_keys( $new_sub_array );

					$current_sub_array = get_object_vars( $current_language_object->options->{$new_key}->options );
					$current_sub_keys = array_keys( $current_sub_array );

					foreach ( $new_sub_keys as $new_sub_key ) {
						if ( !in_array( $new_sub_key, $current_sub_keys ) ) {
							self::$language_data->{$file_name}->options->{$new_key}->options->{$new_sub_key} = $new_language_object->options->{$new_key}->options->{$new_sub_key};
						}
					}
				}

			}

		}

		private function add_new_language_file( $file_name ) {
			if ( !isset( self::$language_data->{$file_name} ) )
				self::$language_data->{$file_name} = self::get_language_file_decoded( $file_name . ".txt" );	
		}

		public function save_language_data() {
			foreach ( self::$language_data as $language_file => $files ) {
				foreach ( $files as $language_section => $sections ) {
					if ( is_array( $sections ) || is_object( $sections ) ) {
						foreach ( $sections as $key => $value ) {
							if ( isset( self::$language_data->{$language_file} ) && isset( self::$language_data->{$language_file}->options->{$language_section} ) && isset( self::$language_data->{$language_file}->options->{$language_section}->options->{$key} ) ) {
								self::$language_data->{$language_file}->options->{$language_section}->options->{$key}->value = str_replace( '"', '\"', $value->value );
							}
						}
					}
				}
			}
			update_option( 'ec_option_language_data', self::get_encoded_language_data() );
		}

		private function get_encoded_language_data() {
			return json_encode( self::$language_data );
		}

		private function get_decoded_language_data() {
			return json_decode( html_entity_decode( get_option( 'ec_option_language_data' ) ) );
		}

		private function language_file_checker( $file_contents ) {
			self::check_bracket_count( $file_contents );
			self::check_for_quotes( $file_contents );
		}

		private function check_bracket_count( $file_contents ) {
			$open_count = substr_count( trim($file_contents), "{" );
			$close_count = substr_count( trim($file_contents), "}" );

			if ( $open_count > $close_count ) {
				throw new Exception( "Too many open brackets in language file." );
			} else if ( $close_count > $open_count ) {
				throw new Exception( "Too many closed brackets in language file." );
			}
		}

		private function check_for_quotes( $file_contents ) {
			$open_bracket_found = false;
			$open_paren_found = false;
			$closed_paren_found = false;
			for ( $i = 0; $i < strlen( $file_contents ); $i++) {
				$char = substr( $file_contents, $i, 1 );
				if ( $open_bracket_found && $char != '"' ) {
					throw new Exception( "Needed a paren after open bracket at character " . esc_attr( $i ) . "." );
				} else {
					$open_bracket_found = false;
				}
				if ( $open_paren_found && $char == '"' ) {
					$closed_paren_found = true;
					$open_paren_found = false;
				}
				if ( $closed_paren_found && $char != ':' && $char != ',' && $char != "}" ) {
					throw new Exception( "Expected a : or , or } after a closed paren at character " . esc_attr( $i ) . "." );
				} else {
					$closed_paren_found = false;	
				}
			}
		}

	}
endif;

function wp_easycart_language() {
	return wp_easycart_language::instance();
}
wp_easycart_language();
$GLOBALS['language'] = wp_easycart_language::instance();
