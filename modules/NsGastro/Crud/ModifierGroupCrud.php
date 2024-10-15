<?php

namespace Modules\NsGastro\Crud;

use App\Exceptions\NotAllowedException;
use App\Models\Product;
use App\Models\User;
use App\Services\CrudEntry;
use App\Services\CrudService;
use App\Services\Helper;
use App\Services\Users;
use Illuminate\Http\Request;
use Modules\NsGastro\Models\ModifierGroup;
use TorMorten\Eventy\Facades\Events as Hook;

class ModifierGroupCrud extends CrudService
{
    /**
     * define the base table
     *
     * @param  string
     */
    protected $table = 'nexopos_gastro_modifiers_groups';

    /**
     * default slug
     *
     * @param  string
     */
    protected $slug = 'restaurant/modifiers-groups';

    /**
     * Define namespace
     *
     * @param  string
     */
    protected $namespace = 'ns.gastro-modifiers-groups';

    /**
     * Model Used
     *
     * @param  string
     */
    protected $model = ModifierGroup::class;

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
        ['nexopos_users as user', 'user.id', '=', 'nexopos_gastro_modifiers_groups.author'],
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
    public $fillable = [];

    /**
     * Return the label used for the crud
     * instance
     *
     * @return  array
     **/
    public function getLabels()
    {
        return [
            'list_title'            =>  __m('Modifier Groups List', 'NsGastro'),
            'list_description'      =>  __m('Display all modifier groups.', 'NsGastro'),
            'no_entry'              =>  __m('No modifier groups has been registered', 'NsGastro'),
            'create_new'            =>  __m('Add a new modifier group', 'NsGastro'),
            'create_title'          =>  __m('Create a new modifier group', 'NsGastro'),
            'create_description'    =>  __m('Register a new modifier group and save it.', 'NsGastro'),
            'edit_title'            =>  __m('Edit modifier group', 'NsGastro'),
            'edit_description'      =>  __m('Modify  Modifier Group.', 'NsGastro'),
            'back_to_list'          =>  __m('Return to Modifier Groups', 'NsGastro'),
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
                'label'         =>  __m('Name', 'NsGastro'),
                'name'          =>  'name',
                'value'         =>  $entry->name ?? '',
                'description'   =>  __m('Provide a name to the resource.', 'NsGastro'),
            ],
            'tabs'  =>  [
                'general'   =>  [
                    'label'     =>  __m('General', 'NsGastro'),
                    'fields'    =>  [
                        [
                            'type'          =>  'switch',
                            'name'          =>  'forced',
                            'options'       =>  Helper::kvToJsOptions([__m('No', 'NsGastro'), __m('Yes', 'NsGastro')]),
                            'label'         =>  __m('Forced', 'NsGastro'),
                            'description'   =>  __m('If set to yes, the modifiers will require a selection while being processed.', 'NsGastro'),
                            'value'         =>  $entry->forced ?? 0,
                        ], [
                            'type'          =>  'switch',
                            'name'          =>  'countable',
                            'options'       =>  Helper::kvToJsOptions([__m('No', 'NsGastro'), __m('Yes', 'NsGastro')]),
                            'label'         =>  __m('Countable', 'NsGastro'),
                            'description'   =>  __m('If the group is countable, every modifiers will require quantity before being added.', 'NsGastro'),
                            'value'         =>  $entry->countable ?? 0,
                        ], [
                            'type'          =>  'switch',
                            'name'          =>  'multiselect',
                            'options'       =>  Helper::kvToJsOptions([__m('No', 'NsGastro'), __m('Yes', 'NsGastro')]),
                            'label'         =>  __m('Multiselect', 'NsGastro'),
                            'description'   =>  __m('Define wether more than one modifier can be selected at the time.', 'NsGastro'),
                            'value'         =>  $entry->multiselect ?? 1,
                        ], [
                            'type'          =>  'textarea',
                            'name'          =>  'description',
                            'label'         =>  __m('Description', 'NsGastro'),
                            'description'   =>  __m('Provide mode information about the modifier group.', 'NsGastro'),
                            'value'         =>  $entry->description ?? '',
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
        return $inputs;
    }

    /**
     * Filter PUT input fields
     *
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPutInputs($inputs, ModifierGroup $entry)
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
     * @param  ModifierGroup  $entry
     * @return  void
     */
    public function afterPost($request, ModifierGroup $entry)
    {
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
        return $request;
    }

    /**
     * Before Delete
     *
     * @return  void
     */
    public function beforeDelete($namespace, $id, $model)
    {
        if ($namespace == 'ns.gastro-modifiers-groups') {
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
                '$sort'         =>  true,
            ],
            'forced'  =>  [
                'label'  =>  __m('Forced', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  true,
            ],
            'multiselect'  =>  [
                'label'  =>  __m('Multiselect', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  true,
            ],
            'countable'  =>  [
                'label'  =>  __m('Countable', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  true,
            ],
            'modifiers'  =>  [
                'label'  =>  __m('Total Modifiers', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  true,
            ],
            'user_username' =>  [
                'label'     =>  __m('Author', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  true,
            ],
            'created_at' =>  [
                'label'     =>  __m('Created On', 'NsGastro'),
                '$direction'    =>  '',
                '$sort'         =>  true,
            ],
        ];
    }

    public function hook($query): void
    {
        $query->orderBy('updated_at', 'desc');
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

        $entry->modifiers   = Product::where( 'modifiers_group_id', $entry->id )->count();
        $entry->multiselect = $entry->multiselect ? __m('Yes', 'NsGastro') : __m('No', 'NsGastro');
        $entry->countable = $entry->countable ? __m('Yes', 'NsGastro') : __m('No', 'NsGastro');
        $entry->forced = $entry->forced ? __m('Yes', 'NsGastro') : __m('No', 'NsGastro');

        // you can make changes here
        $entry->action(
            label: __m( 'Edit', 'NsGastro' ),
            identifier: 'edit',
            type: 'GOTO',
            url: ns()->url( '/dashboard/'.$this->slug.'/edit/'.$entry->id )
        );

        $entry->action(
            label: __m( 'Modifiers', 'NsGastro' ),
            identifier: 'modifiers',
            type: 'GOTO',
            url: ns()->url( '/dashboard/'.$this->slug. '/' .$entry->id . '/modifiers' )
        );

        $entry->action(
            label: __m( 'Delete', 'NsGastro' ),
            identifier: 'delete',
            type: 'DELETE',
            url: ns()->url( '/api/crud/'.$this->namespace.'/'.$entry->id ),
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
                if ($entity instanceof ModifierGroup) {
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
            'list'      =>  ns()->url('dashboard/'.'restaurant/modifiers-groups'),
            'create'    =>  ns()->url('dashboard/'.'restaurant/modifiers-groups/create'),
            'edit'      =>  ns()->url('dashboard/'.'restaurant/modifiers-groups/edit/'),
            'post'      =>  ns()->url('api/crud/'.'ns.gastro-modifiers-groups'),
            'put'       =>  ns()->url('api/crud/'.'ns.gastro-modifiers-groups/{id}'.''),
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
