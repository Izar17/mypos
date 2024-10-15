<?php
/**
 * Table Migration
**/

namespace Modules\NsPrintAdapter\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        if (Schema::hasTable('nexopos_registers')) {
            Schema::table('nexopos_registers', function (Blueprint $table) {
                if (! Schema::hasColumn('nexopos_registers', 'printer_id')) {
                    $table->string('printer_id')->nullable();
                }
                if ( Schema::hasColumn('nexopos_registers', 'cloud_printer_id')) {
                    $table->dropColumn( 'cloud_printer_id' );
                }
                if ( Schema::hasColumn('nexopos_registers', 'printer_name')) {
                    $table->dropColumn( 'printer_name' );
                }
                if ( Schema::hasColumn('nexopos_registers', 'printer_address')) {
                    $table->dropColumn( 'printer_address' );
                }
            });
        }

        if (Schema::hasTable('nexopos_gastro_kitchens')) {
            // Schema::table('nexopos_gastro_kitchens', function (Blueprint $table) {
            //     if (! Schema::hasColumn('nexopos_gastro_kitchens', 'printer_ids')) {
            //         $table->string('printer_ids')->nullable();
            //     }
            // });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        if (Schema::hasTable('nexopos_registers')) {
            Schema::table('nexopos_registers', function (Blueprint $table) {
                if (Schema::hasColumn('nexopos_registers', 'printer_id')) {
                    $table->dropColumn('printer_id');
                }
            });
        }

        if (Schema::hasTable('nexopos_gastro_kitchens')) {
            // Schema::table('nexopos_gastro_kitchens', function (Blueprint $table) {
            //     if (Schema::hasColumn('nexopos_gastro_kitchens', 'printer_ids')) {
            //         $table->dropColumn('printer_ids');
            //     }
            // });
        }
    }
};
