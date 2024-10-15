<?php

namespace Modules\NsPrintAdapter\Crud;

use App\Casts\YesNoBoolCast;
use App\Exceptions\NotAllowedException;
use App\Models\User;
use App\Services\CrudEntry;
use App\Services\CrudService;
use App\Services\Helper;
use App\Services\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\NsPrintAdapter\Models\Printer;
use TorMorten\Eventy\Facades\Events as Hook;

class PrinterCrud extends CrudService
{
    /**
     * define the base table
     *
     * @param  string
     */
    protected $table = 'nexopos_printers';

    /**
     * default slug
     *
     * @param  string
     */
    protected $slug = 'print-adapter/printers';

    /**
     * Define namespace
     *
     * @param  string
     */
    protected $namespace = 'ns.printers';

    /**
     * Model Used
     *
     * @param  string
     */
    protected $model = Printer::class;

    /**
     * Define permissions
     *
     * @param  array
     */
    protected $permissions = [
        'create'    =>  true,
        'read'      =>  true,
        'update'    =>  true,
        'delete'    =>  true,
    ];

    /**
     * Adding relation
     * Example : [ 'nexopos_users as user', 'user.id', '=', 'nexopos_orders.author' ]
     *
     * @param  array
     */
    public $relations = [
    ];

    /**
     * all tabs mentionned on the tabs relations
     * are ignored on the parent model.
     */
    protected $tabsRelations = [
        // 'tab_name'      =>      [ YourRelatedModel::class, 'localkey_on_relatedmodel', 'foreignkey_on_crud_model' ],
    ];

    /**
     * Pick
     * Restrict columns you retreive from relation.
     * Should be an array of associative keys, where
     * keys are either the related table or alias name.
     * Example : [
     *      'user'  =>  [ 'username' ], // here the relation on the table nexopos_users is using "user" as an alias
     * ]
     */
    public $pick = [];

    /**
     * Define where statement
     *
     * @var  array
     **/
    protected $listWhere = [];

    protected $casts    =   [
        'is_default'    =>  YesNoBoolCast::class,
    ];

    /**
     * Define where in statement
     *
     * @var  array
     */
    protected $whereIn = [];

    /**
     * Fields which will be filled during post/put
     */

    /**
     * If few fields should only be filled
     * those should be listed here.
     */
    public $fillable = [];

    /**
     * If fields should be ignored during saving
     * those fields should be listed here
     */
    public $skippable = [];

    /**
     * Return the label used for the crud
     * instance
     *
     * @return  array
     **/
    public function getLabels()
    {
        return [
            'list_title'            =>  __('Printers List'),
            'list_description'      =>  __('Display all printers.'),
            'no_entry'              =>  __('No printers has been registered'),
            'create_new'            =>  __('Add a new printer'),
            'create_title'          =>  __('Create a new printer'),
            'create_description'    =>  __('Register a new printer and save it.'),
            'edit_title'            =>  __('Edit printer'),
            'edit_description'      =>  __('Modify  Printer.'),
            'back_to_list'          =>  __('Return to Printers'),
        ];
    }

    /**
     * Check whether a feature is enabled
     *
     * @return  bool
     **/
    public function isEnabled($feature): bool
    {
        return false; // by default
    }

