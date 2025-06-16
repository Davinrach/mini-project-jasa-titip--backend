<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportGacoanMenu extends Command
{

    protected $signature = 'import:gacoan-menu';
    protected $description = 'Import menu dari file CSV ke tabel products';

    
    public function handle()
    {
                $path = storage_path('app/menu_gacoan_1.csv');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return;
        }

        $file = fopen($path, 'r');
        $header = fgetcsv($file);

        while ($row = fgetcsv($file)) {
            Product::create([
                'id' => Str::uuid(),
                'name' => $row[0],
                'price' => str_replace(['Rp', '.', ','], ['', '', '.'], $row[1]), // Bersihkan format harga
                'description' => $row[2],
                'image' => null, // bisa diisi nanti
            ]);
        }

        fclose($file);

        $this->info("Data berhasil diimpor dari menu_grabfood.csv");
    }
    }

