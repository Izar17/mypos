<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderProductsMar28 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products', 'cooking_cancelation_note')) {
                $table->text('cooking_cancelation_note')->nullable();
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
        if (Schema::hasTable('nexopos_orders_products')) {
            Schema::table('nexopos_orders_products', function (Blueprint $table) {
                if (Schema::hasColumn('nexopos_orders_products', 'cooking_cancelation_note')) {
                    $table->dropColumn('cooking_cancelation_note');
                }
            });
        }
    }
}
