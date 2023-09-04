<?php if ( ! isset( $cartpage ) ) {
	$cartpage = $this;
} ?>
<?php
if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
	$theme = get_option( 'ec_option_stripe_payment_theme' );
	if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
		$pkey = get_option( 'ec_option_stripe_public_api_key' );
	} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
		$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
	} else {
		$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
	}
}
?>

<?php if ( '' != get_option( 'ec_option_google_ga4_property_id' ) ) { ?>
<script>
	jQuery( document ).ready( function() {
		<?php if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) { ?>
		dataLayer.push( { ecommerce: null } );
		dataLayer.push( {
			event: "begin_checkout",
			ecommerce: {
		<?php } else { ?>
		gtag( "event", "begin_checkout", {
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

<?php if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { $cartpage->display_page_one_form_start(); } ?>

<?php do_action( 'wp_easycart_onepage_checkout_top' ); ?>

<?php $allow_express_checkout = false;
if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) && get_option( 'ec_option_stripe_enable_apple_pay' ) ) {
	$allow_express_checkout = true;
} else if ( get_option( 'ec_option_payment_process_method' ) == 'square' && get_option( 'ec_option_square_digital_wallet' ) && ! get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
	$allow_express_checkout = true;
}
if ( $allow_express_checkout ) { 
$show_paypal = ( get_option( 'ec_option_payment_third_party' ) == 'paypal' && apply_filters( 'wp_easycart_allow_paypal_express', false ) && get_option( 'ec_option_paypal_express_page1_checkout' ) && ( get_option( 'ec_option_paypal_enable_credit' ) == '1' || get_option( 'ec_option_paypal_enable_pay_now' ) == '1' ) && $cartpage->order_totals->grand_total > 0 && ( $GLOBALS['ec_cart_data']->cart_data->user_id != "" || ( get_option( 'ec_option_allow_guest' ) && !$cartpage->has_downloads ) ) );
?>
<div class="ec_cart_express_checkout">
	<div class="ec_cart_express_checkout_header" style="margin-bottom:10px;">
		Express checkout
	</div>
		<div class="ec_cart_express_button_container<?php echo ( $show_paypal ) ? '' : ' ec_cart_express_button_container_single'; ?>">
			<?php if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) ) {
				$cartpage->print_stripe_payment_button();
			} else if ( get_option( 'ec_option_payment_process_method' ) == 'square' ) {
				$cartpage->print_square_payment_express();
			} ?>
			<?php if( $show_paypal ){ ?>
				<div id="paypal-button-container" style="float:left; width:100%; margin:10px 0;"></div>
				<div id="paypal-success-cover" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
					<style>
					@keyframes rotation{
						0%  { transform:rotate(0deg); }
						100%{ transform:rotate(359deg); }
					}
					</style>
					<div style='font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;'>
						<div class="paypal-checkout-logo">
							<img class="paypal-checkout-logo-pp" alt="pp" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMzIiIHZpZXdCb3g9IjAgMCAyNCAzMiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWluWU1pbiBtZWV0Ij4KICAgIDxwYXRoIGZpbGw9IiNmZmZmZmYiIG9wYWNpdHk9IjAuNyIgZD0iTSAyMC43MDIgOS40NDYgQyAyMC45ODIgNy4zNDcgMjAuNzAyIDUuOTQ3IDE5LjU3OCA0LjU0OCBDIDE4LjM2MSAzLjE0OCAxNi4yMDggMi41NDggMTMuNDkzIDIuNTQ4IEwgNS41MzYgMi41NDggQyA0Ljk3NCAyLjU0OCA0LjUwNiAyLjk0OCA0LjQxMiAzLjU0OCBMIDEuMTM2IDI1Ljc0IEMgMS4wNDIgMjYuMjM5IDEuMzIzIDI2LjYzOSAxLjc5MSAyNi42MzkgTCA2Ljc1MyAyNi42MzkgTCA2LjM3OCAyOC45MzggQyA2LjI4NSAyOS4yMzggNi42NTkgMjkuNjM4IDYuOTQgMjkuNjM4IEwgMTEuMTUzIDI5LjYzOCBDIDExLjYyMSAyOS42MzggMTEuOTk1IDI5LjIzOCAxMi4wODkgMjguNzM5IEwgMTIuMTgyIDI4LjUzOSBMIDEyLjkzMSAyMy4zNDEgTCAxMy4wMjUgMjMuMDQxIEMgMTMuMTE5IDIyLjQ0MSAxMy40OTMgMjIuMTQxIDEzLjk2MSAyMi4xNDEgTCAxNC42MTYgMjIuMTQxIEMgMTguNjQyIDIyLjE0MSAyMS43MzEgMjAuMzQyIDIyLjY2OCAxNS40NDMgQyAyMy4wNDIgMTMuMzQ0IDIyLjg1NSAxMS41NDUgMjEuODI1IDEwLjM0NSBDIDIxLjQ1MSAxMC4wNDYgMjEuMDc2IDkuNjQ2IDIwLjcwMiA5LjQ0NiBMIDIwLjcwMiA5LjQ0NiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgb3BhY2l0eT0iMC43IiBkPSJNIDIwLjcwMiA5LjQ0NiBDIDIwLjk4MiA3LjM0NyAyMC43MDIgNS45NDcgMTkuNTc4IDQuNTQ4IEMgMTguMzYxIDMuMTQ4IDE2LjIwOCAyLjU0OCAxMy40OTMgMi41NDggTCA1LjUzNiAyLjU0OCBDIDQuOTc0IDIuNTQ4IDQuNTA2IDIuOTQ4IDQuNDEyIDMuNTQ4IEwgMS4xMzYgMjUuNzQgQyAxLjA0MiAyNi4yMzkgMS4zMjMgMjYuNjM5IDEuNzkxIDI2LjYzOSBMIDYuNzUzIDI2LjYzOSBMIDcuOTcgMTguMzQyIEwgNy44NzYgMTguNjQyIEMgOC4wNjMgMTguMDQzIDguNDM4IDE3LjY0MyA5LjA5MyAxNy42NDMgTCAxMS40MzMgMTcuNjQzIEMgMTYuMDIxIDE3LjY0MyAxOS41NzggMTUuNjQzIDIwLjYwOCA5Ljk0NiBDIDIwLjYwOCA5Ljc0NiAyMC42MDggOS41NDYgMjAuNzAyIDkuNDQ2Ij48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDkuMjggOS40NDYgQyA5LjI4IDkuMTQ2IDkuNDY4IDguODQ2IDkuODQyIDguNjQ2IEMgOS45MzYgOC42NDYgMTAuMTIzIDguNTQ2IDEwLjIxNiA4LjU0NiBMIDE2LjQ4OSA4LjU0NiBDIDE3LjIzOCA4LjU0NiAxNy44OTMgOC42NDYgMTguNTQ4IDguNzQ2IEMgMTguNzM2IDguNzQ2IDE4LjgyOSA4Ljc0NiAxOS4xMSA4Ljg0NiBDIDE5LjIwNCA4Ljk0NiAxOS4zOTEgOC45NDYgMTkuNTc4IDkuMDQ2IEMgMTkuNjcyIDkuMDQ2IDE5LjY3MiA5LjA0NiAxOS44NTkgOS4xNDYgQyAyMC4xNCA5LjI0NiAyMC40MjEgOS4zNDYgMjAuNzAyIDkuNDQ2IEMgMjAuOTgyIDcuMzQ3IDIwLjcwMiA1Ljk0NyAxOS41NzggNC42NDggQyAxOC4zNjEgMy4yNDggMTYuMjA4IDIuNTQ4IDEzLjQ5MyAyLjU0OCBMIDUuNTM2IDIuNTQ4IEMgNC45NzQgMi41NDggNC41MDYgMy4wNDggNC40MTIgMy41NDggTCAxLjEzNiAyNS43NCBDIDEuMDQyIDI2LjIzOSAxLjMyMyAyNi42MzkgMS43OTEgMjYuNjM5IEwgNi43NTMgMjYuNjM5IEwgNy45NyAxOC4zNDIgTCA5LjI4IDkuNDQ2IFoiPjwvcGF0aD4KICAgIDxnIHRyYW5zZm9ybT0ibWF0cml4KDAuNDk3NzM3LCAwLCAwLCAwLjUyNjEyLCAxLjEwMTQ0LCAwLjYzODY1NCkiIG9wYWNpdHk9IjAuMiI+CiAgICAgICAgPHBhdGggZmlsbD0iIzIzMWYyMCIgZD0iTTM5LjMgMTYuN2MwLjkgMC41IDEuNyAxLjEgMi4zIDEuOCAxIDEuMSAxLjYgMi41IDEuOSA0LjEgMC4zLTMuMi0wLjItNS44LTEuOS03LjgtMC42LTAuNy0xLjMtMS4yLTIuMS0xLjdDMzkuNSAxNC4yIDM5LjUgMTUuNCAzOS4zIDE2Ljd6Ij48L3BhdGg+CiAgICAgICAgPHBhdGggZmlsbD0iIzIzMWYyMCIgZD0iTTAuNCA0NS4yTDYuNyA1LjZDNi44IDQuNSA3LjggMy43IDguOSAzLjdoMTZjNS41IDAgOS44IDEuMiAxMi4yIDMuOSAxLjIgMS40IDEuOSAzIDIuMiA0LjggMC40LTMuNi0wLjItNi4xLTIuMi04LjRDMzQuNyAxLjIgMzAuNCAwIDI0LjkgMEg4LjljLTEuMSAwLTIuMSAwLjgtMi4zIDEuOUwwIDQ0LjFDMCA0NC41IDAuMSA0NC45IDAuNCA0NS4yeiI+PC9wYXRoPgogICAgICAgIDxwYXRoIGZpbGw9IiMyMzFmMjAiIGQ9Ik0xMC43IDQ5LjRsLTAuMSAwLjZjLTAuMSAwLjQgMC4xIDAuOCAwLjQgMS4xbDAuMy0xLjdIMTAuN3oiPjwvcGF0aD4KICAgIDwvZz4KPC9zdmc+Cg=="><img class="paypal-checkout-logo-paypal" alt="paypal" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjMyIiB2aWV3Qm94PSIwIDAgMTAwIDMyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaW5ZTWluIG1lZXQiPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTSAxMiA1LjMxNSBMIDQuMiA1LjMxNSBDIDMuNyA1LjMxNSAzLjIgNS43MTUgMy4xIDYuMjE1IEwgMCAyNi4yMTUgQyAtMC4xIDI2LjYxNSAwLjIgMjYuOTE1IDAuNiAyNi45MTUgTCA0LjMgMjYuOTE1IEMgNC44IDI2LjkxNSA1LjMgMjYuNTE1IDUuNCAyNi4wMTUgTCA2LjIgMjAuNjE1IEMgNi4zIDIwLjExNSA2LjcgMTkuNzE1IDcuMyAxOS43MTUgTCA5LjggMTkuNzE1IEMgMTQuOSAxOS43MTUgMTcuOSAxNy4yMTUgMTguNyAxMi4zMTUgQyAxOSAxMC4yMTUgMTguNyA4LjUxNSAxNy43IDcuMzE1IEMgMTYuNiA2LjAxNSAxNC42IDUuMzE1IDEyIDUuMzE1IFogTSAxMi45IDEyLjYxNSBDIDEyLjUgMTUuNDE1IDEwLjMgMTUuNDE1IDguMyAxNS40MTUgTCA3LjEgMTUuNDE1IEwgNy45IDEwLjIxNSBDIDcuOSA5LjkxNSA4LjIgOS43MTUgOC41IDkuNzE1IEwgOSA5LjcxNSBDIDEwLjQgOS43MTUgMTEuNyA5LjcxNSAxMi40IDEwLjUxNSBDIDEyLjkgMTAuOTE1IDEzLjEgMTEuNjE1IDEyLjkgMTIuNjE1IFoiPjwvcGF0aD4KICAgIDxwYXRoIGZpbGw9IiNmZmZmZmYiIGQ9Ik0gMzUuMiAxMi41MTUgTCAzMS41IDEyLjUxNSBDIDMxLjIgMTIuNTE1IDMwLjkgMTIuNzE1IDMwLjkgMTMuMDE1IEwgMzAuNyAxNC4wMTUgTCAzMC40IDEzLjYxNSBDIDI5LjYgMTIuNDE1IDI3LjggMTIuMDE1IDI2IDEyLjAxNSBDIDIxLjkgMTIuMDE1IDE4LjQgMTUuMTE1IDE3LjcgMTkuNTE1IEMgMTcuMyAyMS43MTUgMTcuOCAyMy44MTUgMTkuMSAyNS4yMTUgQyAyMC4yIDI2LjUxNSAyMS45IDI3LjExNSAyMy44IDI3LjExNSBDIDI3LjEgMjcuMTE1IDI5IDI1LjAxNSAyOSAyNS4wMTUgTCAyOC44IDI2LjAxNSBDIDI4LjcgMjYuNDE1IDI5IDI2LjgxNSAyOS40IDI2LjgxNSBMIDMyLjggMjYuODE1IEMgMzMuMyAyNi44MTUgMzMuOCAyNi40MTUgMzMuOSAyNS45MTUgTCAzNS45IDEzLjExNSBDIDM2IDEyLjkxNSAzNS42IDEyLjUxNSAzNS4yIDEyLjUxNSBaIE0gMzAuMSAxOS44MTUgQyAyOS43IDIxLjkxNSAyOC4xIDIzLjQxNSAyNS45IDIzLjQxNSBDIDI0LjggMjMuNDE1IDI0IDIzLjExNSAyMy40IDIyLjQxNSBDIDIyLjggMjEuNzE1IDIyLjYgMjAuODE1IDIyLjggMTkuODE1IEMgMjMuMSAxNy43MTUgMjQuOSAxNi4yMTUgMjcgMTYuMjE1IEMgMjguMSAxNi4yMTUgMjguOSAxNi42MTUgMjkuNSAxNy4yMTUgQyAzMCAxNy44MTUgMzAuMiAxOC43MTUgMzAuMSAxOS44MTUgWiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTSA1NS4xIDEyLjUxNSBMIDUxLjQgMTIuNTE1IEMgNTEgMTIuNTE1IDUwLjcgMTIuNzE1IDUwLjUgMTMuMDE1IEwgNDUuMyAyMC42MTUgTCA0My4xIDEzLjMxNSBDIDQzIDEyLjgxNSA0Mi41IDEyLjUxNSA0Mi4xIDEyLjUxNSBMIDM4LjQgMTIuNTE1IEMgMzggMTIuNTE1IDM3LjYgMTIuOTE1IDM3LjggMTMuNDE1IEwgNDEuOSAyNS41MTUgTCAzOCAzMC45MTUgQyAzNy43IDMxLjMxNSAzOCAzMS45MTUgMzguNSAzMS45MTUgTCA0Mi4yIDMxLjkxNSBDIDQyLjYgMzEuOTE1IDQyLjkgMzEuNzE1IDQzLjEgMzEuNDE1IEwgNTUuNiAxMy40MTUgQyA1NS45IDEzLjExNSA1NS42IDEyLjUxNSA1NS4xIDEyLjUxNSBaIj48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDY3LjUgNS4zMTUgTCA1OS43IDUuMzE1IEMgNTkuMiA1LjMxNSA1OC43IDUuNzE1IDU4LjYgNi4yMTUgTCA1NS41IDI2LjExNSBDIDU1LjQgMjYuNTE1IDU1LjcgMjYuODE1IDU2LjEgMjYuODE1IEwgNjAuMSAyNi44MTUgQyA2MC41IDI2LjgxNSA2MC44IDI2LjUxNSA2MC44IDI2LjIxNSBMIDYxLjcgMjAuNTE1IEMgNjEuOCAyMC4wMTUgNjIuMiAxOS42MTUgNjIuOCAxOS42MTUgTCA2NS4zIDE5LjYxNSBDIDcwLjQgMTkuNjE1IDczLjQgMTcuMTE1IDc0LjIgMTIuMjE1IEMgNzQuNSAxMC4xMTUgNzQuMiA4LjQxNSA3My4yIDcuMjE1IEMgNzIgNi4wMTUgNzAuMSA1LjMxNSA2Ny41IDUuMzE1IFogTSA2OC40IDEyLjYxNSBDIDY4IDE1LjQxNSA2NS44IDE1LjQxNSA2My44IDE1LjQxNSBMIDYyLjYgMTUuNDE1IEwgNjMuNCAxMC4yMTUgQyA2My40IDkuOTE1IDYzLjcgOS43MTUgNjQgOS43MTUgTCA2NC41IDkuNzE1IEMgNjUuOSA5LjcxNSA2Ny4yIDkuNzE1IDY3LjkgMTAuNTE1IEMgNjguNCAxMC45MTUgNjguNSAxMS42MTUgNjguNCAxMi42MTUgWiI+PC9wYXRoPgogICAgPHBhdGggZmlsbD0iI2ZmZmZmZiIgZD0iTSA5MC43IDEyLjUxNSBMIDg3IDEyLjUxNSBDIDg2LjcgMTIuNTE1IDg2LjQgMTIuNzE1IDg2LjQgMTMuMDE1IEwgODYuMiAxNC4wMTUgTCA4NS45IDEzLjYxNSBDIDg1LjEgMTIuNDE1IDgzLjMgMTIuMDE1IDgxLjUgMTIuMDE1IEMgNzcuNCAxMi4wMTUgNzMuOSAxNS4xMTUgNzMuMiAxOS41MTUgQyA3Mi44IDIxLjcxNSA3My4zIDIzLjgxNSA3NC42IDI1LjIxNSBDIDc1LjcgMjYuNTE1IDc3LjQgMjcuMTE1IDc5LjMgMjcuMTE1IEMgODIuNiAyNy4xMTUgODQuNSAyNS4wMTUgODQuNSAyNS4wMTUgTCA4NC4zIDI2LjAxNSBDIDg0LjIgMjYuNDE1IDg0LjUgMjYuODE1IDg0LjkgMjYuODE1IEwgODguMyAyNi44MTUgQyA4OC44IDI2LjgxNSA4OS4zIDI2LjQxNSA4OS40IDI1LjkxNSBMIDkxLjQgMTMuMTE1IEMgOTEuNCAxMi45MTUgOTEuMSAxMi41MTUgOTAuNyAxMi41MTUgWiBNIDg1LjUgMTkuODE1IEMgODUuMSAyMS45MTUgODMuNSAyMy40MTUgODEuMyAyMy40MTUgQyA4MC4yIDIzLjQxNSA3OS40IDIzLjExNSA3OC44IDIyLjQxNSBDIDc4LjIgMjEuNzE1IDc4IDIwLjgxNSA3OC4yIDE5LjgxNSBDIDc4LjUgMTcuNzE1IDgwLjMgMTYuMjE1IDgyLjQgMTYuMjE1IEMgODMuNSAxNi4yMTUgODQuMyAxNi42MTUgODQuOSAxNy4yMTUgQyA4NS41IDE3LjgxNSA4NS43IDE4LjcxNSA4NS41IDE5LjgxNSBaIj48L3BhdGg+CiAgICA8cGF0aCBmaWxsPSIjZmZmZmZmIiBkPSJNIDk1LjEgNS45MTUgTCA5MS45IDI2LjIxNSBDIDkxLjggMjYuNjE1IDkyLjEgMjYuOTE1IDkyLjUgMjYuOTE1IEwgOTUuNyAyNi45MTUgQyA5Ni4yIDI2LjkxNSA5Ni43IDI2LjUxNSA5Ni44IDI2LjAxNSBMIDEwMCA2LjExNSBDIDEwMC4xIDUuNzE1IDk5LjggNS40MTUgOTkuNCA1LjQxNSBMIDk1LjggNS40MTUgQyA5NS40IDUuMzE1IDk1LjIgNS41MTUgOTUuMSA1LjkxNSBaIj48L3BhdGg+Cjwvc3ZnPgo=">
						</div>
						<div class="paypal-checkout-loader">
							<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
						</div>
					</div>
				</div>
				<?php $cartpage->print_paypal_express_button_code(); ?>
			<?php }?>
		<?php /* <div id="wpec-express-checkout-element"></div> */ ?>
	</div>
	<div id="error-message"></div>
	<div class="ec_cart_express_checkout_divider">
		<div>OR</div>
	</div>
</div>
<?php } ?>

<?php if( $GLOBALS['ec_cart_data']->cart_data->user_id == "" ){ ?>
	<div class="ec_cart_header ec_cart_header_no_border" id="ec_cart_contact_header">
		<span>Contact</span>
		<div style="float:right; font-size:14px; text-transform:none; letter-spacing:1px;">
			<a href="" onclick="return ec_cart_toggle_login_v2();" id="ec_user_login_link"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_already_have_account' ); ?></a>
			<a href="" onclick="return ec_cart_toggle_login_v2();" id="ec_user_login_cancel_link" style="display:none"><?php echo wp_easycart_language( )->get_text( 'account_personal_information', 'account_personal_information_cancel_link' ); ?></a>
		</div>
	</div>

	<div id="ec_user_login_form">

		<input type="hidden" name="ec_login_selector" value="login" />
		<div class="ec_cart_input_row">
			<label for="ec_cart_login_email"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_email_label' ); ?>*</label>
			<input type="text" id="ec_cart_login_email" name="ec_cart_login_email" novalidate />
		</div>
		<div class="ec_cart_error_row" id="ec_cart_login_email_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_email_label' ); ?>
		</div>

		<div class="ec_cart_input_row">
			<?php do_action( 'wpeasycart_pre_login_password_display' ); ?>
			<label for="ec_cart_login_password"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_password_label' ); ?>*</label>
			<input type="password" id="ec_cart_login_password" name="ec_cart_login_password" />
		</div>
		<div class="ec_cart_error_row" id="ec_cart_login_password_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_password_label' ); ?>
		</div>

		<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
		<input type="hidden" id="ec_grecaptcha_response_login" name="ec_grecaptcha_response_login" value="" />
		<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_login_recaptcha"></div>
		<?php }?>
		
		<div class="ec_cart_error_row" id="ec_cart_login_invalid_error">
			<?php echo wp_easycart_language( )->get_text( 'ec_errors', 'login_failed' ); ?>
		</div>
		
		<div class="ec_cart_error_row" id="ec_cart_not_activated_error">
			<?php echo wp_easycart_language( )->get_text( 'ec_errors', 'not_activated' ); ?>
		</div>

		<div class="ec_cart_button_row">
			<a href="<?php echo esc_attr( $cartpage->account_page ); ?>?ec_page=forgot_password" class="ec_account_login_link"><?php echo wp_easycart_language( )->get_text( 'account_login', 'account_login_forgot_password_link' ); ?></a>
			<input type="submit" value="<?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_button' ); ?>" class="ec_cart_button" onclick="return ec_cart_validate_login_v2( '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-cart-login-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );" style="width:unset; padding:14px 25px;" />
			<a href="" onclick="return ec_cart_toggle_login_v2();" class="ec_account_login_cancel_link"><?php echo wp_easycart_language( )->get_text( 'account_personal_information', 'account_personal_information_cancel_link' ); ?></a>
		</div>
		<div class="ec_cart_create_account_row_v2">
			<a href="<?php echo esc_url( $cartpage->account_page . $cartpage->permalink_divider . 'ec_page=register' ); ?>">Create Account</a>
		</div>

		<?php if( get_option( 'ec_option_cache_prevent' ) && get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
		<script type="text/javascript">
			if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
				var wpeasycart_login_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_login_recaptcha' ), {
					'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
					'callback' : wpeasycart_login_recaptcha_callback
				});
			}
		</script>
		<?php }?>
		<?php if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { $cartpage->display_page_one_form_end(); } ?>

	</div>

	<div class="ec_cart_input_row" id="ec_user_contact_form">
		<?php if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) && get_option( 'ec_option_stripe_link' ) ) { ?>
			<div id="link-authentication-element" class="ec_cart_stripe_address_is_init">
				<!-- Elements will create form elements here -->
			</div>
			<div class="ec_cart_error_row" id="ec_email_order1_error">
				Please enter a valid email address.
			</div>
			<div class="ec_cart_error_row" id="ec_create_account_email_error">
				The email address already has an account, please login to continue.
			</div>
			<input type="hidden" id="ec_contact_email" value="" />
			<input type="hidden" id="ec_contact_email_complete" value="0" />
		<?php } else { ?>
			<label for="ec_contact_email"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>*</label>
			<?php $cartpage->ec_cart_display_contact_email_input(); ?>
			<div class="ec_cart_error_row" id="ec_contact_email_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
			</div>
			<div class="ec_cart_error_row" id="ec_create_account_email_error">
				The email address already has an account, please login to continue.
			</div>
		<?php } ?>

		<?php if( get_option( 'ec_option_show_subscriber_feature' ) ){ ?>
		<div class="ec_cart_input_row">
			<input type="checkbox" name="ec_cart_is_subscriber" id="ec_cart_is_subscriber" class="ec_account_register_input_field" value="1" />
			<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_subscribe' )?>
		</div>
		<?php } ?>
	</div>

	<?php if ( ( ! get_option( 'ec_option_allow_guest' ) || $cartpage->has_downloads ) && ( '' == $GLOBALS['ec_cart_data']->cart_data->email || ( '' != $GLOBALS['ec_cart_data']->cart_data->is_guest && $GLOBALS['ec_cart_data']->cart_data->is_guest ) || ! $GLOBALS['ec_user']->user_id ) ) { ?>
	<div id="ec_user_create_form" style="display:block;">
		<div class="ec_cart_header ec_cart_header_no_border" id="ec_cart_create_account_header">
			<?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_create_account' ); ?>
		</div>

		<?php if( get_option( 'ec_option_use_contact_name' ) ){ ?>
			<div class="ec_cart_input_row">
				<div class="ec_cart_input_left_half">
					<label for="ec_contact_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>*</label>
					<?php $cartpage->ec_cart_display_contact_first_name_input(); ?>
					<div class="ec_cart_error_row" id="ec_contact_first_name_error">
						<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_first_name' ); ?>
					</div>
				</div>
				<div class="ec_cart_input_right_half">
					<label for="ec_contact_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>*</label>
					<?php $cartpage->ec_cart_display_contact_last_name_input(); ?>
					<div class="ec_cart_error_row" id="ec_contact_last_name_error">
						<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_last_name' ); ?>
					</div>
				</div>
			</div>
		<?php }?>

		<div class="ec_cart_input_row">
			<?php do_action( 'wpeasycart_pre_password_display' ); ?>
			<label for="ec_contact_password"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_password' ); ?>*</label>
			<?php $cartpage->ec_cart_display_contact_password_input(); ?>
			<div class="ec_cart_error_row" id="ec_contact_password_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_length_error' ); ?>
			</div>
		</div>

		<?php if( get_option( 'ec_option_require_account_terms' ) ){ ?>
			<div class="ec_cart_error_row" id="ec_terms_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_payment_accept_terms' )?> 
			</div>
			<div class="ec_cart_input_row">
				<input type="checkbox" name="ec_terms_agree" id="ec_terms_agree" class="ec_account_register_input_field" />
				<?php echo wp_easycart_language( )->get_text( 'account_register', 'account_register_agree_terms' )?>
			</div>
		<?php }?>
		
		<?php if( get_option( 'ec_option_user_order_notes' ) ){ ?>
			<div class="ec_cart_header">
				<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
			</div>
			<div class="ec_cart_input_row">
			<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_message' ); ?>
				<textarea name="ec_order_notes" id="ec_order_notes"><?php if( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" ){ echo esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes ); } ?></textarea>
			</div>
		<?php }?>

		<?php do_action( 'wpeasycart_order_notes_after' ); ?>

		<?php if( get_option( 'ec_option_enable_extra_email' ) ) { ?>
			<div class="ec_cart_header">
				<?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email_other' ); ?>
			</div>

			<div class="ec_cart_input_row">
				<label for="ec_contact_email_other"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email_other_label' ); ?></label>
				<?php $cartpage->ec_cart_display_contact_email_other_input(); ?>
				<div class="ec_cart_error_row" id="ec_contact_email_other_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
				</div>
			</div>
		<?php }?>

		<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
			<input type="hidden" id="ec_grecaptcha_response_register" name="ec_grecaptcha_response_register" value="" />
			<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_register_recaptcha"></div>
		<?php }?>
	</div>
	<?php } ?>

