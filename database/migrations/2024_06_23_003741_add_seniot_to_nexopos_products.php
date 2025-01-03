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
        Schema::table('nexopos_products', function (Blueprint $table) {
            if (!Schema::hasColumn('nexopos_products', 'is_senior_disc')) {
                $table->boolean('is_senior_disc')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nexopos_products', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_products', 'is_senior_disc')) {
                $table->dropColumn('is_senior_disc');
            }
        });
    }
};
