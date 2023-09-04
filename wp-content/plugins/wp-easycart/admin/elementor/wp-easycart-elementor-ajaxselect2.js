/**
 * WP EasyCart Elementor Ajax Select2 Controler for  Elementor
 *
 * @package  Wp_Easycart_Elementor
 * @author   WP EasyCart
 */

jQuery( window ).on(
	'elementor:init',
	function($) {
		var WPECControlAjaxselect2ItemView = elementor.modules.controls.BaseData.extend(
			{
				onReady: function() {
					var self = this,
						el = self.ui.select,
						url = el.attr( 'data-ajax-url' );

					el.select2(
						{
							ajax: {
								url: url,
								dataType: 'json',
								data: function (params) {
									var query = {
										s: params.term,
									}
									return query;
								},
							},
							cache: true
						}
					);

					var ids = (typeof self.getControlValue() !== 'undefined') ? self.getControlValue() : '';
					if (ids.isArray) {
						ids = self.getControlValue().join();
					}

					jQuery.ajax(
						{
							url: url,
							dataType: 'json',
							data: {
								ids: String( ids ),
							}
						}
					).then(
						function( ret ) {
							if (ret !== null && ret.results.length > 0) {
								jQuery.each(
									ret.results,
									function( i, v ) {
										var op = new Option( v.text, v.id, true, true );
										el.append( op ).trigger( 'change' );
									}
								);
								el.trigger(
									{
										type: 'select2:select',
										params: {
											data: ret,
										}
									}
								);
							}
						}
					);
				},
				onBeforeDestroy: function onBeforeDestroy() {
					if ( this.ui.select.data( 'select2' ) ) {
						this.ui.select.select2( 'destroy' );
					}
					this.$el.remove();
				}
			}
		);
		elementor.addControlView( 'wpecajaxselect2', WPECControlAjaxselect2ItemView );
	}
);
