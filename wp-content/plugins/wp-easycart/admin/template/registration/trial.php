<?php if( strtotime( date( 'Y-m-d', strtotime( wp_easycart_admin_license( )->license_data->support_end_date ) ) ) < strtotime( date( 'Y-m-d' ) ) ){ ?>
<div class="ec_admin_settings_panel">
	<div class="ec_admin_important_numbered_list_fullwidth">
		<div class="ec_admin_list_line_item_fullwidth ec_admin_demo_data_line">
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_registration_loader" ); ?>
			<div class="ec_admin_settings_label">
				<div class="dashicons-before dashicons-lock"></div>
				<span><?php esc_attr_e( 'Your Trial has Ended', 'wp-easycart' ); ?></span>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
				<style>
				input {margin-top: 0px !important;}
				</style>
				<h3 style="font-size:32px; font-weight:normal; margin:0 0 10px; display:block;"><?php esc_attr_e( 'Your Trial is Over. Upgrade Today!', 'wp-easycart' ); ?></h3>
				<ul style="list-style:inherit; padding:0 30px; line-height:1.5em;">
					<li><?php esc_attr_e( 'All EasyCart licenses are good for use on one WordPress website.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'You may easily transfer your website license to any other website by deactivating your license key.', 'wp-easycart' ); ?></li>  
					<li><?php esc_attr_e( 'You may also enter your license key into a new website and it will automatically transfer your license to the new site.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'Want the FREE version? Delete WP EasyCart PRO from your plugins section to revert to FREE (no data will be lost).', 'wp-easycart' ); ?></li>
				</ul>
				<?php $license_info = get_option( 'wp_easycart_license_info' ); ?>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row" style="text-align:left; padding:0 0 50px;">
					<a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/<?php if( is_array( $license_info ) && isset( $license_info['transaction_key'] ) ){ echo '?transaction_key=' . esc_attr( $license_info['transaction_key'] ); } ?>" target="_blank"><?php esc_attr_e( 'UPGRADE TO PRO/PREMIUM NOW', 'wp-easycart' ); ?></a>
				</div>

				<hr />

				<h3 style="font-size:32px; font-weight:normal; margin:25px 0 0px; display:block;"><?php esc_attr_e( 'Already Purchased? Activate Now!', 'wp-easycart' ); ?></h3>
				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=activateregistration"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form1" novalidate="novalidate">
					<div>
						<span class="ec_language_row_label"><?php esc_attr_e( 'Full Name (First & Last)', 'wp-easycart' ); ?>:</span>
						<br /> 
						<input type="text" name="customername" id="customername" required="required" >
					</div>
					<br />
					<div>
						<span class="ec_language_row_label"><?php esc_attr_e( 'Email Address', 'wp-easycart' ); ?>:</span>
						<br /> 
						<input type="email" name="customeremail" id="customeremail" required="required" >
					</div>
					<br />
					<div>
						<span class="ec_language_row_label"><?php esc_attr_e( 'License Key', 'wp-easycart' ); ?>:</span>
						<br /> 
						<input type="text" name="transactionkey" id="transactionkey" required="required" >
					</div>
					<br />
					<div class="ec_admin_settings_input" style="padding:0px;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'ACTIVATE LICENSE', 'wp-easycart' ); ?>" style="font-weight:normal; padding:12px 20px; border-radius:5px; font-size:15px;">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php }else{ ?>
<div class="ec_admin_settings_panel">
	<div class="ec_admin_important_numbered_list_fullwidth">
		<div class="ec_admin_list_line_item_fullwidth ec_admin_demo_data_line">
			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_registration_loader" ); ?>
			<div class="ec_admin_settings_label">
				<div class="dashicons-before dashicons-admin-network"></div>
				<span><?php esc_attr_e( 'Your Trial is Activated', 'wp-easycart' ); ?></span>
			</div>
			<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
				<style> input {margin-top: 0px !important;} </style>

				<h3 style="font-size:32px; font-weight:normal; margin:0 0 10px; display:block;"><?php esc_attr_e( 'Ready to Sell Like a Pro? Upgrade Now!', 'wp-easycart' ); ?></h3>

				<ul style="list-style:inherit; padding:0 30px; line-height:1.5em;">
					<li><?php esc_attr_e( 'All EasyCart licenses are good for use on one WordPress website.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'You may easily transfer your website license to any other website by deactivating your license key.', 'wp-easycart' ); ?></li>  
					<li><?php esc_attr_e( 'You may also enter your license key into a new website and it will automatically transfer your license to the new site.', 'wp-easycart' ); ?></li>
				</ul>

				<?php $license_info = get_option( 'wp_easycart_license_info' ); ?>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row" style="text-align:left; padding:0 0 50px;">
					<a href="https://www.wpeasycart.com/products/wp-easycart-trial-upgrade/<?php if( is_array( $license_info ) && isset( $license_info['transaction_key'] ) ){ echo '?transaction_key=' . esc_attr( $license_info['transaction_key'] ); } ?>" target="_blank"><?php esc_attr_e( 'UPGRADE TO PRO/PREMIUM NOW', 'wp-easycart' ); ?></a>
				</div>

				<hr style="float:left; width:100%; margin:5px 0 25px;" />

				<?php $license_info = get_option( 'wp_easycart_license_info' ); ?>
				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=updateregistrationemail" method="POST" name="wpeasycart_admin_form" novalidate="novalidate">
					<div class="ec_admin_settings_input" style="padding:0px;">
						<span class="ec_language_row_label" style="font-weight:normal; font-size:18px;"><?php esc_attr_e( 'Trial Email Address', 'wp-easycart' ); ?>:</span>
						<br />
						<input type="email" name="customeremail" id="customeremail" value="<?php echo esc_attr( $license_info['customer_email'] ); ?>" >
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'UPDATE EMAIL ADDRESS', 'wp-easycart' ); ?>" />
					</div>
				</form>

				<hr style="float:left; width:100%; margin:25px 0;" />

				<h3 style="font-size:32px; font-weight:normal; margin:25px 0 0px; display:block;"><?php esc_attr_e( 'Already Purchased? Activate Now!', 'wp-easycart' ); ?></h3>
				<h6 style="font-size:14px; font-weight:lighter; color:#666; margin:0 0 10px;"><?php esc_attr_e( 'Upgrade from your Trial to PRO or Premium by clicking the button above or purchase a new license from www.wpeasycart.com and enter it below, both ways will work!', 'wp-easycart' ); ?></h6>

				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=activateregistration"  method="POST" name="wpeasycart_admin_form" id="wpeasycart_admin_form1" novalidate="novalidate">
					<div>
						<span class="ec_language_row_label"><?php esc_attr_e( 'Full Name (First & Last)', 'wp-easycart' ); ?>:</span>
						<br /> 
						<input type="text" name="customername" id="customername" required="required" >
					</div>
					<br />
					<div>
						<span class="ec_language_row_label"><?php esc_attr_e( 'Email Address', 'wp-easycart' ); ?>:</span>
						<br /> 
						<input type="email" name="customeremail" id="customeremail" required="required" >
					</div>
					<br />
					<div>
						<span class="ec_language_row_label"><?php esc_attr_e( 'License Key', 'wp-easycart' ); ?>:</span>
						<br /> 
						<input type="text" name="transactionkey" id="transactionkey" required="required" >
					</div>
					<br />
					<div class="ec_admin_settings_input" style="padding:0px;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'ACTIVATE LICENSE', 'wp-easycart' ); ?>" style="font-weight:normal; padding:12px 20px; border-radius:5px; font-size:15px;">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php }?>