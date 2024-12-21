<?php
namespace Modules\NsGastro\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('nexopos_gastro_tables_assigned_waiters')) {
            Schema::create('nexopos_gastro_tables_assigned_waiters', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('table_id');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('nexopos_gastro_tables_assigned_waiters');
    }
};