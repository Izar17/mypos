<?php
use Illuminate\Support\Str;

$printerAddress     =   Str::finish( ns()->option->get( 'ns_pa_server_address' ), '/' );
?>
<script>
    document.addEventListener( 'DOMContentLoaded', () => {
        const address   =   '{{ $printerAddress }}';
        const finish    =   function(str, ending) {
            if (str.endsWith(ending)) {
                return str;
            } else {
                return str + ending;
            }
        }

        nsEvent.subject().subscribe( async (event) => {
            if ( event.identifier === 'ns-table-row-action' && event.value.action.identifier === 'test-print' ) {
                try {
                    const result    =   await new Promise( ( resolve, reject ) => {
                        const data      =   new FormData;
                        const printer   =   event.value.row;
                        const oReq      =   new XMLHttpRequest();
                        const content   =   `
                        <${'?xml version="1.0" encoding="UTF-8"?'}>
                        <configuration>
                            <characterset>${printer.characterset}</characterset>
                            <interface>Printer:${printer.name}</interface>
                            <type>${printer.type}</type>
                            <line-character>${printer.line_character}</line-character>
                        </configuration>
                        <document>
                            <double-width size="3:3">
                                <align mode="center">
                                    <bold>
                                            <text-line>Test Print</text-line>
                                    </bold>
                                </align>
                            </double-width>
                            <line-feed/>
                            <align mode="center">
                                <text-line>This test confirm NexoPOS is able to communicate with Nexo Print Server</text-line> 
                            </align>
                            <align mode="center">
                                <text-line>Additionnally this test is made to ensure every options offered by Nexo Print Server are supported by the current printer</text-line>
                            </align>
                            <line-feed/>
                            <invert>
                                <bold>
                                    <text-line>Text Alignment</text-line>
                                </bold>
                            </invert>                            
                            <align mode="left">
                                <text-line size="1:2">Aligned Left</text-line>
                            </align>
                            <align mode="right">
                                <text-line size="1:2">Aligned Right</text-line>
                            </align>
                            <align mode="center">
                                <text-line size="1:2">Aligned Center</text-line>
                            </align>
                            <line-feed/>
                            <invert>
                                <bold>
                                    <text-line>Text Size & Weight</text-line>
                                </bold>
                            </invert>   
                            <bold>
                                <text-line>Bold Text</text-line>
                            </bold>
                            <quad-size>
                                <text-line>Quart Size</text-line>
                            </quad-size>
                            <double-width>
                                <text-line>Double Width</text-line>
                            </double-width>
                            <double-height>
                                <text-line>Double Height</text-line>
                            </double-height>
                            <invert>
                                <bold>
                                    <text-line>Image And Barcode</text-line>
                                </bold>
                            </invert>   
                            <align mode="center">
                                <image>https://user-images.githubusercontent.com/5265663/162700085-40ed00ca-9154-42cb-850a-ccf1c2db2d5d.png</image>
                            </align>
                            <line-separator/>
                            <line-feed></line-feed>
                            <full-cut/>
                        </document>
                        `

                        oReq.addEventListener( "load", ( e ) => {
                            const result    =   JSON.parse( e.target.response );

                            if ( result.status === 'error' ) {
                                return reject( result );
                            }

                            resolve( result );
                        });

                        oReq.addEventListener( 'error', () => {
                            reject({
                                status: 'error',
                                message: __m( 'An unexpected error has occured while printing.', 'NsPrintAdapter' )
                            });
                        });

                        oReq.open( "POST",  `${finish( address, '/' )}api/print` );
                        oReq.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                        oReq.send(JSON.stringify({ content }));
                    });

                    console.log( result );
                } catch( exception ) {
                    nsNotice.error( __m( 'An Error Occured', 'NsPrintAdapter' ), exception.message );
                }
            }
        });

        nsExtraComponents.createPrintersFromLocalSourceComponent   =   defineComponent({
            template  : `
            <button @click="refreshPrinters()" class="rounded-full
            text-sm
            h-10
            px-3
            outline-none
            border
            ns-crud-button
            "> <i :class="isLoading ? 'animate-spin' : ''" class="las la-sync"></i> ${__m( 'Refresh Printers', 'NsPrintAdpater' )}</button>
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
                    nsHttpClient.get( `{{ $printerAddress }}api/printers` )
                        .subscribe({
                            next: printers => {
                                this.loadPrinters( printers );
                            },
                            error: error => {
                                this.isLoading  =   false;
                                nsNotice.error(
                                    __m( 'Refresh Failed', 'NsPrintAdapter' ), 
                                    __m( 'Unable to retrieve the printers from Nexo Print Server. Make sure the server is running and double-check the address.', 'NsPrintAdapter' ), 
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
    })
</script>