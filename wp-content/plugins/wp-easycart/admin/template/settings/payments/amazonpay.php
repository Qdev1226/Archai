<div class="ec_admin_amazonpay_row">
	<div class="ec_admin_slider_row">
		<?php wp_easycart_admin()->preloader->print_preloader( 'ec_admin_amazonpay_display_loader' ); ?>
		<h3>
			<span style="float:left; width:100%;"><?php esc_attr_e( 'Amazon Pay', 'wp-easycart' ); ?></span>
			<a href="<?php echo esc_url_raw( wp_easycart_admin()->helpsystem->print_docs_url( 'settings', 'payment', 'amazonpay' ) ); ?>" target="_blank" class="ec_help_icon_link" title="<?php esc_attr_e( 'View Help?', 'wp-easycart' ); ?>" style="float:left; margin-left:0px;">
				<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
			</a>
		</h3>
		<div class="ec_admin_slider_row_description">
			<div><?php esc_attr_e( 'Allow your customers to checkout using their Amazon account. By enabling this feature, conversion rates increase due to the ease of access to addresses and payment methods that most customers use regularly with their Amazon account. This feature is only available in PRO or Premium WP EasyCart Licenses.', 'wp-easycart' ); ?></div>
		</div>
		<div class="ec_admin_toggles_wrap">
			<div class="ec_admin_toggle">
				<span><?php esc_attr_e( 'Enable', 'wp-easycart' ); ?>:</span>
				<label class="ec_admin_switch">
					<input type="checkbox" onclick="show_pro_required( ); return false;" class="ec_admin_slider_checkbox" value="amazonpay" id="ec_option_use_amazonpay">
					<span class="ec_admin_slider round"></span>
				</label>
			</div>
		</div>
	</div>
</div>
