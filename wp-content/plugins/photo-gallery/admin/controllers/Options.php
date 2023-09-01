<?php

/**
 * Class OptionsController_bwg
 */
class OptionsController_bwg {

  public $prefix;

  public function __construct() {
    $this->prefix = BWG()->prefix;
    $this->model = new OptionsModel_bwg();
    $this->view = new OptionsView_bwg();
    $this->page = WDWLibrary::get('page');
  }

  public function execute() {
    $task = WDWLibrary::get('task');
    if($task != ''){
      check_admin_referer(BWG()->nonce, BWG()->nonce);
    }
    $params = array();
    $params['permissions'] = array(
      'manage_options' => 'Administrator',
      'moderate_comments' => 'Editor',
      'publish_posts' => 'Author',
      'edit_posts' => 'Contributor',
    );
    $built_in_watermark_fonts = array();
    foreach (scandir(path_join(BWG()->plugin_dir, 'fonts')) as $filename) {
      if ( strpos($filename, '.') === 0 || strpos($filename, 'twbb') !== FALSE ) {
        continue;
      }
      else {
        $built_in_watermark_fonts[] = $filename;
      }
    }
    $params['built_in_watermark_fonts'] = $built_in_watermark_fonts;
    $params['watermark_fonts'] = array(
      'arial' => 'Arial',
      'Lucida grande' => 'Lucida grande',
      'segoe ui' => 'Segoe ui',
      'tahoma' => 'Tahoma',
      'trebuchet ms' => 'Trebuchet ms',
      'verdana' => 'Verdana',
      'cursive' =>'Cursive',
      'fantasy' => 'Fantasy',
      'monospace' => 'Monospace',
      'serif' => 'Serif',
    );
    $params['page_title'] = __('Global Settings', 'photo-gallery');
    $params['active_tab'] = WDWLibrary::get('active_tab', 0, 'intval');
    $params['gallery_type'] = WDWLibrary::get('gallery_type', 'thumbnails');
    $params['album_type'] = WDWLibrary::get('album_type', 'album_compact_preview');
    $params['gallery_types_name'] = array(
      'thumbnails' => __('Thumbnails', 'photo-gallery'),
      'thumbnails_masonry' => __('Masonry', 'photo-gallery'),
      'thumbnails_mosaic' => __('Mosaic', 'photo-gallery'),
      'slideshow' => __('Slideshow', 'photo-gallery'),
      'image_browser' => __('Image Browser', 'photo-gallery'),
      'blog_style' => __('Blog Style', 'photo-gallery'),
      'carousel' => __('Carousel', 'photo-gallery'),
    );
    $params['album_types_name'] = array(
      'album_compact_preview' => __('Compact', 'photo-gallery'),
      'album_masonry_preview' => __('Masonry', 'photo-gallery'),
      'album_extended_preview' => __('Extended', 'photo-gallery'),
    );
    if (method_exists($this, $task)) {
      $this->$task($params);
    }
    else {
      do_action('bwg_options_execute_task', $task);
      $this->display($params);
    }
  }

    /**
     * Display.
     *
     * @param $params
     */
  public function display($params = array()) {
    $row = new WD_BWG_Options();

    $params['row']  = $row;
    $params['row']->lightbox_shortcode = 0;
    $params['page'] = $this->page;
    $params['imgcount'] = $this->model->get_image_count();
    $params['options_url_ajax'] = add_query_arg( array(
													'action' => 'options_' . BWG()->prefix,
													BWG()->nonce => wp_create_nonce(BWG()->nonce),
												), admin_url('admin-ajax.php') );

    $this->view->display($params);
  }

