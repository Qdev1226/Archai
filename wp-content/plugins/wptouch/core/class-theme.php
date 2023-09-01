<?php


class WPtouchTheme extends stdClass {
	var $base;
	var $name;
	var $theme_url;
	var $description;
	var $author;
	var $version;
	var $framework;
	var $wptouch_object;
	var $parent_theme;
	var $loaded;

	function __construct( $wptouch_object ) {
		$this->framework = 1;
		$this->wptouch_object = $wptouch_object;
		$this->loaded = false;
	}

	public function load( $theme_location, $theme_url ) {
		$style_file = $theme_location . '/readme.txt';

		$this->load_style_info( $style_file );
	}

	private function load_style_info( $style_file ) {
		if ( file_exists( $style_file ) ) {
			$style_info = $this->wptouch_object->load_file( $style_file );

			$theme_frags = explode( DIRECTORY_SEPARATOR, trim( $theme_location, DIRECTORY_SEPARATOR ) );

			$this->base = $theme_frags[ count( (array) $theme_frags ) - 1 ];
			$this->name = $this->wptouch_object->get_information_fragment( $style_info, 'Theme Name' );
			$this->theme_url = $this->wptouch_object->get_information_fragment( $style_info, 'Theme URI' );
			$this->description = $this->wptouch_object->get_information_fragment( $style_info, 'Description' );
			$this->author = $this->wptouch_object->get_information_fragment( $style_info, 'Author' );
			$this->version = $this->wptouch_object->get_information_fragment( $style_info, 'Version' );
			$this->framework = $this->wptouch_object->get_information_fragment( $style_info, 'Framework' );
			$features = $this->wptouch_object->get_information_fragment( $style_info, 'Features' );

			if ( $features ) {
				$this->features = explode( ',', str_replace( ', ', ',', $features ) );
			} else {
				$this->features = false;
			}

			$parent_info = $this->wptouch_object->get_information_fragment( $style_info, 'Parent' );
			if ( $parent_info ) {
				$this->parent_theme = $parent_info;
			}

			$this->tags = explode( ',', str_replace( ', ', ',', $this->get_information_fragment( $style_info, 'Tags' ) ) );
			$this->screenshot = $theme_url . '/screenshot.png';
			$this->location = str_replace( WP_CONTENT_DIR, '', $theme_location );

			$this->loaded = true;
		}
	}

	public function framework_version() {
		return ( $this->framework );
	}

	public function is_child() {
		return ( $this->parent_theme );
	}
}
