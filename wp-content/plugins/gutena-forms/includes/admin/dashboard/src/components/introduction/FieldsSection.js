import { __ } from '@wordpress/i18n';

const FieldsSection = () => {
    const fields =  gutenaFormsIntroduction?.section?.fields;
    return( 
        <div className="gutena-forms-fields-section ">
            <div className='gf-container'>
                <h2 className='gf-title'>
                    { fields?.title }
                </h2>   
                <div className='gf-flex gf-feature-cards'>
                    {
                        fields?.items?.map( ( item, index ) => (
                            <div className='gf-feature-card' key={ 'field-card'+index } >
                                {
                                    true === item.is_pro && (
                                        <div className='gf-pro-tag'>
                                            { __( 'Pro', 'gutena-forms' ) } 
                                        </div>
                                    )
                                }
                                <div className='gf-icon'>
                                    <img src={ item.icon } alt={ __( 'feature icon', 'gutena-forms' ) } width="36" height="36" />
                                </div>
                                <div className='gf-title'>
                                    { item.title } 
                                </div>
                            </div>
                        ) )
                    }
                    
                </div>
            </div>
        </div>
    );

}

export default FieldsSection;