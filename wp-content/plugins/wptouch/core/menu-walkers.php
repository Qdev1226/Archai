<?php
$theme = get_option( 'stylesheet' );
add_action( 'update_option_theme_mods_' . $theme, 'wptouch_save_menu_changes', 10, 2 );

function wptouch_save_menu_changes( $old_value, $new_value ) {
	if ( isset( $new_value[ 'nav_menu_locations' ] ) ) {
		global $wptouch_pro;
		$menu_map = $new_value[ 'nav_menu_locations' ];

		if ( is_array( $menu_map ) && count( (array) $menu_map ) > 0 ) {
			foreach ( $menu_map as $location => $menu_id ) {
				if ( strstr( $location, 'wptouch_') ) {
					$menu_data = wp_get_nav_menu_object( $menu_id );
					$location = substr( $location, 8 );

					global $wptouch_pro;
					$menu_domain = false;
					foreach( $wptouch_pro->theme_menus as $menu ) {
						if ( $menu->setting_name == $location ) {
							$menu_domain = $menu->settings_domain;
							continue;
						}
					}

					if ( $menu_domain ) {
						$settings = wptouch_get_settings( $menu_domain );
						$settings->{ $location } = $menu_id;
						$settings->save();
					}
				}
			}
		}

		wptouch_customizer_begin_theme_override();
		set_theme_mod( 'nav_menu_locations', $menu_map );
		wptouch_customizer_end_theme_override();
	}
}

add_action( 'wptouch_after_reset_settings', 'wptouch_deregister_wordpress_menus' );
add_action( 'wptouch_after_self_destruct', 'wptouch_deregister_wordpress_menus' );
function wptouch_deregister_wordpress_menus() {
	global $wptouch_pro;

	$menu_map = get_theme_mod( 'nav_menu_locations' );
	foreach( $wptouch_pro->theme_menus as $menu ) {
		$location = $menu->setting_name;
		unset( $menu_map[ 'wptouch_' . $location ] );
	}
	set_theme_mod( 'nav_menu_locations', $menu_map );
}

add_action( 'admin_init', 'wptouch_initialize_wordpress_menus' );
function wptouch_initialize_wordpress_menus() {
	if ( !get_option( 'wptouch_menus_initialized' ) ) {
		global $wptouch_pro;

		foreach( $wptouch_pro->theme_menus as $menu ) {
			$location = $menu->setting_name;
			$menu_domain = $menu->settings_domain;
			$settings = wptouch_get_settings( $menu_domain );

			if ( $settings->{ $location } == 'wp' ) {
				$menu_id = wptouch_migrate_modded_menu();
				$settings->{ $location } = $menu_id;
				$settings->save();
			}
		}

		$menu_map = get_theme_mod( 'nav_menu_locations' );
		foreach( $wptouch_pro->theme_menus as $menu ) {
			$location = $menu->setting_name;
			$menu_domain = $menu->settings_domain;
			$settings = wptouch_get_settings( $menu_domain );
			if ( $settings->{ $location } && $settings->{ $location } != '' ) {
				$menu_map[ 'wptouch_' . $location ] = $settings->{ $location };
			}
		}
		set_theme_mod( 'nav_menu_locations', $menu_map );
		add_option( 'wptouch_menus_initialized', true );
	}
}

