<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Services\ModulesService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GastroHardReset extends TestCase
{
    use WithFaker;

    public function testHardReset()
    {
        Artisan::call('ns:reset --mode=hard');

        Artisan::call('ns:setup', [
            '--admin_username'  =>  env('NS_RESET_USERNAME', 'admin'),
            '--admin_email'     =>  env('NS_RESET_MAIL', 'contact@nexopos.com'),
            '--admin_password'  =>  env('NS_RESET_PASSWORD', 123456),
            '--store_name'      =>  env('NS_RESET_APPNAME', 'NexoPOS'),
        ]);

        Artisan::call('migrate --path=database/migrations/core');
        Artisan::call('migrate --path=database/migrations/create');
        /**
         * let's install gastro
         *
         * @var ModulesService
         */
        $moduleService = new ModulesService;
        $moduleService->load();
        $module = $moduleService->get('NsGastro');
        $migrationsFiles = $moduleService->getAllMigrations($module);

        foreach ($migrationsFiles as $file) {
            $moduleService->runMigration('NsGastro', $file);
        }

        $moduleService->enable('NsGastro');

        return $this->assertTrue(true);
    }
}
