<?php
global $wpdb;
$price_triggers = $wpdb->get_results( "SELECT * FROM ec_shippingrate WHERE is_price_based = 1 ORDER BY trigger_rate ASC" );
$shipping_zones = $wpdb->get_results( "SELECT * FROM ec_zone ORDER BY zone_name ASC" );
$currency = new ec_currency( );
?>
<div class="ec_admin_settings_input ec_admin_settings_shipping_section ec_admin_settings_<?php if( wp_easycart_admin( )->settings->shipping_method == "price" ){ ?>show<?php }else{?>hide<?php }?>" id="price">
	<?php foreach( $price_triggers as $trigger ){ ?>
	<div class="ec_admin_tax_row ec_admin_shipping_price_trigger_row" id="ec_admin_price_trigger_row_<?php echo esc_attr( $trigger->shippingrate_id ); ?>">
		<div class="ec_admin_shipping_trigger">
			<span>
				<?php esc_attr_e( 'Price Trigger', 'wp-easycart' ); ?> 
				<?php echo esc_attr( $currency->symbol ); ?>
			</span>
			<input type="number" class="ec_admin_price_trigger_input" step=".01" value="<?php echo esc_attr( $currency->get_number_safe( $trigger->trigger_rate ) ); ?>" name="ec_admin_price_trigger_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" id="ec_admin_new_price_trigger_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Rate', 'wp-easycart' ); ?>
				<?php echo esc_attr( $currency->symbol ); ?>
			</span>
			<input type="number" class="ec_admin_price_trigger_rate_input" step=".01" value="<?php echo esc_attr( $currency->get_number_safe( $trigger->shipping_rate ) ); ?>" name="ec_admin_price_trigger_rate_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" id="ec_admin_new_price_trigger_rate_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Zone', 'wp-easycart' ); ?>
			</span>
			<select class="ec_admin_price_trigger_zone_id_input" name="ec_admin_price_trigger_zone_id_<?php echo esc_attr( $trigger->shippingrate_id ); ?>" id="ec_admin_price_trigger_zone_id_<?php echo esc_attr( $trigger->shippingrate_id ); ?>">
				<option value="0"><?php esc_attr_e( 'No Zone', 'wp-easycart' ); ?></option>
				<?php foreach( $shipping_zones as $zone ){ ?>
					<option value="<?php echo esc_attr( $zone->zone_id ); ?>"<?php if( $zone->zone_id == $trigger->zone_id ){ ?> selected="selected"<?php }?>><?php echo esc_attr( $zone->zone_name ); ?></option>
				<?php }?>
			</select>
		</div>
		<span class="ec_admin_shipping_rate_delete">
			<div class="dashicons-before dashicons-trash" onclick="ec_admin_delete_price_trigger( '<?php echo esc_attr( $trigger->shippingrate_id ); ?>' );"></div>
		</span>
	</div>
	<?php } ?>

	<div id="insert_new_price_trigger_here"></div>

	<div id="ec_admin_no_price_triggers"<?php if( count( $price_triggers ) > 0 ){ ?> style="display:none;"<?php }?>><?php esc_attr_e( 'No Price Triggers Entered', 'wp-easycart' ); ?></div>

	<div class="ec_admin_settings_shipping_input">
		<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Save Triggers', 'wp-easycart' ); ?>" onclick="return ec_admin_save_shipping_price_triggers( );" />
	</div>

	<div class="ec_admin_settings_shipping_divider"></div>

	<span><?php esc_attr_e( 'Add Price Trigger', 'wp-easycart' ); ?></span>

	<div class="ec_admin_tax_row ec_admin_shipping_price_trigger_row">
		<div class="ec_admin_shipping_trigger">
			<span>
				<?php esc_attr_e( 'Price Trigger', 'wp-easycart' ); ?>
				<?php echo esc_attr( $currency->symbol ); ?>
			</span>
			<input type="number" step=".01" value="<?php echo esc_attr( $currency->get_number_safe( 0.00 ) ); ?>" name="ec_admin_new_price_trigger" id="ec_admin_new_price_trigger" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Rate', 'wp-easycart' ); ?>
				<?php echo esc_attr( $currency->symbol ); ?>
			</span>
			<input type="number" step=".01" value="<?php echo esc_attr( $currency->get_number_safe( 0.00 ) ); ?>" name="ec_admin_new_price_trigger_rate" id="ec_admin_new_price_trigger_rate" />
		</div>
		<div class="ec_admin_shipping_rate">
			<span>
				<?php esc_attr_e( 'Shipping Zone', 'wp-easycart' ); ?>
			</span>
			<select name="ec_admin_new_price_trigger_zone_id" id="ec_admin_new_price_trigger_zone_id">
				<option value="0"><?php esc_attr_e( 'No Zone', 'wp-easycart' ); ?></option>
				<?php foreach( $shipping_zones as $zone ){ ?>
					<option value="<?php echo esc_attr( $zone->zone_id ); ?>"><?php echo esc_attr( $zone->zone_name ); ?></option>
				<?php }?>
			</select>
		</div>
		<div class="ec_admin_settings_shipping_input">
			<input type="submit" class="ec_admin_settings_simple_button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="return ec_admin_add_new_shipping_price_trigger( );" />
		</div>
	</div>

</div>