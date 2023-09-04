<?php
class wp_easycart_admin_language_editor {

	public $language_file;
	public $language_settings_file;
	public $settings_file;

	public function __construct() {
		$this->language_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/language-editor/language.php';
		$this->language_settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/language-editor/language-settings.php';
		$this->settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/language-editor/settings.php';

		add_action( 'wpeasycart_admin_language_editor_settings', array( $this, 'load_language_editor_settings' ) );
		add_action( 'wpeasycart_admin_language_editor', array( $this, 'load_language_editor' ) );
	}

	public function load_language() {
		include( $this->settings_file );
	}

	public function load_language_editor_settings() {
		include( $this->language_settings_file );
	}
	public function load_language_editor() {
		include( $this->language_file );
	}
}
