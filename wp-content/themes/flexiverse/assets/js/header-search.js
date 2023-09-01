/**
 * Header Search Show/Hide js
 */
document.querySelectorAll('.search-controller').forEach((value) => {
	value.addEventListener( "click", function( e ){
		e.preventDefault();
		e.stopImmediatePropagation();
		console.log('search-main');
		const displayTarget = value.nextElementSibling;
		
		document.querySelectorAll('.social-controller').forEach((item) => {
			item.querySelector('.social').style.display = "block";
			item.querySelector('.cross').style.display  = "none";
			const itemTarget                            = item.nextElementSibling;
			itemTarget.style.display                    = "none";
		});
		
		document.querySelectorAll('.search-controller').forEach((item) => {
			if ( item !== value ) {
				item.querySelector('.search').style.display = "block";
				item.querySelector('.cross').style.display  = "none";
				const itemTarget                            = item.nextElementSibling;
				itemTarget.style.display                    = "none";
			}
		});
		
		if ( !displayTarget.style.display || displayTarget.style.display === "none" ) {
			displayTarget.style.display                  = "block";
			value.querySelector('.search').style.display = "none";
			value.querySelector('.cross').style.display  = "block";
		} else {
			displayTarget.style.display                  = "none";
			value.querySelector('.search').style.display = "block";
			value.querySelector('.cross').style.display  = "none";
		}
	});
});

document.addEventListener('click', function(e){   
	document.querySelectorAll('.search-controller').forEach((value) => {
		var displayTarget = value.nextElementSibling;
		
		if ( ! displayTarget.contains( e.target ) ) {
			displayTarget.style.display                  = "none";
			value.querySelector('.search').style.display = "block";
			value.querySelector('.cross').style.display  = "none";
		}
	});
});
