<?php

namespace Modules\NsGastro\Crud;

use App\Exceptions\NotAllowedException;
use App\Models\User;
use App\Services\CrudEntry;
use App\Services\CrudService;
use App\Services\Users;
use Illuminate\Http\Request;
use Modules\NsGastro\Models\Area;
use TorMorten\Eventy\Facades\Events as Hook;

class AreaCrud extends CrudService
{
    /**
     * define the base table
     *
     * @param  string
     */
    protected $table = 'nexopos_gastro_areas';

    /**
     * default slug
     *
     * @param  string
     */
    protected $slug = 'restaurant/areas';

    /**
     * Define namespace
     *
     * @param  string
     */
    protected $namespace = 'ns.gastro-areas';

    /**
     * Model Used
     *
     * @param  string
     */
    protected $model = Area::class;

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
     *
     * @param  array
     */
    public $relations = [
        ['nexopos_users as user', 'user.id', '=', 'nexopos_gastro_areas.author'],
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
            'list_title'            =>  __m('Restaurant Areas List', 'NsGastro'),
            'list_description'      =>  __m('Display all restaurant areas.', 'NsGastro'),
            'no_entry'              =>  __m('No areas has been registered', 'NsGastro'),
            'create_new'            =>  __m('Add a new area', 'NsGastro'),
            'create_title'          =>  __m('Create a new area', 'NsGastro'),
            'create_description'    =>  __m('Register a new area and save it.', 'NsGastro'),
            'edit_title'            =>  __m('Edit area', 'NsGastro'),
            'edit_description'      =>  __m('Modify A Restaurant Area.', 'NsGastro'),
            'back_to_list'          =>  __m('Return to Restaurant Areas', 'NsGastro'),
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
                            'type'  =>  'textarea',
                            'name'  =>  'description',
                            'label' =>  __m('Description', 'NsGastro'),
                            'value' =>  $entry->description ?? '',
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
    public function filterPutInputs($inputs, Area $entry)
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
     * @param  Area  $entry
     * @return  void
     */
    public function afterPost($request, Area $entry)
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
        if ($namespace == 'ns.gastro-areas') {
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
            'user_username'  =>  [
                'label'  =>  __m('By', 'NsGastro'),
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
            identifier: 'edit',
            label:      __m('Edit', 'NsGastro'),
            type:      'GOTO',
            url:      ns()->url('/dashboard/'.'restaurant/areas'.'/edit/'.$entry->id),
        );

        $entry->action(
            identifier: 'delete',
            label:  __m('Delete', 'NsGastro'),
            type:  'DELETE',
            url:  ns()->url('/api/crud/ns.gastro-areas/'.$entry->id),
            confirm:  [
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
                if ($entity instanceof Area) {
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
            'list'      =>  ns()->url('dashboard/'.'restaurant/areas'),
            'create'    =>  ns()->url('dashboard/'.'restaurant/areas/create'),
            'edit'      =>  ns()->url('dashboard/'.'restaurant/areas/edit/'),
            'post'      =>  ns()->url('api/crud/'.'ns.gastro-areas'),
            'put'       =>  ns()->url('api/crud/'.'ns.gastro-areas/{id}'.''),
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
