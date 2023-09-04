<?php
global $wpdb;
$quantity_triggers = $wpdb->get_results( "SELECT * FROM ec_shippingrate WHERE is_quantity_based = 1 ORDER BY trigger_rate ASC" );
$shipping_zones = $wpdb->get_results( "SELECT * FROM ec_zone ORDER BY zone_name ASC" );
$currency = new ec_currency( );
?>
<div class="ec_admin_settings_input ec_admin_settings_shipping_section ec_admin_settings_<?php if( wp_easycart_admin( )->settings->shipping_method == "quantity" ){ ?>show<?php }else{?>hide<?php }?>" id="quantity">
	<?php foreach( $quantity_triggers as $trigger ){ ?>
	<div class="ec_admin_tax_row ec_admin_shipping_quantity_trigger_row" id="ec_admin_quantity_trigger_row_<?php echo esc_attr( $trigger->shippingrate_id ); ?>">
		<div class="ec_admin_shipping_trigger">
			<span>
				<?php esc_attr_e( 'Quantity Trigger', 'wp-easycart' ); ?>
			</span>
			<input type="number" class="ec_admin_quantity_trigger_input" step="1" value="<?php echo esc_attr( number_format( $trigger->trigger_rate, 0, '', '' ) ); ?>" name="ec_admin_quantity_trigger_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" id="ec_admin_new_quantity_trigger_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Rate', 'wp-easycart' ); ?>
				<?php echo esc_attr( $currency->symbol ); ?>
			</span>
			<input type="number" class="ec_admin_quantity_trigger_rate_input" step=".01" value="<?php echo esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ); ?>" name="ec_admin_quantity_trigger_rate_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" id="ec_admin_new_quantity_trigger_rate_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Zone', 'wp-easycart' ); ?>
			</span>
			<select class="ec_admin_quantity_trigger_zone_id_input" name="ec_admin_quantity_trigger_zone_id_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" id="ec_admin_quantity_trigger_zone_id_<?php echo esc_attr( $trigger->shippingrate_id ); ?>">
				<option value="0"><?php esc_attr_e( 'No Zone', 'wp-easycart' ); ?></option>
				<?php foreach( $shipping_zones as $zone ){ ?>
					<option value="<?php echo esc_attr( $zone->zone_id ); ?>"<?php if( $zone->zone_id == $trigger->zone_id ){ ?> selected="selected"<?php }?>><?php echo esc_attr( $zone->zone_name ); ?></option>
				<?php }?>
			</select>
		</div>
		<span class="ec_admin_shipping_rate_delete">
			<div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_quantity_trigger( '<?php echo esc_attr( $trigger->shippingrate_id ); ?>' );"></div>
		</span>
	</div>
	<?php } ?>
	<div id="insert_new_quantity_trigger_here"></div>

	<div id="ec_admin_no_quantity_triggers"<?php if( count( $quantity_triggers ) > 0 ){ ?> style="display:none;"<?php }?>><?php esc_attr_e( 'No quantity Triggers Entered', 'wp-easycart' ); ?></div>

	<div class="ec_admin_settings_shipping_input">
		<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Save Triggers', 'wp-easycart' ); ?>" onclick="return ec_admin_save_shipping_quantity_triggers( );" />
	</div>

	<div class="ec_admin_settings_shipping_divider"></div>

	<span><?php esc_attr_e( 'Add Quantity Trigger', 'wp-easycart' ); ?></span>

	<div class="ec_admin_tax_row ec_admin_shipping_quantity_trigger_row">
		<div class="ec_admin_shipping_trigger">
			<span>
				<?php esc_attr_e( 'Quantity Trigger', 'wp-easycart' ); ?>
			</span>
			<input type="number" step="0" value="1" name="ec_admin_new_quantity_trigger" id="ec_admin_new_quantity_trigger" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Rate', 'wp-easycart' ); ?>
				<?php echo esc_attr( $currency->symbol ); ?>
			</span>
			<input type="number" step=".01" value="<?php echo esc_attr( $currency->get_number_safe( 0.00 ) ); ?>" name="ec_admin_new_quantity_trigger_rate" id="ec_admin_new_quantity_trigger_rate" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Zone', 'wp-easycart' ); ?>
			</span>
			<select name="ec_admin_new_quantity_trigger_zone_id" id="ec_admin_new_quantity_trigger_zone_id">
				<option value="0"><?php esc_attr_e( 'No Zone', 'wp-easycart' ); ?></option>
				<?php foreach( $shipping_zones as $zone ){ ?>
					<option value="<?php echo esc_attr( $zone->zone_id ); ?>"><?php echo esc_attr( $zone->zone_name ); ?></option>
				<?php }?>
			</select>
		</div>
		<div class="ec_admin_settings_shipping_input">
			<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="return ec_admin_add_new_shipping_quantity_trigger( );" />
		</div>
	</div>    
</div>