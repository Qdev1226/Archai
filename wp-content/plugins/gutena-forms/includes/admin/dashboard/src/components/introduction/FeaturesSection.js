import { __ } from '@wordpress/i18n';

const FeaturesSection = () => {
    const features =  gutenaFormsIntroduction?.section?.features;
    return( 
        <div className="gutena-forms-features-section ">
            <div className='gf-container'>
                <h2 className='gf-title'>
                    { features?.title }
                </h2>
                <div className='gf-flex gf-feature-cards'>
                    {
                        features?.items?.map( ( item, index ) => (
                            <div className='gf-feature-card' key={ 'feature-card'+index } >
                                {
                                    true === item.is_pro && (
                                        <div className='gf-pro-tag'>
                                            { __( 'Pro', 'gutena-forms' ) } 
                                        </div>
                                    )
                                }
                                <div className='gf-icon'>
                                    <img src={ item?.icon } alt={ __( 'feature icon', 'gutena-forms' ) } width="50" height="50" />
                                </div>
                                <div className='gf-title'>
                                    { item?.title } 
                                </div>
                                <div className='gf-description'>
                                    { item?.description } 
                                </div>
                            </div>
                        ) )
                    }
                    
                </div>
            </div>
        </div>
    );

}

export default FeaturesSection;