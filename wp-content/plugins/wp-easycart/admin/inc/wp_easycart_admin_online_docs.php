<?php
class wp_easycart_admin_online_docs {

	public $admin_guide_url;
	public $admin_guide_section_url;
	public $installation_guide_url;
	public $extension_guide_url;

	public function __construct() {
		$this->admin_guide_url = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1';
		$this->admin_guide_section_url = 'http://docs.wpeasycart.com/wp-easycart-administrative-console-guide/?wpeasycartadmin=1&section=';
		$this->installation_guide_url = 'http://docs.wpeasycart.com/wp-easycart-installation-guide/?wpeasycartadmin=1&section=';
		$this->extension_guide_url = 'http://docs.wpeasycart.com/wp-easycart-extensions-guide/?wpeasycartadmin=1&section=';
	}

	public function print_docs_url( $section, $category, $panel ) {
		if ( $section == 'products' ) {
			if ( $category == 'products' ) {
				if ( $panel == "importer" ) {
					return $this->admin_guide_section_url . $category . '-importer';
				} else {
					return $this->admin_guide_section_url . $category;
				}
			} else if ( $category == 'inventory' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'option-sets' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'categories' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'menus' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'manufacturers' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'product-reviews' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'subscription-plans' ) {
				return $this->admin_guide_section_url . $category;
			} else {
				return $this->admin_guide_url;
			}
		}

		if ( $section == 'orders' ) {
			if ( $category == 'order-management' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'subscriptions' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'manage-downloads' ) {
				return $this->admin_guide_section_url . $category;
			} else {
				return $this->admin_guide_url;
			}
		}

		if ( $section == 'users' ) {
			if ( $category == 'user-accounts' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'user-roles' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'subscribers' ) {
				return $this->admin_guide_section_url . $category;
			} else {
				return $this->admin_guide_url;
			}
		}

		if ( $section == 'marketing' ) {
			if ( $category == 'coupons' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'gift-cards' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'promotions' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'abandoned-carts' ) {
				return $this->admin_guide_section_url . $category;
			} else {
				return $this->admin_guide_url;
			}
		}
		if ( $section == 'settings' ) {
			if ( $category == 'initial-setup' ) {
				////SETTINGS
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'product-settings' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'taxes' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'shipping-settings' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'shipping-rates' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'payment' ) {
				if ( 'amazonpay' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/amazon-pay/';
				} else if ( 'stripe' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/stripe-payment-settings/';
				} else if ( 'square' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/square-payment-settings/';
				} else if ( 'paypal' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/paypal-payment-settings/';
				} else if ( 'authorize' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/authorize-net-payment-settings/';
				} else if ( 'intuit' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/intuit-payment-settings/';
				} else if ( 'firstdata' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/first-data-payment-settings/';
				} else if ( 'worldpay' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/worldpay-payment-settings/';
				} else if ( 'payfast' == $panel ) {
					return 'https://docs.wpeasycart.com/docs/administrative-console-guide/payfast-payment-settings/';
				} else {
					return $this->admin_guide_section_url . $category;
				}
			} else if ( $category == 'checkout' ) {
				if( $panel == 'text-notifications' ) {
					return 'https://docs.wpeasycart.com/docs/non-knowledgebase/text-messaging-cloud-service/';
				}else{
					return $this->admin_guide_section_url . $category;
				}
			} else if ( $category == 'accounts' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'additional-settings' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'language-editor' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'design' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'email-setup' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'third-party' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'cart-importer' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'countries' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'states-territories' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'per-page-options' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'price-points' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'log-entries' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'store-status' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'registration' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'gift-cards' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'coupons' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'promotions' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'abandon-cart' ) {
				return $this->admin_guide_section_url . $category;
			} else if ( $category == 'fees' ) {
				return 'https://docs.wpeasycart.com/docs/administrative-console-guide/flex-fee-settings/';
			} else {
				return $this->admin_guide_url;
			}
		} else {
			return $this->admin_guide_url;
		}
	}

