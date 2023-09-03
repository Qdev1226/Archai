<?php
/**
 * Admin Area
 * like: file upload
 *
 */

 defined( 'ABSPATH' ) || exit;

 /**
  * Abort if the class is already exists.
  */
 if ( ! class_exists( 'Gutena_Forms_Admin' ) && class_exists( 'Gutena_Forms' ) ) {
 
   
	class Gutena_Forms_Admin extends Gutena_Forms {

		private $form_id = '';
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
			$this->load_classes();
			if ( ! empty( $_GET['formid'] ) && is_numeric( $_GET['formid'] ) ) {
				$this->form_id = absint( sanitize_key( $_GET['formid'] ) );
			}  
			add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'load_admin_classes' ) );

			if ( ! is_gutena_forms_pro( false ) ) {
				//view dashboard notice 
				add_action( 'admin_notices', array( $this, 'view_dashboard_notice' ) );
				//admin script
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_admin' ) );
				//Update form entry status to read
				add_action( 'wp_ajax_gutena_forms_dismiss_notice', array( $this, 'dismiss_notice' ) );
			}
			
		}

		/**
		 * Load classes
		 */
		private function load_classes() {

			//return if class already loaded
			if ( class_exists( 'Gutena_Forms_Activate_Deactivate' ) ) {
				return false;
			}

			/**
			 * activate-deactivate : Action on activation 
			 * store : common db functions 
			 * create-store extends store : create tables for storing form submission
			 * manage-store extends store : manage store : Read, Update and delete
			 */
			foreach ( array( "activate-deactivate", "store", "create-store", "manage-store" ) as $key => $filename) {
				if ( file_exists( GUTENA_FORMS_DIR_PATH . 'includes/admin/class-' . $filename .'.php' ) ) {
					require_once GUTENA_FORMS_DIR_PATH . 'includes/admin/class-' . $filename .'.php';
				}
			}
		}

		/**
		 * Load admin classes 
		 */
		public function load_admin_classes() {
			//load list table core wp class
			if ( ! class_exists( 'WP_List_Table' ) ) {
				if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
				}
			}
		}

		//Check admin capabilities
		public function is_gfadmin( $check_permission = 'manage_options' ) {
			if ( ! function_exists( 'wp_get_current_user' ) && file_exists( ABSPATH . "wp-includes/pluggable.php" ) ) { 
				require_once( ABSPATH . "wp-includes/pluggable.php" ); 
			}
			if ( ! function_exists( 'current_user_can' ) && file_exists( ABSPATH . "wp-includes/capabilities.php" ) ) { 
				require_once( ABSPATH . "wp-includes/capabilities.php" ); 
			}
			return ( function_exists( 'is_admin' ) && is_admin() && function_exists( 'current_user_can' ) && current_user_can( $check_permission ) );
		}


		/**
		 * Admin Menu
		 */
		public function register_admin_menu() {
			//check for proper permission
			if ( ( ! has_filter( 'gutena_forms_check_user_access' ) && ! $this->is_gfadmin() )  || ! class_exists( 'Gutena_Forms_Store' ) || ! apply_filters( 'gutena_forms_check_user_access', true, 'view_entries' ) ) {
				return;
			}
			
			//register menu
			$page_hook_suffix = add_menu_page(
				__( 'Forms', 'gutena-forms' ),
				__( 'Forms', 'gutena-forms' ),
				'delete_posts',
				'gutena-forms',
				array( $this, 'forms_dashboard' ),
				'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTQuMzUyMTEgMTEuNDE1NUgwLjE1NzYyNUwxMS40MTcgMC4xNTU1NzJWNC4zMTc1TDQuMzUyMTEgMTEuNDE1NVoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0xOS44MDYxIDExLjQxNDFIMjQuMDAwNkwxMi43NDEyIDAuMTU0MTA3VjQuMzE2MDRMMTkuODA2MSAxMS40MTQxWiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTQuMzQ5MTggMTIuNzM5M0gwLjE1NDY5NkwxMS40MTQxIDIzLjk5OTJWMTkuODM3M0w0LjM0OTE4IDEyLjczOTNaIiBmaWxsPSJ3aGl0ZSIvPgo8cGF0aCBkPSJNMTkuODAzMiAxMi43NDAySDIzLjk5NzZMMTIuNzM4MyAyNC4wMDAyVjE5LjgzODNMMTkuODAzMiAxMi43NDAyWiIgZmlsbD0id2hpdGUiLz4KPHJlY3Qgd2lkdGg9IjguNzU3MjkiIGhlaWdodD0iMi41ODY4NiIgdHJhbnNmb3JtPSJtYXRyaXgoMSAwIDAgLTEgMTIuMDQxIDE1LjMyNjIpIiBmaWxsPSJ3aGl0ZSIvPgo8L3N2Zz4K',
				27
			);
			if ( ! empty( $page_hook_suffix ) ) {
				add_action( 'admin_print_styles-' . $page_hook_suffix, array( $this, 'forms_listing_styles' ) );
				add_action( 'admin_print_scripts-' . $page_hook_suffix, array( $this, 'forms_listing_scripts' ) );
		   }
		}

		/**
		 * Form Dashboard pages: including form list, respective form entries etc
		 */
		public function forms_dashboard() {
			if ( ! function_exists('wp_localize_script') && ( ! has_filter( 'gutena_forms_check_user_access' ) && ! $this->is_gfadmin() )  || ! class_exists( 'Gutena_Forms_Store' ) || ! apply_filters( 'gutena_forms_check_user_access', true, 'view_entries' ) ) {
				esc_html_e( 'Access forbidden! Please contact admin to view this page.', 'gutena-forms' );
				return;
			}
			
			$form_store = new Gutena_Forms_Store();
			$form_table = '';
			$pagetype = empty( $_GET['pagetype'] ) ? '': sanitize_key( wp_unslash( $_GET['pagetype'] ) );
			echo '<div id="gutena-forms-dashboard-page" class="gutena-forms-dashboard '.( is_gutena_forms_pro() ? '':'gf-basic' ).' " style="display:none;" >';  

			if ( '' === $pagetype ) {
				if ( '' === $this->form_id ) {
					if ( file_exists( GUTENA_FORMS_DIR_PATH . 'includes/admin/class-forms-list-table.php' ) ) {
						require_once GUTENA_FORMS_DIR_PATH . 'includes/admin/class-forms-list-table.php';
						if ( class_exists( 'Gutena_Forms_List_Table' ) ) {
							$form_table = new Gutena_Forms_List_Table();
						}
					}
				} else if ( file_exists( GUTENA_FORMS_DIR_PATH . 'includes/admin/class-forms-entries-table.php' ) ) {
					require_once GUTENA_FORMS_DIR_PATH . 'includes/admin/class-forms-entries-table.php';
					if ( class_exists( 'Gutena_Forms_Entries_Table' ) ) {
						$form_list = $form_store->get_form_list();
						//form list
						if ( ! empty( $form_list ) ) {
							//Provide data for form submission script
							wp_localize_script(
								'gutena-forms-dashboard',
								'gutenaFormsList',
								array(
									'list'       => $form_list
								)
							);
						}
						$form_table = new Gutena_Forms_Entries_Table();
					}
				}
			} 

			//view-entry page
			if ( ! is_gutena_forms_pro() ) {
				//Entry View Page
				if ( ! empty( $_GET['form_entry_id'] ) && is_numeric( $_GET['form_entry_id'] ) && 'viewentry' === $pagetype  ) {
					
					//Provide data for form submission script
					wp_localize_script(
						'gutena-forms-dashboard',
						'gutenaFormsEntryDetails',
						array(
							'entry_data'       => $form_store->get_entry_details( absint( wp_unslash( $_GET['form_entry_id'] ) ) ),
						)
					);
				} 
			} 

			//data require to create page by react js
			$this->script_page_data( $pagetype );

			//Top header
			echo  $form_store->get_dashboard_header();
			echo '<div id="gutena-forms-dashboard-admin-message"></div>';
			//Placeholder for breadcrumb
			if ( ( '' === $pagetype && ! empty( $this->form_id ) ) || in_array( $pagetype, array( 'viewentry') ) ) {
				echo '<div id="gfp-page-breadcrumb"></div>';
			}
			//placeholder for react based dashboard
			echo '<div id="gfp-page-' . esc_attr( $pagetype ) . '"></div>';
			do_action( 'gutena_forms_entries_load_custom_page' );
			
			//render list
            if ( ! empty( $form_table ) ) {
                $form_table->render_list_table( $form_store );
				$this->entry_delete_modal();
            }
			//pro modal
			$this->go_pro_modal();
			echo '</div>';
		}

		/**
		 * Load script page data
		 */
		private function script_page_data( $pagetype = '' ){

			if ( ! function_exists('wp_localize_script') ) {
				return;
			}
			$gutena_url = 'https://gutena.io';
			//introduction page data
			if ( 'introduction' === $pagetype ) {
				/**
				 ** EDD checkout:  https://easydigitaldownloads.com/docs/creating-custom-add-to-cart-links/
				 **/
				wp_localize_script(
					'gutena-forms-dashboard',
					'gutenaFormsIntroduction',
					array(
						'section' => array(
							'welcome'   => array(
								'into_img'			=> esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/welcome.png' ),
								'intro_video_link' => esc_url( 'https://www.youtube.com/watch?v=2ZVS3b_02CA' ),
								'title'			=> __( 'Welcome to Gutena Forms!', 'gutena-forms' ),
								'description' 	=> __( "Gutena Forms is the easiest way to create forms inside the WordPress block editor. Our plugin does not use jQuery and is lightweight, so you can rest assured that it won't slow down your website. Instead, it allows you to quickly and easily create custom forms right inside the block editor.", "gutena-forms" ),
								'pricing_btn_name'	=> __( 'See Pricing', 'gutena-forms' ),
								'help_btn_name'	=> __( 'Need Help?', 'gutena-forms' ),
							),
							'features'	=> array(
								'title' => __( 'Gutena Forms Features', 'gutena-forms' ),
								'items' => array(
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/gutenberg.png' ),
										'title' => __( 'Build form with WP Editor', 'gutena-forms' ),
										'description' => __( 'Build forms effortlessly with WP Editor for a seamless and user-friendly form creation experience.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/list.svg' ),
										'title' => __( 'Entry Management', 'gutena-forms' ),
										'description' => __( 'Efficiently manage form submissions with comprehensive entry management and analysis capabilities.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/filter.svg' ),
										'title' => __( 'Advance Filter for Entries', 'gutena-forms' ),
										'description' => __( 'Easily locate specific entries with an advanced filtering system for efficient entry search.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/notes.svg' ),
										'title' => __( 'Entry Notes', 'gutena-forms' ),
										'description' => __( 'Collaborate and track progress by adding notes or comments to individual form entries.
										', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/responsive.svg' ),
										'title' => __( 'Responsive Mobile Friendly', 'gutena-forms' ),
										'description' => __( 'Ensure optimal user experience with fully responsive and mobile-friendly forms.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/support.svg' ),
										'title' => __( 'Premium Support', 'gutena-forms' ),
										'description' => __( 'Get premium customer support for prompt assistance and guidance in using the plugin effectively.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/status-management.svg' ),
										'title' => __( 'Status Management', 'gutena-forms' ),
										'description' => __( 'Organize and track form submissions with customizable entry statuses for streamlined workflow.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/tags-management.svg' ),
										'title' => __( 'Tags Management', 'gutena-forms' ),
										'description' => __( 'Categorize and sort form entries using tags for efficient organization and reporting.', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/user-management.svg' ),
										'title' => __( 'User Access Management', 'gutena-forms' ),
										'description' => __( 'Manage user access and permissions to control form data security and privacy.', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/forbidden.svg' ),
										'title' => __( 'No jQuery', 'gutena-forms' ),
										'description' => __( 'Enjoy improved performance and compatibility without jQuery dependencies.', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/recaptcha.png' ),
										'title' => __( 'Google reCAPTCHA', 'gutena-forms' ),
										'description' => __( 'Enhance form security with Google reCAPTCHA integration to prevent spam submissions.', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/settings.svg' ),
										'title' => __( 'Fully customizable', 'gutena-forms' ),
										'description' => __( 'Customize your forms extensively with various options for field types, layout, and styling.', 'gutena-forms' ),
									),
								)
							),
							'fields'	=> array(
								'title' => __( 'Gutena Forms Input Fields', 'gutena-forms' ),
								'items' => array(
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/text.svg' ),
										'title' => __( 'Text', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/hash.svg' ),
										'title' => __( 'Number', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/features/settings.svg' ),
										'title' => __( 'Range', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/email.svg' ),
										'title' => __( 'Email', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/dropdown.svg' ),
										'title' => __( 'Dropdown', 'gutena-forms' ),
									),
									array(
										'is_pro' => false,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/checkbox.svg' ),
										'title' => __( 'Checkbox', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/phone.svg' ),
										'title' => __( 'Phone', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/radio.svg' ),
										'title' => __( 'Radio', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/calendar.svg' ),
										'title' => __( 'Date', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/time.svg' ),
										'title' => __( 'Time', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/rating.svg' ),
										'title' => __( 'Rating', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/country.svg' ),
										'title' => __( 'Country 
										Dropdown ', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/state.svg' ),
										'title' => __( 'State', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/file.svg' ),
										'title' => __( 'File Upload', 'gutena-forms' ),
									),
									array(
										'is_pro' => true,
										'icon' => esc_url( GUTENA_FORMS_PLUGIN_URL . 'assets/img/fields/link.svg' ),
										'title' => __( 'URL', 'gutena-forms' ),
									),
								)
							),
							'pricing'	=> array(
								'title' => __( 'Achieve more with Premium', 'gutena-forms' ),
								'subtitle' => __( 'Unlock the full potential of Gutena Form with Premium', 'gutena-forms' ),
								'billed_frequency' =>  __( 'Yearly', 'gutena-forms' ),
								'features' => array(
									__( 'All Premium Input Fields', 'gutena-forms' ),
									__( 'All Premium Features', 'gutena-forms' ),
									__( 'Premium Support', 'gutena-forms' ),
								),
								'items' => array(
									array(
										'title' => __( 'STARTER PLAN', 'gutena-forms' ),
										'price'	=> '$9.00',
										'description' => __( '1 Site - 14 Days Free Trial', 'gutena-forms' ),
										'btn_name' => __( 'Start Trial', 'gutena-forms' ),
										'link'	=> 'https://shop.gutena.io/checkout/?edd_action=add_to_cart&download_id=1493807&edd_options[price_id]=1',
									),
									array(
										'title' => __( 'DEVELOPER PLAN', 'gutena-forms' ),
										'price'	=> '$29.00',
										'description' => __( '5 Site', 'gutena-forms' ),
										'link'	=> 'https://shop.gutena.io/checkout/?edd_action=add_to_cart&download_id=1493807&edd_options[price_id]=2',
										'btn_name' => __( 'Get Started', 'gutena-forms' ),
									),
									array(
										'title' => __( 'AGENCY PLAN', 'gutena-forms' ),
										'price'	=> '$99.00',
										'description' => __( 'Unlimited Sites', 'gutena-forms' ),
										'link'	=> 'https://shop.gutena.io/checkout/?edd_action=add_to_cart&download_id=1493807&edd_options[price_id]=3',
										'btn_name' => __( 'Get Started', 'gutena-forms' ),
									),
								)
								),
							'faq'		=> array(
								'title' => __( 'Frequently asked questions', 'gutena-forms' ),
								'items' => array(
									array(
										'title' => __( 'What is Gutena Forms?
										', 'gutena-forms' ),
										'description' => __( 'Gutena Forms is a WordPress plugin that allows you to create custom forms easily within the block editor, without jQuery, ensuring superior performance.', 'gutena-forms' ),
									),
									array(
										'title' => __( "How does Gutena Forms differ from other form plugins?", "gutena-forms" ),
										'description' => __( "Gutena Forms integrates seamlessly with the block editor, offering a user-friendly form-building experience. It is lightweight and doesn't slow down your website.", "gutena-forms" ),
									),
									array(
										'title' => __( 'Can I create different types of forms with Gutena Forms?', 'gutena-forms' ),
										'description' => __( 'Yes, Gutena Forms offers various form elements and customization options, enabling you to create contact forms, surveys, feedback forms, and more to suit your needs.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Is Gutena Forms compatible with my theme?', 'gutena-forms' ),
										'description' => __( 'Yes, Gutena Forms is designed to be compatible with most WordPress themes, ensuring a consistent form-building experience regardless of your theme choice.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Is Gutena Forms responsive and mobile-friendly?', 'gutena-forms' ),
										'description' => __( 'Yes, Gutena Forms ensures that your forms are fully responsive and adapt perfectly to different devices, providing an optimal user experience on smartphones, tablets, and desktops.', 'gutena-forms' ),
									),
									array(
										'title' => __( ' Can I customize the look and feel of my forms?', 'gutena-forms' ),
										'description' => __( 'Absolutely! Gutena Forms offers extensive customization options, allowing you to personalize your forms with different field types, layouts, and custom styles.', 'gutena-forms' ),
									),
									array(
										'title' => __( ' Is support available if I need assistance?', 'gutena-forms' ),
										'description' => __( 'Yes, we provide dedicated support for Gutena Forms to address any questions, issues, or guidance you may need during your form-building journey.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Can Gutena Forms handle large volumes of form submissions?', 'gutena-forms' ),
										'description' => __( 'Yes, Gutena Forms offers robust entry management capabilities, allowing you to efficiently handle and analyze high volumes of form submissions from your WordPress dashboard.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Can I transfer my license from one site to another?', 'gutena-forms' ),
										'description' => __( 'Yes, you can transfer your license from one site to another. Simply deactivate the license on the current site and then activate it on the new site. This process ensures that your license is valid and active for the new site, allowing you to continue using the plugin seamlessly.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Is Gutena Forms regularly updated and maintained?', 'gutena-forms' ),
										'description' => __( 'Yes, Gutena Forms is regularly updated to ensure compatibility with the latest WordPress versions, security patches, and to bring new features and enhancements to the plugin.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'What happens when my license expires?', 'gutena-forms' ),
										'description' => __( 'When your license expires, you may lose access to updates, support, and premium features. However, the plugin will continue to function as it is.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Do you offer refunds?', 'gutena-forms' ),
										'description' => __( 'No, but we do offer a special 14-day trial plan at $0, You can try out the plugin as much as you want and then decide whether to upgrade to any of our premium plans.', 'gutena-forms' ),
									),
									array(
										'title' => __( 'Can I upgrade my Premium plan?', 'gutena-forms' ),
										'description' => __( 'Yes, you can upgrade your Premium plan to include more sites. We currently offer 3 Premium Plans with increased site limits. Contact our support team for assistance with the upgrade process.', 'gutena-forms' ),
									),
								),
								'sales' => array(
									'title1' => __( 'Do you have any question?', 'gutena-forms' ),
									'title2' => __( 'Contact with Sales Team', 'gutena-forms' ),
									'link'	=> esc_url( $gutena_url . '/contact' ),
								)
							)
						)
					)
				);
			} else if (  'settings' === $pagetype ) {
					//setting page data
					wp_localize_script(
						'gutena-forms-dashboard',
						'gutenaFormsSettingsTab',
						array(
							'tabs' => array(
								array(
									'name' => 'status',
									'title' => esc_html__( 'Status', 'gutena-forms' ),
									'heading' => esc_html__( 'Status Management', 'gutena-forms' ),
									'description' => esc_html__( 'Organize and track form submissions with customizable entry status for streamlined workflow.', 'gutena-forms' ),
								),
								array(
									'name' => 'tags',
									'title' => esc_html__( 'Tags', 'gutena-forms' ),
									'heading' => esc_html__( 'Tags Management', 'gutena-forms' ),
									'description' => esc_html__( 'Categorize and sort form entries using tags for efficient organization and reporting.', 'gutena-forms' ),
								),
								array(
									'name' => 'useraccess',
									'title' => esc_html__( 'User Access', 'gutena-forms' ),
									'heading' => esc_html__( 'User Access Management', 'gutena-forms' ),
									'description' => esc_html__( 'Manage user access and permissions to control form data security and privacy.', 'gutena-forms' ),
								)
							),
						)
					);
			} else if ( 'doc' === $pagetype ) {
				//knowledge base page data
				wp_localize_script(
					'gutena-forms-dashboard',
					'gutenaFormsDoc',
					array(
						'topics' => array(
							'title' => esc_html__( 'How to Topics and Tips', 'gutena-forms' ),
							'items' => array(
								array(
									'heading' =>  esc_html__( 'How to reuse Gutena forms on Multiple Pages?', 'gutena-forms' ),
									'link' => esc_url( $gutena_url . '/reuse-gutena-forms-on-multiple-pages' ),
								),
								array(
									'heading' =>  esc_html__( 'How to generate Google reCaptcha Site Key and Secret Key?', 'gutena-forms' ),
									'link' => esc_url( $gutena_url . '/how-to-generate-google-recaptcha-site-key-and-secret-key' ),
								),
								array(
									'heading' =>  esc_html__( 'How to start with Gutena Forms Pro?', 'gutena-forms' ),
									'link' => esc_url( $gutena_url . '/how-to-start-with-gutena-forms-pro' ),
								),
							)
							
						),
						'support' => array(
							'title' => esc_html__( 'Need Help?', 'gutena-forms' ),
							'description' => esc_html__( 'Have a question, we are happy to help! Get in touch with our support team.', 'gutena-forms' ),
							'documentation_link' => esc_url( $gutena_url . '/blog' ),
							'documentation_text' => esc_html__( 'Help Articles', 'gutena-forms' ),
							'link_text' => esc_html__( 'Support', 'gutena-forms' ),
						),
						'changelog' => array(
							'title'       => esc_html__( 'Releases and fixes', 'gutena-forms' ),
							'description' => $this->get_changelog(),
						),
					),
				);
			}

			
		}

		// Get Changelog from readme.txt file
		private function get_changelog() {
			$response = wp_remote_get( GUTENA_FORMS_PLUGIN_URL . 'readme.txt', array(
				'sslverify' => false
			) );
			if ( ! is_wp_error( $response ) ) {
				$response =  wp_remote_retrieve_body( $response );
				$response = explode( '== Changelog ==', $response, 2 );
				if ( 2 === count( $response ) ) {
					$response =  explode( '== Copyright ==', trim( $response[1] ), 2 );
					if ( ! empty( $response[0] ) ) {
						$response = $response[0];
						$response =  str_ireplace( "= ", "<span class='version'>", $response );
						$response =  str_ireplace( " =", "</span>", $response );
						return $response;
					}
				}
			}
			return '';
		}

		/**
		 * Go Pro Modal: 
		 * to open this modal use : <a modalid="gutena-forms-go-pro-modal" href="#" class="gutena-forms-modal-btn"  ></a> - anywhere in html document
		 */
		public function go_pro_modal() {
			
			//Action Html
			if ( ! is_gutena_forms_pro( false ) ) {
				echo '
				<div id="gutena-forms-go-pro-modal"  class="gutena-forms-modal gf-small-modal" >
				<div class="gutena-forms-modal-content" >
				<span class="gf-close-btn gf-close-icon">&times;</span>
				<div class="gf-header" > 
					<div class="gf-title" >'.__( 'Upgrade to Complete the Experience!', 'gutena-forms' ).'</div> 
				</div>
				
				<div class="gf-body" >
				<p class="gf-description"> '.__( 'Get Gutena Forms Pro Today and Unlock all the Features', 'gutena-forms' ).'</p>
				<a href="' . esc_url( admin_url( 'admin.php?page=gutena-forms&pagetype=introduction#gutena-forms-pricing' ) ) . '" class="gf-btn gf-pro-btn" > <span class="gf-btn-text">'.__( 'Go Premium - 14 Day Free Trial', 'gutena-forms' ).'</span> </a>
				</div></div></div>';
			} else {
				do_action( 'gutena_forms_go_pro_modal' );
			}
			
		}

		/**
		 * Delete a form entry modal: 
		 * to open this modal use : <a modalid="gutena-forms-entry-delete-modal" href="#" class="gutena-forms-modal-btn"  ></a> - anywhere in html document
		 */
		public function entry_delete_modal() {
			//Action Html
			echo '
			<div id="gutena-forms-entry-delete-modal"  class="gutena-forms-modal gf-small-modal" >
			<div class="gutena-forms-modal-content" >
			<span class="gf-close-btn gf-close-icon">&times;</span>
			<div class="gf-header" > 
				<div class="gf-title" >'.__( 'Trash Entry', 'gutena-forms' ).'</div> 
			</div>
			
			<div class="gf-body" >
			<p class="gf-description"> '.__( 'Do you really wanna trash this entry?', 'gutena-forms' ).'</p>
			<div class="gf-action-btns">
				<a href="#" class="gf-btn gf-close-btn" > <span class="gf-btn-text">'.__( 'Cancel', 'gutena-forms' ).'</span> </a>
				<a href="#" class="gf-btn gf-entry-delete-btn" > <span class="gf-btn-text">'.__( 'Trash', 'gutena-forms' ).'</span> </a>
			</div>
			
			</div></div></div>';
		}

		public function forms_listing_styles() {
			wp_enqueue_style( 'gutena-forms-dashboard-style', GUTENA_FORMS_PLUGIN_URL . 'includes/admin/dashboard/build/style-index.css', array( 'wp-components','wp-edit-blocks' ), GUTENA_FORMS_VERSION, 'all' );

			do_action( 'gutena_forms_dashboard_enqueue_style');

		}

		public function forms_listing_scripts() {

			$asset_file = include_once( GUTENA_FORMS_DIR_PATH . 'includes/admin/dashboard/build/index.asset.php' );

			if ( ! empty( $asset_file['dependencies'] ) && function_exists( 'admin_url' ) ) {
				
				wp_enqueue_script( 'gutena-forms-dashboard', GUTENA_FORMS_PLUGIN_URL . 'includes/admin/dashboard/build/index.js', $asset_file['dependencies'], $asset_file['version'], true );
				
				wp_enqueue_script( 'gutena-forms-dashboard-script', GUTENA_FORMS_PLUGIN_URL . 'includes/admin/dashboard/build/script.js', array(), $asset_file['version'], true );

				$dashboard_url =  esc_url( admin_url( 'admin.php?page=gutena-forms' ) );
				$is_admin = $this->is_gfadmin() ? '1':'0';
				//Provide data for form submission script
				wp_localize_script(
					'gutena-forms-dashboard-script',
					'gutenaFormsDashboard',
					array(
						'read_status_action'    => 'gutena_forms_entries_read',
						'ajax_url'           	=> admin_url( 'admin-ajax.php' ),
						'nonce'                 => wp_create_nonce( 'gutena_Forms' ),
						'support_link'			=> esc_url( apply_filters( 
							'gutena_forms_support_link',
							'https://wordpress.org/support/plugin/gutena-forms/' 
						 ) ),
						'entry_view_url'        => $dashboard_url.'&pagetype=viewentry&form_entry_id=',
						'entry_list_url' 		=> $dashboard_url.'&formid=',
						'page_url'				=> $dashboard_url.'&pagetype=',
						'is_gutena_forms_pro'   => is_gutena_forms_pro() ? '1' : '0',
						'is_admin'				=> $is_admin,
						'pagetype'				=> empty( $_GET['pagetype'] ) ? '': sanitize_key( wp_unslash( $_GET['pagetype'] ) ),
						'form_id'				=> $this->form_id,
						'dashboard_menu' => apply_filters( 
							'gutena_forms_dashboard_menu', 
							array(
								array(
									'slug'  => 'introduction',
									'title' => __( 'Introduction', 'gutena-forms' ),
									'enable' => '1'
								),
								array(
									'slug'  => '',
									'title' => __( 'Entries', 'gutena-forms' ),
									'enable' => '1'
								),
								array(
									'slug'  => 'settings',
									'title' => __( 'Settings', 'gutena-forms' ),
									'enable' => $is_admin
								),
								array(
									'slug'  => 'doc',
									'title' => __( 'Knowledge Base', 'gutena-forms' ),
									'enable' => '1'
								)
							)
						),
						
					)
				);

				do_action( 'gutena_forms_dashboard_enqueue_scripts');
			}
			
			
		}

		/**
		 * Check if given keys in an array is empty or not 
		 * 
		 * @param array $check_array array to check
		 * @param array $keys keys to check
		 * @param boolean $logic_or apply conditional logic OR if true otherwise AND
		 * 
		 * @return boolean 
		 */
		public function is_empty( $check_array , $keys , $logic_or = true ) {
			//return if values not provided
			if ( empty( $check_array ) || empty( $keys ) ) {
				return true;
			}

			foreach ( $keys as $key ) {
				if ( $logic_or ) {
					if ( empty( $check_array[ $key ] ) ) {
						return true;
					}
				} else if ( ! empty( $check_array[ $key ] ) ) {
					return false;
				}
			}
			/** 
			 * At the end for 
			 * OR condition no one is empty  so return false 
			 * AND all are empty so return true
			 * */
			return $logic_or ? false : true;
		}

		// sanitize and seralize data
		public function sanitize_serialize_data( $data ) {
			
			if ( empty( $data ) ) {
				return $data;
			}

			if ( is_object( $data ) ) {
				$data = clone $data;
			} else if ( is_array( $data ) ) {
				$data = $this->sanitize_array( $data, true );
			} else {
				$data = sanitize_textarea_field( $data );
			}

			$data = sanitize_option( 'gutena_forms', $data );

			if( ! is_serialized( $data ) ) {
				$data = maybe_serialize($data);
			}

			return $data;
		}

		//unserialize data
		public function maybe_unserialize( $data ) {
			return ( function_exists( 'maybe_unserialize' ) && ! empty( $data )  ) ? maybe_unserialize( $data ) : $data;
		}

		//get current user id
		public function current_user_id() {
			//current user id
			return intval( function_exists( 'get_current_user_id' ) ? get_current_user_id() : 0 );
		}

		//view dashboard notice
		public function view_dashboard_notice() {
			$notice_id = "gutena-forms-view-dashboard-notice";
			$notice = $this->get_notices_and_status( $notice_id );
			if ( false === $notice['dismissed'] ) {
				echo '<div id="'.esc_attr( $notice_id ).'" class="notice notice-info is-dismissible gutena-forms-admin-notice" style="display:flex;align-items:center; gap:6px;" > <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M4.22379 11.333H0.00196697L11.3347 0.000257024V4.1891L4.22379 11.333Z" fill="#0DA88C"/>
					<path d="M19.7791 11.3325H24.001L12.6682 -0.000231258V4.18861L19.7791 11.3325Z" fill="#0DA88C"/>
					<path d="M4.22159 12.6655H-0.000230298L11.3325 23.9983V19.8094L4.22159 12.6655Z" fill="#0DA88C"/>
					<path d="M19.7772 12.6675H23.999L12.6663 24.0002V19.8114L19.7772 12.6675Z" fill="#0DA88C"/>
					<rect width="8.81436" height="2.60358" transform="matrix(1 0 0 -1 11.9624 15.2695)" fill="#0DA88C"/>
					</svg>
					<p> <strong>' . __( 'Exciting News!', 'gutena-forms' ) . ' </strong>'. __( ' Now, you can view and manage all your form submissions right from the Gutena Forms Dashboard.', 'gutena-forms' ) . '<strong><a href="'.esc_url( admin_url( 'admin.php?page=gutena-forms' ) ).'" style="color: #E35D3F;margin-left:1rem;" > ' . __( 'See all Entries', 'gutena-forms' ) . ' </strong></a></p></div>';
			}
		}

		//enqueue admin scripts
		public function enqueue_scripts_admin() {
			wp_enqueue_script( 'gutena-forms-admin', GUTENA_FORMS_PLUGIN_URL . 'assets/minify/js/admin.min.js', array(), GUTENA_FORMS_VERSION, true );

			wp_localize_script(
				'gutena-forms-admin',
				'gutenaFormsAdmin',
				array(
					'dismiss_notice_action'    => 'gutena_forms_dismiss_notice',
					'ajax_url'           	=> admin_url( 'admin-ajax.php' ),
					'nonce'                 => wp_create_nonce( 'gutena_Forms' ),
				)
			);

		}

		//get and check if provided notice id is dismissed or not
		private function get_notices_and_status( $notice_id = '' ) {
			$notices = get_option( 'gutena_forms_dismiss_notices', array() );
			return array( 
				'notices' => $notices,
				'dismissed' => ( empty( $notice_id ) || ( ! empty( $notices ) && in_array( $notice_id, $notices ) ) )
			);
		}

		//dismiss notice ajax
		public function dismiss_notice() {
			check_ajax_referer( 'gutena_Forms', 'gfnonce' );
			if ( $this->is_gfadmin() && ! empty( $_POST['notice_id'] ) ) {
				$notice_id = sanitize_key( wp_unslash( $_POST['notice_id'] ) );
				$notice = $this->get_notices_and_status( $notice_id );
				if ( is_array( $notice['notices'] ) && false === $notice['dismissed'] ) {
					array_push( $notice['notices'], $notice_id );
					update_option( 'gutena_forms_dismiss_notices', $notice['notices'] );
				}
			}

			wp_send_json_success( array(
				'status'  => 'success',
				'message' => __( 'Notice dismissed successfully', 'gutena-forms' ),
			) );
		}

		//check if data tables exists
		public function is_forms_store_exists() {
			return empty(  get_option( 'gutena_forms_store_version', false ) ) ? false : true;
		}
	}

	Gutena_Forms_Admin::get_instance();
}