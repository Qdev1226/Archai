import { __ } from '@wordpress/i18n';
import domReady from '@wordpress/dom-ready';
import {createRoot } from '@wordpress/element';
//Dashboard menu
import DashboardMenu from './components/navigation/DashboardMenu';
//Breadcrumb
import Breadcrumb from './components/navigation/Breadcrumb';
//Gutena forms Introduction page
import Introduction from './components/introduction/Introduction';
//Form Entry page
import  EntryPage from './components/entry-page/EntryPage';
//Form settings page
import  SettingsPage from './components/settings/SettingsPage';
//knowledge base page
import KnowledgeBase from './components/knowledge-base/KnowledgeBase';

import { gfpIsEmpty } from './helper';
import './style.scss';

//Set dashboard at HTML id echo by Gutena_Kit_Admin class
domReady( () => {

    if ( 'undefined' === typeof gutenaFormsDashboard  ) {
        console.log("dashboard data missing");
        return '';
    }

    //Dashboard menu
    let domID = document.getElementById("gfp-dashboard-navigation");
    if ( ! gfpIsEmpty( domID ) && ! gfpIsEmpty( gutenaFormsDashboard?.page_url ) && ! gfpIsEmpty( gutenaFormsDashboard?.dashboard_menu ) ) {
        const root = createRoot( domID );
        root.render(
            <DashboardMenu />
        );

        //Breadcrumb
        const pageType = gfpIsEmpty( gutenaFormsDashboard.pagetype ) ? '': gutenaFormsDashboard.pagetype;
        const pageSlugs = gutenaFormsDashboard.dashboard_menu.map( item => item.slug );
        if( '' === pageType || ! pageSlugs.includes( pageType ) ) {
            domID = document.getElementById("gfp-page-breadcrumb");
            if ( ! gfpIsEmpty( domID ) ) {
                const root = createRoot( domID );
                root.render(
                    <Breadcrumb />
                );
            }
        };
    }

    //Gutena forms Introduction page
    if ( 'undefined' !== typeof gutenaFormsIntroduction ) {
        domID = document.getElementById("gfp-page-introduction");
        if ( ! gfpIsEmpty( domID ) ) {
            const root = createRoot( domID );
            root.render(
                <Introduction />
            );
        }
    }

    //knowledge base page
    if ( 'undefined' !== typeof gutenaFormsDoc  ) {
        domID = document.getElementById("gfp-page-doc");
        if ( ! gfpIsEmpty( domID ) ) {
            const root = createRoot( domID );
            root.render(
                <KnowledgeBase />
            );
        }
    }

    if ( ! gfpIsEmpty( gutenaFormsDashboard?.is_gutena_forms_pro ) && '0' === gutenaFormsDashboard.is_gutena_forms_pro ) {
        const openGoToProModal = () => {
            let modalEl = document.querySelector('#gutena-forms-go-pro-modal');
            if ( ! gfpIsEmpty( modalEl ) ) {
                modalEl.style.display = "block";
            }
        }

        //Page: Entry View 
        if ( 'undefined' !== typeof gutenaFormsEntryDetails ) {
            domID = document.getElementById("gfp-page-viewentry");
            if ( ! gfpIsEmpty( domID ) ) {
                const root = createRoot( domID );
                root.render(
                    <EntryPage
                    onClickFunc={ openGoToProModal }
                    />
                );
            }
        }

        //Page: Settings
        domID = document.getElementById("gfp-page-settings");
        if ( ! gfpIsEmpty( domID ) && 'undefined' !== typeof gutenaFormsSettingsTab && null !== gutenaFormsSettingsTab ) {
            const root = createRoot( domID );
            root.render(
                <SettingsPage
                onClickFunc={ openGoToProModal }
                />
            );
        }
        
    }
    
    
});