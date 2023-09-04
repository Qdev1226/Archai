<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'ec_address_form' ) ) :

	final class ec_address_form {

		protected static $_instance = null;
		private $cartpage;
		private $country_code;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() { }
		
		public function get_default() {
			return (object) array(
				'fields' => (object) array(
					'name' => array(
						(object) array(
							'field_key' => 'first_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_first_name' ),
						),
						(object) array(
							'field_key' => 'last_name',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_last_name' ),
						),
					),
					'company' => array(
						(object) array(
							'field_key' => 'company_name',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_company_name' ),
						),
					),
					'address' => array(
						(object) array(
							'field_key' => 'address',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address' ),
						),
					),
					'address2' => array(
						(object) array(
							'field_key' => 'address2',
							'required' => false,
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_address2' ),
						),
					),
					'address_info' => array( 
						(object) array(
							'field_key' => 'city',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_city' ),
						),
						(object) array(
							'field_key' => 'state',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_state' ),
						),
						(object) array(
							'field_key' => 'zip',
							'required' => true,
							'format' => '/.+/',
							'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_zip' ),
						),
					),
				),
			);
		}

		public function print_form( $cartpage, $prefix ) {
			$this->cartpage = $cartpage;
			$this->country_code = $this->cartpage->get_selected_country( ( ( 'ec_cart_shipping' == $prefix ) ? 'shipping' : 'billing' ) );

			$form = $this->get_default();
			if ( isset( $this->country_code ) && '' != $this->country_code && isset( $GLOBALS['ec_countries']->address_format[ $this->country_code ] ) ) {
				$form = $GLOBALS['ec_countries']->address_format[ $this->country_code ];
			}

			if ( get_option( 'ec_option_display_country_top' ) ) {
				$this->print_country_row( $prefix );
			}

			foreach ( $form->fields as $row_key => $row_fields ) {
				$this->print_row( $row_key, $row_fields, $prefix, ( 'hidden' == $row_key ) );
				if ( 'company' == $row_key ) {
					$this->print_vat_registration_row( $prefix );
				}
			}

			if ( ! get_option( 'ec_option_display_country_top' ) ) {
				$this->print_country_row( $prefix );
			}
			if ( get_option( 'ec_option_collect_user_phone' ) ) {
				$this->print_phone_row( $prefix );
			}
		}
		
		/* Form Row Functions */
		public function print_row( $row_key, $row_fields, $prefix, $is_hidden = false ) {
			if ( 'company' == $row_key && ! get_option( 'ec_option_enable_company_name' ) ) {
				return;
			}
			echo '<div class="ec_cart_input_row_flex"';
			if ( $is_hidden ) {
				echo ' style="display:none"';
			}
			echo '>';
			foreach ( $row_fields as $column_field ) {
				echo '<div class="ec_cart_input_column_' . esc_attr( count( $row_fields ) ) . '">';
				$this->print_field( $column_field, $prefix );
				echo '</div>';
			}
			echo '</div>';
		}

		public function print_country_row( $prefix ) {
			$this->print_row(
				'country',
				array(
					(object) array(
						'field_key' => 'country',
						'required' => true,
						'format' => '/^[A-Z][A-Z]$/',
						'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_country' )
					),
				),
				$prefix
			);
		}

		public function print_vat_registration_row( $prefix ) {
			$this->print_row(
				'vat_registration_number',
				array(
					(object) array(
						'field_key' => 'vat_registration_number',
						'required' => true,
						'format' => '/^[A-Z][A-Z]$/',
						'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_vat_registration_number' )
					)
				),
				$prefix
			);
		}

		public function print_phone_row( $prefix ) {
			$this->print_row(
				'phone',
				array(
					(object) array(
						'field_key' => 'phone',
						'required' => true,
						'format' => '/^[A-Z][A-Z]$/',
						'label' => wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_phone' )
					)
				),
				$prefix
			);
		}

		/* Print Column */
		public function print_field( $field, $prefix ) {
			$this->print_label( $field->field_key, $field, $prefix );
			$this->print_input_field( $field->field_key, $field, $prefix );
			$this->print_error( $field->field_key, $prefix );
		}

		public function print_label( $field_key, $field, $prefix ){
			$found_label = false;
			echo '<label for="' . $prefix . '_' . $field_key . '" id="' . $prefix . '_' . $field_key . '_label">';
			echo esc_attr( $field->label );
			echo '<span id="' . $prefix . '_' . $field_key . '_required"';
			if ( ! $is_field_required ) {
				'style="display:none"';
			}
			echo '>*</span>';
			echo '</label>';
		}

		public function print_input_field( $field_key, $field, $prefix ) {
			$billing_or_shipping = 'display_billing_input';
			if ( 'ec_cart_shipping' == $prefix ) {
				$billing_or_shipping = 'display_shipping_input';
			}
			if ( $field_key == 'country' ) {
				$this->cartpage->{$billing_or_shipping}( 'country' );
			} else if ( $field_key == 'state' ) {
				$this->cartpage->{$billing_or_shipping}( 'state' );
			} else if ( $field_key == 'vat_registration_number' ) {
				$this->cartpage->display_vat_registration_number_input();
			} else if ( $field_key == 'phone' ) {
				$this->cartpage->{$billing_or_shipping}( 'phone' );
			} else {
				$this->cartpage->{$billing_or_shipping}( $field_key );
			}
		}

		public function print_error( $field_key, $prefix ) {
			echo '<div class="ec_cart_error_row" id="' . $prefix . '_' . $field_key . '_error">';
				echo wp_easycart_language( )->get_text( 'cart_form_notices', 'cart_notice_please_enter_your' ) . ' ' . wp_easycart_language( )->get_text( 'cart_billing_information', 'cart_billing_information_state' );
			echo '</div>';
		}
	}

endif; // End if class_exists check

function ec_address_form() {
	return ec_address_form::instance();
}
