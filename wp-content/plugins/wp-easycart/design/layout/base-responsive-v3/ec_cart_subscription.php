<?php
$ua = sanitize_text_field( $_SERVER["HTTP_USER_AGENT"] );
$safariorchrome = strpos($ua, 'Safari') ? true : false;
$chrome = strpos($ua, 'Chrome') ? true : false;
if ( $safariorchrome && !$chrome )
	$safari = true;
else
	$safari = false;

$ipad = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPad' );
$iphone = (bool) strpos( sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ), 'iPhone' );

$is_admin = ( ( current_user_can( 'manage_options' ) || current_user_can( 'wpec_manager' ) ) && ! get_option( 'ec_option_hide_live_editor' ) );

if ( isset( $_GET['preview'] ) ) {
	$is_preview = true;
} else {
	$is_preview = false;
}

if ( isset( $_GET['previewholder'] ) )
	$is_preview_holder = true;
else
	$is_preview_holder = false;

// END CHECK // 

/* PREVIEW CONTENT */
if ( $is_preview_holder && $is_admin ) { ?>

<div class="ec_admin_preview_container" id="ec_admin_preview_container">
	<div class="ec_admin_preview_content">
		<div class="ec_admin_preview_button_container">
			<div class="ec_admin_preview_ipad_landscape"><input type="button" onclick="ec_admin_ipad_landscape_preview();" value="iPad Landscape"></div>
			<div class="ec_admin_preview_ipad_portrait"><input type="button" onclick="ec_admin_ipad_portrait_preview();" value="iPad Portrait"></div>
			<div class="ec_admin_preview_iphone_landscape"><input type="button" onclick="ec_admin_iphone_landscape_preview();" value="iPhone Landscape"></div>
			<div class="ec_admin_preview_iphone_portrait"><input type="button" onclick="ec_admin_iphone_portrait_preview();" value="iPhone Portrait"></div>
		</div>
		<div id="ec_admin_preview_content" class="ec_admin_preview_wrapper ipad landscape">
			<iframe src="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>preview=true" width="100%" height="100%" id="ec_admin_preview_iframe"></iframe>
		</div>
	</div>
</div>

<?php } else if ( $is_admin && !$safari && !$is_preview ) { ?>

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
	<div id="ec_page_editor_openclose_button" class="ec_slideout_openclose" data-post-id="<?php global $post; echo ( isset( $post->ID ) ) ? esc_attr( $post->ID ) : 0; ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-page-options' ) ); ?>">
		<div class="dashicons dashicons-admin-generic"></div>
	</div>

	<div class="ec_admin_preview_button"><a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>previewholder=true" target="_blank">Show Device Preview</a></div>

	<div class="ec_admin_page_size">Cart Options</div>
	<div><strong>Desktop Columns</strong></div>
	<div><select id="ec_option_cart_columns_desktop">
			<option value="0"<?php if ( get_option( 'ec_option_cart_columns_desktop' ) == "" ) {?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if ( get_option( 'ec_option_cart_columns_desktop' ) == "1" ) {?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if ( get_option( 'ec_option_cart_columns_desktop' ) == "2" ) {?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Tablet Landscape Columns</strong></div>
	<div><select id="ec_option_cart_columns_laptop">
			<option value="0"<?php if ( get_option( 'ec_option_cart_columns_laptop' ) == "" ) {?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if ( get_option( 'ec_option_cart_columns_laptop' ) == "1" ) {?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if ( get_option( 'ec_option_cart_columns_laptop' ) == "2" ) {?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Tablet Portfolio Columns</strong></div>
	<div><select id="ec_option_cart_columns_tablet_wide">
			<option value="0"<?php if ( get_option( 'ec_option_cart_columns_tablet_wide' ) == "" ) {?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if ( get_option( 'ec_option_cart_columns_tablet_wide' ) == "1" ) {?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if ( get_option( 'ec_option_cart_columns_tablet_wide' ) == "2" ) {?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Smartphone Landscape Columns</strong></div>
	<div><select id="ec_option_cart_columns_tablet">
			<option value="0"<?php if ( get_option( 'ec_option_cart_columns_tablet' ) == "" ) {?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if ( get_option( 'ec_option_cart_columns_tablet' ) == "1" ) {?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if ( get_option( 'ec_option_cart_columns_tablet' ) == "2" ) {?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Smartphone Portfolio Columns</strong></div>
	<div><select id="ec_option_cart_columns_smartphone">
			<option value="0"<?php if ( get_option( 'ec_option_cart_columns_smartphone' ) == "" ) {?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if ( get_option( 'ec_option_cart_columns_smartphone' ) == "1" ) {?> selected="selected"<?php }?>>1 Column</option>
			<option value="2"<?php if ( get_option( 'ec_option_cart_columns_smartphone' ) == "2" ) {?> selected="selected"<?php }?>>2 Columns</option>
	</select></div>
	<div><strong>Dark/Light Text</strong></div>
	<div><select id="ec_option_use_dark_bg">
			<option value="0"<?php if ( get_option( 'ec_option_use_dark_bg' ) == "" ) {?> selected="selected"<?php }?>>Select One</option>
			<option value="1"<?php if ( get_option( 'ec_option_use_dark_bg' ) == "1" ) {?> selected="selected"<?php }?>>White Text</option>
			<option value="0"<?php if ( get_option( 'ec_option_use_dark_bg' ) == "0" ) {?> selected="selected"<?php }?>>Dark Text</option>
	</select></div>

	<div><input type="button" value="APPLY AND SAVE" onclick="ec_admin_save_cart_options(); return false;" /></div>

	<div class="ec_editor_link_row"><a href="<?php echo esc_attr( get_admin_url() ); ?>admin.php?page=ec_adminv2&ec_page=store-setup&ec_panel=basic-settings#cart-settings" target="_blank">Edit Basic Cart Settings</a></div>

</div>

<script>
function ec_admin_save_cart_options() {
	jQuery( "#ec_admin_page_updated_loader" ).show();
	jQuery( "#ec_admin_loader_bg" ).show();
	var data = {
		action: 'ec_ajax_save_cart_options',
		ec_option_cart_columns_desktop: jQuery( '#ec_option_cart_columns_desktop' ).val(),
		ec_option_cart_columns_laptop: jQuery( '#ec_option_cart_columns_laptop' ).val(),
		ec_option_cart_columns_tablet_wide: jQuery( '#ec_option_cart_columns_tablet_wide' ).val(),
		ec_option_cart_columns_tablet: jQuery( '#ec_option_cart_columns_tablet' ).val(),
		ec_option_cart_columns_smartphone: jQuery( '#ec_option_cart_columns_smartphone' ).val(),
		ec_option_use_dark_bg: jQuery( '#ec_option_use_dark_bg' ).val(),
		nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-cart-options' ) ); ?>'
	}
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data) { 
		jQuery( "#ec_admin_page_updated_loader" ).hide();
		jQuery( "#ec_admin_page_updated" ).show();
		jQuery( "#ec_admin_loader_bg" ).fadeOut( 'slow' );
		location.reload();
	} } );
	jQuery( '#ec_page_editor' ).animate( { left:'-290px' }, {queue:false, duration:220} ).removeClass( 'ec_display_editor_true' ).addClass( 'ec_display_editor_false' );
}
</script>

<?php }// Close editor content ?>

<?php do_action( 'wp_easycart_subscription_top', $product ); ?>

<section class="ec_cart_page">

	<?php if ( $product->is_subscription_item && $product->trial_period_days > 0 ) { ?>
	<div class="ec_cart_success"><?php echo wp_easycart_language()->get_text( 'product_page', 'product_page_start_trial_1' ); ?> <?php echo esc_attr( $product->trial_period_days ); ?> <?php echo wp_easycart_language()->get_text( 'product_page', 'product_page_start_trial_2' ); ?></div>
	<?php }?>

	<div class="ec_cart_left">

		<div id="ec_cart_payment_one_column">

			<div class="ec_cart_header ec_top" style="color:#999 !important; margin-bottom:0px;">
				<?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_label' )?>
			</div>

			<div class="ec_cart_price_row ec_cart_price_row_subscription_title">
				<div class="ec_cart_price_row_label"><?php echo wp_easycart_escape_html( $product->title ); ?></div>
				<div class="ec_cart_price_row_total" id="ec_cart_subtotal_mobile"><?php echo esc_attr( $product->get_price_formatted( $subscription_quantity, $product->price ) ); ?></div>
			</div>

			<?php if ( $this->subscription_option1 != 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option1">
				<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option1_label ); ?></div>
				<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option1_name ); ?></div>
			</div>
			<?php if ( $subscription_option1->optionitem_price > 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option1">
				<div class="ec_cart_price_row_label"></div>
				<div class="ec_cart_price_row_total" id="ec_cart_option1_total_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option1->optionitem_price, $subscription_quantity ) ); ?></div>
			</div>
			<?php }?>
			<?php }?>

			<?php if ( $this->subscription_option2 != 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option2">
				<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option2_label ); ?></div>
				<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option2_name ); ?></div>
			</div>
			<?php if ( $subscription_option2->optionitem_price > 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option2">
				<div class="ec_cart_price_row_label"></div>
				<div class="ec_cart_price_row_total" id="ec_cart_option2_total_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option2->optionitem_price, $subscription_quantity ) ); ?></div>
			</div>
			<?php }?>
			<?php }?>

			<?php if ( $this->subscription_option3 != 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option3">
				<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option3_label ); ?></div>
				<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option3_name ); ?></div>
			</div>
			<?php if ( $subscription_option3->optionitem_price > 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option3">
				<div class="ec_cart_price_row_label"></div>
				<div class="ec_cart_price_row_total" id="ec_cart_option3_total_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option3->optionitem_price, $subscription_quantity ) ); ?></div>
			</div>
			<?php }?>
			<?php }?>

			<?php if ( $this->subscription_option4 != 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option4">
				<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option4_label ); ?></div>
				<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option4_name ); ?></div>
			</div>
			<?php if ( $subscription_option4->optionitem_price > 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option4">
				<div class="ec_cart_price_row_label"></div>
				<div class="ec_cart_price_row_total" id="ec_cart_option4_total_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option4->optionitem_price, $subscription_quantity ) ); ?></div>
			</div>
			<?php }?>
			<?php }?>

			<?php if ( $this->subscription_option5 != 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
				<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option5_label ); ?></div>
				<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option5_name ); ?> </div>   
			</div>
			<?php if ( $subscription_option5->optionitem_price > 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
				<div class="ec_cart_price_row_label"></div>
				<div class="ec_cart_price_row_total" id="ec_cart_option5_total_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option5->optionitem_price, $subscription_quantity ) ); ?></div>
			</div>
			<?php }?>
			<?php }?>

			<?php
			$subscription_advanced_options_display = apply_filters( 'wp_easycart_subscription_modifiers_display_list', $this->subscription_advanced_options, $product );
			if ( $subscription_advanced_options_display ) {
				foreach( $subscription_advanced_options_display as $option ) { ?>
				<div class="ec_cart_price_row ec_cart_price_row_subscription_adv_option">
					<div class="ec_cart_price_row_label"><?php echo esc_attr( $option['option_label'] ); ?></div>
					<div class="ec_cart_price_row_total"><?php 
					if ( $option['option_type'] == 'dimensions1' ) {
						echo esc_attr( $option['optionitem_value'][0] ); 
						if ( !get_option( 'ec_option_enable_metric_unit_display' ) ) { 
							echo "\"";
						}
						echo " x " . esc_attr( $option['optionitem_value'][1] ); 
						if ( !get_option( 'ec_option_enable_metric_unit_display' ) ) { 
							echo "\"";
						}
					} else if ( $option['option_type'] == 'dimensions2' ) {
						echo esc_attr( $option['optionitem_value'][0] . " " . $option['optionitem_value'][1] . "\" x " . $option['optionitem_value'][2] . " " . $option['optionitem_value'][3] ) . "\"";
					} else {
						echo esc_attr( $option['optionitem_value'] ); 
					} 
					?></div>   
				</div>
					<?php 
					$optionitem = $GLOBALS['ec_options']->get_optionitem( $option['optionitem_id'] );
					$optionitem_price = 0;
					if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) { ?>
						<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
							<div class="ec_cart_price_row_label"></div>
							<div class="ec_cart_price_row_total"><?php echo esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ); ?></div>
						</div>
					<?php } else if ( $optionitem && $optionitem->optionitem_price > 0 ) {
						if ( 'number' == $option['option_type'] ) {
							$optionitem_price = ( $optionitem->optionitem_price * (int) $option['optionitem_value'] );
						} else {
							$optionitem_price = $optionitem->optionitem_price;
						}
						if ( $optionitem_price > 0 ) { ?>
						<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
							<div class="ec_cart_price_row_label"></div>
							<div class="ec_cart_price_row_total"><?php echo esc_attr( $product->get_option_price_formatted( $optionitem_price, $subscription_quantity ) ); ?></div>
						</div>
						<?php }
					} else if ( $optionitem && $optionitem->optionitem_price_onetime > 0 ) {
						if ( 'number' == $option['option_type'] ) {
							$optionitem_price = ( $optionitem->optionitem_price_onetime * (int) $option['optionitem_value'] );
						} else {
							$optionitem_price = $optionitem->optionitem_price_onetime;
						}
						if ( $optionitem_price > 0 ) { ?>
						<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
							<div class="ec_cart_price_row_label"></div>
							<div class="ec_cart_price_row_total"><?php echo esc_attr( $product->get_option_price_formatted( $optionitem_price, 1 ) ); ?></div>
						</div>
						<?php }
					}
				}
			}?>

			<div class="ec_cart_price_row ec_cart_price_row_discount_total<?php if ( $discount_amount == 0 ) { ?> ec_no_discount<?php } else { ?> ec_has_discount<?php }?>">
				<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_discounts' )?></div>
				<div class="ec_cart_price_row_total" id="ec_cart_discount_mobile"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( (-1)*$discount_amount ) ); ?></div>
			</div>

			<?php if ( $product->is_taxable ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_tax_total"<?php if ( $tax_total <= 0 ) { ?> style="display:none;"<?php }?> id="ec_cart_tax_row_mobile">
				<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_tax' )?></div>
				<div class="ec_cart_price_row_total" id="ec_cart_tax_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $tax_total, 1 ) ); ?></div>
			</div>
			<?php }?>

			<?php if ( $product->vat_rate > 0 ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_vat_total"<?php if ( $vat_total <= 0 ) { ?> style="display:none;"<?php }?> id="ec_cart_vat_row_mobile">
				<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_vat' ); ?> <span id="ec_cart_vat_rate_mobile"<?php echo ( $vat_total <= 0 ) ? ' style="display:none;"' : ''; ?>><?php echo esc_attr( $this->get_vat_rate_formatted( $vat_rate ) ); ?></span></div>
				<div class="ec_cart_price_row_total" id="ec_cart_vat_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $vat_total, 1 ) ); ?></div>
			</div>
			<?php }?>

			<?php if ( get_option( 'ec_option_enable_easy_canada_tax' ) ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_gst_total" id="ec_cart_gst_row_mobile"<?php if ( $gst_total <= 0 ) { ?> style="display:none"<?php }?>>
				<div class="ec_cart_price_row_label">GST (<span id="ec_cart_gst_rate_mobile"><?php echo esc_attr( $gst_rate ); ?></span>%)</div>
				<div class="ec_cart_price_row_total" id="ec_cart_gst_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $gst_total, 1 ) ); ?></div>
			</div>
			<?php }?>
			<?php if ( get_option( 'ec_option_enable_easy_canada_tax' ) ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_pst_total" id="ec_cart_pst_row_mobile"<?php if ( $pst_total <= 0 ) { ?> style="display:none"<?php }?>>
				<div class="ec_cart_price_row_label">PST (<span id="ec_cart_pst_rate_mobile"><?php echo esc_attr( $pst_rate ); ?></span>%)</div>
				<div class="ec_cart_price_row_total" id="ec_cart_pst_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $pst_total, 1 ) ); ?></div>
			</div>
			<?php }?>
			<?php if ( get_option( 'ec_option_enable_easy_canada_tax' ) ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_hst_total" id="ec_cart_hst_row_mobile"<?php if ( $hst_total <= 0 ) { ?> style="display:none"<?php }?>>
				<div class="ec_cart_price_row_label">HST (<span id="ec_cart_hst_rate_mobile"><?php echo esc_attr( $hst_rate ); ?></span>%)</div>
				<div class="ec_cart_price_row_total" id="ec_cart_hst_mobile"><?php echo esc_attr( $product->get_option_price_formatted( $hst_total, 1 ) ); ?></div>
			</div>
			<?php }?>
		
			<?php if( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ){ ?>
			<div class="ec_cart_price_row ec_cart_price_row_shipping_total" id="ec_cart_shipping_row_mobile"<?php if( ! $this->shipping->has_shipping_rates() ){ ?> style="display:none;"<?php }?>>
				<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' )?></div>
				<div class="ec_cart_price_row_total" id="ec_cart_shipping_mobile"><?php echo esc_attr( ( $product->subscription_shipping_recurring ) ? $product->get_option_price_formatted( $shipping_total, 1 ) : $GLOBALS['currency']->get_currency_display( $shipping_total ) ); ?></div>
			</div>
			<?php }?>

			<div class="ec_cart_price_row ec_cart_price_row_grand_total">
				<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_grand_total' )?></div>
				<div class="ec_cart_price_row_total" id="ec_cart_total_mobile"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $grand_total ) ); ?></div>
			</div>

			<?php if ( $product->subscription_signup_fee > 0 ) { ?>
			<div class="ec_cart_price_row_total ec_cart_price_row_fees">
				<?php echo wp_easycart_language()->get_text( 'product_details', 'product_details_signup_fee_notice1' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $product->subscription_signup_fee * $subscription_quantity ) ); ?> <?php echo wp_easycart_language()->get_text( 'product_details', 'product_details_signup_fee_notice2' ); ?>
			</div>
			<?php }?>

			<?php if ( !get_option( 'ec_option_subscription_one_only' ) ) { ?>
			<form action="<?php echo esc_attr( $this->cart_page ); ?>" method="POST" enctype="multipart/form-data" class="ec_add_to_cart_form">
			<input type="hidden" name="ec_cart_form_action" value="process_update_subscription_quantity" />
			<input type="hidden" name="ec_cart_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-cart-subscription-update-item-' . $product->product_id ) ); ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->product_id ); ?>" />
			<table class="ec_cartitem_quantity_table ec_subscription_table">
				<tbody>
					<tr>
						<td class="ec_minus_column">
							<input type="button" value="-" class="ec_minus" onclick="ec_minus_quantity( '<?php echo esc_attr( $product->product_id ); ?>', <?php echo esc_attr( $product->min_purchase_quantity ); ?> );" /></td>
						<td class="ec_quantity_column"><input type="number" value="<?php echo esc_attr( $subscription_quantity ); ?>" id="ec_quantity_<?php echo esc_attr( $product->product_id ); ?>" name="ec_quantity" autocomplete="off" step="1" min="<?php if ( $product->min_purchase_quantity > 0 ) { echo esc_attr( $product->min_purchase_quantity ); } else { echo '1'; } ?>" class="ec_quantity" /></td>
						<td class="ec_plus_column"><input type="button" value="+" class="ec_plus" onclick="ec_plus_quantity( '<?php echo esc_attr( $product->product_id ); ?>', <?php echo esc_attr( $product->show_stock_quantity ); ?>, <?php if ( $product->max_purchase_quantity > 0 ) { echo esc_attr( $product->max_purchase_quantity ); } else if ( $product->show_stock_quantity ) { echo esc_attr( $product->stock_quantity ); } else { echo '10000000'; } ?> );" /></td>
					</tr>
					<tr>
						<td colspan="3"><input type="submit" class="ec_cartitem_update_button" id="ec_cartitem_update_<?php echo esc_attr( $product->product_id ); ?>" value="<?php echo wp_easycart_language()->get_text( 'cart', 'cart_item_update_button' )?>" /></td>
					</tr>
				</tbody>
			</table>
			</form>
			<?php }?>

			<?php if ( get_option( 'ec_option_show_coupons' ) ) { ?>
			<div class="ec_cart_header">
				<?php echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_coupon_title' )?>
			</div>

			<div class="ec_cart_error_message" id="ec_coupon_error_mobile"></div>
			<div class="ec_cart_success_message" id="ec_coupon_success_mobile"<?php if ( isset( $this->coupon ) ) {?> style="display:block;"<?php }?>><?php if ( isset( $this->coupon ) ) { if ( $this->discount->coupon_matches <= 0 ) { echo wp_easycart_language()->get_text( 'cart_coupons', 'coupon_not_applicable' ); } else { echo wp_easycart_language()->convert_text( $this->coupon->message ); } } ?></div>
			<div class="ec_cart_input_row">
				<input type="text" name="ec_coupon_code_mobile" id="ec_coupon_code_mobile" value="<?php if ( isset( $this->coupon ) ) { echo esc_attr( $this->coupon_code ); } ?>" placeholder="<?php echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_enter_coupon' )?>" />
			</div>
			<div class="ec_cart_button_row">
				<div class="ec_cart_button" id="ec_apply_coupon_mobile" onclick="ec_apply_subscription_coupon( '<?php echo esc_attr( $product->product_id ); ?>', '<?php echo esc_attr( $product->manufacturer_id ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-subscription-coupon-code-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>', true );"><?php echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_apply_coupon' ); ?></div>
				<div class="ec_cart_button_working" id="ec_applying_coupon_mobile"><?php echo wp_easycart_language()->get_text( 'cart', 'cart_please_wait' )?></div>
			</div>
			<?php }?>

		</div>

		<?php $this->display_subscription_form_start( $product->model_number ); ?>

		<?php if ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id ) { ?>
		
		<div id="ec_cart_create_account_loader" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
			<style>
			@keyframes rotation{
				0%  { transform:rotate(0deg); }
				100%{ transform:rotate(359deg); }
			}
			</style>
			<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; background:#EFEFEF; border-radius:10px; padding:25px;'>
				<div class="paypal-checkout-loader">
					<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
				</div>
				<div style="float:left; width:100%; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif; margin-top:10px; color:#222; font-size:18px;"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'create_account_please_wait' )?></div>
			</div>
		</div>

		<div class="ec_cart_header ec_top" id="ec_user_logged_out_header">
			<?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_new_customer_title' ); ?><span class="ec_cart_login_header_link"><span id="ec_user_login_link"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_already_have_account2' ); ?><a href="#" onclick="return ec_cart_toggle_login_v2();" id="ec_user_login_link"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_already_have_account_login' ); ?></a></span><a href="#" style="display:none;" onclick="return ec_cart_toggle_login_v2();" id="ec_user_login_cancel_link"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_new_customer_return_link' ); ?></a></span>
		</div>
		
		<div id="ec_user_contact_form">
			<div class="ec_cart_error" id="ec_subscription_email_exists" style="display:none;">
				<div>
					<?php echo wp_easycart_language( )->get_text( "ec_errors", "register_email_error" ); ?>
				</div>
			</div>

			<div class="ec_cart_input_row">
				<label for="ec_contact_email"><?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>*</label>
				<?php $this->ec_cart_display_contact_email_input(); ?>
				<div class="ec_cart_error_row" id="ec_contact_email_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
				</div>
			</div>

			<?php if ( get_option( 'ec_option_use_contact_name' ) ) { ?>
			<div class="ec_cart_input_row">
				<div class="ec_cart_input_left_half">
					<label for="ec_contact_first_name"><?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>*</label>
					<?php $this->ec_cart_display_contact_first_name_input(); ?>
					<div class="ec_cart_error_row" id="ec_contact_first_name_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>
					</div>
				</div>
				<div class="ec_cart_input_right_half">
					<label for="ec_contact_last_name"><?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>*</label>
					<?php $this->ec_cart_display_contact_last_name_input(); ?>
					<div class="ec_cart_error_row" id="ec_contact_last_name_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>
					</div>
				</div>
			</div>
			<?php }?>

			<div class="ec_cart_input_row">
				<?php do_action( 'wpeasycart_pre_password_display' ); ?>
				<label for="ec_contact_password"><?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_password' ); ?>*</label>
				<?php $this->ec_cart_display_contact_password_input(); ?>
				<div class="ec_cart_error_row" id="ec_contact_password_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_length_error' ); ?>
				</div>
			</div>

			<div class="ec_cart_button_row">
				<div class="ec_cart_button" id="ec_address_save" onclick="return subscription_create_account( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-subscription-create-account-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language()->get_text( 'cart_contact_information', 'cart_contact_information_create_account' )?></div>
				<div class="ec_cart_button_working" id="ec_address_save_working"><?php echo wp_easycart_language()->get_text( 'cart', 'cart_please_wait' )?></div>
			</div>
		</div>

		<div id="ec_cart_login_loader" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
			<style>
			@keyframes rotation{
				0%  { transform:rotate(0deg); }
				100%{ transform:rotate(359deg); }
			}
			</style>
			<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; background:#EFEFEF; border-radius:10px; padding:25px;'>
				<div class="paypal-checkout-loader">
					<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
				</div>
				<div style="float:left; width:100%; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif; margin-top:10px; color:#222; font-size:18px;"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'login_please_wait' )?></div>
			</div>
		</div>

		<div id="ec_user_login_form">
			<input type="checkbox" name="ec_login_selector" id="ec_login_selector" value="1" style="display:none" />
			<div class="ec_cart_input_row">
				<label for="ec_cart_login_email"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_email_label' ); ?>*</label>
				<input type="text" id="ec_cart_login_email" name="ec_cart_login_email" />
			</div>
			<div class="ec_cart_error_row" id="ec_cart_login_email_error">
				<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_email_label' ); ?>
			</div>

			<div class="ec_cart_input_row">
				<?php do_action( 'wpeasycart_pre_login_password_display' ); ?>
				<label for="ec_cart_login_password"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_password_label' ); ?>*</label>
				<input type="password" id="ec_cart_login_password" name="ec_cart_login_password" />
			</div>
			<div class="ec_cart_error_row" id="ec_cart_login_password_error">
				<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_password_label' ); ?>
			</div>

			<div class="ec_cart_button_row">
				<input type="hidden" name="ec_cart_subscription" value="<?php echo esc_attr( $product->model_number ); ?>" />
				<input type="submit" value="<?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_button' ); ?>" class="ec_cart_button" onclick="return ec_validate_cart_login();" />
			</div>

			<div class="ec_cart_input_row">
				<a href="<?php echo esc_attr( $this->account_page ); ?>?ec_page=forgot_password" class="ec_account_login_link"><?php echo wp_easycart_language()->get_text( 'account_login', 'account_login_forgot_password_link' ); ?></a>
			</div>

		</div>

		<div id="ec_user_logged_in_form" style="display:none;">
			<div class="ec_cart_header ec_top">
				<?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_title' ); ?>
			</div>

			<div class="ec_cart_input_row">
				<?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_account_information_text' ); ?> <span id="ec_cart_user_logged_in_name"></span>, <a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider . "ec_cart_action=logout&subscription=" . $product->model_number ); ?>"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_account_information_logout_link' ); ?></a> <?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_account_information_text2' ); ?>
			</div>
		</div>

		<?php } else { // close section for NON logged in user ?>

		<div class="ec_cart_header ec_top">
			<?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_title' ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_account_information_text' ); ?> <?php echo esc_attr( $GLOBALS['ec_user']->first_name ); ?> <?php echo esc_attr( $GLOBALS['ec_user']->last_name ); ?>, <a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider . "ec_cart_action=logout&subscription=" . $product->model_number ); ?>"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_account_information_logout_link' ); ?></a> <?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_login_account_information_text2' ); ?>
		</div>

		<?php }?>

		<div id="ec_cart_address_loader" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
			<style>
			@keyframes rotation{
				0%  { transform:rotate(0deg); }
				100%{ transform:rotate(359deg); }
			}
			</style>
			<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; background:#EFEFEF; border-radius:10px; padding:25px;'>
				<div class="paypal-checkout-loader">
					<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
				</div>
				<div style="float:left; width:100%; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif; margin-top:10px; color:#222; font-size:18px;"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'address_please_wait' )?></div>
			</div>
		</div>

		<div id="ec_cart_billing_locked" class="ec_cart_address_display ec_cart_address_display_billing"<?php if ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id || '' == $GLOBALS['ec_cart_data']->cart_data->billing_first_name || '' == $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 || '' == $GLOBALS['ec_cart_data']->cart_data->billing_city ) { ?> style="display:none"<?php }?>>
			<div class="ec_cart_address_display_header">
				<?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
			</div>
			<div class="ec_cart_address_display_line" id="ec_cart_billing_address_display"><?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_first_name . ' ' . $GLOBALS['ec_cart_data']->cart_data->billing_last_name ); ?>, <?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_company_name . ', ' : '' ) ); ?><?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ); ?>, <?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 . ', ' : '' ) ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_city ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_state ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_zip ); ?>, <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_country ); ?><?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_phone ) ? ', ' . $GLOBALS['ec_cart_data']->cart_data->billing_phone : '' ) ); ?></div>
			<div class="ec_cart_address_change"><a href="#" onclick="return ec_cart_toggle_address_edit();">Change Address</a></div>
		</div>

		<?php if( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ){ ?>
			<?php if ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" ) { ?>
			<div id="ec_cart_shipping_locked" class="ec_cart_address_display"<?php if ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id || '' == $GLOBALS['ec_cart_data']->cart_data->billing_first_name || '' == $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 || '' == $GLOBALS['ec_cart_data']->cart_data->billing_city ) { ?> style="display:none"<?php }?>>
				<div class="ec_cart_address_display_header">
					<?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
				</div>
				<div class="ec_cart_address_display_line" id="ec_cart_shipping_address_display"><?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name . ' ' . $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ); ?>, <?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_company_name . ', ' : '' ) ); ?><?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ); ?>, <?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 . ', ' : '' ) ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_city ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_state ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ); ?>, <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_country ); ?><?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) ? ', ' . $GLOBALS['ec_cart_data']->cart_data->shipping_phone : '' ) ); ?></div>
				<div class="ec_cart_address_change"><a href="#" onclick="return ec_cart_toggle_address_edit();">Change Address</a></div>
			</div>
			<?php } else { ?>
			<div id="ec_cart_shipping_locked" class="ec_cart_address_display"<?php if ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id || '' == $GLOBALS['ec_cart_data']->cart_data->billing_first_name || '' == $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 || '' == $GLOBALS['ec_cart_data']->cart_data->billing_city ) { ?> style="display:none"<?php }?>>
				<div class="ec_cart_address_display_header">
					<?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
				</div>
				<div class="ec_cart_address_display_line" id="ec_cart_shipping_address_display"><?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_first_name . ' ' . $GLOBALS['ec_cart_data']->cart_data->billing_last_name ); ?>, <?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_company_name . ', ' : '' ) ); ?><?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ); ?>, <?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 . ', ' : '' ) ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_city ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_state ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_zip ); ?>, <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_country ); ?><?php echo esc_attr( ( ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_phone ) ? ', ' . $GLOBALS['ec_cart_data']->cart_data->billing_phone : '' ) ); ?></div>
				<div class="ec_cart_address_change"><a href="#" onclick="return ec_cart_toggle_address_edit();">Change Address</a></div>
			</div>
			<?php }?>
		<?php }?>
		
		<div id="ec_cart_billing_form"<?php if ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id || ( '' != $GLOBALS['ec_cart_data']->cart_data->user_id && '' != $GLOBALS['ec_cart_data']->cart_data->billing_first_name && '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 && '' != $GLOBALS['ec_cart_data']->cart_data->billing_city ) ) { ?> style="display:none"<?php }?>>
			<div class="ec_cart_header">
				<?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
			</div>

			<?php if ( get_option( 'ec_option_display_country_top' ) ) { ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_country"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
				<?php $this->display_billing_input( "country" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
				</div>
			</div>
			<?php }?>

			<div class="ec_cart_input_row">
				<div class="ec_cart_input_left_half">
					<label for="ec_cart_billing_first_name"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>*</label>
					<?php $this->display_billing_input( "first_name" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_billing_first_name_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>
					</div>
				</div>
				<div class="ec_cart_input_right_half">
					<label for="ec_cart_billing_last_name"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>*</label>
					<?php $this->display_billing_input( "last_name" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_billing_last_name_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>
					</div>
				</div>
			</div>

			<?php if ( get_option( 'ec_option_enable_company_name' ) ) { ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_company_name"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ); ?></label>
				<?php $this->display_billing_input( "company_name" ); ?>
			</div>
			<?php }?>

			<?php if ( get_option( 'ec_option_collect_vat_registration_number' ) ) { ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_vat_registration_number"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?></label>
				<?php $this->display_vat_registration_number_input(); ?>
			</div>
			<?php }?>

			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_address"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>*</label>
				<?php $this->display_billing_input( "address" ); ?>
			</div>
			<div class="ec_cart_error_row" id="ec_cart_billing_address_error">
				<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>
			</div>

			<?php if ( get_option( 'ec_option_use_address2' ) ) { ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_address2"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address2' ); ?></label>
				<?php $this->display_billing_input( "address2" ); ?>
			</div>
			<?php }?>

			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_city"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>*</label>
				<?php $this->display_billing_input( "city" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_city_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_row">
				<div class="ec_cart_input_left_half">
					<label for="ec_cart_billing_state"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?><span id="ec_billing_state_required">*</span></label>
					<?php $this->display_billing_input( "state" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_billing_state_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?>
					</div>
				</div>
				<div class="ec_cart_input_right_half">
					<label for="ec_cart_billing_zip"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>*</label>
					<?php $this->display_billing_input( "zip" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_billing_zip_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>
					</div>
				</div>
			</div>

			<?php if ( !get_option( 'ec_option_display_country_top' ) ) { ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_country"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
				<?php $this->display_billing_input( "country" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
				</div>
			</div>
			<?php }?>

			<?php if ( get_option( 'ec_option_collect_user_phone' ) ) { ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_billing_phone"><?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?>*</label>
				<?php $this->display_billing_input( "phone" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_phone_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?>
				</div>
			</div>
			<?php }?>

			<?php if( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ){ ?>
			<div class="ec_cart_header">
				<input type="checkbox" name="ec_shipping_selector" id="ec_shipping_selector" value="true" onchange="ec_update_shipping_view();"<?php if ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" ) {?> checked="checked"<?php }?> /> <?php echo wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_ship_to_different' ); ?>
			</div>
			<div id="ec_shipping_form"<?php if ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" ) {?> style="display:block;"<?php }?>>
				<?php if ( get_option( 'ec_option_display_country_top' ) ) { ?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_country"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>*</label>
					<?php $this->display_shipping_input( "country" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_shipping_country_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>
					</div>
				</div>
				<?php }?>
				<div class="ec_cart_input_row">
					<div class="ec_cart_input_left_half">
						<label for="ec_cart_shipping_first_name"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_first_name' ); ?>*</label>
						<?php $this->display_shipping_input( "first_name" ); ?>
						<div class="ec_cart_error_row" id="ec_cart_shipping_first_name_error">
							<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_first_name' ); ?>
						</div>
					</div>
					<div class="ec_cart_input_right_half">
						<label for="ec_cart_shipping_last_name"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_last_name' ); ?>*</label>
						<?php $this->display_shipping_input( "last_name" ); ?>
						<div class="ec_cart_error_row" id="ec_cart_shipping_last_name_error">
							<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_last_name' ); ?>
						</div>
					</div>
				</div>
				<?php if ( get_option( 'ec_option_enable_company_name' ) ) { ?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_company_name"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_company_name' ); ?></label>
					<?php $this->display_shipping_input( "company_name" ); ?>
				</div>
				<?php }?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_address"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_address' ); ?>*</label>
					<?php $this->display_shipping_input( "address" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_shipping_address_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_address' ); ?>
					</div>
				</div>
				<?php if ( get_option( 'ec_option_use_address2' ) ) { ?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_address2"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address2' ); ?></label>
					<?php $this->display_shipping_input( "address2" ); ?>
				</div>
				<?php }?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_city"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_city' ); ?>*</label>
					<?php $this->display_shipping_input( "city" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_shipping_city_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_city' ); ?>
					</div>
				</div>
				<div class="ec_cart_input_row">
					<div class="ec_cart_input_left_half">
						<label for="ec_cart_shipping_state"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_state' ); ?><span id="ec_shipping_state_required">*</span></label>
						<?php $this->display_shipping_input( "state" ); ?>
						<div class="ec_cart_error_row" id="ec_cart_shipping_state_error">
							<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_state' ); ?>
						</div>
					</div>
					<div class="ec_cart_input_right_half">
						<label for="ec_cart_shipping_zip"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_zip' ); ?>*</label>
						<?php $this->display_shipping_input( "zip" ); ?>
						<div class="ec_cart_error_row" id="ec_cart_shipping_zip_error">
							<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_zip' ); ?>
						</div>
					</div>
				</div>
				<?php if ( !get_option( 'ec_option_display_country_top' ) ) { ?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_country"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>*</label>
					<?php $this->display_shipping_input( "country" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_shipping_country_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>
					</div>
				</div>
				<?php }?>
				<?php if ( get_option( 'ec_option_collect_user_phone' ) ) { ?>
				<div class="ec_cart_input_row">
					<label for="ec_cart_shipping_phone"><?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_phone' ); ?>*</label>
					<?php $this->display_shipping_input( "phone" ); ?>
					<div class="ec_cart_error_row" id="ec_cart_shipping_phone_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_phone' ); ?>
					</div>
				</div>
				<?php }?>
			</div>

			<?php } // Close if use shipping ?>
				
			<div class="ec_cart_button_row">
				<div class="ec_cart_button" id="ec_address_save" onclick="return update_subscription_totals( '<?php echo esc_attr( $product->product_id ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-subscription-tax-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language()->get_text( 'cart_login', 'cart_subscription_addresss_continue' )?></div>
				<div class="ec_cart_button_working" id="ec_address_save_working"><?php echo wp_easycart_language()->get_text( 'cart', 'cart_please_wait' )?></div>
			</div>
		</div>
		
		<div id="ec_cart_subscription_end_form"<?php if ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id || ( '' == $GLOBALS['ec_cart_data']->cart_data->user_id || '' == $GLOBALS['ec_cart_data']->cart_data->billing_first_name || '' == $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 || '' == $GLOBALS['ec_cart_data']->cart_data->billing_city ) ) { ?> style="display:none"<?php }?>>

			<?php if ( get_option( 'ec_option_user_order_notes' ) ) { ?>
			<div class="ec_cart_header">
				<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
			</div>
			<div class="ec_cart_input_row">
				<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_message' ); ?>
				<textarea name="ec_order_notes" id="ec_order_notes"><?php if ( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" ) { echo esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes ); } ?></textarea>
			</div>
			<?php }?>

			<?php if( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ){ ?>
			<div id="ec_cart_subscription_shipping_methods_loader" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
				<style>
				@keyframes rotation{
					0%  { transform:rotate(0deg); }
					100%{ transform:rotate(359deg); }
				}
				</style>
				<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; background:#EFEFEF; border-radius:10px; padding:25px;'>
					<div class="paypal-checkout-loader">
						<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
					</div>
					<div style="float:left; width:100%; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif; margin-top:10px; color:#222; font-size:18px;"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'shipping_methods_please_wait' )?></div>
				</div>
			</div>
			<div class="ec_cart_header">
				<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
			</div>
			<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_please_select_one' ); ?>
			</div>
			<div class="ec_cart_input_row" id="ec_cart_subscription_shipping_methods">
				<?php $this->ec_cart_display_shipping_methods( wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language( )->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ), "RADIO" ); ?>
			</div>
			<?php } ?>

			<div class="ec_cart_header">
				<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_payment_method' ); ?>
			</div>

			<?php if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' && get_option( 'ec_option_stripe_public_api_key' ) != "" ) || ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ) { ?>
				<div class="form-row" style="margin-top:12px;float:left;width:100%;">
					<div id="ec_stripe_card_row">
					  <!-- a Stripe Element will be inserted here. -->
					</div>

					<!-- Used to display form errors -->
					<div id="ec_card_errors" role="alert" style="color:rgb(181, 41, 41); float:left; width:100%; margin-top:5px; text-align:center; background:rgb(241, 241, 241);"></div>
				</div>

				<div id="stripe-success-cover" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
					<style>
					@keyframes rotation{
						0%  { transform:rotate(0deg); }
						100%{ transform:rotate(359deg); }
					}
					</style>
					<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center; background:#EFEFEF; border-radius:10px; padding:25px;'>
						<div class="paypal-checkout-loader">
							<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
						</div>
						<div style="float:left; width:100%; font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif; margin-top:10px; color:#222; font-size:18px;"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_please_wait' )?></div>
					</div>
				</div>
				<script><?php
					if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
						$pkey = get_option( 'ec_option_stripe_public_api_key' );
					else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) )
						$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
					else
						$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );	
					?>
					jQuery( document.getElementById( 'stripe-success-cover' ) ).appendTo( document.body );
					try {
						var stripe = Stripe( '<?php echo esc_attr( $pkey ); ?>' );
						var elements = stripe.elements();
						var style = {
							base: {
								color: '#32325d',
								fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
								fontSmoothing: 'antialiased',
								fontSize: '16px',
								'::placeholder': {
								  color: '#aab7c4'
								}
							},
							invalid: {
								color: '#fa755a',
								iconColor: '#fa755a'
							}
						};
						var card = elements.create( 'card', {style: style, hidePostalCode: true} );
						card.mount( '#ec_stripe_card_row' );
						card.addEventListener( 'change', function( event ) {
							var displayError = document.getElementById( 'ec_card_errors' );
							if ( event.error ) {
								displayError.textContent = event.error.message;
							} else {
								displayError.textContent = '';
							}<?php do_action( 'wp_easycart_stripe_subscription_onchange', $product ); ?>
						} );
						var form = document.getElementById( 'ec_submit_order_form' );
						form.addEventListener( 'submit', function( event ) {
							if ( jQuery( document.getElementById( 'ec_user_login_form' ) ).is( ':visible' ) ) {
								jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show();
								jQuery( document.getElementById( 'ec_cart_login_loader' ) ).show();
							} else {
								var payment_method = "credit_card";
								event.preventDefault();
								jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide();
								jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show();
								jQuery( document.getElementById( 'stripe-success-cover' ) ).show();
								jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).hide();
								jQuery( document.getElementById( 'ec_card_errors' ) ).hide();
								var first_name = jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).val();
								var last_name = jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).val();
								var name = first_name + ' ' + last_name;
								var company_name = '';
								if ( jQuery( document.getElementById( 'ec_cart_billing_company_name' ) ).length ) {
									company_name = jQuery( document.getElementById( 'ec_cart_billing_company_name' ) ).val();
								}
								var vat_registration_number = '';
								if ( jQuery( document.getElementById( 'ec_cart_shipping_vat_registration_number' ) ).length ) {
									vat_registration_number = jQuery( document.getElementById( 'ec_cart_shipping_vat_registration_number' ) ).val();
								}
								var address1 = jQuery( document.getElementById( 'ec_cart_billing_address' ) ).val();
								var address2 = '';
								if ( jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).length ) {
									address2 = jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).val();
								}
								var city = jQuery( document.getElementById( 'ec_cart_billing_city' ) ).val();
								var state = jQuery( document.getElementById( 'ec_cart_billing_state' ) ).val();
								if ( jQuery( document.getElementById( 'ec_cart_billing_state_' + jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val() ) ).length ) {
									state = jQuery( document.getElementById( 'ec_cart_billing_state_' + jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val() ) ).val();
								}
								var zip = jQuery( document.getElementById( 'ec_cart_billing_zip' ) ).val();
								var country = jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val();
								var email = '<?php echo ( $GLOBALS['ec_user']->user_id ) ? esc_attr( $GLOBALS['ec_user']->email ) : ''; ?>';
								if ( jQuery( document.getElementById( 'ec_contact_email' ) ).length ) {
									email = jQuery( document.getElementById( 'ec_contact_email' ) ).val();
								}
								var phone = '';
								if ( jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).length ) {
									phone = jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).val();
								}
								var shipping_first_name = '';
								var shipping_last_name = '';
								var shipping_name = shipping_first_name + ' ' + shipping_last_name;
								var shipping_company_name = '';
								var shipping_address1 = '';
								var shipping_address2 = '';
								var shipping_city = '';
								var shipping_state = '';
								var shipping_zip = '';
								var shipping_country = '';
								var shipping_phone = '';
								if ( jQuery( document.getElementById( 'ec_shipping_selector' ) ).length && jQuery( document.getElementById( 'ec_shipping_selector' ) ).is( ':checked' ) ) {
									if ( jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).length ) {
										shipping_first_name = jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).length ) {
										shipping_last_name = jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).val();
									}
									shipping_name = shipping_first_name + ' ' + shipping_last_name;
									if ( jQuery( document.getElementById( 'ec_cart_shipping_company_name' ) ).length ) {
										shipping_company_name = jQuery( document.getElementById( 'ec_cart_shipping_company_name' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_address' ) ).length ) {
										shipping_address1 = jQuery( document.getElementById( 'ec_cart_shipping_address' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_address2' ) ).length ) {
										shipping_address2 = jQuery( document.getElementById( 'ec_cart_shipping_address2' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_city' ) ).length ) {
										shipping_city = jQuery( document.getElementById( 'ec_cart_shipping_city' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_state' ) ).length ) {
										jQuery( document.getElementById( 'ec_cart_shipping_state' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_state_' + jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val() ) ).length ) {
										shipping_state = jQuery( document.getElementById( 'ec_cart_shipping_state_' + jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val() ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_zip' ) ).length ) {
										shipping_zip = jQuery( document.getElementById( 'ec_cart_shipping_zip' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).length ) {
										shipping_country = jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val();
									}
									if ( jQuery( document.getElementById( 'ec_cart_shipping_phone' ) ).length ) {
										shipping_phone = jQuery( document.getElementById( 'ec_cart_shipping_phone' ) ).val();
									}
								} else {
									shipping_first_name = first_name;
									shipping_last_name = last_name;
									shipping_name = shipping_first_name + ' ' + shipping_last_name;
									shipping_company_name = company_name;
									shipping_address1 = address1;
									shipping_address2 = address2;
									shipping_city = city;
									shipping_state = state;
									shipping_zip = zip;
									shipping_country = country;
									shipping_phone = phone;
								}
								var additionalData = {
									name: name,
									address_line1: address1,
									address_city: city,
									address_state: state,
									address_zip: zip
								};
								var coupon_code = '';
								if ( jQuery( document.getElementById( 'ec_coupon_code' ) ).length ) {
									coupon_code = jQuery( document.getElementById( 'ec_coupon_code' ) ).val();
								}
								var model_number = jQuery( document.getElementById( 'ec_cart_model_number' ) ).val();
								var order_notes = '';
								if ( jQuery( document.getElementById( 'ec_order_notes' ) ).length ) {
									order_notes = jQuery( document.getElementById( 'ec_order_notes' ) ).val();
								}
								var is_subscriber = '';
								if ( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).length ) {
									is_subscriber = 0;
									if ( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ) {
										is_subscriber = 1;
									}
								}
								var ec_terms_agree = 0;
								if ( jQuery( document.getElementById( 'ec_terms_agree' ) ).length && jQuery( document.getElementById( 'ec_terms_agree' ) ).is( ':checked' ) ) {
									ec_terms_agree = 1;
								}
								var ec_cart_is_subscriber = 0;
								if ( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).length && jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ) {
									ec_cart_is_subscriber = 1;
								}
								stripe.createToken( card, additionalData ).then( function( result ) {
									if ( result.error ) {
										var errorElement = document.getElementById( 'ec_card_errors' );
										errorElement.textContent = result.error.message;
										jQuery( document.getElementById( 'ec_submit_order_error' ) ).show();
										jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show();
										jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide();
										jQuery( document.getElementById( 'stripe-success-cover' ) ).hide();
									} else {
										var token = result.token;
										var ec_quantity = 1;
										if ( jQuery( document.getElementById( 'ec_quantity_<?php echo esc_attr( $product->product_id ); ?>' ) ).length ) {
											ec_quantity = jQuery( document.getElementById( 'ec_quantity_<?php echo esc_attr( $product->product_id ); ?>' ) ).val();
										}
										var billing_details = {
											first_name: first_name,
											last_name: last_name,
											company_name: company_name,
											address: {
												city: city,
												country: country,
												line1: address1,
												line2: address2,
												postal_code: zip,
												state: state
											},
											email: email,
											name: name,
											phone: phone
										};
										var shipping_details = {
											first_name: shipping_first_name,
											last_name: shipping_last_name,
											company_name: shipping_company_name,
											address: {
												city: shipping_city,
												country: shipping_country,
												line1: shipping_address1,
												line2: shipping_address2,
												postal_code: shipping_zip,
												state: shipping_state
											},
											email: email,
											name: shipping_name,
											phone: shipping_phone
										};
										var data = {
											action: 'ec_ajax_get_stripe_create_subscription',
											language: wpeasycart_ajax_object.current_language,
											billing_details: billing_details,
											shipping_details: shipping_details,
											coupon_code: coupon_code,
											model_number: model_number,
											order_notes: order_notes,
											ec_quantity: ec_quantity,
											vat_registration_number: vat_registration_number,
											is_subscriber: is_subscriber,
											stripeToken: token.id,
											nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-create-subscription-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
										};
										var card_info = {
											'name': result.token.card.name,
											'last4': result.token.card.last4,
											'exp_month': result.token.card.exp_month,
											'exp_year': result.token.card.exp_year,
											'cvv': '',
											'brand': result.token.card.brand
										}
										jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
											subscription_result = JSON.parse( result );
											if ( !result || subscription_result.error ) {
												jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show();
												jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide();
												jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut();
												jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).fadeIn().find( 'div' ).html( subscription_result.error.message );
												jQuery( document.getElementById( 'ec_card_errors' ) ).fadeIn().html( subscription_result.error.message );
											} else {
												if ( subscription_result.status == 'open' ) {
													stripe.handleCardPayment( subscription_result.clientSecret, card, {
														payment_method_data: {
															billing_details: {
																address: {
																	city: city,
																	country: country,
																	line1: address1,
																	line2: address2,
																	postal_code: zip,
																	state: state
																},
																email: email,
																name: name
															}
														}
													} ).then( function( result ) {
														if ( result.error ) {
															jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show();
															jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide();
															jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut();
															jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).fadeIn().find( 'div' ).html( result.error.message );
															jQuery( document.getElementById( 'ec_card_errors' ) ).fadeIn().html( result.error.message );
														} else {
															var data = {
																action: 'ec_ajax_get_stripe_complete_payment_subscription',
																subscription_id: subscription_result.subscription_id,
																stripe_charge_id: subscription_result.stripe_charge_id,
																paymentintent_id: subscription_result.paymentintent_id,
																billing_details: billing_details,
																vat_registration_number: vat_registration_number,
																model_number: model_number,
																coupon_code: coupon_code,
																card: card_info,
																order_notes: order_notes,
																ec_quantity: ec_quantity,
																is_subscriber: is_subscriber,
																ec_terms_agree: ec_terms_agree,
																ec_cart_is_subscriber: ec_cart_is_subscriber,
																language: wpeasycart_ajax_object.current_language,
																nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-subscription-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
															};
															jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
																jQuery( location ).attr( 'href', result );
															} } );
														}
													} );
												} else if ( subscription_result.status == 'error' ) {
													jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show();
													jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide();
													jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut();
													jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).fadeIn().find( 'div' ).html( result.error.message );
													jQuery( document.getElementById( 'ec_card_errors' ) ).fadeIn().html( result.error.message );
												} else {
													var data = {
														action: 'ec_ajax_get_stripe_complete_payment_subscription',
														subscription_id: subscription_result.subscription_id,
														stripe_charge_id: subscription_result.stripe_charge_id,
														paymentintent_id: subscription_result.paymentintent_id,
														billing_details: billing_details,
														model_number: model_number,
														coupon_code: coupon_code,
														card: card_info,
														order_notes: order_notes,
														ec_quantity: ec_quantity,
														ec_terms_agree: ec_terms_agree,
														ec_cart_is_subscriber: ec_cart_is_subscriber,
														language: wpeasycart_ajax_object.current_language,
														nonce: '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-subscription-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>'
													};
													jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
														jQuery( location ).attr( 'href', result );
													} } );	
												}
											}
										} } );
									}
								} );
							}
						} );
					}catch( err ) {
						alert( "Your WP EasyCart with Stripe has a problem: " + err.message + ". Contact WP EasyCart for assistance." );
					}
				</script>

			<?php } else if ( $this->use_payment_gateway() ) { // Close if Stripe New Form and maybe use old Stripe ?>

			<div class="ec_cart_input_row" style="margin-top:-10px;">
				<?php if ( get_option('ec_option_use_visa') || get_option('ec_option_use_delta') || get_option('ec_option_use_uke') ) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "visa.png" ) ); ?>" alt="Visa" class="ec_card_active" id="ec_card_visa" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "visa_inactive.png" ) ); ?>" alt="Visa" class="ec_card_inactive" id="ec_card_visa_inactive" />
				<?php }?>

				<?php if ( get_option('ec_option_use_discover') ) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "discover.png" ) ); ?>" alt="Discover" class="ec_card_active" id="ec_card_discover" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "discover_inactive.png" ) ); ?>" alt="Discover" class="ec_card_inactive" id="ec_card_discover_inactive" />
				<?php }?>

				<?php if ( get_option('ec_option_use_mastercard') || get_option('ec_option_use_mcdebit') ) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "mastercard.png") ); ?>" alt="Mastercard" class="ec_card_active" id="ec_card_mastercard" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "mastercard_inactive.png") ); ?>" alt="Mastercard" class="ec_card_inactive" id="ec_card_mastercard_inactive" />
				<?php }?>

				<?php if ( get_option('ec_option_use_amex') ) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "american_express.png") ); ?>" alt="AMEX" class="ec_card_active" id="ec_card_amex" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "american_express_inactive.png") ); ?>" alt="AMEX" class="ec_card_inactive" id="ec_card_amex_inactive" />
				<?php }?>

				<?php if ( get_option('ec_option_use_jcb') ) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "jcb.png") ); ?>" alt="JCB" class="ec_card_active" id="ec_card_jcb" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "jcb_inactive.png") ); ?>" alt="JCB" class="ec_card_inactive" id="ec_card_jcb_inactive" />
				<?php }?>

				<?php if ( get_option('ec_option_use_diners') ) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "diners.png") ); ?>" alt="Diners" class="ec_card_active" id="ec_card_diners" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "diners_inactive.png") ); ?>" alt="Diners" class="ec_card_inactive" id="ec_card_diners_inactive" />
				<?php }?>

				<?php if ( get_option('ec_option_use_maestro') || get_option('ec_option_use_laser')) { ?>
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "maestro.png") ); ?>" alt="Maestro" class="ec_card_active" id="ec_card_maestro" />
					<img src="<?php echo esc_attr( $this->get_payment_image_source( "maestro_inactive.png") ); ?>" alt="Maestro" class="ec_card_inactive" id="ec_card_maestro_inactive" />
				<?php }?>
			</div>

			<?php if ( get_option( 'ec_option_show_card_holder_name' ) ) { ?>
			<div class="ec_cart_input_row">
				<input name="ec_card_holder_name" id="ec_card_holder_name" type="text" class="input-lg form-control" placeholder="<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_card_holder_name' )?>">
				<div class="ec_cart_error_row" id="ec_card_holder_name_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_card_holder_name' )?>
				</div>
			</div>
			<?php } else { ?>
			<?php $this->ec_cart_display_card_holder_name_hidden_input(); ?>
			<?php } ?>
			<div class="ec_cart_input_row">
				<input name="ec_card_number" id="ec_card_number" type="tel" class="input-lg form-control cc-number" autocomplete="cc-number" placeholder="<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_card_number' )?>">
				<div class="ec_cart_error_row" id="ec_card_number_error">
					<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_card_number' )?>
				</div>
			</div>
			<div class="ec_cart_input_row">
				<div class="ec_cart_input_left_half">
					<input name="ec_cc_expiration" id="ec_cc_expiration" type="tel" class="input-lg form-control cc-exp" autocomplete="cc-exp" placeholder="MM / YYYY">
					<div class="ec_cart_error_row" id="ec_expiration_date_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_expiration_date' )?>
					</div>
				</div>
				<div class="ec_cart_input_right_half">
					<input name="ec_security_code" id="ec_security_code" type="tel" class="input-lg form-control cc-cvc" autocomplete="off" placeholder="CVV">
					<div class="ec_cart_error_row" id="ec_security_code_error">
						<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_security_code' )?>
					</div>
				</div>
			</div>
			<?php } else { //use paypal ?>

			<div class="ec_cart_input_row">
				<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_third_party_first' )?> <?php $this->ec_cart_display_current_third_party_name(); ?> <?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_third_party_second' )?>
			</div>

			<?php } ?>

			<div class="ec_cart_header">
				<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_submit_order_button' )?>
			</div>

			<div class="ec_cart_error" id="ec_stripe_dynamic_error" style="display:none;">
				<div>
					<?php echo wp_easycart_language()->get_text( "ec_errors", "payment_failed" ); ?>
				</div>
			</div>

			<div class="ec_cart_error_row" id="ec_terms_error">
				<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
			</div>
			<div class="ec_cart_input_row">
				<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_checkout_text' )?>
			</div>

			<?php if ( get_option( 'ec_option_show_subscriber_feature' ) ) { ?>
			<div class="ec_cart_input_row ec_agreement_section">
				<input type="checkbox" name="ec_cart_is_subscriber" id="ec_cart_is_subscriber" value="1" />
				<?php echo wp_easycart_language()->get_text( 'account_register', 'account_register_subscribe' )?>
			</div>
			<?php }?>

			<?php if ( get_option( 'ec_option_require_terms_agreement' ) ) { ?>
			<div class="ec_cart_input_row ec_agreement_section">
				<input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" value="1"  /> <?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_review_agree' )?>
			</div>
			<?php } else { ?>
				<input type="hidden" name="ec_terms_agree" id="ec_terms_agree" value="2"  />
			<?php }?>


			<div class="ec_cart_error_row" id="ec_submit_order_error">
				<?php echo wp_easycart_language()->get_text( 'cart_form_notices', 'cart_notice_payment_correct_errors' )?> 
			</div>

			<div class="ec_cart_button_row">
				<input type="hidden" name="ec_quantity" value="<?php echo esc_attr( $subscription_quantity ); ?>" />
				<input type="submit" value="<?php echo wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_submit_order_button' )?>" class="ec_cart_button" id="ec_cart_submit_order" onclick="<?php $ec_subscription_js_function = apply_filters( 'wpeasycart_subscription_js_function', 'return ec_validate_submit_subscription();' ); echo esc_attr( $ec_subscription_js_function ); ?>" />
				<input type="submit" value="<?php echo esc_attr( strtoupper( wp_easycart_language()->get_text( 'cart', 'cart_please_wait' ) ) ); ?>" class="ec_cart_button_working" id="ec_cart_submit_order_working" onclick="return false;" />
			</div>

		</div>

		<?php $this->display_subscription_form_end(); ?>

	</div>

	<div class="ec_cart_right" id="ec_cart_payment_hide_column">

		<div class="ec_cart_header ec_top" style="color:#999 !important; margin-bottom:0px;">
			<?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_label' )?>
		</div>

		<div class="ec_cart_price_row ec_cart_price_row_subscription_title">
			<div class="ec_cart_price_row_label"><?php echo wp_easycart_escape_html( $product->title ); ?></div>
			<div class="ec_cart_price_row_total" id="ec_cart_subtotal"><?php echo esc_attr( $product->get_price_formatted( $subscription_quantity, $product->price ) ); ?></div>
		</div>

		<?php if ( $this->subscription_option1 != 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option1">
			<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option1_label ); ?></div>
			<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option1_name ); ?></div>
		</div>
		<?php if ( $subscription_option1->optionitem_price > 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option1">
			<div class="ec_cart_price_row_label"></div>
			<div class="ec_cart_price_row_total" id="ec_cart_option1_total"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option1->optionitem_price, $subscription_quantity ) ); ?></div>
		</div>
		<?php }?>
		<?php }?>

		<?php if ( $this->subscription_option2 != 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option2">
			<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option2_label ); ?></div>
			<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option2_name ); ?></div>
		</div>
		<?php if ( $subscription_option2->optionitem_price > 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option2">
			<div class="ec_cart_price_row_label"></div>
			<div class="ec_cart_price_row_total" id="ec_cart_option2_total"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option2->optionitem_price, $subscription_quantity ) ); ?></div>
		</div>
		<?php }?>
		<?php }?>

		<?php if ( $this->subscription_option3 != 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option3">
			<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option3_label ); ?></div>
			<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option3_name ); ?></div>
		</div>
		<?php if ( $subscription_option3->optionitem_price > 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option3">
			<div class="ec_cart_price_row_label"></div>
			<div class="ec_cart_price_row_total" id="ec_cart_option3_total"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option3->optionitem_price, $subscription_quantity ) ); ?></div>
		</div>
		<?php }?>
		<?php }?>

		<?php if ( $this->subscription_option4 != 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option4">
			<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option4_label ); ?></div>
			<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option4_name ); ?></div>
		</div>
		<?php if ( $subscription_option4->optionitem_price > 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option4">
			<div class="ec_cart_price_row_label"></div>
			<div class="ec_cart_price_row_total" id="ec_cart_option4_total"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option4->optionitem_price, $subscription_quantity ) ); ?></div>
		</div>
		<?php }?>
		<?php }?>

		<?php if ( $this->subscription_option5 != 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
			<div class="ec_cart_price_row_label"><?php echo esc_attr( $this->subscription_option5_label ); ?></div>
			<div class="ec_cart_price_row_total"><?php echo esc_attr( $this->subscription_option5_name ); ?> </div>   
		</div>
		<?php if ( $subscription_option5->optionitem_price > 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
			<div class="ec_cart_price_row_label"></div>
			<div class="ec_cart_price_row_total" id="ec_cart_option5_total"><?php echo esc_attr( $product->get_option_price_formatted( $subscription_option5->optionitem_price, $subscription_quantity ) ); ?></div>
		</div>
		<?php }?>
		<?php }?>

		<?php 
		if ( $subscription_advanced_options_display ) {
			foreach( $subscription_advanced_options_display as $option ) { ?>
			<div class="ec_cart_price_row ec_cart_price_row_subscription_adv_option">
				<div class="ec_cart_price_row_label"><?php echo esc_attr( $option['option_label'] ); ?></div>
				<div class="ec_cart_price_row_total"><?php 
				if ( $option['option_type'] == 'dimensions1' ) {
					echo esc_attr( $option['optionitem_value'][0] ); 
					if ( !get_option( 'ec_option_enable_metric_unit_display' ) ) { 
						echo "\"";
					}
					echo " x " . esc_attr( $option['optionitem_value'][1] ); 
					if ( !get_option( 'ec_option_enable_metric_unit_display' ) ) { 
						echo "\"";
					}
				} else if ( $option['option_type'] == 'dimensions2' ) {
					echo esc_attr( $option['optionitem_value'][0] . " " . $option['optionitem_value'][1] . "\" x " . $option['optionitem_value'][2] . " " . $option['optionitem_value'][3] ) . "\"";
				} else {
					echo esc_attr( $option['optionitem_value'] ); 
				} 
				?></div>   
			</div>
				<?php 
				$optionitem = $GLOBALS['ec_options']->get_optionitem( $option['optionitem_id'] );
				$optionitem_price = 0;
				if ( $optionitem->optionitem_enable_custom_price_label && ( $optionitem->optionitem_price != 0 || ( isset( $optionitem->optionitem_price ) && $optionitem->optionitem_price != 0 ) || ( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime != 0 ) ) ) { ?>
					<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
						<div class="ec_cart_price_row_label"></div>
						<div class="ec_cart_price_row_total"><?php echo esc_attr( wp_easycart_language( )->convert_text( $optionitem->optionitem_custom_price_label ) ); ?></div>
					</div>
				<?php } else if ( $optionitem && $optionitem->optionitem_price > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$optionitem_price = ( $optionitem->optionitem_price * (int) $option['optionitem_value'] );
					} else {
						$optionitem_price = $optionitem->optionitem_price;
					}
					if ( $optionitem_price > 0 ) { ?>
					<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
						<div class="ec_cart_price_row_label"></div>
						<div class="ec_cart_price_row_total"><?php echo esc_attr( $product->get_option_price_formatted( $optionitem_price, $subscription_quantity ) ); ?></div>
					</div>
					<?php }
				} else if ( $optionitem && $optionitem->optionitem_price_onetime > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$optionitem_price = ( $optionitem->optionitem_price_onetime * (int) $option['optionitem_value'] );
					} else {
						$optionitem_price = $optionitem->optionitem_price_onetime;
					}
					if ( $optionitem_price > 0 ) { ?>
					<div class="ec_cart_price_row ec_cart_price_row_subscription_option5">
						<div class="ec_cart_price_row_label"></div>
						<div class="ec_cart_price_row_total"><?php echo esc_attr( $product->get_option_price_formatted( $optionitem_price, 1 ) ); ?></div>
					</div>
					<?php }
				}
			}
		}?>

		<div class="ec_cart_price_row ec_cart_price_row_discount_total<?php if ( $discount_amount == 0 ) { ?> ec_no_discount<?php } else { ?> ec_has_discount<?php }?>">
			<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_discounts' )?></div>
			<div class="ec_cart_price_row_total" id="ec_cart_discount"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( (-1)*$discount_amount ) ); ?></div>
		</div>

		<?php if ( $product->is_taxable ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_tax_total"<?php if ( $tax_total <= 0 ) { ?> style="display:none;"<?php }?> id="ec_cart_tax_row">
			<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_tax' )?></div>
			<div class="ec_cart_price_row_total" id="ec_cart_tax"><?php echo esc_attr( $product->get_option_price_formatted( $tax_total, 1 ) ); ?></div>
		</div>
		<?php }?>

		<?php if ( $product->vat_rate > 0 ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_vat_total"<?php if ( $vat_total <= 0 ) { ?> style="display:none;"<?php }?> id="ec_cart_vat_row">
			<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_vat' ); ?> <span id="ec_cart_vat_rate"<?php echo ( $vat_total <= 0 ) ? ' style="display:none;"' : ''; ?>><?php echo esc_attr( $this->get_vat_rate_formatted( $vat_rate ) ); ?></span></div>
			<div class="ec_cart_price_row_total" id="ec_cart_vat"><?php echo esc_attr( $product->get_option_price_formatted( $vat_total, 1 ) ); ?></div>
		</div>
		<?php }?>

		<?php if ( get_option( 'ec_option_enable_easy_canada_tax' ) ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_gst_total" id="ec_cart_gst_row"<?php if ( $gst_total <= 0 ) { ?> style="display:none"<?php }?>>
			<div class="ec_cart_price_row_label">GST (<span id="ec_cart_gst_rate"><?php echo esc_attr( $gst_rate ); ?></span>%)</div>
			<div class="ec_cart_price_row_total" id="ec_cart_gst"><?php echo esc_attr( $product->get_option_price_formatted( $gst_total, 1 ) ); ?></div>
		</div>
		<?php }?>
		<?php if ( get_option( 'ec_option_enable_easy_canada_tax' ) ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_pst_total" id="ec_cart_pst_row"<?php if ( $pst_total <= 0 ) { ?> style="display:none"<?php }?>>
			<div class="ec_cart_price_row_label">PST (<span id="ec_cart_pst_rate"><?php echo esc_attr( $pst_rate ); ?></span>%)</div>
			<div class="ec_cart_price_row_total" id="ec_cart_pst"><?php echo esc_attr( $product->get_option_price_formatted( $pst_total, 1 ) ); ?></div>
		</div>
		<?php }?>
		<?php if ( get_option( 'ec_option_enable_easy_canada_tax' ) ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_hst_total" id="ec_cart_hst_row"<?php if ( $hst_total <= 0 ) { ?> style="display:none"<?php }?>>
			<div class="ec_cart_price_row_label">HST (<span id="ec_cart_hst_rate"><?php echo esc_attr( $hst_rate ); ?></span>%)</div>
			<div class="ec_cart_price_row_total" id="ec_cart_hst"><?php echo esc_attr( $product->get_option_price_formatted( $hst_total, 1 ) ); ?></div>
		</div>
		<?php }?>
		
		<?php if( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ){ ?>
		<div class="ec_cart_price_row ec_cart_price_row_shipping_total" id="ec_cart_shipping_row"<?php if( !$this->shipping->has_shipping_rates() ){ ?> style="display:none;"<?php }?>>
			<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' )?></div>
			<div class="ec_cart_price_row_total" id="ec_cart_shipping"><?php echo esc_attr( ( $product->subscription_shipping_recurring ) ? $product->get_option_price_formatted( $shipping_total, 1 ) : $GLOBALS['currency']->get_currency_display( $shipping_total ) ); ?></div>
		</div>
		<?php }?>

		<div class="ec_cart_price_row ec_cart_price_row_grand_total">
			<div class="ec_cart_price_row_label"><?php echo wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_grand_total' )?></div>
			<div class="ec_cart_price_row_total" id="ec_cart_total"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $grand_total ) ); ?></div>
		</div>

		<?php if ( $product->subscription_signup_fee > 0 ) { ?>
		<div class="ec_cart_price_row_total ec_cart_price_row_fees">
			<?php echo wp_easycart_language()->get_text( 'product_details', 'product_details_signup_fee_notice1' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $product->subscription_signup_fee * $subscription_quantity ) ); ?> <?php echo wp_easycart_language()->get_text( 'product_details', 'product_details_signup_fee_notice2' ); ?>
		</div>
		<?php }?>

		<?php if ( !get_option( 'ec_option_subscription_one_only' ) ) { ?>
		<form action="<?php echo esc_attr( $this->cart_page ); ?>" method="POST" enctype="multipart/form-data" class="ec_add_to_cart_form">
		<input type="hidden" name="ec_cart_form_action" value="process_update_subscription_quantity" />
		<input type="hidden" name="ec_cart_form_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-cart-subscription-update-item-' . $product->product_id ) ); ?>" />
		<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->product_id ); ?>" />
		<table class="ec_cartitem_quantity_table ec_subscription_table">
			<tbody>
				<tr>
					<td class="ec_minus_column">
						<input type="button" value="-" class="ec_minus" onclick="ec_minus_quantity( '<?php echo esc_attr( $product->product_id ); ?>-2', <?php echo esc_attr( $product->min_purchase_quantity ); ?> );" /></td>
					<td class="ec_quantity_column"><input type="number" value="<?php echo esc_attr( $subscription_quantity ); ?>" id="ec_quantity_<?php echo esc_attr( $product->product_id ); ?>-2" name="ec_quantity" autocomplete="off" step="1" min="<?php if ( $product->min_purchase_quantity > 0 ) { echo esc_attr( $product->min_purchase_quantity ); } else { echo '1'; } ?>" class="ec_quantity" /></td>
					<td class="ec_plus_column"><input type="button" value="+" class="ec_plus" onclick="ec_plus_quantity( '<?php echo esc_attr( $product->product_id ); ?>-2', <?php echo esc_attr( $product->show_stock_quantity ); ?>, <?php if ( $product->max_purchase_quantity > 0 ) { echo esc_attr( $product->max_purchase_quantity ); } else if ( $product->show_stock_quantity ) { echo esc_attr( $product->stock_quantity ); } else { echo '10000000'; } ?> );" /></td>
				</tr>
				<tr>
					<td colspan="3"><input type="submit" class="ec_cartitem_update_button" id="ec_cartitem_update_<?php echo esc_attr( $product->product_id ); ?>-2" value="<?php echo wp_easycart_language()->get_text( 'cart', 'cart_item_update_button' )?>" /></td>
				</tr>
			</tbody>
		</table>
		</form>
		<?php } ?>

		<?php if ( get_option( 'ec_option_show_coupons' ) ) { ?>
		<div class="ec_cart_header">
			<?php echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_coupon_title' )?>
		</div>

		<div class="ec_cart_error_message" id="ec_coupon_error"></div>
		<div class="ec_cart_success_message" id="ec_coupon_success"<?php if ( isset( $this->coupon ) ) {?> style="display:block;"<?php }?>><?php if ( isset( $this->coupon ) ) { if ( $this->discount->coupon_matches <= 0 ) { echo wp_easycart_language()->get_text( 'cart_coupons', 'coupon_not_applicable' ); } else { echo wp_easycart_language()->convert_text( $this->coupon->message ); } } ?></div>
		<div class="ec_cart_input_row">
			<input type="text" name="ec_coupon_code" id="ec_coupon_code" value="<?php if ( isset( $this->coupon ) ) { echo esc_attr( $this->coupon_code ); } ?>" placeholder="<?php echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_enter_coupon' )?>" />
		</div>
		<div class="ec_cart_button_row">
			<div class="ec_cart_button" id="ec_apply_coupon" onclick="ec_apply_subscription_coupon( '<?php echo esc_attr( $product->product_id ); ?>', '<?php echo esc_attr( $product->manufacturer_id ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-redeem-subscription-coupon-code-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"><?php echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_apply_coupon' ); ?></div>
			<div class="ec_cart_button_working" id="ec_applying_coupon"><?php echo wp_easycart_language()->get_text( 'cart', 'cart_please_wait' )?></div>
		</div>
		<?php }?>

		<input type="hidden" id="product_id" value="<?php echo esc_attr( $product->product_id ); ?>" />

	</div>

	<div style="clear:both;"></div>
	<div id="ec_current_media_size"></div>

</section>
<?php if ( get_option( 'ec_option_cache_prevent' ) ) { ?>
<script type="text/javascript">
	wpeasycart_cart_billing_country_update();
	wpeasycart_cart_shipping_country_update();
	jQuery( document.getElementById( 'ec_cart_billing_country' ) ).change( wpeasycart_cart_billing_country_update );
	jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).change( wpeasycart_cart_shipping_country_update );
</script>
<?php }?>