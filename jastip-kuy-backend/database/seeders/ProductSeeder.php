<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::insert([
            [
                'id' => Str::uuid(),
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan ayam, telur, dan kerupuk.',
                'price' => 15000,
                'image' => 'nasi-goreng.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Mie Ayam Bakso',
                'description' => 'Mie ayam lengkap dengan 4 bakso dan pangsit.',
                'price' => 18000,
                'image' => 'mie-ayam.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

