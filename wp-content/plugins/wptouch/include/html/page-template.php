<?php 
	$templates = wptouch_page_templates_get_all();
	global $post;
	$current_template = get_post_meta( $post->ID, '_mobile_page_template', true ); 
	wp_nonce_field( 'mobile_template_box', 'mobile_template_box_nonce' );
?>
<select name="wptouch_mobile_page_template">
	<option <?php if ( $current_template == '' ) echo 'selected'; ?>><?php esc_attr_e( 'Default Template' , 'wptouch-pro' ); ?></option>
	<?php foreach( $templates as $file => $template ) { ?>
	<option value="<?php echo esc_url($template->location); ?>" <?php if ( $current_template == esc_url($template->location) ) echo 'selected'; ?>><?php echo esc_html($template->name); ?></option>
	<?php } ?>
</select>
