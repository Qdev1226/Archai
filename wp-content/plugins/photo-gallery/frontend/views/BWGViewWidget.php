<?php
class BWGViewWidgetFrontEnd {
  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function view_tags($params = array()) {
    $current_url = isset($_SERVER['REQUEST_URI']) ? sanitize_url($_SERVER['REQUEST_URI']) : '';
    $type = isset($params["type"]) ? $params["type"] : 'text';
    $bwg = isset($params["bwg"]) ? $params["bwg"] : 0;
    $show_name = isset($params["show_name"]) ? $params["show_name"] : 0;
    $open_option = isset($params["open_option"]) ? $params["open_option"] : 'page';
    $count = isset($params["count"]) ? $params["count"] : 0;
    $width = isset($params["width"]) ? $params["width"] : 250;
    $height = isset($params["height"]) ? $params["height"] : 250;
    $background_transparent = isset($params["background_transparent"]) ? $params["background_transparent"] : 1;
    $background_color = isset($params["background_color"]) ? $params["background_color"] : "000000";
    $text_color = isset($params["text_color"]) ? $params["text_color"] : "eeeeee";
    $theme_id = isset($params["theme_id"]) ? $params["theme_id"] : 0;

    $tags = $this->model->get_tags_data($count);

    require_once BWG()->plugin_dir . "/frontend/models/model.php";
    $model_site = new BWGModelSite();
    $theme_row = $model_site->get_theme_row_data($theme_id);

    ob_start();
    ?>
    @media screen and (max-width: <?php echo esc_html($width) ?>px) {
        #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #tags_cloud_item_<?php echo esc_attr($bwg); ?> {
          display: none;
        }
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #tags_cloud_item_<?php echo esc_attr($bwg); ?> {
      width:<?php echo esc_html($width) ?>px;
      height:<?php echo esc_html($height) ?>px;
      margin:0 auto;
      overflow: hidden;
      position: relative;
      background-color: <?php echo esc_html($background_transparent ? 'transparent' : '#' . $background_color); ?>;
      color: #<?php echo esc_html($text_color) ?> !important;
      max-width: 100%;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #tags_cloud_item_<?php echo esc_attr($bwg); ?> ul {
      list-style-type: none;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #tags_cloud_item_<?php echo esc_attr($bwg); ?> ul li:before {
      content: "";
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #tags_cloud_item_<?php echo esc_attr($bwg); ?> ul li a {
      color: inherit !important;
    }
    #bwg_container1_<?php echo esc_attr($bwg); ?> #bwg_container2_<?php echo esc_attr($bwg); ?> #tags_cloud_item_<?php echo esc_attr($bwg); ?> .bwg_link_widget {
      text-decoration: none;
      color: #<?php echo esc_html($text_color); ?> !important;
      cursor: pointer;
      font-size: inherit !important;
    }
	<?php
    $inline_style = ob_get_clean();
    $lazyload = BWG()->options->lazyload_images;
    if ( !WDWLibrary::elementor_is_active() ) {
      if ( BWG()->options->use_inline_stiles_and_scripts) {
        wp_enqueue_style('bwg_frontend');
        wp_add_inline_style('bwg_frontend', $inline_style);

        if (!wp_script_is('bwg_frontend', 'done')) {
        wp_print_scripts('bwg_frontend');
        }
        if (!wp_script_is('mCustomScrollbar', 'done')) {
        wp_print_scripts('mCustomScrollbar');
        }
        if (!wp_script_is('jquery-fullscreen', 'done')) {
        wp_print_scripts('jquery-fullscreen');
        }
        if (!wp_script_is('bwg_gallery_box', 'done')) {
        wp_print_scripts('bwg_gallery_box');
        }
        if(!wp_script_is('bwg_raty', 'done')) {
        wp_print_scripts('bwg_raty');
        }
        if (!wp_script_is('bwg_mobile', 'done')) {
        wp_print_scripts('bwg_mobile');
        }
        if (!wp_script_is('bwg_3DEngine', 'done')) {
        wp_print_scripts('bwg_3DEngine');
        }
      }
      else {
        echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
      }
    }
    else {
      echo wp_kses('<style id="bwg-style-' . esc_attr($bwg) . '">' . $inline_style . '</style>', array('style' => array('id' => true)));
    }
    ?>
    <script type="text/javascript">
    jQuery(function() {
      if (jQuery("#tags_cloud_item_<?php echo esc_attr($bwg); ?> ul li").length > 0) {
        var container = jQuery("#tags_cloud_item_<?php echo esc_attr($bwg); ?>");
        var camera = new Camera3D();
        camera.init(0, 0, 0, (container.width() + container.height()) / 2);
        var tags_cloud_item = new Object3D(container);
        radius = (container.height() > container.width() ? container.width() : container.height());
        tags_cloud_item.addChild(new Sphere(radius * 0.35, <?php echo esc_html(sqrt(count($tags))) ?>, <?php echo esc_html(count($tags)) ?>));
        var scene = new Scene3D();
        scene.addToScene(tags_cloud_item);
        var mouseX = 20;
        var mouseY = 30;
        var offsetX = container.offset().left;
        var offsetY = container.offset().top;
        var speed = 6000;
        container.mousemove(function (e) {
          offsetX = container.offset().left;
          offsetY = container.offset().top;
          mouseX = (e.clientX + jQuery(window).scrollLeft() - offsetX - (container.width() / 2)) % container.width();
          mouseY = (e.clientY + jQuery(window).scrollTop() - offsetY - (container.height() / 2)) % container.height();
        });
        var animateIt = function () {
          if (mouseX != undefined) {
            axisRotation.y += (mouseX) / speed;
          }
          if (mouseY != undefined) {
            axisRotation.x -= mouseY / speed;
          }
          scene.renderCamera(camera);
        };
        setInterval(animateIt, 60);
        jQuery("#tags_cloud_item_<?php echo esc_attr($bwg); ?>").attr("style", "visibility: visible;");
      }
    });
    </script>
    <div id="bwg_container1_<?php echo esc_attr($bwg); ?>">
      <div id="bwg_container2_<?php echo esc_attr($bwg); ?>">
        <div id="tags_cloud_item_<?php echo esc_attr($bwg); ?>" style="visibility: hidden;">
          <ul>
          <?php
            foreach ($tags as $tag) {
              if ($open_option == 'lightbox') {
                $params_array = array(
                  'action' => 'GalleryBox',
                  'current_view' => $bwg,
                  'image_id' => $tag->image_id,
                  'tag' => $tag->term_id,
                  'theme_id' => $theme_id,
                  'current_url' => $current_url,
                  'shortcode_id' => 0,
                );
                if ($type == 'text') {
                  ?>
                  <li><a class="bwg_link_widget" onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo esc_attr($bwg); ?>', '800', '600', 1, 'testpopup', 5, '<?php echo esc_html($theme_row->lightbox_ctrl_btn_pos) ;?>'); return false;"><?php echo esc_html($tag->name); ?></a></li>
                  <?php
                }
                else {
                  
                  $is_embed = preg_match('/EMBED/',$tag->filetype)==1 ? true :false;

                  ?>
                  <li style="text-align: center;">
                    <a class="bwg-a bwg_link_widget" onclick="spider_createpopup('<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>', '<?php echo esc_attr($bwg); ?>', '800', '600', 1, 'testpopup', 5, '<?php echo esc_html($theme_row->lightbox_ctrl_btn_pos) ;?>'); return false;">
                      <img class="skip-lazy <?php if( $lazyload ) { ?> bwg_lazyload <?php } ?>"
                           id="imgg"
                           src="<?php if( !$lazyload ) { echo ( esc_html($is_embed) ? "" : esc_url(BWG()->upload_url) . $tag->thumb_url); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
                           data-src="<?php echo ( $is_embed ? "" : esc_url(BWG()->upload_url)) . esc_url($tag->thumb_url); ?>"
                           alt="<?php echo esc_attr($tag->name) ?>"
                           title="<?php echo $show_name ? '' : esc_attr($tag->name); ?>" />
                      <?php echo $show_name ? '<br />' . esc_html($tag->name) : ''; ?>
                  </a></li>
                  <?php
                }
              }
              else {
                if ($type == 'text') {
                  ?>
                  <li><a class="tag_cloud_link" href="<?php echo esc_url($tag->permalink); ?>"><?php echo esc_html($tag->name); ?></a></li>
                  <?php
                }
                else {
                  $is_embed = preg_match('/EMBED/', $tag->filetype) == 1 ? true : false;
                  ?>
                  <li style="text-align: center;">
                    <a class="bwg-a bwg_link_widget" href="<?php echo esc_url($tag->permalink); ?>">
                      <img class="skip-lazy <?php if( $lazyload ) { ?> bwg_lazyload <?php } ?>"
                           id="imgg"
                           src="<?php if( !$lazyload ) { echo ( esc_html($is_embed) ? "" : esc_url(BWG()->upload_url)) . esc_url($tag->thumb_url); } else { echo esc_url(BWG()->plugin_url."/images/lazy_placeholder.gif"); } ?>"
                           data-src="<?php echo ( esc_html($is_embed) ? "" : esc_url(BWG()->upload_url)) . esc_url($tag->thumb_url);?>"
                           alt="<?php echo esc_attr($tag->name); ?>"
                           title="<?php echo $show_name ? '' : esc_attr($tag->name); ?>" />
                      <?php echo $show_name ? '<br />' . esc_html($tag->name) : ''; ?>
                  </a></li>
                  <?php
                }
              }
            }
          ?>
          </ul>
        </div>
        <div id="bwg_spider_popup_loading_<?php echo esc_attr($bwg); ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo esc_attr($bwg); ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
      </div>
    </div>
    <?php
  }
}
