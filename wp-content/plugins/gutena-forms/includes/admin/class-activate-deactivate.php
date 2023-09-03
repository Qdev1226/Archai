<?php
/**
* Plugin activation and deactivation
* 
*
*/

 defined( 'ABSPATH' ) || exit;

 /**
 * Abort if the class is already exists.
 */
 if ( ! class_exists( 'Gutena_Forms_Activate_Deactivate' ) && class_exists( 'Gutena_Forms_Admin' ) ) {

	class Gutena_Forms_Activate_Deactivate extends Gutena_Forms_Admin {

		// The instance of this class
		private static $instance = null;

		// Returns the instance of this class.
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
			//on activate
			register_activation_hook( GUTENA_FORMS_FILE, array( $this, 'activate' ) );
			//on deactivate
			register_deactivation_hook( GUTENA_FORMS_FILE, array( $this, 'deactivate' ) );
			//on plugin update
			add_action( 'admin_init', array( $this, 'check_and_create_store' ));
			//Fires when a site initialization routine should be executed.
			add_action( 'wp_initialize_site', array( $this, 'new_site_in_multisite' ), 10, 2 );

		}

		//trigger activation if form entries store was not created 
		public function check_and_create_store() {
			if ( $this->is_gfadmin() && ! $this->is_forms_store_exists() ) {
				$this->activate();
			}
		}

		//cretae form entries store on plugin activation
		public function activate() {
			//check for multisite if plugin is activated 
			if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename( GUTENA_FORMS_FILE ) ) ) {
				if ( function_exists( 'get_sites' ) && class_exists( 'WP_Site_Query' ) && function_exists( 'switch_to_blog' ) ) {
					$sites = get_sites();
					foreach ( $sites as $site ) {
						if ( ! empty( $site->blog_id ) ) {
							//Switches the current blog
							switch_to_blog( $site->blog_id );
								//activation begins
								$this->activation_begins();
							//switch back to restore
							restore_current_blog();
						}
					}
					return;
				}
			} else {
				//single site
				$this->activation_begins();
			}
		}

		private function activation_begins() {
			//activation begins
			do_action( 'gutena_forms_activation_begins' );
			//will use to check last installed version
			update_option( 'gutena_forms_version', GUTENA_FORMS_VERSION );
			//activation ends
			do_action( 'gutena_forms_activation_end' );
		}

		/**
		 * Fires when a site's initialization routine should be executed after inserts a new site
		 * into the database.
		 * when a new site is created inside a multisite
		 *
		 * @param WP_Site $site New site object.
		 * @param array   $args     Arguments for the initialization.
		 */
		public function new_site_in_multisite( $site, $args ) {
			if ( function_exists( 'is_plugin_active_for_network' ) && is_plugin_active_for_network( plugin_basename( GUTENA_FORMS_FILE ) ) ) {
				if ( ! empty( $site->blog_id ) ) {
					//Switches the current blog
					switch_to_blog( $site->blog_id );
						//activation begins
						$this->activation_begins();
					//switch back to restore
					restore_current_blog();
				}
			}
		}

		public function deactivate() {
			do_action( 'gutena_forms_deactivation_begins' );
		}
		
	}

	Gutena_Forms_Activate_Deactivate::get_instance();
 }
