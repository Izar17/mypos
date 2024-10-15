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
        Schema::table('nexopos_orders', function (Blueprint $table) {
            Schema::table('nexopos_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('nexopos_orders', 'vat_exempt_sales')) {
                    $table->decimal('vat_exempt_sales', 15,2)->default(0);
                }
                if (!Schema::hasColumn('nexopos_orders', 'sc_vatable')) {
                    $table->decimal('sc_vatable',15,2)->default(0);
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nexopos_orders', function (Blueprint $table) {
            Schema::table('nexopos_orders', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders', 'vat_exempt_sales')) {
                $table->dropColumn('vat_exempt_sales');
            }
            if (Schema::hasColumn('nexopos_orders', 'sc_vatable')) {
                $table->dropColumn('sc_vatable');
            }
        });
        });
    }
};
