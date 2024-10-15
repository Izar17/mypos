<script>
    document.addEventListener( 'DOMContentLoaded', () => {
        nsExtraComponents.createPrintersFromLocalSourceComponent   =   defineComponent({
            template  : `
            <button @click="refreshPrinters()" class="rounded-full
            text-sm
            h-10
            px-3
            outline-none
            border
            ns-crud-button
            "> <i :class="isLoading ? 'animate-spin' : ''" class="las la-sync"></i> ${__m( 'Sync Cloud Printers', 'NsPrintAdpater' )}</button>
            `,
            props: [ 'result' ],
            data() {
                return {
                    isLoading: false,
                }
            },
            mounted() {
                // ...
            },
            methods: {
                refreshPrinters() {
                    this.isLoading  =   true;
                    nsHttpClient.get( `{{ ns()->route( 'ns.print-adapter.sync-printers', [ 'direction' => 'down' ] ) }}` )
                        .subscribe({
                            next: printers => {
                                this.isLoading  =   false;
                                this.$emit( 'refresh' );
                            },
                            error: error => {
                                this.isLoading  =   false;
                                nsNotice.error(
                                    __m( 'Sync Cloud Print', 'NsPrintAdapter' ), 
                                    __m( 'Unable to retrieve the printers from the my.nexopos.com. Make sure you\'ve linked your installation to the server and selected a Printers Setup.', 'NsPrintAdapter' ), 
                                    { 
                                        actions: {
                                            close: {
                                                label: __m( 'Close', 'NsPrintAdapter' ),
                                            },
                                            configure: {
                                                label: __m( 'Settings', 'NsPrintAdapter' ),
                                                onClick: () => {
                                                    document.location   =   nsPrintData.settings;
                                                }
                                            }
                                        }
                                    }).subscribe();
                            }
                        });                    
                },
                loadPrinters( printers ) {
                    nsHttpClient.post( `/api/ns-printadapter/refresh-printers`, { printers })
                        .subscribe({
                            next: result => {
                                this.$emit( 'refresh' );
                                this.isLoading  =   false;
                            },
                            error: error => {
                                this.isLoading  =   false;
                                nsSnackBar.error( __m( 'An error occured while refreshing the printers.', 'NsPrintAdapter' ) ).subscribe();
                            }
                        })
                }
            }
        });

        nsEvent.subject().subscribe( async (event) => {
            if ( event.identifier === 'ns-table-row-action' && event.value.action.identifier === 'test-print' ) {
                nsHttpClient.post( '/api/ns-printadapter/test', { printer: event.value.row.id })
                    .subscribe({
                        next: result => {
                            nsSnackBar.success( result.message ).subscribe();
                        },
                        error: error => {
                            nsSnackBar.error( error.message ).subscribe();
                        }
                    })
            }
        });
    })
</script>