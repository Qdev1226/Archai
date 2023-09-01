/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

 ( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );




	// Color preview

	wp.customize( 'header_img_textcolor', function( value ) {
		value.bind( function( to ) {
			$( '.bottom-header-title, .bottom-header-paragraph' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'website_background_color', function( value ) {
		value.bind( function( to ) {
			$( 'body, .site, .swidgets-wrap h3, .post-data-text' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'header_logo_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a, .site-description' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'header_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.sheader' ).css( {
				'background-color':to
			});
		} );
	} );

	wp.customize( 'navigation_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.main-navigation ul li a, .main-navigation ul li .sub-arrow, .super-menu .toggle-mobile-menu, .mobile-menu-active .smenu-hide' ).css( {
				'color':to
			});
		} );
	} );

	wp.customize( 'navigation_border_color', function( value ) {
		value.bind( function( to ) {
			$( '#smobile-menu.show .main-navigation ul ul.children.active, #smobile-menu.show .main-navigation ul ul.sub-menu.active, #smobile-menu.show .main-navigation ul li, .smenu-hide.toggle-mobile-menu.menu-toggle, #smobile-menu.show .main-navigation ul li, .primary-menu ul li ul.children li, .primary-menu ul li ul.sub-menu li' ).css( {
				'border-color':to
			});
		} );
	} );
	wp.customize( 'navigation_border_color', function( value ) {
		value.bind( function( to ) {
			$( '#smobile-menu.show .main-navigation ul ul.children.active, #smobile-menu.show .main-navigation ul ul.sub-menu.active, #smobile-menu.show .main-navigation ul li, .smenu-hide.toggle-mobile-menu.menu-toggle, #smobile-menu.show .main-navigation ul li, .primary-menu ul li ul.children li, .primary-menu ul li ul.sub-menu li' ).css( {
				'border-bottom-color':to
			});
		} );
	} );
	wp.customize( 'sidebar_headline_color', function( value ) {
		value.bind( function( to ) {
			$( '#secondary .widget h3, #secondary .widget h3 a, #secondary .widget h4, #secondary .widget h1, #secondary .widget h2, #secondary .widget h5, #secondary .widget h6' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'sidebar_link_color', function( value ) {
		value.bind( function( to ) {
			$( '#secondary .widget a, #secondary a, #secondary .widget li a , #secondary span.sub-arrow' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'sidebar_text_color', function( value ) {
		value.bind( function( to ) {
			$( '#secondary, #secondary .widget, #secondary .widget p, #secondary .widget li, .widget time.rpwe-time.published' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'sidebar_border_color', function( value ) {
		value.bind( function( to ) {
			$( '#secondary .swidgets-wrap, #secondary .widget ul li, .featured-sidebar .search-field' ).css( {
				'border-color':to
			});
		} );
	} );
	wp.customize( 'footer_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-info, .footer-column-three input.search-submit, .footer-column-three p, .footer-column-three li, .footer-column-three td, .footer-column-three th, .footer-column-three caption' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'footer_headline_color', function( value ) {
		value.bind( function( to ) {
			$( '.footer-column-three h3, .footer-column-three h4, .footer-column-three h5, .footer-column-three h6, .footer-column-three h1, .footer-column-three h2, .footer-column-three h4, .footer-column-three h3 a' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'footer_link_color', function( value ) {
		value.bind( function( to ) {
			$( '.footer-column-three a, .footer-column-three li a, .footer-column-three .widget a, .footer-column-three .sub-arrow' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'footer_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.footer-column-three h3:after' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'footer_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-info, .widget ul li, .footer-column-three input.search-field, .footer-column-three input.search-submit' ).css( {
				'border-color':to
			});
		} );
	} );
	wp.customize( 'footer_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer' ).css( {
				'background-color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_headline_color', function( value ) {
		value.bind( function( to ) {
			$( '.archive .page-header h1, .blogposts-list h2 a, .blogposts-list h2 a:hover, .blogposts-list h2 a:active, .search-results h1.page-title' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_byline_color', function( value ) {
		value.bind( function( to ) {
			$( '.blogposts-list .content-wrapper .post-data-text *' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.blogposts-list p' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_buttonbg_color', function( value ) {
		value.bind( function( to ) {
			$( '.page-numbers li a, .blogposts-list .blogpost-button, a.continuereading, .page-numbers.current, span.page-numbers.dots' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'blogfeed_buttontext_color', function( value ) {
		value.bind( function( to ) {
			$( '.page-numbers li a, .blogposts-list .blogpost-button, span.page-numbers.dots, .page-numbers.current, .page-numbers li a:hover, a.continuereading' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.archive .page-header h1, .search-results h1.page-title, .blogposts-list.fbox, span.page-numbers.dots, .page-numbers li a, .page-numbers.current' ).css( {
				'border-color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.blogposts-list .post-data-divider' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'postpage_headline_color', function( value ) {
		value.bind( function( to ) {
			$( '.page .comments-area .comment-author, .page .comments-area .comment-author a, .page .comments-area .comments-title, .page .content-area h1, .page .content-area h2, .page .content-area h3, .page .content-area h4, .page .content-area h5, .page .content-area h6, .page .content-area th, .single  .comments-area .comment-author, .single .comments-area .comment-author a, .single .comments-area .comments-title, .single .content-area h1, .single .content-area h2, .single .content-area h3, .single .content-area h4, .single .content-area h5, .single .content-area h6, .single .content-area th, .search-no-results h1, .error404 h1' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'postpage_byline_color', function( value ) {
		value.bind( function( to ) {
			$( '.single .post-data-text, .page .post-data-text, .page .post-data-text a, .single .post-data-text a, .comments-area .comment-meta .comment-metadata a, .single .post-data-text *' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'postpage_buttonbg_color', function( value ) {
		value.bind( function( to ) {
			$( '.comments-area p.form-submit input' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'postpage_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.page .comments-area, .page article.fbox, .page article tr, .page .comments-area ol.comment-list ol.children li, .page .comments-area ol.comment-list .comment, .single .comments-area, .single article.fbox, .single article tr, .comments-area ol.comment-list ol.children li, .comments-area ol.comment-list .comment, .error404 main#main, .error404 .search-form label, .search-no-results .search-form label, .error404 input.search-submit, .search-no-results input.search-submit, .error404 main#main, .search-no-results section.fbox.no-results.not-found' ).css( {
				'border-color':to
			});
		} );
	} );

	wp.customize( 'postpage_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.single .post-data-divider, .page .post-data-divider' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'postpage_buttontext_color', function( value ) {
		value.bind( function( to ) {
			$( '.single .comments-area p.form-submit input, .page .comments-area p.form-submit input' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'postpage_headline_color', function( value ) {
		value.bind( function( to ) {
			$( '.page .comments-area .comment-author, .page .comments-area .comment-author a, .page .comments-area .comments-title, .page .content-area h1, .page .content-area h2, .page .content-area h3, .page .content-area h4, .page .content-area h5, .page .content-area h6, .page .content-area th, .single  .comments-area .comment-author, .single .comments-area .comment-author a, .single .comments-area .comments-title, .single .content-area h1, .single .content-area h2, .single .content-area h3, .single .content-area h4, .single .content-area h5, .single .content-area h6, .single .content-area th, .search-no-results h1, .error404 h1' ).css( {
				'color':to
			});
		} );
	} );

	wp.customize( 'postpage_link_color', function( value ) {
		value.bind( function( to ) {
			$( '.single .entry-content a, .page .entry-content a, .comment-content a, .comments-area .reply a, .logged-in-as a, .comments-area .comment-respond a' ).css( {
				'color':to
			});
		} );
	} );


	wp.customize( 'postpage_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.page .content-area p, .page article, .page .content-area table, .page .content-area dd, .page .content-area dt, .page .content-area address, .page .content-area .entry-content, .page .content-area li, .page .content-area ol, .single .content-area p, .single article, .single .content-area table, .single .content-area dd, .single .content-area dt, .single .content-area address, .single .entry-content, .single .content-area li, .single .content-area ol, .search-no-results .page-content p' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'imagebanner_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.bottom-header-wrapper' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'imagebanner_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.bottom-header-wrapper *' ).css( {
				'color':to
			});
		} );
	} );

	wp.customize( 'upperwidgets_link_color', function( value ) {
		value.bind( function( to ) {
			$( '.header-widget a, .header-widget li a, .header-widget i.fa' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'upperwidgets_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.header-widget, .header-widget p, .header-widget li, .header-widget .textwidget' ).css( {
				'color':to
			});
		} );
	} );
	wp.customize( 'upperwidgets_title_color', function( value ) {
		value.bind( function( to ) {
			$( '.header-widget .widget-title, .header-widget h1, .header-widget h3, .header-widget h2, .header-widget h4, .header-widget h5, .header-widget h6' ).css( {
				'color':to
			});
		} );
	} );


		wp.customize( 'upperwidgets_border_color', function( value ) {
		value.bind( function( to ) {
			$( '.header-widget.swidgets-wrap, .header-widget ul li, .header-widget .search-field' ).css( {
				'border-color':to
			});
		} );
	} );
		wp.customize( 'upperwidgets_bg_color', function( value ) {
		value.bind( function( to ) {
			$( '.header-widgets-wrapper' ).css( {
				'background':to
			});
		} );
	} );

		wp.customize( 'navigation_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.primary-menu .pmenu, .super-menu, #smobile-menu, .primary-menu ul li ul.children, .primary-menu ul li ul.sub-menu, .top-nav-wrapper' ).css( {
				'background-color':to
			});
		} );
	} );

	wp.customize( 'sidebar_bg_color', function( value ) {
		value.bind( function( to ) {
			$( '#secondary .swidgets-wrap' ).css( {
				'background':to
			});
		} );
	} );
	wp.customize( 'sidebar_border_color', function( value ) {
		value.bind( function( to ) {
			$( '#secondary .swidget' ).css( {
				'border-color':to
			});
		} );
	} );
	wp.customize( 'blogfeed_bg_color', function( value ) {
		value.bind( function( to ) {
			$( '.archive article.fbox, .search-results article.fbox, .blog article.fbox' ).css( {
				'background':to
			});
		} );
	} );



		wp.customize( 'postpage_bg_color', function( value ) {
		value.bind( function( to ) {
			$( '.comments-area, .single article.fbox, .page article.fbox' ).css( {
				'background':to
			});
		} );
	} );





	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );
} )( jQuery );
