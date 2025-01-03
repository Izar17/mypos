<?php
namespace Modules\NsGastro\Crud;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\CrudService;
use App\Services\CrudEntry;
use App\Exceptions\NotAllowedException;
use App\Models\Product;
use TorMorten\Eventy\Facades\Events as Hook;
use Modules\NsGastro\Models\ProductModifierGroup;

class ModifierGroupProductCrud extends CrudService
{
    /**
     * Defines if the crud class should be automatically discovered by NexoPOS.
     * If set to "true", you won't need to register that class on the "CrudServiceProvider".
     */
    const AUTOLOAD = true;

    /**
     * define the base table
     * @param string
     */
    protected $table = 'nexopos_products';

    /**
     * default slug
     * @param string
     */
    protected $slug = 'modifiers-groups-products';

    /**
     * Define namespace
     * @param string
     */
    protected $namespace = 'ns.gastro-modifiers-groups-products';

    /**
     * To be able to autoload the class, we need to define
     * the identifier on a constant.
     */
    const IDENTIFIER = 'ns.gastro-modifiers-groups-products';

    /**
     * Model Used
     * @param string
     */
    protected $model = Product::class;

    /**
     * Define permissions
     * @param array
     */
    protected $permissions  =   [
        'create'    =>  true,
        'read'      =>  true,
        'update'    =>  true,
        'delete'    =>  true,
    ];

    /**
     * Adding relation
     * Example : [ 'nexopos_users as user', 'user.id', '=', 'nexopos_orders.author' ]
     * @param array
     */
    public $relations   =  [
        [ 'nexopos_gastro_modifiers_groups as modifier_group', 'modifier_group.id', '=', 'nexopos_products.modifiers_group_id' ],
    ];

    /**
     * all tabs mentionned on the tabs relations
     * are ignored on the parent model.
     */
    protected $tabsRelations    =   [
        // 'tab_name'      =>      [ YourRelatedModel::class, 'localkey_on_relatedmodel', 'foreignkey_on_crud_model' ],
    ];

    /**
     * Export Columns defines the columns that
     * should be included on the exported csv file.
     */
    protected $exportColumns = []; // @getColumns will be used by default.

    /**
     * Pick
     * Restrict columns you retrieve from relation.
     * Should be an array of associative keys, where
     * keys are either the related table or alias name.
     * Example : [
     *      'user'  =>  [ 'username' ], // here the relation on the table nexopos_users is using "user" as an alias
     * ]
     */
    public $pick = [
        'product'   =>  [ 'name' ],
        'modifier_group'   =>  [ 'name' ],
    ];

    /**
     * Define where statement
     * @var array
    **/
    protected $listWhere = [];

    /**
     * Define where in statement
     * @var array
     */
    protected $whereIn = [];

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
     * Determine if the options column should display
     * before the crud columns
     */
    protected $prependOptions = false;

    /**
     * Will make the options column available per row if
     * set to "true". Otherwise it will be hidden.
     */
    protected $showOptions = true;

    /**
     * Return the label used for the crud object.
    **/
    public function getLabels(): array
    {
        return [
            'list_title'            =>  __( 'Modifier Group Products List' ),
            'list_description'      =>  __( 'Display all Modifier Group Products.' ),
            'no_entry'              =>  __( 'No Modifier Group Products has been registered' ),
            'create_new'            =>  __( 'Add a new Modifier Group Product' ),
            'create_title'          =>  __( 'Create a new Modifier Group Product' ),
            'create_description'    =>  __( 'Register a new Modifier Group Product and save it.' ),
            'edit_title'            =>  __( 'Edit Modifier Group Product' ),
            'edit_description'      =>  __( 'Modify Modifier Group Products.' ),
            'back_to_list'          =>  __( 'Return to Modifier Group Products' ),
        ];
    }

    public function hook( $query ): void
    {
        $query->where( 'nexopos_products.modifiers_group_id', request()->input( 'modifiers_group_id' ) );
    }

    /**
     * Defines the forms used to create and update entries.
     */
    public function getForm( ProductModifierGroup $entry = null ): array
    {
        return [
            'main' =>  [
                'label'         =>  __( 'Name' ),
                'name'          =>  'name',
                'value'         =>  $entry->name ?? '',
                'description'   =>  __( 'Provide a name to the resource.' )
            ],
            'tabs'  =>  [
                'general'   =>  [
                    'label'     =>  __( 'General' ),
                    'fields'    =>  [
                        [
                            'type'  =>  'text',
                            'name'  =>  'id',
                            'label' =>  __( 'Id' ),
                            'value' =>  $entry->id ?? '',
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'product_id',
                            'label' =>  __( 'Product_id' ),
                            'value' =>  $entry->product_id ?? '',
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'modifier_group_id',
                            'label' =>  __( 'Modifier_group_id' ),
                            'value' =>  $entry->modifier_group_id ?? '',
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'sort_order',
                            'label' =>  __( 'Sort_order' ),
                            'value' =>  $entry->sort_order ?? '',
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'created_at',
                            'label' =>  __( 'Created_at' ),
                            'value' =>  $entry->created_at ?? '',
                        ], [
                            'type'  =>  'text',
                            'name'  =>  'updated_at',
                            'label' =>  __( 'Updated_at' ),
                            'value' =>  $entry->updated_at ?? '',
                        ],                     ]
                ]
            ]
        ];
    }

