jQuery(document).ready(function($){
	console.log("loaded");
		$('.toggle-mobile-menu').click(function(e) {
			setTimeout(function(){
				e.preventDefault();  // don't grab focus
			if($('body').hasClass('mobile-menu-active') ) {
				$("#smobile-menu #primary-menu li a").first().focus();
				$( document ).off("keydown");
				$( document ).on( 'keydown', function ( e ) {
					if ( e.keyCode === 27 ) { 
						// escape to close popout menu
						backToMenu();
					}
				});
				
				$('#smobile-menu .smenu-hide').off("keydown");
				$('#smobile-menu .smenu-hide').on('keydown', function (e) {
					if((e.keyCode === 9 && e.shiftKey) || e.keyCode === 13) {
						   //shift tab or enter on "menu" close menu
						backToMenu();
					}
				});
			}
			},10);
		});

		$("#smobile-menu #primary-menu").append(
			'<li><a href="" id="accessibility-close-mobile-menu" style="padding:0;height:0;"></a></li>'
		);

		$("#accessibility-close-mobile-menu").focusin(function(e){
			$( document ).off("keydown");
			$('.toggle-mobile-menu').click();
			$('#primary a').first().focus();
		});
	
	
		function backToMenu(){
			$( document ).off("keydown");
			$('.toggle-mobile-menu').trigger("click");
			setTimeout(function(){
				$('.toggle-mobile-menu').focus();
			}, 10);
			$('.toggle-mobile-menu').focus();
		}
	

});
	