<form action="<?php echo esc_attr( $this->action ); ?>"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form" novalidate="novalidate">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-option-details' ); ?>
	<input type="hidden" name="ec_admin_form_action" value="<?php echo esc_attr( $this->form_action ); ?>" />
	<input type="hidden" name="option_id" value="<?php echo esc_attr( $this->option->option_id ); ?>" />

	<div class="ec_admin_settings_panel ec_admin_details_panel">
		<div class="ec_admin_important_numbered_list">
			<div class="ec_admin_flex_row">
				<div class="ec_admin_list_line_item ec_admin_col_12 ec_admin_col_first">

					<div class="ec_admin_settings_label">
						<div class="dashicons-before dashicons-image-filter"></div>
						<span><?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'add-new-option' ){ esc_attr_e( 'ADD NEW OPTION', 'wp-easycart' ); }else{ esc_attr_e( 'EDIT OPTION', 'wp-easycart' ); } ?></span>
						<div class="ec_page_title_button_wrap">
							<a href="<?php echo esc_url_raw( $this->docs_link ); ?>" target="_blank" class="ec_help_icon_link">
								<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
							</a>
							<?php wp_easycart_admin( )->helpsystem->print_vids_url('products', 'option-sets', 'details');?>
							<?php if( isset( $_GET['ec_admin_form_action'] ) && $_GET['ec_admin_form_action'] == 'edit' ) { ?>
							<a href="admin.php?page=wp-easycart-products&subpage=optionitems&option_id=<?php echo esc_attr( $this->option->option_id ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Edit Option Items', 'wp-easycart' ); ?></a>
							<?php } ?>
							<a href="<?php echo esc_attr( $this->action ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></a>
							<input type="submit" value="<?php esc_attr_e( 'Save', 'wp-easycart' ); ?>" onclick="return wpeasycart_admin_validate_form( )" class="ec_page_title_button">
						</div>
					</div>

					<div class="ec_admin_settings_input ec_admin_settings_currency_section">
						<?php do_action( 'wp_easycart_admin_option_details_basic_fields' ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>