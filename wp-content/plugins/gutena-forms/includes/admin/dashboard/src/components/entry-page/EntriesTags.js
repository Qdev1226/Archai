import { __ } from '@wordpress/i18n';
import { PanelBody, FormTokenField } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { lockIcon } from '../icon';

const EntriesTags = ( props ) => {
    const [ selectedTags, setSelectedTags ] = useState( [ 
        __( 'Tag 1', 'gutena-forms' ), 
        __( 'Tag 2', 'gutena-forms' )
    ] );

    return(
        <PanelBody 
            icon={ lockIcon() }
            iconPosition='right'
            className='tags-section '
            title={ __( 'Tags' ) } 
            initialOpen={ true }
        >
            <FormTokenField
                label={ __( 'Add new tag', 'gutena-forms' ) }
                value={ selectedTags }
                suggestions={ selectedTags }
                onChange={ ( tags ) => setSelectedTags( tags )  }
                disabled={ true }
            />
        </PanelBody>
    );
}

export default EntriesTags;