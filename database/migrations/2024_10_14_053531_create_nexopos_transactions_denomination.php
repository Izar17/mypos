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
        Schema::create('nexopos_transactions_denomination', function (Blueprint $table) {
            $table->bigIncrements( 'id' );
            $table->string( 'name' );
            $table->string( 'identifier' )->unique();
            $table->text( 'description' )->nullable();
            $table->integer( 'author' )->nullable();
            $table->integer( 'pos_id' )->nullable();
            $table->integer( 'register_history_id' );
            $table->float( 'thousand', 18, 5 )->nullable();
            $table->float( 'five_hundred', 18, 5 )->nullable();
            $table->float( 'two_hundred', 18, 5 )->nullable();
            $table->float( 'one_hundred', 18, 5 )->nullable();
            $table->float( 'fifty', 18, 5 )->nullable();
            $table->float( 'twenty', 18, 5 )->nullable();
            $table->float( 'ten', 18, 5 )->nullable();
            $table->float( 'five', 18, 5 )->nullable();
            $table->float( 'peso', 18, 5 )->nullable();
            $table->float( 'twenty_five_cent', 18, 5 )->nullable();
            $table->float( 'ten_cent', 18, 5 )->nullable();
            $table->float( 'five_cent', 18, 5 )->nullable();
            $table->float( 'one_cent', 18, 5 )->nullable();
            $table->float( 'cash_float', 18, 5 )->nullable();
            $table->float( 'cash_out', 18, 5 )->nullable();
            $table->float( 'balance', 18, 5 )->nullable();
            $table->float( 'value', 18, 5 )->nullable();
            $table->float( 'discrepancy', 18, 5 )->nullable();
            $table->float( 'total_cash', 18, 5 )->nullable();
            $table->string( 'uuid' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nexopos_transactions_denomination');
    }
};
