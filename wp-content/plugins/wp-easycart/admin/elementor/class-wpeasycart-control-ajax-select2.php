<?php
/**
 * WP EasyCart Ajax Select2 for Elementor
 *
 * @category Class
 * @package  WPEasyCart_Control_Ajax_Select2
 * @author   WP EasyCart
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WP EasyCart Ajax Select2 for Elementor
 *
 * @category Class
 * @package  WPEasyCart_Control_Ajax_Select2
 * @author   WP EasyCart
 */
class WPEasyCart_Control_Ajax_Select2 extends \Elementor\Base_Data_Control {

	/**
	 * Get select2 control type.
	 */
	public function get_type() {
		return 'wpecajaxselect2';
	}

	/**
	 * Get select2 control default settings.
	 */
	protected function get_default_settings() {
		return array(
			'options'        => array(),
			'select2options' => array(),
			'multiple'       => false,
		);
	}

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		wp_register_script( 'wpecajaxselect2-editor', plugins_url( 'wp-easycart/admin/elementor/wp-easycart-elementor-ajaxselect2.js', EC_PLUGIN_DIRECTORY ), array(), EC_CURRENT_VERSION );
		wp_enqueue_script( 'wpecajaxselect2-editor' );
	}


	/**
	 * Render select2 control output in the editor.
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		$restapi = get_site_url( '' ) . '/wp-json/wp-easycart/v1';
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select 
					id="<?php echo esc_attr( $control_uid ); ?>"
					class="elementor-ajaxselect2" 
					type="wpecajaxselect2" {{ multiple }} 
					data-setting="{{ data.name }}"
					data-ajax-url="<?php echo esc_url( $restapi ) . '/{{data.options}}/'; ?>""
				>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
