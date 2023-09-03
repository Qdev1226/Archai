import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { lockIcon } from '../icon';

const EntriesStatus = ( props ) => {
    const [ status, setStatus ] = useState( 'In progress' );
    
    return(
        <PanelBody 
        icon={ lockIcon() }
        iconPosition='right'
        className='status-section '
        title={ __( 'Status' ) } 
        initialOpen={ true }
        >
            <SelectControl
               value={ status }
               onChange={ ( entry_status ) => setStatus( entry_status ) }
               options={ [
                { label: __( 'Custom status' ), value: 'inprogress' },
                ]  
               }
               disabled={ true }
            />
        </PanelBody>
    );
}

export default EntriesStatus;