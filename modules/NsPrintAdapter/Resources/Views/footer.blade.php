<?php
use Modules\NsPrintAdapter\Models\Printer;
?>
<script>
document.addEventListener( 'DOMContentLoaded', () => {

    nsPaAuthenticateData    =   <?php echo json_encode([
        'redirect_url'  =>  ns()->route( 'ns.print-adapter.authenticate-callback' ),
        'url'           =>  'https://my.nexopos.com/en/documentation/my-nexopos/how-to-create-a-client-on-my-nexopos?utm_source=' . url( '/' ) . '&utm_campaign=settings&utm_medium=nexopos'
    ]);?>

    nsExtraComponents[ 'nsPaAuthenticate' ]   =   defineComponent({
        data() {
            return {
                validation: new FormValidation,
                fields: [],
                hasSavedSettings: false,
                appCredentialsFieldsLoading: false,
            }
        },
        methods: {
            saveCredentials() {
                if ( ! this.validation.validateFields( this.fields ) ) {
                    return Popup.show( nsAlertPopup, {
                        title: __m( 'Invalid credentials', 'NsPrintAdapter' ),
                        message: __m( 'Unable to proceed as the form is not valid.', 'NsPrintAdapter' ),
                    });
                }

                const form  =   this.validation.extractFields( this.fields );

                nsHttpClient.post( '/api/ns-printadapter/save-credentials', form )
                    .subscribe({
                        next: result => {
                            this.hasSavedSettings   =   true;
                            return nsNotice.info( 
                                __m( 'Settings Saved', 'NsPrintAdapter' ),
                                __m( 'Your settings were successfully saved.', 'NsPrintAdapter' ),
                            )
                        },
                        error: error => {
                            return nsNotice.error( 
                                __m( 'An error occured', 'NsPrintAdapter' ),
                                __m( 'We were\'nt able to save your settings. Please try again.', 'NsPrintAdapter' ),
                            )
                        }
                    });
            }
        },
        mounted() {
            this.appCredentialsFieldsLoading    =   true;
            nsHttpClient.get( '/api/ns-printadapter/get-fields' )
                .subscribe({
                    next: fields => {
                        this.appCredentialsFieldsLoading    =   false;
                        this.fields     =   fields;
                        // all fields has a value defined. This means we've saved settings already.
                        this.hasSavedSettings   =   fields.filter( field => field.value.length > 0 ).length === this.fields.length;
                        this.fields     =   this.validation.createFields( this.fields );
                    },
                    error: error => {
                        this.appCredentialsFieldsLoading    =   false;
                        return nsNotice.error( 
                            __m( 'An error occured', 'NsPrintAdapter' ),
                            __m( 'We were\'nt able to load the credentials fields. Please try again.', 'NsPrintAdapter' ),
                        )
                    }
                })
        },
        template: `
        <div class="p-2">
            <div class="flex flex-col md:flex-row md:-mx-2">
                <div class="w-full md:w-full lg:w-2/3 xl:1/3 md:px-2">
                    <h3 class="font-bold">${ __m( 'Step 1: Create Client', 'NsPrintAdapter' ) }</h3>
                    <p class="text-sm">${ __m( `
                        In order to link your installation to my.nexopos.com, you need to create <a href="{url}" target="_blank" class="ns-link underline">an Api Client</a> with the following redirection URL : 
                        <br><br><pre class="bg-gray-700 text-white p-2">{redirection_url}</pre> <br>
                        <img src="{{ ns()->asset( '/modules/nsprintadapter/images/demo.jpg' ) }}" alt="demo"/>
                        <br> We need to link your installation to My NexoPOS so that we can synchronise your printers with your account.
                        <br><br>
                        `, 'NsPrintAdapter' )
                        .replace( '{url}', nsPaAuthenticateData.url )
                        .replace( '{redirection_url}', nsPaAuthenticateData.redirect_url )
                    }</p>
                    <ns-spinner v-if="appCredentialsFieldsLoading"/>
                    <div v-if="fields.length > 0">
                        <h3 class="font-bold">${ __m( 'Step 2: Saving Client Credentials', 'NsPrintAdapter' ) }</h3>
                        <p class="text-sm">${ __m( `Once created, you'll copy the value of the "App ID" and the "Secret key" on the following fields`, 'NsPrintAdapter' )}</p>
                        <img src="{{ ns()->asset( '/modules/nsprintadapter/images/demo2.jpg' ) }}" alt="demo"/>
                        <br/>
                        <ns-field :key="key" v-for="(field, key) in fields" :field="field"/>
                        <ns-button @click="saveCredentials()" type="info">${ __m( 'Save Credentials', 'NsPrintAdapter' ) }</ns-button>
                        <br/>
                    </div>
                    <div v-if="hasSavedSettings">
                        <h3 class="font-bold">${ __m( 'Step 3: Authenticate', 'NsPrintAdapter' ) }</h3>
                        <p class="text-sm">${ __m( `Once saved, you\'re now able to authenticate your installation with My NexoPOS.`, 'NsPrintAdapter' )}</p>
                        <br>
                        <ns-link href="{{ ns()->route( 'ns.print-adapter.authenticate' ) }}" type="info">${ __( 'Authenticate' ) }</ns-link>
                    </div>
                </div>
                <div class="w-full lg:w-1/3 md:px-2">
                    
                </div> 
            </div>
        </div>
        `
    });

    /**
     * We'll store the data for that component
     * on a javascript object.
     */
    const nsPaPrinterSyncData               =   <?php echo json_encode([
        'totalPrinters'             =>      Printer::enabled()->count(),
        'setupName'                 =>      ns()->option->get( 'ns_pa_setup_name' ),
        'defaultPrinter'            =>      ns()->option->get( 'ns_pa_cloud_printer_id' ),
        'url'                       =>      [
            'syncPrinters'          =>      ns()->route( 'ns.print-adapter.sync-printers' ),
            'getPrinters'           =>      ns()->route( 'ns.print-adapter.enabled-printers' ),
            'savePrinterSettings'   =>      ns()->route( 'ns.print-adapter.save-printer-settings' ), // @deprecated
        ]
    ]);?>

    nsExtraComponents[ 'nsPaPrinterSync' ]  =   defineComponent({
        template: `
        <div class="p-2">
            <div class="flex -mx-2">
                <div class="w-full lg:w-1/2 px-2">
                    <h3 class="ns-heading-3">${ __m( 'Setup', 'NsPrintAdapter' ) }: ${ nsPaPrinterSyncData.setupName }</h3>
                    <p class="ns-paragraph">${ __m( 'You can now sync your printers to my.nexopos.com in order to make them available for a cloud printing. Only active printers can be synced.', 'NsPrintAdapter' ) }</p>
                    <div class="flex -mx-2">
                        <div class="px-2">
                            <ns-link href="{{ ns()->route( 'ns.print-adapter.delete-setup' ) }}" type="error">${ __m( 'Change Setup', 'NsPrintAdapter' ) }</ns-link>
                        </div>
                        <div class="px-2">
                            <ns-button :type="syncing == false ? 'info' : 'disabled'" @click="syncPrinters()">
                                <span class="mr-2">${ __m( 'Sync Printers', 'NsPrintAdapter' ) }</span>
                                <span class="ns-label w-6 h-6 rounded-full flex items-center justify-center">@{{ totalPrinters }}</span>
                            </ns-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `,
        mounted() {
            this.printerFields  =   this.validation.createFields( this.printerFields );
            this.loadPrinters();
        },
        methods: {
            syncPrinters() {
                if ( this.syncing === false ) {
                    this.syncing    =   true;
                    nsHttpClient.get( this.url.syncPrinters )
                        .subscribe({
                            next: result => {
                                this.syncing    =   false;
                                nsSnackBar.success( __m( 'The printers were successfully synced.', 'NsPrintAdapter' ) ).subscribe();
                                this.loadPrinters();
                            },
                            error: error => {
                                this.syncing    =   false;
                                nsSnackBar.error( result.message || __m( 'An error has occured while syncing the printers.', 'NsPrintAdapter' ) ).subscribe();
                            }
                        });
                }
            },
            /**
             * @deprecated
             */
            savePrinters() {
                if ( ! this.validation.validateFields( this.printerFields ) ) {
                    return nsSnackBar.error( __m( 'Unable to proceed the form is not valid' ) ).subscribe();
                }

                nsHttpClient.post( this.url.savePrinterSettings, this.validation.extractFields( this.printerFields ) )
                    .subscribe( result => {
                        return nsSnackBar.success( __m( 'The settings has been saved.' ) ).subscribe();
                    })  
            },
            loadPrinters() {
                nsHttpClient.get( this.url.getPrinters )
                    .subscribe( printers => {
                        const options   =   printers.map( printer => {
                            return {
                                label: printer.name,
                                value: printer.id
                            }
                        });

                        this.printerFields[0].options   =   options;
                    });
            }
        },
        data() {
            return {
                validation: new FormValidation,
                syncing: false,
                ...nsPaPrinterSyncData,
                printerFields: [
                    {
                        label: __m( 'Printer' ),
                        name: 'ns_pa_cloud_printer_id',
                        description: __m( 'Choose what is the default cloud printer', 'NsPrintAdapter' ),
                        type: 'select',
                        validation: 'required',
                        options: [],
                        value: nsPaPrinterSyncData.defaultPrinter
                    }
                ]
            }
        }
    });

    const nsPaSyncData  =   {
        url: {
            current         :   `{{ url()->current() }}`,
            getSetups       :   `{{ ns()->route( 'ns.print-adapter.get-setups' ) }}`,
            saveSetup       :   `{{ ns()->route( 'ns.print-adapter.save-setup' ) }}`,
        },
        fields: [
            {
                type: 'select',
                options: [],
                validation: 'required',
                label: `{{ __m( 'Assigned Setup', 'NsPrintAdapter' ) }}`,
                name: 'setup_id',
                description: `{{ __m( 'Select to which setup the printer should be assigned to.', 'NsPrintAdapter' ) }}`
            }
        ]
    };

    nsExtraComponents[ 'nsPaSync' ]   =   defineComponent({
        template: `
        <div class="p-2">
            <div class="flex -mx-2">
                <div class="w-full md:w-1/2 lg:w-1/3 px-2">
                    <h3 class="ns-heading-3">${ __m( 'Connected', 'NsPrintAdapter' ) }</h3>
                    <p class="ns-paragraph">${ __m( 'Your installation is currently linked to my.nexopos.com. At anytime, you can unlink your installation to stop syncing the printers.', 'NsPrintAdapter' ) }</p>
                    <div class="my-2">
                        <ns-field v-for="field of fields" :field="field"></ns-field>
                    </div>
                    <div class="flex -mx-2">
                        <div class="px-2">
                            <ns-link href="{{ ns()->route( 'ns.print-adapter.unlink' ) }}" type="error">${ __m( 'Unlink', 'NsPrintAdapter' ) }</ns-link>
                        </div>
                        <div class="px-2">
                            <ns-button @click="saveSetup()" type="info">${ __m( 'Save Setup', 'NsPrintAdapter' ) }</ns-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `,
        data() {
            return {
                fields: nsPaSyncData.fields,
                validation: new FormValidation,
                url: nsPaSyncData.url
            }
        },
        mounted() {
            this.fields     =   this.validation.createFields( this.fields );

            this.getSetups();
        },
        methods: {
            getSetups() {
                nsHttpClient.get( this.url.getSetups )
                    .subscribe( setups => {
                        this.fields[0].options  =   setups;
                    })
            },
            saveSetup() {
                if ( ! this.validation.validateFields( this.fields ) ) {
                    return nsSnackBar.error( __m( 'The form is not valid. Please select a setup.' ) ).subscribe();
                }

                const form  =   this.validation.extractFields( this.fields );
                const field     =   this.fields.filter( field => field.name === 'setup_id' );

                if ( field.length > 0 ) {
                    form.setup_name     =   field[0].options.filter( option => option.value === form.setup_id )[0].label;
                }                

                nsHttpClient.post( this.url.saveSetup, form )
                    .subscribe( result => {
                        setTimeout( () => document.location = `${this.url.current}?tab=cloud`, 1000 );
                        return nsSnackBar.success( result.message ).subscribe();
                    });
            }
        }
    })
});
</script>