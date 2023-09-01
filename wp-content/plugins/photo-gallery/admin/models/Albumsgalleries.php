<?php

/**
 * Class AlbumsgalleriesModel_bwg
 */
class AlbumsgalleriesModel_bwg {
  /**
   * Get rows data or total count.
   *
   * @param      $params
   * @param bool $total
   *
   * @return array|null|object|string
   */
  public function get_rows_data($params, $total = FALSE) {
    global $wpdb;
    $prepareArgs = array($params['album_id']);
    $where = '';
    if ( !current_user_can('manage_options') && BWG()->options->gallery_role ) {
      $where .= " author=%d ";
      $prepareArgs[] = get_current_user_id();
      // because there are 2 $where one after another and there are no more variables between there in the request and you need a clear number of arguments for preparing query
      $prepareArgs[] = get_current_user_id();
    }
    else {
      $where .= " author>=0 ";
    }
    $limit = '';
    if($params['search']) {
      $where = '`name` LIKE "%s"';
      $prepareArgs[] = "%" . $params['search'] . "%";
      $prepareArgs[] = "%" . $params['search'] . "%";
    }
    $order_by = $total ? '' : ' ORDER BY `' . $params['orderby'] . '` ' . $params['order'];
    if ( !$total ) {
      $limit = ' LIMIT %d, %d';
      $prepareArgs[] = $params['page_num'];
      $prepareArgs[] = $params['items_per_page'];

    }

    $query = '(SELECT id, name, preview_image, random_preview_image, published, 1 as is_album FROM ' . $wpdb->prefix . 'bwg_album WHERE id <> %d ' . (($where) ? 'AND '. $where : '' ) . ')
                UNION ALL
              (SELECT id, name, preview_image, random_preview_image, published, 0 as is_album FROM ' . $wpdb->prefix . 'bwg_gallery ' . (($where) ? 'WHERE '. $where : '' )  . ')' . $order_by . $limit;
    if ($total) {
      $query = 'SELECT COUNT(*) FROM (' . $query . ') as temp';
      return $wpdb->get_var( $wpdb->prepare($query, $prepareArgs) );
    }
    $rows = $wpdb->get_results( $wpdb->prepare($query, $prepareArgs) );
    return $rows;
  }

  /**
   * Return total count.
   *
   * @param $params
   *
   * @return array|null|object|string
   */
  public function total($params) {
    return $this->get_rows_data($params, TRUE);
  }
}