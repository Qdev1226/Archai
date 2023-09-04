<?php if( isset( $_GET['success'] ) && $_GET['success'] == "easycart-products-saved" ){ ?>
<div class="ec_admin_success_message"><div><?php esc_attr_e( 'Your product setup has been updated successfully.', 'wp-easycart' ); ?></div></div>
<?php } ?>