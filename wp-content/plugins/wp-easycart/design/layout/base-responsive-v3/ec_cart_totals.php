<div class="ec_cart_price_row ec_cart_price_row_subtotal">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_subtotal' )?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_subtotal"><?php echo esc_attr( $this->get_subtotal( ) ); ?></div>
</div>
<?php if( get_option( 'ec_option_enable_tips' ) ){ ?>
<?php $default_tips = explode( ',', get_option( 'ec_option_default_tips' ) ); ?>
<div class="ec_cart_price_row ec_cart_tips">

	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip' ); ?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_tip"><?php echo esc_attr( $this->get_tip_total( ) ); ?></div>
	<ul class="ec_cart_tip_items">
		<?php foreach( $default_tips as $tip_rate ){ ?>
		<li class="ec_cart_tip_item<?php echo ( (float) $GLOBALS['ec_cart_data']->cart_data->tip_rate == (float) $tip_rate ) ? ' ec_tip_selected' : ''; ?>">
			<a href="" onclick="wpeasycart_update_tip( '<?php echo esc_attr( $tip_rate ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-tip-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' ); jQuery( this ).parent( ).addClass( 'ec_tip_selected' ); jQuery( document.getElementById( 'ec_cart_tip' ) ).html( jQuery( this ).find( 'span' ).html( ) ); jQuery( document.getElementById( 'ec_cart_tip_custom' ) ).val( '' ); return false;"><strong><?php echo esc_attr( number_format( $tip_rate, 0, '', '' ) ); ?>%</strong><br /><span><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $tip_rate / 100 * $this->order_totals->get_converted_sub_total( ), false ) ); ?></span></a>
		</li>
		<?php }?>
	</ul>
	<div class="ec_cart_tip_item ec_cart_tip_custom_item ec_cart_button_row">
		<label><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tip_custom' ); ?></label>
		<input type="number" id="ec_cart_tip_custom" value="<?php echo ( $GLOBALS['ec_cart_data']->cart_data->tip_rate == 'custom' ) ? number_format( $GLOBALS['ec_cart_data']->cart_data->tip_amount, 2, '.', '' ) : ''; ?>" />
		<input type="button" value="<?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_apply_tip_custom' ); ?>" id="ec_apply_tip_button" class="ec_cart_button" onclick="wpeasycart_update_tip( 'custom', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-tip-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' ); return false;" />
		<div class="ec_cart_button_working" id="ec_applying_tip"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' )?></div>
	</div>
</div>
<?php }?>
<?php if( ( $this->tax->is_tax_enabled( ) && !get_option( 'ec_option_enable_easy_canada_tax' ) ) || ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->tax_total > 0 ) ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_tax_total">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tax' )?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_tax"><?php echo esc_attr( $this->get_tax_total( ) ); ?></div>
</div>
<?php }?>
<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) && $this->shipping->has_shipping_rates( ) ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_shipping_total">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' )?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_shipping"><?php echo esc_attr( $this->get_shipping_total( ) ); ?></div>
	<div class="ec_cart_price_row_loader">
		<div class="ec_cart_price_animate_pulse">
			<div class="ec_cart_price_animate_line_container">
				<div class="ec_cart_price_animate_line_top"></div>
				<div class="ec_cart_price_animate_line_bottom"></div>
			</div>
		</div>
	</div>
</div>
<?php }?>
<div class="ec_cart_price_row ec_cart_price_row_discount_total<?php if( $this->order_totals->discount_total == 0 ){ ?> ec_no_discount<?php }else{ ?> ec_has_discount<?php }?>">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_discounts' )?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_discount"><?php echo esc_attr( $this->get_discount_total( ) ); ?></div>
</div>
<?php if( $this->tax->is_duty_enabled( ) ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_duty_total">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_duty' )?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_duty"><?php echo esc_attr( $this->get_duty_total( ) ); ?></div>
</div>
<?php }?>
<?php if( $this->tax->is_vat_enabled( ) ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_vat_total">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_vat' )?> <span id="ec_cart_vat_rate"<?php echo ( $this->order_totals->vat_total <= 0 ) ? ' style="display:none;"' : ''; ?>><?php echo esc_attr( $this->get_vat_rate_formatted( ) ); ?></span></div>
	<div class="ec_cart_price_row_total" id="ec_cart_vat"><?php echo esc_attr( $this->get_vat_total_formatted( ) ); ?></div>
</div>
<?php }?>
<?php if( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->gst_total > 0 ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_gst_total">
	<div class="ec_cart_price_row_label">GST (<?php echo esc_attr( $this->tax->gst_rate ); ?>%)</div>
	<div class="ec_cart_price_row_total" id="ec_cart_tax"><?php echo esc_attr( $this->get_gst_total( ) ); ?></div>
</div>
<?php }?>
<?php if( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->pst_total > 0 ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_pst_total">
	<div class="ec_cart_price_row_label">PST (<?php echo esc_attr( $this->tax->pst_rate ); ?>%)</div>
	<div class="ec_cart_price_row_total" id="ec_cart_tax"><?php echo esc_attr( $this->get_pst_total( ) ); ?></div>
</div>
<?php }?>
<?php if( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->hst_total > 0 ){ ?>
<div class="ec_cart_price_row ec_cart_price_row_hst_total">
	<div class="ec_cart_price_row_label">HST (<?php echo esc_attr( $this->tax->hst_rate ); ?>%)</div>
	<div class="ec_cart_price_row_total" id="ec_cart_tax"><?php echo esc_attr( $this->get_hst_total( ) ); ?></div>
</div>
<?php }?>
<?php if ( count( $this->tax->fees ) > 0 ) { ?>
	<?php foreach ( $this->tax->fees as $fee ) { ?>
		<div class="ec_cart_price_row ec_cart_price_row_fee">
			<div class="ec_cart_price_row_label"><?php echo esc_attr( $fee->label ); ?></div>
			<div class="ec_cart_price_row_total" id="ec_cart_fee_<?php echo esc_attr( $fee->fee_id ); ?>"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $fee->amount, false ) ); ?></div>
		</div>
	<?php }?>
<?php }?>
<div class="ec_cart_price_row ec_order_total">
	<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_grand_total' )?></div>
	<div class="ec_cart_price_row_total" id="ec_cart_total"><?php echo esc_attr( $this->get_grand_total( ) ); ?></div>
	<div class="ec_cart_price_row_loader">
		<div class="ec_cart_price_animate_pulse">
			<div class="ec_cart_price_animate_line_container">
				<div class="ec_cart_price_animate_line_top"></div>
				<div class="ec_cart_price_animate_line_bottom"></div>
			</div>
		</div>
	</div>
</div>
