( function( $ ) {
	function wptouchCustomizerCloseButton() {
		$( '.wp-full-overlay #customize-header-actions' ).unbind().on( 'click', 'a.customize-controls-close', function( e ) {
			e.preventDefault();
			window.location = WPtouchCustomizer.settings_url;
		} );
	}

	function wptouchCustomizerDeviceToggles() {
		html = '<div class="toggle-wrapper">';
//	html += '<i class="customize-tooltip" title="Click to change orientation"></i>';
		html += '<div class="toggle-inner portrait">';
		html += '<span>' + WPtouchCustomizer.device_orientation + ':</span>';
		html += '<i class="icon-mobile toggle active"></i>';
		if ( WPtouchCustomizer.device_tags.indexOf( 'tablet' ) > - 1 ) {
			html += '<i class="icon-tablet toggle"></i>';
		}
		html += '</div>';
		html += '</div>';
	}

	function wptouchCustomizerWindowMods() {

		// New free ad
//	if ( WPtouchCustomizer.wptouch_is_pro == 'no' ) {
//		html += '<div id="wptouch-pro-notice">Upgrade to WPtouch Pro and save $10 with coupon code <span>TOUCHUP10</span><a class="button button-primary" href="http://www.wptouch.com/go-pro/?utm_source=free_admin&utm_medium=website&utm_term=customizer&utm_campaign=customizer" target="_blank">Go Pro Today</a></div>';
//	}

		$( '.wp-full-overlay' ).append( html );

		$( '.toggle-inner' ).on( 'click', 'i', function( e ) {

			var previewDiv = $( '#customize-preview' );
			var innerDiv = $( '.toggle-inner' );

			// Active device
			if ( ! $( this ).hasClass( 'active' ) ) {
				$( '.toggle-inner i' ).removeClass( 'active' );
				$( this ).addClass( 'active' );
				if ( $( this ).hasClass( 'icon-tablet' ) ) {
					previewDiv.addClass( 'tablet' );
				} else {
					previewDiv.removeClass( 'tablet' );
				}
				if ( $( this ).hasClass( 'icon-mobile' ) ) {
					previewDiv.removeClass( 'tablet' );
				} else {
					previewDiv.addClass( 'tablet' );
				}
				// Cleanup orientation on device switch
				innerDiv.removeClass( 'landscape' ).addClass( 'portrait' );
				previewDiv.removeClass( 'landscape' );
				// Don't do the orientation change on active switch
				return;
			}
			// Handle Orientation
			if ( innerDiv.hasClass( 'portrait' ) ) {
				innerDiv.removeClass( 'portrait' ).addClass( 'landscape' );
				previewDiv.addClass( 'landscape' );
			} else {
				innerDiv.removeClass( 'landscape' ).addClass( 'portrait' );
				previewDiv.removeClass( 'landscape' );
			}

			e.preventDefault();

		} );
	}

	function wptouchCustomizerAddRangeValue() {
		$( 'input[type="range"]', '#customize-theme-controls' ).each( function() {
			$( this ).after( '<span class="rangeval"></span>' );
			$( this ).on( 'click mousemove', function() {
				rangeValue = $( this ).val();
				$( this ).next( 'span' ).text( rangeValue );
			} ).click();
		} );
	}

	function wptouchCustomizerGetLuma( hexvalue ) {
		var c = hexvalue.substring( 1 );      // strip #
		var rgb = parseInt( c, 16 );   		// convert rrggbb to decimal
		var r = ( rgb >> 16
		        ) & 0xff;  		// extract red
		var g = ( rgb >> 8
		        ) & 0xff;  		// extract green
		var b = ( rgb >> 0
		        ) & 0xff;  		// extract blue

		return 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709'
	}

	function wptouchCustomizerFoundationSettings() {

		// Sharing Links on/off
		var sharingCheckbox = $( '[id$=show_share]' );
		sharingCheckbox.on( 'change wptouch.customizerReady', 'input', function() {
			if ( $( this ).is( ':checked' ) ) {
				sharingCheckbox.nextAll( 'li' ).css( 'visibility', '' );
			} else {
				sharingCheckbox.nextAll( 'li' ).css( 'visibility', 'hidden' );
			}
		} );

		// Featured Slider on/off
		var featuredCheckbox = $( '[id$=wptouch_featured_enabled]' );
		featuredCheckbox.on( 'change wptouch.customizerReady', 'input', function() {
			if ( $( this ).is( ':checked' ) ) {
				featuredCheckbox.nextAll( 'li' ).css( 'visibility', '' );
			} else {
				featuredCheckbox.nextAll( 'li' ).css( 'visibility', 'hidden' );
			}
		} );

		// Featured slider source select
		$( '[id$="featured_type"]' ).on( 'change wptouch.customizerReady', 'select', function() {
			var tagSetting = $( '[id$=featured_tag] *' );
			var catSetting = $( '[id$=featured_category] *' );
			var posttySetting = $( '[id$=featured_post_type] *' );
			var postSetting = $( '[id$=featured_post_ids] *' );

			switch ( $( this ).val() ) {
				case 'tag':
					tagSetting.show();
					catSetting.hide();
					posttySetting.hide();
					postSetting.hide();
					break;
				case 'category':
					tagSetting.hide();
					catSetting.show();
					posttySetting.hide();
					postSetting.hide();
					break;
				case 'post_type':
					tagSetting.hide();
					catSetting.hide();
					posttySetting.show();
					postSetting.hide();
					break;
				case 'posts':
					tagSetting.hide();
					catSetting.hide();
					posttySetting.hide();
					postSetting.show();
					break;
				case 'latest':
				default:
					tagSetting.hide();
					catSetting.hide();
					posttySetting.hide();
					postSetting.hide();
					break;
			}
		} );

		// Fire a change to deal with Customizer controlof .change()
		$( '#customize-theme-controls' ).find( 'input[type="checkbox"], select' ).trigger( 'wptouch.customizerReady' );

		// Live preview changes to custom css (Additional CSS) field.
		$( '.customize-control-code_editor textarea.code' ).on( 'change wptouch.customizerReady', function( e ) {
			$( '.customize-custom-css-styles' ).remove();
			var customCss = $( this ).val(),
				customCssContents = '<style class="customize-custom-css-styles" type="text/css">' + customCss + '</style>';
			$( '#customize-preview iframe' )
				.contents()
				.find( '.customize-custom-css-styles' )
				.remove()
				.end()
				.find( 'head' )
				.append(
					customCssContents
				);
		} );
	}

	function wptouchCustomizerChecklist() {
		$( '.customize-control-checklist input[type="checkbox"]' ).on( 'change', function() {
				checkbox_values = $( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
					function() {
						return this.value;
					}
				).get().join( ',' );
				$( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
			}
		);
	}

	function wptouchCustomizerAdminReady() {
		wptouchCustomizerDeviceToggles();
		if ( true == WPtouchCustomizer.mobile_preview ) {
			wptouchCustomizerCloseButton();
			wptouchCustomizerWindowMods();
			wptouchCustomizerAddRangeValue();
			wptouchCustomizerChecklist();
			wptouchCustomizerFoundationSettings();
		}
	}

	$( document ).ready( function() {
		wptouchCustomizerAdminReady();
	} );

}
)( jQuery );
