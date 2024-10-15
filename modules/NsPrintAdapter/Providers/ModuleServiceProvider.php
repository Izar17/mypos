<?php

namespace Modules\NsPrintAdapter\Providers;

use App\Classes\Hook;
use App\Classes\Output;
use App\Crud\RegisterCrud;
use App\Events\OrderAfterCreatedEvent;
use App\Services\Helper;
use App\Services\ModulesService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Modules\NsGastro\Crud\KitchensCrud;
use Modules\NsGastro\Events\GastroAfterCanceledOrderProductEvent;
use Modules\NsGastro\Events\GastroNewProductAddedToOrderEvent;
use Modules\NsMultiStore\Events\MultiStoreApiRoutesLoadedEvent;
use Modules\NsMultiStore\Events\MultiStoreWebRoutesLoadedEvent;
use Modules\NsPrintAdapter\Crud\PrinterCrud;
use Modules\NsPrintAdapter\Events\NsPrintAdapterEvent;
use Modules\NsPrintAdapter\Events\NsPrintAdapterFilter;
use Modules\NsPrintAdapter\Models\Printer;
use Modules\NsPrintAdapter\Services\CloudPrintService;
use Modules\NsPrintAdapter\Services\PrintService;
use Modules\NsPrintAdapter\Settings\PrintAdapterSettings;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        Hook::addFilter('ns-dashboard-menus', function ($menus) {
            if (isset($menus['settings'])) {
                $menus['settings']['childrens']['ns-adapter'] = [
                    'label'     =>      __m('NPS Adapter', 'NsPrintAdapter'),
                    'href'      =>      ns()->url('/dashboard/print-adapter/settings'),
                ];
            }

            if (isset($menus['settings'])) {
                $menus = array_insert_after($menus, 'settings', [
                    'ns.print-adapter.printers'     =>  [
                        'label'     =>  __m('Printers', 'NsPrintAdapter'),
                        'icon'      =>  'la-print',
                        'permissions'   =>  [ 'nspa.manage-printers' ],
                        'childrens' =>  [
                            [
                                'label' =>  __m('List', 'NsPrinAdapter'),
                                'href'  =>  ns()->route('ns.print-adapter.printers'),
                            ],
                        ],
                    ],
                ]);
            }

            return $menus;
        }, 30);

        Hook::addAction('ns-crud-form-footer', function (Output $output, $namespace) {
            if ($namespace === 'ns.registers') {
                return $output->addView('NsPrintAdapter::cash-registers.footer');
            }

            return $output;
        }, 10, 2);

        Hook::addAction('ns-crud-footer', function (Output $output, $namespace) {
            if ($namespace === 'ns.printers') {
                return $output->addView('NsPrintAdapter::printers.footer');
            }

            return $output;
        }, 10, 2);

        Hook::addFilter('ns-crud-resource', function ($identifier) {
            switch ($identifier) {
                case 'ns.printers':
                    return PrinterCrud::class;
                default:
                    return $identifier;
            }
        });

        Hook::addFilter('ns.settings', function ($class, $identifier) {
            if ($identifier === 'ns.pa-settings') {
                return new PrintAdapterSettings;
            }

            return $class;
        }, 10, 2);

        Event::listen(MultiStoreApiRoutesLoadedEvent::class, fn () => ModulesService::loadModuleFile('NsPrintAdapter', 'Routes/api'));
        Event::listen(MultiStoreWebRoutesLoadedEvent::class, fn () => ModulesService::loadModuleFile('NsPrintAdapter', 'Routes/multistore'));
        Event::listen(OrderAfterCreatedEvent::class, [NsPrintAdapterEvent::class, 'prepareOrderForKitchenPrint']);
        Event::listen( GastroAfterCanceledOrderProductEvent::class, [ NsPrintAdapterEvent::class, 'prepareOrderForCanceledPrint' ]);
        Event::listen( GastroNewProductAddedToOrderEvent::class, [ NsPrintAdapterEvent::class, 'prepareOrderForAdditionalPrint' ]);

        /**
         * This will filter the POS options
         * to provide new options.
         */
        Hook::addFilter('ns-pos-options', function ($options) {
            $options['ns_pa_printing_gateway'] = ns()->option->get('ns_pa_printing_gateway', 'default');

            return $options;
        });

        // Hook::addFilter( KitchensCrud::method( 'getFillable' ), function( $fillable ) {
        //     $fillable[]     =   'printer_ids';
        //     return $fillable;
        // });

        Hook::addFilter(RegisterCrud::class.'@filterPostInputs', function ($inputs) {
            $inputs['printer_id'] = request()->input('general.printer_id');

            return $inputs;
        });

        Hook::addFilter(RegisterCrud::class.'@filterPutInputs', function ($inputs) {
            $inputs['printer_id'] = request()->input('general.printer_id');

            return $inputs;
        });

        /**
         * This will overwrite the printing fields
         * to add a new printing option (Nexo Print Server)
         */
        Hook::addFilter('ns-printing-settings-fields', function ($fields) {
            foreach ($fields as &$field) {
                if ($field['name'] === 'ns_pos_printing_gateway') {
                    $field['options'] = [
                        ...$field['options'],
                        [
                            'value' =>  'nps_legacy',
                            'label' =>  __('Nexo Print Server'),
                        ]
                    ];
                }
            }

            return $fields;
        });

        Hook::addFilter(RegisterCrud::method('getForm'), function ($form, $data = null) {
            if (! empty($data)) {
                extract($data);
            }

            $fields = [
                [
                    'type'  =>  'select',
                    'name'  =>  'printer_id',
                    'label' =>  __('Printer'),
                    'options'   =>  Helper::toJsOptions(Printer::enabled()->get(), ['id', 'name']),
                    'description'   =>  __('Select the printer used for the cash register.'),
                    'value' =>  $model->printer_name ?? '',
                ],
            ];

            array_push($form['tabs']['general']['fields'], ...$fields);

            return $form;
        }, 10, 2);

        Hook::addAction('ns-dashboard-pos-footer', [NsPrintAdapterEvent::class, 'getFooter']);
        Hook::addAction('ns-dashboard-orders-footer', [NsPrintAdapterEvent::class, 'getFooter']);
        Hook::addFilter( 'gastro-kitchen-receipt', [ NsPrintAdapterFilter::class, 'customKitchenPrint' ]);
        Hook::addFilter( 'gastro-kitchen-canceled-receipt', [ NsPrintAdapterFilter::class, 'customCanceledKitchenPrint' ]);

        $this->app->singleton(PrintService::class, fn () => new PrintService);

        $this->app->singleton(CloudPrintService::class, fn () => new CloudPrintService( 
            notificationService: app()->make( NotificationService::class )
        ) );
    }

    public function boot()
    {
    }
}
