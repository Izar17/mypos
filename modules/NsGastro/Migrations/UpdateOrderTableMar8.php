<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderTableMar8 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders', 'table_id')) {
                $table->integer('table_id')->nullable();
            }

            if (! Schema::hasColumn('nexopos_orders', 'area_id')) {
                $table->integer('area_id')->nullable();
            }

            if (! Schema::hasColumn('nexopos_orders', 'seats')) {
                $table->integer('seats')->nullable();
            }

            if (! Schema::hasColumn('nexopos_orders', 'table_name')) {
                $table->string('table_name')->nullable();
            }

            if (! Schema::hasColumn('nexopos_orders', 'area_name')) {
                $table->string('area_name')->nullable();
            }

            if (! Schema::hasColumn('nexopos_orders', 'gastro_order_status')) {
                $table->string('gastro_order_status')
                    ->default('pending');
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
        Schema::table('nexopos_orders', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders', 'table_id')) {
                $table->dropColumn('table_id');
            }

            if (Schema::hasColumn('nexopos_orders', 'area_id')) {
                $table->dropColumn('area_id');
            }

            if (Schema::hasColumn('nexopos_orders', 'seats')) {
                $table->dropColumn('seats');
            }

            if (Schema::hasColumn('nexopos_orders', 'table_name')) {
                $table->dropColumn('table_name');
            }

            if (Schema::hasColumn('nexopos_orders', 'table_area')) {
                $table->dropColumn('table_area');
            }

            if (Schema::hasColumn('nexopos_orders', 'gastro_order_status')) {
                $table->dropColumn('gastro_order_status');
            }
        });
    }
}
