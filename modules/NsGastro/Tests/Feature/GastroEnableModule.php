<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Services\ModulesService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GastroEnableModule extends TestCase
{
    use WithFaker;

    public function testHardReset()
    {
        /**
         * let's install gastro
         *
         * @var ModulesService
         */
        $moduleService = new ModulesService;
        $moduleService->load();
        $response     =   $moduleService->enable('NsGastro');

        return $this->assertTrue( $response[ 'status' ] === 'success' );
    }
}
