<?php
if ( ! isset( $cartpage ) ) {
	$cartpage = $this;
}
?>

<?php if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) { ?>
<script>
	jQuery( document ).ready( function() {
		<?php if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) { ?>
		dataLayer.push( { ecommerce: null } );
		dataLayer.push( {
			event: "view_cart",
			ecommerce: {
		<?php } else { ?>
		gtag( "event", "view_cart", {
		<?php }?>
			currency: "<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>",
			value: <?php echo esc_attr( number_format( $cartpage->order_totals->grand_total, 2, '.', '' ) ); ?>,
			coupon_code: "<?php echo esc_attr( $cartpage->coupon_code ); ?>",
			items: [
			<?php for( $i=0; $i < count( $cartpage->cart->cart ); $i++ ) { ?>
				{
					item_id: "<?php echo esc_attr( $cartpage->cart->cart[$i]->model_number ); ?>",
					item_name: "<?php echo esc_attr( $cartpage->cart->cart[$i]->title ); ?>",
					index: <?php echo esc_attr( $i ); ?>,
					price: <?php echo esc_attr( number_format( $cartpage->cart->cart[$i]->unit_price, 2, '.', '' ) ); ?>,
					item_brand: "<?php echo esc_attr( $cartpage->cart->cart[$i]->manufacturer_name ) ; ?>",
					quantity: <?php echo esc_attr( number_format( $cartpage->cart->cart[$i]->quantity, 2, '.', '' ) ); ?>
				},
			<?php } ?>
			]
		<?php if ( ! get_option( 'ec_option_google_ga4_tag_manager' ) ) { ?>} );<?php } else { ?>} } );<?php }?>
	} );
</script>
<?php }?>

<?php do_action( 'wpeasycart_cart_top_left', $cartpage ); ?>

<div class="ec_cart_backorders_present" id="ec_cart_backorder_message"<?php if( !$cartpage->cart->has_backordered_item( ) ){ ?> style="display:none;"<?php }?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backordered_message' ); ?></div>

<?php do_action( 'wpeasycart_cart_post_minimum_notice' ); ?>

