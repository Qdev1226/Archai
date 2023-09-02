import { registerBlockType } from '@wordpress/blocks';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InnerBlocks } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';

registerBlockType( 'lez-library/listdd', {
	title: __( 'List Item Content', 'listicles' ),
	parent: ['lez-library/listitem'],
	icon: 'migrate',
	category: 'layout',
	description: __( 'A list item description (aka content).', 'listicles' ),

	edit: function (props) {
		const { className } = props;

		return (
			<Fragment>
				<dd className={className}>
					<InnerBlocks
						template={[
							[
								'core/paragraph',
								{ placeholder: 'List Content...' },
							],
						]}
						templateLock={false} // This doesn't work as described.
					/>
				</dd>
			</Fragment>
		);
	},

	save: function (props) {
		const {
			attributes: { className },
		} = props;
		return (
			<dd className={className}>
				<InnerBlocks.Content />
			</dd>
		);
	},
});
