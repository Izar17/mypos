<?php
namespace modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateToV5Migration extends Migration
{
    public function up()
    {
        ns()->option->delete( 'ns_gastro_kitchen_print_gateway' );
        ns()->option->delete( 'ns_gastro_logo_shortcode' );
        ns()->option->delete( 'ns_gastro_logo_type' );

        Schema::table('nexopos_orders_products_modifiers', function (Blueprint $table) {
            if (! Schema::hasColumn('nexopos_orders_products_modifiers', 'order_product_modifier_group_id')) {
                $table->integer('order_product_modifier_group_id')->nullable();
            }
        });
    }
}