import { __ } from '@wordpress/i18n';
import { gfpIsEmpty } from '../../helper';
import { SelectControl } from '@wordpress/components';

const FormListOrName = () => {
    
    const entryData = ( 'undefined' === typeof gutenaFormsEntryDetails || gfpIsEmpty( gutenaFormsEntryDetails?.entry_data ) ) ? '': gutenaFormsEntryDetails.entry_data ;

    //form list
    if ( '' === entryData && ! gfpIsEmpty( gutenaFormsDashboard?.form_id ) && ! gfpIsEmpty( gutenaFormsList?.list ) ) {
        const formOptions = gutenaFormsList.list.map( item => ({ label: item.form_name, value: item.form_id }) );
        
        return(
            <SelectControl
                value={ gutenaFormsDashboard.form_id }
                options={ formOptions }
                onChange={ ( formID ) => {
                    window.location = gutenaFormsDashboard.entry_list_url+formID;
                } }
                __nextHasNoMarginBottom
            />
        )
    } else if ( '' !== entryData ) {
        const formID = entryData?.form_id;
        const entry_list_url = gfpIsEmpty( gutenaFormsDashboard?.entry_list_url ) ? '#': gutenaFormsDashboard.entry_list_url+formID;
        return(
            <>
            <span>
                <a href={ entry_list_url } >{ entryData.form_name }</a>
            </span>
            <span> { '>' } </span>
            <span> { __( 'Entry ', 'gutena-forms' ) + entryData.current_entry_sno } </span>
            </>
        )
    }

    return '';
}

const Breadcrumb = () => {
    const pageUrl = gutenaFormsDashboard.page_url;
    
    return( 
        <>
            <span>
                <a href={ pageUrl }>
                    { __( 'Entries', 'gutena-forms' ) }
                </a>
            </span>
            <span> { '>' } </span>
            <FormListOrName />
        </>
    );

}

export default Breadcrumb;