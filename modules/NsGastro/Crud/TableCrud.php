<?php

namespace Modules\NsGastro\Crud;

use App\Exceptions\NotAllowedException;
use App\Models\User;
use App\Services\CrudEntry;
use App\Services\CrudService;
use App\Services\Helper;
use App\Services\Users;
use Illuminate\Http\Request;
use Modules\NsGastro\Models\Area;
use Modules\NsGastro\Models\Table;
use TorMorten\Eventy\Facades\Events as Hook;

class TableCrud extends CrudService
{
    /**
     * define the base table
     *
     * @param  string
     */
    protected $table = 'nexopos_gastro_tables';

    /**
     * default slug
     *
     * @param  string
     */
    protected $slug = 'restaurant/tables';

    /**
     * Define namespace
     *
     * @param  string
     */
    protected $namespace = 'ns.gastro-tables';

    /**
     * Model Used
     *
     * @param  string
     */
    protected $model = Table::class;

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
        ['nexopos_users as user', 'nexopos_gastro_tables.author', '=', 'user.id'],
        'leftJoin'  =>  [
            [ 'nexopos_gastro_areas as area', 'nexopos_gastro_tables.area_id', '=', 'area.id' ]
        ],
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
        'area'  =>  ['name'],
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
    public $fillable = [];

    public $skippable = [ 'assigned_waiters' ];

