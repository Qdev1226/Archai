<?php
defined('ABSPATH') || die('Access Denied');

class WD_BWG_Options {

  // General
  public $images_directory = 'wp-content/uploads';
  public $resizable_thumbnails = 1;
  public $upload_img_width = 1200;
  public $upload_img_height = 1200;
  public $upload_thumb_width = 500;
  public $upload_thumb_height = 500;
  public $image_quality = 75;
  public $lazyload_images = 0;
  public $preload_images = 1;
  public $preload_images_count = 10;
  public $show_hide_custom_post = 0;
  public $noindex_custom_post = 1;
  public $show_hide_post_meta = 0;
  public $tags_filter_and_or = 0;
  public $gdpr_compliance = 0;
  public $save_ip = 1;
  public $image_right_click = 0;
  public $use_inline_stiles_and_scripts = 0;
  public $enable_google_fonts = 1;
  public $enable_wp_editor = 0;
  public $enable_seo = 1;
  public $read_metadata = 1;
  public $auto_rotate = 0;
  public $front_ajax = 0;
  public $developer_mode = 0;
  public $enable_date_parameter = 1;

  // Thumbnail
  public $thumb_width = 250;
  public $thumb_height = 140;
  public $image_column_number = 5;
  public $image_enable_page = 1;
  public $images_per_page = 30;
  public $load_more_image_count = 30;
  public $sort_by = 'order';
  public $order_by = 'asc';
  public $show_search_box = 0;
  public $placeholder = 'Search';
  public $search_box_width = 330;
  public $show_sort_images = 0;
  public $show_tag_box = 0;
  public $showthumbs_name = 0;
  public $show_gallery_description = 0;
  public $image_title_show_hover = 'hover';
  public $show_thumb_description = 0;
  public $play_icon = 1;
  public $gallery_download = 0;
  public $ecommerce_icon_show_hover = 'none';

  // Masonry
  public $masonry = 'vertical';
  public $show_masonry_thumb_description = 0;
  public $masonry_thumb_size = 250;
  public $masonry_image_column_number = 5;
  public $masonry_image_enable_page = 1;
  public $masonry_images_per_page = 30;
  public $masonry_load_more_image_count = 30;
  public $masonry_sort_by = 'order';
  public $masonry_order_by = 'asc';
  public $masonry_show_search_box = 0;
  public $masonry_placeholder = 'Search';
  public $masonry_search_box_width = 180;
  public $masonry_show_sort_images = 0;
  public $masonry_show_tag_box = 0;
  public $masonry_show_gallery_title = 0;
  public $masonry_show_gallery_description = 0;
  public $masonry_image_title = 'none';
  public $masonry_play_icon = 1;
  public $masonry_gallery_download = 0;
  public $masonry_ecommerce_icon_show_hover = 'none';

  // Mosaic
  public $mosaic = 'vertical';
  public $resizable_mosaic = 0;
  public $mosaic_total_width = 100;
  public $mosaic_thumb_size = 250;
  public $mosaic_image_enable_page = 1;
  public $mosaic_images_per_page = 30;
  public $mosaic_load_more_image_count = 30;
  public $mosaic_sort_by = 'order';
  public $mosaic_order_by = 'asc';
  public $mosaic_show_search_box = 0;
  public $mosaic_placeholder = 'Search';
  public $mosaic_search_box_width = 180;
  public $mosaic_show_sort_images = 0;
  public $mosaic_show_tag_box = 0;
  public $mosaic_show_gallery_title = 0;
  public $mosaic_show_gallery_description = 0;
  public $mosaic_image_title_show_hover = 'none';
  public $mosaic_play_icon = 1;
  public $mosaic_gallery_download = 0;
  public $mosaic_ecommerce_icon_show_hover = 'none';

  // Slideshow
  public $slideshow_type = 'fade';
  public $slideshow_interval = 5;
  public $slideshow_width = 800;
  public $slideshow_height = 500;
  public $slideshow_sort_by = 'order';
  public $slideshow_order_by = 'asc';
  public $slideshow_enable_autoplay = 0;
  public $slideshow_enable_shuffle = 0;
  public $slideshow_enable_ctrl = 1;
  public $autohide_slideshow_navigation = 1;
  public $slideshow_filmstrip_type = 1;
  public $slideshow_thumbnails_count = 5;
  public $slideshow_filmstrip_height = 90;
  public $slideshow_enable_title = 0;
  public $slideshow_title_position = 'top-right';
  public $slideshow_title_full_width = 0;
  public $slideshow_enable_description = 0;
  public $slideshow_description_position = 'bottom-right';
  public $slideshow_enable_music = 0;
  public $slideshow_audio_url = '';
  public $slideshow_effect_duration = 0.1;
  public $slideshow_gallery_download = 0;

