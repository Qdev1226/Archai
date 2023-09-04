<div class="ec_account_order_line_<?php echo esc_attr( $i%2 ); ?>">

	<div class="ec_account_order_line_column1"><?php $order->display_order_id( ); ?></div>

	<div class="ec_account_order_line_column2"><?php $order->display_order_date( ); ?></div>

	<div class="ec_account_order_line_column3"><?php $order->display_grand_total( ); ?></div>

	<div class="ec_account_order_line_column4"><?php $order->display_order_status( ); ?></div>

	<div class="ec_account_order_line_column5"><?php $order->display_order_link( wp_easycart_language( )->get_text( 'account_orders', 'account_orders_view_order_button' ) ); ?></div>

</div>