<?php

class WPtouchCacheSmash {
	var $cache_plugin_detected;
	var $cache_plugin_configured;

	public function __construct() {
		$this->cache_plugin_detected = false;
		$this->cache_plugin_configured = false;

		if ( $this->is_cache_plugin_detected() ) {
			// caching plugin detected
			$this->cache_plugin_detected = true;
			if ( $this->is_cache_configured() ) {
				$this->cache_plugin_configured = true;
			}
		}
	}

	private function is_w3_plugin_detected() {
		return (
			defined( 'W3TC' ) &&
			defined( 'W3TC_DIR' )
		);
	}

	private function is_wp_super_cache_detected() {
		return function_exists( 'wp_cache_set_home' );
	}

	private function is_wordfence_detected() {
		return defined( 'WORDFENCE_VERSION' );
	}

	public function is_wp_super_cache_broken() {
		/* This state should fire when:
			1) WP_CACHE is defined, which means WP Super Cache is loading via wp-config.php
			2) WP Super Cache plugin isn't loaded directly anymore
			3) WP Super Cache is active in settings AND WP Super Cache UAs aren't configured properly
		*/

		return (
			defined( 'WP_CACHE') &&
			WP_CACHE &&
			!function_exists( 'get_wpcachehome' ) &&
			$this->is_wp_super_cache_active() &&
			!$this->does_wp_super_cache_have_configured_uas()
		);
	}

	public function is_cache_plugin_detected() {
		return $this->is_w3_plugin_detected() || $this->is_wp_super_cache_detected() || $this->is_wordfence_detected();
	}

	private function find_in_array_no_case( $needle, $haystack_array ) {
		$found = false;

		if ( ! empty( $haystack_array ) ) {
			foreach( $haystack_array as $key => $value ) {
				$new_value = strtolower( $value );
				if ( $new_value == strtolower( $needle ) ) {
					$found = true;
				}
			}
		}

		return $found;
	}

	private function is_wp_super_cache_active() {
		global $cache_enabled;

		$super_cache_enabled = isset( $_POST[ 'wp_cache_easy_on' ] ) ? $_POST[ 'wp_cache_easy_on' ] : $cache_enabled;

		return $super_cache_enabled;
	}

	private function does_wp_super_cache_have_configured_uas() {
		$configured = true;

		global $cache_rejected_user_agent;

		$super_cache_rejected_ua = isset( $_POST[ 'wp_rejected_user_agent' ] ) ? explode( "\r\n", $_POST[ 'wp_rejected_user_agent' ] ) : $cache_rejected_user_agent;

		if ( !$this->find_in_array_no_case( 'iphone', $super_cache_rejected_ua ) ) {
			$configured = false;
		}

		return $configured;
	}

	public function is_cache_configured() {
		$cache_configured = true;

		if ( $this->is_wp_super_cache_detected() ) {
			return ( $this->is_wp_super_cache_active() && $this->does_wp_super_cache_have_configured_uas() ) || ( !$this->is_wp_super_cache_active() );
		}

		// Check W3
		if ( $this->is_w3_plugin_detected() ) {

			if( version_compare( W3TC_VERSION, '0.9.5', ">=" ) ) {
				if ( !function_exists( 'w3_instance' ) ) {
					require_once W3TC_DIR . '/inc/define.php';
				}

				$w3_config = w3_instance( 'W3_Config' );
			}
			else {
				$w3_config = new W3_Config( true );
			}

			if ( $w3_config ) {
				// Check to see if the Page Cache is enabled
				if ( $w3_config->get( 'pgcache.enabled' ) ) {
					// If it's enabled, we need to make sure the user agents have been updated
					$rejected_user_agents = $w3_config->get( 'pgcache.reject.ua' );

					if ( !$this->find_in_array_no_case( 'iphone', $rejected_user_agents ) ) {
						$cache_configured = false;
					}

					if ( !$cache_configured && ( $user_groups = $w3_config->get_array( 'mobile.rgroups' ) ) && is_array( $user_groups ) && count( (array) $user_groups ) > 0 ) {
						foreach ( $user_groups as $group ) {
							if ( $group[ 'enabled' ] && $this->find_in_array_no_case( 'iphone', $group[ 'agents' ] ) && $group[ 'theme' ] == '' && $group[ 'redirect' ] == '' ) {
								$cache_configured = true;

								$rejected_cookies = $w3_config->get( 'pgcache.reject.cookie' );
								if ( !$this->find_in_array_no_case( 'wptouch-pro-view', $rejected_cookies ) ) {
									add_filter( 'wptouch_show_mobile_switch_link', '__return_false' );
								}
							}
						}
					}
				}
			}

			return $cache_configured;
		}

		if ( $this->is_wordfence_detected() ) {
			$cache_type = wfConfig::get( 'cacheType', false );
			if ( !$cache_type || $cache_type == "disabled" ) {
				$cache_configured = true;
			} else {
				$cookie_set = false;
				$user_agents_set = false;

				$exclusions = wfConfig::get( 'cacheExclusions', false );
				if( $exclusions ) {
					$exclusions = unserialize( $exclusions );
					if ( $exclusions ) {
						foreach( $exclusions as $exclusion ) {
							if ( $exclusion[ 'pt' ] == 'cc' && $exclusion[ 'p' ] = 'wptouch_switch_toggle' ) {
								$cookie_set = true;
							} else if ( $exclusion[ 'pt'] == 'uac' && strtolower( $exclusion[ 'p' ] ) == 'iphone' ) {
								$user_agents_set = true;
							}

							if ( $cookie_set && $user_agents_set ) {
								break;
							}
						}
					}
				}

				$cache_configured = ( $cookie_set && $user_agents_set );
			}

			return $cache_configured;
		}

		return $cache_configured;
	}

	public function cache_plugin_name() {
		if ( $this->is_w3_plugin_detected() ) {
			return "W3 Total Cache";
		} else if ( $this->is_wp_super_cache_detected() ) {
			return "WP Super Cache";
		} else if ( $this->is_wordfence_detected() ) {
			return "WordFence";
		}
	}

	public function get_cache_support_url() {
		if ( $this->is_w3_plugin_detected() || $this->is_wp_super_cache_detected() ) {
			return "https://support.wptouch.com/support/solutions/articles/5000537668-configuring-cache-plugins-for-wptouch";
		} else if ( $this->is_wordfence_detected() ) {
			return "https://support.wptouch.com/support/solutions/articles/5000637442-configuring-wordfence-for-wptouch";
		}


	}

	public function should_disable_mobile_theme() {
		return $this->cache_plugin_detected && !$this->cache_plugin_configured;
	}
}
