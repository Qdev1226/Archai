<div class="ec_cart_widget">
	<a href="<?php if( !$use_popup_minicart ){ echo esc_attr( $cart_page ); }else{ echo "#"; } ?>"<?php if( $use_popup_minicart ){ ?><?php if( $open_on_click ){ echo " onclick=\"ec_cart_widget_click( ); return false;\""; }?><?php if( $open_on_mouseover ){ echo " onclick=\"return false;\""; } ?><?php }?>>

	<div class="ec_cart_widget_button"<?php if( $open_on_mouseover ){ echo " onmouseover=\"ec_cart_widget_mouseover( );\""; } ?>><?php echo esc_attr( $link_text ); ?> (<span class="ec_cart_items_total"><?php echo esc_attr( $cart->total_items ); ?></span>)</div>

	</a>

	<?php if( $use_popup_minicart ){?>
	<div class="ec_cart_widget_minicart_wrap">

		<div class="ec_cart_widget_minicart_content">

			<div class="ec_cart_widget_minicart_title"><?php echo wp_easycart_language( )->convert_text( $link_text ); ?></div>

			<div class="ec_cart_widget_minicart_subtotal"><?php echo wp_easycart_language( )->get_text( "ec_minicart_widget", "subtotal_text" ); ?> <span class="ec_cart_price_total"><?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $subtotal ) ); ?></span></div>

			<a href="<?php echo esc_attr( $cart_page ); ?>"><div class="ec_cart_widget_minicart_checkout_button"><?php echo wp_easycart_language( )->get_text( "ec_minicart_widget", "checkout_button_text" ); ?></div></a>

			<div class="ec_cart_widget_minicart_product_holder">

				<div class="ec_cart_widget_minicart_product_padding">

					<?php for( $i=0; $i<count($cart->cart); $i++){

						echo '<div class="ec_cart_widget_minicart_product_title" id="ec_cart_widget_row_' . esc_attr( $cart->cart[$i]->cartitem_id ) . '">';
						$cart->cart[$i]->display_title_link( );
						echo ' x ';
						echo '<span id="ec_cartitem_items_' . esc_attr( $cart->cart[$i]->cartitem_id ) . '">' . esc_attr( $cart->cart[$i]->quantity ) . '</span>';
						echo ' @ ';
						echo '<span id="ec_cartitem_unit_price_' . esc_attr( $cart->cart[$i]->cartitem_id ) . '">' . esc_attr( $cart->cart[$i]->get_unit_price() ) . '</span>';
						echo '</div>';

					}?>

				</div>

			</div>

		 </div>

	</div>

	<div class="ec_cart_widget_minicart_bg"<?php if( $open_on_mouseover ){ echo " onmouseover=\"ec_cart_widget_mouseout( );\""; } ?>></div>

	<?php }?>

</div>