<div class="ec_cart_table">
	<div class="ec_cart_table_headers">
		<div class="ec_cart_table_header_spacer ec_cart_table_details"></div>
		<div class="ec_cart_table_header_price ec_cart_table_price"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column3' )?></div>
		<div class="ec_cart_table_header_quantity ec_cart_table_quantity"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column4' )?></div>
		<div class="ec_cart_table_header_total ec_cart_table_total"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column5' )?></div>
	</div>

	<div class="ec_cart_table_body">
		<?php for( $cartitem_index = 0; $cartitem_index<count( $cartpage->cart->cart ); $cartitem_index++ ){ ?>
		<?php do_action( 'wpeasycart_cart_item_row_pre', $cartpage->cart->cart[$cartitem_index], $cartitem_index ); ?>
		<div class="ec_cart_table_error_row" id="ec_cartitem_max_error_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>">
			<div><?php echo wp_easycart_language( )->get_text( 'cart', 'cartitem_max_error' )?></div>
		</div>

		<div class="ec_cart_table_error_row" id="ec_cartitem_min_error_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>">
			<div><?php echo wp_easycart_language( )->get_text( 'cart', 'cartitem_min_error' )?></div>
		</div>

		<div class="ec_cart_table_row" id="ec_cartitem_row_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>">
			<div class="ec_cart_table_column_details ec_cart_table_details">
				<div class="ec_cart_table_image">
					<img src="<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->get_image_url( ) ); ?>" alt="<?php echo esc_attr( str_replace( '"', '&quot;', $cartpage->cart->cart[$cartitem_index]->title ) ); ?>" />
				</div>
				<div class="ec_cart_table_details_content">
					<a href="<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->get_title_link( ) ); ?>" class="ec_cartitem_title"><?php $cartpage->cart->cart[$cartitem_index]->display_title( ); ?></a>
					<div class="ec_cart_table_mobile_price" id="ec_cartitem_price_mobile_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->get_unit_price( ) ); ?></div>
					<?php if( 
						$cartpage->cart->cart[$cartitem_index]->use_optionitem_quantity_tracking && 
						$cartpage->cart->cart[$cartitem_index]->allow_backorders
					){ ?>
					<div class="ec_cart_backorder_date" id="ec_cartitem_backorder_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"<?php if( $cartpage->cart->cart[$cartitem_index]->optionitem_stock_quantity >= $cartpage->cart->cart[$cartitem_index]->quantity ){ ?> style="display:none;"<?php }?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_some_backordered' ); ?> <?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->optionitem_stock_quantity ); ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_some_backordered_remaining' ); ?><?php if( $cartpage->cart->cart[$cartitem_index]->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $cartpage->cart->cart[$cartitem_index]->backorder_fill_date ); ?><?php }?></div>

					<?php }else if( 
						$cartpage->cart->cart[$cartitem_index]->show_stock_quantity &&
						$cartpage->cart->cart[$cartitem_index]->allow_backorders ){ ?>
					<div class="ec_cart_backorder_date" id="ec_cartitem_backorder_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"<?php if( $cartpage->cart->cart[$cartitem_index]->stock_quantity > $cartpage->cart->cart[$cartitem_index]->quantity ){ ?> style="display:none;"<?php }?>><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backordered' ); ?><?php if( $cartpage->cart->cart[$cartitem_index]->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $cartpage->cart->cart[$cartitem_index]->backorder_fill_date ); ?><?php }?></div>

					<?php } ?>
					<?php if( $cartpage->cart->cart[$cartitem_index]->optionitem1_name ){ ?>
						<dl>
							<dt><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->optionitem1_name ); ?><?php
								if ( $cartpage->cart->cart[$cartitem_index]->optionitem1_price > 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )</span><?php
								} else if ( $cartpage->cart->cart[$cartitem_index]->optionitem1_price < 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )</span><?php
								} ?></dt>

						<?php if( $cartpage->cart->cart[$cartitem_index]->optionitem2_name ){ ?>
							<dt><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->optionitem2_name ); ?><?php
								if ( $cartpage->cart->cart[$cartitem_index]->optionitem2_price > 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )</span><?php
								} else if ( $cartpage->cart->cart[$cartitem_index]->optionitem2_price < 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )</span><?php
								} ?></dt>
						<?php }?>

						<?php if( $cartpage->cart->cart[$cartitem_index]->optionitem3_name ){ ?>
							<dt><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->optionitem3_name ); ?><?php
								if ( $cartpage->cart->cart[$cartitem_index]->optionitem3_price > 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )</span><?php
								} else if ( $cartpage->cart->cart[$cartitem_index]->optionitem3_price < 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )</span><?php
								} ?></dt>
						<?php }?>

						<?php if( $cartpage->cart->cart[$cartitem_index]->optionitem4_name ){ ?>
							<dt><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->optionitem4_name ); ?><?php
								if ( $cartpage->cart->cart[$cartitem_index]->optionitem4_price > 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )</span><?php
								} else if ( $cartpage->cart->cart[$cartitem_index]->optionitem4_price < 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )</span><?php
								} ?></dt>
						<?php }?>

						<?php if( $cartpage->cart->cart[$cartitem_index]->optionitem5_name ){ ?>
							<dt><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->optionitem5_name ); ?><?php
								if ( $cartpage->cart->cart[$cartitem_index]->optionitem5_price > 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )</span><?php
								} else if ( $cartpage->cart->cart[$cartitem_index]->optionitem5_price < 0 ) {
									?><span class="ec_cart_line_optionitem_pricing"> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cartpage->cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )</span><?php
								} ?></dt>
						<?php }?>
						</dl>
					<?php }?>

					<?php if( $cartpage->cart->cart[$cartitem_index]->use_advanced_optionset || $cartpage->cart->cart[$cartitem_index]->use_both_option_types ){ ?>
					<dl>
						<?php foreach( $cartpage->cart->cart[$cartitem_index]->advanced_options as $advanced_option_set ){ ?>
							<?php if( $advanced_option_set->option_type == "grid" ){ ?>
							<dt><?php echo wp_easycart_escape_html( $advanced_option_set->optionitem_name ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php 
							if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
							} else if ( $advanced_option_set->optionitem_price > 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price < 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')'; }else if( $advanced_option_set->optionitem_price_onetime > 0 ){ echo ' (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')</span>';
							} ?></dt>
							<?php }else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){ ?>
								<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo esc_attr( " x " . $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?></dt>

							<?php }else{ ?>
							<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php 
							if ( $advanced_option_set->optionitem_enable_custom_price_label && ( $advanced_option_set->optionitem_price != 0 || ( isset( $advanced_option_set->optionitem_price ) && $advanced_option_set->optionitem_price != 0 ) || ( isset( $advanced_option_set->optionitem_price_onetime ) && $advanced_option_set->optionitem_price_onetime != 0 ) ) ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> ' . esc_attr( wp_easycart_language( )->convert_text( $advanced_option_set->optionitem_custom_price_label ) ) . '</span>';
							} else if( $advanced_option_set->optionitem_price > 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price < 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price_onetime > 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (+' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price_onetime < 0 ) {
								echo '<span class="ec_cart_line_optionitem_pricing">' . esc_attr( ' (' . $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
							} else if ( $advanced_option_set->optionitem_price_override > -1 ) {
								echo '<span class="ec_cart_line_optionitem_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override ) ) . ')</span>';
							} ?></dt>
							<?php } ?>
						<?php }?>
					</dl>
					<?php }?>

					<?php if( $cartpage->cart->cart[$cartitem_index]->is_giftcard ){ ?>
					<dl>
						<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->gift_card_to_name ); ?></dt>
						<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->gift_card_email ); ?></dt>
						<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->gift_card_from_name ); ?></dt>
						<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->gift_card_message ); ?></dt>
					</dl>
					<?php }?>

					<?php if( $cartpage->cart->cart[$cartitem_index]->is_deconetwork ){ ?>
					<dl>
						<dt><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->deconetwork_options ); ?></dt>
						<dt><?php echo "<a href=\"https://" . esc_attr( get_option( 'ec_option_deconetwork_url' ) ) . esc_attr( $cartpage->cart->cart[$cartitem_index]->deconetwork_edit_link ) . "\">" . wp_easycart_language( )->get_text( 'cart', 'deconetwork_edit' ) . "</a>"; ?></dt>
					</dl>
					<?php }?>
					<?php do_action( 'wp_easycart_cartitem_post_optionitems', $cartpage->cart->cart[$cartitem_index] ); ?>
				</div>
			</div>
			<div class="ec_cart_table_column_price ec_cart_table_price" id="ec_cartitem_price_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->get_unit_price( ) ); ?></div>
			<div class="ec_cart_table_column_quantity ec_cart_table_quantity">
				<?php if( $cartpage->cart->cart[$cartitem_index]->grid_quantity > 0 ){ ?>
					<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->grid_quantity ); ?>
				<?php }else if( $cartpage->cart->cart[$cartitem_index]->is_deconetwork ){ ?>
					<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->quantity ); ?>
				<?php }else{ ?>
				<div class="ec_cartitem_updating" id="ec_cartitem_updating_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"></div>
				<input type="number" value="<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->quantity ); ?>" id="ec_quantity_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>" autocomplete="off" step="1" min="<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->min_quantity ); ?>" max="<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->max_quantity ); ?>" class="ec_quantity ec_table_quantity_box" onchange="ec_cartitem_update( '<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-cart-item-' . (int) $cartpage->cart->cart[$cartitem_index]->cartitem_id ) ); ?>' );" />
				<?php }?>
				<div class="ec_cartitem_delete" id="ec_cartitem_delete_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>" onclick="ec_cartitem_delete( '<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->model_number ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-delete-cart-item-' . (int) $cartpage->cart->cart[$cartitem_index]->cartitem_id ) ); ?>' );<?php
				if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
					echo 'ec_ga4_remove_from_cart( \'' . esc_attr( $cartpage->cart->cart[$cartitem_index]->model_number ) . '\', \'' . esc_attr( str_replace( "'", "\'", $cartpage->cart->cart[$cartitem_index]->title ) ) . '\', jQuery( document.getElementById( \'ec_quantity_' . esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ) . '\' ) ).val(), \'' . esc_attr( number_format( $cartpage->cart->cart[$cartitem_index]->unit_price, 2, '.', '' ) ) . '\', \'' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '\' );';
				}
				?>">Remove</div>
				<div class="ec_cartitem_deleting" id="ec_cartitem_deleting_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"></div>
			</div>
			<div class="ec_cart_table_column_total ec_cart_table_total" id="ec_cartitem_total_<?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->cartitem_id ); ?>"><?php echo esc_attr( $cartpage->cart->cart[$cartitem_index]->get_total( ) ); ?></div>
		</div>
		<?php }?>
	</div>
