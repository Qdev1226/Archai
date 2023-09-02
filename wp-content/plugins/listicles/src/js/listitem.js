import { registerBlockType } from '@wordpress/blocks';
import { useEffect } from 'react';
import { updateListicleCount } from './components/update-count';

/**
 * WordPress dependencies
 */
import { __, _n } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import { select } from '@wordpress/data';

registerBlockType( 'lez-library/listitem', {
	title: __( 'List Item', 'listicles' ),
	parent: [ 'lez-library/listicles' ],
	icon: 'editor-rtl',
	category: 'layout',
	description: __( 'An individual list item.', 'listicles' ),

	edit: function( props ) {
		const { clientId } = props;

		useEffect(() => {
			const parentClientId = select( 'core/block-editor' ).getBlockHierarchyRootClientId( clientId );
			updateListicleCount( parentClientId );
			return () => updateListicleCount( parentClientId );
		}, [clientId]);

		return (
			<Fragment>
				<div className='listicles-innerblocks' >
 					<InnerBlocks
 					template={ [
 						[ 'lez-library/listdt' ],
 						[ 'lez-library/listdd' ],
 					] }
 					allowedBlocks={ [
 						[ 'lez-library/listdt' ],
						[ 'lez-library/listdd' ]
 					] }
 					templateLock={ 'all' }
					/>
				</div>
			</Fragment>
		);
	},

	save: function() {
		return (
			<InnerBlocks.Content />
		);
	},
} );
