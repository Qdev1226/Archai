<?php
global $wpdb;
$canada_tax_options = get_option( 'ec_option_canada_tax_options' );
$user_roles = $wpdb->get_results( "SELECT ec_role.* FROM ec_role WHERE ec_role.role_label != 'admin'" );
$provinces = array( "alberta", "british_columbia", "manitoba", "new_brunswick", "newfoundland", "northwest_territories", "nova_scotia", "nunavut", "ontario", "prince_edward_island", "quebec", "saskatchewan", "yukon" );
$user_role_combo = $wpdb->get_results( "SELECT ec_role.role_label AS value, ec_role.role_label AS label FROM ec_role WHERE ec_role.role_label != 'admin'" );
$province_combo = array( 
	(object) array(
		'value'=> "alberta",
		'label'=> "Alberta"
	),
	(object) array(
		'value'=> "british_columbia", 
		'label'=> "British Columbia"
	),
	(object) array(
		'value'=> "manitoba", 
		'label'=> "Manitoba"
	),
	(object) array(
		'value'=> "new_brunswick", 
		'label'=> "New Brunswick"
	),
	(object) array(
		'value'=> "newfoundland", 
		'label'=> "New Foundland"
	),
	(object) array(
		'value'=> "northwest_territories", 
		'label'=> "Northwest Territories"
	),
	(object) array(
		'value'=> "nova_scotia", 
		'label'=> "Nova Scotia"
	),
	(object) array(
		'value'=> "nunavut", 
		'label'=> "Nunavut"
	),
	(object) array(
		'value'=> "ontario", 
		'label'=> "Ontario"
	),
	(object) array(
		'value'=> "prince_edward_island", 
		'label'=> "Prince Edward Island"
	),
	(object) array(
		'value'=> "quebec", 
		'label'=> "Quebec"
	),
	(object) array(
		'value'=> "saskatchewan", 
		'label'=> "Saskatchewan"
	),
	(object) array(
		'value'=> "yukon",
		'label'=> "Yukon"
	)
);

$canada_tax_defaults = array( 
	"alberta" => array(
		"name" => "Alberta", 
		"gst" => .05,
		"pst" => .00,
		"hst" => .00
	),
	"british_columbia" => array( 
		"name" => "British Columbia",
		"gst" => .05,
		"pst" => .07,
		"hst" => .00
	),
	"manitoba" => array( 
		"name" => "Manitoba",
		"gst" => .05,
		"pst" => .08,
		"hst" => .00
	),
	"new_brunswick" => array( 
		"name" => "New Brunswick",
		"gst" => .00,
		"pst" => .00,
		"hst" => .13
	),
	"newfoundland" => array( 
		"name" => "Newfoundland and Labrador",
		"gst" => .00,
		"pst" => .00,
		"hst" => .13
	),
	"northwest_territories" => array( 
		"name" => "Northwest Territories",
		"gst" => .05,
		"pst" => .00,
		"hst" => .00
	),
	"nova_scotia" => array( 
		"name" => "Nova Scotia",
		"gst" => .00,
		"pst" => .00,
		"hst" => .15
	),
	"nunavut" => array( 
		"name" => "Nunavut",
		"gst" => .05,
		"pst" => .00,
		"hst" => .00
	),
	"ontario" => array( 
		"name" => "Ontario",
		"gst" => .00,
		"pst" => .00,
		"hst" => .13
	),
	"prince_edward_island" => array( 
		"name" => "Price Edward Island",
		"gst" => .00,
		"pst" => .00,
		"hst" => .14
	),
	"quebec" => array( 
		"name" => "Quebec",
		"gst" => .05,
		"pst" => .09975,
		"hst" => .00
	),
	"saskatchewan" => array( 
		"name" => "Saskatchewan",
		"gst" => .05,
		"pst" => .05,
		"hst" => .00
	),
	"yukon" => array( 
		"name" => "Yukon",
		"gst" => .05,
		"pst" => .00,
		"hst" => .00
	)
);

