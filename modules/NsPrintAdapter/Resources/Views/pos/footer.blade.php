<?php

use App\Services\ModulesService;
use Illuminate\Support\Str;

$moduleService  =   app()->make( ModulesService::class );
?>
<script src="{{ asset( 'modules/nsprintadapter/js/html2canvas.min.js' ) }}"></script>
<script>
const nsPrintAdapterOptions     =   {
    'printer_name'              :   '{{ ns()->option->get( "ns_pa_printer" ) }}',
    'ns_pa_cloud_print'         :   '{{ ns()->option->get( "ns_pa_cloud_print" ) }}',
    'ns_pa_convert_to_image'    :   '{{ ns()->option->get( "ns_pa_convert_to_image" ) }}',
    'server_address'            :   '{{ Str::finish( ns()->option->get( "ns_pa_server_address" ), "/" ) }}',
    'gastro_enabled'            :   <?php echo $moduleService->getIfEnabled( 'NsGastro' ) ? 'true': 'false';?>,
};

class nsPaPrint {
    constructor() {
        nsHooks.addFilter( 'ns-custom-print', 'ns-pa.catch-printing', ( data ) => {
            return {
                params: data.params,
                promise: () => new Promise( async ( resolve, reject ) => {

                    const { mode, gateway, reference_id, document }     =   data.params;

                    try {
                        if ( gateway === 'nps_legacy' ) {
                            if ( mode === 'aloud' ) {
                                    if ( nsPrintAdapterOptions.ns_pa_cloud_print === 'no' ) {
                                        try {
                                            return resolve( this.printOnLocalSelectedPrinter( reference_id, document ) );
                                        } catch( exception ) {
                                            return reject( exception );
                                        }
                                    } else {
                                        try {
                                            return  resolve( await this.printRemotelyOnSelectedPrinter({ reference_id, gateway, document }) );
                                        } catch( exception ) {
                                            return  reject( exception );
                                        }
                                    }
                            } else {
                                if ( nsPrintAdapterOptions.ns_pa_cloud_print === 'no' ) {                       
                                    return resolve( await this.print( reference_id, document ) );
                                } else {
                                    /**
                                     * print is handled behind the scene
                                     * so let's just make NexoPOS doesn't warn an unsupported
                                     * print gateway.
                                     */
                                    return resolve( await this.printOrderSilently( reference_id, null, document ) );
                                }
                            }
                        }

                        return reject({
                            status: 'error',
                            message: __m( 'Unsupported print gateway provided.', 'NsPrintAdapter' )
                        });
                    } catch( exception ) {
                        nsNotice.error( 
                            __m( 'An Error Occured', 'NsPrintAdapter' ), 
                            exception.message 
                        );
                    }
                })
            };
        });

        nsHooks.addAction( 'ns-gastro-after-add-products', 'nps-kitchen-add-products', async ( config ) => {
            try {
                this.triggerKitchenPrint( config.order.id, config.products.map( product => product.id ) );
            } catch( exception ) {
                console.error( exception );
            }
        });

        nsHooks.addFilter( 'ns-gastro-print-order-canceled-meal', 'nps-kitchen-canceled-meals', async ( config ) => {
            console.log({ config });
            return config;
        });

        nsHooks.addAction( 'ns-order-submit-successful', 'nps-kitchen-submit-order', async ( result ) => {
            try {
                this.triggerKitchenPrint( result.data.order.id, [] );
            } catch( exception ) {
                console.error( exception );
            }
        });
    }

