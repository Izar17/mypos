<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\Role;
use Faker\Factory;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\Area;
use Tests\TestCase;

class GastroCreateTableAndAreas extends TestCase
{
    /**
     * @var Factory
     */
    public $faker;

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

        $kitchenNames = [__m('Hall Area', 'NsGastro'), __m('Stage 1', 'NsGastro'), __m('Outside', 'NsGastro')];
        $this->faker = Factory::create();

        foreach ($kitchenNames as $index => $name) {
            $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/crud/ns.gastro-areas', [
                    'name'              =>  $name,
                ]);

            $response->assertJsonPath('status', 'success');

            $area = Area::orderBy('id', 'desc')->first();

            for ($i = 1; $i <= 10; $i++) {
                $response = $this
                    ->withSession($this->app['session']->all())
                    ->json('POST', '/api/crud/ns.gastro-tables', [
                        'name'              =>  $area->name.' Table '.$i,
                        'general'           =>  [
                            'area_id'               =>  $area->id,
                            'status'                =>  'available',
                            'busy'                  =>  false,
                            'allow_multi_clients'   =>  Arr::random([true, false]),
                            'seats'                 =>  $this->faker->randomElement([5, 10, 15, 20, 25, 30]),
                        ],
                    ]);

                $response->assertJsonPath('status', 'success');
            }
        }
    }
}
