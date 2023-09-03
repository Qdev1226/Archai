import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

const PricingSection = () => {
    
    const pricing =  gutenaFormsIntroduction?.section?.pricing;
    return( 
        <div className="gutena-forms-pricing-section">
            <div className='gf-container'>
                <h2 id="gutena-forms-pricing" className='gf-title'>
                    { pricing?.title }
                </h2>
                <h3 className='gf-description'>
                    { pricing?.subtitle } 
                </h3>
                <div className='gf-flex gf-feature-cards'>
                    {
                        pricing?.items?.map( ( item, index ) => (
                            <div className='gf-feature-card' key={ 'pricing-card'+index } >
                                <div className='gf-title'>
                                    { item?.title } 
                                </div>
                                <div className='gf-pricing'>
                                   <span className='gf-price'> { item?.price } </span>
                                   <span className='gf-bill-frequency'> { pricing?.billed_frequency } </span>
                                </div>
                                <div className='gf-description'>
                                    { item?.description } 
                                </div>
                                <ul className='gf-features-list'>
                                    {
                                        pricing?.features?.map( ( feature, index ) => (
                                            <li key={ 'features-list'+index } >
                                                <span className='gf-icon'>
                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clipPath="url(#clip0_5487_231)">
                                                    <path d="M6.74987 12.1275L3.62237 9L2.55737 10.0575L6.74987 14.25L15.7499 5.25L14.6924 4.1925L6.74987 12.1275Z" fill="#2C3338"/>
                                                    </g>
                                                    <defs>
                                                    <clipPath id="clip0_5487_231">
                                                    <rect width="18" height="18" fill="white"/>
                                                    </clipPath>
                                                    </defs>
                                                </svg>
                                                </span>
                                                <span className='gf-feature-title'>
                                                    { feature }
                                                </span>
                                            </li>
                                        ))
                                    }
                                </ul>
                                <Button 
                                    href={ item?.link } 
                                    target='_blank'
                                    className='gf-primary-btn'
                                    variant='primary'
                                >
                                    { item?.btn_name }
                                </Button>
                            </div>
                        ) )
                    }
                    
                </div>
            </div>
        </div>
    );

}

export default PricingSection;