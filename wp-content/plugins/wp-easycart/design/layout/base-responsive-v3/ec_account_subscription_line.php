<div class="ec_account_subscription_line_<?php echo esc_attr( $i%2 ); ?>">
    <div class="ec_account_subscription_line_column5">
        <?php $subscription->display_subscription_link( wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_view_subscription_button' ) ); ?>
    </div>
    <div class="ec_account_subscription_line_column1">
        <strong><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_header_1' )?>: </strong>
        <?php $subscription->display_title( ); ?>
    </div>
    <div class="ec_account_subscription_line_column2">
        <strong><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_header_2' )?>: </strong>
        <?php $subscription->display_next_bill_date(  ); ?>
    </div>
    <div class="ec_account_subscription_line_column3">
        <strong><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_header_3' )?>: </strong>
        <?php $subscription->display_last_bill_date(  ); ?>
    </div>
    <div class="ec_account_subscription_line_column4">
        <strong><?php echo wp_easycart_language( )->get_text( 'account_subscriptions', 'account_subscriptions_header_4' )?>: </strong>
        <?php $subscription->display_price( ); ?>
    </div>
</div>