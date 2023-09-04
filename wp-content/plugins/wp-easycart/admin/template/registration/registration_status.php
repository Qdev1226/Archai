<div class="ec_admin_settings_panel">
	<div class="ec_admin_important_numbered_list_fullwidth">
		<div class="ec_admin_list_line_item_fullwidth ec_admin_demo_data_line">

			<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_registration_loader" ); ?>

			<div class="ec_admin_settings_label">
				<div class="dashicons-before dashicons-unlock"></div>
				<span><?php esc_attr_e( 'Registration & Activation', 'wp-easycart' ); ?></span>
				<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'registration', 'registration' ) ); ?>" target="_blank" class="ec_help_icon_link">
					<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
				</a>
				<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'registration', 'registration');?>
			</div>

			<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
				<style>input {margin-top: 0px !important;}</style>
				<?php if( isset( $_GET['success'] ) && $_GET['success'] == 'deactivate-complete' ){ ?>
				<div id='setting-error-settings_updated' class='updated settings-success' style="margin:0 0 10px;">
					<p><strong><?php esc_attr_e( 'EasyCart Successfully Deactivated! You may now use your license key elsewhere.', 'wp-easycart' ); ?></strong></p>
				</div>
				<?php }else if( isset( $_GET['error'] ) && $_GET['error'] == 'deactivate-no-key-found' ){ ?>
				<div id='setting-error-settings_updated' class='updated error' style="margin:0 0 10px;">
					<p><strong><?php esc_attr_e( 'No License Key found with that value, please check your value.', 'wp-easycart' ); ?></strong></p>
				</div> 
				<?php }else if( isset( $_GET['error'] ) && $_GET['error'] == 'deactivate-registration-failed' ){ ?>
				<div id='setting-error-settings_updated' class='updated error' style="margin:0 0 10px;">
					<p><strong><?php esc_attr_e( 'There was an error Deactivating EasyCart, please try again at a later time.', 'wp-easycart' ); ?></strong></p>
				</div> 
				<?php }?>

				<?php if( isset( $_GET['success'] ) && $_GET['success'] == 'activate-complete' ){ ?>
				<div id='setting-error-settings_updated' class='updated settings-success' style="margin:0 0 10px;">
					<p><strong><?php esc_attr_e( 'EasyCart Successfully Activated! You now have access to new sections within EasyCart.', 'wp-easycart' ); ?></strong></p>
				</div>
				<?php }else if( isset( $_GET['error'] ) && $_GET['error'] == 'activate-no-key-found' ){ ?>
				<div id='setting-error-settings_updated' class='updated error' style="margin:0 0 10px;">
					<p><strong><?php esc_attr_e( 'No License Key found with that value, please check your value.', 'wp-easycart' ); ?></strong></p>
				</div> 
				<?php }else if( isset( $_GET['error'] ) && $_GET['error'] == 'activate-registration-failed' ){ ?>
				<div id='setting-error-settings_updated' class='updated error' style="margin:0 0 10px;">
					<p><strong><?php esc_attr_e( 'There was an error activating EasyCart, please try again at a later time.', 'wp-easycart' ); ?></strong></p>
				</div> 
				<?php }?>

				<?php if( $license_status == 'deactivated' ){ ?>
				<span style="font-size:32px; font-weight:normal; margin:0 0 10px; display:block;"><?php esc_attr_e( 'Try WP EasyCart PRO FREE for 14 Days', 'wp-easycart' ); ?></span>
				<p style="margin-top:0px;"><?php esc_attr_e( 'If you do not have a license, try starting a FREE 14 day trial!', 'wp-easycart' ); ?></p>
				<div class="ec_admin_upgrade_subheader ec_admin_upgrade_box_signup_row" style="text-align:left; padding:0 0 50px;">
					<a href="admin.php?page=wp-easycart-registration&ec_trial=start"><?php esc_attr_e( 'ACTIVATE 14 DAY PRO TRIAL NOW!', 'wp-easycart' ); ?></a>
				</div>

				<hr style="float:left; width:100%;" />

				<span style="font-size:32px; font-weight:normal; margin:25px 0 10px; display:block;"><?php esc_attr_e( 'Already Have a License? Register Your Site:', 'wp-easycart' ); ?></span>

				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=activateregistration" method="POST" id="wpeasycart_admin_form1" novalidate="novalidate">
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
					<div class="ec_admin_settings_input" style="padding:0;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Activate EasyCart License', 'wp-easycart' ); ?>">
					</div>
				</form>

				<br /><br />

				<hr />

				<span style="font-size:32px; font-weight:normal; margin:0 0 10px; display:block;"><?php esc_attr_e( 'Forgot your License Key?', 'wp-easycart' ); ?></span>
				<p><?php esc_attr_e( 'All of your license keys are available by logging into our website at www.wpeasycart.com and visiting your account. You can see past orders, license keys, days left of support & upgrades, as well as what sites are reigstered to what license keys.', 'wp-easycart' ); ?></p>
				<form action="https://www.wpeasycart.com/my-account"  method="POST" target="_blank" id="wpeasycart_admin_form_outside1" novalidate="novalidate">
					<div class="ec_admin_settings_input" style="padding:0;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Visit WP EasyCart Account', 'wp-easycart' ); ?>" >
					</div>
				</form>

				<div style="clear:both;"></div>

				<?php }else if( $license_status == 'activated' ){ 
					$license_data = wp_easycart_admin_license( )->license_data;
					if( $license_data->key_version == 'v3' ){ ?>
				<div class='updated error' style="margin-bottom:20px;">
					<p><strong><?php esc_attr_e( 'It appears we are having trouble communicating with the WP EasyCart licensing server. Please contact us if this continues to be a problem. In most cases we will have the licensing system back up and running in no time!', 'wp-easycart' ); ?></strong></p>
				</div>
				<br />
				<p><?php esc_attr_e( 'All EasyCart licenses are good for use on one WordPress website. You may easily transfer your website license to any other website by simply deactivating your license key. You may also simply enter your license key into a new website and it will automatically deactivate any other websites that may be active.', 'wp-easycart' ); ?></p>
				<p><?php esc_attr_e( 'We make it easy and simple! Each WordPress website requires a license, you can purchase new licenses by visiting www.wpeasycart.com.', 'wp-easycart' ); ?></p>

				<div>
					<span class="ec_language_row_label"><?php esc_attr_e( 'License Version', 'wp-easycart' ); ?>:</span>
					<input type="text"  disabled="disabled" value="Legacy V3 License" />
				</div>
				<br />
				<div>
					<span class="ec_language_row_label"><?php esc_attr_e( 'Registered URL', 'wp-easycart' ); ?>:</span>
					<input type="text"  disabled="disabled" value="<?php echo esc_attr( $license_data->siteurl ); ?>" />
				</div>
				<br />

				<div>
					<span class="ec_language_row_label"><?php esc_attr_e( 'Registration Date', 'wp-easycart' ); ?>:</span>
					<input type="text"  disabled="disabled" value="<?php echo esc_attr( date( "F j, Y, g:i a", strtotime( $license_data->date ) ) );   ?>"  />
				</div>
				<br />

				<form action="https://www.wpeasycart.com/my-account/"  method="POST" target="_blank" id="wpeasycart_admin_form_outside2" novalidate="novalidate">
					<div class="ec_admin_settings_input"><input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'View Account and Upgrade', 'wp-easycart' ); ?>" ></div>
				</form>

				<br />

				<?php esc_attr_e( '**You must login with the same WP EasyCart account you used to purchase this license for it to accurately apply credit.', 'wp-easycart' ); ?>

				<br />

				<hr />

				<span><?php esc_attr_e( 'Would you like to deactivate this site license?', 'wp-easycart' ); ?></span>
				<p><?php esc_attr_e( 'You may enter your original license key and deactivate this site license at anytime. If you are moving to a new server or want to use the license key on a different URL, we make it easy to deactivate your license.  No data is touched during this process.', 'wp-easycart' ); ?></p>

				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=deactivateregistration"  method="POST" id="wpeasycart_admin_form2" novalidate="novalidate">
					<div>
						<input type="text" name="transactionkey" id="transactionkey" required="required" >
					</div>
					<div class="ec_admin_settings_input">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Deactivate EasyCart License', 'wp-easycart' ); ?>" >
					</div>
				</form>

				<br /><br />

				<hr />

				<span><?php esc_attr_e( 'Forgot your License Key?', 'wp-easycart' ); ?><br /><?php esc_attr_e( 'Want to extend Support & Upgrades for EasyCart?', 'wp-easycart' ); ?></span>
				<p><?php esc_attr_e( 'All of your license keys are available by logging into our website at www.wpeasycart.com and visiting your account.  You can see past orders, license keys, days left of support & upgrades, as well as what sites are reigstered to what license keys.', 'wp-easycart' ); ?></p>

				<form action="https://www.wpeasycart.com/my-account"  method="POST" target="_blank" id="wpeasycart_admin_form_outside3" novalidate="novalidate">
					<div class="ec_admin_settings_input">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Visit WP EasyCart Account', 'wp-easycart' ); ?>" >
					</div>
				</form>

				<br /><br />

				<?php }else if( $license_data->key_version == 'v4' ){ ?>

				<span style="font-size:32px; font-weight:normal; margin:0 0 10px; display:block;"><?php esc_attr_e( 'Your License is Active', 'wp-easycart' ); ?></span>
				<ul style="list-style:inherit; padding:0 30px; line-height:1.5em;">
					<li><?php esc_attr_e( 'All EasyCart licenses are good for use on one WordPress website.', 'wp-easycart' ); ?></li>
					<li><?php esc_attr_e( 'You may easily transfer your website license to any other website by deactivating your license key.', 'wp-easycart' ); ?></li>  
					<li><?php esc_attr_e( 'You may also enter your license key into a new website and it will automatically transfer your license to the new site.', 'wp-easycart' ); ?></li>
				</ul>

				<hr />

				<span style="font-size:32px; font-weight:normal; margin:25px 0 10px; display:block;"><?php esc_attr_e( 'License Information', 'wp-easycart' ); ?></span>

				<?php wp_easycart_admin_license( )->license_check( ); ?>
				<?php if( wp_easycart_admin_license( )->active_license == false ){ ?>

				<div id='setting-error-settings_updated' class='updated error'>
					<p><strong><?php esc_attr_e( 'Automatic upgrades have expired!  Please visit below to continue to get security patches and upgrades.', 'wp-easycart' ); ?></strong></p>
				</div>

				<br />

				<?php }else if( wp_easycart_admin_license( )->active_license == true ){ ?>

				<?php } ?>

				<?php $license_info = get_option( 'wp_easycart_license_info' ); ?>

				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=updateregistrationemail"  method="POST" novalidate="novalidate">
					<div class="ec_admin_settings_input" style="padding:0px;">
						<span class="ec_language_row_label"><?php esc_attr_e( 'Email Address', 'wp-easycart' ); ?>:</span>
						<br />
						<input type="email" name="customeremail" id="customeremail" value="<?php echo esc_attr( $license_info['customer_email'] ); ?>" >
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'UPDATE EMAIL ADDRESS', 'wp-easycart' ); ?>" />
					</div>
				</form>

				<div>
					<span class="ec_language_row_label"><?php esc_attr_e( 'License Version', 'wp-easycart' ); ?>:</span>
					<input type="text"  disabled="disabled" value="<?php esc_attr_e( 'V4 License', 'wp-easycart' ); ?>" />
				</div>

				<br />

				<div>
					<span class="ec_language_row_label"><?php esc_attr_e( 'Registered URL', 'wp-easycart' ); ?>:</span>
					<input type="text"  disabled="disabled" value="<?php echo esc_attr( $license_data->siteurl ); ?>" />
				</div>

				<br />

				<div>
					<span class="ec_language_row_label"><?php esc_attr_e( 'Support & Upgrades End', 'wp-easycart' ); ?>:</span>
					<input type="text"  disabled="disabled" value="<?php echo esc_attr( date( "F j, Y", strtotime( $license_data->support_end_date ) ) ); ?>" />
				</div>

				<br />

				<form action="https://www.wpeasycart.com/my-account/" method="POST" target="_blank" id="wpeasycart_admin_form_outside4" novalidate="novalidate">
					<div class="ec_admin_settings_input" style="padding:0px;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'View Account and Upgrade', 'wp-easycart' ); ?>" />
					</div>
				</form>

				<br />

				<?php esc_attr_e( '**You must login with the same WP EasyCart account you used to purchase this license for it to accurately apply credit.', 'wp-easycart' ); ?>

				<br />

				<hr />

				<span style="font-size:32px; font-weight:normal; margin:25px 0 10px; display:block;"><?php esc_attr_e( 'Would you like to deactivate this site license?', 'wp-easycart' ); ?></span>

				<p><?php esc_attr_e( 'You may enter your original license key and deactivate this site license at anytime.  If you are moving to a new server or want to use the license key on a different URL, we make it easy to deactivate your license.  No data is touched during this process.', 'wp-easycart' ); ?></p>

				<form action="admin.php?page=wp-easycart-registration&subpage=registration&ec_action=deactivateregistration"  method="POST" id="wpeasycart_admin_form3" novalidate="novalidate">
					<div><input type="text" name="transactionkey" id="transactionkey" required="required" ></div>
					<div class="ec_admin_settings_input" style="padding:0px;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Deactivate EasyCart License', 'wp-easycart' ); ?>" />
					</div>
				</form>

				<br /><br />

				<hr />

				<span style="font-size:32px; font-weight:normal; margin:25px 0 10px; display:block;"><?php esc_attr_e( 'Forgot your License Key?', 'wp-easycart' ); ?></span>

				<p><?php echo sprintf( esc_attr__( 'All of your license keys are available by logging into our website at %s and visiting your account. You can see past orders, license keys, days left of support & upgrades, as well as what sites are reigstered to what license keys.', 'wp-easycart' ), '<a href="www.wpeasycart.com/my-account" target="_blank">www.wpeasycart.com/my-account</a>' ); ?></p>
				<form action="https://www.wpeasycart.com/my-account"  method="POST" target="_blank" id="wpeasycart_admin_form_outside5" novalidate="novalidate">
					<div class="ec_admin_settings_input" style="padding:0px;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Visit WP EasyCart Account', 'wp-easycart' ); ?>" >
					</div>
				</form>

				<br /><br />

				<hr />

				<span style="font-size:32px; font-weight:normal; margin:25px 0 10px; display:block;"><?php esc_attr_e( 'Extend Support & Upgrades for EasyCart?', 'wp-easycart' ); ?></span>

				<p><?php sprintf( __( 'You can always extend your support & upgrades early! Go to %s and extend your license directly from your account.', 'wp-easycart' ), '<a href="www.wpeasycart.com/my-account" target="_blank">www.wpeasycart.com/my-account</a>' ); ?></p>
				<form action="https://www.wpeasycart.com/my-account"  method="POST" target="_blank" id="wpeasycart_admin_form_outside6" novalidate="novalidate">
					<div class="ec_admin_settings_input" style="padding:0px;">
						<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Visit WP EasyCart Account', 'wp-easycart' ); ?>" >
					</div>
				</form>

				<br /><br />

				<?php } ?>

			<?php }else if( $license_status == 'communications_error' ){ ?>

			<div id='setting-error-settings_updated' class='updated error'>
				<p><strong><?php esc_attr_e( 'Communications Error! Licensing server is down at this time.', 'wp-easycart' ); ?></strong></p>
			</div>
			<p><?php esc_attr_e( 'Registration and Licensing servers are currently down, check back at a later time.', 'wp-easycart' ); ?></p>

			<?php } ?>
		</div>
	</div>
</div>
