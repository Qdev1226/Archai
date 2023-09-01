<?php
class BWGViewThumbnails extends BWGViewSite {

  public function display($params = array(), $bwg = 0, $ajax = FALSE) {
    $theme_row = $params['theme_row'];
    $image_rows = $params['image_rows'];
    $image_rows = $image_rows['images'];
    $inline_style = $this->inline_styles($bwg, $theme_row, $params);
    $lazyload = BWG()->options->lazyload_images;
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
    ?>
    <div data-max-count="<?php echo esc_attr($params['image_column_number']); ?>"
         data-thumbnail-width="<?php echo esc_attr($params['thumb_width']); ?>"
         data-bwg="<?php echo esc_attr($bwg); ?>"
         data-gallery-id="<?php echo esc_attr($params['gallery_id']); ?>"
         data-lightbox-url="<?php echo esc_attr(addslashes(add_query_arg($params['params_array'], admin_url('admin-ajax.php')))); ?>"
         id="bwg_<?php echo esc_attr($params['gallery_type'].'_'.$bwg) ?>"
         class="bwg-container-<?php echo esc_attr($bwg); ?> bwg-thumbnails bwg-standard-thumbnails bwg-container bwg-border-box">
      <?php
      foreach ($image_rows as $image_row) {
        $is_embed = preg_match('/EMBED/',$image_row->filetype) == 1 ? true : false;
        $is_embed_video = preg_match('/VIDEO/',$image_row->filetype) == 1 ? true : false;
	  		$bwg_thumb_url = esc_url($is_embed ? $image_row->thumb_url : BWG()->upload_url . $image_row->thumb_url);
        $class = '';
        $data_image_id = '';
        $href = '';
        $title = '<div class="bwg-title1"><div class="bwg-title2">' . ($image_row->alt ? htmlspecialchars_decode($image_row->alt, ENT_COMPAT | ENT_QUOTES) : '&nbsp;') . '</div></div>';
        $description = '<div class="bwg-thumb-description bwg_thumb_description_0"><span>' . ($image_row->description ? htmlspecialchars_decode($image_row->description, ENT_COMPAT | ENT_QUOTES) : '') . '</span></div>';
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
          $href = ' href="' . esc_url($image_row->redirect_url) . '" target="' .  (esc_attr($params['thumb_link_target']) ? '_blank' : '')  . '"';
        }

        $resolution_thumb = $image_row->resolution_thumb;
        $image_thumb_width = '';
        $image_thumb_height = '';

        if($resolution_thumb != "" && strpos($resolution_thumb,'x') !== false) {
          $resolution_th = explode("x", $resolution_thumb);
          $image_thumb_width = $resolution_th[0];
          $image_thumb_height = $resolution_th[1];
        }
        ?>
      <div class="bwg-item">
        <a class="bwg-a <?php echo esc_attr($class); ?>" <?php echo $data_image_id; ?><?php echo $href; ?> data-elementor-open-lightbox="no">
        <?php if ( $params['image_title'] == 'show' && $theme_row->thumb_title_pos == 'top' ) { echo WDWLibrary::strip_tags($title); } ?>
        <div class="bwg-item0 <?php if( $lazyload ) { ?> lazy_loader <?php } ?>">
          <div class="bwg-item1 <?php echo $theme_row->thumb_hover_effect == 'zoom' && $params['image_title'] == 'hover' ? 'bwg-zoom-effect' : ''; ?>">
            <div class="bwg-item2">
              <img class="skip-lazy bwg_standart_thumb_img_<?php echo esc_attr($bwg); ?> <?php if( $lazyload ) { ?> bwg_lazyload <?php } ?>"
                   data-id="<?php echo esc_attr($image_row->id); ?>"
                   data-width="<?php echo esc_attr($image_thumb_width); ?>"
                   data-height="<?php echo esc_attr($image_thumb_height); ?>"
                   data-src="<?php echo esc_url($bwg_thumb_url); ?>"
                   src="<?php if( !$lazyload ) { echo esc_url($bwg_thumb_url); } else { echo esc_url(BWG()->plugin_url . '/images/lazy_placeholder.gif'); } ?>"
                   alt="<?php echo esc_attr($image_row->alt); ?>"
                   title="<?php echo esc_attr($image_row->alt); ?>" />
            </div>
            <div class="<?php echo $theme_row->thumb_hover_effect == 'zoom' && $params['image_title'] == 'hover' ? 'bwg-zoom-effect-overlay' : ''; ?>">
              <?php if ( $params['image_title'] == 'hover' ) { echo WDWLibrary::strip_tags($title); } ?>
              <?php if ( function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover' && $image_row->pricelist_id ) { echo WDWLibrary::strip_tags($ecommerce_icon); } ?>
              <?php if ( $is_embed_video && $params['play_icon'] ) { echo WDWLibrary::strip_tags($play_icon); } ?>
            </div>
          </div>
        </div>
        <?php if ( function_exists('BWGEC') && $params['ecommerce_icon'] == 'show' ) { echo WDWLibrary::strip_tags($ecommerce_icon); } ?>
        <?php if ( $params['image_title'] == 'show' && $theme_row->thumb_title_pos == 'bottom' ) { echo WDWLibrary::strip_tags($title); } ?>
        </a>
        <?php if ( isset($params['show_thumb_description']) && $params['show_thumb_description'] ) { echo WDWLibrary::strip_tags($description); } ?>
      </div>
      <?php
      }
      ?>
    </div>
    <?php
    $content = ob_get_clean();

    if ( $ajax ) { /* Ajax response after ajax call for filters and pagination.*/
      parent::ajax_content($params, $bwg, $content);
    }
    else {
      parent::container($params, $bwg, $content);
    }
  }

