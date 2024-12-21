<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::createIfMissing('nexopos_orders_products_modifiers_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->integer('order_product_id');
            $table->integer('modifier_group_id');
            $table->boolean('multiselect');
            $table->boolean('countable');
            $table->boolean('forced');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('nexopos_orders_products_modifiers_groups');
    }
};