<?php
class ec_prodimages {
	public $use_optionitem_images;
	public $options;
	public $model_number;
	public $additional_link_options;
	public $post_id;
	public $guid;
	public $is_deconetwork;
	public $deconetwork_link;

	public $image1;
	public $image2;
	public $image3;
	public $image4;
	public $image5;
	public $product_images;

	public $imageset = array();

	private $store_page;
	private $permalink_divider;

	function __construct ( $product_id, $options, $model_number, $use_optionitem_images, $image1, $image2, $image3, $image4, $image5, $image_data, $additional_link_options, $post_id, $guid, $is_deconetwork = false, $deconetwork_link = '', $product_images = '' ) {
		$this->use_optionitem_images = $use_optionitem_images;
		$this->options = $options;
		$this->model_number = $model_number;
		$this->post_id = $post_id;
		$this->guid = $guid;
		$this->additional_link_options = $additional_link_options;
		$this->is_deconetwork = $is_deconetwork;
		$this->deconetwork_link = $deconetwork_link;

		$this->image1 = $image1;
		$this->image2 = $image2;
		$this->image3 = $image3;
		$this->image4 = $image4;
		$this->image5 = $image5;
		$this->product_images = ( isset( $product_images ) && '' != $product_images ) ? explode( ',', $product_images ) : array();

		$storepageid = get_option( 'ec_option_storepage' );

		if ( function_exists( 'icl_object_id' ) ) {
			$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->store_page = get_permalink( $storepageid );

		if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
		}

		if ( substr_count( $this->store_page, '?' ) ) {
			$this->permalink_divider = '&';
		} else {
			$this->permalink_divider = '?';
		}

		if ( $this->use_optionitem_images ) {
			for ( $i = 0; $i < count( $image_data ); $i++ ) {
				if ( 0 == $i ) {
					$this->image1 = $image_data[$i]->image1;
					$this->image2 = $image_data[$i]->image2;
					$this->image3 = $image_data[$i]->image3;
					$this->image4 = $image_data[$i]->image4;
					$this->image5 = $image_data[$i]->image5;
					if ( isset( $image_data[$i]->product_images ) && is_array( $image_data[$i]->product_images ) ) { 
						$this->product_images = $image_data[$i]->product_images;
					} else {
						$this->product_images = ( isset( $image_data[$i]->product_images ) && '' != $image_data[$i]->product_images ) ? explode( ',', $image_data[$i]->product_images ) : array();
					}
				}
				if ( isset( $image_data[$i]->product_images ) && is_array( $image_data[$i]->product_images ) ) { 
					$image_data[$i]->product_images = $image_data[$i]->product_images;
				} else {
					$image_data[$i]->product_images = ( isset( $image_data[$i]->product_images ) && '' != $image_data[$i]->product_images ) ? explode( ',', $image_data[$i]->product_images ) : array();
				}
				array_push( $this->imageset, new ec_prodimageset( $product_id, $image_data[$i] ) );
			}
		}
	}

