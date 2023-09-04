<?php
global $wpdb;
$manufacturer_list = $wpdb->get_results( "SELECT ec_manufacturer.manufacturer_id AS value, ec_manufacturer.name AS label FROM ec_manufacturer ORDER BY ec_manufacturer.name ASC" );
$basic_option_list = $wpdb->get_results( "SELECT ec_option.option_id AS value, ec_option.option_name AS label FROM ec_option WHERE option_type = 'basic-combo' OR option_type = 'basic-swatch' ORDER BY option_name ASC" );

?>
<div class="ec_admin_slideout_container" id="product_quick_edit_box" style="z-index:1028;">
	<input type="hidden" id="ec_qe_product_id" value="" />
	<div class="ec_admin_slideout_container_content">
		<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_product_quick_edit_display_loader" ); ?>
		<input type="hidden" id="wp_easycart_product_quick_edit_nonce" value="<?php echo esc_attr( wp_create_nonce( 'wp-easycart-product-quick-edit' ) ); ?>" />
		<header class="ec_admin_slideout_container_content_header">
			<div class="ec_admin_slideout_container_content_header_inner">
				<h3><?php esc_attr_e( 'Product Quick Edit', 'wp-easycart' ); ?></h3>
				<div class="ec_admin_slideout_close" onclick="wp_easycart_admin_close_slideout( 'product_quick_edit_box' );">
					<div class="dashicons-before dashicons-no-alt"></div>
				</div>
			</div>
		</header>
		<div class="ec_admin_slideout_container_content_inner">
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_status"><?php esc_attr_e( 'Product Status', 'wp-easycart' ); ?></label>
				<div>
					<select id="ec_quick_editproduct_status" name="ec_quick_editproduct_status" class="select2-basic">
						<option value="0"><?php esc_attr_e( 'Not Active', 'wp-easycart' ); ?></option>
						<option value="1" selected="selected"><?php esc_attr_e( 'Active', 'wp-easycart' ); ?></option>
					</select>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_featured"><?php esc_attr_e( 'Feature on Main Store Page?', 'wp-easycart' ); ?></label>
				<div>
					<select id="ec_quick_editproduct_featured" name="ec_quick_editproduct_featured" class="select2-basic">
						<option value="1" selected="selected"><?php esc_attr_e( 'Yes', 'wp-easycart' ); ?></option>
						<option value="0"><?php esc_attr_e( 'No', 'wp-easycart' ); ?></option>
					</select>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_title"><?php esc_attr_e( 'Title', 'wp-easycart' ); ?></label>
				<div>
					<input type="text" id="ec_quick_editproduct_title" name="ec_quick_editproduct_title" placeholder="<?php esc_attr_e( 'Your Product Name', 'wp-easycart' ); ?>" />
				</div>
				<div class="ec_admin_slideout_error_text" id="title_required">
					<?php esc_attr_e( 'The title is required.', 'wp-easycart' ); ?>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_sku"><?php esc_attr_e( 'SKU', 'wp-easycart' ); ?></label>
				<div>
					<input type="text" id="ec_quick_editproduct_sku" name="ec_quick_editproduct_sku" placeholder="<?php esc_attr_e( 'product-name', 'wp-easycart' ); ?>" />
				</div>
				<div class="ec_admin_slideout_error_text" id="sku_required">
					<?php esc_attr_e( 'The SKU is required.', 'wp-easycart' ); ?>
				</div>
				<div class="ec_admin_slideout_error_text" id="duplicate_sku">
					<?php esc_attr_e( 'Duplicate SKU, please change to a unique value.', 'wp-easycart' ); ?>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_manufacturer"><?php esc_attr_e( 'Manufacturer', 'wp-easycart' ); ?></label>
				<div>
					<div class="wpec-admin-75-select">
						<select id="ec_quick_editproduct_manufacturer" name="ec_quick_editproduct_manufacturer" class="select2-basic">
							<option value="0"><?php esc_attr_e( 'Select One', 'wp-easycart' ); ?></option>
							<?php foreach( $manufacturer_list as $manufacturer ){ ?>
							<option value="<?php echo esc_attr( $manufacturer->value ); ?>"><?php echo esc_attr( $manufacturer->label ); ?></option>
							<?php }?>
						</select>
					</div>
					<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="wp_easycart_admin_open_slideout( 'new_manufacturer_box' );" />
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<div>
					<div class="wpec-admin-50-wide">
						<label for="ec_quick_editproduct_price"><?php esc_attr_e( 'Price', 'wp-easycart' ); ?></label>
						<div>
							<?php
							$step = 1;
							for( $i=0; $i<$GLOBALS['currency']->get_decimal_length( ); $i++ ){
								$step = $step / 10;
							}
							?>
							<input type="number" step="<?php echo esc_attr( $step ); ?>" id="ec_quick_editproduct_price" name="ec_quick_editproduct_price" placeholder="19.99" />
						</div>
					</div>
					<div class="wpec-admin-50-wide">
						<label for="ec_quick_editproduct_list_price"><?php esc_attr_e( 'List Price', 'wp-easycart' ); ?></label>
						<div>
							<input type="number" step="<?php echo esc_attr( $step ); ?>" id="ec_quick_editproduct_list_price" name="ec_quick_editproduct_list_price" placeholder="24.99" />
						</div>
					</div>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_image"><?php esc_attr_e( 'Image', 'wp-easycart' ); ?></label>
				<div>
					<input type="text" id="ec_quick_editproduct_image" name="ec_quick_editproduct_image" class="wpec-admin-upload-input" value="" />
					<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Select Image', 'wp-easycart' ); ?>" id="ec_upload_button_image" onclick="ec_admin_image_upload( 'ec_quick_editproduct_image' );" />
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_sort_position"><?php esc_attr_e( 'Sort Position', 'wp-easycart' ); ?></label>
				<div>
					<input type="number" step="1" min="0" id="ec_quick_editproduct_sort_position" name="ec_quick_editproduct_sort_position"  />
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_stock_option"><?php esc_attr_e( 'Stock Options', 'wp-easycart' ); ?></label>
				<div>
					<select id="ec_quick_editproduct_stock_option" name="ec_quick_editproduct_stock_option" class="select2-basic" onchange="ec_admin_quick_edit_product_update_stock_option( );">
						<option value="0"><?php esc_attr_e( 'Do Not Track Stock', 'wp-easycart' ); ?></option>
						<option value="1"><?php esc_attr_e( 'Track Basic Stock', 'wp-easycart' ); ?></option>
						<option value="2"><?php esc_attr_e( 'Track Option Item Stock', 'wp-easycart' ); ?></option>
					</select>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row ec_admin_quick_edit_product_basic_stock" style="display:none;">
				<div>
					<input type="number" step="1" id="ec_quick_editproduct_stock_quantity" name="ec_quick_editproduct_stock_quantity" placeholder="<?php esc_attr_e( 'Stock Quantity', 'wp-easycart' ); ?>" />
				</div>
			</div>

			<div class="ec_admin_slideout_container_input_row ec_admin_quick_edit_product_optionitem_stock" style="display:none; float:left; width:100%; margin-top:25px; text-align:center;">-- <?php esc_attr_e( 'Option item quantities are available when you edit the full product', 'wp-easycart' ); ?> --</div>
			<div class="ec_admin_slideout_container_input_row">
				<label for="ec_quick_editproduct_is_shippable"><?php esc_attr_e( 'Shipping Options', 'wp-easycart' ); ?></label>
				<div>
					<select id="ec_quick_editproduct_is_shippable" name="ec_quick_editproduct_is_shippable" class="select2-basic" onchange="ec_admin_quick_edit_product_update_shipping_type( );">
						<option value="0"><?php esc_attr_e( 'No Shipping', 'wp-easycart' ); ?></option>
						<option value="1"><?php esc_attr_e( 'Enable Shipping', 'wp-easycart' ); ?></option>
					</select>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row ec_admin_quick_edit_product_shipping_row" style="display:none;">
				<div>
					<div class="wpec-admin-50-wide">
						<label for="ec_quick_editproduct_weight"><?php esc_attr_e( 'Weight', 'wp-easycart' ); ?></label>
						<input type="number" min="0" step=".01" id="ec_quick_editproduct_weight" name="ec_quick_editproduct_weight" placeholder="<?php esc_attr_e( 'Weight', 'wp-easycart' ); ?>" />
					</div>
					<div class="wpec-admin-50-wide">
						<label for="ec_quick_editproduct_weight"><?php esc_attr_e( 'Length', 'wp-easycart' ); ?></label>
						<input type="number" min="0" step=".01" id="ec_quick_editproduct_length" name="ec_quick_editproduct_length" placeholder="<?php esc_attr_e( 'Length', 'wp-easycart' ); ?>" />
					</div>
					<div class="wpec-admin-50-wide">
						<label for="ec_quick_editproduct_weight"><?php esc_attr_e( 'Width', 'wp-easycart' ); ?></label>
						<input type="number" min="0" step=".01" id="ec_quick_editproduct_width" name="ec_quick_editproduct_width" placeholder="<?php esc_attr_e( 'Width', 'wp-easycart' ); ?>" />
					</div>
					<div class="wpec-admin-50-wide">
						<label for="ec_quick_editproduct_weight"><?php esc_attr_e( 'Height', 'wp-easycart' ); ?></label>
						<input type="number" min="0" step=".01" id="ec_quick_editproduct_height" name="ec_quick_editproduct_height" placeholder="<?php esc_attr_e( 'Height', 'wp-easycart' ); ?>" />
					</div>
				</div>
			</div>
			<div class="ec_admin_slideout_container_input_row"<?php if( !get_option( 'ec_option_admin_product_show_tax_option' ) ){ ?> style="display:none;"<?php }?>>
				<label for="ec_quick_editproduct_is_taxable"><?php esc_attr_e( 'Tax Options', 'wp-easycart' ); ?></label>
				<div>
					<select id="ec_quick_editproduct_is_taxable" name="ec_quick_editproduct_is_taxable" class="select2-basic">
						<option value="0"><?php esc_attr_e( 'Not Taxable', 'wp-easycart' ); ?></option>
						<option value="1"><?php esc_attr_e( 'Enable Tax', 'wp-easycart' ); ?></option>
						<option value="2"><?php esc_attr_e( 'Enable VAT', 'wp-easycart' ); ?></option>
						<option value="3"><?php esc_attr_e( 'Enable Tax & VAT', 'wp-easycart' ); ?></option>
					</select>
				</div>
			</div>
			<div style="float:left; width:100%; margin-top:25px; text-align:center;"><?php esc_attr_e( '*You can edit all product settings after creating the product basics', 'wp-easycart' ); ?></div>
		</div>
		<footer class="ec_admin_slideout_container_content_footer">
			<div class="ec_admin_slideout_container_content_footer_inner">
				<div class="ec_admin_slideout_container_content_footer_inner_body">
					<ul>
						<li>
							<button onclick="ec_admin_update_quick_product( 0 );">
								<span><?php esc_attr_e( 'Save', 'wp-easycart' ); ?></span>
							</button>
						</li>
						<li>
							<button onclick="ec_admin_update_quick_product( 1 );">
								<span><?php esc_attr_e( 'Save &amp; Edit Full', 'wp-easycart' ); ?></span>
							</button>
						</li>
						<li>
							<button onclick="wp_easycart_admin_close_slideout( 'product_quick_edit_box' )">
								<span><?php esc_attr_e( 'Discard Changes', 'wp-easycart' ); ?></span>
							</button>
						</li>
					</ul>
				</div>
			</div>
		</footer>
	</div>
</div>
<script>jQuery( document.getElementById( 'new_product_box' ) ).appendTo( document.body );</script>
