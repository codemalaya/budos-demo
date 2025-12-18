<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan ayam, telur, dan sayuran.',
                'base_price' => 18000,
                'variants' => [
                    ['name' => 'Reguler', 'price' => 18000, 'is_default' => true],
                    ['name' => 'Jumbo', 'price' => 25000],
                ],
            ],
            [
                'name' => 'Mie Ayam',
                'description' => 'Mie ayam gurih dengan pangsit.',
                'base_price' => 15000,
                'variants' => [
                    ['name' => 'Biasa', 'price' => 15000, 'is_default' => true],
                    ['name' => 'Pangsit', 'price' => 18000],
                ],
            ],
            [
                'name' => 'Es Teh Manis',
                'description' => 'Minuman segar pilihan.',
                'base_price' => 5000,
                'variants' => [
                    ['name' => 'Normal', 'price' => 5000, 'is_default' => true],
                    ['name' => 'Less Sugar', 'price' => 5000],
                ],
            ],
            [
                'name' => 'Ayam Bakar',
                'description' => 'Ayam bakar bumbu khas.',
                'base_price' => 22000,
                'variants' => [
                    ['name' => 'Paha', 'price' => 22000, 'is_default' => true],
                    ['name' => 'Dada', 'price' => 23000],
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $menu = Menu::create([
                'name' => $menuData['name'],
                'slug' => Str::slug($menuData['name']),
                'description' => $menuData['description'],
                'base_price' => $menuData['base_price'],
                'is_active' => true,
            ]);

            foreach ($menuData['variants'] as $variantData) {
                $menu->variants()->create([
                    'name' => $variantData['name'],
                    'price' => $variantData['price'],
                    'is_default' => $variantData['is_default'] ?? false,
                    'is_active' => true,
                ]);
            }
        }
    }
}
