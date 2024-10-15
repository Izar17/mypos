<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\ProductCategory;
use App\Models\Role;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GastroCreateKitchens extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        Sanctum::actingAs(
            Role::namespace('admin')->users->first(),
            ['*']
        );

        $kitchenNames = [__m('First Kitchen', 'NsGastro'), __m('Second Kitchen', 'NsGastro'), __m('Third Kitchen', 'NsGastro')];
        $categories = ProductCategory::get()
            ->map(fn ($category) => $category->id)
            ->toArray();

        foreach ($kitchenNames as $index => $name) {
            $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/crud/ns.gastro-kitchens', [
                    'name'              =>  $name,
                    'general'           =>  [
                        'status'        =>  'enabled',
                        'notification_status'   =>  'enabled',
                        'categories'            =>  array_chunk($categories, count($categories) / 3)[$index],
                    ],
                ]);

            $response->assertJsonPath('status', 'success');
        }
    }
}
