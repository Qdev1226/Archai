<?php
class BWGViewThumbnails_masonry extends BWGViewSite {
  public function display($params = array(), $bwg = 0, $ajax = FALSE) {
    $theme_row = $params['theme_row'];
    $image_rows = $params['image_rows'];
    $image_rows = $image_rows['images'];
    $lazyload = BWG()->options->lazyload_images;
    $inline_style = $this->inline_styles($bwg, $theme_row, $params);
    if ( !WDWLibrary::elementor_is_active() ) {
      if ( !$ajax ) {
        if ( BWG()->options->use_inline_stiles_and_scripts ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
        }
        else {
          echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
        }
      }
    }
    else {
      echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
    }
    ob_start();
    if ('horizontal' == $params['masonry_hor_ver']) {
      ?>
      <div class="bwg-masonry-horizontal-parent">
      <div class="bwg-masonry-horizontal-container">
      <?php
    }
    ?>
    <div id="bwg_thumbnails_masonry_<?php echo esc_attr($bwg); ?>"
         data-bwg="<?php echo esc_attr($bwg); ?>"
         data-masonry-type="<?php echo esc_attr($params['masonry_hor_ver']); ?>"
         data-resizable-thumbnails="<?php echo esc_attr(BWG()->options->resizable_thumbnails); ?>"
         data-max-count="<?php echo esc_attr($params['image_column_number']); ?>"
         data-thumbnail-width="<?php echo esc_attr($params['thumb_width']); ?>"
         data-thumbnail-height="<?php echo esc_attr($params['thumb_height']); ?>"
         data-thumbnail-padding="<?php echo esc_attr($theme_row->masonry_thumb_padding); ?>"
         data-thumbnail-border="<?php echo esc_attr($theme_row->masonry_thumb_border_width); ?>"
         data-gallery-id="<?php echo esc_attr($params['gallery_id']); ?>"
         data-lightbox-url="<?php echo esc_url(addslashes(add_query_arg($params['params_array'], admin_url('admin-ajax.php')))); ?>"
         class="bwg-container-<?php echo esc_attr($bwg); ?> bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> bwg-masonry-thumbnails bwg-masonry-<?php echo esc_attr($params['masonry_hor_ver']); ?> bwg-container bwg-border-box">
      <?php
      foreach ($image_rows as $image_row) {
        $is_embed = preg_match('/EMBED/',$image_row->filetype) == 1 ? true : false;
        $is_embed_video = preg_match('/VIDEO/',$image_row->filetype) == 1 ? true : false;
        $bwg_thumb_url = esc_url($is_embed ? $image_row->thumb_url : BWG()->upload_url . $image_row->thumb_url);
        $class = '';
        $data_image_id = '';
        $href = '';
		    $title = '<div class="bwg-title1"><div class="bwg-title2">' . htmlspecialchars_decode($image_row->alt, ENT_COMPAT | ENT_QUOTES) . '</div></div>';
        $play_icon = '<div class="bwg-play-icon1"><i title="' . __('Play', 'photo-gallery') . '" class="bwg-icon-play bwg-title2 bwg-play-icon2"></i></div>';
        $ecommerce_icon = '<div class="bwg-ecommerce1"><div class="bwg-ecommerce2">';
        if ( $image_row->pricelist_id ) {
          $ecommerce_icon .= '<i title="' . __('Open', 'photo-gallery') . '" class="bwg-icon-sign-out bwg_ctrl_btn bwg_open"></i>&nbsp;';
          $ecommerce_icon .= '<i title="' . __('Ecommerce', 'photo-gallery') . '" class="bwg-icon-shopping-cart bwg_ctrl_btn bwg_ecommerce"></i>';
        }
        else {
          $ecommerce_icon .= '&nbsp;';
        }
        $ecommerce_icon .= '</div></div>';
        if ( $params['thumb_click_action'] == 'open_lightbox' ) {
          $class = ' bwg_lightbox';
          $data_image_id = ' data-image-id="' . esc_attr($image_row->id) . '"';
          if ( BWG()->options->enable_seo ) {
            $href = ' href="' . esc_url($is_embed ? $image_row->thumb_url : BWG()->upload_url . $image_row->image_url) . '"';
          }
        }
        elseif ( $params['thumb_click_action'] == 'redirect_to_url' && $image_row->redirect_url ) {
          $href = ' href="' . esc_url($image_row->redirect_url) . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"';
        }

        $resolution_thumb = $image_row->resolution_thumb;
        $image_thumb_width = '';
        $image_thumb_height = '';

        if ( $resolution_thumb != "" && strpos($resolution_thumb,'x') !== false ) {
          $resolution_th = explode("x", $resolution_thumb);
          $image_thumb_width = $resolution_th[0];
          $image_thumb_height = $resolution_th[1];
        }
        ?>
        <div class="bwg-item">
          <a class="bwg-a <?php echo esc_attr($class); ?>" <?php echo $data_image_id; ?><?php echo $href; ?> data-elementor-open-lightbox="no">
            <div class="bwg-item0">
              <div class="bwg-item1 <?php echo $theme_row->masonry_thumb_hover_effect == 'zoom' && $params['image_title'] == 'hover' ? 'bwg-zoom-effect' : ''; ?>">
      					<img class="skip-lazy bwg-masonry-thumb bwg_masonry_thumb_<?php echo esc_attr($bwg); ?> <?php if( $lazyload ) { ?> bwg_lazyload lazy_loader<?php } ?>"
                       data-id="<?php echo esc_attr($image_row->id); ?>"
                       data-src="<?php echo esc_url($bwg_thumb_url); ?>"
                       data-width="<?php echo esc_attr($image_thumb_width); ?>"
                       data-height="<?php echo esc_attr($image_thumb_height); ?>"
										   src="<?php if( !$lazyload ) { echo esc_url($bwg_thumb_url); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
										   alt="<?php echo esc_attr($image_row->alt); ?>"
										   title="<?php echo esc_attr($image_row->alt); ?>" />
                <div class="<?php echo $theme_row->masonry_thumb_hover_effect == 'zoom' && $params['image_title'] == 'hover' ? 'bwg-zoom-effect-overlay' : ''; ?>">
                  <?php if ( $params['masonry_hor_ver'] == 'vertical' && $params['image_title'] == 'hover' && !empty($image_row->alt) ) { echo WDWLibrary::strip_tags($title); } ?>
				          <?php if ( function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id ) { echo WDWLibrary::strip_tags($ecommerce_icon); } ?>
                  <?php if ( $is_embed_video && $params['play_icon'] ) { echo WDWLibrary::strip_tags($play_icon); } ?>
                </div>
              </div>
            </div>
            <?php if ( $params['masonry_hor_ver'] == 'vertical' &&  $params['image_title'] == 'show' && !empty($image_row->alt) ) { echo WDWLibrary::strip_tags($title); } ?>
          </a>
          <?php
          if ( $params['masonry_hor_ver'] == 'vertical' && $params['show_masonry_thumb_description'] && $image_row->description) {
            ?>
            <div class="bwg-masonry-thumb-description bwg_masonry_thumb_description_<?php echo esc_attr($bwg); ?>">
              <span><?php echo WDWLibrary::strip_tags(stripslashes($image_row->description)); ?></span>
            </div>
            <?php
          }
          ?>
        </div>
      <?php } ?>
    </div>
    <?php
    if ( 'horizontal' == $params['masonry_hor_ver'] ) {
      ?>
      </div>
      </div>
      <?php
    }
    $content = ob_get_clean();
    /* Set theme parameters for Gallery/Gallery group title/description.*/
    $theme_row->thumb_gal_title_font_size = $theme_row->masonry_thumb_gal_title_font_size;
    $theme_row->thumb_gal_title_font_color = $theme_row->masonry_thumb_gal_title_font_color;
    $theme_row->thumb_gal_title_font_style = $theme_row->masonry_thumb_gal_title_font_style;
    $theme_row->thumb_gal_title_font_weight = $theme_row->masonry_thumb_gal_title_font_weight;
    $theme_row->thumb_gal_title_shadow = $theme_row->masonry_thumb_gal_title_shadow;
    $theme_row->thumb_gal_title_margin = $theme_row->masonry_thumb_gal_title_margin;
    $theme_row->thumb_gal_title_align = $theme_row->masonry_thumb_gal_title_align;

    if ( $ajax ) { /* Ajax response after ajax call for filters and pagination.*/
      parent::ajax_content($params, $bwg, $content);
    }
    else {
      parent::container($params, $bwg, $content);
    }
  }