<?php } else { // close section for NON logged in user ?>
	<div class="ec_cart_header ec_cart_header_no_border">
		<?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_title' ); ?>
	</div>

	<div class="ec_cart_input_row" id="ec_cart_logged_in_section">
		<?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_account_information_text' ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->first_name, ENT_QUOTES ) ); ?> <?php echo esc_attr( htmlspecialchars( $GLOBALS['ec_user']->last_name, ENT_QUOTES ) ); ?>, <a href="<?php echo esc_attr( $cartpage->cart_page . $cartpage->permalink_divider . "ec_cart_action=logout" ); ?>" onclick="ec_cart_logout_v2();"><?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_account_information_logout_link' ); ?></a> <?php echo wp_easycart_language( )->get_text( 'cart_login', 'cart_login_account_information_text2' ); ?>
	</div>
<?php }?>

<?php if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) && get_option( 'ec_option_stripe_address_autocomplete' ) ) {
	// Shipping First
	if( get_option( 'ec_option_use_shipping' ) && $cartpage->shipping_address_allowed && ( $cartpage->cart->shippable_total_items > 0 || $cartpage->order_totals->handling_total > 0 || $cartpage->cart->excluded_shippable_total_items > 0 ) ) { ?>
		<div class="ec_cart_header ec_cart_header_no_border" id="ec_cart_shipping_header">
			<?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
		</div>
		<div id="shipping-address-element" class="ec_cart_stripe_address_is_init">
			<!-- Elements will create form elements here -->
		</div>
		<?php if( get_option( 'ec_option_user_order_notes' ) ){ ?>
		<div class="ec_cart_header ec_cart_header_no_border">
			<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ); ?>
		</div>
		<div class="ec_cart_input_row">
			<?php echo wp_easycart_language( )->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_message' ); ?>
			<textarea name="ec_order_notes" id="ec_order_notes"><?php if( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" ){ echo esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes ); } ?></textarea>
		</div>
		<?php }?>
		<?php $shipping_selector = ( ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" ) || ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "" && ( $GLOBALS['ec_cart_data']->cart_data->billing_first_name != $GLOBALS['ec_cart_data']->cart_data->shipping_first_name || $GLOBALS['ec_cart_data']->cart_data->billing_last_name != $GLOBALS['ec_cart_data']->cart_data->shipping_last_name || $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 || $GLOBALS['ec_cart_data']->cart_data->billing_city != $GLOBALS['ec_cart_data']->cart_data->shipping_city || $GLOBALS['ec_cart_data']->cart_data->billing_state != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) ) ) ? 1 : 0; ?>
		<input type="hidden" name="ec_shipping_selector" id="ec_shipping_selector" value="<?php echo esc_attr( $shipping_selector ); ?>" />
		<input type="hidden" id="ec_shipping_complete" value="0" />
		<input type="hidden" id="ec_shipping_name" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ); ?>" />
		<input type="hidden" id="ec_shipping_last_name" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ); ?>" />
		<input type="hidden" id="ec_shipping_company_name" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ); ?>" />
		<input type="hidden" id="ec_shipping_address_line_1" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ); ?>" />
		<input type="hidden" id="ec_shipping_address_line_2" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ); ?>" />
		<input type="hidden" id="ec_shipping_city" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_city ); ?>" />
		<input type="hidden" id="ec_shipping_state" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_state ); ?>" />
		<input type="hidden" id="ec_shipping_zip" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ); ?>" />
		<input type="hidden" id="ec_shipping_country" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_country ); ?>" />
		<input type="hidden" id="ec_shipping_phone" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->shipping_phone ); ?>" />
	<?php } else { // Billing Only, Not Shippable ?>
		<div class="ec_cart_header ec_cart_header_no_border">
			Billing Address
		</div>

		<div id="billing-address-element" class="ec_cart_stripe_address_is_init">
			<!-- Elements will create form elements here -->
		</div>
		<input type="hidden" id="ec_billing_name" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_first_name ); ?> <?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_last_name ); ?>" />
		<input type="hidden" id="ec_billing_last_name" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_last_name ); ?>" />
		<input type="hidden" id="ec_billing_company_name" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_company_name ); ?>" />
		<input type="hidden" id="ec_billing_address_line_1" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ); ?>" />
		<input type="hidden" id="ec_billing_address_line_2" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ); ?>" />
		<input type="hidden" id="ec_billing_city" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_city ); ?>" />
		<input type="hidden" id="ec_billing_state" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_state ); ?>" />
		<input type="hidden" id="ec_billing_zip" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_zip ); ?>" />
		<input type="hidden" id="ec_billing_country" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_country ); ?>" />
		<input type="hidden" id="ec_billing_phone" value="<?php echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->billing_phone ); ?>" />
	<?php
	}
} else { // Do not use stripe auto complete
	if ( get_option( 'ec_option_use_shipping' ) && $cartpage->shipping_address_allowed && ( $cartpage->cart->shippable_total_items > 0 || $cartpage->order_totals->handling_total > 0 || $cartpage->cart->excluded_shippable_total_items > 0 ) ) { // Shipping First ?>
		<div class="ec_cart_header ec_cart_header_no_border" id="ec_cart_shipping_header">
			<?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_title' ); ?>
		</div>

		<?php if( get_option( 'ec_option_display_country_top' ) ){ ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_shipping_country"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>*</label>
				<?php $cartpage->display_shipping_input( "country" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_country_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>
				</div>
			</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<div class="ec_cart_input_left_half">
				<label for="ec_cart_shipping_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_first_name' ); ?>*</label>
				<?php $cartpage->display_shipping_input( "first_name" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_first_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_first_name' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_right_half">
				<label for="ec_cart_shipping_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_last_name' ); ?>*</label>
				<?php $cartpage->display_shipping_input( "last_name" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_last_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_last_name' ); ?>
				</div>
			</div>
		</div>
		<?php if( get_option( 'ec_option_enable_company_name' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_company_name"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_company_name' ); ?></label>
			<?php $cartpage->display_shipping_input( "company_name" ); ?>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_address"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address' ); ?>*</label>
			<?php $cartpage->display_shipping_input( "address" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_address_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address' ); ?>
			</div>
		</div>
		<?php if( get_option( 'ec_option_use_address2' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_address2"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_address2' ); ?></label>
			<?php $cartpage->display_shipping_input( "address2" ); ?>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_city"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_city' ); ?>*</label>
			<?php $cartpage->display_shipping_input( "city" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_city_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_city' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_row">
			<div class="ec_cart_input_left_half">
				<label for="ec_cart_shipping_state"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_state' ); ?><span id="ec_shipping_state_required">*</span></label>
				<?php $cartpage->display_shipping_input( "state" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_state_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_state' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_right_half">
				<label for="ec_cart_shipping_zip"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_zip' ); ?>*</label>
				<?php $cartpage->display_shipping_input( "zip" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_zip_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_zip' ); ?>
				</div>
			</div>
		</div>
		<?php if( !get_option( 'ec_option_display_country_top' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_shipping_country"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>*</label>
			<?php $cartpage->display_shipping_input( "country" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_shipping_country_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_country' ); ?>
			</div>
		</div>
		<?php }?>
		<?php if( get_option( 'ec_option_collect_user_phone' ) ){ ?>
			<div class="ec_cart_input_row">
				<label for="ec_cart_shipping_phone"><?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_phone' ); ?><?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>*<?php }?></label>
				<?php $cartpage->display_shipping_input( "phone" ); ?>
				<?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>
				<div class="ec_cart_error_row" id="ec_cart_shipping_phone_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_shipping_information', 'cart_shipping_information_phone' ); ?>
				</div>
				<?php }?>
			</div>
		<?php } ?>

		<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
		<script type="text/javascript">
			wpeasycart_cart_shipping_country_update( );
			jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).change( wpeasycart_cart_shipping_country_update );
		</script>
		<?php }?>

	<?php } else { // not shippable products, billing only ?>
		<div class="ec_cart_header ec_cart_header_no_border">
			<?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_title' ); ?>
		</div>

		<?php if( get_option( 'ec_option_display_country_top' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_country"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
			<?php $cartpage->display_billing_input( "country" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
			</div>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<div class="ec_cart_input_left_half">
				<label for="ec_cart_billing_first_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>*</label>
				<?php $cartpage->display_billing_input( "first_name" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_first_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_right_half">
				<label for="ec_cart_billing_last_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>*</label>
				<?php $cartpage->display_billing_input( "last_name" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_last_name_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ); ?>
				</div>
			</div>
		</div>
		<?php if( get_option( 'ec_option_enable_company_name' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_company_name"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ); ?></label>
			<?php $cartpage->display_billing_input( "company_name" ); ?>
		</div>
		<?php }?>
		<?php if( get_option( 'ec_option_collect_vat_registration_number' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_vat_registration_number"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' ); ?></label>
			<?php $cartpage->display_vat_registration_number_input( ); ?>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_address"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>*</label>
			<?php $cartpage->display_billing_input( "address" ); ?>
		</div>
		<div class="ec_cart_error_row" id="ec_cart_billing_address_error">
			<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address' ); ?>
		</div>
		<?php if( get_option( 'ec_option_use_address2' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_address2"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_address2' ); ?></label>
			<?php $cartpage->display_billing_input( "address2" ); ?>
		</div>
		<?php }?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_city"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>*</label>
			<?php $cartpage->display_billing_input( "city" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_city_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_city' ); ?>
			</div>
		</div>
		<div class="ec_cart_input_row">
			<div class="ec_cart_input_left_half">
				<label for="ec_cart_billing_state"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?><span id="ec_billing_state_required">*</span></label>
				<?php $cartpage->display_billing_input( "state" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_state_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' ); ?>
				</div>
			</div>
			<div class="ec_cart_input_right_half">
				<label for="ec_cart_billing_zip"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>*</label>
				<?php $cartpage->display_billing_input( "zip" ); ?>
				<div class="ec_cart_error_row" id="ec_cart_billing_zip_error">
					<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_zip' ); ?>
				</div>
			</div>
		</div>
		<?php if( !get_option( 'ec_option_display_country_top' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_country"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>*</label>
			<?php $cartpage->display_billing_input( "country" ); ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_country_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_select_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_country' ); ?>
			</div>
		</div>
		<?php }?>
		<?php if( get_option( 'ec_option_collect_user_phone' ) ){ ?>
		<div class="ec_cart_input_row">
			<label for="ec_cart_billing_phone"><?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?><?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>*<?php }?></label>
			<?php $cartpage->display_billing_input( "phone" ); ?>
			<?php if( get_option( 'ec_option_user_phone_required' ) ){ ?>
			<div class="ec_cart_error_row" id="ec_cart_billing_phone_error">
				<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_phone' ); ?>
			</div>
			<?php }?>
		</div>
		<?php }?>

		<?php if( get_option( 'ec_option_cache_prevent' ) ){ ?>
		<script type="text/javascript">
			wpeasycart_cart_billing_country_update( );
			jQuery( document.getElementById( 'ec_cart_billing_country' ) ).change( wpeasycart_cart_billing_country_update );
		</script>
		<?php }?>

		<input type="hidden" id="wp_easycart_update_billing_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-update-billing-address-type-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>" />

		<?php do_action( 'wpeasycart_billing_after' ); ?>

	<?php } // End billing info only ?>

<?php } // Using standard address, not stripe auto-complete ?>

<?php if ( ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) && get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
	$cartpage->print_stripe_script_v2( false );
} ?>

<?php do_action( 'wpeasycart_shipping_after' ); ?>

<?php do_action( 'wpeasycart_order_notes_after' ); ?>

<?php if( get_option( 'ec_option_enable_extra_email' ) ) { ?>
<div class="ec_cart_header ec_cart_header_no_border">
	<?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email_other' ); ?>
</div>

<div class="ec_cart_input_row">
	<label for="ec_contact_email_other"><?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email_other_label' ); ?></label>
	<?php $cartpage->ec_cart_display_contact_email_other_input(); ?>
	<div class="ec_cart_error_row" id="ec_contact_email_other_error">
		<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_valid' ); ?> <?php echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_email' ); ?>
	</div>
</div>
<?php }?>

<?php if( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
<input type="hidden" id="ec_grecaptcha_response_register" name="ec_grecaptcha_response_register" value="" />
<div class="ec_cart_input_row" data-sitekey="<?php echo esc_attr( get_option( 'ec_option_recaptcha_site_key' ) ); ?>" id="ec_account_register_recaptcha"></div>
<?php }?>

<input type="hidden" name="wpeasycart_checkout_nonce" id="wpeasycart_checkout_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-save-checkout-info-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>" />

<?php if( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { ?>

<div class="ec_cart_bottom_nav_v2 ec_cart_bottom_nav_tabbed">
	<div class="ec_cart_bottom_nav_left">
		<a href="#" class="ec_cart_bottom_nav_back" onclick="return wp_easycart_goto_page_v2( 'cart', '<?php echo esc_attr( wp_create_nonce( 'wp-easycart-goto-cart-page-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ); ?>' );">Return to Cart</a>
	</div>
	<div class="ec_cart_bottom_nav_right ec_cart_button_column">
		<input type="button" value="<?php if( get_option( 'ec_option_skip_shipping_page' ) || ( $cartpage->cart->shippable_total_items <= 0 && $cartpage->order_totals->handling_total <= 0 ) ){ echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_payment' ); } else { echo wp_easycart_language( )->get_text( 'cart_contact_information', 'cart_contact_information_continue_shipping' ); }?>" onclick="return wp_easycart_goto_shipping_v2( true );" class="ec_cart_button" />
	</div>
</div>

<div class="ec_cart_error_row" id="ec_email_order2_error">
	Please enter a valid email address.
</div>

<div class="ec_cart_error_row" id="ec_shipping_order_error">
	Please correct errors with your shipping address.
</div>

<div class="ec_cart_error_row" id="ec_create_account_order_error">
	<?php echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_checkout_details_errors' )?>
</div>
<?php }?>

<?php if( get_option( 'ec_option_cache_prevent' ) && get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) && get_option( 'ec_option_recaptcha_site_key' ) != '' ){ ?>
<script type="text/javascript">
	if( jQuery( document.getElementById( 'ec_account_register_recaptcha' ) ).length ){
		var wpeasycart_register_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_register_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_register_recaptcha_callback
		});
	}
</script>
<?php }?>

<?php do_action( 'wp_easycart_checkout_details_left_end', $cartpage ); ?>

<?php if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) { $cartpage->display_page_one_form_end(); } ?>