  // Image browser
  public $image_browser_width = 800;
  public $image_browser_title_enable = 1;
  public $image_browser_description_enable = 1;
  public $image_browser_sort_by = 'order';
  public $image_browser_order_by = 'asc';
  public $image_browser_show_gallery_title = 0;
  public $image_browser_show_gallery_description = 0;
  public $image_browser_show_search_box = 0;
  public $image_browser_show_sort_images = 0;
  public $image_browser_show_tag_box = 0;
  public $image_browser_placeholder = 'Search';
  public $image_browser_search_box_width = 180;
  public $image_browser_gallery_download = 0;

  // Blog style
  public $blog_style_width = 800;
  public $blog_style_title_enable = 1;
  public $blog_style_images_per_page = 5;
  public $blog_style_load_more_image_count = 5;
  public $blog_style_enable_page = 1;
  public $blog_style_description_enable = 0;
  public $blog_style_sort_by = 'order';
  public $blog_style_order_by = 'asc';
  public $blog_style_show_gallery_title = 0;
  public $blog_style_show_gallery_description = 0;
  public $blog_style_show_search_box = 0;
  public $blog_style_placeholder = 'Search';
  public $blog_style_search_box_width = 180;
  public $blog_style_show_sort_images = 0;
  public $blog_style_show_tag_box = 0;
  public $blog_style_gallery_download = 0;

  // Carousel
  public $carousel_interval = 5;
  public $carousel_width = 300;
  public $carousel_height = 300;
  public $carousel_image_column_number = 5;
  public $carousel_image_par = '0.75';
  public $carousel_enable_title = 0;
  public $carousel_enable_autoplay = 0;
  public $carousel_r_width = 800;
  public $carousel_fit_containerWidth = 1;
  public $carousel_prev_next_butt = 1;
  public $carousel_play_pause_butt = 1;
  public $carousel_sort_by = 'order';
  public $carousel_order_by = 'asc';
  public $carousel_show_gallery_title = 0;
  public $carousel_show_gallery_description = 0;
  public $carousel_gallery_download = 0;

  // Album compact
  public $album_column_number = 5;
  public $album_thumb_width = 250;
  public $album_thumb_height = 140;
  public $album_image_column_number = 5;
  public $album_image_thumb_width = 250;
  public $album_image_thumb_height = 140;
  public $album_enable_page = 1;
  public $albums_per_page = 30;
  public $album_images_per_page = 30;  
  public $compact_album_sort_by = 'order';
  public $compact_album_order_by = 'asc';
  public $album_sort_by = 'order';
  public $album_order_by = 'asc';
  public $album_show_search_box = 0;
  public $album_placeholder = 'Search';
  public $album_search_box_width = 180;
  public $album_show_sort_images = 0;
  public $album_show_tag_box = 0;
  public $show_album_name = 0;
  public $album_show_gallery_description = 0;
  public $album_title_show_hover = 'hover';
  public $album_view_type = 'thumbnail';
  public $album_image_title_show_hover = 'none';
  public $album_mosaic = 'vertical';
  public $album_resizable_mosaic = 0;
  public $album_mosaic_total_width = 100;
  public $album_play_icon = 1;
  public $album_gallery_download = 0;
  public $album_ecommerce_icon_show_hover = 'none';

  // Album masonry
  public $album_masonry_column_number = 5;
  public $album_masonry_thumb_width = 250;
  public $album_masonry_image_column_number = 5;
  public $album_masonry_image_thumb_width = 250;
  public $album_masonry_enable_page = 1;
  public $albums_masonry_per_page = 30;
  public $album_masonry_images_per_page = 30;
  public $masonry_album_sort_by = 'order';
  public $masonry_album_order_by = 'asc';
  public $album_masonry_sort_by = 'order';
  public $album_masonry_order_by = 'asc';
  public $album_masonry_show_search_box = 0;
  public $album_masonry_placeholder = 'Search';
  public $album_masonry_search_box_width = 180;
  public $album_masonry_show_sort_images = 0;
  public $album_masonry_show_tag_box = 0;
  public $show_album_masonry_name = 0;
  public $album_masonry_show_gallery_description = 0;
  public $album_masonry_image_title = 'none';
  public $album_masonry_gallery_download = 0;
  public $album_masonry_ecommerce_icon_show_hover = 'none';