function wptouch_migrate_modded_menu() {
	$menu_name = 'WPtouch - Recovered Page Menu';
	$menu_object = wp_get_nav_menu_object( $menu_name );

	if ( !$menu_object ) {
		$menu_id = wp_create_nav_menu($menu_name);

		$pages = get_pages( array( 'post_status' => 'publish', 'sort_column' => 'menu_order, post_title' ));
		$menu_items = array();
		$skipped_pages = array();

		foreach ( $pages as $page ) {
			if ( wptouch_menu_is_disabled( $page->ID ) ) {
				$skipped_pages[] = $page->ID;
			} elseif ( !in_array( $page->post_parent, $skipped_pages ) ) {
				$parent_id = 0;
				if ( isset( $menu_items[ $page->post_parent ] ) ) {
					$parent_id = $menu_items[ $page->post_parent ];
				}

				$args = array(
					'menu-item-db-id' => 0,
					'menu-item-object-id' => $page->ID,
					'menu-item-object' => 'page',
					'menu-item-type'  => 'post_type',
					'menu-item-parent-id' => $parent_id,
					'menu-item-status' => 'publish'
				);

				$menu_items[ $page->ID ] = wp_update_nav_menu_item ( $menu_id, 0, $args );

				$menu_icon = get_post_meta( $page->ID, '_wptouch_pro_menu_item_icon', true );
				if ( $menu_icon ) {
					add_post_meta( $menu_items[ $page->ID ], '_wptouch_pro_menu_item_icon', $menu_icon );
				}
			}
		}
	} else {
		$menu_id = $menu_object->ID;
	}

	return ( $menu_id );
}


add_action( 'in_admin_header', 'wptouch_output_menu_icon_packs' );

