<?php

namespace Modules\NsGastro\Tests\Feature;

use App\Models\ProductCategory;
use App\Models\Role;
use App\Models\Unit;
use App\Models\UnitGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Modules\NsGastro\Models\ModifierGroup;
use Modules\NsGastro\Tests\TestCase;

class GastroCreateCategories extends TestCase
{
    use WithFaker;

    private $user;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateRestaurantFoods()
    {
        $this->user = Role::namespace('admin')->users->first();

        Sanctum::actingAs(
            $this->user,
            ['*']
        );

        $categories = [
            json_decode(file_get_contents(__DIR__.'/../Json/beverages.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/burgers.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/cafes.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/meats.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/starters.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/pastas.json'), true),
        ];

        $modifiableCategories = [
            json_decode(file_get_contents(__DIR__.'/../Json/condiments.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/side-orders.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Json/sauces.json'), true),
        ];

        /**
         * creating unit group
         * and unit used througout this test
         */
        $unitGroup = UnitGroup::first();

        /**
         * create category and modififers
         */
        foreach ($modifiableCategories as $index => $rawCategory) {
            $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/crud/ns.products-categories', [
                    'name'                  =>  $rawCategory['name'],
                    'general'               =>  [
                        'preview_url'       =>  asset('modules/nsgastro/images/categories/'.$rawCategory['products'][0]['image']),
                        'displays_on_pos'   =>  true,
                        'description'       =>  $rawCategory['description'] ?? '',
                    ],
                ]);

            $response->assertJsonPath('status', 'success');

            $category = ProductCategory::orderBy('id', 'desc')->first();