	public function print_vids_url( $section, $category, $panel) {
		$videoID = false;
		if ( $section == 'products' ) {
			if ( $category == 'products' ) {
				if ( $panel == "importer" ) {
					$videoID = 'ua50lCD4ROA';
				} else {
					$videoID = 'XZmXGI02i6Y';
				}
			} else if ( $category == 'option-sets' ) {
				 $videoID = 'T0dByKX67iY';
			} else if ( $category == 'categories' ) {
				 $videoID = 'rRHm0XvqXto';
			} else if ( $category == 'menus' ) {
			} else if ( $category == 'manufacturers' ) {
			} else if ( $category == 'product-reviews' ) {
			} else if ( $category == 'subscription-plans' ) {
				$videoID = 'w53tzohUv2k'; 
			}
		}
		if ( $section == 'orders' ) {
			if ( $category == 'order-management' ) {
				 $videoID = 'sAfCkLgtGQY'; 
			} else if ( $category == 'subscriptions' ) {
				 $videoID = 'w53tzohUv2k';  
			} else if ( $category == 'manage-downloads' ) {
				 $videoID = 'Vpp-lXFIndo';
			}
		}
		if ( $section == 'users' ) {
			if ( $category == 'user-accounts' ) {
				$videoID = 'w-4oLtHGGa4';
			} else if ( $category == 'user-roles' ) {
				 $videoID = ' h2THe0-Zt-M';
			} else if ( $category == 'subscribers' ) {
			}
		}
		if ( $section == 'marketing' ) {	
			if ( $category == 'coupons' ) {
				  $videoID = '9rR8CwEeqG4'; 
			} else if ( $category == 'gift-cards' ) {
				  $videoID = '2KzoV89GeTM'; 
			} else if ( $category == 'promotions' ) {
				  $videoID = 'r-6WaGDig_k'; 
			} else if ( $category == 'abandoned-carts' ) {
				 $videoID = 'UeqYbbCRdiQ'; 
			}
		}
		if ( $section == 'settings' ) {
			if ( $category == 'initial-setup' ) {
				if ( $panel == 'product-page' ) {
					 $videoID = 'Pc3bCSgR-xM';
				} else if ( $panel == 'account-page' ) {
					 $videoID = 'Pc3bCSgR-xM';
				} else if ( $panel == 'cart-page' ) {
					 $videoID = 'Pc3bCSgR-xM';
				} else if ( $panel == 'demo-data' ) {
				} else if ( $panel == 'currency' ) {
				} else if ( $panel == 'goals' ) {
				}
			} else if ( $category == 'product-settings' ) {
				if ( $panel == 'product-list' ) {

				} else if ( $panel == 'product-display' ) {
					  $videoID = 'Ai1Ie-IeLkg';
				} else if ( $panel == 'customer-review' ) {
				} else if ( $panel == 'product-details' ) {
				} else if ( $panel == 'price-display' ) {
				} else if ( $panel == 'inventory' ) {
				}
			} else if ( $category == 'taxes' ) {
				if ( $panel == 'tax-by-state-setup' ) {
					 $videoID = 'NXgU7tc1850';
				} else if ( $panel == 'vat-setup' ) {
					 $videoID = '8MAXc16HObg';
				} else if ( $panel == 'tax-by-country-setup' ) {
					$videoID = 'NXgU7tc1850';
				} else if ( $panel == 'global-tax-setup' ) {
					 $videoID = 'NXgU7tc1850';
				} else if ( $panel == 'duty-tax-setup' ) {
					 $videoID = 'NXgU7tc1850';
				} else if ( $panel == 'canada-tax-setup' ) {
					 $videoID = 'pRAMxO2XEl0';
				} else if ( $panel == 'tax-cloud-setup' ) {
					 $videoID = '36LEWLY6HE4'; 
				}
			} else if ( $category == 'shipping-settings' ) {
				if ( $panel == 'usps' ) {
					$videoID = 'MeHz8lazvcI';
				} else if ( $panel == 'ups' ) {
					$videoID = 'MeHz8lazvcI';
				} else if ( $panel == 'fedex' ) {
					$videoID = 'MeHz8lazvcI';
				} else if ( $panel == 'dhl' ) {
					$videoID = 'MeHz8lazvcI';
				} else if ( $panel == 'canada-post' ) {
					$videoID = 'MeHz8lazvcI';
				} else if ( $panel == 'australia-post' ) {
					$videoID = 'MeHz8lazvcI';
				}
			} else if ( $category == 'shipping-rates' ) {
				if ( $panel == 'shipping-method' ) {
					$videoID = 'Mx_z_ciKerw';
				} else if ( $panel == 'country-list' ) {
					$videoID = '8HoUdEqXWNM';
				} else if ( $panel == 'state-list' ) {
					$videoID = '8HoUdEqXWNM';
				} else if ( $panel == 'shipping-zones' ) {
					$videoID = '8HoUdEqXWNM';
				} else if ( $panel == 'shipping-basic-options' ) {
				}
			} else if ( $category == 'payment' ) {
				if ( $panel == 'bill-later' ) {
					 $videoID = 'WBnAke8lt-c';
				} else if ( $panel == 'paypal' ) {
					 $videoID = 'A1wDK3ujO70';
				} else if ( $panel == 'stripe' ) {
					 $videoID = 'GzrlmpSqzRU';
				} else if ( $panel == 'square' ) {
					 $videoID = '37Ci-Jz5BjM';
				}
			} else if ( $category == 'checkout' ) {
				if ( $panel == 'settings' ) {
				} else if ( $panel == 'form-settings' ) {
				} else if ( $panel == 'stock-control' ) {
				} else if ( $panel == 'text-notifications' ) {
				}
			} else if ( $category == 'accounts' ) {
				if ( $panel == 'settings' ) {
				}	
			} else if ( $category == 'additional-settings' ) {
				if ( $panel == 'search-options' ) {
				} else if ( $panel == 'additional-options' ) {
				}
			} else if ( $category == 'language-editor' ) {
				if ( $panel == 'current-language' ) {
					 $videoID = 'wiifQ2IhvNY';
				} else if ( $panel == 'installed-languages' ) {
					 $videoID = 'wiifQ2IhvNY'; 
				} 	
			} else if ( $category == 'design' ) {
				if ( $panel == 'cart' ) {
					 $videoID = 'e59LM9CcyCM';
				} else if ( $panel == 'custom-css' ) {
					 $videoID = 'e59LM9CcyCM';
				} else if ( $panel == 'colors' ) {
					 $videoID = 'e59LM9CcyCM';
				} else if ( $panel == 'templates' ) {
					 $videoID = 'e59LM9CcyCM';
				} else if ( $panel == 'product' ) {
					 $videoID = 'e59LM9CcyCM';
				} else if ( $panel == 'product-details' ) {
					 $videoID = 'e59LM9CcyCM';
				} else if ( $panel == 'settings' ) {
					 $videoID = 'e59LM9CcyCM';
				}
			} else if ( $category == 'email-setup' ) {
				if ( $panel == 'customer-email' ) {
					 $videoID = 'p96NBca16N0';
				} else if ( $panel == 'email-settings' ) {
					 $videoID = 'p96NBca16N0';
				} else if ( $panel == 'order-receipt-language' ) {
					 $videoID = 'p96NBca16N0';
				} else if ( $panel == 'order-receipt' ) {
					 $videoID = 'p96NBca16N0';
				} 	
			} else if ( $category == 'third-party' ) {
				if ( $panel == 'amazon' ) {
				} else if ( $panel == 'deconetwork' ) {
				} else if ( $panel == 'google adwords' ) {
				} else if ( $panel == 'google-analytics' ) {
				} else if ( $panel == 'google-merchant' ) {
				}
			} else if ( $category == 'cart-importer' ) {
				if ( $panel == 'woo' ) {
				} else if ( $panel == 'oscommerce' ) {
				}
			} else if ( $category == 'manage-countries' ) {
					$videoID = 'doRi9r5yOAY';
			} else if ( $category == 'manage-states' ) {
					$videoID = 'doRi9r5yOAY';
			} else if ( $category == 'manage-per-page' ) {
			} else if ( $category == 'manage-price-points' ) {
			} else if ( $category == 'logs' ) {
			} else if ( $category == 'store-status' ) {
			} else if ( $category == 'registration' ) {
				 if ( $panel == 'registration' ) {
					$videoID = 'r3Q4FJiUwWY';
				} else if ( $panel == 'none' ) {
					$videoID = 'r3Q4FJiUwWY';
				} else if ( $panel == 'expired' ) {
					$videoID = 'r3Q4FJiUwWY';
				}
			}
		}

		if ( $videoID != false ) {
			echo '<script>';
			echo '	var wp_easycart_help_player;';
			echo '	var tag = document.createElement( "script" );';
			echo '	tag.src = "https://www.youtube.com/iframe_api";';
			echo '	var firstScriptTag = document.getElementsByTagName( "script" )[0];';
			echo '	firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );';
			echo '	function onYouTubeIframeAPIReady() {';
			echo '		wp_easycart_help_player = new YT.Player( "wp_easycart_admin_help_video_player", {';
			echo '			width: "100%",';
			echo '			height: "450",';
			echo '			videoId: "' . esc_attr( $videoID ) . '"';
			echo '		});';
			echo '	}';
			echo '	jQuery( ".ec_admin_help_video_container > .ec_admin_upsell_popup_close > a" ).on( "click", function() {';
			echo '		wp_easycart_help_player.pauseVideo(); wp_easycart_admin_close_video_help(); return false;';
			echo '	} );';
			echo '</script>';
			echo ' <a href="https://www.youtube.com/watch?v=' . esc_attr( $videoID ) . '"  onclick="wp_easycart_admin_open_video_help(\'' . esc_attr( $videoID ) . '\' ); wp_easycart_help_player.playVideo(); return false;" class="ec_help_icon_link"><div class="dashicons-before ec_help_icon dashicons-format-video"></div> ' . esc_attr__( 'Video', 'wp-easycart' ) . '</a>';
		}
	}
}
