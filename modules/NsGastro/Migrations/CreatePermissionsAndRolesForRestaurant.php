<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * prevent this from running on multistore migration
     */
    public $multistore = false;

    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        /**
         * Create table
         */
        $createTable = Permission::withNamespace('gastro.create.table')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.create.table';
            $permission->name = __m('Create Restaurant Table', 'NsGastro');
            $permission->description = __m('Allow the user to create a restaurant table.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $readTable = Permission::withNamespace('gastro.read.table')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.read.table';
            $permission->name = __m('Read Restaurant Table', 'NsGastro');
            $permission->description = __m('Allow the user to read a restaurant table.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $updateTable = Permission::withNamespace('gastro.update.table')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.update.table';
            $permission->name = __m('Update Restaurant Table', 'NsGastro');
            $permission->description = __m('Allow the user to update a restaurant table.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $deleteTable = Permission::withNamespace('gastro.delete.table')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.delete.table';
            $permission->name = __m('Delete Restaurant Table', 'NsGastro');
            $permission->description = __m('Allow the user to delete a restaurant table.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        /**
         * create table
         */
        $createKitchens = Permission::withNamespace('gastro.create.kitchens')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.create.kitchens';
            $permission->name = __m('Create Restaurant Kitchens', 'NsGastro');
            $permission->description = __m('Allow the user to create a restaurant kitchens.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $readKitchens = Permission::withNamespace('gastro.read.kitchens')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.read.kitchens';
            $permission->name = __m('Read Restaurant Kitchens', 'NsGastro');
            $permission->description = __m('Allow the user to read a restaurant kitchens.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $updateKitchens = Permission::withNamespace('gastro.update.kitchens')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.update.kitchens';
            $permission->name = __m('Update Restaurant Kitchens', 'NsGastro');
            $permission->description = __m('Allow the user to update a restaurant kitchens.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $deleteKitchens = Permission::withNamespace('gastro.delete.kitchens')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.delete.kitchens';
            $permission->name = __m('Delete Restaurant Kitchens', 'NsGastro');
            $permission->description = __m('Allow the user to delete a restaurant kitchens.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $usesKitchen = Permission::withNamespace('gastro.use.kitchens')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.use.kitchens';
            $permission->name = __m('Use Restaurant Kitchens', 'NsGastro');
            $permission->description = __m('Allow the user to delete a restaurant kitchens.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $cancelMeals = Permission::withNamespace('gastro.cancel.meals')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.cancel.meals';
            $permission->name = __m('Cancel Meals', 'NsGastro');
            $permission->description = __m('Allow the user to cancel the meal.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $serveMeals = Permission::withNamespace('gastro.serve.meals')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.serve.meals';
            $permission->name = __m('Serve Meals', 'NsGastro');
            $permission->description = __m('Allow the user to serve the meal.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $updateMealsNote = Permission::withNamespace('gastro.update.meals-note')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.update.meals-note';
            $permission->name = __m('Update Meals Note', 'NsGastro');
            $permission->description = __m('Allow the user to update meal note.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        /**
         * create modifers groups
         */
        $createModifiersGroups = Permission::withNamespace('gastro.create.modifiers-groups')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.create.modifiers-groups';
            $permission->name = __m('Create Restaurant Modifiers Groups', 'NsGastro');
            $permission->description = __m('Allow the user to create a restaurant modifiers groups.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $redModifiersGroups = Permission::withNamespace('gastro.read.modifiers-groups')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.read.modifiers-groups';
            $permission->name = __m('Read Restaurant Modifiers Groups', 'NsGastro');
            $permission->description = __m('Allow the user to read a restaurant modifiers groups.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $updateModifiersGroups = Permission::withNamespace('gastro.update.modifiers-groups')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.update.modifiers-groups';
            $permission->name = __m('Update Restaurant Modifiers Groups', 'NsGastro');
            $permission->description = __m('Allow the user to update a restaurant modifiers groups.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        $deleteModifiersGroups = Permission::withNamespace('gastro.delete.modifiers-groups')->firstOr(function () {
            $permission = new Permission;
            $permission->namespace = 'gastro.delete.modifiers-groups';
            $permission->name = __m('Delete Restaurant Modifiers Groups', 'NsGastro');
            $permission->description = __m('Allow the user to delete a restaurant modifiers groups.', 'NsGastro');
            $permission->save();

            return $permission;
        });

        /**
         * create roles
         */
        $waiter = Role::namespace('gastro.waiter');

        if (! $waiter instanceof Role) {
            $waiter = new Role();
        }

        $waiter->namespace = 'gastro.waiter';
        $waiter->name = __m('Waiter', 'NsGastro');
        $waiter->description = __m('The waiter is responsible of welcoming customers and placing orders.', 'NsGastro');
        $waiter->save();

        /**
         * allow the waiter to
         * cancel meals
         */
        $waiter->addPermissions([
            'gastro.cancel.meals',
            'gastro.serve.meals',
            'gastro.update.meals-note',
        ]);

        $chef = Role::namespace('gastro.chef');

        if (! $chef instanceof Role) {
            $chef = new Role();
        }

        $chef->namespace = 'gastro.chef';
        $chef->name = __m('Chef', 'NsGastro');
        $chef->description = __m('The chef is responsible cooking meals and handling materials.', 'NsGastro');
        $chef->save();

        /**
         * Allow the chef to use kitchen
         */
        $chef->addPermissions([
            'gastro.use.kitchens',
            'gastro.update.meals-note',
            'gastro.cancel.meals',
        ]);

        collect([
            $createTable,
            $readTable,
            $updateTable,
            $deleteTable,
            $createKitchens,
            $readKitchens,
            $updateKitchens,
            $deleteKitchens,
            $usesKitchen,
            $createModifiersGroups,
            $redModifiersGroups,
            $updateModifiersGroups,
            $deleteModifiersGroups,
            $cancelMeals,
            $serveMeals,
            $updateMealsNote,
        ])->each(function ($permission) {
            Role::namespace('admin')->addPermissions($permission);
            Role::namespace('nexopos.store.administrator')->addPermissions($permission);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Permission::where('namespace', 'like', '%gastro.%')
            ->get()
            ->each(function (Permission $permission) {
                $permission->removeFromRoles();
            });

        Role::where('namespace', 'like', '%gastro.%')
            ->get()
            ->each(function (Role $role) {
                $role->delete();
            });
    }
};