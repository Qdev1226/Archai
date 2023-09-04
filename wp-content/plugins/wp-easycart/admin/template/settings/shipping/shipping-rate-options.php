<option value="price" <?php if( wp_easycart_admin( )->settings->shipping_method == 'price' ) echo ' selected'; ?>>
	<?php esc_attr_e( 'Price Trigger System', 'wp-easycart' ); ?>
</option>
<option value="weight" <?php if( wp_easycart_admin( )->settings->shipping_method == 'weight' ) echo ' selected'; ?>>
	<?php esc_attr_e( 'Weight Trigger System', 'wp-easycart' ); ?>
</option>
<option value="quantity" <?php if( wp_easycart_admin( )->settings->shipping_method == 'quantity' ) echo ' selected'; ?>>
	<?php esc_attr_e( 'Quantity Trigger System', 'wp-easycart' ); ?>
</option>
<option value="percentage" <?php if( wp_easycart_admin( )->settings->shipping_method == 'percentage' ) echo ' selected'; ?>>
	<?php esc_attr_e( 'Percentage Based Shipping', 'wp-easycart' ); ?>
</option>
<option value="method" <?php if( wp_easycart_admin( )->settings->shipping_method == 'method' ) echo ' selected'; ?>>
	<?php esc_attr_e( 'Static Shipping Method', 'wp-easycart' ); ?>
</option>
<option value="fraktjakt" <?php if( wp_easycart_admin( )->settings->shipping_method == 'fraktjakt' ) echo ' selected'; ?>>
	<?php esc_attr_e( 'Fraktjakt', 'wp-easycart' ); ?>
</option>