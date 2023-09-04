<?php $GLOBALS['wpeasycart_prod_details_count'] = ( isset( $GLOBALS['wpeasycart_prod_details_count'] ) ) ? (int) $GLOBALS['wpeasycart_prod_details_count'] + 1 : 1; ?>
<?php $wpeasycart_addtocart_shortcode_rand = (int) $GLOBALS['wpeasycart_prod_details_count']; ?>

<strong></strong><?php
do_action( 'wp_easycart_product_details_before', $this->product );
if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){
	echo "<script>
			fbq('track', 'ViewContent', {
				content_name: '" . esc_attr( strip_tags( $this->product->title ) ) . "',
				content_ids: ['" . esc_attr( $this->product->product_id ) . "'],
				content_type: 'product',
				value: " . esc_attr( number_format( $this->product->price, 2, '.', '' ) ) . ",
				currency: '" . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . "',
			});
		</script>";
}
?>
<?php
// Check for iPhone/iPad/Admin
$ipad = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPad' );
$iphone = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPhone' );

$is_admin = ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && ! get_option( 'ec_option_hide_live_editor' ) );

if ( isset( $_GET['preview'] ) ) {
	$is_preview = true;
} else {
	$is_preview = false;
}

if ( isset( $_GET['previewholder'] ) ) {
	$is_preview_holder = true;
} else {
	$is_preview_holder = false;
}
if ( get_option( 'ec_option_details_main_color' ) != '' ) {
	$color1 = get_option( 'ec_option_details_main_color' );
} else {
	$color1 = '#222222';
}
if ( get_option( 'ec_option_details_second_color' ) != '' ) {
	$color2 = get_option( 'ec_option_details_second_color' );
} else {
	$color2 = '#666666';
}
// END CHECK // 

/* PREVIEW CONTENT */
if( $is_preview_holder && $is_admin && !$GLOBALS['ec_admin_details_loaded_previously'] ){ ?>

<div class="ec_admin_preview_container" id="ec_admin_preview_container">
	<div class="ec_admin_preview_content">
		<div class="ec_admin_preview_button_container">
			<div class="ec_admin_preview_ipad_landscape"><input type="button" onclick="ec_admin_ipad_landscape_preview( );" value="iPad Landscape"></div>
			<div class="ec_admin_preview_ipad_portrait"><input type="button" onclick="ec_admin_ipad_portrait_preview( );" value="iPad Portrait"></div>
			<div class="ec_admin_preview_iphone_landscape"><input type="button" onclick="ec_admin_iphone_landscape_preview( );" value="iPhone Landscape"></div>
			<div class="ec_admin_preview_iphone_portrait"><input type="button" onclick="ec_admin_iphone_portrait_preview( );" value="iPhone Portrait"></div>
		</div>
		<div id="ec_admin_preview_content" class="ec_admin_preview_wrapper ipad landscape">
			<iframe src="<?php the_permalink( ); ?>?model_number=<?php echo esc_attr( $this->product->model_number ); ?>&amp;preview=true" width="100%" height="100%" id="ec_admin_preview_iframe"></iframe>
		</div>
	</div>
</div><?php }else if( $is_admin && !$is_preview && ( !isset( $GLOBALS['ec_live_editor_loaded'] ) ) ){ 

$GLOBALS['ec_live_editor_loaded'] = "loaded";

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
<div id="ec_page_editor" class="ec_slideout_editor ec_display_editor_false ec_details_editor">
	<div id="ec_page_editor_openclose_button" class="ec_slideout_openclose" data-post-id="<?php global $post; echo esc_attr( $post->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-page-options' ) ); ?>">
		<div class="dashicons dashicons-admin-generic"></div>
	</div>
	<div class="ec_admin_preview_button"><a href="<?php the_permalink( ); ?>?model_number=<?php echo esc_attr( $this->product->model_number ); ?>&amp;previewholder=true" target="_blank">Show Device Preview</a></div>
	<div class="ec_admin_page_size">Colorize EasyCart</div>
    <div style="float:left; width:100%; margin-bottom:5px;"><span style="float:left; width:50%;"><strong>Main Color:</strong></span><span style="float:left; width:50%;"><strong>Secondary Color:</strong></span></div>
    <div><span style="float:left; width:50%;"><input name="ec_option_details_main_color" id="ec_option_details_main_color" type="color" value="<?php echo esc_attr( $color1 ); ?>" /></span><span style="float:left; width:50%;"><input name="ec_option_details_second_color" id="ec_option_details_second_color" type="color" value="<?php echo esc_attr( $color2 ); ?>" /></span></div>
    
    <div style="float:left; width:100%; margin-top:10px; height:40px; color:#900; font-size:12px; font-family:Arial,sans-serif;">Colors will be applied after saving and refreshing the page.</div>
	
	<div class="ec_admin_page_size" style="clear:both;">Product Details Options</div>
	<div><strong>Desktop Columns</strong></div>
	<div><select id="ec_option_details_columns_desktop">
			<option value="0"<?php if( get_option( 'ec_option_details_columns_desktop' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_details_columns_desktop' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_details_columns_desktop' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Tablet Landscape Columns</strong></div>
	<div><select id="ec_option_details_columns_laptop">
			<option value="0"<?php if( get_option( 'ec_option_details_columns_laptop' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_details_columns_laptop' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_details_columns_laptop' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Tablet Portrait Columns</strong></div>
	<div><select id="ec_option_details_columns_tablet_wide">
			<option value="0"<?php if( get_option( 'ec_option_details_columns_tablet_wide' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_details_columns_tablet_wide' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_details_columns_tablet_wide' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Smartphone Landscape Columns</strong></div>
	<div><select id="ec_option_details_columns_tablet">
			<option value="0"<?php if( get_option( 'ec_option_details_columns_tablet' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_details_columns_tablet' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_details_columns_tablet' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Smartphone Portrait Columns</strong></div>
	<div><select id="ec_option_details_columns_smartphone">
			<option value="0"<?php if( get_option( 'ec_option_details_columns_smartphone' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_details_columns_smartphone' ) == "1" ){?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if( get_option( 'ec_option_details_columns_smartphone' ) == "2" ){?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Dark/Light Text</strong></div>
	<div><select id="ec_option_use_dark_bg">
			<option value="0"<?php if( get_option( 'ec_option_use_dark_bg' ) == "" ){?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if( get_option( 'ec_option_use_dark_bg' ) == "1" ){?> selected="selected"<?php }?>>White Text</option>
			<option value="0"<?php if( get_option( 'ec_option_use_dark_bg' ) == "0" ){?> selected="selected"<?php }?>>Dark Text</option>
	</select></div>

	<div><input type="button" value="APPLY AND SAVE" onclick="ec_admin_save_product_details_options( ); return false;" /></div>

	<div class="ec_admin_view_more_button">
		<a href="<?php echo esc_attr( get_admin_url( ) ); ?>admin.php?page=wp-easycart-settings&subpage=design" target="_blank" title="More Options">View More Display Options</a>
	</div>

</div>
<div id="ec_current_media_size"></div>
<input type="hidden" id="product_details_save_options_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-product-details-options' ) ); ?>" />
<?php }// Close editor content ?>
<?php 
/* START PRODUCT DETAILS PAGE */ 
?>

<?php
/* If using Google Merchant Show Necessary META */
if( isset( $this->product->google_attributes ) && $this->product->google_attributes != NULL && $this->product->google_attributes != "" ){
	$google_attributes = json_decode( $this->product->google_attributes );
}else{
	$google_attributes = false;
}
?>
<script type="application/ld+json">
{
	"@context": "http://schema.org",
	"@type": "Product",
	"offers": {
		"@type": "Offer",
		"url": "<?php echo esc_js( $this->product->get_product_link( ) ); ?>",
		"availability": "<?php echo ( !$this->product->show_stock_quantity || $this->product->stock_quantity > 0 ) ? 'InStock' : 'OutOfStock'; ?>",
		"price": "<?php echo esc_js( number_format( $this->product->price, 2, '.', '' ) ); ?>",
		"priceValidUntil": "<?php echo esc_js( date( 'Y-m-d', strtotime( '+1 year' ) ) ); ?>",
		"priceCurrency": "<?php echo esc_js( $GLOBALS['currency']->get_currency_code( ) ); ?>"<?php if( $google_attributes && isset( $google_attributes->condition ) ){ ?>,
		"itemCondition": "<?php if( 'new' == strtolower( $google_attributes->condition ) || '' == $google_attributes->condition ) { echo 'NewCondition'; }else if( 'used' == strtolower( $google_attributes->condition ) ){ echo 'UsedCondition'; }else{ echo 'RefurbishedCondition'; } ?>"<?php }?>
	},
	"brand": "<?php echo esc_js( $this->product->manufacturer_name ); ?>",
	"sku": "<?php echo esc_js( $this->product->model_number ); ?>",
	"name": "<?php echo esc_js( strip_tags( $this->product->title ) ); ?>",
	"description": "<?php echo esc_js( ( isset( $this->product->short_description ) && strlen( $this->product->short_description ) > 0 ) ? $this->product->short_description : $this->product->description ); ?>"<?php if( $google_attributes && strlen( $google_attributes->gtin ) > 0 ){ ?>,
	"gtin": "<?php echo esc_js( $google_attributes->gtin ); ?>"<?php }else if( $google_attributes && strlen( $google_attributes->mpn ) > 0 ){ ?>,
	"mpn": "<?php echo esc_js( $google_attributes->mpn ); ?>"<?php }?>,
	"url": "<?php echo esc_js( $this->product->get_product_link( ) ); ?>",<?php if( $this->product->use_customer_reviews && count( $this->product->reviews ) > 0 ){ 
	$best_review = false;
	foreach( $this->product->reviews as $one_review ){
		if( !$best_review || $one_review->rating > $best_review->rating ){
			$best_review = $one_review;
		}
	}
	if( $best_review ){
		$best_review = new ec_review( $best_review );
	?>
	"review": {
		"@type": "Review",
		"reviewRating": {
			"@type": "Rating",
			"ratingValue": "<?php echo esc_js( $best_review->rating ); ?>"
		},
		"author":{
			"@type": "Person",
			"name": "<?php echo esc_js( $best_review->reviewer_name ); ?>"
		},
		"reviewBody": "<?php echo esc_js( $best_review->description ); ?>"
	},<?php }?>
	"aggregateRating": {
		"@type": "AggregateRating",
		"reviewCount": "<?php echo esc_js( count( $this->product->reviews ) ); ?>",
		"ratingValue": "<?php echo esc_js( $this->product->get_rating( ) ); ?>"
	},<?php }?>
	"image": {
		"@type": "ImageObject",
		"url": "<?php echo esc_js( $this->product->get_first_image_url( ) ); ?>",
		"image": "<?php echo esc_js( $this->product->get_first_image_url( ) ); ?>",
		"name": "<?php echo esc_js( strip_tags( stripslashes( $this->product->title ) ) ); ?>"
	}
}
</script>

<?php do_action( 'wpeasycart_product_description_top', $this->product->product_id ); ?>

<?php /* INQUIRY OPTIONS */ ?>
<?php if( $this->product->is_inquiry_mode && $this->product->inquiry_url == "" ){ ?>

<div class="ec_details_inquiry_popup ec_details_inquiry_popup_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
<div class="ec_details_inquiry_popup_content">
	<div class="ec_details_inquiry_popup_padding">
		<div class="ec_details_inquiry_popup_holder">
			<div class="ec_details_inquiry_popup_main">
				<div style="display:none;" class="ec_store_loader" id="ec_inquiry_loader">Loading...</div>
				<div class="ec_store_loader_bg" id="ec_inquiry_loader_bg"></div>
				<form action="<?php echo esc_attr( $this->cart_page ); ?>" method="POST" enctype="multipart/form-data" class="ec_add_to_cart_form">
				<div class="ec_details_options ec_details_options_inquiry_info" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
					<h3><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquiry_title' ); ?></h3>
					<strong><?php echo esc_attr( strip_tags( $this->product->title ) ); ?></strong>
					<div class="ec_details_option_row_error ec_inquiry_error" id="ec_details_inquiry_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'ec_errors', 'missing_inquiry_options' ); ?></div>
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquiry_name' ); ?></div>
						<div class="ec_details_option_data"><input type="text" name="ec_inquiry_name" id="ec_inquiry_name_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="" /></div>
					</div>
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquiry_email' ); ?></div>
						<div class="ec_details_option_data"><input type="text" name="ec_inquiry_email" id="ec_inquiry_email_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="" /></div>
					</div>
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquiry_message' ); ?></div>
						<div class="ec_details_option_data"><textarea name="ec_inquiry_message" id="ec_inquiry_message_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></textarea></div>
					</div>
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"></div>
						<div class="ec_details_option_data"><input type="checkbox" name="ec_inquiry_send_copy" id="ec_inquiry_send_copy_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" /> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquiry_send_copy' ); ?></div>
					</div>
				</div>

				<?php /* PRODUCT BASIC OPTIONS */ 
				$has_quantity_grid = false;
				?>
				<?php if( $this->product->has_options && ( ! $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ){ ?>
				<div class="ec_details_options ec_details_basic_options_inquiry" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<?php 
				$optionsets = array( $this->product->options->optionset1, $this->product->options->optionset2, $this->product->options->optionset3, $this->product->options->optionset4, $this->product->options->optionset5 );

				for( $i=0; $i<5; $i++ ){ ?>

					<?php
					/* START IMAGE SWATCHES AREA */
					if( count( $optionsets[$i]->optionset ) > 0 && $optionsets[$i]->option_type == 'basic-swatch' && $optionsets[$i]->optionset[0]->optionitem_icon != "" ){ ?>

					<div class="ec_details_option_row_error ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" id="ec_details_option_row_error_<?php echo esc_attr( $optionsets[$i]->option_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ); ?> <?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>
					<input type="hidden" name="ec_option<?php echo esc_attr( $i+1 ); ?>" id="ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="0" />
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>
						<ul class="ec_details_swatches">
						<?php
						for( $j=0; $j<count( $optionsets[$i]->optionset ); $j++ ){
							// Check the in stock status for this option item
							if( $this->product->allow_backorders ){
								$optionitem_in_stock = true;
							}else if( $this->product->use_optionitem_quantity_tracking && ( $i > 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] <= 0 ) ){
								$optionitem_in_stock = false;
							}else{
								$optionitem_in_stock = true;
							}
						?>
						<li class="ec_details_swatch <?php echo ( 0 == $i ) ? 'ec_optionitem_images' : ''; ?> ec_option<?php echo esc_attr( $i+1 ); ?> ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $optionitem_in_stock ){ ?> ec_active <?php }?><?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $optionsets[$i]->option_meta['url_var'] ) && $optionsets[$i]->option_meta['url_var'] != '' && isset( $_GET[$optionsets[$i]->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionsets[$i]->option_meta['url_var']] ) ) == strtolower( $optionsets[$i]->optionset[$j]->optionitem_name ) ) || ( isset( $_GET['o'.$optionsets[$i]->option_id] ) && sanitize_text_field( $_GET['o'.$optionsets[$i]->option_id] ) == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-id="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_id ); ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] ); ?>"<?php }?> data-optionitem-price="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price ); }else{ echo "0.00"; } ?>" data-optionitem-price-onetime="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ); }else{ echo "0.00"; } ?>" data-optionitem-price-override="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_override ); }else{ echo "-1.00"; } ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_multiplier ); ?>"><img src="<?php if( substr( $optionsets[$i]->optionset[$j]->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionsets[$i]->optionset[$j]->optionitem_icon, 0, 8 ) == 'https://' ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_icon ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/swatches/" . $optionsets[$i]->optionset[$j]->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" title="<?php 
							echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_name ); ?><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if( $optionsets[$i]->optionset[$j]->optionitem_enable_custom_price_label && ( $optionsets[$i]->optionset[$j]->optionitem_price != 0 || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price ) && $optionsets[$i]->optionset[$j]->optionitem_price != 0 ) || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != 0 ) ) ) {
								?> <?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_custom_price_label ); ?><?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override > -1 ){
								?> (<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_override ) ); ?>)<?php
							} ?>" /></li>
						<?php
						}
						?>
						</ul>
						<div class="ec_option_loading" id="ec_option_loading_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_loading_options' ); ?></div>
					</div>
					<?php

					// HTML Swatches
					}else if( count( $optionsets[$i]->optionset ) > 0 && $optionsets[$i]->option_type == 'basic-swatch' ){ ?>

					<div class="ec_details_option_row_error ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" id="ec_details_option_row_error_<?php echo esc_attr( $optionsets[$i]->option_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ); ?> <?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>
					<input type="hidden" name="ec_option<?php echo esc_attr( $i+1 ); ?>" id="ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="0" />
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>
						<ul class="ec_details_swatches ec_details_html_swatches">
						<?php
						for( $j=0; $j<count( $optionsets[$i]->optionset ); $j++ ){
							// Check the in stock status for this option item
							if( $this->product->allow_backorders ){
								$optionitem_in_stock = true;
							}else if( $this->product->use_optionitem_quantity_tracking && ( $i > 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] <= 0 ) ){
								$optionitem_in_stock = false;
							}else{
								$optionitem_in_stock = true;
							}
						?>
						<li class="ec_details_swatch wpeasycart-html-swatch <?php echo ( 0 == $i ) ? 'ec_optionitem_images' : ''; ?> ec_option<?php echo esc_attr( $i+1 ); ?> ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $optionitem_in_stock ){ ?> ec_active <?php }?><?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $optionsets[$i]->option_meta['url_var'] ) && $optionsets[$i]->option_meta['url_var'] != '' && isset( $_GET[$optionsets[$i]->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionsets[$i]->option_meta['url_var']] ) ) == strtolower( $optionsets[$i]->optionset[$j]->optionitem_name ) ) || ( isset( $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] ) && sanitize_text_field( $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] ) == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-id="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_id ); ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] ); ?>"<?php }?> data-optionitem-price="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price ); }else{ echo "0.00"; } ?>" data-optionitem-price-onetime="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ); }else{ echo "0.00"; } ?>" data-optionitem-price-override="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_override ); }else{ echo "-1.00"; } ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_multiplier ); ?>" title="<?php 
							echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_name ); ?><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if( $optionsets[$i]->optionset[$j]->optionitem_enable_custom_price_label && ( $optionsets[$i]->optionset[$j]->optionitem_price != 0 || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price ) && $optionsets[$i]->optionset[$j]->optionitem_price != 0 ) || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != 0 ) ) ) {
								?> <?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_custom_price_label ); ?><?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override > -1 ){
								?> (<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_override ) ); ?>)<?php
							} ?>"><?php echo wp_easycart_escape_html( $optionsets[$i]->optionset[$j]->optionitem_name ); ?></li>
						<?php
						}
						?>
						</ul>
						<div class="ec_option_loading" id="ec_option_loading_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_loading_options' ); ?></div>
					</div>
					<?php
					/* END SWATCHES AREA */

					/* START COMBO BOX AREA */
					}else if( count( $optionsets[$i]->optionset ) > 0 && $optionsets[$i]->optionset[0]->optionitem_name != "" ){ ?>
					<div class="ec_details_option_row_error ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" id="ec_details_option_row_error_<?php echo esc_attr( $optionsets[$i]->option_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ); ?> <?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>

					<div class="ec_details_option_row">
						<select name="ec_option<?php echo esc_attr( $i+1 ); ?>" id="ec_option<?php echo esc_attr( $i+1 ); ?>" class="ec_details_combo ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_option<?php echo esc_attr( $i+1 ); ?><?php if( $this->product->use_optionitem_quantity_tracking && $i > 0 ){ ?> ec_inactive<?php }?> <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i > 0 ){ ?> disabled="disabled"<?php }?>>
						<option value="0"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->stock_quantity ); ?>"<?php }?> data-optionitem-price="0.00" data-optionitem-price-onetime="0.00" data-optionitem-price-override="-1" data-optionitem-price-multiplier="-1.00"><?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></option>
						<?php
						for( $j=0; $j<count( $optionsets[$i]->optionset ); $j++ ){
							// Check the in stock status for this option item
							if( $this->product->allow_backorders ){
								$optionitem_in_stock = true;
							}else if( $this->product->use_optionitem_quantity_tracking && ( $i > 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] <= 0 ) ){
								$optionitem_in_stock = false;
							}else{
								$optionitem_in_stock = true;
							}
						?>
						<?php if( !$this->product->use_optionitem_quantity_tracking || $i != 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] > 0 || $optionitem_in_stock ){ ?> 
						<option value="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_id ); ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] ); ?>"<?php }?> data-optionitem-price="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price ); }else{ echo "0.00"; } ?>" data-optionitem-price-onetime="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price_onetime != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ); }else{ echo "0.00"; } ?>" data-optionitem-price-override="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_override ); }else{ echo "-1.00"; } ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_multiplier ); ?>"<?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $optionsets[$i]->option_meta['url_var'] ) && $optionsets[$i]->option_meta['url_var'] != '' && isset( $_GET[$optionsets[$i]->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionsets[$i]->option_meta['url_var']] ) ) == strtolower( $optionsets[$i]->optionset[$j]->optionitem_name ) ) || ( isset( $_GET['o'.$optionsets[$i]->option_id] ) && sanitize_text_field( $_GET['o'.$optionsets[$i]->option_id] ) == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> selected="selected"<?php }?>><?php echo wp_easycart_escape_html( $optionsets[$i]->optionset[$j]->optionitem_name ); ?> <?php 
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if( $optionsets[$i]->optionset[$j]->optionitem_enable_custom_price_label && ( $optionsets[$i]->optionset[$j]->optionitem_price != 0 || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price ) && $optionsets[$i]->optionset[$j]->optionitem_price != 0 ) || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != 0 ) ) ) {
								?> <?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_custom_price_label ); ?><?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override > -1 ){
								?> (<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_override ) ); ?>)<?php
							} ?></option>
						<?php }?>
						<?php
						}
						?>
						</select>
						<div class="ec_option_loading" id="ec_option_loading_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_loading_options' ); ?></div>
					</div>
					<?php
					}
					/* END COMBO BOX AREA*/
				}
				?>
				</div>
				<?php } ?>
				<?php /* END BASIC OPTIONS */ ?>

				<?php /* PRODUCT ADVANCED OPTIONS */ ?>
				<?php 

				$add_price_grid = 0;
				$add_order_price_grid = 0;
				$override_price_grid = -1;
				if( ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) && count( $this->product->advanced_optionsets ) > 0 ){ ?>
				<div class="ec_details_options ec_details_advanced_options_inquiry" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
					<?php 
					$first_optionitem_image_type = true;
					foreach( $this->product->advanced_optionsets as $optionset ){
						$optionitems = $this->product->get_advanced_optionitems( $optionset->option_id );
					?>
					<?php 
					if( $optionset->option_required ){ 
					?>
					<div class="ec_details_option_row_error" id="ec_details_adv_option_row_error_<?php echo esc_attr( $optionset->option_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo ( '' != $optionset->option_error_text ) ? wp_easycart_escape_html( $optionset->option_error_text ) : wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ) . ' ' . wp_easycart_escape_html( $optionset->option_label ); // Escaped from language class ?></div>
					<?php
					}
					?>
					<div class="ec_details_option_row ec_option_type_<?php echo esc_attr( $optionset->option_type ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-option-required="<?php echo esc_attr( $optionset->option_required ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
						<?php if( $optionset->option_type != "combo" ){ ?>
						<div class="ec_details_option_label"><?php echo wp_easycart_escape_html( $optionset->option_label ); ?></div>
						<?php }?>
						<div class="ec_details_option_data">
						<?php
						/* START ADVANCED CHECBOX TYPE */
						if( $optionset->option_type == "checkbox" ){
						?>

							<?php
							foreach( $optionitems as $optionitem ){
							?>

								<div class="ec_details_checkbox_row"><input type="checkbox" class="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $optionitem->optionitem_id ); ?>" value="<?php echo esc_html( wp_easycart_escape_html( $optionitem->optionitem_name ) ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-id="<?php echo esc_attr( $optionitem->optionitem_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>"<?php if( $optionitem->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ) == strtolower( $optionitem->optionitem_name ) ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitem->optionitem_name ) ){ ?> checked="checked"<?php }?> /> <?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?> <?php 
									if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
										// No pricing shown in this case.
									} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
										echo '<span class="ec_product_details_option_pricing">' . esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ) . '</span>';
									} else if ( $optionitem->optionitem_price > 0 ) {
										echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
									} else if ( $optionitem->optionitem_price < 0 ) {
										echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
									} else if ( $optionitem->optionitem_price_onetime > 0 ) {
										echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
									} else if ( $optionitem->optionitem_price_onetime < 0 ) {
										echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
									} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
										echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')</span>';
									} ?></div>

							<?php
							}
							?>

						<?php

						/* START ADVANCED COMBO TYPE */
						}else if( $optionset->option_type == "combo" ){
						?>
							<select name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" ddata-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>"<?php echo ( $first_optionitem_image_type ) ? 'class="ec_optionitem_images"' : ''; ?>>
							<option value="0" data-optionitem-price="0.000" data-optionitem-price-onetime="0.000" data-optionitem-price-override="-1.000" data-optionitem-price-multiplier="-1.000"><?php echo wp_easycart_escape_html( $optionset->option_label ); ?></option>
							<?php
							foreach( $optionitems as $optionitem ){
							?>

								<option value="<?php echo esc_attr( $optionitem->optionitem_id ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>"<?php if( $optionitem->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ) == strtolower( $optionitem->optionitem_name ) ) || ( isset( $_GET['o'.$optionset->option_id] ) && $_GET['o'.$optionset->option_id] == $optionitem->optionitem_name ) ){ ?> selected="selected"<?php }?>><?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?> <?php
								if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
									// No pricing shown in this case.
								} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
									echo '<span class="ec_product_details_option_pricing">' . esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ) . '</span>';
								} else if ( $optionitem->optionitem_price > 0 ) {
									echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
								} else if ( $optionitem->optionitem_price < 0 ) {
									echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
								} else if ( $optionitem->optionitem_price_onetime > 0 ) {
									echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
								} else if ( $optionitem->optionitem_price_onetime < 0 ) {
									echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
								} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
									echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')';
								} ?></option>

							<?php
							}
							?>
							</select>
						<?php
						$first_optionitem_image_type = false;

						/* START ADVANCED DATE TYPE*/
						}else if( $optionset->option_type == "date" ){
						?>

							<input type="text" value="<?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); } ?>" class="ec_is_datepicker" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

						<?php

						/* START ADVANCED FILE TYPE */
						}else if( $optionset->option_type == "file" ){
						?>

							<input type="file" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

						<?php

						/* START ADVANCED SWATCH TYPE */
						}else if( $optionset->option_type == "swatch" ){ ?>
							<input type="hidden" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="0" />
							<ul class="ec_details_swatches ec_details_html_swatches">
								<?php
								for( $j=0; $j<count( $optionitems ); $j++ ){
									if ( '' != $optionitems[$j]->optionitem_icon ) {
								?>
									<li class="ec_details_swatch ec_advanced <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?> ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_active<?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-id="<?php echo esc_attr( $optionitems[$j]->optionitem_id ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[$j]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[$j]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[$j]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[$j]->optionitem_price_multiplier ); ?>"><img src="<?php if( substr( $optionitems[$j]->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionitems[$j]->optionitem_icon, 0, 8 ) == 'https://' ){ echo esc_attr( $optionitems[$j]->optionitem_icon ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/swatches/" . $optionitems[$j]->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" title="<?php echo esc_attr( $optionitems[$j]->optionitem_name ); ?> <?php 
										if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
											// No pricing shown in this case.
										} else if ( $optionitems[$j]->optionitem_enable_custom_price_label && ( $optionitems[$j]->optionitem_price != 0 || ( isset( $optionitems[$j]->optionitem_price ) && $optionitems[$j]->optionitem_price != 0 ) || ( isset( $optionitems[$j]->optionitem_price_onetime ) && $optionitems[$j]->optionitem_price_onetime != 0 ) ) ) {
											echo ' ' . esc_attr( $optionitems[$j]->optionitem_custom_price_label );
										} else if( $optionitems[$j]->optionitem_price > 0 ) {
											echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
										} else if( $optionitems[$j]->optionitem_price < 0 ) {
											echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
										} else if( $optionitems[$j]->optionitem_price_onetime > 0 ) {
											echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
										} else if( $optionitems[$j]->optionitem_price_onetime < 0 ) {
											echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
										} else if( isset( $optionitems[$j]->optionitem_price_override ) && $optionitems[$j]->optionitem_price_override > -1 ) {
											echo ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_override ) );
										} ?>" /></li>
								<?php
									} else { ?>
										<li class="ec_details_swatch wpeasycart-html-swatch ec_advanced <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?> ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_active<?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-id="<?php echo esc_attr( $optionitems[$j]->optionitem_id ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[$j]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[$j]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[$j]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[$j]->optionitem_price_multiplier ); ?>" title="<?php echo esc_attr( $optionitems[$j]->optionitem_name ); ?> <?php 
										if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
											// No pricing shown in this case.
										} else if ( $optionitems[$j]->optionitem_enable_custom_price_label && ( $optionitems[$j]->optionitem_price != 0 || ( isset( $optionitems[$j]->optionitem_price ) && $optionitems[$j]->optionitem_price != 0 ) || ( isset( $optionitems[$j]->optionitem_price_onetime ) && $optionitems[$j]->optionitem_price_onetime != 0 ) ) ) {
											echo ' ' . esc_attr( $optionitems[$j]->optionitem_custom_price_label );
										} else if( $optionitems[$j]->optionitem_price > 0 ) {
											echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
										} else if( $optionitems[$j]->optionitem_price < 0 ) {
											echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
										} else if( $optionitems[$j]->optionitem_price_onetime > 0 ) {
											echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
										} else if( $optionitems[$j]->optionitem_price_onetime < 0 ) {
											echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
										} else if( isset( $optionitems[$j]->optionitem_price_override ) && $optionitems[$j]->optionitem_price_override > -1 ) {
											echo ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_override ) );
										} ?>"><?php echo esc_attr( $optionitems[$j]->optionitem_name ); ?></li>
									<?php
									}
								}
								$first_optionitem_image_type = false;
								?>
							</ul>
						
						<?php
						/* START ADVANCED GRID TYPE */
						}else if( $optionset->option_type == "grid" ){
							$has_quantity_grid = true;
						?>

							<?php
							foreach( $optionitems as $optionitem ){

							if( $optionitem->optionitem_initial_value > 0 ){	
								if( $optionitem->optionitem_price >= 0 ){
									$add_price_grid = $add_price_grid + $optionitem->optionitem_price;

								}else if( $optionitem->optionitem_price_override >= 0 ){
									$override_price_grid = $optionitem->optionitem_price_override;

								}else if( $optionitem->optionitem_price_onetime > 0 ){
									$add_order_price_grid = $add_order_price_grid + $optionitem->optionitem_price_onetime;

								}
							}
							?>

								<div class="ec_details_grid_row"><span><?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?></span><input type="number" min="<?php if( $this->product->min_purchase_quantity > 0 ){ echo esc_attr( $this->product->min_purchase_quantity ); }else{ echo '0'; } ?>"<?php if( $this->product->show_stock_quantity || $this->product->max_purchase_quantity > 0 ){ ?> max="<?php if( $this->product->max_purchase_quantity > 0 ){ echo esc_attr( $this->product->max_purchase_quantity ); }else{ echo esc_attr( $this->product->stock_quantity ); } ?>"<?php }?> step="1" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $optionitem->optionitem_id ); ?>" value="<?php echo esc_attr( number_format( $optionitem->optionitem_initial_value, 0, "", "" ) ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>" /><?php
								if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
									// No pricing shown in this case.
								} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
									echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitem->optionitem_custom_price_label ) . '</span>';
								} else if ( $optionitem->optionitem_price > 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price < 0 ){
									echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price_onetime > 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price_onetime < 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
								} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')</span>';
								} ?></div>

							<?php
							}
							?>

						<?php

						/* START ADVANCED RADIO TYPE */
						}else if( $optionset->option_type == "radio" ){
						?>

							<?php
							foreach( $optionitems as $optionitem ){
							?>

								<div class="ec_details_radio_row <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?>"><input type="radio" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" value="<?php echo esc_attr( $optionitem->optionitem_id ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>"<?php if( $optionitem->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ) == strtolower( $optionitem->optionitem_name ) ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitem->optionitem_name ) ){ ?> checked="checked"<?php }?> /> <?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?> <?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitem->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitem->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitem->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitem->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitem->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')</span>';
							} ?></div>

							<?php
							}
							?>

						<?php
						$first_optionitem_image_type = false;

						/* START ADVANCED TEXT TYPE */
						}else if( $optionset->option_type == "text" ){
						?>

							<input type="text" value="<?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); }else if( $optionitems[0]->optionitem_initial_value != '' ){ echo esc_attr( $optionitems[0]->optionitem_initial_value ); } ?>" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

						<?php

						/* START ADVANCED NUMBER TYPE */
						}else if( $optionset->option_type == "number" ){
						?>

							<input type="number" value="<?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); }else if( $optionitems[0]->optionitem_initial_value != '' ){ echo esc_attr( $optionitems[0]->optionitem_initial_value ); } ?>" min="<?php echo esc_attr( $optionset->option_meta['min'] ); ?>" max="<?php echo esc_attr( $optionset->option_meta['max'] ); ?>" step="<?php echo esc_attr( $optionset->option_meta['step'] ); ?>" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

						<?php

						/* START ADVANCED TEXT AREA TYPE */
						}else if( $optionset->option_type == "textarea" ){
						?>

							<textarea name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>"><?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); } ?></textarea><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

						<?php

						/* START ADVANCED DIMENSIONS TYPE */
						}else if( $optionset->option_type == "dimensions1" || $optionset->option_type == "dimensions2" ){

							// Type 1 is NO sub dimensions (34")
							// Type 2 USES sub dimensions (34 1/2")

							$type = 2;

							if( $optionitems[0]->optionitem_name == "DimensionType1" )
								$type = 1;
						?>
							<?php if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if( $optionitems[0]->optionitem_price > 0 ){
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							}else if( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							}else if( $optionitems[0]->optionitem_price_onetime > 0 ){
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							}else if( $optionitems[0]->optionitem_price_onetime < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							}else if( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ){
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							}else if( isset( $optionitems[0]->optionitem_price_per_character ) && $optionitems[0]->optionitem_price_per_character > 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_per_character ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment_per_character' ) . ')</span>';
							} ?>

							<input type="text" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_width" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_width" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>" class="ec_dimensions_box ec_dimensions_width" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-is-metric="<?php echo esc_attr( get_option( 'ec_option_enable_metric_unit_display' ) ); ?>" />

							<?php if( $type == 2 ){ ?>
							<select name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_sub_width" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_sub_width" class="ec_dimensions_select">
								<option value="0">0</option>
								<option value="1/16">1/16</option>
								<option value="1/8">1/8</option>
								<option value="3/16">3/16</option>
								<option value="1/4">1/4</option>
								<option value="5/16">5/16</option>
								<option value="3/8">3/8</option>
								<option value="7/16">7/16</option>
								<option value="1/2">1/2</option>
								<option value="9/16">9/16</option>
								<option value="5/8">5/8</option>
								<option value="11/16">11/16</option>
								<option value="3/4">3/4</option>
								<option value="13/16">13/16</option>
								<option value="7/8">7/8</option>
								<option value="15/16">15/16</option>
							</select>
							<?php }?>

							<span class="ec_dimensions_seperator">x</span>

							<input type="text" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_height" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_height" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>" class="ec_dimensions_box" />

							<?php if( $type == 2 ){ ?>
							<select name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_sub_height" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_sub_height" class="ec_dimensions_select">
								<option value="0">0</option>
								<option value="1/16">1/16</option>
								<option value="1/8">1/8</option>
								<option value="3/16">3/16</option>
								<option value="1/4">1/4</option>
								<option value="5/16">5/16</option>
								<option value="3/8">3/8</option>
								<option value="7/16">7/16</option>
								<option value="1/2">1/2</option>
								<option value="9/16">9/16</option>
								<option value="5/8">5/8</option>
								<option value="11/16">11/16</option>
								<option value="3/4">3/4</option>
								<option value="13/16">13/16</option>
								<option value="7/8">7/8</option>
								<option value="15/16">15/16</option>
							</select>
							<?php }?>

						<?php
						}
					?>
						</div>
					</div>				
					<?php
					}
					?>
				</div>
				<?php }?>
				<?php /* END ADVANCED OPTIONS*/ ?>

				<?php /* Maybe add recaptcha */ ?>
				<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
				<input type="hidden" id="ec_grecaptcha_response_inquiry" name="ec_grecaptcha_response_inquiry" value="" />
				<input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
				<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_product_details_inquiry_recaptcha"></div>
				<?php }?>

				<div class="ec_details_add_to_cart">
					<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquire' ); ?>" onclick="return ec_details_submit_inquiry( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
				</div>
				<input type="hidden" name="ec_cart_form_action" value="send_inquiry" />
				<input type="hidden" name="ec_cart_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-send-inquiry' ) ); ?>" />
				<input type="hidden" name="ec_inquiry_model_number" value="<?php echo esc_attr( $this->product->model_number ); ?>" />
				</form>
			</div>
			<div class="ec_details_large_popup_close"><input type="button" onclick="ec_details_hide_inquiry_popup( '<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>' );" value="x"></div>
		</div>
	</div>
