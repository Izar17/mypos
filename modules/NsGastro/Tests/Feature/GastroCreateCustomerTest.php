<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\CustomerGroup;
use App\Models\Role;
use Faker\Factory;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GastroCreateCustomerTest extends TestCase
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

        $faker = Factory::create();

        $group = CustomerGroup::first();

        for ($i = 0; $i < 5; $i++) {
            /**
             * Creating a first customer
             */
            $response = $this->withSession($this->app['session']->all())
                ->json('POST', 'api/crud/ns.customers', [
                    'first_name'  =>  $faker->firstName,
                    'general'   =>  [
                        'group_id'  =>  $group->id,
                        'last_name'   =>  $faker->lastName,
                        'email'     =>  $faker->email,
                    ],
                ]);

            $response->assertJson([
                'status'    =>  'success',
            ]);
        }
    }
}
