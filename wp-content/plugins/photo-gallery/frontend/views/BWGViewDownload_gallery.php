<?php

class BWGViewDownload_gallery {
  public function display( $params = array() ) {
    $bwg = $params['bwg'];
    $bwg_gallery_id = $params['gallery_id'];
    $bwg_type = $params['type'];
    $bwg_tag_input_name = $params['tag_input_name'];
    $bwg_tag = $params['tag'];
    // TODO. call method on mudel
    $bwg_img = WDWLibrary::get_image_rows_data( $bwg_gallery_id, $bwg, $bwg_type, $bwg_tag_input_name, $bwg_tag, '', '', '' );
    $images = array();

    foreach ( $bwg_img['images'] as $image ) {
      array_push( $images, $image->pure_thumb_url );
    }

    if ( $images ) {
      @setlocale(LC_ALL, 'he_IL.UTF-8');
      $upload_dir = wp_upload_dir();
      $filepath = $upload_dir['basedir'] . "/photo-gallery-" . date('Y-m-d-H-i-s') . ".zip";

      if ( !class_exists('\PhpZip\ZipFile') ) {
        include_once BWG()->plugin_dir . '/library/vendor/autoload.php';
      }
      $zip = new \PhpZip\ZipFile();

      $images = array_unique($images);
      foreach ( $images as $image ) {
        if ( strpos($image, "http") !== FALSE ) {
          continue;
        }
        $image = html_entity_decode($image, ENT_QUOTES);
        $original = str_replace("thumb", ".original", BWG()->upload_dir . $image);
        if ( WDWLibrary::repair_image_original($original) ) {
          $download_file_original = file_get_contents($original);
          $zip->addFromString(basename($original), $download_file_original)->saveAsFile($filepath); // save the archive to a file;
        }
      }
      $zip->close();
      $filename = basename($filepath);
      header('Content-Type: application/zip');
      header("Content-Disposition: attachment; filename=\"$filename\"");
      while ( ob_get_level() ) {
        ob_end_clean();
      }
      readfile($filepath);
      unlink($filepath);
      die();
    }
    else {
      ?>
      <p><?php _e('There are no images to download.', 'photo-gallery'); ?></p>
      <?php
    }
  }
}