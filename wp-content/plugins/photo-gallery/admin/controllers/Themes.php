<?php
/**
 * Class ThemesController_bwg
 */
class ThemesController_bwg {
  /**
   * @var $model
   */
  private $model;
  /**
   * @var $view
   */
  private $view;
  /**
 * @var string $page
 */
  private $page;
  /**
   * @var string $bulk_action_name
   */
  private $bulk_action_name;
  /**
   * @var int $items_per_page
   */
  private $items_per_page = 20;
  /**
   * @var array $actions
   */
  private $actions = array();

  public function __construct() {
    $this->model = new ThemesModel_bwg();
    $this->view = new ThemesView_bwg();

    $this->page = WDWLibrary::get('page');

    $this->actions = array(
      'duplicate' => array(
        'title' => __('Duplicate', 'photo-gallery'),
        $this->bulk_action_name => __('duplicated', 'photo-gallery'),
      ),
      'delete' => array(
        'title' => __('Delete', 'photo-gallery'),
        $this->bulk_action_name => __('deleted', 'photo-gallery'),
      ),
    );

    $user = get_current_user_id();
    $screen = get_current_screen();
    $option = $screen->get_option('per_page', 'option');
    $this->items_per_page = get_user_meta($user, $option, true);

    if ( empty ( $this->items_per_page) || $this->items_per_page < 1 ) {
      $this->items_per_page = $screen->get_option( 'per_page', 'default' );
    }
  }

  /**
   * Execute.
   */
  public function execute() {
    $task = WDWLibrary::get('task');
    $id = (int) WDWLibrary::get('current_id', 0);
    if ( $task != 'display' && method_exists($this, $task) ) {
      if ( $task != 'add' && $task != 'edit' ) {
        check_admin_referer(BWG()->nonce, BWG()->nonce);
      }
      $action = WDWLibrary::get('bulk_action', -1);
      if ( $action != -1 ) {
        $this->bulk_action($action);
      }
      else {
        $this->$task($id);
      }
    }
    else {
      $this->display();
    }
  }

  /**
   * Display.
   */
  public function display() {
    // Set params for view.
    $params = array();
    $params['page'] = $this->page;
    $params['page_title'] = __('Themes', 'photo-gallery');
    $params['actions'] = $this->actions;
    $params['order'] = WDWLibrary::get('order', 'desc');
    $params['orderby'] = WDWLibrary::get('orderby', 'default_theme');
    // To prevent SQL injections.
    $params['order'] = ($params['order'] == 'desc') ? 'desc' : 'asc';
    if ( !in_array($params['orderby'], array( 'name', 'default_theme' )) ) {
      $params['orderby'] = 'default_theme';
    }
    $params['items_per_page'] = $this->items_per_page;
    $page = (int) WDWLibrary::get('paged', 1);
    $page_num = $page ? ($page - 1) * $params['items_per_page'] : 0;
    $params['page_num'] = $page_num;
    $params['search'] = WDWLibrary::get('s', '');;
    $params['total'] = $this->model->total($params);
    $params['rows_data'] = $this->model->get_rows_data($params);
    $this->view->display($params);
  }

  /**
   * Bulk actions.
   *
   * @param $task
   */
  public function bulk_action($task) {
    $message = 0;
    $successfully_updated = 0;

    $check = WDWLibrary::get('check', '');
    $all = WDWLibrary::get('check_all_items', '');
    $all = ($all == 'on' ? TRUE : FALSE);

    if ( $check ) {
      foreach ( $check as $form_id => $item ) {
        if ( method_exists($this, $task) ) {
          $message = $this->$task($form_id, TRUE, $all);
          if ( $message != 2 ) {
            // Increase successfully updated items count, if action doesn't failed.
            $successfully_updated++;
          }
        }
      }
      if ( $successfully_updated ) {
        $block_action = $this->bulk_action_name;
        $message = sprintf(_n('%s item successfully %s.', '%s items successfully %s.', $successfully_updated, 'photo-gallery'), $successfully_updated, $this->actions[$task][$block_action]);
      }
    }
    WDWLibrary::redirect(add_query_arg(array(
                                         'page' => $this->page,
                                         'task' => 'display',
                                         ($message === 2 ? 'message' : 'msg') => $message,
                                       ), admin_url('admin.php')));
  }

  /**
   * Delete form by id.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   *
   * @return int
   */
  public function delete( $id, $bulk = FALSE, $all = FALSE ) {
    $isDefault = $this->model->get_default($id);
    if ( $isDefault ) {
      $message = 4;
    }
    else {
      global $wpdb;
      $where = ($all ? '' : ' WHERE id=%d');
      if( $where != '' ) {
          $delete = $wpdb->query($wpdb->prepare('DELETE FROM `' . $wpdb->prefix . 'bwg_theme`' . $where, $id));
      } else {
          $delete = $wpdb->query('DELETE FROM `' . $wpdb->prefix . 'bwg_theme`' . $where);
      }
      if ( $delete ) {
        $message = 3;
      }
      else {
        $message = 2;
      }
    }
    if ( $bulk ) {
      return $message;
    }
    WDWLibrary::redirect( add_query_arg( array('page' => $this->page, 'task' => 'display', 'message' => $message), admin_url('admin.php') ) );
  }

