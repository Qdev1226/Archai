<div class="ec_admin_list_line_item ec_admin_demo_data_line">

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-editor-alignleft"></div>
		<span><?php esc_attr_e( 'Custom CSS', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'design', 'custom-css' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'design', 'custom-css');?>
	</div>

	<div class="ec_admin_settings_input wp_easycart_admin_no_padding">

		<?php wp_easycart_admin( )->load_toggle_group_textarea( 'ec_option_custom_css', 'ec_admin_save_design_text_setting', get_option( 'ec_option_custom_css' ), __( 'Custom CSS', 'wp-easycart' ), __( 'Enter custom CSS rules here to be applied to all EasyCart pages.', 'wp-easycart' ), '', 'ec_admin_ec_option_custom_css_row', true, false ); ?>

	</div>
</div>