<form action="" method="POST" name="wpeasycart_admin_setup_wizard_form" id="wpeasycart_admin_setup_wizard_form" novalidate="novalidate">
	<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_nonce', 'wp-easycart-process-wizard-page-setup' ); ?>
	<input type="hidden" name="ec_admin_form_action" id="ec_admin_form_action" value="process-wizard-page-setup">
	<h3><?php esc_attr_e( 'Page Setup', 'wp-easycart' ); ?></h3>
	<p><?php echo sprintf( esc_attr__( 'Your store needs a few pages to operate. The following pages have %salready been created automatically%s for you (if they did not already exist).', 'wp-easycart' ), '<strong>', '</strong>' ); ?></p>
	<div class="ec_admin_wizard_page_row">
		<div class="ec_admin_wizard_page_row_title"><a href="<?php echo esc_url_raw( wp_easycart_admin( )->store_page ); ?>" target="_blank"><?php esc_attr_e( 'Store', 'wp-easycart' ); ?></a></div>
		<div class="ec_admin_wizard_page_row_content"><?php esc_attr_e( 'This is where your customers will enter your store and begin shopping.', 'wp-easycart' ); ?></div>
		<div style="clear:both;"></div>
	</div>
	<div class="ec_admin_wizard_page_row">
		<div class="ec_admin_wizard_page_row_title"><a href="<?php echo esc_url_raw( wp_easycart_admin( )->cart_page ); ?>" target="_blank"><?php esc_attr_e( 'Shopping Cart', 'wp-easycart' ); ?></a></div>
		<div class="ec_admin_wizard_page_row_content"><?php esc_attr_e( 'This page displays the shopping cart and checkout to the customer.', 'wp-easycart' ); ?></div>
		<div style="clear:both;"></div>
	</div>
	<div class="ec_admin_wizard_page_row">
		<div class="ec_admin_wizard_page_row_title"><a href="<?php echo esc_url_raw( wp_easycart_admin( )->account_page ); ?>" target="_blank"><?php esc_attr_e( 'My Account', 'wp-easycart' ); ?></a></div>
		<div class="ec_admin_wizard_page_row_content"><?php esc_attr_e( 'This page displays the customer\'s account, order history, and other information.', 'wp-easycart' ); ?></div>
		<div style="clear:both;"></div>
	</div>
	<div class="ec_admin_wizard_page_row">
		<p><?php echo sprintf( esc_attr__( 'We recommend adding your store, account, and cart to your menu. You can show these pages on your site by adding them to your menu via %s Appearance >> Menus %s', 'wp-easycart' ), '<a href="nav-menus.php" target="_blank">', '</a>' ); ?></p>
		<div style="clear:both;"></div>
	</div>

	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'Cache Compatibility', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'Caching is the process of serving a snapshot of a page to every user, which makes your site fast! The problem is that a shopping cart needs to display something different for each customer. EasyCart has that handled by loading your cart and account dynamically. Keep this feature on for best compatibility.', 'wp-easycart' ); ?></div>
		<label class="ec_admin_wizard_input_row_toggle">
			<input type="checkbox" name="ec_option_cache_prevent" id="ec_option_cache_prevent" checked="checked" />
			<span class="ec_admin_wizard_slider round" style="top:-7px;"></span>
		</label>
		<div style="clear:both;"></div>
	</div>

	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'SSL &amp; Secure Sites', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php esc_attr_e( 'SSL Certificates are what make the connection between your customer and your cart secure. You will need to get one from your hosting provider to securely process credit cards and may cost you extra. The good news is that once installed, everyone will see a nice secure lock in their browser. We recommend leaving this setting on to automatically force your EasyCart into a secure state. If you see a warning in the browser, you\'ll know to get an SSL certificate from your host.', 'wp-easycart' ); ?></div>
		<label class="ec_admin_wizard_input_row_toggle">
			<input type="checkbox" name="ec_option_load_ssl" id="ec_option_load_ssl" checked="checked" />
			<span class="ec_admin_wizard_slider round" style="top:-7px;"></span>
		</label>
		<div style="clear:both;"></div>
	</div>

	<div class="ec_admin_wizard_page_row" style="padding:30px 0;">
		<div class="ec_admin_wizard_page_row_title"><strong><?php esc_attr_e( 'SEO Friendly Links', 'wp-easycart' ); ?></strong></div>
		<div class="ec_admin_wizard_page_row_content" style="padding-right:100px;"><?php echo sprintf( esc_attr__( 'Permalinks are what WordPress calls SEO Friendly links and the EasyCart offers this to those that have the correct server setup. We recommend this option because it will turn store links from %s to %s, which search engines love! If you turn this on and notice an issue accessing your products, contact us for help!', 'wp-easycart' ), 'https://www.yoursite.com/store/?model_number=XYZ', 'https://www.yoursite.com/store/my-widget/' ); ?></div>
		<label class="ec_admin_wizard_input_row_toggle">
			<input type="checkbox" name="ec_option_use_old_linking_style" id="ec_option_use_old_linking_style" checked="checked" />
			<span class="ec_admin_wizard_slider round" style="top:-7px;"></span>
		</label>
		<div style="clear:both;"></div>
	</div>

	<div class="ec_admin_wizard_button_bar">
		<a href="admin.php?page=wp-easycart-settings&ec_admin_form_action=skip-wizard&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-skip-wizard' ) ); ?>" class="ec_admin_wizard_quit_button"><?php esc_attr_e( 'Skip Setup Wizard', 'wp-easycart' ); ?></a>
		<a href="admin.php?page=wp-easycart-products&subpage=products"><?php esc_attr_e( 'Setup Later', 'wp-easycart' ); ?></a>
		<input type="submit" class="ec_admin_wizard_next_button" value="<?php esc_attr_e( 'Save &amp; Continue', 'wp-easycart' ); ?>" />
	</div>
</form>