    /**
     * Reset.
     *
     * @param array $params
     */
  public function reset( $params = array() ) {
    $params['row'] = new WD_BWG_Options(true);
    $params['page'] = $this->page;
  	$params['imgcount'] = $this->model->get_image_count();
    $params['options_url_ajax'] = add_query_arg( array(
													'action' => 'options_' . BWG()->prefix,
													BWG()->nonce => wp_create_nonce(BWG()->nonce),
												), admin_url('admin-ajax.php') );
    echo WDWLibrary::message_id(0, __('Default values restored. Changes must be saved.', 'photo-gallery'), 'notice notice-warning');
    $this->view->display($params);
  }

  public function save( $params = array() ) {
    $this->save_db();
    $this->display( $params );
  }

  public function save_db() {
    $row = new WD_BWG_Options();
    if ( WDWLibrary::get('old_images_directory') ) {
      $row->old_images_directory = WDWLibrary::get('old_images_directory');
    }

    if ( WDWLibrary::get('images_directory', '', 'sanitize_text_field') ) {
      $row->images_directory = WDWLibrary::get('images_directory', '', 'sanitize_text_field');
      if (!is_dir(BWG()->abspath . $row->images_directory) || (is_dir(BWG()->abspath . $row->images_directory . '/photo-gallery') && $row->old_images_directory && $row->old_images_directory != $row->images_directory)) {
        if (!is_dir(BWG()->abspath . $row->images_directory)) {
          echo WDWLibrary::message_id(0, __('Uploads directory doesn\'t exist. Old value is restored.', 'photo-gallery'), 'error');
        }
        else {
          echo WDWLibrary::message_id(0, __('Warning: "photo-gallery" folder already exists in uploads directory. Old value is restored.', 'photo-gallery'), 'error');
        }
        if ($row->old_images_directory) {
          $row->images_directory = $row->old_images_directory;
        }
        else {
          $upload_dir = wp_upload_dir();
          if (!is_dir($upload_dir['basedir'] . '/photo-gallery')) {
            mkdir($upload_dir['basedir'] . '/photo-gallery', 0755);
          }
          $row->images_directory = str_replace(BWG()->abspath, '', $upload_dir['basedir']);
        }
      }
    }

    foreach ($row as $name => $value) {
      if ( $name != 'images_directory' ) {
        $row->$name = WDWLibrary::get($name, $row->$name);
      }
    }
    $save = update_option('wd_bwg_options', json_encode($row), 'no');
    if ( WDWLibrary::get('recreate') == "resize_image_thumb" ) {
      $resize_status = $this->resize_image_thumb();
      if ( !$resize_status ) {
        echo WDWLibrary::message_id(31);
      }
      else {
        echo WDWLibrary::message_id(0, __('All thumbnails are successfully recreated.', 'photo-gallery'));
      }
    }

    if ( $save ) {
      // Move images folder to the new direction if image directory has been changed.
      if ($row->old_images_directory && $row->old_images_directory != $row->images_directory) {
        rename(BWG()->abspath . $row->old_images_directory . '/photo-gallery', BWG()->abspath . $row->images_directory . '/photo-gallery');
      }

      if (!is_dir(BWG()->abspath . $row->images_directory . '/photo-gallery')) {
        mkdir(BWG()->abspath . $row->images_directory . '/photo-gallery', 0755);
      }
      else {
        echo WDWLibrary::message_id(0, __('Item Succesfully Saved.', 'photo-gallery'));
      }
    }
  }