    triggerKitchenPrint( reference_id, products_id = [] ) {
        return new Promise( ( resolve, reject) => {
            
            /**
             * We'll process this only if Gastro is enabled
             * and if the cloud print option is disabled as 
             * it's processed remotely.
             */
            if ( nsPrintAdapterOptions.gastro_enabled && nsPrintAdapterOptions.ns_pa_cloud_print === 'no' ) {
                try {
                    // this will help get the kitchen printer name
                    nsHttpClient.post( `/api/ns-printadapter/kitchen-print-data`, { products_id, reference_id, document: 'kitchen' })
                        .subscribe({
                            next: ( receipts ) => {
                                if ( receipts.length === 0 ) {
                                    reject({
                                        status: 'error',
                                        message: __m( 'No printer assigned to the kitchen handling the sold products.', 'NsPrintAdapter' )
                                    });

                                    return nsNotice.error( 
                                        __( 'No Printers Assigned' ), 
                                        __( 'We\'re not able to print this at the kitchen as there is no printer assigned to the kitchen handling the sold products' ), {
                                            duration: 0,
                                            actions: {
                                                close: {
                                                    label: __( 'Close' ),
                                                    onClick: ( floating ) => {
                                                        floating.close();
                                                    }
                                                }
                                            }
                                        }
                                    );
                                } 

                                receipts.forEach( async (receipt) => {
                                    const {content, printer}   =   receipt;
                                    try {
                                        await this.sendXMLPrintRequest({ printer, content, address: nsPrintAdapterOptions.server_address });
                                    } catch( exception ) {
                                        nsSnackBar.error( exception.message || __( 'An unexpected error occured while printing locally.' ) ).subscribe();
                                    }
                                    
                                    return resolve( receipt );
                                });
                            }, 
                            error: ( error ) => {
                                nsSnackBar.error( __m( `An error has occured while fetching the order receipts for the kitchen printing.`, 'NsGastro' ) ).subscribe();
                                return reject( error );
                            }
                        });
                } catch( exception ) {
                    return reject( exception );
                }
            }

            return resolve( true ); // not handled
        })
    }

    async printOnLocalSelectedPrinter( order_id, document = 'sale' ) {
        return new Promise( async ( resolve, reject ) => {
            try {
                const printers          =   await this.getLocalPrinters( nsPrintAdapterOptions.server_address );
                const localPrinterID    =   await new Promise( ( resolve, reject ) => {
                    Popup.show( nsSelectPopup, {
                        label: __m( 'Choose The Local Printer', 'NsPrintAdapter' ),
                        name: 'select_printer',
                        description: __m( 'On which printer would you like to print this order?', 'NsPrintAdapter' ),
                        options: printers.map( printer => {
                            return {
                                label: printer.name,
                                value: printer.id || printer.name
                            }
                        }),
                        resolve,
                        reject
                    });
                });

                const selectedPrinter   =   printers.filter( printer => printer.id === localPrinterID );

                resolve( await this.printOrderSilentlyLocally( order_id, localPrinterID, undefined, document ) );
            } catch( exception ) {
                if ( exception !== false ) {
                    reject({
                        message: __m( 'An error occured while retreiving the local printers. Make sure you\'ve set the authentication credentials correctly.', 'NsPrintAdapter' ),
                        status: 'error'
                    })
                }
            }
        })
    }

    printOrderSilently( reference_id, printer = null, document = 'sale' ) {
        const loading   =   Popup.show( nsPOSLoadingPopup );

        return new Promise( ( resolve, reject ) => {
            nsHttpClient.post( `/api/ns-printadapter/cloud-print`, { printer, document, reference_id }).subscribe({
                next: result => {
                    loading.close();                    
                    return resolve({
                        status: 'success',
                        message: __m( 'The print job was submitted to the cloud', 'NsPrintAdapter' )
                    });
                },
                error: error => {
                    loading.close();
                    nsSnackBar.error( error.message ).subscribe();

                    return reject({
                        status: 'error',
                        message: __m( 'An error has occured while submitting to the cloud.', 'NsPrintAdapter' )
                    });
                }
            });
        })
    }

    printRemotelyOnSelectedPrinter({ reference_id, gateway, printed, document }) {
        return new Promise( ( resolve, reject ) => {
            const loading   =   Popup.show( nsPOSLoadingPopup );
            nsHttpClient.get( `/api/ns-printadapter/enabled-printers` ).subscribe({
                next: async (printers) => {
                    loading.close();
                    if ( printers.length > 0 ) {
                        try {
                            const printer_id    =   await new Promise( ( resolve, reject ) => {
                                Popup.show( nsSelectPopup, {
                                    label: __m( 'Choose The Cloud Printer', 'NsPrintAdapter' ),
                                    description: __m( 'On which printer would you like to print this order?', 'NsPrintAdapter' ),
                                    options: printers.map( printer => {
                                        return {
                                            label: printer.name,
                                            value: printer.id
                                        }
                                    }),
                                    resolve,
                                    reject
                                });
                            });

                            this.printOrderSilently( reference_id, printer_id, document )
                                .then( result => {
                                    return resolve(result);
                                })
                                .catch( error => {
                                    return reject(error);
                                })
                        } catch( exception ) {
                            // no need to show an error.
                        }
                    } else {
                        nsSnackBar.error( __m( 'No active printers for printing. Make sure to enable at least one cloud printer.', 'NsPrintAdapter' ) ).subscribe();
                        return reject({ printed: false });
                    }
                },
                error: error => {
                    loading.close();
                    nsSnackBar.error( error.message || __m( 'An unexpected error occured while fetching enabled cloud printers.', 'NsPrintAdapter' ) ).subscribe();
                    return reject({ printed: false });
                }
            });
        })
    }

