<?php

/**
 * Class BWGViewThumbnails_mosaic
 */
class BWGViewThumbnails_mosaic extends BWGViewSite {
  /**
   * Display.
   *
   * @param array $params
   * @param int $bwg
   */
  public function display( $params = array(), $bwg = 0, $ajax = FALSE ) {
    $images = $params['image_rows']['images'];
    $theme_row = $params['theme_row'];
    $play_icon = $params['play_icon'];
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
    $mosaic_block_id = 'bwg_' . $params['gallery_type'] . '_' . $bwg;
    ob_start();
    ?>
    <div id="bwg_mosaic_thumbnails_div_<?php echo esc_attr($bwg); ?>"
         class="bwg-container-<?php echo esc_attr($bwg); ?> bwg-container bwg-mosaic-thumbnails"
         data-bwg="<?php echo esc_attr($bwg); ?>"
         data-mosaic-direction="<?php echo esc_attr($params['mosaic_hor_ver']); ?>"
         data-resizable="<?php echo esc_attr($params['resizable_mosaic']); ?>"
         data-width="<?php echo esc_attr($params['thumb_width']); ?>"
         data-height="<?php echo esc_attr($params['thumb_height']); ?>"
         data-total-width="<?php echo esc_attr($params['mosaic_total_width']); ?>"
         data-block-id="<?php echo esc_attr($mosaic_block_id); ?>"
         data-thumb-padding="<?php echo esc_attr($theme_row->mosaic_thumb_padding); ?>"
         data-thumb-border="<?php echo esc_attr($theme_row->mosaic_thumb_border_width); ?>"
         data-title-margin="<?php echo esc_attr($theme_row->mosaic_thumb_title_margin); ?>"
         data-image-title="<?php echo esc_attr($params['image_title']); ?>"
         data-play-icon="<?php echo esc_attr($play_icon); ?>"
         data-ecommerce-icon="<?php echo esc_attr($params['ecommerce_icon']); ?>"
         data-gallery-id="<?php echo esc_attr($params['gallery_id']); ?>"
         data-lightbox-url="<?php echo esc_url(addslashes(add_query_arg($params['params_array'], admin_url('admin-ajax.php')))); ?>"
         data-mosaic-thumb-transition="<?php echo ($theme_row->mosaic_thumb_transition) ? true : false; ?>"
         style="visibility: hidden" >
      <div id="<?php echo esc_attr($mosaic_block_id); ?>" class="bwg_mosaic_thumbnails_<?php echo esc_attr($bwg); ?>">
        <?php foreach ( $images as $image_row ) {
        $is_embed = preg_match('/EMBED/',$image_row->filetype) == 1 ? true :false;
        $is_embed_video = preg_match('/VIDEO/',$image_row->filetype) == 1 ? true :false;
		    $bwg_thumb_url = esc_url($is_embed ? $image_row->thumb_url : BWG()->upload_url . $image_row->thumb_url);
		    $is_zoom_hover_effect = ($theme_row->mosaic_thumb_hover_effect == 'zoom' && $params['image_title'] == 'hover') ? true : false;
        $resolution_thumb = $image_row->resolution_thumb;
        $image_thumb_width = '';
        $image_thumb_height = '';

        if($resolution_thumb != "" && strpos($resolution_thumb,'x') !== false) {
          $resolution_th = explode("x", $resolution_thumb);
          $image_thumb_width = $resolution_th[0];
          $image_thumb_height = $resolution_th[1];
        }
		    ?>
          <a <?php echo (esc_html($params['thumb_click_action']) == 'open_lightbox' ? (' class="bwg-a bwg_lightbox bwg_lightbox_' . esc_attr($bwg) . '"' . (BWG()->options->enable_seo ? ' href="' . esc_url($is_embed ? $image_row->thumb_url : BWG()->upload_url . $image_row->image_url) . '"' : '') . ' data-image-id="' . esc_attr($image_row->id) . '" data-elementor-open-lightbox="no"') : ('class="bwg-a" ' . (esc_html($params['thumb_click_action']) == 'redirect_to_url' && $image_row->redirect_url ? 'href="' . esc_url($image_row->redirect_url) . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : ''))) ?>>
          <div class="bwg_mosaic_thumb_spun_<?php echo esc_attr($bwg); ?> bwg-item0 bwg-mosaic-thumb-span <?php echo ( $is_zoom_hover_effect ) ? 'bwg-zoom-effect' : ''; ?>">
            <img class="bwg_mosaic_thumb_<?php echo esc_attr($bwg); ?> skip-lazy bwg_img_clear bwg_img_custom <?php if( $lazyload ) { ?> bwg_lazyload lazy_loader <?php } ?>"
                 id="<?php echo esc_attr($image_row->id); ?>"
                 src="<?php if( !$lazyload ) { echo esc_url($bwg_thumb_url); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
                 data-src="<?php echo esc_url($bwg_thumb_url); ?>"
                 data-width="<?php echo esc_attr($image_thumb_width); ?>"
                 data-height="<?php echo esc_attr($image_thumb_height); ?>"
                 alt="<?php echo esc_attr($image_row->alt); ?>"
                 title="<?php echo esc_attr($image_row->alt); ?>" />
            <?php
             if ( $is_zoom_hover_effect ) {
              echo '<div class="bwg-zoom-effect-overlay">';
             }
              if ( $play_icon && $is_embed_video ) { ?>
              <span class="bwg_mosaic_play_icon_spun_<?php echo esc_attr($bwg); ?>">
                 <i title="<?php _e('Play', 'photo-gallery'); ?>"  class="bwg-icon-play bwg_mosaic_play_icon_<?php echo esc_attr($bwg); ?>"></i>
              </span>
              <?php
              }
              if ($params['image_title'] == 'hover' || $params['image_title'] == 'show') {
              ?>
              <span class="bwg_mosaic_title_spun1_<?php echo esc_attr($bwg); ?>">
                <span class="bwg_mosaic_title_spun2_<?php echo esc_attr($bwg); ?>">
                  <?php echo htmlspecialchars_decode($image_row->alt, ENT_COMPAT | ENT_QUOTES); ?>
                </span>
              </span>
              <?php
              }
              if ( function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id ) {
              ?>
              <span class="bwg_ecommerce_spun1_<?php echo esc_attr($bwg); ?>">
                <span class="bwg_ecommerce_spun2_<?php echo esc_attr($bwg); ?>">
                <i title="<?php _e('Open', 'photo-gallery'); ?>" class="bwg-icon-sign-out bwg_ctrl_btn bwg_open"></i>
                <i title="<?php _e('Ecommerce', 'photo-gallery'); ?>" class="bwg-icon-shopping-cart bwg_ctrl_btn bwg_ecommerce"></i>
                </span>
              </span>
              <?php
              }
            if ( $is_zoom_hover_effect ) {
              echo '</div>';
            }
            ?>
          </div>
        </a>
      <?php } ?>
      </div>
    </div>
    <?php
    $content = ob_get_clean();

    /* Set theme parameters for Gallery/Gallery group title/description.*/
    $theme_row->thumb_gal_title_font_size = $theme_row->mosaic_thumb_gal_title_font_size;
    $theme_row->thumb_gal_title_font_color = $theme_row->mosaic_thumb_gal_title_font_color;
    $theme_row->thumb_gal_title_font_style = $theme_row->mosaic_thumb_gal_title_font_style;
    $theme_row->thumb_gal_title_font_weight = $theme_row->mosaic_thumb_gal_title_font_weight;
    $theme_row->thumb_gal_title_shadow = $theme_row->mosaic_thumb_gal_title_shadow;
    $theme_row->thumb_gal_title_margin = $theme_row->mosaic_thumb_gal_title_margin;
    $theme_row->thumb_gal_title_align = $theme_row->mosaic_thumb_gal_title_align;

    if ( $ajax ) { /* Ajax response after ajax call for filters and pagination.*/
      parent::ajax_content($params, $bwg, $content);
    }
    else {
      parent::container($params, $bwg, $content);
    }
  }

