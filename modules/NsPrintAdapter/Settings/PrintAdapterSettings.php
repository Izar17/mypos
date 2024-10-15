<?php

namespace Modules\NsPrintAdapter\Settings;

use App\Classes\Hook;
use App\Classes\Output;
use App\Services\Helper;
use App\Services\Options;
use App\Services\SettingsPage;

class PrintAdapterSettings extends SettingsPage
{
    protected $form = [];

    const IDENTIFIER = 'ns.pa-settings';

    public function __construct()
    {
        $options = app()->make(Options::class);

        Hook::addAction( 'ns-dashboard-footer', function( Output $output ) {
            $output->addView( 'NsPrintAdapter::settings/footer' );
        });

        $this->form = [
            'title'         =>  __m('Print Adapter Settings', 'NsPrintAdapter'),
            'description'   =>  __m('Provides settings for print adapter.', 'NsPrintAdapter'),
            'tabs'      =>  [
                'general'   =>  [
                    'label'     =>  __m('General', 'NsPrintAdapter'),
                    'fields'    =>  [
                        [
                            'type'          =>  'switch',
                            'label'         =>  __m('Cloud Print Enabled', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_cloud_print'),
                            'description'   =>  __m('Wether or not the cloud printing should be used. ', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'yes'       =>  __m('Yes', 'NsPrintAdapter'),
                                'no'        =>  __m('No', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_cloud_print',
                        ],  [
                            'type'          =>  'switch',
                            'label'         =>  __m('Convert To Image', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_convert_to_image'),
                            'description'   =>  __m('NPS will attempt to convert receipts into image for printing. ', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'yes'       =>  __m('Yes', 'NsPrintAdapter'),
                                'no'        =>  __m('No', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_convert_to_image',
                        ],  [
                            'type'          =>  'switch',
                            'label'         =>  __m('Print Enabled', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_enabled'),
                            'description'   =>  __m('Wether or not the printing should be enabled. ', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'yes'       =>  __m('Yes', 'NsPrintAdapter'),
                                'no'        =>  __m('No', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_enabled',
                        ],  [
                            'type'          =>  'switch',
                            'label'         =>  __m('Display Payment Summary', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_payment_summary'),
                            'description'   =>  __m('If enabled, will display the differnt type of payment used on the payment receipt.', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'yes'       =>  __m('Yes', 'NsPrintAdapter'),
                                'no'        =>  __m('No', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_payment_summary',
                        ],  [
                            'type'          =>  'select',
                            'label'         =>  __m('Logo Type', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_logotype'),
                            'description'   =>  __m('Define what is the logo type.', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'url'       =>  __m('Image URL', 'NsPrintAdapter'),
                                'text'      =>  __m('Use Store Name', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_logotype',
                        ], [
                            'type'          =>  'media',
                            'label'         =>  __m('Logo URL', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_logourl'),
                            'description'   =>  __m('If the Logo type is an image URL, select the image here.', 'NsPrintAdapter'),
                            'name'          =>  'ns_pa_logourl',
                        ], [
                            'type'          =>  'text',
                            'label'         =>  __m('Character Limit', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_characters_limit'),
                            'description'   =>  __m('Define the maximum allowed characters. Default (48)', 'NsPrintAdapter'),
                            'name'          =>  'ns_pa_characters_limit',
                        ], [
                            'type'          =>  'textarea',
                            'label'         =>  __m('Left Column', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_left_column'),
                            'description'   =>  __m('Define the header for the left column', 'NsPrintAdapter'),
                            'name'          =>  'ns_pa_left_column',
                        ], [
                            'type'          =>  'textarea',
                            'label'         =>  __m('Right Column', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_right_column'),
                            'description'   =>  __m('Define the header for the right column', 'NsPrintAdapter'),
                            'name'          =>  'ns_pa_right_column',
                        ], [
                            'type'          =>  'textarea',
                            'label'         =>  __m('Receipt Footer', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_receipt_footer'),
                            'description'   =>  __m('This will always displays at the bottom of the receipt.', 'NsPrintAdapter'),
                            'name'          =>  'ns_pa_receipt_footer',
                        ],
                    ],
                ],
                'experimental'  => [
                    'label'     =>  __m('Experimental', 'NsPrintAdapter'),
                    'fields'    =>  [
                        [
                            'type'          =>  'switch',
                            'label'         =>  __m('Generate Image', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_generate_image'),
                            'description'   =>  __m('Will generate the receipt internal before printing.', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'yes'       =>  __m('Yes', 'NsPrintAdapter'),
                                'no'        =>  __m('No', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_generate_image',
                        ], [
                            'type'          =>  'search-select',
                            'label'         =>  __m('Font Familly', 'NsPrintAdapter'),
                            'value'         =>  $options->get('ns_pa_font_familly'),
                            'description'   =>  __m('each font might have support for different language.', 'NsPrintAdapter'),
                            'options'       =>  Helper::kvToJsOptions([
                                'roboto'    =>  __m('Roboto', 'NsPrintAdapter'),
                                'chinese'   =>  __m('Noto Sans TC (Chinese)', 'NsPrintAdapter'),
                                'arabic'    =>  __m('Noto Sans (Arabic)', 'NsPrintAdapter'),
                            ]),
                            'name'          =>  'ns_pa_font_familly',
                        ],
                    ],
                ]
            ],
        ];

        if (ns()->option->get('ns_pa_cloud_print', 'no') === 'yes') {

            /**
             * in case it has already been connected
             */
            if (ns()->option->get('ns_pa_access_token', false) && ! ns()->option->get('ns_pa_setup_hash', false)) {
                $this->form['tabs']['cloud'] = [
                    'label'     =>  __m('Cloud Printing', 'NsPrintAdapter'),
                    'component' =>  'nsPaSync',
                ];
            } elseif (ns()->option->get('ns_pa_setup_hash', false)) {
                $this->form['tabs']['cloud'] = [
                    'label'     =>  __m('Cloud Printing', 'NsPrintAdapter'),
                    'component' =>  'nsPaPrinterSync',
                ];
            } else {
                $this->form['tabs']['cloud'] = [
                    'label'     =>  __m('Cloud Printing', 'NsPrintAdapter'),
                    'component' =>  'nsPaAuthenticate',
                ];
            }
        }

        if (ns()->option->get('ns_pa_cloud_print', 'no') === 'no') {
            $this->form['tabs']['local'] = [
                'label' =>  __m('Local Printing', 'NsPrintAdapter'),
                'fields'    =>  [
                    [
                        'type'          =>  'text',
                        'label'         =>  __m('NPS Address', 'NsPrintAdapter'),
                        'value'         =>  $options->get('ns_pa_server_address'),
                        'description'   =>  __m('Provide the local Nexo Print Server address', 'NsPrintAdapter'),
                        'name'          =>  'ns_pa_server_address',
                    ],
                ],
            ];
        }
    }
}
