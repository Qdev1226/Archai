import { __ } from '@wordpress/i18n';
import { gfpIsEmpty } from '../../helper';

const DashboardMenu = () => {

    const pageType = gfpIsEmpty( gutenaFormsDashboard.pagetype ) ? '': gutenaFormsDashboard.pagetype;
    const menu = gutenaFormsDashboard.dashboard_menu.filter( item =>  '1' === item.enable );
    const pageSlugs = menu.map( item => item.slug );
    const pageUrl = gutenaFormsDashboard.page_url;

    const isActiveMenu = ( slug = '' ) => ( pageType === slug || ( '' === slug && ! pageSlugs.includes( pageType ) ) ) ? 'active': '';
    
    return( 
        <ul>
            {
                menu.map( ( item, index ) => (
                    <li 
                    className={ isActiveMenu( item.slug ) } 
                    key={ 'dashboard-mav-'+index } 
                    >
                        <a href={ gfpIsEmpty( item.link ) ? pageUrl+item.slug : item.link } 
                           target={ gfpIsEmpty( item.target ) ? '' :  item.target }
                        >
                            { item.title }
                        </a>
                    </li>
                ) )
            }
        </ul>
    );

}

export default DashboardMenu;