  public function inline_styles($bwg, $theme_row, $params) {
    ob_start();
    $rgb_thumbs_bg_color = WDWLibrary::spider_hex2rgb($theme_row->thumbs_bg_color);
    ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails {
      width: <?php echo esc_html(($params['image_column_number'] * $params['thumb_width']) + ($theme_row->container_margin ? $theme_row->thumb_margin : 0)); ?>px;
      <?php
      if ( $theme_row->thumb_align == 'center' ) {
        ?>
        justify-content: center;
        margin:0 auto !important;
        <?php
      }
      elseif ( $theme_row->thumb_align == 'left') {
        ?>
        justify-content: flex-start;
        margin-right:auto;
        <?php
      }
      else {
        ?>
        justify-content: flex-end;
        margin-left:auto;
        <?php
      }
      ?>
      background-color: rgba(<?php echo esc_html($rgb_thumbs_bg_color['red']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['green']); ?>, <?php echo esc_html($rgb_thumbs_bg_color['blue']); ?>, <?php echo esc_html(number_format($theme_row->thumb_bg_transparent / 100, 2, ".", "")); ?>);
      <?php
      if ( $theme_row->container_margin ) {
        ?>
      padding-left: <?php echo esc_html($theme_row->thumb_margin); ?>px;
      padding-top: <?php echo esc_html($theme_row->thumb_margin); ?>px;
      max-width: 100%;
        <?php
      }
      else {
        ?>
      margin-right: -<?php echo esc_html($theme_row->thumb_margin); ?>px;
      max-width: calc(100% + <?php echo esc_html($theme_row->thumb_margin); ?>px);
        <?php
      }
      ?>
    }
    <?php
    if (!$theme_row->container_margin && $theme_row->thumb_margin) {
      ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-background-<?php echo esc_attr($bwg); ?> {
        overflow: hidden;
      }
      <?php
    }
    ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item {
    justify-content: <?php echo ($theme_row->thumb_title_pos == 'bottom' || (isset($params['show_thumb_description']) && $params['show_thumb_description'])) ? 'flex-start' : 'flex-end'; ?>;
      max-width: <?php echo esc_html($params['thumb_width']); ?>px;
      <?php if ( !BWG()->options->resizable_thumbnails ) { ?>
      width: <?php echo esc_html($params['thumb_width']); ?>px !important;
      <?php } ?>
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item a {
       margin-right: <?php echo esc_html($theme_row->thumb_margin); ?>px;
       margin-bottom: <?php echo esc_html($theme_row->thumb_margin); ?>px;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item0 {
      padding: <?php echo esc_html($theme_row->thumb_padding); ?>px;
      <?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->thumb_bg_color ); ?>
      background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', '.number_format($theme_row->thumb_bg_transparency / 100, 2, ".", "")); ?>);
      border: <?php echo esc_html($theme_row->thumb_border_width); ?>px <?php echo esc_html($theme_row->thumb_border_style); ?> #<?php echo esc_html($theme_row->thumb_border_color); ?>;
      opacity: <?php echo number_format($theme_row->thumb_transparent / 100, 2, ".", ""); ?>;
      border-radius: <?php echo esc_html($theme_row->thumb_border_radius); ?>;
      box-shadow: <?php echo esc_html($theme_row->thumb_box_shadow); ?>;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item1 img {
      max-height: none;
      max-width: none;
      padding: 0 !important;
    }
    <?php if ( $theme_row->thumb_hover_effect == 'zoom' ) { ?>
     @media only screen and (min-width: 480px) {
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item1 img {
			<?php echo ($theme_row->thumb_transition) ? '-webkit-transition: all .3s; transition: all .3s;' : ''; ?>
		}
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item1 img:hover {
			-ms-transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
			-webkit-transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
			transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
		}
		<?php if ( $params['image_title'] == 'hover' ) { ?>
		.bwg-standard-thumbnails .bwg-zoom-effect .bwg-zoom-effect-overlay {
			<?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->thumb_bg_color ); ?>
			background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', 0.3'); ?>);
		}
		.bwg-standard-thumbnails .bwg-zoom-effect:hover img {
			-ms-transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
			-webkit-transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
			transform: scale(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
		}
		<?php } ?>
      }
	<?php
    }
    else {
    ?>
    @media only screen and (min-width: 480px) {
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item0 {
        <?php echo ($theme_row->thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item0:hover {
        -ms-transform: <?php echo esc_html($theme_row->thumb_hover_effect); ?>(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
        -webkit-transform: <?php echo esc_html($theme_row->thumb_hover_effect); ?>(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
        transform: <?php echo esc_html($theme_row->thumb_hover_effect); ?>(<?php echo esc_html($theme_row->thumb_hover_effect_value); ?>);
      }
    }
      <?php
    }
    ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-item1 {
      padding-top: <?php echo esc_html($params['thumb_height'] / $params['thumb_width'] * 100); ?>%;
    }
    <?php
	  /* Show image title on hover.*/
    if ( $params['image_title'] == 'hover' ) { ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-title1 {
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
	  <?php
    }
    ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-title2,
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-ecommerce2 {
      color: #<?php echo esc_html(( $params['image_title'] == 'hover') ? (isset($theme_row->thumb_title_font_color_hover) ? $theme_row->thumb_title_font_color_hover : $theme_row->thumb_title_font_color) : $theme_row->thumb_title_font_color); ?>;
      font-family: <?php echo esc_html($theme_row->thumb_title_font_style); ?>;
      font-size: <?php echo esc_html($theme_row->thumb_title_font_size); ?>px;
      font-weight: <?php echo esc_html($theme_row->thumb_title_font_weight); ?>;
      padding: <?php echo esc_html($theme_row->thumb_title_margin); ?>;
      text-shadow: <?php echo esc_html($theme_row->thumb_title_shadow); ?>;
      max-height: 100%;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-thumb-description span {
    color: #<?php echo esc_html($theme_row->thumb_description_font_color); ?>;
    font-family: <?php echo esc_html($theme_row->thumb_description_font_style); ?>;
    font-size: <?php echo esc_html($theme_row->thumb_description_font_size); ?>px;
    max-height: 100%;
    word-wrap: break-word;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-play-icon2 {
      font-size: <?php echo esc_html(2 * $theme_row->thumb_title_font_size); ?>px;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-ecommerce2 {
      font-size: <?php echo esc_html(1.2 * $theme_row->thumb_title_font_size); ?>px;
      color: #<?php echo esc_html(( $params['ecommerce_icon'] == 'hover') ? (isset($theme_row->thumb_title_font_color_hover) ? $theme_row->thumb_title_font_color_hover : $theme_row->thumb_title_font_color) : $theme_row->thumb_title_font_color); ?>;
    }
    <?php
    if ( function_exists('BWGEC') && $params['ecommerce_icon'] == 'hover' ) { /* Show eCommerce icon on hover.*/
      ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-standard-thumbnails .bwg-ecommerce1 {
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
  }
}
