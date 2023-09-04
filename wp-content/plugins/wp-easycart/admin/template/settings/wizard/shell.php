<div class="ec_admin_wizard_container">
	<h1><?php esc_attr_e( 'SETUP WIZARD', 'wp-easycart' ); ?></h1>
    <div class="ec_admin_wizard_nav">
    	<?php do_action( 'wp_easycart_admin_wizard_navigation' ); ?>
    </div>
    <div class="ec_admin_wizard_basic_content">
    	<div class="ec_admin_wizard_basic_content_inner">
    		<?php do_action( 'wp_easycart_admin_wizard_content' ); ?>
    	</div>
    </div>
</div>