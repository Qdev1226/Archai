/**
 * gutena forms: Knowledge base page
 */
import { Button } from '@wordpress/components';

const KnowledgeBase = () => {
    return(
        <div className="gutena-forms-knowledge-base">
            <div className="gutena-docs">
                <div className="gutena-details">
                    <h2 className="gutena-title">{ gutenaFormsDoc?.topics?.title }</h2>
                    <ol className="gutena-topics">
                        {
                            gutenaFormsDoc?.topics?.items.map( ( item, index ) => {
                                return(
                                    <li className="gutena-topic" key={ index }>
                                        <a className="gutena-topic-link" href={ item?.link } target="_blank" >{ item?.heading }</a>
                                        <p className="gutena-topic-description">{ item?.description }</p>
                                    </li>
                                );
                            } )
                        }
                    </ol>
                </div>
            </div> 
            <div className="gutena-support">
                <div className="gutena-details">
                    <h2 className="gutena-title">{ gutenaFormsDoc?.support?.title }</h2>
                    <p className="gutena-description">{ gutenaFormsDoc?.support?.description }</p>
                    <div className='gf-btn-group'>
                        <Button 
                        href={ gutenaFormsDoc?.support?.documentation_link }
                        target='_blank'
                        className='gf-dark-btn'
                        variant='primary'
                        >
                            { gutenaFormsDoc?.support?.documentation_text }
                        </Button>
                        <Button 
                        href={ gutenaFormsDashboard?.support_link }
                        target='_blank'
                        className='gf-primary-btn'
                        variant='primary'
                        >
                            { gutenaFormsDoc?.support?.link_text }
                        </Button>
                    </div>
                </div>
            </div>
            <div className="gutena-changelog">
                <div className="gutena-details">
                    <h2 className="gutena-title">{ gutenaFormsDoc?.changelog?.title }</h2>
                    <p 
                        className="gutena-description"  
                        dangerouslySetInnerHTML={{ __html: gutenaFormsDoc?.changelog?.description }}
                    />
                </div>
            </div>
        </div>
    )
}

export default KnowledgeBase;