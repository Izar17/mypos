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
        Schema::createIfMissing('nexopos_orders_products_modifiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_product_id');
            $table->integer('modifier_id');
            $table->string('name')->nullable();
            $table->integer('order_product_modifier_group_id');
            $table->float('unit_price');
            $table->integer('unit_id');
            $table->float('quantity');
            $table->float('total_price');
            $table->float('tax_value');
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
        Schema::dropIfExists('nexopos_orders_products_modifiers');
    }
};