<?php
namespace Modules\NsGastro\Crud;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\CrudService;
use App\Services\Users;
use App\Services\CrudEntry;
use App\Exceptions\NotAllowedException;
use App\Models\User;
use TorMorten\Eventy\Facades\Events as Hook;
use Exception;
use App\Models\OrderProduct;
use App\Services\CrudLink;
use Modules\NsGastro\Models\OrderProduct as ModelsOrderProduct;

class CanceledProductCrud extends CrudService
{
    /**
     * define the base table
     * @param  string
     */
    protected $table      =   'nexopos_orders_products';

    /**
     * default slug
     * @param  string
     */
    protected $slug   =   'restaurant/canceled-products';

    /**
     * Define namespace
     * @param  string
     */
    protected $namespace  =   'ns.gastro-canceled-products';

    /**
     * Model Used
     * @param  string
     */
    protected $model      =   OrderProduct::class;

    /**
     * Define permissions
     * @param  array
     */
    protected $permissions  =   [
        'create'    =>  false,
        'read'      =>  'gastro.manage.logs',
        'update'    =>  false,
        'delete'    =>  'gastro.manage.logs',
    ];

    /**
     * Adding relation
     * Example : [ 'nexopos_users as user', 'user.id', '=', 'nexopos_orders.author' ]
     * @param  array
     */
    public $relations   =  [
        [ 'nexopos_orders as order', 'order.id', '=', 'nexopos_orders_products.order_id' ]
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
    protected $exportColumns    =   []; // @getColumns  will be used by default.

    /**
     * Pick
     * Restrict columns you retreive from relation.
     * Should be an array of associative keys, where 
     * keys are either the related table or alias name.
     * Example : [
     *      'user'  =>  [ 'username' ], // here the relation on the table nexopos_users is using "user" as an alias
     * ]
     */
    public $pick        =   [
        'order' =>  [ 'code', 'id' ]
    ];

    /**
     * Define where statement
     * @var  array
    **/
    protected $listWhere    =   [];

    /**
     * Define where in statement
     * @var  array
     */
    protected $whereIn      =   [];

    /**
     * Fields which will be filled during post/put
     */
    
    /**
     * If few fields should only be filled
     * those should be listed here.
     */
    public $fillable    =   [];

    /**
     * If fields should be ignored during saving
     * those fields should be listed here
     */
    public $skippable   =   [];

    /**
     * Determine if the options column should display
     * before the crud columns
     */
    protected $prependOptions     =   false;

    /**
     * Return the label used for the crud 
     * instance
     * @return  array
    **/
    public function getLabels()
    {
        return [
            'list_title'            =>  __( 'Canceled Products List' ),
            'list_description'      =>  __( 'Display all canceled products.' ),
            'no_entry'              =>  __( 'No canceled products has been registered' ),
            'create_new'            =>  __( 'Add a new canceled product' ),
            'create_title'          =>  __( 'Create a new canceled product' ),
            'create_description'    =>  __( 'Register a new canceled product and save it.' ),
            'edit_title'            =>  __( 'Edit canceled product' ),
            'edit_description'      =>  __( 'Modify  Canceled Product.' ),
            'back_to_list'          =>  __( 'Return to Canceled Products' ),
        ];
    }

    /**
     * Check whether a feature is enabled
     * @return  boolean
    **/
    public function isEnabled( $feature ): bool
    {
        return false; // by default
    }

    /**
     * Fields
     * @param  object/null
     * @return  array of field
     */
    public function getForm( $entry = null ) 
    {
        return [
            'main' =>  [
                'label'         =>  __( 'Name' ),
                // 'name'          =>  'name',
                // 'value'         =>  $entry->name ?? '',
                'description'   =>  __( 'Provide a name to the resource.' )
            ],
            'tabs'  =>  [
                'general'   =>  [
                    'label'     =>  __( 'General' ),
                    'fields'    =>  [
                        // ...                 
                    ]
                ]
            ]
        ];
    }

    /**
     * Filter POST input fields
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPostInputs( $inputs )
    {
        return $inputs;
    }

    /**
     * Filter PUT input fields
     * @param  array of fields
     * @return  array of fields
     */
    public function filterPutInputs( $inputs, OrderProduct $entry )
    {
        return $inputs;
    }

    /**
     * Before saving a record
     * @param  Request $request
     * @return  void
     */
    public function beforePost( $request )
    {
        if ( $this->permissions[ 'create' ] !== false ) {
            ns()->restrict( $this->permissions[ 'create' ] );
        } else {
            throw new NotAllowedException;
        }

        return $request;
    }

    /**
     * After saving a record
     * @param  Request $request
     * @param  OrderProduct $entry
     * @return  void
     */
    public function afterPost( $request, OrderProduct $entry )
    {
        return $request;
    }

    
    /**
     * get
     * @param  string
     * @return  mixed
     */
    public function get( $param )
    {
        switch( $param ) {
            case 'model' : return $this->model ; break;
        }
    }

    /**
     * Before updating a record
     * @param  Request $request
     * @param  object entry
     * @return  void
     */
    public function beforePut( $request, $entry )
    {
        if ( $this->permissions[ 'update' ] !== false ) {
            ns()->restrict( $this->permissions[ 'update' ] );
        } else {
            throw new NotAllowedException;
        }

        return $request;
    }

    /**
     * After updating a record
     * @param  Request $request
     * @param  object entry
     * @return  void
     */
    public function afterPut( $request, $entry )
    {
        return $request;
    }

    /**
     * Before Delete
     * @return  void
     */
    public function beforeDelete( $namespace, $id, $model ) {
        if ( $namespace == 'ns.gastro-canceled-products' ) {
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
     * Define Columns
     */
    public function getColumns(): array 
    {
        return [
            'name'  =>  [
                'label'  =>  __( 'Name' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'order_code'  =>  [
                'label'  =>  __( 'Order' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'meal_placed_by_name'  =>  [
                'label'  =>  __( 'Placed By' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'meal_served_by_name'  =>  [
                'label'  =>  __( 'Served By' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'meal_canceled_by_name'  =>  [
                'label'  =>  __( 'Canceled By' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'cooking_cancelation_note'  =>  [
                'label'  =>  __( 'Reason' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'cooking_note'  =>  [
                'label'  =>  __( 'Cooking Note' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
            'updated_at'  =>  [
                'label'  =>  __( 'Last Update' ),
                '$direction'    =>  '',
                '$sort'         =>  false
            ],
        ];
    }

    public function hook( $query ): void
    {
        $query
            ->where( 'cooking_status', ModelsOrderProduct::COOKING_CANCELED )
            ->orderBy( 'updated_at', 'desc' );
    }

    /**
     * Define actions
     */
    public function setActions( CrudEntry $entry ): CrudEntry
    {
        $entry->meal_placed_by_name         =   $entry->meal_placed_by_name ?: __( 'N/A' );
        $entry->meal_canceled_by_name       =   $entry->meal_canceled_by_name ?: __( 'N/A' );
        $entry->meal_served_by_name         =   $entry->meal_served_by_name ?: __( 'N/A' );
        $entry->meal_served_by_name         =   $entry->meal_served_by_name ?: __( 'N/A' );
        $entry->cooking_cancelation_note    =   $entry->cooking_cancelation_note ?: __( 'N/A' );
        $entry->cooking_note                =   $entry->cooking_note ?: __( 'N/A' );

        $entry->order_code                  =   new CrudLink(
            label: $entry->order_code,
            href: ns()->route( 'ns.dashboard.orders-receipt', [ 'order' => $entry->order_id ])
        );

        /**
         * Declaring entry actions
         */
        $entry->addAction( 'edit', [
            'label'         =>      __( 'Edit' ),
            'namespace'     =>      'edit',
            'type'          =>      'GOTO',
            'url'           =>      ns()->url( '/dashboard/' . $this->slug . '/edit/' . $entry->id )
        ]);
        
        $entry->addAction( 'delete', [
            'label'     =>  __( 'Delete' ),
            'namespace' =>  'delete',
            'type'      =>  'DELETE',
            'url'       =>  ns()->url( '/api/crud/ns.gastro-canceled-products/' . $entry->id ),
            'confirm'   =>  [
                'message'  =>  __( 'Would you like to delete this ?' ),
            ]
        ]);
        
        return $entry;
    }

    
    /**
     * Bulk Delete Action
     * @param    object Request with object
     * @return    false/array
     */
    public function bulkAction( Request $request ) 
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
                if ( $entity instanceof OrderProduct ) {
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
     * get Links
     * @return  array of links
     */
    public function getLinks(): array
    {
        return  [
            'list'      =>  ns()->url( 'dashboard/' . 'ns-gastro-canceled-products' ),
            'create'    =>  ns()->url( 'dashboard/' . 'ns-gastro-canceled-products/create' ),
            'edit'      =>  ns()->url( 'dashboard/' . 'ns-gastro-canceled-products/edit/' ),
            'post'      =>  ns()->url( 'api/crud/' . 'ns.gastro-canceled-products' ),
            'put'       =>  ns()->url( 'api/crud/' . 'ns.gastro-canceled-products/{id}' . '' ),
        ];
    }

    /**
     * Get Bulk actions
     * @return  array of actions
    **/
    public function getBulkActions(): array
    {
        return Hook::filter( $this->namespace . '-bulk', [
            [
                'label'         =>  __( 'Delete Selected Groups' ),
                'identifier'    =>  'delete_selected',
                'url'           =>  ns()->route( 'ns.api.crud-bulk-actions', [
                    'namespace' =>  $this->namespace
                ])
            ]
        ]);
    }

    /**
     * get exports
     * @return  array of export formats
    **/
    public function getExports()
    {
        return [];
    }
}