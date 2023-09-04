<?php
global $wpdb;
$order_status = $wpdb->get_results( "SELECT ec_orderstatus.status_id AS value, ec_orderstatus.order_status AS label, ec_orderstatus.is_approved FROM ec_orderstatus ORDER BY status_id ASC" );

?>
<div class="ec_admin_slideout_container" id="order_quick_edit_box" style="z-index:1028;">
    <div class="ec_admin_slideout_container_content">
        <?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_order_quick_edit_display_loader" ); ?>
        <input type="hidden" id="wp_easycart_order_quick_edit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-order-quick-edit' ) ); ?>" />
		<header class="ec_admin_slideout_container_content_header">
            <div class="ec_admin_slideout_container_content_header_inner">
                <h3><?php esc_attr_e( 'Order Quick Edit', 'wp-easycart' ); ?></h3>
                <div class="ec_admin_slideout_close" onclick="wp_easycart_admin_close_slideout( 'order_quick_edit_box' );">
                    <div class="dashicons-before dashicons-no-alt"></div>
                </div>
            </div>
        </header>
        <div class="ec_admin_slideout_container_content_inner">
            <div class="ec_admin_slideout_container_input_row">
                <div class="ec_admin_slideout_container_simple_row">
                	<strong><?php esc_attr_e( 'Order', 'wp-easycart' ); ?> <span id="ec_qe_order_id"></span></strong>
                </div>
            	<div class="ec_admin_slideout_container_simple_row">
                	<strong><?php esc_attr_e( 'Shipping Address', 'wp-easycart' ); ?>:</strong><br /><br />
					<span id="ec_qe_order_shipping_address"></span>
                    <hr>
                	<strong><?php esc_attr_e( 'Items', 'wp-easycart' ); ?>:</strong><br /><br />
					<span id="ec_qe_order_items"></span>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_qe_order_status"><?php esc_attr_e( 'Order Status', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_qe_order_status" name="ec_qe_order_status" class="select2-basic" data-partial-refund="<?php echo esc_attr__( 'Partial Refund', 'wp-easycart' ); ?>" data-refunded="<?php echo esc_attr__( 'Refunded', 'wp-easycart' ); ?>" data-paid="<?php echo esc_attr__( 'Paid', 'wp-easycart' ); ?>" data-cancelled="<?php echo esc_attr__( 'Canceled', 'wp-easycart' ); ?>" data-failed="<?php echo esc_attr__( 'Failed', 'wp-easycart' ); ?>" data-pending="<?php echo esc_attr__( 'Processing', 'wp-easycart' ); ?>">
                        <?php foreach( $order_status as $status ){ ?>
                        <option value="<?php echo esc_attr( $status->value ); ?>" isapproved="<?php echo esc_attr( $status->is_approved ); ?>"><?php echo esc_attr( $status->label ); ?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_qe_order_use_expedited_shipping"><?php esc_attr_e( 'Shipping Type', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_qe_order_use_expedited_shipping" name="ec_qe_order_use_expedited_shipping" class="select2-basic">
                        <option value="0"><?php esc_attr_e( 'Standard Shipping', 'wp-easycart' ); ?></option>
                        <option value="1"><?php esc_attr_e( 'Expedite Shipping', 'wp-easycart' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_qe_order_shipping_type"><?php esc_attr_e( 'Shipping Method', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_qe_order_shipping_method" name="ec_qe_order_shipping_method" placeholder="<?php esc_attr_e( 'Shipping Method', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_qe_order_shipping_type"><?php esc_attr_e( 'Shipping Carrier', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_qe_order_shipping_carrier" name="ec_qe_order_shipping_carrier" placeholder="<?php esc_attr_e( 'Shipping Carrier', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_qe_order_shipping_type"><?php esc_attr_e( 'Tracking Number', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_qe_order_tracking_number" name="ec_qe_order_tracking_number" placeholder="<?php esc_attr_e( 'Tracking Number', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_qe_order_send_tracking_email"><?php esc_attr_e( 'Send Shipped Email on Save?', 'wp-easycart' ); ?></label>
                <div>
                     <select id="ec_qe_order_send_tracking_email" name="ec_qe_order_send_tracking_email" class="select2-basic">
                        <option value="0"><?php esc_attr_e( 'No, Do Not Send', 'wp-easycart' ); ?></option>
                        <option value="1"><?php esc_attr_e( 'Yes, Send Shipped Email', 'wp-easycart' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <footer class="ec_admin_slideout_container_content_footer">
            <div class="ec_admin_slideout_container_content_footer_inner">
                <div class="ec_admin_slideout_container_content_footer_inner_body">
                    <ul>
                        <li class="ec_admin_mobile_hide">
                            <button onclick="ec_admin_cancel_order_quick_edit( );">
                                <span><?php esc_attr_e( 'Cancel', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                        <li>
                            <button onclick="ec_admin_save_order_quick_edit( );">
                                <span><?php esc_attr_e( 'Save Changes', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>
<script>jQuery( document.getElementById( 'order_quick_edit_box' ) ).appendTo( document.body );</script>