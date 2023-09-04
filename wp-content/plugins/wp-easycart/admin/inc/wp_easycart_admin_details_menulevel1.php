<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details_menulevel1 extends wp_easycart_admin_details {

	public $menulevel1;
	public $item;

	public function __construct() {
		parent::__construct();
		add_action( 'wp_easycart_admin_menulevel1_details_basic_fields', array( $this, 'basic_fields' ) );
	}

	protected function init() {
		$this->docs_link = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=menus';
		$this->id = 0;
		$this->page = 'wp-easycart-products';
		$this->subpage = 'menus';
		$this->action = 'admin.php?page=' . $this->page . '&subpage=' . $this->subpage;
		$this->form_action = 'add-new-menulevel1';
		$this->menulevel1 = (object) array(
			'menulevel1_id' => '',
			'name' => '',
			'guid' => '',
			'menu_order' => '',
			'clicks' => '',
			'seo_keywords' => '',
			'seo_description' => '',
			'banner_image' => '',
			'post_id' => '',
			'post_excerpt' => '',
		);
	}

	protected function init_data() {
		global $wpdb;
		$this->form_action = 'update-menulevel1';
		$this->menulevel1 = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_menulevel1.*, ' . $wpdb->prefix . 'posts.guid, ' . $wpdb->prefix . 'posts.post_excerpt FROM ec_menulevel1 LEFT JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ec_menulevel1.post_id WHERE menulevel1_id = %d', (int) $_GET['menulevel1_id'] ) ); 
		$this->id = $this->menulevel1->menulevel1_id;
	}

	public function output( $type = 'edit' ) {
		$this->init();
		if ( 'edit' == $type ) {
			$this->init_data();
		}
		include( EC_PLUGIN_DIRECTORY . '/admin/template/products/menus/menulevel1-details.php' );
	}

	public function basic_fields() {
		$banner_image = $this->menulevel1->banner_image;
		if ( '' != $banner_image && 'http://' != substr( $banner_image, 0, 7 ) && 'https://' != substr( $banner_image, 0, 8 ) ) {
			$banner_image = plugins_url( '/wp-easycart-data/products/banners/' . $banner_image, EC_PLUGIN_DATA_DIRECTORY );
		}

		$fields = apply_filters(
			'wp_easycart_admin_menulevel1_details_basic_fields_list',
			array(
				array(
					'name' => 'menulevel1_id',
					'alt_name' => 'menulevel1_id',
					'type' => 'hidden',
					'value' => $this->menulevel1->menulevel1_id,
				),
				array(
					'name' => 'post_id',
					'alt_name' => 'post_id',
					'type' => 'hidden',
					'value' => $this->menulevel1->post_id,
				),
				array(
					'name' => 'name',
					'type' => 'text',
					'label' => __( 'Menu Name', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter a unique menu name.', 'wp-easycart' ),
					'validation_type' => 'text',
					'value' => $this->menulevel1->name,
				),
				array(
					'name' => 'post_slug',
					'type' => 'text',
					'label' => __( 'Link Slug', 'wp-easycart' ),
					'required' => false,
					'validation_type' => 'post_slug',
					'visible' => ($this->id == '0') ? false : true,
					'value' => basename( $this->menulevel1->guid ),
					'message' => __( 'Post Slug values must be unique and may only include letters, numbers, and dashes', 'wp-easycart' ),
				),
				array(
					'name' => 'menu_order',
					'type' => 'number',
					'label' => __( 'Menu Order #', 'wp-easycart' ),
					'required' => true,
					'message' => __( 'Please enter number to which this menu will sort by.', 'wp-easycart' ),
					'validation_type' => 'number',
					'value' => $this->menulevel1->menu_order,
				),
				array(
					'name' => 'seo_keywords',
					'type' => 'textarea',
					'label' => __( 'SEO Keywords (optional)', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter SEO keywords separated by a comma.', 'wp-easycart' ),
					'validation_type' => 'textarea',
					'value' => $this->menulevel1->seo_keywords,
				),
				array(
					'name' => 'seo_description',
					'type' => 'textarea',
					'label' => __( 'SEO Description (optional)', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please enter a couple sentences for SEO descriptions.', 'wp-easycart' ),
					'validation_type' => 'textarea',
					'value' => $this->menulevel1->seo_description,
				),
				array(
					'name' => 'banner_image',
					'type' => 'image_upload',
					'label' => __( 'Banner Image (optional)', 'wp-easycart' ),
					'required' => false,
					'message' => __( 'Please select an image for this menu.', 'wp-easycart' ),
					'validation_type' => 'image_upload',
					'value' => $banner_image,
				),
				array(
					'name' => 'post_excerpt',
					'type' => 'textarea',
					'label' => __( 'Post Excerpt - Commonly Used in Search Results', 'wp-easycart' ),
					'required' => false,
					'validation_type' => 'textarea',
					'visible' => true,
					'value' => $this->menulevel1->post_excerpt,
				),
				array(
					'name' => 'featured_image',
					'type' => 'wp_image_upload',
					'label' => __( 'Post Featured Image - Commonly Used in Search Results', 'wp-easycart' ),
					'required' => false,
					'validation_type' => 'wp_image',
					'visible' => true,
					'value' => get_post_thumbnail_id( $this->menulevel1->post_id ),
				),
			)
		);
		$this->print_fields( $fields );
	}
}
