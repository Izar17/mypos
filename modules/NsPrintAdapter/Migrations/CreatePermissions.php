<?php

/**
 * Table Migration
 * @package 5.0.0
 **/

namespace Modules\NsPrintAdapter\Migrations;

use App\Classes\Schema;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$managePrinters         =       Permission::firstOrCreate([
			'namespace'     =>      'nspa.manage-printers',
		], [
			'name'          =>      __m('Manage Printers', 'NsPrintAdapter'),
			'description'   =>      __m('Allow the user to manage the printers (create, read, update, delete)', 'NsPrintAdapter')
		]);

		Role::namespace(Role::ADMIN)->addPermissions($managePrinters);
		Role::namespace(Role::STOREADMIN)->addPermissions($managePrinters);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop tables here
	}
};
