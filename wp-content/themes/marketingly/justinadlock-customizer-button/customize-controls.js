( function( api ) {

	// Extends our custom "marketingly" section.
	api.sectionConstructor['marketingly'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
