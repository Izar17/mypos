<?php
/**
 * Table Migration
 * @package 5.0.1
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::table( 'nexopos_procurements_products', function( Blueprint $table ) {
			if ( ! Schema::hasColumn( 'nexopos_procurements_products', 'convert_unit_id' ) ) {
				$table->integer( 'convert_unit_id' )->nullable();
			}
		});
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
