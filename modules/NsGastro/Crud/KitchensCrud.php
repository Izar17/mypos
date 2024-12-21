<?php

namespace Modules\NsGastro\Crud;

use App\Exceptions\NotAllowedException;
use App\Models\ProductCategory;
use App\Models\User;
use App\Services\CrudEntry;
use App\Services\CrudService;
use App\Services\Helper;
use App\Services\ModulesService;
use App\Services\Users;
use Illuminate\Http\Request;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\KitchenCategory;
use Modules\NsGastro\Models\KitchenPrinter;
use Modules\NsPrintAdapter\Models\Printer;
use TorMorten\Eventy\Facades\Events as Hook;

class KitchensCrud extends CrudService
{
    /**
     * define the base table
     *
     * @param  string
     */
    protected $table = 'nexopos_gastro_kitchens';

    /**
     * default slug
     *
     * @param  string
     */
    protected $slug = '/restaurant/kitchens';

    /**
     * Define namespace
     *
     * @param  string
     */
    protected $namespace = 'ns.gastro-kitchens';

    /**
     * Model Used
     *
     * @param  string
     */
    protected $model = Kitchen::class;

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
        ['nexopos_users as user', 'user.id', '=', 'nexopos_gastro_kitchens.author'],
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
    public $pick = [
        'user'  =>  ['username'],
    ];

    /**
     * Define where statement
     *
     * @var  array
     **/
    protected $listWhere = [];

    /**
     * Define where in statement
     *
     * @var  array
     */
    protected $whereIn = [];

    /**
     * Fields which will be filled during post/put
     */
    public $fillable = [
        'name', 'status', 'description', 'author', 'notification_sound', 'notification_status',
    ];

