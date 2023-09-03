import EntryViewEdit from './EntryViewEdit';
import EntryDetails from './EntryDetails';
import RelatedEntries from './RelatedEntries';
import EntryAdminNotes from './EntryAdminNotes';
import EntriesStatus from './EntriesStatus';
import EntriesTags from './EntriesTags';
const noop = () => {};
const EntryPage = ({
    onClickFunc = noop
}) => {
    
    return(
        <div className='gfp-entry-page' >
            <div className='left-section'>
                <EntryViewEdit />
                <div   
                onClick={ () => onClickFunc() }
                >
                <EntryAdminNotes 
                 onClickFunc={ onClickFunc }
                />
                </div>
            </div>
            <div className='right-section'>
                <EntryDetails />
                <div   
                onClick={ () => onClickFunc() }
                >
                <EntriesStatus />
                <EntriesTags />
                <RelatedEntries />
                </div>
            </div>
        </div>
    );
}

export default EntryPage;