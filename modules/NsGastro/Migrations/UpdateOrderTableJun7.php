<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrderTableJun7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders', 'gastro_table_session_id')) {
                $table->integer('gastro_table_session_id')->nullable();
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
            if (Schema::hasColumn('nexopos_orders', 'gastro_table_session_id')) {
                $table->dropColumn('gastro_table_session_id');
            }
        });
    }
}
