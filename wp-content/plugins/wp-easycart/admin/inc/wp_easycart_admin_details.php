<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class wp_easycart_admin_details {

	protected $wpdb;
	protected $id;
	protected $page;
	protected $subpage;
	protected $action;
	protected $form_action;
	protected $docs_link;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function print_fields( $field_list ) {
		foreach ( $field_list as $field ) {
			$this->print_field( $field );
		}
	}

	protected function print_field( $field ) {
		$this->{'print_' . $field['type'] . '_field'}( $field );
	}

	protected function print_heading_field( $column ) {
		if ( true == $column['horizontal_rule'] ) {
			echo '<br><hr>';
		}
		echo '<div id="ec_admin_row_heading_title" class="ec_admin_row_heading_title">';
		echo esc_attr( $column['label'] ) . '<br>';
		echo '</div>';
		echo '<div id="ec_admin_row_heading_message" class="ec_admin_row_heading_message"><p>';
		echo wp_easycart_escape_html( $column['message'] ) . '</p>';
		echo '</div>';
	}

	protected function print_image_preview_field( $column ) {
		global $wpdb;
		$product = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_product WHERE product_id = %d', (int) $column['value'] ) );
		if ( $product && $product->use_optionitem_images ) {
			$optionitem_images = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitemimage WHERE product_id = %d', $product->product_id ) );
			echo '<div class="wp-easycart-admin-product-details-images-locked">';
			$optionitem_images_option_id = $product->option_id_1;
			if ( $product->use_advanced_optionset ) {
				$advanced_option = $wpdb->get_row( $wpdb->prepare( "SELECT ec_option.*, ec_option_to_product.product_id, ec_option_to_product.option_to_product_id, ec_option_to_product.conditional_logic FROM ec_option_to_product, ec_option WHERE ec_option_to_product.product_id = %d AND ec_option.option_id = ec_option_to_product.option_id AND ( ec_option.option_type = 'combo' OR ec_option.option_type = 'swatch' OR ec_option.option_type = 'radio' ) ORDER BY ec_option_to_product.option_order ASC, ec_option.option_name ASC", $product->product_id ) );
				if ( $advanced_option ) {
					$optionitem_images_option_id = $advanced_option->option_id;
				}
			}
			$optionitems = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_optionitem WHERE option_id = %d ORDER BY optionitem_order ASC', $optionitem_images_option_id ) );
			foreach ( $optionitems as $optionitem ) {
				foreach ( $optionitem_images as $optionitem_image_set ) {
					if ( $optionitem_image_set->optionitem_id == $optionitem->optionitem_id ) {
						if ( '' != $optionitem_image_set->product_images ) {
							$product_images = explode( ',', $optionitem_image_set->product_images );
							if ( $product_images ) {
								foreach ( $product_images as $product_image ) {
									echo '<div class="wp-easycart-admin-product-details-image-locked">';
									echo '<span class="dashicons dashicons-lock"></span>';
									if ( 'http://' == substr( $product_image, 0, 7 ) || 'https://' == substr( $product_image, 0, 8 ) ) { // external
										echo '<img src="' . esc_attr( $product_image ) . '" />';

									} else if ( 'image1' == $product_image ){ // easycart folders pic 1
										echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image1, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image1, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image1 : plugins_url( '/wp-easycart-data/products/pics1/' . $optionitem_image_set->image1 ) ) . '" />';

									} else if( 'image2' == $product_image ){ // easycart folders pic 2
										echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image2, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image2, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image2 : plugins_url( '/wp-easycart-data/products/pics2/' . $optionitem_image_set->image2 ) ) . '" />';

									} else if( 'image3' == $product_image ){ // easycart folders pic 3
										echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image3, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image3, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image3 : plugins_url( '/wp-easycart-data/products/pics3/' . $optionitem_image_set->image3 ) ) . '" />';

									} else if( 'image4' == $product_image ){ // easycart folders pic 4
										echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image4, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image4, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image4 : plugins_url( '/wp-easycart-data/products/pics4/' . $optionitem_image_set->image4 ) ) . '" />';

									} else if( 'image5' == $product_image ){ // easycart folders pic 5
										echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image5, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image5, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image5 : plugins_url( '/wp-easycart-data/products/pics5/' . $optionitem_image_set->image5 ) ) . '" />';

									} else if( 'image:' == substr( $product_image, 0, 6 ) ) { // image url
										echo '<img src="' . esc_attr( substr( $product_image, 6, strlen( $product_image ) - 6 ) ) . '" />';

									} else if( 'video:' == substr( $product_image, 0, 6 ) ) { // video url
										// Video Icon

									} else if( 'youtube:' == substr( $product_image, 0, 8 ) ) { // youtube 
										// YouTube Icon

									} else if( 'vimeo:' == substr( $product_image, 0, 6 ) ) { // vimeo 
										// Vimeo Icon

									} else { // media
										$product_image_media = wp_get_attachment_image_src( $product_image, 'large' );
										if ( $product_image_media ) {
											echo '<img src="' . esc_attr( $product_image_media[0] ) . '" />';
										}
									}
									echo '</div>';
								}
							}
						} else {
							if ( '' != $optionitem_image_set->image1 ) {
								echo '<div class="wp-easycart-admin-product-details-image-locked">';
								echo '<span class="dashicons dashicons-lock"></span>';
								echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image1, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image1, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image1 : plugins_url( '/wp-easycart-data/products/pics1/' . $optionitem_image_set->image1 ) ) . '" />';
								echo '</div>';
							}
							if ( '' != $optionitem_image_set->image2 ) {
								echo '<div class="wp-easycart-admin-product-details-image-locked">';
								echo '<span class="dashicons dashicons-lock"></span>';
								echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image2, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image2, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image2 : plugins_url( '/wp-easycart-data/products/pics1/' . $optionitem_image_set->image2 ) ) . '" />';
								echo '</div>';
							}
							if ( '' != $optionitem_image_set->image3 ) {
								echo '<div class="wp-easycart-admin-product-details-image-locked">';
								echo '<span class="dashicons dashicons-lock"></span>';
								echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image3, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image3, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image3 : plugins_url( '/wp-easycart-data/products/pics1/' . $optionitem_image_set->image3 ) ) . '" />';
								echo '</div>';
							}
							if ( '' != $optionitem_image_set->image4 ) {
								echo '<div class="wp-easycart-admin-product-details-image-locked">';
								echo '<span class="dashicons dashicons-lock"></span>';
								echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image4, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image4, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image4 : plugins_url( '/wp-easycart-data/products/pics1/' . $optionitem_image_set->image4 ) ) . '" />';
								echo '</div>';
							}
							if ( '' != $optionitem_image_set->image5 ) {
								echo '<div class="wp-easycart-admin-product-details-image-locked">';
								echo '<span class="dashicons dashicons-lock"></span>';
								echo '<img src="' . esc_attr( ( substr( $optionitem_image_set->image5, 0, 7 ) == 'http://' || substr( $optionitem_image_set->image5, 0, 8 ) == 'https://' )  ? $optionitem_image_set->image5 : plugins_url( '/wp-easycart-data/products/pics1/' . $optionitem_image_set->image5 ) ) . '" />';
								echo '</div>';
							}
						}
					}
				}
			}
			echo '</div>';
		} else if( '' != $product->product_images ) {
			echo '<div class="wp-easycart-admin-product-details-images-locked">';
			$product_images = explode( ',', $product->product_images );
			if ( $product_images ) {
				foreach ( $product_images as $product_image ) {
					echo '<div class="wp-easycart-admin-product-details-image-locked">';
					echo '<span class="dashicons dashicons-lock"></span>';
					if ( 'http://' == substr( $product_image, 0, 7 ) || 'https://' == substr( $product_image, 0, 8 ) ) { // external
						echo '<img src="' . esc_attr( $product_image ) . '" />';

					} else if ( $product_image == 'image1' ){ // easycart folders pic 1
						echo '<img src="' . esc_attr( ( 'http://' == substr( $product->image1, 0, 7 ) || 'https://' == substr( $product->image1, 0, 8 ) )  ? $product->image1 : plugins_url( '/wp-easycart-data/products/pics1/' . $product->image1 ) ) . '" />';

					} else if( $product_image == 'image2' ){ // easycart folders pic 2
						echo '<img src="' . esc_attr( ( 'http://' == substr( $product->image2, 0, 7 ) || 'https://' == substr( $product->image2, 0, 8 ) )  ? $product->image2 : plugins_url( '/wp-easycart-data/products/pics2/' . $product->image2 ) ) . '" />';

					} else if( $product_image == 'image3' ){ // easycart folders pic 3
						echo '<img src="' . esc_attr( ( 'http://' == substr( $product->image3, 0, 7 ) || 'https://' == substr( $product->image3, 0, 8 ) )  ? $product->image3 : plugins_url( '/wp-easycart-data/products/pics3/' . $product->image3 ) ) . '" />';

					} else if( $product_image == 'image4' ){ // easycart folders pic 4
						echo '<img src="' . esc_attr( ( 'http://' == substr( $product->image4, 0, 7 ) || 'https://' == substr( $product->image4, 0, 8 ) )  ? $product->image4 : plugins_url( '/wp-easycart-data/products/pics4/' . $product->image4 ) ) . '" />';

					} else if( $product_image == 'image5' ){ // easycart folders pic 5
						echo '<img src="' . esc_attr( ( 'http://' == substr( $product->image5, 0, 7 ) || 'https://' == substr( $product->image5, 0, 8 ) )  ? $product->image5 : plugins_url( '/wp-easycart-data/products/pics5/' . $product->image5 ) ) . '" />';

					} else if( 'image:' == substr( $product_image, 0, 6 ) ) { // image url
						echo '<img src="' . esc_attr( substr( $product_image, 6, strlen( $product_image ) - 6 ) ) . '" />';

					} else if( 'video:' == substr( $product_image, 0, 6 ) ) { // video url
						// Video Icon

					} else if( 'youtube:' == substr( $product_image, 0, 8 ) ) { // youtube 
						// YouTube Icon

					} else if( 'vimeo:' == substr( $product_image, 0, 6 ) ) { // vimeo 
						// Vimeo Icon

					} else { // media
						$product_image_media = wp_get_attachment_image_src( $product_image, 'large' );
						if ( $product_image_media ) {
							echo '<img src="' . esc_attr( $product_image_media[0] ) . '" />';
						}
					}
					echo '</div>';
				}
			}
			echo '</div>';
		}
	}

	protected function print_hidden_field( $column ) {
		echo '<input type="hidden" name="' . esc_attr( $column['alt_name'] ) . '" id="' . esc_attr( $column['alt_name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_attr( $column['value'] );
		}
		echo '"';
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' />';
	}

	protected function print_wp_image_upload_field( $column ) {
		$button_label = __( 'Upload Image', 'wp-easycart' );
		if ( isset( $column['button_label'] ) ) {
			$button_label = $column['button_label'];
		}
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';
			} else {
				echo ' class="wpeasycart-admin-image-row"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';

		} else {
			echo ' class="wpeasycart-admin-image-row"';

		}
		echo '><div>' . esc_attr( $column['label'] ) . '</div>';
		echo '<input type="hidden" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" class="wpec-admin-upload-input" value="';
		if ( $this->id ) {
			echo (int) $column['value'];
		}
		echo '" />';
		echo '<input type="button" style="float:none;" class="wpec-admin-upload-button" value="' . esc_attr( $button_label ) . '" id="ec_upload_button_' . esc_attr( $column['name'] ) . '"';
		if ( isset( $column['image_action'] ) ) {
			echo ' onclick="' . esc_attr( $column['image_action'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		} else {
			echo ' onclick="ec_admin_image_upload_wp( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' />';
		if ( isset( $column['required'] ) && $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</div>';
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '_preview"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';
			} else {
				echo ' class="wpeasycart-admin-preview-row"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';

		} else {
			echo ' class="wpeasycart-admin-preview-row"';

		}
		echo '>';
		$img_url = wp_get_attachment_image_url( $column['value'] );

		if ( ! isset( $column['hide_preview'] ) ) {
			echo '<img src="' . esc_attr( $img_url ) . '" id="' . esc_attr( $column['name'] ) . '_preview" class="wpec-admin-upload-preview" />';
		}

		if ( ! isset( $column['show_delete'] ) || $column['show_delete'] ) {
			echo '<button class="ec_page_title_button ec_admin_delete_image';
			if ( '' == $column['value'] ) {
				echo ' ec_admin_hidden';
			}
			echo '" onclick="ec_admin_delete_image( \'' . esc_attr( $column['name'] ) . '\' ); return false;">';
			if ( isset( $column['delete_label'] ) ) {
				echo esc_attr( $column['delete_label'] );
			} else {
				echo esc_attr__( 'Delete Image', 'wp-easycart' );
			}
			echo '</button>';
		}
		echo '</div>';
	}

	protected function print_image_upload_field( $column ) {
		$button_label = __( 'Upload Image', 'wp-easycart' );
		if ( isset( $column['button_label'] ) ) {
			$button_label = $column['button_label'];
		}
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';
			} else {
				echo ' class="wpeasycart-admin-image-row"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-image-row"';

		} else {
			echo ' class="wpeasycart-admin-image-row"';

		}
		echo '>' . esc_attr( $column['label'] );
		echo '<input type="text" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" class="wpec-admin-upload-input" value="';
		if ( $this->id ) {
			echo esc_attr( ( isset( $column['value'] ) ) ? htmlentities( $column['value'] ) : '' );
		}
		echo '"';
		if ( isset( $column['maxlength'] ) ) {
			echo ' maxlength="' . esc_attr( $column['maxlength'] ) . '"';
		}
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( isset( $column['required'] ) && $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' />';
		echo '<input type="button" class="wpec-admin-upload-button" value="' . esc_attr( $button_label ) . '" id="ec_upload_button_' . esc_attr( $column['name'] ) . '"';
		if ( isset( $column['image_action'] ) ) {
			echo ' onclick="' . esc_attr( $column['image_action'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		} else {
			echo ' onclick="ec_admin_image_upload( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' />';
		if ( isset( $column['required'] ) && $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</div>';
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '_preview"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';
			} else {
				echo ' class="wpeasycart-admin-preview-row"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden wpeasycart-admin-preview-row"';

		} else {
			echo ' class="wpeasycart-admin-preview-row"';

		}
		echo '>';
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $column['value'] ) && ! is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/pics1/' . $column['value'] ) ) {
			$img_url = plugins_url( 'wp-easycart-data/products/pics1/' . $column['value'], EC_PLUGIN_DATA_DIRECTORY );

		} else {
			$img_url = $column['value'];
		}

		if ( ! isset( $column['hide_preview'] ) ) {
			echo '<img src="' . esc_attr( $img_url ) . '" id="' . esc_attr( $column['name'] ) . '_preview" class="wpec-admin-upload-preview" />';
		}

		if ( ! isset( $column['show_delete'] ) || $column['show_delete'] ) {
			echo '<button class="ec_page_title_button ec_admin_delete_image';
			if ( '' == $column['value'] ) {
				echo ' ec_admin_hidden';
			}
			echo '" onclick="ec_admin_delete_image( \'' . esc_attr( $column['name'] ) . '\' ); return false;">';
			if ( isset( $column['delete_label'] ) ) {
				echo esc_attr( $column['delete_label'] );
			} else {
				echo esc_attr__( 'Delete Image', 'wp-easycart' );
			}
			echo '</button>';
		}
		echo '</div>';
	}

	protected function print_password_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{ $column['requires']['name'] }, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{ $column['requires']['name'] } != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>' . esc_attr( $column['label'] );
		echo '<input type="password" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_attr( $column['value'] );
		}
		echo '"';
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) .'\');"';
		}
		echo ' />';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</div>';
	}

	protected function print_text_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['visible'] ) && false == $column['visible'] ) {
			echo ' style="display:none;"';
		}
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>' . esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		echo '<input type="text" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_textarea( wp_unslash( ( ( isset( $column['value'] ) ) ? $column['value'] : '' ) ) );
		} else if ( isset( $column['default'] ) ) {
			echo esc_textarea( wp_unslash( $column['default'] ) );
		}
		echo '"';
		if ( isset( $column['maxlength'] ) ) {
			echo ' maxlength="' . esc_attr( $column['maxlength'] ) . '"';
		}
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' readonly ';
		}
		if ( isset( $column['placeholder'] ) ) {
			echo ' placeholder="' . esc_attr( $column['placeholder'] ) . '"';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-easycart-admin-field ';
			echo ( isset( $column['read-only'] ) && $column['read-only'] ) ? 'wpec-admin-readonly' : '';
			echo '" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		} else if ( isset( $column['validation_type'] ) && isset( $column['message'] ) ) {
			echo ' class="wpep-validate-only wp-easycart-admin-field ';
			echo ( isset( $column['read-only'] ) && $column['read-only'] ) ? 'wpec-admin-readonly' : '';
			echo '" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		} else {
			echo ' class="wp-easycart-admin-field ';
			echo ( isset( $column['read-only'] ) && $column['read-only'] ) ? 'wpec-admin-readonly' : '';
			echo '"';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( isset( $column['onkeyup'] ) && $column['onkeyup'] ) {
			echo ' onkeyup="' . esc_attr( $column['onkeyup'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( isset( $column['onclick'] ) && $column['onclick'] ) {
			echo 'onclick="return ' . esc_attr( $column['onclick'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' />';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		} else if ( isset( $column['validation_type'] ) && isset( $column['message'] ) ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset></div></div></div>';
	}

	protected function print_color_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>' . esc_attr( $column['label'] );
		echo '<div class="ec_admin_color_holder"><input type="color" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_attr( $column['value'] );
		} else if ( isset( $column['default'] ) ) {
			echo esc_attr( $column['default'] );
		}
		echo '"';
		if ( isset( $column['maxlength'] ) ) {
			echo ' maxlength="' . esc_attr( $column['maxlength'] ) . '"';
		}
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( $column['required'] ) {
			echo ' class="ec_color_block_input wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		} else if ( isset( $column['validation_type'] ) && isset( $column['message'] ) ) {
			echo ' class="ec_color_block_input wpep-validate-only" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		} else {
			echo ' class="ec_color_block_input"';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( isset( $column['onkeyup'] ) && $column['onkeyup'] ) {
			echo ' onkeyup="' . esc_attr( $column['onkeyup'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( isset( $column['onclick'] ) && $column['onclick'] ) {
			echo 'onclick="return ' . esc_attr( $column['onclick'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' /></div>';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		} else if ( isset( $column['validation_type'] ) && isset( $column['message'] ) ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</div>';
	}

	protected function print_select_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>';
		if ( isset( $column['onchange'] ) && ( 'show_pro_required' == $column['onchange'] || 'show_pro_required_optionitem_images' == $column['onchange'] || 'show_pro_required_advanced_options' == $column['onchange'] ) ) {
			echo '<span class="dashicons dashicons-lock" style="color:#FC0; margin-top:-3px;"></span>';
		}
		echo esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		echo '<select name="' . esc_attr( $column['name'] ) . ( ( isset( $column['multiple'] ) && $column['multiple'] ) ? '[]' : '' ) . '" id="' . esc_attr( $column['name'] ) . '"';
		$select_classes = array();
		if ( isset( $column['select2'] ) ) {
			$select_classes[] = 'select2-' . $column['select2'];
		}
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			$select_classes[] = 'wpec-admin-readonly';
			echo 'disabled="true" ';
		}
		if ( isset( $column['multiple'] ) && $column['multiple'] ) {
			echo 'multiple="multiple" ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( $column['required'] ) {
			$select_classes[] = 'wpep-required';
			echo ' wpec-admin-validation-type="' . esc_attr( ( isset( $column['validation_type'] ) ) ? $column['validation_type'] : 'text' ) . '"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' class="';
		foreach ( $select_classes as $class ) {
			echo esc_attr( $class ) . ' ';
		}
		echo '"';
		echo '>';
		echo '<option value="' . ( ( isset( $column['default_value'] ) ) ? esc_attr( $column['default_value'] ) : '0' ) . '"';
		echo ( isset( $column['default_selected'] ) && $column['default_selected'] ) ? ' selected="selected"' : '';
		echo '>' . esc_attr( ( isset( $column['data_label'] ) ) ? $column['data_label'] : $column['label'] ) . '</option>';
		foreach ( $column['data'] as $data_item ) {
			echo '<option value="' . esc_attr( htmlentities( $data_item->id ) ) . '"';
			if ( $this->id ) {
				if ( isset( $column['dependent'] ) && 'custom' == $column['dependent'] && isset( $data_item->selected ) ) {
					$selected = true;
					foreach ( $data_item->selected as $name => $value ) {
						if ( ( true === $value && $this->item->{$name} ) || ( false === $value && ! $this->item->{ $name } ) || ( ! is_bool( $value ) && $this->item->{ $name } != $value ) ) {
							$selected = false;
						}
					}
					if ( $selected ) {
						echo ' selected="selected"';
					}
				} else if ( $data_item->id == $column['value'] ) {
					echo ' selected="selected"';
				} else if ( isset( $column['multiple'] ) && $column['multiple'] && is_array( $column['value'] ) && in_array( $data_item->id, $column['value'] ) ) {
					echo ' selected="selected"';
				}
			}
			echo '>' . esc_attr( $data_item->value ) . '</option>';
		}
		echo '</select>';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset></div></div></div>';
	}

	protected function print_number_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>' . esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		if ( isset( $column['step'] ) ) {
			echo '<input type="number" step="' . esc_attr( $column['step'] ) . '" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		} else {
			echo '<input type="number" step=".01" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		}
		if ( $this->id ) {
			echo esc_attr( $column['value'] );
		} else if ( isset( $column['default'] ) ) {
			echo esc_attr( $column['default'] );
		}
		echo '"';
		if ( isset( $column['max'] ) ) {
			echo ' max="' . esc_attr( $column['max'] ) . '"';
		}
		if ( isset( $column['min'] ) ) {
			echo ' min="' . esc_attr( $column['min'] ) . '"';
		}
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		if ( isset( $column['styles'] ) ) {
			echo ' style="';
			foreach ( $column['styles'] as $style ) {
				echo esc_attr( $style[0] ) . ':' . esc_attr( $style[1] ) . ';';
			}
			echo '"';
		}
		echo ' />';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset></div></div></div>';
	}

	protected function print_currency_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>' . esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		$step = 1;
		$decimal_length = (int) $GLOBALS['currency']->get_decimal_length();
		for ( $i = 0; $i < $decimal_length; $i++ ) {
			$step = $step / 10;
		}
		echo '<input type="number" name="' . esc_attr( $column['name'] ) . '" step="' . esc_attr( $step ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_attr( number_format( (float) $column['value'], 2, '.', '' ) );
		} else if ( isset( $column['default'] ) ) {
			echo esc_attr( number_format( (float) $column['default'], 2, '.', '' ) );
		}
		echo '"';
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo ' />';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset></div></div></div>';
	}

	protected function print_textarea_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( isset( $column['visible'] ) && false == $column['visible'] ) { 
			echo ' style="display:none;"';
		}
		echo '>' . esc_attr( $column['label'] );
		echo '<textarea name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '"';
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		if ( isset( $column['height'] ) && $column['height'] ) {
			echo ' style=" height: ' . esc_attr( $column['height'] ) . 'px;" ';
		}
		echo '>';
		if ( $this->id ) {
			if ( isset( $column['validation_type'] ) && 'textarea_xml' == $column['validation_type'] ) {
				echo ( function_exists( 'esc_xml' ) ) ? esc_xml( wp_unslash( $column['value'] ) ) : esc_js( wp_unslash( $column['value'] ) );
			} else {
				echo wp_easycart_escape_html( wp_unslash( $column['value'] ) );
			}
		}
		echo '</textarea>';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</div>';
	}

	protected function print_wp_textarea_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>' . esc_attr( $column['label'] ) . '</div>';
		$editor_settings = array(
			'wpautop' => true,
			'default_editor' => 'TinyMCE',
		);
		if ( $column['required'] ) {
			$editor_settings['editor_class'] = 'wpep-wp-editor-required tinymce_enabled';
		}
		$editor_value = '';
		if ( $this->id ) {
			$editor_value = ( isset( $column['value'] ) ) ? str_replace( ']]>', ']]&gt;', stripslashes( $column['value'] ) ) : '';
		}
		wp_editor( $editor_value, $column['name'], $editor_settings );
		if ( isset( $column['validation'] ) ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
	}
	protected function print_toggle_field( $column ) {
		wp_easycart_admin()->load_toggle_group( $column['name'], $column['onclick'], $column['value'], $column['label'], $column['description'] );
	}
	protected function print_checkbox_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';
		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';
		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';
		}
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group">';
		echo '<label for="' . esc_attr( $column['name'] ) . '">';
		if ( isset( $column['onclick'] ) && ( 'show_pro_required' == $column['onclick'] || 'show_pro_required_optionitem_images' == $column['onclick'] || 'show_pro_required_advanced_options' == $column['onclick'] ) ) {
			echo '<span class="dashicons dashicons-lock" style="color:#FC0; margin-top:-3px;"></span>';
		}
		echo esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		echo '<input type="checkbox" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="1"';
		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( $this->id && $column['value'] ) {
			echo ' checked="checked"';
		} else if ( ! $this->id && isset( $column['selected'] ) && true == $column['selected'] ) {
			echo ' checked="checked"';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		if ( isset( $column['onclick'] ) && $column['onclick'] ) {
			echo 'onclick="return ' . esc_attr( $column['onclick'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		echo ' /> ';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '<div class="wp-easycart-admin-onoffswitch wp-easycart-admin-pull-right" aria-hidden="true">';
		echo '<div class="wp-easycart-admin-onoffswitch-label">';
		echo '<div class="wp-easycart-admin-onoffswitch-inner"></div>';
		echo '<div class="wp-easycart-admin-onoffswitch-switch">';
		echo '<div class="wp-easycart-admin-dual-ring wp_easycart_toggle_saving" style="display: none;"></div>';
		echo '<div class="dashicons-before dashicons-yes-alt wp_easycart_toggle_saved" style="display: none;"></div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</fieldset></div></div></div>';
	}
	protected function print_popup_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>' . esc_attr( $column['label'] ) . '<input type="submit" value="' . esc_attr( $column['button'] ) . '" class="ec_admin_settings_simple_button" onclick="return ' . esc_attr( $column['click'] ) . '( );"></div>';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
	}

	protected function print_date_field( $column ) {
		if ( '' == $column['value'] ) {
			$print_date = '';
		} else if ( is_numeric( $column['value'] ) && (int) $column['value'] == $column['value'] ) {
			$print_date = date( 'Y-m-d', $column['value'] );
		} else if ( ! is_numeric( $column['value'] ) || (int) $column['value'] != $column['value'] ) {
			$print_date = date( 'Y-m-d', strtotime( $column['value'] ) );
		}

		echo '<script>jQuery(document).ready(function(){jQuery(".wp-ec-datepicker").datepicker();	});</script>';
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{ $column['requires']['name'] }, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{ $column['requires']['name'] } != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';
		} else if ( ! $this->id && isset( $column['requires'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';
		}
		echo '>';
		echo '<div class="wp_easycart_admin_no_padding">';
		echo '<div class="wp-easycart-admin-toggle-group-text">';
		echo '<label>' . esc_attr( $column['label'] ) . '</label>';
		echo '<fieldset class="wp-easycart-admin-field-container">';
		echo '<input type="text" autocomplete="off" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_attr( $print_date );
		}
		echo '"';

		if ( isset( $column['min'] ) ) {
			echo ' min="' . esc_attr( $column['min'] ) . '" ';
		}
		if ( isset( $column['max'] ) ) {
			echo ' max="' . esc_attr( $column['max'] ) . '" ';
		}

		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required wp-ec-datepicker" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		} else {
			echo ' class="wp-ec-datepicker" ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		echo ' />';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</fieldset></div></div></div>';
	}

	protected function print_star_rating_field( $column ) {
		echo '<div id="ec_admin_row_' . esc_attr( $column['name'] ) . '"';
		if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ! isset( $column['requires']['name'] ) ) {
			$hide = false;
			$requires_count = count( $column['requires'] );
			for ( $i = 0; $i < $requires_count; $i++ ) {
				if ( $this->item->{$column['requires'][ $i ]['name']} != $column['requires'][ $i ]['value'] ) {
					$hide = true;
				}
			}
			if ( $hide ) {
				echo ' class="ec_admin_hidden"';
			}
		} else if ( $this->id && isset( $column['requires'] ) && isset( $this->item ) && ( ( is_array( $column['requires']['value'] ) && ! in_array( $this->item->{$column['requires']['name']}, $column['requires']['value'] ) ) || ( ! is_array( $column['requires']['value'] ) && $this->item->{$column['requires']['name']} != $column['requires']['value'] ) ) ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && is_array( $column['requires'] ) && isset( $column['requires'][0] ) && isset( $column['requires'][0]['default_show'] ) && false == $column['requires'][0]['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		} else if ( ! $this->id && isset( $column['requires'] ) && isset( $column['requires']['default_show'] ) && false == $column['requires']['default_show'] ) {
			echo ' class="ec_admin_hidden"';

		}
		echo '>' . esc_attr( $column['label'] );

		echo '<div class="ec_admin_rating_bar">';
		$this->display_review_stars( $column['value'] );
		echo '</div>';

		echo '<input type="number" step="' . esc_attr( $column['step'] ) . '" name="' . esc_attr( $column['name'] ) . '" id="' . esc_attr( $column['name'] ) . '" value="';
		if ( $this->id ) {
			echo esc_attr( $column['value'] );
		}
		echo '"';
		if ( $column['min'] ) {
			echo ' min="' . esc_attr( $column['min'] ) . '" ';
		}
		if ( $column['max'] ) {
			echo ' max="' . esc_attr( $column['max'] ) . '" ';
		}

		if ( isset( $column['read-only'] ) && $column['read-only'] ) {
			echo ' class="wpec-admin-readonly" readonly ';
		}
		if ( isset( $column['show'] ) ) {
			echo ' onchange="ec_admin_show_hide_update( \'' . esc_attr( $column['name'] ) . '\', \'' . esc_attr( $column['show']['value'] ) . '\', \'ec_admin_row_' . esc_attr( $column['show']['name'] ) . '\' )"';
		}
		if ( isset( $column['onchange'] ) && $column['onchange'] ) {
			echo ' onchange="' . esc_attr( $column['onchange'] ) . '( \'' . esc_attr( $column['name'] ) . '\');"';
		}
		if ( $column['required'] ) {
			echo ' class="wpep-required" wpec-admin-validation-type="' . esc_attr( $column['validation_type'] ) . '"';
		}
		echo ' />';
		if ( $column['required'] ) {
			echo '<span id="' . esc_attr( $column['name'] ) . '_validation" class="ec_validation_error">' . wp_easycart_escape_html( $column['message'] ) . '</span>';
		}
		echo '</div>';
	}

	protected function print_custom_field(){}

	protected function get_item() {
		$sql = 'SELECT ';
		$first = true;
		foreach ( $this->columns as $column ) {
			if ( '' != $column['name'] ) {
				if ( ! isset( $column['dependent'] ) ) {
					if ( ! $first ) {
						$sql .= ', ';
					}
					$sql .= $column['name'];
					$first = false;
				}
			}
		}
		$sql .= ' FROM ' . $this->table . ' WHERE ' . $this->wpdb->prepare( $this->table . ' .' . $this->table_key . ' = %s', $this->id );
		$this->item = $this->wpdb->get_row( $sql );
	}

	protected function get_url( $param = false, $value = false, $reset_params = true, $alt_param = null, $alt_value = null ) {
		$uri_parts = ( isset( $_SERVER['REQUEST_URI'] ) ) ? explode( '?', sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 2 ) : false;
		if ( ! $uri_parts ) {
			return '';
		}
		$this->page_url = $uri_parts[0];
		$url = $this->page_url;
		if ( ! $reset_params ) {
			$url .= '?';
			foreach ( $this->query_params as $query_param ) {
				if ( 'orderby' == $param && 'pagenum' == $query_param[0] ) {
					// Igrore pagenum only when resorting products.
				} else if ( isset( $query_param[0] ) && isset( $query_param[1] ) && $query_param[0] != $param && ( ! $alt_param || $query_param[0] != $alt_param ) ) {
					$url .= '&' . $query_param[0] . '=' . $query_param[1];
				}
			}
			$url .= '&' . $param . '=' . $value;
			if ( $alt_param ) {
				$url .= '&' . $alt_param . '=' . $alt_value;
			}
		} else {
			$page = ( isset( $_GET['page'] ) ) ? sanitize_key( $_GET['page'] ) : '';
			$subpage = ( isset( $_GET['subpage'] ) ) ? sanitize_key( $_GET['subpage'] ) : '';
			$url .= '?page=' . $page;
			if ( isset( $_GET['subpage'] ) ) {
				$url .= '&subpage=' . $subpage;
			}
			if ( $param ) {
				$url .= '&' . $param . '=' . $value;
			}
			if ( $alt_param ) {
				$url .= '&' . $alt_param . '=' . $alt_value;
			}
		}
		return $url;
	}

	public function display_review_stars( $rating ) {
		for ( $i = 0; $i < $rating; $i++ ) {
			$this->display_star_on();
		}
		for ( $i = $rating; $i < 5; $i++ ) {
			$this->display_star_off();
		}
	}

	private function display_star_on() {
		echo '<div class="ec_admin_review_star_on"></div>';
	}

	private function display_star_off() {
		echo '<div class="ec_admin_review_star_off"></div>';
	}
}
