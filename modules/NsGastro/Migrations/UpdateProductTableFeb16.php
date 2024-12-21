<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProductTableFeb16 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_products', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_products', 'modifiers_group_id')) {
                $table->integer('modifiers_group_id')->nullable();
            }

            if (! Schema::hasColumn('nexopos_products', 'modifiers_groups')) {
                $table->string('modifiers_groups')->nullable();
            }

            if (! Schema::hasColumn('nexopos_products', 'gastro_item_type')) {
                $table->string('gastro_item_type')->nullable();
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
        Schema::table('nexopos_products', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_products', 'modifiers_group_id')) {
                $table->dropColumn('modifiers_group_id');
            }

            if (Schema::hasColumn('nexopos_products', 'modifiers_groups')) {
                $table->dropColumn('modifiers_groups');
            }

            if (Schema::hasColumn('nexopos_products', 'gastro_item_type')) {
                $table->dropColumn('gastro_item_type');
            }
        });
    }
}
