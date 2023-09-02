/**
 * Update the count by checking the number of child blocks.
 *
 * @param clientId
 */

import { select, dispatch } from '@wordpress/data';

export function updateListicleCount( clientId ) {
	const listicle = select('core/block-editor').getBlock( clientId );
	const items = select('core/block-editor').getBlockCount( clientId );
	const length = listicle?.innerBlocks?.length;

	if ( length > 0 && items !== length ) {
		dispatch('core/block-editor').updateBlockAttributes( clientId, {
			items: length,
		});
	}
}
