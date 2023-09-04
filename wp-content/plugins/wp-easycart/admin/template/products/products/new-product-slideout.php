<?php
global $wpdb;
$manufacturer_list = $wpdb->get_results( "SELECT ec_manufacturer.manufacturer_id AS value, ec_manufacturer.name AS label FROM ec_manufacturer ORDER BY ec_manufacturer.name ASC" );
$basic_option_list = $wpdb->get_results( "SELECT ec_option.option_id AS value, ec_option.option_name AS label FROM ec_option WHERE option_type = 'basic-combo' OR option_type = 'basic-swatch' ORDER BY option_name ASC" );

?>
<div class="ec_admin_slideout_container" id="new_product_box" style="z-index:1028;">
    <div class="ec_admin_slideout_container_content">
        <?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_new_product_display_loader" ); ?>
        <header class="ec_admin_slideout_container_content_header">
            <div class="ec_admin_slideout_container_content_header_inner">
                <h3><?php esc_attr_e( 'Create a Product', 'wp-easycart' ); ?></h3>
                <div class="ec_admin_slideout_close" onclick="wp_easycart_admin_close_slideout( 'new_product_box' );">
                    <div class="dashicons-before dashicons-no-alt"></div>
                </div>
            </div>
        </header>
        <div class="ec_admin_slideout_container_content_inner">
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_status"><?php esc_attr_e( 'Product Status', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_status" name="ec_new_product_status" class="select2-basic">
                        <option value="0"><?php esc_attr_e( 'Not Active', 'wp-easycart' ); ?></option>
                        <option value="1" selected="selected"><?php esc_attr_e( 'Active', 'wp-easycart' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_featured"><?php esc_attr_e( 'Feature on Main Store Page?', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_featured" name="ec_new_product_featured" class="select2-basic">
                        <option value="1" selected="selected"><?php esc_attr_e( 'Yes', 'wp-easycart' ); ?></option>
                        <option value="0"><?php esc_attr_e( 'No', 'wp-easycart' ); ?></option>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_type"><?php esc_attr_e( 'Product Type', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_type" name="ec_new_product_type" class="select2-basic"<?php echo wp_easycart_escape_html( apply_filters( 'wp_easycart_admin_lock_icon', ' onchange="wp_easycart_admin_new_product_type_change( );"' ) ); ?>>
                        <option value="0" selected="selected"><?php esc_attr_e( 'Classic Retail Product', 'wp-easycart' ); ?></option>
                         <option value="11"><?php esc_attr_e( 'Service Product', 'wp-easycart' ); ?></option>
                        <option value="12"><?php esc_attr_e( 'Ticket or Event', 'wp-easycart' ); ?></option>
                    	<option value="13"><?php esc_attr_e( 'Class or Online Course', 'wp-easycart' ); ?></option>
                    	<option value="1"><?php esc_attr_e( 'Downloadable Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="2"><?php esc_attr_e( 'eBook Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="3"><?php esc_attr_e( 'Donation Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="4"><?php esc_attr_e( 'Invoice Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="5"><?php esc_attr_e( 'Subscription Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="6"><?php esc_attr_e( 'Membership Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="7"><?php esc_attr_e( 'Gift Card Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="8"><?php esc_attr_e( 'Deconetwork Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="9"><?php esc_attr_e( 'Inquiry Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                        <option value="10"><?php esc_attr_e( 'Seasonal/Coming Soon Product', 'wp-easycart' ); ?><?php echo esc_attr( apply_filters( 'wp_easycart_admin_lock_icon', ' (PRO/PREMIUM ONLY)' ) ); ?></option>
                    </select>
                </div>
                <div id="stripe_paypal_only" style="display:none; padding:10px 0; font-size:12px; text-align:right;"><?php esc_attr_e( '*NOTE: This product type is only compatible with Stripe', 'wp-easycart' ); ?></div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_title"><?php esc_attr_e( 'Title', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_product_title" name="ec_new_product_title" placeholder="<?php esc_attr_e( 'Your Product Name', 'wp-easycart' ); ?>" />
                </div>
                <div class="ec_admin_slideout_error_text" id="title_required">
                	<?php esc_attr_e( 'The title is required.', 'wp-easycart' ); ?>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_sku"><?php esc_attr_e( 'SKU', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_product_sku" name="ec_new_product_sku" placeholder="<?php esc_attr_e( 'product-name', 'wp-easycart' ); ?>" />
                </div>
                <div class="ec_admin_slideout_error_text" id="sku_required">
                	<?php esc_attr_e( 'The SKU is required.', 'wp-easycart' ); ?>
                </div>
                <div class="ec_admin_slideout_error_text" id="duplicate_sku">
                	<?php esc_attr_e( 'Duplicate SKU, please change to a unique value.', 'wp-easycart' ); ?>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_manufacturer"><?php esc_attr_e( 'Manufacturer', 'wp-easycart' ); ?></label>
                <div>
                	<div class="wpec-admin-75-select">
                        <select id="ec_new_product_manufacturer" name="ec_new_product_manufacturer" class="select2-basic">
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
                <label for="ec_new_product_price"><?php esc_attr_e( 'Price', 'wp-easycart' ); ?></label>
                <div>
                	<?php
                    $step = 1;
                    for( $i=0; $i<$GLOBALS['currency']->get_decimal_length( ); $i++ ){
                        $step = $step / 10;
                    }
					?>
                    <input type="number" step="<?php echo esc_attr( $step ); ?>" id="ec_new_product_price" name="ec_new_product_price" placeholder="19.99" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row">
                <label for="ec_new_product_image"><?php esc_attr_e( 'Image', 'wp-easycart' ); ?></label>
                <div>
                    <input type="text" id="ec_new_product_image" name="ec_new_product_image" class="wpec-admin-upload-input" />
					<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Select Image', 'wp-easycart' ); ?>" id="ec_upload_button_image" onclick="ec_admin_image_upload( 'ec_new_product_image' );" />
                </div>
            </div>
            
            <div class="ec_admin_slideout_container_input_row"<?php if( !get_option( 'ec_option_admin_product_show_stock_option' ) ){ ?> style="display:none;"<?php }?>>
                <label for="ec_new_product_stock_option"><?php esc_attr_e( 'Stock Options', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_stock_option" name="ec_new_product_stock_option" class="select2-basic" onchange="ec_admin_new_product_update_stock_option( );">
                        <option value="0"><?php esc_attr_e( 'Do Not Track Stock', 'wp-easycart' ); ?></option>
                        <option value="1"><?php esc_attr_e( 'Track Basic Stock', 'wp-easycart' ); ?></option>
                        <option value="2"><?php esc_attr_e( 'Track Option Item Stock', 'wp-easycart' ); ?></option>
                    </select>
            	</div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_basic_stock" style="display:none;">
                <div>
                	<input type="number" step="1" id="ec_new_product_stock_quantity" name="ec_new_product_stock_quantity" placeholder="<?php esc_attr_e( 'Stock Quantity', 'wp-easycart' ); ?>" />
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_optionitem_stock" style="display:none; float:left; width:100%; margin-top:25px; text-align:center;">-- <?php esc_attr_e( 'Option item quantities will be added when you edit the product', 'wp-easycart' ); ?> --</div>
            
            <div class="ec_admin_slideout_container_input_row"<?php if( !get_option( 'ec_option_admin_product_show_shipping_option' ) ){ ?> style="display:none;"<?php }?>>
                <label for="ec_new_product_is_shippable"><?php esc_attr_e( 'Shipping Options', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_is_shippable" name="ec_new_product_is_shippable" class="select2-basic" onchange="ec_admin_new_product_update_shipping_type( );">
                        <option value="0"><?php esc_attr_e( 'No Shipping', 'wp-easycart' ); ?></option>
                        <option value="1"><?php esc_attr_e( 'Enable Shipping', 'wp-easycart' ); ?></option>
                    </select>
            	</div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_shipping_row" style="display:none;">
                <div>
                	<div class="wpec-admin-50-wide">
                    	<input type="number" min="0" step=".01" id="ec_new_product_weight" name="ec_new_product_weight" placeholder="<?php esc_attr_e( 'Weight', 'wp-easycart' ); ?>" />
                    </div>
                	<div class="wpec-admin-50-wide">
                    	<input type="number" min="0" step=".01" id="ec_new_product_length" name="ec_new_product_length" placeholder="<?php esc_attr_e( 'Length', 'wp-easycart' ); ?>" />
                    </div>
                	<div class="wpec-admin-50-wide">
                    	<input type="number" min="0" step=".01" id="ec_new_product_width" name="ec_new_product_width" placeholder="<?php esc_attr_e( 'Width', 'wp-easycart' ); ?>" />
                    </div>
                	<div class="wpec-admin-50-wide">
                    	<input type="number" min="0" step=".01" id="ec_new_product_height" name="ec_new_product_height" placeholder="<?php esc_attr_e( 'Height', 'wp-easycart' ); ?>" />
                    </div>
                </div>
            </div>
            
            <div class="ec_admin_slideout_container_input_row"<?php if( !get_option( 'ec_option_admin_product_show_tax_option' ) ){ ?> style="display:none;"<?php }?>>
                <label for="ec_new_product_is_taxable"><?php esc_attr_e( 'Tax Options', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_is_taxable" name="ec_new_product_is_taxable" class="select2-basic">
                        <option value="0"><?php esc_attr_e( 'Not Taxable', 'wp-easycart' ); ?></option>
                        <option value="1"><?php esc_attr_e( 'Enable Tax', 'wp-easycart' ); ?></option>
                        <option value="2"><?php esc_attr_e( 'Enable VAT', 'wp-easycart' ); ?></option>
                    </select>
            	</div>
            </div>
            
            <div class="ec_admin_slideout_container_input_row"<?php if( !get_option( 'ec_option_admin_product_show_variant_option' ) ){ ?> style="display:none;"<?php }?>>
                <label for="ec_new_product_options_needed"><?php esc_attr_e( 'Product Options (Product Variants)?', 'wp-easycart' ); ?></label>
                <div>
                    <select id="ec_new_product_options_needed" name="ec_new_product_options_needed" class="select2-basic" onchange="ec_admin_new_product_update_option_type( );">
                        <option value="0"><?php esc_attr_e( 'No Options or Modifiers', 'wp-easycart' ); ?></option>
                        <option value="1"><?php esc_attr_e( 'Product Options (Variants)', 'wp-easycart' ); ?></option>
                        <?php do_action( 'wp_easycart_admin_product_slideout_option_types' ); ?>
                    </select>
                </div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_option_row" style="display:none;">
                <label for="ec_new_product_option1"><?php echo sprintf( esc_attr__( 'Option %s', 'wp-easycart' ), '1' ); ?></label>
                <div>
                	<div class="wpec-admin-75-select">
                        <select id="ec_new_product_option1" name="ec_new_product_option1" class="select2-basic">
                            <option value="0"><?php esc_attr_e( 'None Selected', 'wp-easycart' ); ?></option>
                            <?php foreach( $basic_option_list as $option ){ ?>
                            <option value="<?php echo esc_attr( $option->value ); ?>"><?php echo esc_attr( $option->label ); ?></option>
                            <?php }?>
                        </select>
	                </div>
                	<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="wp_easycart_admin_open_slideout( 'new_option_box' );" />
            	</div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_option_row" style="display:none;">
                <label for="ec_new_product_option2"><?php echo sprintf( esc_attr__( 'Option %s', 'wp-easycart' ), '2' ); ?></label>
                <div>
                	<div class="wpec-admin-75-select">
                        <select id="ec_new_product_option2" name="ec_new_product_option2" class="select2-basic">
                            <option value="0"><?php esc_attr_e( 'None Selected', 'wp-easycart' ); ?></option>
                            <?php foreach( $basic_option_list as $option ){ ?>
                            <option value="<?php echo esc_attr( $option->value ); ?>"><?php echo esc_attr( $option->label ); ?></option>
                            <?php }?>
                        </select>
	                </div>
                	<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="wp_easycart_admin_open_slideout( 'new_option_box' );" />
            	</div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_option_row" style="display:none;">
                <label for="ec_new_product_option3"><?php echo sprintf( esc_attr__( 'Option %s', 'wp-easycart' ), '3' ); ?></label>
                <div>
                	<div class="wpec-admin-75-select">
                        <select id="ec_new_product_option3" name="ec_new_product_option3" class="select2-basic">
                            <option value="0"><?php esc_attr_e( 'None Selected', 'wp-easycart' ); ?></option>
                            <?php foreach( $basic_option_list as $option ){ ?>
                            <option value="<?php echo esc_attr( $option->value ); ?>"><?php echo esc_attr( $option->label ); ?></option>
                            <?php }?>
                        </select>
	                </div>
                	<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="wp_easycart_admin_open_slideout( 'new_option_box' );" />
            	</div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_option_row" style="display:none;">
                <label for="ec_new_product_option4"><?php echo sprintf( esc_attr__( 'Option %s', 'wp-easycart' ), '4' ); ?></label>
                <div>
                	<div class="wpec-admin-75-select">
                        <select id="ec_new_product_option4" name="ec_new_product_option4" class="select2-basic">
                            <option value="0"><?php esc_attr_e( 'None Selected', 'wp-easycart' ); ?></option>
                            <?php foreach( $basic_option_list as $option ){ ?>
                            <option value="<?php echo esc_attr( $option->value ); ?>"><?php echo esc_attr( $option->label ); ?></option>
                            <?php }?>
                        </select>
	                </div>
                	<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="wp_easycart_admin_open_slideout( 'new_option_box' );" />
            	</div>
            </div>
            <div class="ec_admin_slideout_container_input_row ec_admin_new_product_option_row" style="display:none;">
                <label for="ec_new_product_option5"><?php echo sprintf( esc_attr__( 'Option %s', 'wp-easycart' ), '5' ); ?></label>
                <div>
                	<div class="wpec-admin-75-select">
                        <select id="ec_new_product_option5" name="ec_new_product_option5" class="select2-basic">
                            <option value="0"><?php esc_attr_e( 'None Selected', 'wp-easycart' ); ?></option>
                            <?php foreach( $basic_option_list as $option ){ ?>
                            <option value="<?php echo esc_attr( $option->value ); ?>"><?php echo esc_attr( $option->label ); ?></option>
                            <?php }?>
                        </select>
	                </div>
                	<input type="button" class="wpec-admin-upload-button" value="<?php esc_attr_e( 'Add New', 'wp-easycart' ); ?>" onclick="wp_easycart_admin_open_slideout( 'new_option_box' );" />
            	</div>
            </div>
            
            <div style="display:none; float:left; width:100%; margin-top:25px; text-align:center;" id="ec_new_product_advanced_options">-- <?php esc_attr_e( 'Add advanced options by creating then editing the product', 'wp-easycart' ); ?> --</div>
            <div style="float:left; width:100%; margin-top:25px; text-align:center;">*<?php esc_attr_e( 'You can edit all product settings after creating the product basics', 'wp-easycart' ); ?></div>
            <div style="float:left; width:100%; margin-top:25px; text-align:center;">*<?php esc_attr_e( 'Looking to customize this panel?', 'wp-easycart' ); ?> <a href="admin.php?page=wp-easycart-settings&subpage=miscellaneous" target="_blank"><?php esc_attr_e( 'Click here', 'wp-easycart' ); ?></a>.</div>
        </div>
        <footer class="ec_admin_slideout_container_content_footer">
            <div class="ec_admin_slideout_container_content_footer_inner">
                <div class="ec_admin_slideout_container_content_footer_inner_body">
                    <ul>
                        <li>
                            <button onclick="ec_admin_save_new_quick_product( 1 );">
                                <span><?php esc_attr_e( 'Create and Edit', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                        <li class="ec_admin_mobile_hide">
                            <button onclick="ec_admin_save_new_quick_product( 2 );">
                                <span><?php esc_attr_e( 'Create and Another', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                        <li>
                            <button onclick="ec_admin_save_new_quick_product( 3 );">
                                <span><?php esc_attr_e( 'Create', 'wp-easycart' ); ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</div>
<script>jQuery( document.getElementById( 'new_product_box' ) ).appendTo( document.body );</script>