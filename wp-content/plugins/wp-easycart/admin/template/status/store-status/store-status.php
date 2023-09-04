<div class="ec_admin_list_line_item_fullwidth ec_admin_demo_data_line">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_store_status_loader" ); 
		global $wpdb;
		$status = new wp_easycart_admin_store_status();
	?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Store & Server Status', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'store-status', 'settings' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'store-status', 'settings');?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		<?php
		///////////////////////////////////////////////
		// Database Status Section
		///////////////////////////////////////////////
		?>
		<div class="ec_status_header"><div class="ec_status_header_text"><?php esc_attr_e( 'Database Status', 'wp-easycart' ); ?></div></div>
		<?php if( $errors = $status->database_check( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div>
			<span class="ec_status_label"><?php esc_attr_e( 'We have found problems with your WP EasyCart database structure.', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a>
			<br />
			<span id="wpeasycart_database_errors_min_status"><?php esc_attr_e( 'For Complete Details', 'wp-easycart' ); ?> <a href="#" onclick="jQuery( '#wpeasycart_database_errors_status' ).show( ); jQuery( '#wpeasycart_database_errors_min_status' ).hide( ); return false;"><?php esc_attr_e( 'Click Here', 'wp-easycart' ); ?></a></span>
			<ul id="wpeasycart_database_errors_status" style="display:none;">
			<?php foreach( $errors as $error ){
				echo '<li>' . esc_attr( $error['error'] ) . '</li>';
			} ?>
			</ul>
			</span>
		</div>

		<?php }else{ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your database is currently correctly formatted and not missing any tables or columns.', 'wp-easycart' ); ?></span></div>

		<?php if( $errors = $status->settings_check() ) { ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your settings data is setup.', 'wp-easycart' ); ?></span></div>
		<?php } else { ?>
		<div class='ec_status_error'><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Your settings data is missing!', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database-data&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database-data' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a></span></div> 
		<?php } ?>

		<?php if( $status->countries_check() ) { ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your country data is setup.', 'wp-easycart' ); ?></span></div>
		<?php } else { ?>
		<div class='ec_status_error'><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Your country data is missing!', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database-data&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database-data' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a></span></div> 
		<?php } ?>

		<?php if( $status->order_status_check() ) { ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your order status data is setup.', 'wp-easycart' ); ?></span></div>
		<?php } else { ?>
		<div class='ec_status_error'><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Your order status data is missing!', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database-data&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database-data' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a></span></div> 
		<?php } ?>

		<?php if( $status->timezone_check() ) { ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your timezone data is setup.', 'wp-easycart' ); ?></span></div>
		<?php } else { ?>
		<div class='ec_status_error'><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Your timezone data is missing!', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database-data&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database-data' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a></span></div> 
		<?php } ?>

		<?php if( $status->state_check() ) { ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your state data is setup.', 'wp-easycart' ); ?></span></div>
		<?php } else { ?>
		<div class='ec_status_error'><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Your state data is missing!', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database-data&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database-data' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a></span></div> 
		<?php } ?>

		<?php if( $status->zone_check() ) { ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Your shipping zone data is setup.', 'wp-easycart' ); ?></span></div>
		<?php } else { ?>
		<div class='ec_status_error'><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Your shipping zone data is missing!', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=repair-database-data&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-action-repair-database-data' ) ); ?>"><?php esc_attr_e( 'Click to Repair!', 'wp-easycart' ); ?></a></span></div> 
		<?php } ?>

		<?php }?>

		<?php
		///////////////////////////////////////////////
		// Server Status Section
		///////////////////////////////////////////////
		?>

		<div class="ec_status_header"><div class="ec_status_header_text"><?php esc_attr_e( 'Server Settings Status', 'wp-easycart' ); ?></div></div>

		<?php 
		////////////////////////////
		// PHP Versoin Check
		////////////////////////////
		if( $status->ec_get_php_version( ) < 5.3 ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'PHP 5.3 is the mimimal version accepted. We do not guarantee functionality for PHP versions below 5.3 at this time.', 'wp-easycart' ); ?></span></div>
		<?php }else{ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php echo sprintf( esc_attr__( 'Your PHP Version is %s, meeting the PHP 5.3 minimal setup.', 'wp-easycart' ), esc_attr( $status->ec_get_php_version( ) ) ); ?></span></div>
		<?php } ?>

		<div class="ec_status_subs ec_status_success">
			<strong><?php esc_attr_e( 'Common PHP Settings', 'wp-easycart' ); ?></strong><br />
			<p><?php esc_attr_e( 'These settings are something you should contact your web hosting company regarding installation of modules and PHP setting adjustments.  This is likely not something EasyCart technicians would be able to assist with.', 'wp-easycart' ); ?></p>
			<p><i><strong><?php esc_attr_e( 'Note: EasyCart may operate just fine in some situations with warnings in this section as some modules and settings only affect certain areas or features.  Refer to this section if you begin experiencing problems with EasyCart.', 'wp-easycart' ); ?></strong></i></p>

		<?php
			// ======= Allow URL Fopen =======
			if (ini_get("allow_url_fopen") != "1")
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'URL fopen disabled', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'URL fopen enabled', 'wp-easycart' ); ?></div><?php
			}

			// ======= File Uploads =======
			if (ini_get("file_uploads") != "1")
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'File Uploads disabled', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'File Uploads enabled', 'wp-easycart' ); ?></div><?php
			}
			// ======= openSSL =======
			$isopenssl = extension_loaded("openssl");
			if (!$isopenssl)
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Open SSL Not Installed', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Open SSL Installed', 'wp-easycart' ); ?></div><?php
			}
			// ======= Curl =======
			$iscurl = extension_loaded("curl");
			if (!$iscurl)
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Curl Not Installed', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Curl Installed', 'wp-easycart' ); ?></div><?php
			}
			// ======= GD =======
			$isgd = extension_loaded("gd");
			if (!$isgd)
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'GD Not Installed', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'GD Installed', 'wp-easycart' ); ?></div><?php
			}
			// ======= SOAP =======
			$isSOAP = extension_loaded("SOAP");
			if (!$isSOAP)
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'SOAP Not Installed', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'SOAP Installed', 'wp-easycart' ); ?></div><?php
			}
			// ======= MySQL =======
			$isMySQL = extension_loaded("MySQL");
			$isMySQLi = extension_loaded("MySQLi");
			if( !$isMySQL && !$isMySQLi )
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'MySQL Not Installed', 'wp-easycart' ); ?></div><?php 
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'MySQL Installed', 'wp-easycart' ); ?></div><?php
			}
			// ======= Max File Upload Size =======
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div>
			<?php esc_attr_e( 'Max PHP File Upload Size (Recommended >10M)', 'wp-easycart' ); ?>: <?php echo esc_attr( ini_get( "upload_max_filesize" ) ); ?>
			</div><?php
			// ======= max_execution_time =======
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div>
			<?php esc_attr_e( 'Max PHP Execution Time (Recommended >300)', 'wp-easycart' ); ?>: <?php echo esc_attr( ini_get( "max_execution_time" ) ); ?>
			</div><?php
			// ======= memory_limit =======
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div>
			<?php esc_attr_e( 'PHP Memory Limit (Recommended >128M)', 'wp-easycart' ); ?>: <?php echo esc_attr( ini_get( "memory_limit" ) ); ?>
			</div><?php
			// ======= post_max_size =======
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div>
			<?php esc_attr_e( 'Max PHP Post Size (Recommended >10M)', 'wp-easycart' ); ?>: <?php echo esc_attr( ini_get( "post_max_size" ) ); ?>
			</div><?php
			/*// ======= Output  Buffering =======
			if (ini_get("output_buffering") == 0)
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Output Buffering OFF', 'wp-easycart' ); ?></div><?php 
			} if (ini_get("output_buffering") == 1) {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Output Buffering ON', 'wp-easycart' ); ?></div><?php
			}
			// ======= oAuth =======
			if (!class_exists( 'OAuth' ))
			{
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'oAuth Not Installed', 'wp-easycart' ); ?></div><?php 
			} if (class_exists( 'OAuth' )) {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'oAuth Installed', 'wp-easycart' ); ?></div><?php
			}*/
			// ======= create directory =======
			$to = dirname( __FILE__ ) . "/../../../../testfolder/";
			$success = mkdir( $to, 0777 );
			if( !$success ){
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Create Directories', 'wp-easycart' ); ?></div><?php  
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Success Creating Directories', 'wp-easycart' ); ?></div><?php
			}
			// ======= remove directory =======
			if ($success) {
				$to = dirname( __FILE__ ) . "/../../../../testfolder/";
				$remove = rmdir( $to );
				if( !$remove ){
				?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Remove Directories', 'wp-easycart' ); ?></div><?php 
				} else {
				?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Success Removing Directories', 'wp-easycart' ); ?></div><?php
				}
			} else {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Remove Directories', 'wp-easycart' ); ?></div><?php 
			}

			// ======= test file write to plugins directory =======
			$ec_test_php = 'test file write'; 

			$ec_test_filename = dirname( __FILE__ ) . "/../../../../../testfile.php";
			$ec_test_filehandler = fopen($ec_test_filename, 'w');
			if(!$ec_test_filehandler) {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Open File in Plugin Directory', 'wp-easycart' ); ?></div><?php 
			} else {
				if(!fwrite($ec_test_filehandler, $ec_test_php)) {
				?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Write File to Plugin Directory', 'wp-easycart' ); ?></div><?php 
				} else {
					if(!fclose($ec_test_filehandler)) {
						?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Close File in Plugin Directory', 'wp-easycart' ); ?></div><?php 
						unlink($ec_test_filename);
					} else {
						?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Success in Writing Files to Plugin Directory', 'wp-easycart' ); ?></div><?php
						unlink($ec_test_filename);
					}
				}
			}

			// ======= permalinks test =======
			if( strstr( get_option( 'permalink_structure' ), '%postname%' ) === FALSE ) {?>
				<div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Not using Post Name permalinks, which could have negative effects on your store. Edit this in your Settings -> Permalinks', 'wp-easycart' ); ?></div><?php 
			} else {?>
				<div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Post Name permalinks in use', 'wp-easycart' ); ?></div><?php
			}

			// ======= test file write to easycart plugin directory =======
			$ec_test_php = 'test file write'; 

			$ec_test_filename = dirname( __FILE__ ) . "/../../../../testfile.php";
			$ec_test_filehandler = fopen($ec_test_filename, 'w');
			if(!$ec_test_filehandler) {
			?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Open File in EasyCart Directory', 'wp-easycart' ); ?></div><?php 
			} else {
				if(!fwrite($ec_test_filehandler, $ec_test_php)) {
				?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Write File to EasyCart Directory', 'wp-easycart' ); ?></div><?php 
				} else {
					if(!fclose($ec_test_filehandler)) {
						?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Failed to Close File in EasyCart Directory', 'wp-easycart' ); ?></div><?php 
						unlink($ec_test_filename);
					} else {
						?><div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Success in Writing Files to EasyCart Directory', 'wp-easycart' ); ?></div><?php
						unlink($ec_test_filename);
					}
				}
			}

			// ======= simpleXMLElement =======
			if( !class_exists( 'SimpleXMLElement' ) ){
				$using_live_shipping_with_sxml = false;
				if( $status->ec_using_live_shipping( ) && $status->ec_using_canadapost_shipping( ) && $status->ec_canadapost_shipping_setup( ) ){
					$using_live_shipping_with_sxml = true;
				}else if( $status->ec_using_live_shipping( ) && $status->ec_using_dhl_shipping( ) && $status->ec_dhl_shipping_setup( ) ){
					$using_live_shipping_with_sxml = true;
				}else if( $status->ec_using_live_shipping( ) && $status->ec_using_usps_shipping( ) && $status->ec_usps_shipping_setup( ) ){
					$using_live_shipping_with_sxml = true;
				}else if( $status->ec_using_live_shipping( ) && $status->ec_using_ups_shipping( ) && $status->ec_ups_shipping_setup( ) ){
					$using_live_shipping_with_sxml = true;
				}

				if( $using_live_shipping_with_sxml ){ ?>
				<div class="ec_status_subtitles"><div class="dashicons-before dashicons-warning"></div><?php esc_attr_e( 'Your live shipping setup requires the PHP extension SimpleXMLElement to function correctly. Please contact your host to enable this feature.', 'wp-easycart' ); ?></div>   
				<?php }else{ ?>
				<div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'Some live shipping requires the SimpleXMLElement php extension. Currently this is not a problem, but if you add one of the live shippers that requires this, you may have problems.', 'wp-easycart' ); ?></div>
				<?php }
			}else{ ?>
				<div class="ec_status_subtitles"><div class="dashicons-before dashicons-yes"></div><?php esc_attr_e( 'SimpleXMLElement installed.', 'wp-easycart' ); ?></div>
			<?php }
		?>
		</div>
		<?php
		///////////////////////////////////////////////
		// EasyCart Status Section
		///////////////////////////////////////////////
		?>
		<div class="ec_status_header"><div class="ec_status_header_text"><?php esc_attr_e( 'EasyCart Setup Status', 'wp-easycart' ); ?> - <a href="http://docs.wpeasycart.com/wp-easycart-installation-guide/" target="_blank"><?php esc_attr_e( 'Click Here', 'wp-easycart' ); ?></a> <?php esc_attr_e( 'for our Installation Guide', 'wp-easycart' ); ?></div></div>
		<?php
		////////////////////////////
		// Data Folder Check
		////////////////////////////
		if( $status->wpeasycart_is_data_folder_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Data Folders Setup Correctly', 'wp-easycart' ); ?></span></div>
		<?php }else{ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=fix-data-folders&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-fix-data-folders' ) ); ?>"><?php esc_attr_e( 'Fix Errors', 'wp-easycart' ); ?></a> <?php echo esc_attr( $status->ec_get_data_folders_error( ) ); ?></span></div>
		<?php } ?>

		<?php
		////////////////////////////
		// Store Page Setup Check
		////////////////////////////
		if( $status->ec_is_store_page_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Store Page Setup &amp; Connected Correctly', 'wp-easycart' ); ?></span></div>
		<?php }else{ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php echo esc_attr( $status->ec_get_store_page_error( ) ); ?></span></div>
		<?php } ?>

		<?php
		////////////////////////////
		// Cart Page Setup Check
		////////////////////////////
		if( $status->ec_is_cart_page_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Cart Page Setup &amp; Connected Correctly', 'wp-easycart' ); ?></span></div>
		<?php }else{ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php echo esc_attr( $status->ec_get_cart_page_error( ) ); ?></span></div>
		<?php } ?>

		<?php
		////////////////////////////
		// Account Page Setup Check
		////////////////////////////
		if( $status->ec_is_account_page_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'Account Page Setup &amp; Connected Correctly', 'wp-easycart' ); ?></span></div>
		<?php }else{ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php echo esc_attr( $status->ec_get_account_page_error( ) ); ?></span></div>
		<?php } ?>

		<?php
		///////////////////////////////////////////////
		// Shipping Status Section
		///////////////////////////////////////////////
		?>
		<div class="ec_status_header"><div class="ec_status_header_text"><?php esc_attr_e( 'Shipping Status', 'wp-easycart' ); ?> - <a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=shipping-rates" target="_blank"><?php esc_attr_e( 'Click Here', 'wp-easycart' ); ?></a> <?php esc_attr_e( 'for Shipping Setup Help', 'wp-easycart' ); ?></div></div>
		<?php

		////////////////////////////
		// No Shipping Check
		////////////////////////////
		if( $status->ec_using_method_shipping( ) == false && $status->ec_using_live_shipping( ) == false && $status->ec_using_price_shipping( ) == false && $status->ec_using_weight_shipping( ) == false && $status->ec_using_quantity_shipping( ) == false && $status->ec_using_percentage_shipping( ) == false && $status->ec_using_fraktjakt_shipping( ) == false){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'No shipping methods have been setup at this time.', 'wp-easycart' ); ?></span></div>
		<?php }



		////////////////////////////
		// Price Based Shipping Check
		////////////////////////////
		if( $status->ec_using_price_shipping( ) && $status->ec_price_shipping_setup( )  ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup price based shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_price_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have chosen to use price based shipping, but there doesn\'t appear to be any price triggers setup.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Weight Based Shipping Check
		////////////////////////////
		if( $status->ec_using_weight_shipping( ) && $status->ec_weight_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup weight based shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_weight_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have chosen to use weight based shipping, but there doesn\'t appear to be any weight triggers setup.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Quantity Based Shipping Check
		////////////////////////////
		if( $status->ec_using_quantity_shipping( ) && $status->ec_quantity_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup quantity based shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_quantity_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have chosen to use quantity based shipping, but there doesn\'t appear to be any quantity triggers setup.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Percentage Based Shipping Check
		////////////////////////////
		if( $status->ec_using_percentage_shipping( ) && $status->ec_percentage_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup percentage based shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_percentage_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have chosen to use percentage based shipping, but there doesn\'t appear to be any percentage triggers setup.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Method Based Shipping Check
		////////////////////////////
		if( $status->ec_using_method_shipping( ) && $status->ec_method_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup method based shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_method_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have chosen to use method based shipping, but there doesn\'t appear to be any method triggers setup.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Live Based Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && !$status->ec_live_shipping_setup( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have live shipping selected, but no rates are setup.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// UPS Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && $status->ec_using_ups_shipping( ) && $status->ec_ups_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup UPS live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_live_shipping( ) && $status->ec_using_ups_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'UPS live shipping setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// USPS Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && $status->ec_using_usps_shipping( ) && $status->ec_usps_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup USPS live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_live_shipping( ) && $status->ec_using_usps_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'USPS live shipping setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// FEDEX Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && $status->ec_using_fedex_shipping( ) && $status->ec_fedex_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup FedEx live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_live_shipping( ) && $status->ec_using_fedex_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'FedEx live shipping setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// DHL Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && $status->ec_using_dhl_shipping( ) && $status->ec_dhl_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup DHL live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_live_shipping( ) && $status->ec_using_dhl_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'DHL live shipping setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// AUSPOST Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && $status->ec_using_auspost_shipping( ) && $status->ec_auspost_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup Australia Post live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_live_shipping( ) && $status->ec_using_auspost_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Australia Post live shipping setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php } 

		////////////////////////////
		// Canada Post Shipping Check
		////////////////////////////
		if( $status->ec_using_live_shipping( ) && $status->ec_using_canadapost_shipping( ) && $status->ec_canadapost_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup Canada Post live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_live_shipping( ) && $status->ec_using_canadapost_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Canada Post live shipping setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php } 

		////////////////////////////
		// Fraktjakt Shipping Check
		////////////////////////////
		if( $status->ec_using_fraktjakt_shipping( ) && $status->ec_fraktjakt_shipping_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully setup Fraktjakt live shipping.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_fraktjakt_shipping( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'Fraktjakt live shipping is setup incorrectly.', 'wp-easycart' ); ?></span></div>
		<?php } 

		///////////////////////////////////////////////
		// Tax Status Section
		///////////////////////////////////////////////
		?>

		<div class="ec_status_header"><div class="ec_status_header_text"><?php esc_attr_e( 'Tax Status', 'wp-easycart' ); ?> - <a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=taxes" target="_blank"><?php esc_attr_e( 'Click Here', 'wp-easycart' ); ?></a> <?php esc_attr_e( 'for Tax Setup Help', 'wp-easycart' ); ?></div></div>

		<?php 
		////////////////////////////
		// No Tax Check
		////////////////////////////
		if( $status->ec_using_no_tax( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You are setup to use no tax structure, this can be changed in the Store Admin -> Rates -> Tax Rates panel.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// State Tax Check
		////////////////////////////
		if( $status->ec_using_state_tax( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully configured state/province taxes.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Country Tax Check
		////////////////////////////
		if( $status->ec_using_country_tax( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully configured country taxes.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Gloabl Tax Check
		////////////////////////////
		if( $status->ec_using_global_tax( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully configured global taxes.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// Duty Tax Check
		////////////////////////////
		if( $status->ec_using_duty_tax( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully configured customs duty or export taxes.', 'wp-easycart' ); ?></span></div>
		<?php }

		////////////////////////////
		// VAT Tax Check
		////////////////////////////
		if( $status->ec_using_vat_tax( ) && $status->ec_global_vat_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have successfully configured VAT.', 'wp-easycart' ); ?></span></div>
		<?php }else if( $status->ec_using_vat_tax( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have selected to use VAT, but have not entered a rate and/or have not set any individual country rates.', 'wp-easycart' ); ?></span></div>
		<?php } ?>




		<div class="ec_status_header">
			<div class="ec_status_header_text"><?php esc_attr_e( 'Payment Status', 'wp-easycart' ); ?> - 
				<a href="http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?section=payment" target="_blank"><?php esc_attr_e( 'Click Here', 'wp-easycart' ); ?></a> <?php esc_attr_e( 'for Payment Setup Help', 'wp-easycart' ); ?>
			</div>
		</div>
		<?php

		///////////////////////////////////////////////
		// Payment Status Section
		///////////////////////////////////////////////

		////////////////////////////
		// No Payment Type Selected Check
		////////////////////////////
		if( $status->ec_no_payment_selected( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php esc_attr_e( 'You have not selected a payment method, the checkout process cannot be completed by your customers at this time.', 'wp-easycart' ); ?></span></div>
		<?php } 

		////////////////////////////
		// Manual Payment Type Selected Check
		////////////////////////////
		if( $status->ec_manual_payment_selected( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php esc_attr_e( 'You have setup your store to use manual payment. This method requires your customer to send a check or direct deposit before shipping.', 'wp-easycart' ); ?></span></div>
		<?php } 

		////////////////////////////
		// Third Party Payment Type Selected Check
		////////////////////////////
		if( $status->ec_third_party_payment_selected( ) && $status->ec_third_party_payment_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php echo sprintf( esc_attr__( 'You have selected to use %s as a third party payment method and you have entered all necessary info.', 'wp-easycart' ), esc_attr( $status->ec_get_third_party_method( ) ) ); ?></span></div>
		<?php }else if( $status->ec_third_party_payment_selected( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php echo sprintf( esc_attr__( 'You have selected %s, but have missed some necessary info. Go to WP EasyCart -> Settings -> Payment to resolve this.', 'wp-easycart' ), esc_attr( $status->ec_get_third_party_method( ) ) ); ?></span></div>
		<?php } 

		////////////////////////////
		// Live Payment Type Selected Check
		////////////////////////////
		if( $status->ec_live_payment_selected( ) && $status->ec_live_payment_setup( ) ){ ?>
		<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label"><?php echo sprintf( esc_attr__( 'You have selected to use %s as a live payment method and you have entered all necessary info.', 'wp-easycart' ), esc_attr( $status->ec_get_live_payment_method( ) ) ); ?></span></div>
		<?php }else if( $status->ec_live_payment_selected( ) ){ ?>
		<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label"><?php echo sprintf( esc_attr__( 'You have selected %s, but have missed some necessary info. Go to WP EasyCart -> Settings -> Payment to resolve this.', 'wp-easycart' ), esc_attr( $status->ec_get_live_payment_method( ) ) ); ?></span></div>
		<?php } 

		////////////////////////////
		// MISCELLANEOUS
		////////////////////////////
		?>
		<div class="ec_status_header"><div class="ec_status_header_text"><?php esc_attr_e( 'Miscellaneous', 'wp-easycart' ); ?></div></div>
		<?php
		////////////////////////////
		// Provide fix for custom post type links
		////////////////////////////
		?>

		<div class="ec_status_success">
			<div class="dashicons-before dashicons-yes"></div>
			<span class="ec_status_label"><?php esc_attr_e( 'If you are having problems with store links', 'wp-easycart' ); ?>,
				<a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=reset-store-permalinks&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-reset-store-permalinks' ) ); ?>"><?php esc_attr_e( 'reset permalinks', 'wp-easycart' ); ?></a>
				|
				<a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=reset-store-permalinks&ec_reset_phase2=true&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-reset-store-permalinks' ) ); ?>"><?php esc_attr_e( 'rebuild permalinks', 'wp-easycart' ); ?></a>
				|
				<a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=fix-category-permalinks&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-fix-category-permalinks' ) ); ?>"><?php esc_attr_e( 'Fix Category Permalink Issues', 'wp-easycart' ); ?></a>
				|
				<a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=fix-product-permalinks&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-fix-product-permalinks' ) ); ?>"><?php esc_attr_e( 'Fix Product Permalink Issues', 'wp-easycart' ); ?></a>
			</span>
		</div>

		<?php $response_log_size = $wpdb->get_var( 'SELECT COUNT(*) AS response_count FROM ec_response' ); ?>
		<?php $webhook_log_size = $wpdb->get_var( 'SELECT COUNT(*) AS webhook_count FROM ec_webhook' ); ?>
		
		<?php if ( $response_log_size > 100000 ) {
			echo '<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label" style="line-height:2em;">' . sprintf( esc_attr__( 'Your database storage for your gateway log has %d items and is bigger than it should be, please take a moment to remove the older log items.', 'wp-easycart' ), $response_log_size ) . ' <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=fix-gateway-log&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-fix-gateway-log' ) ) . '">' . esc_attr__( 'Click here to trim your gateway log to the last 100 items', 'wp-easycart' ) . '</a></span></div>';
		} else {
			echo '<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label">' . sprintf( esc_attr__( 'Your database storage for your gateway log has %d items, nothing to worry about.', 'wp-easycart' ), $response_log_size ) . '</span></div>';
		} ?>

		<?php if ( $webhook_log_size > 100000 ) {
			echo '<div class="ec_status_error"><div class="dashicons-before dashicons-no"></div><span class="ec_status_label" style="line-height:2em;">' . sprintf( esc_attr__( 'Your database storage for your webhook log has %d items and is bigger than it should be, please take a moment to remove the older log items.', 'wp-easycart' ), $webhook_log_size ) . ' <a href="admin.php?page=wp-easycart-status&subpage=store-status&ec_admin_form_action=fix-webhook-log&wp_easycart_nonce=' . esc_attr( wp_create_nonce( 'wp-easycart-fix-webhook-log' ) ) . '">' . esc_attr__( 'Click here to trim your webhook log to the last 1000 items', 'wp-easycart' ) . '</a></span></div>';
		} else {
			echo '<div class="ec_status_success"><div class="dashicons-before dashicons-yes"></div><span class="ec_status_label">' . sprintf( esc_attr__( 'Your database storage for your webhook log has %d items, nothing to worry about.', 'wp-easycart' ), $webhook_log_size ) . '</span></div>';
		} ?>

	</div>
</div>