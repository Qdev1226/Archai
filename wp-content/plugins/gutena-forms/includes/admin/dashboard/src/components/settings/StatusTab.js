import { __ } from '@wordpress/i18n';
import {  
    TextControl,
    __experimentalHStack as HStack,
    __experimentalToolsPanel as ToolsPanel,
    Button,
} from '@wordpress/components';
import {  
    __experimentalColorGradientSettingsDropdown as ColorGradientSettingsDropdown,
} from "@wordpress/block-editor";
import {  useState } from '@wordpress/element';
import { gfpIsEmpty } from '../../helper';
import { trashIcon } from '../icon';
const noop = () => {};
const StatusTab = ({
    onClickFunc = noop
}) => {

    //status list 
    const [ statusList, setStatusList ] = useState ( 
        {
            availableStatus: [
                {
                    title: __( 'Unread', 'gutena-forms' ),
                    slug: 'unread',
                    color: '#D2D2D2' 
                },
                {
                    title: __( 'Read', 'gutena-forms' ),
                    slug: 'read',
                    color: '#7b68ee' 
                },
                {
                    title: __( 'Closed', 'gutena-forms' ),
                    slug: 'closed',
                    color: '#67cb48' 
                },
                {
                    title: __( 'Hot', 'gutena-forms' ),
                    slug: 'hot',
                    color: '#FF0000' 
                },
                {
                    title: __( 'Warm', 'gutena-forms' ),
                    slug: 'warm',
                    color: '#FFC700' 
                },
                {
                    title: __( 'Cold', 'gutena-forms' ),
                    slug: 'cold',
                    color: '#0047FF' 
                },
            ],
        }
    );

    const colors = [
        { name: __( 'red', 'gutena-forms' ), color: '#E83737' },
        { name: __( 'dark red', 'gutena-forms' ), color: '#8B0000' },
        { name: __( 'brown', 'gutena-forms' ), color: '#800000' },
        { name: __( 'blue', 'gutena-forms' ), color: '#0231e8' },
        { name: __( 'navy', 'gutena-forms' ), color: '#000080' },
        { name: __( 'green', 'gutena-forms' ), color: '#1bbc9c' },
        { name: __( 'violet', 'gutena-forms' ), color: '#bf55ec' },
        { name: __( 'green', 'gutena-forms' ), color: '#006400' },
        { name: __( 'grey', 'gutena-forms' ), color: '#667684' },
        { name: __( 'dark slate grey', 'gutena-forms' ), color: '#2F4F4F' },
        { name: __( 'magenta', 'gutena-forms' ), color: '#8B008B' },
        { name: __( 'violet red', 'gutena-forms' ), color: '#C71585' },
        { name: __( 'teal', 'gutena-forms' ), color: '#008080' },
    ];
    return (
        <div className='gfp-settings-section'>
            <ToolsPanel className="status-tool-panel" resetAll={ () => {}  } panelId= "status-color" >
                { statusList.availableStatus.map( ( itemStatus, index ) => {
                    let backgroundColor = {backgroundColor: gfpIsEmpty( itemStatus?.color ) ? '#D2D2D233': itemStatus.color+'33' };
                    return (
                    <HStack spacing={0} className={ "action-row "+itemStatus.slug } key={ "action-row"+index } style={backgroundColor} >
                        <ColorGradientSettingsDropdown 
                            settings={ [{
                                colorValue: itemStatus?.color,
                                onColorChange: ( color ) => {},
                            }] }
                            disableCustomColors={ false }
                            enableAlpha={ true }
                            panelId={ "status-color" }
                            colors={ colors }
                            title={ __( 'Status color', 'gutena-forms' ) }
                            disabled={ true }
                        />
                        <TextControl
                            value={ itemStatus?.title }
                            onChange={ ( title ) => {} }
                            placeholder={ __( 'Status Title', 'gutena-forms' ) }
                            style={backgroundColor}
                            disabled={ true }
                        />
                        {
                            ! ['unread','read'].includes( itemStatus.slug ) && (
                                <Button 
                                variant="tertiary"
                                className='delete-btn'
                                isDestructive={ true }
                                onClick={ () => onClickFunc() }
                                title={ __( 'Delete status', 'gutena-forms' ) }
                                icon={ trashIcon() }
                                disabled={ false }
                                >
                                </Button>
                            )

                        }
                        
                    </HStack>
                ) } ) }
                
            </ToolsPanel>
            <div className="add-save-row">
                
                {
                    (
                        <div>
                            <Button 
                                label={ __( 'Add Status', 'gutena-forms' ) }
                                variant="secondary"
                                onClick={ () => onClickFunc() }
                                disabled={ false }
                            >
                                    { __( 'Add Status', 'gutena-forms' ) }
                            </Button>
                        </div>
                    )
                }
            
                <div>
                    <Button 
                        label={ __( 'Save Status', 'gutena-forms' ) }
                        variant="primary"
                        className='save-btn'
                        disabled={ false }
                        onClick={ () => onClickFunc() }
                    >
                            {  __( 'Save', 'gutena-forms' ) }
                    </Button> 
                </div>
            </div>
        </div>
    );
}

export default StatusTab;