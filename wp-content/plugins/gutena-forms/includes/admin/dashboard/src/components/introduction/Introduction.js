import WelcomeSection from './WelcomeSection';
import FeaturesSection from './FeaturesSection';
import FieldsSection from './FieldsSection';
import PricingSection from './PricingSection';
import FaqSection from './FaqSection';

const Introduction = (props) => {
   
    return(
        <div className='gf-introduction-page' >
           <WelcomeSection />
           <FeaturesSection />
           <FieldsSection />
           <PricingSection />
           <FaqSection />
        </div>
    );
}

export default Introduction;