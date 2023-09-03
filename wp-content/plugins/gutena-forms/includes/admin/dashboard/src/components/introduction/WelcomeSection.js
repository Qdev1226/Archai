import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';

const WelcomeSection = () => {
    const intro =  gutenaFormsIntroduction?.section?.welcome;
    return( 
        <div className="gutena-forms-welcome-section ">
            <div className='gf-flex gf-container'>
                <div className='gf-left-section'>
                    <a href={ intro?.intro_video_link } target="_blank" >
                        <img src={ intro?.into_img } alt={ __( 'Introduction', 'gutena-forms' ) } />
                    </a>
                </div>
                <div className='gf-right-section'>
                    <h2 className='gf-title'>
                        { intro?.title }
                    </h2>
                    <p className='gf-description' >
                        { intro?.description }
                    </p>
                    <div className='gf-btn-group'>
                        <Button 
                        href='#gutena-forms-pricing' 
                        className='gf-primary-btn'
                        variant='primary'
                        >
                            { intro?.pricing_btn_name }
                        </Button>
                        <Button 
                        href={ gutenaFormsDashboard?.support_link } 
                        target='_blank'
                        className='gf-dark-btn'
                        variant='primary'
                        >
                            { intro?.help_btn_name }
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );

}

export default WelcomeSection;