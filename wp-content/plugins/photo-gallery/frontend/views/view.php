<?php
class BWGViewSite {

  public function container($params = array(), $bwg = 0, $content = '') {
    if ( $params['thumb_click_action'] == 'open_lightbox' ) {
      // Checking image type is EMBED_OEMBED_INSTAGRAM_POST.
      $embed_instagram_post = FALSE;
      if ( !empty($params['image_rows']['images']) ) {
        $image_filetypes = array_column($params['image_rows']['images'], 'filetype');
        if ( in_array('EMBED_OEMBED_INSTAGRAM_POST',$image_filetypes) ) {
          $embed_instagram_post = TRUE;
        }
      }
      if ( $embed_instagram_post || ( isset($params['gallery_row']->gallery_type)
        && in_array($params['gallery_row']->gallery_type, array( 'instagram', 'instagram_post' )) ) ) {
        if ( !wp_script_is('instagram-embed', 'done') ) {
          wp_register_script( 'instagram-embed', 'https://www.instagram.com/embed.js' );
          wp_print_scripts( 'instagram-embed' );
        }

      }
    }
    if ( !WDWLibrary::elementor_is_active() && BWG()->options->use_inline_stiles_and_scripts ) {
      wp_enqueue_style(BWG()->prefix . '_frontend');
      if ( (isset($params['show_tag_box']) && $params['show_tag_box'])
        || (isset($params['show_sort_images']) && $params['show_sort_images'])
        || $params['album_view_type'] == 'album' ) {
        if ( !wp_script_is('sumoselect', 'done') ) {
          wp_print_scripts('sumoselect');
        }
      }
      if ( $params['thumb_click_action'] == 'open_lightbox' ) {
        if ( $params['popup_enable_comment'] ) {
          if ( !wp_script_is('mCustomScrollbar', 'done') ) {
            wp_print_scripts('mCustomScrollbar');
          }
        }
        if ( $params['popup_enable_fullscreen'] ) {
          if ( !wp_script_is('jquery-fullscreen', 'done') ) {
            wp_print_scripts('jquery-fullscreen');
          }
        }
        if ( !wp_script_is('bwg_gallery_box', 'done') ) {
          wp_print_scripts('bwg_gallery_box');
        }
        if ( $params['popup_enable_rate'] ) {
          if ( !wp_script_is('bwg_raty', 'done') ) {
            wp_print_scripts('bwg_raty');
          }
        }
      }
      if ( !wp_script_is('bwg_mobile', 'done') ) {
        wp_print_scripts('bwg_mobile');
      }
      if ( !wp_script_is('bwg_frontend', 'done') ) {
        wp_print_scripts('bwg_frontend');
      }
    }

    $params_array = $params['params_array'];
    $theme_row = $params['theme_row'];
    if (function_exists('amp_is_request') && amp_is_request()) {
        echo
        '<style>
            amp-img {
              height: 100%!important;
            }
            #bwg_container1_' . esc_attr($bwg) . ' #bwg_container2_' . esc_attr($bwg) . ' .bwg-container-' . esc_attr($bwg) . '.bwg-standard-thumbnails .bwg-item1 {
                padding-top: 66%;
            }
            .bwg-container-' . esc_attr($bwg) . ' {
            width: 100%!important;
            }
            @media screen and (max-width: 485px) {
            .bwg-item {
                                width:100%;
                            }
            }
            @media screen and (max-width: 768px) and (min-width: 486px) {
            .bwg-item {
                                width:50%;
                            }
            }
            @media screen and (min-width: 769px) {
            .bwg-item {
                                width:33.3%;
                            }
            }
        </style>';
    }
    $params['gallery_view_type'] = isset($params['gallery_view_type']) ? $params['gallery_view_type'] : $params['gallery_type'];
    ?>

    <div id="bwg_container1_<?php echo esc_attr($bwg); ?>"
         class="bwg_container bwg_thumbnail bwg_<?php echo esc_attr($params['gallery_type']); ?> <?php echo $params['gallery_type'] != $params['gallery_view_type'] ? 'bwg_'.esc_attr($params['gallery_view_type']) : ''; ?>"
         data-right-click-protection="<?php echo esc_attr(BWG()->options->image_right_click); ?>"
         data-bwg="<?php echo esc_attr($bwg); ?>"
         data-scroll="<?php echo esc_attr($params['need_scroll']); ?>"
         data-gallery-type="<?php echo esc_attr($params['gallery_type']); ?>"
         data-gallery-view-type="<?php echo esc_attr($params['gallery_view_type']); ?>"
         data-current-url="<?php echo esc_url(addslashes(urldecode($params_array['current_url']))); ?>"
         data-lightbox-url="<?php echo esc_url(addslashes(add_query_arg($params_array, admin_url('admin-ajax.php')))); ?>"
         data-gallery-id="<?php echo esc_attr($params_array['gallery_id']); ?>"
         data-popup-width="<?php echo esc_attr($params["popup_width"]); ?>"
         data-popup-height="<?php echo esc_attr($params["popup_height"]); ?>"
         data-is-album="<?php echo esc_attr($params["view_type"]); ?>"
         data-buttons-position="<?php echo esc_attr($theme_row->lightbox_ctrl_btn_pos); ?>">
      <div id="bwg_container2_<?php echo esc_attr($bwg); ?>">
         <?php (function_exists('amp_is_request') && amp_is_request()) ? :$this->loading($bwg, $params["image_enable_page"], $params['gallery_type'] ); ?>
        <form id="gal_front_form_<?php echo esc_attr($bwg); ?>"
              <?php echo (function_exists('amp_is_request') && amp_is_request()) ? : 'class="bwg-hidden"' ?>
              method="post"
              action="#"
              data-current="<?php echo esc_attr($bwg); ?>"
              data-shortcode-id="<?php echo isset($params['id']) ? esc_attr($params['id']) : 0; ?>"
              data-gallery-type="<?php echo esc_attr($params['gallery_type']); ?>"
              data-gallery-id="<?php echo esc_attr($params['gallery_id']); ?>"
              data-tag="<?php echo esc_attr($params['tag']); ?>"
              data-album-id="<?php echo esc_attr($params['album_id']); ?>"
              data-theme-id="<?php echo esc_attr($params['theme_id']); ?>"
              data-ajax-url="<?php echo esc_url(add_query_arg(array('action' => 'bwg_frontend_data'), admin_url('admin-ajax.php'))); ?>">
          <div id="bwg_container3_<?php echo esc_attr($bwg); ?>" class="bwg-background bwg-background-<?php echo esc_attr($bwg); ?>">
            <?php
            $get_album_gallery_id = WDWLibrary::get("album_gallery_id_" . $bwg, 0, 'intval');
            if ( BWG()->options->front_ajax == "1" && isset($get_album_gallery_id) && intval($get_album_gallery_id) > 0 ) {
              $this->back($params, $bwg);
            }
            if ( BWG()->options->front_ajax == "1" || !$get_album_gallery_id ) {
              $this->title_description($params, $bwg);
            }
            if ( (!isset($params['from']) || $params['from'] !== 'widget')
              && !(function_exists('amp_is_request') && amp_is_request())
              && ((isset($params['show_sort_images']) && $params['show_sort_images'])
              || (isset($params['show_tag_box']) && $params['show_tag_box'])
              || (isset($params['show_search_box']) && $params['show_search_box'])) )
            {
							/* Used when Tag Filter is Select View */
							if ( $theme_row->tags_view == 1 ) { ?>
								<div class="search_line bwg_tag_select_view">
								 <?php
								 $this->ajax_html_frontend_sort_box($params, $bwg);
								 $this->ajax_html_frontend_search_tags($params, $bwg);
								 $this->ajax_html_frontend_search_box($params, $bwg);
								 ?>
								</div>
							<?php
							}
							/* Used when Tag Filter is Button View */
							else { ?>
								<div class="search_line bwg_tag_buttons_view">
								 <?php
								 $this->ajax_html_frontend_sort_box($params, $bwg);
								 $this->ajax_html_frontend_search_box($params, $bwg);
								 $this->ajax_html_frontend_search_tags($params, $bwg);
								 ?>
								</div>
							<?php
							}
            }
            if ( isset($params['image_rows']) && !count($params['image_rows']['images']) ) {
              if ( $params['tag'] ) {
                echo WDWLibrary::message(esc_html__('There are no images.', 'photo-gallery'), 'wd_error');
              }
              else {
                echo WDWLibrary::message(esc_html__('No Images found.', 'photo-gallery'), 'wd_error');
              }
            }
            if ( $params['album_view_type'] == 'album' || isset($params['image_rows']) && count($params['image_rows']['images']) ) {
              $pagination_style = $params['gallery_type'] == 'image_browser' ? 'image_browser' : 'simple';
              $this->ajax_html_frontend_page_nav($theme_row, 'top', $params, $bwg, $pagination_style);
              echo $content;
              $this->ajax_html_frontend_page_nav($theme_row, 'bottom', $params, $bwg, $pagination_style);
              $this->download_button($params, $bwg);
            }
            ?>
          </div>
        </form>
        <?php
         