function wptouch_output_menu_icon_packs() {
	global $current_screen;

	if ( $current_screen->base != 'nav-menus' || $current_screen->get_help_tab( 'locations-overview' ) != null ) {
		return false;
	}

	require_once( WPTOUCH_DIR . '/core/admin-icons.php' );
	if (wptouch_have_icon_packs() ) {
	?>
		<div id="icon-picker">
			<div id="icon-packs">
			<?php while ( wptouch_have_icon_packs() ) { wptouch_the_icon_pack(); ?>

			<div class="pack" id="pack-<?php echo esc_html(wptouch_get_icon_pack_class_name()); ?>">
		  		<?php if ( wptouch_have_icons( wptouch_get_icon_pack_name() ) ) { ?>
					<h3><?php echo esc_html(wptouch_get_icon_pack_name()); ?></h3>
					<ul>
					<?php while ( wptouch_have_icons( wptouch_get_icon_pack_name() ) ) { ?>
						<?php wptouch_the_icon(); ?>
						<li>
							<img src="<?php wptouch_the_icon_url(); ?>" alt="icon" />
						</li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div>
		<?php } ?>
			</div>
		</div>
	<?php
	}
}

add_action( 'wp_update_nav_menu_item', 'wptouch_save_menu_icon', 10, 3 );

function wptouch_save_menu_icon( $menu_id, $menu_item_db_id, $args ) {
	if ( isset( $_POST[ 'menu-item-icon' ] ) && isset( $_POST[ 'menu-item-icon' ][ $menu_item_db_id ] ) ) {
		$image_file = str_replace( wptouch_check_url_ssl( site_url() ), '', sanitize_text_field(wp_unslash($_POST[ 'menu-item-icon' ][ $menu_item_db_id ])) );

		if ( substr( $image_file, 0, 2 ) == '//' ) {
			// Fix for sites that implement protocol-relative media URLs, e.g. with Cloudflare plugin
			$image_file = substr( $image_file, 2 );
			$image_file = substr( $image_file, strpos( $image_file, '/' ) );
		}
		update_post_meta( $menu_item_db_id, '_wptouch_pro_menu_item_icon', $image_file );
	}
}

$temp_settings = wptouch_get_settings();
if ( $temp_settings->enable_menu_icons ) {
	add_filter( 'wp_edit_nav_menu_walker', 'wptouch_edit_nav_menu_walker', 9999, 2 );
}

function wptouch_edit_nav_menu_walker( $walker, $menu_id ) {
	return 'WPtouch_Walker_Nav_Menu_Edit';
}

class WPtouch_Walker_Nav_Menu_Edit extends Walker_Nav_Menu {
	var $show_menu_icons;

	function __construct( $show_menu_icons = true ) {
		$this->show_menu_icons = $show_menu_icons;
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker_Nav_Menu::end_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {}

	/**
	 * Start the element output.
	 *
	 * @see Walker_Nav_Menu::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Not used.
	 * @param int    $id     Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $_wp_nav_menu_max_depth;
		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = get_the_title( $original_object->ID );
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)'), $item->title );
		}

		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

		$submenu_text = '';
		if ( 0 == $depth )
			$submenu_text = 'style="display: none;"';

		?>
		<li id="menu-item-<?php echo esc_html($item_id); ?>" class="<?php echo esc_html(implode(' ', $classes )); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo esc_html($submenu_text); ?>><?php esc_html_e( 'sub item' ); ?></span></span>
					<span class="item-controls">
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo esc_url(wp_nonce_url(
									esc_url( add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									) ),
									'move-menu_item'
								));
							?>" class="item-move-up"><abbr title="<?php esc_html_e('Move up'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo esc_url(wp_nonce_url(
									esc_url( add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									) ),
									'move-menu_item'
								));
							?>" class="item-move-down"><abbr title="<?php esc_html_e('Move down'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo esc_html($item_id); ?>" title="<?php esc_html_e('Edit Menu Item'); ?>" href="<?php
							echo esc_url( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item']  ? admin_url( 'nav-menus.php' ) : esc_url( add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) ) );
						?>"><?php esc_html_e( 'Edit Menu Item' ); ?></a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings" id="menu-item-settings-<?php echo esc_html($item_id); ?>">
				<?php if( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo esc_html($item_id); ?>">
							<?php esc_html_e( 'URL' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo esc_html($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-thin">
					<label for="edit-menu-item-title-<?php echo esc_html($item_id); ?>">
						<?php esc_html_e( 'Navigation Label' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo esc_html($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="description description-thin">
					<label for="edit-menu-item-attr-title-<?php echo esc_html($item_id); ?>">
						<?php esc_html_e( 'Title Attribute' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo esc_html($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo esc_html($item_id); ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo esc_html($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_html($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php esc_html_e( 'Open link in a new window/tab' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo esc_html($item_id); ?>">
						<?php esc_html_e( 'CSS Classes (optional)' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo esc_html($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo esc_html($item_id); ?>">
						<?php esc_html_e( 'Link Relationship (XFN)' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo esc_html($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo esc_html($item_id); ?>">
						<?php esc_html_e( 'Description' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo esc_html($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_html($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
						<span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
					</label>
				</p>

				<?php
						$settings = wptouch_get_settings();
					if ( $this->show_menu_icons && $settings->enable_menu_icons ) {
				?>
					<p class="field-icon description wptouch-menu-icon" data-object-id="<?php echo esc_html($item_id); ?>">
						<label for="edit-menu-item-icon-<?php echo esc_html($item_id); ?>">
							<?php esc_html_e( 'Mobile Menu Icon' ); ?><br />
						</label>
						<img class="icon-preview" src="<?php echo esc_html(wptouch_get_menu_icon( $item->ID )); ?>" alt="menu-icon" style="height: 36px; width: 36px;" />
						<input type="hidden" id="edit-menu-item-icon-<?php echo esc_html($item_id); ?>" class="widefat edit-menu-item-icon" name="menu-item-icon[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( wptouch_get_menu_icon( $item->ID ) ); ?>" />
						<a tabindex="0" role="button" class="button change_icon" data-toggle="wptouch-popover" title="<?php esc_html_e( 'WPtouch Icons', 'wptouch-pro' ); ?>"><?php esc_html_e( 'Change Icon', 'wptouch-pro' ); ?></a>
						<a role="button" style="display:none" class="remove_icon"<?php if ( wptouch_get_menu_icon( $item->ID ) == wptouch_get_site_default_icon() ) { echo ' style="display: none;"'; } ?>><?php esc_html_e( 'Use Default', 'wptouch-pro' ); ?></a>
					</p>
				<?php
					}
				?>

				<p class="field-move hide-if-no-js description description-wide">
					<label>
						<span><?php esc_html_e( 'Move' ); ?></span>
						<a href="#" class="menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one' ); ?></a>
						<a href="#" class="menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one' ); ?></a>
						<a href="#" class="menus-move menus-move-left" data-dir="left"></a>
						<a href="#" class="menus-move menus-move-right" data-dir="right"></a>
						<a href="#" class="menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top' ); ?></a>
					</label>
				</p>

				<div class="menu-item-actions description-wide submitbox">
					<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( esc_html( __( 'Original: %s' ) ), esc_html( '<a href="' . $item->url . '">' . $original_title  . '</a>' ) ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_html($item_id); ?>" href="<?php
					echo esc_url(wp_nonce_url(
						esc_url( add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							admin_url( 'nav-menus.php' )
						) ),
						'delete-menu_item_' . $item_id
					)); ?>"><?php esc_html_e( 'Remove' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_html($item_id); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
						?>#menu-item-settings-<?php echo esc_html($item_id); ?>"><?php esc_html_e('Cancel'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_html($item_id); ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_html($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php
		$output .= ob_get_clean();
	}

} // Walker_Nav_Menu_Edit

function wptouch_menu_walker_get_classes( $item, $has_icon = true ) {
	$clear_classes = array( 'menu-item' );

	if ( isset( $item->classes ) && is_array( $item->classes ) ) {
		foreach( $item->classes as $key => $value ) {
			if ( is_string ( $value ) && strlen( $value ) ) {
				if ( !in_array( $value, $clear_classes ) ) {
					$clear_classes[] = $value;
				}
			}
		}
	}

	if ( !$has_icon ) {
		$clear_classes[] = 'no-icon';
	}

	return implode( ' ', apply_filters( 'wptouch_menu_item_classes', $clear_classes, $item ) );
}

function wptouch_menu_walker_the_classes( $classes ) {
	echo esc_html(wptouch_menu_walker_get_classes( $classes ));
}

class WPtouchProNavMenuWalker extends Walker_Nav_Menu {}

class WPtouchProMainNavMenuWalker extends WPtouchProNavMenuWalker {
	var $last_item;
	var $skipping_item;
	var $pending_levels;
	var $show_menu_icons;

	function __construct( $show_menu_icons = true ) {
		$this->show_menu_icons = $show_menu_icons;
	}

	function output_last_item( &$output ) {
		if ( $this->last_item->object == 'custom' || $this->last_item->type == 'taxonomy' ) {
			$link = $this->last_item->url;
		} else {
			$link = get_permalink( $this->last_item->object_id );
		}

		$target = '';
		if ( $this->last_item->target == '_blank' ) {
			$target = ' target="_blank"';
		}

		$title = '';
		if ( $this->last_item->title ) {
			$title = $this->last_item->title;
		} else {
			$title = $this->last_item->post_title;
		}

 		$output .= '<a href="' . $link . '" class="title"' . $target . '>' . $title . '</a>';
 		$this->last_item = false;
	}

	function start_lvl( &$output, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$this->output_last_item( $output );
 		}

	 	$output .= '<ul>';
	 }

	function end_lvl( &$output, $depth=0, $args=array() ) {
		$output .= '</ul>';
	}

	function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {
		$this->skipping_item = wptouch_menu_is_disabled( $item->ID );

		if ( !$this->skipping_item ) {
			$output .= '<li class="' . wptouch_menu_walker_get_classes( $item, $this->show_menu_icons ) . '">';

			$settings = wptouch_get_settings();
			if ( $this->show_menu_icons && $settings->enable_menu_icons ) {
				$output .= '<img src="' . wptouch_get_menu_icon( $item->ID ) . '" alt="menu-icon" />';
			}

			$this->last_item = $item;
		}
	}

 	function end_el( &$output, $item, $depth=0, $args=array() ) {
 		if ( !$this->skipping_item ) {
 			if ( $this->last_item ) {
				$this->output_last_item( $output );
 			}

 			$output .= "</li>";
 		}
 	}
}

class WPtouchProPageWalker extends Walker_Page {}

class WPtouchProMainPageMenuWalker extends WPtouchProPageWalker {
	var $last_item;
	var $skipping_item;
	var $show_menu_icons;

	function __construct( $show_menu_icons = true ) {
		$this->show_menu_icons = $show_menu_icons;
	}

	function output_last_item( &$output ) {
		$output .= '<a href="' . get_permalink( $this->last_item->ID ) . '" class="title">' . $this->last_item->post_title . '</a>';
		$this->last_item = false;
	}

	function start_lvl( &$output, $depth=0, $args=array() ) {
 		if ( $this->last_item ) {
 			$this->output_last_item( $output );
 		}

	 	$output .= '<ul>';
	 }

	function end_lvl( &$output, $depth=0, $args=array() ) {
		$output .= '</ul>';
	}

	function start_el( &$output, $item, $depth=0, $args=array(), $current_object_id = 0 ) {
		$this->skipping_item = wptouch_menu_is_disabled( $item->ID );

		if ( !$this->skipping_item ) {
			$output .= '<li class="' . wptouch_menu_walker_get_classes( $item, $this->show_menu_icons ) . '">';

			$settings = wptouch_get_settings();
			if ( $this->show_menu_icons && $settings->enable_menu_icons ) {
				$output .= '<img src="' . wptouch_get_menu_icon( $item->ID ) . '" alt="menu-icon" />';
			}

			$this->last_item = $item;
		}
	}

 	function end_el( &$output, $item, $depth=0, $args=array() ) {
  		if ( !$this->skipping_item ) {
	 		if ( $this->last_item ) {
	 			$this->output_last_item( $output );
	 		}

	 		$output .= "</li>";
	 	}
 	}
}

/**
 * Create HTML list of categories.
 *
 * @package WordPress
 * @since 2.1.0
 * @uses Walker
 */
class WPtouchProCategoryWalker extends Walker {

	public $tree_type = 'category';
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters(
			'list_cats',
			esc_attr( $category->name ),
			$category
		);

		$link = '<a href="' . esc_url( get_term_link( $category ) ) . '" ';
		if ( $args['use_desc_for_title'] && ! empty( $category->description ) ) {
			/**
			 * Filter the category description for display.
			 *
			 * @since 1.2.0
			 *
			 * @param string $description Category description.
			 * @param object $category    Category object.
			 */
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		}

		$link .= '>';
		$link .= $cat_name . '</a>';

		if ( ! empty( $args['feed_image'] ) || ! empty( $args['feed'] ) ) {
			$link .= ' ';

			if ( empty( $args['feed_image'] ) ) {
				$link .= '(';
			}

			$link .= '<a href="' . esc_url( get_term_feed_link( $category->term_id, $category->taxonomy, $args['feed_type'] ) ) . '"';

			if ( empty( $args['feed'] ) ) {
				$alt = ' alt="' . sprintf(__( 'Feed for all posts filed under %s' ), $cat_name ) . '"';
			} else {
				$alt = ' alt="' . $args['feed'] . '"';
				$name = $args['feed'];
				$link .= empty( $args['title'] ) ? '' : $args['title'];
			}

			$link .= '>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= $name;
			} else {
				$link .= "<img src='" . $args['feed_image'] . "'$alt" . ' />';
			}
			$link .= '</a>';

			if ( empty( $args['feed_image'] ) ) {
				$link .= ')';
			}
		}

		if ( ! empty( $args['show_count'] ) ) {
			$link .= ' (' . number_format_i18n( $category->count ) . ')';
		}
		if ( 'list' == $args['style'] ) {
			$output .= "\t<li";
			$class = 'menu-item no-icon menu-item-' . $category->term_id;
			if ( get_term_children( $category->term_id, $category->taxonomy ) ) {
				$class .= ' menu-item-has-children';
			}
			if ( ! empty( $args['current_category'] ) ) {
				$_current_category = get_term( $args['current_category'], $category->taxonomy );
				if ( $category->term_id == $args['current_category'] ) {
					$class .=  ' current-cat';
				} elseif ( $category->term_id == $_current_category->parent ) {
					$class .=  ' current-cat-parent';
				}
			}
			$output .=  ' class="' . $class . '"';
			$output .= ">$link\n";
		} else {
			$output .= "\t$link<br />\n";
		}
	}

	public function end_el( &$output, $page, $depth = 0, $args = array() ) {
		if ( 'list' != $args['style'] )
			return;

		$output .= "</li>\n";
	}

}
