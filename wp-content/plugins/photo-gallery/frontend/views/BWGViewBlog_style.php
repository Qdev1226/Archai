<?php
class BWGViewBlog_style extends BWGViewSite {

  public function display($params = array(), $bwg = 0, $ajax = FALSE) {
    $theme_row = $params['theme_row'];
    $image_rows = $params['image_rows'];
    $image_rows = $image_rows['images'];
    $lazyload = BWG()->options->lazyload_images;

    $image_title = $params['blog_style_title_enable'];

    if ($params['watermark_type'] == 'none') {
      $show_watermark = FALSE;
      $text_align = '';
      $vertical_align = '';
      $params['watermark_width'] = '';
      $params['watermark_opacity'] = '';
      $watermark_image_or_text = '';
    }
    if ($params['watermark_type'] != 'none') {
      $watermark_position =(($params['watermark_position'] != 'undefined') ? $params['watermark_position'] : 'top-center');
			$position = explode('-', $watermark_position);
			$vertical_align = $position[0];
			$text_align = $position[1];
    }
    if ($params['watermark_type'] == 'text') {
      $show_watermark = TRUE;
			$params['watermark_width'] = '';
			$watermark_image_or_text = esc_html($params['watermark_text']);
			$watermark_a = 'bwg_watermark_text_' . $bwg;
			$watermark_div = 'text_';
    }
    elseif ($params['watermark_type'] == 'image') {
      $show_watermark = TRUE;
			$watermark_image_or_text = '<img class="bwg_blog_style_watermark_img_' . esc_attr($bwg) . '" src="' . esc_url(urldecode($params['watermark_url'])) . '" >';
			$watermark_a = '';
			$watermark_div = '';
    }

    $inline_style = $this->inline_styles($bwg, $theme_row, $params, $show_watermark, $text_align, $vertical_align, $watermark_image_or_text);
    if ( !WDWLibrary::elementor_is_active() ) {
	  if ( !$params['ajax'] ) {
        if ( BWG()->options->use_inline_stiles_and_scripts ) {
          wp_add_inline_style('bwg_frontend', $inline_style);
        }
        else {
          echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
        }
      } else {
      echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
	    }
    }
    else {
      echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
    }
    ob_start();
    ?>
    <div id="bwg_<?php echo esc_attr($params['gallery_type'].'_'.$bwg) ?>" class="bwg-container-<?php echo esc_attr($bwg); ?> blog_style_images_conteiner_<?php echo esc_attr($bwg); ?> bwg-container"
         data-lightbox-url="<?php echo esc_url(addslashes(add_query_arg($params['params_array'], admin_url('admin-ajax.php')))); ?>">
      <div class="blog_style_images_<?php echo esc_attr($bwg); ?>" id="blog_style_images_<?php echo esc_attr($bwg); ?>" >
        <?php
        foreach ($image_rows as $image_row) {
          $params['image_id'] = WDWLibrary::get('image_id', $image_row->id, 'intval');
          $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
          $is_embed_16x9 = ((preg_match('/EMBED/',$image_row->filetype)==1 ? true :false) && (preg_match('/VIDEO/',$image_row->filetype)==1 ? true :false) && !(preg_match('/INSTAGRAM/',$image_row->filetype)==1 ? true :false));
          $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/',$image_row->filetype)==1 ? true :false;
          ?>
          <div class="blog_style_image_buttons_conteiner_<?php echo esc_attr($bwg); ?>">
            <div class="blog_style_image_buttons_<?php echo esc_attr($bwg);?>">
              <div class="bwg_blog_style_image_<?php echo esc_attr($bwg); ?>" >
                <?php
                if ($show_watermark) {
                  ?>
                  <div class="bwg_blog_style_image_contain_<?php echo esc_attr($bwg); ?>" id="bwg_blog_style_image_contain_<?php echo esc_attr($image_row->id) ?>">
                    <div class="bwg_blog_style_watermark_contain_<?php echo esc_attr($bwg); ?>">
                      <div class="bwg_blog_style_watermark_cont_<?php echo esc_attr($bwg); ?>">
                        <div class="bwg_blog_style_watermark_<?php echo esc_attr($watermark_div).esc_attr($bwg) ?>">
                          <a class="bwg_none_selectable <?php echo esc_attr($watermark_a); ?>" id="watermark_a<?php echo esc_attr($image_row->id); ?>" href="<?php echo esc_url(urldecode($params['watermark_link'])); ?>" target="_blank">
                            <?php echo $watermark_image_or_text ?>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                }
                if ( !$is_embed ) {
                ?>
                <a style="position:relative;" <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg-a bwg_lightbox_' . esc_attr($bwg) . '" data-image-id="' . esc_attr($image_row->id) . '"') : ('class="bwg-a" ' . ($params['thumb_click_action'] == 'redirect_to_url' && $image_row->redirect_url ? 'href="' . esc_url($image_row->redirect_url) . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : ''))) ?>>
                  <img class="skip-lazy bwg_blog_style_img_<?php echo esc_attr($bwg); ?> <?php if( $lazyload ) { ?> bwg_lazyload lazy_loader<?php } ?>"
                       src="<?php if( !$lazyload ) { echo esc_url(BWG()->upload_url . $image_row->image_url); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
                       data-src="<?php echo esc_url(BWG()->upload_url . $image_row->image_url); ?>"
                       alt="<?php echo esc_attr($image_row->alt); ?>"
                       title="<?php echo esc_attr($image_row->alt); ?>" />
                </a>
                <?php
                }
                else /*$is_embed*/
                {
                  if($is_embed_16x9) {
                    WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class' => 'bwg_embed_frame_16x9_' . $bwg, 'width' => $params['blog_style_width'], 'height' => $params['blog_style_width'] * 0.5625, 'frameborder' => "0", 'allowfullscreen' => "allowfullscreen", 'style' => "position: relative; margin:0;"));
                  }
                  else if($is_embed_instagram_post) {
                    $instagram_post_width = $params['blog_style_width'];
                    $instagram_post_height = $params['blog_style_width'];
                    $image_resolution = explode(' x ', $image_row->resolution);
                    if (is_array($image_resolution)) {
                      $instagram_post_width = $image_resolution[0];
                      $instagram_post_height = explode(' ', $image_resolution[1]);
                      $instagram_post_height = $instagram_post_height[0];
                    }
                    WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class' => "bwg_embed_frame_instapost_" . $bwg, 'data-width' => $instagram_post_width, 'data-height' => $instagram_post_height, 'frameborder' => "0", 'allowfullscreen' => "allowfullscreen", 'style' => "position: relative; margin:0;"));
                  }
                  else {/*for instagram image, video and flickr enable lightbox onclick*/
                    ?>
                    <a style="position:relative;" <?php echo ($params['thumb_click_action'] == 'open_lightbox' ? (' class="bwg_lightbox_' . esc_attr($bwg) . '" data-image-id="' . esc_attr($image_row->id) . '"') : ($image_row->redirect_url ? 'href="' . esc_url($image_row->redirect_url) . '" target="' .  ($params['thumb_link_target'] ? '_blank' : '')  . '"' : '')) ?>>
                      <?php
                      WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class' => 'bwg_embed_frame_' . $bwg,'width'=>$params['blog_style_width'], 'height'=>'inherit !important', 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=>"position: relative; margin:0;"));
                      ?>
                    </a>
                    <?php
                  }
                }
                ?>
              </div>
            </div>
            <div class="bwg_blog_style_share_buttons_image_alt<?php echo esc_attr($bwg); ?>">
              <?php
              if ($image_title) {
                ?>
                <div class="bwg_image_alt_<?php echo esc_attr($bwg); ?>" id="alt<?php echo esc_attr($image_row->id); ?>">
                  <?php echo html_entity_decode($image_row->alt); ?>
                </div>
                <?php
              }
              if (($params['popup_enable_comment'] or $params['popup_enable_facebook'] or $params['popup_enable_twitter'] or $params['popup_enable_pinterest'] or $params['popup_enable_tumblr']) and ($params['popup_enable_ctrl_btn'])) {
                $current_url = WDWLibrary::get('current_url', isset($_SERVER['REQUEST_URI']) ? sanitize_url($_SERVER['REQUEST_URI']) : '', 'sanitize_url');
                $share_url = add_query_arg(array(
                                             'gallery_id' => $params['gallery_id'],
                                             'image_id' => $image_row->id,
                                             'curr_url' => $current_url,
                                           ), WDWLibrary::get_share_page());
                $share_image_url = str_replace('%252F', '%2F', urlencode($is_embed ? $image_row->thumb_url : BWG()->upload_url . rawurlencode($image_row->image_url_raw)));
                ?>
                <div id="bwg_blog_style_share_buttons_<?php echo esc_attr($image_row->id); ?>" class="bwg_blog_style_share_buttons_<?php echo esc_attr($bwg); ?>">
                  <?php
                  if ($params['popup_enable_comment']) {
                    ?>
					          <a onclick="jQuery('#bwg_blog_style_share_buttons_<?php echo esc_attr($image_row->id); ?>').attr('data-open-comment', 1); bwg_gallery_box(<?php echo esc_attr($image_row->id); ?>, jQuery('.blog_style_image_buttons_conteiner_<?php echo esc_attr($bwg); ?>').closest('.bwg_container')); return false;" href="#">
                      <i title="<?php echo __('Show comments', 'photo-gallery'); ?>" class="bwg-icon-comment-square bwg_comment"></i>
                    </a>
                    <?php
                  }
                  /* Added:
                    noopener - to prevent the opening page to gain any kind of access to the original page.
                    noreferrer - to prevent passing the referrer information to the target website by removing the referral info from the HTTP header.*/
                  if ($params['popup_enable_facebook']) {
                    ?>
                    <a rel="noopener noreferrer" id="bwg_facebook_a_<?php echo esc_attr($image_row->id); ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Facebook', 'photo-gallery'); ?>">
                      <i title="<?php echo __('Share on Facebook', 'photo-gallery'); ?>" class="bwg-icon-facebook-square bwg_facebook"></i>
                    </a>
                    <?php
                  }
                  if ($params['popup_enable_twitter']) {
                    ?>
                    <a rel="noopener noreferrer" href="https://twitter.com/intent/tweet?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Twitter', 'photo-gallery'); ?>">
                      <i title="<?php echo __('Share on Twitter', 'photo-gallery'); ?>" class="bwg-icon-twitter-square bwg_twitter"></i>
                    </a>
                    <?php
                  }
                  if ($params['popup_enable_pinterest']) {
                    ?>
                    <a rel="noopener noreferrer" href="http://pinterest.com/pin/create/button/?s=100&url=<?php echo urlencode($share_url); ?>&media=<?php echo $share_image_url; ?>&description=<?php echo $image_row->alt . '%0A' . urlencode($image_row->description); ?>" target="_blank" title="<?php echo __('Share on Pinterest', 'photo-gallery'); ?>">
                      <i title="<?php echo __('Share on Pinterest', 'photo-gallery'); ?>" class="bwg-icon-pinterest-square bwg_ctrl_btn bwg_pinterest"></i>
                    </a>
                    <?php
                  }
                  if ($params['popup_enable_tumblr']) {
                    ?>
                    <a rel="noopener noreferrer" href="https://www.tumblr.com/share/photo?source=<?php echo $share_image_url; ?>&caption=<?php echo urlencode($image_row->alt); ?>&clickthru=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Tumblr', 'photo-gallery'); ?>">
                      <i title="<?php echo __('Share on Tumblr', 'photo-gallery'); ?>" class="bwg-icon-tumblr-square bwg_ctrl_btn bwg_tumblr"></i>
                    </a>
                    <?php
                  }
                if ($params['popup_enable_ecommerce'] &&  $image_row->pricelist_id) {
                    ?>
                    <a href="javascript:bwg_gallery_box(<?php echo esc_attr($image_row->id); ?>, jQuery('.blog_style_image_buttons_conteiner_<?php echo esc_attr($bwg); ?>' ).closest( '.bwg_container' ), true);">
                      <i title="<?php echo __('Show ecommerce', 'photo-gallery'); ?>" class="bwg-icon-shopping-cart bwg_ecommerce"></i>
                    </a>
                    <?php
                  }
                  ?>
                </div>
                <?php
              }
              ?>
            </div>
            <?php
            if ($params['blog_style_description_enable']) {
              ?>
            <div class="bwg_blog_style_share_buttons_image_alt<?php echo esc_attr($bwg); ?>">
              <div class="bwg_image_alt_<?php echo esc_attr($bwg); ?>" id="desc<?php echo esc_attr($image_row->id); ?>">
                <?php echo html_entity_decode($image_row->description); ?>
              </div>
            </div>
              <?php
            }
            ?>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
    $content = ob_get_clean();

    /* Set theme parameters for Gallery/Gallery group title/description.*/
    $theme_row->thumb_gal_title_font_size = $theme_row->blog_style_gal_title_font_size;
    $theme_row->thumb_gal_title_font_color = $theme_row->blog_style_gal_title_font_color;
    $theme_row->thumb_gal_title_font_style = $theme_row->blog_style_gal_title_font_style;
    $theme_row->thumb_gal_title_font_weight = $theme_row->blog_style_gal_title_font_weight;
    $theme_row->thumb_gal_title_shadow = $theme_row->blog_style_gal_title_shadow;
    $theme_row->thumb_gal_title_margin = $theme_row->blog_style_gal_title_margin;
    $theme_row->thumb_gal_title_align = $theme_row->blog_style_gal_title_align;

    if ( $ajax ) { /* Ajax response after ajax call for filters and pagination.*/
      parent::ajax_content($params, $bwg, $content);
    }
    else {
      parent::container($params, $bwg, $content);
    }
  }

  public function inline_styles($bwg, $theme_row, $params, $show_watermark = FALSE, $text_align = '', $vertical_align = '', $watermark_image_or_text = '') {
    ob_start();
    $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
    $bwg_blog_style_image = WDWLibrary::spider_hex2rgb($theme_row->blog_style_bg_color);
    $blog_style_share_buttons_bg_color = WDWLibrary::spider_hex2rgb($theme_row->blog_style_share_buttons_bg_color);
    ?>
		.bwg-container {
			<?php
			if ( $theme_row->blog_style_align == 'center' ) { ?>
				justify-content: center;
		 	<?php
			}
			elseif ( $theme_row->blog_style_align == 'left') { ?>
				justify-content: flex-start;
		 	<?php
			}
			else { ?>
				justify-content: flex-end;
		 	<?php } ?>
		}
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .blog_style_images_conteiner_<?php echo esc_attr($bwg); ?>{
		background-color: rgba(0, 0, 0, 0);
		text-align: center;
		width: 100%;
		position: relative;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .blog_style_images_<?php echo esc_attr($bwg); ?> {
		display: inline-block;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		font-size: 0;
		text-align: center;
		max-width: 100%;
		width: <?php echo esc_html($params['blog_style_width']); ?>px;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .blog_style_image_buttons_conteiner_<?php echo esc_attr($bwg); ?> {
		text-align: <?php echo esc_html($theme_row->blog_style_align); ?>;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .blog_style_image_buttons_<?php echo esc_attr($bwg); ?> {
		text-align: center;
		/*display: inline-block;*/
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_image_<?php echo esc_attr($bwg); ?> {
        background-color: rgba(<?php echo esc_html($bwg_blog_style_image['red']); ?>, <?php echo esc_html($bwg_blog_style_image['green']); ?>, <?php echo esc_html($bwg_blog_style_image['blue']); ?>, <?php echo number_format($theme_row->blog_style_transparent / 100, 2, ".", ""); ?>);
		text-align: center;
		/*display: inline-block;*/
		vertical-align: middle;
		margin: <?php echo esc_html($theme_row->blog_style_margin); ?>;
		padding: <?php echo esc_html($theme_row->blog_style_padding); ?>;
		border-radius: <?php echo esc_html($theme_row->blog_style_border_radius); ?>;
		border: <?php echo esc_html($theme_row->blog_style_border_width); ?>px <?php echo esc_html($theme_row->blog_style_border_style); ?> #<?php echo esc_html($theme_row->blog_style_border_color); ?>;
		box-shadow: <?php echo esc_html($theme_row->blog_style_box_shadow); ?>;
		position: relative;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_alt_<?php echo esc_attr($bwg); ?> {
		display: table-cell;
		width: 50%;
		text-align: <?php  if (!(($params['popup_enable_comment'] or $params['popup_enable_facebook'] or $params['popup_enable_twitter']) and ($params['popup_enable_ctrl_btn'])) and ($params['blog_style_title_enable']) ) echo esc_html($theme_row->blog_style_share_buttons_align); else echo "left"; ?>;
		font-size: <?php echo esc_html($theme_row->blog_style_img_font_size); ?>px;
		font-family: <?php echo esc_html($theme_row->blog_style_img_font_family); ?>;
		color: #<?php echo esc_html($theme_row->blog_style_img_font_color); ?>;
		padding-left: 8px;
        word-wrap: break-word;
        word-break: break-word;
        vertical-align: middle;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_img_<?php echo esc_attr($bwg); ?> {
        padding: 0 !important;
        max-width: 100% !important;
        height: inherit !important;
        width: 100%;
      }
      /*pagination styles*/
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .tablenav-pages_<?php echo esc_attr($bwg); ?> {
				text-align: <?php echo esc_html($theme_row->page_nav_align); ?>;
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				margin: 6px 0 4px;
				display: block;
				height: 30px;
				line-height: 30px;
      }
      @media only screen and (max-width : 320px) {
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .displaying-num_<?php echo esc_attr($bwg); ?> {
				  display: none;
				}
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_image_alt_<?php echo esc_attr($bwg); ?>{
				  display: none;
				}
        <?php
        if ($show_watermark && ($params['watermark_type'] == 'text' ? TRUE : FALSE)) { ?>
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>,
		#bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>:hover {
				  font-size:10px !important;
				  text-decoration: none;
				  margin: 4px;
				  font-family: <?php echo esc_html($params['watermark_font']); ?>;
				  color: #<?php echo esc_html($params['watermark_color']); ?> !important;
				  <?php if( !empty($params['watermark_opacity'])) { ?>
				  opacity: <?php echo number_format($params['watermark_opacity'] / 100, 2, ".", ""); ?>;
				  <?php } ?>
				  text-decoration: none;
				  position: relative;
				  z-index: 10141;
				}
        <?php } ?>
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .displaying-num_<?php echo esc_attr($bwg); ?> {
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				margin-right: 10px;
				vertical-align: middle;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .paging-input_<?php echo esc_attr($bwg); ?> {
				font-size: <?php echo esc_html($theme_row->page_nav_font_size); ?>px;
				font-family: <?php echo esc_html($theme_row->page_nav_font_style); ?>;
				font-weight: <?php echo esc_html($theme_row->page_nav_font_weight); ?>;
				color: #<?php echo esc_html($theme_row->page_nav_font_color); ?>;
				vertical-align: middle;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .tablenav-pages_<?php echo esc_attr($bwg); ?> a.disabled,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .tablenav-pages_<?php echo esc_attr($bwg); ?> a.disabled:hover,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .tablenav-pages_<?php echo esc_attr($bwg); ?> a.disabled:focus {
				cursor: default;
				color: rgba(<?php echo esc_html($rgb_page_nav_font_color['red']); ?>, <?php echo esc_html($rgb_page_nav_font_color['green']); ?>, <?php echo esc_html($rgb_page_nav_font_color['blue']); ?>, 0.5);
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .tablenav-pages_<?php echo esc_attr($bwg); ?> a {
				cursor: pointer;
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
				background-color: #<?php echo esc_html($theme_row->page_nav_button_bg_color); ?>;
				opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
				box-shadow: <?php echo esc_html($theme_row->page_nav_box_shadow); ?>;
				<?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      /*Share button styles*/
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_share_buttons_image_alt<?php echo esc_attr($bwg); ?> {
				display: table;
				clear: both;
				margin: <?php echo esc_html($theme_row->blog_style_share_buttons_margin); ?>;
				text-align: center;
				width: 100%;
				border:<?php echo esc_html($theme_row->blog_style_share_buttons_border_width); ?>px <?php echo esc_html($theme_row->blog_style_share_buttons_border_style); ?> #<?php echo esc_html($theme_row->blog_style_share_buttons_border_color); ?>;
				border-radius: <?php echo esc_html($theme_row->blog_style_share_buttons_border_radius); ?>;
				background-color: rgba(<?php echo esc_html($blog_style_share_buttons_bg_color['red']); ?>, <?php echo esc_html($blog_style_share_buttons_bg_color['green']); ?>, <?php echo esc_html($blog_style_share_buttons_bg_color['blue']); ?>, <?php echo number_format($theme_row->blog_style_share_buttons_bg_transparent / 100, 2, ".", ""); ?>);
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_share_buttons_<?php echo esc_attr($bwg); ?> {
        display: table-cell;
        text-align: <?php if ((($params['popup_enable_comment'] or $params['popup_enable_facebook'] or $params['popup_enable_twitter']) and ($params['popup_enable_ctrl_btn'])) and (!($params['blog_style_title_enable'])) ) echo esc_html($theme_row->blog_style_share_buttons_align); else echo "right"; ?>;
        width: 50%;
        color: #<?php echo esc_html($theme_row->blog_style_share_buttons_color); ?>;
		font-size: <?php echo esc_html($theme_row->blog_style_share_buttons_font_size); ?>px;
        vertical-align:middle;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_share_buttons_<?php echo esc_attr($bwg); ?> a,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_share_buttons_<?php echo esc_attr($bwg); ?> a:hover {
        color: #<?php echo esc_html($theme_row->blog_style_share_buttons_color); ?>;
		font-size: <?php echo esc_html($theme_row->blog_style_share_buttons_font_size); ?>px;
        margin: 0 5px;
        text-decoration: none;
        vertical-align: middle;
        font-family: none;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> [class^="bwg-icon-"],
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> [class*=" bwg-icon-"] {
        vertical-align: baseline;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_facebook:hover {
				color: #3B5998;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_twitter:hover {
				color: #4099FB;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_pinterest:hover {
        color: #cb2027;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_tumblr:hover {
        color: #2F5070;
      }
      /*watermark*/
      <?php
        if ($show_watermark && $watermark_image_or_text) { ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>,
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_watermark_text_<?php echo esc_attr($bwg); ?>:hover {
				text-decoration: none;
				margin: 4px;
				font-size: <?php echo esc_html($params['watermark_font_size']); ?>px;
				font-family: <?php echo esc_html($params['watermark_font']); ?>;
				color: #<?php echo esc_html($params['watermark_color']); ?> !important;
				opacity: <?php echo esc_html(number_format($params['watermark_opacity'] / 100, 2, ".", "")); ?>;
				position: relative;
				z-index: 10141;
      }
      <?php } ?>
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_image_contain_<?php echo esc_attr($bwg); ?>{
				position: absolute;
				text-align: center;
				vertical-align: middle;
				width: 100%;
				height: 100%;
				cursor: pointer;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_watermark_contain_<?php echo esc_attr($bwg); ?>{
		display: table;
		vertical-align: middle;
		width: 100%;
		height: 100%;
        display:none;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_watermark_cont_<?php echo esc_attr($bwg); ?>{
        display: table-cell;
				text-align: <?php echo esc_html($text_align);   ?>;
				position: relative;
				vertical-align: <?php echo esc_html($vertical_align); ?>;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_watermark_<?php echo esc_attr($bwg); ?>{
				display: inline-block;
				overflow: hidden;
				position: relative;
				vertical-align: middle;
				z-index: 10140;
				width: <?php echo esc_html($params['watermark_width']); ?>px;
				max-width: <?php echo intval(($params['watermark_width'])) / intval(($params['blog_style_width'])) * 100 ; ?>%;
				margin: 10px 10px 10px 10px ;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_watermark_text_<?php echo esc_attr($bwg); ?>{
				display: inline-block;
				overflow: hidden;
				position: relative;
				vertical-align: middle;
				z-index: 10140;
				margin: 10px 10px 10px 10px ;
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_blog_style_watermark_img_<?php echo esc_attr($bwg); ?>{
				max-width: 100%;
				<?php if ( !empty($params['watermark_opacity']) ) { ?>
				opacity: <?php echo number_format($params['watermark_opacity'] / 100, 2, ".", ""); ?>;
				<?php } ?>
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
      }
      #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> .bwg_gal_title_<?php echo esc_attr($bwg); ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo esc_html($theme_row->blog_style_gal_title_font_color); ?>;
        display: block;
        font-family: <?php echo esc_html($theme_row->blog_style_gal_title_font_style); ?>;
        font-size: <?php echo esc_html($theme_row->blog_style_gal_title_font_size); ?>px;
        font-weight: <?php echo esc_html($theme_row->blog_style_gal_title_font_weight); ?>;
        padding: <?php echo esc_html($theme_row->blog_style_gal_title_margin); ?>;
        text-shadow: <?php echo esc_html($theme_row->blog_style_gal_title_shadow); ?>;
        text-align: <?php echo esc_html($theme_row->blog_style_gal_title_align); ?>;
      }
    <?php
    return ob_get_clean();
  }
}