        if ( $params['thumb_click_action'] == 'open_lightbox' ) {
          ob_start();
          ?>
          #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #spider_popup_overlay_<?php echo esc_attr($bwg); ?> {
          background-color: #<?php echo esc_html($theme_row->lightbox_overlay_bg_color); ?>;
          opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
          }
          <?php
          $inline_style = ob_get_clean();

          if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() ) {
            wp_add_inline_style( 'bwg_frontend', $inline_style );
          }
          else {
            echo wp_kses('<style>' . $inline_style . '</style>', array('style' => array()));
          }
          ?>
        <div id="bwg_spider_popup_loading_<?php echo esc_attr($bwg); ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo esc_attr($bwg); ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
        <input type="hidden" id="bwg_random_seed_<?php echo esc_attr($bwg); ?>" value="<?php echo isset($GLOBALS['bwg_random_seed_' . $bwg]) ? esc_attr($GLOBALS['bwg_random_seed_' . $bwg]) : '' ?>">
          <?php
        }
        ?>
      </div>
    </div>
    <script>
      if (document.readyState === 'complete') {
        if( typeof bwg_main_ready == 'function' ) {
          if ( jQuery("#bwg_container1_<?php echo esc_attr($bwg); ?>").height() ) {
            bwg_main_ready(jQuery("#bwg_container1_<?php echo esc_attr($bwg); ?>"));
          }
        }
      } else {
        document.addEventListener('DOMContentLoaded', function() {
          if( typeof bwg_main_ready == 'function' ) {
            if ( jQuery("#bwg_container1_<?php echo esc_attr($bwg); ?>").height() ) {
             bwg_main_ready(jQuery("#bwg_container1_<?php echo esc_attr($bwg); ?>"));
            }
          }
        });
      }
    </script>
    <?php
  }

  public function ajax_content($params, $bwg, $content) {
    $theme_row = $params['theme_row'];
	  if ( isset($params['breadcrumb_arr']) && count($params['breadcrumb_arr']) > 1 ) { /* If not first album.*/
      $this->back($params, $bwg);
    }
    if ( BWG()->options->front_ajax != "1" ) {
      $this->title_description($params, $bwg);
      if((!isset($params['from']) || $params['from'] !== 'widget')
        && ((isset($params['show_sort_images']) && $params['show_sort_images'])
          || (isset($params['show_tag_box']) && $params['show_tag_box'])
          || (isset($params['show_search_box']) && $params['show_search_box'])))
      {
        /* Used when Tag Filter is Select View */
				if ( $theme_row->tags_view == 1 ) { ?>
					<div class="search_line bwg_tag_select_view">
					 <?php
					 $this->ajax_html_frontend_sort_box($params, $bwg);
					 $this->ajax_html_frontend_search_tags($params, $bwg);
					 $this->ajax_html_frontend_search_box($params, $bwg);
					 ?>
					</div>
				<?php
				}
				/* Used when Tag Filter is Button View */
				else { ?>
					<div class="search_line bwg_tag_buttons_view">
					 <?php
             $this->ajax_html_frontend_sort_box($params, $bwg);
             $this->ajax_html_frontend_search_box($params, $bwg);
             $this->ajax_html_frontend_search_tags($params, $bwg);
					 ?>
					</div>
				<?php
				}
      }
    }

    if ( isset($params['image_rows']) && !count($params['image_rows']['images']) ) {
      if ( $params['tag'] ) {
        echo WDWLibrary::message(esc_html__('There are no images.', 'photo-gallery'), 'wd_error');
      }
      else {
        echo WDWLibrary::message(esc_html__('No Images found.', 'photo-gallery'), 'wd_error');
      }
    }

    $compuct_album_enable_page = "";
    if (isset($params["compuct_album_enable_page"])) {
      $compuct_album_enable_page = $params["compuct_album_enable_page"];
    }
    $enable_page = "";
    if (isset($params["image_enable_page"])) {
      $enable_page = $params["image_enable_page"];
    }
    if ( BWG()->options->front_ajax == "1" && (($compuct_album_enable_page !== "2" && $compuct_album_enable_page !== "3") && ($enable_page !== "2" && $enable_page !== "3")) ) {
      if ( $params['album_view_type'] == 'album' || isset($params['image_rows']) && count($params['image_rows']['images']) ) {
        echo $content;
      }
    }
    else {
      if ($params['album_view_type'] == 'album' || isset($params['image_rows']) && count($params['image_rows']['images'])) {
        $pagination_style = $params['gallery_type'] == 'image_browser' ? 'image_browser' : 'simple';
        $this->ajax_html_frontend_page_nav($params['theme_row'], 'top', $params, $bwg, $pagination_style);
        echo $content;
        $this->ajax_html_frontend_page_nav($params['theme_row'], 'bottom', $params, $bwg, $pagination_style);
        $this->download_button($params, $bwg);
      }
    }
  }

  public function loading($bwg = 0, $image_enable_page = 0, $gallery_type = '' ) {
    ?>
    <div id="ajax_loading_<?php echo esc_attr($bwg); ?>" class="bwg_loading_div_1">
      <div class="bwg_loading_div_2">
        <div class="bwg_loading_div_3">
          <div id="loading_div_<?php echo esc_attr($bwg); ?>" class="bwg_spider_ajax_loading">
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public function back($params, $bwg) {
    $theme_row = $params['theme_row'];
    ob_start();
    ?>
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_back_<?php echo esc_attr($bwg); ?> {
      display: inline-block;
      background-color: rgba(0, 0, 0, 0);
      margin-bottom: 20px;
      padding: <?php echo esc_html($theme_row->back_padding); ?>;
      line-height: <?php echo esc_html($theme_row->back_font_size); ?>px;
      text-align: left;
      font-family: <?php echo esc_html($theme_row->back_font_style); ?>;
      font-weight: <?php echo esc_html($theme_row->back_font_weight); ?>;
      font-size: <?php echo esc_html($theme_row->back_font_size); ?>px;
      color: #<?php echo esc_html($theme_row->back_font_color); ?>;
      cursor: pointer;
    }
    <?php
    $inline_style = ob_get_clean();
    if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
      wp_add_inline_style('bwg_frontend', $inline_style);
    }
    else {
      echo wp_kses('<style>' . $inline_style . '</style>', array('style' => array()));
    }
    ?>
    <div class="bwg_back bwg_back_<?php echo esc_attr($bwg); ?>" onclick="bwg_ajax('gal_front_form_<?php echo esc_attr($bwg); ?>', '<?php echo esc_attr($bwg); ?>', '<?php echo esc_html($params['container_id']); ?>', 'back', '', 'album')"><i class="bwg-icon-arrow-left"></i> <?php _e('Back', 'photo-gallery'); ?></div>
    <?php
  }

  public function title_description($params, $bwg) {
    $row = $params['album_view_type'] == 'album' ? $params['album_row'] : $params['gallery_row'];
    if ( is_object($row) && isset($params['showthumbs_name']) && isset($params['show_gallery_description']) ) {
      if ( (($params['showthumbs_name'] && isset($row->name) && $row->name != '')
        || ($params['show_gallery_description'] && isset($row->description) && $row->description != '')) ) {
        $theme_row = $params['theme_row'];
        ob_start();
        ?>
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_gal_title_<?php echo esc_attr($bwg); ?> {
          display: block;
          padding: <?php echo esc_html($theme_row->thumb_gal_title_margin); ?>;
          background-color: rgba(0, 0, 0, 0);
          line-height: 20px;
          font-family: <?php echo esc_html($theme_row->thumb_gal_title_font_style); ?>;
          font-size: <?php echo esc_html($theme_row->thumb_gal_title_font_size); ?>px;
          font-weight: <?php echo esc_html($theme_row->thumb_gal_title_font_weight); ?>;
          color: #<?php echo esc_html($theme_row->thumb_gal_title_font_color); ?>;
          text-shadow: <?php echo esc_html($theme_row->thumb_gal_title_shadow); ?>;
          text-align: <?php echo esc_html($theme_row->thumb_gal_title_align); ?>;
        }
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_gal_description_<?php echo esc_attr($bwg); ?> {
          margin: 20px 0;
          word-break: break-word;
          text-align: justify;
          font-size: <?php echo esc_html($theme_row->thumb_gal_title_font_size - 3); ?>px;
          font-weight: unset;
        }
        <?php
        $inline_style = ob_get_clean();
        if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
          wp_add_inline_style('bwg_frontend', $inline_style);
        }
        else {
          echo wp_kses('<style>' . $inline_style . '</style>', array('style' => array()));
        }
      }
      if ( $params['showthumbs_name'] && isset($row->name) && $row->name != '' ) {
        ?>
        <div class="bwg_gal_title_<?php echo esc_attr($bwg); ?>"><?php echo WDWLibrary::strip_tags(stripslashes($row->name)); ?></div>
        <?php
      }
      if ( $params['show_gallery_description'] && isset($row->description) && $row->description != '' ) {
        ?>
        <div class="bwg_gal_title_<?php echo esc_attr($bwg); ?> bwg_gal_description_<?php echo esc_attr($bwg); ?>"><?php echo WDWLibrary::strip_tags(stripslashes($row->description)); ?></div>
        <?php
      }
    }
  }

  /**
   * @param $params
   * @param $bwg
   */
  public function download_button($params, $bwg) {
    if ( isset($params['gallery_download'])
      && $params['gallery_download']
      && ($params['gallery_row']->id == 0 || ($params['gallery_row']->gallery_type != 'facebook' && $params['gallery_row']->gallery_type != 'instagram' && $params['gallery_row']->gallery_type != 'instagram_post')) ) {
      if ( count($params['image_rows']['images']) ) {
        $bwg_tags_input_value = WDWLibrary::get('bwg_tag_id_'.$params['container_id']);
        $bwg_download_nonce = wp_create_nonce( 'bwg-download-nonce' );
        $query_url = addslashes(add_query_arg(array(
                                                "action" => "download_gallery",
                                                "_wpnonce" => esc_attr( $bwg_download_nonce ),
                                                "gallery_id" => $params['gallery_id'],
                                                "bwg" => $bwg,
                                                "type" => 'gallery',
                                                "tag_input_name" => 'bwg_tag_id_'.$params['container_id'],
                                                'bwg_tag_id_'.$params['container_id'] => $bwg_tags_input_value,
                                                "tag" => $params['tag'],
                                                "bwg_search_" . $bwg => WDWLibrary::get('bwg_search_' . $bwg),
                                              ), admin_url('admin-ajax.php')));
        ?>
        <div class="bwg_download_gallery">
          <a href="<?php echo esc_url($query_url); ?>">
            <i title="<?php _e('Download gallery', 'photo-gallery'); ?>" class="bwg-icon-download bwg_ctrl_btn"></i>
          </a>
        </div>
        <?php
      }
    }
  }

  public function ajax_html_frontend_search_box($params, $bwg) {
    if ( isset($params['show_search_box']) && $params['show_search_box'] ) {
      $form_id = 'gal_front_form_' . $bwg;
      $current_view = $bwg;
      $cur_gal_id = $params['container_id'];
      $images_count = count(($params['album_view_type'] != 'album' ? $params['image_rows']['images'] : $params['album_gallery_rows']['rows']));
      $search_box_width = $params['search_box_width'];
      $placeholder = $params['placeholder'];
      $bwg_search = WDWLibrary::get('bwg_search_' . $current_view);
      $type = WDWLibrary::get('type_' . $current_view, 'album');
      $type = ($type != 'album') ? 'gallery' : 'album';
      if ( $type == 'album' ) {
       $bwg_search = WDWLibrary::get('bwg_album_search_' . $current_view);
      }
      if ( BWG()->options->front_ajax == "1" ) {
        $bwg_search = WDWLibrary::get('bwg_search_' . $current_view);
      }
      $album_gallery_id =  WDWLibrary::get('album_gallery_id_' . $current_view, $params['album_gallery_id'], 'intval');
      ob_start();
      ?>
      #bwg_search_container_1_<?php echo esc_attr($current_view); ?> {
				max-width: <?php echo esc_html($search_box_width); ?>px;
      }
      <?php
      $inline_style = ob_get_clean();
      if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
      }
      else {
          echo wp_kses('<style>' . $inline_style . '</style>', array('style' => array()));
      }
      ?>
      <?php
      $bwg_search_focus = "";
      $bwg_search_reset = "bwg-hidden";
      $bwg_ajax_reset = "bwg_ajax('".$form_id."', '".$current_view."', '".$cur_gal_id."', ". $album_gallery_id.", '', '".$type."', 1)";
      if (BWG()->options->front_ajax == "1" && !empty($bwg_search)) {
        $bwg_search_focus = 'style="display:none;"';
        $bwg_search_reset = "";
        $bwg_ajax_reset = "";
      }
      ?>
      <div class="bwg_search_container_1" id="bwg_search_container_1_<?php echo esc_attr($current_view); ?>">
        <div class="bwg_search_container_2" id="bwg_search_container_2_<?php echo esc_attr($current_view); ?>">
        <span class="bwg_search_input_container">
          <span class="bwg_search_loupe_container1 bwg-hidden">
             <i title="<?php echo esc_html__('SEARCH...', 'photo-gallery'); ?>" class="bwg-icon-search bwg_search" onclick="bwg_ajax('<?php echo esc_attr($form_id); ?>', '<?php echo esc_attr($current_view); ?>', '<?php echo esc_html($cur_gal_id); ?>', <?php echo esc_html($album_gallery_id); ?>, '', '<?php echo esc_html($type); ?>', 1)"></i>
          </span>
          <input id="bwg_search_input_<?php echo esc_attr($current_view); ?>" class="bwg_search_input" type="search" onkeypress="bwg_key_press(this); return bwg_check_search_input_enter(this, event);" name="bwg_search_<?php echo esc_attr($current_view); ?>" value="<?php echo esc_attr($bwg_search); ?>" placeholder="<?php echo esc_attr($placeholder); ?>" />
          <span class="bwg_search_reset_container <?php echo esc_attr($bwg_search_reset); ?>">
          <i title="<?php echo esc_html__('Reset', 'photo-gallery'); ?>" class="bwg-icon-times bwg_reset" onclick="bwg_clear_search_input('<?php echo esc_attr($current_view); ?>'); <?php echo esc_html($bwg_ajax_reset);?> "></i>
        </span>
          <input id="bwg_images_count_<?php echo esc_attr($current_view); ?>" class="bwg_search_input" type="hidden" name="bwg_images_count_<?php echo esc_attr($current_view); ?>" value="<?php echo esc_attr($images_count); ?>">
            <span class="search_placeholder_title" onclick="bwg_search_focus(this)" <?php echo esc_html($bwg_search_focus); ?>>
                <span class="bwg_search_loupe_container">
                  <i title="<?php echo esc_html__('SEARCH...', 'photo-gallery'); ?>" class="bwg-icon-search bwg_search"></i>
                </span>
                <span style="font-size: 12px; font-family: Ubuntu;"><?php echo esc_html($placeholder); ?></span>
          </span>
        </span>
        </div>
      </div>
      <?php
    }
  }

  public function ajax_html_frontend_sort_box($params, $bwg) {
    if ( isset($params['show_sort_images']) && $params['show_sort_images'] ) {
      $form_id = 'gal_front_form_' . $bwg;
      $current_view = $bwg;
      $cur_gal_id = $params['container_id'];
      $sort_by = $params['sort_by'];
      $search_box_width = $params['search_box_width'];
      $type = WDWLibrary::get('type_' . $current_view, 'album');
      $type = ($type != 'album') ? 'gallery' : 'album';
      $album_gallery_id = WDWLibrary::get('album_gallery_id_' . $current_view, 0, 'intval');
      if ( BWG()->options->front_ajax == "1" ) {
        $sort_by = WDWLibrary::get( 'sort_by_'. $bwg, $sort_by );
      }
      ob_start();
      ?>
      #bwg_order_<?php echo esc_attr($current_view); ?> {
      width: <?php echo esc_html($search_box_width); ?>px;
      }
      <?php
      $inline_style = ob_get_clean();
      if ( BWG()->options->use_inline_stiles_and_scripts ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
      }
      ?>
      <div class="bwg_order_cont">
        <select id="bwg_order_<?php echo esc_attr($current_view); ?>" class="bwg_order bwg_order_<?php echo esc_attr($current_view); ?>" onchange="bwg_ajax('<?php echo esc_html($form_id); ?>', '<?php echo esc_html($current_view); ?>', '<?php echo esc_html($cur_gal_id); ?>', <?php echo esc_html($album_gallery_id); ?>, '', '<?php echo esc_html($type); ?>', 1, '', this.value)">
          <option <?php if ( $sort_by == 'default' ) {
            echo 'selected';
          } ?> value="default"><?php echo esc_html__('Order by Default', 'photo-gallery'); ?></option>
          <option <?php if ( $sort_by == 'alt' ) {
            echo 'selected';
          } ?> value="alt"><?php echo esc_html__('Title', 'photo-gallery'); ?></option>
          <option <?php if ( $sort_by == 'date' ) {
            echo 'selected';
          } ?> value="date"><?php echo esc_html__('Date', 'photo-gallery'); ?></option>
          <option <?php if ( $sort_by == 'filename' ) {
            echo 'selected';
          } ?> value="filename"><?php echo esc_html__('Filename', 'photo-gallery'); ?></option>
          <option <?php if ( $sort_by == 'size' ) {
            echo 'selected';
          } ?> value="size"><?php echo esc_html__('Size', 'photo-gallery'); ?></option>
          <option <?php if ( $sort_by == 'random' || $sort_by == 'RAND()' ) {
            echo 'selected';
          } ?> value="random"><?php echo esc_html__('Random', 'photo-gallery'); ?></option>
        </select>
      </div>
      <?php
    }
  }

  public function ajax_html_frontend_search_tags($params, $bwg) {
    if ( isset($params['show_tag_box']) && $params['show_tag_box'] ) {
    	$theme_row = $params['theme_row'];
    	$form_id = 'gal_front_form_' . $bwg;
      $current_view = $bwg;
      $cur_gal_id = $params['container_id'];
      $tags_rows = $params['tags_rows'];
      $type = WDWLibrary::get('type_' . $current_view, 'album');
      $type = ($type != 'album') ? 'gallery' : 'album';
      $bwg_search_tags = WDWLibrary::get('bwg_tag_id_' . $cur_gal_id, array());
      $get_filter_teg = WDWLibrary::get('filter_tag_' . $bwg);
      if ( BWG()->options->front_ajax == "1" && isset($get_filter_teg) && !empty($get_filter_teg) ) {
        $filter_teg_arr = array_map('intval', explode(",", trim($get_filter_teg)));
        $bwg_search_tags = $filter_teg_arr;
      } ob_start();
      $album_gallery_id = WDWLibrary::get('album_gallery_id_' . $current_view, 0, 'intval');
      $show_bottom_tag = 0;
      ?>
			/* Tags styles */
			<?php if ( ( $params['show_sort_images'] || $params['show_search_box'] ) && ( $params['show_tag_box'] && $theme_row->tags_view == 0 ) ) { ?>
				#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_search_container_1,
				#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_order_cont {
					margin-bottom: 20px;
				}
			<?php } ?>
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_view_container .verLine {
				border-left: 1px solid #<?php echo esc_html($theme_row->tags_but_border_color); ?>;
			}
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_view_container button {
				padding: <?php echo esc_html($theme_row->tags_but_padding); ?>;
				margin: <?php echo esc_html($theme_row->tags_but_margin); ?>;
				font-size: <?php echo esc_html($theme_row->tags_but_font_size); ?>px;
				color: #<?php echo esc_html($theme_row->tags_but_font_color); ?>;
				font-family: <?php echo esc_html($theme_row->tags_but_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->tags_but_font_weight); ?>;
				background-color: #<?php echo esc_html($theme_row->tags_but_bg_color); ?>;
				border-radius: <?php echo esc_html($theme_row->tags_but_border_radius); ?>;
				border: <?php echo esc_html($theme_row->tags_but_border_width); ?>px <?php echo esc_html($theme_row->tags_but_border_style); ?> #<?php echo esc_html($theme_row->tags_but_border_color); ?>;
			}
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_view_container button.active {
				background-color: #<?php echo esc_html($theme_row->tags_but_active_bg_color); ?>;
			  color: #<?php echo esc_html($theme_row->tags_but_active_color); ?>;
			}
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_see_all {
				border-top: 1px solid #<?php echo esc_html($theme_row->tags_but_border_color); ?>;
			}
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_see_all .see_all_tags {
				border: <?php echo esc_html($theme_row->tags_but_see_all_border_w); ?>px <?php echo esc_html($theme_row->tags_but_see_all_border_s); ?> #<?php echo esc_html($theme_row->tags_but_see_all_border_c); ?>;
				border-radius: <?php echo esc_html($theme_row->tags_but_see_all_border_r); ?>;
				background-color: #<?php echo esc_html($theme_row->tags_but_see_all_back_color); ?>;
				font-size: <?php echo esc_html($theme_row->tags_but_see_all_size); ?>px;
				color: #<?php echo esc_html($theme_row->tags_but_see_all_color); ?>;
			}
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_see_all .see_all_tags span {
				font-size: <?php echo esc_html($theme_row->tags_but_see_all_size); ?>px;
				font-weight: <?php echo esc_html($theme_row->tags_but_font_weight); ?>;
				font-family: <?php echo esc_html($theme_row->tags_but_font_style); ?>;
				background-color: #<?php echo esc_html($theme_row->tags_but_see_all_color); ?>;
			}
			@media only screen and (max-width : 480px) {
				#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .bwg_tag_button_see_all .see_all_tags {
					font-size: calc(<?php echo esc_html($theme_row->tags_but_see_all_size); ?>px - 3px);
					 padding: 10px 15px;
    left: 42%;
				}
			}
			<?php
			$inline_style = ob_get_clean();
			if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
				wp_add_inline_style('bwg_frontend', $inline_style);
			}
			else {
          echo wp_kses('<style>' . $inline_style . '</style>', array('style' => array()));
			}
      if ( $theme_row->tags_view == 1 ) { ?>
        <div class="bwg_tags_container_<?php echo esc_attr($current_view); ?>" >
          <div class="search_tags_container">
            <select class="search_tags" id="bwg_tag_id_<?php echo esc_attr($cur_gal_id); ?>" multiple="multiple">
             <?php
             foreach ( $tags_rows as $tags_row ) {
                $selected = ( !empty($bwg_search_tags) && in_array($tags_row->term_id ? $tags_row->term_id : '', $bwg_search_tags)) ? 'selected="selected"' : ''; ?>
                <option value="<?php echo esc_attr($tags_row->term_id) ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($tags_row->name) ?></option>
              <?php
             }
             ?>
            </select>
          </div>
      <?php
      }
      else { ?>
        <div class="bwg_tags_container_<?php echo esc_attr($current_view); ?>">
          <div class="bwg_tag_button_view_container" >
             <button type="button" class="bwg_all_tags active" data-filter="All Tags"><?php echo esc_html__('All Tags', 'photo-gallery');?></button><div class="verLine"></div>
             <?php
             foreach ( $tags_rows as $tags_row ) {
                $active = ( !empty($bwg_search_tags) && in_array($tags_row->term_id ? $tags_row->term_id : '', $bwg_search_tags)) ? 'active' : '';
              ?>
               <button type="button"  class="bwg_tags_filter_buttons <?php echo esc_attr($active) ?>" data-filter="<?php echo esc_attr($tags_row->name) ?>" value="<?php echo esc_attr($tags_row->term_id); ?>" ><?php echo esc_html($tags_row->name) ?></button>
             <?php
             }
             ?>
          </div>
          <div class="bwg_tag_button_see_all"><div class="see_all_tags bwg_show"><span>&#43;</span><?php echo esc_html__('see all tags', 'photo-gallery');?></div></div>
        <?php
        } ?>
        <input type="hidden" id="bwg_tag_id_<?php echo esc_attr($cur_gal_id); ?>" value="<?php echo esc_attr($get_filter_teg) ?>" />
        <input type="hidden" class="current_view" value="<?php echo esc_attr($current_view); ?>" />
        <input type="hidden" class="form_id" value="<?php echo esc_attr($form_id); ?>" />
        <input type="hidden" class="cur_gal_id" value="<?php echo esc_attr($cur_gal_id); ?>" />
        <input type="hidden" class="album_gallery_id" value="<?php echo esc_attr($album_gallery_id); ?>" />
        <input type="hidden" class="type" value="<?php echo esc_attr($type); ?>" />
        <input type="hidden" class="show_bottom_tag" value="<?php echo esc_attr($show_bottom_tag); ?>" />
      </div>
      <?php
    }
  }

  /**
   * @param $theme_row
   * @param $position
   * @param $params
   * @param $bwg
   */
  public function ajax_html_frontend_page_nav($theme_row, $position, $params, $bwg, $pagination_style = 'simple') {
    $count_items = $params['album_view_type'] == 'album' ? $params['album_gallery_rows']['page_nav']['total'] : $params['image_rows']['page_nav']['total'];
    $page_number = $params['album_view_type'] == 'album' ? $params['album_gallery_rows']['page_nav']['limit'] : $params['image_rows']['page_nav']['limit'];
    $form_id = 'gal_front_form_' . $bwg;
    $items_per_page = $params['items_per_page'];
    $current_view = $bwg;
    $id = $params['container_id'];
    $cur_alb_gal_id = $params['cur_alb_gal_id'];
    $type = $params['album_view_type'];
    $enable_seo = (int) BWG()->options->enable_seo;
    $enable_dynamic_url = (int) BWG()->options->front_ajax;
    $pagination = $params['image_enable_page'];
    if ( isset($params['image_enable_page'])
      && isset($params['images_per_page'])
      && $params['image_enable_page']
      && $params['images_per_page']
      && ($theme_row->page_nav_position == $position) ) {
      ob_start();
      $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
      ?>
      /*pagination styles*/
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> {
				text-align: <?php echo esc_html($theme_row->page_nav_align); ?>;
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				margin: 6px 0 4px;
				display: block;
      }
      @media only screen and (max-width : 320px) {
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .displaying-num_<?php echo esc_attr($current_view); ?> {
     	 display: none;
      }
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .displaying-num_<?php echo esc_attr($current_view); ?> {
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				margin-right: 10px;
				vertical-align: middle;
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .paging-input_<?php echo esc_attr($current_view); ?> {
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				vertical-align: middle;
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a.disabled,
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a.disabled:hover,
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a.disabled:focus,
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> input.bwg_current_page {
				cursor: default;
				color: rgba(<?php echo esc_html($rgb_page_nav_font_color['red']); ?>, <?php echo esc_html($rgb_page_nav_font_color['green']); ?>, <?php echo esc_html($rgb_page_nav_font_color['blue']); ?>, 0.5);
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a,
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> input.bwg_current_page {
				cursor: pointer;
				text-align: center;
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				text-decoration: none;
				padding: <?php echo esc_html($theme_row->page_nav_padding); ?>;
				margin: <?php echo esc_html($theme_row->page_nav_margin); ?>;
				border-radius: <?php echo esc_html($theme_row->page_nav_border_radius); ?>;
				border-style: <?php echo esc_html($theme_row->page_nav_border_style); ?>;
				border-width: <?php echo esc_html($theme_row->page_nav_border_width); ?>px;
				border-color: #<?php echo esc_html($theme_row->page_nav_border_color); ?>;
				<?php $rgb_page_nav_button_bg_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_button_bg_color); ?>
				background-color: rgba(<?php echo esc_html($rgb_page_nav_button_bg_color['red']); ?>, <?php echo esc_html($rgb_page_nav_button_bg_color['green']); ?>, <?php echo esc_html($rgb_page_nav_button_bg_color['blue']); ?>, <?php echo esc_html(number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", "")); ?>);
				box-shadow: <?php echo esc_html($theme_row->page_nav_box_shadow); ?>;
				<?php echo ($theme_row->page_nav_button_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      <?php
      if ( !$params['pagination_default_style'] && $pagination_style == 'image_browser' ) {
        $image_browser_images_conteiner = WDWLibrary::spider_hex2rgb($theme_row->image_browser_full_bg_color);
      ?>
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a.next-page:hover,
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a.prev-page:hover {
        color: #000000;
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> .first-page,
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> .last-page {
        padding: 0% 7%;
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> .next-page {
        margin: 0% 4% 0% 0%;
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> .prev-page {
        margin: 0% 0% 0% 4%;
      }
      #bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a {
        font-size: 15px !important;
        padding: 0% 7% !important;
        border-style: none !important;
        border-width: <?php echo esc_html($theme_row->page_nav_border_width); ?>px;
        border-color: #<?php echo esc_html($theme_row->page_nav_border_color); ?>;
        background-color: #<?php echo esc_html($theme_row->page_nav_button_bg_color); ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      .bwg_image_browser#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a {
         padding: 0% 11% !important;
      }
			#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> {
        background-color: rgba(<?php echo esc_attr($image_browser_images_conteiner['red']); ?>, <?php echo esc_attr($image_browser_images_conteiner['green']); ?>, <?php echo esc_attr($image_browser_images_conteiner['blue']); ?>, <?php echo number_format($theme_row->image_browser_full_transparent / 100, 2, ".", ""); ?>);
        margin-top: 0;
      }
      .bwg_image_browser  .pagination-links_<?php echo esc_attr($current_view); ?> {
        white-space: nowrap;
      }
      @media screen and (max-width: 465px) {
        .bwg_image_browser#bwg_container1_<?php echo esc_attr($current_view); ?> #bwg_container2_<?php echo esc_attr($current_view); ?> .tablenav-pages_<?php echo esc_attr($current_view); ?> a {
          padding: 0% 5% !important;
          font-size: 13px;
        }
      }
      <?php
      }
      $inline_style = ob_get_clean();
      if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
      }
      else {
          echo wp_kses('<style>' . $inline_style . '</style>', array('style' => array()));
      }
      $limit = $page_number > 1 ? $items_per_page['load_more_image_count'] : $items_per_page['images_per_page'];
      $limit = $limit ? $limit : 1;
      $type = WDWLibrary::get('type_' . $current_view, $type);
      $type = ($type != 'album') ? 'gallery' : 'album';
      $album_gallery_id = WDWLibrary::get('album_gallery_id_' . $current_view, $cur_alb_gal_id, 'intval');
      if ( $count_items ) {
        if ( $count_items % $limit ) {
          $items_county = ($count_items - $count_items % $limit) / $limit + 1;
        }
        else {
          $items_county = ($count_items - $count_items % $limit) / $limit;
        }
        if ( $pagination == 2 ) {
          $items_county++;
        }
      }
      else {
        $items_county = 1;
      }
      $first_page = "first-page-" . $current_view;
      $prev_page = "prev-page-" . $current_view;
      $next_page = "next-page-" . $current_view;
      $last_page = "last-page-" . $current_view;
      ?>
      <span class="bwg_nav_cont_<?php echo esc_attr($current_view); ?>">
      <?php
      if ( $pagination == 1 ) {
        ?>
        <div class="tablenav-pages_<?php echo esc_attr($current_view); ?>">
          <?php
          if ( $theme_row->page_nav_number ) {
            ?>
            <span class="displaying-num_<?php echo esc_attr($current_view); ?>"><?php echo esc_html($count_items) . ' ' . esc_html__(' item(s)', 'photo-gallery'); ?></span>
            <?php
          }
          if ( $count_items > $limit ) {
            if ( $theme_row->page_nav_button_text ) {
              $first_button = esc_html__('First', 'photo-gallery');
              $previous_button = esc_html__('Previous', 'photo-gallery');
              $next_button = esc_html__('Next', 'photo-gallery');
              $last_button = esc_html__('Last', 'photo-gallery');
            }
            else {
              $first_button = '«';
              $previous_button = '‹';
              $next_button = '›';
              $last_button = '»';
            }
            if ( $page_number == 1 ) {
              $first_page = "first-page disabled";
              $prev_page = "prev-page disabled";
            }
            if ( $page_number >= $items_county ) {
              $next_page = "next-page disabled";
              $last_page = "last-page disabled";
            }

            $REQUEST_URI = isset($_SERVER['REQUEST_URI']) ? sanitize_url($_SERVER['REQUEST_URI']) : '';
            $REQUEST_URI_first = add_query_arg(array( "page_number_" . $current_view => 1 ), $REQUEST_URI);
            $REQUEST_URI_prev =  add_query_arg(array( "page_number_" . $current_view => $page_number - 1 ), $REQUEST_URI);
            ?>
            <span class="pagination-links_<?php echo esc_attr($current_view); ?> pagination-links" data-pages-count="<?php echo esc_attr($items_county); ?>">
              <span class="pagination-links_col1">
              <a class="bwg-a <?php echo esc_html($first_page); ?>" title="<?php echo esc_html__('Go to the first page', 'photo-gallery'); ?>" <?php echo (($enable_dynamic_url || $enable_seo) && $page_number > 1) ? 'href="' . esc_url($REQUEST_URI_first) . '"' : ""; ?>><?php echo esc_html($first_button); ?></a>
              <a class="bwg-a <?php echo esc_html($prev_page); ?>" title="<?php echo esc_html__('Go to the previous page', 'photo-gallery'); ?>" <?php echo (($enable_dynamic_url || $enable_seo) && $page_number > 1) ? 'href="' . esc_url($REQUEST_URI_prev) . '"' : ""; ?>><?php echo esc_html($previous_button); ?></a>
              </span>
							<span class="pagination-links_col2">
									<input type="number" class="bwg_current_page" name="bwg_current_page" value="<?php echo esc_attr($page_number); ?>" data-url-info="page_number_<?php echo esc_attr($current_view); ?>" onkeypress="return bwg_change_page_<?php echo esc_attr($current_view); ?>(event, this)" min="1" oninput="validity.valid||(value='')" />
										<?php echo esc_html__('of', 'photo-gallery'); ?>
										<span class="total-pages_<?php echo esc_attr($current_view); ?>">
											<?php echo esc_html($items_county); ?>
										</span>
							</span>
              <span class="pagination-links_col3">
                <a class="bwg-a <?php echo esc_html($next_page) ?>" title="<?php echo esc_html__('Go to the next page', 'photo-gallery'); ?>" <?php echo (($enable_dynamic_url || $enable_seo) && $page_number + 1 <= $items_county) ? 'href="' . esc_url(add_query_arg(array( "page_number_" . $current_view => $page_number + 1 ), $_SERVER['REQUEST_URI'])) . '"' : ""; ?>><?php echo esc_html($next_button); ?></a>
                <a class="bwg-a <?php echo esc_html($last_page) ?>" title="<?php echo esc_html__('Go to the last page', 'photo-gallery'); ?>" <?php echo (($enable_dynamic_url || $enable_seo) && $page_number < $items_county) ? 'href="' . esc_url(add_query_arg(array( "page_number_" . $current_view => $items_county ), $_SERVER['REQUEST_URI'])) . '"' : ""; ?>><?php echo esc_html($last_button); ?></a>
              </span>
						</span>
						<?php
          }
          ?>
        </div>
          <?php
      }
      elseif ($pagination == 2) {
        if ($count_items > ($limit * ($page_number - 1)) + $items_per_page['images_per_page']) {
        ?>
          <div id="bwg_load_<?php echo esc_attr($current_view); ?>" class="tablenav-pages_<?php echo esc_attr($current_view); ?>">
            <a class="bwg-a bwg_load_btn_<?php echo esc_attr($current_view); ?> bwg_load_btn" href="javascript:void(0);"><?php echo esc_html__('Load More...', 'photo-gallery'); ?></a>
            <input type="hidden" id="bwg_load_more_<?php echo esc_attr($current_view); ?>" name="bwg_load_more_<?php echo esc_attr($current_view); ?>" value="on" />
          </div>
        <?php
        } else {
          ?>
          <script>jQuery('.bwg_nav_cont_<?php echo esc_attr($current_view); ?>').remove()</script>
        <?php
        }
      }
      elseif ($pagination == 3) {
        if ($count_items > $limit * $page_number) {
          ?>
        <script type="text/javascript">
          function bwg_scroll_load_action() {
            if (jQuery(document).scrollTop() + jQuery(window).height() > (jQuery('#<?php echo esc_html($form_id); ?>').offset().top + jQuery('#<?php echo esc_html($form_id); ?>').height())) {
              spider_page_<?php echo esc_attr($current_view); ?>('', <?php echo esc_html($page_number); ?>, 1, true);
              return false;
            }
          }
        jQuery(function() {
            jQuery(window).off("scroll").on("scroll", bwg_scroll_load_action );
        });
        </script>
        <?php
        } else {
        ?>
          <script>jQuery('.bwg_nav_cont_<?php echo esc_attr($current_view); ?>').remove()</script>
          <?php
        }
      }

      $page_number = WDWLibrary::get('page_number_' . $current_view, 1, 'intval');
      $scroll_to_top = $pagination == 1 ? 1 : 0;
      ?>
      <input type="hidden" id="page_number_<?php echo esc_attr($current_view); ?>" name="page_number_<?php echo esc_attr($current_view); ?>" value="<?php echo esc_attr($page_number); ?>" />
      <script type="text/javascript">
        if( jQuery('.bwg_nav_cont_<?php echo esc_attr($current_view); ?>').length > 1 ) {
          jQuery('.bwg_nav_cont_<?php echo esc_attr($current_view); ?>').first().remove()
        }
        function spider_page_<?php echo esc_attr($current_view); ?>(cur, x, y, load_more) {
          if (typeof load_more == "undefined") {
            var load_more = false;
          }
          if (jQuery(cur).hasClass('disabled')) {
            return false;
          }
          var items_county_<?php echo esc_attr($current_view); ?> = <?php echo esc_html($items_county); ?>;
          switch (y) {
            case 1:
              if (x >= items_county_<?php echo esc_attr($current_view); ?>) {
                document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = items_county_<?php echo esc_attr($current_view); ?>;
              }
              else {
                document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = x + 1;
              }
              break;
            case 2:
              document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = items_county_<?php echo esc_attr($current_view); ?>;
              break;
            case -1:
              if (x == 1) {
                document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = 1;
              }
              else {
                document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = x - 1;
              }
              break;
            case -2:
              document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = 1;
              break;
						case 0:
              document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = x;
              break;
            default:
              document.getElementById('page_number_<?php echo esc_attr($current_view); ?>').value = 1;
          }
          bwg_ajax('<?php echo esc_html($form_id); ?>', '<?php echo esc_attr($current_view); ?>', '<?php echo esc_html($id); ?>', '<?php echo esc_html($album_gallery_id); ?>', '', '<?php echo esc_html($type); ?>', 0, '', '', load_more, '', <?php echo esc_html($scroll_to_top); ?>);
        }

        <?php if ( BWG()->options->front_ajax != "1" ) {  ?>
            jQuery('.<?php echo esc_html($first_page); ?>').on('click', function () {
              spider_page_<?php echo esc_attr($current_view); ?>(this, <?php echo esc_html($page_number); ?>, -2, 'numeric');
              return false;
            });
            jQuery('.<?php echo esc_html($prev_page); ?>').on('click', function () {
              spider_page_<?php echo esc_attr($current_view); ?>(this, <?php echo esc_html($page_number); ?>, -1, 'numeric');
              return false;
            });
            jQuery('.<?php echo esc_html($next_page); ?>').on('click', function () {
              spider_page_<?php echo esc_attr($current_view); ?>(this, <?php echo esc_html($page_number); ?>, 1, 'numeric');
              return false;
            });
            jQuery('.<?php echo esc_html($last_page); ?>').on('click', function () {
              spider_page_<?php echo esc_attr($current_view); ?>(this, <?php echo esc_html($page_number); ?>, 2, 'numeric');
              return false;
            });
        <?php } ?>

				/* Change page on input enter. */
        function bwg_change_page_<?php echo esc_attr($current_view); ?>( e, that ) {
          if (  e.key == 'Enter' ) {
            var to_page = parseInt(jQuery(that).val());
            var pages_count = jQuery(that).parents(".pagination-links").data("pages-count");
            var current_url_param = jQuery(that).attr('data-url-info');
            if (to_page > pages_count) {
              to_page = 1;
            }
	 					<?php if ( BWG()->options->front_ajax != "1" ) {  ?>
							spider_page_<?php echo esc_attr($current_view); ?>(this, to_page, 0, 'numeric');
							return false;
						<?php
	 					} else { ?>
            	var url= new URL(window.parent.location);
							var searchParams = new URLSearchParams(url.search);
           	 	if(searchParams != ""){
                var get_page_number = searchParams.get(current_url_param);
                searchParams.set(current_url_param, to_page);
                var new_url = searchParams.toString();
              }
           	 	else {
                var new_url = current_url_param + "=" + to_page;
              }
							window.location.replace("?" + new_url);
							return false;
		  			<?php } ?>
          }
          return true;
        }

        jQuery('.bwg_load_btn_<?php echo esc_attr($current_view); ?>').on('click', function () {
            spider_page_<?php echo esc_attr($current_view); ?>(this, <?php echo esc_html($page_number); ?>, 1, true);
            return false;
        });
      </script>
      </span>
      <?php
    }
  }

  public function http_strip_query_param( $url, $param ) {
    $pieces = parse_url( html_entity_decode($url) );
    if ( !$pieces['query'] ) {
      return $url;
    }
    $query = array();
    parse_str($pieces['query'], $query);
    if ( !isset($query[$param]) ) {
      return $url;
    }
    unset($query[$param]);
    $s_url = sanitize_url($_SERVER["PHP_SELF"]);
    if ( isset($_SERVER["REQUEST_URI"]) ) {
      $r_url = explode("?", sanitize_url($_SERVER["REQUEST_URI"]));
      if ( isset($r_url[0]) ) {
        $s_url = $r_url[0];
      }
    }
    $pieces['query'] = http_build_query($query);
    $href = add_query_arg($query, $s_url);

    return $href;
  }
}