  /**
   * Duplicate by id.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   *
   * @return int
   */
  public function duplicate( $id, $bulk = FALSE, $all = FALSE ) {
    $message = 2;
    $table = 'bwg_theme';
    $row = $this->model->select_rows("get_row", array(
      "selection" => "*",
      "table" => $table,
      "where" => "id=" . (int) $id,
    ));
    if ( $row ) {
      $row = (array) $row;
      unset($row['id']);
      $row['default_theme'] = 0;
      $inserted = $this->model->insert_data_to_db($table, (array) $row);
      if ( $inserted !== FALSE ) {
        $message = 11;
      }
    }
    if ( $bulk ) {
      return $message;
    }
    else {
      WDWLibrary::redirect(add_query_arg(array(
                                                  'page' => $this->page,
                                                  'task' => 'display',
                                                  'message' => $message,
                                                ), admin_url('admin.php')));
    }
  }

  /**
   * Set default.
   *
   * @param      $id
   * @param bool $bulk
   * @param bool $all
   */
  public function setdefault( $id, $bulk = FALSE, $all = FALSE ) {
    $this->model->update( array( 'default_theme' => 0 ), array( 'default_theme' => 1 ) );
    $save = $this->model->update( array( 'default_theme' => 1 ), array( 'id' => $id ) );
    if ( $save !== FALSE ) {
      $message = 7;
    }
    else {
      $message = 2;
    }
    $page = WDWLibrary::get('page');
    WDWLibrary::redirect(add_query_arg(array(
                                                'page' => $page,
                                                'task' => 'display',
                                                'message' => $message,
                                              ), admin_url('admin.php')));
  }

  /**
   * Add.
   *
   * @param int  $id
   */
	public function add( $id = 0 ) {
		$this->edit(0);
	}

  /**
   * Edit by id.
   *
   * @param int  $id
   * @param bool $bulk
   */
  public function edit( $id = 0, $bulk = FALSE ) {
    $reset = WDWLibrary::get('reset', FALSE);
    $active_tab = WDWLibrary::get('active_tab', 'Thumbnail');
    // Get Theme data.
    $row = $this->model->get_row_data($id, $reset, $active_tab);
		$current_type = WDWLibrary::get('current_type', 'Thumbnail');
		$form_action  = add_query_arg( array(
                                'page' => 'themes_' . BWG()->prefix,
								                'current_id' => $id,
                                BWG()->nonce => wp_create_nonce(BWG()->nonce),
							), admin_url('admin.php') );

		$tabs = array(
			'Thumbnail' => __('Thumbnail', 'photo-gallery'),
			'Masonry' => __('Masonry', 'photo-gallery'),
			'Mosaic' => __('Mosaic', 'photo-gallery'),
			'Slideshow' => __('Slideshow', 'photo-gallery'),
			'Image_browser' => __('Image browser', 'photo-gallery'),
			'Compact_album' => __('Compact album', 'photo-gallery'),
			'Masonry_album' => __('Masonry album', 'photo-gallery'),
			'Extended_album' => __('Extended album', 'photo-gallery'),
			'Blog_style' => __('Blog style', 'photo-gallery'),
			'Lightbox' => __('Lightbox', 'photo-gallery'),
			'Navigation' => __('Navigation', 'photo-gallery'),
			'Carousel' => __('Carousel', 'photo-gallery'),
			'Tags' => __('Tags', 'photo-gallery'),
		);

		$border_styles = array(
			'none' => __('None', 'photo-gallery'),
			'solid' => __('Solid', 'photo-gallery'),
			'dotted' => __('Dotted', 'photo-gallery'),
			'dashed' => __('Dashed', 'photo-gallery'),
			'double' => __('Double', 'photo-gallery'),
			'groove' => __('Groove', 'photo-gallery'),
			'ridge' => __('Ridge', 'photo-gallery'),
			'inset' => __('Inset', 'photo-gallery'),
			'outset' => __('Outset', 'photo-gallery'),
		);

		$google_fonts = WDWLibrary::get_google_fonts();
		$font_families = array(
			'arial' => 'Arial',
			'lucida grande' => 'Lucida grande',
			'segoe ui' => 'Segoe ui',
			'tahoma' => 'Tahoma',
			'trebuchet ms' => 'Trebuchet ms',
			'verdana' => 'Verdana',
			'cursive' =>'Cursive',
			'fantasy' => 'Fantasy',
			'monospace' => 'Monospace',
			'serif' => 'Serif',
		);

		$aligns = array(
			'left' 	=> __('Left', 'photo-gallery'),
			'center' 	=> __('Center', 'photo-gallery'),
			'right' 	=> __('Right', 'photo-gallery'),
		);

		$font_weights = array(
			'lighter' => __('Lighter', 'photo-gallery'),
			'normal' => __('Normal', 'photo-gallery'),
			'bold' => __('Bold', 'photo-gallery'),
		);

		// ToDO: Remove after global update.
		$hover_effects = array(
			'none' => __('None', 'photo-gallery'),
			'rotate' => __('Rotate', 'photo-gallery'),
			'scale' => __('Scale', 'photo-gallery'),
			'skew' => __('Skew', 'photo-gallery'),
		);

		$thumbnail_hover_effects = array(
		  'none' => __('None', 'photo-gallery'),
		  'rotate' => __('Rotate', 'photo-gallery'),
		  'scale' => __('Scale', 'photo-gallery'),
		  'zoom' => __('Zoom', 'photo-gallery'),
		  'skew' => __('Skew', 'photo-gallery'),
		);

		$button_styles = array(
			'bwg-icon-angle' => __('Angle', 'photo-gallery'),
			'bwg-icon-chevron' => __('Chevron', 'photo-gallery'),
			'bwg-icon-double' => __('Double', 'photo-gallery'),
		);

		$rate_icons = array(
			'star' => __('Star', 'photo-gallery'),
			'bell' => __('Bell', 'photo-gallery'),
			'circle' => __('Circle', 'photo-gallery'),
			'flag' => __('Flag', 'photo-gallery'),
			'heart' => __('Heart', 'photo-gallery'),
			'square' => __('Square', 'photo-gallery'),
		);

		$params = array(
			'id' => $id,
			'row' => $row,
			'reset' => $reset,
			'form_action' => $form_action,
			'tabs' => $tabs,
			'current_type' => $current_type,
			'border_styles' => $border_styles,
			'google_fonts' => $google_fonts,
			'font_families' => $font_families,
			'aligns' => $aligns,
			'font_weights' => $font_weights,
			'hover_effects' => $hover_effects,
			'thumbnail_hover_effects' => $thumbnail_hover_effects,
			'button_styles' => $button_styles,
			'rate_icons' => $rate_icons,
      'active_tab' => $active_tab,
		);
		$this->view->edit( $params );
	}


