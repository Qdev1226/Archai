<div class="ec_color_widget">
	
	<div class="ec_details_option_row">
            
        <ul class="ec_details_swatches">
            	
            <?php foreach( $optionitems as $optionitem ){ ?>
        
       			<li class="ec_details_swatch ec_option ec_active<?php if( isset( $_GET['ec_optionitem_id'] ) && $_GET['ec_optionitem_id'] == $optionitem->optionitem_id ){ echo " ec_selected"; } ?>">
                	<a href="<?php echo esc_attr( $filter->get_link_string( 7 ) ) . "&amp;ec_optionitem_id=" . esc_attr( $optionitem->optionitem_id ); ?>">
                		<img src="<?php if( substr( $optionitem->optionitem_icon, 0, 7 ) == 'http://' || substr( $optionitem->optionitem_icon, 0, 8 ) == 'https://' ){ echo esc_attr( $optionitem->optionitem_icon ); }else{ echo esc_url( plugins_url( "/wp-easycart-data/products/swatches/" . $optionitem->optionitem_icon, EC_PLUGIN_DATA_DIRECTORY ) ); } ?>" title="<?php echo esc_attr( $optionitem->optionitem_name ); ?><?php if( $optionitem->optionitem_price > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?> )<?php }else if( $optionitem->optionitem_price < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ); ?> )<?php }else if( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime > 0 ){ ?> ( +<?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?> )<?php }else if( isset( $optionitem->optionitem_price_onetime ) && $optionitem->optionitem_price_onetime < 0 ){ ?> ( <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_onetime ) ); ?> <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ); ?> )<?php }else if( isset( $optionitem->optionitem_price_override ) && $optionitem->optionitem_price_override > -1 ){ ?> ( <?php echo wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $optionitem->optionitem_price_override ) ); ?> )<?php }?>" />
                    
                    </a>
                
                </li>
	
			<?php } ?>
                
        </ul>
    
    </div>

</div>