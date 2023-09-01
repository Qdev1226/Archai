window.addEventListener("load", function() {
	tabs();
});

function tabs() {
	function myTabClicks(tabClickEvent) {

		if ( tabClickEvent.target.classList.contains('tabs-item') || tabClickEvent.target.classList.contains('tabs') ) {
		
			tabClickEvent.preventDefault();
			document.querySelectorAll("li.tabs").forEach(tab => {
				tab.classList.remove('active');
			});
			tabClickEvent.target.parentNode.classList.add('active');
			tabClickEvent.preventDefault();
			var myContentPanes        = document.querySelectorAll(".tab-pane");
			myContentPanes.forEach(tabPane => {
				tabPane.classList.remove('active');
				tabPane.style.display = 'none';
			});
			
			var anchorReference = tabClickEvent.target;

			var activePaneId = anchorReference.getAttribute("href");
			var test         = activePaneId.slice(1);
			var activePane   = document.querySelector(`[data-tab="${test}"]`);
			activePane.classList.add("active");
			activePane.style.display = 'block';
		}
	}
			
	document.addEventListener('click', myTabClicks);
}
