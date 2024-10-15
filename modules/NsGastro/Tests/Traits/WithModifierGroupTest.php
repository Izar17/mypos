<?php
namespace Modules\NsGastro\Tests\Traits;

use Illuminate\Support\Arr;
use Modules\NsGastro\Models\ModifierGroup;

trait WithModifierGroupTest
{
    public function attemptCreateModifiersGroup()
    {
        $faker  =   \Faker\Factory::create();
        $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/crud/ns.gastro-modifiers-groups', [
                    'name'              =>  $faker->name,
                    'general'           =>  [
                        'forced'        =>  $rawCategory['forced'] ?? true,
                        'countable'     =>  $rawCategory['countable'] ?? Arr::random([true, false]),
                        'multiselect'   =>  $rawCategory['multiselect'] ?? true,
                        'description'   =>  $rawCategory['description'] ?? '',
                    ],
                ]);

        $response->assertJsonPath('status', 'success');

        return ModifierGroup::find( $response->json()['data']['entry']['id'] );
    }

    public function attemptUpdateModifiersGroup( $modifierGroup )
    {
        $faker  =   \Faker\Factory::create();
        $response = $this
                ->withSession($this->app['session']->all())
                ->json('PUT', '/api/crud/ns.gastro-modifiers-groups/' . $modifierGroup->id, [
                    'name'              =>  $faker->name,
                    'general'           =>  [
                        'forced'        =>  $rawCategory['forced'] ?? true,
                        'countable'     =>  $rawCategory['countable'] ?? Arr::random([true, false]),
                        'multiselect'   =>  $rawCategory['multiselect'] ?? true,
                        'description'   =>  $rawCategory['description'] ?? '',
                    ],
                ]);

        $response->assertJsonPath('status', 'success');

        return ModifierGroup::find( $response->json()['data']['entry']['id'] );
    }

    public function attemptDeleteModifiersGroup( $modifierGroup )
    {
        $response = $this
                ->withSession($this->app['session']->all())
                ->json('DELETE', '/api/crud/ns.gastro-modifiers-groups/' . $modifierGroup->id);

        $response->assertJsonPath('status', 'success');

        return $modifierGroup;
    }
}