<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_reviews' ) ) :

	final class wp_easycart_admin_reviews{

		protected static $_instance = null;

		public $reviews_list_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self(  );
			}
			return self::$_instance;
		}

		public function __construct() { 
			$this->reviews_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/reviews/review-list.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_review' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_approve_review' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_approve_reviews' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_unapprove_review' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_unapprove_reviews' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_review' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_reviews' ) );
		}

		public function process_update_review() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( $_POST['ec_admin_form_action'] == "update-review" ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-review-details' ) ) {
					$result = $this->update_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function process_approve_review() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'reviews' && $_GET['ec_admin_form_action'] == 'approve-review' && isset( $_GET['review_id'] ) && !isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-approve-review' ) ) {
					$result = $this->approve_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function process_bulk_approve_reviews() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'reviews' && $_GET['ec_admin_form_action'] == 'approve-review' && !isset( $_GET['review_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-reviews' ) ) {
					$result = $this->bulk_approve_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function process_unapprove_review() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'reviews' && $_GET['ec_admin_form_action'] == 'unapprove-review' && isset( $_GET['review_id'] ) && !isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-unapprove-review' ) ) {
					$result = $this->unapprove_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function process_bulk_unapprove_reviews() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'reviews' && $_GET['ec_admin_form_action'] == 'unapprove-review' && !isset( $_GET['review_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-reviews' ) ) {
					$result = $this->bulk_unapprove_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function process_delete_review() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'reviews' && $_GET['ec_admin_form_action'] == 'delete-review' && isset( $_GET['review_id'] ) && !isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-review' ) ) {
					$result = $this->delete_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function process_bulk_delete_reviews() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && $_GET['subpage'] == 'reviews' && $_GET['ec_admin_form_action'] == 'delete-review' && !isset( $_GET['review_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-reviews' ) ) {
					$result = $this->bulk_delete_review();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'reviews', $result );
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && $_GET['success'] == 'review-updated' ) {
				$messages[] = __( 'Review successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'review-deleted' ) {
				$messages[] = __( 'Review successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'review-approved' ) {
				$messages[] = __( 'Review(s) successfully approved', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'review-unapproved' ) {
				$messages[] = __( 'Review(s) successfully denied', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && $_GET['error'] == 'review-updated-error' ) {
				$messages[] = __( 'Review failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'review-deleted-error' ) {
				$messages[] = __( 'Review failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'review-duplicate' ) {
				$messages[] = __( 'Review failed to create due to duplicate', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'review-approved' ) {
				$messages[] = __( 'There was an issue approving the review(s).', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && $_GET['error'] == 'review-unapproved' ) {
				$messages[] = __( 'There was an issue denying the review(s).', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_reviews_list() {
			if ( ( isset( $_GET['review_id'] ) && isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'edit' ) || 
				( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new' ) ) {
					include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_review.php' );
					$details = new wp_easycart_admin_details_review();
					$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->reviews_list_file );
			}
		}

		public function update_review() {
			global $wpdb;
			$review_id = (int) $_POST['review_id'];
			$product_id = (int) $_POST['product_id'];
			$user_id = (int) $_POST['user_id'];
			$rating = (int) $_POST['rating'];
			$title = wp_easycart_escape_html( $_POST['title'] ); // XSS OK.
			$description = wp_easycart_escape_html( $_POST['description'] ); // XSS OK.
			$date_submitted = date( "Y-m-d h:i:s", strtotime( sanitize_text_field( $_POST['date_submitted'] ) ) );
			$reviewer_name = sanitize_text_field( $_POST['reviewer_name'] );
			$approved = 0;
			if ( isset( $_POST['approved'] ) ) {
				$approved = 1;
			}
			$wpdb->query( $wpdb->prepare( "UPDATE ec_review SET review_id = %s, product_id = %s, user_id = %s, approved = %s, rating = %s , title = %s , description = %s , date_submitted = %s, reviewer_name = %s  WHERE review_id = %s", $review_id, $product_id, $user_id, $approved, $rating, $title, $description, $date_submitted, $reviewer_name, $review_id ) );
			return array( 'success' => 'review-updated' );	
		}


		public function delete_review() {
			global $wpdb;
			$review_id = (int) $_GET['review_id'];
			$query_vars = array();
			$wpdb->query( $wpdb->prepare( "DELETE FROM ec_review WHERE ec_review.review_id = %s", $review_id ) );
			$query_vars['success'] = 'review-deleted';
			return $query_vars;
		}

		public function approve_review() {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "UPDATE ec_review SET approved = 1 WHERE review_id = %d", (int) $_GET['review_id'] ) );
			$query_vars = array( 'success' => 'review-approved' );
			return $query_vars;
		}

		public function bulk_approve_review() {
			global $wpdb;
			$query_vars = array();
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( "UPDATE ec_review SET approved = 1 WHERE review_id = %d", (int) $bulk_id ) );
			}

			$query_vars['success'] = 'review-approved';
			return $query_vars;
		}

		public function unapprove_review() {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "UPDATE ec_review SET approved = 0 WHERE review_id = %d", (int) $_GET['review_id'] ) );
			$query_vars = array( 'success' => 'review-unapproved' );
			return $query_vars;
		}

		public function bulk_unapprove_review() {
			global $wpdb;
			$query_vars = array();
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( "UPDATE ec_review SET approved = 0 WHERE review_id = %d", (int) $bulk_id ) );
			}

			$query_vars['success'] = 'review-unapproved';
			return $query_vars;
		}

		public function bulk_delete_review() {
			global $wpdb;
			$query_vars = array();
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( "DELETE FROM ec_review WHERE review_id = %d", (int) $bulk_id ) );
			}

			$query_vars['success'] = 'review-deleted';
			return $query_vars;
		}
	}
endif;

function wp_easycart_admin_reviews() {
	return wp_easycart_admin_reviews::instance();
}
wp_easycart_admin_reviews();
