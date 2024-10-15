<script>
document.addEventListener( 'DOMContentLoaded', () => {
    nsHooks.addAction( 'ns-crud-form-loaded', 'ns-gastro-settings', ( instance ) => {
        setTimeout(() => {
            const url   =   `{{ Str::finish( ns()->option->get( "ns_pa_server_address" ), '/' ) }}`;
            if ( url.length > 0 ) {
                var oReq = new XMLHttpRequest();
                oReq.addEventListener("load", ( e ) => {
                    const result    =   JSON.parse( e.target.responseText );
                    instance.form.tabs.general.fields.forEach( field => {
                        if ( field.name === 'printers' ) {
                            field.options   =   result.map( printer => {
                                return {
                                    label: printer.name,
                                    value: printer.name
                                }
                            })
                        }
                    })
                });
                oReq.addEventListener( 'error', () => {
                    nsSnackBar.error( __( 'An error has occured while loading the printer. Make sure the server URL is correct.' ) ).subscribe();
                });
                oReq.open("GET",  `${url}api/printers` );
                oReq.send();
            }
        }, 100 );
    })
});
</script>