  /**
   * Get inline styles.
   *
   * @param int $bwg
   * @param array $theme_row
   * @param array $params
   * @return string
   */
  public function inline_styles( $bwg = 0, $theme_row = array(), $params = array() ) {
    ob_start();
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->mosaic_thumbs_bg_color);
    ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #bwg_mosaic_thumbnails_div_<?php echo esc_attr($bwg); ?> {
        width: 100%;
        position: relative;
        background-color: rgba(<?php echo esc_html($rgb_thumbs_bg_color['red']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['green']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['blue']); ?>, <?php echo number_format($theme_row->mosaic_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        text-align: <?php echo esc_html($theme_row->mosaic_thumb_align); ?>;
        justify-content: <?php echo esc_html($theme_row->mosaic_thumb_align); ?>;
        <?php
        if ( $theme_row->thumb_align == 'center' ) {
          ?>
          margin-left: auto;
          margin-right: auto;
          <?php
        }
        elseif ( $theme_row->thumb_align == 'left') {
          ?>
          margin-right: auto;
          <?php
        }
        else {
          ?>
          margin-left: auto;
          <?php
        }
        if ( $theme_row->mosaic_container_margin ) {
          ?>
          padding-left: <?php echo esc_html($theme_row->mosaic_thumb_padding); ?>px;
          padding-top: <?php echo esc_html($theme_row->mosaic_thumb_padding); ?>px;
          <?php
        }
        ?>
      }

    <?php
    if (!$theme_row->mosaic_container_margin && $theme_row->mosaic_thumb_padding) {
      ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumbnails_<?php echo esc_attr($bwg); ?> {
        overflow: hidden;
      }
      <?php
    }
    ?>

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumb_spun_<?php echo esc_attr($bwg); ?> {
        display: block;
        position: absolute;
				border-radius: <?php echo esc_html($theme_row->mosaic_thumb_border_radius); ?>;
				border: <?php echo esc_html($theme_row->mosaic_thumb_border_width); ?>px <?php echo esc_html($theme_row->mosaic_thumb_border_style); ?> #<?php echo esc_html($theme_row->mosaic_thumb_border_color); ?>;
    		<?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->mosaic_thumb_bg_color ); ?>
    		background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', '.number_format($theme_row->mosaic_thumb_bg_transparency / 100, 2, ".", "")); ?>);
        -moz-box-sizing: content-box !important;
        -webkit-box-sizing: content-box !important;
        box-sizing: content-box !important;
      }

			#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumb_<?php echo esc_attr($bwg); ?> {
				display: block;
				-moz-box-sizing: content-box !important;
				-webkit-box-sizing: content-box !important;
				box-sizing: content-box !important;
				margin: 0;
				opacity: <?php echo number_format($theme_row->mosaic_thumb_transparent / 100, 2, ".", ""); ?>;
			}

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumb_spun_<?php echo esc_attr($bwg); ?>:hover {
        opacity: 1;
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
      }

	<?php if ( $theme_row->mosaic_thumb_hover_effect == 'zoom' ) { ?>
     @media only screen and (min-width: 480px) {
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-mosaic-thumbnails .bwg-item0 img {
			<?php echo ($theme_row->mosaic_thumb_transition) ? '-webkit-transition: -webkit-transform .3s; transition: transform .3s;' : ''; ?>
		}

		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-mosaic-thumbnails .bwg-item0 img:hover {
			-ms-transform: scale(<?php echo esc_html($theme_row->mosaic_thumb_hover_effect_value); ?>);
			-webkit-transform: scale(<?php echo esc_html($theme_row->mosaic_thumb_hover_effect_value); ?>);
			transform: scale(<?php echo esc_html($theme_row->mosaic_thumb_hover_effect_value); ?>);
		}
		<?php if ( $params['image_title'] == 'hover' ) { ?>
		.bwg-mosaic-thumbnails .bwg-zoom-effect .bwg-zoom-effect-overlay {
			<?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->mosaic_thumb_bg_color ); ?>
			background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', 0.3'); ?>);
		}
		.bwg-mosaic-thumbnails .bwg-zoom-effect:hover img {
			-ms-transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
			-webkit-transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
			transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
		}
		<?php } ?>
      }
	<?php }
	else {	?>
      @media only screen and (min-width: 480px) {
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumb_spun_<?php echo esc_attr($bwg); ?>:hover {
          transform: <?php echo esc_html($theme_row->mosaic_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->mosaic_thumb_hover_effect_value); ?>);
          -ms-transform: <?php echo esc_html($theme_row->mosaic_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->mosaic_thumb_hover_effect_value); ?>);
          -webkit-transform: <?php echo esc_html($theme_row->mosaic_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->mosaic_thumb_hover_effect_value); ?>);
        }
      }
	<?php } ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumbnails_<?php echo esc_attr($bwg); ?> {
        background-color: rgba(<?php echo esc_html($rgb_thumbs_bg_color['red']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['green']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['blue']); ?>, <?php echo number_format($theme_row->mosaic_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        position: relative;
        text-align: <?php echo esc_html($theme_row->mosaic_thumb_align); ?>;
        display: inline-block;
        visibility: hidden;
        <?php
        if ( !$theme_row->mosaic_container_margin ) {
          ?>
          margin-right: -<?php echo esc_html($theme_row->mosaic_thumb_padding); ?>px;
          <?php
        }
        ?>
      }

	  /*image title styles*/
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_title_spun1_<?php echo esc_attr($bwg); ?>,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-mosaic-thumbnails .bwg_ecommerce_spun1_<?php echo esc_attr($bwg); ?> {
        position: absolute;
        display: block;
        opacity: 0;
        box-sizing: border-box;
        text-align: center;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-mosaic-thumbnails .bwg_mosaic_title_spun2_<?php echo esc_attr($bwg); ?>,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-mosaic-thumbnails .bwg_ecommerce_spun2_<?php echo esc_attr($bwg); ?> {
		    color: #<?php echo ( esc_html($params['image_title']) == 'hover') ? (isset($theme_row->mosaic_thumb_title_font_color_hover) ? esc_html($theme_row->mosaic_thumb_title_font_color_hover) : esc_html($theme_row->mosaic_thumb_title_font_color)) : esc_html($theme_row->mosaic_thumb_title_font_color); ?>;
        font-family: <?php echo esc_html($theme_row->mosaic_thumb_title_font_style); ?>;
        font-size: <?php echo esc_html($theme_row->mosaic_thumb_title_font_size); ?>px;
        font-weight: <?php echo esc_html($theme_row->mosaic_thumb_title_font_weight); ?>;
        text-shadow: <?php echo esc_html($theme_row->mosaic_thumb_title_shadow); ?>;
        vertical-align: middle;
        word-wrap: break-word;
      }

      <?php if( function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover') { ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-mosaic-thumbnails .bwg_ecommerce_spun1_<?php echo esc_attr($bwg); ?>,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-mosaic-thumbnails .bwg_ecommerce_spun2_<?php echo esc_attr($bwg); ?>, #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_ecommerce_spun2_<?php echo esc_attr($bwg); ?> i {
        opacity: 1 !important;
        font-size: 20px !important;
        z-index: 45;
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumb_spun_<?php echo esc_attr($bwg); ?>:hover img {
        opacity: 0.5;
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_mosaic_thumb_spun_<?php echo esc_attr($bwg); ?>:hover {
        background: #000;
      }

      <?php } ?>
      .bwg_mosaic_play_icon_spun_<?php echo esc_attr($bwg); ?> {
        display: table;
        position: absolute;
        left: -10000px;
        top: 0px;
        opacity: 0;
      }

      .bwg_mosaic_play_icon_<?php echo esc_attr($bwg); ?> {
        color: #<?php echo esc_html($theme_row->mosaic_thumb_title_font_color); ?>;
        font-size: <?php echo esc_html(2 * $theme_row->mosaic_thumb_title_font_size); ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }

      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_gal_title_<?php echo esc_attr($bwg); ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo esc_html($theme_row->mosaic_thumb_gal_title_font_color); ?>;
        display: block;
        font-family: <?php echo esc_html($theme_row->mosaic_thumb_gal_title_font_style); ?>;
        font-size: <?php echo esc_html($theme_row->mosaic_thumb_gal_title_font_size); ?>px;
        font-weight: <?php echo esc_html($theme_row->mosaic_thumb_gal_title_font_weight); ?>;
        padding: <?php echo esc_html($theme_row->mosaic_thumb_gal_title_margin); ?>;
        text-shadow: <?php echo esc_html($theme_row->mosaic_thumb_gal_title_shadow); ?>;
        text-align: <?php echo esc_html($theme_row->mosaic_thumb_gal_title_align); ?>;
      }
      <?php

    if( $params["mosaic_hor_ver"] == "vertical" ) {
      ?>
      @media screen and (max-width: <?php echo esc_html($params['thumb_width'] + 100); ?>px) {
      div[class^="bwg_mosaic_thumbnails_"],
      span[class^="bwg_mosaic_thumb_spun_"] {
      width: 100% !important;
      }
      img[class^="bwg_mosaic_thumb_"] {
      width: 100% !important;
      height: auto !important;
      margin: 0px auto !important;
      }
      }
      <?php
    }
    return ob_get_clean();
  }
}
