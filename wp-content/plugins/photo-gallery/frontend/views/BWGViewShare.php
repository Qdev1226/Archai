<?php
class BWGViewShare {

  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display() {
    $gallery_id = WDWLibrary::get('gallery_id', 0, 'intval');
    if ( empty($gallery_id) ) {
      die();
    }

    $image_id = WDWLibrary::get('image_id', 0, 'intval');
    if ( empty($image_id) ) {
      die();
    }

    $curr_url = WDWLibrary::get('curr_url', '', 'sanitize_url');
    if ( empty($curr_url) ) {
      die();
    }

    $curr_url = trim($curr_url, ".");
    $curr_url = trim($curr_url, "/");

    $domain = !empty($_SERVER['HTTP_HOST']) ? sanitize_url($_SERVER['HTTP_HOST']) : '';
    $domain = rtrim($domain, "/") . '/';

    // Current URL.
    $current_url = $domain . $curr_url . '#bwg' . $gallery_id . '/' . $image_id;

    $image_row = $this->model->get_image_row_data($image_id);
    if ( empty($image_row) ) {
      die();
    }

    $blog_name = get_bloginfo('name');
    $alt = ($image_row->alt != '') ? $image_row->alt : $blog_name;
    $description = $image_row->description;
    $share_url = add_query_arg(array(
                                 'gallery_id' => $gallery_id,
                                 'image_id' => $image_id,
                                 'curr_url' => $curr_url,
                               ), WDWLibrary::get_share_page());

    $image_thumb_width = BWG()->options->thumb_width;
    $image_thumb_height = BWG()->options->thumb_height;
    $is_embed = preg_match('/EMBED/', $image_row->filetype) == 1 ? TRUE : FALSE;
    if ( !$is_embed ) {
      $image_url = BWG()->upload_url . $image_row->image_url;
      $image_path_url = htmlspecialchars_decode(BWG()->upload_dir . $image_row->image_url, ENT_COMPAT | ENT_QUOTES);
      $image_path_url = explode('?bwg', $image_path_url);
      if ( !empty($image_path_url[0]) ) {
        list($image_thumb_width, $image_thumb_height) = getimagesize($image_path_url[0]);
      }
    }
    else {
      $image_url = $image_row->thumb_url;
      if ($image_row->resolution != '') {
        $resolution_arr = explode(" ", $image_row->resolution);
        $resolution_w = intval($resolution_arr[0]);
        $resolution_h = intval($resolution_arr[2]);
        if ( $resolution_w != 0 && $resolution_h != 0 ) {
          $scale = max(BWG()->options->thumb_width / $resolution_w, BWG()->options->thumb_height / $resolution_h);
          $image_thumb_width = $resolution_w * $scale;
          $image_thumb_height = $resolution_h * $scale;
        }
      }
    }
    ?>
    <html>
      <head>
        <title><?php echo esc_html($blog_name); ?></title>
        <meta property="og:title" content="<?php echo esc_attr($alt); ?>" />
        <meta property="og:site_name" content="<?php echo esc_attr($blog_name); ?>"/>
        <meta property="og:url" content="<?php echo esc_url($share_url); ?>" />
        <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
        <meta property="og:image" content="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_html($image_row->alt); ?>" />
        <meta property="og:image:width" name="bwg_width" content="<?php echo esc_attr($image_thumb_width); ?>" />
        <meta property="og:image:height" name="bwg_height" content="<?php echo esc_attr($image_thumb_height); ?>" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:image" content="<?php echo esc_url($image_url); ?>" />
        <meta name="twitter:title" content="<?php echo esc_attr($alt); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
        <meta content="summary" name="twitter:card" />
      </head>
      <body style="display: none;">
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($alt); ?>" />
      </body>
    </html>
    <script>
      window.location.href = "<?php echo esc_url($current_url); ?>";
    </script>
    <?php
    die();
  }
}
