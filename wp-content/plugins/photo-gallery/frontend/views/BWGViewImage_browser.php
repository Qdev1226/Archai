<?php
/**
 * Class BWGViewImage_browser
 */
class BWGViewImage_browser extends BWGViewSite {

  /**
   * Display.
   *
   * @param array $params
   * @param int $bwg
   */
  public function display( $params = array(), $bwg = 0, $ajax = FALSE) {
    $theme_row = $params['theme_row'];
    $image_rows = $params['image_rows'];
    $image_title = $params['image_browser_title_enable'];
    $enable_image_description = $params['image_browser_description_enable'];
    $image_right_click = isset(BWG()->options->image_right_click) ? BWG()->options->image_right_click : 0;
    $page_nav = $image_rows['page_nav'];
    $images = $image_rows['images'];
    $items_per_page = array('images_per_page' => 1, 'load_more_image_count' => 1);
    $lazyload = BWG()->options->lazyload_images;
    if ( $params['watermark_type'] == 'none' ) {
      $text_align = '';
      $vertical_align = '';
      $show_watermark = FALSE;
    }
    if ( $params['watermark_type'] != 'none' ) {
      $position = explode('-', $params['watermark_position']);
      $vertical_align = $position[0];
      $text_align = $position[1];
    }
    if ( $params['watermark_type'] == 'text' ) {
      $show_watermark = TRUE;
      $params['watermark_width'] = 0;
      $watermark_a = 'bwg_watermark_text_' . $bwg;
      $watermark_div = '_text';
      $watermark_image_or_text = esc_html($params["watermark_text"]);
    }
    elseif ( $params['watermark_type'] == 'image' ) {
      $show_watermark = TRUE;
      $watermark_image_or_text = '<img class="bwg_image_browser_watermark_img_' . esc_attr($bwg) . '" src="' . urldecode($params['watermark_url']) . '" />';
      $watermark_a = '';
      $watermark_div = '';
      $params['watermark_font'] = '';
      $params['watermark_color'] = '';
      $params['watermark_font_size'] = '';
    }
    $image_browser_image_title_align = (isset($theme_row->image_browser_image_title_align)) ? $theme_row->image_browser_image_title_align : 'top';
    $inline_style = $this->inline_styles($bwg, $theme_row, $params, $text_align, $vertical_align);
    if ( !WDWLibrary::elementor_is_active() ) {
      if ( !$params['ajax'] ) {
        if ( BWG()->options->use_inline_stiles_and_scripts ) {
          wp_add_inline_style('bwg_frontend', $inline_style);
        }
        else {
          echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
        }
      }
	  else {
      echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
      }
    }
    else {
      echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
    }
    $bwg_param = array(
      'is_pro' => BWG()->is_pro,
      'image_right_click' => $image_right_click,
      'gallery_id' => $params['gallery_id'],
    );
    $bwg_params = json_encode($bwg_param);
    ob_start();
    ?>
	  <div id="bwg_<?php echo esc_attr($params['gallery_type'] . '_' . $bwg) ?>" class="image_browser_images_conteiner_<?php echo esc_attr($bwg); ?> bwg-image_browser-images-wrapper bwg-container"
         data-params='<?php echo esc_attr($bwg_params) ?>'
         data-bwg="<?php echo esc_attr($bwg); ?>"
         data-lightbox-url="<?php echo esc_url(addslashes(add_query_arg($params['params_array'], admin_url('admin-ajax.php')))); ?>">
      <div class="image_browser_images_<?php echo esc_attr($bwg); ?>">
        <?php
        foreach ( $images as $image_row ) {
          $params['image_id'] = WDWLibrary::get('image_id', $image_row->id, 'intval');
          $is_embed = preg_match('/EMBED/', $image_row->filetype) == 1 ? TRUE : FALSE;
          $is_embed_16x9 = ((preg_match('/EMBED/', $image_row->filetype) == 1 ? TRUE : FALSE) && (preg_match('/VIDEO/', $image_row->filetype) == 1 ? TRUE : FALSE) && !(preg_match('/INSTAGRAM/', $image_row->filetype) == 1 ? TRUE : FALSE));
          $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/', $image_row->filetype) == 1 ? TRUE : FALSE;
          ?>
          <div class="image_browser_image_buttons_conteiner_<?php echo esc_attr($bwg); ?>">
            <div class="image_browser_image_buttons_<?php echo esc_attr($bwg); ?>">
              <?php
              if ( $image_title && ($image_browser_image_title_align == 'top') ) {
                ?>
                <div class="bwg_image_browser_image_alt_<?php echo esc_attr($bwg); ?>">
                  <div class="bwg_image_alt_<?php echo esc_attr($bwg); ?>" id="alt<?php echo esc_attr($image_row->id); ?>">
                    <?php echo WDWLibrary::strip_tags($image_row->alt); ?>
                  </div>
                </div>
                <?php
              }
              ?>
              <div class="bwg_image_browser_image_<?php echo esc_attr($bwg); ?>">
                <?php
                if ( $show_watermark ) {
                  ?>
                  <div class="bwg_image_browser_image_contain_<?php echo esc_attr($bwg); ?>" id="bwg_image_browser_image_contain_<?php echo esc_attr($image_row->id) ?>">
                    <div class="bwg_image_browser_watermark_contain_<?php echo esc_attr($bwg); ?>">
                      <div class="bwg_image_browser_watermark_cont_<?php echo esc_attr($bwg); ?>">
                        <div class="bwg_image_browser_watermark<?php echo esc_attr($watermark_div.'_'. $bwg)?>"  >
                          <a class="bwg_none_selectable <?php echo esc_attr($watermark_a); ?>" id="watermark_a<?php echo esc_attr($image_row->id); ?>" href="<?php echo esc_url($params['watermark_link']); ?>" target="_blank">
                            <?php echo $watermark_image_or_text; ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                }
                if ( !$is_embed ) {
                  ?>
                  <a style="position:relative;" <?php echo (esc_html($params['thumb_click_action']) == 'open_lightbox' ? (' class="bwg-a bwg_lightbox" data-image-id="' . esc_attr($image_row->id) . '" data-elementor-open-lightbox="no"') : ('class="bwg-a" ' . ($params['thumb_click_action'] == 'redirect_to_url' && $image_row->redirect_url ? 'href="' . esc_url($image_row->redirect_url) . '" target="' . ($params['thumb_link_target'] ? '_blank' : '') . '"' : ''))) ?>>
                    <img class="skip-lazy bwg-item0 bwg_image_browser_img bwg_image_browser_img_<?php echo esc_attr($bwg); ?> <?php if( $lazyload ) { ?> bwg_lazyload lazy_loader<?php } ?>"
                         src="<?php if( !$lazyload ) { echo esc_url(BWG()->upload_url . $image_row->image_url); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
                         data-src="<?php echo esc_url(BWG()->upload_url . $image_row->image_url); ?>"
                         alt="<?php echo esc_attr($image_row->alt); ?>"
                         title="<?php echo esc_attr($image_row->alt); ?>" />
                  </a>
                  <?php
                }
                else { /*$is_embed*/
                  if ( $is_embed_16x9 ) {
                    WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array(
                      'id' => 'bwg_embed_frame_16x9_' . $bwg,
                      'width' => $params['image_browser_width'],
                      'height' => $params['image_browser_width'] * 0.5625,
                      'frameborder' => "0",
                      'allowfullscreen' => "allowfullscreen",
                      'style' => "position: relative; margin:0;"
                    ));
                  }
                  else {
                    if ( $is_embed_instagram_post ) {
                      $instagram_post_width = $params['image_browser_width'];
                      $instagram_post_height = $params['image_browser_width'];
                      $image_resolution = explode(' x ', $image_row->resolution);
                      if ( is_array($image_resolution) ) {
                        $instagram_post_width = $image_resolution[0];
                        $instagram_post_height = explode(' ', $image_resolution[1]);
                        $instagram_post_height = $instagram_post_height[0];
                      }
                      WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array(
                        'class' => 'bwg_embed_frame_instapost_' . $bwg,
                        'data-width' => $instagram_post_width,
                        'data-height' => $instagram_post_height,
                        'frameborder' => "0",
                        'allowfullscreen' => "allowfullscreen",
                        'style' => "position: relative; margin:0;"
                      ));
                    }
                    else {/*for instagram image, video and flickr enable lightbox onclick*/
                      ?>
                      <a style="position:relative;" <?php echo ( esc_html($params['thumb_click_action']) == 'open_lightbox' ? (' class="bwg_lightbox bwg_lightbox_' . esc_attr($bwg) . '" data-image-id="' . esc_attr($image_row->id) . '"') : ($image_row->redirect_url ? 'href="' . esc_url($image_row->redirect_url) . '" target="' . ($params['thumb_link_target'] ? '_blank' : '') . '"' : '')) ?>>
                        <?php
                        WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array(
                          'id' => 'bwg_embed_frame_' . $bwg,
                          'width' => $params['image_browser_width'],
                          'height' => 'auto',
                          'frameborder' => "0",
                          'allowfullscreen' => "allowfullscreen",
                          'style' => "position: relative; margin:0;",
                          'class' => 'bwg-item0'
                        ));
                        ?>
                      </a>
                      <?php
                    }
                  }
                }
                ?>
              </div>
              <?php
              if ( $image_title && ($image_browser_image_title_align == 'bottom') ) {
                ?>
                <div class="bwg_image_browser_image_alt_<?php echo esc_attr($bwg); ?>">
                  <div class="bwg_image_alt_<?php echo esc_attr($bwg); ?>" id="alt<?php echo esc_attr($image_row->id); ?>">
                    <?php echo WDWLibrary::strip_tags($image_row->alt); ?>
                  </div>
                </div>
                <?php
              }
              if ( $enable_image_description && ($image_row->description != "") ) {
                ?>
                <div class="bwg_image_browser_image_desp_<?php echo esc_attr($bwg); ?>">
                  <div class="bwg_image_browser_image_description_<?php echo esc_attr($bwg); ?>" id="alt<?php echo esc_attr($image_row->id); ?>">
                    <?php echo WDWLibrary::strip_tags($image_row->description); ?>
                  </div>
                </div>
                <?php
              }
              ?>
            </div>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
    $content = ob_get_clean();

    /* Set theme parameters for Gallery/Gallery group title/description.*/
    $theme_row->thumb_gal_title_font_size = $theme_row->image_browser_gal_title_font_size;
    $theme_row->thumb_gal_title_font_color = $theme_row->image_browser_gal_title_font_color;
    $theme_row->thumb_gal_title_font_style = $theme_row->image_browser_gal_title_font_style;
    $theme_row->thumb_gal_title_font_weight = $theme_row->image_browser_gal_title_font_weight;
    $theme_row->thumb_gal_title_shadow = $theme_row->image_browser_gal_title_shadow;
    $theme_row->thumb_gal_title_margin = $theme_row->image_browser_gal_title_margin;
    $theme_row->thumb_gal_title_align = $theme_row->image_browser_gal_title_align;
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
   * @param $bwg
   * @param $theme_row
   * @param $params
   * @param $text_align
   * @param $vertical_align
   * @return string
   */
  public function inline_styles($bwg, $theme_row, $params, $text_align = '', $vertical_align ='') {
    ob_start();
    $image_browser_images_conteiner = WDWLibrary::spider_hex2rgb($theme_row->image_browser_full_bg_color);
    $bwg_image_browser_image = WDWLibrary::spider_hex2rgb($theme_row->image_browser_bg_color);
    ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .image_browser_images_conteiner_<?php echo esc_attr($bwg); ?> * {
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .image_browser_images_conteiner_<?php echo esc_attr($bwg); ?>{
		background-color: rgba(<?php echo esc_html($image_browser_images_conteiner['red']); ?>, <?php echo esc_html($image_browser_images_conteiner['green']); ?>, <?php echo esc_html($image_browser_images_conteiner['blue']); ?>, <?php echo number_format($theme_row->image_browser_full_transparent / 100, 2, ".", ""); ?>);
		text-align: center;
		width: 100%;
		border-style: <?php echo esc_html($theme_row->image_browser_full_border_style);?>;
		border-width: <?php echo esc_html($theme_row->image_browser_full_border_width);?>px;
		border-color: #<?php echo esc_html($theme_row->image_browser_full_border_color);?>;
		padding: <?php echo esc_html($theme_row->image_browser_full_padding); ?>;
		border-radius: <?php echo esc_html($theme_row->image_browser_full_border_radius); ?>;
		position:relative;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .image_browser_images_<?php echo esc_attr($bwg); ?> {
		display: inline-block;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		font-size: 0;
		text-align: center;
		max-width: 100%;
		width: 100%;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .image_browser_image_buttons_conteiner_<?php echo esc_attr($bwg); ?> {
		text-align: <?php echo esc_html($theme_row->image_browser_align); ?>;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .image_browser_image_buttons_<?php echo esc_attr($bwg); ?> {
		display: inline-block;
		width:100%;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_image_<?php echo esc_attr($bwg); ?> {
        background-color: rgba(<?php echo esc_html($bwg_image_browser_image['red']); ?>, <?php echo esc_html($bwg_image_browser_image['green']); ?>, <?php echo esc_html($bwg_image_browser_image['blue']); ?>, <?php echo number_format($theme_row->image_browser_transparent / 100, 2, ".", ""); ?>);
		text-align: center;
		display: inline-block;
		vertical-align: middle;
		margin: <?php echo esc_html($theme_row->image_browser_margin); ?>;
		padding: <?php echo esc_html($theme_row->image_browser_padding); ?>;
		border-radius: <?php echo esc_html($theme_row->image_browser_border_radius); ?>;
		border: <?php echo esc_html($theme_row->image_browser_border_width); ?>px <?php echo esc_html($theme_row->image_browser_border_style); ?> #<?php echo esc_html($theme_row->image_browser_border_color); ?>;
		box-shadow: <?php echo esc_html($theme_row->image_browser_box_shadow); ?>;
		max-width: <?php echo esc_html($params['image_browser_width']); ?>px;
		width: 100%;
		/*z-index: 100;*/
		position: relative;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_alt_<?php echo esc_attr($bwg); ?>{
		display: table;
		width: 100%;
		font-size: <?php echo esc_html($theme_row->image_browser_img_font_size); ?>px;
		font-family: <?php echo esc_html($theme_row->image_browser_img_font_family); ?>;
		color: #<?php echo esc_html($theme_row->image_browser_img_font_color); ?>;
		text-align:<?php echo esc_html($theme_row->image_browser_image_description_align); ?>;
		padding-left: 8px;
        word-break: break-word;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_img_<?php echo esc_attr($bwg); ?> {
        padding: 0 !important;
		max-width: 100% !important;
		height: inherit !important;
		width: 100%;
      }
      @media only screen and (max-width : 320px) {
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .displaying-num_<?php echo esc_attr($bwg); ?> {
		  display: none;
		}
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_alt_<?php echo esc_attr($bwg); ?> {
		  font-size: 10px !important;
		}
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>,
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>:hover {
		  font-size: 10px !important;
		  text-decoration: none;
		  margin: 4px;
		  font-family: <?php echo esc_html($params['watermark_font']); ?>;
		  color: #<?php echo esc_html($params['watermark_color']); ?> !important;
		  opacity: <?php echo number_format($params['watermark_opacity'] / 100, 2, ".", ""); ?>;
		  text-decoration: none;
		  position: relative;
		  z-index: 10141;
		}
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_image_description_<?php echo esc_attr($bwg); ?> {
		  color: #<?php echo esc_html($theme_row->image_browser_img_font_color); ?>;
		  display: table;
		  width: 100%;
		  text-align: left;
		  font-size: 8px !important;
		  font-family: <?php echo esc_html($theme_row->image_browser_img_font_family); ?>;
		  padding: <?php echo esc_html($theme_row->image_browser_image_description_padding); ?>;
		  /*word-break: break-all;*/
		  border-style: <?php echo esc_html($theme_row->image_browser_image_description_border_style); ?>;
		  background-color: #<?php echo esc_html($theme_row->image_browser_image_description_bg_color); ?>;
		  border-radius: <?php echo esc_html($theme_row->image_browser_image_description_border_radius); ?>;
		  border-width: <?php echo esc_html($theme_row->image_browser_image_description_border_width); ?>px;
		}
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .tablenav-pages_<?php echo esc_attr($bwg); ?> a {
		  font-size: 10px !important;
		}
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_image_desp_<?php echo esc_attr($bwg); ?> {
				display: table;
				clear: both;
				text-align: center;
        padding: <?php echo esc_html($theme_row->image_browser_image_description_margin); ?>;
				width: 100%;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_image_description_<?php echo esc_attr($bwg); ?> {
        color: #<?php echo esc_html($theme_row->image_browser_img_font_color); ?>;
		display: table;
		width: 100%;
		text-align: left;
		font-size: <?php echo esc_html($theme_row->image_browser_img_font_size); ?>px;
		font-family: <?php echo esc_html($theme_row->image_browser_img_font_family); ?>;
		padding: <?php echo esc_html($theme_row->image_browser_image_description_padding); ?>;
		word-break: break-word;
		border-style: <?php echo esc_html($theme_row->image_browser_image_description_border_style); ?>;
		background-color: #<?php echo esc_html($theme_row->image_browser_image_description_bg_color); ?>;
		border-radius: <?php echo esc_html($theme_row->image_browser_image_description_border_radius); ?>;
		border-width: <?php echo esc_html($theme_row->image_browser_image_description_border_width); ?>px;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_image_alt_<?php echo esc_attr($bwg); ?> {
      	display:table;
        clear: both;
        text-align: center;
        padding: 8px;
        width: 100%;
      }
      /*watermark*/
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>:hover {
        text-decoration: none;
        margin: 4px;
        font-size: <?php echo esc_html($params['watermark_font_size']); ?>px;
        font-family: <?php echo esc_html($params['watermark_font']); ?>;
        color: #<?php echo esc_html($params['watermark_color']); ?> !important;
        opacity: <?php echo number_format($params['watermark_opacity'] / 100, 2, ".", ""); ?>;
        position: relative;
        z-index: 10141;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_image_contain_<?php echo esc_attr($bwg); ?>{
        position: absolute;
        text-align: center;
        vertical-align: middle;
        width: 100%;
        height: 100%;
        cursor: pointer;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_watermark_contain_<?php echo esc_attr($bwg); ?>{
        display: table;
        vertical-align: middle;
        width: 100%;
        height: 100%;
      }	 
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_watermark_cont_<?php echo esc_attr($bwg); ?>{
        display: table-cell;
        text-align: <?php echo esc_html($text_align); ?>;
        position: relative;
        vertical-align: <?php echo esc_html($vertical_align); ?>;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_watermark_<?php echo esc_attr($bwg); ?>{
		display: inline-block;
		overflow: hidden;
		position: relative;
		vertical-align: middle;
		z-index: 10140;
		width: <?php echo esc_html($params['watermark_width']);?>px;
		max-width: <?php echo esc_html((($params['watermark_width']) / ($params['image_browser_width'])) * 100); ?>%;
		margin: 10px 10px 10px 10px ;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_watermark_text_<?php echo esc_attr($bwg); ?>{
        display: inline-block;
		overflow: hidden;
		position: relative;
		vertical-align: middle;
		z-index: 10140;
		margin: 10px 10px 10px 10px;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_browser_watermark_img_<?php echo esc_attr($bwg); ?>{
        max-width: 100%;
        opacity: <?php echo number_format($params['watermark_opacity'] / 100, 2, ".", ""); ?>;
        position: relative;
        z-index: 10141;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_none_selectable {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
       ecoration: none;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_gal_title_<?php echo esc_attr($bwg); ?> {
      background-color: rgba(0, 0, 0, 0);
      color: #<?php echo esc_html($theme_row->image_browser_gal_title_font_color); ?>;
      display: block;
      font-family: <?php echo esc_html($theme_row->image_browser_gal_title_font_style); ?>;
      font-size: <?php echo esc_html($theme_row->image_browser_gal_title_font_size); ?>px;
      font-weight: <?php echo esc_html($theme_row->image_browser_gal_title_font_weight); ?>;
      padding: <?php echo esc_html($theme_row->image_browser_gal_title_margin); ?>;
      text-shadow: <?php echo esc_html($theme_row->image_browser_gal_title_shadow); ?>;
      text-align: <?php echo esc_html($theme_row->image_browser_gal_title_align); ?>;
    }
    <?php
    return ob_get_clean();
  }
}
