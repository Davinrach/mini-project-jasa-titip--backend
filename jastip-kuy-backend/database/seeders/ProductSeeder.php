<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('data/products.json'));

        if (!$json) {
            dd('File tidak ditemukan atau gagal dibaca.');
        }

        $products = json_decode($json, true);

        if (is_null($products)) {
            dd('Gagal decode JSON');
        }

        foreach ($products as $item) {
            Product::create([
                'id' => Str::uuid(),
                'name' => $item['nama'],
                'price' => (int) $item['harga'],
                'kategori' => $item['kategori'],
                'image' => $item['gambar'],
                'waktu_scraping' => $item['waktu_scraping'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

