<?php
class wp_easycart_admin_categories {
	public $categories_list_file;
	public function __construct() {
		$this->categories_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/categories/categories-list.php';
	}
	public function load_categories_list() {
		include( $this->categories_list_file );
	}
}