  // Album extended
  public $extended_album_column_number = 2;
  public $extended_album_height = 160;
  public $album_extended_thumb_width = 357;
  public $album_extended_thumb_height = 201;
  public $album_extended_image_column_number = 5;
  public $album_extended_image_thumb_width = 357;
  public $album_extended_image_thumb_height = 201;
  public $album_extended_enable_page = 1;
  public $albums_extended_per_page = 30;
  public $album_extended_images_per_page = 30;
  public $extended_album_sort_by = 'order';
  public $extended_album_order_by = 'asc';
  public $album_extended_sort_by = 'order';
  public $album_extended_order_by = 'asc';
  public $album_extended_show_search_box = 0;
  public $album_extended_placeholder = 'Search';
  public $album_extended_search_box_width = 180;
  public $album_extended_show_sort_images = 0;
  public $album_extended_show_tag_box = 0;
  public $show_album_extended_name = 0;
  public $extended_album_description_enable = 1;
  public $album_extended_show_gallery_description = 0;
  public $album_extended_view_type = 'thumbnail';
  public $album_extended_image_title_show_hover = 'none';
  public $album_extended_mosaic = 'vertical';
  public $album_extended_resizable_mosaic = 0;
  public $album_extended_mosaic_total_width = 100;
  public $album_extended_play_icon = 1;
  public $album_extended_gallery_download = 0;
  public $album_extended_ecommerce_icon_show_hover = 'none';

  // Lightbox
  public $thumb_click_action = 'open_lightbox';
  public $thumb_link_target = 1;
  public $popup_fullscreen = 1;
  public $popup_width = 800;
  public $popup_height = 500;
  public $popup_type = 'fade';
  public $popup_effect_duration = 0.1;
  public $popup_autoplay = 0;
  public $popup_interval = 2.5;
  public $popup_enable_filmstrip = 1;
  public $popup_filmstrip_height = 60;
  public $popup_enable_ctrl_btn = 1;
  public $popup_enable_fullscreen = 1;
  public $popup_enable_comment = 1;
  public $popup_enable_email = 1;
  public $popup_enable_captcha = 0;
  public $comment_moderation = 0;
  public $popup_enable_info = 1;
  public $popup_info_always_show = 0;
  public $popup_info_full_width = 1;
  public $autohide_lightbox_navigation = 0;
  public $popup_hit_counter = 0;
  public $popup_enable_rate = 0;
  public $popup_enable_zoom = 0;
  public $popup_enable_fullsize_image = 0;
  public $popup_enable_download = 0;
  public $show_image_counts = 0;
  public $enable_loop = 1;
  public $enable_addthis = 0;
  public $addthis_profile_id = '';
  public $popup_enable_facebook = 1;
  public $popup_enable_twitter = 1;
  public $popup_enable_pinterest = 0;
  public $popup_enable_tumblr = 0;
  public $popup_enable_ecommerce = 1;

  // Advanced
  public $autoupdate_interval = 30;
  public $instagram_access_token = '';
  public $instagram_access_token_start_in = '';
  public $instagram_access_token_expires_in = '';
  public $instagram_user_id = '';
  public $instagram_username = '';
  public $facebook_app_id = '';
  public $facebook_app_secret = '';
  public $permissions = 'manage_options';
  public $gallery_role = 0;
  public $album_role = 0;
  public $image_role = 0;
  public $tag_role = 0;
  public $theme_role = 0;
  public $settings_role = 0;

  public $watermark_type = 'none';
  public $watermark_position = 'bottom-left';
  public $watermark_width = 90;
  public $watermark_height = 90;
  public $watermark_url = '';
  public $watermark_text = '10Web.io';
  public $watermark_link = 'https://10web.io/';
  public $watermark_font_size = 20;
  public $watermark_font = 'segoe ui';
  public $watermark_color = 'FFFFFF';
  public $watermark_opacity = 30;