  public function image_set_watermark($params = array()) {
	$limitstart = WDWLibrary::get('limitstart', 0, 'intval');
    /*  Update options only first time of the loop  */
    if ( $limitstart == 0 ) {
		$update_options = array(
			'built_in_watermark_type' => WDWLibrary::get('built_in_watermark_type'),
			'built_in_watermark_position' => WDWLibrary::get('built_in_watermark_position')
		);
		if ( $update_options['built_in_watermark_type'] == 'text' ){
			$update_options['built_in_watermark_text'] = WDWLibrary::get('built_in_watermark_text');
			$update_options['built_in_watermark_font_size'] = WDWLibrary::get('built_in_watermark_font_size', 20, 'intval');
			$update_options['built_in_watermark_font'] = WDWLibrary::get('built_in_watermark_font');
			$update_options['built_in_watermark_color'] = WDWLibrary::get('built_in_watermark_color');
      $update_options['built_in_watermark_opacity'] = WDWLibrary::get('built_in_opacity');
		} 
		else {
			$update_options['built_in_watermark_size'] = WDWLibrary::get('built_in_watermark_size', 20, 'intval');
			$update_options['built_in_watermark_url'] = WDWLibrary::get('built_in_watermark_url', '', 'esc_url');
		}
		$this->model->update_options_by_key( $update_options );
    }

	$error = false;

    if ( ini_get('allow_url_fopen') == 0 ) {
      $error = true;
      $message = WDWLibrary::message_id(0, __('http:// wrapper is disabled in the server configuration by allow_url_fopen=0.', 'photo-gallery'), 'error');
    }
    else {
      if ( !empty($update_options['built_in_watermark_url']) ) {
        list($width_watermark, $height_watermark, $type_watermark) = getimagesize($update_options['built_in_watermark_url']);
      }
      if ( isset($update_options['built_in_watermark_type']) && $update_options['built_in_watermark_type'] == 'image' && (empty($width_watermark) OR empty($height_watermark) OR empty($type_watermark)) ) {
        $error = TRUE;
        $message = WDWLibrary::message_id(0, __('Watermark could not be set. The image URL is incorrect.', 'photo-gallery'), 'error');
      }
      if ( $error === FALSE ) {
        WDWLibrary::bwg_image_set_watermark(0, 0, $limitstart);
        $message = WDWLibrary::message_id(0, __('All images are successfully watermarked.', 'photo-gallery'), 'updated');
      }
    }
    $json_data = array('error' => $error, 'message' => $message);
    echo json_encode($json_data); die();
  }

  public function image_recover_all($params = array()) {
    $limitstart = WDWLibrary::get('limitstart', 0, 'intval');
    WDWLibrary::bwg_image_recover_all(0, $limitstart);
  }

  /**
   * Resize image thumb.
   *
   * @param array $params
   *
   * @return bool
   */
  public function resize_image_thumb( $params = array() ) {
    global $wpdb;
    $max_width = WDWLibrary::get('img_option_width', 500, 'intval');
    $max_height = WDWLibrary::get('img_option_height', 500, 'intval');
    $limitstart = WDWLibrary::get('limitstart', 0, 'intval');
    $resize_status = true;
    /*  Update options only first time of the loop  */
    if ( $limitstart == 0 ) {
      $this->model->update_options_by_key( array('upload_thumb_width' => $max_width,'upload_thumb_height' => $max_height ) );
    }
    $img_ids = $wpdb->get_results($wpdb->prepare('SELECT id, thumb_url, filetype FROM ' . $wpdb->prefix . 'bwg_image LIMIT 50 OFFSET %d', $limitstart));
    foreach ($img_ids as $img_id) {
      if ( preg_match('/EMBED/', $img_id->filetype) == 1 ) {
        continue;
      }
      $file_path = str_replace("thumb", ".original", htmlspecialchars_decode(BWG()->upload_dir . $img_id->thumb_url, ENT_COMPAT | ENT_QUOTES));
      $new_file_path = htmlspecialchars_decode( BWG()->upload_dir . $img_id->thumb_url, ENT_COMPAT | ENT_QUOTES );
      if ( WDWLibrary::repair_image_original($file_path) ) {
        $resize_status = WDWLibrary::resize_image( $file_path, $new_file_path, $max_width, $max_height );
      }
    }
    if ( wp_doing_ajax() ) {
      $message = ($resize_status) ? __('Thumbnails successfully recreated.', 'photo-gallery') : __('The webp support should be enabled for GD and/or ImageMagick.', 'photo-gallery');
      echo json_encode( array(
        'status' => $resize_status,
        'message' => $message
        ) );
      exit;
    }
    return $resize_status;
  }
}