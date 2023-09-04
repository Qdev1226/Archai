<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_order_statuses_settings_loader" ); ?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-feedback"></div>
		<span><?php esc_attr_e( 'Order Statuses', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'checkout', 'order-statuses' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'checkout', 'form-settings');?>
	</div>
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="50%"><?php esc_attr_e( 'Status', 'wp-easycart' ); ?></th>
                    <th style="text-align:center;"><?php esc_attr_e( 'Payment Complete?', 'wp-easycart' ); ?></th>
                    <th style="text-align:right;"><?php esc_attr_e( 'Delete', 'wp-easycart' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php global $wpdb; ?>
            <?php $order_statuses = $wpdb->get_results( "SELECT * FROM ec_orderstatus WHERE is_archieved = 0 ORDER BY status_id ASC" ); ?>
            <?php foreach( $order_statuses as $order_status ){ ?>
                <tr id="wpeasycart_orderstatus_row_<?php echo esc_attr( $order_status->status_id ); ?>">
                    <td>
						<input type="text" style="margin-top:0px;" id="wpeasycart_orderstatus_status_<?php echo esc_attr( $order_status->status_id ); ?>" class="wpeasycart_orderstatus_id_edit" data-id="<?php echo esc_attr( $order_status->status_id ); ?>" value="<?php echo esc_attr( $order_status->order_status ); ?>" />
					</td>
                    <td style="text-align:center;">
						<input type="checkbox"<?php echo ( $order_status->status_id > 19 ) ? ' class="wpeasycart_orderstatus_approved_edit"' : ''; ?> id="wpeasycart_orderstatus_approved_<?php echo esc_attr( $order_status->status_id ); ?>" data-id="<?php echo esc_attr( $order_status->status_id ); ?>" value="1"<?php echo ( $order_status->status_id <= 19 ) ? ' readonly onclick="return false;"' : ''; ?><?php echo ( $order_status->is_approved ) ? ' checked="checked"' : ''; ?> />
					</td>
                    <td style="text-align:right;">
						<?php echo ( esc_attr( $order_status->status_id ) > 19 ) ? '<input type="button" class="ec_admin_order_edit_button" onclick="wpeasycart_archieve_orderstatus( ' . esc_attr( $order_status->status_id ) . ' );" value="' . esc_attr__( 'Delete', 'wp-easycart' ) . '" />' : '' . esc_attr__( 'Locked', 'wp-easycart' ) . ''; ?>
					</td>
                </tr>
            <?php }?>
                <tr id="wpeasycart_orderstatus_row_add">
                    <td><input type="text" style="margin-top:0px;" id="wpeasycart_orderstatus_add" value="" placeholder="<?php esc_attr_e( 'Enter New Status', 'wp-easycart' ); ?>" /></td>
                    <td style="text-align:center;"><input type="checkbox" id="wpeasycart_orderstatus_approved_add" value="1" /></td>
                    <td style="text-align:right;"><input type="button" class="ec_admin_order_edit_button" onclick="return wpeasycart_add_orderstatus( );" value="<?php esc_attr_e( '+ADD', 'wp-easycart' ); ?>" /></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>