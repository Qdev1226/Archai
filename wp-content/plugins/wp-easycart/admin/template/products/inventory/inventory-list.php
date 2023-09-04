<?php
global $wpdb;
$products = $wpdb->get_results( "SELECT ec_product.product_id, ec_product.title, ec_product.model_number, ec_product.stock_quantity, ec_product.use_optionitem_quantity_tracking, ec_product.show_stock_quantity, ec_product.option_id_1, ec_product.option_id_2, ec_product.option_id_3, ec_product.option_id_4, ec_product.option_id_5 FROM ec_product WHERE ec_product.activate_in_store = 1 ORDER BY ec_product.title ASC" );

?>
<?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_inventory_nonce', 'wp-easycart-inventory' ); ?>
<div class="wrap">
  <div class="ec_inventory_status_section">
	<h1 class="wp-heading-inline">
		<div class="dashicons-before dashicons-performance"></div>
		<?php esc_attr_e( 'Product Inventory Status', 'wp-easycart' ); ?>
		<a href="<?php echo esc_url_raw( wp_easycart_admin( )->helpsystem->print_docs_url( 'products', 'inventory', "master-record" ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url( 'products', 'inventory', "master-record" ); ?>
		<a href="admin.php?page=wp-easycart-products&subpage=inventory&ec_admin_form_action=export-inventory-list&wp_easycart_nonce=<?php echo esc_attr( wp_create_nonce( 'wp-easycart-export-inventory' ) ); ?>" class="ec_page_title_button"><?php esc_attr_e( 'Export Inventory List', 'wp-easycart' ); ?></a>
	</h1>
	<div class="ec_inventory_export_row"></div>
	<table class="wp-list-table widefat fixed striped pages">
		<thead>
			<tr>
				<td class="ec_inventory_status_title"><?php esc_attr_e( 'Title', 'wp-easycart' ); ?></td>
				<td class="ec_inventory_status_quantity" style="text-align:right; padding-right:60px;"><?php esc_attr_e( 'Stock Quantity', 'wp-easycart' ); ?></td>
			</tr>
		</thead>
		<tbody>
		<?php foreach ( $products as $product ) {
			if ( $product->use_optionitem_quantity_tracking ) {
				/* START THE CREATION OF A COMPLEX QUERY. THIS COMBINES MULTIPLE OPTIONS TO ALLOW A USER TO ENTER A QUANTITY FOR EACH */
				$sql = "";
				if( $product->option_id_1 != 0 ){
					$sql .= $wpdb->prepare( "SELECT * FROM ( SELECT optionitem_name AS optname1, optionitem_id as optid1 FROM ec_optionitem WHERE option_id = %d ) as optionitems1 ", $product->option_id_1 );
				}

				if($product->option_id_2 != 0){
					$sql .= $wpdb->prepare(" JOIN ( SELECT optionitem_name AS optname2, optionitem_id as optid2 FROM ec_optionitem WHERE option_id = %d ) as optionitems2 ON (1=1) ", $product->option_id_2 );
				}

				if($product->option_id_3 != 0){
					$sql .= $wpdb->prepare(" JOIN ( SELECT optionitem_name AS optname3, optionitem_id as optid3 FROM ec_optionitem WHERE option_id = %d ) as optionitems3 ON (1=1) ", $product->option_id_3 );
				}

				if($product->option_id_4 != 0){
					$sql .= $wpdb->prepare(" JOIN ( SELECT optionitem_name AS optname4, optionitem_id as optid4 FROM ec_optionitem WHERE option_id = %d ) as optionitems4 ON (1=1) ", $product->option_id_4 );
				}

				if($product->option_id_5 != 0){
					$sql .= $wpdb->prepare(" JOIN ( SELECT optionitem_name AS optname5, optionitem_id as optid5 FROM ec_optionitem WHERE option_id = %s ) as optionitems5 ON (1=1) ", $product->option_id_5 );
				}

				$sql .= " LEFT JOIN ec_optionitemquantity ON ( 1=1 ";

				if($product->option_id_1 != 0){
					$sql .= " AND ec_optionitemquantity.optionitem_id_1 = optid1";
				}

				if($product->option_id_2 != 0){
					$sql .= " AND ec_optionitemquantity.optionitem_id_2 = optid2";
				}

				if($product->option_id_3 != 0){
					$sql .= " AND ec_optionitemquantity.optionitem_id_3 = optid3";
				}

				if($product->option_id_4 != 0){
					$sql .= " AND ec_optionitemquantity.optionitem_id_4 = optid4";
				}

				if($product->option_id_5 != 0){
					$sql .= " AND ec_optionitemquantity.optionitem_id_5 = optid5";
				}

				$sql .= $wpdb->prepare( " AND ec_optionitemquantity.product_id = %d )", $product->product_id );

				$sql .= " ORDER BY optname1";

				//Finally, get the query results
				$optionitems = $wpdb->get_results( $sql );
				if ( count( $optionitems ) > 100 ) { ?>
				<tr class="<?php if( $product->stock_quantity <= 0 ){ echo 'out_of_stock'; }else if( $product->stock_quantity <= 10 ){ echo 'inventory_low'; }else{ echo 'inventory_fine'; }?>">
					<td class="ec_inventory_status_title"><?php echo esc_attr( $product->title ); ?></td>
					<td class="ec_inventory_status_quantity" style="text-align:right;">
						<a href="admin.php?page=wp-easycart-products&subpage=products&product_id=<?php echo esc_attr( $product->product_id ); ?>&ec_admin_form_action=edit&wp_easycart_nonce=<?php echo wp_create_nonce( 'wp-easycart-action-edit' ); ?>" target="_blank"><?php esc_attr_e( 'Click here to manage product stock', 'wp-easycart' ); ?></a>
					</td>
				</tr>
				<?php } else {
					foreach( $optionitems as $optionitem ){ 
						$opt_title = $product->title . " (";
						if( $optionitem->optionitem_id_1 != 0 ){
							$opt_title .= $optionitem->optname1;
						}
						if( $optionitem->optionitem_id_2 != 0 ){
							$opt_title .= ", " . $optionitem->optname2;
						}
						if( $optionitem->optionitem_id_3 != 0 ){
							$opt_title .= ", " . $optionitem->optname3;
						}
						if( $optionitem->optionitem_id_4 != 0 ){
							$opt_title .= ", " . $optionitem->optname4;
						}
						if( $optionitem->optionitem_id_5 != 0 ){
							$opt_title .= ", " . $optionitem->optname5;
						}

						$opt_title .= ")";
						?>
						<tr class="<?php if( $optionitem->quantity <= 0 ){ echo 'out_of_stock'; }else if( $optionitem->quantity <= 10 ){ echo 'inventory_low'; }else{ echo 'inventory_fine'; }?>">
							<td class="ec_inventory_status_title"><?php echo esc_attr( $opt_title ); ?></td>
							<td class="ec_inventory_status_quantity">
								<div class="wp-easycart-admin-field-container">
									<input style="text-align:center; float:right; margin-top:6px;" class="wp-easycart-inventory-update" type="number" step="1" value="<?php echo esc_attr( $optionitem->quantity ); ?>" data-product-id="<?php echo esc_attr( $optionitem->product_id ); ?>" data-id="<?php echo esc_attr( $optionitem->optionitemquantity_id ); ?>" />
									<div class="wp-easycart-admin-icons-container">
										<div class="wp-easycart-admin-icon-close">
											<div class="wp-easycart-admin-icon-close-check"></div>
											<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
											<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none; margin:3px 0 0 -2px !important;"></div>
										</div>
									</div>
								</div>
							</td>
						</tr>
					<?php
					} // Close optionitem quantity tracking loop
				}
			} else if( $product->show_stock_quantity ){ ?>

				<tr class="<?php if( $product->stock_quantity <= 0 ){ echo 'out_of_stock'; }else if( $product->stock_quantity <= 10 ){ echo 'inventory_low'; }else{ echo 'inventory_fine'; }?>">
					<td class="ec_inventory_status_title"><?php echo esc_attr( $product->title ); ?></td>
					<td class="ec_inventory_status_quantity">
						<div class="wp-easycart-admin-field-container">
							<input style="text-align:center; float:right; margin-top:6px;" class="wp-easycart-inventory-update" type="number" step="1" value="<?php echo esc_attr( $product->stock_quantity ); ?>" data-product-id="<?php echo esc_attr( $product->product_id ); ?>" data-id="-1" />
							<div class="wp-easycart-admin-icons-container">
								<div class="wp-easycart-admin-icon-close">
									<div class="wp-easycart-admin-icon-close-check"></div>
									<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>
									<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none; margin:3px 0 0 -2px !important;"></div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php  }else{ ?>
				<tr class="inventory_fine">
					<td class="ec_inventory_status_title"><?php echo esc_attr( $product->title ); ?></td>
					<td class="ec_inventory_status_quantity">∞</td>
				</tr>
				<?php }// Close product type if
				} // Close foreach 
				if( count( $products ) == 0 ){ ?>
				<tr>
					<td class="ec_inventory_status_title"><?php esc_attr_e( 'No Products Found', 'wp-easycart' ); ?></td>
					<td class="ec_inventory_status_quantity"></td>
				</tr>	
				<?php }?>
		</tbody>
	</table>
	<div class="ec_stats_button_container">

	</div>
  </div>