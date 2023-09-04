<?php if ( get_option( 'ec_option_googleanalyticsid' ) != "UA-XXXXXXX-X" && get_option( 'ec_option_googleanalyticsid' ) != "" ) { ?>
<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', '<?php echo esc_attr( $google_urchin_code ); ?>', '<?php echo esc_attr( $google_wp_url ); ?>');
	ga('send', 'pageview');
	ga('require', 'ecommerce', 'ecommerce.js');
	<?php $this->print_google_transaction( ); ?>
	ga('ecommerce:send');
</script>
<?php } ?>

<?php $this->ec_cart_display_third_party_form_start( ); ?>
<?php if( get_option( 'ec_option_payment_third_party' ) == "paypal" ){ ?>
<img src="<?php echo esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/paypal.jpg", EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="PayPal" />
<?php }else if( get_option( 'ec_option_payment_third_party' ) == "skrill" ){ ?>
<img src="<?php echo esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/skrill-logo.gif", EC_PLUGIN_DATA_DIRECTORY ) ); ?>" alt="Skrill" />
<?php } ?>
<br \>
You are about to leave our site. To complete your order you must be redirected to <?php $this->ec_cart_display_current_third_party_name( ); ?>. Once the order has been completed through <?php $this->ec_cart_display_current_third_party_name( ); ?> you will be brought back to our site.
<br />
<br />
<?php $this->display_third_party_submit_button( esc_attr( "Continue to " . $this->ec_cart_get_current_third_party_name( ) ) ); ?>
<?php $this->ec_cart_display_third_party_form_end( ); ?>