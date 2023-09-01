<?php

/**
 * Class GalleriesView_bwg
 */
class GalleriesView_bwg extends AdminView_bwg {

  public function __construct() {
    wp_enqueue_script(BWG()->prefix . '_jquery.ui.touch-punch.min');
    parent::__construct();
  }

  /**
   * Display page.
   *
   * @param $params
   */
  public function display( $params = array() ) {
    wp_enqueue_script(BWG()->prefix . '_jquery.ui.touch-punch.min');
    ob_start();
    echo $this->body($params);
    $form_attr = array(
      'id' => BWG()->prefix . '_galleries',
      'name' => BWG()->prefix . '_galleries',
      'class' => BWG()->prefix . '_galleries wd-form',
      'action' => add_query_arg(array( 'page' => 'galleries_' . BWG()->prefix ), 'admin.php'),
    );
    echo $this->form(ob_get_clean(), $form_attr);
  }

  /**
   * Generate page body.
   *
   * @param $params
   */
  public function body( $params = array() ) {
    echo $this->title(array(
                        'title' => $params['page_title'],
                        'title_class' => 'wd-header',
                        'add_new_button' => array(
                          'href' => add_query_arg(array(
                                                    'page' => $params['page'],
                                                    'task' => 'edit',
                                                  ), admin_url('admin.php')),
                        ),
                        'add_new_button_text' => __('Add new gallery', 'photo-gallery'),
                        'how_to_button' => true,
                      ));
    echo $this->search();
    ?>
    <div class="tablenav top">
      <?php
      echo $this->bulk_actions($params['actions'], TRUE);
      echo $this->pagination($params['page_url'], $params['total'], $params['items_per_page']);
      ?>
    </div>
    <table class="images_table adminlist table table-striped wp-list-table widefat fixed pages media bwg-gallery-lists">
      <thead class="alternate">
		    <td class="col_drag" data-page-number="<?php echo $params['page_num']; ?>" data-ordering-url="<?php echo $params['galleries_ordering_ajax_url']; ?>"></td>
        <td id="cb" class="column-cb check-column">
          <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Title', 'photo-gallery'); ?></label>
          <input id="check_all" type="checkbox">
        </td>
        <?php echo WDWLibrary::ordering('name', $params['orderby'], $params['order'], __('Title', 'photo-gallery'), $params['page_url'], 'column-primary'); ?>
        <th class="col_count"><?php _e('Images count', 'photo-gallery'); ?></th>
        <?php echo WDWLibrary::ordering('author', $params['orderby'], $params['order'], __('Author', 'photo-gallery'), $params['page_url']); ?>
      </thead>
      <tbody id="bwg-table-sortable" class="bwg-ordering">
      <?php
      if ( $params['rows'] ) {
        $alternate = 'class="alternate"';
        foreach ( $params['rows'] as $row ) {
          $user = get_userdata($row->author);
          $alternate = (isset($alternate) && $alternate == 'class="alternate"') ? '' : 'class="alternate"';
          $edit_url = add_query_arg(array(
                                      'page' => $params['page'],
                                      'task' => 'edit',
                                      'current_id' => $row->id,
                                    ), admin_url('admin.php'));
          $publish_url = add_query_arg(array(
                                         'task' => ($row->published ? 'unpublish' : 'publish'),
                                         'current_id' => $row->id,
                                       ), $params['page_url']);
          $duplicate_url = add_query_arg(array( 'task' => 'duplicate', 'current_id' => $row->id ), $params['page_url']);
          $delete_url = add_query_arg(array( 'task' => 'delete', 'current_id' => $row->id ), $params['page_url']);
          $preview_url =  WDWLibrary::get_custom_post_permalink( array('slug' => $row->slug, 'post_type' => 'gallery' ));
          $preview_image = BWG()->plugin_url . '/images/no-image.png';
          if ( !empty($row->preview_image) ) {
            $preview_image = BWG()->upload_url . $row->preview_image;
          }
          if ( !empty($row->random_preview_image)) {
            $preview_image = BWG()->upload_url . $row->random_preview_image;
            if ( WDWLibrary::check_external_link($row->random_preview_image) ) {
              $preview_image = $row->random_preview_image;
            }
          }
          ?>
          <tr id="tr_<?php echo $row->id; ?>" <?php echo $alternate; ?>>
            <th class="connectedSortable col_drag handles ui-sortable-handle">
              <div title="<?php _e('Drag to re-order', 'photo-gallery'); ?>" class="wd-drag handle dashicons dashicons-move"></div>
              <input class="wd-id" id="id_input_<?php echo $row->id; ?>" name="id_input_<?php echo $row->id; ?>" type="hidden" size="1" value="<?php echo $row->id; ?>" />
              <input class="wd-order" id="order_input_<?php echo $row->id; ?>" name="order_input_<?php echo $row->id; ?>" type="hidden" size="1" value="<?php echo $row->order; ?>" />
            </th>
            <th class="check-column">
              <input type="checkbox" id="check_<?php echo $row->id; ?>" name="check[<?php echo $row->id; ?>]" onclick="spider_check_all(this)" />
            </th>
            <td class="column-primary column-title" data-colname="<?php _e('Title', 'photo-gallery'); ?>">
              <strong class="has-media-icon">
                <a href="<?php echo $edit_url; ?>">
                  <span class="media-icon image-icon">
                    <img class="preview-image" title="<?php echo $row->name; ?>" src="<?php echo $preview_image; ?>" width="60" height="60" />
                  </span>
                  <?php echo $row->name; ?>
                </a>
                <?php if ( !$row->published ) { ?>
                  — <span class="post-state"><?php _e('Unpublished', 'photo-gallery'); ?></span>
                <?php } ?>
              </strong>
              <div class="row-actions">
                <span><a href="<?php echo $edit_url; ?>"><?php _e('Edit', 'photo-gallery'); ?></a> |</span>
                <span><a href="<?php echo $publish_url; ?>"><?php echo($row->published ? __('Unpublish', 'photo-gallery') : __('Publish', 'photo-gallery')); ?></a> |</span>
                <span class="trash"><a onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', 'photo-gallery')); ?>')) {return false;}" href="<?php echo $delete_url; ?>"><?php _e('Delete', 'photo-gallery'); ?></a> |</span>
                <span><a href="<?php echo $duplicate_url; ?>"><?php _e('Duplicate', 'photo-gallery'); ?></a> |</span>
                <span><a target="_blank" href="<?php echo $preview_url; ?>"><?php _e('Preview', 'photo-gallery'); ?></a></span>
              </div>
              <button class="toggle-row" type="button">
                <span class="screen-reader-text"><?php _e('Show more details', 'photo-gallery'); ?></span>
              </button>
            </td>
            <td class="col_count" data-colname="<?php _e('Images count', 'photo-gallery'); ?>"><?php echo $row->images_count; ?></td>
            <td data-colname="<?php _e('Author', 'photo-gallery'); ?>" class="col-author"><?php echo ($user) ? $user->display_name : ''; ?></td>
          </tr>
          <?php
        }
      }
      else {
        echo WDWLibrary::no_items('galleries', 3);
      }
      ?>
      </tbody>
    </table>
    <div class="tablenav bottom">
      <?php echo $this->pagination($params['page_url'], $params['total'], $params['items_per_page']); ?>
    </div>
    <input type="hidden" id="ids_exclude" name="ids_exclude" value="" />
    <?php
  }

  /**
   * Add/Edit.
   *
   * @param  array $params
   *
   * @return string html
   */
  public function edit( $params = array() ) {
    wp_enqueue_style('thickbox');
    wp_enqueue_script('thickbox');
    wp_enqueue_media();
    wp_enqueue_style('jquery-ui-tooltip');
    wp_enqueue_script('jquery-ui-tooltip');
    wp_enqueue_script(BWG()->prefix . '_embed');
    ob_start();
    echo $this->edit_body( $params );

    // Pass the content to form.
    $form_attr = array(
      'id' => BWG()->prefix . '_gallery',
      'name' => BWG()->prefix . '_galleries',
      'class' => BWG()->prefix . '_galleries wd-form',
      'action' => $params['form_action'],
      'current_id' => $params['id'],
    );

    echo $this->form(ob_get_clean(), $form_attr);
  }

  /**
   * Generate page edit body.
   *
   * @param $params
   */
  public function edit_body( $params = array() ) {
    add_action('bwg_call_how_to', array($this, 'get_how_to'), 10, 2);
    $row = $params['row'];
    $current_id = $params['id'];
    $enable_wp_editor = isset(BWG()->options->enable_wp_editor) ? BWG()->options->enable_wp_editor : 0;

    /* Preview Section - set galleries types */
    $demo_url_for_free = "https://demo.10web.io/photo-gallery/";

    $get_gallery_types = array(
			'thumbnails' 	=> array(
				'title' => __('Thumbnails', 'photo-gallery'),
				'preview_url' => add_query_arg('bwg-preview-type', 'thumbnails', $params['preview_action']),
			),
			'thumbnails_masonry' 	=> array(
				'title' => __('Masonry', 'photo-gallery'),
				'preview_url' => add_query_arg('bwg-preview-type', 'thumbnails_masonry', $params['preview_action']),
		  ),
			'thumbnails_mosaic' 	=> array(
				'title' => __('Mosaic', 'photo-gallery'),
				'preview_url' => add_query_arg('bwg-preview-type', 'thumbnails_mosaic', $params['preview_action']),
		  ),
			'slideshow' 	=> array(
				'title' => __('Slideshow', 'photo-gallery'),
				'preview_url' => add_query_arg('bwg-preview-type', 'slideshow', $params['preview_action']),
		  ),
			'image_browser' 	=> array(
				'title' => __('Image Browser', 'photo-gallery'),
				'preview_url' => add_query_arg('bwg-preview-type', 'image_browser', $params['preview_action']),
		  ),
			'blog_style' 	=> array(
				'title' => __('Blog Style', 'photo-gallery'),
				'preview_url' => add_query_arg('bwg-preview-type', 'blog_style', $params['preview_action']),
		  ),
			'carousel' 	=> array(
				'title' => __('Carousel', 'photo-gallery') . (!BWG()->is_pro ? '<span class="pro_btn">' . __('Premium', 'photo-gallery') . '</span>' : ''),
				'preview_url' => !BWG()->is_pro ? $demo_url_for_free.'carousel' : add_query_arg('bwg-preview-type', 'carousel', $params['preview_action']),
		  ),
    );
    ?>
    <div class="gal-msg wd-hide">
      <?php
      if ( isset($params['message']['gallery_message']) ) {
        if ( $params['message']['gallery_message'] ) {
          echo WDWLibrary::message_id(1);
        }
        else {
          echo WDWLibrary::message_id(2);
        }
      }
      ?>
    </div>
    <div id="message_div" class="wd_updated" style="display: none;"></div>
    <div class="bwg-page-header wd-list-view-header">
      <div class="wd-page-title wd-header wd-list-view-header-left">
        <div>
          <h1 class="wp-heading-inline bwg-heading"><?php _e('Gallery title', 'photo-gallery'); ?></h1>
          <input type="text" id="name" name="name" class="bwg_requried" value="<?php echo !empty($row->name) ? esc_html($row->name) : ''; ?>">
        </div>
        <div class="bwg-page-actions">
          <?php
          if ( !BWG()->is_pro ) {
            WDWLibrary::gallery_to_pro_button();
          }
          ?>
					<button class="tw-button-primary button-large" onclick="if (spider_check_required('name', 'Title') ) {return false;};
						spider_set_input_value('task', 'save');
						spider_set_input_value('ajax_task', '');
						spider_set_input_value('bulk-action-selector-top', '-1');
						spider_ajax_save('<?php echo BWG()->prefix . '_gallery'; ?>');return false;">
		  			<?php echo ($params['id']) ? __('Update', 'photo-gallery') : __('Publish', 'photo-gallery'); ?>
					</button>
					<?php /* Preview Section */
					if ( $params['id'] && $params['preview_action'] ) { ?>
						<div class="tw-button-secondary bwg-preview-button " onclick="bwg_preview_section(this);"><?php _e('Preview', 'photo-gallery'); ?></div>
						<div class="bwg-preview-section">
							<p><?php _e('Preview gallery in:', 'photo-gallery'); ?></p>
							<?php  foreach ( $get_gallery_types as $key => $value ) { ?>
								<div class="bwg-preview-gallery-type" onclick="window.open('<?php echo $value['preview_url']; ?>')">
									<div class="bwg-preview-gallery-type-icon"></div>
									<div class="bwg-preview-gallery-type-title">
										<?php echo $value["title"]; ?>
									</div>
								</div>
						 	<?php } ?>
						</div>
					<?php } ?>

					<?php
					if ( $params['shortcode_id'] ) {
						require BWG()->plugin_dir . '/framework/howto/howto.php';
					}
					?>
        </div>
      </div>
      <div class="bwg-clear"></div>
    </div>
    <div class="wd-table meta-box-sortables">
      <div class="wd-table-col wd-table-col-100 wd-table-col-left">
        <div class="wd-box-section">
          <div class="postbox <?php echo $current_id ? 'closed' : '' ?>">
            <button class="button-link handlediv" type="button" aria-expanded="true">
              <span class="screen-reader-text"><?php _e('Toggle panel:', 'photo-gallery'); ?></span>
              <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle">
              <span><?php _e('Basic', 'photo-gallery'); ?></span>
            </h2>
            <div class="inside">
              <div class="wd-box-content">
                <div class="wd-group">
                  <label class="wd-label" for="url"><?php _e('Preview image', 'photo-gallery'); ?> </label>
                  <a href="<?php echo $params['add_preview_image_action']; ?>"
                     class="button thickbox thickbox-preview"
                     id="button_preview_image"
                     title="<?php _e('Add Preview Image', 'photo-gallery'); ?>"
                     onclick="return false;"
                     style="<?php echo !empty($row->preview_image) ? 'display:none;' : '' ?>">
                    <span class="dashicons dashicons-camera"></span><?php _e('Add', 'photo-gallery'); ?>
                  </a>
                  <input type="hidden" id="preview_image" name="preview_image" value="<?php echo $row->preview_image; ?>" style="display: inline-block;" />
                  <img id="img_preview_image"
                       style="<?php echo empty($row->preview_image) ? 'display:none;' : '' ?>"
                       src="<?php echo $row->preview_image ? (BWG()->upload_url . $row->preview_image) : ''; ?>" />
                  <span id="delete_preview_image" class="spider_delete_img dashicons dashicons-no-alt" onclick="spider_remove_url('button_preview_image', 'preview_image', 'delete_preview_image', 'img_preview_image')" style="<?php echo empty($row->preview_image) ? 'display:none;' : '' ?>"></span>
                  <p class="description"><?php _e('Add a preview image, which will be displayed as the cover image of the gallery when it is published in a gallery group.', 'photo-gallery'); ?></p>
                </div>
                <div class="wd-group">
                  <label class="wd-label"><?php _e('Published', 'photo-gallery'); ?></label>
                  <input type="radio" class="inputbox" id="published1" name="published" <?php echo(($row->published) ? 'checked="checked"' : ''); ?> value="1" />
                  <label for="published1"><?php _e('Yes', 'photo-gallery'); ?></label>
                  <input type="radio" class="inputbox" id="published0" name="published" <?php echo(($row->published) ? '' : 'checked="checked"'); ?> value="0" />
                  <label for="published0"><?php _e('No', 'photo-gallery'); ?></label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="wd-table-row wd-table-col-100 wd-table-col-left">
        <div class="wd-box-section">
          <div class="postbox closed">
            <button class="button-link handlediv" type="button" aria-expanded="true">
              <span class="screen-reader-text"><?php _e('Toggle panel:', 'photo-gallery'); ?></span>
              <span class="toggle-indicator" aria-hidden="true"></span>
            </button>
            <h2 class="hndle">
              <span><?php _e('Advanced', 'photo-gallery'); ?></span>
            </h2>
            <div class="inside bwg-flex bwg-flex-wrap bwg-align-items-top">
              <div class="wd-table-col-50">
                <div class="wd-box-section">
                  <div class="wd-box-content">
                    <div class="wd-group">
                      <label class="wd-label" for="author"><?php _e('Author', 'photo-gallery'); ?></label>
                      <span><?php echo $row->author; ?></span>
                    </div>
                    <div class="wd-group">
                      <label class="wd-label" for="slug"><?php _e('Slug', 'photo-gallery'); ?></label>
                      <input type="text" id="slug" name="slug" value="<?php echo urldecode($row->slug); ?>" size="39" />
                      <input type="hidden" id="old_slug" name="old_slug" value="<?php echo urldecode($row->slug); ?>" size="39" />
                    </div>
                    <div class="wd-group">
                      <label class="wd-label" for="description"><?php _e('Description', 'photo-gallery'); ?></label>
                      <div>
                        <?php
                        if ( user_can_richedit() && $enable_wp_editor ) {
                          wp_editor($row->description, 'description', array(
                            'teeny' => TRUE,
                            'textarea_name' => 'description',
                            'media_buttons' => FALSE,
                            'textarea_rows' => 5,
                          ));
                        }
                        else {
                          ?>
                          <textarea cols="36" rows="5" id="description" name="description" class="wd-resize-vertical"><?php echo $row->description; ?></textarea>
                          <?php
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="wd-table-col-50">
                <div class="wd-box-section">
                  <div class="wd-box-content">
                    <div class="wd-group">
                      <label class="wd-label" for="gallery_type"><?php _e('Gallery content type', 'photo-gallery'); ?></label>
                      <select <?php echo ( !empty($params['rows'][0]) ) ? 'disabled' : ''; ?> name="gallery_type" id="gallery_type" onchange="bwg_gallery_type('');">
                        <?php
                        foreach ($params['gallery_types'] as $id => $type) {
                          ?>
                          <option value="<?php echo $id; ?>" <?php echo(($params['gallery_type'] == $id) ? 'selected="selected"' : ''); ?>>
                            <?php echo $type; ?>
                          </option>
                          <?php
                        }
                        ?>
                      </select>
                      <input type="text" id="gallery_type_old" name="gallery_type_old" value="<?php echo $row->gallery_type; ?>" style='display:none;' />
					  <?php if ( empty($params['rows'][0]) ) { ?>
                      <p class="description"><?php _e('Select the type of gallery content. Mixed galleries can include all supported items. Alternatively, you can showcase images from one specific source only.', 'photo-gallery'); ?></p>
					  <?php } else { ?>
                      <p class="description"><?php _e('Gallery type cannot be changed, as it is not empty. If you would like to show images from another source, please create a new gallery.', 'photo-gallery'); ?></p>
					  <?php } ?>
                    </div>
                    <?php do_action('bwg_gallery_type_options', $params); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo $this->image_display($params); ?>
    <div id="loading_div" class="bwg_show"></div>
    <?php
  }

  public function total_size_banner($total_size) {
    ?>
    <div class="bwg-total-size-banner-cont">
      <?php
      if ($total_size) {
        ?>
      <div class="bwg-total-size-banner">
        <div>
          <span class="bwg-total-size-banner-note">
            <b><?php echo sprintf(__("Total size: %s", 'photo-gallery'), $total_size); ?></b>
            <?php esc_html_e('Heavy images negatively affect your website loading time and user experience. Optimize images to get better performance.', 'photo-gallery'); ?>
          </span>
        </div>
        <div>
          <a href="<?php echo esc_url(add_query_arg(array( 'page' => 'twbbwg_photo-gallery' ), admin_url('admin.php'))); ?>" class="bwg-total-size-banner-button">
            <?php esc_html_e('Optimize now', 'photo-gallery'); ?>
          </a>
        </div>
      </div>
        <?php
      }
      ?>
    </div>
    <?php
  }

  public function image_display( $params = array() ) {
    if ( $params['row'] ) {
      $is_google_photos = ($params['row']->gallery_type == 'google_photos') ? TRUE : FALSE;
    }
    $ids_string = '';
    ?>
    <div class="buttons_div_left">
      <a href="<?php echo $params['add_images_action']; ?>" id="add_image_bwg"
         class="button button-primary button-large thickbox thickbox-preview"
         title="<?php _e("Add Images", 'photo-gallery'); ?>"
         style="margin-bottom:5px; <?php if ( $params['gallery_type'] != '' ) { echo 'display:none'; } ?>">
        <?php _e('Add Images', 'photo-gallery'); ?>
      </a>
      <input type="button" id="import_image_bwg" class="button button-secondary button-large" onclick="<?php echo (BWG()->is_demo ? 'alert(\'' . addslashes(__('This option is disabled in demo.', 'photo-gallery')) . '\');' : 'spider_media_uploader(event, true);'); ?>return false;" value="<?php _e("Import from Media Library", 'photo-gallery'); ?>" style="<?php if ( $params['gallery_type'] != '' ) { echo 'display:none';} ?>" />
      <?php
      /*(re?)define ajax_url to add nonce only in admin*/
      ?>
      <script>
        var bwg_ajax_url_nonce = "<?php echo wp_nonce_url(admin_url('admin-ajax.php'), '', 'bwg_nonce'); ?>";
      </script>
      <input id="show_add_embed" class="button button-secondary button-large" title="<?php _e('Embed Media', 'photo-gallery'); ?>" style="<?php if ( $params['gallery_type'] != '' ) { echo 'display:none'; } ?>" type="button" onclick="jQuery('.opacity_add_embed').show(); jQuery('#add_embed_help').hide(); return false;" value="<?php _e('Embed Media', 'photo-gallery'); ?>" />
    </div>
    <div class="clear"></div>
    <?php $this->total_size_banner($params['total_size']); ?>
    <div class="clear"></div>
    <div class="opacity_image_alt opacity_image_description opacity_image_redirect opacity_resize_image opacity_add_embed opacity_image_desc opacity_bulk_embed bwg_opacity_media"
         onclick="
         jQuery('.opacity_image_alt').hide();
         jQuery('.opacity_image_description').hide();
         jQuery('.opacity_image_redirect').hide();
         jQuery('.opacity_add_embed').hide();
         jQuery('.opacity_bulk_embed').hide();
         jQuery('.opacity_resize_image').hide();
         jQuery('.opacity_image_desc').hide();"></div>

    <!-- Media Embed -->
    <div id="add_embed" class="opacity_add_embed bwg_add_embed">
      <input type="text" id="embed_url" name="embed_url" value="" placeholder="<?php _e('Enter YouTube, Vimeo, Flickr or Dailymotion URL here.', 'photo-gallery'); ?>"/>
      <input class="button button-primary button-large" type="button" onclick="if (bwg_get_embed_info('embed_url')) {jQuery('.opacity_add_embed').hide();} return false;" value="<?php _e('Add to gallery', 'photo-gallery'); ?>" />
      <input class="button button-secondary button-large" type="button" onclick="jQuery('.opacity_add_embed').hide(); return false;" value="<?php _e('Cancel', 'photo-gallery'); ?>" />
      <p class="description"></p>
      <br>
      <div class="spider_description">
        <div>
          <p class="spider_description_title"><?php _e('<b>Youtube</b> URL example:', 'photo-gallery'); ?></p>
          <input type="text" value="https://www.youtube.com/watch?v=pA8-5qaMBqM" disabled="disabled">
        </div>
        <div>
          <p class="spider_description_title"><?php _e('<b>Vimeo</b> URL example:', 'photo-gallery'); ?></p>
          <input type="text" value="https://vimeo.com/69726973" disabled="disabled">
        </div>
        <?php
        if ( !empty($params['facebook_embed']['media']) && !empty($params['facebook_embed']['media']['body']) ) {
          echo $params['facebook_embed']['media']['body'];
        }
        ?>
        <div class="row">
          <p class="spider_description_title"><?php _e('<b>Flickr</b> URL example:', 'photo-gallery'); ?></p>
          <input type="text" value="https://www.flickr.com/photos/powerpig/18780957662/in/photostream/" disabled="disabled">
        </div>
        <div class="row">
          <p class="spider_description_title"><?php _e('<b>Dailymotion</b> URL example:', 'photo-gallery'); ?></p>
          <input type="text" value="http://www.dailymotion.com/video/x2w0jzl_cortoons-tv-tropty-episodio-2_fun" disabled="disabled">
        </div>
      </div>
    </div>
    <!-- Resize -->
    <div class="opacity_resize_image bwg_resize_image">
      <div id="resize_cont">
      <?php _e("Resize images to: ", 'photo-gallery'); ?>
      <input type="text" name="image_width" id="image_width" value="1600" /> x
      <input type="text" name="image_height" id="image_height" value="1200" /> px
      <p class="description"><?php _e("The maximum size of resized image.", 'photo-gallery'); ?></p>
      </div>
      <div  id="resize_buttons">
      <input class="button button-primary button-large" type="button" onclick="spider_set_input_value('ajax_task', 'image_resize');
                                                                                 spider_ajax_save('bwg_gallery');
                                                                                 jQuery('.opacity_resize_image').hide();
                                                                                 return false;" value="<?php _e("Resize", 'photo-gallery'); ?>" />
      <input class="button button-secondary button-large" type="button" onclick="jQuery('.opacity_resize_image').hide(); return false;" value="<?php _e("Cancel", 'photo-gallery'); ?>" />
      </div>
    </div>


    <!-- Edit from bulk block alt/title -->
    <div id="add_alt" class="opacity_image_alt bwg_image_desc">

      <div>
        <span class="bwg_popup_label">
          <?php _e('Alt/Title: ', 'photo-gallery'); ?>
        </span>
        <input class="bwg_popup_input" type="text" id="title" name="title" value="" />
        <p class="description"><?php _e('Leave blank and click to "Save changes" to delete Alt/Titles.', 'photo-gallery'); ?></p>
      </div>
      <br>
      <div class="edit_cont_buttons">
        <input class="button button-primary button-large" type="button" onclick="spider_set_input_value('ajax_task', 'image_edit_field');
                                                                                 spider_ajax_save('bwg_gallery');
                                                                                 jQuery('.opacity_image_alt').hide();
                                                                                 return false;" value="<?php _e('Save changes', 'photo-gallery'); ?>" />
        <input class="button button-secondary button-large" type="button" onclick="jQuery('.opacity_image_alt').hide(); return false;" value="<?php echo __('Cancel', 'photo-gallery'); ?>" />
      </div>
    </div>

    <!-- Edit from bulk block redirect url -->
    <div id="add_red_url" class="opacity_image_redirect bwg_image_desc">
      <div>
        <span class="bwg_popup_label">
          <?php _e('Redirect URL: ', 'photo-gallery'); ?>
        </span>
        <input class="bwg_popup_input" type="text" id="redirecturl" name="redirecturl" value="" />
        <p class="description"><?php _e('Leave blank and click to "Save changes" to delete Redirect URLs.', 'photo-gallery'); ?></p>
      </div>
      <br>
      <div class="edit_cont_buttons">
        <input class="button button-primary button-large" type="button" onclick="spider_set_input_value('ajax_task', 'image_edit_field');
                                                                                 spider_ajax_save('bwg_gallery');
                                                                                 jQuery('.opacity_image_redirect').hide();
                                                                                 return false;" value="<?php _e('Save changes', 'photo-gallery'); ?>" />
        <input class="button button-secondary button-large" type="button" onclick="jQuery('.opacity_image_redirect').hide(); return false;" value="<?php echo __('Cancel', 'photo-gallery'); ?>" />
      </div>
    </div>

    <!-- Edit from bulk block description -->
    <div id="add_desc" class="opacity_image_description bwg_image_desc">
      <div>
        <span class="bwg_popup_label">
          <?php _e('Description: ', 'photo-gallery'); ?>
        </span>
        <textarea class="bwg_popup_input" type="text" id="desc" name="desc"></textarea>
        <p class="description"><?php _e('Leave blank and click to "Save changes" to delete Descriptions.', 'photo-gallery'); ?></p>

      </div>
      <br>
      <div class="edit_cont_buttons">
        <input class="button button-primary button-large" type="button" onclick="spider_set_input_value('ajax_task', 'image_edit_field');
                                                                                 spider_ajax_save('bwg_gallery');
                                                                                 jQuery('.opacity_image_description').hide();
                                                                                 return false;" value="<?php _e('Save changes', 'photo-gallery'); ?>" />
        <input class="button button-secondary button-large" type="button" onclick="jQuery('.opacity_image_description').hide(); return false;" value="<?php echo __('Cancel', 'photo-gallery'); ?>" />
      </div>
    </div>
    <div class="ajax-msg wd-hide">
      <?php
      if ( isset($params['message']['image_message']) && $params['message']['image_message'] ) {
        if ( is_int($params['message']['image_message']) ) {
          echo WDWLibrary::message_id($params['message']['image_message']);
        }
        else {
          echo WDWLibrary::message_id(0, $params['message']['image_message']);
        }
      }
      ?>
      <!--Using to get message id in ajax response in spider_ajax_save function  -->
      <input type="hidden" name="bwg_action_last_message" id="bwg_action_last_message" value="<?php echo isset($params['message']['image_message']) ? $params['message']['image_message'] : 0; ?>">
    </div>
    <div class="unsaved-msg wd-hide">
      <?php
      echo WDWLibrary::message_id(0, __('You have unsaved changes.', 'photo-gallery'), 'notice notice-warning');
      ?>
    </div>
    <div class="sorting-msg wd-hide">
      <?php
      echo WDWLibrary::message_id(0, __('This sorting does not affect the published galleries. You can change the ordering on frontend by editing gallery shortcode or Photo Gallery Options.', 'photo-gallery'), 'notice notice-warning');
      ?>
    </div>
    <?php echo $this->search(array('sorting' => true)); ?>
    <div class="tablenav top">
      <?php
      if( !$is_google_photos ) {
          echo $this->bulk_actions($params['actions'], TRUE, 'image_bulk_action');
      }
      echo $this->pagination($params['page_url'], $params['total'], $params['items_per_page']);
      ?>
    </div>
    <table id="images_table" class="images_table adminlist table table-striped wp-list-table widefat fixed pages media">
      <thead class="alternate">
      <?php if( !$is_google_photos ) { ?>
        <td class="col_drag" data-page-number="<?php echo $params['page_num']; ?>">
          <?php if ($params['orderby'] == 'order') { ?>
          <select title="<?php _e('Show order column', 'photo-gallery'); ?>" onchange="wd_showhide_weights(true);return false;">
            <option><?php _e('Drag&Drop', 'photo-gallery'); ?></option>
            <option><?php _e('Numerate', 'photo-gallery'); ?></option>
          </select>
          <?php
          }
          else {
            ?>
            <?php _e('Ordering', 'photo-gallery'); ?>
            <?php
          }
          ?>
        </td>
        <td id="cb" class="column-cb check-column">
          <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Filename', 'photo-gallery'); ?></label>
          <input id="check_all" type="checkbox" />
        </td>
      <?php } ?>
        <td class="col_num">#</td>
        <th class="column-primary column-title"><?php _e('Image', 'photo-gallery'); ?></th>
        <th></th>
      </thead>
      <tbody id="tbody_arr" data-meta="<?php echo BWG()->options->read_metadata; ?>" class="bwg-ordering">
      <?php
      if ( $params['rows'] ) {
        $i = $params['page_num'];
        $alternate = 'alternate';
        foreach ( $params['rows'] as $row ) {
          $alternate = (!isset($alternate) || $alternate == '') ? 'alternate' : '';
          $temp = $row->id == 'tempid' ? TRUE : FALSE;
          $is_embed = preg_match('/EMBED/', $row->filetype) == 1 ? TRUE : FALSE;
          $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/', $row->filetype) == 1 ? TRUE : FALSE;
          $is_oembed_instagram_post = ( $row->filetype == 'EMBED_OEMBED_INSTAGRAM_POST' ) ? TRUE : FALSE;
          $is_facebook_post = ($row->filetype == 'EMBED_OEMBED_FACEBOOK_POST') ? TRUE : FALSE;
          $is_instagram = ( in_array($row->filetype, WDWLibrary::get_instagram_types()) ) ? TRUE : FALSE;
          $fb_post_url = ($is_facebook_post) ? $row->filename : '';
          $instagram_post_width = 'temp_instagram_post_width';
          $instagram_post_height = 'temp_instagram_post_height';
          $link = add_query_arg(array(
            'action' => 'editimage_' . BWG()->prefix,
            'type' => 'display',
            'modified_date' => $row->modified_date,
            'image_url' => urlencode($row->pure_image_url),
            'thumb_url' => urlencode($row->pure_thumb_url),
            'image_id' => $row->id,
            'bwg_width' => '1000',
            'bwg_height' => '500',
            BWG()->nonce => wp_create_nonce('editimage_' . BWG()->prefix),
          ), admin_url('admin-ajax.php'));
          $image_link = add_query_arg(array(
            'type' => 'display',
            'FACEBOOK_POST' => ($temp ? 'tempis_facebook_post' : $is_facebook_post),
            'fb_post_url' => ($temp ? 'tempfb_post_url' : $fb_post_url),
          ), $link);
          if ( $is_embed_instagram_post ) {
            $image_resolution = explode(' x ', $row->resolution);
            if ( is_array($image_resolution) ) {
              $instagram_post_width = $image_resolution[0];
              $instagram_post_height = explode(' ', $image_resolution[1]);
              $instagram_post_height = $instagram_post_height[0];
            }
          }
          $image_link = add_query_arg(array(
						'instagram_post_width' => $instagram_post_width,
						'instagram_post_height' => $instagram_post_height,
					), $image_link);
          $image_link = add_query_arg(array('TB_iframe' => '1'), $image_link);
          $edit_link = add_query_arg(array('type' => 'rotate', 'TB_iframe' => '1'), $link);
          $crop_link = add_query_arg(array('bwg_height' => '600', 'type' => 'crop', 'TB_iframe' => '1'), $link);
          $image_url = ( !$is_embed ? BWG()->upload_url : '') . $row->thumb_url;
          $add_tag_url = add_query_arg(array('image_id' => $row->id, 'TB_iframe' => '1'),  $params['add_tags_action']);
          ?>
          <tr id="tr_<?php echo $row->id; ?>" class="<?php echo $alternate; ?><?php echo $temp ? ' wd-template wd-hide' : ''; ?>">
            <?php if( !$is_google_photos ) { ?>
            <th class="<?php if ($params['orderby'] == 'order') echo 'connectedSortable'; ?> col_drag handles ui-sortable-handle">
              <div title="<?php _e('Drag to re-order', 'photo-gallery'); ?>" class="wd-drag handle dashicons dashicons-move <?php if ($params['orderby'] != 'order') echo 'wd-hide'; ?>"></div>
              <input class="wd-hide wd-order" id="order_input_<?php echo $row->id; ?>" name="order_input_<?php echo $row->id; ?>" type="text" size="1" value="<?php echo $row->order; ?>" />
            </th>
            <th class="check-column">
              <input type="checkbox" id="check_<?php echo $row->id; ?>" name="check[<?php echo $row->id; ?>]" onclick="spider_check_all(this)" />
            </th>
            <?php } ?>
            <th class="col_num"><?php echo $temp ? 'tempnum' : ++$i; ?></th>
            <td class="column-primary column-title" data-colname="<?php _e('Filename', 'photo-gallery'); ?>">
              <strong class="has-media-icon">
                  <span class="bwg-media-icon">
                    <span class="media-icon image-icon">
                      <img id="image_thumb_<?php echo $row->id; ?>"
                           class="preview-image gallery_image_thumb <?php echo $temp ? '' : 'bwg_no_border' ?>"
                          <?php echo $temp ? 'tempthumb_src=""' : ''; ?>
                           data-embed="<?php echo $is_embed; ?>"
                           data-instagram="<?php echo $is_instagram; ?>"
                           data-type="<?php echo $row->filetype; ?>"
                           data-src="<?php echo $temp ? '' : $image_url ?>"
                           title="<?php echo $row->filename; ?>"
                           alt="<?php echo $row->filename; ?>"/>

                    </span>
                    <?php
                    if ( isset($row->size) && $row->size ) {
                      ?>
                    <span class="bwg-total-size">
                      <span><?php echo sprintf(__("Total size: %s", 'photo-gallery'), '<b>' . $row->size . '</b>'); ?></span>
                      <a
                        href="<?php echo(BWG()->is_demo ? 'javascript:alert(\'' . addslashes(__('This option is disabled in demo.', 'photo-gallery')) . '\');' : $params['booster_page_url']); ?>">
                        <?php _e('Optimize now', 'photo-gallery'); ?>
                      </a>
                    </span>
                      <?php
                    }
                    ?>
                  </span>
                  <?php if (!$is_oembed_instagram_post) { ?>
                  <a class="thickbox thickbox-preview" onclick="jQuery('#loading_div').show();"
                     href="<?php echo $image_link; ?>">
                    <?php } ?>
                    <?php echo $row->filename; ?><?php if (!$is_oembed_instagram_post) { ?>
                  </a>
                  <?php } ?>
                  <i class="wd-info dashicons dashicons-info" data-id="wd-info-<?php echo $row->id; ?>"></i>
                  <div id="wd-info-<?php echo $row->id; ?>" class="wd-hide">
                    <p><?php echo __("Date", 'photo-gallery') . ': ' . ($temp ? $row->date : date("d F Y, H:i", strtotime($row->date))); ?></p>
                    <p><?php echo __("Resolution", 'photo-gallery') . ': ' . $row->resolution; ?></p>
                    <?php
                    if (isset($row->size) && $row->size) {
                      ?>
                    <p><?php echo __("Size", 'photo-gallery') . ': ' . $row->size; ?></p>
                      <?php
                    }
                    ?>
                    <p><?php echo __("Type", 'photo-gallery') . ': ' . $row->filetype; ?></p>
                  </div>

                <?php if ( !$row->published ) { ?>
                  — <span class="post-state"><?php _e('Unpublished', 'photo-gallery'); ?></span>
                <?php } ?>
              </strong>
                <?php if( !$is_google_photos) {
                  $svg_check = strpos(strtolower($row->filetype), 'svg') > -1 ? true : false;
                  ?>
              <div class="row-actions">
                <span class="wd-image-actions <?php echo ( !$svg_check && !$is_embed && ( $params['gallery_type'] == '' ) ? '' : ' wd-hide' ); ?>"><a class="<?php echo (BWG()->is_demo || !BWG()->wp_editor_exists ? '' : 'thickbox thickbox-preview'); ?>" href="<?php echo (BWG()->is_demo ? 'javascript:alert(\'' . addslashes(__('This option is disabled in demo.', 'photo-gallery')) . '\');' : (BWG()->wp_editor_exists ? $edit_link : 'javascript:alert(\'' . addslashes(__('Image edit functionality is not supported by your web host.', 'photo-gallery')) . '\');')); ?>"><?php _e('Edit', 'photo-gallery'); ?></a> |</span>
                <span class="wd-image-actions <?php echo ( !$svg_check && !$is_embed && ( $params['gallery_type'] == '' ) ? '' : ' wd-hide' ); ?>"><a class="<?php echo (BWG()->is_demo || !BWG()->wp_editor_exists ? '' : 'thickbox thickbox-preview'); ?>" href="<?php echo (BWG()->is_demo ? 'javascript:alert(\'' . addslashes(__('This option is disabled in demo.', 'photo-gallery')) . '\');' : (BWG()->wp_editor_exists ? $crop_link : 'javascript:alert(\'' . addslashes(__('Image edit functionality is not supported by your web host.', 'photo-gallery')) . '\');')); ?>"><?php _e('Crop Thumbnail', 'photo-gallery'); ?></a> |</span>
                <span class="wd-image-actions <?php echo ( !$svg_check && !$is_embed && ( $params['gallery_type'] == '' ) ? '' : ' wd-hide' ); ?>"><a onclick="<?php echo (BWG()->is_demo ? 'alert(\'' . addslashes(__('This option is disabled in demo.', 'photo-gallery')) . '\');' : 'if (confirm(\'' . addslashes(__('Do you want to reset the image?', 'photo-gallery')) . '\')) { spider_set_input_value(\'ajax_task\', \'image_reset\'); spider_set_input_value(\'image_current_id\', \'' . $row->id . '\'); spider_ajax_save(\'bwg_gallery\'); } return false;'); ?>"><?php _e('Reset', 'photo-gallery'); ?></a> |</span>
                <span><a onclick="spider_set_input_value('ajax_task', 'image_<?php echo $row->published ? 'unpublish' : 'publish'; ?>');
                    spider_set_input_value('image_current_id', '<?php echo $row->id; ?>');
                    spider_ajax_save('bwg_gallery');"><?php echo($row->published ? __('Unpublish', 'photo-gallery') : __('Publish', 'photo-gallery')); ?></a> |</span>
                <span class="trash"><a onclick="if (confirm('<?php echo addslashes(__('Do you want to delete selected item?', 'photo-gallery')); ?>')) {
                    spider_set_input_value('ajax_task', 'image_delete');
                    spider_set_input_value('image_current_id', '<?php echo $row->id; ?>');
                    spider_ajax_save('bwg_gallery');
                    } else {
                    return false;
                    }"><?php _e('Delete', 'photo-gallery'); ?></a></span>
              </div>
                <?php } ?>
              <button class="toggle-row" type="button">
                <span class="screen-reader-text"><?php _e('Show more details', 'photo-gallery'); ?></span>
              </button>
            </td>
            <td class="column-data">
              <div class="bwg-td-container">
                <div class="bwg-td-item">
                  <label class="wd-table-label" for="image_alt_text_<?php echo $row->id; ?>"><?php _e('Alt/Title', 'photo-gallery'); ?></label>
                  <textarea rows="4" id="image_alt_text_<?php echo $row->id; ?>" <?php disabled($is_google_photos) ?> name="image_alt_text_<?php echo $row->id; ?>"><?php echo $row->alt; ?></textarea>
                </div>
                <div class="bwg-td-item">
                  <label class="wd-table-label" for="image_description_<?php echo $row->id; ?>"><?php _e('Description', 'photo-gallery'); ?></label>
                  <textarea rows="4" id="image_description_<?php echo $row->id; ?>" <?php disabled($is_google_photos) ?> name="image_description_<?php echo $row->id; ?>"><?php echo $row->description; ?></textarea>
                </div>
                <?php
                  if ( function_exists('BWGEC') ) {
                    $priselist_name = $row->priselist_name ? "Pricelist: " . $row->priselist_name : "Not for sale";
                    $unset = $priselist_name == "Not for sale" ? "" : " <span onclick='bwg_remove_pricelist(this);' data-image-id= '" . $row->id . "' data-pricelist-id='" . $row->pricelist_id . "' class ='spider_delete_img_small' style='margin-top: -2px;margin-left: 3px;'></span>";
                    echo "<div class=\"bwg-td-item\"><div><strong>" . $priselist_name . " </strong>" . $unset . "</div>";
                    $not_set_text = $row->not_set_items == 1 ? __('Selected pricelist item longest dimension greater than some original images dimensions.', 'photo-gallery') : "";
                    echo "<small id='priselist_set_error" . $row->id . "' style='color:#B41111;' >" . $not_set_text . "</small>";
                    echo "<input type='hidden' id='pricelist_id_" . $row->id . "' value='" . $row->pricelist_id . "'></div>";
                  }
                  ?>
                <?php
                $tags_id_string = '';
                if( !$is_google_photos ) {
				?>
                <div class="bwg-td-item bwg-td-item-redirect-url">
                  <label class="wd-table-label" for="redirect_url_<?php echo $row->id; ?>"><?php _e('Redirect URL', 'photo-gallery'); ?></label>
                  <i class="wd-info dashicons dashicons-info" data-id="wd-info-redirect"></i>
                  <div id="wd-info-redirect" class="wd-hide">
                    <p><?php
                      $link = '<a target="_blank" href="'.add_query_arg(array('page' => 'options_bwg'), admin_url('admin.php')).'">'. __('Options > General', 'photo-gallery') . '</a>';
                      echo sprintf(__('To activate this feature, go to %s, then set "Image click action" to "Redirect to URL". Please use absolute URLs when specifying the links.', 'photo-gallery'), $link);
                      ?>
                    </p>
                  </div>
                  <textarea rows="4" onkeypress="prevent_new_line(event)" class="bwg_redirect_url" id="redirect_url_<?php echo $row->id; ?>" name="redirect_url_<?php echo $row->id; ?>"><?php echo $row->redirect_url; ?></textarea>
                </div>
                <div class="bwg-td-item bwg-td-item-tags">
                  <label class="wd-table-label"><?php _e('Tags', 'photo-gallery'); ?></label>
                  <div class="tags_div<?php echo count($row->tags) > 1 ? '' : ' tags_div_empty'; ?>">
                    <?php
                    if ( $row->tags ) {
                      ?>
                      <div id="tags_div_<?php echo $row->id; ?>">
                      <?php
                      foreach ( $row->tags as $row_tag_data ) {
                        ?>
                        <div class="tag_div<?php echo $row_tag_data->term_id == 'temptagid' ? ' wd-tag-template wd-hide' : ''; ?>" id="<?php echo $row->id; ?>_tag_<?php echo $row_tag_data->term_id; ?>">
                          <span class="tag_name"><?php echo $row_tag_data->name; ?></span>
                          <span class="dashicons dashicons-no-alt wd-delete-tag" title="<?php _e('Remove tag', 'photo-gallery'); ?>" onclick="bwg_remove_tag('<?php echo $row_tag_data->term_id; ?>', '<?php echo $row->id; ?>')" />
                        </div>
                        <?php
                        $tags_id_string .= ($row_tag_data->term_id == 'temptagid' ? '' : ($row_tag_data->term_id . ','));
                      }
                      ?>
                      </div>
                      <?php
                    }
                    ?>
                    <a onclick="jQuery('#loading_div').show();" href="<?php echo $add_tag_url; ?>" class="thickbox thickbox-preview"><span class="dashicons dashicons-plus"></span><?php _e('Add tag', 'photo-gallery'); ?></a>
                  </div>
                </div>
                <?php } ?>
                <input type="hidden" value="<?php echo $tags_id_string; ?>" id="tags_<?php echo $row->id; ?>" name="tags_<?php echo $row->id; ?>" />
                <input type="hidden" value="<?php echo $tags_id_string; ?>" id="deleted_tags_<?php echo $row->id; ?>" name="deleted_tags_<?php echo $row->id; ?>" />
                <input type="hidden" id="image_url_<?php echo $row->id; ?>" name="image_url_<?php echo $row->id; ?>" value="<?php echo $row->pure_image_url; ?>" />
                <input type="hidden" id="thumb_url_<?php echo $row->id; ?>" name="thumb_url_<?php echo $row->id; ?>" value="<?php echo $row->pure_thumb_url; ?>" />
                <input type="hidden" id="input_filename_<?php echo $row->id; ?>" name="input_filename_<?php echo $row->id; ?>" value="<?php echo $row->filename; ?>" />
                <input type="hidden" id="input_date_modified_<?php echo $row->id; ?>" name="input_date_modified_<?php echo $row->id; ?>" value="<?php echo $row->date; ?>" />
                <input type="hidden" id="input_resolution_<?php echo $row->id; ?>" name="input_resolution_<?php echo $row->id; ?>" value="<?php echo $row->resolution; ?>" />
                <input type="hidden" id="input_resolution_thumb_<?php echo $row->id; ?>" name="input_resolution_thumb_<?php echo $row->id; ?>" value="<?php echo $row->resolution_thumb; ?>" />
                <input type="hidden" id="input_size_<?php echo $row->id; ?>" name="input_size_<?php echo $row->id; ?>" value="<?php echo $row->size; ?>" />
                <input type="hidden" id="input_filetype_<?php echo $row->id; ?>" name="input_filetype_<?php echo $row->id; ?>" value="<?php echo $row->filetype; ?>" />
              </div>
            </td>
          </tr>
          <?php
          $ids_string .= $temp ? '' : ($row->id . ',');
        }
        if (  count($params['rows']) <= 1 ) {
          echo WDWLibrary::no_items('images', 5);
        }
      }
      ?>
      </tbody>
    </table>
    <?php $paged = WDWLibrary::get('paged', 1, 'intval'); ?>
    <div class="wd-hidden-values">
	  <input type="hidden" value="<?php echo !empty($params['row']->modified_date) ? $params['row']->modified_date : time() ?>" id="modified_date" name="modified_date" />
      <input type="hidden" id="ids_exclude" name="ids_exclude" value="" />
      <input type="hidden" id="ids_string" name="ids_string" value="<?php echo $ids_string; ?>" />
      <input type="hidden" id="paged" name="paged" value="<?php echo $paged; ?>" />
      <input type="hidden" id="ajax_task" name="ajax_task" value="" />
      <input type="hidden" id="image_current_id" name="image_current_id" value="" />
      <input type="hidden" id="total" name="total" value="<?php echo $params['total']; ?>" />
      <input type="hidden" id="added_tags_id" name="added_tags_id" value="" />
      <input type="hidden" id="added_tags_act" name="added_tags_act" value="" />
      <?php
      if (class_exists('BWGEC')) {
        $query_url = admin_url('admin-ajax.php');
        $query_url = add_query_arg(array(
          'action' => 'add_pricelist',
          'page' => 'pricelists_pge',
          'task' => 'explore',
          'bwg_width' => '785',
          'bwg_height' => '550',
          'TB_iframe' => '1',
        ), $query_url);
      ?>
      <a class="wd-add-pricelist thickbox thickbox-preview wd-hide" href="<?php echo $query_url; ?>"></a>
      <input type="hidden" name="image_pricelist_id" id="image_pricelist_id" />
      <?php } ?>
      <input type="hidden" id="remove_pricelist" value="" />
    </div>
    <a class="wd-add-tags thickbox thickbox-preview wd-hide" href="<?php echo add_query_arg(array('TB_iframe' => '1'),  $params['add_tags_action']); ?>"></a>
    <div class="tablenav bottom">
      <?php echo $this->pagination($params['page_url'], $params['total'], $params['items_per_page']); ?>
    </div>
    <?php
  }
}