  public $built_in_watermark_type = 'none';
  public $built_in_watermark_position = 'middle-center';
  public $built_in_watermark_size = 15;
  public $built_in_watermark_url = '';
  public $built_in_watermark_text = '10Web.io';
  public $built_in_watermark_font_size = 20;
  public $built_in_watermark_font = 'arial';
  public $built_in_watermark_color = 'FFFFFF';
  public $built_in_watermark_opacity = 30;

  public function __construct($reset = false) {
    $options = get_option('wd_bwg_options');
    $old_images_directory = '';
    if ($options) {
      $options = json_decode($options);
      $old_images_directory = $options->images_directory;
      if (!$reset) {
        if (isset($options)) {
          $this->resizable_thumbnails = 0;
          foreach ($options as $name => $value) {
            $this->$name = $value;
          }
        }
      }
    }
    if ( $this->images_directory === 'wp-content/uploads' ) {
      // If images directory has not been changed by user.
      $upload_dir = wp_upload_dir();
      $this->upload_dir = $upload_dir['basedir'] . '/photo-gallery';
      $this->upload_url = $upload_dir['baseurl'] . '/photo-gallery';
      if ( is_ssl() ) {
        $this->upload_url = str_replace('http://', 'https://', $this->upload_url);
      }
    }
    else {
      // For old users, who have changed images directory.
      // Using ABSPATH here instead of BWG()->abspath to avoid memory leak.
      $this->upload_dir = BWG::get_abspath() . '/' . $this->images_directory . '/photo-gallery';
      $this->upload_url = site_url() . '/' . $this->images_directory . '/photo-gallery';
    }

    // Create directory if not exist.
    if ( !is_dir($this->upload_dir) ) {
      mkdir($this->upload_dir, 0755);
    }

    $this->old_images_directory = $old_images_directory;

    if ( $reset ) {
      $this->watermark_url = BWG()->plugin_url . '/images/watermark.png';
      $this->built_in_watermark_url = BWG()->plugin_url . '/images/watermark.png';
    }
    if ($this->permissions != 'moderate_comments' && $this->permissions != 'publish_posts' && $this->permissions != 'edit_posts') {
      $this->permissions = 'manage_options';
    }

    $this->jpeg_quality = intval($this->image_quality);
    $this->png_quality = 9 - round(9 * intval($this->image_quality) / 100);

    // Will access_token refresh in the last 30 dey.
    if ( !empty( $this->instagram_access_token ) && !empty( $this->instagram_access_token_start_in ) && !empty( $this->instagram_access_token_expires_in ) ) {
      $expires_time = $this->instagram_access_token_start_in + $this->instagram_access_token_expires_in - (30 * 24 * 60 * 60);
      if ( time() >= $expires_time ) {
        $instagram_access_token = WDWLibrary::refresh_instagram_access_token( $this->instagram_access_token, $this );
        if ( isset( $instagram_access_token['access_token'] ) ) {
          $this->instagram_access_token = $instagram_access_token['access_token'];
          $this->instagram_access_token_start_in = time();;
          $this->instagram_access_token_expires_in = $instagram_access_token['expires_in'];
        }
      }
    }
  }

  public function __get($name) {
    return isset($this->$name) ? $this->$name : '';
  }

