<?php 
// DISPLAY OPTIONS //
// DISPLAY TYPE SETUP
if( isset( $this->page_options->product_type ) )
	$product_type = $this->page_options->product_type;
else
	$product_type = get_option( 'ec_option_default_product_type' );
	
// DISPLAY QUICK VIEW SETUP
if( isset( $this->page_options->use_quickview ) )
	$quick_view = $this->page_options->use_quickview;
else
	$quick_view = get_option( 'ec_option_default_quick_view' );

// DISPLAY WIDTH SETUP
if( isset( $this->page_options->product_type ) )
	$product_type = $this->page_options->product_type;
else
	$product_type = get_option( 'ec_option_default_product_type' );
	
// DISPLAY QUICK VIEW SETUP
if( isset( $this->page_options->use_quickview ) )
	$quick_view = $this->page_options->use_quickview;
else
	$quick_view = get_option( 'ec_option_default_quick_view' );

// DISPLAY WIDTH SETUP
if( isset( $this->page_options->dynamic_image_sizing ) )  
	$dynamic_sizing = $this->page_options->dynamic_image_sizing;
else
	$dynamic_sizing = get_option( 'ec_option_default_dynamic_sizing' );
		
if( isset( $this->page_options->columns_smartphone ) )  
	$display_width_smartphone = (100/$this->page_options->columns_smartphone) . "%";
else if( get_option( 'ec_option_default_smartphone_columns' ) )
	$display_width_smartphone = (100/get_option( 'ec_option_default_smartphone_columns' ) ) . "%";
else
	$display_width_smartphone = (100/1) . "%";
	
if( isset( $this->page_options->columns_tablet ) )  
	$display_width_tablet = (100/$this->page_options->columns_tablet) . "%";
else if( get_option( 'ec_option_default_tablet_columns' ) )
	$display_width_tablet = (100/get_option( 'ec_option_default_tablet_columns' ) ) . "%";
else
	$display_width_tablet = (100/2) . "%";
	
if( isset( $this->page_options->columns_tablet_wide ) )  
	$display_width_tablet_wide = (100/$this->page_options->columns_tablet_wide) . "%";
else if( get_option( 'ec_option_default_tablet_wide_columns' ) )
	$display_width_tablet_wide = (100/get_option( 'ec_option_default_tablet_wide_columns' ) ) . "%";
else
	$display_width_tablet_wide = (100/2) . "%";
	
if( isset( $this->page_options->columns_laptop ) )  
	$display_width_laptop = (100/$this->page_options->columns_laptop) . "%";
else if( get_option( 'ec_option_default_laptop_columns' ) )
	$display_width_laptop = (100/get_option( 'ec_option_default_laptop_columns' ) ) . "%";
else
	$display_width_laptop = (100/3) . "%";
	
if( isset( $this->page_options->columns_desktop ) )  
	$display_width_desktop = (100/$this->page_options->columns_desktop ) . "%";
else if( get_option( 'ec_option_default_desktop_columns' ) )
	$display_width_desktop = (100/get_option( 'ec_option_default_desktop_columns' ) ) . "%";
else
	$display_width_desktop = (100/3) . "%";
	
// COLUMNS SETUP
if( isset( $this->page_options->columns_smartphone ) )  
	$columns_smartphone = $this->page_options->columns_smartphone;
else if( get_option( 'ec_option_default_smartphone_columns' ) )
	$columns_smartphone = get_option( 'ec_option_default_smartphone_columns' );
else
	$columns_smartphone = 1;
	
if( isset( $this->page_options->columns_tablet ) )  
	$columns_tablet = $this->page_options->columns_tablet;
else if( get_option( 'ec_option_default_tablet_columns' ) )
	$columns_tablet = get_option( 'ec_option_default_tablet_columns' );
else
	$columns_tablet = 2;
	
if( isset( $this->page_options->columns_tablet_wide ) )  
	$columns_tablet_wide = $this->page_options->columns_tablet_wide;
