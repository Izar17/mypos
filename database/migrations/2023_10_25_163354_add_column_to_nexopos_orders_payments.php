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
        Schema::table('nexopos_orders_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('nexopos_orders_payments', 'total')) {
                $table->float('total')->default(0);
            }
            if (!Schema::hasColumn('nexopos_orders_payments', 'change')) {
                $table->float('change')->default(0);
            }
            if (!Schema::hasColumn('nexopos_orders_payments', 'reference')) {
                $table->text('reference')->nullable();
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
        Schema::table('nexopos_orders_payments', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders_payments', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('nexopos_orders_payments', 'change')) {
                $table->dropColumn('change');
            }
            if (Schema::hasColumn('nexopos_orders_payments', 'reference')) {
                $table->dropColumn('reference');
            }
        });
    }
};