  /**
   * Get default shortcode options by type (thumb, masonry, compact album...)
   *
   * @param string $view_type
   *
   * @return array
  */
  public function get_default_shortcode_options_by_type( $view_type = '' ) {
    $global_options = get_option('wd_bwg_options');

    switch ( $view_type ) {
      case 'thumbnails':
      case 'tag':
        $data = array(
          "thumb_width" => isset($global_options['thumb_width']) ? $global_options['thumb_width'] : $this->thumb_width,
          "thumb_height" => isset($global_options['thumb_height']) ? $global_options['thumb_height'] : $this->thumb_height,
          "image_column_number" => isset($global_options['image_column_number']) ? $global_options['image_column_number'] : $this->image_column_number,
          "image_enable_page" => isset($global_options['image_enable_page']) ? $global_options['image_enable_page'] : $this->image_enable_page,
          "images_per_page" => isset($global_options['images_per_page']) ? $global_options['images_per_page'] : $this->images_per_page,
          "load_more_image_count" => isset($global_options['load_more_image_count']) ? $global_options['load_more_image_count'] : $this->load_more_image_count,
          "sort_by" => isset($global_options['sort_by']) ? $global_options['sort_by'] : $this->sort_by,
          "order_by" => isset($global_options['order_by']) ? $global_options['order_by'] : $this->order_by,
          "show_search_box" => isset($global_options['show_search_box']) ? $global_options['show_search_box'] : $this->show_search_box,
          "placeholder" => isset($global_options['placeholder']) ? $global_options['placeholder'] : $this->placeholder,
          "search_box_width" => isset($global_options['search_box_width']) ? $global_options['search_box_width'] : $this->search_box_width,
          "show_sort_images" => isset($global_options['show_sort_images']) ? $global_options['show_sort_images'] : $this->show_sort_images,
          "show_tag_box" => isset($global_options['show_tag_box']) ? $global_options['show_tag_box'] : $this->show_tag_box,
          "showthumbs_name" => isset($global_options['showthumbs_name']) ? $global_options['showthumbs_name'] : $this->showthumbs_name,
          "show_gallery_description" => isset($global_options['show_gallery_description']) ? $global_options['show_gallery_description'] : $this->show_gallery_description,
          "image_title" => isset($global_options['image_title']) ? $global_options['image_title'] : $this->image_title,
          "show_thumb_description" => isset($global_options['show_thumb_description']) ? $global_options['show_thumb_description'] : $this->show_thumb_description,
          "play_icon" => isset($global_options['play_icon']) ? $global_options['play_icon'] : $this->play_icon,
          "gallery_download" => isset($global_options['gallery_download']) ? $global_options['gallery_download'] : $this->gallery_download,
          "ecommerce_icon" => isset($global_options['ecommerce_icon']) ? $global_options['ecommerce_icon'] : $this->ecommerce_icon,
          "thumb_click_action" => isset($global_options['thumb_click_action']) ? $global_options['thumb_click_action'] : $this->thumb_click_action,
          "thumb_link_target" => isset($global_options['thumb_link_target']) ? $global_options['thumb_link_target'] : $this->thumb_link_target,
          "popup_fullscreen" => isset($global_options['popup_fullscreen']) ? $global_options['popup_fullscreen'] : $this->popup_fullscreen,
          "popup_width" => isset($global_options['popup_width']) ? $global_options['popup_width'] : $this->popup_width,
          "popup_height" => isset($global_options['popup_height']) ? $global_options['popup_height'] : $this->popup_height,
          "popup_effect" => isset($global_options['popup_effect']) ? $global_options['popup_effect'] : $this->popup_effect,
          "popup_effect_duration" => isset($global_options['popup_effect_duration']) ? $global_options['popup_effect_duration'] : $this->popup_effect_duration,
          "popup_autoplay" => isset($global_options['popup_autoplay']) ? $global_options['popup_autoplay'] : $this->popup_autoplay,
          "popup_interval" => isset($global_options['popup_interval']) ? $global_options['popup_interval'] : $this->popup_interval,
          "popup_enable_filmstrip" => isset($global_options['popup_enable_filmstrip']) ? $global_options['popup_enable_filmstrip'] : $this->popup_enable_filmstrip,
          "popup_filmstrip_height" => isset($global_options['popup_effect']) ? $global_options['popup_effect'] : $this->popup_effect,
          "popup_enable_ctrl_btn" => isset($global_options['popup_enable_ctrl_btn']) ? $global_options['popup_enable_ctrl_btn'] : $this->popup_enable_ctrl_btn,
          "popup_enable_fullscreen" => isset($global_options['popup_enable_fullscreen']) ? $global_options['popup_enable_fullscreen'] : $this->popup_enable_fullscreen,
          "popup_enable_comment" => isset($global_options['popup_enable_comment']) ? $global_options['popup_enable_comment'] : $this->popup_enable_comment,
          "popup_enable_email" => isset($global_options['popup_enable_email']) ? $global_options['popup_enable_email'] : $this->popup_enable_email,
          "popup_enable_captcha" => isset($global_options['popup_enable_captcha']) ? $global_options['popup_enable_captcha'] : $this->popup_enable_captcha,
          "gdpr_compliance" => isset($global_options['gdpr_compliance']) ? $global_options['gdpr_compliance'] : $this->gdpr_compliance,
          "comment_moderation" => isset($global_options['comment_moderation']) ? $global_options['comment_moderation'] : $this->comment_moderation,
          "popup_enable_info" => isset($global_options['popup_enable_info']) ? $global_options['popup_enable_info'] : $this->popup_enable_info,
          "popup_info_always_show" => isset($global_options['popup_info_always_show']) ? $global_options['popup_info_always_show'] : $this->popup_info_always_show,
          "popup_info_full_width" => isset($global_options['popup_info_full_width']) ? $global_options['popup_info_full_width'] : $this->popup_info_full_width,
          "autohide_lightbox_navigation" => isset($global_options['autohide_lightbox_navigation']) ? $global_options['autohide_lightbox_navigation'] : $this->autohide_lightbox_navigation,
          "popup_hit_counter" => isset($global_options['popup_hit_counter']) ? $global_options['popup_hit_counter'] : $this->popup_hit_counter,
          "popup_enable_rate" => isset($global_options['popup_enable_rate']) ? $global_options['popup_enable_rate'] : $this->popup_enable_rate,
          "popup_enable_zoom" => isset($global_options['popup_enable_zoom']) ? $global_options['popup_enable_zoom'] : $this->popup_enable_zoom,
          "popup_enable_fullsize_image" => isset($global_options['popup_enable_fullsize_image']) ? $global_options['popup_enable_fullsize_image'] : $this->popup_enable_fullsize_image,
          "popup_enable_download" => isset($global_options['popup_enable_download']) ? $global_options['popup_enable_download'] : $this->popup_enable_download,
          "show_image_counts" => isset($global_options['show_image_counts']) ? $global_options['show_image_counts'] : $this->show_image_counts,
          "enable_loop" => isset($global_options['enable_loop']) ? $global_options['enable_loop'] : $this->enable_loop,
          "enable_addthis" => isset($global_options['enable_addthis']) ? $global_options['enable_addthis'] : $this->enable_addthis,
          "addthis_profile_id" => isset($global_options['addthis_profile_id']) ? $global_options['addthis_profile_id'] : $this->addthis_profile_id,
          "popup_enable_facebook" => isset($global_options['popup_enable_facebook']) ? $global_options['popup_enable_facebook'] : $this->popup_enable_facebook,
          "popup_enable_twitter" => isset($global_options['popup_enable_twitter']) ? $global_options['popup_enable_twitter'] : $this->popup_enable_twitter,
          "popup_enable_pinterest" => isset($global_options['popup_enable_pinterest']) ? $global_options['popup_enable_pinterest'] : $this->popup_enable_pinterest,
          "popup_enable_tumblr" => isset($global_options['popup_enable_tumblr']) ? $global_options['popup_enable_tumblr'] : $this->popup_enable_tumblr,
          "popup_enable_ecommerce" => isset($global_options['popup_enable_ecommerce']) ? $global_options['popup_enable_ecommerce'] : $this->popup_enable_ecommerce,
          "watermark_type" => isset($global_options['watermark_type']) ? $global_options['watermark_type'] : $this->watermark_type,
          "watermark_link" => isset($global_options['watermark_link']) ? $global_options['watermark_link'] : $this->watermark_link,
        );
        break;
      case 'album_compact':
        $data = array(
          "compuct_album_column_number" => isset($global_options['album_column_number']) ? $global_options['album_column_number'] : $this->album_column_number,
          "compuct_album_thumb_width" => isset($global_options['album_thumb_width']) ? $global_options['album_thumb_width'] : $this->album_thumb_width,
          "compuct_album_thumb_height" => isset($global_options['album_thumb_height']) ? $global_options['album_thumb_height'] : $this->album_thumb_height,
          "compuct_album_image_column_number" => isset($global_options['album_image_column_number']) ? $global_options['album_image_column_number'] : $this->album_image_column_number,
          "compuct_album_image_thumb_width" => isset($global_options['album_image_thumb_width']) ? $global_options['album_image_thumb_width'] : $this->album_image_thumb_width,
          "compuct_album_image_thumb_height" => isset($global_options['album_image_thumb_height']) ? $global_options['album_image_thumb_height'] : $this->album_image_thumb_height,
          "compuct_album_enable_page" => isset($global_options['album_enable_page']) ? $global_options['album_enable_page'] : $this->album_enable_page,
          "compuct_albums_per_page" => isset($global_options['albums_per_page']) ? $global_options['albums_per_page'] : $this->albums_per_page,
          "compuct_album_images_per_page" => isset($global_options['album_images_per_page']) ? $global_options['album_images_per_page'] : $this->album_images_per_page,
          "all_album_sort_by" => isset($global_options['compact_album_sort_by']) ? $global_options['compact_album_sort_by'] : $this->compact_album_sort_by,
          "all_album_order_by" => isset($global_options['compact_album_order_by']) ? $global_options['compact_album_order_by'] : $this->compact_album_order_by,
          "sort_by" => isset($global_options['album_sort_by']) ? $global_options['album_sort_by'] : $this->album_sort_by,
          "order_by" => isset($global_options['album_order_by']) ? $global_options['album_order_by'] : $this->album_order_by,
          "show_search_box" => isset($global_options['album_show_search_box']) ? $global_options['album_show_search_box'] : $this->album_show_search_box,
          "placeholder" => isset($global_options['album_placeholder']) ? $global_options['album_placeholder'] : $this->album_placeholder,
          "search_box_width" => isset($global_options['album_search_box_width']) ? $global_options['album_search_box_width'] : $this->album_search_box_width,
          "show_sort_images" => isset($global_options['album_show_sort_images']) ? $global_options['album_show_sort_images'] : $this->album_show_sort_images,
          "show_tag_box" => isset($global_options['album_show_tag_box']) ? $global_options['album_show_tag_box'] : $this->album_show_tag_box,
          "show_album_name" => isset($global_options['show_album_name']) ? $global_options['show_album_name'] : $this->show_album_name,
          "show_gallery_description" => isset($global_options['album_show_gallery_description']) ? $global_options['album_show_gallery_description'] : $this->album_show_gallery_description,
          "compuct_album_title" => isset($global_options['album_title_show_hover']) ? $global_options['album_title_show_hover'] : $this->album_title_show_hover,
          "compuct_album_view_type" => isset($global_options['album_view_type']) ? $global_options['album_view_type'] : $this->album_view_type,
          "compuct_album_image_title" => isset($global_options['album_image_title']) ? $global_options['album_image_title'] : $this->album_image_title,
          "compuct_album_mosaic_hor_ver" => isset($global_options['album_mosaic']) ? $global_options['album_mosaic'] : $this->album_mosaic,
          "compuct_album_resizable_mosaic" => isset($global_options['album_resizable_mosaic']) ? $global_options['album_resizable_mosaic'] : $this->album_resizable_mosaic,
          "compuct_album_mosaic_total_width" => isset($global_options['album_mosaic_total_width']) ? $global_options['album_mosaic_total_width'] : $this->album_mosaic_total_width,
          "play_icon" => isset($global_options['album_play_icon']) ? $global_options['album_play_icon'] : $this->album_play_icon,
          "gallery_download" => isset($global_options['album_gallery_download']) ? $global_options['album_gallery_download'] : $this->album_gallery_download,
          "ecommerce_icon" => isset($global_options['album_ecommerce_icon_show_hover']) ? $global_options['album_ecommerce_icon_show_hover'] : $this->album_ecommerce_icon_show_hover,
          "thumb_click_action" => isset($global_options['thumb_click_action']) ? $global_options['thumb_click_action'] : $this->thumb_click_action,
          "thumb_link_target" => isset($global_options['thumb_link_target']) ? $global_options['thumb_link_target'] : $this->thumb_link_target,
          "popup_fullscreen" => isset($global_options['popup_fullscreen']) ? $global_options['popup_fullscreen'] : $this->popup_fullscreen,
          "popup_width" => isset($global_options['popup_width']) ? $global_options['popup_width'] : $this->popup_width,
          "popup_height" => isset($global_options['popup_height']) ? $global_options['popup_height'] : $this->popup_height,
          "popup_effect" => isset($global_options['popup_effect']) ? $global_options['popup_effect'] : $this->popup_effect,
          "popup_effect_duration" => isset($global_options['popup_effect_duration']) ? $global_options['popup_effect_duration'] : $this->popup_effect_duration,
          "popup_autoplay" => isset($global_options['popup_autoplay']) ? $global_options['popup_autoplay'] : $this->popup_autoplay,
          "popup_interval" => isset($global_options['popup_interval']) ? $global_options['popup_interval'] : $this->popup_interval,
          "popup_enable_filmstrip" => isset($global_options['popup_enable_filmstrip']) ? $global_options['popup_enable_filmstrip'] : $this->popup_enable_filmstrip,
          "popup_filmstrip_height" => isset($global_options['popup_effect']) ? $global_options['popup_effect'] : $this->popup_effect,
          "popup_enable_ctrl_btn" => isset($global_options['popup_enable_ctrl_btn']) ? $global_options['popup_enable_ctrl_btn'] : $this->popup_enable_ctrl_btn,
          "popup_enable_fullscreen" => isset($global_options['popup_enable_fullscreen']) ? $global_options['popup_enable_fullscreen'] : $this->popup_enable_fullscreen,
          "popup_enable_comment" => isset($global_options['popup_enable_comment']) ? $global_options['popup_enable_comment'] : $this->popup_enable_comment,
          "popup_enable_email" => isset($global_options['popup_enable_email']) ? $global_options['popup_enable_email'] : $this->popup_enable_email,
          "popup_enable_captcha" => isset($global_options['popup_enable_captcha']) ? $global_options['popup_enable_captcha'] : $this->popup_enable_captcha,
          "gdpr_compliance" => isset($global_options['gdpr_compliance']) ? $global_options['gdpr_compliance'] : $this->gdpr_compliance,
          "comment_moderation" => isset($global_options['comment_moderation']) ? $global_options['comment_moderation'] : $this->comment_moderation,
          "popup_enable_info" => isset($global_options['popup_enable_info']) ? $global_options['popup_enable_info'] : $this->popup_enable_info,
          "popup_info_always_show" => isset($global_options['popup_info_always_show']) ? $global_options['popup_info_always_show'] : $this->popup_info_always_show,
          "popup_info_full_width" => isset($global_options['popup_info_full_width']) ? $global_options['popup_info_full_width'] : $this->popup_info_full_width,
          "autohide_lightbox_navigation" => isset($global_options['autohide_lightbox_navigation']) ? $global_options['autohide_lightbox_navigation'] : $this->autohide_lightbox_navigation,
          "popup_hit_counter" => isset($global_options['popup_hit_counter']) ? $global_options['popup_hit_counter'] : $this->popup_hit_counter,
          "popup_enable_rate" => isset($global_options['popup_enable_rate']) ? $global_options['popup_enable_rate'] : $this->popup_enable_rate,
          "popup_enable_zoom" => isset($global_options['popup_enable_zoom']) ? $global_options['popup_enable_zoom'] : $this->popup_enable_zoom,
          "popup_enable_fullsize_image" => isset($global_options['popup_enable_fullsize_image']) ? $global_options['popup_enable_fullsize_image'] : $this->popup_enable_fullsize_image,
          "popup_enable_download" => isset($global_options['popup_enable_download']) ? $global_options['popup_enable_download'] : $this->popup_enable_download,
          "show_image_counts" => isset($global_options['show_image_counts']) ? $global_options['show_image_counts'] : $this->show_image_counts,
          "enable_loop" => isset($global_options['enable_loop']) ? $global_options['enable_loop'] : $this->enable_loop,
          "enable_addthis" => isset($global_options['enable_addthis']) ? $global_options['enable_addthis'] : $this->enable_addthis,
          "addthis_profile_id" => isset($global_options['addthis_profile_id']) ? $global_options['addthis_profile_id'] : $this->addthis_profile_id,
          "popup_enable_facebook" => isset($global_options['popup_enable_facebook']) ? $global_options['popup_enable_facebook'] : $this->popup_enable_facebook,
          "popup_enable_twitter" => isset($global_options['popup_enable_twitter']) ? $global_options['popup_enable_twitter'] : $this->popup_enable_twitter,
          "popup_enable_pinterest" => isset($global_options['popup_enable_pinterest']) ? $global_options['popup_enable_pinterest'] : $this->popup_enable_pinterest,
          "popup_enable_tumblr" => isset($global_options['popup_enable_tumblr']) ? $global_options['popup_enable_tumblr'] : $this->popup_enable_tumblr,
          "popup_enable_ecommerce" => isset($global_options['popup_enable_ecommerce']) ? $global_options['popup_enable_ecommerce'] : $this->popup_enable_ecommerce,
          "watermark_type" => isset($global_options['watermark_type']) ? $global_options['watermark_type'] : $this->watermark_type,
          "watermark_link" => isset($global_options['watermark_link']) ? $global_options['watermark_link'] : $this->watermark_link,
        );
        break;
      default:
        break;
    }

    return $data;
  }
}