<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderProductTableMar3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products', 'cooking_status')) {
                $table->string('cooking_status')->default('pending'); // can be "pending", "ongoing", "served".
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
            if (Schema::hasColumn('nexopos_orders_products', 'cooking_status')) {
                $table->dropColumn('cooking_status');
            }
        });
    }
}