    finish(str, ending) {
        if (str.endsWith(ending)) {
            return str;
        } else {
            return str + ending;
        }
    }


    getLocalPrinters( url ) {
        return new Promise ( ( resolve, reject ) => {
            nsHttpClient.get( `/api/ns-printadapter/enabled-printers` )
                .subscribe({
                    next: printers  => {
                        resolve( printers );
                    },
                    error: error => {
                        nsSnackBar.error( error.message || __m( 'An unexpected error occured', 'NsPrintAdapter' ), __m( 'Okay', 'NsPrintAdapter' ) ).subscribe();
                    }
                })
        })
    }
    
    async print( order_id, document = 'sale' ) {
        return new Promise( async ( resolve, reject ) => {
            if ( typeof POS !== 'undefined' )  {
                const registerId = POS.get( 'register' ) ? POS.get( 'register' ).id : null;

                try {
                    resolve(  await this.printOrderSilentlyLocally( order_id, undefined, registerId, document ) );
                } catch( exception ) {
                    reject( exception );
                }
            } else {
                const printers    =   await this.getLocalPrinters( nsPrintAdapterOptions.server_address );
                
                if ( printers.length === 0 ) {
                    return reject({
                        status: 'error',
                        message: __m( 'No printer configured on Nexo Print Server for printing.', 'NsPrintAdapter' )
                    });
                }

                try {
                    const localPrintName    =   await new Promise( ( resolve, reject ) => {
                        Popup.show( nsSelectPopup, {
                            label: __m( 'Choose The Local Printer', 'NsPrintAdapter' ),
                            description: __m( 'On which printer would you like to print this order?', 'NsPrintAdapter' ),
                            options: printers.map( printer => {
                                return {
                                    label: printer.name,
                                    value: printer.name
                                }
                            }),
                            resolve,
                            reject
                        });
                    });

                    try {
                        return resolve( await this.printOrderSilentlyLocally( order_id, localPrintName, undefined, document ) );
                    } catch( exception ) {
                        return reject( exception );
                    }
                } catch( exception ) {
                    return reject( exception );
                }
            }   
        })
    }

    sendXMLPrintRequest({ printer, content, address }) {
        return new Promise( ( resolve, reject ) => {
            const oReq = new XMLHttpRequest();

            oReq.addEventListener( "load", ( e ) => {
                resolve({
                    status: 'success',
                    message: __m( 'The print job has been submitted.', 'NsPrintAdapter' )
                });
            });

            oReq.addEventListener( 'error', () => {
                reject({
                    status: 'error',
                    message: __m( 'An unexpected error has occured while printing.', 'NsPrintAdapter' )
                });
            });

            oReq.open( "POST",  `${this.finish( nsPrintAdapterOptions.server_address, '/' )}api/print` );
            oReq.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            oReq.send(JSON.stringify({ printer, content }));
        })
    }

    printOrderSilentlyLocally( reference_id, printer_id, register_id, document = 'sale' ) {
        return new Promise( ( resolve, reject ) => {
            nsHttpClient.post( `/api/ns-printadapter/print-data`, { register_id, printer_id, document, reference_id })
                .subscribe( async ( receipts ) => {
                    for( let i = 0; i < receipts.length; i++ ) {
                        
                        let { content, printer }  =   receipts[i]; // we've omitted to use the printer set here.
                        const data  =   new FormData;

                        try {
                            const printResult    =   this.sendXMLPrintRequest({ content, printer });
                            resolve( printResult );
                        } catch( exception ) {
                            reject( exception );
                        }
                    };

                }, ( error ) => {
                    reject({
                        status: 'error',
                        message: error.message || __m( 'An unexpected error has occured while retreiving the receipt.', 'NsPrintAdapter' )
                    })
                });
        })
    }
}

let nsPaPrintObject;
document.addEventListener( 'DOMContentLoaded', () => {
    nsPaPrintObject     =   new nsPaPrint;
})
</script>