import { __ } from '@wordpress/i18n';
import { TabPanel } from '@wordpress/components';
import StatusTab  from './StatusTab';
import UserAccessTab from './UserAccessTab';
import TagsTab from './TagsTab';
import { lockIcon } from '../icon';
import { gfpIsEmpty } from '../../helper';
const noop = () => {};
const SettingsPage = ({
    onClickFunc = noop
}) => {
    const tabComponents = {
        status:StatusTab,
        tags:TagsTab,
        useraccess:UserAccessTab
    }
    const tabs = gutenaFormsSettingsTab.tabs.map( item => ({
        ...item,
        className:item.name,
        component:tabComponents[item.name]
    }) );

    return(
        <div className='gfp-settings-page' >
            <TabPanel
                className="gfp-tab-panel"
                activeClass="active-tab"
                onSelect={ () => {} }
                tabs={ tabs }
            >
                { ( tab ) => {
                    return(
                        <>
                            <h2 className='title'>{ tab.heading } { lockIcon() }</h2>
                            <p className='description' >{ tab.description }</p>
                            <div 
                            onClick={ () => onClickFunc() }
                            >
                            <tab.component
                            onClickFunc={ onClickFunc }
                            />
                            </div>
                        </>
                    )
                } }
            </TabPanel>
        </div>
    );
}

export default SettingsPage;