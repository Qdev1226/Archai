//Check if undefined, null, empty
export const gfpIsEmpty = ( data ) => {
	return 'undefined' === typeof data || null === data || '' === data;
};

export const gfpDateFormat = ( gfpdate, timeSeparator = ' ' ) => {
	let newDate = new Date( gfpdate );
	let localtime = newDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true }).toLowerCase();
    newDate = newDate.toDateString().split(' ');
	return newDate[1]+' '+newDate[2]+', '+newDate[3]+' '+timeSeparator+' '+localtime;
}

/**
 * Get array of column values of a object 
 * @param {object} obj 
 * @param {string} columnnName 
 * @returns array of column value
 */
export const gfpObjColumn = ( obj, columnnName ) => {
	if ( gfpIsEmpty(obj) ) {
		return [];
	}
	let columnArray = [];
	for (let key in obj ) {
		if ( obj.hasOwnProperty( key ) && ! gfpIsEmpty( obj[key][columnnName] ) ) {
			columnArray.push( obj[key][columnnName] );
		}
	}
    return columnArray;
}

//filter and get unique array
export const gfpUniqueArray = ( a ) =>  Array.isArray( a ) ? a.filter( (item, pos) => a.indexOf(item) === pos ) : a;

//sanitize string: allow onlt alphanumeric character
export const gfpSanitizeText = ( data ) => gfpIsEmpty( data ) ? data : data.replace(/[^a-z A-Z 0-9]+/gi, '');

//replace space with underscore and remove unwanted characters
export const gfpSanitizeName = ( data ) => {
	if ( gfpIsEmpty( data ) ) {
		return '';
	}
	data = data.toLowerCase().replace( / /g, '_' );
	/* \W is the equivalent of [^0-9a-zA-Z_] - it includes the underscore character */
	data = data.replace(/\W/g, '');
	return data;
}

export const gfpUcFirst = ( s ) => gfpIsEmpty(s) ? s : ( s[0].toUpperCase() + s.slice(1) );