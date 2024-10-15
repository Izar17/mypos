<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nexopos_users', function (Blueprint $table) {
            if (!Schema::hasColumn('nexopos_users', 'id_no')) {
                $table->string('id_no')->nullable();
            }
            if (!Schema::hasColumn('nexopos_users', 'room_no')) {
                $table->string('room_no')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nexopos_users', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_users', 'id_no')) {
                $table->dropColumn('id_no');
            }
            if (Schema::hasColumn('nexopos_users', 'room_no')) {
                $table->dropColumn('room_no');
            }
        });
    }
};