?>
<div class="ec_admin_list_line_item">

	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_canada_tax_options_loader" ); ?>

	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-site"></div>
		<span><?php esc_attr_e( 'Canada Tax Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'taxes', 'canada-tax-setup' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
		<?php wp_easycart_admin( )->helpsystem->print_vids_url( 'settings', 'taxes', 'canada-tax-setup' ); ?>
	</div>

	<div class="ec_admin_settings_input ec_admin_settings_products_section">
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_easy_canada_tax', 'ec_admin_update_canada_tax_display', get_option( 'ec_option_enable_easy_canada_tax' ), __( 'Enable Canada Tax', 'wp-easycart' ), __( 'Enabling this will allow you to apply a Canada tax rate by province or territory and customize by user role.', 'wp-easycart' ) ); ?>

		<div id="ec_admin_use_canada_tax_section"<?php if( !get_option( 'ec_option_enable_easy_canada_tax' ) ){ ?> style="display:none;"<?php }?>>
			<?php foreach( $provinces as $province ){
				foreach( $user_roles as $user_role ){ ?>
				<?php wp_easycart_admin( )->load_toggle_group( 
					'ec_option_collect_' . $province . '_tax_' . $user_role->role_label, 
					'ec_admin_update_province_canada_tax_display( \'' . $province . '\', \'' . $user_role->role_label . '\', \'enable\' ); ec_admin_update_canada_tax_display_item', 
					( ( $canada_tax_options && isset( $canada_tax_options['ec_option_collect_' . $province . '_tax_' . $user_role->role_label] ) ) ? true : false ), 
					__( 'Enable', 'wp-easycart' ) . ' ' . $canada_tax_defaults[$province]['name'] . ' (' . $user_role->role_label . ')', 
					'' ); ?>

				<div id="ec_admin_canada_tax_row_<?php echo esc_attr( $province ); ?>_<?php echo esc_attr( $user_role->role_label ); ?>" class="wp_easycart_admin_no_padding" style="float:left; width:100%; border:1px solid #CCC; padding:10px; background:#f3f3f3; margin:-20px 0 20px; <?php if( !$canada_tax_options || !isset( $canada_tax_options['ec_option_collect_' . $province . '_tax_' . $user_role->role_label] ) ){ ?>display:none;<?php }?>">

					<div class="wp_easycart_admin_no_padding" style="float:left; width:33%; margin-right:.5%;">
						<?php wp_easycart_admin( )->load_toggle_group_percentage( 
							'ec_option_' . $province . '_tax_' . $user_role->role_label . '_gst', 
							'ec_admin_update_canada_tax_display_item( jQuery( this ), \'' . $province . '\', \'' . $user_role->role_label . '\', \'gst\' ); void', 
							( ( $canada_tax_options && isset( $canada_tax_options['ec_option_' . $province . '_tax_' . $user_role->role_label . '_gst'] ) ) ? $canada_tax_options['ec_option_' . $province . '_tax_' . $user_role->role_label . '_gst'] : $canada_tax_defaults[$province]['gst'] ), 
							__( 'GST Rate', 'wp-easycart' ), '', '0.000', true, true ); ?>
					</div>
					<div class="wp_easycart_admin_no_padding" style="float:left; width:33%; margin-right:.5%;">
						<?php wp_easycart_admin( )->load_toggle_group_percentage( 
							'ec_option_' . $province . '_tax_' . $user_role->role_label . '_pst', 
							'ec_admin_update_canada_tax_display_item( jQuery( this ), \'' . $province . '\', \'' . $user_role->role_label . '\', \'pst\' ); void', 
							( ( $canada_tax_options && isset( $canada_tax_options['ec_option_' . $province . '_tax_' . $user_role->role_label . '_pst'] ) ) ? $canada_tax_options['ec_option_' . $province . '_tax_' . $user_role->role_label . '_pst'] : $canada_tax_defaults[$province]['pst'] ), 
							__( 'PST Rate', 'wp-easycart' ), '', '0.000', true, true ); ?>
					</div>
					<div class="wp_easycart_admin_no_padding" style="float:left; width:33%;">
						<?php wp_easycart_admin( )->load_toggle_group_percentage( 
							'ec_option_' . $province . '_tax_' . $user_role->role_label . '_hst', 
							'ec_admin_update_canada_tax_display_item( jQuery( this ), \'' . $province . '\', \'' . $user_role->role_label . '\', \'hst\' ); void', 
							( ( $canada_tax_options && isset( $canada_tax_options['ec_option_' . $province . '_tax_' . $user_role->role_label . '_hst'] ) ) ? $canada_tax_options['ec_option_' . $province . '_tax_' . $user_role->role_label . '_hst'] : $canada_tax_defaults[$province]['hst'] ), 
							__( 'HST Rate', 'wp-easycart' ), '', '0.000', true, true ); ?>
					</div>

				</div>
			<?php
				}
			} ?>
		</div>

	</div>

</div>