else if( get_option( 'ec_option_default_tablet_wide_columns' ) )
	$columns_tablet_wide = get_option( 'ec_option_default_tablet_wide_columns' );
else
	$columns_tablet_wide = 2;
	
if( isset( $this->page_options->columns_laptop ) )  
	$columns_laptop = $this->page_options->columns_laptop;
else if( get_option( 'ec_option_default_laptop_columns' ) )
	$columns_laptop = get_option( 'ec_option_default_laptop_columns' );
else
	$columns_laptop = 3;
	
if( isset( $this->page_options->columns_desktop ) )  
	$columns_desktop = $this->page_options->columns_desktop;
else if( get_option( 'ec_option_default_desktop_columns' ) )
	$columns_desktop = get_option( 'ec_option_default_desktop_columns' );
else
	$columns_desktop = 3;

// Image Height Setup
if( isset( $this->page_options->image_height_smartphone ) )
	$image_height_smartphone = $this->page_options->image_height_smartphone;
else if( get_option( 'ec_option_default_smartphone_image_height' ) )
	$image_height_smartphone = get_option( 'ec_option_default_smartphone_image_height' );
else
	$image_height_smartphone = '225px';
	
if( isset( $this->page_options->image_height_tablet ) )
	$image_height_tablet = $this->page_options->image_height_tablet;
else if( get_option( 'ec_option_default_tablet_image_height' ) )
	$image_height_tablet = get_option( 'ec_option_default_tablet_image_height' );
else
	$image_height_tablet = '250px';
	
if( isset( $this->page_options->image_height_tablet_wide ) )
	$image_height_tablet_wide = $this->page_options->image_height_tablet_wide;
else if( get_option( 'ec_option_default_tablet_wide_image_height' ) )
	$image_height_tablet_wide = get_option( 'ec_option_default_tablet_wide_image_height' );
else
	$image_height_tablet_wide = '275px';
	
if( isset( $this->page_options->image_height_laptop ) )
	$image_height_laptop = $this->page_options->image_height_laptop;
else if( get_option( 'ec_option_default_laptop_image_height' ) )
	$image_height_laptop = get_option( 'ec_option_default_laptop_image_height' );
else
	$image_height_laptop = '205px';
	
if( isset( $this->page_options->image_height_desktop ) )
	$image_height_desktop = $this->page_options->image_height_desktop;
else if( get_option( 'ec_option_default_desktop_image_height' ) )
	$image_height_desktop = get_option( 'ec_option_default_desktop_image_height' );
else
	$image_height_desktop = '205px';
	
// COLOR SETUP
if( get_option( 'ec_option_details_main_color' ) != '' )
	$color1 = get_option( 'ec_option_details_main_color' );
else
	$color1 = '#222222';
	
if( get_option( 'ec_option_details_second_color' ) != '' )
	$color2 = get_option( 'ec_option_details_second_color' );
else
	$color2 = '#666666';

// DISPLAY OPTIONS //

// Check for iPhone/iPad/Admin
$ipad = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPad' );

$is_admin = ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) );

if( isset( $_GET['preview'] ) ){
	$is_preview = true;
}else{
	$is_preview = false;
}

if( isset( $_GET['previewholder'] ) )
	$is_preview_holder = true;
else
	$is_preview_holder = false;

// Show admin if logged in and not using preview
if( $is_admin && !$is_preview && !get_option( 'ec_option_hide_live_editor' ) )
	$admin_access = true;
else
	$admin_access = false;
// END CHECK // 
?>

<?php 

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
			<iframe src="<?php the_permalink( ); ?>?preview=true" width="100%" height="100%" id="ec_admin_preview_iframe"></iframe>
		</div>
	</div>
</div>

