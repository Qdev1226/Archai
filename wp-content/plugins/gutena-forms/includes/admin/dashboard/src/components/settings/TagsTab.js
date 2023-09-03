import { __ } from '@wordpress/i18n';
import {  
    TextControl,
    __experimentalHStack as HStack,
    Button,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { gfpIsEmpty } from '../../helper';
import { trashIcon } from '../icon';
const noop = () => {};
const TagsTab = ({
    onClickFunc = noop
}) => {
    

    //tag list 
    const [ tagList, setTagsList ] = useState ( 
        {
            availableTags: [
                {
                    title: __( 'New', 'gutena-forms' ),
                    slug: 'new',
                },
                {
                    title: __( 'Good', 'gutena-forms' ),
                    slug: 'good',
                },
                {
                    title: __( 'Best', 'gutena-forms' ),
                    slug: 'best',
                },
                {
                    title: __( 'Trending', 'gutena-forms' ),
                    slug: 'trending',
                    color: '#FF0000' 
                },
                {
                    title: __( 'Unique', 'gutena-forms' ),
                    slug: 'unique' 
                },
            ],
        }
    );


    return (
        <div className='gfp-settings-section'>
            <div className="tag-list" >
                { tagList.availableTags.map( ( itemTags, index ) => (
                    <HStack spacing={0} className="action-row" key={ "action-row"+index } >
                        <TextControl
                            value={ itemTags?.title }
                            onChange={ ( title ) => {} }
                            placeholder={ __( 'Tags Title', 'gutena-forms' ) }
                            disabled={ true }
                        />
                        <Button 
                        variant="tertiary"
                        className='delete-btn'
                        isDestructive={ true }
                        onClick={ () => {} }
                        title={ __( 'Delete tag', 'gutena-forms' ) }
                        icon={ trashIcon() }
                        disabled={ true }
                        >
                        </Button>
                    </HStack>
                ) ) }
            </div>
            <div className="add-save-row">
                {
                    (
                        <div>
                            <Button 
                                label={ __( 'Add Tags', 'gutena-forms' ) }
                                variant="secondary"
                                onClick={ () => onClickFunc() }
                                disabled={ false }
                            >
                                    { __( 'Add Tags', 'gutena-forms' ) }
                            </Button>
                        </div>
                    )
                }
            
                <div>
                    <Button 
                        label={ __( 'Save Tags', 'gutena-forms' ) }
                        variant="primary"
                        className='save-btn'
                        onClick={ () => onClickFunc() }
                        disabled={ false }
                    >
                            { __( 'Save', 'gutena-forms' ) }
                    </Button> 
                </div>
            </div>
        </div>
    );
}

export default TagsTab;