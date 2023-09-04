<?php
// Check for iPhone/iPad/Admin
$ipad = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPad' );
$iphone = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPhone' );

$is_admin = ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && ! get_option( 'ec_option_hide_live_editor' ) );

if( isset( $_GET['preview'] ) ){
	$is_preview = true;
}else{
	$is_preview = false;
}

if( isset( $_GET['previewholder'] ) )
	$is_preview_holder = true;
else
	$is_preview_holder = false;

// END CHECK // 

/* PREVIEW CONTENT */
if( $is_preview_holder && $is_admin ){ ?>

<div class="ec_admin_preview_container" id="ec_admin_preview_container">
	<div class="ec_admin_preview_content">
		<div class="ec_admin_preview_button_container">
			<div class="ec_admin_preview_ipad_landscape"><input type="button" onclick="ec_admin_ipad_landscape_preview( );" value="iPad Landscape"></div>
			<div class="ec_admin_preview_ipad_portrait"><input type="button" onclick="ec_admin_ipad_portrait_preview( );" value="iPad Portrait"></div>
			<div class="ec_admin_preview_iphone_landscape"><input type="button" onclick="ec_admin_iphone_landscape_preview( );" value="iPhone Landscape"></div>
			<div class="ec_admin_preview_iphone_portrait"><input type="button" onclick="ec_admin_iphone_portrait_preview( );" value="iPhone Portrait"></div>
		</div>
		<div id="ec_admin_preview_content" class="ec_admin_preview_wrapper ipad landscape">
			<iframe src="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>preview=true" width="100%" height="100%" id="ec_admin_preview_iframe"></iframe>
		</div>
	</div>
</div>

<?php }else if( $is_admin && !$is_preview && !isset( $GLOBALS['ec_live_editor_loaded'] ) ){ 

$GLOBALS['ec_live_editor_loaded'] = true;

?>
<div class="ec_admin_successfully_update_container" id="ec_admin_page_updated">
	<div class="ec_admin_successfully_updated">
		<div>Your Page Settings Have Been Updated Successfully. The Page Will Now Reload.</div>
	</div>
</div>

<div class="ec_admin_loader_container" id="ec_admin_page_updated_loader">
	<div class="ec_admin_loader">
		<div>Updating Your Page Options...</div>
	</div>
</div>

<div class="ec_admin_loader_bg" id="ec_admin_loader_bg"></div>

<div id="ec_page_editor" class="ec_slideout_editor ec_display_editor_false ec_cart_editor">
	<div id="ec_page_editor_openclose_button" class="ec_slideout_openclose" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-page-options' ) ); ?>">
		<div class="dashicons dashicons-admin-generic"></div>
	</div>

	<div class="ec_admin_preview_button"><a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>previewholder=true" target="_blank">Show Device Preview</a></div>

	<div class="ec_admin_page_size">Cart Options</div>
	<div><strong>Desktop Columns</strong></div>
	<div><select id="ec_option_cart_columns_desktop">
			<option value="0"<?php if( get_option( 'ec_option_cart_columns_desktop' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_cart_columns_desktop' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_cart_columns_desktop' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Tablet Landscape Columns</strong></div>
	<div><select id="ec_option_cart_columns_laptop">
			<option value="0"<?php if( get_option( 'ec_option_cart_columns_laptop' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_cart_columns_laptop' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_cart_columns_laptop' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Tablet Portfolio Columns</strong></div>
	<div><select id="ec_option_cart_columns_tablet_wide">
			<option value="0"<?php if( get_option( 'ec_option_cart_columns_tablet_wide' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_cart_columns_tablet_wide' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_cart_columns_tablet_wide' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Smartphone Landscape Columns</strong></div>
	<div><select id="ec_option_cart_columns_tablet">
			<option value="0"<?php if( get_option( 'ec_option_cart_columns_tablet' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_cart_columns_tablet' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_cart_columns_tablet' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Smartphone Portfolio Columns</strong></div>
	<div><select id="ec_option_cart_columns_smartphone">
			<option value="0"<?php if( get_option( 'ec_option_cart_columns_smartphone' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_cart_columns_smartphone' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_cart_columns_smartphone' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Dark/Light Text</strong></div>
	<div><select id="ec_option_use_dark_bg">
			<option value="0"<?php if( get_option( 'ec_option_use_dark_bg' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_use_dark_bg' ) == "1" ){?> selected="selected"<?php }?>>White Text</option>
			<option value="0"<?php if( get_option( 'ec_option_use_dark_bg' ) == "0" ){?> selected="selected"<?php }?>>Dark Text</option>
	</select></div>

	<div><input type="button" value="APPLY AND SAVE" onclick="ec_admin_save_cart_options( ); return false;" /></div>

	<div class="ec_admin_view_more_button">
		<a href="<?php echo esc_attr( get_admin_url( ) ); ?>admin.php?page=wp-easycart-settings&subpage=design" target="_blank" title="More Options">View More Display Options</a>
	</div>

</div>
<script>wp_easycart_init_live_editor( ); 
function ec_admin_save_cart_options( ){
	jQuery( "#ec_admin_page_updated_loader" ).show( );
	jQuery( "#ec_admin_loader_bg" ).show( );
	var data = {
		action: 'ec_ajax_save_cart_options',
		ec_option_cart_columns_desktop: jQuery( '#ec_option_cart_columns_desktop' ).val( ),
		ec_option_cart_columns_laptop: jQuery( '#ec_option_cart_columns_laptop' ).val( ),
		ec_option_cart_columns_tablet_wide: jQuery( '#ec_option_cart_columns_tablet_wide' ).val( ),
		ec_option_cart_columns_tablet: jQuery( '#ec_option_cart_columns_tablet' ).val( ),
		ec_option_cart_columns_smartphone: jQuery( '#ec_option_cart_columns_smartphone' ).val( ),
		ec_option_use_dark_bg: jQuery( '#ec_option_use_dark_bg' ).val( ),
		nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-cart-options' ) ); ?>'
	}
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( "#ec_admin_page_updated_loader" ).hide( );
		jQuery( "#ec_admin_page_updated" ).show( );
		jQuery( "#ec_admin_loader_bg" ).fadeOut( 'slow' );
		location.reload( );
	} } );
	jQuery( '#ec_page_editor' ).animate( { left:'-290px' }, {queue:false, duration:220} ).removeClass( 'ec_display_editor_true' ).addClass( 'ec_display_editor_false' );
}</script>

<?php }// Close editor content ?>

<?php $this->display_cart_success( $success_code ); ?>

<?php $this->display_cart_error( $error_code ); ?>
<?php if ( $page_num != 1 ) { ?>
<section class="ec_cart_page">

	<div class="ec_cart_error" id="ec_stripe_error" style="display:none;">
		<div>
			<?php echo wp_easycart_language( )->get_text( "ec_errors", "payment_failed" ); ?>
		</div>
	</div>

	<div class="ec_cart_breadcrumbs">
		<div class="ec_cart_breadcrumb<?php if( $page_num < 1 ){ ?> ec_inactive<?php }?>"><a href="<?php echo esc_url( $this->cart_page ); ?>"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_title' ); ?></a></div>
		<div class="ec_cart_breadcrumb_divider"></div>
		<div class="ec_cart_breadcrumb<?php if( $page_num < 2 ){ ?> ec_inactive<?php }?>"><?php if( $page_num > 1 ){ ?><a href="<?php echo esc_url( $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info' ); ?>"><?php }?><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_checkout_details_title' ); ?><?php if( $page_num > 1 ){ ?></a><?php }?></div>
		<div class="ec_cart_breadcrumb_divider"></div>
		<div class="ec_cart_breadcrumb<?php if( $page_num < 3 ){ ?> ec_inactive<?php }?>"><?php if( $page_num > 2 ){ ?><a href="<?php echo esc_url( $this->cart_page . $this->permalink_divider . 'ec_page=checkout_payment' ); ?>"><?php }?><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_submit_payment_title' ); ?></div>
	</div>
<?php } ?>
