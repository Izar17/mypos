<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nexopos_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('nexopos_orders', 'discount_manager')) {
                $table->string('discount_manager', 255)->nullable()->comment('User Authorize the discount');
            }
            if (!Schema::hasColumn('nexopos_orders', 'vat_exempt')) {
                $table->decimal('vat_exempt', 15,2)->default(0);
            }
            if (!Schema::hasColumn('nexopos_orders', 'discount_code')) {
                $table->string('discount_code')->nullable();
            }
            if (!Schema::hasColumn('nexopos_orders', 'number_pax')) {
                $table->integer('number_pax')->default(0);
            }
            if (!Schema::hasColumn('nexopos_orders', 'number_pax_discount')) {
                $table->integer('number_pax_discount')->default(0);
            }
            if (!Schema::hasColumn('nexopos_orders', 'service_charge')) {
                $table->decimal('service_charge', 15,2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nexopos_orders', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders', 'discount_manager')) {
                $table->dropColumn('discount_manager');
            }
            if (Schema::hasColumn('nexopos_orders', 'vat_exempt')) {
                $table->dropColumn('vat_exempt');
            }
            if (Schema::hasColumn('nexopos_orders', 'discount_code')) {
                $table->dropColumn('discount_code');
            }
            if (Schema::hasColumn('nexopos_orders', 'number_pax')) {
                $table->dropColumn('number_pax');
            }
            if (Schema::hasColumn('nexopos_orders', 'number_pax_discount')) {
                $table->dropColumn('number_pax_discount');
            }
            if (Schema::hasColumn('nexopos_orders', 'service_charge')) {
                $table->dropColumn('service_charge');
            }
        });
    }
};
