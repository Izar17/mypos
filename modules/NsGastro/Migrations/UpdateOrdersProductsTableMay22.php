<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Modules\NsGastro\Models\OrderProduct;

class UpdateOrdersProductsTableMay22 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products', 'modifiers_gross_total')) {
                $table->float('modifiers_gross_total')->default(0);
            }
            if (! Schema::hasColumn('nexopos_orders_products', 'modifiers_net_total')) {
                $table->float('modifiers_net_total')->default(0);
            }
            if (! Schema::hasColumn('nexopos_orders_products', 'modifiers_total')) {
                $table->float('modifiers_total')->default(0);
            }
        });

        OrderProduct::with( 'modifiers' )
            ->get()
            ->each(function ($product) {
                $totalModifiers = $product->modifiers->sum('total_price');
                $product->modifiers_total = $totalModifiers;
                $product->modifiers_net_total = $totalModifiers;
                $product->modifiers_gross_total = $totalModifiers;
                $product->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::table('nexopos_orders_products', function (Blueprint $table) {
            if (Schema::hasColumn('nexopos_orders_products', 'modifiers_gross_total')) {
                $table->dropColumn('modifiers_gross_total');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'modifiers_net_total')) {
                $table->dropColumn('modifiers_net_total');
            }
            if (Schema::hasColumn('nexopos_orders_products', 'modifiers_total')) {
                $table->dropColumn('modifiers_total');
            }
        });
    }
}