	public function get_single_image() {
		if ( count( $this->product_images ) > 0 ) {
			if( 'image1' == $this->product_images[0] ) {
				if ( substr( $this->image1, 0, 7 ) == 'http://' || substr( $this->image1, 0, 8 ) == 'https://' ){
					return $this->image1;
				} else {
					return plugins_url( "/wp-easycart-data/products/pics1/" . $this->image1, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image2' == $this->product_images[0] ) {
				if ( substr( $this->image2, 0, 7 ) == 'http://' || substr( $this->image2, 0, 8 ) == 'https://' ){
					return $this->image2;
				} else {
					return plugins_url( "/wp-easycart-data/products/pics2/" . $this->image2, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image3' == $this->product_images[0] ) {
				if ( substr( $this->image3, 0, 7 ) == 'http://' || substr( $this->image3, 0, 8 ) == 'https://' ){
					return $this->image3;
				} else {
					return plugins_url( "/wp-easycart-data/products/pics3/" . $cartitem_data->image3, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image4' == $this->product_images[0] ) {
				if ( substr( $this->image4, 0, 7 ) == 'http://' || substr( $this->image4, 0, 8 ) == 'https://' ){
					return $this->image4;
				} else {
					return plugins_url( "/wp-easycart-data/products/pics4/" . $this->image4, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image5' == $this->product_images[0] ) {
				if ( substr( $this->image5, 0, 7 ) == 'http://' || substr( $this->image5, 0, 8 ) == 'https://' ){
					return $this->image5;
				} else {
					return plugins_url( "/wp-easycart-data/products/pics5/" . $this->image5, EC_PLUGIN_DATA_DIRECTORY );
				}
			} else if( 'image:' == substr( $this->product_images[0], 0, 6 ) ) {
				return substr( $this->product_images[0], 6, strlen( $this->product_images[0] ) - 6 );
			} else if( 'video:' == substr( $this->product_images[0], 0, 6 ) ) {
				$video_str = substr( $this->product_images[0], 6, strlen( $this->product_images[0] ) - 6 );
				$video_arr = explode( ':::', $video_str );
				if ( count( $video_arr ) >= 2 ) {
					return $video_arr[1];
				}
			} else if( 'youtube:' == substr( $this->product_images[0], 0, 8 ) ) {
				$youtube_video_str = substr( $this->product_images[0], 8, strlen( $this->product_images[0] ) - 8 );
				$youtube_video_arr = explode( ':::', $youtube_video_str );
				if ( count( $youtube_video_arr ) >= 2 ) {
					return $youtube_video_arr[1];
				}
			} else if( 'vimeo:' == substr( $this->product_images[0], 0, 6 ) ) {
				$vimeo_video_str = substr( $this->product_images[0], 6, strlen( $this->product_images[0] ) - 6 );
				$vimeo_video_arr = explode( ':::', $vimeo_video_str );
				if ( count( $vimeo_video_arr ) >= 2 ) {
					return $vimeo_video_arr[1];
				}
			} else {
				$product_image_media = wp_get_attachment_image_src( $this->product_images[0], 'large' );
				if( $product_image_media && isset( $product_image_media[0] ) ) {
					return $product_image_media[0];
				}
			}
		}
		return $this->image1;
	}

	public function get_product_images( $size, $selected, $id_prefix, $js_function_name ) {
		if ( $this->use_optionitem_images ) {
			return $this->get_image_set_html( $size, $selected, false, $id_prefix, $js_function_name, true );
		} else {
			$ret_string = $this->get_image_html( 1, $this->image1, true, 0, $size, true, '', false, $id_prefix );
			return $ret_string;
		}
	}

	public function get_product_details_images( $size, $selected, $id_prefix, $js_function_name ) {
		if ( $this->use_optionitem_images ) {
			return $this->get_image_set_html( $size, $selected, true, $id_prefix, $js_function_name, false );
		} else if( $this->image1 ) {
			$ret_string  = $this->get_image_html( 1, $this->image1, true,  0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 1);", true, $id_prefix );
			$ret_string .= $this->get_image_html( 2, $this->image2, false, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 2);", true, $id_prefix );
			$ret_string .= $this->get_image_html( 3, $this->image3, false, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 3);", true, $id_prefix );
			$ret_string .= $this->get_image_html( 4, $this->image4, false, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 4);", true, $id_prefix );
			$ret_string .= $this->get_image_html( 5, $this->image5, false, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 5);", true, $id_prefix );
			return $ret_string;
		}

	}

	public function has_thumbnails( ) {
		if ( ( $this->use_optionitem_images &&  $this->imageset[0]->image1 && $this->imageset[0]->image2 ) || ( !$this->use_optionitem_images && $this->image1 && $this->image2 ) ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_product_thumbnails( $size, $initial, $id_prefix, $js_function_name ) {
		$ret_string = "";
		if ( $this->use_optionitem_images ) {
			return $this->get_image_set_thumbnails_html( $size, $initial, $id_prefix, $js_function_name );
		} else if( $this->image1 && $this->image2 ) {
			$ret_string .= $this->get_image_html( 1, $this->image1, true, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 1);", false, $id_prefix );
			$ret_string .= $this->get_image_html( 2, $this->image2, true, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 2);", false, $id_prefix );
			$ret_string .= $this->get_image_html( 3, $this->image3, true, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 3);", false, $id_prefix );
			$ret_string .= $this->get_image_html( 4, $this->image4, true, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 4);", false, $id_prefix );
			$ret_string .= $this->get_image_html( 5, $this->image5, true, 0, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', 0, 5);", false, $id_prefix );
			return $ret_string;
		}
	}

	private function get_image_set_html( $size, $selected, $allow_popup, $id_prefix, $js_function_name, $islink ) {
		$ret_string = "";
		for ( $i = 0; $i < count( $this->imageset ); $i++ ) {
			if ( $i == $selected ) {
				$ret_string .= $this->get_image_html( 1, $this->imageset[$i]->image1, true,  $i, $size, $islink, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 1);", $allow_popup, $id_prefix );
			} else {
				$ret_string .= $this->get_image_html( 1, $this->imageset[$i]->image1, false, $i, $size, $islink, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 1);", $allow_popup, $id_prefix );
			}
			if ( $this->imageset[$i]->image2 != "" ) {
				$ret_string .= $this->get_image_html( 2, $this->imageset[$i]->image2, false, $i, $size, $islink, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 2);", $allow_popup, $id_prefix );
			}
			if ( $this->imageset[$i]->image3 != "" ) {
				$ret_string .= $this->get_image_html( 3, $this->imageset[$i]->image3, false, $i, $size, $islink, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 3);", $allow_popup, $id_prefix );
			}
			if ( $this->imageset[$i]->image4 != "" ) {
				$ret_string .= $this->get_image_html( 4, $this->imageset[$i]->image4, false, $i, $size, $islink, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 4);", $allow_popup, $id_prefix );
			}
			if ( $this->imageset[$i]->image5 != "" ) {
				$ret_string .= $this->get_image_html( 5, $this->imageset[$i]->image5, false, $i, $size, $islink, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 5);", $allow_popup, $id_prefix );
			}
		}
		return $ret_string;
	}

	private function get_image_set_thumbnails_html( $size, $initial, $id_prefix, $js_function_name ) {
		$ret_string = "";
		for ( $i = 0; $i < count( $this->imageset ); $i++ ) {
			if ( $i == $initial ) {
				$display = true;
			} else {
				$display = false;
			}
			if ( $this->imageset[$i]->image1 && $this->imageset[$i]->image2 ) {
				$ret_string .= $this->get_image_html( 1, $this->imageset[$i]->image1, $display, $i, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 1);", false, $id_prefix );
				$ret_string .= $this->get_image_html( 2, $this->imageset[$i]->image2, $display, $i, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 2);", false, $id_prefix );
				$ret_string .= $this->get_image_html( 3, $this->imageset[$i]->image3, $display, $i, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 3);", false, $id_prefix );
				$ret_string .= $this->get_image_html( 4, $this->imageset[$i]->image4, $display, $i, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 4);", false, $id_prefix );
				$ret_string .= $this->get_image_html( 5, $this->imageset[$i]->image5, $display, $i, $size, false, "return " . $js_function_name . "('" . $this->model_number . "', " . $i . ", 5);", false, $id_prefix );
			}
		}
		return $ret_string;
	}

	private function get_image_html( $level, $img, $active, $i, $size, $islink, $js, $allow_popup, $id_prefix ) {
		if ( $this->is_deconetwork ) {
			$permalink = $this->deconetwork_link;
		} else {
			$permalink = $this->ec_get_permalink( $this->post_id );
			$add_options_divider = "?";
			if ( substr_count( $permalink, '?' ) ) {
				$add_options_divider = "&";
			}
		}
		if ( $img ) {
			$test_src = EC_PLUGIN_DIRECTORY . "/products/pics" . $level . "/" . $img;
			$test_src2 = EC_PLUGIN_DATA_DIRECTORY . "/products/pics" . $level . "/" . $img;
			$test_src3 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_image_not_found.jpg";
			$test_src4 = EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg";

			if ( substr( $img, 0, 7 ) == 'http://' || substr( $img, 0, 8 ) == 'https://' ) {
				$image_src = $img;

			} else if ( file_exists( $test_src2 ) ) {
				$image_src = plugins_url( "wp-easycart-data/products/pics" . $level . "/" . $img, EC_PLUGIN_DATA_DIRECTORY );

			} else if ( file_exists( $test_src ) ) {
				$image_src = plugins_url( "wp-easycart/products/pics" . $level . "/" . $img, EC_PLUGIN_DIRECTORY );

			} else if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
				$image_src = get_option( 'ec_option_product_image_default' );

			} else if ( file_exists( $test_src3 ) ) {
				$image_src = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

			} else if ( file_exists( $test_src4 ) ) {
				$image_src = plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DATA_DIRECTORY );

			} else {
				$image_src = plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/ec_image_not_found.jpg", EC_PLUGIN_DIRECTORY );
			}
			if( $islink ){
				$ret_string = "<a href=\"" . $permalink;
				if ( $i < count( $this->imageset ) && $this->imageset[$i]->optionitem_id ) {
					$ret_string .= $add_options_divider . "optionitem_id=" . $this->imageset[$i]->optionitem_id;
				}
				if ( ( $i < count( $this->imageset ) && $this->imageset[$i]->optionitem_id ) && $this->additional_link_options ) {
					$ret_string .= $this->additional_link_options;
				} else if( $this->additional_link_options ) {
					$ret_string .= $add_options_divider . substr( $this->additional_link_options, 5, strlen( $this->additional_link_options ) - 5 );
				}
				$ret_string .= "\" class=\"ec_product_image";
				if ( !$active ) {
					$ret_string .= "_inactive";
				}
				$ret_string .= "\" ";
				if ( !$active ) {
					$ret_string .= " style=\"display: none !important;\" ";
				}
				$ret_string .= "  id=\"" . $id_prefix . $this->model_number . "_" . $level . "_" . $i . "\" >";
				$ret_string .= "<img src=\"" . $image_src . "\"";
				if ( $js ) {
					$ret_string .= "onclick=\"" . $js . "\"";
				}
				$ret_string .= " \>";
				$ret_string .= "</a>";

			} else if( $allow_popup ) {
				if( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/products/pics" . $level . "/" . $img ) ) {
					$ret_string = "<a href=\"" . plugins_url( "/wp-easycart-data/products/pics" . $level . "/" . $img, EC_PLUGIN_DATA_DIRECTORY ) . "\" class=\"ec_product_image";
				} else {
					$ret_string = "<a href=\"" . plugins_url( "wp-easycart/products/pics" . $level . "/" . $img, EC_PLUGIN_DIRECTORY ) . "\" class=\"ec_product_image";
				}
				if ( !$active ) {
					$ret_string .= "_inactive";
				}
				$ret_string .= "\" ";
				if ( !$active ) {
					$ret_string .= " style=\"display: none !important;\" ";
				}
				$ret_string .= "   rel = lightbox[".$this->model_number."] id=\"" . $id_prefix . $this->model_number . "_" . $level . "_" . $i . "\" >";
				$ret_string .= "<img src=\"" . $image_src . "\"";
				if ( $js ) {
					$ret_string .= "onclick=\"" . $js . "\"";
				}
				$ret_string .= " \>";
				$ret_string .= "</a>";

			}else{
				$ret_string = "<img src=\"" . $image_src . "\"";
				$ret_string .= " class=\"ec_product_image";	
				if ( !$active ) {
					$ret_string .= "_inactive";
				}
				$ret_string .= "\" ";
				if ( !$active ) {
					$ret_string .= " style=\"display: none !important;\" ";
				}
				$ret_string .= " id=\"" . $id_prefix . $this->model_number . "_" . $level . "_" . $i . "\"";
				if ( $js ) {
					$ret_string .= "onclick=\"" . $js . "\"";
				}
				$ret_string .= " \>";
			}

			return $ret_string;
		}
	}

	private function ec_get_permalink( $postid ) {
		if ( ! get_option( 'ec_option_use_old_linking_style' ) && $postid != "0" ){
			return $this->guid;
		} else {
			return $this->store_page . $this->permalink_divider . "model_number=" . $this->model_number;
		}
	}
}
