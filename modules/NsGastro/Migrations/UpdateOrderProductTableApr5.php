<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderProductTableApr5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products', 'meal_placed_by')) {
                $table->integer('meal_placed_by')->nullable(); // can be "pending", "ongoing", "served".
            }
            if (! Schema::hasColumn('nexopos_orders_products', 'meal_placed_by_name')) {
                $table->string('meal_placed_by_name')->nullable(); 
            }

            if (! Schema::hasColumn('nexopos_orders_products', 'meal_served_by')) {
                $table->integer('meal_served_by')->nullable(); // can be "pending", "ongoing", "served".
            }
            if (! Schema::hasColumn('nexopos_orders_products', 'meal_served_by_name')) {
                $table->string('meal_served_by_name')->nullable(); 
            }

            if (! Schema::hasColumn('nexopos_orders_products', 'meal_canceled_by')) {
                $table->integer('meal_canceled_by')->nullable(); // can be "pending", "ongoing", "served".
            }
            if (! Schema::hasColumn('nexopos_orders_products', 'meal_canceled_by_name')) {
                $table->string('meal_canceled_by_name')->nullable(); 
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
            if (Schema::hasColumn('nexopos_orders_products', 'meal_placed_by')) {
                $table->dropColumn('meal_placed_by');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'meal_served_by')) {
                $table->dropColumn('meal_served_by');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'meal_canceled_by')) {
                $table->dropColumn('meal_canceled_by');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'meal_placed_by_name')) {
                $table->dropColumn('meal_placed_by_name');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'meal_served_by_name')) {
                $table->dropColumn('meal_served_by_name');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'meal_canceled_by_name')) {
                $table->dropColumn('meal_canceled_by_name');
            }
        });
    }
}
