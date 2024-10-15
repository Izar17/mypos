<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\RewardSystem;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GastroCreateCustomerGroupTest extends TestCase
{
    use WithFaker;

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

        $response = $this->withSession($this->app['session']->all())
            ->json('POST', 'api/crud/ns.customers-groups', [
                'name'  =>  __m('Base Customers', 'NsGastro'),
                'general'   =>  [
                    'reward_system_id'  =>  $this->faker->randomElement(RewardSystem::get()->map(fn ($reward) => $reward->id)->toArray()),
                ],
            ]);

        $response->assertJson([
            'status'    =>  'success',
        ]);
    }
}
