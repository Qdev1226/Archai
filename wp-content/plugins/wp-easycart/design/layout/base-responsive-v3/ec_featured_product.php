<div class="ec_featured_product prod<?php echo esc_attr( $i ); ?>">
	<div class="ec_featured_product_image">
		<a href="<?php echo esc_attr( $featured_product->get_product_link( ) ); ?>" class="ec_product_image">
			<img src="<?php if( count( $featured_product->images->product_images ) > 0 ) { 
				if ( 'image1' == $featured_product->images->product_images[0] ) {
					echo esc_attr( $featured_product->get_first_image_url( ) );
				} else if( 'image2' == $featured_product->images->product_images[0] ) {
					echo esc_attr( $featured_product->get_second_image_url( ) );
				} else if( 'image3' == $featured_product->images->product_images[0] ) {
					echo esc_attr( $featured_product->get_third_image_url( ) );
				} else if( 'image4' == $featured_product->images->product_images[0] ) {
					echo esc_attr( $featured_product->get_fourth_image_url( ) );
				} else if( 'image5' == $featured_product->images->product_images[0] ) {
					echo esc_attr( $featured_product->get_fifth_image_url( ) );
				} else if( 'image:' == substr( $featured_product->images->product_images[0], 0, 6 ) ) {
					esc_attr( substr( $featured_product->images->product_images[0], 6, strlen( $featured_product->images->product_images[0] ) - 6 ) );
				} else if( 'video:' == substr( $featured_product->images->product_images[0], 0, 6 ) ) {
					$video_str = substr( $featured_product->images->product_images[0], 6, strlen( $featured_product->images->product_images[0] ) - 6 );
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
				} else if( 'youtube:' == substr( $featured_product->images->product_images[0], 0, 8 ) ) {
					$youtube_video_str = substr( $featured_product->images->product_images[0], 8, strlen( $featured_product->images->product_images[0] ) - 8 );
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
				} else if( 'vimeo:' == substr( $featured_product->images->product_images[0], 0, 6 ) ) {
					$vimeo_video_str = substr( $featured_product->images->product_images[0], 6, strlen( $featured_product->images->product_images[0] ) - 6 );
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
					$featured_product_image_media = wp_get_attachment_image_src( $featured_product->images->product_images[0], 'large' );
					echo esc_attr( $featured_product_image_media[0] );
				}
			} else { 
				echo esc_attr( $featured_product->get_first_image_url( ) );
			} ?>" alt="<?php echo esc_attr( strip_tags( $featured_product->title ) ); ?>" class="skip-lazy" />
		</a>
	</div>
	<div class="ec_featured_product_title">
	<?php $featured_product->display_product_title_link(); ?>
	</div>
	<div class="ec_featured_product_rating">
		<div class="ec_featured_product_rating_stars"><?php $featured_product->display_product_stars(); ?></div>
		<div class="ec_featured_product_rating_num_ratings">(<?php $featured_product->display_product_number_reviews(); ?>)</div>
	</div>
</div>