  /**
   * Reset by id.
   *
   * @param int $id
   */
  public function reset( $id = 0 ) {
    $active_tab = WDWLibrary::get('active_tab', 'Thumbnail');
    WDWLibrary::redirect(add_query_arg(array(
                                         'page' => $this->page,
                                         'task' => 'edit',
                                         'current_id' => $id,
                                         'active_tab' => $active_tab,
                                         'reset' => '1',
                                       ), admin_url('admin.php')));
  }

  /**
   * Save by id.
   *
   * @param int $id
   */
  public function save( $id = 0 ) {
    $data = $this->save_db($id);
    $active_tab = WDWLibrary::get('active_tab','Thumbnail');
    $page = WDWLibrary::get('page');
    $query_url = wp_nonce_url(admin_url('admin.php'), 'themes_bwg', 'bwg_nonce');
    $query_url = add_query_arg(array(
                                 'page' => $page,
                                 'task' => 'edit',
                                 'current_id' => $data['id'],
                                 'active_tab' => $active_tab,
                                 'message' => $data['msg'],
                               ), $query_url);
    WDWLibrary::spider_redirect($query_url);
  }

  /**
   * Save db by id.
   *
   * @param  int $id
   *
   * @return int $message_id
   */
	public function save_db( $id = 0 ) {
		global $wpdb;
    $row = new WD_BWG_Theme($id);
    $theme_name = WDWLibrary::get('name', 'Theme');
    foreach ($row as $name => $value) {
      $name_var = $name;
      if ( WD_BWG_Theme::font_style($name) ) {
        if ( $_POST[WD_BWG_Theme::font_style($name)] != '1' ) {
          $name_var = $name . '_default';
        }
      }
      $post_name = WDWLibrary::get($name_var);

      if ( !in_array($name, array('id', 'name', 'default_theme')) && ( isset($post_name) ) ) {
        $row->$name = $post_name;
      }
    }
    $themes = json_encode($row);

    if ( $id == 0 ) {
      $save = $wpdb->insert($wpdb->prefix . 'bwg_theme', array(
        'name' => $theme_name,
        'options' => $themes,
        'default_theme' => 0,
      ), array('%s','%s','%d'));
      $id = $wpdb->insert_id;
    }
    else {
      $save = $wpdb->update($wpdb->prefix . 'bwg_theme', array(
        'name' => $theme_name,
        'options' => $themes,
      ), array( 'id' => $id ), array('%s','%s'), array('%d'));
    }
    $message_id = 2;
    if ( $save !== FALSE ) {
      $message_id = 1;
    }

    return array( 'id' => $id, 'msg' => $message_id );
  }
}
