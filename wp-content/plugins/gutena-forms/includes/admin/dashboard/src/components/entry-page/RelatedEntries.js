import { __ } from '@wordpress/i18n';
import { PanelBody } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { lockIcon } from '../icon';
import { gfpIsEmpty } from '../../helper';

const RelatedEntries = (props) => {
    let related_entry = gutenaFormsEntryDetails?.entry_data?.related_entry;
    related_entry = gfpIsEmpty( related_entry ) ? __( 'No entries found', 'gutena-forms' ) : ( parseInt( related_entry ) + __( ' entries found', 'gutena-forms' ) );
    return(
        <PanelBody 
            icon={ lockIcon() }
            iconPosition='right'
            className='related-section '
            title={ __( 'Related Entries' ) } 
            initialOpen={ true }
        >
            <p>{ __( 'The user who created this entry also submitted the entries below', 'gutena-forms' ) }</p>
            <p>{ related_entry }</p>
        </PanelBody>
    );
}

export default RelatedEntries;