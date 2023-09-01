<?php
class BWGViewAlbum_masonry_preview extends BWGViewSite {

  private $gallery_view = FALSE;

  public function display($params = array(), $bwg = 0) {
    require_once BWG()->plugin_dir . '/frontend/views/BWGViewThumbnails_masonry.php';
    $view_class = 'BWGViewThumbnails_masonry';
    $this->gallery_view = new $view_class();
		$theme_row = $params['theme_row'];

		$from = (isset($params['from']) ? esc_html($params['from']) : 0);

    $breadcrumb = WDWLibrary::get('bwg_album_breadcrumb_' . $bwg);
    if ( !empty($breadcrumb) ) {
      $breadcrumb_arr = json_decode($breadcrumb);
      $params['breadcrumb_arr'] = array();
      // Validation json data.
      foreach ( $breadcrumb_arr as $key => $breadcrumb ) {
        $params['breadcrumb_arr'][$key]['id'] = intval($breadcrumb->id);
        $params['breadcrumb_arr'][$key]['page'] = intval($breadcrumb->page);
      }
    }
    else {
      $params['breadcrumb_arr'] = array(
        0 => array(
          'id' => $params['album_gallery_id'],
          'page' => WDWLibrary::get('page_number_' . $bwg, 1, 'intval'),
        ),
      );
    }
    $breadcrumb = json_encode($params['breadcrumb_arr']);

    /* Set theme parameters for Gallery/Gallery group title/description.*/
    $theme_row->thumb_gal_title_font_size = $theme_row->album_masonry_gal_title_font_size;
    $theme_row->thumb_gal_title_font_color = $theme_row->album_masonry_gal_title_font_color;
    $theme_row->thumb_gal_title_font_style = $theme_row->album_masonry_gal_title_font_style;
    $theme_row->thumb_gal_title_font_weight = $theme_row->album_masonry_gal_title_font_weight;
    $theme_row->thumb_gal_title_shadow = $theme_row->album_masonry_gal_title_shadow;
    $theme_row->thumb_gal_title_margin = $theme_row->album_masonry_gal_title_margin;
    $theme_row->thumb_gal_title_align = $theme_row->album_masonry_gal_title_align;

    $inline_style = $this->inline_styles($bwg, $theme_row, $params);
    $lazyload = BWG()->options->lazyload_images;

    if ( !WDWLibrary::elementor_is_active() ) {
      if ( !$params['ajax'] ) {
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
    if ( $params['album_view_type'] != 'gallery' ) {
    ?>
    <div data-bwg="<?php echo esc_attr($bwg); ?>"
         data-masonry-type="vertical"
         data-resizable-thumbnails="<?php echo esc_attr(BWG()->options->resizable_thumbnails); ?>"
         data-max-count="<?php echo esc_attr($params['masonry_album_column_number']); ?>"
         data-thumbnail-width="<?php echo esc_attr($params['masonry_album_thumb_width']); ?>"
         data-thumbnail-padding="<?php echo esc_attr($theme_row->album_masonry_thumb_padding); ?>"
         data-thumbnail-border="<?php echo esc_attr($theme_row->album_masonry_thumb_border_width); ?>"
         id="<?php echo esc_attr($params['container_id']); ?>"
         class="bwg-thumbnails bwg-masonry-album-thumbnails bwg-masonry-thumbnails bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> bwg-masonry-vertical bwg-container bwg-border-box bwg-container-<?php echo esc_attr($bwg); ?> bwg-album-thumbnails <?php echo esc_attr($params['album_gallery_div_class']); ?> <?php echo esc_attr($lazyload) ? 'lazy_loader' : ''; ?>">
          <?php
          if ( !$params['album_gallery_rows']['page_nav']['total'] ) {
            echo WDWLibrary::message(__('No results found.', 'photo-gallery'), 'wd_error');
          }
            foreach ($params['album_gallery_rows']['rows'] as $row) {
              $REQUEST_URI = isset($_SERVER['REQUEST_URI']) ? sanitize_url($_SERVER['REQUEST_URI']) : '';
              $href = esc_url( add_query_arg(array(
                                      "type_" . $bwg => $row->def_type,
                                      "album_gallery_id_" . $bwg => (($params['album_gallery_id'] != 0) ? $row->alb_gal_id : $row->id),
                                    ), $REQUEST_URI) );
              $href = $this->http_strip_query_param($href, 'bwg_search_' . $bwg);
              $href = $this->http_strip_query_param($href, 'page_number_' . $bwg);
              $title = '<div class="bwg-title1"><div class="bwg-title2">' . esc_html($row->name) . '</div></div>';
              $resolution_thumb = $row->resolution_thumb;
              $image_thumb_width = '';
              $image_thumb_height = '';
              if ( $resolution_thumb != "" && strpos($resolution_thumb, 'x') !== FALSE ) {
                $resolution_th = explode("x", $resolution_thumb);
                $image_thumb_width = $resolution_th[0];
                $image_thumb_height = $resolution_th[1];
              }
              $enable_seo = (int) BWG()->options->enable_seo;
              $enable_dynamic_url = (int) BWG()->options->front_ajax;
              ?>
              <div class="bwg-item">
                <a class="bwg-a <?php echo esc_html($from) !== "widget" ? 'bwg-album ' : ''; ?>bwg_album_<?php echo esc_attr($bwg); ?>"
                  <?php echo ( (esc_html($enable_seo) || esc_html($enable_dynamic_url)) && $from !== "widget" ? "href='" . esc_url($href) . "'" : ""); ?>
                  <?php echo esc_html($from) === "widget" ? 'href="' . esc_url($row->permalink). '"' : ''; ?>
                   data-container_id="<?php echo esc_attr($params['container_id']); ?>"
                   data-def_type="<?php echo esc_attr($row->def_type); ?>"
                   data-album_gallery_id="<?php echo esc_attr($params['album_gallery_id']); ?>"
                   data-alb_gal_id="<?php echo ((esc_html($params['album_gallery_id']) != 0) ? esc_attr($row->alb_gal_id) : esc_attr($row->id)); ?>"
                   data-title="<?php echo esc_attr($row->name); ?>"
                   data-bwg="<?php echo esc_attr($bwg); ?>">
                  <div class="bwg-item0">
                    <div class="bwg-item1 <?php echo esc_html($theme_row->album_masonry_thumb_hover_effect) == 'zoom' && $params['image_title'] == 'hover' ? 'bwg-zoom-effect' : ''; ?>">
                      <img class="skip-lazy bwg-masonry-thumb bwg_masonry_thumb_<?php echo esc_attr($bwg); ?> <?php if( $lazyload ) { ?> bwg_lazyload lazy_loader<?php } ?>"
                           src="<?php if( !$lazyload ) { echo esc_url($row->preview_image); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
                           data-src="<?php echo esc_url($row->preview_image); ?>"
                           data-width="<?php echo esc_attr($image_thumb_width); ?>"
                           data-height="<?php echo esc_attr($image_thumb_height); ?>"
                           alt="<?php echo esc_attr($row->name); ?>"
                           title="<?php echo esc_attr($row->name); ?>" />
                      <div class="<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect) == 'zoom' && esc_html($params['image_title']) == 'hover' ? 'bwg-zoom-effect-overlay' : ''; ?>">
                        <?php if ( $params['image_title'] == 'hover' && $row->name ) { echo esc_html($title); } ?>
                      </div>
                    </div>
                  </div>
                  <?php
                  if ( $params['image_title'] == 'show' && $row->name ) { echo WDWLibrary::strip_tags($title); }
                  if ( BWG()->options->show_masonry_thumb_description && $row->description) {
                    ?>
                    <div class="bwg-masonry-thumb-description bwg_masonry_thumb_description_<?php echo esc_attr($bwg); ?>">
                      <span><?php echo WDWLibrary::strip_tags(stripslashes($row->description)); ?></span>
                    </div>
                    <?php
                  }
                  ?>
                </a>
              </div>
              <?php
            }
          ?>
      </div>
      <?php
      }
      elseif ($params['album_view_type'] == 'gallery') {
        if ( $this->gallery_view && method_exists($this->gallery_view, 'display') ) {
          $this->gallery_view->display($params, $bwg, TRUE);
        }
      }
      ?>
    <input type="hidden" id="bwg_album_breadcrumb_<?php echo esc_attr($bwg); ?>" name="bwg_album_breadcrumb_<?php echo esc_attr($bwg); ?>" value='<?php echo esc_attr($breadcrumb); ?>' />
    <?php
    $content = ob_get_clean();

    if ( $params['ajax'] ) {/* Ajax response after ajax call for filters and pagination.*/
      if ( $params['album_view_type'] != 'gallery' ) {
        parent::ajax_content($params, $bwg, $content);
      }
      else {
        echo $content;
      }
    }
    else {
      parent::container($params, $bwg, $content);
    }
  }

  private function inline_styles($bwg, $theme_row, $params) {
    ob_start();
    ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #bwg_album_masonry_<?php echo esc_attr($bwg); ?>.bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> {
      width: <?php echo esc_html($params['masonry_album_column_number'] * $params['masonry_album_thumb_width'] + ($theme_row->album_masonry_container_margin ? $theme_row->album_masonry_thumb_padding : 0)); ?>px;
      max-width: 100%;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #bwg_album_masonry_<?php echo esc_attr($bwg); ?>.bwg-container-<?php echo esc_attr($bwg); ?> .bwg-item1 img {
      max-height: none;
      max-width: <?php echo esc_html($params['masonry_album_thumb_width']); ?>px;
    }
    @media only screen and (max-width: <?php echo esc_html($params['masonry_album_column_number'] * ($params['masonry_album_thumb_width'] + 2 * ($theme_row->album_masonry_thumb_padding + $theme_row->album_masonry_thumb_border_width))); ?>px) {
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #bwg_album_masonry_<?php echo esc_attr($bwg); ?>.bwg_masonry_thumbnails_<?php echo esc_attr($bwg); ?> {
        width: inherit;
      }
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #bwg_album_masonry_<?php echo esc_attr($bwg); ?> .bwg_masonry_thumb_<?php echo esc_attr($bwg); ?> {
      width: <?php echo BWG()->options->resizable_thumbnails ? '100% !important;' : esc_html($params['masonry_album_thumb_width']) . 'px'; ?>;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #bwg_album_masonry_<?php echo esc_attr($bwg); ?>.bwg-container-<?php echo esc_attr($bwg); ?> .bwg-item {
      max-width: <?php echo esc_html($params['masonry_album_thumb_width']); ?>px;
      <?php if ( !BWG()->options->resizable_thumbnails ) { ?>
      width: <?php echo esc_html($params['masonry_album_thumb_width']); ?>px !important;
      <?php } ?>
    }

    <?php if ( $theme_row->album_masonry_thumb_hover_effect == 'zoom' ) { ?>
      @media only screen and (min-width: 480px) {
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-album-thumbnails.bwg-masonry-thumbnails .bwg-item1 img {
      <?php echo ($theme_row->album_masonry_thumb_transition) ? '-webkit-transition: all .3s; transition: all .3s;' : ''; ?>
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-album-thumbnails.bwg-masonry-thumbnails .bwg-item1 img:hover {
      -ms-transform: scale(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
      -webkit-transform: scale(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
      transform: scale(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
      }
      <?php if ( $params['image_title'] == 'hover' ) { ?>
        .bwg-masonry-album-thumbnails.bwg-masonry-thumbnails .bwg-zoom-effect .bwg-zoom-effect-overlay {
        <?php $thumb_bg_color = WDWLibrary::spider_hex2rgb( $theme_row->album_masonry_thumb_bg_color ); ?>
        background-color:rgba(<?php echo esc_html($thumb_bg_color['red'] .','. $thumb_bg_color['green'] . ',' . $thumb_bg_color['blue'] . ', 0.3'); ?>);
        }
        .bwg-masonry-album-thumbnails.bwg-masonry-thumbnails .bwg-zoom-effect:hover img {
        -ms-transform: scale(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
        -webkit-transform: scale(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
        transform: scale(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
        }
      <?php } ?>
      }
      <?php
    }
    else {
      ?>
      @media only screen and (min-width: 480px) {
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-album-thumbnails.bwg-masonry-thumbnails .bwg-item0 {
      <?php echo ($theme_row->album_masonry_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg-container-<?php echo esc_attr($bwg); ?>.bwg-masonry-album-thumbnails.bwg-masonry-thumbnails .bwg-item0:hover {
      -ms-transform: <?php echo esc_html($theme_row->album_masonry_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
      -webkit-transform: <?php echo esc_html($theme_row->album_masonry_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
      transform: <?php echo esc_html($theme_row->album_masonry_thumb_hover_effect); ?>(<?php echo esc_html($theme_row->album_masonry_thumb_hover_effect_value); ?>);
      }
      }
      <?php
    }
    ?>
    <?php

    /* Add gallery styles, if gallery type exist.*/
    if ( $this->gallery_view && method_exists($this->gallery_view, 'inline_styles') ) {
      /* Set parameters for gallery view from album shortcode.*/
      $params['thumb_width'] = $params['masonry_album_image_thumb_width'];
      $params['image_column_number'] = $params['masonry_album_image_column_number'];
      $params['images_per_page'] = $params['masonry_album_images_per_page'];
      $params['image_enable_page'] = $params['masonry_album_enable_page'];
      $params['show_masonry_thumb_description'] = BWG()->options->show_masonry_thumb_description;
      $params['masonry_hor_ver'] = BWG()->options->masonry;

      /* Set theme parameters for gallery view.*/
      $theme_row->masonry_thumb_padding = $theme_row->album_masonry_thumb_padding;
      $theme_row->masonry_container_margin = $theme_row->album_masonry_container_margin;
      $theme_row->masonry_thumb_border_width = $theme_row->album_masonry_thumb_border_width;
      $theme_row->masonry_thumb_border_style = $theme_row->album_masonry_thumb_border_style;
      $theme_row->masonry_thumb_border_color = $theme_row->album_masonry_thumb_border_color;
      $theme_row->masonry_thumb_border_radius = $theme_row->album_masonry_thumb_border_radius;

      $theme_row->masonry_thumb_bg_color = $theme_row->album_masonry_thumb_bg_color;
      $theme_row->masonry_thumb_transparent = $theme_row->album_masonry_thumb_transparent;
      $theme_row->masonry_thumbs_bg_color = $theme_row->album_masonry_thumbs_bg_color;
      $theme_row->masonry_thumb_bg_transparent = $theme_row->album_masonry_thumb_bg_transparent;
      $theme_row->masonry_thumb_align = $theme_row->album_masonry_thumb_align;

      $theme_row->masonry_thumb_title_font_size = $theme_row->album_masonry_title_font_size;
      $theme_row->masonry_thumb_title_font_color = $theme_row->album_masonry_title_font_color;
      $theme_row->masonry_thumb_title_font_color_hover = $theme_row->album_masonry_thumb_title_font_color_hover;
      $theme_row->masonry_thumb_title_font_style = $theme_row->album_masonry_title_font_style;
      $theme_row->masonry_thumb_title_font_weight = $theme_row->album_masonry_title_font_weight;
      $theme_row->masonry_thumb_gal_title_font_size = $theme_row->album_masonry_gal_title_font_size;
      $theme_row->masonry_thumb_gal_title_font_color = $theme_row->album_masonry_gal_title_font_color;
      $theme_row->masonry_thumb_gal_title_font_style = $theme_row->album_masonry_gal_title_font_style;
      $theme_row->masonry_thumb_gal_title_font_weight = $theme_row->album_masonry_gal_title_font_weight;
      $theme_row->masonry_thumb_gal_title_shadow = $theme_row->album_masonry_gal_title_shadow;
      $theme_row->masonry_thumb_gal_title_margin = $theme_row->album_masonry_gal_title_margin;
      $theme_row->masonry_thumb_gal_title_align = $theme_row->album_masonry_gal_title_align;

      echo $this->gallery_view->inline_styles($bwg, $theme_row, $params);
    }

    return ob_get_clean();
  }
}