    /**
     * Return the label used for the crud
     * instance
     *
     * @return  array
     **/
    public function getLabels()
    {
        return [
            'list_title'            =>  __m('Kitchens List', 'NsGastro'),
            'list_description'      =>  __m('Display all kitchens.', 'NsGastro'),
            'no_entry'              =>  __m('No kitchens has been registered', 'NsGastro'),
            'create_new'            =>  __m('Add a new kitchen', 'NsGastro'),
            'create_title'          =>  __m('Create a new kitchen', 'NsGastro'),
            'create_description'    =>  __m('Register a new kitchen and save it.', 'NsGastro'),
            'edit_title'            =>  __m('Edit kitchen', 'NsGastro'),
            'edit_description'      =>  __m('Modify  Kitchen.', 'NsGastro'),
            'back_to_list'          =>  __m('Return to Kitchens', 'NsGastro'),
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
        $form   =   [
            'main' =>  [
                'label'         =>  __m('Name', 'NsGastro'),
                'name'          =>  'name',
                'value'         =>  $entry->name ?? '',
                'validation'    =>  'required',
                'description'   =>  __m('Provide a name to the resource.', 'NsGastro'),
            ],
            'tabs'  =>  [
                'general'   =>  [
                    'label'     =>  __m('General', 'NsGastro'),
                    'fields'    =>  [
                        [
                            'type'          =>  'select',
                            'options'       =>  Helper::kvToJsOptions([
                                'enabled'   =>  __m('Enabled', 'NsGastro'),
                                'disabled'  =>  __m('Disabled', 'NsGastro'),
                            ]),
                            'validation'    =>  'required',
                            'description'   =>  __m('Define the actual kitchen status. A "disabled" kitchen can be used.', 'NsGastro'),
                            'name'          =>  'status',
                            'label'         =>  __m('Status', 'NsGastro'),
                            'value'         =>  $entry->status ?? '',
                        ], [
                            'type'          =>  'multiselect',
                            'options'       =>  Helper::toJsOptions(ProductCategory::get(), ['id', 'name']),
                            'description'   =>  __m('Select which categories are handled by the kitchen. If no category is selected, the kitchen handle all categories.', 'NsGastro'),
                            'name'          =>  'categories',
                            'validation'    =>  'required',
                            'label'         =>  __m('Supported Categories', 'NsGastro'),
                            'value'         =>  $entry !== null ? (
                                    $entry->categories
                                        ->map(fn ($category) => $category->category_id)
                                        ->toArray() ?? ''
                                ) : '',
                        ], [
                            'label'     =>  __m('New Order Sound', 'NsGastro'),
                            'name'      =>  'notification_status',
                            'value'     =>  $entry !== null ? $entry->notification_status : 'disabled',
                            'type'      =>  'switch',
                            'options'   =>  Helper::kvToJsOptions([
                                'enabled'       =>  __m('Enabled', 'NsGastro'),
                                'disabled'      =>  __m('Disabled', 'NsGastro'),
                            ]),
                            'description'   =>  __m('Define if the notification should be enabled on this kitchen.', 'NsGastro'),
                        ], [
                            'label'     =>  __m('New Order Sound', 'NsGastro'),
                            'name'      =>  'notification_sound',
                            'value'     =>  $entry !== null ? $entry->notification_sound : '',
                            'type'      =>  'select-audio',
                            'options'   =>  Helper::kvToJsOptions(Hook::filter('ns.gastro.kitchens-sound', [
                                asset('modules/nsgastro/mp3/marimba.mp3')   =>  __m('Marimba', 'NsGastro'),
                                asset('modules/nsgastro/mp3/bell.mp3')   =>  __m('Bell', 'NsGastro'),
                                asset('modules/nsgastro/mp3/notify.mp3')   =>  __m('Notify', 'NsGastro'),
                            ])),
                            'description'   =>  __m('The availability determine whether a table can be set as busy or not.', 'NsGastro'),
                        ], [
                            'type'  =>  'textarea',
                            'name'  =>  'description',
                            'label' =>  __m('Description', 'NsGastro'),
                            'value' =>  $entry->description ?? '',
                        ],
                    ],
                ],
            ],
        ];

        /**
         * @var ModulesService
         */
        $moduleService = app()->make( ModulesService::class );

        /**
         * The Printer field will be loaded
         * if the NsPrintAdapter module is enabled.
         */
        if ( $moduleService->getIfEnabled( 'NsPrintAdapter' ) ) {
            $form[ 'tabs' ][ 'general' ][ 'fields' ]     =     array_insert_before( $form[ 'tabs' ][ 'general' ][ 'fields' ], 3, [
                [
                    'type'          =>  'multiselect',
                    'options'       =>  Helper::toJsOptions( Printer::enabled()->get(), [ 'id', 'name' ]),
                    'description'   =>  __m('Assign a printer to the kichen. (NPS Adapter required)', 'NsGastro'),
                    'name'          =>  'printers',
                    'label'         =>  __m('Printers', 'NsGastro'),
                    'value'         =>  $entry && ! empty( $entry->printers ) !== null ?
                        $entry->printers->map( fn( $printer ) => intval( $printer->printer_id ) )->toArray() : '',
                ], 
            ]);
        }

        return $form;
    }

    /**
     * Filter POST input fields
     *
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPostInputs($inputs)
    {
        unset($inputs['categories']);
        unset($inputs['printers']);

        return $inputs;
    }

    /**
     * Filter PUT input fields
     *
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPutInputs($inputs, Kitchen $entry)
    {
        unset($inputs['categories']);
        unset($inputs['printers']);

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
     * @param  Kitchen  $entry
     * @return  void
     */
    public function afterPost($inputs, Kitchen $entry)
    {
        $this->saveCategories($inputs, $entry);
        $this->savePrinters($inputs, $entry);

        return $inputs;
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
    public function beforePut($inputs, $entry)
    {
        if ($this->permissions['update'] !== false) {
            ns()->restrict($this->permissions['update']);
        } else {
            throw new NotAllowedException;
        }

        return $inputs;
    }

    private function savePrinters($inputs, $kitchen)
    {
        // delete all defined printers
        KitchenPrinter::where('kitchen_id', $kitchen->id)->delete();

        if (! empty($inputs['printers'])) {
            foreach ($inputs['printers'] as $printerID) {
                $printer    =   Printer::findOrFail( $printerID );
                
                $kitchePrinter = new KitchenPrinter;
                $kitchePrinter->printer_name = $printer->name;
                $kitchePrinter->printer_id      =   $printer->id;
                $kitchePrinter->kitchen_id = $kitchen->id;
                $kitchePrinter->save();
            }
        }
    }

    /**
     * After updating a record
     *
     * @param  Request  $request
     * @param  object entry
     * @return  void
     */
    public function afterPut($inputs, $entry)
    {
        $entry->categories->each(function ($category) {
            $category->delete();
        });

        $this->saveCategories($inputs, $entry);
        $this->savePrinters($inputs, $entry);

        return $inputs;
    }

    private function saveCategories($inputs, $entry)
    {
        if ( ! empty( $inputs['categories'] ) ) {
            foreach ($inputs['categories'] as $category) {
                $kitchenCategory = new KitchenCategory;
                $kitchenCategory->kitchen_id = $entry->id;
                $kitchenCategory->category_id = $category;
                $kitchenCategory->save();
            }
        }
    }

    /**
     * Before Delete
     *
     * @return  void
     */
    public function beforeDelete($namespace, $id, $model)
    {
        if ($namespace == 'ns.gastro-kitchens') {
            /**
             *  Perform an action before deleting an entry
             *  In case something wrong, this response can be returned
             *
             *  return response([
             *      'status'    =>  'danger',
             *      'message'   =>  __m( 'You\re not allowed to do that.' )
             *  ], 403 );
             **/
            if ($this->permissions['delete'] !== false) {
                ns()->restrict($this->permissions['delete']);
            } else {
                throw new NotAllowedException;
            }

            $model->categories->each(fn ($category) => $category->delete());
        }
    }

    /**
     * Define Columns
     */
    public function getColumns(): array
    {
        return [
            'name'  =>  [
                'label'  =>  __m('Name', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'status'  =>  [
                'label'  =>  __m('Status', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'user_username'  =>  [
                'label'  =>  __m('Author', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'created_at'  =>  [
                'label'  =>  __m('Created At', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
        ];
    }

    /**
     * Define actions
     */
    public function setActions( CrudEntry $entry ): CrudEntry
    {
        // Don't overwrite
        $entry->{ '$checked' } = false;
        $entry->{ '$toggled' } = false;
        $entry->{ '$id' } = $entry->id;

        // you can make changes here
        $entry->action( 
            label: __m('Edit', 'NsGastro'),
            identifier: 'edit',
            type: 'GOTO',
            url: ns()->url('/dashboard'.$this->slug.'/edit/'.$entry->id)
        );

        $entry->action(
            label: __m('Delete', 'NsGastro'),
            identifier: 'delete',
            type: 'DELETE',
            url: ns()->url('/api/crud/ns.gastro-kitchens/'.$entry->id),
            confirm: [
                'message'  =>  __m('Would you like to delete this ?', 'NsGastro'),
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
                if ($entity instanceof Kitchen) {
                    /**
                     * delete all linked categories
                     */
                    $entity->categories->each(fn ($category) => $category->delete());

                    $entity->delete();
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
            'list'      =>  ns()->url('dashboard/'.'restaurant/kitchens'),
            'create'    =>  ns()->url('dashboard/'.'restaurant/kitchens/create'),
            'edit'      =>  ns()->url('dashboard/'.'restaurant/kitchens/edit/'),
            'post'      =>  ns()->url('api/crud/'.'ns.gastro-kitchens'),
            'put'       =>  ns()->url('api/crud/'.'ns.gastro-kitchens/{id}'.''),
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
                'label'         =>  __m('Delete Selected Groups', 'NsGastro'),
                'identifier'    =>  'delete_selected',
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
}
