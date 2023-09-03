import { __ } from '@wordpress/i18n';
import { PanelBody, Button, TextareaControl, Modal } from '@wordpress/components';
import { lockIcon } from '../icon';
const noop = () => {};
const EntryAdminNotes = ( {
    onClickFunc = noop
} ) => {
    

    return(
        <div className='notes-section no-panel-section' >
        <h2 className='title' >{ __( 'Notes' ) } { lockIcon() }</h2>
            <Button 
                label={ __( 'Add Notes', 'gutena-forms' ) }
                variant="secondary"
                onClick={ () => onClickFunc() }
                disabled={ false }
            >
                    { __( 'Add Notes', 'gutena-forms' ) }
            </Button>
        </div>
    );
}

export default EntryAdminNotes;