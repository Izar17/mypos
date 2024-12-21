<?php
/**
 * Table Migration
 * @package  4.8.0
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePermissionsJul0422 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        $manageLogs = Permission::withNamespace('gastro.manage.logs')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.manage.logs';
            $permission->name = __m('Manage Activity Logs', 'NsGastro');
            $permission->description = __m('Allow the user to manage the activity logs.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        Role::namespace( Role::ADMIN )->addPermissions( $manageLogs );
        Role::namespace( Role::STOREADMIN )->addPermissions( $manageLogs );

        $editStoreOrders = Permission::withNamespace('gastro.edit.orders')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.edit.orders';
            $permission->name = __m('Allow Order Edition', 'NsGastro');
            $permission->description = __m('Allow the user to edit saved orders.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        Role::namespace( Role::ADMIN )->addPermissions( $editStoreOrders );
        Role::namespace( Role::STOREADMIN )->addPermissions( $editStoreOrders );
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        $manageLogs = Permission::withNamespace('gastro.manage.logs')->first();
        if ( $manageLogs instanceof Permission ) {
            $manageLogs->removeFromRoles();
            $manageLogs->delete();
        }

        $editStoreOrders = Permission::withNamespace('gastro.edit.orders')->first();
        if ( $editStoreOrders instanceof Permission ) {
            $editStoreOrders->removeFromRoles();
            $editStoreOrders->delete();
        }
    }
}
