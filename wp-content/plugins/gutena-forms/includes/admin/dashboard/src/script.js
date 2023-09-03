document.addEventListener("DOMContentLoaded", function(){
    class GfBasicDashboard {
    
        constructor() {
            if ( ! this.isEmpty( gutenaFormsDashboard ) ) {
                this.select_change_url();
                this.confirm_delete();
                this.read_entries_status_update();
                this.goToEntryListPage();
                this.goToEntryViewPage();
                setTimeout(() => {
                    this.gutenaFormsModal();
                    this.accordions();
                    this.scrollToSectionFromUrl();
                }, 500);
                
                this.makeDashboardVisible();
            } else {
                console.log(" gutenaFormsDashboard not found");
            }
        }
        
        //scroll to section from url in case react component may not appear bedore call
        scrollToSectionFromUrl() {
            let elId = window.location.hash;
            if ( 'undefined' !== typeof elId  && null !== elId && '' !== elId ) {
                let el = document.querySelector( elId );
                if ( 'undefined' !== typeof el ) {
                    location.hash = '#';
                    location.hash = elId;
                }
            }
        }

        //make page visible after fully loaded
        makeDashboardVisible(){
           let domID = document.getElementById("gutena-forms-dashboard-page");
            domID.style.display="block";
        }
        
        //accordions: panel open close
        accordions(){
            let panels = document.querySelectorAll(
                '#gutena-forms-dashboard-page .gf-accordions .gf-title-icon'
            );
            
            if ( 0 < panels.length ) {
                
                for ( let i = 0; i < panels.length; i++ ) {
                    panels[ i ].addEventListener( 'click', function () {
                        
                        let panel = this.nextElementSibling;
                        if (panel.style.maxHeight) {
                            panel.style.maxHeight = null;
                        } else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                        } 
                    })
                }
            }
        }

        //check if elemnet has the given class or not
        hasClass( element, className ){
            return (
                ( ' ' + element.className + ' ' ).indexOf( ' ' + className + ' ' ) >
                -1
            );
        };

        //check if given data is empty
        isEmpty( data ){
			return 'undefined' === typeof data || null === data || '' === data;
		};

        //Get parent HTML elemnet
        getParents( el, query ){
            let parents = [];
            while ( el.parentNode !== document.body ) {
                el.matches( query ) && parents.push( el );
                el = el.parentNode;
            }
            return 0 < parents.length ? parents[0] : false ;
        };

        /**
         * On select url will be be change and perform a GET request
         */
        select_change_url(){
            let selectEl = document.querySelectorAll(
                '.gutena-forms-dashboard .select-change-url'
            );
            if ( 0 < selectEl.length ) {
                for ( let i = 0; i < selectEl.length; i++ ) {
                    selectEl[ i ].addEventListener( 'change', function () {
                        let url = this.getAttribute('url');
                        if ( 'undefined' !== typeof url && null !== url ) {
                            window.location = url+( this.value );
                        }
                    } );
                }
            }
        }

        /**
         * Update form entry status to read
         */
        read_entries_status_update(){
            let viewEl = document.querySelectorAll(
                '.gutena-forms-dashboard .quick-view-form-entry-unread'
            );
            if ( 0 < viewEl.length &&  'undefined' !== typeof gutenaFormsDashboard && null !== gutenaFormsDashboard  ) {
                let obj = this;
                for ( let i = 0; i < viewEl.length; i++ ) {
                    viewEl[ i ].addEventListener( 'click', function () {
                        let entry_id = this.getAttribute('entryid');
                        let table_row = obj.getParents( this, 'tr' );
                        let entry_status = false === table_row ? '' : table_row.getAttribute('currentstatus');
                        //console.log("entry_status "+entry_status+" entry_id"+entry_id);
                        if ( 'undefined' !== typeof entry_status && 'unread' === entry_status && 'undefined' !== typeof entry_id && null !== entry_id && 0 < entry_id ) { 
                            let el = this;
                            fetch( gutenaFormsDashboard.ajax_url, {
                                method: 'POST',
                                credentials: 'same-origin', // <-- make sure to include credentials
                                headers:{
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'Accept': 'application/json',
                                    'X-WP-Nonce' : gutenaFormsDashboard.nonce
                                },
                                body: new URLSearchParams({
                                    action:gutenaFormsDashboard.read_status_action,
                                    gfnonce:gutenaFormsDashboard.nonce,
                                    form_entry_id:entry_id
                                }),
                            } )
                            .then( ( response ) => response.json() )
                            .then( ( response ) => {
                                if ( false !== table_row ) {	
                                    table_row.classList.remove( 'unread' );
                                    table_row.classList.add( 'read' );
                                    table_row.setAttribute('currentstatus', 'read');
                                }
                            } ).catch((error) => {
                                console.error('Error:', error);
                                return false;
                            });
                        }
                    } );
                }
            }
        }

        /**
         * On click delete link. it will trigger a confirmation box and perform a GET delete action accoedingly 
         */
        confirm_delete(){
            let delete_entries = document.querySelectorAll(
                '.gutena-forms-dashboard .gf-delete'
            );
            if ( 0 < delete_entries.length ) {
                let obj = this;
                for ( let i = 0; i < delete_entries.length; i++ ) {
                    delete_entries[ i ].addEventListener( 'click', function (e) {
                        e.preventDefault();
                        let url = this.getAttribute('href');
                        if ( 'undefined' !== typeof url && null !== url ) {
                            
                            let modalEl = document.querySelector('#gutena-forms-entry-delete-modal');

                            if ( ! obj.isEmpty( modalEl ) ) {
                                
                                let deleteBtn = modalEl.querySelector('.gf-entry-delete-btn');
                                if ( ! obj.isEmpty( deleteBtn ) ) {
                                    deleteBtn.setAttribute("href",url);
                                    modalEl.style.display = "block";
                                }
                            }
                        }
                    } );
                }
            }
        };

        /**
         * Modal : in entry table modal which encolsed modal and trigger button are under same parent 
         */
        gutenaFormsModal(){
            //Open modal
            let modalBtn = document.querySelectorAll(
                '.toplevel_page_gutena-forms .gutena-forms-modal-btn'
            );
            let i = 0;
            let obj = this;
            if ( 0 < modalBtn.length ) {
                for ( i = 0; i < modalBtn.length; i++ ) {
                    modalBtn[ i ].addEventListener( 'click', function (e) {
                        e.preventDefault();
                        let modalID = this.getAttribute('modalid');
                        if ( 'undefined' === typeof modalID || null === modalID ) {
                            console.log("modal not found");
                            return false;
                        }
                        
                        let modalEl = document.querySelector('#'+modalID);

                        if ( 'undefined' !== typeof modalEl && null !== modalEl ) {
                            modalEl.style.display = "block";
                        }
                    } );
                }
            }
            //close modal
            let closeBtn = document.querySelectorAll(
                '.gutena-forms-modal .gf-close-btn'
            );
            if ( 0 < closeBtn.length ) {
                for ( i = 0; i < closeBtn.length; i++ ) {
                    closeBtn[ i ].addEventListener( 'click', function (e) {
                        e.preventDefault();
                        //get field group 
                        let parentModalEl = obj.getParents(
                            this,
                            '.gutena-forms-modal'
                        );
                        if ( false !== parentModalEl ) {
                            parentModalEl.style.display = "none";
                        }
                    } );
                }
            }

            //close modal on click outside
            let modalEls = document.querySelectorAll(
                '.gutena-forms-modal'
            );
            if ( 0 < modalEls.length ) {
                window.onclick = function(event) {
                    for ( i = 0; i < modalEls.length; i++ ) {
                        if (event.target == modalEls[i]) {
                            modalEls[i].style.display = "none";
                        }
                    }
                }
            }

            //Action button
            let modalActionBtn = document.querySelectorAll(
                '.gutena-forms-modal .gf-action-btn'
            );
            if ( 0 < modalActionBtn.length ) {
                for ( i = 0; i < modalActionBtn.length; i++ ) {
                    modalActionBtn[ i ].addEventListener( 'click', function () {
                        //action drop down 
                        let dropdownEl = this.nextElementSibling;
                        if (dropdownEl.style.display === "none") {
                            dropdownEl.style.display = "block";
                        } else {
                            dropdownEl.style.display = "none";
                        }
                    } );
                }
            }
        };

        /**
         * Go to Entry View page on click row
         */
        goToEntryViewPage(){
            //Open modal
            let tableRow = document.querySelectorAll(
                '.toplevel_page_gutena-forms .gutena-forms-dashboard  .entry tbody tr'
            );
            let i = 0;
            let obj = this;
            if ( 0 < tableRow.length ) {
                for ( i = 0; i < tableRow.length; i++ ) {
                    tableRow[ i ].addEventListener( 'click', function (e) {
                        let el = e.target;
                        let column_name = el.getAttribute('data-colname');
                        column_name = obj.isEmpty( column_name ) ? '': column_name.toLowerCase()
                        if ( '' !== column_name && ! ['status','action'].includes( column_name ) ) {
                            let entryid = this.getAttribute('entryid');
                            if ( ! obj.isEmpty( entryid ) ) {
                                e.preventDefault();
                                window.location = gutenaFormsDashboard.entry_view_url+entryid;
                            }
                        }
                        
                    });
                }
            }
        
        }

         /**
         * Go to form's Entry list page on click row 
         */
         goToEntryListPage(){
            //Open modal
            let tableRow = document.querySelectorAll(
                '.toplevel_page_gutena-forms .gutena-forms-dashboard  .table-view-list.form  tbody tr'
            );
            let i = 0;
            let obj = this;
            if ( 0 < tableRow.length ) {
                for ( i = 0; i < tableRow.length; i++ ) {
                    tableRow[ i ].addEventListener( 'click', function (e) {
                        let el = e.target;
                        let column_name = el.getAttribute('data-colname');
                        column_name = obj.isEmpty( column_name ) ? '': column_name.toLowerCase()
                        if ( '' !== column_name && ! ['status','action'].includes( column_name ) ) {
                            let formid = this.getAttribute('formid');
                            if ( ! obj.isEmpty( formid ) ) {
                                e.preventDefault();
                                window.location = gutenaFormsDashboard.entry_list_url+formid;
                            }
                        }
                        
                    });
                }
            }
        
        }
    }

    const GfBasicDashboard1 = new GfBasicDashboard();
});