<?php $order_item->display_download_error( ); ?>

<tr class="ec_account_orderitem_row" id="ec_account_order_details_item_display_<?php $order_item->display_order_item_id(); ?>">

	<td class="ec_account_orderitem_image"><?php $order_item->display_image( "small" ); ?></td>

	<td class="ec_account_orderitem_details">

	<div class="ec_account_order_details_item_display_title"><?php $product_link = $order_item->get_product_link( ); if( $product_link ){ ?><a href="<?php echo esc_attr( $product_link ); ?>" target="_blank"><?php $order_item->display_title(); ?></a><?php }else{ $order_item->display_title(); } ?></div>

	<div class="ec_account_order_details_item_display_option"><?php echo esc_attr( $order_item->model_number ); ?></div>

	<?php 

	do_action( 'wpeasycart_order_detail_line_item', $order_item->model_number, $order_item->orderdetail_id );

	$advanced_optionitem_download_allowed = true;

	$advanced_optionitem_additional_downloads = array( );

	if ( ! $order_item->use_advanced_optionset || $order_item->use_both_option_types ) {

	?>

		<?php if( $order_item->has_option1( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_option1( ); ?><?php if( $order_item->has_option1_price( ) ){ ?> (<?php $order_item->display_option1_price( ); ?>)<?php }?>

			</div>

		<?php }?>

		<?php if( $order_item->has_option2( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_option2( ); ?><?php if( $order_item->has_option2_price( ) ){ ?> (<?php $order_item->display_option2_price( ); ?>)<?php }?>

			</div>

		<?php }?>

		<?php if( $order_item->has_option3( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

			<?php $order_item->display_option3( ); ?><?php if( $order_item->has_option3_price( ) ){ ?> (<?php $order_item->display_option3_price( ); ?>)<?php }?>

			</div>

		<?php }?>

		<?php if( $order_item->has_option4( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_option4( ); ?><?php if( $order_item->has_option4_price( ) ){ ?> (<?php $order_item->display_option4_price( ); ?>)<?php }?>

			</div>

		<?php }?>

		<?php if( $order_item->has_option5( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_option5( ); ?><?php if( $order_item->has_option5_price( ) ){ ?> (<?php $order_item->display_option5_price( ); ?>)<?php }?>

			</div>

		<?php }

	}//close basic options
		
	if ( $order_item->use_advanced_optionset || $order_item->use_both_option_types ) {

		$advanced_options = $this->mysqli->get_order_options( $order_item->orderdetail_id );

		foreach( $advanced_options as $advanced_option ){

			if( !$advanced_option->optionitem_allow_download ){
				$advanced_optionitem_download_allowed = false;
			}

			if( $advanced_option->download_addition_file ){
				$advanced_optionitem_additional_downloads[] = $advanced_option->download_addition_file;
			}

			if ( 'file' == $advanced_option->option_type ) {

				$file_split = explode( "/", $advanced_option->option_value );

				echo "<div class=\"ec_account_order_details_item_display_option\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $file_split[1] );

			} else if ( 'grid' == $advanced_option->option_type ) {

				echo "<div class=\"ec_account_order_details_item_display_option\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . wp_easycart_escape_html( $advanced_option->optionitem_name . " (" . $advanced_option->option_value . ")" );

			} else {

				echo "<div class=\"ec_account_order_details_item_display_option\">" . wp_easycart_escape_html( $advanced_option->option_label ) . ":</span> <span class=\"ec_option_name\">" . esc_attr( $advanced_option->option_value );

			}

			if ( $advanced_option->optionitem_enable_custom_price_label && ( $advanced_option->optionitem_price != 0 || ( isset( $advanced_option->optionitem_price ) && $advanced_option->optionitem_price != 0 ) || ( isset( $advanced_option->optionitem_price_onetime ) && $advanced_option->optionitem_price_onetime != 0 ) ) ) {
				echo '<span class="ec_account_line_optionitem_pricing">' . esc_attr( $advanced_option->optionitem_custom_price_label ) . '</span>';
			} else if ( $advanced_option->optionitem_price > 0 ) {
				echo '<span class="ec_account_line_optionitem_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
			} else if ( $advanced_option->optionitem_price < 0 ) {
				echo '<span class="ec_account_line_optionitem_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option->optionitem_price ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_item_adjustment' ) . ')</span>';
			} else if ( isset( $advanced_option->optionitem_price_onetime ) && $advanced_option->optionitem_price_onetime > 0 ) {
				echo '<span class="ec_account_line_optionitem_pricing"> (+' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
			} else if ( isset( $advanced_option->optionitem_price_onetime ) && $advanced_option->optionitem_price_onetime < 0 ) {
				echo '<span class="ec_account_line_optionitem_pricing"> (' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option->optionitem_price_onetime ) ) . ' ' . wp_easycart_language( )->get_text( 'cart', 'cart_order_adjustment' ) . ')</span>';
			} else if ( isset( $advanced_option->optionitem_price_override ) && $advanced_option->optionitem_price_override > -1 ) {
				echo '<span class="ec_account_line_optionitem_pricing"> (' . wp_easycart_language( )->get_text( 'cart', 'cart_item_new_price_option' ) . ' ' . esc_attr( $GLOBALS['currency']->get_currency_display( $advanced_option->optionitem_price_override ) ) . ')</span>';
			}

			echo '</div>';

		}

	}

	?>

		<?php if( $order_item->has_gift_card_message( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_gift_card_message( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_message' ) ); ?>

			</div>

		<?php }?>

		<?php if( $order_item->has_gift_card_from_name( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_gift_card_from_name( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_from' ) ); ?>

			</div>

		<?php }?>

		<?php if( $order_item->has_gift_card_to_name( ) ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_gift_card_to_name( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_gift_to' ) ); ?>

			</div>

		<?php }?>

		<?php if( $order_item->has_print_gift_card_link( ) && $this->is_approved ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_print_online_link( wp_easycart_language( )->get_text( "account_order_details", "account_orders_details_print_online" ) ); ?>

			</div>

		<?php }?>

		<?php if( $order_item->has_download_link( ) && $this->is_approved && $advanced_optionitem_download_allowed ){ ?>

			<div class="ec_account_order_details_item_display_option">

				<?php $order_item->display_download_link( wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_download' ), $advanced_optionitem_additional_downloads ); ?>

			</div>

			<?php if( $order_item->maximum_downloads_allowed > 0 ){ ?>

				<div class="ec_account_order_details_item_display_option">

				  <?php echo "<span id=\"ec_download_count_" . esc_attr( $order_item->orderdetail_id ) . "\">" . esc_attr( $order_item->download_count ) . "</span>" . "/" . "<span id=\"ec_download_count_max_" . esc_attr( $order_item->orderdetail_id ) . "\">" . esc_attr( $order_item->maximum_downloads_allowed ) . "</span> " . wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_downloads_used' ); ?>

				</div>

			<?php }?>

			<?php if( $order_item->download_timelimit_seconds > 0 ){ ?>

				<div class="ec_account_order_details_item_display_option">

					<?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_downloads_expire_time' ) . " " . esc_attr( $order_item->get_download_expire_date( "d M Y" ) ); ?>

				</div>

			<?php }?>

		<?php }?>

		<?php if( $order_item->include_code && $this->is_approved ){ 

			global $wpdb;
			$codes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_code WHERE ec_code.orderdetail_id = %d", $order_item->orderdetail_id ) );
			$code_list = "";
			for( $code_index = 0; $code_index < count( $codes ); $code_index++ ){
				if( $code_index > 0 )
					$code_list .= ", ";
				$code_list .= $codes[$code_index]->code_val;
			}

		?>

			<div class="ec_account_order_details_item_display_option"><?php echo wp_easycart_language( )->get_text( 'account_order_details', 'account_orders_details_your_codes' ); ?> <?php echo esc_attr( $code_list ); ?></div>

		<?php }?>
		
		<?php if( $order_item->subscription_signup_fee > 0 ) {?>

		<div class="ec_account_order_details_item_display_option"><?php echo wp_easycart_language( )->get_text( 'product_details', 'product_details_signup_fee_notice1' ); ?> <?php echo esc_attr( $GLOBALS['currency']->get_currency_display( $order_item->subscription_signup_fee ) ); ?></div>

		<?php }?>

		<?php do_action( 'wp_easycart_order_detail_item_optionitems', $order_item ); ?>

	</td>

	<td class="ec_account_orderitem_price">

		<div class="ec_account_order_details_item_display_unit_price">

		  <?php $order_item->display_unit_price(); ?>

		</div>

	</td>

	<td class="ec_account_orderitem_quantity">

		<div class="ec_account_order_details_item_display_quantity">

			<?php $order_item->display_quantity(); ?>

		</div>

	</td>

	<td class="ec_account_orderitem_total">

		<div class="ec_account_order_details_item_display_total_price">

			<?php $order_item->display_item_total(); ?>

		</div>

	</td>

</tr>