</div>

<div class="ec_cart_table_subtotal_row">
	<?php if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) ) { ?>
	<div class="ec_cart_table_split_payments">
		<?php
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
			$pkey = get_option( 'ec_option_stripe_public_api_key' );
		} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
			$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
		} else {
			$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
		}
		?>
		<div id="wpec-payment-method-messaging-element"></div>
		<script>
		jQuery( document ).ready( function() {
			const appearance = {
				theme: '<?php echo esc_attr( get_option( 'ec_option_stripe_payment_theme' ) ); ?>',
			};
			const elements = stripe.elements( appearance );
			const options = {
				amount: <?php echo esc_attr( (int) ( $cartpage->order_totals->sub_total * 100 ) ); ?>,
				currency: '<?php echo esc_attr( $GLOBALS['currency']->get_currency_code() ); ?>',
				paymentMethodTypes: ['klarna', 'afterpay_clearpay', 'affirm'],
				countryCode: '<?php echo esc_attr( ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_country : get_option( 'ec_option_stripe_company_country' ) ); ?>',
			};
			const PaymentMessageElement =
			elements.create( 'paymentMethodMessaging', options );
			PaymentMessageElement.mount( '#wpec-payment-method-messaging-element' );
		} );
		</script>
	</div>
	<?php }?>
	
	<div class="ec_cart_table_subtotal">
		<div>
			<span class="ec_cart_table_subtotal_label"><?php echo wp_easycart_language( )->get_text( 'cart_totals', 'cart_totals_subtotal' )?></span>
			<span class="ec_cart_table_subtotal_amount"><?php echo esc_attr( $cartpage->get_subtotal( ) ); ?></span>
		</div>

		<div class="ec_cart_table_message">Shipping, taxes, and discount codes calculated at checkout.</div>

		<?php if( apply_filters( 'wpeasycart_show_checkout_button', true ) ){ ?>
		<div class="ec_cart_table_checkout_button_row">
			<a class="ec_cart_table_checkout_button" href="#" onclick="return wp_easycart_goto_page_v2( 'information', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );"<?php if( trim( get_option( 'ec_option_fb_pixel' ) ) != '' ){ ?> onclick="fbq('track', 'InitiateCheckout', {value: <?php echo number_format( $cartpage->order_totals->grand_total, 2, '.', '' ); ?>, currency: '<?php echo esc_attr( $GLOBALS['currency']->get_currency_code( ) ); ?>', num_items: '<?php echo esc_attr( $cartpage->cart->total_items ); ?>', contents: [<?php
				for( $i=0; $i<count( $cartpage->cart->cart ); $i++ ){
					if( $i > 0 )
						echo ", ";
					echo "{ id: '" . esc_js( $cartpage->cart->cart[$i]->product_id ) . "', quantity: " . esc_js( $cartpage->cart->cart[$i]->quantity ) . ", price: " . esc_js( $cartpage->cart->cart[$i]->unit_price ) . " }";
				}		
				?>]});"<?php }?><?php do_action( 'wpeasycart_checkout_button_ahref' ); ?>><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_checkout' ); ?>
				<div class="wp-easycart-ld-ring wp-easycart-ld-spin" style="color:#fff"></div>
			</a>
		</div>
		<?php } ?>

		<?php do_action( 'wp_easycart_cart_after_checkout_button', $cartpage ); ?>

		<div class="ec_cart_table_shopping_button">
			<?php do_action( 'wp_easycart_cart_before_continue_shopping' ); ?>
			<a class="ec_cart_table_continue_shopping" href="<?php echo esc_attr( $cartpage->return_to_store_page( $cartpage->store_page ) ); ?>"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_continue_shopping' ); ?></a>
		</div>
	</div>
</div>


