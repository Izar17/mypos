<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderProductsModifiersApr11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products_modifiers', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products_modifiers', 'order_product_id')) {
                $table->integer('order_product_id')->nullable();
            }
            if (! Schema::hasColumn('nexopos_orders_products_modifiers', 'modifier_id')) {
                $table->integer('modifier_id')->nullable();
            }
        });

        Schema::table('nexopos_orders_products_modifiers_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products_modifiers_groups', 'order_product_id')) {
                $table->integer('order_product_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        // drop tables here
    }
}