  public function inline_styles($bwg, $theme_row, $params) {
    // This code helps us to have correct styles.
    ob_start();
    ?>
    <style>
      <?php
      ob_end_clean();
      ob_start();
      $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->masonry_thumbs_bg_color);
      ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumb_<?php echo esc_attr($bwg); ?> {
        text-align: center;
        display: inline-block;
        vertical-align: middle;
      	<?php if ( $params['masonry_hor_ver'] == 'vertical' ) { ?>
          width: <?php echo esc_html(BWG()->options->resizable_thumbnails ? '100% !important;' : $params['thumb_width'] . 'px'); ?>;
      	<?php }
      	else { ?>
       	 height: <?php echo esc_html($params['thumb_height'] - $theme_row->masonry_thumb_padding); ?>px;
      	<?php } ?>
        margin: 0;
        opacity: <?php echo number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", ""); ?>;
      }

			<?php if ( $theme_row->masonry_thumb_hover_effect == "scale" ) { ?>
      	#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> {
					overflow:visible;
				}
			<?php } ?>

			<?php if ( !$theme_row->masonry_container_margin && $theme_row->masonry_thumb_padding ) { ?>
				#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-background-<?php echo esc_attr($bwg); ?> {
					overflow: hidden;
				}
      <?php } ?>

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-temp<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item {
        padding: calc(<?php echo esc_html($theme_row->masonry_thumb_padding); ?>px / 2);
				<?php if ( !BWG()->options->resizable_thumbnails ) { ?>
					width: <?php echo esc_html($params['thumb_width']); ?>px !important;
				<?php } ?>
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item0 {
      	<?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->masonry_thumb_bg_color ); ?>
        background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', '.number_format($theme_row->masonry_thumb_bg_transparency / 100, 2, ".", "")); ?>);
        border: <?php echo esc_html($theme_row->masonry_thumb_border_width); ?>px <?php echo esc_html($theme_row->masonry_thumb_border_style); ?> #<?php echo esc_html($theme_row->masonry_thumb_border_color); ?>;
				opacity: <?php echo esc_html(number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", "")); ?>;
        border-radius: <?php echo esc_html($theme_row->masonry_thumb_border_radius); ?>;
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item1 img {
        max-height: none;
				<?php if ( BWG()->options->resizable_thumbnails ) { ?>
        	max-width: calc(<?php echo esc_html($params['thumb_width']); ?>px + (<?php echo esc_html($theme_row->masonry_thumb_padding); ?>px + <?php echo esc_html($theme_row->masonry_thumb_border_width); ?>px));
				<?php } ?>
      }

			<?php if ( $theme_row->masonry_thumb_hover_effect == 'zoom' ) { ?>
				@media only screen and (min-width: 480px) {
					#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item1 img {
						<?php echo ($theme_row->masonry_thumb_transition) ? '-webkit-transition: -webkit-transform .3s; transition: transform .3s;' : ''; ?>
					}
					#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item1 img:hover {
						-ms-transform: scale(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
						-webkit-transform: scale(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
						transform: scale(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
					}
					<?php if ( $params['image_title'] == 'hover' ) { ?>
						.bwg-masonry-thumbnails .bwg-zoom-effect .bwg-zoom-effect-overlay {
							<?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->masonry_thumb_bg_color ); ?>
							background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', 0.3'); ?>);
						}
						.bwg-masonry-thumbnails .bwg-zoom-effect:hover img {
							-ms-transform: scale(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
							-webkit-transform: scale(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
							transform: scale(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
						}
					<?php } ?>
				}
			<?php
			}
    	else {
      ?>
      @media only screen and (min-width: 480px) {
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item0 {
       	 	<?php echo esc_html(($theme_row->masonry_thumb_transition) ? 'transition: transform 0.3s ease 0s; -webkit-transition: -webkit-transform 0.3s ease 0s;' : ''); ?>
        }
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-item0:hover {
          -ms-transform: <?php echo esc_html($theme_row->masonry_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
          -webkit-transform: <?php echo esc_html($theme_row->masonry_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
          transform: <?php echo esc_html($theme_row->masonry_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->masonry_thumb_hover_effect_value); ?>);
        }
      }
      <?php } ?>

			<?php
			/* Show image title on hover.*/
			if ( $params['image_title'] == 'hover' ) { ?>
			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-title1 {
				position: absolute;
				top: 0;
				z-index: 100;
				width: 100%;
				height: 100%;
				display: flex;
				justify-content: center;
				align-content: center;
				flex-direction: column;
				opacity: 0;
			}
	  <?php } ?>

			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-title2,
			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-temp<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-title2,
			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-play-icon2,
			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-ecommerce2 {
				max-height: 100%;
				padding: <?php echo esc_html($theme_row->masonry_thumb_title_margin); ?>;
				font-family: <?php echo esc_html($theme_row->masonry_thumb_title_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->masonry_thumb_title_font_weight); ?>;
				font-size: <?php echo esc_html($theme_row->masonry_thumb_title_font_size); ?>px;
				color: #<?php echo esc_html(( $params['image_title'] == 'hover') ? (isset($theme_row->masonry_thumb_title_font_color_hover) ? $theme_row->masonry_thumb_title_font_color_hover : $theme_row->masonry_thumb_gal_title_font_color) : $theme_row->masonry_thumb_title_font_color); ?>;
			}

			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-ecommerce2 {
				color: #<?php echo esc_html(( $params['ecommerce_icon'] == 'hover') ? (isset($theme_row->masonry_thumb_gal_title_font_color_hover) ? $theme_row->masonry_thumb_gal_title_font_color_hover : $theme_row->masonry_thumb_gal_title_font_color) : $theme_row->masonry_thumb_gal_title_font_color); ?>;
			}

	  	#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> {
				position: relative;
				background-color: rgba(<?php echo esc_html($rgb_thumbs_bg_color['red']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['green']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['blue']); ?>, <?php echo number_format($theme_row->masonry_thumb_bg_transparent / 100, 2, ".", ""); ?>);
				font-size: 0;
				<?php if ( $params['masonry_hor_ver'] == 'vertical' ) { ?>
					width: <?php echo esc_html($params['image_column_number'] * $params['thumb_width'] + ($theme_row->masonry_container_margin ? $theme_row->masonry_thumb_padding : 0)); ?>px;
					max-width: 100%;
				<?php
				}
				else { ?>
					height: <?php echo esc_html($params['image_column_number'] * $params['thumb_height']); ?>px !important;
				<?php
				}
				if ( $theme_row->masonry_container_margin ) { ?>
					max-width: 100%;
				<?php
				}
				else { ?>
					margin-right: -<?php echo esc_html($theme_row->masonry_thumb_padding); ?>px;
					max-width: calc(100% + <?php echo esc_html($theme_row->masonry_thumb_padding); ?>px);
				<?php } ?>
				<?php if ( $theme_row->masonry_thumb_align == 'center' ) { ?>
					margin: 0 auto;
				<?php
				}
				elseif ( $theme_row->masonry_thumb_align == 'left') { ?>
					margin-right: auto;
				<?php
				}
				else { ?>
					margin-left: auto;
				<?php } ?>
			}

			<?php if ( $params['masonry_hor_ver'] == 'vertical' ) { ?>
				@media only screen and (max-width: <?php echo esc_html($params['image_column_number'] * ($params['thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width))); ?>px) {
					#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> {
						width: inherit;
					}
				}
			<?php
			}
			else { ?>
				@media only screen and (max-height: <?php echo esc_html($params['image_column_number'] * ($params['thumb_height'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width))); ?>px) {
					#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> {
						height: inherit;
					}
				}
			<?php } ?>

			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumb_description_<?php echo esc_attr($bwg); ?> {
        color: #<?php echo esc_html($theme_row->masonry_description_color); ?>;
        line-height: 1.4;
        font-size: <?php echo esc_html($theme_row->masonry_description_font_size); ?>px;
        font-family: <?php echo esc_html($theme_row->masonry_description_font_style); ?>;
        text-align: justify;
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?>.bwg_thumbnails_masonry .wd_error p {
        color: #<?php echo esc_html($theme_row->album_masonry_back_font_color); ?>;
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_gal_title_<?php echo esc_attr($bwg); ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo esc_html($theme_row->masonry_thumb_gal_title_font_color); ?>;
        display: block;
        font-family: <?php echo esc_html($theme_row->masonry_thumb_gal_title_font_style); ?>;
        font-size: <?php echo esc_html($theme_row->masonry_thumb_gal_title_font_size); ?>px;
        font-weight: <?php echo esc_html($theme_row->masonry_thumb_gal_title_font_weight); ?>;
        padding: <?php echo esc_html($theme_row->masonry_thumb_gal_title_margin); ?>;
        text-shadow: <?php echo esc_html($theme_row->masonry_thumb_gal_title_shadow); ?>;
        text-align: <?php echo esc_html($theme_row->masonry_thumb_gal_title_align); ?>;
      }

      <?php if ( function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover' ) {
      	/* Show eCommerce icon on hover.*/ ?>
				#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-thumbnails .bwg-ecommerce1 {
					display: flex;
					height: 100%;
					left: -3000px;
					opacity: 0;
					position: absolute;
					top: 0;
					width: 100%;
					z-index: 100;
					justify-content: center;
					align-content: center;
					flex-direction: column;
					text-align: center;
				}
		 <?php
		 }
    return ob_get_clean();
    ob_start();
    ?>
    </style>
    <?php
    ob_end_clean();
  }
}