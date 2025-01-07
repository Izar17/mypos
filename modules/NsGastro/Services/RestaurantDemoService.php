<?php

namespace Modules\NsGastro\Services;

use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\UnitGroup;
use App\Services\CrudService;
use App\Services\DemoService;
use App\Services\ProductService;
use Faker\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\NsGastro\Models\Area;
use Modules\NsGastro\Models\ModifierGroup;

class RestaurantDemoService extends DemoService
{
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
        $unitGroup->author = Auth::id();
        $unitGroup->save();

        $unit = Unit::identifier('piece')->first();

        if (! $unit instanceof Unit) {
            $unit = new Unit;
        }

        $unit->name = __m('Piece', 'NsGastro');
        $unit->author = Auth::id();
        $unit->identifier = 'piece';
        $unit->preview_url = '';
        $unit->value = 1;
        $unit->group_id = $unitGroup->id;
        $unit->base_unit = true;
        $unit->description = '';
        $unit->save();

        return $unitGroup->fresh();
    }

    public function createProducts()
    {
        /**
         * @var ProductService $productService
         */
        $productService = app()->make(ProductService::class);

        /**
         * @var CrudService $crudService
         */
        $crudService = app()->make(CrudService::class);

        $this->__createProducts($productService, $crudService);
        $this->createTables($crudService);
        $this->createKitchens($crudService);
    }

    public function __createProducts($productService, $crudService)
    {
        $categories = [
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/beverages.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/burgers.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/cafes.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/meats.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/starters.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/pastas.json'), true),
        ];

        $modifiableCategories = [
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/condiments.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/side-orders.json'), true),
            json_decode(file_get_contents(__DIR__.'/../Tests/Json/sauces.json'), true),
        ];

        /**
         * creating unit group
         * and unit used througout this test
         */
        $unitGroup = $this->createUnitGroup();

        /**
         * create category and modififers
         */
        foreach ($modifiableCategories as $index => $rawCategory) {
            $crudInstance = $crudService->getCrudInstance('ns.products-categories');

            $data = $crudInstance->getFlatForm([
                'name'                  =>  $rawCategory['name'],
                'general'               =>  [
                    'preview_url'       =>  asset('modules/nsgastro/images/categories/'.$rawCategory['products'][0]['image']),
                    'displays_on_pos'   =>  true,
                    'description'       =>  $rawCategory['description'] ?? '',
                ],
            ]);

            $crudInstance->submitRequest('ns.products-categories', $data);

            $category = ProductCategory::orderBy('id', 'desc')->first();

            /**
             * creating modifiers that match every category
             * we should probably extends category to be used
             * as a modifier group.
             */
            $crudInstance = $crudService->getCrudInstance('ns.gastro-modifiers-groups');

            $data = $crudInstance->getFlatForm([
                'name'              =>  $category->name,
                'general'           =>  [
                    'forced'        =>  $rawCategory['forced'] ?? true,
                    'countable'     =>  $rawCategory['countable'] ?? Arr::random([true, false]),
                    'multiselect'   =>  $rawCategory['multiselect'] ?? true,
                    'description'   =>  $rawCategory['description'] ?? '',
                ],
            ]);

            $crudInstance->submitRequest('ns.gastro-modifiers-groups', $data);

            $modifierGroup = ModifierGroup::orderBy('id', 'desc')->first();

            foreach ($rawCategory['products'] as $product) {
                $fields = $this->extractProductFields([
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
                                    'featured'      =>  true,
                                    'url'           =>  asset('modules/nsgastro/images/categories/'.$product['image']),
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

                $productService->create($fields);
            }
        }

        foreach ($categories as $index => $rawCategory) {
            $crudInstance = $crudService->getCrudInstance('ns.products-categories');

            $data = $crudInstance->getFlatForm([
                'name'                  =>  $rawCategory['name'],
                'general'               =>  [
                    'preview_url'       =>  asset('modules/nsgastro/images/categories/'.$rawCategory['products'][0]['image']),
                    'displays_on_pos'   =>  true,
                ],
            ]);

            $result = $crudInstance->submitRequest('ns.products-categories', $data);

            $modifierGroup = ModifierGroup::get()
                ->map(fn ($group) => $group->id)
                ->toArray();

            foreach ($rawCategory['products'] as $product) {
                $fields = $this->extractProductFields([
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
                                'category_id'       =>  $result[ 'data' ]['entry']->id,
                                'description'       =>  __m('Created via tests', 'NsGastro'),
                                'product_type'      =>  'product',
                                'type'              =>  'dematerialized',
                                'sku'               =>  Str::random(15),
                                'status'            =>  'available',
                                'stock_management'  =>  $rawCategory['stock-management'] ?? 'disabled',
                            ],
                            'images'            =>  [
                                [
                                    'primary'       =>  true,
                                    'url'           =>  asset('modules/nsgastro/images/categories/'.$product['image']),
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

                $productService->create($fields);
            }
        }
    }

    public function createKitchens(CrudService $crudService)
    {
        $kitchenNames = [__m('First Kitchen', 'NsGastro'), __m('Second Kitchen', 'NsGastro'), __m('Third Kitchen', 'NsGastro')];
        $categories = ProductCategory::get()
            ->map(fn ($category) => $category->id)
            ->toArray();

        $kitchenCrudInstance    =   $crudService->getCrudInstance( 'ns.gastro-kitchens' );

        foreach ($kitchenNames as $index => $name) {
            $kitchenCrudInstance->submitPreparedRequest([
                'name'              =>  $name,
                'general'           =>  [
                    'status'                =>  'enabled',
                    'notification_status'   =>  'enabled',
                    'categories'            =>  count($categories) > 0 ? array_chunk($categories, count($categories) / 3)[$index] : [],
                ],
            ]);
        }
    }

    public function createTables(CrudService $crudService)
    {
        $kitchenNames = [__m('Hall Area', 'NsGastro'), __m('Stage 1', 'NsGastro'), __m('Outside', 'NsGastro')];
        $areaCrudInstance       =   $crudService->getCrudInstance( 'ns.gastro-areas' );
        $tableCrudInstance      =   $crudService->getCrudInstance( 'ns.gastro-tables' );
        $this->faker = Factory::create();

        foreach ($kitchenNames as $index => $name) {
            $areaCrudInstance->submitPreparedRequest([
                'name'              =>  $name,
            ]);

            $area = Area::orderBy('id', 'desc')->first();

            for ($i = 1; $i <= 5; $i++) {
                $tableCrudInstance->submitPreparedRequest([
                    'name'              =>  $area->name.' Table '.$i,
                    'general'           =>  [
                        'area_id'               =>  $area->id,
                        'status'                =>  'available',
                        'busy'                  =>  false,
                        'allow_multi_clients'   =>  Arr::random([true, false]),
                        'seats'                 =>  $this->faker->randomElement([5, 10, 15, 20, 25, 30]),
                    ],
                ]);
            }
        }
    }
}
