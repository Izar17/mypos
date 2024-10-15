<?php
/**
 * Table Migration
 * @package 5.0.0
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use App\Models\Product;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\NsGastro\Jobs\UpdateProductModifierGroupsJob;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::createIfMissing('nexopos_gastro_products_modifiers_groups', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->integer('product_id');
			$table->integer('modifier_group_id')->unsigned();
			$table->integer('sort_order')->unsigned()->default(0);
			$table->timestamps();
		});

		Product::get()->each(function ($product) {
			UpdateProductModifierGroupsJob::dispatch($product);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('nexopos_gastro_products_modifiers_groups');
	}
};
