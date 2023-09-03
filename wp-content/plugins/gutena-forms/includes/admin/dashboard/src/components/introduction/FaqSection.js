import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

const TabPanel = ( {
    item = {},
    index = 0
} ) => {
    const [ openPanel, setOpenPanel ] = useState( 0 === index );
    return (
        <div className={ 'gf-accordions-panel '+( openPanel ? 'open':'' ) } >
            <div className='gf-title-icon'
            onClick={ () => setOpenPanel( ! openPanel ) }
            >
               <span className='gf-title'> { item?.title } </span>
               <span className='gf-icon'> {
                openPanel ? 
                <svg width="15" height="3" viewBox="0 0 15 3" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.1" y="0.1" width="14.8" height="2.8" fill="#272A41" stroke="white" strokeWidth="0.2"/>
                </svg>
                :<svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect y="6" width="15" height="3" fill="#272A41"/>
                    <rect x="9" width="15" height="3" transform="rotate(90 9 0)" fill="#272A41"/>
                </svg>
               } </span>
            </div>
            <div className='gf-description' 
            style={ 0 === index ? { maxHeight: 'fit-content' }:{} } 
            >
                <p>{ item?.description }</p>
            </div>
        </div>
    )
}

const FaqSection = () => {
    const faq =  gutenaFormsIntroduction?.section?.faq;
    return( 
        <div className="gutena-forms-faq-section">
            <div className='gf-container'>
                <h2 className='gf-title'>
                    { faq?.title }
                </h2>

                <ul className='gf-accordions' >
                    {
                        faq?.items?.map( ( item, index ) => (
                            <TabPanel
                            item={ item }
                            index={ index }
                            key={ 'gf-accordion-panel'+index }
                            /> 
                        ))
                    }
                </ul>

                <p className='gf-contact-sales' >
                    <span>{ faq?.sales?.title1 } </span>
                    <a href={ faq?.sales?.link } target="_blank">
                    { faq?.sales?.title2 } 
                    </a>
                </p>
            </div>
        </div>
    );

}

export default FaqSection;