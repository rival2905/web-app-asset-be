<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        Asset::create([
            'name' => 'Meja',
            'size' => '150x60 cm',
            'item_code' => 'AST-001',
            'stock' => 10,
            'description' => 'Meja kantor',
            'asset_type_id' => 1,
            'asset_material_id' => 1,
            'brand_id' => 1,
        ]);

        Asset::create([
            'name' => 'Kursi',
            'size' => 'Standard',
            'item_code' => 'AST-002',
            'stock' => 20,
            'description' => 'Kursi kantor',
            'asset_type_id' => 2,
            'asset_material_id' => 2,
            'brand_id' => 2,
        ]);
    }
}
