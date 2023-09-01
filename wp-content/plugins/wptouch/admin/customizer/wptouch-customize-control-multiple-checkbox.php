<?php
/**
 * Extend WP_Customize_Control to allow for multiple checkboxes.
 */

class WPtouch_Customize_Control_Multiple_Checkbox extends WP_Customize_Control {

	/**
	 * The type of customize control being rendered.
	 */
	public $type = 'checklist';

	/**
	 * Displays the multiple select on the customize screen.
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		} ?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>
		<?php $multi_values = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
		<ul class="customize-control-selectlist">
			<?php foreach ( $this->choices as $value => $label ) : ?>
				<li>
					<label>
						<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>"/>
		<?php
	}
}