</div>
</div>
<?php } ?>
<?php /* END INQUIRY OPTIONS */ ?>
<?php 
$rules = array( );
foreach( $this->product->advanced_optionsets as $advanced_option ){
	if( isset( $advanced_option->conditional_logic ) ){
		$rules[$advanced_option->option_to_product_id] = json_decode( $advanced_option->conditional_logic );
	}
} 
?>  
<script>
var ec_advanced_logic_rules_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> = [
	<?php
	if ( count( $rules ) > 0 ) {
		foreach ( $rules as $key => $option_rules ) {
			if ( is_object( $option_rules ) && isset( $option_rules->enabled ) && $option_rules->enabled && isset( $option_rules->rules ) && is_array( $option_rules->rules ) && count( $option_rules->rules ) > 0 ) {
				echo "{
					'id': " . esc_js( $key ) . ",
					'show_field': " . esc_attr( ( $option_rules->show_field ) ? 'true' : 'false' ) . ",
					'and_rules': '" . esc_attr( $option_rules->and_rules ) . "',
					'rules':[";
						foreach( $option_rules->rules as $rule ){
							echo "
							{	
									'option_id': " . esc_attr( (int) $rule->option_id ) . ",
									'operator': '" . esc_js( $rule->operator ) . "',
									'optionitem_id': " . esc_js( (int) $rule->optionitem_id ) . ",
									'optionitem_value': '" . esc_js( $rule->optionitem_value ) . "'
							},";
						}
					echo "
					]
				},";
			}
		}
	}?>
];
</script>
<section class="ec_product_details_page<?php echo ( isset( $this->atts['cols_desktop'] ) ) ? ' ec-product-details-cols-desktop-' . (int) $this->atts['cols_desktop'] : ''; ?><?php echo ( isset( $this->atts['columns'] ) ) ? ' ec-product-details-cols-' . (int) $this->atts['columns'] : ''; ?><?php echo ( isset( $this->atts['cols_tablet'] ) ) ? ' ec-product-details-cols-tablet-' . (int) $this->atts['cols_tablet'] : ''; ?><?php echo ( isset( $this->atts['cols_mobile'] ) ) ? ' ec-product-details-cols-mobile-' . (int) $this->atts['cols_mobile'] : ''; ?><?php echo ( isset( $this->atts['cols_mobile_small'] ) ) ? ' ec-product-details-cols-mobile-small-' . (int) $this->atts['cols_mobile_small'] : ''; ?><?php echo ( isset( $this->atts['details_sizing'] ) ) ? ' ec-product-details-sizing-' . (int) $this->atts['details_sizing'] : ''; ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php if( $this->product->has_promotion_text( ) ){ ?>
	<div class="ec_cart_success"><div><?php $this->product->display_promotion_text( ); ?></div></div><?php }?>
	<?php if( $this->product->is_subscription_item && $this->product->trial_period_days > 0 ){ ?>
	<div class="ec_cart_success"><?php echo wp_easycart_language( )->get_text( 'product_page', 'product_page_start_trial_1' ); ?> <?php echo esc_attr( $this->product->trial_period_days ); ?> <?php echo wp_easycart_language( )->get_text( 'product_page', 'product_page_start_trial_2' ); ?></div>
	<?php }?>
	<?php if( isset( $_GET['ec_action'] ) && $_GET['ec_action'] == 'product-notify-unsubscribe' && isset( $_GET['unsubscribe_email'] ) && isset( $_GET['unsubscribe_id'] ) ){ 
	global $wpdb;
	$wpdb->query( $wpdb->prepare( "UPDATE ec_product_subscriber SET status = 'unsubscribed' WHERE email = %s AND product_subscriber_id = %d", sanitize_email( $_GET['unsubscribe_email'] ), (int) $_GET['unsubscribe_id'] ) );
	?>
	<div class="ec_cart_success"><?php echo wp_easycart_language( )->get_text( 'product_page', 'product_page_notify_unsubscribe_success' ); ?></div>
	<?php }?>
	<?php 
	/* START PRODUCT BREADCRUMBS */ 
	?><?php if( ( isset( $this->atts['show_breadcrumbs'] ) && $this->atts['show_breadcrumbs'] ) || ( ! isset( $this->atts['show_breadcrumbs'] ) && get_option( 'ec_option_show_breadcrumbs' ) ) ){ ?>
	<h4 class="ec_details_breadcrumbs" id="ec_breadcrumbs_type1">
		<a href="<?php echo esc_attr( home_url( ) ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_home_link' ); ?></a> / 
		<a href="<?php echo esc_attr( $this->store_page ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_store_link' ); ?></a> <?php if( $this->product->menuitems[0]->menulevel1_1_name ){ ?> / 
		<a href="<?php if( !get_option( 'ec_option_use_old_linking_style' ) && $this->product->post_id != "0" ){ 
			echo esc_attr( get_permalink( $this->product->menuitems[0]->menulevel1_1_post_id ) ); 
		}else{ 
			echo esc_attr( $this->store_page ) . esc_attr( $this->permalink_divider ) . "menuid=" . esc_attr( $this->product->menuitems[0]->menulevel1_1_menu_id );
		} ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->menuitems[0]->menulevel1_1_name ); ?></a>
		<?php if( $this->product->menuitems[0]->menulevel2_1_name ){ ?> / 
		<a href="<?php if( !get_option( 'ec_option_use_old_linking_style' ) && $this->product->post_id != "0" ){ 
			echo esc_attr( get_permalink( $this->product->menuitems[0]->menulevel2_1_post_id ) );
		}else{ 
			echo esc_attr( $this->store_page ) . esc_attr( $this->permalink_divider ) . "submenuid=" . esc_attr( $this->product->menuitems[0]->menulevel2_1_menu_id );
		} ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->menuitems[0]->menulevel2_1_name ); ?></a>
		<?php if( $this->product->menuitems[0]->menulevel3_1_name ){ ?> / 
		<a href="<?php if( !get_option( 'ec_option_use_old_linking_style' ) && $this->product->post_id != "0" ){ 
			echo esc_attr( get_permalink( $this->product->menuitems[0]->menulevel3_1_post_id ) );
		}else{
			echo esc_attr( $this->store_page ) . esc_attr( $this->permalink_divider ) . "subsubmenuid=" . esc_attr( $this->product->menuitems[0]->menulevel3_1_menu_id ); 
		} ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->menuitems[0]->menulevel3_1_name ); ?></a><?php } } }?>
	</h4>
	<?php } ?><?php 
	/* START MAIN DATA AREA FOR PRODUCT */ ?>
	<div class="ec_details_content<?php echo ( ( isset( $this->atts['show_breadcrumbs'] ) && ! $this->atts['show_breadcrumbs'] ) || ( ! isset( $this->atts['show_breadcrumbs'] ) && ! get_option( 'ec_option_show_breadcrumbs' ) ) ) ? ' ec-details-content-no-breadcrumbs' : ''; ?>">
		<?php /* START MOBILE SIZED CONTENT REGION */ ?>
		<div class="ec_details_mobile_title_area">
			<?php if( ( isset( $this->atts['show_title'] ) && $this->atts['show_title'] ) || ( ! isset( $this->atts['show_title'] ) ) ) { ?>
			<h1 class="ec_details_title ec_details_title_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" style="<?php echo ( isset( $this->atts['title_font'] ) ) ? 'font-family:' . esc_attr( $this->atts['title_font'] ) . ' !important;' : ''; ?><?php echo ( isset( $this->atts['title_color'] ) ) ? 'color:' . esc_attr( $this->atts['title_color'] ) . ' !important;' : ''; ?>"><?php echo wp_easycart_escape_html( $this->product->title ); ?></h1>
			<?php }?>
			<?php if( ( isset( $this->atts['show_customer_reviews'] ) && $this->atts['show_customer_reviews'] ) || ( ! isset( $this->atts['show_customer_reviews'] ) && $this->product->use_customer_reviews ) ){ ?>
			<div class="ec_details_review_holder">
				<span class="ec_details_review_stars">
					<?php $rating = $this->product->get_rating( ); ?>
					<div class="ec_product_details_star_<?php if( $rating > 0.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
					<div class="ec_product_details_star_<?php if( $rating > 1.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
					<div class="ec_product_details_star_<?php if( $rating > 2.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
					<div class="ec_product_details_star_<?php if( $rating > 3.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
					<div class="ec_product_details_star_<?php if( $rating > 4.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
				</span>
				<div class="ec_details_reviews"><?php $this->product->display_product_number_reviews(); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_reviews_text' ); ?></div>
			</div>
			<?php }?>
			<?php 
			$vat_rate_multiplier = 1;
			if ( $this->product->login_for_pricing && !$this->product->is_login_for_pricing_valid( ) ) {
				// No Pricing

			}else if( ( $this->product->is_catalog_mode && get_option( 'ec_option_hide_price_seasonal' ) ) || 
					  ( $this->product->is_inquiry_mode && get_option( 'ec_option_hide_price_inquiry' ) ) ){ // NO PRICE SHOWN

			}else if( $this->product->vat_rate > 0  && get_option( 'ec_option_show_multiple_vat_pricing' ) ){ 
			$shipping_state = '';
				$shipping_country = '';
				if( isset( $GLOBALS['ec_cart_data']->shipping_state ) && $GLOBALS['ec_cart_data']->shipping_state != '' ){
					$shipping_state = $GLOBALS['ec_cart_data']->shipping_state;
				}else if( isset( $GLOBALS['ec_user']->shipping->state ) && $GLOBALS['ec_user']->shipping->state != '' ){
					$shipping_state = $GLOBALS['ec_user']->shipping->state;
				}
				if( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) && $GLOBALS['ec_cart_data']->cart_data->shipping_country != '' ){
					$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
				}else if( isset( $GLOBALS['ec_user']->shipping->country ) && $GLOBALS['ec_user']->shipping->country != '' ){
					$shipping_country = $GLOBALS['ec_user']->shipping->country;
				}
				$vat_tax_class = new ec_tax( $this->product->price, $this->product->price, $this->product->price, $shipping_state, $shipping_country, false, 0, (object) array( 
					'cart' => array( 
						(object) array( 
							'product_id' => $this->product->product_id, 
							'total_price' => $this->product->price, 
							'manufacturer_id' => $this->product->manufacturer_id, 
							'is_taxable' => $this->product->is_taxable, 
							'vat_enabled' => $this->product->vat_rate 
						)
					)
				) );
				$vat_rate = apply_filters( 'wp_easycart_product_details_vat_rate', $vat_tax_class->vat_rate, $this->product );
				$vat_row = (object) array(
					'vat_rate'  => $vat_rate,
					'vat_added' => $vat_tax_class->vat_added,
					'vat_included' => $vat_tax_class->vat_included
				);
				$vat_rate_multiplier = ( $vat_rate / 100 ) + 1;

			?>
			<?php if( get_option( 'ec_option_show_multiple_vat_pricing' ) == '1' ){ ?>
			<div class="ec_details_price ec_details_no_vat_price"><?php $this->product->display_product_pricing_no_vat( 
				( isset( $this->atts['price_font'] ) ) ? $this->atts['price_font'] : false,
				( isset( $this->atts['price_color'] ) ) ? $this->atts['price_color'] : false,
				( isset( $this->atts['list_price_font'] ) ) ? $this->atts['list_price_font'] : false,
				( isset( $this->atts['list_price_color'] ) ) ? $this->atts['list_price_color'] : false,
				$wpeasycart_addtocart_shortcode_rand
			); ?></div>
			<?php }?>
			<div class="ec_details_price ec_details_vat_price"><?php $this->product->display_product_pricing_vat( 
				( isset( $this->atts['price_font'] ) ) ? $this->atts['price_font'] : false,
				( isset( $this->atts['price_color'] ) ) ? $this->atts['price_color'] : false,
				( isset( $this->atts['list_price_font'] ) ) ? $this->atts['list_price_font'] : false,
				( isset( $this->atts['list_price_color'] ) ) ? $this->atts['list_price_color'] : false,
				$wpeasycart_addtocart_shortcode_rand
			); ?></div>

			<?php }else{ ?>
			<div class="ec_details_price ec_details_single_price"><?php $this->product->display_product_list_price( 
				( isset( $this->atts['list_price_font'] ) ) ? $this->atts['list_price_font'] : false,
				( isset( $this->atts['list_price_color'] ) ) ? $this->atts['list_price_color'] : false
			); ?><?php if ( $this->product->replace_price_label && in_array( $this->product->enable_price_label, array( 2, 4, 6, 7 ) ) ) { ?>
					<span class="ec_product_price"><?php echo wp_easycart_escape_html( $this->product->custom_price_label ); ?></span>
				<?php } else {
					$this->product->display_price( 
						( isset( $this->atts['price_font'] ) ) ? $this->atts['price_font'] : false,
						( isset( $this->atts['price_color'] ) ) ? $this->atts['price_color'] : false,
						$wpeasycart_addtocart_shortcode_rand
					);
				} ?><?php if ( ! $this->product->replace_price_label && in_array( $this->product->enable_price_label, array( 2, 4, 6, 7 ) ) ) {
				?><span class="ec_details_price_label"><?php echo wp_easycart_escape_html( $this->product->custom_price_label ); ?></span><?php
			} ?></div>
			<?php }?>
			<div class="ec_details_clear"></div>
		</div>
		<?php /* END MOBILE SIZED CONTENT REGION */ ?>

		<?php /* START PRODUCT IMAGES AREA */ ?>
		<div class="ec_details_images <?php if( isset( $this->atts['details_sizing'] ) ){ echo 'ec_details_images-' . esc_attr( (int) $this->atts['details_sizing'] ); }else{ echo ( get_option( 'ec_option_product_details_sizing' ) && get_option( 'ec_option_product_details_sizing' ) != '' ) ? 'ec_details_images-' . esc_attr( (int) get_option( 'ec_option_product_details_sizing' ) ) : ''; } ?>">
		<?php if( apply_filters( 'wp_easycart_product_details_show_images', true ) ){ ?>
			<div class="ec_details_main_image ec_details_main_image_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php echo ( ! $ipad && ! $iphone && get_option( 'ec_option_show_magnification' ) ) ? ' mag_enabled' : ''; ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( ( isset( $this->atts['show_lightbox'] ) && $this->atts['show_lightbox'] ) || ( ! isset( $this->atts['show_lightbox'] ) && get_option( 'ec_option_show_large_popup' ) ) ){ ?> onclick="ec_details_show_image_popup( '<?php echo esc_attr( $this->product->model_number ); ?>' );"<?php }else{ ?> style="cursor:inherit;"<?php }?>>
				<?php do_action( 'wp_easycart_product_details_image_holder_pre', $this->product );
				$magbox_active = true;
				if( $this->product->use_optionitem_images ){
					$first_image_found = false;
					$first_optionitem_id = false;
					if( $this->product->use_advanced_optionset ) {
						if( count( $this->product->advanced_optionsets ) > 0 ) {
							$valid_optionset = false;
							foreach( $this->product->advanced_optionsets as $adv_optionset ) {
								if( ! $valid_optionset && ( $adv_optionset->option_type == 'combo' || $adv_optionset->option_type == 'swatch' || $adv_optionset->option_type == 'radio' ) ) {
									$valid_optionset = $adv_optionset;
								}
							}
							if ( $valid_optionset ) {
								$optionitems = $this->product->get_advanced_optionitems( $valid_optionset->option_id );
								if ( count( $optionitems ) > 0 ) {
									$first_optionitem_id = $optionitems[0]->optionitem_id;
								}
							}
						}
					} else {
						if( count( $this->product->options->optionset1->optionset ) > 0 ){
							$first_optionitem_id = $this->product->options->optionset1->optionset[0]->optionitem_id;
						}
					}
					if( $first_optionitem_id ) {
						for( $i=0; $i<count( $this->product->images->imageset ); $i++ ){
							if( $this->product->images->imageset[$i]->optionitem_id == $first_optionitem_id ){
								if( count( $this->product->images->imageset[$i]->product_images ) > 0  && 'video:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
									$video_str = substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 );
									$video_arr = explode( ':::', $video_str );
									if ( count( $video_arr ) >= 2 ) {
										echo '<div class="wp-easycart-video-box"><video controls><source src="' . esc_attr( $video_arr[0] ) . '" /></video></div>';
										echo '<img src="' . esc_attr( $video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" style="display:none" />';
									}
									$magbox_active = false;
								} else if( count( $this->product->images->imageset[$i]->product_images ) > 0  && 'youtube:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 8 ) ) {
									$youtube_video_str = substr( $this->product->images->imageset[$i]->product_images[0], 8, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 8 );
									$youtube_video_arr = explode( ':::', $youtube_video_str );
									if ( count( $youtube_video_arr ) >= 2 ) {
										echo '<div class="wp-easycart-video-box"><iframe src="' . esc_attr( $youtube_video_arr[0] ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
										echo '<img src="' . esc_attr( $youtube_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" style="display:none" />';
									}
									$magbox_active = false;
								} else if( count( $this->product->images->imageset[$i]->product_images ) > 0  && 'vimeo:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
									$vimeo_video_str = substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 );
									$vimeo_video_arr = explode( ':::', $vimeo_video_str );
									if ( count( $vimeo_video_arr ) >= 2 ) {
										echo '<div class="wp-easycart-video-box"><iframe src="' . esc_attr( $vimeo_video_arr[0] ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
										echo '<img src="' . esc_attr( $vimeo_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" style="display:none" />';
									}
									$magbox_active = false;
								} else { ?>
									<img src="<?php if( count( $this->product->images->imageset[$i]->product_images ) > 0 ) { 
										if ( 'image1' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_first_image_url( ) );
										} else if( 'image2' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_second_image_url( ) );
										} else if( 'image3' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_third_image_url( ) );
										} else if( 'image4' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_fourth_image_url( ) );
										} else if( 'image5' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_fifth_image_url( ) );
										} else if( 'image:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
											echo esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 ) ) );
										} else {
											$product_image_media = wp_get_attachment_image_src( $this->product->images->imageset[$i]->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
											if( $product_image_media && isset( $product_image_media[0] ) ) {
												echo esc_attr( $product_image_media[0] );
											}
										}
									} else { 
										echo esc_attr( $this->product->get_first_image_url( ) );
									} ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" />
								<?php } // close check for video
							}
						}
					}
				} else {
					if( count( $this->product->images->product_images ) > 0  && 'video:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
						$video_str = substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 );
						$video_arr = explode( ':::', $video_str );
						if ( count( $video_arr ) >= 2 ) {
							echo '<div class="wp-easycart-video-box"><video controls><source src="' . esc_attr( $video_arr[0] ) . '" /></video></div>';
							echo '<img src="' . esc_attr( $video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" style="display:none" />';
						}
						$magbox_active = false;
					} else if( count( $this->product->images->product_images ) > 0  && 'youtube:' == substr( $this->product->images->product_images[0], 0, 8 ) ) {
						$youtube_video_str = substr( $this->product->images->product_images[0], 8, strlen( $this->product->images->product_images[0] ) - 8 );
						$youtube_video_arr = explode( ':::', $youtube_video_str );
						if ( count( $youtube_video_arr ) >= 2 ) {
							echo '<div class="wp-easycart-video-box"><iframe src="' . esc_attr( $youtube_video_arr[0] ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
							echo '<img src="' . esc_attr( $youtube_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" style="display:none" />';
						}
						$magbox_active = false;
					} else if( count( $this->product->images->product_images ) > 0  && 'vimeo:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
						$vimeo_video_str = substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 );
						$vimeo_video_arr = explode( ':::', $vimeo_video_str );
						if ( count( $vimeo_video_arr ) >= 2 ) {
							echo '<div class="wp-easycart-video-box"><iframe src="' . esc_attr( $vimeo_video_arr[0] ) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
							echo '<img src="' . esc_attr( $vimeo_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" style="display:none" />';
						}
						$magbox_active = false;
					} else { ?>
						<img src="<?php if( count( $this->product->images->product_images ) > 0 ) { 
							if ( 'image1' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_first_image_url( ) );
							} else if( 'image2' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_second_image_url( ) );
							} else if( 'image3' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_third_image_url( ) );
							} else if( 'image4' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_fourth_image_url( ) );
							} else if( 'image5' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_fifth_image_url( ) );
							} else if( 'image:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
								echo esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 ) ) );
							} else {
								$product_image_media = wp_get_attachment_image_src( $this->product->images->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
								if( $product_image_media && isset( $product_image_media[0] ) ) {
									echo esc_attr( $product_image_media[0] );
								}
							}
						} else { 
							echo esc_attr( $this->product->get_first_image_url( ) );
						} ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" />
					<?php } // close check for video ?>
				<?php } // close check for option item images ?>
				<?php do_action( 'wp_easycart_product_details_image_holder_post', $this->product ); ?>
			</div>

			<?php /* START MAIN IMAGE THUMBNAILS */ ?>
			<?php /* START DISPLAY FOR OPTION ITEM IMAGES USEAGE */ ?>
			<?php if( ( isset( $this->atts['show_thumbnails'] ) && $this->atts['show_thumbnails'] ) || ( ! isset( $this->atts['show_thumbnails'] ) ) ) { ?>
			<?php if( $this->product->use_optionitem_images ){
				$optionitem_id_array = array( );
				if( $this->product->use_advanced_optionset ) {
					if( count( $this->product->advanced_optionsets ) > 0 ) {
						$valid_optionset = false;
						foreach( $this->product->advanced_optionsets as $adv_optionset ) {
							if( ! $valid_optionset && ( $adv_optionset->option_type == 'combo' || $adv_optionset->option_type == 'swatch' || $adv_optionset->option_type == 'radio' ) ) {
								$valid_optionset = $adv_optionset;
							}
						}
						if ( $valid_optionset ) {
							$optionitems = $this->product->get_advanced_optionitems( $valid_optionset->option_id );
							foreach( $optionitems as $optionitem ) {
								$optionitem_id_array[] = $optionitem->optionitem_id;
							}
						}
					}
				} else {
					foreach( $this->product->options->optionset1->optionset as $optionitem ){
						$optionitem_id_array[] = $optionitem->optionitem_id;
					}
				}
				$thumbnails_displayed = 0;
				for( $i=0; $i<count( $this->product->images->imageset ); $i++ ){
					if( in_array( $this->product->images->imageset[$i]->optionitem_id, $optionitem_id_array ) ){
						if( is_array( $this->product->images->imageset[$i]->product_images ) && count( $this->product->images->imageset[$i]->product_images ) > 0 ) { ?>
							<div class="ec_details_thumbnails ec_details_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $thumbnails_displayed > 0 ){ ?> ec_inactive<?php }?><?php if( count( $this->product->images->imageset[$i]->product_images ) <= 1 ){ ?> ec_no_thumbnails<?php }?>" id="ec_details_thumbnails_<?php echo esc_attr( $this->product->images->imageset[$i]->optionitem_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( count( $this->product->images->imageset[$i]->product_images ) <= 1 ){ ?> style="display:none !important;"<?php }?>>
							<?php $is_first_prod_image = true;
							foreach( $this->product->images->imageset[$i]->product_images as $product_image_id ) {
								if( 'image1' == $product_image_id ) {
									echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
										echo '<img src="';
										if ( substr( $this->product->images->imageset[$i]->image1, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image1, 0, 8 ) == 'https://' ){
											echo esc_attr( $this->product->images->imageset[$i]->image1 );
										} else {
											echo esc_attr( plugins_url( "/wp-easycart-data/products/pics1/" . $this->product->images->imageset[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) );
										}
										echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
									echo '</div>';
									$is_first_prod_image = false;
								} else if( 'image2' == $product_image_id ) {
									echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
										echo '<img src="';
										if ( substr( $this->product->images->imageset[$i]->image2, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image2, 0, 8 ) == 'https://' ){
											echo esc_attr( $this->product->images->imageset[$i]->image2 );
										} else {
											echo esc_attr( plugins_url( "/wp-easycart-data/products/pics2/" . $this->product->images->imageset[$i]->image2, EC_PLUGIN_DATA_DIRECTORY ) );
										}
										echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
									echo '</div>';
									$is_first_prod_image = false;
								} else if( 'image3' == $product_image_id ) {
									echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
										echo '<img src="';
										if ( substr( $this->product->images->imageset[$i]->image3, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image3, 0, 8 ) == 'https://' ){
											echo esc_attr( $this->product->images->imageset[$i]->image3 );
										} else {
											echo esc_attr( plugins_url( "/wp-easycart-data/products/pics3/" . $this->product->images->imageset[$i]->image3, EC_PLUGIN_DATA_DIRECTORY ) );
										}
										echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
									echo '</div>';
									$is_first_prod_image = false;
								} else if( 'image4' == $product_image_id ) {
									echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
										echo '<img src="';
										if ( substr( $this->product->images->imageset[$i]->image4, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image4, 0, 8 ) == 'https://' ){
											echo esc_attr( $this->product->images->imageset[$i]->image4 );
										} else {
											echo esc_attr( plugins_url( "/wp-easycart-data/products/pics4/" . $this->product->images->imageset[$i]->image4, EC_PLUGIN_DATA_DIRECTORY ) );
										}
										echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
									echo '</div>';
									$is_first_prod_image = false;
								} else if( 'image5' == $product_image_id ) {
									echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
										echo '<img src="';
										if ( substr( $this->product->images->imageset[$i]->image5, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image5, 0, 8 ) == 'https://' ){
											echo esc_attr( $this->product->images->imageset[$i]->image5 );
										} else {
											echo esc_attr( plugins_url( "/wp-easycart-data/products/pics5/" . $this->product->images->imageset[$i]->image5, EC_PLUGIN_DATA_DIRECTORY ) );
										}
										echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
									echo '</div>';
									$is_first_prod_image = false;
								} else if( 'image:' == substr( $product_image_id, 0, 6 ) ) {
									echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
										echo '<img src="' . esc_attr( apply_filters('wp_easycart_product_details_thumbnail_image_url_type', substr( $product_image_id, 6, strlen( $product_image_id ) - 6 ) ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
									echo '</div>';
									$is_first_prod_image = false;
								} else if( 'video:' == substr( $product_image_id, 0, 6 ) ) {
									$video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
									$video_arr = explode( ':::', $video_str );
									if ( count( $video_arr ) >= 2 ) {
										echo '<div class="ec_details_thumbnail ec_details_thumbnail_video videoType' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
											echo '<a href="' . esc_attr( $video_arr[0] ) . '" class="ec_details_video_thumb">';
												echo '<img src="' . esc_attr( $video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
												echo '<div class="wp-easycart-video-cover"></div>';
												echo '<div class="dashicons dashicons-controls-play"></div>';
											echo '</a>';
											do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
										echo '</div>';
										$is_first_prod_image = false;
									}
								} else if( 'youtube:' == substr( $product_image_id, 0, 8 ) ) {
									$youtube_video_str = substr( $product_image_id, 8, strlen( $product_image_id ) - 8 );
									$youtube_video_arr = explode( ':::', $youtube_video_str );
									if ( count( $youtube_video_arr ) >= 2 ) {
										echo '<div class="ec_details_thumbnail ec_details_thumbnail_video' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
											echo '<a href="' . esc_attr( $youtube_video_arr[0] ) . '" class="ec_details_youtube_thumb">';
												echo '<img src="' . esc_attr( $youtube_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
												echo '<div class="wp-easycart-video-cover"></div>';
												echo '<div class="dashicons dashicons-controls-play"></div>';
											echo '</a>';
											do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
										echo '</div>';
										$is_first_prod_image = false;
									}
								} else if( 'vimeo:' == substr( $product_image_id, 0, 6 ) ) {
									$vimeo_video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
									$vimeo_video_arr = explode( ':::', $vimeo_video_str );
									if ( count( $vimeo_video_arr ) >= 2 ) {
										echo '<div class="ec_details_thumbnail ec_details_thumbnail_video' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
											echo '<a href="' . esc_attr( $vimeo_video_arr[0] ) . '" class="ec_details_vimeo_thumb">';
												echo '<img src="' . esc_attr( $vimeo_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
												echo '<div class="wp-easycart-video-cover"></div>';
												echo '<div class="dashicons dashicons-controls-play"></div>';
											echo '</a>';
											do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
										echo '</div>';
										$is_first_prod_image = false;
									}
								} else {
									$product_image_media = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_thumbnail_size', 'medium' ) );
									$product_image_media_large = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
									if( $product_image_media && isset( $product_image_media[0] ) ) {
										echo '<div class="ec_details_thumbnail ec_details_thumbnail_wpmedia' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
											echo '<img src="' . esc_attr( $product_image_media[0] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '"' . ( ( $product_image_media_large && isset( $product_image_media_large[0] ) ) ? ' data-large-src="' . esc_attr( $product_image_media_large[0] ) . '"': '' ) . ' />';
											do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
										echo '</div>';
										$is_first_prod_image = false;
									}
								}
							} ?>
							</div>
						<?php } else { ?>
						<div class="ec_details_thumbnails ec_details_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $thumbnails_displayed > 0 ){ ?> ec_inactive<?php }?><?php if( $this->product->images->imageset[$i]->image2 == "" ){ ?> ec_no_thumbnails<?php }?>" id="ec_details_thumbnails_<?php echo esc_attr( $this->product->images->imageset[$i]->optionitem_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( trim( $this->product->images->imageset[$i]->image2 ) == "" ){ ?> style="display:none !important;"<?php }?>>
							<div class="ec_details_thumbnail ec_active" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php if( substr( $this->product->images->imageset[$i]->image1, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image1, 0, 8 ) == 'https://' ){ echo esc_attr( $this->product->images->imageset[$i]->image1 ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/pics1/" . $this->product->images->imageset[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
							
							<?php if( trim( $this->product->images->imageset[$i]->image2 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php if( substr( $this->product->images->imageset[$i]->image2, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image2, 0, 8 ) == 'https://' ){ echo esc_attr( $this->product->images->imageset[$i]->image2 ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/pics2/" . $this->product->images->imageset[$i]->image2, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" /></div><?php } ?>
							<?php if( trim( $this->product->images->imageset[$i]->image3 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php if( substr( $this->product->images->imageset[$i]->image3, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image3, 0, 8 ) == 'https://' ){ echo esc_attr( $this->product->images->imageset[$i]->image3 ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/pics3/" . $this->product->images->imageset[$i]->image3, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php if( trim( $this->product->images->imageset[$i]->image4 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php if( substr( $this->product->images->imageset[$i]->image4, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image4, 0, 8 ) == 'https://' ){ echo esc_attr( $this->product->images->imageset[$i]->image4 ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/pics4/" . $this->product->images->imageset[$i]->image4, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php if( trim( $this->product->images->imageset[$i]->image5 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php if( substr( $this->product->images->imageset[$i]->image5, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image5, 0, 8 ) == 'https://' ){ echo esc_attr( $this->product->images->imageset[$i]->image5 ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/pics5/" . $this->product->images->imageset[$i]->image5, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php do_action( 'wp_easycart_product_details_thumbnail_items_simple', $this->product, $wpeasycart_addtocart_shortcode_rand ); ?>
						</div>
						<?php if( $thumbnails_displayed == 0 ){ ?>
						<div class="ec_details_thumbnails ec_details_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_inactive<?php if( $this->product->images->imageset[$i]->image2 == "" ){ ?> ec_no_thumbnails<?php }?>" id="ec_details_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( trim( $this->product->images->imageset[$i]->image2 ) == "" ){ ?> style="display:none !important;"<?php }?>>
							<div class="ec_details_thumbnail ec_active" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( plugins_url( "/wp-easycart-data/products/pics1/" . $this->product->images->imageset[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
							<?php if( trim( $this->product->images->imageset[$i]->image2 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( plugins_url( "/wp-easycart-data/products/pics2/" . $this->product->images->imageset[$i]->image2, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php if( trim( $this->product->images->imageset[$i]->image3 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( plugins_url( "/wp-easycart-data/products/pics3/" . $this->product->images->imageset[$i]->image3, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php if( trim( $this->product->images->imageset[$i]->image4 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( plugins_url( "/wp-easycart-data/products/pics4/" . $this->product->images->imageset[$i]->image4, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php if( trim( $this->product->images->imageset[$i]->image5 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( plugins_url( "/wp-easycart-data/products/pics5/" . $this->product->images->imageset[$i]->image5, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							<?php do_action( 'wp_easycart_product_details_thumbnail_items_simple', $this->product, $wpeasycart_addtocart_shortcode_rand ); ?>
						</div>

						<?php } // Close test for thumbs displayed
					} // Close test for unlimited options
					$thumbnails_displayed++;
				}// Close test for existing option item id (bad data fix)

			} //Close for loop of image set
			/* END DISPLAY FOR OPTION ITEM IMAGES THUMNAILS */

			/* START DISPLAY FOR BASIC IMAGE THUMBNAILS */
			} else if( count( $this->product->images->product_images ) > 0 ) {
				if( count( $this->product->images->product_images ) > 1 ) {
					echo '<div class="ec_details_thumbnails ec_details_thumbnails_' . esc_attr( $this->product->product_id ) . '_' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
					$is_first_prod_image = true;
					foreach( $this->product->images->product_images as $product_image_id ) {
						if( 'image1' == $product_image_id ) {
							echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
								echo '<img src="' . esc_attr( $this->product->get_first_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
								do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
							echo '</div>';
							$is_first_prod_image = false;
						} else if( 'image2' == $product_image_id ) {
							echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
								echo '<img src="' . esc_attr( $this->product->get_second_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
								do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
							echo '</div>';
							$is_first_prod_image = false;
						} else if( 'image3' == $product_image_id ) {
							echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
								echo '<img src="' . esc_attr( $this->product->get_third_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
								do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
							echo '</div>';
							$is_first_prod_image = false;
						} else if( 'image4' == $product_image_id ) {
							echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
								echo '<img src="' . esc_attr( $this->product->get_fourth_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
								do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
							echo '</div>';
							$is_first_prod_image = false;
						} else if( 'image5' == $product_image_id ) {
							echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
								echo '<img src="' . esc_attr( $this->product->get_fifth_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
								do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
							echo '</div>';
							$is_first_prod_image = false;
						} else if( 'image:' == substr( $product_image_id, 0, 6 ) ) {
							echo '<div class="ec_details_thumbnail' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
								echo '<img src="' . esc_attr( apply_filters('wp_easycart_product_details_thumbnail_image_url_type', substr( $product_image_id, 6, strlen( $product_image_id ) - 6 ) ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
								do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
							echo '</div>';
							$is_first_prod_image = false;
						} else if( 'video:' == substr( $product_image_id, 0, 6 ) ) {
							$video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
							$video_arr = explode( ':::', $video_str );
							if ( count( $video_arr ) >= 2 ) {
								echo '<div class="ec_details_thumbnail ec_details_thumbnail_video videoType' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
									echo '<a href="' . esc_attr( $video_arr[0] ) . '" class="ec_details_video_thumb">';
										echo '<img src="' . esc_attr( $video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										echo '<div class="wp-easycart-video-cover"></div>';
										echo '<div class="dashicons dashicons-controls-play"></div>';
									echo '</a>';
									do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
								echo '</div>';
								$is_first_prod_image = false;
							}
						} else if( 'youtube:' == substr( $product_image_id, 0, 8 ) ) {
							$youtube_video_str = substr( $product_image_id, 8, strlen( $product_image_id ) - 8 );
							$youtube_video_arr = explode( ':::', $youtube_video_str );
							if ( count( $youtube_video_arr ) >= 2 ) {
								echo '<div class="ec_details_thumbnail ec_details_thumbnail_video' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
									echo '<a href="' . esc_attr( $youtube_video_arr[0] ) . '" class="ec_details_youtube_thumb">';
										echo '<img src="' . esc_attr( $youtube_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										echo '<div class="wp-easycart-video-cover"></div>';
										echo '<div class="dashicons dashicons-controls-play"></div>';
									echo '</a>';
									do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
								echo '</div>';
								$is_first_prod_image = false;
							}
						} else if( 'vimeo:' == substr( $product_image_id, 0, 6 ) ) {
							$vimeo_video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
							$vimeo_video_arr = explode( ':::', $vimeo_video_str );
							if ( count( $vimeo_video_arr ) >= 2 ) {
								echo '<div class="ec_details_thumbnail ec_details_thumbnail_video' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
									echo '<a href="' . esc_attr( $vimeo_video_arr[0] ) . '" class="ec_details_vimeo_thumb">';
										echo '<img src="' . esc_attr( $vimeo_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
										echo '<div class="wp-easycart-video-cover"></div>';
										echo '<div class="dashicons dashicons-controls-play"></div>';
									echo '</a>';
									do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
								echo '</div>';
								$is_first_prod_image = false;
							}
						} else {
							$product_image_media = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_thumbnail_size', 'medium' ) );
							$product_image_media_large = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
							if( $product_image_media && isset( $product_image_media[0] ) ) {
								echo '<div class="ec_details_thumbnail ec_details_thumbnail_wpmedia' . ( ( $is_first_prod_image ) ? ' ec_active' : '' ) . '" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
									echo '<img src="' . esc_attr( $product_image_media[0] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '"' . ( ( $product_image_media_large && isset( $product_image_media_large[0] ) ) ? ' data-large-src="' . esc_attr( $product_image_media_large[0] ) . '"': '' ) . ' />';
									do_action( 'wp_easycart_product_details_thumbnail_item', $product_image_id, $this->product->product_id, $wpeasycart_addtocart_shortcode_rand );
								echo '</div>';
								$is_first_prod_image = false;
							}
						}
					}
					echo '</div>';
				}
			} else if ( trim( $this->product->images->image2 ) != "" ) { ?>
			<div class="ec_details_thumbnails ec_details_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<div class="ec_details_thumbnail ec_active" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_first_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
				<div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_second_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
				<?php if( trim( $this->product->images->image3 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_third_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
				<?php if( trim( $this->product->images->image4 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_fourth_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
				<?php if( trim( $this->product->images->image5 ) != "" ){ ?><div class="ec_details_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_fifth_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
				<?php do_action( 'wp_easycart_product_details_thumbnail_items_simple', $this->product, $wpeasycart_addtocart_shortcode_rand ); ?>
			</div>
			<?php }?>
			<?php }?>
			<?php /* END MAIN IMAGE THUMBNAILS */ ?>

			<?php /* START IMAGE MAGNIFICATION BOX */ ?>
			<?php if( !$ipad && !$iphone && ( ( isset( $this->atts['show_image_hover'] ) && $this->atts['show_image_hover'] ) || ( !isset( $this->atts['show_image_hover'] ) && get_option( 'ec_option_show_magnification' ) ) ) ){?>
			<div class="ec_details_magbox ec_details_magbox_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php echo ( $magbox_active ) ? '' : ' inactive'; ?>">
				<div class="ec_details_magbox_image ec_details_magbox_image_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" style="background:url( '<?php 
				if( $this->product->use_optionitem_images ){
					$first_image_found = false;
					$first_optionitem_id = false;
					if( $this->product->use_advanced_optionset ) {
						if( count( $this->product->advanced_optionsets ) > 0 ) {
							$valid_optionset = false;
							foreach( $this->product->advanced_optionsets as $adv_optionset ) {
								if( ! $valid_optionset && ( $adv_optionset->option_type == 'combo' || $adv_optionset->option_type == 'swatch' || $adv_optionset->option_type == 'radio' ) ) {
									$valid_optionset = $adv_optionset;
								}
							}
							if ( $valid_optionset ) {
								$optionitems = $this->product->get_advanced_optionitems( $valid_optionset->option_id );
								if ( count( $optionitems ) > 0 ) {
									$first_optionitem_id = $optionitems[0]->optionitem_id;
								}
							}
						}
					} else {
						if( count( $this->product->options->optionset1->optionset ) > 0 ){
							$first_optionitem_id = $this->product->options->optionset1->optionset[0]->optionitem_id;
						}
					}
					if( $first_optionitem_id ) {
						for( $i=0; $i<count( $this->product->images->imageset ); $i++ ){
							if( ! $first_image_found && (int) $this->product->images->imageset[$i]->optionitem_id == (int) $first_optionitem_id ){
								if( count( $this->product->images->imageset[$i]->product_images ) > 0 ) {
									if( 'video:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
										$video_str = substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 );
										$video_arr = explode( ':::', $video_str );
										if ( count( $video_arr ) >= 2 ) {
											echo esc_attr( $video_arr[1] );
											$first_image_found = true;
										}
									} else if( 'youtube:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 8 ) ) {
										$youtube_video_str = substr( $this->product->images->imageset[$i]->product_images[0], 8, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 8 );
										$youtube_video_arr = explode( ':::', $youtube_video_str );
										if ( count( $youtube_video_arr ) >= 2 ) {
											echo esc_attr( $youtube_video_arr[1] );
											$first_image_found = true;
										}
									} else if( 'vimeo:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
										$vimeo_video_str = substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 );
										$vimeo_video_arr = explode( ':::', $vimeo_video_str );
										if ( count( $vimeo_video_arr ) >= 2 ) {
											echo esc_attr( $vimeo_video_arr[1] );
											$first_image_found = true;
										}
									} else { 
										if ( 'image1' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_first_image_url( ) );
										} else if( 'image2' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_second_image_url( ) );
										} else if( 'image3' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_third_image_url( ) );
										} else if( 'image4' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_fourth_image_url( ) );
										} else if( 'image5' == $this->product->images->imageset[$i]->product_images[0] ) {
											echo esc_attr( $this->product->get_fifth_image_url( ) );
										} else if( 'image:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
											echo esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 ) ) );
										} else {
											$product_image_media = wp_get_attachment_image_src( $this->product->images->imageset[$i]->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
											if( $product_image_media && isset( $product_image_media[0] ) ) {
												echo esc_attr( $product_image_media[0] );
											}
										}
										$first_image_found = true;
									}
								} else {
									echo esc_attr( $this->product->get_first_image_url( ) );
								}
							}
						}
					}
				} else { // Close check for option item images
					if( count( $this->product->images->product_images ) > 0  && 'video:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
						$video_str = substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 );
						$video_arr = explode( ':::', $video_str );
						if ( count( $video_arr ) >= 2 ) {
							echo esc_attr( $video_arr[1] );
						}
					} else if( count( $this->product->images->product_images ) > 0  && 'youtube:' == substr( $this->product->images->product_images[0], 0, 8 ) ) {
						$youtube_video_str = substr( $this->product->images->product_images[0], 8, strlen( $this->product->images->product_images[0] ) - 8 );
						$youtube_video_arr = explode( ':::', $youtube_video_str );
						if ( count( $youtube_video_arr ) >= 2 ) {
							echo esc_attr( $youtube_video_arr[1] );
						}
					} else if( count( $this->product->images->product_images ) > 0  && 'vimeo:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
						$vimeo_video_str = substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 );
						$vimeo_video_arr = explode( ':::', $vimeo_video_str );
						if ( count( $vimeo_video_arr ) >= 2 ) {
							echo esc_attr( $vimeo_video_arr[1] );
						}
					} else {
						if( count( $this->product->images->product_images ) > 0 ) { 
							if ( 'image1' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_first_image_url( ) );
							} else if( 'image2' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_second_image_url( ) );
							} else if( 'image3' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_third_image_url( ) );
							} else if( 'image4' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_fourth_image_url( ) );
							} else if( 'image5' == $this->product->images->product_images[0] ) {
								echo esc_attr( $this->product->get_fifth_image_url( ) );
							} else if( 'image:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
								echo esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 ) ) );
							} else {
								$product_image_media = wp_get_attachment_image_src( $this->product->images->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
								if( $product_image_media && isset( $product_image_media[0] ) ) {
									echo esc_attr( $product_image_media[0] );
								}
							}
						} else { 
							echo esc_attr( $this->product->get_first_image_url( ) );
						}
					} // close check for video
				} // close check for option item images ?>' ) no-repeat"></div>
			</div>
			<?php }?>
			<?php /* END IMAGE MAGNICFICATION BOX */ ?>

			<?php /* START PRODUCT IMAGES POPUP AREA */ ?>
			<?php if( ( isset( $this->atts['show_lightbox'] ) && $this->atts['show_lightbox'] ) || ( ! isset( $this->atts['show_lightbox'] ) && get_option( 'ec_option_show_large_popup' ) ) ){?>
			<div class="ec_details_large_popup" id="ec_details_large_popup_<?php echo esc_attr( $this->product->model_number ); ?>">
				<div class="ec_details_large_popup_content">
					<div class="ec_details_large_popup_padding">
						<div class="ec_details_large_popup_holder">
							<div class="ec_details_large_popup_main ec_details_large_popup_main_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php 
								if( $this->product->use_optionitem_images ){
									$first_image_found = false;
									$first_optionitem_id = false;
									if( $this->product->use_advanced_optionset ) {
										if( count( $this->product->advanced_optionsets ) > 0 ) {
											$valid_optionset = false;
											foreach( $this->product->advanced_optionsets as $adv_optionset ) {
												if( ! $valid_optionset && ( $adv_optionset->option_type == 'combo' || $adv_optionset->option_type == 'swatch' || $adv_optionset->option_type == 'radio' ) ) {
													$valid_optionset = $adv_optionset;
												}
											}
											if ( $valid_optionset ) {
												$optionitems = $this->product->get_advanced_optionitems( $valid_optionset->option_id );
												if ( count( $optionitems ) > 0 ) {
													$first_optionitem_id = $optionitems[0]->optionitem_id;
												}
											}
										}
									} else {
										if( count( $this->product->options->optionset1->optionset ) > 0 ){
											$first_optionitem_id = $this->product->options->optionset1->optionset[0]->optionitem_id;
										}
									}
									if( $first_optionitem_id ) {
										for( $i=0; $i<count( $this->product->images->imageset ); $i++ ){
											if( ! $first_image_found && (int) $this->product->images->imageset[$i]->optionitem_id == (int) $first_optionitem_id ){
												if( count( $this->product->images->imageset[$i]->product_images ) > 0 ) {
													if( 'video:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
														$video_str = substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 );
														$video_arr = explode( ':::', $video_str );
														if ( count( $video_arr ) >= 2 ) {
															echo esc_attr( $video_arr[1] );
															$first_image_found = true;
														}
													} else if( 'youtube:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 8 ) ) {
														$youtube_video_str = substr( $this->product->images->imageset[$i]->product_images[0], 8, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 8 );
														$youtube_video_arr = explode( ':::', $youtube_video_str );
														if ( count( $youtube_video_arr ) >= 2 ) {
															echo esc_attr( $youtube_video_arr[1] );
															$first_image_found = true;
														}
													} else if( 'vimeo:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
														$vimeo_video_str = substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 );
														$vimeo_video_arr = explode( ':::', $vimeo_video_str );
														if ( count( $vimeo_video_arr ) >= 2 ) {
															echo esc_attr( $vimeo_video_arr[1] );
															$first_image_found = true;
														}
													} else { 
														if ( 'image1' == $this->product->images->imageset[$i]->product_images[0] ) {
															echo esc_attr( $this->product->get_first_image_url( ) );
														} else if( 'image2' == $this->product->images->imageset[$i]->product_images[0] ) {
															echo esc_attr( $this->product->get_second_image_url( ) );
														} else if( 'image3' == $this->product->images->imageset[$i]->product_images[0] ) {
															echo esc_attr( $this->product->get_third_image_url( ) );
														} else if( 'image4' == $this->product->images->imageset[$i]->product_images[0] ) {
															echo esc_attr( $this->product->get_fourth_image_url( ) );
														} else if( 'image5' == $this->product->images->imageset[$i]->product_images[0] ) {
															echo esc_attr( $this->product->get_fifth_image_url( ) );
														} else if( 'image:' == substr( $this->product->images->imageset[$i]->product_images[0], 0, 6 ) ) {
															echo esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $this->product->images->imageset[$i]->product_images[0], 6, strlen( $this->product->images->imageset[$i]->product_images[0] ) - 6 ) ) );
														} else {
															$product_image_media = wp_get_attachment_image_src( $this->product->images->imageset[$i]->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
															if( $product_image_media && isset( $product_image_media[0] ) ) {
																echo esc_attr( $product_image_media[0] );
															}
														}
														$first_image_found = true;
													}
												} else {
													echo esc_attr( $this->product->get_first_image_url( ) );
												}
											}
										}
									}
								} else { // Close check for option item images
									if( count( $this->product->images->product_images ) > 0  && 'video:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
										$video_str = substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 );
										$video_arr = explode( ':::', $video_str );
										if ( count( $video_arr ) >= 2 ) {
											echo esc_attr( $video_arr[1] );
										}
									} else if( count( $this->product->images->product_images ) > 0  && 'youtube:' == substr( $this->product->images->product_images[0], 0, 8 ) ) {
										$youtube_video_str = substr( $this->product->images->product_images[0], 8, strlen( $this->product->images->product_images[0] ) - 8 );
										$youtube_video_arr = explode( ':::', $youtube_video_str );
										if ( count( $youtube_video_arr ) >= 2 ) {
											echo esc_attr( $youtube_video_arr[1] );
										}
									} else if( count( $this->product->images->product_images ) > 0  && 'vimeo:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
										$vimeo_video_str = substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 );
										$vimeo_video_arr = explode( ':::', $vimeo_video_str );
										if ( count( $vimeo_video_arr ) >= 2 ) {
											echo esc_attr( $vimeo_video_arr[1] );
										}
									} else {
										if( count( $this->product->images->product_images ) > 0 ) { 
											if ( 'image1' == $this->product->images->product_images[0] ) {
												echo esc_attr( $this->product->get_first_image_url( ) );
											} else if( 'image2' == $this->product->images->product_images[0] ) {
												echo esc_attr( $this->product->get_second_image_url( ) );
											} else if( 'image3' == $this->product->images->product_images[0] ) {
												echo esc_attr( $this->product->get_third_image_url( ) );
											} else if( 'image4' == $this->product->images->product_images[0] ) {
												echo esc_attr( $this->product->get_fourth_image_url( ) );
											} else if( 'image5' == $this->product->images->product_images[0] ) {
												echo esc_attr( $this->product->get_fifth_image_url( ) );
											} else if( 'image:' == substr( $this->product->images->product_images[0], 0, 6 ) ) {
												echo esc_attr( apply_filters('wp_easycart_product_details_image_url_type', substr( $this->product->images->product_images[0], 6, strlen( $this->product->images->product_images[0] ) - 6 ) ) );
											} else {
												$product_image_media = wp_get_attachment_image_src( $this->product->images->product_images[0], apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
												if( $product_image_media && isset( $product_image_media[0] ) ) {
													echo esc_attr( $product_image_media[0] );
												}
											}
										} else { 
											echo esc_attr( $this->product->get_first_image_url( ) );
										}
									} // close check for video
								} // close check for option item images ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>

							<?php /* SETUP POPUP THUMBNAILS */ ?>
							<?php if ( $this->product->use_optionitem_images ) { 
								$optionitem_id_array = array( );
								if( $this->product->use_advanced_optionset ) {
									if( count( $this->product->advanced_optionsets ) > 0 ) {
										$valid_optionset = false;
										foreach( $this->product->advanced_optionsets as $adv_optionset ) {
											if( ! $valid_optionset && ( $adv_optionset->option_type == 'combo' || $adv_optionset->option_type == 'swatch' || $adv_optionset->option_type == 'radio' ) ) {
												$valid_optionset = $adv_optionset;
											}
										}
										if ( $valid_optionset ) {
											$optionitems = $this->product->get_advanced_optionitems( $valid_optionset->option_id );
											foreach( $optionitems as $optionitem ) {
												$optionitem_id_array[] = $optionitem->optionitem_id;
											}
										}
									}
								} else {
									foreach( $this->product->options->optionset1->optionset as $optionitem ){
										$optionitem_id_array[] = $optionitem->optionitem_id;
									}
								}
								$thumbnails_displayed = 0;
								for( $i=0; $i<count( $this->product->images->imageset ); $i++ ){
									if( in_array( $this->product->images->imageset[$i]->optionitem_id, $optionitem_id_array ) ){
										if( is_array( $this->product->images->imageset[$i]->product_images ) && count( $this->product->images->imageset[$i]->product_images ) > 0 ) { ?>
											<div class="ec_details_large_popup_thumbnails ec_details_large_popup_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $thumbnails_displayed > 0 ){ ?> ec_inactive<?php }?><?php if( count( $this->product->images->imageset[$i]->product_images ) <= 1 ){ ?> ec_no_thumbnails<?php }?>" id="ec_details_large_popup_thumbnails_<?php echo esc_attr( $this->product->images->imageset[$i]->optionitem_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( count( $this->product->images->imageset[$i]->product_images ) <= 1 ){ ?> style="display:none !important;"<?php }?>>
											<?php $is_first_prod_image = true;
											foreach( $this->product->images->imageset[$i]->product_images as $product_image_id ) {
												if( 'image1' == $product_image_id ) {
													echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
														echo '<img src="';
														if ( substr( $this->product->images->imageset[$i]->image1, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image1, 0, 8 ) == 'https://' ){
															echo esc_attr( $this->product->images->imageset[$i]->image1 );
														} else {
															echo esc_attr( plugins_url( "/wp-easycart-data/products/pics1/" . $this->product->images->imageset[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) );
														}
														echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
													echo '</div>';
													$is_first_prod_image = false;
												} else if( 'image2' == $product_image_id ) {
													echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
														echo '<img src="';
														if ( substr( $this->product->images->imageset[$i]->image2, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image2, 0, 8 ) == 'https://' ){
															echo esc_attr( $this->product->images->imageset[$i]->image2 );
														} else {
															echo esc_attr( plugins_url( "/wp-easycart-data/products/pics2/" . $this->product->images->imageset[$i]->image2, EC_PLUGIN_DATA_DIRECTORY ) );
														}
														echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
													echo '</div>';
													$is_first_prod_image = false;
												} else if( 'image3' == $product_image_id ) {
													echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
														echo '<img src="';
														if ( substr( $this->product->images->imageset[$i]->image3, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image3, 0, 8 ) == 'https://' ){
															echo esc_attr( $this->product->images->imageset[$i]->image3 );
														} else {
															echo esc_attr( plugins_url( "/wp-easycart-data/products/pics3/" . $this->product->images->imageset[$i]->image3, EC_PLUGIN_DATA_DIRECTORY ) );
														}
														echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
													echo '</div>';
													$is_first_prod_image = false;
												} else if( 'image4' == $product_image_id ) {
													echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
														echo '<img src="';
														if ( substr( $this->product->images->imageset[$i]->image4, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image4, 0, 8 ) == 'https://' ){
															echo esc_attr( $this->product->images->imageset[$i]->image4 );
														} else {
															echo esc_attr( plugins_url( "/wp-easycart-data/products/pics4/" . $this->product->images->imageset[$i]->image4, EC_PLUGIN_DATA_DIRECTORY ) );
														}
														echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
													echo '</div>';
													$is_first_prod_image = false;
												} else if( 'image5' == $product_image_id ) {
													echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
														echo '<img src="';
														if ( substr( $this->product->images->imageset[$i]->image5, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image5, 0, 8 ) == 'https://' ){
															echo esc_attr( $this->product->images->imageset[$i]->image5 );
														} else {
															echo esc_attr( plugins_url( "/wp-easycart-data/products/pics5/" . $this->product->images->imageset[$i]->image5, EC_PLUGIN_DATA_DIRECTORY ) );
														}
														echo '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
													echo '</div>';
													$is_first_prod_image = false;
												} else if( 'image:' == substr( $product_image_id, 0, 6 ) ) {
													echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
														echo '<img src="' . esc_attr( apply_filters('wp_easycart_product_details_popup_thumbnail_image_url_type', substr( $product_image_id, 6, strlen( $product_image_id ) - 6 ) ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
													echo '</div>';
													$is_first_prod_image = false;
												} else if( 'video:' == substr( $product_image_id, 0, 6 ) ) {
													$video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
													$video_arr = explode( ':::', $video_str );
													if ( count( $video_arr ) >= 2 ) {
														echo '<div class="ec_details_large_popup_thumbnail ec_details_thumbnail_video videoType" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
															echo '<a href="' . esc_attr( $video_arr[0] ) . '" class="ec_details_video_thumb">';
																echo '<img src="' . esc_attr( $video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
																echo '<div class="wp-easycart-video-cover"></div>';
																echo '<div class="dashicons dashicons-controls-play"></div>';
															echo '</a>';
														echo '</div>';
														$is_first_prod_image = false;
													}
												} else if( 'youtube:' == substr( $product_image_id, 0, 8 ) ) {
													$youtube_video_str = substr( $product_image_id, 8, strlen( $product_image_id ) - 8 );
													$youtube_video_arr = explode( ':::', $youtube_video_str );
													if ( count( $youtube_video_arr ) >= 2 ) {
														echo '<div class="ec_details_large_popup_thumbnail ec_details_thumbnail_video" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
															echo '<a href="' . esc_attr( $youtube_video_arr[0] ) . '" class="ec_details_youtube_thumb">';
																echo '<img src="' . esc_attr( $youtube_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
																echo '<div class="wp-easycart-video-cover"></div>';
																echo '<div class="dashicons dashicons-controls-play"></div>';
															echo '</a>';
														echo '</div>';
														$is_first_prod_image = false;
													}
												} else if( 'vimeo:' == substr( $product_image_id, 0, 6 ) ) {
													$vimeo_video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
													$vimeo_video_arr = explode( ':::', $vimeo_video_str );
													if ( count( $vimeo_video_arr ) >= 2 ) {
														echo '<div class="ec_details_large_popup_thumbnail ec_details_thumbnail_video" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
															echo '<a href="' . esc_attr( $vimeo_video_arr[0] ) . '" class="ec_details_vimeo_thumb">';
																echo '<img src="' . esc_attr( $vimeo_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
																echo '<div class="wp-easycart-video-cover"></div>';
																echo '<div class="dashicons dashicons-controls-play"></div>';
															echo '</a>';
														echo '</div>';
														$is_first_prod_image = false;
													}
												} else {
													$product_image_media = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_thumbnail_size', 'medium' ) );
													$product_image_media_large = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
													if( $product_image_media && isset( $product_image_media[0] ) ) {
														echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
															echo '<img src="' . esc_attr( $product_image_media[0] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '"' . ( ( $product_image_media_large && isset( $product_image_media_large[0] ) ) ? ' data-large-src="' . esc_attr( $product_image_media_large[0] ) . '"': '' ) . ' />';
														echo '</div>';
														$is_first_prod_image = false;
													}
												}
											} ?>
											</div>
										<?php } else { ?>
											<div class="ec_details_large_popup_thumbnails ec_details_large_popup_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $thumbnails_displayed > 0 ){ ?> ec_inactive<?php }?>" id="ec_details_large_popup_thumbnails_<?php echo esc_attr( $this->product->images->imageset[$i]->optionitem_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( trim( $this->product->images->imageset[$i]->image2 ) == "" ){ ?> style="display:none;"<?php }?>>
												<div class="ec_details_large_popup_thumbnail ec_active" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( ( substr( $this->product->images->imageset[$i]->image1, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image1, 0, 8 ) == 'https://' ) ? $this->product->images->imageset[$i]->image1 : plugins_url( "/wp-easycart-data/products/pics1/" . $this->product->images->imageset[$i]->image1, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
												<?php if( trim( $this->product->images->imageset[$i]->image2 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( ( substr( $this->product->images->imageset[$i]->image2, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image2, 0, 8 ) == 'https://' ) ? $this->product->images->imageset[$i]->image2 : plugins_url( "/wp-easycart-data/products/pics2/" . $this->product->images->imageset[$i]->image2, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
												<?php if( trim( $this->product->images->imageset[$i]->image3 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( ( substr( $this->product->images->imageset[$i]->image3, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image3, 0, 8 ) == 'https://' ) ? $this->product->images->imageset[$i]->image3 : plugins_url( "/wp-easycart-data/products/pics3/" . $this->product->images->imageset[$i]->image3, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
												<?php if( trim( $this->product->images->imageset[$i]->image4 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( ( substr( $this->product->images->imageset[$i]->image4, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image4, 0, 8 ) == 'https://' ) ? $this->product->images->imageset[$i]->image4 : plugins_url( "/wp-easycart-data/products/pics4/" . $this->product->images->imageset[$i]->image4, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
												<?php if( trim( $this->product->images->imageset[$i]->image5 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( ( substr( $this->product->images->imageset[$i]->image5, 0, 7 ) == 'http://' || substr( $this->product->images->imageset[$i]->image5, 0, 8 ) == 'https://' ) ? $this->product->images->imageset[$i]->image5 : plugins_url( "/wp-easycart-data/products/pics5/" . $this->product->images->imageset[$i]->image5, EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
											</div>
									<?php }
										$thumbnails_displayed++;
									}
								}
								?>
							<?php } else if( count( $this->product->images->product_images ) > 0 ) {
								if( count( $this->product->images->product_images ) > 1 ) {
									echo '<div class="ec_details_large_popup_thumbnails ec_details_large_popup_thumbnails_' . esc_attr( $this->product->product_id ) . '_' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
									$is_first_prod_image = true;
									foreach( $this->product->images->product_images as $product_image_id ) {
										if( 'image1' == $product_image_id ) {
											echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
												echo '<img src="' . esc_attr( $this->product->get_first_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
											echo '</div>';
											$is_first_prod_image = false;
										} else if( 'image2' == $product_image_id ) {
											echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
												echo '<img src="' . esc_attr( $this->product->get_second_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
											echo '</div>';
											$is_first_prod_image = false;
										} else if( 'image3' == $product_image_id ) {
											echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
												echo '<img src="' . esc_attr( $this->product->get_third_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
											echo '</div>';
											$is_first_prod_image = false;
										} else if( 'image4' == $product_image_id ) {
											echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
												echo '<img src="' . esc_attr( $this->product->get_fourth_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
											echo '</div>';
											$is_first_prod_image = false;
										} else if( 'image5' == $product_image_id ) {
											echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
												echo '<img src="' . esc_attr( $this->product->get_fifth_image_url( ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
											echo '</div>';
											$is_first_prod_image = false;
										} else if( 'image:' == substr( $product_image_id, 0, 6 ) ) {
											echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
												echo '<img src="' . esc_attr( apply_filters('wp_easycart_product_details_thumbnail_large_image_url_type', substr( $product_image_id, 6, strlen( $product_image_id ) - 6 ) ) ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
											echo '</div>';
											$is_first_prod_image = false;
										} else if( 'video:' == substr( $product_image_id, 0, 6 ) ) {
											$video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
											$video_arr = explode( ':::', $video_str );
											if ( count( $video_arr ) >= 2 ) {
												echo '<div class="ec_details_large_popup_thumbnail ec_details_thumbnail_video videoType" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
													echo '<a href="' . esc_attr( $video_arr[0] ) . '" class="ec_details_video_thumb">';
														echo '<img src="' . esc_attr( $video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
														echo '<div class="wp-easycart-video-cover"></div>';
														echo '<div class="dashicons dashicons-controls-play"></div>';
													echo '</a>';
												echo '</div>';
												$is_first_prod_image = false;
											}
											$is_first_prod_image = false;
										} else if( 'youtube:' == substr( $product_image_id, 0, 8 ) ) {
											$youtube_video_str = substr( $product_image_id, 8, strlen( $product_image_id ) - 8 );
											$youtube_video_arr = explode( ':::', $youtube_video_str );
											if ( count( $youtube_video_arr ) >= 2 ) {
												echo '<div class="ec_details_large_popup_thumbnail ec_details_thumbnail_video" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
													echo '<a href="' . esc_attr( $youtube_video_arr[0] ) . '" class="ec_details_youtube_thumb">';
														echo '<img src="' . esc_attr( $youtube_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
														echo '<div class="wp-easycart-video-cover"></div>';
														echo '<div class="dashicons dashicons-controls-play"></div>';
													echo '</a>';
												echo '</div>';
												$is_first_prod_image = false;
											}
										} else if( 'vimeo:' == substr( $product_image_id, 0, 6 ) ) {
											$vimeo_video_str = substr( $product_image_id, 6, strlen( $product_image_id ) - 6 );
											$vimeo_video_arr = explode( ':::', $vimeo_video_str );
											if ( count( $vimeo_video_arr ) >= 2 ) {
												echo '<div class="ec_details_large_popup_thumbnail ec_details_thumbnail_video" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
													echo '<a href="' . esc_attr( $vimeo_video_arr[0] ) . '" class="ec_details_vimeo_thumb">';
														echo '<img src="' . esc_attr( $vimeo_video_arr[1] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '" />';
														echo '<div class="wp-easycart-video-cover"></div>';
														echo '<div class="dashicons dashicons-controls-play"></div>';
													echo '</a>';
												echo '</div>';
												$is_first_prod_image = false;
											}
										} else {
											$product_image_media = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_thumbnail_size', 'medium' ) );
											$product_image_media_large = wp_get_attachment_image_src( $product_image_id, apply_filters( 'wp_easycart_product_details_full_size', 'large' ) );
											if( $product_image_media && isset( $product_image_media[0] ) ) {
												echo '<div class="ec_details_large_popup_thumbnail" data-product-id="' . esc_attr( $this->product->product_id ) . '" data-rand-id="' . esc_attr( $wpeasycart_addtocart_shortcode_rand ) . '">';
													echo '<img src="' . esc_attr( $product_image_media[0] ) . '" alt="' . esc_attr( strip_tags( stripslashes( $this->product->title ) ) ) . '"' . ( ( $product_image_media_large && isset( $product_image_media_large[0] ) ) ? ' data-large-src="' . esc_attr( $product_image_media_large[0] ) . '"': '' ) . ' />';
												echo '</div>';
												$is_first_prod_image = false;
											}
										}
									}
									echo '</div>';
								}
							} else if( trim( $this->product->images->image2 ) != "" ) { ?>
							<div class="ec_details_large_popup_thumbnails ec_details_large_popup_thumbnails_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
								<div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_first_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
								<div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_second_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div>
								<?php if( trim( $this->product->images->image3 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_third_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
								<?php if( trim( $this->product->images->image4 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_fourth_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
								<?php if( trim( $this->product->images->image5 ) != "" ){ ?><div class="ec_details_large_popup_thumbnail" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><img src="<?php echo esc_attr( $this->product->get_fifth_image_url( ) ); ?>" alt="<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>" /></div><?php } ?>
							</div>
							<?php }?>
							<?php /* END POPUP THUMBNAIL SETUP */ ?>
							<div class="ec_details_large_popup_close"><input type="button" onclick="ec_details_hide_large_popup( '<?php echo esc_attr( $this->product->model_number ); ?>' );" value="x"></div>
						</div>
					</div>
				</div>
			</div>
			<?php }?>
			<?php /* END PRODUCT IMAGE POPUP AREA */ ?>
			<?php } // Close image show filter ?>
			<?php do_action( 'wp_easycart_product_details_after_left_content_area', $this->product->product_id ); ?>
		</div>
		<?php /* END PRODUCT IMAGES AREA */ ?>

		<div class="ec_details_right <?php if( isset( $this->atts['details_sizing'] ) ){ echo 'ec_details_right-' . esc_attr( (int) $this->atts['details_sizing'] ); }else{ echo ( get_option( 'ec_option_product_details_sizing' ) && get_option( 'ec_option_product_details_sizing' ) != '' ) ? 'ec_details_right-' . esc_attr( (int) get_option( 'ec_option_product_details_sizing' ) ) : ''; } ?>">
			<?php if( $this->product->inquiry_url == "" ){ // Regular Add to Cart Form ?>

			<form action="<?php echo esc_attr( $this->cart_page ); ?>" method="POST" enctype="multipart/form-data" class="ec_add_to_cart_form<?php echo esc_attr( ( ( isset( $this->atts['background_add'] ) && $this->atts['background_add'] && ! $this->product->is_subscription_item ) ? ' ec_add_to_cart_form_ajax' : '' ) ); ?>">
			<?php if( $this->product->is_subscription_item ){ // && !class_exists( "ec_stripe" ) ){ ?>
			<input type="hidden" name="ec_cart_form_action" value="subscribe_v3" />
			<input type="hidden" name="ec_cart_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-subscribe-' . $this->product->product_id ) ); ?>" />
			<?php }else{ ?>
			<input type="hidden" name="ec_cart_form_action" value="add_to_cart_v3" />
			<input type="hidden" name="ec_cart_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-add-to-cart-' . $this->product->product_id ) ); ?>" />
			<?php }?>
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $this->product->product_id ); ?>"  />

			<?php }else{ // Custom Inquiry Form ?>

			<form action="<?php echo esc_attr( $this->product->inquiry_url ); ?>" method="GET" enctype="multipart/form-data" class="ec_add_to_cart_form">
			<input type="hidden" name="model_number" value="<?php echo esc_attr( $this->product->model_number ); ?>"  />

			<?php }?>

			<?php /* START TOP PRODUCT DATA */ ?>
			<?php if( ( isset( $this->atts['show_breadcrumbs'] ) && $this->atts['show_breadcrumbs'] ) || ( ! isset( $this->atts['show_breadcrumbs'] ) && get_option( 'ec_option_show_breadcrumbs' ) ) ){ ?>
			<h4 class="ec_details_breadcrumbs ec_small" id="ec_breadcrumbs_type2">
			<a href="<?php echo esc_attr( home_url( ) ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_home_link' ); ?></a> / 
			<a href="<?php echo esc_attr( $this->store_page ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_store_link' ); ?></a> 
			<?php if( $this->product->menuitems[0]->menulevel1_1_name ){ ?> / 
			<a href="<?php if( !get_option( 'ec_option_use_old_linking_style' ) && $this->product->post_id != "0" ){ 
				echo esc_attr( get_permalink( $this->product->menuitems[0]->menulevel1_1_post_id ) ); 
			}else{ 
				echo esc_attr( $this->store_page . $this->permalink_divider . "menuid=" . $this->product->menuitems[0]->menulevel1_1_menu_id );
			} ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->menuitems[0]->menulevel1_1_name ); ?></a>
			<?php if( $this->product->menuitems[0]->menulevel2_1_name ){ ?> / 
			<a href="<?php if( !get_option( 'ec_option_use_old_linking_style' ) && $this->product->post_id != "0" ){ 
				echo esc_attr( get_permalink( $this->product->menuitems[0]->menulevel2_1_post_id ) );
			}else{ 
				echo esc_attr( $this->store_page . $this->permalink_divider . "submenuid=" . $this->product->menuitems[0]->menulevel2_1_menu_id );
			} ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->menuitems[0]->menulevel2_1_name ); ?></a>
			<?php if( $this->product->menuitems[0]->menulevel3_1_name ){ ?> / 
			<a href="<?php if( !get_option( 'ec_option_use_old_linking_style' ) && $this->product->post_id != "0" ){ 
				echo esc_attr( get_permalink( $this->product->menuitems[0]->menulevel3_1_post_id ) );
			}else{
				echo esc_attr( $this->store_page . $this->permalink_divider . "subsubmenuid=" . $this->product->menuitems[0]->menulevel3_1_menu_id ); 
			} ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->menuitems[0]->menulevel3_1_name ); ?></a><?php } } }?>
			</h4>
			<?php }?>
			<?php if( ( isset( $this->atts['show_title'] ) && $this->atts['show_title'] ) || ( ! isset( $this->atts['show_title'] ) ) ) { ?>
			<h1 class="ec_details_title ec_details_title_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" style="<?php echo ( isset( $this->atts['title_font'] ) ) ? 'font-family:' . esc_attr( $this->atts['title_font'] ) . ' !important;' : ''; ?><?php echo ( isset( $this->atts['title_color'] ) ) ? 'color:' . esc_attr( $this->atts['title_color'] ) . ' !important;' : ''; ?>"><?php echo wp_easycart_escape_html( $this->product->title ); ?></h1>
			<?php } ?>
			<?php do_action( 'wp_easycart_product_details_after_title', $this->product->product_id ); ?>
			<?php if( ( isset( $this->atts['show_title'] ) && $this->atts['show_title'] ) || ( ! isset( $this->atts['show_title'] ) ) ) { ?>
			<div class="ec_title_divider" style="<?php echo ( isset( $this->atts['title_divider_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['title_divider_color'] ) . ' !important;': ''; ?>"></div>
			<?php } ?>
			<?php do_action( 'wp_easycart_product_details_after_title_divider', $this->product->product_id ); ?>
			<?php if( ( isset( $this->atts['show_price'] ) && $this->atts['show_price'] ) || ( ! isset( $this->atts['show_price'] ) ) ) { ?>
			<?php if ( $this->product->login_for_pricing && !$this->product->is_login_for_pricing_valid( ) ){
				// No Pricing

			}else if( ( $this->product->is_catalog_mode && get_option( 'ec_option_hide_price_seasonal' ) ) || 
					  ( $this->product->is_inquiry_mode && get_option( 'ec_option_hide_price_inquiry' ) ) ){ // NO PRICE SHOWN

			}else if( $this->product->vat_rate > 0  && get_option( 'ec_option_show_multiple_vat_pricing' ) ){ ?>

			<?php if( get_option( 'ec_option_show_multiple_vat_pricing' ) == '1' ){ ?>
			<div class="ec_details_price ec_details_no_vat_price"><?php $this->product->display_product_pricing_no_vat( 
				( isset( $this->atts['price_font'] ) ) ? $this->atts['price_font'] : false,
				( isset( $this->atts['price_color'] ) ) ? $this->atts['price_color'] : false,
				( isset( $this->atts['list_price_font'] ) ) ? $this->atts['list_price_font'] : false,
				( isset( $this->atts['list_price_color'] ) ) ? $this->atts['list_price_color'] : false,
				$wpeasycart_addtocart_shortcode_rand
			); ?></div>
			<?php }?>
			<div class="ec_details_price ec_details_vat_price"><?php $this->product->display_product_pricing_vat( 
				( isset( $this->atts['price_font'] ) ) ? $this->atts['price_font'] : false,
				( isset( $this->atts['price_color'] ) ) ? $this->atts['price_color'] : false,
				( isset( $this->atts['list_price_font'] ) ) ? $this->atts['list_price_font'] : false,
				( isset( $this->atts['list_price_color'] ) ) ? $this->atts['list_price_color'] : false,
				$wpeasycart_addtocart_shortcode_rand
			); ?></div>

			<?php }else{ ?>
			<div class="ec_details_price ec_details_single_price"><?php $this->product->display_product_list_price( 
				( isset( $this->atts['list_price_font'] ) ) ? $this->atts['list_price_font'] : false,
				( isset( $this->atts['list_price_color'] ) ) ? $this->atts['list_price_color'] : false
			); ?><?php if ( $this->product->replace_price_label && in_array( $this->product->enable_price_label, array( 2, 4, 6, 7 ) ) ) { ?>
					<span class="ec_product_price"><?php echo wp_easycart_escape_html( $this->product->custom_price_label ); ?></span>
				<?php } else {
					$this->product->display_price( 
						( isset( $this->atts['price_font'] ) ) ? $this->atts['price_font'] : false,
						( isset( $this->atts['price_color'] ) ) ? $this->atts['price_color'] : false,
						$wpeasycart_addtocart_shortcode_rand
					);
				} ?><?php if ( ! $this->product->replace_price_label && in_array( $this->product->enable_price_label, array( 2, 4, 6, 7 ) ) ) {
				?><span class="ec_details_price_label"><?php echo wp_easycart_escape_html( $this->product->custom_price_label ); ?></span><?php
			} ?></div>
			<?php }?>
			<?php }?>
			<?php if( ( isset( $this->atts['show_customer_reviews'] ) && $this->atts['show_customer_reviews'] ) || ( ! isset( $this->atts['show_customer_reviews'] ) && $this->product->use_customer_reviews ) ){ ?>
			<div class="ec_details_rating">
				<?php $rating = $this->product->get_rating( ); ?>
				<div class="ec_product_details_star_<?php if( $rating > 0.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
				<div class="ec_product_details_star_<?php if( $rating > 1.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
				<div class="ec_product_details_star_<?php if( $rating > 2.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
				<div class="ec_product_details_star_<?php if( $rating > 3.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
				<div class="ec_product_details_star_<?php if( $rating > 4.49 ){ ?>on<?php }else{ ?>off<?php }?>"></div>
			</div>
			<?php }?>
			<?php if( ( isset( $this->atts['show_model_number'] ) && $this->atts['show_model_number'] ) || ( ! isset( $this->atts['show_model_number'] ) && get_option( 'ec_option_show_model_number' ) ) ){ ?>
			<div class="ec_details_model_number"><?php echo esc_attr( ucwords( wp_easycart_language( )->get_text( 'product_details', 'product_details_model_number' ) ) ); ?>: <span class="ec_details_model_number_sku_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo esc_attr( $this->product->model_number ); ?></span></div>
			<?php }?>
			<?php if( ! get_option( 'ec_option_short_description_below' ) && ( ( isset( $this->atts['show_short_description'] ) && $this->atts['show_short_description'] ) || ( ! isset( $this->atts['show_short_description'] ) && isset( $this->product->short_description ) && strlen( trim( $this->product->short_description ) ) > 0 ) ) ) {?>
			<div class="ec_details_description"><?php echo wp_easycart_escape_html( nl2br( stripslashes( $this->product->short_description ) ) ); ?></div>
			<?php }?>
			<?php do_action( 'wp_easycart_product_details_after_description', $this->product->product_id ); ?>
			<div class="ec_details_options_divider_pre"></div>
			<?php /* GIFT CARD OPTIONS */ ?>
			<?php if( $this->product->is_giftcard ){ ?>
			<div class="ec_details_options ec_details_options_gift_card" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<div class="ec_details_option_row_error ec_giftcard_error" id="ec_details_giftcard_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'ec_errors', 'missing_gift_card_options' ); ?></div>
				<div class="ec_details_option_row">
					<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?></div>
					<div class="ec_details_option_data"><input type="text" name="ec_giftcard_to_name" id="ec_giftcard_to_name_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="" /></div>
				</div>

				<div class="ec_details_option_row">
					<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?></div>
					<div class="ec_details_option_data"><input type="text" name="ec_giftcard_to_email" id="ec_giftcard_to_email_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="" /></div>
				</div>

				<div class="ec_details_option_row">
					<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?></div>
					<div class="ec_details_option_data"><input type="text" name="ec_giftcard_from_name" id="ec_giftcard_from_name_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="" /></div>
				</div>

				<div class="ec_details_option_row">
					<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?></div>
					<div class="ec_details_option_data"><textarea name="ec_giftcard_message" id="ec_giftcard_message_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></textarea></div>
				</div>
			</div>
			<?php }?>
			<?php /* END GIFT CARD OPTIONS */ ?>

			<?php /* DONATION OPTIONS */ ?>
			<?php if( $this->product->is_donation ){ ?>
			<div class="ec_details_options ec_details_options_donation" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<div class="ec_details_option_row_error ec_donation_error" id="ec_details_donation_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_donation_error' ) . " " . esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->price ) ); ?>.</div>
				<div class="ec_details_option_row">
					<div class="ec_details_option_label"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_donation_amount' ); ?></div>
					<div class="ec_details_option_data"><input type="number" step=".01" min="<?php echo esc_attr( $GLOBALS['currency']->get_number_only( $this->product->price ) ); ?>" class="ec_donation_amount" name="ec_donation_amount" id="ec_donation_amount_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_number_only( $this->product->price ) ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-use-advanced-optionset="<?php echo ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ? '1' : '0'; ?>" /></div>
				</div>
			</div>
			<?php } ?>
			<?php /* END DONATION OPTIONS */ ?>

			<?php if ( $this->product->login_for_pricing && !$this->product->is_login_for_pricing_valid( ) ){
					// No Pricing
			}else if( !$this->product->using_role_price && isset( $this->product->pricetiers[0] ) && count( $this->product->pricetiers[0] ) > 1 ){ ?>
			<ul class="ec_details_tiers">
				<?php 
				for( $pricetier_i=0; $pricetier_i < count( $this->product->pricetiers ); $pricetier_i++ ){

					$tier_price = $GLOBALS['currency']->get_currency_display( $this->product->pricetiers[$pricetier_i][0] );

					$tier_quantity_start = (int) $this->product->pricetiers[$pricetier_i][1];
					if( $pricetier_i + 1 < count( $this->product->pricetiers ) )
						$tier_quantity_end = (int) $this->product->pricetiers[$pricetier_i+1][1] - 1;
					else
						$tier_quantity_end = -1;

					if( $tier_quantity_end == -1 ){
						$tier_quantity_text = $tier_quantity_start . " " . wp_easycart_language( )->get_text( 'product_details', 'product_details_tier_or_more' );
					}else if( $tier_quantity_start == $tier_quantity_end  ){
						$tier_quantity_text = $tier_quantity_start;
					}else{
						$tier_quantity_text = $tier_quantity_start . "-" . $tier_quantity_end;
					}
					?>
				<li><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_tier_buy' ); ?> <?php echo esc_attr( $tier_quantity_text ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_tier_buy_at' ); ?> <strong><?php echo esc_attr( $tier_price ); ?></strong> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_tier_each' ); ?></li>
				<?php }?>
			</ul>
			<?php }
			/* END TOP PRODUCT DATA */
			?>

			<?php /* PRODUCT BASIC OPTIONS */ 
			if( !$this->product->is_inquiry_mode || $this->product->inquiry_url != ""){
			$has_quantity_grid = false;
			?>
			<?php if( $this->product->has_options && ( ! $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ){ ?>
			<div class="ec_details_options ec_details_options_basic" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
			<?php 
			$optionsets = array( $this->product->options->optionset1, $this->product->options->optionset2, $this->product->options->optionset3, $this->product->options->optionset4, $this->product->options->optionset5 );

			for( $i=0; $i<5; $i++ ){ ?>

				<?php
				/* START IMAGE SWATCHES AREA */
					if( count( $optionsets[$i]->optionset ) > 0 && $optionsets[$i]->option_type == 'basic-swatch' && $optionsets[$i]->optionset[0]->optionitem_icon != "" ){ ?>

					<div class="ec_details_option_row_error ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" id="ec_details_option_row_error_<?php echo esc_attr( $optionsets[$i]->option_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ); ?> <?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>
					<input type="hidden" name="ec_option<?php echo esc_attr( $i+1 ); ?>" id="ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="0" />
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?><span class="ec_details_option_label_selected"></span></div>
						<ul class="ec_details_swatches ec_details_swatches_<?php echo esc_attr( ( ( isset( $optionsets[$i]->option_meta['swatch_size'] ) ) ? (int) $optionsets[$i]->option_meta['swatch_size'] : 30 ) ); ?>">
						<?php
						for( $j=0; $j<count( $optionsets[$i]->optionset ); $j++ ){
							// Check the in stock status for this option item
							if( $this->product->allow_backorders ){
								$optionitem_in_stock = true;
							}else if( $this->product->use_optionitem_quantity_tracking && ( $i > 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] <= 0 ) ){
								$optionitem_in_stock = false;
							}else{
								$optionitem_in_stock = true;
							}
							if ( $this->product->options->verify_optionitem( ( $i + 1 ), $optionsets[$i]->optionset[$j]->optionitem_id ) ) {
						?>
						<li class="ec_details_swatch <?php echo ( 0 == $i ) ? 'ec_optionitem_images' : ''; ?> ec_option<?php echo esc_attr( $i+1 ); ?> ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $optionitem_in_stock ){ ?> ec_active <?php }?><?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $optionsets[$i]->option_meta['url_var'] ) && $optionsets[$i]->option_meta['url_var'] != '' && isset( $_GET[$optionsets[$i]->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionsets[$i]->option_meta['url_var']] ) ) == strtolower( $optionsets[$i]->optionset[$j]->optionitem_name ) ) || ( isset( $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] ) && $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-id="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_id ); ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] ); ?>"<?php }?> data-optionitem-price="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price ); }else{ echo "0.00"; } ?>" data-optionitem-price-onetime="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ); }else{ echo "0.00"; } ?>" data-optionitem-price-override="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_override ); }else{ echo "-1.00"; } ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_multiplier ); ?>"><img src="<?php if( substr( $optionsets[$i]->optionset[$j]->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionsets[$i]->optionset[$j]->optionitem_icon, 0, 8 ) == 'https://' ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_icon ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/swatches/" . $optionsets[$i]->optionset[$j]->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" title="<?php 
							echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_name ); ?><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if( $optionsets[$i]->optionset[$j]->optionitem_enable_custom_price_label && ( $optionsets[$i]->optionset[$j]->optionitem_price != 0 || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price ) && $optionsets[$i]->optionset[$j]->optionitem_price != 0 ) || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != 0 ) ) ) {
								?> (<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_custom_price_label ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override > -1 ){
								?> (<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_override ) ); ?>)<?php
							} ?>" /></li>
						<?php }
						}
						?>
						</ul>
						<div class="ec_option_loading" id="ec_option_loading_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_loading_options' ); ?></div>
					</div>
					<?php

					// HTML Swatches
					}else if( count( $optionsets[$i]->optionset ) > 0 && $optionsets[$i]->option_type == 'basic-swatch' ){ ?>

					<div class="ec_details_option_row_error ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" id="ec_details_option_row_error_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ); ?> <?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>
					<input type="hidden" name="ec_option<?php echo esc_attr( $i+1 ); ?>" id="ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="0" />
					<div class="ec_details_option_row">
						<div class="ec_details_option_label"><?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?><span class="ec_details_option_label_selected"></span></div>
						<ul class="ec_details_swatches ec_details_html_swatches ec_details_swatches_<?php echo esc_attr( ( ( isset( $optionsets[$i]->option_meta['swatch_size'] ) ) ? (int) $optionsets[$i]->option_meta['swatch_size'] : 30 ) ); ?>">
						<?php
						for( $j=0; $j<count( $optionsets[$i]->optionset ); $j++ ){
							// Check the in stock status for this option item
							if( $this->product->allow_backorders ){
								$optionitem_in_stock = true;
							}else if( $this->product->use_optionitem_quantity_tracking && ( $i > 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] <= 0 ) ){
								$optionitem_in_stock = false;
							}else{
								$optionitem_in_stock = true;
							}
							if ( $this->product->options->verify_optionitem( ( $i + 1 ), $optionsets[$i]->optionset[$j]->optionitem_id ) ) {
						?>
						<li class="ec_details_swatch wpeasycart-html-swatch <?php echo ( 0 == $i ) ? 'ec_optionitem_images' : ''; ?> ec_option<?php echo esc_attr( $i+1 ); ?> ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?><?php if( $optionitem_in_stock ){ ?> ec_active <?php }?><?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $optionsets[$i]->option_meta['url_var'] ) && $optionsets[$i]->option_meta['url_var'] != '' && isset( $_GET[$optionsets[$i]->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionsets[$i]->option_meta['url_var']] ) ) == strtolower( $optionsets[$i]->optionset[$j]->optionitem_name ) ) || ( isset( $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] ) && $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-id="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_id ); ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] ); ?>"<?php }?> data-optionitem-price="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price ); }else{ echo "0.00"; } ?>" data-optionitem-price-onetime="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ); }else{ echo "0.00"; } ?>" data-optionitem-price-override="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_override ); }else{ echo "-1.00"; } ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_multiplier ); ?>" title="<?php 
							echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_name ); ?><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if( $optionsets[$i]->optionset[$j]->optionitem_enable_custom_price_label && ( $optionsets[$i]->optionset[$j]->optionitem_price != 0 || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price ) && $optionsets[$i]->optionset[$j]->optionitem_price != 0 ) || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != 0 ) ) ) {
								?> <?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_custom_price_label ); ?><?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override > -1 ){
								?> (<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_override ) ); ?>)<?php
							} ?>"><?php echo wp_easycart_escape_html( $optionsets[$i]->optionset[$j]->optionitem_name ); ?></li>
						<?php }
						}
						?>
						</ul>
						<div class="ec_option_loading" id="ec_option_loading_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_loading_options' ); ?></div>
					</div>
					<?php
					/* END SWATCHES AREA */

				/* START COMBO BOX AREA */
				}else if( count( $optionsets[$i]->optionset ) > 0 && $optionsets[$i]->optionset[0]->optionitem_name != "" ){ ?>
				<div class="ec_details_option_row_error ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" id="ec_details_option_row_error_<?php echo esc_attr( $optionsets[$i]->option_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ); ?> <?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></div>

				<div class="ec_details_option_row">
					<select name="ec_option<?php echo esc_attr( $i+1 ); ?>" id="ec_option<?php echo esc_attr( $i+1 ); ?>" class="ec_details_combo ec_option<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_option<?php echo esc_attr( $i+1 ); ?><?php if( $this->product->use_optionitem_quantity_tracking && $i > 0 ){ ?> ec_inactive<?php }?> <?php echo ( 0 == $i ) ? 'ec_optionitem_images' : ''; ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i > 0 ){ ?> disabled="disabled"<?php }?>>
					<option value="0"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->stock_quantity ); ?>"<?php }?> data-optionitem-price="0.00" data-optionitem-price-onetime="0.00" data-optionitem-price-override="-1" data-optionitem-price-multiplier="-1.00"><?php echo wp_easycart_escape_html( $optionsets[$i]->option_label ); ?></option>
					<?php
					for( $j=0; $j<count( $optionsets[$i]->optionset ); $j++ ){
						// Check the in stock status for this option item
						if( $this->product->allow_backorders ){
							$optionitem_in_stock = true;
						}else if( $this->product->use_optionitem_quantity_tracking && ( $i > 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] <= 0 ) ){
							$optionitem_in_stock = false;
						}else{
							$optionitem_in_stock = true;
						}
						if ( $this->product->options->verify_optionitem( ( $i + 1 ), $optionsets[$i]->optionset[$j]->optionitem_id ) ) {
					?>
					<?php if( !$this->product->use_optionitem_quantity_tracking || $i != 0 || $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] > 0 || $optionitem_in_stock ){ ?> 
					<option value="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_id ); ?>"<?php if( $this->product->use_optionitem_quantity_tracking && $i == 0 ){ ?> data-optionitem-quantity="<?php echo esc_attr( $this->product->option1quantity[$optionsets[$i]->optionset[$j]->optionitem_id] ); ?>"<?php }?> data-optionitem-price="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price ); }else{ echo "0.00"; } ?>" data-optionitem-price-onetime="<?php if( $optionsets[$i]->optionset[$j]->optionitem_price_onetime != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ); }else{ echo "0.00"; } ?>" data-optionitem-price-override="<?php if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override != "" ){ echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_override ); }else{ echo "-1.00"; } ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_price_multiplier ); ?>"<?php if( $optionsets[$i]->optionset[$j]->optionitem_initially_selected || ( isset( $optionsets[$i]->option_meta['url_var'] ) && $optionsets[$i]->option_meta['url_var'] != '' && isset( $_GET[$optionsets[$i]->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionsets[$i]->option_meta['url_var']] ) ) == strtolower( $optionsets[$i]->optionset[$j]->optionitem_name ) ) || ( isset( $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] ) && $_GET['o'.$optionsets[$i]->optionset[$j]->option_id] == $optionsets[$i]->optionset[$j]->optionitem_name ) ){ ?> selected="selected"<?php }?>><?php echo wp_easycart_escape_html( $optionsets[$i]->optionset[$j]->optionitem_name ); ?> <?php 
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if( $optionsets[$i]->optionset[$j]->optionitem_enable_custom_price_label && ( $optionsets[$i]->optionset[$j]->optionitem_price != 0 || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price ) && $optionsets[$i]->optionset[$j]->optionitem_price != 0 ) || ( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime != 0 ) ) ) {
								?> <?php echo esc_attr( $optionsets[$i]->optionset[$j]->optionitem_custom_price_label ); ?><?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( $optionsets[$i]->optionset[$j]->optionitem_price < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime > 0 ){
								?> (+<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) && $optionsets[$i]->optionset[$j]->optionitem_price_onetime < 0 ){
								?> (<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?>)<?php
							} else if( isset( $optionsets[$i]->optionset[$j]->optionitem_price_override ) && $optionsets[$i]->optionset[$j]->optionitem_price_override > -1 ){
								?> (<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionsets[$i]->optionset[$j]->optionitem_price_override ) ); ?>)<?php
							} ?></option>
					<?php }?>
					<?php }
					}
					?>
					</select>
					<div class="ec_option_loading" id="ec_option_loading_<?php echo esc_attr( $i+1 ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_loading_options' ); ?></div>
				</div>
				<?php
				}
				/* END COMBO BOX AREA*/
			}
			?>
			</div>
			<?php } ?>
			<?php /* END BASIC OPTIONS */ ?>

			<?php /* PRODUCT ADVANCED OPTIONS */ ?>
			<?php 

			$add_price_grid = 0;
			$add_order_price_grid = 0;
			$override_price_grid = -1;
			if( ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) && count( $this->product->advanced_optionsets ) > 0 ){ ?>
			<div class="ec_details_options ec_details_options_advanced" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<?php
				$first_optionitem_image_type = true;
				foreach( $this->product->advanced_optionsets as $optionset ){
					$optionitems = $this->product->get_advanced_optionitems( $optionset->option_id );
				?>
				<?php 
				if( $optionset->option_required ){ 
				?>
				<div class="ec_details_option_row_error" id="ec_details_adv_option_row_error_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo ( '' != $optionset->option_error_text ) ? wp_easycart_escape_html( $optionset->option_error_text ) : wp_easycart_language( )->get_text( 'product_details', 'product_details_missing_option' ) . ' ' . wp_easycart_escape_html( $optionset->option_label ); // Escaped from language class ?></div>
				<?php
				}
				?>
				<div class="ec_details_option_row ec_option_type_<?php echo esc_attr( $optionset->option_type ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-option-required="<?php echo esc_attr( $optionset->option_required ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php echo ( ! $this->product->is_option_initially_visible( $optionset ) ) ? ' style="display:none"' : ''; ?>>
					<?php if( $optionset->option_type != "combo" ){ ?>
					<div class="ec_details_option_label"><?php echo wp_easycart_escape_html( $optionset->option_label ); ?><?php if( $optionset->option_type == "swatch" ){ ?><span class="ec_details_option_label_selected"><?php foreach( $optionitems as $optionitem ) { 
						if ( $optionitem->optionitem_initially_selected ) {
							echo esc_attr( $optionitem->optionitem_name );
							break;
						}
					} ?></span><?php }?></div>
					<?php }?>
					<div class="ec_details_option_data">
					<?php
					/* START ADVANCED CHECBOX TYPE */
					if( $optionset->option_type == "checkbox" ){
					?>

						<?php
						foreach( $optionitems as $optionitem ){
						?>

							<div class="ec_details_checkbox_row"><input type="checkbox" class="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $optionitem->optionitem_id ); ?>" value="<?php echo esc_html( wp_easycart_escape_html( $optionitem->optionitem_name ) ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-id="<?php echo esc_attr( $optionitem->optionitem_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>"<?php if( $optionitem->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ) == strtolower( $optionitem->optionitem_name ) ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitem->optionitem_name ) ){ ?> checked="checked"<?php }?> /> <?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?> <?php 
								if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
									// No pricing shown in this case.
								} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
									echo '<span class="ec_product_details_option_pricing">' . esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ) . '</span>';
								} else if ( $optionitem->optionitem_price > 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price < 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price_onetime > 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price_onetime < 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
								} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')</span>';
								} ?></div>

						<?php
						}
						?>

					<?php

					/* START ADVANCED COMBO TYPE */
					}else if( $optionset->option_type == "combo" ){
					?>
						<select name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>"<?php echo ( $first_optionitem_image_type ) ? ' class="ec_optionitem_images"' : ''; ?>>
						<option value="0" data-optionitem-price="0.000" data-optionitem-price-onetime="0.000" data-optionitem-price-override="-1.000" data-optionitem-price-multiplier="-1.000"><?php echo wp_easycart_escape_html( $optionset->option_label ); ?></option>
						<?php
						foreach( $optionitems as $optionitem ){
						?>

							<option value="<?php echo esc_attr( $optionitem->optionitem_id ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>"<?php if( $optionitem->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ) == strtolower( $optionitem->optionitem_name ) ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitem->optionitem_name ) ){ ?> selected="selected"<?php }?>><?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?> <?php
								if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
									// No pricing shown in this case.
								} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
									echo '<span class="ec_product_details_option_pricing">' . esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ) . '</span>';
								} else if ( $optionitem->optionitem_price > 0 ) {
									echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
								} else if ( $optionitem->optionitem_price < 0 ) {
									echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
								} else if ( $optionitem->optionitem_price_onetime > 0 ) {
									echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
								} else if ( $optionitem->optionitem_price_onetime < 0 ) {
									echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
								} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
									echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')';
								} ?></option>

						<?php
						}
						?>
						</select>
					<?php
						$first_optionitem_image_type = false;

					/* START ADVANCED DATE TYPE*/
					}else if( $optionset->option_type == "date" ){
					?>

						<input type="text" value="<?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); } ?>" class="ec_is_datepicker" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

					<?php

					/* START ADVANCED FILE TYPE */
					}else if( $optionset->option_type == "file" ){
					?>

						<input type="file" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

					<?php

					/* START ADVANCED SWATCH TYPE */
					}else if( $optionset->option_type == "swatch" ){
						$initial_swatch_selected_val = 0; 
						?>
						<ul class="ec_details_swatches ec_details_swatches_<?php echo esc_attr( ( ( isset( $optionset->option_meta['swatch_size'] ) ) ? (int) $optionset->option_meta['swatch_size'] : 30 ) ); ?>">
							<?php
							for( $j=0; $j<count( $optionitems ); $j++ ){
								$initial_swatch_selected_val = ( $optionitems[$j]->optionitem_initially_selected ) ? $optionitems[$j]->optionitem_id : $initial_swatch_selected_val;
								if ( '' != $optionitems[$j]->optionitem_icon ) {
								?>
								<li class="ec_details_swatch ec_advanced <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?> ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_active<?php if( $optionitems[$j]->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) == $optionitems[$j]->optionitem_name ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitems[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-id="<?php echo esc_attr( $optionitems[$j]->optionitem_id ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[$j]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[$j]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[$j]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[$j]->optionitem_price_multiplier ); ?>"><img src="<?php if( substr( $optionitems[$j]->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionitems[$j]->optionitem_icon, 0, 8 ) == 'https://' ){ echo esc_attr( $optionitems[$j]->optionitem_icon ); }else{ echo esc_attr( plugins_url( "/wp-easycart-data/products/swatches/" . $optionitems[$j]->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" title="<?php echo esc_attr( $optionitems[$j]->optionitem_name ); ?> <?php 
									if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
										// No pricing shown in this case.
									} else if ( $optionitems[$j]->optionitem_enable_custom_price_label && ( $optionitems[$j]->optionitem_price != 0 || ( isset( $optionitems[$j]->optionitem_price ) && $optionitems[$j]->optionitem_price != 0 ) || ( isset( $optionitems[$j]->optionitem_price_onetime ) && $optionitems[$j]->optionitem_price_onetime != 0 ) ) ) {
										echo ' ' . esc_attr( $optionitems[$j]->optionitem_custom_price_label );
									} else if( $optionitems[$j]->optionitem_price > 0 ) {
										echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
									} else if( $optionitems[$j]->optionitem_price < 0 ) {
										echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
									} else if( $optionitems[$j]->optionitem_price_onetime > 0 ) {
										echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
									} else if( $optionitems[$j]->optionitem_price_onetime < 0 ) {
										echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
									} else if( isset( $optionitems[$j]->optionitem_price_override ) && $optionitems[$j]->optionitem_price_override > -1 ) {
										echo ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_override ) );
									} ?>" /></li>
							<?php
								} else { ?>
									<li class="ec_details_swatch wpeasycart-html-swatch ec_advanced <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?> ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_active<?php if( $optionitems[$j]->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) == $optionitems[$j]->optionitem_name ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitems[$j]->optionitem_name ) ){ ?> ec_selected<?php }?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-id="<?php echo esc_attr( $optionitems[$j]->optionitem_id ); ?>" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[$j]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[$j]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[$j]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[$j]->optionitem_price_multiplier ); ?>" title="<?php echo esc_attr( $optionitems[$j]->optionitem_name ); ?><?php 
									if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
										// No pricing shown in this case.
									} else if ( $optionitems[$j]->optionitem_enable_custom_price_label && ( $optionitems[$j]->optionitem_price != 0 || ( isset( $optionitems[$j]->optionitem_price ) && $optionitems[$j]->optionitem_price != 0 ) || ( isset( $optionitems[$j]->optionitem_price_onetime ) && $optionitems[$j]->optionitem_price_onetime != 0 ) ) ) {
										echo ' ' . esc_attr( $optionitems[$j]->optionitem_custom_price_label );
									} else if( $optionitems[$j]->optionitem_price > 0 ) {
										echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
									} else if( $optionitems[$j]->optionitem_price < 0 ) {
										echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' );
									} else if( $optionitems[$j]->optionitem_price_onetime > 0 ) {
										echo ' +' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
									} else if( $optionitems[$j]->optionitem_price_onetime < 0 ) {
										echo ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' );
									} else if( isset( $optionitems[$j]->optionitem_price_override ) && $optionitems[$j]->optionitem_price_override > -1 ) {
										echo ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[$j]->optionitem_price_override ) );
									} ?>"><?php echo wp_easycart_escape_html( $optionitems[$j]->optionitem_name ); ?></li>
								<?php }
							}
							$first_optionitem_image_type = false;
							?>
						</ul>
						<input type="hidden" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $initial_swatch_selected_val ); ?>" />
						<?php 

					/* START ADVANCED GRID TYPE */
					}else if( $optionset->option_type == "grid" ){
						$has_quantity_grid = true;
					?>

						<?php
						foreach( $optionitems as $optionitem ){

						if( $optionitem->optionitem_initial_value > 0 ){	
							if( $optionitem->optionitem_price >= 0 ){
								$add_price_grid = $add_price_grid + $optionitem->optionitem_price;

							}else if( $optionitem->optionitem_price_override >= 0 ){
								$override_price_grid = $optionitem->optionitem_price_override;

							}else if( $optionitem->optionitem_price_onetime > 0 ){
								$add_order_price_grid = $add_order_price_grid + $optionitem->optionitem_price_onetime;

							}
						}
						?>

							<div class="ec_details_grid_row"><span><?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?></span><input type="number" min="<?php if( $this->product->min_purchase_quantity > 0 ){ echo esc_attr( $this->product->min_purchase_quantity ); }else{ echo '0'; } ?>"<?php if( $this->product->show_stock_quantity || $this->product->max_purchase_quantity > 0 ){ ?> max="<?php if( $this->product->max_purchase_quantity > 0 ){ echo esc_attr( $this->product->max_purchase_quantity ); }else{ echo esc_attr( $this->product->stock_quantity ); } ?>"<?php }?> step="1" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $optionitem->optionitem_id ); ?>" value="<?php echo number_format( (float) esc_attr( $optionitem->optionitem_initial_value ), 0, "", "" ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>" /><?php
								if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
									// No pricing shown in this case.
								} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
									echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitem->optionitem_custom_price_label ) . '</span>';
								} else if ( $optionitem->optionitem_price > 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price < 0 ){
									echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price_onetime > 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
								} else if ( $optionitem->optionitem_price_onetime < 0 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
								} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
									echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')</span>';
								} ?></div>

						<?php
						}
						?>

					<?php

					/* START ADVANCED RADIO TYPE */
					}else if( $optionset->option_type == "radio" ){
					?>

						<?php
						foreach( $optionitems as $optionitem ){
						?>

							<div class="ec_details_radio_row <?php echo ( $first_optionitem_image_type ) ? 'ec_optionitem_images' : ''; ?>"><input type="radio" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" value="<?php echo esc_attr( $optionitem->optionitem_id ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitem->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitem->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitem->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitem->optionitem_price_multiplier ); ?>"<?php if( $optionitem->optionitem_initially_selected || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) && strtolower( sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ) == strtolower( $optionitem->optionitem_name ) ) || ( isset( $_GET['o'.$optionset->option_id] ) && sanitize_text_field( $_GET['o'.$optionset->option_id] ) == $optionitem->optionitem_name ) ){ ?> checked="checked"<?php }?> /> <?php echo wp_easycart_escape_html( $optionitem->optionitem_name ); ?> <?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitem->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitem->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitem->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitem->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitem->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ) . ')</span>';
							} ?></div>

						<?php
						}
						?>

					<?php

					/* START ADVANCED TEXT TYPE */
					}else if( $optionset->option_type == "text" ){
					?>

						<input type="text" value="<?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); }else if( $optionitems[0]->optionitem_initial_value != '' ){ echo esc_attr( $optionitems[0]->optionitem_initial_value ); } ?>" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

					<?php

					/* START ADVANCED NUMBER TYPE */
					}else if( $optionset->option_type == "number" ){
					?>

						<input type="number" value="<?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); }else if( $optionitems[0]->optionitem_initial_value != '' ){ echo esc_attr( $optionitems[0]->optionitem_initial_value ); } ?>" min="<?php echo esc_attr( $optionset->option_meta['min'] ); ?>" max="<?php echo esc_attr( $optionset->option_meta['max'] ); ?>" step="<?php echo esc_attr( $optionset->option_meta['step'] ); ?>" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" /><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

					<?php

					/* START ADVANCED TEXT AREA TYPE */
					}else if( $optionset->option_type == "textarea" ){
					?>

						<textarea name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>"><?php if( isset( $_GET['o'.$optionset->option_id] ) || ( isset( $optionset->option_meta['url_var'] ) && $optionset->option_meta['url_var'] != '' && isset( $_GET[$optionset->option_meta['url_var']] ) ) ){ echo esc_attr( htmlspecialchars( ( ( isset( $_GET['o'.$optionset->option_id] ) ) ? sanitize_text_field( $_GET['o'.$optionset->option_id] ) : sanitize_text_field( $_GET[$optionset->option_meta['url_var']] ) ), ENT_QUOTES ) ); } ?></textarea><?php
							if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
								// No pricing shown in this case.
							} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
							} else if ( $optionitems[0]->optionitem_price > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price < 0 ){
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $optionitems[0]->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ) {
								echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
							} ?>

					<?php

					/* START ADVANCED DIMENSIONS TYPE */
					}else if( $optionset->option_type == "dimensions1" || $optionset->option_type == "dimensions2" ){

						// Type 1 is NO sub dimensions (34")
						// Type 2 USES sub dimensions (34 1/2")

						$type = 2;

						if( $optionitems[0]->optionitem_name == "DimensionType1" )
							$type = 1;
					?>

						<?php if ( $this->product->login_for_pricing && ! $this->product->is_login_for_pricing_valid() ) {
							// No pricing shown in this case.
						} else if ( $optionitems[0]->optionitem_enable_custom_price_label && ( $optionitems[0]->optionitem_price != 0 || ( isset( $optionitems[0]->optionitem_price ) && $optionitems[0]->optionitem_price != 0 ) || ( isset( $optionitems[0]->optionitem_price_onetime ) && $optionitems[0]->optionitem_price_onetime != 0 ) ) ) {
							echo '<span class="ec_product_details_option_pricing">' . esc_attr( $optionitems[0]->optionitem_custom_price_label ) . '</span>';
						} else if( $optionitems[0]->optionitem_price > 0 ){
							echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
						} else if( $optionitems[0]->optionitem_price < 0 ){
							echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>'; }else if( $optionitems[0]->optionitem_price_onetime > 0 ){ echo '<span class="ec_product_details_option_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
						} else if( $optionitems[0]->optionitem_price_onetime < 0 ){
							echo '<span class="ec_product_details_option_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
						} else if( isset( $optionitems[0]->optionitem_price_override ) && $optionitems[0]->optionitem_price_override > -1 ){
							echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_override ) ) . ')</span>';
						}else if( isset( $optionitems[0]->optionitem_price_per_character ) && $optionitems[0]->optionitem_price_per_character > 0 ){
							echo '<span class="ec_product_details_option_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $optionitems[0]->optionitem_price_per_character ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment_per_character' ) . ')</span>';
						} ?>

						<input type="text" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_width" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_width" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>" class="ec_dimensions_box ec_dimensions_width" data-option-id="<?php echo esc_attr( $optionset->option_id ); ?>" data-product-option-id="<?php echo esc_attr( $optionset->option_to_product_id ); ?>" data-is-metric="<?php echo esc_attr( get_option( 'ec_option_enable_metric_unit_display' ) ); ?>" />
						

						<?php if( $type == 2 ){ ?>
							<select name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_sub_width" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_sub_width" class="ec_dimensions_select">
								<option value="0">0</option>
								<option value="1/16">1/16</option>
								<option value="1/8">1/8</option>
								<option value="3/16">3/16</option>
								<option value="1/4">1/4</option>
								<option value="5/16">5/16</option>
								<option value="3/8">3/8</option>
								<option value="7/16">7/16</option>
								<option value="1/2">1/2</option>
								<option value="9/16">9/16</option>
								<option value="5/8">5/8</option>
								<option value="11/16">11/16</option>
								<option value="3/4">3/4</option>
								<option value="13/16">13/16</option>
								<option value="7/8">7/8</option>
								<option value="15/16">15/16</option>
							</select>
						<?php }?>

						<span class="ec_dimensions_seperator">x</span>

						<input type="text" name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_height" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_height" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-optionitem-price="<?php echo esc_attr( $optionitems[0]->optionitem_price ); ?>" data-optionitem-price-onetime="<?php echo esc_attr( $optionitems[0]->optionitem_price_onetime ); ?>" data-optionitem-price-override="<?php echo esc_attr( $optionitems[0]->optionitem_price_override ); ?>" data-optionitem-price-multiplier="<?php echo esc_attr( $optionitems[0]->optionitem_price_multiplier ); ?>" data-optionitem-price-per-character="<?php echo esc_attr( $optionitems[0]->optionitem_price_per_character ); ?>" class="ec_dimensions_box" />
						
						<?php if( $type == 2 ){ ?>
						<select name="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_sub_height" id="ec_option_adv_<?php echo esc_attr( $optionset->option_to_product_id ); ?>_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_sub_height" class="ec_dimensions_select">
							<option value="0">0</option>
							<option value="1/16">1/16</option>
							<option value="1/8">1/8</option>
							<option value="3/16">3/16</option>
							<option value="1/4">1/4</option>
							<option value="5/16">5/16</option>
							<option value="3/8">3/8</option>
							<option value="7/16">7/16</option>
							<option value="1/2">1/2</option>
							<option value="9/16">9/16</option>
							<option value="5/8">5/8</option>
							<option value="11/16">11/16</option>
							<option value="3/4">3/4</option>
							<option value="13/16">13/16</option>
							<option value="7/8">7/8</option>
							<option value="15/16">15/16</option>
						</select>
						<?php }?>

					<?php
					}
				?>
					</div>
				</div>				
				<?php
				}
				?>
			</div>
			<?php }?>
			<?php } /* INQUIRY MODE EXCLUDED */ ?>
			<?php /* END ADVANCED OPTIONS*/ ?>
			<?php if ( ( isset( $optionsets ) && count( $optionsets ) > 0 ) || ( $this->product->advanced_optionsets && count( $this->product->advanced_optionsets ) > 0 ) ) { ?>
			<div class="ec_details_options_divider_post"></div>
			<?php } ?>
			<?php /* PRODUCT ADD TO CART */ ?>
			<div class="ec_details_option_row_error" id="ec_addtocart_quantity_exceeded_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_maximum_quantity' ); ?></div>
			<div class="ec_details_option_row_error" id="ec_addtocart_quantity_minimum_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_minimum_quantity_text1' ); ?> <?php echo esc_attr( $this->product->min_purchase_quantity ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_minimum_quantity_text2' ); ?></div>
			<div class="ec_details_option_row_error" id="ec_addtocart_quantity_maximum_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_maximum_quantity_text1' ); ?> <?php echo esc_attr( $this->product->max_purchase_quantity ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_maximum_quantity_text2' ); ?></div>

			<?php

			do_action( 'wp_easycart_product_details_pre_add_to_cart', $this->product->product_id );

			$show_add_to_cart_area = true;
			$show_add_to_cart_area = apply_filters( 'wp_easycart_product_details_show_cart_area', $show_add_to_cart_area );

			if( $show_add_to_cart_area ){ ?>
				<div class="ec_details_add_to_cart_area">

					<?php /* CATALOG MODE */ ?>
					<?php if( apply_filters( 'wp_easycart_catalog_display', get_option( 'ec_option_display_as_catalog' ) ) ){
					// Show nothing

					} else if ( $this->product->login_for_pricing && !$this->product->is_login_for_pricing_valid( ) && $GLOBALS['ec_user']->user_id != 0 ) { ?>
						<div class="ec_seasonal_mode"><?php echo wp_easycart_language( )->get_text( 'product_page', 'product_page_login_for_price_no_access' ); ?></div>

					<?php } else if ( $this->product->login_for_pricing && !$this->product->is_login_for_pricing_valid( ) ) { ?>
						<div class="ec_details_add_to_cart"><a href="<?php echo esc_attr( $this->account_page ); ?>" style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>"><?php echo esc_attr( ( $this->product->login_for_pricing_label != '' ) ? $this->product->login_for_pricing_label : wp_easycart_language( )->get_text( 'product_page', 'product_page_login_for_price' ) ); ?></a></div>

					<?php } else if( $this->product->is_catalog_mode ) { ?>
						<div class="ec_details_seasonal_mode"><?php echo esc_attr( $this->product->catalog_mode_phrase ); ?></div>	

					<?php /* INQUIRY BUTTON */ ?>
					<?php } else if( $this->product->is_inquiry_mode ) { ?>
						<div class="ec_details_add_to_cart">
							<?php if( get_option( 'ec_option_use_inquiry_form' ) || $this->product->inquiry_url == "" ){ ?>
							<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquire' ); ?>" <?php if( $this->product->inquiry_url == "" ){ ?>onclick="return ec_details_show_inquiry_form( '<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>' );" <?php }else{ ?>onclick="<?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?>wp_easycart_facebook_add_to_cart_track_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( ); <?php }?>return ec_details_add_to_cart( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" <?php }?>style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
							<?php }else{ ?>
							<a href="<?php echo esc_attr( $this->product->inquiry_url ); ?>" style="margin-left:0px !important;"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_inquire' ); ?></a>
							<?php }?>
						</div>

					<?php /* DecoNetwork BUTTON */ ?>
					<?php } else if( $this->product->is_deconetwork ) { ?>
						<?php if( get_option( 'ec_option_deconetwork_allow_blank_products' ) ){ // Custom option to have both add to cart and design now ?>
							<div class="ec_details_quantity" data-use-advanced-optionset="<?php echo ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ? '1' : '0'; ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-min-purchase-quantity="<?php echo esc_attr( ( ( $this->product->min_purchase_quantity > 0 ) ? $this->product->min_purchase_quantity : '1' ) ); ?>" data-max-purchase-quantity="<?php echo esc_attr( ( ( $this->product->max_purchase_quantity > 0 ) ? $this->product->max_purchase_quantity : $this->product->stock_quantity ) ); ?>" data-show-stock-quantity="<?php echo esc_attr( $this->product->show_stock_quantity ); ?>" <?php if( $has_quantity_grid ){ ?> style="display:none;"<?php }?>>
								<input type="button" value="-" class="ec_minus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
								<input type="number" value="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" name="ec_quantity" id="ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" autocomplete="off" step="1" min="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" class="ec_quantity"<?php if( $this->product->show_stock_quantity || $this->product->max_purchase_quantity > 0 ){ ?> max="<?php echo ( $this->product->max_purchase_quantity > 0 ) ? esc_attr( $this->product->max_purchase_quantity ) : esc_attr( $this->product->stock_quantity ); ?>"<?php } ?> />
								<input type="button" value="+" class="ec_plus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
							</div>
							<div class="ec_details_add_to_cart ec_deconetwork_custom_space">
								<input type="submit" value="<?php echo esc_attr( apply_filters( 'wp_easycart_product_details_add_to_cart_value', wp_easycart_language( )->get_text( 'product_details', 'product_details_add_to_cart' ), $this->product->product_id ) ); ?>" onclick="<?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?>wp_easycart_facebook_add_to_cart_track_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( ); <?php }?>return ec_details_add_to_cart( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );"<?php if( $has_quantity_grid ){ ?> style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>"<?php }?> />
							</div>
						<?php } ?>

						<div class="ec_details_add_to_cart">
							<a href="<?php echo esc_attr( $this->product->get_deconetwork_link( ) ); ?>" style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>"><?php echo wp_easycart_language( )->get_text( 'product_page', 'product_design_now' ); ?></a>
						</div>

					<?php /* SUBSCRIPTION BUTTON */ ?>
					<?php } else if( $this->product->is_subscription_item ) { // && !class_exists( "ec_stripe" ) ){ ?>

						<?php if ( !get_option( 'ec_option_subscription_one_only' ) ) { ?>
							<div class="ec_details_quantity" data-use-advanced-optionset="<?php echo ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ? '1' : '0'; ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-min-purchase-quantity="<?php echo esc_attr( ( ( $this->product->min_purchase_quantity > 0 ) ? $this->product->min_purchase_quantity : '1' ) ); ?>" data-max-purchase-quantity="<?php echo esc_attr( ( ( $this->product->max_purchase_quantity > 0 ) ? $this->product->max_purchase_quantity : $this->product->stock_quantity ) ); ?>" data-show-stock-quantity="<?php echo esc_attr( $this->product->show_stock_quantity ); ?>" <?php if( $has_quantity_grid ){ ?> style="display:none;"<?php }?>>
								<input type="button" value="-" class="ec_minus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
								<input type="number" value="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" name="ec_quantity" id="ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" autocomplete="off" step="1" min="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" class="ec_quantity"<?php if( $this->product->show_stock_quantity || $this->product->max_purchase_quantity > 0 ){ ?> max="<?php echo ( $this->product->max_purchase_quantity > 0 ) ? esc_attr( $this->product->max_purchase_quantity ) : esc_attr( $this->product->stock_quantity ); } ?>" />
								<input type="button" value="+" class="ec_plus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
							</div>
						<?php } else { ?>
							<input type="hidden" id="ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="1" />
						<?php } ?>

						<div class="ec_details_add_to_cart">
							<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_sign_up_now' ); ?>" onclick="<?php do_action( 'wp_easycart_product_details_subscription_button_onclick', $this->product ); ?><?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?>wp_easycart_facebook_add_to_cart_track_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( ); <?php }?>return ec_details_add_to_cart( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );"<?php if( get_option( 'ec_option_subscription_one_only' ) ){ ?> style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>"<?php } ?> />
						</div>
						<span class="ec_details_hidden_base_price" id="ec_base_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo esc_attr( $this->product->price ); ?></span>


					<?php /* REGULAR BUTTON + QUANTITY */ ?>
					<?php } else if( $this->product->in_stock( ) || ( $this->product->allow_backorders && $this->product->use_optionitem_quantity_tracking ) || apply_filters( 'wp_easycart_product_details_allow_add_to_cart', false, $this->product->product_id ) ) { ?>
						<div class="ec_details_quantity" data-use-advanced-optionset="<?php echo ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ? '1' : '0'; ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-min-purchase-quantity="<?php echo esc_attr( ( ( $this->product->min_purchase_quantity > 0 ) ? $this->product->min_purchase_quantity : '1' ) ); ?>" data-max-purchase-quantity="<?php echo esc_attr( ( ( $this->product->max_purchase_quantity > 0 ) ? $this->product->max_purchase_quantity : $this->product->stock_quantity ) ); ?>" data-show-stock-quantity="<?php echo esc_attr( $this->product->show_stock_quantity ); ?>" <?php if( $has_quantity_grid ){ ?> style="display:none;"<?php }?>>
							<input type="button" value="-" class="ec_minus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
							<input type="number" value="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" name="ec_quantity" id="ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" autocomplete="off" step="1" min="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" class="ec_quantity"<?php if( ( !$this->product->allow_backorders && $this->product->show_stock_quantity ) || $this->product->max_purchase_quantity > 0 ){ ?> max="<?php echo ( $this->product->max_purchase_quantity > 0 ) ? esc_attr( $this->product->max_purchase_quantity ) : esc_attr( $this->product->stock_quantity ); ?>"<?php }?> />
							<input type="button" value="+" class="ec_plus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
						</div>
						<div class="ec_details_add_to_cart">
							<input type="submit" value="<?php echo esc_attr( apply_filters( 'wp_easycart_product_details_add_to_cart_value', wp_easycart_language( )->get_text( 'product_details', 'product_details_add_to_cart' ), $this->product->product_id ) ); ?>" onclick="<?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?>wp_easycart_facebook_add_to_cart_track_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( ); <?php }?>return ec_details_add_to_cart( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" style="<?php if( $has_quantity_grid ){ ?>margin-left:0px !important;<?php } ?><?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
						</div>

						<?php /* PRICING AREA FOR OPTIONS */ ?>
						<?php if( $this->product->has_options || $this->product->use_advanced_optionset || $this->product->use_both_option_types ){ ?>
						<div class="ec_details_final_price"<?php echo ( ( isset( $this->atts['show_price'] ) && !$this->atts['show_price'] ) ) ? ' style="display:none;"' : ''; ?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_your_price' ); ?> <span id="ec_final_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php if( $override_price_grid > -1 ){ echo esc_attr( $GLOBALS['currency']->get_currency_display( $override_price_grid ) ); }else if( $add_price_grid > 0 ){ echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->price_options + $add_price_grid ) ); }else{ echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->price_options ) ); } ?></span></div>
						<?php } ?>
						<span class="ec_details_hidden_base_price" id="ec_base_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo esc_attr( $this->product->price ); ?></span>

					<?php /* OUT OF STOCK BUT BACKORDERS ALLOWED */ ?>
					<?php } else if( $this->product->allow_backorders ) { ?>
						<div class="ec_details_quantity" data-use-advanced-optionset="<?php echo ( $this->product->use_advanced_optionset || $this->product->use_both_option_types ) ? '1' : '0'; ?>" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-min-purchase-quantity="<?php echo esc_attr( ( ( $this->product->min_purchase_quantity > 0 ) ? $this->product->min_purchase_quantity : '1' ) ); ?>" data-max-purchase-quantity="100000000" data-show-stock-quantity="<?php echo esc_attr( $this->product->show_stock_quantity ); ?>" <?php if( $has_quantity_grid ){ ?> style="display:none;"<?php }?>>
							<input type="button" value="-" class="ec_minus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
							<input type="number" value="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" name="ec_quantity" id="ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" autocomplete="off" step="1" min="<?php echo ( $this->product->min_purchase_quantity > 0 ) ? esc_attr( $this->product->min_purchase_quantity ) : '1'; ?>" class="ec_quantity"<?php if( ! $this->product->allow_backorders && $this->product->max_purchase_quantity > 0 ){ ?> max="<?php echo esc_attr( $this->product->max_purchase_quantity ); ?>"<?php }?> />
							<input type="button" value="+" class="ec_plus" style="<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>" />
						</div>
						<div class="ec_details_add_to_cart">
							<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_button' ); ?>" onclick="<?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?>wp_easycart_facebook_add_to_cart_track_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( ); <?php }?>return ec_details_add_to_cart( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );"<?php if( $has_quantity_grid ){ ?> style="margin-left:0px !important;<?php echo ( isset( $this->atts['add_to_cart_color'] ) ) ? 'background-color:' . esc_attr( $this->atts['add_to_cart_color'] ) . ' !important;' : ''; ?>"<?php }?> />
						</div>

						<?php /* PRICING AREA FOR OPTIONS */ ?>
						<?php if ( $this->product->has_options || $this->product->use_advanced_optionset || $this->product->use_both_option_types ) { ?>
							<div class="ec_details_final_price"<?php echo ( ( isset( $this->atts['show_price'] ) && !$this->atts['show_price'] ) ) ? ' style="display:none;"' : ''; ?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_your_price' ); ?> <span id="ec_final_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php if( $override_price_grid > -1 ){ echo esc_attr( $GLOBALS['currency']->get_currency_display( $override_price_grid ) ); }else if( $add_price_grid > 0 ){ echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->price_options + $add_price_grid ) ); }else{ echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->price_options ) ); } ?></span></div>
						<?php } ?>
						<span class="ec_details_hidden_base_price" id="ec_base_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo esc_attr( $this->product->price ); ?></span>

					<?php /* OUT OF STOCK INFO (NO ADD TO CART CASE) */ ?>
					<?php } else { ?>
						<div class="ec_out_of_stock"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_out_of_stock' ); ?></div>
						<?php if( get_option( 'ec_option_enable_inventory_notification' ) ){ ?>
							<div class="ec_cart_success" style="display:none;" id="ec_product_details_stock_notify_complete_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><div><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_notify_subscribe_success' ); ?></div></div>
							<div class="ec_out_of_stock_notify" id="ec_product_details_stock_notify_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
								<div class="ec_out_of_stock_notify_loader_cover" style="display:none;" id="ec_product_details_stock_notify_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_loader_cover"></div>
								<div class="ec_out_of_stock_notify_loader" style="display:none;" id="ec_product_details_stock_notify_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
								<div class="ec_out_of_stock_notify_title"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_notify_subscribe_title' ); ?></div>
								<div class="ec_out_of_stock_notify_input">
									<div class="ec_cart_error_row" id="ec_email_notify_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>_error">
										<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
									</div>
									<input type="text" id="ec_email_notify_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="" placeholder="<?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_notify_subscribe_email_placeholder' ); ?>" />
								</div>

								<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
								<div class="ec_out_of_stock_notify_grecaptcha" style="float:left; width:100%; margin:-20px 0 5px; padding:0 15px;">
									<input type="hidden" id="ec_grecaptcha_response_product_details" name="ec_grecaptcha_response_product_details" value="" />
									<input type="hidden" id="ec_grecaptcha_site_key" value="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" />
									<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_product_details_recaptcha"></div>
								</div>
								<?php }?>

								<div class="ec_out_of_stock_notify_button">
									<input type="button" onclick="ec_notify_submit( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>, '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-subscribe-to-stock-notification-' . (int) $this->product->product_id ) ); ?>' );" value="<?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_notify_subscribe_button_title' ); ?>" />
								</div>
							</div>
						<?php }?>
					<?php }?>
				</div>
			<?php } //END FILTER FOR HIDING ADD TO CART ?>

			<?php if( !$this->product->in_stock( ) && !$this->product->use_optionitem_quantity_tracking && $this->product->allow_backorders ){ ?>
			<div class="ec_details_backorder_info" id="ec_back_order_info_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php echo ( ( isset( $this->atts['show_stock'] ) && !$this->atts['show_stock'] ) ) ? ' style="display:none;"' : ''; ?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_out_of_stock' ); ?><?php if( $this->product->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $this->product->backorder_fill_date ); ?><?php }?></div>
			<?php }?>

			<?php /* END ADD TO CART */ ?>

			<?php do_action( 'wp_easycart_product_details_post_add_to_cart', $this->product->product_id ); ?>

			</form>

			<?php /* START AREA BELOW ADD TO CART BUTTON ROW */ ?>
			<?php if( $this->product->has_options || $this->product->use_advanced_optionset || $this->product->use_both_option_types ){ ?><div class="ec_details_added_price ec_details_added_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( $add_order_price_grid > 0 ){ echo ' style="display:block";'; } ?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_one_time_addition1' ); ?> <span id="ec_added_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php if( $add_order_price_grid > 0 ){ echo esc_attr( $GLOBALS['currency']->get_currency_display( $add_order_price_grid ) ); }else{ echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->price ) ); } ?></span> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_one_time_addition2' ); ?></span></div><?php }?>

			<?php if( ( $this->product->show_stock_quantity || $this->product->use_optionitem_quantity_tracking ) && $this->product->stock_quantity > 0 && get_option( 'ec_option_show_stock_quantity' ) ){ ?><div class="ec_details_stock_total"<?php echo ( ( isset( $this->atts['show_stock'] ) && !$this->atts['show_stock'] ) ) ? ' style="display:none;"' : ''; ?>><span id="ec_details_stock_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo esc_attr( $this->product->stock_quantity ); ?></span> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_left_in_stock' ); ?></div><?php }else{ ?><span id="ec_details_stock_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" style="display:none;">10000000</span><?php }?>

			<?php if( $this->product->min_purchase_quantity > 1 ){ ?><div class="ec_details_min_purchase_quantity"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_minimum_quantity_text1' ); ?> <?php echo esc_attr( $this->product->min_purchase_quantity ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_minimum_quantity_text2' ); ?></div><?php }?>

			<?php if( $this->product->max_purchase_quantity > 0 ){ ?><div class="ec_details_min_purchase_quantity"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_maximum_quantity_text1' ); ?> <?php echo esc_attr( $this->product->max_purchase_quantity ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_maximum_quantity_text2' ); ?></div><?php }?>

			<?php if( $this->product->handling_price > 0 ){ ?><div class="ec_details_handling_fee"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_handling_fee_notice1' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->handling_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_handling_fee_notice2' ); ?></div><?php }?>

			<?php if( $this->product->handling_price_each > 0 ){ ?><div class="ec_details_handling_fee"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_handling_fee_each_notice1' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->handling_price_each ) ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_handling_fee_each_notice2' ); ?></div><?php }?>

			<?php if( $this->product->subscription_signup_fee > 0 ){ ?><div class="ec_details_handling_fee"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_signup_fee_notice1' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->product->subscription_signup_fee ) ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_signup_fee_notice2' ); ?></div><?php }?>

			<?php if( get_option( 'ec_option_short_description_below' ) && ( ( isset( $this->atts['show_short_description'] ) && $this->atts['show_short_description'] ) || ( ! isset( $this->atts['show_short_description'] ) && isset( $this->product->short_description ) && strlen( trim( $this->product->short_description ) ) > 0 ) ) ) {?>
			<div class="ec_details_description"><?php echo wp_easycart_escape_html( nl2br( stripslashes( $this->product->short_description ) ) ); ?></div>
			<?php }?>
			<?php do_action( 'wp_easycart_product_details_after_description_below', $this->product->product_id ); ?>

			<?php if( ( isset( $this->atts['show_categories'] ) && $this->atts['show_categories'] ) || ( ! isset( $this->atts['show_categories'] ) && get_option( 'ec_option_show_categories' ) ) ){ ?>
				<?php if( count( $this->product->categoryitems ) > 0 ){ ?>
				<div class="ec_details_categories"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_categories' ); ?> <?php $categoryitems = array( );
					$is_first_categoryitem = true;
					foreach( $this->product->categoryitems as $categoryitem ){
						if ( ! $is_first_categoryitem ) {
							echo ', ';
						}
						echo '<a href="' . esc_attr( $this->product->get_category_link( $categoryitem->post_id, $categoryitem->category_id ) ) . '">' . wp_easycart_language( )->convert_text( $categoryitem->category_name ) . '</a>';
						$is_first_categoryitem = false;
					} ?>
				</div>
				<?php }?>
			<?php }?>

			<?php if( ( isset( $this->atts['show_manufacturer'] ) && $this->atts['show_manufacturer'] ) || ( ! isset( $this->atts['show_manufacturer'] ) && get_option( 'ec_option_show_manufacturer' ) ) ){ ?>
			<div class="ec_details_manufacturer"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_manufacturer' ); ?> <a href="<?php echo esc_attr( $this->product->get_manufacturer_link( ) ); ?>"><?php echo wp_easycart_language( )->convert_text( $this->product->manufacturer_name ); ?></a></div>
			<?php }?>

			<?php /* START SOCIAL ICONS */ ?>
			<?php
			if( get_option( 'ec_option_base_theme' ) ){
				$folder = "wp-easycart-data";
				$theme = get_option( 'ec_option_base_theme' );
			}else{
				$folder = "wp-easycart";
				$theme = get_option( 'ec_option_latest_theme' );
			}
			?>
			<?php if( ( isset( $this->atts['show_social'] ) && $this->atts['show_social'] ) || ( ! isset( $this->atts['show_social'] ) ) ){ ?>
			<div class="ec_details_social">

				<?php if( get_option( 'ec_option_use_facebook_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_facebook"><a href="<?php echo esc_attr( $this->product->social_icons->get_facebook_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "facebook-icon.png" ) ); ?>" alt="Facebook" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_twitter_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_twitter"><a href="<?php echo esc_attr( $this->product->social_icons->get_twitter_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "twitter-icon.png" ) ); ?>" alt="Twitter" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_email_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_email"><a href="<?php echo esc_attr( $this->product->social_icons->get_email_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "email-icon.png" ) ); ?>" alt="Email" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_pinterest_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_pinterest"><a href="<?php echo esc_attr( $this->product->social_icons->get_pinterest_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "pinterest-icon.png" ) ); ?>" alt="Pinterest" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_googleplus_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_googleplus"><a href="<?php echo esc_attr( $this->product->social_icons->get_googleplus_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "google-icon.png" ) ); ?>" alt="Google+" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_linkedin_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_linkedin"><a href="<?php echo esc_attr( $this->product->social_icons->get_linkedin_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "linkedin-icon.png" ) ); ?>" alt="LinkedIn" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_myspace_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_myspace"><a href="<?php echo esc_attr( $this->product->social_icons->get_myspace_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "myspace-icon.png" ) ); ?>" alt="MySpace" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_digg_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_digg"><a href="<?php echo esc_attr( $this->product->social_icons->get_digg_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "digg-icon.png" ) ); ?>" alt="Digg" /></a></div>
				<?php }?>

				<?php if( get_option( 'ec_option_use_delicious_icon' ) ){ ?>
				<div class="ec_details_social_icon ec_delicious"><a href="<?php echo esc_attr( $this->product->social_icons->get_delicious_link( ) ); ?>" target="_blank"><img src="<?php echo esc_attr( $this->product->social_icons->get_icon_image( "delicious-icon.png" ) ); ?>" alt="Delicious" /></a></div>
				<?php }?>

			</div>
			<?php }?>
			<?php /* END SOCIAL ICONS */ ?>

		</div>
		<?php /* END RIGHT HALF */ ?>

	</div>
	<?php /* END TOP SECTION*/ ?>


	<?php /* START EXTRA CONTENT AREA */ ?>
	<?php if( ( ( isset( $this->atts['show_description'] ) && $this->atts['show_description'] ) || ( ! isset( $this->atts['show_description'] ) ) ) || ( ( isset( $this->atts['show_specifications'] ) && $this->atts['show_specifications'] ) || ( ! isset( $this->atts['show_specifications'] ) && $this->product->use_specifications ) ) || ( ( isset( $this->atts['show_customer_reviews'] ) && $this->atts['show_customer_reviews'] ) || ( ! isset( $this->atts['show_customer_reviews'] ) && $this->product->use_customer_reviews ) ) ){ ?>
	<div class="ec_details_extra_area ec_details_extra_area_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">

		<ul class="ec_details_tabs" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">

			<?php do_action( 'wpeasycart_pre_description_tab', $this->product->product_id, $wpeasycart_addtocart_shortcode_rand ); ?>
			<?php if( ( isset( $this->atts['show_description'] ) && $this->atts['show_description'] ) || ( ! isset( $this->atts['show_description'] ) ) ){ ?>
			<li class="ec_details_tab ec_details_tab_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> <?php echo esc_attr( apply_filters( 'wpeasycart_description_initally_active', 'ec_active', $this->product->product_id ) ); ?> ec_description"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_description' ); ?></li>
			<?php }?>

			<?php do_action( 'wpeasycart_pre_specifications_tab', $this->product->product_id, $wpeasycart_addtocart_shortcode_rand ); ?>
			<?php if( ( isset( $this->atts['show_specifications'] ) && $this->atts['show_specifications'] ) || ( ! isset( $this->atts['show_specifications'] ) && $this->product->use_specifications ) ){ ?>
			<li class="ec_details_tab ec_details_tab_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_specifications<?php if( isset( $this->atts['show_description'] ) && ! $this->atts['show_description'] ){ echo ' ec_active'; } ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_specifications' ); ?></li>
			<?php }?>

			<?php do_action( 'wpeasycart_pre_customer_reviews_tab', $this->product->product_id, $wpeasycart_addtocart_shortcode_rand ); ?>
			<?php if( ( isset( $this->atts['show_customer_reviews'] ) && $this->atts['show_customer_reviews'] ) || ( ! isset( $this->atts['show_customer_reviews'] ) && $this->product->use_customer_reviews ) ){ ?>
			<li class="ec_details_tab ec_details_tab_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ec_customer_reviews<?php if( isset( $this->atts['show_description'] ) && ! $this->atts['show_description'] && isset( $this->atts['show_specifications'] ) && ! $this->atts['show_specifications'] ){ echo ' ec_active'; } ?>"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_customer_reviews' ); ?> (<?php echo count( $this->product->reviews ); ?>)</li>
			<?php } ?>

			<?php do_action( 'wpeasycart_addon_product_details_tab', $this->product->product_id, $wpeasycart_addtocart_shortcode_rand ); ?>

		</ul>

		<?php if( ( isset( $this->atts['show_description'] ) && $this->atts['show_description'] ) || ( ! isset( $this->atts['show_description'] ) ) ){ ?>
		<div class="ec_details_description_tab ec_details_description_tab_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" <?php echo esc_attr( apply_filters( 'wpeasycart_description_content_initally_active', '', $this->product->product_id ) ); ?>>

			<?php 
			// START CONTENT EDITING SECTION
			if( $is_admin && !$is_preview && substr( $this->product->description, 0, 3 ) != "[ec" ){ ?>
			<div class="ec_details_edit_buttons">
				<div class="ec_details_edit_button" id="ec_details_edit_description_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><input type="button" value="Edit Description" onclick="ec_admin_show_description_editor( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" /></div>
				<div class="ec_details_edit_button" id="ec_details_save_description_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><input type="button" value="Save Description" onclick="ec_admin_save_description_editor( '<?php echo esc_attr( $this->product->model_number ); ?>', <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" /></div>
			</div>

			<div class="ec_details_description_editor ec_details_description_editor_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<?php
					$content = do_shortcode( stripslashes( $this->product->description ) );
					$content = str_replace( ']]>', ']]&gt;', $content );
					wp_editor( $content, 'desc_' . $this->product->model_number, array( ) );
				?>
			</div>
			<?php } 
			//END CONTENT EDITING SECTION ?>

			<div class="ec_details_description_content ec_details_description_content_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<?php
					if( substr( $this->product->description, 0, 3 ) == "[ec" ){
						$this->product->display_product_description( );
					}else{
						$content = do_shortcode( stripslashes( $this->product->description ) );
						$content = str_replace( ']]>', ']]&gt;', $content );
						echo wp_easycart_escape_html( $content ); // XSS OK.
					}
				?>
			</div>

		</div>
		<?php } ?>

		<?php if( ( isset( $this->atts['show_specifications'] ) && $this->atts['show_specifications'] ) || ( ! isset( $this->atts['show_specifications'] ) && $this->product->use_specifications ) ){ ?>
		<div class="ec_details_specifications_tab ec_details_specifications_tab_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( isset( $this->atts['show_description'] ) && ! $this->atts['show_description'] ){ echo ' style="display:block;"'; } ?>>

			<?php 
			// START CONTENT EDITING SECTION
			if( $is_admin && !$is_preview && substr( $this->product->specifications, 0, 3 ) != "[ec" ){ ?>
			<div class="ec_details_edit_buttons">
				<div class="ec_details_edit_button" id="ec_details_edit_specifications_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><input type="button" value="Edit Specifications" onclick="ec_admin_show_specifications_editor( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" /></div>
				<div class="ec_details_edit_button" id="ec_details_save_specifications_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><input type="button" value="Save Specifications" onclick="ec_admin_save_specifications_editor( '<?php echo esc_attr( $this->product->model_number ); ?>', <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> );" /></div>
			</div>

			<div class="ec_details_specifications_editor ec_details_specifications_editor_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<?php
					$content = do_shortcode( stripslashes( $this->product->specifications ) );
					$content = stripslashes( str_replace( ']]>', ']]&gt;', $content ) );
					wp_editor( $content, 'specs_' . $this->product->model_number, array( ) );
				?>
			</div>
			<?php } 
			//END CONTENT EDITING SECTION ?>

			<div class="ec_details_specifications_content ec_details_specifications_content_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
				<?php
					if( substr( $this->product->specifications, 0, 3 ) == "[ec" ){
						$this->product->display_product_specifications( );
					}else{
						$content = do_shortcode( stripslashes( $this->product->specifications ) );
						$content = stripslashes( str_replace( ']]>', ']]&gt;', $content ) );
						echo wp_easycart_escape_html( $content ); // XSS OK.
					}
				?>
			</div>

		</div>
		<?php }?>

		<?php 
		/* START CUSTOMER REVIEW AREA */
		if( ( isset( $this->atts['show_customer_reviews'] ) && $this->atts['show_customer_reviews'] ) || ( ! isset( $this->atts['show_customer_reviews'] ) && $this->product->use_customer_reviews ) ){ ?>
		<div class="ec_details_customer_reviews_tab ec_details_customer_reviews_tab_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"<?php if( isset( $this->atts['show_description'] ) && ! $this->atts['show_description'] && isset( $this->atts['show_specifications'] ) && ! $this->atts['show_specifications'] ){ echo ' style="display:block;"'; } ?>>
			<?php if( count( $this->product->reviews ) > 0 ){ ?>
			<div class="ec_details_customer_reviews_left">
				<h3><?php echo count( $this->product->reviews ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_reviews_for_text' ); ?> <?php echo esc_attr( $this->product->title ); ?></h3>
				<?php $perpage = apply_filters( 'wp_easycart_reviews_pagnation_perpage', 6 ); ?>
				<input type="hidden" id="ec_details_reviews_per_page_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $perpage ); ?>" />
				<ul class="ec_details_customer_review_list" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>">
					<?php foreach( $this->product->reviews as $review_row ){ 
					$review = new ec_review( $review_row );
					?>
					<li>
						<div>
							<span class="ec_details_customer_review_date"><strong><?php echo esc_attr( wp_unslash( $review->title ) ); ?></strong> - <?php echo esc_attr( $review->review_date ); ?></span>
							<?php if( get_option( 'ec_option_customer_review_show_user_name' ) ){ ?><span class="ec_details_customer_review_name"><?php echo esc_attr( wp_unslash( $review->reviewer_name ) ); ?></span><?php }?>
							<span class="ec_details_customer_review_stars" title="Rated <?php echo esc_attr( $review->rating ); ?> of 5"><?php $review->display_review_stars( ); ?></span>
						</div>
						<div class="ec_details_customer_review_data"><?php echo wp_easycart_escape_html( nl2br( wp_unslash( $review->description ) ) ); ?></div>
					</li>
					<?php } ?>
				</ul>
			</div>
			<?php }else{ ?>
			<div class="ec_details_customer_reviews_left"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_review_no_reviews' ); ?></div>
			<?php } ?>
			<?php if( !get_option( 'ec_option_customer_review_require_login' ) || ( $GLOBALS['ec_cart_data']->cart_data->user_id != "" && $GLOBALS['ec_cart_data']->cart_data->user_id != 0 ) ){ ?>
			<div class="ec_details_customer_reviews_form">
				<div class="ec_details_customer_reviews_form_holder">
					<div class="ec_details_customer_review_loader_holder" id="ec_details_customer_review_loader_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
						<div class="ec_details_customer_review_loader"><?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_submitting_review' ); ?></div>
					</div>
					<div class="ec_details_customer_review_success_holder" id="ec_details_customer_review_success_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
						<div class="ec_details_customer_review_success"><?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_review_submitted' ); ?></div>
					</div>
					<h3><?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_add_a_review_for' ); ?> <?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?></h3>
					<div class="ec_details_option_row_error" id="ec_details_review_error_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><?php echo wp_easycart_language( )->get_text( 'customer_review', 'review_error' ); ?></div>
					<div class="ec_details_customer_reviews_row"><?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_your_review_title' ); ?></div>
					<div class="ec_details_customer_reviews_row ec_lower_space"><input type="text" id="ec_review_title_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" /></div>
					<div class="ec_details_customer_reviews_row"><?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_your_review_rating' ); ?></div>
					<div class="ec_details_customer_reviews_row ec_stars" data-product-id="<?php echo esc_attr( $this->product->product_id ); ?>" data-rand-id="<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>">
						<div class="ec_product_details_star_off ec_details_review_input ec_details_review_input_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-review-score="1" id="ec_details_review_star1_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></div>
						<div class="ec_product_details_star_off ec_details_review_input ec_details_review_input_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-review-score="2" id="ec_details_review_star2_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></div>
						<div class="ec_product_details_star_off ec_details_review_input ec_details_review_input_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-review-score="3" id="ec_details_review_star3_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></div>
						<div class="ec_product_details_star_off ec_details_review_input ec_details_review_input_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-review-score="4" id="ec_details_review_star4_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></div>
						<div class="ec_product_details_star_off ec_details_review_input ec_details_review_input_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" data-review-score="5" id="ec_details_review_star5_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></div>
					</div>
					<div class="ec_details_customer_reviews_row"><?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_your_review_message' ); ?></div>
					<div class="ec_details_customer_reviews_row ec_lower_space"><textarea id="ec_review_message_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"></textarea></div>
					<div class="ec_details_customer_reviews_row ec_details_submit_review_button_row" id="ec_details_submit_review_button_row_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><input type="button" value="<?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_your_review_submit' ); ?>" onclick="ec_submit_product_review( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>, '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-insert-customer-review-' . (int) $this->product->product_id ) ); ?>' )" /></div>
					<div class="ec_details_customer_reviews_row ec_details_review_submitted_button_row" id="ec_details_review_submitted_button_row_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>"><input type="button" disabled="disabled" value="<?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_review_submitted_button' ); ?>" /></div>
				</div>
			</div>
			<?php }else{ ?>
			<div class="ec_details_customer_reviews_form">
				<div class="ec_details_customer_reviews_form_holder">
					<?php echo wp_easycart_language( )->get_text( 'customer_review', 'product_details_review_log_in_first' ); ?>
				</div> 
			</div>
			<?php }?>
		</div>
		<?php }?>

		<?php do_action( 'wpeasycart_addon_product_details_tab_content', $this->product->product_id ); ?>

	</div>
	<?php }?>

	<?php /* START RELATED PRODUCTS AREA */ ?>
	<?php if( ( isset( $this->atts['show_related_products'] ) && $this->atts['show_related_products'] ) || ( ! isset( $this->atts['show_related_products'] ) ) ){ ?>
	<?php if( $this->product->featured_products->product1 || $this->product->featured_products->product2 || $this->product->featured_products->product3 || $this->product->featured_products->product4 ){ ?>
	<div class="ec_details_related_products_area">
		<h3><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_related_products' ); ?></h3>
		<ul class="ec_details_related_products">
			<?php if( $this->product->featured_products->product1 ){ ?>

			<?php
			$product = $this->product->featured_products->product1;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product.php' );
			else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product.php' );
			?>

			<?php } ?>

			<?php if( $this->product->featured_products->product2 ){ ?>

			<?php
			$product = $this->product->featured_products->product2;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product.php' );
			else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product.php' );
			?>

			<?php } ?>

			<?php if( $this->product->featured_products->product3 ){ ?>

			<?php
			$product = $this->product->featured_products->product3;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product.php' );
			else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product.php' );
			?>

			<?php } ?>

			<?php if( $this->product->featured_products->product4 ){ ?>

			<?php
			$product = $this->product->featured_products->product4;
			if( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option('ec_option_base_layout') . '/ec_product.php' );
			else if( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_product.php' ) )	
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option('ec_option_latest_layout') . '/ec_product.php' );
			?>

			<?php } ?>
		</ul>
	</div>
	<?php }?>
	<?php }?>
	<div style="clear:both;"></div>
</section>
<div style="clear:both;"></div>
<input type="hidden" id="ec_default_sku_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->model_number ); ?>" />
<input type="hidden" id="ec_default_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->price ); ?>" />
<input type="hidden" id="use_optionitem_images_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->use_optionitem_images ); ?>" />
<input type="hidden" id="use_optionitem_quantity_tracking_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->use_optionitem_quantity_tracking ); ?>" />
<input type="hidden" id="price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->price ); ?>" />
<input type="hidden" id="ec_base_option_price_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->price ); ?>" />
<input type="hidden" id="min_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->min_purchase_quantity ); ?>" />
<input type="hidden" id="max_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $this->product->max_purchase_quantity ); ?>" />
<input type="hidden" id="vat_added_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo ( isset( $vat_row ) && $vat_row->vat_added ) ? '1' : '0'; ?>" />
<input type="hidden" id="vat_rate_multiplier_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $vat_rate_multiplier ); ?>" />
<input type="hidden" id="currency_symbol_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_symbol( ) ); ?>" />
<input type="hidden" id="num_decimals_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_decimal_length( ) ); ?>" />
<input type="hidden" id="decimal_symbol_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_decimal_symbol( ) ); ?>" />
<input type="hidden" id="grouping_symbol_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_grouping_symbol( ) ); ?>" />
<input type="hidden" id="conversion_rate_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_conversion_rate( ) ); ?>" />
<input type="hidden" id="symbol_location_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_symbol_location( ) ); ?>" />
<input type="hidden" id="currency_code_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>" />
<input type="hidden" id="show_currency_code_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( $GLOBALS['currency']->get_show_currency_code( ) ); ?>" />
<input type="hidden" id="product_details_nonce_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-product-details-' . (int) $this->product->product_id ) ); ?>" />
<script>
var tier_quantities_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> = [<?php if( !$this->product->using_role_price ){ for( $tier_i = 0; $tier_i < count( $this->product->pricetiers ); $tier_i++ ){ if( $tier_i > 0 ){ echo ","; } echo esc_attr( $this->product->pricetiers[$tier_i][1] ); } } ?>];
var tier_prices_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> = [<?php if( !$this->product->using_role_price ){ for( $tier_i = 0; $tier_i < count( $this->product->pricetiers ); $tier_i++ ){ if( $tier_i > 0 ){ echo ","; } echo esc_attr( $this->product->pricetiers[$tier_i][0] ); } } ?>];
var varitation_data_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> = {<?php foreach ( $this->product->options->variation_array as $variation_key => $variation_item ) {
	if ( isset( $variation_item->quantity ) && isset( $variation_item->sku ) && isset( $variation_item->price ) ) {
		echo '"' . esc_attr( $variation_key ) . '":{quantity:' . esc_attr( $variation_item->quantity ) . ',sku:"' . esc_attr( $variation_item->sku ) . '",price:"' . esc_attr( $variation_item->price ) . '",tracking:' . esc_attr( ( ( $variation_item->is_stock_tracking_enabled ) ? 'true' : 'false' ) ) . ',enabled:' . esc_attr( ( ( $variation_item->is_enabled ) ? 'true' : 'false' ) ) . '},';
	}
} ?> };
function wp_easycart_add_to_cart_js_validation_end_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( errors ){
	<?php do_action( 'wp_easycart_add_to_cart_js_validation_end', $this->product->product_id ); ?>
	return errors;
}<?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?>
function wp_easycart_facebook_add_to_cart_track_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>( ){
	if( ec_details_add_to_cart( <?php echo esc_attr( $this->product->product_id ); ?>, <?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?> ) ){
		fbq('track', 'AddToCart', {
			content_name: '<?php echo esc_attr( strip_tags( stripslashes( $this->product->title ) ) ); ?>',
			content: [{id: '<?php echo esc_attr( $this->product->product_id ); ?>', quantity: jQuery( document.getElementById( 'ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>' ) ).val( ), item_price: <?php echo number_format( esc_attr( $this->product->price ), 2, '.', '' ); ?>}],
			content_type: 'product',
			value: Number( jQuery( document.getElementById( 'ec_quantity_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>' ) ).val( ) * <?php echo number_format( esc_attr( $this->product->price ), 2, '.', '' ); ?> ).toFixed( 2 ),
			currency: '<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>'
		});
	}
}<?php }?>
</script><?php if( $is_admin && !$is_preview ){ ?>
<input type="hidden" id="product_details_update_product_description_nonce_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-product-description-' . (int) $this->product->product_id ) ); ?>" />
<input type="hidden" id="product_details_update_product_specs_nonce_<?php echo esc_attr( $this->product->product_id ); ?>_<?php echo esc_attr( $wpeasycart_addtocart_shortcode_rand ); ?>" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-product-specifications-' . (int) $this->product->product_id ) ); ?>" />
<?php } ?>
<?php do_action( 'wp_easycart_product_details_end', $this->product ); ?>
<?php /* END NEW PRODUCT DETAILS CONTENT */ ?>