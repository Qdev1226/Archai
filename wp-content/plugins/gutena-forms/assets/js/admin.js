document.addEventListener("DOMContentLoaded", function(){
    class GutenaFormsAdmin {
    
        constructor() {
            if ( 'undefined' !== typeof gutenaFormsAdmin && null !== gutenaFormsAdmin && '' !== gutenaFormsAdmin ) {
                setTimeout(() => {
                    this.dismissAdminNotice();
                }, 100);
            }
        }

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
        
        //scroll to section from url in case react component may not appear bedore call
        dismissAdminNotice() {

            let dismissBtns = document.querySelectorAll(
                '.gutena-forms-admin-notice .notice-dismiss'
            );
            
            if ( 0 < dismissBtns.length ) {
                let obj  = this;
                for ( let i = 0; i < dismissBtns.length; i++ ) {
                    dismissBtns[ i ].addEventListener( 'click', function () {
                        let notice = obj.getParents( this, '.gutena-forms-admin-notice' );
                        if ( ! obj.isEmpty( notice ) ) {
                            let notice_id = notice.getAttribute('id');
                            fetch( gutenaFormsAdmin.ajax_url, {
                                method: 'POST',
                                credentials: 'same-origin', // <-- make sure to include credentials
                                headers:{
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'Accept': 'application/json',
                                    'X-WP-Nonce' : gutenaFormsAdmin.nonce
                                },
                                body: new URLSearchParams({
                                    action:gutenaFormsAdmin.dismiss_notice_action,
                                    gfnonce:gutenaFormsAdmin.nonce,
                                    notice_id:notice_id
                                }),
                            } )
                            .then( ( response ) => response.json() )
                            .then( ( response ) => {
                                notice.remove();
                                //console.log("dismiss notice",notice_id);
                            } ).catch((error) => {
                                console.error('Error:', error);
                            });
                        }
                    })
                }
            }
        }
    }

    const newGutenaFormsAdmin = new GutenaFormsAdmin();
});