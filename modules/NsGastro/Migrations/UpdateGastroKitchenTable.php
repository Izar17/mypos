<?php
/**
* Table Migration
* @package 5.2.1
**/

namespace Modules\NsGastro\Migrations;

use App\Classes\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	* Run the migrations.
	*/
	public function up()
	{
		if ( Schema::hasTable( 'nexopos_gastro_kitchens_printers' ) ) {
			Schema::table( 'nexopos_gastro_kitchens_printers', function( Blueprint $table ) {
				if ( Schema::hasColumn( 'nexopos_gastro_kitchens_printers', 'printer' ) ) {
					$table->dropColumn( 'printer' );
				}
				if ( ! Schema::hasColumn( 'nexopos_gastro_kitchens_printers', 'printer_name' ) ) {
					$table->string( 'printer_name' )->nullable();
				}
				if ( ! Schema::hasColumn( 'nexopos_gastro_kitchens_printers', 'printer_id' ) ) {
					$table->integer( 'printer_id' )->nullable();
				}
			});
		}
	}
	
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		if ( Schema::hasTable( 'nexopos_gastro_kitchens_printers' ) ) {
			Schema::table( 'nexopos_gastro_kitchens_printers', function( Blueprint $table ) {
				if ( Schema::hasColumn( 'nexopos_gastro_kitchens_printers', 'printer_name' ) ) {
					$table->dropColumn( 'printer_name' );
				}
				if ( Schema::hasColumn( 'nexopos_gastro_kitchens_printers', 'printer_id' ) ) {
					$table->dropColumn( 'printer_id' );
				}
			});
		}
	}
};
