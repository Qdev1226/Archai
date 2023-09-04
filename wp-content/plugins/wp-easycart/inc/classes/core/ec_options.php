<?php

class ec_options{
	
	public $wpdb;
	public $options;
	public $optionitems;
	public $optionitemimages;
	
	/****************************************
	* CONSTRUCTOR
	*****************************************/
	function __construct( ){
		global $wpdb;
		$this->wpdb =& $wpdb;
		$this->options = array( );
		$this->optionitems = array( );
		$this->optionitemimages = array( );
	}
	
	public function get_option( $option_id ){
		
		$option = wp_cache_get( 'wpeasycart-option-'.$option_id, 'wpeasycart-options' );
		if( !$option ){
			$option = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM ec_option WHERE ec_option.option_id = %d ORDER BY ec_option.option_id", $option_id ) );
			wp_cache_set( 'wpeasycart-option-'.$option_id, $option, 'wpeasycart-options' );
		}
		return $option;
		
	}
	
	public function get_all_optionitems( ){
		$optionitems = wp_cache_get( 'wpeasycart-optionset-optionitems-all', 'wpeasycart-options' );
		if( !$optionitems ){
			$optionitems = $this->wpdb->get_results( "SELECT ec_optionitem.*, ec_option.option_name, ec_option.option_label FROM ec_optionitem LEFT JOIN ec_option ON ec_option.option_id = ec_optionitem.option_id ORDER BY ec_optionitem.option_id ASC, ec_optionitem.optionitem_order ASC" );
			wp_cache_set( 'wpeasycart-optionset-optionitems-all', $optionitems, 'wpeasycart-options' );
		}
		return $optionitems;
	}
	
	public function get_optionitems( $option_id ){
		
		$optionitems = wp_cache_get( 'wpeasycart-optionset-optionitems-'.$option_id, 'wpeasycart-options' );
		if( !$optionitems ){
			$optionitems = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT * FROM ec_optionitem WHERE ec_optionitem.option_id = %d ORDER BY ec_optionitem.option_id ASC, ec_optionitem.optionitem_order ASC", $option_id ) );
			$optionitems = apply_filters( 'wpeasycart_basic_optionitems', $optionitems );
			wp_cache_set( 'wpeasycart-optionset-optionitems-'.$option_id, $optionitems, 'wpeasycart-options' );
		}
		return $optionitems;
		
	}
	
	public function get_optionitem( $optionitem_id ){
		
		$optionitem = wp_cache_get( 'wpeasycart-optionitem-'.$optionitem_id, 'wpeasycart-optionitems' );
		if( !$optionitem ){
			$optionitem = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT * FROM ec_optionitem WHERE ec_optionitem.optionitem_id = %d ORDER BY ec_optionitem.option_id ASC, ec_optionitem.optionitem_order ASC", $optionitem_id ) );
			$optionitem = apply_filters( 'wpeasycart_basic_optionitem', $optionitem );
			wp_cache_set( 'wpeasycart-optionitem-'.$optionitem_id, $optionitem, 'wpeasycart-optionitems' );
		}
		return $optionitem;
		
	}
	