    /**
     * Return the label used for the crud
     * instance
     *
     * @return  array
     **/
    public function getLabels()
    {
        return [
            'list_title'            =>  __m('Tables List', 'NsGastro'),
            'list_description'      =>  __m('Display all tables.', 'NsGastro'),
            'no_entry'              =>  __m('No tables has been registered', 'NsGastro'),
            'create_new'            =>  __m('Add a new table', 'NsGastro'),
            'create_title'          =>  __m('Create a new table', 'NsGastro'),
            'create_description'    =>  __m('Register a new table and save it.', 'NsGastro'),
            'edit_title'            =>  __m('Edit table', 'NsGastro'),
            'edit_description'      =>  __m('Modify  Table.', 'NsGastro'),
            'back_to_list'          =>  __m('Return to Tables', 'NsGastro'),
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
        $form = [
            'main' =>  [
                'label'         =>  __m('Name', 'NsGastro'),
                'name'          =>  'name',
                'value'         =>  $entry->name ?? '',
                'description'   =>  __m('Provide a name to the table.', 'NsGastro'),
            ],
            'tabs'  =>  [
                'general'   =>  [
                    'label'     =>  __m('General', 'NsGastro'),
                    'fields'    =>  [
                        [
                            'type'  =>  'media',
                            'name'  =>  'preview',
                            'label' =>  __m('Preview', 'NsGastro'),
                            'value' =>  $entry->preview ?? '',
                            'description'   =>  __m('Provide a preview of the table might be used for booking.', 'NsGastro'),
                        ], [
                            'type'  =>  'select',
                            'options'   =>  Helper::kvToJsOptions([
                                'available'     =>  __m('Available', 'NsGastro'),
                                'unavailable'   =>  __m('Unavailable', 'NsGastro'),
                            ]),
                            'name'  =>  'status',
                            'validation'    =>  'required',
                            'label' =>  __m('Status', 'NsGastro'),
                            'value' =>  $entry->status ?? '',
                            'description'   =>  __m('Set if the table is available or not.', 'NsGastro'),
                        ], [
                            'type'  =>  'switch',
                            'name'  =>  'allow_multi_clients',
                            'validation'    =>  'required',
                            'label' =>  __m('Multiple Clients', 'NsGastro'),
                            'options'   =>  Helper::kvToJsOptions([
                                true    =>  __m('Yes', 'NsGastro'),
                                false   =>  __m('No', 'NsGastro'),
                            ]),
                            'value' =>  $entry->allow_multi_clients ?? '',
                            'description'   =>  __m('Determine if the table allow multiple customers.', 'NsGastro'),
                        ], [
                            'type'  =>  'switch',
                            'name'  =>  'busy',
                            'validation'    =>  'required',
                            'label' =>  __m('Availability', 'NsGastro'),
                            'options'   =>  Helper::kvToJsOptions([
                                true    =>  __m('Busy', 'NsGastro'),
                                false   =>  __m('Available', 'NsGastro'),
                            ]),
                            'value' =>  $entry->busy ?? '',
                            'description'   =>  __m('Determine if the table is available or busy.', 'NsGastro'),
                        ], [
                            'type'  =>  'number',
                            'name'  =>  'seats',
                            'validation'    =>  'required',
                            'label' =>  __m('Seats', 'NsGastro'),
                            'description'   =>  __m('Determine how many seats are available.', 'NsGastro'),
                            'value' =>  $entry->seats ?? '',
                        ], [
                            'type'  =>  'datetime',
                            'name'  =>  'booking_starts_at',
                            'label' =>  __m('Booking Ends', 'NsGastro'),
                            'description'   =>  __m('Determin when the booking starts.', 'NsGastro'),
                            'value' =>  $entry->booking_starts_at ?? '',
                        ], [
                            'type'  =>  'datetime',
                            'name'  =>  'booking_ends_at',
                            'label' =>  __m('Booking Starts', 'NsGastro'),
                            'description'   =>  __m('If the table is booked, set when it ends.', 'NsGastro'),
                            'value' =>  $entry->booking_ends_at ?? '',
                        ], [
                            'type'  =>  'textarea',
                            'name'  =>  'description',
                            'label' =>  __m('Description', 'NsGastro'),
                            'description'   =>  __m('Further details about the table', 'NsGastro'),
                            'value' =>  $entry->description ?? '',
                        ],
                    ],
                ],
            ],
        ];

        if ( ( bool ) ns()->option->get( 'ns_gastro_tables_assignation_enabled' ) ) {
            $form[ 'tabs' ][ 'general' ][ 'fields' ]   =   array_insert_after( $form[ 'tabs' ][ 'general' ][ 'fields' ], 1, [
                [
                    'type'  =>  'multiselect',
                    'name'  =>  'assigned_waiters',
                    'label' =>  __m('Assigned Waiters', 'NsGastro'),
                    'description'   =>  __m('Select the waiters assigned to this table.', 'NsGastro'),
                    'options'   =>  Helper::toJsOptions(User::get(), fn( $user ) => [
                        'label' => $user->first_name.' '.$user->last_name . ' (' . $user->username . ')',
                        'value' => $user->id,
                    ]),
                    'value' =>  $entry !== null ? $entry->assignedWaiters()->get()->map( fn( $assignedWaiter ) => $assignedWaiter->user_id ) : [],
                    'multiple'  =>  true,
                ],
            ]);
        }

        if ( ( bool ) ns()->option->get( 'ns_gastro_areas_enabled' ) ) {
            $form[ 'tabs' ][ 'general' ][ 'fields' ]   =   array_insert_after( $form[ 'tabs' ][ 'general' ][ 'fields' ], 2, [
                [
                    'type'  =>  'select',
                    'name'  =>  'area_id',
                    'validation'    =>  'required',
                    'options'   =>  Helper::toJsOptions(Area::get(), ['id', 'name']),
                    'label' =>  __m('Area', 'NsGastro'),
                    'description'   =>  __m('Assign the table to an area (or room).', 'NsGastro'),
                    'value' =>  $entry->area_id ?? '',
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
        return $inputs;
    }

    /**
     * Filter PUT input fields
     *
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPutInputs($inputs, Table $entry)
    {
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
     * @param  Table  $entry
     * @return  void
     */
    public function afterPost($request, Table $entry)
    {
        if ( ! empty( $request[ 'assigned_waiters' ] ) ) {
            foreach( $request[ 'assigned_waiters' ] as $waiter ) {
                $entry->assignedWaiters()->create([
                    'user_id'   =>  $waiter,
                ]);
            }
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
    public function afterPut($request, $entry)
    {
        $entry->assignedWaiters()->delete();

        if ( ! empty( $request[ 'assigned_waiters' ] ) ) {
            foreach( $request[ 'assigned_waiters' ] as $waiter ) {
                $entry->assignedWaiters()->create([
                    'user_id'   =>  $waiter,
                ]);
            }
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
        if ($namespace == 'ns.gastro-tables') {
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
            'area_name'  =>  [
                'label'         =>  __m('Area', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'status'  =>  [
                'label'  =>  __m('Status', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'allow_multi_clients'  =>  [
                'label'  =>  __m('Multiple Clients', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'busy'  =>  [
                'label'  =>  __m('Availability', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'seats'  =>  [
                'label'  =>  __m('Total Seats', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'user_username'  =>  [
                'label'  =>  __m('Author', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'booking_starts_at'  =>  [
                'label'  =>  __m('Booking Starts', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'booking_ends_at'  =>  [
                'label'  =>  __m('Booking Ends', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  false,
            ],
            'created_at'  =>  [
                'label'  =>  __m('Date', 'NsGastro'),
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

        $entry->area_name = $entry->area_name ?? __m('Not Defined', 'NsGastro');
        $entry->allow_multi_clients = (bool) $entry->allow_multi_clients ? __m('Yes', 'NsGastro') : __m('No', 'NsGastro');
        $entry->busy = (bool) $entry->busy ? __m('Busy', 'NsGastro') : __m('Available', 'NsGastro');
        $entry->booking_starts_at = $entry->booking_starts_at === null ? __m('N/A', 'NsGastro') : ns()->date->getFormatted($entry->booking_starts_at);
        $entry->booking_ends_at = $entry->booking_ends_at === null ? __m('N/A', 'NsGastro') : ns()->date->getFormatted($entry->booking_ends_at);

        $entry->action(
            label: __m('Edit', 'NsGastro'),
            identifier: 'edit',
            type: 'GOTO',
            url: ns()->url('/dashboard/'.'restaurant/tables'.'/edit/'.$entry->id),
        );
        
        $entry->action( 
            label: __m( 'Delete', 'NsGastro' ),
            identifier: 'delete',
            type: 'DELETE',
            url: ns()->url( '/api/crud/'.'ns.gastro-tables/'.$entry->id ),
            confirm: [
                'message'  =>  __m( 'Would you like to delete this ?', 'NsGastro' ),
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
                if ($entity instanceof Table) {
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
            'list'      =>  ns()->url('dashboard/'.'restaurant/tables'),
            'create'    =>  ns()->url('dashboard/'.'restaurant/tables/create'),
            'edit'      =>  ns()->url('dashboard/'.'restaurant/tables/edit/'),
            'post'      =>  ns()->url('api/crud/'.'ns.gastro-tables'),
            'put'       =>  ns()->url('api/crud/'.'ns.gastro-tables/{id}'.''),
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
