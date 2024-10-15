<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateKitchenTable10Apr22 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_gastro_kitchens', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_gastro_kitchens', 'notification_status')) {
                $table->string('notification_status')->default('disabled');
            }
            if (! Schema::hasColumn('nexopos_gastro_kitchens', 'notification_sound')) {
                $table->string('notification_sound')->default('');
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
        Schema::table('nexopos_gastro_kitchens', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_gastro_kitchens', 'notification_status')) {
                $table->dropColumn('notification_status');
            }
            if (Schema::hasColumn('nexopos_gastro_kitchens', 'notification_sound')) {
                $table->dropColumn('notification_sound');
            }
        });
    }
}