    /**
     * Fields
     *
     * @param  object/null
     * @return  array of field
     */
    public function getForm($entry = null)
    {
        return [
            'main' =>  [
                'label'         =>  __('Name'),
                'name'          =>  'name',
                'value'         =>  $entry->name ?? '',
                'description'   =>  __('Provide a name to the resource.'),
            ],
            'tabs'  =>  [
                'general'   =>  [
                    'label'     =>  __('General'),
                    'fields'    =>  [
                        [
                            'type'  =>  'switch',
                            'name'  =>  'is_default',
                            'validation'    =>  'required',
                            'options'   =>  Helper::kvToJsOptions([
                                0   =>  __m( 'No', 'NsPrintAdapter' ),
                                1   =>  __m( 'Yes', 'NsPrintAdapter' ),
                            ]),
                            'label' =>  __('Is Default'),
                            'value' =>  $entry ? ( $entry->is_default ? 1 : 0 ) : 0,
                            'description'   =>  __m('Set if the printer should be used by default.', 'NsPrintAdapter'),
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'identifier',
                            'disabled'  =>  'true',
                            'label' =>  __('Identifier'),
                            'value' =>  $entry->identifier ?? '',
                            'description'   =>  __m('Unique printer identifier.', 'NsPrintAdapter'),
                        ], [
                            'type'  =>  'select',
                            'options'   =>  Helper::kvToJsOptions([
                                Printer::INTERFACE_ETHERNET     =>  __m('Ethernet', 'NsPrintAdapter'),
                                Printer::INTERFACE_USBSERIAL    =>  __m('USB Serial', 'NsPrintAdapter'),
                                // Printer::INTERFACE_SERIAL       =>  __m('Serial', 'NsPrintAdapter'),
                                // Printer::INTERFACE_PARALLEL     =>  __m('Parallel', 'NsPrintAdapter'),
                            ]),
                            'name'  =>  'interface',
                            'label' =>  __('Interface'),
                            'value' =>  $entry->interface ?? '',
                            'description'   =>  __m('What interface should be use to reach the printer.', 'NsPrintAdapter'),
                        ], [
                            'type'  =>  'select',
                            'name'  =>  'type',
                            'label' =>  __('Type'),
                            'options'   => Helper::kvToJsOptions([
                                'epson' =>  __( 'Epson' ),
                                'star'  =>  __( 'Star' )
                            ]),
                            'value' =>  $entry->type ?? '',
                            'description'   =>  __m('Choose the type of your printer.', 'NsPrintAdapter'),
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'argument',
                            'label' =>  __('Argument'),
                            'value' =>  $entry->argument ?? '',
                            'description'   =>  __m('Does any argument is requied for reaching the printer ? Provide it there.', 'NsPrintAdapter'),
                        ], [
                            'type'  =>  'select',
                            'validation'    =>  'required',
                            'options'   =>  Helper::kvToJsOptions([
                                'PC437_USA' =>  'PC437_USA',
                                'PC850_MULTILINGUAL'    =>  'PC850_MULTILINGUAL',
                                'PC860_PORTUGUESE'  =>  'PC860_PORTUGUESE',
                                'PC863_CANADIAN_FRENCH' =>  'PC863_CANADIAN_FRENCH',
                                'PC865_NORDIC'  =>  'PC865_NORDIC',
                                'PC851_GREEK'   =>  'PC851_GREEK',
                                'PC857_TURKISH' =>  'PC857_TURKISH',
                                'PC737_GREEK'   =>  'PC737_GREEK',
                                'ISO8859_7_GREEK'   =>  'ISO8859_7_GREEK',
                                'WPC1252'   =>  'WPC1252',
                                'PC866_CYRILLIC2'   =>  'PC866_CYRILLIC2',
                                'PC852_LATIN2'  =>  'PC852_LATIN2',
                                'SLOVENIA'  =>  'SLOVENIA',
                                'PC858_EURO'    =>  'PC858_EURO',
                                'WPC775_BALTIC_RIM' =>  'WPC775_BALTIC_RIM',
                                'PC855_CYRILLIC'    =>  'PC855_CYRILLIC',
                                'PC861_ICELANDIC'   =>  'PC861_ICELANDIC',
                                'PC862_HEBREW'  =>  'PC862_HEBREW',
                                'PC864_ARABIC'  =>  'PC864_ARABIC',
                                'PC869_GREEK'   =>  'PC869_GREEK',
                                'ISO8859_2_LATIN2'  =>  'ISO8859_2_LATIN2',
                                'ISO8859_15_LATIN9' =>  'ISO8859_15_LATIN9',
                                'PC1125_UKRANIAN'   =>  'PC1125_UKRANIAN',
                                'WPC1250_LATIN2'    =>  'WPC1250_LATIN2',
                                'WPC1251_CYRILLIC'  =>  'WPC1251_CYRILLIC',
                                'WPC1253_GREEK' =>  'WPC1253_GREEK',
                                'WPC1254_TURKISH'   =>  'WPC1254_TURKISH',
                                'WPC1255_HEBREW'    =>  'WPC1255_HEBREW',
                                'WPC1256_ARABIC'    =>  'WPC1256_ARABIC',
                                'WPC1257_BALTIC_RIM'    =>  'WPC1257_BALTIC_RIM',
                                'WPC1258_VIETNAMESE'    =>  'WPC1258_VIETNAMESE',
                                'KZ1048_KAZAKHSTAN' =>  'KZ1048_KAZAKHSTAN',
                            ]),
                            'name'  =>  'characterset',
                            'label' =>  __('Character Set'),
                            'value' =>  $entry->characterset ?? '',
                            'description'   =>  __m('Set what character set should be used for printing.', 'NsPrintAdapter'),
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'line_character',
                            'label' =>  __('Line Character'),
                            'value' =>  $entry->line_character ?? '',
                            'description'   =>  __m('Set what character should be used for creating line on the receipt.', 'NsPrintAdapter'),
                        ], [
                            'type'          =>  'select',
                            'options'       =>  Helper::kvToJsOptions([
                                Printer::ENABLED    =>  __m('Enabled', 'NsPrintAdapter'),
                                Printer::DISABLED   =>  __m('Disabled', 'NsPrintAdapter'),
                            ]),
                            'description'   =>  __m('Define the printer status.', 'NsPrintAdapter'),
                            'name'          =>  'status',
                            'label'         =>  __('Status'),
                            'value'         =>  $entry->status ?? '',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Filter POST input fields
     *
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPostInputs($inputs)
    {
        $inputs['identifier'] = Str::uuid();

        return $inputs;
    }

    /**
     * Filter PUT input fields
     *
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPutInputs($inputs, Printer $entry)
    {
        if (empty($inputs['identifier'])) {
            $inputs['identifier'] = Str::uuid();
        }

        return $inputs;
    }

    /**
     * Before saving a record
     *
     * @param  Request  $request
     * @return  void
     */
    public function beforePost($request)
    {
        if ($this->permissions['create'] !== false) {
            ns()->restrict($this->permissions['create']);
        } else {
            throw new NotAllowedException;
        }

        return $request;
    }

    /**
     * After saving a record
     *
     * @param  Request  $request
     * @param  Printer  $entry
     * @return  void
     */
    public function afterPost($request, Printer $entry)
    {
        /**
         * Let's remove any other default printer
         */
        if ( $entry->is_default ) {
            Printer::where( 'id', '<>', $entry->id )->update([
                'is_default'    =>  false,
            ]);
        }
        
        return $request;
    }

    /**
     * get
     *
     * @param  string
     * @return  mixed
     */
    public function get($param)
    {
        switch ($param) {
            case 'model': return $this->model; break;
        }
    }

    /**
     * Before updating a record
     *
     * @param  Request  $request
     * @param  object entry
     * @return  void
     */
    public function beforePut($request, $entry)
    {
        if ($this->permissions['update'] !== false) {
            ns()->restrict($this->permissions['update']);
        } else {
            throw new NotAllowedException;
        }

        return $request;
    }

    /**
     * After updating a record
     *
     * @param  Request  $request
     * @param  object entry
     * @return  void
     */
    public function afterPut($request, Printer $entry)
    {
        /**
         * Let's remove any other default printer
         */
        if ( $entry->is_default ) {
            Printer::where( 'id', '<>', $entry->id )->update([
                'is_default'    =>  false,
            ]);
        }

        return $request;
    }

    /**
     * Before Delete
     *
     * @return  void
     */
    public function beforeDelete($namespace, $id, $model)
    {
        if ($namespace == 'ns.printers') {
            /**
             *  Perform an action before deleting an entry
             *  In case something wrong, this response can be returned
             *
             *  return response([
             *      'status'    =>  'danger',
             *      'message'   =>  __( 'You\re not allowed to do that.' )
             *  ], 403 );
             **/
            if ($this->permissions['delete'] !== false) {
                ns()->restrict($this->permissions['delete']);
            } else {
                throw new NotAllowedException;
            }
        }
    }

    /**
     * Define Columns
     */
    public function getColumns(): array
    {
        return [
            'name'  =>  [
                'label'  =>  __('Name'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'identifier'  =>  [
                'label'  =>  __('Identifier'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'interface'  =>  [
                'label'  =>  __('Interface'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'is_default'  =>  [
                'label'  =>  __('Default'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'status'  =>  [
                'label'  =>  __('Status'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'created_at'  =>  [
                'label'  =>  __('Created At'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
        ];
    }

    /**
     * Define actions
     */
    public function setActions(CrudEntry $entry ): CrudEntry
    {
        // Don't overwrite
        $entry->{ '$checked' } = false;
        $entry->{ '$toggled' } = false;
        $entry->{ '$id' } = $entry->id;

        // you can make changes here
        $entry->action(
            identifier: 'edit',
            label: __('Edit'),
            type: 'GOTO',
            url: ns()->url('/dashboard/'.$this->slug.'/edit/'.$entry->id),
        );

        $entry->action(
            identifier: 'test-print',
            label: __('Test Printing'),
            type: 'POPUP'
        );

        $entry->action(
            identifier: 'delete', 
            label: __('Delete'),
            type: 'DELETE',
            url: ns()->url('/api/crud/ns.printers/'.$entry->id),
            confirm:  [
                'message'  =>  __('Would you like to delete this ?'),
            ],
        );

        return $entry;
    }

    /**
     * Bulk Delete Action
     *
     * @param    object Request with object
     * @return    false/array
     */
    public function bulkAction(Request $request)
    {
        /**
         * Deleting licence is only allowed for admin
         * and supervisor.
         */
        if ($request->input('action') == 'delete_selected') {

            /**
             * Will control if the user has the permissoin to do that.
             */
            if ($this->permissions['delete'] !== false) {
                ns()->restrict($this->permissions['delete']);
            } else {
                throw new NotAllowedException;
            }

            $status = [
                'success'   =>  0,
                'error'    =>  0,
            ];

            foreach ($request->input('entries') as $id) {
                $entity = $this->model::find($id);
                if ($entity instanceof Printer) {
                    $entity->delete();
                    $status['success']++;
                } else {
                    $status['error']++;
                }
            }

            return $status;
        }

        if ($request->input('action') == 'enable_printers') {

            /**
             * Will control if the user has the permissoin to do that.
             */
            if ($this->permissions['update'] !== false) {
                ns()->restrict($this->permissions['update']);
            } else {
                throw new NotAllowedException;
            }

            $status = [
                'success'   =>  0,
                'error'    =>  0,
            ];

            foreach ($request->input('entries') as $id) {
                $entity = $this->model::find($id);
                if ($entity instanceof Printer) {
                    
                    $entity->status     =   Printer::ENABLED;
                    $entity->save();

                    $status['success']++;
                } else {
                    $status['error']++;
                }
            }

            return $status;
        }

        if ($request->input('action') == 'disable_printers') {

            /**
             * Will control if the user has the permissoin to do that.
             */
            if ($this->permissions['update'] !== false) {
                ns()->restrict($this->permissions['update']);
            } else {
                throw new NotAllowedException;
            }

            $status = [
                'success'   =>  0,
                'error'    =>  0,
            ];

            foreach ($request->input('entries') as $id) {
                $entity = $this->model::find($id);
                if ($entity instanceof Printer) {
                    
                    $entity->status     =   Printer::DISABLED;
                    $entity->save();

                    $status['success']++;
                } else {
                    $status['error']++;
                }
            }

            return $status;
        }

        return Hook::filter($this->namespace.'-catch-action', false, $request);
    }

    /**
     * get Links
     *
     * @return  array of links
     */
    public function getLinks(): array
    {
        return  [
            'list'      =>  ns()->url('dashboard/'.'print-adapter/printers'),
            'create'    =>  ns()->url('dashboard/'.'print-adapter/printers/create'),
            'edit'      =>  ns()->url('dashboard/'.'print-adapter/printers/edit/'),
            'post'      =>  ns()->url('api/crud/'.'ns.printers'),
            'put'       =>  ns()->url('api/crud/'.'ns.printers/{id}'.''),
        ];
    }

    /**
     * Get Bulk actions
     *
     * @return  array of actions
     **/
    public function getBulkActions(): array
    {
        return Hook::filter($this->namespace.'-bulk', [
            [
                'label'         =>  __('Delete Selected Printers'),
                'identifier'    =>  'delete_selected',
                'url'           =>  ns()->route('ns.api.crud-bulk-actions', [
                    'namespace' =>  $this->namespace,
                ]),
            ], [
                'label'         =>  __('Enable Printers'),
                'identifier'    =>  'enable_printers',
                'url'           =>  ns()->route('ns.api.crud-bulk-actions', [
                    'namespace' =>  $this->namespace,
                ]),
            ], [
                'label'         =>  __('Disable Printers'),
                'identifier'    =>  'disable_printers',
                'url'           =>  ns()->route('ns.api.crud-bulk-actions', [
                    'namespace' =>  $this->namespace,
                ]),
            ],
        ]);
    }

    /**
     * get exports
     *
     * @return  array of export formats
     **/
    public function getExports()
    {
        return [];
    }

    public function getHeaderButtons(): array
    {
        return [
            'createPrintersFromLocalSourceComponent'
        ];
    }
}
