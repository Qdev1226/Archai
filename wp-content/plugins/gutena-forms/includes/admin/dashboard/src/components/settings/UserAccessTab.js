import { __ } from '@wordpress/i18n';
import { 
    Button,
    SelectControl
} from '@wordpress/components';
import { useContext, useState } from '@wordpress/element';
import { store as coreStore } from '@wordpress/core-data';
import { useDispatch, useSelect } from '@wordpress/data';
import { gfpIsEmpty, gfpSanitizeName, gfpUcFirst, saveSettingsAjax } from '../../helper';
import { trashIcon } from '../icon';

const noop = () => {};
const UserAccessTab = ({
    onClickFunc = noop
}) => {
    
    //Get users list
	const users = useSelect( ( select ) => {

		let adminUsers = select( coreStore ).getUsers( { roles:['administrator', 'author', 'editor', 'shop_manager'] } );

        //process user list to keep admin at top
        if ( ! gfpIsEmpty( adminUsers ) ) {
            let newUsers = {
                administrator: [],
                author: [],
                editor: [],
                shop_manager: []
            };
            adminUsers.forEach( user => {
                if ( user.roles.includes('administrator') ) {
                    newUsers.administrator.push( user );
                } else if ( user.roles.includes('author') ) {
                    newUsers.author.push( user );
                } else if ( user.roles.includes('editor') ) {
                    newUsers.editor.push( user );
                } else if ( user.roles.includes('shop_manager') ) {
                    newUsers.shop_manager.push( user );
                }
            });
            adminUsers = [ 
                ...newUsers.administrator,
                ...newUsers.author,
                ...newUsers.editor,
                ...newUsers.shop_manager
            ];  
        }
        return adminUsers;
	}, [] );

    if ( gfpIsEmpty( users ) ) {
        return false;
    } 

    const accessOptions = [
        { label: __( 'None', 'gutena-forms' ), value: '' },
    ];

    const replaceUnderScoreWithSpace = ( data ) => gfpIsEmpty( data ) ? data :  data.toLowerCase().replace( /_/g, ' ' );

    return (
        <div className='user-acess-tab gfp-settings-section'>
            <div className='user-acess'>
                {
                    users.map(( user, index )=>(
                        <div className='user-control-wrapper'  key={"user-row-"+user.id} >
                            <div className='username-role-row' >
                                <span className='username'  >{ user.username }</span>
                                <span className='description' > ( { gfpUcFirst( replaceUnderScoreWithSpace( user.roles[0] ) ) } )
                                </span>
                            </div>
                            <SelectControl
                                label=''
                                value=''
                                onChange={ ( selection ) => {} 
                                }
                                disabled={ true }
                                __nextHasNoMarginBottom
                            >
                                {
                                    accessOptions.map(( options ) => (
                                        <option value={ options.value } key={ 'option'+options.value } >{ options.label }</option>
                                    ))
                                }
                            </SelectControl>
                        </div>
                    ))
                }
            </div>
            <div className="add-save-row">
                <Button 
                    label={ __( 'Save user access', 'gutena-forms' ) }
                    variant="primary"
                    className='save-btn'
                    disabled={ false }
                    onClick={ () => onClickFunc() }
                >
                        {  __( 'Save', 'gutena-forms' ) }
                </Button> 
            </div>
        </div>
    );
}

export default UserAccessTab;