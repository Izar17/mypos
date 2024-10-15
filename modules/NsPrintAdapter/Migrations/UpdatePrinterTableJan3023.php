<?php

/**
 * Table Migration
 * @package 5.0.0
 **/

namespace Modules\NsPrintAdapter\Migrations;

use App\Classes\Schema;
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
		Schema::table('nexopos_printers', function (Blueprint $table) {
			if (!Schema::hasColumn('nexopos_printers', 'characterset')) {
				$table->string('characterset')->default('');
			}
			if (!Schema::hasColumn('nexopos_printers', 'type')) {
				$table->string('type')->default('epson');
			}
			if (!Schema::hasColumn('nexopos_printers', 'line_character')) {
				$table->string('line_character')->default('*');
			}
			if (!Schema::hasColumn('nexopos_printers', 'line_character')) {
				$table->boolean('is_default')->default(false);
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
