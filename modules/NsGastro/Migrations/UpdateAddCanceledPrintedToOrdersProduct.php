<?php
/**
 * Table Migration
 * @package  4.8.5
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAddCanceledPrintedToOrdersProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table( 'nexopos_orders_products', function( Blueprint $table ) {
            if ( ! Schema::hasColumn( 'nexopos_orders_products', 'meal_cancelation_printed' ) ) {
                $table->boolean( 'meal_cancelation_printed' )->default( false );
            }
            if ( ! Schema::hasColumn( 'nexopos_orders_products', 'meal_cancelation_quantity' ) ) {
                $table->boolean( 'meal_cancelation_quantity' )->default( false );
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::table( 'nexopos_orders_products', function( Blueprint $table ) {
            if ( Schema::hasColumn( 'nexopos_orders_products', 'meal_cancelation_printed' ) ) {
                $table->dropColumn( 'meal_cancelation_printed' );
            }

            if ( Schema::hasColumn( 'nexopos_orders_products', 'meal_cancelation_quantity' ) ) {
                $table->dropColumn( 'meal_cancelation_quantity' );
            }
        });
    }
}