    /**
     * Filter POST input fields
     * @param array of fields
     * @return array of fields
     */
    public function filterPostInputs( $inputs ): array
    {
        return $inputs;
    }

    /**
     * Filter PUT input fields
     * @param array of fields
     * @return array of fields
     */
    public function filterPutInputs( array $inputs, ProductModifierGroup $entry )
    {
        return $inputs;
    }

    /**
     * Trigger actions that are executed before the
     * crud entry is created.
     */
    public function beforePost( array $request ): array
    {
        $this->allowedTo( 'create' );

        return $request;
    }

    /**
     * Trigger actions that will be executed 
     * after the entry has been created.
     */
    public function afterPost( array $request, ProductModifierGroup $entry ): array
    {
        return $request;
    }


    /**
     * A shortcut and secure way to access
     * senstive value on a read only way.
     */
    public function get( string $param ): mixed
    {
        switch( $param ) {
            case 'model' : return $this->model ; break;
        }
    }

    /**
     * Trigger actions that are executed before
     * the crud entry is updated.
     */
    public function beforePut( array $request, ProductModifierGroup $entry ): array
    {
        $this->allowedTo( 'update' );

        return $request;
    }

    /**
     * This trigger actions that are executed after
     * the crud entry is successfully updated.
     */
    public function afterPut( array $request, ProductModifierGroup $entry ): array
    {
        return $request;
    }

    /**
     * This triggers actions that will be executed ebfore
     * the crud entry is deleted.
     */
    public function beforeDelete( $namespace, $id, $model ): void
    {
        if ( $namespace == 'ns.gastro-modifiers-groups-products' ) {
            /**
             *  Perform an action before deleting an entry
             *  In case something wrong, this response can be returned
             *
             *  return response([
             *      'status'    =>  'danger',
             *      'message'   =>  __( 'You\re not allowed to do that.' )
             *  ], 403 );
            **/
            if ( $this->permissions[ 'delete' ] !== false ) {
                ns()->restrict( $this->permissions[ 'delete' ] );
            } else {
                throw new NotAllowedException;
            }
        }
    }

    /**
     * Define columns and how it is structured.
     */
    public function getColumns(): array
    {
        return [
            'name'  =>  [
                'label'  =>  __( 'Product' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'modifier_group_name'  =>  [
                'label'  =>  __( 'Modifier Group' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'updated_at'  =>  [
                'label'  =>  __( 'Updated_at' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
        ];
    }

    /**
     * Define row actions.
     */
    public function addActions( CrudEntry $entry ): CrudEntry
    {
        /**
         * Declaring entry actions
         */
        $entry->action( 
            identifier: 'edit',
            label: __( 'Edit' ),
            url: ns()->url( '/dashboard/products/edit/' . $entry->id )
        );
        
        $entry->action( 
            identifier: 'delete',
            label: __( 'Delete' ),
            type: 'DELETE',
            url: ns()->url( '/api/crud/ns.gastro-modifiers-groups-products/' . $entry->id ),
            confirm: [
                'message'  =>  __( 'Would you like to delete this ?' ),
            ]
        );
        
        return $entry;
    }


    /**
     * trigger actions that are executed
     * when a bulk actio is posted.
     */
    public function bulkAction( Request $request ): array
    {
        /**
         * Deleting licence is only allowed for admin
         * and supervisor.
         */

        if ( $request->input( 'action' ) == 'delete_selected' ) {

            /**
             * Will control if the user has the permissoin to do that.
             */
            if ( $this->permissions[ 'delete' ] !== false ) {
                ns()->restrict( $this->permissions[ 'delete' ] );
            } else {
                throw new NotAllowedException;
            }

            $status     =   [
                'success'   =>  0,
                'error'    =>  0
            ];

            foreach ( $request->input( 'entries' ) as $id ) {
                $entity     =   $this->model::find( $id );
                if ( $entity instanceof ProductModifierGroup ) {
                    $entity->delete();
                    $status[ 'success' ]++;
                } else {
                    $status[ 'error' ]++;
                }
            }
            return $status;
        }

        return Hook::filter( $this->namespace . '-catch-action', false, $request );
    }

    /**
     * Defines links used on the CRUD object.
     */
    public function getLinks(): array
    {
        return  [
            'list'      =>  ns()->url( 'dashboard/' . 'modifiers-groups-products' ),
            'create'    =>  ns()->url( 'dashboard/' . 'modifiers-groups-products/create' ),
            'edit'      =>  ns()->url( 'dashboard/' . 'modifiers-groups-products/edit/' ),
            'post'      =>  ns()->url( 'api/crud/' . 'ns.gastro-modifiers-groups-products' ),
            'put'       =>  ns()->url( 'api/crud/' . 'ns.gastro-modifiers-groups-products/{id}' . '' ),
        ];
    }

    /**
     * Defines the bulk actions.
    **/
    public function getBulkActions(): array
    {
        return Hook::filter( $this->namespace . '-bulk', [
            [
                'label'         =>  __( 'Delete Selected Entries' ),
                'identifier'    =>  'delete_selected',
                'url'           =>  ns()->route( 'ns.api.crud-bulk-actions', [
                    'namespace' =>  $this->namespace
                ])
            ]
        ]);
    }

    /**
     * Defines the export configuration.
    **/
    public function getExports(): array
    {
        return [];
    }
}
