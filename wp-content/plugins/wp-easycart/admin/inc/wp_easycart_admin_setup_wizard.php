<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_setup_wizard' ) ) :

	final class wp_easycart_admin_setup_wizard{

		protected static $_instance = null;

		public $step = 0;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self(  );
			}
			return self::$_instance;

		}

		public function __construct() { 
			/* Display EasyCart Actions */
			add_action( 'wp_easycart_admin_wizard_navigation', array( $this, 'load_navigation' ) );
			add_action( 'wp_easycart_admin_wizard_content', array( $this, 'load_content' ) );

			/* Process EasyCart Form Actions */
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_skip_wizard' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_page_setup_submit' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_location_submit' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_payments_submit' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_shipping_submit' ) );
		}

		public function load_setup_wizard() {
			$this->step = get_option( 'ec_option_setup_wizard_step' );
			if ( isset( $_GET['step'] ) ) {
				update_option( 'ec_option_setup_wizard_step', (int) $_GET['step'] );
				$this->step = (int) $_GET['step'];
			}
			include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/shell.php' );
		}

		public function load_navigation() {
			include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/navigation.php' );
		}

		public function load_content() {
			if ( $this->step == 0 ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/intro.php' );
			} else if ( $this->step == 1 ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/page-setup.php' );
			} else if ( $this->step == 2 ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/location.php' );
			} else if ( $this->step == 3 ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/payments.php' );
			} else if ( $this->step == 4 ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/shipping.php' );
			} else if ( $this->step == 5 ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/template/settings/wizard/complete.php' );
			}
		}

		public function process_skip_wizard() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( $_GET['ec_admin_form_action'] == 'skip-wizard' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-skip-wizard' ) ) {
					if ( get_option( 'ec_option_allow_tracking' ) == '3' ) {
						update_option( 'ec_option_allow_tracking', 0 );
					}
					update_option( 'ec_option_setup_wizard_done', 1 );
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=5' );
				}
			}
		}

		public function process_page_setup_submit() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( $_POST['ec_admin_form_action'] == 'process-wizard-page-setup' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-process-wizard-page-setup' ) ) {
					$ec_option_cache_prevent = $ec_option_load_ssl = 0;
					$ec_option_use_old_linking_style = 1;
					if ( isset( $_POST['ec_option_cache_prevent'] ) ) {
						$ec_option_cache_prevent = 1;
					}
					if ( isset( $_POST['ec_option_load_ssl'] ) ) {
						$ec_option_load_ssl = 1;
					}
					if ( isset( $_POST['ec_option_use_old_linking_style'] ) ) {
						$ec_option_use_old_linking_style = 0;
					}
					update_option( 'ec_option_cache_prevent', $ec_option_cache_prevent );
					update_option( 'ec_option_load_ssl', $ec_option_load_ssl );
					update_option( 'ec_option_use_old_linking_style', $ec_option_use_old_linking_style );

					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=2' );
				}
			}
		}

		public function process_location_submit() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( $_POST['ec_admin_form_action'] == 'process-wizard-location' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-process-wizard-location' ) ) {
					$countries = array(
						'US' => __( 'United States (US)', 'wp-easycart' ),
						'CA' => __( 'Canada', 'wp-easycart' ),
						'GB' => __( 'United Kingdom (UK)', 'wp-easycart' ),
						'AU' => __( 'Australia', 'wp-easycart' ),
						'AF' => __( 'Afghanistan', 'wp-easycart' ),
						'AX' => __( '&#197;land Islands', 'wp-easycart' ),
						'AL' => __( 'Albania', 'wp-easycart' ),
						'DZ' => __( 'Algeria', 'wp-easycart' ),
						'AS' => __( 'American Samoa', 'wp-easycart' ),
						'AD' => __( 'Andorra', 'wp-easycart' ),
						'AO' => __( 'Angola', 'wp-easycart' ),
						'AI' => __( 'Anguilla', 'wp-easycart' ),
						'AQ' => __( 'Antarctica', 'wp-easycart' ),
						'AG' => __( 'Antigua and Barbuda', 'wp-easycart' ),
						'AR' => __( 'Argentina', 'wp-easycart' ),
						'AM' => __( 'Armenia', 'wp-easycart' ),
						'AW' => __( 'Aruba', 'wp-easycart' ),
						'AU' => __( 'Australia', 'wp-easycart' ),
						'AT' => __( 'Austria', 'wp-easycart' ),
						'AZ' => __( 'Azerbaijan', 'wp-easycart' ),
						'BS' => __( 'Bahamas', 'wp-easycart' ),
						'BH' => __( 'Bahrain', 'wp-easycart' ),
						'BD' => __( 'Bangladesh', 'wp-easycart' ),
						'BB' => __( 'Barbados', 'wp-easycart' ),
						'BY' => __( 'Belarus', 'wp-easycart' ),
						'BE' => __( 'Belgium', 'wp-easycart' ),
						'PW' => __( 'Belau', 'wp-easycart' ),
						'BZ' => __( 'Belize', 'wp-easycart' ),
						'BJ' => __( 'Benin', 'wp-easycart' ),
						'BM' => __( 'Bermuda', 'wp-easycart' ),
						'BT' => __( 'Bhutan', 'wp-easycart' ),
						'BO' => __( 'Bolivia', 'wp-easycart' ),
						'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'wp-easycart' ),
						'BA' => __( 'Bosnia and Herzegovina', 'wp-easycart' ),
						'BW' => __( 'Botswana', 'wp-easycart' ),
						'BV' => __( 'Bouvet Island', 'wp-easycart' ),
						'BR' => __( 'Brazil', 'wp-easycart' ),
						'IO' => __( 'British Indian Ocean Territory', 'wp-easycart' ),
						'VG' => __( 'British Virgin Islands', 'wp-easycart' ),
						'BN' => __( 'Brunei', 'wp-easycart' ),
						'BG' => __( 'Bulgaria', 'wp-easycart' ),
						'BF' => __( 'Burkina Faso', 'wp-easycart' ),
						'BI' => __( 'Burundi', 'wp-easycart' ),
						'KH' => __( 'Cambodia', 'wp-easycart' ),
						'CM' => __( 'Cameroon', 'wp-easycart' ),
						'CA' => __( 'Canada', 'wp-easycart' ),
						'CV' => __( 'Cape Verde', 'wp-easycart' ),
						'KY' => __( 'Cayman Islands', 'wp-easycart' ),
						'CF' => __( 'Central African Republic', 'wp-easycart' ),
						'TD' => __( 'Chad', 'wp-easycart' ),
						'CL' => __( 'Chile', 'wp-easycart' ),
						'CN' => __( 'China', 'wp-easycart' ),
						'CX' => __( 'Christmas Island', 'wp-easycart' ),
						'CC' => __( 'Cocos (Keeling) Islands', 'wp-easycart' ),
						'CO' => __( 'Colombia', 'wp-easycart' ),
						'KM' => __( 'Comoros', 'wp-easycart' ),
						'CG' => __( 'Congo (Brazzaville)', 'wp-easycart' ),
						'CD' => __( 'Congo (Kinshasa)', 'wp-easycart' ),
						'CK' => __( 'Cook Islands', 'wp-easycart' ),
						'CR' => __( 'Costa Rica', 'wp-easycart' ),
						'HR' => __( 'Croatia', 'wp-easycart' ),
						'CU' => __( 'Cuba', 'wp-easycart' ),
						'CW' => __( 'Cura&ccedil;ao', 'wp-easycart' ),
						'CY' => __( 'Cyprus', 'wp-easycart' ),
						'CZ' => __( 'Czech Republic', 'wp-easycart' ),
						'DK' => __( 'Denmark', 'wp-easycart' ),
						'DJ' => __( 'Djibouti', 'wp-easycart' ),
						'DM' => __( 'Dominica', 'wp-easycart' ),
						'DO' => __( 'Dominican Republic', 'wp-easycart' ),
						'EC' => __( 'Ecuador', 'wp-easycart' ),
						'EG' => __( 'Egypt', 'wp-easycart' ),
						'SV' => __( 'El Salvador', 'wp-easycart' ),
						'GQ' => __( 'Equatorial Guinea', 'wp-easycart' ),
						'ER' => __( 'Eritrea', 'wp-easycart' ),
						'EE' => __( 'Estonia', 'wp-easycart' ),
						'ET' => __( 'Ethiopia', 'wp-easycart' ),
						'FK' => __( 'Falkland Islands', 'wp-easycart' ),
						'FO' => __( 'Faroe Islands', 'wp-easycart' ),
						'FJ' => __( 'Fiji', 'wp-easycart' ),
						'FI' => __( 'Finland', 'wp-easycart' ),
						'FR' => __( 'France', 'wp-easycart' ),
						'GF' => __( 'French Guiana', 'wp-easycart' ),
						'PF' => __( 'French Polynesia', 'wp-easycart' ),
						'TF' => __( 'French Southern Territories', 'wp-easycart' ),
						'GA' => __( 'Gabon', 'wp-easycart' ),
						'GM' => __( 'Gambia', 'wp-easycart' ),
						'GE' => __( 'Georgia', 'wp-easycart' ),
						'DE' => __( 'Germany', 'wp-easycart' ),
						'GH' => __( 'Ghana', 'wp-easycart' ),
						'GI' => __( 'Gibraltar', 'wp-easycart' ),
						'GR' => __( 'Greece', 'wp-easycart' ),
						'GL' => __( 'Greenland', 'wp-easycart' ),
						'GD' => __( 'Grenada', 'wp-easycart' ),
						'GP' => __( 'Guadeloupe', 'wp-easycart' ),
						'GU' => __( 'Guam', 'wp-easycart' ),
						'GT' => __( 'Guatemala', 'wp-easycart' ),
						'GG' => __( 'Guernsey', 'wp-easycart' ),
						'GN' => __( 'Guinea', 'wp-easycart' ),
						'GW' => __( 'Guinea-Bissau', 'wp-easycart' ),
						'GY' => __( 'Guyana', 'wp-easycart' ),
						'HT' => __( 'Haiti', 'wp-easycart' ),
						'HM' => __( 'Heard Island and McDonald Islands', 'wp-easycart' ),
						'HN' => __( 'Honduras', 'wp-easycart' ),
						'HK' => __( 'Hong Kong', 'wp-easycart' ),
						'HU' => __( 'Hungary', 'wp-easycart' ),
						'IS' => __( 'Iceland', 'wp-easycart' ),
						'IN' => __( 'India', 'wp-easycart' ),
						'ID' => __( 'Indonesia', 'wp-easycart' ),
						'IR' => __( 'Iran', 'wp-easycart' ),
						'IQ' => __( 'Iraq', 'wp-easycart' ),
						'IE' => __( 'Ireland', 'wp-easycart' ),
						'IM' => __( 'Isle of Man', 'wp-easycart' ),
						'IL' => __( 'Israel', 'wp-easycart' ),
						'IT' => __( 'Italy', 'wp-easycart' ),
						'CI' => __( 'Ivory Coast', 'wp-easycart' ),
						'JM' => __( 'Jamaica', 'wp-easycart' ),
						'JP' => __( 'Japan', 'wp-easycart' ),
						'JE' => __( 'Jersey', 'wp-easycart' ),
						'JO' => __( 'Jordan', 'wp-easycart' ),
						'KZ' => __( 'Kazakhstan', 'wp-easycart' ),
						'KE' => __( 'Kenya', 'wp-easycart' ),
						'KI' => __( 'Kiribati', 'wp-easycart' ),
						'KW' => __( 'Kuwait', 'wp-easycart' ),
						'KG' => __( 'Kyrgyzstan', 'wp-easycart' ),
						'LA' => __( 'Laos', 'wp-easycart' ),
						'LV' => __( 'Latvia', 'wp-easycart' ),
						'LB' => __( 'Lebanon', 'wp-easycart' ),
						'LS' => __( 'Lesotho', 'wp-easycart' ),
						'LR' => __( 'Liberia', 'wp-easycart' ),
						'LY' => __( 'Libya', 'wp-easycart' ),
						'LI' => __( 'Liechtenstein', 'wp-easycart' ),
						'LT' => __( 'Lithuania', 'wp-easycart' ),
						'LU' => __( 'Luxembourg', 'wp-easycart' ),
						'MO' => __( 'Macao S.A.R., China', 'wp-easycart' ),
						'MK' => __( 'Macedonia', 'wp-easycart' ),
						'MG' => __( 'Madagascar', 'wp-easycart' ),
						'MW' => __( 'Malawi', 'wp-easycart' ),
						'MY' => __( 'Malaysia', 'wp-easycart' ),
						'MV' => __( 'Maldives', 'wp-easycart' ),
						'ML' => __( 'Mali', 'wp-easycart' ),
						'MT' => __( 'Malta', 'wp-easycart' ),
						'MH' => __( 'Marshall Islands', 'wp-easycart' ),
						'MQ' => __( 'Martinique', 'wp-easycart' ),
						'MR' => __( 'Mauritania', 'wp-easycart' ),
						'MU' => __( 'Mauritius', 'wp-easycart' ),
						'YT' => __( 'Mayotte', 'wp-easycart' ),
						'MX' => __( 'Mexico', 'wp-easycart' ),
						'FM' => __( 'Micronesia', 'wp-easycart' ),
						'MD' => __( 'Moldova', 'wp-easycart' ),
						'MC' => __( 'Monaco', 'wp-easycart' ),
						'MN' => __( 'Mongolia', 'wp-easycart' ),
						'ME' => __( 'Montenegro', 'wp-easycart' ),
						'MS' => __( 'Montserrat', 'wp-easycart' ),
						'MA' => __( 'Morocco', 'wp-easycart' ),
						'MZ' => __( 'Mozambique', 'wp-easycart' ),
						'MM' => __( 'Myanmar', 'wp-easycart' ),
						'NA' => __( 'Namibia', 'wp-easycart' ),
						'NR' => __( 'Nauru', 'wp-easycart' ),
						'NP' => __( 'Nepal', 'wp-easycart' ),
						'NL' => __( 'Netherlands', 'wp-easycart' ),
						'NC' => __( 'New Caledonia', 'wp-easycart' ),
						'NZ' => __( 'New Zealand', 'wp-easycart' ),
						'NI' => __( 'Nicaragua', 'wp-easycart' ),
						'NE' => __( 'Niger', 'wp-easycart' ),
						'NG' => __( 'Nigeria', 'wp-easycart' ),
						'NU' => __( 'Niue', 'wp-easycart' ),
						'NF' => __( 'Norfolk Island', 'wp-easycart' ),
						'MP' => __( 'Northern Mariana Islands', 'wp-easycart' ),
						'KP' => __( 'North Korea', 'wp-easycart' ),
						'NO' => __( 'Norway', 'wp-easycart' ),
						'OM' => __( 'Oman', 'wp-easycart' ),
						'PK' => __( 'Pakistan', 'wp-easycart' ),
						'PS' => __( 'Palestinian Territory', 'wp-easycart' ),
						'PA' => __( 'Panama', 'wp-easycart' ),
						'PG' => __( 'Papua New Guinea', 'wp-easycart' ),
						'PY' => __( 'Paraguay', 'wp-easycart' ),
						'PE' => __( 'Peru', 'wp-easycart' ),
						'PH' => __( 'Philippines', 'wp-easycart' ),
						'PN' => __( 'Pitcairn', 'wp-easycart' ),
						'PL' => __( 'Poland', 'wp-easycart' ),
						'PT' => __( 'Portugal', 'wp-easycart' ),
						'PR' => __( 'Puerto Rico', 'wp-easycart' ),
						'QA' => __( 'Qatar', 'wp-easycart' ),
						'RE' => __( 'Reunion', 'wp-easycart' ),
						'RO' => __( 'Romania', 'wp-easycart' ),
						'RU' => __( 'Russia', 'wp-easycart' ),
						'RW' => __( 'Rwanda', 'wp-easycart' ),
						'BL' => __( 'Saint Barth&eacute;lemy', 'wp-easycart' ),
						'SH' => __( 'Saint Helena', 'wp-easycart' ),
						'KN' => __( 'Saint Kitts and Nevis', 'wp-easycart' ),
						'LC' => __( 'Saint Lucia', 'wp-easycart' ),
						'MF' => __( 'Saint Martin (French part)', 'wp-easycart' ),
						'SX' => __( 'Saint Martin (Dutch part)', 'wp-easycart' ),
						'PM' => __( 'Saint Pierre and Miquelon', 'wp-easycart' ),
						'VC' => __( 'Saint Vincent and the Grenadines', 'wp-easycart' ),
						'SM' => __( 'San Marino', 'wp-easycart' ),
						'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'wp-easycart' ),
						'SA' => __( 'Saudi Arabia', 'wp-easycart' ),
						'SN' => __( 'Senegal', 'wp-easycart' ),
						'RS' => __( 'Serbia', 'wp-easycart' ),
						'SC' => __( 'Seychelles', 'wp-easycart' ),
						'SL' => __( 'Sierra Leone', 'wp-easycart' ),
						'SG' => __( 'Singapore', 'wp-easycart' ),
						'SK' => __( 'Slovak Republic', 'wp-easycart' ),
						'SI' => __( 'Slovenia', 'wp-easycart' ),
						'SB' => __( 'Solomon Islands', 'wp-easycart' ),
						'SO' => __( 'Somalia', 'wp-easycart' ),
						'ZA' => __( 'South Africa', 'wp-easycart' ),
						'GS' => __( 'South Georgia/Sandwich Islands', 'wp-easycart' ),
						'KR' => __( 'South Korea', 'wp-easycart' ),
						'SS' => __( 'South Sudan', 'wp-easycart' ),
						'ES' => __( 'Spain', 'wp-easycart' ),
						'LK' => __( 'Sri Lanka', 'wp-easycart' ),
						'SD' => __( 'Sudan', 'wp-easycart' ),
						'SR' => __( 'Suriname', 'wp-easycart' ),
						'SJ' => __( 'Svalbard and Jan Mayen', 'wp-easycart' ),
						'SZ' => __( 'Swaziland', 'wp-easycart' ),
						'SE' => __( 'Sweden', 'wp-easycart' ),
						'CH' => __( 'Switzerland', 'wp-easycart' ),
						'SY' => __( 'Syria', 'wp-easycart' ),
						'TW' => __( 'Taiwan', 'wp-easycart' ),
						'TJ' => __( 'Tajikistan', 'wp-easycart' ),
						'TZ' => __( 'Tanzania', 'wp-easycart' ),
						'TH' => __( 'Thailand', 'wp-easycart' ),
						'TL' => __( 'Timor-Leste', 'wp-easycart' ),
						'TG' => __( 'Togo', 'wp-easycart' ),
						'TK' => __( 'Tokelau', 'wp-easycart' ),
						'TO' => __( 'Tonga', 'wp-easycart' ),
						'TT' => __( 'Trinidad and Tobago', 'wp-easycart' ),
						'TN' => __( 'Tunisia', 'wp-easycart' ),
						'TR' => __( 'Turkey', 'wp-easycart' ),
						'TM' => __( 'Turkmenistan', 'wp-easycart' ),
						'TC' => __( 'Turks and Caicos Islands', 'wp-easycart' ),
						'TV' => __( 'Tuvalu', 'wp-easycart' ),
						'UG' => __( 'Uganda', 'wp-easycart' ),
						'UA' => __( 'Ukraine', 'wp-easycart' ),
						'AE' => __( 'United Arab Emirates', 'wp-easycart' ),
						'GB' => __( 'United Kingdom (UK)', 'wp-easycart' ),
						'US' => __( 'United States (US)', 'wp-easycart' ),
						'UM' => __( 'United States (US) Minor Outlying Islands', 'wp-easycart' ),
						'VI' => __( 'United States (US) Virgin Islands', 'wp-easycart' ),
						'UY' => __( 'Uruguay', 'wp-easycart' ),
						'UZ' => __( 'Uzbekistan', 'wp-easycart' ),
						'VU' => __( 'Vanuatu', 'wp-easycart' ),
						'VA' => __( 'Vatican', 'wp-easycart' ),
						'VE' => __( 'Venezuela', 'wp-easycart' ),
						'VN' => __( 'Vietnam', 'wp-easycart' ),
						'WF' => __( 'Wallis and Futuna', 'wp-easycart' ),
						'EH' => __( 'Western Sahara', 'wp-easycart' ),
						'WS' => __( 'Samoa', 'wp-easycart' ),
						'YE' => __( 'Yemen', 'wp-easycart' ),
						'ZM' => __( 'Zambia', 'wp-easycart' ),
						'ZW' => __( 'Zimbabwe', 'wp-easycart' ),
					);

					$locales =  array(
						'US' => array(
							'currency_code'  => 'USD',
							'currency_pos'	=> 'left',
							'thousand_sep'	=> ',',
							'decimal_sep'	 => '.',
							'num_decimals'	=> 2,
							'weight_unit'	 => 'lbs',
							'dimension_unit' => 'in',
							'tax_rates'		=> array(
								'AL' => array(
									array(
										'country'  => 'US',
										'state'    => 'AL',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'AZ' => array(
									array(
										'country'  => 'US',
										'state'    => 'AZ',
										'rate'     => '5.6000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'AR' => array(
									array(
										'country'  => 'US',
										'state'    => 'AR',
										'rate'     => '6.5000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'CA' => array(
									array(
										'country'  => 'US',
										'state'    => 'CA',
										'rate'     => '7.5000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'CO' => array(
									array(
										'country'  => 'US',
										'state'    => 'CO',
										'rate'     => '2.9000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'CT' => array(
									array(
										'country'  => 'US',
										'state'    => 'CT',
										'rate'     => '6.3500',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'DC' => array(
									array(
										'country'  => 'US',
										'state'    => 'DC',
										'rate'     => '5.7500',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'FL' => array(
									array(
										'country'  => 'US',
										'state'    => 'FL',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'GA' => array(
									array(
										'country'  => 'US',
										'state'    => 'GA',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'GU' => array(
									array(
										'country'  => 'US',
										'state'    => 'GU',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'HI' => array(
									array(
										'country'  => 'US',
										'state'    => 'HI',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'ID' => array(
									array(
										'country'  => 'US',
										'state'    => 'ID',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'IL' => array(
									array(
										'country'  => 'US',
										'state'    => 'IL',
										'rate'     => '6.2500',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'IN' => array(
									array(
										'country'  => 'US',
										'state'    => 'IN',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'IA' => array(
									array(
										'country'  => 'US',
										'state'    => 'IA',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'KS' => array(
									array(
										'country'  => 'US',
										'state'    => 'KS',
										'rate'     => '6.1500',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'KY' => array(
									array(
										'country'  => 'US',
										'state'    => 'KY',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'LA' => array(
									array(
										'country'  => 'US',
										'state'    => 'LA',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'ME' => array(
									array(
										'country'  => 'US',
										'state'    => 'ME',
										'rate'     => '5.5000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'MD' => array(
									array(
										'country'  => 'US',
										'state'    => 'MD',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'MA' => array(
									array(
										'country'  => 'US',
										'state'    => 'MA',
										'rate'     => '6.2500',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'MI' => array(
									array(
										'country'  => 'US',
										'state'    => 'MI',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'MN' => array(
									array(
										'country'  => 'US',
										'state'    => 'MN',
										'rate'     => '6.8750',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'MS' => array(
									array(
										'country'  => 'US',
										'state'    => 'MS',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'MO' => array(
									array(
										'country'  => 'US',
										'state'    => 'MO',
										'rate'     => '4.225',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'NE' => array(
									array(
										'country'  => 'US',
										'state'    => 'NE',
										'rate'     => '5.5000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'NV' => array(
									array(
										'country'  => 'US',
										'state'    => 'NV',
										'rate'     => '6.8500',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'NJ' => array(
									array(
										'country'  => 'US',
										'state'    => 'NJ',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'NM' => array(
									array(
										'country'  => 'US',
										'state'    => 'NM',
										'rate'     => '5.1250',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'NY' => array(
									array(
										'country'  => 'US',
										'state'    => 'NY',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'NC' => array(
									array(
										'country'  => 'US',
										'state'    => 'NC',
										'rate'     => '4.7500',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'ND' => array(
									array(
										'country'  => 'US',
										'state'    => 'ND',
										'rate'     => '5.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'OH' => array(
									array(
										'country'  => 'US',
										'state'    => 'OH',
										'rate'     => '5.7500',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'OK' => array(
									array(
										'country'  => 'US',
										'state'    => 'OK',
										'rate'     => '4.5000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'PA' => array(
									array(
										'country'  => 'US',
										'state'    => 'PA',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'PR' => array(
									array(
										'country'  => 'US',
										'state'    => 'PR',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'RI' => array(
									array(
										'country'  => 'US',
										'state'    => 'RI',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'SC' => array(
									array(
										'country'  => 'US',
										'state'    => 'SC',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'SD' => array(
									array(
										'country'  => 'US',
										'state'    => 'SD',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'TN' => array(
									array(
										'country'  => 'US',
										'state'    => 'TN',
										'rate'     => '7.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'TX' => array(
									array(
										'country'  => 'US',
										'state'    => 'TX',
										'rate'     => '6.2500',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'UT' => array(
									array(
										'country'  => 'US',
										'state'    => 'UT',
										'rate'     => '5.9500',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'VT' => array(
									array(
										'country'  => 'US',
										'state'    => 'VT',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'VA' => array(
									array(
										'country'  => 'US',
										'state'    => 'VA',
										'rate'     => '5.3000',
										'name'     => 'State Tax',
										'shipping' => false,
									),
								),
								'WA' => array(
									array(
										'country'  => 'US',
										'state'    => 'WA',
										'rate'     => '6.5000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'WV' => array(
									array(
										'country'  => 'US',
										'state'    => 'WV',
										'rate'     => '6.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'WI' => array(
									array(
										'country'  => 'US',
										'state'    => 'WI',
										'rate'     => '5.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
								'WY' => array(
									array(
										'country'  => 'US',
										'state'    => 'WY',
										'rate'     => '4.0000',
										'name'     => 'State Tax',
										'shipping' => true,
									),
								),
							),
						),
						'CA' => array(
							'currency_code'  => 'CAD',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'BC' => array(
									array(
										'country'  => 'CA',
										'state'    => 'BC',
										'rate'     => '7.0000',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => false,
										'priority' => 2,
									),
								),
								'SK' => array(
									array(
										'country'  => 'CA',
										'state'    => 'SK',
										'rate'     => '5.0000',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => false,
										'priority' => 2,
									),
								),
								'MB' => array(
									array(
										'country'  => 'CA',
										'state'    => 'MB',
										'rate'     => '8.0000',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => false,
										'priority' => 2,
									),
								),
								'QC' => array(
									array(
										'country'  => 'CA',
										'state'    => 'QC',
										'rate'     => '9.975',
										'name'     => _x( 'PST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => false,
										'priority' => 2,
									),
								),
								'*' => array(
									array(
										'country'  => 'CA',
										'state'    => 'ON',
										'rate'     => '13.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'NL',
										'rate'     => '13.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'NB',
										'rate'     => '13.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'PE',
										'rate'     => '14.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'NS',
										'rate'     => '15.0000',
										'name'     => _x( 'HST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'AB',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'BC',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'NT',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'NU',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'YT',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'SK',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'MB',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
									array(
										'country'  => 'CA',
										'state'    => 'QC',
										'rate'     => '5.0000',
										'name'     => _x( 'GST', 'Canadian Tax Rates', 'wp-easycart' ),
										'shipping' => true,
									),
								),
							),
						),
						'AT' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'in',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'AT',
										'state'    => '',
										'rate'     => '20.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'AU' => array(
							'currency_code'  => 'AUD',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'AU',
										'state'    => '',
										'rate'     => '10.0000',
										'name'     => 'GST',
										'shipping' => true,
									),
								),
							),
						),
						'BD' => array(
							'currency_code'  => 'BDT',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'in',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'BD',
										'state'    => '',
										'rate'     => '15.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'BE' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => ' ',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
								  array(
										'country'  => 'BE',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'BTW',
										'shipping' => true,
									),
								),
							),
						),
						'BR' => array(
							'currency_code'  => 'BRL',
							'currency_pos'   => 'left',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(),
						),
						'CH' => array(
							'currency_code'  => 'CHF',
							'currency_pos'   => 'left',
							'thousand_sep'   => "'",
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'CH',
										'state'    => '',
										'rate'     => '7.7000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'CZ' => array(
							'currency_code'  => 'CZK',
							'currency_pos'   => 'left',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'CZ',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'DE' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'DE',
										'state'    => '',
										'rate'     => '19.0000',
										'name'     => 'Mwst.',
										'shipping' => true,
									),
								),
							),
						),
						'DK' => array(
							'currency_code'  => 'DKK',
							'currency_pos'   => 'left',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'DK',
										'state'    => '',
										'rate'     => '25.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'EE' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'EE',
										'state'    => '',
										'rate'     => '20.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'ES' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'ES',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'FI' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => ' ',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'FI',
										'state'    => '',
										'rate'     => '24.0000',
										'name'     => 'ALV',
										'shipping' => true,
									),
								),
							),
						),
						'FR' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right',
							'thousand_sep'   => ' ',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'FR',
										'state'    => '',
										'rate'     => '20.0000',
										'name'     => 'TVA',
										'shipping' => true,
									),
								),
							),
						),
						'GB' => array(
							'currency_code'  => 'GBP',
							'currency_pos'	=> 'left',
							'thousand_sep'	=> ',',
							'decimal_sep'	 => '.',
							'num_decimals'	=> 2,
							'weight_unit'	 => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'		=> array(
								'' => array(
									array(
										'country'  => 'GB',
										'state'	 => '',
										'rate'	  => '20.0000',
										'name'	  => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'GR' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'GR',
										'state'    => '',
										'rate'     => '24.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'HU' => array(
							'currency_code'  => 'HUF',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => ' ',
							'decimal_sep'    => ',',
							'num_decimals'   => 0,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'HU',
										'state'    => '',
										'rate'     => '27.0000',
										'name'     => 'ÁFA',
										'shipping' => true,
									),
								),
							),
						),
						'IE' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'IE',
										'state'    => '',
										'rate'     => '23.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'IS' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'IS',
										'state'    => '',
										'rate'     => '24.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'IT' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'IT',
										'state'    => '',
										'rate'     => '22.0000',
										'name'     => 'IVA',
										'shipping' => true,
									),
								),
							),
						),
						'JP' => array(
							'currency_code'  => 'JPY',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 0,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'JP',
										'state'    => '',
										'rate'     => '8.0000',
										'name'     => __( 'Consumption tax', 'wp-easycart' ),
										'shipping' => true,
									),
								),
							),
						),
						'LV' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'LV',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'LU' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'LU',
										'state'    => '',
										'rate'     => '17.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'NL' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'NL',
										'state'    => '',
										'rate'     => '21.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'NO' => array(
							'currency_code'  => 'Kr',
							'currency_pos'   => 'left_space',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'NO',
										'state'    => '',
										'rate'     => '25.0000',
										'name'     => 'MVA',
										'shipping' => true,
									),
								),
							),
						),
						'NP' => array(
							'currency_code'  => 'NPR',
							'currency_pos'   => 'left_space',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'NP',
										'state'    => '',
										'rate'     => '13.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'PL' => array(
							'currency_code'  => 'PLN',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => ' ',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'PL',
										'state'    => '',
										'rate'     => '23.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'PT' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'PT',
										'state'    => '',
										'rate'     => '23.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'SE' => array(
							'currency_code'  => 'SEK',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => ' ',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'SE',
										'state'    => '',
										'rate'     => '25.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'SI' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'SI',
										'state'    => '',
										'rate'     => '22.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'SK' => array(
							'currency_code'  => 'EUR',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'SK',
										'state'    => '',
										'rate'     => '20.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'RO' => array(
							'currency_code'  => 'RON',
							'currency_pos'   => 'right_space',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'RO',
										'state'    => '',
										'rate'     => '19.0000',
										'name'     => 'TVA',
										'shipping' => true,
									),
								),
							),
						),
						'TH' => array(
							'currency_code'  => 'THB',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'TH',
										'state'    => '',
										'rate'     => '7.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
						'TR' => array(
							'currency_code'  => 'TRY',
							'currency_pos'   => 'left_space',
							'thousand_sep'   => '.',
							'decimal_sep'    => ',',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'TR',
										'state'    => '',
										'rate'     => '18.0000',
										'name'     => 'KDV',
										'shipping' => true,
									),
								),
							),
						),
						'ZA' => array(
							'currency_code'  => 'ZAR',
							'currency_pos'   => 'left',
							'thousand_sep'   => ',',
							'decimal_sep'    => '.',
							'num_decimals'   => 2,
							'weight_unit'    => 'kg',
							'dimension_unit' => 'cm',
							'tax_rates'      => array(
								'' => array(
									array(
										'country'  => 'ZA',
										'state'    => '',
										'rate'     => '15.0000',
										'name'     => 'VAT',
										'shipping' => true,
									),
								),
							),
						),
					);

					$currency_symbols = array(
						'AED' => '&#x62f;.&#x625;',
						'AFN' => '&#x60b;',
						'ALL' => 'L',
						'AMD' => 'AMD',
						'ANG' => '&fnof;',
						'AOA' => 'Kz',
						'ARS' => '$',
						'AUD' => '$',
						'AWG' => 'Afl.',
						'AZN' => 'AZN',
						'BAM' => 'KM',
						'BBD' => '$',
						'BDT' => '&#2547;&nbsp;',
						'BGN' => '&#1083;&#1074;.',
						'BHD' => '.&#x62f;.&#x628;',
						'BIF' => 'Fr',
						'BMD' => '$',
						'BND' => '$',
						'BOB' => 'Bs.',
						'BRL' => '&#82;$',
						'BSD' => '$',
						'BTC' => '&#3647;',
						'BTN' => 'Nu.',
						'BWP' => 'P',
						'BYR' => 'Br',
						'BZD' => '$',
						'CAD' => '$',
						'CDF' => 'Fr',
						'CHF' => '&#67;&#72;&#70;',
						'CLP' => '$',
						'CNY' => '&yen;',
						'COP' => '$',
						'CRC' => '&#x20a1;',
						'CUC' => '$',
						'CUP' => '$',
						'CVE' => '$',
						'CZK' => '&#75;&#269;',
						'DJF' => 'Fr',
						'DKK' => 'DKK',
						'DOP' => 'RD$',
						'DZD' => '&#x62f;.&#x62c;',
						'EGP' => 'EGP',
						'ERN' => 'Nfk',
						'ETB' => 'Br',
						'EUR' => '&euro;',
						'FJD' => '$',
						'FKP' => '&pound;',
						'GBP' => '&pound;',
						'GEL' => '&#x10da;',
						'GGP' => '&pound;',
						'GHS' => '&#x20b5;',
						'GIP' => '&pound;',
						'GMD' => 'D',
						'GNF' => 'Fr',
						'GTQ' => 'Q',
						'GYD' => '$',
						'HKD' => '$',
						'HNL' => 'L',
						'HRK' => 'Kn',
						'HTG' => 'G',
						'HUF' => '&#70;&#116;',
						'IDR' => 'Rp',
						'ILS' => '&#8362;',
						'IMP' => '&pound;',
						'INR' => '&#8377;',
						'IQD' => '&#x639;.&#x62f;',
						'IRR' => '&#xfdfc;',
						'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
						'ISK' => 'kr.',
						'JEP' => '&pound;',
						'JMD' => '$',
						'JOD' => '&#x62f;.&#x627;',
						'JPY' => '&yen;',
						'KES' => 'KSh',
						'KGS' => '&#x441;&#x43e;&#x43c;',
						'KHR' => '&#x17db;',
						'KMF' => 'Fr',
						'KPW' => '&#x20a9;',
						'KRW' => '&#8361;',
						'KWD' => '&#x62f;.&#x643;',
						'KYD' => '$',
						'KZT' => 'KZT',
						'LAK' => '&#8365;',
						'LBP' => '&#x644;.&#x644;',
						'LKR' => '&#xdbb;&#xdd4;',
						'LRD' => '$',
						'LSL' => 'L',
						'LYD' => '&#x644;.&#x62f;',
						'MAD' => '&#x62f;.&#x645;.',
						'MDL' => 'MDL',
						'MGA' => 'Ar',
						'MKD' => '&#x434;&#x435;&#x43d;',
						'MMK' => 'Ks',
						'MNT' => '&#x20ae;',
						'MOP' => 'P',
						'MRO' => 'UM',
						'MUR' => '&#x20a8;',
						'MVR' => '.&#x783;',
						'MWK' => 'MK',
						'MXN' => '$',
						'MYR' => '&#82;&#77;',
						'MZN' => 'MT',
						'NAD' => '$',
						'NGN' => '&#8358;',
						'NIO' => 'C$',
						'NOK' => '&#107;&#114;',
						'NPR' => '&#8360;',
						'NZD' => '$',
						'OMR' => '&#x631;.&#x639;.',
						'PAB' => 'B/.',
						'PEN' => 'S/.',
						'PGK' => 'K',
						'PHP' => '&#8369;',
						'PKR' => '&#8360;',
						'PLN' => '&#122;&#322;',
						'PRB' => '&#x440;.',
						'PYG' => '&#8370;',
						'QAR' => '&#x631;.&#x642;',
						'RMB' => '&yen;',
						'RON' => 'lei',
						'RSD' => '&#x434;&#x438;&#x43d;.',
						'RUB' => '&#8381;',
						'RWF' => 'Fr',
						'SAR' => '&#x631;.&#x633;',
						'SBD' => '$',
						'SCR' => '&#x20a8;',
						'SDG' => '&#x62c;.&#x633;.',
						'SEK' => '&#107;&#114;',
						'SGD' => '$',
						'SHP' => '&pound;',
						'SLL' => 'Le',
						'SOS' => 'Sh',
						'SRD' => '$',
						'SSP' => '&pound;',
						'STD' => 'Db',
						'SYP' => '&#x644;.&#x633;',
						'SZL' => 'L',
						'THB' => '&#3647;',
						'TJS' => '&#x405;&#x41c;',
						'TMT' => 'm',
						'TND' => '&#x62f;.&#x62a;',
						'TOP' => 'T$',
						'TRY' => '&#8378;',
						'TTD' => '$',
						'TWD' => '&#78;&#84;$',
						'TZS' => 'Sh',
						'UAH' => '&#8372;',
						'UGX' => 'UGX',
						'USD' => '$',
						'UYU' => '$',
						'UZS' => 'UZS',
						'VEF' => 'Bs F',
						'VND' => '&#8363;',
						'VUV' => 'Vt',
						'WST' => 'T',
						'XAF' => 'CFA',
						'XCD' => '$',
						'XOF' => 'CFA',
						'XPF' => 'Fr',
						'YER' => '&#xfdfc;',
						'ZAR' => '&#82;',
						'ZMW' => 'ZK',
					);
					$states = array();
					$states['CA'] = array(
						'AB' => __( 'Alberta', 'woocommerce' ),
						'BC' => __( 'British Columbia', 'woocommerce' ),
						'MB' => __( 'Manitoba', 'woocommerce' ),
						'NB' => __( 'New Brunswick', 'woocommerce' ),
						'NL' => __( 'Newfoundland', 'woocommerce' ),
						'NT' => __( 'Northwest Territories', 'woocommerce' ),
						'NS' => __( 'Nova Scotia', 'woocommerce' ),
						'NU' => __( 'Nunavut', 'woocommerce' ),
						'ON' => __( 'Ontario', 'woocommerce' ),
						'PE' => __( 'Prince Edward Island', 'woocommerce' ),
						'QC' => __( 'Quebec', 'woocommerce' ),
						'SK' => __( 'Saskatchewan', 'woocommerce' ),
						'YT' => __( 'Yukon', 'woocommerce' ),
					);

					$selected_locale = ( isset( $locales[$_POST['locale']] ) ) ? sanitize_text_field( $_POST['locale'] ) : 'US';
					$exploded = explode( '_', $selected_locale );
					if ( count( $exploded ) > 1 ) {
						$selected_locale = $exploded[0];
						$selected_state_locale = $exploded[1];
					}
					$selected_currency = ( isset( $currency_symbols[$_POST['currency']] ) ) ? sanitize_text_field( $_POST['currency'] ) : 'USD';
					update_option( 'ec_option_store_locale', $selected_locale );
					update_option( 'ec_option_base_currency', $selected_currency );
					update_option( 'ec_option_currency', $currency_symbols[$selected_currency] );
					if ( isset( $locales[$selected_locale] ) ) {
						if ( $locales[$selected_locale]['currency_pos'] == 'left' ) {
							update_option( 'ec_option_currency_symbol_location', 1 );
						} else {
							update_option( 'ec_option_currency_symbol_location', 0 );
						}
						update_option( 'ec_option_currency_decimal_symbol', $locales[$selected_locale]['decimal_sep'] );
						update_option( 'ec_option_currency_thousands_seperator', $locales[$selected_locale]['thousand_sep'] );
						update_option( 'ec_option_currency_decimal_places', $locales[$selected_locale]['num_decimals'] );
						update_option( 'ec_option_paypal_currency_code', $selected_currency );
						$paypal_locales = array( 'US', 'AU', 'AT', 'BE', 'BR', 'CA', 'CH', 'CN', 'DE', 'ES', 'GB', 'FR', 'IT', 'NL', 'PL', 'PT', 'RU' );
						if ( in_array( $selected_locale, $paypal_locales ) )
							update_option( 'ec_option_paypal_lc', $selected_locale );
						if ( $locales[$selected_locale]['weight_unit'] == 'kg' )
							update_option( 'ec_option_paypal_weight_unit', 'kgs');
						else
							update_option( 'ec_option_paypal_weight_unit', 'lbs' );

						global $wpdb;

						if ( isset( $_POST['sales_tax'] ) ) {
							if ( $selected_locale == 'US' ) {
								foreach ( $locales[$selected_locale]['tax_rates'] as $state_code => $tax_rates ) {
									if ( $selected_state_locale == $state_code ) {
										for( $i=0; $i<count( $tax_rates ); $i++ ) {
											$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_state, state_rate, state_code ) VALUES( 1, %s, %s )', $tax_rates[$i]['rate'], $tax_rates[$i]['state'] ) );
										}
									}
								}

							} else if ( $selected_locale == 'CA' ) {
								$canada_tax_rates = array();
								foreach ( $locales[$selected_locale]['tax_rates'] as $tax_rates ) {
									for( $i=0; $i<count( $tax_rates ); $i++ ) {
										if ( !isset( $canada_tax_rates['ec_option_collect_'.str_replace( ' ', '_', strtolower( $states['CA'][$tax_rates[$i]['state']] ) ).'_tax_shopper'] ) ) {
											$canada_tax_rates['ec_option_collect_'.str_replace( ' ', '_', strtolower( $states['CA'][$tax_rates[$i]['state']] ) ).'_tax_shopper'] = 1;
											$canada_tax_rates['ec_option_'.str_replace( ' ', '_', strtolower( $states['CA'][$tax_rates[$i]['state']] ) ).'_tax_shopper_gst'] = 0.000;
											$canada_tax_rates['ec_option_'.str_replace( ' ', '_', strtolower( $states['CA'][$tax_rates[$i]['state']] ) ).'_tax_shopper_hst'] = 0.000;
											$canada_tax_rates['ec_option_'.str_replace( ' ', '_', strtolower( $states['CA'][$tax_rates[$i]['state']] ) ).'_tax_shopper_pst'] = 0.000;
										}
										$canada_tax_rates['ec_option_'.str_replace( ' ', '_', strtolower( $states['CA'][$tax_rates[$i]['state']] ) ).'_tax_shopper_'.strtolower( $tax_rates[$i]['name'] )] = $tax_rates[$i]['rate'] / 100;
									}
								}

								if ( $selected_locale == 'CA' ) {
									update_option( 'ec_option_enable_easy_canada_tax', 1 );
									update_option( 'ec_option_canada_tax_options', $canada_tax_rates );
								}

							} else { // VAT or Other
								if ( $selected_locale == 'AU' ) {
									wp_easycart_language()->set_language_data( json_decode( html_entity_decode( str_replace( 'VAT', 'GST', json_encode( wp_easycart_language()->get_language_data() ) ) ) ) );
									wp_easycart_language()->update_language_data();
								}
								foreach ( $locales[$selected_locale]['tax_rates'] as $tax_rates ) {
									for( $i=0; $i<count( $tax_rates ); $i++ ) {
										if ( $check_row = $wpdb->get_row( 'SELECT taxrate_id FROM ec_taxrate WHERE tax_by_vat = 1' ) )
											$wpdb->query( $wpdb->prepare( 'UPDATE ec_taxrate SET vat_rate = %s WHERE taxrate_id = %d', $tax_rates[$i]['rate'], $check_row->taxrate_id ) );
										else
											$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_taxrate( tax_by_vat, vat_rate ) VALUES( 1, %s )', $tax_rates[$i]['rate'] ) );

										if ( $check_row = $wpdb->get_row( $wpdb->prepare( 'SELECT id_cnt FROM ec_country WHERE iso2_cnt = %s', $tax_rates[$i]['country'] ) ) )
											$wpdb->query( $wpdb->prepare( 'UPDATE ec_country SET vat_rate_cnt = %s WHERE id_cnt = %d', $tax_rates[$i]['rate'], $check_row->id_cnt ) );
										else
											$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_country( name_cnt, iso2_cnt, vat_rate_cnt ) VALUES( 1, %s )', $countries[$tax_rates[$i]['country']], $tax_rates[$i]['country'], $tax_rates[$i]['rate'] ) );
									}
								}
							}
						}
					}
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3' );
				}
			}
		}

		public function process_payments_submit() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( $_POST['ec_admin_form_action'] == 'process-wizard-payments' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-process-wizard-payments' ) ) {
					if ( isset( $_POST['manual_billing'] ) ) {
						update_option( 'ec_option_use_direct_deposit', 1 );
					} else {
						update_option( 'ec_option_use_direct_deposit', 0 );
					}

					if ( isset( $_POST['paypal_standard'] ) ) {
						update_option( 'ec_option_payment_third_party', 'paypal' );
						update_option( 'ec_option_paypal_email', sanitize_email( $_POST['paypal_email'] ) );
					}

					if ( isset( $_POST['use_stripe'] ) ) {
						update_option( 'ec_option_payment_process_method', 'stripe_connect' );
						update_option( 'ec_option_default_payment_type', 'credit_card' );
					}

					if ( isset( $_POST['use_square'] ) ) {
						update_option( 'ec_option_payment_process_method', 'square' );
						update_option( 'ec_option_default_payment_type', 'credit_card' );
					}

					if ( $_POST['bcc_email'] != '' ) {
						update_option( 'ec_option_bcc_email_addresses', sanitize_email( $_POST['bcc_email'] ) );
					}

					if ( $_POST['bcc_email'] != '' && isset( $_POST['subscribe_me'] ) ) {
						$customeremail = sanitize_email( $_POST['bcc_email'] );
						$customername = get_bloginfo( 'name' );
						$site_url = site_url();
						$site_url = str_replace( 'http://', '', $site_url );
						$site_url = str_replace( 'https://', '', $site_url );
						$site_url = str_replace( 'www.', '', $site_url );
						$request = new WP_Http;
						$response = $request->request( 
							sprintf( 'https://licensing.wpeasycart.com/licensing/activatetrial.php?customeremail=%s&customername=%s&siteurl=%s', urlencode( sanitize_email( $customeremail ) ), urlencode( sanitize_email( $customername ) ), urlencode( esc_url_raw( $site_url ) ) ), 
							array( 
								'method' => 'GET',
								'timeout' => 5
							)
						);
					}
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=4' );
				}
			}
		}

		public function process_shipping_submit() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( $_POST['ec_admin_form_action'] == 'process-wizard-shipping' ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-process-wizard-shipping' ) ) {
					$shipping_options = array(
						'static' => array(
							array(
								'shipping_label' => __( 'Standard Shipping 7-10 Days', 'wp-easycart' ),
								'shipping_order' => 1,
								'shipping_rate' => '7.99'
							),
							array(
								'shipping_label' => __( 'Priority 3 Day Shipping', 'wp-easycart' ),
								'shipping_order' => 2,
								'shipping_rate' => '14.99'
							),
							array(
								'shipping_label' => __( 'Priority 2 Day Shipping', 'wp-easycart' ),
								'shipping_order' => 3,
								'shipping_rate' => '19.99'
							)
						),
						'price' => array(
							array(
								'trigger_rate' => '0.00',
								'shipping_rate' => '7.99'
							),
							array(
								'trigger_rate' => '20.00',
								'shipping_rate' => '9.99'
							),
							array(
								'trigger_rate' => '50.00',
								'shipping_rate' => '12.99'
							),
							array(
								'trigger_rate' => '100.00',
								'shipping_rate' => '19.99'
							),
							array(
								'trigger_rate' => '500.00',
								'shipping_rate' => '29.99'
							),
						),
						'weight' => array(
							array(
								'trigger_rate' => '0.00',
								'shipping_rate' => '7.99'
							),
							array(
								'trigger_rate' => '20.00',
								'shipping_rate' => '9.99'
							),
							array(
								'trigger_rate' => '50.00',
								'shipping_rate' => '12.99'
							),
							array(
								'trigger_rate' => '100.00',
								'shipping_rate' => '19.99'
							),
							array(
								'trigger_rate' => '500.00',
								'shipping_rate' => '29.99'
							)
						)
					);
					global $wpdb;
					if ( $_POST['shipping_method'] == 'static' ) {
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_setting SET shipping_method = %s', 'method' ) );
						foreach ( $shipping_options['static'] as $rate ) {
							$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_shippingrate( is_method_based, shipping_label, shipping_rate, shipping_order ) VALUES( 1, %s, %s, %d )', $rate['shipping_label'], $rate['shipping_rate'], $rate['shipping_order'] ) );
						}

					} else if ( $_POST['shipping_method'] == 'price' ) {
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_setting SET shipping_method = %s', 'price' ) );
						foreach ( $shipping_options['price'] as $rate ) {
							$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_shippingrate( is_price_based, trigger_rate, shipping_rate ) VALUES( 1, %s, %s )', $rate['trigger_rate'], $rate['shipping_rate'] ) );
						}

					} else if ( $_POST['shipping_method'] == 'weight' ) {
						$wpdb->query( $wpdb->prepare( 'UPDATE ec_setting SET shipping_method = %s', 'weight' ) );
						foreach ( $shipping_options['weight'] as $rate ) {
							$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_shippingrate( is_weight_based, trigger_rate, shipping_rate ) VALUES( 1, %s, %s )', $rate['trigger_rate'], $rate['shipping_rate'] ) );
						}

					}
					update_option( 'ec_option_setup_wizard_done', 1 );
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=5' );
				}
			}
		}
	}
endif; // End if class_exists check

function wp_easycart_admin_setup_wizard() {
	return wp_easycart_admin_setup_wizard::instance();
}
wp_easycart_admin_setup_wizard();
