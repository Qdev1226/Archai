import { __ } from '@wordpress/i18n';
import { formIcon, listIcon, calenderCheckIcon, calenderEditedIcon, userIcon, desktopIcon } from '../icon';
import { gfpDateFormat, gfpIsEmpty } from '../../helper';

const EntryDetails = (props) => {
    const entryData = gutenaFormsEntryDetails?.entry_data;
   
    return(
        <div className='details-section no-panel-section' >
        <h2 className='title' >{ __( 'Entry Details' ) }</h2>
            <ul className='details'>
                <li>
                    <span className='icon'>{ formIcon() }</span>
                    <span className='label'>{ __( 'Form :', 'gutena-forms' ) }</span>
                    <span className='description'>{ entryData.form_name }</span>
                </li>
                <li>
                    <span className='icon'>{ listIcon() }</span>
                    <span className='label'>{ __( 'Entry ID :', 'gutena-forms' ) }</span>
                    <span className='description'>{ entryData.entry_id }</span>
                </li>
                <li>
                    <span className='icon'>{ calenderCheckIcon() }</span>
                    <span className='label'>{ __( 'Submitted :', 'gutena-forms' ) }</span>
                    <span className='description'>{ gfpDateFormat( entryData.added_time, '@' ) }</span>
                </li>
                <li>
                    <span className='icon'>{ calenderEditedIcon() }</span>
                    <span className='label'>{ __( 'Modified :', 'gutena-forms' ) }</span>
                    <span className='description'>{ gfpDateFormat( entryData.modified_time, '@' ) }</span>
                </li>
                { ! gfpIsEmpty( entryData.user_display_name ) && (
                    <li>
                        <span className='icon'>{ userIcon() }</span>
                        <span className='label'>{ __( 'User :', 'gutena-forms' ) }</span>
                        <span className='description'>{ entryData.user_display_name }</span>
                    </li>
                )}
                {/* <li>
                    <span className='icon'>{ desktopIcon() }</span>
                    <span className='label'>{ __( 'User IP', 'gutena-forms' ) } :</span>
                    <span className='description'>{ entryData.ip_address }192.258.521</span>
                </li> */}
            </ul>
        </div>
    );
}

export default EntryDetails;