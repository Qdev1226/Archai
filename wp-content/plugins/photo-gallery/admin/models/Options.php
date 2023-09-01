<?php

/**
 * Class OptionsModel_bwg
 */
class OptionsModel_bwg {
  /**
   * Get images count.
   *
   * @return int $imgcount
   */
  public function get_image_count() {
    global $wpdb;
    $imgcount = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "bwg_image");

    return $imgcount;
  }

  /**
   * Update gallery options by key.
   *
   * @param $data_params
   */
  public function update_options_by_key( $data_params = array() ) {
    $options = get_option('wd_bwg_options');
    if ( $options ) {
      $options = json_decode($options);
      foreach ( $data_params as $key => $value ) {
        $options->$key = $value;
      }
      update_option('wd_bwg_options', json_encode($options), 'yes');
    }
  }
}