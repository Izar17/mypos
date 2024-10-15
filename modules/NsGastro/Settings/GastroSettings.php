<?php

namespace Modules\NsGastro\Settings;

use App\Classes\FormInput;
use App\Classes\SettingForm;
use App\Services\Helper;
use App\Services\ModulesService;
use App\Services\SettingsPage;

class GastroSettings extends SettingsPage
{
    protected $form;
    
    const IDENTIFIER = 'ns-gastro-settings';

    public function __construct()
    {
        /**
         * @var ModulesService $module
         */
        $module = app()->make(ModulesService::class);

        /**
         * general settings
         */
        $settings   =   SettingForm::form(
            title: __m( 'Gastro Settings', 'NsGastro' ),
            description: __m( 'Configure the settings for the restaurant.' ),
            tabs: SettingForm::tabs(
                SettingForm::tab(
                    identifier: 'general',
                    label: __m( 'POS', 'NsGastro' ),
                    fields: SettingForm::fields(
                        FormInput::switch(
                            name: 'ns_gastro_tables_assignation_enabled',
                            label: __m( 'Table Assignation', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_tables_assignation_enabled'),
                            description: __m( 'Will restrict table access to assigned users.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::switch(
                            name: 'ns_gastro_areas_enabled',
                            label: __m( 'Areas Enabled', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_areas_enabled'),
                            description: __m( 'If set to yes, areas before seeing tables.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::switch(
                            name: 'ns_gastro_seats_enabled',
                            label: __m( 'Seats Enabled', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_seats_enabled'),
                            description: __m( 'If set to yes, seats selection will be forced.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::switch(
                            name: 'ns_gastro_freed_table_with_payment',
                            label: __m( 'Freed Table After Payment', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_freed_table_with_payment'),
                            description: __m( 'If set to yes, every time a complete payment is made over a table, that table will be marked as free, Only works if table session is enabled.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::switch(
                            name: 'ns_gastro_enable_table_sessions',
                            label: __m( 'Enable Table Sessions', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_enable_table_sessions'),
                            description: __m( 'Useful to track orders made by a customer and chekc the table status.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::switch(
                            name: 'ns_gastro_allow_ready_meal_cancelation',
                            label: __m( 'Ready Meal Cancelation', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_allow_ready_meal_cancelation'),
                            description: __m( 'Wether or not cancelation for ready meal should possible.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::switch(
                            name: 'ns_gastro_allow_cancelation_print',
                            label: __m( 'Enable Cancelation Print', 'NsGastro' ),
                            value: (int) ns()->option->get('ns_gastro_allow_cancelation_print'),
                            description: __m( 'Will print a receipt when a meal is canceled.', 'NsGastro' ),
                            options: Helper::kvToJsOptions([false => __m( 'No', 'NsGastro' ), true => __m( 'Yes', 'NsGastro' )]),
                        ),
                        FormInput::textarea(
                            name: 'ns_gastro_cancelation_note',
                            label: __m( 'Cancelation Note', 'NsGastro' ),
                            value: ns()->option->get('ns_gastro_cancelation_note'),
                            description: __m( 'A note that should appear at the head of each item cancelation receipt.', 'NsGastro' ),
                        ),
                    )
                )
            )
        );        

        $this->form = $settings;
    }
}
