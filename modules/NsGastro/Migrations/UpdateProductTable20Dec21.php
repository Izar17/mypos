<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProductTable20Dec21 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        if (! Schema::hasColumn('nexopos_products', 'skip_cooking')) {
            Schema::table('nexopos_products', function (Blueprint $table) {
                $table->boolean('skip_cooking')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        if (Schema::hasColumn('nexopos_products', 'skip_cooking')) {
            Schema::table('nexopos_products', function (Blueprint $table) {
                $table->dropColumn('skip_cooking');
            });
        }
    }
}
