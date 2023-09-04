<?php
if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){
	echo "<script>
		fbq('track', 'AddPaymentInfo', {value: " . esc_js( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . ", currency: '" . esc_js( $GLOBALS['currency']->get_currency_code( ) ) . "', contents: [";
		for( $i=0; $i<count( $this->cart->cart ); $i++ ){
			if( $i > 0 )
				echo ", ";
			echo "{ id: '" . esc_attr( $this->cart->cart[$i]->product_id ) . "', quantity: " . esc_attr( $this->cart->cart[$i]->quantity ) . ", price: " . esc_attr( $this->cart->cart[$i]->unit_price ) . " }";
		}		
		echo "]});
	</script>";
}
?>
<div class="ec_cart_left">

	<?php $this->display_page_three_form_start( ); ?>
	<?php if( isset( $_GET['OID'] ) || $oid ){ ?>
	<input type="hidden" name="paypal_order_id" value="<?php echo esc_attr( ( isset( $_GET['OID'] ) ) ? strtoupper( preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['OID'] ) ) ) : strtoupper( preg_replace( "/[^A-Za-z0-9\-]/", '', $oid ) ) ); ?>" />
	<?php }else{ ?>
	<input type="hidden" name="paypal_payment_id" value="<?php echo esc_attr( ( isset( $_GET['PID'] ) ) ? strtoupper( preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['PID'] ) ) ) : strtoupper( preg_replace( "/[^A-Za-z0-9\-]/", '', $pid ) ) ); ?>" />
	<?php }?>
	<input type="hidden" name="paypal_payer_id" value="<?php echo esc_attr( ( isset( $_GET['PYID'] ) ) ? strtoupper( preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_GET['PYID'] ) ) ) : strtoupper( preg_replace( "/[^A-Za-z0-9]/", '', $pyid ) ) ); ?>" />
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_payment_method' ); ?>
	</div>
	<div class="ec_cart_input_row ec_cart_paypal_note">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_paypal_final_note' ); ?>
	</div>
	<div class="wp-easycart-paypal-express-logo-box" style="float:left; width:100%; background:#FFF; padding:10px 20px; border:1px solid #e1e1e1; text-align:center;"><img src="<?php echo esc_attr( $this->get_payment_image_source( "paypal.jpg" ) ); ?>" alt="PayPal" /></div>
	<div class="wp-easycart-paypal-express-back-link" style="float:left; width:100%; text-align:right;"><a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_payment"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_change_payment_method' ); ?></a></div>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_review_title' )?>
	</div>

	<?php for( $cartitem_index = 0; $cartitem_index<count( $this->cart->cart ); $cartitem_index++ ){ ?>

	<div class="ec_cart_price_row ec_cart_price_row_cartitem_<?php echo esc_attr( $cartitem_index ); ?>">
		<div class="ec_cart_price_row_label"><?php $this->cart->cart[$cartitem_index]->display_title( ); ?><?php if( $this->cart->cart[$cartitem_index]->grid_quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->grid_quantity ); ?><?php }else if( $this->cart->cart[$cartitem_index]->quantity > 1 ){ ?> x <?php echo esc_attr( $this->cart->cart[$cartitem_index]->quantity ); ?><?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->stock_quantity <= 0 && $this->cart->cart[$cartitem_index]->allow_backorders ){ ?>
		<div class="ec_cart_backorder_date"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backordered' ); ?><?php if( $this->cart->cart[$cartitem_index]->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $this->cart->cart[$cartitem_index]->backorder_fill_date ); ?><?php }?></div>
		<?php }?>
		<?php if( $this->cart->cart[$cartitem_index]->optionitem1_name ){ ?>
		<dl>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem1_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem1_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem1_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php } ?></dt>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem2_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem2_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem2_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem2_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem3_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem3_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem3_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem3_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem4_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem4_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem4_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem4_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php } ?></dt>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->optionitem5_name ){ ?>
			<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->optionitem5_name ); ?><?php if( $this->cart->cart[$cartitem_index]->optionitem5_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php }else if( $this->cart->cart[$cartitem_index]->optionitem5_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $this->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php } ?></dt>
		<?php }?>
		</dl>
		<?php }?>

		<?php if ( $this->cart->cart[$cartitem_index]->use_advanced_optionset || $this->cart->cart[$cartitem_index]->use_both_option_types ) { ?>
		<dl>
		<?php foreach( $this->cart->cart[$cartitem_index]->advanced_options as $advanced_option_set ){ ?>
			<?php if( $advanced_option_set->option_type == "grid" ){ ?>
			<dt><?php echo wp_easycart_escape_html( $advanced_option_set->optionitem_name ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if ( $advanced_option_set->optionitem_price > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
					echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
				} ?></dt>
			<?php }else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){ ?>
			<strong><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

			<?php }else{ ?>
			<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( htmlspecialchars( $advanced_option_set->optionitem_value, ENT_QUOTES ) ); ?><?php
				if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
					echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
				} else if ( $advanced_option_set->optionitem_price > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price < 0 ){
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
					echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
					echo ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')';
				} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
					echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')';
				} ?></dt>
			<?php } ?>
		<?php }?>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_giftcard ){ ?>
		<dl>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_to_name, ENT_QUOTES ) ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_email, ENT_QUOTES ) ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_from_name, ENT_QUOTES ) ); ?></dt>
		<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( htmlspecialchars( $this->cart->cart[$cartitem_index]->gift_card_message, ENT_QUOTES ) ); ?></dt>
		</dl>
		<?php }?>

		<?php if( $this->cart->cart[$cartitem_index]->is_deconetwork ){ ?>
		<dl>
		<dt><?php echo esc_attr( $this->cart->cart[$cartitem_index]->deconetwork_options ); ?></dt>
		<dt><?php echo "<a href=\"https://" . esc_attr( get_option( 'ec_option_deconetwork_url' ) ) . esc_attr( $this->cart->cart[$cartitem_index]->deconetwork_edit_link ) . "\">" . wp_easycart_language( )->get_text( 'cart', 'deconetwork_edit' ) . "</a>"; ?></dt>
		</dl>
		<?php }?>

		<?php do_action( 'wp_easycart_cartitem_post_optionitems', $this->cart->cart[$cartitem_index] ); ?>

		</div>
		<div class="ec_cart_price_row_total" id="ec_cart_subtotal"><?php echo esc_attr( $this->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
	</div>

	<?php }?>

	<div class="ec_cart_price_row ec_order_total">
		<div class="ec_cart_price_row_label"></div>
		<div class="ec_cart_price_row_total"><a href="<?php echo esc_attr( $this->cart_page ); ?>"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_cart_link' ); ?></a></div>
	</div>

	<?php if( get_option( 'ec_option_user_order_notes' ) && $GLOBALS['ec_cart_data']->cart_data->order_notes != "" && strlen( $GLOBALS['ec_cart_data']->cart_data->order_notes ) > 0 ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( nl2br( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->order_notes, ENT_QUOTES ) ) ); ?>
	</div>
	<?php }?>

	<div id="ec_cart_payment_one_column">
		<div class="ec_cart_header ec_top">
			<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_user']->billing->last_name, ENT_QUOTES ) ); ?>
		</div>

		<?php if( strlen( $GLOBALS['ec_user']->billing->company_name ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->company_name, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->address_line_1, ENT_QUOTES ) ); ?>
		</div>

		<?php if( strlen( $GLOBALS['ec_user']->billing->address_line_2 ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->address_line_2, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->zip, ENT_QUOTES ) ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->country_name, ENT_QUOTES ) ); ?>
		</div>

		<?php if( strlen( $GLOBALS['ec_user']->billing->phone ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->phone, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<?php if( strlen( $GLOBALS['ec_user']->vat_registration_number ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<strong><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?>:</strong> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->vat_registration_number, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) ){ ?>

		<div class="ec_cart_header ec_top">
			<?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_user']->shipping->last_name, ENT_QUOTES ) ); ?>
		</div>

		<?php if( strlen( $GLOBALS['ec_user']->shipping->company_name ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->company_name, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_1, ENT_QUOTES ) ); ?>
		</div>

		<?php if( strlen( $GLOBALS['ec_user']->shipping->address_line_2 ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_2, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->zip, ENT_QUOTES ) ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->country_name, ENT_QUOTES ) ); ?>
		</div>

		<?php if( strlen( $GLOBALS['ec_user']->shipping->phone ) > 0 ){ ?>
		<div class="ec_cart_input_row">
			<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->phone, ENT_QUOTES ) ); ?>
		</div>
		<?php }?>

		<?php if( !isset( $_GET['OID'] ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) ){ ?>
		<div class="ec_cart_header">
			<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?> 
		</div>
		<div class="ec_cart_input_row">
			<?php $this->display_selected_shipping_method( ); ?>
			<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_shipping"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_shipping_method_link' ); ?></a>
		</div>
		<?php }?>

		<?php }?>
	</div>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_review_totals_title' ); ?>
	</div>
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
	<?php if( $this->order_totals->tax_total > 0 ){ ?>
	<div class="ec_cart_price_row ec_cart_price_row_tax_total">
		<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_tax' )?></div>
		<div class="ec_cart_price_row_total" id="ec_cart_tax"><?php echo esc_attr( $this->get_tax_total( ) ); ?></div>
	</div>
	<?php }?>
	<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) ){ ?>
	<div class="ec_cart_price_row ec_cart_price_row_shipping_total">
		<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_shipping' )?></div>
		<div class="ec_cart_price_row_total" id="ec_cart_shipping"><?php echo esc_attr( $this->get_shipping_total( ) ); ?></div>
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
	<div class="ec_cart_price_row ec_order_total">
		<div class="ec_cart_price_row_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_grand_total' )?></div>
		<div class="ec_cart_price_row_total" id="ec_cart_total"><?php echo esc_attr( $this->get_grand_total( ) ); ?></div>
	</div>

	<?php if( get_option( 'ec_option_user_order_notes' ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_message' ); ?>
		<textarea name="ec_order_notes" id="ec_order_notes"><?php if( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" ){ echo esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes ); } ?></textarea>
	</div>
	<?php }?>

	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_submit_order_button' )?>
	</div>

	<div class="ec_cart_error_row" id="ec_terms_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
	</div>
	<div class="ec_cart_input_row">
		<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_checkout_text' )?>
	</div>
	<?php if( get_option( 'ec_option_require_terms_agreement' ) ){ ?>
	<div class="ec_cart_input_row ec_agreement_section">
		<input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" value="1"  /> <?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_review_agree' )?>
	</div>
	<?php }else{ ?>
		<input type="hidden" name="ec_terms_agree" id="ec_terms_agree" value="2"  />
	<?php }?>

	<?php if( get_option( 'ec_option_show_subscriber_feature' ) && ( !$GLOBALS['ec_user']->user_id || !$GLOBALS['ec_user']->is_subscriber ) ){ ?>
	<div class="ec_cart_input_row ec_agreement_section"<?php if( get_option( 'ec_option_require_terms_agreement' ) ){ ?> style="margin-top:-10px;"<?php }?>>
		<input type="checkbox" name="ec_cart_is_subscriber" id="ec_cart_is_subscriber" class="ec_account_register_input_field" value="1" />
		<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_subscribe' )?>
	</div>
	<?php }?>

	<div class="ec_cart_error_row" id="ec_submit_order_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_correct_errors' )?> 
	</div>

	<div class="ec_cart_button_row">
		<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_submit_order_button' )?>" class="ec_cart_button" id="ec_cart_submit_order" onclick="return ec_validate_paypal_express_submit_order( );" />
		<input type="submit" value="<?php echo esc_attr( strtoupper( wp_easycart_language( )->get_text( 'cart', 'cart_please_wait' ) ) ); ?>" class="ec_cart_button_working" id="ec_cart_submit_order_working" onclick="return false;" />
	</div>
	<?php $this->display_page_three_form_end( ); ?>
</div>

<div class="ec_cart_right" id="ec_cart_payment_hide_column">

	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_user']->billing->last_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_user']->billing->company_name ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->company_name, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->address_line_1, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_user']->billing->address_line_2 ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->address_line_2, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->zip, ENT_QUOTES ) ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->country_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_user']->billing->phone ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->phone, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<?php if( strlen( $GLOBALS['ec_user']->vat_registration_number ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<strong><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?>:</strong> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->vat_registration_number, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<?php if( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) ){ ?>
	<div class="ec_cart_header ec_top">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->first_name, ENT_QUOTES ) . ' ' . htmlspecialchars( $GLOBALS['ec_user']->shipping->last_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_user']->shipping->company_name ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->company_name, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_1, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_user']->shipping->address_line_2 ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->address_line_2, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->city, ENT_QUOTES ) ); ?>, <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->state, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->zip, ENT_QUOTES ) ); ?>
	</div>

	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->country_name, ENT_QUOTES ) ); ?>
	</div>

	<?php if( strlen( $GLOBALS['ec_user']->shipping->phone ) > 0 ){ ?>
	<div class="ec_cart_input_row">
		<?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->shipping->phone, ENT_QUOTES ) ); ?>
	</div>
	<?php }?>
	<?php }?>

	<?php if( !isset( $_GET['OID'] ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) ){ ?>
	<div class="ec_cart_header">
		<?php echo wp_easycart_language( )->get_text( 'cart_shipping_method', 'cart_shipping_method_title' ); ?>
	</div>
	<div class="ec_cart_input_row">
		<?php $this->display_selected_shipping_method( ); ?>
		<a href="<?php echo esc_attr( $this->cart_page . $this->permalink_divider ); ?>ec_page=checkout_shipping"><?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_edit_shipping_method_link' ); ?></a>
	</div>
	<?php } // Close if for shipping ?>

</div>

<div style="clear:both;"></div>
<div id="ec_current_media_size"></div>