	public function get_optionitem_images( $product_id ){
		
		$optionitem_images = wp_cache_get( 'wpeascyart-optionitem-images-'.$product_id, 'wpeasycart-optionitems' );
		if( !$optionitem_images ){
			$optionitem_images = $this->wpdb->get_results( $this->wpdb->prepare( "SELECT 
						ec_optionitemimage.optionitemimage_id,
						ec_optionitemimage.optionitem_id, 
						ec_optionitemimage.product_id, 
						ec_optionitemimage.image1, 
						ec_optionitemimage.image2, 
						ec_optionitemimage.image3, 
						ec_optionitemimage.image4, 
						ec_optionitemimage.image5,
						ec_optionitemimage.product_images,
						ec_optionitem.optionitem_order
						
						FROM ec_optionitemimage, ec_optionitem
		
						WHERE 
						ec_optionitemimage.product_id = %d AND
						ec_optionitem.optionitem_id = ec_optionitemimage.optionitem_id
						
						ORDER BY
						ec_optionitem.optionitem_order", $product_id ) );
			wp_cache_set( 'wpeascyart-optionitem-images-'.$product_id, $optionitem_images, 'wpeasycart-optionitems' );
		}
		return $optionitem_images;
		
	}
	
	public function get_optionitem_image1( $product_id, $optionitem_id ) {
		$optionitem_image1 = wp_cache_get( 'wpeasycart-optionitem-image1-'.$product_id.'-'.$optionitem_id, 'wpeasycart-optionitems' );
		if ( ! $optionitem_image1 ) {
			$optionitem_image1 = false;
			if ( $product_id && $optionitem_id ) {
				$optionitem_image_row = $this->wpdb->get_row( $this->wpdb->prepare( "SELECT ec_optionitemimage.image1, ec_optionitemimage.product_images FROM ec_optionitemimage WHERE ec_optionitemimage.optionitem_id = %d AND ec_optionitemimage.product_id = %d", $optionitem_id, $product_id ) );
				if ( $optionitem_image_row ) {
					$optionitem_image1 = $optionitem_image_row->image1;
					if ( '' != $optionitem_image_row->product_images ) {
						$product_images = explode( ',', $optionitem_image_row->product_images );
						if( $product_images && is_array( $product_images ) && count( $product_images ) > 0 ) {
							if( 'image1' == $product_images[0] ) {
								if ( substr( $optionitem_image_row->image1, 0, 7 ) == 'http://' || substr( $optionitem_image_row->image1, 0, 8 ) == 'https://' ){
									$optionitem_image1 = $optionitem_image_row->image1;
								} else {
									$optionitem_image1 = plugins_url( "/wp-easycart-data/products/pics1/" . $optionitem_image_row->image1, EC_PLUGIN_DATA_DIRECTORY );
								}
							} else if( 'image2' == $product_images[0] ) {
								if ( substr( $optionitem_image_row->image2, 0, 7 ) == 'http://' || substr( $optionitem_image_row->image2, 0, 8 ) == 'https://' ){
									$optionitem_image1 = $optionitem_image_row->image2;
								} else {
									$optionitem_image1 = plugins_url( "/wp-easycart-data/products/pics2/" . $optionitem_image_row->image2, EC_PLUGIN_DATA_DIRECTORY );
								}
							} else if( 'image3' == $product_images[0] ) {
								if ( substr( $optionitem_image_row->image3, 0, 7 ) == 'http://' || substr( $optionitem_image_row->image3, 0, 8 ) == 'https://' ){
									$optionitem_image1 = $optionitem_image_row->image3;
								} else {
									$optionitem_image1 = plugins_url( "/wp-easycart-data/products/pics3/" . $optionitem_image_row->image3, EC_PLUGIN_DATA_DIRECTORY );
								}
							} else if( 'image4' == $product_images[0] ) {
								if ( substr( $optionitem_image_row->image4, 0, 7 ) == 'http://' || substr( $optionitem_image_row->image4, 0, 8 ) == 'https://' ){
									$optionitem_image1 = $optionitem_image_row->image4;
								} else {
									$optionitem_image1 = plugins_url( "/wp-easycart-data/products/pics4/" . $optionitem_image_row->image4, EC_PLUGIN_DATA_DIRECTORY );
								}
							} else if( 'image5' == $product_images[0] ) {
								if ( substr( $optionitem_image_row->image5, 0, 7 ) == 'http://' || substr( $optionitem_image_row->image5, 0, 8 ) == 'https://' ){
									$optionitem_image1 = $optionitem_image_row->image5;
								} else {
									$optionitem_image1 = plugins_url( "/wp-easycart-data/products/pics5/" . $optionitem_image_row->image5, EC_PLUGIN_DATA_DIRECTORY );
								}
							} else if( 'image:' == substr( $product_images[0], 0, 6 ) ) {
								$optionitem_image1 = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
							} else if( 'video:' == substr( $product_images[0], 0, 6 ) ) {
								$video_str = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
								$video_arr = explode( ':::', $video_str );
								if ( count( $video_arr ) >= 2 ) {
									$optionitem_image1 = $video_arr[1];
								}
							} else if( 'youtube:' == substr( $product_images[0], 0, 8 ) ) {
								$youtube_video_str = substr( $product_images[0], 8, strlen( $product_images[0] ) - 8 );
								$youtube_video_arr = explode( ':::', $youtube_video_str );
								if ( count( $youtube_video_arr ) >= 2 ) {
									$optionitem_image1 = $youtube_video_arr[1];
								}
							} else if( 'vimeo:' == substr( $product_images[0], 0, 6 ) ) {
								$vimeo_video_str = substr( $product_images[0], 6, strlen( $product_images[0] ) - 6 );
								$vimeo_video_arr = explode( ':::', $vimeo_video_str );
								if ( count( $vimeo_video_arr ) >= 2 ) {
									$optionitem_image1 = $vimeo_video_arr[1];
								}
							} else {
								$product_image_media = wp_get_attachment_image_src( $product_images[0], 'large' );
								if( $product_image_media && isset( $product_image_media[0] ) ) {
									$optionitem_image1 = $product_image_media[0];
								}
							}
						}
					}
				}
			}
			wp_cache_set( 'wpeasycart-optionitem-image1-'.$product_id.'-'.$optionitem_id, $optionitem_image1, 'wpeasycart-optionitems' );
		}
		return $optionitem_image1;
	}
}