<?php }else if( $admin_access && !$is_preview && !isset( $GLOBALS['ec_live_editor_loaded'] ) ){ 

$GLOBALS['ec_live_editor_loaded'] = "loaded";

?>
<div class="ec_admin_successfully_update_container" id="ec_admin_page_updated">
	<div class="ec_admin_successfully_updated">
    	<div>Your Page Settings Have Been Updated Successfully</div>
    </div>
</div>
        
<div class="ec_admin_loader_container" id="ec_admin_page_updated_loader">
	<div class="ec_admin_loader">
    	<div>Updating Your Page Options...</div>
    </div>
</div>

<div class="ec_admin_successfully_update_container" id="ec_admin_product_updated">
	<div class="ec_admin_successfully_updated"><div>Your Product Settings Have Been Updated Successfully</div></div>
</div>
        
<div class="ec_admin_loader_container" id="ec_admin_product_updated_loader">
	<div class="ec_admin_loader"><div>Updating Your Product Options...</div></div>
</div>

<div class="ec_admin_loader_bg" id="ec_admin_loader_bg"></div>

<div id="ec_page_editor" class="ec_slideout_editor ec_display_editor_false">
	<div id="ec_page_editor_openclose_button" class="ec_slideout_openclose" data-post-id="<?php global $post; echo esc_attr( $post->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-page-options' ) ); ?>">
    	<div class="dashicons dashicons-admin-generic"></div>
    </div>
    
    <form method="POST">
    <div class="ec_admin_preview_button"><a href="<?php the_permalink( ); ?>?previewholder=true" target="_blank">Show Device Preview</a></div>
    
    <div class="ec_admin_page_size">Page Product Options</div>
    <div><strong>Product Type:</strong></div>
    <div><select name="ec_page_options_product_type" id="ec_page_options_product_type">
            <option value="1"<?php if( $product_type == '1' ){ echo " selected='selected'"; }?>>Grid Type 1</option>
            <option value="2"<?php if( $product_type == '2' ){ echo " selected='selected'"; }?>>Grid Type 2</option>
            <option value="3"<?php if( $product_type == '3' ){ echo " selected='selected'"; }?>>Grid Type 3</option>
            <option value="4"<?php if( $product_type == '4' ){ echo " selected='selected'"; }?>>Grid Type 4</option>
            <option value="5"<?php if( $product_type == '5' ){ echo " selected='selected'"; }?>>Grid Type 5</option>
            <option value="6"<?php if( $product_type == '6' ){ echo " selected='selected'"; }?>>List Type 6</option>
    </select></div>
    
    <div><strong>Quick View:</strong></div>
    <div><select name="ec_page_options_quick_view" id="ec_page_options_quick_view">
            <option value="1"<?php if( $quick_view == '1' ){ echo " selected='selected'"; }?>>On</option>
            <option value="0"<?php if( $quick_view == '0' ){ echo " selected='selected'"; }?>>Off</option>
    </select></div>
    
    <div class="ec_admin_page_size">Colorize EasyCart</div>
    
    <div style="float:left; width:100%; margin-bottom:5px;"><span style="float:left; width:50%;"><strong>Main Color:</strong></span><span style="float:left; width:50%;"><strong>Secondary Color:</strong></span></div>
    <div><span style="float:left; width:50%;"><input name="ec_option_details_main_color" id="ec_option_details_main_color" type="color" value="<?php echo esc_attr( $color1 ); ?>" /></span><span style="float:left; width:50%;"><input name="ec_option_details_second_color" id="ec_option_details_second_color" type="color" value="<?php echo esc_attr( $color2 ); ?>" /></span></div>
    
    <div style="float:left; width:100%; margin-top:10px; height:40px; color:#900; font-size:12px; font-family:Arial,sans-serif;">Colors will be applied after saving and refreshing the page.</div>
    
    <div>    	
        <div>Dynamic Image Height:</div>
        <div>
        	<select name="ec_page_option_dynamic_image_sizing" id="ec_page_option_dynamic_image_sizing">
                <option value="0"<?php if( !$dynamic_sizing ){ echo " selected='selected'"; }?>>No</option>
                <option value="1"<?php if( $dynamic_sizing ){ echo " selected='selected'"; }?>>Yes</option>
            </select>
        </div>
    </div>
    
    <div style="clear:both; position:relative;">    	
        <div class="ec_responsive_left">&#65513;</div>
        <div class="ec_responsive_right">&#65515;</div>
    </div>
    
    <div id="ec_responsive_smartphone">
        <div class="ec_admin_page_size">iPhone Size - Portrait</div>
        <div><strong>Columns:</strong></div>
        <div><select name="ec_page_options_columns_smartphone" id="ec_page_options_columns_smartphone">
                <option value="1"<?php if( $columns_smartphone == '1' ){ echo " selected='selected'"; }?>>1 Column</option>
                <option value="2"<?php if( $columns_smartphone == '2' ){ echo " selected='selected'"; }?>>2 Columns</option>
                <option value="3"<?php if( $columns_smartphone == '3' ){ echo " selected='selected'"; }?>>3 Columns</option>
                <option value="4"<?php if( $columns_smartphone == '4' ){ echo " selected='selected'"; }?>>4 Columns</option>
                <option value="5"<?php if( $columns_smartphone == '5' ){ echo " selected='selected'"; }?>>5 Columns</option>
        </select></div>
        
        <div class="ec_non_dynamic_sizing"<?php if( $dynamic_sizing ){ ?> style="display:none;"<?php }?>>
    
            <div><strong>Image Height:</strong></div>
                <div><input name="ec_page_options_image_height_smartphone" id="ec_page_options_image_height_smartphone" type="number" value="<?php echo esc_attr( str_replace( "px", "", $image_height_smartphone ) ); ?>" style="width:110px; float:left;" /><span style="line-height:30px; margin-left:10px; font-weight:bold; font-size:12px;">px</span></div>
            </div>
            
		</div>
    
    <div id="ec_responsive_tablet">
        <div class="ec_admin_page_size">iPhone Size - Landscape</div>
        <div><strong>Columns:</strong></div>
        <div><select name="ec_page_options_columns_tablet" id="ec_page_options_columns_tablet">
                <option value="1"<?php if( $columns_tablet == '1' ){ echo " selected='selected'"; }?>>1 Column</option>
                <option value="2"<?php if( $columns_tablet == '2' ){ echo " selected='selected'"; }?>>2 Columns</option>
                <option value="3"<?php if( $columns_tablet == '3' ){ echo " selected='selected'"; }?>>3 Columns</option>
                <option value="4"<?php if( $columns_tablet == '4' ){ echo " selected='selected'"; }?>>4 Columns</option>
                <option value="5"<?php if( $columns_tablet == '5' ){ echo " selected='selected'"; }?>>5 Columns</option>
        </select></div>
        
        <div class="ec_non_dynamic_sizing"<?php if( $dynamic_sizing ){ ?> style="display:none;"<?php }?>>
    
            <div><strong>Image Height:</strong></div>
        	<div><input name="ec_page_options_image_height_tablet" id="ec_page_options_image_height_tablet" type="number" value="<?php echo esc_attr( str_replace( "px", "", $image_height_tablet ) ); ?>" style="width:110px; float:left;" /><span style="line-height:30px; margin-left:10px; font-weight:bold; font-size:12px;">px</span></div>
            
        </div>
            
    </div>
    
    <div id="ec_responsive_tablet_wide">
        <div class="ec_admin_page_size">iPad Size - Portrait</div>
        <div><strong>Columns:</strong></div>
        <div><select name="ec_page_options_columns_tablet_wide" id="ec_page_options_columns_tablet_wide">
                <option value="1"<?php if( $columns_tablet_wide == '1' ){ echo " selected='selected'"; }?>>1 Column</option>
                <option value="2"<?php if( $columns_tablet_wide == '2' ){ echo " selected='selected'"; }?>>2 Columns</option>
                <option value="3"<?php if( $columns_tablet_wide == '3' ){ echo " selected='selected'"; }?>>3 Columns</option>
                <option value="4"<?php if( $columns_tablet_wide == '4' ){ echo " selected='selected'"; }?>>4 Columns</option>
                <option value="5"<?php if( $columns_tablet_wide == '5' ){ echo " selected='selected'"; }?>>5 Columns</option>
        </select></div>
        
        <div class="ec_non_dynamic_sizing"<?php if( $dynamic_sizing ){ ?> style="display:none;"<?php }?>>
    
            <div><strong>Image Height:</strong></div>
        	<div><input name="ec_page_options_image_height_tablet_wide" id="ec_page_options_image_height_tablet_wide" type="number" value="<?php echo esc_attr( str_replace( "px", "", $image_height_tablet_wide ) ); ?>" style="width:110px; float:left;" /><span style="line-height:30px; margin-left:10px; font-weight:bold; font-size:12px;">px</span></div>
            
        </div>
        
    </div>
    
    <div id="ec_responsive_laptop">
        <div class="ec_admin_page_size">iPad Size - Landscape</div>
        <div><strong>Columns:</strong></div>
        <div><select name="ec_page_options_columns_laptop" id="ec_page_options_columns_laptop">
                <option value="1"<?php if( $columns_laptop == '1' ){ echo " selected='selected'"; }?>>1 Column</option>
                <option value="2"<?php if( $columns_laptop == '2' ){ echo " selected='selected'"; }?>>2 Columns</option>
                <option value="3"<?php if( $columns_laptop == '3' ){ echo " selected='selected'"; }?>>3 Columns</option>
                <option value="4"<?php if( $columns_laptop == '4' ){ echo " selected='selected'"; }?>>4 Columns</option>
                <option value="5"<?php if( $columns_laptop == '5' ){ echo " selected='selected'"; }?>>5 Columns</option>
        </select></div>
        
        <div class="ec_non_dynamic_sizing"<?php if( $dynamic_sizing ){ ?> style="display:none;"<?php }?>>
    
            <div><strong>Image Height:</strong></div>
        	<div><input name="ec_page_options_image_height_laptop" id="ec_page_options_image_height_laptop" type="number" value="<?php echo esc_attr( str_replace( "px", "", $image_height_laptop ) ); ?>" style="width:110px; float:left;" /><span style="line-height:30px; margin-left:10px; font-weight:bold; font-size:12px;">px</span></div>
            
        </div>
        
    </div>
    
    <div id="ec_responsive_desktop">
        <div class="ec_admin_page_size">Responsive Desktop</div>
        <div><strong>Columns:</strong></div>
        <div><select name="ec_page_options_columns_desktop" id="ec_page_options_columns_desktop">
                <option value="1"<?php if( $columns_desktop == '1' ){ echo " selected='selected'"; }?>>1 Column</option>
                <option value="2"<?php if( $columns_desktop == '2' ){ echo " selected='selected'"; }?>>2 Columns</option>
                <option value="3"<?php if( $columns_desktop == '3' ){ echo " selected='selected'"; }?>>3 Columns</option>
                <option value="4"<?php if( $columns_desktop == '4' ){ echo " selected='selected'"; }?>>4 Columns</option>
                <option value="5"<?php if( $columns_desktop == '5' ){ echo " selected='selected'"; }?>>5 Columns</option>
        </select></div>
        
        <div class="ec_non_dynamic_sizing"<?php if( $dynamic_sizing ){ ?> style="display:none;"<?php }?>>
    
            <div><strong>Image Height:</strong></div>
        	<div><input name="ec_page_options_image_height_desktop" id="ec_page_options_image_height_desktop" type="number" value="<?php echo esc_attr( str_replace( "px", "", $image_height_desktop ) ); ?>" style="width:110px; float:left;" /><span style="line-height:30px; margin-left:10px; font-weight:bold; font-size:12px;">px</span></div>
            
        </div>
        
    </div>
    
    <div style="clear:both;"></div>
    
	<div><input type="submit" value="SAVE" onclick="ec_admin_save_page_options( '<?php global $post; echo esc_attr( $post->ID ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-page-options' ) ); ?>' ); return false;" /></div>
    <div><input type="submit" value="SET AS DEFAULT" onclick="ec_admin_set_default_page_options( '<?php global $post; echo esc_attr( $post->ID ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-page-default-options' ) ); ?>' ); return false;" /></div>
    
    <div class="ec_admin_view_more_button">
    	<a href="<?php echo esc_attr( get_admin_url( ) ); ?>admin.php?page=ec_adminv2&ec_page=store-setup&ec_panel=basic-settings#product" target="_blank" title="More Options">View More Display Options</a>
    </div>
    
    <div class="ec_admin_help_link"><a href="https://www.youtube.com/watch?v=Pc3bCSgR-xM" target="_blank">Need Help? Click to Watch our Help Video</a></div>
    
    </form>
    
    <div class="ec_admin_page_size">Mass Change Product Options</div>
    
    <div><strong>Image Hover Effect</strong></div>
    <div><select id="ec_product_image_hover_type">
    		<option value="0" selected="selected">Select One</option>
            <option value="1">Image Flip</option>
            <option value="2">Image Crossfade</option>
            <option value="3">Lighten</option>
            <option value="5">Image Grow</option>
            <option value="6">Image Shrink</option>
            <option value="7">Grey-Color</option>
            <option value="8">Brighten</option>
            <option value="9">Image Slide</option>
            <option value="10">FlipBook</option>
            <option value="4">No Effect</option>
    </select></div>
    
    <div><strong>Image Effect</strong></div>
    <div><select id="ec_product_image_effect_type">
    		<option value="0" selected="selected">Select One</option>
            <option value="none">None</option>
            <option value="border">Border</option>
            <option value="shadow">Shadow</option>
    </select></div>
    
    <div style="color:red; margin:10px 0px; display:none;" id="ec_admin_mass_change_error">Please select each option to apply.</div>
    
    <div><input type="submit" value="APPLY AND SAVE" onclick="ec_admin_apply_product_options( '<?php  global $post; echo esc_attr( $post->ID ); ?>' ); return false;" /></div>
    
</div>

<div class="ec_products_sortable_holder">
	<div class="ec_products_sortable_padding">
    	<h3>Manually Arrange Products</h3>
        <h2>click the up and down arrows in the list to manually set the order of the products on this page</h2>
        <ul id="ec_products_sortable">
        <?php 
		$product_list = $this->get_products_no_limit( );
		for( $i=0; $i<count( $product_list ); $i++ ){ ?>
        <li class="ec_product_sort_item" data-model-number="<?php echo esc_attr( $product_list[$i]['model_number'] ); ?>"><div class="dashicons dashicons-arrow-up-alt2"></div><div class="dashicons dashicons-arrow-down-alt2"></div><span><?php if( strlen( $product_list[$i]['title'] ) > 40 ){ echo esc_attr( substr( $product_list[$i]['title'], 0, 40 ) . "..." ); }else{ echo esc_attr( $product_list[$i]['title'] ); } ?></span></li>
        <?php }?>
        </ul>
        <div class="ec_products_sortable_button_container">
        	<div class="ec_products_sortable_save_button" data-post-id="<?php echo esc_attr( $post->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-product-order' ) ); ?>">Save Changes</div><div class="ec_products_sortable_cancel_button">Cancel</div>
        </div>
    </div>
</div>
<div class="ec_products_sortable_bg"></div>
<div id="ec_current_media_size"></div>

<?php /* SCRIPT FUNCTIONS REQUIRED TO BE RUN ON THE PRODUCT PAGE */ ?>
<script>
function ec_admin_apply_product_options( post_id ){
	var product_list = new Array( <?php for( $i=0; $i<count( $this->product_list->products ); $i++ ){ if( $i > 0 ){ echo ","; } echo "'" . esc_attr( $this->product_list->products[$i]->model_number ) . "'"; } ?> );
	if( jQuery( document.getElementById( 'ec_product_image_hover_type' ) ).val( ) != '0' && jQuery( document.getElementById( 'ec_product_image_effect_type' ) ).val( ) != '0' ){
		jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).show( );
		jQuery( document.getElementById( "ec_admin_loader_bg" ) ).show( );
		jQuery( document.getElementById( 'ec_admin_mass_change_error' ) ).hide( );
		for( var i=0; i<product_list.length; i++ ){
			jQuery( document.getElementById( 'ec_product_image_hover_type_' + product_list[i] ) ).val( jQuery( document.getElementById( 'ec_product_image_hover_type' ) ).val( ) );
			ec_admin_update_image_hover_effect( product_list[i] );
			jQuery( document.getElementById( 'ec_product_image_effect_type_' + product_list[i] ) ).val( jQuery( document.getElementById( 'ec_product_image_effect_type' ) ).val( ) );
			ec_admin_update_image_effect_type( product_list[i] );
		}
		var data = {
			action: 'ec_ajax_mass_save_product_options',
			image_hover_type: jQuery( document.getElementById( 'ec_product_image_hover_type' ) ).val( ),
			image_effect_type: jQuery( document.getElementById( 'ec_product_image_effect_type' ) ).val( ),
			products: product_list,
			nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-mass-save-product-options' ) ); ?>'
		}
		jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).hide( );
			jQuery( document.getElementById( "ec_admin_page_updated" ) ).show( ).delay( 1500 ).fadeOut( 'slow' );
			jQuery( document.getElementById( "ec_admin_loader_bg" ) ).fadeOut( 'slow' );
		} } );
		jQuery( document.getElementById( 'ec_page_editor' ) ).animate( { left:'-290px' }, {queue:false, duration:220} ).removeClass( 'ec_display_editor_true' ).addClass( 'ec_display_editor_false' );
	}else{
		jQuery( document.getElementById( 'ec_admin_mass_change_error' ) ).show( );
	}
}
function ec_admin_reorder_products( ids ){
	// Column Widths
	var window_width = jQuery( document.getElementById( 'ec_current_media_size' ) ).css( "max-width" ).replace( "px", "" );
	var columns = jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( );
	if( window_width > 1140 ){
		columns = jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( );
	}else if( window_width > 990 ){
		columns = jQuery( document.getElementById( 'ec_page_options_columns_laptop' ) ).val( );
	}else if( window_width > 768 ){
		columns = jQuery( document.getElementById( 'ec_page_options_columns_tablet_wide' ) ).val( );
	}else if( window_width > 481 ){
		columns = jQuery( document.getElementById( 'ec_page_options_columns_tablet' ) ).val( );
	}else{
		columns = jQuery( document.getElementById( 'ec_page_options_columns_smartphone' ) ).val( );
	}
	var column_width = ( 100 / columns );
	for( var i=0; i<<?php echo count( $this->product_list->products ); ?>; i++ ){
		if( !document.getElementById( 'ec_product_li_' + ids[i] ) ){
			jQuery( document.getElementById( 'ec_store_product_list' ) ).append( '<li class="ec_product_li empty" style="width:' + column_width + '%" id="ec_product_li_' + ids[i] + '">This item was not initially available on the page. Please reload to view this item.</li>' );
		}
		jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ).removeClass( 'first' );
		jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ).removeClass( 'not_first' );
		jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ).removeClass( 'hidden' );
		
		jQuery( document.getElementById( 'ec_store_product_list' ) ).append( jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ) );
		if( i%columns == 0 ){
			jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ).addClass( 'first' );
		}else{
			jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ).addClass( 'not_first' );
		}
	}
	for( i; i<ids.length; i++ ){
		jQuery( document.getElementById( 'ec_product_li_' + ids[i] ) ).addClass( 'hidden' );
	}
}
</script>

<?php }?>

<?php // START MAIN CONTENT FOR PRODUCT PAGE // ?>

<section class="ec_product_page" id="ec_product_page">
	
    <ul id="ec_store_product_list"><?php $this->category_list( ); ?></ul>
    
</section>