function bsbHandleShortcode(id) {
	var input = document.querySelector('#bsbFrontShortcode-' + id + ' input');
	var tooltip = document.querySelector('#bsbFrontShortcode-' + id + ' .tooltip');
	input.select();
	input.setSelectionRange(0, 30);
	document.execCommand('copy');
	tooltip.innerHTML = wp.i18n.__('Copied Successfully!', 'slider');
	setTimeout(() => {
		tooltip.innerHTML = wp.i18n.__('Copy To Clipboard', 'slider');
	}, 1500);
}