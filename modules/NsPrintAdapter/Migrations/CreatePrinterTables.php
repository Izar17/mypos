<?php
/**
 * Table Migration
**/

namespace Modules\NsPrintAdapter\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Modules\NsPrintAdapter\Models\Printer;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        if (! Schema::hasTable('nexopos_printers')) {
            Schema::create('nexopos_printers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('identifier');
                $table->string('interface');
                $table->string( 'characterset' )->default('');
                $table->string( 'type' )->default('epson');
                $table->string( 'line_character' )->default('*');
                $table->string('argument')->nullable();
                $table->boolean( 'is_default' )->default( false );
                $table->integer('author');
                $table->string('status')->default(Printer::DISABLED);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('nexopos_printers');
    }
};
