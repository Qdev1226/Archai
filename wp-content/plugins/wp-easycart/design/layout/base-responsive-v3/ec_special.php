<div class="ec_product item<?php echo esc_attr( $i+1 ); ?>">
	<div class="ec_product_images">
		<a href="<?php echo esc_attr( $product->get_product_link( ) ); ?>" class="ec_product_image">
			<img src="<?php if( count( $product->images->product_images ) > 0 ) { 
				if ( 'image1' == $product->images->product_images[0] ) {
					echo esc_attr( $product->get_first_image_url( ) );
				} else if( 'image2' == $product->images->product_images[0] ) {
					echo esc_attr( $product->get_second_image_url( ) );
				} else if( 'image3' == $product->images->product_images[0] ) {
					echo esc_attr( $product->get_third_image_url( ) );
				} else if( 'image4' == $product->images->product_images[0] ) {
					echo esc_attr( $product->get_fourth_image_url( ) );
				} else if( 'image5' == $product->images->product_images[0] ) {
					echo esc_attr( $product->get_fifth_image_url( ) );
				} else if( 'image:' == substr( $product->images->product_images[0], 0, 6 ) ) {
					esc_attr( substr( $product->images->product_images[0], 6, strlen( $product->images->product_images[0] ) - 6 ) );
				} else if( 'video:' == substr( $product->images->product_images[0], 0, 6 ) ) {
					$video_str = substr( $product->images->product_images[0], 6, strlen( $product->images->product_images[0] ) - 6 );
					$video_arr = explode( ':::', $video_str );
					if ( count( $video_arr ) >= 2 ) {
						echo esc_attr( $video_arr[1] );
					} else {
						if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
							echo esc_attr( get_option( 'ec_option_product_image_default' ) );
						} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/themes/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/themes/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) ) {
							echo esc_attr( plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DATA_DIRECTORY ) );
						} else {
							echo esc_attr( plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DIRECTORY ) );
						}
					}
				} else if( 'youtube:' == substr( $product->images->product_images[0], 0, 8 ) ) {
					$youtube_video_str = substr( $product->images->product_images[0], 8, strlen( $product->images->product_images[0] ) - 8 );
					$youtube_video_arr = explode( ':::', $youtube_video_str );
					if ( count( $youtube_video_arr ) >= 2 ) {
						echo esc_attr( $youtube_video_arr[1] );
					} else {
						if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
							echo esc_attr( get_option( 'ec_option_product_image_default' ) );
						} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/themes/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/themes/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) ) {
							echo esc_attr( plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DATA_DIRECTORY ) );
						} else {
							echo esc_attr( plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DIRECTORY ) );
						}
					}
				} else if( 'vimeo:' == substr( $product->images->product_images[0], 0, 6 ) ) {
					$vimeo_video_str = substr( $product->images->product_images[0], 6, strlen( $product->images->product_images[0] ) - 6 );
					$vimeo_video_arr = explode( ':::', $vimeo_video_str );
					if ( count( $vimeo_video_arr ) >= 2 ) {
						echo esc_attr( $vimeo_video_arr[1] );
					} else {
						if ( get_option( 'ec_option_product_image_default' ) && '' != get_option( 'ec_option_product_image_default' ) ) {
							echo esc_attr( get_option( 'ec_option_product_image_default' ) );
						} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/themes/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) && !is_dir( EC_PLUGIN_DATA_DIRECTORY . '/design/themes/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg' ) ) {
							echo esc_attr( plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DATA_DIRECTORY ) );
						} else {
							echo esc_attr( plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/images/ec_image_not_found.jpg', EC_PLUGIN_DIRECTORY ) );
						}
					}
				} else {
					$product_image_media = wp_get_attachment_image_src( $product->images->product_images[0], 'large' );
					echo esc_attr( $product_image_media[0] );
				}
			} else { 
				echo esc_attr( $product->get_first_image_url( ) );
			} ?>" alt="<?php echo esc_attr( strip_tags( $product->title ) ); ?>" class="skip-lazy" />
		</a>
	</div>
	<div class="ec_product_title">
		<?php $product->display_product_title_link(); ?>
	</div>
	<?php if( ( $product->is_catalog_mode && get_option( 'ec_option_hide_price_seasonal' ) ) || 
			  ( $product->is_inquiry_mode && get_option( 'ec_option_hide_price_inquiry' ) ) ){ // don't show price
		  }else{ ?>
	<div class="ec_price_container_type1">
		<?php if( $product->list_price > 0 ){ ?>
			<span class="ec_list_price_type1"><?php $list_price = $GLOBALS['currency']->get_currency_display( $product->list_price );
				$list_price = apply_filters( 'wp_easycart_product_list_price_display', $list_price, $product->list_price );
				echo esc_attr( $list_price ); ?></span>
		<?php }?>
		<span class="ec_price_type1"><?php 
			$display_price = $GLOBALS['currency']->get_currency_display( $product->price );
			if( $product->pricing_per_sq_foot && !get_option( 'ec_option_enable_metric_unit_display' ) ){ 
				$display_price .= "/sq ft";
			}else if( $product->pricing_per_sq_foot && get_option( 'ec_option_enable_metric_unit_display' ) ){ 
				$display_price .= "/sq m";
			}
			$display_price = apply_filters( 'wp_easycart_product_price_display', $display_price, $product->price, $product->product_id );
			echo esc_attr( $display_price );
		?><?php if( $GLOBALS['ec_vat_included'] && $product->vat_rate == 1 && get_option( 'ec_option_show_multiple_vat_pricing' ) ){ ?> <span class="ec_inc_vat_text"><?php echo wp_easycart_language( )->get_text( 'product_page', 'product_inc_vat_text' ); ?></span><?php }else if( $GLOBALS['ec_vat_added'] && $product->vat_rate == 1 && get_option( 'ec_option_show_multiple_vat_pricing' ) ){ ?> <span class="ec_inc_vat_text"><?php echo wp_easycart_language( )->get_text( 'product_page', 'product_excluding_vat_text' ); ?></span><?php }?></span>
	</div>
	<?php }?>
</div>