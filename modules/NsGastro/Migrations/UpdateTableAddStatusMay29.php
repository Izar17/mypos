<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTableAddStatusMay29 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_gastro_tables', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_gastro_tables', 'busy')) {
                $table->boolean('busy')->default(false);
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
        if (Schema::hasTable('nexopos_gastro_tables')) {
            if (Schema::hasColumn('nexopos_gastro_tables', 'busy')) {
                Schema::table('nexopos_gastro_tables', function (Blueprint $table) {
                    if (Schema::hasColumn('nexopos_gastro_tables', 'busy')) {
                        $table->dropColumn('busy');
                    }
                });
            }
        }
    }
}
