<section class="ec_cart_page">
<?php if( count( $cart->cart ) > 0 ){ ?>
<table class="ec_cart" cellspacing="0">
	<thead>
		<tr>
			<th class="ec_cartitem_head_name" colspan="3"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column1' )?></th>
			<th class="ec_cartitem_head_price"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column3' )?></th>
			<th class="ec_cartitem_head_quantity"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column4' )?></th>
			<th class="ec_cartitem_head_total"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_header_column5' )?></th>
		</tr>
	</thead>

	<tbody>
		<?php for( $cartitem_index = 0; $cartitem_index<count( $cart->cart ); $cartitem_index++ ){ ?>
		<tr class="ec_cartitem_error_row" id="ec_cartitem_max_error_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>">
			<td colspan="6"><?php echo wp_easycart_language( )->get_text( 'cart', 'cartitem_max_error' )?></td>
		</tr>

		<tr class="ec_cartitem_error_row" id="ec_cartitem_min_error_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>">
			<td colspan="6"><?php echo wp_easycart_language( )->get_text( 'cart', 'cartitem_min_error' )?></td>
		</tr>

		<tr class="ec_cartitem_row" id="ec_cartitem_row_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>">
			<td class="ec_cartitem_remove_column">
				<div class="ec_cartitem_delete" id="ec_cartitem_delete_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>" onclick="ec_cartitem_delete( '<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( $cart->cart[$cartitem_index]->model_number ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-delete-cart-item-' . (int) $cart->cart[$cartitem_index]->cartitem_id ) ); ?>' );<?php
				if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) {
					echo 'ec_ga4_remove_from_cart( \'' . esc_attr( $this->cart->cart[$cartitem_index]->model_number ) . '\', \'' . esc_attr( str_replace( "'", "\'", $this->cart->cart[$cartitem_index]->title ) ) . '\', jQuery( document.getElementById( \'ec_quantity_' . esc_attr( $this->cart->cart[$cartitem_index]->cartitem_id ) . '\' ) ).val(), \'' . esc_attr( number_format( $this->cart->cart[$cartitem_index]->unit_price, 2, '.', '' ) ) . '\', \'' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '\', \'' . esc_attr( $this->cart->cart[$cartitem_index]->manufacturer_name ) . '\', ' . esc_attr( ( get_option( 'ec_option_google_ga4_tag_manager' ) ) ? '1' : '0' ) . ' );';
				}
				?>"></div>
				<div class="ec_cartitem_deleting" id="ec_cartitem_deleting_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>"></div>
			</td>
			<td class="ec_cartitem_image"><img src="<?php echo esc_attr( $cart->cart[$cartitem_index]->get_image_url( ) ); ?>" alt="<?php echo esc_attr( $cart->cart[$cartitem_index]->title ); ?>" /></td>
			<td class="ec_cartitem_details">
				<a href="<?php echo esc_attr( $cart->cart[$cartitem_index]->get_title_link( ) ); ?>" class="ec_cartitem_title"><?php $cart->cart[$cartitem_index]->display_title( ); ?></a>
				<?php if( $cart->cart[$cartitem_index]->stock_quantity <= 0 && $cart->cart[$cartitem_index]->allow_backorders ){ ?>
				<div class="ec_cart_backorder_date"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backordered' ); ?><?php if( $cart->cart[$cartitem_index]->backorder_fill_date != "" ){ ?> <?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_backorder_until' ); ?> <?php echo wp_easycart_escape_html( $cart->cart[$cartitem_index]->backorder_fill_date ); ?><?php }?></div>
				<?php }?>
				<?php if( $cart->cart[$cartitem_index]->optionitem1_name ){ ?>
					<dl>
						<dt><?php echo esc_attr( $cart->cart[$cartitem_index]->optionitem1_name ); ?><?php if( $cart->cart[$cartitem_index]->optionitem1_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php }else if( $cart->cart[$cartitem_index]->optionitem1_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem1_price ) ); ?> )<?php } ?></dt>

					<?php if( $cart->cart[$cartitem_index]->optionitem2_name ){ ?>
						<dt><?php echo esc_attr( $cart->cart[$cartitem_index]->optionitem2_name ); ?><?php if( $cart->cart[$cartitem_index]->optionitem2_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php }else if( $cart->cart[$cartitem_index]->optionitem2_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem2_price ) ); ?> )<?php } ?></dt>
					<?php }?>

					<?php if( $cart->cart[$cartitem_index]->optionitem3_name ){ ?>
						<dt><?php echo esc_attr( $cart->cart[$cartitem_index]->optionitem3_name ); ?><?php if( $cart->cart[$cartitem_index]->optionitem3_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php }else if( $cart->cart[$cartitem_index]->optionitem3_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem3_price ) ); ?> )<?php } ?></dt>
					<?php }?>

					<?php if( $cart->cart[$cartitem_index]->optionitem4_name ){ ?>
						<dt><?php echo esc_attr( $cart->cart[$cartitem_index]->optionitem4_name ); ?><?php if( $cart->cart[$cartitem_index]->optionitem4_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php }else if( $cart->cart[$cartitem_index]->optionitem4_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem4_price ) ); ?> )<?php } ?></dt>
					<?php }?>

					<?php if( $cart->cart[$cartitem_index]->optionitem5_name ){ ?>
						<dt><?php echo esc_attr( $cart->cart[$cartitem_index]->optionitem5_name ); ?><?php if( $cart->cart[$cartitem_index]->optionitem5_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php }else if( $cart->cart[$cartitem_index]->optionitem5_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $cart->cart[$cartitem_index]->optionitem5_price ) ); ?> )<?php } ?></dt>
					<?php }?>
					</dl>
				<?php }?>

				<?php if( $cart->cart[$cartitem_index]->use_advanced_optionset ){ ?>
				<dl>
					<?php foreach( $cart->cart[$cartitem_index]->advanced_options as $advanced_option_set ){ ?>
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
								echo ' (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option_set->optionitem_price_override )  ). ')';
							} ?></dt>
						<?php }else if( $advanced_option_set->option_type == "dimensions1" || $advanced_option_set->option_type == "dimensions2" ){ ?>
						<strong><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>:</strong><br /><?php $dimensions = json_decode( $advanced_option_set->optionitem_value ); if( count( $dimensions ) == 2 ){ echo esc_attr( $dimensions[0] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } echo " x " . esc_attr( $dimensions[1] ); if( !get_option( 'ec_option_enable_metric_unit_display' ) ){ echo "\""; } }else if( count( $dimensions ) == 4 ){ echo esc_attr( $dimensions[0] . " " . $dimensions[1] . "\" x " . $dimensions[2] . " " . $dimensions[3] ) . "\""; } ?><br />

						<?php }else{ ?>
						<dt><?php echo wp_easycart_escape_html( $advanced_option_set->option_label ); ?>: <?php echo esc_attr( $advanced_option_set->optionitem_value ); ?><?php
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
						<?php } ?>
					<?php }?>
				</dl>
				<?php }?>

				<?php if( $cart->cart[$cartitem_index]->is_giftcard ){ ?>
				<dl>
					<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_name' ); ?>: <?php echo esc_attr( $cart->cart[$cartitem_index]->gift_card_to_name ); ?></dt>
					<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_recipient_email' ); ?>: <?php echo esc_attr( $cart->cart[$cartitem_index]->gift_card_email ); ?></dt>
					<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_sender_name' ); ?>: <?php echo esc_attr( $cart->cart[$cartitem_index]->gift_card_from_name ); ?></dt>
					<dt><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_gift_card_message' ); ?>: <?php echo esc_attr( $cart->cart[$cartitem_index]->gift_card_message ); ?></dt>
				</dl>
				<?php }?>

				<?php if( $cart->cart[$cartitem_index]->is_deconetwork ){ ?>
				<dl>
					<dt><?php echo esc_attr( $cart->cart[$cartitem_index]->deconetwork_options ); ?></dt>
					<dt><?php echo "<a href=\"https://" . esc_attr( get_option( 'ec_option_deconetwork_url' ) ) . esc_attr( $cart->cart[$cartitem_index]->deconetwork_edit_link ) . "\">" . wp_easycart_language( )->get_text( 'cart', 'deconetwork_edit' ) . "</a>"; ?></dt>
				</dl>
				<?php }?>
			</td>
			</td>
			<td class="ec_cartitem_price" id="ec_cartitem_price_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>"><?php echo esc_attr( $cart->cart[$cartitem_index]->get_unit_price( ) ); ?></td>
			<td class="ec_cartitem_quantity">
				<?php if( $cart->cart[$cartitem_index]->grid_quantity > 0 ){ ?>
					<?php echo esc_attr( $cart->cart[$cartitem_index]->grid_quantity ); ?>
				<?php }else if( $cart->cart[$cartitem_index]->is_deconetwork ){ ?>
					<?php echo esc_attr( $cart->cart[$cartitem_index]->quantity ); ?>
				<?php }else{ ?>
				<div class="ec_cartitem_updating" id="ec_cartitem_updating_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>"></div>
				<table class="ec_cartitem_quantity_table">
					<tbody>
						<tr>
							<td class="ec_minus_column">
								<input type="button" value="-" class="ec_minus" onclick="ec_minus_quantity( '<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( $cart->cart[$cartitem_index]->min_quantity ); ?>' );" /></td>
							<td class="ec_quantity_column"><input type="number" value="<?php echo esc_attr( $cart->cart[$cartitem_index]->quantity ); ?>" id="ec_quantity_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>" autocomplete="off" step="1" min="<?php if( $cart->cart[$cartitem_index]->min_purchase_quantity > 0 ){ echo esc_attr( $cart->cart[$cartitem_index]->min_purchase_quantity ); }else{ echo '1'; } ?>" class="ec_quantity" /></td>
							<td class="ec_plus_column"><input type="button" value="+" class="ec_plus" onclick="ec_plus_quantity( '<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( $cart->cart[$cartitem_index]->show_stock_quantity ); ?>', '<?php echo esc_attr( $cart->cart[$cartitem_index]->max_quantity ); ?>' );" /></td>
						</tr>
						<tr>
							<td colspan="3"><div class="ec_cartitem_update_button" id="ec_cartitem_update_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>" onclick="ec_cartitem_update( '<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-cart-item-' . (int) $cart->cart[$cartitem_index]->cartitem_id ) ); ?>' );"><?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_update_button' )?></div></td>
						</tr>
					</tbody>
				</table>
				<?php }?>
			</td>
			<td class="ec_cartitem_total" id="ec_cartitem_total_<?php echo esc_attr( $cart->cart[$cartitem_index]->cartitem_id ); ?>"><?php echo esc_attr( $cart->cart[$cartitem_index]->get_total( ) ); ?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<?php }else{ ?>

<div class="ec_cart_empty">
	<?php echo wp_easycart_language( )->get_text( 'cart', 'cart_empty_cart' ); ?>
</div>

<?php }?>
</section>