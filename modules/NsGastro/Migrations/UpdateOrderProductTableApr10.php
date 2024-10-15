<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderProductTableApr10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products', 'order_product_id')) {
                $table->integer('order_product_id')->nullable();
            }

            if (! Schema::hasColumn('nexopos_orders_products', 'modifier_id')) {
                $table->integer('modifier_id')->nullable();
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
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders_products', 'order_product_id')) {
                $table->dropColumn('order_product_id');
            }
        });

        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders_products', 'modifier_id')) {
                $table->dropColumn('modifier_id');
            }
        });
    }
}
