<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTablesForRestaurant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::createIfMissing('nexopos_gastro_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('preview')->nullable();
            $table->string('status')->default('unavailable'); // available, unavailable, booked
            $table->boolean('allow_multi_clients')->default(false);
            $table->integer('seats')->default(0);
            $table->integer('author');
            $table->integer('area_id')->nullable();
            $table->datetime('booking_starts_at')->nullable();
            $table->datetime('booking_ends_at')->nullable();
            $table->timestamps();
        });

        Schema::createIfMissing('nexopos_gastro_tables_history', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('table_id');
            $table->integer('author');
            $table->timestamps();
        });

        Schema::createIfMissing('nexopos_gastro_tables_booking_history', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('table_id');
            $table->datetime('booking_time');
            $table->integer('author');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('nexopos_gastro_tables');
        Schema::dropIfExists('nexopos_gastro_tables_history');
        Schema::dropIfExists('nexopos_gastro_tables_booking_history');
    }
}
