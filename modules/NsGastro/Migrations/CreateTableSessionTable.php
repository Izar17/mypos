<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTableSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::createIfMissing('nexopos_gastro_tables_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->integer('table_id');
            $table->datetime('session_starts')->nullable();
            $table->datetime('session_ends')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('session_minutes_duration')->default(0); // in minutes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('nexopos_gastro_tables_sessions');
    }
}