            /**
             * creating modifiers that match every category
             * we should probably extends category to be used
             * as a modifier group.
             */
            $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/crud/ns.gastro-modifiers-groups', [
                    'name'              =>  $category->name,
                    'general'           =>  [
                        'forced'        =>  $rawCategory['forced'] ?? true,
                        'countable'     =>  $rawCategory['countable'] ?? Arr::random([true, false]),
                        'multiselect'   =>  $rawCategory['multiselect'] ?? true,
                        'description'   =>  $rawCategory['description'] ?? '',
                    ],
                ]);

            $response->assertJsonPath('status', 'success');

            $modifierGroup = ModifierGroup::orderBy('id', 'desc')->first();

            foreach ($rawCategory['products'] as $product) {
                $response = $this
                    ->withSession($this->app['session']->all())
                    ->json('POST', '/api/products/', [
                        'name'          =>  $product['name'],
                        'variations'    =>  [
                            [
                                '$primary'  =>  true,
                                'expiracy'  =>  [
                                    'expires'       =>  0,
                                    'on_expiration' =>  'prevent_sales',
                                ],
                                'identification'    =>  [
                                    'barcode'           =>  $this->faker->ean13(),
                                    'barcode_type'      =>  'ean13',
                                    'category_id'       =>  $category->id,
                                    'description'       =>  __m('Created via tests', 'NsGastro'),
                                    'product_type'      =>  'product',
                                    'type'              =>  in_array($category->id, [2]) ? 'materialized' : 'dematerialized',
                                    'sku'               =>  Str::random(15),
                                    'status'            =>  'available',
                                    'stock_management'  =>  in_array($category->id, [2]) ? 'enabled' : 'disabled',
                                ],
                                'images'            =>  [
                                    [
                                        'primary'       =>  true,
                                        'url'         =>  asset('modules/nsgastro/images/categories/'.$product['image']),
                                    ],
                                ],
                                'taxes'             =>  [
                                    'tax_group_id'  =>  1,
                                    'tax_type'      =>  'inclusive',
                                ],
                                'units'             =>  [
                                    'selling_group' =>  $unitGroup->units->map(function ($unit) use ($product) {
                                        return [
                                            'sale_price_edit'       =>  $product['price'],
                                            'wholesale_price_edit'  =>  ns()->currency->getPercentageValue($product['price'], 10, 'substract'),
                                            'unit_id'               =>  $unit->id,
                                            'preview_url'           =>  asset('modules/nsgastro/images/categories/'.$product['image']),
                                        ];
                                    }),
                                    'unit_group'    =>  $unitGroup->id,
                                ],
                                'restaurant'            =>  [
                                    'modifiers_groups'      =>  '',
                                    'modifiers_group_id'    =>  $modifierGroup->id,
                                ],
                            ],
                        ],
                    ]);

                $response->assertJsonPath('status', 'success');
            }
        }

        foreach ($categories as $index => $rawCategory) {
            $response = $this
                ->withSession($this->app['session']->all())
                ->json('POST', '/api/crud/ns.products-categories', [
                    'name'                  =>  $rawCategory['name'],
                    'general'               =>  [
                        'preview_url'       =>  asset('modules/nsgastro/images/categories/'.$rawCategory['products'][0]['image']),
                        'displays_on_pos'   =>  true,
                    ],
                ]);

            $response->assertJsonPath('status', 'success');

            $category = ProductCategory::orderBy('id', 'desc')->first();
            $modifierGroup = ModifierGroup::get()
                ->map(fn ($group) => $group->id)
                ->toArray();

            foreach ($rawCategory['products'] as $product) {
                $response = $this
                    ->withSession($this->app['session']->all())
                    ->json('POST', '/api/products/', [
                        'name'          =>  $product['name'],
                        'variations'    =>  [
                            [
                                '$primary'  =>  true,
                                'expiracy'  =>  [
                                    'expires'       =>  0,
                                    'on_expiration' =>  'prevent_sales',
                                ],
                                'identification'    =>  [
                                    'barcode'           =>  $this->faker->ean13(),
                                    'barcode_type'      =>  'ean13',
                                    'category_id'       =>  $category->id,
                                    'description'       =>  __m('Created via tests', 'NsGastro'),
                                    'product_type'      =>  'product',
                                    'type'              =>  'dematerialized',
                                    'sku'               =>  Str::random(15),
                                    'status'            =>  'available',
                                    'stock_management'  =>  'disabled',
                                ],
                                'images'            =>  [
                                    [
                                        'primary'       =>  true,
                                        'url'         =>  asset('modules/nsgastro/images/categories/'.$product['image']),
                                    ],
                                ],
                                'taxes'             =>  [
                                    'tax_group_id'  =>  1,
                                    'tax_type'      =>  'inclusive',
                                ],
                                'units'             =>  [
                                    'selling_group' =>  $unitGroup->units->map(function ($unit) use ($product) {
                                        return [
                                            'sale_price_edit'       =>  $product['price'],
                                            'wholesale_price_edit'  =>  ns()->currency->getPercentageValue($product['price'], 10, 'substract'),
                                            'unit_id'               =>  $unit->id,
                                            'preview_url'           =>  asset('modules/nsgastro/images/categories/'.$product['image']),
                                        ];
                                    }),
                                    'unit_group'    =>  $unitGroup->id,
                                ],
                                'restaurant'            =>  [
                                    'modifiers_groups'      =>  isset($rawCategory['disable-modifier']) ? [] : $modifierGroup,
                                    'modifiers_group_id'    =>  null,
                                ],
                            ],
                        ],
                    ]);

                $response->assertJsonPath('status', 'success');
            }
        }
    }

    /**
     * Will create default unit for the
     * restaurant items
     *
     * @return UnitGroup
     */
    private function createUnitGroup(): UnitGroup
    {
        $unitGroup = UnitGroup::where('name', __m('Countable', 'NsGastro'))->first();

        if (! $unitGroup instanceof UnitGroup) {
            $unitGroup = new UnitGroup;
        }

        $unitGroup->name = __m('Countable', 'NsGastro');
        $unitGroup->author = $this->user->id;
        $unitGroup->save();

        $unit = Unit::identifier('piece')->first();

        if (! $unit instanceof Unit) {
            $unit = new Unit;
        }

        $unit->name = __m('Piece', 'NsGastro');
        $unit->author = $this->user->id;
        $unit->identifier = 'piece';
        $unit->preview_url = '';
        $unit->value = 1;
        $unit->group_id = $unitGroup->id;
        $unit->base_unit = true;
        $unit->description = '';
        $unit->save();

        return $unitGroup->fresh();
    }
}
