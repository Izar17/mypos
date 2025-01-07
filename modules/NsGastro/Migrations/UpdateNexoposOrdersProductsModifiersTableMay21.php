<?php
/**
 * Table Migration
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Modules\NsGastro\Models\OrderProductModifier;

class UpdateNexoposOrdersProductsModifiersTableMay21 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        Schema::table('nexopos_orders_products_modifiers', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products_modifiers', 'unit_quantity_id')) {
                $table->integer('unit_quantity_id')->nullable();
            }
            if (! Schema::hasColumn('nexopos_orders_products_modifiers', 'unit_id')) {
                $table->integer('unit_id')->nullable();
            }
        });

        OrderProductModifier::get()->each(function ($orderProductModifier) {
            $product = $orderProductModifier->product;

            if ($product instanceof Product) {
                if (! $product->unit_quantities->isEmpty()) {
                    $unitQuantity = $product->unit_quantities->first();
                    $orderProductModifier->unit_quantity_id = $unitQuantity->id;
                    $orderProductModifier->unit_id = $unitQuantity->unit_id;
                    $orderProductModifier->save();
                }
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
        // drop tables here
    }
}
