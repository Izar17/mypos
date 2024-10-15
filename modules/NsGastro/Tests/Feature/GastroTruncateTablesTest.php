<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Tests\TestCase;

class GastroTruncateTablesTest extends TestCase
{
    protected $count = 1;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCookingOrder()
    {
        Sanctum::actingAs(
            Role::namespace('admin')->users->first(),
            ['*']
        );

        $tables = [
            'areas',
            'kitchens',
            'kitchens_categories',
            'kitchens_printers',
            'modifiers_groups',
            'tables',
            'tables_booking_history',
            'tables_history',
            'tables_sessions',
        ];

        foreach ($tables as $table) {
            $this->assertNull( DB::table('nexopos_gastro_'.$table)->truncate() );
        }
    }
}
